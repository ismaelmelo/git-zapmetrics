<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "gpcomiteinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$gpcomite_add = NULL; // Initialize page object first

class cgpcomite_add extends cgpcomite {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'gpcomite';

	// Page object name
	var $PageObjName = 'gpcomite_add';

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

		// Table object (gpcomite)
		if (!isset($GLOBALS["gpcomite"])) {
			$GLOBALS["gpcomite"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["gpcomite"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'gpcomite', TRUE);

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
			$this->Page_Terminate("gpcomitelist.php");
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
			if (@$_GET["nu_gpComite"] != "") {
				$this->nu_gpComite->setQueryStringValue($_GET["nu_gpComite"]);
				$this->setKey("nu_gpComite", $this->nu_gpComite->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_gpComite", ""); // Clear key
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
					$this->Page_Terminate("gpcomitelist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "gpcomiteview.php")
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
		$this->im_anexoDiretrizes->Upload->Index = $objForm->Index;
		if ($this->im_anexoDiretrizes->Upload->UploadFile()) {

			// No action required
		} else {
			echo $this->im_anexoDiretrizes->Upload->Message;
			$this->Page_Terminate();
			exit();
		}
		$this->im_anexoDiretrizes->CurrentValue = $this->im_anexoDiretrizes->Upload->FileName;
		$this->im_anexoComunicacao->Upload->Index = $objForm->Index;
		if ($this->im_anexoComunicacao->Upload->UploadFile()) {

			// No action required
		} else {
			echo $this->im_anexoComunicacao->Upload->Message;
			$this->Page_Terminate();
			exit();
		}
		$this->im_anexoComunicacao->CurrentValue = $this->im_anexoComunicacao->Upload->FileName;
		$this->im_anexoParecerJuridico->Upload->Index = $objForm->Index;
		if ($this->im_anexoParecerJuridico->Upload->UploadFile()) {

			// No action required
		} else {
			echo $this->im_anexoParecerJuridico->Upload->Message;
			$this->Page_Terminate();
			exit();
		}
		$this->im_anexoParecerJuridico->CurrentValue = $this->im_anexoParecerJuridico->Upload->FileName;
		$this->im_anexoDesignacao->Upload->Index = $objForm->Index;
		if ($this->im_anexoDesignacao->Upload->UploadFile()) {

			// No action required
		} else {
			echo $this->im_anexoDesignacao->Upload->Message;
			$this->Page_Terminate();
			exit();
		}
		$this->im_anexoDesignacao->CurrentValue = $this->im_anexoDesignacao->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->no_gpComite->CurrentValue = NULL;
		$this->no_gpComite->OldValue = $this->no_gpComite->CurrentValue;
		$this->ic_tpGpOuComite->CurrentValue = NULL;
		$this->ic_tpGpOuComite->OldValue = $this->ic_tpGpOuComite->CurrentValue;
		$this->ds_descricao->CurrentValue = NULL;
		$this->ds_descricao->OldValue = $this->ds_descricao->CurrentValue;
		$this->ds_finalidade->CurrentValue = NULL;
		$this->ds_finalidade->OldValue = $this->ds_finalidade->CurrentValue;
		$this->ic_natureza->CurrentValue = NULL;
		$this->ic_natureza->OldValue = $this->ic_natureza->CurrentValue;
		$this->ds_competencias->CurrentValue = NULL;
		$this->ds_competencias->OldValue = $this->ds_competencias->CurrentValue;
		$this->ic_periodicidadeReunioes->CurrentValue = NULL;
		$this->ic_periodicidadeReunioes->OldValue = $this->ic_periodicidadeReunioes->CurrentValue;
		$this->dt_basePeriodicidade->CurrentValue = NULL;
		$this->dt_basePeriodicidade->OldValue = $this->dt_basePeriodicidade->CurrentValue;
		$this->no_localDocDiretrizes->CurrentValue = NULL;
		$this->no_localDocDiretrizes->OldValue = $this->no_localDocDiretrizes->CurrentValue;
		$this->im_anexoDiretrizes->Upload->DbValue = NULL;
		$this->im_anexoDiretrizes->OldValue = $this->im_anexoDiretrizes->Upload->DbValue;
		$this->im_anexoDiretrizes->CurrentValue = NULL; // Clear file related field
		$this->no_localDocComunicacao->CurrentValue = NULL;
		$this->no_localDocComunicacao->OldValue = $this->no_localDocComunicacao->CurrentValue;
		$this->im_anexoComunicacao->Upload->DbValue = NULL;
		$this->im_anexoComunicacao->OldValue = $this->im_anexoComunicacao->Upload->DbValue;
		$this->im_anexoComunicacao->CurrentValue = NULL; // Clear file related field
		$this->no_localParecerJuridico->CurrentValue = NULL;
		$this->no_localParecerJuridico->OldValue = $this->no_localParecerJuridico->CurrentValue;
		$this->im_anexoParecerJuridico->Upload->DbValue = NULL;
		$this->im_anexoParecerJuridico->OldValue = $this->im_anexoParecerJuridico->Upload->DbValue;
		$this->im_anexoParecerJuridico->CurrentValue = NULL; // Clear file related field
		$this->no_localDocDesignacao->CurrentValue = NULL;
		$this->no_localDocDesignacao->OldValue = $this->no_localDocDesignacao->CurrentValue;
		$this->im_anexoDesignacao->Upload->DbValue = NULL;
		$this->im_anexoDesignacao->OldValue = $this->im_anexoDesignacao->Upload->DbValue;
		$this->im_anexoDesignacao->CurrentValue = NULL; // Clear file related field
		$this->ds_partesInteressadas->CurrentValue = NULL;
		$this->ds_partesInteressadas->OldValue = $this->ds_partesInteressadas->CurrentValue;
		$this->nu_usuario->CurrentValue = NULL;
		$this->nu_usuario->OldValue = $this->nu_usuario->CurrentValue;
		$this->ts_datahora->CurrentValue = NULL;
		$this->ts_datahora->OldValue = $this->ts_datahora->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->no_gpComite->FldIsDetailKey) {
			$this->no_gpComite->setFormValue($objForm->GetValue("x_no_gpComite"));
		}
		if (!$this->ic_tpGpOuComite->FldIsDetailKey) {
			$this->ic_tpGpOuComite->setFormValue($objForm->GetValue("x_ic_tpGpOuComite"));
		}
		if (!$this->ds_descricao->FldIsDetailKey) {
			$this->ds_descricao->setFormValue($objForm->GetValue("x_ds_descricao"));
		}
		if (!$this->ds_finalidade->FldIsDetailKey) {
			$this->ds_finalidade->setFormValue($objForm->GetValue("x_ds_finalidade"));
		}
		if (!$this->ic_natureza->FldIsDetailKey) {
			$this->ic_natureza->setFormValue($objForm->GetValue("x_ic_natureza"));
		}
		if (!$this->ds_competencias->FldIsDetailKey) {
			$this->ds_competencias->setFormValue($objForm->GetValue("x_ds_competencias"));
		}
		if (!$this->ic_periodicidadeReunioes->FldIsDetailKey) {
			$this->ic_periodicidadeReunioes->setFormValue($objForm->GetValue("x_ic_periodicidadeReunioes"));
		}
		if (!$this->dt_basePeriodicidade->FldIsDetailKey) {
			$this->dt_basePeriodicidade->setFormValue($objForm->GetValue("x_dt_basePeriodicidade"));
			$this->dt_basePeriodicidade->CurrentValue = ew_UnFormatDateTime($this->dt_basePeriodicidade->CurrentValue, 7);
		}
		if (!$this->no_localDocDiretrizes->FldIsDetailKey) {
			$this->no_localDocDiretrizes->setFormValue($objForm->GetValue("x_no_localDocDiretrizes"));
		}
		if (!$this->no_localDocComunicacao->FldIsDetailKey) {
			$this->no_localDocComunicacao->setFormValue($objForm->GetValue("x_no_localDocComunicacao"));
		}
		if (!$this->no_localParecerJuridico->FldIsDetailKey) {
			$this->no_localParecerJuridico->setFormValue($objForm->GetValue("x_no_localParecerJuridico"));
		}
		if (!$this->no_localDocDesignacao->FldIsDetailKey) {
			$this->no_localDocDesignacao->setFormValue($objForm->GetValue("x_no_localDocDesignacao"));
		}
		if (!$this->ds_partesInteressadas->FldIsDetailKey) {
			$this->ds_partesInteressadas->setFormValue($objForm->GetValue("x_ds_partesInteressadas"));
		}
		if (!$this->nu_usuario->FldIsDetailKey) {
			$this->nu_usuario->setFormValue($objForm->GetValue("x_nu_usuario"));
		}
		if (!$this->ts_datahora->FldIsDetailKey) {
			$this->ts_datahora->setFormValue($objForm->GetValue("x_ts_datahora"));
			$this->ts_datahora->CurrentValue = ew_UnFormatDateTime($this->ts_datahora->CurrentValue, 7);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->no_gpComite->CurrentValue = $this->no_gpComite->FormValue;
		$this->ic_tpGpOuComite->CurrentValue = $this->ic_tpGpOuComite->FormValue;
		$this->ds_descricao->CurrentValue = $this->ds_descricao->FormValue;
		$this->ds_finalidade->CurrentValue = $this->ds_finalidade->FormValue;
		$this->ic_natureza->CurrentValue = $this->ic_natureza->FormValue;
		$this->ds_competencias->CurrentValue = $this->ds_competencias->FormValue;
		$this->ic_periodicidadeReunioes->CurrentValue = $this->ic_periodicidadeReunioes->FormValue;
		$this->dt_basePeriodicidade->CurrentValue = $this->dt_basePeriodicidade->FormValue;
		$this->dt_basePeriodicidade->CurrentValue = ew_UnFormatDateTime($this->dt_basePeriodicidade->CurrentValue, 7);
		$this->no_localDocDiretrizes->CurrentValue = $this->no_localDocDiretrizes->FormValue;
		$this->no_localDocComunicacao->CurrentValue = $this->no_localDocComunicacao->FormValue;
		$this->no_localParecerJuridico->CurrentValue = $this->no_localParecerJuridico->FormValue;
		$this->no_localDocDesignacao->CurrentValue = $this->no_localDocDesignacao->FormValue;
		$this->ds_partesInteressadas->CurrentValue = $this->ds_partesInteressadas->FormValue;
		$this->nu_usuario->CurrentValue = $this->nu_usuario->FormValue;
		$this->ts_datahora->CurrentValue = $this->ts_datahora->FormValue;
		$this->ts_datahora->CurrentValue = ew_UnFormatDateTime($this->ts_datahora->CurrentValue, 7);
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
		$this->nu_gpComite->setDbValue($rs->fields('nu_gpComite'));
		$this->no_gpComite->setDbValue($rs->fields('no_gpComite'));
		$this->ic_tpGpOuComite->setDbValue($rs->fields('ic_tpGpOuComite'));
		$this->ds_descricao->setDbValue($rs->fields('ds_descricao'));
		$this->ds_finalidade->setDbValue($rs->fields('ds_finalidade'));
		$this->ic_natureza->setDbValue($rs->fields('ic_natureza'));
		$this->ds_competencias->setDbValue($rs->fields('ds_competencias'));
		$this->ic_periodicidadeReunioes->setDbValue($rs->fields('ic_periodicidadeReunioes'));
		$this->dt_basePeriodicidade->setDbValue($rs->fields('dt_basePeriodicidade'));
		$this->no_localDocDiretrizes->setDbValue($rs->fields('no_localDocDiretrizes'));
		$this->im_anexoDiretrizes->Upload->DbValue = $rs->fields('im_anexoDiretrizes');
		$this->no_localDocComunicacao->setDbValue($rs->fields('no_localDocComunicacao'));
		$this->im_anexoComunicacao->Upload->DbValue = $rs->fields('im_anexoComunicacao');
		$this->no_localParecerJuridico->setDbValue($rs->fields('no_localParecerJuridico'));
		$this->im_anexoParecerJuridico->Upload->DbValue = $rs->fields('im_anexoParecerJuridico');
		$this->no_localDocDesignacao->setDbValue($rs->fields('no_localDocDesignacao'));
		$this->im_anexoDesignacao->Upload->DbValue = $rs->fields('im_anexoDesignacao');
		$this->ds_partesInteressadas->setDbValue($rs->fields('ds_partesInteressadas'));
		$this->nu_usuario->setDbValue($rs->fields('nu_usuario'));
		$this->ts_datahora->setDbValue($rs->fields('ts_datahora'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_gpComite->DbValue = $row['nu_gpComite'];
		$this->no_gpComite->DbValue = $row['no_gpComite'];
		$this->ic_tpGpOuComite->DbValue = $row['ic_tpGpOuComite'];
		$this->ds_descricao->DbValue = $row['ds_descricao'];
		$this->ds_finalidade->DbValue = $row['ds_finalidade'];
		$this->ic_natureza->DbValue = $row['ic_natureza'];
		$this->ds_competencias->DbValue = $row['ds_competencias'];
		$this->ic_periodicidadeReunioes->DbValue = $row['ic_periodicidadeReunioes'];
		$this->dt_basePeriodicidade->DbValue = $row['dt_basePeriodicidade'];
		$this->no_localDocDiretrizes->DbValue = $row['no_localDocDiretrizes'];
		$this->im_anexoDiretrizes->Upload->DbValue = $row['im_anexoDiretrizes'];
		$this->no_localDocComunicacao->DbValue = $row['no_localDocComunicacao'];
		$this->im_anexoComunicacao->Upload->DbValue = $row['im_anexoComunicacao'];
		$this->no_localParecerJuridico->DbValue = $row['no_localParecerJuridico'];
		$this->im_anexoParecerJuridico->Upload->DbValue = $row['im_anexoParecerJuridico'];
		$this->no_localDocDesignacao->DbValue = $row['no_localDocDesignacao'];
		$this->im_anexoDesignacao->Upload->DbValue = $row['im_anexoDesignacao'];
		$this->ds_partesInteressadas->DbValue = $row['ds_partesInteressadas'];
		$this->nu_usuario->DbValue = $row['nu_usuario'];
		$this->ts_datahora->DbValue = $row['ts_datahora'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_gpComite")) <> "")
			$this->nu_gpComite->CurrentValue = $this->getKey("nu_gpComite"); // nu_gpComite
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
		// nu_gpComite
		// no_gpComite
		// ic_tpGpOuComite
		// ds_descricao
		// ds_finalidade
		// ic_natureza
		// ds_competencias
		// ic_periodicidadeReunioes
		// dt_basePeriodicidade
		// no_localDocDiretrizes
		// im_anexoDiretrizes
		// no_localDocComunicacao
		// im_anexoComunicacao
		// no_localParecerJuridico
		// im_anexoParecerJuridico
		// no_localDocDesignacao
		// im_anexoDesignacao
		// ds_partesInteressadas
		// nu_usuario
		// ts_datahora

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_gpComite
			$this->nu_gpComite->ViewValue = $this->nu_gpComite->CurrentValue;
			$this->nu_gpComite->ViewCustomAttributes = "";

			// no_gpComite
			$this->no_gpComite->ViewValue = $this->no_gpComite->CurrentValue;
			$this->no_gpComite->ViewCustomAttributes = "";

			// ic_tpGpOuComite
			if (strval($this->ic_tpGpOuComite->CurrentValue) <> "") {
				switch ($this->ic_tpGpOuComite->CurrentValue) {
					case $this->ic_tpGpOuComite->FldTagValue(1):
						$this->ic_tpGpOuComite->ViewValue = $this->ic_tpGpOuComite->FldTagCaption(1) <> "" ? $this->ic_tpGpOuComite->FldTagCaption(1) : $this->ic_tpGpOuComite->CurrentValue;
						break;
					case $this->ic_tpGpOuComite->FldTagValue(2):
						$this->ic_tpGpOuComite->ViewValue = $this->ic_tpGpOuComite->FldTagCaption(2) <> "" ? $this->ic_tpGpOuComite->FldTagCaption(2) : $this->ic_tpGpOuComite->CurrentValue;
						break;
					case $this->ic_tpGpOuComite->FldTagValue(3):
						$this->ic_tpGpOuComite->ViewValue = $this->ic_tpGpOuComite->FldTagCaption(3) <> "" ? $this->ic_tpGpOuComite->FldTagCaption(3) : $this->ic_tpGpOuComite->CurrentValue;
						break;
					default:
						$this->ic_tpGpOuComite->ViewValue = $this->ic_tpGpOuComite->CurrentValue;
				}
			} else {
				$this->ic_tpGpOuComite->ViewValue = NULL;
			}
			$this->ic_tpGpOuComite->ViewCustomAttributes = "";

			// ds_descricao
			$this->ds_descricao->ViewValue = $this->ds_descricao->CurrentValue;
			$this->ds_descricao->ViewCustomAttributes = "";

			// ds_finalidade
			$this->ds_finalidade->ViewValue = $this->ds_finalidade->CurrentValue;
			$this->ds_finalidade->ViewCustomAttributes = "";

			// ic_natureza
			if (strval($this->ic_natureza->CurrentValue) <> "") {
				switch ($this->ic_natureza->CurrentValue) {
					case $this->ic_natureza->FldTagValue(1):
						$this->ic_natureza->ViewValue = $this->ic_natureza->FldTagCaption(1) <> "" ? $this->ic_natureza->FldTagCaption(1) : $this->ic_natureza->CurrentValue;
						break;
					case $this->ic_natureza->FldTagValue(2):
						$this->ic_natureza->ViewValue = $this->ic_natureza->FldTagCaption(2) <> "" ? $this->ic_natureza->FldTagCaption(2) : $this->ic_natureza->CurrentValue;
						break;
					default:
						$this->ic_natureza->ViewValue = $this->ic_natureza->CurrentValue;
				}
			} else {
				$this->ic_natureza->ViewValue = NULL;
			}
			$this->ic_natureza->ViewCustomAttributes = "";

			// ds_competencias
			$this->ds_competencias->ViewValue = $this->ds_competencias->CurrentValue;
			$this->ds_competencias->ViewCustomAttributes = "";

			// ic_periodicidadeReunioes
			if (strval($this->ic_periodicidadeReunioes->CurrentValue) <> "") {
				switch ($this->ic_periodicidadeReunioes->CurrentValue) {
					case $this->ic_periodicidadeReunioes->FldTagValue(1):
						$this->ic_periodicidadeReunioes->ViewValue = $this->ic_periodicidadeReunioes->FldTagCaption(1) <> "" ? $this->ic_periodicidadeReunioes->FldTagCaption(1) : $this->ic_periodicidadeReunioes->CurrentValue;
						break;
					case $this->ic_periodicidadeReunioes->FldTagValue(2):
						$this->ic_periodicidadeReunioes->ViewValue = $this->ic_periodicidadeReunioes->FldTagCaption(2) <> "" ? $this->ic_periodicidadeReunioes->FldTagCaption(2) : $this->ic_periodicidadeReunioes->CurrentValue;
						break;
					case $this->ic_periodicidadeReunioes->FldTagValue(3):
						$this->ic_periodicidadeReunioes->ViewValue = $this->ic_periodicidadeReunioes->FldTagCaption(3) <> "" ? $this->ic_periodicidadeReunioes->FldTagCaption(3) : $this->ic_periodicidadeReunioes->CurrentValue;
						break;
					case $this->ic_periodicidadeReunioes->FldTagValue(4):
						$this->ic_periodicidadeReunioes->ViewValue = $this->ic_periodicidadeReunioes->FldTagCaption(4) <> "" ? $this->ic_periodicidadeReunioes->FldTagCaption(4) : $this->ic_periodicidadeReunioes->CurrentValue;
						break;
					case $this->ic_periodicidadeReunioes->FldTagValue(5):
						$this->ic_periodicidadeReunioes->ViewValue = $this->ic_periodicidadeReunioes->FldTagCaption(5) <> "" ? $this->ic_periodicidadeReunioes->FldTagCaption(5) : $this->ic_periodicidadeReunioes->CurrentValue;
						break;
					case $this->ic_periodicidadeReunioes->FldTagValue(6):
						$this->ic_periodicidadeReunioes->ViewValue = $this->ic_periodicidadeReunioes->FldTagCaption(6) <> "" ? $this->ic_periodicidadeReunioes->FldTagCaption(6) : $this->ic_periodicidadeReunioes->CurrentValue;
						break;
					default:
						$this->ic_periodicidadeReunioes->ViewValue = $this->ic_periodicidadeReunioes->CurrentValue;
				}
			} else {
				$this->ic_periodicidadeReunioes->ViewValue = NULL;
			}
			$this->ic_periodicidadeReunioes->ViewCustomAttributes = "";

			// dt_basePeriodicidade
			$this->dt_basePeriodicidade->ViewValue = $this->dt_basePeriodicidade->CurrentValue;
			$this->dt_basePeriodicidade->ViewValue = ew_FormatDateTime($this->dt_basePeriodicidade->ViewValue, 7);
			$this->dt_basePeriodicidade->ViewCustomAttributes = "";

			// no_localDocDiretrizes
			$this->no_localDocDiretrizes->ViewValue = $this->no_localDocDiretrizes->CurrentValue;
			$this->no_localDocDiretrizes->ViewCustomAttributes = "";

			// im_anexoDiretrizes
			$this->im_anexoDiretrizes->UploadPath = "arquivos/grupocti_diretrizes";
			if (!ew_Empty($this->im_anexoDiretrizes->Upload->DbValue)) {
				$this->im_anexoDiretrizes->ViewValue = $this->im_anexoDiretrizes->Upload->DbValue;
			} else {
				$this->im_anexoDiretrizes->ViewValue = "";
			}
			$this->im_anexoDiretrizes->ViewCustomAttributes = "";

			// no_localDocComunicacao
			$this->no_localDocComunicacao->ViewValue = $this->no_localDocComunicacao->CurrentValue;
			$this->no_localDocComunicacao->ViewCustomAttributes = "";

			// im_anexoComunicacao
			$this->im_anexoComunicacao->UploadPath = "arquivos/grupocti_comunicacao";
			if (!ew_Empty($this->im_anexoComunicacao->Upload->DbValue)) {
				$this->im_anexoComunicacao->ViewValue = $this->im_anexoComunicacao->Upload->DbValue;
			} else {
				$this->im_anexoComunicacao->ViewValue = "";
			}
			$this->im_anexoComunicacao->ViewCustomAttributes = "";

			// no_localParecerJuridico
			$this->no_localParecerJuridico->ViewValue = $this->no_localParecerJuridico->CurrentValue;
			$this->no_localParecerJuridico->ViewCustomAttributes = "";

			// im_anexoParecerJuridico
			$this->im_anexoParecerJuridico->UploadPath = "arquivos/grupocti_parjuridico";
			if (!ew_Empty($this->im_anexoParecerJuridico->Upload->DbValue)) {
				$this->im_anexoParecerJuridico->ViewValue = $this->im_anexoParecerJuridico->Upload->DbValue;
			} else {
				$this->im_anexoParecerJuridico->ViewValue = "";
			}
			$this->im_anexoParecerJuridico->ViewCustomAttributes = "";

			// no_localDocDesignacao
			$this->no_localDocDesignacao->ViewValue = $this->no_localDocDesignacao->CurrentValue;
			$this->no_localDocDesignacao->ViewCustomAttributes = "";

			// im_anexoDesignacao
			$this->im_anexoDesignacao->UploadPath = "arquivos/grupocti_designacao";
			if (!ew_Empty($this->im_anexoDesignacao->Upload->DbValue)) {
				$this->im_anexoDesignacao->ViewValue = $this->im_anexoDesignacao->Upload->DbValue;
			} else {
				$this->im_anexoDesignacao->ViewValue = "";
			}
			$this->im_anexoDesignacao->ViewCustomAttributes = "";

			// ds_partesInteressadas
			$this->ds_partesInteressadas->ViewValue = $this->ds_partesInteressadas->CurrentValue;
			$this->ds_partesInteressadas->ViewCustomAttributes = "";

			// nu_usuario
			$this->nu_usuario->ViewValue = $this->nu_usuario->CurrentValue;
			$this->nu_usuario->ViewCustomAttributes = "";

			// ts_datahora
			$this->ts_datahora->ViewValue = $this->ts_datahora->CurrentValue;
			$this->ts_datahora->ViewValue = ew_FormatDateTime($this->ts_datahora->ViewValue, 7);
			$this->ts_datahora->ViewCustomAttributes = "";

			// no_gpComite
			$this->no_gpComite->LinkCustomAttributes = "";
			$this->no_gpComite->HrefValue = "";
			$this->no_gpComite->TooltipValue = "";

			// ic_tpGpOuComite
			$this->ic_tpGpOuComite->LinkCustomAttributes = "";
			$this->ic_tpGpOuComite->HrefValue = "";
			$this->ic_tpGpOuComite->TooltipValue = "";

			// ds_descricao
			$this->ds_descricao->LinkCustomAttributes = "";
			$this->ds_descricao->HrefValue = "";
			$this->ds_descricao->TooltipValue = "";

			// ds_finalidade
			$this->ds_finalidade->LinkCustomAttributes = "";
			$this->ds_finalidade->HrefValue = "";
			$this->ds_finalidade->TooltipValue = "";

			// ic_natureza
			$this->ic_natureza->LinkCustomAttributes = "";
			$this->ic_natureza->HrefValue = "";
			$this->ic_natureza->TooltipValue = "";

			// ds_competencias
			$this->ds_competencias->LinkCustomAttributes = "";
			$this->ds_competencias->HrefValue = "";
			$this->ds_competencias->TooltipValue = "";

			// ic_periodicidadeReunioes
			$this->ic_periodicidadeReunioes->LinkCustomAttributes = "";
			$this->ic_periodicidadeReunioes->HrefValue = "";
			$this->ic_periodicidadeReunioes->TooltipValue = "";

			// dt_basePeriodicidade
			$this->dt_basePeriodicidade->LinkCustomAttributes = "";
			$this->dt_basePeriodicidade->HrefValue = "";
			$this->dt_basePeriodicidade->TooltipValue = "";

			// no_localDocDiretrizes
			$this->no_localDocDiretrizes->LinkCustomAttributes = "";
			$this->no_localDocDiretrizes->HrefValue = "";
			$this->no_localDocDiretrizes->TooltipValue = "";

			// im_anexoDiretrizes
			$this->im_anexoDiretrizes->LinkCustomAttributes = "";
			$this->im_anexoDiretrizes->HrefValue = "";
			$this->im_anexoDiretrizes->HrefValue2 = $this->im_anexoDiretrizes->UploadPath . $this->im_anexoDiretrizes->Upload->DbValue;
			$this->im_anexoDiretrizes->TooltipValue = "";

			// no_localDocComunicacao
			$this->no_localDocComunicacao->LinkCustomAttributes = "";
			$this->no_localDocComunicacao->HrefValue = "";
			$this->no_localDocComunicacao->TooltipValue = "";

			// im_anexoComunicacao
			$this->im_anexoComunicacao->LinkCustomAttributes = "";
			$this->im_anexoComunicacao->HrefValue = "";
			$this->im_anexoComunicacao->HrefValue2 = $this->im_anexoComunicacao->UploadPath . $this->im_anexoComunicacao->Upload->DbValue;
			$this->im_anexoComunicacao->TooltipValue = "";

			// no_localParecerJuridico
			$this->no_localParecerJuridico->LinkCustomAttributes = "";
			$this->no_localParecerJuridico->HrefValue = "";
			$this->no_localParecerJuridico->TooltipValue = "";

			// im_anexoParecerJuridico
			$this->im_anexoParecerJuridico->LinkCustomAttributes = "";
			$this->im_anexoParecerJuridico->HrefValue = "";
			$this->im_anexoParecerJuridico->HrefValue2 = $this->im_anexoParecerJuridico->UploadPath . $this->im_anexoParecerJuridico->Upload->DbValue;
			$this->im_anexoParecerJuridico->TooltipValue = "";

			// no_localDocDesignacao
			$this->no_localDocDesignacao->LinkCustomAttributes = "";
			$this->no_localDocDesignacao->HrefValue = "";
			$this->no_localDocDesignacao->TooltipValue = "";

			// im_anexoDesignacao
			$this->im_anexoDesignacao->LinkCustomAttributes = "";
			$this->im_anexoDesignacao->HrefValue = "";
			$this->im_anexoDesignacao->HrefValue2 = $this->im_anexoDesignacao->UploadPath . $this->im_anexoDesignacao->Upload->DbValue;
			$this->im_anexoDesignacao->TooltipValue = "";

			// ds_partesInteressadas
			$this->ds_partesInteressadas->LinkCustomAttributes = "";
			$this->ds_partesInteressadas->HrefValue = "";
			$this->ds_partesInteressadas->TooltipValue = "";

			// nu_usuario
			$this->nu_usuario->LinkCustomAttributes = "";
			$this->nu_usuario->HrefValue = "";
			$this->nu_usuario->TooltipValue = "";

			// ts_datahora
			$this->ts_datahora->LinkCustomAttributes = "";
			$this->ts_datahora->HrefValue = "";
			$this->ts_datahora->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// no_gpComite
			$this->no_gpComite->EditCustomAttributes = "";
			$this->no_gpComite->EditValue = ew_HtmlEncode($this->no_gpComite->CurrentValue);
			$this->no_gpComite->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_gpComite->FldCaption()));

			// ic_tpGpOuComite
			$this->ic_tpGpOuComite->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_tpGpOuComite->FldTagValue(1), $this->ic_tpGpOuComite->FldTagCaption(1) <> "" ? $this->ic_tpGpOuComite->FldTagCaption(1) : $this->ic_tpGpOuComite->FldTagValue(1));
			$arwrk[] = array($this->ic_tpGpOuComite->FldTagValue(2), $this->ic_tpGpOuComite->FldTagCaption(2) <> "" ? $this->ic_tpGpOuComite->FldTagCaption(2) : $this->ic_tpGpOuComite->FldTagValue(2));
			$arwrk[] = array($this->ic_tpGpOuComite->FldTagValue(3), $this->ic_tpGpOuComite->FldTagCaption(3) <> "" ? $this->ic_tpGpOuComite->FldTagCaption(3) : $this->ic_tpGpOuComite->FldTagValue(3));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_tpGpOuComite->EditValue = $arwrk;

			// ds_descricao
			$this->ds_descricao->EditCustomAttributes = "";
			$this->ds_descricao->EditValue = $this->ds_descricao->CurrentValue;
			$this->ds_descricao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_descricao->FldCaption()));

			// ds_finalidade
			$this->ds_finalidade->EditCustomAttributes = "";
			$this->ds_finalidade->EditValue = $this->ds_finalidade->CurrentValue;
			$this->ds_finalidade->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_finalidade->FldCaption()));

			// ic_natureza
			$this->ic_natureza->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_natureza->FldTagValue(1), $this->ic_natureza->FldTagCaption(1) <> "" ? $this->ic_natureza->FldTagCaption(1) : $this->ic_natureza->FldTagValue(1));
			$arwrk[] = array($this->ic_natureza->FldTagValue(2), $this->ic_natureza->FldTagCaption(2) <> "" ? $this->ic_natureza->FldTagCaption(2) : $this->ic_natureza->FldTagValue(2));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_natureza->EditValue = $arwrk;

