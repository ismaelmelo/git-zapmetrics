<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "pargeraisinfo.php" ?>
<?php include_once "organizacaoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$pargerais_edit = NULL; // Initialize page object first

class cpargerais_edit extends cpargerais {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'pargerais';

	// Page object name
	var $PageObjName = 'pargerais_edit';

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

		// Table object (pargerais)
		if (!isset($GLOBALS["pargerais"])) {
			$GLOBALS["pargerais"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["pargerais"];
		}

		// Table object (organizacao)
		if (!isset($GLOBALS['organizacao'])) $GLOBALS['organizacao'] = new corganizacao();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'pargerais', TRUE);

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
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("pargeraislist.php");
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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["nu_parametro"] <> "") {
			$this->nu_parametro->setQueryStringValue($_GET["nu_parametro"]);
		}

		// Set up master detail parameters
		$this->SetUpMasterParms();

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->nu_parametro->CurrentValue == "")
			$this->Page_Terminate("pargeraislist.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("pargeraislist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "pargeraisview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to View page directly
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
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

	// Get upload files
	function GetUploadFiles() {
		global $objForm;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_orgBase->FldIsDetailKey) {
			$this->nu_orgBase->setFormValue($objForm->GetValue("x_nu_orgBase"));
		}
		if (!$this->nu_area->FldIsDetailKey) {
			$this->nu_area->setFormValue($objForm->GetValue("x_nu_area"));
		}
		if (!$this->nu_usuarioRespAreaTi->FldIsDetailKey) {
			$this->nu_usuarioRespAreaTi->setFormValue($objForm->GetValue("x_nu_usuarioRespAreaTi"));
		}
		if (!$this->qt_horasMes->FldIsDetailKey) {
			$this->qt_horasMes->setFormValue($objForm->GetValue("x_qt_horasMes"));
		}
		if (!$this->nu_sistema->FldIsDetailKey) {
			$this->nu_sistema->setFormValue($objForm->GetValue("x_nu_sistema"));
		}
		if (!$this->dt_inicioOpSistema->FldIsDetailKey) {
			$this->dt_inicioOpSistema->setFormValue($objForm->GetValue("x_dt_inicioOpSistema"));
			$this->dt_inicioOpSistema->CurrentValue = ew_UnFormatDateTime($this->dt_inicioOpSistema->CurrentValue, 7);
		}
		if (!$this->tx_htmlHomeNaoLogado->FldIsDetailKey) {
			$this->tx_htmlHomeNaoLogado->setFormValue($objForm->GetValue("x_tx_htmlHomeNaoLogado"));
		}
		if (!$this->nu_orgMetricas->FldIsDetailKey) {
			$this->nu_orgMetricas->setFormValue($objForm->GetValue("x_nu_orgMetricas"));
		}
		if (!$this->nu_areaMetricas->FldIsDetailKey) {
			$this->nu_areaMetricas->setFormValue($objForm->GetValue("x_nu_areaMetricas"));
		}
		if (!$this->nu_fornMetricas->FldIsDetailKey) {
			$this->nu_fornMetricas->setFormValue($objForm->GetValue("x_nu_fornMetricas"));
		}
		if (!$this->no_areaMetricas->FldIsDetailKey) {
			$this->no_areaMetricas->setFormValue($objForm->GetValue("x_no_areaMetricas"));
		}
		if (!$this->nu_modeloMetricasPadrao->FldIsDetailKey) {
			$this->nu_modeloMetricasPadrao->setFormValue($objForm->GetValue("x_nu_modeloMetricasPadrao"));
		}
		if (!$this->nu_areaVincEscritProj->FldIsDetailKey) {
			$this->nu_areaVincEscritProj->setFormValue($objForm->GetValue("x_nu_areaVincEscritProj"));
		}
		if (!$this->no_areaEscritProj->FldIsDetailKey) {
			$this->no_areaEscritProj->setFormValue($objForm->GetValue("x_no_areaEscritProj"));
		}
		if (!$this->nu_fornecedorAuditoria->FldIsDetailKey) {
			$this->nu_fornecedorAuditoria->setFormValue($objForm->GetValue("x_nu_fornecedorAuditoria"));
		}
		if (!$this->nu_fornPadraoFsw->FldIsDetailKey) {
			$this->nu_fornPadraoFsw->setFormValue($objForm->GetValue("x_nu_fornPadraoFsw"));
		}
		if (!$this->nu_contFornPadraoFsw->FldIsDetailKey) {
			$this->nu_contFornPadraoFsw->setFormValue($objForm->GetValue("x_nu_contFornPadraoFsw"));
		}
		if (!$this->nu_itemContFornPadraoFsw->FldIsDetailKey) {
			$this->nu_itemContFornPadraoFsw->setFormValue($objForm->GetValue("x_nu_itemContFornPadraoFsw"));
		}
		if (!$this->nu_pesoProbRisco->FldIsDetailKey) {
			$this->nu_pesoProbRisco->setFormValue($objForm->GetValue("x_nu_pesoProbRisco"));
		}
		if (!$this->nu_pesoImpacRisco->FldIsDetailKey) {
			$this->nu_pesoImpacRisco->setFormValue($objForm->GetValue("x_nu_pesoImpacRisco"));
		}
		if (!$this->nu_parametro->FldIsDetailKey)
			$this->nu_parametro->setFormValue($objForm->GetValue("x_nu_parametro"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->nu_parametro->CurrentValue = $this->nu_parametro->FormValue;
		$this->nu_orgBase->CurrentValue = $this->nu_orgBase->FormValue;
		$this->nu_area->CurrentValue = $this->nu_area->FormValue;
		$this->nu_usuarioRespAreaTi->CurrentValue = $this->nu_usuarioRespAreaTi->FormValue;
		$this->qt_horasMes->CurrentValue = $this->qt_horasMes->FormValue;
		$this->nu_sistema->CurrentValue = $this->nu_sistema->FormValue;
		$this->dt_inicioOpSistema->CurrentValue = $this->dt_inicioOpSistema->FormValue;
		$this->dt_inicioOpSistema->CurrentValue = ew_UnFormatDateTime($this->dt_inicioOpSistema->CurrentValue, 7);
		$this->tx_htmlHomeNaoLogado->CurrentValue = $this->tx_htmlHomeNaoLogado->FormValue;
		$this->nu_orgMetricas->CurrentValue = $this->nu_orgMetricas->FormValue;
		$this->nu_areaMetricas->CurrentValue = $this->nu_areaMetricas->FormValue;
		$this->nu_fornMetricas->CurrentValue = $this->nu_fornMetricas->FormValue;
		$this->no_areaMetricas->CurrentValue = $this->no_areaMetricas->FormValue;
		$this->nu_modeloMetricasPadrao->CurrentValue = $this->nu_modeloMetricasPadrao->FormValue;
		$this->nu_areaVincEscritProj->CurrentValue = $this->nu_areaVincEscritProj->FormValue;
		$this->no_areaEscritProj->CurrentValue = $this->no_areaEscritProj->FormValue;
		$this->nu_fornecedorAuditoria->CurrentValue = $this->nu_fornecedorAuditoria->FormValue;
		$this->nu_fornPadraoFsw->CurrentValue = $this->nu_fornPadraoFsw->FormValue;
		$this->nu_contFornPadraoFsw->CurrentValue = $this->nu_contFornPadraoFsw->FormValue;
		$this->nu_itemContFornPadraoFsw->CurrentValue = $this->nu_itemContFornPadraoFsw->FormValue;
		$this->nu_pesoProbRisco->CurrentValue = $this->nu_pesoProbRisco->FormValue;
		$this->nu_pesoImpacRisco->CurrentValue = $this->nu_pesoImpacRisco->FormValue;
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
		$this->nu_parametro->setDbValue($rs->fields('nu_parametro'));
		$this->nu_orgBase->setDbValue($rs->fields('nu_orgBase'));
		$this->nu_area->setDbValue($rs->fields('nu_area'));
		$this->nu_usuarioRespAreaTi->setDbValue($rs->fields('nu_usuarioRespAreaTi'));
		if (array_key_exists('EV__nu_usuarioRespAreaTi', $rs->fields)) {
			$this->nu_usuarioRespAreaTi->VirtualValue = $rs->fields('EV__nu_usuarioRespAreaTi'); // Set up virtual field value
		} else {
			$this->nu_usuarioRespAreaTi->VirtualValue = ""; // Clear value
		}
		$this->qt_horasMes->setDbValue($rs->fields('qt_horasMes'));
		$this->nu_sistema->setDbValue($rs->fields('nu_sistema'));
		$this->dt_inicioOpSistema->setDbValue($rs->fields('dt_inicioOpSistema'));
		$this->tx_htmlHomeNaoLogado->setDbValue($rs->fields('tx_htmlHomeNaoLogado'));
		$this->nu_orgMetricas->setDbValue($rs->fields('nu_orgMetricas'));
		$this->nu_areaMetricas->setDbValue($rs->fields('nu_areaMetricas'));
		if (array_key_exists('EV__nu_areaMetricas', $rs->fields)) {
			$this->nu_areaMetricas->VirtualValue = $rs->fields('EV__nu_areaMetricas'); // Set up virtual field value
		} else {
			$this->nu_areaMetricas->VirtualValue = ""; // Clear value
		}
		$this->nu_fornMetricas->setDbValue($rs->fields('nu_fornMetricas'));
		$this->no_areaMetricas->setDbValue($rs->fields('no_areaMetricas'));
		$this->nu_modeloMetricasPadrao->setDbValue($rs->fields('nu_modeloMetricasPadrao'));
		if (array_key_exists('EV__nu_modeloMetricasPadrao', $rs->fields)) {
			$this->nu_modeloMetricasPadrao->VirtualValue = $rs->fields('EV__nu_modeloMetricasPadrao'); // Set up virtual field value
		} else {
			$this->nu_modeloMetricasPadrao->VirtualValue = ""; // Clear value
		}
		$this->nu_areaVincEscritProj->setDbValue($rs->fields('nu_areaVincEscritProj'));
		$this->no_areaEscritProj->setDbValue($rs->fields('no_areaEscritProj'));
		$this->nu_fornecedorAuditoria->setDbValue($rs->fields('nu_fornecedorAuditoria'));
		$this->nu_fornPadraoFsw->setDbValue($rs->fields('nu_fornPadraoFsw'));
		if (array_key_exists('EV__nu_fornPadraoFsw', $rs->fields)) {
			$this->nu_fornPadraoFsw->VirtualValue = $rs->fields('EV__nu_fornPadraoFsw'); // Set up virtual field value
		} else {
			$this->nu_fornPadraoFsw->VirtualValue = ""; // Clear value
		}
		$this->nu_contFornPadraoFsw->setDbValue($rs->fields('nu_contFornPadraoFsw'));
		if (array_key_exists('EV__nu_contFornPadraoFsw', $rs->fields)) {
			$this->nu_contFornPadraoFsw->VirtualValue = $rs->fields('EV__nu_contFornPadraoFsw'); // Set up virtual field value
		} else {
			$this->nu_contFornPadraoFsw->VirtualValue = ""; // Clear value
		}
		$this->nu_itemContFornPadraoFsw->setDbValue($rs->fields('nu_itemContFornPadraoFsw'));
		$this->nu_pesoProbRisco->setDbValue($rs->fields('nu_pesoProbRisco'));
		$this->nu_pesoImpacRisco->setDbValue($rs->fields('nu_pesoImpacRisco'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_parametro->DbValue = $row['nu_parametro'];
		$this->nu_orgBase->DbValue = $row['nu_orgBase'];
		$this->nu_area->DbValue = $row['nu_area'];
		$this->nu_usuarioRespAreaTi->DbValue = $row['nu_usuarioRespAreaTi'];
		$this->qt_horasMes->DbValue = $row['qt_horasMes'];
		$this->nu_sistema->DbValue = $row['nu_sistema'];
		$this->dt_inicioOpSistema->DbValue = $row['dt_inicioOpSistema'];
		$this->tx_htmlHomeNaoLogado->DbValue = $row['tx_htmlHomeNaoLogado'];
		$this->nu_orgMetricas->DbValue = $row['nu_orgMetricas'];
		$this->nu_areaMetricas->DbValue = $row['nu_areaMetricas'];
		$this->nu_fornMetricas->DbValue = $row['nu_fornMetricas'];
		$this->no_areaMetricas->DbValue = $row['no_areaMetricas'];
		$this->nu_modeloMetricasPadrao->DbValue = $row['nu_modeloMetricasPadrao'];
		$this->nu_areaVincEscritProj->DbValue = $row['nu_areaVincEscritProj'];
		$this->no_areaEscritProj->DbValue = $row['no_areaEscritProj'];
		$this->nu_fornecedorAuditoria->DbValue = $row['nu_fornecedorAuditoria'];
		$this->nu_fornPadraoFsw->DbValue = $row['nu_fornPadraoFsw'];
		$this->nu_contFornPadraoFsw->DbValue = $row['nu_contFornPadraoFsw'];
		$this->nu_itemContFornPadraoFsw->DbValue = $row['nu_itemContFornPadraoFsw'];
		$this->nu_pesoProbRisco->DbValue = $row['nu_pesoProbRisco'];
		$this->nu_pesoImpacRisco->DbValue = $row['nu_pesoImpacRisco'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_parametro
		// nu_orgBase
		// nu_area
		// nu_usuarioRespAreaTi
		// qt_horasMes
		// nu_sistema
		// dt_inicioOpSistema
		// tx_htmlHomeNaoLogado
		// nu_orgMetricas
		// nu_areaMetricas
		// nu_fornMetricas
		// no_areaMetricas
		// nu_modeloMetricasPadrao
		// nu_areaVincEscritProj
		// no_areaEscritProj
		// nu_fornecedorAuditoria
		// nu_fornPadraoFsw
		// nu_contFornPadraoFsw
		// nu_itemContFornPadraoFsw
		// nu_pesoProbRisco
		// nu_pesoImpacRisco

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_orgBase
			if (strval($this->nu_orgBase->CurrentValue) <> "") {
				$sFilterWrk = "[nu_organizacao]" . ew_SearchString("=", $this->nu_orgBase->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_organizacao], [no_organizacao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[organizacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_orgBase, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_organizacao] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_orgBase->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_orgBase->ViewValue = $this->nu_orgBase->CurrentValue;
				}
			} else {
				$this->nu_orgBase->ViewValue = NULL;
			}
			$this->nu_orgBase->ViewCustomAttributes = "";

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

			// nu_usuarioRespAreaTi
			if ($this->nu_usuarioRespAreaTi->VirtualValue <> "") {
				$this->nu_usuarioRespAreaTi->ViewValue = $this->nu_usuarioRespAreaTi->VirtualValue;
			} else {
			if (strval($this->nu_usuarioRespAreaTi->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioRespAreaTi->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioRespAreaTi, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_usuario] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioRespAreaTi->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioRespAreaTi->ViewValue = $this->nu_usuarioRespAreaTi->CurrentValue;
				}
			} else {
				$this->nu_usuarioRespAreaTi->ViewValue = NULL;
			}
			}
			$this->nu_usuarioRespAreaTi->ViewCustomAttributes = "";

			// qt_horasMes
			$this->qt_horasMes->ViewValue = $this->qt_horasMes->CurrentValue;
			$this->qt_horasMes->ViewCustomAttributes = "";

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

			// dt_inicioOpSistema
			$this->dt_inicioOpSistema->ViewValue = $this->dt_inicioOpSistema->CurrentValue;
			$this->dt_inicioOpSistema->ViewValue = ew_FormatDateTime($this->dt_inicioOpSistema->ViewValue, 7);
			$this->dt_inicioOpSistema->ViewCustomAttributes = "";

			// tx_htmlHomeNaoLogado
			$this->tx_htmlHomeNaoLogado->ViewValue = $this->tx_htmlHomeNaoLogado->CurrentValue;
			$this->tx_htmlHomeNaoLogado->ViewCustomAttributes = "";

			// nu_orgMetricas
			if (strval($this->nu_orgMetricas->CurrentValue) <> "") {
				$sFilterWrk = "[nu_organizacao]" . ew_SearchString("=", $this->nu_orgMetricas->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_organizacao], [no_organizacao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[organizacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_orgMetricas, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_organizacao] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_orgMetricas->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_orgMetricas->ViewValue = $this->nu_orgMetricas->CurrentValue;
				}
			} else {
				$this->nu_orgMetricas->ViewValue = NULL;
			}
			$this->nu_orgMetricas->ViewCustomAttributes = "";

			// nu_areaMetricas
			if ($this->nu_areaMetricas->VirtualValue <> "") {
				$this->nu_areaMetricas->ViewValue = $this->nu_areaMetricas->VirtualValue;
			} else {
			if (strval($this->nu_areaMetricas->CurrentValue) <> "") {
				$sFilterWrk = "[nu_area]" . ew_SearchString("=", $this->nu_areaMetricas->CurrentValue, EW_DATATYPE_NUMBER);
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
			$this->Lookup_Selecting($this->nu_areaMetricas, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_area] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_areaMetricas->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_areaMetricas->ViewValue = $this->nu_areaMetricas->CurrentValue;
				}
			} else {
				$this->nu_areaMetricas->ViewValue = NULL;
			}
			}
			$this->nu_areaMetricas->ViewCustomAttributes = "";

			// nu_fornMetricas
			if (strval($this->nu_fornMetricas->CurrentValue) <> "") {
				$sFilterWrk = "[nu_fornecedor]" . ew_SearchString("=", $this->nu_fornMetricas->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_fornecedor], [no_fornecedor] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[fornecedor]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_fornMetricas, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_fornecedor] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_fornMetricas->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_fornMetricas->ViewValue = $this->nu_fornMetricas->CurrentValue;
				}
			} else {
				$this->nu_fornMetricas->ViewValue = NULL;
			}
			$this->nu_fornMetricas->ViewCustomAttributes = "";

			// no_areaMetricas
			$this->no_areaMetricas->ViewValue = $this->no_areaMetricas->CurrentValue;
			$this->no_areaMetricas->ViewCustomAttributes = "";

			// nu_modeloMetricasPadrao
			if ($this->nu_modeloMetricasPadrao->VirtualValue <> "") {
				$this->nu_modeloMetricasPadrao->ViewValue = $this->nu_modeloMetricasPadrao->VirtualValue;
			} else {
			if (strval($this->nu_modeloMetricasPadrao->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpMetrica]" . ew_SearchString("=", $this->nu_modeloMetricasPadrao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpMetrica], [no_tpMetrica] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpmetrica]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_modeloMetricasPadrao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpMetrica] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_modeloMetricasPadrao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_modeloMetricasPadrao->ViewValue = $this->nu_modeloMetricasPadrao->CurrentValue;
				}
			} else {
				$this->nu_modeloMetricasPadrao->ViewValue = NULL;
			}
			}
			$this->nu_modeloMetricasPadrao->ViewCustomAttributes = "";

			// nu_areaVincEscritProj
			if (strval($this->nu_areaVincEscritProj->CurrentValue) <> "") {
				$sFilterWrk = "[nu_area]" . ew_SearchString("=", $this->nu_areaVincEscritProj->CurrentValue, EW_DATATYPE_NUMBER);
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
			$this->Lookup_Selecting($this->nu_areaVincEscritProj, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_area] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_areaVincEscritProj->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_areaVincEscritProj->ViewValue = $this->nu_areaVincEscritProj->CurrentValue;
				}
			} else {
				$this->nu_areaVincEscritProj->ViewValue = NULL;
			}
			$this->nu_areaVincEscritProj->ViewCustomAttributes = "";

			// no_areaEscritProj
			$this->no_areaEscritProj->ViewValue = $this->no_areaEscritProj->CurrentValue;
			$this->no_areaEscritProj->ViewCustomAttributes = "";

			// nu_fornecedorAuditoria
			if (strval($this->nu_fornecedorAuditoria->CurrentValue) <> "") {
				$sFilterWrk = "[nu_fornecedor]" . ew_SearchString("=", $this->nu_fornecedorAuditoria->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_fornecedor], [no_fornecedor] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[fornecedor]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_fornecedorAuditoria, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_fornecedor] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_fornecedorAuditoria->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_fornecedorAuditoria->ViewValue = $this->nu_fornecedorAuditoria->CurrentValue;
				}
			} else {
				$this->nu_fornecedorAuditoria->ViewValue = NULL;
			}
			$this->nu_fornecedorAuditoria->ViewCustomAttributes = "";

			// nu_fornPadraoFsw
			if ($this->nu_fornPadraoFsw->VirtualValue <> "") {
				$this->nu_fornPadraoFsw->ViewValue = $this->nu_fornPadraoFsw->VirtualValue;
			} else {
			if (strval($this->nu_fornPadraoFsw->CurrentValue) <> "") {
				$sFilterWrk = "[nu_fornecedor]" . ew_SearchString("=", $this->nu_fornPadraoFsw->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_fornecedor], [no_fornecedor] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[fornecedor]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_fornPadraoFsw, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_fornPadraoFsw->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_fornPadraoFsw->ViewValue = $this->nu_fornPadraoFsw->CurrentValue;
				}
			} else {
				$this->nu_fornPadraoFsw->ViewValue = NULL;
			}
			}
			$this->nu_fornPadraoFsw->ViewCustomAttributes = "";

			// nu_contFornPadraoFsw
			if ($this->nu_contFornPadraoFsw->VirtualValue <> "") {
				$this->nu_contFornPadraoFsw->ViewValue = $this->nu_contFornPadraoFsw->VirtualValue;
			} else {
			if (strval($this->nu_contFornPadraoFsw->CurrentValue) <> "") {
				$sFilterWrk = "[nu_contrato]" . ew_SearchString("=", $this->nu_contFornPadraoFsw->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_contrato], [no_contrato] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[contrato]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_contFornPadraoFsw, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_contrato] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_contFornPadraoFsw->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_contFornPadraoFsw->ViewValue = $this->nu_contFornPadraoFsw->CurrentValue;
				}
			} else {
				$this->nu_contFornPadraoFsw->ViewValue = NULL;
			}
			}
			$this->nu_contFornPadraoFsw->ViewCustomAttributes = "";

			// nu_itemContFornPadraoFsw
			if (strval($this->nu_itemContFornPadraoFsw->CurrentValue) <> "") {
				$sFilterWrk = "[nu_itemContratado]" . ew_SearchString("=", $this->nu_itemContFornPadraoFsw->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_itemContratado], [no_itemContratado] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[item_contratado]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_itemContFornPadraoFsw, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_itemContratado] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_itemContFornPadraoFsw->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_itemContFornPadraoFsw->ViewValue = $this->nu_itemContFornPadraoFsw->CurrentValue;
				}
			} else {
				$this->nu_itemContFornPadraoFsw->ViewValue = NULL;
			}
			$this->nu_itemContFornPadraoFsw->ViewCustomAttributes = "";

			// nu_pesoProbRisco
			$this->nu_pesoProbRisco->ViewValue = $this->nu_pesoProbRisco->CurrentValue;
			$this->nu_pesoProbRisco->ViewCustomAttributes = "";

			// nu_pesoImpacRisco
			$this->nu_pesoImpacRisco->ViewValue = $this->nu_pesoImpacRisco->CurrentValue;
			$this->nu_pesoImpacRisco->ViewCustomAttributes = "";

			// nu_orgBase
			$this->nu_orgBase->LinkCustomAttributes = "";
			$this->nu_orgBase->HrefValue = "";
			$this->nu_orgBase->TooltipValue = "";

			// nu_area
			$this->nu_area->LinkCustomAttributes = "";
			$this->nu_area->HrefValue = "";
			$this->nu_area->TooltipValue = "";

			// nu_usuarioRespAreaTi
			$this->nu_usuarioRespAreaTi->LinkCustomAttributes = "";
			$this->nu_usuarioRespAreaTi->HrefValue = "";
			$this->nu_usuarioRespAreaTi->TooltipValue = "";

			// qt_horasMes
			$this->qt_horasMes->LinkCustomAttributes = "";
			$this->qt_horasMes->HrefValue = "";
			$this->qt_horasMes->TooltipValue = "";

			// nu_sistema
			$this->nu_sistema->LinkCustomAttributes = "";
			$this->nu_sistema->HrefValue = "";
			$this->nu_sistema->TooltipValue = "";

			// dt_inicioOpSistema
			$this->dt_inicioOpSistema->LinkCustomAttributes = "";
			$this->dt_inicioOpSistema->HrefValue = "";
			$this->dt_inicioOpSistema->TooltipValue = "";

			// tx_htmlHomeNaoLogado
			$this->tx_htmlHomeNaoLogado->LinkCustomAttributes = "";
			$this->tx_htmlHomeNaoLogado->HrefValue = "";
			$this->tx_htmlHomeNaoLogado->TooltipValue = "";

			// nu_orgMetricas
			$this->nu_orgMetricas->LinkCustomAttributes = "";
			$this->nu_orgMetricas->HrefValue = "";
			$this->nu_orgMetricas->TooltipValue = "";

			// nu_areaMetricas
			$this->nu_areaMetricas->LinkCustomAttributes = "";
			$this->nu_areaMetricas->HrefValue = "";
			$this->nu_areaMetricas->TooltipValue = "";

			// nu_fornMetricas
			$this->nu_fornMetricas->LinkCustomAttributes = "";
			$this->nu_fornMetricas->HrefValue = "";
			$this->nu_fornMetricas->TooltipValue = "";

			// no_areaMetricas
			$this->no_areaMetricas->LinkCustomAttributes = "";
			$this->no_areaMetricas->HrefValue = "";
			$this->no_areaMetricas->TooltipValue = "";

			// nu_modeloMetricasPadrao
			$this->nu_modeloMetricasPadrao->LinkCustomAttributes = "";
			$this->nu_modeloMetricasPadrao->HrefValue = "";
			$this->nu_modeloMetricasPadrao->TooltipValue = "";

			// nu_areaVincEscritProj
			$this->nu_areaVincEscritProj->LinkCustomAttributes = "";
			$this->nu_areaVincEscritProj->HrefValue = "";
			$this->nu_areaVincEscritProj->TooltipValue = "";

			// no_areaEscritProj
			$this->no_areaEscritProj->LinkCustomAttributes = "";
			$this->no_areaEscritProj->HrefValue = "";
			$this->no_areaEscritProj->TooltipValue = "";

			// nu_fornecedorAuditoria
			$this->nu_fornecedorAuditoria->LinkCustomAttributes = "";
			$this->nu_fornecedorAuditoria->HrefValue = "";
			$this->nu_fornecedorAuditoria->TooltipValue = "";

			// nu_fornPadraoFsw
			$this->nu_fornPadraoFsw->LinkCustomAttributes = "";
			$this->nu_fornPadraoFsw->HrefValue = "";
			$this->nu_fornPadraoFsw->TooltipValue = "";

			// nu_contFornPadraoFsw
			$this->nu_contFornPadraoFsw->LinkCustomAttributes = "";
			$this->nu_contFornPadraoFsw->HrefValue = "";
			$this->nu_contFornPadraoFsw->TooltipValue = "";

			// nu_itemContFornPadraoFsw
			$this->nu_itemContFornPadraoFsw->LinkCustomAttributes = "";
			$this->nu_itemContFornPadraoFsw->HrefValue = "";
			$this->nu_itemContFornPadraoFsw->TooltipValue = "";

			// nu_pesoProbRisco
			$this->nu_pesoProbRisco->LinkCustomAttributes = "";
			$this->nu_pesoProbRisco->HrefValue = "";
			$this->nu_pesoProbRisco->TooltipValue = "";

			// nu_pesoImpacRisco
			$this->nu_pesoImpacRisco->LinkCustomAttributes = "";
			$this->nu_pesoImpacRisco->HrefValue = "";
			$this->nu_pesoImpacRisco->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// nu_orgBase
			$this->nu_orgBase->EditCustomAttributes = "";
			if ($this->nu_orgBase->getSessionValue() <> "") {
				$this->nu_orgBase->CurrentValue = $this->nu_orgBase->getSessionValue();
			if (strval($this->nu_orgBase->CurrentValue) <> "") {
				$sFilterWrk = "[nu_organizacao]" . ew_SearchString("=", $this->nu_orgBase->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_organizacao], [no_organizacao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[organizacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_orgBase, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_organizacao] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_orgBase->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_orgBase->ViewValue = $this->nu_orgBase->CurrentValue;
				}
			} else {
				$this->nu_orgBase->ViewValue = NULL;
			}
			$this->nu_orgBase->ViewCustomAttributes = "";
			} else {
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_organizacao], [no_organizacao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[organizacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_orgBase, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_organizacao] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_orgBase->EditValue = $arwrk;
			}

			// nu_area
			$this->nu_area->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_area], [no_area] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [nu_organizacao] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[area]";
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

			// nu_usuarioRespAreaTi
			$this->nu_usuarioRespAreaTi->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if (!$GLOBALS["pargerais"]->UserIDAllow("edit")) $sWhereWrk = $GLOBALS["usuario"]->AddUserIDFilter($sWhereWrk);

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioRespAreaTi, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_usuario] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_usuarioRespAreaTi->EditValue = $arwrk;

			// qt_horasMes
			$this->qt_horasMes->EditCustomAttributes = "";
			$this->qt_horasMes->EditValue = ew_HtmlEncode($this->qt_horasMes->CurrentValue);
			$this->qt_horasMes->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_horasMes->FldCaption()));

			// nu_sistema
			$this->nu_sistema->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_sistema], [co_alternativo] AS [DispFld], [no_sistema] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[sistema]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_sistema, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_sistema->EditValue = $arwrk;

			// dt_inicioOpSistema
			$this->dt_inicioOpSistema->EditCustomAttributes = "";
			$this->dt_inicioOpSistema->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->dt_inicioOpSistema->CurrentValue, 7));
			$this->dt_inicioOpSistema->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dt_inicioOpSistema->FldCaption()));

			// tx_htmlHomeNaoLogado
			$this->tx_htmlHomeNaoLogado->EditCustomAttributes = "";
			$this->tx_htmlHomeNaoLogado->EditValue = $this->tx_htmlHomeNaoLogado->CurrentValue;
			$this->tx_htmlHomeNaoLogado->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->tx_htmlHomeNaoLogado->FldCaption()));

			// nu_orgMetricas
			$this->nu_orgMetricas->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_organizacao], [no_organizacao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[organizacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_orgMetricas, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_organizacao] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_orgMetricas->EditValue = $arwrk;

			// nu_areaMetricas
			$this->nu_areaMetricas->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_area], [no_area] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [nu_organizacao] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[area]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_areaMetricas, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_area] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_areaMetricas->EditValue = $arwrk;

			// nu_fornMetricas
			$this->nu_fornMetricas->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_fornecedor], [no_fornecedor] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[fornecedor]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_fornMetricas, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_fornecedor] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_fornMetricas->EditValue = $arwrk;

			// no_areaMetricas
			$this->no_areaMetricas->EditCustomAttributes = "";
			$this->no_areaMetricas->EditValue = ew_HtmlEncode($this->no_areaMetricas->CurrentValue);
			$this->no_areaMetricas->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_areaMetricas->FldCaption()));

			// nu_modeloMetricasPadrao
			$this->nu_modeloMetricasPadrao->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_tpMetrica], [no_tpMetrica] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[tpmetrica]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_modeloMetricasPadrao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpMetrica] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_modeloMetricasPadrao->EditValue = $arwrk;

			// nu_areaVincEscritProj
			$this->nu_areaVincEscritProj->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_area], [no_area] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [nu_organizacao] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[area]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_areaVincEscritProj, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_area] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_areaVincEscritProj->EditValue = $arwrk;

			// no_areaEscritProj
			$this->no_areaEscritProj->EditCustomAttributes = "";
			$this->no_areaEscritProj->EditValue = ew_HtmlEncode($this->no_areaEscritProj->CurrentValue);
			$this->no_areaEscritProj->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_areaEscritProj->FldCaption()));

			// nu_fornecedorAuditoria
			$this->nu_fornecedorAuditoria->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_fornecedor], [no_fornecedor] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[fornecedor]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_fornecedorAuditoria, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_fornecedor] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_fornecedorAuditoria->EditValue = $arwrk;

			// nu_fornPadraoFsw
			$this->nu_fornPadraoFsw->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_fornecedor], [no_fornecedor] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[fornecedor]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_fornPadraoFsw, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_fornPadraoFsw->EditValue = $arwrk;

			// nu_contFornPadraoFsw
			$this->nu_contFornPadraoFsw->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_contrato], [no_contrato] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [nu_fornecedor] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[contrato]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_contFornPadraoFsw, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_contrato] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_contFornPadraoFsw->EditValue = $arwrk;

			// nu_itemContFornPadraoFsw
			$this->nu_itemContFornPadraoFsw->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_itemContratado], [no_itemContratado] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [nu_contrato] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[item_contratado]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_itemContFornPadraoFsw, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_itemContratado] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_itemContFornPadraoFsw->EditValue = $arwrk;

			// nu_pesoProbRisco
			$this->nu_pesoProbRisco->EditCustomAttributes = "";
			$this->nu_pesoProbRisco->EditValue = ew_HtmlEncode($this->nu_pesoProbRisco->CurrentValue);
			$this->nu_pesoProbRisco->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_pesoProbRisco->FldCaption()));

			// nu_pesoImpacRisco
			$this->nu_pesoImpacRisco->EditCustomAttributes = "";
			$this->nu_pesoImpacRisco->EditValue = ew_HtmlEncode($this->nu_pesoImpacRisco->CurrentValue);
			$this->nu_pesoImpacRisco->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_pesoImpacRisco->FldCaption()));

			// Edit refer script
			// nu_orgBase

			$this->nu_orgBase->HrefValue = "";

			// nu_area
			$this->nu_area->HrefValue = "";

			// nu_usuarioRespAreaTi
			$this->nu_usuarioRespAreaTi->HrefValue = "";

			// qt_horasMes
			$this->qt_horasMes->HrefValue = "";

			// nu_sistema
			$this->nu_sistema->HrefValue = "";

			// dt_inicioOpSistema
			$this->dt_inicioOpSistema->HrefValue = "";

			// tx_htmlHomeNaoLogado
			$this->tx_htmlHomeNaoLogado->HrefValue = "";

			// nu_orgMetricas
			$this->nu_orgMetricas->HrefValue = "";

			// nu_areaMetricas
			$this->nu_areaMetricas->HrefValue = "";

			// nu_fornMetricas
			$this->nu_fornMetricas->HrefValue = "";

			// no_areaMetricas
			$this->no_areaMetricas->HrefValue = "";

			// nu_modeloMetricasPadrao
			$this->nu_modeloMetricasPadrao->HrefValue = "";

			// nu_areaVincEscritProj
			$this->nu_areaVincEscritProj->HrefValue = "";

			// no_areaEscritProj
			$this->no_areaEscritProj->HrefValue = "";

			// nu_fornecedorAuditoria
			$this->nu_fornecedorAuditoria->HrefValue = "";

			// nu_fornPadraoFsw
			$this->nu_fornPadraoFsw->HrefValue = "";

			// nu_contFornPadraoFsw
			$this->nu_contFornPadraoFsw->HrefValue = "";

			// nu_itemContFornPadraoFsw
			$this->nu_itemContFornPadraoFsw->HrefValue = "";

			// nu_pesoProbRisco
			$this->nu_pesoProbRisco->HrefValue = "";

			// nu_pesoImpacRisco
			$this->nu_pesoImpacRisco->HrefValue = "";
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
		if (!$this->nu_orgBase->FldIsDetailKey && !is_null($this->nu_orgBase->FormValue) && $this->nu_orgBase->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_orgBase->FldCaption());
		}
		if (!$this->nu_area->FldIsDetailKey && !is_null($this->nu_area->FormValue) && $this->nu_area->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_area->FldCaption());
		}
		if (!$this->nu_usuarioRespAreaTi->FldIsDetailKey && !is_null($this->nu_usuarioRespAreaTi->FormValue) && $this->nu_usuarioRespAreaTi->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_usuarioRespAreaTi->FldCaption());
		}
		if (!$this->qt_horasMes->FldIsDetailKey && !is_null($this->qt_horasMes->FormValue) && $this->qt_horasMes->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->qt_horasMes->FldCaption());
		}
		if (!ew_CheckInteger($this->qt_horasMes->FormValue)) {
			ew_AddMessage($gsFormError, $this->qt_horasMes->FldErrMsg());
		}
		if (!$this->nu_sistema->FldIsDetailKey && !is_null($this->nu_sistema->FormValue) && $this->nu_sistema->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_sistema->FldCaption());
		}
		if (!$this->dt_inicioOpSistema->FldIsDetailKey && !is_null($this->dt_inicioOpSistema->FormValue) && $this->dt_inicioOpSistema->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->dt_inicioOpSistema->FldCaption());
		}
		if (!ew_CheckEuroDate($this->dt_inicioOpSistema->FormValue)) {
			ew_AddMessage($gsFormError, $this->dt_inicioOpSistema->FldErrMsg());
		}
		if (!$this->tx_htmlHomeNaoLogado->FldIsDetailKey && !is_null($this->tx_htmlHomeNaoLogado->FormValue) && $this->tx_htmlHomeNaoLogado->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->tx_htmlHomeNaoLogado->FldCaption());
		}
		if (!$this->nu_orgMetricas->FldIsDetailKey && !is_null($this->nu_orgMetricas->FormValue) && $this->nu_orgMetricas->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_orgMetricas->FldCaption());
		}
		if (!$this->nu_areaMetricas->FldIsDetailKey && !is_null($this->nu_areaMetricas->FormValue) && $this->nu_areaMetricas->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_areaMetricas->FldCaption());
		}
		if (!$this->no_areaMetricas->FldIsDetailKey && !is_null($this->no_areaMetricas->FormValue) && $this->no_areaMetricas->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_areaMetricas->FldCaption());
		}
		if (!$this->nu_areaVincEscritProj->FldIsDetailKey && !is_null($this->nu_areaVincEscritProj->FormValue) && $this->nu_areaVincEscritProj->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_areaVincEscritProj->FldCaption());
		}
		if (!$this->no_areaEscritProj->FldIsDetailKey && !is_null($this->no_areaEscritProj->FormValue) && $this->no_areaEscritProj->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_areaEscritProj->FldCaption());
		}
		if (!ew_CheckInteger($this->nu_pesoProbRisco->FormValue)) {
			ew_AddMessage($gsFormError, $this->nu_pesoProbRisco->FldErrMsg());
		}
		if (!ew_CheckInteger($this->nu_pesoImpacRisco->FormValue)) {
			ew_AddMessage($gsFormError, $this->nu_pesoImpacRisco->FldErrMsg());
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

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// nu_orgBase
			$this->nu_orgBase->SetDbValueDef($rsnew, $this->nu_orgBase->CurrentValue, NULL, $this->nu_orgBase->ReadOnly);

			// nu_area
			$this->nu_area->SetDbValueDef($rsnew, $this->nu_area->CurrentValue, NULL, $this->nu_area->ReadOnly);

			// nu_usuarioRespAreaTi
			$this->nu_usuarioRespAreaTi->SetDbValueDef($rsnew, $this->nu_usuarioRespAreaTi->CurrentValue, NULL, $this->nu_usuarioRespAreaTi->ReadOnly);

			// qt_horasMes
			$this->qt_horasMes->SetDbValueDef($rsnew, $this->qt_horasMes->CurrentValue, NULL, $this->qt_horasMes->ReadOnly);

			// nu_sistema
			$this->nu_sistema->SetDbValueDef($rsnew, $this->nu_sistema->CurrentValue, NULL, $this->nu_sistema->ReadOnly);

			// dt_inicioOpSistema
			$this->dt_inicioOpSistema->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->dt_inicioOpSistema->CurrentValue, 7), NULL, $this->dt_inicioOpSistema->ReadOnly);

			// tx_htmlHomeNaoLogado
			$this->tx_htmlHomeNaoLogado->SetDbValueDef($rsnew, $this->tx_htmlHomeNaoLogado->CurrentValue, NULL, $this->tx_htmlHomeNaoLogado->ReadOnly);

			// nu_orgMetricas
			$this->nu_orgMetricas->SetDbValueDef($rsnew, $this->nu_orgMetricas->CurrentValue, NULL, $this->nu_orgMetricas->ReadOnly);

			// nu_areaMetricas
			$this->nu_areaMetricas->SetDbValueDef($rsnew, $this->nu_areaMetricas->CurrentValue, NULL, $this->nu_areaMetricas->ReadOnly);

			// nu_fornMetricas
			$this->nu_fornMetricas->SetDbValueDef($rsnew, $this->nu_fornMetricas->CurrentValue, NULL, $this->nu_fornMetricas->ReadOnly);

			// no_areaMetricas
			$this->no_areaMetricas->SetDbValueDef($rsnew, $this->no_areaMetricas->CurrentValue, NULL, $this->no_areaMetricas->ReadOnly);

			// nu_modeloMetricasPadrao
			$this->nu_modeloMetricasPadrao->SetDbValueDef($rsnew, $this->nu_modeloMetricasPadrao->CurrentValue, NULL, $this->nu_modeloMetricasPadrao->ReadOnly);

			// nu_areaVincEscritProj
			$this->nu_areaVincEscritProj->SetDbValueDef($rsnew, $this->nu_areaVincEscritProj->CurrentValue, NULL, $this->nu_areaVincEscritProj->ReadOnly);

			// no_areaEscritProj
			$this->no_areaEscritProj->SetDbValueDef($rsnew, $this->no_areaEscritProj->CurrentValue, NULL, $this->no_areaEscritProj->ReadOnly);

			// nu_fornecedorAuditoria
			$this->nu_fornecedorAuditoria->SetDbValueDef($rsnew, $this->nu_fornecedorAuditoria->CurrentValue, NULL, $this->nu_fornecedorAuditoria->ReadOnly);

			// nu_fornPadraoFsw
			$this->nu_fornPadraoFsw->SetDbValueDef($rsnew, $this->nu_fornPadraoFsw->CurrentValue, NULL, $this->nu_fornPadraoFsw->ReadOnly);

			// nu_contFornPadraoFsw
			$this->nu_contFornPadraoFsw->SetDbValueDef($rsnew, $this->nu_contFornPadraoFsw->CurrentValue, NULL, $this->nu_contFornPadraoFsw->ReadOnly);

			// nu_itemContFornPadraoFsw
			$this->nu_itemContFornPadraoFsw->SetDbValueDef($rsnew, $this->nu_itemContFornPadraoFsw->CurrentValue, NULL, $this->nu_itemContFornPadraoFsw->ReadOnly);

			// nu_pesoProbRisco
			$this->nu_pesoProbRisco->SetDbValueDef($rsnew, $this->nu_pesoProbRisco->CurrentValue, NULL, $this->nu_pesoProbRisco->ReadOnly);

			// nu_pesoImpacRisco
			$this->nu_pesoImpacRisco->SetDbValueDef($rsnew, $this->nu_pesoImpacRisco->CurrentValue, NULL, $this->nu_pesoImpacRisco->ReadOnly);

			// Check referential integrity for master table 'organizacao'
			$bValidMasterRecord = TRUE;
			$sMasterFilter = $this->SqlMasterFilter_organizacao();
			$KeyValue = isset($rsnew['nu_orgBase']) ? $rsnew['nu_orgBase'] : $rsold['nu_orgBase'];
			if (strval($KeyValue) <> "") {
				$sMasterFilter = str_replace("@nu_organizacao@", ew_AdjustSql($KeyValue), $sMasterFilter);
			} else {
				$bValidMasterRecord = FALSE;
			}
			if ($bValidMasterRecord) {
				$rsmaster = $GLOBALS["organizacao"]->LoadRs($sMasterFilter);
				$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
				$rsmaster->Close();
			}
			if (!$bValidMasterRecord) {
				$sRelatedRecordMsg = str_replace("%t", "organizacao", $Language->Phrase("RelatedRecordRequired"));
				$this->setFailureMessage($sRelatedRecordMsg);
				$rs->Close();
				return FALSE;
			}

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = 'ew_ErrorFn';
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
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
			if ($sMasterTblVar == "organizacao") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_organizacao"] <> "") {
					$GLOBALS["organizacao"]->nu_organizacao->setQueryStringValue($_GET["nu_organizacao"]);
					$this->nu_orgBase->setQueryStringValue($GLOBALS["organizacao"]->nu_organizacao->QueryStringValue);
					$this->nu_orgBase->setSessionValue($this->nu_orgBase->QueryStringValue);
					if (!is_numeric($GLOBALS["organizacao"]->nu_organizacao->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "organizacao") {
				if ($this->nu_orgBase->QueryStringValue == "") $this->nu_orgBase->setSessionValue("");
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "pargeraislist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("edit");
		$Breadcrumb->Add("edit", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
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
if (!isset($pargerais_edit)) $pargerais_edit = new cpargerais_edit();

// Page init
$pargerais_edit->Page_Init();

// Page main
$pargerais_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pargerais_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var pargerais_edit = new ew_Page("pargerais_edit");
pargerais_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = pargerais_edit.PageID; // For backward compatibility

// Form object
var fpargeraisedit = new ew_Form("fpargeraisedit");

// Validate form
fpargeraisedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_orgBase");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($pargerais->nu_orgBase->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_area");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($pargerais->nu_area->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_usuarioRespAreaTi");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($pargerais->nu_usuarioRespAreaTi->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_qt_horasMes");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($pargerais->qt_horasMes->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_qt_horasMes");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pargerais->qt_horasMes->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nu_sistema");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($pargerais->nu_sistema->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dt_inicioOpSistema");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($pargerais->dt_inicioOpSistema->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dt_inicioOpSistema");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pargerais->dt_inicioOpSistema->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tx_htmlHomeNaoLogado");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($pargerais->tx_htmlHomeNaoLogado->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_orgMetricas");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($pargerais->nu_orgMetricas->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_areaMetricas");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($pargerais->nu_areaMetricas->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no_areaMetricas");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($pargerais->no_areaMetricas->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_areaVincEscritProj");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($pargerais->nu_areaVincEscritProj->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no_areaEscritProj");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($pargerais->no_areaEscritProj->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_pesoProbRisco");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pargerais->nu_pesoProbRisco->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nu_pesoImpacRisco");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pargerais->nu_pesoImpacRisco->FldErrMsg()) ?>");

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
fpargeraisedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpargeraisedit.ValidateRequired = true;
<?php } else { ?>
fpargeraisedit.ValidateRequired = false; 
<?php } ?>

