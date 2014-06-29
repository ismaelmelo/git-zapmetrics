<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "ciialternativainfo.php" ?>
<?php include_once "ciiquestaoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$ciialternativa_add = NULL; // Initialize page object first

class cciialternativa_add extends cciialternativa {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'ciialternativa';

	// Page object name
	var $PageObjName = 'ciialternativa_add';

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

		// Table object (ciialternativa)
		if (!isset($GLOBALS["ciialternativa"])) {
			$GLOBALS["ciialternativa"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["ciialternativa"];
		}

		// Table object (ciiquestao)
		if (!isset($GLOBALS['ciiquestao'])) $GLOBALS['ciiquestao'] = new cciiquestao();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'ciialternativa', TRUE);

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
			$this->Page_Terminate("ciialternativalist.php");
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
			if (@$_GET["nu_alternativa"] != "") {
				$this->nu_alternativa->setQueryStringValue($_GET["nu_alternativa"]);
				$this->setKey("nu_alternativa", $this->nu_alternativa->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_alternativa", ""); // Clear key
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
					$this->Page_Terminate("ciialternativalist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "ciialternativaview.php")
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
		$this->co_questao->CurrentValue = NULL;
		$this->co_questao->OldValue = $this->co_questao->CurrentValue;
		$this->no_alternativa->CurrentValue = NULL;
		$this->no_alternativa->OldValue = $this->no_alternativa->CurrentValue;
		$this->ds_alternativa->CurrentValue = NULL;
		$this->ds_alternativa->OldValue = $this->ds_alternativa->CurrentValue;
		$this->vr_alternativa->CurrentValue = NULL;
		$this->vr_alternativa->OldValue = $this->vr_alternativa->CurrentValue;
		$this->nu_peso->CurrentValue = NULL;
		$this->nu_peso->OldValue = $this->nu_peso->CurrentValue;
		$this->ic_ativo->CurrentValue = NULL;
		$this->ic_ativo->OldValue = $this->ic_ativo->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->co_questao->FldIsDetailKey) {
			$this->co_questao->setFormValue($objForm->GetValue("x_co_questao"));
		}
		if (!$this->no_alternativa->FldIsDetailKey) {
			$this->no_alternativa->setFormValue($objForm->GetValue("x_no_alternativa"));
		}
		if (!$this->ds_alternativa->FldIsDetailKey) {
			$this->ds_alternativa->setFormValue($objForm->GetValue("x_ds_alternativa"));
		}
		if (!$this->vr_alternativa->FldIsDetailKey) {
			$this->vr_alternativa->setFormValue($objForm->GetValue("x_vr_alternativa"));
		}
		if (!$this->nu_peso->FldIsDetailKey) {
			$this->nu_peso->setFormValue($objForm->GetValue("x_nu_peso"));
		}
		if (!$this->ic_ativo->FldIsDetailKey) {
			$this->ic_ativo->setFormValue($objForm->GetValue("x_ic_ativo"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->co_questao->CurrentValue = $this->co_questao->FormValue;
		$this->no_alternativa->CurrentValue = $this->no_alternativa->FormValue;
		$this->ds_alternativa->CurrentValue = $this->ds_alternativa->FormValue;
		$this->vr_alternativa->CurrentValue = $this->vr_alternativa->FormValue;
		$this->nu_peso->CurrentValue = $this->nu_peso->FormValue;
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
		$this->nu_alternativa->setDbValue($rs->fields('nu_alternativa'));
		$this->co_questao->setDbValue($rs->fields('co_questao'));
		$this->no_alternativa->setDbValue($rs->fields('no_alternativa'));
		$this->ds_alternativa->setDbValue($rs->fields('ds_alternativa'));
		$this->vr_alternativa->setDbValue($rs->fields('vr_alternativa'));
		$this->nu_peso->setDbValue($rs->fields('nu_peso'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_alternativa->DbValue = $row['nu_alternativa'];
		$this->co_questao->DbValue = $row['co_questao'];
		$this->no_alternativa->DbValue = $row['no_alternativa'];
		$this->ds_alternativa->DbValue = $row['ds_alternativa'];
		$this->vr_alternativa->DbValue = $row['vr_alternativa'];
		$this->nu_peso->DbValue = $row['nu_peso'];
		$this->ic_ativo->DbValue = $row['ic_ativo'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_alternativa")) <> "")
			$this->nu_alternativa->CurrentValue = $this->getKey("nu_alternativa"); // nu_alternativa
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

		if ($this->vr_alternativa->FormValue == $this->vr_alternativa->CurrentValue && is_numeric(ew_StrToFloat($this->vr_alternativa->CurrentValue)))
			$this->vr_alternativa->CurrentValue = ew_StrToFloat($this->vr_alternativa->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_alternativa
		// co_questao
		// no_alternativa
		// ds_alternativa
		// vr_alternativa
		// nu_peso
		// ic_ativo

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_alternativa
			$this->nu_alternativa->ViewValue = $this->nu_alternativa->CurrentValue;
			$this->nu_alternativa->ViewCustomAttributes = "";

			// co_questao
			if (strval($this->co_questao->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_questao->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_questao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_questao->ViewValue = $rswrk->fields('DispFld');
					$this->co_questao->ViewValue .= ew_ValueSeparator(1,$this->co_questao) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_questao->ViewValue = $this->co_questao->CurrentValue;
				}
			} else {
				$this->co_questao->ViewValue = NULL;
			}
			$this->co_questao->ViewCustomAttributes = "";

			// no_alternativa
			$this->no_alternativa->ViewValue = $this->no_alternativa->CurrentValue;
			$this->no_alternativa->ViewCustomAttributes = "";

			// ds_alternativa
			$this->ds_alternativa->ViewValue = $this->ds_alternativa->CurrentValue;
			$this->ds_alternativa->ViewCustomAttributes = "";

			// vr_alternativa
			$this->vr_alternativa->ViewValue = $this->vr_alternativa->CurrentValue;
			$this->vr_alternativa->ViewCustomAttributes = "";

			// nu_peso
			$this->nu_peso->ViewValue = $this->nu_peso->CurrentValue;
			$this->nu_peso->ViewCustomAttributes = "";

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

			// co_questao
			$this->co_questao->LinkCustomAttributes = "";
			$this->co_questao->HrefValue = "";
			$this->co_questao->TooltipValue = "";

			// no_alternativa
			$this->no_alternativa->LinkCustomAttributes = "";
			$this->no_alternativa->HrefValue = "";
			$this->no_alternativa->TooltipValue = "";

			// ds_alternativa
			$this->ds_alternativa->LinkCustomAttributes = "";
			$this->ds_alternativa->HrefValue = "";
			$this->ds_alternativa->TooltipValue = "";

			// vr_alternativa
			$this->vr_alternativa->LinkCustomAttributes = "";
			$this->vr_alternativa->HrefValue = "";
			$this->vr_alternativa->TooltipValue = "";

			// nu_peso
			$this->nu_peso->LinkCustomAttributes = "";
			$this->nu_peso->HrefValue = "";
			$this->nu_peso->TooltipValue = "";

			// ic_ativo
			$this->ic_ativo->LinkCustomAttributes = "";
			$this->ic_ativo->HrefValue = "";
			$this->ic_ativo->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// co_questao
			$this->co_questao->EditCustomAttributes = "";
			if ($this->co_questao->getSessionValue() <> "") {
				$this->co_questao->CurrentValue = $this->co_questao->getSessionValue();
			if (strval($this->co_questao->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_questao->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_questao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_questao->ViewValue = $rswrk->fields('DispFld');
					$this->co_questao->ViewValue .= ew_ValueSeparator(1,$this->co_questao) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_questao->ViewValue = $this->co_questao->CurrentValue;
				}
			} else {
				$this->co_questao->ViewValue = NULL;
			}
			$this->co_questao->ViewCustomAttributes = "";
			} else {
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_questao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->co_questao->EditValue = $arwrk;
			}

			// no_alternativa
			$this->no_alternativa->EditCustomAttributes = "";
			$this->no_alternativa->EditValue = ew_HtmlEncode($this->no_alternativa->CurrentValue);
			$this->no_alternativa->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_alternativa->FldCaption()));

			// ds_alternativa
			$this->ds_alternativa->EditCustomAttributes = "";
			$this->ds_alternativa->EditValue = $this->ds_alternativa->CurrentValue;
			$this->ds_alternativa->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_alternativa->FldCaption()));

			// vr_alternativa
			$this->vr_alternativa->EditCustomAttributes = "";
			$this->vr_alternativa->EditValue = ew_HtmlEncode($this->vr_alternativa->CurrentValue);
			$this->vr_alternativa->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_alternativa->FldCaption()));
			if (strval($this->vr_alternativa->EditValue) <> "" && is_numeric($this->vr_alternativa->EditValue)) $this->vr_alternativa->EditValue = ew_FormatNumber($this->vr_alternativa->EditValue, -2, -1, -2, 0);

			// nu_peso
			$this->nu_peso->EditCustomAttributes = "";
			$this->nu_peso->EditValue = ew_HtmlEncode($this->nu_peso->CurrentValue);
			$this->nu_peso->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_peso->FldCaption()));

			// ic_ativo
			$this->ic_ativo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_ativo->FldTagValue(1), $this->ic_ativo->FldTagCaption(1) <> "" ? $this->ic_ativo->FldTagCaption(1) : $this->ic_ativo->FldTagValue(1));
			$arwrk[] = array($this->ic_ativo->FldTagValue(2), $this->ic_ativo->FldTagCaption(2) <> "" ? $this->ic_ativo->FldTagCaption(2) : $this->ic_ativo->FldTagValue(2));
			$this->ic_ativo->EditValue = $arwrk;

