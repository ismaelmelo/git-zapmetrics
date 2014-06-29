<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "tpmetricainfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "tpcontagemgridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$tpmetrica_add = NULL; // Initialize page object first

class ctpmetrica_add extends ctpmetrica {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'tpmetrica';

	// Page object name
	var $PageObjName = 'tpmetrica_add';

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

		// Table object (tpmetrica)
		if (!isset($GLOBALS["tpmetrica"])) {
			$GLOBALS["tpmetrica"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tpmetrica"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tpmetrica', TRUE);

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
			$this->Page_Terminate("tpmetricalist.php");
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
			if (@$_GET["nu_tpMetrica"] != "") {
				$this->nu_tpMetrica->setQueryStringValue($_GET["nu_tpMetrica"]);
				$this->setKey("nu_tpMetrica", $this->nu_tpMetrica->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_tpMetrica", ""); // Clear key
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
					$this->Page_Terminate("tpmetricalist.php"); // No matching record, return to list
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
					if (ew_GetPageName($sReturnUrl) == "tpmetricaview.php")
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
		$this->no_tpMetrica->CurrentValue = NULL;
		$this->no_tpMetrica->OldValue = $this->no_tpMetrica->CurrentValue;
		$this->ic_tpMetrica->CurrentValue = NULL;
		$this->ic_tpMetrica->OldValue = $this->ic_tpMetrica->CurrentValue;
		$this->ic_tpAplicacao->CurrentValue = NULL;
		$this->ic_tpAplicacao->OldValue = $this->ic_tpAplicacao->CurrentValue;
		$this->ds_helpTela->CurrentValue = NULL;
		$this->ds_helpTela->OldValue = $this->ds_helpTela->CurrentValue;
		$this->ic_ativo->CurrentValue = "S";
		$this->ic_metodoEsforco->CurrentValue = "1";
		$this->ic_metodoPrazo->CurrentValue = "1";
		$this->ic_metodoCusto->CurrentValue = "1";
		$this->ic_metodoRecursos->CurrentValue = "2";
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->no_tpMetrica->FldIsDetailKey) {
			$this->no_tpMetrica->setFormValue($objForm->GetValue("x_no_tpMetrica"));
		}
		if (!$this->ic_tpMetrica->FldIsDetailKey) {
			$this->ic_tpMetrica->setFormValue($objForm->GetValue("x_ic_tpMetrica"));
		}
		if (!$this->ic_tpAplicacao->FldIsDetailKey) {
			$this->ic_tpAplicacao->setFormValue($objForm->GetValue("x_ic_tpAplicacao"));
		}
		if (!$this->ds_helpTela->FldIsDetailKey) {
			$this->ds_helpTela->setFormValue($objForm->GetValue("x_ds_helpTela"));
		}
		if (!$this->ic_ativo->FldIsDetailKey) {
			$this->ic_ativo->setFormValue($objForm->GetValue("x_ic_ativo"));
		}
		if (!$this->ic_metodoEsforco->FldIsDetailKey) {
			$this->ic_metodoEsforco->setFormValue($objForm->GetValue("x_ic_metodoEsforco"));
		}
		if (!$this->ic_metodoPrazo->FldIsDetailKey) {
			$this->ic_metodoPrazo->setFormValue($objForm->GetValue("x_ic_metodoPrazo"));
		}
		if (!$this->ic_metodoCusto->FldIsDetailKey) {
			$this->ic_metodoCusto->setFormValue($objForm->GetValue("x_ic_metodoCusto"));
		}
		if (!$this->ic_metodoRecursos->FldIsDetailKey) {
			$this->ic_metodoRecursos->setFormValue($objForm->GetValue("x_ic_metodoRecursos"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->no_tpMetrica->CurrentValue = $this->no_tpMetrica->FormValue;
		$this->ic_tpMetrica->CurrentValue = $this->ic_tpMetrica->FormValue;
		$this->ic_tpAplicacao->CurrentValue = $this->ic_tpAplicacao->FormValue;
		$this->ds_helpTela->CurrentValue = $this->ds_helpTela->FormValue;
		$this->ic_ativo->CurrentValue = $this->ic_ativo->FormValue;
		$this->ic_metodoEsforco->CurrentValue = $this->ic_metodoEsforco->FormValue;
		$this->ic_metodoPrazo->CurrentValue = $this->ic_metodoPrazo->FormValue;
		$this->ic_metodoCusto->CurrentValue = $this->ic_metodoCusto->FormValue;
		$this->ic_metodoRecursos->CurrentValue = $this->ic_metodoRecursos->FormValue;
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
		$this->nu_tpMetrica->setDbValue($rs->fields('nu_tpMetrica'));
		$this->no_tpMetrica->setDbValue($rs->fields('no_tpMetrica'));
		$this->ic_tpMetrica->setDbValue($rs->fields('ic_tpMetrica'));
		$this->ic_tpAplicacao->setDbValue($rs->fields('ic_tpAplicacao'));
		$this->ds_helpTela->setDbValue($rs->fields('ds_helpTela'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
		$this->ic_metodoEsforco->setDbValue($rs->fields('ic_metodoEsforco'));
		$this->ic_metodoPrazo->setDbValue($rs->fields('ic_metodoPrazo'));
		$this->ic_metodoCusto->setDbValue($rs->fields('ic_metodoCusto'));
		$this->ic_metodoRecursos->setDbValue($rs->fields('ic_metodoRecursos'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_tpMetrica->DbValue = $row['nu_tpMetrica'];
		$this->no_tpMetrica->DbValue = $row['no_tpMetrica'];
		$this->ic_tpMetrica->DbValue = $row['ic_tpMetrica'];
		$this->ic_tpAplicacao->DbValue = $row['ic_tpAplicacao'];
		$this->ds_helpTela->DbValue = $row['ds_helpTela'];
		$this->ic_ativo->DbValue = $row['ic_ativo'];
		$this->ic_metodoEsforco->DbValue = $row['ic_metodoEsforco'];
		$this->ic_metodoPrazo->DbValue = $row['ic_metodoPrazo'];
		$this->ic_metodoCusto->DbValue = $row['ic_metodoCusto'];
		$this->ic_metodoRecursos->DbValue = $row['ic_metodoRecursos'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_tpMetrica")) <> "")
			$this->nu_tpMetrica->CurrentValue = $this->getKey("nu_tpMetrica"); // nu_tpMetrica
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
		// nu_tpMetrica
		// no_tpMetrica
		// ic_tpMetrica
		// ic_tpAplicacao
		// ds_helpTela
		// ic_ativo
		// ic_metodoEsforco
		// ic_metodoPrazo
		// ic_metodoCusto
		// ic_metodoRecursos

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_tpMetrica
			$this->nu_tpMetrica->ViewValue = $this->nu_tpMetrica->CurrentValue;
			$this->nu_tpMetrica->ViewCustomAttributes = "";

			// no_tpMetrica
			$this->no_tpMetrica->ViewValue = $this->no_tpMetrica->CurrentValue;
			$this->no_tpMetrica->ViewCustomAttributes = "";

			// ic_tpMetrica
			if (strval($this->ic_tpMetrica->CurrentValue) <> "") {
				switch ($this->ic_tpMetrica->CurrentValue) {
					case $this->ic_tpMetrica->FldTagValue(1):
						$this->ic_tpMetrica->ViewValue = $this->ic_tpMetrica->FldTagCaption(1) <> "" ? $this->ic_tpMetrica->FldTagCaption(1) : $this->ic_tpMetrica->CurrentValue;
						break;
					case $this->ic_tpMetrica->FldTagValue(2):
						$this->ic_tpMetrica->ViewValue = $this->ic_tpMetrica->FldTagCaption(2) <> "" ? $this->ic_tpMetrica->FldTagCaption(2) : $this->ic_tpMetrica->CurrentValue;
						break;
					case $this->ic_tpMetrica->FldTagValue(3):
						$this->ic_tpMetrica->ViewValue = $this->ic_tpMetrica->FldTagCaption(3) <> "" ? $this->ic_tpMetrica->FldTagCaption(3) : $this->ic_tpMetrica->CurrentValue;
						break;
					default:
						$this->ic_tpMetrica->ViewValue = $this->ic_tpMetrica->CurrentValue;
				}
			} else {
				$this->ic_tpMetrica->ViewValue = NULL;
			}
			$this->ic_tpMetrica->ViewCustomAttributes = "";

			// ic_tpAplicacao
			if (strval($this->ic_tpAplicacao->CurrentValue) <> "") {
				$this->ic_tpAplicacao->ViewValue = "";
				$arwrk = explode(",", strval($this->ic_tpAplicacao->CurrentValue));
				$cnt = count($arwrk);
				for ($ari = 0; $ari < $cnt; $ari++) {
					switch (trim($arwrk[$ari])) {
						case $this->ic_tpAplicacao->FldTagValue(1):
							$this->ic_tpAplicacao->ViewValue .= $this->ic_tpAplicacao->FldTagCaption(1) <> "" ? $this->ic_tpAplicacao->FldTagCaption(1) : trim($arwrk[$ari]);
							break;
						case $this->ic_tpAplicacao->FldTagValue(2):
							$this->ic_tpAplicacao->ViewValue .= $this->ic_tpAplicacao->FldTagCaption(2) <> "" ? $this->ic_tpAplicacao->FldTagCaption(2) : trim($arwrk[$ari]);
							break;
						case $this->ic_tpAplicacao->FldTagValue(3):
							$this->ic_tpAplicacao->ViewValue .= $this->ic_tpAplicacao->FldTagCaption(3) <> "" ? $this->ic_tpAplicacao->FldTagCaption(3) : trim($arwrk[$ari]);
							break;
						default:
							$this->ic_tpAplicacao->ViewValue .= trim($arwrk[$ari]);
					}
					if ($ari < $cnt-1) $this->ic_tpAplicacao->ViewValue .= ew_ViewOptionSeparator($ari);
				}
			} else {
				$this->ic_tpAplicacao->ViewValue = NULL;
			}
			$this->ic_tpAplicacao->ViewCustomAttributes = "";

			// ds_helpTela
			$this->ds_helpTela->ViewValue = $this->ds_helpTela->CurrentValue;
			$this->ds_helpTela->ViewCustomAttributes = "";

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

			// ic_metodoEsforco
			if (strval($this->ic_metodoEsforco->CurrentValue) <> "") {
				switch ($this->ic_metodoEsforco->CurrentValue) {
					case $this->ic_metodoEsforco->FldTagValue(1):
						$this->ic_metodoEsforco->ViewValue = $this->ic_metodoEsforco->FldTagCaption(1) <> "" ? $this->ic_metodoEsforco->FldTagCaption(1) : $this->ic_metodoEsforco->CurrentValue;
						break;
					case $this->ic_metodoEsforco->FldTagValue(2):
						$this->ic_metodoEsforco->ViewValue = $this->ic_metodoEsforco->FldTagCaption(2) <> "" ? $this->ic_metodoEsforco->FldTagCaption(2) : $this->ic_metodoEsforco->CurrentValue;
						break;
					default:
						$this->ic_metodoEsforco->ViewValue = $this->ic_metodoEsforco->CurrentValue;
				}
			} else {
				$this->ic_metodoEsforco->ViewValue = NULL;
			}
			$this->ic_metodoEsforco->ViewCustomAttributes = "";

			// ic_metodoPrazo
			if (strval($this->ic_metodoPrazo->CurrentValue) <> "") {
				switch ($this->ic_metodoPrazo->CurrentValue) {
					case $this->ic_metodoPrazo->FldTagValue(1):
						$this->ic_metodoPrazo->ViewValue = $this->ic_metodoPrazo->FldTagCaption(1) <> "" ? $this->ic_metodoPrazo->FldTagCaption(1) : $this->ic_metodoPrazo->CurrentValue;
						break;
					case $this->ic_metodoPrazo->FldTagValue(2):
						$this->ic_metodoPrazo->ViewValue = $this->ic_metodoPrazo->FldTagCaption(2) <> "" ? $this->ic_metodoPrazo->FldTagCaption(2) : $this->ic_metodoPrazo->CurrentValue;
						break;
					case $this->ic_metodoPrazo->FldTagValue(3):
						$this->ic_metodoPrazo->ViewValue = $this->ic_metodoPrazo->FldTagCaption(3) <> "" ? $this->ic_metodoPrazo->FldTagCaption(3) : $this->ic_metodoPrazo->CurrentValue;
						break;
					case $this->ic_metodoPrazo->FldTagValue(4):
						$this->ic_metodoPrazo->ViewValue = $this->ic_metodoPrazo->FldTagCaption(4) <> "" ? $this->ic_metodoPrazo->FldTagCaption(4) : $this->ic_metodoPrazo->CurrentValue;
						break;
					default:
						$this->ic_metodoPrazo->ViewValue = $this->ic_metodoPrazo->CurrentValue;
				}
			} else {
				$this->ic_metodoPrazo->ViewValue = NULL;
			}
			$this->ic_metodoPrazo->ViewCustomAttributes = "";

			// ic_metodoCusto
			if (strval($this->ic_metodoCusto->CurrentValue) <> "") {
				switch ($this->ic_metodoCusto->CurrentValue) {
					case $this->ic_metodoCusto->FldTagValue(1):
						$this->ic_metodoCusto->ViewValue = $this->ic_metodoCusto->FldTagCaption(1) <> "" ? $this->ic_metodoCusto->FldTagCaption(1) : $this->ic_metodoCusto->CurrentValue;
						break;
					case $this->ic_metodoCusto->FldTagValue(2):
						$this->ic_metodoCusto->ViewValue = $this->ic_metodoCusto->FldTagCaption(2) <> "" ? $this->ic_metodoCusto->FldTagCaption(2) : $this->ic_metodoCusto->CurrentValue;
						break;
					default:
						$this->ic_metodoCusto->ViewValue = $this->ic_metodoCusto->CurrentValue;
				}
			} else {
				$this->ic_metodoCusto->ViewValue = NULL;
			}
			$this->ic_metodoCusto->ViewCustomAttributes = "";

			// ic_metodoRecursos
			if (strval($this->ic_metodoRecursos->CurrentValue) <> "") {
				switch ($this->ic_metodoRecursos->CurrentValue) {
					case $this->ic_metodoRecursos->FldTagValue(1):
						$this->ic_metodoRecursos->ViewValue = $this->ic_metodoRecursos->FldTagCaption(1) <> "" ? $this->ic_metodoRecursos->FldTagCaption(1) : $this->ic_metodoRecursos->CurrentValue;
						break;
					case $this->ic_metodoRecursos->FldTagValue(2):
						$this->ic_metodoRecursos->ViewValue = $this->ic_metodoRecursos->FldTagCaption(2) <> "" ? $this->ic_metodoRecursos->FldTagCaption(2) : $this->ic_metodoRecursos->CurrentValue;
						break;
					default:
						$this->ic_metodoRecursos->ViewValue = $this->ic_metodoRecursos->CurrentValue;
				}
			} else {
				$this->ic_metodoRecursos->ViewValue = NULL;
			}
			$this->ic_metodoRecursos->ViewCustomAttributes = "";

			// no_tpMetrica
			$this->no_tpMetrica->LinkCustomAttributes = "";
			$this->no_tpMetrica->HrefValue = "";
			$this->no_tpMetrica->TooltipValue = "";

			// ic_tpMetrica
			$this->ic_tpMetrica->LinkCustomAttributes = "";
			$this->ic_tpMetrica->HrefValue = "";
			$this->ic_tpMetrica->TooltipValue = "";

			// ic_tpAplicacao
			$this->ic_tpAplicacao->LinkCustomAttributes = "";
			$this->ic_tpAplicacao->HrefValue = "";
			$this->ic_tpAplicacao->TooltipValue = "";

			// ds_helpTela
			$this->ds_helpTela->LinkCustomAttributes = "";
			$this->ds_helpTela->HrefValue = "";
			$this->ds_helpTela->TooltipValue = "";

			// ic_ativo
			$this->ic_ativo->LinkCustomAttributes = "";
			$this->ic_ativo->HrefValue = "";
			$this->ic_ativo->TooltipValue = "";

			// ic_metodoEsforco
			$this->ic_metodoEsforco->LinkCustomAttributes = "";
			$this->ic_metodoEsforco->HrefValue = "";
			$this->ic_metodoEsforco->TooltipValue = "";

			// ic_metodoPrazo
			$this->ic_metodoPrazo->LinkCustomAttributes = "";
			$this->ic_metodoPrazo->HrefValue = "";
			$this->ic_metodoPrazo->TooltipValue = "";

			// ic_metodoCusto
			$this->ic_metodoCusto->LinkCustomAttributes = "";
			$this->ic_metodoCusto->HrefValue = "";
			$this->ic_metodoCusto->TooltipValue = "";

			// ic_metodoRecursos
			$this->ic_metodoRecursos->LinkCustomAttributes = "";
			$this->ic_metodoRecursos->HrefValue = "";
			$this->ic_metodoRecursos->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// no_tpMetrica
			$this->no_tpMetrica->EditCustomAttributes = "";
			$this->no_tpMetrica->EditValue = ew_HtmlEncode($this->no_tpMetrica->CurrentValue);
			$this->no_tpMetrica->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_tpMetrica->FldCaption()));

			// ic_tpMetrica
			$this->ic_tpMetrica->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_tpMetrica->FldTagValue(1), $this->ic_tpMetrica->FldTagCaption(1) <> "" ? $this->ic_tpMetrica->FldTagCaption(1) : $this->ic_tpMetrica->FldTagValue(1));
			$arwrk[] = array($this->ic_tpMetrica->FldTagValue(2), $this->ic_tpMetrica->FldTagCaption(2) <> "" ? $this->ic_tpMetrica->FldTagCaption(2) : $this->ic_tpMetrica->FldTagValue(2));
			$arwrk[] = array($this->ic_tpMetrica->FldTagValue(3), $this->ic_tpMetrica->FldTagCaption(3) <> "" ? $this->ic_tpMetrica->FldTagCaption(3) : $this->ic_tpMetrica->FldTagValue(3));
			$this->ic_tpMetrica->EditValue = $arwrk;

			// ic_tpAplicacao
			$this->ic_tpAplicacao->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_tpAplicacao->FldTagValue(1), $this->ic_tpAplicacao->FldTagCaption(1) <> "" ? $this->ic_tpAplicacao->FldTagCaption(1) : $this->ic_tpAplicacao->FldTagValue(1));
			$arwrk[] = array($this->ic_tpAplicacao->FldTagValue(2), $this->ic_tpAplicacao->FldTagCaption(2) <> "" ? $this->ic_tpAplicacao->FldTagCaption(2) : $this->ic_tpAplicacao->FldTagValue(2));
			$arwrk[] = array($this->ic_tpAplicacao->FldTagValue(3), $this->ic_tpAplicacao->FldTagCaption(3) <> "" ? $this->ic_tpAplicacao->FldTagCaption(3) : $this->ic_tpAplicacao->FldTagValue(3));
			$this->ic_tpAplicacao->EditValue = $arwrk;

			// ds_helpTela
			$this->ds_helpTela->EditCustomAttributes = "";
			$this->ds_helpTela->EditValue = $this->ds_helpTela->CurrentValue;
			$this->ds_helpTela->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_helpTela->FldCaption()));

			// ic_ativo
			$this->ic_ativo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_ativo->FldTagValue(1), $this->ic_ativo->FldTagCaption(1) <> "" ? $this->ic_ativo->FldTagCaption(1) : $this->ic_ativo->FldTagValue(1));
			$arwrk[] = array($this->ic_ativo->FldTagValue(2), $this->ic_ativo->FldTagCaption(2) <> "" ? $this->ic_ativo->FldTagCaption(2) : $this->ic_ativo->FldTagValue(2));
			$this->ic_ativo->EditValue = $arwrk;

			// ic_metodoEsforco
			$this->ic_metodoEsforco->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_metodoEsforco->FldTagValue(1), $this->ic_metodoEsforco->FldTagCaption(1) <> "" ? $this->ic_metodoEsforco->FldTagCaption(1) : $this->ic_metodoEsforco->FldTagValue(1));
			$arwrk[] = array($this->ic_metodoEsforco->FldTagValue(2), $this->ic_metodoEsforco->FldTagCaption(2) <> "" ? $this->ic_metodoEsforco->FldTagCaption(2) : $this->ic_metodoEsforco->FldTagValue(2));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_metodoEsforco->EditValue = $arwrk;

			// ic_metodoPrazo
			$this->ic_metodoPrazo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_metodoPrazo->FldTagValue(1), $this->ic_metodoPrazo->FldTagCaption(1) <> "" ? $this->ic_metodoPrazo->FldTagCaption(1) : $this->ic_metodoPrazo->FldTagValue(1));
			$arwrk[] = array($this->ic_metodoPrazo->FldTagValue(2), $this->ic_metodoPrazo->FldTagCaption(2) <> "" ? $this->ic_metodoPrazo->FldTagCaption(2) : $this->ic_metodoPrazo->FldTagValue(2));
			$arwrk[] = array($this->ic_metodoPrazo->FldTagValue(3), $this->ic_metodoPrazo->FldTagCaption(3) <> "" ? $this->ic_metodoPrazo->FldTagCaption(3) : $this->ic_metodoPrazo->FldTagValue(3));
			$arwrk[] = array($this->ic_metodoPrazo->FldTagValue(4), $this->ic_metodoPrazo->FldTagCaption(4) <> "" ? $this->ic_metodoPrazo->FldTagCaption(4) : $this->ic_metodoPrazo->FldTagValue(4));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_metodoPrazo->EditValue = $arwrk;

			// ic_metodoCusto
			$this->ic_metodoCusto->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_metodoCusto->FldTagValue(1), $this->ic_metodoCusto->FldTagCaption(1) <> "" ? $this->ic_metodoCusto->FldTagCaption(1) : $this->ic_metodoCusto->FldTagValue(1));
			$arwrk[] = array($this->ic_metodoCusto->FldTagValue(2), $this->ic_metodoCusto->FldTagCaption(2) <> "" ? $this->ic_metodoCusto->FldTagCaption(2) : $this->ic_metodoCusto->FldTagValue(2));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_metodoCusto->EditValue = $arwrk;

			// ic_metodoRecursos
			$this->ic_metodoRecursos->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_metodoRecursos->FldTagValue(1), $this->ic_metodoRecursos->FldTagCaption(1) <> "" ? $this->ic_metodoRecursos->FldTagCaption(1) : $this->ic_metodoRecursos->FldTagValue(1));
			$arwrk[] = array($this->ic_metodoRecursos->FldTagValue(2), $this->ic_metodoRecursos->FldTagCaption(2) <> "" ? $this->ic_metodoRecursos->FldTagCaption(2) : $this->ic_metodoRecursos->FldTagValue(2));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_metodoRecursos->EditValue = $arwrk;

