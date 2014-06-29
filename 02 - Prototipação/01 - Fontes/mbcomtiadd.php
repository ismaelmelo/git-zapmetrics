<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "mbcomtiinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$mbcomti_add = NULL; // Initialize page object first

class cmbcomti_add extends cmbcomti {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{FE479719-4CC0-498B-BE07-C9817DD0435B}";

	// Table name
	var $TableName = 'mbcomti';

	// Page object name
	var $PageObjName = 'mbcomti_add';

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

		// Table object (mbcomti)
		if (!isset($GLOBALS["mbcomti"])) {
			$GLOBALS["mbcomti"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["mbcomti"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'mbcomti', TRUE);

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
			$this->Page_Terminate("mbcomtilist.php");
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
			if (@$_GET["nu_recursoHum"] != "") {
				$this->nu_recursoHum->setQueryStringValue($_GET["nu_recursoHum"]);
				$this->setKey("nu_recursoHum", $this->nu_recursoHum->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_recursoHum", ""); // Clear key
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
					$this->Page_Terminate("mbcomtilist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "mbcomtiview.php")
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
		$this->nu_pessoa->CurrentValue = NULL;
		$this->nu_pessoa->OldValue = $this->nu_pessoa->CurrentValue;
		$this->nu_organizacao->CurrentValue = NULL;
		$this->nu_organizacao->OldValue = $this->nu_organizacao->CurrentValue;
		$this->nu_papelMembro->CurrentValue = NULL;
		$this->nu_papelMembro->OldValue = $this->nu_papelMembro->CurrentValue;
		$this->dt_inicio->CurrentValue = NULL;
		$this->dt_inicio->OldValue = $this->dt_inicio->CurrentValue;
		$this->dt_fim->CurrentValue = NULL;
		$this->dt_fim->OldValue = $this->dt_fim->CurrentValue;
		$this->ds_observacoes->CurrentValue = NULL;
		$this->ds_observacoes->OldValue = $this->ds_observacoes->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_pessoa->FldIsDetailKey) {
			$this->nu_pessoa->setFormValue($objForm->GetValue("x_nu_pessoa"));
		}
		if (!$this->nu_organizacao->FldIsDetailKey) {
			$this->nu_organizacao->setFormValue($objForm->GetValue("x_nu_organizacao"));
		}
		if (!$this->nu_papelMembro->FldIsDetailKey) {
			$this->nu_papelMembro->setFormValue($objForm->GetValue("x_nu_papelMembro"));
		}
		if (!$this->dt_inicio->FldIsDetailKey) {
			$this->dt_inicio->setFormValue($objForm->GetValue("x_dt_inicio"));
			$this->dt_inicio->CurrentValue = ew_UnFormatDateTime($this->dt_inicio->CurrentValue, 7);
		}
		if (!$this->dt_fim->FldIsDetailKey) {
			$this->dt_fim->setFormValue($objForm->GetValue("x_dt_fim"));
			$this->dt_fim->CurrentValue = ew_UnFormatDateTime($this->dt_fim->CurrentValue, 7);
		}
		if (!$this->ds_observacoes->FldIsDetailKey) {
			$this->ds_observacoes->setFormValue($objForm->GetValue("x_ds_observacoes"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->nu_pessoa->CurrentValue = $this->nu_pessoa->FormValue;
		$this->nu_organizacao->CurrentValue = $this->nu_organizacao->FormValue;
		$this->nu_papelMembro->CurrentValue = $this->nu_papelMembro->FormValue;
		$this->dt_inicio->CurrentValue = $this->dt_inicio->FormValue;
		$this->dt_inicio->CurrentValue = ew_UnFormatDateTime($this->dt_inicio->CurrentValue, 7);
		$this->dt_fim->CurrentValue = $this->dt_fim->FormValue;
		$this->dt_fim->CurrentValue = ew_UnFormatDateTime($this->dt_fim->CurrentValue, 7);
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
		$this->nu_recursoHum->setDbValue($rs->fields('nu_recursoHum'));
		$this->nu_pessoa->setDbValue($rs->fields('nu_pessoa'));
		if (array_key_exists('EV__nu_pessoa', $rs->fields)) {
			$this->nu_pessoa->VirtualValue = $rs->fields('EV__nu_pessoa'); // Set up virtual field value
		} else {
			$this->nu_pessoa->VirtualValue = ""; // Clear value
		}
		$this->nu_organizacao->setDbValue($rs->fields('nu_organizacao'));
		$this->nu_papelMembro->setDbValue($rs->fields('nu_papelMembro'));
		$this->dt_inicio->setDbValue($rs->fields('dt_inicio'));
		$this->dt_fim->setDbValue($rs->fields('dt_fim'));
		$this->ds_observacoes->setDbValue($rs->fields('ds_observacoes'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_recursoHum->DbValue = $row['nu_recursoHum'];
		$this->nu_pessoa->DbValue = $row['nu_pessoa'];
		$this->nu_organizacao->DbValue = $row['nu_organizacao'];
		$this->nu_papelMembro->DbValue = $row['nu_papelMembro'];
		$this->dt_inicio->DbValue = $row['dt_inicio'];
		$this->dt_fim->DbValue = $row['dt_fim'];
		$this->ds_observacoes->DbValue = $row['ds_observacoes'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_recursoHum")) <> "")
			$this->nu_recursoHum->CurrentValue = $this->getKey("nu_recursoHum"); // nu_recursoHum
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
		// nu_recursoHum
		// nu_pessoa
		// nu_organizacao
		// nu_papelMembro
		// dt_inicio
		// dt_fim
		// ds_observacoes

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_recursoHum
			$this->nu_recursoHum->ViewValue = $this->nu_recursoHum->CurrentValue;
			$this->nu_recursoHum->ViewCustomAttributes = "";

			// nu_pessoa
			if ($this->nu_pessoa->VirtualValue <> "") {
				$this->nu_pessoa->ViewValue = $this->nu_pessoa->VirtualValue;
			} else {
			if (strval($this->nu_pessoa->CurrentValue) <> "") {
				$sFilterWrk = "[nu_pessoa]" . ew_SearchString("=", $this->nu_pessoa->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_pessoa], [no_pessoa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[pessoa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_pessoa, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_pessoa->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_pessoa->ViewValue = $this->nu_pessoa->CurrentValue;
				}
			} else {
				$this->nu_pessoa->ViewValue = NULL;
			}
			}
			$this->nu_pessoa->ViewCustomAttributes = "";

			// nu_organizacao
			if (strval($this->nu_organizacao->CurrentValue) <> "") {
				$sFilterWrk = "[nu_organizacao]" . ew_SearchString("=", $this->nu_organizacao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_organizacao], [no_organizacao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[organizacao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_organizacao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_organizacao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_organizacao->ViewValue = $this->nu_organizacao->CurrentValue;
				}
			} else {
				$this->nu_organizacao->ViewValue = NULL;
			}
			$this->nu_organizacao->ViewCustomAttributes = "";

			// nu_papelMembro
			if (strval($this->nu_papelMembro->CurrentValue) <> "") {
				$sFilterWrk = "[co_papel]" . ew_SearchString("=", $this->nu_papelMembro->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [co_papel], [no_papel] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[papel]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_papelMembro, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_papel] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_papelMembro->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_papelMembro->ViewValue = $this->nu_papelMembro->CurrentValue;
				}
			} else {
				$this->nu_papelMembro->ViewValue = NULL;
			}
			$this->nu_papelMembro->ViewCustomAttributes = "";

			// dt_inicio
			$this->dt_inicio->ViewValue = $this->dt_inicio->CurrentValue;
			$this->dt_inicio->ViewValue = ew_FormatDateTime($this->dt_inicio->ViewValue, 7);
			$this->dt_inicio->ViewCustomAttributes = "";

			// dt_fim
			$this->dt_fim->ViewValue = $this->dt_fim->CurrentValue;
			$this->dt_fim->ViewValue = ew_FormatDateTime($this->dt_fim->ViewValue, 7);
			$this->dt_fim->ViewCustomAttributes = "";

			// ds_observacoes
			$this->ds_observacoes->ViewValue = $this->ds_observacoes->CurrentValue;
			$this->ds_observacoes->ViewCustomAttributes = "";

			// nu_pessoa
			$this->nu_pessoa->LinkCustomAttributes = "";
			$this->nu_pessoa->HrefValue = "";
			$this->nu_pessoa->TooltipValue = "";

			// nu_organizacao
			$this->nu_organizacao->LinkCustomAttributes = "";
			$this->nu_organizacao->HrefValue = "";
			$this->nu_organizacao->TooltipValue = "";

			// nu_papelMembro
			$this->nu_papelMembro->LinkCustomAttributes = "";
			$this->nu_papelMembro->HrefValue = "";
			$this->nu_papelMembro->TooltipValue = "";

			// dt_inicio
			$this->dt_inicio->LinkCustomAttributes = "";
			$this->dt_inicio->HrefValue = "";
			$this->dt_inicio->TooltipValue = "";

			// dt_fim
			$this->dt_fim->LinkCustomAttributes = "";
			$this->dt_fim->HrefValue = "";
			$this->dt_fim->TooltipValue = "";

			// ds_observacoes
			$this->ds_observacoes->LinkCustomAttributes = "";
			$this->ds_observacoes->HrefValue = "";
			$this->ds_observacoes->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nu_pessoa
			$this->nu_pessoa->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_pessoa], [no_pessoa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[pessoa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_pessoa, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_pessoa->EditValue = $arwrk;

			// nu_organizacao
			$this->nu_organizacao->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_organizacao], [no_organizacao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[organizacao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_organizacao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_organizacao->EditValue = $arwrk;

			// nu_papelMembro
			$this->nu_papelMembro->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [co_papel], [no_papel] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[papel]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_papelMembro, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_papel] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_papelMembro->EditValue = $arwrk;

			// dt_inicio
			$this->dt_inicio->EditCustomAttributes = "";
			$this->dt_inicio->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->dt_inicio->CurrentValue, 7));
			$this->dt_inicio->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dt_inicio->FldCaption()));

			// dt_fim
			$this->dt_fim->EditCustomAttributes = "";
			$this->dt_fim->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->dt_fim->CurrentValue, 7));
			$this->dt_fim->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dt_fim->FldCaption()));

			// ds_observacoes
			$this->ds_observacoes->EditCustomAttributes = "";
			$this->ds_observacoes->EditValue = $this->ds_observacoes->CurrentValue;
			$this->ds_observacoes->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_observacoes->FldCaption()));

			// Edit refer script
			// nu_pessoa

			$this->nu_pessoa->HrefValue = "";

			// nu_organizacao
			$this->nu_organizacao->HrefValue = "";

			// nu_papelMembro
			$this->nu_papelMembro->HrefValue = "";

			// dt_inicio
			$this->dt_inicio->HrefValue = "";

			// dt_fim
			$this->dt_fim->HrefValue = "";

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
		if (!$this->nu_pessoa->FldIsDetailKey && !is_null($this->nu_pessoa->FormValue) && $this->nu_pessoa->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_pessoa->FldCaption());
		}
		if (!$this->nu_organizacao->FldIsDetailKey && !is_null($this->nu_organizacao->FormValue) && $this->nu_organizacao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_organizacao->FldCaption());
		}
		if (!$this->nu_papelMembro->FldIsDetailKey && !is_null($this->nu_papelMembro->FormValue) && $this->nu_papelMembro->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_papelMembro->FldCaption());
		}
		if (!ew_CheckEuroDate($this->dt_inicio->FormValue)) {
			ew_AddMessage($gsFormError, $this->dt_inicio->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->dt_fim->FormValue)) {
			ew_AddMessage($gsFormError, $this->dt_fim->FldErrMsg());
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

		// nu_pessoa
		$this->nu_pessoa->SetDbValueDef($rsnew, $this->nu_pessoa->CurrentValue, NULL, FALSE);

		// nu_organizacao
		$this->nu_organizacao->SetDbValueDef($rsnew, $this->nu_organizacao->CurrentValue, NULL, FALSE);

		// nu_papelMembro
		$this->nu_papelMembro->SetDbValueDef($rsnew, $this->nu_papelMembro->CurrentValue, NULL, FALSE);

		// dt_inicio
		$this->dt_inicio->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->dt_inicio->CurrentValue, 7), NULL, FALSE);

		// dt_fim
		$this->dt_fim->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->dt_fim->CurrentValue, 7), NULL, FALSE);

		// ds_observacoes
		$this->ds_observacoes->SetDbValueDef($rsnew, $this->ds_observacoes->CurrentValue, NULL, FALSE);

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
			$this->nu_recursoHum->setDbValue($conn->Insert_ID());
			$rsnew['nu_recursoHum'] = $this->nu_recursoHum->DbValue;
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "mbcomtilist.php", $this->TableVar);
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
if (!isset($mbcomti_add)) $mbcomti_add = new cmbcomti_add();

