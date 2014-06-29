<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "contagempfinfo.php" ?>
<?php include_once "solicitacaometricasinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "contagempf_agrupadorgridcls.php" ?>
<?php include_once "contagempf_funcaogridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$contagempf_add = NULL; // Initialize page object first

class ccontagempf_add extends ccontagempf {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'contagempf';

	// Page object name
	var $PageObjName = 'contagempf_add';

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

		// Table object (contagempf)
		if (!isset($GLOBALS["contagempf"])) {
			$GLOBALS["contagempf"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["contagempf"];
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
			define("EW_TABLE_NAME", 'contagempf', TRUE);

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
			$this->Page_Terminate("contagempflist.php");
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
			if (@$_GET["nu_contagem"] != "") {
				$this->nu_contagem->setQueryStringValue($_GET["nu_contagem"]);
				$this->setKey("nu_contagem", $this->nu_contagem->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_contagem", ""); // Clear key
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
					$this->Page_Terminate("contagempflist.php"); // No matching record, return to list
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
					if (ew_GetPageName($sReturnUrl) == "contagempfview.php")
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
	}

	// Load default values
	function LoadDefaultValues() {
		$this->nu_solMetricas->CurrentValue = NULL;
		$this->nu_solMetricas->OldValue = $this->nu_solMetricas->CurrentValue;
		$this->nu_tpMetrica->CurrentValue = NULL;
		$this->nu_tpMetrica->OldValue = $this->nu_tpMetrica->CurrentValue;
		$this->nu_tpContagem->CurrentValue = NULL;
		$this->nu_tpContagem->OldValue = $this->nu_tpContagem->CurrentValue;
		$this->nu_proposito->CurrentValue = NULL;
		$this->nu_proposito->OldValue = $this->nu_proposito->CurrentValue;
		$this->nu_sistema->CurrentValue = NULL;
		$this->nu_sistema->OldValue = $this->nu_sistema->CurrentValue;
		$this->nu_ambiente->CurrentValue = NULL;
		$this->nu_ambiente->OldValue = $this->nu_ambiente->CurrentValue;
		$this->nu_metodologia->CurrentValue = NULL;
		$this->nu_metodologia->OldValue = $this->nu_metodologia->CurrentValue;
		$this->nu_roteiro->CurrentValue = NULL;
		$this->nu_roteiro->OldValue = $this->nu_roteiro->CurrentValue;
		$this->nu_faseMedida->CurrentValue = NULL;
		$this->nu_faseMedida->OldValue = $this->nu_faseMedida->CurrentValue;
		$this->nu_usuarioLogado->CurrentValue = NULL;
		$this->nu_usuarioLogado->OldValue = $this->nu_usuarioLogado->CurrentValue;
		$this->dh_inicio->CurrentValue = NULL;
		$this->dh_inicio->OldValue = $this->dh_inicio->CurrentValue;
		$this->ic_stContagem->CurrentValue = NULL;
		$this->ic_stContagem->OldValue = $this->ic_stContagem->CurrentValue;
		$this->ar_fasesRoteiro->CurrentValue = NULL;
		$this->ar_fasesRoteiro->OldValue = $this->ar_fasesRoteiro->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_solMetricas->FldIsDetailKey) {
			$this->nu_solMetricas->setFormValue($objForm->GetValue("x_nu_solMetricas"));
		}
		if (!$this->nu_tpMetrica->FldIsDetailKey) {
			$this->nu_tpMetrica->setFormValue($objForm->GetValue("x_nu_tpMetrica"));
		}
		if (!$this->nu_tpContagem->FldIsDetailKey) {
			$this->nu_tpContagem->setFormValue($objForm->GetValue("x_nu_tpContagem"));
		}
		if (!$this->nu_proposito->FldIsDetailKey) {
			$this->nu_proposito->setFormValue($objForm->GetValue("x_nu_proposito"));
		}
		if (!$this->nu_sistema->FldIsDetailKey) {
			$this->nu_sistema->setFormValue($objForm->GetValue("x_nu_sistema"));
		}
		if (!$this->nu_ambiente->FldIsDetailKey) {
			$this->nu_ambiente->setFormValue($objForm->GetValue("x_nu_ambiente"));
		}
		if (!$this->nu_metodologia->FldIsDetailKey) {
			$this->nu_metodologia->setFormValue($objForm->GetValue("x_nu_metodologia"));
		}
		if (!$this->nu_roteiro->FldIsDetailKey) {
			$this->nu_roteiro->setFormValue($objForm->GetValue("x_nu_roteiro"));
		}
		if (!$this->nu_faseMedida->FldIsDetailKey) {
			$this->nu_faseMedida->setFormValue($objForm->GetValue("x_nu_faseMedida"));
		}
		if (!$this->nu_usuarioLogado->FldIsDetailKey) {
			$this->nu_usuarioLogado->setFormValue($objForm->GetValue("x_nu_usuarioLogado"));
		}
		if (!$this->dh_inicio->FldIsDetailKey) {
			$this->dh_inicio->setFormValue($objForm->GetValue("x_dh_inicio"));
			$this->dh_inicio->CurrentValue = ew_UnFormatDateTime($this->dh_inicio->CurrentValue, 7);
		}
		if (!$this->ic_stContagem->FldIsDetailKey) {
			$this->ic_stContagem->setFormValue($objForm->GetValue("x_ic_stContagem"));
		}
		if (!$this->ar_fasesRoteiro->FldIsDetailKey) {
			$this->ar_fasesRoteiro->setFormValue($objForm->GetValue("x_ar_fasesRoteiro"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->nu_solMetricas->CurrentValue = $this->nu_solMetricas->FormValue;
		$this->nu_tpMetrica->CurrentValue = $this->nu_tpMetrica->FormValue;
		$this->nu_tpContagem->CurrentValue = $this->nu_tpContagem->FormValue;
		$this->nu_proposito->CurrentValue = $this->nu_proposito->FormValue;
		$this->nu_sistema->CurrentValue = $this->nu_sistema->FormValue;
		$this->nu_ambiente->CurrentValue = $this->nu_ambiente->FormValue;
		$this->nu_metodologia->CurrentValue = $this->nu_metodologia->FormValue;
		$this->nu_roteiro->CurrentValue = $this->nu_roteiro->FormValue;
		$this->nu_faseMedida->CurrentValue = $this->nu_faseMedida->FormValue;
		$this->nu_usuarioLogado->CurrentValue = $this->nu_usuarioLogado->FormValue;
		$this->dh_inicio->CurrentValue = $this->dh_inicio->FormValue;
		$this->dh_inicio->CurrentValue = ew_UnFormatDateTime($this->dh_inicio->CurrentValue, 7);
		$this->ic_stContagem->CurrentValue = $this->ic_stContagem->FormValue;
		$this->ar_fasesRoteiro->CurrentValue = $this->ar_fasesRoteiro->FormValue;
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
		$this->nu_solMetricas->setDbValue($rs->fields('nu_solMetricas'));
		$this->nu_tpMetrica->setDbValue($rs->fields('nu_tpMetrica'));
		$this->nu_tpContagem->setDbValue($rs->fields('nu_tpContagem'));
		$this->nu_proposito->setDbValue($rs->fields('nu_proposito'));
		$this->nu_sistema->setDbValue($rs->fields('nu_sistema'));
		$this->nu_ambiente->setDbValue($rs->fields('nu_ambiente'));
		$this->nu_metodologia->setDbValue($rs->fields('nu_metodologia'));
		$this->nu_roteiro->setDbValue($rs->fields('nu_roteiro'));
		$this->nu_faseMedida->setDbValue($rs->fields('nu_faseMedida'));
		$this->nu_usuarioLogado->setDbValue($rs->fields('nu_usuarioLogado'));
		$this->dh_inicio->setDbValue($rs->fields('dh_inicio'));
		$this->ic_stContagem->setDbValue($rs->fields('ic_stContagem'));
		$this->ar_fasesRoteiro->setDbValue($rs->fields('ar_fasesRoteiro'));
		$this->pc_varFasesRoteiro->setDbValue($rs->fields('pc_varFasesRoteiro'));
		$this->vr_pfFaturamento->setDbValue($rs->fields('vr_pfFaturamento'));
		$this->ic_bloqueio->setDbValue($rs->fields('ic_bloqueio'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_contagem->DbValue = $row['nu_contagem'];
		$this->nu_solMetricas->DbValue = $row['nu_solMetricas'];
		$this->nu_tpMetrica->DbValue = $row['nu_tpMetrica'];
		$this->nu_tpContagem->DbValue = $row['nu_tpContagem'];
		$this->nu_proposito->DbValue = $row['nu_proposito'];
		$this->nu_sistema->DbValue = $row['nu_sistema'];
		$this->nu_ambiente->DbValue = $row['nu_ambiente'];
		$this->nu_metodologia->DbValue = $row['nu_metodologia'];
		$this->nu_roteiro->DbValue = $row['nu_roteiro'];
		$this->nu_faseMedida->DbValue = $row['nu_faseMedida'];
		$this->nu_usuarioLogado->DbValue = $row['nu_usuarioLogado'];
		$this->dh_inicio->DbValue = $row['dh_inicio'];
		$this->ic_stContagem->DbValue = $row['ic_stContagem'];
		$this->ar_fasesRoteiro->DbValue = $row['ar_fasesRoteiro'];
		$this->pc_varFasesRoteiro->DbValue = $row['pc_varFasesRoteiro'];
		$this->vr_pfFaturamento->DbValue = $row['vr_pfFaturamento'];
		$this->ic_bloqueio->DbValue = $row['ic_bloqueio'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_contagem")) <> "")
			$this->nu_contagem->CurrentValue = $this->getKey("nu_contagem"); // nu_contagem
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
		// nu_contagem
		// nu_solMetricas
		// nu_tpMetrica
		// nu_tpContagem
		// nu_proposito
		// nu_sistema
		// nu_ambiente
		// nu_metodologia
		// nu_roteiro
		// nu_faseMedida
		// nu_usuarioLogado
		// dh_inicio
		// ic_stContagem
		// ar_fasesRoteiro
		// pc_varFasesRoteiro
		// vr_pfFaturamento
		// ic_bloqueio

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_contagem
			$this->nu_contagem->ViewValue = $this->nu_contagem->CurrentValue;
			$this->nu_contagem->ViewCustomAttributes = "";

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
			$sSqlWrk .= " ORDER BY [nu_solMetricas] ASC";
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

			// nu_tpMetrica
			if (strval($this->nu_tpMetrica->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpMetrica]" . ew_SearchString("=", $this->nu_tpMetrica->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpMetrica], [no_tpMetrica] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpmetrica]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo] = 'S'";
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

			// nu_tpContagem
			if (strval($this->nu_tpContagem->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpContagem]" . ew_SearchString("=", $this->nu_tpContagem->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpContagem], [no_tpContagem] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpcontagem]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpContagem, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpContagem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpContagem->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpContagem->ViewValue = $this->nu_tpContagem->CurrentValue;
				}
			} else {
				$this->nu_tpContagem->ViewValue = NULL;
			}
			$this->nu_tpContagem->ViewCustomAttributes = "";

			// nu_proposito
			if (strval($this->nu_proposito->CurrentValue) <> "") {
				$sFilterWrk = "[nu_proposito]" . ew_SearchString("=", $this->nu_proposito->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_proposito], [no_proposito] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[proposito]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_proposito, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_proposito] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_proposito->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_proposito->ViewValue = $this->nu_proposito->CurrentValue;
				}
			} else {
				$this->nu_proposito->ViewValue = NULL;
			}
			$this->nu_proposito->ViewCustomAttributes = "";

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
			$sSqlWrk .= " ORDER BY [co_alternativo] ASC";
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

			// nu_ambiente
			if (strval($this->nu_ambiente->CurrentValue) <> "") {
				$sFilterWrk = "[nu_ambiente]" . ew_SearchString("=", $this->nu_ambiente->CurrentValue, EW_DATATYPE_NUMBER);
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
			$this->Lookup_Selecting($this->nu_ambiente, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_ambiente] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_ambiente->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_ambiente->ViewValue = $this->nu_ambiente->CurrentValue;
				}
			} else {
				$this->nu_ambiente->ViewValue = NULL;
			}
			$this->nu_ambiente->ViewCustomAttributes = "";

			// nu_metodologia
			if (strval($this->nu_metodologia->CurrentValue) <> "") {
				$sFilterWrk = "[nu_metodologia]" . ew_SearchString("=", $this->nu_metodologia->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_metodologia], [no_metodologia] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[metodologia]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_metodologia, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_metodologia] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_metodologia->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_metodologia->ViewValue = $this->nu_metodologia->CurrentValue;
				}
			} else {
				$this->nu_metodologia->ViewValue = NULL;
			}
			$this->nu_metodologia->ViewCustomAttributes = "";

			// nu_roteiro
			if (strval($this->nu_roteiro->CurrentValue) <> "") {
				$sFilterWrk = "[nu_roteiro]" . ew_SearchString("=", $this->nu_roteiro->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_roteiro], [no_roteiro] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[roteiro]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_roteiro, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_roteiro] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_roteiro->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_roteiro->ViewValue = $this->nu_roteiro->CurrentValue;
				}
			} else {
				$this->nu_roteiro->ViewValue = NULL;
			}
			$this->nu_roteiro->ViewCustomAttributes = "";

			// nu_faseMedida
			if (strval($this->nu_faseMedida->CurrentValue) <> "") {
				$sFilterWrk = "[nu_faseRoteiro]" . ew_SearchString("=", $this->nu_faseMedida->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_faseRoteiro], [no_faseRoteiro] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[faseroteiro]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_faseMedida, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_faseMedida->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_faseMedida->ViewValue = $this->nu_faseMedida->CurrentValue;
				}
			} else {
				$this->nu_faseMedida->ViewValue = NULL;
			}
			$this->nu_faseMedida->ViewCustomAttributes = "";

			// nu_usuarioLogado
			if (strval($this->nu_usuarioLogado->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioLogado->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioLogado, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_usuario] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioLogado->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioLogado->ViewValue = $this->nu_usuarioLogado->CurrentValue;
				}
			} else {
				$this->nu_usuarioLogado->ViewValue = NULL;
			}
			$this->nu_usuarioLogado->ViewCustomAttributes = "";

