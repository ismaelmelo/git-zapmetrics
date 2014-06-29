<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "demanda_artefatoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$demanda_artefato_edit = NULL; // Initialize page object first

class cdemanda_artefato_edit extends cdemanda_artefato {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'demanda_artefato';

	// Page object name
	var $PageObjName = 'demanda_artefato_edit';

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

		// Table object (demanda_artefato)
		if (!isset($GLOBALS["demanda_artefato"])) {
			$GLOBALS["demanda_artefato"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["demanda_artefato"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'demanda_artefato', TRUE);

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
			$this->Page_Terminate("demanda_artefatolist.php");
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
		if (@$_GET["nu_artefato"] <> "") {
			$this->nu_artefato->setQueryStringValue($_GET["nu_artefato"]);
		}

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
		if ($this->nu_artefato->CurrentValue == "")
			$this->Page_Terminate("demanda_artefatolist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("demanda_artefatolist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "demanda_artefatoview.php")
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
		if (!$this->nu_demanda->FldIsDetailKey) {
			$this->nu_demanda->setFormValue($objForm->GetValue("x_nu_demanda"));
		}
		if (!$this->ic_tpArtefato->FldIsDetailKey) {
			$this->ic_tpArtefato->setFormValue($objForm->GetValue("x_ic_tpArtefato"));
		}
		if (!$this->no_local->FldIsDetailKey) {
			$this->no_local->setFormValue($objForm->GetValue("x_no_local"));
		}
		if (!$this->im_anexo->FldIsDetailKey) {
			$this->im_anexo->setFormValue($objForm->GetValue("x_im_anexo"));
		}
		if (!$this->ic_situacao->FldIsDetailKey) {
			$this->ic_situacao->setFormValue($objForm->GetValue("x_ic_situacao"));
		}
		if (!$this->nu_pessoaResp->FldIsDetailKey) {
			$this->nu_pessoaResp->setFormValue($objForm->GetValue("x_nu_pessoaResp"));
		}
		if (!$this->nu_artefato->FldIsDetailKey)
			$this->nu_artefato->setFormValue($objForm->GetValue("x_nu_artefato"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->nu_artefato->CurrentValue = $this->nu_artefato->FormValue;
		$this->nu_demanda->CurrentValue = $this->nu_demanda->FormValue;
		$this->ic_tpArtefato->CurrentValue = $this->ic_tpArtefato->FormValue;
		$this->no_local->CurrentValue = $this->no_local->FormValue;
		$this->im_anexo->CurrentValue = $this->im_anexo->FormValue;
		$this->ic_situacao->CurrentValue = $this->ic_situacao->FormValue;
		$this->nu_pessoaResp->CurrentValue = $this->nu_pessoaResp->FormValue;
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
		$this->nu_artefato->setDbValue($rs->fields('nu_artefato'));
		$this->nu_demanda->setDbValue($rs->fields('nu_demanda'));
		$this->ic_tpArtefato->setDbValue($rs->fields('ic_tpArtefato'));
		$this->no_local->setDbValue($rs->fields('no_local'));
		$this->im_anexo->setDbValue($rs->fields('im_anexo'));
		$this->ic_situacao->setDbValue($rs->fields('ic_situacao'));
		$this->nu_pessoaResp->setDbValue($rs->fields('nu_pessoaResp'));
		$this->nu_usuario->setDbValue($rs->fields('nu_usuario'));
		$this->ts_datahora->setDbValue($rs->fields('ts_datahora'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_artefato->DbValue = $row['nu_artefato'];
		$this->nu_demanda->DbValue = $row['nu_demanda'];
		$this->ic_tpArtefato->DbValue = $row['ic_tpArtefato'];
		$this->no_local->DbValue = $row['no_local'];
		$this->im_anexo->DbValue = $row['im_anexo'];
		$this->ic_situacao->DbValue = $row['ic_situacao'];
		$this->nu_pessoaResp->DbValue = $row['nu_pessoaResp'];
		$this->nu_usuario->DbValue = $row['nu_usuario'];
		$this->ts_datahora->DbValue = $row['ts_datahora'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_artefato
		// nu_demanda
		// ic_tpArtefato
		// no_local
		// im_anexo
		// ic_situacao
		// nu_pessoaResp
		// nu_usuario
		// ts_datahora

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_artefato
			$this->nu_artefato->ViewValue = $this->nu_artefato->CurrentValue;
			$this->nu_artefato->ViewCustomAttributes = "";

			// nu_demanda
			if (strval($this->nu_demanda->CurrentValue) <> "") {
				$sFilterWrk = "[nu_demanda]" . ew_SearchString("=", $this->nu_demanda->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_demanda], [nu_demanda] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[demanda]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_demanda, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_demanda->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_demanda->ViewValue = $this->nu_demanda->CurrentValue;
				}
			} else {
				$this->nu_demanda->ViewValue = NULL;
			}
			$this->nu_demanda->ViewCustomAttributes = "";

			// ic_tpArtefato
			if (strval($this->ic_tpArtefato->CurrentValue) <> "") {
				switch ($this->ic_tpArtefato->CurrentValue) {
					case $this->ic_tpArtefato->FldTagValue(1):
						$this->ic_tpArtefato->ViewValue = $this->ic_tpArtefato->FldTagCaption(1) <> "" ? $this->ic_tpArtefato->FldTagCaption(1) : $this->ic_tpArtefato->CurrentValue;
						break;
					case $this->ic_tpArtefato->FldTagValue(2):
						$this->ic_tpArtefato->ViewValue = $this->ic_tpArtefato->FldTagCaption(2) <> "" ? $this->ic_tpArtefato->FldTagCaption(2) : $this->ic_tpArtefato->CurrentValue;
						break;
					case $this->ic_tpArtefato->FldTagValue(3):
						$this->ic_tpArtefato->ViewValue = $this->ic_tpArtefato->FldTagCaption(3) <> "" ? $this->ic_tpArtefato->FldTagCaption(3) : $this->ic_tpArtefato->CurrentValue;
						break;
					case $this->ic_tpArtefato->FldTagValue(4):
						$this->ic_tpArtefato->ViewValue = $this->ic_tpArtefato->FldTagCaption(4) <> "" ? $this->ic_tpArtefato->FldTagCaption(4) : $this->ic_tpArtefato->CurrentValue;
						break;
					case $this->ic_tpArtefato->FldTagValue(5):
						$this->ic_tpArtefato->ViewValue = $this->ic_tpArtefato->FldTagCaption(5) <> "" ? $this->ic_tpArtefato->FldTagCaption(5) : $this->ic_tpArtefato->CurrentValue;
						break;
					case $this->ic_tpArtefato->FldTagValue(6):
						$this->ic_tpArtefato->ViewValue = $this->ic_tpArtefato->FldTagCaption(6) <> "" ? $this->ic_tpArtefato->FldTagCaption(6) : $this->ic_tpArtefato->CurrentValue;
						break;
					case $this->ic_tpArtefato->FldTagValue(7):
						$this->ic_tpArtefato->ViewValue = $this->ic_tpArtefato->FldTagCaption(7) <> "" ? $this->ic_tpArtefato->FldTagCaption(7) : $this->ic_tpArtefato->CurrentValue;
						break;
					case $this->ic_tpArtefato->FldTagValue(8):
						$this->ic_tpArtefato->ViewValue = $this->ic_tpArtefato->FldTagCaption(8) <> "" ? $this->ic_tpArtefato->FldTagCaption(8) : $this->ic_tpArtefato->CurrentValue;
						break;
					default:
						$this->ic_tpArtefato->ViewValue = $this->ic_tpArtefato->CurrentValue;
				}
			} else {
				$this->ic_tpArtefato->ViewValue = NULL;
			}
			$this->ic_tpArtefato->ViewCustomAttributes = "";

			// no_local
			$this->no_local->ViewValue = $this->no_local->CurrentValue;
			$this->no_local->ViewCustomAttributes = "";

			// im_anexo
			$this->im_anexo->ViewValue = $this->im_anexo->CurrentValue;
			$this->im_anexo->ViewCustomAttributes = "";

			// ic_situacao
			$this->ic_situacao->ViewValue = $this->ic_situacao->CurrentValue;
			$this->ic_situacao->ViewCustomAttributes = "";

			// nu_pessoaResp
			if (strval($this->nu_pessoaResp->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_pessoaResp->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_pessoaResp, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_pessoaResp->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_pessoaResp->ViewValue = $this->nu_pessoaResp->CurrentValue;
				}
			} else {
				$this->nu_pessoaResp->ViewValue = NULL;
			}
			$this->nu_pessoaResp->ViewCustomAttributes = "";

			// nu_usuario
			$this->nu_usuario->ViewValue = $this->nu_usuario->CurrentValue;
			$this->nu_usuario->ViewCustomAttributes = "";

			// ts_datahora
			$this->ts_datahora->ViewValue = $this->ts_datahora->CurrentValue;
			$this->ts_datahora->ViewValue = ew_FormatDateTime($this->ts_datahora->ViewValue, 7);
			$this->ts_datahora->ViewCustomAttributes = "";

			// nu_demanda
			$this->nu_demanda->LinkCustomAttributes = "";
			$this->nu_demanda->HrefValue = "";
			$this->nu_demanda->TooltipValue = "";

			// ic_tpArtefato
			$this->ic_tpArtefato->LinkCustomAttributes = "";
			$this->ic_tpArtefato->HrefValue = "";
			$this->ic_tpArtefato->TooltipValue = "";

			// no_local
			$this->no_local->LinkCustomAttributes = "";
			$this->no_local->HrefValue = "";
			$this->no_local->TooltipValue = "";

			// im_anexo
			$this->im_anexo->LinkCustomAttributes = "";
			$this->im_anexo->HrefValue = "";
			$this->im_anexo->TooltipValue = "";

			// ic_situacao
			$this->ic_situacao->LinkCustomAttributes = "";
			$this->ic_situacao->HrefValue = "";
			$this->ic_situacao->TooltipValue = "";

			// nu_pessoaResp
			$this->nu_pessoaResp->LinkCustomAttributes = "";
			$this->nu_pessoaResp->HrefValue = "";
			$this->nu_pessoaResp->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// nu_demanda
			$this->nu_demanda->EditCustomAttributes = "";
			if (strval($this->nu_demanda->CurrentValue) <> "") {
				$sFilterWrk = "[nu_demanda]" . ew_SearchString("=", $this->nu_demanda->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_demanda], [nu_demanda] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[demanda]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_demanda, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_demanda->EditValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_demanda->EditValue = $this->nu_demanda->CurrentValue;
				}
			} else {
				$this->nu_demanda->EditValue = NULL;
			}
			$this->nu_demanda->ViewCustomAttributes = "";

			// ic_tpArtefato
			$this->ic_tpArtefato->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_tpArtefato->FldTagValue(1), $this->ic_tpArtefato->FldTagCaption(1) <> "" ? $this->ic_tpArtefato->FldTagCaption(1) : $this->ic_tpArtefato->FldTagValue(1));
			$arwrk[] = array($this->ic_tpArtefato->FldTagValue(2), $this->ic_tpArtefato->FldTagCaption(2) <> "" ? $this->ic_tpArtefato->FldTagCaption(2) : $this->ic_tpArtefato->FldTagValue(2));
			$arwrk[] = array($this->ic_tpArtefato->FldTagValue(3), $this->ic_tpArtefato->FldTagCaption(3) <> "" ? $this->ic_tpArtefato->FldTagCaption(3) : $this->ic_tpArtefato->FldTagValue(3));
			$arwrk[] = array($this->ic_tpArtefato->FldTagValue(4), $this->ic_tpArtefato->FldTagCaption(4) <> "" ? $this->ic_tpArtefato->FldTagCaption(4) : $this->ic_tpArtefato->FldTagValue(4));
			$arwrk[] = array($this->ic_tpArtefato->FldTagValue(5), $this->ic_tpArtefato->FldTagCaption(5) <> "" ? $this->ic_tpArtefato->FldTagCaption(5) : $this->ic_tpArtefato->FldTagValue(5));
			$arwrk[] = array($this->ic_tpArtefato->FldTagValue(6), $this->ic_tpArtefato->FldTagCaption(6) <> "" ? $this->ic_tpArtefato->FldTagCaption(6) : $this->ic_tpArtefato->FldTagValue(6));
			$arwrk[] = array($this->ic_tpArtefato->FldTagValue(7), $this->ic_tpArtefato->FldTagCaption(7) <> "" ? $this->ic_tpArtefato->FldTagCaption(7) : $this->ic_tpArtefato->FldTagValue(7));
			$arwrk[] = array($this->ic_tpArtefato->FldTagValue(8), $this->ic_tpArtefato->FldTagCaption(8) <> "" ? $this->ic_tpArtefato->FldTagCaption(8) : $this->ic_tpArtefato->FldTagValue(8));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_tpArtefato->EditValue = $arwrk;

			// no_local
			$this->no_local->EditCustomAttributes = "";
			$this->no_local->EditValue = ew_HtmlEncode($this->no_local->CurrentValue);
			$this->no_local->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_local->FldCaption()));

			// im_anexo
			$this->im_anexo->EditCustomAttributes = "";
			$this->im_anexo->EditValue = ew_HtmlEncode($this->im_anexo->CurrentValue);
			$this->im_anexo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->im_anexo->FldCaption()));

