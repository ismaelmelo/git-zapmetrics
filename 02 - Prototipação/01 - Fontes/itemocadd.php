<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "itemocinfo.php" ?>
<?php include_once "ocinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$itemoc_add = NULL; // Initialize page object first

class citemoc_add extends citemoc {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'itemoc';

	// Page object name
	var $PageObjName = 'itemoc_add';

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

		// Table object (itemoc)
		if (!isset($GLOBALS["itemoc"])) {
			$GLOBALS["itemoc"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["itemoc"];
		}

		// Table object (oc)
		if (!isset($GLOBALS['oc'])) $GLOBALS['oc'] = new coc();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'itemoc', TRUE);

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
			$this->Page_Terminate("itemoclist.php");
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
			if (@$_GET["nu_itemOc"] != "") {
				$this->nu_itemOc->setQueryStringValue($_GET["nu_itemOc"]);
				$this->setKey("nu_itemOc", $this->nu_itemOc->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_itemOc", ""); // Clear key
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
					$this->Page_Terminate("itemoclist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "itemocview.php")
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
		$this->nu_oc->CurrentValue = NULL;
		$this->nu_oc->OldValue = $this->nu_oc->CurrentValue;
		$this->nu_tpItem->CurrentValue = NULL;
		$this->nu_tpItem->OldValue = $this->nu_tpItem->CurrentValue;
		$this->no_itemOc->CurrentValue = NULL;
		$this->no_itemOc->OldValue = $this->no_itemOc->CurrentValue;
		$this->ds_itemOc->CurrentValue = NULL;
		$this->ds_itemOc->OldValue = $this->ds_itemOc->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_oc->FldIsDetailKey) {
			$this->nu_oc->setFormValue($objForm->GetValue("x_nu_oc"));
		}
		if (!$this->nu_tpItem->FldIsDetailKey) {
			$this->nu_tpItem->setFormValue($objForm->GetValue("x_nu_tpItem"));
		}
		if (!$this->no_itemOc->FldIsDetailKey) {
			$this->no_itemOc->setFormValue($objForm->GetValue("x_no_itemOc"));
		}
		if (!$this->ds_itemOc->FldIsDetailKey) {
			$this->ds_itemOc->setFormValue($objForm->GetValue("x_ds_itemOc"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->nu_oc->CurrentValue = $this->nu_oc->FormValue;
		$this->nu_tpItem->CurrentValue = $this->nu_tpItem->FormValue;
		$this->no_itemOc->CurrentValue = $this->no_itemOc->FormValue;
		$this->ds_itemOc->CurrentValue = $this->ds_itemOc->FormValue;
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
		$this->nu_itemOc->setDbValue($rs->fields('nu_itemOc'));
		$this->nu_oc->setDbValue($rs->fields('nu_oc'));
		$this->nu_tpItem->setDbValue($rs->fields('nu_tpItem'));
		$this->no_itemOc->setDbValue($rs->fields('no_itemOc'));
		$this->ds_itemOc->setDbValue($rs->fields('ds_itemOc'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_itemOc->DbValue = $row['nu_itemOc'];
		$this->nu_oc->DbValue = $row['nu_oc'];
		$this->nu_tpItem->DbValue = $row['nu_tpItem'];
		$this->no_itemOc->DbValue = $row['no_itemOc'];
		$this->ds_itemOc->DbValue = $row['ds_itemOc'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_itemOc")) <> "")
			$this->nu_itemOc->CurrentValue = $this->getKey("nu_itemOc"); // nu_itemOc
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
		// nu_itemOc
		// nu_oc
		// nu_tpItem
		// no_itemOc
		// ds_itemOc

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_itemOc
			$this->nu_itemOc->ViewValue = $this->nu_itemOc->CurrentValue;
			$this->nu_itemOc->ViewCustomAttributes = "";

			// nu_oc
			$this->nu_oc->ViewValue = $this->nu_oc->CurrentValue;
			$this->nu_oc->ViewCustomAttributes = "";

			// nu_tpItem
			if (strval($this->nu_tpItem->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpItem]" . ew_SearchString("=", $this->nu_tpItem->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpItem], [no_tpItem] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpitem]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpItem, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpItem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpItem->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpItem->ViewValue = $this->nu_tpItem->CurrentValue;
				}
			} else {
				$this->nu_tpItem->ViewValue = NULL;
			}
			$this->nu_tpItem->ViewCustomAttributes = "";

			// no_itemOc
			$this->no_itemOc->ViewValue = $this->no_itemOc->CurrentValue;
			$this->no_itemOc->ViewCustomAttributes = "";

			// ds_itemOc
			$this->ds_itemOc->ViewValue = $this->ds_itemOc->CurrentValue;
			$this->ds_itemOc->ViewCustomAttributes = "";

			// nu_oc
			$this->nu_oc->LinkCustomAttributes = "";
			$this->nu_oc->HrefValue = "";
			$this->nu_oc->TooltipValue = "";

			// nu_tpItem
			$this->nu_tpItem->LinkCustomAttributes = "";
			$this->nu_tpItem->HrefValue = "";
			$this->nu_tpItem->TooltipValue = "";

			// no_itemOc
			$this->no_itemOc->LinkCustomAttributes = "";
			$this->no_itemOc->HrefValue = "";
			$this->no_itemOc->TooltipValue = "";

			// ds_itemOc
			$this->ds_itemOc->LinkCustomAttributes = "";
			$this->ds_itemOc->HrefValue = "";
			$this->ds_itemOc->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nu_oc
			$this->nu_oc->EditCustomAttributes = "";
			if ($this->nu_oc->getSessionValue() <> "") {
				$this->nu_oc->CurrentValue = $this->nu_oc->getSessionValue();
			$this->nu_oc->ViewValue = $this->nu_oc->CurrentValue;
			$this->nu_oc->ViewCustomAttributes = "";
			} else {
			$this->nu_oc->EditValue = ew_HtmlEncode($this->nu_oc->CurrentValue);
			$this->nu_oc->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_oc->FldCaption()));
			}

			// nu_tpItem
			$this->nu_tpItem->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_tpItem], [no_tpItem] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[tpitem]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpItem, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpItem] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_tpItem->EditValue = $arwrk;

			// no_itemOc
			$this->no_itemOc->EditCustomAttributes = "";
			$this->no_itemOc->EditValue = ew_HtmlEncode($this->no_itemOc->CurrentValue);
			$this->no_itemOc->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_itemOc->FldCaption()));

			// ds_itemOc
			$this->ds_itemOc->EditCustomAttributes = "";
			$this->ds_itemOc->EditValue = $this->ds_itemOc->CurrentValue;
			$this->ds_itemOc->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_itemOc->FldCaption()));

			// Edit refer script
			// nu_oc

			$this->nu_oc->HrefValue = "";

			// nu_tpItem
			$this->nu_tpItem->HrefValue = "";

			// no_itemOc
			$this->no_itemOc->HrefValue = "";

			// ds_itemOc
			$this->ds_itemOc->HrefValue = "";
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
		if (!$this->nu_oc->FldIsDetailKey && !is_null($this->nu_oc->FormValue) && $this->nu_oc->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_oc->FldCaption());
		}
		if (!ew_CheckInteger($this->nu_oc->FormValue)) {
			ew_AddMessage($gsFormError, $this->nu_oc->FldErrMsg());
		}
		if (!$this->nu_tpItem->FldIsDetailKey && !is_null($this->nu_tpItem->FormValue) && $this->nu_tpItem->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_tpItem->FldCaption());
		}
		if (!$this->no_itemOc->FldIsDetailKey && !is_null($this->no_itemOc->FormValue) && $this->no_itemOc->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_itemOc->FldCaption());
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

		// nu_oc
		$this->nu_oc->SetDbValueDef($rsnew, $this->nu_oc->CurrentValue, NULL, FALSE);

		// nu_tpItem
		$this->nu_tpItem->SetDbValueDef($rsnew, $this->nu_tpItem->CurrentValue, NULL, FALSE);

		// no_itemOc
		$this->no_itemOc->SetDbValueDef($rsnew, $this->no_itemOc->CurrentValue, "", FALSE);

		// ds_itemOc
		$this->ds_itemOc->SetDbValueDef($rsnew, $this->ds_itemOc->CurrentValue, NULL, FALSE);

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
			$this->nu_itemOc->setDbValue($conn->Insert_ID());
			$rsnew['nu_itemOc'] = $this->nu_itemOc->DbValue;
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
			if ($sMasterTblVar == "oc") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_oc"] <> "") {
					$GLOBALS["oc"]->nu_oc->setQueryStringValue($_GET["nu_oc"]);
					$this->nu_oc->setQueryStringValue($GLOBALS["oc"]->nu_oc->QueryStringValue);
					$this->nu_oc->setSessionValue($this->nu_oc->QueryStringValue);
					if (!is_numeric($GLOBALS["oc"]->nu_oc->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "oc") {
				if ($this->nu_oc->QueryStringValue == "") $this->nu_oc->setSessionValue("");
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "itemoclist.php", $this->TableVar);
		$PageCaption = ($this->CurrentAction == "C") ? $Language->Phrase("Copy") : $Language->Phrase("Add");
		$Breadcrumb->Add("add", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'itemoc';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'itemoc';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_itemOc'];

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
if (!isset($itemoc_add)) $itemoc_add = new citemoc_add();

// Page init
$itemoc_add->Page_Init();

// Page main
$itemoc_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$itemoc_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var itemoc_add = new ew_Page("itemoc_add");
itemoc_add.PageID = "add"; // Page ID
var EW_PAGE_ID = itemoc_add.PageID; // For backward compatibility

// Form object
var fitemocadd = new ew_Form("fitemocadd");

// Validate form
fitemocadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_oc");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($itemoc->nu_oc->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_oc");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($itemoc->nu_oc->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nu_tpItem");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($itemoc->nu_tpItem->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no_itemOc");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($itemoc->no_itemOc->FldCaption()) ?>");

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
fitemocadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fitemocadd.ValidateRequired = true;
<?php } else { ?>
fitemocadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fitemocadd.Lists["x_nu_tpItem"] = {"LinkField":"x_nu_tpItem","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpItem","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $itemoc_add->ShowPageHeader(); ?>
<?php
$itemoc_add->ShowMessage();
?>
<form name="fitemocadd" id="fitemocadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="itemoc">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_itemocadd" class="table table-bordered table-striped">
<?php if ($itemoc->nu_oc->Visible) { // nu_oc ?>
	<tr id="r_nu_oc">
		<td><span id="elh_itemoc_nu_oc"><?php echo $itemoc->nu_oc->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $itemoc->nu_oc->CellAttributes() ?>>
<?php if ($itemoc->nu_oc->getSessionValue() <> "") { ?>
<span<?php echo $itemoc->nu_oc->ViewAttributes() ?>>
<?php echo $itemoc->nu_oc->ViewValue ?></span>
<input type="hidden" id="x_nu_oc" name="x_nu_oc" value="<?php echo ew_HtmlEncode($itemoc->nu_oc->CurrentValue) ?>">
<?php } else { ?>
<input type="text" data-field="x_nu_oc" name="x_nu_oc" id="x_nu_oc" size="30" placeholder="<?php echo $itemoc->nu_oc->PlaceHolder ?>" value="<?php echo $itemoc->nu_oc->EditValue ?>"<?php echo $itemoc->nu_oc->EditAttributes() ?>>
<?php } ?>
<?php echo $itemoc->nu_oc->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($itemoc->nu_tpItem->Visible) { // nu_tpItem ?>
	<tr id="r_nu_tpItem">
		<td><span id="elh_itemoc_nu_tpItem"><?php echo $itemoc->nu_tpItem->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $itemoc->nu_tpItem->CellAttributes() ?>>
<span id="el_itemoc_nu_tpItem" class="control-group">
<select data-field="x_nu_tpItem" id="x_nu_tpItem" name="x_nu_tpItem"<?php echo $itemoc->nu_tpItem->EditAttributes() ?>>
<?php
if (is_array($itemoc->nu_tpItem->EditValue)) {
	$arwrk = $itemoc->nu_tpItem->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($itemoc->nu_tpItem->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fitemocadd.Lists["x_nu_tpItem"].Options = <?php echo (is_array($itemoc->nu_tpItem->EditValue)) ? ew_ArrayToJson($itemoc->nu_tpItem->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $itemoc->nu_tpItem->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($itemoc->no_itemOc->Visible) { // no_itemOc ?>
	<tr id="r_no_itemOc">
		<td><span id="elh_itemoc_no_itemOc"><?php echo $itemoc->no_itemOc->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $itemoc->no_itemOc->CellAttributes() ?>>
<span id="el_itemoc_no_itemOc" class="control-group">
<input type="text" data-field="x_no_itemOc" name="x_no_itemOc" id="x_no_itemOc" size="30" maxlength="100" placeholder="<?php echo $itemoc->no_itemOc->PlaceHolder ?>" value="<?php echo $itemoc->no_itemOc->EditValue ?>"<?php echo $itemoc->no_itemOc->EditAttributes() ?>>
</span>
<?php echo $itemoc->no_itemOc->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($itemoc->ds_itemOc->Visible) { // ds_itemOc ?>
	<tr id="r_ds_itemOc">
		<td><span id="elh_itemoc_ds_itemOc"><?php echo $itemoc->ds_itemOc->FldCaption() ?></span></td>
		<td<?php echo $itemoc->ds_itemOc->CellAttributes() ?>>
<span id="el_itemoc_ds_itemOc" class="control-group">
<textarea data-field="x_ds_itemOc" name="x_ds_itemOc" id="x_ds_itemOc" cols="35" rows="4" placeholder="<?php echo $itemoc->ds_itemOc->PlaceHolder ?>"<?php echo $itemoc->ds_itemOc->EditAttributes() ?>><?php echo $itemoc->ds_itemOc->EditValue ?></textarea>
</span>
<?php echo $itemoc->ds_itemOc->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fitemocadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$itemoc_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$itemoc_add->Page_Terminate();
?>
