<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "itembaseconhecimentoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$itembaseconhecimento_add = NULL; // Initialize page object first

class citembaseconhecimento_add extends citembaseconhecimento {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'itembaseconhecimento';

	// Page object name
	var $PageObjName = 'itembaseconhecimento_add';

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

		// Table object (itembaseconhecimento)
		if (!isset($GLOBALS["itembaseconhecimento"])) {
			$GLOBALS["itembaseconhecimento"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["itembaseconhecimento"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'itembaseconhecimento', TRUE);

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
			$this->Page_Terminate("itembaseconhecimentolist.php");
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
			if (@$_GET["nu_item"] != "") {
				$this->nu_item->setQueryStringValue($_GET["nu_item"]);
				$this->setKey("nu_item", $this->nu_item->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_item", ""); // Clear key
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
					$this->Page_Terminate("itembaseconhecimentolist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "itembaseconhecimentoview.php")
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
		$this->no_tituloItem->CurrentValue = NULL;
		$this->no_tituloItem->OldValue = $this->no_tituloItem->CurrentValue;
		$this->ic_tpItem->CurrentValue = NULL;
		$this->ic_tpItem->OldValue = $this->ic_tpItem->CurrentValue;
		$this->ds_item->CurrentValue = NULL;
		$this->ds_item->OldValue = $this->ds_item->CurrentValue;
		$this->ic_situacao->CurrentValue = "N";
		$this->ds_acoes->CurrentValue = NULL;
		$this->ds_acoes->OldValue = $this->ds_acoes->CurrentValue;
		$this->nu_usuarioInc->CurrentValue = NULL;
		$this->nu_usuarioInc->OldValue = $this->nu_usuarioInc->CurrentValue;
		$this->dh_inclusao->CurrentValue = NULL;
		$this->dh_inclusao->OldValue = $this->dh_inclusao->CurrentValue;
		$this->nu_sistema->CurrentValue = NULL;
		$this->nu_sistema->OldValue = $this->nu_sistema->CurrentValue;
		$this->nu_modulo->CurrentValue = NULL;
		$this->nu_modulo->OldValue = $this->nu_modulo->CurrentValue;
		$this->nu_uc->CurrentValue = NULL;
		$this->nu_uc->OldValue = $this->nu_uc->CurrentValue;
		$this->nu_processoCobit->CurrentValue = NULL;
		$this->nu_processoCobit->OldValue = $this->nu_processoCobit->CurrentValue;
		$this->nu_prospecto->CurrentValue = NULL;
		$this->nu_prospecto->OldValue = $this->nu_prospecto->CurrentValue;
		$this->nu_projeto->CurrentValue = NULL;
		$this->nu_projeto->OldValue = $this->nu_projeto->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->no_tituloItem->FldIsDetailKey) {
			$this->no_tituloItem->setFormValue($objForm->GetValue("x_no_tituloItem"));
		}
		if (!$this->ic_tpItem->FldIsDetailKey) {
			$this->ic_tpItem->setFormValue($objForm->GetValue("x_ic_tpItem"));
		}
		if (!$this->ds_item->FldIsDetailKey) {
			$this->ds_item->setFormValue($objForm->GetValue("x_ds_item"));
		}
		if (!$this->ic_situacao->FldIsDetailKey) {
			$this->ic_situacao->setFormValue($objForm->GetValue("x_ic_situacao"));
		}
		if (!$this->ds_acoes->FldIsDetailKey) {
			$this->ds_acoes->setFormValue($objForm->GetValue("x_ds_acoes"));
		}
		if (!$this->nu_usuarioInc->FldIsDetailKey) {
			$this->nu_usuarioInc->setFormValue($objForm->GetValue("x_nu_usuarioInc"));
		}
		if (!$this->dh_inclusao->FldIsDetailKey) {
			$this->dh_inclusao->setFormValue($objForm->GetValue("x_dh_inclusao"));
			$this->dh_inclusao->CurrentValue = ew_UnFormatDateTime($this->dh_inclusao->CurrentValue, 9);
		}
		if (!$this->nu_sistema->FldIsDetailKey) {
			$this->nu_sistema->setFormValue($objForm->GetValue("x_nu_sistema"));
		}
		if (!$this->nu_modulo->FldIsDetailKey) {
			$this->nu_modulo->setFormValue($objForm->GetValue("x_nu_modulo"));
		}
		if (!$this->nu_uc->FldIsDetailKey) {
			$this->nu_uc->setFormValue($objForm->GetValue("x_nu_uc"));
		}
		if (!$this->nu_processoCobit->FldIsDetailKey) {
			$this->nu_processoCobit->setFormValue($objForm->GetValue("x_nu_processoCobit"));
		}
		if (!$this->nu_prospecto->FldIsDetailKey) {
			$this->nu_prospecto->setFormValue($objForm->GetValue("x_nu_prospecto"));
		}
		if (!$this->nu_projeto->FldIsDetailKey) {
			$this->nu_projeto->setFormValue($objForm->GetValue("x_nu_projeto"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->no_tituloItem->CurrentValue = $this->no_tituloItem->FormValue;
		$this->ic_tpItem->CurrentValue = $this->ic_tpItem->FormValue;
		$this->ds_item->CurrentValue = $this->ds_item->FormValue;
		$this->ic_situacao->CurrentValue = $this->ic_situacao->FormValue;
		$this->ds_acoes->CurrentValue = $this->ds_acoes->FormValue;
		$this->nu_usuarioInc->CurrentValue = $this->nu_usuarioInc->FormValue;
		$this->dh_inclusao->CurrentValue = $this->dh_inclusao->FormValue;
		$this->dh_inclusao->CurrentValue = ew_UnFormatDateTime($this->dh_inclusao->CurrentValue, 9);
		$this->nu_sistema->CurrentValue = $this->nu_sistema->FormValue;
		$this->nu_modulo->CurrentValue = $this->nu_modulo->FormValue;
		$this->nu_uc->CurrentValue = $this->nu_uc->FormValue;
		$this->nu_processoCobit->CurrentValue = $this->nu_processoCobit->FormValue;
		$this->nu_prospecto->CurrentValue = $this->nu_prospecto->FormValue;
		$this->nu_projeto->CurrentValue = $this->nu_projeto->FormValue;
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
		$this->nu_item->setDbValue($rs->fields('nu_item'));
		$this->no_tituloItem->setDbValue($rs->fields('no_tituloItem'));
		$this->ic_tpItem->setDbValue($rs->fields('ic_tpItem'));
		$this->ds_item->setDbValue($rs->fields('ds_item'));
		$this->ic_situacao->setDbValue($rs->fields('ic_situacao'));
		$this->ds_acoes->setDbValue($rs->fields('ds_acoes'));
		$this->nu_usuarioInc->setDbValue($rs->fields('nu_usuarioInc'));
		$this->dh_inclusao->setDbValue($rs->fields('dh_inclusao'));
		$this->nu_usuarioAlt->setDbValue($rs->fields('nu_usuarioAlt'));
		$this->dh_alteracao->setDbValue($rs->fields('dh_alteracao'));
		$this->nu_sistema->setDbValue($rs->fields('nu_sistema'));
		if (array_key_exists('EV__nu_sistema', $rs->fields)) {
			$this->nu_sistema->VirtualValue = $rs->fields('EV__nu_sistema'); // Set up virtual field value
		} else {
			$this->nu_sistema->VirtualValue = ""; // Clear value
		}
		$this->nu_modulo->setDbValue($rs->fields('nu_modulo'));
		if (array_key_exists('EV__nu_modulo', $rs->fields)) {
			$this->nu_modulo->VirtualValue = $rs->fields('EV__nu_modulo'); // Set up virtual field value
		} else {
			$this->nu_modulo->VirtualValue = ""; // Clear value
		}
		$this->nu_uc->setDbValue($rs->fields('nu_uc'));
		if (array_key_exists('EV__nu_uc', $rs->fields)) {
			$this->nu_uc->VirtualValue = $rs->fields('EV__nu_uc'); // Set up virtual field value
		} else {
			$this->nu_uc->VirtualValue = ""; // Clear value
		}
		$this->nu_processoCobit->setDbValue($rs->fields('nu_processoCobit'));
		$this->nu_prospecto->setDbValue($rs->fields('nu_prospecto'));
		if (array_key_exists('EV__nu_prospecto', $rs->fields)) {
			$this->nu_prospecto->VirtualValue = $rs->fields('EV__nu_prospecto'); // Set up virtual field value
		} else {
			$this->nu_prospecto->VirtualValue = ""; // Clear value
		}
		$this->nu_projeto->setDbValue($rs->fields('nu_projeto'));
		if (array_key_exists('EV__nu_projeto', $rs->fields)) {
			$this->nu_projeto->VirtualValue = $rs->fields('EV__nu_projeto'); // Set up virtual field value
		} else {
			$this->nu_projeto->VirtualValue = ""; // Clear value
		}
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_item->DbValue = $row['nu_item'];
		$this->no_tituloItem->DbValue = $row['no_tituloItem'];
		$this->ic_tpItem->DbValue = $row['ic_tpItem'];
		$this->ds_item->DbValue = $row['ds_item'];
		$this->ic_situacao->DbValue = $row['ic_situacao'];
		$this->ds_acoes->DbValue = $row['ds_acoes'];
		$this->nu_usuarioInc->DbValue = $row['nu_usuarioInc'];
		$this->dh_inclusao->DbValue = $row['dh_inclusao'];
		$this->nu_usuarioAlt->DbValue = $row['nu_usuarioAlt'];
		$this->dh_alteracao->DbValue = $row['dh_alteracao'];
		$this->nu_sistema->DbValue = $row['nu_sistema'];
		$this->nu_modulo->DbValue = $row['nu_modulo'];
		$this->nu_uc->DbValue = $row['nu_uc'];
		$this->nu_processoCobit->DbValue = $row['nu_processoCobit'];
		$this->nu_prospecto->DbValue = $row['nu_prospecto'];
		$this->nu_projeto->DbValue = $row['nu_projeto'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_item")) <> "")
			$this->nu_item->CurrentValue = $this->getKey("nu_item"); // nu_item
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
		// nu_item
		// no_tituloItem
		// ic_tpItem
		// ds_item
		// ic_situacao
		// ds_acoes
		// nu_usuarioInc
		// dh_inclusao
		// nu_usuarioAlt
		// dh_alteracao
		// nu_sistema
		// nu_modulo
		// nu_uc
		// nu_processoCobit
		// nu_prospecto
		// nu_projeto

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_item
			$this->nu_item->ViewValue = $this->nu_item->CurrentValue;
			$this->nu_item->ViewCustomAttributes = "";

			// no_tituloItem
			$this->no_tituloItem->ViewValue = $this->no_tituloItem->CurrentValue;
			$this->no_tituloItem->ViewCustomAttributes = "";

			// ic_tpItem
			if (strval($this->ic_tpItem->CurrentValue) <> "") {
				switch ($this->ic_tpItem->CurrentValue) {
					case $this->ic_tpItem->FldTagValue(1):
						$this->ic_tpItem->ViewValue = $this->ic_tpItem->FldTagCaption(1) <> "" ? $this->ic_tpItem->FldTagCaption(1) : $this->ic_tpItem->CurrentValue;
						break;
					case $this->ic_tpItem->FldTagValue(2):
						$this->ic_tpItem->ViewValue = $this->ic_tpItem->FldTagCaption(2) <> "" ? $this->ic_tpItem->FldTagCaption(2) : $this->ic_tpItem->CurrentValue;
						break;
					case $this->ic_tpItem->FldTagValue(3):
						$this->ic_tpItem->ViewValue = $this->ic_tpItem->FldTagCaption(3) <> "" ? $this->ic_tpItem->FldTagCaption(3) : $this->ic_tpItem->CurrentValue;
						break;
					default:
						$this->ic_tpItem->ViewValue = $this->ic_tpItem->CurrentValue;
				}
			} else {
				$this->ic_tpItem->ViewValue = NULL;
			}
			$this->ic_tpItem->ViewCustomAttributes = "";

			// ds_item
			$this->ds_item->ViewValue = $this->ds_item->CurrentValue;
			$this->ds_item->ViewCustomAttributes = "";

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
					case $this->ic_situacao->FldTagValue(5):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(5) <> "" ? $this->ic_situacao->FldTagCaption(5) : $this->ic_situacao->CurrentValue;
						break;
					default:
						$this->ic_situacao->ViewValue = $this->ic_situacao->CurrentValue;
				}
			} else {
				$this->ic_situacao->ViewValue = NULL;
			}
			$this->ic_situacao->ViewCustomAttributes = "";

			// ds_acoes
			$this->ds_acoes->ViewValue = $this->ds_acoes->CurrentValue;
			$this->ds_acoes->ViewCustomAttributes = "";

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
			$this->dh_inclusao->ViewValue = ew_FormatDateTime($this->dh_inclusao->ViewValue, 9);
			$this->dh_inclusao->ViewCustomAttributes = "";

			// nu_usuarioAlt
			if (strval($this->nu_usuarioAlt->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioAlt->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioAlt, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioAlt->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioAlt->ViewValue = $this->nu_usuarioAlt->CurrentValue;
				}
			} else {
				$this->nu_usuarioAlt->ViewValue = NULL;
			}
			$this->nu_usuarioAlt->ViewCustomAttributes = "";

			// dh_alteracao
			$this->dh_alteracao->ViewValue = $this->dh_alteracao->CurrentValue;
			$this->dh_alteracao->ViewValue = ew_FormatDateTime($this->dh_alteracao->ViewValue, 9);
			$this->dh_alteracao->ViewCustomAttributes = "";

			// nu_sistema
			if ($this->nu_sistema->VirtualValue <> "") {
				$this->nu_sistema->ViewValue = $this->nu_sistema->VirtualValue;
			} else {
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
			}
			$this->nu_sistema->ViewCustomAttributes = "";

			// nu_modulo
			if ($this->nu_modulo->VirtualValue <> "") {
				$this->nu_modulo->ViewValue = $this->nu_modulo->VirtualValue;
			} else {
			if (strval($this->nu_modulo->CurrentValue) <> "") {
				$sFilterWrk = "[nu_modulo]" . ew_SearchString("=", $this->nu_modulo->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_modulo], [no_modulo] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[modulo]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_modulo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_modulo] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_modulo->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_modulo->ViewValue = $this->nu_modulo->CurrentValue;
				}
			} else {
				$this->nu_modulo->ViewValue = NULL;
			}
			}
			$this->nu_modulo->ViewCustomAttributes = "";

			// nu_uc
			if ($this->nu_uc->VirtualValue <> "") {
				$this->nu_uc->ViewValue = $this->nu_uc->VirtualValue;
			} else {
			if (strval($this->nu_uc->CurrentValue) <> "") {
				$sFilterWrk = "[nu_uc]" . ew_SearchString("=", $this->nu_uc->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_uc], [co_alternativo] AS [DispFld], [no_uc] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[uc]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_uc, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [co_alternativo] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_uc->ViewValue = $rswrk->fields('DispFld');
					$this->nu_uc->ViewValue .= ew_ValueSeparator(1,$this->nu_uc) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_uc->ViewValue = $this->nu_uc->CurrentValue;
				}
			} else {
				$this->nu_uc->ViewValue = NULL;
			}
			}
			$this->nu_uc->ViewCustomAttributes = "";

			// nu_processoCobit
			if (strval($this->nu_processoCobit->CurrentValue) <> "") {
				$sFilterWrk = "[nu_processo]" . ew_SearchString("=", $this->nu_processoCobit->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_processo], [co_alternativo] AS [DispFld], [no_processo] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[processocobit5]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_processoCobit, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_processoCobit->ViewValue = $rswrk->fields('DispFld');
					$this->nu_processoCobit->ViewValue .= ew_ValueSeparator(1,$this->nu_processoCobit) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_processoCobit->ViewValue = $this->nu_processoCobit->CurrentValue;
				}
			} else {
				$this->nu_processoCobit->ViewValue = NULL;
			}
			$this->nu_processoCobit->ViewCustomAttributes = "";

			// nu_prospecto
			if ($this->nu_prospecto->VirtualValue <> "") {
				$this->nu_prospecto->ViewValue = $this->nu_prospecto->VirtualValue;
			} else {
			if (strval($this->nu_prospecto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_prospecto]" . ew_SearchString("=", $this->nu_prospecto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_prospecto], [no_prospecto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[prospecto]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_prospecto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_prospecto] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_prospecto->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_prospecto->ViewValue = $this->nu_prospecto->CurrentValue;
				}
			} else {
				$this->nu_prospecto->ViewValue = NULL;
			}
			}
			$this->nu_prospecto->ViewCustomAttributes = "";

			// nu_projeto
			if ($this->nu_projeto->VirtualValue <> "") {
				$this->nu_projeto->ViewValue = $this->nu_projeto->VirtualValue;
			} else {
			if (strval($this->nu_projeto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_projeto]" . ew_SearchString("=", $this->nu_projeto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_projeto], [no_projeto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[projeto]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_projeto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_projeto] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_projeto->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_projeto->ViewValue = $this->nu_projeto->CurrentValue;
				}
			} else {
				$this->nu_projeto->ViewValue = NULL;
			}
			}
			$this->nu_projeto->ViewCustomAttributes = "";

