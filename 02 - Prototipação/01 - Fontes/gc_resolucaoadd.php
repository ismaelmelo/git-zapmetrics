<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "gc_resolucaoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$gc_resolucao_add = NULL; // Initialize page object first

class cgc_resolucao_add extends cgc_resolucao {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'gc_resolucao';

	// Page object name
	var $PageObjName = 'gc_resolucao_add';

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

		// Table object (gc_resolucao)
		if (!isset($GLOBALS["gc_resolucao"])) {
			$GLOBALS["gc_resolucao"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["gc_resolucao"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'gc_resolucao', TRUE);

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
			$this->Page_Terminate("gc_resolucaolist.php");
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
			if (@$_GET["nu_resolucao"] != "") {
				$this->nu_resolucao->setQueryStringValue($_GET["nu_resolucao"]);
				$this->setKey("nu_resolucao", $this->nu_resolucao->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_resolucao", ""); // Clear key
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
					$this->Page_Terminate("gc_resolucaolist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "gc_resolucaoview.php")
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
		$this->im_anexo->Upload->Index = $objForm->Index;
		if ($this->im_anexo->Upload->UploadFile()) {

			// No action required
		} else {
			echo $this->im_anexo->Upload->Message;
			$this->Page_Terminate();
			exit();
		}
		$this->im_anexo->CurrentValue = $this->im_anexo->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->nu_grupoOuComite->CurrentValue = NULL;
		$this->nu_grupoOuComite->OldValue = $this->nu_grupoOuComite->CurrentValue;
		$this->ds_resolucao->CurrentValue = NULL;
		$this->ds_resolucao->OldValue = $this->ds_resolucao->CurrentValue;
		$this->no_local->CurrentValue = NULL;
		$this->no_local->OldValue = $this->no_local->CurrentValue;
		$this->im_anexo->Upload->DbValue = NULL;
		$this->im_anexo->OldValue = $this->im_anexo->Upload->DbValue;
		$this->im_anexo->CurrentValue = NULL; // Clear file related field
		$this->dt_publicacao->CurrentValue = NULL;
		$this->dt_publicacao->OldValue = $this->dt_publicacao->CurrentValue;
		$this->ic_situacao->CurrentValue = "E";
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
		if (!$this->nu_grupoOuComite->FldIsDetailKey) {
			$this->nu_grupoOuComite->setFormValue($objForm->GetValue("x_nu_grupoOuComite"));
		}
		if (!$this->ds_resolucao->FldIsDetailKey) {
			$this->ds_resolucao->setFormValue($objForm->GetValue("x_ds_resolucao"));
		}
		if (!$this->no_local->FldIsDetailKey) {
			$this->no_local->setFormValue($objForm->GetValue("x_no_local"));
		}
		if (!$this->dt_publicacao->FldIsDetailKey) {
			$this->dt_publicacao->setFormValue($objForm->GetValue("x_dt_publicacao"));
			$this->dt_publicacao->CurrentValue = ew_UnFormatDateTime($this->dt_publicacao->CurrentValue, 7);
		}
		if (!$this->ic_situacao->FldIsDetailKey) {
			$this->ic_situacao->setFormValue($objForm->GetValue("x_ic_situacao"));
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
		$this->nu_grupoOuComite->CurrentValue = $this->nu_grupoOuComite->FormValue;
		$this->ds_resolucao->CurrentValue = $this->ds_resolucao->FormValue;
		$this->no_local->CurrentValue = $this->no_local->FormValue;
		$this->dt_publicacao->CurrentValue = $this->dt_publicacao->FormValue;
		$this->dt_publicacao->CurrentValue = ew_UnFormatDateTime($this->dt_publicacao->CurrentValue, 7);
		$this->ic_situacao->CurrentValue = $this->ic_situacao->FormValue;
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
		$this->nu_resolucao->setDbValue($rs->fields('nu_resolucao'));
		$this->nu_grupoOuComite->setDbValue($rs->fields('nu_grupoOuComite'));
		$this->ds_resolucao->setDbValue($rs->fields('ds_resolucao'));
		$this->no_local->setDbValue($rs->fields('no_local'));
		$this->im_anexo->Upload->DbValue = $rs->fields('im_anexo');
		$this->dt_publicacao->setDbValue($rs->fields('dt_publicacao'));
		$this->ic_situacao->setDbValue($rs->fields('ic_situacao'));
		$this->nu_usuario->setDbValue($rs->fields('nu_usuario'));
		$this->ts_datahora->setDbValue($rs->fields('ts_datahora'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_resolucao->DbValue = $row['nu_resolucao'];
		$this->nu_grupoOuComite->DbValue = $row['nu_grupoOuComite'];
		$this->ds_resolucao->DbValue = $row['ds_resolucao'];
		$this->no_local->DbValue = $row['no_local'];
		$this->im_anexo->Upload->DbValue = $row['im_anexo'];
		$this->dt_publicacao->DbValue = $row['dt_publicacao'];
		$this->ic_situacao->DbValue = $row['ic_situacao'];
		$this->nu_usuario->DbValue = $row['nu_usuario'];
		$this->ts_datahora->DbValue = $row['ts_datahora'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_resolucao")) <> "")
			$this->nu_resolucao->CurrentValue = $this->getKey("nu_resolucao"); // nu_resolucao
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
		// nu_resolucao
		// nu_grupoOuComite
		// ds_resolucao
		// no_local
		// im_anexo
		// dt_publicacao
		// ic_situacao
		// nu_usuario
		// ts_datahora

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_resolucao
			$this->nu_resolucao->ViewValue = $this->nu_resolucao->CurrentValue;
			$this->nu_resolucao->ViewCustomAttributes = "";

			// nu_grupoOuComite
			if (strval($this->nu_grupoOuComite->CurrentValue) <> "") {
				$sFilterWrk = "[nu_gpComite]" . ew_SearchString("=", $this->nu_grupoOuComite->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_gpComite], [no_gpComite] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[gpcomite]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_grupoOuComite, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_grupoOuComite->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_grupoOuComite->ViewValue = $this->nu_grupoOuComite->CurrentValue;
				}
			} else {
				$this->nu_grupoOuComite->ViewValue = NULL;
			}
			$this->nu_grupoOuComite->ViewCustomAttributes = "";

			// ds_resolucao
			$this->ds_resolucao->ViewValue = $this->ds_resolucao->CurrentValue;
			$this->ds_resolucao->ViewCustomAttributes = "";

			// no_local
			$this->no_local->ViewValue = $this->no_local->CurrentValue;
			$this->no_local->ViewCustomAttributes = "";

			// im_anexo
			$this->im_anexo->UploadPath = "arquivos/resolucoes";
			if (!ew_Empty($this->im_anexo->Upload->DbValue)) {
				$this->im_anexo->ViewValue = $this->im_anexo->Upload->DbValue;
			} else {
				$this->im_anexo->ViewValue = "";
			}
			$this->im_anexo->ViewCustomAttributes = "";

			// dt_publicacao
			$this->dt_publicacao->ViewValue = $this->dt_publicacao->CurrentValue;
			$this->dt_publicacao->ViewValue = ew_FormatDateTime($this->dt_publicacao->ViewValue, 7);
			$this->dt_publicacao->ViewCustomAttributes = "";

			// ic_situacao
			if (strval($this->ic_situacao->CurrentValue) <> "") {
				switch ($this->ic_situacao->CurrentValue) {
					case $this->ic_situacao->FldTagValue(1):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(1) <> "" ? $this->ic_situacao->FldTagCaption(1) : $this->ic_situacao->CurrentValue;
						break;
					case $this->ic_situacao->FldTagValue(2):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(2) <> "" ? $this->ic_situacao->FldTagCaption(2) : $this->ic_situacao->CurrentValue;
						break;
					case $this->ic_situacao->FldTagValue(3):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(3) <> "" ? $this->ic_situacao->FldTagCaption(3) : $this->ic_situacao->CurrentValue;
						break;
					default:
						$this->ic_situacao->ViewValue = $this->ic_situacao->CurrentValue;
				}
			} else {
				$this->ic_situacao->ViewValue = NULL;
			}
			$this->ic_situacao->ViewCustomAttributes = "";

			// nu_usuario
			$this->nu_usuario->ViewValue = $this->nu_usuario->CurrentValue;
			$this->nu_usuario->ViewCustomAttributes = "";

			// ts_datahora
			$this->ts_datahora->ViewValue = $this->ts_datahora->CurrentValue;
			$this->ts_datahora->ViewValue = ew_FormatDateTime($this->ts_datahora->ViewValue, 7);
			$this->ts_datahora->ViewCustomAttributes = "";

			// nu_grupoOuComite
			$this->nu_grupoOuComite->LinkCustomAttributes = "";
			$this->nu_grupoOuComite->HrefValue = "";
			$this->nu_grupoOuComite->TooltipValue = "";

			// ds_resolucao
			$this->ds_resolucao->LinkCustomAttributes = "";
			$this->ds_resolucao->HrefValue = "";
			$this->ds_resolucao->TooltipValue = "";

			// no_local
			$this->no_local->LinkCustomAttributes = "";
			$this->no_local->HrefValue = "";
			$this->no_local->TooltipValue = "";

			// im_anexo
			$this->im_anexo->LinkCustomAttributes = "";
			$this->im_anexo->HrefValue = "";
			$this->im_anexo->HrefValue2 = $this->im_anexo->UploadPath . $this->im_anexo->Upload->DbValue;
			$this->im_anexo->TooltipValue = "";

			// dt_publicacao
			$this->dt_publicacao->LinkCustomAttributes = "";
			$this->dt_publicacao->HrefValue = "";
			$this->dt_publicacao->TooltipValue = "";

			// ic_situacao
			$this->ic_situacao->LinkCustomAttributes = "";
			$this->ic_situacao->HrefValue = "";
			$this->ic_situacao->TooltipValue = "";

			// nu_usuario
			$this->nu_usuario->LinkCustomAttributes = "";
			$this->nu_usuario->HrefValue = "";
			$this->nu_usuario->TooltipValue = "";

			// ts_datahora
			$this->ts_datahora->LinkCustomAttributes = "";
			$this->ts_datahora->HrefValue = "";
			$this->ts_datahora->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nu_grupoOuComite
			$this->nu_grupoOuComite->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_gpComite], [no_gpComite] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[gpcomite]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_grupoOuComite, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_grupoOuComite->EditValue = $arwrk;

			// ds_resolucao
			$this->ds_resolucao->EditCustomAttributes = "";
			$this->ds_resolucao->EditValue = $this->ds_resolucao->CurrentValue;
			$this->ds_resolucao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_resolucao->FldCaption()));

			// no_local
			$this->no_local->EditCustomAttributes = "";
			$this->no_local->EditValue = ew_HtmlEncode($this->no_local->CurrentValue);
			$this->no_local->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_local->FldCaption()));

			// im_anexo
			$this->im_anexo->EditCustomAttributes = "";
			$this->im_anexo->UploadPath = "arquivos/resolucoes";
			if (!ew_Empty($this->im_anexo->Upload->DbValue)) {
				$this->im_anexo->EditValue = $this->im_anexo->Upload->DbValue;
			} else {
				$this->im_anexo->EditValue = "";
			}
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->im_anexo);

			// dt_publicacao
			$this->dt_publicacao->EditCustomAttributes = "";
			$this->dt_publicacao->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->dt_publicacao->CurrentValue, 7));
			$this->dt_publicacao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dt_publicacao->FldCaption()));

			// ic_situacao
			$this->ic_situacao->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_situacao->FldTagValue(1), $this->ic_situacao->FldTagCaption(1) <> "" ? $this->ic_situacao->FldTagCaption(1) : $this->ic_situacao->FldTagValue(1));
			$arwrk[] = array($this->ic_situacao->FldTagValue(2), $this->ic_situacao->FldTagCaption(2) <> "" ? $this->ic_situacao->FldTagCaption(2) : $this->ic_situacao->FldTagValue(2));
			$arwrk[] = array($this->ic_situacao->FldTagValue(3), $this->ic_situacao->FldTagCaption(3) <> "" ? $this->ic_situacao->FldTagCaption(3) : $this->ic_situacao->FldTagValue(3));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_situacao->EditValue = $arwrk;

			// nu_usuario
			// ts_datahora
			// Edit refer script
			// nu_grupoOuComite

			$this->nu_grupoOuComite->HrefValue = "";

			// ds_resolucao
			$this->ds_resolucao->HrefValue = "";

			// no_local
			$this->no_local->HrefValue = "";

			// im_anexo
			$this->im_anexo->HrefValue = "";
			$this->im_anexo->HrefValue2 = $this->im_anexo->UploadPath . $this->im_anexo->Upload->DbValue;

			// dt_publicacao
			$this->dt_publicacao->HrefValue = "";

			// ic_situacao
			$this->ic_situacao->HrefValue = "";

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
		if (!$this->nu_grupoOuComite->FldIsDetailKey && !is_null($this->nu_grupoOuComite->FormValue) && $this->nu_grupoOuComite->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_grupoOuComite->FldCaption());
		}
		if (!$this->ds_resolucao->FldIsDetailKey && !is_null($this->ds_resolucao->FormValue) && $this->ds_resolucao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ds_resolucao->FldCaption());
		}
		if (!$this->no_local->FldIsDetailKey && !is_null($this->no_local->FormValue) && $this->no_local->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_local->FldCaption());
		}
		if (!$this->dt_publicacao->FldIsDetailKey && !is_null($this->dt_publicacao->FormValue) && $this->dt_publicacao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->dt_publicacao->FldCaption());
		}
		if (!ew_CheckEuroDate($this->dt_publicacao->FormValue)) {
			ew_AddMessage($gsFormError, $this->dt_publicacao->FldErrMsg());
		}
		if (!$this->ic_situacao->FldIsDetailKey && !is_null($this->ic_situacao->FormValue) && $this->ic_situacao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_situacao->FldCaption());
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
			$this->im_anexo->OldUploadPath = "arquivos/resolucoes";
			$this->im_anexo->UploadPath = $this->im_anexo->OldUploadPath;
		}
		$rsnew = array();

		// nu_grupoOuComite
		$this->nu_grupoOuComite->SetDbValueDef($rsnew, $this->nu_grupoOuComite->CurrentValue, NULL, FALSE);

		// ds_resolucao
		$this->ds_resolucao->SetDbValueDef($rsnew, $this->ds_resolucao->CurrentValue, "", FALSE);

		// no_local
		$this->no_local->SetDbValueDef($rsnew, $this->no_local->CurrentValue, "", FALSE);

		// im_anexo
		if (!$this->im_anexo->Upload->KeepFile) {
			if ($this->im_anexo->Upload->FileName == "") {
				$rsnew['im_anexo'] = NULL;
			} else {
				$rsnew['im_anexo'] = $this->im_anexo->Upload->FileName;
			}
		}

		// dt_publicacao
		$this->dt_publicacao->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->dt_publicacao->CurrentValue, 7), NULL, FALSE);

		// ic_situacao
		$this->ic_situacao->SetDbValueDef($rsnew, $this->ic_situacao->CurrentValue, NULL, FALSE);

		// nu_usuario
		$this->nu_usuario->SetDbValueDef($rsnew, CurrentUserID(), NULL);
		$rsnew['nu_usuario'] = &$this->nu_usuario->DbValue;

		// ts_datahora
		$this->ts_datahora->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
		$rsnew['ts_datahora'] = &$this->ts_datahora->DbValue;
		if (!$this->im_anexo->Upload->KeepFile) {
			$this->im_anexo->UploadPath = "arquivos/resolucoes";
			$OldFiles = explode(",", $this->im_anexo->Upload->DbValue);
			if (!ew_Empty($this->im_anexo->Upload->FileName)) {
				$NewFiles = explode(",", $this->im_anexo->Upload->FileName);
				$FileCount = count($NewFiles);
				for ($i = 0; $i < $FileCount; $i++) {
					$fldvar = ($this->im_anexo->Upload->Index < 0) ? $this->im_anexo->FldVar : substr($this->im_anexo->FldVar, 0, 1) . $this->im_anexo->Upload->Index . substr($this->im_anexo->FldVar, 1);
					if ($NewFiles[$i] <> "") {
						$file = ew_UploadTempPath($fldvar) . EW_PATH_DELIMITER . $NewFiles[$i];
						if (file_exists($file)) {
							if (!in_array($NewFiles[$i], $OldFiles)) {
								$NewFiles[$i] = ew_UploadFileNameEx($this->im_anexo->UploadPath, $NewFiles[$i]); // Get new file name
								$file1 = ew_UploadTempPath($fldvar) . EW_PATH_DELIMITER . $NewFiles[$i];
								if ($file1 <> $file) // Rename temp file
									rename($file, $file1);
							}
						}
					}
				}
				$this->im_anexo->Upload->FileName = implode(",", $NewFiles);
				$rsnew['im_anexo'] = $this->im_anexo->Upload->FileName;
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
				if (!$this->im_anexo->Upload->KeepFile) {
					$OldFiles = explode(",", $this->im_anexo->Upload->DbValue);
					if (!ew_Empty($this->im_anexo->Upload->FileName)) {
						$NewFiles = explode(",", $this->im_anexo->Upload->FileName);
						$NewFiles2 = explode(",", $rsnew['im_anexo']);
						$FileCount = count($NewFiles);
						for ($i = 0; $i < $FileCount; $i++) {
							$fldvar = ($this->im_anexo->Upload->Index < 0) ? $this->im_anexo->FldVar : substr($this->im_anexo->FldVar, 0, 1) . $this->im_anexo->Upload->Index . substr($this->im_anexo->FldVar, 1);
							if ($NewFiles[$i] <> "") {
								$file = ew_UploadTempPath($fldvar) . EW_PATH_DELIMITER . $NewFiles[$i];
								if (file_exists($file)) {
									$this->im_anexo->Upload->Value = file_get_contents($file);
									$this->im_anexo->Upload->SaveToFile($this->im_anexo->UploadPath, (@$NewFiles2[$i] <> "") ? $NewFiles2[$i] : $NewFiles[$i], TRUE); // Just replace
								}
							}
						}
					} else {
						$NewFiles = array();
					}
					$FileCount = count($OldFiles);
					for ($i = 0; $i < $FileCount; $i++) {
						if ($OldFiles[$i] <> "" && !in_array($OldFiles[$i], $NewFiles))
							@unlink(ew_UploadPathEx(TRUE, $this->im_anexo->OldUploadPath) . $OldFiles[$i]);
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
			$this->nu_resolucao->setDbValue($conn->Insert_ID());
			$rsnew['nu_resolucao'] = $this->nu_resolucao->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}

		// im_anexo
		ew_CleanUploadTempPath($this->im_anexo, $this->im_anexo->Upload->Index);
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "gc_resolucaolist.php", $this->TableVar);
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
if (!isset($gc_resolucao_add)) $gc_resolucao_add = new cgc_resolucao_add();

// Page init
$gc_resolucao_add->Page_Init();

// Page main
$gc_resolucao_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$gc_resolucao_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var gc_resolucao_add = new ew_Page("gc_resolucao_add");
gc_resolucao_add.PageID = "add"; // Page ID
var EW_PAGE_ID = gc_resolucao_add.PageID; // For backward compatibility

// Form object
var fgc_resolucaoadd = new ew_Form("fgc_resolucaoadd");

// Validate form
fgc_resolucaoadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_grupoOuComite");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($gc_resolucao->nu_grupoOuComite->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ds_resolucao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($gc_resolucao->ds_resolucao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no_local");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($gc_resolucao->no_local->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dt_publicacao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($gc_resolucao->dt_publicacao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dt_publicacao");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($gc_resolucao->dt_publicacao->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ic_situacao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($gc_resolucao->ic_situacao->FldCaption()) ?>");

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
fgc_resolucaoadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgc_resolucaoadd.ValidateRequired = true;
<?php } else { ?>
fgc_resolucaoadd.ValidateRequired = false; 
<?php } ?>

// Multi-Page properties
fgc_resolucaoadd.MultiPage = new ew_MultiPage("fgc_resolucaoadd",
	[["x_nu_grupoOuComite",1],["x_ds_resolucao",1],["x_no_local",2],["x_im_anexo",2],["x_dt_publicacao",1],["x_ic_situacao",1]]
);

// Dynamic selection lists
fgc_resolucaoadd.Lists["x_nu_grupoOuComite"] = {"LinkField":"x_nu_gpComite","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_gpComite","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $gc_resolucao_add->ShowPageHeader(); ?>
<?php
$gc_resolucao_add->ShowMessage();
?>
<form name="fgc_resolucaoadd" id="fgc_resolucaoadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="gc_resolucao">
<input type="hidden" name="a_add" id="a_add" value="A">
<table class="ewStdTable"><tbody><tr><td>
<div class="tabbable" id="gc_resolucao_add">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_gc_resolucao1" data-toggle="tab"><?php echo $gc_resolucao->PageCaption(1) ?></a></li>
		<li><a href="#tab_gc_resolucao2" data-toggle="tab"><?php echo $gc_resolucao->PageCaption(2) ?></a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab_gc_resolucao1">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_gc_resolucaoadd1" class="table table-bordered table-striped">
<?php if ($gc_resolucao->nu_grupoOuComite->Visible) { // nu_grupoOuComite ?>
	<tr id="r_nu_grupoOuComite">
		<td><span id="elh_gc_resolucao_nu_grupoOuComite"><?php echo $gc_resolucao->nu_grupoOuComite->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $gc_resolucao->nu_grupoOuComite->CellAttributes() ?>>
<span id="el_gc_resolucao_nu_grupoOuComite" class="control-group">
<select data-field="x_nu_grupoOuComite" id="x_nu_grupoOuComite" name="x_nu_grupoOuComite"<?php echo $gc_resolucao->nu_grupoOuComite->EditAttributes() ?>>
<?php
if (is_array($gc_resolucao->nu_grupoOuComite->EditValue)) {
	$arwrk = $gc_resolucao->nu_grupoOuComite->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($gc_resolucao->nu_grupoOuComite->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fgc_resolucaoadd.Lists["x_nu_grupoOuComite"].Options = <?php echo (is_array($gc_resolucao->nu_grupoOuComite->EditValue)) ? ew_ArrayToJson($gc_resolucao->nu_grupoOuComite->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $gc_resolucao->nu_grupoOuComite->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gc_resolucao->ds_resolucao->Visible) { // ds_resolucao ?>
	<tr id="r_ds_resolucao">
		<td><span id="elh_gc_resolucao_ds_resolucao"><?php echo $gc_resolucao->ds_resolucao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $gc_resolucao->ds_resolucao->CellAttributes() ?>>
<span id="el_gc_resolucao_ds_resolucao" class="control-group">
<textarea data-field="x_ds_resolucao" name="x_ds_resolucao" id="x_ds_resolucao" cols="35" rows="4" placeholder="<?php echo $gc_resolucao->ds_resolucao->PlaceHolder ?>"<?php echo $gc_resolucao->ds_resolucao->EditAttributes() ?>><?php echo $gc_resolucao->ds_resolucao->EditValue ?></textarea>
</span>
<?php echo $gc_resolucao->ds_resolucao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gc_resolucao->dt_publicacao->Visible) { // dt_publicacao ?>
	<tr id="r_dt_publicacao">
		<td><span id="elh_gc_resolucao_dt_publicacao"><?php echo $gc_resolucao->dt_publicacao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $gc_resolucao->dt_publicacao->CellAttributes() ?>>
<span id="el_gc_resolucao_dt_publicacao" class="control-group">
<input type="text" data-field="x_dt_publicacao" name="x_dt_publicacao" id="x_dt_publicacao" placeholder="<?php echo $gc_resolucao->dt_publicacao->PlaceHolder ?>" value="<?php echo $gc_resolucao->dt_publicacao->EditValue ?>"<?php echo $gc_resolucao->dt_publicacao->EditAttributes() ?>>
<?php if (!$gc_resolucao->dt_publicacao->ReadOnly && !$gc_resolucao->dt_publicacao->Disabled && @$gc_resolucao->dt_publicacao->EditAttrs["readonly"] == "" && @$gc_resolucao->dt_publicacao->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_dt_publicacao" name="cal_x_dt_publicacao" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x_dt_publicacao" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fgc_resolucaoadd", "x_dt_publicacao", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $gc_resolucao->dt_publicacao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gc_resolucao->ic_situacao->Visible) { // ic_situacao ?>
	<tr id="r_ic_situacao">
		<td><span id="elh_gc_resolucao_ic_situacao"><?php echo $gc_resolucao->ic_situacao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $gc_resolucao->ic_situacao->CellAttributes() ?>>
<span id="el_gc_resolucao_ic_situacao" class="control-group">
<select data-field="x_ic_situacao" id="x_ic_situacao" name="x_ic_situacao"<?php echo $gc_resolucao->ic_situacao->EditAttributes() ?>>
<?php
if (is_array($gc_resolucao->ic_situacao->EditValue)) {
	$arwrk = $gc_resolucao->ic_situacao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($gc_resolucao->ic_situacao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $gc_resolucao->ic_situacao->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_gc_resolucao2">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_gc_resolucaoadd2" class="table table-bordered table-striped">
<?php if ($gc_resolucao->no_local->Visible) { // no_local ?>
	<tr id="r_no_local">
		<td><span id="elh_gc_resolucao_no_local"><?php echo $gc_resolucao->no_local->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $gc_resolucao->no_local->CellAttributes() ?>>
<span id="el_gc_resolucao_no_local" class="control-group">
<input type="text" data-field="x_no_local" name="x_no_local" id="x_no_local" size="30" maxlength="255" placeholder="<?php echo $gc_resolucao->no_local->PlaceHolder ?>" value="<?php echo $gc_resolucao->no_local->EditValue ?>"<?php echo $gc_resolucao->no_local->EditAttributes() ?>>
</span>
<?php echo $gc_resolucao->no_local->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gc_resolucao->im_anexo->Visible) { // im_anexo ?>
	<tr id="r_im_anexo">
		<td><span id="elh_gc_resolucao_im_anexo"><?php echo $gc_resolucao->im_anexo->FldCaption() ?></span></td>
		<td<?php echo $gc_resolucao->im_anexo->CellAttributes() ?>>
<span id="el_gc_resolucao_im_anexo" class="control-group">
<span id="fd_x_im_anexo">
<span class="btn btn-small fileinput-button">
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_im_anexo" name="x_im_anexo" id="x_im_anexo" multiple="multiple">
</span>
<input type="hidden" name="fn_x_im_anexo" id= "fn_x_im_anexo" value="<?php echo $gc_resolucao->im_anexo->Upload->FileName ?>">
<input type="hidden" name="fa_x_im_anexo" id= "fa_x_im_anexo" value="0">
<input type="hidden" name="fs_x_im_anexo" id= "fs_x_im_anexo" value="255">
</span>
<table id="ft_x_im_anexo" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $gc_resolucao->im_anexo->CustomMsg ?></td>
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
fgc_resolucaoadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$gc_resolucao_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$gc_resolucao_add->Page_Terminate();
?>
