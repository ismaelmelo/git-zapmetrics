<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "roteiroinfo.php" ?>
<?php include_once "metodologiainfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "faseroteirogridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$roteiro_edit = NULL; // Initialize page object first

class croteiro_edit extends croteiro {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'roteiro';

	// Page object name
	var $PageObjName = 'roteiro_edit';

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

		// Table object (roteiro)
		if (!isset($GLOBALS["roteiro"])) {
			$GLOBALS["roteiro"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["roteiro"];
		}

		// Table object (metodologia)
		if (!isset($GLOBALS['metodologia'])) $GLOBALS['metodologia'] = new cmetodologia();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'roteiro', TRUE);

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
			$this->Page_Terminate("roteirolist.php");
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
		if (@$_GET["nu_roteiro"] <> "") {
			$this->nu_roteiro->setQueryStringValue($_GET["nu_roteiro"]);
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
		if ($this->nu_roteiro->CurrentValue == "")
			$this->Page_Terminate("roteirolist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("roteirolist.php"); // No matching record, return to list
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
		if (!$this->nu_metodologia->FldIsDetailKey) {
			$this->nu_metodologia->setFormValue($objForm->GetValue("x_nu_metodologia"));
		}
		if (!$this->no_roteiro->FldIsDetailKey) {
			$this->no_roteiro->setFormValue($objForm->GetValue("x_no_roteiro"));
		}
		if (!$this->ds_roteiro->FldIsDetailKey) {
			$this->ds_roteiro->setFormValue($objForm->GetValue("x_ds_roteiro"));
		}
		if (!$this->ic_ativo->FldIsDetailKey) {
			$this->ic_ativo->setFormValue($objForm->GetValue("x_ic_ativo"));
		}
		if (!$this->nu_ordem->FldIsDetailKey) {
			$this->nu_ordem->setFormValue($objForm->GetValue("x_nu_ordem"));
		}
		if (!$this->nu_roteiro->FldIsDetailKey)
			$this->nu_roteiro->setFormValue($objForm->GetValue("x_nu_roteiro"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->nu_roteiro->CurrentValue = $this->nu_roteiro->FormValue;
		$this->nu_metodologia->CurrentValue = $this->nu_metodologia->FormValue;
		$this->no_roteiro->CurrentValue = $this->no_roteiro->FormValue;
		$this->ds_roteiro->CurrentValue = $this->ds_roteiro->FormValue;
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
		$this->nu_roteiro->setDbValue($rs->fields('nu_roteiro'));
		$this->nu_metodologia->setDbValue($rs->fields('nu_metodologia'));
		$this->no_roteiro->setDbValue($rs->fields('no_roteiro'));
		$this->ds_roteiro->setDbValue($rs->fields('ds_roteiro'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
		$this->nu_ordem->setDbValue($rs->fields('nu_ordem'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_roteiro->DbValue = $row['nu_roteiro'];
		$this->nu_metodologia->DbValue = $row['nu_metodologia'];
		$this->no_roteiro->DbValue = $row['no_roteiro'];
		$this->ds_roteiro->DbValue = $row['ds_roteiro'];
		$this->ic_ativo->DbValue = $row['ic_ativo'];
		$this->nu_ordem->DbValue = $row['nu_ordem'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_roteiro
		// nu_metodologia
		// no_roteiro
		// ds_roteiro
		// ic_ativo
		// nu_ordem

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_metodologia
			if (strval($this->nu_metodologia->CurrentValue) <> "") {
				$sFilterWrk = "[nu_metodologia]" . ew_SearchString("=", $this->nu_metodologia->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_metodologia], [no_metodologia] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[metodologia]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_metodologia, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_metodologia->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_metodologia->ViewValue = $this->nu_metodologia->CurrentValue;
				}
			} else {
				$this->nu_metodologia->ViewValue = NULL;
			}
			$this->nu_metodologia->ViewCustomAttributes = "";

			// no_roteiro
			$this->no_roteiro->ViewValue = $this->no_roteiro->CurrentValue;
			$this->no_roteiro->ViewCustomAttributes = "";

			// ds_roteiro
			$this->ds_roteiro->ViewValue = $this->ds_roteiro->CurrentValue;
			$this->ds_roteiro->ViewCustomAttributes = "";

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

			// nu_metodologia
			$this->nu_metodologia->LinkCustomAttributes = "";
			$this->nu_metodologia->HrefValue = "";
			$this->nu_metodologia->TooltipValue = "";

			// no_roteiro
			$this->no_roteiro->LinkCustomAttributes = "";
			$this->no_roteiro->HrefValue = "";
			$this->no_roteiro->TooltipValue = "";

			// ds_roteiro
			$this->ds_roteiro->LinkCustomAttributes = "";
			$this->ds_roteiro->HrefValue = "";
			$this->ds_roteiro->TooltipValue = "";

			// ic_ativo
			$this->ic_ativo->LinkCustomAttributes = "";
			$this->ic_ativo->HrefValue = "";
			$this->ic_ativo->TooltipValue = "";

			// nu_ordem
			$this->nu_ordem->LinkCustomAttributes = "";
			$this->nu_ordem->HrefValue = "";
			$this->nu_ordem->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// nu_metodologia
			$this->nu_metodologia->EditCustomAttributes = "";
			if (strval($this->nu_metodologia->CurrentValue) <> "") {
				$sFilterWrk = "[nu_metodologia]" . ew_SearchString("=", $this->nu_metodologia->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_metodologia], [no_metodologia] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[metodologia]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_metodologia, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_metodologia->EditValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_metodologia->EditValue = $this->nu_metodologia->CurrentValue;
				}
			} else {
				$this->nu_metodologia->EditValue = NULL;
			}
			$this->nu_metodologia->ViewCustomAttributes = "";

			// no_roteiro
			$this->no_roteiro->EditCustomAttributes = "";
			$this->no_roteiro->EditValue = ew_HtmlEncode($this->no_roteiro->CurrentValue);
			$this->no_roteiro->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_roteiro->FldCaption()));

			// ds_roteiro
			$this->ds_roteiro->EditCustomAttributes = "";
			$this->ds_roteiro->EditValue = $this->ds_roteiro->CurrentValue;
			$this->ds_roteiro->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_roteiro->FldCaption()));

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
			// nu_metodologia

			$this->nu_metodologia->HrefValue = "";

			// no_roteiro
			$this->no_roteiro->HrefValue = "";

			// ds_roteiro
			$this->ds_roteiro->HrefValue = "";

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
		if (!$this->no_roteiro->FldIsDetailKey && !is_null($this->no_roteiro->FormValue) && $this->no_roteiro->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_roteiro->FldCaption());
		}
		if ($this->ic_ativo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_ativo->FldCaption());
		}
		if (!ew_CheckInteger($this->nu_ordem->FormValue)) {
			ew_AddMessage($gsFormError, $this->nu_ordem->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("faseroteiro", $DetailTblVar) && $GLOBALS["faseroteiro"]->DetailEdit) {
			if (!isset($GLOBALS["faseroteiro_grid"])) $GLOBALS["faseroteiro_grid"] = new cfaseroteiro_grid(); // get detail page object
			$GLOBALS["faseroteiro_grid"]->ValidateGridForm();
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

			// no_roteiro
			$this->no_roteiro->SetDbValueDef($rsnew, $this->no_roteiro->CurrentValue, "", $this->no_roteiro->ReadOnly);

			// ds_roteiro
			$this->ds_roteiro->SetDbValueDef($rsnew, $this->ds_roteiro->CurrentValue, NULL, $this->ds_roteiro->ReadOnly);

			// ic_ativo
			$this->ic_ativo->SetDbValueDef($rsnew, $this->ic_ativo->CurrentValue, "", $this->ic_ativo->ReadOnly);

			// nu_ordem
			$this->nu_ordem->SetDbValueDef($rsnew, $this->nu_ordem->CurrentValue, NULL, $this->nu_ordem->ReadOnly);

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
					if (in_array("faseroteiro", $DetailTblVar) && $GLOBALS["faseroteiro"]->DetailEdit) {
						if (!isset($GLOBALS["faseroteiro_grid"])) $GLOBALS["faseroteiro_grid"] = new cfaseroteiro_grid(); // Get detail page object
						$EditRow = $GLOBALS["faseroteiro_grid"]->GridUpdate();
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
			if ($sMasterTblVar == "metodologia") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_metodologia"] <> "") {
					$GLOBALS["metodologia"]->nu_metodologia->setQueryStringValue($_GET["nu_metodologia"]);
					$this->nu_metodologia->setQueryStringValue($GLOBALS["metodologia"]->nu_metodologia->QueryStringValue);
					$this->nu_metodologia->setSessionValue($this->nu_metodologia->QueryStringValue);
					if (!is_numeric($GLOBALS["metodologia"]->nu_metodologia->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "metodologia") {
				if ($this->nu_metodologia->QueryStringValue == "") $this->nu_metodologia->setSessionValue("");
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
			if (in_array("faseroteiro", $DetailTblVar)) {
				if (!isset($GLOBALS["faseroteiro_grid"]))
					$GLOBALS["faseroteiro_grid"] = new cfaseroteiro_grid;
				if ($GLOBALS["faseroteiro_grid"]->DetailEdit) {
					$GLOBALS["faseroteiro_grid"]->CurrentMode = "edit";
					$GLOBALS["faseroteiro_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["faseroteiro_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["faseroteiro_grid"]->setStartRecordNumber(1);
					$GLOBALS["faseroteiro_grid"]->nu_roteiro->FldIsDetailKey = TRUE;
					$GLOBALS["faseroteiro_grid"]->nu_roteiro->CurrentValue = $this->nu_roteiro->CurrentValue;
					$GLOBALS["faseroteiro_grid"]->nu_roteiro->setSessionValue($GLOBALS["faseroteiro_grid"]->nu_roteiro->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "roteirolist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("edit");
		$Breadcrumb->Add("edit", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'roteiro';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		if (!$this->AuditTrailOnEdit) return;
		$table = 'roteiro';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['nu_roteiro'];

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
if (!isset($roteiro_edit)) $roteiro_edit = new croteiro_edit();

// Page init
$roteiro_edit->Page_Init();

// Page main
$roteiro_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$roteiro_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var roteiro_edit = new ew_Page("roteiro_edit");
roteiro_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = roteiro_edit.PageID; // For backward compatibility

// Form object
var froteiroedit = new ew_Form("froteiroedit");

// Validate form
froteiroedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_no_roteiro");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($roteiro->no_roteiro->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_ativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($roteiro->ic_ativo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_ordem");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($roteiro->nu_ordem->FldErrMsg()) ?>");

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
froteiroedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
froteiroedit.ValidateRequired = true;
<?php } else { ?>
froteiroedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
froteiroedit.Lists["x_nu_metodologia"] = {"LinkField":"x_nu_metodologia","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_metodologia","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $roteiro_edit->ShowPageHeader(); ?>
<?php
$roteiro_edit->ShowMessage();
?>
<form name="froteiroedit" id="froteiroedit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="roteiro">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_roteiroedit" class="table table-bordered table-striped">
<?php if ($roteiro->nu_metodologia->Visible) { // nu_metodologia ?>
	<tr id="r_nu_metodologia">
		<td><span id="elh_roteiro_nu_metodologia"><?php echo $roteiro->nu_metodologia->FldCaption() ?></span></td>
		<td<?php echo $roteiro->nu_metodologia->CellAttributes() ?>>
<span id="el_roteiro_nu_metodologia" class="control-group">
<span<?php echo $roteiro->nu_metodologia->ViewAttributes() ?>>
<?php echo $roteiro->nu_metodologia->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_metodologia" name="x_nu_metodologia" id="x_nu_metodologia" value="<?php echo ew_HtmlEncode($roteiro->nu_metodologia->CurrentValue) ?>">
<?php echo $roteiro->nu_metodologia->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($roteiro->no_roteiro->Visible) { // no_roteiro ?>
	<tr id="r_no_roteiro">
		<td><span id="elh_roteiro_no_roteiro"><?php echo $roteiro->no_roteiro->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $roteiro->no_roteiro->CellAttributes() ?>>
<span id="el_roteiro_no_roteiro" class="control-group">
<input type="text" data-field="x_no_roteiro" name="x_no_roteiro" id="x_no_roteiro" size="30" maxlength="75" placeholder="<?php echo $roteiro->no_roteiro->PlaceHolder ?>" value="<?php echo $roteiro->no_roteiro->EditValue ?>"<?php echo $roteiro->no_roteiro->EditAttributes() ?>>
</span>
<?php echo $roteiro->no_roteiro->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($roteiro->ds_roteiro->Visible) { // ds_roteiro ?>
	<tr id="r_ds_roteiro">
		<td><span id="elh_roteiro_ds_roteiro"><?php echo $roteiro->ds_roteiro->FldCaption() ?></span></td>
		<td<?php echo $roteiro->ds_roteiro->CellAttributes() ?>>
<span id="el_roteiro_ds_roteiro" class="control-group">
<textarea data-field="x_ds_roteiro" name="x_ds_roteiro" id="x_ds_roteiro" cols="35" rows="4" placeholder="<?php echo $roteiro->ds_roteiro->PlaceHolder ?>"<?php echo $roteiro->ds_roteiro->EditAttributes() ?>><?php echo $roteiro->ds_roteiro->EditValue ?></textarea>
</span>
<?php echo $roteiro->ds_roteiro->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($roteiro->ic_ativo->Visible) { // ic_ativo ?>
	<tr id="r_ic_ativo">
		<td><span id="elh_roteiro_ic_ativo"><?php echo $roteiro->ic_ativo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $roteiro->ic_ativo->CellAttributes() ?>>
<span id="el_roteiro_ic_ativo" class="control-group">
<div id="tp_x_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_ativo" id="x_ic_ativo" value="{value}"<?php echo $roteiro->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $roteiro->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($roteiro->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x_ic_ativo" id="x_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $roteiro->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $roteiro->ic_ativo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($roteiro->nu_ordem->Visible) { // nu_ordem ?>
	<tr id="r_nu_ordem">
		<td><span id="elh_roteiro_nu_ordem"><?php echo $roteiro->nu_ordem->FldCaption() ?></span></td>
		<td<?php echo $roteiro->nu_ordem->CellAttributes() ?>>
<span id="el_roteiro_nu_ordem" class="control-group">
<input type="text" data-field="x_nu_ordem" name="x_nu_ordem" id="x_nu_ordem" size="30" placeholder="<?php echo $roteiro->nu_ordem->PlaceHolder ?>" value="<?php echo $roteiro->nu_ordem->EditValue ?>"<?php echo $roteiro->nu_ordem->EditAttributes() ?>>
</span>
<?php echo $roteiro->nu_ordem->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<input type="hidden" data-field="x_nu_roteiro" name="x_nu_roteiro" id="x_nu_roteiro" value="<?php echo ew_HtmlEncode($roteiro->nu_roteiro->CurrentValue) ?>">
<?php
	if (in_array("faseroteiro", explode(",", $roteiro->getCurrentDetailTable())) && $faseroteiro->DetailEdit) {
?>
<?php include_once "faseroteirogrid.php" ?>
<?php } ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
froteiroedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$roteiro_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$roteiro_edit->Page_Terminate();
?>
