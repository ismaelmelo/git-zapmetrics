<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "planoestrategicoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$planoestrategico_addopt = NULL; // Initialize page object first

class cplanoestrategico_addopt extends cplanoestrategico {

	// Page ID
	var $PageID = 'addopt';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'planoestrategico';

	// Page object name
	var $PageObjName = 'planoestrategico_addopt';

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

		// Table object (planoestrategico)
		if (!isset($GLOBALS["planoestrategico"])) {
			$GLOBALS["planoestrategico"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["planoestrategico"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'addopt', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'planoestrategico', TRUE);

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
			$this->Page_Terminate("planoestrategicolist.php");
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

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		set_error_handler("ew_ErrorHandler");

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if ($objForm->GetValue("a_addopt") <> "") {
			$this->CurrentAction = $objForm->GetValue("a_addopt"); // Get form action
			$this->LoadFormValues(); // Load form values

			// Validate form
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->setFailureMessage($gsFormError);
			}
		} else { // Not post back
			$this->CurrentAction = "I"; // Display blank record
			$this->LoadDefaultValues(); // Load default values
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow()) { // Add successful
					$row = array();
					$row["x_nu_plano"] = $this->nu_plano->DbValue;
					$row["x_nu_anoInicio"] = $this->nu_anoInicio->DbValue;
					$row["x_nu_anoFim"] = $this->nu_anoFim->DbValue;
					$row["x_no_plano"] = $this->no_plano->DbValue;
					$row["x_ds_plano"] = $this->ds_plano->DbValue;
					$row["x_ds_missao"] = $this->ds_missao->DbValue;
					$row["x_ds_visao"] = $this->ds_visao->DbValue;
					$row["x_ds_valores"] = $this->ds_valores->DbValue;
					$row["x_no_localArquivo"] = $this->no_localArquivo->DbValue;
					$row["x_im_anexo"] = $this->im_anexo->DbValue;
					$row["x_ic_situacao"] = $this->ic_situacao->DbValue;
					$row["x_nu_usuario"] = $this->nu_usuario->DbValue;
					$row["x_ts_datahora"] = $this->ts_datahora->DbValue;
					if (!EW_DEBUG_ENABLED && ob_get_length())
						ob_end_clean();
					echo ew_ArrayToJson(array($row));
				} else {
					$this->ShowMessage();
				}
				$this->Page_Terminate();
				exit();
		}

