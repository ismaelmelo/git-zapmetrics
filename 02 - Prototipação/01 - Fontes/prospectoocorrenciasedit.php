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

$prospectoocorrencias_edit = NULL; // Initialize page object first

class cprospectoocorrencias_edit extends cprospectoocorrencias {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'prospectoocorrencias';

	// Page object name
	var $PageObjName = 'prospectoocorrencias_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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
		if (!$Security->CanEdit()) {
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
		$this->nu_ocorrencia->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		if (@$_GET["nu_ocorrencia"] <> "") {
			$this->nu_ocorrencia->setQueryStringValue($_GET["nu_ocorrencia"]);
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
		if ($this->nu_ocorrencia->CurrentValue == "")
			$this->Page_Terminate("prospectoocorrenciaslist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("prospectoocorrenciaslist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "prospectoocorrenciasview.php")
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
		if (!$this->nu_prospecto->FldIsDetailKey) {
			$this->nu_prospecto->setFormValue($objForm->GetValue("x_nu_prospecto"));
		}
		if (!$this->nu_ocorrencia->FldIsDetailKey)
			$this->nu_ocorrencia->setFormValue($objForm->GetValue("x_nu_ocorrencia"));
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
		$this->LoadRow();
		$this->nu_prospecto->CurrentValue = $this->nu_prospecto->FormValue;
		$this->nu_ocorrencia->CurrentValue = $this->nu_ocorrencia->FormValue;
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

			// nu_ocorrencia
			$this->nu_ocorrencia->LinkCustomAttributes = "";
			$this->nu_ocorrencia->HrefValue = "";
			$this->nu_ocorrencia->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// nu_prospecto
			$this->nu_prospecto->EditCustomAttributes = "";
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
					$this->nu_prospecto->EditValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_prospecto->EditValue = $this->nu_prospecto->CurrentValue;
				}
			} else {
				$this->nu_prospecto->EditValue = NULL;
			}
			$this->nu_prospecto->ViewCustomAttributes = "";

			// nu_ocorrencia
			$this->nu_ocorrencia->EditCustomAttributes = "";
			$this->nu_ocorrencia->EditValue = $this->nu_ocorrencia->CurrentValue;
			$this->nu_ocorrencia->ViewCustomAttributes = "";

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

			// nu_ocorrencia
			$this->nu_ocorrencia->HrefValue = "";

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

			// no_assuntoOcor
			$this->no_assuntoOcor->SetDbValueDef($rsnew, $this->no_assuntoOcor->CurrentValue, NULL, $this->no_assuntoOcor->ReadOnly);

			// ds_ocorrencia
			$this->ds_ocorrencia->SetDbValueDef($rsnew, $this->ds_ocorrencia->CurrentValue, "", $this->ds_ocorrencia->ReadOnly);

			// dh_inclusao
			$this->dh_inclusao->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->dh_inclusao->CurrentValue, 11), NULL, $this->dh_inclusao->ReadOnly);

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
		$PageCaption = $Language->Phrase("edit");
		$Breadcrumb->Add("edit", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
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
if (!isset($prospectoocorrencias_edit)) $prospectoocorrencias_edit = new cprospectoocorrencias_edit();

// Page init
$prospectoocorrencias_edit->Page_Init();

// Page main
$prospectoocorrencias_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$prospectoocorrencias_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var prospectoocorrencias_edit = new ew_Page("prospectoocorrencias_edit");
prospectoocorrencias_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = prospectoocorrencias_edit.PageID; // For backward compatibility

// Form object
var fprospectoocorrenciasedit = new ew_Form("fprospectoocorrenciasedit");

// Validate form
fprospectoocorrenciasedit.Validate = function() {
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
fprospectoocorrenciasedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fprospectoocorrenciasedit.ValidateRequired = true;
<?php } else { ?>
fprospectoocorrenciasedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fprospectoocorrenciasedit.Lists["x_nu_prospecto"] = {"LinkField":"x_nu_prospecto","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_prospecto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $prospectoocorrencias_edit->ShowPageHeader(); ?>
<?php
$prospectoocorrencias_edit->ShowMessage();
?>
<form name="fprospectoocorrenciasedit" id="fprospectoocorrenciasedit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="prospectoocorrencias">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_prospectoocorrenciasedit" class="table table-bordered table-striped">
<?php if ($prospectoocorrencias->nu_prospecto->Visible) { // nu_prospecto ?>
	<tr id="r_nu_prospecto">
		<td><span id="elh_prospectoocorrencias_nu_prospecto"><?php echo $prospectoocorrencias->nu_prospecto->FldCaption() ?></span></td>
		<td<?php echo $prospectoocorrencias->nu_prospecto->CellAttributes() ?>>
<span id="el_prospectoocorrencias_nu_prospecto" class="control-group">
<span<?php echo $prospectoocorrencias->nu_prospecto->ViewAttributes() ?>>
<?php echo $prospectoocorrencias->nu_prospecto->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_prospecto" name="x_nu_prospecto" id="x_nu_prospecto" value="<?php echo ew_HtmlEncode($prospectoocorrencias->nu_prospecto->CurrentValue) ?>">
<?php echo $prospectoocorrencias->nu_prospecto->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($prospectoocorrencias->nu_ocorrencia->Visible) { // nu_ocorrencia ?>
	<tr id="r_nu_ocorrencia">
		<td><span id="elh_prospectoocorrencias_nu_ocorrencia"><?php echo $prospectoocorrencias->nu_ocorrencia->FldCaption() ?></span></td>
		<td<?php echo $prospectoocorrencias->nu_ocorrencia->CellAttributes() ?>>
<span id="el_prospectoocorrencias_nu_ocorrencia" class="control-group">
<span<?php echo $prospectoocorrencias->nu_ocorrencia->ViewAttributes() ?>>
<?php echo $prospectoocorrencias->nu_ocorrencia->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_ocorrencia" name="x_nu_ocorrencia" id="x_nu_ocorrencia" value="<?php echo ew_HtmlEncode($prospectoocorrencias->nu_ocorrencia->CurrentValue) ?>">
<?php echo $prospectoocorrencias->nu_ocorrencia->CustomMsg ?></td>
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
ew_CreateCalendar("fprospectoocorrenciasedit", "x_dh_inclusao", "%d/%m/%Y %H:%M:%S");
</script>
<?php } ?>
</span>
<?php echo $prospectoocorrencias->dh_inclusao->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fprospectoocorrenciasedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$prospectoocorrencias_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$prospectoocorrencias_edit->Page_Terminate();
?>
