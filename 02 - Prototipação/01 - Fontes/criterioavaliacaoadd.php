<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "criterioavaliacaoinfo.php" ?>
<?php include_once "criterioinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$criterioavaliacao_add = NULL; // Initialize page object first

class ccriterioavaliacao_add extends ccriterioavaliacao {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'criterioavaliacao';

	// Page object name
	var $PageObjName = 'criterioavaliacao_add';

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

		// Table object (criterioavaliacao)
		if (!isset($GLOBALS["criterioavaliacao"])) {
			$GLOBALS["criterioavaliacao"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["criterioavaliacao"];
		}

		// Table object (criterio)
		if (!isset($GLOBALS['criterio'])) $GLOBALS['criterio'] = new ccriterio();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'criterioavaliacao', TRUE);

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
			$this->Page_Terminate("criterioavaliacaolist.php");
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
			if (@$_GET["nu_alternativaAvaliacao"] != "") {
				$this->nu_alternativaAvaliacao->setQueryStringValue($_GET["nu_alternativaAvaliacao"]);
				$this->setKey("nu_alternativaAvaliacao", $this->nu_alternativaAvaliacao->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_alternativaAvaliacao", ""); // Clear key
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
					$this->Page_Terminate("criterioavaliacaolist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "criterioavaliacaoview.php")
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
		$this->nu_criterioPrioridade->CurrentValue = NULL;
		$this->nu_criterioPrioridade->OldValue = $this->nu_criterioPrioridade->CurrentValue;
		$this->no_alternativa->CurrentValue = NULL;
		$this->no_alternativa->OldValue = $this->no_alternativa->CurrentValue;
		$this->vr_alternativa->CurrentValue = NULL;
		$this->vr_alternativa->OldValue = $this->vr_alternativa->CurrentValue;
		$this->dt_manutencao->CurrentValue = NULL;
		$this->dt_manutencao->OldValue = $this->dt_manutencao->CurrentValue;
		$this->nu_usuarioAlterou->CurrentValue = NULL;
		$this->nu_usuarioAlterou->OldValue = $this->nu_usuarioAlterou->CurrentValue;
		$this->ic_ativo->CurrentValue = "S";
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_criterioPrioridade->FldIsDetailKey) {
			$this->nu_criterioPrioridade->setFormValue($objForm->GetValue("x_nu_criterioPrioridade"));
		}
		if (!$this->no_alternativa->FldIsDetailKey) {
			$this->no_alternativa->setFormValue($objForm->GetValue("x_no_alternativa"));
		}
		if (!$this->vr_alternativa->FldIsDetailKey) {
			$this->vr_alternativa->setFormValue($objForm->GetValue("x_vr_alternativa"));
		}
		if (!$this->dt_manutencao->FldIsDetailKey) {
			$this->dt_manutencao->setFormValue($objForm->GetValue("x_dt_manutencao"));
			$this->dt_manutencao->CurrentValue = ew_UnFormatDateTime($this->dt_manutencao->CurrentValue, 7);
		}
		if (!$this->nu_usuarioAlterou->FldIsDetailKey) {
			$this->nu_usuarioAlterou->setFormValue($objForm->GetValue("x_nu_usuarioAlterou"));
		}
		if (!$this->ic_ativo->FldIsDetailKey) {
			$this->ic_ativo->setFormValue($objForm->GetValue("x_ic_ativo"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->nu_criterioPrioridade->CurrentValue = $this->nu_criterioPrioridade->FormValue;
		$this->no_alternativa->CurrentValue = $this->no_alternativa->FormValue;
		$this->vr_alternativa->CurrentValue = $this->vr_alternativa->FormValue;
		$this->dt_manutencao->CurrentValue = $this->dt_manutencao->FormValue;
		$this->dt_manutencao->CurrentValue = ew_UnFormatDateTime($this->dt_manutencao->CurrentValue, 7);
		$this->nu_usuarioAlterou->CurrentValue = $this->nu_usuarioAlterou->FormValue;
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
		$this->nu_alternativaAvaliacao->setDbValue($rs->fields('nu_alternativaAvaliacao'));
		$this->nu_criterioPrioridade->setDbValue($rs->fields('nu_criterioPrioridade'));
		$this->no_alternativa->setDbValue($rs->fields('no_alternativa'));
		$this->vr_alternativa->setDbValue($rs->fields('vr_alternativa'));
		$this->dt_manutencao->setDbValue($rs->fields('dt_manutencao'));
		$this->nu_usuarioAlterou->setDbValue($rs->fields('nu_usuarioAlterou'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_alternativaAvaliacao->DbValue = $row['nu_alternativaAvaliacao'];
		$this->nu_criterioPrioridade->DbValue = $row['nu_criterioPrioridade'];
		$this->no_alternativa->DbValue = $row['no_alternativa'];
		$this->vr_alternativa->DbValue = $row['vr_alternativa'];
		$this->dt_manutencao->DbValue = $row['dt_manutencao'];
		$this->nu_usuarioAlterou->DbValue = $row['nu_usuarioAlterou'];
		$this->ic_ativo->DbValue = $row['ic_ativo'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_alternativaAvaliacao")) <> "")
			$this->nu_alternativaAvaliacao->CurrentValue = $this->getKey("nu_alternativaAvaliacao"); // nu_alternativaAvaliacao
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

		if ($this->vr_alternativa->FormValue == $this->vr_alternativa->CurrentValue && is_numeric(ew_StrToFloat($this->vr_alternativa->CurrentValue)))
			$this->vr_alternativa->CurrentValue = ew_StrToFloat($this->vr_alternativa->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_alternativaAvaliacao
		// nu_criterioPrioridade
		// no_alternativa
		// vr_alternativa
		// dt_manutencao
		// nu_usuarioAlterou
		// ic_ativo

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_alternativaAvaliacao
			$this->nu_alternativaAvaliacao->ViewValue = $this->nu_alternativaAvaliacao->CurrentValue;
			$this->nu_alternativaAvaliacao->ViewCustomAttributes = "";

			// nu_criterioPrioridade
			if (strval($this->nu_criterioPrioridade->CurrentValue) <> "") {
				$sFilterWrk = "[nu_criterioPrioridade]" . ew_SearchString("=", $this->nu_criterioPrioridade->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_criterioPrioridade], [no_criterioPrioridade] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[criterio]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_criterioPrioridade, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_criterioPrioridade] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_criterioPrioridade->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_criterioPrioridade->ViewValue = $this->nu_criterioPrioridade->CurrentValue;
				}
			} else {
				$this->nu_criterioPrioridade->ViewValue = NULL;
			}
			$this->nu_criterioPrioridade->ViewCustomAttributes = "";

			// no_alternativa
			$this->no_alternativa->ViewValue = $this->no_alternativa->CurrentValue;
			$this->no_alternativa->ViewCustomAttributes = "";

			// vr_alternativa
			$this->vr_alternativa->ViewValue = $this->vr_alternativa->CurrentValue;
			$this->vr_alternativa->ViewValue = ew_FormatNumber($this->vr_alternativa->ViewValue, 2, -2, -2, -2);
			$this->vr_alternativa->ViewCustomAttributes = "";

			// dt_manutencao
			$this->dt_manutencao->ViewValue = $this->dt_manutencao->CurrentValue;
			$this->dt_manutencao->ViewValue = ew_FormatDateTime($this->dt_manutencao->ViewValue, 7);
			$this->dt_manutencao->ViewCustomAttributes = "";

			// nu_usuarioAlterou
			$this->nu_usuarioAlterou->ViewValue = $this->nu_usuarioAlterou->CurrentValue;
			$this->nu_usuarioAlterou->ViewCustomAttributes = "";

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

			// nu_criterioPrioridade
			$this->nu_criterioPrioridade->LinkCustomAttributes = "";
			$this->nu_criterioPrioridade->HrefValue = "";
			$this->nu_criterioPrioridade->TooltipValue = "";

			// no_alternativa
			$this->no_alternativa->LinkCustomAttributes = "";
			$this->no_alternativa->HrefValue = "";
			$this->no_alternativa->TooltipValue = "";

			// vr_alternativa
			$this->vr_alternativa->LinkCustomAttributes = "";
			$this->vr_alternativa->HrefValue = "";
			$this->vr_alternativa->TooltipValue = "";

			// dt_manutencao
			$this->dt_manutencao->LinkCustomAttributes = "";
			$this->dt_manutencao->HrefValue = "";
			$this->dt_manutencao->TooltipValue = "";

			// nu_usuarioAlterou
			$this->nu_usuarioAlterou->LinkCustomAttributes = "";
			$this->nu_usuarioAlterou->HrefValue = "";
			$this->nu_usuarioAlterou->TooltipValue = "";

			// ic_ativo
			$this->ic_ativo->LinkCustomAttributes = "";
			$this->ic_ativo->HrefValue = "";
			$this->ic_ativo->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nu_criterioPrioridade
			$this->nu_criterioPrioridade->EditCustomAttributes = "";
			if ($this->nu_criterioPrioridade->getSessionValue() <> "") {
				$this->nu_criterioPrioridade->CurrentValue = $this->nu_criterioPrioridade->getSessionValue();
			if (strval($this->nu_criterioPrioridade->CurrentValue) <> "") {
				$sFilterWrk = "[nu_criterioPrioridade]" . ew_SearchString("=", $this->nu_criterioPrioridade->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_criterioPrioridade], [no_criterioPrioridade] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[criterio]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_criterioPrioridade, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_criterioPrioridade] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_criterioPrioridade->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_criterioPrioridade->ViewValue = $this->nu_criterioPrioridade->CurrentValue;
				}
			} else {
				$this->nu_criterioPrioridade->ViewValue = NULL;
			}
			$this->nu_criterioPrioridade->ViewCustomAttributes = "";
			} else {
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_criterioPrioridade], [no_criterioPrioridade] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[criterio]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_criterioPrioridade, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_criterioPrioridade] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_criterioPrioridade->EditValue = $arwrk;
			}

			// no_alternativa
			$this->no_alternativa->EditCustomAttributes = "";
			$this->no_alternativa->EditValue = ew_HtmlEncode($this->no_alternativa->CurrentValue);
			$this->no_alternativa->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_alternativa->FldCaption()));

			// vr_alternativa
			$this->vr_alternativa->EditCustomAttributes = "";
			$this->vr_alternativa->EditValue = ew_HtmlEncode($this->vr_alternativa->CurrentValue);
			$this->vr_alternativa->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_alternativa->FldCaption()));
			if (strval($this->vr_alternativa->EditValue) <> "" && is_numeric($this->vr_alternativa->EditValue)) $this->vr_alternativa->EditValue = ew_FormatNumber($this->vr_alternativa->EditValue, -2, -2, -2, -2);

			// dt_manutencao
			// nu_usuarioAlterou
			// ic_ativo

			$this->ic_ativo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_ativo->FldTagValue(1), $this->ic_ativo->FldTagCaption(1) <> "" ? $this->ic_ativo->FldTagCaption(1) : $this->ic_ativo->FldTagValue(1));
			$arwrk[] = array($this->ic_ativo->FldTagValue(2), $this->ic_ativo->FldTagCaption(2) <> "" ? $this->ic_ativo->FldTagCaption(2) : $this->ic_ativo->FldTagValue(2));
			$this->ic_ativo->EditValue = $arwrk;

			// Edit refer script
			// nu_criterioPrioridade

			$this->nu_criterioPrioridade->HrefValue = "";

			// no_alternativa
			$this->no_alternativa->HrefValue = "";

			// vr_alternativa
			$this->vr_alternativa->HrefValue = "";

			// dt_manutencao
			$this->dt_manutencao->HrefValue = "";

			// nu_usuarioAlterou
			$this->nu_usuarioAlterou->HrefValue = "";

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
		if (!$this->nu_criterioPrioridade->FldIsDetailKey && !is_null($this->nu_criterioPrioridade->FormValue) && $this->nu_criterioPrioridade->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_criterioPrioridade->FldCaption());
		}
		if (!$this->no_alternativa->FldIsDetailKey && !is_null($this->no_alternativa->FormValue) && $this->no_alternativa->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_alternativa->FldCaption());
		}
		if (!$this->vr_alternativa->FldIsDetailKey && !is_null($this->vr_alternativa->FormValue) && $this->vr_alternativa->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->vr_alternativa->FldCaption());
		}
		if (!ew_CheckNumber($this->vr_alternativa->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_alternativa->FldErrMsg());
		}
		if ($this->ic_ativo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_ativo->FldCaption());
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

		// nu_criterioPrioridade
		$this->nu_criterioPrioridade->SetDbValueDef($rsnew, $this->nu_criterioPrioridade->CurrentValue, 0, FALSE);

		// no_alternativa
		$this->no_alternativa->SetDbValueDef($rsnew, $this->no_alternativa->CurrentValue, "", FALSE);

		// vr_alternativa
		$this->vr_alternativa->SetDbValueDef($rsnew, $this->vr_alternativa->CurrentValue, 0, FALSE);

		// dt_manutencao
		$this->dt_manutencao->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
		$rsnew['dt_manutencao'] = &$this->dt_manutencao->DbValue;

		// nu_usuarioAlterou
		$this->nu_usuarioAlterou->SetDbValueDef($rsnew, CurrentUserID(), NULL);
		$rsnew['nu_usuarioAlterou'] = &$this->nu_usuarioAlterou->DbValue;

		// ic_ativo
		$this->ic_ativo->SetDbValueDef($rsnew, $this->ic_ativo->CurrentValue, "", FALSE);

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
			$this->nu_alternativaAvaliacao->setDbValue($conn->Insert_ID());
			$rsnew['nu_alternativaAvaliacao'] = $this->nu_alternativaAvaliacao->DbValue;
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
			if ($sMasterTblVar == "criterio") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_criterioPrioridade"] <> "") {
					$GLOBALS["criterio"]->nu_criterioPrioridade->setQueryStringValue($_GET["nu_criterioPrioridade"]);
					$this->nu_criterioPrioridade->setQueryStringValue($GLOBALS["criterio"]->nu_criterioPrioridade->QueryStringValue);
					$this->nu_criterioPrioridade->setSessionValue($this->nu_criterioPrioridade->QueryStringValue);
					if (!is_numeric($GLOBALS["criterio"]->nu_criterioPrioridade->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "criterio") {
				if ($this->nu_criterioPrioridade->QueryStringValue == "") $this->nu_criterioPrioridade->setSessionValue("");
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "criterioavaliacaolist.php", $this->TableVar);
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
if (!isset($criterioavaliacao_add)) $criterioavaliacao_add = new ccriterioavaliacao_add();

// Page init
$criterioavaliacao_add->Page_Init();

// Page main
$criterioavaliacao_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$criterioavaliacao_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var criterioavaliacao_add = new ew_Page("criterioavaliacao_add");
criterioavaliacao_add.PageID = "add"; // Page ID
var EW_PAGE_ID = criterioavaliacao_add.PageID; // For backward compatibility

// Form object
var fcriterioavaliacaoadd = new ew_Form("fcriterioavaliacaoadd");

// Validate form
fcriterioavaliacaoadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_criterioPrioridade");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($criterioavaliacao->nu_criterioPrioridade->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no_alternativa");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($criterioavaliacao->no_alternativa->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_alternativa");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($criterioavaliacao->vr_alternativa->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_alternativa");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($criterioavaliacao->vr_alternativa->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ic_ativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($criterioavaliacao->ic_ativo->FldCaption()) ?>");

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
fcriterioavaliacaoadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcriterioavaliacaoadd.ValidateRequired = true;
<?php } else { ?>
fcriterioavaliacaoadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcriterioavaliacaoadd.Lists["x_nu_criterioPrioridade"] = {"LinkField":"x_nu_criterioPrioridade","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_criterioPrioridade","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $criterioavaliacao_add->ShowPageHeader(); ?>
<?php
$criterioavaliacao_add->ShowMessage();
?>
<form name="fcriterioavaliacaoadd" id="fcriterioavaliacaoadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="criterioavaliacao">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_criterioavaliacaoadd" class="table table-bordered table-striped">
<?php if ($criterioavaliacao->nu_criterioPrioridade->Visible) { // nu_criterioPrioridade ?>
	<tr id="r_nu_criterioPrioridade">
		<td><span id="elh_criterioavaliacao_nu_criterioPrioridade"><?php echo $criterioavaliacao->nu_criterioPrioridade->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $criterioavaliacao->nu_criterioPrioridade->CellAttributes() ?>>
<?php if ($criterioavaliacao->nu_criterioPrioridade->getSessionValue() <> "") { ?>
<span<?php echo $criterioavaliacao->nu_criterioPrioridade->ViewAttributes() ?>>
<?php echo $criterioavaliacao->nu_criterioPrioridade->ViewValue ?></span>
<input type="hidden" id="x_nu_criterioPrioridade" name="x_nu_criterioPrioridade" value="<?php echo ew_HtmlEncode($criterioavaliacao->nu_criterioPrioridade->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_nu_criterioPrioridade" id="x_nu_criterioPrioridade" name="x_nu_criterioPrioridade"<?php echo $criterioavaliacao->nu_criterioPrioridade->EditAttributes() ?>>
<?php
if (is_array($criterioavaliacao->nu_criterioPrioridade->EditValue)) {
	$arwrk = $criterioavaliacao->nu_criterioPrioridade->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($criterioavaliacao->nu_criterioPrioridade->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fcriterioavaliacaoadd.Lists["x_nu_criterioPrioridade"].Options = <?php echo (is_array($criterioavaliacao->nu_criterioPrioridade->EditValue)) ? ew_ArrayToJson($criterioavaliacao->nu_criterioPrioridade->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<?php echo $criterioavaliacao->nu_criterioPrioridade->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($criterioavaliacao->no_alternativa->Visible) { // no_alternativa ?>
	<tr id="r_no_alternativa">
		<td><span id="elh_criterioavaliacao_no_alternativa"><?php echo $criterioavaliacao->no_alternativa->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $criterioavaliacao->no_alternativa->CellAttributes() ?>>
<span id="el_criterioavaliacao_no_alternativa" class="control-group">
<input type="text" data-field="x_no_alternativa" name="x_no_alternativa" id="x_no_alternativa" size="30" maxlength="50" placeholder="<?php echo $criterioavaliacao->no_alternativa->PlaceHolder ?>" value="<?php echo $criterioavaliacao->no_alternativa->EditValue ?>"<?php echo $criterioavaliacao->no_alternativa->EditAttributes() ?>>
</span>
<?php echo $criterioavaliacao->no_alternativa->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($criterioavaliacao->vr_alternativa->Visible) { // vr_alternativa ?>
	<tr id="r_vr_alternativa">
		<td><span id="elh_criterioavaliacao_vr_alternativa"><?php echo $criterioavaliacao->vr_alternativa->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $criterioavaliacao->vr_alternativa->CellAttributes() ?>>
<span id="el_criterioavaliacao_vr_alternativa" class="control-group">
<input type="text" data-field="x_vr_alternativa" name="x_vr_alternativa" id="x_vr_alternativa" size="30" placeholder="<?php echo $criterioavaliacao->vr_alternativa->PlaceHolder ?>" value="<?php echo $criterioavaliacao->vr_alternativa->EditValue ?>"<?php echo $criterioavaliacao->vr_alternativa->EditAttributes() ?>>
</span>
<?php echo $criterioavaliacao->vr_alternativa->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($criterioavaliacao->ic_ativo->Visible) { // ic_ativo ?>
	<tr id="r_ic_ativo">
		<td><span id="elh_criterioavaliacao_ic_ativo"><?php echo $criterioavaliacao->ic_ativo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $criterioavaliacao->ic_ativo->CellAttributes() ?>>
<span id="el_criterioavaliacao_ic_ativo" class="control-group">
<div id="tp_x_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_ativo" id="x_ic_ativo" value="{value}"<?php echo $criterioavaliacao->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $criterioavaliacao->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($criterioavaliacao->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x_ic_ativo" id="x_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $criterioavaliacao->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $criterioavaliacao->ic_ativo->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fcriterioavaliacaoadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$criterioavaliacao_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$criterioavaliacao_add->Page_Terminate();
?>
