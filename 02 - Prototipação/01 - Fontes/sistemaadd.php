<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "sistemainfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "atorgridcls.php" ?>
<?php include_once "modulogridcls.php" ?>
<?php include_once "ucgridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$sistema_add = NULL; // Initialize page object first

class csistema_add extends csistema {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'sistema';

	// Page object name
	var $PageObjName = 'sistema_add';

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

		// Table object (sistema)
		if (!isset($GLOBALS["sistema"])) {
			$GLOBALS["sistema"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["sistema"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'sistema', TRUE);

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
			$this->Page_Terminate("sistemalist.php");
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
			if (@$_GET["nu_sistema"] != "") {
				$this->nu_sistema->setQueryStringValue($_GET["nu_sistema"]);
				$this->setKey("nu_sistema", $this->nu_sistema->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_sistema", ""); // Clear key
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
					$this->Page_Terminate("sistemalist.php"); // No matching record, return to list
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
					if (ew_GetPageName($sReturnUrl) == "sistemaview.php")
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
		$this->co_alternativo->CurrentValue = NULL;
		$this->co_alternativo->OldValue = $this->co_alternativo->CurrentValue;
		$this->no_sistema->CurrentValue = NULL;
		$this->no_sistema->OldValue = $this->no_sistema->CurrentValue;
		$this->ds_sistema->CurrentValue = NULL;
		$this->ds_sistema->OldValue = $this->ds_sistema->CurrentValue;
		$this->nu_fornecedor->CurrentValue = NULL;
		$this->nu_fornecedor->OldValue = $this->nu_fornecedor->CurrentValue;
		$this->ds_contatos->CurrentValue = NULL;
		$this->ds_contatos->OldValue = $this->ds_contatos->CurrentValue;
		$this->ds_comentarios->CurrentValue = NULL;
		$this->ds_comentarios->OldValue = $this->ds_comentarios->CurrentValue;
		$this->nu_stSistema->CurrentValue = NULL;
		$this->nu_stSistema->OldValue = $this->nu_stSistema->CurrentValue;
		$this->ic_ativo->CurrentValue = "S";
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->co_alternativo->FldIsDetailKey) {
			$this->co_alternativo->setFormValue($objForm->GetValue("x_co_alternativo"));
		}
		if (!$this->no_sistema->FldIsDetailKey) {
			$this->no_sistema->setFormValue($objForm->GetValue("x_no_sistema"));
		}
		if (!$this->ds_sistema->FldIsDetailKey) {
			$this->ds_sistema->setFormValue($objForm->GetValue("x_ds_sistema"));
		}
		if (!$this->nu_fornecedor->FldIsDetailKey) {
			$this->nu_fornecedor->setFormValue($objForm->GetValue("x_nu_fornecedor"));
		}
		if (!$this->ds_contatos->FldIsDetailKey) {
			$this->ds_contatos->setFormValue($objForm->GetValue("x_ds_contatos"));
		}
		if (!$this->ds_comentarios->FldIsDetailKey) {
			$this->ds_comentarios->setFormValue($objForm->GetValue("x_ds_comentarios"));
		}
		if (!$this->nu_stSistema->FldIsDetailKey) {
			$this->nu_stSistema->setFormValue($objForm->GetValue("x_nu_stSistema"));
		}
		if (!$this->ic_ativo->FldIsDetailKey) {
			$this->ic_ativo->setFormValue($objForm->GetValue("x_ic_ativo"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->co_alternativo->CurrentValue = $this->co_alternativo->FormValue;
		$this->no_sistema->CurrentValue = $this->no_sistema->FormValue;
		$this->ds_sistema->CurrentValue = $this->ds_sistema->FormValue;
		$this->nu_fornecedor->CurrentValue = $this->nu_fornecedor->FormValue;
		$this->ds_contatos->CurrentValue = $this->ds_contatos->FormValue;
		$this->ds_comentarios->CurrentValue = $this->ds_comentarios->FormValue;
		$this->nu_stSistema->CurrentValue = $this->nu_stSistema->FormValue;
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
		$this->nu_sistema->setDbValue($rs->fields('nu_sistema'));
		$this->co_alternativo->setDbValue($rs->fields('co_alternativo'));
		$this->no_sistema->setDbValue($rs->fields('no_sistema'));
		$this->ds_sistema->setDbValue($rs->fields('ds_sistema'));
		$this->nu_fornecedor->setDbValue($rs->fields('nu_fornecedor'));
		$this->ds_contatos->setDbValue($rs->fields('ds_contatos'));
		$this->ds_comentarios->setDbValue($rs->fields('ds_comentarios'));
		$this->nu_stSistema->setDbValue($rs->fields('nu_stSistema'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_sistema->DbValue = $row['nu_sistema'];
		$this->co_alternativo->DbValue = $row['co_alternativo'];
		$this->no_sistema->DbValue = $row['no_sistema'];
		$this->ds_sistema->DbValue = $row['ds_sistema'];
		$this->nu_fornecedor->DbValue = $row['nu_fornecedor'];
		$this->ds_contatos->DbValue = $row['ds_contatos'];
		$this->ds_comentarios->DbValue = $row['ds_comentarios'];
		$this->nu_stSistema->DbValue = $row['nu_stSistema'];
		$this->ic_ativo->DbValue = $row['ic_ativo'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_sistema")) <> "")
			$this->nu_sistema->CurrentValue = $this->getKey("nu_sistema"); // nu_sistema
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
		// nu_sistema
		// co_alternativo
		// no_sistema
		// ds_sistema
		// nu_fornecedor
		// ds_contatos
		// ds_comentarios
		// nu_stSistema
		// ic_ativo

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// co_alternativo
			$this->co_alternativo->ViewValue = $this->co_alternativo->CurrentValue;
			$this->co_alternativo->ViewCustomAttributes = "";

			// no_sistema
			$this->no_sistema->ViewValue = $this->no_sistema->CurrentValue;
			$this->no_sistema->ViewCustomAttributes = "";

			// ds_sistema
			$this->ds_sistema->ViewValue = $this->ds_sistema->CurrentValue;
			$this->ds_sistema->ViewCustomAttributes = "";

			// nu_fornecedor
			if (strval($this->nu_fornecedor->CurrentValue) <> "") {
				$sFilterWrk = "[nu_fornecedor]" . ew_SearchString("=", $this->nu_fornecedor->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_fornecedor], [no_fornecedor] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[fornecedor]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_fornecedor, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_fornecedor] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_fornecedor->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_fornecedor->ViewValue = $this->nu_fornecedor->CurrentValue;
				}
			} else {
				$this->nu_fornecedor->ViewValue = NULL;
			}
			$this->nu_fornecedor->ViewCustomAttributes = "";

			// ds_contatos
			$this->ds_contatos->ViewValue = $this->ds_contatos->CurrentValue;
			$this->ds_contatos->ViewCustomAttributes = "";

			// ds_comentarios
			$this->ds_comentarios->ViewValue = $this->ds_comentarios->CurrentValue;
			$this->ds_comentarios->ViewCustomAttributes = "";

			// nu_stSistema
			if (strval($this->nu_stSistema->CurrentValue) <> "") {
				$sFilterWrk = "[nu_stSistema]" . ew_SearchString("=", $this->nu_stSistema->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_stSistema], [no_stSistema] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[stsistema]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_stSistema, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_stSistema->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_stSistema->ViewValue = $this->nu_stSistema->CurrentValue;
				}
			} else {
				$this->nu_stSistema->ViewValue = NULL;
			}
			$this->nu_stSistema->ViewCustomAttributes = "";

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

			// co_alternativo
			$this->co_alternativo->LinkCustomAttributes = "";
			$this->co_alternativo->HrefValue = "";
			$this->co_alternativo->TooltipValue = "";

			// no_sistema
			$this->no_sistema->LinkCustomAttributes = "";
			$this->no_sistema->HrefValue = "";
			$this->no_sistema->TooltipValue = "";

			// ds_sistema
			$this->ds_sistema->LinkCustomAttributes = "";
			$this->ds_sistema->HrefValue = "";
			$this->ds_sistema->TooltipValue = "";

			// nu_fornecedor
			$this->nu_fornecedor->LinkCustomAttributes = "";
			$this->nu_fornecedor->HrefValue = "";
			$this->nu_fornecedor->TooltipValue = "";

			// ds_contatos
			$this->ds_contatos->LinkCustomAttributes = "";
			$this->ds_contatos->HrefValue = "";
			$this->ds_contatos->TooltipValue = "";

			// ds_comentarios
			$this->ds_comentarios->LinkCustomAttributes = "";
			$this->ds_comentarios->HrefValue = "";
			$this->ds_comentarios->TooltipValue = "";

			// nu_stSistema
			$this->nu_stSistema->LinkCustomAttributes = "";
			$this->nu_stSistema->HrefValue = "";
			$this->nu_stSistema->TooltipValue = "";

			// ic_ativo
			$this->ic_ativo->LinkCustomAttributes = "";
			$this->ic_ativo->HrefValue = "";
			$this->ic_ativo->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// co_alternativo
			$this->co_alternativo->EditCustomAttributes = "";
			$this->co_alternativo->EditValue = ew_HtmlEncode($this->co_alternativo->CurrentValue);
			$this->co_alternativo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->co_alternativo->FldCaption()));

			// no_sistema
			$this->no_sistema->EditCustomAttributes = "";
			$this->no_sistema->EditValue = ew_HtmlEncode($this->no_sistema->CurrentValue);
			$this->no_sistema->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_sistema->FldCaption()));

			// ds_sistema
			$this->ds_sistema->EditCustomAttributes = "";
			$this->ds_sistema->EditValue = $this->ds_sistema->CurrentValue;
			$this->ds_sistema->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_sistema->FldCaption()));

