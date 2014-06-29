<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "fatura_osinfo.php" ?>
<?php include_once "faturainfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$fatura_os_add = NULL; // Initialize page object first

class cfatura_os_add extends cfatura_os {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'fatura_os';

	// Page object name
	var $PageObjName = 'fatura_os_add';

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

		// Table object (fatura_os)
		if (!isset($GLOBALS["fatura_os"])) {
			$GLOBALS["fatura_os"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["fatura_os"];
		}

		// Table object (fatura)
		if (!isset($GLOBALS['fatura'])) $GLOBALS['fatura'] = new cfatura();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'fatura_os', TRUE);

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
			$this->Page_Terminate("fatura_oslist.php");
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
			if (@$_GET["nu_fatura"] != "") {
				$this->nu_fatura->setQueryStringValue($_GET["nu_fatura"]);
				$this->setKey("nu_fatura", $this->nu_fatura->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_fatura", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if (@$_GET["nu_os"] != "") {
				$this->nu_os->setQueryStringValue($_GET["nu_os"]);
				$this->setKey("nu_os", $this->nu_os->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_os", ""); // Clear key
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
					$this->Page_Terminate("fatura_oslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "fatura_osview.php")
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
		$this->nu_fatura->CurrentValue = NULL;
		$this->nu_fatura->OldValue = $this->nu_fatura->CurrentValue;
		$this->nu_os->CurrentValue = NULL;
		$this->nu_os->OldValue = $this->nu_os->CurrentValue;
		$this->ic_pagIntegralOs->CurrentValue = NULL;
		$this->ic_pagIntegralOs->OldValue = $this->ic_pagIntegralOs->CurrentValue;
		$this->vr_pagoOsFatura->CurrentValue = NULL;
		$this->vr_pagoOsFatura->OldValue = $this->vr_pagoOsFatura->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_fatura->FldIsDetailKey) {
			$this->nu_fatura->setFormValue($objForm->GetValue("x_nu_fatura"));
		}
		if (!$this->nu_os->FldIsDetailKey) {
			$this->nu_os->setFormValue($objForm->GetValue("x_nu_os"));
		}
		if (!$this->ic_pagIntegralOs->FldIsDetailKey) {
			$this->ic_pagIntegralOs->setFormValue($objForm->GetValue("x_ic_pagIntegralOs"));
		}
		if (!$this->vr_pagoOsFatura->FldIsDetailKey) {
			$this->vr_pagoOsFatura->setFormValue($objForm->GetValue("x_vr_pagoOsFatura"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->nu_fatura->CurrentValue = $this->nu_fatura->FormValue;
		$this->nu_os->CurrentValue = $this->nu_os->FormValue;
		$this->ic_pagIntegralOs->CurrentValue = $this->ic_pagIntegralOs->FormValue;
		$this->vr_pagoOsFatura->CurrentValue = $this->vr_pagoOsFatura->FormValue;
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
		$this->nu_fatura->setDbValue($rs->fields('nu_fatura'));
		$this->nu_os->setDbValue($rs->fields('nu_os'));
		$this->ic_pagIntegralOs->setDbValue($rs->fields('ic_pagIntegralOs'));
		$this->vr_pagoOsFatura->setDbValue($rs->fields('vr_pagoOsFatura'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_fatura->DbValue = $row['nu_fatura'];
		$this->nu_os->DbValue = $row['nu_os'];
		$this->ic_pagIntegralOs->DbValue = $row['ic_pagIntegralOs'];
		$this->vr_pagoOsFatura->DbValue = $row['vr_pagoOsFatura'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_fatura")) <> "")
			$this->nu_fatura->CurrentValue = $this->getKey("nu_fatura"); // nu_fatura
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("nu_os")) <> "")
			$this->nu_os->CurrentValue = $this->getKey("nu_os"); // nu_os
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

		if ($this->vr_pagoOsFatura->FormValue == $this->vr_pagoOsFatura->CurrentValue && is_numeric(ew_StrToFloat($this->vr_pagoOsFatura->CurrentValue)))
			$this->vr_pagoOsFatura->CurrentValue = ew_StrToFloat($this->vr_pagoOsFatura->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_fatura
		// nu_os
		// ic_pagIntegralOs
		// vr_pagoOsFatura

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_fatura
			if (strval($this->nu_fatura->CurrentValue) <> "") {
				$sFilterWrk = "[nu_fatura]" . ew_SearchString("=", $this->nu_fatura->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_fatura], [nu_fatura] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[fatura]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_fatura, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_fatura] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_fatura->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_fatura->ViewValue = $this->nu_fatura->CurrentValue;
				}
			} else {
				$this->nu_fatura->ViewValue = NULL;
			}
			$this->nu_fatura->ViewCustomAttributes = "";

			// nu_os
			if (strval($this->nu_os->CurrentValue) <> "") {
				$sFilterWrk = "[nu_os]" . ew_SearchString("=", $this->nu_os->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_os], [no_titulo] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[os]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_os, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_os] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_os->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_os->ViewValue = $this->nu_os->CurrentValue;
				}
			} else {
				$this->nu_os->ViewValue = NULL;
			}
			$this->nu_os->ViewCustomAttributes = "";

			// ic_pagIntegralOs
			if (strval($this->ic_pagIntegralOs->CurrentValue) <> "") {
				switch ($this->ic_pagIntegralOs->CurrentValue) {
					case $this->ic_pagIntegralOs->FldTagValue(1):
						$this->ic_pagIntegralOs->ViewValue = $this->ic_pagIntegralOs->FldTagCaption(1) <> "" ? $this->ic_pagIntegralOs->FldTagCaption(1) : $this->ic_pagIntegralOs->CurrentValue;
						break;
					case $this->ic_pagIntegralOs->FldTagValue(2):
						$this->ic_pagIntegralOs->ViewValue = $this->ic_pagIntegralOs->FldTagCaption(2) <> "" ? $this->ic_pagIntegralOs->FldTagCaption(2) : $this->ic_pagIntegralOs->CurrentValue;
						break;
					default:
						$this->ic_pagIntegralOs->ViewValue = $this->ic_pagIntegralOs->CurrentValue;
				}
			} else {
				$this->ic_pagIntegralOs->ViewValue = NULL;
			}
			$this->ic_pagIntegralOs->ViewCustomAttributes = "";

			// vr_pagoOsFatura
			$this->vr_pagoOsFatura->ViewValue = $this->vr_pagoOsFatura->CurrentValue;
			$this->vr_pagoOsFatura->ViewCustomAttributes = "";

			// nu_fatura
			$this->nu_fatura->LinkCustomAttributes = "";
			$this->nu_fatura->HrefValue = "";
			$this->nu_fatura->TooltipValue = "";

			// nu_os
			$this->nu_os->LinkCustomAttributes = "";
			$this->nu_os->HrefValue = "";
			$this->nu_os->TooltipValue = "";

			// ic_pagIntegralOs
			$this->ic_pagIntegralOs->LinkCustomAttributes = "";
			$this->ic_pagIntegralOs->HrefValue = "";
			$this->ic_pagIntegralOs->TooltipValue = "";

			// vr_pagoOsFatura
			$this->vr_pagoOsFatura->LinkCustomAttributes = "";
			$this->vr_pagoOsFatura->HrefValue = "";
			$this->vr_pagoOsFatura->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nu_fatura
			$this->nu_fatura->EditCustomAttributes = "";
			if ($this->nu_fatura->getSessionValue() <> "") {
				$this->nu_fatura->CurrentValue = $this->nu_fatura->getSessionValue();
			if (strval($this->nu_fatura->CurrentValue) <> "") {
				$sFilterWrk = "[nu_fatura]" . ew_SearchString("=", $this->nu_fatura->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_fatura], [nu_fatura] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[fatura]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_fatura, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_fatura] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_fatura->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_fatura->ViewValue = $this->nu_fatura->CurrentValue;
				}
			} else {
				$this->nu_fatura->ViewValue = NULL;
			}
			$this->nu_fatura->ViewCustomAttributes = "";
			} else {
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_fatura], [nu_fatura] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[fatura]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_fatura, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_fatura] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_fatura->EditValue = $arwrk;
			}

