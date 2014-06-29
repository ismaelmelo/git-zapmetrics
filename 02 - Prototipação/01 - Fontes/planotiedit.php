<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "planotiinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$planoti_edit = NULL; // Initialize page object first

class cplanoti_edit extends cplanoti {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'planoti';

	// Page object name
	var $PageObjName = 'planoti_edit';

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

		// Table object (planoti)
		if (!isset($GLOBALS["planoti"])) {
			$GLOBALS["planoti"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["planoti"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'planoti', TRUE);

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
			$this->Page_Terminate("planotilist.php");
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
		if (@$_GET["nu_plano"] <> "") {
			$this->nu_plano->setQueryStringValue($_GET["nu_plano"]);
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
		if ($this->nu_plano->CurrentValue == "")
			$this->Page_Terminate("planotilist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("planotilist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "planotiview.php")
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
		if (!$this->nu_anoInicio->FldIsDetailKey) {
			$this->nu_anoInicio->setFormValue($objForm->GetValue("x_nu_anoInicio"));
		}
		if (!$this->nu_anoFim->FldIsDetailKey) {
			$this->nu_anoFim->setFormValue($objForm->GetValue("x_nu_anoFim"));
		}
		if (!$this->no_plano->FldIsDetailKey) {
			$this->no_plano->setFormValue($objForm->GetValue("x_no_plano"));
		}
		if (!$this->ds_plano->FldIsDetailKey) {
			$this->ds_plano->setFormValue($objForm->GetValue("x_ds_plano"));
		}
		if (!$this->nu_planoEstrategico->FldIsDetailKey) {
			$this->nu_planoEstrategico->setFormValue($objForm->GetValue("x_nu_planoEstrategico"));
		}
		if (!$this->no_localArquivo->FldIsDetailKey) {
			$this->no_localArquivo->setFormValue($objForm->GetValue("x_no_localArquivo"));
		}
		if (!$this->ic_situacao->FldIsDetailKey) {
			$this->ic_situacao->setFormValue($objForm->GetValue("x_ic_situacao"));
		}
		if (!$this->nu_plano->FldIsDetailKey)
			$this->nu_plano->setFormValue($objForm->GetValue("x_nu_plano"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->nu_plano->CurrentValue = $this->nu_plano->FormValue;
		$this->nu_anoInicio->CurrentValue = $this->nu_anoInicio->FormValue;
		$this->nu_anoFim->CurrentValue = $this->nu_anoFim->FormValue;
		$this->no_plano->CurrentValue = $this->no_plano->FormValue;
		$this->ds_plano->CurrentValue = $this->ds_plano->FormValue;
		$this->nu_planoEstrategico->CurrentValue = $this->nu_planoEstrategico->FormValue;
		$this->no_localArquivo->CurrentValue = $this->no_localArquivo->FormValue;
		$this->ic_situacao->CurrentValue = $this->ic_situacao->FormValue;
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
		$this->nu_plano->setDbValue($rs->fields('nu_plano'));
		$this->nu_anoInicio->setDbValue($rs->fields('nu_anoInicio'));
		$this->nu_anoFim->setDbValue($rs->fields('nu_anoFim'));
		$this->no_plano->setDbValue($rs->fields('no_plano'));
		$this->ds_plano->setDbValue($rs->fields('ds_plano'));
		$this->nu_planoEstrategico->setDbValue($rs->fields('nu_planoEstrategico'));
		$this->no_localArquivo->setDbValue($rs->fields('no_localArquivo'));
		$this->im_anexo->Upload->DbValue = $rs->fields('im_anexo');
		$this->ic_situacao->setDbValue($rs->fields('ic_situacao'));
		$this->nu_usuario->setDbValue($rs->fields('nu_usuario'));
		$this->ts_datahora->setDbValue($rs->fields('ts_datahora'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_plano->DbValue = $row['nu_plano'];
		$this->nu_anoInicio->DbValue = $row['nu_anoInicio'];
		$this->nu_anoFim->DbValue = $row['nu_anoFim'];
		$this->no_plano->DbValue = $row['no_plano'];
		$this->ds_plano->DbValue = $row['ds_plano'];
		$this->nu_planoEstrategico->DbValue = $row['nu_planoEstrategico'];
		$this->no_localArquivo->DbValue = $row['no_localArquivo'];
		$this->im_anexo->Upload->DbValue = $row['im_anexo'];
		$this->ic_situacao->DbValue = $row['ic_situacao'];
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
		// nu_plano
		// nu_anoInicio
		// nu_anoFim
		// no_plano
		// ds_plano
		// nu_planoEstrategico
		// no_localArquivo
		// im_anexo
		// ic_situacao
		// nu_usuario
		// ts_datahora

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_plano
			$this->nu_plano->ViewValue = $this->nu_plano->CurrentValue;
			$this->nu_plano->ViewCustomAttributes = "";

			// nu_anoInicio
			$this->nu_anoInicio->ViewValue = $this->nu_anoInicio->CurrentValue;
			$this->nu_anoInicio->ViewCustomAttributes = "";

			// nu_anoFim
			$this->nu_anoFim->ViewValue = $this->nu_anoFim->CurrentValue;
			if (strval($this->nu_anoFim->CurrentValue) <> "") {
				$sFilterWrk = "[nu_ano]" . ew_SearchString("=", $this->nu_anoFim->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_ano], [nu_ano] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ano]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_anoFim, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_anoFim->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_anoFim->ViewValue = $this->nu_anoFim->CurrentValue;
				}
			} else {
				$this->nu_anoFim->ViewValue = NULL;
			}
			$this->nu_anoFim->ViewCustomAttributes = "";

			// no_plano
			$this->no_plano->ViewValue = $this->no_plano->CurrentValue;
			$this->no_plano->ViewCustomAttributes = "";

			// ds_plano
			$this->ds_plano->ViewValue = $this->ds_plano->CurrentValue;
			$this->ds_plano->ViewCustomAttributes = "";

			// nu_planoEstrategico
			if (strval($this->nu_planoEstrategico->CurrentValue) <> "") {
				$sFilterWrk = "[nu_plano]" . ew_SearchString("=", $this->nu_planoEstrategico->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_plano], [no_plano] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[planoestrategico]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_planoEstrategico, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_anoInicio] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_planoEstrategico->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_planoEstrategico->ViewValue = $this->nu_planoEstrategico->CurrentValue;
				}
			} else {
				$this->nu_planoEstrategico->ViewValue = NULL;
			}
			$this->nu_planoEstrategico->ViewCustomAttributes = "";

			// no_localArquivo
			$this->no_localArquivo->ViewValue = $this->no_localArquivo->CurrentValue;
			$this->no_localArquivo->ViewCustomAttributes = "";

			// im_anexo
			$this->im_anexo->UploadPath = "arquivos/plano_ti";
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

			// nu_usuario
			$this->nu_usuario->ViewValue = $this->nu_usuario->CurrentValue;
			$this->nu_usuario->ViewCustomAttributes = "";

			// ts_datahora
			$this->ts_datahora->ViewValue = $this->ts_datahora->CurrentValue;
			$this->ts_datahora->ViewValue = ew_FormatDateTime($this->ts_datahora->ViewValue, 7);
			$this->ts_datahora->ViewCustomAttributes = "";

			// nu_anoInicio
			$this->nu_anoInicio->LinkCustomAttributes = "";
			$this->nu_anoInicio->HrefValue = "";
			$this->nu_anoInicio->TooltipValue = "";

			// nu_anoFim
			$this->nu_anoFim->LinkCustomAttributes = "";
			$this->nu_anoFim->HrefValue = "";
			$this->nu_anoFim->TooltipValue = "";

			// no_plano
			$this->no_plano->LinkCustomAttributes = "";
			$this->no_plano->HrefValue = "";
			$this->no_plano->TooltipValue = "";

			// ds_plano
			$this->ds_plano->LinkCustomAttributes = "";
			$this->ds_plano->HrefValue = "";
			$this->ds_plano->TooltipValue = "";

			// nu_planoEstrategico
			$this->nu_planoEstrategico->LinkCustomAttributes = "";
			$this->nu_planoEstrategico->HrefValue = "";
			$this->nu_planoEstrategico->TooltipValue = "";

			// no_localArquivo
			$this->no_localArquivo->LinkCustomAttributes = "";
			$this->no_localArquivo->HrefValue = "";
			$this->no_localArquivo->TooltipValue = "";

			// im_anexo
			$this->im_anexo->LinkCustomAttributes = "";
			$this->im_anexo->HrefValue = "";
			$this->im_anexo->HrefValue2 = $this->im_anexo->UploadPath . $this->im_anexo->Upload->DbValue;
			$this->im_anexo->TooltipValue = "";

			// ic_situacao
			$this->ic_situacao->LinkCustomAttributes = "";
			$this->ic_situacao->HrefValue = "";
			$this->ic_situacao->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// nu_anoInicio
			$this->nu_anoInicio->EditCustomAttributes = "";
			$this->nu_anoInicio->EditValue = $this->nu_anoInicio->CurrentValue;
			$this->nu_anoInicio->ViewCustomAttributes = "";

			// nu_anoFim
			$this->nu_anoFim->EditCustomAttributes = "";
			$this->nu_anoFim->EditValue = $this->nu_anoFim->CurrentValue;
			if (strval($this->nu_anoFim->CurrentValue) <> "") {
				$sFilterWrk = "[nu_ano]" . ew_SearchString("=", $this->nu_anoFim->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_ano], [nu_ano] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ano]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_anoFim, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_anoFim->EditValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_anoFim->EditValue = $this->nu_anoFim->CurrentValue;
				}
			} else {
				$this->nu_anoFim->EditValue = NULL;
			}
			$this->nu_anoFim->ViewCustomAttributes = "";

			// no_plano
			$this->no_plano->EditCustomAttributes = "";
			$this->no_plano->EditValue = ew_HtmlEncode($this->no_plano->CurrentValue);
			$this->no_plano->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_plano->FldCaption()));

			// ds_plano
			$this->ds_plano->EditCustomAttributes = "";
			$this->ds_plano->EditValue = $this->ds_plano->CurrentValue;
			$this->ds_plano->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_plano->FldCaption()));

			// nu_planoEstrategico
			$this->nu_planoEstrategico->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_plano], [no_plano] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[planoestrategico]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_planoEstrategico, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_anoInicio] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_planoEstrategico->EditValue = $arwrk;

			// no_localArquivo
			$this->no_localArquivo->EditCustomAttributes = "";
			$this->no_localArquivo->EditValue = ew_HtmlEncode($this->no_localArquivo->CurrentValue);
			$this->no_localArquivo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_localArquivo->FldCaption()));

			// im_anexo
			$this->im_anexo->EditCustomAttributes = "";
			$this->im_anexo->UploadPath = "arquivos/plano_ti";
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

			// Edit refer script
			// nu_anoInicio

			$this->nu_anoInicio->HrefValue = "";

			// nu_anoFim
			$this->nu_anoFim->HrefValue = "";

			// no_plano
			$this->no_plano->HrefValue = "";

			// ds_plano
			$this->ds_plano->HrefValue = "";

			// nu_planoEstrategico
			$this->nu_planoEstrategico->HrefValue = "";

			// no_localArquivo
			$this->no_localArquivo->HrefValue = "";

			// im_anexo
			$this->im_anexo->HrefValue = "";
			$this->im_anexo->HrefValue2 = $this->im_anexo->UploadPath . $this->im_anexo->Upload->DbValue;

			// ic_situacao
			$this->ic_situacao->HrefValue = "";
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
		if (!$this->no_plano->FldIsDetailKey && !is_null($this->no_plano->FormValue) && $this->no_plano->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_plano->FldCaption());
		}
		if (!$this->nu_planoEstrategico->FldIsDetailKey && !is_null($this->nu_planoEstrategico->FormValue) && $this->nu_planoEstrategico->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_planoEstrategico->FldCaption());
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
			$this->im_anexo->OldUploadPath = "arquivos/plano_ti";
			$this->im_anexo->UploadPath = $this->im_anexo->OldUploadPath;
			$rsnew = array();

			// no_plano
			$this->no_plano->SetDbValueDef($rsnew, $this->no_plano->CurrentValue, "", $this->no_plano->ReadOnly);

			// ds_plano
			$this->ds_plano->SetDbValueDef($rsnew, $this->ds_plano->CurrentValue, NULL, $this->ds_plano->ReadOnly);

			// nu_planoEstrategico
			$this->nu_planoEstrategico->SetDbValueDef($rsnew, $this->nu_planoEstrategico->CurrentValue, 0, $this->nu_planoEstrategico->ReadOnly);

			// no_localArquivo
			$this->no_localArquivo->SetDbValueDef($rsnew, $this->no_localArquivo->CurrentValue, NULL, $this->no_localArquivo->ReadOnly);

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
			$this->ic_situacao->SetDbValueDef($rsnew, $this->ic_situacao->CurrentValue, "", $this->ic_situacao->ReadOnly);
			if (!$this->im_anexo->Upload->KeepFile) {
				$this->im_anexo->UploadPath = "arquivos/plano_ti";
				$OldFiles = explode(",", $this->im_anexo->Upload->DbValue);
				if (!ew_Empty($this->im_anexo->Upload->FileName)) {
					$NewFiles = explode(",", $this->im_anexo->Upload->FileName);
					$FileCount = count($NewFiles);
					for ($i = 0; $i < $FileCount; $i++) {
						$fldvar = ($this->im_anexo->Upload->Index < 0) ? $this->im_anexo->FldVar : substr($this->im_anexo->FldVar, 0, 1) . $this->im_anexo->Upload->Index . substr($this->im_anexo->FldVar, 1);
						if ($NewFiles[$i] <> "") {
							$file = ew_UploadTempPath($fldvar) . EW_PATH_DELIMITER . $NewFiles[$i];
							if (file_exists($file)) {
								if (!in_array($NewFiles[$i], $OldFiles)) {
									$NewFiles[$i] = ew_UploadFileNameEx($this->im_anexo->UploadPath, $NewFiles[$i]); // Get new file name
									$file1 = ew_UploadTempPath($fldvar) . EW_PATH_DELIMITER . $NewFiles[$i];
									if ($file1 <> $file) // Rename temp file
										rename($file, $file1);
								}
							}
						}
					}
					$this->im_anexo->Upload->FileName = implode(",", $NewFiles);
					$rsnew['im_anexo'] = $this->im_anexo->Upload->FileName;
				} else {
					$NewFiles = array();
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
						$OldFiles = explode(",", $this->im_anexo->Upload->DbValue);
						if (!ew_Empty($this->im_anexo->Upload->FileName)) {
							$NewFiles = explode(",", $this->im_anexo->Upload->FileName);
							$NewFiles2 = explode(",", $rsnew['im_anexo']);
							$FileCount = count($NewFiles);
							for ($i = 0; $i < $FileCount; $i++) {
								$fldvar = ($this->im_anexo->Upload->Index < 0) ? $this->im_anexo->FldVar : substr($this->im_anexo->FldVar, 0, 1) . $this->im_anexo->Upload->Index . substr($this->im_anexo->FldVar, 1);
								if ($NewFiles[$i] <> "") {
									$file = ew_UploadTempPath($fldvar) . EW_PATH_DELIMITER . $NewFiles[$i];
									if (file_exists($file)) {
										$this->im_anexo->Upload->Value = file_get_contents($file);
										$this->im_anexo->Upload->SaveToFile($this->im_anexo->UploadPath, (@$NewFiles2[$i] <> "") ? $NewFiles2[$i] : $NewFiles[$i], TRUE); // Just replace
									}
								}
							}
						} else {
							$NewFiles = array();
						}
						$FileCount = count($OldFiles);
						for ($i = 0; $i < $FileCount; $i++) {
							if ($OldFiles[$i] <> "" && !in_array($OldFiles[$i], $NewFiles))
								@unlink(ew_UploadPathEx(TRUE, $this->im_anexo->OldUploadPath) . $OldFiles[$i]);
						}
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "planotilist.php", $this->TableVar);
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
if (!isset($planoti_edit)) $planoti_edit = new cplanoti_edit();

// Page init
$planoti_edit->Page_Init();

// Page main
$planoti_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$planoti_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var planoti_edit = new ew_Page("planoti_edit");
planoti_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = planoti_edit.PageID; // For backward compatibility

// Form object
var fplanotiedit = new ew_Form("fplanotiedit");

// Validate form
fplanotiedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_no_plano");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($planoti->no_plano->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_planoEstrategico");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($planoti->nu_planoEstrategico->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_situacao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($planoti->ic_situacao->FldCaption()) ?>");

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
fplanotiedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fplanotiedit.ValidateRequired = true;
<?php } else { ?>
fplanotiedit.ValidateRequired = false; 
<?php } ?>

