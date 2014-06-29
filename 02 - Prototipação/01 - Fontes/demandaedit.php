<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "demandainfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$demanda_edit = NULL; // Initialize page object first

class cdemanda_edit extends cdemanda {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'demanda';

	// Page object name
	var $PageObjName = 'demanda_edit';

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

		// Table object (demanda)
		if (!isset($GLOBALS["demanda"])) {
			$GLOBALS["demanda"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["demanda"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'demanda', TRUE);

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
			$this->Page_Terminate("demandalist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action
		$this->nu_demanda->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		if (@$_GET["nu_demanda"] <> "") {
			$this->nu_demanda->setQueryStringValue($_GET["nu_demanda"]);
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
		if ($this->nu_demanda->CurrentValue == "")
			$this->Page_Terminate("demandalist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("demandalist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "demandaview.php")
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
		$this->im_anexo->Upload->Index = $objForm->Index;
		if ($this->im_anexo->Upload->UploadFile()) {

			// No action required
		} else {
			echo $this->im_anexo->Upload->Message;
			$this->Page_Terminate();
			exit();
		}
		$this->im_anexo->CurrentValue = $this->im_anexo->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->nu_demanda->FldIsDetailKey)
			$this->nu_demanda->setFormValue($objForm->GetValue("x_nu_demanda"));
		if (!$this->ds_demanda->FldIsDetailKey) {
			$this->ds_demanda->setFormValue($objForm->GetValue("x_ds_demanda"));
		}
		if (!$this->nu_pessoaResponsavel->FldIsDetailKey) {
			$this->nu_pessoaResponsavel->setFormValue($objForm->GetValue("x_nu_pessoaResponsavel"));
		}
		if (!$this->nu_itemPDTI->FldIsDetailKey) {
			$this->nu_itemPDTI->setFormValue($objForm->GetValue("x_nu_itemPDTI"));
		}
		if (!$this->dt_registro->FldIsDetailKey) {
			$this->dt_registro->setFormValue($objForm->GetValue("x_dt_registro"));
			$this->dt_registro->CurrentValue = ew_UnFormatDateTime($this->dt_registro->CurrentValue, 7);
		}
		if (!$this->ic_situacao->FldIsDetailKey) {
			$this->ic_situacao->setFormValue($objForm->GetValue("x_ic_situacao"));
		}
		if (!$this->dt_aprovacao->FldIsDetailKey) {
			$this->dt_aprovacao->setFormValue($objForm->GetValue("x_dt_aprovacao"));
			$this->dt_aprovacao->CurrentValue = ew_UnFormatDateTime($this->dt_aprovacao->CurrentValue, 7);
		}
		if (!$this->nu_pessoaAprovadora->FldIsDetailKey) {
			$this->nu_pessoaAprovadora->setFormValue($objForm->GetValue("x_nu_pessoaAprovadora"));
		}
		if (!$this->ts_datahora->FldIsDetailKey) {
			$this->ts_datahora->setFormValue($objForm->GetValue("x_ts_datahora"));
			$this->ts_datahora->CurrentValue = ew_UnFormatDateTime($this->ts_datahora->CurrentValue, 7);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->nu_demanda->CurrentValue = $this->nu_demanda->FormValue;
		$this->ds_demanda->CurrentValue = $this->ds_demanda->FormValue;
		$this->nu_pessoaResponsavel->CurrentValue = $this->nu_pessoaResponsavel->FormValue;
		$this->nu_itemPDTI->CurrentValue = $this->nu_itemPDTI->FormValue;
		$this->dt_registro->CurrentValue = $this->dt_registro->FormValue;
		$this->dt_registro->CurrentValue = ew_UnFormatDateTime($this->dt_registro->CurrentValue, 7);
		$this->ic_situacao->CurrentValue = $this->ic_situacao->FormValue;
		$this->dt_aprovacao->CurrentValue = $this->dt_aprovacao->FormValue;
		$this->dt_aprovacao->CurrentValue = ew_UnFormatDateTime($this->dt_aprovacao->CurrentValue, 7);
		$this->nu_pessoaAprovadora->CurrentValue = $this->nu_pessoaAprovadora->FormValue;
		$this->ts_datahora->CurrentValue = $this->ts_datahora->FormValue;
		$this->ts_datahora->CurrentValue = ew_UnFormatDateTime($this->ts_datahora->CurrentValue, 7);
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
		$this->nu_demanda->setDbValue($rs->fields('nu_demanda'));
		$this->ds_demanda->setDbValue($rs->fields('ds_demanda'));
		$this->nu_pessoaResponsavel->setDbValue($rs->fields('nu_pessoaResponsavel'));
		$this->nu_itemPDTI->setDbValue($rs->fields('nu_itemPDTI'));
		$this->dt_registro->setDbValue($rs->fields('dt_registro'));
		$this->im_anexo->Upload->DbValue = $rs->fields('im_anexo');
		$this->ic_situacao->setDbValue($rs->fields('ic_situacao'));
		$this->dt_aprovacao->setDbValue($rs->fields('dt_aprovacao'));
		$this->nu_pessoaAprovadora->setDbValue($rs->fields('nu_pessoaAprovadora'));
		$this->nu_usuario->setDbValue($rs->fields('nu_usuario'));
		$this->ts_datahora->setDbValue($rs->fields('ts_datahora'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_demanda->DbValue = $row['nu_demanda'];
		$this->ds_demanda->DbValue = $row['ds_demanda'];
		$this->nu_pessoaResponsavel->DbValue = $row['nu_pessoaResponsavel'];
		$this->nu_itemPDTI->DbValue = $row['nu_itemPDTI'];
		$this->dt_registro->DbValue = $row['dt_registro'];
		$this->im_anexo->Upload->DbValue = $row['im_anexo'];
		$this->ic_situacao->DbValue = $row['ic_situacao'];
		$this->dt_aprovacao->DbValue = $row['dt_aprovacao'];
		$this->nu_pessoaAprovadora->DbValue = $row['nu_pessoaAprovadora'];
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
		// nu_demanda
		// ds_demanda
		// nu_pessoaResponsavel
		// nu_itemPDTI
		// dt_registro
		// im_anexo
		// ic_situacao
		// dt_aprovacao
		// nu_pessoaAprovadora
		// nu_usuario
		// ts_datahora

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_demanda
			$this->nu_demanda->ViewValue = $this->nu_demanda->CurrentValue;
			$this->nu_demanda->ViewCustomAttributes = "";

			// ds_demanda
			$this->ds_demanda->ViewValue = $this->ds_demanda->CurrentValue;
			$this->ds_demanda->ViewCustomAttributes = "";

			// nu_pessoaResponsavel
			if (strval($this->nu_pessoaResponsavel->CurrentValue) <> "") {
				$sFilterWrk = "[nu_pessoa]" . ew_SearchString("=", $this->nu_pessoaResponsavel->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_pessoa], [no_pessoa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[pessoa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_pessoaResponsavel, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_pessoa] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_pessoaResponsavel->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_pessoaResponsavel->ViewValue = $this->nu_pessoaResponsavel->CurrentValue;
				}
			} else {
				$this->nu_pessoaResponsavel->ViewValue = NULL;
			}
			$this->nu_pessoaResponsavel->ViewCustomAttributes = "";

			// nu_itemPDTI
			$this->nu_itemPDTI->ViewValue = $this->nu_itemPDTI->CurrentValue;
			$this->nu_itemPDTI->ViewCustomAttributes = "";

			// dt_registro
			$this->dt_registro->ViewValue = $this->dt_registro->CurrentValue;
			$this->dt_registro->ViewValue = ew_FormatDateTime($this->dt_registro->ViewValue, 7);
			$this->dt_registro->ViewCustomAttributes = "";

			// im_anexo
			if (!ew_Empty($this->im_anexo->Upload->DbValue)) {
				$this->im_anexo->ViewValue = $this->im_anexo->Upload->DbValue;
			} else {
				$this->im_anexo->ViewValue = "";
			}
			$this->im_anexo->ViewCustomAttributes = "";

			// ic_situacao
			if (strval($this->ic_situacao->CurrentValue) <> "") {
				switch ($this->ic_situacao->CurrentValue) {
					case $this->ic_situacao->FldTagValue(1):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(1) <> "" ? $this->ic_situacao->FldTagCaption(1) : $this->ic_situacao->CurrentValue;
						break;
					case $this->ic_situacao->FldTagValue(2):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(2) <> "" ? $this->ic_situacao->FldTagCaption(2) : $this->ic_situacao->CurrentValue;
						break;
					case $this->ic_situacao->FldTagValue(3):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(3) <> "" ? $this->ic_situacao->FldTagCaption(3) : $this->ic_situacao->CurrentValue;
						break;
					case $this->ic_situacao->FldTagValue(4):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(4) <> "" ? $this->ic_situacao->FldTagCaption(4) : $this->ic_situacao->CurrentValue;
						break;
					default:
						$this->ic_situacao->ViewValue = $this->ic_situacao->CurrentValue;
				}
			} else {
				$this->ic_situacao->ViewValue = NULL;
			}
			$this->ic_situacao->ViewCustomAttributes = "";

			// dt_aprovacao
			$this->dt_aprovacao->ViewValue = $this->dt_aprovacao->CurrentValue;
			$this->dt_aprovacao->ViewValue = ew_FormatDateTime($this->dt_aprovacao->ViewValue, 7);
			$this->dt_aprovacao->ViewCustomAttributes = "";

			// nu_pessoaAprovadora
			if (strval($this->nu_pessoaAprovadora->CurrentValue) <> "") {
				$sFilterWrk = "[nu_pessoa]" . ew_SearchString("=", $this->nu_pessoaAprovadora->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_pessoa], [no_pessoa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[pessoa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_pessoaAprovadora, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_pessoaAprovadora->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_pessoaAprovadora->ViewValue = $this->nu_pessoaAprovadora->CurrentValue;
				}
			} else {
				$this->nu_pessoaAprovadora->ViewValue = NULL;
			}
			$this->nu_pessoaAprovadora->ViewCustomAttributes = "";

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

			// ds_demanda
			$this->ds_demanda->LinkCustomAttributes = "";
			$this->ds_demanda->HrefValue = "";
			$this->ds_demanda->TooltipValue = "";

			// nu_pessoaResponsavel
			$this->nu_pessoaResponsavel->LinkCustomAttributes = "";
			$this->nu_pessoaResponsavel->HrefValue = "";
			$this->nu_pessoaResponsavel->TooltipValue = "";

			// nu_itemPDTI
			$this->nu_itemPDTI->LinkCustomAttributes = "";
			$this->nu_itemPDTI->HrefValue = "";
			$this->nu_itemPDTI->TooltipValue = "";

			// dt_registro
			$this->dt_registro->LinkCustomAttributes = "";
			$this->dt_registro->HrefValue = "";
			$this->dt_registro->TooltipValue = "";

			// im_anexo
			$this->im_anexo->LinkCustomAttributes = "";
			$this->im_anexo->HrefValue = "";
			$this->im_anexo->HrefValue2 = $this->im_anexo->UploadPath . $this->im_anexo->Upload->DbValue;
			$this->im_anexo->TooltipValue = "";

			// ic_situacao
			$this->ic_situacao->LinkCustomAttributes = "";
			$this->ic_situacao->HrefValue = "";
			$this->ic_situacao->TooltipValue = "";

			// dt_aprovacao
			$this->dt_aprovacao->LinkCustomAttributes = "";
			$this->dt_aprovacao->HrefValue = "";
			$this->dt_aprovacao->TooltipValue = "";

			// nu_pessoaAprovadora
			$this->nu_pessoaAprovadora->LinkCustomAttributes = "";
			$this->nu_pessoaAprovadora->HrefValue = "";
			$this->nu_pessoaAprovadora->TooltipValue = "";

			// ts_datahora
			$this->ts_datahora->LinkCustomAttributes = "";
			$this->ts_datahora->HrefValue = "";
			$this->ts_datahora->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// nu_demanda
			$this->nu_demanda->EditCustomAttributes = "";
			$this->nu_demanda->EditValue = $this->nu_demanda->CurrentValue;
			$this->nu_demanda->ViewCustomAttributes = "";

			// ds_demanda
			$this->ds_demanda->EditCustomAttributes = "";
			$this->ds_demanda->EditValue = $this->ds_demanda->CurrentValue;
			$this->ds_demanda->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_demanda->FldCaption()));

			// nu_pessoaResponsavel
			$this->nu_pessoaResponsavel->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_pessoa], [no_pessoa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[pessoa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_pessoaResponsavel, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_pessoa] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_pessoaResponsavel->EditValue = $arwrk;

			// nu_itemPDTI
			$this->nu_itemPDTI->EditCustomAttributes = "";
			$this->nu_itemPDTI->EditValue = ew_HtmlEncode($this->nu_itemPDTI->CurrentValue);
			$this->nu_itemPDTI->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_itemPDTI->FldCaption()));

			// dt_registro
			// im_anexo

			$this->im_anexo->EditCustomAttributes = "";
			if (!ew_Empty($this->im_anexo->Upload->DbValue)) {
				$this->im_anexo->EditValue = $this->im_anexo->Upload->DbValue;
			} else {
				$this->im_anexo->EditValue = "";
			}
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->im_anexo);

			// ic_situacao
			$this->ic_situacao->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_situacao->FldTagValue(1), $this->ic_situacao->FldTagCaption(1) <> "" ? $this->ic_situacao->FldTagCaption(1) : $this->ic_situacao->FldTagValue(1));
			$arwrk[] = array($this->ic_situacao->FldTagValue(2), $this->ic_situacao->FldTagCaption(2) <> "" ? $this->ic_situacao->FldTagCaption(2) : $this->ic_situacao->FldTagValue(2));
			$arwrk[] = array($this->ic_situacao->FldTagValue(3), $this->ic_situacao->FldTagCaption(3) <> "" ? $this->ic_situacao->FldTagCaption(3) : $this->ic_situacao->FldTagValue(3));
			$arwrk[] = array($this->ic_situacao->FldTagValue(4), $this->ic_situacao->FldTagCaption(4) <> "" ? $this->ic_situacao->FldTagCaption(4) : $this->ic_situacao->FldTagValue(4));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_situacao->EditValue = $arwrk;

			// dt_aprovacao
			$this->dt_aprovacao->EditCustomAttributes = "";
			$this->dt_aprovacao->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->dt_aprovacao->CurrentValue, 7));
			$this->dt_aprovacao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dt_aprovacao->FldCaption()));

			// nu_pessoaAprovadora
			$this->nu_pessoaAprovadora->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_pessoa], [no_pessoa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[pessoa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_pessoaAprovadora, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_pessoaAprovadora->EditValue = $arwrk;

			// ts_datahora
			// Edit refer script
			// nu_demanda

			$this->nu_demanda->HrefValue = "";

			// ds_demanda
			$this->ds_demanda->HrefValue = "";

			// nu_pessoaResponsavel
			$this->nu_pessoaResponsavel->HrefValue = "";

			// nu_itemPDTI
			$this->nu_itemPDTI->HrefValue = "";

			// dt_registro
			$this->dt_registro->HrefValue = "";

			// im_anexo
			$this->im_anexo->HrefValue = "";
			$this->im_anexo->HrefValue2 = $this->im_anexo->UploadPath . $this->im_anexo->Upload->DbValue;

			// ic_situacao
			$this->ic_situacao->HrefValue = "";

			// dt_aprovacao
			$this->dt_aprovacao->HrefValue = "";

			// nu_pessoaAprovadora
			$this->nu_pessoaAprovadora->HrefValue = "";

			// ts_datahora
			$this->ts_datahora->HrefValue = "";
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
		if (!$this->ds_demanda->FldIsDetailKey && !is_null($this->ds_demanda->FormValue) && $this->ds_demanda->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ds_demanda->FldCaption());
		}
		if (!$this->nu_pessoaResponsavel->FldIsDetailKey && !is_null($this->nu_pessoaResponsavel->FormValue) && $this->nu_pessoaResponsavel->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_pessoaResponsavel->FldCaption());
		}
		if (!ew_CheckInteger($this->nu_itemPDTI->FormValue)) {
			ew_AddMessage($gsFormError, $this->nu_itemPDTI->FldErrMsg());
		}
		if (!$this->ic_situacao->FldIsDetailKey && !is_null($this->ic_situacao->FormValue) && $this->ic_situacao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_situacao->FldCaption());
		}
		if (!ew_CheckEuroDate($this->dt_aprovacao->FormValue)) {
			ew_AddMessage($gsFormError, $this->dt_aprovacao->FldErrMsg());
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

			// ds_demanda
			$this->ds_demanda->SetDbValueDef($rsnew, $this->ds_demanda->CurrentValue, "", $this->ds_demanda->ReadOnly);

			// nu_pessoaResponsavel
			$this->nu_pessoaResponsavel->SetDbValueDef($rsnew, $this->nu_pessoaResponsavel->CurrentValue, 0, $this->nu_pessoaResponsavel->ReadOnly);

			// nu_itemPDTI
			$this->nu_itemPDTI->SetDbValueDef($rsnew, $this->nu_itemPDTI->CurrentValue, NULL, $this->nu_itemPDTI->ReadOnly);

			// dt_registro
			$this->dt_registro->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
			$rsnew['dt_registro'] = &$this->dt_registro->DbValue;

			// im_anexo
			if (!($this->im_anexo->ReadOnly) && !$this->im_anexo->Upload->KeepFile) {
				$this->im_anexo->Upload->DbValue = $rs->fields('im_anexo'); // Get original value
				if ($this->im_anexo->Upload->FileName == "") {
					$rsnew['im_anexo'] = NULL;
				} else {
					$rsnew['im_anexo'] = $this->im_anexo->Upload->FileName;
				}
			}

			// ic_situacao
			$this->ic_situacao->SetDbValueDef($rsnew, $this->ic_situacao->CurrentValue, NULL, $this->ic_situacao->ReadOnly);

			// dt_aprovacao
			$this->dt_aprovacao->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->dt_aprovacao->CurrentValue, 7), NULL, $this->dt_aprovacao->ReadOnly);

			// nu_pessoaAprovadora
			$this->nu_pessoaAprovadora->SetDbValueDef($rsnew, $this->nu_pessoaAprovadora->CurrentValue, NULL, $this->nu_pessoaAprovadora->ReadOnly);

			// ts_datahora
			$this->ts_datahora->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
			$rsnew['ts_datahora'] = &$this->ts_datahora->DbValue;
			if (!$this->im_anexo->Upload->KeepFile) {
				if (!ew_Empty($this->im_anexo->Upload->Value)) {
					if ($this->im_anexo->Upload->FileName == $this->im_anexo->Upload->DbValue) { // Overwrite if same file name
						$this->im_anexo->Upload->DbValue = ""; // No need to delete any more
					} else {
						$rsnew['im_anexo'] = ew_UploadFileNameEx($this->im_anexo->UploadPath, $rsnew['im_anexo']); // Get new file name
					}
				}
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
					if (!$this->im_anexo->Upload->KeepFile) {
						if (!ew_Empty($this->im_anexo->Upload->Value)) {
							$this->im_anexo->Upload->SaveToFile($this->im_anexo->UploadPath, $rsnew['im_anexo'], TRUE);
						}
						if ($this->im_anexo->Upload->DbValue <> "")
							@unlink(ew_UploadPathEx(TRUE, $this->im_anexo->OldUploadPath) . $this->im_anexo->Upload->DbValue);
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
		$rs->Close();

		// im_anexo
		ew_CleanUploadTempPath($this->im_anexo, $this->im_anexo->Upload->Index);
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "demandalist.php", $this->TableVar);
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
if (!isset($demanda_edit)) $demanda_edit = new cdemanda_edit();

// Page init
$demanda_edit->Page_Init();

// Page main
$demanda_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$demanda_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var demanda_edit = new ew_Page("demanda_edit");
demanda_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = demanda_edit.PageID; // For backward compatibility

// Form object
var fdemandaedit = new ew_Form("fdemandaedit");

// Validate form
fdemandaedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_ds_demanda");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($demanda->ds_demanda->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_pessoaResponsavel");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($demanda->nu_pessoaResponsavel->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_itemPDTI");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($demanda->nu_itemPDTI->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ic_situacao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($demanda->ic_situacao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dt_aprovacao");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($demanda->dt_aprovacao->FldErrMsg()) ?>");

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
fdemandaedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdemandaedit.ValidateRequired = true;
<?php } else { ?>
fdemandaedit.ValidateRequired = false; 
<?php } ?>

