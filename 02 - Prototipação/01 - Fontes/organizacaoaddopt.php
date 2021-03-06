<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "organizacaoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$organizacao_addopt = NULL; // Initialize page object first

class corganizacao_addopt extends corganizacao {

	// Page ID
	var $PageID = 'addopt';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'organizacao';

	// Page object name
	var $PageObjName = 'organizacao_addopt';

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

		// Table object (organizacao)
		if (!isset($GLOBALS["organizacao"])) {
			$GLOBALS["organizacao"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["organizacao"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'addopt', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'organizacao', TRUE);

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
			$this->Page_Terminate("organizacaolist.php");
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

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		set_error_handler("ew_ErrorHandler");

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if ($objForm->GetValue("a_addopt") <> "") {
			$this->CurrentAction = $objForm->GetValue("a_addopt"); // Get form action
			$this->LoadFormValues(); // Load form values

			// Validate form
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->setFailureMessage($gsFormError);
			}
		} else { // Not post back
			$this->CurrentAction = "I"; // Display blank record
			$this->LoadDefaultValues(); // Load default values
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow()) { // Add successful
					$row = array();
					$row["x_nu_organizacao"] = $this->nu_organizacao->DbValue;
					$row["x_no_organizacao"] = $this->no_organizacao->DbValue;
					$row["x_ds_organizacao"] = $this->ds_organizacao->DbValue;
					$row["x_nu_verticalNegocio"] = $this->nu_verticalNegocio->DbValue;
					$row["x_ic_ativo"] = $this->ic_ativo->DbValue;
					$row["x_ic_padrao"] = $this->ic_padrao->DbValue;
					if (!EW_DEBUG_ENABLED && ob_get_length())
						ob_end_clean();
					echo ew_ArrayToJson(array($row));
				} else {
					$this->ShowMessage();
				}
				$this->Page_Terminate();
				exit();
		}

