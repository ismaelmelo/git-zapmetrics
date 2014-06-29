<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "ativroteiroinfo.php" ?>
<?php include_once "faseroteiroinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$ativroteiro_edit = NULL; // Initialize page object first

class cativroteiro_edit extends cativroteiro {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'ativroteiro';

	// Page object name
	var $PageObjName = 'ativroteiro_edit';

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

		// Table object (ativroteiro)
		if (!isset($GLOBALS["ativroteiro"])) {
			$GLOBALS["ativroteiro"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["ativroteiro"];
		}

		// Table object (faseroteiro)
		if (!isset($GLOBALS['faseroteiro'])) $GLOBALS['faseroteiro'] = new cfaseroteiro();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'ativroteiro', TRUE);

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
			$this->Page_Terminate("ativroteirolist.php");
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
		if (@$_GET["nu_ativRoteiro"] <> "") {
			$this->nu_ativRoteiro->setQueryStringValue($_GET["nu_ativRoteiro"]);
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
		if ($this->nu_ativRoteiro->CurrentValue == "")
			$this->Page_Terminate("ativroteirolist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("ativroteirolist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
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
		if (!$this->nu_faseRoteiro->FldIsDetailKey) {
			$this->nu_faseRoteiro->setFormValue($objForm->GetValue("x_nu_faseRoteiro"));
		}
		if (!$this->no_ativRoteiro->FldIsDetailKey) {
			$this->no_ativRoteiro->setFormValue($objForm->GetValue("x_no_ativRoteiro"));
		}
		if (!$this->ds_atividade->FldIsDetailKey) {
			$this->ds_atividade->setFormValue($objForm->GetValue("x_ds_atividade"));
		}
		if (!$this->pc_distribuicao->FldIsDetailKey) {
			$this->pc_distribuicao->setFormValue($objForm->GetValue("x_pc_distribuicao"));
		}
		if (!$this->ic_ativo->FldIsDetailKey) {
			$this->ic_ativo->setFormValue($objForm->GetValue("x_ic_ativo"));
		}
		if (!$this->nu_ordem->FldIsDetailKey) {
			$this->nu_ordem->setFormValue($objForm->GetValue("x_nu_ordem"));
		}
		if (!$this->nu_ativRoteiro->FldIsDetailKey)
			$this->nu_ativRoteiro->setFormValue($objForm->GetValue("x_nu_ativRoteiro"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->nu_ativRoteiro->CurrentValue = $this->nu_ativRoteiro->FormValue;
		$this->nu_faseRoteiro->CurrentValue = $this->nu_faseRoteiro->FormValue;
		$this->no_ativRoteiro->CurrentValue = $this->no_ativRoteiro->FormValue;
		$this->ds_atividade->CurrentValue = $this->ds_atividade->FormValue;
		$this->pc_distribuicao->CurrentValue = $this->pc_distribuicao->FormValue;
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
		$this->nu_ativRoteiro->setDbValue($rs->fields('nu_ativRoteiro'));
		$this->nu_faseRoteiro->setDbValue($rs->fields('nu_faseRoteiro'));
		$this->no_ativRoteiro->setDbValue($rs->fields('no_ativRoteiro'));
		$this->ds_atividade->setDbValue($rs->fields('ds_atividade'));
		$this->pc_distribuicao->setDbValue($rs->fields('pc_distribuicao'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
		$this->nu_ordem->setDbValue($rs->fields('nu_ordem'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_ativRoteiro->DbValue = $row['nu_ativRoteiro'];
		$this->nu_faseRoteiro->DbValue = $row['nu_faseRoteiro'];
		$this->no_ativRoteiro->DbValue = $row['no_ativRoteiro'];
		$this->ds_atividade->DbValue = $row['ds_atividade'];
		$this->pc_distribuicao->DbValue = $row['pc_distribuicao'];
		$this->ic_ativo->DbValue = $row['ic_ativo'];
		$this->nu_ordem->DbValue = $row['nu_ordem'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->pc_distribuicao->FormValue == $this->pc_distribuicao->CurrentValue && is_numeric(ew_StrToFloat($this->pc_distribuicao->CurrentValue)))
			$this->pc_distribuicao->CurrentValue = ew_StrToFloat($this->pc_distribuicao->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_ativRoteiro
		// nu_faseRoteiro
		// no_ativRoteiro
		// ds_atividade
		// pc_distribuicao
		// ic_ativo
		// nu_ordem

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_faseRoteiro
			if (strval($this->nu_faseRoteiro->CurrentValue) <> "") {
				$sFilterWrk = "[nu_roteiro]" . ew_SearchString("=", $this->nu_faseRoteiro->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_roteiro], [no_roteiro] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[roteiro]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_faseRoteiro, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_faseRoteiro->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_faseRoteiro->ViewValue = $this->nu_faseRoteiro->CurrentValue;
				}
			} else {
				$this->nu_faseRoteiro->ViewValue = NULL;
			}
			$this->nu_faseRoteiro->ViewCustomAttributes = "";

			// no_ativRoteiro
			$this->no_ativRoteiro->ViewValue = $this->no_ativRoteiro->CurrentValue;
			$this->no_ativRoteiro->ViewCustomAttributes = "";

			// ds_atividade
			$this->ds_atividade->ViewValue = $this->ds_atividade->CurrentValue;
			$this->ds_atividade->ViewCustomAttributes = "";

			// pc_distribuicao
			$this->pc_distribuicao->ViewValue = $this->pc_distribuicao->CurrentValue;
			$this->pc_distribuicao->ViewValue = ew_FormatNumber($this->pc_distribuicao->ViewValue, 2, 0, 0, 0);
			$this->pc_distribuicao->ViewCustomAttributes = "";

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

			// nu_faseRoteiro
			$this->nu_faseRoteiro->LinkCustomAttributes = "";
			$this->nu_faseRoteiro->HrefValue = "";
			$this->nu_faseRoteiro->TooltipValue = "";

			// no_ativRoteiro
			$this->no_ativRoteiro->LinkCustomAttributes = "";
			$this->no_ativRoteiro->HrefValue = "";
			$this->no_ativRoteiro->TooltipValue = "";

			// ds_atividade
			$this->ds_atividade->LinkCustomAttributes = "";
			$this->ds_atividade->HrefValue = "";
			$this->ds_atividade->TooltipValue = "";

			// pc_distribuicao
			$this->pc_distribuicao->LinkCustomAttributes = "";
			$this->pc_distribuicao->HrefValue = "";
			$this->pc_distribuicao->TooltipValue = "";

			// ic_ativo
			$this->ic_ativo->LinkCustomAttributes = "";
			$this->ic_ativo->HrefValue = "";
			$this->ic_ativo->TooltipValue = "";

			// nu_ordem
			$this->nu_ordem->LinkCustomAttributes = "";
			$this->nu_ordem->HrefValue = "";
			$this->nu_ordem->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// nu_faseRoteiro
			$this->nu_faseRoteiro->EditCustomAttributes = "";
			if (strval($this->nu_faseRoteiro->CurrentValue) <> "") {
				$sFilterWrk = "[nu_roteiro]" . ew_SearchString("=", $this->nu_faseRoteiro->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_roteiro], [no_roteiro] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[roteiro]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_faseRoteiro, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_faseRoteiro->EditValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_faseRoteiro->EditValue = $this->nu_faseRoteiro->CurrentValue;
				}
			} else {
				$this->nu_faseRoteiro->EditValue = NULL;
			}
			$this->nu_faseRoteiro->ViewCustomAttributes = "";

			// no_ativRoteiro
			$this->no_ativRoteiro->EditCustomAttributes = "";
			$this->no_ativRoteiro->EditValue = ew_HtmlEncode($this->no_ativRoteiro->CurrentValue);
			$this->no_ativRoteiro->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_ativRoteiro->FldCaption()));

			// ds_atividade
			$this->ds_atividade->EditCustomAttributes = "";
			$this->ds_atividade->EditValue = $this->ds_atividade->CurrentValue;
			$this->ds_atividade->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_atividade->FldCaption()));

			// pc_distribuicao
			$this->pc_distribuicao->EditCustomAttributes = "";
			$this->pc_distribuicao->EditValue = ew_HtmlEncode($this->pc_distribuicao->CurrentValue);
			$this->pc_distribuicao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->pc_distribuicao->FldCaption()));
			if (strval($this->pc_distribuicao->EditValue) <> "" && is_numeric($this->pc_distribuicao->EditValue)) $this->pc_distribuicao->EditValue = ew_FormatNumber($this->pc_distribuicao->EditValue, -2, 0, 0, 0);

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
			// nu_faseRoteiro

			$this->nu_faseRoteiro->HrefValue = "";

			// no_ativRoteiro
			$this->no_ativRoteiro->HrefValue = "";

			// ds_atividade
			$this->ds_atividade->HrefValue = "";

			// pc_distribuicao
			$this->pc_distribuicao->HrefValue = "";

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
		if (!$this->no_ativRoteiro->FldIsDetailKey && !is_null($this->no_ativRoteiro->FormValue) && $this->no_ativRoteiro->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_ativRoteiro->FldCaption());
		}
		if (!ew_CheckNumber($this->pc_distribuicao->FormValue)) {
			ew_AddMessage($gsFormError, $this->pc_distribuicao->FldErrMsg());
		}
		if ($this->ic_ativo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_ativo->FldCaption());
		}
		if (!ew_CheckInteger($this->nu_ordem->FormValue)) {
			ew_AddMessage($gsFormError, $this->nu_ordem->FldErrMsg());
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

			// no_ativRoteiro
			$this->no_ativRoteiro->SetDbValueDef($rsnew, $this->no_ativRoteiro->CurrentValue, "", $this->no_ativRoteiro->ReadOnly);

			// ds_atividade
			$this->ds_atividade->SetDbValueDef($rsnew, $this->ds_atividade->CurrentValue, NULL, $this->ds_atividade->ReadOnly);

			// pc_distribuicao
			$this->pc_distribuicao->SetDbValueDef($rsnew, $this->pc_distribuicao->CurrentValue, NULL, $this->pc_distribuicao->ReadOnly);

			// ic_ativo
			$this->ic_ativo->SetDbValueDef($rsnew, $this->ic_ativo->CurrentValue, "", $this->ic_ativo->ReadOnly);

			// nu_ordem
			$this->nu_ordem->SetDbValueDef($rsnew, $this->nu_ordem->CurrentValue, NULL, $this->nu_ordem->ReadOnly);

			// Check referential integrity for master table 'faseroteiro'
			$bValidMasterRecord = TRUE;
			$sMasterFilter = $this->SqlMasterFilter_faseroteiro();
			$KeyValue = isset($rsnew['nu_faseRoteiro']) ? $rsnew['nu_faseRoteiro'] : $rsold['nu_faseRoteiro'];
			if (strval($KeyValue) <> "") {
				$sMasterFilter = str_replace("@nu_faseRoteiro@", ew_AdjustSql($KeyValue), $sMasterFilter);
			} else {
				$bValidMasterRecord = FALSE;
			}
			if ($bValidMasterRecord) {
				$rsmaster = $GLOBALS["faseroteiro"]->LoadRs($sMasterFilter);
				$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
				$rsmaster->Close();
			}
			if (!$bValidMasterRecord) {
				$sRelatedRecordMsg = str_replace("%t", "faseroteiro", $Language->Phrase("RelatedRecordRequired"));
				$this->setFailureMessage($sRelatedRecordMsg);
				$rs->Close();
				return FALSE;
			}

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
			if ($sMasterTblVar == "faseroteiro") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_faseRoteiro"] <> "") {
					$GLOBALS["faseroteiro"]->nu_faseRoteiro->setQueryStringValue($_GET["nu_faseRoteiro"]);
					$this->nu_faseRoteiro->setQueryStringValue($GLOBALS["faseroteiro"]->nu_faseRoteiro->QueryStringValue);
					$this->nu_faseRoteiro->setSessionValue($this->nu_faseRoteiro->QueryStringValue);
					if (!is_numeric($GLOBALS["faseroteiro"]->nu_faseRoteiro->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "faseroteiro") {
				if ($this->nu_faseRoteiro->QueryStringValue == "") $this->nu_faseRoteiro->setSessionValue("");
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "ativroteirolist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("edit");
		$Breadcrumb->Add("edit", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'ativroteiro';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		if (!$this->AuditTrailOnEdit) return;
		$table = 'ativroteiro';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['nu_ativRoteiro'];

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
if (!isset($ativroteiro_edit)) $ativroteiro_edit = new cativroteiro_edit();

// Page init
$ativroteiro_edit->Page_Init();

// Page main
$ativroteiro_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$ativroteiro_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var ativroteiro_edit = new ew_Page("ativroteiro_edit");
ativroteiro_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = ativroteiro_edit.PageID; // For backward compatibility

// Form object
var fativroteiroedit = new ew_Form("fativroteiroedit");

// Validate form
fativroteiroedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_no_ativRoteiro");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ativroteiro->no_ativRoteiro->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_pc_distribuicao");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ativroteiro->pc_distribuicao->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ic_ativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ativroteiro->ic_ativo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_ordem");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ativroteiro->nu_ordem->FldErrMsg()) ?>");

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
fativroteiroedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fativroteiroedit.ValidateRequired = true;
<?php } else { ?>
fativroteiroedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fativroteiroedit.Lists["x_nu_faseRoteiro"] = {"LinkField":"x_nu_roteiro","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_roteiro","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $ativroteiro_edit->ShowPageHeader(); ?>
<?php
$ativroteiro_edit->ShowMessage();
?>
<form name="fativroteiroedit" id="fativroteiroedit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="ativroteiro">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_ativroteiroedit" class="table table-bordered table-striped">
<?php if ($ativroteiro->nu_faseRoteiro->Visible) { // nu_faseRoteiro ?>
	<tr id="r_nu_faseRoteiro">
		<td><span id="elh_ativroteiro_nu_faseRoteiro"><?php echo $ativroteiro->nu_faseRoteiro->FldCaption() ?></span></td>
		<td<?php echo $ativroteiro->nu_faseRoteiro->CellAttributes() ?>>
<span id="el_ativroteiro_nu_faseRoteiro" class="control-group">
<span<?php echo $ativroteiro->nu_faseRoteiro->ViewAttributes() ?>>
<?php echo $ativroteiro->nu_faseRoteiro->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_faseRoteiro" name="x_nu_faseRoteiro" id="x_nu_faseRoteiro" value="<?php echo ew_HtmlEncode($ativroteiro->nu_faseRoteiro->CurrentValue) ?>">
<?php echo $ativroteiro->nu_faseRoteiro->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ativroteiro->no_ativRoteiro->Visible) { // no_ativRoteiro ?>
	<tr id="r_no_ativRoteiro">
		<td><span id="elh_ativroteiro_no_ativRoteiro"><?php echo $ativroteiro->no_ativRoteiro->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $ativroteiro->no_ativRoteiro->CellAttributes() ?>>
<span id="el_ativroteiro_no_ativRoteiro" class="control-group">
<input type="text" data-field="x_no_ativRoteiro" name="x_no_ativRoteiro" id="x_no_ativRoteiro" size="30" maxlength="75" placeholder="<?php echo $ativroteiro->no_ativRoteiro->PlaceHolder ?>" value="<?php echo $ativroteiro->no_ativRoteiro->EditValue ?>"<?php echo $ativroteiro->no_ativRoteiro->EditAttributes() ?>>
</span>
<?php echo $ativroteiro->no_ativRoteiro->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ativroteiro->ds_atividade->Visible) { // ds_atividade ?>
	<tr id="r_ds_atividade">
		<td><span id="elh_ativroteiro_ds_atividade"><?php echo $ativroteiro->ds_atividade->FldCaption() ?></span></td>
		<td<?php echo $ativroteiro->ds_atividade->CellAttributes() ?>>
<span id="el_ativroteiro_ds_atividade" class="control-group">
<textarea data-field="x_ds_atividade" name="x_ds_atividade" id="x_ds_atividade" cols="35" rows="4" placeholder="<?php echo $ativroteiro->ds_atividade->PlaceHolder ?>"<?php echo $ativroteiro->ds_atividade->EditAttributes() ?>><?php echo $ativroteiro->ds_atividade->EditValue ?></textarea>
</span>
<?php echo $ativroteiro->ds_atividade->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ativroteiro->pc_distribuicao->Visible) { // pc_distribuicao ?>
	<tr id="r_pc_distribuicao">
		<td><span id="elh_ativroteiro_pc_distribuicao"><?php echo $ativroteiro->pc_distribuicao->FldCaption() ?></span></td>
		<td<?php echo $ativroteiro->pc_distribuicao->CellAttributes() ?>>
<span id="el_ativroteiro_pc_distribuicao" class="control-group">
<input type="text" data-field="x_pc_distribuicao" name="x_pc_distribuicao" id="x_pc_distribuicao" size="30" placeholder="<?php echo $ativroteiro->pc_distribuicao->PlaceHolder ?>" value="<?php echo $ativroteiro->pc_distribuicao->EditValue ?>"<?php echo $ativroteiro->pc_distribuicao->EditAttributes() ?>>
</span>
<?php echo $ativroteiro->pc_distribuicao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ativroteiro->ic_ativo->Visible) { // ic_ativo ?>
	<tr id="r_ic_ativo">
		<td><span id="elh_ativroteiro_ic_ativo"><?php echo $ativroteiro->ic_ativo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $ativroteiro->ic_ativo->CellAttributes() ?>>
<span id="el_ativroteiro_ic_ativo" class="control-group">
<div id="tp_x_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_ativo" id="x_ic_ativo" value="{value}"<?php echo $ativroteiro->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $ativroteiro->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ativroteiro->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x_ic_ativo" id="x_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $ativroteiro->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $ativroteiro->ic_ativo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ativroteiro->nu_ordem->Visible) { // nu_ordem ?>
	<tr id="r_nu_ordem">
		<td><span id="elh_ativroteiro_nu_ordem"><?php echo $ativroteiro->nu_ordem->FldCaption() ?></span></td>
		<td<?php echo $ativroteiro->nu_ordem->CellAttributes() ?>>
<span id="el_ativroteiro_nu_ordem" class="control-group">
<input type="text" data-field="x_nu_ordem" name="x_nu_ordem" id="x_nu_ordem" size="30" placeholder="<?php echo $ativroteiro->nu_ordem->PlaceHolder ?>" value="<?php echo $ativroteiro->nu_ordem->EditValue ?>"<?php echo $ativroteiro->nu_ordem->EditAttributes() ?>>
</span>
<?php echo $ativroteiro->nu_ordem->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<input type="hidden" data-field="x_nu_ativRoteiro" name="x_nu_ativRoteiro" id="x_nu_ativRoteiro" value="<?php echo ew_HtmlEncode($ativroteiro->nu_ativRoteiro->CurrentValue) ?>">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fativroteiroedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$ativroteiro_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$ativroteiro_edit->Page_Terminate();
?>
