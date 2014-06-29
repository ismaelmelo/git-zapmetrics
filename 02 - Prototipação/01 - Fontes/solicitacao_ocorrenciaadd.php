<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "solicitacao_ocorrenciainfo.php" ?>
<?php include_once "solicitacaometricasinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$solicitacao_ocorrencia_add = NULL; // Initialize page object first

class csolicitacao_ocorrencia_add extends csolicitacao_ocorrencia {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'solicitacao_ocorrencia';

	// Page object name
	var $PageObjName = 'solicitacao_ocorrencia_add';

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

		// Table object (solicitacao_ocorrencia)
		if (!isset($GLOBALS["solicitacao_ocorrencia"])) {
			$GLOBALS["solicitacao_ocorrencia"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["solicitacao_ocorrencia"];
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
			define("EW_TABLE_NAME", 'solicitacao_ocorrencia', TRUE);

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
			$this->Page_Terminate("solicitacao_ocorrencialist.php");
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
			if (@$_GET["nu_ocorrencia"] != "") {
				$this->nu_ocorrencia->setQueryStringValue($_GET["nu_ocorrencia"]);
				$this->setKey("nu_ocorrencia", $this->nu_ocorrencia->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_ocorrencia", ""); // Clear key
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
					$this->Page_Terminate("solicitacao_ocorrencialist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "solicitacao_ocorrenciaview.php")
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
		$this->ic_tpOcorrencia->CurrentValue = NULL;
		$this->ic_tpOcorrencia->OldValue = $this->ic_tpOcorrencia->CurrentValue;
		$this->ic_exibirNoLaudo->CurrentValue = NULL;
		$this->ic_exibirNoLaudo->OldValue = $this->ic_exibirNoLaudo->CurrentValue;
		$this->ds_observacao->CurrentValue = NULL;
		$this->ds_observacao->OldValue = $this->ds_observacao->CurrentValue;
		$this->nu_usuarioInc->CurrentValue = NULL;
		$this->nu_usuarioInc->OldValue = $this->nu_usuarioInc->CurrentValue;
		$this->dh_inclusao->CurrentValue = NULL;
		$this->dh_inclusao->OldValue = $this->dh_inclusao->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_solicitacao->FldIsDetailKey) {
			$this->nu_solicitacao->setFormValue($objForm->GetValue("x_nu_solicitacao"));
		}
		if (!$this->ic_tpOcorrencia->FldIsDetailKey) {
			$this->ic_tpOcorrencia->setFormValue($objForm->GetValue("x_ic_tpOcorrencia"));
		}
		if (!$this->ic_exibirNoLaudo->FldIsDetailKey) {
			$this->ic_exibirNoLaudo->setFormValue($objForm->GetValue("x_ic_exibirNoLaudo"));
		}
		if (!$this->ds_observacao->FldIsDetailKey) {
			$this->ds_observacao->setFormValue($objForm->GetValue("x_ds_observacao"));
		}
		if (!$this->nu_usuarioInc->FldIsDetailKey) {
			$this->nu_usuarioInc->setFormValue($objForm->GetValue("x_nu_usuarioInc"));
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
		$this->nu_solicitacao->CurrentValue = $this->nu_solicitacao->FormValue;
		$this->ic_tpOcorrencia->CurrentValue = $this->ic_tpOcorrencia->FormValue;
		$this->ic_exibirNoLaudo->CurrentValue = $this->ic_exibirNoLaudo->FormValue;
		$this->ds_observacao->CurrentValue = $this->ds_observacao->FormValue;
		$this->nu_usuarioInc->CurrentValue = $this->nu_usuarioInc->FormValue;
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
		$this->nu_solicitacao->setDbValue($rs->fields('nu_solicitacao'));
		$this->nu_ocorrencia->setDbValue($rs->fields('nu_ocorrencia'));
		$this->ic_tpOcorrencia->setDbValue($rs->fields('ic_tpOcorrencia'));
		$this->ic_exibirNoLaudo->setDbValue($rs->fields('ic_exibirNoLaudo'));
		$this->ds_observacao->setDbValue($rs->fields('ds_observacao'));
		$this->nu_usuarioInc->setDbValue($rs->fields('nu_usuarioInc'));
		$this->dh_inclusao->setDbValue($rs->fields('dh_inclusao'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_solicitacao->DbValue = $row['nu_solicitacao'];
		$this->nu_ocorrencia->DbValue = $row['nu_ocorrencia'];
		$this->ic_tpOcorrencia->DbValue = $row['ic_tpOcorrencia'];
		$this->ic_exibirNoLaudo->DbValue = $row['ic_exibirNoLaudo'];
		$this->ds_observacao->DbValue = $row['ds_observacao'];
		$this->nu_usuarioInc->DbValue = $row['nu_usuarioInc'];
		$this->dh_inclusao->DbValue = $row['dh_inclusao'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_solicitacao")) <> "")
			$this->nu_solicitacao->CurrentValue = $this->getKey("nu_solicitacao"); // nu_solicitacao
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("nu_ocorrencia")) <> "")
			$this->nu_ocorrencia->CurrentValue = $this->getKey("nu_ocorrencia"); // nu_ocorrencia
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
		// nu_solicitacao
		// nu_ocorrencia
		// ic_tpOcorrencia
		// ic_exibirNoLaudo
		// ds_observacao
		// nu_usuarioInc
		// dh_inclusao

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_solicitacao
			$this->nu_solicitacao->ViewValue = $this->nu_solicitacao->CurrentValue;
			$this->nu_solicitacao->ViewCustomAttributes = "";

			// nu_ocorrencia
			$this->nu_ocorrencia->ViewValue = $this->nu_ocorrencia->CurrentValue;
			$this->nu_ocorrencia->ViewCustomAttributes = "";

			// ic_tpOcorrencia
			if (strval($this->ic_tpOcorrencia->CurrentValue) <> "") {
				switch ($this->ic_tpOcorrencia->CurrentValue) {
					case $this->ic_tpOcorrencia->FldTagValue(1):
						$this->ic_tpOcorrencia->ViewValue = $this->ic_tpOcorrencia->FldTagCaption(1) <> "" ? $this->ic_tpOcorrencia->FldTagCaption(1) : $this->ic_tpOcorrencia->CurrentValue;
						break;
					case $this->ic_tpOcorrencia->FldTagValue(2):
						$this->ic_tpOcorrencia->ViewValue = $this->ic_tpOcorrencia->FldTagCaption(2) <> "" ? $this->ic_tpOcorrencia->FldTagCaption(2) : $this->ic_tpOcorrencia->CurrentValue;
						break;
					case $this->ic_tpOcorrencia->FldTagValue(3):
						$this->ic_tpOcorrencia->ViewValue = $this->ic_tpOcorrencia->FldTagCaption(3) <> "" ? $this->ic_tpOcorrencia->FldTagCaption(3) : $this->ic_tpOcorrencia->CurrentValue;
						break;
					case $this->ic_tpOcorrencia->FldTagValue(4):
						$this->ic_tpOcorrencia->ViewValue = $this->ic_tpOcorrencia->FldTagCaption(4) <> "" ? $this->ic_tpOcorrencia->FldTagCaption(4) : $this->ic_tpOcorrencia->CurrentValue;
						break;
					default:
						$this->ic_tpOcorrencia->ViewValue = $this->ic_tpOcorrencia->CurrentValue;
				}
			} else {
				$this->ic_tpOcorrencia->ViewValue = NULL;
			}
			$this->ic_tpOcorrencia->ViewCustomAttributes = "";

			// ic_exibirNoLaudo
			if (strval($this->ic_exibirNoLaudo->CurrentValue) <> "") {
				switch ($this->ic_exibirNoLaudo->CurrentValue) {
					case $this->ic_exibirNoLaudo->FldTagValue(1):
						$this->ic_exibirNoLaudo->ViewValue = $this->ic_exibirNoLaudo->FldTagCaption(1) <> "" ? $this->ic_exibirNoLaudo->FldTagCaption(1) : $this->ic_exibirNoLaudo->CurrentValue;
						break;
					case $this->ic_exibirNoLaudo->FldTagValue(2):
						$this->ic_exibirNoLaudo->ViewValue = $this->ic_exibirNoLaudo->FldTagCaption(2) <> "" ? $this->ic_exibirNoLaudo->FldTagCaption(2) : $this->ic_exibirNoLaudo->CurrentValue;
						break;
					default:
						$this->ic_exibirNoLaudo->ViewValue = $this->ic_exibirNoLaudo->CurrentValue;
				}
			} else {
				$this->ic_exibirNoLaudo->ViewValue = NULL;
			}
			$this->ic_exibirNoLaudo->ViewCustomAttributes = "";

			// ds_observacao
			$this->ds_observacao->ViewValue = $this->ds_observacao->CurrentValue;
			$this->ds_observacao->ViewCustomAttributes = "";

			// nu_usuarioInc
			if (strval($this->nu_usuarioInc->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioInc->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioInc, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioInc->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioInc->ViewValue = $this->nu_usuarioInc->CurrentValue;
				}
			} else {
				$this->nu_usuarioInc->ViewValue = NULL;
			}
			$this->nu_usuarioInc->ViewCustomAttributes = "";

			// dh_inclusao
			$this->dh_inclusao->ViewValue = $this->dh_inclusao->CurrentValue;
			$this->dh_inclusao->ViewValue = ew_FormatDateTime($this->dh_inclusao->ViewValue, 11);
			$this->dh_inclusao->ViewCustomAttributes = "";

			// nu_solicitacao
			$this->nu_solicitacao->LinkCustomAttributes = "";
			$this->nu_solicitacao->HrefValue = "";
			$this->nu_solicitacao->TooltipValue = "";

			// ic_tpOcorrencia
			$this->ic_tpOcorrencia->LinkCustomAttributes = "";
			$this->ic_tpOcorrencia->HrefValue = "";
			$this->ic_tpOcorrencia->TooltipValue = "";

			// ic_exibirNoLaudo
			$this->ic_exibirNoLaudo->LinkCustomAttributes = "";
			$this->ic_exibirNoLaudo->HrefValue = "";
			$this->ic_exibirNoLaudo->TooltipValue = "";

			// ds_observacao
			$this->ds_observacao->LinkCustomAttributes = "";
			$this->ds_observacao->HrefValue = "";
			$this->ds_observacao->TooltipValue = "";

			// nu_usuarioInc
			$this->nu_usuarioInc->LinkCustomAttributes = "";
			$this->nu_usuarioInc->HrefValue = "";
			$this->nu_usuarioInc->TooltipValue = "";

			// dh_inclusao
			$this->dh_inclusao->LinkCustomAttributes = "";
			$this->dh_inclusao->HrefValue = "";
			$this->dh_inclusao->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nu_solicitacao
			$this->nu_solicitacao->EditCustomAttributes = "";
			if ($this->nu_solicitacao->getSessionValue() <> "") {
				$this->nu_solicitacao->CurrentValue = $this->nu_solicitacao->getSessionValue();
			$this->nu_solicitacao->ViewValue = $this->nu_solicitacao->CurrentValue;
			$this->nu_solicitacao->ViewCustomAttributes = "";
			} else {
			$this->nu_solicitacao->EditValue = ew_HtmlEncode($this->nu_solicitacao->CurrentValue);
			$this->nu_solicitacao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_solicitacao->FldCaption()));
			}

			// ic_tpOcorrencia
			$this->ic_tpOcorrencia->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_tpOcorrencia->FldTagValue(1), $this->ic_tpOcorrencia->FldTagCaption(1) <> "" ? $this->ic_tpOcorrencia->FldTagCaption(1) : $this->ic_tpOcorrencia->FldTagValue(1));
			$arwrk[] = array($this->ic_tpOcorrencia->FldTagValue(2), $this->ic_tpOcorrencia->FldTagCaption(2) <> "" ? $this->ic_tpOcorrencia->FldTagCaption(2) : $this->ic_tpOcorrencia->FldTagValue(2));
			$arwrk[] = array($this->ic_tpOcorrencia->FldTagValue(3), $this->ic_tpOcorrencia->FldTagCaption(3) <> "" ? $this->ic_tpOcorrencia->FldTagCaption(3) : $this->ic_tpOcorrencia->FldTagValue(3));
			$arwrk[] = array($this->ic_tpOcorrencia->FldTagValue(4), $this->ic_tpOcorrencia->FldTagCaption(4) <> "" ? $this->ic_tpOcorrencia->FldTagCaption(4) : $this->ic_tpOcorrencia->FldTagValue(4));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_tpOcorrencia->EditValue = $arwrk;

			// ic_exibirNoLaudo
			$this->ic_exibirNoLaudo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_exibirNoLaudo->FldTagValue(1), $this->ic_exibirNoLaudo->FldTagCaption(1) <> "" ? $this->ic_exibirNoLaudo->FldTagCaption(1) : $this->ic_exibirNoLaudo->FldTagValue(1));
			$arwrk[] = array($this->ic_exibirNoLaudo->FldTagValue(2), $this->ic_exibirNoLaudo->FldTagCaption(2) <> "" ? $this->ic_exibirNoLaudo->FldTagCaption(2) : $this->ic_exibirNoLaudo->FldTagValue(2));
			$this->ic_exibirNoLaudo->EditValue = $arwrk;

			// ds_observacao
			$this->ds_observacao->EditCustomAttributes = "";
			$this->ds_observacao->EditValue = $this->ds_observacao->CurrentValue;
			$this->ds_observacao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_observacao->FldCaption()));

