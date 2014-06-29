<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "laudoinfo.php" ?>
<?php include_once "solicitacaometricasinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$laudo_add = NULL; // Initialize page object first

class claudo_add extends claudo {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'laudo';

	// Page object name
	var $PageObjName = 'laudo_add';

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

		// Table object (laudo)
		if (!isset($GLOBALS["laudo"])) {
			$GLOBALS["laudo"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["laudo"];
		}

		// Table object (solicitacaoMetricas)
		if (!isset($GLOBALS['solicitacaoMetricas'])) $GLOBALS['solicitacaoMetricas'] = new csolicitacaoMetricas();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'laudo', TRUE);

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
			$this->Page_Terminate("laudolist.php");
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
			if (@$_GET["nu_solicitacao"] != "") {
				$this->nu_solicitacao->setQueryStringValue($_GET["nu_solicitacao"]);
				$this->setKey("nu_solicitacao", $this->nu_solicitacao->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_solicitacao", ""); // Clear key
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
					$this->Page_Terminate("laudolist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "laudoview.php")
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
		$this->nu_solicitacao->CurrentValue = NULL;
		$this->nu_solicitacao->OldValue = $this->nu_solicitacao->CurrentValue;
		$this->nu_versao->CurrentValue = "1";
		$this->ds_sobreDocumentacao->CurrentValue = NULL;
		$this->ds_sobreDocumentacao->OldValue = $this->ds_sobreDocumentacao->CurrentValue;
		$this->ds_sobreMetrificacao->CurrentValue = NULL;
		$this->ds_sobreMetrificacao->OldValue = $this->ds_sobreMetrificacao->CurrentValue;
		$this->qt_pf->CurrentValue = NULL;
		$this->qt_pf->OldValue = $this->qt_pf->CurrentValue;
		$this->qt_horas->CurrentValue = NULL;
		$this->qt_horas->OldValue = $this->qt_horas->CurrentValue;
		$this->qt_prazoMeses->CurrentValue = NULL;
		$this->qt_prazoMeses->OldValue = $this->qt_prazoMeses->CurrentValue;
		$this->qt_prazoDias->CurrentValue = NULL;
		$this->qt_prazoDias->OldValue = $this->qt_prazoDias->CurrentValue;
		$this->vr_contratacao->CurrentValue = NULL;
		$this->vr_contratacao->OldValue = $this->vr_contratacao->CurrentValue;
		$this->nu_usuarioResp->CurrentValue = NULL;
		$this->nu_usuarioResp->OldValue = $this->nu_usuarioResp->CurrentValue;
		$this->dt_inicioSolicitacao->CurrentValue = NULL;
		$this->dt_inicioSolicitacao->OldValue = $this->dt_inicioSolicitacao->CurrentValue;
		$this->dt_inicioContagem->CurrentValue = NULL;
		$this->dt_inicioContagem->OldValue = $this->dt_inicioContagem->CurrentValue;
		$this->dt_emissao->CurrentValue = NULL;
		$this->dt_emissao->OldValue = $this->dt_emissao->CurrentValue;
		$this->hh_emissao->CurrentValue = NULL;
		$this->hh_emissao->OldValue = $this->hh_emissao->CurrentValue;
		$this->ic_tamanho->CurrentValue = "S";
		$this->ic_esforco->CurrentValue = "N";
		$this->ic_prazo->CurrentValue = "N";
		$this->ic_custo->CurrentValue = "N";
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_solicitacao->FldIsDetailKey) {
			$this->nu_solicitacao->setFormValue($objForm->GetValue("x_nu_solicitacao"));
		}
		if (!$this->nu_versao->FldIsDetailKey) {
			$this->nu_versao->setFormValue($objForm->GetValue("x_nu_versao"));
		}
		if (!$this->ds_sobreDocumentacao->FldIsDetailKey) {
			$this->ds_sobreDocumentacao->setFormValue($objForm->GetValue("x_ds_sobreDocumentacao"));
		}
		if (!$this->ds_sobreMetrificacao->FldIsDetailKey) {
			$this->ds_sobreMetrificacao->setFormValue($objForm->GetValue("x_ds_sobreMetrificacao"));
		}
		if (!$this->qt_pf->FldIsDetailKey) {
			$this->qt_pf->setFormValue($objForm->GetValue("x_qt_pf"));
		}
		if (!$this->qt_horas->FldIsDetailKey) {
			$this->qt_horas->setFormValue($objForm->GetValue("x_qt_horas"));
		}
		if (!$this->qt_prazoMeses->FldIsDetailKey) {
			$this->qt_prazoMeses->setFormValue($objForm->GetValue("x_qt_prazoMeses"));
		}
		if (!$this->qt_prazoDias->FldIsDetailKey) {
			$this->qt_prazoDias->setFormValue($objForm->GetValue("x_qt_prazoDias"));
		}
		if (!$this->vr_contratacao->FldIsDetailKey) {
			$this->vr_contratacao->setFormValue($objForm->GetValue("x_vr_contratacao"));
		}
		if (!$this->nu_usuarioResp->FldIsDetailKey) {
			$this->nu_usuarioResp->setFormValue($objForm->GetValue("x_nu_usuarioResp"));
		}
		if (!$this->dt_inicioSolicitacao->FldIsDetailKey) {
			$this->dt_inicioSolicitacao->setFormValue($objForm->GetValue("x_dt_inicioSolicitacao"));
			$this->dt_inicioSolicitacao->CurrentValue = ew_UnFormatDateTime($this->dt_inicioSolicitacao->CurrentValue, 7);
		}
		if (!$this->dt_inicioContagem->FldIsDetailKey) {
			$this->dt_inicioContagem->setFormValue($objForm->GetValue("x_dt_inicioContagem"));
			$this->dt_inicioContagem->CurrentValue = ew_UnFormatDateTime($this->dt_inicioContagem->CurrentValue, 7);
		}
		if (!$this->dt_emissao->FldIsDetailKey) {
			$this->dt_emissao->setFormValue($objForm->GetValue("x_dt_emissao"));
			$this->dt_emissao->CurrentValue = ew_UnFormatDateTime($this->dt_emissao->CurrentValue, 7);
		}
		if (!$this->hh_emissao->FldIsDetailKey) {
			$this->hh_emissao->setFormValue($objForm->GetValue("x_hh_emissao"));
		}
		if (!$this->ic_tamanho->FldIsDetailKey) {
			$this->ic_tamanho->setFormValue($objForm->GetValue("x_ic_tamanho"));
		}
		if (!$this->ic_esforco->FldIsDetailKey) {
			$this->ic_esforco->setFormValue($objForm->GetValue("x_ic_esforco"));
		}
		if (!$this->ic_prazo->FldIsDetailKey) {
			$this->ic_prazo->setFormValue($objForm->GetValue("x_ic_prazo"));
		}
		if (!$this->ic_custo->FldIsDetailKey) {
			$this->ic_custo->setFormValue($objForm->GetValue("x_ic_custo"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->nu_solicitacao->CurrentValue = $this->nu_solicitacao->FormValue;
		$this->nu_versao->CurrentValue = $this->nu_versao->FormValue;
		$this->ds_sobreDocumentacao->CurrentValue = $this->ds_sobreDocumentacao->FormValue;
		$this->ds_sobreMetrificacao->CurrentValue = $this->ds_sobreMetrificacao->FormValue;
		$this->qt_pf->CurrentValue = $this->qt_pf->FormValue;
		$this->qt_horas->CurrentValue = $this->qt_horas->FormValue;
		$this->qt_prazoMeses->CurrentValue = $this->qt_prazoMeses->FormValue;
		$this->qt_prazoDias->CurrentValue = $this->qt_prazoDias->FormValue;
		$this->vr_contratacao->CurrentValue = $this->vr_contratacao->FormValue;
		$this->nu_usuarioResp->CurrentValue = $this->nu_usuarioResp->FormValue;
		$this->dt_inicioSolicitacao->CurrentValue = $this->dt_inicioSolicitacao->FormValue;
		$this->dt_inicioSolicitacao->CurrentValue = ew_UnFormatDateTime($this->dt_inicioSolicitacao->CurrentValue, 7);
		$this->dt_inicioContagem->CurrentValue = $this->dt_inicioContagem->FormValue;
		$this->dt_inicioContagem->CurrentValue = ew_UnFormatDateTime($this->dt_inicioContagem->CurrentValue, 7);
		$this->dt_emissao->CurrentValue = $this->dt_emissao->FormValue;
		$this->dt_emissao->CurrentValue = ew_UnFormatDateTime($this->dt_emissao->CurrentValue, 7);
		$this->hh_emissao->CurrentValue = $this->hh_emissao->FormValue;
		$this->ic_tamanho->CurrentValue = $this->ic_tamanho->FormValue;
		$this->ic_esforco->CurrentValue = $this->ic_esforco->FormValue;
		$this->ic_prazo->CurrentValue = $this->ic_prazo->FormValue;
		$this->ic_custo->CurrentValue = $this->ic_custo->FormValue;
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
		$this->nu_solicitacao->setDbValue($rs->fields('nu_solicitacao'));
		$this->nu_versao->setDbValue($rs->fields('nu_versao'));
		$this->ds_sobreDocumentacao->setDbValue($rs->fields('ds_sobreDocumentacao'));
		$this->ds_sobreMetrificacao->setDbValue($rs->fields('ds_sobreMetrificacao'));
		$this->qt_pf->setDbValue($rs->fields('qt_pf'));
		$this->qt_horas->setDbValue($rs->fields('qt_horas'));
		$this->qt_prazoMeses->setDbValue($rs->fields('qt_prazoMeses'));
		$this->qt_prazoDias->setDbValue($rs->fields('qt_prazoDias'));
		$this->vr_contratacao->setDbValue($rs->fields('vr_contratacao'));
		$this->nu_usuarioResp->setDbValue($rs->fields('nu_usuarioResp'));
		$this->dt_inicioSolicitacao->setDbValue($rs->fields('dt_inicioSolicitacao'));
		$this->dt_inicioContagem->setDbValue($rs->fields('dt_inicioContagem'));
		$this->dt_emissao->setDbValue($rs->fields('dt_emissao'));
		$this->hh_emissao->setDbValue($rs->fields('hh_emissao'));
		$this->ic_tamanho->setDbValue($rs->fields('ic_tamanho'));
		$this->ic_esforco->setDbValue($rs->fields('ic_esforco'));
		$this->ic_prazo->setDbValue($rs->fields('ic_prazo'));
		$this->ic_custo->setDbValue($rs->fields('ic_custo'));
		$this->ic_bloqueio->setDbValue($rs->fields('ic_bloqueio'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_solicitacao->DbValue = $row['nu_solicitacao'];
		$this->nu_versao->DbValue = $row['nu_versao'];
		$this->ds_sobreDocumentacao->DbValue = $row['ds_sobreDocumentacao'];
		$this->ds_sobreMetrificacao->DbValue = $row['ds_sobreMetrificacao'];
		$this->qt_pf->DbValue = $row['qt_pf'];
		$this->qt_horas->DbValue = $row['qt_horas'];
		$this->qt_prazoMeses->DbValue = $row['qt_prazoMeses'];
		$this->qt_prazoDias->DbValue = $row['qt_prazoDias'];
		$this->vr_contratacao->DbValue = $row['vr_contratacao'];
		$this->nu_usuarioResp->DbValue = $row['nu_usuarioResp'];
		$this->dt_inicioSolicitacao->DbValue = $row['dt_inicioSolicitacao'];
		$this->dt_inicioContagem->DbValue = $row['dt_inicioContagem'];
		$this->dt_emissao->DbValue = $row['dt_emissao'];
		$this->hh_emissao->DbValue = $row['hh_emissao'];
		$this->ic_tamanho->DbValue = $row['ic_tamanho'];
		$this->ic_esforco->DbValue = $row['ic_esforco'];
		$this->ic_prazo->DbValue = $row['ic_prazo'];
		$this->ic_custo->DbValue = $row['ic_custo'];
		$this->ic_bloqueio->DbValue = $row['ic_bloqueio'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_solicitacao")) <> "")
			$this->nu_solicitacao->CurrentValue = $this->getKey("nu_solicitacao"); // nu_solicitacao
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
		// Convert decimal values if posted back

		if ($this->qt_pf->FormValue == $this->qt_pf->CurrentValue && is_numeric(ew_StrToFloat($this->qt_pf->CurrentValue)))
			$this->qt_pf->CurrentValue = ew_StrToFloat($this->qt_pf->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_horas->FormValue == $this->qt_horas->CurrentValue && is_numeric(ew_StrToFloat($this->qt_horas->CurrentValue)))
			$this->qt_horas->CurrentValue = ew_StrToFloat($this->qt_horas->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_prazoMeses->FormValue == $this->qt_prazoMeses->CurrentValue && is_numeric(ew_StrToFloat($this->qt_prazoMeses->CurrentValue)))
			$this->qt_prazoMeses->CurrentValue = ew_StrToFloat($this->qt_prazoMeses->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_contratacao->FormValue == $this->vr_contratacao->CurrentValue && is_numeric(ew_StrToFloat($this->vr_contratacao->CurrentValue)))
			$this->vr_contratacao->CurrentValue = ew_StrToFloat($this->vr_contratacao->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_solicitacao
		// nu_versao
		// ds_sobreDocumentacao
		// ds_sobreMetrificacao
		// qt_pf
		// qt_horas
		// qt_prazoMeses
		// qt_prazoDias
		// vr_contratacao
		// nu_usuarioResp
		// dt_inicioSolicitacao
		// dt_inicioContagem
		// dt_emissao
		// hh_emissao
		// ic_tamanho
		// ic_esforco
		// ic_prazo
		// ic_custo
		// ic_bloqueio

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_solicitacao
			if (strval($this->nu_solicitacao->CurrentValue) <> "") {
				$sFilterWrk = "[nu_solMetricas]" . ew_SearchString("=", $this->nu_solicitacao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_solMetricas], [nu_solMetricas] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[solicitacaoMetricas]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_solicitacao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_solMetricas] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_solicitacao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_solicitacao->ViewValue = $this->nu_solicitacao->CurrentValue;
				}
			} else {
				$this->nu_solicitacao->ViewValue = NULL;
			}
			$this->nu_solicitacao->ViewCustomAttributes = "";

			// nu_versao
			$this->nu_versao->ViewValue = $this->nu_versao->CurrentValue;
			$this->nu_versao->ViewCustomAttributes = "";

			// ds_sobreDocumentacao
			$this->ds_sobreDocumentacao->ViewValue = $this->ds_sobreDocumentacao->CurrentValue;
			$this->ds_sobreDocumentacao->ViewCustomAttributes = "";

			// ds_sobreMetrificacao
			$this->ds_sobreMetrificacao->ViewValue = $this->ds_sobreMetrificacao->CurrentValue;
			$this->ds_sobreMetrificacao->ViewCustomAttributes = "";

			// qt_pf
			$this->qt_pf->ViewValue = $this->qt_pf->CurrentValue;
			$this->qt_pf->ViewCustomAttributes = "";

			// qt_horas
			$this->qt_horas->ViewValue = $this->qt_horas->CurrentValue;
			$this->qt_horas->ViewCustomAttributes = "";

			// qt_prazoMeses
			$this->qt_prazoMeses->ViewValue = $this->qt_prazoMeses->CurrentValue;
			$this->qt_prazoMeses->ViewCustomAttributes = "";

			// qt_prazoDias
			$this->qt_prazoDias->ViewValue = $this->qt_prazoDias->CurrentValue;
			$this->qt_prazoDias->ViewCustomAttributes = "";

			// vr_contratacao
			$this->vr_contratacao->ViewValue = $this->vr_contratacao->CurrentValue;
			$this->vr_contratacao->ViewValue = ew_FormatCurrency($this->vr_contratacao->ViewValue, 2, -2, -2, -2);
			$this->vr_contratacao->ViewCustomAttributes = "";

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
			$sSqlWrk .= " ORDER BY [no_usuario] ASC";
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

			// dt_inicioSolicitacao
			$this->dt_inicioSolicitacao->ViewValue = $this->dt_inicioSolicitacao->CurrentValue;
			$this->dt_inicioSolicitacao->ViewValue = ew_FormatDateTime($this->dt_inicioSolicitacao->ViewValue, 7);
			$this->dt_inicioSolicitacao->ViewCustomAttributes = "";

			// dt_inicioContagem
			$this->dt_inicioContagem->ViewValue = $this->dt_inicioContagem->CurrentValue;
			$this->dt_inicioContagem->ViewValue = ew_FormatDateTime($this->dt_inicioContagem->ViewValue, 7);
			$this->dt_inicioContagem->ViewCustomAttributes = "";

			// dt_emissao
			$this->dt_emissao->ViewValue = $this->dt_emissao->CurrentValue;
			$this->dt_emissao->ViewValue = ew_FormatDateTime($this->dt_emissao->ViewValue, 7);
			$this->dt_emissao->ViewCustomAttributes = "";

			// hh_emissao
			$this->hh_emissao->ViewValue = $this->hh_emissao->CurrentValue;
			$this->hh_emissao->ViewValue = ew_FormatDateTime($this->hh_emissao->ViewValue, 4);
			$this->hh_emissao->ViewCustomAttributes = "";

			// ic_tamanho
			if (strval($this->ic_tamanho->CurrentValue) <> "") {
				switch ($this->ic_tamanho->CurrentValue) {
					case $this->ic_tamanho->FldTagValue(1):
						$this->ic_tamanho->ViewValue = $this->ic_tamanho->FldTagCaption(1) <> "" ? $this->ic_tamanho->FldTagCaption(1) : $this->ic_tamanho->CurrentValue;
						break;
					case $this->ic_tamanho->FldTagValue(2):
						$this->ic_tamanho->ViewValue = $this->ic_tamanho->FldTagCaption(2) <> "" ? $this->ic_tamanho->FldTagCaption(2) : $this->ic_tamanho->CurrentValue;
						break;
					default:
						$this->ic_tamanho->ViewValue = $this->ic_tamanho->CurrentValue;
				}
			} else {
				$this->ic_tamanho->ViewValue = NULL;
			}
			$this->ic_tamanho->ViewCustomAttributes = "";

			// ic_esforco
			if (strval($this->ic_esforco->CurrentValue) <> "") {
				switch ($this->ic_esforco->CurrentValue) {
					case $this->ic_esforco->FldTagValue(1):
						$this->ic_esforco->ViewValue = $this->ic_esforco->FldTagCaption(1) <> "" ? $this->ic_esforco->FldTagCaption(1) : $this->ic_esforco->CurrentValue;
						break;
					case $this->ic_esforco->FldTagValue(2):
						$this->ic_esforco->ViewValue = $this->ic_esforco->FldTagCaption(2) <> "" ? $this->ic_esforco->FldTagCaption(2) : $this->ic_esforco->CurrentValue;
						break;
					default:
						$this->ic_esforco->ViewValue = $this->ic_esforco->CurrentValue;
				}
			} else {
				$this->ic_esforco->ViewValue = NULL;
			}
			$this->ic_esforco->ViewCustomAttributes = "";

			// ic_prazo
			if (strval($this->ic_prazo->CurrentValue) <> "") {
				switch ($this->ic_prazo->CurrentValue) {
					case $this->ic_prazo->FldTagValue(1):
						$this->ic_prazo->ViewValue = $this->ic_prazo->FldTagCaption(1) <> "" ? $this->ic_prazo->FldTagCaption(1) : $this->ic_prazo->CurrentValue;
						break;
					case $this->ic_prazo->FldTagValue(2):
						$this->ic_prazo->ViewValue = $this->ic_prazo->FldTagCaption(2) <> "" ? $this->ic_prazo->FldTagCaption(2) : $this->ic_prazo->CurrentValue;
						break;
					default:
						$this->ic_prazo->ViewValue = $this->ic_prazo->CurrentValue;
				}
			} else {
				$this->ic_prazo->ViewValue = NULL;
			}
			$this->ic_prazo->ViewCustomAttributes = "";

			// ic_custo
			if (strval($this->ic_custo->CurrentValue) <> "") {
				switch ($this->ic_custo->CurrentValue) {
					case $this->ic_custo->FldTagValue(1):
						$this->ic_custo->ViewValue = $this->ic_custo->FldTagCaption(1) <> "" ? $this->ic_custo->FldTagCaption(1) : $this->ic_custo->CurrentValue;
						break;
					case $this->ic_custo->FldTagValue(2):
						$this->ic_custo->ViewValue = $this->ic_custo->FldTagCaption(2) <> "" ? $this->ic_custo->FldTagCaption(2) : $this->ic_custo->CurrentValue;
						break;
					default:
						$this->ic_custo->ViewValue = $this->ic_custo->CurrentValue;
				}
			} else {
				$this->ic_custo->ViewValue = NULL;
			}
			$this->ic_custo->ViewCustomAttributes = "";

			// ic_bloqueio
			$this->ic_bloqueio->ViewValue = $this->ic_bloqueio->CurrentValue;
			$this->ic_bloqueio->ViewCustomAttributes = "";

			// nu_solicitacao
			$this->nu_solicitacao->LinkCustomAttributes = "";
			$this->nu_solicitacao->HrefValue = "";
			$this->nu_solicitacao->TooltipValue = "";

			// nu_versao
			$this->nu_versao->LinkCustomAttributes = "";
			$this->nu_versao->HrefValue = "";
			$this->nu_versao->TooltipValue = "";

			// ds_sobreDocumentacao
			$this->ds_sobreDocumentacao->LinkCustomAttributes = "";
			$this->ds_sobreDocumentacao->HrefValue = "";
			$this->ds_sobreDocumentacao->TooltipValue = "";

			// ds_sobreMetrificacao
			$this->ds_sobreMetrificacao->LinkCustomAttributes = "";
			$this->ds_sobreMetrificacao->HrefValue = "";
			$this->ds_sobreMetrificacao->TooltipValue = "";

			// qt_pf
			$this->qt_pf->LinkCustomAttributes = "";
			$this->qt_pf->HrefValue = "";
			$this->qt_pf->TooltipValue = "";

			// qt_horas
			$this->qt_horas->LinkCustomAttributes = "";
			$this->qt_horas->HrefValue = "";
			$this->qt_horas->TooltipValue = "";

			// qt_prazoMeses
			$this->qt_prazoMeses->LinkCustomAttributes = "";
			$this->qt_prazoMeses->HrefValue = "";
			$this->qt_prazoMeses->TooltipValue = "";

			// qt_prazoDias
			$this->qt_prazoDias->LinkCustomAttributes = "";
			$this->qt_prazoDias->HrefValue = "";
			$this->qt_prazoDias->TooltipValue = "";

			// vr_contratacao
			$this->vr_contratacao->LinkCustomAttributes = "";
			$this->vr_contratacao->HrefValue = "";
			$this->vr_contratacao->TooltipValue = "";

			// nu_usuarioResp
			$this->nu_usuarioResp->LinkCustomAttributes = "";
			$this->nu_usuarioResp->HrefValue = "";
			$this->nu_usuarioResp->TooltipValue = "";

			// dt_inicioSolicitacao
			$this->dt_inicioSolicitacao->LinkCustomAttributes = "";
			$this->dt_inicioSolicitacao->HrefValue = "";
			$this->dt_inicioSolicitacao->TooltipValue = "";

			// dt_inicioContagem
			$this->dt_inicioContagem->LinkCustomAttributes = "";
			$this->dt_inicioContagem->HrefValue = "";
			$this->dt_inicioContagem->TooltipValue = "";

			// dt_emissao
			$this->dt_emissao->LinkCustomAttributes = "";
			$this->dt_emissao->HrefValue = "";
			$this->dt_emissao->TooltipValue = "";

			// hh_emissao
			$this->hh_emissao->LinkCustomAttributes = "";
			$this->hh_emissao->HrefValue = "";
			$this->hh_emissao->TooltipValue = "";

			// ic_tamanho
			$this->ic_tamanho->LinkCustomAttributes = "";
			$this->ic_tamanho->HrefValue = "";
			$this->ic_tamanho->TooltipValue = "";

			// ic_esforco
			$this->ic_esforco->LinkCustomAttributes = "";
			$this->ic_esforco->HrefValue = "";
			$this->ic_esforco->TooltipValue = "";

			// ic_prazo
			$this->ic_prazo->LinkCustomAttributes = "";
			$this->ic_prazo->HrefValue = "";
			$this->ic_prazo->TooltipValue = "";

			// ic_custo
			$this->ic_custo->LinkCustomAttributes = "";
			$this->ic_custo->HrefValue = "";
			$this->ic_custo->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nu_solicitacao
			$this->nu_solicitacao->EditCustomAttributes = "";
			if ($this->nu_solicitacao->getSessionValue() <> "") {
				$this->nu_solicitacao->CurrentValue = $this->nu_solicitacao->getSessionValue();
			if (strval($this->nu_solicitacao->CurrentValue) <> "") {
				$sFilterWrk = "[nu_solMetricas]" . ew_SearchString("=", $this->nu_solicitacao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_solMetricas], [nu_solMetricas] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[solicitacaoMetricas]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_solicitacao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_solMetricas] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_solicitacao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_solicitacao->ViewValue = $this->nu_solicitacao->CurrentValue;
				}
			} else {
				$this->nu_solicitacao->ViewValue = NULL;
			}
			$this->nu_solicitacao->ViewCustomAttributes = "";
			} else {
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_solMetricas], [nu_solMetricas] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[solicitacaoMetricas]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_solicitacao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_solMetricas] DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_solicitacao->EditValue = $arwrk;
			}

			// nu_versao
			$this->nu_versao->EditCustomAttributes = "readonly";
			$this->nu_versao->EditValue = ew_HtmlEncode($this->nu_versao->CurrentValue);
			$this->nu_versao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_versao->FldCaption()));

			// ds_sobreDocumentacao
			$this->ds_sobreDocumentacao->EditCustomAttributes = "";
			$this->ds_sobreDocumentacao->EditValue = $this->ds_sobreDocumentacao->CurrentValue;
			$this->ds_sobreDocumentacao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_sobreDocumentacao->FldCaption()));

			// ds_sobreMetrificacao
			$this->ds_sobreMetrificacao->EditCustomAttributes = "";
			$this->ds_sobreMetrificacao->EditValue = $this->ds_sobreMetrificacao->CurrentValue;
			$this->ds_sobreMetrificacao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_sobreMetrificacao->FldCaption()));

			// qt_pf
			$this->qt_pf->EditCustomAttributes = "readonly";
			$this->qt_pf->EditValue = ew_HtmlEncode($this->qt_pf->CurrentValue);
			$this->qt_pf->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_pf->FldCaption()));
			if (strval($this->qt_pf->EditValue) <> "" && is_numeric($this->qt_pf->EditValue)) $this->qt_pf->EditValue = ew_FormatNumber($this->qt_pf->EditValue, -2, -1, -2, 0);

			// qt_horas
			$this->qt_horas->EditCustomAttributes = "readonly";
			$this->qt_horas->EditValue = ew_HtmlEncode($this->qt_horas->CurrentValue);
			$this->qt_horas->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_horas->FldCaption()));
			if (strval($this->qt_horas->EditValue) <> "" && is_numeric($this->qt_horas->EditValue)) $this->qt_horas->EditValue = ew_FormatNumber($this->qt_horas->EditValue, -2, -1, -2, 0);

			// qt_prazoMeses
			$this->qt_prazoMeses->EditCustomAttributes = "readonly";
			$this->qt_prazoMeses->EditValue = ew_HtmlEncode($this->qt_prazoMeses->CurrentValue);
			$this->qt_prazoMeses->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_prazoMeses->FldCaption()));
			if (strval($this->qt_prazoMeses->EditValue) <> "" && is_numeric($this->qt_prazoMeses->EditValue)) $this->qt_prazoMeses->EditValue = ew_FormatNumber($this->qt_prazoMeses->EditValue, -2, -1, -2, 0);

			// qt_prazoDias
			$this->qt_prazoDias->EditCustomAttributes = "readonly";
			$this->qt_prazoDias->EditValue = ew_HtmlEncode($this->qt_prazoDias->CurrentValue);
			$this->qt_prazoDias->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_prazoDias->FldCaption()));

			// vr_contratacao
			$this->vr_contratacao->EditCustomAttributes = "readonly";
			$this->vr_contratacao->EditValue = ew_HtmlEncode($this->vr_contratacao->CurrentValue);
			$this->vr_contratacao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_contratacao->FldCaption()));
			if (strval($this->vr_contratacao->EditValue) <> "" && is_numeric($this->vr_contratacao->EditValue)) $this->vr_contratacao->EditValue = ew_FormatNumber($this->vr_contratacao->EditValue, -2, -2, -2, -2);

			// nu_usuarioResp
			// dt_inicioSolicitacao

			$this->dt_inicioSolicitacao->EditCustomAttributes = "readonly";
			$this->dt_inicioSolicitacao->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->dt_inicioSolicitacao->CurrentValue, 7));
			$this->dt_inicioSolicitacao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dt_inicioSolicitacao->FldCaption()));

			// dt_inicioContagem
			$this->dt_inicioContagem->EditCustomAttributes = "readonly";
			$this->dt_inicioContagem->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->dt_inicioContagem->CurrentValue, 7));
			$this->dt_inicioContagem->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dt_inicioContagem->FldCaption()));

			// dt_emissao
			// hh_emissao
			// ic_tamanho

			$this->ic_tamanho->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_tamanho->FldTagValue(1), $this->ic_tamanho->FldTagCaption(1) <> "" ? $this->ic_tamanho->FldTagCaption(1) : $this->ic_tamanho->FldTagValue(1));
			$arwrk[] = array($this->ic_tamanho->FldTagValue(2), $this->ic_tamanho->FldTagCaption(2) <> "" ? $this->ic_tamanho->FldTagCaption(2) : $this->ic_tamanho->FldTagValue(2));
			$this->ic_tamanho->EditValue = $arwrk;

			// ic_esforco
			$this->ic_esforco->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_esforco->FldTagValue(1), $this->ic_esforco->FldTagCaption(1) <> "" ? $this->ic_esforco->FldTagCaption(1) : $this->ic_esforco->FldTagValue(1));
			$arwrk[] = array($this->ic_esforco->FldTagValue(2), $this->ic_esforco->FldTagCaption(2) <> "" ? $this->ic_esforco->FldTagCaption(2) : $this->ic_esforco->FldTagValue(2));
			$this->ic_esforco->EditValue = $arwrk;

			// ic_prazo
			$this->ic_prazo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_prazo->FldTagValue(1), $this->ic_prazo->FldTagCaption(1) <> "" ? $this->ic_prazo->FldTagCaption(1) : $this->ic_prazo->FldTagValue(1));
			$arwrk[] = array($this->ic_prazo->FldTagValue(2), $this->ic_prazo->FldTagCaption(2) <> "" ? $this->ic_prazo->FldTagCaption(2) : $this->ic_prazo->FldTagValue(2));
			$this->ic_prazo->EditValue = $arwrk;

			// ic_custo
			$this->ic_custo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_custo->FldTagValue(1), $this->ic_custo->FldTagCaption(1) <> "" ? $this->ic_custo->FldTagCaption(1) : $this->ic_custo->FldTagValue(1));
			$arwrk[] = array($this->ic_custo->FldTagValue(2), $this->ic_custo->FldTagCaption(2) <> "" ? $this->ic_custo->FldTagCaption(2) : $this->ic_custo->FldTagValue(2));
			$this->ic_custo->EditValue = $arwrk;

			// Edit refer script
			// nu_solicitacao

			$this->nu_solicitacao->HrefValue = "";

			// nu_versao
			$this->nu_versao->HrefValue = "";

			// ds_sobreDocumentacao
			$this->ds_sobreDocumentacao->HrefValue = "";

			// ds_sobreMetrificacao
			$this->ds_sobreMetrificacao->HrefValue = "";

			// qt_pf
			$this->qt_pf->HrefValue = "";

			// qt_horas
			$this->qt_horas->HrefValue = "";

			// qt_prazoMeses
			$this->qt_prazoMeses->HrefValue = "";

			// qt_prazoDias
			$this->qt_prazoDias->HrefValue = "";

			// vr_contratacao
			$this->vr_contratacao->HrefValue = "";

			// nu_usuarioResp
			$this->nu_usuarioResp->HrefValue = "";

			// dt_inicioSolicitacao
			$this->dt_inicioSolicitacao->HrefValue = "";

			// dt_inicioContagem
			$this->dt_inicioContagem->HrefValue = "";

			// dt_emissao
			$this->dt_emissao->HrefValue = "";

			// hh_emissao
			$this->hh_emissao->HrefValue = "";

			// ic_tamanho
			$this->ic_tamanho->HrefValue = "";

			// ic_esforco
			$this->ic_esforco->HrefValue = "";

			// ic_prazo
			$this->ic_prazo->HrefValue = "";

			// ic_custo
			$this->ic_custo->HrefValue = "";
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
		if (!$this->nu_solicitacao->FldIsDetailKey && !is_null($this->nu_solicitacao->FormValue) && $this->nu_solicitacao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_solicitacao->FldCaption());
		}
		if (!$this->nu_versao->FldIsDetailKey && !is_null($this->nu_versao->FormValue) && $this->nu_versao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_versao->FldCaption());
		}
		if (!ew_CheckInteger($this->nu_versao->FormValue)) {
			ew_AddMessage($gsFormError, $this->nu_versao->FldErrMsg());
		}
		if (!$this->ds_sobreDocumentacao->FldIsDetailKey && !is_null($this->ds_sobreDocumentacao->FormValue) && $this->ds_sobreDocumentacao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ds_sobreDocumentacao->FldCaption());
		}
		if (!$this->ds_sobreMetrificacao->FldIsDetailKey && !is_null($this->ds_sobreMetrificacao->FormValue) && $this->ds_sobreMetrificacao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ds_sobreMetrificacao->FldCaption());
		}
		if (!ew_CheckNumber($this->qt_pf->FormValue)) {
			ew_AddMessage($gsFormError, $this->qt_pf->FldErrMsg());
		}
		if (!ew_CheckNumber($this->qt_horas->FormValue)) {
			ew_AddMessage($gsFormError, $this->qt_horas->FldErrMsg());
		}
		if (!ew_CheckNumber($this->qt_prazoMeses->FormValue)) {
			ew_AddMessage($gsFormError, $this->qt_prazoMeses->FldErrMsg());
		}
		if (!ew_CheckInteger($this->qt_prazoDias->FormValue)) {
			ew_AddMessage($gsFormError, $this->qt_prazoDias->FldErrMsg());
		}
		if (!ew_CheckNumber($this->vr_contratacao->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_contratacao->FldErrMsg());
		}
		if (!$this->dt_inicioSolicitacao->FldIsDetailKey && !is_null($this->dt_inicioSolicitacao->FormValue) && $this->dt_inicioSolicitacao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->dt_inicioSolicitacao->FldCaption());
		}
		if (!ew_CheckEuroDate($this->dt_inicioSolicitacao->FormValue)) {
			ew_AddMessage($gsFormError, $this->dt_inicioSolicitacao->FldErrMsg());
		}
		if (!$this->dt_inicioContagem->FldIsDetailKey && !is_null($this->dt_inicioContagem->FormValue) && $this->dt_inicioContagem->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->dt_inicioContagem->FldCaption());
		}
		if (!ew_CheckEuroDate($this->dt_inicioContagem->FormValue)) {
			ew_AddMessage($gsFormError, $this->dt_inicioContagem->FldErrMsg());
		}
		if ($this->ic_tamanho->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_tamanho->FldCaption());
		}
		if ($this->ic_esforco->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_esforco->FldCaption());
		}
		if ($this->ic_prazo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_prazo->FldCaption());
		}
		if ($this->ic_custo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_custo->FldCaption());
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

		// nu_solicitacao
		$this->nu_solicitacao->SetDbValueDef($rsnew, $this->nu_solicitacao->CurrentValue, 0, FALSE);

		// nu_versao
		$this->nu_versao->SetDbValueDef($rsnew, $this->nu_versao->CurrentValue, 0, FALSE);

		// ds_sobreDocumentacao
		$this->ds_sobreDocumentacao->SetDbValueDef($rsnew, $this->ds_sobreDocumentacao->CurrentValue, NULL, FALSE);

		// ds_sobreMetrificacao
		$this->ds_sobreMetrificacao->SetDbValueDef($rsnew, $this->ds_sobreMetrificacao->CurrentValue, NULL, FALSE);

		// qt_pf
		$this->qt_pf->SetDbValueDef($rsnew, $this->qt_pf->CurrentValue, NULL, FALSE);

		// qt_horas
		$this->qt_horas->SetDbValueDef($rsnew, $this->qt_horas->CurrentValue, NULL, FALSE);

		// qt_prazoMeses
		$this->qt_prazoMeses->SetDbValueDef($rsnew, $this->qt_prazoMeses->CurrentValue, NULL, FALSE);

		// qt_prazoDias
		$this->qt_prazoDias->SetDbValueDef($rsnew, $this->qt_prazoDias->CurrentValue, NULL, FALSE);

		// vr_contratacao
		$this->vr_contratacao->SetDbValueDef($rsnew, $this->vr_contratacao->CurrentValue, NULL, FALSE);

		// nu_usuarioResp
		$this->nu_usuarioResp->SetDbValueDef($rsnew, CurrentUserID(), NULL);
		$rsnew['nu_usuarioResp'] = &$this->nu_usuarioResp->DbValue;

		// dt_inicioSolicitacao
		$this->dt_inicioSolicitacao->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->dt_inicioSolicitacao->CurrentValue, 7), NULL, FALSE);

		// dt_inicioContagem
		$this->dt_inicioContagem->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->dt_inicioContagem->CurrentValue, 7), NULL, FALSE);

		// dt_emissao
		$this->dt_emissao->SetDbValueDef($rsnew, ew_CurrentDate(), ew_CurrentDate());
		$rsnew['dt_emissao'] = &$this->dt_emissao->DbValue;

		// hh_emissao
		$this->hh_emissao->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
		$rsnew['hh_emissao'] = &$this->hh_emissao->DbValue;

		// ic_tamanho
		$this->ic_tamanho->SetDbValueDef($rsnew, $this->ic_tamanho->CurrentValue, NULL, FALSE);

		// ic_esforco
		$this->ic_esforco->SetDbValueDef($rsnew, $this->ic_esforco->CurrentValue, NULL, FALSE);

		// ic_prazo
		$this->ic_prazo->SetDbValueDef($rsnew, $this->ic_prazo->CurrentValue, NULL, FALSE);

		// ic_custo
		$this->ic_custo->SetDbValueDef($rsnew, $this->ic_custo->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && $this->nu_solicitacao->CurrentValue == "" && $this->nu_solicitacao->getSessionValue() == "") {
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
			if ($sMasterTblVar == "solicitacaoMetricas") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_solMetricas"] <> "") {
					$GLOBALS["solicitacaoMetricas"]->nu_solMetricas->setQueryStringValue($_GET["nu_solMetricas"]);
					$this->nu_solicitacao->setQueryStringValue($GLOBALS["solicitacaoMetricas"]->nu_solMetricas->QueryStringValue);
					$this->nu_solicitacao->setSessionValue($this->nu_solicitacao->QueryStringValue);
					if (!is_numeric($GLOBALS["solicitacaoMetricas"]->nu_solMetricas->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "solicitacaoMetricas") {
				if ($this->nu_solicitacao->QueryStringValue == "") $this->nu_solicitacao->setSessionValue("");
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "laudolist.php", $this->TableVar);
		$PageCaption = ($this->CurrentAction == "C") ? $Language->Phrase("Copy") : $Language->Phrase("Add");
		$Breadcrumb->Add("add", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'laudo';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'laudo';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_solicitacao'];
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

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($laudo_add)) $laudo_add = new claudo_add();

// Page init
$laudo_add->Page_Init();

// Page main
$laudo_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$laudo_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var laudo_add = new ew_Page("laudo_add");
laudo_add.PageID = "add"; // Page ID
var EW_PAGE_ID = laudo_add.PageID; // For backward compatibility

// Form object
var flaudoadd = new ew_Form("flaudoadd");

// Validate form
flaudoadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_solicitacao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($laudo->nu_solicitacao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_versao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($laudo->nu_versao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_versao");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($laudo->nu_versao->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ds_sobreDocumentacao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($laudo->ds_sobreDocumentacao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ds_sobreMetrificacao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($laudo->ds_sobreMetrificacao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_qt_pf");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($laudo->qt_pf->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_qt_horas");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($laudo->qt_horas->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_qt_prazoMeses");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($laudo->qt_prazoMeses->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_qt_prazoDias");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($laudo->qt_prazoDias->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vr_contratacao");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($laudo->vr_contratacao->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_dt_inicioSolicitacao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($laudo->dt_inicioSolicitacao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dt_inicioSolicitacao");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($laudo->dt_inicioSolicitacao->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_dt_inicioContagem");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($laudo->dt_inicioContagem->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dt_inicioContagem");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($laudo->dt_inicioContagem->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ic_tamanho");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($laudo->ic_tamanho->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_esforco");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($laudo->ic_esforco->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_prazo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($laudo->ic_prazo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_custo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($laudo->ic_custo->FldCaption()) ?>");

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
flaudoadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
flaudoadd.ValidateRequired = true;
<?php } else { ?>
flaudoadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
flaudoadd.Lists["x_nu_solicitacao"] = {"LinkField":"x_nu_solMetricas","Ajax":null,"AutoFill":false,"DisplayFields":["x_nu_solMetricas","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
flaudoadd.Lists["x_nu_usuarioResp"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $laudo_add->ShowPageHeader(); ?>
<?php
$laudo_add->ShowMessage();
?>
<form name="flaudoadd" id="flaudoadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="laudo">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_laudoadd" class="table table-bordered table-striped">
<?php if ($laudo->nu_solicitacao->Visible) { // nu_solicitacao ?>
	<tr id="r_nu_solicitacao">
		<td><span id="elh_laudo_nu_solicitacao"><?php echo $laudo->nu_solicitacao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $laudo->nu_solicitacao->CellAttributes() ?>>
<?php if ($laudo->nu_solicitacao->getSessionValue() <> "") { ?>
<span<?php echo $laudo->nu_solicitacao->ViewAttributes() ?>>
<?php echo $laudo->nu_solicitacao->ViewValue ?></span>
<input type="hidden" id="x_nu_solicitacao" name="x_nu_solicitacao" value="<?php echo ew_HtmlEncode($laudo->nu_solicitacao->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_nu_solicitacao" id="x_nu_solicitacao" name="x_nu_solicitacao"<?php echo $laudo->nu_solicitacao->EditAttributes() ?>>
<?php
if (is_array($laudo->nu_solicitacao->EditValue)) {
	$arwrk = $laudo->nu_solicitacao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($laudo->nu_solicitacao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
flaudoadd.Lists["x_nu_solicitacao"].Options = <?php echo (is_array($laudo->nu_solicitacao->EditValue)) ? ew_ArrayToJson($laudo->nu_solicitacao->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<?php echo $laudo->nu_solicitacao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($laudo->nu_versao->Visible) { // nu_versao ?>
	<tr id="r_nu_versao">
		<td><span id="elh_laudo_nu_versao"><?php echo $laudo->nu_versao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $laudo->nu_versao->CellAttributes() ?>>
<span id="el_laudo_nu_versao" class="control-group">
<input type="text" data-field="x_nu_versao" name="x_nu_versao" id="x_nu_versao" size="30" placeholder="<?php echo $laudo->nu_versao->PlaceHolder ?>" value="<?php echo $laudo->nu_versao->EditValue ?>"<?php echo $laudo->nu_versao->EditAttributes() ?>>
</span>
<?php echo $laudo->nu_versao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($laudo->ds_sobreDocumentacao->Visible) { // ds_sobreDocumentacao ?>
	<tr id="r_ds_sobreDocumentacao">
		<td><span id="elh_laudo_ds_sobreDocumentacao"><?php echo $laudo->ds_sobreDocumentacao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $laudo->ds_sobreDocumentacao->CellAttributes() ?>>
<span id="el_laudo_ds_sobreDocumentacao" class="control-group">
<textarea data-field="x_ds_sobreDocumentacao" name="x_ds_sobreDocumentacao" id="x_ds_sobreDocumentacao" cols="35" rows="4" placeholder="<?php echo $laudo->ds_sobreDocumentacao->PlaceHolder ?>"<?php echo $laudo->ds_sobreDocumentacao->EditAttributes() ?>><?php echo $laudo->ds_sobreDocumentacao->EditValue ?></textarea>
</span>
<?php echo $laudo->ds_sobreDocumentacao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($laudo->ds_sobreMetrificacao->Visible) { // ds_sobreMetrificacao ?>
	<tr id="r_ds_sobreMetrificacao">
		<td><span id="elh_laudo_ds_sobreMetrificacao"><?php echo $laudo->ds_sobreMetrificacao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $laudo->ds_sobreMetrificacao->CellAttributes() ?>>
<span id="el_laudo_ds_sobreMetrificacao" class="control-group">
<textarea data-field="x_ds_sobreMetrificacao" name="x_ds_sobreMetrificacao" id="x_ds_sobreMetrificacao" cols="35" rows="4" placeholder="<?php echo $laudo->ds_sobreMetrificacao->PlaceHolder ?>"<?php echo $laudo->ds_sobreMetrificacao->EditAttributes() ?>><?php echo $laudo->ds_sobreMetrificacao->EditValue ?></textarea>
</span>
<?php echo $laudo->ds_sobreMetrificacao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($laudo->qt_pf->Visible) { // qt_pf ?>
	<tr id="r_qt_pf">
		<td><span id="elh_laudo_qt_pf"><?php echo $laudo->qt_pf->FldCaption() ?></span></td>
		<td<?php echo $laudo->qt_pf->CellAttributes() ?>>
<span id="el_laudo_qt_pf" class="control-group">
<input type="text" data-field="x_qt_pf" name="x_qt_pf" id="x_qt_pf" size="30" placeholder="<?php echo $laudo->qt_pf->PlaceHolder ?>" value="<?php echo $laudo->qt_pf->EditValue ?>"<?php echo $laudo->qt_pf->EditAttributes() ?>>
</span>
<?php echo $laudo->qt_pf->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($laudo->qt_horas->Visible) { // qt_horas ?>
	<tr id="r_qt_horas">
		<td><span id="elh_laudo_qt_horas"><?php echo $laudo->qt_horas->FldCaption() ?></span></td>
		<td<?php echo $laudo->qt_horas->CellAttributes() ?>>
<span id="el_laudo_qt_horas" class="control-group">
<input type="text" data-field="x_qt_horas" name="x_qt_horas" id="x_qt_horas" size="30" placeholder="<?php echo $laudo->qt_horas->PlaceHolder ?>" value="<?php echo $laudo->qt_horas->EditValue ?>"<?php echo $laudo->qt_horas->EditAttributes() ?>>
</span>
<?php echo $laudo->qt_horas->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($laudo->qt_prazoMeses->Visible) { // qt_prazoMeses ?>
	<tr id="r_qt_prazoMeses">
		<td><span id="elh_laudo_qt_prazoMeses"><?php echo $laudo->qt_prazoMeses->FldCaption() ?></span></td>
		<td<?php echo $laudo->qt_prazoMeses->CellAttributes() ?>>
<span id="el_laudo_qt_prazoMeses" class="control-group">
<input type="text" data-field="x_qt_prazoMeses" name="x_qt_prazoMeses" id="x_qt_prazoMeses" size="30" placeholder="<?php echo $laudo->qt_prazoMeses->PlaceHolder ?>" value="<?php echo $laudo->qt_prazoMeses->EditValue ?>"<?php echo $laudo->qt_prazoMeses->EditAttributes() ?>>
</span>
<?php echo $laudo->qt_prazoMeses->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($laudo->qt_prazoDias->Visible) { // qt_prazoDias ?>
	<tr id="r_qt_prazoDias">
		<td><span id="elh_laudo_qt_prazoDias"><?php echo $laudo->qt_prazoDias->FldCaption() ?></span></td>
		<td<?php echo $laudo->qt_prazoDias->CellAttributes() ?>>
<span id="el_laudo_qt_prazoDias" class="control-group">
<input type="text" data-field="x_qt_prazoDias" name="x_qt_prazoDias" id="x_qt_prazoDias" size="30" placeholder="<?php echo $laudo->qt_prazoDias->PlaceHolder ?>" value="<?php echo $laudo->qt_prazoDias->EditValue ?>"<?php echo $laudo->qt_prazoDias->EditAttributes() ?>>
</span>
<?php echo $laudo->qt_prazoDias->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($laudo->vr_contratacao->Visible) { // vr_contratacao ?>
	<tr id="r_vr_contratacao">
		<td><span id="elh_laudo_vr_contratacao"><?php echo $laudo->vr_contratacao->FldCaption() ?></span></td>
		<td<?php echo $laudo->vr_contratacao->CellAttributes() ?>>
<span id="el_laudo_vr_contratacao" class="control-group">
<input type="text" data-field="x_vr_contratacao" name="x_vr_contratacao" id="x_vr_contratacao" size="30" placeholder="<?php echo $laudo->vr_contratacao->PlaceHolder ?>" value="<?php echo $laudo->vr_contratacao->EditValue ?>"<?php echo $laudo->vr_contratacao->EditAttributes() ?>>
</span>
<?php echo $laudo->vr_contratacao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($laudo->dt_inicioSolicitacao->Visible) { // dt_inicioSolicitacao ?>
	<tr id="r_dt_inicioSolicitacao">
		<td><span id="elh_laudo_dt_inicioSolicitacao"><?php echo $laudo->dt_inicioSolicitacao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $laudo->dt_inicioSolicitacao->CellAttributes() ?>>
<span id="el_laudo_dt_inicioSolicitacao" class="control-group">
<input type="text" data-field="x_dt_inicioSolicitacao" name="x_dt_inicioSolicitacao" id="x_dt_inicioSolicitacao" size="30" maxlength="10" placeholder="<?php echo $laudo->dt_inicioSolicitacao->PlaceHolder ?>" value="<?php echo $laudo->dt_inicioSolicitacao->EditValue ?>"<?php echo $laudo->dt_inicioSolicitacao->EditAttributes() ?>>
</span>
<?php echo $laudo->dt_inicioSolicitacao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($laudo->dt_inicioContagem->Visible) { // dt_inicioContagem ?>
	<tr id="r_dt_inicioContagem">
		<td><span id="elh_laudo_dt_inicioContagem"><?php echo $laudo->dt_inicioContagem->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $laudo->dt_inicioContagem->CellAttributes() ?>>
<span id="el_laudo_dt_inicioContagem" class="control-group">
<input type="text" data-field="x_dt_inicioContagem" name="x_dt_inicioContagem" id="x_dt_inicioContagem" size="30" maxlength="10" placeholder="<?php echo $laudo->dt_inicioContagem->PlaceHolder ?>" value="<?php echo $laudo->dt_inicioContagem->EditValue ?>"<?php echo $laudo->dt_inicioContagem->EditAttributes() ?>>
</span>
<?php echo $laudo->dt_inicioContagem->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($laudo->ic_tamanho->Visible) { // ic_tamanho ?>
	<tr id="r_ic_tamanho">
		<td><span id="elh_laudo_ic_tamanho"><?php echo $laudo->ic_tamanho->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $laudo->ic_tamanho->CellAttributes() ?>>
<span id="el_laudo_ic_tamanho" class="control-group">
<div id="tp_x_ic_tamanho" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_tamanho" id="x_ic_tamanho" value="{value}"<?php echo $laudo->ic_tamanho->EditAttributes() ?>></div>
<div id="dsl_x_ic_tamanho" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $laudo->ic_tamanho->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($laudo->ic_tamanho->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_tamanho" name="x_ic_tamanho" id="x_ic_tamanho_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $laudo->ic_tamanho->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $laudo->ic_tamanho->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($laudo->ic_esforco->Visible) { // ic_esforco ?>
	<tr id="r_ic_esforco">
		<td><span id="elh_laudo_ic_esforco"><?php echo $laudo->ic_esforco->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $laudo->ic_esforco->CellAttributes() ?>>
<span id="el_laudo_ic_esforco" class="control-group">
<div id="tp_x_ic_esforco" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_esforco" id="x_ic_esforco" value="{value}"<?php echo $laudo->ic_esforco->EditAttributes() ?>></div>
<div id="dsl_x_ic_esforco" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $laudo->ic_esforco->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($laudo->ic_esforco->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_esforco" name="x_ic_esforco" id="x_ic_esforco_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $laudo->ic_esforco->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $laudo->ic_esforco->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($laudo->ic_prazo->Visible) { // ic_prazo ?>
	<tr id="r_ic_prazo">
		<td><span id="elh_laudo_ic_prazo"><?php echo $laudo->ic_prazo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $laudo->ic_prazo->CellAttributes() ?>>
<span id="el_laudo_ic_prazo" class="control-group">
<div id="tp_x_ic_prazo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_prazo" id="x_ic_prazo" value="{value}"<?php echo $laudo->ic_prazo->EditAttributes() ?>></div>
<div id="dsl_x_ic_prazo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $laudo->ic_prazo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($laudo->ic_prazo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_prazo" name="x_ic_prazo" id="x_ic_prazo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $laudo->ic_prazo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $laudo->ic_prazo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($laudo->ic_custo->Visible) { // ic_custo ?>
	<tr id="r_ic_custo">
		<td><span id="elh_laudo_ic_custo"><?php echo $laudo->ic_custo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $laudo->ic_custo->CellAttributes() ?>>
<span id="el_laudo_ic_custo" class="control-group">
<div id="tp_x_ic_custo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_custo" id="x_ic_custo" value="{value}"<?php echo $laudo->ic_custo->EditAttributes() ?>></div>
<div id="dsl_x_ic_custo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $laudo->ic_custo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($laudo->ic_custo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_custo" name="x_ic_custo" id="x_ic_custo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $laudo->ic_custo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $laudo->ic_custo->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
flaudoadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$laudo_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$laudo_add->Page_Terminate();
?>