			// nu_os
			$this->nu_os->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_os], [no_titulo] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[os]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_os, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_os] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_os->EditValue = $arwrk;

			// ic_pagIntegralOs
			$this->ic_pagIntegralOs->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_pagIntegralOs->FldTagValue(1), $this->ic_pagIntegralOs->FldTagCaption(1) <> "" ? $this->ic_pagIntegralOs->FldTagCaption(1) : $this->ic_pagIntegralOs->FldTagValue(1));
			$arwrk[] = array($this->ic_pagIntegralOs->FldTagValue(2), $this->ic_pagIntegralOs->FldTagCaption(2) <> "" ? $this->ic_pagIntegralOs->FldTagCaption(2) : $this->ic_pagIntegralOs->FldTagValue(2));
			$this->ic_pagIntegralOs->EditValue = $arwrk;

			// vr_pagoOsFatura
			$this->vr_pagoOsFatura->EditCustomAttributes = "";
			$this->vr_pagoOsFatura->EditValue = ew_HtmlEncode($this->vr_pagoOsFatura->CurrentValue);
			$this->vr_pagoOsFatura->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_pagoOsFatura->FldCaption()));
			if (strval($this->vr_pagoOsFatura->EditValue) <> "" && is_numeric($this->vr_pagoOsFatura->EditValue)) $this->vr_pagoOsFatura->EditValue = ew_FormatNumber($this->vr_pagoOsFatura->EditValue, -2, -1, -2, 0);

			// Edit refer script
			// nu_fatura

			$this->nu_fatura->HrefValue = "";

			// nu_os
			$this->nu_os->HrefValue = "";

			// ic_pagIntegralOs
			$this->ic_pagIntegralOs->HrefValue = "";

			// vr_pagoOsFatura
			$this->vr_pagoOsFatura->HrefValue = "";
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
		if (!$this->nu_fatura->FldIsDetailKey && !is_null($this->nu_fatura->FormValue) && $this->nu_fatura->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_fatura->FldCaption());
		}
		if (!$this->nu_os->FldIsDetailKey && !is_null($this->nu_os->FormValue) && $this->nu_os->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_os->FldCaption());
		}
		if ($this->ic_pagIntegralOs->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_pagIntegralOs->FldCaption());
		}
		if (!$this->vr_pagoOsFatura->FldIsDetailKey && !is_null($this->vr_pagoOsFatura->FormValue) && $this->vr_pagoOsFatura->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->vr_pagoOsFatura->FldCaption());
		}
		if (!ew_CheckNumber($this->vr_pagoOsFatura->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_pagoOsFatura->FldErrMsg());
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

		// Check referential integrity for master table 'fatura'
		$bValidMasterRecord = TRUE;
		$sMasterFilter = $this->SqlMasterFilter_fatura();
		if (strval($this->nu_fatura->CurrentValue) <> "") {
			$sMasterFilter = str_replace("@nu_fatura@", ew_AdjustSql($this->nu_fatura->CurrentValue), $sMasterFilter);
		} else {
			$bValidMasterRecord = FALSE;
		}
		if ($bValidMasterRecord) {
			$rsmaster = $GLOBALS["fatura"]->LoadRs($sMasterFilter);
			$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
			$rsmaster->Close();
		}
		if (!$bValidMasterRecord) {
			$sRelatedRecordMsg = str_replace("%t", "fatura", $Language->Phrase("RelatedRecordRequired"));
			$this->setFailureMessage($sRelatedRecordMsg);
			return FALSE;
		}

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// nu_fatura
		$this->nu_fatura->SetDbValueDef($rsnew, $this->nu_fatura->CurrentValue, 0, FALSE);

		// nu_os
		$this->nu_os->SetDbValueDef($rsnew, $this->nu_os->CurrentValue, 0, FALSE);

		// ic_pagIntegralOs
		$this->ic_pagIntegralOs->SetDbValueDef($rsnew, $this->ic_pagIntegralOs->CurrentValue, NULL, FALSE);

		// vr_pagoOsFatura
		$this->vr_pagoOsFatura->SetDbValueDef($rsnew, $this->vr_pagoOsFatura->CurrentValue, 0, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && $this->nu_fatura->CurrentValue == "" && $this->nu_fatura->getSessionValue() == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && $this->nu_os->CurrentValue == "" && $this->nu_os->getSessionValue() == "") {
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
			if ($sMasterTblVar == "fatura") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_fatura"] <> "") {
					$GLOBALS["fatura"]->nu_fatura->setQueryStringValue($_GET["nu_fatura"]);
					$this->nu_fatura->setQueryStringValue($GLOBALS["fatura"]->nu_fatura->QueryStringValue);
					$this->nu_fatura->setSessionValue($this->nu_fatura->QueryStringValue);
					if (!is_numeric($GLOBALS["fatura"]->nu_fatura->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "fatura") {
				if ($this->nu_fatura->QueryStringValue == "") $this->nu_fatura->setSessionValue("");
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "fatura_oslist.php", $this->TableVar);
		$PageCaption = ($this->CurrentAction == "C") ? $Language->Phrase("Copy") : $Language->Phrase("Add");
		$Breadcrumb->Add("add", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'fatura_os';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'fatura_os';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_fatura'];
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_os'];

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
if (!isset($fatura_os_add)) $fatura_os_add = new cfatura_os_add();

// Page init
$fatura_os_add->Page_Init();

// Page main
$fatura_os_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$fatura_os_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var fatura_os_add = new ew_Page("fatura_os_add");
fatura_os_add.PageID = "add"; // Page ID
var EW_PAGE_ID = fatura_os_add.PageID; // For backward compatibility

// Form object
var ffatura_osadd = new ew_Form("ffatura_osadd");

// Validate form
ffatura_osadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_fatura");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($fatura_os->nu_fatura->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_os");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($fatura_os->nu_os->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_pagIntegralOs");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($fatura_os->ic_pagIntegralOs->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_pagoOsFatura");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($fatura_os->vr_pagoOsFatura->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_pagoOsFatura");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($fatura_os->vr_pagoOsFatura->FldErrMsg()) ?>");

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
ffatura_osadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffatura_osadd.ValidateRequired = true;
<?php } else { ?>
ffatura_osadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ffatura_osadd.Lists["x_nu_fatura"] = {"LinkField":"x_nu_fatura","Ajax":null,"AutoFill":false,"DisplayFields":["x_nu_fatura","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ffatura_osadd.Lists["x_nu_os"] = {"LinkField":"x_nu_os","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_titulo","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $fatura_os_add->ShowPageHeader(); ?>
<?php
$fatura_os_add->ShowMessage();
?>
<form name="ffatura_osadd" id="ffatura_osadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="fatura_os">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_fatura_osadd" class="table table-bordered table-striped">
<?php if ($fatura_os->nu_fatura->Visible) { // nu_fatura ?>
	<tr id="r_nu_fatura">
		<td><span id="elh_fatura_os_nu_fatura"><?php echo $fatura_os->nu_fatura->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $fatura_os->nu_fatura->CellAttributes() ?>>
<?php if ($fatura_os->nu_fatura->getSessionValue() <> "") { ?>
<span<?php echo $fatura_os->nu_fatura->ViewAttributes() ?>>
<?php echo $fatura_os->nu_fatura->ViewValue ?></span>
<input type="hidden" id="x_nu_fatura" name="x_nu_fatura" value="<?php echo ew_HtmlEncode($fatura_os->nu_fatura->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_nu_fatura" id="x_nu_fatura" name="x_nu_fatura"<?php echo $fatura_os->nu_fatura->EditAttributes() ?>>
<?php
if (is_array($fatura_os->nu_fatura->EditValue)) {
	$arwrk = $fatura_os->nu_fatura->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($fatura_os->nu_fatura->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
ffatura_osadd.Lists["x_nu_fatura"].Options = <?php echo (is_array($fatura_os->nu_fatura->EditValue)) ? ew_ArrayToJson($fatura_os->nu_fatura->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<?php echo $fatura_os->nu_fatura->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fatura_os->nu_os->Visible) { // nu_os ?>
	<tr id="r_nu_os">
		<td><span id="elh_fatura_os_nu_os"><?php echo $fatura_os->nu_os->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $fatura_os->nu_os->CellAttributes() ?>>
<span id="el_fatura_os_nu_os" class="control-group">
<select data-field="x_nu_os" id="x_nu_os" name="x_nu_os"<?php echo $fatura_os->nu_os->EditAttributes() ?>>
<?php
if (is_array($fatura_os->nu_os->EditValue)) {
	$arwrk = $fatura_os->nu_os->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($fatura_os->nu_os->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
ffatura_osadd.Lists["x_nu_os"].Options = <?php echo (is_array($fatura_os->nu_os->EditValue)) ? ew_ArrayToJson($fatura_os->nu_os->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $fatura_os->nu_os->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fatura_os->ic_pagIntegralOs->Visible) { // ic_pagIntegralOs ?>
	<tr id="r_ic_pagIntegralOs">
		<td><span id="elh_fatura_os_ic_pagIntegralOs"><?php echo $fatura_os->ic_pagIntegralOs->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $fatura_os->ic_pagIntegralOs->CellAttributes() ?>>
<span id="el_fatura_os_ic_pagIntegralOs" class="control-group">
<div id="tp_x_ic_pagIntegralOs" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_pagIntegralOs" id="x_ic_pagIntegralOs" value="{value}"<?php echo $fatura_os->ic_pagIntegralOs->EditAttributes() ?>></div>
<div id="dsl_x_ic_pagIntegralOs" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $fatura_os->ic_pagIntegralOs->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($fatura_os->ic_pagIntegralOs->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_pagIntegralOs" name="x_ic_pagIntegralOs" id="x_ic_pagIntegralOs_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $fatura_os->ic_pagIntegralOs->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $fatura_os->ic_pagIntegralOs->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fatura_os->vr_pagoOsFatura->Visible) { // vr_pagoOsFatura ?>
	<tr id="r_vr_pagoOsFatura">
		<td><span id="elh_fatura_os_vr_pagoOsFatura"><?php echo $fatura_os->vr_pagoOsFatura->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $fatura_os->vr_pagoOsFatura->CellAttributes() ?>>
<span id="el_fatura_os_vr_pagoOsFatura" class="control-group">
<input type="text" data-field="x_vr_pagoOsFatura" name="x_vr_pagoOsFatura" id="x_vr_pagoOsFatura" size="30" placeholder="<?php echo $fatura_os->vr_pagoOsFatura->PlaceHolder ?>" value="<?php echo $fatura_os->vr_pagoOsFatura->EditValue ?>"<?php echo $fatura_os->vr_pagoOsFatura->EditAttributes() ?>>
</span>
<?php echo $fatura_os->vr_pagoOsFatura->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
ffatura_osadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$fatura_os_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$fatura_os_add->Page_Terminate();
?>