// Multi-Page properties
fdemandaedit.MultiPage = new ew_MultiPage("fdemandaedit",
	[["x_nu_demanda",1],["x_ds_demanda",1],["x_nu_pessoaResponsavel",1],["x_nu_itemPDTI",1],["x_im_anexo",2],["x_ic_situacao",1],["x_dt_aprovacao",3],["x_nu_pessoaAprovadora",3]]
);

// Dynamic selection lists
fdemandaedit.Lists["x_nu_pessoaResponsavel"] = {"LinkField":"x_nu_pessoa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_pessoa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fdemandaedit.Lists["x_nu_pessoaAprovadora"] = {"LinkField":"x_nu_pessoa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_pessoa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $demanda_edit->ShowPageHeader(); ?>
<?php
$demanda_edit->ShowMessage();
?>
<form name="fdemandaedit" id="fdemandaedit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="demanda">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table class="ewStdTable"><tbody><tr><td>
<div class="tabbable" id="demanda_edit">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_demanda1" data-toggle="tab"><?php echo $demanda->PageCaption(1) ?></a></li>
		<li><a href="#tab_demanda2" data-toggle="tab"><?php echo $demanda->PageCaption(2) ?></a></li>
		<li><a href="#tab_demanda3" data-toggle="tab"><?php echo $demanda->PageCaption(3) ?></a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab_demanda1">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_demandaedit1" class="table table-bordered table-striped">
<?php if ($demanda->nu_demanda->Visible) { // nu_demanda ?>
	<tr id="r_nu_demanda">
		<td><span id="elh_demanda_nu_demanda"><?php echo $demanda->nu_demanda->FldCaption() ?></span></td>
		<td<?php echo $demanda->nu_demanda->CellAttributes() ?>>
<span id="el_demanda_nu_demanda" class="control-group">
<span<?php echo $demanda->nu_demanda->ViewAttributes() ?>>
<?php echo $demanda->nu_demanda->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_demanda" name="x_nu_demanda" id="x_nu_demanda" value="<?php echo ew_HtmlEncode($demanda->nu_demanda->CurrentValue) ?>">
<?php echo $demanda->nu_demanda->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($demanda->ds_demanda->Visible) { // ds_demanda ?>
	<tr id="r_ds_demanda">
		<td><span id="elh_demanda_ds_demanda"><?php echo $demanda->ds_demanda->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $demanda->ds_demanda->CellAttributes() ?>>
<span id="el_demanda_ds_demanda" class="control-group">
<textarea data-field="x_ds_demanda" name="x_ds_demanda" id="x_ds_demanda" cols="35" rows="4" placeholder="<?php echo $demanda->ds_demanda->PlaceHolder ?>"<?php echo $demanda->ds_demanda->EditAttributes() ?>><?php echo $demanda->ds_demanda->EditValue ?></textarea>
</span>
<?php echo $demanda->ds_demanda->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($demanda->nu_pessoaResponsavel->Visible) { // nu_pessoaResponsavel ?>
	<tr id="r_nu_pessoaResponsavel">
		<td><span id="elh_demanda_nu_pessoaResponsavel"><?php echo $demanda->nu_pessoaResponsavel->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $demanda->nu_pessoaResponsavel->CellAttributes() ?>>
<span id="el_demanda_nu_pessoaResponsavel" class="control-group">
<select data-field="x_nu_pessoaResponsavel" id="x_nu_pessoaResponsavel" name="x_nu_pessoaResponsavel"<?php echo $demanda->nu_pessoaResponsavel->EditAttributes() ?>>
<?php
if (is_array($demanda->nu_pessoaResponsavel->EditValue)) {
	$arwrk = $demanda->nu_pessoaResponsavel->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($demanda->nu_pessoaResponsavel->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if (AllowAdd(CurrentProjectID() . "pessoa")) { ?>
&nbsp;<a id="aol_x_nu_pessoaResponsavel" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_nu_pessoaResponsavel',url:'pessoaaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $demanda->nu_pessoaResponsavel->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
fdemandaedit.Lists["x_nu_pessoaResponsavel"].Options = <?php echo (is_array($demanda->nu_pessoaResponsavel->EditValue)) ? ew_ArrayToJson($demanda->nu_pessoaResponsavel->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $demanda->nu_pessoaResponsavel->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($demanda->nu_itemPDTI->Visible) { // nu_itemPDTI ?>
	<tr id="r_nu_itemPDTI">
		<td><span id="elh_demanda_nu_itemPDTI"><?php echo $demanda->nu_itemPDTI->FldCaption() ?></span></td>
		<td<?php echo $demanda->nu_itemPDTI->CellAttributes() ?>>
<span id="el_demanda_nu_itemPDTI" class="control-group">
<input type="text" data-field="x_nu_itemPDTI" name="x_nu_itemPDTI" id="x_nu_itemPDTI" size="30" placeholder="<?php echo $demanda->nu_itemPDTI->PlaceHolder ?>" value="<?php echo $demanda->nu_itemPDTI->EditValue ?>"<?php echo $demanda->nu_itemPDTI->EditAttributes() ?>>
</span>
<?php echo $demanda->nu_itemPDTI->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($demanda->ic_situacao->Visible) { // ic_situacao ?>
	<tr id="r_ic_situacao">
		<td><span id="elh_demanda_ic_situacao"><?php echo $demanda->ic_situacao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $demanda->ic_situacao->CellAttributes() ?>>
<span id="el_demanda_ic_situacao" class="control-group">
<select data-field="x_ic_situacao" id="x_ic_situacao" name="x_ic_situacao"<?php echo $demanda->ic_situacao->EditAttributes() ?>>
<?php
if (is_array($demanda->ic_situacao->EditValue)) {
	$arwrk = $demanda->ic_situacao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($demanda->ic_situacao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $demanda->ic_situacao->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_demanda2">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_demandaedit2" class="table table-bordered table-striped">
<?php if ($demanda->im_anexo->Visible) { // im_anexo ?>
	<tr id="r_im_anexo">
		<td><span id="elh_demanda_im_anexo"><?php echo $demanda->im_anexo->FldCaption() ?></span></td>
		<td<?php echo $demanda->im_anexo->CellAttributes() ?>>
<span id="el_demanda_im_anexo" class="control-group">
<span id="fd_x_im_anexo">
<span class="btn btn-small fileinput-button">
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_im_anexo" name="x_im_anexo" id="x_im_anexo">
</span>
<input type="hidden" name="fn_x_im_anexo" id= "fn_x_im_anexo" value="<?php echo $demanda->im_anexo->Upload->FileName ?>">
<?php if (@$_POST["fa_x_im_anexo"] == "0") { ?>
<input type="hidden" name="fa_x_im_anexo" id= "fa_x_im_anexo" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_im_anexo" id= "fa_x_im_anexo" value="1">
<?php } ?>
<input type="hidden" name="fs_x_im_anexo" id= "fs_x_im_anexo" value="255">
</span>
<table id="ft_x_im_anexo" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $demanda->im_anexo->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_demanda3">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_demandaedit3" class="table table-bordered table-striped">
<?php if ($demanda->dt_aprovacao->Visible) { // dt_aprovacao ?>
	<tr id="r_dt_aprovacao">
		<td><span id="elh_demanda_dt_aprovacao"><?php echo $demanda->dt_aprovacao->FldCaption() ?></span></td>
		<td<?php echo $demanda->dt_aprovacao->CellAttributes() ?>>
<span id="el_demanda_dt_aprovacao" class="control-group">
<input type="text" data-field="x_dt_aprovacao" name="x_dt_aprovacao" id="x_dt_aprovacao" placeholder="<?php echo $demanda->dt_aprovacao->PlaceHolder ?>" value="<?php echo $demanda->dt_aprovacao->EditValue ?>"<?php echo $demanda->dt_aprovacao->EditAttributes() ?>>
<?php if (!$demanda->dt_aprovacao->ReadOnly && !$demanda->dt_aprovacao->Disabled && @$demanda->dt_aprovacao->EditAttrs["readonly"] == "" && @$demanda->dt_aprovacao->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_dt_aprovacao" name="cal_x_dt_aprovacao" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x_dt_aprovacao" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fdemandaedit", "x_dt_aprovacao", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $demanda->dt_aprovacao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($demanda->nu_pessoaAprovadora->Visible) { // nu_pessoaAprovadora ?>
	<tr id="r_nu_pessoaAprovadora">
		<td><span id="elh_demanda_nu_pessoaAprovadora"><?php echo $demanda->nu_pessoaAprovadora->FldCaption() ?></span></td>
		<td<?php echo $demanda->nu_pessoaAprovadora->CellAttributes() ?>>
<span id="el_demanda_nu_pessoaAprovadora" class="control-group">
<select data-field="x_nu_pessoaAprovadora" id="x_nu_pessoaAprovadora" name="x_nu_pessoaAprovadora"<?php echo $demanda->nu_pessoaAprovadora->EditAttributes() ?>>
<?php
if (is_array($demanda->nu_pessoaAprovadora->EditValue)) {
	$arwrk = $demanda->nu_pessoaAprovadora->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($demanda->nu_pessoaAprovadora->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fdemandaedit.Lists["x_nu_pessoaAprovadora"].Options = <?php echo (is_array($demanda->nu_pessoaAprovadora->EditValue)) ? ew_ArrayToJson($demanda->nu_pessoaAprovadora->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $demanda->nu_pessoaAprovadora->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
	</div>
</div>
</td></tr></tbody></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fdemandaedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$demanda_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$demanda_edit->Page_Terminate();
?>
