<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "contratoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "item_contratadogridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$contrato_add = NULL; // Initialize page object first

class ccontrato_add extends ccontrato {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'contrato';

	// Page object name
	var $PageObjName = 'contrato_add';

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

		// Table object (contrato)
		if (!isset($GLOBALS["contrato"])) {
			$GLOBALS["contrato"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["contrato"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'contrato', TRUE);

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
			$this->Page_Terminate("contratolist.php");
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
			if (@$_GET["nu_contrato"] != "") {
				$this->nu_contrato->setQueryStringValue($_GET["nu_contrato"]);
				$this->setKey("nu_contrato", $this->nu_contrato->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_contrato", ""); // Clear key
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
					$this->Page_Terminate("contratolist.php"); // No matching record, return to list
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
					if (ew_GetPageName($sReturnUrl) == "contratoview.php")
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
		$this->im_contrato->Upload->Index = $objForm->Index;
		if ($this->im_contrato->Upload->UploadFile()) {

			// No action required
		} else {
			echo $this->im_contrato->Upload->Message;
			$this->Page_Terminate();
			exit();
		}
	}

	// Load default values
	function LoadDefaultValues() {
		$this->co_alternativo->CurrentValue = NULL;
		$this->co_alternativo->OldValue = $this->co_alternativo->CurrentValue;
		$this->nu_fornecedor->CurrentValue = NULL;
		$this->nu_fornecedor->OldValue = $this->nu_fornecedor->CurrentValue;
		$this->no_contrato->CurrentValue = NULL;
		$this->no_contrato->OldValue = $this->no_contrato->CurrentValue;
		$this->ds_contrato->CurrentValue = NULL;
		$this->ds_contrato->OldValue = $this->ds_contrato->CurrentValue;
		$this->dt_vencimento->CurrentValue = NULL;
		$this->dt_vencimento->OldValue = $this->dt_vencimento->CurrentValue;
		$this->im_contrato->Upload->DbValue = NULL;
		$this->im_contrato->OldValue = $this->im_contrato->Upload->DbValue;
		$this->nu_stContrato->CurrentValue = NULL;
		$this->nu_stContrato->OldValue = $this->nu_stContrato->CurrentValue;
		$this->ds_observacoes->CurrentValue = NULL;
		$this->ds_observacoes->OldValue = $this->ds_observacoes->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->co_alternativo->FldIsDetailKey) {
			$this->co_alternativo->setFormValue($objForm->GetValue("x_co_alternativo"));
		}
		if (!$this->nu_fornecedor->FldIsDetailKey) {
			$this->nu_fornecedor->setFormValue($objForm->GetValue("x_nu_fornecedor"));
		}
		if (!$this->no_contrato->FldIsDetailKey) {
			$this->no_contrato->setFormValue($objForm->GetValue("x_no_contrato"));
		}
		if (!$this->ds_contrato->FldIsDetailKey) {
			$this->ds_contrato->setFormValue($objForm->GetValue("x_ds_contrato"));
		}
		if (!$this->dt_vencimento->FldIsDetailKey) {
			$this->dt_vencimento->setFormValue($objForm->GetValue("x_dt_vencimento"));
			$this->dt_vencimento->CurrentValue = ew_UnFormatDateTime($this->dt_vencimento->CurrentValue, 7);
		}
		if (!$this->nu_stContrato->FldIsDetailKey) {
			$this->nu_stContrato->setFormValue($objForm->GetValue("x_nu_stContrato"));
		}
		if (!$this->ds_observacoes->FldIsDetailKey) {
			$this->ds_observacoes->setFormValue($objForm->GetValue("x_ds_observacoes"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->co_alternativo->CurrentValue = $this->co_alternativo->FormValue;
		$this->nu_fornecedor->CurrentValue = $this->nu_fornecedor->FormValue;
		$this->no_contrato->CurrentValue = $this->no_contrato->FormValue;
		$this->ds_contrato->CurrentValue = $this->ds_contrato->FormValue;
		$this->dt_vencimento->CurrentValue = $this->dt_vencimento->FormValue;
		$this->dt_vencimento->CurrentValue = ew_UnFormatDateTime($this->dt_vencimento->CurrentValue, 7);
		$this->nu_stContrato->CurrentValue = $this->nu_stContrato->FormValue;
		$this->ds_observacoes->CurrentValue = $this->ds_observacoes->FormValue;
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
		$this->nu_contrato->setDbValue($rs->fields('nu_contrato'));
		$this->co_alternativo->setDbValue($rs->fields('co_alternativo'));
		$this->nu_fornecedor->setDbValue($rs->fields('nu_fornecedor'));
		$this->no_contrato->setDbValue($rs->fields('no_contrato'));
		$this->ds_contrato->setDbValue($rs->fields('ds_contrato'));
		$this->dt_vencimento->setDbValue($rs->fields('dt_vencimento'));
		$this->im_contrato->Upload->DbValue = $rs->fields('im_contrato');
		$this->nu_stContrato->setDbValue($rs->fields('nu_stContrato'));
		$this->ds_observacoes->setDbValue($rs->fields('ds_observacoes'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_contrato->DbValue = $row['nu_contrato'];
		$this->co_alternativo->DbValue = $row['co_alternativo'];
		$this->nu_fornecedor->DbValue = $row['nu_fornecedor'];
		$this->no_contrato->DbValue = $row['no_contrato'];
		$this->ds_contrato->DbValue = $row['ds_contrato'];
		$this->dt_vencimento->DbValue = $row['dt_vencimento'];
		$this->im_contrato->Upload->DbValue = $row['im_contrato'];
		$this->nu_stContrato->DbValue = $row['nu_stContrato'];
		$this->ds_observacoes->DbValue = $row['ds_observacoes'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_contrato")) <> "")
			$this->nu_contrato->CurrentValue = $this->getKey("nu_contrato"); // nu_contrato
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
		// nu_contrato
		// co_alternativo
		// nu_fornecedor
		// no_contrato
		// ds_contrato
		// dt_vencimento
		// im_contrato
		// nu_stContrato
		// ds_observacoes

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_contrato
			$this->nu_contrato->ViewValue = $this->nu_contrato->CurrentValue;
			$this->nu_contrato->ViewCustomAttributes = "";

			// co_alternativo
			$this->co_alternativo->ViewValue = $this->co_alternativo->CurrentValue;
			$this->co_alternativo->ViewCustomAttributes = "";

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

			// no_contrato
			$this->no_contrato->ViewValue = $this->no_contrato->CurrentValue;
			$this->no_contrato->ViewCustomAttributes = "";

			// ds_contrato
			$this->ds_contrato->ViewValue = $this->ds_contrato->CurrentValue;
			$this->ds_contrato->ViewCustomAttributes = "";

			// dt_vencimento
			$this->dt_vencimento->ViewValue = $this->dt_vencimento->CurrentValue;
			$this->dt_vencimento->ViewValue = ew_FormatDateTime($this->dt_vencimento->ViewValue, 7);
			$this->dt_vencimento->ViewCustomAttributes = "";

			// im_contrato
			$this->im_contrato->UploadPath = "arquivos/contratos";
			if (!ew_Empty($this->im_contrato->Upload->DbValue)) {
				$this->im_contrato->ViewValue = $this->im_contrato->Upload->DbValue;
			} else {
				$this->im_contrato->ViewValue = "";
			}
			$this->im_contrato->ViewCustomAttributes = "";

			// nu_stContrato
			if (strval($this->nu_stContrato->CurrentValue) <> "") {
				$sFilterWrk = "[nu_stContrato]" . ew_SearchString("=", $this->nu_stContrato->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_stContrato], [no_stContrato] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[stcontrato]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_stContrato, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_stContrato] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_stContrato->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_stContrato->ViewValue = $this->nu_stContrato->CurrentValue;
				}
			} else {
				$this->nu_stContrato->ViewValue = NULL;
			}
			$this->nu_stContrato->ViewCustomAttributes = "";

			// ds_observacoes
			$this->ds_observacoes->ViewValue = $this->ds_observacoes->CurrentValue;
			$this->ds_observacoes->ViewCustomAttributes = "";

			// co_alternativo
			$this->co_alternativo->LinkCustomAttributes = "";
			$this->co_alternativo->HrefValue = "";
			$this->co_alternativo->TooltipValue = "";

			// nu_fornecedor
			$this->nu_fornecedor->LinkCustomAttributes = "";
			$this->nu_fornecedor->HrefValue = "";
			$this->nu_fornecedor->TooltipValue = "";

			// no_contrato
			$this->no_contrato->LinkCustomAttributes = "";
			$this->no_contrato->HrefValue = "";
			$this->no_contrato->TooltipValue = "";

			// ds_contrato
			$this->ds_contrato->LinkCustomAttributes = "";
			$this->ds_contrato->HrefValue = "";
			$this->ds_contrato->TooltipValue = "";

			// dt_vencimento
			$this->dt_vencimento->LinkCustomAttributes = "";
			$this->dt_vencimento->HrefValue = "";
			$this->dt_vencimento->TooltipValue = "";

			// im_contrato
			$this->im_contrato->LinkCustomAttributes = "";
			$this->im_contrato->UploadPath = "arquivos/contratos";
			if (!ew_Empty($this->im_contrato->Upload->DbValue)) {
				$this->im_contrato->HrefValue = "%u"; // Add prefix/suffix
				$this->im_contrato->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->im_contrato->HrefValue = ew_ConvertFullUrl($this->im_contrato->HrefValue);
			} else {
				$this->im_contrato->HrefValue = "";
			}
			$this->im_contrato->HrefValue2 = $this->im_contrato->UploadPath . $this->im_contrato->Upload->DbValue;
			$this->im_contrato->TooltipValue = "";

			// nu_stContrato
			$this->nu_stContrato->LinkCustomAttributes = "";
			$this->nu_stContrato->HrefValue = "";
			$this->nu_stContrato->TooltipValue = "";

			// ds_observacoes
			$this->ds_observacoes->LinkCustomAttributes = "";
			$this->ds_observacoes->HrefValue = "";
			$this->ds_observacoes->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// co_alternativo
			$this->co_alternativo->EditCustomAttributes = "";
			$this->co_alternativo->EditValue = ew_HtmlEncode($this->co_alternativo->CurrentValue);
			$this->co_alternativo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->co_alternativo->FldCaption()));

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

			// no_contrato
			$this->no_contrato->EditCustomAttributes = "";
			$this->no_contrato->EditValue = ew_HtmlEncode($this->no_contrato->CurrentValue);
			$this->no_contrato->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_contrato->FldCaption()));

			// ds_contrato
			$this->ds_contrato->EditCustomAttributes = "";
			$this->ds_contrato->EditValue = $this->ds_contrato->CurrentValue;
			$this->ds_contrato->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_contrato->FldCaption()));

			// dt_vencimento
			$this->dt_vencimento->EditCustomAttributes = "";
			$this->dt_vencimento->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->dt_vencimento->CurrentValue, 7));
			$this->dt_vencimento->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dt_vencimento->FldCaption()));

			// im_contrato
			$this->im_contrato->EditCustomAttributes = "";
			$this->im_contrato->UploadPath = "arquivos/contratos";
			if (!ew_Empty($this->im_contrato->Upload->DbValue)) {
				$this->im_contrato->EditValue = $this->im_contrato->Upload->DbValue;
			} else {
				$this->im_contrato->EditValue = "";
			}
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->im_contrato);

			// nu_stContrato
			$this->nu_stContrato->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_stContrato], [no_stContrato] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[stcontrato]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_stContrato, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_stContrato] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_stContrato->EditValue = $arwrk;

			// ds_observacoes
			$this->ds_observacoes->EditCustomAttributes = "";
			$this->ds_observacoes->EditValue = $this->ds_observacoes->CurrentValue;
			$this->ds_observacoes->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_observacoes->FldCaption()));

			// Edit refer script
			// co_alternativo

			$this->co_alternativo->HrefValue = "";

			// nu_fornecedor
			$this->nu_fornecedor->HrefValue = "";

			// no_contrato
			$this->no_contrato->HrefValue = "";

			// ds_contrato
			$this->ds_contrato->HrefValue = "";

			// dt_vencimento
			$this->dt_vencimento->HrefValue = "";

			// im_contrato
			$this->im_contrato->UploadPath = "arquivos/contratos";
			if (!ew_Empty($this->im_contrato->Upload->DbValue)) {
				$this->im_contrato->HrefValue = "%u"; // Add prefix/suffix
				$this->im_contrato->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->im_contrato->HrefValue = ew_ConvertFullUrl($this->im_contrato->HrefValue);
			} else {
				$this->im_contrato->HrefValue = "";
			}
			$this->im_contrato->HrefValue2 = $this->im_contrato->UploadPath . $this->im_contrato->Upload->DbValue;

			// nu_stContrato
			$this->nu_stContrato->HrefValue = "";

			// ds_observacoes
			$this->ds_observacoes->HrefValue = "";
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
		if (!ew_CheckInteger($this->co_alternativo->FormValue)) {
			ew_AddMessage($gsFormError, $this->co_alternativo->FldErrMsg());
		}
		if (!$this->nu_fornecedor->FldIsDetailKey && !is_null($this->nu_fornecedor->FormValue) && $this->nu_fornecedor->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_fornecedor->FldCaption());
		}
		if (!$this->no_contrato->FldIsDetailKey && !is_null($this->no_contrato->FormValue) && $this->no_contrato->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_contrato->FldCaption());
		}
		if (!$this->ds_contrato->FldIsDetailKey && !is_null($this->ds_contrato->FormValue) && $this->ds_contrato->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ds_contrato->FldCaption());
		}
		if (!$this->dt_vencimento->FldIsDetailKey && !is_null($this->dt_vencimento->FormValue) && $this->dt_vencimento->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->dt_vencimento->FldCaption());
		}
		if (!ew_CheckEuroDate($this->dt_vencimento->FormValue)) {
			ew_AddMessage($gsFormError, $this->dt_vencimento->FldErrMsg());
		}
		if (!$this->nu_stContrato->FldIsDetailKey && !is_null($this->nu_stContrato->FormValue) && $this->nu_stContrato->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_stContrato->FldCaption());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("item_contratado", $DetailTblVar) && $GLOBALS["item_contratado"]->DetailAdd) {
			if (!isset($GLOBALS["item_contratado_grid"])) $GLOBALS["item_contratado_grid"] = new citem_contratado_grid(); // get detail page object
			$GLOBALS["item_contratado_grid"]->ValidateGridForm();
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
			$this->im_contrato->OldUploadPath = "arquivos/contratos";
			$this->im_contrato->UploadPath = $this->im_contrato->OldUploadPath;
		}
		$rsnew = array();

		// co_alternativo
		$this->co_alternativo->SetDbValueDef($rsnew, $this->co_alternativo->CurrentValue, 0, FALSE);

		// nu_fornecedor
		$this->nu_fornecedor->SetDbValueDef($rsnew, $this->nu_fornecedor->CurrentValue, NULL, FALSE);

		// no_contrato
		$this->no_contrato->SetDbValueDef($rsnew, $this->no_contrato->CurrentValue, "", FALSE);

		// ds_contrato
		$this->ds_contrato->SetDbValueDef($rsnew, $this->ds_contrato->CurrentValue, "", FALSE);

		// dt_vencimento
		$this->dt_vencimento->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->dt_vencimento->CurrentValue, 7), NULL, FALSE);

		// im_contrato
		if (!$this->im_contrato->Upload->KeepFile) {
			if ($this->im_contrato->Upload->FileName == "") {
				$rsnew['im_contrato'] = NULL;
			} else {
				$rsnew['im_contrato'] = $this->im_contrato->Upload->FileName;
			}
		}

		// nu_stContrato
		$this->nu_stContrato->SetDbValueDef($rsnew, $this->nu_stContrato->CurrentValue, NULL, FALSE);

		// ds_observacoes
		$this->ds_observacoes->SetDbValueDef($rsnew, $this->ds_observacoes->CurrentValue, NULL, FALSE);
		if (!$this->im_contrato->Upload->KeepFile) {
			$this->im_contrato->UploadPath = "arquivos/contratos";
			$OldFiles = explode(",", $this->im_contrato->Upload->DbValue);
			if (!ew_Empty($this->im_contrato->Upload->FileName)) {
				$NewFiles = explode(",", $this->im_contrato->Upload->FileName);
				$FileCount = count($NewFiles);
				for ($i = 0; $i < $FileCount; $i++) {
					$fldvar = ($this->im_contrato->Upload->Index < 0) ? $this->im_contrato->FldVar : substr($this->im_contrato->FldVar, 0, 1) . $this->im_contrato->Upload->Index . substr($this->im_contrato->FldVar, 1);
					if ($NewFiles[$i] <> "") {
						$file = ew_UploadTempPath($fldvar) . EW_PATH_DELIMITER . $NewFiles[$i];
						if (file_exists($file)) {
							if (!in_array($NewFiles[$i], $OldFiles)) {
								$NewFiles[$i] = ew_UploadFileNameEx($this->im_contrato->UploadPath, $NewFiles[$i]); // Get new file name
								$file1 = ew_UploadTempPath($fldvar) . EW_PATH_DELIMITER . $NewFiles[$i];
								if ($file1 <> $file) // Rename temp file
									rename($file, $file1);
							}
						}
					}
				}
				$this->im_contrato->Upload->FileName = implode(",", $NewFiles);
				$rsnew['im_contrato'] = $this->im_contrato->Upload->FileName;
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
				if (!$this->im_contrato->Upload->KeepFile) {
					$OldFiles = explode(",", $this->im_contrato->Upload->DbValue);
					if (!ew_Empty($this->im_contrato->Upload->FileName)) {
						$NewFiles = explode(",", $this->im_contrato->Upload->FileName);
						$NewFiles2 = explode(",", $rsnew['im_contrato']);
						$FileCount = count($NewFiles);
						for ($i = 0; $i < $FileCount; $i++) {
							$fldvar = ($this->im_contrato->Upload->Index < 0) ? $this->im_contrato->FldVar : substr($this->im_contrato->FldVar, 0, 1) . $this->im_contrato->Upload->Index . substr($this->im_contrato->FldVar, 1);
							if ($NewFiles[$i] <> "") {
								$file = ew_UploadTempPath($fldvar) . EW_PATH_DELIMITER . $NewFiles[$i];
								if (file_exists($file)) {
									$this->im_contrato->Upload->Value = file_get_contents($file);
									$this->im_contrato->Upload->SaveToFile($this->im_contrato->UploadPath, (@$NewFiles2[$i] <> "") ? $NewFiles2[$i] : $NewFiles[$i], TRUE); // Just replace
								}
							}
						}
					} else {
						$NewFiles = array();
					}
					$FileCount = count($OldFiles);
					for ($i = 0; $i < $FileCount; $i++) {
						if ($OldFiles[$i] <> "" && !in_array($OldFiles[$i], $NewFiles))
							@unlink(ew_UploadPathEx(TRUE, $this->im_contrato->OldUploadPath) . $OldFiles[$i]);
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
			$this->nu_contrato->setDbValue($conn->Insert_ID());
			$rsnew['nu_contrato'] = $this->nu_contrato->DbValue;
		}

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("item_contratado", $DetailTblVar) && $GLOBALS["item_contratado"]->DetailAdd) {
				$GLOBALS["item_contratado"]->nu_contrato->setSessionValue($this->nu_contrato->CurrentValue); // Set master key
				if (!isset($GLOBALS["item_contratado_grid"])) $GLOBALS["item_contratado_grid"] = new citem_contratado_grid(); // Get detail page object
				$AddRow = $GLOBALS["item_contratado_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["item_contratado"]->nu_contrato->setSessionValue(""); // Clear master key if insert failed
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

		// im_contrato
		ew_CleanUploadTempPath($this->im_contrato, $this->im_contrato->Upload->Index);
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
			if (in_array("item_contratado", $DetailTblVar)) {
				if (!isset($GLOBALS["item_contratado_grid"]))
					$GLOBALS["item_contratado_grid"] = new citem_contratado_grid;
				if ($GLOBALS["item_contratado_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["item_contratado_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["item_contratado_grid"]->CurrentMode = "add";
					$GLOBALS["item_contratado_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["item_contratado_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["item_contratado_grid"]->setStartRecordNumber(1);
					$GLOBALS["item_contratado_grid"]->nu_contrato->FldIsDetailKey = TRUE;
					$GLOBALS["item_contratado_grid"]->nu_contrato->CurrentValue = $this->nu_contrato->CurrentValue;
					$GLOBALS["item_contratado_grid"]->nu_contrato->setSessionValue($GLOBALS["item_contratado_grid"]->nu_contrato->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "contratolist.php", $this->TableVar);
		$PageCaption = ($this->CurrentAction == "C") ? $Language->Phrase("Copy") : $Language->Phrase("Add");
		$Breadcrumb->Add("add", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'contrato';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'contrato';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_contrato'];

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
if (!isset($contrato_add)) $contrato_add = new ccontrato_add();

// Page init
$contrato_add->Page_Init();

// Page main
$contrato_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$contrato_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var contrato_add = new ew_Page("contrato_add");
contrato_add.PageID = "add"; // Page ID
var EW_PAGE_ID = contrato_add.PageID; // For backward compatibility

// Form object
var fcontratoadd = new ew_Form("fcontratoadd");

// Validate form
fcontratoadd.Validate = function() {
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
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($contrato->co_alternativo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_co_alternativo");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($contrato->co_alternativo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nu_fornecedor");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($contrato->nu_fornecedor->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no_contrato");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($contrato->no_contrato->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ds_contrato");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($contrato->ds_contrato->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dt_vencimento");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($contrato->dt_vencimento->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dt_vencimento");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($contrato->dt_vencimento->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nu_stContrato");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($contrato->nu_stContrato->FldCaption()) ?>");

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
fcontratoadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcontratoadd.ValidateRequired = true;
<?php } else { ?>
fcontratoadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcontratoadd.Lists["x_nu_fornecedor"] = {"LinkField":"x_nu_fornecedor","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_fornecedor","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontratoadd.Lists["x_nu_stContrato"] = {"LinkField":"x_nu_stContrato","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_stContrato","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $contrato_add->ShowPageHeader(); ?>
<?php
$contrato_add->ShowMessage();
?>
<form name="fcontratoadd" id="fcontratoadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="contrato">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_contratoadd" class="table table-bordered table-striped">
<?php if ($contrato->co_alternativo->Visible) { // co_alternativo ?>
	<tr id="r_co_alternativo">
		<td><span id="elh_contrato_co_alternativo"><?php echo $contrato->co_alternativo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $contrato->co_alternativo->CellAttributes() ?>>
<span id="el_contrato_co_alternativo" class="control-group">
<input type="text" data-field="x_co_alternativo" name="x_co_alternativo" id="x_co_alternativo" size="30" placeholder="<?php echo $contrato->co_alternativo->PlaceHolder ?>" value="<?php echo $contrato->co_alternativo->EditValue ?>"<?php echo $contrato->co_alternativo->EditAttributes() ?>>
</span>
<?php echo $contrato->co_alternativo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contrato->nu_fornecedor->Visible) { // nu_fornecedor ?>
	<tr id="r_nu_fornecedor">
		<td><span id="elh_contrato_nu_fornecedor"><?php echo $contrato->nu_fornecedor->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $contrato->nu_fornecedor->CellAttributes() ?>>
<span id="el_contrato_nu_fornecedor" class="control-group">
<select data-field="x_nu_fornecedor" id="x_nu_fornecedor" name="x_nu_fornecedor"<?php echo $contrato->nu_fornecedor->EditAttributes() ?>>
<?php
if (is_array($contrato->nu_fornecedor->EditValue)) {
	$arwrk = $contrato->nu_fornecedor->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contrato->nu_fornecedor->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if (AllowAdd(CurrentProjectID() . "fornecedor")) { ?>
&nbsp;<a id="aol_x_nu_fornecedor" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_nu_fornecedor',url:'fornecedoraddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $contrato->nu_fornecedor->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
fcontratoadd.Lists["x_nu_fornecedor"].Options = <?php echo (is_array($contrato->nu_fornecedor->EditValue)) ? ew_ArrayToJson($contrato->nu_fornecedor->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $contrato->nu_fornecedor->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contrato->no_contrato->Visible) { // no_contrato ?>
	<tr id="r_no_contrato">
		<td><span id="elh_contrato_no_contrato"><?php echo $contrato->no_contrato->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $contrato->no_contrato->CellAttributes() ?>>
<span id="el_contrato_no_contrato" class="control-group">
<input type="text" data-field="x_no_contrato" name="x_no_contrato" id="x_no_contrato" size="30" maxlength="100" placeholder="<?php echo $contrato->no_contrato->PlaceHolder ?>" value="<?php echo $contrato->no_contrato->EditValue ?>"<?php echo $contrato->no_contrato->EditAttributes() ?>>
</span>
<?php echo $contrato->no_contrato->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contrato->ds_contrato->Visible) { // ds_contrato ?>
	<tr id="r_ds_contrato">
		<td><span id="elh_contrato_ds_contrato"><?php echo $contrato->ds_contrato->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $contrato->ds_contrato->CellAttributes() ?>>
<span id="el_contrato_ds_contrato" class="control-group">
<textarea data-field="x_ds_contrato" name="x_ds_contrato" id="x_ds_contrato" cols="35" rows="4" placeholder="<?php echo $contrato->ds_contrato->PlaceHolder ?>"<?php echo $contrato->ds_contrato->EditAttributes() ?>><?php echo $contrato->ds_contrato->EditValue ?></textarea>
</span>
<?php echo $contrato->ds_contrato->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contrato->dt_vencimento->Visible) { // dt_vencimento ?>
	<tr id="r_dt_vencimento">
		<td><span id="elh_contrato_dt_vencimento"><?php echo $contrato->dt_vencimento->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $contrato->dt_vencimento->CellAttributes() ?>>
<span id="el_contrato_dt_vencimento" class="control-group">
<input type="text" data-field="x_dt_vencimento" name="x_dt_vencimento" id="x_dt_vencimento" placeholder="<?php echo $contrato->dt_vencimento->PlaceHolder ?>" value="<?php echo $contrato->dt_vencimento->EditValue ?>"<?php echo $contrato->dt_vencimento->EditAttributes() ?>>
<?php if (!$contrato->dt_vencimento->ReadOnly && !$contrato->dt_vencimento->Disabled && @$contrato->dt_vencimento->EditAttrs["readonly"] == "" && @$contrato->dt_vencimento->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_dt_vencimento" name="cal_x_dt_vencimento" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x_dt_vencimento" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fcontratoadd", "x_dt_vencimento", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $contrato->dt_vencimento->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contrato->im_contrato->Visible) { // im_contrato ?>
	<tr id="r_im_contrato">
		<td><span id="elh_contrato_im_contrato"><?php echo $contrato->im_contrato->FldCaption() ?></span></td>
		<td<?php echo $contrato->im_contrato->CellAttributes() ?>>
<span id="el_contrato_im_contrato" class="control-group">
<span id="fd_x_im_contrato">
<span class="btn btn-small fileinput-button">
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_im_contrato" name="x_im_contrato" id="x_im_contrato" multiple="multiple">
</span>
<input type="hidden" name="fn_x_im_contrato" id= "fn_x_im_contrato" value="<?php echo $contrato->im_contrato->Upload->FileName ?>">
<input type="hidden" name="fa_x_im_contrato" id= "fa_x_im_contrato" value="0">
<input type="hidden" name="fs_x_im_contrato" id= "fs_x_im_contrato" value="2147483647">
</span>
<table id="ft_x_im_contrato" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $contrato->im_contrato->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contrato->nu_stContrato->Visible) { // nu_stContrato ?>
	<tr id="r_nu_stContrato">
		<td><span id="elh_contrato_nu_stContrato"><?php echo $contrato->nu_stContrato->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $contrato->nu_stContrato->CellAttributes() ?>>
<span id="el_contrato_nu_stContrato" class="control-group">
<select data-field="x_nu_stContrato" id="x_nu_stContrato" name="x_nu_stContrato"<?php echo $contrato->nu_stContrato->EditAttributes() ?>>
<?php
if (is_array($contrato->nu_stContrato->EditValue)) {
	$arwrk = $contrato->nu_stContrato->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contrato->nu_stContrato->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fcontratoadd.Lists["x_nu_stContrato"].Options = <?php echo (is_array($contrato->nu_stContrato->EditValue)) ? ew_ArrayToJson($contrato->nu_stContrato->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $contrato->nu_stContrato->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($contrato->ds_observacoes->Visible) { // ds_observacoes ?>
	<tr id="r_ds_observacoes">
		<td><span id="elh_contrato_ds_observacoes"><?php echo $contrato->ds_observacoes->FldCaption() ?></span></td>
		<td<?php echo $contrato->ds_observacoes->CellAttributes() ?>>
<span id="el_contrato_ds_observacoes" class="control-group">
<textarea data-field="x_ds_observacoes" name="x_ds_observacoes" id="x_ds_observacoes" cols="35" rows="4" placeholder="<?php echo $contrato->ds_observacoes->PlaceHolder ?>"<?php echo $contrato->ds_observacoes->EditAttributes() ?>><?php echo $contrato->ds_observacoes->EditValue ?></textarea>
</span>
<?php echo $contrato->ds_observacoes->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<?php
	if (in_array("item_contratado", explode(",", $contrato->getCurrentDetailTable())) && $item_contratado->DetailAdd) {
?>
<?php include_once "item_contratadogrid.php" ?>
<?php } ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fcontratoadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$contrato_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$contrato_add->Page_Terminate();
?>