			// nu_fornecedor
			$this->nu_fornecedor->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_fornecedor], [no_fornecedor] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[fornecedor]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_fornecedor, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_fornecedor] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_fornecedor->EditValue = $arwrk;

			// ds_contatos
			$this->ds_contatos->EditCustomAttributes = "";
			$this->ds_contatos->EditValue = $this->ds_contatos->CurrentValue;
			$this->ds_contatos->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_contatos->FldCaption()));

			// ds_comentarios
			$this->ds_comentarios->EditCustomAttributes = "";
			$this->ds_comentarios->EditValue = $this->ds_comentarios->CurrentValue;
			$this->ds_comentarios->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_comentarios->FldCaption()));

			// nu_stSistema
			$this->nu_stSistema->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_stSistema], [no_stSistema] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[stsistema]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_stSistema, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_stSistema->EditValue = $arwrk;

			// ic_ativo
			$this->ic_ativo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_ativo->FldTagValue(1), $this->ic_ativo->FldTagCaption(1) <> "" ? $this->ic_ativo->FldTagCaption(1) : $this->ic_ativo->FldTagValue(1));
			$arwrk[] = array($this->ic_ativo->FldTagValue(2), $this->ic_ativo->FldTagCaption(2) <> "" ? $this->ic_ativo->FldTagCaption(2) : $this->ic_ativo->FldTagValue(2));
			$this->ic_ativo->EditValue = $arwrk;

			// Edit refer script
			// co_alternativo

			$this->co_alternativo->HrefValue = "";

			// no_sistema
			$this->no_sistema->HrefValue = "";

			// ds_sistema
			$this->ds_sistema->HrefValue = "";

			// nu_fornecedor
			$this->nu_fornecedor->HrefValue = "";

			// ds_contatos
			$this->ds_contatos->HrefValue = "";

			// ds_comentarios
			$this->ds_comentarios->HrefValue = "";

			// nu_stSistema
			$this->nu_stSistema->HrefValue = "";

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
		if (!$this->co_alternativo->FldIsDetailKey && !is_null($this->co_alternativo->FormValue) && $this->co_alternativo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->co_alternativo->FldCaption());
		}
		if (!$this->no_sistema->FldIsDetailKey && !is_null($this->no_sistema->FormValue) && $this->no_sistema->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_sistema->FldCaption());
		}
		if (!$this->nu_stSistema->FldIsDetailKey && !is_null($this->nu_stSistema->FormValue) && $this->nu_stSistema->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_stSistema->FldCaption());
		}
		if ($this->ic_ativo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_ativo->FldCaption());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("ator", $DetailTblVar) && $GLOBALS["ator"]->DetailAdd) {
			if (!isset($GLOBALS["ator_grid"])) $GLOBALS["ator_grid"] = new cator_grid(); // get detail page object
			$GLOBALS["ator_grid"]->ValidateGridForm();
		}
		if (in_array("modulo", $DetailTblVar) && $GLOBALS["modulo"]->DetailAdd) {
			if (!isset($GLOBALS["modulo_grid"])) $GLOBALS["modulo_grid"] = new cmodulo_grid(); // get detail page object
			$GLOBALS["modulo_grid"]->ValidateGridForm();
		}
		if (in_array("uc", $DetailTblVar) && $GLOBALS["uc"]->DetailAdd) {
			if (!isset($GLOBALS["uc_grid"])) $GLOBALS["uc_grid"] = new cuc_grid(); // get detail page object
			$GLOBALS["uc_grid"]->ValidateGridForm();
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

		// co_alternativo
		$this->co_alternativo->SetDbValueDef($rsnew, $this->co_alternativo->CurrentValue, "", FALSE);

		// no_sistema
		$this->no_sistema->SetDbValueDef($rsnew, $this->no_sistema->CurrentValue, "", FALSE);

		// ds_sistema
		$this->ds_sistema->SetDbValueDef($rsnew, $this->ds_sistema->CurrentValue, NULL, FALSE);

		// nu_fornecedor
		$this->nu_fornecedor->SetDbValueDef($rsnew, $this->nu_fornecedor->CurrentValue, NULL, FALSE);

		// ds_contatos
		$this->ds_contatos->SetDbValueDef($rsnew, $this->ds_contatos->CurrentValue, NULL, FALSE);

		// ds_comentarios
		$this->ds_comentarios->SetDbValueDef($rsnew, $this->ds_comentarios->CurrentValue, NULL, FALSE);

		// nu_stSistema
		$this->nu_stSistema->SetDbValueDef($rsnew, $this->nu_stSistema->CurrentValue, NULL, FALSE);

		// ic_ativo
		$this->ic_ativo->SetDbValueDef($rsnew, $this->ic_ativo->CurrentValue, NULL, FALSE);

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
			$this->nu_sistema->setDbValue($conn->Insert_ID());
			$rsnew['nu_sistema'] = $this->nu_sistema->DbValue;
		}

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("ator", $DetailTblVar) && $GLOBALS["ator"]->DetailAdd) {
				$GLOBALS["ator"]->nu_sistema->setSessionValue($this->nu_sistema->CurrentValue); // Set master key
				if (!isset($GLOBALS["ator_grid"])) $GLOBALS["ator_grid"] = new cator_grid(); // Get detail page object
				$AddRow = $GLOBALS["ator_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["ator"]->nu_sistema->setSessionValue(""); // Clear master key if insert failed
			}
			if (in_array("modulo", $DetailTblVar) && $GLOBALS["modulo"]->DetailAdd) {
				$GLOBALS["modulo"]->nu_sistema->setSessionValue($this->nu_sistema->CurrentValue); // Set master key
				if (!isset($GLOBALS["modulo_grid"])) $GLOBALS["modulo_grid"] = new cmodulo_grid(); // Get detail page object
				$AddRow = $GLOBALS["modulo_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["modulo"]->nu_sistema->setSessionValue(""); // Clear master key if insert failed
			}
			if (in_array("uc", $DetailTblVar) && $GLOBALS["uc"]->DetailAdd) {
				$GLOBALS["uc"]->nu_sistema->setSessionValue($this->nu_sistema->CurrentValue); // Set master key
				if (!isset($GLOBALS["uc_grid"])) $GLOBALS["uc_grid"] = new cuc_grid(); // Get detail page object
				$AddRow = $GLOBALS["uc_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["uc"]->nu_sistema->setSessionValue(""); // Clear master key if insert failed
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
			if (in_array("ator", $DetailTblVar)) {
				if (!isset($GLOBALS["ator_grid"]))
					$GLOBALS["ator_grid"] = new cator_grid;
				if ($GLOBALS["ator_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["ator_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["ator_grid"]->CurrentMode = "add";
					$GLOBALS["ator_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["ator_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["ator_grid"]->setStartRecordNumber(1);
					$GLOBALS["ator_grid"]->nu_sistema->FldIsDetailKey = TRUE;
					$GLOBALS["ator_grid"]->nu_sistema->CurrentValue = $this->nu_sistema->CurrentValue;
					$GLOBALS["ator_grid"]->nu_sistema->setSessionValue($GLOBALS["ator_grid"]->nu_sistema->CurrentValue);
				}
			}
			if (in_array("modulo", $DetailTblVar)) {
				if (!isset($GLOBALS["modulo_grid"]))
					$GLOBALS["modulo_grid"] = new cmodulo_grid;
				if ($GLOBALS["modulo_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["modulo_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["modulo_grid"]->CurrentMode = "add";
					$GLOBALS["modulo_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["modulo_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["modulo_grid"]->setStartRecordNumber(1);
					$GLOBALS["modulo_grid"]->nu_sistema->FldIsDetailKey = TRUE;
					$GLOBALS["modulo_grid"]->nu_sistema->CurrentValue = $this->nu_sistema->CurrentValue;
					$GLOBALS["modulo_grid"]->nu_sistema->setSessionValue($GLOBALS["modulo_grid"]->nu_sistema->CurrentValue);
				}
			}
			if (in_array("uc", $DetailTblVar)) {
				if (!isset($GLOBALS["uc_grid"]))
					$GLOBALS["uc_grid"] = new cuc_grid;
				if ($GLOBALS["uc_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["uc_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["uc_grid"]->CurrentMode = "add";
					$GLOBALS["uc_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["uc_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["uc_grid"]->setStartRecordNumber(1);
					$GLOBALS["uc_grid"]->nu_sistema->FldIsDetailKey = TRUE;
					$GLOBALS["uc_grid"]->nu_sistema->CurrentValue = $this->nu_sistema->CurrentValue;
					$GLOBALS["uc_grid"]->nu_sistema->setSessionValue($GLOBALS["uc_grid"]->nu_sistema->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "sistemalist.php", $this->TableVar);
		$PageCaption = ($this->CurrentAction == "C") ? $Language->Phrase("Copy") : $Language->Phrase("Add");
		$Breadcrumb->Add("add", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'sistema';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'sistema';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_sistema'];

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
if (!isset($sistema_add)) $sistema_add = new csistema_add();

// Page init
$sistema_add->Page_Init();

// Page main
$sistema_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$sistema_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var sistema_add = new ew_Page("sistema_add");
sistema_add.PageID = "add"; // Page ID
var EW_PAGE_ID = sistema_add.PageID; // For backward compatibility

// Form object
var fsistemaadd = new ew_Form("fsistemaadd");

// Validate form
fsistemaadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_co_alternativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($sistema->co_alternativo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no_sistema");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($sistema->no_sistema->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_stSistema");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($sistema->nu_stSistema->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_ativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($sistema->ic_ativo->FldCaption()) ?>");

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
fsistemaadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsistemaadd.ValidateRequired = true;
<?php } else { ?>
fsistemaadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsistemaadd.Lists["x_nu_fornecedor"] = {"LinkField":"x_nu_fornecedor","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_fornecedor","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fsistemaadd.Lists["x_nu_stSistema"] = {"LinkField":"x_nu_stSistema","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_stSistema","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $sistema_add->ShowPageHeader(); ?>
<?php
$sistema_add->ShowMessage();
?>
<form name="fsistemaadd" id="fsistemaadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="sistema">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_sistemaadd" class="table table-bordered table-striped">
<?php if ($sistema->co_alternativo->Visible) { // co_alternativo ?>
	<tr id="r_co_alternativo">
		<td><span id="elh_sistema_co_alternativo"><?php echo $sistema->co_alternativo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $sistema->co_alternativo->CellAttributes() ?>>
<span id="el_sistema_co_alternativo" class="control-group">
<input type="text" data-field="x_co_alternativo" name="x_co_alternativo" id="x_co_alternativo" size="30" maxlength="15" placeholder="<?php echo $sistema->co_alternativo->PlaceHolder ?>" value="<?php echo $sistema->co_alternativo->EditValue ?>"<?php echo $sistema->co_alternativo->EditAttributes() ?>>
</span>
<?php echo $sistema->co_alternativo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($sistema->no_sistema->Visible) { // no_sistema ?>
	<tr id="r_no_sistema">
		<td><span id="elh_sistema_no_sistema"><?php echo $sistema->no_sistema->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $sistema->no_sistema->CellAttributes() ?>>
<span id="el_sistema_no_sistema" class="control-group">
<input type="text" data-field="x_no_sistema" name="x_no_sistema" id="x_no_sistema" size="30" maxlength="120" placeholder="<?php echo $sistema->no_sistema->PlaceHolder ?>" value="<?php echo $sistema->no_sistema->EditValue ?>"<?php echo $sistema->no_sistema->EditAttributes() ?>>
</span>
<?php echo $sistema->no_sistema->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($sistema->ds_sistema->Visible) { // ds_sistema ?>
	<tr id="r_ds_sistema">
		<td><span id="elh_sistema_ds_sistema"><?php echo $sistema->ds_sistema->FldCaption() ?></span></td>
		<td<?php echo $sistema->ds_sistema->CellAttributes() ?>>
<span id="el_sistema_ds_sistema" class="control-group">
<textarea data-field="x_ds_sistema" name="x_ds_sistema" id="x_ds_sistema" cols="35" rows="4" placeholder="<?php echo $sistema->ds_sistema->PlaceHolder ?>"<?php echo $sistema->ds_sistema->EditAttributes() ?>><?php echo $sistema->ds_sistema->EditValue ?></textarea>
</span>
<?php echo $sistema->ds_sistema->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($sistema->nu_fornecedor->Visible) { // nu_fornecedor ?>
	<tr id="r_nu_fornecedor">
		<td><span id="elh_sistema_nu_fornecedor"><?php echo $sistema->nu_fornecedor->FldCaption() ?></span></td>
		<td<?php echo $sistema->nu_fornecedor->CellAttributes() ?>>
<span id="el_sistema_nu_fornecedor" class="control-group">
<select data-field="x_nu_fornecedor" id="x_nu_fornecedor" name="x_nu_fornecedor"<?php echo $sistema->nu_fornecedor->EditAttributes() ?>>
<?php
if (is_array($sistema->nu_fornecedor->EditValue)) {
	$arwrk = $sistema->nu_fornecedor->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($sistema->nu_fornecedor->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fsistemaadd.Lists["x_nu_fornecedor"].Options = <?php echo (is_array($sistema->nu_fornecedor->EditValue)) ? ew_ArrayToJson($sistema->nu_fornecedor->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $sistema->nu_fornecedor->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($sistema->ds_contatos->Visible) { // ds_contatos ?>
	<tr id="r_ds_contatos">
		<td><span id="elh_sistema_ds_contatos"><?php echo $sistema->ds_contatos->FldCaption() ?></span></td>
		<td<?php echo $sistema->ds_contatos->CellAttributes() ?>>
<span id="el_sistema_ds_contatos" class="control-group">
<textarea data-field="x_ds_contatos" name="x_ds_contatos" id="x_ds_contatos" cols="35" rows="4" placeholder="<?php echo $sistema->ds_contatos->PlaceHolder ?>"<?php echo $sistema->ds_contatos->EditAttributes() ?>><?php echo $sistema->ds_contatos->EditValue ?></textarea>
</span>
<?php echo $sistema->ds_contatos->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($sistema->ds_comentarios->Visible) { // ds_comentarios ?>
	<tr id="r_ds_comentarios">
		<td><span id="elh_sistema_ds_comentarios"><?php echo $sistema->ds_comentarios->FldCaption() ?></span></td>
		<td<?php echo $sistema->ds_comentarios->CellAttributes() ?>>
<span id="el_sistema_ds_comentarios" class="control-group">
<textarea data-field="x_ds_comentarios" name="x_ds_comentarios" id="x_ds_comentarios" cols="35" rows="4" placeholder="<?php echo $sistema->ds_comentarios->PlaceHolder ?>"<?php echo $sistema->ds_comentarios->EditAttributes() ?>><?php echo $sistema->ds_comentarios->EditValue ?></textarea>
</span>
<?php echo $sistema->ds_comentarios->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($sistema->nu_stSistema->Visible) { // nu_stSistema ?>
	<tr id="r_nu_stSistema">
		<td><span id="elh_sistema_nu_stSistema"><?php echo $sistema->nu_stSistema->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $sistema->nu_stSistema->CellAttributes() ?>>
<span id="el_sistema_nu_stSistema" class="control-group">
<select data-field="x_nu_stSistema" id="x_nu_stSistema" name="x_nu_stSistema"<?php echo $sistema->nu_stSistema->EditAttributes() ?>>
<?php
if (is_array($sistema->nu_stSistema->EditValue)) {
	$arwrk = $sistema->nu_stSistema->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($sistema->nu_stSistema->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fsistemaadd.Lists["x_nu_stSistema"].Options = <?php echo (is_array($sistema->nu_stSistema->EditValue)) ? ew_ArrayToJson($sistema->nu_stSistema->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $sistema->nu_stSistema->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($sistema->ic_ativo->Visible) { // ic_ativo ?>
	<tr id="r_ic_ativo">
		<td><span id="elh_sistema_ic_ativo"><?php echo $sistema->ic_ativo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $sistema->ic_ativo->CellAttributes() ?>>
<span id="el_sistema_ic_ativo" class="control-group">
<div id="tp_x_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_ativo" id="x_ic_ativo" value="{value}"<?php echo $sistema->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $sistema->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($sistema->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x_ic_ativo" id="x_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $sistema->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $sistema->ic_ativo->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<?php
	if (in_array("ator", explode(",", $sistema->getCurrentDetailTable())) && $ator->DetailAdd) {
?>
<?php include_once "atorgrid.php" ?>
<?php } ?>
<?php
	if (in_array("modulo", explode(",", $sistema->getCurrentDetailTable())) && $modulo->DetailAdd) {
?>
<?php include_once "modulogrid.php" ?>
<?php } ?>
<?php
	if (in_array("uc", explode(",", $sistema->getCurrentDetailTable())) && $uc->DetailAdd) {
?>
<?php include_once "ucgrid.php" ?>
<?php } ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fsistemaadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$sistema_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$sistema_add->Page_Terminate();
?>
