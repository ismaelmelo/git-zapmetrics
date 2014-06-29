<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "metodologiainfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "roteirogridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$metodologia_add = NULL; // Initialize page object first

class cmetodologia_add extends cmetodologia {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'metodologia';

	// Page object name
	var $PageObjName = 'metodologia_add';

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

		// Table object (metodologia)
		if (!isset($GLOBALS["metodologia"])) {
			$GLOBALS["metodologia"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["metodologia"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'metodologia', TRUE);

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
			$this->Page_Terminate("metodologialist.php");
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
			if (@$_GET["nu_metodologia"] != "") {
				$this->nu_metodologia->setQueryStringValue($_GET["nu_metodologia"]);
				$this->setKey("nu_metodologia", $this->nu_metodologia->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_metodologia", ""); // Clear key
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
					$this->Page_Terminate("metodologialist.php"); // No matching record, return to list
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
					if (ew_GetPageName($sReturnUrl) == "metodologiaview.php")
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
		$this->no_metodologia->CurrentValue = NULL;
		$this->no_metodologia->OldValue = $this->no_metodologia->CurrentValue;
		$this->ds_metodologia->CurrentValue = NULL;
		$this->ds_metodologia->OldValue = $this->ds_metodologia->CurrentValue;
		$this->ic_tpModeloDev->CurrentValue = NULL;
		$this->ic_tpModeloDev->OldValue = $this->ic_tpModeloDev->CurrentValue;
		$this->ic_ativo->CurrentValue = "S";
		$this->nu_ordem->CurrentValue = NULL;
		$this->nu_ordem->OldValue = $this->nu_ordem->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->no_metodologia->FldIsDetailKey) {
			$this->no_metodologia->setFormValue($objForm->GetValue("x_no_metodologia"));
		}
		if (!$this->ds_metodologia->FldIsDetailKey) {
			$this->ds_metodologia->setFormValue($objForm->GetValue("x_ds_metodologia"));
		}
		if (!$this->ic_tpModeloDev->FldIsDetailKey) {
			$this->ic_tpModeloDev->setFormValue($objForm->GetValue("x_ic_tpModeloDev"));
		}
		if (!$this->ic_ativo->FldIsDetailKey) {
			$this->ic_ativo->setFormValue($objForm->GetValue("x_ic_ativo"));
		}
		if (!$this->nu_ordem->FldIsDetailKey) {
			$this->nu_ordem->setFormValue($objForm->GetValue("x_nu_ordem"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->no_metodologia->CurrentValue = $this->no_metodologia->FormValue;
		$this->ds_metodologia->CurrentValue = $this->ds_metodologia->FormValue;
		$this->ic_tpModeloDev->CurrentValue = $this->ic_tpModeloDev->FormValue;
		$this->ic_ativo->CurrentValue = $this->ic_ativo->FormValue;
		$this->nu_ordem->CurrentValue = $this->nu_ordem->FormValue;
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
		$this->nu_metodologia->setDbValue($rs->fields('nu_metodologia'));
		$this->no_metodologia->setDbValue($rs->fields('no_metodologia'));
		$this->ds_metodologia->setDbValue($rs->fields('ds_metodologia'));
		$this->ic_tpModeloDev->setDbValue($rs->fields('ic_tpModeloDev'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
		$this->nu_ordem->setDbValue($rs->fields('nu_ordem'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_metodologia->DbValue = $row['nu_metodologia'];
		$this->no_metodologia->DbValue = $row['no_metodologia'];
		$this->ds_metodologia->DbValue = $row['ds_metodologia'];
		$this->ic_tpModeloDev->DbValue = $row['ic_tpModeloDev'];
		$this->ic_ativo->DbValue = $row['ic_ativo'];
		$this->nu_ordem->DbValue = $row['nu_ordem'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_metodologia")) <> "")
			$this->nu_metodologia->CurrentValue = $this->getKey("nu_metodologia"); // nu_metodologia
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
		// nu_metodologia
		// no_metodologia
		// ds_metodologia
		// ic_tpModeloDev
		// ic_ativo
		// nu_ordem

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// no_metodologia
			$this->no_metodologia->ViewValue = $this->no_metodologia->CurrentValue;
			$this->no_metodologia->ViewCustomAttributes = "";

			// ds_metodologia
			$this->ds_metodologia->ViewValue = $this->ds_metodologia->CurrentValue;
			$this->ds_metodologia->ViewCustomAttributes = "";

			// ic_tpModeloDev
			if (strval($this->ic_tpModeloDev->CurrentValue) <> "") {
				switch ($this->ic_tpModeloDev->CurrentValue) {
					case $this->ic_tpModeloDev->FldTagValue(1):
						$this->ic_tpModeloDev->ViewValue = $this->ic_tpModeloDev->FldTagCaption(1) <> "" ? $this->ic_tpModeloDev->FldTagCaption(1) : $this->ic_tpModeloDev->CurrentValue;
						break;
					case $this->ic_tpModeloDev->FldTagValue(2):
						$this->ic_tpModeloDev->ViewValue = $this->ic_tpModeloDev->FldTagCaption(2) <> "" ? $this->ic_tpModeloDev->FldTagCaption(2) : $this->ic_tpModeloDev->CurrentValue;
						break;
					case $this->ic_tpModeloDev->FldTagValue(3):
						$this->ic_tpModeloDev->ViewValue = $this->ic_tpModeloDev->FldTagCaption(3) <> "" ? $this->ic_tpModeloDev->FldTagCaption(3) : $this->ic_tpModeloDev->CurrentValue;
						break;
					case $this->ic_tpModeloDev->FldTagValue(4):
						$this->ic_tpModeloDev->ViewValue = $this->ic_tpModeloDev->FldTagCaption(4) <> "" ? $this->ic_tpModeloDev->FldTagCaption(4) : $this->ic_tpModeloDev->CurrentValue;
						break;
					case $this->ic_tpModeloDev->FldTagValue(5):
						$this->ic_tpModeloDev->ViewValue = $this->ic_tpModeloDev->FldTagCaption(5) <> "" ? $this->ic_tpModeloDev->FldTagCaption(5) : $this->ic_tpModeloDev->CurrentValue;
						break;
					case $this->ic_tpModeloDev->FldTagValue(6):
						$this->ic_tpModeloDev->ViewValue = $this->ic_tpModeloDev->FldTagCaption(6) <> "" ? $this->ic_tpModeloDev->FldTagCaption(6) : $this->ic_tpModeloDev->CurrentValue;
						break;
					default:
						$this->ic_tpModeloDev->ViewValue = $this->ic_tpModeloDev->CurrentValue;
				}
			} else {
				$this->ic_tpModeloDev->ViewValue = NULL;
			}
			$this->ic_tpModeloDev->ViewCustomAttributes = "";

			// ic_ativo
			if (strval($this->ic_ativo->CurrentValue) <> "") {
				switch ($this->ic_ativo->CurrentValue) {
					case $this->ic_ativo->FldTagValue(1):
						$this->ic_ativo->ViewValue = $this->ic_ativo->FldTagCaption(1) <> "" ? $this->ic_ativo->FldTagCaption(1) : $this->ic_ativo->CurrentValue;
						break;
					case $this->ic_ativo->FldTagValue(2):
						$this->ic_ativo->ViewValue = $this->ic_ativo->FldTagCaption(2) <> "" ? $this->ic_ativo->FldTagCaption(2) : $this->ic_ativo->CurrentValue;
						break;
					default:
						$this->ic_ativo->ViewValue = $this->ic_ativo->CurrentValue;
				}
			} else {
				$this->ic_ativo->ViewValue = NULL;
			}
			$this->ic_ativo->ViewCustomAttributes = "";

			// nu_ordem
			$this->nu_ordem->ViewValue = $this->nu_ordem->CurrentValue;
			$this->nu_ordem->ViewCustomAttributes = "";

			// no_metodologia
			$this->no_metodologia->LinkCustomAttributes = "";
			$this->no_metodologia->HrefValue = "";
			$this->no_metodologia->TooltipValue = "";

			// ds_metodologia
			$this->ds_metodologia->LinkCustomAttributes = "";
			$this->ds_metodologia->HrefValue = "";
			$this->ds_metodologia->TooltipValue = "";

			// ic_tpModeloDev
			$this->ic_tpModeloDev->LinkCustomAttributes = "";
			$this->ic_tpModeloDev->HrefValue = "";
			$this->ic_tpModeloDev->TooltipValue = "";

			// ic_ativo
			$this->ic_ativo->LinkCustomAttributes = "";
			$this->ic_ativo->HrefValue = "";
			$this->ic_ativo->TooltipValue = "";

			// nu_ordem
			$this->nu_ordem->LinkCustomAttributes = "";
			$this->nu_ordem->HrefValue = "";
			$this->nu_ordem->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// no_metodologia
			$this->no_metodologia->EditCustomAttributes = "";
			$this->no_metodologia->EditValue = ew_HtmlEncode($this->no_metodologia->CurrentValue);
			$this->no_metodologia->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_metodologia->FldCaption()));

			// ds_metodologia
			$this->ds_metodologia->EditCustomAttributes = "";
			$this->ds_metodologia->EditValue = $this->ds_metodologia->CurrentValue;
			$this->ds_metodologia->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_metodologia->FldCaption()));

			// ic_tpModeloDev
			$this->ic_tpModeloDev->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_tpModeloDev->FldTagValue(1), $this->ic_tpModeloDev->FldTagCaption(1) <> "" ? $this->ic_tpModeloDev->FldTagCaption(1) : $this->ic_tpModeloDev->FldTagValue(1));
			$arwrk[] = array($this->ic_tpModeloDev->FldTagValue(2), $this->ic_tpModeloDev->FldTagCaption(2) <> "" ? $this->ic_tpModeloDev->FldTagCaption(2) : $this->ic_tpModeloDev->FldTagValue(2));
			$arwrk[] = array($this->ic_tpModeloDev->FldTagValue(3), $this->ic_tpModeloDev->FldTagCaption(3) <> "" ? $this->ic_tpModeloDev->FldTagCaption(3) : $this->ic_tpModeloDev->FldTagValue(3));
			$arwrk[] = array($this->ic_tpModeloDev->FldTagValue(4), $this->ic_tpModeloDev->FldTagCaption(4) <> "" ? $this->ic_tpModeloDev->FldTagCaption(4) : $this->ic_tpModeloDev->FldTagValue(4));
			$arwrk[] = array($this->ic_tpModeloDev->FldTagValue(5), $this->ic_tpModeloDev->FldTagCaption(5) <> "" ? $this->ic_tpModeloDev->FldTagCaption(5) : $this->ic_tpModeloDev->FldTagValue(5));
			$arwrk[] = array($this->ic_tpModeloDev->FldTagValue(6), $this->ic_tpModeloDev->FldTagCaption(6) <> "" ? $this->ic_tpModeloDev->FldTagCaption(6) : $this->ic_tpModeloDev->FldTagValue(6));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_tpModeloDev->EditValue = $arwrk;

			// ic_ativo
			$this->ic_ativo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_ativo->FldTagValue(1), $this->ic_ativo->FldTagCaption(1) <> "" ? $this->ic_ativo->FldTagCaption(1) : $this->ic_ativo->FldTagValue(1));
			$arwrk[] = array($this->ic_ativo->FldTagValue(2), $this->ic_ativo->FldTagCaption(2) <> "" ? $this->ic_ativo->FldTagCaption(2) : $this->ic_ativo->FldTagValue(2));
			$this->ic_ativo->EditValue = $arwrk;

			// nu_ordem
			$this->nu_ordem->EditCustomAttributes = "";
			$this->nu_ordem->EditValue = ew_HtmlEncode($this->nu_ordem->CurrentValue);
			$this->nu_ordem->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_ordem->FldCaption()));

			// Edit refer script
			// no_metodologia

			$this->no_metodologia->HrefValue = "";

			// ds_metodologia
			$this->ds_metodologia->HrefValue = "";

			// ic_tpModeloDev
			$this->ic_tpModeloDev->HrefValue = "";

			// ic_ativo
			$this->ic_ativo->HrefValue = "";

			// nu_ordem
			$this->nu_ordem->HrefValue = "";
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
		if (!$this->no_metodologia->FldIsDetailKey && !is_null($this->no_metodologia->FormValue) && $this->no_metodologia->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_metodologia->FldCaption());
		}
		if (!$this->ic_tpModeloDev->FldIsDetailKey && !is_null($this->ic_tpModeloDev->FormValue) && $this->ic_tpModeloDev->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_tpModeloDev->FldCaption());
		}
		if ($this->ic_ativo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_ativo->FldCaption());
		}
		if (!ew_CheckInteger($this->nu_ordem->FormValue)) {
			ew_AddMessage($gsFormError, $this->nu_ordem->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("roteiro", $DetailTblVar) && $GLOBALS["roteiro"]->DetailAdd) {
			if (!isset($GLOBALS["roteiro_grid"])) $GLOBALS["roteiro_grid"] = new croteiro_grid(); // get detail page object
			$GLOBALS["roteiro_grid"]->ValidateGridForm();
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

		// no_metodologia
		$this->no_metodologia->SetDbValueDef($rsnew, $this->no_metodologia->CurrentValue, "", FALSE);

		// ds_metodologia
		$this->ds_metodologia->SetDbValueDef($rsnew, $this->ds_metodologia->CurrentValue, NULL, FALSE);

		// ic_tpModeloDev
		$this->ic_tpModeloDev->SetDbValueDef($rsnew, $this->ic_tpModeloDev->CurrentValue, NULL, FALSE);

		// ic_ativo
		$this->ic_ativo->SetDbValueDef($rsnew, $this->ic_ativo->CurrentValue, "", FALSE);

		// nu_ordem
		$this->nu_ordem->SetDbValueDef($rsnew, $this->nu_ordem->CurrentValue, NULL, FALSE);

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
			$this->nu_metodologia->setDbValue($conn->Insert_ID());
			$rsnew['nu_metodologia'] = $this->nu_metodologia->DbValue;
		}

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("roteiro", $DetailTblVar) && $GLOBALS["roteiro"]->DetailAdd) {
				$GLOBALS["roteiro"]->nu_metodologia->setSessionValue($this->nu_metodologia->CurrentValue); // Set master key
				if (!isset($GLOBALS["roteiro_grid"])) $GLOBALS["roteiro_grid"] = new croteiro_grid(); // Get detail page object
				$AddRow = $GLOBALS["roteiro_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["roteiro"]->nu_metodologia->setSessionValue(""); // Clear master key if insert failed
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
			if (in_array("roteiro", $DetailTblVar)) {
				if (!isset($GLOBALS["roteiro_grid"]))
					$GLOBALS["roteiro_grid"] = new croteiro_grid;
				if ($GLOBALS["roteiro_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["roteiro_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["roteiro_grid"]->CurrentMode = "add";
					$GLOBALS["roteiro_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["roteiro_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["roteiro_grid"]->setStartRecordNumber(1);
					$GLOBALS["roteiro_grid"]->nu_metodologia->FldIsDetailKey = TRUE;
					$GLOBALS["roteiro_grid"]->nu_metodologia->CurrentValue = $this->nu_metodologia->CurrentValue;
					$GLOBALS["roteiro_grid"]->nu_metodologia->setSessionValue($GLOBALS["roteiro_grid"]->nu_metodologia->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "metodologialist.php", $this->TableVar);
		$PageCaption = ($this->CurrentAction == "C") ? $Language->Phrase("Copy") : $Language->Phrase("Add");
		$Breadcrumb->Add("add", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'metodologia';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'metodologia';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_metodologia'];

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
if (!isset($metodologia_add)) $metodologia_add = new cmetodologia_add();

// Page init
$metodologia_add->Page_Init();

// Page main
$metodologia_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$metodologia_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var metodologia_add = new ew_Page("metodologia_add");
metodologia_add.PageID = "add"; // Page ID
var EW_PAGE_ID = metodologia_add.PageID; // For backward compatibility

// Form object
var fmetodologiaadd = new ew_Form("fmetodologiaadd");

// Validate form
fmetodologiaadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_no_metodologia");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($metodologia->no_metodologia->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_tpModeloDev");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($metodologia->ic_tpModeloDev->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_ativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($metodologia->ic_ativo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_ordem");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($metodologia->nu_ordem->FldErrMsg()) ?>");

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
fmetodologiaadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmetodologiaadd.ValidateRequired = true;
<?php } else { ?>
fmetodologiaadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $metodologia_add->ShowPageHeader(); ?>
<?php
$metodologia_add->ShowMessage();
?>
<form name="fmetodologiaadd" id="fmetodologiaadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="metodologia">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_metodologiaadd" class="table table-bordered table-striped">
<?php if ($metodologia->no_metodologia->Visible) { // no_metodologia ?>
	<tr id="r_no_metodologia">
		<td><span id="elh_metodologia_no_metodologia"><?php echo $metodologia->no_metodologia->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $metodologia->no_metodologia->CellAttributes() ?>>
<span id="el_metodologia_no_metodologia" class="control-group">
<input type="text" data-field="x_no_metodologia" name="x_no_metodologia" id="x_no_metodologia" size="30" maxlength="75" placeholder="<?php echo $metodologia->no_metodologia->PlaceHolder ?>" value="<?php echo $metodologia->no_metodologia->EditValue ?>"<?php echo $metodologia->no_metodologia->EditAttributes() ?>>
</span>
<?php echo $metodologia->no_metodologia->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($metodologia->ds_metodologia->Visible) { // ds_metodologia ?>
	<tr id="r_ds_metodologia">
		<td><span id="elh_metodologia_ds_metodologia"><?php echo $metodologia->ds_metodologia->FldCaption() ?></span></td>
		<td<?php echo $metodologia->ds_metodologia->CellAttributes() ?>>
<span id="el_metodologia_ds_metodologia" class="control-group">
<textarea data-field="x_ds_metodologia" name="x_ds_metodologia" id="x_ds_metodologia" cols="35" rows="4" placeholder="<?php echo $metodologia->ds_metodologia->PlaceHolder ?>"<?php echo $metodologia->ds_metodologia->EditAttributes() ?>><?php echo $metodologia->ds_metodologia->EditValue ?></textarea>
</span>
<?php echo $metodologia->ds_metodologia->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($metodologia->ic_tpModeloDev->Visible) { // ic_tpModeloDev ?>
	<tr id="r_ic_tpModeloDev">
		<td><span id="elh_metodologia_ic_tpModeloDev"><?php echo $metodologia->ic_tpModeloDev->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $metodologia->ic_tpModeloDev->CellAttributes() ?>>
<span id="el_metodologia_ic_tpModeloDev" class="control-group">
<select data-field="x_ic_tpModeloDev" id="x_ic_tpModeloDev" name="x_ic_tpModeloDev"<?php echo $metodologia->ic_tpModeloDev->EditAttributes() ?>>
<?php
if (is_array($metodologia->ic_tpModeloDev->EditValue)) {
	$arwrk = $metodologia->ic_tpModeloDev->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($metodologia->ic_tpModeloDev->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
</span>
<?php echo $metodologia->ic_tpModeloDev->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($metodologia->ic_ativo->Visible) { // ic_ativo ?>
	<tr id="r_ic_ativo">
		<td><span id="elh_metodologia_ic_ativo"><?php echo $metodologia->ic_ativo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $metodologia->ic_ativo->CellAttributes() ?>>
<span id="el_metodologia_ic_ativo" class="control-group">
<div id="tp_x_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_ativo" id="x_ic_ativo" value="{value}"<?php echo $metodologia->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $metodologia->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($metodologia->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x_ic_ativo" id="x_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $metodologia->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $metodologia->ic_ativo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($metodologia->nu_ordem->Visible) { // nu_ordem ?>
	<tr id="r_nu_ordem">
		<td><span id="elh_metodologia_nu_ordem"><?php echo $metodologia->nu_ordem->FldCaption() ?></span></td>
		<td<?php echo $metodologia->nu_ordem->CellAttributes() ?>>
<span id="el_metodologia_nu_ordem" class="control-group">
<input type="text" data-field="x_nu_ordem" name="x_nu_ordem" id="x_nu_ordem" size="30" placeholder="<?php echo $metodologia->nu_ordem->PlaceHolder ?>" value="<?php echo $metodologia->nu_ordem->EditValue ?>"<?php echo $metodologia->nu_ordem->EditAttributes() ?>>
</span>
<?php echo $metodologia->nu_ordem->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<?php
	if (in_array("roteiro", explode(",", $metodologia->getCurrentDetailTable())) && $roteiro->DetailAdd) {
?>
<?php include_once "roteirogrid.php" ?>
<?php } ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fmetodologiaadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$metodologia_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$metodologia_add->Page_Terminate();
?>
