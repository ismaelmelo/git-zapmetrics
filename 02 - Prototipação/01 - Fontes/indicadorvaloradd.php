<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "indicadorvalorinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "indicadorversaogridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$indicadorvalor_add = NULL; // Initialize page object first

class cindicadorvalor_add extends cindicadorvalor {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{FE479719-4CC0-498B-BE07-C9817DD0435B}";

	// Table name
	var $TableName = 'indicadorvalor';

	// Page object name
	var $PageObjName = 'indicadorvalor_add';

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

		// Table object (indicadorvalor)
		if (!isset($GLOBALS["indicadorvalor"])) {
			$GLOBALS["indicadorvalor"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["indicadorvalor"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'indicadorvalor', TRUE);

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
			$this->Page_Terminate("indicadorvalorlist.php");
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
			if (@$_GET["nu_indicador"] != "") {
				$this->nu_indicador->setQueryStringValue($_GET["nu_indicador"]);
				$this->setKey("nu_indicador", $this->nu_indicador->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_indicador", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if (@$_GET["nu_versao"] != "") {
				$this->nu_versao->setQueryStringValue($_GET["nu_versao"]);
				$this->setKey("nu_versao", $this->nu_versao->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_versao", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if (@$_GET["dh_geracao"] != "") {
				$this->dh_geracao->setQueryStringValue($_GET["dh_geracao"]);
				$this->setKey("dh_geracao", $this->dh_geracao->CurrentValue); // Set up key
			} else {
				$this->setKey("dh_geracao", ""); // Clear key
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
					$this->Page_Terminate("indicadorvalorlist.php"); // No matching record, return to list
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
					if (ew_GetPageName($sReturnUrl) == "indicadorvalorview.php")
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
		$this->nu_indicador->CurrentValue = NULL;
		$this->nu_indicador->OldValue = $this->nu_indicador->CurrentValue;
		$this->nu_versao->CurrentValue = NULL;
		$this->nu_versao->OldValue = $this->nu_versao->CurrentValue;
		$this->dh_geracao->CurrentValue = NULL;
		$this->dh_geracao->OldValue = $this->dh_geracao->CurrentValue;
		$this->vr_indicadorNumerico->CurrentValue = NULL;
		$this->vr_indicadorNumerico->OldValue = $this->vr_indicadorNumerico->CurrentValue;
		$this->vr_indicadorTexto->CurrentValue = NULL;
		$this->vr_indicadorTexto->OldValue = $this->vr_indicadorTexto->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_indicador->FldIsDetailKey) {
			$this->nu_indicador->setFormValue($objForm->GetValue("x_nu_indicador"));
		}
		if (!$this->nu_versao->FldIsDetailKey) {
			$this->nu_versao->setFormValue($objForm->GetValue("x_nu_versao"));
		}
		if (!$this->dh_geracao->FldIsDetailKey) {
			$this->dh_geracao->setFormValue($objForm->GetValue("x_dh_geracao"));
			$this->dh_geracao->CurrentValue = ew_UnFormatDateTime($this->dh_geracao->CurrentValue, 11);
		}
		if (!$this->vr_indicadorNumerico->FldIsDetailKey) {
			$this->vr_indicadorNumerico->setFormValue($objForm->GetValue("x_vr_indicadorNumerico"));
		}
		if (!$this->vr_indicadorTexto->FldIsDetailKey) {
			$this->vr_indicadorTexto->setFormValue($objForm->GetValue("x_vr_indicadorTexto"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->nu_indicador->CurrentValue = $this->nu_indicador->FormValue;
		$this->nu_versao->CurrentValue = $this->nu_versao->FormValue;
		$this->dh_geracao->CurrentValue = $this->dh_geracao->FormValue;
		$this->dh_geracao->CurrentValue = ew_UnFormatDateTime($this->dh_geracao->CurrentValue, 11);
		$this->vr_indicadorNumerico->CurrentValue = $this->vr_indicadorNumerico->FormValue;
		$this->vr_indicadorTexto->CurrentValue = $this->vr_indicadorTexto->FormValue;
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
		$this->nu_indicador->setDbValue($rs->fields('nu_indicador'));
		$this->nu_versao->setDbValue($rs->fields('nu_versao'));
		$this->dh_geracao->setDbValue($rs->fields('dh_geracao'));
		$this->vr_indicadorNumerico->setDbValue($rs->fields('vr_indicadorNumerico'));
		$this->vr_indicadorTexto->setDbValue($rs->fields('vr_indicadorTexto'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_indicador->DbValue = $row['nu_indicador'];
		$this->nu_versao->DbValue = $row['nu_versao'];
		$this->dh_geracao->DbValue = $row['dh_geracao'];
		$this->vr_indicadorNumerico->DbValue = $row['vr_indicadorNumerico'];
		$this->vr_indicadorTexto->DbValue = $row['vr_indicadorTexto'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_indicador")) <> "")
			$this->nu_indicador->CurrentValue = $this->getKey("nu_indicador"); // nu_indicador
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("nu_versao")) <> "")
			$this->nu_versao->CurrentValue = $this->getKey("nu_versao"); // nu_versao
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("dh_geracao")) <> "")
			$this->dh_geracao->CurrentValue = $this->getKey("dh_geracao"); // dh_geracao
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

		if ($this->vr_indicadorNumerico->FormValue == $this->vr_indicadorNumerico->CurrentValue && is_numeric(ew_StrToFloat($this->vr_indicadorNumerico->CurrentValue)))
			$this->vr_indicadorNumerico->CurrentValue = ew_StrToFloat($this->vr_indicadorNumerico->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_indicador
		// nu_versao
		// dh_geracao
		// vr_indicadorNumerico
		// vr_indicadorTexto

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_indicador
			if (strval($this->nu_indicador->CurrentValue) <> "") {
				$sFilterWrk = "[nu_indicador]" . ew_SearchString("=", $this->nu_indicador->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_indicador], [no_indicador] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[indicador]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_indicador, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_indicador->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_indicador->ViewValue = $this->nu_indicador->CurrentValue;
				}
			} else {
				$this->nu_indicador->ViewValue = NULL;
			}
			$this->nu_indicador->ViewCustomAttributes = "";

			// nu_versao
			if (strval($this->nu_versao->CurrentValue) <> "") {
				$sFilterWrk = "[nu_versao]" . ew_SearchString("=", $this->nu_versao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_versao], [nu_versao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[indicadorversao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_versao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_versao] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_versao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_versao->ViewValue = $this->nu_versao->CurrentValue;
				}
			} else {
				$this->nu_versao->ViewValue = NULL;
			}
			$this->nu_versao->ViewCustomAttributes = "";

			// dh_geracao
			$this->dh_geracao->ViewValue = $this->dh_geracao->CurrentValue;
			$this->dh_geracao->ViewValue = ew_FormatDateTime($this->dh_geracao->ViewValue, 11);
			$this->dh_geracao->ViewCustomAttributes = "";

			// vr_indicadorNumerico
			$this->vr_indicadorNumerico->ViewValue = $this->vr_indicadorNumerico->CurrentValue;
			$this->vr_indicadorNumerico->ViewCustomAttributes = "";

			// vr_indicadorTexto
			$this->vr_indicadorTexto->ViewValue = $this->vr_indicadorTexto->CurrentValue;
			$this->vr_indicadorTexto->ViewCustomAttributes = "";

			// nu_indicador
			$this->nu_indicador->LinkCustomAttributes = "";
			$this->nu_indicador->HrefValue = "";
			$this->nu_indicador->TooltipValue = "";

			// nu_versao
			$this->nu_versao->LinkCustomAttributes = "";
			$this->nu_versao->HrefValue = "";
			$this->nu_versao->TooltipValue = "";

			// dh_geracao
			$this->dh_geracao->LinkCustomAttributes = "";
			$this->dh_geracao->HrefValue = "";
			$this->dh_geracao->TooltipValue = "";

			// vr_indicadorNumerico
			$this->vr_indicadorNumerico->LinkCustomAttributes = "";
			$this->vr_indicadorNumerico->HrefValue = "";
			$this->vr_indicadorNumerico->TooltipValue = "";

			// vr_indicadorTexto
			$this->vr_indicadorTexto->LinkCustomAttributes = "";
			$this->vr_indicadorTexto->HrefValue = "";
			$this->vr_indicadorTexto->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nu_indicador
			$this->nu_indicador->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_indicador], [no_indicador] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[indicador]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_indicador, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_indicador->EditValue = $arwrk;

			// nu_versao
			$this->nu_versao->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_versao], [nu_versao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [nu_indicador] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[indicadorversao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_versao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_versao] DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_versao->EditValue = $arwrk;

			// dh_geracao
			$this->dh_geracao->EditCustomAttributes = "";
			$this->dh_geracao->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->dh_geracao->CurrentValue, 11));
			$this->dh_geracao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dh_geracao->FldCaption()));

			// vr_indicadorNumerico
			$this->vr_indicadorNumerico->EditCustomAttributes = "";
			$this->vr_indicadorNumerico->EditValue = ew_HtmlEncode($this->vr_indicadorNumerico->CurrentValue);
			$this->vr_indicadorNumerico->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_indicadorNumerico->FldCaption()));
			if (strval($this->vr_indicadorNumerico->EditValue) <> "" && is_numeric($this->vr_indicadorNumerico->EditValue)) $this->vr_indicadorNumerico->EditValue = ew_FormatNumber($this->vr_indicadorNumerico->EditValue, -2, -1, -2, 0);

			// vr_indicadorTexto
			$this->vr_indicadorTexto->EditCustomAttributes = "";
			$this->vr_indicadorTexto->EditValue = ew_HtmlEncode($this->vr_indicadorTexto->CurrentValue);
			$this->vr_indicadorTexto->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_indicadorTexto->FldCaption()));

			// Edit refer script
			// nu_indicador

			$this->nu_indicador->HrefValue = "";

			// nu_versao
			$this->nu_versao->HrefValue = "";

			// dh_geracao
			$this->dh_geracao->HrefValue = "";

			// vr_indicadorNumerico
			$this->vr_indicadorNumerico->HrefValue = "";

			// vr_indicadorTexto
			$this->vr_indicadorTexto->HrefValue = "";
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
		if (!$this->nu_indicador->FldIsDetailKey && !is_null($this->nu_indicador->FormValue) && $this->nu_indicador->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_indicador->FldCaption());
		}
		if (!$this->nu_versao->FldIsDetailKey && !is_null($this->nu_versao->FormValue) && $this->nu_versao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_versao->FldCaption());
		}
		if (!$this->dh_geracao->FldIsDetailKey && !is_null($this->dh_geracao->FormValue) && $this->dh_geracao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->dh_geracao->FldCaption());
		}
		if (!ew_CheckEuroDate($this->dh_geracao->FormValue)) {
			ew_AddMessage($gsFormError, $this->dh_geracao->FldErrMsg());
		}
		if (!ew_CheckNumber($this->vr_indicadorNumerico->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_indicadorNumerico->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("indicadorversao", $DetailTblVar) && $GLOBALS["indicadorversao"]->DetailAdd) {
			if (!isset($GLOBALS["indicadorversao_grid"])) $GLOBALS["indicadorversao_grid"] = new cindicadorversao_grid(); // get detail page object
			$GLOBALS["indicadorversao_grid"]->ValidateGridForm();
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

		// nu_indicador
		$this->nu_indicador->SetDbValueDef($rsnew, $this->nu_indicador->CurrentValue, 0, FALSE);

		// nu_versao
		$this->nu_versao->SetDbValueDef($rsnew, $this->nu_versao->CurrentValue, 0, FALSE);

		// dh_geracao
		$this->dh_geracao->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->dh_geracao->CurrentValue, 11), ew_CurrentDate(), FALSE);

		// vr_indicadorNumerico
		$this->vr_indicadorNumerico->SetDbValueDef($rsnew, $this->vr_indicadorNumerico->CurrentValue, NULL, FALSE);

		// vr_indicadorTexto
		$this->vr_indicadorTexto->SetDbValueDef($rsnew, $this->vr_indicadorTexto->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && $this->nu_indicador->CurrentValue == "" && $this->nu_indicador->getSessionValue() == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && $this->nu_versao->CurrentValue == "" && $this->nu_versao->getSessionValue() == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && $this->dh_geracao->CurrentValue == "" && $this->dh_geracao->getSessionValue() == "") {
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

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("indicadorversao", $DetailTblVar) && $GLOBALS["indicadorversao"]->DetailAdd) {
				$GLOBALS["indicadorversao"]->nu_indicador->setSessionValue($this->nu_indicador->CurrentValue); // Set master key
				$GLOBALS["indicadorversao"]->nu_versao->setSessionValue($this->nu_versao->CurrentValue); // Set master key
				if (!isset($GLOBALS["indicadorversao_grid"])) $GLOBALS["indicadorversao_grid"] = new cindicadorversao_grid(); // Get detail page object
				$AddRow = $GLOBALS["indicadorversao_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["indicadorversao"]->nu_versao->setSessionValue(""); // Clear master key if insert failed
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
			if (in_array("indicadorversao", $DetailTblVar)) {
				if (!isset($GLOBALS["indicadorversao_grid"]))
					$GLOBALS["indicadorversao_grid"] = new cindicadorversao_grid;
				if ($GLOBALS["indicadorversao_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["indicadorversao_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["indicadorversao_grid"]->CurrentMode = "add";
					$GLOBALS["indicadorversao_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["indicadorversao_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["indicadorversao_grid"]->setStartRecordNumber(1);
					$GLOBALS["indicadorversao_grid"]->nu_indicador->FldIsDetailKey = TRUE;
					$GLOBALS["indicadorversao_grid"]->nu_indicador->CurrentValue = $this->nu_indicador->CurrentValue;
					$GLOBALS["indicadorversao_grid"]->nu_indicador->setSessionValue($GLOBALS["indicadorversao_grid"]->nu_indicador->CurrentValue);
					$GLOBALS["indicadorversao_grid"]->nu_versao->FldIsDetailKey = TRUE;
					$GLOBALS["indicadorversao_grid"]->nu_versao->CurrentValue = $this->nu_versao->CurrentValue;
					$GLOBALS["indicadorversao_grid"]->nu_versao->setSessionValue($GLOBALS["indicadorversao_grid"]->nu_versao->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "indicadorvalorlist.php", $this->TableVar);
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
if (!isset($indicadorvalor_add)) $indicadorvalor_add = new cindicadorvalor_add();

// Page init
$indicadorvalor_add->Page_Init();

// Page main
$indicadorvalor_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$indicadorvalor_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var indicadorvalor_add = new ew_Page("indicadorvalor_add");
indicadorvalor_add.PageID = "add"; // Page ID
var EW_PAGE_ID = indicadorvalor_add.PageID; // For backward compatibility

// Form object
var findicadorvaloradd = new ew_Form("findicadorvaloradd");

// Validate form
findicadorvaloradd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_indicador");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($indicadorvalor->nu_indicador->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_versao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($indicadorvalor->nu_versao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dh_geracao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($indicadorvalor->dh_geracao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dh_geracao");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($indicadorvalor->dh_geracao->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vr_indicadorNumerico");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($indicadorvalor->vr_indicadorNumerico->FldErrMsg()) ?>");

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
findicadorvaloradd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
findicadorvaloradd.ValidateRequired = true;
<?php } else { ?>
findicadorvaloradd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
findicadorvaloradd.Lists["x_nu_indicador"] = {"LinkField":"x_nu_indicador","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_indicador","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
findicadorvaloradd.Lists["x_nu_versao"] = {"LinkField":"x_nu_versao","Ajax":null,"AutoFill":false,"DisplayFields":["x_nu_versao","","",""],"ParentFields":["x_nu_indicador"],"FilterFields":["x_nu_indicador"],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $indicadorvalor_add->ShowPageHeader(); ?>
<?php
$indicadorvalor_add->ShowMessage();
?>
<form name="findicadorvaloradd" id="findicadorvaloradd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="indicadorvalor">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_indicadorvaloradd" class="table table-bordered table-striped">
<?php if ($indicadorvalor->nu_indicador->Visible) { // nu_indicador ?>
	<tr id="r_nu_indicador">
		<td><span id="elh_indicadorvalor_nu_indicador"><?php echo $indicadorvalor->nu_indicador->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $indicadorvalor->nu_indicador->CellAttributes() ?>>
<span id="el_indicadorvalor_nu_indicador" class="control-group">
<?php $indicadorvalor->nu_indicador->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_versao']); " . @$indicadorvalor->nu_indicador->EditAttrs["onchange"]; ?>
<select data-field="x_nu_indicador" id="x_nu_indicador" name="x_nu_indicador"<?php echo $indicadorvalor->nu_indicador->EditAttributes() ?>>
<?php
if (is_array($indicadorvalor->nu_indicador->EditValue)) {
	$arwrk = $indicadorvalor->nu_indicador->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($indicadorvalor->nu_indicador->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
findicadorvaloradd.Lists["x_nu_indicador"].Options = <?php echo (is_array($indicadorvalor->nu_indicador->EditValue)) ? ew_ArrayToJson($indicadorvalor->nu_indicador->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $indicadorvalor->nu_indicador->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($indicadorvalor->nu_versao->Visible) { // nu_versao ?>
	<tr id="r_nu_versao">
		<td><span id="elh_indicadorvalor_nu_versao"><?php echo $indicadorvalor->nu_versao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $indicadorvalor->nu_versao->CellAttributes() ?>>
<span id="el_indicadorvalor_nu_versao" class="control-group">
<select data-field="x_nu_versao" id="x_nu_versao" name="x_nu_versao"<?php echo $indicadorvalor->nu_versao->EditAttributes() ?>>
<?php
if (is_array($indicadorvalor->nu_versao->EditValue)) {
	$arwrk = $indicadorvalor->nu_versao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($indicadorvalor->nu_versao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
findicadorvaloradd.Lists["x_nu_versao"].Options = <?php echo (is_array($indicadorvalor->nu_versao->EditValue)) ? ew_ArrayToJson($indicadorvalor->nu_versao->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $indicadorvalor->nu_versao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($indicadorvalor->dh_geracao->Visible) { // dh_geracao ?>
	<tr id="r_dh_geracao">
		<td><span id="elh_indicadorvalor_dh_geracao"><?php echo $indicadorvalor->dh_geracao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $indicadorvalor->dh_geracao->CellAttributes() ?>>
<span id="el_indicadorvalor_dh_geracao" class="control-group">
<input type="text" data-field="x_dh_geracao" name="x_dh_geracao" id="x_dh_geracao" placeholder="<?php echo $indicadorvalor->dh_geracao->PlaceHolder ?>" value="<?php echo $indicadorvalor->dh_geracao->EditValue ?>"<?php echo $indicadorvalor->dh_geracao->EditAttributes() ?>>
</span>
<?php echo $indicadorvalor->dh_geracao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($indicadorvalor->vr_indicadorNumerico->Visible) { // vr_indicadorNumerico ?>
	<tr id="r_vr_indicadorNumerico">
		<td><span id="elh_indicadorvalor_vr_indicadorNumerico"><?php echo $indicadorvalor->vr_indicadorNumerico->FldCaption() ?></span></td>
		<td<?php echo $indicadorvalor->vr_indicadorNumerico->CellAttributes() ?>>
<span id="el_indicadorvalor_vr_indicadorNumerico" class="control-group">
<input type="text" data-field="x_vr_indicadorNumerico" name="x_vr_indicadorNumerico" id="x_vr_indicadorNumerico" size="30" placeholder="<?php echo $indicadorvalor->vr_indicadorNumerico->PlaceHolder ?>" value="<?php echo $indicadorvalor->vr_indicadorNumerico->EditValue ?>"<?php echo $indicadorvalor->vr_indicadorNumerico->EditAttributes() ?>>
</span>
<?php echo $indicadorvalor->vr_indicadorNumerico->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($indicadorvalor->vr_indicadorTexto->Visible) { // vr_indicadorTexto ?>
	<tr id="r_vr_indicadorTexto">
		<td><span id="elh_indicadorvalor_vr_indicadorTexto"><?php echo $indicadorvalor->vr_indicadorTexto->FldCaption() ?></span></td>
		<td<?php echo $indicadorvalor->vr_indicadorTexto->CellAttributes() ?>>
<span id="el_indicadorvalor_vr_indicadorTexto" class="control-group">
<input type="text" data-field="x_vr_indicadorTexto" name="x_vr_indicadorTexto" id="x_vr_indicadorTexto" size="30" maxlength="50" placeholder="<?php echo $indicadorvalor->vr_indicadorTexto->PlaceHolder ?>" value="<?php echo $indicadorvalor->vr_indicadorTexto->EditValue ?>"<?php echo $indicadorvalor->vr_indicadorTexto->EditAttributes() ?>>
</span>
<?php echo $indicadorvalor->vr_indicadorTexto->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<?php
	if (in_array("indicadorversao", explode(",", $indicadorvalor->getCurrentDetailTable())) && $indicadorversao->DetailAdd) {
?>
<?php include_once "indicadorversaogrid.php" ?>
<?php } ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
findicadorvaloradd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$indicadorvalor_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$indicadorvalor_add->Page_Terminate();
?>