// Page init
$mbcomti_add->Page_Init();

// Page main
$mbcomti_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$mbcomti_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var mbcomti_add = new ew_Page("mbcomti_add");
mbcomti_add.PageID = "add"; // Page ID
var EW_PAGE_ID = mbcomti_add.PageID; // For backward compatibility

// Form object
var fmbcomtiadd = new ew_Form("fmbcomtiadd");

// Validate form
fmbcomtiadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_pessoa");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($mbcomti->nu_pessoa->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_organizacao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($mbcomti->nu_organizacao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_papelMembro");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($mbcomti->nu_papelMembro->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dt_inicio");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($mbcomti->dt_inicio->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_dt_fim");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($mbcomti->dt_fim->FldErrMsg()) ?>");

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
fmbcomtiadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmbcomtiadd.ValidateRequired = true;
<?php } else { ?>
fmbcomtiadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fmbcomtiadd.Lists["x_nu_pessoa"] = {"LinkField":"x_nu_pessoa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_pessoa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fmbcomtiadd.Lists["x_nu_organizacao"] = {"LinkField":"x_nu_organizacao","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_organizacao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fmbcomtiadd.Lists["x_nu_papelMembro"] = {"LinkField":"x_co_papel","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_papel","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $mbcomti_add->ShowPageHeader(); ?>
<?php
$mbcomti_add->ShowMessage();
?>
<form name="fmbcomtiadd" id="fmbcomtiadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="mbcomti">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_mbcomtiadd" class="table table-bordered table-striped">
<?php if ($mbcomti->nu_pessoa->Visible) { // nu_pessoa ?>
	<tr id="r_nu_pessoa">
		<td><span id="elh_mbcomti_nu_pessoa"><?php echo $mbcomti->nu_pessoa->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $mbcomti->nu_pessoa->CellAttributes() ?>>
<span id="el_mbcomti_nu_pessoa" class="control-group">
<select data-field="x_nu_pessoa" id="x_nu_pessoa" name="x_nu_pessoa"<?php echo $mbcomti->nu_pessoa->EditAttributes() ?>>
<?php
if (is_array($mbcomti->nu_pessoa->EditValue)) {
	$arwrk = $mbcomti->nu_pessoa->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($mbcomti->nu_pessoa->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if (AllowAdd(CurrentProjectID() . "pessoa")) { ?>
&nbsp;<a id="aol_x_nu_pessoa" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_nu_pessoa',url:'pessoaaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $mbcomti->nu_pessoa->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
fmbcomtiadd.Lists["x_nu_pessoa"].Options = <?php echo (is_array($mbcomti->nu_pessoa->EditValue)) ? ew_ArrayToJson($mbcomti->nu_pessoa->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $mbcomti->nu_pessoa->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($mbcomti->nu_organizacao->Visible) { // nu_organizacao ?>
	<tr id="r_nu_organizacao">
		<td><span id="elh_mbcomti_nu_organizacao"><?php echo $mbcomti->nu_organizacao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $mbcomti->nu_organizacao->CellAttributes() ?>>
<span id="el_mbcomti_nu_organizacao" class="control-group">
<select data-field="x_nu_organizacao" id="x_nu_organizacao" name="x_nu_organizacao"<?php echo $mbcomti->nu_organizacao->EditAttributes() ?>>
<?php
if (is_array($mbcomti->nu_organizacao->EditValue)) {
	$arwrk = $mbcomti->nu_organizacao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($mbcomti->nu_organizacao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fmbcomtiadd.Lists["x_nu_organizacao"].Options = <?php echo (is_array($mbcomti->nu_organizacao->EditValue)) ? ew_ArrayToJson($mbcomti->nu_organizacao->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $mbcomti->nu_organizacao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($mbcomti->nu_papelMembro->Visible) { // nu_papelMembro ?>
	<tr id="r_nu_papelMembro">
		<td><span id="elh_mbcomti_nu_papelMembro"><?php echo $mbcomti->nu_papelMembro->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $mbcomti->nu_papelMembro->CellAttributes() ?>>
<span id="el_mbcomti_nu_papelMembro" class="control-group">
<select data-field="x_nu_papelMembro" id="x_nu_papelMembro" name="x_nu_papelMembro"<?php echo $mbcomti->nu_papelMembro->EditAttributes() ?>>
<?php
if (is_array($mbcomti->nu_papelMembro->EditValue)) {
	$arwrk = $mbcomti->nu_papelMembro->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($mbcomti->nu_papelMembro->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if (AllowAdd(CurrentProjectID() . "papel")) { ?>
&nbsp;<a id="aol_x_nu_papelMembro" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_nu_papelMembro',url:'papeladdopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $mbcomti->nu_papelMembro->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
fmbcomtiadd.Lists["x_nu_papelMembro"].Options = <?php echo (is_array($mbcomti->nu_papelMembro->EditValue)) ? ew_ArrayToJson($mbcomti->nu_papelMembro->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $mbcomti->nu_papelMembro->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($mbcomti->dt_inicio->Visible) { // dt_inicio ?>
	<tr id="r_dt_inicio">
		<td><span id="elh_mbcomti_dt_inicio"><?php echo $mbcomti->dt_inicio->FldCaption() ?></span></td>
		<td<?php echo $mbcomti->dt_inicio->CellAttributes() ?>>
<span id="el_mbcomti_dt_inicio" class="control-group">
<input type="text" data-field="x_dt_inicio" name="x_dt_inicio" id="x_dt_inicio" placeholder="<?php echo $mbcomti->dt_inicio->PlaceHolder ?>" value="<?php echo $mbcomti->dt_inicio->EditValue ?>"<?php echo $mbcomti->dt_inicio->EditAttributes() ?>>
<?php if (!$mbcomti->dt_inicio->ReadOnly && !$mbcomti->dt_inicio->Disabled && @$mbcomti->dt_inicio->EditAttrs["readonly"] == "" && @$mbcomti->dt_inicio->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_dt_inicio" name="cal_x_dt_inicio" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x_dt_inicio" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fmbcomtiadd", "x_dt_inicio", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $mbcomti->dt_inicio->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($mbcomti->dt_fim->Visible) { // dt_fim ?>
	<tr id="r_dt_fim">
		<td><span id="elh_mbcomti_dt_fim"><?php echo $mbcomti->dt_fim->FldCaption() ?></span></td>
		<td<?php echo $mbcomti->dt_fim->CellAttributes() ?>>
<span id="el_mbcomti_dt_fim" class="control-group">
<input type="text" data-field="x_dt_fim" name="x_dt_fim" id="x_dt_fim" placeholder="<?php echo $mbcomti->dt_fim->PlaceHolder ?>" value="<?php echo $mbcomti->dt_fim->EditValue ?>"<?php echo $mbcomti->dt_fim->EditAttributes() ?>>
<?php if (!$mbcomti->dt_fim->ReadOnly && !$mbcomti->dt_fim->Disabled && @$mbcomti->dt_fim->EditAttrs["readonly"] == "" && @$mbcomti->dt_fim->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_dt_fim" name="cal_x_dt_fim" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x_dt_fim" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fmbcomtiadd", "x_dt_fim", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $mbcomti->dt_fim->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($mbcomti->ds_observacoes->Visible) { // ds_observacoes ?>
	<tr id="r_ds_observacoes">
		<td><span id="elh_mbcomti_ds_observacoes"><?php echo $mbcomti->ds_observacoes->FldCaption() ?></span></td>
		<td<?php echo $mbcomti->ds_observacoes->CellAttributes() ?>>
<span id="el_mbcomti_ds_observacoes" class="control-group">
<textarea data-field="x_ds_observacoes" name="x_ds_observacoes" id="x_ds_observacoes" cols="35" rows="4" placeholder="<?php echo $mbcomti->ds_observacoes->PlaceHolder ?>"<?php echo $mbcomti->ds_observacoes->EditAttributes() ?>><?php echo $mbcomti->ds_observacoes->EditValue ?></textarea>
</span>
<?php echo $mbcomti->ds_observacoes->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fmbcomtiadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$mbcomti_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$mbcomti_add->Page_Terminate();
?>
