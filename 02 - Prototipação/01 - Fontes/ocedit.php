<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "ocinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "itemocgridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$oc_edit = NULL; // Initialize page object first

class coc_edit extends coc {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'oc';

	// Page object name
	var $PageObjName = 'oc_edit';

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

		// Table object (oc)
		if (!isset($GLOBALS["oc"])) {
			$GLOBALS["oc"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["oc"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'oc', TRUE);

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
			$this->Page_Terminate("oclist.php");
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
		if (@$_GET["nu_oc"] <> "") {
			$this->nu_oc->setQueryStringValue($_GET["nu_oc"]);
		}

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
		if ($this->nu_oc->CurrentValue == "")
			$this->Page_Terminate("oclist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("oclist.php"); // No matching record, return to list
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
		if (!$this->ic_tpOc->FldIsDetailKey) {
			$this->ic_tpOc->setFormValue($objForm->GetValue("x_ic_tpOc"));
		}
		if (!$this->co_alternativo->FldIsDetailKey) {
			$this->co_alternativo->setFormValue($objForm->GetValue("x_co_alternativo"));
		}
		if (!$this->ds_oc->FldIsDetailKey) {
			$this->ds_oc->setFormValue($objForm->GetValue("x_ds_oc"));
		}
		if (!$this->dt_oc->FldIsDetailKey) {
			$this->dt_oc->setFormValue($objForm->GetValue("x_dt_oc"));
			$this->dt_oc->CurrentValue = ew_UnFormatDateTime($this->dt_oc->CurrentValue, 7);
		}
		if (!$this->nu_stOc->FldIsDetailKey) {
			$this->nu_stOc->setFormValue($objForm->GetValue("x_nu_stOc"));
		}
		if (!$this->ds_observacoes->FldIsDetailKey) {
			$this->ds_observacoes->setFormValue($objForm->GetValue("x_ds_observacoes"));
		}
		if (!$this->nu_oc->FldIsDetailKey)
			$this->nu_oc->setFormValue($objForm->GetValue("x_nu_oc"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->nu_oc->CurrentValue = $this->nu_oc->FormValue;
		$this->ic_tpOc->CurrentValue = $this->ic_tpOc->FormValue;
		$this->co_alternativo->CurrentValue = $this->co_alternativo->FormValue;
		$this->ds_oc->CurrentValue = $this->ds_oc->FormValue;
		$this->dt_oc->CurrentValue = $this->dt_oc->FormValue;
		$this->dt_oc->CurrentValue = ew_UnFormatDateTime($this->dt_oc->CurrentValue, 7);
		$this->nu_stOc->CurrentValue = $this->nu_stOc->FormValue;
		$this->ds_observacoes->CurrentValue = $this->ds_observacoes->FormValue;
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
		$this->nu_oc->setDbValue($rs->fields('nu_oc'));
		$this->ic_tpOc->setDbValue($rs->fields('ic_tpOc'));
		$this->co_alternativo->setDbValue($rs->fields('co_alternativo'));
		$this->ds_oc->setDbValue($rs->fields('ds_oc'));
		$this->dt_oc->setDbValue($rs->fields('dt_oc'));
		$this->nu_stOc->setDbValue($rs->fields('nu_stOc'));
		$this->ds_observacoes->setDbValue($rs->fields('ds_observacoes'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_oc->DbValue = $row['nu_oc'];
		$this->ic_tpOc->DbValue = $row['ic_tpOc'];
		$this->co_alternativo->DbValue = $row['co_alternativo'];
		$this->ds_oc->DbValue = $row['ds_oc'];
		$this->dt_oc->DbValue = $row['dt_oc'];
		$this->nu_stOc->DbValue = $row['nu_stOc'];
		$this->ds_observacoes->DbValue = $row['ds_observacoes'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_oc
		// ic_tpOc
		// co_alternativo
		// ds_oc
		// dt_oc
		// nu_stOc
		// ds_observacoes

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_oc
			$this->nu_oc->ViewValue = $this->nu_oc->CurrentValue;
			$this->nu_oc->ViewCustomAttributes = "";

			// ic_tpOc
			if (strval($this->ic_tpOc->CurrentValue) <> "") {
				switch ($this->ic_tpOc->CurrentValue) {
					case $this->ic_tpOc->FldTagValue(1):
						$this->ic_tpOc->ViewValue = $this->ic_tpOc->FldTagCaption(1) <> "" ? $this->ic_tpOc->FldTagCaption(1) : $this->ic_tpOc->CurrentValue;
						break;
					case $this->ic_tpOc->FldTagValue(2):
						$this->ic_tpOc->ViewValue = $this->ic_tpOc->FldTagCaption(2) <> "" ? $this->ic_tpOc->FldTagCaption(2) : $this->ic_tpOc->CurrentValue;
						break;
					case $this->ic_tpOc->FldTagValue(3):
						$this->ic_tpOc->ViewValue = $this->ic_tpOc->FldTagCaption(3) <> "" ? $this->ic_tpOc->FldTagCaption(3) : $this->ic_tpOc->CurrentValue;
						break;
					default:
						$this->ic_tpOc->ViewValue = $this->ic_tpOc->CurrentValue;
				}
			} else {
				$this->ic_tpOc->ViewValue = NULL;
			}
			$this->ic_tpOc->ViewCustomAttributes = "";

			// co_alternativo
			$this->co_alternativo->ViewValue = $this->co_alternativo->CurrentValue;
			$this->co_alternativo->ViewCustomAttributes = "";

			// ds_oc
			$this->ds_oc->ViewValue = $this->ds_oc->CurrentValue;
			$this->ds_oc->ViewCustomAttributes = "";

			// dt_oc
			$this->dt_oc->ViewValue = $this->dt_oc->CurrentValue;
			$this->dt_oc->ViewValue = ew_FormatDateTime($this->dt_oc->ViewValue, 7);
			$this->dt_oc->ViewCustomAttributes = "";

			// nu_stOc
			if (strval($this->nu_stOc->CurrentValue) <> "") {
				$sFilterWrk = "[nu_stOc]" . ew_SearchString("=", $this->nu_stOc->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_stOc], [no_stOc] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[stoc]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_stOc, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_stOc] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_stOc->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_stOc->ViewValue = $this->nu_stOc->CurrentValue;
				}
			} else {
				$this->nu_stOc->ViewValue = NULL;
			}
			$this->nu_stOc->ViewCustomAttributes = "";

			// ds_observacoes
			$this->ds_observacoes->ViewValue = $this->ds_observacoes->CurrentValue;
			$this->ds_observacoes->ViewCustomAttributes = "";

			// ic_tpOc
			$this->ic_tpOc->LinkCustomAttributes = "";
			$this->ic_tpOc->HrefValue = "";
			$this->ic_tpOc->TooltipValue = "";

			// co_alternativo
			$this->co_alternativo->LinkCustomAttributes = "";
			$this->co_alternativo->HrefValue = "";
			$this->co_alternativo->TooltipValue = "";

			// ds_oc
			$this->ds_oc->LinkCustomAttributes = "";
			$this->ds_oc->HrefValue = "";
			$this->ds_oc->TooltipValue = "";

			// dt_oc
			$this->dt_oc->LinkCustomAttributes = "";
			$this->dt_oc->HrefValue = "";
			$this->dt_oc->TooltipValue = "";

			// nu_stOc
			$this->nu_stOc->LinkCustomAttributes = "";
			$this->nu_stOc->HrefValue = "";
			$this->nu_stOc->TooltipValue = "";

			// ds_observacoes
			$this->ds_observacoes->LinkCustomAttributes = "";
			$this->ds_observacoes->HrefValue = "";
			$this->ds_observacoes->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// ic_tpOc
			$this->ic_tpOc->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_tpOc->FldTagValue(1), $this->ic_tpOc->FldTagCaption(1) <> "" ? $this->ic_tpOc->FldTagCaption(1) : $this->ic_tpOc->FldTagValue(1));
			$arwrk[] = array($this->ic_tpOc->FldTagValue(2), $this->ic_tpOc->FldTagCaption(2) <> "" ? $this->ic_tpOc->FldTagCaption(2) : $this->ic_tpOc->FldTagValue(2));
			$arwrk[] = array($this->ic_tpOc->FldTagValue(3), $this->ic_tpOc->FldTagCaption(3) <> "" ? $this->ic_tpOc->FldTagCaption(3) : $this->ic_tpOc->FldTagValue(3));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_tpOc->EditValue = $arwrk;

			// co_alternativo
			$this->co_alternativo->EditCustomAttributes = "";
			$this->co_alternativo->EditValue = ew_HtmlEncode($this->co_alternativo->CurrentValue);
			$this->co_alternativo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->co_alternativo->FldCaption()));

			// ds_oc
			$this->ds_oc->EditCustomAttributes = "";
			$this->ds_oc->EditValue = $this->ds_oc->CurrentValue;
			$this->ds_oc->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_oc->FldCaption()));

			// dt_oc
			$this->dt_oc->EditCustomAttributes = "";
			$this->dt_oc->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->dt_oc->CurrentValue, 7));
			$this->dt_oc->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dt_oc->FldCaption()));

			// nu_stOc
			$this->nu_stOc->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_stOc], [no_stOc] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[stoc]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_stOc, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_stOc] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_stOc->EditValue = $arwrk;

			// ds_observacoes
			$this->ds_observacoes->EditCustomAttributes = "";
			$this->ds_observacoes->EditValue = $this->ds_observacoes->CurrentValue;
			$this->ds_observacoes->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_observacoes->FldCaption()));

			// Edit refer script
			// ic_tpOc

			$this->ic_tpOc->HrefValue = "";

			// co_alternativo
			$this->co_alternativo->HrefValue = "";

			// ds_oc
			$this->ds_oc->HrefValue = "";

			// dt_oc
			$this->dt_oc->HrefValue = "";

			// nu_stOc
			$this->nu_stOc->HrefValue = "";

			// ds_observacoes
			$this->ds_observacoes->HrefValue = "";
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
		if (!$this->ic_tpOc->FldIsDetailKey && !is_null($this->ic_tpOc->FormValue) && $this->ic_tpOc->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_tpOc->FldCaption());
		}
		if (!$this->co_alternativo->FldIsDetailKey && !is_null($this->co_alternativo->FormValue) && $this->co_alternativo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->co_alternativo->FldCaption());
		}
		if (!$this->dt_oc->FldIsDetailKey && !is_null($this->dt_oc->FormValue) && $this->dt_oc->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->dt_oc->FldCaption());
		}
		if (!ew_CheckEuroDate($this->dt_oc->FormValue)) {
			ew_AddMessage($gsFormError, $this->dt_oc->FldErrMsg());
		}
		if (!$this->nu_stOc->FldIsDetailKey && !is_null($this->nu_stOc->FormValue) && $this->nu_stOc->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_stOc->FldCaption());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("itemoc", $DetailTblVar) && $GLOBALS["itemoc"]->DetailEdit) {
			if (!isset($GLOBALS["itemoc_grid"])) $GLOBALS["itemoc_grid"] = new citemoc_grid(); // get detail page object
			$GLOBALS["itemoc_grid"]->ValidateGridForm();
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

			// ic_tpOc
			$this->ic_tpOc->SetDbValueDef($rsnew, $this->ic_tpOc->CurrentValue, "", $this->ic_tpOc->ReadOnly);

			// co_alternativo
			$this->co_alternativo->SetDbValueDef($rsnew, $this->co_alternativo->CurrentValue, "", $this->co_alternativo->ReadOnly);

			// ds_oc
			$this->ds_oc->SetDbValueDef($rsnew, $this->ds_oc->CurrentValue, NULL, $this->ds_oc->ReadOnly);

			// dt_oc
			$this->dt_oc->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->dt_oc->CurrentValue, 7), ew_CurrentDate(), $this->dt_oc->ReadOnly);

			// nu_stOc
			$this->nu_stOc->SetDbValueDef($rsnew, $this->nu_stOc->CurrentValue, NULL, $this->nu_stOc->ReadOnly);

			// ds_observacoes
			$this->ds_observacoes->SetDbValueDef($rsnew, $this->ds_observacoes->CurrentValue, NULL, $this->ds_observacoes->ReadOnly);

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
					if (in_array("itemoc", $DetailTblVar) && $GLOBALS["itemoc"]->DetailEdit) {
						if (!isset($GLOBALS["itemoc_grid"])) $GLOBALS["itemoc_grid"] = new citemoc_grid(); // Get detail page object
						$EditRow = $GLOBALS["itemoc_grid"]->GridUpdate();
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
			if (in_array("itemoc", $DetailTblVar)) {
				if (!isset($GLOBALS["itemoc_grid"]))
					$GLOBALS["itemoc_grid"] = new citemoc_grid;
				if ($GLOBALS["itemoc_grid"]->DetailEdit) {
					$GLOBALS["itemoc_grid"]->CurrentMode = "edit";
					$GLOBALS["itemoc_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["itemoc_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["itemoc_grid"]->setStartRecordNumber(1);
					$GLOBALS["itemoc_grid"]->nu_oc->FldIsDetailKey = TRUE;
					$GLOBALS["itemoc_grid"]->nu_oc->CurrentValue = $this->nu_oc->CurrentValue;
					$GLOBALS["itemoc_grid"]->nu_oc->setSessionValue($GLOBALS["itemoc_grid"]->nu_oc->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "oclist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("edit");
		$Breadcrumb->Add("edit", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'oc';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		if (!$this->AuditTrailOnEdit) return;
		$table = 'oc';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['nu_oc'];

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
if (!isset($oc_edit)) $oc_edit = new coc_edit();

// Page init
$oc_edit->Page_Init();

// Page main
$oc_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$oc_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var oc_edit = new ew_Page("oc_edit");
oc_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = oc_edit.PageID; // For backward compatibility

// Form object
var focedit = new ew_Form("focedit");

// Validate form
focedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_ic_tpOc");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($oc->ic_tpOc->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_co_alternativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($oc->co_alternativo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dt_oc");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($oc->dt_oc->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dt_oc");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($oc->dt_oc->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nu_stOc");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($oc->nu_stOc->FldCaption()) ?>");

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
focedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
focedit.ValidateRequired = true;
<?php } else { ?>
focedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
focedit.Lists["x_nu_stOc"] = {"LinkField":"x_nu_stOc","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_stOc","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $oc_edit->ShowPageHeader(); ?>
<?php
$oc_edit->ShowMessage();
?>
<form name="focedit" id="focedit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="oc">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_ocedit" class="table table-bordered table-striped">
<?php if ($oc->ic_tpOc->Visible) { // ic_tpOc ?>
	<tr id="r_ic_tpOc">
		<td><span id="elh_oc_ic_tpOc"><?php echo $oc->ic_tpOc->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $oc->ic_tpOc->CellAttributes() ?>>
<span id="el_oc_ic_tpOc" class="control-group">
<select data-field="x_ic_tpOc" id="x_ic_tpOc" name="x_ic_tpOc"<?php echo $oc->ic_tpOc->EditAttributes() ?>>
<?php
if (is_array($oc->ic_tpOc->EditValue)) {
	$arwrk = $oc->ic_tpOc->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($oc->ic_tpOc->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $oc->ic_tpOc->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($oc->co_alternativo->Visible) { // co_alternativo ?>
	<tr id="r_co_alternativo">
		<td><span id="elh_oc_co_alternativo"><?php echo $oc->co_alternativo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $oc->co_alternativo->CellAttributes() ?>>
<span id="el_oc_co_alternativo" class="control-group">
<input type="text" data-field="x_co_alternativo" name="x_co_alternativo" id="x_co_alternativo" size="30" maxlength="35" placeholder="<?php echo $oc->co_alternativo->PlaceHolder ?>" value="<?php echo $oc->co_alternativo->EditValue ?>"<?php echo $oc->co_alternativo->EditAttributes() ?>>
</span>
<?php echo $oc->co_alternativo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($oc->ds_oc->Visible) { // ds_oc ?>
	<tr id="r_ds_oc">
		<td><span id="elh_oc_ds_oc"><?php echo $oc->ds_oc->FldCaption() ?></span></td>
		<td<?php echo $oc->ds_oc->CellAttributes() ?>>
<span id="el_oc_ds_oc" class="control-group">
<textarea data-field="x_ds_oc" name="x_ds_oc" id="x_ds_oc" cols="35" rows="4" placeholder="<?php echo $oc->ds_oc->PlaceHolder ?>"<?php echo $oc->ds_oc->EditAttributes() ?>><?php echo $oc->ds_oc->EditValue ?></textarea>
</span>
<?php echo $oc->ds_oc->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($oc->dt_oc->Visible) { // dt_oc ?>
	<tr id="r_dt_oc">
		<td><span id="elh_oc_dt_oc"><?php echo $oc->dt_oc->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $oc->dt_oc->CellAttributes() ?>>
<span id="el_oc_dt_oc" class="control-group">
<input type="text" data-field="x_dt_oc" name="x_dt_oc" id="x_dt_oc" placeholder="<?php echo $oc->dt_oc->PlaceHolder ?>" value="<?php echo $oc->dt_oc->EditValue ?>"<?php echo $oc->dt_oc->EditAttributes() ?>>
<?php if (!$oc->dt_oc->ReadOnly && !$oc->dt_oc->Disabled && @$oc->dt_oc->EditAttrs["readonly"] == "" && @$oc->dt_oc->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_dt_oc" name="cal_x_dt_oc" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x_dt_oc" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("focedit", "x_dt_oc", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $oc->dt_oc->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($oc->nu_stOc->Visible) { // nu_stOc ?>
	<tr id="r_nu_stOc">
		<td><span id="elh_oc_nu_stOc"><?php echo $oc->nu_stOc->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $oc->nu_stOc->CellAttributes() ?>>
<span id="el_oc_nu_stOc" class="control-group">
<select data-field="x_nu_stOc" id="x_nu_stOc" name="x_nu_stOc"<?php echo $oc->nu_stOc->EditAttributes() ?>>
<?php
if (is_array($oc->nu_stOc->EditValue)) {
	$arwrk = $oc->nu_stOc->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($oc->nu_stOc->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
focedit.Lists["x_nu_stOc"].Options = <?php echo (is_array($oc->nu_stOc->EditValue)) ? ew_ArrayToJson($oc->nu_stOc->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $oc->nu_stOc->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($oc->ds_observacoes->Visible) { // ds_observacoes ?>
	<tr id="r_ds_observacoes">
		<td><span id="elh_oc_ds_observacoes"><?php echo $oc->ds_observacoes->FldCaption() ?></span></td>
		<td<?php echo $oc->ds_observacoes->CellAttributes() ?>>
<span id="el_oc_ds_observacoes" class="control-group">
<textarea data-field="x_ds_observacoes" name="x_ds_observacoes" id="x_ds_observacoes" cols="35" rows="4" placeholder="<?php echo $oc->ds_observacoes->PlaceHolder ?>"<?php echo $oc->ds_observacoes->EditAttributes() ?>><?php echo $oc->ds_observacoes->EditValue ?></textarea>
</span>
<?php echo $oc->ds_observacoes->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<input type="hidden" data-field="x_nu_oc" name="x_nu_oc" id="x_nu_oc" value="<?php echo ew_HtmlEncode($oc->nu_oc->CurrentValue) ?>">
<?php
	if (in_array("itemoc", explode(",", $oc->getCurrentDetailTable())) && $itemoc->DetailEdit) {
?>
<?php include_once "itemocgrid.php" ?>
<?php } ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
focedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$oc_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$oc_edit->Page_Terminate();
?>
