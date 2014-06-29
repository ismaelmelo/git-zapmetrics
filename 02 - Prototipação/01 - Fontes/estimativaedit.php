<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "estimativainfo.php" ?>
<?php include_once "solicitacaometricasinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$estimativa_edit = NULL; // Initialize page object first

class cestimativa_edit extends cestimativa {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'estimativa';

	// Page object name
	var $PageObjName = 'estimativa_edit';

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
	var $AuditTrailOnEdit = TRUE;

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

		// Table object (estimativa)
		if (!isset($GLOBALS["estimativa"])) {
			$GLOBALS["estimativa"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["estimativa"];
		}

		// Table object (solicitacaoMetricas)
		if (!isset($GLOBALS['solicitacaoMetricas'])) $GLOBALS['solicitacaoMetricas'] = new csolicitacaoMetricas();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'estimativa', TRUE);

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
			$this->Page_Terminate("estimativalist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action
		$this->nu_estimativa->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		if (@$_GET["nu_estimativa"] <> "") {
			$this->nu_estimativa->setQueryStringValue($_GET["nu_estimativa"]);
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
		if ($this->nu_estimativa->CurrentValue == "")
			$this->Page_Terminate("estimativalist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("estimativalist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "estimativaview.php")
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
		if (!$this->nu_solMetricas->FldIsDetailKey) {
			$this->nu_solMetricas->setFormValue($objForm->GetValue("x_nu_solMetricas"));
		}
		if (!$this->nu_estimativa->FldIsDetailKey)
			$this->nu_estimativa->setFormValue($objForm->GetValue("x_nu_estimativa"));
		if (!$this->ic_solicitacaoCritica->FldIsDetailKey) {
			$this->ic_solicitacaoCritica->setFormValue($objForm->GetValue("x_ic_solicitacaoCritica"));
		}
		if (!$this->nu_ambienteMaisRepresentativo->FldIsDetailKey) {
			$this->nu_ambienteMaisRepresentativo->setFormValue($objForm->GetValue("x_nu_ambienteMaisRepresentativo"));
		}
		if (!$this->qt_tamBase->FldIsDetailKey) {
			$this->qt_tamBase->setFormValue($objForm->GetValue("x_qt_tamBase"));
		}
		if (!$this->ic_modeloCocomo->FldIsDetailKey) {
			$this->ic_modeloCocomo->setFormValue($objForm->GetValue("x_ic_modeloCocomo"));
		}
		if (!$this->nu_metPrazo->FldIsDetailKey) {
			$this->nu_metPrazo->setFormValue($objForm->GetValue("x_nu_metPrazo"));
		}
		if (!$this->vr_doPf->FldIsDetailKey) {
			$this->vr_doPf->setFormValue($objForm->GetValue("x_vr_doPf"));
		}
		if (!$this->pz_estimadoMeses->FldIsDetailKey) {
			$this->pz_estimadoMeses->setFormValue($objForm->GetValue("x_pz_estimadoMeses"));
		}
		if (!$this->pz_estimadoDias->FldIsDetailKey) {
			$this->pz_estimadoDias->setFormValue($objForm->GetValue("x_pz_estimadoDias"));
		}
		if (!$this->vr_ipMaximo->FldIsDetailKey) {
			$this->vr_ipMaximo->setFormValue($objForm->GetValue("x_vr_ipMaximo"));
		}
		if (!$this->vr_ipMedio->FldIsDetailKey) {
			$this->vr_ipMedio->setFormValue($objForm->GetValue("x_vr_ipMedio"));
		}
		if (!$this->vr_ipMinimo->FldIsDetailKey) {
			$this->vr_ipMinimo->setFormValue($objForm->GetValue("x_vr_ipMinimo"));
		}
		if (!$this->vr_ipInformado->FldIsDetailKey) {
			$this->vr_ipInformado->setFormValue($objForm->GetValue("x_vr_ipInformado"));
		}
		if (!$this->qt_esforco->FldIsDetailKey) {
			$this->qt_esforco->setFormValue($objForm->GetValue("x_qt_esforco"));
		}
		if (!$this->vr_custoDesenv->FldIsDetailKey) {
			$this->vr_custoDesenv->setFormValue($objForm->GetValue("x_vr_custoDesenv"));
		}
		if (!$this->vr_outrosCustos->FldIsDetailKey) {
			$this->vr_outrosCustos->setFormValue($objForm->GetValue("x_vr_outrosCustos"));
		}
		if (!$this->vr_custoTotal->FldIsDetailKey) {
			$this->vr_custoTotal->setFormValue($objForm->GetValue("x_vr_custoTotal"));
		}
		if (!$this->qt_tamBaseFaturamento->FldIsDetailKey) {
			$this->qt_tamBaseFaturamento->setFormValue($objForm->GetValue("x_qt_tamBaseFaturamento"));
		}
		if (!$this->qt_recursosEquipe->FldIsDetailKey) {
			$this->qt_recursosEquipe->setFormValue($objForm->GetValue("x_qt_recursosEquipe"));
		}
		if (!$this->ds_observacoes->FldIsDetailKey) {
			$this->ds_observacoes->setFormValue($objForm->GetValue("x_ds_observacoes"));
		}
		if (!$this->nu_altRELY->FldIsDetailKey) {
			$this->nu_altRELY->setFormValue($objForm->GetValue("x_nu_altRELY"));
		}
		if (!$this->nu_altDATA->FldIsDetailKey) {
			$this->nu_altDATA->setFormValue($objForm->GetValue("x_nu_altDATA"));
		}
		if (!$this->nu_altCPLX1->FldIsDetailKey) {
			$this->nu_altCPLX1->setFormValue($objForm->GetValue("x_nu_altCPLX1"));
		}
		if (!$this->nu_altCPLX2->FldIsDetailKey) {
			$this->nu_altCPLX2->setFormValue($objForm->GetValue("x_nu_altCPLX2"));
		}
		if (!$this->nu_altCPLX3->FldIsDetailKey) {
			$this->nu_altCPLX3->setFormValue($objForm->GetValue("x_nu_altCPLX3"));
		}
		if (!$this->nu_altCPLX4->FldIsDetailKey) {
			$this->nu_altCPLX4->setFormValue($objForm->GetValue("x_nu_altCPLX4"));
		}
		if (!$this->nu_altCPLX5->FldIsDetailKey) {
			$this->nu_altCPLX5->setFormValue($objForm->GetValue("x_nu_altCPLX5"));
		}
		if (!$this->nu_altDOCU->FldIsDetailKey) {
			$this->nu_altDOCU->setFormValue($objForm->GetValue("x_nu_altDOCU"));
		}
		if (!$this->nu_altRUSE->FldIsDetailKey) {
			$this->nu_altRUSE->setFormValue($objForm->GetValue("x_nu_altRUSE"));
		}
		if (!$this->nu_altTIME->FldIsDetailKey) {
			$this->nu_altTIME->setFormValue($objForm->GetValue("x_nu_altTIME"));
		}
		if (!$this->nu_altSTOR->FldIsDetailKey) {
			$this->nu_altSTOR->setFormValue($objForm->GetValue("x_nu_altSTOR"));
		}
		if (!$this->nu_altPVOL->FldIsDetailKey) {
			$this->nu_altPVOL->setFormValue($objForm->GetValue("x_nu_altPVOL"));
		}
		if (!$this->nu_altACAP->FldIsDetailKey) {
			$this->nu_altACAP->setFormValue($objForm->GetValue("x_nu_altACAP"));
		}
		if (!$this->nu_altPCAP->FldIsDetailKey) {
			$this->nu_altPCAP->setFormValue($objForm->GetValue("x_nu_altPCAP"));
		}
		if (!$this->nu_altPCON->FldIsDetailKey) {
			$this->nu_altPCON->setFormValue($objForm->GetValue("x_nu_altPCON"));
		}
		if (!$this->nu_altAPEX->FldIsDetailKey) {
			$this->nu_altAPEX->setFormValue($objForm->GetValue("x_nu_altAPEX"));
		}
		if (!$this->nu_altPLEX->FldIsDetailKey) {
			$this->nu_altPLEX->setFormValue($objForm->GetValue("x_nu_altPLEX"));
		}
		if (!$this->nu_altLTEX->FldIsDetailKey) {
			$this->nu_altLTEX->setFormValue($objForm->GetValue("x_nu_altLTEX"));
		}
		if (!$this->nu_altTOOL->FldIsDetailKey) {
			$this->nu_altTOOL->setFormValue($objForm->GetValue("x_nu_altTOOL"));
		}
		if (!$this->nu_altSITE->FldIsDetailKey) {
			$this->nu_altSITE->setFormValue($objForm->GetValue("x_nu_altSITE"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->nu_solMetricas->CurrentValue = $this->nu_solMetricas->FormValue;
		$this->nu_estimativa->CurrentValue = $this->nu_estimativa->FormValue;
		$this->ic_solicitacaoCritica->CurrentValue = $this->ic_solicitacaoCritica->FormValue;
		$this->nu_ambienteMaisRepresentativo->CurrentValue = $this->nu_ambienteMaisRepresentativo->FormValue;
		$this->qt_tamBase->CurrentValue = $this->qt_tamBase->FormValue;
		$this->ic_modeloCocomo->CurrentValue = $this->ic_modeloCocomo->FormValue;
		$this->nu_metPrazo->CurrentValue = $this->nu_metPrazo->FormValue;
		$this->vr_doPf->CurrentValue = $this->vr_doPf->FormValue;
		$this->pz_estimadoMeses->CurrentValue = $this->pz_estimadoMeses->FormValue;
		$this->pz_estimadoDias->CurrentValue = $this->pz_estimadoDias->FormValue;
		$this->vr_ipMaximo->CurrentValue = $this->vr_ipMaximo->FormValue;
		$this->vr_ipMedio->CurrentValue = $this->vr_ipMedio->FormValue;
		$this->vr_ipMinimo->CurrentValue = $this->vr_ipMinimo->FormValue;
		$this->vr_ipInformado->CurrentValue = $this->vr_ipInformado->FormValue;
		$this->qt_esforco->CurrentValue = $this->qt_esforco->FormValue;
		$this->vr_custoDesenv->CurrentValue = $this->vr_custoDesenv->FormValue;
		$this->vr_outrosCustos->CurrentValue = $this->vr_outrosCustos->FormValue;
		$this->vr_custoTotal->CurrentValue = $this->vr_custoTotal->FormValue;
		$this->qt_tamBaseFaturamento->CurrentValue = $this->qt_tamBaseFaturamento->FormValue;
		$this->qt_recursosEquipe->CurrentValue = $this->qt_recursosEquipe->FormValue;
		$this->ds_observacoes->CurrentValue = $this->ds_observacoes->FormValue;
		$this->nu_altRELY->CurrentValue = $this->nu_altRELY->FormValue;
		$this->nu_altDATA->CurrentValue = $this->nu_altDATA->FormValue;
		$this->nu_altCPLX1->CurrentValue = $this->nu_altCPLX1->FormValue;
		$this->nu_altCPLX2->CurrentValue = $this->nu_altCPLX2->FormValue;
		$this->nu_altCPLX3->CurrentValue = $this->nu_altCPLX3->FormValue;
		$this->nu_altCPLX4->CurrentValue = $this->nu_altCPLX4->FormValue;
		$this->nu_altCPLX5->CurrentValue = $this->nu_altCPLX5->FormValue;
		$this->nu_altDOCU->CurrentValue = $this->nu_altDOCU->FormValue;
		$this->nu_altRUSE->CurrentValue = $this->nu_altRUSE->FormValue;
		$this->nu_altTIME->CurrentValue = $this->nu_altTIME->FormValue;
		$this->nu_altSTOR->CurrentValue = $this->nu_altSTOR->FormValue;
		$this->nu_altPVOL->CurrentValue = $this->nu_altPVOL->FormValue;
		$this->nu_altACAP->CurrentValue = $this->nu_altACAP->FormValue;
		$this->nu_altPCAP->CurrentValue = $this->nu_altPCAP->FormValue;
		$this->nu_altPCON->CurrentValue = $this->nu_altPCON->FormValue;
		$this->nu_altAPEX->CurrentValue = $this->nu_altAPEX->FormValue;
		$this->nu_altPLEX->CurrentValue = $this->nu_altPLEX->FormValue;
		$this->nu_altLTEX->CurrentValue = $this->nu_altLTEX->FormValue;
		$this->nu_altTOOL->CurrentValue = $this->nu_altTOOL->FormValue;
		$this->nu_altSITE->CurrentValue = $this->nu_altSITE->FormValue;
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
		$this->nu_estimativa->setDbValue($rs->fields('nu_estimativa'));
		$this->ic_solicitacaoCritica->setDbValue($rs->fields('ic_solicitacaoCritica'));
		$this->nu_ambienteMaisRepresentativo->setDbValue($rs->fields('nu_ambienteMaisRepresentativo'));
		$this->qt_tamBase->setDbValue($rs->fields('qt_tamBase'));
		$this->ic_modeloCocomo->setDbValue($rs->fields('ic_modeloCocomo'));
		$this->nu_metPrazo->setDbValue($rs->fields('nu_metPrazo'));
		$this->vr_doPf->setDbValue($rs->fields('vr_doPf'));
		$this->pz_estimadoMeses->setDbValue($rs->fields('pz_estimadoMeses'));
		$this->pz_estimadoDias->setDbValue($rs->fields('pz_estimadoDias'));
		$this->vr_ipMaximo->setDbValue($rs->fields('vr_ipMaximo'));
		$this->vr_ipMedio->setDbValue($rs->fields('vr_ipMedio'));
		$this->vr_ipMinimo->setDbValue($rs->fields('vr_ipMinimo'));
		$this->vr_ipInformado->setDbValue($rs->fields('vr_ipInformado'));
		$this->qt_esforco->setDbValue($rs->fields('qt_esforco'));
		$this->vr_custoDesenv->setDbValue($rs->fields('vr_custoDesenv'));
		$this->vr_outrosCustos->setDbValue($rs->fields('vr_outrosCustos'));
		$this->vr_custoTotal->setDbValue($rs->fields('vr_custoTotal'));
		$this->qt_tamBaseFaturamento->setDbValue($rs->fields('qt_tamBaseFaturamento'));
		$this->qt_recursosEquipe->setDbValue($rs->fields('qt_recursosEquipe'));
		$this->ds_observacoes->setDbValue($rs->fields('ds_observacoes'));
		$this->ic_bloqueio->setDbValue($rs->fields('ic_bloqueio'));
		$this->nu_altRELY->setDbValue($rs->fields('nu_altRELY'));
		$this->nu_altDATA->setDbValue($rs->fields('nu_altDATA'));
		$this->nu_altCPLX1->setDbValue($rs->fields('nu_altCPLX1'));
		$this->nu_altCPLX2->setDbValue($rs->fields('nu_altCPLX2'));
		$this->nu_altCPLX3->setDbValue($rs->fields('nu_altCPLX3'));
		$this->nu_altCPLX4->setDbValue($rs->fields('nu_altCPLX4'));
		$this->nu_altCPLX5->setDbValue($rs->fields('nu_altCPLX5'));
		$this->nu_altDOCU->setDbValue($rs->fields('nu_altDOCU'));
		$this->nu_altRUSE->setDbValue($rs->fields('nu_altRUSE'));
		$this->nu_altTIME->setDbValue($rs->fields('nu_altTIME'));
		$this->nu_altSTOR->setDbValue($rs->fields('nu_altSTOR'));
		$this->nu_altPVOL->setDbValue($rs->fields('nu_altPVOL'));
		$this->nu_altACAP->setDbValue($rs->fields('nu_altACAP'));
		$this->nu_altPCAP->setDbValue($rs->fields('nu_altPCAP'));
		$this->nu_altPCON->setDbValue($rs->fields('nu_altPCON'));
		$this->nu_altAPEX->setDbValue($rs->fields('nu_altAPEX'));
		$this->nu_altPLEX->setDbValue($rs->fields('nu_altPLEX'));
		$this->nu_altLTEX->setDbValue($rs->fields('nu_altLTEX'));
		$this->nu_altTOOL->setDbValue($rs->fields('nu_altTOOL'));
		$this->nu_altSITE->setDbValue($rs->fields('nu_altSITE'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_solMetricas->DbValue = $row['nu_solMetricas'];
		$this->nu_estimativa->DbValue = $row['nu_estimativa'];
		$this->ic_solicitacaoCritica->DbValue = $row['ic_solicitacaoCritica'];
		$this->nu_ambienteMaisRepresentativo->DbValue = $row['nu_ambienteMaisRepresentativo'];
		$this->qt_tamBase->DbValue = $row['qt_tamBase'];
		$this->ic_modeloCocomo->DbValue = $row['ic_modeloCocomo'];
		$this->nu_metPrazo->DbValue = $row['nu_metPrazo'];
		$this->vr_doPf->DbValue = $row['vr_doPf'];
		$this->pz_estimadoMeses->DbValue = $row['pz_estimadoMeses'];
		$this->pz_estimadoDias->DbValue = $row['pz_estimadoDias'];
		$this->vr_ipMaximo->DbValue = $row['vr_ipMaximo'];
		$this->vr_ipMedio->DbValue = $row['vr_ipMedio'];
		$this->vr_ipMinimo->DbValue = $row['vr_ipMinimo'];
		$this->vr_ipInformado->DbValue = $row['vr_ipInformado'];
		$this->qt_esforco->DbValue = $row['qt_esforco'];
		$this->vr_custoDesenv->DbValue = $row['vr_custoDesenv'];
		$this->vr_outrosCustos->DbValue = $row['vr_outrosCustos'];
		$this->vr_custoTotal->DbValue = $row['vr_custoTotal'];
		$this->qt_tamBaseFaturamento->DbValue = $row['qt_tamBaseFaturamento'];
		$this->qt_recursosEquipe->DbValue = $row['qt_recursosEquipe'];
		$this->ds_observacoes->DbValue = $row['ds_observacoes'];
		$this->ic_bloqueio->DbValue = $row['ic_bloqueio'];
		$this->nu_altRELY->DbValue = $row['nu_altRELY'];
		$this->nu_altDATA->DbValue = $row['nu_altDATA'];
		$this->nu_altCPLX1->DbValue = $row['nu_altCPLX1'];
		$this->nu_altCPLX2->DbValue = $row['nu_altCPLX2'];
		$this->nu_altCPLX3->DbValue = $row['nu_altCPLX3'];
		$this->nu_altCPLX4->DbValue = $row['nu_altCPLX4'];
		$this->nu_altCPLX5->DbValue = $row['nu_altCPLX5'];
		$this->nu_altDOCU->DbValue = $row['nu_altDOCU'];
		$this->nu_altRUSE->DbValue = $row['nu_altRUSE'];
		$this->nu_altTIME->DbValue = $row['nu_altTIME'];
		$this->nu_altSTOR->DbValue = $row['nu_altSTOR'];
		$this->nu_altPVOL->DbValue = $row['nu_altPVOL'];
		$this->nu_altACAP->DbValue = $row['nu_altACAP'];
		$this->nu_altPCAP->DbValue = $row['nu_altPCAP'];
		$this->nu_altPCON->DbValue = $row['nu_altPCON'];
		$this->nu_altAPEX->DbValue = $row['nu_altAPEX'];
		$this->nu_altPLEX->DbValue = $row['nu_altPLEX'];
		$this->nu_altLTEX->DbValue = $row['nu_altLTEX'];
		$this->nu_altTOOL->DbValue = $row['nu_altTOOL'];
		$this->nu_altSITE->DbValue = $row['nu_altSITE'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->qt_tamBase->FormValue == $this->qt_tamBase->CurrentValue && is_numeric(ew_StrToFloat($this->qt_tamBase->CurrentValue)))
			$this->qt_tamBase->CurrentValue = ew_StrToFloat($this->qt_tamBase->CurrentValue);

		// Convert decimal values if posted back
		if ($this->pz_estimadoMeses->FormValue == $this->pz_estimadoMeses->CurrentValue && is_numeric(ew_StrToFloat($this->pz_estimadoMeses->CurrentValue)))
			$this->pz_estimadoMeses->CurrentValue = ew_StrToFloat($this->pz_estimadoMeses->CurrentValue);

		// Convert decimal values if posted back
		if ($this->pz_estimadoDias->FormValue == $this->pz_estimadoDias->CurrentValue && is_numeric(ew_StrToFloat($this->pz_estimadoDias->CurrentValue)))
			$this->pz_estimadoDias->CurrentValue = ew_StrToFloat($this->pz_estimadoDias->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_ipMaximo->FormValue == $this->vr_ipMaximo->CurrentValue && is_numeric(ew_StrToFloat($this->vr_ipMaximo->CurrentValue)))
			$this->vr_ipMaximo->CurrentValue = ew_StrToFloat($this->vr_ipMaximo->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_ipMedio->FormValue == $this->vr_ipMedio->CurrentValue && is_numeric(ew_StrToFloat($this->vr_ipMedio->CurrentValue)))
			$this->vr_ipMedio->CurrentValue = ew_StrToFloat($this->vr_ipMedio->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_ipMinimo->FormValue == $this->vr_ipMinimo->CurrentValue && is_numeric(ew_StrToFloat($this->vr_ipMinimo->CurrentValue)))
			$this->vr_ipMinimo->CurrentValue = ew_StrToFloat($this->vr_ipMinimo->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_esforco->FormValue == $this->qt_esforco->CurrentValue && is_numeric(ew_StrToFloat($this->qt_esforco->CurrentValue)))
			$this->qt_esforco->CurrentValue = ew_StrToFloat($this->qt_esforco->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_custoDesenv->FormValue == $this->vr_custoDesenv->CurrentValue && is_numeric(ew_StrToFloat($this->vr_custoDesenv->CurrentValue)))
			$this->vr_custoDesenv->CurrentValue = ew_StrToFloat($this->vr_custoDesenv->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_outrosCustos->FormValue == $this->vr_outrosCustos->CurrentValue && is_numeric(ew_StrToFloat($this->vr_outrosCustos->CurrentValue)))
			$this->vr_outrosCustos->CurrentValue = ew_StrToFloat($this->vr_outrosCustos->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_custoTotal->FormValue == $this->vr_custoTotal->CurrentValue && is_numeric(ew_StrToFloat($this->vr_custoTotal->CurrentValue)))
			$this->vr_custoTotal->CurrentValue = ew_StrToFloat($this->vr_custoTotal->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_tamBaseFaturamento->FormValue == $this->qt_tamBaseFaturamento->CurrentValue && is_numeric(ew_StrToFloat($this->qt_tamBaseFaturamento->CurrentValue)))
			$this->qt_tamBaseFaturamento->CurrentValue = ew_StrToFloat($this->qt_tamBaseFaturamento->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_recursosEquipe->FormValue == $this->qt_recursosEquipe->CurrentValue && is_numeric(ew_StrToFloat($this->qt_recursosEquipe->CurrentValue)))
			$this->qt_recursosEquipe->CurrentValue = ew_StrToFloat($this->qt_recursosEquipe->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_solMetricas
		// nu_estimativa
		// ic_solicitacaoCritica
		// nu_ambienteMaisRepresentativo
		// qt_tamBase
		// ic_modeloCocomo
		// nu_metPrazo
		// vr_doPf
		// pz_estimadoMeses
		// pz_estimadoDias
		// vr_ipMaximo
		// vr_ipMedio
		// vr_ipMinimo
		// vr_ipInformado
		// qt_esforco
		// vr_custoDesenv
		// vr_outrosCustos
		// vr_custoTotal
		// qt_tamBaseFaturamento
		// qt_recursosEquipe
		// ds_observacoes
		// ic_bloqueio
		// nu_altRELY
		// nu_altDATA
		// nu_altCPLX1
		// nu_altCPLX2
		// nu_altCPLX3
		// nu_altCPLX4
		// nu_altCPLX5
		// nu_altDOCU
		// nu_altRUSE
		// nu_altTIME
		// nu_altSTOR
		// nu_altPVOL
		// nu_altACAP
		// nu_altPCAP
		// nu_altPCON
		// nu_altAPEX
		// nu_altPLEX
		// nu_altLTEX
		// nu_altTOOL
		// nu_altSITE

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_solMetricas
			if (strval($this->nu_solMetricas->CurrentValue) <> "") {
				$sFilterWrk = "[nu_solMetricas]" . ew_SearchString("=", $this->nu_solMetricas->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_solMetricas], [nu_solMetricas] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[solicitacaoMetricas]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_solMetricas, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_solMetricas] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_solMetricas->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_solMetricas->ViewValue = $this->nu_solMetricas->CurrentValue;
				}
			} else {
				$this->nu_solMetricas->ViewValue = NULL;
			}
			$this->nu_solMetricas->ViewCustomAttributes = "";

			// nu_estimativa
			$this->nu_estimativa->ViewValue = $this->nu_estimativa->CurrentValue;
			$this->nu_estimativa->ViewCustomAttributes = "";

			// ic_solicitacaoCritica
			if (strval($this->ic_solicitacaoCritica->CurrentValue) <> "") {
				switch ($this->ic_solicitacaoCritica->CurrentValue) {
					case $this->ic_solicitacaoCritica->FldTagValue(1):
						$this->ic_solicitacaoCritica->ViewValue = $this->ic_solicitacaoCritica->FldTagCaption(1) <> "" ? $this->ic_solicitacaoCritica->FldTagCaption(1) : $this->ic_solicitacaoCritica->CurrentValue;
						break;
					case $this->ic_solicitacaoCritica->FldTagValue(2):
						$this->ic_solicitacaoCritica->ViewValue = $this->ic_solicitacaoCritica->FldTagCaption(2) <> "" ? $this->ic_solicitacaoCritica->FldTagCaption(2) : $this->ic_solicitacaoCritica->CurrentValue;
						break;
					default:
						$this->ic_solicitacaoCritica->ViewValue = $this->ic_solicitacaoCritica->CurrentValue;
				}
			} else {
				$this->ic_solicitacaoCritica->ViewValue = NULL;
			}
			$this->ic_solicitacaoCritica->ViewCustomAttributes = "";

			// nu_ambienteMaisRepresentativo
			$this->nu_ambienteMaisRepresentativo->ViewValue = $this->nu_ambienteMaisRepresentativo->CurrentValue;
			if (strval($this->nu_ambienteMaisRepresentativo->CurrentValue) <> "") {
				$sFilterWrk = "[nu_ambiente]" . ew_SearchString("=", $this->nu_ambienteMaisRepresentativo->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_ambiente], [no_ambiente] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ambiente]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_ambienteMaisRepresentativo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_ambiente] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_ambienteMaisRepresentativo->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_ambienteMaisRepresentativo->ViewValue = $this->nu_ambienteMaisRepresentativo->CurrentValue;
				}
			} else {
				$this->nu_ambienteMaisRepresentativo->ViewValue = NULL;
			}
			$this->nu_ambienteMaisRepresentativo->ViewCustomAttributes = "";

			// qt_tamBase
			$this->qt_tamBase->ViewValue = $this->qt_tamBase->CurrentValue;
			$this->qt_tamBase->ViewCustomAttributes = "";

			// ic_modeloCocomo
			if (strval($this->ic_modeloCocomo->CurrentValue) <> "") {
				switch ($this->ic_modeloCocomo->CurrentValue) {
					case $this->ic_modeloCocomo->FldTagValue(1):
						$this->ic_modeloCocomo->ViewValue = $this->ic_modeloCocomo->FldTagCaption(1) <> "" ? $this->ic_modeloCocomo->FldTagCaption(1) : $this->ic_modeloCocomo->CurrentValue;
						break;
					case $this->ic_modeloCocomo->FldTagValue(2):
						$this->ic_modeloCocomo->ViewValue = $this->ic_modeloCocomo->FldTagCaption(2) <> "" ? $this->ic_modeloCocomo->FldTagCaption(2) : $this->ic_modeloCocomo->CurrentValue;
						break;
					default:
						$this->ic_modeloCocomo->ViewValue = $this->ic_modeloCocomo->CurrentValue;
				}
			} else {
				$this->ic_modeloCocomo->ViewValue = NULL;
			}
			$this->ic_modeloCocomo->ViewCustomAttributes = "";

			// nu_metPrazo
			if (strval($this->nu_metPrazo->CurrentValue) <> "") {
				switch ($this->nu_metPrazo->CurrentValue) {
					case $this->nu_metPrazo->FldTagValue(1):
						$this->nu_metPrazo->ViewValue = $this->nu_metPrazo->FldTagCaption(1) <> "" ? $this->nu_metPrazo->FldTagCaption(1) : $this->nu_metPrazo->CurrentValue;
						break;
					case $this->nu_metPrazo->FldTagValue(2):
						$this->nu_metPrazo->ViewValue = $this->nu_metPrazo->FldTagCaption(2) <> "" ? $this->nu_metPrazo->FldTagCaption(2) : $this->nu_metPrazo->CurrentValue;
						break;
					case $this->nu_metPrazo->FldTagValue(3):
						$this->nu_metPrazo->ViewValue = $this->nu_metPrazo->FldTagCaption(3) <> "" ? $this->nu_metPrazo->FldTagCaption(3) : $this->nu_metPrazo->CurrentValue;
						break;
					case $this->nu_metPrazo->FldTagValue(4):
						$this->nu_metPrazo->ViewValue = $this->nu_metPrazo->FldTagCaption(4) <> "" ? $this->nu_metPrazo->FldTagCaption(4) : $this->nu_metPrazo->CurrentValue;
						break;
					case $this->nu_metPrazo->FldTagValue(5):
						$this->nu_metPrazo->ViewValue = $this->nu_metPrazo->FldTagCaption(5) <> "" ? $this->nu_metPrazo->FldTagCaption(5) : $this->nu_metPrazo->CurrentValue;
						break;
					default:
						$this->nu_metPrazo->ViewValue = $this->nu_metPrazo->CurrentValue;
				}
			} else {
				$this->nu_metPrazo->ViewValue = NULL;
			}
			$this->nu_metPrazo->ViewCustomAttributes = "";

			// vr_doPf
			$this->vr_doPf->ViewValue = $this->vr_doPf->CurrentValue;
			$this->vr_doPf->ViewCustomAttributes = "";

			// pz_estimadoMeses
			$this->pz_estimadoMeses->ViewValue = $this->pz_estimadoMeses->CurrentValue;
			$this->pz_estimadoMeses->ViewCustomAttributes = "";

			// pz_estimadoDias
			$this->pz_estimadoDias->ViewValue = $this->pz_estimadoDias->CurrentValue;
			$this->pz_estimadoDias->ViewCustomAttributes = "";

			// vr_ipMaximo
			$this->vr_ipMaximo->ViewValue = $this->vr_ipMaximo->CurrentValue;
			$this->vr_ipMaximo->ViewCustomAttributes = "";

			// vr_ipMedio
			$this->vr_ipMedio->ViewValue = $this->vr_ipMedio->CurrentValue;
			$this->vr_ipMedio->ViewCustomAttributes = "";

			// vr_ipMinimo
			$this->vr_ipMinimo->ViewValue = $this->vr_ipMinimo->CurrentValue;
			$this->vr_ipMinimo->ViewCustomAttributes = "";

			// vr_ipInformado
			$this->vr_ipInformado->ViewValue = $this->vr_ipInformado->CurrentValue;
			$this->vr_ipInformado->ViewCustomAttributes = "";

			// qt_esforco
			$this->qt_esforco->ViewValue = $this->qt_esforco->CurrentValue;
			$this->qt_esforco->ViewCustomAttributes = "";

			// vr_custoDesenv
			$this->vr_custoDesenv->ViewValue = $this->vr_custoDesenv->CurrentValue;
			$this->vr_custoDesenv->ViewCustomAttributes = "";

			// vr_outrosCustos
			$this->vr_outrosCustos->ViewValue = $this->vr_outrosCustos->CurrentValue;
			$this->vr_outrosCustos->ViewCustomAttributes = "";

			// vr_custoTotal
			$this->vr_custoTotal->ViewValue = $this->vr_custoTotal->CurrentValue;
			$this->vr_custoTotal->ViewCustomAttributes = "";

			// qt_tamBaseFaturamento
			$this->qt_tamBaseFaturamento->ViewValue = $this->qt_tamBaseFaturamento->CurrentValue;
			$this->qt_tamBaseFaturamento->ViewCustomAttributes = "";

			// qt_recursosEquipe
			$this->qt_recursosEquipe->ViewValue = $this->qt_recursosEquipe->CurrentValue;
			$this->qt_recursosEquipe->ViewCustomAttributes = "";

			// ds_observacoes
			$this->ds_observacoes->ViewValue = $this->ds_observacoes->CurrentValue;
			$this->ds_observacoes->ViewCustomAttributes = "";

			// ic_bloqueio
			$this->ic_bloqueio->ViewValue = $this->ic_bloqueio->CurrentValue;
			$this->ic_bloqueio->ViewCustomAttributes = "";

			// nu_altRELY
			if (strval($this->nu_altRELY->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altRELY->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[co_questao]=(select co_quePREC FROM ambiente_valoracao where nu_ambiente = '2' and nu_versaoValoracao = '1') AND [ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altRELY, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altRELY->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_altRELY->ViewValue = $this->nu_altRELY->CurrentValue;
				}
			} else {
				$this->nu_altRELY->ViewValue = NULL;
			}
			$this->nu_altRELY->ViewCustomAttributes = "";

			// nu_altDATA
			if (strval($this->nu_altDATA->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altDATA->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[co_questao]=(select co_queDATA FROM ambiente_valoracao where nu_ambiente = '2' and nu_versaoValoracao = '1') AND [ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altDATA, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altDATA->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_altDATA->ViewValue = $this->nu_altDATA->CurrentValue;
				}
			} else {
				$this->nu_altDATA->ViewValue = NULL;
			}
			$this->nu_altDATA->ViewCustomAttributes = "";

			// nu_altCPLX1
			if (strval($this->nu_altCPLX1->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX1->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX1, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altCPLX1->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_altCPLX1->ViewValue = $this->nu_altCPLX1->CurrentValue;
				}
			} else {
				$this->nu_altCPLX1->ViewValue = NULL;
			}
			$this->nu_altCPLX1->ViewCustomAttributes = "";

			// nu_altCPLX2
			if (strval($this->nu_altCPLX2->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX2->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX2, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altCPLX2->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_altCPLX2->ViewValue = $this->nu_altCPLX2->CurrentValue;
				}
			} else {
				$this->nu_altCPLX2->ViewValue = NULL;
			}
			$this->nu_altCPLX2->ViewCustomAttributes = "";

			// nu_altCPLX3
			if (strval($this->nu_altCPLX3->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX3->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX3, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altCPLX3->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_altCPLX3->ViewValue = $this->nu_altCPLX3->CurrentValue;
				}
			} else {
				$this->nu_altCPLX3->ViewValue = NULL;
			}
			$this->nu_altCPLX3->ViewCustomAttributes = "";

			// nu_altCPLX4
			if (strval($this->nu_altCPLX4->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX4->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX4, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altCPLX4->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_altCPLX4->ViewValue = $this->nu_altCPLX4->CurrentValue;
				}
			} else {
				$this->nu_altCPLX4->ViewValue = NULL;
			}
			$this->nu_altCPLX4->ViewCustomAttributes = "";

			// nu_altCPLX5
			if (strval($this->nu_altCPLX5->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX5->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX5, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altCPLX5->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_altCPLX5->ViewValue = $this->nu_altCPLX5->CurrentValue;
				}
			} else {
				$this->nu_altCPLX5->ViewValue = NULL;
			}
			$this->nu_altCPLX5->ViewCustomAttributes = "";

			// nu_altDOCU
			if (strval($this->nu_altDOCU->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altDOCU->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altDOCU, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altDOCU->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_altDOCU->ViewValue = $this->nu_altDOCU->CurrentValue;
				}
			} else {
				$this->nu_altDOCU->ViewValue = NULL;
			}
			$this->nu_altDOCU->ViewCustomAttributes = "";

			// nu_altRUSE
			if (strval($this->nu_altRUSE->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altRUSE->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altRUSE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altRUSE->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_altRUSE->ViewValue = $this->nu_altRUSE->CurrentValue;
				}
			} else {
				$this->nu_altRUSE->ViewValue = NULL;
			}
			$this->nu_altRUSE->ViewCustomAttributes = "";

			// nu_altTIME
			if (strval($this->nu_altTIME->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altTIME->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altTIME, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altTIME->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_altTIME->ViewValue = $this->nu_altTIME->CurrentValue;
				}
			} else {
				$this->nu_altTIME->ViewValue = NULL;
			}
			$this->nu_altTIME->ViewCustomAttributes = "";

			// nu_altSTOR
			if (strval($this->nu_altSTOR->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altSTOR->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altSTOR, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altSTOR->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_altSTOR->ViewValue = $this->nu_altSTOR->CurrentValue;
				}
			} else {
				$this->nu_altSTOR->ViewValue = NULL;
			}
			$this->nu_altSTOR->ViewCustomAttributes = "";

			// nu_altPVOL
			if (strval($this->nu_altPVOL->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altPVOL->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPVOL, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altPVOL->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_altPVOL->ViewValue = $this->nu_altPVOL->CurrentValue;
				}
			} else {
				$this->nu_altPVOL->ViewValue = NULL;
			}
			$this->nu_altPVOL->ViewCustomAttributes = "";

			// nu_altACAP
			if (strval($this->nu_altACAP->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altACAP->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altACAP, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altACAP->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_altACAP->ViewValue = $this->nu_altACAP->CurrentValue;
				}
			} else {
				$this->nu_altACAP->ViewValue = NULL;
			}
			$this->nu_altACAP->ViewCustomAttributes = "";

			// nu_altPCAP
			if (strval($this->nu_altPCAP->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altPCAP->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPCAP, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altPCAP->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_altPCAP->ViewValue = $this->nu_altPCAP->CurrentValue;
				}
			} else {
				$this->nu_altPCAP->ViewValue = NULL;
			}
			$this->nu_altPCAP->ViewCustomAttributes = "";

			// nu_altPCON
			if (strval($this->nu_altPCON->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altPCON->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPCON, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altPCON->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_altPCON->ViewValue = $this->nu_altPCON->CurrentValue;
				}
			} else {
				$this->nu_altPCON->ViewValue = NULL;
			}
			$this->nu_altPCON->ViewCustomAttributes = "";

			// nu_altAPEX
			if (strval($this->nu_altAPEX->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altAPEX->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altAPEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altAPEX->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_altAPEX->ViewValue = $this->nu_altAPEX->CurrentValue;
				}
			} else {
				$this->nu_altAPEX->ViewValue = NULL;
			}
			$this->nu_altAPEX->ViewCustomAttributes = "";

			// nu_altPLEX
			if (strval($this->nu_altPLEX->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altPLEX->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPLEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altPLEX->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_altPLEX->ViewValue = $this->nu_altPLEX->CurrentValue;
				}
			} else {
				$this->nu_altPLEX->ViewValue = NULL;
			}
			$this->nu_altPLEX->ViewCustomAttributes = "";

			// nu_altLTEX
			if (strval($this->nu_altLTEX->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altLTEX->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altLTEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altLTEX->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_altLTEX->ViewValue = $this->nu_altLTEX->CurrentValue;
				}
			} else {
				$this->nu_altLTEX->ViewValue = NULL;
			}
			$this->nu_altLTEX->ViewCustomAttributes = "";

			// nu_altTOOL
			if (strval($this->nu_altTOOL->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altTOOL->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altTOOL, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altTOOL->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_altTOOL->ViewValue = $this->nu_altTOOL->CurrentValue;
				}
			} else {
				$this->nu_altTOOL->ViewValue = NULL;
			}
			$this->nu_altTOOL->ViewCustomAttributes = "";

			// nu_altSITE
			if (strval($this->nu_altSITE->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altSITE->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altSITE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altSITE->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_altSITE->ViewValue = $this->nu_altSITE->CurrentValue;
				}
			} else {
				$this->nu_altSITE->ViewValue = NULL;
			}
			$this->nu_altSITE->ViewCustomAttributes = "";

			// nu_solMetricas
			$this->nu_solMetricas->LinkCustomAttributes = "";
			$this->nu_solMetricas->HrefValue = "";
			$this->nu_solMetricas->TooltipValue = "";

			// nu_estimativa
			$this->nu_estimativa->LinkCustomAttributes = "";
			$this->nu_estimativa->HrefValue = "";
			$this->nu_estimativa->TooltipValue = "";

			// ic_solicitacaoCritica
			$this->ic_solicitacaoCritica->LinkCustomAttributes = "";
			$this->ic_solicitacaoCritica->HrefValue = "";
			$this->ic_solicitacaoCritica->TooltipValue = "";

			// nu_ambienteMaisRepresentativo
			$this->nu_ambienteMaisRepresentativo->LinkCustomAttributes = "";
			$this->nu_ambienteMaisRepresentativo->HrefValue = "";
			$this->nu_ambienteMaisRepresentativo->TooltipValue = "";

			// qt_tamBase
			$this->qt_tamBase->LinkCustomAttributes = "";
			$this->qt_tamBase->HrefValue = "";
			$this->qt_tamBase->TooltipValue = "";

			// ic_modeloCocomo
			$this->ic_modeloCocomo->LinkCustomAttributes = "";
			$this->ic_modeloCocomo->HrefValue = "";
			$this->ic_modeloCocomo->TooltipValue = "";

			// nu_metPrazo
			$this->nu_metPrazo->LinkCustomAttributes = "";
			$this->nu_metPrazo->HrefValue = "";
			$this->nu_metPrazo->TooltipValue = "";

			// vr_doPf
			$this->vr_doPf->LinkCustomAttributes = "";
			$this->vr_doPf->HrefValue = "";
			$this->vr_doPf->TooltipValue = "";

			// pz_estimadoMeses
			$this->pz_estimadoMeses->LinkCustomAttributes = "";
			$this->pz_estimadoMeses->HrefValue = "";
			$this->pz_estimadoMeses->TooltipValue = "";

			// pz_estimadoDias
			$this->pz_estimadoDias->LinkCustomAttributes = "";
			$this->pz_estimadoDias->HrefValue = "";
			$this->pz_estimadoDias->TooltipValue = "";

			// vr_ipMaximo
			$this->vr_ipMaximo->LinkCustomAttributes = "";
			$this->vr_ipMaximo->HrefValue = "";
			$this->vr_ipMaximo->TooltipValue = "";

			// vr_ipMedio
			$this->vr_ipMedio->LinkCustomAttributes = "";
			$this->vr_ipMedio->HrefValue = "";
			$this->vr_ipMedio->TooltipValue = "";

			// vr_ipMinimo
			$this->vr_ipMinimo->LinkCustomAttributes = "";
			$this->vr_ipMinimo->HrefValue = "";
			$this->vr_ipMinimo->TooltipValue = "";

			// vr_ipInformado
			$this->vr_ipInformado->LinkCustomAttributes = "";
			$this->vr_ipInformado->HrefValue = "";
			$this->vr_ipInformado->TooltipValue = "";

			// qt_esforco
			$this->qt_esforco->LinkCustomAttributes = "";
			$this->qt_esforco->HrefValue = "";
			$this->qt_esforco->TooltipValue = "";

			// vr_custoDesenv
			$this->vr_custoDesenv->LinkCustomAttributes = "";
			$this->vr_custoDesenv->HrefValue = "";
			$this->vr_custoDesenv->TooltipValue = "";

			// vr_outrosCustos
			$this->vr_outrosCustos->LinkCustomAttributes = "";
			$this->vr_outrosCustos->HrefValue = "";
			$this->vr_outrosCustos->TooltipValue = "";

			// vr_custoTotal
			$this->vr_custoTotal->LinkCustomAttributes = "";
			$this->vr_custoTotal->HrefValue = "";
			$this->vr_custoTotal->TooltipValue = "";

			// qt_tamBaseFaturamento
			$this->qt_tamBaseFaturamento->LinkCustomAttributes = "";
			$this->qt_tamBaseFaturamento->HrefValue = "";
			$this->qt_tamBaseFaturamento->TooltipValue = "";

			// qt_recursosEquipe
			$this->qt_recursosEquipe->LinkCustomAttributes = "";
			$this->qt_recursosEquipe->HrefValue = "";
			$this->qt_recursosEquipe->TooltipValue = "";

			// ds_observacoes
			$this->ds_observacoes->LinkCustomAttributes = "";
			$this->ds_observacoes->HrefValue = "";
			$this->ds_observacoes->TooltipValue = "";

			// nu_altRELY
			$this->nu_altRELY->LinkCustomAttributes = "";
			$this->nu_altRELY->HrefValue = "";
			$this->nu_altRELY->TooltipValue = "";

			// nu_altDATA
			$this->nu_altDATA->LinkCustomAttributes = "";
			$this->nu_altDATA->HrefValue = "";
			$this->nu_altDATA->TooltipValue = "";

			// nu_altCPLX1
			$this->nu_altCPLX1->LinkCustomAttributes = "";
			$this->nu_altCPLX1->HrefValue = "";
			$this->nu_altCPLX1->TooltipValue = "";

			// nu_altCPLX2
			$this->nu_altCPLX2->LinkCustomAttributes = "";
			$this->nu_altCPLX2->HrefValue = "";
			$this->nu_altCPLX2->TooltipValue = "";

			// nu_altCPLX3
			$this->nu_altCPLX3->LinkCustomAttributes = "";
			$this->nu_altCPLX3->HrefValue = "";
			$this->nu_altCPLX3->TooltipValue = "";

			// nu_altCPLX4
			$this->nu_altCPLX4->LinkCustomAttributes = "";
			$this->nu_altCPLX4->HrefValue = "";
			$this->nu_altCPLX4->TooltipValue = "";

			// nu_altCPLX5
			$this->nu_altCPLX5->LinkCustomAttributes = "";
			$this->nu_altCPLX5->HrefValue = "";
			$this->nu_altCPLX5->TooltipValue = "";

			// nu_altDOCU
			$this->nu_altDOCU->LinkCustomAttributes = "";
			$this->nu_altDOCU->HrefValue = "";
			$this->nu_altDOCU->TooltipValue = "";

			// nu_altRUSE
			$this->nu_altRUSE->LinkCustomAttributes = "";
			$this->nu_altRUSE->HrefValue = "";
			$this->nu_altRUSE->TooltipValue = "";

			// nu_altTIME
			$this->nu_altTIME->LinkCustomAttributes = "";
			$this->nu_altTIME->HrefValue = "";
			$this->nu_altTIME->TooltipValue = "";

			// nu_altSTOR
			$this->nu_altSTOR->LinkCustomAttributes = "";
			$this->nu_altSTOR->HrefValue = "";
			$this->nu_altSTOR->TooltipValue = "";

			// nu_altPVOL
			$this->nu_altPVOL->LinkCustomAttributes = "";
			$this->nu_altPVOL->HrefValue = "";
			$this->nu_altPVOL->TooltipValue = "";

			// nu_altACAP
			$this->nu_altACAP->LinkCustomAttributes = "";
			$this->nu_altACAP->HrefValue = "";
			$this->nu_altACAP->TooltipValue = "";

			// nu_altPCAP
			$this->nu_altPCAP->LinkCustomAttributes = "";
			$this->nu_altPCAP->HrefValue = "";
			$this->nu_altPCAP->TooltipValue = "";

			// nu_altPCON
			$this->nu_altPCON->LinkCustomAttributes = "";
			$this->nu_altPCON->HrefValue = "";
			$this->nu_altPCON->TooltipValue = "";

			// nu_altAPEX
			$this->nu_altAPEX->LinkCustomAttributes = "";
			$this->nu_altAPEX->HrefValue = "";
			$this->nu_altAPEX->TooltipValue = "";

			// nu_altPLEX
			$this->nu_altPLEX->LinkCustomAttributes = "";
			$this->nu_altPLEX->HrefValue = "";
			$this->nu_altPLEX->TooltipValue = "";

			// nu_altLTEX
			$this->nu_altLTEX->LinkCustomAttributes = "";
			$this->nu_altLTEX->HrefValue = "";
			$this->nu_altLTEX->TooltipValue = "";

			// nu_altTOOL
			$this->nu_altTOOL->LinkCustomAttributes = "";
			$this->nu_altTOOL->HrefValue = "";
			$this->nu_altTOOL->TooltipValue = "";

			// nu_altSITE
			$this->nu_altSITE->LinkCustomAttributes = "";
			$this->nu_altSITE->HrefValue = "";
			$this->nu_altSITE->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// nu_solMetricas
			$this->nu_solMetricas->EditCustomAttributes = "";
			if ($this->nu_solMetricas->getSessionValue() <> "") {
				$this->nu_solMetricas->CurrentValue = $this->nu_solMetricas->getSessionValue();
			if (strval($this->nu_solMetricas->CurrentValue) <> "") {
				$sFilterWrk = "[nu_solMetricas]" . ew_SearchString("=", $this->nu_solMetricas->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_solMetricas], [nu_solMetricas] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[solicitacaoMetricas]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_solMetricas, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_solMetricas] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_solMetricas->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_solMetricas->ViewValue = $this->nu_solMetricas->CurrentValue;
				}
			} else {
				$this->nu_solMetricas->ViewValue = NULL;
			}
			$this->nu_solMetricas->ViewCustomAttributes = "";
			} else {
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_solMetricas], [nu_solMetricas] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[solicitacaoMetricas]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_solMetricas, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_solMetricas] DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_solMetricas->EditValue = $arwrk;
			}

			// nu_estimativa
			$this->nu_estimativa->EditCustomAttributes = "";
			$this->nu_estimativa->EditValue = $this->nu_estimativa->CurrentValue;
			$this->nu_estimativa->ViewCustomAttributes = "";

			// ic_solicitacaoCritica
			$this->ic_solicitacaoCritica->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_solicitacaoCritica->FldTagValue(1), $this->ic_solicitacaoCritica->FldTagCaption(1) <> "" ? $this->ic_solicitacaoCritica->FldTagCaption(1) : $this->ic_solicitacaoCritica->FldTagValue(1));
			$arwrk[] = array($this->ic_solicitacaoCritica->FldTagValue(2), $this->ic_solicitacaoCritica->FldTagCaption(2) <> "" ? $this->ic_solicitacaoCritica->FldTagCaption(2) : $this->ic_solicitacaoCritica->FldTagValue(2));
			$this->ic_solicitacaoCritica->EditValue = $arwrk;