			// ic_situacao
			$this->ic_situacao->EditCustomAttributes = "";
			$this->ic_situacao->EditValue = ew_HtmlEncode($this->ic_situacao->CurrentValue);
			$this->ic_situacao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ic_situacao->FldCaption()));

			// nu_pessoaResp
			$this->nu_pessoaResp->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if (!$GLOBALS["demanda_artefato"]->UserIDAllow("edit")) $sWhereWrk = $GLOBALS["usuario"]->AddUserIDFilter($sWhereWrk);

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_pessoaResp, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_pessoaResp->EditValue = $arwrk;

			// Edit refer script
			// nu_demanda

			$this->nu_demanda->HrefValue = "";

			// ic_tpArtefato
			$this->ic_tpArtefato->HrefValue = "";

			// no_local
			$this->no_local->HrefValue = "";

			// im_anexo
			$this->im_anexo->HrefValue = "";

			// ic_situacao
			$this->ic_situacao->HrefValue = "";

			// nu_pessoaResp
			$this->nu_pessoaResp->HrefValue = "";
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
		if (!$this->ic_tpArtefato->FldIsDetailKey && !is_null($this->ic_tpArtefato->FormValue) && $this->ic_tpArtefato->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_tpArtefato->FldCaption());
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

			// ic_tpArtefato
			$this->ic_tpArtefato->SetDbValueDef($rsnew, $this->ic_tpArtefato->CurrentValue, "", $this->ic_tpArtefato->ReadOnly);

			// no_local
			$this->no_local->SetDbValueDef($rsnew, $this->no_local->CurrentValue, NULL, $this->no_local->ReadOnly);

			// im_anexo
			$this->im_anexo->SetDbValueDef($rsnew, $this->im_anexo->CurrentValue, NULL, $this->im_anexo->ReadOnly);

			// ic_situacao
			$this->ic_situacao->SetDbValueDef($rsnew, $this->ic_situacao->CurrentValue, NULL, $this->ic_situacao->ReadOnly);

			// nu_pessoaResp
			$this->nu_pessoaResp->SetDbValueDef($rsnew, $this->nu_pessoaResp->CurrentValue, NULL, $this->nu_pessoaResp->ReadOnly);

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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "demanda_artefatolist.php", $this->TableVar);
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
if (!isset($demanda_artefato_edit)) $demanda_artefato_edit = new cdemanda_artefato_edit();

