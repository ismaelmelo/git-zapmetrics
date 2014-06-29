<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "mensageminfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$mensagem_add = NULL; // Initialize page object first

class cmensagem_add extends cmensagem {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'mensagem';

	// Page object name
	var $PageObjName = 'mensagem_add';

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
	var $AuditTrailOnAdd = TRUE;

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

		// Table object (mensagem)
		if (!isset($GLOBALS["mensagem"])) {
			$GLOBALS["mensagem"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["mensagem"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'mensagem', TRUE);

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
			$this->Page_Terminate("mensagemlist.php");
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
			if (@$_GET["nu_mensagem"] != "") {
				$this->nu_mensagem->setQueryStringValue($_GET["nu_mensagem"]);
				$this->setKey("nu_mensagem", $this->nu_mensagem->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_mensagem", ""); // Clear key
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
					$this->Page_Terminate("mensagemlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "mensagemview.php")
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
	}

	// Load default values
	function LoadDefaultValues() {
		$this->co_alternativo->CurrentValue = NULL;
		$this->co_alternativo->OldValue = $this->co_alternativo->CurrentValue;
		$this->no_mensagem->CurrentValue = NULL;
		$this->no_mensagem->OldValue = $this->no_mensagem->CurrentValue;
		$this->ds_mensagem->CurrentValue = NULL;
		$this->ds_mensagem->OldValue = $this->ds_mensagem->CurrentValue;
		$this->ds_ajuda->CurrentValue = NULL;
		$this->ds_ajuda->OldValue = $this->ds_ajuda->CurrentValue;
		$this->nu_stMensagem->CurrentValue = NULL;
		$this->nu_stMensagem->OldValue = $this->nu_stMensagem->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->co_alternativo->FldIsDetailKey) {
			$this->co_alternativo->setFormValue($objForm->GetValue("x_co_alternativo"));
		}
		if (!$this->no_mensagem->FldIsDetailKey) {
			$this->no_mensagem->setFormValue($objForm->GetValue("x_no_mensagem"));
		}
		if (!$this->ds_mensagem->FldIsDetailKey) {
			$this->ds_mensagem->setFormValue($objForm->GetValue("x_ds_mensagem"));
		}
		if (!$this->ds_ajuda->FldIsDetailKey) {
			$this->ds_ajuda->setFormValue($objForm->GetValue("x_ds_ajuda"));
		}
		if (!$this->nu_stMensagem->FldIsDetailKey) {
			$this->nu_stMensagem->setFormValue($objForm->GetValue("x_nu_stMensagem"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->co_alternativo->CurrentValue = $this->co_alternativo->FormValue;
		$this->no_mensagem->CurrentValue = $this->no_mensagem->FormValue;
		$this->ds_mensagem->CurrentValue = $this->ds_mensagem->FormValue;
		$this->ds_ajuda->CurrentValue = $this->ds_ajuda->FormValue;
		$this->nu_stMensagem->CurrentValue = $this->nu_stMensagem->FormValue;
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
		$this->nu_mensagem->setDbValue($rs->fields('nu_mensagem'));
		$this->co_alternativo->setDbValue($rs->fields('co_alternativo'));
		$this->no_mensagem->setDbValue($rs->fields('no_mensagem'));
		$this->ds_mensagem->setDbValue($rs->fields('ds_mensagem'));
		$this->ds_ajuda->setDbValue($rs->fields('ds_ajuda'));
		$this->nu_stMensagem->setDbValue($rs->fields('nu_stMensagem'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_mensagem->DbValue = $row['nu_mensagem'];
		$this->co_alternativo->DbValue = $row['co_alternativo'];
		$this->no_mensagem->DbValue = $row['no_mensagem'];
		$this->ds_mensagem->DbValue = $row['ds_mensagem'];
		$this->ds_ajuda->DbValue = $row['ds_ajuda'];
		$this->nu_stMensagem->DbValue = $row['nu_stMensagem'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_mensagem")) <> "")
			$this->nu_mensagem->CurrentValue = $this->getKey("nu_mensagem"); // nu_mensagem
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
		// nu_mensagem
		// co_alternativo
		// no_mensagem
		// ds_mensagem
		// ds_ajuda
		// nu_stMensagem

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// co_alternativo
			$this->co_alternativo->ViewValue = $this->co_alternativo->CurrentValue;
			$this->co_alternativo->ViewCustomAttributes = "";

			// no_mensagem
			$this->no_mensagem->ViewValue = $this->no_mensagem->CurrentValue;
			$this->no_mensagem->ViewCustomAttributes = "";

			// ds_mensagem
			$this->ds_mensagem->ViewValue = $this->ds_mensagem->CurrentValue;
			$this->ds_mensagem->ViewCustomAttributes = "";

			// ds_ajuda
			$this->ds_ajuda->ViewValue = $this->ds_ajuda->CurrentValue;
			$this->ds_ajuda->ViewCustomAttributes = "";

			// nu_stMensagem
			if (strval($this->nu_stMensagem->CurrentValue) <> "") {
				$sFilterWrk = "[nu_stMensagem]" . ew_SearchString("=", $this->nu_stMensagem->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_stMensagem], [no_stMensagem] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[stmensagem]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_stMensagem, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_stMensagem->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_stMensagem->ViewValue = $this->nu_stMensagem->CurrentValue;
				}
			} else {
				$this->nu_stMensagem->ViewValue = NULL;
			}
			$this->nu_stMensagem->ViewCustomAttributes = "";

			// co_alternativo
			$this->co_alternativo->LinkCustomAttributes = "";
			$this->co_alternativo->HrefValue = "";
			$this->co_alternativo->TooltipValue = "";

			// no_mensagem
			$this->no_mensagem->LinkCustomAttributes = "";
			$this->no_mensagem->HrefValue = "";
			$this->no_mensagem->TooltipValue = "";

			// ds_mensagem
			$this->ds_mensagem->LinkCustomAttributes = "";
			$this->ds_mensagem->HrefValue = "";
			$this->ds_mensagem->TooltipValue = "";

			// ds_ajuda
			$this->ds_ajuda->LinkCustomAttributes = "";
			$this->ds_ajuda->HrefValue = "";
			$this->ds_ajuda->TooltipValue = "";

			// nu_stMensagem
			$this->nu_stMensagem->LinkCustomAttributes = "";
			$this->nu_stMensagem->HrefValue = "";
			$this->nu_stMensagem->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// co_alternativo
			$this->co_alternativo->EditCustomAttributes = "readonly";
			$this->co_alternativo->EditValue = ew_HtmlEncode($this->co_alternativo->CurrentValue);
			$this->co_alternativo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->co_alternativo->FldCaption()));

			// no_mensagem
			$this->no_mensagem->EditCustomAttributes = "";
			$this->no_mensagem->EditValue = ew_HtmlEncode($this->no_mensagem->CurrentValue);
			$this->no_mensagem->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_mensagem->FldCaption()));

			// ds_mensagem
			$this->ds_mensagem->EditCustomAttributes = "";
			$this->ds_mensagem->EditValue = $this->ds_mensagem->CurrentValue;
			$this->ds_mensagem->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_mensagem->FldCaption()));

			// ds_ajuda
			$this->ds_ajuda->EditCustomAttributes = "";
			$this->ds_ajuda->EditValue = $this->ds_ajuda->CurrentValue;
			$this->ds_ajuda->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_ajuda->FldCaption()));

			// nu_stMensagem
			$this->nu_stMensagem->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_stMensagem], [no_stMensagem] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[stmensagem]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_stMensagem, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_stMensagem->EditValue = $arwrk;