		// Render row
		$this->RowType = EW_ROWTYPE_ADD; // Render add type
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
		$this->nu_anoInicio->CurrentValue = NULL;
		$this->nu_anoInicio->OldValue = $this->nu_anoInicio->CurrentValue;
		$this->nu_anoFim->CurrentValue = NULL;
		$this->nu_anoFim->OldValue = $this->nu_anoFim->CurrentValue;
		$this->no_plano->CurrentValue = NULL;
		$this->no_plano->OldValue = $this->no_plano->CurrentValue;
		$this->ds_plano->CurrentValue = NULL;
		$this->ds_plano->OldValue = $this->ds_plano->CurrentValue;
		$this->ds_missao->CurrentValue = NULL;
		$this->ds_missao->OldValue = $this->ds_missao->CurrentValue;
		$this->ds_visao->CurrentValue = NULL;
		$this->ds_visao->OldValue = $this->ds_visao->CurrentValue;
		$this->ds_valores->CurrentValue = NULL;
		$this->ds_valores->OldValue = $this->ds_valores->CurrentValue;
		$this->no_localArquivo->CurrentValue = NULL;
		$this->no_localArquivo->OldValue = $this->no_localArquivo->CurrentValue;
		$this->im_anexo->Upload->DbValue = NULL;
		$this->im_anexo->OldValue = $this->im_anexo->Upload->DbValue;
		$this->im_anexo->CurrentValue = NULL; // Clear file related field
		$this->ic_situacao->CurrentValue = "L";
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
		if (!$this->nu_anoInicio->FldIsDetailKey) {
			$this->nu_anoInicio->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_nu_anoInicio")));
		}
		if (!$this->nu_anoFim->FldIsDetailKey) {
			$this->nu_anoFim->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_nu_anoFim")));
		}
		if (!$this->no_plano->FldIsDetailKey) {
			$this->no_plano->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_no_plano")));
		}
		if (!$this->ds_plano->FldIsDetailKey) {
			$this->ds_plano->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_ds_plano")));
		}
		if (!$this->ds_missao->FldIsDetailKey) {
			$this->ds_missao->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_ds_missao")));
		}
		if (!$this->ds_visao->FldIsDetailKey) {
			$this->ds_visao->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_ds_visao")));
		}
		if (!$this->ds_valores->FldIsDetailKey) {
			$this->ds_valores->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_ds_valores")));
		}
		if (!$this->no_localArquivo->FldIsDetailKey) {
			$this->no_localArquivo->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_no_localArquivo")));
		}
		if (!$this->ic_situacao->FldIsDetailKey) {
			$this->ic_situacao->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_ic_situacao")));
		}
		if (!$this->nu_usuario->FldIsDetailKey) {
			$this->nu_usuario->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_nu_usuario")));
		}
		if (!$this->ts_datahora->FldIsDetailKey) {
			$this->ts_datahora->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_ts_datahora")));
			$this->ts_datahora->CurrentValue = ew_UnFormatDateTime($this->ts_datahora->CurrentValue, 7);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->nu_anoInicio->CurrentValue = ew_ConvertToUtf8($this->nu_anoInicio->FormValue);
		$this->nu_anoFim->CurrentValue = ew_ConvertToUtf8($this->nu_anoFim->FormValue);
		$this->no_plano->CurrentValue = ew_ConvertToUtf8($this->no_plano->FormValue);
		$this->ds_plano->CurrentValue = ew_ConvertToUtf8($this->ds_plano->FormValue);
		$this->ds_missao->CurrentValue = ew_ConvertToUtf8($this->ds_missao->FormValue);
		$this->ds_visao->CurrentValue = ew_ConvertToUtf8($this->ds_visao->FormValue);
		$this->ds_valores->CurrentValue = ew_ConvertToUtf8($this->ds_valores->FormValue);
		$this->no_localArquivo->CurrentValue = ew_ConvertToUtf8($this->no_localArquivo->FormValue);
		$this->ic_situacao->CurrentValue = ew_ConvertToUtf8($this->ic_situacao->FormValue);
		$this->nu_usuario->CurrentValue = ew_ConvertToUtf8($this->nu_usuario->FormValue);
		$this->ts_datahora->CurrentValue = ew_ConvertToUtf8($this->ts_datahora->FormValue);
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
		$this->nu_plano->setDbValue($rs->fields('nu_plano'));
		$this->nu_anoInicio->setDbValue($rs->fields('nu_anoInicio'));
		$this->nu_anoFim->setDbValue($rs->fields('nu_anoFim'));
		$this->no_plano->setDbValue($rs->fields('no_plano'));
		$this->ds_plano->setDbValue($rs->fields('ds_plano'));
		$this->ds_missao->setDbValue($rs->fields('ds_missao'));
		$this->ds_visao->setDbValue($rs->fields('ds_visao'));
		$this->ds_valores->setDbValue($rs->fields('ds_valores'));
		$this->no_localArquivo->setDbValue($rs->fields('no_localArquivo'));
		$this->im_anexo->Upload->DbValue = $rs->fields('im_anexo');
		$this->ic_situacao->setDbValue($rs->fields('ic_situacao'));
		$this->nu_usuario->setDbValue($rs->fields('nu_usuario'));
		$this->ts_datahora->setDbValue($rs->fields('ts_datahora'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_plano->DbValue = $row['nu_plano'];
		$this->nu_anoInicio->DbValue = $row['nu_anoInicio'];
		$this->nu_anoFim->DbValue = $row['nu_anoFim'];
		$this->no_plano->DbValue = $row['no_plano'];
		$this->ds_plano->DbValue = $row['ds_plano'];
		$this->ds_missao->DbValue = $row['ds_missao'];
		$this->ds_visao->DbValue = $row['ds_visao'];
		$this->ds_valores->DbValue = $row['ds_valores'];
		$this->no_localArquivo->DbValue = $row['no_localArquivo'];
		$this->im_anexo->Upload->DbValue = $row['im_anexo'];
		$this->ic_situacao->DbValue = $row['ic_situacao'];
		$this->nu_usuario->DbValue = $row['nu_usuario'];
		$this->ts_datahora->DbValue = $row['ts_datahora'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_plano
		// nu_anoInicio
		// nu_anoFim
		// no_plano
		// ds_plano
		// ds_missao
		// ds_visao
		// ds_valores
		// no_localArquivo
		// im_anexo
		// ic_situacao
		// nu_usuario
		// ts_datahora

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_plano
			$this->nu_plano->ViewValue = $this->nu_plano->CurrentValue;
			$this->nu_plano->ViewCustomAttributes = "";

			// nu_anoInicio
			$this->nu_anoInicio->ViewValue = $this->nu_anoInicio->CurrentValue;
			$this->nu_anoInicio->ViewCustomAttributes = "";

			// nu_anoFim
			$this->nu_anoFim->ViewValue = $this->nu_anoFim->CurrentValue;
			$this->nu_anoFim->ViewCustomAttributes = "";

			// no_plano
			$this->no_plano->ViewValue = $this->no_plano->CurrentValue;
			$this->no_plano->ViewCustomAttributes = "";

			// ds_plano
			$this->ds_plano->ViewValue = $this->ds_plano->CurrentValue;
			$this->ds_plano->ViewCustomAttributes = "";

			// ds_missao
			$this->ds_missao->ViewValue = $this->ds_missao->CurrentValue;
			$this->ds_missao->ViewCustomAttributes = "";

			// ds_visao
			$this->ds_visao->ViewValue = $this->ds_visao->CurrentValue;
			$this->ds_visao->ViewCustomAttributes = "";

			// ds_valores
			$this->ds_valores->ViewValue = $this->ds_valores->CurrentValue;
			$this->ds_valores->ViewCustomAttributes = "";

			// no_localArquivo
			$this->no_localArquivo->ViewValue = $this->no_localArquivo->CurrentValue;
			$this->no_localArquivo->ViewCustomAttributes = "";

			// im_anexo
			$this->im_anexo->UploadPath = "arquivos/plano_estrategico";
			if (!ew_Empty($this->im_anexo->Upload->DbValue)) {
				$this->im_anexo->ViewValue = $this->im_anexo->Upload->DbValue;
			} else {
				$this->im_anexo->ViewValue = "";
			}
			$this->im_anexo->ViewCustomAttributes = "";

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
					case $this->ic_situacao->FldTagValue(4):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(4) <> "" ? $this->ic_situacao->FldTagCaption(4) : $this->ic_situacao->CurrentValue;
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

			// nu_anoInicio
			$this->nu_anoInicio->LinkCustomAttributes = "";
			$this->nu_anoInicio->HrefValue = "";
			$this->nu_anoInicio->TooltipValue = "";

			// nu_anoFim
			$this->nu_anoFim->LinkCustomAttributes = "";
			$this->nu_anoFim->HrefValue = "";
			$this->nu_anoFim->TooltipValue = "";

			// no_plano
			$this->no_plano->LinkCustomAttributes = "";
			$this->no_plano->HrefValue = "";
			$this->no_plano->TooltipValue = "";

			// ds_plano
			$this->ds_plano->LinkCustomAttributes = "";
			$this->ds_plano->HrefValue = "";
			$this->ds_plano->TooltipValue = "";

			// ds_missao
			$this->ds_missao->LinkCustomAttributes = "";
			$this->ds_missao->HrefValue = "";
			$this->ds_missao->TooltipValue = "";

			// ds_visao
			$this->ds_visao->LinkCustomAttributes = "";
			$this->ds_visao->HrefValue = "";
			$this->ds_visao->TooltipValue = "";

			// ds_valores
			$this->ds_valores->LinkCustomAttributes = "";
			$this->ds_valores->HrefValue = "";
			$this->ds_valores->TooltipValue = "";

			// no_localArquivo
			$this->no_localArquivo->LinkCustomAttributes = "";
			$this->no_localArquivo->HrefValue = "";
			$this->no_localArquivo->TooltipValue = "";

			// im_anexo
			$this->im_anexo->LinkCustomAttributes = "";
			$this->im_anexo->HrefValue = "";
			$this->im_anexo->HrefValue2 = $this->im_anexo->UploadPath . $this->im_anexo->Upload->DbValue;
			$this->im_anexo->TooltipValue = "";

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

			// nu_anoInicio
			$this->nu_anoInicio->EditCustomAttributes = "";
			$this->nu_anoInicio->EditValue = ew_HtmlEncode($this->nu_anoInicio->CurrentValue);
			$this->nu_anoInicio->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_anoInicio->FldCaption()));

			// nu_anoFim
			$this->nu_anoFim->EditCustomAttributes = "";
			$this->nu_anoFim->EditValue = ew_HtmlEncode($this->nu_anoFim->CurrentValue);
			$this->nu_anoFim->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_anoFim->FldCaption()));

