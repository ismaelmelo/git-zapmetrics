<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "prospectoocorrenciasinfo.php" ?>
<?php include_once "prospectoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$prospectoocorrencias_add = NULL; // Initialize page object first

class cprospectoocorrencias_add extends cprospectoocorrencias {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'prospectoocorrencias';

	// Page object name
	var $PageObjName = 'prospectoocorrencias_add';

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

		// Table object (prospectoocorrencias)
		if (!isset($GLOBALS["prospectoocorrencias"])) {
			$GLOBALS["prospectoocorrencias"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["prospectoocorrencias"];
		}

		// Table object (prospecto)
		if (!isset($GLOBALS['prospecto'])) $GLOBALS['prospecto'] = new cprospecto();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'prospectoocorrencias', TRUE);

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
			$this->Page_Terminate("prospectoocorrenciaslist.php");
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
			if (@$_GET["nu_ocorrencia"] != "") {
				$this->nu_ocorrencia->setQueryStringValue($_GET["nu_ocorrencia"]);
				$this->setKey("nu_ocorrencia", $this->nu_ocorrencia->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_ocorrencia", ""); // Clear key
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
					$this->Page_Terminate("prospectoocorrenciaslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "prospectoocorrenciasview.php")
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
		$this->nu_prospecto->CurrentValue = NULL;
		$this->nu_prospecto->OldValue = $this->nu_prospecto->CurrentValue;
		$this->no_assuntoOcor->CurrentValue = NULL;
		$this->no_assuntoOcor->OldValue = $this->no_assuntoOcor->CurrentValue;
		$this->ds_ocorrencia->CurrentValue = NULL;
		$this->ds_ocorrencia->OldValue = $this->ds_ocorrencia->CurrentValue;
		$this->dh_inclusao->CurrentValue = NULL;
		$this->dh_inclusao->OldValue = $this->dh_inclusao->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_prospecto->FldIsDetailKey) {
			$this->nu_prospecto->setFormValue($objForm->GetValue("x_nu_prospecto"));
		}
		if (!$this->no_assuntoOcor->FldIsDetailKey) {
			$this->no_assuntoOcor->setFormValue($objForm->GetValue("x_no_assuntoOcor"));
		}
		if (!$this->ds_ocorrencia->FldIsDetailKey) {
			$this->ds_ocorrencia->setFormValue($objForm->GetValue("x_ds_ocorrencia"));
		}
		if (!$this->dh_inclusao->FldIsDetailKey) {
			$this->dh_inclusao->setFormValue($objForm->GetValue("x_dh_inclusao"));
			$this->dh_inclusao->CurrentValue = ew_UnFormatDateTime($this->dh_inclusao->CurrentValue, 11);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->nu_prospecto->CurrentValue = $this->nu_prospecto->FormValue;
		$this->no_assuntoOcor->CurrentValue = $this->no_assuntoOcor->FormValue;
		$this->ds_ocorrencia->CurrentValue = $this->ds_ocorrencia->FormValue;
		$this->dh_inclusao->CurrentValue = $this->dh_inclusao->FormValue;
		$this->dh_inclusao->CurrentValue = ew_UnFormatDateTime($this->dh_inclusao->CurrentValue, 11);
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
		$this->nu_prospecto->setDbValue($rs->fields('nu_prospecto'));
		$this->nu_ocorrencia->setDbValue($rs->fields('nu_ocorrencia'));
		$this->no_assuntoOcor->setDbValue($rs->fields('no_assuntoOcor'));
		$this->ds_ocorrencia->setDbValue($rs->fields('ds_ocorrencia'));
		$this->dh_inclusao->setDbValue($rs->fields('dh_inclusao'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_prospecto->DbValue = $row['nu_prospecto'];
		$this->nu_ocorrencia->DbValue = $row['nu_ocorrencia'];
		$this->no_assuntoOcor->DbValue = $row['no_assuntoOcor'];
		$this->ds_ocorrencia->DbValue = $row['ds_ocorrencia'];
		$this->dh_inclusao->DbValue = $row['dh_inclusao'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_ocorrencia")) <> "")
			$this->nu_ocorrencia->CurrentValue = $this->getKey("nu_ocorrencia"); // nu_ocorrencia
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
		// nu_prospecto
		// nu_ocorrencia
		// no_assuntoOcor
		// ds_ocorrencia
		// dh_inclusao

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_prospecto
			if (strval($this->nu_prospecto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_prospecto]" . ew_SearchString("=", $this->nu_prospecto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_prospecto], [no_prospecto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[prospecto]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_prospecto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_prospecto] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_prospecto->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_prospecto->ViewValue = $this->nu_prospecto->CurrentValue;
				}
			} else {
				$this->nu_prospecto->ViewValue = NULL;
			}
			$this->nu_prospecto->ViewCustomAttributes = "";

			// nu_ocorrencia
			$this->nu_ocorrencia->ViewValue = $this->nu_ocorrencia->CurrentValue;
			$this->nu_ocorrencia->ViewCustomAttributes = "";

			// no_assuntoOcor
			$this->no_assuntoOcor->ViewValue = $this->no_assuntoOcor->CurrentValue;
			$this->no_assuntoOcor->ViewCustomAttributes = "";

			// ds_ocorrencia
			$this->ds_ocorrencia->ViewValue = $this->ds_ocorrencia->CurrentValue;
			$this->ds_ocorrencia->ViewCustomAttributes = "";

			// dh_inclusao
			$this->dh_inclusao->ViewValue = $this->dh_inclusao->CurrentValue;
			$this->dh_inclusao->ViewValue = ew_FormatDateTime($this->dh_inclusao->ViewValue, 11);
			$this->dh_inclusao->ViewCustomAttributes = "";

			// nu_prospecto
			$this->nu_prospecto->LinkCustomAttributes = "";
			$this->nu_prospecto->HrefValue = "";
			$this->nu_prospecto->TooltipValue = "";

			// no_assuntoOcor
			$this->no_assuntoOcor->LinkCustomAttributes = "";
			$this->no_assuntoOcor->HrefValue = "";
			$this->no_assuntoOcor->TooltipValue = "";

			// ds_ocorrencia
			$this->ds_ocorrencia->LinkCustomAttributes = "";
			$this->ds_ocorrencia->HrefValue = "";
			$this->ds_ocorrencia->TooltipValue = "";

			// dh_inclusao
			$this->dh_inclusao->LinkCustomAttributes = "";
			$this->dh_inclusao->HrefValue = "";
			$this->dh_inclusao->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nu_prospecto
			$this->nu_prospecto->EditCustomAttributes = "";
			if ($this->nu_prospecto->getSessionValue() <> "") {
				$this->nu_prospecto->CurrentValue = $this->nu_prospecto->getSessionValue();
			if (strval($this->nu_prospecto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_prospecto]" . ew_SearchString("=", $this->nu_prospecto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_prospecto], [no_prospecto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[prospecto]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_prospecto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_prospecto] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_prospecto->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_prospecto->ViewValue = $this->nu_prospecto->CurrentValue;
				}
			} else {
				$this->nu_prospecto->ViewValue = NULL;
			}
			$this->nu_prospecto->ViewCustomAttributes = "";
			} else {
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_prospecto], [no_prospecto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[prospecto]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_prospecto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_prospecto] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_prospecto->EditValue = $arwrk;
			}

			// no_assuntoOcor
			$this->no_assuntoOcor->EditCustomAttributes = "";
			$this->no_assuntoOcor->EditValue = ew_HtmlEncode($this->no_assuntoOcor->CurrentValue);
			$this->no_assuntoOcor->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_assuntoOcor->FldCaption()));

			// ds_ocorrencia
			$this->ds_ocorrencia->EditCustomAttributes = "";
			$this->ds_ocorrencia->EditValue = $this->ds_ocorrencia->CurrentValue;
			$this->ds_ocorrencia->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_ocorrencia->FldCaption()));

			// dh_inclusao
			$this->dh_inclusao->EditCustomAttributes = "";
			$this->dh_inclusao->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->dh_inclusao->CurrentValue, 11));
			$this->dh_inclusao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dh_inclusao->FldCaption()));

			// Edit refer script
			// nu_prospecto

			$this->nu_prospecto->HrefValue = "";

			// no_assuntoOcor
			$this->no_assuntoOcor->HrefValue = "";

			// ds_ocorrencia
			$this->ds_ocorrencia->HrefValue = "";

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
		if (!$this->nu_prospecto->FldIsDetailKey && !is_null($this->nu_prospecto->FormValue) && $this->nu_prospecto->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_prospecto->FldCaption());
		}
		if (!$this->no_assuntoOcor->FldIsDetailKey && !is_null($this->no_assuntoOcor->FormValue) && $this->no_assuntoOcor->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_assuntoOcor->FldCaption());
		}
		if (!$this->ds_ocorrencia->FldIsDetailKey && !is_null($this->ds_ocorrencia->FormValue) && $this->ds_ocorrencia->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ds_ocorrencia->FldCaption());
		}
		if (!$this->dh_inclusao->FldIsDetailKey && !is_null($this->dh_inclusao->FormValue) && $this->dh_inclusao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->dh_inclusao->FldCaption());
		}
		if (!ew_CheckEuroDate($this->dh_inclusao->FormValue)) {
			ew_AddMessage($gsFormError, $this->dh_inclusao->FldErrMsg());
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

		// nu_prospecto
		$this->nu_prospecto->SetDbValueDef($rsnew, $this->nu_prospecto->CurrentValue, 0, FALSE);

		// no_assuntoOcor
		$this->no_assuntoOcor->SetDbValueDef($rsnew, $this->no_assuntoOcor->CurrentValue, NULL, FALSE);

		// ds_ocorrencia
		$this->ds_ocorrencia->SetDbValueDef($rsnew, $this->ds_ocorrencia->CurrentValue, "", FALSE);

		// dh_inclusao
		$this->dh_inclusao->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->dh_inclusao->CurrentValue, 11), NULL, FALSE);

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
			$this->nu_ocorrencia->setDbValue($conn->Insert_ID());
			$rsnew['nu_ocorrencia'] = $this->nu_ocorrencia->DbValue;
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
			if ($sMasterTblVar == "prospecto") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_prospecto"] <> "") {
					$GLOBALS["prospecto"]->nu_prospecto->setQueryStringValue($_GET["nu_prospecto"]);
					$this->nu_prospecto->setQueryStringValue($GLOBALS["prospecto"]->nu_prospecto->QueryStringValue);
					$this->nu_prospecto->setSessionValue($this->nu_prospecto->QueryStringValue);
					if (!is_numeric($GLOBALS["prospecto"]->nu_prospecto->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "prospecto") {
				if ($this->nu_prospecto->QueryStringValue == "") $this->nu_prospecto->setSessionValue("");
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "prospectoocorrenciaslist.php", $this->TableVar);
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
if (!isset($prospectoocorrencias_add)) $prospectoocorrencias_add = new cprospectoocorrencias_add();

// Page init
$prospectoocorrencias_add->Page_Init();

// Page main
$prospectoocorrencias_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$prospectoocorrencias_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var prospectoocorrencias_add = new ew_Page("prospectoocorrencias_add");
prospectoocorrencias_add.PageID = "add"; // Page ID
var EW_PAGE_ID = prospectoocorrencias_add.PageID; // For backward compatibility

// Form object
var fprospectoocorrenciasadd = new ew_Form("fprospectoocorrenciasadd");

// Validate form
fprospectoocorrenciasadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_prospecto");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($prospectoocorrencias->nu_prospecto->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no_assuntoOcor");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($prospectoocorrencias->no_assuntoOcor->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ds_ocorrencia");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($prospectoocorrencias->ds_ocorrencia->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dh_inclusao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($prospectoocorrencias->dh_inclusao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dh_inclusao");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($prospectoocorrencias->dh_inclusao->FldErrMsg()) ?>");

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
fprospectoocorrenciasadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fprospectoocorrenciasadd.ValidateRequired = true;
<?php } else { ?>
fprospectoocorrenciasadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fprospectoocorrenciasadd.Lists["x_nu_prospecto"] = {"LinkField":"x_nu_prospecto","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_prospecto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $prospectoocorrencias_add->ShowPageHeader(); ?>
<?php
$prospectoocorrencias_add->ShowMessage();
?>
<form name="fprospectoocorrenciasadd" id="fprospectoocorrenciasadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="prospectoocorrencias">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_prospectoocorrenciasadd" class="table table-bordered table-striped">
<?php if ($prospectoocorrencias->nu_prospecto->Visible) { // nu_prospecto ?>
	<tr id="r_nu_prospecto">
		<td><span id="elh_prospectoocorrencias_nu_prospecto"><?php echo $prospectoocorrencias->nu_prospecto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $prospectoocorrencias->nu_prospecto->CellAttributes() ?>>
<?php if ($prospectoocorrencias->nu_prospecto->getSessionValue() <> "") { ?>
<span<?php echo $prospectoocorrencias->nu_prospecto->ViewAttributes() ?>>
<?php echo $prospectoocorrencias->nu_prospecto->ViewValue ?></span>
<input type="hidden" id="x_nu_prospecto" name="x_nu_prospecto" value="<?php echo ew_HtmlEncode($prospectoocorrencias->nu_prospecto->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_nu_prospecto" id="x_nu_prospecto" name="x_nu_prospecto"<?php echo $prospectoocorrencias->nu_prospecto->EditAttributes() ?>>
<?php
if (is_array($prospectoocorrencias->nu_prospecto->EditValue)) {
	$arwrk = $prospectoocorrencias->nu_prospecto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($prospectoocorrencias->nu_prospecto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fprospectoocorrenciasadd.Lists["x_nu_prospecto"].Options = <?php echo (is_array($prospectoocorrencias->nu_prospecto->EditValue)) ? ew_ArrayToJson($prospectoocorrencias->nu_prospecto->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<?php echo $prospectoocorrencias->nu_prospecto->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($prospectoocorrencias->no_assuntoOcor->Visible) { // no_assuntoOcor ?>
	<tr id="r_no_assuntoOcor">
		<td><span id="elh_prospectoocorrencias_no_assuntoOcor"><?php echo $prospectoocorrencias->no_assuntoOcor->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $prospectoocorrencias->no_assuntoOcor->CellAttributes() ?>>
<span id="el_prospectoocorrencias_no_assuntoOcor" class="control-group">
<input type="text" data-field="x_no_assuntoOcor" name="x_no_assuntoOcor" id="x_no_assuntoOcor" size="30" maxlength="75" placeholder="<?php echo $prospectoocorrencias->no_assuntoOcor->PlaceHolder ?>" value="<?php echo $prospectoocorrencias->no_assuntoOcor->EditValue ?>"<?php echo $prospectoocorrencias->no_assuntoOcor->EditAttributes() ?>>
</span>
<?php echo $prospectoocorrencias->no_assuntoOcor->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($prospectoocorrencias->ds_ocorrencia->Visible) { // ds_ocorrencia ?>
	<tr id="r_ds_ocorrencia">
		<td><span id="elh_prospectoocorrencias_ds_ocorrencia"><?php echo $prospectoocorrencias->ds_ocorrencia->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $prospectoocorrencias->ds_ocorrencia->CellAttributes() ?>>
<span id="el_prospectoocorrencias_ds_ocorrencia" class="control-group">
<textarea data-field="x_ds_ocorrencia" name="x_ds_ocorrencia" id="x_ds_ocorrencia" cols="35" rows="4" placeholder="<?php echo $prospectoocorrencias->ds_ocorrencia->PlaceHolder ?>"<?php echo $prospectoocorrencias->ds_ocorrencia->EditAttributes() ?>><?php echo $prospectoocorrencias->ds_ocorrencia->EditValue ?></textarea>
</span>
<?php echo $prospectoocorrencias->ds_ocorrencia->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($prospectoocorrencias->dh_inclusao->Visible) { // dh_inclusao ?>
	<tr id="r_dh_inclusao">
		<td><span id="elh_prospectoocorrencias_dh_inclusao"><?php echo $prospectoocorrencias->dh_inclusao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $prospectoocorrencias->dh_inclusao->CellAttributes() ?>>
<span id="el_prospectoocorrencias_dh_inclusao" class="control-group">
<input type="text" data-field="x_dh_inclusao" name="x_dh_inclusao" id="x_dh_inclusao" placeholder="<?php echo $prospectoocorrencias->dh_inclusao->PlaceHolder ?>" value="<?php echo $prospectoocorrencias->dh_inclusao->EditValue ?>"<?php echo $prospectoocorrencias->dh_inclusao->EditAttributes() ?>>
<?php if (!$prospectoocorrencias->dh_inclusao->ReadOnly && !$prospectoocorrencias->dh_inclusao->Disabled && @$prospectoocorrencias->dh_inclusao->EditAttrs["readonly"] == "" && @$prospectoocorrencias->dh_inclusao->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_dh_inclusao" name="cal_x_dh_inclusao" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x_dh_inclusao" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fprospectoocorrenciasadd", "x_dh_inclusao", "%d/%m/%Y %H:%M:%S");
</script>
<?php } ?>
</span>
<?php echo $prospectoocorrencias->dh_inclusao->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fprospectoocorrenciasadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$prospectoocorrencias_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$prospectoocorrencias_add->Page_Terminate();
?>