			// ds_competencias
			$this->ds_competencias->EditCustomAttributes = "";
			$this->ds_competencias->EditValue = $this->ds_competencias->CurrentValue;
			$this->ds_competencias->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_competencias->FldCaption()));

			// ic_periodicidadeReunioes
			$this->ic_periodicidadeReunioes->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_periodicidadeReunioes->FldTagValue(1), $this->ic_periodicidadeReunioes->FldTagCaption(1) <> "" ? $this->ic_periodicidadeReunioes->FldTagCaption(1) : $this->ic_periodicidadeReunioes->FldTagValue(1));
			$arwrk[] = array($this->ic_periodicidadeReunioes->FldTagValue(2), $this->ic_periodicidadeReunioes->FldTagCaption(2) <> "" ? $this->ic_periodicidadeReunioes->FldTagCaption(2) : $this->ic_periodicidadeReunioes->FldTagValue(2));
			$arwrk[] = array($this->ic_periodicidadeReunioes->FldTagValue(3), $this->ic_periodicidadeReunioes->FldTagCaption(3) <> "" ? $this->ic_periodicidadeReunioes->FldTagCaption(3) : $this->ic_periodicidadeReunioes->FldTagValue(3));
			$arwrk[] = array($this->ic_periodicidadeReunioes->FldTagValue(4), $this->ic_periodicidadeReunioes->FldTagCaption(4) <> "" ? $this->ic_periodicidadeReunioes->FldTagCaption(4) : $this->ic_periodicidadeReunioes->FldTagValue(4));
			$arwrk[] = array($this->ic_periodicidadeReunioes->FldTagValue(5), $this->ic_periodicidadeReunioes->FldTagCaption(5) <> "" ? $this->ic_periodicidadeReunioes->FldTagCaption(5) : $this->ic_periodicidadeReunioes->FldTagValue(5));
			$arwrk[] = array($this->ic_periodicidadeReunioes->FldTagValue(6), $this->ic_periodicidadeReunioes->FldTagCaption(6) <> "" ? $this->ic_periodicidadeReunioes->FldTagCaption(6) : $this->ic_periodicidadeReunioes->FldTagValue(6));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_periodicidadeReunioes->EditValue = $arwrk;

			// dt_basePeriodicidade
			$this->dt_basePeriodicidade->EditCustomAttributes = "";
			$this->dt_basePeriodicidade->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->dt_basePeriodicidade->CurrentValue, 7));
			$this->dt_basePeriodicidade->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dt_basePeriodicidade->FldCaption()));

			// no_localDocDiretrizes
			$this->no_localDocDiretrizes->EditCustomAttributes = "";
			$this->no_localDocDiretrizes->EditValue = ew_HtmlEncode($this->no_localDocDiretrizes->CurrentValue);
			$this->no_localDocDiretrizes->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_localDocDiretrizes->FldCaption()));

			// im_anexoDiretrizes
			$this->im_anexoDiretrizes->EditCustomAttributes = "";
			$this->im_anexoDiretrizes->UploadPath = "arquivos/grupocti_diretrizes";
			if (!ew_Empty($this->im_anexoDiretrizes->Upload->DbValue)) {
				$this->im_anexoDiretrizes->EditValue = $this->im_anexoDiretrizes->Upload->DbValue;
			} else {
				$this->im_anexoDiretrizes->EditValue = "";
			}
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->im_anexoDiretrizes);

			// no_localDocComunicacao
			$this->no_localDocComunicacao->EditCustomAttributes = "";
			$this->no_localDocComunicacao->EditValue = ew_HtmlEncode($this->no_localDocComunicacao->CurrentValue);
			$this->no_localDocComunicacao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_localDocComunicacao->FldCaption()));

			// im_anexoComunicacao
			$this->im_anexoComunicacao->EditCustomAttributes = "";
			$this->im_anexoComunicacao->UploadPath = "arquivos/grupocti_comunicacao";
			if (!ew_Empty($this->im_anexoComunicacao->Upload->DbValue)) {
				$this->im_anexoComunicacao->EditValue = $this->im_anexoComunicacao->Upload->DbValue;
			} else {
				$this->im_anexoComunicacao->EditValue = "";
			}
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->im_anexoComunicacao);

			// no_localParecerJuridico
			$this->no_localParecerJuridico->EditCustomAttributes = "";
			$this->no_localParecerJuridico->EditValue = ew_HtmlEncode($this->no_localParecerJuridico->CurrentValue);
			$this->no_localParecerJuridico->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_localParecerJuridico->FldCaption()));

			// im_anexoParecerJuridico
			$this->im_anexoParecerJuridico->EditCustomAttributes = "";
			$this->im_anexoParecerJuridico->UploadPath = "arquivos/grupocti_parjuridico";
			if (!ew_Empty($this->im_anexoParecerJuridico->Upload->DbValue)) {
				$this->im_anexoParecerJuridico->EditValue = $this->im_anexoParecerJuridico->Upload->DbValue;
			} else {
				$this->im_anexoParecerJuridico->EditValue = "";
			}
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->im_anexoParecerJuridico);

			// no_localDocDesignacao
			$this->no_localDocDesignacao->EditCustomAttributes = "";
			$this->no_localDocDesignacao->EditValue = ew_HtmlEncode($this->no_localDocDesignacao->CurrentValue);
			$this->no_localDocDesignacao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_localDocDesignacao->FldCaption()));

			// im_anexoDesignacao
			$this->im_anexoDesignacao->EditCustomAttributes = "";
			$this->im_anexoDesignacao->UploadPath = "arquivos/grupocti_designacao";
			if (!ew_Empty($this->im_anexoDesignacao->Upload->DbValue)) {
				$this->im_anexoDesignacao->EditValue = $this->im_anexoDesignacao->Upload->DbValue;
			} else {
				$this->im_anexoDesignacao->EditValue = "";
			}
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->im_anexoDesignacao);

			// ds_partesInteressadas
			$this->ds_partesInteressadas->EditCustomAttributes = "";
			$this->ds_partesInteressadas->EditValue = $this->ds_partesInteressadas->CurrentValue;
			$this->ds_partesInteressadas->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_partesInteressadas->FldCaption()));

			// nu_usuario
			// ts_datahora
			// Edit refer script
			// no_gpComite

			$this->no_gpComite->HrefValue = "";

			// ic_tpGpOuComite
			$this->ic_tpGpOuComite->HrefValue = "";

			// ds_descricao
			$this->ds_descricao->HrefValue = "";

			// ds_finalidade
			$this->ds_finalidade->HrefValue = "";

			// ic_natureza
			$this->ic_natureza->HrefValue = "";

			// ds_competencias
			$this->ds_competencias->HrefValue = "";

			// ic_periodicidadeReunioes
			$this->ic_periodicidadeReunioes->HrefValue = "";

			// dt_basePeriodicidade
			$this->dt_basePeriodicidade->HrefValue = "";

			// no_localDocDiretrizes
			$this->no_localDocDiretrizes->HrefValue = "";

			// im_anexoDiretrizes
			$this->im_anexoDiretrizes->HrefValue = "";
			$this->im_anexoDiretrizes->HrefValue2 = $this->im_anexoDiretrizes->UploadPath . $this->im_anexoDiretrizes->Upload->DbValue;

			// no_localDocComunicacao
			$this->no_localDocComunicacao->HrefValue = "";

			// im_anexoComunicacao
			$this->im_anexoComunicacao->HrefValue = "";
			$this->im_anexoComunicacao->HrefValue2 = $this->im_anexoComunicacao->UploadPath . $this->im_anexoComunicacao->Upload->DbValue;

			// no_localParecerJuridico
			$this->no_localParecerJuridico->HrefValue = "";

			// im_anexoParecerJuridico
			$this->im_anexoParecerJuridico->HrefValue = "";
			$this->im_anexoParecerJuridico->HrefValue2 = $this->im_anexoParecerJuridico->UploadPath . $this->im_anexoParecerJuridico->Upload->DbValue;

			// no_localDocDesignacao
			$this->no_localDocDesignacao->HrefValue = "";

			// im_anexoDesignacao
			$this->im_anexoDesignacao->HrefValue = "";
			$this->im_anexoDesignacao->HrefValue2 = $this->im_anexoDesignacao->UploadPath . $this->im_anexoDesignacao->Upload->DbValue;

			// ds_partesInteressadas
			$this->ds_partesInteressadas->HrefValue = "";

			// nu_usuario
			$this->nu_usuario->HrefValue = "";

			// ts_datahora
			$this->ts_datahora->HrefValue = "";
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
		if (!$this->no_gpComite->FldIsDetailKey && !is_null($this->no_gpComite->FormValue) && $this->no_gpComite->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_gpComite->FldCaption());
		}
		if (!$this->ic_tpGpOuComite->FldIsDetailKey && !is_null($this->ic_tpGpOuComite->FormValue) && $this->ic_tpGpOuComite->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_tpGpOuComite->FldCaption());
		}
		if (!$this->ic_periodicidadeReunioes->FldIsDetailKey && !is_null($this->ic_periodicidadeReunioes->FormValue) && $this->ic_periodicidadeReunioes->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_periodicidadeReunioes->FldCaption());
		}
		if (!ew_CheckEuroDate($this->dt_basePeriodicidade->FormValue)) {
			ew_AddMessage($gsFormError, $this->dt_basePeriodicidade->FldErrMsg());
		}
		if (!$this->ds_partesInteressadas->FldIsDetailKey && !is_null($this->ds_partesInteressadas->FormValue) && $this->ds_partesInteressadas->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ds_partesInteressadas->FldCaption());
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
			$this->im_anexoDiretrizes->OldUploadPath = "arquivos/grupocti_diretrizes";
			$this->im_anexoDiretrizes->UploadPath = $this->im_anexoDiretrizes->OldUploadPath;
			$this->im_anexoComunicacao->OldUploadPath = "arquivos/grupocti_comunicacao";
			$this->im_anexoComunicacao->UploadPath = $this->im_anexoComunicacao->OldUploadPath;
			$this->im_anexoParecerJuridico->OldUploadPath = "arquivos/grupocti_parjuridico";
			$this->im_anexoParecerJuridico->UploadPath = $this->im_anexoParecerJuridico->OldUploadPath;
			$this->im_anexoDesignacao->OldUploadPath = "arquivos/grupocti_designacao";
			$this->im_anexoDesignacao->UploadPath = $this->im_anexoDesignacao->OldUploadPath;
		}
		$rsnew = array();

		// no_gpComite
		$this->no_gpComite->SetDbValueDef($rsnew, $this->no_gpComite->CurrentValue, "", FALSE);

		// ic_tpGpOuComite
		$this->ic_tpGpOuComite->SetDbValueDef($rsnew, $this->ic_tpGpOuComite->CurrentValue, "", FALSE);

		// ds_descricao
		$this->ds_descricao->SetDbValueDef($rsnew, $this->ds_descricao->CurrentValue, NULL, FALSE);

		// ds_finalidade
		$this->ds_finalidade->SetDbValueDef($rsnew, $this->ds_finalidade->CurrentValue, NULL, FALSE);

		// ic_natureza
		$this->ic_natureza->SetDbValueDef($rsnew, $this->ic_natureza->CurrentValue, NULL, FALSE);

		// ds_competencias
		$this->ds_competencias->SetDbValueDef($rsnew, $this->ds_competencias->CurrentValue, NULL, FALSE);

		// ic_periodicidadeReunioes
		$this->ic_periodicidadeReunioes->SetDbValueDef($rsnew, $this->ic_periodicidadeReunioes->CurrentValue, NULL, FALSE);

		// dt_basePeriodicidade
		$this->dt_basePeriodicidade->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->dt_basePeriodicidade->CurrentValue, 7), NULL, FALSE);

		// no_localDocDiretrizes
		$this->no_localDocDiretrizes->SetDbValueDef($rsnew, $this->no_localDocDiretrizes->CurrentValue, NULL, FALSE);

		// im_anexoDiretrizes
		if (!$this->im_anexoDiretrizes->Upload->KeepFile) {
			if ($this->im_anexoDiretrizes->Upload->FileName == "") {
				$rsnew['im_anexoDiretrizes'] = NULL;
			} else {
				$rsnew['im_anexoDiretrizes'] = $this->im_anexoDiretrizes->Upload->FileName;
			}
		}

		// no_localDocComunicacao
		$this->no_localDocComunicacao->SetDbValueDef($rsnew, $this->no_localDocComunicacao->CurrentValue, NULL, FALSE);

		// im_anexoComunicacao
		if (!$this->im_anexoComunicacao->Upload->KeepFile) {
			if ($this->im_anexoComunicacao->Upload->FileName == "") {
				$rsnew['im_anexoComunicacao'] = NULL;
			} else {
				$rsnew['im_anexoComunicacao'] = $this->im_anexoComunicacao->Upload->FileName;
			}
		}

		// no_localParecerJuridico
		$this->no_localParecerJuridico->SetDbValueDef($rsnew, $this->no_localParecerJuridico->CurrentValue, NULL, FALSE);

		// im_anexoParecerJuridico
		if (!$this->im_anexoParecerJuridico->Upload->KeepFile) {
			if ($this->im_anexoParecerJuridico->Upload->FileName == "") {
				$rsnew['im_anexoParecerJuridico'] = NULL;
			} else {
				$rsnew['im_anexoParecerJuridico'] = $this->im_anexoParecerJuridico->Upload->FileName;
			}
		}

		// no_localDocDesignacao
		$this->no_localDocDesignacao->SetDbValueDef($rsnew, $this->no_localDocDesignacao->CurrentValue, NULL, FALSE);

		// im_anexoDesignacao
		if (!$this->im_anexoDesignacao->Upload->KeepFile) {
			if ($this->im_anexoDesignacao->Upload->FileName == "") {
				$rsnew['im_anexoDesignacao'] = NULL;
			} else {
				$rsnew['im_anexoDesignacao'] = $this->im_anexoDesignacao->Upload->FileName;
			}
		}

		// ds_partesInteressadas
		$this->ds_partesInteressadas->SetDbValueDef($rsnew, $this->ds_partesInteressadas->CurrentValue, "", FALSE);

		// nu_usuario
		$this->nu_usuario->SetDbValueDef($rsnew, CurrentUserID(), NULL);
		$rsnew['nu_usuario'] = &$this->nu_usuario->DbValue;

		// ts_datahora
		$this->ts_datahora->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
		$rsnew['ts_datahora'] = &$this->ts_datahora->DbValue;
		if (!$this->im_anexoDiretrizes->Upload->KeepFile) {
			$this->im_anexoDiretrizes->UploadPath = "arquivos/grupocti_diretrizes";
			$OldFiles = explode(",", $this->im_anexoDiretrizes->Upload->DbValue);
			if (!ew_Empty($this->im_anexoDiretrizes->Upload->FileName)) {
				$NewFiles = explode(",", $this->im_anexoDiretrizes->Upload->FileName);
				$FileCount = count($NewFiles);
				for ($i = 0; $i < $FileCount; $i++) {
					$fldvar = ($this->im_anexoDiretrizes->Upload->Index < 0) ? $this->im_anexoDiretrizes->FldVar : substr($this->im_anexoDiretrizes->FldVar, 0, 1) . $this->im_anexoDiretrizes->Upload->Index . substr($this->im_anexoDiretrizes->FldVar, 1);
					if ($NewFiles[$i] <> "") {
						$file = ew_UploadTempPath($fldvar) . EW_PATH_DELIMITER . $NewFiles[$i];
						if (file_exists($file)) {
							if (!in_array($NewFiles[$i], $OldFiles)) {
								$NewFiles[$i] = ew_UploadFileNameEx($this->im_anexoDiretrizes->UploadPath, $NewFiles[$i]); // Get new file name
								$file1 = ew_UploadTempPath($fldvar) . EW_PATH_DELIMITER . $NewFiles[$i];
								if ($file1 <> $file) // Rename temp file
									rename($file, $file1);
							}
						}
					}
				}
				$this->im_anexoDiretrizes->Upload->FileName = implode(",", $NewFiles);
				$rsnew['im_anexoDiretrizes'] = $this->im_anexoDiretrizes->Upload->FileName;
			} else {
				$NewFiles = array();
			}
		}
		if (!$this->im_anexoComunicacao->Upload->KeepFile) {
			$this->im_anexoComunicacao->UploadPath = "arquivos/grupocti_comunicacao";
			$OldFiles = explode(",", $this->im_anexoComunicacao->Upload->DbValue);
			if (!ew_Empty($this->im_anexoComunicacao->Upload->FileName)) {
				$NewFiles = explode(",", $this->im_anexoComunicacao->Upload->FileName);
				$FileCount = count($NewFiles);
				for ($i = 0; $i < $FileCount; $i++) {
					$fldvar = ($this->im_anexoComunicacao->Upload->Index < 0) ? $this->im_anexoComunicacao->FldVar : substr($this->im_anexoComunicacao->FldVar, 0, 1) . $this->im_anexoComunicacao->Upload->Index . substr($this->im_anexoComunicacao->FldVar, 1);
					if ($NewFiles[$i] <> "") {
						$file = ew_UploadTempPath($fldvar) . EW_PATH_DELIMITER . $NewFiles[$i];
						if (file_exists($file)) {
							if (!in_array($NewFiles[$i], $OldFiles)) {
								$NewFiles[$i] = ew_UploadFileNameEx($this->im_anexoComunicacao->UploadPath, $NewFiles[$i]); // Get new file name
								$file1 = ew_UploadTempPath($fldvar) . EW_PATH_DELIMITER . $NewFiles[$i];
								if ($file1 <> $file) // Rename temp file
									rename($file, $file1);
							}
						}
					}
				}
				$this->im_anexoComunicacao->Upload->FileName = implode(",", $NewFiles);
				$rsnew['im_anexoComunicacao'] = $this->im_anexoComunicacao->Upload->FileName;
			} else {
				$NewFiles = array();
			}
		}
		if (!$this->im_anexoParecerJuridico->Upload->KeepFile) {
			$this->im_anexoParecerJuridico->UploadPath = "arquivos/grupocti_parjuridico";
			$OldFiles = explode(",", $this->im_anexoParecerJuridico->Upload->DbValue);
			if (!ew_Empty($this->im_anexoParecerJuridico->Upload->FileName)) {
				$NewFiles = explode(",", $this->im_anexoParecerJuridico->Upload->FileName);
				$FileCount = count($NewFiles);
				for ($i = 0; $i < $FileCount; $i++) {
					$fldvar = ($this->im_anexoParecerJuridico->Upload->Index < 0) ? $this->im_anexoParecerJuridico->FldVar : substr($this->im_anexoParecerJuridico->FldVar, 0, 1) . $this->im_anexoParecerJuridico->Upload->Index . substr($this->im_anexoParecerJuridico->FldVar, 1);
					if ($NewFiles[$i] <> "") {
						$file = ew_UploadTempPath($fldvar) . EW_PATH_DELIMITER . $NewFiles[$i];
						if (file_exists($file)) {
							if (!in_array($NewFiles[$i], $OldFiles)) {
								$NewFiles[$i] = ew_UploadFileNameEx($this->im_anexoParecerJuridico->UploadPath, $NewFiles[$i]); // Get new file name
								$file1 = ew_UploadTempPath($fldvar) . EW_PATH_DELIMITER . $NewFiles[$i];
								if ($file1 <> $file) // Rename temp file
									rename($file, $file1);
							}
						}
					}
				}
				$this->im_anexoParecerJuridico->Upload->FileName = implode(",", $NewFiles);
				$rsnew['im_anexoParecerJuridico'] = $this->im_anexoParecerJuridico->Upload->FileName;
			} else {
				$NewFiles = array();
			}
		}
		if (!$this->im_anexoDesignacao->Upload->KeepFile) {
			$this->im_anexoDesignacao->UploadPath = "arquivos/grupocti_designacao";
			$OldFiles = explode(",", $this->im_anexoDesignacao->Upload->DbValue);
			if (!ew_Empty($this->im_anexoDesignacao->Upload->FileName)) {
				$NewFiles = explode(",", $this->im_anexoDesignacao->Upload->FileName);
				$FileCount = count($NewFiles);
				for ($i = 0; $i < $FileCount; $i++) {
					$fldvar = ($this->im_anexoDesignacao->Upload->Index < 0) ? $this->im_anexoDesignacao->FldVar : substr($this->im_anexoDesignacao->FldVar, 0, 1) . $this->im_anexoDesignacao->Upload->Index . substr($this->im_anexoDesignacao->FldVar, 1);
					if ($NewFiles[$i] <> "") {
						$file = ew_UploadTempPath($fldvar) . EW_PATH_DELIMITER . $NewFiles[$i];
						if (file_exists($file)) {
							if (!in_array($NewFiles[$i], $OldFiles)) {
								$NewFiles[$i] = ew_UploadFileNameEx($this->im_anexoDesignacao->UploadPath, $NewFiles[$i]); // Get new file name
								$file1 = ew_UploadTempPath($fldvar) . EW_PATH_DELIMITER . $NewFiles[$i];
								if ($file1 <> $file) // Rename temp file
									rename($file, $file1);
							}
						}
					}
				}
				$this->im_anexoDesignacao->Upload->FileName = implode(",", $NewFiles);
				$rsnew['im_anexoDesignacao'] = $this->im_anexoDesignacao->Upload->FileName;
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
				if (!$this->im_anexoDiretrizes->Upload->KeepFile) {
					$OldFiles = explode(",", $this->im_anexoDiretrizes->Upload->DbValue);
					if (!ew_Empty($this->im_anexoDiretrizes->Upload->FileName)) {
						$NewFiles = explode(",", $this->im_anexoDiretrizes->Upload->FileName);
						$NewFiles2 = explode(",", $rsnew['im_anexoDiretrizes']);
						$FileCount = count($NewFiles);
						for ($i = 0; $i < $FileCount; $i++) {
							$fldvar = ($this->im_anexoDiretrizes->Upload->Index < 0) ? $this->im_anexoDiretrizes->FldVar : substr($this->im_anexoDiretrizes->FldVar, 0, 1) . $this->im_anexoDiretrizes->Upload->Index . substr($this->im_anexoDiretrizes->FldVar, 1);
							if ($NewFiles[$i] <> "") {
								$file = ew_UploadTempPath($fldvar) . EW_PATH_DELIMITER . $NewFiles[$i];
								if (file_exists($file)) {
									$this->im_anexoDiretrizes->Upload->Value = file_get_contents($file);
									$this->im_anexoDiretrizes->Upload->SaveToFile($this->im_anexoDiretrizes->UploadPath, (@$NewFiles2[$i] <> "") ? $NewFiles2[$i] : $NewFiles[$i], TRUE); // Just replace
								}
							}
						}
					} else {
						$NewFiles = array();
					}
					$FileCount = count($OldFiles);
					for ($i = 0; $i < $FileCount; $i++) {
						if ($OldFiles[$i] <> "" && !in_array($OldFiles[$i], $NewFiles))
							@unlink(ew_UploadPathEx(TRUE, $this->im_anexoDiretrizes->OldUploadPath) . $OldFiles[$i]);
					}
				}
				if (!$this->im_anexoComunicacao->Upload->KeepFile) {
					$OldFiles = explode(",", $this->im_anexoComunicacao->Upload->DbValue);
					if (!ew_Empty($this->im_anexoComunicacao->Upload->FileName)) {
						$NewFiles = explode(",", $this->im_anexoComunicacao->Upload->FileName);
						$NewFiles2 = explode(",", $rsnew['im_anexoComunicacao']);
						$FileCount = count($NewFiles);
						for ($i = 0; $i < $FileCount; $i++) {
							$fldvar = ($this->im_anexoComunicacao->Upload->Index < 0) ? $this->im_anexoComunicacao->FldVar : substr($this->im_anexoComunicacao->FldVar, 0, 1) . $this->im_anexoComunicacao->Upload->Index . substr($this->im_anexoComunicacao->FldVar, 1);
							if ($NewFiles[$i] <> "") {
								$file = ew_UploadTempPath($fldvar) . EW_PATH_DELIMITER . $NewFiles[$i];
								if (file_exists($file)) {
									$this->im_anexoComunicacao->Upload->Value = file_get_contents($file);
									$this->im_anexoComunicacao->Upload->SaveToFile($this->im_anexoComunicacao->UploadPath, (@$NewFiles2[$i] <> "") ? $NewFiles2[$i] : $NewFiles[$i], TRUE); // Just replace
								}
							}
						}
					} else {
						$NewFiles = array();
					}
					$FileCount = count($OldFiles);
					for ($i = 0; $i < $FileCount; $i++) {
						if ($OldFiles[$i] <> "" && !in_array($OldFiles[$i], $NewFiles))
							@unlink(ew_UploadPathEx(TRUE, $this->im_anexoComunicacao->OldUploadPath) . $OldFiles[$i]);
					}
				}
				if (!$this->im_anexoParecerJuridico->Upload->KeepFile) {
					$OldFiles = explode(",", $this->im_anexoParecerJuridico->Upload->DbValue);
					if (!ew_Empty($this->im_anexoParecerJuridico->Upload->FileName)) {
						$NewFiles = explode(",", $this->im_anexoParecerJuridico->Upload->FileName);
						$NewFiles2 = explode(",", $rsnew['im_anexoParecerJuridico']);
						$FileCount = count($NewFiles);
						for ($i = 0; $i < $FileCount; $i++) {
							$fldvar = ($this->im_anexoParecerJuridico->Upload->Index < 0) ? $this->im_anexoParecerJuridico->FldVar : substr($this->im_anexoParecerJuridico->FldVar, 0, 1) . $this->im_anexoParecerJuridico->Upload->Index . substr($this->im_anexoParecerJuridico->FldVar, 1);
							if ($NewFiles[$i] <> "") {
								$file = ew_UploadTempPath($fldvar) . EW_PATH_DELIMITER . $NewFiles[$i];
								if (file_exists($file)) {
									$this->im_anexoParecerJuridico->Upload->Value = file_get_contents($file);
									$this->im_anexoParecerJuridico->Upload->SaveToFile($this->im_anexoParecerJuridico->UploadPath, (@$NewFiles2[$i] <> "") ? $NewFiles2[$i] : $NewFiles[$i], TRUE); // Just replace
								}
							}
						}
					} else {
						$NewFiles = array();
					}
					$FileCount = count($OldFiles);
					for ($i = 0; $i < $FileCount; $i++) {
						if ($OldFiles[$i] <> "" && !in_array($OldFiles[$i], $NewFiles))
							@unlink(ew_UploadPathEx(TRUE, $this->im_anexoParecerJuridico->OldUploadPath) . $OldFiles[$i]);
					}
				}
				if (!$this->im_anexoDesignacao->Upload->KeepFile) {
					$OldFiles = explode(",", $this->im_anexoDesignacao->Upload->DbValue);
					if (!ew_Empty($this->im_anexoDesignacao->Upload->FileName)) {
						$NewFiles = explode(",", $this->im_anexoDesignacao->Upload->FileName);
						$NewFiles2 = explode(",", $rsnew['im_anexoDesignacao']);
						$FileCount = count($NewFiles);
						for ($i = 0; $i < $FileCount; $i++) {
							$fldvar = ($this->im_anexoDesignacao->Upload->Index < 0) ? $this->im_anexoDesignacao->FldVar : substr($this->im_anexoDesignacao->FldVar, 0, 1) . $this->im_anexoDesignacao->Upload->Index . substr($this->im_anexoDesignacao->FldVar, 1);
							if ($NewFiles[$i] <> "") {
								$file = ew_UploadTempPath($fldvar) . EW_PATH_DELIMITER . $NewFiles[$i];
								if (file_exists($file)) {
									$this->im_anexoDesignacao->Upload->Value = file_get_contents($file);
									$this->im_anexoDesignacao->Upload->SaveToFile($this->im_anexoDesignacao->UploadPath, (@$NewFiles2[$i] <> "") ? $NewFiles2[$i] : $NewFiles[$i], TRUE); // Just replace
								}
							}
						}
					} else {
						$NewFiles = array();
					}
					$FileCount = count($OldFiles);
					for ($i = 0; $i < $FileCount; $i++) {
						if ($OldFiles[$i] <> "" && !in_array($OldFiles[$i], $NewFiles))
							@unlink(ew_UploadPathEx(TRUE, $this->im_anexoDesignacao->OldUploadPath) . $OldFiles[$i]);
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
			$this->nu_gpComite->setDbValue($conn->Insert_ID());
			$rsnew['nu_gpComite'] = $this->nu_gpComite->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}

		// im_anexoDiretrizes
		ew_CleanUploadTempPath($this->im_anexoDiretrizes, $this->im_anexoDiretrizes->Upload->Index);

		// im_anexoComunicacao
		ew_CleanUploadTempPath($this->im_anexoComunicacao, $this->im_anexoComunicacao->Upload->Index);

		// im_anexoParecerJuridico
		ew_CleanUploadTempPath($this->im_anexoParecerJuridico, $this->im_anexoParecerJuridico->Upload->Index);

		// im_anexoDesignacao
		ew_CleanUploadTempPath($this->im_anexoDesignacao, $this->im_anexoDesignacao->Upload->Index);
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "gpcomitelist.php", $this->TableVar);
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
if (!isset($gpcomite_add)) $gpcomite_add = new cgpcomite_add();

// Page init
$gpcomite_add->Page_Init();

// Page main
$gpcomite_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$gpcomite_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var gpcomite_add = new ew_Page("gpcomite_add");
gpcomite_add.PageID = "add"; // Page ID
var EW_PAGE_ID = gpcomite_add.PageID; // For backward compatibility

// Form object
var fgpcomiteadd = new ew_Form("fgpcomiteadd");

// Validate form
fgpcomiteadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_no_gpComite");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($gpcomite->no_gpComite->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_tpGpOuComite");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($gpcomite->ic_tpGpOuComite->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_periodicidadeReunioes");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($gpcomite->ic_periodicidadeReunioes->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dt_basePeriodicidade");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($gpcomite->dt_basePeriodicidade->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ds_partesInteressadas");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($gpcomite->ds_partesInteressadas->FldCaption()) ?>");

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
fgpcomiteadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgpcomiteadd.ValidateRequired = true;
<?php } else { ?>
fgpcomiteadd.ValidateRequired = false; 
<?php } ?>

