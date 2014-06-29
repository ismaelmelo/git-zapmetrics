<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "parsisp_versaoinfo.php" ?>
<?php include_once "parsispinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$parsisp_versao_add = NULL; // Initialize page object first

class cparsisp_versao_add extends cparsisp_versao {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'parsisp_versao';

	// Page object name
	var $PageObjName = 'parsisp_versao_add';

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

		// Table object (parsisp_versao)
		if (!isset($GLOBALS["parsisp_versao"])) {
			$GLOBALS["parsisp_versao"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["parsisp_versao"];
		}

		// Table object (parSisp)
		if (!isset($GLOBALS['parSisp'])) $GLOBALS['parSisp'] = new cparSisp();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'parsisp_versao', TRUE);

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
			$this->Page_Terminate("parsisp_versaolist.php");
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
			if (@$_GET["nu_parSisp"] != "") {
				$this->nu_parSisp->setQueryStringValue($_GET["nu_parSisp"]);
				$this->setKey("nu_parSisp", $this->nu_parSisp->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_parSisp", ""); // Clear key
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
					$this->Page_Terminate("parsisp_versaolist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "parsisp_versaoview.php")
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
		$this->nu_parSisp->CurrentValue = NULL;
		$this->nu_parSisp->OldValue = $this->nu_parSisp->CurrentValue;
		$this->nu_versao->CurrentValue = "1";
		$this->vr_parSisp->CurrentValue = NULL;
		$this->vr_parSisp->OldValue = $this->vr_parSisp->CurrentValue;
		$this->ds_codigoSql->CurrentValue = NULL;
		$this->ds_codigoSql->OldValue = $this->ds_codigoSql->CurrentValue;
		$this->nu_usuarioResp->CurrentValue = NULL;
		$this->nu_usuarioResp->OldValue = $this->nu_usuarioResp->CurrentValue;
		$this->ds_versao->CurrentValue = NULL;
		$this->ds_versao->OldValue = $this->ds_versao->CurrentValue;
		$this->dh_inclusao->CurrentValue = NULL;
		$this->dh_inclusao->OldValue = $this->dh_inclusao->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_parSisp->FldIsDetailKey) {
			$this->nu_parSisp->setFormValue($objForm->GetValue("x_nu_parSisp"));
		}
		if (!$this->nu_versao->FldIsDetailKey) {
			$this->nu_versao->setFormValue($objForm->GetValue("x_nu_versao"));
		}
		if (!$this->vr_parSisp->FldIsDetailKey) {
			$this->vr_parSisp->setFormValue($objForm->GetValue("x_vr_parSisp"));
		}
		if (!$this->ds_codigoSql->FldIsDetailKey) {
			$this->ds_codigoSql->setFormValue($objForm->GetValue("x_ds_codigoSql"));
		}
		if (!$this->nu_usuarioResp->FldIsDetailKey) {
			$this->nu_usuarioResp->setFormValue($objForm->GetValue("x_nu_usuarioResp"));
		}
		if (!$this->ds_versao->FldIsDetailKey) {
			$this->ds_versao->setFormValue($objForm->GetValue("x_ds_versao"));
		}
		if (!$this->dh_inclusao->FldIsDetailKey) {
			$this->dh_inclusao->setFormValue($objForm->GetValue("x_dh_inclusao"));
			$this->dh_inclusao->CurrentValue = ew_UnFormatDateTime($this->dh_inclusao->CurrentValue, 10);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->nu_parSisp->CurrentValue = $this->nu_parSisp->FormValue;
		$this->nu_versao->CurrentValue = $this->nu_versao->FormValue;
		$this->vr_parSisp->CurrentValue = $this->vr_parSisp->FormValue;
		$this->ds_codigoSql->CurrentValue = $this->ds_codigoSql->FormValue;
		$this->nu_usuarioResp->CurrentValue = $this->nu_usuarioResp->FormValue;
		$this->ds_versao->CurrentValue = $this->ds_versao->FormValue;
		$this->dh_inclusao->CurrentValue = $this->dh_inclusao->FormValue;
		$this->dh_inclusao->CurrentValue = ew_UnFormatDateTime($this->dh_inclusao->CurrentValue, 10);
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
		$this->nu_parSisp->setDbValue($rs->fields('nu_parSisp'));
		$this->nu_versao->setDbValue($rs->fields('nu_versao'));
		$this->vr_parSisp->setDbValue($rs->fields('vr_parSisp'));
		$this->ds_codigoSql->setDbValue($rs->fields('ds_codigoSql'));
		$this->nu_usuarioResp->setDbValue($rs->fields('nu_usuarioResp'));
		$this->ds_versao->setDbValue($rs->fields('ds_versao'));
		$this->dh_inclusao->setDbValue($rs->fields('dh_inclusao'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_parSisp->DbValue = $row['nu_parSisp'];
		$this->nu_versao->DbValue = $row['nu_versao'];
		$this->vr_parSisp->DbValue = $row['vr_parSisp'];
		$this->ds_codigoSql->DbValue = $row['ds_codigoSql'];
		$this->nu_usuarioResp->DbValue = $row['nu_usuarioResp'];
		$this->ds_versao->DbValue = $row['ds_versao'];
		$this->dh_inclusao->DbValue = $row['dh_inclusao'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_parSisp")) <> "")
			$this->nu_parSisp->CurrentValue = $this->getKey("nu_parSisp"); // nu_parSisp
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
		// Convert decimal values if posted back

		if ($this->vr_parSisp->FormValue == $this->vr_parSisp->CurrentValue && is_numeric(ew_StrToFloat($this->vr_parSisp->CurrentValue)))
			$this->vr_parSisp->CurrentValue = ew_StrToFloat($this->vr_parSisp->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_parSisp
		// nu_versao
		// vr_parSisp
		// ds_codigoSql
		// nu_usuarioResp
		// ds_versao
		// dh_inclusao

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_parSisp
			if (strval($this->nu_parSisp->CurrentValue) <> "") {
				$sFilterWrk = "[nu_parSisp]" . ew_SearchString("=", $this->nu_parSisp->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_parSisp], [no_parSisp] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[parSisp]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_parSisp, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_parSisp] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_parSisp->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_parSisp->ViewValue = $this->nu_parSisp->CurrentValue;
				}
			} else {
				$this->nu_parSisp->ViewValue = NULL;
			}
			$this->nu_parSisp->ViewCustomAttributes = "";

			// nu_versao
			$this->nu_versao->ViewValue = $this->nu_versao->CurrentValue;
			$this->nu_versao->ViewCustomAttributes = "";

			// vr_parSisp
			$this->vr_parSisp->ViewValue = $this->vr_parSisp->CurrentValue;
			$this->vr_parSisp->ViewCustomAttributes = "";

			// ds_codigoSql
			$this->ds_codigoSql->ViewValue = $this->ds_codigoSql->CurrentValue;
			$this->ds_codigoSql->ViewCustomAttributes = "";

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

			// ds_versao
			$this->ds_versao->ViewValue = $this->ds_versao->CurrentValue;
			$this->ds_versao->ViewCustomAttributes = "";

			// dh_inclusao
			$this->dh_inclusao->ViewValue = $this->dh_inclusao->CurrentValue;
			$this->dh_inclusao->ViewValue = ew_FormatDateTime($this->dh_inclusao->ViewValue, 10);
			$this->dh_inclusao->ViewCustomAttributes = "";

			// nu_parSisp
			$this->nu_parSisp->LinkCustomAttributes = "";
			$this->nu_parSisp->HrefValue = "";
			$this->nu_parSisp->TooltipValue = "";

			// nu_versao
			$this->nu_versao->LinkCustomAttributes = "";
			$this->nu_versao->HrefValue = "";
			$this->nu_versao->TooltipValue = "";

			// vr_parSisp
			$this->vr_parSisp->LinkCustomAttributes = "";
			$this->vr_parSisp->HrefValue = "";
			$this->vr_parSisp->TooltipValue = "";

			// ds_codigoSql
			$this->ds_codigoSql->LinkCustomAttributes = "";
			$this->ds_codigoSql->HrefValue = "";
			$this->ds_codigoSql->TooltipValue = "";

			// nu_usuarioResp
			$this->nu_usuarioResp->LinkCustomAttributes = "";
			$this->nu_usuarioResp->HrefValue = "";
			$this->nu_usuarioResp->TooltipValue = "";

			// ds_versao
			$this->ds_versao->LinkCustomAttributes = "";
			$this->ds_versao->HrefValue = "";
			$this->ds_versao->TooltipValue = "";

			// dh_inclusao
			$this->dh_inclusao->LinkCustomAttributes = "";
			$this->dh_inclusao->HrefValue = "";
			$this->dh_inclusao->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nu_parSisp
			$this->nu_parSisp->EditCustomAttributes = "";
			if ($this->nu_parSisp->getSessionValue() <> "") {
				$this->nu_parSisp->CurrentValue = $this->nu_parSisp->getSessionValue();
			if (strval($this->nu_parSisp->CurrentValue) <> "") {
				$sFilterWrk = "[nu_parSisp]" . ew_SearchString("=", $this->nu_parSisp->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_parSisp], [no_parSisp] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[parSisp]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_parSisp, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_parSisp] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_parSisp->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_parSisp->ViewValue = $this->nu_parSisp->CurrentValue;
				}
			} else {
				$this->nu_parSisp->ViewValue = NULL;
			}
			$this->nu_parSisp->ViewCustomAttributes = "";
			} else {
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_parSisp], [no_parSisp] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[parSisp]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_parSisp, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_parSisp] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_parSisp->EditValue = $arwrk;
			}

			// nu_versao
			$this->nu_versao->EditCustomAttributes = "readonly";
			$this->nu_versao->EditValue = ew_HtmlEncode($this->nu_versao->CurrentValue);
			$this->nu_versao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_versao->FldCaption()));

			// vr_parSisp
			$this->vr_parSisp->EditCustomAttributes = "";
			$this->vr_parSisp->EditValue = ew_HtmlEncode($this->vr_parSisp->CurrentValue);
			$this->vr_parSisp->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_parSisp->FldCaption()));
			if (strval($this->vr_parSisp->EditValue) <> "" && is_numeric($this->vr_parSisp->EditValue)) $this->vr_parSisp->EditValue = ew_FormatNumber($this->vr_parSisp->EditValue, -2, -1, -2, 0);

			// ds_codigoSql
			$this->ds_codigoSql->EditCustomAttributes = "";
			$this->ds_codigoSql->EditValue = $this->ds_codigoSql->CurrentValue;
			$this->ds_codigoSql->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_codigoSql->FldCaption()));

			// nu_usuarioResp
			// ds_versao

			$this->ds_versao->EditCustomAttributes = "";
			$this->ds_versao->EditValue = $this->ds_versao->CurrentValue;
			$this->ds_versao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_versao->FldCaption()));

			// dh_inclusao
			// Edit refer script
			// nu_parSisp

			$this->nu_parSisp->HrefValue = "";

			// nu_versao
			$this->nu_versao->HrefValue = "";

			// vr_parSisp
			$this->vr_parSisp->HrefValue = "";

			// ds_codigoSql
			$this->ds_codigoSql->HrefValue = "";

			// nu_usuarioResp
			$this->nu_usuarioResp->HrefValue = "";

			// ds_versao
			$this->ds_versao->HrefValue = "";

			// dh_inclusao
			$this->dh_inclusao->HrefValue = "";
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
		if (!$this->nu_parSisp->FldIsDetailKey && !is_null($this->nu_parSisp->FormValue) && $this->nu_parSisp->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_parSisp->FldCaption());
		}
		if (!$this->nu_versao->FldIsDetailKey && !is_null($this->nu_versao->FormValue) && $this->nu_versao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_versao->FldCaption());
		}
		if (!ew_CheckInteger($this->nu_versao->FormValue)) {
			ew_AddMessage($gsFormError, $this->nu_versao->FldErrMsg());
		}
		if (!$this->vr_parSisp->FldIsDetailKey && !is_null($this->vr_parSisp->FormValue) && $this->vr_parSisp->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->vr_parSisp->FldCaption());
		}
		if (!ew_CheckNumber($this->vr_parSisp->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_parSisp->FldErrMsg());
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

		// nu_parSisp
		$this->nu_parSisp->SetDbValueDef($rsnew, $this->nu_parSisp->CurrentValue, 0, FALSE);

		// nu_versao
		$this->nu_versao->SetDbValueDef($rsnew, $this->nu_versao->CurrentValue, 0, FALSE);

		// vr_parSisp
		$this->vr_parSisp->SetDbValueDef($rsnew, $this->vr_parSisp->CurrentValue, NULL, FALSE);

		// ds_codigoSql
		$this->ds_codigoSql->SetDbValueDef($rsnew, $this->ds_codigoSql->CurrentValue, NULL, FALSE);

		// nu_usuarioResp
		$this->nu_usuarioResp->SetDbValueDef($rsnew, CurrentUserID(), NULL);
		$rsnew['nu_usuarioResp'] = &$this->nu_usuarioResp->DbValue;

		// ds_versao
		$this->ds_versao->SetDbValueDef($rsnew, $this->ds_versao->CurrentValue, NULL, FALSE);

		// dh_inclusao
		$this->dh_inclusao->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
		$rsnew['dh_inclusao'] = &$this->dh_inclusao->DbValue;

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && $this->nu_parSisp->CurrentValue == "" && $this->nu_parSisp->getSessionValue() == "") {
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
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
			$this->WriteAuditTrailOnAdd($rsnew);
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
			if ($sMasterTblVar == "parSisp") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_parSisp"] <> "") {
					$GLOBALS["parSisp"]->nu_parSisp->setQueryStringValue($_GET["nu_parSisp"]);
					$this->nu_parSisp->setQueryStringValue($GLOBALS["parSisp"]->nu_parSisp->QueryStringValue);
					$this->nu_parSisp->setSessionValue($this->nu_parSisp->QueryStringValue);
					if (!is_numeric($GLOBALS["parSisp"]->nu_parSisp->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "parSisp") {
				if ($this->nu_parSisp->QueryStringValue == "") $this->nu_parSisp->setSessionValue("");
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "parsisp_versaolist.php", $this->TableVar);
		$PageCaption = ($this->CurrentAction == "C") ? $Language->Phrase("Copy") : $Language->Phrase("Add");
		$Breadcrumb->Add("add", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'parsisp_versao';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'parsisp_versao';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_parSisp'];
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_versao'];

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
if (!isset($parsisp_versao_add)) $parsisp_versao_add = new cparsisp_versao_add();

// Page init
$parsisp_versao_add->Page_Init();

// Page main
$parsisp_versao_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$parsisp_versao_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var parsisp_versao_add = new ew_Page("parsisp_versao_add");
parsisp_versao_add.PageID = "add"; // Page ID
var EW_PAGE_ID = parsisp_versao_add.PageID; // For backward compatibility

// Form object
var fparsisp_versaoadd = new ew_Form("fparsisp_versaoadd");

// Validate form
fparsisp_versaoadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_parSisp");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($parsisp_versao->nu_parSisp->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_versao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($parsisp_versao->nu_versao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_versao");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($parsisp_versao->nu_versao->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vr_parSisp");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($parsisp_versao->vr_parSisp->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_parSisp");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($parsisp_versao->vr_parSisp->FldErrMsg()) ?>");

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
fparsisp_versaoadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fparsisp_versaoadd.ValidateRequired = true;
<?php } else { ?>
fparsisp_versaoadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fparsisp_versaoadd.Lists["x_nu_parSisp"] = {"LinkField":"x_nu_parSisp","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_parSisp","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fparsisp_versaoadd.Lists["x_nu_usuarioResp"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $parsisp_versao_add->ShowPageHeader(); ?>
<?php
$parsisp_versao_add->ShowMessage();
?>
<form name="fparsisp_versaoadd" id="fparsisp_versaoadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="parsisp_versao">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_parsisp_versaoadd" class="table table-bordered table-striped">
<?php if ($parsisp_versao->nu_parSisp->Visible) { // nu_parSisp ?>
	<tr id="r_nu_parSisp">
		<td><span id="elh_parsisp_versao_nu_parSisp"><?php echo $parsisp_versao->nu_parSisp->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $parsisp_versao->nu_parSisp->CellAttributes() ?>>
<?php if ($parsisp_versao->nu_parSisp->getSessionValue() <> "") { ?>
<span<?php echo $parsisp_versao->nu_parSisp->ViewAttributes() ?>>
<?php echo $parsisp_versao->nu_parSisp->ViewValue ?></span>
<input type="hidden" id="x_nu_parSisp" name="x_nu_parSisp" value="<?php echo ew_HtmlEncode($parsisp_versao->nu_parSisp->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_nu_parSisp" id="x_nu_parSisp" name="x_nu_parSisp"<?php echo $parsisp_versao->nu_parSisp->EditAttributes() ?>>
<?php
if (is_array($parsisp_versao->nu_parSisp->EditValue)) {
	$arwrk = $parsisp_versao->nu_parSisp->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($parsisp_versao->nu_parSisp->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fparsisp_versaoadd.Lists["x_nu_parSisp"].Options = <?php echo (is_array($parsisp_versao->nu_parSisp->EditValue)) ? ew_ArrayToJson($parsisp_versao->nu_parSisp->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<?php echo $parsisp_versao->nu_parSisp->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($parsisp_versao->nu_versao->Visible) { // nu_versao ?>
	<tr id="r_nu_versao">
		<td><span id="elh_parsisp_versao_nu_versao"><?php echo $parsisp_versao->nu_versao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $parsisp_versao->nu_versao->CellAttributes() ?>>
<span id="el_parsisp_versao_nu_versao" class="control-group">
<input type="text" data-field="x_nu_versao" name="x_nu_versao" id="x_nu_versao" size="30" placeholder="<?php echo $parsisp_versao->nu_versao->PlaceHolder ?>" value="<?php echo $parsisp_versao->nu_versao->EditValue ?>"<?php echo $parsisp_versao->nu_versao->EditAttributes() ?>>
</span>
<?php echo $parsisp_versao->nu_versao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($parsisp_versao->vr_parSisp->Visible) { // vr_parSisp ?>
	<tr id="r_vr_parSisp">
		<td><span id="elh_parsisp_versao_vr_parSisp"><?php echo $parsisp_versao->vr_parSisp->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $parsisp_versao->vr_parSisp->CellAttributes() ?>>
<span id="el_parsisp_versao_vr_parSisp" class="control-group">
<input type="text" data-field="x_vr_parSisp" name="x_vr_parSisp" id="x_vr_parSisp" size="30" maxlength="50" placeholder="<?php echo $parsisp_versao->vr_parSisp->PlaceHolder ?>" value="<?php echo $parsisp_versao->vr_parSisp->EditValue ?>"<?php echo $parsisp_versao->vr_parSisp->EditAttributes() ?>>
</span>
<?php echo $parsisp_versao->vr_parSisp->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($parsisp_versao->ds_codigoSql->Visible) { // ds_codigoSql ?>
	<tr id="r_ds_codigoSql">
		<td><span id="elh_parsisp_versao_ds_codigoSql"><?php echo $parsisp_versao->ds_codigoSql->FldCaption() ?></span></td>
		<td<?php echo $parsisp_versao->ds_codigoSql->CellAttributes() ?>>
<span id="el_parsisp_versao_ds_codigoSql" class="control-group">
<textarea data-field="x_ds_codigoSql" name="x_ds_codigoSql" id="x_ds_codigoSql" cols="35" rows="4" placeholder="<?php echo $parsisp_versao->ds_codigoSql->PlaceHolder ?>"<?php echo $parsisp_versao->ds_codigoSql->EditAttributes() ?>><?php echo $parsisp_versao->ds_codigoSql->EditValue ?></textarea>
</span>
<?php echo $parsisp_versao->ds_codigoSql->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($parsisp_versao->ds_versao->Visible) { // ds_versao ?>
	<tr id="r_ds_versao">
		<td><span id="elh_parsisp_versao_ds_versao"><?php echo $parsisp_versao->ds_versao->FldCaption() ?></span></td>
		<td<?php echo $parsisp_versao->ds_versao->CellAttributes() ?>>
<span id="el_parsisp_versao_ds_versao" class="control-group">
<textarea data-field="x_ds_versao" name="x_ds_versao" id="x_ds_versao" cols="35" rows="4" placeholder="<?php echo $parsisp_versao->ds_versao->PlaceHolder ?>"<?php echo $parsisp_versao->ds_versao->EditAttributes() ?>><?php echo $parsisp_versao->ds_versao->EditValue ?></textarea>
</span>
<?php echo $parsisp_versao->ds_versao->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fparsisp_versaoadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$parsisp_versao_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$parsisp_versao_add->Page_Terminate();
?>