			// Edit refer script
			// co_questao

			$this->co_questao->HrefValue = "";

			// no_alternativa
			$this->no_alternativa->HrefValue = "";

			// ds_alternativa
			$this->ds_alternativa->HrefValue = "";

			// vr_alternativa
			$this->vr_alternativa->HrefValue = "";

			// nu_peso
			$this->nu_peso->HrefValue = "";

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
		if (!$this->co_questao->FldIsDetailKey && !is_null($this->co_questao->FormValue) && $this->co_questao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->co_questao->FldCaption());
		}
		if (!$this->no_alternativa->FldIsDetailKey && !is_null($this->no_alternativa->FormValue) && $this->no_alternativa->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_alternativa->FldCaption());
		}
		if (!$this->vr_alternativa->FldIsDetailKey && !is_null($this->vr_alternativa->FormValue) && $this->vr_alternativa->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->vr_alternativa->FldCaption());
		}
		if (!ew_CheckNumber($this->vr_alternativa->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_alternativa->FldErrMsg());
		}
		if (!ew_CheckInteger($this->nu_peso->FormValue)) {
			ew_AddMessage($gsFormError, $this->nu_peso->FldErrMsg());
		}
		if ($this->ic_ativo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_ativo->FldCaption());
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

		// co_questao
		$this->co_questao->SetDbValueDef($rsnew, $this->co_questao->CurrentValue, "", FALSE);

		// no_alternativa
		$this->no_alternativa->SetDbValueDef($rsnew, $this->no_alternativa->CurrentValue, NULL, FALSE);

		// ds_alternativa
		$this->ds_alternativa->SetDbValueDef($rsnew, $this->ds_alternativa->CurrentValue, NULL, FALSE);

		// vr_alternativa
		$this->vr_alternativa->SetDbValueDef($rsnew, $this->vr_alternativa->CurrentValue, NULL, FALSE);

		// nu_peso
		$this->nu_peso->SetDbValueDef($rsnew, $this->nu_peso->CurrentValue, NULL, FALSE);

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
			$this->nu_alternativa->setDbValue($conn->Insert_ID());
			$rsnew['nu_alternativa'] = $this->nu_alternativa->DbValue;
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
			if ($sMasterTblVar == "ciiquestao") {
				$bValidMaster = TRUE;
				if (@$_GET["co_questao"] <> "") {
					$GLOBALS["ciiquestao"]->co_questao->setQueryStringValue($_GET["co_questao"]);
					$this->co_questao->setQueryStringValue($GLOBALS["ciiquestao"]->co_questao->QueryStringValue);
					$this->co_questao->setSessionValue($this->co_questao->QueryStringValue);
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
			if ($sMasterTblVar <> "ciiquestao") {
				if ($this->co_questao->QueryStringValue == "") $this->co_questao->setSessionValue("");
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "ciialternativalist.php", $this->TableVar);
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
if (!isset($ciialternativa_add)) $ciialternativa_add = new cciialternativa_add();

// Page init
$ciialternativa_add->Page_Init();

// Page main
$ciialternativa_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$ciialternativa_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var ciialternativa_add = new ew_Page("ciialternativa_add");
ciialternativa_add.PageID = "add"; // Page ID
var EW_PAGE_ID = ciialternativa_add.PageID; // For backward compatibility

// Form object
var fciialternativaadd = new ew_Form("fciialternativaadd");

// Validate form
fciialternativaadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_co_questao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ciialternativa->co_questao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no_alternativa");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ciialternativa->no_alternativa->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_alternativa");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ciialternativa->vr_alternativa->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_alternativa");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ciialternativa->vr_alternativa->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nu_peso");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ciialternativa->nu_peso->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ic_ativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ciialternativa->ic_ativo->FldCaption()) ?>");

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
fciialternativaadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fciialternativaadd.ValidateRequired = true;
<?php } else { ?>
fciialternativaadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fciialternativaadd.Lists["x_co_questao"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $ciialternativa_add->ShowPageHeader(); ?>
<?php
$ciialternativa_add->ShowMessage();
?>
<form name="fciialternativaadd" id="fciialternativaadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="ciialternativa">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_ciialternativaadd" class="table table-bordered table-striped">
<?php if ($ciialternativa->co_questao->Visible) { // co_questao ?>
	<tr id="r_co_questao">
		<td><span id="elh_ciialternativa_co_questao"><?php echo $ciialternativa->co_questao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $ciialternativa->co_questao->CellAttributes() ?>>
<?php if ($ciialternativa->co_questao->getSessionValue() <> "") { ?>
<span<?php echo $ciialternativa->co_questao->ViewAttributes() ?>>
<?php echo $ciialternativa->co_questao->ViewValue ?></span>
<input type="hidden" id="x_co_questao" name="x_co_questao" value="<?php echo ew_HtmlEncode($ciialternativa->co_questao->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_co_questao" id="x_co_questao" name="x_co_questao"<?php echo $ciialternativa->co_questao->EditAttributes() ?>>
<?php
if (is_array($ciialternativa->co_questao->EditValue)) {
	$arwrk = $ciialternativa->co_questao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ciialternativa->co_questao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ciialternativa->co_questao) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fciialternativaadd.Lists["x_co_questao"].Options = <?php echo (is_array($ciialternativa->co_questao->EditValue)) ? ew_ArrayToJson($ciialternativa->co_questao->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<?php echo $ciialternativa->co_questao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ciialternativa->no_alternativa->Visible) { // no_alternativa ?>
	<tr id="r_no_alternativa">
		<td><span id="elh_ciialternativa_no_alternativa"><?php echo $ciialternativa->no_alternativa->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $ciialternativa->no_alternativa->CellAttributes() ?>>
<span id="el_ciialternativa_no_alternativa" class="control-group">
<input type="text" data-field="x_no_alternativa" name="x_no_alternativa" id="x_no_alternativa" size="30" maxlength="100" placeholder="<?php echo $ciialternativa->no_alternativa->PlaceHolder ?>" value="<?php echo $ciialternativa->no_alternativa->EditValue ?>"<?php echo $ciialternativa->no_alternativa->EditAttributes() ?>>
</span>
<?php echo $ciialternativa->no_alternativa->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ciialternativa->ds_alternativa->Visible) { // ds_alternativa ?>
	<tr id="r_ds_alternativa">
		<td><span id="elh_ciialternativa_ds_alternativa"><?php echo $ciialternativa->ds_alternativa->FldCaption() ?></span></td>
		<td<?php echo $ciialternativa->ds_alternativa->CellAttributes() ?>>
<span id="el_ciialternativa_ds_alternativa" class="control-group">
<textarea data-field="x_ds_alternativa" name="x_ds_alternativa" id="x_ds_alternativa" cols="35" rows="4" placeholder="<?php echo $ciialternativa->ds_alternativa->PlaceHolder ?>"<?php echo $ciialternativa->ds_alternativa->EditAttributes() ?>><?php echo $ciialternativa->ds_alternativa->EditValue ?></textarea>
</span>
<?php echo $ciialternativa->ds_alternativa->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ciialternativa->vr_alternativa->Visible) { // vr_alternativa ?>
	<tr id="r_vr_alternativa">
		<td><span id="elh_ciialternativa_vr_alternativa"><?php echo $ciialternativa->vr_alternativa->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $ciialternativa->vr_alternativa->CellAttributes() ?>>
<span id="el_ciialternativa_vr_alternativa" class="control-group">
<input type="text" data-field="x_vr_alternativa" name="x_vr_alternativa" id="x_vr_alternativa" size="30" placeholder="<?php echo $ciialternativa->vr_alternativa->PlaceHolder ?>" value="<?php echo $ciialternativa->vr_alternativa->EditValue ?>"<?php echo $ciialternativa->vr_alternativa->EditAttributes() ?>>
</span>
<?php echo $ciialternativa->vr_alternativa->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ciialternativa->nu_peso->Visible) { // nu_peso ?>
	<tr id="r_nu_peso">
		<td><span id="elh_ciialternativa_nu_peso"><?php echo $ciialternativa->nu_peso->FldCaption() ?></span></td>
		<td<?php echo $ciialternativa->nu_peso->CellAttributes() ?>>
<span id="el_ciialternativa_nu_peso" class="control-group">
<input type="text" data-field="x_nu_peso" name="x_nu_peso" id="x_nu_peso" size="30" placeholder="<?php echo $ciialternativa->nu_peso->PlaceHolder ?>" value="<?php echo $ciialternativa->nu_peso->EditValue ?>"<?php echo $ciialternativa->nu_peso->EditAttributes() ?>>
</span>
<?php echo $ciialternativa->nu_peso->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ciialternativa->ic_ativo->Visible) { // ic_ativo ?>
	<tr id="r_ic_ativo">
		<td><span id="elh_ciialternativa_ic_ativo"><?php echo $ciialternativa->ic_ativo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $ciialternativa->ic_ativo->CellAttributes() ?>>
<span id="el_ciialternativa_ic_ativo" class="control-group">
<div id="tp_x_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_ativo" id="x_ic_ativo" value="{value}"<?php echo $ciialternativa->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $ciialternativa->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ciialternativa->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x_ic_ativo" id="x_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $ciialternativa->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $ciialternativa->ic_ativo->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fciialternativaadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$ciialternativa_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$ciialternativa_add->Page_Terminate();
?>