			// no_plano
			$this->no_plano->EditCustomAttributes = "";
			$this->no_plano->EditValue = ew_HtmlEncode($this->no_plano->CurrentValue);
			$this->no_plano->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_plano->FldCaption()));

			// ds_plano
			$this->ds_plano->EditCustomAttributes = "";
			$this->ds_plano->EditValue = $this->ds_plano->CurrentValue;
			$this->ds_plano->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_plano->FldCaption()));

			// ds_missao
			$this->ds_missao->EditCustomAttributes = "";
			$this->ds_missao->EditValue = $this->ds_missao->CurrentValue;
			$this->ds_missao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_missao->FldCaption()));

			// ds_visao
			$this->ds_visao->EditCustomAttributes = "";
			$this->ds_visao->EditValue = $this->ds_visao->CurrentValue;
			$this->ds_visao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_visao->FldCaption()));

			// ds_valores
			$this->ds_valores->EditCustomAttributes = "";
			$this->ds_valores->EditValue = $this->ds_valores->CurrentValue;
			$this->ds_valores->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_valores->FldCaption()));

			// no_localArquivo
			$this->no_localArquivo->EditCustomAttributes = "";
			$this->no_localArquivo->EditValue = ew_HtmlEncode($this->no_localArquivo->CurrentValue);
			$this->no_localArquivo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_localArquivo->FldCaption()));

			// im_anexo
			$this->im_anexo->EditCustomAttributes = "";
			$this->im_anexo->UploadPath = "arquivos/plano_estrategico";
			if (!ew_Empty($this->im_anexo->Upload->DbValue)) {
				$this->im_anexo->EditValue = $this->im_anexo->Upload->DbValue;
			} else {
				$this->im_anexo->EditValue = "";
			}
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->im_anexo);

			// ic_situacao
			$this->ic_situacao->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_situacao->FldTagValue(1), $this->ic_situacao->FldTagCaption(1) <> "" ? $this->ic_situacao->FldTagCaption(1) : $this->ic_situacao->FldTagValue(1));
			$arwrk[] = array($this->ic_situacao->FldTagValue(2), $this->ic_situacao->FldTagCaption(2) <> "" ? $this->ic_situacao->FldTagCaption(2) : $this->ic_situacao->FldTagValue(2));
			$arwrk[] = array($this->ic_situacao->FldTagValue(3), $this->ic_situacao->FldTagCaption(3) <> "" ? $this->ic_situacao->FldTagCaption(3) : $this->ic_situacao->FldTagValue(3));
			$arwrk[] = array($this->ic_situacao->FldTagValue(4), $this->ic_situacao->FldTagCaption(4) <> "" ? $this->ic_situacao->FldTagCaption(4) : $this->ic_situacao->FldTagValue(4));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_situacao->EditValue = $arwrk;

			// nu_usuario
			// ts_datahora
			// Edit refer script
			// nu_anoInicio

			$this->nu_anoInicio->HrefValue = "";

			// nu_anoFim
			$this->nu_anoFim->HrefValue = "";

			// no_plano
			$this->no_plano->HrefValue = "";

			// ds_plano
			$this->ds_plano->HrefValue = "";

			// ds_missao
			$this->ds_missao->HrefValue = "";

			// ds_visao
			$this->ds_visao->HrefValue = "";

			// ds_valores
			$this->ds_valores->HrefValue = "";

			// no_localArquivo
			$this->no_localArquivo->HrefValue = "";

			// im_anexo
			$this->im_anexo->HrefValue = "";
			$this->im_anexo->HrefValue2 = $this->im_anexo->UploadPath . $this->im_anexo->Upload->DbValue;

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
		if (!$this->nu_anoInicio->FldIsDetailKey && !is_null($this->nu_anoInicio->FormValue) && $this->nu_anoInicio->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_anoInicio->FldCaption());
		}
		if (!ew_CheckInteger($this->nu_anoInicio->FormValue)) {
			ew_AddMessage($gsFormError, $this->nu_anoInicio->FldErrMsg());
		}
		if (!$this->nu_anoFim->FldIsDetailKey && !is_null($this->nu_anoFim->FormValue) && $this->nu_anoFim->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_anoFim->FldCaption());
		}
		if (!ew_CheckInteger($this->nu_anoFim->FormValue)) {
			ew_AddMessage($gsFormError, $this->nu_anoFim->FldErrMsg());
		}
		if (!$this->no_plano->FldIsDetailKey && !is_null($this->no_plano->FormValue) && $this->no_plano->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_plano->FldCaption());
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
			$this->im_anexo->OldUploadPath = "arquivos/plano_estrategico";
			$this->im_anexo->UploadPath = $this->im_anexo->OldUploadPath;
		}
		$rsnew = array();

		// nu_anoInicio
		$this->nu_anoInicio->SetDbValueDef($rsnew, $this->nu_anoInicio->CurrentValue, 0, FALSE);

		// nu_anoFim
		$this->nu_anoFim->SetDbValueDef($rsnew, $this->nu_anoFim->CurrentValue, 0, FALSE);

		// no_plano
		$this->no_plano->SetDbValueDef($rsnew, $this->no_plano->CurrentValue, "", FALSE);

		// ds_plano
		$this->ds_plano->SetDbValueDef($rsnew, $this->ds_plano->CurrentValue, NULL, FALSE);

		// ds_missao
		$this->ds_missao->SetDbValueDef($rsnew, $this->ds_missao->CurrentValue, NULL, FALSE);

		// ds_visao
		$this->ds_visao->SetDbValueDef($rsnew, $this->ds_visao->CurrentValue, NULL, FALSE);

		// ds_valores
		$this->ds_valores->SetDbValueDef($rsnew, $this->ds_valores->CurrentValue, NULL, FALSE);

		// no_localArquivo
		$this->no_localArquivo->SetDbValueDef($rsnew, $this->no_localArquivo->CurrentValue, NULL, FALSE);

		// im_anexo
		if (!$this->im_anexo->Upload->KeepFile) {
			if ($this->im_anexo->Upload->FileName == "") {
				$rsnew['im_anexo'] = NULL;
			} else {
				$rsnew['im_anexo'] = $this->im_anexo->Upload->FileName;
			}
		}

		// ic_situacao
		$this->ic_situacao->SetDbValueDef($rsnew, $this->ic_situacao->CurrentValue, "", FALSE);

		// nu_usuario
		$this->nu_usuario->SetDbValueDef($rsnew, CurrentUserID(), NULL);
		$rsnew['nu_usuario'] = &$this->nu_usuario->DbValue;

		// ts_datahora
		$this->ts_datahora->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
		$rsnew['ts_datahora'] = &$this->ts_datahora->DbValue;
		if (!$this->im_anexo->Upload->KeepFile) {
			$this->im_anexo->UploadPath = "arquivos/plano_estrategico";
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
			$this->nu_plano->setDbValue($conn->Insert_ID());
			$rsnew['nu_plano'] = $this->nu_plano->DbValue;
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "planoestrategicolist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("addopt");
		$Breadcrumb->Add("addopt", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
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

	// Custom validate event
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
if (!isset($planoestrategico_addopt)) $planoestrategico_addopt = new cplanoestrategico_addopt();

// Page init
$planoestrategico_addopt->Page_Init();

// Page main
$planoestrategico_addopt->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$planoestrategico_addopt->Page_Render();
?>
<script type="text/javascript">

// Page object
var planoestrategico_addopt = new ew_Page("planoestrategico_addopt");
planoestrategico_addopt.PageID = "addopt"; // Page ID
var EW_PAGE_ID = planoestrategico_addopt.PageID; // For backward compatibility

// Form object
var fplanoestrategicoaddopt = new ew_Form("fplanoestrategicoaddopt");

// Validate form
fplanoestrategicoaddopt.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_anoInicio");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($planoestrategico->nu_anoInicio->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_anoInicio");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($planoestrategico->nu_anoInicio->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nu_anoFim");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($planoestrategico->nu_anoFim->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_anoFim");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($planoestrategico->nu_anoFim->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_no_plano");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($planoestrategico->no_plano->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_situacao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($planoestrategico->ic_situacao->FldCaption()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
fplanoestrategicoaddopt.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fplanoestrategicoaddopt.ValidateRequired = true;
<?php } else { ?>
fplanoestrategicoaddopt.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
$planoestrategico_addopt->ShowMessage();
?>
<form name="fplanoestrategicoaddopt" id="fplanoestrategicoaddopt" class="ewForm form-horizontal" action="planoestrategicoaddopt.php" method="post">
<input type="hidden" name="t" value="planoestrategico">
<input type="hidden" name="a_addopt" id="a_addopt" value="A">
<div id="tbl_planoestrategicoaddopt">
	<div class="control-group">
		<label class="control-label" for="x_nu_anoInicio"><?php echo $planoestrategico->nu_anoInicio->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="controls">
<input type="text" data-field="x_nu_anoInicio" name="x_nu_anoInicio" id="x_nu_anoInicio" size="30" placeholder="<?php echo $planoestrategico->nu_anoInicio->PlaceHolder ?>" value="<?php echo $planoestrategico->nu_anoInicio->EditValue ?>"<?php echo $planoestrategico->nu_anoInicio->EditAttributes() ?>>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_nu_anoFim"><?php echo $planoestrategico->nu_anoFim->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="controls">
<input type="text" data-field="x_nu_anoFim" name="x_nu_anoFim" id="x_nu_anoFim" size="30" placeholder="<?php echo $planoestrategico->nu_anoFim->PlaceHolder ?>" value="<?php echo $planoestrategico->nu_anoFim->EditValue ?>"<?php echo $planoestrategico->nu_anoFim->EditAttributes() ?>>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_no_plano"><?php echo $planoestrategico->no_plano->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="controls">
<input type="text" data-field="x_no_plano" name="x_no_plano" id="x_no_plano" size="30" maxlength="100" placeholder="<?php echo $planoestrategico->no_plano->PlaceHolder ?>" value="<?php echo $planoestrategico->no_plano->EditValue ?>"<?php echo $planoestrategico->no_plano->EditAttributes() ?>>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_ds_plano"><?php echo $planoestrategico->ds_plano->FldCaption() ?></label>
		<div class="controls">
<textarea data-field="x_ds_plano" name="x_ds_plano" id="x_ds_plano" cols="35" rows="4" placeholder="<?php echo $planoestrategico->ds_plano->PlaceHolder ?>"<?php echo $planoestrategico->ds_plano->EditAttributes() ?>><?php echo $planoestrategico->ds_plano->EditValue ?></textarea>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_ds_missao"><?php echo $planoestrategico->ds_missao->FldCaption() ?></label>
		<div class="controls">
<textarea data-field="x_ds_missao" name="x_ds_missao" id="x_ds_missao" cols="35" rows="4" placeholder="<?php echo $planoestrategico->ds_missao->PlaceHolder ?>"<?php echo $planoestrategico->ds_missao->EditAttributes() ?>><?php echo $planoestrategico->ds_missao->EditValue ?></textarea>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_ds_visao"><?php echo $planoestrategico->ds_visao->FldCaption() ?></label>
		<div class="controls">
<textarea data-field="x_ds_visao" name="x_ds_visao" id="x_ds_visao" cols="35" rows="4" placeholder="<?php echo $planoestrategico->ds_visao->PlaceHolder ?>"<?php echo $planoestrategico->ds_visao->EditAttributes() ?>><?php echo $planoestrategico->ds_visao->EditValue ?></textarea>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_ds_valores"><?php echo $planoestrategico->ds_valores->FldCaption() ?></label>
		<div class="controls">
<textarea data-field="x_ds_valores" name="x_ds_valores" id="x_ds_valores" cols="35" rows="4" placeholder="<?php echo $planoestrategico->ds_valores->PlaceHolder ?>"<?php echo $planoestrategico->ds_valores->EditAttributes() ?>><?php echo $planoestrategico->ds_valores->EditValue ?></textarea>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_no_localArquivo"><?php echo $planoestrategico->no_localArquivo->FldCaption() ?></label>
		<div class="controls">
<input type="text" data-field="x_no_localArquivo" name="x_no_localArquivo" id="x_no_localArquivo" size="30" maxlength="255" placeholder="<?php echo $planoestrategico->no_localArquivo->PlaceHolder ?>" value="<?php echo $planoestrategico->no_localArquivo->EditValue ?>"<?php echo $planoestrategico->no_localArquivo->EditAttributes() ?>>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_im_anexo"><?php echo $planoestrategico->im_anexo->FldCaption() ?></label>
		<div class="controls">
<span id="fd_x_im_anexo">
<span class="btn btn-small fileinput-button">
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_im_anexo" name="x_im_anexo" id="x_im_anexo" multiple="multiple">
</span>
<input type="hidden" name="fn_x_im_anexo" id= "fn_x_im_anexo" value="<?php echo $planoestrategico->im_anexo->Upload->FileName ?>">
<input type="hidden" name="fa_x_im_anexo" id= "fa_x_im_anexo" value="0">
<input type="hidden" name="fs_x_im_anexo" id= "fs_x_im_anexo" value="255">
</span>
<table id="ft_x_im_anexo" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_ic_situacao"><?php echo $planoestrategico->ic_situacao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="controls">
<select data-field="x_ic_situacao" id="x_ic_situacao" name="x_ic_situacao"<?php echo $planoestrategico->ic_situacao->EditAttributes() ?>>
<?php
if (is_array($planoestrategico->ic_situacao->EditValue)) {
	$arwrk = $planoestrategico->ic_situacao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($planoestrategico->ic_situacao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
</div>
	</div>
</div>
</form>
<script type="text/javascript">
fplanoestrategicoaddopt.Init();
</script>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php
$planoestrategico_addopt->Page_Terminate();
?>
