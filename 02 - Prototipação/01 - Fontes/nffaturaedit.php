<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "nffaturainfo.php" ?>
<?php include_once "faturainfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$nffatura_edit = NULL; // Initialize page object first

class cnffatura_edit extends cnffatura {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'nffatura';

	// Page object name
	var $PageObjName = 'nffatura_edit';

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

		// Table object (nffatura)
		if (!isset($GLOBALS["nffatura"])) {
			$GLOBALS["nffatura"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["nffatura"];
		}

		// Table object (fatura)
		if (!isset($GLOBALS['fatura'])) $GLOBALS['fatura'] = new cfatura();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'nffatura', TRUE);

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
			$this->Page_Terminate("nffaturalist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action
		$this->nu_nfFatura->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		if (@$_GET["nu_nfFatura"] <> "") {
			$this->nu_nfFatura->setQueryStringValue($_GET["nu_nfFatura"]);
		}

		// Set up master detail parameters
		$this->SetUpMasterParms();

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->nu_nfFatura->CurrentValue == "")
			$this->Page_Terminate("nffaturalist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("nffaturalist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "nffaturaview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to View page directly
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
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
		if (!$this->nu_nfFatura->FldIsDetailKey)
			$this->nu_nfFatura->setFormValue($objForm->GetValue("x_nu_nfFatura"));
		if (!$this->nu_fatura->FldIsDetailKey) {
			$this->nu_fatura->setFormValue($objForm->GetValue("x_nu_fatura"));
		}
		if (!$this->nu_notaFiscal->FldIsDetailKey) {
			$this->nu_notaFiscal->setFormValue($objForm->GetValue("x_nu_notaFiscal"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->nu_nfFatura->CurrentValue = $this->nu_nfFatura->FormValue;
		$this->nu_fatura->CurrentValue = $this->nu_fatura->FormValue;
		$this->nu_notaFiscal->CurrentValue = $this->nu_notaFiscal->FormValue;
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
		$this->nu_nfFatura->setDbValue($rs->fields('nu_nfFatura'));
		$this->nu_fatura->setDbValue($rs->fields('nu_fatura'));
		$this->nu_notaFiscal->setDbValue($rs->fields('nu_notaFiscal'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_nfFatura->DbValue = $row['nu_nfFatura'];
		$this->nu_fatura->DbValue = $row['nu_fatura'];
		$this->nu_notaFiscal->DbValue = $row['nu_notaFiscal'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->nu_notaFiscal->FormValue == $this->nu_notaFiscal->CurrentValue && is_numeric(ew_StrToFloat($this->nu_notaFiscal->CurrentValue)))
			$this->nu_notaFiscal->CurrentValue = ew_StrToFloat($this->nu_notaFiscal->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_nfFatura
		// nu_fatura
		// nu_notaFiscal

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_nfFatura
			$this->nu_nfFatura->ViewValue = $this->nu_nfFatura->CurrentValue;
			$this->nu_nfFatura->ViewCustomAttributes = "";

			// nu_fatura
			$this->nu_fatura->ViewValue = $this->nu_fatura->CurrentValue;
			$this->nu_fatura->ViewCustomAttributes = "";

			// nu_notaFiscal
			$this->nu_notaFiscal->ViewValue = $this->nu_notaFiscal->CurrentValue;
			$this->nu_notaFiscal->ViewCustomAttributes = "";

			// nu_nfFatura
			$this->nu_nfFatura->LinkCustomAttributes = "";
			$this->nu_nfFatura->HrefValue = "";
			$this->nu_nfFatura->TooltipValue = "";

			// nu_fatura
			$this->nu_fatura->LinkCustomAttributes = "";
			$this->nu_fatura->HrefValue = "";
			$this->nu_fatura->TooltipValue = "";

			// nu_notaFiscal
			$this->nu_notaFiscal->LinkCustomAttributes = "";
			$this->nu_notaFiscal->HrefValue = "";
			$this->nu_notaFiscal->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// nu_nfFatura
			$this->nu_nfFatura->EditCustomAttributes = "";
			$this->nu_nfFatura->EditValue = $this->nu_nfFatura->CurrentValue;
			$this->nu_nfFatura->ViewCustomAttributes = "";

			// nu_fatura
			$this->nu_fatura->EditCustomAttributes = "";
			if ($this->nu_fatura->getSessionValue() <> "") {
				$this->nu_fatura->CurrentValue = $this->nu_fatura->getSessionValue();
			$this->nu_fatura->ViewValue = $this->nu_fatura->CurrentValue;
			$this->nu_fatura->ViewCustomAttributes = "";
			} else {
			$this->nu_fatura->EditValue = ew_HtmlEncode($this->nu_fatura->CurrentValue);
			$this->nu_fatura->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_fatura->FldCaption()));
			}

			// nu_notaFiscal
			$this->nu_notaFiscal->EditCustomAttributes = "";
			$this->nu_notaFiscal->EditValue = ew_HtmlEncode($this->nu_notaFiscal->CurrentValue);
			$this->nu_notaFiscal->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_notaFiscal->FldCaption()));
			if (strval($this->nu_notaFiscal->EditValue) <> "" && is_numeric($this->nu_notaFiscal->EditValue)) $this->nu_notaFiscal->EditValue = ew_FormatNumber($this->nu_notaFiscal->EditValue, -2, -1, -2, 0);

			// Edit refer script
			// nu_nfFatura

			$this->nu_nfFatura->HrefValue = "";

			// nu_fatura
			$this->nu_fatura->HrefValue = "";

			// nu_notaFiscal
			$this->nu_notaFiscal->HrefValue = "";
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
		if (!ew_CheckInteger($this->nu_fatura->FormValue)) {
			ew_AddMessage($gsFormError, $this->nu_fatura->FldErrMsg());
		}
		if (!$this->nu_notaFiscal->FldIsDetailKey && !is_null($this->nu_notaFiscal->FormValue) && $this->nu_notaFiscal->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_notaFiscal->FldCaption());
		}
		if (!ew_CheckNumber($this->nu_notaFiscal->FormValue)) {
			ew_AddMessage($gsFormError, $this->nu_notaFiscal->FldErrMsg());
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

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// nu_fatura
			$this->nu_fatura->SetDbValueDef($rsnew, $this->nu_fatura->CurrentValue, NULL, $this->nu_fatura->ReadOnly);

			// nu_notaFiscal
			$this->nu_notaFiscal->SetDbValueDef($rsnew, $this->nu_notaFiscal->CurrentValue, 0, $this->nu_notaFiscal->ReadOnly);

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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "nffaturalist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("edit");
		$Breadcrumb->Add("edit", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'nffatura';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		if (!$this->AuditTrailOnEdit) return;
		$table = 'nffatura';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['nu_nfFatura'];

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
if (!isset($nffatura_edit)) $nffatura_edit = new cnffatura_edit();

// Page init
$nffatura_edit->Page_Init();

// Page main
$nffatura_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$nffatura_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var nffatura_edit = new ew_Page("nffatura_edit");
nffatura_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = nffatura_edit.PageID; // For backward compatibility

// Form object
var fnffaturaedit = new ew_Form("fnffaturaedit");

// Validate form
fnffaturaedit.Validate = function() {
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
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($nffatura->nu_fatura->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_fatura");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($nffatura->nu_fatura->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nu_notaFiscal");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($nffatura->nu_notaFiscal->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_notaFiscal");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($nffatura->nu_notaFiscal->FldErrMsg()) ?>");

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
fnffaturaedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fnffaturaedit.ValidateRequired = true;
<?php } else { ?>
fnffaturaedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $nffatura_edit->ShowPageHeader(); ?>
<?php
$nffatura_edit->ShowMessage();
?>
<form name="fnffaturaedit" id="fnffaturaedit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="nffatura">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_nffaturaedit" class="table table-bordered table-striped">
<?php if ($nffatura->nu_nfFatura->Visible) { // nu_nfFatura ?>
	<tr id="r_nu_nfFatura">
		<td><span id="elh_nffatura_nu_nfFatura"><?php echo $nffatura->nu_nfFatura->FldCaption() ?></span></td>
		<td<?php echo $nffatura->nu_nfFatura->CellAttributes() ?>>
<span id="el_nffatura_nu_nfFatura" class="control-group">
<span<?php echo $nffatura->nu_nfFatura->ViewAttributes() ?>>
<?php echo $nffatura->nu_nfFatura->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_nfFatura" name="x_nu_nfFatura" id="x_nu_nfFatura" value="<?php echo ew_HtmlEncode($nffatura->nu_nfFatura->CurrentValue) ?>">
<?php echo $nffatura->nu_nfFatura->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($nffatura->nu_fatura->Visible) { // nu_fatura ?>
	<tr id="r_nu_fatura">
		<td><span id="elh_nffatura_nu_fatura"><?php echo $nffatura->nu_fatura->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $nffatura->nu_fatura->CellAttributes() ?>>
<?php if ($nffatura->nu_fatura->getSessionValue() <> "") { ?>
<span<?php echo $nffatura->nu_fatura->ViewAttributes() ?>>
<?php echo $nffatura->nu_fatura->ViewValue ?></span>
<input type="hidden" id="x_nu_fatura" name="x_nu_fatura" value="<?php echo ew_HtmlEncode($nffatura->nu_fatura->CurrentValue) ?>">
<?php } else { ?>
<input type="text" data-field="x_nu_fatura" name="x_nu_fatura" id="x_nu_fatura" size="30" placeholder="<?php echo $nffatura->nu_fatura->PlaceHolder ?>" value="<?php echo $nffatura->nu_fatura->EditValue ?>"<?php echo $nffatura->nu_fatura->EditAttributes() ?>>
<?php } ?>
<?php echo $nffatura->nu_fatura->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($nffatura->nu_notaFiscal->Visible) { // nu_notaFiscal ?>
	<tr id="r_nu_notaFiscal">
		<td><span id="elh_nffatura_nu_notaFiscal"><?php echo $nffatura->nu_notaFiscal->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $nffatura->nu_notaFiscal->CellAttributes() ?>>
<span id="el_nffatura_nu_notaFiscal" class="control-group">
<input type="text" data-field="x_nu_notaFiscal" name="x_nu_notaFiscal" id="x_nu_notaFiscal" size="30" placeholder="<?php echo $nffatura->nu_notaFiscal->PlaceHolder ?>" value="<?php echo $nffatura->nu_notaFiscal->EditValue ?>"<?php echo $nffatura->nu_notaFiscal->EditAttributes() ?>>
</span>
<?php echo $nffatura->nu_notaFiscal->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fnffaturaedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$nffatura_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$nffatura_edit->Page_Terminate();
?>