// Multi-Page properties
fgpcomiteadd.MultiPage = new ew_MultiPage("fgpcomiteadd",
	[["x_no_gpComite",1],["x_ic_tpGpOuComite",1],["x_ds_descricao",1],["x_ds_finalidade",1],["x_ic_natureza",1],["x_ds_competencias",1],["x_ic_periodicidadeReunioes",2],["x_dt_basePeriodicidade",2],["x_no_localDocDiretrizes",3],["x_im_anexoDiretrizes",3],["x_no_localDocComunicacao",3],["x_im_anexoComunicacao",3],["x_no_localParecerJuridico",3],["x_im_anexoParecerJuridico",3],["x_no_localDocDesignacao",3],["x_im_anexoDesignacao",3],["x_ds_partesInteressadas",1]]
);

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $gpcomite_add->ShowPageHeader(); ?>
<?php
$gpcomite_add->ShowMessage();
?>
<form name="fgpcomiteadd" id="fgpcomiteadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="gpcomite">
<input type="hidden" name="a_add" id="a_add" value="A">
<table class="ewStdTable"><tbody><tr><td>
<div class="tabbable" id="gpcomite_add">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_gpcomite1" data-toggle="tab"><?php echo $gpcomite->PageCaption(1) ?></a></li>
		<li><a href="#tab_gpcomite2" data-toggle="tab"><?php echo $gpcomite->PageCaption(2) ?></a></li>
		<li><a href="#tab_gpcomite3" data-toggle="tab"><?php echo $gpcomite->PageCaption(3) ?></a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab_gpcomite1">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_gpcomiteadd1" class="table table-bordered table-striped">