// Multi-Page properties
fpargeraisedit.MultiPage = new ew_MultiPage("fpargeraisedit",
	[["x_nu_orgBase",1],["x_nu_area",1],["x_nu_usuarioRespAreaTi",1],["x_qt_horasMes",1],["x_nu_sistema",1],["x_dt_inicioOpSistema",1],["x_tx_htmlHomeNaoLogado",1],["x_nu_orgMetricas",4],["x_nu_areaMetricas",4],["x_nu_fornMetricas",4],["x_no_areaMetricas",4],["x_nu_modeloMetricasPadrao",4],["x_nu_areaVincEscritProj",4],["x_no_areaEscritProj",3],["x_nu_fornecedorAuditoria",5],["x_nu_fornPadraoFsw",2],["x_nu_contFornPadraoFsw",2],["x_nu_itemContFornPadraoFsw",2],["x_nu_pesoProbRisco",4],["x_nu_pesoImpacRisco",4]]
);

// Dynamic selection lists
fpargeraisedit.Lists["x_nu_orgBase"] = {"LinkField":"x_nu_organizacao","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_organizacao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fpargeraisedit.Lists["x_nu_area"] = {"LinkField":"x_nu_area","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_area","","",""],"ParentFields":["x_nu_orgBase"],"FilterFields":["x_nu_organizacao"],"Options":[]};
fpargeraisedit.Lists["x_nu_usuarioRespAreaTi"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fpargeraisedit.Lists["x_nu_sistema"] = {"LinkField":"x_nu_sistema","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_alternativo","x_no_sistema","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fpargeraisedit.Lists["x_nu_orgMetricas"] = {"LinkField":"x_nu_organizacao","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_organizacao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fpargeraisedit.Lists["x_nu_areaMetricas"] = {"LinkField":"x_nu_area","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_area","","",""],"ParentFields":["x_nu_orgMetricas"],"FilterFields":["x_nu_organizacao"],"Options":[]};
fpargeraisedit.Lists["x_nu_fornMetricas"] = {"LinkField":"x_nu_fornecedor","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_fornecedor","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fpargeraisedit.Lists["x_nu_modeloMetricasPadrao"] = {"LinkField":"x_nu_tpMetrica","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpMetrica","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fpargeraisedit.Lists["x_nu_areaVincEscritProj"] = {"LinkField":"x_nu_area","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_area","","",""],"ParentFields":["x_nu_orgBase"],"FilterFields":["x_nu_organizacao"],"Options":[]};
fpargeraisedit.Lists["x_nu_fornecedorAuditoria"] = {"LinkField":"x_nu_fornecedor","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_fornecedor","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fpargeraisedit.Lists["x_nu_fornPadraoFsw"] = {"LinkField":"x_nu_fornecedor","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_fornecedor","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fpargeraisedit.Lists["x_nu_contFornPadraoFsw"] = {"LinkField":"x_nu_contrato","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_contrato","","",""],"ParentFields":["x_nu_fornPadraoFsw"],"FilterFields":["x_nu_fornecedor"],"Options":[]};
fpargeraisedit.Lists["x_nu_itemContFornPadraoFsw"] = {"LinkField":"x_nu_itemContratado","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_itemContratado","","",""],"ParentFields":["x_nu_contFornPadraoFsw"],"FilterFields":["x_nu_contrato"],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $pargerais_edit->ShowPageHeader(); ?>
<?php
$pargerais_edit->ShowMessage();
?>
<form name="fpargeraisedit" id="fpargeraisedit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="pargerais">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table class="ewStdTable"><tbody><tr><td>
<div class="tabbable" id="pargerais_edit">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_pargerais1" data-toggle="tab"><?php echo $pargerais->PageCaption(1) ?></a></li>
		<li><a href="#tab_pargerais2" data-toggle="tab"><?php echo $pargerais->PageCaption(2) ?></a></li>
		<li><a href="#tab_pargerais3" data-toggle="tab"><?php echo $pargerais->PageCaption(3) ?></a></li>
		<li><a href="#tab_pargerais4" data-toggle="tab"><?php echo $pargerais->PageCaption(4) ?></a></li>
		<li><a href="#tab_pargerais5" data-toggle="tab"><?php echo $pargerais->PageCaption(5) ?></a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab_pargerais1">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_pargeraisedit1" class="table table-bordered table-striped">
<?php if ($pargerais->nu_orgBase->Visible) { // nu_orgBase ?>
	<tr id="r_nu_orgBase">
		<td><span id="elh_pargerais_nu_orgBase"><?php echo $pargerais->nu_orgBase->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $pargerais->nu_orgBase->CellAttributes() ?>>
<?php if ($pargerais->nu_orgBase->getSessionValue() <> "") { ?>
<span<?php echo $pargerais->nu_orgBase->ViewAttributes() ?>>
<?php echo $pargerais->nu_orgBase->ViewValue ?></span>
<input type="hidden" id="x_nu_orgBase" name="x_nu_orgBase" value="<?php echo ew_HtmlEncode($pargerais->nu_orgBase->CurrentValue) ?>">
<?php } else { ?>
<?php $pargerais->nu_orgBase->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_area','x_nu_areaVincEscritProj']); " . @$pargerais->nu_orgBase->EditAttrs["onchange"]; ?>
<select data-field="x_nu_orgBase" id="x_nu_orgBase" name="x_nu_orgBase"<?php echo $pargerais->nu_orgBase->EditAttributes() ?>>
<?php
if (is_array($pargerais->nu_orgBase->EditValue)) {
	$arwrk = $pargerais->nu_orgBase->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pargerais->nu_orgBase->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if (AllowAdd(CurrentProjectID() . "organizacao")) { ?>
&nbsp;<a id="aol_x_nu_orgBase" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_nu_orgBase',url:'organizacaoaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $pargerais->nu_orgBase->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
fpargeraisedit.Lists["x_nu_orgBase"].Options = <?php echo (is_array($pargerais->nu_orgBase->EditValue)) ? ew_ArrayToJson($pargerais->nu_orgBase->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<?php echo $pargerais->nu_orgBase->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($pargerais->nu_area->Visible) { // nu_area ?>
	<tr id="r_nu_area">
		<td><span id="elh_pargerais_nu_area"><?php echo $pargerais->nu_area->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $pargerais->nu_area->CellAttributes() ?>>
<span id="el_pargerais_nu_area" class="control-group">
<select data-field="x_nu_area" id="x_nu_area" name="x_nu_area"<?php echo $pargerais->nu_area->EditAttributes() ?>>
<?php
if (is_array($pargerais->nu_area->EditValue)) {
	$arwrk = $pargerais->nu_area->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pargerais->nu_area->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if (AllowAdd(CurrentProjectID() . "area")) { ?>
&nbsp;<a id="aol_x_nu_area" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_nu_area',url:'areaaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $pargerais->nu_area->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
fpargeraisedit.Lists["x_nu_area"].Options = <?php echo (is_array($pargerais->nu_area->EditValue)) ? ew_ArrayToJson($pargerais->nu_area->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $pargerais->nu_area->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($pargerais->nu_usuarioRespAreaTi->Visible) { // nu_usuarioRespAreaTi ?>
	<tr id="r_nu_usuarioRespAreaTi">
		<td><span id="elh_pargerais_nu_usuarioRespAreaTi"><?php echo $pargerais->nu_usuarioRespAreaTi->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $pargerais->nu_usuarioRespAreaTi->CellAttributes() ?>>
<span id="el_pargerais_nu_usuarioRespAreaTi" class="control-group">
<select data-field="x_nu_usuarioRespAreaTi" id="x_nu_usuarioRespAreaTi" name="x_nu_usuarioRespAreaTi"<?php echo $pargerais->nu_usuarioRespAreaTi->EditAttributes() ?>>
<?php
if (is_array($pargerais->nu_usuarioRespAreaTi->EditValue)) {
	$arwrk = $pargerais->nu_usuarioRespAreaTi->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pargerais->nu_usuarioRespAreaTi->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if (AllowAdd(CurrentProjectID() . "usuario")) { ?>
&nbsp;<a id="aol_x_nu_usuarioRespAreaTi" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_nu_usuarioRespAreaTi',url:'usuarioaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $pargerais->nu_usuarioRespAreaTi->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
fpargeraisedit.Lists["x_nu_usuarioRespAreaTi"].Options = <?php echo (is_array($pargerais->nu_usuarioRespAreaTi->EditValue)) ? ew_ArrayToJson($pargerais->nu_usuarioRespAreaTi->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $pargerais->nu_usuarioRespAreaTi->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($pargerais->qt_horasMes->Visible) { // qt_horasMes ?>
	<tr id="r_qt_horasMes">
		<td><span id="elh_pargerais_qt_horasMes"><?php echo $pargerais->qt_horasMes->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $pargerais->qt_horasMes->CellAttributes() ?>>
<span id="el_pargerais_qt_horasMes" class="control-group">
<input type="text" data-field="x_qt_horasMes" name="x_qt_horasMes" id="x_qt_horasMes" size="30" placeholder="<?php echo $pargerais->qt_horasMes->PlaceHolder ?>" value="<?php echo $pargerais->qt_horasMes->EditValue ?>"<?php echo $pargerais->qt_horasMes->EditAttributes() ?>>
</span>
<?php echo $pargerais->qt_horasMes->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($pargerais->nu_sistema->Visible) { // nu_sistema ?>
	<tr id="r_nu_sistema">
		<td><span id="elh_pargerais_nu_sistema"><?php echo $pargerais->nu_sistema->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $pargerais->nu_sistema->CellAttributes() ?>>
<span id="el_pargerais_nu_sistema" class="control-group">
<select data-field="x_nu_sistema" id="x_nu_sistema" name="x_nu_sistema"<?php echo $pargerais->nu_sistema->EditAttributes() ?>>
<?php
if (is_array($pargerais->nu_sistema->EditValue)) {
	$arwrk = $pargerais->nu_sistema->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pargerais->nu_sistema->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$pargerais->nu_sistema) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fpargeraisedit.Lists["x_nu_sistema"].Options = <?php echo (is_array($pargerais->nu_sistema->EditValue)) ? ew_ArrayToJson($pargerais->nu_sistema->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $pargerais->nu_sistema->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($pargerais->dt_inicioOpSistema->Visible) { // dt_inicioOpSistema ?>
	<tr id="r_dt_inicioOpSistema">
		<td><span id="elh_pargerais_dt_inicioOpSistema"><?php echo $pargerais->dt_inicioOpSistema->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $pargerais->dt_inicioOpSistema->CellAttributes() ?>>
<span id="el_pargerais_dt_inicioOpSistema" class="control-group">
<input type="text" data-field="x_dt_inicioOpSistema" name="x_dt_inicioOpSistema" id="x_dt_inicioOpSistema" placeholder="<?php echo $pargerais->dt_inicioOpSistema->PlaceHolder ?>" value="<?php echo $pargerais->dt_inicioOpSistema->EditValue ?>"<?php echo $pargerais->dt_inicioOpSistema->EditAttributes() ?>>
<?php if (!$pargerais->dt_inicioOpSistema->ReadOnly && !$pargerais->dt_inicioOpSistema->Disabled && @$pargerais->dt_inicioOpSistema->EditAttrs["readonly"] == "" && @$pargerais->dt_inicioOpSistema->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_dt_inicioOpSistema" name="cal_x_dt_inicioOpSistema" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x_dt_inicioOpSistema" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fpargeraisedit", "x_dt_inicioOpSistema", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $pargerais->dt_inicioOpSistema->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($pargerais->tx_htmlHomeNaoLogado->Visible) { // tx_htmlHomeNaoLogado ?>
	<tr id="r_tx_htmlHomeNaoLogado">
		<td><span id="elh_pargerais_tx_htmlHomeNaoLogado"><?php echo $pargerais->tx_htmlHomeNaoLogado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $pargerais->tx_htmlHomeNaoLogado->CellAttributes() ?>>
<span id="el_pargerais_tx_htmlHomeNaoLogado" class="control-group">
<textarea data-field="x_tx_htmlHomeNaoLogado" name="x_tx_htmlHomeNaoLogado" id="x_tx_htmlHomeNaoLogado" cols="70" rows="10" placeholder="<?php echo $pargerais->tx_htmlHomeNaoLogado->PlaceHolder ?>"<?php echo $pargerais->tx_htmlHomeNaoLogado->EditAttributes() ?>><?php echo $pargerais->tx_htmlHomeNaoLogado->EditValue ?></textarea>
</span>
<?php echo $pargerais->tx_htmlHomeNaoLogado->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_pargerais2">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_pargeraisedit2" class="table table-bordered table-striped">
<?php if ($pargerais->nu_fornPadraoFsw->Visible) { // nu_fornPadraoFsw ?>
	<tr id="r_nu_fornPadraoFsw">
		<td><span id="elh_pargerais_nu_fornPadraoFsw"><?php echo $pargerais->nu_fornPadraoFsw->FldCaption() ?></span></td>
		<td<?php echo $pargerais->nu_fornPadraoFsw->CellAttributes() ?>>
<span id="el_pargerais_nu_fornPadraoFsw" class="control-group">
<?php $pargerais->nu_fornPadraoFsw->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_contFornPadraoFsw']); " . @$pargerais->nu_fornPadraoFsw->EditAttrs["onchange"]; ?>
<select data-field="x_nu_fornPadraoFsw" id="x_nu_fornPadraoFsw" name="x_nu_fornPadraoFsw"<?php echo $pargerais->nu_fornPadraoFsw->EditAttributes() ?>>
<?php
if (is_array($pargerais->nu_fornPadraoFsw->EditValue)) {
	$arwrk = $pargerais->nu_fornPadraoFsw->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pargerais->nu_fornPadraoFsw->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if (AllowAdd(CurrentProjectID() . "fornecedor")) { ?>
&nbsp;<a id="aol_x_nu_fornPadraoFsw" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_nu_fornPadraoFsw',url:'fornecedoraddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $pargerais->nu_fornPadraoFsw->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
fpargeraisedit.Lists["x_nu_fornPadraoFsw"].Options = <?php echo (is_array($pargerais->nu_fornPadraoFsw->EditValue)) ? ew_ArrayToJson($pargerais->nu_fornPadraoFsw->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $pargerais->nu_fornPadraoFsw->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($pargerais->nu_contFornPadraoFsw->Visible) { // nu_contFornPadraoFsw ?>
	<tr id="r_nu_contFornPadraoFsw">
		<td><span id="elh_pargerais_nu_contFornPadraoFsw"><?php echo $pargerais->nu_contFornPadraoFsw->FldCaption() ?></span></td>
		<td<?php echo $pargerais->nu_contFornPadraoFsw->CellAttributes() ?>>
<span id="el_pargerais_nu_contFornPadraoFsw" class="control-group">
<?php $pargerais->nu_contFornPadraoFsw->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_itemContFornPadraoFsw']); " . @$pargerais->nu_contFornPadraoFsw->EditAttrs["onchange"]; ?>
<select data-field="x_nu_contFornPadraoFsw" id="x_nu_contFornPadraoFsw" name="x_nu_contFornPadraoFsw"<?php echo $pargerais->nu_contFornPadraoFsw->EditAttributes() ?>>
<?php
if (is_array($pargerais->nu_contFornPadraoFsw->EditValue)) {
	$arwrk = $pargerais->nu_contFornPadraoFsw->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pargerais->nu_contFornPadraoFsw->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fpargeraisedit.Lists["x_nu_contFornPadraoFsw"].Options = <?php echo (is_array($pargerais->nu_contFornPadraoFsw->EditValue)) ? ew_ArrayToJson($pargerais->nu_contFornPadraoFsw->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $pargerais->nu_contFornPadraoFsw->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($pargerais->nu_itemContFornPadraoFsw->Visible) { // nu_itemContFornPadraoFsw ?>
	<tr id="r_nu_itemContFornPadraoFsw">
		<td><span id="elh_pargerais_nu_itemContFornPadraoFsw"><?php echo $pargerais->nu_itemContFornPadraoFsw->FldCaption() ?></span></td>
		<td<?php echo $pargerais->nu_itemContFornPadraoFsw->CellAttributes() ?>>
<span id="el_pargerais_nu_itemContFornPadraoFsw" class="control-group">
<select data-field="x_nu_itemContFornPadraoFsw" id="x_nu_itemContFornPadraoFsw" name="x_nu_itemContFornPadraoFsw"<?php echo $pargerais->nu_itemContFornPadraoFsw->EditAttributes() ?>>
<?php
if (is_array($pargerais->nu_itemContFornPadraoFsw->EditValue)) {
	$arwrk = $pargerais->nu_itemContFornPadraoFsw->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pargerais->nu_itemContFornPadraoFsw->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fpargeraisedit.Lists["x_nu_itemContFornPadraoFsw"].Options = <?php echo (is_array($pargerais->nu_itemContFornPadraoFsw->EditValue)) ? ew_ArrayToJson($pargerais->nu_itemContFornPadraoFsw->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $pargerais->nu_itemContFornPadraoFsw->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_pargerais3">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_pargeraisedit3" class="table table-bordered table-striped">
<?php if ($pargerais->no_areaEscritProj->Visible) { // no_areaEscritProj ?>
	<tr id="r_no_areaEscritProj">
		<td><span id="elh_pargerais_no_areaEscritProj"><?php echo $pargerais->no_areaEscritProj->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $pargerais->no_areaEscritProj->CellAttributes() ?>>
<span id="el_pargerais_no_areaEscritProj" class="control-group">
<input type="text" data-field="x_no_areaEscritProj" name="x_no_areaEscritProj" id="x_no_areaEscritProj" size="30" maxlength="100" placeholder="<?php echo $pargerais->no_areaEscritProj->PlaceHolder ?>" value="<?php echo $pargerais->no_areaEscritProj->EditValue ?>"<?php echo $pargerais->no_areaEscritProj->EditAttributes() ?>>
</span>
<?php echo $pargerais->no_areaEscritProj->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_pargerais4">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_pargeraisedit4" class="table table-bordered table-striped">
<?php if ($pargerais->nu_orgMetricas->Visible) { // nu_orgMetricas ?>
	<tr id="r_nu_orgMetricas">
		<td><span id="elh_pargerais_nu_orgMetricas"><?php echo $pargerais->nu_orgMetricas->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $pargerais->nu_orgMetricas->CellAttributes() ?>>
<span id="el_pargerais_nu_orgMetricas" class="control-group">
<?php $pargerais->nu_orgMetricas->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_areaMetricas']); " . @$pargerais->nu_orgMetricas->EditAttrs["onchange"]; ?>
<select data-field="x_nu_orgMetricas" id="x_nu_orgMetricas" name="x_nu_orgMetricas"<?php echo $pargerais->nu_orgMetricas->EditAttributes() ?>>
<?php
if (is_array($pargerais->nu_orgMetricas->EditValue)) {
	$arwrk = $pargerais->nu_orgMetricas->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pargerais->nu_orgMetricas->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fpargeraisedit.Lists["x_nu_orgMetricas"].Options = <?php echo (is_array($pargerais->nu_orgMetricas->EditValue)) ? ew_ArrayToJson($pargerais->nu_orgMetricas->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $pargerais->nu_orgMetricas->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($pargerais->nu_areaMetricas->Visible) { // nu_areaMetricas ?>
	<tr id="r_nu_areaMetricas">
		<td><span id="elh_pargerais_nu_areaMetricas"><?php echo $pargerais->nu_areaMetricas->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $pargerais->nu_areaMetricas->CellAttributes() ?>>
<span id="el_pargerais_nu_areaMetricas" class="control-group">
<select data-field="x_nu_areaMetricas" id="x_nu_areaMetricas" name="x_nu_areaMetricas"<?php echo $pargerais->nu_areaMetricas->EditAttributes() ?>>
<?php
if (is_array($pargerais->nu_areaMetricas->EditValue)) {
	$arwrk = $pargerais->nu_areaMetricas->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pargerais->nu_areaMetricas->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fpargeraisedit.Lists["x_nu_areaMetricas"].Options = <?php echo (is_array($pargerais->nu_areaMetricas->EditValue)) ? ew_ArrayToJson($pargerais->nu_areaMetricas->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $pargerais->nu_areaMetricas->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($pargerais->nu_fornMetricas->Visible) { // nu_fornMetricas ?>
	<tr id="r_nu_fornMetricas">
		<td><span id="elh_pargerais_nu_fornMetricas"><?php echo $pargerais->nu_fornMetricas->FldCaption() ?></span></td>
		<td<?php echo $pargerais->nu_fornMetricas->CellAttributes() ?>>
<span id="el_pargerais_nu_fornMetricas" class="control-group">
<select data-field="x_nu_fornMetricas" id="x_nu_fornMetricas" name="x_nu_fornMetricas"<?php echo $pargerais->nu_fornMetricas->EditAttributes() ?>>
<?php
if (is_array($pargerais->nu_fornMetricas->EditValue)) {
	$arwrk = $pargerais->nu_fornMetricas->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pargerais->nu_fornMetricas->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fpargeraisedit.Lists["x_nu_fornMetricas"].Options = <?php echo (is_array($pargerais->nu_fornMetricas->EditValue)) ? ew_ArrayToJson($pargerais->nu_fornMetricas->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $pargerais->nu_fornMetricas->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($pargerais->no_areaMetricas->Visible) { // no_areaMetricas ?>
	<tr id="r_no_areaMetricas">
		<td><span id="elh_pargerais_no_areaMetricas"><?php echo $pargerais->no_areaMetricas->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $pargerais->no_areaMetricas->CellAttributes() ?>>
<span id="el_pargerais_no_areaMetricas" class="control-group">
<input type="text" data-field="x_no_areaMetricas" name="x_no_areaMetricas" id="x_no_areaMetricas" size="30" maxlength="100" placeholder="<?php echo $pargerais->no_areaMetricas->PlaceHolder ?>" value="<?php echo $pargerais->no_areaMetricas->EditValue ?>"<?php echo $pargerais->no_areaMetricas->EditAttributes() ?>>
</span>
<?php echo $pargerais->no_areaMetricas->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($pargerais->nu_modeloMetricasPadrao->Visible) { // nu_modeloMetricasPadrao ?>
	<tr id="r_nu_modeloMetricasPadrao">
		<td><span id="elh_pargerais_nu_modeloMetricasPadrao"><?php echo $pargerais->nu_modeloMetricasPadrao->FldCaption() ?></span></td>
		<td<?php echo $pargerais->nu_modeloMetricasPadrao->CellAttributes() ?>>
<span id="el_pargerais_nu_modeloMetricasPadrao" class="control-group">
<select data-field="x_nu_modeloMetricasPadrao" id="x_nu_modeloMetricasPadrao" name="x_nu_modeloMetricasPadrao"<?php echo $pargerais->nu_modeloMetricasPadrao->EditAttributes() ?>>
<?php
if (is_array($pargerais->nu_modeloMetricasPadrao->EditValue)) {
	$arwrk = $pargerais->nu_modeloMetricasPadrao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pargerais->nu_modeloMetricasPadrao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fpargeraisedit.Lists["x_nu_modeloMetricasPadrao"].Options = <?php echo (is_array($pargerais->nu_modeloMetricasPadrao->EditValue)) ? ew_ArrayToJson($pargerais->nu_modeloMetricasPadrao->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $pargerais->nu_modeloMetricasPadrao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($pargerais->nu_areaVincEscritProj->Visible) { // nu_areaVincEscritProj ?>
	<tr id="r_nu_areaVincEscritProj">
		<td><span id="elh_pargerais_nu_areaVincEscritProj"><?php echo $pargerais->nu_areaVincEscritProj->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $pargerais->nu_areaVincEscritProj->CellAttributes() ?>>
<span id="el_pargerais_nu_areaVincEscritProj" class="control-group">
<select data-field="x_nu_areaVincEscritProj" id="x_nu_areaVincEscritProj" name="x_nu_areaVincEscritProj"<?php echo $pargerais->nu_areaVincEscritProj->EditAttributes() ?>>
<?php
if (is_array($pargerais->nu_areaVincEscritProj->EditValue)) {
	$arwrk = $pargerais->nu_areaVincEscritProj->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pargerais->nu_areaVincEscritProj->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fpargeraisedit.Lists["x_nu_areaVincEscritProj"].Options = <?php echo (is_array($pargerais->nu_areaVincEscritProj->EditValue)) ? ew_ArrayToJson($pargerais->nu_areaVincEscritProj->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $pargerais->nu_areaVincEscritProj->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($pargerais->nu_pesoProbRisco->Visible) { // nu_pesoProbRisco ?>
	<tr id="r_nu_pesoProbRisco">
		<td><span id="elh_pargerais_nu_pesoProbRisco"><?php echo $pargerais->nu_pesoProbRisco->FldCaption() ?></span></td>
		<td<?php echo $pargerais->nu_pesoProbRisco->CellAttributes() ?>>
<span id="el_pargerais_nu_pesoProbRisco" class="control-group">
<input type="text" data-field="x_nu_pesoProbRisco" name="x_nu_pesoProbRisco" id="x_nu_pesoProbRisco" size="30" placeholder="<?php echo $pargerais->nu_pesoProbRisco->PlaceHolder ?>" value="<?php echo $pargerais->nu_pesoProbRisco->EditValue ?>"<?php echo $pargerais->nu_pesoProbRisco->EditAttributes() ?>>
</span>
<?php echo $pargerais->nu_pesoProbRisco->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($pargerais->nu_pesoImpacRisco->Visible) { // nu_pesoImpacRisco ?>
	<tr id="r_nu_pesoImpacRisco">
		<td><span id="elh_pargerais_nu_pesoImpacRisco"><?php echo $pargerais->nu_pesoImpacRisco->FldCaption() ?></span></td>
		<td<?php echo $pargerais->nu_pesoImpacRisco->CellAttributes() ?>>
<span id="el_pargerais_nu_pesoImpacRisco" class="control-group">
<input type="text" data-field="x_nu_pesoImpacRisco" name="x_nu_pesoImpacRisco" id="x_nu_pesoImpacRisco" size="30" placeholder="<?php echo $pargerais->nu_pesoImpacRisco->PlaceHolder ?>" value="<?php echo $pargerais->nu_pesoImpacRisco->EditValue ?>"<?php echo $pargerais->nu_pesoImpacRisco->EditAttributes() ?>>
</span>
<?php echo $pargerais->nu_pesoImpacRisco->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_pargerais5">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_pargeraisedit5" class="table table-bordered table-striped">
<?php if ($pargerais->nu_fornecedorAuditoria->Visible) { // nu_fornecedorAuditoria ?>
	<tr id="r_nu_fornecedorAuditoria">
		<td><span id="elh_pargerais_nu_fornecedorAuditoria"><?php echo $pargerais->nu_fornecedorAuditoria->FldCaption() ?></span></td>
		<td<?php echo $pargerais->nu_fornecedorAuditoria->CellAttributes() ?>>
<span id="el_pargerais_nu_fornecedorAuditoria" class="control-group">
<select data-field="x_nu_fornecedorAuditoria" id="x_nu_fornecedorAuditoria" name="x_nu_fornecedorAuditoria"<?php echo $pargerais->nu_fornecedorAuditoria->EditAttributes() ?>>
<?php
if (is_array($pargerais->nu_fornecedorAuditoria->EditValue)) {
	$arwrk = $pargerais->nu_fornecedorAuditoria->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pargerais->nu_fornecedorAuditoria->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if (AllowAdd(CurrentProjectID() . "fornecedor")) { ?>
&nbsp;<a id="aol_x_nu_fornecedorAuditoria" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_nu_fornecedorAuditoria',url:'fornecedoraddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $pargerais->nu_fornecedorAuditoria->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
fpargeraisedit.Lists["x_nu_fornecedorAuditoria"].Options = <?php echo (is_array($pargerais->nu_fornecedorAuditoria->EditValue)) ? ew_ArrayToJson($pargerais->nu_fornecedorAuditoria->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $pargerais->nu_fornecedorAuditoria->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
	</div>
</div>
</td></tr></tbody></table>
<input type="hidden" data-field="x_nu_parametro" name="x_nu_parametro" id="x_nu_parametro" value="<?php echo ew_HtmlEncode($pargerais->nu_parametro->CurrentValue) ?>">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fpargeraisedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$pargerais_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$pargerais_edit->Page_Terminate();
?>
