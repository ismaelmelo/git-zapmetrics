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

$contagempf_edit = NULL; // Initialize page object first

class ccontagempf_edit extends ccontagempf {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'contagempf';

	// Page object name
	var $PageObjName = 'contagempf_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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
		if (!$Security->CanEdit()) {
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
		$this->nu_contagem->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		if (@$_GET["nu_contagem"] <> "") {
			$this->nu_contagem->setQueryStringValue($_GET["nu_contagem"]);
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
		if ($this->nu_contagem->CurrentValue == "")
			$this->Page_Terminate("contagempflist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("contagempflist.php"); // No matching record, return to list
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
					if (ew_GetPageName($sReturnUrl) == "contagempfview.php")
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
		if (!$this->nu_contagem->FldIsDetailKey)
			$this->nu_contagem->setFormValue($objForm->GetValue("x_nu_contagem"));
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
		if (!$this->pc_varFasesRoteiro->FldIsDetailKey) {
			$this->pc_varFasesRoteiro->setFormValue($objForm->GetValue("x_pc_varFasesRoteiro"));
		}
		if (!$this->vr_pfFaturamento->FldIsDetailKey) {
			$this->vr_pfFaturamento->setFormValue($objForm->GetValue("x_vr_pfFaturamento"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->nu_contagem->CurrentValue = $this->nu_contagem->FormValue;
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
		$this->pc_varFasesRoteiro->CurrentValue = $this->pc_varFasesRoteiro->FormValue;
		$this->vr_pfFaturamento->CurrentValue = $this->vr_pfFaturamento->FormValue;
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

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->vr_pfFaturamento->FormValue == $this->vr_pfFaturamento->CurrentValue && is_numeric(ew_StrToFloat($this->vr_pfFaturamento->CurrentValue)))
			$this->vr_pfFaturamento->CurrentValue = ew_StrToFloat($this->vr_pfFaturamento->CurrentValue);

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

			// nu_contagem
			$this->nu_contagem->LinkCustomAttributes = "";
			$this->nu_contagem->HrefValue = "";
			$this->nu_contagem->TooltipValue = "";

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

			// pc_varFasesRoteiro
			$this->pc_varFasesRoteiro->LinkCustomAttributes = "";
			$this->pc_varFasesRoteiro->HrefValue = "";
			$this->pc_varFasesRoteiro->TooltipValue = "";

			// vr_pfFaturamento
			$this->vr_pfFaturamento->LinkCustomAttributes = "";
			$this->vr_pfFaturamento->HrefValue = "";
			$this->vr_pfFaturamento->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// nu_contagem
			$this->nu_contagem->EditCustomAttributes = "";
			$this->nu_contagem->EditValue = $this->nu_contagem->CurrentValue;
			$this->nu_contagem->ViewCustomAttributes = "";

			// nu_solMetricas
			$this->nu_solMetricas->EditCustomAttributes = "";
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
					$this->nu_solMetricas->EditValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_solMetricas->EditValue = $this->nu_solMetricas->CurrentValue;
				}
			} else {
				$this->nu_solMetricas->EditValue = NULL;
			}
			$this->nu_solMetricas->ViewCustomAttributes = "";

			// nu_tpMetrica
			$this->nu_tpMetrica->EditCustomAttributes = "";
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
					$this->nu_tpMetrica->EditValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpMetrica->EditValue = $this->nu_tpMetrica->CurrentValue;
				}
			} else {
				$this->nu_tpMetrica->EditValue = NULL;
			}
			$this->nu_tpMetrica->ViewCustomAttributes = "";

			// nu_tpContagem
			$this->nu_tpContagem->EditCustomAttributes = "";
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
					$this->nu_tpContagem->EditValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpContagem->EditValue = $this->nu_tpContagem->CurrentValue;
				}
			} else {
				$this->nu_tpContagem->EditValue = NULL;
			}
			$this->nu_tpContagem->ViewCustomAttributes = "";

			// nu_proposito
			$this->nu_proposito->EditCustomAttributes = "";
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
					$this->nu_proposito->EditValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_proposito->EditValue = $this->nu_proposito->CurrentValue;
				}
			} else {
				$this->nu_proposito->EditValue = NULL;
			}
			$this->nu_proposito->ViewCustomAttributes = "";

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
					$this->nu_metodologia->EditValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_metodologia->EditValue = $this->nu_metodologia->CurrentValue;
				}
			} else {
				$this->nu_metodologia->EditValue = NULL;
			}
			$this->nu_metodologia->ViewCustomAttributes = "";

			// nu_roteiro
			$this->nu_roteiro->EditCustomAttributes = "";
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
					$this->nu_roteiro->EditValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_roteiro->EditValue = $this->nu_roteiro->CurrentValue;
				}
			} else {
				$this->nu_roteiro->EditValue = NULL;
			}
			$this->nu_roteiro->ViewCustomAttributes = "";

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
					$this->ar_fasesRoteiro->EditValue = "";
					$ari = 0;
					while (!$rswrk->EOF) {
						$this->ar_fasesRoteiro->EditValue .= $rswrk->fields('DispFld');
						$rswrk->MoveNext();
						if (!$rswrk->EOF) $this->ar_fasesRoteiro->EditValue .= ew_ViewOptionSeparator($ari); // Separate Options
						$ari++;
					}
					$rswrk->Close();
				} else {
					$this->ar_fasesRoteiro->EditValue = $this->ar_fasesRoteiro->CurrentValue;
				}
			} else {
				$this->ar_fasesRoteiro->EditValue = NULL;
			}
			$this->ar_fasesRoteiro->ViewCustomAttributes = "";

			// pc_varFasesRoteiro
			$this->pc_varFasesRoteiro->EditCustomAttributes = "";

			// vr_pfFaturamento
			$this->vr_pfFaturamento->EditCustomAttributes = "";
			$this->vr_pfFaturamento->EditValue = $this->vr_pfFaturamento->CurrentValue;
			$this->vr_pfFaturamento->ViewCustomAttributes = "";

			// Edit refer script
			// nu_contagem

			$this->nu_contagem->HrefValue = "";

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

			// pc_varFasesRoteiro
			$this->pc_varFasesRoteiro->HrefValue = "";

			// vr_pfFaturamento
			$this->vr_pfFaturamento->HrefValue = "";
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
		if (in_array("contagempf_agrupador", $DetailTblVar) && $GLOBALS["contagempf_agrupador"]->DetailEdit) {
			if (!isset($GLOBALS["contagempf_agrupador_grid"])) $GLOBALS["contagempf_agrupador_grid"] = new ccontagempf_agrupador_grid(); // get detail page object
			$GLOBALS["contagempf_agrupador_grid"]->ValidateGridForm();
		}
		if (in_array("contagempf_funcao", $DetailTblVar) && $GLOBALS["contagempf_funcao"]->DetailEdit) {
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

			// nu_sistema
			$this->nu_sistema->SetDbValueDef($rsnew, $this->nu_sistema->CurrentValue, 0, $this->nu_sistema->ReadOnly);

			// nu_ambiente
			$this->nu_ambiente->SetDbValueDef($rsnew, $this->nu_ambiente->CurrentValue, 0, $this->nu_ambiente->ReadOnly);

			// nu_faseMedida
			$this->nu_faseMedida->SetDbValueDef($rsnew, $this->nu_faseMedida->CurrentValue, NULL, $this->nu_faseMedida->ReadOnly);

			// nu_usuarioLogado
			$this->nu_usuarioLogado->SetDbValueDef($rsnew, CurrentUserID(), NULL);
			$rsnew['nu_usuarioLogado'] = &$this->nu_usuarioLogado->DbValue;

			// dh_inicio
			$this->dh_inicio->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->dh_inicio->CurrentValue, 7), NULL, $this->dh_inicio->ReadOnly);

			// ic_stContagem
			$this->ic_stContagem->SetDbValueDef($rsnew, $this->ic_stContagem->CurrentValue, NULL, $this->ic_stContagem->ReadOnly);

			// pc_varFasesRoteiro
			$this->pc_varFasesRoteiro->SetDbValueDef($rsnew, $this->pc_varFasesRoteiro->CurrentValue, NULL, $this->pc_varFasesRoteiro->ReadOnly);

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
					if (in_array("contagempf_agrupador", $DetailTblVar) && $GLOBALS["contagempf_agrupador"]->DetailEdit) {
						if (!isset($GLOBALS["contagempf_agrupador_grid"])) $GLOBALS["contagempf_agrupador_grid"] = new ccontagempf_agrupador_grid(); // Get detail page object
						$EditRow = $GLOBALS["contagempf_agrupador_grid"]->GridUpdate();
					}
					if (in_array("contagempf_funcao", $DetailTblVar) && $GLOBALS["contagempf_funcao"]->DetailEdit) {
						if (!isset($GLOBALS["contagempf_funcao_grid"])) $GLOBALS["contagempf_funcao_grid"] = new ccontagempf_funcao_grid(); // Get detail page object
						$EditRow = $GLOBALS["contagempf_funcao_grid"]->GridUpdate();
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
				if ($GLOBALS["contagempf_agrupador_grid"]->DetailEdit) {
					$GLOBALS["contagempf_agrupador_grid"]->CurrentMode = "edit";
					$GLOBALS["contagempf_agrupador_grid"]->CurrentAction = "gridedit";

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
				if ($GLOBALS["contagempf_funcao_grid"]->DetailEdit) {
					$GLOBALS["contagempf_funcao_grid"]->CurrentMode = "edit";
					$GLOBALS["contagempf_funcao_grid"]->CurrentAction = "gridedit";

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
		$PageCaption = $Language->Phrase("edit");
		$Breadcrumb->Add("edit", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'contagempf';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		if (!$this->AuditTrailOnEdit) return;
		$table = 'contagempf';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['nu_contagem'];

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
if (!isset($contagempf_edit)) $contagempf_edit = new ccontagempf_edit();

// Page init
$contagempf_edit->Page_Init();

// Page main
$contagempf_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$contagempf_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var contagempf_edit = new ew_Page("contagempf_edit");
contagempf_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = contagempf_edit.PageID; // For backward compatibility

// Form object
var fcontagempfedit = new ew_Form("fcontagempfedit");

// Validate form
fcontagempfedit.Validate = function() {
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
fcontagempfedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcontagempfedit.ValidateRequired = true;
<?php } else { ?>
fcontagempfedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcontagempfedit.Lists["x_nu_solMetricas"] = {"LinkField":"x_nu_solMetricas","Ajax":null,"AutoFill":false,"DisplayFields":["x_nu_solMetricas","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempfedit.Lists["x_nu_tpMetrica"] = {"LinkField":"x_nu_tpMetrica","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpMetrica","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempfedit.Lists["x_nu_tpContagem"] = {"LinkField":"x_nu_tpContagem","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpContagem","","",""],"ParentFields":["x_nu_tpMetrica"],"FilterFields":["x_nu_tpMetrica"],"Options":[]};
fcontagempfedit.Lists["x_nu_proposito"] = {"LinkField":"x_nu_proposito","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_proposito","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempfedit.Lists["x_nu_sistema"] = {"LinkField":"x_nu_sistema","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_alternativo","x_no_sistema","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempfedit.Lists["x_nu_ambiente"] = {"LinkField":"x_nu_ambiente","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_ambiente","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempfedit.Lists["x_nu_metodologia"] = {"LinkField":"x_nu_metodologia","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_metodologia","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempfedit.Lists["x_nu_roteiro"] = {"LinkField":"x_nu_roteiro","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_roteiro","","",""],"ParentFields":["x_nu_metodologia"],"FilterFields":["x_nu_metodologia"],"Options":[]};
fcontagempfedit.Lists["x_nu_faseMedida"] = {"LinkField":"x_nu_faseRoteiro","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_faseRoteiro","","",""],"ParentFields":["x_nu_roteiro"],"FilterFields":["x_nu_roteiro"],"Options":[]};
fcontagempfedit.Lists["x_nu_usuarioLogado"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempfedit.Lists["x_ar_fasesRoteiro[]"] = {"LinkField":"x_nu_faseRoteiro","Ajax":true,"AutoFill":false,"DisplayFields":["x_no_faseRoteiro","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $contagempf_edit->ShowPageHeader(); ?>
<?php
$contagempf_edit->ShowMessage();
?>
<form name="fcontagempfedit" id="fcontagempfedit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="contagempf">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_contagempfedit" class="table table-bordered table-striped">
<?php if ($contagempf->nu_contagem->Visible) { // nu_contagem ?>
	<tr id="r_nu_contagem">
		<td><span id="elh_contagempf_nu_contagem"><?php echo $contagempf->nu_contagem->FldCaption() ?></span></td>
		<td<?php echo $contagempf->nu_contagem->CellAttributes() ?>>
<span id="el_contagempf_nu_contagem" class="control-group">
<span<?php echo $contagempf->nu_contagem->ViewAttributes() ?>>
<?php echo $contagempf->nu_contagem->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_contagem" name="x_nu_contagem" id="x_nu_contagem" value="<?php echo ew_HtmlEncode($contagempf->nu_contagem->CurrentValue) ?>">
<?php echo $contagempf->nu_contagem->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contagempf->nu_solMetricas->Visible) { // nu_solMetricas ?>
	<tr id="r_nu_solMetricas">
		<td><span id="elh_contagempf_nu_solMetricas"><?php echo $contagempf->nu_solMetricas->FldCaption() ?></span></td>
		<td<?php echo $contagempf->nu_solMetricas->CellAttributes() ?>>
<span id="el_contagempf_nu_solMetricas" class="control-group">
<span<?php echo $contagempf->nu_solMetricas->ViewAttributes() ?>>
<?php echo $contagempf->nu_solMetricas->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_solMetricas" name="x_nu_solMetricas" id="x_nu_solMetricas" value="<?php echo ew_HtmlEncode($contagempf->nu_solMetricas->CurrentValue) ?>">
<?php echo $contagempf->nu_solMetricas->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contagempf->nu_tpMetrica->Visible) { // nu_tpMetrica ?>
	<tr id="r_nu_tpMetrica">
		<td><span id="elh_contagempf_nu_tpMetrica"><?php echo $contagempf->nu_tpMetrica->FldCaption() ?></span></td>
		<td<?php echo $contagempf->nu_tpMetrica->CellAttributes() ?>>
<span id="el_contagempf_nu_tpMetrica" class="control-group">
<span<?php echo $contagempf->nu_tpMetrica->ViewAttributes() ?>>
<?php echo $contagempf->nu_tpMetrica->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_tpMetrica" name="x_nu_tpMetrica" id="x_nu_tpMetrica" value="<?php echo ew_HtmlEncode($contagempf->nu_tpMetrica->CurrentValue) ?>">
<?php echo $contagempf->nu_tpMetrica->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contagempf->nu_tpContagem->Visible) { // nu_tpContagem ?>
	<tr id="r_nu_tpContagem">
		<td><span id="elh_contagempf_nu_tpContagem"><?php echo $contagempf->nu_tpContagem->FldCaption() ?></span></td>
		<td<?php echo $contagempf->nu_tpContagem->CellAttributes() ?>>
<span id="el_contagempf_nu_tpContagem" class="control-group">
<span<?php echo $contagempf->nu_tpContagem->ViewAttributes() ?>>
<?php echo $contagempf->nu_tpContagem->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_tpContagem" name="x_nu_tpContagem" id="x_nu_tpContagem" value="<?php echo ew_HtmlEncode($contagempf->nu_tpContagem->CurrentValue) ?>">
<?php echo $contagempf->nu_tpContagem->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contagempf->nu_proposito->Visible) { // nu_proposito ?>
	<tr id="r_nu_proposito">
		<td><span id="elh_contagempf_nu_proposito"><?php echo $contagempf->nu_proposito->FldCaption() ?></span></td>
		<td<?php echo $contagempf->nu_proposito->CellAttributes() ?>>
<span id="el_contagempf_nu_proposito" class="control-group">
<span<?php echo $contagempf->nu_proposito->ViewAttributes() ?>>
<?php echo $contagempf->nu_proposito->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_proposito" name="x_nu_proposito" id="x_nu_proposito" value="<?php echo ew_HtmlEncode($contagempf->nu_proposito->CurrentValue) ?>">
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
fcontagempfedit.Lists["x_nu_sistema"].Options = <?php echo (is_array($contagempf->nu_sistema->EditValue)) ? ew_ArrayToJson($contagempf->nu_sistema->EditValue, 1) : "[]" ?>;
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
fcontagempfedit.Lists["x_nu_ambiente"].Options = <?php echo (is_array($contagempf->nu_ambiente->EditValue)) ? ew_ArrayToJson($contagempf->nu_ambiente->EditValue, 1) : "[]" ?>;
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
<span<?php echo $contagempf->nu_metodologia->ViewAttributes() ?>>
<?php echo $contagempf->nu_metodologia->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_metodologia" name="x_nu_metodologia" id="x_nu_metodologia" value="<?php echo ew_HtmlEncode($contagempf->nu_metodologia->CurrentValue) ?>">
<?php echo $contagempf->nu_metodologia->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contagempf->nu_roteiro->Visible) { // nu_roteiro ?>
	<tr id="r_nu_roteiro">
		<td><span id="elh_contagempf_nu_roteiro"><?php echo $contagempf->nu_roteiro->FldCaption() ?></span></td>
		<td<?php echo $contagempf->nu_roteiro->CellAttributes() ?>>
<span id="el_contagempf_nu_roteiro" class="control-group">
<span<?php echo $contagempf->nu_roteiro->ViewAttributes() ?>>
<?php echo $contagempf->nu_roteiro->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_roteiro" name="x_nu_roteiro" id="x_nu_roteiro" value="<?php echo ew_HtmlEncode($contagempf->nu_roteiro->CurrentValue) ?>">
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
fcontagempfedit.Lists["x_nu_faseMedida"].Options = <?php echo (is_array($contagempf->nu_faseMedida->EditValue)) ? ew_ArrayToJson($contagempf->nu_faseMedida->EditValue, 1) : "[]" ?>;
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
ew_CreateCalendar("fcontagempfedit", "x_dh_inicio", "%d/%m/%Y");
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
<span<?php echo $contagempf->ar_fasesRoteiro->ViewAttributes() ?>>
<?php echo $contagempf->ar_fasesRoteiro->EditValue ?></span>
</span>
<input type="hidden" data-field="x_ar_fasesRoteiro" name="x_ar_fasesRoteiro" id="x_ar_fasesRoteiro" value="<?php echo ew_HtmlEncode($contagempf->ar_fasesRoteiro->CurrentValue) ?>">
<?php echo $contagempf->ar_fasesRoteiro->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contagempf->vr_pfFaturamento->Visible) { // vr_pfFaturamento ?>
	<tr id="r_vr_pfFaturamento">
		<td><span id="elh_contagempf_vr_pfFaturamento"><?php echo $contagempf->vr_pfFaturamento->FldCaption() ?></span></td>
		<td<?php echo $contagempf->vr_pfFaturamento->CellAttributes() ?>>
<span id="el_contagempf_vr_pfFaturamento" class="control-group">
<span<?php echo $contagempf->vr_pfFaturamento->ViewAttributes() ?>>
<?php echo $contagempf->vr_pfFaturamento->EditValue ?></span>
</span>
<input type="hidden" data-field="x_vr_pfFaturamento" name="x_vr_pfFaturamento" id="x_vr_pfFaturamento" value="<?php echo ew_HtmlEncode($contagempf->vr_pfFaturamento->CurrentValue) ?>">
<?php echo $contagempf->vr_pfFaturamento->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<span id="el_contagempf_pc_varFasesRoteiro" class="control-group">
<input type="hidden" data-field="x_pc_varFasesRoteiro" name="x_pc_varFasesRoteiro" id="x_pc_varFasesRoteiro" value="<?php echo ew_HtmlEncode($contagempf->pc_varFasesRoteiro->CurrentValue) ?>">
</span>
<?php
	if (in_array("contagempf_agrupador", explode(",", $contagempf->getCurrentDetailTable())) && $contagempf_agrupador->DetailEdit) {
?>
<?php include_once "contagempf_agrupadorgrid.php" ?>
<?php } ?>
<?php
	if (in_array("contagempf_funcao", explode(",", $contagempf->getCurrentDetailTable())) && $contagempf_funcao->DetailEdit) {
?>
<?php include_once "contagempf_funcaogrid.php" ?>
<?php } ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fcontagempfedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$contagempf_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$contagempf_edit->Page_Terminate();
?>
