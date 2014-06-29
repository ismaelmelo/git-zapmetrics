<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "ucinfo.php" ?>
<?php include_once "sistemainfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "uc_atorgridcls.php" ?>
<?php include_once "uc_mensagemgridcls.php" ?>
<?php include_once "uc_regranegociogridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$uc_edit = NULL; // Initialize page object first

class cuc_edit extends cuc {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'uc';

	// Page object name
	var $PageObjName = 'uc_edit';

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
	var $AuditTrailOnEdit = TRUE;

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

		// Table object (uc)
		if (!isset($GLOBALS["uc"])) {
			$GLOBALS["uc"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["uc"];
		}

		// Table object (sistema)
		if (!isset($GLOBALS['sistema'])) $GLOBALS['sistema'] = new csistema();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'uc', TRUE);

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
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("uclist.php");
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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["nu_uc"] <> "") {
			$this->nu_uc->setQueryStringValue($_GET["nu_uc"]);
		}

		// Set up master detail parameters
		$this->SetUpMasterParms();

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values

			// Set up detail parameters
			$this->SetUpDetailParms();
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->nu_uc->CurrentValue == "")
			$this->Page_Terminate("uclist.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("uclist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					if ($this->getCurrentDetailTable() <> "") // Master/detail edit
						$sReturnUrl = $this->GetDetailUrl();
					else
						$sReturnUrl = $this->getReturnUrl();
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed

					// Set up detail parameters
					$this->SetUpDetailParms();
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_sistema->FldIsDetailKey) {
			$this->nu_sistema->setFormValue($objForm->GetValue("x_nu_sistema"));
		}
		if (!$this->nu_modulo->FldIsDetailKey) {
			$this->nu_modulo->setFormValue($objForm->GetValue("x_nu_modulo"));
		}
		if (!$this->co_alternativo->FldIsDetailKey) {
			$this->co_alternativo->setFormValue($objForm->GetValue("x_co_alternativo"));
		}
		if (!$this->no_uc->FldIsDetailKey) {
			$this->no_uc->setFormValue($objForm->GetValue("x_no_uc"));
		}
		if (!$this->ds_uc->FldIsDetailKey) {
			$this->ds_uc->setFormValue($objForm->GetValue("x_ds_uc"));
		}
		if (!$this->nu_stUc->FldIsDetailKey) {
			$this->nu_stUc->setFormValue($objForm->GetValue("x_nu_stUc"));
		}
		if (!$this->nu_uc->FldIsDetailKey)
			$this->nu_uc->setFormValue($objForm->GetValue("x_nu_uc"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->nu_uc->CurrentValue = $this->nu_uc->FormValue;
		$this->nu_sistema->CurrentValue = $this->nu_sistema->FormValue;
		$this->nu_modulo->CurrentValue = $this->nu_modulo->FormValue;
		$this->co_alternativo->CurrentValue = $this->co_alternativo->FormValue;
		$this->no_uc->CurrentValue = $this->no_uc->FormValue;
		$this->ds_uc->CurrentValue = $this->ds_uc->FormValue;
		$this->nu_stUc->CurrentValue = $this->nu_stUc->FormValue;
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
		$this->nu_uc->setDbValue($rs->fields('nu_uc'));
		$this->nu_sistema->setDbValue($rs->fields('nu_sistema'));
		$this->nu_modulo->setDbValue($rs->fields('nu_modulo'));
		$this->co_alternativo->setDbValue($rs->fields('co_alternativo'));
		$this->no_uc->setDbValue($rs->fields('no_uc'));
		$this->ds_uc->setDbValue($rs->fields('ds_uc'));
		$this->nu_stUc->setDbValue($rs->fields('nu_stUc'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_uc->DbValue = $row['nu_uc'];
		$this->nu_sistema->DbValue = $row['nu_sistema'];
		$this->nu_modulo->DbValue = $row['nu_modulo'];
		$this->co_alternativo->DbValue = $row['co_alternativo'];
		$this->no_uc->DbValue = $row['no_uc'];
		$this->ds_uc->DbValue = $row['ds_uc'];
		$this->nu_stUc->DbValue = $row['nu_stUc'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_uc
		// nu_sistema
		// nu_modulo
		// co_alternativo
		// no_uc
		// ds_uc
		// nu_stUc

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_sistema
			if (strval($this->nu_sistema->CurrentValue) <> "") {
				$sFilterWrk = "[nu_sistema]" . ew_SearchString("=", $this->nu_sistema->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_sistema], [co_alternativo] AS [DispFld], [no_sistema] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[sistema]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_sistema, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_sistema->ViewValue = $rswrk->fields('DispFld');
					$this->nu_sistema->ViewValue .= ew_ValueSeparator(1,$this->nu_sistema) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_sistema->ViewValue = $this->nu_sistema->CurrentValue;
				}
			} else {
				$this->nu_sistema->ViewValue = NULL;
			}
			$this->nu_sistema->ViewCustomAttributes = "";

			// nu_modulo
			if (strval($this->nu_modulo->CurrentValue) <> "") {
				$sFilterWrk = "[nu_modulo]" . ew_SearchString("=", $this->nu_modulo->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_modulo], [no_modulo] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[modulo]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_modulo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_modulo->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_modulo->ViewValue = $this->nu_modulo->CurrentValue;
				}
			} else {
				$this->nu_modulo->ViewValue = NULL;
			}
			$this->nu_modulo->ViewCustomAttributes = "";

			// co_alternativo
			$this->co_alternativo->ViewValue = $this->co_alternativo->CurrentValue;
			$this->co_alternativo->ViewCustomAttributes = "";

			// no_uc
			$this->no_uc->ViewValue = $this->no_uc->CurrentValue;
			$this->no_uc->ViewCustomAttributes = "";

			// ds_uc
			$this->ds_uc->ViewValue = $this->ds_uc->CurrentValue;
			$this->ds_uc->ViewCustomAttributes = "";

			// nu_stUc
			if (strval($this->nu_stUc->CurrentValue) <> "") {
				$sFilterWrk = "[nu_stUc]" . ew_SearchString("=", $this->nu_stUc->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_stUc], [no_stUc] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[stuc]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_stUc, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_stUc->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_stUc->ViewValue = $this->nu_stUc->CurrentValue;
				}
			} else {
				$this->nu_stUc->ViewValue = NULL;
			}
			$this->nu_stUc->ViewCustomAttributes = "";

			// nu_sistema
			$this->nu_sistema->LinkCustomAttributes = "";
			$this->nu_sistema->HrefValue = "";
			$this->nu_sistema->TooltipValue = "";

			// nu_modulo
			$this->nu_modulo->LinkCustomAttributes = "";
			$this->nu_modulo->HrefValue = "";
			$this->nu_modulo->TooltipValue = "";

			// co_alternativo
			$this->co_alternativo->LinkCustomAttributes = "";
			$this->co_alternativo->HrefValue = "";
			$this->co_alternativo->TooltipValue = "";

			// no_uc
			$this->no_uc->LinkCustomAttributes = "";
			$this->no_uc->HrefValue = "";
			$this->no_uc->TooltipValue = "";

			// ds_uc
			$this->ds_uc->LinkCustomAttributes = "";
			$this->ds_uc->HrefValue = "";
			$this->ds_uc->TooltipValue = "";

			// nu_stUc
			$this->nu_stUc->LinkCustomAttributes = "";
			$this->nu_stUc->HrefValue = "";
			$this->nu_stUc->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// nu_sistema
			$this->nu_sistema->EditCustomAttributes = "";
			if (strval($this->nu_sistema->CurrentValue) <> "") {
				$sFilterWrk = "[nu_sistema]" . ew_SearchString("=", $this->nu_sistema->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_sistema], [co_alternativo] AS [DispFld], [no_sistema] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[sistema]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_sistema, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_sistema->EditValue = $rswrk->fields('DispFld');
					$this->nu_sistema->EditValue .= ew_ValueSeparator(1,$this->nu_sistema) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_sistema->EditValue = $this->nu_sistema->CurrentValue;
				}
			} else {
				$this->nu_sistema->EditValue = NULL;
			}
			$this->nu_sistema->ViewCustomAttributes = "";

			// nu_modulo
			$this->nu_modulo->EditCustomAttributes = "";
			if (strval($this->nu_modulo->CurrentValue) <> "") {
				$sFilterWrk = "[nu_modulo]" . ew_SearchString("=", $this->nu_modulo->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_modulo], [no_modulo] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[modulo]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_modulo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_modulo->EditValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_modulo->EditValue = $this->nu_modulo->CurrentValue;
				}
			} else {
				$this->nu_modulo->EditValue = NULL;
			}
			$this->nu_modulo->ViewCustomAttributes = "";

			// co_alternativo
			$this->co_alternativo->EditCustomAttributes = "";
			$this->co_alternativo->EditValue = ew_HtmlEncode($this->co_alternativo->CurrentValue);
			$this->co_alternativo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->co_alternativo->FldCaption()));

			// no_uc
			$this->no_uc->EditCustomAttributes = "";
			$this->no_uc->EditValue = ew_HtmlEncode($this->no_uc->CurrentValue);
			$this->no_uc->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_uc->FldCaption()));

			// ds_uc
			$this->ds_uc->EditCustomAttributes = "";
			$this->ds_uc->EditValue = $this->ds_uc->CurrentValue;
			$this->ds_uc->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_uc->FldCaption()));

			// nu_stUc
			$this->nu_stUc->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_stUc], [no_stUc] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[stuc]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_stUc, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_stUc->EditValue = $arwrk;

			// Edit refer script
			// nu_sistema

			$this->nu_sistema->HrefValue = "";

			// nu_modulo
			$this->nu_modulo->HrefValue = "";

			// co_alternativo
			$this->co_alternativo->HrefValue = "";

			// no_uc
			$this->no_uc->HrefValue = "";

			// ds_uc
			$this->ds_uc->HrefValue = "";

			// nu_stUc
			$this->nu_stUc->HrefValue = "";
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
		if (!$this->no_uc->FldIsDetailKey && !is_null($this->no_uc->FormValue) && $this->no_uc->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_uc->FldCaption());
		}
		if (!$this->nu_stUc->FldIsDetailKey && !is_null($this->nu_stUc->FormValue) && $this->nu_stUc->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_stUc->FldCaption());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("uc_ator", $DetailTblVar) && $GLOBALS["uc_ator"]->DetailEdit) {
			if (!isset($GLOBALS["uc_ator_grid"])) $GLOBALS["uc_ator_grid"] = new cuc_ator_grid(); // get detail page object
			$GLOBALS["uc_ator_grid"]->ValidateGridForm();
		}
		if (in_array("uc_mensagem", $DetailTblVar) && $GLOBALS["uc_mensagem"]->DetailEdit) {
			if (!isset($GLOBALS["uc_mensagem_grid"])) $GLOBALS["uc_mensagem_grid"] = new cuc_mensagem_grid(); // get detail page object
			$GLOBALS["uc_mensagem_grid"]->ValidateGridForm();
		}
		if (in_array("uc_regranegocio", $DetailTblVar) && $GLOBALS["uc_regranegocio"]->DetailEdit) {
			if (!isset($GLOBALS["uc_regranegocio_grid"])) $GLOBALS["uc_regranegocio_grid"] = new cuc_regranegocio_grid(); // get detail page object
			$GLOBALS["uc_regranegocio_grid"]->ValidateGridForm();
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

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Begin transaction
			if ($this->getCurrentDetailTable() <> "")
				$conn->BeginTrans();

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// co_alternativo
			$this->co_alternativo->SetDbValueDef($rsnew, $this->co_alternativo->CurrentValue, "", $this->co_alternativo->ReadOnly);

			// no_uc
			$this->no_uc->SetDbValueDef($rsnew, $this->no_uc->CurrentValue, "", $this->no_uc->ReadOnly);

			// ds_uc
			$this->ds_uc->SetDbValueDef($rsnew, $this->ds_uc->CurrentValue, NULL, $this->ds_uc->ReadOnly);

			// nu_stUc
			$this->nu_stUc->SetDbValueDef($rsnew, $this->nu_stUc->CurrentValue, NULL, $this->nu_stUc->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = 'ew_ErrorFn';
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}

				// Update detail records
				if ($EditRow) {
					$DetailTblVar = explode(",", $this->getCurrentDetailTable());
					if (in_array("uc_ator", $DetailTblVar) && $GLOBALS["uc_ator"]->DetailEdit) {
						if (!isset($GLOBALS["uc_ator_grid"])) $GLOBALS["uc_ator_grid"] = new cuc_ator_grid(); // Get detail page object
						$EditRow = $GLOBALS["uc_ator_grid"]->GridUpdate();
					}
					if (in_array("uc_mensagem", $DetailTblVar) && $GLOBALS["uc_mensagem"]->DetailEdit) {
						if (!isset($GLOBALS["uc_mensagem_grid"])) $GLOBALS["uc_mensagem_grid"] = new cuc_mensagem_grid(); // Get detail page object
						$EditRow = $GLOBALS["uc_mensagem_grid"]->GridUpdate();
					}
					if (in_array("uc_regranegocio", $DetailTblVar) && $GLOBALS["uc_regranegocio"]->DetailEdit) {
						if (!isset($GLOBALS["uc_regranegocio_grid"])) $GLOBALS["uc_regranegocio_grid"] = new cuc_regranegocio_grid(); // Get detail page object
						$EditRow = $GLOBALS["uc_regranegocio_grid"]->GridUpdate();
					}
				}

				// Commit/Rollback transaction
				if ($this->getCurrentDetailTable() <> "") {
					if ($EditRow) {
						$conn->CommitTrans(); // Commit transaction
					} else {
						$conn->RollbackTrans(); // Rollback transaction
					}
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		if ($EditRow) {
			$this->WriteAuditTrailOnEdit($rsold, $rsnew);
		}
		$rs->Close();
		return $EditRow;
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
			if ($sMasterTblVar == "sistema") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_sistema"] <> "") {
					$GLOBALS["sistema"]->nu_sistema->setQueryStringValue($_GET["nu_sistema"]);
					$this->nu_sistema->setQueryStringValue($GLOBALS["sistema"]->nu_sistema->QueryStringValue);
					$this->nu_sistema->setSessionValue($this->nu_sistema->QueryStringValue);
					if (!is_numeric($GLOBALS["sistema"]->nu_sistema->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "sistema") {
				if ($this->nu_sistema->QueryStringValue == "") $this->nu_sistema->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); //  Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
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
			if (in_array("uc_ator", $DetailTblVar)) {
				if (!isset($GLOBALS["uc_ator_grid"]))
					$GLOBALS["uc_ator_grid"] = new cuc_ator_grid;
				if ($GLOBALS["uc_ator_grid"]->DetailEdit) {
					$GLOBALS["uc_ator_grid"]->CurrentMode = "edit";
					$GLOBALS["uc_ator_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["uc_ator_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["uc_ator_grid"]->setStartRecordNumber(1);
					$GLOBALS["uc_ator_grid"]->nu_uc->FldIsDetailKey = TRUE;
					$GLOBALS["uc_ator_grid"]->nu_uc->CurrentValue = $this->nu_uc->CurrentValue;
					$GLOBALS["uc_ator_grid"]->nu_uc->setSessionValue($GLOBALS["uc_ator_grid"]->nu_uc->CurrentValue);
				}
			}
			if (in_array("uc_mensagem", $DetailTblVar)) {
				if (!isset($GLOBALS["uc_mensagem_grid"]))
					$GLOBALS["uc_mensagem_grid"] = new cuc_mensagem_grid;
				if ($GLOBALS["uc_mensagem_grid"]->DetailEdit) {
					$GLOBALS["uc_mensagem_grid"]->CurrentMode = "edit";
					$GLOBALS["uc_mensagem_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["uc_mensagem_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["uc_mensagem_grid"]->setStartRecordNumber(1);
					$GLOBALS["uc_mensagem_grid"]->nu_uc->FldIsDetailKey = TRUE;
					$GLOBALS["uc_mensagem_grid"]->nu_uc->CurrentValue = $this->nu_uc->CurrentValue;
					$GLOBALS["uc_mensagem_grid"]->nu_uc->setSessionValue($GLOBALS["uc_mensagem_grid"]->nu_uc->CurrentValue);
				}
			}
			if (in_array("uc_regranegocio", $DetailTblVar)) {
				if (!isset($GLOBALS["uc_regranegocio_grid"]))
					$GLOBALS["uc_regranegocio_grid"] = new cuc_regranegocio_grid;
				if ($GLOBALS["uc_regranegocio_grid"]->DetailEdit) {
					$GLOBALS["uc_regranegocio_grid"]->CurrentMode = "edit";
					$GLOBALS["uc_regranegocio_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["uc_regranegocio_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["uc_regranegocio_grid"]->setStartRecordNumber(1);
					$GLOBALS["uc_regranegocio_grid"]->nu_uc->FldIsDetailKey = TRUE;
					$GLOBALS["uc_regranegocio_grid"]->nu_uc->CurrentValue = $this->nu_uc->CurrentValue;
					$GLOBALS["uc_regranegocio_grid"]->nu_uc->setSessionValue($GLOBALS["uc_regranegocio_grid"]->nu_uc->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "uclist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("edit");
		$Breadcrumb->Add("edit", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'uc';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		if (!$this->AuditTrailOnEdit) return;
		$table = 'uc';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['nu_uc'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
	  $usr = CurrentUserID();
		foreach (array_keys($rsnew) as $fldname) {
			if ($this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_DATE) { // DateTime field
					$modified = (ew_FormatDateTime($rsold[$fldname], 0) <> ew_FormatDateTime($rsnew[$fldname], 0));
				} else {
					$modified = !ew_CompareValue($rsold[$fldname], $rsnew[$fldname]);
				}
				if ($modified) {
					if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) { // Memo field
						if (EW_AUDIT_TRAIL_TO_DATABASE) {
							$oldvalue = $rsold[$fldname];
							$newvalue = $rsnew[$fldname];
						} else {
							$oldvalue = "[MEMO]";
							$newvalue = "[MEMO]";
						}
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) { // XML field
						$oldvalue = "[XML]";
						$newvalue = "[XML]";
					} else {
						$oldvalue = $rsold[$fldname];
						$newvalue = $rsnew[$fldname];
					}
					ew_WriteAuditTrail("log", $dt, $id, $usr, "U", $table, $fldname, $key, $oldvalue, $newvalue);
				}
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
if (!isset($uc_edit)) $uc_edit = new cuc_edit();

// Page init
$uc_edit->Page_Init();

// Page main
$uc_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$uc_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var uc_edit = new ew_Page("uc_edit");
uc_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = uc_edit.PageID; // For backward compatibility

// Form object
var fucedit = new ew_Form("fucedit");

// Validate form
fucedit.Validate = function() {
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
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($uc->co_alternativo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no_uc");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($uc->no_uc->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_stUc");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($uc->nu_stUc->FldCaption()) ?>");

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
fucedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fucedit.ValidateRequired = true;
<?php } else { ?>
fucedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fucedit.Lists["x_nu_sistema"] = {"LinkField":"x_nu_sistema","Ajax":true,"AutoFill":false,"DisplayFields":["x_co_alternativo","x_no_sistema","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fucedit.Lists["x_nu_modulo"] = {"LinkField":"x_nu_modulo","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_modulo","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fucedit.Lists["x_nu_stUc"] = {"LinkField":"x_nu_stUc","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_stUc","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $uc_edit->ShowPageHeader(); ?>
<?php
$uc_edit->ShowMessage();
?>
<form name="fucedit" id="fucedit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="uc">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_ucedit" class="table table-bordered table-striped">
<?php if ($uc->nu_sistema->Visible) { // nu_sistema ?>
	<tr id="r_nu_sistema">
		<td><span id="elh_uc_nu_sistema"><?php echo $uc->nu_sistema->FldCaption() ?></span></td>
		<td<?php echo $uc->nu_sistema->CellAttributes() ?>>
<span id="el_uc_nu_sistema" class="control-group">
<span<?php echo $uc->nu_sistema->ViewAttributes() ?>>
<?php echo $uc->nu_sistema->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_sistema" name="x_nu_sistema" id="x_nu_sistema" value="<?php echo ew_HtmlEncode($uc->nu_sistema->CurrentValue) ?>">
<?php echo $uc->nu_sistema->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($uc->nu_modulo->Visible) { // nu_modulo ?>
	<tr id="r_nu_modulo">
		<td><span id="elh_uc_nu_modulo"><?php echo $uc->nu_modulo->FldCaption() ?></span></td>
		<td<?php echo $uc->nu_modulo->CellAttributes() ?>>
<span id="el_uc_nu_modulo" class="control-group">
<span<?php echo $uc->nu_modulo->ViewAttributes() ?>>
<?php echo $uc->nu_modulo->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_modulo" name="x_nu_modulo" id="x_nu_modulo" value="<?php echo ew_HtmlEncode($uc->nu_modulo->CurrentValue) ?>">
<?php echo $uc->nu_modulo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($uc->co_alternativo->Visible) { // co_alternativo ?>
	<tr id="r_co_alternativo">
		<td><span id="elh_uc_co_alternativo"><?php echo $uc->co_alternativo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $uc->co_alternativo->CellAttributes() ?>>
<span id="el_uc_co_alternativo" class="control-group">
<input type="text" data-field="x_co_alternativo" name="x_co_alternativo" id="x_co_alternativo" size="30" maxlength="20" placeholder="<?php echo $uc->co_alternativo->PlaceHolder ?>" value="<?php echo $uc->co_alternativo->EditValue ?>"<?php echo $uc->co_alternativo->EditAttributes() ?>>
</span>
<?php echo $uc->co_alternativo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($uc->no_uc->Visible) { // no_uc ?>
	<tr id="r_no_uc">
		<td><span id="elh_uc_no_uc"><?php echo $uc->no_uc->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $uc->no_uc->CellAttributes() ?>>
<span id="el_uc_no_uc" class="control-group">
<input type="text" data-field="x_no_uc" name="x_no_uc" id="x_no_uc" size="30" maxlength="120" placeholder="<?php echo $uc->no_uc->PlaceHolder ?>" value="<?php echo $uc->no_uc->EditValue ?>"<?php echo $uc->no_uc->EditAttributes() ?>>
</span>
<?php echo $uc->no_uc->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($uc->ds_uc->Visible) { // ds_uc ?>
	<tr id="r_ds_uc">
		<td><span id="elh_uc_ds_uc"><?php echo $uc->ds_uc->FldCaption() ?></span></td>
		<td<?php echo $uc->ds_uc->CellAttributes() ?>>
<span id="el_uc_ds_uc" class="control-group">
<textarea data-field="x_ds_uc" name="x_ds_uc" id="x_ds_uc" cols="35" rows="4" placeholder="<?php echo $uc->ds_uc->PlaceHolder ?>"<?php echo $uc->ds_uc->EditAttributes() ?>><?php echo $uc->ds_uc->EditValue ?></textarea>
</span>
<?php echo $uc->ds_uc->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($uc->nu_stUc->Visible) { // nu_stUc ?>
	<tr id="r_nu_stUc">
		<td><span id="elh_uc_nu_stUc"><?php echo $uc->nu_stUc->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $uc->nu_stUc->CellAttributes() ?>>
<span id="el_uc_nu_stUc" class="control-group">
<select data-field="x_nu_stUc" id="x_nu_stUc" name="x_nu_stUc"<?php echo $uc->nu_stUc->EditAttributes() ?>>
<?php
if (is_array($uc->nu_stUc->EditValue)) {
	$arwrk = $uc->nu_stUc->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($uc->nu_stUc->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fucedit.Lists["x_nu_stUc"].Options = <?php echo (is_array($uc->nu_stUc->EditValue)) ? ew_ArrayToJson($uc->nu_stUc->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $uc->nu_stUc->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<input type="hidden" data-field="x_nu_uc" name="x_nu_uc" id="x_nu_uc" value="<?php echo ew_HtmlEncode($uc->nu_uc->CurrentValue) ?>">
<?php
	if (in_array("uc_ator", explode(",", $uc->getCurrentDetailTable())) && $uc_ator->DetailEdit) {
?>
<?php include_once "uc_atorgrid.php" ?>
<?php } ?>
<?php
	if (in_array("uc_mensagem", explode(",", $uc->getCurrentDetailTable())) && $uc_mensagem->DetailEdit) {
?>
<?php include_once "uc_mensagemgrid.php" ?>
<?php } ?>
<?php
	if (in_array("uc_regranegocio", explode(",", $uc->getCurrentDetailTable())) && $uc_regranegocio->DetailEdit) {
?>
<?php include_once "uc_regranegociogrid.php" ?>
<?php } ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fucedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$uc_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$uc_edit->Page_Terminate();
?>