			// Edit refer script
			// no_tpMetrica

			$this->no_tpMetrica->HrefValue = "";

			// ic_tpMetrica
			$this->ic_tpMetrica->HrefValue = "";

			// ic_tpAplicacao
			$this->ic_tpAplicacao->HrefValue = "";

			// ds_helpTela
			$this->ds_helpTela->HrefValue = "";

			// ic_ativo
			$this->ic_ativo->HrefValue = "";

			// ic_metodoEsforco
			$this->ic_metodoEsforco->HrefValue = "";

			// ic_metodoPrazo
			$this->ic_metodoPrazo->HrefValue = "";

			// ic_metodoCusto
			$this->ic_metodoCusto->HrefValue = "";

			// ic_metodoRecursos
			$this->ic_metodoRecursos->HrefValue = "";
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
		if (!$this->no_tpMetrica->FldIsDetailKey && !is_null($this->no_tpMetrica->FormValue) && $this->no_tpMetrica->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_tpMetrica->FldCaption());
		}
		if ($this->ic_tpMetrica->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_tpMetrica->FldCaption());
		}
		if ($this->ic_tpAplicacao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_tpAplicacao->FldCaption());
		}
		if ($this->ic_ativo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_ativo->FldCaption());
		}
		if (!$this->ic_metodoEsforco->FldIsDetailKey && !is_null($this->ic_metodoEsforco->FormValue) && $this->ic_metodoEsforco->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_metodoEsforco->FldCaption());
		}
		if (!$this->ic_metodoPrazo->FldIsDetailKey && !is_null($this->ic_metodoPrazo->FormValue) && $this->ic_metodoPrazo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_metodoPrazo->FldCaption());
		}
		if (!$this->ic_metodoCusto->FldIsDetailKey && !is_null($this->ic_metodoCusto->FormValue) && $this->ic_metodoCusto->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_metodoCusto->FldCaption());
		}
		if (!$this->ic_metodoRecursos->FldIsDetailKey && !is_null($this->ic_metodoRecursos->FormValue) && $this->ic_metodoRecursos->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_metodoRecursos->FldCaption());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("tpcontagem", $DetailTblVar) && $GLOBALS["tpcontagem"]->DetailAdd) {
			if (!isset($GLOBALS["tpcontagem_grid"])) $GLOBALS["tpcontagem_grid"] = new ctpcontagem_grid(); // get detail page object
			$GLOBALS["tpcontagem_grid"]->ValidateGridForm();
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

		// no_tpMetrica
		$this->no_tpMetrica->SetDbValueDef($rsnew, $this->no_tpMetrica->CurrentValue, "", FALSE);

		// ic_tpMetrica
		$this->ic_tpMetrica->SetDbValueDef($rsnew, $this->ic_tpMetrica->CurrentValue, "", FALSE);

		// ic_tpAplicacao
		$this->ic_tpAplicacao->SetDbValueDef($rsnew, $this->ic_tpAplicacao->CurrentValue, NULL, FALSE);

		// ds_helpTela
		$this->ds_helpTela->SetDbValueDef($rsnew, $this->ds_helpTela->CurrentValue, NULL, FALSE);

		// ic_ativo
		$this->ic_ativo->SetDbValueDef($rsnew, $this->ic_ativo->CurrentValue, NULL, FALSE);

		// ic_metodoEsforco
		$this->ic_metodoEsforco->SetDbValueDef($rsnew, $this->ic_metodoEsforco->CurrentValue, NULL, FALSE);

		// ic_metodoPrazo
		$this->ic_metodoPrazo->SetDbValueDef($rsnew, $this->ic_metodoPrazo->CurrentValue, NULL, FALSE);

		// ic_metodoCusto
		$this->ic_metodoCusto->SetDbValueDef($rsnew, $this->ic_metodoCusto->CurrentValue, NULL, FALSE);

		// ic_metodoRecursos
		$this->ic_metodoRecursos->SetDbValueDef($rsnew, $this->ic_metodoRecursos->CurrentValue, NULL, FALSE);

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
			$this->nu_tpMetrica->setDbValue($conn->Insert_ID());
			$rsnew['nu_tpMetrica'] = $this->nu_tpMetrica->DbValue;
		}

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("tpcontagem", $DetailTblVar) && $GLOBALS["tpcontagem"]->DetailAdd) {
				$GLOBALS["tpcontagem"]->nu_tpMetrica->setSessionValue($this->nu_tpMetrica->CurrentValue); // Set master key
				if (!isset($GLOBALS["tpcontagem_grid"])) $GLOBALS["tpcontagem_grid"] = new ctpcontagem_grid(); // Get detail page object
				$AddRow = $GLOBALS["tpcontagem_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["tpcontagem"]->nu_tpMetrica->setSessionValue(""); // Clear master key if insert failed
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
			if (in_array("tpcontagem", $DetailTblVar)) {
				if (!isset($GLOBALS["tpcontagem_grid"]))
					$GLOBALS["tpcontagem_grid"] = new ctpcontagem_grid;
				if ($GLOBALS["tpcontagem_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["tpcontagem_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["tpcontagem_grid"]->CurrentMode = "add";
					$GLOBALS["tpcontagem_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["tpcontagem_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["tpcontagem_grid"]->setStartRecordNumber(1);
					$GLOBALS["tpcontagem_grid"]->nu_tpMetrica->FldIsDetailKey = TRUE;
					$GLOBALS["tpcontagem_grid"]->nu_tpMetrica->CurrentValue = $this->nu_tpMetrica->CurrentValue;
					$GLOBALS["tpcontagem_grid"]->nu_tpMetrica->setSessionValue($GLOBALS["tpcontagem_grid"]->nu_tpMetrica->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "tpmetricalist.php", $this->TableVar);
		$PageCaption = ($this->CurrentAction == "C") ? $Language->Phrase("Copy") : $Language->Phrase("Add");
		$Breadcrumb->Add("add", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'tpmetrica';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'tpmetrica';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_tpMetrica'];

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
if (!isset($tpmetrica_add)) $tpmetrica_add = new ctpmetrica_add();

// Page init
$tpmetrica_add->Page_Init();

// Page main
$tpmetrica_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tpmetrica_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var tpmetrica_add = new ew_Page("tpmetrica_add");
tpmetrica_add.PageID = "add"; // Page ID
var EW_PAGE_ID = tpmetrica_add.PageID; // For backward compatibility

// Form object
var ftpmetricaadd = new ew_Form("ftpmetricaadd");

// Validate form
ftpmetricaadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_no_tpMetrica");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tpmetrica->no_tpMetrica->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_tpMetrica");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tpmetrica->ic_tpMetrica->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_tpAplicacao[]");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tpmetrica->ic_tpAplicacao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_ativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tpmetrica->ic_ativo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_metodoEsforco");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tpmetrica->ic_metodoEsforco->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_metodoPrazo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tpmetrica->ic_metodoPrazo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_metodoCusto");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tpmetrica->ic_metodoCusto->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_metodoRecursos");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tpmetrica->ic_metodoRecursos->FldCaption()) ?>");

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
ftpmetricaadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftpmetricaadd.ValidateRequired = true;
<?php } else { ?>
ftpmetricaadd.ValidateRequired = false; 
<?php } ?>

// Multi-Page properties
ftpmetricaadd.MultiPage = new ew_MultiPage("ftpmetricaadd",
	[["x_no_tpMetrica",1],["x_ic_tpMetrica",1],["x_ic_tpAplicacao",1],["x_ds_helpTela",1],["x_ic_ativo",1],["x_ic_metodoEsforco",2],["x_ic_metodoPrazo",2],["x_ic_metodoCusto",2],["x_ic_metodoRecursos",2]]
);

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $tpmetrica_add->ShowPageHeader(); ?>
<?php
$tpmetrica_add->ShowMessage();
?>
<form name="ftpmetricaadd" id="ftpmetricaadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="tpmetrica">
<input type="hidden" name="a_add" id="a_add" value="A">
<table class="ewStdTable"><tbody><tr><td>
<div class="tabbable" id="tpmetrica_add">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_tpmetrica1" data-toggle="tab"><?php echo $tpmetrica->PageCaption(1) ?></a></li>
		<li><a href="#tab_tpmetrica2" data-toggle="tab"><?php echo $tpmetrica->PageCaption(2) ?></a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab_tpmetrica1">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_tpmetricaadd1" class="table table-bordered table-striped">
<?php if ($tpmetrica->no_tpMetrica->Visible) { // no_tpMetrica ?>
	<tr id="r_no_tpMetrica">
		<td><span id="elh_tpmetrica_no_tpMetrica"><?php echo $tpmetrica->no_tpMetrica->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tpmetrica->no_tpMetrica->CellAttributes() ?>>
<span id="el_tpmetrica_no_tpMetrica" class="control-group">
<input type="text" data-field="x_no_tpMetrica" name="x_no_tpMetrica" id="x_no_tpMetrica" size="30" maxlength="75" placeholder="<?php echo $tpmetrica->no_tpMetrica->PlaceHolder ?>" value="<?php echo $tpmetrica->no_tpMetrica->EditValue ?>"<?php echo $tpmetrica->no_tpMetrica->EditAttributes() ?>>
</span>
<?php echo $tpmetrica->no_tpMetrica->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tpmetrica->ic_tpMetrica->Visible) { // ic_tpMetrica ?>
	<tr id="r_ic_tpMetrica">
		<td><span id="elh_tpmetrica_ic_tpMetrica"><?php echo $tpmetrica->ic_tpMetrica->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tpmetrica->ic_tpMetrica->CellAttributes() ?>>
<span id="el_tpmetrica_ic_tpMetrica" class="control-group">
<div id="tp_x_ic_tpMetrica" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_tpMetrica" id="x_ic_tpMetrica" value="{value}"<?php echo $tpmetrica->ic_tpMetrica->EditAttributes() ?>></div>
<div id="dsl_x_ic_tpMetrica" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $tpmetrica->ic_tpMetrica->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tpmetrica->ic_tpMetrica->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_tpMetrica" name="x_ic_tpMetrica" id="x_ic_tpMetrica_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $tpmetrica->ic_tpMetrica->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $tpmetrica->ic_tpMetrica->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tpmetrica->ic_tpAplicacao->Visible) { // ic_tpAplicacao ?>
	<tr id="r_ic_tpAplicacao">
		<td><span id="elh_tpmetrica_ic_tpAplicacao"><?php echo $tpmetrica->ic_tpAplicacao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tpmetrica->ic_tpAplicacao->CellAttributes() ?>>
<span id="el_tpmetrica_ic_tpAplicacao" class="control-group">
<select data-field="x_ic_tpAplicacao" id="x_ic_tpAplicacao[]" name="x_ic_tpAplicacao[]" multiple="multiple"<?php echo $tpmetrica->ic_tpAplicacao->EditAttributes() ?>>
<?php
if (is_array($tpmetrica->ic_tpAplicacao->EditValue)) {
	$arwrk = $tpmetrica->ic_tpAplicacao->EditValue;
	$armultiwrk= explode(",", strval($tpmetrica->ic_tpAplicacao->CurrentValue));
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = "";
		$cnt = count($armultiwrk);
		for ($ari = 0; $ari < $cnt; $ari++) {
			if (strval($arwrk[$rowcntwrk][0]) == trim(strval($armultiwrk[$ari]))) {
				$selwrk = " selected=\"selected\"";
				if ($selwrk <> "") $emptywrk = FALSE;
				break;
			}
		}	
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
<?php echo $tpmetrica->ic_tpAplicacao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tpmetrica->ds_helpTela->Visible) { // ds_helpTela ?>
	<tr id="r_ds_helpTela">
		<td><span id="elh_tpmetrica_ds_helpTela"><?php echo $tpmetrica->ds_helpTela->FldCaption() ?></span></td>
		<td<?php echo $tpmetrica->ds_helpTela->CellAttributes() ?>>
<span id="el_tpmetrica_ds_helpTela" class="control-group">
<textarea data-field="x_ds_helpTela" name="x_ds_helpTela" id="x_ds_helpTela" cols="35" rows="4" placeholder="<?php echo $tpmetrica->ds_helpTela->PlaceHolder ?>"<?php echo $tpmetrica->ds_helpTela->EditAttributes() ?>><?php echo $tpmetrica->ds_helpTela->EditValue ?></textarea>
</span>
<?php echo $tpmetrica->ds_helpTela->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tpmetrica->ic_ativo->Visible) { // ic_ativo ?>
	<tr id="r_ic_ativo">
		<td><span id="elh_tpmetrica_ic_ativo"><?php echo $tpmetrica->ic_ativo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tpmetrica->ic_ativo->CellAttributes() ?>>
<span id="el_tpmetrica_ic_ativo" class="control-group">
<div id="tp_x_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_ativo" id="x_ic_ativo" value="{value}"<?php echo $tpmetrica->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $tpmetrica->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tpmetrica->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x_ic_ativo" id="x_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $tpmetrica->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $tpmetrica->ic_ativo->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_tpmetrica2">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_tpmetricaadd2" class="table table-bordered table-striped">
<?php if ($tpmetrica->ic_metodoEsforco->Visible) { // ic_metodoEsforco ?>
	<tr id="r_ic_metodoEsforco">
		<td><span id="elh_tpmetrica_ic_metodoEsforco"><?php echo $tpmetrica->ic_metodoEsforco->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tpmetrica->ic_metodoEsforco->CellAttributes() ?>>
<span id="el_tpmetrica_ic_metodoEsforco" class="control-group">
<select data-field="x_ic_metodoEsforco" id="x_ic_metodoEsforco" name="x_ic_metodoEsforco"<?php echo $tpmetrica->ic_metodoEsforco->EditAttributes() ?>>
<?php
if (is_array($tpmetrica->ic_metodoEsforco->EditValue)) {
	$arwrk = $tpmetrica->ic_metodoEsforco->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tpmetrica->ic_metodoEsforco->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $tpmetrica->ic_metodoEsforco->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tpmetrica->ic_metodoPrazo->Visible) { // ic_metodoPrazo ?>
	<tr id="r_ic_metodoPrazo">
		<td><span id="elh_tpmetrica_ic_metodoPrazo"><?php echo $tpmetrica->ic_metodoPrazo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tpmetrica->ic_metodoPrazo->CellAttributes() ?>>
<span id="el_tpmetrica_ic_metodoPrazo" class="control-group">
<select data-field="x_ic_metodoPrazo" id="x_ic_metodoPrazo" name="x_ic_metodoPrazo"<?php echo $tpmetrica->ic_metodoPrazo->EditAttributes() ?>>
<?php
if (is_array($tpmetrica->ic_metodoPrazo->EditValue)) {
	$arwrk = $tpmetrica->ic_metodoPrazo->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tpmetrica->ic_metodoPrazo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $tpmetrica->ic_metodoPrazo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tpmetrica->ic_metodoCusto->Visible) { // ic_metodoCusto ?>
	<tr id="r_ic_metodoCusto">
		<td><span id="elh_tpmetrica_ic_metodoCusto"><?php echo $tpmetrica->ic_metodoCusto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tpmetrica->ic_metodoCusto->CellAttributes() ?>>
<span id="el_tpmetrica_ic_metodoCusto" class="control-group">
<select data-field="x_ic_metodoCusto" id="x_ic_metodoCusto" name="x_ic_metodoCusto"<?php echo $tpmetrica->ic_metodoCusto->EditAttributes() ?>>
<?php
if (is_array($tpmetrica->ic_metodoCusto->EditValue)) {
	$arwrk = $tpmetrica->ic_metodoCusto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tpmetrica->ic_metodoCusto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $tpmetrica->ic_metodoCusto->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tpmetrica->ic_metodoRecursos->Visible) { // ic_metodoRecursos ?>
	<tr id="r_ic_metodoRecursos">
		<td><span id="elh_tpmetrica_ic_metodoRecursos"><?php echo $tpmetrica->ic_metodoRecursos->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tpmetrica->ic_metodoRecursos->CellAttributes() ?>>
<span id="el_tpmetrica_ic_metodoRecursos" class="control-group">
<select data-field="x_ic_metodoRecursos" id="x_ic_metodoRecursos" name="x_ic_metodoRecursos"<?php echo $tpmetrica->ic_metodoRecursos->EditAttributes() ?>>
<?php
if (is_array($tpmetrica->ic_metodoRecursos->EditValue)) {
	$arwrk = $tpmetrica->ic_metodoRecursos->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tpmetrica->ic_metodoRecursos->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $tpmetrica->ic_metodoRecursos->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
	</div>
</div>
</td></tr></tbody></table>
<?php
	if (in_array("tpcontagem", explode(",", $tpmetrica->getCurrentDetailTable())) && $tpcontagem->DetailAdd) {
?>
<?php include_once "tpcontagemgrid.php" ?>
<?php } ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
ftpmetricaadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$tpmetrica_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tpmetrica_add->Page_Terminate();
?>
