<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "gc_reuniaoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$gc_reuniao_add = NULL; // Initialize page object first

class cgc_reuniao_add extends cgc_reuniao {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'gc_reuniao';

	// Page object name
	var $PageObjName = 'gc_reuniao_add';

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

		// Table object (gc_reuniao)
		if (!isset($GLOBALS["gc_reuniao"])) {
			$GLOBALS["gc_reuniao"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["gc_reuniao"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'gc_reuniao', TRUE);

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
			$this->Page_Terminate("gc_reuniaolist.php");
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
			if (@$_GET["nu_reuniao"] != "") {
				$this->nu_reuniao->setQueryStringValue($_GET["nu_reuniao"]);
				$this->setKey("nu_reuniao", $this->nu_reuniao->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_reuniao", ""); // Clear key
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
					$this->Page_Terminate("gc_reuniaolist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "gc_reuniaoview.php")
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
		$this->nu_grupoOuComite->CurrentValue = NULL;
		$this->nu_grupoOuComite->OldValue = $this->nu_grupoOuComite->CurrentValue;
		$this->ds_pauta->CurrentValue = NULL;
		$this->ds_pauta->OldValue = $this->ds_pauta->CurrentValue;
		$this->no_local->CurrentValue = NULL;
		$this->no_local->OldValue = $this->no_local->CurrentValue;
		$this->dt_reuniao->CurrentValue = NULL;
		$this->dt_reuniao->OldValue = $this->dt_reuniao->CurrentValue;
		$this->hh_inicio->CurrentValue = NULL;
		$this->hh_inicio->OldValue = $this->hh_inicio->CurrentValue;
		$this->hh_fim->CurrentValue = NULL;
		$this->hh_fim->OldValue = $this->hh_fim->CurrentValue;
		$this->nu_usuario->CurrentValue = NULL;
		$this->nu_usuario->OldValue = $this->nu_usuario->CurrentValue;
		$this->ts_datahora->CurrentValue = NULL;
		$this->ts_datahora->OldValue = $this->ts_datahora->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_grupoOuComite->FldIsDetailKey) {
			$this->nu_grupoOuComite->setFormValue($objForm->GetValue("x_nu_grupoOuComite"));
		}
		if (!$this->ds_pauta->FldIsDetailKey) {
			$this->ds_pauta->setFormValue($objForm->GetValue("x_ds_pauta"));
		}
		if (!$this->no_local->FldIsDetailKey) {
			$this->no_local->setFormValue($objForm->GetValue("x_no_local"));
		}
		if (!$this->dt_reuniao->FldIsDetailKey) {
			$this->dt_reuniao->setFormValue($objForm->GetValue("x_dt_reuniao"));
			$this->dt_reuniao->CurrentValue = ew_UnFormatDateTime($this->dt_reuniao->CurrentValue, 7);
		}
		if (!$this->hh_inicio->FldIsDetailKey) {
			$this->hh_inicio->setFormValue($objForm->GetValue("x_hh_inicio"));
			$this->hh_inicio->CurrentValue = ew_UnFormatDateTime($this->hh_inicio->CurrentValue, 4);
		}
		if (!$this->hh_fim->FldIsDetailKey) {
			$this->hh_fim->setFormValue($objForm->GetValue("x_hh_fim"));
			$this->hh_fim->CurrentValue = ew_UnFormatDateTime($this->hh_fim->CurrentValue, 4);
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
		$this->nu_grupoOuComite->CurrentValue = $this->nu_grupoOuComite->FormValue;
		$this->ds_pauta->CurrentValue = $this->ds_pauta->FormValue;
		$this->no_local->CurrentValue = $this->no_local->FormValue;
		$this->dt_reuniao->CurrentValue = $this->dt_reuniao->FormValue;
		$this->dt_reuniao->CurrentValue = ew_UnFormatDateTime($this->dt_reuniao->CurrentValue, 7);
		$this->hh_inicio->CurrentValue = $this->hh_inicio->FormValue;
		$this->hh_inicio->CurrentValue = ew_UnFormatDateTime($this->hh_inicio->CurrentValue, 4);
		$this->hh_fim->CurrentValue = $this->hh_fim->FormValue;
		$this->hh_fim->CurrentValue = ew_UnFormatDateTime($this->hh_fim->CurrentValue, 4);
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
		$this->nu_reuniao->setDbValue($rs->fields('nu_reuniao'));
		$this->nu_grupoOuComite->setDbValue($rs->fields('nu_grupoOuComite'));
		$this->ds_pauta->setDbValue($rs->fields('ds_pauta'));
		$this->no_local->setDbValue($rs->fields('no_local'));
		$this->dt_reuniao->setDbValue($rs->fields('dt_reuniao'));
		$this->hh_inicio->setDbValue($rs->fields('hh_inicio'));
		$this->hh_fim->setDbValue($rs->fields('hh_fim'));
		$this->ic_situacao->setDbValue($rs->fields('ic_situacao'));
		$this->nu_usuario->setDbValue($rs->fields('nu_usuario'));
		$this->ts_datahora->setDbValue($rs->fields('ts_datahora'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_reuniao->DbValue = $row['nu_reuniao'];
		$this->nu_grupoOuComite->DbValue = $row['nu_grupoOuComite'];
		$this->ds_pauta->DbValue = $row['ds_pauta'];
		$this->no_local->DbValue = $row['no_local'];
		$this->dt_reuniao->DbValue = $row['dt_reuniao'];
		$this->hh_inicio->DbValue = $row['hh_inicio'];
		$this->hh_fim->DbValue = $row['hh_fim'];
		$this->ic_situacao->DbValue = $row['ic_situacao'];
		$this->nu_usuario->DbValue = $row['nu_usuario'];
		$this->ts_datahora->DbValue = $row['ts_datahora'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_reuniao")) <> "")
			$this->nu_reuniao->CurrentValue = $this->getKey("nu_reuniao"); // nu_reuniao
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
		// nu_reuniao
		// nu_grupoOuComite
		// ds_pauta
		// no_local
		// dt_reuniao
		// hh_inicio
		// hh_fim
		// ic_situacao
		// nu_usuario
		// ts_datahora

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_reuniao
			$this->nu_reuniao->ViewValue = $this->nu_reuniao->CurrentValue;
			$this->nu_reuniao->ViewCustomAttributes = "";

			// nu_grupoOuComite
			if (strval($this->nu_grupoOuComite->CurrentValue) <> "") {
				$sFilterWrk = "[nu_gpComite]" . ew_SearchString("=", $this->nu_grupoOuComite->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_gpComite], [no_gpComite] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[gpcomite]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_grupoOuComite, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_grupoOuComite->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_grupoOuComite->ViewValue = $this->nu_grupoOuComite->CurrentValue;
				}
			} else {
				$this->nu_grupoOuComite->ViewValue = NULL;
			}
			$this->nu_grupoOuComite->ViewCustomAttributes = "";

			// ds_pauta
			$this->ds_pauta->ViewValue = $this->ds_pauta->CurrentValue;
			$this->ds_pauta->ViewCustomAttributes = "";

			// no_local
			$this->no_local->ViewValue = $this->no_local->CurrentValue;
			$this->no_local->ViewCustomAttributes = "";

			// dt_reuniao
			$this->dt_reuniao->ViewValue = $this->dt_reuniao->CurrentValue;
			$this->dt_reuniao->ViewValue = ew_FormatDateTime($this->dt_reuniao->ViewValue, 7);
			$this->dt_reuniao->ViewCustomAttributes = "";

			// hh_inicio
			$this->hh_inicio->ViewValue = $this->hh_inicio->CurrentValue;
			$this->hh_inicio->ViewValue = ew_FormatDateTime($this->hh_inicio->ViewValue, 4);
			$this->hh_inicio->ViewCustomAttributes = "";

			// hh_fim
			$this->hh_fim->ViewValue = $this->hh_fim->CurrentValue;
			$this->hh_fim->ViewValue = ew_FormatDateTime($this->hh_fim->ViewValue, 4);
			$this->hh_fim->ViewCustomAttributes = "";

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

			// nu_grupoOuComite
			$this->nu_grupoOuComite->LinkCustomAttributes = "";
			$this->nu_grupoOuComite->HrefValue = "";
			$this->nu_grupoOuComite->TooltipValue = "";

			// ds_pauta
			$this->ds_pauta->LinkCustomAttributes = "";
			$this->ds_pauta->HrefValue = "";
			$this->ds_pauta->TooltipValue = "";

			// no_local
			$this->no_local->LinkCustomAttributes = "";
			$this->no_local->HrefValue = "";
			$this->no_local->TooltipValue = "";

			// dt_reuniao
			$this->dt_reuniao->LinkCustomAttributes = "";
			$this->dt_reuniao->HrefValue = "";
			$this->dt_reuniao->TooltipValue = "";

			// hh_inicio
			$this->hh_inicio->LinkCustomAttributes = "";
			$this->hh_inicio->HrefValue = "";
			$this->hh_inicio->TooltipValue = "";

			// hh_fim
			$this->hh_fim->LinkCustomAttributes = "";
			$this->hh_fim->HrefValue = "";
			$this->hh_fim->TooltipValue = "";

			// nu_usuario
			$this->nu_usuario->LinkCustomAttributes = "";
			$this->nu_usuario->HrefValue = "";
			$this->nu_usuario->TooltipValue = "";

			// ts_datahora
			$this->ts_datahora->LinkCustomAttributes = "";
			$this->ts_datahora->HrefValue = "";
			$this->ts_datahora->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nu_grupoOuComite
			$this->nu_grupoOuComite->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_gpComite], [no_gpComite] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[gpcomite]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_grupoOuComite, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_grupoOuComite->EditValue = $arwrk;

			// ds_pauta
			$this->ds_pauta->EditCustomAttributes = "";
			$this->ds_pauta->EditValue = $this->ds_pauta->CurrentValue;
			$this->ds_pauta->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_pauta->FldCaption()));

			// no_local
			$this->no_local->EditCustomAttributes = "";
			$this->no_local->EditValue = ew_HtmlEncode($this->no_local->CurrentValue);
			$this->no_local->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_local->FldCaption()));

			// dt_reuniao
			$this->dt_reuniao->EditCustomAttributes = "";
			$this->dt_reuniao->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->dt_reuniao->CurrentValue, 7));
			$this->dt_reuniao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dt_reuniao->FldCaption()));

			// hh_inicio
			$this->hh_inicio->EditCustomAttributes = "";
			$this->hh_inicio->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->hh_inicio->CurrentValue, 4));
			$this->hh_inicio->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->hh_inicio->FldCaption()));

			// hh_fim
			$this->hh_fim->EditCustomAttributes = "";
			$this->hh_fim->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->hh_fim->CurrentValue, 4));
			$this->hh_fim->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->hh_fim->FldCaption()));

			// nu_usuario
			// ts_datahora
			// Edit refer script
			// nu_grupoOuComite

			$this->nu_grupoOuComite->HrefValue = "";

			// ds_pauta
			$this->ds_pauta->HrefValue = "";

			// no_local
			$this->no_local->HrefValue = "";

			// dt_reuniao
			$this->dt_reuniao->HrefValue = "";

			// hh_inicio
			$this->hh_inicio->HrefValue = "";

			// hh_fim
			$this->hh_fim->HrefValue = "";

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
		if (!$this->nu_grupoOuComite->FldIsDetailKey && !is_null($this->nu_grupoOuComite->FormValue) && $this->nu_grupoOuComite->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_grupoOuComite->FldCaption());
		}
		if (!$this->ds_pauta->FldIsDetailKey && !is_null($this->ds_pauta->FormValue) && $this->ds_pauta->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ds_pauta->FldCaption());
		}
		if (!$this->no_local->FldIsDetailKey && !is_null($this->no_local->FormValue) && $this->no_local->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_local->FldCaption());
		}
		if (!$this->dt_reuniao->FldIsDetailKey && !is_null($this->dt_reuniao->FormValue) && $this->dt_reuniao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->dt_reuniao->FldCaption());
		}
		if (!ew_CheckEuroDate($this->dt_reuniao->FormValue)) {
			ew_AddMessage($gsFormError, $this->dt_reuniao->FldErrMsg());
		}
		if (!$this->hh_inicio->FldIsDetailKey && !is_null($this->hh_inicio->FormValue) && $this->hh_inicio->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->hh_inicio->FldCaption());
		}
		if (!$this->hh_fim->FldIsDetailKey && !is_null($this->hh_fim->FormValue) && $this->hh_fim->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->hh_fim->FldCaption());
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

		// nu_grupoOuComite
		$this->nu_grupoOuComite->SetDbValueDef($rsnew, $this->nu_grupoOuComite->CurrentValue, NULL, FALSE);

		// ds_pauta
		$this->ds_pauta->SetDbValueDef($rsnew, $this->ds_pauta->CurrentValue, "", FALSE);

		// no_local
		$this->no_local->SetDbValueDef($rsnew, $this->no_local->CurrentValue, "", FALSE);

		// dt_reuniao
		$this->dt_reuniao->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->dt_reuniao->CurrentValue, 7), NULL, FALSE);

		// hh_inicio
		$this->hh_inicio->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->hh_inicio->CurrentValue, 4), NULL, FALSE);

		// hh_fim
		$this->hh_fim->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->hh_fim->CurrentValue, 4), NULL, FALSE);

		// nu_usuario
		$this->nu_usuario->SetDbValueDef($rsnew, CurrentUserID(), NULL);
		$rsnew['nu_usuario'] = &$this->nu_usuario->DbValue;

		// ts_datahora
		$this->ts_datahora->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
		$rsnew['ts_datahora'] = &$this->ts_datahora->DbValue;

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
			$this->nu_reuniao->setDbValue($conn->Insert_ID());
			$rsnew['nu_reuniao'] = $this->nu_reuniao->DbValue;
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "gc_reuniaolist.php", $this->TableVar);
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
if (!isset($gc_reuniao_add)) $gc_reuniao_add = new cgc_reuniao_add();

