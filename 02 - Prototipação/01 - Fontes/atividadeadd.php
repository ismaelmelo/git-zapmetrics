<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "atividadeinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "atividade_papelgridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$atividade_add = NULL; // Initialize page object first

class catividade_add extends catividade {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'atividade';

	// Page object name
	var $PageObjName = 'atividade_add';

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

		// Table object (atividade)
		if (!isset($GLOBALS["atividade"])) {
			$GLOBALS["atividade"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["atividade"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'atividade', TRUE);

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
			$this->Page_Terminate("atividadelist.php");
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
			if (@$_GET["nu_atividade"] != "") {
				$this->nu_atividade->setQueryStringValue($_GET["nu_atividade"]);
				$this->setKey("nu_atividade", $this->nu_atividade->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_atividade", ""); // Clear key
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
					$this->Page_Terminate("atividadelist.php"); // No matching record, return to list
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
					if (ew_GetPageName($sReturnUrl) == "atividadeview.php")
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
		$this->nu_processo->CurrentValue = NULL;
		$this->nu_processo->OldValue = $this->nu_processo->CurrentValue;
		$this->nu_atividadePred->CurrentValue = NULL;
		$this->nu_atividadePred->OldValue = $this->nu_atividadePred->CurrentValue;
		$this->no_atividade->CurrentValue = NULL;
		$this->no_atividade->OldValue = $this->no_atividade->CurrentValue;
		$this->ds_atividade->CurrentValue = NULL;
		$this->ds_atividade->OldValue = $this->ds_atividade->CurrentValue;
		$this->vr_duracao->CurrentValue = "1";
		$this->ic_ativo->CurrentValue = "S";
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_processo->FldIsDetailKey) {
			$this->nu_processo->setFormValue($objForm->GetValue("x_nu_processo"));
		}
		if (!$this->nu_atividadePred->FldIsDetailKey) {
			$this->nu_atividadePred->setFormValue($objForm->GetValue("x_nu_atividadePred"));
		}
		if (!$this->no_atividade->FldIsDetailKey) {
			$this->no_atividade->setFormValue($objForm->GetValue("x_no_atividade"));
		}
		if (!$this->ds_atividade->FldIsDetailKey) {
			$this->ds_atividade->setFormValue($objForm->GetValue("x_ds_atividade"));
		}
		if (!$this->vr_duracao->FldIsDetailKey) {
			$this->vr_duracao->setFormValue($objForm->GetValue("x_vr_duracao"));
		}
		if (!$this->ic_ativo->FldIsDetailKey) {
			$this->ic_ativo->setFormValue($objForm->GetValue("x_ic_ativo"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->nu_processo->CurrentValue = $this->nu_processo->FormValue;
		$this->nu_atividadePred->CurrentValue = $this->nu_atividadePred->FormValue;
		$this->no_atividade->CurrentValue = $this->no_atividade->FormValue;
		$this->ds_atividade->CurrentValue = $this->ds_atividade->FormValue;
		$this->vr_duracao->CurrentValue = $this->vr_duracao->FormValue;
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
		$this->nu_atividade->setDbValue($rs->fields('nu_atividade'));
		$this->nu_processo->setDbValue($rs->fields('nu_processo'));
		$this->nu_atividadePred->setDbValue($rs->fields('nu_atividadePred'));
		$this->no_atividade->setDbValue($rs->fields('no_atividade'));
		$this->ds_atividade->setDbValue($rs->fields('ds_atividade'));
		$this->vr_duracao->setDbValue($rs->fields('vr_duracao'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_atividade->DbValue = $row['nu_atividade'];
		$this->nu_processo->DbValue = $row['nu_processo'];
		$this->nu_atividadePred->DbValue = $row['nu_atividadePred'];
		$this->no_atividade->DbValue = $row['no_atividade'];
		$this->ds_atividade->DbValue = $row['ds_atividade'];
		$this->vr_duracao->DbValue = $row['vr_duracao'];
		$this->ic_ativo->DbValue = $row['ic_ativo'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_atividade")) <> "")
			$this->nu_atividade->CurrentValue = $this->getKey("nu_atividade"); // nu_atividade
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

		if ($this->vr_duracao->FormValue == $this->vr_duracao->CurrentValue && is_numeric(ew_StrToFloat($this->vr_duracao->CurrentValue)))
			$this->vr_duracao->CurrentValue = ew_StrToFloat($this->vr_duracao->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_atividade
		// nu_processo
		// nu_atividadePred
		// no_atividade
		// ds_atividade
		// vr_duracao
		// ic_ativo

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_processo
			$this->nu_processo->ViewValue = $this->nu_processo->CurrentValue;
			if (strval($this->nu_processo->CurrentValue) <> "") {
				$sFilterWrk = "[nu_processo]" . ew_SearchString("=", $this->nu_processo->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_processo], [no_processo] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[processo]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_processo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_processo] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_processo->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_processo->ViewValue = $this->nu_processo->CurrentValue;
				}
			} else {
				$this->nu_processo->ViewValue = NULL;
			}
			$this->nu_processo->ViewCustomAttributes = "";

			// nu_atividadePred
			if (strval($this->nu_atividadePred->CurrentValue) <> "") {
				$sFilterWrk = "[nu_atividade]" . ew_SearchString("=", $this->nu_atividadePred->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_atividade], [no_atividade] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[atividade]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_atividadePred, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_atividade] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_atividadePred->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_atividadePred->ViewValue = $this->nu_atividadePred->CurrentValue;
				}
			} else {
				$this->nu_atividadePred->ViewValue = NULL;
			}
			$this->nu_atividadePred->ViewCustomAttributes = "";

			// no_atividade
			$this->no_atividade->ViewValue = $this->no_atividade->CurrentValue;
			$this->no_atividade->ViewCustomAttributes = "";

			// ds_atividade
			$this->ds_atividade->ViewValue = $this->ds_atividade->CurrentValue;
			$this->ds_atividade->ViewCustomAttributes = "";

			// vr_duracao
			$this->vr_duracao->ViewValue = $this->vr_duracao->CurrentValue;
			$this->vr_duracao->ViewValue = ew_FormatNumber($this->vr_duracao->ViewValue, 2, -2, -2, -2);
			$this->vr_duracao->ViewCustomAttributes = "";

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

			// nu_processo
			$this->nu_processo->LinkCustomAttributes = "";
			$this->nu_processo->HrefValue = "";
			$this->nu_processo->TooltipValue = "";

			// nu_atividadePred
			$this->nu_atividadePred->LinkCustomAttributes = "";
			$this->nu_atividadePred->HrefValue = "";
			$this->nu_atividadePred->TooltipValue = "";

			// no_atividade
			$this->no_atividade->LinkCustomAttributes = "";
			$this->no_atividade->HrefValue = "";
			$this->no_atividade->TooltipValue = "";

			// ds_atividade
			$this->ds_atividade->LinkCustomAttributes = "";
			$this->ds_atividade->HrefValue = "";
			$this->ds_atividade->TooltipValue = "";

			// vr_duracao
			$this->vr_duracao->LinkCustomAttributes = "";
			$this->vr_duracao->HrefValue = "";
			$this->vr_duracao->TooltipValue = "";

			// ic_ativo
			$this->ic_ativo->LinkCustomAttributes = "";
			$this->ic_ativo->HrefValue = "";
			$this->ic_ativo->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nu_processo
			$this->nu_processo->EditCustomAttributes = "";
			$this->nu_processo->EditValue = ew_HtmlEncode($this->nu_processo->CurrentValue);
			if (strval($this->nu_processo->CurrentValue) <> "") {
				$sFilterWrk = "[nu_processo]" . ew_SearchString("=", $this->nu_processo->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_processo], [no_processo] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[processo]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_processo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_processo] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_processo->EditValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_processo->EditValue = $this->nu_processo->CurrentValue;
				}
			} else {
				$this->nu_processo->EditValue = NULL;
			}
			$this->nu_processo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_processo->FldCaption()));

			// nu_atividadePred
			$this->nu_atividadePred->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_atividade], [no_atividade] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [nu_processo] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[atividade]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_atividadePred, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_atividade] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_atividadePred->EditValue = $arwrk;