// Multi-Page properties
fplanotiedit.MultiPage = new ew_MultiPage("fplanotiedit",
	[["x_nu_anoInicio",1],["x_nu_anoFim",1],["x_no_plano",1],["x_ds_plano",1],["x_nu_planoEstrategico",1],["x_no_localArquivo",2],["x_im_anexo",2],["x_ic_situacao",1]]
);

// Dynamic selection lists
fplanotiedit.Lists["x_nu_anoFim"] = {"LinkField":"x_nu_ano","Ajax":true,"AutoFill":false,"DisplayFields":["x_nu_ano","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fplanotiedit.Lists["x_nu_planoEstrategico"] = {"LinkField":"x_nu_plano","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_plano","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $planoti_edit->ShowPageHeader(); ?>
<?php
$planoti_edit->ShowMessage();
?>
<form name="fplanotiedit" id="fplanotiedit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="planoti">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table class="ewStdTable"><tbody><tr><td>
<div class="tabbable" id="planoti_edit">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_planoti1" data-toggle="tab"><?php echo $planoti->PageCaption(1) ?></a></li>
		<li><a href="#tab_planoti2" data-toggle="tab"><?php echo $planoti->PageCaption(2) ?></a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab_planoti1">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_planotiedit1" class="table table-bordered table-striped">
<?php if ($planoti->nu_anoInicio->Visible) { // nu_anoInicio ?>
	<tr id="r_nu_anoInicio">
		<td><span id="elh_planoti_nu_anoInicio"><?php echo $planoti->nu_anoInicio->FldCaption() ?></span></td>
		<td<?php echo $planoti->nu_anoInicio->CellAttributes() ?>>
<span id="el_planoti_nu_anoInicio" class="control-group">
<span<?php echo $planoti->nu_anoInicio->ViewAttributes() ?>>
<?php echo $planoti->nu_anoInicio->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_anoInicio" name="x_nu_anoInicio" id="x_nu_anoInicio" value="<?php echo ew_HtmlEncode($planoti->nu_anoInicio->CurrentValue) ?>">
<?php echo $planoti->nu_anoInicio->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($planoti->nu_anoFim->Visible) { // nu_anoFim ?>
	<tr id="r_nu_anoFim">
		<td><span id="elh_planoti_nu_anoFim"><?php echo $planoti->nu_anoFim->FldCaption() ?></span></td>
		<td<?php echo $planoti->nu_anoFim->CellAttributes() ?>>
<span id="el_planoti_nu_anoFim" class="control-group">
<span<?php echo $planoti->nu_anoFim->ViewAttributes() ?>>
<?php echo $planoti->nu_anoFim->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_anoFim" name="x_nu_anoFim" id="x_nu_anoFim" value="<?php echo ew_HtmlEncode($planoti->nu_anoFim->CurrentValue) ?>">
<?php echo $planoti->nu_anoFim->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($planoti->no_plano->Visible) { // no_plano ?>
	<tr id="r_no_plano">
		<td><span id="elh_planoti_no_plano"><?php echo $planoti->no_plano->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $planoti->no_plano->CellAttributes() ?>>
<span id="el_planoti_no_plano" class="control-group">
<input type="text" data-field="x_no_plano" name="x_no_plano" id="x_no_plano" size="30" maxlength="100" placeholder="<?php echo $planoti->no_plano->PlaceHolder ?>" value="<?php echo $planoti->no_plano->EditValue ?>"<?php echo $planoti->no_plano->EditAttributes() ?>>
</span>
<?php echo $planoti->no_plano->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($planoti->ds_plano->Visible) { // ds_plano ?>
	<tr id="r_ds_plano">
		<td><span id="elh_planoti_ds_plano"><?php echo $planoti->ds_plano->FldCaption() ?></span></td>
		<td<?php echo $planoti->ds_plano->CellAttributes() ?>>
<span id="el_planoti_ds_plano" class="control-group">
<textarea data-field="x_ds_plano" name="x_ds_plano" id="x_ds_plano" cols="35" rows="4" placeholder="<?php echo $planoti->ds_plano->PlaceHolder ?>"<?php echo $planoti->ds_plano->EditAttributes() ?>><?php echo $planoti->ds_plano->EditValue ?></textarea>
</span>
<?php echo $planoti->ds_plano->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($planoti->nu_planoEstrategico->Visible) { // nu_planoEstrategico ?>
	<tr id="r_nu_planoEstrategico">
		<td><span id="elh_planoti_nu_planoEstrategico"><?php echo $planoti->nu_planoEstrategico->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $planoti->nu_planoEstrategico->CellAttributes() ?>>
<span id="el_planoti_nu_planoEstrategico" class="control-group">
<select data-field="x_nu_planoEstrategico" id="x_nu_planoEstrategico" name="x_nu_planoEstrategico"<?php echo $planoti->nu_planoEstrategico->EditAttributes() ?>>
<?php
if (is_array($planoti->nu_planoEstrategico->EditValue)) {
	$arwrk = $planoti->nu_planoEstrategico->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($planoti->nu_planoEstrategico->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if (AllowAdd(CurrentProjectID() . "planoestrategico")) { ?>
&nbsp;<a id="aol_x_nu_planoEstrategico" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_nu_planoEstrategico',url:'planoestrategicoaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $planoti->nu_planoEstrategico->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
fplanotiedit.Lists["x_nu_planoEstrategico"].Options = <?php echo (is_array($planoti->nu_planoEstrategico->EditValue)) ? ew_ArrayToJson($planoti->nu_planoEstrategico->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $planoti->nu_planoEstrategico->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($planoti->ic_situacao->Visible) { // ic_situacao ?>
	<tr id="r_ic_situacao">
		<td><span id="elh_planoti_ic_situacao"><?php echo $planoti->ic_situacao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $planoti->ic_situacao->CellAttributes() ?>>
<span id="el_planoti_ic_situacao" class="control-group">
<select data-field="x_ic_situacao" id="x_ic_situacao" name="x_ic_situacao"<?php echo $planoti->ic_situacao->EditAttributes() ?>>
<?php
if (is_array($planoti->ic_situacao->EditValue)) {
	$arwrk = $planoti->ic_situacao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($planoti->ic_situacao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $planoti->ic_situacao->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_planoti2">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_planotiedit2" class="table table-bordered table-striped">
<?php if ($planoti->no_localArquivo->Visible) { // no_localArquivo ?>
	<tr id="r_no_localArquivo">
		<td><span id="elh_planoti_no_localArquivo"><?php echo $planoti->no_localArquivo->FldCaption() ?></span></td>
		<td<?php echo $planoti->no_localArquivo->CellAttributes() ?>>
<span id="el_planoti_no_localArquivo" class="control-group">
<input type="text" data-field="x_no_localArquivo" name="x_no_localArquivo" id="x_no_localArquivo" size="30" maxlength="255" placeholder="<?php echo $planoti->no_localArquivo->PlaceHolder ?>" value="<?php echo $planoti->no_localArquivo->EditValue ?>"<?php echo $planoti->no_localArquivo->EditAttributes() ?>>
</span>
<?php echo $planoti->no_localArquivo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($planoti->im_anexo->Visible) { // im_anexo ?>
	<tr id="r_im_anexo">
		<td><span id="elh_planoti_im_anexo"><?php echo $planoti->im_anexo->FldCaption() ?></span></td>
		<td<?php echo $planoti->im_anexo->CellAttributes() ?>>
<span id="el_planoti_im_anexo" class="control-group">
<span id="fd_x_im_anexo">
<span class="btn btn-small fileinput-button">
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_im_anexo" name="x_im_anexo" id="x_im_anexo" multiple="multiple">
</span>
<input type="hidden" name="fn_x_im_anexo" id= "fn_x_im_anexo" value="<?php echo $planoti->im_anexo->Upload->FileName ?>">
<?php if (@$_POST["fa_x_im_anexo"] == "0") { ?>
<input type="hidden" name="fa_x_im_anexo" id= "fa_x_im_anexo" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_im_anexo" id= "fa_x_im_anexo" value="1">
<?php } ?>
<input type="hidden" name="fs_x_im_anexo" id= "fs_x_im_anexo" value="255">
</span>
<table id="ft_x_im_anexo" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $planoti->im_anexo->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
	</div>
</div>
</td></tr></tbody></table>
<input type="hidden" data-field="x_nu_plano" name="x_nu_plano" id="x_nu_plano" value="<?php echo ew_HtmlEncode($planoti->nu_plano->CurrentValue) ?>">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fplanotiedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$planoti_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$planoti_edit->Page_Terminate();
?>