		// Render row
		$this->RowType = EW_ROWTYPE_ADD; // Render add type
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
		$this->no_organizacao->CurrentValue = NULL;
		$this->no_organizacao->OldValue = $this->no_organizacao->CurrentValue;
		$this->ds_organizacao->CurrentValue = NULL;
		$this->ds_organizacao->OldValue = $this->ds_organizacao->CurrentValue;
		$this->nu_verticalNegocio->CurrentValue = NULL;
		$this->nu_verticalNegocio->OldValue = $this->nu_verticalNegocio->CurrentValue;
		$this->ic_ativo->CurrentValue = "S";
		$this->ic_padrao->CurrentValue = NULL;
		$this->ic_padrao->OldValue = $this->ic_padrao->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->no_organizacao->FldIsDetailKey) {
			$this->no_organizacao->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_no_organizacao")));
		}
		if (!$this->ds_organizacao->FldIsDetailKey) {
			$this->ds_organizacao->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_ds_organizacao")));
		}
		if (!$this->nu_verticalNegocio->FldIsDetailKey) {
			$this->nu_verticalNegocio->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_nu_verticalNegocio")));
		}
		if (!$this->ic_ativo->FldIsDetailKey) {
			$this->ic_ativo->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_ic_ativo")));
		}
		if (!$this->ic_padrao->FldIsDetailKey) {
			$this->ic_padrao->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_ic_padrao")));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->no_organizacao->CurrentValue = ew_ConvertToUtf8($this->no_organizacao->FormValue);
		$this->ds_organizacao->CurrentValue = ew_ConvertToUtf8($this->ds_organizacao->FormValue);
		$this->nu_verticalNegocio->CurrentValue = ew_ConvertToUtf8($this->nu_verticalNegocio->FormValue);
		$this->ic_ativo->CurrentValue = ew_ConvertToUtf8($this->ic_ativo->FormValue);
		$this->ic_padrao->CurrentValue = ew_ConvertToUtf8($this->ic_padrao->FormValue);
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
		$this->nu_organizacao->setDbValue($rs->fields('nu_organizacao'));
		$this->no_organizacao->setDbValue($rs->fields('no_organizacao'));
		$this->ds_organizacao->setDbValue($rs->fields('ds_organizacao'));
		$this->nu_verticalNegocio->setDbValue($rs->fields('nu_verticalNegocio'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
		$this->ic_padrao->setDbValue($rs->fields('ic_padrao'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_organizacao->DbValue = $row['nu_organizacao'];
		$this->no_organizacao->DbValue = $row['no_organizacao'];
		$this->ds_organizacao->DbValue = $row['ds_organizacao'];
		$this->nu_verticalNegocio->DbValue = $row['nu_verticalNegocio'];
		$this->ic_ativo->DbValue = $row['ic_ativo'];
		$this->ic_padrao->DbValue = $row['ic_padrao'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_organizacao
		// no_organizacao
		// ds_organizacao
		// nu_verticalNegocio
		// ic_ativo
		// ic_padrao

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// no_organizacao
			$this->no_organizacao->ViewValue = $this->no_organizacao->CurrentValue;
			$this->no_organizacao->ViewCustomAttributes = "";

			// ds_organizacao
			$this->ds_organizacao->ViewValue = $this->ds_organizacao->CurrentValue;
			$this->ds_organizacao->ViewCustomAttributes = "";

			// nu_verticalNegocio
			if (strval($this->nu_verticalNegocio->CurrentValue) <> "") {
				$sFilterWrk = "[nu_verticalNegocio]" . ew_SearchString("=", $this->nu_verticalNegocio->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_verticalNegocio], [no_vertical] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[verticalnegocio]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_verticalNegocio, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_vertical] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_verticalNegocio->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_verticalNegocio->ViewValue = $this->nu_verticalNegocio->CurrentValue;
				}
			} else {
				$this->nu_verticalNegocio->ViewValue = NULL;
			}
			$this->nu_verticalNegocio->ViewCustomAttributes = "";

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

			// ic_padrao
			if (strval($this->ic_padrao->CurrentValue) <> "") {
				switch ($this->ic_padrao->CurrentValue) {
					case $this->ic_padrao->FldTagValue(1):
						$this->ic_padrao->ViewValue = $this->ic_padrao->FldTagCaption(1) <> "" ? $this->ic_padrao->FldTagCaption(1) : $this->ic_padrao->CurrentValue;
						break;
					case $this->ic_padrao->FldTagValue(2):
						$this->ic_padrao->ViewValue = $this->ic_padrao->FldTagCaption(2) <> "" ? $this->ic_padrao->FldTagCaption(2) : $this->ic_padrao->CurrentValue;
						break;
					case $this->ic_padrao->FldTagValue(3):
						$this->ic_padrao->ViewValue = $this->ic_padrao->FldTagCaption(3) <> "" ? $this->ic_padrao->FldTagCaption(3) : $this->ic_padrao->CurrentValue;
						break;
					default:
						$this->ic_padrao->ViewValue = $this->ic_padrao->CurrentValue;
				}
			} else {
				$this->ic_padrao->ViewValue = NULL;
			}
			$this->ic_padrao->ViewCustomAttributes = "";

			// no_organizacao
			$this->no_organizacao->LinkCustomAttributes = "";
			$this->no_organizacao->HrefValue = "";
			$this->no_organizacao->TooltipValue = "";

			// ds_organizacao
			$this->ds_organizacao->LinkCustomAttributes = "";
			$this->ds_organizacao->HrefValue = "";
			$this->ds_organizacao->TooltipValue = "";

			// nu_verticalNegocio
			$this->nu_verticalNegocio->LinkCustomAttributes = "";
			$this->nu_verticalNegocio->HrefValue = "";
			$this->nu_verticalNegocio->TooltipValue = "";

			// ic_ativo
			$this->ic_ativo->LinkCustomAttributes = "";
			$this->ic_ativo->HrefValue = "";
			$this->ic_ativo->TooltipValue = "";

			// ic_padrao
			$this->ic_padrao->LinkCustomAttributes = "";
			$this->ic_padrao->HrefValue = "";
			$this->ic_padrao->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// no_organizacao
			$this->no_organizacao->EditCustomAttributes = "";
			$this->no_organizacao->EditValue = ew_HtmlEncode($this->no_organizacao->CurrentValue);
			$this->no_organizacao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_organizacao->FldCaption()));

			// ds_organizacao
			$this->ds_organizacao->EditCustomAttributes = "";
			$this->ds_organizacao->EditValue = $this->ds_organizacao->CurrentValue;
			$this->ds_organizacao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_organizacao->FldCaption()));

			// nu_verticalNegocio
			$this->nu_verticalNegocio->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_verticalNegocio], [no_vertical] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[verticalnegocio]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_verticalNegocio, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_vertical] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_verticalNegocio->EditValue = $arwrk;

			// ic_ativo
			$this->ic_ativo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_ativo->FldTagValue(1), $this->ic_ativo->FldTagCaption(1) <> "" ? $this->ic_ativo->FldTagCaption(1) : $this->ic_ativo->FldTagValue(1));
			$arwrk[] = array($this->ic_ativo->FldTagValue(2), $this->ic_ativo->FldTagCaption(2) <> "" ? $this->ic_ativo->FldTagCaption(2) : $this->ic_ativo->FldTagValue(2));
			$this->ic_ativo->EditValue = $arwrk;

			// ic_padrao
			$this->ic_padrao->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_padrao->FldTagValue(1), $this->ic_padrao->FldTagCaption(1) <> "" ? $this->ic_padrao->FldTagCaption(1) : $this->ic_padrao->FldTagValue(1));
			$arwrk[] = array($this->ic_padrao->FldTagValue(2), $this->ic_padrao->FldTagCaption(2) <> "" ? $this->ic_padrao->FldTagCaption(2) : $this->ic_padrao->FldTagValue(2));
			$arwrk[] = array($this->ic_padrao->FldTagValue(3), $this->ic_padrao->FldTagCaption(3) <> "" ? $this->ic_padrao->FldTagCaption(3) : $this->ic_padrao->FldTagValue(3));
			$this->ic_padrao->EditValue = $arwrk;

			// Edit refer script
			// no_organizacao

			$this->no_organizacao->HrefValue = "";

			// ds_organizacao
			$this->ds_organizacao->HrefValue = "";

			// nu_verticalNegocio
			$this->nu_verticalNegocio->HrefValue = "";

			// ic_ativo
			$this->ic_ativo->HrefValue = "";

			// ic_padrao
			$this->ic_padrao->HrefValue = "";
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
		if (!$this->no_organizacao->FldIsDetailKey && !is_null($this->no_organizacao->FormValue) && $this->no_organizacao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_organizacao->FldCaption());
		}
		if (!$this->nu_verticalNegocio->FldIsDetailKey && !is_null($this->nu_verticalNegocio->FormValue) && $this->nu_verticalNegocio->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_verticalNegocio->FldCaption());
		}
		if ($this->ic_ativo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_ativo->FldCaption());
		}
		if ($this->ic_padrao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_padrao->FldCaption());
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
		if ($this->ic_padrao->CurrentValue <> "") { // Check field with unique index
			$sFilter = "(ic_padrao = '" . ew_AdjustSql($this->ic_padrao->CurrentValue) . "')";
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->ic_padrao->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->ic_padrao->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
		}

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// no_organizacao
		$this->no_organizacao->SetDbValueDef($rsnew, $this->no_organizacao->CurrentValue, "", FALSE);

		// ds_organizacao
		$this->ds_organizacao->SetDbValueDef($rsnew, $this->ds_organizacao->CurrentValue, NULL, FALSE);

		// nu_verticalNegocio
		$this->nu_verticalNegocio->SetDbValueDef($rsnew, $this->nu_verticalNegocio->CurrentValue, NULL, FALSE);

		// ic_ativo
		$this->ic_ativo->SetDbValueDef($rsnew, $this->ic_ativo->CurrentValue, NULL, FALSE);

		// ic_padrao
		$this->ic_padrao->SetDbValueDef($rsnew, $this->ic_padrao->CurrentValue, NULL, FALSE);

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
			$this->nu_organizacao->setDbValue($conn->Insert_ID());
			$rsnew['nu_organizacao'] = $this->nu_organizacao->DbValue;
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "organizacaolist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("addopt");
		$Breadcrumb->Add("addopt", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'organizacao';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'organizacao';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_organizacao'];

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

	// Custom validate event
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
if (!isset($organizacao_addopt)) $organizacao_addopt = new corganizacao_addopt();

// Page init
$organizacao_addopt->Page_Init();

// Page main
$organizacao_addopt->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$organizacao_addopt->Page_Render();
?>
<script type="text/javascript">

// Page object
var organizacao_addopt = new ew_Page("organizacao_addopt");
organizacao_addopt.PageID = "addopt"; // Page ID
var EW_PAGE_ID = organizacao_addopt.PageID; // For backward compatibility

// Form object
var forganizacaoaddopt = new ew_Form("forganizacaoaddopt");

// Validate form
forganizacaoaddopt.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_no_organizacao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($organizacao->no_organizacao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_verticalNegocio");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($organizacao->nu_verticalNegocio->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_ativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($organizacao->ic_ativo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_padrao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($organizacao->ic_padrao->FldCaption()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
forganizacaoaddopt.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
forganizacaoaddopt.ValidateRequired = true;
<?php } else { ?>
forganizacaoaddopt.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
forganizacaoaddopt.Lists["x_nu_verticalNegocio"] = {"LinkField":"x_nu_verticalNegocio","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_vertical","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
$organizacao_addopt->ShowMessage();
?>
<form name="forganizacaoaddopt" id="forganizacaoaddopt" class="ewForm form-horizontal" action="organizacaoaddopt.php" method="post">
<input type="hidden" name="t" value="organizacao">
<input type="hidden" name="a_addopt" id="a_addopt" value="A">
<div id="tbl_organizacaoaddopt">
	<div class="control-group">
		<label class="control-label" for="x_no_organizacao"><?php echo $organizacao->no_organizacao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="controls">
<input type="text" data-field="x_no_organizacao" name="x_no_organizacao" id="x_no_organizacao" size="30" maxlength="100" placeholder="<?php echo $organizacao->no_organizacao->PlaceHolder ?>" value="<?php echo $organizacao->no_organizacao->EditValue ?>"<?php echo $organizacao->no_organizacao->EditAttributes() ?>>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_ds_organizacao"><?php echo $organizacao->ds_organizacao->FldCaption() ?></label>
		<div class="controls">
<textarea data-field="x_ds_organizacao" name="x_ds_organizacao" id="x_ds_organizacao" cols="35" rows="4" placeholder="<?php echo $organizacao->ds_organizacao->PlaceHolder ?>"<?php echo $organizacao->ds_organizacao->EditAttributes() ?>><?php echo $organizacao->ds_organizacao->EditValue ?></textarea>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_nu_verticalNegocio"><?php echo $organizacao->nu_verticalNegocio->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="controls">
<select data-field="x_nu_verticalNegocio" id="x_nu_verticalNegocio" name="x_nu_verticalNegocio"<?php echo $organizacao->nu_verticalNegocio->EditAttributes() ?>>
<?php
if (is_array($organizacao->nu_verticalNegocio->EditValue)) {
	$arwrk = $organizacao->nu_verticalNegocio->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($organizacao->nu_verticalNegocio->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
forganizacaoaddopt.Lists["x_nu_verticalNegocio"].Options = <?php echo (is_array($organizacao->nu_verticalNegocio->EditValue)) ? ew_ArrayToJson($organizacao->nu_verticalNegocio->EditValue, 1) : "[]" ?>;
</script>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_ic_ativo"><?php echo $organizacao->ic_ativo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="controls">
<div id="tp_x_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_ativo" id="x_ic_ativo" value="{value}"<?php echo $organizacao->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $organizacao->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($organizacao->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x_ic_ativo" id="x_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $organizacao->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_ic_padrao"><?php echo $organizacao->ic_padrao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="controls">
<div id="tp_x_ic_padrao" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_padrao" id="x_ic_padrao" value="{value}"<?php echo $organizacao->ic_padrao->EditAttributes() ?>></div>
<div id="dsl_x_ic_padrao" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $organizacao->ic_padrao->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($organizacao->ic_padrao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_padrao" name="x_ic_padrao" id="x_ic_padrao_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $organizacao->ic_padrao->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</div>
	</div>
</div>
</form>
<script type="text/javascript">
forganizacaoaddopt.Init();
</script>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php
$organizacao_addopt->Page_Terminate();
?>
