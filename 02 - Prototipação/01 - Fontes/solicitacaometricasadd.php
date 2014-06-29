<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "solicitacaometricasinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "solicitacao_ocorrenciagridcls.php" ?>
<?php include_once "contagempfgridcls.php" ?>
<?php include_once "estimativagridcls.php" ?>
<?php include_once "laudogridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$solicitacaoMetricas_add = NULL; // Initialize page object first

class csolicitacaoMetricas_add extends csolicitacaoMetricas {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'solicitacaoMetricas';

	// Page object name
	var $PageObjName = 'solicitacaoMetricas_add';

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

		// Table object (solicitacaoMetricas)
		if (!isset($GLOBALS["solicitacaoMetricas"])) {
			$GLOBALS["solicitacaoMetricas"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["solicitacaoMetricas"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'solicitacaoMetricas', TRUE);

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
			$this->Page_Terminate("solicitacaometricaslist.php");
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
			if (@$_GET["nu_solMetricas"] != "") {
				$this->nu_solMetricas->setQueryStringValue($_GET["nu_solMetricas"]);
				$this->setKey("nu_solMetricas", $this->nu_solMetricas->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_solMetricas", ""); // Clear key
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

		// Set up detail parameters
		$this->SetUpDetailParms();

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
					$this->Page_Terminate("solicitacaometricaslist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					if ($this->getCurrentDetailTable() <> "") // Master/detail add
						$sReturnUrl = $this->GetDetailUrl();
					else
						$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "solicitacaometricasview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values

					// Set up detail parameters
					$this->SetUpDetailParms();
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
		$this->im_anexosContForn->Upload->Index = $objForm->Index;
		if ($this->im_anexosContForn->Upload->UploadFile()) {

			// No action required
		} else {
			echo $this->im_anexosContForn->Upload->Message;
			$this->Page_Terminate();
			exit();
		}
		$this->im_anexosContForn->CurrentValue = $this->im_anexosContForn->Upload->FileName;
		$this->im_anexosContAnt->Upload->Index = $objForm->Index;
		if ($this->im_anexosContAnt->Upload->UploadFile()) {

			// No action required
		} else {
			echo $this->im_anexosContAnt->Upload->Message;
			$this->Page_Terminate();
			exit();
		}
		$this->im_anexosContAnt->CurrentValue = $this->im_anexosContAnt->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->nu_tpSolicitacao->CurrentValue = NULL;
		$this->nu_tpSolicitacao->OldValue = $this->nu_tpSolicitacao->CurrentValue;
		$this->nu_projeto->CurrentValue = NULL;
		$this->nu_projeto->OldValue = $this->nu_projeto->CurrentValue;
		$this->no_atividadeMaeRedmine->CurrentValue = NULL;
		$this->no_atividadeMaeRedmine->OldValue = $this->no_atividadeMaeRedmine->CurrentValue;
		$this->ds_observacoes->CurrentValue = NULL;
		$this->ds_observacoes->OldValue = $this->ds_observacoes->CurrentValue;
		$this->ds_documentacaoAux->CurrentValue = NULL;
		$this->ds_documentacaoAux->OldValue = $this->ds_documentacaoAux->CurrentValue;
		$this->ds_imapactoDb->CurrentValue = NULL;
		$this->ds_imapactoDb->OldValue = $this->ds_imapactoDb->CurrentValue;
		$this->ic_stSolicitacao->CurrentValue = NULL;
		$this->ic_stSolicitacao->OldValue = $this->ic_stSolicitacao->CurrentValue;
		$this->nu_usuarioAlterou->CurrentValue = NULL;
		$this->nu_usuarioAlterou->OldValue = $this->nu_usuarioAlterou->CurrentValue;
		$this->dh_alteracao->CurrentValue = NULL;
		$this->dh_alteracao->OldValue = $this->dh_alteracao->CurrentValue;
		$this->nu_usuarioIncluiu->CurrentValue = NULL;
		$this->nu_usuarioIncluiu->OldValue = $this->nu_usuarioIncluiu->CurrentValue;
		$this->dh_inclusao->CurrentValue = NULL;
		$this->dh_inclusao->OldValue = $this->dh_inclusao->CurrentValue;
		$this->dt_stSolicitacao->CurrentValue = NULL;
		$this->dt_stSolicitacao->OldValue = $this->dt_stSolicitacao->CurrentValue;
		$this->vr_pfContForn->CurrentValue = NULL;
		$this->vr_pfContForn->OldValue = $this->vr_pfContForn->CurrentValue;
		$this->nu_tpMetrica->CurrentValue = NULL;
		$this->nu_tpMetrica->OldValue = $this->nu_tpMetrica->CurrentValue;
		$this->ds_observacoesContForn->CurrentValue = NULL;
		$this->ds_observacoesContForn->OldValue = $this->ds_observacoesContForn->CurrentValue;
		$this->im_anexosContForn->Upload->DbValue = NULL;
		$this->im_anexosContForn->OldValue = $this->im_anexosContForn->Upload->DbValue;
		$this->im_anexosContForn->CurrentValue = NULL; // Clear file related field
		$this->nu_contagemAnt->CurrentValue = NULL;
		$this->nu_contagemAnt->OldValue = $this->nu_contagemAnt->CurrentValue;
		$this->ds_observaocoesContAnt->CurrentValue = NULL;
		$this->ds_observaocoesContAnt->OldValue = $this->ds_observaocoesContAnt->CurrentValue;
		$this->im_anexosContAnt->Upload->DbValue = NULL;
		$this->im_anexosContAnt->OldValue = $this->im_anexosContAnt->Upload->DbValue;
		$this->im_anexosContAnt->CurrentValue = NULL; // Clear file related field
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->nu_tpSolicitacao->FldIsDetailKey) {
			$this->nu_tpSolicitacao->setFormValue($objForm->GetValue("x_nu_tpSolicitacao"));
		}
		if (!$this->nu_projeto->FldIsDetailKey) {
			$this->nu_projeto->setFormValue($objForm->GetValue("x_nu_projeto"));
		}
		if (!$this->no_atividadeMaeRedmine->FldIsDetailKey) {
			$this->no_atividadeMaeRedmine->setFormValue($objForm->GetValue("x_no_atividadeMaeRedmine"));
		}
		if (!$this->ds_observacoes->FldIsDetailKey) {
			$this->ds_observacoes->setFormValue($objForm->GetValue("x_ds_observacoes"));
		}
		if (!$this->ds_documentacaoAux->FldIsDetailKey) {
			$this->ds_documentacaoAux->setFormValue($objForm->GetValue("x_ds_documentacaoAux"));
		}
		if (!$this->ds_imapactoDb->FldIsDetailKey) {
			$this->ds_imapactoDb->setFormValue($objForm->GetValue("x_ds_imapactoDb"));
		}
		if (!$this->ic_stSolicitacao->FldIsDetailKey) {
			$this->ic_stSolicitacao->setFormValue($objForm->GetValue("x_ic_stSolicitacao"));
		}
		if (!$this->nu_usuarioAlterou->FldIsDetailKey) {
			$this->nu_usuarioAlterou->setFormValue($objForm->GetValue("x_nu_usuarioAlterou"));
		}
		if (!$this->dh_alteracao->FldIsDetailKey) {
			$this->dh_alteracao->setFormValue($objForm->GetValue("x_dh_alteracao"));
			$this->dh_alteracao->CurrentValue = ew_UnFormatDateTime($this->dh_alteracao->CurrentValue, 10);
		}
		if (!$this->nu_usuarioIncluiu->FldIsDetailKey) {
			$this->nu_usuarioIncluiu->setFormValue($objForm->GetValue("x_nu_usuarioIncluiu"));
		}
		if (!$this->dh_inclusao->FldIsDetailKey) {
			$this->dh_inclusao->setFormValue($objForm->GetValue("x_dh_inclusao"));
			$this->dh_inclusao->CurrentValue = ew_UnFormatDateTime($this->dh_inclusao->CurrentValue, 7);
		}
		if (!$this->dt_stSolicitacao->FldIsDetailKey) {
			$this->dt_stSolicitacao->setFormValue($objForm->GetValue("x_dt_stSolicitacao"));
			$this->dt_stSolicitacao->CurrentValue = ew_UnFormatDateTime($this->dt_stSolicitacao->CurrentValue, 7);
		}
		if (!$this->vr_pfContForn->FldIsDetailKey) {
			$this->vr_pfContForn->setFormValue($objForm->GetValue("x_vr_pfContForn"));
		}
		if (!$this->nu_tpMetrica->FldIsDetailKey) {
			$this->nu_tpMetrica->setFormValue($objForm->GetValue("x_nu_tpMetrica"));
		}
		if (!$this->ds_observacoesContForn->FldIsDetailKey) {
			$this->ds_observacoesContForn->setFormValue($objForm->GetValue("x_ds_observacoesContForn"));
		}
		if (!$this->nu_contagemAnt->FldIsDetailKey) {
			$this->nu_contagemAnt->setFormValue($objForm->GetValue("x_nu_contagemAnt"));
		}
		if (!$this->ds_observaocoesContAnt->FldIsDetailKey) {
			$this->ds_observaocoesContAnt->setFormValue($objForm->GetValue("x_ds_observaocoesContAnt"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->nu_tpSolicitacao->CurrentValue = $this->nu_tpSolicitacao->FormValue;
		$this->nu_projeto->CurrentValue = $this->nu_projeto->FormValue;
		$this->no_atividadeMaeRedmine->CurrentValue = $this->no_atividadeMaeRedmine->FormValue;
		$this->ds_observacoes->CurrentValue = $this->ds_observacoes->FormValue;
		$this->ds_documentacaoAux->CurrentValue = $this->ds_documentacaoAux->FormValue;
		$this->ds_imapactoDb->CurrentValue = $this->ds_imapactoDb->FormValue;
		$this->ic_stSolicitacao->CurrentValue = $this->ic_stSolicitacao->FormValue;
		$this->nu_usuarioAlterou->CurrentValue = $this->nu_usuarioAlterou->FormValue;
		$this->dh_alteracao->CurrentValue = $this->dh_alteracao->FormValue;
		$this->dh_alteracao->CurrentValue = ew_UnFormatDateTime($this->dh_alteracao->CurrentValue, 10);
		$this->nu_usuarioIncluiu->CurrentValue = $this->nu_usuarioIncluiu->FormValue;
		$this->dh_inclusao->CurrentValue = $this->dh_inclusao->FormValue;
		$this->dh_inclusao->CurrentValue = ew_UnFormatDateTime($this->dh_inclusao->CurrentValue, 7);
		$this->dt_stSolicitacao->CurrentValue = $this->dt_stSolicitacao->FormValue;
		$this->dt_stSolicitacao->CurrentValue = ew_UnFormatDateTime($this->dt_stSolicitacao->CurrentValue, 7);
		$this->vr_pfContForn->CurrentValue = $this->vr_pfContForn->FormValue;
		$this->nu_tpMetrica->CurrentValue = $this->nu_tpMetrica->FormValue;
		$this->ds_observacoesContForn->CurrentValue = $this->ds_observacoesContForn->FormValue;
		$this->nu_contagemAnt->CurrentValue = $this->nu_contagemAnt->FormValue;
		$this->ds_observaocoesContAnt->CurrentValue = $this->ds_observaocoesContAnt->FormValue;
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
		$this->nu_solMetricas->setDbValue($rs->fields('nu_solMetricas'));
		$this->nu_tpSolicitacao->setDbValue($rs->fields('nu_tpSolicitacao'));
		$this->nu_projeto->setDbValue($rs->fields('nu_projeto'));
		if (array_key_exists('EV__nu_projeto', $rs->fields)) {
			$this->nu_projeto->VirtualValue = $rs->fields('EV__nu_projeto'); // Set up virtual field value
		} else {
			$this->nu_projeto->VirtualValue = ""; // Clear value
		}
		$this->no_atividadeMaeRedmine->setDbValue($rs->fields('no_atividadeMaeRedmine'));
		$this->ds_observacoes->setDbValue($rs->fields('ds_observacoes'));
		$this->ds_documentacaoAux->setDbValue($rs->fields('ds_documentacaoAux'));
		$this->ds_imapactoDb->setDbValue($rs->fields('ds_imapactoDb'));
		$this->ic_stSolicitacao->setDbValue($rs->fields('ic_stSolicitacao'));
		$this->nu_usuarioAlterou->setDbValue($rs->fields('nu_usuarioAlterou'));
		$this->dh_alteracao->setDbValue($rs->fields('dh_alteracao'));
		$this->nu_usuarioIncluiu->setDbValue($rs->fields('nu_usuarioIncluiu'));
		$this->dh_inclusao->setDbValue($rs->fields('dh_inclusao'));
		$this->dt_stSolicitacao->setDbValue($rs->fields('dt_stSolicitacao'));
		$this->qt_pfTotal->setDbValue($rs->fields('qt_pfTotal'));
		$this->vr_pfContForn->setDbValue($rs->fields('vr_pfContForn'));
		$this->nu_tpMetrica->setDbValue($rs->fields('nu_tpMetrica'));
		$this->ds_observacoesContForn->setDbValue($rs->fields('ds_observacoesContForn'));
		$this->im_anexosContForn->Upload->DbValue = $rs->fields('im_anexosContForn');
		$this->nu_contagemAnt->setDbValue($rs->fields('nu_contagemAnt'));
		if (array_key_exists('EV__nu_contagemAnt', $rs->fields)) {
			$this->nu_contagemAnt->VirtualValue = $rs->fields('EV__nu_contagemAnt'); // Set up virtual field value
		} else {
			$this->nu_contagemAnt->VirtualValue = ""; // Clear value
		}
		$this->ds_observaocoesContAnt->setDbValue($rs->fields('ds_observaocoesContAnt'));
		$this->im_anexosContAnt->Upload->DbValue = $rs->fields('im_anexosContAnt');
		$this->ic_bloqueio->setDbValue($rs->fields('ic_bloqueio'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_solMetricas->DbValue = $row['nu_solMetricas'];
		$this->nu_tpSolicitacao->DbValue = $row['nu_tpSolicitacao'];
		$this->nu_projeto->DbValue = $row['nu_projeto'];
		$this->no_atividadeMaeRedmine->DbValue = $row['no_atividadeMaeRedmine'];
		$this->ds_observacoes->DbValue = $row['ds_observacoes'];
		$this->ds_documentacaoAux->DbValue = $row['ds_documentacaoAux'];
		$this->ds_imapactoDb->DbValue = $row['ds_imapactoDb'];
		$this->ic_stSolicitacao->DbValue = $row['ic_stSolicitacao'];
		$this->nu_usuarioAlterou->DbValue = $row['nu_usuarioAlterou'];
		$this->dh_alteracao->DbValue = $row['dh_alteracao'];
		$this->nu_usuarioIncluiu->DbValue = $row['nu_usuarioIncluiu'];
		$this->dh_inclusao->DbValue = $row['dh_inclusao'];
		$this->dt_stSolicitacao->DbValue = $row['dt_stSolicitacao'];
		$this->qt_pfTotal->DbValue = $row['qt_pfTotal'];
		$this->vr_pfContForn->DbValue = $row['vr_pfContForn'];
		$this->nu_tpMetrica->DbValue = $row['nu_tpMetrica'];
		$this->ds_observacoesContForn->DbValue = $row['ds_observacoesContForn'];
		$this->im_anexosContForn->Upload->DbValue = $row['im_anexosContForn'];
		$this->nu_contagemAnt->DbValue = $row['nu_contagemAnt'];
		$this->ds_observaocoesContAnt->DbValue = $row['ds_observaocoesContAnt'];
		$this->im_anexosContAnt->Upload->DbValue = $row['im_anexosContAnt'];
		$this->ic_bloqueio->DbValue = $row['ic_bloqueio'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_solMetricas")) <> "")
			$this->nu_solMetricas->CurrentValue = $this->getKey("nu_solMetricas"); // nu_solMetricas
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

		if ($this->vr_pfContForn->FormValue == $this->vr_pfContForn->CurrentValue && is_numeric(ew_StrToFloat($this->vr_pfContForn->CurrentValue)))
			$this->vr_pfContForn->CurrentValue = ew_StrToFloat($this->vr_pfContForn->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_solMetricas
		// nu_tpSolicitacao
		// nu_projeto
		// no_atividadeMaeRedmine
		// ds_observacoes
		// ds_documentacaoAux
		// ds_imapactoDb
		// ic_stSolicitacao
		// nu_usuarioAlterou
		// dh_alteracao
		// nu_usuarioIncluiu
		// dh_inclusao
		// dt_stSolicitacao
		// qt_pfTotal
		// vr_pfContForn
		// nu_tpMetrica
		// ds_observacoesContForn
		// im_anexosContForn
		// nu_contagemAnt
		// ds_observaocoesContAnt
		// im_anexosContAnt
		// ic_bloqueio

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_solMetricas
			$this->nu_solMetricas->ViewValue = $this->nu_solMetricas->CurrentValue;
			$this->nu_solMetricas->ViewCustomAttributes = "";

			// nu_tpSolicitacao
			if (strval($this->nu_tpSolicitacao->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpSolicitacao]" . ew_SearchString("=", $this->nu_tpSolicitacao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpSolicitacao], [no_tpSolicitacao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpsolicitacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpSolicitacao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpSolicitacao] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpSolicitacao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpSolicitacao->ViewValue = $this->nu_tpSolicitacao->CurrentValue;
				}
			} else {
				$this->nu_tpSolicitacao->ViewValue = NULL;
			}
			$this->nu_tpSolicitacao->ViewCustomAttributes = "";

			// nu_projeto
			if ($this->nu_projeto->VirtualValue <> "") {
				$this->nu_projeto->ViewValue = $this->nu_projeto->VirtualValue;
			} else {
			if (strval($this->nu_projeto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_projeto]" . ew_SearchString("=", $this->nu_projeto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_projeto], [no_projeto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[projeto]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_passivelContPf]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
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

			// no_atividadeMaeRedmine
			$this->no_atividadeMaeRedmine->ViewValue = $this->no_atividadeMaeRedmine->CurrentValue;
			$this->no_atividadeMaeRedmine->ViewCustomAttributes = "";

			// ds_observacoes
			$this->ds_observacoes->ViewValue = $this->ds_observacoes->CurrentValue;
			$this->ds_observacoes->ViewCustomAttributes = "";

			// ds_documentacaoAux
			$this->ds_documentacaoAux->ViewValue = $this->ds_documentacaoAux->CurrentValue;
			$this->ds_documentacaoAux->ViewCustomAttributes = "";

			// ds_imapactoDb
			$this->ds_imapactoDb->ViewValue = $this->ds_imapactoDb->CurrentValue;
			$this->ds_imapactoDb->ViewCustomAttributes = "";

			// ic_stSolicitacao
			if (strval($this->ic_stSolicitacao->CurrentValue) <> "") {
				switch ($this->ic_stSolicitacao->CurrentValue) {
					case $this->ic_stSolicitacao->FldTagValue(1):
						$this->ic_stSolicitacao->ViewValue = $this->ic_stSolicitacao->FldTagCaption(1) <> "" ? $this->ic_stSolicitacao->FldTagCaption(1) : $this->ic_stSolicitacao->CurrentValue;
						break;
					case $this->ic_stSolicitacao->FldTagValue(2):
						$this->ic_stSolicitacao->ViewValue = $this->ic_stSolicitacao->FldTagCaption(2) <> "" ? $this->ic_stSolicitacao->FldTagCaption(2) : $this->ic_stSolicitacao->CurrentValue;
						break;
					case $this->ic_stSolicitacao->FldTagValue(3):
						$this->ic_stSolicitacao->ViewValue = $this->ic_stSolicitacao->FldTagCaption(3) <> "" ? $this->ic_stSolicitacao->FldTagCaption(3) : $this->ic_stSolicitacao->CurrentValue;
						break;
					case $this->ic_stSolicitacao->FldTagValue(4):
						$this->ic_stSolicitacao->ViewValue = $this->ic_stSolicitacao->FldTagCaption(4) <> "" ? $this->ic_stSolicitacao->FldTagCaption(4) : $this->ic_stSolicitacao->CurrentValue;
						break;
					default:
						$this->ic_stSolicitacao->ViewValue = $this->ic_stSolicitacao->CurrentValue;
				}
			} else {
				$this->ic_stSolicitacao->ViewValue = NULL;
			}
			$this->ic_stSolicitacao->ViewCustomAttributes = "";

			// nu_usuarioAlterou
			if (strval($this->nu_usuarioAlterou->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioAlterou->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioAlterou, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioAlterou->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioAlterou->ViewValue = $this->nu_usuarioAlterou->CurrentValue;
				}
			} else {
				$this->nu_usuarioAlterou->ViewValue = NULL;
			}
			$this->nu_usuarioAlterou->ViewCustomAttributes = "";

			// dh_alteracao
			$this->dh_alteracao->ViewValue = $this->dh_alteracao->CurrentValue;
			$this->dh_alteracao->ViewValue = ew_FormatDateTime($this->dh_alteracao->ViewValue, 10);
			$this->dh_alteracao->ViewCustomAttributes = "";

			// nu_usuarioIncluiu
			if (strval($this->nu_usuarioIncluiu->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioIncluiu->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioIncluiu, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioIncluiu->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioIncluiu->ViewValue = $this->nu_usuarioIncluiu->CurrentValue;
				}
			} else {
				$this->nu_usuarioIncluiu->ViewValue = NULL;
			}
			$this->nu_usuarioIncluiu->ViewCustomAttributes = "";

			// dh_inclusao
			$this->dh_inclusao->ViewValue = $this->dh_inclusao->CurrentValue;
			$this->dh_inclusao->ViewValue = ew_FormatDateTime($this->dh_inclusao->ViewValue, 7);
			$this->dh_inclusao->ViewCustomAttributes = "";

			// dt_stSolicitacao
			$this->dt_stSolicitacao->ViewValue = $this->dt_stSolicitacao->CurrentValue;
			$this->dt_stSolicitacao->ViewValue = ew_FormatDateTime($this->dt_stSolicitacao->ViewValue, 7);
			$this->dt_stSolicitacao->ViewCustomAttributes = "";

			// qt_pfTotal
			$this->qt_pfTotal->ViewValue = $this->qt_pfTotal->CurrentValue;
			$this->qt_pfTotal->ViewCustomAttributes = "";

			// vr_pfContForn
			$this->vr_pfContForn->ViewValue = $this->vr_pfContForn->CurrentValue;
			$this->vr_pfContForn->ViewCustomAttributes = "";

			// nu_tpMetrica
			if (strval($this->nu_tpMetrica->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpMetrica]" . ew_SearchString("=", $this->nu_tpMetrica->CurrentValue, EW_DATATYPE_NUMBER);
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
			$this->Lookup_Selecting($this->nu_tpMetrica, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpMetrica] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpMetrica->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpMetrica->ViewValue = $this->nu_tpMetrica->CurrentValue;
				}
			} else {
				$this->nu_tpMetrica->ViewValue = NULL;
			}
			$this->nu_tpMetrica->ViewCustomAttributes = "";

			// ds_observacoesContForn
			$this->ds_observacoesContForn->ViewValue = $this->ds_observacoesContForn->CurrentValue;
			$this->ds_observacoesContForn->ViewCustomAttributes = "";

			// im_anexosContForn
			$this->im_anexosContForn->UploadPath = "contagem_fornecedor";
			if (!ew_Empty($this->im_anexosContForn->Upload->DbValue)) {
				$this->im_anexosContForn->ViewValue = $this->im_anexosContForn->Upload->DbValue;
			} else {
				$this->im_anexosContForn->ViewValue = "";
			}
			$this->im_anexosContForn->ViewCustomAttributes = "";

			// nu_contagemAnt
			if ($this->nu_contagemAnt->VirtualValue <> "") {
				$this->nu_contagemAnt->ViewValue = $this->nu_contagemAnt->VirtualValue;
			} else {
			if (strval($this->nu_contagemAnt->CurrentValue) <> "") {
				$sFilterWrk = "[nu_solMetricas]" . ew_SearchString("=", $this->nu_contagemAnt->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_solMetricas], [nu_solMetricas] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[solicitacaoMetricas]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_contagemAnt, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_solMetricas] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_contagemAnt->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_contagemAnt->ViewValue = $this->nu_contagemAnt->CurrentValue;
				}
			} else {
				$this->nu_contagemAnt->ViewValue = NULL;
			}
			}
			$this->nu_contagemAnt->ViewCustomAttributes = "";

			// ds_observaocoesContAnt
			$this->ds_observaocoesContAnt->ViewValue = $this->ds_observaocoesContAnt->CurrentValue;
			$this->ds_observaocoesContAnt->ViewCustomAttributes = "";

			// im_anexosContAnt
			$this->im_anexosContAnt->UploadPath = "contagem_anterior";
			if (!ew_Empty($this->im_anexosContAnt->Upload->DbValue)) {
				$this->im_anexosContAnt->ViewValue = $this->im_anexosContAnt->Upload->DbValue;
			} else {
				$this->im_anexosContAnt->ViewValue = "";
			}
			$this->im_anexosContAnt->ViewCustomAttributes = "";

			// ic_bloqueio
			$this->ic_bloqueio->ViewValue = $this->ic_bloqueio->CurrentValue;
			$this->ic_bloqueio->ViewCustomAttributes = "";

			// nu_tpSolicitacao
			$this->nu_tpSolicitacao->LinkCustomAttributes = "";
			$this->nu_tpSolicitacao->HrefValue = "";
			$this->nu_tpSolicitacao->TooltipValue = "";

			// nu_projeto
			$this->nu_projeto->LinkCustomAttributes = "";
			$this->nu_projeto->HrefValue = "";
			$this->nu_projeto->TooltipValue = "";

			// no_atividadeMaeRedmine
			$this->no_atividadeMaeRedmine->LinkCustomAttributes = "";
			$this->no_atividadeMaeRedmine->HrefValue = "";
			$this->no_atividadeMaeRedmine->TooltipValue = "";

			// ds_observacoes
			$this->ds_observacoes->LinkCustomAttributes = "";
			$this->ds_observacoes->HrefValue = "";
			$this->ds_observacoes->TooltipValue = "";

			// ds_documentacaoAux
			$this->ds_documentacaoAux->LinkCustomAttributes = "";
			$this->ds_documentacaoAux->HrefValue = "";
			$this->ds_documentacaoAux->TooltipValue = "";

			// ds_imapactoDb
			$this->ds_imapactoDb->LinkCustomAttributes = "";
			$this->ds_imapactoDb->HrefValue = "";
			$this->ds_imapactoDb->TooltipValue = "";

			// ic_stSolicitacao
			$this->ic_stSolicitacao->LinkCustomAttributes = "";
			$this->ic_stSolicitacao->HrefValue = "";
			$this->ic_stSolicitacao->TooltipValue = "";

			// nu_usuarioAlterou
			$this->nu_usuarioAlterou->LinkCustomAttributes = "";
			$this->nu_usuarioAlterou->HrefValue = "";
			$this->nu_usuarioAlterou->TooltipValue = "";

			// dh_alteracao
			$this->dh_alteracao->LinkCustomAttributes = "";
			$this->dh_alteracao->HrefValue = "";
			$this->dh_alteracao->TooltipValue = "";

			// nu_usuarioIncluiu
			$this->nu_usuarioIncluiu->LinkCustomAttributes = "";
			$this->nu_usuarioIncluiu->HrefValue = "";
			$this->nu_usuarioIncluiu->TooltipValue = "";

			// dh_inclusao
			$this->dh_inclusao->LinkCustomAttributes = "";
			$this->dh_inclusao->HrefValue = "";
			$this->dh_inclusao->TooltipValue = "";

			// dt_stSolicitacao
			$this->dt_stSolicitacao->LinkCustomAttributes = "";
			$this->dt_stSolicitacao->HrefValue = "";
			$this->dt_stSolicitacao->TooltipValue = "";

			// vr_pfContForn
			$this->vr_pfContForn->LinkCustomAttributes = "";
			$this->vr_pfContForn->HrefValue = "";
			$this->vr_pfContForn->TooltipValue = "";

			// nu_tpMetrica
			$this->nu_tpMetrica->LinkCustomAttributes = "";
			$this->nu_tpMetrica->HrefValue = "";
			$this->nu_tpMetrica->TooltipValue = "";

			// ds_observacoesContForn
			$this->ds_observacoesContForn->LinkCustomAttributes = "";
			$this->ds_observacoesContForn->HrefValue = "";
			$this->ds_observacoesContForn->TooltipValue = "";

			// im_anexosContForn
			$this->im_anexosContForn->LinkCustomAttributes = "";
			$this->im_anexosContForn->HrefValue = "";
			$this->im_anexosContForn->HrefValue2 = $this->im_anexosContForn->UploadPath . $this->im_anexosContForn->Upload->DbValue;
			$this->im_anexosContForn->TooltipValue = "";

			// nu_contagemAnt
			$this->nu_contagemAnt->LinkCustomAttributes = "";
			$this->nu_contagemAnt->HrefValue = "";
			$this->nu_contagemAnt->TooltipValue = "";

			// ds_observaocoesContAnt
			$this->ds_observaocoesContAnt->LinkCustomAttributes = "";
			$this->ds_observaocoesContAnt->HrefValue = "";
			$this->ds_observaocoesContAnt->TooltipValue = "";

			// im_anexosContAnt
			$this->im_anexosContAnt->LinkCustomAttributes = "";
			$this->im_anexosContAnt->HrefValue = "";
			$this->im_anexosContAnt->HrefValue2 = $this->im_anexosContAnt->UploadPath . $this->im_anexosContAnt->Upload->DbValue;
			$this->im_anexosContAnt->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nu_tpSolicitacao
			$this->nu_tpSolicitacao->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_tpSolicitacao], [no_tpSolicitacao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[tpsolicitacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpSolicitacao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpSolicitacao] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_tpSolicitacao->EditValue = $arwrk;

			// nu_projeto
			$this->nu_projeto->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_projeto], [no_projeto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[projeto]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_passivelContPf]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
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

			// no_atividadeMaeRedmine
			$this->no_atividadeMaeRedmine->EditCustomAttributes = "";
			$this->no_atividadeMaeRedmine->EditValue = ew_HtmlEncode($this->no_atividadeMaeRedmine->CurrentValue);
			$this->no_atividadeMaeRedmine->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_atividadeMaeRedmine->FldCaption()));

			// ds_observacoes
			$this->ds_observacoes->EditCustomAttributes = "";
			$this->ds_observacoes->EditValue = $this->ds_observacoes->CurrentValue;
			$this->ds_observacoes->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_observacoes->FldCaption()));

			// ds_documentacaoAux
			$this->ds_documentacaoAux->EditCustomAttributes = "";
			$this->ds_documentacaoAux->EditValue = $this->ds_documentacaoAux->CurrentValue;
			$this->ds_documentacaoAux->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_documentacaoAux->FldCaption()));

			// ds_imapactoDb
			$this->ds_imapactoDb->EditCustomAttributes = "";
			$this->ds_imapactoDb->EditValue = $this->ds_imapactoDb->CurrentValue;
			$this->ds_imapactoDb->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_imapactoDb->FldCaption()));