			// no_tituloItem
			$this->no_tituloItem->LinkCustomAttributes = "";
			$this->no_tituloItem->HrefValue = "";
			$this->no_tituloItem->TooltipValue = "";

			// ic_tpItem
			$this->ic_tpItem->LinkCustomAttributes = "";
			$this->ic_tpItem->HrefValue = "";
			$this->ic_tpItem->TooltipValue = "";

			// ds_item
			$this->ds_item->LinkCustomAttributes = "";
			$this->ds_item->HrefValue = "";
			$this->ds_item->TooltipValue = "";

			// ic_situacao
			$this->ic_situacao->LinkCustomAttributes = "";
			$this->ic_situacao->HrefValue = "";
			$this->ic_situacao->TooltipValue = "";

			// ds_acoes
			$this->ds_acoes->LinkCustomAttributes = "";
			$this->ds_acoes->HrefValue = "";
			$this->ds_acoes->TooltipValue = "";

			// nu_usuarioInc
			$this->nu_usuarioInc->LinkCustomAttributes = "";
			$this->nu_usuarioInc->HrefValue = "";
			$this->nu_usuarioInc->TooltipValue = "";

			// dh_inclusao
			$this->dh_inclusao->LinkCustomAttributes = "";
			$this->dh_inclusao->HrefValue = "";
			$this->dh_inclusao->TooltipValue = "";