			// Edit refer script
			// co_alternativo

			$this->co_alternativo->HrefValue = "";

			// no_mensagem
			$this->no_mensagem->HrefValue = "";

			// ds_mensagem
			$this->ds_mensagem->HrefValue = "";

			// ds_ajuda
			$this->ds_ajuda->HrefValue = "";

			// nu_stMensagem
			$this->nu_stMensagem->HrefValue = "";
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
		if (!$this->co_alternativo->FldIsDetailKey && !is_null($this->co_alternativo->FormValue) && $this->co_alternativo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->co_alternativo->FldCaption());
		}
		if (!$this->no_mensagem->FldIsDetailKey && !is_null($this->no_mensagem->FormValue) && $this->no_mensagem->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_mensagem->FldCaption());
		}
		if (!$this->nu_stMensagem->FldIsDetailKey && !is_null($this->nu_stMensagem->FormValue) && $this->nu_stMensagem->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_stMensagem->FldCaption());
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

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// co_alternativo
		$this->co_alternativo->SetDbValueDef($rsnew, $this->co_alternativo->CurrentValue, "", FALSE);

		// no_mensagem
		$this->no_mensagem->SetDbValueDef($rsnew, $this->no_mensagem->CurrentValue, "", FALSE);

		// ds_mensagem
		$this->ds_mensagem->SetDbValueDef($rsnew, $this->ds_mensagem->CurrentValue, NULL, FALSE);

		// ds_ajuda
		$this->ds_ajuda->SetDbValueDef($rsnew, $this->ds_ajuda->CurrentValue, NULL, FALSE);

		// nu_stMensagem
		$this->nu_stMensagem->SetDbValueDef($rsnew, $this->nu_stMensagem->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
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
			$this->nu_mensagem->setDbValue($conn->Insert_ID());
			$rsnew['nu_mensagem'] = $this->nu_mensagem->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
			$this->WriteAuditTrailOnAdd($rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "mensagemlist.php", $this->TableVar);
		$PageCaption = ($this->CurrentAction == "C") ? $Language->Phrase("Copy") : $Language->Phrase("Add");
		$Breadcrumb->Add("add", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'mensagem';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'mensagem';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_mensagem'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
	  $usr = CurrentUserID();
		foreach (array_keys($rs) as $fldname) {
			if ($this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$newvalue = $rs[$fldname];
					else
						$newvalue = "[MEMO]"; // Memo Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$newvalue = "[XML]"; // XML Field
				} else {
					$newvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $usr, "A", $table, $fldname, $key, "", $newvalue);
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
if (!isset($mensagem_add)) $mensagem_add = new cmensagem_add();

// Page init
$mensagem_add->Page_Init();

// Page main
$mensagem_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$mensagem_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var mensagem_add = new ew_Page("mensagem_add");
mensagem_add.PageID = "add"; // Page ID
var EW_PAGE_ID = mensagem_add.PageID; // For backward compatibility

// Form object
var fmensagemadd = new ew_Form("fmensagemadd");

// Validate form
fmensagemadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_co_alternativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($mensagem->co_alternativo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no_mensagem");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($mensagem->no_mensagem->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_stMensagem");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($mensagem->nu_stMensagem->FldCaption()) ?>");

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
fmensagemadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmensagemadd.ValidateRequired = true;
<?php } else { ?>
fmensagemadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fmensagemadd.Lists["x_nu_stMensagem"] = {"LinkField":"x_nu_stMensagem","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_stMensagem","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $mensagem_add->ShowPageHeader(); ?>
<?php
$mensagem_add->ShowMessage();
?>
<form name="fmensagemadd" id="fmensagemadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="mensagem">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_mensagemadd" class="table table-bordered table-striped">
<?php if ($mensagem->co_alternativo->Visible) { // co_alternativo ?>
	<tr id="r_co_alternativo">
		<td><span id="elh_mensagem_co_alternativo"><?php echo $mensagem->co_alternativo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $mensagem->co_alternativo->CellAttributes() ?>>
<span id="el_mensagem_co_alternativo" class="control-group">
<input type="text" data-field="x_co_alternativo" name="x_co_alternativo" id="x_co_alternativo" size="30" maxlength="20" placeholder="<?php echo $mensagem->co_alternativo->PlaceHolder ?>" value="<?php echo $mensagem->co_alternativo->EditValue ?>"<?php echo $mensagem->co_alternativo->EditAttributes() ?>>
</span>
<?php echo $mensagem->co_alternativo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($mensagem->no_mensagem->Visible) { // no_mensagem ?>
	<tr id="r_no_mensagem">
		<td><span id="elh_mensagem_no_mensagem"><?php echo $mensagem->no_mensagem->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $mensagem->no_mensagem->CellAttributes() ?>>
<span id="el_mensagem_no_mensagem" class="control-group">
<input type="text" data-field="x_no_mensagem" name="x_no_mensagem" id="x_no_mensagem" size="30" maxlength="120" placeholder="<?php echo $mensagem->no_mensagem->PlaceHolder ?>" value="<?php echo $mensagem->no_mensagem->EditValue ?>"<?php echo $mensagem->no_mensagem->EditAttributes() ?>>
</span>
<?php echo $mensagem->no_mensagem->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($mensagem->ds_mensagem->Visible) { // ds_mensagem ?>
	<tr id="r_ds_mensagem">
		<td><span id="elh_mensagem_ds_mensagem"><?php echo $mensagem->ds_mensagem->FldCaption() ?></span></td>
		<td<?php echo $mensagem->ds_mensagem->CellAttributes() ?>>
<span id="el_mensagem_ds_mensagem" class="control-group">
<textarea data-field="x_ds_mensagem" name="x_ds_mensagem" id="x_ds_mensagem" cols="35" rows="4" placeholder="<?php echo $mensagem->ds_mensagem->PlaceHolder ?>"<?php echo $mensagem->ds_mensagem->EditAttributes() ?>><?php echo $mensagem->ds_mensagem->EditValue ?></textarea>
</span>
<?php echo $mensagem->ds_mensagem->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($mensagem->ds_ajuda->Visible) { // ds_ajuda ?>
	<tr id="r_ds_ajuda">
		<td><span id="elh_mensagem_ds_ajuda"><?php echo $mensagem->ds_ajuda->FldCaption() ?></span></td>
		<td<?php echo $mensagem->ds_ajuda->CellAttributes() ?>>
<span id="el_mensagem_ds_ajuda" class="control-group">
<textarea data-field="x_ds_ajuda" name="x_ds_ajuda" id="x_ds_ajuda" cols="35" rows="4" placeholder="<?php echo $mensagem->ds_ajuda->PlaceHolder ?>"<?php echo $mensagem->ds_ajuda->EditAttributes() ?>><?php echo $mensagem->ds_ajuda->EditValue ?></textarea>
</span>
<?php echo $mensagem->ds_ajuda->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($mensagem->nu_stMensagem->Visible) { // nu_stMensagem ?>
	<tr id="r_nu_stMensagem">
		<td><span id="elh_mensagem_nu_stMensagem"><?php echo $mensagem->nu_stMensagem->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $mensagem->nu_stMensagem->CellAttributes() ?>>
<span id="el_mensagem_nu_stMensagem" class="control-group">
<select data-field="x_nu_stMensagem" id="x_nu_stMensagem" name="x_nu_stMensagem"<?php echo $mensagem->nu_stMensagem->EditAttributes() ?>>
<?php
if (is_array($mensagem->nu_stMensagem->EditValue)) {
	$arwrk = $mensagem->nu_stMensagem->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($mensagem->nu_stMensagem->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fmensagemadd.Lists["x_nu_stMensagem"].Options = <?php echo (is_array($mensagem->nu_stMensagem->EditValue)) ? ew_ArrayToJson($mensagem->nu_stMensagem->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $mensagem->nu_stMensagem->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fmensagemadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$mensagem_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$mensagem_add->Page_Terminate();
?>
