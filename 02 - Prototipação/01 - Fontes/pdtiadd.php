<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "pdtiinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$pdti_add = NULL; // Initialize page object first

class cpdti_add extends cpdti {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'pdti';

	// Page object name
	var $PageObjName = 'pdti_add';

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

		// Table object (pdti)
		if (!isset($GLOBALS["pdti"])) {
			$GLOBALS["pdti"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["pdti"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'pdti', TRUE);

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
			$this->Page_Terminate("pdtilist.php");
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
			if (@$_GET["nu_periodo"] != "") {
				$this->nu_periodo->setQueryStringValue($_GET["nu_periodo"]);
				$this->setKey("nu_periodo", $this->nu_periodo->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_periodo", ""); // Clear key
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
					$this->Page_Terminate("pdtilist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "pdtiview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
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
		$this->im_planoTrabalho->Upload->Index = $objForm->Index;
		if ($this->im_planoTrabalho->Upload->UploadFile()) {

			// No action required
		} else {
			echo $this->im_planoTrabalho->Upload->Message;
			$this->Page_Terminate();
			exit();
		}
		$this->im_planoTrabalho->CurrentValue = $this->im_planoTrabalho->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->nu_periodo->CurrentValue = NULL;
		$this->nu_periodo->OldValue = $this->nu_periodo->CurrentValue;
		$this->no_tituloCapa->CurrentValue = NULL;
		$this->no_tituloCapa->OldValue = $this->no_tituloCapa->CurrentValue;
		$this->ds_apresentacao->CurrentValue = NULL;
		$this->ds_apresentacao->OldValue = $this->ds_apresentacao->CurrentValue;
		$this->ds_introducao->CurrentValue = NULL;
		$this->ds_introducao->OldValue = $this->ds_introducao->CurrentValue;
		$this->no_localArquivo->CurrentValue = NULL;
		$this->no_localArquivo->OldValue = $this->no_localArquivo->CurrentValue;
		$this->im_planoTrabalho->Upload->DbValue = NULL;
		$this->im_planoTrabalho->OldValue = $this->im_planoTrabalho->Upload->DbValue;
		$this->im_planoTrabalho->CurrentValue = NULL; // Clear file related field
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->nu_periodo->FldIsDetailKey) {
			$this->nu_periodo->setFormValue($objForm->GetValue("x_nu_periodo"));
		}
		if (!$this->no_tituloCapa->FldIsDetailKey) {
			$this->no_tituloCapa->setFormValue($objForm->GetValue("x_no_tituloCapa"));
		}
		if (!$this->ds_apresentacao->FldIsDetailKey) {
			$this->ds_apresentacao->setFormValue($objForm->GetValue("x_ds_apresentacao"));
		}
		if (!$this->ds_introducao->FldIsDetailKey) {
			$this->ds_introducao->setFormValue($objForm->GetValue("x_ds_introducao"));
		}
		if (!$this->no_localArquivo->FldIsDetailKey) {
			$this->no_localArquivo->setFormValue($objForm->GetValue("x_no_localArquivo"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->nu_periodo->CurrentValue = $this->nu_periodo->FormValue;
		$this->no_tituloCapa->CurrentValue = $this->no_tituloCapa->FormValue;
		$this->ds_apresentacao->CurrentValue = $this->ds_apresentacao->FormValue;
		$this->ds_introducao->CurrentValue = $this->ds_introducao->FormValue;
		$this->no_localArquivo->CurrentValue = $this->no_localArquivo->FormValue;
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
		$this->nu_periodo->setDbValue($rs->fields('nu_periodo'));
		$this->no_tituloCapa->setDbValue($rs->fields('no_tituloCapa'));
		$this->ds_apresentacao->setDbValue($rs->fields('ds_apresentacao'));
		$this->ds_introducao->setDbValue($rs->fields('ds_introducao'));
		$this->no_localArquivo->setDbValue($rs->fields('no_localArquivo'));
		$this->im_planoTrabalho->Upload->DbValue = $rs->fields('im_planoTrabalho');
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_periodo->DbValue = $row['nu_periodo'];
		$this->no_tituloCapa->DbValue = $row['no_tituloCapa'];
		$this->ds_apresentacao->DbValue = $row['ds_apresentacao'];
		$this->ds_introducao->DbValue = $row['ds_introducao'];
		$this->no_localArquivo->DbValue = $row['no_localArquivo'];
		$this->im_planoTrabalho->Upload->DbValue = $row['im_planoTrabalho'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_periodo")) <> "")
			$this->nu_periodo->CurrentValue = $this->getKey("nu_periodo"); // nu_periodo
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
		// nu_periodo
		// no_tituloCapa
		// ds_apresentacao
		// ds_introducao
		// no_localArquivo
		// im_planoTrabalho

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_periodo
			if (strval($this->nu_periodo->CurrentValue) <> "") {
				$sFilterWrk = "[nu_periodo]" . ew_SearchString("=", $this->nu_periodo->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT DISTINCT [nu_periodo], [no_periodo] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[perplanejamento]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_periodo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_periodo] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_periodo->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_periodo->ViewValue = $this->nu_periodo->CurrentValue;
				}
			} else {
				$this->nu_periodo->ViewValue = NULL;
			}
			$this->nu_periodo->ViewCustomAttributes = "";

			// no_tituloCapa
			$this->no_tituloCapa->ViewValue = $this->no_tituloCapa->CurrentValue;
			$this->no_tituloCapa->ViewCustomAttributes = "";

			// ds_apresentacao
			$this->ds_apresentacao->ViewValue = $this->ds_apresentacao->CurrentValue;
			$this->ds_apresentacao->ViewCustomAttributes = "";

			// ds_introducao
			$this->ds_introducao->ViewValue = $this->ds_introducao->CurrentValue;
			$this->ds_introducao->ViewCustomAttributes = "";

			// no_localArquivo
			$this->no_localArquivo->ViewValue = $this->no_localArquivo->CurrentValue;
			$this->no_localArquivo->ViewCustomAttributes = "";

			// im_planoTrabalho
			$this->im_planoTrabalho->UploadPath = "arquivos/planos_pdti";
			if (!ew_Empty($this->im_planoTrabalho->Upload->DbValue)) {
				$this->im_planoTrabalho->ViewValue = $this->im_planoTrabalho->Upload->DbValue;
			} else {
				$this->im_planoTrabalho->ViewValue = "";
			}
			$this->im_planoTrabalho->ViewCustomAttributes = "";

			// nu_periodo
			$this->nu_periodo->LinkCustomAttributes = "";
			$this->nu_periodo->HrefValue = "";
			$this->nu_periodo->TooltipValue = "";

			// no_tituloCapa
			$this->no_tituloCapa->LinkCustomAttributes = "";
			$this->no_tituloCapa->HrefValue = "";
			$this->no_tituloCapa->TooltipValue = "";

			// ds_apresentacao
			$this->ds_apresentacao->LinkCustomAttributes = "";
			$this->ds_apresentacao->HrefValue = "";
			$this->ds_apresentacao->TooltipValue = "";

			// ds_introducao
			$this->ds_introducao->LinkCustomAttributes = "";
			$this->ds_introducao->HrefValue = "";
			$this->ds_introducao->TooltipValue = "";

			// no_localArquivo
			$this->no_localArquivo->LinkCustomAttributes = "";
			$this->no_localArquivo->HrefValue = "";
			$this->no_localArquivo->TooltipValue = "";

			// im_planoTrabalho
			$this->im_planoTrabalho->LinkCustomAttributes = "";
			$this->im_planoTrabalho->UploadPath = "arquivos/planos_pdti";
			if (!ew_Empty($this->im_planoTrabalho->Upload->DbValue)) {
				$this->im_planoTrabalho->HrefValue = "%u"; // Add prefix/suffix
				$this->im_planoTrabalho->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->im_planoTrabalho->HrefValue = ew_ConvertFullUrl($this->im_planoTrabalho->HrefValue);
			} else {
				$this->im_planoTrabalho->HrefValue = "";
			}
			$this->im_planoTrabalho->HrefValue2 = $this->im_planoTrabalho->UploadPath . $this->im_planoTrabalho->Upload->DbValue;
			$this->im_planoTrabalho->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nu_periodo
			$this->nu_periodo->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT DISTINCT [nu_periodo], [no_periodo] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[perplanejamento]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_periodo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_periodo] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_periodo->EditValue = $arwrk;

			// no_tituloCapa
			$this->no_tituloCapa->EditCustomAttributes = "";
			$this->no_tituloCapa->EditValue = ew_HtmlEncode($this->no_tituloCapa->CurrentValue);
			$this->no_tituloCapa->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_tituloCapa->FldCaption()));

			// ds_apresentacao
			$this->ds_apresentacao->EditCustomAttributes = "";
			$this->ds_apresentacao->EditValue = $this->ds_apresentacao->CurrentValue;
			$this->ds_apresentacao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_apresentacao->FldCaption()));

			// ds_introducao
			$this->ds_introducao->EditCustomAttributes = "";
			$this->ds_introducao->EditValue = $this->ds_introducao->CurrentValue;
			$this->ds_introducao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_introducao->FldCaption()));