			// nu_usuarioInc
			// dh_inclusao
			// Edit refer script
			// nu_solicitacao

			$this->nu_solicitacao->HrefValue = "";

			// ic_tpOcorrencia
			$this->ic_tpOcorrencia->HrefValue = "";

			// ic_exibirNoLaudo
			$this->ic_exibirNoLaudo->HrefValue = "";

			// ds_observacao
			$this->ds_observacao->HrefValue = "";

			// nu_usuarioInc
			$this->nu_usuarioInc->HrefValue = "";

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
		if (!$this->nu_solicitacao->FldIsDetailKey && !is_null($this->nu_solicitacao->FormValue) && $this->nu_solicitacao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_solicitacao->FldCaption());
		}
		if (!ew_CheckInteger($this->nu_solicitacao->FormValue)) {
			ew_AddMessage($gsFormError, $this->nu_solicitacao->FldErrMsg());
		}
		if (!$this->ic_tpOcorrencia->FldIsDetailKey && !is_null($this->ic_tpOcorrencia->FormValue) && $this->ic_tpOcorrencia->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_tpOcorrencia->FldCaption());
		}
		if ($this->ic_exibirNoLaudo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_exibirNoLaudo->FldCaption());
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

		// Check referential integrity for master table 'solicitacaoMetricas'
		$bValidMasterRecord = TRUE;
		$sMasterFilter = $this->SqlMasterFilter_solicitacaoMetricas();
		if (strval($this->nu_solicitacao->CurrentValue) <> "") {
			$sMasterFilter = str_replace("@nu_solMetricas@", ew_AdjustSql($this->nu_solicitacao->CurrentValue), $sMasterFilter);
		} else {
			$bValidMasterRecord = FALSE;
		}
		if ($bValidMasterRecord) {
			$rsmaster = $GLOBALS["solicitacaoMetricas"]->LoadRs($sMasterFilter);
			$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
			$rsmaster->Close();
		}
		if (!$bValidMasterRecord) {
			$sRelatedRecordMsg = str_replace("%t", "solicitacaoMetricas", $Language->Phrase("RelatedRecordRequired"));
			$this->setFailureMessage($sRelatedRecordMsg);
			return FALSE;
		}

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// nu_solicitacao
		$this->nu_solicitacao->SetDbValueDef($rsnew, $this->nu_solicitacao->CurrentValue, 0, FALSE);

		// ic_tpOcorrencia
		$this->ic_tpOcorrencia->SetDbValueDef($rsnew, $this->ic_tpOcorrencia->CurrentValue, NULL, FALSE);

		// ic_exibirNoLaudo
		$this->ic_exibirNoLaudo->SetDbValueDef($rsnew, $this->ic_exibirNoLaudo->CurrentValue, NULL, FALSE);

		// ds_observacao
		$this->ds_observacao->SetDbValueDef($rsnew, $this->ds_observacao->CurrentValue, NULL, FALSE);

		// nu_usuarioInc
		$this->nu_usuarioInc->SetDbValueDef($rsnew, CurrentUserID(), NULL);
		$rsnew['nu_usuarioInc'] = &$this->nu_usuarioInc->DbValue;

		// dh_inclusao
		$this->dh_inclusao->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
		$rsnew['dh_inclusao'] = &$this->dh_inclusao->DbValue;

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && $this->nu_solicitacao->CurrentValue == "" && $this->nu_solicitacao->getSessionValue() == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
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
			$this->nu_ocorrencia->setDbValue($conn->Insert_ID());
			$rsnew['nu_ocorrencia'] = $this->nu_ocorrencia->DbValue;
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "solicitacao_ocorrencialist.php", $this->TableVar);
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
if (!isset($solicitacao_ocorrencia_add)) $solicitacao_ocorrencia_add = new csolicitacao_ocorrencia_add();