			// dh_inicio
			$this->dh_inicio->ViewValue = $this->dh_inicio->CurrentValue;
			$this->dh_inicio->ViewValue = ew_FormatDateTime($this->dh_inicio->ViewValue, 7);
			$this->dh_inicio->ViewCustomAttributes = "";

			// ic_stContagem
			if (strval($this->ic_stContagem->CurrentValue) <> "") {
				switch ($this->ic_stContagem->CurrentValue) {
					case $this->ic_stContagem->FldTagValue(1):
						$this->ic_stContagem->ViewValue = $this->ic_stContagem->FldTagCaption(1) <> "" ? $this->ic_stContagem->FldTagCaption(1) : $this->ic_stContagem->CurrentValue;
						break;
					case $this->ic_stContagem->FldTagValue(2):
						$this->ic_stContagem->ViewValue = $this->ic_stContagem->FldTagCaption(2) <> "" ? $this->ic_stContagem->FldTagCaption(2) : $this->ic_stContagem->CurrentValue;
						break;
					case $this->ic_stContagem->FldTagValue(3):
						$this->ic_stContagem->ViewValue = $this->ic_stContagem->FldTagCaption(3) <> "" ? $this->ic_stContagem->FldTagCaption(3) : $this->ic_stContagem->CurrentValue;
						break;
					case $this->ic_stContagem->FldTagValue(4):
						$this->ic_stContagem->ViewValue = $this->ic_stContagem->FldTagCaption(4) <> "" ? $this->ic_stContagem->FldTagCaption(4) : $this->ic_stContagem->CurrentValue;
						break;
					default:
						$this->ic_stContagem->ViewValue = $this->ic_stContagem->CurrentValue;
				}
			} else {
				$this->ic_stContagem->ViewValue = NULL;
			}
			$this->ic_stContagem->ViewCustomAttributes = "";

