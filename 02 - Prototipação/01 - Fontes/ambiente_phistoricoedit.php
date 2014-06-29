<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "ambiente_phistoricoinfo.php" ?>
<?php include_once "ambienteinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$ambiente_phistorico_edit = NULL; // Initialize page object first

class cambiente_phistorico_edit extends cambiente_phistorico {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'ambiente_phistorico';

	// Page object name
	var $PageObjName = 'ambiente_phistorico_edit';

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

		// Table object (ambiente_phistorico)
		if (!isset($GLOBALS["ambiente_phistorico"])) {
			$GLOBALS["ambiente_phistorico"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["ambiente_phistorico"];
		}

		// Table object (ambiente)
		if (!isset($GLOBALS['ambiente'])) $GLOBALS['ambiente'] = new cambiente();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'ambiente_phistorico', TRUE);

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
			$this->Page_Terminate("ambiente_phistoricolist.php");
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
		if (@$_GET["nu_projhist"] <> "") {
			$this->nu_projhist->setQueryStringValue($_GET["nu_projhist"]);
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
		if ($this->nu_projhist->CurrentValue == "")
			$this->Page_Terminate("ambiente_phistoricolist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("ambiente_phistoricolist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "ambiente_phistoricoview.php")
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
		if (!$this->no_projeto->FldIsDetailKey) {
			$this->no_projeto->setFormValue($objForm->GetValue("x_no_projeto"));
		}
		if (!$this->ds_projeto->FldIsDetailKey) {
			$this->ds_projeto->setFormValue($objForm->GetValue("x_ds_projeto"));
		}
		if (!$this->qt_pf->FldIsDetailKey) {
			$this->qt_pf->setFormValue($objForm->GetValue("x_qt_pf"));
		}
		if (!$this->qt_sloc->FldIsDetailKey) {
			$this->qt_sloc->setFormValue($objForm->GetValue("x_qt_sloc"));
		}
		if (!$this->qt_slocPf->FldIsDetailKey) {
			$this->qt_slocPf->setFormValue($objForm->GetValue("x_qt_slocPf"));
		}
		if (!$this->qt_esforcoReal->FldIsDetailKey) {
			$this->qt_esforcoReal->setFormValue($objForm->GetValue("x_qt_esforcoReal"));
		}
		if (!$this->qt_esforcoRealPm->FldIsDetailKey) {
			$this->qt_esforcoRealPm->setFormValue($objForm->GetValue("x_qt_esforcoRealPm"));
		}
		if (!$this->qt_prazoRealM->FldIsDetailKey) {
			$this->qt_prazoRealM->setFormValue($objForm->GetValue("x_qt_prazoRealM"));
		}
		if (!$this->ic_situacao->FldIsDetailKey) {
			$this->ic_situacao->setFormValue($objForm->GetValue("x_ic_situacao"));
		}
		if (!$this->ds_acoes->FldIsDetailKey) {
			$this->ds_acoes->setFormValue($objForm->GetValue("x_ds_acoes"));
		}
		if (!$this->nu_usuarioAlt->FldIsDetailKey) {
			$this->nu_usuarioAlt->setFormValue($objForm->GetValue("x_nu_usuarioAlt"));
		}
		if (!$this->dh_alteracao->FldIsDetailKey) {
			$this->dh_alteracao->setFormValue($objForm->GetValue("x_dh_alteracao"));
			$this->dh_alteracao->CurrentValue = ew_UnFormatDateTime($this->dh_alteracao->CurrentValue, 7);
		}
		if (!$this->nu_projhist->FldIsDetailKey)
			$this->nu_projhist->setFormValue($objForm->GetValue("x_nu_projhist"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->nu_projhist->CurrentValue = $this->nu_projhist->FormValue;
		$this->no_projeto->CurrentValue = $this->no_projeto->FormValue;
		$this->ds_projeto->CurrentValue = $this->ds_projeto->FormValue;
		$this->qt_pf->CurrentValue = $this->qt_pf->FormValue;
		$this->qt_sloc->CurrentValue = $this->qt_sloc->FormValue;
		$this->qt_slocPf->CurrentValue = $this->qt_slocPf->FormValue;
		$this->qt_esforcoReal->CurrentValue = $this->qt_esforcoReal->FormValue;
		$this->qt_esforcoRealPm->CurrentValue = $this->qt_esforcoRealPm->FormValue;
		$this->qt_prazoRealM->CurrentValue = $this->qt_prazoRealM->FormValue;
		$this->ic_situacao->CurrentValue = $this->ic_situacao->FormValue;
		$this->ds_acoes->CurrentValue = $this->ds_acoes->FormValue;
		$this->nu_usuarioAlt->CurrentValue = $this->nu_usuarioAlt->FormValue;
		$this->dh_alteracao->CurrentValue = $this->dh_alteracao->FormValue;
		$this->dh_alteracao->CurrentValue = ew_UnFormatDateTime($this->dh_alteracao->CurrentValue, 7);
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
		$this->nu_projhist->setDbValue($rs->fields('nu_projhist'));
		$this->nu_ambiente->setDbValue($rs->fields('nu_ambiente'));
		$this->no_projeto->setDbValue($rs->fields('no_projeto'));
		$this->ds_projeto->setDbValue($rs->fields('ds_projeto'));
		$this->qt_pf->setDbValue($rs->fields('qt_pf'));
		$this->qt_sloc->setDbValue($rs->fields('qt_sloc'));
		$this->qt_slocPf->setDbValue($rs->fields('qt_slocPf'));
		$this->qt_esforcoReal->setDbValue($rs->fields('qt_esforcoReal'));
		$this->qt_esforcoRealPm->setDbValue($rs->fields('qt_esforcoRealPm'));
		$this->qt_prazoRealM->setDbValue($rs->fields('qt_prazoRealM'));
		$this->ic_situacao->setDbValue($rs->fields('ic_situacao'));
		$this->ds_acoes->setDbValue($rs->fields('ds_acoes'));
		$this->nu_usuarioInc->setDbValue($rs->fields('nu_usuarioInc'));
		$this->dh_inclusao->setDbValue($rs->fields('dh_inclusao'));
		$this->nu_usuarioAlt->setDbValue($rs->fields('nu_usuarioAlt'));
		$this->dh_alteracao->setDbValue($rs->fields('dh_alteracao'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_projhist->DbValue = $row['nu_projhist'];
		$this->nu_ambiente->DbValue = $row['nu_ambiente'];
		$this->no_projeto->DbValue = $row['no_projeto'];
		$this->ds_projeto->DbValue = $row['ds_projeto'];
		$this->qt_pf->DbValue = $row['qt_pf'];
		$this->qt_sloc->DbValue = $row['qt_sloc'];
		$this->qt_slocPf->DbValue = $row['qt_slocPf'];
		$this->qt_esforcoReal->DbValue = $row['qt_esforcoReal'];
		$this->qt_esforcoRealPm->DbValue = $row['qt_esforcoRealPm'];
		$this->qt_prazoRealM->DbValue = $row['qt_prazoRealM'];
		$this->ic_situacao->DbValue = $row['ic_situacao'];
		$this->ds_acoes->DbValue = $row['ds_acoes'];
		$this->nu_usuarioInc->DbValue = $row['nu_usuarioInc'];
		$this->dh_inclusao->DbValue = $row['dh_inclusao'];
		$this->nu_usuarioAlt->DbValue = $row['nu_usuarioAlt'];
		$this->dh_alteracao->DbValue = $row['dh_alteracao'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->qt_pf->FormValue == $this->qt_pf->CurrentValue && is_numeric(ew_StrToFloat($this->qt_pf->CurrentValue)))
			$this->qt_pf->CurrentValue = ew_StrToFloat($this->qt_pf->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_sloc->FormValue == $this->qt_sloc->CurrentValue && is_numeric(ew_StrToFloat($this->qt_sloc->CurrentValue)))
			$this->qt_sloc->CurrentValue = ew_StrToFloat($this->qt_sloc->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_slocPf->FormValue == $this->qt_slocPf->CurrentValue && is_numeric(ew_StrToFloat($this->qt_slocPf->CurrentValue)))
			$this->qt_slocPf->CurrentValue = ew_StrToFloat($this->qt_slocPf->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_esforcoReal->FormValue == $this->qt_esforcoReal->CurrentValue && is_numeric(ew_StrToFloat($this->qt_esforcoReal->CurrentValue)))
			$this->qt_esforcoReal->CurrentValue = ew_StrToFloat($this->qt_esforcoReal->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_esforcoRealPm->FormValue == $this->qt_esforcoRealPm->CurrentValue && is_numeric(ew_StrToFloat($this->qt_esforcoRealPm->CurrentValue)))
			$this->qt_esforcoRealPm->CurrentValue = ew_StrToFloat($this->qt_esforcoRealPm->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_prazoRealM->FormValue == $this->qt_prazoRealM->CurrentValue && is_numeric(ew_StrToFloat($this->qt_prazoRealM->CurrentValue)))
			$this->qt_prazoRealM->CurrentValue = ew_StrToFloat($this->qt_prazoRealM->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_projhist
		// nu_ambiente
		// no_projeto
		// ds_projeto
		// qt_pf
		// qt_sloc
		// qt_slocPf
		// qt_esforcoReal
		// qt_esforcoRealPm
		// qt_prazoRealM
		// ic_situacao
		// ds_acoes
		// nu_usuarioInc
		// dh_inclusao
		// nu_usuarioAlt
		// dh_alteracao

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_projhist
			$this->nu_projhist->ViewValue = $this->nu_projhist->CurrentValue;
			$this->nu_projhist->ViewCustomAttributes = "";

			// nu_ambiente
			if (strval($this->nu_ambiente->CurrentValue) <> "") {
				$sFilterWrk = "[nu_ambiente]" . ew_SearchString("=", $this->nu_ambiente->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_ambiente], [no_ambiente] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ambiente]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_ambiente, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_ambiente] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_ambiente->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_ambiente->ViewValue = $this->nu_ambiente->CurrentValue;
				}
			} else {
				$this->nu_ambiente->ViewValue = NULL;
			}
			$this->nu_ambiente->ViewCustomAttributes = "";

			// no_projeto
			$this->no_projeto->ViewValue = $this->no_projeto->CurrentValue;
			$this->no_projeto->ViewCustomAttributes = "";

			// ds_projeto
			$this->ds_projeto->ViewValue = $this->ds_projeto->CurrentValue;
			$this->ds_projeto->ViewCustomAttributes = "";

			// qt_pf
			$this->qt_pf->ViewValue = $this->qt_pf->CurrentValue;
			$this->qt_pf->ViewCustomAttributes = "";

			// qt_sloc
			$this->qt_sloc->ViewValue = $this->qt_sloc->CurrentValue;
			$this->qt_sloc->ViewCustomAttributes = "";

			// qt_slocPf
			$this->qt_slocPf->ViewValue = $this->qt_slocPf->CurrentValue;
			$this->qt_slocPf->ViewCustomAttributes = "";

			// qt_esforcoReal
			$this->qt_esforcoReal->ViewValue = $this->qt_esforcoReal->CurrentValue;
			$this->qt_esforcoReal->ViewCustomAttributes = "";

			// qt_esforcoRealPm
			$this->qt_esforcoRealPm->ViewValue = $this->qt_esforcoRealPm->CurrentValue;
			$this->qt_esforcoRealPm->ViewCustomAttributes = "";

			// qt_prazoRealM
			$this->qt_prazoRealM->ViewValue = $this->qt_prazoRealM->CurrentValue;
			$this->qt_prazoRealM->ViewCustomAttributes = "";

			// ic_situacao
			if (strval($this->ic_situacao->CurrentValue) <> "") {
				switch ($this->ic_situacao->CurrentValue) {
					case $this->ic_situacao->FldTagValue(1):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(1) <> "" ? $this->ic_situacao->FldTagCaption(1) : $this->ic_situacao->CurrentValue;
						break;
					case $this->ic_situacao->FldTagValue(2):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(2) <> "" ? $this->ic_situacao->FldTagCaption(2) : $this->ic_situacao->CurrentValue;
						break;
					default:
						$this->ic_situacao->ViewValue = $this->ic_situacao->CurrentValue;
				}
			} else {
				$this->ic_situacao->ViewValue = NULL;
			}
			$this->ic_situacao->ViewCustomAttributes = "";

			// ds_acoes
			$this->ds_acoes->ViewValue = $this->ds_acoes->CurrentValue;
			$this->ds_acoes->ViewCustomAttributes = "";

			// nu_usuarioInc
			if (strval($this->nu_usuarioInc->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioInc->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioInc, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioInc->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioInc->ViewValue = $this->nu_usuarioInc->CurrentValue;
				}
			} else {
				$this->nu_usuarioInc->ViewValue = NULL;
			}
			$this->nu_usuarioInc->ViewCustomAttributes = "";

			// dh_inclusao
			$this->dh_inclusao->ViewValue = $this->dh_inclusao->CurrentValue;
			$this->dh_inclusao->ViewValue = ew_FormatDateTime($this->dh_inclusao->ViewValue, 7);
			$this->dh_inclusao->ViewCustomAttributes = "";

			// nu_usuarioAlt
			$this->nu_usuarioAlt->ViewValue = $this->nu_usuarioAlt->CurrentValue;
			if (strval($this->nu_usuarioAlt->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioAlt->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioAlt, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioAlt->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioAlt->ViewValue = $this->nu_usuarioAlt->CurrentValue;
				}
			} else {
				$this->nu_usuarioAlt->ViewValue = NULL;
			}
			$this->nu_usuarioAlt->ViewCustomAttributes = "";

			// dh_alteracao
			$this->dh_alteracao->ViewValue = $this->dh_alteracao->CurrentValue;
			$this->dh_alteracao->ViewValue = ew_FormatDateTime($this->dh_alteracao->ViewValue, 7);
			$this->dh_alteracao->ViewCustomAttributes = "";

			// no_projeto
			$this->no_projeto->LinkCustomAttributes = "";
			$this->no_projeto->HrefValue = "";
			$this->no_projeto->TooltipValue = "";

			// ds_projeto
			$this->ds_projeto->LinkCustomAttributes = "";
			$this->ds_projeto->HrefValue = "";
			$this->ds_projeto->TooltipValue = "";

			// qt_pf
			$this->qt_pf->LinkCustomAttributes = "";
			$this->qt_pf->HrefValue = "";
			$this->qt_pf->TooltipValue = "";

			// qt_sloc
			$this->qt_sloc->LinkCustomAttributes = "";
			$this->qt_sloc->HrefValue = "";
			$this->qt_sloc->TooltipValue = "";

			// qt_slocPf
			$this->qt_slocPf->LinkCustomAttributes = "";
			$this->qt_slocPf->HrefValue = "";
			$this->qt_slocPf->TooltipValue = "";

			// qt_esforcoReal
			$this->qt_esforcoReal->LinkCustomAttributes = "";
			$this->qt_esforcoReal->HrefValue = "";
			$this->qt_esforcoReal->TooltipValue = "";

			// qt_esforcoRealPm
			$this->qt_esforcoRealPm->LinkCustomAttributes = "";
			$this->qt_esforcoRealPm->HrefValue = "";
			$this->qt_esforcoRealPm->TooltipValue = "";

			// qt_prazoRealM
			$this->qt_prazoRealM->LinkCustomAttributes = "";
			$this->qt_prazoRealM->HrefValue = "";
			$this->qt_prazoRealM->TooltipValue = "";

			// ic_situacao
			$this->ic_situacao->LinkCustomAttributes = "";
			$this->ic_situacao->HrefValue = "";
			$this->ic_situacao->TooltipValue = "";

			// ds_acoes
			$this->ds_acoes->LinkCustomAttributes = "";
			$this->ds_acoes->HrefValue = "";
			$this->ds_acoes->TooltipValue = "";

			// nu_usuarioAlt
			$this->nu_usuarioAlt->LinkCustomAttributes = "";
			$this->nu_usuarioAlt->HrefValue = "";
			$this->nu_usuarioAlt->TooltipValue = "";

			// dh_alteracao
			$this->dh_alteracao->LinkCustomAttributes = "";
			$this->dh_alteracao->HrefValue = "";
			$this->dh_alteracao->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// no_projeto
			$this->no_projeto->EditCustomAttributes = "";
			$this->no_projeto->EditValue = ew_HtmlEncode($this->no_projeto->CurrentValue);
			$this->no_projeto->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_projeto->FldCaption()));

			// ds_projeto
			$this->ds_projeto->EditCustomAttributes = "";
			$this->ds_projeto->EditValue = $this->ds_projeto->CurrentValue;
			$this->ds_projeto->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_projeto->FldCaption()));

			// qt_pf
			$this->qt_pf->EditCustomAttributes = "";
			$this->qt_pf->EditValue = ew_HtmlEncode($this->qt_pf->CurrentValue);
			$this->qt_pf->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_pf->FldCaption()));
			if (strval($this->qt_pf->EditValue) <> "" && is_numeric($this->qt_pf->EditValue)) $this->qt_pf->EditValue = ew_FormatNumber($this->qt_pf->EditValue, -2, -1, -2, 0);

			// qt_sloc
			$this->qt_sloc->EditCustomAttributes = "";
			$this->qt_sloc->EditValue = ew_HtmlEncode($this->qt_sloc->CurrentValue);
			$this->qt_sloc->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_sloc->FldCaption()));
			if (strval($this->qt_sloc->EditValue) <> "" && is_numeric($this->qt_sloc->EditValue)) $this->qt_sloc->EditValue = ew_FormatNumber($this->qt_sloc->EditValue, -2, -1, -2, 0);

			// qt_slocPf
			$this->qt_slocPf->EditCustomAttributes = "";
			$this->qt_slocPf->EditValue = ew_HtmlEncode($this->qt_slocPf->CurrentValue);
			$this->qt_slocPf->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_slocPf->FldCaption()));
			if (strval($this->qt_slocPf->EditValue) <> "" && is_numeric($this->qt_slocPf->EditValue)) $this->qt_slocPf->EditValue = ew_FormatNumber($this->qt_slocPf->EditValue, -2, -1, -2, 0);

			// qt_esforcoReal
			$this->qt_esforcoReal->EditCustomAttributes = "";
			$this->qt_esforcoReal->EditValue = ew_HtmlEncode($this->qt_esforcoReal->CurrentValue);
			$this->qt_esforcoReal->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_esforcoReal->FldCaption()));
			if (strval($this->qt_esforcoReal->EditValue) <> "" && is_numeric($this->qt_esforcoReal->EditValue)) $this->qt_esforcoReal->EditValue = ew_FormatNumber($this->qt_esforcoReal->EditValue, -2, -1, -2, 0);

			// qt_esforcoRealPm
			$this->qt_esforcoRealPm->EditCustomAttributes = "";
			$this->qt_esforcoRealPm->EditValue = ew_HtmlEncode($this->qt_esforcoRealPm->CurrentValue);
			$this->qt_esforcoRealPm->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_esforcoRealPm->FldCaption()));
			if (strval($this->qt_esforcoRealPm->EditValue) <> "" && is_numeric($this->qt_esforcoRealPm->EditValue)) $this->qt_esforcoRealPm->EditValue = ew_FormatNumber($this->qt_esforcoRealPm->EditValue, -2, -1, -2, 0);

			// qt_prazoRealM
			$this->qt_prazoRealM->EditCustomAttributes = "";
			$this->qt_prazoRealM->EditValue = ew_HtmlEncode($this->qt_prazoRealM->CurrentValue);
			$this->qt_prazoRealM->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_prazoRealM->FldCaption()));
			if (strval($this->qt_prazoRealM->EditValue) <> "" && is_numeric($this->qt_prazoRealM->EditValue)) $this->qt_prazoRealM->EditValue = ew_FormatNumber($this->qt_prazoRealM->EditValue, -2, -1, -2, 0);

			// ic_situacao
			$this->ic_situacao->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_situacao->FldTagValue(1), $this->ic_situacao->FldTagCaption(1) <> "" ? $this->ic_situacao->FldTagCaption(1) : $this->ic_situacao->FldTagValue(1));
			$arwrk[] = array($this->ic_situacao->FldTagValue(2), $this->ic_situacao->FldTagCaption(2) <> "" ? $this->ic_situacao->FldTagCaption(2) : $this->ic_situacao->FldTagValue(2));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_situacao->EditValue = $arwrk;

			// ds_acoes
			$this->ds_acoes->EditCustomAttributes = "";
			$this->ds_acoes->EditValue = $this->ds_acoes->CurrentValue;
			$this->ds_acoes->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_acoes->FldCaption()));

			// nu_usuarioAlt
			// dh_alteracao
			// Edit refer script
			// no_projeto

			$this->no_projeto->HrefValue = "";

			// ds_projeto
			$this->ds_projeto->HrefValue = "";

			// qt_pf
			$this->qt_pf->HrefValue = "";

			// qt_sloc
			$this->qt_sloc->HrefValue = "";

			// qt_slocPf
			$this->qt_slocPf->HrefValue = "";

			// qt_esforcoReal
			$this->qt_esforcoReal->HrefValue = "";

			// qt_esforcoRealPm
			$this->qt_esforcoRealPm->HrefValue = "";

			// qt_prazoRealM
			$this->qt_prazoRealM->HrefValue = "";

			// ic_situacao
			$this->ic_situacao->HrefValue = "";

			// ds_acoes
			$this->ds_acoes->HrefValue = "";

			// nu_usuarioAlt
			$this->nu_usuarioAlt->HrefValue = "";

			// dh_alteracao
			$this->dh_alteracao->HrefValue = "";
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
		if (!ew_CheckNumber($this->qt_pf->FormValue)) {
			ew_AddMessage($gsFormError, $this->qt_pf->FldErrMsg());
		}
		if (!ew_CheckNumber($this->qt_sloc->FormValue)) {
			ew_AddMessage($gsFormError, $this->qt_sloc->FldErrMsg());
		}
		if (!ew_CheckNumber($this->qt_slocPf->FormValue)) {
			ew_AddMessage($gsFormError, $this->qt_slocPf->FldErrMsg());
		}
		if (!ew_CheckNumber($this->qt_esforcoReal->FormValue)) {
			ew_AddMessage($gsFormError, $this->qt_esforcoReal->FldErrMsg());
		}
		if (!ew_CheckNumber($this->qt_esforcoRealPm->FormValue)) {
			ew_AddMessage($gsFormError, $this->qt_esforcoRealPm->FldErrMsg());
		}
		if (!ew_CheckNumber($this->qt_prazoRealM->FormValue)) {
			ew_AddMessage($gsFormError, $this->qt_prazoRealM->FldErrMsg());
		}
		if (!$this->ic_situacao->FldIsDetailKey && !is_null($this->ic_situacao->FormValue) && $this->ic_situacao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_situacao->FldCaption());
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

			// no_projeto
			$this->no_projeto->SetDbValueDef($rsnew, $this->no_projeto->CurrentValue, NULL, $this->no_projeto->ReadOnly);

			// ds_projeto
			$this->ds_projeto->SetDbValueDef($rsnew, $this->ds_projeto->CurrentValue, NULL, $this->ds_projeto->ReadOnly);

			// qt_pf
			$this->qt_pf->SetDbValueDef($rsnew, $this->qt_pf->CurrentValue, NULL, $this->qt_pf->ReadOnly);

			// qt_sloc
			$this->qt_sloc->SetDbValueDef($rsnew, $this->qt_sloc->CurrentValue, NULL, $this->qt_sloc->ReadOnly);

			// qt_slocPf
			$this->qt_slocPf->SetDbValueDef($rsnew, $this->qt_slocPf->CurrentValue, NULL, $this->qt_slocPf->ReadOnly);

			// qt_esforcoReal
			$this->qt_esforcoReal->SetDbValueDef($rsnew, $this->qt_esforcoReal->CurrentValue, NULL, $this->qt_esforcoReal->ReadOnly);

			// qt_esforcoRealPm
			$this->qt_esforcoRealPm->SetDbValueDef($rsnew, $this->qt_esforcoRealPm->CurrentValue, NULL, $this->qt_esforcoRealPm->ReadOnly);

			// qt_prazoRealM
			$this->qt_prazoRealM->SetDbValueDef($rsnew, $this->qt_prazoRealM->CurrentValue, NULL, $this->qt_prazoRealM->ReadOnly);

			// ic_situacao
			$this->ic_situacao->SetDbValueDef($rsnew, $this->ic_situacao->CurrentValue, NULL, $this->ic_situacao->ReadOnly);

			// ds_acoes
			$this->ds_acoes->SetDbValueDef($rsnew, $this->ds_acoes->CurrentValue, NULL, $this->ds_acoes->ReadOnly);

			// nu_usuarioAlt
			$this->nu_usuarioAlt->SetDbValueDef($rsnew, CurrentUserID(), NULL);
			$rsnew['nu_usuarioAlt'] = &$this->nu_usuarioAlt->DbValue;

			// dh_alteracao
			$this->dh_alteracao->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
			$rsnew['dh_alteracao'] = &$this->dh_alteracao->DbValue;

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
			if ($sMasterTblVar == "ambiente") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_ambiente"] <> "") {
					$GLOBALS["ambiente"]->nu_ambiente->setQueryStringValue($_GET["nu_ambiente"]);
					$this->nu_ambiente->setQueryStringValue($GLOBALS["ambiente"]->nu_ambiente->QueryStringValue);
					$this->nu_ambiente->setSessionValue($this->nu_ambiente->QueryStringValue);
					if (!is_numeric($GLOBALS["ambiente"]->nu_ambiente->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "ambiente") {
				if ($this->nu_ambiente->QueryStringValue == "") $this->nu_ambiente->setSessionValue("");
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "ambiente_phistoricolist.php", $this->TableVar);
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
if (!isset($ambiente_phistorico_edit)) $ambiente_phistorico_edit = new cambiente_phistorico_edit();

// Page init
$ambiente_phistorico_edit->Page_Init();

// Page main
$ambiente_phistorico_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$ambiente_phistorico_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var ambiente_phistorico_edit = new ew_Page("ambiente_phistorico_edit");
ambiente_phistorico_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = ambiente_phistorico_edit.PageID; // For backward compatibility

// Form object
var fambiente_phistoricoedit = new ew_Form("fambiente_phistoricoedit");

// Validate form
fambiente_phistoricoedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_qt_pf");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ambiente_phistorico->qt_pf->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_qt_sloc");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ambiente_phistorico->qt_sloc->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_qt_slocPf");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ambiente_phistorico->qt_slocPf->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_qt_esforcoReal");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ambiente_phistorico->qt_esforcoReal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_qt_esforcoRealPm");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ambiente_phistorico->qt_esforcoRealPm->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_qt_prazoRealM");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ambiente_phistorico->qt_prazoRealM->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ic_situacao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ambiente_phistorico->ic_situacao->FldCaption()) ?>");

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
fambiente_phistoricoedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fambiente_phistoricoedit.ValidateRequired = true;
<?php } else { ?>
fambiente_phistoricoedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fambiente_phistoricoedit.Lists["x_nu_usuarioAlt"] = {"LinkField":"x_nu_usuario","Ajax":true,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $ambiente_phistorico_edit->ShowPageHeader(); ?>
<?php
$ambiente_phistorico_edit->ShowMessage();
?>
<form name="fambiente_phistoricoedit" id="fambiente_phistoricoedit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="ambiente_phistorico">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_ambiente_phistoricoedit" class="table table-bordered table-striped">
<?php if ($ambiente_phistorico->no_projeto->Visible) { // no_projeto ?>
	<tr id="r_no_projeto">
		<td><span id="elh_ambiente_phistorico_no_projeto"><?php echo $ambiente_phistorico->no_projeto->FldCaption() ?></span></td>
		<td<?php echo $ambiente_phistorico->no_projeto->CellAttributes() ?>>
<span id="el_ambiente_phistorico_no_projeto" class="control-group">
<input type="text" data-field="x_no_projeto" name="x_no_projeto" id="x_no_projeto" size="30" maxlength="150" placeholder="<?php echo $ambiente_phistorico->no_projeto->PlaceHolder ?>" value="<?php echo $ambiente_phistorico->no_projeto->EditValue ?>"<?php echo $ambiente_phistorico->no_projeto->EditAttributes() ?>>
</span>
<?php echo $ambiente_phistorico->no_projeto->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_phistorico->ds_projeto->Visible) { // ds_projeto ?>
	<tr id="r_ds_projeto">
		<td><span id="elh_ambiente_phistorico_ds_projeto"><?php echo $ambiente_phistorico->ds_projeto->FldCaption() ?></span></td>
		<td<?php echo $ambiente_phistorico->ds_projeto->CellAttributes() ?>>
<span id="el_ambiente_phistorico_ds_projeto" class="control-group">
<textarea data-field="x_ds_projeto" name="x_ds_projeto" id="x_ds_projeto" cols="35" rows="4" placeholder="<?php echo $ambiente_phistorico->ds_projeto->PlaceHolder ?>"<?php echo $ambiente_phistorico->ds_projeto->EditAttributes() ?>><?php echo $ambiente_phistorico->ds_projeto->EditValue ?></textarea>
</span>
<?php echo $ambiente_phistorico->ds_projeto->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_phistorico->qt_pf->Visible) { // qt_pf ?>
	<tr id="r_qt_pf">
		<td><span id="elh_ambiente_phistorico_qt_pf"><?php echo $ambiente_phistorico->qt_pf->FldCaption() ?></span></td>
		<td<?php echo $ambiente_phistorico->qt_pf->CellAttributes() ?>>
<span id="el_ambiente_phistorico_qt_pf" class="control-group">
<input type="text" data-field="x_qt_pf" name="x_qt_pf" id="x_qt_pf" size="30" placeholder="<?php echo $ambiente_phistorico->qt_pf->PlaceHolder ?>" value="<?php echo $ambiente_phistorico->qt_pf->EditValue ?>"<?php echo $ambiente_phistorico->qt_pf->EditAttributes() ?>>
</span>
<?php echo $ambiente_phistorico->qt_pf->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_phistorico->qt_sloc->Visible) { // qt_sloc ?>
	<tr id="r_qt_sloc">
		<td><span id="elh_ambiente_phistorico_qt_sloc"><?php echo $ambiente_phistorico->qt_sloc->FldCaption() ?></span></td>
		<td<?php echo $ambiente_phistorico->qt_sloc->CellAttributes() ?>>
<span id="el_ambiente_phistorico_qt_sloc" class="control-group">
<input type="text" data-field="x_qt_sloc" name="x_qt_sloc" id="x_qt_sloc" size="30" placeholder="<?php echo $ambiente_phistorico->qt_sloc->PlaceHolder ?>" value="<?php echo $ambiente_phistorico->qt_sloc->EditValue ?>"<?php echo $ambiente_phistorico->qt_sloc->EditAttributes() ?>>
</span>
<?php echo $ambiente_phistorico->qt_sloc->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_phistorico->qt_slocPf->Visible) { // qt_slocPf ?>
	<tr id="r_qt_slocPf">
		<td><span id="elh_ambiente_phistorico_qt_slocPf"><?php echo $ambiente_phistorico->qt_slocPf->FldCaption() ?></span></td>
		<td<?php echo $ambiente_phistorico->qt_slocPf->CellAttributes() ?>>
<span id="el_ambiente_phistorico_qt_slocPf" class="control-group">
<input type="text" data-field="x_qt_slocPf" name="x_qt_slocPf" id="x_qt_slocPf" size="30" placeholder="<?php echo $ambiente_phistorico->qt_slocPf->PlaceHolder ?>" value="<?php echo $ambiente_phistorico->qt_slocPf->EditValue ?>"<?php echo $ambiente_phistorico->qt_slocPf->EditAttributes() ?>>
</span>
<?php echo $ambiente_phistorico->qt_slocPf->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_phistorico->qt_esforcoReal->Visible) { // qt_esforcoReal ?>
	<tr id="r_qt_esforcoReal">
		<td><span id="elh_ambiente_phistorico_qt_esforcoReal"><?php echo $ambiente_phistorico->qt_esforcoReal->FldCaption() ?></span></td>
		<td<?php echo $ambiente_phistorico->qt_esforcoReal->CellAttributes() ?>>
<span id="el_ambiente_phistorico_qt_esforcoReal" class="control-group">
<input type="text" data-field="x_qt_esforcoReal" name="x_qt_esforcoReal" id="x_qt_esforcoReal" size="30" placeholder="<?php echo $ambiente_phistorico->qt_esforcoReal->PlaceHolder ?>" value="<?php echo $ambiente_phistorico->qt_esforcoReal->EditValue ?>"<?php echo $ambiente_phistorico->qt_esforcoReal->EditAttributes() ?>>
</span>
<?php echo $ambiente_phistorico->qt_esforcoReal->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_phistorico->qt_esforcoRealPm->Visible) { // qt_esforcoRealPm ?>
	<tr id="r_qt_esforcoRealPm">
		<td><span id="elh_ambiente_phistorico_qt_esforcoRealPm"><?php echo $ambiente_phistorico->qt_esforcoRealPm->FldCaption() ?></span></td>
		<td<?php echo $ambiente_phistorico->qt_esforcoRealPm->CellAttributes() ?>>
<span id="el_ambiente_phistorico_qt_esforcoRealPm" class="control-group">
<input type="text" data-field="x_qt_esforcoRealPm" name="x_qt_esforcoRealPm" id="x_qt_esforcoRealPm" size="30" placeholder="<?php echo $ambiente_phistorico->qt_esforcoRealPm->PlaceHolder ?>" value="<?php echo $ambiente_phistorico->qt_esforcoRealPm->EditValue ?>"<?php echo $ambiente_phistorico->qt_esforcoRealPm->EditAttributes() ?>>
</span>
<?php echo $ambiente_phistorico->qt_esforcoRealPm->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_phistorico->qt_prazoRealM->Visible) { // qt_prazoRealM ?>
	<tr id="r_qt_prazoRealM">
		<td><span id="elh_ambiente_phistorico_qt_prazoRealM"><?php echo $ambiente_phistorico->qt_prazoRealM->FldCaption() ?></span></td>
		<td<?php echo $ambiente_phistorico->qt_prazoRealM->CellAttributes() ?>>
<span id="el_ambiente_phistorico_qt_prazoRealM" class="control-group">
<input type="text" data-field="x_qt_prazoRealM" name="x_qt_prazoRealM" id="x_qt_prazoRealM" size="30" placeholder="<?php echo $ambiente_phistorico->qt_prazoRealM->PlaceHolder ?>" value="<?php echo $ambiente_phistorico->qt_prazoRealM->EditValue ?>"<?php echo $ambiente_phistorico->qt_prazoRealM->EditAttributes() ?>>
</span>
<?php echo $ambiente_phistorico->qt_prazoRealM->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_phistorico->ic_situacao->Visible) { // ic_situacao ?>
	<tr id="r_ic_situacao">
		<td><span id="elh_ambiente_phistorico_ic_situacao"><?php echo $ambiente_phistorico->ic_situacao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $ambiente_phistorico->ic_situacao->CellAttributes() ?>>
<span id="el_ambiente_phistorico_ic_situacao" class="control-group">
<select data-field="x_ic_situacao" id="x_ic_situacao" name="x_ic_situacao"<?php echo $ambiente_phistorico->ic_situacao->EditAttributes() ?>>
<?php
if (is_array($ambiente_phistorico->ic_situacao->EditValue)) {
	$arwrk = $ambiente_phistorico->ic_situacao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_phistorico->ic_situacao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $ambiente_phistorico->ic_situacao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_phistorico->ds_acoes->Visible) { // ds_acoes ?>
	<tr id="r_ds_acoes">
		<td><span id="elh_ambiente_phistorico_ds_acoes"><?php echo $ambiente_phistorico->ds_acoes->FldCaption() ?></span></td>
		<td<?php echo $ambiente_phistorico->ds_acoes->CellAttributes() ?>>
<span id="el_ambiente_phistorico_ds_acoes" class="control-group">
<textarea data-field="x_ds_acoes" name="x_ds_acoes" id="x_ds_acoes" cols="35" rows="4" placeholder="<?php echo $ambiente_phistorico->ds_acoes->PlaceHolder ?>"<?php echo $ambiente_phistorico->ds_acoes->EditAttributes() ?>><?php echo $ambiente_phistorico->ds_acoes->EditValue ?></textarea>
</span>
<?php echo $ambiente_phistorico->ds_acoes->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<input type="hidden" data-field="x_nu_projhist" name="x_nu_projhist" id="x_nu_projhist" value="<?php echo ew_HtmlEncode($ambiente_phistorico->nu_projhist->CurrentValue) ?>">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fambiente_phistoricoedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$ambiente_phistorico_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$ambiente_phistorico_edit->Page_Terminate();
?>