			// nu_ambienteMaisRepresentativo
			$this->nu_ambienteMaisRepresentativo->EditCustomAttributes = "";
			$this->nu_ambienteMaisRepresentativo->EditValue = ew_HtmlEncode($this->nu_ambienteMaisRepresentativo->CurrentValue);
			if (strval($this->nu_ambienteMaisRepresentativo->CurrentValue) <> "") {
				$sFilterWrk = "[nu_ambiente]" . ew_SearchString("=", $this->nu_ambienteMaisRepresentativo->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_ambiente], [no_ambiente] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ambiente]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_ambienteMaisRepresentativo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_ambiente] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_ambienteMaisRepresentativo->EditValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_ambienteMaisRepresentativo->EditValue = $this->nu_ambienteMaisRepresentativo->CurrentValue;
				}
			} else {
				$this->nu_ambienteMaisRepresentativo->EditValue = NULL;
			}
			$this->nu_ambienteMaisRepresentativo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_ambienteMaisRepresentativo->FldCaption()));

			// qt_tamBase
			$this->qt_tamBase->EditCustomAttributes = "readonly";
			$this->qt_tamBase->EditValue = ew_HtmlEncode($this->qt_tamBase->CurrentValue);
			$this->qt_tamBase->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_tamBase->FldCaption()));
			if (strval($this->qt_tamBase->EditValue) <> "" && is_numeric($this->qt_tamBase->EditValue)) $this->qt_tamBase->EditValue = ew_FormatNumber($this->qt_tamBase->EditValue, -2, -1, -2, 0);

			// ic_modeloCocomo
			$this->ic_modeloCocomo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_modeloCocomo->FldTagValue(1), $this->ic_modeloCocomo->FldTagCaption(1) <> "" ? $this->ic_modeloCocomo->FldTagCaption(1) : $this->ic_modeloCocomo->FldTagValue(1));
			$arwrk[] = array($this->ic_modeloCocomo->FldTagValue(2), $this->ic_modeloCocomo->FldTagCaption(2) <> "" ? $this->ic_modeloCocomo->FldTagCaption(2) : $this->ic_modeloCocomo->FldTagValue(2));
			$this->ic_modeloCocomo->EditValue = $arwrk;

			// nu_metPrazo
			$this->nu_metPrazo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->nu_metPrazo->FldTagValue(1), $this->nu_metPrazo->FldTagCaption(1) <> "" ? $this->nu_metPrazo->FldTagCaption(1) : $this->nu_metPrazo->FldTagValue(1));
			$arwrk[] = array($this->nu_metPrazo->FldTagValue(2), $this->nu_metPrazo->FldTagCaption(2) <> "" ? $this->nu_metPrazo->FldTagCaption(2) : $this->nu_metPrazo->FldTagValue(2));
			$arwrk[] = array($this->nu_metPrazo->FldTagValue(3), $this->nu_metPrazo->FldTagCaption(3) <> "" ? $this->nu_metPrazo->FldTagCaption(3) : $this->nu_metPrazo->FldTagValue(3));
			$arwrk[] = array($this->nu_metPrazo->FldTagValue(4), $this->nu_metPrazo->FldTagCaption(4) <> "" ? $this->nu_metPrazo->FldTagCaption(4) : $this->nu_metPrazo->FldTagValue(4));
			$arwrk[] = array($this->nu_metPrazo->FldTagValue(5), $this->nu_metPrazo->FldTagCaption(5) <> "" ? $this->nu_metPrazo->FldTagCaption(5) : $this->nu_metPrazo->FldTagValue(5));
			$this->nu_metPrazo->EditValue = $arwrk;

			// vr_doPf
			$this->vr_doPf->EditCustomAttributes = "";
			$this->vr_doPf->EditValue = ew_HtmlEncode($this->vr_doPf->CurrentValue);
			$this->vr_doPf->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_doPf->FldCaption()));

			// pz_estimadoMeses
			$this->pz_estimadoMeses->EditCustomAttributes = "readonly";
			$this->pz_estimadoMeses->EditValue = ew_HtmlEncode($this->pz_estimadoMeses->CurrentValue);
			$this->pz_estimadoMeses->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->pz_estimadoMeses->FldCaption()));
			if (strval($this->pz_estimadoMeses->EditValue) <> "" && is_numeric($this->pz_estimadoMeses->EditValue)) $this->pz_estimadoMeses->EditValue = ew_FormatNumber($this->pz_estimadoMeses->EditValue, -2, -1, -2, 0);

			// pz_estimadoDias
			$this->pz_estimadoDias->EditCustomAttributes = "readonly";
			$this->pz_estimadoDias->EditValue = ew_HtmlEncode($this->pz_estimadoDias->CurrentValue);
			$this->pz_estimadoDias->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->pz_estimadoDias->FldCaption()));
			if (strval($this->pz_estimadoDias->EditValue) <> "" && is_numeric($this->pz_estimadoDias->EditValue)) $this->pz_estimadoDias->EditValue = ew_FormatNumber($this->pz_estimadoDias->EditValue, -2, -1, -2, 0);

			// vr_ipMaximo
			$this->vr_ipMaximo->EditCustomAttributes = "readonly";
			$this->vr_ipMaximo->EditValue = ew_HtmlEncode($this->vr_ipMaximo->CurrentValue);
			$this->vr_ipMaximo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_ipMaximo->FldCaption()));
			if (strval($this->vr_ipMaximo->EditValue) <> "" && is_numeric($this->vr_ipMaximo->EditValue)) $this->vr_ipMaximo->EditValue = ew_FormatNumber($this->vr_ipMaximo->EditValue, -2, -1, -2, 0);

			// vr_ipMedio
			$this->vr_ipMedio->EditCustomAttributes = "readonly";
			$this->vr_ipMedio->EditValue = ew_HtmlEncode($this->vr_ipMedio->CurrentValue);
			$this->vr_ipMedio->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_ipMedio->FldCaption()));
			if (strval($this->vr_ipMedio->EditValue) <> "" && is_numeric($this->vr_ipMedio->EditValue)) $this->vr_ipMedio->EditValue = ew_FormatNumber($this->vr_ipMedio->EditValue, -2, -1, -2, 0);

			// vr_ipMinimo
			$this->vr_ipMinimo->EditCustomAttributes = "readonly";
			$this->vr_ipMinimo->EditValue = ew_HtmlEncode($this->vr_ipMinimo->CurrentValue);
			$this->vr_ipMinimo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_ipMinimo->FldCaption()));
			if (strval($this->vr_ipMinimo->EditValue) <> "" && is_numeric($this->vr_ipMinimo->EditValue)) $this->vr_ipMinimo->EditValue = ew_FormatNumber($this->vr_ipMinimo->EditValue, -2, -1, -2, 0);

			// vr_ipInformado
			$this->vr_ipInformado->EditCustomAttributes = "";
			$this->vr_ipInformado->EditValue = ew_HtmlEncode($this->vr_ipInformado->CurrentValue);
			$this->vr_ipInformado->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_ipInformado->FldCaption()));

			// qt_esforco
			$this->qt_esforco->EditCustomAttributes = "readonly";
			$this->qt_esforco->EditValue = ew_HtmlEncode($this->qt_esforco->CurrentValue);
			$this->qt_esforco->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_esforco->FldCaption()));
			if (strval($this->qt_esforco->EditValue) <> "" && is_numeric($this->qt_esforco->EditValue)) $this->qt_esforco->EditValue = ew_FormatNumber($this->qt_esforco->EditValue, -2, -1, -2, 0);

			// vr_custoDesenv
			$this->vr_custoDesenv->EditCustomAttributes = "readonly";
			$this->vr_custoDesenv->EditValue = ew_HtmlEncode($this->vr_custoDesenv->CurrentValue);
			$this->vr_custoDesenv->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_custoDesenv->FldCaption()));
			if (strval($this->vr_custoDesenv->EditValue) <> "" && is_numeric($this->vr_custoDesenv->EditValue)) $this->vr_custoDesenv->EditValue = ew_FormatNumber($this->vr_custoDesenv->EditValue, -2, -1, -2, 0);

			// vr_outrosCustos
			$this->vr_outrosCustos->EditCustomAttributes = "";
			$this->vr_outrosCustos->EditValue = ew_HtmlEncode($this->vr_outrosCustos->CurrentValue);
			$this->vr_outrosCustos->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_outrosCustos->FldCaption()));
			if (strval($this->vr_outrosCustos->EditValue) <> "" && is_numeric($this->vr_outrosCustos->EditValue)) $this->vr_outrosCustos->EditValue = ew_FormatNumber($this->vr_outrosCustos->EditValue, -2, -1, -2, 0);

			// vr_custoTotal
			$this->vr_custoTotal->EditCustomAttributes = "readonly";
			$this->vr_custoTotal->EditValue = ew_HtmlEncode($this->vr_custoTotal->CurrentValue);
			$this->vr_custoTotal->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_custoTotal->FldCaption()));
			if (strval($this->vr_custoTotal->EditValue) <> "" && is_numeric($this->vr_custoTotal->EditValue)) $this->vr_custoTotal->EditValue = ew_FormatNumber($this->vr_custoTotal->EditValue, -2, -1, -2, 0);

			// qt_tamBaseFaturamento
			$this->qt_tamBaseFaturamento->EditCustomAttributes = "readonly";
			$this->qt_tamBaseFaturamento->EditValue = ew_HtmlEncode($this->qt_tamBaseFaturamento->CurrentValue);
			$this->qt_tamBaseFaturamento->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_tamBaseFaturamento->FldCaption()));
			if (strval($this->qt_tamBaseFaturamento->EditValue) <> "" && is_numeric($this->qt_tamBaseFaturamento->EditValue)) $this->qt_tamBaseFaturamento->EditValue = ew_FormatNumber($this->qt_tamBaseFaturamento->EditValue, -2, -1, -2, 0);

			// qt_recursosEquipe
			$this->qt_recursosEquipe->EditCustomAttributes = "readonly";
			$this->qt_recursosEquipe->EditValue = ew_HtmlEncode($this->qt_recursosEquipe->CurrentValue);
			$this->qt_recursosEquipe->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_recursosEquipe->FldCaption()));
			if (strval($this->qt_recursosEquipe->EditValue) <> "" && is_numeric($this->qt_recursosEquipe->EditValue)) $this->qt_recursosEquipe->EditValue = ew_FormatNumber($this->qt_recursosEquipe->EditValue, -2, -1, -2, 0);

			// ds_observacoes
			$this->ds_observacoes->EditCustomAttributes = "";
			$this->ds_observacoes->EditValue = $this->ds_observacoes->CurrentValue;
			$this->ds_observacoes->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_observacoes->FldCaption()));

			// nu_altRELY
			$this->nu_altRELY->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[co_questao]=(select co_quePREC FROM ambiente_valoracao where nu_ambiente = '2' and nu_versaoValoracao = '1') AND [ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altRELY, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altRELY->EditValue = $arwrk;

			// nu_altDATA
			$this->nu_altDATA->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[co_questao]=(select co_queDATA FROM ambiente_valoracao where nu_ambiente = '2' and nu_versaoValoracao = '1') AND [ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altDATA, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altDATA->EditValue = $arwrk;

			// nu_altCPLX1
			$this->nu_altCPLX1->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX1, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altCPLX1->EditValue = $arwrk;

			// nu_altCPLX2
			$this->nu_altCPLX2->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX2, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altCPLX2->EditValue = $arwrk;

			// nu_altCPLX3
			$this->nu_altCPLX3->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX3, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altCPLX3->EditValue = $arwrk;

			// nu_altCPLX4
			$this->nu_altCPLX4->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX4, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altCPLX4->EditValue = $arwrk;

			// nu_altCPLX5
			$this->nu_altCPLX5->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX5, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altCPLX5->EditValue = $arwrk;

			// nu_altDOCU
			$this->nu_altDOCU->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altDOCU, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altDOCU->EditValue = $arwrk;

			// nu_altRUSE
			$this->nu_altRUSE->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altRUSE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altRUSE->EditValue = $arwrk;

			// nu_altTIME
			$this->nu_altTIME->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altTIME, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altTIME->EditValue = $arwrk;

			// nu_altSTOR
			$this->nu_altSTOR->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altSTOR, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altSTOR->EditValue = $arwrk;

			// nu_altPVOL
			$this->nu_altPVOL->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPVOL, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altPVOL->EditValue = $arwrk;

			// nu_altACAP
			$this->nu_altACAP->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altACAP, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altACAP->EditValue = $arwrk;

			// nu_altPCAP
			$this->nu_altPCAP->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPCAP, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altPCAP->EditValue = $arwrk;

			// nu_altPCON
			$this->nu_altPCON->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPCON, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altPCON->EditValue = $arwrk;

			// nu_altAPEX
			$this->nu_altAPEX->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altAPEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altAPEX->EditValue = $arwrk;

			// nu_altPLEX
			$this->nu_altPLEX->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPLEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altPLEX->EditValue = $arwrk;

			// nu_altLTEX
			$this->nu_altLTEX->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altLTEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altLTEX->EditValue = $arwrk;

			// nu_altTOOL
			$this->nu_altTOOL->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altTOOL, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altTOOL->EditValue = $arwrk;

			// nu_altSITE
			$this->nu_altSITE->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altSITE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altSITE->EditValue = $arwrk;

			// Edit refer script
			// nu_solMetricas

			$this->nu_solMetricas->HrefValue = "";

			// nu_estimativa
			$this->nu_estimativa->HrefValue = "";

			// ic_solicitacaoCritica
			$this->ic_solicitacaoCritica->HrefValue = "";

			// nu_ambienteMaisRepresentativo
			$this->nu_ambienteMaisRepresentativo->HrefValue = "";

			// qt_tamBase
			$this->qt_tamBase->HrefValue = "";

			// ic_modeloCocomo
			$this->ic_modeloCocomo->HrefValue = "";

			// nu_metPrazo
			$this->nu_metPrazo->HrefValue = "";

			// vr_doPf
			$this->vr_doPf->HrefValue = "";

			// pz_estimadoMeses
			$this->pz_estimadoMeses->HrefValue = "";

			// pz_estimadoDias
			$this->pz_estimadoDias->HrefValue = "";

			// vr_ipMaximo
			$this->vr_ipMaximo->HrefValue = "";

			// vr_ipMedio
			$this->vr_ipMedio->HrefValue = "";

			// vr_ipMinimo
			$this->vr_ipMinimo->HrefValue = "";

			// vr_ipInformado
			$this->vr_ipInformado->HrefValue = "";

			// qt_esforco
			$this->qt_esforco->HrefValue = "";

			// vr_custoDesenv
			$this->vr_custoDesenv->HrefValue = "";

			// vr_outrosCustos
			$this->vr_outrosCustos->HrefValue = "";

			// vr_custoTotal
			$this->vr_custoTotal->HrefValue = "";

			// qt_tamBaseFaturamento
			$this->qt_tamBaseFaturamento->HrefValue = "";

			// qt_recursosEquipe
			$this->qt_recursosEquipe->HrefValue = "";

			// ds_observacoes
			$this->ds_observacoes->HrefValue = "";

			// nu_altRELY
			$this->nu_altRELY->HrefValue = "";

			// nu_altDATA
			$this->nu_altDATA->HrefValue = "";

			// nu_altCPLX1
			$this->nu_altCPLX1->HrefValue = "";

			// nu_altCPLX2
			$this->nu_altCPLX2->HrefValue = "";

			// nu_altCPLX3
			$this->nu_altCPLX3->HrefValue = "";

			// nu_altCPLX4
			$this->nu_altCPLX4->HrefValue = "";

			// nu_altCPLX5
			$this->nu_altCPLX5->HrefValue = "";

			// nu_altDOCU
			$this->nu_altDOCU->HrefValue = "";

			// nu_altRUSE
			$this->nu_altRUSE->HrefValue = "";

			// nu_altTIME
			$this->nu_altTIME->HrefValue = "";

			// nu_altSTOR
			$this->nu_altSTOR->HrefValue = "";

			// nu_altPVOL
			$this->nu_altPVOL->HrefValue = "";

			// nu_altACAP
			$this->nu_altACAP->HrefValue = "";

			// nu_altPCAP
			$this->nu_altPCAP->HrefValue = "";

			// nu_altPCON
			$this->nu_altPCON->HrefValue = "";

			// nu_altAPEX
			$this->nu_altAPEX->HrefValue = "";

			// nu_altPLEX
			$this->nu_altPLEX->HrefValue = "";

			// nu_altLTEX
			$this->nu_altLTEX->HrefValue = "";

			// nu_altTOOL
			$this->nu_altTOOL->HrefValue = "";

			// nu_altSITE
			$this->nu_altSITE->HrefValue = "";
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
		if (!$this->nu_solMetricas->FldIsDetailKey && !is_null($this->nu_solMetricas->FormValue) && $this->nu_solMetricas->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_solMetricas->FldCaption());
		}
		if ($this->ic_solicitacaoCritica->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_solicitacaoCritica->FldCaption());
		}
		if (!$this->nu_ambienteMaisRepresentativo->FldIsDetailKey && !is_null($this->nu_ambienteMaisRepresentativo->FormValue) && $this->nu_ambienteMaisRepresentativo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_ambienteMaisRepresentativo->FldCaption());
		}
		if (!ew_CheckInteger($this->nu_ambienteMaisRepresentativo->FormValue)) {
			ew_AddMessage($gsFormError, $this->nu_ambienteMaisRepresentativo->FldErrMsg());
		}
		if (!$this->qt_tamBase->FldIsDetailKey && !is_null($this->qt_tamBase->FormValue) && $this->qt_tamBase->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->qt_tamBase->FldCaption());
		}
		if (!ew_CheckNumber($this->qt_tamBase->FormValue)) {
			ew_AddMessage($gsFormError, $this->qt_tamBase->FldErrMsg());
		}
		if ($this->nu_metPrazo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_metPrazo->FldCaption());
		}
		if (!ew_CheckInteger($this->vr_doPf->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_doPf->FldErrMsg());
		}
		if (!ew_CheckNumber($this->pz_estimadoMeses->FormValue)) {
			ew_AddMessage($gsFormError, $this->pz_estimadoMeses->FldErrMsg());
		}
		if (!ew_CheckNumber($this->pz_estimadoDias->FormValue)) {
			ew_AddMessage($gsFormError, $this->pz_estimadoDias->FldErrMsg());
		}
		if (!ew_CheckNumber($this->vr_ipMaximo->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_ipMaximo->FldErrMsg());
		}
		if (!ew_CheckNumber($this->vr_ipMedio->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_ipMedio->FldErrMsg());
		}
		if (!ew_CheckNumber($this->vr_ipMinimo->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_ipMinimo->FldErrMsg());
		}
		if (!$this->vr_ipInformado->FldIsDetailKey && !is_null($this->vr_ipInformado->FormValue) && $this->vr_ipInformado->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->vr_ipInformado->FldCaption());
		}
		if (!ew_CheckInteger($this->vr_ipInformado->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_ipInformado->FldErrMsg());
		}
		if (!ew_CheckNumber($this->qt_esforco->FormValue)) {
			ew_AddMessage($gsFormError, $this->qt_esforco->FldErrMsg());
		}
		if (!ew_CheckNumber($this->vr_custoDesenv->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_custoDesenv->FldErrMsg());
		}
		if (!ew_CheckNumber($this->vr_outrosCustos->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_outrosCustos->FldErrMsg());
		}
		if (!ew_CheckNumber($this->vr_custoTotal->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_custoTotal->FldErrMsg());
		}
		if (!ew_CheckNumber($this->qt_tamBaseFaturamento->FormValue)) {
			ew_AddMessage($gsFormError, $this->qt_tamBaseFaturamento->FldErrMsg());
		}
		if (!ew_CheckNumber($this->qt_recursosEquipe->FormValue)) {
			ew_AddMessage($gsFormError, $this->qt_recursosEquipe->FldErrMsg());
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

			// nu_solMetricas
			$this->nu_solMetricas->SetDbValueDef($rsnew, $this->nu_solMetricas->CurrentValue, NULL, $this->nu_solMetricas->ReadOnly);

			// ic_solicitacaoCritica
			$this->ic_solicitacaoCritica->SetDbValueDef($rsnew, $this->ic_solicitacaoCritica->CurrentValue, NULL, $this->ic_solicitacaoCritica->ReadOnly);

			// nu_ambienteMaisRepresentativo
			$this->nu_ambienteMaisRepresentativo->SetDbValueDef($rsnew, $this->nu_ambienteMaisRepresentativo->CurrentValue, NULL, $this->nu_ambienteMaisRepresentativo->ReadOnly);

			// qt_tamBase
			$this->qt_tamBase->SetDbValueDef($rsnew, $this->qt_tamBase->CurrentValue, NULL, $this->qt_tamBase->ReadOnly);

			// ic_modeloCocomo
			$this->ic_modeloCocomo->SetDbValueDef($rsnew, $this->ic_modeloCocomo->CurrentValue, NULL, $this->ic_modeloCocomo->ReadOnly);

			// nu_metPrazo
			$this->nu_metPrazo->SetDbValueDef($rsnew, $this->nu_metPrazo->CurrentValue, NULL, $this->nu_metPrazo->ReadOnly);

			// vr_doPf
			$this->vr_doPf->SetDbValueDef($rsnew, $this->vr_doPf->CurrentValue, NULL, $this->vr_doPf->ReadOnly);

			// pz_estimadoMeses
			$this->pz_estimadoMeses->SetDbValueDef($rsnew, $this->pz_estimadoMeses->CurrentValue, NULL, $this->pz_estimadoMeses->ReadOnly);

			// pz_estimadoDias
			$this->pz_estimadoDias->SetDbValueDef($rsnew, $this->pz_estimadoDias->CurrentValue, NULL, $this->pz_estimadoDias->ReadOnly);

			// vr_ipMaximo
			$this->vr_ipMaximo->SetDbValueDef($rsnew, $this->vr_ipMaximo->CurrentValue, NULL, $this->vr_ipMaximo->ReadOnly);

			// vr_ipMedio
			$this->vr_ipMedio->SetDbValueDef($rsnew, $this->vr_ipMedio->CurrentValue, NULL, $this->vr_ipMedio->ReadOnly);

			// vr_ipMinimo
			$this->vr_ipMinimo->SetDbValueDef($rsnew, $this->vr_ipMinimo->CurrentValue, NULL, $this->vr_ipMinimo->ReadOnly);

			// vr_ipInformado
			$this->vr_ipInformado->SetDbValueDef($rsnew, $this->vr_ipInformado->CurrentValue, NULL, $this->vr_ipInformado->ReadOnly);

			// qt_esforco
			$this->qt_esforco->SetDbValueDef($rsnew, $this->qt_esforco->CurrentValue, NULL, $this->qt_esforco->ReadOnly);

			// vr_custoDesenv
			$this->vr_custoDesenv->SetDbValueDef($rsnew, $this->vr_custoDesenv->CurrentValue, NULL, $this->vr_custoDesenv->ReadOnly);

			// vr_outrosCustos
			$this->vr_outrosCustos->SetDbValueDef($rsnew, $this->vr_outrosCustos->CurrentValue, NULL, $this->vr_outrosCustos->ReadOnly);

			// vr_custoTotal
			$this->vr_custoTotal->SetDbValueDef($rsnew, $this->vr_custoTotal->CurrentValue, NULL, $this->vr_custoTotal->ReadOnly);

			// qt_tamBaseFaturamento
			$this->qt_tamBaseFaturamento->SetDbValueDef($rsnew, $this->qt_tamBaseFaturamento->CurrentValue, NULL, $this->qt_tamBaseFaturamento->ReadOnly);

			// qt_recursosEquipe
			$this->qt_recursosEquipe->SetDbValueDef($rsnew, $this->qt_recursosEquipe->CurrentValue, NULL, $this->qt_recursosEquipe->ReadOnly);

			// ds_observacoes
			$this->ds_observacoes->SetDbValueDef($rsnew, $this->ds_observacoes->CurrentValue, NULL, $this->ds_observacoes->ReadOnly);

			// nu_altRELY
			$this->nu_altRELY->SetDbValueDef($rsnew, $this->nu_altRELY->CurrentValue, NULL, $this->nu_altRELY->ReadOnly);

			// nu_altDATA
			$this->nu_altDATA->SetDbValueDef($rsnew, $this->nu_altDATA->CurrentValue, NULL, $this->nu_altDATA->ReadOnly);

			// nu_altCPLX1
			$this->nu_altCPLX1->SetDbValueDef($rsnew, $this->nu_altCPLX1->CurrentValue, NULL, $this->nu_altCPLX1->ReadOnly);

			// nu_altCPLX2
			$this->nu_altCPLX2->SetDbValueDef($rsnew, $this->nu_altCPLX2->CurrentValue, NULL, $this->nu_altCPLX2->ReadOnly);

			// nu_altCPLX3
			$this->nu_altCPLX3->SetDbValueDef($rsnew, $this->nu_altCPLX3->CurrentValue, NULL, $this->nu_altCPLX3->ReadOnly);

			// nu_altCPLX4
			$this->nu_altCPLX4->SetDbValueDef($rsnew, $this->nu_altCPLX4->CurrentValue, NULL, $this->nu_altCPLX4->ReadOnly);

			// nu_altCPLX5
			$this->nu_altCPLX5->SetDbValueDef($rsnew, $this->nu_altCPLX5->CurrentValue, NULL, $this->nu_altCPLX5->ReadOnly);

			// nu_altDOCU
			$this->nu_altDOCU->SetDbValueDef($rsnew, $this->nu_altDOCU->CurrentValue, NULL, $this->nu_altDOCU->ReadOnly);

			// nu_altRUSE
			$this->nu_altRUSE->SetDbValueDef($rsnew, $this->nu_altRUSE->CurrentValue, NULL, $this->nu_altRUSE->ReadOnly);

			// nu_altTIME
			$this->nu_altTIME->SetDbValueDef($rsnew, $this->nu_altTIME->CurrentValue, NULL, $this->nu_altTIME->ReadOnly);

			// nu_altSTOR
			$this->nu_altSTOR->SetDbValueDef($rsnew, $this->nu_altSTOR->CurrentValue, NULL, $this->nu_altSTOR->ReadOnly);

			// nu_altPVOL
			$this->nu_altPVOL->SetDbValueDef($rsnew, $this->nu_altPVOL->CurrentValue, NULL, $this->nu_altPVOL->ReadOnly);

			// nu_altACAP
			$this->nu_altACAP->SetDbValueDef($rsnew, $this->nu_altACAP->CurrentValue, NULL, $this->nu_altACAP->ReadOnly);

			// nu_altPCAP
			$this->nu_altPCAP->SetDbValueDef($rsnew, $this->nu_altPCAP->CurrentValue, NULL, $this->nu_altPCAP->ReadOnly);

			// nu_altPCON
			$this->nu_altPCON->SetDbValueDef($rsnew, $this->nu_altPCON->CurrentValue, NULL, $this->nu_altPCON->ReadOnly);

			// nu_altAPEX
			$this->nu_altAPEX->SetDbValueDef($rsnew, $this->nu_altAPEX->CurrentValue, NULL, $this->nu_altAPEX->ReadOnly);

			// nu_altPLEX
			$this->nu_altPLEX->SetDbValueDef($rsnew, $this->nu_altPLEX->CurrentValue, NULL, $this->nu_altPLEX->ReadOnly);

			// nu_altLTEX
			$this->nu_altLTEX->SetDbValueDef($rsnew, $this->nu_altLTEX->CurrentValue, NULL, $this->nu_altLTEX->ReadOnly);

			// nu_altTOOL
			$this->nu_altTOOL->SetDbValueDef($rsnew, $this->nu_altTOOL->CurrentValue, NULL, $this->nu_altTOOL->ReadOnly);

			// nu_altSITE
			$this->nu_altSITE->SetDbValueDef($rsnew, $this->nu_altSITE->CurrentValue, NULL, $this->nu_altSITE->ReadOnly);

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
		if ($EditRow) {
			$this->WriteAuditTrailOnEdit($rsold, $rsnew);
		}
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
			if ($sMasterTblVar == "solicitacaoMetricas") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_solMetricas"] <> "") {
					$GLOBALS["solicitacaoMetricas"]->nu_solMetricas->setQueryStringValue($_GET["nu_solMetricas"]);
					$this->nu_solMetricas->setQueryStringValue($GLOBALS["solicitacaoMetricas"]->nu_solMetricas->QueryStringValue);
					$this->nu_solMetricas->setSessionValue($this->nu_solMetricas->QueryStringValue);
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
				if ($this->nu_solMetricas->QueryStringValue == "") $this->nu_solMetricas->setSessionValue("");
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "estimativalist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("edit");
		$Breadcrumb->Add("edit", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'estimativa';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		if (!$this->AuditTrailOnEdit) return;
		$table = 'estimativa';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['nu_estimativa'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
	  $usr = CurrentUserID();
		foreach (array_keys($rsnew) as $fldname) {
			if ($this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_DATE) { // DateTime field
					$modified = (ew_FormatDateTime($rsold[$fldname], 0) <> ew_FormatDateTime($rsnew[$fldname], 0));
				} else {
					$modified = !ew_CompareValue($rsold[$fldname], $rsnew[$fldname]);
				}
				if ($modified) {
					if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) { // Memo field
						if (EW_AUDIT_TRAIL_TO_DATABASE) {
							$oldvalue = $rsold[$fldname];
							$newvalue = $rsnew[$fldname];
						} else {
							$oldvalue = "[MEMO]";
							$newvalue = "[MEMO]";
						}
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) { // XML field
						$oldvalue = "[XML]";
						$newvalue = "[XML]";
					} else {
						$oldvalue = $rsold[$fldname];
						$newvalue = $rsnew[$fldname];
					}
					ew_WriteAuditTrail("log", $dt, $id, $usr, "U", $table, $fldname, $key, $oldvalue, $newvalue);
				}
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
if (!isset($estimativa_edit)) $estimativa_edit = new cestimativa_edit();

// Page init
$estimativa_edit->Page_Init();

// Page main
$estimativa_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$estimativa_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var estimativa_edit = new ew_Page("estimativa_edit");
estimativa_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = estimativa_edit.PageID; // For backward compatibility

// Form object
var festimativaedit = new ew_Form("festimativaedit");

// Validate form
festimativaedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_solMetricas");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($estimativa->nu_solMetricas->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_solicitacaoCritica");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($estimativa->ic_solicitacaoCritica->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_ambienteMaisRepresentativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($estimativa->nu_ambienteMaisRepresentativo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_ambienteMaisRepresentativo");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($estimativa->nu_ambienteMaisRepresentativo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_qt_tamBase");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($estimativa->qt_tamBase->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_qt_tamBase");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($estimativa->qt_tamBase->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nu_metPrazo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($estimativa->nu_metPrazo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_doPf");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($estimativa->vr_doPf->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_pz_estimadoMeses");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($estimativa->pz_estimadoMeses->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_pz_estimadoDias");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($estimativa->pz_estimadoDias->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vr_ipMaximo");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($estimativa->vr_ipMaximo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vr_ipMedio");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($estimativa->vr_ipMedio->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vr_ipMinimo");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($estimativa->vr_ipMinimo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vr_ipInformado");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($estimativa->vr_ipInformado->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_ipInformado");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($estimativa->vr_ipInformado->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_qt_esforco");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($estimativa->qt_esforco->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vr_custoDesenv");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($estimativa->vr_custoDesenv->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vr_outrosCustos");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($estimativa->vr_outrosCustos->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vr_custoTotal");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($estimativa->vr_custoTotal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_qt_tamBaseFaturamento");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($estimativa->qt_tamBaseFaturamento->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_qt_recursosEquipe");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($estimativa->qt_recursosEquipe->FldErrMsg()) ?>");

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
festimativaedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
festimativaedit.ValidateRequired = true;
<?php } else { ?>
festimativaedit.ValidateRequired = false; 
<?php } ?>

// Multi-Page properties
festimativaedit.MultiPage = new ew_MultiPage("festimativaedit",
	[["x_nu_solMetricas",1],["x_nu_estimativa",1],["x_ic_solicitacaoCritica",1],["x_nu_ambienteMaisRepresentativo",1],["x_qt_tamBase",1],["x_ic_modeloCocomo",1],["x_nu_metPrazo",1],["x_vr_doPf",1],["x_pz_estimadoMeses",3],["x_pz_estimadoDias",3],["x_vr_ipMaximo",3],["x_vr_ipMedio",3],["x_vr_ipMinimo",3],["x_vr_ipInformado",3],["x_qt_esforco",3],["x_vr_custoDesenv",3],["x_vr_outrosCustos",3],["x_vr_custoTotal",3],["x_qt_tamBaseFaturamento",3],["x_qt_recursosEquipe",3],["x_ds_observacoes",3],["x_nu_altRELY",2],["x_nu_altDATA",2],["x_nu_altCPLX1",2],["x_nu_altCPLX2",2],["x_nu_altCPLX3",2],["x_nu_altCPLX4",2],["x_nu_altCPLX5",2],["x_nu_altDOCU",2],["x_nu_altRUSE",2],["x_nu_altTIME",2],["x_nu_altSTOR",2],["x_nu_altPVOL",2],["x_nu_altACAP",2],["x_nu_altPCAP",2],["x_nu_altPCON",2],["x_nu_altAPEX",2],["x_nu_altPLEX",2],["x_nu_altLTEX",2],["x_nu_altTOOL",2],["x_nu_altSITE",2]]
);

// Dynamic selection lists
festimativaedit.Lists["x_nu_solMetricas"] = {"LinkField":"x_nu_solMetricas","Ajax":null,"AutoFill":false,"DisplayFields":["x_nu_solMetricas","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaedit.Lists["x_nu_ambienteMaisRepresentativo"] = {"LinkField":"x_nu_ambiente","Ajax":true,"AutoFill":false,"DisplayFields":["x_no_ambiente","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaedit.Lists["x_nu_altRELY"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaedit.Lists["x_nu_altDATA"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaedit.Lists["x_nu_altCPLX1"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaedit.Lists["x_nu_altCPLX2"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaedit.Lists["x_nu_altCPLX3"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaedit.Lists["x_nu_altCPLX4"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaedit.Lists["x_nu_altCPLX5"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaedit.Lists["x_nu_altDOCU"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaedit.Lists["x_nu_altRUSE"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaedit.Lists["x_nu_altTIME"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaedit.Lists["x_nu_altSTOR"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaedit.Lists["x_nu_altPVOL"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaedit.Lists["x_nu_altACAP"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaedit.Lists["x_nu_altPCAP"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaedit.Lists["x_nu_altPCON"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaedit.Lists["x_nu_altAPEX"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaedit.Lists["x_nu_altPLEX"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaedit.Lists["x_nu_altLTEX"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaedit.Lists["x_nu_altTOOL"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaedit.Lists["x_nu_altSITE"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $estimativa_edit->ShowPageHeader(); ?>
<?php
$estimativa_edit->ShowMessage();
?>
<form name="festimativaedit" id="festimativaedit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="estimativa">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table class="ewStdTable"><tbody><tr><td>
<div class="tabbable" id="estimativa_edit">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_estimativa1" data-toggle="tab"><?php echo $estimativa->PageCaption(1) ?></a></li>
		<li><a href="#tab_estimativa2" data-toggle="tab"><?php echo $estimativa->PageCaption(2) ?></a></li>
		<li><a href="#tab_estimativa3" data-toggle="tab"><?php echo $estimativa->PageCaption(3) ?></a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab_estimativa1">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_estimativaedit1" class="table table-bordered table-striped">
<?php if ($estimativa->nu_solMetricas->Visible) { // nu_solMetricas ?>
	<tr id="r_nu_solMetricas">
		<td><span id="elh_estimativa_nu_solMetricas"><?php echo $estimativa->nu_solMetricas->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $estimativa->nu_solMetricas->CellAttributes() ?>>
<?php if ($estimativa->nu_solMetricas->getSessionValue() <> "") { ?>
<span<?php echo $estimativa->nu_solMetricas->ViewAttributes() ?>>
<?php echo $estimativa->nu_solMetricas->ViewValue ?></span>
<input type="hidden" id="x_nu_solMetricas" name="x_nu_solMetricas" value="<?php echo ew_HtmlEncode($estimativa->nu_solMetricas->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_nu_solMetricas" id="x_nu_solMetricas" name="x_nu_solMetricas"<?php echo $estimativa->nu_solMetricas->EditAttributes() ?>>
<?php
if (is_array($estimativa->nu_solMetricas->EditValue)) {
	$arwrk = $estimativa->nu_solMetricas->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($estimativa->nu_solMetricas->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
festimativaedit.Lists["x_nu_solMetricas"].Options = <?php echo (is_array($estimativa->nu_solMetricas->EditValue)) ? ew_ArrayToJson($estimativa->nu_solMetricas->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<?php echo $estimativa->nu_solMetricas->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_estimativa->Visible) { // nu_estimativa ?>
	<tr id="r_nu_estimativa">
		<td><span id="elh_estimativa_nu_estimativa"><?php echo $estimativa->nu_estimativa->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_estimativa->CellAttributes() ?>>
<span id="el_estimativa_nu_estimativa" class="control-group">
<span<?php echo $estimativa->nu_estimativa->ViewAttributes() ?>>
<?php echo $estimativa->nu_estimativa->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_estimativa" name="x_nu_estimativa" id="x_nu_estimativa" value="<?php echo ew_HtmlEncode($estimativa->nu_estimativa->CurrentValue) ?>">
<?php echo $estimativa->nu_estimativa->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->ic_solicitacaoCritica->Visible) { // ic_solicitacaoCritica ?>
	<tr id="r_ic_solicitacaoCritica">
		<td><span id="elh_estimativa_ic_solicitacaoCritica"><?php echo $estimativa->ic_solicitacaoCritica->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $estimativa->ic_solicitacaoCritica->CellAttributes() ?>>
<span id="el_estimativa_ic_solicitacaoCritica" class="control-group">
<div id="tp_x_ic_solicitacaoCritica" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_solicitacaoCritica" id="x_ic_solicitacaoCritica" value="{value}"<?php echo $estimativa->ic_solicitacaoCritica->EditAttributes() ?>></div>
<div id="dsl_x_ic_solicitacaoCritica" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $estimativa->ic_solicitacaoCritica->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($estimativa->ic_solicitacaoCritica->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_solicitacaoCritica" name="x_ic_solicitacaoCritica" id="x_ic_solicitacaoCritica_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $estimativa->ic_solicitacaoCritica->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $estimativa->ic_solicitacaoCritica->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_ambienteMaisRepresentativo->Visible) { // nu_ambienteMaisRepresentativo ?>
	<tr id="r_nu_ambienteMaisRepresentativo">
		<td><span id="elh_estimativa_nu_ambienteMaisRepresentativo"><?php echo $estimativa->nu_ambienteMaisRepresentativo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $estimativa->nu_ambienteMaisRepresentativo->CellAttributes() ?>>
<span id="el_estimativa_nu_ambienteMaisRepresentativo" class="control-group">
<?php
	$wrkonchange = trim(" " . @$estimativa->nu_ambienteMaisRepresentativo->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$estimativa->nu_ambienteMaisRepresentativo->EditAttrs["onchange"] = "";
?>
<span id="as_x_nu_ambienteMaisRepresentativo" style="white-space: nowrap; z-index: 8960">
	<input type="text" name="sv_x_nu_ambienteMaisRepresentativo" id="sv_x_nu_ambienteMaisRepresentativo" value="<?php echo $estimativa->nu_ambienteMaisRepresentativo->EditValue ?>" size="30" placeholder="<?php echo $estimativa->nu_ambienteMaisRepresentativo->PlaceHolder ?>"<?php echo $estimativa->nu_ambienteMaisRepresentativo->EditAttributes() ?>>&nbsp;<span id="em_x_nu_ambienteMaisRepresentativo" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_nu_ambienteMaisRepresentativo" style="display: inline; z-index: 8960"></div>
</span>
<input type="hidden" data-field="x_nu_ambienteMaisRepresentativo" name="x_nu_ambienteMaisRepresentativo" id="x_nu_ambienteMaisRepresentativo" value="<?php echo $estimativa->nu_ambienteMaisRepresentativo->CurrentValue ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT  TOP " . EW_AUTO_SUGGEST_MAX_ENTRIES . " [nu_ambiente], [no_ambiente] AS [DispFld] FROM [dbo].[ambiente]";
$sWhereWrk = "[no_ambiente] LIKE '%{query_value}%'";
$lookuptblfilter = "[ic_ativo]='S'";
if (strval($lookuptblfilter) <> "") {
	ew_AddFilter($sWhereWrk, $lookuptblfilter);
}

// Call Lookup selecting
$estimativa->Lookup_Selecting($estimativa->nu_ambienteMaisRepresentativo, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY [no_ambiente] ASC";
?>
<input type="hidden" name="q_x_nu_ambienteMaisRepresentativo" id="q_x_nu_ambienteMaisRepresentativo" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x_nu_ambienteMaisRepresentativo", festimativaedit, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_nu_ambienteMaisRepresentativo") + ar[i] : "";
	return dv;
}
festimativaedit.AutoSuggests["x_nu_ambienteMaisRepresentativo"] = oas;
</script>
</span>
<?php echo $estimativa->nu_ambienteMaisRepresentativo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->qt_tamBase->Visible) { // qt_tamBase ?>
	<tr id="r_qt_tamBase">
		<td><span id="elh_estimativa_qt_tamBase"><?php echo $estimativa->qt_tamBase->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $estimativa->qt_tamBase->CellAttributes() ?>>
<span id="el_estimativa_qt_tamBase" class="control-group">
<input type="text" data-field="x_qt_tamBase" name="x_qt_tamBase" id="x_qt_tamBase" size="30" placeholder="<?php echo $estimativa->qt_tamBase->PlaceHolder ?>" value="<?php echo $estimativa->qt_tamBase->EditValue ?>"<?php echo $estimativa->qt_tamBase->EditAttributes() ?>>
</span>
<?php echo $estimativa->qt_tamBase->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->ic_modeloCocomo->Visible) { // ic_modeloCocomo ?>
	<tr id="r_ic_modeloCocomo">
		<td><span id="elh_estimativa_ic_modeloCocomo"><?php echo $estimativa->ic_modeloCocomo->FldCaption() ?></span></td>
		<td<?php echo $estimativa->ic_modeloCocomo->CellAttributes() ?>>
<span id="el_estimativa_ic_modeloCocomo" class="control-group">
<div id="tp_x_ic_modeloCocomo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_modeloCocomo" id="x_ic_modeloCocomo" value="{value}"<?php echo $estimativa->ic_modeloCocomo->EditAttributes() ?>></div>
<div id="dsl_x_ic_modeloCocomo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $estimativa->ic_modeloCocomo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($estimativa->ic_modeloCocomo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_modeloCocomo" name="x_ic_modeloCocomo" id="x_ic_modeloCocomo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $estimativa->ic_modeloCocomo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $estimativa->ic_modeloCocomo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_metPrazo->Visible) { // nu_metPrazo ?>
	<tr id="r_nu_metPrazo">
		<td><span id="elh_estimativa_nu_metPrazo"><?php echo $estimativa->nu_metPrazo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $estimativa->nu_metPrazo->CellAttributes() ?>>
<span id="el_estimativa_nu_metPrazo" class="control-group">
<div id="tp_x_nu_metPrazo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_nu_metPrazo" id="x_nu_metPrazo" value="{value}"<?php echo $estimativa->nu_metPrazo->EditAttributes() ?>></div>
<div id="dsl_x_nu_metPrazo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $estimativa->nu_metPrazo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($estimativa->nu_metPrazo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_nu_metPrazo" name="x_nu_metPrazo" id="x_nu_metPrazo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $estimativa->nu_metPrazo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $estimativa->nu_metPrazo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->vr_doPf->Visible) { // vr_doPf ?>
	<tr id="r_vr_doPf">
		<td><span id="elh_estimativa_vr_doPf"><?php echo $estimativa->vr_doPf->FldCaption() ?></span></td>
		<td<?php echo $estimativa->vr_doPf->CellAttributes() ?>>
<span id="el_estimativa_vr_doPf" class="control-group">
<input type="text" data-field="x_vr_doPf" name="x_vr_doPf" id="x_vr_doPf" size="30" placeholder="<?php echo $estimativa->vr_doPf->PlaceHolder ?>" value="<?php echo $estimativa->vr_doPf->EditValue ?>"<?php echo $estimativa->vr_doPf->EditAttributes() ?>>
</span>
<?php echo $estimativa->vr_doPf->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_estimativa2">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_estimativaedit2" class="table table-bordered table-striped">
<?php if ($estimativa->nu_altRELY->Visible) { // nu_altRELY ?>
	<tr id="r_nu_altRELY">
		<td><span id="elh_estimativa_nu_altRELY"><?php echo $estimativa->nu_altRELY->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altRELY->CellAttributes() ?>>
<span id="el_estimativa_nu_altRELY" class="control-group">
<select data-field="x_nu_altRELY" id="x_nu_altRELY" name="x_nu_altRELY"<?php echo $estimativa->nu_altRELY->EditAttributes() ?>>
<?php
if (is_array($estimativa->nu_altRELY->EditValue)) {
	$arwrk = $estimativa->nu_altRELY->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($estimativa->nu_altRELY->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
festimativaedit.Lists["x_nu_altRELY"].Options = <?php echo (is_array($estimativa->nu_altRELY->EditValue)) ? ew_ArrayToJson($estimativa->nu_altRELY->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $estimativa->nu_altRELY->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altDATA->Visible) { // nu_altDATA ?>
	<tr id="r_nu_altDATA">
		<td><span id="elh_estimativa_nu_altDATA"><?php echo $estimativa->nu_altDATA->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altDATA->CellAttributes() ?>>
<span id="el_estimativa_nu_altDATA" class="control-group">
<select data-field="x_nu_altDATA" id="x_nu_altDATA" name="x_nu_altDATA"<?php echo $estimativa->nu_altDATA->EditAttributes() ?>>
<?php
if (is_array($estimativa->nu_altDATA->EditValue)) {
	$arwrk = $estimativa->nu_altDATA->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($estimativa->nu_altDATA->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
festimativaedit.Lists["x_nu_altDATA"].Options = <?php echo (is_array($estimativa->nu_altDATA->EditValue)) ? ew_ArrayToJson($estimativa->nu_altDATA->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $estimativa->nu_altDATA->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altCPLX1->Visible) { // nu_altCPLX1 ?>
	<tr id="r_nu_altCPLX1">
		<td><span id="elh_estimativa_nu_altCPLX1"><?php echo $estimativa->nu_altCPLX1->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altCPLX1->CellAttributes() ?>>
<span id="el_estimativa_nu_altCPLX1" class="control-group">
<select data-field="x_nu_altCPLX1" id="x_nu_altCPLX1" name="x_nu_altCPLX1"<?php echo $estimativa->nu_altCPLX1->EditAttributes() ?>>
<?php
if (is_array($estimativa->nu_altCPLX1->EditValue)) {
	$arwrk = $estimativa->nu_altCPLX1->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($estimativa->nu_altCPLX1->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
festimativaedit.Lists["x_nu_altCPLX1"].Options = <?php echo (is_array($estimativa->nu_altCPLX1->EditValue)) ? ew_ArrayToJson($estimativa->nu_altCPLX1->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $estimativa->nu_altCPLX1->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altCPLX2->Visible) { // nu_altCPLX2 ?>
	<tr id="r_nu_altCPLX2">
		<td><span id="elh_estimativa_nu_altCPLX2"><?php echo $estimativa->nu_altCPLX2->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altCPLX2->CellAttributes() ?>>
<span id="el_estimativa_nu_altCPLX2" class="control-group">
<select data-field="x_nu_altCPLX2" id="x_nu_altCPLX2" name="x_nu_altCPLX2"<?php echo $estimativa->nu_altCPLX2->EditAttributes() ?>>
<?php
if (is_array($estimativa->nu_altCPLX2->EditValue)) {
	$arwrk = $estimativa->nu_altCPLX2->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($estimativa->nu_altCPLX2->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
festimativaedit.Lists["x_nu_altCPLX2"].Options = <?php echo (is_array($estimativa->nu_altCPLX2->EditValue)) ? ew_ArrayToJson($estimativa->nu_altCPLX2->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $estimativa->nu_altCPLX2->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altCPLX3->Visible) { // nu_altCPLX3 ?>
	<tr id="r_nu_altCPLX3">
		<td><span id="elh_estimativa_nu_altCPLX3"><?php echo $estimativa->nu_altCPLX3->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altCPLX3->CellAttributes() ?>>
<span id="el_estimativa_nu_altCPLX3" class="control-group">
<select data-field="x_nu_altCPLX3" id="x_nu_altCPLX3" name="x_nu_altCPLX3"<?php echo $estimativa->nu_altCPLX3->EditAttributes() ?>>
<?php
if (is_array($estimativa->nu_altCPLX3->EditValue)) {
	$arwrk = $estimativa->nu_altCPLX3->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($estimativa->nu_altCPLX3->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
festimativaedit.Lists["x_nu_altCPLX3"].Options = <?php echo (is_array($estimativa->nu_altCPLX3->EditValue)) ? ew_ArrayToJson($estimativa->nu_altCPLX3->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $estimativa->nu_altCPLX3->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altCPLX4->Visible) { // nu_altCPLX4 ?>
	<tr id="r_nu_altCPLX4">
		<td><span id="elh_estimativa_nu_altCPLX4"><?php echo $estimativa->nu_altCPLX4->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altCPLX4->CellAttributes() ?>>
<span id="el_estimativa_nu_altCPLX4" class="control-group">
<select data-field="x_nu_altCPLX4" id="x_nu_altCPLX4" name="x_nu_altCPLX4"<?php echo $estimativa->nu_altCPLX4->EditAttributes() ?>>
<?php
if (is_array($estimativa->nu_altCPLX4->EditValue)) {
	$arwrk = $estimativa->nu_altCPLX4->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($estimativa->nu_altCPLX4->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
festimativaedit.Lists["x_nu_altCPLX4"].Options = <?php echo (is_array($estimativa->nu_altCPLX4->EditValue)) ? ew_ArrayToJson($estimativa->nu_altCPLX4->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $estimativa->nu_altCPLX4->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altCPLX5->Visible) { // nu_altCPLX5 ?>
	<tr id="r_nu_altCPLX5">
		<td><span id="elh_estimativa_nu_altCPLX5"><?php echo $estimativa->nu_altCPLX5->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altCPLX5->CellAttributes() ?>>
<span id="el_estimativa_nu_altCPLX5" class="control-group">
<select data-field="x_nu_altCPLX5" id="x_nu_altCPLX5" name="x_nu_altCPLX5"<?php echo $estimativa->nu_altCPLX5->EditAttributes() ?>>
<?php
if (is_array($estimativa->nu_altCPLX5->EditValue)) {
	$arwrk = $estimativa->nu_altCPLX5->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($estimativa->nu_altCPLX5->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
festimativaedit.Lists["x_nu_altCPLX5"].Options = <?php echo (is_array($estimativa->nu_altCPLX5->EditValue)) ? ew_ArrayToJson($estimativa->nu_altCPLX5->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $estimativa->nu_altCPLX5->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altDOCU->Visible) { // nu_altDOCU ?>
	<tr id="r_nu_altDOCU">
		<td><span id="elh_estimativa_nu_altDOCU"><?php echo $estimativa->nu_altDOCU->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altDOCU->CellAttributes() ?>>
<span id="el_estimativa_nu_altDOCU" class="control-group">
<select data-field="x_nu_altDOCU" id="x_nu_altDOCU" name="x_nu_altDOCU"<?php echo $estimativa->nu_altDOCU->EditAttributes() ?>>
<?php
if (is_array($estimativa->nu_altDOCU->EditValue)) {
	$arwrk = $estimativa->nu_altDOCU->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($estimativa->nu_altDOCU->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
festimativaedit.Lists["x_nu_altDOCU"].Options = <?php echo (is_array($estimativa->nu_altDOCU->EditValue)) ? ew_ArrayToJson($estimativa->nu_altDOCU->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $estimativa->nu_altDOCU->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altRUSE->Visible) { // nu_altRUSE ?>
	<tr id="r_nu_altRUSE">
		<td><span id="elh_estimativa_nu_altRUSE"><?php echo $estimativa->nu_altRUSE->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altRUSE->CellAttributes() ?>>
<span id="el_estimativa_nu_altRUSE" class="control-group">
<select data-field="x_nu_altRUSE" id="x_nu_altRUSE" name="x_nu_altRUSE"<?php echo $estimativa->nu_altRUSE->EditAttributes() ?>>
<?php
if (is_array($estimativa->nu_altRUSE->EditValue)) {
	$arwrk = $estimativa->nu_altRUSE->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($estimativa->nu_altRUSE->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
festimativaedit.Lists["x_nu_altRUSE"].Options = <?php echo (is_array($estimativa->nu_altRUSE->EditValue)) ? ew_ArrayToJson($estimativa->nu_altRUSE->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $estimativa->nu_altRUSE->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altTIME->Visible) { // nu_altTIME ?>
	<tr id="r_nu_altTIME">
		<td><span id="elh_estimativa_nu_altTIME"><?php echo $estimativa->nu_altTIME->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altTIME->CellAttributes() ?>>
<span id="el_estimativa_nu_altTIME" class="control-group">
<select data-field="x_nu_altTIME" id="x_nu_altTIME" name="x_nu_altTIME"<?php echo $estimativa->nu_altTIME->EditAttributes() ?>>
<?php
if (is_array($estimativa->nu_altTIME->EditValue)) {
	$arwrk = $estimativa->nu_altTIME->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($estimativa->nu_altTIME->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
festimativaedit.Lists["x_nu_altTIME"].Options = <?php echo (is_array($estimativa->nu_altTIME->EditValue)) ? ew_ArrayToJson($estimativa->nu_altTIME->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $estimativa->nu_altTIME->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altSTOR->Visible) { // nu_altSTOR ?>
	<tr id="r_nu_altSTOR">
		<td><span id="elh_estimativa_nu_altSTOR"><?php echo $estimativa->nu_altSTOR->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altSTOR->CellAttributes() ?>>
<span id="el_estimativa_nu_altSTOR" class="control-group">
<select data-field="x_nu_altSTOR" id="x_nu_altSTOR" name="x_nu_altSTOR"<?php echo $estimativa->nu_altSTOR->EditAttributes() ?>>
<?php
if (is_array($estimativa->nu_altSTOR->EditValue)) {
	$arwrk = $estimativa->nu_altSTOR->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($estimativa->nu_altSTOR->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
festimativaedit.Lists["x_nu_altSTOR"].Options = <?php echo (is_array($estimativa->nu_altSTOR->EditValue)) ? ew_ArrayToJson($estimativa->nu_altSTOR->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $estimativa->nu_altSTOR->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altPVOL->Visible) { // nu_altPVOL ?>
	<tr id="r_nu_altPVOL">
		<td><span id="elh_estimativa_nu_altPVOL"><?php echo $estimativa->nu_altPVOL->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altPVOL->CellAttributes() ?>>
<span id="el_estimativa_nu_altPVOL" class="control-group">
<select data-field="x_nu_altPVOL" id="x_nu_altPVOL" name="x_nu_altPVOL"<?php echo $estimativa->nu_altPVOL->EditAttributes() ?>>
<?php
if (is_array($estimativa->nu_altPVOL->EditValue)) {
	$arwrk = $estimativa->nu_altPVOL->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($estimativa->nu_altPVOL->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
festimativaedit.Lists["x_nu_altPVOL"].Options = <?php echo (is_array($estimativa->nu_altPVOL->EditValue)) ? ew_ArrayToJson($estimativa->nu_altPVOL->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $estimativa->nu_altPVOL->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altACAP->Visible) { // nu_altACAP ?>
	<tr id="r_nu_altACAP">
		<td><span id="elh_estimativa_nu_altACAP"><?php echo $estimativa->nu_altACAP->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altACAP->CellAttributes() ?>>
<span id="el_estimativa_nu_altACAP" class="control-group">
<select data-field="x_nu_altACAP" id="x_nu_altACAP" name="x_nu_altACAP"<?php echo $estimativa->nu_altACAP->EditAttributes() ?>>
<?php
if (is_array($estimativa->nu_altACAP->EditValue)) {
	$arwrk = $estimativa->nu_altACAP->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($estimativa->nu_altACAP->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
festimativaedit.Lists["x_nu_altACAP"].Options = <?php echo (is_array($estimativa->nu_altACAP->EditValue)) ? ew_ArrayToJson($estimativa->nu_altACAP->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $estimativa->nu_altACAP->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altPCAP->Visible) { // nu_altPCAP ?>
	<tr id="r_nu_altPCAP">
		<td><span id="elh_estimativa_nu_altPCAP"><?php echo $estimativa->nu_altPCAP->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altPCAP->CellAttributes() ?>>
<span id="el_estimativa_nu_altPCAP" class="control-group">
<select data-field="x_nu_altPCAP" id="x_nu_altPCAP" name="x_nu_altPCAP"<?php echo $estimativa->nu_altPCAP->EditAttributes() ?>>
<?php
if (is_array($estimativa->nu_altPCAP->EditValue)) {
	$arwrk = $estimativa->nu_altPCAP->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($estimativa->nu_altPCAP->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
festimativaedit.Lists["x_nu_altPCAP"].Options = <?php echo (is_array($estimativa->nu_altPCAP->EditValue)) ? ew_ArrayToJson($estimativa->nu_altPCAP->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $estimativa->nu_altPCAP->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altPCON->Visible) { // nu_altPCON ?>
	<tr id="r_nu_altPCON">
		<td><span id="elh_estimativa_nu_altPCON"><?php echo $estimativa->nu_altPCON->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altPCON->CellAttributes() ?>>
<span id="el_estimativa_nu_altPCON" class="control-group">
<select data-field="x_nu_altPCON" id="x_nu_altPCON" name="x_nu_altPCON"<?php echo $estimativa->nu_altPCON->EditAttributes() ?>>
<?php
if (is_array($estimativa->nu_altPCON->EditValue)) {
	$arwrk = $estimativa->nu_altPCON->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($estimativa->nu_altPCON->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
festimativaedit.Lists["x_nu_altPCON"].Options = <?php echo (is_array($estimativa->nu_altPCON->EditValue)) ? ew_ArrayToJson($estimativa->nu_altPCON->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $estimativa->nu_altPCON->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altAPEX->Visible) { // nu_altAPEX ?>
	<tr id="r_nu_altAPEX">
		<td><span id="elh_estimativa_nu_altAPEX"><?php echo $estimativa->nu_altAPEX->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altAPEX->CellAttributes() ?>>
<span id="el_estimativa_nu_altAPEX" class="control-group">
<select data-field="x_nu_altAPEX" id="x_nu_altAPEX" name="x_nu_altAPEX"<?php echo $estimativa->nu_altAPEX->EditAttributes() ?>>
<?php
if (is_array($estimativa->nu_altAPEX->EditValue)) {
	$arwrk = $estimativa->nu_altAPEX->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($estimativa->nu_altAPEX->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
festimativaedit.Lists["x_nu_altAPEX"].Options = <?php echo (is_array($estimativa->nu_altAPEX->EditValue)) ? ew_ArrayToJson($estimativa->nu_altAPEX->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $estimativa->nu_altAPEX->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altPLEX->Visible) { // nu_altPLEX ?>
	<tr id="r_nu_altPLEX">
		<td><span id="elh_estimativa_nu_altPLEX"><?php echo $estimativa->nu_altPLEX->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altPLEX->CellAttributes() ?>>
<span id="el_estimativa_nu_altPLEX" class="control-group">
<select data-field="x_nu_altPLEX" id="x_nu_altPLEX" name="x_nu_altPLEX"<?php echo $estimativa->nu_altPLEX->EditAttributes() ?>>
<?php
if (is_array($estimativa->nu_altPLEX->EditValue)) {
	$arwrk = $estimativa->nu_altPLEX->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($estimativa->nu_altPLEX->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
festimativaedit.Lists["x_nu_altPLEX"].Options = <?php echo (is_array($estimativa->nu_altPLEX->EditValue)) ? ew_ArrayToJson($estimativa->nu_altPLEX->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $estimativa->nu_altPLEX->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altLTEX->Visible) { // nu_altLTEX ?>
	<tr id="r_nu_altLTEX">
		<td><span id="elh_estimativa_nu_altLTEX"><?php echo $estimativa->nu_altLTEX->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altLTEX->CellAttributes() ?>>
<span id="el_estimativa_nu_altLTEX" class="control-group">
<select data-field="x_nu_altLTEX" id="x_nu_altLTEX" name="x_nu_altLTEX"<?php echo $estimativa->nu_altLTEX->EditAttributes() ?>>
<?php
if (is_array($estimativa->nu_altLTEX->EditValue)) {
	$arwrk = $estimativa->nu_altLTEX->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($estimativa->nu_altLTEX->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
festimativaedit.Lists["x_nu_altLTEX"].Options = <?php echo (is_array($estimativa->nu_altLTEX->EditValue)) ? ew_ArrayToJson($estimativa->nu_altLTEX->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $estimativa->nu_altLTEX->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altTOOL->Visible) { // nu_altTOOL ?>
	<tr id="r_nu_altTOOL">
		<td><span id="elh_estimativa_nu_altTOOL"><?php echo $estimativa->nu_altTOOL->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altTOOL->CellAttributes() ?>>
<span id="el_estimativa_nu_altTOOL" class="control-group">
<select data-field="x_nu_altTOOL" id="x_nu_altTOOL" name="x_nu_altTOOL"<?php echo $estimativa->nu_altTOOL->EditAttributes() ?>>
<?php
if (is_array($estimativa->nu_altTOOL->EditValue)) {
	$arwrk = $estimativa->nu_altTOOL->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($estimativa->nu_altTOOL->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
festimativaedit.Lists["x_nu_altTOOL"].Options = <?php echo (is_array($estimativa->nu_altTOOL->EditValue)) ? ew_ArrayToJson($estimativa->nu_altTOOL->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $estimativa->nu_altTOOL->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altSITE->Visible) { // nu_altSITE ?>
	<tr id="r_nu_altSITE">
		<td><span id="elh_estimativa_nu_altSITE"><?php echo $estimativa->nu_altSITE->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altSITE->CellAttributes() ?>>
<span id="el_estimativa_nu_altSITE" class="control-group">
<select data-field="x_nu_altSITE" id="x_nu_altSITE" name="x_nu_altSITE"<?php echo $estimativa->nu_altSITE->EditAttributes() ?>>
<?php
if (is_array($estimativa->nu_altSITE->EditValue)) {
	$arwrk = $estimativa->nu_altSITE->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($estimativa->nu_altSITE->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
festimativaedit.Lists["x_nu_altSITE"].Options = <?php echo (is_array($estimativa->nu_altSITE->EditValue)) ? ew_ArrayToJson($estimativa->nu_altSITE->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $estimativa->nu_altSITE->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_estimativa3">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_estimativaedit3" class="table table-bordered table-striped">
<?php if ($estimativa->pz_estimadoMeses->Visible) { // pz_estimadoMeses ?>
	<tr id="r_pz_estimadoMeses">
		<td><span id="elh_estimativa_pz_estimadoMeses"><?php echo $estimativa->pz_estimadoMeses->FldCaption() ?></span></td>
		<td<?php echo $estimativa->pz_estimadoMeses->CellAttributes() ?>>
<span id="el_estimativa_pz_estimadoMeses" class="control-group">
<input type="text" data-field="x_pz_estimadoMeses" name="x_pz_estimadoMeses" id="x_pz_estimadoMeses" size="30" placeholder="<?php echo $estimativa->pz_estimadoMeses->PlaceHolder ?>" value="<?php echo $estimativa->pz_estimadoMeses->EditValue ?>"<?php echo $estimativa->pz_estimadoMeses->EditAttributes() ?>>
</span>
<?php echo $estimativa->pz_estimadoMeses->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->pz_estimadoDias->Visible) { // pz_estimadoDias ?>
	<tr id="r_pz_estimadoDias">
		<td><span id="elh_estimativa_pz_estimadoDias"><?php echo $estimativa->pz_estimadoDias->FldCaption() ?></span></td>
		<td<?php echo $estimativa->pz_estimadoDias->CellAttributes() ?>>
<span id="el_estimativa_pz_estimadoDias" class="control-group">
<input type="text" data-field="x_pz_estimadoDias" name="x_pz_estimadoDias" id="x_pz_estimadoDias" size="30" placeholder="<?php echo $estimativa->pz_estimadoDias->PlaceHolder ?>" value="<?php echo $estimativa->pz_estimadoDias->EditValue ?>"<?php echo $estimativa->pz_estimadoDias->EditAttributes() ?>>
</span>
<?php echo $estimativa->pz_estimadoDias->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->vr_ipMaximo->Visible) { // vr_ipMaximo ?>
	<tr id="r_vr_ipMaximo">
		<td><span id="elh_estimativa_vr_ipMaximo"><?php echo $estimativa->vr_ipMaximo->FldCaption() ?></span></td>
		<td<?php echo $estimativa->vr_ipMaximo->CellAttributes() ?>>
<span id="el_estimativa_vr_ipMaximo" class="control-group">
<input type="text" data-field="x_vr_ipMaximo" name="x_vr_ipMaximo" id="x_vr_ipMaximo" size="30" placeholder="<?php echo $estimativa->vr_ipMaximo->PlaceHolder ?>" value="<?php echo $estimativa->vr_ipMaximo->EditValue ?>"<?php echo $estimativa->vr_ipMaximo->EditAttributes() ?>>
</span>
<?php echo $estimativa->vr_ipMaximo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->vr_ipMedio->Visible) { // vr_ipMedio ?>
	<tr id="r_vr_ipMedio">
		<td><span id="elh_estimativa_vr_ipMedio"><?php echo $estimativa->vr_ipMedio->FldCaption() ?></span></td>
		<td<?php echo $estimativa->vr_ipMedio->CellAttributes() ?>>
<span id="el_estimativa_vr_ipMedio" class="control-group">
<input type="text" data-field="x_vr_ipMedio" name="x_vr_ipMedio" id="x_vr_ipMedio" size="30" placeholder="<?php echo $estimativa->vr_ipMedio->PlaceHolder ?>" value="<?php echo $estimativa->vr_ipMedio->EditValue ?>"<?php echo $estimativa->vr_ipMedio->EditAttributes() ?>>
</span>
<?php echo $estimativa->vr_ipMedio->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->vr_ipMinimo->Visible) { // vr_ipMinimo ?>
	<tr id="r_vr_ipMinimo">
		<td><span id="elh_estimativa_vr_ipMinimo"><?php echo $estimativa->vr_ipMinimo->FldCaption() ?></span></td>
		<td<?php echo $estimativa->vr_ipMinimo->CellAttributes() ?>>
<span id="el_estimativa_vr_ipMinimo" class="control-group">
<input type="text" data-field="x_vr_ipMinimo" name="x_vr_ipMinimo" id="x_vr_ipMinimo" size="30" placeholder="<?php echo $estimativa->vr_ipMinimo->PlaceHolder ?>" value="<?php echo $estimativa->vr_ipMinimo->EditValue ?>"<?php echo $estimativa->vr_ipMinimo->EditAttributes() ?>>
</span>
<?php echo $estimativa->vr_ipMinimo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->vr_ipInformado->Visible) { // vr_ipInformado ?>
	<tr id="r_vr_ipInformado">
		<td><span id="elh_estimativa_vr_ipInformado"><?php echo $estimativa->vr_ipInformado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $estimativa->vr_ipInformado->CellAttributes() ?>>
<span id="el_estimativa_vr_ipInformado" class="control-group">
<input type="text" data-field="x_vr_ipInformado" name="x_vr_ipInformado" id="x_vr_ipInformado" size="30" placeholder="<?php echo $estimativa->vr_ipInformado->PlaceHolder ?>" value="<?php echo $estimativa->vr_ipInformado->EditValue ?>"<?php echo $estimativa->vr_ipInformado->EditAttributes() ?>>
</span>
<?php echo $estimativa->vr_ipInformado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->qt_esforco->Visible) { // qt_esforco ?>
	<tr id="r_qt_esforco">
		<td><span id="elh_estimativa_qt_esforco"><?php echo $estimativa->qt_esforco->FldCaption() ?></span></td>
		<td<?php echo $estimativa->qt_esforco->CellAttributes() ?>>
<span id="el_estimativa_qt_esforco" class="control-group">
<input type="text" data-field="x_qt_esforco" name="x_qt_esforco" id="x_qt_esforco" size="30" placeholder="<?php echo $estimativa->qt_esforco->PlaceHolder ?>" value="<?php echo $estimativa->qt_esforco->EditValue ?>"<?php echo $estimativa->qt_esforco->EditAttributes() ?>>
</span>
<?php echo $estimativa->qt_esforco->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->vr_custoDesenv->Visible) { // vr_custoDesenv ?>
	<tr id="r_vr_custoDesenv">
		<td><span id="elh_estimativa_vr_custoDesenv"><?php echo $estimativa->vr_custoDesenv->FldCaption() ?></span></td>
		<td<?php echo $estimativa->vr_custoDesenv->CellAttributes() ?>>
<span id="el_estimativa_vr_custoDesenv" class="control-group">
<input type="text" data-field="x_vr_custoDesenv" name="x_vr_custoDesenv" id="x_vr_custoDesenv" size="30" placeholder="<?php echo $estimativa->vr_custoDesenv->PlaceHolder ?>" value="<?php echo $estimativa->vr_custoDesenv->EditValue ?>"<?php echo $estimativa->vr_custoDesenv->EditAttributes() ?>>
</span>
<?php echo $estimativa->vr_custoDesenv->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->vr_outrosCustos->Visible) { // vr_outrosCustos ?>
	<tr id="r_vr_outrosCustos">
		<td><span id="elh_estimativa_vr_outrosCustos"><?php echo $estimativa->vr_outrosCustos->FldCaption() ?></span></td>
		<td<?php echo $estimativa->vr_outrosCustos->CellAttributes() ?>>
<span id="el_estimativa_vr_outrosCustos" class="control-group">
<input type="text" data-field="x_vr_outrosCustos" name="x_vr_outrosCustos" id="x_vr_outrosCustos" size="30" placeholder="<?php echo $estimativa->vr_outrosCustos->PlaceHolder ?>" value="<?php echo $estimativa->vr_outrosCustos->EditValue ?>"<?php echo $estimativa->vr_outrosCustos->EditAttributes() ?>>
</span>
<?php echo $estimativa->vr_outrosCustos->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->vr_custoTotal->Visible) { // vr_custoTotal ?>
	<tr id="r_vr_custoTotal">
		<td><span id="elh_estimativa_vr_custoTotal"><?php echo $estimativa->vr_custoTotal->FldCaption() ?></span></td>
		<td<?php echo $estimativa->vr_custoTotal->CellAttributes() ?>>
<span id="el_estimativa_vr_custoTotal" class="control-group">
<input type="text" data-field="x_vr_custoTotal" name="x_vr_custoTotal" id="x_vr_custoTotal" size="30" placeholder="<?php echo $estimativa->vr_custoTotal->PlaceHolder ?>" value="<?php echo $estimativa->vr_custoTotal->EditValue ?>"<?php echo $estimativa->vr_custoTotal->EditAttributes() ?>>
</span>
<?php echo $estimativa->vr_custoTotal->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->qt_tamBaseFaturamento->Visible) { // qt_tamBaseFaturamento ?>
	<tr id="r_qt_tamBaseFaturamento">
		<td><span id="elh_estimativa_qt_tamBaseFaturamento"><?php echo $estimativa->qt_tamBaseFaturamento->FldCaption() ?></span></td>
		<td<?php echo $estimativa->qt_tamBaseFaturamento->CellAttributes() ?>>
<span id="el_estimativa_qt_tamBaseFaturamento" class="control-group">
<input type="text" data-field="x_qt_tamBaseFaturamento" name="x_qt_tamBaseFaturamento" id="x_qt_tamBaseFaturamento" size="30" placeholder="<?php echo $estimativa->qt_tamBaseFaturamento->PlaceHolder ?>" value="<?php echo $estimativa->qt_tamBaseFaturamento->EditValue ?>"<?php echo $estimativa->qt_tamBaseFaturamento->EditAttributes() ?>>
</span>
<?php echo $estimativa->qt_tamBaseFaturamento->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->qt_recursosEquipe->Visible) { // qt_recursosEquipe ?>
	<tr id="r_qt_recursosEquipe">
		<td><span id="elh_estimativa_qt_recursosEquipe"><?php echo $estimativa->qt_recursosEquipe->FldCaption() ?></span></td>
		<td<?php echo $estimativa->qt_recursosEquipe->CellAttributes() ?>>
<span id="el_estimativa_qt_recursosEquipe" class="control-group">
<input type="text" data-field="x_qt_recursosEquipe" name="x_qt_recursosEquipe" id="x_qt_recursosEquipe" size="30" placeholder="<?php echo $estimativa->qt_recursosEquipe->PlaceHolder ?>" value="<?php echo $estimativa->qt_recursosEquipe->EditValue ?>"<?php echo $estimativa->qt_recursosEquipe->EditAttributes() ?>>
</span>
<?php echo $estimativa->qt_recursosEquipe->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($estimativa->ds_observacoes->Visible) { // ds_observacoes ?>
	<tr id="r_ds_observacoes">
		<td><span id="elh_estimativa_ds_observacoes"><?php echo $estimativa->ds_observacoes->FldCaption() ?></span></td>
		<td<?php echo $estimativa->ds_observacoes->CellAttributes() ?>>
<span id="el_estimativa_ds_observacoes" class="control-group">
<textarea data-field="x_ds_observacoes" name="x_ds_observacoes" id="x_ds_observacoes" cols="35" rows="4" placeholder="<?php echo $estimativa->ds_observacoes->PlaceHolder ?>"<?php echo $estimativa->ds_observacoes->EditAttributes() ?>><?php echo $estimativa->ds_observacoes->EditValue ?></textarea>
</span>
<?php echo $estimativa->ds_observacoes->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
	</div>
</div>
</td></tr></tbody></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
festimativaedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$estimativa_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$estimativa_edit->Page_Terminate();
?>
