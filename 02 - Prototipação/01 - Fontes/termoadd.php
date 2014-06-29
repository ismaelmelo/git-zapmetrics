<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "termoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$termo_add = NULL; // Initialize page object first

class ctermo_add extends ctermo {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'termo';

	// Page object name
	var $PageObjName = 'termo_add';

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

		// Table object (termo)
		if (!isset($GLOBALS["termo"])) {
			$GLOBALS["termo"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["termo"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'termo', TRUE);

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
			$this->Page_Terminate("termolist.php");
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
			if (@$_GET["nu_termo"] != "") {
				$this->nu_termo->setQueryStringValue($_GET["nu_termo"]);
				$this->setKey("nu_termo", $this->nu_termo->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_termo", ""); // Clear key
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
					$this->Page_Terminate("termolist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "termoview.php")
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
		$this->no_termo->CurrentValue = NULL;
		$this->no_termo->OldValue = $this->no_termo->CurrentValue;
		$this->ds_termo->CurrentValue = NULL;
		$this->ds_termo->OldValue = $this->ds_termo->CurrentValue;
		$this->ic_tpTermo->CurrentValue = NULL;
		$this->ic_tpTermo->OldValue = $this->ic_tpTermo->CurrentValue;
		$this->dt_emissao->CurrentValue = NULL;
		$this->dt_emissao->OldValue = $this->dt_emissao->CurrentValue;
		$this->im_anexo->Upload->DbValue = NULL;
		$this->im_anexo->OldValue = $this->im_anexo->Upload->DbValue;
		$this->im_anexo->CurrentValue = NULL; // Clear file related field
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
		if (!$this->no_termo->FldIsDetailKey) {
			$this->no_termo->setFormValue($objForm->GetValue("x_no_termo"));
		}
		if (!$this->ds_termo->FldIsDetailKey) {
			$this->ds_termo->setFormValue($objForm->GetValue("x_ds_termo"));
		}
		if (!$this->ic_tpTermo->FldIsDetailKey) {
			$this->ic_tpTermo->setFormValue($objForm->GetValue("x_ic_tpTermo"));
		}
		if (!$this->dt_emissao->FldIsDetailKey) {
			$this->dt_emissao->setFormValue($objForm->GetValue("x_dt_emissao"));
			$this->dt_emissao->CurrentValue = ew_UnFormatDateTime($this->dt_emissao->CurrentValue, 7);
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
		$this->no_termo->CurrentValue = $this->no_termo->FormValue;
		$this->ds_termo->CurrentValue = $this->ds_termo->FormValue;
		$this->ic_tpTermo->CurrentValue = $this->ic_tpTermo->FormValue;
		$this->dt_emissao->CurrentValue = $this->dt_emissao->FormValue;
		$this->dt_emissao->CurrentValue = ew_UnFormatDateTime($this->dt_emissao->CurrentValue, 7);
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
		$this->nu_termo->setDbValue($rs->fields('nu_termo'));
		$this->no_termo->setDbValue($rs->fields('no_termo'));
		$this->ds_termo->setDbValue($rs->fields('ds_termo'));
		$this->ic_tpTermo->setDbValue($rs->fields('ic_tpTermo'));
		$this->dt_emissao->setDbValue($rs->fields('dt_emissao'));
		$this->im_anexo->Upload->DbValue = $rs->fields('im_anexo');
		$this->ic_situacao->setDbValue($rs->fields('ic_situacao'));
		$this->nu_usuario->setDbValue($rs->fields('nu_usuario'));
		$this->ts_datahora->setDbValue($rs->fields('ts_datahora'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_termo->DbValue = $row['nu_termo'];
		$this->no_termo->DbValue = $row['no_termo'];
		$this->ds_termo->DbValue = $row['ds_termo'];
		$this->ic_tpTermo->DbValue = $row['ic_tpTermo'];
		$this->dt_emissao->DbValue = $row['dt_emissao'];
		$this->im_anexo->Upload->DbValue = $row['im_anexo'];
		$this->ic_situacao->DbValue = $row['ic_situacao'];
		$this->nu_usuario->DbValue = $row['nu_usuario'];
		$this->ts_datahora->DbValue = $row['ts_datahora'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_termo")) <> "")
			$this->nu_termo->CurrentValue = $this->getKey("nu_termo"); // nu_termo
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
		// nu_termo
		// no_termo
		// ds_termo
		// ic_tpTermo
		// dt_emissao
		// im_anexo
		// ic_situacao
		// nu_usuario
		// ts_datahora

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_termo
			$this->nu_termo->ViewValue = $this->nu_termo->CurrentValue;
			$this->nu_termo->ViewCustomAttributes = "";

			// no_termo
			$this->no_termo->ViewValue = $this->no_termo->CurrentValue;
			$this->no_termo->ViewCustomAttributes = "";

			// ds_termo
			$this->ds_termo->ViewValue = $this->ds_termo->CurrentValue;
			$this->ds_termo->ViewCustomAttributes = "";

			// ic_tpTermo
			if (strval($this->ic_tpTermo->CurrentValue) <> "") {
				switch ($this->ic_tpTermo->CurrentValue) {
					case $this->ic_tpTermo->FldTagValue(1):
						$this->ic_tpTermo->ViewValue = $this->ic_tpTermo->FldTagCaption(1) <> "" ? $this->ic_tpTermo->FldTagCaption(1) : $this->ic_tpTermo->CurrentValue;
						break;
					default:
						$this->ic_tpTermo->ViewValue = $this->ic_tpTermo->CurrentValue;
				}
			} else {
				$this->ic_tpTermo->ViewValue = NULL;
			}
			$this->ic_tpTermo->ViewCustomAttributes = "";

			// dt_emissao
			$this->dt_emissao->ViewValue = $this->dt_emissao->CurrentValue;
			$this->dt_emissao->ViewValue = ew_FormatDateTime($this->dt_emissao->ViewValue, 7);
			$this->dt_emissao->ViewCustomAttributes = "";

			// im_anexo
			$this->im_anexo->UploadPath = "arquivos/termos";
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

			// no_termo
			$this->no_termo->LinkCustomAttributes = "";
			$this->no_termo->HrefValue = "";
			$this->no_termo->TooltipValue = "";

			// ds_termo
			$this->ds_termo->LinkCustomAttributes = "";
			$this->ds_termo->HrefValue = "";
			$this->ds_termo->TooltipValue = "";

			// ic_tpTermo
			$this->ic_tpTermo->LinkCustomAttributes = "";
			$this->ic_tpTermo->HrefValue = "";
			$this->ic_tpTermo->TooltipValue = "";

			// dt_emissao
			$this->dt_emissao->LinkCustomAttributes = "";
			$this->dt_emissao->HrefValue = "";
			$this->dt_emissao->TooltipValue = "";

			// im_anexo
			$this->im_anexo->LinkCustomAttributes = "";
			$this->im_anexo->HrefValue = "";
			$this->im_anexo->HrefValue2 = $this->im_anexo->UploadPath . $this->im_anexo->Upload->DbValue;
			$this->im_anexo->TooltipValue = "";

			// nu_usuario
			$this->nu_usuario->LinkCustomAttributes = "";
			$this->nu_usuario->HrefValue = "";
			$this->nu_usuario->TooltipValue = "";

			// ts_datahora
			$this->ts_datahora->LinkCustomAttributes = "";
			$this->ts_datahora->HrefValue = "";
			$this->ts_datahora->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// no_termo
			$this->no_termo->EditCustomAttributes = "";
			$this->no_termo->EditValue = ew_HtmlEncode($this->no_termo->CurrentValue);
			$this->no_termo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_termo->FldCaption()));

			// ds_termo
			$this->ds_termo->EditCustomAttributes = "";
			$this->ds_termo->EditValue = $this->ds_termo->CurrentValue;
			$this->ds_termo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_termo->FldCaption()));

			// ic_tpTermo
			$this->ic_tpTermo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_tpTermo->FldTagValue(1), $this->ic_tpTermo->FldTagCaption(1) <> "" ? $this->ic_tpTermo->FldTagCaption(1) : $this->ic_tpTermo->FldTagValue(1));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_tpTermo->EditValue = $arwrk;

			// dt_emissao
			$this->dt_emissao->EditCustomAttributes = "";
			$this->dt_emissao->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->dt_emissao->CurrentValue, 7));
			$this->dt_emissao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dt_emissao->FldCaption()));