// Page init
$solicitacao_ocorrencia_add->Page_Init();

// Page main
$solicitacao_ocorrencia_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$solicitacao_ocorrencia_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var solicitacao_ocorrencia_add = new ew_Page("solicitacao_ocorrencia_add");
solicitacao_ocorrencia_add.PageID = "add"; // Page ID
var EW_PAGE_ID = solicitacao_ocorrencia_add.PageID; // For backward compatibility

// Form object
var fsolicitacao_ocorrenciaadd = new ew_Form("fsolicitacao_ocorrenciaadd");

// Validate form
fsolicitacao_ocorrenciaadd.Validate = function() {
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
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($solicitacao_ocorrencia->nu_solicitacao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_solicitacao");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($solicitacao_ocorrencia->nu_solicitacao->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ic_tpOcorrencia");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($solicitacao_ocorrencia->ic_tpOcorrencia->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_exibirNoLaudo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($solicitacao_ocorrencia->ic_exibirNoLaudo->FldCaption()) ?>");

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
fsolicitacao_ocorrenciaadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsolicitacao_ocorrenciaadd.ValidateRequired = true;
<?php } else { ?>
fsolicitacao_ocorrenciaadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsolicitacao_ocorrenciaadd.Lists["x_nu_usuarioInc"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $solicitacao_ocorrencia_add->ShowPageHeader(); ?>
<?php
$solicitacao_ocorrencia_add->ShowMessage();
?>
<form name="fsolicitacao_ocorrenciaadd" id="fsolicitacao_ocorrenciaadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="solicitacao_ocorrencia">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_solicitacao_ocorrenciaadd" class="table table-bordered table-striped">
<?php if ($solicitacao_ocorrencia->nu_solicitacao->Visible) { // nu_solicitacao ?>
	<tr id="r_nu_solicitacao">
		<td><span id="elh_solicitacao_ocorrencia_nu_solicitacao"><?php echo $solicitacao_ocorrencia->nu_solicitacao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $solicitacao_ocorrencia->nu_solicitacao->CellAttributes() ?>>
<?php if ($solicitacao_ocorrencia->nu_solicitacao->getSessionValue() <> "") { ?>
<span<?php echo $solicitacao_ocorrencia->nu_solicitacao->ViewAttributes() ?>>
<?php echo $solicitacao_ocorrencia->nu_solicitacao->ViewValue ?></span>
<input type="hidden" id="x_nu_solicitacao" name="x_nu_solicitacao" value="<?php echo ew_HtmlEncode($solicitacao_ocorrencia->nu_solicitacao->CurrentValue) ?>">
<?php } else { ?>
<input type="text" data-field="x_nu_solicitacao" name="x_nu_solicitacao" id="x_nu_solicitacao" size="30" placeholder="<?php echo $solicitacao_ocorrencia->nu_solicitacao->PlaceHolder ?>" value="<?php echo $solicitacao_ocorrencia->nu_solicitacao->EditValue ?>"<?php echo $solicitacao_ocorrencia->nu_solicitacao->EditAttributes() ?>>
<?php } ?>
<?php echo $solicitacao_ocorrencia->nu_solicitacao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($solicitacao_ocorrencia->ic_tpOcorrencia->Visible) { // ic_tpOcorrencia ?>
	<tr id="r_ic_tpOcorrencia">
		<td><span id="elh_solicitacao_ocorrencia_ic_tpOcorrencia"><?php echo $solicitacao_ocorrencia->ic_tpOcorrencia->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $solicitacao_ocorrencia->ic_tpOcorrencia->CellAttributes() ?>>
<span id="el_solicitacao_ocorrencia_ic_tpOcorrencia" class="control-group">
<select data-field="x_ic_tpOcorrencia" id="x_ic_tpOcorrencia" name="x_ic_tpOcorrencia"<?php echo $solicitacao_ocorrencia->ic_tpOcorrencia->EditAttributes() ?>>
<?php
if (is_array($solicitacao_ocorrencia->ic_tpOcorrencia->EditValue)) {
	$arwrk = $solicitacao_ocorrencia->ic_tpOcorrencia->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($solicitacao_ocorrencia->ic_tpOcorrencia->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $solicitacao_ocorrencia->ic_tpOcorrencia->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($solicitacao_ocorrencia->ic_exibirNoLaudo->Visible) { // ic_exibirNoLaudo ?>
	<tr id="r_ic_exibirNoLaudo">
		<td><span id="elh_solicitacao_ocorrencia_ic_exibirNoLaudo"><?php echo $solicitacao_ocorrencia->ic_exibirNoLaudo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $solicitacao_ocorrencia->ic_exibirNoLaudo->CellAttributes() ?>>
<span id="el_solicitacao_ocorrencia_ic_exibirNoLaudo" class="control-group">
<div id="tp_x_ic_exibirNoLaudo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_exibirNoLaudo" id="x_ic_exibirNoLaudo" value="{value}"<?php echo $solicitacao_ocorrencia->ic_exibirNoLaudo->EditAttributes() ?>></div>
<div id="dsl_x_ic_exibirNoLaudo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $solicitacao_ocorrencia->ic_exibirNoLaudo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($solicitacao_ocorrencia->ic_exibirNoLaudo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_exibirNoLaudo" name="x_ic_exibirNoLaudo" id="x_ic_exibirNoLaudo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $solicitacao_ocorrencia->ic_exibirNoLaudo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $solicitacao_ocorrencia->ic_exibirNoLaudo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($solicitacao_ocorrencia->ds_observacao->Visible) { // ds_observacao ?>
	<tr id="r_ds_observacao">
		<td><span id="elh_solicitacao_ocorrencia_ds_observacao"><?php echo $solicitacao_ocorrencia->ds_observacao->FldCaption() ?></span></td>
		<td<?php echo $solicitacao_ocorrencia->ds_observacao->CellAttributes() ?>>
<span id="el_solicitacao_ocorrencia_ds_observacao" class="control-group">
<textarea data-field="x_ds_observacao" name="x_ds_observacao" id="x_ds_observacao" cols="35" rows="4" placeholder="<?php echo $solicitacao_ocorrencia->ds_observacao->PlaceHolder ?>"<?php echo $solicitacao_ocorrencia->ds_observacao->EditAttributes() ?>><?php echo $solicitacao_ocorrencia->ds_observacao->EditValue ?></textarea>
</span>
<?php echo $solicitacao_ocorrencia->ds_observacao->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fsolicitacao_ocorrenciaadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$solicitacao_ocorrencia_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$solicitacao_ocorrencia_add->Page_Terminate();
?>
