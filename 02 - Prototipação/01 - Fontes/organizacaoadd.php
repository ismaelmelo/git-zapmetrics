<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "organizacaoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "areagridcls.php" ?>
<?php include_once "pargeraisgridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$organizacao_add = NULL; // Initialize page object first

class corganizacao_add extends corganizacao {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'organizacao';

	// Page object name
	var $PageObjName = 'organizacao_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
			if (@$_GET["nu_organizacao"] != "") {
				$this->nu_organizacao->setQueryStringValue($_GET["nu_organizacao"]);
				$this->setKey("nu_organizacao", $this->nu_organizacao->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_organizacao", ""); // Clear key
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

		// Set up detail parameters
		$this->SetUpDetailParms();

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
					$this->Page_Terminate("organizacaolist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					if ($this->getCurrentDetailTable() <> "") // Master/detail add
						$sReturnUrl = $this->GetDetailUrl();
					else
						$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "organizacaoview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values

					// Set up detail parameters
					$this->SetUpDetailParms();
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
			$this->no_organizacao->setFormValue($objForm->GetValue("x_no_organizacao"));
		}
		if (!$this->ds_organizacao->FldIsDetailKey) {
			$this->ds_organizacao->setFormValue($objForm->GetValue("x_ds_organizacao"));
		}
		if (!$this->nu_verticalNegocio->FldIsDetailKey) {
			$this->nu_verticalNegocio->setFormValue($objForm->GetValue("x_nu_verticalNegocio"));
		}
		if (!$this->ic_ativo->FldIsDetailKey) {
			$this->ic_ativo->setFormValue($objForm->GetValue("x_ic_ativo"));
		}
		if (!$this->ic_padrao->FldIsDetailKey) {
			$this->ic_padrao->setFormValue($objForm->GetValue("x_ic_padrao"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->no_organizacao->CurrentValue = $this->no_organizacao->FormValue;
		$this->ds_organizacao->CurrentValue = $this->ds_organizacao->FormValue;
		$this->nu_verticalNegocio->CurrentValue = $this->nu_verticalNegocio->FormValue;
		$this->ic_ativo->CurrentValue = $this->ic_ativo->FormValue;
		$this->ic_padrao->CurrentValue = $this->ic_padrao->FormValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_organizacao")) <> "")
			$this->nu_organizacao->CurrentValue = $this->getKey("nu_organizacao"); // nu_organizacao
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

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("area", $DetailTblVar) && $GLOBALS["area"]->DetailAdd) {
			if (!isset($GLOBALS["area_grid"])) $GLOBALS["area_grid"] = new carea_grid(); // get detail page object
			$GLOBALS["area_grid"]->ValidateGridForm();
		}
		if (in_array("pargerais", $DetailTblVar) && $GLOBALS["pargerais"]->DetailAdd) {
			if (!isset($GLOBALS["pargerais_grid"])) $GLOBALS["pargerais_grid"] = new cpargerais_grid(); // get detail page object
			$GLOBALS["pargerais_grid"]->ValidateGridForm();
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

		// Begin transaction
		if ($this->getCurrentDetailTable() <> "")
			$conn->BeginTrans();

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

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("area", $DetailTblVar) && $GLOBALS["area"]->DetailAdd) {
				$GLOBALS["area"]->nu_organizacao->setSessionValue($this->nu_organizacao->CurrentValue); // Set master key
				if (!isset($GLOBALS["area_grid"])) $GLOBALS["area_grid"] = new carea_grid(); // Get detail page object
				$AddRow = $GLOBALS["area_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["area"]->nu_organizacao->setSessionValue(""); // Clear master key if insert failed
			}
			if (in_array("pargerais", $DetailTblVar) && $GLOBALS["pargerais"]->DetailAdd) {
				$GLOBALS["pargerais"]->nu_orgBase->setSessionValue($this->nu_organizacao->CurrentValue); // Set master key
				if (!isset($GLOBALS["pargerais_grid"])) $GLOBALS["pargerais_grid"] = new cpargerais_grid(); // Get detail page object
				$AddRow = $GLOBALS["pargerais_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["pargerais"]->nu_orgBase->setSessionValue(""); // Clear master key if insert failed
			}
		}

		// Commit/Rollback transaction
		if ($this->getCurrentDetailTable() <> "") {
			if ($AddRow) {
				$conn->CommitTrans(); // Commit transaction
			} else {
				$conn->RollbackTrans(); // Rollback transaction
			}
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
			$this->WriteAuditTrailOnAdd($rsnew);
		}
		return $AddRow;
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
			if (in_array("area", $DetailTblVar)) {
				if (!isset($GLOBALS["area_grid"]))
					$GLOBALS["area_grid"] = new carea_grid;
				if ($GLOBALS["area_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["area_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["area_grid"]->CurrentMode = "add";
					$GLOBALS["area_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["area_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["area_grid"]->setStartRecordNumber(1);
					$GLOBALS["area_grid"]->nu_organizacao->FldIsDetailKey = TRUE;
					$GLOBALS["area_grid"]->nu_organizacao->CurrentValue = $this->nu_organizacao->CurrentValue;
					$GLOBALS["area_grid"]->nu_organizacao->setSessionValue($GLOBALS["area_grid"]->nu_organizacao->CurrentValue);
				}
			}
			if (in_array("pargerais", $DetailTblVar)) {
				if (!isset($GLOBALS["pargerais_grid"]))
					$GLOBALS["pargerais_grid"] = new cpargerais_grid;
				if ($GLOBALS["pargerais_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["pargerais_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["pargerais_grid"]->CurrentMode = "add";
					$GLOBALS["pargerais_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["pargerais_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["pargerais_grid"]->setStartRecordNumber(1);
					$GLOBALS["pargerais_grid"]->nu_orgBase->FldIsDetailKey = TRUE;
					$GLOBALS["pargerais_grid"]->nu_orgBase->CurrentValue = $this->nu_organizacao->CurrentValue;
					$GLOBALS["pargerais_grid"]->nu_orgBase->setSessionValue($GLOBALS["pargerais_grid"]->nu_orgBase->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "organizacaolist.php", $this->TableVar);
		$PageCaption = ($this->CurrentAction == "C") ? $Language->Phrase("Copy") : $Language->Phrase("Add");
		$Breadcrumb->Add("add", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
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
if (!isset($organizacao_add)) $organizacao_add = new corganizacao_add();

// Page init
$organizacao_add->Page_Init();

// Page main
$organizacao_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$organizacao_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var organizacao_add = new ew_Page("organizacao_add");
organizacao_add.PageID = "add"; // Page ID
var EW_PAGE_ID = organizacao_add.PageID; // For backward compatibility

// Form object
var forganizacaoadd = new ew_Form("forganizacaoadd");

// Validate form
forganizacaoadd.Validate = function() {
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
forganizacaoadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
forganizacaoadd.ValidateRequired = true;
<?php } else { ?>
forganizacaoadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
forganizacaoadd.Lists["x_nu_verticalNegocio"] = {"LinkField":"x_nu_verticalNegocio","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_vertical","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $organizacao_add->ShowPageHeader(); ?>
<?php
$organizacao_add->ShowMessage();
?>
<form name="forganizacaoadd" id="forganizacaoadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="organizacao">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_organizacaoadd" class="table table-bordered table-striped">
<?php if ($organizacao->no_organizacao->Visible) { // no_organizacao ?>
	<tr id="r_no_organizacao">
		<td><span id="elh_organizacao_no_organizacao"><?php echo $organizacao->no_organizacao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $organizacao->no_organizacao->CellAttributes() ?>>
<span id="el_organizacao_no_organizacao" class="control-group">
<input type="text" data-field="x_no_organizacao" name="x_no_organizacao" id="x_no_organizacao" size="30" maxlength="100" placeholder="<?php echo $organizacao->no_organizacao->PlaceHolder ?>" value="<?php echo $organizacao->no_organizacao->EditValue ?>"<?php echo $organizacao->no_organizacao->EditAttributes() ?>>
</span>
<?php echo $organizacao->no_organizacao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($organizacao->ds_organizacao->Visible) { // ds_organizacao ?>
	<tr id="r_ds_organizacao">
		<td><span id="elh_organizacao_ds_organizacao"><?php echo $organizacao->ds_organizacao->FldCaption() ?></span></td>
		<td<?php echo $organizacao->ds_organizacao->CellAttributes() ?>>
<span id="el_organizacao_ds_organizacao" class="control-group">
<textarea data-field="x_ds_organizacao" name="x_ds_organizacao" id="x_ds_organizacao" cols="35" rows="4" placeholder="<?php echo $organizacao->ds_organizacao->PlaceHolder ?>"<?php echo $organizacao->ds_organizacao->EditAttributes() ?>><?php echo $organizacao->ds_organizacao->EditValue ?></textarea>
</span>
<?php echo $organizacao->ds_organizacao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($organizacao->nu_verticalNegocio->Visible) { // nu_verticalNegocio ?>
	<tr id="r_nu_verticalNegocio">
		<td><span id="elh_organizacao_nu_verticalNegocio"><?php echo $organizacao->nu_verticalNegocio->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $organizacao->nu_verticalNegocio->CellAttributes() ?>>
<span id="el_organizacao_nu_verticalNegocio" class="control-group">
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
<?php if (AllowAdd(CurrentProjectID() . "verticalnegocio")) { ?>
&nbsp;<a id="aol_x_nu_verticalNegocio" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_nu_verticalNegocio',url:'verticalnegocioaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $organizacao->nu_verticalNegocio->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
forganizacaoadd.Lists["x_nu_verticalNegocio"].Options = <?php echo (is_array($organizacao->nu_verticalNegocio->EditValue)) ? ew_ArrayToJson($organizacao->nu_verticalNegocio->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $organizacao->nu_verticalNegocio->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($organizacao->ic_ativo->Visible) { // ic_ativo ?>
	<tr id="r_ic_ativo">
		<td><span id="elh_organizacao_ic_ativo"><?php echo $organizacao->ic_ativo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $organizacao->ic_ativo->CellAttributes() ?>>
<span id="el_organizacao_ic_ativo" class="control-group">
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
</span>
<?php echo $organizacao->ic_ativo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($organizacao->ic_padrao->Visible) { // ic_padrao ?>
	<tr id="r_ic_padrao">
		<td><span id="elh_organizacao_ic_padrao"><?php echo $organizacao->ic_padrao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $organizacao->ic_padrao->CellAttributes() ?>>
<span id="el_organizacao_ic_padrao" class="control-group">
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
</span>
<?php echo $organizacao->ic_padrao->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<?php
	if (in_array("area", explode(",", $organizacao->getCurrentDetailTable())) && $area->DetailAdd) {
?>
<?php include_once "areagrid.php" ?>
<?php } ?>
<?php
	if (in_array("pargerais", explode(",", $organizacao->getCurrentDetailTable())) && $pargerais->DetailAdd) {
?>
<?php include_once "pargeraisgrid.php" ?>
<?php } ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
forganizacaoadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$organizacao_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$organizacao_add->Page_Terminate();
?>
