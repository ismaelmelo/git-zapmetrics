<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "contagempf_funcaoinfo.php" ?>
<?php include_once "contagempfinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$contagempf_funcao_add = NULL; // Initialize page object first

class ccontagempf_funcao_add extends ccontagempf_funcao {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'contagempf_funcao';

	// Page object name
	var $PageObjName = 'contagempf_funcao_add';

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

		// Table object (contagempf_funcao)
		if (!isset($GLOBALS["contagempf_funcao"])) {
			$GLOBALS["contagempf_funcao"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["contagempf_funcao"];
		}

		// Table object (contagempf)
		if (!isset($GLOBALS['contagempf'])) $GLOBALS['contagempf'] = new ccontagempf();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'contagempf_funcao', TRUE);

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
			$this->Page_Terminate("contagempf_funcaolist.php");
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
			if (@$_GET["nu_funcao"] != "") {
				$this->nu_funcao->setQueryStringValue($_GET["nu_funcao"]);
				$this->setKey("nu_funcao", $this->nu_funcao->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_funcao", ""); // Clear key
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
					$this->Page_Terminate("contagempf_funcaolist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "contagempf_funcaoview.php")
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
		$this->nu_contagem->CurrentValue = NULL;
		$this->nu_contagem->OldValue = $this->nu_contagem->CurrentValue;
		$this->nu_agrupador->CurrentValue = NULL;
		$this->nu_agrupador->OldValue = $this->nu_agrupador->CurrentValue;
		$this->nu_uc->CurrentValue = NULL;
		$this->nu_uc->OldValue = $this->nu_uc->CurrentValue;
		$this->no_funcao->CurrentValue = NULL;
		$this->no_funcao->OldValue = $this->no_funcao->CurrentValue;
		$this->nu_tpManutencao->CurrentValue = NULL;
		$this->nu_tpManutencao->OldValue = $this->nu_tpManutencao->CurrentValue;
		$this->nu_tpElemento->CurrentValue = NULL;
		$this->nu_tpElemento->OldValue = $this->nu_tpElemento->CurrentValue;
		$this->qt_alr->CurrentValue = NULL;
		$this->qt_alr->OldValue = $this->qt_alr->CurrentValue;
		$this->ds_alr->CurrentValue = NULL;
		$this->ds_alr->OldValue = $this->ds_alr->CurrentValue;
		$this->qt_der->CurrentValue = NULL;
		$this->qt_der->OldValue = $this->qt_der->CurrentValue;
		$this->ds_der->CurrentValue = NULL;
		$this->ds_der->OldValue = $this->ds_der->CurrentValue;
		$this->ic_complexApf->CurrentValue = NULL;
		$this->ic_complexApf->OldValue = $this->ic_complexApf->CurrentValue;
		$this->vr_contribuicao->CurrentValue = NULL;
		$this->vr_contribuicao->OldValue = $this->vr_contribuicao->CurrentValue;
		$this->vr_fatorReducao->CurrentValue = NULL;
		$this->vr_fatorReducao->OldValue = $this->vr_fatorReducao->CurrentValue;
		$this->pc_varFasesRoteiro->CurrentValue = 100.00;
		$this->vr_qtPf->CurrentValue = NULL;
		$this->vr_qtPf->OldValue = $this->vr_qtPf->CurrentValue;
		$this->ic_analalogia->CurrentValue = "N";
		$this->ds_observacoes->CurrentValue = NULL;
		$this->ds_observacoes->OldValue = $this->ds_observacoes->CurrentValue;
		$this->nu_usuarioLogado->CurrentValue = NULL;
		$this->nu_usuarioLogado->OldValue = $this->nu_usuarioLogado->CurrentValue;
		$this->dh_inclusao->CurrentValue = NULL;
		$this->dh_inclusao->OldValue = $this->dh_inclusao->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_contagem->FldIsDetailKey) {
			$this->nu_contagem->setFormValue($objForm->GetValue("x_nu_contagem"));
		}
		if (!$this->nu_agrupador->FldIsDetailKey) {
			$this->nu_agrupador->setFormValue($objForm->GetValue("x_nu_agrupador"));
		}
		if (!$this->nu_uc->FldIsDetailKey) {
			$this->nu_uc->setFormValue($objForm->GetValue("x_nu_uc"));
		}
		if (!$this->no_funcao->FldIsDetailKey) {
			$this->no_funcao->setFormValue($objForm->GetValue("x_no_funcao"));
		}
		if (!$this->nu_tpManutencao->FldIsDetailKey) {
			$this->nu_tpManutencao->setFormValue($objForm->GetValue("x_nu_tpManutencao"));
		}
		if (!$this->nu_tpElemento->FldIsDetailKey) {
			$this->nu_tpElemento->setFormValue($objForm->GetValue("x_nu_tpElemento"));
		}
		if (!$this->qt_alr->FldIsDetailKey) {
			$this->qt_alr->setFormValue($objForm->GetValue("x_qt_alr"));
		}
		if (!$this->ds_alr->FldIsDetailKey) {
			$this->ds_alr->setFormValue($objForm->GetValue("x_ds_alr"));
		}
		if (!$this->qt_der->FldIsDetailKey) {
			$this->qt_der->setFormValue($objForm->GetValue("x_qt_der"));
		}
		if (!$this->ds_der->FldIsDetailKey) {
			$this->ds_der->setFormValue($objForm->GetValue("x_ds_der"));
		}
		if (!$this->ic_complexApf->FldIsDetailKey) {
			$this->ic_complexApf->setFormValue($objForm->GetValue("x_ic_complexApf"));
		}
		if (!$this->vr_contribuicao->FldIsDetailKey) {
			$this->vr_contribuicao->setFormValue($objForm->GetValue("x_vr_contribuicao"));
		}
		if (!$this->vr_fatorReducao->FldIsDetailKey) {
			$this->vr_fatorReducao->setFormValue($objForm->GetValue("x_vr_fatorReducao"));
		}
		if (!$this->pc_varFasesRoteiro->FldIsDetailKey) {
			$this->pc_varFasesRoteiro->setFormValue($objForm->GetValue("x_pc_varFasesRoteiro"));
		}
		if (!$this->vr_qtPf->FldIsDetailKey) {
			$this->vr_qtPf->setFormValue($objForm->GetValue("x_vr_qtPf"));
		}
		if (!$this->ic_analalogia->FldIsDetailKey) {
			$this->ic_analalogia->setFormValue($objForm->GetValue("x_ic_analalogia"));
		}
		if (!$this->ds_observacoes->FldIsDetailKey) {
			$this->ds_observacoes->setFormValue($objForm->GetValue("x_ds_observacoes"));
		}
		if (!$this->nu_usuarioLogado->FldIsDetailKey) {
			$this->nu_usuarioLogado->setFormValue($objForm->GetValue("x_nu_usuarioLogado"));
		}
		if (!$this->dh_inclusao->FldIsDetailKey) {
			$this->dh_inclusao->setFormValue($objForm->GetValue("x_dh_inclusao"));
			$this->dh_inclusao->CurrentValue = ew_UnFormatDateTime($this->dh_inclusao->CurrentValue, 11);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->nu_contagem->CurrentValue = $this->nu_contagem->FormValue;
		$this->nu_agrupador->CurrentValue = $this->nu_agrupador->FormValue;
		$this->nu_uc->CurrentValue = $this->nu_uc->FormValue;
		$this->no_funcao->CurrentValue = $this->no_funcao->FormValue;
		$this->nu_tpManutencao->CurrentValue = $this->nu_tpManutencao->FormValue;
		$this->nu_tpElemento->CurrentValue = $this->nu_tpElemento->FormValue;
		$this->qt_alr->CurrentValue = $this->qt_alr->FormValue;
		$this->ds_alr->CurrentValue = $this->ds_alr->FormValue;
		$this->qt_der->CurrentValue = $this->qt_der->FormValue;
		$this->ds_der->CurrentValue = $this->ds_der->FormValue;
		$this->ic_complexApf->CurrentValue = $this->ic_complexApf->FormValue;
		$this->vr_contribuicao->CurrentValue = $this->vr_contribuicao->FormValue;
		$this->vr_fatorReducao->CurrentValue = $this->vr_fatorReducao->FormValue;
		$this->pc_varFasesRoteiro->CurrentValue = $this->pc_varFasesRoteiro->FormValue;
		$this->vr_qtPf->CurrentValue = $this->vr_qtPf->FormValue;
		$this->ic_analalogia->CurrentValue = $this->ic_analalogia->FormValue;
		$this->ds_observacoes->CurrentValue = $this->ds_observacoes->FormValue;
		$this->nu_usuarioLogado->CurrentValue = $this->nu_usuarioLogado->FormValue;
		$this->dh_inclusao->CurrentValue = $this->dh_inclusao->FormValue;
		$this->dh_inclusao->CurrentValue = ew_UnFormatDateTime($this->dh_inclusao->CurrentValue, 11);
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
		$this->nu_contagem->setDbValue($rs->fields('nu_contagem'));
		$this->nu_funcao->setDbValue($rs->fields('nu_funcao'));
		$this->nu_agrupador->setDbValue($rs->fields('nu_agrupador'));
		if (array_key_exists('EV__nu_agrupador', $rs->fields)) {
			$this->nu_agrupador->VirtualValue = $rs->fields('EV__nu_agrupador'); // Set up virtual field value
		} else {
			$this->nu_agrupador->VirtualValue = ""; // Clear value
		}
		$this->nu_uc->setDbValue($rs->fields('nu_uc'));
		$this->no_funcao->setDbValue($rs->fields('no_funcao'));
		$this->nu_tpManutencao->setDbValue($rs->fields('nu_tpManutencao'));
		$this->nu_tpElemento->setDbValue($rs->fields('nu_tpElemento'));
		$this->qt_alr->setDbValue($rs->fields('qt_alr'));
		$this->ds_alr->setDbValue($rs->fields('ds_alr'));
		$this->qt_der->setDbValue($rs->fields('qt_der'));
		$this->ds_der->setDbValue($rs->fields('ds_der'));
		$this->ic_complexApf->setDbValue($rs->fields('ic_complexApf'));
		$this->vr_contribuicao->setDbValue($rs->fields('vr_contribuicao'));
		$this->vr_fatorReducao->setDbValue($rs->fields('vr_fatorReducao'));
		$this->pc_varFasesRoteiro->setDbValue($rs->fields('pc_varFasesRoteiro'));
		$this->vr_qtPf->setDbValue($rs->fields('vr_qtPf'));
		$this->ic_analalogia->setDbValue($rs->fields('ic_analalogia'));
		$this->ds_observacoes->setDbValue($rs->fields('ds_observacoes'));
		$this->nu_usuarioLogado->setDbValue($rs->fields('nu_usuarioLogado'));
		$this->dh_inclusao->setDbValue($rs->fields('dh_inclusao'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_contagem->DbValue = $row['nu_contagem'];
		$this->nu_funcao->DbValue = $row['nu_funcao'];
		$this->nu_agrupador->DbValue = $row['nu_agrupador'];
		$this->nu_uc->DbValue = $row['nu_uc'];
		$this->no_funcao->DbValue = $row['no_funcao'];
		$this->nu_tpManutencao->DbValue = $row['nu_tpManutencao'];
		$this->nu_tpElemento->DbValue = $row['nu_tpElemento'];
		$this->qt_alr->DbValue = $row['qt_alr'];
		$this->ds_alr->DbValue = $row['ds_alr'];
		$this->qt_der->DbValue = $row['qt_der'];
		$this->ds_der->DbValue = $row['ds_der'];
		$this->ic_complexApf->DbValue = $row['ic_complexApf'];
		$this->vr_contribuicao->DbValue = $row['vr_contribuicao'];
		$this->vr_fatorReducao->DbValue = $row['vr_fatorReducao'];
		$this->pc_varFasesRoteiro->DbValue = $row['pc_varFasesRoteiro'];
		$this->vr_qtPf->DbValue = $row['vr_qtPf'];
		$this->ic_analalogia->DbValue = $row['ic_analalogia'];
		$this->ds_observacoes->DbValue = $row['ds_observacoes'];
		$this->nu_usuarioLogado->DbValue = $row['nu_usuarioLogado'];
		$this->dh_inclusao->DbValue = $row['dh_inclusao'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_funcao")) <> "")
			$this->nu_funcao->CurrentValue = $this->getKey("nu_funcao"); // nu_funcao
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

		if ($this->vr_fatorReducao->FormValue == $this->vr_fatorReducao->CurrentValue && is_numeric(ew_StrToFloat($this->vr_fatorReducao->CurrentValue)))
			$this->vr_fatorReducao->CurrentValue = ew_StrToFloat($this->vr_fatorReducao->CurrentValue);

		// Convert decimal values if posted back
		if ($this->pc_varFasesRoteiro->FormValue == $this->pc_varFasesRoteiro->CurrentValue && is_numeric(ew_StrToFloat($this->pc_varFasesRoteiro->CurrentValue)))
			$this->pc_varFasesRoteiro->CurrentValue = ew_StrToFloat($this->pc_varFasesRoteiro->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_qtPf->FormValue == $this->vr_qtPf->CurrentValue && is_numeric(ew_StrToFloat($this->vr_qtPf->CurrentValue)))
			$this->vr_qtPf->CurrentValue = ew_StrToFloat($this->vr_qtPf->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_contagem
		// nu_funcao
		// nu_agrupador
		// nu_uc
		// no_funcao
		// nu_tpManutencao
		// nu_tpElemento
		// qt_alr
		// ds_alr
		// qt_der
		// ds_der
		// ic_complexApf
		// vr_contribuicao
		// vr_fatorReducao
		// pc_varFasesRoteiro
		// vr_qtPf
		// ic_analalogia
		// ds_observacoes
		// nu_usuarioLogado
		// dh_inclusao

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_contagem
			$this->nu_contagem->ViewValue = $this->nu_contagem->CurrentValue;
			if (strval($this->nu_contagem->CurrentValue) <> "") {
				$sFilterWrk = "[nu_contagem]" . ew_SearchString("=", $this->nu_contagem->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_contagem], [nu_contagem] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[contagempf]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_contagem, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_contagem->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_contagem->ViewValue = $this->nu_contagem->CurrentValue;
				}
			} else {
				$this->nu_contagem->ViewValue = NULL;
			}
			$this->nu_contagem->ViewCustomAttributes = "";

			// nu_agrupador
			if ($this->nu_agrupador->VirtualValue <> "") {
				$this->nu_agrupador->ViewValue = $this->nu_agrupador->VirtualValue;
			} else {
			if (strval($this->nu_agrupador->CurrentValue) <> "") {
				$sFilterWrk = "[nu_agrupador]" . ew_SearchString("=", $this->nu_agrupador->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_agrupador], [no_agrupador] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[contagempf_agrupador]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_agrupador, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_agrupador] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_agrupador->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_agrupador->ViewValue = $this->nu_agrupador->CurrentValue;
				}
			} else {
				$this->nu_agrupador->ViewValue = NULL;
			}
			}
			$this->nu_agrupador->ViewCustomAttributes = "";

			// nu_uc
			if (strval($this->nu_uc->CurrentValue) <> "") {
				$sFilterWrk = "[nu_uc]" . ew_SearchString("=", $this->nu_uc->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_uc], [co_alternativo] AS [DispFld], [no_uc] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[uc]";
			$sWhereWrk = "";
			$lookuptblfilter = "[nu_sistema] = (SELECT nu_sistema FROM contagempf WHERE nu_contagem = " . strval(CurrentPage()->nu_contagem->CurrentValue) . ")";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_uc, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [co_alternativo] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_uc->ViewValue = $rswrk->fields('DispFld');
					$this->nu_uc->ViewValue .= ew_ValueSeparator(1,$this->nu_uc) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_uc->ViewValue = $this->nu_uc->CurrentValue;
				}
			} else {
				$this->nu_uc->ViewValue = NULL;
			}
			$this->nu_uc->ViewCustomAttributes = "";

			// no_funcao
			$this->no_funcao->ViewValue = $this->no_funcao->CurrentValue;
			$this->no_funcao->ViewCustomAttributes = "";

			// nu_tpManutencao
			if (strval($this->nu_tpManutencao->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpManutencao]" . ew_SearchString("=", $this->nu_tpManutencao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpManutencao], [no_tpManutencao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpmanutencao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[nu_tpContagem]=(SELECT nu_tpContagem FROM contagempf WHERE nu_contagem = " . strval($this->nu_contagem->CurrentValue) . ")";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpManutencao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpManutencao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpManutencao->ViewValue = $this->nu_tpManutencao->CurrentValue;
				}
			} else {
				$this->nu_tpManutencao->ViewValue = NULL;
			}
			$this->nu_tpManutencao->ViewCustomAttributes = "";

			// nu_tpElemento
			if (strval($this->nu_tpElemento->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpElemento]" . ew_SearchString("=", $this->nu_tpElemento->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpElemento], [no_tpElemento] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpElemento]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpElemento, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_tpElemento] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpElemento->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpElemento->ViewValue = $this->nu_tpElemento->CurrentValue;
				}
			} else {
				$this->nu_tpElemento->ViewValue = NULL;
			}
			$this->nu_tpElemento->ViewCustomAttributes = "";

			// qt_alr
			$this->qt_alr->ViewValue = $this->qt_alr->CurrentValue;
			$this->qt_alr->ViewValue = ew_FormatNumber($this->qt_alr->ViewValue, 0, 0, 0, 0);
			$this->qt_alr->ViewCustomAttributes = "";

			// ds_alr
			$this->ds_alr->ViewValue = $this->ds_alr->CurrentValue;
			$this->ds_alr->ViewCustomAttributes = "";

			// qt_der
			$this->qt_der->ViewValue = $this->qt_der->CurrentValue;
			$this->qt_der->ViewValue = ew_FormatNumber($this->qt_der->ViewValue, 0, 0, 0, 0);
			$this->qt_der->ViewCustomAttributes = "";

			// ds_der
			$this->ds_der->ViewValue = $this->ds_der->CurrentValue;
			$this->ds_der->ViewCustomAttributes = "";

			// ic_complexApf
			$this->ic_complexApf->ViewValue = $this->ic_complexApf->CurrentValue;
			$this->ic_complexApf->ViewCustomAttributes = "";

			// vr_contribuicao
			$this->vr_contribuicao->ViewValue = $this->vr_contribuicao->CurrentValue;
			$this->vr_contribuicao->ViewValue = ew_FormatNumber($this->vr_contribuicao->ViewValue, 0, 0, 0, 0);
			$this->vr_contribuicao->ViewCustomAttributes = "";

			// vr_fatorReducao
			$this->vr_fatorReducao->ViewValue = $this->vr_fatorReducao->CurrentValue;
			$this->vr_fatorReducao->ViewCustomAttributes = "";

			// pc_varFasesRoteiro
			$this->pc_varFasesRoteiro->ViewValue = $this->pc_varFasesRoteiro->CurrentValue;
			$this->pc_varFasesRoteiro->ViewCustomAttributes = "";

			// vr_qtPf
			$this->vr_qtPf->ViewValue = $this->vr_qtPf->CurrentValue;
			$this->vr_qtPf->ViewCustomAttributes = "";

			// ic_analalogia
			if (strval($this->ic_analalogia->CurrentValue) <> "") {
				switch ($this->ic_analalogia->CurrentValue) {
					case $this->ic_analalogia->FldTagValue(1):
						$this->ic_analalogia->ViewValue = $this->ic_analalogia->FldTagCaption(1) <> "" ? $this->ic_analalogia->FldTagCaption(1) : $this->ic_analalogia->CurrentValue;
						break;
					case $this->ic_analalogia->FldTagValue(2):
						$this->ic_analalogia->ViewValue = $this->ic_analalogia->FldTagCaption(2) <> "" ? $this->ic_analalogia->FldTagCaption(2) : $this->ic_analalogia->CurrentValue;
						break;
					default:
						$this->ic_analalogia->ViewValue = $this->ic_analalogia->CurrentValue;
				}
			} else {
				$this->ic_analalogia->ViewValue = NULL;
			}
			$this->ic_analalogia->ViewCustomAttributes = "";

			// ds_observacoes
			$this->ds_observacoes->ViewValue = $this->ds_observacoes->CurrentValue;
			$this->ds_observacoes->ViewCustomAttributes = "";

			// nu_usuarioLogado
			$this->nu_usuarioLogado->ViewValue = $this->nu_usuarioLogado->CurrentValue;
			$this->nu_usuarioLogado->ViewCustomAttributes = "";

			// dh_inclusao
			$this->dh_inclusao->ViewValue = $this->dh_inclusao->CurrentValue;
			$this->dh_inclusao->ViewValue = ew_FormatDateTime($this->dh_inclusao->ViewValue, 11);
			$this->dh_inclusao->ViewCustomAttributes = "";

			// nu_contagem
			$this->nu_contagem->LinkCustomAttributes = "";
			$this->nu_contagem->HrefValue = "";
			$this->nu_contagem->TooltipValue = "";

			// nu_agrupador
			$this->nu_agrupador->LinkCustomAttributes = "";
			$this->nu_agrupador->HrefValue = "";
			$this->nu_agrupador->TooltipValue = "";

			// nu_uc
			$this->nu_uc->LinkCustomAttributes = "";
			$this->nu_uc->HrefValue = "";
			$this->nu_uc->TooltipValue = "";

			// no_funcao
			$this->no_funcao->LinkCustomAttributes = "";
			$this->no_funcao->HrefValue = "";
			$this->no_funcao->TooltipValue = "";

			// nu_tpManutencao
			$this->nu_tpManutencao->LinkCustomAttributes = "";
			$this->nu_tpManutencao->HrefValue = "";
			$this->nu_tpManutencao->TooltipValue = "";

			// nu_tpElemento
			$this->nu_tpElemento->LinkCustomAttributes = "";
			$this->nu_tpElemento->HrefValue = "";
			$this->nu_tpElemento->TooltipValue = "";

			// qt_alr
			$this->qt_alr->LinkCustomAttributes = "";
			$this->qt_alr->HrefValue = "";
			$this->qt_alr->TooltipValue = "";

			// ds_alr
			$this->ds_alr->LinkCustomAttributes = "";
			$this->ds_alr->HrefValue = "";
			$this->ds_alr->TooltipValue = "";

			// qt_der
			$this->qt_der->LinkCustomAttributes = "";
			$this->qt_der->HrefValue = "";
			$this->qt_der->TooltipValue = "";

			// ds_der
			$this->ds_der->LinkCustomAttributes = "";
			$this->ds_der->HrefValue = "";
			$this->ds_der->TooltipValue = "";

			// ic_complexApf
			$this->ic_complexApf->LinkCustomAttributes = "";
			$this->ic_complexApf->HrefValue = "";
			$this->ic_complexApf->TooltipValue = "";

			// vr_contribuicao
			$this->vr_contribuicao->LinkCustomAttributes = "";
			$this->vr_contribuicao->HrefValue = "";
			$this->vr_contribuicao->TooltipValue = "";

			// vr_fatorReducao
			$this->vr_fatorReducao->LinkCustomAttributes = "";
			$this->vr_fatorReducao->HrefValue = "";
			$this->vr_fatorReducao->TooltipValue = "";

			// pc_varFasesRoteiro
			$this->pc_varFasesRoteiro->LinkCustomAttributes = "";
			$this->pc_varFasesRoteiro->HrefValue = "";
			$this->pc_varFasesRoteiro->TooltipValue = "";

			// vr_qtPf
			$this->vr_qtPf->LinkCustomAttributes = "";
			$this->vr_qtPf->HrefValue = "";
			$this->vr_qtPf->TooltipValue = "";

			// ic_analalogia
			$this->ic_analalogia->LinkCustomAttributes = "";
			$this->ic_analalogia->HrefValue = "";
			$this->ic_analalogia->TooltipValue = "";

			// ds_observacoes
			$this->ds_observacoes->LinkCustomAttributes = "";
			$this->ds_observacoes->HrefValue = "";
			$this->ds_observacoes->TooltipValue = "";

			// nu_usuarioLogado
			$this->nu_usuarioLogado->LinkCustomAttributes = "";
			$this->nu_usuarioLogado->HrefValue = "";
			$this->nu_usuarioLogado->TooltipValue = "";

			// dh_inclusao
			$this->dh_inclusao->LinkCustomAttributes = "";
			$this->dh_inclusao->HrefValue = "";
			$this->dh_inclusao->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nu_contagem
			$this->nu_contagem->EditCustomAttributes = "readonly";
			if ($this->nu_contagem->getSessionValue() <> "") {
				$this->nu_contagem->CurrentValue = $this->nu_contagem->getSessionValue();
			$this->nu_contagem->ViewValue = $this->nu_contagem->CurrentValue;
			if (strval($this->nu_contagem->CurrentValue) <> "") {
				$sFilterWrk = "[nu_contagem]" . ew_SearchString("=", $this->nu_contagem->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_contagem], [nu_contagem] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[contagempf]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_contagem, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_contagem->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_contagem->ViewValue = $this->nu_contagem->CurrentValue;
				}
			} else {
				$this->nu_contagem->ViewValue = NULL;
			}
			$this->nu_contagem->ViewCustomAttributes = "";
			} else {
			$this->nu_contagem->EditValue = ew_HtmlEncode($this->nu_contagem->CurrentValue);
			if (strval($this->nu_contagem->CurrentValue) <> "") {
				$sFilterWrk = "[nu_contagem]" . ew_SearchString("=", $this->nu_contagem->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_contagem], [nu_contagem] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[contagempf]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_contagem, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_contagem->EditValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_contagem->EditValue = $this->nu_contagem->CurrentValue;
				}
			} else {
				$this->nu_contagem->EditValue = NULL;
			}
			$this->nu_contagem->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_contagem->FldCaption()));
			}

			// nu_agrupador
			$this->nu_agrupador->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_agrupador], [no_agrupador] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [nu_contagem] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[contagempf_agrupador]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_agrupador, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_agrupador] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_agrupador->EditValue = $arwrk;

			// nu_uc
			$this->nu_uc->EditCustomAttributes = "";
			if (trim(strval($this->nu_uc->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "[nu_uc]" . ew_SearchString("=", $this->nu_uc->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT [nu_uc], [co_alternativo] AS [DispFld], [no_uc] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[uc]";
			$sWhereWrk = "";
			$lookuptblfilter = "[nu_sistema] = (SELECT nu_sistema FROM contagempf WHERE nu_contagem = " . strval(CurrentPage()->nu_contagem->CurrentValue) . ")";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_uc, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [co_alternativo] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_uc->EditValue = $arwrk;

			// no_funcao
			$this->no_funcao->EditCustomAttributes = "";
			$this->no_funcao->EditValue = ew_HtmlEncode($this->no_funcao->CurrentValue);
			$this->no_funcao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_funcao->FldCaption()));

			// nu_tpManutencao
			$this->nu_tpManutencao->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_tpManutencao], [no_tpManutencao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[tpmanutencao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[nu_tpContagem]=(SELECT nu_tpContagem FROM contagempf WHERE nu_contagem = " . strval($this->nu_contagem->CurrentValue) . ")";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpManutencao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_tpManutencao->EditValue = $arwrk;

			// nu_tpElemento
			$this->nu_tpElemento->EditCustomAttributes = "onchange='CalcularPF()'";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_tpElemento], [no_tpElemento] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [nu_tpManutencao] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[tpElemento]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpElemento, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_tpElemento] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_tpElemento->EditValue = $arwrk;

			// qt_alr
			$this->qt_alr->EditCustomAttributes = "autocomplete='off' onchange='CalcularPF()'";
			$this->qt_alr->EditValue = ew_HtmlEncode($this->qt_alr->CurrentValue);
			$this->qt_alr->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_alr->FldCaption()));

			// ds_alr
			$this->ds_alr->EditCustomAttributes = "";
			$this->ds_alr->EditValue = $this->ds_alr->CurrentValue;
			$this->ds_alr->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_alr->FldCaption()));

			// qt_der
			$this->qt_der->EditCustomAttributes = "autocomplete='off' onchange='CalcularPF()'";
			$this->qt_der->EditValue = ew_HtmlEncode($this->qt_der->CurrentValue);
			$this->qt_der->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_der->FldCaption()));

			// ds_der
			$this->ds_der->EditCustomAttributes = "";
			$this->ds_der->EditValue = $this->ds_der->CurrentValue;
			$this->ds_der->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_der->FldCaption()));

			// ic_complexApf
			$this->ic_complexApf->EditCustomAttributes = "readonly";
			$this->ic_complexApf->EditValue = ew_HtmlEncode($this->ic_complexApf->CurrentValue);
			$this->ic_complexApf->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ic_complexApf->FldCaption()));

			// vr_contribuicao
			$this->vr_contribuicao->EditCustomAttributes = "readonly";
			$this->vr_contribuicao->EditValue = ew_HtmlEncode($this->vr_contribuicao->CurrentValue);
			$this->vr_contribuicao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_contribuicao->FldCaption()));

			// vr_fatorReducao
			$this->vr_fatorReducao->EditCustomAttributes = "readonly";
			$this->vr_fatorReducao->EditValue = ew_HtmlEncode($this->vr_fatorReducao->CurrentValue);
			$this->vr_fatorReducao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_fatorReducao->FldCaption()));
			if (strval($this->vr_fatorReducao->EditValue) <> "" && is_numeric($this->vr_fatorReducao->EditValue)) $this->vr_fatorReducao->EditValue = ew_FormatNumber($this->vr_fatorReducao->EditValue, -2, -1, -2, 0);

			// pc_varFasesRoteiro
			$this->pc_varFasesRoteiro->EditCustomAttributes = "readonly";
			$this->pc_varFasesRoteiro->EditValue = ew_HtmlEncode($this->pc_varFasesRoteiro->CurrentValue);
			$this->pc_varFasesRoteiro->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->pc_varFasesRoteiro->FldCaption()));
			if (strval($this->pc_varFasesRoteiro->EditValue) <> "" && is_numeric($this->pc_varFasesRoteiro->EditValue)) $this->pc_varFasesRoteiro->EditValue = ew_FormatNumber($this->pc_varFasesRoteiro->EditValue, -2, -1, -2, 0);

			// vr_qtPf
			$this->vr_qtPf->EditCustomAttributes = "readonly";
			$this->vr_qtPf->EditValue = ew_HtmlEncode($this->vr_qtPf->CurrentValue);
			$this->vr_qtPf->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_qtPf->FldCaption()));
			if (strval($this->vr_qtPf->EditValue) <> "" && is_numeric($this->vr_qtPf->EditValue)) $this->vr_qtPf->EditValue = ew_FormatNumber($this->vr_qtPf->EditValue, -2, -1, -2, 0);

			// ic_analalogia
			$this->ic_analalogia->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_analalogia->FldTagValue(1), $this->ic_analalogia->FldTagCaption(1) <> "" ? $this->ic_analalogia->FldTagCaption(1) : $this->ic_analalogia->FldTagValue(1));
			$arwrk[] = array($this->ic_analalogia->FldTagValue(2), $this->ic_analalogia->FldTagCaption(2) <> "" ? $this->ic_analalogia->FldTagCaption(2) : $this->ic_analalogia->FldTagValue(2));
			$this->ic_analalogia->EditValue = $arwrk;

			// ds_observacoes
			$this->ds_observacoes->EditCustomAttributes = "";
			$this->ds_observacoes->EditValue = $this->ds_observacoes->CurrentValue;
			$this->ds_observacoes->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_observacoes->FldCaption()));

			// nu_usuarioLogado
			// dh_inclusao
			// Edit refer script
			// nu_contagem

			$this->nu_contagem->HrefValue = "";

			// nu_agrupador
			$this->nu_agrupador->HrefValue = "";

			// nu_uc
			$this->nu_uc->HrefValue = "";

			// no_funcao
			$this->no_funcao->HrefValue = "";

			// nu_tpManutencao
			$this->nu_tpManutencao->HrefValue = "";

			// nu_tpElemento
			$this->nu_tpElemento->HrefValue = "";

			// qt_alr
			$this->qt_alr->HrefValue = "";

			// ds_alr
			$this->ds_alr->HrefValue = "";

			// qt_der
			$this->qt_der->HrefValue = "";

			// ds_der
			$this->ds_der->HrefValue = "";

			// ic_complexApf
			$this->ic_complexApf->HrefValue = "";

			// vr_contribuicao
			$this->vr_contribuicao->HrefValue = "";

			// vr_fatorReducao
			$this->vr_fatorReducao->HrefValue = "";

			// pc_varFasesRoteiro
			$this->pc_varFasesRoteiro->HrefValue = "";

			// vr_qtPf
			$this->vr_qtPf->HrefValue = "";

			// ic_analalogia
			$this->ic_analalogia->HrefValue = "";

			// ds_observacoes
			$this->ds_observacoes->HrefValue = "";

			// nu_usuarioLogado
			$this->nu_usuarioLogado->HrefValue = "";

			// dh_inclusao
			$this->dh_inclusao->HrefValue = "";
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
		if (!$this->nu_contagem->FldIsDetailKey && !is_null($this->nu_contagem->FormValue) && $this->nu_contagem->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_contagem->FldCaption());
		}
		if (!ew_CheckInteger($this->nu_contagem->FormValue)) {
			ew_AddMessage($gsFormError, $this->nu_contagem->FldErrMsg());
		}
		if (!$this->nu_agrupador->FldIsDetailKey && !is_null($this->nu_agrupador->FormValue) && $this->nu_agrupador->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_agrupador->FldCaption());
		}
		if (!$this->no_funcao->FldIsDetailKey && !is_null($this->no_funcao->FormValue) && $this->no_funcao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_funcao->FldCaption());
		}
		if (!$this->nu_tpManutencao->FldIsDetailKey && !is_null($this->nu_tpManutencao->FormValue) && $this->nu_tpManutencao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_tpManutencao->FldCaption());
		}
		if (!$this->nu_tpElemento->FldIsDetailKey && !is_null($this->nu_tpElemento->FormValue) && $this->nu_tpElemento->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_tpElemento->FldCaption());
		}
		if (!ew_CheckInteger($this->qt_alr->FormValue)) {
			ew_AddMessage($gsFormError, $this->qt_alr->FldErrMsg());
		}
		if (!ew_CheckInteger($this->qt_der->FormValue)) {
			ew_AddMessage($gsFormError, $this->qt_der->FldErrMsg());
		}
		if (!ew_CheckInteger($this->vr_contribuicao->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_contribuicao->FldErrMsg());
		}
		if (!ew_CheckNumber($this->pc_varFasesRoteiro->FormValue)) {
			ew_AddMessage($gsFormError, $this->pc_varFasesRoteiro->FldErrMsg());
		}
		if (!ew_CheckNumber($this->vr_qtPf->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_qtPf->FldErrMsg());
		}
		if ($this->ic_analalogia->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_analalogia->FldCaption());
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

		// nu_contagem
		$this->nu_contagem->SetDbValueDef($rsnew, $this->nu_contagem->CurrentValue, 0, FALSE);

		// nu_agrupador
		$this->nu_agrupador->SetDbValueDef($rsnew, $this->nu_agrupador->CurrentValue, 0, FALSE);

		// nu_uc
		$this->nu_uc->SetDbValueDef($rsnew, $this->nu_uc->CurrentValue, NULL, FALSE);

		// no_funcao
		$this->no_funcao->SetDbValueDef($rsnew, $this->no_funcao->CurrentValue, "", FALSE);

		// nu_tpManutencao
		$this->nu_tpManutencao->SetDbValueDef($rsnew, $this->nu_tpManutencao->CurrentValue, NULL, FALSE);

		// nu_tpElemento
		$this->nu_tpElemento->SetDbValueDef($rsnew, $this->nu_tpElemento->CurrentValue, NULL, FALSE);

		// qt_alr
		$this->qt_alr->SetDbValueDef($rsnew, $this->qt_alr->CurrentValue, NULL, FALSE);

		// ds_alr
		$this->ds_alr->SetDbValueDef($rsnew, $this->ds_alr->CurrentValue, NULL, FALSE);

		// qt_der
		$this->qt_der->SetDbValueDef($rsnew, $this->qt_der->CurrentValue, NULL, FALSE);

		// ds_der
		$this->ds_der->SetDbValueDef($rsnew, $this->ds_der->CurrentValue, NULL, FALSE);

		// ic_complexApf
		$this->ic_complexApf->SetDbValueDef($rsnew, $this->ic_complexApf->CurrentValue, NULL, FALSE);

		// vr_contribuicao
		$this->vr_contribuicao->SetDbValueDef($rsnew, $this->vr_contribuicao->CurrentValue, NULL, FALSE);

		// vr_fatorReducao
		$this->vr_fatorReducao->SetDbValueDef($rsnew, $this->vr_fatorReducao->CurrentValue, NULL, FALSE);

		// pc_varFasesRoteiro
		$this->pc_varFasesRoteiro->SetDbValueDef($rsnew, $this->pc_varFasesRoteiro->CurrentValue, NULL, FALSE);

		// vr_qtPf
		$this->vr_qtPf->SetDbValueDef($rsnew, $this->vr_qtPf->CurrentValue, NULL, FALSE);

		// ic_analalogia
		$this->ic_analalogia->SetDbValueDef($rsnew, $this->ic_analalogia->CurrentValue, NULL, FALSE);

		// ds_observacoes
		$this->ds_observacoes->SetDbValueDef($rsnew, $this->ds_observacoes->CurrentValue, NULL, FALSE);

		// nu_usuarioLogado
		$this->nu_usuarioLogado->SetDbValueDef($rsnew, CurrentUserID(), NULL);
		$rsnew['nu_usuarioLogado'] = &$this->nu_usuarioLogado->DbValue;

		// dh_inclusao
		$this->dh_inclusao->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
		$rsnew['dh_inclusao'] = &$this->dh_inclusao->DbValue;

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
			$this->nu_funcao->setDbValue($conn->Insert_ID());
			$rsnew['nu_funcao'] = $this->nu_funcao->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
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
			if ($sMasterTblVar == "contagempf") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_contagem"] <> "") {
					$GLOBALS["contagempf"]->nu_contagem->setQueryStringValue($_GET["nu_contagem"]);
					$this->nu_contagem->setQueryStringValue($GLOBALS["contagempf"]->nu_contagem->QueryStringValue);
					$this->nu_contagem->setSessionValue($this->nu_contagem->QueryStringValue);
					if (!is_numeric($GLOBALS["contagempf"]->nu_contagem->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "contagempf") {
				if ($this->nu_contagem->QueryStringValue == "") $this->nu_contagem->setSessionValue("");
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "contagempf_funcaolist.php", $this->TableVar);
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
if (!isset($contagempf_funcao_add)) $contagempf_funcao_add = new ccontagempf_funcao_add();

// Page init
$contagempf_funcao_add->Page_Init();

// Page main
$contagempf_funcao_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$contagempf_funcao_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var contagempf_funcao_add = new ew_Page("contagempf_funcao_add");
contagempf_funcao_add.PageID = "add"; // Page ID
var EW_PAGE_ID = contagempf_funcao_add.PageID; // For backward compatibility

// Form object
var fcontagempf_funcaoadd = new ew_Form("fcontagempf_funcaoadd");

// Validate form
fcontagempf_funcaoadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_contagem");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($contagempf_funcao->nu_contagem->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_contagem");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($contagempf_funcao->nu_contagem->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nu_agrupador");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($contagempf_funcao->nu_agrupador->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no_funcao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($contagempf_funcao->no_funcao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_tpManutencao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($contagempf_funcao->nu_tpManutencao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_tpElemento");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($contagempf_funcao->nu_tpElemento->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_qt_alr");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($contagempf_funcao->qt_alr->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_qt_der");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($contagempf_funcao->qt_der->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vr_contribuicao");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($contagempf_funcao->vr_contribuicao->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_pc_varFasesRoteiro");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($contagempf_funcao->pc_varFasesRoteiro->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vr_qtPf");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($contagempf_funcao->vr_qtPf->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ic_analalogia");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($contagempf_funcao->ic_analalogia->FldCaption()) ?>");

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
fcontagempf_funcaoadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcontagempf_funcaoadd.ValidateRequired = true;
<?php } else { ?>
fcontagempf_funcaoadd.ValidateRequired = false; 
<?php } ?>