			// nu_sistema
			$this->nu_sistema->LinkCustomAttributes = "";
			$this->nu_sistema->HrefValue = "";
			$this->nu_sistema->TooltipValue = "";

			// nu_modulo
			$this->nu_modulo->LinkCustomAttributes = "";
			$this->nu_modulo->HrefValue = "";
			$this->nu_modulo->TooltipValue = "";

			// nu_uc
			$this->nu_uc->LinkCustomAttributes = "";
			$this->nu_uc->HrefValue = "";
			$this->nu_uc->TooltipValue = "";

			// nu_processoCobit
			$this->nu_processoCobit->LinkCustomAttributes = "";
			$this->nu_processoCobit->HrefValue = "";
			$this->nu_processoCobit->TooltipValue = "";

			// nu_prospecto
			$this->nu_prospecto->LinkCustomAttributes = "";
			$this->nu_prospecto->HrefValue = "";
			$this->nu_prospecto->TooltipValue = "";

			// nu_projeto
			$this->nu_projeto->LinkCustomAttributes = "";
			$this->nu_projeto->HrefValue = "";
			$this->nu_projeto->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// no_tituloItem
			$this->no_tituloItem->EditCustomAttributes = "";
			$this->no_tituloItem->EditValue = ew_HtmlEncode($this->no_tituloItem->CurrentValue);
			$this->no_tituloItem->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_tituloItem->FldCaption()));

			// ic_tpItem
			$this->ic_tpItem->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_tpItem->FldTagValue(1), $this->ic_tpItem->FldTagCaption(1) <> "" ? $this->ic_tpItem->FldTagCaption(1) : $this->ic_tpItem->FldTagValue(1));
			$arwrk[] = array($this->ic_tpItem->FldTagValue(2), $this->ic_tpItem->FldTagCaption(2) <> "" ? $this->ic_tpItem->FldTagCaption(2) : $this->ic_tpItem->FldTagValue(2));
			$arwrk[] = array($this->ic_tpItem->FldTagValue(3), $this->ic_tpItem->FldTagCaption(3) <> "" ? $this->ic_tpItem->FldTagCaption(3) : $this->ic_tpItem->FldTagValue(3));
			$this->ic_tpItem->EditValue = $arwrk;

			// ds_item
			$this->ds_item->EditCustomAttributes = "";
			$this->ds_item->EditValue = $this->ds_item->CurrentValue;
			$this->ds_item->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_item->FldCaption()));

			// ic_situacao
			$this->ic_situacao->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_situacao->FldTagValue(1), $this->ic_situacao->FldTagCaption(1) <> "" ? $this->ic_situacao->FldTagCaption(1) : $this->ic_situacao->FldTagValue(1));
			$arwrk[] = array($this->ic_situacao->FldTagValue(2), $this->ic_situacao->FldTagCaption(2) <> "" ? $this->ic_situacao->FldTagCaption(2) : $this->ic_situacao->FldTagValue(2));
			$arwrk[] = array($this->ic_situacao->FldTagValue(3), $this->ic_situacao->FldTagCaption(3) <> "" ? $this->ic_situacao->FldTagCaption(3) : $this->ic_situacao->FldTagValue(3));
			$arwrk[] = array($this->ic_situacao->FldTagValue(4), $this->ic_situacao->FldTagCaption(4) <> "" ? $this->ic_situacao->FldTagCaption(4) : $this->ic_situacao->FldTagValue(4));
			$arwrk[] = array($this->ic_situacao->FldTagValue(5), $this->ic_situacao->FldTagCaption(5) <> "" ? $this->ic_situacao->FldTagCaption(5) : $this->ic_situacao->FldTagValue(5));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_situacao->EditValue = $arwrk;

			// ds_acoes
			$this->ds_acoes->EditCustomAttributes = "";
			$this->ds_acoes->EditValue = $this->ds_acoes->CurrentValue;
			$this->ds_acoes->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_acoes->FldCaption()));

			// nu_usuarioInc
			// dh_inclusao
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

			// nu_modulo
			$this->nu_modulo->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_modulo], [no_modulo] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [nu_sistema] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[modulo]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_modulo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_modulo] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_modulo->EditValue = $arwrk;

			// nu_uc
			$this->nu_uc->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_uc], [co_alternativo] AS [DispFld], [no_uc] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [nu_sistema] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[uc]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_uc, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [co_alternativo] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_uc->EditValue = $arwrk;

			// nu_processoCobit
			$this->nu_processoCobit->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_processo], [co_alternativo] AS [DispFld], [no_processo] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[processocobit5]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_processoCobit, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_processoCobit->EditValue = $arwrk;

			// nu_prospecto
			$this->nu_prospecto->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_prospecto], [no_prospecto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[prospecto]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_prospecto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_prospecto] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_prospecto->EditValue = $arwrk;

			// nu_projeto
			$this->nu_projeto->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_projeto], [no_projeto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[projeto]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_projeto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_projeto] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_projeto->EditValue = $arwrk;

			// Edit refer script
			// no_tituloItem

			$this->no_tituloItem->HrefValue = "";

			// ic_tpItem
			$this->ic_tpItem->HrefValue = "";

			// ds_item
			$this->ds_item->HrefValue = "";

			// ic_situacao
			$this->ic_situacao->HrefValue = "";

			// ds_acoes
			$this->ds_acoes->HrefValue = "";

			// nu_usuarioInc
			$this->nu_usuarioInc->HrefValue = "";

			// dh_inclusao
			$this->dh_inclusao->HrefValue = "";

			// nu_sistema
			$this->nu_sistema->HrefValue = "";

			// nu_modulo
			$this->nu_modulo->HrefValue = "";

			// nu_uc
			$this->nu_uc->HrefValue = "";

			// nu_processoCobit
			$this->nu_processoCobit->HrefValue = "";

			// nu_prospecto
			$this->nu_prospecto->HrefValue = "";

			// nu_projeto
			$this->nu_projeto->HrefValue = "";
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
		if (!$this->no_tituloItem->FldIsDetailKey && !is_null($this->no_tituloItem->FormValue) && $this->no_tituloItem->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_tituloItem->FldCaption());
		}
		if ($this->ic_tpItem->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_tpItem->FldCaption());
		}
		if (!$this->ds_item->FldIsDetailKey && !is_null($this->ds_item->FormValue) && $this->ds_item->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ds_item->FldCaption());
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
		}
		$rsnew = array();

		// no_tituloItem
		$this->no_tituloItem->SetDbValueDef($rsnew, $this->no_tituloItem->CurrentValue, NULL, FALSE);

		// ic_tpItem
		$this->ic_tpItem->SetDbValueDef($rsnew, $this->ic_tpItem->CurrentValue, "", FALSE);

		// ds_item
		$this->ds_item->SetDbValueDef($rsnew, $this->ds_item->CurrentValue, NULL, FALSE);

		// ic_situacao
		$this->ic_situacao->SetDbValueDef($rsnew, $this->ic_situacao->CurrentValue, NULL, FALSE);

		// ds_acoes
		$this->ds_acoes->SetDbValueDef($rsnew, $this->ds_acoes->CurrentValue, NULL, FALSE);

		// nu_usuarioInc
		$this->nu_usuarioInc->SetDbValueDef($rsnew, CurrentUserID(), NULL);
		$rsnew['nu_usuarioInc'] = &$this->nu_usuarioInc->DbValue;

		// dh_inclusao
		$this->dh_inclusao->SetDbValueDef($rsnew, ew_CurrentTime(), NULL);
		$rsnew['dh_inclusao'] = &$this->dh_inclusao->DbValue;

		// nu_sistema
		$this->nu_sistema->SetDbValueDef($rsnew, $this->nu_sistema->CurrentValue, NULL, FALSE);

		// nu_modulo
		$this->nu_modulo->SetDbValueDef($rsnew, $this->nu_modulo->CurrentValue, NULL, FALSE);

		// nu_uc
		$this->nu_uc->SetDbValueDef($rsnew, $this->nu_uc->CurrentValue, NULL, FALSE);

		// nu_processoCobit
		$this->nu_processoCobit->SetDbValueDef($rsnew, $this->nu_processoCobit->CurrentValue, NULL, FALSE);

		// nu_prospecto
		$this->nu_prospecto->SetDbValueDef($rsnew, $this->nu_prospecto->CurrentValue, NULL, FALSE);

		// nu_projeto
		$this->nu_projeto->SetDbValueDef($rsnew, $this->nu_projeto->CurrentValue, NULL, FALSE);

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
			$this->nu_item->setDbValue($conn->Insert_ID());
			$rsnew['nu_item'] = $this->nu_item->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "itembaseconhecimentolist.php", $this->TableVar);
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
if (!isset($itembaseconhecimento_add)) $itembaseconhecimento_add = new citembaseconhecimento_add();