			// im_anexo
			$this->im_anexo->EditCustomAttributes = "";
			$this->im_anexo->UploadPath = "arquivos/termos";
			if (!ew_Empty($this->im_anexo->Upload->DbValue)) {
				$this->im_anexo->EditValue = $this->im_anexo->Upload->DbValue;
			} else {
				$this->im_anexo->EditValue = "";
			}
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->im_anexo);

			// nu_usuario
			// ts_datahora
			// Edit refer script
			// no_termo

			$this->no_termo->HrefValue = "";

			// ds_termo
			$this->ds_termo->HrefValue = "";

			// ic_tpTermo
			$this->ic_tpTermo->HrefValue = "";

			// dt_emissao
			$this->dt_emissao->HrefValue = "";

			// im_anexo
			$this->im_anexo->HrefValue = "";
			$this->im_anexo->HrefValue2 = $this->im_anexo->UploadPath . $this->im_anexo->Upload->DbValue;

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
		if (!$this->no_termo->FldIsDetailKey && !is_null($this->no_termo->FormValue) && $this->no_termo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_termo->FldCaption());
		}
		if (!$this->ds_termo->FldIsDetailKey && !is_null($this->ds_termo->FormValue) && $this->ds_termo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ds_termo->FldCaption());
		}
		if (!$this->ic_tpTermo->FldIsDetailKey && !is_null($this->ic_tpTermo->FormValue) && $this->ic_tpTermo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_tpTermo->FldCaption());
		}
		if (!ew_CheckEuroDate($this->dt_emissao->FormValue)) {
			ew_AddMessage($gsFormError, $this->dt_emissao->FldErrMsg());
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
			$this->im_anexo->OldUploadPath = "arquivos/termos";
			$this->im_anexo->UploadPath = $this->im_anexo->OldUploadPath;
		}
		$rsnew = array();

		// no_termo
		$this->no_termo->SetDbValueDef($rsnew, $this->no_termo->CurrentValue, "", FALSE);

		// ds_termo
		$this->ds_termo->SetDbValueDef($rsnew, $this->ds_termo->CurrentValue, "", FALSE);

		// ic_tpTermo
		$this->ic_tpTermo->SetDbValueDef($rsnew, $this->ic_tpTermo->CurrentValue, "", FALSE);

		// dt_emissao
		$this->dt_emissao->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->dt_emissao->CurrentValue, 7), NULL, FALSE);

		// im_anexo
		if (!$this->im_anexo->Upload->KeepFile) {
			if ($this->im_anexo->Upload->FileName == "") {
				$rsnew['im_anexo'] = NULL;
			} else {
				$rsnew['im_anexo'] = $this->im_anexo->Upload->FileName;
			}
		}

		// nu_usuario
		$this->nu_usuario->SetDbValueDef($rsnew, CurrentUserID(), NULL);
		$rsnew['nu_usuario'] = &$this->nu_usuario->DbValue;

		// ts_datahora
		$this->ts_datahora->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
		$rsnew['ts_datahora'] = &$this->ts_datahora->DbValue;
		if (!$this->im_anexo->Upload->KeepFile) {
			$this->im_anexo->UploadPath = "arquivos/termos";
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
			$this->nu_termo->setDbValue($conn->Insert_ID());
			$rsnew['nu_termo'] = $this->nu_termo->DbValue;
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "termolist.php", $this->TableVar);
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
if (!isset($termo_add)) $termo_add = new ctermo_add();

