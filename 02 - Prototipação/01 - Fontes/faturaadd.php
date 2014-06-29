<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "faturainfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "fatura_osgridcls.php" ?>
<?php include_once "nffaturagridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$fatura_add = NULL; // Initialize page object first

class cfatura_add extends cfatura {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'fatura';

	// Page object name
	var $PageObjName = 'fatura_add';

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

		// Table object (fatura)
		if (!isset($GLOBALS["fatura"])) {
			$GLOBALS["fatura"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["fatura"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'fatura', TRUE);

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
			$this->Page_Terminate("faturalist.php");
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
			if (@$_GET["nu_fatura"] != "") {
				$this->nu_fatura->setQueryStringValue($_GET["nu_fatura"]);
				$this->setKey("nu_fatura", $this->nu_fatura->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_fatura", ""); // Clear key
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
					$this->Page_Terminate("faturalist.php"); // No matching record, return to list
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
					if (ew_GetPageName($sReturnUrl) == "faturaview.php")
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
		$this->nu_tpFatura->CurrentValue = NULL;
		$this->nu_tpFatura->OldValue = $this->nu_tpFatura->CurrentValue;
		$this->ds_fatura->CurrentValue = NULL;
		$this->ds_fatura->OldValue = $this->ds_fatura->CurrentValue;
		$this->dt_faturamento->CurrentValue = NULL;
		$this->dt_faturamento->OldValue = $this->dt_faturamento->CurrentValue;
		$this->nu_stFatura->CurrentValue = NULL;
		$this->nu_stFatura->OldValue = $this->nu_stFatura->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_tpFatura->FldIsDetailKey) {
			$this->nu_tpFatura->setFormValue($objForm->GetValue("x_nu_tpFatura"));
		}
		if (!$this->ds_fatura->FldIsDetailKey) {
			$this->ds_fatura->setFormValue($objForm->GetValue("x_ds_fatura"));
		}
		if (!$this->dt_faturamento->FldIsDetailKey) {
			$this->dt_faturamento->setFormValue($objForm->GetValue("x_dt_faturamento"));
			$this->dt_faturamento->CurrentValue = ew_UnFormatDateTime($this->dt_faturamento->CurrentValue, 7);
		}
		if (!$this->nu_stFatura->FldIsDetailKey) {
			$this->nu_stFatura->setFormValue($objForm->GetValue("x_nu_stFatura"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->nu_tpFatura->CurrentValue = $this->nu_tpFatura->FormValue;
		$this->ds_fatura->CurrentValue = $this->ds_fatura->FormValue;
		$this->dt_faturamento->CurrentValue = $this->dt_faturamento->FormValue;
		$this->dt_faturamento->CurrentValue = ew_UnFormatDateTime($this->dt_faturamento->CurrentValue, 7);
		$this->nu_stFatura->CurrentValue = $this->nu_stFatura->FormValue;
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
		$this->nu_tpFatura->setDbValue($rs->fields('nu_tpFatura'));
		$this->ds_fatura->setDbValue($rs->fields('ds_fatura'));
		$this->dt_faturamento->setDbValue($rs->fields('dt_faturamento'));
		$this->nu_stFatura->setDbValue($rs->fields('nu_stFatura'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_fatura->DbValue = $row['nu_fatura'];
		$this->nu_tpFatura->DbValue = $row['nu_tpFatura'];
		$this->ds_fatura->DbValue = $row['ds_fatura'];
		$this->dt_faturamento->DbValue = $row['dt_faturamento'];
		$this->nu_stFatura->DbValue = $row['nu_stFatura'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_fatura")) <> "")
			$this->nu_fatura->CurrentValue = $this->getKey("nu_fatura"); // nu_fatura
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
		// nu_fatura
		// nu_tpFatura
		// ds_fatura
		// dt_faturamento
		// nu_stFatura

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_fatura
			$this->nu_fatura->ViewValue = $this->nu_fatura->CurrentValue;
			$this->nu_fatura->ViewCustomAttributes = "";

			// nu_tpFatura
			if (strval($this->nu_tpFatura->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpFatura]" . ew_SearchString("=", $this->nu_tpFatura->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpFatura], [no_tpFatura] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tipofatura]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpFatura, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpFatura] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpFatura->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpFatura->ViewValue = $this->nu_tpFatura->CurrentValue;
				}
			} else {
				$this->nu_tpFatura->ViewValue = NULL;
			}
			$this->nu_tpFatura->ViewCustomAttributes = "";

			// ds_fatura
			$this->ds_fatura->ViewValue = $this->ds_fatura->CurrentValue;
			$this->ds_fatura->ViewCustomAttributes = "";

			// dt_faturamento
			$this->dt_faturamento->ViewValue = $this->dt_faturamento->CurrentValue;
			$this->dt_faturamento->ViewValue = ew_FormatDateTime($this->dt_faturamento->ViewValue, 7);
			$this->dt_faturamento->ViewCustomAttributes = "";

			// nu_stFatura
			if (strval($this->nu_stFatura->CurrentValue) <> "") {
				$sFilterWrk = "[nu_stFatura]" . ew_SearchString("=", $this->nu_stFatura->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_stFatura], [no_stFatura] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[stfatura]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_stFatura, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_stFatura] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_stFatura->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_stFatura->ViewValue = $this->nu_stFatura->CurrentValue;
				}
			} else {
				$this->nu_stFatura->ViewValue = NULL;
			}
			$this->nu_stFatura->ViewCustomAttributes = "";

			// nu_tpFatura
			$this->nu_tpFatura->LinkCustomAttributes = "";
			$this->nu_tpFatura->HrefValue = "";
			$this->nu_tpFatura->TooltipValue = "";

			// ds_fatura
			$this->ds_fatura->LinkCustomAttributes = "";
			$this->ds_fatura->HrefValue = "";
			$this->ds_fatura->TooltipValue = "";

			// dt_faturamento
			$this->dt_faturamento->LinkCustomAttributes = "";
			$this->dt_faturamento->HrefValue = "";
			$this->dt_faturamento->TooltipValue = "";

			// nu_stFatura
			$this->nu_stFatura->LinkCustomAttributes = "";
			$this->nu_stFatura->HrefValue = "";
			$this->nu_stFatura->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nu_tpFatura
			$this->nu_tpFatura->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_tpFatura], [no_tpFatura] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[tipofatura]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpFatura, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpFatura] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_tpFatura->EditValue = $arwrk;

			// ds_fatura
			$this->ds_fatura->EditCustomAttributes = "";
			$this->ds_fatura->EditValue = $this->ds_fatura->CurrentValue;
			$this->ds_fatura->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_fatura->FldCaption()));

			// dt_faturamento
			$this->dt_faturamento->EditCustomAttributes = "";
			$this->dt_faturamento->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->dt_faturamento->CurrentValue, 7));
			$this->dt_faturamento->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dt_faturamento->FldCaption()));

			// nu_stFatura
			$this->nu_stFatura->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_stFatura], [no_stFatura] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[stfatura]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_stFatura, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_stFatura] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_stFatura->EditValue = $arwrk;

			// Edit refer script
			// nu_tpFatura

			$this->nu_tpFatura->HrefValue = "";

			// ds_fatura
			$this->ds_fatura->HrefValue = "";

			// dt_faturamento
			$this->dt_faturamento->HrefValue = "";

			// nu_stFatura
			$this->nu_stFatura->HrefValue = "";
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
		if (!$this->nu_tpFatura->FldIsDetailKey && !is_null($this->nu_tpFatura->FormValue) && $this->nu_tpFatura->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_tpFatura->FldCaption());
		}
		if (!$this->ds_fatura->FldIsDetailKey && !is_null($this->ds_fatura->FormValue) && $this->ds_fatura->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ds_fatura->FldCaption());
		}
		if (!$this->dt_faturamento->FldIsDetailKey && !is_null($this->dt_faturamento->FormValue) && $this->dt_faturamento->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->dt_faturamento->FldCaption());
		}
		if (!ew_CheckEuroDate($this->dt_faturamento->FormValue)) {
			ew_AddMessage($gsFormError, $this->dt_faturamento->FldErrMsg());
		}
		if (!$this->nu_stFatura->FldIsDetailKey && !is_null($this->nu_stFatura->FormValue) && $this->nu_stFatura->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_stFatura->FldCaption());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("fatura_os", $DetailTblVar) && $GLOBALS["fatura_os"]->DetailAdd) {
			if (!isset($GLOBALS["fatura_os_grid"])) $GLOBALS["fatura_os_grid"] = new cfatura_os_grid(); // get detail page object
			$GLOBALS["fatura_os_grid"]->ValidateGridForm();
		}
		if (in_array("nffatura", $DetailTblVar) && $GLOBALS["nffatura"]->DetailAdd) {
			if (!isset($GLOBALS["nffatura_grid"])) $GLOBALS["nffatura_grid"] = new cnffatura_grid(); // get detail page object
			$GLOBALS["nffatura_grid"]->ValidateGridForm();
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

		// nu_tpFatura
		$this->nu_tpFatura->SetDbValueDef($rsnew, $this->nu_tpFatura->CurrentValue, NULL, FALSE);

		// ds_fatura
		$this->ds_fatura->SetDbValueDef($rsnew, $this->ds_fatura->CurrentValue, "", FALSE);

		// dt_faturamento
		$this->dt_faturamento->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->dt_faturamento->CurrentValue, 7), ew_CurrentDate(), FALSE);

		// nu_stFatura
		$this->nu_stFatura->SetDbValueDef($rsnew, $this->nu_stFatura->CurrentValue, NULL, FALSE);

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
			$this->nu_fatura->setDbValue($conn->Insert_ID());
			$rsnew['nu_fatura'] = $this->nu_fatura->DbValue;
		}

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("fatura_os", $DetailTblVar) && $GLOBALS["fatura_os"]->DetailAdd) {
				$GLOBALS["fatura_os"]->nu_fatura->setSessionValue($this->nu_fatura->CurrentValue); // Set master key
				if (!isset($GLOBALS["fatura_os_grid"])) $GLOBALS["fatura_os_grid"] = new cfatura_os_grid(); // Get detail page object
				$AddRow = $GLOBALS["fatura_os_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["fatura_os"]->nu_fatura->setSessionValue(""); // Clear master key if insert failed
			}
			if (in_array("nffatura", $DetailTblVar) && $GLOBALS["nffatura"]->DetailAdd) {
				$GLOBALS["nffatura"]->nu_fatura->setSessionValue($this->nu_fatura->CurrentValue); // Set master key
				if (!isset($GLOBALS["nffatura_grid"])) $GLOBALS["nffatura_grid"] = new cnffatura_grid(); // Get detail page object
				$AddRow = $GLOBALS["nffatura_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["nffatura"]->nu_fatura->setSessionValue(""); // Clear master key if insert failed
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
			if (in_array("fatura_os", $DetailTblVar)) {
				if (!isset($GLOBALS["fatura_os_grid"]))
					$GLOBALS["fatura_os_grid"] = new cfatura_os_grid;
				if ($GLOBALS["fatura_os_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["fatura_os_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["fatura_os_grid"]->CurrentMode = "add";
					$GLOBALS["fatura_os_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["fatura_os_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["fatura_os_grid"]->setStartRecordNumber(1);
					$GLOBALS["fatura_os_grid"]->nu_fatura->FldIsDetailKey = TRUE;
					$GLOBALS["fatura_os_grid"]->nu_fatura->CurrentValue = $this->nu_fatura->CurrentValue;
					$GLOBALS["fatura_os_grid"]->nu_fatura->setSessionValue($GLOBALS["fatura_os_grid"]->nu_fatura->CurrentValue);
				}
			}
			if (in_array("nffatura", $DetailTblVar)) {
				if (!isset($GLOBALS["nffatura_grid"]))
					$GLOBALS["nffatura_grid"] = new cnffatura_grid;
				if ($GLOBALS["nffatura_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["nffatura_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["nffatura_grid"]->CurrentMode = "add";
					$GLOBALS["nffatura_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["nffatura_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["nffatura_grid"]->setStartRecordNumber(1);
					$GLOBALS["nffatura_grid"]->nu_fatura->FldIsDetailKey = TRUE;
					$GLOBALS["nffatura_grid"]->nu_fatura->CurrentValue = $this->nu_fatura->CurrentValue;
					$GLOBALS["nffatura_grid"]->nu_fatura->setSessionValue($GLOBALS["nffatura_grid"]->nu_fatura->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "faturalist.php", $this->TableVar);
		$PageCaption = ($this->CurrentAction == "C") ? $Language->Phrase("Copy") : $Language->Phrase("Add");
		$Breadcrumb->Add("add", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'fatura';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'fatura';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_fatura'];

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
if (!isset($fatura_add)) $fatura_add = new cfatura_add();

// Page init
$fatura_add->Page_Init();

// Page main
$fatura_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$fatura_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var fatura_add = new ew_Page("fatura_add");
fatura_add.PageID = "add"; // Page ID
var EW_PAGE_ID = fatura_add.PageID; // For backward compatibility

// Form object
var ffaturaadd = new ew_Form("ffaturaadd");

// Validate form
ffaturaadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_tpFatura");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($fatura->nu_tpFatura->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ds_fatura");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($fatura->ds_fatura->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dt_faturamento");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($fatura->dt_faturamento->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dt_faturamento");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($fatura->dt_faturamento->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nu_stFatura");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($fatura->nu_stFatura->FldCaption()) ?>");

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
ffaturaadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffaturaadd.ValidateRequired = true;
<?php } else { ?>
ffaturaadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ffaturaadd.Lists["x_nu_tpFatura"] = {"LinkField":"x_nu_tpFatura","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpFatura","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ffaturaadd.Lists["x_nu_stFatura"] = {"LinkField":"x_nu_stFatura","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_stFatura","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $fatura_add->ShowPageHeader(); ?>
<?php
$fatura_add->ShowMessage();
?>
<form name="ffaturaadd" id="ffaturaadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="fatura">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_faturaadd" class="table table-bordered table-striped">
<?php if ($fatura->nu_tpFatura->Visible) { // nu_tpFatura ?>
	<tr id="r_nu_tpFatura">
		<td><span id="elh_fatura_nu_tpFatura"><?php echo $fatura->nu_tpFatura->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $fatura->nu_tpFatura->CellAttributes() ?>>
<span id="el_fatura_nu_tpFatura" class="control-group">
<select data-field="x_nu_tpFatura" id="x_nu_tpFatura" name="x_nu_tpFatura"<?php echo $fatura->nu_tpFatura->EditAttributes() ?>>
<?php
if (is_array($fatura->nu_tpFatura->EditValue)) {
	$arwrk = $fatura->nu_tpFatura->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($fatura->nu_tpFatura->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
ffaturaadd.Lists["x_nu_tpFatura"].Options = <?php echo (is_array($fatura->nu_tpFatura->EditValue)) ? ew_ArrayToJson($fatura->nu_tpFatura->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $fatura->nu_tpFatura->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fatura->ds_fatura->Visible) { // ds_fatura ?>
	<tr id="r_ds_fatura">
		<td><span id="elh_fatura_ds_fatura"><?php echo $fatura->ds_fatura->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $fatura->ds_fatura->CellAttributes() ?>>
<span id="el_fatura_ds_fatura" class="control-group">
<textarea data-field="x_ds_fatura" name="x_ds_fatura" id="x_ds_fatura" cols="35" rows="4" placeholder="<?php echo $fatura->ds_fatura->PlaceHolder ?>"<?php echo $fatura->ds_fatura->EditAttributes() ?>><?php echo $fatura->ds_fatura->EditValue ?></textarea>
</span>
<?php echo $fatura->ds_fatura->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fatura->dt_faturamento->Visible) { // dt_faturamento ?>
	<tr id="r_dt_faturamento">
		<td><span id="elh_fatura_dt_faturamento"><?php echo $fatura->dt_faturamento->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $fatura->dt_faturamento->CellAttributes() ?>>
<span id="el_fatura_dt_faturamento" class="control-group">
<input type="text" data-field="x_dt_faturamento" name="x_dt_faturamento" id="x_dt_faturamento" placeholder="<?php echo $fatura->dt_faturamento->PlaceHolder ?>" value="<?php echo $fatura->dt_faturamento->EditValue ?>"<?php echo $fatura->dt_faturamento->EditAttributes() ?>>
<?php if (!$fatura->dt_faturamento->ReadOnly && !$fatura->dt_faturamento->Disabled && @$fatura->dt_faturamento->EditAttrs["readonly"] == "" && @$fatura->dt_faturamento->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_dt_faturamento" name="cal_x_dt_faturamento" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x_dt_faturamento" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("ffaturaadd", "x_dt_faturamento", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $fatura->dt_faturamento->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fatura->nu_stFatura->Visible) { // nu_stFatura ?>
	<tr id="r_nu_stFatura">
		<td><span id="elh_fatura_nu_stFatura"><?php echo $fatura->nu_stFatura->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $fatura->nu_stFatura->CellAttributes() ?>>
<span id="el_fatura_nu_stFatura" class="control-group">
<select data-field="x_nu_stFatura" id="x_nu_stFatura" name="x_nu_stFatura"<?php echo $fatura->nu_stFatura->EditAttributes() ?>>
<?php
if (is_array($fatura->nu_stFatura->EditValue)) {
	$arwrk = $fatura->nu_stFatura->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($fatura->nu_stFatura->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
ffaturaadd.Lists["x_nu_stFatura"].Options = <?php echo (is_array($fatura->nu_stFatura->EditValue)) ? ew_ArrayToJson($fatura->nu_stFatura->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $fatura->nu_stFatura->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<?php
	if (in_array("fatura_os", explode(",", $fatura->getCurrentDetailTable())) && $fatura_os->DetailAdd) {
?>
<?php include_once "fatura_osgrid.php" ?>
<?php } ?>
<?php
	if (in_array("nffatura", explode(",", $fatura->getCurrentDetailTable())) && $nffatura->DetailAdd) {
?>
<?php include_once "nffaturagrid.php" ?>
<?php } ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
ffaturaadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$fatura_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$fatura_add->Page_Terminate();
?>