// Page init
$demanda_artefato_edit->Page_Init();

// Page main
$demanda_artefato_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$demanda_artefato_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var demanda_artefato_edit = new ew_Page("demanda_artefato_edit");
demanda_artefato_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = demanda_artefato_edit.PageID; // For backward compatibility

// Form object
var fdemanda_artefatoedit = new ew_Form("fdemanda_artefatoedit");

// Validate form
fdemanda_artefatoedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_ic_tpArtefato");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($demanda_artefato->ic_tpArtefato->FldCaption()) ?>");

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
fdemanda_artefatoedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdemanda_artefatoedit.ValidateRequired = true;
<?php } else { ?>
fdemanda_artefatoedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fdemanda_artefatoedit.Lists["x_nu_demanda"] = {"LinkField":"x_nu_demanda","Ajax":null,"AutoFill":false,"DisplayFields":["x_nu_demanda","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fdemanda_artefatoedit.Lists["x_nu_pessoaResp"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $demanda_artefato_edit->ShowPageHeader(); ?>
<?php
$demanda_artefato_edit->ShowMessage();
?>
<form name="fdemanda_artefatoedit" id="fdemanda_artefatoedit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="demanda_artefato">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_demanda_artefatoedit" class="table table-bordered table-striped">
<?php if ($demanda_artefato->nu_demanda->Visible) { // nu_demanda ?>
	<tr id="r_nu_demanda">
		<td><span id="elh_demanda_artefato_nu_demanda"><?php echo $demanda_artefato->nu_demanda->FldCaption() ?></span></td>
		<td<?php echo $demanda_artefato->nu_demanda->CellAttributes() ?>>
<span id="el_demanda_artefato_nu_demanda" class="control-group">
<span<?php echo $demanda_artefato->nu_demanda->ViewAttributes() ?>>
<?php echo $demanda_artefato->nu_demanda->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_demanda" name="x_nu_demanda" id="x_nu_demanda" value="<?php echo ew_HtmlEncode($demanda_artefato->nu_demanda->CurrentValue) ?>">
<?php echo $demanda_artefato->nu_demanda->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($demanda_artefato->ic_tpArtefato->Visible) { // ic_tpArtefato ?>
	<tr id="r_ic_tpArtefato">
		<td><span id="elh_demanda_artefato_ic_tpArtefato"><?php echo $demanda_artefato->ic_tpArtefato->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $demanda_artefato->ic_tpArtefato->CellAttributes() ?>>
<span id="el_demanda_artefato_ic_tpArtefato" class="control-group">
<select data-field="x_ic_tpArtefato" id="x_ic_tpArtefato" name="x_ic_tpArtefato"<?php echo $demanda_artefato->ic_tpArtefato->EditAttributes() ?>>
<?php
if (is_array($demanda_artefato->ic_tpArtefato->EditValue)) {
	$arwrk = $demanda_artefato->ic_tpArtefato->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($demanda_artefato->ic_tpArtefato->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $demanda_artefato->ic_tpArtefato->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($demanda_artefato->no_local->Visible) { // no_local ?>
	<tr id="r_no_local">
		<td><span id="elh_demanda_artefato_no_local"><?php echo $demanda_artefato->no_local->FldCaption() ?></span></td>
		<td<?php echo $demanda_artefato->no_local->CellAttributes() ?>>
<span id="el_demanda_artefato_no_local" class="control-group">
<input type="text" data-field="x_no_local" name="x_no_local" id="x_no_local" size="30" maxlength="255" placeholder="<?php echo $demanda_artefato->no_local->PlaceHolder ?>" value="<?php echo $demanda_artefato->no_local->EditValue ?>"<?php echo $demanda_artefato->no_local->EditAttributes() ?>>
</span>
<?php echo $demanda_artefato->no_local->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($demanda_artefato->im_anexo->Visible) { // im_anexo ?>
	<tr id="r_im_anexo">
		<td><span id="elh_demanda_artefato_im_anexo"><?php echo $demanda_artefato->im_anexo->FldCaption() ?></span></td>
		<td<?php echo $demanda_artefato->im_anexo->CellAttributes() ?>>
<span id="el_demanda_artefato_im_anexo" class="control-group">
<input type="text" data-field="x_im_anexo" name="x_im_anexo" id="x_im_anexo" size="30" maxlength="255" placeholder="<?php echo $demanda_artefato->im_anexo->PlaceHolder ?>" value="<?php echo $demanda_artefato->im_anexo->EditValue ?>"<?php echo $demanda_artefato->im_anexo->EditAttributes() ?>>
</span>
<?php echo $demanda_artefato->im_anexo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($demanda_artefato->ic_situacao->Visible) { // ic_situacao ?>
	<tr id="r_ic_situacao">
		<td><span id="elh_demanda_artefato_ic_situacao"><?php echo $demanda_artefato->ic_situacao->FldCaption() ?></span></td>
		<td<?php echo $demanda_artefato->ic_situacao->CellAttributes() ?>>
<span id="el_demanda_artefato_ic_situacao" class="control-group">
<input type="text" data-field="x_ic_situacao" name="x_ic_situacao" id="x_ic_situacao" size="30" placeholder="<?php echo $demanda_artefato->ic_situacao->PlaceHolder ?>" value="<?php echo $demanda_artefato->ic_situacao->EditValue ?>"<?php echo $demanda_artefato->ic_situacao->EditAttributes() ?>>
</span>
<?php echo $demanda_artefato->ic_situacao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($demanda_artefato->nu_pessoaResp->Visible) { // nu_pessoaResp ?>
	<tr id="r_nu_pessoaResp">
		<td><span id="elh_demanda_artefato_nu_pessoaResp"><?php echo $demanda_artefato->nu_pessoaResp->FldCaption() ?></span></td>
		<td<?php echo $demanda_artefato->nu_pessoaResp->CellAttributes() ?>>
<span id="el_demanda_artefato_nu_pessoaResp" class="control-group">
<select data-field="x_nu_pessoaResp" id="x_nu_pessoaResp" name="x_nu_pessoaResp"<?php echo $demanda_artefato->nu_pessoaResp->EditAttributes() ?>>
<?php
if (is_array($demanda_artefato->nu_pessoaResp->EditValue)) {
	$arwrk = $demanda_artefato->nu_pessoaResp->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($demanda_artefato->nu_pessoaResp->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fdemanda_artefatoedit.Lists["x_nu_pessoaResp"].Options = <?php echo (is_array($demanda_artefato->nu_pessoaResp->EditValue)) ? ew_ArrayToJson($demanda_artefato->nu_pessoaResp->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $demanda_artefato->nu_pessoaResp->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<input type="hidden" data-field="x_nu_artefato" name="x_nu_artefato" id="x_nu_artefato" value="<?php echo ew_HtmlEncode($demanda_artefato->nu_artefato->CurrentValue) ?>">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fdemanda_artefatoedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$demanda_artefato_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$demanda_artefato_edit->Page_Terminate();
?>
