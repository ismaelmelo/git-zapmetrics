<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "prospectoinfo.php" ?>
<?php include_once "rprospresumoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "prospecto_itempdtigridcls.php" ?>
<?php include_once "prospectoocorrenciasgridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$prospecto_edit = NULL; // Initialize page object first

class cprospecto_edit extends cprospecto {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'prospecto';

	// Page object name
	var $PageObjName = 'prospecto_edit';

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

		// Table object (prospecto)
		if (!isset($GLOBALS["prospecto"])) {
			$GLOBALS["prospecto"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["prospecto"];
		}

		// Table object (rprospresumo)
		if (!isset($GLOBALS['rprospresumo'])) $GLOBALS['rprospresumo'] = new crprospresumo();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'prospecto', TRUE);

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
			$this->Page_Terminate("prospectolist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action
		$this->nu_prospecto->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		if (@$_GET["nu_prospecto"] <> "") {
			$this->nu_prospecto->setQueryStringValue($_GET["nu_prospecto"]);
		}

		// Set up master detail parameters
		$this->SetUpMasterParms();

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values

			// Set up detail parameters
			$this->SetUpDetailParms();
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->nu_prospecto->CurrentValue == "")
			$this->Page_Terminate("prospectolist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("prospectolist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					if ($this->getCurrentDetailTable() <> "") // Master/detail edit
						$sReturnUrl = $this->GetDetailUrl();
					else
						$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "prospectoview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to View page directly
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed

					// Set up detail parameters
					$this->SetUpDetailParms();
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
		if (!$this->nu_prospecto->FldIsDetailKey)
			$this->nu_prospecto->setFormValue($objForm->GetValue("x_nu_prospecto"));
		if (!$this->no_prospecto->FldIsDetailKey) {
			$this->no_prospecto->setFormValue($objForm->GetValue("x_no_prospecto"));
		}
		if (!$this->nu_area->FldIsDetailKey) {
			$this->nu_area->setFormValue($objForm->GetValue("x_nu_area"));
		}
		if (!$this->no_solicitante->FldIsDetailKey) {
			$this->no_solicitante->setFormValue($objForm->GetValue("x_no_solicitante"));
		}
		if (!$this->no_patrocinador->FldIsDetailKey) {
			$this->no_patrocinador->setFormValue($objForm->GetValue("x_no_patrocinador"));
		}
		if (!$this->ar_entidade->FldIsDetailKey) {
			$this->ar_entidade->setFormValue($objForm->GetValue("x_ar_entidade"));
		}
		if (!$this->ar_nivel->FldIsDetailKey) {
			$this->ar_nivel->setFormValue($objForm->GetValue("x_ar_nivel"));
		}
		if (!$this->nu_categoriaProspecto->FldIsDetailKey) {
			$this->nu_categoriaProspecto->setFormValue($objForm->GetValue("x_nu_categoriaProspecto"));
		}
		if (!$this->nu_alternativaImpacto->FldIsDetailKey) {
			$this->nu_alternativaImpacto->setFormValue($objForm->GetValue("x_nu_alternativaImpacto"));
		}
		if (!$this->ds_sistemas->FldIsDetailKey) {
			$this->ds_sistemas->setFormValue($objForm->GetValue("x_ds_sistemas"));
		}
		if (!$this->ds_impactoNaoImplem->FldIsDetailKey) {
			$this->ds_impactoNaoImplem->setFormValue($objForm->GetValue("x_ds_impactoNaoImplem"));
		}
		if (!$this->nu_alternativaAlinhamento->FldIsDetailKey) {
			$this->nu_alternativaAlinhamento->setFormValue($objForm->GetValue("x_nu_alternativaAlinhamento"));
		}
		if (!$this->nu_alternativaAbrangencia->FldIsDetailKey) {
			$this->nu_alternativaAbrangencia->setFormValue($objForm->GetValue("x_nu_alternativaAbrangencia"));
		}
		if (!$this->nu_alternativaUrgencia->FldIsDetailKey) {
			$this->nu_alternativaUrgencia->setFormValue($objForm->GetValue("x_nu_alternativaUrgencia"));
		}
		if (!$this->dt_prazo->FldIsDetailKey) {
			$this->dt_prazo->setFormValue($objForm->GetValue("x_dt_prazo"));
			$this->dt_prazo->CurrentValue = ew_UnFormatDateTime($this->dt_prazo->CurrentValue, 7);
		}
		if (!$this->nu_alternativaTmpEstimado->FldIsDetailKey) {
			$this->nu_alternativaTmpEstimado->setFormValue($objForm->GetValue("x_nu_alternativaTmpEstimado"));
		}
		if (!$this->nu_alternativaTmpFila->FldIsDetailKey) {
			$this->nu_alternativaTmpFila->setFormValue($objForm->GetValue("x_nu_alternativaTmpFila"));
		}
		if (!$this->ic_implicacaoLegal->FldIsDetailKey) {
			$this->ic_implicacaoLegal->setFormValue($objForm->GetValue("x_ic_implicacaoLegal"));
		}
		if (!$this->ic_risco->FldIsDetailKey) {
			$this->ic_risco->setFormValue($objForm->GetValue("x_ic_risco"));
		}
		if (!$this->ic_stProspecto->FldIsDetailKey) {
			$this->ic_stProspecto->setFormValue($objForm->GetValue("x_ic_stProspecto"));
		}
		if (!$this->ds_observacoes->FldIsDetailKey) {
			$this->ds_observacoes->setFormValue($objForm->GetValue("x_ds_observacoes"));
		}
		if (!$this->ic_ativo->FldIsDetailKey) {
			$this->ic_ativo->setFormValue($objForm->GetValue("x_ic_ativo"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->nu_prospecto->CurrentValue = $this->nu_prospecto->FormValue;
		$this->no_prospecto->CurrentValue = $this->no_prospecto->FormValue;
		$this->nu_area->CurrentValue = $this->nu_area->FormValue;
		$this->no_solicitante->CurrentValue = $this->no_solicitante->FormValue;
		$this->no_patrocinador->CurrentValue = $this->no_patrocinador->FormValue;
		$this->ar_entidade->CurrentValue = $this->ar_entidade->FormValue;
		$this->ar_nivel->CurrentValue = $this->ar_nivel->FormValue;
		$this->nu_categoriaProspecto->CurrentValue = $this->nu_categoriaProspecto->FormValue;
		$this->nu_alternativaImpacto->CurrentValue = $this->nu_alternativaImpacto->FormValue;
		$this->ds_sistemas->CurrentValue = $this->ds_sistemas->FormValue;
		$this->ds_impactoNaoImplem->CurrentValue = $this->ds_impactoNaoImplem->FormValue;
		$this->nu_alternativaAlinhamento->CurrentValue = $this->nu_alternativaAlinhamento->FormValue;
		$this->nu_alternativaAbrangencia->CurrentValue = $this->nu_alternativaAbrangencia->FormValue;
		$this->nu_alternativaUrgencia->CurrentValue = $this->nu_alternativaUrgencia->FormValue;
		$this->dt_prazo->CurrentValue = $this->dt_prazo->FormValue;
		$this->dt_prazo->CurrentValue = ew_UnFormatDateTime($this->dt_prazo->CurrentValue, 7);
		$this->nu_alternativaTmpEstimado->CurrentValue = $this->nu_alternativaTmpEstimado->FormValue;
		$this->nu_alternativaTmpFila->CurrentValue = $this->nu_alternativaTmpFila->FormValue;
		$this->ic_implicacaoLegal->CurrentValue = $this->ic_implicacaoLegal->FormValue;
		$this->ic_risco->CurrentValue = $this->ic_risco->FormValue;
		$this->ic_stProspecto->CurrentValue = $this->ic_stProspecto->FormValue;
		$this->ds_observacoes->CurrentValue = $this->ds_observacoes->FormValue;
		$this->ic_ativo->CurrentValue = $this->ic_ativo->FormValue;
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
		$this->nu_prospecto->setDbValue($rs->fields('nu_prospecto'));
		$this->no_prospecto->setDbValue($rs->fields('no_prospecto'));
		$this->nu_area->setDbValue($rs->fields('nu_area'));
		$this->no_solicitante->setDbValue($rs->fields('no_solicitante'));
		$this->no_patrocinador->setDbValue($rs->fields('no_patrocinador'));
		$this->ar_entidade->setDbValue($rs->fields('ar_entidade'));
		$this->ar_nivel->setDbValue($rs->fields('ar_nivel'));
		$this->nu_categoriaProspecto->setDbValue($rs->fields('nu_categoriaProspecto'));
		$this->nu_alternativaImpacto->setDbValue($rs->fields('nu_alternativaImpacto'));
		$this->ds_sistemas->setDbValue($rs->fields('ds_sistemas'));
		$this->ds_impactoNaoImplem->setDbValue($rs->fields('ds_impactoNaoImplem'));
		$this->nu_alternativaAlinhamento->setDbValue($rs->fields('nu_alternativaAlinhamento'));
		$this->nu_alternativaAbrangencia->setDbValue($rs->fields('nu_alternativaAbrangencia'));
		$this->nu_alternativaUrgencia->setDbValue($rs->fields('nu_alternativaUrgencia'));
		$this->dt_prazo->setDbValue($rs->fields('dt_prazo'));
		$this->nu_alternativaTmpEstimado->setDbValue($rs->fields('nu_alternativaTmpEstimado'));
		$this->nu_alternativaTmpFila->setDbValue($rs->fields('nu_alternativaTmpFila'));
		$this->ic_implicacaoLegal->setDbValue($rs->fields('ic_implicacaoLegal'));
		$this->ic_risco->setDbValue($rs->fields('ic_risco'));
		$this->ic_stProspecto->setDbValue($rs->fields('ic_stProspecto'));
		$this->ds_observacoes->setDbValue($rs->fields('ds_observacoes'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_prospecto->DbValue = $row['nu_prospecto'];
		$this->no_prospecto->DbValue = $row['no_prospecto'];
		$this->nu_area->DbValue = $row['nu_area'];
		$this->no_solicitante->DbValue = $row['no_solicitante'];
		$this->no_patrocinador->DbValue = $row['no_patrocinador'];
		$this->ar_entidade->DbValue = $row['ar_entidade'];
		$this->ar_nivel->DbValue = $row['ar_nivel'];
		$this->nu_categoriaProspecto->DbValue = $row['nu_categoriaProspecto'];
		$this->nu_alternativaImpacto->DbValue = $row['nu_alternativaImpacto'];
		$this->ds_sistemas->DbValue = $row['ds_sistemas'];
		$this->ds_impactoNaoImplem->DbValue = $row['ds_impactoNaoImplem'];
		$this->nu_alternativaAlinhamento->DbValue = $row['nu_alternativaAlinhamento'];
		$this->nu_alternativaAbrangencia->DbValue = $row['nu_alternativaAbrangencia'];
		$this->nu_alternativaUrgencia->DbValue = $row['nu_alternativaUrgencia'];
		$this->dt_prazo->DbValue = $row['dt_prazo'];
		$this->nu_alternativaTmpEstimado->DbValue = $row['nu_alternativaTmpEstimado'];
		$this->nu_alternativaTmpFila->DbValue = $row['nu_alternativaTmpFila'];
		$this->ic_implicacaoLegal->DbValue = $row['ic_implicacaoLegal'];
		$this->ic_risco->DbValue = $row['ic_risco'];
		$this->ic_stProspecto->DbValue = $row['ic_stProspecto'];
		$this->ds_observacoes->DbValue = $row['ds_observacoes'];
		$this->ic_ativo->DbValue = $row['ic_ativo'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_prospecto
		// no_prospecto
		// nu_area
		// no_solicitante
		// no_patrocinador
		// ar_entidade
		// ar_nivel
		// nu_categoriaProspecto
		// nu_alternativaImpacto
		// ds_sistemas
		// ds_impactoNaoImplem
		// nu_alternativaAlinhamento
		// nu_alternativaAbrangencia
		// nu_alternativaUrgencia
		// dt_prazo
		// nu_alternativaTmpEstimado
		// nu_alternativaTmpFila
		// ic_implicacaoLegal
		// ic_risco
		// ic_stProspecto
		// ds_observacoes
		// ic_ativo

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_prospecto
			$this->nu_prospecto->ViewValue = $this->nu_prospecto->CurrentValue;
			$this->nu_prospecto->ViewCustomAttributes = "";

			// no_prospecto
			$this->no_prospecto->ViewValue = $this->no_prospecto->CurrentValue;
			$this->no_prospecto->ViewCustomAttributes = "";

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

			// no_solicitante
			$this->no_solicitante->ViewValue = $this->no_solicitante->CurrentValue;
			$this->no_solicitante->ViewCustomAttributes = "";

			// no_patrocinador
			$this->no_patrocinador->ViewValue = $this->no_patrocinador->CurrentValue;
			$this->no_patrocinador->ViewCustomAttributes = "";

			// ar_entidade
			if (strval($this->ar_entidade->CurrentValue) <> "") {
				$arwrk = explode(",", $this->ar_entidade->CurrentValue);
				$sFilterWrk = "";
				foreach ($arwrk as $wrk) {
					if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
					$sFilterWrk .= "[nu_organizacao]" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER);
				}	
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
			$this->Lookup_Selecting($this->ar_entidade, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_organizacao] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->ar_entidade->ViewValue = "";
					$ari = 0;
					while (!$rswrk->EOF) {
						$this->ar_entidade->ViewValue .= $rswrk->fields('DispFld');
						$rswrk->MoveNext();
						if (!$rswrk->EOF) $this->ar_entidade->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
						$ari++;
					}
					$rswrk->Close();
				} else {
					$this->ar_entidade->ViewValue = $this->ar_entidade->CurrentValue;
				}
			} else {
				$this->ar_entidade->ViewValue = NULL;
			}
			$this->ar_entidade->ViewCustomAttributes = "";

			// ar_nivel
			if (strval($this->ar_nivel->CurrentValue) <> "") {
				$this->ar_nivel->ViewValue = "";
				$arwrk = explode(",", strval($this->ar_nivel->CurrentValue));
				$cnt = count($arwrk);
				for ($ari = 0; $ari < $cnt; $ari++) {
					switch (trim($arwrk[$ari])) {
						case $this->ar_nivel->FldTagValue(1):
							$this->ar_nivel->ViewValue .= $this->ar_nivel->FldTagCaption(1) <> "" ? $this->ar_nivel->FldTagCaption(1) : trim($arwrk[$ari]);
							break;
						case $this->ar_nivel->FldTagValue(2):
							$this->ar_nivel->ViewValue .= $this->ar_nivel->FldTagCaption(2) <> "" ? $this->ar_nivel->FldTagCaption(2) : trim($arwrk[$ari]);
							break;
						default:
							$this->ar_nivel->ViewValue .= trim($arwrk[$ari]);
					}
					if ($ari < $cnt-1) $this->ar_nivel->ViewValue .= ew_ViewOptionSeparator($ari);
				}
			} else {
				$this->ar_nivel->ViewValue = NULL;
			}
			$this->ar_nivel->ViewCustomAttributes = "";

			// nu_categoriaProspecto
			if (strval($this->nu_categoriaProspecto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_categoria]" . ew_SearchString("=", $this->nu_categoriaProspecto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_categoria], [no_categoria] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[catprospecto]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_categoriaProspecto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_categoria] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_categoriaProspecto->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_categoriaProspecto->ViewValue = $this->nu_categoriaProspecto->CurrentValue;
				}
			} else {
				$this->nu_categoriaProspecto->ViewValue = NULL;
			}
			$this->nu_categoriaProspecto->ViewCustomAttributes = "";

			// nu_alternativaImpacto
			if (strval($this->nu_alternativaImpacto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativaAvaliacao]" . ew_SearchString("=", $this->nu_alternativaImpacto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativaAvaliacao], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[criterioavaliacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S' AND [nu_criterioPrioridade] = 10";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_alternativaImpacto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_alternativaImpacto->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_alternativaImpacto->ViewValue = $this->nu_alternativaImpacto->CurrentValue;
				}
			} else {
				$this->nu_alternativaImpacto->ViewValue = NULL;
			}
			$this->nu_alternativaImpacto->ViewCustomAttributes = "";

			// ds_sistemas
			$this->ds_sistemas->ViewValue = $this->ds_sistemas->CurrentValue;
			$this->ds_sistemas->ViewCustomAttributes = "";

			// ds_impactoNaoImplem
			$this->ds_impactoNaoImplem->ViewValue = $this->ds_impactoNaoImplem->CurrentValue;
			$this->ds_impactoNaoImplem->ViewCustomAttributes = "";

			// nu_alternativaAlinhamento
			if (strval($this->nu_alternativaAlinhamento->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativaAvaliacao]" . ew_SearchString("=", $this->nu_alternativaAlinhamento->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativaAvaliacao], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[criterioavaliacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S' AND [nu_criterioPrioridade] = '11'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_alternativaAlinhamento, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_alternativaAlinhamento->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_alternativaAlinhamento->ViewValue = $this->nu_alternativaAlinhamento->CurrentValue;
				}
			} else {
				$this->nu_alternativaAlinhamento->ViewValue = NULL;
			}
			$this->nu_alternativaAlinhamento->ViewCustomAttributes = "";

			// nu_alternativaAbrangencia
			if (strval($this->nu_alternativaAbrangencia->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativaAvaliacao]" . ew_SearchString("=", $this->nu_alternativaAbrangencia->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativaAvaliacao], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[criterioavaliacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S' AND [nu_criterioPrioridade] = 12";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_alternativaAbrangencia, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_alternativaAbrangencia->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_alternativaAbrangencia->ViewValue = $this->nu_alternativaAbrangencia->CurrentValue;
				}
			} else {
				$this->nu_alternativaAbrangencia->ViewValue = NULL;
			}
			$this->nu_alternativaAbrangencia->ViewCustomAttributes = "";

			// nu_alternativaUrgencia
			if (strval($this->nu_alternativaUrgencia->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativaAvaliacao]" . ew_SearchString("=", $this->nu_alternativaUrgencia->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativaAvaliacao], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[criterioavaliacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S' AND [nu_criterioPrioridade] = 13";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_alternativaUrgencia, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_alternativaUrgencia->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_alternativaUrgencia->ViewValue = $this->nu_alternativaUrgencia->CurrentValue;
				}
			} else {
				$this->nu_alternativaUrgencia->ViewValue = NULL;
			}
			$this->nu_alternativaUrgencia->ViewCustomAttributes = "";

