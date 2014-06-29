<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "riscoprojetoinfo.php" ?>
<?php include_once "projetoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$riscoprojeto_add = NULL; // Initialize page object first

class criscoprojeto_add extends criscoprojeto {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'riscoprojeto';

	// Page object name
	var $PageObjName = 'riscoprojeto_add';

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

		// Table object (riscoprojeto)
		if (!isset($GLOBALS["riscoprojeto"])) {
			$GLOBALS["riscoprojeto"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["riscoprojeto"];
		}

		// Table object (projeto)
		if (!isset($GLOBALS['projeto'])) $GLOBALS['projeto'] = new cprojeto();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'riscoprojeto', TRUE);

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
			$this->Page_Terminate("riscoprojetolist.php");
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
			if (@$_GET["nu_riscoProjeto"] != "") {
				$this->nu_riscoProjeto->setQueryStringValue($_GET["nu_riscoProjeto"]);
				$this->setKey("nu_riscoProjeto", $this->nu_riscoProjeto->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_riscoProjeto", ""); // Clear key
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
					$this->Page_Terminate("riscoprojetolist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "riscoprojetoview.php")
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
		$this->nu_projeto->CurrentValue = NULL;
		$this->nu_projeto->OldValue = $this->nu_projeto->CurrentValue;
		$this->nu_catRisco->CurrentValue = NULL;
		$this->nu_catRisco->OldValue = $this->nu_catRisco->CurrentValue;
		$this->ic_tpRisco->CurrentValue = NULL;
		$this->ic_tpRisco->OldValue = $this->ic_tpRisco->CurrentValue;
		$this->ds_risco->CurrentValue = NULL;
		$this->ds_risco->OldValue = $this->ds_risco->CurrentValue;
		$this->ds_consequencia->CurrentValue = NULL;
		$this->ds_consequencia->OldValue = $this->ds_consequencia->CurrentValue;
		$this->nu_probabilidade->CurrentValue = NULL;
		$this->nu_probabilidade->OldValue = $this->nu_probabilidade->CurrentValue;
		$this->nu_impacto->CurrentValue = NULL;
		$this->nu_impacto->OldValue = $this->nu_impacto->CurrentValue;
		$this->nu_severidade->CurrentValue = NULL;
		$this->nu_severidade->OldValue = $this->nu_severidade->CurrentValue;
		$this->nu_acao->CurrentValue = NULL;
		$this->nu_acao->OldValue = $this->nu_acao->CurrentValue;
		$this->ds_gatilho->CurrentValue = NULL;
		$this->ds_gatilho->OldValue = $this->ds_gatilho->CurrentValue;
		$this->ds_respRisco->CurrentValue = NULL;
		$this->ds_respRisco->OldValue = $this->ds_respRisco->CurrentValue;
		$this->nu_usuarioResp->CurrentValue = NULL;
		$this->nu_usuarioResp->OldValue = $this->nu_usuarioResp->CurrentValue;
		$this->ic_stRisco->CurrentValue = "A";
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_projeto->FldIsDetailKey) {
			$this->nu_projeto->setFormValue($objForm->GetValue("x_nu_projeto"));
		}
		if (!$this->nu_catRisco->FldIsDetailKey) {
			$this->nu_catRisco->setFormValue($objForm->GetValue("x_nu_catRisco"));
		}
		if (!$this->ic_tpRisco->FldIsDetailKey) {
			$this->ic_tpRisco->setFormValue($objForm->GetValue("x_ic_tpRisco"));
		}
		if (!$this->ds_risco->FldIsDetailKey) {
			$this->ds_risco->setFormValue($objForm->GetValue("x_ds_risco"));
		}
		if (!$this->ds_consequencia->FldIsDetailKey) {
			$this->ds_consequencia->setFormValue($objForm->GetValue("x_ds_consequencia"));
		}
		if (!$this->nu_probabilidade->FldIsDetailKey) {
			$this->nu_probabilidade->setFormValue($objForm->GetValue("x_nu_probabilidade"));
		}
		if (!$this->nu_impacto->FldIsDetailKey) {
			$this->nu_impacto->setFormValue($objForm->GetValue("x_nu_impacto"));
		}
		if (!$this->nu_severidade->FldIsDetailKey) {
			$this->nu_severidade->setFormValue($objForm->GetValue("x_nu_severidade"));
		}
		if (!$this->nu_acao->FldIsDetailKey) {
			$this->nu_acao->setFormValue($objForm->GetValue("x_nu_acao"));
		}
		if (!$this->ds_gatilho->FldIsDetailKey) {
			$this->ds_gatilho->setFormValue($objForm->GetValue("x_ds_gatilho"));
		}
		if (!$this->ds_respRisco->FldIsDetailKey) {
			$this->ds_respRisco->setFormValue($objForm->GetValue("x_ds_respRisco"));
		}
		if (!$this->nu_usuarioResp->FldIsDetailKey) {
			$this->nu_usuarioResp->setFormValue($objForm->GetValue("x_nu_usuarioResp"));
		}
		if (!$this->ic_stRisco->FldIsDetailKey) {
			$this->ic_stRisco->setFormValue($objForm->GetValue("x_ic_stRisco"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->nu_projeto->CurrentValue = $this->nu_projeto->FormValue;
		$this->nu_catRisco->CurrentValue = $this->nu_catRisco->FormValue;
		$this->ic_tpRisco->CurrentValue = $this->ic_tpRisco->FormValue;
		$this->ds_risco->CurrentValue = $this->ds_risco->FormValue;
		$this->ds_consequencia->CurrentValue = $this->ds_consequencia->FormValue;
		$this->nu_probabilidade->CurrentValue = $this->nu_probabilidade->FormValue;
		$this->nu_impacto->CurrentValue = $this->nu_impacto->FormValue;
		$this->nu_severidade->CurrentValue = $this->nu_severidade->FormValue;
		$this->nu_acao->CurrentValue = $this->nu_acao->FormValue;
		$this->ds_gatilho->CurrentValue = $this->ds_gatilho->FormValue;
		$this->ds_respRisco->CurrentValue = $this->ds_respRisco->FormValue;
		$this->nu_usuarioResp->CurrentValue = $this->nu_usuarioResp->FormValue;
		$this->ic_stRisco->CurrentValue = $this->ic_stRisco->FormValue;
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
		$this->nu_riscoProjeto->setDbValue($rs->fields('nu_riscoProjeto'));
		$this->nu_projeto->setDbValue($rs->fields('nu_projeto'));
		$this->nu_catRisco->setDbValue($rs->fields('nu_catRisco'));
		$this->ic_tpRisco->setDbValue($rs->fields('ic_tpRisco'));
		$this->ds_risco->setDbValue($rs->fields('ds_risco'));
		$this->ds_consequencia->setDbValue($rs->fields('ds_consequencia'));
		$this->nu_probabilidade->setDbValue($rs->fields('nu_probabilidade'));
		$this->nu_impacto->setDbValue($rs->fields('nu_impacto'));
		$this->nu_severidade->setDbValue($rs->fields('nu_severidade'));
		$this->nu_acao->setDbValue($rs->fields('nu_acao'));
		$this->ds_gatilho->setDbValue($rs->fields('ds_gatilho'));
		$this->ds_respRisco->setDbValue($rs->fields('ds_respRisco'));
		$this->nu_usuarioResp->setDbValue($rs->fields('nu_usuarioResp'));
		$this->ic_stRisco->setDbValue($rs->fields('ic_stRisco'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_riscoProjeto->DbValue = $row['nu_riscoProjeto'];
		$this->nu_projeto->DbValue = $row['nu_projeto'];
		$this->nu_catRisco->DbValue = $row['nu_catRisco'];
		$this->ic_tpRisco->DbValue = $row['ic_tpRisco'];
		$this->ds_risco->DbValue = $row['ds_risco'];
		$this->ds_consequencia->DbValue = $row['ds_consequencia'];
		$this->nu_probabilidade->DbValue = $row['nu_probabilidade'];
		$this->nu_impacto->DbValue = $row['nu_impacto'];
		$this->nu_severidade->DbValue = $row['nu_severidade'];
		$this->nu_acao->DbValue = $row['nu_acao'];
		$this->ds_gatilho->DbValue = $row['ds_gatilho'];
		$this->ds_respRisco->DbValue = $row['ds_respRisco'];
		$this->nu_usuarioResp->DbValue = $row['nu_usuarioResp'];
		$this->ic_stRisco->DbValue = $row['ic_stRisco'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_riscoProjeto")) <> "")
			$this->nu_riscoProjeto->CurrentValue = $this->getKey("nu_riscoProjeto"); // nu_riscoProjeto
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
		// nu_riscoProjeto
		// nu_projeto
		// nu_catRisco
		// ic_tpRisco
		// ds_risco
		// ds_consequencia
		// nu_probabilidade
		// nu_impacto
		// nu_severidade
		// nu_acao
		// ds_gatilho
		// ds_respRisco
		// nu_usuarioResp
		// ic_stRisco

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_riscoProjeto
			$this->nu_riscoProjeto->ViewValue = $this->nu_riscoProjeto->CurrentValue;
			$this->nu_riscoProjeto->ViewCustomAttributes = "";

			// nu_projeto
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
			$sSqlWrk .= " ORDER BY [nu_projeto] DESC";
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
			$this->nu_projeto->ViewCustomAttributes = "";

			// nu_catRisco
			if (strval($this->nu_catRisco->CurrentValue) <> "") {
				$sFilterWrk = "[nu_catRisco]" . ew_SearchString("=", $this->nu_catRisco->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_catRisco], [no_catRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[catriscoproj]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_catRisco, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_catRisco] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_catRisco->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_catRisco->ViewValue = $this->nu_catRisco->CurrentValue;
				}
			} else {
				$this->nu_catRisco->ViewValue = NULL;
			}
			$this->nu_catRisco->ViewCustomAttributes = "";

			// ic_tpRisco
			if (strval($this->ic_tpRisco->CurrentValue) <> "") {
				switch ($this->ic_tpRisco->CurrentValue) {
					case $this->ic_tpRisco->FldTagValue(1):
						$this->ic_tpRisco->ViewValue = $this->ic_tpRisco->FldTagCaption(1) <> "" ? $this->ic_tpRisco->FldTagCaption(1) : $this->ic_tpRisco->CurrentValue;
						break;
					case $this->ic_tpRisco->FldTagValue(2):
						$this->ic_tpRisco->ViewValue = $this->ic_tpRisco->FldTagCaption(2) <> "" ? $this->ic_tpRisco->FldTagCaption(2) : $this->ic_tpRisco->CurrentValue;
						break;
					default:
						$this->ic_tpRisco->ViewValue = $this->ic_tpRisco->CurrentValue;
				}
			} else {
				$this->ic_tpRisco->ViewValue = NULL;
			}
			$this->ic_tpRisco->ViewCustomAttributes = "";

			// ds_risco
			$this->ds_risco->ViewValue = $this->ds_risco->CurrentValue;
			$this->ds_risco->ViewCustomAttributes = "";

			// ds_consequencia
			$this->ds_consequencia->ViewValue = $this->ds_consequencia->CurrentValue;
			$this->ds_consequencia->ViewCustomAttributes = "";

			// nu_probabilidade
			if (strval($this->nu_probabilidade->CurrentValue) <> "") {
				$sFilterWrk = "[nu_probOcoRisco]" . ew_SearchString("=", $this->nu_probabilidade->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_probOcoRisco], [no_probOcoRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[probocorisco]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_probabilidade, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_valor] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_probabilidade->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_probabilidade->ViewValue = $this->nu_probabilidade->CurrentValue;
				}
			} else {
				$this->nu_probabilidade->ViewValue = NULL;
			}
			$this->nu_probabilidade->ViewCustomAttributes = "";

			// nu_impacto
			if (strval($this->nu_impacto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_impactoRisco]" . ew_SearchString("=", $this->nu_impacto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_impactoRisco], [no_impactoRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[impactorisco]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_impacto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_valor] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_impacto->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_impacto->ViewValue = $this->nu_impacto->CurrentValue;
				}
			} else {
				$this->nu_impacto->ViewValue = NULL;
			}
			$this->nu_impacto->ViewCustomAttributes = "";

			// nu_severidade
			$this->nu_severidade->ViewValue = $this->nu_severidade->CurrentValue;
			$this->nu_severidade->ViewCustomAttributes = "";

			// nu_acao
			if (strval($this->nu_acao->CurrentValue) <> "") {
				$sFilterWrk = "[nu_acaoRisco]" . ew_SearchString("=", $this->nu_acao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_acaoRisco], [no_acaoRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[acaorisco]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_acao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_acaoRisco] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_acao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_acao->ViewValue = $this->nu_acao->CurrentValue;
				}
			} else {
				$this->nu_acao->ViewValue = NULL;
			}
			$this->nu_acao->ViewCustomAttributes = "";

			// ds_gatilho
			$this->ds_gatilho->ViewValue = $this->ds_gatilho->CurrentValue;
			$this->ds_gatilho->ViewCustomAttributes = "";

			// ds_respRisco
			$this->ds_respRisco->ViewValue = $this->ds_respRisco->CurrentValue;
			$this->ds_respRisco->ViewCustomAttributes = "";

			// nu_usuarioResp
			if (strval($this->nu_usuarioResp->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioResp->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioResp, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioResp->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioResp->ViewValue = $this->nu_usuarioResp->CurrentValue;
				}
			} else {
				$this->nu_usuarioResp->ViewValue = NULL;
			}
			$this->nu_usuarioResp->ViewCustomAttributes = "";

			// ic_stRisco
			if (strval($this->ic_stRisco->CurrentValue) <> "") {
				switch ($this->ic_stRisco->CurrentValue) {
					case $this->ic_stRisco->FldTagValue(1):
						$this->ic_stRisco->ViewValue = $this->ic_stRisco->FldTagCaption(1) <> "" ? $this->ic_stRisco->FldTagCaption(1) : $this->ic_stRisco->CurrentValue;
						break;
					case $this->ic_stRisco->FldTagValue(2):
						$this->ic_stRisco->ViewValue = $this->ic_stRisco->FldTagCaption(2) <> "" ? $this->ic_stRisco->FldTagCaption(2) : $this->ic_stRisco->CurrentValue;
						break;
					default:
						$this->ic_stRisco->ViewValue = $this->ic_stRisco->CurrentValue;
				}
			} else {
				$this->ic_stRisco->ViewValue = NULL;
			}
			$this->ic_stRisco->ViewCustomAttributes = "";

			// nu_projeto
			$this->nu_projeto->LinkCustomAttributes = "";
			$this->nu_projeto->HrefValue = "";
			$this->nu_projeto->TooltipValue = "";

			// nu_catRisco
			$this->nu_catRisco->LinkCustomAttributes = "";
			$this->nu_catRisco->HrefValue = "";
			$this->nu_catRisco->TooltipValue = "";

			// ic_tpRisco
			$this->ic_tpRisco->LinkCustomAttributes = "";
			$this->ic_tpRisco->HrefValue = "";
			$this->ic_tpRisco->TooltipValue = "";

			// ds_risco
			$this->ds_risco->LinkCustomAttributes = "";
			$this->ds_risco->HrefValue = "";
			$this->ds_risco->TooltipValue = "";

			// ds_consequencia
			$this->ds_consequencia->LinkCustomAttributes = "";
			$this->ds_consequencia->HrefValue = "";
			$this->ds_consequencia->TooltipValue = "";

			// nu_probabilidade
			$this->nu_probabilidade->LinkCustomAttributes = "";
			$this->nu_probabilidade->HrefValue = "";
			$this->nu_probabilidade->TooltipValue = "";

			// nu_impacto
			$this->nu_impacto->LinkCustomAttributes = "";
			$this->nu_impacto->HrefValue = "";
			$this->nu_impacto->TooltipValue = "";

			// nu_severidade
			$this->nu_severidade->LinkCustomAttributes = "";
			$this->nu_severidade->HrefValue = "";
			$this->nu_severidade->TooltipValue = "";

			// nu_acao
			$this->nu_acao->LinkCustomAttributes = "";
			$this->nu_acao->HrefValue = "";
			$this->nu_acao->TooltipValue = "";

			// ds_gatilho
			$this->ds_gatilho->LinkCustomAttributes = "";
			$this->ds_gatilho->HrefValue = "";
			$this->ds_gatilho->TooltipValue = "";

			// ds_respRisco
			$this->ds_respRisco->LinkCustomAttributes = "";
			$this->ds_respRisco->HrefValue = "";
			$this->ds_respRisco->TooltipValue = "";

			// nu_usuarioResp
			$this->nu_usuarioResp->LinkCustomAttributes = "";
			$this->nu_usuarioResp->HrefValue = "";
			$this->nu_usuarioResp->TooltipValue = "";

			// ic_stRisco
			$this->ic_stRisco->LinkCustomAttributes = "";
			$this->ic_stRisco->HrefValue = "";
			$this->ic_stRisco->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nu_projeto
			$this->nu_projeto->EditCustomAttributes = "";
			if ($this->nu_projeto->getSessionValue() <> "") {
				$this->nu_projeto->CurrentValue = $this->nu_projeto->getSessionValue();
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
			$sSqlWrk .= " ORDER BY [nu_projeto] DESC";
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
			$this->nu_projeto->ViewCustomAttributes = "";
			} else {
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_projeto], [no_projeto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[projeto]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_projeto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_projeto] DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_projeto->EditValue = $arwrk;
			}

			// nu_catRisco
			$this->nu_catRisco->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_catRisco], [no_catRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[catriscoproj]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_catRisco, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_catRisco] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_catRisco->EditValue = $arwrk;

			// ic_tpRisco
			$this->ic_tpRisco->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_tpRisco->FldTagValue(1), $this->ic_tpRisco->FldTagCaption(1) <> "" ? $this->ic_tpRisco->FldTagCaption(1) : $this->ic_tpRisco->FldTagValue(1));
			$arwrk[] = array($this->ic_tpRisco->FldTagValue(2), $this->ic_tpRisco->FldTagCaption(2) <> "" ? $this->ic_tpRisco->FldTagCaption(2) : $this->ic_tpRisco->FldTagValue(2));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_tpRisco->EditValue = $arwrk;

			// ds_risco
			$this->ds_risco->EditCustomAttributes = "";
			$this->ds_risco->EditValue = $this->ds_risco->CurrentValue;
			$this->ds_risco->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_risco->FldCaption()));

			// ds_consequencia
			$this->ds_consequencia->EditCustomAttributes = "";
			$this->ds_consequencia->EditValue = $this->ds_consequencia->CurrentValue;
			$this->ds_consequencia->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_consequencia->FldCaption()));

			// nu_probabilidade
			$this->nu_probabilidade->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_probOcoRisco], [no_probOcoRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[probocorisco]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_probabilidade, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_valor] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_probabilidade->EditValue = $arwrk;

			// nu_impacto
			$this->nu_impacto->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_impactoRisco], [no_impactoRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[impactorisco]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_impacto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_valor] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_impacto->EditValue = $arwrk;

			// nu_severidade
			$this->nu_severidade->EditCustomAttributes = "";
			$this->nu_severidade->EditValue = ew_HtmlEncode($this->nu_severidade->CurrentValue);
			$this->nu_severidade->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_severidade->FldCaption()));

			// nu_acao
			$this->nu_acao->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_acaoRisco], [no_acaoRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [ic_tpRisco] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[acaorisco]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_acao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_acaoRisco] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_acao->EditValue = $arwrk;

			// ds_gatilho
			$this->ds_gatilho->EditCustomAttributes = "";
			$this->ds_gatilho->EditValue = $this->ds_gatilho->CurrentValue;
			$this->ds_gatilho->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_gatilho->FldCaption()));

			// ds_respRisco
			$this->ds_respRisco->EditCustomAttributes = "";
			$this->ds_respRisco->EditValue = $this->ds_respRisco->CurrentValue;
			$this->ds_respRisco->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_respRisco->FldCaption()));

			// nu_usuarioResp
			// ic_stRisco

			$this->ic_stRisco->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_stRisco->FldTagValue(1), $this->ic_stRisco->FldTagCaption(1) <> "" ? $this->ic_stRisco->FldTagCaption(1) : $this->ic_stRisco->FldTagValue(1));
			$arwrk[] = array($this->ic_stRisco->FldTagValue(2), $this->ic_stRisco->FldTagCaption(2) <> "" ? $this->ic_stRisco->FldTagCaption(2) : $this->ic_stRisco->FldTagValue(2));
			$this->ic_stRisco->EditValue = $arwrk;

			// Edit refer script
			// nu_projeto

			$this->nu_projeto->HrefValue = "";

			// nu_catRisco
			$this->nu_catRisco->HrefValue = "";

			// ic_tpRisco
			$this->ic_tpRisco->HrefValue = "";

			// ds_risco
			$this->ds_risco->HrefValue = "";

			// ds_consequencia
			$this->ds_consequencia->HrefValue = "";

			// nu_probabilidade
			$this->nu_probabilidade->HrefValue = "";

			// nu_impacto
			$this->nu_impacto->HrefValue = "";

			// nu_severidade
			$this->nu_severidade->HrefValue = "";

			// nu_acao
			$this->nu_acao->HrefValue = "";

			// ds_gatilho
			$this->ds_gatilho->HrefValue = "";

			// ds_respRisco
			$this->ds_respRisco->HrefValue = "";

			// nu_usuarioResp
			$this->nu_usuarioResp->HrefValue = "";

			// ic_stRisco
			$this->ic_stRisco->HrefValue = "";
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
		if (!$this->nu_projeto->FldIsDetailKey && !is_null($this->nu_projeto->FormValue) && $this->nu_projeto->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_projeto->FldCaption());
		}
		if (!$this->nu_catRisco->FldIsDetailKey && !is_null($this->nu_catRisco->FormValue) && $this->nu_catRisco->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_catRisco->FldCaption());
		}
		if (!$this->ic_tpRisco->FldIsDetailKey && !is_null($this->ic_tpRisco->FormValue) && $this->ic_tpRisco->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_tpRisco->FldCaption());
		}
		if (!$this->nu_probabilidade->FldIsDetailKey && !is_null($this->nu_probabilidade->FormValue) && $this->nu_probabilidade->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_probabilidade->FldCaption());
		}
		if (!ew_CheckInteger($this->nu_severidade->FormValue)) {
			ew_AddMessage($gsFormError, $this->nu_severidade->FldErrMsg());
		}
		if (!$this->nu_acao->FldIsDetailKey && !is_null($this->nu_acao->FormValue) && $this->nu_acao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_acao->FldCaption());
		}
		if ($this->ic_stRisco->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_stRisco->FldCaption());
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

		// nu_projeto
		$this->nu_projeto->SetDbValueDef($rsnew, $this->nu_projeto->CurrentValue, 0, FALSE);

		// nu_catRisco
		$this->nu_catRisco->SetDbValueDef($rsnew, $this->nu_catRisco->CurrentValue, 0, FALSE);

		// ic_tpRisco
		$this->ic_tpRisco->SetDbValueDef($rsnew, $this->ic_tpRisco->CurrentValue, "", FALSE);

		// ds_risco
		$this->ds_risco->SetDbValueDef($rsnew, $this->ds_risco->CurrentValue, NULL, FALSE);

		// ds_consequencia
		$this->ds_consequencia->SetDbValueDef($rsnew, $this->ds_consequencia->CurrentValue, NULL, FALSE);

		// nu_probabilidade
		$this->nu_probabilidade->SetDbValueDef($rsnew, $this->nu_probabilidade->CurrentValue, NULL, FALSE);

		// nu_impacto
		$this->nu_impacto->SetDbValueDef($rsnew, $this->nu_impacto->CurrentValue, NULL, FALSE);

		// nu_severidade
		$this->nu_severidade->SetDbValueDef($rsnew, $this->nu_severidade->CurrentValue, NULL, FALSE);

		// nu_acao
		$this->nu_acao->SetDbValueDef($rsnew, $this->nu_acao->CurrentValue, NULL, FALSE);

		// ds_gatilho
		$this->ds_gatilho->SetDbValueDef($rsnew, $this->ds_gatilho->CurrentValue, NULL, FALSE);

		// ds_respRisco
		$this->ds_respRisco->SetDbValueDef($rsnew, $this->ds_respRisco->CurrentValue, NULL, FALSE);

		// nu_usuarioResp
		$this->nu_usuarioResp->SetDbValueDef($rsnew, CurrentUserID(), NULL);
		$rsnew['nu_usuarioResp'] = &$this->nu_usuarioResp->DbValue;

		// ic_stRisco
		$this->ic_stRisco->SetDbValueDef($rsnew, $this->ic_stRisco->CurrentValue, NULL, FALSE);

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
			$this->nu_riscoProjeto->setDbValue($conn->Insert_ID());
			$rsnew['nu_riscoProjeto'] = $this->nu_riscoProjeto->DbValue;
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
			if ($sMasterTblVar == "projeto") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_projeto"] <> "") {
					$GLOBALS["projeto"]->nu_projeto->setQueryStringValue($_GET["nu_projeto"]);
					$this->nu_projeto->setQueryStringValue($GLOBALS["projeto"]->nu_projeto->QueryStringValue);
					$this->nu_projeto->setSessionValue($this->nu_projeto->QueryStringValue);
					if (!is_numeric($GLOBALS["projeto"]->nu_projeto->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "projeto") {
				if ($this->nu_projeto->QueryStringValue == "") $this->nu_projeto->setSessionValue("");
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "riscoprojetolist.php", $this->TableVar);
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
if (!isset($riscoprojeto_add)) $riscoprojeto_add = new criscoprojeto_add();

// Page init
$riscoprojeto_add->Page_Init();

// Page main
$riscoprojeto_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$riscoprojeto_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var riscoprojeto_add = new ew_Page("riscoprojeto_add");
riscoprojeto_add.PageID = "add"; // Page ID
var EW_PAGE_ID = riscoprojeto_add.PageID; // For backward compatibility

// Form object
var friscoprojetoadd = new ew_Form("friscoprojetoadd");

// Validate form
friscoprojetoadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_projeto");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($riscoprojeto->nu_projeto->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_catRisco");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($riscoprojeto->nu_catRisco->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_tpRisco");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($riscoprojeto->ic_tpRisco->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_probabilidade");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($riscoprojeto->nu_probabilidade->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_severidade");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($riscoprojeto->nu_severidade->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nu_acao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($riscoprojeto->nu_acao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_stRisco");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($riscoprojeto->ic_stRisco->FldCaption()) ?>");

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
friscoprojetoadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
friscoprojetoadd.ValidateRequired = true;
<?php } else { ?>
friscoprojetoadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
friscoprojetoadd.Lists["x_nu_projeto"] = {"LinkField":"x_nu_projeto","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_projeto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
friscoprojetoadd.Lists["x_nu_catRisco"] = {"LinkField":"x_nu_catRisco","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_catRisco","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
friscoprojetoadd.Lists["x_nu_probabilidade"] = {"LinkField":"x_nu_probOcoRisco","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_probOcoRisco","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
friscoprojetoadd.Lists["x_nu_impacto"] = {"LinkField":"x_nu_impactoRisco","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_impactoRisco","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
friscoprojetoadd.Lists["x_nu_acao"] = {"LinkField":"x_nu_acaoRisco","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_acaoRisco","","",""],"ParentFields":["x_ic_tpRisco"],"FilterFields":["x_ic_tpRisco"],"Options":[]};
friscoprojetoadd.Lists["x_nu_usuarioResp"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $riscoprojeto_add->ShowPageHeader(); ?>
<?php
$riscoprojeto_add->ShowMessage();
?>
<form name="friscoprojetoadd" id="friscoprojetoadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="riscoprojeto">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_riscoprojetoadd" class="table table-bordered table-striped">
<?php if ($riscoprojeto->nu_projeto->Visible) { // nu_projeto ?>
	<tr id="r_nu_projeto">
		<td><span id="elh_riscoprojeto_nu_projeto"><?php echo $riscoprojeto->nu_projeto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $riscoprojeto->nu_projeto->CellAttributes() ?>>
<?php if ($riscoprojeto->nu_projeto->getSessionValue() <> "") { ?>
<span<?php echo $riscoprojeto->nu_projeto->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_projeto->ViewValue ?></span>
<input type="hidden" id="x_nu_projeto" name="x_nu_projeto" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_projeto->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_nu_projeto" id="x_nu_projeto" name="x_nu_projeto"<?php echo $riscoprojeto->nu_projeto->EditAttributes() ?>>
<?php
if (is_array($riscoprojeto->nu_projeto->EditValue)) {
	$arwrk = $riscoprojeto->nu_projeto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($riscoprojeto->nu_projeto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
friscoprojetoadd.Lists["x_nu_projeto"].Options = <?php echo (is_array($riscoprojeto->nu_projeto->EditValue)) ? ew_ArrayToJson($riscoprojeto->nu_projeto->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<?php echo $riscoprojeto->nu_projeto->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($riscoprojeto->nu_catRisco->Visible) { // nu_catRisco ?>
	<tr id="r_nu_catRisco">
		<td><span id="elh_riscoprojeto_nu_catRisco"><?php echo $riscoprojeto->nu_catRisco->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $riscoprojeto->nu_catRisco->CellAttributes() ?>>
<span id="el_riscoprojeto_nu_catRisco" class="control-group">
<select data-field="x_nu_catRisco" id="x_nu_catRisco" name="x_nu_catRisco"<?php echo $riscoprojeto->nu_catRisco->EditAttributes() ?>>
<?php
if (is_array($riscoprojeto->nu_catRisco->EditValue)) {
	$arwrk = $riscoprojeto->nu_catRisco->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($riscoprojeto->nu_catRisco->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if (AllowAdd(CurrentProjectID() . "catriscoproj")) { ?>
&nbsp;<a id="aol_x_nu_catRisco" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_nu_catRisco',url:'catriscoprojaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $riscoprojeto->nu_catRisco->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
friscoprojetoadd.Lists["x_nu_catRisco"].Options = <?php echo (is_array($riscoprojeto->nu_catRisco->EditValue)) ? ew_ArrayToJson($riscoprojeto->nu_catRisco->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $riscoprojeto->nu_catRisco->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($riscoprojeto->ic_tpRisco->Visible) { // ic_tpRisco ?>
	<tr id="r_ic_tpRisco">
		<td><span id="elh_riscoprojeto_ic_tpRisco"><?php echo $riscoprojeto->ic_tpRisco->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $riscoprojeto->ic_tpRisco->CellAttributes() ?>>
<span id="el_riscoprojeto_ic_tpRisco" class="control-group">
<?php $riscoprojeto->ic_tpRisco->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_acao']); " . @$riscoprojeto->ic_tpRisco->EditAttrs["onchange"]; ?>
<select data-field="x_ic_tpRisco" id="x_ic_tpRisco" name="x_ic_tpRisco"<?php echo $riscoprojeto->ic_tpRisco->EditAttributes() ?>>
<?php
if (is_array($riscoprojeto->ic_tpRisco->EditValue)) {
	$arwrk = $riscoprojeto->ic_tpRisco->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($riscoprojeto->ic_tpRisco->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $riscoprojeto->ic_tpRisco->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($riscoprojeto->ds_risco->Visible) { // ds_risco ?>
	<tr id="r_ds_risco">
		<td><span id="elh_riscoprojeto_ds_risco"><?php echo $riscoprojeto->ds_risco->FldCaption() ?></span></td>
		<td<?php echo $riscoprojeto->ds_risco->CellAttributes() ?>>
<span id="el_riscoprojeto_ds_risco" class="control-group">
<textarea data-field="x_ds_risco" name="x_ds_risco" id="x_ds_risco" cols="35" rows="4" placeholder="<?php echo $riscoprojeto->ds_risco->PlaceHolder ?>"<?php echo $riscoprojeto->ds_risco->EditAttributes() ?>><?php echo $riscoprojeto->ds_risco->EditValue ?></textarea>
</span>
<?php echo $riscoprojeto->ds_risco->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($riscoprojeto->ds_consequencia->Visible) { // ds_consequencia ?>
	<tr id="r_ds_consequencia">
		<td><span id="elh_riscoprojeto_ds_consequencia"><?php echo $riscoprojeto->ds_consequencia->FldCaption() ?></span></td>
		<td<?php echo $riscoprojeto->ds_consequencia->CellAttributes() ?>>
<span id="el_riscoprojeto_ds_consequencia" class="control-group">
<textarea data-field="x_ds_consequencia" name="x_ds_consequencia" id="x_ds_consequencia" cols="35" rows="4" placeholder="<?php echo $riscoprojeto->ds_consequencia->PlaceHolder ?>"<?php echo $riscoprojeto->ds_consequencia->EditAttributes() ?>><?php echo $riscoprojeto->ds_consequencia->EditValue ?></textarea>
</span>
<?php echo $riscoprojeto->ds_consequencia->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($riscoprojeto->nu_probabilidade->Visible) { // nu_probabilidade ?>
	<tr id="r_nu_probabilidade">
		<td><span id="elh_riscoprojeto_nu_probabilidade"><?php echo $riscoprojeto->nu_probabilidade->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $riscoprojeto->nu_probabilidade->CellAttributes() ?>>
<span id="el_riscoprojeto_nu_probabilidade" class="control-group">
<select data-field="x_nu_probabilidade" id="x_nu_probabilidade" name="x_nu_probabilidade"<?php echo $riscoprojeto->nu_probabilidade->EditAttributes() ?>>
<?php
if (is_array($riscoprojeto->nu_probabilidade->EditValue)) {
	$arwrk = $riscoprojeto->nu_probabilidade->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($riscoprojeto->nu_probabilidade->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if (AllowAdd(CurrentProjectID() . "probocorisco")) { ?>
&nbsp;<a id="aol_x_nu_probabilidade" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_nu_probabilidade',url:'probocoriscoaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $riscoprojeto->nu_probabilidade->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
friscoprojetoadd.Lists["x_nu_probabilidade"].Options = <?php echo (is_array($riscoprojeto->nu_probabilidade->EditValue)) ? ew_ArrayToJson($riscoprojeto->nu_probabilidade->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $riscoprojeto->nu_probabilidade->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($riscoprojeto->nu_impacto->Visible) { // nu_impacto ?>
	<tr id="r_nu_impacto">
		<td><span id="elh_riscoprojeto_nu_impacto"><?php echo $riscoprojeto->nu_impacto->FldCaption() ?></span></td>
		<td<?php echo $riscoprojeto->nu_impacto->CellAttributes() ?>>
<span id="el_riscoprojeto_nu_impacto" class="control-group">
<select data-field="x_nu_impacto" id="x_nu_impacto" name="x_nu_impacto"<?php echo $riscoprojeto->nu_impacto->EditAttributes() ?>>
<?php
if (is_array($riscoprojeto->nu_impacto->EditValue)) {
	$arwrk = $riscoprojeto->nu_impacto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($riscoprojeto->nu_impacto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if (AllowAdd(CurrentProjectID() . "impactorisco")) { ?>
&nbsp;<a id="aol_x_nu_impacto" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_nu_impacto',url:'impactoriscoaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $riscoprojeto->nu_impacto->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
friscoprojetoadd.Lists["x_nu_impacto"].Options = <?php echo (is_array($riscoprojeto->nu_impacto->EditValue)) ? ew_ArrayToJson($riscoprojeto->nu_impacto->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $riscoprojeto->nu_impacto->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($riscoprojeto->nu_severidade->Visible) { // nu_severidade ?>
	<tr id="r_nu_severidade">
		<td><span id="elh_riscoprojeto_nu_severidade"><?php echo $riscoprojeto->nu_severidade->FldCaption() ?></span></td>
		<td<?php echo $riscoprojeto->nu_severidade->CellAttributes() ?>>
<span id="el_riscoprojeto_nu_severidade" class="control-group">
<input type="text" data-field="x_nu_severidade" name="x_nu_severidade" id="x_nu_severidade" size="30" placeholder="<?php echo $riscoprojeto->nu_severidade->PlaceHolder ?>" value="<?php echo $riscoprojeto->nu_severidade->EditValue ?>"<?php echo $riscoprojeto->nu_severidade->EditAttributes() ?>>
</span>
<?php echo $riscoprojeto->nu_severidade->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($riscoprojeto->nu_acao->Visible) { // nu_acao ?>
	<tr id="r_nu_acao">
		<td><span id="elh_riscoprojeto_nu_acao"><?php echo $riscoprojeto->nu_acao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $riscoprojeto->nu_acao->CellAttributes() ?>>
<span id="el_riscoprojeto_nu_acao" class="control-group">
<select data-field="x_nu_acao" id="x_nu_acao" name="x_nu_acao"<?php echo $riscoprojeto->nu_acao->EditAttributes() ?>>
<?php
if (is_array($riscoprojeto->nu_acao->EditValue)) {
	$arwrk = $riscoprojeto->nu_acao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($riscoprojeto->nu_acao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
friscoprojetoadd.Lists["x_nu_acao"].Options = <?php echo (is_array($riscoprojeto->nu_acao->EditValue)) ? ew_ArrayToJson($riscoprojeto->nu_acao->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $riscoprojeto->nu_acao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($riscoprojeto->ds_gatilho->Visible) { // ds_gatilho ?>
	<tr id="r_ds_gatilho">
		<td><span id="elh_riscoprojeto_ds_gatilho"><?php echo $riscoprojeto->ds_gatilho->FldCaption() ?></span></td>
		<td<?php echo $riscoprojeto->ds_gatilho->CellAttributes() ?>>
<span id="el_riscoprojeto_ds_gatilho" class="control-group">
<textarea data-field="x_ds_gatilho" name="x_ds_gatilho" id="x_ds_gatilho" cols="35" rows="4" placeholder="<?php echo $riscoprojeto->ds_gatilho->PlaceHolder ?>"<?php echo $riscoprojeto->ds_gatilho->EditAttributes() ?>><?php echo $riscoprojeto->ds_gatilho->EditValue ?></textarea>
</span>
<?php echo $riscoprojeto->ds_gatilho->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($riscoprojeto->ds_respRisco->Visible) { // ds_respRisco ?>
	<tr id="r_ds_respRisco">
		<td><span id="elh_riscoprojeto_ds_respRisco"><?php echo $riscoprojeto->ds_respRisco->FldCaption() ?></span></td>
		<td<?php echo $riscoprojeto->ds_respRisco->CellAttributes() ?>>
<span id="el_riscoprojeto_ds_respRisco" class="control-group">
<textarea data-field="x_ds_respRisco" name="x_ds_respRisco" id="x_ds_respRisco" cols="35" rows="4" placeholder="<?php echo $riscoprojeto->ds_respRisco->PlaceHolder ?>"<?php echo $riscoprojeto->ds_respRisco->EditAttributes() ?>><?php echo $riscoprojeto->ds_respRisco->EditValue ?></textarea>
</span>
<?php echo $riscoprojeto->ds_respRisco->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($riscoprojeto->ic_stRisco->Visible) { // ic_stRisco ?>
	<tr id="r_ic_stRisco">
		<td><span id="elh_riscoprojeto_ic_stRisco"><?php echo $riscoprojeto->ic_stRisco->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $riscoprojeto->ic_stRisco->CellAttributes() ?>>
<span id="el_riscoprojeto_ic_stRisco" class="control-group">
<div id="tp_x_ic_stRisco" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_stRisco" id="x_ic_stRisco" value="{value}"<?php echo $riscoprojeto->ic_stRisco->EditAttributes() ?>></div>
<div id="dsl_x_ic_stRisco" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $riscoprojeto->ic_stRisco->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($riscoprojeto->ic_stRisco->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_stRisco" name="x_ic_stRisco" id="x_ic_stRisco_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $riscoprojeto->ic_stRisco->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $riscoprojeto->ic_stRisco->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
friscoprojetoadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$riscoprojeto_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$riscoprojeto_add->Page_Terminate();
?>