			// ic_stSolicitacao
			$this->ic_stSolicitacao->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_stSolicitacao->FldTagValue(1), $this->ic_stSolicitacao->FldTagCaption(1) <> "" ? $this->ic_stSolicitacao->FldTagCaption(1) : $this->ic_stSolicitacao->FldTagValue(1));
			$arwrk[] = array($this->ic_stSolicitacao->FldTagValue(2), $this->ic_stSolicitacao->FldTagCaption(2) <> "" ? $this->ic_stSolicitacao->FldTagCaption(2) : $this->ic_stSolicitacao->FldTagValue(2));
			$arwrk[] = array($this->ic_stSolicitacao->FldTagValue(3), $this->ic_stSolicitacao->FldTagCaption(3) <> "" ? $this->ic_stSolicitacao->FldTagCaption(3) : $this->ic_stSolicitacao->FldTagValue(3));
			$arwrk[] = array($this->ic_stSolicitacao->FldTagValue(4), $this->ic_stSolicitacao->FldTagCaption(4) <> "" ? $this->ic_stSolicitacao->FldTagCaption(4) : $this->ic_stSolicitacao->FldTagValue(4));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_stSolicitacao->EditValue = $arwrk;

			// nu_usuarioAlterou
			// dh_alteracao
			// nu_usuarioIncluiu
			// dh_inclusao
			// dt_stSolicitacao
			// vr_pfContForn

			$this->vr_pfContForn->EditCustomAttributes = "";
			$this->vr_pfContForn->EditValue = ew_HtmlEncode($this->vr_pfContForn->CurrentValue);
			$this->vr_pfContForn->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_pfContForn->FldCaption()));
			if (strval($this->vr_pfContForn->EditValue) <> "" && is_numeric($this->vr_pfContForn->EditValue)) $this->vr_pfContForn->EditValue = ew_FormatNumber($this->vr_pfContForn->EditValue, -2, -1, -2, 0);

			// nu_tpMetrica
			$this->nu_tpMetrica->EditCustomAttributes = "";
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
			$this->Lookup_Selecting($this->nu_tpMetrica, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpMetrica] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_tpMetrica->EditValue = $arwrk;

			// ds_observacoesContForn
			$this->ds_observacoesContForn->EditCustomAttributes = "";
			$this->ds_observacoesContForn->EditValue = $this->ds_observacoesContForn->CurrentValue;
			$this->ds_observacoesContForn->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_observacoesContForn->FldCaption()));

			// im_anexosContForn
			$this->im_anexosContForn->EditCustomAttributes = "";
			$this->im_anexosContForn->UploadPath = "contagem_fornecedor";
			if (!ew_Empty($this->im_anexosContForn->Upload->DbValue)) {
				$this->im_anexosContForn->EditValue = $this->im_anexosContForn->Upload->DbValue;
			} else {
				$this->im_anexosContForn->EditValue = "";
			}
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->im_anexosContForn);

			// nu_contagemAnt
			$this->nu_contagemAnt->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_solMetricas], [nu_solMetricas] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[solicitacaoMetricas]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_contagemAnt, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_solMetricas] DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_contagemAnt->EditValue = $arwrk;

			// ds_observaocoesContAnt
			$this->ds_observaocoesContAnt->EditCustomAttributes = "";
			$this->ds_observaocoesContAnt->EditValue = $this->ds_observaocoesContAnt->CurrentValue;
			$this->ds_observaocoesContAnt->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_observaocoesContAnt->FldCaption()));

			// im_anexosContAnt
			$this->im_anexosContAnt->EditCustomAttributes = "";
			$this->im_anexosContAnt->UploadPath = "contagem_anterior";
			if (!ew_Empty($this->im_anexosContAnt->Upload->DbValue)) {
				$this->im_anexosContAnt->EditValue = $this->im_anexosContAnt->Upload->DbValue;
			} else {
				$this->im_anexosContAnt->EditValue = "";
			}
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->im_anexosContAnt);

			// Edit refer script
			// nu_tpSolicitacao

			$this->nu_tpSolicitacao->HrefValue = "";

			// nu_projeto
			$this->nu_projeto->HrefValue = "";

			// no_atividadeMaeRedmine
			$this->no_atividadeMaeRedmine->HrefValue = "";

			// ds_observacoes
			$this->ds_observacoes->HrefValue = "";

			// ds_documentacaoAux
			$this->ds_documentacaoAux->HrefValue = "";

			// ds_imapactoDb
			$this->ds_imapactoDb->HrefValue = "";

			// ic_stSolicitacao
			$this->ic_stSolicitacao->HrefValue = "";

			// nu_usuarioAlterou
			$this->nu_usuarioAlterou->HrefValue = "";

			// dh_alteracao
			$this->dh_alteracao->HrefValue = "";

			// nu_usuarioIncluiu
			$this->nu_usuarioIncluiu->HrefValue = "";

			// dh_inclusao
			$this->dh_inclusao->HrefValue = "";

			// dt_stSolicitacao
			$this->dt_stSolicitacao->HrefValue = "";

			// vr_pfContForn
			$this->vr_pfContForn->HrefValue = "";

			// nu_tpMetrica
			$this->nu_tpMetrica->HrefValue = "";

			// ds_observacoesContForn
			$this->ds_observacoesContForn->HrefValue = "";

			// im_anexosContForn
			$this->im_anexosContForn->HrefValue = "";
			$this->im_anexosContForn->HrefValue2 = $this->im_anexosContForn->UploadPath . $this->im_anexosContForn->Upload->DbValue;

			// nu_contagemAnt
			$this->nu_contagemAnt->HrefValue = "";

			// ds_observaocoesContAnt
			$this->ds_observaocoesContAnt->HrefValue = "";

			// im_anexosContAnt
			$this->im_anexosContAnt->HrefValue = "";
			$this->im_anexosContAnt->HrefValue2 = $this->im_anexosContAnt->UploadPath . $this->im_anexosContAnt->Upload->DbValue;
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
		if (!$this->nu_tpSolicitacao->FldIsDetailKey && !is_null($this->nu_tpSolicitacao->FormValue) && $this->nu_tpSolicitacao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_tpSolicitacao->FldCaption());
		}
		if (!$this->nu_projeto->FldIsDetailKey && !is_null($this->nu_projeto->FormValue) && $this->nu_projeto->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_projeto->FldCaption());
		}
		if (!$this->no_atividadeMaeRedmine->FldIsDetailKey && !is_null($this->no_atividadeMaeRedmine->FormValue) && $this->no_atividadeMaeRedmine->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_atividadeMaeRedmine->FldCaption());
		}
		if (!$this->ic_stSolicitacao->FldIsDetailKey && !is_null($this->ic_stSolicitacao->FormValue) && $this->ic_stSolicitacao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_stSolicitacao->FldCaption());
		}
		if (!ew_CheckNumber($this->vr_pfContForn->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_pfContForn->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("solicitacao_ocorrencia", $DetailTblVar) && $GLOBALS["solicitacao_ocorrencia"]->DetailAdd) {
			if (!isset($GLOBALS["solicitacao_ocorrencia_grid"])) $GLOBALS["solicitacao_ocorrencia_grid"] = new csolicitacao_ocorrencia_grid(); // get detail page object
			$GLOBALS["solicitacao_ocorrencia_grid"]->ValidateGridForm();
		}
		if (in_array("contagempf", $DetailTblVar) && $GLOBALS["contagempf"]->DetailAdd) {
			if (!isset($GLOBALS["contagempf_grid"])) $GLOBALS["contagempf_grid"] = new ccontagempf_grid(); // get detail page object
			$GLOBALS["contagempf_grid"]->ValidateGridForm();
		}
		if (in_array("estimativa", $DetailTblVar) && $GLOBALS["estimativa"]->DetailAdd) {
			if (!isset($GLOBALS["estimativa_grid"])) $GLOBALS["estimativa_grid"] = new cestimativa_grid(); // get detail page object
			$GLOBALS["estimativa_grid"]->ValidateGridForm();
		}
		if (in_array("laudo", $DetailTblVar) && $GLOBALS["laudo"]->DetailAdd) {
			if (!isset($GLOBALS["laudo_grid"])) $GLOBALS["laudo_grid"] = new claudo_grid(); // get detail page object
			$GLOBALS["laudo_grid"]->ValidateGridForm();
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

		// Begin transaction
		if ($this->getCurrentDetailTable() <> "")
			$conn->BeginTrans();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
			$this->im_anexosContForn->OldUploadPath = "contagem_fornecedor";
			$this->im_anexosContForn->UploadPath = $this->im_anexosContForn->OldUploadPath;
			$this->im_anexosContAnt->OldUploadPath = "contagem_anterior";
			$this->im_anexosContAnt->UploadPath = $this->im_anexosContAnt->OldUploadPath;
		}
		$rsnew = array();

		// nu_tpSolicitacao
		$this->nu_tpSolicitacao->SetDbValueDef($rsnew, $this->nu_tpSolicitacao->CurrentValue, 0, FALSE);

		// nu_projeto
		$this->nu_projeto->SetDbValueDef($rsnew, $this->nu_projeto->CurrentValue, NULL, FALSE);

		// no_atividadeMaeRedmine
		$this->no_atividadeMaeRedmine->SetDbValueDef($rsnew, $this->no_atividadeMaeRedmine->CurrentValue, NULL, FALSE);

		// ds_observacoes
		$this->ds_observacoes->SetDbValueDef($rsnew, $this->ds_observacoes->CurrentValue, NULL, FALSE);

		// ds_documentacaoAux
		$this->ds_documentacaoAux->SetDbValueDef($rsnew, $this->ds_documentacaoAux->CurrentValue, NULL, FALSE);

		// ds_imapactoDb
		$this->ds_imapactoDb->SetDbValueDef($rsnew, $this->ds_imapactoDb->CurrentValue, NULL, FALSE);

		// ic_stSolicitacao
		$this->ic_stSolicitacao->SetDbValueDef($rsnew, $this->ic_stSolicitacao->CurrentValue, NULL, FALSE);

		// nu_usuarioAlterou
		$this->nu_usuarioAlterou->SetDbValueDef($rsnew, CurrentUserID(), NULL);
		$rsnew['nu_usuarioAlterou'] = &$this->nu_usuarioAlterou->DbValue;

		// dh_alteracao
		$this->dh_alteracao->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
		$rsnew['dh_alteracao'] = &$this->dh_alteracao->DbValue;

		// nu_usuarioIncluiu
		$this->nu_usuarioIncluiu->SetDbValueDef($rsnew, CurrentUserID(), NULL);
		$rsnew['nu_usuarioIncluiu'] = &$this->nu_usuarioIncluiu->DbValue;

		// dh_inclusao
		$this->dh_inclusao->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
		$rsnew['dh_inclusao'] = &$this->dh_inclusao->DbValue;

		// dt_stSolicitacao
		$this->dt_stSolicitacao->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
		$rsnew['dt_stSolicitacao'] = &$this->dt_stSolicitacao->DbValue;

		// vr_pfContForn
		$this->vr_pfContForn->SetDbValueDef($rsnew, $this->vr_pfContForn->CurrentValue, NULL, FALSE);

		// nu_tpMetrica
		$this->nu_tpMetrica->SetDbValueDef($rsnew, $this->nu_tpMetrica->CurrentValue, NULL, FALSE);

		// ds_observacoesContForn
		$this->ds_observacoesContForn->SetDbValueDef($rsnew, $this->ds_observacoesContForn->CurrentValue, NULL, FALSE);

		// im_anexosContForn
		if (!$this->im_anexosContForn->Upload->KeepFile) {
			if ($this->im_anexosContForn->Upload->FileName == "") {
				$rsnew['im_anexosContForn'] = NULL;
			} else {
				$rsnew['im_anexosContForn'] = $this->im_anexosContForn->Upload->FileName;
			}
		}

		// nu_contagemAnt
		$this->nu_contagemAnt->SetDbValueDef($rsnew, $this->nu_contagemAnt->CurrentValue, NULL, FALSE);

		// ds_observaocoesContAnt
		$this->ds_observaocoesContAnt->SetDbValueDef($rsnew, $this->ds_observaocoesContAnt->CurrentValue, NULL, FALSE);

		// im_anexosContAnt
		if (!$this->im_anexosContAnt->Upload->KeepFile) {
			if ($this->im_anexosContAnt->Upload->FileName == "") {
				$rsnew['im_anexosContAnt'] = NULL;
			} else {
				$rsnew['im_anexosContAnt'] = $this->im_anexosContAnt->Upload->FileName;
			}
		}
		if (!$this->im_anexosContForn->Upload->KeepFile) {
			$this->im_anexosContForn->UploadPath = "contagem_fornecedor";
			$OldFiles = explode(",", $this->im_anexosContForn->Upload->DbValue);
			if (!ew_Empty($this->im_anexosContForn->Upload->FileName)) {
				$NewFiles = explode(",", $this->im_anexosContForn->Upload->FileName);
				$FileCount = count($NewFiles);
				for ($i = 0; $i < $FileCount; $i++) {
					$fldvar = ($this->im_anexosContForn->Upload->Index < 0) ? $this->im_anexosContForn->FldVar : substr($this->im_anexosContForn->FldVar, 0, 1) . $this->im_anexosContForn->Upload->Index . substr($this->im_anexosContForn->FldVar, 1);
					if ($NewFiles[$i] <> "") {
						$file = ew_UploadTempPath($fldvar) . EW_PATH_DELIMITER . $NewFiles[$i];
						if (file_exists($file)) {
							if (!in_array($NewFiles[$i], $OldFiles)) {
								$NewFiles[$i] = ew_UploadFileNameEx($this->im_anexosContForn->UploadPath, $NewFiles[$i]); // Get new file name
								$file1 = ew_UploadTempPath($fldvar) . EW_PATH_DELIMITER . $NewFiles[$i];
								if ($file1 <> $file) // Rename temp file
									rename($file, $file1);
							}
						}
					}
				}
				$this->im_anexosContForn->Upload->FileName = implode(",", $NewFiles);
				$rsnew['im_anexosContForn'] = $this->im_anexosContForn->Upload->FileName;
			} else {
				$NewFiles = array();
			}
		}
		if (!$this->im_anexosContAnt->Upload->KeepFile) {
			$this->im_anexosContAnt->UploadPath = "contagem_anterior";
			$OldFiles = explode(",", $this->im_anexosContAnt->Upload->DbValue);
			if (!ew_Empty($this->im_anexosContAnt->Upload->FileName)) {
				$NewFiles = explode(",", $this->im_anexosContAnt->Upload->FileName);
				$FileCount = count($NewFiles);
				for ($i = 0; $i < $FileCount; $i++) {
					$fldvar = ($this->im_anexosContAnt->Upload->Index < 0) ? $this->im_anexosContAnt->FldVar : substr($this->im_anexosContAnt->FldVar, 0, 1) . $this->im_anexosContAnt->Upload->Index . substr($this->im_anexosContAnt->FldVar, 1);
					if ($NewFiles[$i] <> "") {
						$file = ew_UploadTempPath($fldvar) . EW_PATH_DELIMITER . $NewFiles[$i];
						if (file_exists($file)) {
							if (!in_array($NewFiles[$i], $OldFiles)) {
								$NewFiles[$i] = ew_UploadFileNameEx($this->im_anexosContAnt->UploadPath, $NewFiles[$i]); // Get new file name
								$file1 = ew_UploadTempPath($fldvar) . EW_PATH_DELIMITER . $NewFiles[$i];
								if ($file1 <> $file) // Rename temp file
									rename($file, $file1);
							}
						}
					}
				}
				$this->im_anexosContAnt->Upload->FileName = implode(",", $NewFiles);
				$rsnew['im_anexosContAnt'] = $this->im_anexosContAnt->Upload->FileName;
			} else {
				$NewFiles = array();
			}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
				if (!$this->im_anexosContForn->Upload->KeepFile) {
					$OldFiles = explode(",", $this->im_anexosContForn->Upload->DbValue);
					if (!ew_Empty($this->im_anexosContForn->Upload->FileName)) {
						$NewFiles = explode(",", $this->im_anexosContForn->Upload->FileName);
						$NewFiles2 = explode(",", $rsnew['im_anexosContForn']);
						$FileCount = count($NewFiles);
						for ($i = 0; $i < $FileCount; $i++) {
							$fldvar = ($this->im_anexosContForn->Upload->Index < 0) ? $this->im_anexosContForn->FldVar : substr($this->im_anexosContForn->FldVar, 0, 1) . $this->im_anexosContForn->Upload->Index . substr($this->im_anexosContForn->FldVar, 1);
							if ($NewFiles[$i] <> "") {
								$file = ew_UploadTempPath($fldvar) . EW_PATH_DELIMITER . $NewFiles[$i];
								if (file_exists($file)) {
									$this->im_anexosContForn->Upload->Value = file_get_contents($file);
									$this->im_anexosContForn->Upload->SaveToFile($this->im_anexosContForn->UploadPath, (@$NewFiles2[$i] <> "") ? $NewFiles2[$i] : $NewFiles[$i], TRUE); // Just replace
								}
							}
						}
					} else {
						$NewFiles = array();
					}
					$FileCount = count($OldFiles);
					for ($i = 0; $i < $FileCount; $i++) {
						if ($OldFiles[$i] <> "" && !in_array($OldFiles[$i], $NewFiles))
							@unlink(ew_UploadPathEx(TRUE, $this->im_anexosContForn->OldUploadPath) . $OldFiles[$i]);
					}
				}
				if (!$this->im_anexosContAnt->Upload->KeepFile) {
					$OldFiles = explode(",", $this->im_anexosContAnt->Upload->DbValue);
					if (!ew_Empty($this->im_anexosContAnt->Upload->FileName)) {
						$NewFiles = explode(",", $this->im_anexosContAnt->Upload->FileName);
						$NewFiles2 = explode(",", $rsnew['im_anexosContAnt']);
						$FileCount = count($NewFiles);
						for ($i = 0; $i < $FileCount; $i++) {
							$fldvar = ($this->im_anexosContAnt->Upload->Index < 0) ? $this->im_anexosContAnt->FldVar : substr($this->im_anexosContAnt->FldVar, 0, 1) . $this->im_anexosContAnt->Upload->Index . substr($this->im_anexosContAnt->FldVar, 1);
							if ($NewFiles[$i] <> "") {
								$file = ew_UploadTempPath($fldvar) . EW_PATH_DELIMITER . $NewFiles[$i];
								if (file_exists($file)) {
									$this->im_anexosContAnt->Upload->Value = file_get_contents($file);
									$this->im_anexosContAnt->Upload->SaveToFile($this->im_anexosContAnt->UploadPath, (@$NewFiles2[$i] <> "") ? $NewFiles2[$i] : $NewFiles[$i], TRUE); // Just replace
								}
							}
						}
					} else {
						$NewFiles = array();
					}
					$FileCount = count($OldFiles);
					for ($i = 0; $i < $FileCount; $i++) {
						if ($OldFiles[$i] <> "" && !in_array($OldFiles[$i], $NewFiles))
							@unlink(ew_UploadPathEx(TRUE, $this->im_anexosContAnt->OldUploadPath) . $OldFiles[$i]);
					}
				}
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
			$this->nu_solMetricas->setDbValue($conn->Insert_ID());
			$rsnew['nu_solMetricas'] = $this->nu_solMetricas->DbValue;
		}

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("solicitacao_ocorrencia", $DetailTblVar) && $GLOBALS["solicitacao_ocorrencia"]->DetailAdd) {
				$GLOBALS["solicitacao_ocorrencia"]->nu_solicitacao->setSessionValue($this->nu_solMetricas->CurrentValue); // Set master key
				if (!isset($GLOBALS["solicitacao_ocorrencia_grid"])) $GLOBALS["solicitacao_ocorrencia_grid"] = new csolicitacao_ocorrencia_grid(); // Get detail page object
				$AddRow = $GLOBALS["solicitacao_ocorrencia_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["solicitacao_ocorrencia"]->nu_solicitacao->setSessionValue(""); // Clear master key if insert failed
			}
			if (in_array("contagempf", $DetailTblVar) && $GLOBALS["contagempf"]->DetailAdd) {
				$GLOBALS["contagempf"]->nu_solMetricas->setSessionValue($this->nu_solMetricas->CurrentValue); // Set master key
				if (!isset($GLOBALS["contagempf_grid"])) $GLOBALS["contagempf_grid"] = new ccontagempf_grid(); // Get detail page object
				$AddRow = $GLOBALS["contagempf_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["contagempf"]->nu_solMetricas->setSessionValue(""); // Clear master key if insert failed
			}
			if (in_array("estimativa", $DetailTblVar) && $GLOBALS["estimativa"]->DetailAdd) {
				$GLOBALS["estimativa"]->nu_solMetricas->setSessionValue($this->nu_solMetricas->CurrentValue); // Set master key
				if (!isset($GLOBALS["estimativa_grid"])) $GLOBALS["estimativa_grid"] = new cestimativa_grid(); // Get detail page object
				$AddRow = $GLOBALS["estimativa_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["estimativa"]->nu_solMetricas->setSessionValue(""); // Clear master key if insert failed
			}
			if (in_array("laudo", $DetailTblVar) && $GLOBALS["laudo"]->DetailAdd) {
				$GLOBALS["laudo"]->nu_solicitacao->setSessionValue($this->nu_solMetricas->CurrentValue); // Set master key
				if (!isset($GLOBALS["laudo_grid"])) $GLOBALS["laudo_grid"] = new claudo_grid(); // Get detail page object
				$AddRow = $GLOBALS["laudo_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["laudo"]->nu_solicitacao->setSessionValue(""); // Clear master key if insert failed
			}
		}

		// Commit/Rollback transaction
		if ($this->getCurrentDetailTable() <> "") {
			if ($AddRow) {
				$conn->CommitTrans(); // Commit transaction
			} else {
				$conn->RollbackTrans(); // Rollback transaction
			}
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
			$this->WriteAuditTrailOnAdd($rsnew);
		}

		// im_anexosContForn
		ew_CleanUploadTempPath($this->im_anexosContForn, $this->im_anexosContForn->Upload->Index);

		// im_anexosContAnt
		ew_CleanUploadTempPath($this->im_anexosContAnt, $this->im_anexosContAnt->Upload->Index);
		return $AddRow;
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
			if (in_array("solicitacao_ocorrencia", $DetailTblVar)) {
				if (!isset($GLOBALS["solicitacao_ocorrencia_grid"]))
					$GLOBALS["solicitacao_ocorrencia_grid"] = new csolicitacao_ocorrencia_grid;
				if ($GLOBALS["solicitacao_ocorrencia_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["solicitacao_ocorrencia_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["solicitacao_ocorrencia_grid"]->CurrentMode = "add";
					$GLOBALS["solicitacao_ocorrencia_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["solicitacao_ocorrencia_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["solicitacao_ocorrencia_grid"]->setStartRecordNumber(1);
					$GLOBALS["solicitacao_ocorrencia_grid"]->nu_solicitacao->FldIsDetailKey = TRUE;
					$GLOBALS["solicitacao_ocorrencia_grid"]->nu_solicitacao->CurrentValue = $this->nu_solMetricas->CurrentValue;
					$GLOBALS["solicitacao_ocorrencia_grid"]->nu_solicitacao->setSessionValue($GLOBALS["solicitacao_ocorrencia_grid"]->nu_solicitacao->CurrentValue);
				}
			}
			if (in_array("contagempf", $DetailTblVar)) {
				if (!isset($GLOBALS["contagempf_grid"]))
					$GLOBALS["contagempf_grid"] = new ccontagempf_grid;
				if ($GLOBALS["contagempf_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["contagempf_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["contagempf_grid"]->CurrentMode = "add";
					$GLOBALS["contagempf_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["contagempf_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["contagempf_grid"]->setStartRecordNumber(1);
					$GLOBALS["contagempf_grid"]->nu_solMetricas->FldIsDetailKey = TRUE;
					$GLOBALS["contagempf_grid"]->nu_solMetricas->CurrentValue = $this->nu_solMetricas->CurrentValue;
					$GLOBALS["contagempf_grid"]->nu_solMetricas->setSessionValue($GLOBALS["contagempf_grid"]->nu_solMetricas->CurrentValue);
				}
			}
			if (in_array("estimativa", $DetailTblVar)) {
				if (!isset($GLOBALS["estimativa_grid"]))
					$GLOBALS["estimativa_grid"] = new cestimativa_grid;
				if ($GLOBALS["estimativa_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["estimativa_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["estimativa_grid"]->CurrentMode = "add";
					$GLOBALS["estimativa_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["estimativa_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["estimativa_grid"]->setStartRecordNumber(1);
					$GLOBALS["estimativa_grid"]->nu_solMetricas->FldIsDetailKey = TRUE;
					$GLOBALS["estimativa_grid"]->nu_solMetricas->CurrentValue = $this->nu_solMetricas->CurrentValue;
					$GLOBALS["estimativa_grid"]->nu_solMetricas->setSessionValue($GLOBALS["estimativa_grid"]->nu_solMetricas->CurrentValue);
				}
			}
			if (in_array("laudo", $DetailTblVar)) {
				if (!isset($GLOBALS["laudo_grid"]))
					$GLOBALS["laudo_grid"] = new claudo_grid;
				if ($GLOBALS["laudo_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["laudo_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["laudo_grid"]->CurrentMode = "add";
					$GLOBALS["laudo_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["laudo_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["laudo_grid"]->setStartRecordNumber(1);
					$GLOBALS["laudo_grid"]->nu_solicitacao->FldIsDetailKey = TRUE;
					$GLOBALS["laudo_grid"]->nu_solicitacao->CurrentValue = $this->nu_solMetricas->CurrentValue;
					$GLOBALS["laudo_grid"]->nu_solicitacao->setSessionValue($GLOBALS["laudo_grid"]->nu_solicitacao->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "solicitacaometricaslist.php", $this->TableVar);
		$PageCaption = ($this->CurrentAction == "C") ? $Language->Phrase("Copy") : $Language->Phrase("Add");
		$Breadcrumb->Add("add", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'solicitacaoMetricas';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'solicitacaoMetricas';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_solMetricas'];

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
if (!isset($solicitacaoMetricas_add)) $solicitacaoMetricas_add = new csolicitacaoMetricas_add();

// Page init
$solicitacaoMetricas_add->Page_Init();

// Page main
$solicitacaoMetricas_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$solicitacaoMetricas_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var solicitacaoMetricas_add = new ew_Page("solicitacaoMetricas_add");
solicitacaoMetricas_add.PageID = "add"; // Page ID
var EW_PAGE_ID = solicitacaoMetricas_add.PageID; // For backward compatibility

// Form object
var fsolicitacaoMetricasadd = new ew_Form("fsolicitacaoMetricasadd");

// Validate form
fsolicitacaoMetricasadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_tpSolicitacao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($solicitacaoMetricas->nu_tpSolicitacao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_projeto");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($solicitacaoMetricas->nu_projeto->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no_atividadeMaeRedmine");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($solicitacaoMetricas->no_atividadeMaeRedmine->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_stSolicitacao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($solicitacaoMetricas->ic_stSolicitacao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_pfContForn");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($solicitacaoMetricas->vr_pfContForn->FldErrMsg()) ?>");

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
fsolicitacaoMetricasadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsolicitacaoMetricasadd.ValidateRequired = true;
<?php } else { ?>
fsolicitacaoMetricasadd.ValidateRequired = false; 
<?php } ?>

// Multi-Page properties
fsolicitacaoMetricasadd.MultiPage = new ew_MultiPage("fsolicitacaoMetricasadd",
	[["x_nu_tpSolicitacao",1],["x_nu_projeto",1],["x_no_atividadeMaeRedmine",1],["x_ds_observacoes",1],["x_ds_documentacaoAux",1],["x_ds_imapactoDb",1],["x_ic_stSolicitacao",1],["x_vr_pfContForn",2],["x_nu_tpMetrica",2],["x_ds_observacoesContForn",2],["x_im_anexosContForn",2],["x_nu_contagemAnt",3],["x_ds_observaocoesContAnt",3],["x_im_anexosContAnt",3]]
);

// Dynamic selection lists
fsolicitacaoMetricasadd.Lists["x_nu_tpSolicitacao"] = {"LinkField":"x_nu_tpSolicitacao","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpSolicitacao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fsolicitacaoMetricasadd.Lists["x_nu_projeto"] = {"LinkField":"x_nu_projeto","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_projeto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fsolicitacaoMetricasadd.Lists["x_nu_usuarioAlterou"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fsolicitacaoMetricasadd.Lists["x_nu_usuarioIncluiu"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fsolicitacaoMetricasadd.Lists["x_nu_tpMetrica"] = {"LinkField":"x_nu_tpMetrica","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpMetrica","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fsolicitacaoMetricasadd.Lists["x_nu_contagemAnt"] = {"LinkField":"x_nu_solMetricas","Ajax":null,"AutoFill":false,"DisplayFields":["x_nu_solMetricas","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $solicitacaoMetricas_add->ShowPageHeader(); ?>
<?php
$solicitacaoMetricas_add->ShowMessage();
?>
<form name="fsolicitacaoMetricasadd" id="fsolicitacaoMetricasadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="solicitacaoMetricas">
<input type="hidden" name="a_add" id="a_add" value="A">
<table class="ewStdTable"><tbody><tr><td>
<div class="tabbable" id="solicitacaoMetricas_add">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_solicitacaoMetricas1" data-toggle="tab"><?php echo $solicitacaoMetricas->PageCaption(1) ?></a></li>
		<li><a href="#tab_solicitacaoMetricas2" data-toggle="tab"><?php echo $solicitacaoMetricas->PageCaption(2) ?></a></li>
		<li><a href="#tab_solicitacaoMetricas3" data-toggle="tab"><?php echo $solicitacaoMetricas->PageCaption(3) ?></a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab_solicitacaoMetricas1">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_solicitacaoMetricasadd1" class="table table-bordered table-striped">
<?php if ($solicitacaoMetricas->nu_tpSolicitacao->Visible) { // nu_tpSolicitacao ?>
	<tr id="r_nu_tpSolicitacao">
		<td><span id="elh_solicitacaoMetricas_nu_tpSolicitacao"><?php echo $solicitacaoMetricas->nu_tpSolicitacao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $solicitacaoMetricas->nu_tpSolicitacao->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_nu_tpSolicitacao" class="control-group">
<select data-field="x_nu_tpSolicitacao" id="x_nu_tpSolicitacao" name="x_nu_tpSolicitacao"<?php echo $solicitacaoMetricas->nu_tpSolicitacao->EditAttributes() ?>>
<?php
if (is_array($solicitacaoMetricas->nu_tpSolicitacao->EditValue)) {
	$arwrk = $solicitacaoMetricas->nu_tpSolicitacao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($solicitacaoMetricas->nu_tpSolicitacao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fsolicitacaoMetricasadd.Lists["x_nu_tpSolicitacao"].Options = <?php echo (is_array($solicitacaoMetricas->nu_tpSolicitacao->EditValue)) ? ew_ArrayToJson($solicitacaoMetricas->nu_tpSolicitacao->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $solicitacaoMetricas->nu_tpSolicitacao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($solicitacaoMetricas->nu_projeto->Visible) { // nu_projeto ?>
	<tr id="r_nu_projeto">
		<td><span id="elh_solicitacaoMetricas_nu_projeto"><?php echo $solicitacaoMetricas->nu_projeto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $solicitacaoMetricas->nu_projeto->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_nu_projeto" class="control-group">
<select data-field="x_nu_projeto" id="x_nu_projeto" name="x_nu_projeto"<?php echo $solicitacaoMetricas->nu_projeto->EditAttributes() ?>>
<?php
if (is_array($solicitacaoMetricas->nu_projeto->EditValue)) {
	$arwrk = $solicitacaoMetricas->nu_projeto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($solicitacaoMetricas->nu_projeto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
&nbsp;<a id="aol_x_nu_projeto" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_nu_projeto',url:'projetoaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $solicitacaoMetricas->nu_projeto->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
fsolicitacaoMetricasadd.Lists["x_nu_projeto"].Options = <?php echo (is_array($solicitacaoMetricas->nu_projeto->EditValue)) ? ew_ArrayToJson($solicitacaoMetricas->nu_projeto->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $solicitacaoMetricas->nu_projeto->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($solicitacaoMetricas->no_atividadeMaeRedmine->Visible) { // no_atividadeMaeRedmine ?>
	<tr id="r_no_atividadeMaeRedmine">
		<td><span id="elh_solicitacaoMetricas_no_atividadeMaeRedmine"><?php echo $solicitacaoMetricas->no_atividadeMaeRedmine->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $solicitacaoMetricas->no_atividadeMaeRedmine->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_no_atividadeMaeRedmine" class="control-group">
<input type="text" data-field="x_no_atividadeMaeRedmine" name="x_no_atividadeMaeRedmine" id="x_no_atividadeMaeRedmine" size="30" maxlength="120" placeholder="<?php echo $solicitacaoMetricas->no_atividadeMaeRedmine->PlaceHolder ?>" value="<?php echo $solicitacaoMetricas->no_atividadeMaeRedmine->EditValue ?>"<?php echo $solicitacaoMetricas->no_atividadeMaeRedmine->EditAttributes() ?>>
</span>
<?php echo $solicitacaoMetricas->no_atividadeMaeRedmine->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($solicitacaoMetricas->ds_observacoes->Visible) { // ds_observacoes ?>
	<tr id="r_ds_observacoes">
		<td><span id="elh_solicitacaoMetricas_ds_observacoes"><?php echo $solicitacaoMetricas->ds_observacoes->FldCaption() ?></span></td>
		<td<?php echo $solicitacaoMetricas->ds_observacoes->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_ds_observacoes" class="control-group">
<textarea data-field="x_ds_observacoes" name="x_ds_observacoes" id="x_ds_observacoes" cols="35" rows="8" placeholder="<?php echo $solicitacaoMetricas->ds_observacoes->PlaceHolder ?>"<?php echo $solicitacaoMetricas->ds_observacoes->EditAttributes() ?>><?php echo $solicitacaoMetricas->ds_observacoes->EditValue ?></textarea>
</span>
<?php echo $solicitacaoMetricas->ds_observacoes->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($solicitacaoMetricas->ds_documentacaoAux->Visible) { // ds_documentacaoAux ?>
	<tr id="r_ds_documentacaoAux">
		<td><span id="elh_solicitacaoMetricas_ds_documentacaoAux"><?php echo $solicitacaoMetricas->ds_documentacaoAux->FldCaption() ?></span></td>
		<td<?php echo $solicitacaoMetricas->ds_documentacaoAux->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_ds_documentacaoAux" class="control-group">
<textarea data-field="x_ds_documentacaoAux" name="x_ds_documentacaoAux" id="x_ds_documentacaoAux" cols="35" rows="4" placeholder="<?php echo $solicitacaoMetricas->ds_documentacaoAux->PlaceHolder ?>"<?php echo $solicitacaoMetricas->ds_documentacaoAux->EditAttributes() ?>><?php echo $solicitacaoMetricas->ds_documentacaoAux->EditValue ?></textarea>
</span>
<?php echo $solicitacaoMetricas->ds_documentacaoAux->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($solicitacaoMetricas->ds_imapactoDb->Visible) { // ds_imapactoDb ?>
	<tr id="r_ds_imapactoDb">
		<td><span id="elh_solicitacaoMetricas_ds_imapactoDb"><?php echo $solicitacaoMetricas->ds_imapactoDb->FldCaption() ?></span></td>
		<td<?php echo $solicitacaoMetricas->ds_imapactoDb->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_ds_imapactoDb" class="control-group">
<textarea data-field="x_ds_imapactoDb" name="x_ds_imapactoDb" id="x_ds_imapactoDb" cols="35" rows="4" placeholder="<?php echo $solicitacaoMetricas->ds_imapactoDb->PlaceHolder ?>"<?php echo $solicitacaoMetricas->ds_imapactoDb->EditAttributes() ?>><?php echo $solicitacaoMetricas->ds_imapactoDb->EditValue ?></textarea>
</span>
<?php echo $solicitacaoMetricas->ds_imapactoDb->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($solicitacaoMetricas->ic_stSolicitacao->Visible) { // ic_stSolicitacao ?>
	<tr id="r_ic_stSolicitacao">
		<td><span id="elh_solicitacaoMetricas_ic_stSolicitacao"><?php echo $solicitacaoMetricas->ic_stSolicitacao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $solicitacaoMetricas->ic_stSolicitacao->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_ic_stSolicitacao" class="control-group">
<select data-field="x_ic_stSolicitacao" id="x_ic_stSolicitacao" name="x_ic_stSolicitacao"<?php echo $solicitacaoMetricas->ic_stSolicitacao->EditAttributes() ?>>
<?php
if (is_array($solicitacaoMetricas->ic_stSolicitacao->EditValue)) {
	$arwrk = $solicitacaoMetricas->ic_stSolicitacao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($solicitacaoMetricas->ic_stSolicitacao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $solicitacaoMetricas->ic_stSolicitacao->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_solicitacaoMetricas2">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_solicitacaoMetricasadd2" class="table table-bordered table-striped">
<?php if ($solicitacaoMetricas->vr_pfContForn->Visible) { // vr_pfContForn ?>
	<tr id="r_vr_pfContForn">
		<td><span id="elh_solicitacaoMetricas_vr_pfContForn"><?php echo $solicitacaoMetricas->vr_pfContForn->FldCaption() ?></span></td>
		<td<?php echo $solicitacaoMetricas->vr_pfContForn->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_vr_pfContForn" class="control-group">
<input type="text" data-field="x_vr_pfContForn" name="x_vr_pfContForn" id="x_vr_pfContForn" size="30" placeholder="<?php echo $solicitacaoMetricas->vr_pfContForn->PlaceHolder ?>" value="<?php echo $solicitacaoMetricas->vr_pfContForn->EditValue ?>"<?php echo $solicitacaoMetricas->vr_pfContForn->EditAttributes() ?>>
</span>
<?php echo $solicitacaoMetricas->vr_pfContForn->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($solicitacaoMetricas->nu_tpMetrica->Visible) { // nu_tpMetrica ?>
	<tr id="r_nu_tpMetrica">
		<td><span id="elh_solicitacaoMetricas_nu_tpMetrica"><?php echo $solicitacaoMetricas->nu_tpMetrica->FldCaption() ?></span></td>
		<td<?php echo $solicitacaoMetricas->nu_tpMetrica->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_nu_tpMetrica" class="control-group">
<select data-field="x_nu_tpMetrica" id="x_nu_tpMetrica" name="x_nu_tpMetrica"<?php echo $solicitacaoMetricas->nu_tpMetrica->EditAttributes() ?>>
<?php
if (is_array($solicitacaoMetricas->nu_tpMetrica->EditValue)) {
	$arwrk = $solicitacaoMetricas->nu_tpMetrica->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($solicitacaoMetricas->nu_tpMetrica->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fsolicitacaoMetricasadd.Lists["x_nu_tpMetrica"].Options = <?php echo (is_array($solicitacaoMetricas->nu_tpMetrica->EditValue)) ? ew_ArrayToJson($solicitacaoMetricas->nu_tpMetrica->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $solicitacaoMetricas->nu_tpMetrica->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($solicitacaoMetricas->ds_observacoesContForn->Visible) { // ds_observacoesContForn ?>
	<tr id="r_ds_observacoesContForn">
		<td><span id="elh_solicitacaoMetricas_ds_observacoesContForn"><?php echo $solicitacaoMetricas->ds_observacoesContForn->FldCaption() ?></span></td>
		<td<?php echo $solicitacaoMetricas->ds_observacoesContForn->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_ds_observacoesContForn" class="control-group">
<textarea data-field="x_ds_observacoesContForn" name="x_ds_observacoesContForn" id="x_ds_observacoesContForn" cols="35" rows="4" placeholder="<?php echo $solicitacaoMetricas->ds_observacoesContForn->PlaceHolder ?>"<?php echo $solicitacaoMetricas->ds_observacoesContForn->EditAttributes() ?>><?php echo $solicitacaoMetricas->ds_observacoesContForn->EditValue ?></textarea>
</span>
<?php echo $solicitacaoMetricas->ds_observacoesContForn->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($solicitacaoMetricas->im_anexosContForn->Visible) { // im_anexosContForn ?>
	<tr id="r_im_anexosContForn">
		<td><span id="elh_solicitacaoMetricas_im_anexosContForn"><?php echo $solicitacaoMetricas->im_anexosContForn->FldCaption() ?></span></td>
		<td<?php echo $solicitacaoMetricas->im_anexosContForn->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_im_anexosContForn" class="control-group">
<span id="fd_x_im_anexosContForn">
<span class="btn btn-small fileinput-button">
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_im_anexosContForn" name="x_im_anexosContForn" id="x_im_anexosContForn" multiple="multiple">
</span>
<input type="hidden" name="fn_x_im_anexosContForn" id= "fn_x_im_anexosContForn" value="<?php echo $solicitacaoMetricas->im_anexosContForn->Upload->FileName ?>">
<input type="hidden" name="fa_x_im_anexosContForn" id= "fa_x_im_anexosContForn" value="0">
<input type="hidden" name="fs_x_im_anexosContForn" id= "fs_x_im_anexosContForn" value="2147483647">
</span>
<table id="ft_x_im_anexosContForn" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $solicitacaoMetricas->im_anexosContForn->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_solicitacaoMetricas3">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_solicitacaoMetricasadd3" class="table table-bordered table-striped">
<?php if ($solicitacaoMetricas->nu_contagemAnt->Visible) { // nu_contagemAnt ?>
	<tr id="r_nu_contagemAnt">
		<td><span id="elh_solicitacaoMetricas_nu_contagemAnt"><?php echo $solicitacaoMetricas->nu_contagemAnt->FldCaption() ?></span></td>
		<td<?php echo $solicitacaoMetricas->nu_contagemAnt->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_nu_contagemAnt" class="control-group">
<select data-field="x_nu_contagemAnt" id="x_nu_contagemAnt" name="x_nu_contagemAnt"<?php echo $solicitacaoMetricas->nu_contagemAnt->EditAttributes() ?>>
<?php
if (is_array($solicitacaoMetricas->nu_contagemAnt->EditValue)) {
	$arwrk = $solicitacaoMetricas->nu_contagemAnt->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($solicitacaoMetricas->nu_contagemAnt->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fsolicitacaoMetricasadd.Lists["x_nu_contagemAnt"].Options = <?php echo (is_array($solicitacaoMetricas->nu_contagemAnt->EditValue)) ? ew_ArrayToJson($solicitacaoMetricas->nu_contagemAnt->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $solicitacaoMetricas->nu_contagemAnt->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($solicitacaoMetricas->ds_observaocoesContAnt->Visible) { // ds_observaocoesContAnt ?>
	<tr id="r_ds_observaocoesContAnt">
		<td><span id="elh_solicitacaoMetricas_ds_observaocoesContAnt"><?php echo $solicitacaoMetricas->ds_observaocoesContAnt->FldCaption() ?></span></td>
		<td<?php echo $solicitacaoMetricas->ds_observaocoesContAnt->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_ds_observaocoesContAnt" class="control-group">
<textarea data-field="x_ds_observaocoesContAnt" name="x_ds_observaocoesContAnt" id="x_ds_observaocoesContAnt" cols="35" rows="4" placeholder="<?php echo $solicitacaoMetricas->ds_observaocoesContAnt->PlaceHolder ?>"<?php echo $solicitacaoMetricas->ds_observaocoesContAnt->EditAttributes() ?>><?php echo $solicitacaoMetricas->ds_observaocoesContAnt->EditValue ?></textarea>
</span>
<?php echo $solicitacaoMetricas->ds_observaocoesContAnt->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($solicitacaoMetricas->im_anexosContAnt->Visible) { // im_anexosContAnt ?>
	<tr id="r_im_anexosContAnt">
		<td><span id="elh_solicitacaoMetricas_im_anexosContAnt"><?php echo $solicitacaoMetricas->im_anexosContAnt->FldCaption() ?></span></td>
		<td<?php echo $solicitacaoMetricas->im_anexosContAnt->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_im_anexosContAnt" class="control-group">
<span id="fd_x_im_anexosContAnt">
<span class="btn btn-small fileinput-button">
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_im_anexosContAnt" name="x_im_anexosContAnt" id="x_im_anexosContAnt" multiple="multiple">
</span>
<input type="hidden" name="fn_x_im_anexosContAnt" id= "fn_x_im_anexosContAnt" value="<?php echo $solicitacaoMetricas->im_anexosContAnt->Upload->FileName ?>">
<input type="hidden" name="fa_x_im_anexosContAnt" id= "fa_x_im_anexosContAnt" value="0">
<input type="hidden" name="fs_x_im_anexosContAnt" id= "fs_x_im_anexosContAnt" value="2147483647">
</span>
<table id="ft_x_im_anexosContAnt" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $solicitacaoMetricas->im_anexosContAnt->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
	</div>
</div>
</td></tr></tbody></table>
<?php
	if (in_array("solicitacao_ocorrencia", explode(",", $solicitacaoMetricas->getCurrentDetailTable())) && $solicitacao_ocorrencia->DetailAdd) {
?>
<?php include_once "solicitacao_ocorrenciagrid.php" ?>
<?php } ?>
<?php
	if (in_array("contagempf", explode(",", $solicitacaoMetricas->getCurrentDetailTable())) && $contagempf->DetailAdd) {
?>
<?php include_once "contagempfgrid.php" ?>
<?php } ?>
<?php
	if (in_array("estimativa", explode(",", $solicitacaoMetricas->getCurrentDetailTable())) && $estimativa->DetailAdd) {
?>
<?php include_once "estimativagrid.php" ?>
<?php } ?>
<?php
	if (in_array("laudo", explode(",", $solicitacaoMetricas->getCurrentDetailTable())) && $laudo->DetailAdd) {
?>
<?php include_once "laudogrid.php" ?>
<?php } ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fsolicitacaoMetricasadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$solicitacaoMetricas_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$solicitacaoMetricas_add->Page_Terminate();
?>
