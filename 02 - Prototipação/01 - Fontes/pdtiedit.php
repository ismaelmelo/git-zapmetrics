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

$pdti_edit = NULL; // Initialize page object first

class cpdti_edit extends cpdti {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'pdti';

	// Page object name
	var $PageObjName = 'pdti_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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
		if (!$Security->CanEdit()) {
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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["nu_periodo"] <> "") {
			$this->nu_periodo->setQueryStringValue($_GET["nu_periodo"]);
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
		if ($this->nu_periodo->CurrentValue == "")
			$this->Page_Terminate("pdtilist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("pdtilist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "pdtiview.php")
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
		$this->LoadRow();
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// nu_periodo
			$this->nu_periodo->EditCustomAttributes = "";
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
					$this->nu_periodo->EditValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_periodo->EditValue = $this->nu_periodo->CurrentValue;
				}
			} else {
				$this->nu_periodo->EditValue = NULL;
			}
			$this->nu_periodo->ViewCustomAttributes = "";

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
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->im_planoTrabalho);

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
			$this->im_planoTrabalho->OldUploadPath = "arquivos/planos_pdti";
			$this->im_planoTrabalho->UploadPath = $this->im_planoTrabalho->OldUploadPath;
			$rsnew = array();

			// no_tituloCapa
			$this->no_tituloCapa->SetDbValueDef($rsnew, $this->no_tituloCapa->CurrentValue, NULL, $this->no_tituloCapa->ReadOnly);

			// ds_apresentacao
			$this->ds_apresentacao->SetDbValueDef($rsnew, $this->ds_apresentacao->CurrentValue, NULL, $this->ds_apresentacao->ReadOnly);

			// ds_introducao
			$this->ds_introducao->SetDbValueDef($rsnew, $this->ds_introducao->CurrentValue, NULL, $this->ds_introducao->ReadOnly);

			// no_localArquivo
			$this->no_localArquivo->SetDbValueDef($rsnew, $this->no_localArquivo->CurrentValue, NULL, $this->no_localArquivo->ReadOnly);

			// im_planoTrabalho
			if (!($this->im_planoTrabalho->ReadOnly) && !$this->im_planoTrabalho->Upload->KeepFile) {
				$this->im_planoTrabalho->Upload->DbValue = $rs->fields('im_planoTrabalho'); // Get original value
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
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();

		// im_planoTrabalho
		ew_CleanUploadTempPath($this->im_planoTrabalho, $this->im_planoTrabalho->Upload->Index);
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "pdtilist.php", $this->TableVar);
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
if (!isset($pdti_edit)) $pdti_edit = new cpdti_edit();

// Page init
$pdti_edit->Page_Init();

// Page main
$pdti_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pdti_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var pdti_edit = new ew_Page("pdti_edit");
pdti_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = pdti_edit.PageID; // For backward compatibility

// Form object
var fpdtiedit = new ew_Form("fpdtiedit");

// Validate form
fpdtiedit.Validate = function() {
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
fpdtiedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpdtiedit.ValidateRequired = true;
<?php } else { ?>
fpdtiedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fpdtiedit.Lists["x_nu_periodo"] = {"LinkField":"x_nu_periodo","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_periodo","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $pdti_edit->ShowPageHeader(); ?>
<?php
$pdti_edit->ShowMessage();
?>
<form name="fpdtiedit" id="fpdtiedit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="pdti">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_pdtiedit" class="table table-bordered table-striped">
<?php if ($pdti->nu_periodo->Visible) { // nu_periodo ?>
	<tr id="r_nu_periodo">
		<td><span id="elh_pdti_nu_periodo"><?php echo $pdti->nu_periodo->FldCaption() ?></span></td>
		<td<?php echo $pdti->nu_periodo->CellAttributes() ?>>
<span id="el_pdti_nu_periodo" class="control-group">
<span<?php echo $pdti->nu_periodo->ViewAttributes() ?>>
<?php echo $pdti->nu_periodo->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_periodo" name="x_nu_periodo" id="x_nu_periodo" value="<?php echo ew_HtmlEncode($pdti->nu_periodo->CurrentValue) ?>">
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
<?php if (@$_POST["fa_x_im_planoTrabalho"] == "0") { ?>
<input type="hidden" name="fa_x_im_planoTrabalho" id= "fa_x_im_planoTrabalho" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_im_planoTrabalho" id= "fa_x_im_planoTrabalho" value="1">
<?php } ?>
<input type="hidden" name="fs_x_im_planoTrabalho" id= "fs_x_im_planoTrabalho" value="2147483647">
</span>
<table id="ft_x_im_planoTrabalho" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $pdti->im_planoTrabalho->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fpdtiedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$pdti_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$pdti_edit->Page_Terminate();
?>