			// no_localArquivo
			$this->no_localArquivo->EditCustomAttributes = "";
			$this->no_localArquivo->EditValue = ew_HtmlEncode($this->no_localArquivo->CurrentValue);
			$this->no_localArquivo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_localArquivo->FldCaption()));

			// im_planoTrabalho
			$this->im_planoTrabalho->EditCustomAttributes = "";
			$this->im_planoTrabalho->UploadPath = "arquivos/planos_pdti";
			if (!ew_Empty($this->im_planoTrabalho->Upload->DbValue)) {
				$this->im_planoTrabalho->EditValue = $this->im_planoTrabalho->Upload->DbValue;
			} else {
				$this->im_planoTrabalho->EditValue = "";
			}
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->im_planoTrabalho);

			// Edit refer script
			// nu_periodo

			$this->nu_periodo->HrefValue = "";

			// no_tituloCapa
			$this->no_tituloCapa->HrefValue = "";

			// ds_apresentacao
			$this->ds_apresentacao->HrefValue = "";

			// ds_introducao
			$this->ds_introducao->HrefValue = "";

			// no_localArquivo
			$this->no_localArquivo->HrefValue = "";

			// im_planoTrabalho
			$this->im_planoTrabalho->UploadPath = "arquivos/planos_pdti";
			if (!ew_Empty($this->im_planoTrabalho->Upload->DbValue)) {
				$this->im_planoTrabalho->HrefValue = "%u"; // Add prefix/suffix
				$this->im_planoTrabalho->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->im_planoTrabalho->HrefValue = ew_ConvertFullUrl($this->im_planoTrabalho->HrefValue);
			} else {
				$this->im_planoTrabalho->HrefValue = "";
			}
			$this->im_planoTrabalho->HrefValue2 = $this->im_planoTrabalho->UploadPath . $this->im_planoTrabalho->Upload->DbValue;
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
		if (!$this->nu_periodo->FldIsDetailKey && !is_null($this->nu_periodo->FormValue) && $this->nu_periodo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_periodo->FldCaption());
		}
		if (!$this->no_tituloCapa->FldIsDetailKey && !is_null($this->no_tituloCapa->FormValue) && $this->no_tituloCapa->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_tituloCapa->FldCaption());
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
		if ($this->nu_periodo->CurrentValue <> "") { // Check field with unique index
			$sFilter = "(nu_periodo = " . ew_AdjustSql($this->nu_periodo->CurrentValue) . ")";
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->nu_periodo->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->nu_periodo->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
		}

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
			$this->im_planoTrabalho->OldUploadPath = "arquivos/planos_pdti";
			$this->im_planoTrabalho->UploadPath = $this->im_planoTrabalho->OldUploadPath;
		}
		$rsnew = array();

		// nu_periodo
		$this->nu_periodo->SetDbValueDef($rsnew, $this->nu_periodo->CurrentValue, 0, FALSE);

		// no_tituloCapa
		$this->no_tituloCapa->SetDbValueDef($rsnew, $this->no_tituloCapa->CurrentValue, NULL, FALSE);

		// ds_apresentacao
		$this->ds_apresentacao->SetDbValueDef($rsnew, $this->ds_apresentacao->CurrentValue, NULL, FALSE);

		// ds_introducao
		$this->ds_introducao->SetDbValueDef($rsnew, $this->ds_introducao->CurrentValue, NULL, FALSE);

		// no_localArquivo
		$this->no_localArquivo->SetDbValueDef($rsnew, $this->no_localArquivo->CurrentValue, NULL, FALSE);

		// im_planoTrabalho
		if (!$this->im_planoTrabalho->Upload->KeepFile) {
			if ($this->im_planoTrabalho->Upload->FileName == "") {
				$rsnew['im_planoTrabalho'] = NULL;
			} else {
				$rsnew['im_planoTrabalho'] = $this->im_planoTrabalho->Upload->FileName;
			}
		}
		if (!$this->im_planoTrabalho->Upload->KeepFile) {
			$this->im_planoTrabalho->UploadPath = "arquivos/planos_pdti";
			$OldFiles = explode(",", $this->im_planoTrabalho->Upload->DbValue);
			if (!ew_Empty($this->im_planoTrabalho->Upload->FileName)) {
				$NewFiles = explode(",", $this->im_planoTrabalho->Upload->FileName);
				$FileCount = count($NewFiles);
				for ($i = 0; $i < $FileCount; $i++) {
					$fldvar = ($this->im_planoTrabalho->Upload->Index < 0) ? $this->im_planoTrabalho->FldVar : substr($this->im_planoTrabalho->FldVar, 0, 1) . $this->im_planoTrabalho->Upload->Index . substr($this->im_planoTrabalho->FldVar, 1);
					if ($NewFiles[$i] <> "") {
						$file = ew_UploadTempPath($fldvar) . EW_PATH_DELIMITER . $NewFiles[$i];
						if (file_exists($file)) {
							if (!in_array($NewFiles[$i], $OldFiles)) {
								$NewFiles[$i] = ew_UploadFileNameEx($this->im_planoTrabalho->UploadPath, $NewFiles[$i]); // Get new file name
								$file1 = ew_UploadTempPath($fldvar) . EW_PATH_DELIMITER . $NewFiles[$i];
								if ($file1 <> $file) // Rename temp file
									rename($file, $file1);
							}
						}
					}
				}
				$this->im_planoTrabalho->Upload->FileName = implode(",", $NewFiles);
				$rsnew['im_planoTrabalho'] = $this->im_planoTrabalho->Upload->FileName;
			} else {
				$NewFiles = array();
			}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && $this->nu_periodo->CurrentValue == "" && $this->nu_periodo->getSessionValue() == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check for duplicate key
		if ($bInsertRow && $this->ValidateKey) {
			$sFilter = $this->KeyFilter();
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sKeyErrMsg = str_replace("%f", $sFilter, $Language->Phrase("DupKey"));
				$this->setFailureMessage($sKeyErrMsg);
				$rsChk->Close();
				$bInsertRow = FALSE;
			}
		}
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
				if (!$this->im_planoTrabalho->Upload->KeepFile) {
					$OldFiles = explode(",", $this->im_planoTrabalho->Upload->DbValue);
					if (!ew_Empty($this->im_planoTrabalho->Upload->FileName)) {
						$NewFiles = explode(",", $this->im_planoTrabalho->Upload->FileName);
						$NewFiles2 = explode(",", $rsnew['im_planoTrabalho']);
						$FileCount = count($NewFiles);
						for ($i = 0; $i < $FileCount; $i++) {
							$fldvar = ($this->im_planoTrabalho->Upload->Index < 0) ? $this->im_planoTrabalho->FldVar : substr($this->im_planoTrabalho->FldVar, 0, 1) . $this->im_planoTrabalho->Upload->Index . substr($this->im_planoTrabalho->FldVar, 1);
							if ($NewFiles[$i] <> "") {
								$file = ew_UploadTempPath($fldvar) . EW_PATH_DELIMITER . $NewFiles[$i];
								if (file_exists($file)) {
									$this->im_planoTrabalho->Upload->Value = file_get_contents($file);
									$this->im_planoTrabalho->Upload->SaveToFile($this->im_planoTrabalho->UploadPath, (@$NewFiles2[$i] <> "") ? $NewFiles2[$i] : $NewFiles[$i], TRUE); // Just replace
								}
							}
						}
					} else {
						$NewFiles = array();
					}
					$FileCount = count($OldFiles);
					for ($i = 0; $i < $FileCount; $i++) {
						if ($OldFiles[$i] <> "" && !in_array($OldFiles[$i], $NewFiles))
							@unlink(ew_UploadPathEx(TRUE, $this->im_planoTrabalho->OldUploadPath) . $OldFiles[$i]);
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
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Get insert id if necessary
		if ($AddRow) {
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}

		// im_planoTrabalho
		ew_CleanUploadTempPath($this->im_planoTrabalho, $this->im_planoTrabalho->Upload->Index);
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "pdtilist.php", $this->TableVar);
		$PageCaption = ($this->CurrentAction == "C") ? $Language->Phrase("Copy") : $Language->Phrase("Add");
		$Breadcrumb->Add("add", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
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
if (!isset($pdti_add)) $pdti_add = new cpdti_add();

// Page init
$pdti_add->Page_Init();

// Page main
$pdti_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pdti_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var pdti_add = new ew_Page("pdti_add");
pdti_add.PageID = "add"; // Page ID
var EW_PAGE_ID = pdti_add.PageID; // For backward compatibility

// Form object
var fpdtiadd = new ew_Form("fpdtiadd");

// Validate form
fpdtiadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_periodo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($pdti->nu_periodo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no_tituloCapa");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($pdti->no_tituloCapa->FldCaption()) ?>");

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
fpdtiadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpdtiadd.ValidateRequired = true;
<?php } else { ?>
fpdtiadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fpdtiadd.Lists["x_nu_periodo"] = {"LinkField":"x_nu_periodo","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_periodo","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $pdti_add->ShowPageHeader(); ?>
<?php
$pdti_add->ShowMessage();
?>
<form name="fpdtiadd" id="fpdtiadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="pdti">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_pdtiadd" class="table table-bordered table-striped">
<?php if ($pdti->nu_periodo->Visible) { // nu_periodo ?>
	<tr id="r_nu_periodo">
		<td><span id="elh_pdti_nu_periodo"><?php echo $pdti->nu_periodo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $pdti->nu_periodo->CellAttributes() ?>>
<span id="el_pdti_nu_periodo" class="control-group">
<select data-field="x_nu_periodo" id="x_nu_periodo" name="x_nu_periodo"<?php echo $pdti->nu_periodo->EditAttributes() ?>>
<?php
if (is_array($pdti->nu_periodo->EditValue)) {
	$arwrk = $pdti->nu_periodo->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pdti->nu_periodo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if (AllowAdd(CurrentProjectID() . "perplanejamento")) { ?>
&nbsp;<a id="aol_x_nu_periodo" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_nu_periodo',url:'perplanejamentoaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $pdti->nu_periodo->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
fpdtiadd.Lists["x_nu_periodo"].Options = <?php echo (is_array($pdti->nu_periodo->EditValue)) ? ew_ArrayToJson($pdti->nu_periodo->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $pdti->nu_periodo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($pdti->no_tituloCapa->Visible) { // no_tituloCapa ?>
	<tr id="r_no_tituloCapa">
		<td><span id="elh_pdti_no_tituloCapa"><?php echo $pdti->no_tituloCapa->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $pdti->no_tituloCapa->CellAttributes() ?>>
<span id="el_pdti_no_tituloCapa" class="control-group">
<input type="text" data-field="x_no_tituloCapa" name="x_no_tituloCapa" id="x_no_tituloCapa" size="30" maxlength="100" placeholder="<?php echo $pdti->no_tituloCapa->PlaceHolder ?>" value="<?php echo $pdti->no_tituloCapa->EditValue ?>"<?php echo $pdti->no_tituloCapa->EditAttributes() ?>>
</span>
<?php echo $pdti->no_tituloCapa->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($pdti->ds_apresentacao->Visible) { // ds_apresentacao ?>
	<tr id="r_ds_apresentacao">
		<td><span id="elh_pdti_ds_apresentacao"><?php echo $pdti->ds_apresentacao->FldCaption() ?></span></td>
		<td<?php echo $pdti->ds_apresentacao->CellAttributes() ?>>
<span id="el_pdti_ds_apresentacao" class="control-group">
<textarea data-field="x_ds_apresentacao" name="x_ds_apresentacao" id="x_ds_apresentacao" cols="35" rows="4" placeholder="<?php echo $pdti->ds_apresentacao->PlaceHolder ?>"<?php echo $pdti->ds_apresentacao->EditAttributes() ?>><?php echo $pdti->ds_apresentacao->EditValue ?></textarea>
</span>
<?php echo $pdti->ds_apresentacao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($pdti->ds_introducao->Visible) { // ds_introducao ?>
	<tr id="r_ds_introducao">
		<td><span id="elh_pdti_ds_introducao"><?php echo $pdti->ds_introducao->FldCaption() ?></span></td>
		<td<?php echo $pdti->ds_introducao->CellAttributes() ?>>
<span id="el_pdti_ds_introducao" class="control-group">
<textarea data-field="x_ds_introducao" name="x_ds_introducao" id="x_ds_introducao" cols="35" rows="4" placeholder="<?php echo $pdti->ds_introducao->PlaceHolder ?>"<?php echo $pdti->ds_introducao->EditAttributes() ?>><?php echo $pdti->ds_introducao->EditValue ?></textarea>
</span>
<?php echo $pdti->ds_introducao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($pdti->no_localArquivo->Visible) { // no_localArquivo ?>
	<tr id="r_no_localArquivo">
		<td><span id="elh_pdti_no_localArquivo"><?php echo $pdti->no_localArquivo->FldCaption() ?></span></td>
		<td<?php echo $pdti->no_localArquivo->CellAttributes() ?>>
<span id="el_pdti_no_localArquivo" class="control-group">
<input type="text" data-field="x_no_localArquivo" name="x_no_localArquivo" id="x_no_localArquivo" size="30" maxlength="255" placeholder="<?php echo $pdti->no_localArquivo->PlaceHolder ?>" value="<?php echo $pdti->no_localArquivo->EditValue ?>"<?php echo $pdti->no_localArquivo->EditAttributes() ?>>
</span>
<?php echo $pdti->no_localArquivo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($pdti->im_planoTrabalho->Visible) { // im_planoTrabalho ?>
	<tr id="r_im_planoTrabalho">
		<td><span id="elh_pdti_im_planoTrabalho"><?php echo $pdti->im_planoTrabalho->FldCaption() ?></span></td>
		<td<?php echo $pdti->im_planoTrabalho->CellAttributes() ?>>
<span id="el_pdti_im_planoTrabalho" class="control-group">
<span id="fd_x_im_planoTrabalho">
<span class="btn btn-small fileinput-button">
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_im_planoTrabalho" name="x_im_planoTrabalho" id="x_im_planoTrabalho" multiple="multiple">
</span>
<input type="hidden" name="fn_x_im_planoTrabalho" id= "fn_x_im_planoTrabalho" value="<?php echo $pdti->im_planoTrabalho->Upload->FileName ?>">
<input type="hidden" name="fa_x_im_planoTrabalho" id= "fa_x_im_planoTrabalho" value="0">
<input type="hidden" name="fs_x_im_planoTrabalho" id= "fs_x_im_planoTrabalho" value="2147483647">
</span>
<table id="ft_x_im_planoTrabalho" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $pdti->im_planoTrabalho->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fpdtiadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$pdti_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$pdti_add->Page_Terminate();
?>