			// dt_prazo
			$this->dt_prazo->ViewValue = $this->dt_prazo->CurrentValue;
			$this->dt_prazo->ViewValue = ew_FormatDateTime($this->dt_prazo->ViewValue, 7);
			$this->dt_prazo->ViewCustomAttributes = "";

			// nu_alternativaTmpEstimado
			if (strval($this->nu_alternativaTmpEstimado->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativaAvaliacao]" . ew_SearchString("=", $this->nu_alternativaTmpEstimado->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativaAvaliacao], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[criterioavaliacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S' AND [nu_criterioPrioridade] = 14";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_alternativaTmpEstimado, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_alternativaTmpEstimado->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_alternativaTmpEstimado->ViewValue = $this->nu_alternativaTmpEstimado->CurrentValue;
				}
			} else {
				$this->nu_alternativaTmpEstimado->ViewValue = NULL;
			}
			$this->nu_alternativaTmpEstimado->ViewCustomAttributes = "";

			// nu_alternativaTmpFila
			if (strval($this->nu_alternativaTmpFila->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativaAvaliacao]" . ew_SearchString("=", $this->nu_alternativaTmpFila->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativaAvaliacao], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[criterioavaliacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S' AND [nu_criterioPrioridade] = 15";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_alternativaTmpFila, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_alternativaTmpFila->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_alternativaTmpFila->ViewValue = $this->nu_alternativaTmpFila->CurrentValue;
				}
			} else {
				$this->nu_alternativaTmpFila->ViewValue = NULL;
			}
			$this->nu_alternativaTmpFila->ViewCustomAttributes = "";

			// ic_implicacaoLegal
			if (strval($this->ic_implicacaoLegal->CurrentValue) <> "") {
				switch ($this->ic_implicacaoLegal->CurrentValue) {
					case $this->ic_implicacaoLegal->FldTagValue(1):
						$this->ic_implicacaoLegal->ViewValue = $this->ic_implicacaoLegal->FldTagCaption(1) <> "" ? $this->ic_implicacaoLegal->FldTagCaption(1) : $this->ic_implicacaoLegal->CurrentValue;
						break;
					case $this->ic_implicacaoLegal->FldTagValue(2):
						$this->ic_implicacaoLegal->ViewValue = $this->ic_implicacaoLegal->FldTagCaption(2) <> "" ? $this->ic_implicacaoLegal->FldTagCaption(2) : $this->ic_implicacaoLegal->CurrentValue;
						break;
					default:
						$this->ic_implicacaoLegal->ViewValue = $this->ic_implicacaoLegal->CurrentValue;
				}
			} else {
				$this->ic_implicacaoLegal->ViewValue = NULL;
			}
			$this->ic_implicacaoLegal->ViewCustomAttributes = "";

			// ic_risco
			if (strval($this->ic_risco->CurrentValue) <> "") {
				switch ($this->ic_risco->CurrentValue) {
					case $this->ic_risco->FldTagValue(1):
						$this->ic_risco->ViewValue = $this->ic_risco->FldTagCaption(1) <> "" ? $this->ic_risco->FldTagCaption(1) : $this->ic_risco->CurrentValue;
						break;
					case $this->ic_risco->FldTagValue(2):
						$this->ic_risco->ViewValue = $this->ic_risco->FldTagCaption(2) <> "" ? $this->ic_risco->FldTagCaption(2) : $this->ic_risco->CurrentValue;
						break;
					case $this->ic_risco->FldTagValue(3):
						$this->ic_risco->ViewValue = $this->ic_risco->FldTagCaption(3) <> "" ? $this->ic_risco->FldTagCaption(3) : $this->ic_risco->CurrentValue;
						break;
					default:
						$this->ic_risco->ViewValue = $this->ic_risco->CurrentValue;
				}
			} else {
				$this->ic_risco->ViewValue = NULL;
			}
			$this->ic_risco->ViewCustomAttributes = "";

			// ic_stProspecto
			if (strval($this->ic_stProspecto->CurrentValue) <> "") {
				switch ($this->ic_stProspecto->CurrentValue) {
					case $this->ic_stProspecto->FldTagValue(1):
						$this->ic_stProspecto->ViewValue = $this->ic_stProspecto->FldTagCaption(1) <> "" ? $this->ic_stProspecto->FldTagCaption(1) : $this->ic_stProspecto->CurrentValue;
						break;
					case $this->ic_stProspecto->FldTagValue(2):
						$this->ic_stProspecto->ViewValue = $this->ic_stProspecto->FldTagCaption(2) <> "" ? $this->ic_stProspecto->FldTagCaption(2) : $this->ic_stProspecto->CurrentValue;
						break;
					case $this->ic_stProspecto->FldTagValue(3):
						$this->ic_stProspecto->ViewValue = $this->ic_stProspecto->FldTagCaption(3) <> "" ? $this->ic_stProspecto->FldTagCaption(3) : $this->ic_stProspecto->CurrentValue;
						break;
					case $this->ic_stProspecto->FldTagValue(4):
						$this->ic_stProspecto->ViewValue = $this->ic_stProspecto->FldTagCaption(4) <> "" ? $this->ic_stProspecto->FldTagCaption(4) : $this->ic_stProspecto->CurrentValue;
						break;
					case $this->ic_stProspecto->FldTagValue(5):
						$this->ic_stProspecto->ViewValue = $this->ic_stProspecto->FldTagCaption(5) <> "" ? $this->ic_stProspecto->FldTagCaption(5) : $this->ic_stProspecto->CurrentValue;
						break;
					default:
						$this->ic_stProspecto->ViewValue = $this->ic_stProspecto->CurrentValue;
				}
			} else {
				$this->ic_stProspecto->ViewValue = NULL;
			}
			$this->ic_stProspecto->ViewCustomAttributes = "";

			// ds_observacoes
			$this->ds_observacoes->ViewValue = $this->ds_observacoes->CurrentValue;
			$this->ds_observacoes->ViewCustomAttributes = "";

			// ic_ativo
			if (strval($this->ic_ativo->CurrentValue) <> "") {
				switch ($this->ic_ativo->CurrentValue) {
					case $this->ic_ativo->FldTagValue(1):
						$this->ic_ativo->ViewValue = $this->ic_ativo->FldTagCaption(1) <> "" ? $this->ic_ativo->FldTagCaption(1) : $this->ic_ativo->CurrentValue;
						break;
					case $this->ic_ativo->FldTagValue(2):
						$this->ic_ativo->ViewValue = $this->ic_ativo->FldTagCaption(2) <> "" ? $this->ic_ativo->FldTagCaption(2) : $this->ic_ativo->CurrentValue;
						break;
					default:
						$this->ic_ativo->ViewValue = $this->ic_ativo->CurrentValue;
				}
			} else {
				$this->ic_ativo->ViewValue = NULL;
			}
			$this->ic_ativo->ViewCustomAttributes = "";

			// nu_prospecto
			$this->nu_prospecto->LinkCustomAttributes = "";
			$this->nu_prospecto->HrefValue = "";
			$this->nu_prospecto->TooltipValue = "";

			// no_prospecto
			$this->no_prospecto->LinkCustomAttributes = "";
			$this->no_prospecto->HrefValue = "";
			$this->no_prospecto->TooltipValue = "";

			// nu_area
			$this->nu_area->LinkCustomAttributes = "";
			$this->nu_area->HrefValue = "";
			$this->nu_area->TooltipValue = "";

			// no_solicitante
			$this->no_solicitante->LinkCustomAttributes = "";
			$this->no_solicitante->HrefValue = "";
			$this->no_solicitante->TooltipValue = "";

			// no_patrocinador
			$this->no_patrocinador->LinkCustomAttributes = "";
			$this->no_patrocinador->HrefValue = "";
			$this->no_patrocinador->TooltipValue = "";

			// ar_entidade
			$this->ar_entidade->LinkCustomAttributes = "";
			$this->ar_entidade->HrefValue = "";
			$this->ar_entidade->TooltipValue = "";

			// ar_nivel
			$this->ar_nivel->LinkCustomAttributes = "";
			$this->ar_nivel->HrefValue = "";
			$this->ar_nivel->TooltipValue = "";

			// nu_categoriaProspecto
			$this->nu_categoriaProspecto->LinkCustomAttributes = "";
			$this->nu_categoriaProspecto->HrefValue = "";
			$this->nu_categoriaProspecto->TooltipValue = "";

			// nu_alternativaImpacto
			$this->nu_alternativaImpacto->LinkCustomAttributes = "";
			$this->nu_alternativaImpacto->HrefValue = "";
			$this->nu_alternativaImpacto->TooltipValue = "";

			// ds_sistemas
			$this->ds_sistemas->LinkCustomAttributes = "";
			$this->ds_sistemas->HrefValue = "";
			$this->ds_sistemas->TooltipValue = "";

			// ds_impactoNaoImplem
			$this->ds_impactoNaoImplem->LinkCustomAttributes = "";
			$this->ds_impactoNaoImplem->HrefValue = "";
			$this->ds_impactoNaoImplem->TooltipValue = "";

			// nu_alternativaAlinhamento
			$this->nu_alternativaAlinhamento->LinkCustomAttributes = "";
			$this->nu_alternativaAlinhamento->HrefValue = "";
			$this->nu_alternativaAlinhamento->TooltipValue = "";

			// nu_alternativaAbrangencia
			$this->nu_alternativaAbrangencia->LinkCustomAttributes = "";
			$this->nu_alternativaAbrangencia->HrefValue = "";
			$this->nu_alternativaAbrangencia->TooltipValue = "";

			// nu_alternativaUrgencia
			$this->nu_alternativaUrgencia->LinkCustomAttributes = "";
			$this->nu_alternativaUrgencia->HrefValue = "";
			$this->nu_alternativaUrgencia->TooltipValue = "";

			// dt_prazo
			$this->dt_prazo->LinkCustomAttributes = "";
			$this->dt_prazo->HrefValue = "";
			$this->dt_prazo->TooltipValue = "";

			// nu_alternativaTmpEstimado
			$this->nu_alternativaTmpEstimado->LinkCustomAttributes = "";
			$this->nu_alternativaTmpEstimado->HrefValue = "";
			$this->nu_alternativaTmpEstimado->TooltipValue = "";

			// nu_alternativaTmpFila
			$this->nu_alternativaTmpFila->LinkCustomAttributes = "";
			$this->nu_alternativaTmpFila->HrefValue = "";
			$this->nu_alternativaTmpFila->TooltipValue = "";

			// ic_implicacaoLegal
			$this->ic_implicacaoLegal->LinkCustomAttributes = "";
			$this->ic_implicacaoLegal->HrefValue = "";
			$this->ic_implicacaoLegal->TooltipValue = "";

			// ic_risco
			$this->ic_risco->LinkCustomAttributes = "";
			$this->ic_risco->HrefValue = "";
			$this->ic_risco->TooltipValue = "";

			// ic_stProspecto
			$this->ic_stProspecto->LinkCustomAttributes = "";
			$this->ic_stProspecto->HrefValue = "";
			$this->ic_stProspecto->TooltipValue = "";

			// ds_observacoes
			$this->ds_observacoes->LinkCustomAttributes = "";
			$this->ds_observacoes->HrefValue = "";
			$this->ds_observacoes->TooltipValue = "";

			// ic_ativo
			$this->ic_ativo->LinkCustomAttributes = "";
			$this->ic_ativo->HrefValue = "";
			$this->ic_ativo->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// nu_prospecto
			$this->nu_prospecto->EditCustomAttributes = "";
			$this->nu_prospecto->EditValue = $this->nu_prospecto->CurrentValue;
			$this->nu_prospecto->ViewCustomAttributes = "";

			// no_prospecto
			$this->no_prospecto->EditCustomAttributes = "";
			$this->no_prospecto->EditValue = ew_HtmlEncode($this->no_prospecto->CurrentValue);
			$this->no_prospecto->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_prospecto->FldCaption()));

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

			// no_solicitante
			$this->no_solicitante->EditCustomAttributes = "";
			$this->no_solicitante->EditValue = ew_HtmlEncode($this->no_solicitante->CurrentValue);
			$this->no_solicitante->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_solicitante->FldCaption()));

			// no_patrocinador
			$this->no_patrocinador->EditCustomAttributes = "";
			$this->no_patrocinador->EditValue = ew_HtmlEncode($this->no_patrocinador->CurrentValue);
			$this->no_patrocinador->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_patrocinador->FldCaption()));

			// ar_entidade
			$this->ar_entidade->EditCustomAttributes = "";
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
			$this->Lookup_Selecting($this->ar_entidade, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_organizacao] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->ar_entidade->EditValue = $arwrk;

			// ar_nivel
			$this->ar_nivel->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ar_nivel->FldTagValue(1), $this->ar_nivel->FldTagCaption(1) <> "" ? $this->ar_nivel->FldTagCaption(1) : $this->ar_nivel->FldTagValue(1));
			$arwrk[] = array($this->ar_nivel->FldTagValue(2), $this->ar_nivel->FldTagCaption(2) <> "" ? $this->ar_nivel->FldTagCaption(2) : $this->ar_nivel->FldTagValue(2));
			$this->ar_nivel->EditValue = $arwrk;

			// nu_categoriaProspecto
			$this->nu_categoriaProspecto->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_categoria], [no_categoria] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[catprospecto]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_categoriaProspecto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_categoria] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_categoriaProspecto->EditValue = $arwrk;

			// nu_alternativaImpacto
			$this->nu_alternativaImpacto->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativaAvaliacao], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[criterioavaliacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S' AND [nu_criterioPrioridade] = 10";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_alternativaImpacto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_alternativaImpacto->EditValue = $arwrk;

			// ds_sistemas
			$this->ds_sistemas->EditCustomAttributes = "";
			$this->ds_sistemas->EditValue = $this->ds_sistemas->CurrentValue;
			$this->ds_sistemas->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_sistemas->FldCaption()));

			// ds_impactoNaoImplem
			$this->ds_impactoNaoImplem->EditCustomAttributes = "";
			$this->ds_impactoNaoImplem->EditValue = $this->ds_impactoNaoImplem->CurrentValue;
			$this->ds_impactoNaoImplem->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_impactoNaoImplem->FldCaption()));

			// nu_alternativaAlinhamento
			$this->nu_alternativaAlinhamento->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativaAvaliacao], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[criterioavaliacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S' AND [nu_criterioPrioridade] = '11'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_alternativaAlinhamento, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_alternativaAlinhamento->EditValue = $arwrk;

			// nu_alternativaAbrangencia
			$this->nu_alternativaAbrangencia->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativaAvaliacao], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[criterioavaliacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S' AND [nu_criterioPrioridade] = 12";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_alternativaAbrangencia, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_alternativaAbrangencia->EditValue = $arwrk;

			// nu_alternativaUrgencia
			$this->nu_alternativaUrgencia->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativaAvaliacao], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[criterioavaliacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S' AND [nu_criterioPrioridade] = 13";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_alternativaUrgencia, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_alternativaUrgencia->EditValue = $arwrk;

			// dt_prazo
			$this->dt_prazo->EditCustomAttributes = "";
			$this->dt_prazo->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->dt_prazo->CurrentValue, 7));
			$this->dt_prazo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dt_prazo->FldCaption()));

			// nu_alternativaTmpEstimado
			$this->nu_alternativaTmpEstimado->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativaAvaliacao], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[criterioavaliacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S' AND [nu_criterioPrioridade] = 14";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_alternativaTmpEstimado, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_alternativaTmpEstimado->EditValue = $arwrk;

			// nu_alternativaTmpFila
			$this->nu_alternativaTmpFila->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativaAvaliacao], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[criterioavaliacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S' AND [nu_criterioPrioridade] = 15";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_alternativaTmpFila, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_alternativaTmpFila->EditValue = $arwrk;

			// ic_implicacaoLegal
			$this->ic_implicacaoLegal->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_implicacaoLegal->FldTagValue(1), $this->ic_implicacaoLegal->FldTagCaption(1) <> "" ? $this->ic_implicacaoLegal->FldTagCaption(1) : $this->ic_implicacaoLegal->FldTagValue(1));
			$arwrk[] = array($this->ic_implicacaoLegal->FldTagValue(2), $this->ic_implicacaoLegal->FldTagCaption(2) <> "" ? $this->ic_implicacaoLegal->FldTagCaption(2) : $this->ic_implicacaoLegal->FldTagValue(2));
			$this->ic_implicacaoLegal->EditValue = $arwrk;

			// ic_risco
			$this->ic_risco->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_risco->FldTagValue(1), $this->ic_risco->FldTagCaption(1) <> "" ? $this->ic_risco->FldTagCaption(1) : $this->ic_risco->FldTagValue(1));
			$arwrk[] = array($this->ic_risco->FldTagValue(2), $this->ic_risco->FldTagCaption(2) <> "" ? $this->ic_risco->FldTagCaption(2) : $this->ic_risco->FldTagValue(2));
			$arwrk[] = array($this->ic_risco->FldTagValue(3), $this->ic_risco->FldTagCaption(3) <> "" ? $this->ic_risco->FldTagCaption(3) : $this->ic_risco->FldTagValue(3));
			$this->ic_risco->EditValue = $arwrk;

			// ic_stProspecto
			$this->ic_stProspecto->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_stProspecto->FldTagValue(1), $this->ic_stProspecto->FldTagCaption(1) <> "" ? $this->ic_stProspecto->FldTagCaption(1) : $this->ic_stProspecto->FldTagValue(1));
			$arwrk[] = array($this->ic_stProspecto->FldTagValue(2), $this->ic_stProspecto->FldTagCaption(2) <> "" ? $this->ic_stProspecto->FldTagCaption(2) : $this->ic_stProspecto->FldTagValue(2));
			$arwrk[] = array($this->ic_stProspecto->FldTagValue(3), $this->ic_stProspecto->FldTagCaption(3) <> "" ? $this->ic_stProspecto->FldTagCaption(3) : $this->ic_stProspecto->FldTagValue(3));
			$arwrk[] = array($this->ic_stProspecto->FldTagValue(4), $this->ic_stProspecto->FldTagCaption(4) <> "" ? $this->ic_stProspecto->FldTagCaption(4) : $this->ic_stProspecto->FldTagValue(4));
			$arwrk[] = array($this->ic_stProspecto->FldTagValue(5), $this->ic_stProspecto->FldTagCaption(5) <> "" ? $this->ic_stProspecto->FldTagCaption(5) : $this->ic_stProspecto->FldTagValue(5));
			$this->ic_stProspecto->EditValue = $arwrk;

			// ds_observacoes
			$this->ds_observacoes->EditCustomAttributes = "";
			$this->ds_observacoes->EditValue = $this->ds_observacoes->CurrentValue;
			$this->ds_observacoes->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_observacoes->FldCaption()));

			// ic_ativo
			$this->ic_ativo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_ativo->FldTagValue(1), $this->ic_ativo->FldTagCaption(1) <> "" ? $this->ic_ativo->FldTagCaption(1) : $this->ic_ativo->FldTagValue(1));
			$arwrk[] = array($this->ic_ativo->FldTagValue(2), $this->ic_ativo->FldTagCaption(2) <> "" ? $this->ic_ativo->FldTagCaption(2) : $this->ic_ativo->FldTagValue(2));
			$this->ic_ativo->EditValue = $arwrk;

			// Edit refer script
			// nu_prospecto

			$this->nu_prospecto->HrefValue = "";

			// no_prospecto
			$this->no_prospecto->HrefValue = "";

			// nu_area
			$this->nu_area->HrefValue = "";

			// no_solicitante
			$this->no_solicitante->HrefValue = "";

			// no_patrocinador
			$this->no_patrocinador->HrefValue = "";

			// ar_entidade
			$this->ar_entidade->HrefValue = "";

			// ar_nivel
			$this->ar_nivel->HrefValue = "";

			// nu_categoriaProspecto
			$this->nu_categoriaProspecto->HrefValue = "";

			// nu_alternativaImpacto
			$this->nu_alternativaImpacto->HrefValue = "";

			// ds_sistemas
			$this->ds_sistemas->HrefValue = "";

			// ds_impactoNaoImplem
			$this->ds_impactoNaoImplem->HrefValue = "";

			// nu_alternativaAlinhamento
			$this->nu_alternativaAlinhamento->HrefValue = "";

			// nu_alternativaAbrangencia
			$this->nu_alternativaAbrangencia->HrefValue = "";

			// nu_alternativaUrgencia
			$this->nu_alternativaUrgencia->HrefValue = "";

			// dt_prazo
			$this->dt_prazo->HrefValue = "";

			// nu_alternativaTmpEstimado
			$this->nu_alternativaTmpEstimado->HrefValue = "";

			// nu_alternativaTmpFila
			$this->nu_alternativaTmpFila->HrefValue = "";

			// ic_implicacaoLegal
			$this->ic_implicacaoLegal->HrefValue = "";

			// ic_risco
			$this->ic_risco->HrefValue = "";

			// ic_stProspecto
			$this->ic_stProspecto->HrefValue = "";

			// ds_observacoes
			$this->ds_observacoes->HrefValue = "";

			// ic_ativo
			$this->ic_ativo->HrefValue = "";
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
		if (!$this->no_prospecto->FldIsDetailKey && !is_null($this->no_prospecto->FormValue) && $this->no_prospecto->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_prospecto->FldCaption());
		}
		if (!$this->nu_area->FldIsDetailKey && !is_null($this->nu_area->FormValue) && $this->nu_area->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_area->FldCaption());
		}
		if ($this->ar_entidade->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ar_entidade->FldCaption());
		}
		if ($this->ar_nivel->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ar_nivel->FldCaption());
		}
		if (!$this->nu_categoriaProspecto->FldIsDetailKey && !is_null($this->nu_categoriaProspecto->FormValue) && $this->nu_categoriaProspecto->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_categoriaProspecto->FldCaption());
		}
		if (!$this->nu_alternativaImpacto->FldIsDetailKey && !is_null($this->nu_alternativaImpacto->FormValue) && $this->nu_alternativaImpacto->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_alternativaImpacto->FldCaption());
		}
		if (!$this->nu_alternativaAlinhamento->FldIsDetailKey && !is_null($this->nu_alternativaAlinhamento->FormValue) && $this->nu_alternativaAlinhamento->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_alternativaAlinhamento->FldCaption());
		}
		if (!$this->nu_alternativaAbrangencia->FldIsDetailKey && !is_null($this->nu_alternativaAbrangencia->FormValue) && $this->nu_alternativaAbrangencia->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_alternativaAbrangencia->FldCaption());
		}
		if (!$this->nu_alternativaUrgencia->FldIsDetailKey && !is_null($this->nu_alternativaUrgencia->FormValue) && $this->nu_alternativaUrgencia->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_alternativaUrgencia->FldCaption());
		}
		if (!ew_CheckEuroDate($this->dt_prazo->FormValue)) {
			ew_AddMessage($gsFormError, $this->dt_prazo->FldErrMsg());
		}
		if (!$this->nu_alternativaTmpEstimado->FldIsDetailKey && !is_null($this->nu_alternativaTmpEstimado->FormValue) && $this->nu_alternativaTmpEstimado->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_alternativaTmpEstimado->FldCaption());
		}
		if (!$this->nu_alternativaTmpFila->FldIsDetailKey && !is_null($this->nu_alternativaTmpFila->FormValue) && $this->nu_alternativaTmpFila->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_alternativaTmpFila->FldCaption());
		}
		if ($this->ic_implicacaoLegal->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_implicacaoLegal->FldCaption());
		}
		if ($this->ic_risco->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_risco->FldCaption());
		}
		if ($this->ic_stProspecto->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_stProspecto->FldCaption());
		}
		if ($this->ic_ativo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_ativo->FldCaption());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("prospecto_itempdti", $DetailTblVar) && $GLOBALS["prospecto_itempdti"]->DetailEdit) {
			if (!isset($GLOBALS["prospecto_itempdti_grid"])) $GLOBALS["prospecto_itempdti_grid"] = new cprospecto_itempdti_grid(); // get detail page object
			$GLOBALS["prospecto_itempdti_grid"]->ValidateGridForm();
		}
		if (in_array("prospectoocorrencias", $DetailTblVar) && $GLOBALS["prospectoocorrencias"]->DetailEdit) {
			if (!isset($GLOBALS["prospectoocorrencias_grid"])) $GLOBALS["prospectoocorrencias_grid"] = new cprospectoocorrencias_grid(); // get detail page object
			$GLOBALS["prospectoocorrencias_grid"]->ValidateGridForm();
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

			// Begin transaction
			if ($this->getCurrentDetailTable() <> "")
				$conn->BeginTrans();

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// no_prospecto
			$this->no_prospecto->SetDbValueDef($rsnew, $this->no_prospecto->CurrentValue, NULL, $this->no_prospecto->ReadOnly);

			// nu_area
			$this->nu_area->SetDbValueDef($rsnew, $this->nu_area->CurrentValue, NULL, $this->nu_area->ReadOnly);

			// no_solicitante
			$this->no_solicitante->SetDbValueDef($rsnew, $this->no_solicitante->CurrentValue, NULL, $this->no_solicitante->ReadOnly);

			// no_patrocinador
			$this->no_patrocinador->SetDbValueDef($rsnew, $this->no_patrocinador->CurrentValue, NULL, $this->no_patrocinador->ReadOnly);

			// ar_entidade
			$this->ar_entidade->SetDbValueDef($rsnew, $this->ar_entidade->CurrentValue, NULL, $this->ar_entidade->ReadOnly);

			// ar_nivel
			$this->ar_nivel->SetDbValueDef($rsnew, $this->ar_nivel->CurrentValue, NULL, $this->ar_nivel->ReadOnly);

			// nu_categoriaProspecto
			$this->nu_categoriaProspecto->SetDbValueDef($rsnew, $this->nu_categoriaProspecto->CurrentValue, NULL, $this->nu_categoriaProspecto->ReadOnly);

			// nu_alternativaImpacto
			$this->nu_alternativaImpacto->SetDbValueDef($rsnew, $this->nu_alternativaImpacto->CurrentValue, NULL, $this->nu_alternativaImpacto->ReadOnly);

			// ds_sistemas
			$this->ds_sistemas->SetDbValueDef($rsnew, $this->ds_sistemas->CurrentValue, NULL, $this->ds_sistemas->ReadOnly);

			// ds_impactoNaoImplem
			$this->ds_impactoNaoImplem->SetDbValueDef($rsnew, $this->ds_impactoNaoImplem->CurrentValue, NULL, $this->ds_impactoNaoImplem->ReadOnly);

			// nu_alternativaAlinhamento
			$this->nu_alternativaAlinhamento->SetDbValueDef($rsnew, $this->nu_alternativaAlinhamento->CurrentValue, NULL, $this->nu_alternativaAlinhamento->ReadOnly);

			// nu_alternativaAbrangencia
			$this->nu_alternativaAbrangencia->SetDbValueDef($rsnew, $this->nu_alternativaAbrangencia->CurrentValue, NULL, $this->nu_alternativaAbrangencia->ReadOnly);

			// nu_alternativaUrgencia
			$this->nu_alternativaUrgencia->SetDbValueDef($rsnew, $this->nu_alternativaUrgencia->CurrentValue, NULL, $this->nu_alternativaUrgencia->ReadOnly);

			// dt_prazo
			$this->dt_prazo->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->dt_prazo->CurrentValue, 7), NULL, $this->dt_prazo->ReadOnly);

			// nu_alternativaTmpEstimado
			$this->nu_alternativaTmpEstimado->SetDbValueDef($rsnew, $this->nu_alternativaTmpEstimado->CurrentValue, NULL, $this->nu_alternativaTmpEstimado->ReadOnly);

			// nu_alternativaTmpFila
			$this->nu_alternativaTmpFila->SetDbValueDef($rsnew, $this->nu_alternativaTmpFila->CurrentValue, NULL, $this->nu_alternativaTmpFila->ReadOnly);

			// ic_implicacaoLegal
			$this->ic_implicacaoLegal->SetDbValueDef($rsnew, $this->ic_implicacaoLegal->CurrentValue, NULL, $this->ic_implicacaoLegal->ReadOnly);

			// ic_risco
			$this->ic_risco->SetDbValueDef($rsnew, $this->ic_risco->CurrentValue, NULL, $this->ic_risco->ReadOnly);

			// ic_stProspecto
			$this->ic_stProspecto->SetDbValueDef($rsnew, $this->ic_stProspecto->CurrentValue, NULL, $this->ic_stProspecto->ReadOnly);

			// ds_observacoes
			$this->ds_observacoes->SetDbValueDef($rsnew, $this->ds_observacoes->CurrentValue, NULL, $this->ds_observacoes->ReadOnly);

			// ic_ativo
			$this->ic_ativo->SetDbValueDef($rsnew, $this->ic_ativo->CurrentValue, NULL, $this->ic_ativo->ReadOnly);

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

				// Update detail records
				if ($EditRow) {
					$DetailTblVar = explode(",", $this->getCurrentDetailTable());
					if (in_array("prospecto_itempdti", $DetailTblVar) && $GLOBALS["prospecto_itempdti"]->DetailEdit) {
						if (!isset($GLOBALS["prospecto_itempdti_grid"])) $GLOBALS["prospecto_itempdti_grid"] = new cprospecto_itempdti_grid(); // Get detail page object
						$EditRow = $GLOBALS["prospecto_itempdti_grid"]->GridUpdate();
					}
					if (in_array("prospectoocorrencias", $DetailTblVar) && $GLOBALS["prospectoocorrencias"]->DetailEdit) {
						if (!isset($GLOBALS["prospectoocorrencias_grid"])) $GLOBALS["prospectoocorrencias_grid"] = new cprospectoocorrencias_grid(); // Get detail page object
						$EditRow = $GLOBALS["prospectoocorrencias_grid"]->GridUpdate();
					}
				}

				// Commit/Rollback transaction
				if ($this->getCurrentDetailTable() <> "") {
					if ($EditRow) {
						$conn->CommitTrans(); // Commit transaction
					} else {
						$conn->RollbackTrans(); // Rollback transaction
					}
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
			if ($sMasterTblVar == "rprospresumo") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_prospecto"] <> "") {
					$GLOBALS["rprospresumo"]->nu_prospecto->setQueryStringValue($_GET["nu_prospecto"]);
					$this->nu_prospecto->setQueryStringValue($GLOBALS["rprospresumo"]->nu_prospecto->QueryStringValue);
					$this->nu_prospecto->setSessionValue($this->nu_prospecto->QueryStringValue);
					if (!is_numeric($GLOBALS["rprospresumo"]->nu_prospecto->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "rprospresumo") {
				if ($this->nu_prospecto->QueryStringValue == "") $this->nu_prospecto->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); //  Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
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
			if (in_array("prospecto_itempdti", $DetailTblVar)) {
				if (!isset($GLOBALS["prospecto_itempdti_grid"]))
					$GLOBALS["prospecto_itempdti_grid"] = new cprospecto_itempdti_grid;
				if ($GLOBALS["prospecto_itempdti_grid"]->DetailEdit) {
					$GLOBALS["prospecto_itempdti_grid"]->CurrentMode = "edit";
					$GLOBALS["prospecto_itempdti_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["prospecto_itempdti_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["prospecto_itempdti_grid"]->setStartRecordNumber(1);
					$GLOBALS["prospecto_itempdti_grid"]->nu_prospecto->FldIsDetailKey = TRUE;
					$GLOBALS["prospecto_itempdti_grid"]->nu_prospecto->CurrentValue = $this->nu_prospecto->CurrentValue;
					$GLOBALS["prospecto_itempdti_grid"]->nu_prospecto->setSessionValue($GLOBALS["prospecto_itempdti_grid"]->nu_prospecto->CurrentValue);
				}
			}
			if (in_array("prospectoocorrencias", $DetailTblVar)) {
				if (!isset($GLOBALS["prospectoocorrencias_grid"]))
					$GLOBALS["prospectoocorrencias_grid"] = new cprospectoocorrencias_grid;
				if ($GLOBALS["prospectoocorrencias_grid"]->DetailEdit) {
					$GLOBALS["prospectoocorrencias_grid"]->CurrentMode = "edit";
					$GLOBALS["prospectoocorrencias_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["prospectoocorrencias_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["prospectoocorrencias_grid"]->setStartRecordNumber(1);
					$GLOBALS["prospectoocorrencias_grid"]->nu_prospecto->FldIsDetailKey = TRUE;
					$GLOBALS["prospectoocorrencias_grid"]->nu_prospecto->CurrentValue = $this->nu_prospecto->CurrentValue;
					$GLOBALS["prospectoocorrencias_grid"]->nu_prospecto->setSessionValue($GLOBALS["prospectoocorrencias_grid"]->nu_prospecto->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "prospectolist.php", $this->TableVar);
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
if (!isset($prospecto_edit)) $prospecto_edit = new cprospecto_edit();

// Page init
$prospecto_edit->Page_Init();

// Page main
$prospecto_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$prospecto_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var prospecto_edit = new ew_Page("prospecto_edit");
prospecto_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = prospecto_edit.PageID; // For backward compatibility

// Form object
var fprospectoedit = new ew_Form("fprospectoedit");

// Validate form
fprospectoedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_no_prospecto");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($prospecto->no_prospecto->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_area");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($prospecto->nu_area->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ar_entidade[]");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($prospecto->ar_entidade->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ar_nivel[]");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($prospecto->ar_nivel->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_categoriaProspecto");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($prospecto->nu_categoriaProspecto->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_alternativaImpacto");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($prospecto->nu_alternativaImpacto->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_alternativaAlinhamento");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($prospecto->nu_alternativaAlinhamento->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_alternativaAbrangencia");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($prospecto->nu_alternativaAbrangencia->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_alternativaUrgencia");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($prospecto->nu_alternativaUrgencia->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dt_prazo");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($prospecto->dt_prazo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nu_alternativaTmpEstimado");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($prospecto->nu_alternativaTmpEstimado->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_alternativaTmpFila");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($prospecto->nu_alternativaTmpFila->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_implicacaoLegal");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($prospecto->ic_implicacaoLegal->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_risco");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($prospecto->ic_risco->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_stProspecto");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($prospecto->ic_stProspecto->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_ativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($prospecto->ic_ativo->FldCaption()) ?>");

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
fprospectoedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fprospectoedit.ValidateRequired = true;
<?php } else { ?>
fprospectoedit.ValidateRequired = false; 
<?php } ?>

// Multi-Page properties
fprospectoedit.MultiPage = new ew_MultiPage("fprospectoedit",
	[["x_nu_prospecto",1],["x_no_prospecto",1],["x_nu_area",1],["x_no_solicitante",1],["x_no_patrocinador",1],["x_ar_entidade",1],["x_ar_nivel",1],["x_nu_categoriaProspecto",1],["x_nu_alternativaImpacto",1],["x_ds_sistemas",1],["x_ds_impactoNaoImplem",1],["x_nu_alternativaAlinhamento",2],["x_nu_alternativaAbrangencia",2],["x_nu_alternativaUrgencia",2],["x_dt_prazo",1],["x_nu_alternativaTmpEstimado",2],["x_nu_alternativaTmpFila",2],["x_ic_implicacaoLegal",2],["x_ic_risco",1],["x_ic_stProspecto",1],["x_ds_observacoes",1],["x_ic_ativo",1]]
);

// Dynamic selection lists
fprospectoedit.Lists["x_nu_area"] = {"LinkField":"x_nu_area","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_area","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fprospectoedit.Lists["x_ar_entidade[]"] = {"LinkField":"x_nu_organizacao","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_organizacao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fprospectoedit.Lists["x_nu_categoriaProspecto"] = {"LinkField":"x_nu_categoria","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_categoria","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fprospectoedit.Lists["x_nu_alternativaImpacto"] = {"LinkField":"x_nu_alternativaAvaliacao","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fprospectoedit.Lists["x_nu_alternativaAlinhamento"] = {"LinkField":"x_nu_alternativaAvaliacao","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fprospectoedit.Lists["x_nu_alternativaAbrangencia"] = {"LinkField":"x_nu_alternativaAvaliacao","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fprospectoedit.Lists["x_nu_alternativaUrgencia"] = {"LinkField":"x_nu_alternativaAvaliacao","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fprospectoedit.Lists["x_nu_alternativaTmpEstimado"] = {"LinkField":"x_nu_alternativaAvaliacao","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fprospectoedit.Lists["x_nu_alternativaTmpFila"] = {"LinkField":"x_nu_alternativaAvaliacao","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $prospecto_edit->ShowPageHeader(); ?>
<?php
$prospecto_edit->ShowMessage();
?>
<form name="fprospectoedit" id="fprospectoedit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="prospecto">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table class="ewStdTable"><tbody><tr><td>
<div class="tabbable" id="prospecto_edit">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_prospecto1" data-toggle="tab"><?php echo $prospecto->PageCaption(1) ?></a></li>
		<li><a href="#tab_prospecto2" data-toggle="tab"><?php echo $prospecto->PageCaption(2) ?></a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab_prospecto1">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_prospectoedit1" class="table table-bordered table-striped">
<?php if ($prospecto->nu_prospecto->Visible) { // nu_prospecto ?>
	<tr id="r_nu_prospecto">
		<td><span id="elh_prospecto_nu_prospecto"><?php echo $prospecto->nu_prospecto->FldCaption() ?></span></td>
		<td<?php echo $prospecto->nu_prospecto->CellAttributes() ?>>
<span id="el_prospecto_nu_prospecto" class="control-group">
<span<?php echo $prospecto->nu_prospecto->ViewAttributes() ?>>
<?php echo $prospecto->nu_prospecto->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_prospecto" name="x_nu_prospecto" id="x_nu_prospecto" value="<?php echo ew_HtmlEncode($prospecto->nu_prospecto->CurrentValue) ?>">
<?php echo $prospecto->nu_prospecto->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($prospecto->no_prospecto->Visible) { // no_prospecto ?>
	<tr id="r_no_prospecto">
		<td><span id="elh_prospecto_no_prospecto"><?php echo $prospecto->no_prospecto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $prospecto->no_prospecto->CellAttributes() ?>>
<span id="el_prospecto_no_prospecto" class="control-group">
<input type="text" data-field="x_no_prospecto" name="x_no_prospecto" id="x_no_prospecto" size="75" maxlength="120" placeholder="<?php echo $prospecto->no_prospecto->PlaceHolder ?>" value="<?php echo $prospecto->no_prospecto->EditValue ?>"<?php echo $prospecto->no_prospecto->EditAttributes() ?>>
</span>
<?php echo $prospecto->no_prospecto->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($prospecto->nu_area->Visible) { // nu_area ?>
	<tr id="r_nu_area">
		<td><span id="elh_prospecto_nu_area"><?php echo $prospecto->nu_area->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $prospecto->nu_area->CellAttributes() ?>>
<span id="el_prospecto_nu_area" class="control-group">
<select data-field="x_nu_area" id="x_nu_area" name="x_nu_area"<?php echo $prospecto->nu_area->EditAttributes() ?>>
<?php
if (is_array($prospecto->nu_area->EditValue)) {
	$arwrk = $prospecto->nu_area->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($prospecto->nu_area->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fprospectoedit.Lists["x_nu_area"].Options = <?php echo (is_array($prospecto->nu_area->EditValue)) ? ew_ArrayToJson($prospecto->nu_area->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $prospecto->nu_area->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($prospecto->no_solicitante->Visible) { // no_solicitante ?>
	<tr id="r_no_solicitante">
		<td><span id="elh_prospecto_no_solicitante"><?php echo $prospecto->no_solicitante->FldCaption() ?></span></td>
		<td<?php echo $prospecto->no_solicitante->CellAttributes() ?>>
<span id="el_prospecto_no_solicitante" class="control-group">
<input type="text" data-field="x_no_solicitante" name="x_no_solicitante" id="x_no_solicitante" size="75" maxlength="120" placeholder="<?php echo $prospecto->no_solicitante->PlaceHolder ?>" value="<?php echo $prospecto->no_solicitante->EditValue ?>"<?php echo $prospecto->no_solicitante->EditAttributes() ?>>
</span>
<?php echo $prospecto->no_solicitante->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($prospecto->no_patrocinador->Visible) { // no_patrocinador ?>
	<tr id="r_no_patrocinador">
		<td><span id="elh_prospecto_no_patrocinador"><?php echo $prospecto->no_patrocinador->FldCaption() ?></span></td>
		<td<?php echo $prospecto->no_patrocinador->CellAttributes() ?>>
<span id="el_prospecto_no_patrocinador" class="control-group">
<input type="text" data-field="x_no_patrocinador" name="x_no_patrocinador" id="x_no_patrocinador" size="75" maxlength="120" placeholder="<?php echo $prospecto->no_patrocinador->PlaceHolder ?>" value="<?php echo $prospecto->no_patrocinador->EditValue ?>"<?php echo $prospecto->no_patrocinador->EditAttributes() ?>>
</span>
<?php echo $prospecto->no_patrocinador->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($prospecto->ar_entidade->Visible) { // ar_entidade ?>
	<tr id="r_ar_entidade">
		<td><span id="elh_prospecto_ar_entidade"><?php echo $prospecto->ar_entidade->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $prospecto->ar_entidade->CellAttributes() ?>>
<span id="el_prospecto_ar_entidade" class="control-group">
<div id="tp_x_ar_entidade" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME; ?>"><input type="checkbox" name="x_ar_entidade[]" id="x_ar_entidade[]" value="{value}"<?php echo $prospecto->ar_entidade->EditAttributes() ?>></div>
<div id="dsl_x_ar_entidade" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $prospecto->ar_entidade->EditValue;
if (is_array($arwrk)) {
	$armultiwrk= explode(",", strval($prospecto->ar_entidade->CurrentValue));
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = "";
		$cnt = count($armultiwrk);
		for ($ari = 0; $ari < $cnt; $ari++) {
			if (strval($arwrk[$rowcntwrk][0]) == trim(strval($armultiwrk[$ari]))) {
				$selwrk = " checked=\"checked\"";
				if ($selwrk <> "") $emptywrk = FALSE;
				break;
			}
		}

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="checkbox"><input type="checkbox" data-field="x_ar_entidade" name="x_ar_entidade[]" id="x_ar_entidade_<?php echo $rowcntwrk ?>[]" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $prospecto->ar_entidade->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
<script type="text/javascript">
fprospectoedit.Lists["x_ar_entidade[]"].Options = <?php echo (is_array($prospecto->ar_entidade->EditValue)) ? ew_ArrayToJson($prospecto->ar_entidade->EditValue, 0) : "[]" ?>;
</script>
</span>
<?php echo $prospecto->ar_entidade->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($prospecto->ar_nivel->Visible) { // ar_nivel ?>
	<tr id="r_ar_nivel">
		<td><span id="elh_prospecto_ar_nivel"><?php echo $prospecto->ar_nivel->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $prospecto->ar_nivel->CellAttributes() ?>>
<span id="el_prospecto_ar_nivel" class="control-group">
<div id="tp_x_ar_nivel" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME; ?>"><input type="checkbox" name="x_ar_nivel[]" id="x_ar_nivel[]" value="{value}"<?php echo $prospecto->ar_nivel->EditAttributes() ?>></div>
<div id="dsl_x_ar_nivel" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $prospecto->ar_nivel->EditValue;
if (is_array($arwrk)) {
	$armultiwrk= explode(",", strval($prospecto->ar_nivel->CurrentValue));
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = "";
		$cnt = count($armultiwrk);
		for ($ari = 0; $ari < $cnt; $ari++) {
			if (strval($arwrk[$rowcntwrk][0]) == trim(strval($armultiwrk[$ari]))) {
				$selwrk = " checked=\"checked\"";
				if ($selwrk <> "") $emptywrk = FALSE;
				break;
			}
		}

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="checkbox"><input type="checkbox" data-field="x_ar_nivel" name="x_ar_nivel[]" id="x_ar_nivel_<?php echo $rowcntwrk ?>[]" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $prospecto->ar_nivel->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $prospecto->ar_nivel->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($prospecto->nu_categoriaProspecto->Visible) { // nu_categoriaProspecto ?>
	<tr id="r_nu_categoriaProspecto">
		<td><span id="elh_prospecto_nu_categoriaProspecto"><?php echo $prospecto->nu_categoriaProspecto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $prospecto->nu_categoriaProspecto->CellAttributes() ?>>
<span id="el_prospecto_nu_categoriaProspecto" class="control-group">
<select data-field="x_nu_categoriaProspecto" id="x_nu_categoriaProspecto" name="x_nu_categoriaProspecto"<?php echo $prospecto->nu_categoriaProspecto->EditAttributes() ?>>
<?php
if (is_array($prospecto->nu_categoriaProspecto->EditValue)) {
	$arwrk = $prospecto->nu_categoriaProspecto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($prospecto->nu_categoriaProspecto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if (AllowAdd(CurrentProjectID() . "catprospecto")) { ?>
&nbsp;<a id="aol_x_nu_categoriaProspecto" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_nu_categoriaProspecto',url:'catprospectoaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $prospecto->nu_categoriaProspecto->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
fprospectoedit.Lists["x_nu_categoriaProspecto"].Options = <?php echo (is_array($prospecto->nu_categoriaProspecto->EditValue)) ? ew_ArrayToJson($prospecto->nu_categoriaProspecto->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $prospecto->nu_categoriaProspecto->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($prospecto->nu_alternativaImpacto->Visible) { // nu_alternativaImpacto ?>
	<tr id="r_nu_alternativaImpacto">
		<td><span id="elh_prospecto_nu_alternativaImpacto"><?php echo $prospecto->nu_alternativaImpacto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $prospecto->nu_alternativaImpacto->CellAttributes() ?>>
<span id="el_prospecto_nu_alternativaImpacto" class="control-group">
<select data-field="x_nu_alternativaImpacto" id="x_nu_alternativaImpacto" name="x_nu_alternativaImpacto"<?php echo $prospecto->nu_alternativaImpacto->EditAttributes() ?>>
<?php
if (is_array($prospecto->nu_alternativaImpacto->EditValue)) {
	$arwrk = $prospecto->nu_alternativaImpacto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($prospecto->nu_alternativaImpacto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fprospectoedit.Lists["x_nu_alternativaImpacto"].Options = <?php echo (is_array($prospecto->nu_alternativaImpacto->EditValue)) ? ew_ArrayToJson($prospecto->nu_alternativaImpacto->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $prospecto->nu_alternativaImpacto->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($prospecto->ds_sistemas->Visible) { // ds_sistemas ?>
	<tr id="r_ds_sistemas">
		<td><span id="elh_prospecto_ds_sistemas"><?php echo $prospecto->ds_sistemas->FldCaption() ?></span></td>
		<td<?php echo $prospecto->ds_sistemas->CellAttributes() ?>>
<span id="el_prospecto_ds_sistemas" class="control-group">
<textarea data-field="x_ds_sistemas" name="x_ds_sistemas" id="x_ds_sistemas" cols="35" rows="4" placeholder="<?php echo $prospecto->ds_sistemas->PlaceHolder ?>"<?php echo $prospecto->ds_sistemas->EditAttributes() ?>><?php echo $prospecto->ds_sistemas->EditValue ?></textarea>
</span>
<?php echo $prospecto->ds_sistemas->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($prospecto->ds_impactoNaoImplem->Visible) { // ds_impactoNaoImplem ?>
	<tr id="r_ds_impactoNaoImplem">
		<td><span id="elh_prospecto_ds_impactoNaoImplem"><?php echo $prospecto->ds_impactoNaoImplem->FldCaption() ?></span></td>
		<td<?php echo $prospecto->ds_impactoNaoImplem->CellAttributes() ?>>
<span id="el_prospecto_ds_impactoNaoImplem" class="control-group">
<textarea data-field="x_ds_impactoNaoImplem" name="x_ds_impactoNaoImplem" id="x_ds_impactoNaoImplem" cols="35" rows="4" placeholder="<?php echo $prospecto->ds_impactoNaoImplem->PlaceHolder ?>"<?php echo $prospecto->ds_impactoNaoImplem->EditAttributes() ?>><?php echo $prospecto->ds_impactoNaoImplem->EditValue ?></textarea>
</span>
<?php echo $prospecto->ds_impactoNaoImplem->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($prospecto->dt_prazo->Visible) { // dt_prazo ?>
	<tr id="r_dt_prazo">
		<td><span id="elh_prospecto_dt_prazo"><?php echo $prospecto->dt_prazo->FldCaption() ?></span></td>
		<td<?php echo $prospecto->dt_prazo->CellAttributes() ?>>
<span id="el_prospecto_dt_prazo" class="control-group">
<input type="text" data-field="x_dt_prazo" name="x_dt_prazo" id="x_dt_prazo" placeholder="<?php echo $prospecto->dt_prazo->PlaceHolder ?>" value="<?php echo $prospecto->dt_prazo->EditValue ?>"<?php echo $prospecto->dt_prazo->EditAttributes() ?>>
<?php if (!$prospecto->dt_prazo->ReadOnly && !$prospecto->dt_prazo->Disabled && @$prospecto->dt_prazo->EditAttrs["readonly"] == "" && @$prospecto->dt_prazo->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_dt_prazo" name="cal_x_dt_prazo" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x_dt_prazo" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fprospectoedit", "x_dt_prazo", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $prospecto->dt_prazo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($prospecto->ic_risco->Visible) { // ic_risco ?>
	<tr id="r_ic_risco">
		<td><span id="elh_prospecto_ic_risco"><?php echo $prospecto->ic_risco->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $prospecto->ic_risco->CellAttributes() ?>>
<span id="el_prospecto_ic_risco" class="control-group">
<div id="tp_x_ic_risco" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_risco" id="x_ic_risco" value="{value}"<?php echo $prospecto->ic_risco->EditAttributes() ?>></div>
<div id="dsl_x_ic_risco" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $prospecto->ic_risco->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($prospecto->ic_risco->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_risco" name="x_ic_risco" id="x_ic_risco_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $prospecto->ic_risco->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $prospecto->ic_risco->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($prospecto->ic_stProspecto->Visible) { // ic_stProspecto ?>
	<tr id="r_ic_stProspecto">
		<td><span id="elh_prospecto_ic_stProspecto"><?php echo $prospecto->ic_stProspecto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $prospecto->ic_stProspecto->CellAttributes() ?>>
<span id="el_prospecto_ic_stProspecto" class="control-group">
<div id="tp_x_ic_stProspecto" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_stProspecto" id="x_ic_stProspecto" value="{value}"<?php echo $prospecto->ic_stProspecto->EditAttributes() ?>></div>
<div id="dsl_x_ic_stProspecto" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $prospecto->ic_stProspecto->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($prospecto->ic_stProspecto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_stProspecto" name="x_ic_stProspecto" id="x_ic_stProspecto_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $prospecto->ic_stProspecto->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $prospecto->ic_stProspecto->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($prospecto->ds_observacoes->Visible) { // ds_observacoes ?>
	<tr id="r_ds_observacoes">
		<td><span id="elh_prospecto_ds_observacoes"><?php echo $prospecto->ds_observacoes->FldCaption() ?></span></td>
		<td<?php echo $prospecto->ds_observacoes->CellAttributes() ?>>
<span id="el_prospecto_ds_observacoes" class="control-group">
<textarea data-field="x_ds_observacoes" name="x_ds_observacoes" id="x_ds_observacoes" cols="75" rows="4" placeholder="<?php echo $prospecto->ds_observacoes->PlaceHolder ?>"<?php echo $prospecto->ds_observacoes->EditAttributes() ?>><?php echo $prospecto->ds_observacoes->EditValue ?></textarea>
</span>
<?php echo $prospecto->ds_observacoes->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($prospecto->ic_ativo->Visible) { // ic_ativo ?>
	<tr id="r_ic_ativo">
		<td><span id="elh_prospecto_ic_ativo"><?php echo $prospecto->ic_ativo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $prospecto->ic_ativo->CellAttributes() ?>>
<span id="el_prospecto_ic_ativo" class="control-group">
<div id="tp_x_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_ativo" id="x_ic_ativo" value="{value}"<?php echo $prospecto->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $prospecto->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($prospecto->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x_ic_ativo" id="x_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $prospecto->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $prospecto->ic_ativo->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_prospecto2">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_prospectoedit2" class="table table-bordered table-striped">
<?php if ($prospecto->nu_alternativaAlinhamento->Visible) { // nu_alternativaAlinhamento ?>
	<tr id="r_nu_alternativaAlinhamento">
		<td><span id="elh_prospecto_nu_alternativaAlinhamento"><?php echo $prospecto->nu_alternativaAlinhamento->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $prospecto->nu_alternativaAlinhamento->CellAttributes() ?>>
<span id="el_prospecto_nu_alternativaAlinhamento" class="control-group">
<select data-field="x_nu_alternativaAlinhamento" id="x_nu_alternativaAlinhamento" name="x_nu_alternativaAlinhamento"<?php echo $prospecto->nu_alternativaAlinhamento->EditAttributes() ?>>
<?php
if (is_array($prospecto->nu_alternativaAlinhamento->EditValue)) {
	$arwrk = $prospecto->nu_alternativaAlinhamento->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($prospecto->nu_alternativaAlinhamento->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fprospectoedit.Lists["x_nu_alternativaAlinhamento"].Options = <?php echo (is_array($prospecto->nu_alternativaAlinhamento->EditValue)) ? ew_ArrayToJson($prospecto->nu_alternativaAlinhamento->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $prospecto->nu_alternativaAlinhamento->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($prospecto->nu_alternativaAbrangencia->Visible) { // nu_alternativaAbrangencia ?>
	<tr id="r_nu_alternativaAbrangencia">
		<td><span id="elh_prospecto_nu_alternativaAbrangencia"><?php echo $prospecto->nu_alternativaAbrangencia->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $prospecto->nu_alternativaAbrangencia->CellAttributes() ?>>
<span id="el_prospecto_nu_alternativaAbrangencia" class="control-group">
<select data-field="x_nu_alternativaAbrangencia" id="x_nu_alternativaAbrangencia" name="x_nu_alternativaAbrangencia"<?php echo $prospecto->nu_alternativaAbrangencia->EditAttributes() ?>>
<?php
if (is_array($prospecto->nu_alternativaAbrangencia->EditValue)) {
	$arwrk = $prospecto->nu_alternativaAbrangencia->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($prospecto->nu_alternativaAbrangencia->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fprospectoedit.Lists["x_nu_alternativaAbrangencia"].Options = <?php echo (is_array($prospecto->nu_alternativaAbrangencia->EditValue)) ? ew_ArrayToJson($prospecto->nu_alternativaAbrangencia->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $prospecto->nu_alternativaAbrangencia->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($prospecto->nu_alternativaUrgencia->Visible) { // nu_alternativaUrgencia ?>
	<tr id="r_nu_alternativaUrgencia">
		<td><span id="elh_prospecto_nu_alternativaUrgencia"><?php echo $prospecto->nu_alternativaUrgencia->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $prospecto->nu_alternativaUrgencia->CellAttributes() ?>>
<span id="el_prospecto_nu_alternativaUrgencia" class="control-group">
<select data-field="x_nu_alternativaUrgencia" id="x_nu_alternativaUrgencia" name="x_nu_alternativaUrgencia"<?php echo $prospecto->nu_alternativaUrgencia->EditAttributes() ?>>
<?php
if (is_array($prospecto->nu_alternativaUrgencia->EditValue)) {
	$arwrk = $prospecto->nu_alternativaUrgencia->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($prospecto->nu_alternativaUrgencia->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fprospectoedit.Lists["x_nu_alternativaUrgencia"].Options = <?php echo (is_array($prospecto->nu_alternativaUrgencia->EditValue)) ? ew_ArrayToJson($prospecto->nu_alternativaUrgencia->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $prospecto->nu_alternativaUrgencia->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($prospecto->nu_alternativaTmpEstimado->Visible) { // nu_alternativaTmpEstimado ?>
	<tr id="r_nu_alternativaTmpEstimado">
		<td><span id="elh_prospecto_nu_alternativaTmpEstimado"><?php echo $prospecto->nu_alternativaTmpEstimado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $prospecto->nu_alternativaTmpEstimado->CellAttributes() ?>>
<span id="el_prospecto_nu_alternativaTmpEstimado" class="control-group">
<select data-field="x_nu_alternativaTmpEstimado" id="x_nu_alternativaTmpEstimado" name="x_nu_alternativaTmpEstimado"<?php echo $prospecto->nu_alternativaTmpEstimado->EditAttributes() ?>>
<?php
if (is_array($prospecto->nu_alternativaTmpEstimado->EditValue)) {
	$arwrk = $prospecto->nu_alternativaTmpEstimado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($prospecto->nu_alternativaTmpEstimado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fprospectoedit.Lists["x_nu_alternativaTmpEstimado"].Options = <?php echo (is_array($prospecto->nu_alternativaTmpEstimado->EditValue)) ? ew_ArrayToJson($prospecto->nu_alternativaTmpEstimado->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $prospecto->nu_alternativaTmpEstimado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($prospecto->nu_alternativaTmpFila->Visible) { // nu_alternativaTmpFila ?>
	<tr id="r_nu_alternativaTmpFila">
		<td><span id="elh_prospecto_nu_alternativaTmpFila"><?php echo $prospecto->nu_alternativaTmpFila->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $prospecto->nu_alternativaTmpFila->CellAttributes() ?>>
<span id="el_prospecto_nu_alternativaTmpFila" class="control-group">
<select data-field="x_nu_alternativaTmpFila" id="x_nu_alternativaTmpFila" name="x_nu_alternativaTmpFila"<?php echo $prospecto->nu_alternativaTmpFila->EditAttributes() ?>>
<?php
if (is_array($prospecto->nu_alternativaTmpFila->EditValue)) {
	$arwrk = $prospecto->nu_alternativaTmpFila->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($prospecto->nu_alternativaTmpFila->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fprospectoedit.Lists["x_nu_alternativaTmpFila"].Options = <?php echo (is_array($prospecto->nu_alternativaTmpFila->EditValue)) ? ew_ArrayToJson($prospecto->nu_alternativaTmpFila->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $prospecto->nu_alternativaTmpFila->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($prospecto->ic_implicacaoLegal->Visible) { // ic_implicacaoLegal ?>
	<tr id="r_ic_implicacaoLegal">
		<td><span id="elh_prospecto_ic_implicacaoLegal"><?php echo $prospecto->ic_implicacaoLegal->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $prospecto->ic_implicacaoLegal->CellAttributes() ?>>
<span id="el_prospecto_ic_implicacaoLegal" class="control-group">
<div id="tp_x_ic_implicacaoLegal" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_implicacaoLegal" id="x_ic_implicacaoLegal" value="{value}"<?php echo $prospecto->ic_implicacaoLegal->EditAttributes() ?>></div>
<div id="dsl_x_ic_implicacaoLegal" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $prospecto->ic_implicacaoLegal->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($prospecto->ic_implicacaoLegal->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_implicacaoLegal" name="x_ic_implicacaoLegal" id="x_ic_implicacaoLegal_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $prospecto->ic_implicacaoLegal->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $prospecto->ic_implicacaoLegal->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
	</div>
</div>
</td></tr></tbody></table>
<?php
	if (in_array("prospecto_itempdti", explode(",", $prospecto->getCurrentDetailTable())) && $prospecto_itempdti->DetailEdit) {
?>
<?php include_once "prospecto_itempdtigrid.php" ?>
<?php } ?>
<?php
	if (in_array("prospectoocorrencias", explode(",", $prospecto->getCurrentDetailTable())) && $prospectoocorrencias->DetailEdit) {
?>
<?php include_once "prospectoocorrenciasgrid.php" ?>
<?php } ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fprospectoedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$prospecto_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$prospecto_edit->Page_Terminate();
?>
