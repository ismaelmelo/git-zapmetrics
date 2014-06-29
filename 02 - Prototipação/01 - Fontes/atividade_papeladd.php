<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "atividade_papelinfo.php" ?>
<?php include_once "atividadeinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$atividade_papel_add = NULL; // Initialize page object first

class catividade_papel_add extends catividade_papel {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'atividade_papel';

	// Page object name
	var $PageObjName = 'atividade_papel_add';

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

		// Table object (atividade_papel)
		if (!isset($GLOBALS["atividade_papel"])) {
			$GLOBALS["atividade_papel"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["atividade_papel"];
		}

		// Table object (atividade)
		if (!isset($GLOBALS['atividade'])) $GLOBALS['atividade'] = new catividade();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'atividade_papel', TRUE);

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
			$this->Page_Terminate("atividade_papellist.php");
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

		// Set up master/detail parameters
		$this->SetUpMasterParms();

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["nu_atividade"] != "") {
				$this->nu_atividade->setQueryStringValue($_GET["nu_atividade"]);
				$this->setKey("nu_atividade", $this->nu_atividade->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_atividade", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if (@$_GET["co_papel"] != "") {
				$this->co_papel->setQueryStringValue($_GET["co_papel"]);
				$this->setKey("co_papel", $this->co_papel->CurrentValue); // Set up key
			} else {
				$this->setKey("co_papel", ""); // Clear key
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
					$this->Page_Terminate("atividade_papellist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "atividade_papelview.php")
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
		$this->nu_atividade->CurrentValue = NULL;
		$this->nu_atividade->OldValue = $this->nu_atividade->CurrentValue;
		$this->co_papel->CurrentValue = NULL;
		$this->co_papel->OldValue = $this->co_papel->CurrentValue;
		$this->ic_responsabilidade->CurrentValue = NULL;
		$this->ic_responsabilidade->OldValue = $this->ic_responsabilidade->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_atividade->FldIsDetailKey) {
			$this->nu_atividade->setFormValue($objForm->GetValue("x_nu_atividade"));
		}
		if (!$this->co_papel->FldIsDetailKey) {
			$this->co_papel->setFormValue($objForm->GetValue("x_co_papel"));
		}
		if (!$this->ic_responsabilidade->FldIsDetailKey) {
			$this->ic_responsabilidade->setFormValue($objForm->GetValue("x_ic_responsabilidade"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->nu_atividade->CurrentValue = $this->nu_atividade->FormValue;
		$this->co_papel->CurrentValue = $this->co_papel->FormValue;
		$this->ic_responsabilidade->CurrentValue = $this->ic_responsabilidade->FormValue;
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
		$this->nu_atividade->setDbValue($rs->fields('nu_atividade'));
		$this->co_papel->setDbValue($rs->fields('co_papel'));
		$this->ic_responsabilidade->setDbValue($rs->fields('ic_responsabilidade'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_atividade->DbValue = $row['nu_atividade'];
		$this->co_papel->DbValue = $row['co_papel'];
		$this->ic_responsabilidade->DbValue = $row['ic_responsabilidade'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_atividade")) <> "")
			$this->nu_atividade->CurrentValue = $this->getKey("nu_atividade"); // nu_atividade
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("co_papel")) <> "")
			$this->co_papel->CurrentValue = $this->getKey("co_papel"); // co_papel
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
		// nu_atividade
		// co_papel
		// ic_responsabilidade

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_atividade
			if (strval($this->nu_atividade->CurrentValue) <> "") {
				$sFilterWrk = "[nu_atividade]" . ew_SearchString("=", $this->nu_atividade->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_atividade], [no_atividade] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[atividade]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_atividade, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_atividade] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_atividade->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_atividade->ViewValue = $this->nu_atividade->CurrentValue;
				}
			} else {
				$this->nu_atividade->ViewValue = NULL;
			}
			$this->nu_atividade->ViewCustomAttributes = "";

			// co_papel
			if (strval($this->co_papel->CurrentValue) <> "") {
				$sFilterWrk = "[co_papel]" . ew_SearchString("=", $this->co_papel->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [co_papel], [no_papel] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[papel]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_papel, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_papel] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_papel->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->co_papel->ViewValue = $this->co_papel->CurrentValue;
				}
			} else {
				$this->co_papel->ViewValue = NULL;
			}
			$this->co_papel->ViewCustomAttributes = "";

			// ic_responsabilidade
			if (strval($this->ic_responsabilidade->CurrentValue) <> "") {
				switch ($this->ic_responsabilidade->CurrentValue) {
					case $this->ic_responsabilidade->FldTagValue(1):
						$this->ic_responsabilidade->ViewValue = $this->ic_responsabilidade->FldTagCaption(1) <> "" ? $this->ic_responsabilidade->FldTagCaption(1) : $this->ic_responsabilidade->CurrentValue;
						break;
					case $this->ic_responsabilidade->FldTagValue(2):
						$this->ic_responsabilidade->ViewValue = $this->ic_responsabilidade->FldTagCaption(2) <> "" ? $this->ic_responsabilidade->FldTagCaption(2) : $this->ic_responsabilidade->CurrentValue;
						break;
					case $this->ic_responsabilidade->FldTagValue(3):
						$this->ic_responsabilidade->ViewValue = $this->ic_responsabilidade->FldTagCaption(3) <> "" ? $this->ic_responsabilidade->FldTagCaption(3) : $this->ic_responsabilidade->CurrentValue;
						break;
					default:
						$this->ic_responsabilidade->ViewValue = $this->ic_responsabilidade->CurrentValue;
				}
			} else {
				$this->ic_responsabilidade->ViewValue = NULL;
			}
			$this->ic_responsabilidade->ViewCustomAttributes = "";

			// nu_atividade
			$this->nu_atividade->LinkCustomAttributes = "";
			$this->nu_atividade->HrefValue = "";
			$this->nu_atividade->TooltipValue = "";

			// co_papel
			$this->co_papel->LinkCustomAttributes = "";
			$this->co_papel->HrefValue = "";
			$this->co_papel->TooltipValue = "";

			// ic_responsabilidade
			$this->ic_responsabilidade->LinkCustomAttributes = "";
			$this->ic_responsabilidade->HrefValue = "";
			$this->ic_responsabilidade->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nu_atividade
			$this->nu_atividade->EditCustomAttributes = "";
			if ($this->nu_atividade->getSessionValue() <> "") {
				$this->nu_atividade->CurrentValue = $this->nu_atividade->getSessionValue();
			if (strval($this->nu_atividade->CurrentValue) <> "") {
				$sFilterWrk = "[nu_atividade]" . ew_SearchString("=", $this->nu_atividade->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_atividade], [no_atividade] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[atividade]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_atividade, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_atividade] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_atividade->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_atividade->ViewValue = $this->nu_atividade->CurrentValue;
				}
			} else {
				$this->nu_atividade->ViewValue = NULL;
			}
			$this->nu_atividade->ViewCustomAttributes = "";
			} else {
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_atividade], [no_atividade] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[atividade]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_atividade, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_atividade] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_atividade->EditValue = $arwrk;
			}

			// co_papel
			$this->co_papel->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [co_papel], [no_papel] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[papel]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_papel, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_papel] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->co_papel->EditValue = $arwrk;

			// ic_responsabilidade
			$this->ic_responsabilidade->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_responsabilidade->FldTagValue(1), $this->ic_responsabilidade->FldTagCaption(1) <> "" ? $this->ic_responsabilidade->FldTagCaption(1) : $this->ic_responsabilidade->FldTagValue(1));
			$arwrk[] = array($this->ic_responsabilidade->FldTagValue(2), $this->ic_responsabilidade->FldTagCaption(2) <> "" ? $this->ic_responsabilidade->FldTagCaption(2) : $this->ic_responsabilidade->FldTagValue(2));
			$arwrk[] = array($this->ic_responsabilidade->FldTagValue(3), $this->ic_responsabilidade->FldTagCaption(3) <> "" ? $this->ic_responsabilidade->FldTagCaption(3) : $this->ic_responsabilidade->FldTagValue(3));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_responsabilidade->EditValue = $arwrk;

			// Edit refer script
			// nu_atividade

			$this->nu_atividade->HrefValue = "";

			// co_papel
			$this->co_papel->HrefValue = "";

			// ic_responsabilidade
			$this->ic_responsabilidade->HrefValue = "";
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
		if (!$this->nu_atividade->FldIsDetailKey && !is_null($this->nu_atividade->FormValue) && $this->nu_atividade->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_atividade->FldCaption());
		}
		if (!$this->co_papel->FldIsDetailKey && !is_null($this->co_papel->FormValue) && $this->co_papel->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->co_papel->FldCaption());
		}
		if (!$this->ic_responsabilidade->FldIsDetailKey && !is_null($this->ic_responsabilidade->FormValue) && $this->ic_responsabilidade->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_responsabilidade->FldCaption());
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

		// Check referential integrity for master table 'atividade'
		$bValidMasterRecord = TRUE;
		$sMasterFilter = $this->SqlMasterFilter_atividade();
		if (strval($this->nu_atividade->CurrentValue) <> "") {
			$sMasterFilter = str_replace("@nu_atividade@", ew_AdjustSql($this->nu_atividade->CurrentValue), $sMasterFilter);
		} else {
			$bValidMasterRecord = FALSE;
		}
		if ($bValidMasterRecord) {
			$rsmaster = $GLOBALS["atividade"]->LoadRs($sMasterFilter);
			$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
			$rsmaster->Close();
		}
		if (!$bValidMasterRecord) {
			$sRelatedRecordMsg = str_replace("%t", "atividade", $Language->Phrase("RelatedRecordRequired"));
			$this->setFailureMessage($sRelatedRecordMsg);
			return FALSE;
		}

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// nu_atividade
		$this->nu_atividade->SetDbValueDef($rsnew, $this->nu_atividade->CurrentValue, 0, FALSE);

		// co_papel
		$this->co_papel->SetDbValueDef($rsnew, $this->co_papel->CurrentValue, 0, FALSE);

		// ic_responsabilidade
		$this->ic_responsabilidade->SetDbValueDef($rsnew, $this->ic_responsabilidade->CurrentValue, "", FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && $this->nu_atividade->CurrentValue == "" && $this->nu_atividade->getSessionValue() == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && $this->co_papel->CurrentValue == "" && $this->co_papel->getSessionValue() == "") {
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
			$this->WriteAuditTrailOnAdd($rsnew);
		}
		return $AddRow;
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
			if ($sMasterTblVar == "atividade") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_atividade"] <> "") {
					$GLOBALS["atividade"]->nu_atividade->setQueryStringValue($_GET["nu_atividade"]);
					$this->nu_atividade->setQueryStringValue($GLOBALS["atividade"]->nu_atividade->QueryStringValue);
					$this->nu_atividade->setSessionValue($this->nu_atividade->QueryStringValue);
					if (!is_numeric($GLOBALS["atividade"]->nu_atividade->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "atividade") {
				if ($this->nu_atividade->QueryStringValue == "") $this->nu_atividade->setSessionValue("");
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "atividade_papellist.php", $this->TableVar);
		$PageCaption = ($this->CurrentAction == "C") ? $Language->Phrase("Copy") : $Language->Phrase("Add");
		$Breadcrumb->Add("add", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'atividade_papel';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'atividade_papel';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_atividade'];
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['co_papel'];

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
if (!isset($atividade_papel_add)) $atividade_papel_add = new catividade_papel_add();

// Page init
$atividade_papel_add->Page_Init();

// Page main
$atividade_papel_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$atividade_papel_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var atividade_papel_add = new ew_Page("atividade_papel_add");
atividade_papel_add.PageID = "add"; // Page ID
var EW_PAGE_ID = atividade_papel_add.PageID; // For backward compatibility

// Form object
var fatividade_papeladd = new ew_Form("fatividade_papeladd");

// Validate form
fatividade_papeladd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_atividade");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($atividade_papel->nu_atividade->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_co_papel");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($atividade_papel->co_papel->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_responsabilidade");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($atividade_papel->ic_responsabilidade->FldCaption()) ?>");

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
fatividade_papeladd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fatividade_papeladd.ValidateRequired = true;
<?php } else { ?>
fatividade_papeladd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fatividade_papeladd.Lists["x_nu_atividade"] = {"LinkField":"x_nu_atividade","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_atividade","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fatividade_papeladd.Lists["x_co_papel"] = {"LinkField":"x_co_papel","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_papel","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $atividade_papel_add->ShowPageHeader(); ?>
<?php
$atividade_papel_add->ShowMessage();
?>
<form name="fatividade_papeladd" id="fatividade_papeladd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="atividade_papel">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_atividade_papeladd" class="table table-bordered table-striped">
<?php if ($atividade_papel->nu_atividade->Visible) { // nu_atividade ?>
	<tr id="r_nu_atividade">
		<td><span id="elh_atividade_papel_nu_atividade"><?php echo $atividade_papel->nu_atividade->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $atividade_papel->nu_atividade->CellAttributes() ?>>
<?php if ($atividade_papel->nu_atividade->getSessionValue() <> "") { ?>
<span<?php echo $atividade_papel->nu_atividade->ViewAttributes() ?>>
<?php echo $atividade_papel->nu_atividade->ViewValue ?></span>
<input type="hidden" id="x_nu_atividade" name="x_nu_atividade" value="<?php echo ew_HtmlEncode($atividade_papel->nu_atividade->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_nu_atividade" id="x_nu_atividade" name="x_nu_atividade"<?php echo $atividade_papel->nu_atividade->EditAttributes() ?>>
<?php
if (is_array($atividade_papel->nu_atividade->EditValue)) {
	$arwrk = $atividade_papel->nu_atividade->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($atividade_papel->nu_atividade->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fatividade_papeladd.Lists["x_nu_atividade"].Options = <?php echo (is_array($atividade_papel->nu_atividade->EditValue)) ? ew_ArrayToJson($atividade_papel->nu_atividade->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<?php echo $atividade_papel->nu_atividade->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($atividade_papel->co_papel->Visible) { // co_papel ?>
	<tr id="r_co_papel">
		<td><span id="elh_atividade_papel_co_papel"><?php echo $atividade_papel->co_papel->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $atividade_papel->co_papel->CellAttributes() ?>>
<span id="el_atividade_papel_co_papel" class="control-group">
<select data-field="x_co_papel" id="x_co_papel" name="x_co_papel"<?php echo $atividade_papel->co_papel->EditAttributes() ?>>
<?php
if (is_array($atividade_papel->co_papel->EditValue)) {
	$arwrk = $atividade_papel->co_papel->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($atividade_papel->co_papel->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fatividade_papeladd.Lists["x_co_papel"].Options = <?php echo (is_array($atividade_papel->co_papel->EditValue)) ? ew_ArrayToJson($atividade_papel->co_papel->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $atividade_papel->co_papel->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($atividade_papel->ic_responsabilidade->Visible) { // ic_responsabilidade ?>
	<tr id="r_ic_responsabilidade">
		<td><span id="elh_atividade_papel_ic_responsabilidade"><?php echo $atividade_papel->ic_responsabilidade->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $atividade_papel->ic_responsabilidade->CellAttributes() ?>>
<span id="el_atividade_papel_ic_responsabilidade" class="control-group">
<select data-field="x_ic_responsabilidade" id="x_ic_responsabilidade" name="x_ic_responsabilidade"<?php echo $atividade_papel->ic_responsabilidade->EditAttributes() ?>>
<?php
if (is_array($atividade_papel->ic_responsabilidade->EditValue)) {
	$arwrk = $atividade_papel->ic_responsabilidade->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($atividade_papel->ic_responsabilidade->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $atividade_papel->ic_responsabilidade->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fatividade_papeladd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$atividade_papel_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$atividade_papel_add->Page_Terminate();
?>