			// ar_fasesRoteiro
			if (strval($this->ar_fasesRoteiro->CurrentValue) <> "") {
				$arwrk = explode(",", $this->ar_fasesRoteiro->CurrentValue);
				$sFilterWrk = "";
				foreach ($arwrk as $wrk) {
					if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
					$sFilterWrk .= "[nu_faseRoteiro]" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER);
				}	
			$sSqlWrk = "SELECT [nu_faseRoteiro], [no_faseRoteiro] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[faseroteiro]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->ar_fasesRoteiro, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->ar_fasesRoteiro->ViewValue = "";
					$ari = 0;
					while (!$rswrk->EOF) {
						$this->ar_fasesRoteiro->ViewValue .= $rswrk->fields('DispFld');
						$rswrk->MoveNext();
						if (!$rswrk->EOF) $this->ar_fasesRoteiro->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
						$ari++;
					}
					$rswrk->Close();
				} else {
					$this->ar_fasesRoteiro->ViewValue = $this->ar_fasesRoteiro->CurrentValue;
				}
			} else {
				$this->ar_fasesRoteiro->ViewValue = NULL;
			}
			$this->ar_fasesRoteiro->ViewCustomAttributes = "";

			// pc_varFasesRoteiro
			$this->pc_varFasesRoteiro->ViewValue = $this->pc_varFasesRoteiro->CurrentValue;
			$this->pc_varFasesRoteiro->ViewCustomAttributes = "";

			// vr_pfFaturamento
			$this->vr_pfFaturamento->ViewValue = $this->vr_pfFaturamento->CurrentValue;
			$this->vr_pfFaturamento->ViewCustomAttributes = "";

			// ic_bloqueio
			$this->ic_bloqueio->ViewValue = $this->ic_bloqueio->CurrentValue;
			$this->ic_bloqueio->ViewCustomAttributes = "";

			// nu_solMetricas
			$this->nu_solMetricas->LinkCustomAttributes = "";
			$this->nu_solMetricas->HrefValue = "";
			$this->nu_solMetricas->TooltipValue = "";

			// nu_tpMetrica
			$this->nu_tpMetrica->LinkCustomAttributes = "";
			$this->nu_tpMetrica->HrefValue = "";
			$this->nu_tpMetrica->TooltipValue = "";

			// nu_tpContagem
			$this->nu_tpContagem->LinkCustomAttributes = "";
			$this->nu_tpContagem->HrefValue = "";
			$this->nu_tpContagem->TooltipValue = "";

			// nu_proposito
			$this->nu_proposito->LinkCustomAttributes = "";
			$this->nu_proposito->HrefValue = "";
			$this->nu_proposito->TooltipValue = "";

			// nu_sistema
			$this->nu_sistema->LinkCustomAttributes = "";
			$this->nu_sistema->HrefValue = "";
			$this->nu_sistema->TooltipValue = "";

			// nu_ambiente
			$this->nu_ambiente->LinkCustomAttributes = "";
			$this->nu_ambiente->HrefValue = "";
			$this->nu_ambiente->TooltipValue = "";

			// nu_metodologia
			$this->nu_metodologia->LinkCustomAttributes = "";
			$this->nu_metodologia->HrefValue = "";
			$this->nu_metodologia->TooltipValue = "";

			// nu_roteiro
			$this->nu_roteiro->LinkCustomAttributes = "";
			$this->nu_roteiro->HrefValue = "";
			$this->nu_roteiro->TooltipValue = "";

			// nu_faseMedida
			$this->nu_faseMedida->LinkCustomAttributes = "";
			$this->nu_faseMedida->HrefValue = "";
			$this->nu_faseMedida->TooltipValue = "";

			// nu_usuarioLogado
			$this->nu_usuarioLogado->LinkCustomAttributes = "";
			$this->nu_usuarioLogado->HrefValue = "";
			$this->nu_usuarioLogado->TooltipValue = "";

			// dh_inicio
			$this->dh_inicio->LinkCustomAttributes = "";
			$this->dh_inicio->HrefValue = "";
			$this->dh_inicio->TooltipValue = "";

			// ic_stContagem
			$this->ic_stContagem->LinkCustomAttributes = "";
			$this->ic_stContagem->HrefValue = "";
			$this->ic_stContagem->TooltipValue = "";

			// ar_fasesRoteiro
			$this->ar_fasesRoteiro->LinkCustomAttributes = "";
			$this->ar_fasesRoteiro->HrefValue = "";
			$this->ar_fasesRoteiro->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

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
			$sSqlWrk .= " ORDER BY [nu_solMetricas] ASC";
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
			$sSqlWrk .= " ORDER BY [nu_solMetricas] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_solMetricas->EditValue = $arwrk;
			}

			// nu_tpMetrica
			$this->nu_tpMetrica->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_tpMetrica], [no_tpMetrica] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[tpmetrica]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo] = 'S'";
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

			// nu_tpContagem
			$this->nu_tpContagem->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_tpContagem], [no_tpContagem] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [nu_tpMetrica] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[tpcontagem]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpContagem, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpContagem] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_tpContagem->EditValue = $arwrk;

			// nu_proposito
			$this->nu_proposito->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_proposito], [no_proposito] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [nu_tpContagem] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[proposito]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_proposito, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_proposito] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_proposito->EditValue = $arwrk;

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
			$sSqlWrk .= " ORDER BY [co_alternativo] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_sistema->EditValue = $arwrk;

			// nu_ambiente
			$this->nu_ambiente->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_ambiente], [no_ambiente] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ambiente]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_ambiente, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_ambiente] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_ambiente->EditValue = $arwrk;

			// nu_metodologia
			$this->nu_metodologia->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_metodologia], [no_metodologia] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[metodologia]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_metodologia, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_metodologia] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_metodologia->EditValue = $arwrk;

			// nu_roteiro
			$this->nu_roteiro->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_roteiro], [no_roteiro] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [nu_metodologia] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[roteiro]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_roteiro, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_roteiro] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_roteiro->EditValue = $arwrk;

			// nu_faseMedida
			$this->nu_faseMedida->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_faseRoteiro], [no_faseRoteiro] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [nu_roteiro] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[faseroteiro]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_faseMedida, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_faseMedida->EditValue = $arwrk;

			// nu_usuarioLogado
			// dh_inicio

			$this->dh_inicio->EditCustomAttributes = "";
			$this->dh_inicio->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->dh_inicio->CurrentValue, 7));
			$this->dh_inicio->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dh_inicio->FldCaption()));

			// ic_stContagem
			$this->ic_stContagem->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_stContagem->FldTagValue(1), $this->ic_stContagem->FldTagCaption(1) <> "" ? $this->ic_stContagem->FldTagCaption(1) : $this->ic_stContagem->FldTagValue(1));
			$arwrk[] = array($this->ic_stContagem->FldTagValue(2), $this->ic_stContagem->FldTagCaption(2) <> "" ? $this->ic_stContagem->FldTagCaption(2) : $this->ic_stContagem->FldTagValue(2));
			$arwrk[] = array($this->ic_stContagem->FldTagValue(3), $this->ic_stContagem->FldTagCaption(3) <> "" ? $this->ic_stContagem->FldTagCaption(3) : $this->ic_stContagem->FldTagValue(3));
			$arwrk[] = array($this->ic_stContagem->FldTagValue(4), $this->ic_stContagem->FldTagCaption(4) <> "" ? $this->ic_stContagem->FldTagCaption(4) : $this->ic_stContagem->FldTagValue(4));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_stContagem->EditValue = $arwrk;

			// ar_fasesRoteiro
			$this->ar_fasesRoteiro->EditCustomAttributes = " checked=checked";
			if (trim(strval($this->ar_fasesRoteiro->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$arwrk = explode(",", $this->ar_fasesRoteiro->CurrentValue);
				$sFilterWrk = "";
				foreach ($arwrk as $wrk) {
					if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
					$sFilterWrk .= "[nu_faseRoteiro]" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER);
				}
			}
			$sSqlWrk = "SELECT [nu_faseRoteiro], [no_faseRoteiro] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [nu_roteiro] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[faseroteiro]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->ar_fasesRoteiro, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->ar_fasesRoteiro->EditValue = $arwrk;

			// Edit refer script
			// nu_solMetricas

			$this->nu_solMetricas->HrefValue = "";

			// nu_tpMetrica
			$this->nu_tpMetrica->HrefValue = "";

			// nu_tpContagem
			$this->nu_tpContagem->HrefValue = "";

			// nu_proposito
			$this->nu_proposito->HrefValue = "";

			// nu_sistema
			$this->nu_sistema->HrefValue = "";

			// nu_ambiente
			$this->nu_ambiente->HrefValue = "";

			// nu_metodologia
			$this->nu_metodologia->HrefValue = "";

			// nu_roteiro
			$this->nu_roteiro->HrefValue = "";

			// nu_faseMedida
			$this->nu_faseMedida->HrefValue = "";

			// nu_usuarioLogado
			$this->nu_usuarioLogado->HrefValue = "";

			// dh_inicio
			$this->dh_inicio->HrefValue = "";

			// ic_stContagem
			$this->ic_stContagem->HrefValue = "";

			// ar_fasesRoteiro
			$this->ar_fasesRoteiro->HrefValue = "";
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
		if (!$this->nu_tpMetrica->FldIsDetailKey && !is_null($this->nu_tpMetrica->FormValue) && $this->nu_tpMetrica->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_tpMetrica->FldCaption());
		}
		if (!$this->nu_tpContagem->FldIsDetailKey && !is_null($this->nu_tpContagem->FormValue) && $this->nu_tpContagem->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_tpContagem->FldCaption());
		}
		if (!$this->nu_proposito->FldIsDetailKey && !is_null($this->nu_proposito->FormValue) && $this->nu_proposito->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_proposito->FldCaption());
		}
		if (!$this->nu_sistema->FldIsDetailKey && !is_null($this->nu_sistema->FormValue) && $this->nu_sistema->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_sistema->FldCaption());
		}
		if (!$this->nu_ambiente->FldIsDetailKey && !is_null($this->nu_ambiente->FormValue) && $this->nu_ambiente->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_ambiente->FldCaption());
		}
		if (!$this->dh_inicio->FldIsDetailKey && !is_null($this->dh_inicio->FormValue) && $this->dh_inicio->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->dh_inicio->FldCaption());
		}
		if (!ew_CheckEuroDate($this->dh_inicio->FormValue)) {
			ew_AddMessage($gsFormError, $this->dh_inicio->FldErrMsg());
		}
		if (!$this->ic_stContagem->FldIsDetailKey && !is_null($this->ic_stContagem->FormValue) && $this->ic_stContagem->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_stContagem->FldCaption());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("contagempf_agrupador", $DetailTblVar) && $GLOBALS["contagempf_agrupador"]->DetailAdd) {
			if (!isset($GLOBALS["contagempf_agrupador_grid"])) $GLOBALS["contagempf_agrupador_grid"] = new ccontagempf_agrupador_grid(); // get detail page object
			$GLOBALS["contagempf_agrupador_grid"]->ValidateGridForm();
		}
		if (in_array("contagempf_funcao", $DetailTblVar) && $GLOBALS["contagempf_funcao"]->DetailAdd) {
			if (!isset($GLOBALS["contagempf_funcao_grid"])) $GLOBALS["contagempf_funcao_grid"] = new ccontagempf_funcao_grid(); // get detail page object
			$GLOBALS["contagempf_funcao_grid"]->ValidateGridForm();
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
		}
		$rsnew = array();

		// nu_solMetricas
		$this->nu_solMetricas->SetDbValueDef($rsnew, $this->nu_solMetricas->CurrentValue, 0, FALSE);

		// nu_tpMetrica
		$this->nu_tpMetrica->SetDbValueDef($rsnew, $this->nu_tpMetrica->CurrentValue, 0, FALSE);

		// nu_tpContagem
		$this->nu_tpContagem->SetDbValueDef($rsnew, $this->nu_tpContagem->CurrentValue, 0, FALSE);

		// nu_proposito
		$this->nu_proposito->SetDbValueDef($rsnew, $this->nu_proposito->CurrentValue, 0, FALSE);

		// nu_sistema
		$this->nu_sistema->SetDbValueDef($rsnew, $this->nu_sistema->CurrentValue, 0, FALSE);

		// nu_ambiente
		$this->nu_ambiente->SetDbValueDef($rsnew, $this->nu_ambiente->CurrentValue, 0, FALSE);

		// nu_metodologia
		$this->nu_metodologia->SetDbValueDef($rsnew, $this->nu_metodologia->CurrentValue, NULL, FALSE);

		// nu_roteiro
		$this->nu_roteiro->SetDbValueDef($rsnew, $this->nu_roteiro->CurrentValue, NULL, FALSE);

		// nu_faseMedida
		$this->nu_faseMedida->SetDbValueDef($rsnew, $this->nu_faseMedida->CurrentValue, NULL, FALSE);

		// nu_usuarioLogado
		$this->nu_usuarioLogado->SetDbValueDef($rsnew, CurrentUserID(), NULL);
		$rsnew['nu_usuarioLogado'] = &$this->nu_usuarioLogado->DbValue;

		// dh_inicio
		$this->dh_inicio->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->dh_inicio->CurrentValue, 7), NULL, FALSE);

		// ic_stContagem
		$this->ic_stContagem->SetDbValueDef($rsnew, $this->ic_stContagem->CurrentValue, NULL, FALSE);

		// ar_fasesRoteiro
		$this->ar_fasesRoteiro->SetDbValueDef($rsnew, $this->ar_fasesRoteiro->CurrentValue, NULL, FALSE);

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
			$this->nu_contagem->setDbValue($conn->Insert_ID());
			$rsnew['nu_contagem'] = $this->nu_contagem->DbValue;
		}

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("contagempf_agrupador", $DetailTblVar) && $GLOBALS["contagempf_agrupador"]->DetailAdd) {
				$GLOBALS["contagempf_agrupador"]->nu_contagem->setSessionValue($this->nu_contagem->CurrentValue); // Set master key
				if (!isset($GLOBALS["contagempf_agrupador_grid"])) $GLOBALS["contagempf_agrupador_grid"] = new ccontagempf_agrupador_grid(); // Get detail page object
				$AddRow = $GLOBALS["contagempf_agrupador_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["contagempf_agrupador"]->nu_contagem->setSessionValue(""); // Clear master key if insert failed
			}
			if (in_array("contagempf_funcao", $DetailTblVar) && $GLOBALS["contagempf_funcao"]->DetailAdd) {
				$GLOBALS["contagempf_funcao"]->nu_contagem->setSessionValue($this->nu_contagem->CurrentValue); // Set master key
				if (!isset($GLOBALS["contagempf_funcao_grid"])) $GLOBALS["contagempf_funcao_grid"] = new ccontagempf_funcao_grid(); // Get detail page object
				$AddRow = $GLOBALS["contagempf_funcao_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["contagempf_funcao"]->nu_contagem->setSessionValue(""); // Clear master key if insert failed
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
			if (in_array("contagempf_agrupador", $DetailTblVar)) {
				if (!isset($GLOBALS["contagempf_agrupador_grid"]))
					$GLOBALS["contagempf_agrupador_grid"] = new ccontagempf_agrupador_grid;
				if ($GLOBALS["contagempf_agrupador_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["contagempf_agrupador_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["contagempf_agrupador_grid"]->CurrentMode = "add";
					$GLOBALS["contagempf_agrupador_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["contagempf_agrupador_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["contagempf_agrupador_grid"]->setStartRecordNumber(1);
					$GLOBALS["contagempf_agrupador_grid"]->nu_contagem->FldIsDetailKey = TRUE;
					$GLOBALS["contagempf_agrupador_grid"]->nu_contagem->CurrentValue = $this->nu_contagem->CurrentValue;
					$GLOBALS["contagempf_agrupador_grid"]->nu_contagem->setSessionValue($GLOBALS["contagempf_agrupador_grid"]->nu_contagem->CurrentValue);
				}
			}
			if (in_array("contagempf_funcao", $DetailTblVar)) {
				if (!isset($GLOBALS["contagempf_funcao_grid"]))
					$GLOBALS["contagempf_funcao_grid"] = new ccontagempf_funcao_grid;
				if ($GLOBALS["contagempf_funcao_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["contagempf_funcao_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["contagempf_funcao_grid"]->CurrentMode = "add";
					$GLOBALS["contagempf_funcao_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["contagempf_funcao_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["contagempf_funcao_grid"]->setStartRecordNumber(1);
					$GLOBALS["contagempf_funcao_grid"]->nu_contagem->FldIsDetailKey = TRUE;
					$GLOBALS["contagempf_funcao_grid"]->nu_contagem->CurrentValue = $this->nu_contagem->CurrentValue;
					$GLOBALS["contagempf_funcao_grid"]->nu_contagem->setSessionValue($GLOBALS["contagempf_funcao_grid"]->nu_contagem->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "contagempflist.php", $this->TableVar);
		$PageCaption = ($this->CurrentAction == "C") ? $Language->Phrase("Copy") : $Language->Phrase("Add");
		$Breadcrumb->Add("add", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'contagempf';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'contagempf';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_contagem'];

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
if (!isset($contagempf_add)) $contagempf_add = new ccontagempf_add();

// Page init
$contagempf_add->Page_Init();

// Page main
$contagempf_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$contagempf_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var contagempf_add = new ew_Page("contagempf_add");
contagempf_add.PageID = "add"; // Page ID
var EW_PAGE_ID = contagempf_add.PageID; // For backward compatibility

// Form object
var fcontagempfadd = new ew_Form("fcontagempfadd");

// Validate form
fcontagempfadd.Validate = function() {
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
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($contagempf->nu_solMetricas->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_tpMetrica");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($contagempf->nu_tpMetrica->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_tpContagem");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($contagempf->nu_tpContagem->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_proposito");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($contagempf->nu_proposito->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_sistema");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($contagempf->nu_sistema->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_ambiente");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($contagempf->nu_ambiente->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dh_inicio");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($contagempf->dh_inicio->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dh_inicio");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($contagempf->dh_inicio->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ic_stContagem");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($contagempf->ic_stContagem->FldCaption()) ?>");

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
fcontagempfadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcontagempfadd.ValidateRequired = true;
<?php } else { ?>
fcontagempfadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcontagempfadd.Lists["x_nu_solMetricas"] = {"LinkField":"x_nu_solMetricas","Ajax":null,"AutoFill":false,"DisplayFields":["x_nu_solMetricas","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempfadd.Lists["x_nu_tpMetrica"] = {"LinkField":"x_nu_tpMetrica","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpMetrica","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempfadd.Lists["x_nu_tpContagem"] = {"LinkField":"x_nu_tpContagem","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpContagem","","",""],"ParentFields":["x_nu_tpMetrica"],"FilterFields":["x_nu_tpMetrica"],"Options":[]};
fcontagempfadd.Lists["x_nu_proposito"] = {"LinkField":"x_nu_proposito","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_proposito","","",""],"ParentFields":["x_nu_tpContagem"],"FilterFields":["x_nu_tpContagem"],"Options":[]};
fcontagempfadd.Lists["x_nu_sistema"] = {"LinkField":"x_nu_sistema","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_alternativo","x_no_sistema","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempfadd.Lists["x_nu_ambiente"] = {"LinkField":"x_nu_ambiente","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_ambiente","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempfadd.Lists["x_nu_metodologia"] = {"LinkField":"x_nu_metodologia","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_metodologia","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempfadd.Lists["x_nu_roteiro"] = {"LinkField":"x_nu_roteiro","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_roteiro","","",""],"ParentFields":["x_nu_metodologia"],"FilterFields":["x_nu_metodologia"],"Options":[]};
fcontagempfadd.Lists["x_nu_faseMedida"] = {"LinkField":"x_nu_faseRoteiro","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_faseRoteiro","","",""],"ParentFields":["x_nu_roteiro"],"FilterFields":["x_nu_roteiro"],"Options":[]};
fcontagempfadd.Lists["x_nu_usuarioLogado"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempfadd.Lists["x_ar_fasesRoteiro[]"] = {"LinkField":"x_nu_faseRoteiro","Ajax":true,"AutoFill":false,"DisplayFields":["x_no_faseRoteiro","","",""],"ParentFields":["x_nu_roteiro"],"FilterFields":["x_nu_roteiro"],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $contagempf_add->ShowPageHeader(); ?>
<?php
$contagempf_add->ShowMessage();
?>
<form name="fcontagempfadd" id="fcontagempfadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="contagempf">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_contagempfadd" class="table table-bordered table-striped">
<?php if ($contagempf->nu_solMetricas->Visible) { // nu_solMetricas ?>
	<tr id="r_nu_solMetricas">
		<td><span id="elh_contagempf_nu_solMetricas"><?php echo $contagempf->nu_solMetricas->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $contagempf->nu_solMetricas->CellAttributes() ?>>
<?php if ($contagempf->nu_solMetricas->getSessionValue() <> "") { ?>
<span<?php echo $contagempf->nu_solMetricas->ViewAttributes() ?>>
<?php echo $contagempf->nu_solMetricas->ViewValue ?></span>
<input type="hidden" id="x_nu_solMetricas" name="x_nu_solMetricas" value="<?php echo ew_HtmlEncode($contagempf->nu_solMetricas->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_nu_solMetricas" id="x_nu_solMetricas" name="x_nu_solMetricas"<?php echo $contagempf->nu_solMetricas->EditAttributes() ?>>
<?php
if (is_array($contagempf->nu_solMetricas->EditValue)) {
	$arwrk = $contagempf->nu_solMetricas->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->nu_solMetricas->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fcontagempfadd.Lists["x_nu_solMetricas"].Options = <?php echo (is_array($contagempf->nu_solMetricas->EditValue)) ? ew_ArrayToJson($contagempf->nu_solMetricas->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<?php echo $contagempf->nu_solMetricas->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contagempf->nu_tpMetrica->Visible) { // nu_tpMetrica ?>
	<tr id="r_nu_tpMetrica">
		<td><span id="elh_contagempf_nu_tpMetrica"><?php echo $contagempf->nu_tpMetrica->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $contagempf->nu_tpMetrica->CellAttributes() ?>>
<span id="el_contagempf_nu_tpMetrica" class="control-group">
<?php $contagempf->nu_tpMetrica->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_tpContagem']); " . @$contagempf->nu_tpMetrica->EditAttrs["onchange"]; ?>
<select data-field="x_nu_tpMetrica" id="x_nu_tpMetrica" name="x_nu_tpMetrica"<?php echo $contagempf->nu_tpMetrica->EditAttributes() ?>>
<?php
if (is_array($contagempf->nu_tpMetrica->EditValue)) {
	$arwrk = $contagempf->nu_tpMetrica->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->nu_tpMetrica->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fcontagempfadd.Lists["x_nu_tpMetrica"].Options = <?php echo (is_array($contagempf->nu_tpMetrica->EditValue)) ? ew_ArrayToJson($contagempf->nu_tpMetrica->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $contagempf->nu_tpMetrica->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contagempf->nu_tpContagem->Visible) { // nu_tpContagem ?>
	<tr id="r_nu_tpContagem">
		<td><span id="elh_contagempf_nu_tpContagem"><?php echo $contagempf->nu_tpContagem->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $contagempf->nu_tpContagem->CellAttributes() ?>>
<span id="el_contagempf_nu_tpContagem" class="control-group">
<?php $contagempf->nu_tpContagem->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_proposito']); " . @$contagempf->nu_tpContagem->EditAttrs["onchange"]; ?>
<select data-field="x_nu_tpContagem" id="x_nu_tpContagem" name="x_nu_tpContagem"<?php echo $contagempf->nu_tpContagem->EditAttributes() ?>>
<?php
if (is_array($contagempf->nu_tpContagem->EditValue)) {
	$arwrk = $contagempf->nu_tpContagem->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->nu_tpContagem->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fcontagempfadd.Lists["x_nu_tpContagem"].Options = <?php echo (is_array($contagempf->nu_tpContagem->EditValue)) ? ew_ArrayToJson($contagempf->nu_tpContagem->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $contagempf->nu_tpContagem->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contagempf->nu_proposito->Visible) { // nu_proposito ?>
	<tr id="r_nu_proposito">
		<td><span id="elh_contagempf_nu_proposito"><?php echo $contagempf->nu_proposito->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $contagempf->nu_proposito->CellAttributes() ?>>
<span id="el_contagempf_nu_proposito" class="control-group">
<select data-field="x_nu_proposito" id="x_nu_proposito" name="x_nu_proposito"<?php echo $contagempf->nu_proposito->EditAttributes() ?>>
<?php
if (is_array($contagempf->nu_proposito->EditValue)) {
	$arwrk = $contagempf->nu_proposito->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->nu_proposito->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if (AllowAdd(CurrentProjectID() . "proposito")) { ?>
&nbsp;<a id="aol_x_nu_proposito" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_nu_proposito',url:'propositoaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $contagempf->nu_proposito->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
fcontagempfadd.Lists["x_nu_proposito"].Options = <?php echo (is_array($contagempf->nu_proposito->EditValue)) ? ew_ArrayToJson($contagempf->nu_proposito->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $contagempf->nu_proposito->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contagempf->nu_sistema->Visible) { // nu_sistema ?>
	<tr id="r_nu_sistema">
		<td><span id="elh_contagempf_nu_sistema"><?php echo $contagempf->nu_sistema->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $contagempf->nu_sistema->CellAttributes() ?>>
<span id="el_contagempf_nu_sistema" class="control-group">
<select data-field="x_nu_sistema" id="x_nu_sistema" name="x_nu_sistema"<?php echo $contagempf->nu_sistema->EditAttributes() ?>>
<?php
if (is_array($contagempf->nu_sistema->EditValue)) {
	$arwrk = $contagempf->nu_sistema->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->nu_sistema->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$contagempf->nu_sistema) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "sistema")) { ?>
&nbsp;<a id="aol_x_nu_sistema" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_nu_sistema',url:'sistemaaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $contagempf->nu_sistema->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
fcontagempfadd.Lists["x_nu_sistema"].Options = <?php echo (is_array($contagempf->nu_sistema->EditValue)) ? ew_ArrayToJson($contagempf->nu_sistema->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $contagempf->nu_sistema->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contagempf->nu_ambiente->Visible) { // nu_ambiente ?>
	<tr id="r_nu_ambiente">
		<td><span id="elh_contagempf_nu_ambiente"><?php echo $contagempf->nu_ambiente->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $contagempf->nu_ambiente->CellAttributes() ?>>
<span id="el_contagempf_nu_ambiente" class="control-group">
<select data-field="x_nu_ambiente" id="x_nu_ambiente" name="x_nu_ambiente"<?php echo $contagempf->nu_ambiente->EditAttributes() ?>>
<?php
if (is_array($contagempf->nu_ambiente->EditValue)) {
	$arwrk = $contagempf->nu_ambiente->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->nu_ambiente->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if (AllowAdd(CurrentProjectID() . "ambiente")) { ?>
&nbsp;<a id="aol_x_nu_ambiente" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_nu_ambiente',url:'ambienteaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $contagempf->nu_ambiente->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
fcontagempfadd.Lists["x_nu_ambiente"].Options = <?php echo (is_array($contagempf->nu_ambiente->EditValue)) ? ew_ArrayToJson($contagempf->nu_ambiente->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $contagempf->nu_ambiente->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contagempf->nu_metodologia->Visible) { // nu_metodologia ?>
	<tr id="r_nu_metodologia">
		<td><span id="elh_contagempf_nu_metodologia"><?php echo $contagempf->nu_metodologia->FldCaption() ?></span></td>
		<td<?php echo $contagempf->nu_metodologia->CellAttributes() ?>>
<span id="el_contagempf_nu_metodologia" class="control-group">
<?php $contagempf->nu_metodologia->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_roteiro']); " . @$contagempf->nu_metodologia->EditAttrs["onchange"]; ?>
<select data-field="x_nu_metodologia" id="x_nu_metodologia" name="x_nu_metodologia"<?php echo $contagempf->nu_metodologia->EditAttributes() ?>>
<?php
if (is_array($contagempf->nu_metodologia->EditValue)) {
	$arwrk = $contagempf->nu_metodologia->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->nu_metodologia->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fcontagempfadd.Lists["x_nu_metodologia"].Options = <?php echo (is_array($contagempf->nu_metodologia->EditValue)) ? ew_ArrayToJson($contagempf->nu_metodologia->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $contagempf->nu_metodologia->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contagempf->nu_roteiro->Visible) { // nu_roteiro ?>
	<tr id="r_nu_roteiro">
		<td><span id="elh_contagempf_nu_roteiro"><?php echo $contagempf->nu_roteiro->FldCaption() ?></span></td>
		<td<?php echo $contagempf->nu_roteiro->CellAttributes() ?>>
<span id="el_contagempf_nu_roteiro" class="control-group">
<?php $contagempf->nu_roteiro->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_faseMedida','x_ar_fasesRoteiro[]']); " . @$contagempf->nu_roteiro->EditAttrs["onchange"]; ?>
<select data-field="x_nu_roteiro" id="x_nu_roteiro" name="x_nu_roteiro"<?php echo $contagempf->nu_roteiro->EditAttributes() ?>>
<?php
if (is_array($contagempf->nu_roteiro->EditValue)) {
	$arwrk = $contagempf->nu_roteiro->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->nu_roteiro->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fcontagempfadd.Lists["x_nu_roteiro"].Options = <?php echo (is_array($contagempf->nu_roteiro->EditValue)) ? ew_ArrayToJson($contagempf->nu_roteiro->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $contagempf->nu_roteiro->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contagempf->nu_faseMedida->Visible) { // nu_faseMedida ?>
	<tr id="r_nu_faseMedida">
		<td><span id="elh_contagempf_nu_faseMedida"><?php echo $contagempf->nu_faseMedida->FldCaption() ?></span></td>
		<td<?php echo $contagempf->nu_faseMedida->CellAttributes() ?>>
<span id="el_contagempf_nu_faseMedida" class="control-group">
<select data-field="x_nu_faseMedida" id="x_nu_faseMedida" name="x_nu_faseMedida"<?php echo $contagempf->nu_faseMedida->EditAttributes() ?>>
<?php
if (is_array($contagempf->nu_faseMedida->EditValue)) {
	$arwrk = $contagempf->nu_faseMedida->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->nu_faseMedida->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fcontagempfadd.Lists["x_nu_faseMedida"].Options = <?php echo (is_array($contagempf->nu_faseMedida->EditValue)) ? ew_ArrayToJson($contagempf->nu_faseMedida->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $contagempf->nu_faseMedida->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contagempf->dh_inicio->Visible) { // dh_inicio ?>
	<tr id="r_dh_inicio">
		<td><span id="elh_contagempf_dh_inicio"><?php echo $contagempf->dh_inicio->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $contagempf->dh_inicio->CellAttributes() ?>>
<span id="el_contagempf_dh_inicio" class="control-group">
<input type="text" data-field="x_dh_inicio" name="x_dh_inicio" id="x_dh_inicio" placeholder="<?php echo $contagempf->dh_inicio->PlaceHolder ?>" value="<?php echo $contagempf->dh_inicio->EditValue ?>"<?php echo $contagempf->dh_inicio->EditAttributes() ?>>
<?php if (!$contagempf->dh_inicio->ReadOnly && !$contagempf->dh_inicio->Disabled && @$contagempf->dh_inicio->EditAttrs["readonly"] == "" && @$contagempf->dh_inicio->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_dh_inicio" name="cal_x_dh_inicio" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x_dh_inicio" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fcontagempfadd", "x_dh_inicio", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $contagempf->dh_inicio->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contagempf->ic_stContagem->Visible) { // ic_stContagem ?>
	<tr id="r_ic_stContagem">
		<td><span id="elh_contagempf_ic_stContagem"><?php echo $contagempf->ic_stContagem->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $contagempf->ic_stContagem->CellAttributes() ?>>
<span id="el_contagempf_ic_stContagem" class="control-group">
<select data-field="x_ic_stContagem" id="x_ic_stContagem" name="x_ic_stContagem"<?php echo $contagempf->ic_stContagem->EditAttributes() ?>>
<?php
if (is_array($contagempf->ic_stContagem->EditValue)) {
	$arwrk = $contagempf->ic_stContagem->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->ic_stContagem->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $contagempf->ic_stContagem->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contagempf->ar_fasesRoteiro->Visible) { // ar_fasesRoteiro ?>
	<tr id="r_ar_fasesRoteiro">
		<td><span id="elh_contagempf_ar_fasesRoteiro"><?php echo $contagempf->ar_fasesRoteiro->FldCaption() ?></span></td>
		<td<?php echo $contagempf->ar_fasesRoteiro->CellAttributes() ?>>
<span id="el_contagempf_ar_fasesRoteiro" class="control-group">
<div id="tp_x_ar_fasesRoteiro" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME; ?>"><input type="checkbox" name="x_ar_fasesRoteiro[]" id="x_ar_fasesRoteiro[]" value="{value}"<?php echo $contagempf->ar_fasesRoteiro->EditAttributes() ?>></div>
<div id="dsl_x_ar_fasesRoteiro" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $contagempf->ar_fasesRoteiro->EditValue;
if (is_array($arwrk)) {
	$armultiwrk= explode(",", strval($contagempf->ar_fasesRoteiro->CurrentValue));
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
<label class="checkbox"><input type="checkbox" data-field="x_ar_fasesRoteiro" name="x_ar_fasesRoteiro[]" id="x_ar_fasesRoteiro_<?php echo $rowcntwrk ?>[]" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $contagempf->ar_fasesRoteiro->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
<?php
$sSqlWrk = "SELECT [nu_faseRoteiro], [no_faseRoteiro] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[faseroteiro]";
$sWhereWrk = "{filter}";
$lookuptblfilter = "[ic_ativo]='S'";
if (strval($lookuptblfilter) <> "") {
	ew_AddFilter($sWhereWrk, $lookuptblfilter);
}

// Call Lookup selecting
$contagempf->Lookup_Selecting($contagempf->ar_fasesRoteiro, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
?>
<input type="hidden" name="s_x_ar_fasesRoteiro" id="s_x_ar_fasesRoteiro" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&f0=<?php echo ew_Encrypt("[nu_faseRoteiro] = {filter_value}"); ?>&t0=3&f1=<?php echo ew_Encrypt("[nu_roteiro] IN ({filter_value})"); ?>&t1=3">
</span>
<?php echo $contagempf->ar_fasesRoteiro->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<?php
	if (in_array("contagempf_agrupador", explode(",", $contagempf->getCurrentDetailTable())) && $contagempf_agrupador->DetailAdd) {
?>
<?php include_once "contagempf_agrupadorgrid.php" ?>
<?php } ?>
<?php
	if (in_array("contagempf_funcao", explode(",", $contagempf->getCurrentDetailTable())) && $contagempf_funcao->DetailAdd) {
?>
<?php include_once "contagempf_funcaogrid.php" ?>
<?php } ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fcontagempfadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$contagempf_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$contagempf_add->Page_Terminate();
?>
