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

$fatura_edit = NULL; // Initialize page object first

class cfatura_edit extends cfatura {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'fatura';

	// Page object name
	var $PageObjName = 'fatura_edit';

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

		// Table object (fatura)
		if (!isset($GLOBALS["fatura"])) {
			$GLOBALS["fatura"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["fatura"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

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
		if (!$Security->CanEdit()) {
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
		$this->nu_fatura->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		if (@$_GET["nu_fatura"] <> "") {
			$this->nu_fatura->setQueryStringValue($_GET["nu_fatura"]);
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
		if ($this->nu_fatura->CurrentValue == "")
			$this->Page_Terminate("faturalist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("faturalist.php"); // No matching record, return to list
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
		if (!$this->nu_fatura->FldIsDetailKey)
			$this->nu_fatura->setFormValue($objForm->GetValue("x_nu_fatura"));
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
		$this->LoadRow();
		$this->nu_fatura->CurrentValue = $this->nu_fatura->FormValue;
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

			// nu_fatura
			$this->nu_fatura->LinkCustomAttributes = "";
			$this->nu_fatura->HrefValue = "";
			$this->nu_fatura->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// nu_fatura
			$this->nu_fatura->EditCustomAttributes = "";
			$this->nu_fatura->EditValue = $this->nu_fatura->CurrentValue;
			$this->nu_fatura->ViewCustomAttributes = "";

			// nu_tpFatura
			$this->nu_tpFatura->EditCustomAttributes = "";
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
					$this->nu_tpFatura->EditValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpFatura->EditValue = $this->nu_tpFatura->CurrentValue;
				}
			} else {
				$this->nu_tpFatura->EditValue = NULL;
			}
			$this->nu_tpFatura->ViewCustomAttributes = "";

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
			// nu_fatura

			$this->nu_fatura->HrefValue = "";

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
		if (in_array("fatura_os", $DetailTblVar) && $GLOBALS["fatura_os"]->DetailEdit) {
			if (!isset($GLOBALS["fatura_os_grid"])) $GLOBALS["fatura_os_grid"] = new cfatura_os_grid(); // get detail page object
			$GLOBALS["fatura_os_grid"]->ValidateGridForm();
		}
		if (in_array("nffatura", $DetailTblVar) && $GLOBALS["nffatura"]->DetailEdit) {
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

			// ds_fatura
			$this->ds_fatura->SetDbValueDef($rsnew, $this->ds_fatura->CurrentValue, "", $this->ds_fatura->ReadOnly);

			// dt_faturamento
			$this->dt_faturamento->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->dt_faturamento->CurrentValue, 7), ew_CurrentDate(), $this->dt_faturamento->ReadOnly);

			// nu_stFatura
			$this->nu_stFatura->SetDbValueDef($rsnew, $this->nu_stFatura->CurrentValue, NULL, $this->nu_stFatura->ReadOnly);

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
					if (in_array("fatura_os", $DetailTblVar) && $GLOBALS["fatura_os"]->DetailEdit) {
						if (!isset($GLOBALS["fatura_os_grid"])) $GLOBALS["fatura_os_grid"] = new cfatura_os_grid(); // Get detail page object
						$EditRow = $GLOBALS["fatura_os_grid"]->GridUpdate();
					}
					if (in_array("nffatura", $DetailTblVar) && $GLOBALS["nffatura"]->DetailEdit) {
						if (!isset($GLOBALS["nffatura_grid"])) $GLOBALS["nffatura_grid"] = new cnffatura_grid(); // Get detail page object
						$EditRow = $GLOBALS["nffatura_grid"]->GridUpdate();
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
			if (in_array("fatura_os", $DetailTblVar)) {
				if (!isset($GLOBALS["fatura_os_grid"]))
					$GLOBALS["fatura_os_grid"] = new cfatura_os_grid;
				if ($GLOBALS["fatura_os_grid"]->DetailEdit) {
					$GLOBALS["fatura_os_grid"]->CurrentMode = "edit";
					$GLOBALS["fatura_os_grid"]->CurrentAction = "gridedit";

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
				if ($GLOBALS["nffatura_grid"]->DetailEdit) {
					$GLOBALS["nffatura_grid"]->CurrentMode = "edit";
					$GLOBALS["nffatura_grid"]->CurrentAction = "gridedit";

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
		$PageCaption = $Language->Phrase("edit");
		$Breadcrumb->Add("edit", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'fatura';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		if (!$this->AuditTrailOnEdit) return;
		$table = 'fatura';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['nu_fatura'];

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
if (!isset($fatura_edit)) $fatura_edit = new cfatura_edit();

// Page init
$fatura_edit->Page_Init();

// Page main
$fatura_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$fatura_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var fatura_edit = new ew_Page("fatura_edit");
fatura_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = fatura_edit.PageID; // For backward compatibility

// Form object
var ffaturaedit = new ew_Form("ffaturaedit");

// Validate form
ffaturaedit.Validate = function() {
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
ffaturaedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffaturaedit.ValidateRequired = true;
<?php } else { ?>
ffaturaedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ffaturaedit.Lists["x_nu_tpFatura"] = {"LinkField":"x_nu_tpFatura","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpFatura","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ffaturaedit.Lists["x_nu_stFatura"] = {"LinkField":"x_nu_stFatura","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_stFatura","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $fatura_edit->ShowPageHeader(); ?>
<?php
$fatura_edit->ShowMessage();
?>
<form name="ffaturaedit" id="ffaturaedit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="fatura">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_faturaedit" class="table table-bordered table-striped">
<?php if ($fatura->nu_fatura->Visible) { // nu_fatura ?>
	<tr id="r_nu_fatura">
		<td><span id="elh_fatura_nu_fatura"><?php echo $fatura->nu_fatura->FldCaption() ?></span></td>
		<td<?php echo $fatura->nu_fatura->CellAttributes() ?>>
<span id="el_fatura_nu_fatura" class="control-group">
<span<?php echo $fatura->nu_fatura->ViewAttributes() ?>>
<?php echo $fatura->nu_fatura->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_fatura" name="x_nu_fatura" id="x_nu_fatura" value="<?php echo ew_HtmlEncode($fatura->nu_fatura->CurrentValue) ?>">
<?php echo $fatura->nu_fatura->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fatura->nu_tpFatura->Visible) { // nu_tpFatura ?>
	<tr id="r_nu_tpFatura">
		<td><span id="elh_fatura_nu_tpFatura"><?php echo $fatura->nu_tpFatura->FldCaption() ?></span></td>
		<td<?php echo $fatura->nu_tpFatura->CellAttributes() ?>>
<span id="el_fatura_nu_tpFatura" class="control-group">
<span<?php echo $fatura->nu_tpFatura->ViewAttributes() ?>>
<?php echo $fatura->nu_tpFatura->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_tpFatura" name="x_nu_tpFatura" id="x_nu_tpFatura" value="<?php echo ew_HtmlEncode($fatura->nu_tpFatura->CurrentValue) ?>">
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
ew_CreateCalendar("ffaturaedit", "x_dt_faturamento", "%d/%m/%Y");
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
ffaturaedit.Lists["x_nu_stFatura"].Options = <?php echo (is_array($fatura->nu_stFatura->EditValue)) ? ew_ArrayToJson($fatura->nu_stFatura->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $fatura->nu_stFatura->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<?php
	if (in_array("fatura_os", explode(",", $fatura->getCurrentDetailTable())) && $fatura_os->DetailEdit) {
?>
<?php include_once "fatura_osgrid.php" ?>
<?php } ?>
<?php
	if (in_array("nffatura", explode(",", $fatura->getCurrentDetailTable())) && $nffatura->DetailEdit) {
?>
<?php include_once "nffaturagrid.php" ?>
<?php } ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
ffaturaedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$fatura_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$fatura_edit->Page_Terminate();
?>