<?php if ($gpcomite->no_gpComite->Visible) { // no_gpComite ?>
	<tr id="r_no_gpComite">
		<td><span id="elh_gpcomite_no_gpComite"><?php echo $gpcomite->no_gpComite->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $gpcomite->no_gpComite->CellAttributes() ?>>
<span id="el_gpcomite_no_gpComite" class="control-group">
<input type="text" data-field="x_no_gpComite" name="x_no_gpComite" id="x_no_gpComite" size="30" maxlength="50" placeholder="<?php echo $gpcomite->no_gpComite->PlaceHolder ?>" value="<?php echo $gpcomite->no_gpComite->EditValue ?>"<?php echo $gpcomite->no_gpComite->EditAttributes() ?>>
</span>
<?php echo $gpcomite->no_gpComite->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpcomite->ic_tpGpOuComite->Visible) { // ic_tpGpOuComite ?>
	<tr id="r_ic_tpGpOuComite">
		<td><span id="elh_gpcomite_ic_tpGpOuComite"><?php echo $gpcomite->ic_tpGpOuComite->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $gpcomite->ic_tpGpOuComite->CellAttributes() ?>>
<span id="el_gpcomite_ic_tpGpOuComite" class="control-group">
<select data-field="x_ic_tpGpOuComite" id="x_ic_tpGpOuComite" name="x_ic_tpGpOuComite"<?php echo $gpcomite->ic_tpGpOuComite->EditAttributes() ?>>
<?php
if (is_array($gpcomite->ic_tpGpOuComite->EditValue)) {
	$arwrk = $gpcomite->ic_tpGpOuComite->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($gpcomite->ic_tpGpOuComite->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $gpcomite->ic_tpGpOuComite->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpcomite->ds_descricao->Visible) { // ds_descricao ?>
	<tr id="r_ds_descricao">
		<td><span id="elh_gpcomite_ds_descricao"><?php echo $gpcomite->ds_descricao->FldCaption() ?></span></td>
		<td<?php echo $gpcomite->ds_descricao->CellAttributes() ?>>
<span id="el_gpcomite_ds_descricao" class="control-group">
<textarea data-field="x_ds_descricao" name="x_ds_descricao" id="x_ds_descricao" cols="35" rows="4" placeholder="<?php echo $gpcomite->ds_descricao->PlaceHolder ?>"<?php echo $gpcomite->ds_descricao->EditAttributes() ?>><?php echo $gpcomite->ds_descricao->EditValue ?></textarea>
</span>
<?php echo $gpcomite->ds_descricao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpcomite->ds_finalidade->Visible) { // ds_finalidade ?>
	<tr id="r_ds_finalidade">
		<td><span id="elh_gpcomite_ds_finalidade"><?php echo $gpcomite->ds_finalidade->FldCaption() ?></span></td>
		<td<?php echo $gpcomite->ds_finalidade->CellAttributes() ?>>
<span id="el_gpcomite_ds_finalidade" class="control-group">
<textarea data-field="x_ds_finalidade" name="x_ds_finalidade" id="x_ds_finalidade" cols="35" rows="4" placeholder="<?php echo $gpcomite->ds_finalidade->PlaceHolder ?>"<?php echo $gpcomite->ds_finalidade->EditAttributes() ?>><?php echo $gpcomite->ds_finalidade->EditValue ?></textarea>
</span>
<?php echo $gpcomite->ds_finalidade->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpcomite->ic_natureza->Visible) { // ic_natureza ?>
	<tr id="r_ic_natureza">
		<td><span id="elh_gpcomite_ic_natureza"><?php echo $gpcomite->ic_natureza->FldCaption() ?></span></td>
		<td<?php echo $gpcomite->ic_natureza->CellAttributes() ?>>
<span id="el_gpcomite_ic_natureza" class="control-group">
<select data-field="x_ic_natureza" id="x_ic_natureza" name="x_ic_natureza"<?php echo $gpcomite->ic_natureza->EditAttributes() ?>>
<?php
if (is_array($gpcomite->ic_natureza->EditValue)) {
	$arwrk = $gpcomite->ic_natureza->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($gpcomite->ic_natureza->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $gpcomite->ic_natureza->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpcomite->ds_competencias->Visible) { // ds_competencias ?>
	<tr id="r_ds_competencias">
		<td><span id="elh_gpcomite_ds_competencias"><?php echo $gpcomite->ds_competencias->FldCaption() ?></span></td>
		<td<?php echo $gpcomite->ds_competencias->CellAttributes() ?>>
<span id="el_gpcomite_ds_competencias" class="control-group">
<textarea data-field="x_ds_competencias" name="x_ds_competencias" id="x_ds_competencias" cols="35" rows="4" placeholder="<?php echo $gpcomite->ds_competencias->PlaceHolder ?>"<?php echo $gpcomite->ds_competencias->EditAttributes() ?>><?php echo $gpcomite->ds_competencias->EditValue ?></textarea>
</span>
<?php echo $gpcomite->ds_competencias->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpcomite->ds_partesInteressadas->Visible) { // ds_partesInteressadas ?>
	<tr id="r_ds_partesInteressadas">
		<td><span id="elh_gpcomite_ds_partesInteressadas"><?php echo $gpcomite->ds_partesInteressadas->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $gpcomite->ds_partesInteressadas->CellAttributes() ?>>
<span id="el_gpcomite_ds_partesInteressadas" class="control-group">
<textarea data-field="x_ds_partesInteressadas" name="x_ds_partesInteressadas" id="x_ds_partesInteressadas" cols="35" rows="4" placeholder="<?php echo $gpcomite->ds_partesInteressadas->PlaceHolder ?>"<?php echo $gpcomite->ds_partesInteressadas->EditAttributes() ?>><?php echo $gpcomite->ds_partesInteressadas->EditValue ?></textarea>
</span>
<?php echo $gpcomite->ds_partesInteressadas->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_gpcomite2">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_gpcomiteadd2" class="table table-bordered table-striped">
<?php if ($gpcomite->ic_periodicidadeReunioes->Visible) { // ic_periodicidadeReunioes ?>
	<tr id="r_ic_periodicidadeReunioes">
		<td><span id="elh_gpcomite_ic_periodicidadeReunioes"><?php echo $gpcomite->ic_periodicidadeReunioes->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $gpcomite->ic_periodicidadeReunioes->CellAttributes() ?>>
<span id="el_gpcomite_ic_periodicidadeReunioes" class="control-group">
<select data-field="x_ic_periodicidadeReunioes" id="x_ic_periodicidadeReunioes" name="x_ic_periodicidadeReunioes"<?php echo $gpcomite->ic_periodicidadeReunioes->EditAttributes() ?>>
<?php
if (is_array($gpcomite->ic_periodicidadeReunioes->EditValue)) {
	$arwrk = $gpcomite->ic_periodicidadeReunioes->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($gpcomite->ic_periodicidadeReunioes->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $gpcomite->ic_periodicidadeReunioes->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpcomite->dt_basePeriodicidade->Visible) { // dt_basePeriodicidade ?>
	<tr id="r_dt_basePeriodicidade">
		<td><span id="elh_gpcomite_dt_basePeriodicidade"><?php echo $gpcomite->dt_basePeriodicidade->FldCaption() ?></span></td>
		<td<?php echo $gpcomite->dt_basePeriodicidade->CellAttributes() ?>>
<span id="el_gpcomite_dt_basePeriodicidade" class="control-group">
<input type="text" data-field="x_dt_basePeriodicidade" name="x_dt_basePeriodicidade" id="x_dt_basePeriodicidade" placeholder="<?php echo $gpcomite->dt_basePeriodicidade->PlaceHolder ?>" value="<?php echo $gpcomite->dt_basePeriodicidade->EditValue ?>"<?php echo $gpcomite->dt_basePeriodicidade->EditAttributes() ?>>
<?php if (!$gpcomite->dt_basePeriodicidade->ReadOnly && !$gpcomite->dt_basePeriodicidade->Disabled && @$gpcomite->dt_basePeriodicidade->EditAttrs["readonly"] == "" && @$gpcomite->dt_basePeriodicidade->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_dt_basePeriodicidade" name="cal_x_dt_basePeriodicidade" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x_dt_basePeriodicidade" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fgpcomiteadd", "x_dt_basePeriodicidade", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $gpcomite->dt_basePeriodicidade->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_gpcomite3">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_gpcomiteadd3" class="table table-bordered table-striped">
<?php if ($gpcomite->no_localDocDiretrizes->Visible) { // no_localDocDiretrizes ?>
	<tr id="r_no_localDocDiretrizes">
		<td><span id="elh_gpcomite_no_localDocDiretrizes"><?php echo $gpcomite->no_localDocDiretrizes->FldCaption() ?></span></td>
		<td<?php echo $gpcomite->no_localDocDiretrizes->CellAttributes() ?>>
<span id="el_gpcomite_no_localDocDiretrizes" class="control-group">
<input type="text" data-field="x_no_localDocDiretrizes" name="x_no_localDocDiretrizes" id="x_no_localDocDiretrizes" size="30" maxlength="255" placeholder="<?php echo $gpcomite->no_localDocDiretrizes->PlaceHolder ?>" value="<?php echo $gpcomite->no_localDocDiretrizes->EditValue ?>"<?php echo $gpcomite->no_localDocDiretrizes->EditAttributes() ?>>
</span>
<?php echo $gpcomite->no_localDocDiretrizes->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpcomite->im_anexoDiretrizes->Visible) { // im_anexoDiretrizes ?>
	<tr id="r_im_anexoDiretrizes">
		<td><span id="elh_gpcomite_im_anexoDiretrizes"><?php echo $gpcomite->im_anexoDiretrizes->FldCaption() ?></span></td>
		<td<?php echo $gpcomite->im_anexoDiretrizes->CellAttributes() ?>>
<span id="el_gpcomite_im_anexoDiretrizes" class="control-group">
<span id="fd_x_im_anexoDiretrizes">
<span class="btn btn-small fileinput-button">
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_im_anexoDiretrizes" name="x_im_anexoDiretrizes" id="x_im_anexoDiretrizes" multiple="multiple">
</span>
<input type="hidden" name="fn_x_im_anexoDiretrizes" id= "fn_x_im_anexoDiretrizes" value="<?php echo $gpcomite->im_anexoDiretrizes->Upload->FileName ?>">
<input type="hidden" name="fa_x_im_anexoDiretrizes" id= "fa_x_im_anexoDiretrizes" value="0">
<input type="hidden" name="fs_x_im_anexoDiretrizes" id= "fs_x_im_anexoDiretrizes" value="255">
</span>
<table id="ft_x_im_anexoDiretrizes" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $gpcomite->im_anexoDiretrizes->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpcomite->no_localDocComunicacao->Visible) { // no_localDocComunicacao ?>
	<tr id="r_no_localDocComunicacao">
		<td><span id="elh_gpcomite_no_localDocComunicacao"><?php echo $gpcomite->no_localDocComunicacao->FldCaption() ?></span></td>
		<td<?php echo $gpcomite->no_localDocComunicacao->CellAttributes() ?>>
<span id="el_gpcomite_no_localDocComunicacao" class="control-group">
<input type="text" data-field="x_no_localDocComunicacao" name="x_no_localDocComunicacao" id="x_no_localDocComunicacao" size="30" maxlength="255" placeholder="<?php echo $gpcomite->no_localDocComunicacao->PlaceHolder ?>" value="<?php echo $gpcomite->no_localDocComunicacao->EditValue ?>"<?php echo $gpcomite->no_localDocComunicacao->EditAttributes() ?>>
</span>
<?php echo $gpcomite->no_localDocComunicacao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpcomite->im_anexoComunicacao->Visible) { // im_anexoComunicacao ?>
	<tr id="r_im_anexoComunicacao">
		<td><span id="elh_gpcomite_im_anexoComunicacao"><?php echo $gpcomite->im_anexoComunicacao->FldCaption() ?></span></td>
		<td<?php echo $gpcomite->im_anexoComunicacao->CellAttributes() ?>>
<span id="el_gpcomite_im_anexoComunicacao" class="control-group">
<span id="fd_x_im_anexoComunicacao">
<span class="btn btn-small fileinput-button">
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_im_anexoComunicacao" name="x_im_anexoComunicacao" id="x_im_anexoComunicacao" multiple="multiple">
</span>
<input type="hidden" name="fn_x_im_anexoComunicacao" id= "fn_x_im_anexoComunicacao" value="<?php echo $gpcomite->im_anexoComunicacao->Upload->FileName ?>">
<input type="hidden" name="fa_x_im_anexoComunicacao" id= "fa_x_im_anexoComunicacao" value="0">
<input type="hidden" name="fs_x_im_anexoComunicacao" id= "fs_x_im_anexoComunicacao" value="255">
</span>
<table id="ft_x_im_anexoComunicacao" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $gpcomite->im_anexoComunicacao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpcomite->no_localParecerJuridico->Visible) { // no_localParecerJuridico ?>
	<tr id="r_no_localParecerJuridico">
		<td><span id="elh_gpcomite_no_localParecerJuridico"><?php echo $gpcomite->no_localParecerJuridico->FldCaption() ?></span></td>
		<td<?php echo $gpcomite->no_localParecerJuridico->CellAttributes() ?>>
<span id="el_gpcomite_no_localParecerJuridico" class="control-group">
<input type="text" data-field="x_no_localParecerJuridico" name="x_no_localParecerJuridico" id="x_no_localParecerJuridico" size="30" maxlength="255" placeholder="<?php echo $gpcomite->no_localParecerJuridico->PlaceHolder ?>" value="<?php echo $gpcomite->no_localParecerJuridico->EditValue ?>"<?php echo $gpcomite->no_localParecerJuridico->EditAttributes() ?>>
</span>
<?php echo $gpcomite->no_localParecerJuridico->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpcomite->im_anexoParecerJuridico->Visible) { // im_anexoParecerJuridico ?>
	<tr id="r_im_anexoParecerJuridico">
		<td><span id="elh_gpcomite_im_anexoParecerJuridico"><?php echo $gpcomite->im_anexoParecerJuridico->FldCaption() ?></span></td>
		<td<?php echo $gpcomite->im_anexoParecerJuridico->CellAttributes() ?>>
<span id="el_gpcomite_im_anexoParecerJuridico" class="control-group">
<span id="fd_x_im_anexoParecerJuridico">
<span class="btn btn-small fileinput-button">
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_im_anexoParecerJuridico" name="x_im_anexoParecerJuridico" id="x_im_anexoParecerJuridico" multiple="multiple">
</span>
<input type="hidden" name="fn_x_im_anexoParecerJuridico" id= "fn_x_im_anexoParecerJuridico" value="<?php echo $gpcomite->im_anexoParecerJuridico->Upload->FileName ?>">
<input type="hidden" name="fa_x_im_anexoParecerJuridico" id= "fa_x_im_anexoParecerJuridico" value="0">
<input type="hidden" name="fs_x_im_anexoParecerJuridico" id= "fs_x_im_anexoParecerJuridico" value="255">
</span>
<table id="ft_x_im_anexoParecerJuridico" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $gpcomite->im_anexoParecerJuridico->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpcomite->no_localDocDesignacao->Visible) { // no_localDocDesignacao ?>
	<tr id="r_no_localDocDesignacao">
		<td><span id="elh_gpcomite_no_localDocDesignacao"><?php echo $gpcomite->no_localDocDesignacao->FldCaption() ?></span></td>
		<td<?php echo $gpcomite->no_localDocDesignacao->CellAttributes() ?>>
<span id="el_gpcomite_no_localDocDesignacao" class="control-group">
<input type="text" data-field="x_no_localDocDesignacao" name="x_no_localDocDesignacao" id="x_no_localDocDesignacao" size="30" maxlength="255" placeholder="<?php echo $gpcomite->no_localDocDesignacao->PlaceHolder ?>" value="<?php echo $gpcomite->no_localDocDesignacao->EditValue ?>"<?php echo $gpcomite->no_localDocDesignacao->EditAttributes() ?>>
</span>
<?php echo $gpcomite->no_localDocDesignacao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpcomite->im_anexoDesignacao->Visible) { // im_anexoDesignacao ?>
	<tr id="r_im_anexoDesignacao">
		<td><span id="elh_gpcomite_im_anexoDesignacao"><?php echo $gpcomite->im_anexoDesignacao->FldCaption() ?></span></td>
		<td<?php echo $gpcomite->im_anexoDesignacao->CellAttributes() ?>>
<span id="el_gpcomite_im_anexoDesignacao" class="control-group">
<span id="fd_x_im_anexoDesignacao">
<span class="btn btn-small fileinput-button">
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_im_anexoDesignacao" name="x_im_anexoDesignacao" id="x_im_anexoDesignacao" multiple="multiple">
</span>
<input type="hidden" name="fn_x_im_anexoDesignacao" id= "fn_x_im_anexoDesignacao" value="<?php echo $gpcomite->im_anexoDesignacao->Upload->FileName ?>">
<input type="hidden" name="fa_x_im_anexoDesignacao" id= "fa_x_im_anexoDesignacao" value="0">
<input type="hidden" name="fs_x_im_anexoDesignacao" id= "fs_x_im_anexoDesignacao" value="255">
</span>
<table id="ft_x_im_anexoDesignacao" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $gpcomite->im_anexoDesignacao->CustomMsg ?></td>
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
fgpcomiteadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$gpcomite_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$gpcomite_add->Page_Terminate();
?>