// Page init
$itembaseconhecimento_add->Page_Init();

// Page main
$itembaseconhecimento_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$itembaseconhecimento_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var itembaseconhecimento_add = new ew_Page("itembaseconhecimento_add");
itembaseconhecimento_add.PageID = "add"; // Page ID
var EW_PAGE_ID = itembaseconhecimento_add.PageID; // For backward compatibility

// Form object
var fitembaseconhecimentoadd = new ew_Form("fitembaseconhecimentoadd");

// Validate form
fitembaseconhecimentoadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_no_tituloItem");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($itembaseconhecimento->no_tituloItem->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_tpItem");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($itembaseconhecimento->ic_tpItem->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ds_item");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($itembaseconhecimento->ds_item->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_situacao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($itembaseconhecimento->ic_situacao->FldCaption()) ?>");

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
fitembaseconhecimentoadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fitembaseconhecimentoadd.ValidateRequired = true;
<?php } else { ?>
fitembaseconhecimentoadd.ValidateRequired = false; 
<?php } ?>

// Multi-Page properties
fitembaseconhecimentoadd.MultiPage = new ew_MultiPage("fitembaseconhecimentoadd",
	[["x_no_tituloItem",1],["x_ic_tpItem",1],["x_ds_item",1],["x_ic_situacao",1],["x_ds_acoes",1],["x_nu_sistema",4],["x_nu_modulo",4],["x_nu_uc",4],["x_nu_processoCobit",2],["x_nu_prospecto",3],["x_nu_projeto",3]]
);

