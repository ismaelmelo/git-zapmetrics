<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "indicadorversaoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "indicadorvalorinfo.php" ?>
<?php include_once "indicadorgridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$indicadorversao_add = NULL; // Initialize page object first

class cindicadorversao_add extends cindicadorversao {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{FE479719-4CC0-498B-BE07-C9817DD0435B}";

	// Table name
	var $TableName = 'indicadorversao';

	// Page object name
	var $PageObjName = 'indicadorversao_add';

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

		// Table object (indicadorversao)
		if (!isset($GLOBALS["indicadorversao"])) {
			$GLOBALS["indicadorversao"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["indicadorversao"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Table object (indicadorvalor)
		if (!isset($GLOBALS['indicadorvalor'])) $GLOBALS['indicadorvalor'] = new cindicadorvalor();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'indicadorversao', TRUE);

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
			$this->Page_Terminate("indicadorversaolist.php");
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
			if (@$_GET["nu_indicador"] != "") {
				$this->nu_indicador->setQueryStringValue($_GET["nu_indicador"]);
				$this->setKey("nu_indicador", $this->nu_indicador->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_indicador", ""); // Clear key
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
					$this->Page_Terminate("indicadorversaolist.php"); // No matching record, return to list
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
					if (ew_GetPageName($sReturnUrl) == "indicadorversaoview.php")
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
		$this->nu_indicador->CurrentValue = NULL;
		$this->nu_indicador->OldValue = $this->nu_indicador->CurrentValue;
		$this->nu_versao->CurrentValue = NULL;
		$this->nu_versao->OldValue = $this->nu_versao->CurrentValue;
		$this->ic_periodicidadeGeracao->CurrentValue = NULL;
		$this->ic_periodicidadeGeracao->OldValue = $this->ic_periodicidadeGeracao->CurrentValue;
		$this->ds_origemIndicador->CurrentValue = NULL;
		$this->ds_origemIndicador->OldValue = $this->ds_origemIndicador->CurrentValue;
		$this->ic_reponsavelColetaCtrl->CurrentValue = NULL;
		$this->ic_reponsavelColetaCtrl->OldValue = $this->ic_reponsavelColetaCtrl->CurrentValue;
		$this->ds_codigoSql->CurrentValue = NULL;
		$this->ds_codigoSql->OldValue = $this->ds_codigoSql->CurrentValue;
		$this->dh_versao->CurrentValue = NULL;
		$this->dh_versao->OldValue = $this->dh_versao->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_indicador->FldIsDetailKey) {
			$this->nu_indicador->setFormValue($objForm->GetValue("x_nu_indicador"));
		}
		if (!$this->nu_versao->FldIsDetailKey) {
			$this->nu_versao->setFormValue($objForm->GetValue("x_nu_versao"));
		}
		if (!$this->ic_periodicidadeGeracao->FldIsDetailKey) {
			$this->ic_periodicidadeGeracao->setFormValue($objForm->GetValue("x_ic_periodicidadeGeracao"));
		}
		if (!$this->ds_origemIndicador->FldIsDetailKey) {
			$this->ds_origemIndicador->setFormValue($objForm->GetValue("x_ds_origemIndicador"));
		}
		if (!$this->ic_reponsavelColetaCtrl->FldIsDetailKey) {
			$this->ic_reponsavelColetaCtrl->setFormValue($objForm->GetValue("x_ic_reponsavelColetaCtrl"));
		}
		if (!$this->ds_codigoSql->FldIsDetailKey) {
			$this->ds_codigoSql->setFormValue($objForm->GetValue("x_ds_codigoSql"));
		}
		if (!$this->dh_versao->FldIsDetailKey) {
			$this->dh_versao->setFormValue($objForm->GetValue("x_dh_versao"));
			$this->dh_versao->CurrentValue = ew_UnFormatDateTime($this->dh_versao->CurrentValue, 11);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->nu_indicador->CurrentValue = $this->nu_indicador->FormValue;
		$this->nu_versao->CurrentValue = $this->nu_versao->FormValue;
		$this->ic_periodicidadeGeracao->CurrentValue = $this->ic_periodicidadeGeracao->FormValue;
		$this->ds_origemIndicador->CurrentValue = $this->ds_origemIndicador->FormValue;
		$this->ic_reponsavelColetaCtrl->CurrentValue = $this->ic_reponsavelColetaCtrl->FormValue;
		$this->ds_codigoSql->CurrentValue = $this->ds_codigoSql->FormValue;
		$this->dh_versao->CurrentValue = $this->dh_versao->FormValue;
		$this->dh_versao->CurrentValue = ew_UnFormatDateTime($this->dh_versao->CurrentValue, 11);
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
		$this->nu_indicador->setDbValue($rs->fields('nu_indicador'));
		$this->nu_versao->setDbValue($rs->fields('nu_versao'));
		$this->ic_periodicidadeGeracao->setDbValue($rs->fields('ic_periodicidadeGeracao'));
		$this->ds_origemIndicador->setDbValue($rs->fields('ds_origemIndicador'));
		$this->ic_reponsavelColetaCtrl->setDbValue($rs->fields('ic_reponsavelColetaCtrl'));
		$this->ds_codigoSql->setDbValue($rs->fields('ds_codigoSql'));
		$this->dh_versao->setDbValue($rs->fields('dh_versao'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_indicador->DbValue = $row['nu_indicador'];
		$this->nu_versao->DbValue = $row['nu_versao'];
		$this->ic_periodicidadeGeracao->DbValue = $row['ic_periodicidadeGeracao'];
		$this->ds_origemIndicador->DbValue = $row['ds_origemIndicador'];
		$this->ic_reponsavelColetaCtrl->DbValue = $row['ic_reponsavelColetaCtrl'];
		$this->ds_codigoSql->DbValue = $row['ds_codigoSql'];
		$this->dh_versao->DbValue = $row['dh_versao'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_indicador")) <> "")
			$this->nu_indicador->CurrentValue = $this->getKey("nu_indicador"); // nu_indicador
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
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_indicador
		// nu_versao
		// ic_periodicidadeGeracao
		// ds_origemIndicador
		// ic_reponsavelColetaCtrl
		// ds_codigoSql
		// dh_versao

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_indicador
			if (strval($this->nu_indicador->CurrentValue) <> "") {
				$sFilterWrk = "[nu_indicador]" . ew_SearchString("=", $this->nu_indicador->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_indicador], [no_indicador] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[indicador]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_indicador, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_indicador] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_indicador->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_indicador->ViewValue = $this->nu_indicador->CurrentValue;
				}
			} else {
				$this->nu_indicador->ViewValue = NULL;
			}
			$this->nu_indicador->ViewCustomAttributes = "";

			// nu_versao
			$this->nu_versao->ViewValue = $this->nu_versao->CurrentValue;
			$this->nu_versao->ViewCustomAttributes = "";

			// ic_periodicidadeGeracao
			if (strval($this->ic_periodicidadeGeracao->CurrentValue) <> "") {
				switch ($this->ic_periodicidadeGeracao->CurrentValue) {
					case $this->ic_periodicidadeGeracao->FldTagValue(1):
						$this->ic_periodicidadeGeracao->ViewValue = $this->ic_periodicidadeGeracao->FldTagCaption(1) <> "" ? $this->ic_periodicidadeGeracao->FldTagCaption(1) : $this->ic_periodicidadeGeracao->CurrentValue;
						break;
					case $this->ic_periodicidadeGeracao->FldTagValue(2):
						$this->ic_periodicidadeGeracao->ViewValue = $this->ic_periodicidadeGeracao->FldTagCaption(2) <> "" ? $this->ic_periodicidadeGeracao->FldTagCaption(2) : $this->ic_periodicidadeGeracao->CurrentValue;
						break;
					case $this->ic_periodicidadeGeracao->FldTagValue(3):
						$this->ic_periodicidadeGeracao->ViewValue = $this->ic_periodicidadeGeracao->FldTagCaption(3) <> "" ? $this->ic_periodicidadeGeracao->FldTagCaption(3) : $this->ic_periodicidadeGeracao->CurrentValue;
						break;
					case $this->ic_periodicidadeGeracao->FldTagValue(4):
						$this->ic_periodicidadeGeracao->ViewValue = $this->ic_periodicidadeGeracao->FldTagCaption(4) <> "" ? $this->ic_periodicidadeGeracao->FldTagCaption(4) : $this->ic_periodicidadeGeracao->CurrentValue;
						break;
					case $this->ic_periodicidadeGeracao->FldTagValue(5):
						$this->ic_periodicidadeGeracao->ViewValue = $this->ic_periodicidadeGeracao->FldTagCaption(5) <> "" ? $this->ic_periodicidadeGeracao->FldTagCaption(5) : $this->ic_periodicidadeGeracao->CurrentValue;
						break;
					default:
						$this->ic_periodicidadeGeracao->ViewValue = $this->ic_periodicidadeGeracao->CurrentValue;
				}
			} else {
				$this->ic_periodicidadeGeracao->ViewValue = NULL;
			}
			$this->ic_periodicidadeGeracao->ViewCustomAttributes = "";

			// ds_origemIndicador
			$this->ds_origemIndicador->ViewValue = $this->ds_origemIndicador->CurrentValue;
			$this->ds_origemIndicador->ViewCustomAttributes = "";

			// ic_reponsavelColetaCtrl
			if (strval($this->ic_reponsavelColetaCtrl->CurrentValue) <> "") {
				switch ($this->ic_reponsavelColetaCtrl->CurrentValue) {
					case $this->ic_reponsavelColetaCtrl->FldTagValue(1):
						$this->ic_reponsavelColetaCtrl->ViewValue = $this->ic_reponsavelColetaCtrl->FldTagCaption(1) <> "" ? $this->ic_reponsavelColetaCtrl->FldTagCaption(1) : $this->ic_reponsavelColetaCtrl->CurrentValue;
						break;
					case $this->ic_reponsavelColetaCtrl->FldTagValue(2):
						$this->ic_reponsavelColetaCtrl->ViewValue = $this->ic_reponsavelColetaCtrl->FldTagCaption(2) <> "" ? $this->ic_reponsavelColetaCtrl->FldTagCaption(2) : $this->ic_reponsavelColetaCtrl->CurrentValue;
						break;
					default:
						$this->ic_reponsavelColetaCtrl->ViewValue = $this->ic_reponsavelColetaCtrl->CurrentValue;
				}
			} else {
				$this->ic_reponsavelColetaCtrl->ViewValue = NULL;
			}
			$this->ic_reponsavelColetaCtrl->ViewCustomAttributes = "";

			// ds_codigoSql
			$this->ds_codigoSql->ViewValue = $this->ds_codigoSql->CurrentValue;
			$this->ds_codigoSql->ViewCustomAttributes = "";

			// dh_versao
			$this->dh_versao->ViewValue = $this->dh_versao->CurrentValue;
			$this->dh_versao->ViewValue = ew_FormatDateTime($this->dh_versao->ViewValue, 11);
			$this->dh_versao->ViewCustomAttributes = "";

			// nu_indicador
			$this->nu_indicador->LinkCustomAttributes = "";
			$this->nu_indicador->HrefValue = "";
			$this->nu_indicador->TooltipValue = "";

			// nu_versao
			$this->nu_versao->LinkCustomAttributes = "";
			$this->nu_versao->HrefValue = "";
			$this->nu_versao->TooltipValue = "";

			// ic_periodicidadeGeracao
			$this->ic_periodicidadeGeracao->LinkCustomAttributes = "";
			$this->ic_periodicidadeGeracao->HrefValue = "";
			$this->ic_periodicidadeGeracao->TooltipValue = "";

			// ds_origemIndicador
			$this->ds_origemIndicador->LinkCustomAttributes = "";
			$this->ds_origemIndicador->HrefValue = "";
			$this->ds_origemIndicador->TooltipValue = "";

			// ic_reponsavelColetaCtrl
			$this->ic_reponsavelColetaCtrl->LinkCustomAttributes = "";
			$this->ic_reponsavelColetaCtrl->HrefValue = "";
			$this->ic_reponsavelColetaCtrl->TooltipValue = "";

			// ds_codigoSql
			$this->ds_codigoSql->LinkCustomAttributes = "";
			$this->ds_codigoSql->HrefValue = "";
			$this->ds_codigoSql->TooltipValue = "";

			// dh_versao
			$this->dh_versao->LinkCustomAttributes = "";
			$this->dh_versao->HrefValue = "";
			$this->dh_versao->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nu_indicador
			$this->nu_indicador->EditCustomAttributes = "";
			if ($this->nu_indicador->getSessionValue() <> "") {
				$this->nu_indicador->CurrentValue = $this->nu_indicador->getSessionValue();
			if (strval($this->nu_indicador->CurrentValue) <> "") {
				$sFilterWrk = "[nu_indicador]" . ew_SearchString("=", $this->nu_indicador->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_indicador], [no_indicador] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[indicador]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_indicador, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_indicador] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_indicador->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_indicador->ViewValue = $this->nu_indicador->CurrentValue;
				}
			} else {
				$this->nu_indicador->ViewValue = NULL;
			}
			$this->nu_indicador->ViewCustomAttributes = "";
			} else {
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_indicador], [no_indicador] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[indicador]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_indicador, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_indicador] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_indicador->EditValue = $arwrk;
			}

			// nu_versao
			$this->nu_versao->EditCustomAttributes = "";
			if ($this->nu_versao->getSessionValue() <> "") {
				$this->nu_versao->CurrentValue = $this->nu_versao->getSessionValue();
			$this->nu_versao->ViewValue = $this->nu_versao->CurrentValue;
			$this->nu_versao->ViewCustomAttributes = "";
			} else {
			$this->nu_versao->EditValue = ew_HtmlEncode($this->nu_versao->CurrentValue);
			$this->nu_versao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_versao->FldCaption()));
			}

			// ic_periodicidadeGeracao
			$this->ic_periodicidadeGeracao->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_periodicidadeGeracao->FldTagValue(1), $this->ic_periodicidadeGeracao->FldTagCaption(1) <> "" ? $this->ic_periodicidadeGeracao->FldTagCaption(1) : $this->ic_periodicidadeGeracao->FldTagValue(1));
			$arwrk[] = array($this->ic_periodicidadeGeracao->FldTagValue(2), $this->ic_periodicidadeGeracao->FldTagCaption(2) <> "" ? $this->ic_periodicidadeGeracao->FldTagCaption(2) : $this->ic_periodicidadeGeracao->FldTagValue(2));
			$arwrk[] = array($this->ic_periodicidadeGeracao->FldTagValue(3), $this->ic_periodicidadeGeracao->FldTagCaption(3) <> "" ? $this->ic_periodicidadeGeracao->FldTagCaption(3) : $this->ic_periodicidadeGeracao->FldTagValue(3));
			$arwrk[] = array($this->ic_periodicidadeGeracao->FldTagValue(4), $this->ic_periodicidadeGeracao->FldTagCaption(4) <> "" ? $this->ic_periodicidadeGeracao->FldTagCaption(4) : $this->ic_periodicidadeGeracao->FldTagValue(4));
			$arwrk[] = array($this->ic_periodicidadeGeracao->FldTagValue(5), $this->ic_periodicidadeGeracao->FldTagCaption(5) <> "" ? $this->ic_periodicidadeGeracao->FldTagCaption(5) : $this->ic_periodicidadeGeracao->FldTagValue(5));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_periodicidadeGeracao->EditValue = $arwrk;

			// ds_origemIndicador
			$this->ds_origemIndicador->EditCustomAttributes = "";
			$this->ds_origemIndicador->EditValue = $this->ds_origemIndicador->CurrentValue;
			$this->ds_origemIndicador->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_origemIndicador->FldCaption()));

			// ic_reponsavelColetaCtrl
			$this->ic_reponsavelColetaCtrl->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_reponsavelColetaCtrl->FldTagValue(1), $this->ic_reponsavelColetaCtrl->FldTagCaption(1) <> "" ? $this->ic_reponsavelColetaCtrl->FldTagCaption(1) : $this->ic_reponsavelColetaCtrl->FldTagValue(1));
			$arwrk[] = array($this->ic_reponsavelColetaCtrl->FldTagValue(2), $this->ic_reponsavelColetaCtrl->FldTagCaption(2) <> "" ? $this->ic_reponsavelColetaCtrl->FldTagCaption(2) : $this->ic_reponsavelColetaCtrl->FldTagValue(2));
			$this->ic_reponsavelColetaCtrl->EditValue = $arwrk;

			// ds_codigoSql
			$this->ds_codigoSql->EditCustomAttributes = "";
			$this->ds_codigoSql->EditValue = $this->ds_codigoSql->CurrentValue;
			$this->ds_codigoSql->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_codigoSql->FldCaption()));

			// dh_versao
			// Edit refer script
			// nu_indicador

			$this->nu_indicador->HrefValue = "";

			// nu_versao
			$this->nu_versao->HrefValue = "";

			// ic_periodicidadeGeracao
			$this->ic_periodicidadeGeracao->HrefValue = "";

			// ds_origemIndicador
			$this->ds_origemIndicador->HrefValue = "";

			// ic_reponsavelColetaCtrl
			$this->ic_reponsavelColetaCtrl->HrefValue = "";

			// ds_codigoSql
			$this->ds_codigoSql->HrefValue = "";

			// dh_versao
			$this->dh_versao->HrefValue = "";
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
		if (!$this->nu_indicador->FldIsDetailKey && !is_null($this->nu_indicador->FormValue) && $this->nu_indicador->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_indicador->FldCaption());
		}
		if (!$this->nu_versao->FldIsDetailKey && !is_null($this->nu_versao->FormValue) && $this->nu_versao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_versao->FldCaption());
		}
		if (!ew_CheckInteger($this->nu_versao->FormValue)) {
			ew_AddMessage($gsFormError, $this->nu_versao->FldErrMsg());
		}
		if (!$this->ic_periodicidadeGeracao->FldIsDetailKey && !is_null($this->ic_periodicidadeGeracao->FormValue) && $this->ic_periodicidadeGeracao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_periodicidadeGeracao->FldCaption());
		}
		if ($this->ic_reponsavelColetaCtrl->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_reponsavelColetaCtrl->FldCaption());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("indicador", $DetailTblVar) && $GLOBALS["indicador"]->DetailAdd) {
			if (!isset($GLOBALS["indicador_grid"])) $GLOBALS["indicador_grid"] = new cindicador_grid(); // get detail page object
			$GLOBALS["indicador_grid"]->ValidateGridForm();
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

		// nu_indicador
		$this->nu_indicador->SetDbValueDef($rsnew, $this->nu_indicador->CurrentValue, 0, FALSE);

		// nu_versao
		$this->nu_versao->SetDbValueDef($rsnew, $this->nu_versao->CurrentValue, 0, FALSE);

		// ic_periodicidadeGeracao
		$this->ic_periodicidadeGeracao->SetDbValueDef($rsnew, $this->ic_periodicidadeGeracao->CurrentValue, NULL, FALSE);

		// ds_origemIndicador
		$this->ds_origemIndicador->SetDbValueDef($rsnew, $this->ds_origemIndicador->CurrentValue, NULL, FALSE);

		// ic_reponsavelColetaCtrl
		$this->ic_reponsavelColetaCtrl->SetDbValueDef($rsnew, $this->ic_reponsavelColetaCtrl->CurrentValue, NULL, FALSE);

		// ds_codigoSql
		$this->ds_codigoSql->SetDbValueDef($rsnew, $this->ds_codigoSql->CurrentValue, NULL, FALSE);

		// dh_versao
		$this->dh_versao->SetDbValueDef($rsnew, ew_CurrentDate(), NULL);
		$rsnew['dh_versao'] = &$this->dh_versao->DbValue;

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && $this->nu_indicador->CurrentValue == "" && $this->nu_indicador->getSessionValue() == "") {
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

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("indicador", $DetailTblVar) && $GLOBALS["indicador"]->DetailAdd) {
				$GLOBALS["indicador"]->nu_indicador->setSessionValue($this->nu_indicador->CurrentValue); // Set master key
				if (!isset($GLOBALS["indicador_grid"])) $GLOBALS["indicador_grid"] = new cindicador_grid(); // Get detail page object
				$AddRow = $GLOBALS["indicador_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["indicador"]->nu_indicador->setSessionValue(""); // Clear master key if insert failed
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
			if ($sMasterTblVar == "indicadorvalor") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_indicador"] <> "") {
					$GLOBALS["indicadorvalor"]->nu_indicador->setQueryStringValue($_GET["nu_indicador"]);
					$this->nu_indicador->setQueryStringValue($GLOBALS["indicadorvalor"]->nu_indicador->QueryStringValue);
					$this->nu_indicador->setSessionValue($this->nu_indicador->QueryStringValue);
					if (!is_numeric($GLOBALS["indicadorvalor"]->nu_indicador->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
				if (@$_GET["nu_versao"] <> "") {
					$GLOBALS["indicadorvalor"]->nu_versao->setQueryStringValue($_GET["nu_versao"]);
					$this->nu_versao->setQueryStringValue($GLOBALS["indicadorvalor"]->nu_versao->QueryStringValue);
					$this->nu_versao->setSessionValue($this->nu_versao->QueryStringValue);
					if (!is_numeric($GLOBALS["indicadorvalor"]->nu_versao->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "indicadorvalor") {
				if ($this->nu_indicador->QueryStringValue == "") $this->nu_indicador->setSessionValue("");
				if ($this->nu_versao->QueryStringValue == "") $this->nu_versao->setSessionValue("");
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
			if (in_array("indicador", $DetailTblVar)) {
				if (!isset($GLOBALS["indicador_grid"]))
					$GLOBALS["indicador_grid"] = new cindicador_grid;
				if ($GLOBALS["indicador_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["indicador_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["indicador_grid"]->CurrentMode = "add";
					$GLOBALS["indicador_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["indicador_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["indicador_grid"]->setStartRecordNumber(1);
					$GLOBALS["indicador_grid"]->nu_indicador->FldIsDetailKey = TRUE;
					$GLOBALS["indicador_grid"]->nu_indicador->CurrentValue = $this->nu_indicador->CurrentValue;
					$GLOBALS["indicador_grid"]->nu_indicador->setSessionValue($GLOBALS["indicador_grid"]->nu_indicador->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "indicadorversaolist.php", $this->TableVar);
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
if (!isset($indicadorversao_add)) $indicadorversao_add = new cindicadorversao_add();

// Page init
$indicadorversao_add->Page_Init();

// Page main
$indicadorversao_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$indicadorversao_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var indicadorversao_add = new ew_Page("indicadorversao_add");
indicadorversao_add.PageID = "add"; // Page ID
var EW_PAGE_ID = indicadorversao_add.PageID; // For backward compatibility

// Form object
var findicadorversaoadd = new ew_Form("findicadorversaoadd");

// Validate form
findicadorversaoadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_indicador");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($indicadorversao->nu_indicador->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_versao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($indicadorversao->nu_versao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_versao");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($indicadorversao->nu_versao->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ic_periodicidadeGeracao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($indicadorversao->ic_periodicidadeGeracao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_reponsavelColetaCtrl");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($indicadorversao->ic_reponsavelColetaCtrl->FldCaption()) ?>");

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
findicadorversaoadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
findicadorversaoadd.ValidateRequired = true;
<?php } else { ?>
findicadorversaoadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
findicadorversaoadd.Lists["x_nu_indicador"] = {"LinkField":"x_nu_indicador","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_indicador","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $indicadorversao_add->ShowPageHeader(); ?>
<?php
$indicadorversao_add->ShowMessage();
?>
<form name="findicadorversaoadd" id="findicadorversaoadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="indicadorversao">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_indicadorversaoadd" class="table table-bordered table-striped">
<?php if ($indicadorversao->nu_indicador->Visible) { // nu_indicador ?>
	<tr id="r_nu_indicador">
		<td><span id="elh_indicadorversao_nu_indicador"><?php echo $indicadorversao->nu_indicador->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $indicadorversao->nu_indicador->CellAttributes() ?>>
<?php if ($indicadorversao->nu_indicador->getSessionValue() <> "") { ?>
<span<?php echo $indicadorversao->nu_indicador->ViewAttributes() ?>>
<?php echo $indicadorversao->nu_indicador->ViewValue ?></span>
<input type="hidden" id="x_nu_indicador" name="x_nu_indicador" value="<?php echo ew_HtmlEncode($indicadorversao->nu_indicador->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_nu_indicador" id="x_nu_indicador" name="x_nu_indicador"<?php echo $indicadorversao->nu_indicador->EditAttributes() ?>>
<?php
if (is_array($indicadorversao->nu_indicador->EditValue)) {
	$arwrk = $indicadorversao->nu_indicador->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($indicadorversao->nu_indicador->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
findicadorversaoadd.Lists["x_nu_indicador"].Options = <?php echo (is_array($indicadorversao->nu_indicador->EditValue)) ? ew_ArrayToJson($indicadorversao->nu_indicador->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<?php echo $indicadorversao->nu_indicador->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($indicadorversao->nu_versao->Visible) { // nu_versao ?>
	<tr id="r_nu_versao">
		<td><span id="elh_indicadorversao_nu_versao"><?php echo $indicadorversao->nu_versao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $indicadorversao->nu_versao->CellAttributes() ?>>
<?php if ($indicadorversao->nu_versao->getSessionValue() <> "") { ?>
<span<?php echo $indicadorversao->nu_versao->ViewAttributes() ?>>
<?php echo $indicadorversao->nu_versao->ViewValue ?></span>
<input type="hidden" id="x_nu_versao" name="x_nu_versao" value="<?php echo ew_HtmlEncode($indicadorversao->nu_versao->CurrentValue) ?>">
<?php } else { ?>
<input type="text" data-field="x_nu_versao" name="x_nu_versao" id="x_nu_versao" size="30" placeholder="<?php echo $indicadorversao->nu_versao->PlaceHolder ?>" value="<?php echo $indicadorversao->nu_versao->EditValue ?>"<?php echo $indicadorversao->nu_versao->EditAttributes() ?>>
<?php } ?>
<?php echo $indicadorversao->nu_versao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($indicadorversao->ic_periodicidadeGeracao->Visible) { // ic_periodicidadeGeracao ?>
	<tr id="r_ic_periodicidadeGeracao">
		<td><span id="elh_indicadorversao_ic_periodicidadeGeracao"><?php echo $indicadorversao->ic_periodicidadeGeracao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $indicadorversao->ic_periodicidadeGeracao->CellAttributes() ?>>
<span id="el_indicadorversao_ic_periodicidadeGeracao" class="control-group">
<select data-field="x_ic_periodicidadeGeracao" id="x_ic_periodicidadeGeracao" name="x_ic_periodicidadeGeracao"<?php echo $indicadorversao->ic_periodicidadeGeracao->EditAttributes() ?>>
<?php
if (is_array($indicadorversao->ic_periodicidadeGeracao->EditValue)) {
	$arwrk = $indicadorversao->ic_periodicidadeGeracao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($indicadorversao->ic_periodicidadeGeracao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $indicadorversao->ic_periodicidadeGeracao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($indicadorversao->ds_origemIndicador->Visible) { // ds_origemIndicador ?>
	<tr id="r_ds_origemIndicador">
		<td><span id="elh_indicadorversao_ds_origemIndicador"><?php echo $indicadorversao->ds_origemIndicador->FldCaption() ?></span></td>
		<td<?php echo $indicadorversao->ds_origemIndicador->CellAttributes() ?>>
<span id="el_indicadorversao_ds_origemIndicador" class="control-group">
<textarea data-field="x_ds_origemIndicador" name="x_ds_origemIndicador" id="x_ds_origemIndicador" cols="35" rows="4" placeholder="<?php echo $indicadorversao->ds_origemIndicador->PlaceHolder ?>"<?php echo $indicadorversao->ds_origemIndicador->EditAttributes() ?>><?php echo $indicadorversao->ds_origemIndicador->EditValue ?></textarea>
</span>
<?php echo $indicadorversao->ds_origemIndicador->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($indicadorversao->ic_reponsavelColetaCtrl->Visible) { // ic_reponsavelColetaCtrl ?>
	<tr id="r_ic_reponsavelColetaCtrl">
		<td><span id="elh_indicadorversao_ic_reponsavelColetaCtrl"><?php echo $indicadorversao->ic_reponsavelColetaCtrl->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $indicadorversao->ic_reponsavelColetaCtrl->CellAttributes() ?>>
<span id="el_indicadorversao_ic_reponsavelColetaCtrl" class="control-group">
<div id="tp_x_ic_reponsavelColetaCtrl" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_reponsavelColetaCtrl" id="x_ic_reponsavelColetaCtrl" value="{value}"<?php echo $indicadorversao->ic_reponsavelColetaCtrl->EditAttributes() ?>></div>
<div id="dsl_x_ic_reponsavelColetaCtrl" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $indicadorversao->ic_reponsavelColetaCtrl->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($indicadorversao->ic_reponsavelColetaCtrl->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_reponsavelColetaCtrl" name="x_ic_reponsavelColetaCtrl" id="x_ic_reponsavelColetaCtrl_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $indicadorversao->ic_reponsavelColetaCtrl->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $indicadorversao->ic_reponsavelColetaCtrl->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($indicadorversao->ds_codigoSql->Visible) { // ds_codigoSql ?>
	<tr id="r_ds_codigoSql">
		<td><span id="elh_indicadorversao_ds_codigoSql"><?php echo $indicadorversao->ds_codigoSql->FldCaption() ?></span></td>
		<td<?php echo $indicadorversao->ds_codigoSql->CellAttributes() ?>>
<span id="el_indicadorversao_ds_codigoSql" class="control-group">
<textarea data-field="x_ds_codigoSql" name="x_ds_codigoSql" id="x_ds_codigoSql" cols="35" rows="4" placeholder="<?php echo $indicadorversao->ds_codigoSql->PlaceHolder ?>"<?php echo $indicadorversao->ds_codigoSql->EditAttributes() ?>><?php echo $indicadorversao->ds_codigoSql->EditValue ?></textarea>
</span>
<?php echo $indicadorversao->ds_codigoSql->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<?php
	if (in_array("indicador", explode(",", $indicadorversao->getCurrentDetailTable())) && $indicador->DetailAdd) {
?>
<?php include_once "indicadorgrid.php" ?>
<?php } ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
findicadorversaoadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$indicadorversao_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$indicadorversao_add->Page_Terminate();
?>