			// no_atividade
			$this->no_atividade->EditCustomAttributes = "";
			$this->no_atividade->EditValue = ew_HtmlEncode($this->no_atividade->CurrentValue);
			$this->no_atividade->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_atividade->FldCaption()));

			// ds_atividade
			$this->ds_atividade->EditCustomAttributes = "";
			$this->ds_atividade->EditValue = $this->ds_atividade->CurrentValue;
			$this->ds_atividade->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_atividade->FldCaption()));

			// vr_duracao
			$this->vr_duracao->EditCustomAttributes = "";
			$this->vr_duracao->EditValue = ew_HtmlEncode($this->vr_duracao->CurrentValue);
			$this->vr_duracao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_duracao->FldCaption()));
			if (strval($this->vr_duracao->EditValue) <> "" && is_numeric($this->vr_duracao->EditValue)) $this->vr_duracao->EditValue = ew_FormatNumber($this->vr_duracao->EditValue, -2, -2, -2, -2);

			// ic_ativo
			$this->ic_ativo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_ativo->FldTagValue(1), $this->ic_ativo->FldTagCaption(1) <> "" ? $this->ic_ativo->FldTagCaption(1) : $this->ic_ativo->FldTagValue(1));
			$arwrk[] = array($this->ic_ativo->FldTagValue(2), $this->ic_ativo->FldTagCaption(2) <> "" ? $this->ic_ativo->FldTagCaption(2) : $this->ic_ativo->FldTagValue(2));
			$this->ic_ativo->EditValue = $arwrk;

			// Edit refer script
			// nu_processo

			$this->nu_processo->HrefValue = "";

			// nu_atividadePred
			$this->nu_atividadePred->HrefValue = "";

			// no_atividade
			$this->no_atividade->HrefValue = "";

			// ds_atividade
			$this->ds_atividade->HrefValue = "";

			// vr_duracao
			$this->vr_duracao->HrefValue = "";

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
		if (!$this->nu_processo->FldIsDetailKey && !is_null($this->nu_processo->FormValue) && $this->nu_processo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_processo->FldCaption());
		}
		if (!ew_CheckInteger($this->nu_processo->FormValue)) {
			ew_AddMessage($gsFormError, $this->nu_processo->FldErrMsg());
		}
		if (!$this->no_atividade->FldIsDetailKey && !is_null($this->no_atividade->FormValue) && $this->no_atividade->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_atividade->FldCaption());
		}
		if (!$this->vr_duracao->FldIsDetailKey && !is_null($this->vr_duracao->FormValue) && $this->vr_duracao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->vr_duracao->FldCaption());
		}
		if (!ew_CheckNumber($this->vr_duracao->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_duracao->FldErrMsg());
		}
		if ($this->ic_ativo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_ativo->FldCaption());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("atividade_papel", $DetailTblVar) && $GLOBALS["atividade_papel"]->DetailAdd) {
			if (!isset($GLOBALS["atividade_papel_grid"])) $GLOBALS["atividade_papel_grid"] = new catividade_papel_grid(); // get detail page object
			$GLOBALS["atividade_papel_grid"]->ValidateGridForm();
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

		// nu_processo
		$this->nu_processo->SetDbValueDef($rsnew, $this->nu_processo->CurrentValue, NULL, FALSE);

		// nu_atividadePred
		$this->nu_atividadePred->SetDbValueDef($rsnew, $this->nu_atividadePred->CurrentValue, NULL, FALSE);

		// no_atividade
		$this->no_atividade->SetDbValueDef($rsnew, $this->no_atividade->CurrentValue, NULL, FALSE);

		// ds_atividade
		$this->ds_atividade->SetDbValueDef($rsnew, $this->ds_atividade->CurrentValue, NULL, FALSE);

		// vr_duracao
		$this->vr_duracao->SetDbValueDef($rsnew, $this->vr_duracao->CurrentValue, NULL, FALSE);

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
			$this->nu_atividade->setDbValue($conn->Insert_ID());
			$rsnew['nu_atividade'] = $this->nu_atividade->DbValue;
		}

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("atividade_papel", $DetailTblVar) && $GLOBALS["atividade_papel"]->DetailAdd) {
				$GLOBALS["atividade_papel"]->nu_atividade->setSessionValue($this->nu_atividade->CurrentValue); // Set master key
				if (!isset($GLOBALS["atividade_papel_grid"])) $GLOBALS["atividade_papel_grid"] = new catividade_papel_grid(); // Get detail page object
				$AddRow = $GLOBALS["atividade_papel_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["atividade_papel"]->nu_atividade->setSessionValue(""); // Clear master key if insert failed
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
			if (in_array("atividade_papel", $DetailTblVar)) {
				if (!isset($GLOBALS["atividade_papel_grid"]))
					$GLOBALS["atividade_papel_grid"] = new catividade_papel_grid;
				if ($GLOBALS["atividade_papel_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["atividade_papel_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["atividade_papel_grid"]->CurrentMode = "add";
					$GLOBALS["atividade_papel_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["atividade_papel_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["atividade_papel_grid"]->setStartRecordNumber(1);
					$GLOBALS["atividade_papel_grid"]->nu_atividade->FldIsDetailKey = TRUE;
					$GLOBALS["atividade_papel_grid"]->nu_atividade->CurrentValue = $this->nu_atividade->CurrentValue;
					$GLOBALS["atividade_papel_grid"]->nu_atividade->setSessionValue($GLOBALS["atividade_papel_grid"]->nu_atividade->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "atividadelist.php", $this->TableVar);
		$PageCaption = ($this->CurrentAction == "C") ? $Language->Phrase("Copy") : $Language->Phrase("Add");
		$Breadcrumb->Add("add", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'atividade';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'atividade';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_atividade'];

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
if (!isset($atividade_add)) $atividade_add = new catividade_add();

// Page init
$atividade_add->Page_Init();

// Page main
$atividade_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$atividade_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var atividade_add = new ew_Page("atividade_add");
atividade_add.PageID = "add"; // Page ID
var EW_PAGE_ID = atividade_add.PageID; // For backward compatibility

// Form object
var fatividadeadd = new ew_Form("fatividadeadd");

// Validate form
fatividadeadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_processo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($atividade->nu_processo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_processo");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($atividade->nu_processo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_no_atividade");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($atividade->no_atividade->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_duracao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($atividade->vr_duracao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_duracao");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($atividade->vr_duracao->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ic_ativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($atividade->ic_ativo->FldCaption()) ?>");

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
fatividadeadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fatividadeadd.ValidateRequired = true;
<?php } else { ?>
fatividadeadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fatividadeadd.Lists["x_nu_processo"] = {"LinkField":"x_nu_processo","Ajax":true,"AutoFill":false,"DisplayFields":["x_no_processo","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fatividadeadd.Lists["x_nu_atividadePred"] = {"LinkField":"x_nu_atividade","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_atividade","","",""],"ParentFields":["x_nu_processo"],"FilterFields":["x_nu_processo"],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $atividade_add->ShowPageHeader(); ?>
<?php
$atividade_add->ShowMessage();
?>
<form name="fatividadeadd" id="fatividadeadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="atividade">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_atividadeadd" class="table table-bordered table-striped">
<?php if ($atividade->nu_processo->Visible) { // nu_processo ?>
	<tr id="r_nu_processo">
		<td><span id="elh_atividade_nu_processo"><?php echo $atividade->nu_processo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $atividade->nu_processo->CellAttributes() ?>>
<span id="el_atividade_nu_processo" class="control-group">
<?php
	$wrkonchange = trim("ew_UpdateOpt.call(this, ['x_nu_atividadePred']); " . @$atividade->nu_processo->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$atividade->nu_processo->EditAttrs["onchange"] = "";
?>
<span id="as_x_nu_processo" style="white-space: nowrap; z-index: 8980">
	<input type="text" name="sv_x_nu_processo" id="sv_x_nu_processo" value="<?php echo $atividade->nu_processo->EditValue ?>" size="30" placeholder="<?php echo $atividade->nu_processo->PlaceHolder ?>"<?php echo $atividade->nu_processo->EditAttributes() ?>>&nbsp;<span id="em_x_nu_processo" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_nu_processo" style="display: inline; z-index: 8980"></div>
</span>
<input type="hidden" data-field="x_nu_processo" name="x_nu_processo" id="x_nu_processo" value="<?php echo $atividade->nu_processo->CurrentValue ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT  TOP " . EW_AUTO_SUGGEST_MAX_ENTRIES . " [nu_processo], [no_processo] AS [DispFld] FROM [dbo].[processo]";
$sWhereWrk = "[no_processo] LIKE '%{query_value}%'";
$lookuptblfilter = "[ic_ativo]='S'";
if (strval($lookuptblfilter) <> "") {
	ew_AddFilter($sWhereWrk, $lookuptblfilter);
}

// Call Lookup selecting
$atividade->Lookup_Selecting($atividade->nu_processo, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY [no_processo] ASC";
?>
<input type="hidden" name="q_x_nu_processo" id="q_x_nu_processo" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x_nu_processo", fatividadeadd, true, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_nu_processo") + ar[i] : "";
	return dv;
}
fatividadeadd.AutoSuggests["x_nu_processo"] = oas;
</script>
</span>
<?php echo $atividade->nu_processo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($atividade->nu_atividadePred->Visible) { // nu_atividadePred ?>
	<tr id="r_nu_atividadePred">
		<td><span id="elh_atividade_nu_atividadePred"><?php echo $atividade->nu_atividadePred->FldCaption() ?></span></td>
		<td<?php echo $atividade->nu_atividadePred->CellAttributes() ?>>
<span id="el_atividade_nu_atividadePred" class="control-group">
<select data-field="x_nu_atividadePred" id="x_nu_atividadePred" name="x_nu_atividadePred"<?php echo $atividade->nu_atividadePred->EditAttributes() ?>>
<?php
if (is_array($atividade->nu_atividadePred->EditValue)) {
	$arwrk = $atividade->nu_atividadePred->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($atividade->nu_atividadePred->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fatividadeadd.Lists["x_nu_atividadePred"].Options = <?php echo (is_array($atividade->nu_atividadePred->EditValue)) ? ew_ArrayToJson($atividade->nu_atividadePred->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $atividade->nu_atividadePred->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($atividade->no_atividade->Visible) { // no_atividade ?>
	<tr id="r_no_atividade">
		<td><span id="elh_atividade_no_atividade"><?php echo $atividade->no_atividade->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $atividade->no_atividade->CellAttributes() ?>>
<span id="el_atividade_no_atividade" class="control-group">
<input type="text" data-field="x_no_atividade" name="x_no_atividade" id="x_no_atividade" size="30" maxlength="100" placeholder="<?php echo $atividade->no_atividade->PlaceHolder ?>" value="<?php echo $atividade->no_atividade->EditValue ?>"<?php echo $atividade->no_atividade->EditAttributes() ?>>
</span>
<?php echo $atividade->no_atividade->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($atividade->ds_atividade->Visible) { // ds_atividade ?>
	<tr id="r_ds_atividade">
		<td><span id="elh_atividade_ds_atividade"><?php echo $atividade->ds_atividade->FldCaption() ?></span></td>
		<td<?php echo $atividade->ds_atividade->CellAttributes() ?>>
<span id="el_atividade_ds_atividade" class="control-group">
<textarea data-field="x_ds_atividade" name="x_ds_atividade" id="x_ds_atividade" cols="35" rows="4" placeholder="<?php echo $atividade->ds_atividade->PlaceHolder ?>"<?php echo $atividade->ds_atividade->EditAttributes() ?>><?php echo $atividade->ds_atividade->EditValue ?></textarea>
</span>
<?php echo $atividade->ds_atividade->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($atividade->vr_duracao->Visible) { // vr_duracao ?>
	<tr id="r_vr_duracao">
		<td><span id="elh_atividade_vr_duracao"><?php echo $atividade->vr_duracao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $atividade->vr_duracao->CellAttributes() ?>>
<span id="el_atividade_vr_duracao" class="control-group">
<input type="text" data-field="x_vr_duracao" name="x_vr_duracao" id="x_vr_duracao" size="30" placeholder="<?php echo $atividade->vr_duracao->PlaceHolder ?>" value="<?php echo $atividade->vr_duracao->EditValue ?>"<?php echo $atividade->vr_duracao->EditAttributes() ?>>
</span>
<?php echo $atividade->vr_duracao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($atividade->ic_ativo->Visible) { // ic_ativo ?>
	<tr id="r_ic_ativo">
		<td><span id="elh_atividade_ic_ativo"><?php echo $atividade->ic_ativo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $atividade->ic_ativo->CellAttributes() ?>>
<span id="el_atividade_ic_ativo" class="control-group">
<div id="tp_x_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_ativo" id="x_ic_ativo" value="{value}"<?php echo $atividade->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $atividade->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($atividade->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x_ic_ativo" id="x_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $atividade->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $atividade->ic_ativo->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<?php
	if (in_array("atividade_papel", explode(",", $atividade->getCurrentDetailTable())) && $atividade_papel->DetailAdd) {
?>
<?php include_once "atividade_papelgrid.php" ?>
<?php } ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fatividadeadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$atividade_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$atividade_add->Page_Terminate();
?>
