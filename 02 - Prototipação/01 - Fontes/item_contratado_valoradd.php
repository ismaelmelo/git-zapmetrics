<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "item_contratado_valorinfo.php" ?>
<?php include_once "item_contratadoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$Item_contratado_valor_add = NULL; // Initialize page object first

class cItem_contratado_valor_add extends cItem_contratado_valor {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'Item_contratado_valor';

	// Page object name
	var $PageObjName = 'Item_contratado_valor_add';

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

		// Table object (Item_contratado_valor)
		if (!isset($GLOBALS["Item_contratado_valor"])) {
			$GLOBALS["Item_contratado_valor"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["Item_contratado_valor"];
		}

		// Table object (item_contratado)
		if (!isset($GLOBALS['item_contratado'])) $GLOBALS['item_contratado'] = new citem_contratado();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'Item_contratado_valor', TRUE);

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
			$this->Page_Terminate("item_contratado_valorlist.php");
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
			if (@$_GET["nu_valor"] != "") {
				$this->nu_valor->setQueryStringValue($_GET["nu_valor"]);
				$this->setKey("nu_valor", $this->nu_valor->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_valor", ""); // Clear key
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
					$this->Page_Terminate("item_contratado_valorlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "item_contratado_valorview.php")
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
		$this->nu_itemContratado->CurrentValue = NULL;
		$this->nu_itemContratado->OldValue = $this->nu_itemContratado->CurrentValue;
		$this->vr_item->CurrentValue = NULL;
		$this->vr_item->OldValue = $this->vr_item->CurrentValue;
		$this->dt_valor->CurrentValue = NULL;
		$this->dt_valor->OldValue = $this->dt_valor->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_itemContratado->FldIsDetailKey) {
			$this->nu_itemContratado->setFormValue($objForm->GetValue("x_nu_itemContratado"));
		}
		if (!$this->vr_item->FldIsDetailKey) {
			$this->vr_item->setFormValue($objForm->GetValue("x_vr_item"));
		}
		if (!$this->dt_valor->FldIsDetailKey) {
			$this->dt_valor->setFormValue($objForm->GetValue("x_dt_valor"));
			$this->dt_valor->CurrentValue = ew_UnFormatDateTime($this->dt_valor->CurrentValue, 7);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->nu_itemContratado->CurrentValue = $this->nu_itemContratado->FormValue;
		$this->vr_item->CurrentValue = $this->vr_item->FormValue;
		$this->dt_valor->CurrentValue = $this->dt_valor->FormValue;
		$this->dt_valor->CurrentValue = ew_UnFormatDateTime($this->dt_valor->CurrentValue, 7);
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
		$this->nu_valor->setDbValue($rs->fields('nu_valor'));
		$this->nu_itemContratado->setDbValue($rs->fields('nu_itemContratado'));
		$this->vr_item->setDbValue($rs->fields('vr_item'));
		$this->dt_valor->setDbValue($rs->fields('dt_valor'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_valor->DbValue = $row['nu_valor'];
		$this->nu_itemContratado->DbValue = $row['nu_itemContratado'];
		$this->vr_item->DbValue = $row['vr_item'];
		$this->dt_valor->DbValue = $row['dt_valor'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_valor")) <> "")
			$this->nu_valor->CurrentValue = $this->getKey("nu_valor"); // nu_valor
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

		if ($this->vr_item->FormValue == $this->vr_item->CurrentValue && is_numeric(ew_StrToFloat($this->vr_item->CurrentValue)))
			$this->vr_item->CurrentValue = ew_StrToFloat($this->vr_item->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_valor
		// nu_itemContratado
		// vr_item
		// dt_valor

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_itemContratado
			$this->nu_itemContratado->ViewValue = $this->nu_itemContratado->CurrentValue;
			$this->nu_itemContratado->ViewCustomAttributes = "";

			// vr_item
			$this->vr_item->ViewValue = $this->vr_item->CurrentValue;
			$this->vr_item->ViewCustomAttributes = "";

			// dt_valor
			$this->dt_valor->ViewValue = $this->dt_valor->CurrentValue;
			$this->dt_valor->ViewValue = ew_FormatDateTime($this->dt_valor->ViewValue, 7);
			$this->dt_valor->ViewCustomAttributes = "";

			// nu_itemContratado
			$this->nu_itemContratado->LinkCustomAttributes = "";
			$this->nu_itemContratado->HrefValue = "";
			$this->nu_itemContratado->TooltipValue = "";

			// vr_item
			$this->vr_item->LinkCustomAttributes = "";
			$this->vr_item->HrefValue = "";
			$this->vr_item->TooltipValue = "";

			// dt_valor
			$this->dt_valor->LinkCustomAttributes = "";
			$this->dt_valor->HrefValue = "";
			$this->dt_valor->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nu_itemContratado
			$this->nu_itemContratado->EditCustomAttributes = "";
			if ($this->nu_itemContratado->getSessionValue() <> "") {
				$this->nu_itemContratado->CurrentValue = $this->nu_itemContratado->getSessionValue();
			$this->nu_itemContratado->ViewValue = $this->nu_itemContratado->CurrentValue;
			$this->nu_itemContratado->ViewCustomAttributes = "";
			} else {
			$this->nu_itemContratado->EditValue = ew_HtmlEncode($this->nu_itemContratado->CurrentValue);
			$this->nu_itemContratado->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_itemContratado->FldCaption()));
			}

			// vr_item
			$this->vr_item->EditCustomAttributes = "";
			$this->vr_item->EditValue = ew_HtmlEncode($this->vr_item->CurrentValue);
			$this->vr_item->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_item->FldCaption()));
			if (strval($this->vr_item->EditValue) <> "" && is_numeric($this->vr_item->EditValue)) $this->vr_item->EditValue = ew_FormatNumber($this->vr_item->EditValue, -2, -1, -2, 0);

			// dt_valor
			$this->dt_valor->EditCustomAttributes = "";
			$this->dt_valor->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->dt_valor->CurrentValue, 7));
			$this->dt_valor->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dt_valor->FldCaption()));

			// Edit refer script
			// nu_itemContratado

			$this->nu_itemContratado->HrefValue = "";

			// vr_item
			$this->vr_item->HrefValue = "";

			// dt_valor
			$this->dt_valor->HrefValue = "";
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
		if (!$this->nu_itemContratado->FldIsDetailKey && !is_null($this->nu_itemContratado->FormValue) && $this->nu_itemContratado->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_itemContratado->FldCaption());
		}
		if (!ew_CheckInteger($this->nu_itemContratado->FormValue)) {
			ew_AddMessage($gsFormError, $this->nu_itemContratado->FldErrMsg());
		}
		if (!$this->vr_item->FldIsDetailKey && !is_null($this->vr_item->FormValue) && $this->vr_item->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->vr_item->FldCaption());
		}
		if (!ew_CheckNumber($this->vr_item->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_item->FldErrMsg());
		}
		if (!$this->dt_valor->FldIsDetailKey && !is_null($this->dt_valor->FormValue) && $this->dt_valor->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->dt_valor->FldCaption());
		}
		if (!ew_CheckEuroDate($this->dt_valor->FormValue)) {
			ew_AddMessage($gsFormError, $this->dt_valor->FldErrMsg());
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

		// Check referential integrity for master table 'item_contratado'
		$bValidMasterRecord = TRUE;
		$sMasterFilter = $this->SqlMasterFilter_item_contratado();
		if (strval($this->nu_itemContratado->CurrentValue) <> "") {
			$sMasterFilter = str_replace("@nu_itemContratado@", ew_AdjustSql($this->nu_itemContratado->CurrentValue), $sMasterFilter);
		} else {
			$bValidMasterRecord = FALSE;
		}
		if ($bValidMasterRecord) {
			$rsmaster = $GLOBALS["item_contratado"]->LoadRs($sMasterFilter);
			$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
			$rsmaster->Close();
		}
		if (!$bValidMasterRecord) {
			$sRelatedRecordMsg = str_replace("%t", "item_contratado", $Language->Phrase("RelatedRecordRequired"));
			$this->setFailureMessage($sRelatedRecordMsg);
			return FALSE;
		}

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// nu_itemContratado
		$this->nu_itemContratado->SetDbValueDef($rsnew, $this->nu_itemContratado->CurrentValue, NULL, FALSE);

		// vr_item
		$this->vr_item->SetDbValueDef($rsnew, $this->vr_item->CurrentValue, 0, FALSE);

		// dt_valor
		$this->dt_valor->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->dt_valor->CurrentValue, 7), ew_CurrentDate(), FALSE);

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
			$this->nu_valor->setDbValue($conn->Insert_ID());
			$rsnew['nu_valor'] = $this->nu_valor->DbValue;
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
			if ($sMasterTblVar == "item_contratado") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_itemContratado"] <> "") {
					$GLOBALS["item_contratado"]->nu_itemContratado->setQueryStringValue($_GET["nu_itemContratado"]);
					$this->nu_itemContratado->setQueryStringValue($GLOBALS["item_contratado"]->nu_itemContratado->QueryStringValue);
					$this->nu_itemContratado->setSessionValue($this->nu_itemContratado->QueryStringValue);
					if (!is_numeric($GLOBALS["item_contratado"]->nu_itemContratado->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "item_contratado") {
				if ($this->nu_itemContratado->QueryStringValue == "") $this->nu_itemContratado->setSessionValue("");
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "item_contratado_valorlist.php", $this->TableVar);
		$PageCaption = ($this->CurrentAction == "C") ? $Language->Phrase("Copy") : $Language->Phrase("Add");
		$Breadcrumb->Add("add", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'Item_contratado_valor';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'Item_contratado_valor';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_valor'];

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
if (!isset($Item_contratado_valor_add)) $Item_contratado_valor_add = new cItem_contratado_valor_add();

// Page init
$Item_contratado_valor_add->Page_Init();

// Page main
$Item_contratado_valor_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$Item_contratado_valor_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var Item_contratado_valor_add = new ew_Page("Item_contratado_valor_add");
Item_contratado_valor_add.PageID = "add"; // Page ID
var EW_PAGE_ID = Item_contratado_valor_add.PageID; // For backward compatibility

// Form object
var fItem_contratado_valoradd = new ew_Form("fItem_contratado_valoradd");

// Validate form
fItem_contratado_valoradd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_itemContratado");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($Item_contratado_valor->nu_itemContratado->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_itemContratado");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($Item_contratado_valor->nu_itemContratado->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vr_item");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($Item_contratado_valor->vr_item->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_item");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($Item_contratado_valor->vr_item->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_dt_valor");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($Item_contratado_valor->dt_valor->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dt_valor");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($Item_contratado_valor->dt_valor->FldErrMsg()) ?>");

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
fItem_contratado_valoradd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fItem_contratado_valoradd.ValidateRequired = true;
<?php } else { ?>
fItem_contratado_valoradd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $Item_contratado_valor_add->ShowPageHeader(); ?>
<?php
$Item_contratado_valor_add->ShowMessage();
?>
<form name="fItem_contratado_valoradd" id="fItem_contratado_valoradd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="Item_contratado_valor">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_Item_contratado_valoradd" class="table table-bordered table-striped">
<?php if ($Item_contratado_valor->nu_itemContratado->Visible) { // nu_itemContratado ?>
	<tr id="r_nu_itemContratado">
		<td><span id="elh_Item_contratado_valor_nu_itemContratado"><?php echo $Item_contratado_valor->nu_itemContratado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $Item_contratado_valor->nu_itemContratado->CellAttributes() ?>>
<?php if ($Item_contratado_valor->nu_itemContratado->getSessionValue() <> "") { ?>
<span<?php echo $Item_contratado_valor->nu_itemContratado->ViewAttributes() ?>>
<?php echo $Item_contratado_valor->nu_itemContratado->ViewValue ?></span>
<input type="hidden" id="x_nu_itemContratado" name="x_nu_itemContratado" value="<?php echo ew_HtmlEncode($Item_contratado_valor->nu_itemContratado->CurrentValue) ?>">
<?php } else { ?>
<input type="text" data-field="x_nu_itemContratado" name="x_nu_itemContratado" id="x_nu_itemContratado" size="30" placeholder="<?php echo $Item_contratado_valor->nu_itemContratado->PlaceHolder ?>" value="<?php echo $Item_contratado_valor->nu_itemContratado->EditValue ?>"<?php echo $Item_contratado_valor->nu_itemContratado->EditAttributes() ?>>
<?php } ?>
<?php echo $Item_contratado_valor->nu_itemContratado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($Item_contratado_valor->vr_item->Visible) { // vr_item ?>
	<tr id="r_vr_item">
		<td><span id="elh_Item_contratado_valor_vr_item"><?php echo $Item_contratado_valor->vr_item->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $Item_contratado_valor->vr_item->CellAttributes() ?>>
<span id="el_Item_contratado_valor_vr_item" class="control-group">
<input type="text" data-field="x_vr_item" name="x_vr_item" id="x_vr_item" size="30" placeholder="<?php echo $Item_contratado_valor->vr_item->PlaceHolder ?>" value="<?php echo $Item_contratado_valor->vr_item->EditValue ?>"<?php echo $Item_contratado_valor->vr_item->EditAttributes() ?>>
</span>
<?php echo $Item_contratado_valor->vr_item->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($Item_contratado_valor->dt_valor->Visible) { // dt_valor ?>
	<tr id="r_dt_valor">
		<td><span id="elh_Item_contratado_valor_dt_valor"><?php echo $Item_contratado_valor->dt_valor->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $Item_contratado_valor->dt_valor->CellAttributes() ?>>
<span id="el_Item_contratado_valor_dt_valor" class="control-group">
<input type="text" data-field="x_dt_valor" name="x_dt_valor" id="x_dt_valor" placeholder="<?php echo $Item_contratado_valor->dt_valor->PlaceHolder ?>" value="<?php echo $Item_contratado_valor->dt_valor->EditValue ?>"<?php echo $Item_contratado_valor->dt_valor->EditAttributes() ?>>
<?php if (!$Item_contratado_valor->dt_valor->ReadOnly && !$Item_contratado_valor->dt_valor->Disabled && @$Item_contratado_valor->dt_valor->EditAttrs["readonly"] == "" && @$Item_contratado_valor->dt_valor->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_dt_valor" name="cal_x_dt_valor" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x_dt_valor" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fItem_contratado_valoradd", "x_dt_valor", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $Item_contratado_valor->dt_valor->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fItem_contratado_valoradd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$Item_contratado_valor_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$Item_contratado_valor_add->Page_Terminate();
?>