// Page init
$gc_reuniao_add->Page_Init();

// Page main
$gc_reuniao_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$gc_reuniao_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var gc_reuniao_add = new ew_Page("gc_reuniao_add");
gc_reuniao_add.PageID = "add"; // Page ID
var EW_PAGE_ID = gc_reuniao_add.PageID; // For backward compatibility

// Form object
var fgc_reuniaoadd = new ew_Form("fgc_reuniaoadd");

// Validate form
fgc_reuniaoadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_grupoOuComite");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($gc_reuniao->nu_grupoOuComite->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ds_pauta");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($gc_reuniao->ds_pauta->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no_local");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($gc_reuniao->no_local->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dt_reuniao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($gc_reuniao->dt_reuniao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dt_reuniao");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($gc_reuniao->dt_reuniao->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_hh_inicio");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($gc_reuniao->hh_inicio->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_hh_fim");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($gc_reuniao->hh_fim->FldCaption()) ?>");

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
fgc_reuniaoadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgc_reuniaoadd.ValidateRequired = true;
<?php } else { ?>
fgc_reuniaoadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fgc_reuniaoadd.Lists["x_nu_grupoOuComite"] = {"LinkField":"x_nu_gpComite","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_gpComite","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $gc_reuniao_add->ShowPageHeader(); ?>
<?php
$gc_reuniao_add->ShowMessage();
?>
<form name="fgc_reuniaoadd" id="fgc_reuniaoadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="gc_reuniao">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_gc_reuniaoadd" class="table table-bordered table-striped">
<?php if ($gc_reuniao->nu_grupoOuComite->Visible) { // nu_grupoOuComite ?>
	<tr id="r_nu_grupoOuComite">
		<td><span id="elh_gc_reuniao_nu_grupoOuComite"><?php echo $gc_reuniao->nu_grupoOuComite->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $gc_reuniao->nu_grupoOuComite->CellAttributes() ?>>
<span id="el_gc_reuniao_nu_grupoOuComite" class="control-group">
<select data-field="x_nu_grupoOuComite" id="x_nu_grupoOuComite" name="x_nu_grupoOuComite"<?php echo $gc_reuniao->nu_grupoOuComite->EditAttributes() ?>>
<?php
if (is_array($gc_reuniao->nu_grupoOuComite->EditValue)) {
	$arwrk = $gc_reuniao->nu_grupoOuComite->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($gc_reuniao->nu_grupoOuComite->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fgc_reuniaoadd.Lists["x_nu_grupoOuComite"].Options = <?php echo (is_array($gc_reuniao->nu_grupoOuComite->EditValue)) ? ew_ArrayToJson($gc_reuniao->nu_grupoOuComite->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $gc_reuniao->nu_grupoOuComite->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gc_reuniao->ds_pauta->Visible) { // ds_pauta ?>
	<tr id="r_ds_pauta">
		<td><span id="elh_gc_reuniao_ds_pauta"><?php echo $gc_reuniao->ds_pauta->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $gc_reuniao->ds_pauta->CellAttributes() ?>>
<span id="el_gc_reuniao_ds_pauta" class="control-group">
<textarea data-field="x_ds_pauta" name="x_ds_pauta" id="x_ds_pauta" cols="35" rows="4" placeholder="<?php echo $gc_reuniao->ds_pauta->PlaceHolder ?>"<?php echo $gc_reuniao->ds_pauta->EditAttributes() ?>><?php echo $gc_reuniao->ds_pauta->EditValue ?></textarea>
</span>
<?php echo $gc_reuniao->ds_pauta->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gc_reuniao->no_local->Visible) { // no_local ?>
	<tr id="r_no_local">
		<td><span id="elh_gc_reuniao_no_local"><?php echo $gc_reuniao->no_local->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $gc_reuniao->no_local->CellAttributes() ?>>
<span id="el_gc_reuniao_no_local" class="control-group">
<input type="text" data-field="x_no_local" name="x_no_local" id="x_no_local" size="30" maxlength="75" placeholder="<?php echo $gc_reuniao->no_local->PlaceHolder ?>" value="<?php echo $gc_reuniao->no_local->EditValue ?>"<?php echo $gc_reuniao->no_local->EditAttributes() ?>>
</span>
<?php echo $gc_reuniao->no_local->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gc_reuniao->dt_reuniao->Visible) { // dt_reuniao ?>
	<tr id="r_dt_reuniao">
		<td><span id="elh_gc_reuniao_dt_reuniao"><?php echo $gc_reuniao->dt_reuniao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $gc_reuniao->dt_reuniao->CellAttributes() ?>>
<span id="el_gc_reuniao_dt_reuniao" class="control-group">
<input type="text" data-field="x_dt_reuniao" name="x_dt_reuniao" id="x_dt_reuniao" placeholder="<?php echo $gc_reuniao->dt_reuniao->PlaceHolder ?>" value="<?php echo $gc_reuniao->dt_reuniao->EditValue ?>"<?php echo $gc_reuniao->dt_reuniao->EditAttributes() ?>>
<?php if (!$gc_reuniao->dt_reuniao->ReadOnly && !$gc_reuniao->dt_reuniao->Disabled && @$gc_reuniao->dt_reuniao->EditAttrs["readonly"] == "" && @$gc_reuniao->dt_reuniao->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_dt_reuniao" name="cal_x_dt_reuniao" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x_dt_reuniao" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fgc_reuniaoadd", "x_dt_reuniao", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $gc_reuniao->dt_reuniao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gc_reuniao->hh_inicio->Visible) { // hh_inicio ?>
	<tr id="r_hh_inicio">
		<td><span id="elh_gc_reuniao_hh_inicio"><?php echo $gc_reuniao->hh_inicio->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $gc_reuniao->hh_inicio->CellAttributes() ?>>
<span id="el_gc_reuniao_hh_inicio" class="control-group">
<input type="text" data-field="x_hh_inicio" name="x_hh_inicio" id="x_hh_inicio" placeholder="<?php echo $gc_reuniao->hh_inicio->PlaceHolder ?>" value="<?php echo $gc_reuniao->hh_inicio->EditValue ?>"<?php echo $gc_reuniao->hh_inicio->EditAttributes() ?>>
</span>
<?php echo $gc_reuniao->hh_inicio->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gc_reuniao->hh_fim->Visible) { // hh_fim ?>
	<tr id="r_hh_fim">
		<td><span id="elh_gc_reuniao_hh_fim"><?php echo $gc_reuniao->hh_fim->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $gc_reuniao->hh_fim->CellAttributes() ?>>
<span id="el_gc_reuniao_hh_fim" class="control-group">
<input type="text" data-field="x_hh_fim" name="x_hh_fim" id="x_hh_fim" placeholder="<?php echo $gc_reuniao->hh_fim->PlaceHolder ?>" value="<?php echo $gc_reuniao->hh_fim->EditValue ?>"<?php echo $gc_reuniao->hh_fim->EditAttributes() ?>>
</span>
<?php echo $gc_reuniao->hh_fim->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fgc_reuniaoadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$gc_reuniao_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$gc_reuniao_add->Page_Terminate();
?>