// Page init
$termo_add->Page_Init();

// Page main
$termo_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$termo_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var termo_add = new ew_Page("termo_add");
termo_add.PageID = "add"; // Page ID
var EW_PAGE_ID = termo_add.PageID; // For backward compatibility

// Form object
var ftermoadd = new ew_Form("ftermoadd");

// Validate form
ftermoadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_no_termo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($termo->no_termo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ds_termo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($termo->ds_termo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_tpTermo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($termo->ic_tpTermo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dt_emissao");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($termo->dt_emissao->FldErrMsg()) ?>");

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
ftermoadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftermoadd.ValidateRequired = true;
<?php } else { ?>
ftermoadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $termo_add->ShowPageHeader(); ?>
<?php
$termo_add->ShowMessage();
?>
<form name="ftermoadd" id="ftermoadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="termo">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_termoadd" class="table table-bordered table-striped">
<?php if ($termo->no_termo->Visible) { // no_termo ?>
	<tr id="r_no_termo">
		<td><span id="elh_termo_no_termo"><?php echo $termo->no_termo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $termo->no_termo->CellAttributes() ?>>
<span id="el_termo_no_termo" class="control-group">
<input type="text" data-field="x_no_termo" name="x_no_termo" id="x_no_termo" size="30" maxlength="150" placeholder="<?php echo $termo->no_termo->PlaceHolder ?>" value="<?php echo $termo->no_termo->EditValue ?>"<?php echo $termo->no_termo->EditAttributes() ?>>
</span>
<?php echo $termo->no_termo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($termo->ds_termo->Visible) { // ds_termo ?>
	<tr id="r_ds_termo">
		<td><span id="elh_termo_ds_termo"><?php echo $termo->ds_termo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $termo->ds_termo->CellAttributes() ?>>
<span id="el_termo_ds_termo" class="control-group">
<textarea data-field="x_ds_termo" name="x_ds_termo" id="x_ds_termo" cols="35" rows="4" placeholder="<?php echo $termo->ds_termo->PlaceHolder ?>"<?php echo $termo->ds_termo->EditAttributes() ?>><?php echo $termo->ds_termo->EditValue ?></textarea>
</span>
<?php echo $termo->ds_termo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($termo->ic_tpTermo->Visible) { // ic_tpTermo ?>
	<tr id="r_ic_tpTermo">
		<td><span id="elh_termo_ic_tpTermo"><?php echo $termo->ic_tpTermo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $termo->ic_tpTermo->CellAttributes() ?>>
<span id="el_termo_ic_tpTermo" class="control-group">
<select data-field="x_ic_tpTermo" id="x_ic_tpTermo" name="x_ic_tpTermo"<?php echo $termo->ic_tpTermo->EditAttributes() ?>>
<?php
if (is_array($termo->ic_tpTermo->EditValue)) {
	$arwrk = $termo->ic_tpTermo->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($termo->ic_tpTermo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $termo->ic_tpTermo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($termo->dt_emissao->Visible) { // dt_emissao ?>
	<tr id="r_dt_emissao">
		<td><span id="elh_termo_dt_emissao"><?php echo $termo->dt_emissao->FldCaption() ?></span></td>
		<td<?php echo $termo->dt_emissao->CellAttributes() ?>>
<span id="el_termo_dt_emissao" class="control-group">
<input type="text" data-field="x_dt_emissao" name="x_dt_emissao" id="x_dt_emissao" placeholder="<?php echo $termo->dt_emissao->PlaceHolder ?>" value="<?php echo $termo->dt_emissao->EditValue ?>"<?php echo $termo->dt_emissao->EditAttributes() ?>>
<?php if (!$termo->dt_emissao->ReadOnly && !$termo->dt_emissao->Disabled && @$termo->dt_emissao->EditAttrs["readonly"] == "" && @$termo->dt_emissao->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_dt_emissao" name="cal_x_dt_emissao" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x_dt_emissao" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("ftermoadd", "x_dt_emissao", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $termo->dt_emissao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($termo->im_anexo->Visible) { // im_anexo ?>
	<tr id="r_im_anexo">
		<td><span id="elh_termo_im_anexo"><?php echo $termo->im_anexo->FldCaption() ?></span></td>
		<td<?php echo $termo->im_anexo->CellAttributes() ?>>
<span id="el_termo_im_anexo" class="control-group">
<span id="fd_x_im_anexo">
<span class="btn btn-small fileinput-button">
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_im_anexo" name="x_im_anexo" id="x_im_anexo" multiple="multiple">
</span>
<input type="hidden" name="fn_x_im_anexo" id= "fn_x_im_anexo" value="<?php echo $termo->im_anexo->Upload->FileName ?>">
<input type="hidden" name="fa_x_im_anexo" id= "fa_x_im_anexo" value="0">
<input type="hidden" name="fs_x_im_anexo" id= "fs_x_im_anexo" value="255">
</span>
<table id="ft_x_im_anexo" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $termo->im_anexo->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
ftermoadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$termo_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$termo_add->Page_Terminate();
?>