// Dynamic selection lists
fitembaseconhecimentoadd.Lists["x_nu_usuarioInc"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fitembaseconhecimentoadd.Lists["x_nu_sistema"] = {"LinkField":"x_nu_sistema","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_alternativo","x_no_sistema","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fitembaseconhecimentoadd.Lists["x_nu_modulo"] = {"LinkField":"x_nu_modulo","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_modulo","","",""],"ParentFields":["x_nu_sistema"],"FilterFields":["x_nu_sistema"],"Options":[]};
fitembaseconhecimentoadd.Lists["x_nu_uc"] = {"LinkField":"x_nu_uc","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_alternativo","x_no_uc","",""],"ParentFields":["x_nu_sistema"],"FilterFields":["x_nu_sistema"],"Options":[]};
fitembaseconhecimentoadd.Lists["x_nu_processoCobit"] = {"LinkField":"x_nu_processo","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_alternativo","x_no_processo","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fitembaseconhecimentoadd.Lists["x_nu_prospecto"] = {"LinkField":"x_nu_prospecto","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_prospecto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fitembaseconhecimentoadd.Lists["x_nu_projeto"] = {"LinkField":"x_nu_projeto","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_projeto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $itembaseconhecimento_add->ShowPageHeader(); ?>
<?php
$itembaseconhecimento_add->ShowMessage();
?>
<form name="fitembaseconhecimentoadd" id="fitembaseconhecimentoadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="itembaseconhecimento">
<input type="hidden" name="a_add" id="a_add" value="A">
<table class="ewStdTable"><tbody><tr><td>
<div class="tabbable" id="itembaseconhecimento_add">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_itembaseconhecimento1" data-toggle="tab"><?php echo $itembaseconhecimento->PageCaption(1) ?></a></li>
		<li><a href="#tab_itembaseconhecimento2" data-toggle="tab"><?php echo $itembaseconhecimento->PageCaption(2) ?></a></li>
		<li><a href="#tab_itembaseconhecimento3" data-toggle="tab"><?php echo $itembaseconhecimento->PageCaption(3) ?></a></li>
		<li><a href="#tab_itembaseconhecimento4" data-toggle="tab"><?php echo $itembaseconhecimento->PageCaption(4) ?></a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab_itembaseconhecimento1">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_itembaseconhecimentoadd1" class="table table-bordered table-striped">
<?php if ($itembaseconhecimento->no_tituloItem->Visible) { // no_tituloItem ?>
	<tr id="r_no_tituloItem">
		<td><span id="elh_itembaseconhecimento_no_tituloItem"><?php echo $itembaseconhecimento->no_tituloItem->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $itembaseconhecimento->no_tituloItem->CellAttributes() ?>>
<span id="el_itembaseconhecimento_no_tituloItem" class="control-group">
<input type="text" data-field="x_no_tituloItem" name="x_no_tituloItem" id="x_no_tituloItem" size="30" maxlength="100" placeholder="<?php echo $itembaseconhecimento->no_tituloItem->PlaceHolder ?>" value="<?php echo $itembaseconhecimento->no_tituloItem->EditValue ?>"<?php echo $itembaseconhecimento->no_tituloItem->EditAttributes() ?>>
</span>
<?php echo $itembaseconhecimento->no_tituloItem->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($itembaseconhecimento->ic_tpItem->Visible) { // ic_tpItem ?>
	<tr id="r_ic_tpItem">
		<td><span id="elh_itembaseconhecimento_ic_tpItem"><?php echo $itembaseconhecimento->ic_tpItem->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $itembaseconhecimento->ic_tpItem->CellAttributes() ?>>
<span id="el_itembaseconhecimento_ic_tpItem" class="control-group">
<div id="tp_x_ic_tpItem" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_tpItem" id="x_ic_tpItem" value="{value}"<?php echo $itembaseconhecimento->ic_tpItem->EditAttributes() ?>></div>
<div id="dsl_x_ic_tpItem" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $itembaseconhecimento->ic_tpItem->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($itembaseconhecimento->ic_tpItem->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_tpItem" name="x_ic_tpItem" id="x_ic_tpItem_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $itembaseconhecimento->ic_tpItem->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $itembaseconhecimento->ic_tpItem->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($itembaseconhecimento->ds_item->Visible) { // ds_item ?>
	<tr id="r_ds_item">
		<td><span id="elh_itembaseconhecimento_ds_item"><?php echo $itembaseconhecimento->ds_item->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $itembaseconhecimento->ds_item->CellAttributes() ?>>
<span id="el_itembaseconhecimento_ds_item" class="control-group">
<textarea data-field="x_ds_item" name="x_ds_item" id="x_ds_item" cols="35" rows="4" placeholder="<?php echo $itembaseconhecimento->ds_item->PlaceHolder ?>"<?php echo $itembaseconhecimento->ds_item->EditAttributes() ?>><?php echo $itembaseconhecimento->ds_item->EditValue ?></textarea>
</span>
<?php echo $itembaseconhecimento->ds_item->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($itembaseconhecimento->ic_situacao->Visible) { // ic_situacao ?>
	<tr id="r_ic_situacao">
		<td><span id="elh_itembaseconhecimento_ic_situacao"><?php echo $itembaseconhecimento->ic_situacao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $itembaseconhecimento->ic_situacao->CellAttributes() ?>>
<span id="el_itembaseconhecimento_ic_situacao" class="control-group">
<select data-field="x_ic_situacao" id="x_ic_situacao" name="x_ic_situacao"<?php echo $itembaseconhecimento->ic_situacao->EditAttributes() ?>>
<?php
if (is_array($itembaseconhecimento->ic_situacao->EditValue)) {
	$arwrk = $itembaseconhecimento->ic_situacao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($itembaseconhecimento->ic_situacao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $itembaseconhecimento->ic_situacao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($itembaseconhecimento->ds_acoes->Visible) { // ds_acoes ?>
	<tr id="r_ds_acoes">
		<td><span id="elh_itembaseconhecimento_ds_acoes"><?php echo $itembaseconhecimento->ds_acoes->FldCaption() ?></span></td>
		<td<?php echo $itembaseconhecimento->ds_acoes->CellAttributes() ?>>
<span id="el_itembaseconhecimento_ds_acoes" class="control-group">
<textarea data-field="x_ds_acoes" name="x_ds_acoes" id="x_ds_acoes" cols="35" rows="4" placeholder="<?php echo $itembaseconhecimento->ds_acoes->PlaceHolder ?>"<?php echo $itembaseconhecimento->ds_acoes->EditAttributes() ?>><?php echo $itembaseconhecimento->ds_acoes->EditValue ?></textarea>
</span>
<?php echo $itembaseconhecimento->ds_acoes->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_itembaseconhecimento2">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_itembaseconhecimentoadd2" class="table table-bordered table-striped">
<?php if ($itembaseconhecimento->nu_processoCobit->Visible) { // nu_processoCobit ?>
	<tr id="r_nu_processoCobit">
		<td><span id="elh_itembaseconhecimento_nu_processoCobit"><?php echo $itembaseconhecimento->nu_processoCobit->FldCaption() ?></span></td>
		<td<?php echo $itembaseconhecimento->nu_processoCobit->CellAttributes() ?>>
<span id="el_itembaseconhecimento_nu_processoCobit" class="control-group">
<select data-field="x_nu_processoCobit" id="x_nu_processoCobit" name="x_nu_processoCobit"<?php echo $itembaseconhecimento->nu_processoCobit->EditAttributes() ?>>
<?php
if (is_array($itembaseconhecimento->nu_processoCobit->EditValue)) {
	$arwrk = $itembaseconhecimento->nu_processoCobit->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($itembaseconhecimento->nu_processoCobit->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$itembaseconhecimento->nu_processoCobit) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fitembaseconhecimentoadd.Lists["x_nu_processoCobit"].Options = <?php echo (is_array($itembaseconhecimento->nu_processoCobit->EditValue)) ? ew_ArrayToJson($itembaseconhecimento->nu_processoCobit->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $itembaseconhecimento->nu_processoCobit->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_itembaseconhecimento3">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_itembaseconhecimentoadd3" class="table table-bordered table-striped">
<?php if ($itembaseconhecimento->nu_prospecto->Visible) { // nu_prospecto ?>
	<tr id="r_nu_prospecto">
		<td><span id="elh_itembaseconhecimento_nu_prospecto"><?php echo $itembaseconhecimento->nu_prospecto->FldCaption() ?></span></td>
		<td<?php echo $itembaseconhecimento->nu_prospecto->CellAttributes() ?>>
<span id="el_itembaseconhecimento_nu_prospecto" class="control-group">
<select data-field="x_nu_prospecto" id="x_nu_prospecto" name="x_nu_prospecto"<?php echo $itembaseconhecimento->nu_prospecto->EditAttributes() ?>>
<?php
if (is_array($itembaseconhecimento->nu_prospecto->EditValue)) {
	$arwrk = $itembaseconhecimento->nu_prospecto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($itembaseconhecimento->nu_prospecto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fitembaseconhecimentoadd.Lists["x_nu_prospecto"].Options = <?php echo (is_array($itembaseconhecimento->nu_prospecto->EditValue)) ? ew_ArrayToJson($itembaseconhecimento->nu_prospecto->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $itembaseconhecimento->nu_prospecto->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($itembaseconhecimento->nu_projeto->Visible) { // nu_projeto ?>
	<tr id="r_nu_projeto">
		<td><span id="elh_itembaseconhecimento_nu_projeto"><?php echo $itembaseconhecimento->nu_projeto->FldCaption() ?></span></td>
		<td<?php echo $itembaseconhecimento->nu_projeto->CellAttributes() ?>>
<span id="el_itembaseconhecimento_nu_projeto" class="control-group">
<select data-field="x_nu_projeto" id="x_nu_projeto" name="x_nu_projeto"<?php echo $itembaseconhecimento->nu_projeto->EditAttributes() ?>>
<?php
if (is_array($itembaseconhecimento->nu_projeto->EditValue)) {
	$arwrk = $itembaseconhecimento->nu_projeto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($itembaseconhecimento->nu_projeto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fitembaseconhecimentoadd.Lists["x_nu_projeto"].Options = <?php echo (is_array($itembaseconhecimento->nu_projeto->EditValue)) ? ew_ArrayToJson($itembaseconhecimento->nu_projeto->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $itembaseconhecimento->nu_projeto->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_itembaseconhecimento4">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_itembaseconhecimentoadd4" class="table table-bordered table-striped">
<?php if ($itembaseconhecimento->nu_sistema->Visible) { // nu_sistema ?>
	<tr id="r_nu_sistema">
		<td><span id="elh_itembaseconhecimento_nu_sistema"><?php echo $itembaseconhecimento->nu_sistema->FldCaption() ?></span></td>
		<td<?php echo $itembaseconhecimento->nu_sistema->CellAttributes() ?>>
<span id="el_itembaseconhecimento_nu_sistema" class="control-group">
<?php $itembaseconhecimento->nu_sistema->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_modulo','x_nu_uc']); " . @$itembaseconhecimento->nu_sistema->EditAttrs["onchange"]; ?>
<select data-field="x_nu_sistema" id="x_nu_sistema" name="x_nu_sistema"<?php echo $itembaseconhecimento->nu_sistema->EditAttributes() ?>>
<?php
if (is_array($itembaseconhecimento->nu_sistema->EditValue)) {
	$arwrk = $itembaseconhecimento->nu_sistema->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($itembaseconhecimento->nu_sistema->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$itembaseconhecimento->nu_sistema) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fitembaseconhecimentoadd.Lists["x_nu_sistema"].Options = <?php echo (is_array($itembaseconhecimento->nu_sistema->EditValue)) ? ew_ArrayToJson($itembaseconhecimento->nu_sistema->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $itembaseconhecimento->nu_sistema->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($itembaseconhecimento->nu_modulo->Visible) { // nu_modulo ?>
	<tr id="r_nu_modulo">
		<td><span id="elh_itembaseconhecimento_nu_modulo"><?php echo $itembaseconhecimento->nu_modulo->FldCaption() ?></span></td>
		<td<?php echo $itembaseconhecimento->nu_modulo->CellAttributes() ?>>
<span id="el_itembaseconhecimento_nu_modulo" class="control-group">
<select data-field="x_nu_modulo" id="x_nu_modulo" name="x_nu_modulo"<?php echo $itembaseconhecimento->nu_modulo->EditAttributes() ?>>
<?php
if (is_array($itembaseconhecimento->nu_modulo->EditValue)) {
	$arwrk = $itembaseconhecimento->nu_modulo->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($itembaseconhecimento->nu_modulo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fitembaseconhecimentoadd.Lists["x_nu_modulo"].Options = <?php echo (is_array($itembaseconhecimento->nu_modulo->EditValue)) ? ew_ArrayToJson($itembaseconhecimento->nu_modulo->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $itembaseconhecimento->nu_modulo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($itembaseconhecimento->nu_uc->Visible) { // nu_uc ?>
	<tr id="r_nu_uc">
		<td><span id="elh_itembaseconhecimento_nu_uc"><?php echo $itembaseconhecimento->nu_uc->FldCaption() ?></span></td>
		<td<?php echo $itembaseconhecimento->nu_uc->CellAttributes() ?>>
<span id="el_itembaseconhecimento_nu_uc" class="control-group">
<select data-field="x_nu_uc" id="x_nu_uc" name="x_nu_uc"<?php echo $itembaseconhecimento->nu_uc->EditAttributes() ?>>
<?php
if (is_array($itembaseconhecimento->nu_uc->EditValue)) {
	$arwrk = $itembaseconhecimento->nu_uc->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($itembaseconhecimento->nu_uc->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$itembaseconhecimento->nu_uc) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fitembaseconhecimentoadd.Lists["x_nu_uc"].Options = <?php echo (is_array($itembaseconhecimento->nu_uc->EditValue)) ? ew_ArrayToJson($itembaseconhecimento->nu_uc->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $itembaseconhecimento->nu_uc->CustomMsg ?></td>
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
fitembaseconhecimentoadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$itembaseconhecimento_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$itembaseconhecimento_add->Page_Terminate();
?>