// Multi-Page properties
fcontagempf_funcaoadd.MultiPage = new ew_MultiPage("fcontagempf_funcaoadd",
	[["x_nu_contagem",2],["x_nu_agrupador",1],["x_nu_uc",1],["x_no_funcao",1],["x_nu_tpManutencao",1],["x_nu_tpElemento",1],["x_qt_alr",1],["x_ds_alr",2],["x_qt_der",1],["x_ds_der",2],["x_ic_complexApf",1],["x_vr_contribuicao",1],["x_vr_fatorReducao",1],["x_pc_varFasesRoteiro",1],["x_vr_qtPf",1],["x_ic_analalogia",2],["x_ds_observacoes",1]]
);

// Dynamic selection lists
fcontagempf_funcaoadd.Lists["x_nu_contagem"] = {"LinkField":"x_nu_contagem","Ajax":true,"AutoFill":false,"DisplayFields":["x_nu_contagem","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempf_funcaoadd.Lists["x_nu_agrupador"] = {"LinkField":"x_nu_agrupador","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_agrupador","","",""],"ParentFields":["x_nu_contagem"],"FilterFields":["x_nu_contagem"],"Options":[]};
fcontagempf_funcaoadd.Lists["x_nu_uc"] = {"LinkField":"x_nu_uc","Ajax":true,"AutoFill":false,"DisplayFields":["x_co_alternativo","x_no_uc","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempf_funcaoadd.Lists["x_nu_tpManutencao"] = {"LinkField":"x_nu_tpManutencao","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpManutencao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempf_funcaoadd.Lists["x_nu_tpElemento"] = {"LinkField":"x_nu_tpElemento","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpElemento","","",""],"ParentFields":["x_nu_tpManutencao"],"FilterFields":["x_nu_tpManutencao"],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.        
function CalcularPF() {    
 var NuContagem = $("#x_nu_contagem").val();
  var TpManutencao = $("#x_nu_tpManutencao").val();
  var TpFuncao = $("#x_nu_tpElemento option:selected").text();
  var QtAlrOuRlr = parseInt($("#x_qt_alr").val(), 10);        
  var QtDer = parseInt($("#x_qt_der").val(), 10);                             
  var NoComplex = ""; 
  var Contribuicao = 0;
  var PcRoteiro = parseInt($("#x_pc_varFasesRoteiro").val(), 10); 
  $.ajax({
   type: "POST",
   url: "processa.php",
   data: {
	parametro: NuContagem,
	tpacao: "OTM"
   },
   success: function(data) {
	TpMetrica = data;
   }                              
  });
  $.ajax({
   type: "POST",
   url: "processa.php",
   data: {
	parametro: TpManutencao,
	tpacao: "OMC"
   },
   success: function(data) {
	ModeloCalculo = data;
   }                              
  });
  if (TpMetrica == "I") {   
	switch (TpFuncao) {          
		case "ALI":
			NoComplex = "-";    
			Contribuicao = 35; 
			break;                       
		case "AIE":
			NoComplex = "-";
			Contribuicao = 15;                                 
			break;               
	}    
	$("#x_vr_contribuicao").val(Contribuicao);                      
	$("#x_ic_complexApf").val(NoComplex[0]);
	$("#x_vr_fatorReducao").val("1.00");
	$("#x_vr_qtPf").val(Contribuicao * PcRoteiro/100); 
	  $("#x_qt_alr").val("");
	  $("#x_qt_der").val(""); 
  }
  if (TpMetrica == "E") {    
	switch (TpFuncao) {          
		case "ALI":
			NoComplex = "B";    
			Contribuicao = 7; 
			break;                       
		case "AIE":
			NoComplex = "B";
			Contribuicao = 5;                                 
			break;               
		case "EE":
			NoComplex = "M";    
			Contribuicao = 4; 
			break;                       
		case "CE":
			NoComplex = "M";
			Contribuicao = 4;                                 
			break;    
		case "SE":
			NoComplex = "M";    
			Contribuicao = 5; 
			break;                       
	}    
	$("#x_vr_contribuicao").val(Contribuicao);                      
	$("#x_ic_complexApf").val(NoComplex[0]);
	$("#x_vr_fatorReducao").val("1,00");
	$("#x_vr_qtPf").val(Contribuicao * PcRoteiro/100);   
	  $("#x_qt_alr").val("");
	  $("#x_qt_der").val("");                         
  }
  if (TpMetrica == "D") {
	 switch (ModeloCalculo) {          
	case "I":
	  if (QtAlrOuRlr >= 0 && QtDer >= 1) {
		switch (TpFuncao) {                         
			case "ALI":                                   
				switch (true) {                                          
					case (QtAlrOuRlr == 1) :                  
						switch (true) {          
							case (QtDer >= 1 && QtDer <= 19) :
								NoComplex = "Baixa";    
								Contribuicao = 7; 
								break;                       
							case (QtDer >= 20 && QtDer <= 50) :
								NoComplex = "Baixa";
								Contribuicao = 7;                                 
								break;              
							case (QtDer > 50) :        
								NoComplex = "Media";
								Contribuicao = 10; 
								break;            
						}
						break;
					case (QtAlrOuRlr >= 2 && QtAlrOuRlr <= 5) : 
						switch (true) {          
							case (QtDer >= 1 && QtDer <= 19) :
								NoComplex = "Baixa";
								Contribuicao = 7; 
								break;                   
							case (QtDer >= 20 && QtDer <= 50) :
								NoComplex = "Media";
								Contribuicao = 10; 
								break;  
							case (QtDer > 50) :        
								NoComplex = "Alta";
								Contribuicao = 15; 
								break;                        
						}
						break;                        
					case (QtAlrOuRlr > 5) : 
						switch (true) {          
							case (QtDer >= 1 && QtDer <= 19) :
								NoComplex = "Media";
								Contribuicao = 10; 
								break;                   
							case (QtDer >= 20 && QtDer <= 50) :
								NoComplex = "Alta";
								Contribuicao = 15; 
								break;                  
							case (QtDer > 50) :        
								NoComplex = "Alta";
								Contribuicao = 15; 
								break;                                               
						}
						break;
					default :               
				}
				break;
			case "AIE":                                   
				switch (true) {  
					case (QtAlrOuRlr == 1) : 
						switch (true) {          
							case (QtDer >= 1 && QtDer <= 19) :
								NoComplex = "Baixa";
								Contribuicao = 5; 
								break;                   
							case (QtDer >= 20 && QtDer <= 50) :
								NoComplex = "Baixa";
								Contribuicao = 5; 
								break;          
							case (QtDer > 50) :        
								NoComplex = "Media";
								Contribuicao = 7; 
								break;                         
						} 
						break;
					case (QtAlrOuRlr >= 2 && QtAlrOuRlr <= 5) : 
						switch (true) {          
							case (QtDer >= 1 && QtDer <= 19) :
								NoComplex = "Baixa";     
								Contribuicao = 5; 
								break;                   
							case (QtDer >= 20 && QtDer <= 50) :
								NoComplex = "Media"; 
								Contribuicao = 7;                                 
								break;  
							case (QtDer > 50) :        
								NoComplex = "Alta";     
								Contribuicao = 10; 
								break;                         
						}     
						break;
					case (QtAlrOuRlr > 5) : 
						switch (true) {          
							case (QtDer >= 1 && QtDer <= 19) :
								NoComplex = "Media";
								Contribuicao = 7;                                 
								break;                   
							case (QtDer >= 20 && QtDer <= 50) :
								NoComplex = "Alta";     
								Contribuicao = 10; 
								break;                  
							case (QtDer > 50) :        
								NoComplex = "Alta";
								Contribuicao = 10;                                 
								break;                                             
						}  
						break;
					default :               
				}
				break;
			case "EE":                                   
				switch (true) {  
					case ((QtAlrOuRlr == 0) || (QtAlrOuRlr == 1)) : 
						switch (true) {          
							case (QtDer >= 1 && QtDer <= 4) :
								NoComplex = "Baixa";
								Contribuicao = 3; 
								break;                   
							case (QtDer >= 5 && QtDer <= 15) :
								NoComplex = "Baixa";
								Contribuicao = 3; 
								break;          
							case (QtDer > 15) :        
								NoComplex = "Media";
								Contribuicao = 4; 
								break;                           
						} 
						break;
					case (QtAlrOuRlr == 2) : 
						switch (true) {          
							case (QtDer >= 1 && QtDer <= 4) :
								NoComplex = "Baixa";     
								Contribuicao = 3; 
								break;                   
							case (QtDer >= 5 && QtDer <= 15) :
								NoComplex = "Media";
								Contribuicao = 4;                                 
								break;  
							case (QtDer > 15) :        
								NoComplex = "Alta";     
								Contribuicao = 6; 
								break;                       
						}     
						break;
					case (QtAlrOuRlr > 2) : 
						switch (true) {          
							case (QtDer >= 1 && QtDer <= 4) :
								NoComplex = "Media";
								Contribuicao = 4;                                 
								break;                   
							case (QtDer >= 5 && QtDer <= 15) :
								NoComplex = "Alta"; 
								Contribuicao = 6; 
								break;                  
							case (QtDer > 15) :        
								NoComplex = "Alta";
								Contribuicao = 6; 
								break;                                               
						}  
						break;
					default :               
				}
				break;                
			case "CE":                                   
				switch (true) {  
					case (QtAlrOuRlr == 1) : 
						switch (true) {          
							case (QtDer >= 1 && QtDer <= 5) :
								NoComplex = "Baixa";     
								Contribuicao = 3; 
								break;                   
							case (QtDer >= 6 && QtDer <= 19) :
								NoComplex = "Baixa";
								Contribuicao = 3;                                 
								break;          
							case (QtDer > 19) :        
								NoComplex = "Media";     
								Contribuicao = 4; 
								break;                          
						} 
						break;
					case ((QtAlrOuRlr == 2) || (QtAlrOuRlr == 3)) : 
						switch (true) {          
							case (QtDer >= 1 && QtDer <= 5) :
								NoComplex = "Baixa";
								Contribuicao = 3;                                 
								break;                   
							case (QtDer >= 6 && QtDer <= 19) :
								NoComplex = "Media";
								Contribuicao = 4; 
								break;  
							case (QtDer > 19) :        
								NoComplex = "Alta";
								Contribuicao = 6; 
								break;                      
						}     
						break;
					case (QtAlrOuRlr > 3) : 
						switch (true) {          
							case (QtDer >= 1 && QtDer <= 5) :
								NoComplex = "Media";
								Contribuicao = 4; 
								break;                   
							case (QtDer >= 6 && QtDer <= 19) :
								NoComplex = "Alta";
								Contribuicao = 6; 
								break;                  
							case (QtDer > 19) :        
								NoComplex = "Alta";
								Contribuicao = 6; 
								break;                                                
						}  
						break;
					default :               
				}
				break;                
			case "SE":                                   
				switch (true) {  
					case ((QtAlrOuRlr == 0) || (QtAlrOuRlr == 1)) : 
						switch (true) {          
							case (QtDer >= 1 && QtDer <= 5) :
								NoComplex = "Baixa";     
								Contribuicao = 4; 
								break;                   
							case (QtDer >= 6 && QtDer <= 19) :
								NoComplex = "Baixa";     
								Contribuicao = 4; 
								break;          
							case (QtDer > 19) :        
								NoComplex = "Media";
								Contribuicao = 5;                                 
								break;                           
						} 
						break;
					case ((QtAlrOuRlr == 2) || (QtAlrOuRlr == 3)) : 
						switch (true) {          
							case (QtDer >= 1 && QtDer <= 5) :
								NoComplex = "Baixa";     
								Contribuicao = 4; 
								break;                   
							case (QtDer >= 6 && QtDer <= 19) :
								NoComplex = "Media";     
								Contribuicao = 5; 
								break;  
							case (QtDer > 19) :        
								NoComplex = "Alta";
								Contribuicao = 7;                                 
								break;                         
						}     
						break;
					case (QtAlrOuRlr > 3) : 
						switch (true) {          
							case (QtDer >= 1 && QtDer <= 5) :
								NoComplex = "Media";  
								Contribuicao = 5; 
								break;                   
							case (QtDer >= 6 && QtDer <= 19) :
								NoComplex = "Alta";
								Contribuicao = 7;                                 
								break;                  
							case (QtDer > 19) :        
								NoComplex = "Alta";     
								Contribuicao = 7; 
								break;                                              
						}  
						break;
					default :               
				}
				break;                
		   default :
		}   
		$("#x_vr_contribuicao").val(Contribuicao);                      
		$("#x_ic_complexApf").val(NoComplex[0]);
		var fator = 0;
		$.ajax({
			type: "POST",
			url: "processa.php",
			data: {
			  parametro: $('#x_nu_tpManutencao').val(),
			  tpacao: "CalcularPF"
			},
			success: function(data) {
			  $("#x_vr_fatorReducao").val(floorFigure(data) + "");
			  CalcularFases();
			}                              
		});
	  } 
	  break;
	  case "F":
		$("#x_vr_qtPf").val("");
		$("#x_qt_alr").val("");
		$("#x_qt_der").val("");
		$("#x_vr_contribuicao").val("");                      
		$("#x_ic_complexApf").val("-");
		$("#x_vr_fatorReducao").val("");
		$.ajax({
			type: "POST",
			url: "processa.php",
			data: {
			  parametro: $('#x_nu_tpManutencao').val(),
			  tpacao: "CalcularPF"
			},
			success: function(data) {
			  $("#x_vr_qtPf").val(floorFigure(data) + "");
			}                              
		});   
	break;  
	 }
  }
};

function CalcularFases() {              
	var inputs,i, s = 0.00;
	var f = $("#x_vr_fatorReducao").val();
	var c = $("#x_vr_contribuicao").val();    
	$.ajax({
		type: "POST",
		url: "processa.php",
		data: {           
		  parametro: $('#x_nu_contagem').val(),
		  tpacao: "ObterDistribuicaoFase"
		},                                 
		success: function(data) { 
			s = parseFloat(data) + s; 
			CalcularPfFinal(s, f, c);           
			$("#x_pc_varFasesRoteiro").val(data); 
		}                             
	});                 
};               

function CalcularPfFinal(s, fator, contribuicao) {
	$("#x_pc_varFasesRoteiro").val(floorFigure(s) + "");
	var f = parseFloat(s/100);             
	$.ajax({
		type: "POST",
		url: "processa.php",
		data: {
		  parametro: $('#x_nu_tpManutencao').val(),
		  tpacao: "VerificaAplicabilidadeCalculoPfFases"
		},                                
		success: function(data) {
		  if(data=="S") { 
			$("#x_vr_qtPf").val(floorFigure(contribuicao * fator * f) + "");
		  } else {
			$("#x_vr_qtPf").val(floorFigure(contribuicao * fator) + "");
		  }             
		}                              
	});
};   

function floorFigure(figure, decimals){
	 if (!decimals) decimals = 2;
	 var d = Math.pow(10,decimals);
	 return ((figure*d)/d).toFixed(decimals);
};
</script>
<?php $Breadcrumb->Render(); ?>
<?php $contagempf_funcao_add->ShowPageHeader(); ?>
<?php
$contagempf_funcao_add->ShowMessage();
?>
<form name="fcontagempf_funcaoadd" id="fcontagempf_funcaoadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="contagempf_funcao">
<input type="hidden" name="a_add" id="a_add" value="A">
<table class="ewStdTable"><tbody><tr><td>
<div class="tabbable" id="contagempf_funcao_add">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_contagempf_funcao1" data-toggle="tab"><?php echo $contagempf_funcao->PageCaption(1) ?></a></li>
		<li><a href="#tab_contagempf_funcao2" data-toggle="tab"><?php echo $contagempf_funcao->PageCaption(2) ?></a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab_contagempf_funcao1">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_contagempf_funcaoadd1" class="table table-bordered table-striped">
<?php if ($contagempf_funcao->nu_agrupador->Visible) { // nu_agrupador ?>
	<tr id="r_nu_agrupador">
		<td><span id="elh_contagempf_funcao_nu_agrupador"><?php echo $contagempf_funcao->nu_agrupador->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $contagempf_funcao->nu_agrupador->CellAttributes() ?>>
<span id="el_contagempf_funcao_nu_agrupador" class="control-group">
<select data-field="x_nu_agrupador" id="x_nu_agrupador" name="x_nu_agrupador"<?php echo $contagempf_funcao->nu_agrupador->EditAttributes() ?>>
<?php
if (is_array($contagempf_funcao->nu_agrupador->EditValue)) {
	$arwrk = $contagempf_funcao->nu_agrupador->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf_funcao->nu_agrupador->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if (AllowAdd(CurrentProjectID() . "contagempf_agrupador")) { ?>
&nbsp;<a id="aol_x_nu_agrupador" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_nu_agrupador',url:'contagempf_agrupadoraddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $contagempf_funcao->nu_agrupador->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
fcontagempf_funcaoadd.Lists["x_nu_agrupador"].Options = <?php echo (is_array($contagempf_funcao->nu_agrupador->EditValue)) ? ew_ArrayToJson($contagempf_funcao->nu_agrupador->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $contagempf_funcao->nu_agrupador->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contagempf_funcao->nu_uc->Visible) { // nu_uc ?>
	<tr id="r_nu_uc">
		<td><span id="elh_contagempf_funcao_nu_uc"><?php echo $contagempf_funcao->nu_uc->FldCaption() ?></span></td>
		<td<?php echo $contagempf_funcao->nu_uc->CellAttributes() ?>>
<span id="el_contagempf_funcao_nu_uc" class="control-group">
<select data-field="x_nu_uc" id="x_nu_uc" name="x_nu_uc"<?php echo $contagempf_funcao->nu_uc->EditAttributes() ?>>
<?php
if (is_array($contagempf_funcao->nu_uc->EditValue)) {
	$arwrk = $contagempf_funcao->nu_uc->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf_funcao->nu_uc->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$contagempf_funcao->nu_uc) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<?php
$sSqlWrk = "SELECT [nu_uc], [co_alternativo] AS [DispFld], [no_uc] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[uc]";
$sWhereWrk = "";
$lookuptblfilter = "[nu_sistema] = (SELECT nu_sistema FROM contagempf WHERE nu_contagem = " . strval(CurrentPage()->nu_contagem->CurrentValue) . ")";
if (strval($lookuptblfilter) <> "") {
	ew_AddFilter($sWhereWrk, $lookuptblfilter);
}

// Call Lookup selecting
$contagempf_funcao->Lookup_Selecting($contagempf_funcao->nu_uc, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY [co_alternativo] ASC";
?>
<input type="hidden" name="s_x_nu_uc" id="s_x_nu_uc" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&f0=<?php echo ew_Encrypt("[nu_uc] = {filter_value}"); ?>&t0=3">
</span>
<?php echo $contagempf_funcao->nu_uc->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contagempf_funcao->no_funcao->Visible) { // no_funcao ?>
	<tr id="r_no_funcao">
		<td><span id="elh_contagempf_funcao_no_funcao"><?php echo $contagempf_funcao->no_funcao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $contagempf_funcao->no_funcao->CellAttributes() ?>>
<span id="el_contagempf_funcao_no_funcao" class="control-group">
<input type="text" data-field="x_no_funcao" name="x_no_funcao" id="x_no_funcao" size="100" maxlength="120" placeholder="<?php echo $contagempf_funcao->no_funcao->PlaceHolder ?>" value="<?php echo $contagempf_funcao->no_funcao->EditValue ?>"<?php echo $contagempf_funcao->no_funcao->EditAttributes() ?>>
</span>
<?php echo $contagempf_funcao->no_funcao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contagempf_funcao->nu_tpManutencao->Visible) { // nu_tpManutencao ?>
	<tr id="r_nu_tpManutencao">
		<td><span id="elh_contagempf_funcao_nu_tpManutencao"><?php echo $contagempf_funcao->nu_tpManutencao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $contagempf_funcao->nu_tpManutencao->CellAttributes() ?>>
<span id="el_contagempf_funcao_nu_tpManutencao" class="control-group">
<?php $contagempf_funcao->nu_tpManutencao->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_tpElemento']); " . @$contagempf_funcao->nu_tpManutencao->EditAttrs["onchange"]; ?>
<select data-field="x_nu_tpManutencao" id="x_nu_tpManutencao" name="x_nu_tpManutencao"<?php echo $contagempf_funcao->nu_tpManutencao->EditAttributes() ?>>
<?php
if (is_array($contagempf_funcao->nu_tpManutencao->EditValue)) {
	$arwrk = $contagempf_funcao->nu_tpManutencao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf_funcao->nu_tpManutencao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fcontagempf_funcaoadd.Lists["x_nu_tpManutencao"].Options = <?php echo (is_array($contagempf_funcao->nu_tpManutencao->EditValue)) ? ew_ArrayToJson($contagempf_funcao->nu_tpManutencao->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $contagempf_funcao->nu_tpManutencao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contagempf_funcao->nu_tpElemento->Visible) { // nu_tpElemento ?>
	<tr id="r_nu_tpElemento">
		<td><span id="elh_contagempf_funcao_nu_tpElemento"><?php echo $contagempf_funcao->nu_tpElemento->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $contagempf_funcao->nu_tpElemento->CellAttributes() ?>>
<span id="el_contagempf_funcao_nu_tpElemento" class="control-group">
<select data-field="x_nu_tpElemento" id="x_nu_tpElemento" name="x_nu_tpElemento"<?php echo $contagempf_funcao->nu_tpElemento->EditAttributes() ?>>
<?php
if (is_array($contagempf_funcao->nu_tpElemento->EditValue)) {
	$arwrk = $contagempf_funcao->nu_tpElemento->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf_funcao->nu_tpElemento->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fcontagempf_funcaoadd.Lists["x_nu_tpElemento"].Options = <?php echo (is_array($contagempf_funcao->nu_tpElemento->EditValue)) ? ew_ArrayToJson($contagempf_funcao->nu_tpElemento->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $contagempf_funcao->nu_tpElemento->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contagempf_funcao->qt_alr->Visible) { // qt_alr ?>
	<tr id="r_qt_alr">
		<td><span id="elh_contagempf_funcao_qt_alr"><?php echo $contagempf_funcao->qt_alr->FldCaption() ?></span></td>
		<td<?php echo $contagempf_funcao->qt_alr->CellAttributes() ?>>
<span id="el_contagempf_funcao_qt_alr" class="control-group">
<input type="text" data-field="x_qt_alr" name="x_qt_alr" id="x_qt_alr" size="4" maxlength="4" placeholder="<?php echo $contagempf_funcao->qt_alr->PlaceHolder ?>" value="<?php echo $contagempf_funcao->qt_alr->EditValue ?>"<?php echo $contagempf_funcao->qt_alr->EditAttributes() ?>>
</span>
<?php echo $contagempf_funcao->qt_alr->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contagempf_funcao->qt_der->Visible) { // qt_der ?>
	<tr id="r_qt_der">
		<td><span id="elh_contagempf_funcao_qt_der"><?php echo $contagempf_funcao->qt_der->FldCaption() ?></span></td>
		<td<?php echo $contagempf_funcao->qt_der->CellAttributes() ?>>
<span id="el_contagempf_funcao_qt_der" class="control-group">
<input type="text" data-field="x_qt_der" name="x_qt_der" id="x_qt_der" size="4" maxlength="4" placeholder="<?php echo $contagempf_funcao->qt_der->PlaceHolder ?>" value="<?php echo $contagempf_funcao->qt_der->EditValue ?>"<?php echo $contagempf_funcao->qt_der->EditAttributes() ?>>
</span>
<?php echo $contagempf_funcao->qt_der->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contagempf_funcao->ic_complexApf->Visible) { // ic_complexApf ?>
	<tr id="r_ic_complexApf">
		<td><span id="elh_contagempf_funcao_ic_complexApf"><?php echo $contagempf_funcao->ic_complexApf->FldCaption() ?></span></td>
		<td<?php echo $contagempf_funcao->ic_complexApf->CellAttributes() ?>>
<span id="el_contagempf_funcao_ic_complexApf" class="control-group">
<input type="text" data-field="x_ic_complexApf" name="x_ic_complexApf" id="x_ic_complexApf" size="30" placeholder="<?php echo $contagempf_funcao->ic_complexApf->PlaceHolder ?>" value="<?php echo $contagempf_funcao->ic_complexApf->EditValue ?>"<?php echo $contagempf_funcao->ic_complexApf->EditAttributes() ?>>
</span>
<?php echo $contagempf_funcao->ic_complexApf->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contagempf_funcao->vr_contribuicao->Visible) { // vr_contribuicao ?>
	<tr id="r_vr_contribuicao">
		<td><span id="elh_contagempf_funcao_vr_contribuicao"><?php echo $contagempf_funcao->vr_contribuicao->FldCaption() ?></span></td>
		<td<?php echo $contagempf_funcao->vr_contribuicao->CellAttributes() ?>>
<span id="el_contagempf_funcao_vr_contribuicao" class="control-group">
<input type="text" data-field="x_vr_contribuicao" name="x_vr_contribuicao" id="x_vr_contribuicao" size="30" placeholder="<?php echo $contagempf_funcao->vr_contribuicao->PlaceHolder ?>" value="<?php echo $contagempf_funcao->vr_contribuicao->EditValue ?>"<?php echo $contagempf_funcao->vr_contribuicao->EditAttributes() ?>>
</span>
<?php echo $contagempf_funcao->vr_contribuicao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contagempf_funcao->vr_fatorReducao->Visible) { // vr_fatorReducao ?>
	<tr id="r_vr_fatorReducao">
		<td><span id="elh_contagempf_funcao_vr_fatorReducao"><?php echo $contagempf_funcao->vr_fatorReducao->FldCaption() ?></span></td>
		<td<?php echo $contagempf_funcao->vr_fatorReducao->CellAttributes() ?>>
<span id="el_contagempf_funcao_vr_fatorReducao" class="control-group">
<input type="text" data-field="x_vr_fatorReducao" name="x_vr_fatorReducao" id="x_vr_fatorReducao" size="30" placeholder="<?php echo $contagempf_funcao->vr_fatorReducao->PlaceHolder ?>" value="<?php echo $contagempf_funcao->vr_fatorReducao->EditValue ?>"<?php echo $contagempf_funcao->vr_fatorReducao->EditAttributes() ?>>
</span>
<?php echo $contagempf_funcao->vr_fatorReducao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contagempf_funcao->pc_varFasesRoteiro->Visible) { // pc_varFasesRoteiro ?>
	<tr id="r_pc_varFasesRoteiro">
		<td><span id="elh_contagempf_funcao_pc_varFasesRoteiro"><?php echo $contagempf_funcao->pc_varFasesRoteiro->FldCaption() ?></span></td>
		<td<?php echo $contagempf_funcao->pc_varFasesRoteiro->CellAttributes() ?>>
<span id="el_contagempf_funcao_pc_varFasesRoteiro" class="control-group">
<input type="text" data-field="x_pc_varFasesRoteiro" name="x_pc_varFasesRoteiro" id="x_pc_varFasesRoteiro" size="30" placeholder="<?php echo $contagempf_funcao->pc_varFasesRoteiro->PlaceHolder ?>" value="<?php echo $contagempf_funcao->pc_varFasesRoteiro->EditValue ?>"<?php echo $contagempf_funcao->pc_varFasesRoteiro->EditAttributes() ?>>
</span>
<?php echo $contagempf_funcao->pc_varFasesRoteiro->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contagempf_funcao->vr_qtPf->Visible) { // vr_qtPf ?>
	<tr id="r_vr_qtPf">
		<td><span id="elh_contagempf_funcao_vr_qtPf"><?php echo $contagempf_funcao->vr_qtPf->FldCaption() ?></span></td>
		<td<?php echo $contagempf_funcao->vr_qtPf->CellAttributes() ?>>
<span id="el_contagempf_funcao_vr_qtPf" class="control-group">
<input type="text" data-field="x_vr_qtPf" name="x_vr_qtPf" id="x_vr_qtPf" size="10" placeholder="<?php echo $contagempf_funcao->vr_qtPf->PlaceHolder ?>" value="<?php echo $contagempf_funcao->vr_qtPf->EditValue ?>"<?php echo $contagempf_funcao->vr_qtPf->EditAttributes() ?>>
</span>
<?php echo $contagempf_funcao->vr_qtPf->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contagempf_funcao->ds_observacoes->Visible) { // ds_observacoes ?>
	<tr id="r_ds_observacoes">
		<td><span id="elh_contagempf_funcao_ds_observacoes"><?php echo $contagempf_funcao->ds_observacoes->FldCaption() ?></span></td>
		<td<?php echo $contagempf_funcao->ds_observacoes->CellAttributes() ?>>
<span id="el_contagempf_funcao_ds_observacoes" class="control-group">
<textarea data-field="x_ds_observacoes" name="x_ds_observacoes" id="x_ds_observacoes" cols="35" rows="4" placeholder="<?php echo $contagempf_funcao->ds_observacoes->PlaceHolder ?>"<?php echo $contagempf_funcao->ds_observacoes->EditAttributes() ?>><?php echo $contagempf_funcao->ds_observacoes->EditValue ?></textarea>
</span>
<?php echo $contagempf_funcao->ds_observacoes->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_contagempf_funcao2">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_contagempf_funcaoadd2" class="table table-bordered table-striped">
<?php if ($contagempf_funcao->nu_contagem->Visible) { // nu_contagem ?>
	<tr id="r_nu_contagem">
		<td><span id="elh_contagempf_funcao_nu_contagem"><?php echo $contagempf_funcao->nu_contagem->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $contagempf_funcao->nu_contagem->CellAttributes() ?>>
<?php if ($contagempf_funcao->nu_contagem->getSessionValue() <> "") { ?>
<span<?php echo $contagempf_funcao->nu_contagem->ViewAttributes() ?>>
<?php echo $contagempf_funcao->nu_contagem->ViewValue ?></span>
<input type="hidden" id="x_nu_contagem" name="x_nu_contagem" value="<?php echo ew_HtmlEncode($contagempf_funcao->nu_contagem->CurrentValue) ?>">
<?php } else { ?>
<?php
	$wrkonchange = trim("ew_UpdateOpt.call(this, ['x_nu_agrupador']); " . @$contagempf_funcao->nu_contagem->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$contagempf_funcao->nu_contagem->EditAttrs["onchange"] = "";
?>
<span id="as_x_nu_contagem" style="white-space: nowrap; z-index: 8990">
	<input type="text" name="sv_x_nu_contagem" id="sv_x_nu_contagem" value="<?php echo $contagempf_funcao->nu_contagem->EditValue ?>" size="30" placeholder="<?php echo $contagempf_funcao->nu_contagem->PlaceHolder ?>"<?php echo $contagempf_funcao->nu_contagem->EditAttributes() ?>>&nbsp;<span id="em_x_nu_contagem" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_nu_contagem" style="display: inline; z-index: 8990"></div>
</span>
<input type="hidden" data-field="x_nu_contagem" name="x_nu_contagem" id="x_nu_contagem" value="<?php echo $contagempf_funcao->nu_contagem->CurrentValue ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT  TOP " . EW_AUTO_SUGGEST_MAX_ENTRIES . " [nu_contagem], [nu_contagem] AS [DispFld] FROM [dbo].[contagempf]";
$sWhereWrk = "CAST([nu_contagem] AS NVARCHAR) LIKE '%{query_value}%'";

// Call Lookup selecting
$contagempf_funcao->Lookup_Selecting($contagempf_funcao->nu_contagem, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="q_x_nu_contagem" id="q_x_nu_contagem" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x_nu_contagem", fcontagempf_funcaoadd, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
fcontagempf_funcaoadd.AutoSuggests["x_nu_contagem"] = oas;
</script>
<?php } ?>
<?php echo $contagempf_funcao->nu_contagem->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contagempf_funcao->ds_alr->Visible) { // ds_alr ?>
	<tr id="r_ds_alr">
		<td><span id="elh_contagempf_funcao_ds_alr"><?php echo $contagempf_funcao->ds_alr->FldCaption() ?></span></td>
		<td<?php echo $contagempf_funcao->ds_alr->CellAttributes() ?>>
<span id="el_contagempf_funcao_ds_alr" class="control-group">
<textarea data-field="x_ds_alr" name="x_ds_alr" id="x_ds_alr" cols="35" rows="4" placeholder="<?php echo $contagempf_funcao->ds_alr->PlaceHolder ?>"<?php echo $contagempf_funcao->ds_alr->EditAttributes() ?>><?php echo $contagempf_funcao->ds_alr->EditValue ?></textarea>
</span>
<?php echo $contagempf_funcao->ds_alr->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contagempf_funcao->ds_der->Visible) { // ds_der ?>
	<tr id="r_ds_der">
		<td><span id="elh_contagempf_funcao_ds_der"><?php echo $contagempf_funcao->ds_der->FldCaption() ?></span></td>
		<td<?php echo $contagempf_funcao->ds_der->CellAttributes() ?>>
<span id="el_contagempf_funcao_ds_der" class="control-group">
<textarea data-field="x_ds_der" name="x_ds_der" id="x_ds_der" cols="35" rows="4" placeholder="<?php echo $contagempf_funcao->ds_der->PlaceHolder ?>"<?php echo $contagempf_funcao->ds_der->EditAttributes() ?>><?php echo $contagempf_funcao->ds_der->EditValue ?></textarea>
</span>
<?php echo $contagempf_funcao->ds_der->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contagempf_funcao->ic_analalogia->Visible) { // ic_analalogia ?>
	<tr id="r_ic_analalogia">
		<td><span id="elh_contagempf_funcao_ic_analalogia"><?php echo $contagempf_funcao->ic_analalogia->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $contagempf_funcao->ic_analalogia->CellAttributes() ?>>
<span id="el_contagempf_funcao_ic_analalogia" class="control-group">
<div id="tp_x_ic_analalogia" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_analalogia" id="x_ic_analalogia" value="{value}"<?php echo $contagempf_funcao->ic_analalogia->EditAttributes() ?>></div>
<div id="dsl_x_ic_analalogia" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $contagempf_funcao->ic_analalogia->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf_funcao->ic_analalogia->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_analalogia" name="x_ic_analalogia" id="x_ic_analalogia_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $contagempf_funcao->ic_analalogia->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $contagempf_funcao->ic_analalogia->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
	</div>
</div>
</td></tr></tbody></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fcontagempf_funcaoadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$contagempf_funcao_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$contagempf_funcao_add->Page_Terminate();
?>
