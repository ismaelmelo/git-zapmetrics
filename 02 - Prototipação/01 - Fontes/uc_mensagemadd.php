<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "uc_mensageminfo.php" ?>
<?php include_once "ucinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$uc_mensagem_add = NULL; // Initialize page object first

class cuc_mensagem_add extends cuc_mensagem {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'uc_mensagem';

	// Page object name
	var $PageObjName = 'uc_mensagem_add';

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

		// Table object (uc_mensagem)
		if (!isset($GLOBALS["uc_mensagem"])) {
			$GLOBALS["uc_mensagem"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["uc_mensagem"];
		}

		// Table object (uc)
		if (!isset($GLOBALS['uc'])) $GLOBALS['uc'] = new cuc();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'uc_mensagem', TRUE);

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
			$this->Page_Terminate("uc_mensagemlist.php");
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
			if (@$_GET["nu_uc"] != "") {
				$this->nu_uc->setQueryStringValue($_GET["nu_uc"]);
				$this->setKey("nu_uc", $this->nu_uc->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_uc", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
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
					$this->Page_Terminate("uc_mensagemlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "uc_mensagemview.php")
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
		$this->nu_uc->CurrentValue = NULL;
		$this->nu_uc->OldValue = $this->nu_uc->CurrentValue;
		$this->nu_mensagem->CurrentValue = NULL;
		$this->nu_mensagem->OldValue = $this->nu_mensagem->CurrentValue;
		$this->ds_acao->CurrentValue = NULL;
		$this->ds_acao->OldValue = $this->ds_acao->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_uc->FldIsDetailKey) {
			$this->nu_uc->setFormValue($objForm->GetValue("x_nu_uc"));
		}
		if (!$this->nu_mensagem->FldIsDetailKey) {
			$this->nu_mensagem->setFormValue($objForm->GetValue("x_nu_mensagem"));
		}
		if (!$this->ds_acao->FldIsDetailKey) {
			$this->ds_acao->setFormValue($objForm->GetValue("x_ds_acao"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->nu_uc->CurrentValue = $this->nu_uc->FormValue;
		$this->nu_mensagem->CurrentValue = $this->nu_mensagem->FormValue;
		$this->ds_acao->CurrentValue = $this->ds_acao->FormValue;
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
		$this->nu_uc->setDbValue($rs->fields('nu_uc'));
		if (array_key_exists('EV__nu_uc', $rs->fields)) {
			$this->nu_uc->VirtualValue = $rs->fields('EV__nu_uc'); // Set up virtual field value
		} else {
			$this->nu_uc->VirtualValue = ""; // Clear value
		}
		$this->nu_mensagem->setDbValue($rs->fields('nu_mensagem'));
		if (array_key_exists('EV__nu_mensagem', $rs->fields)) {
			$this->nu_mensagem->VirtualValue = $rs->fields('EV__nu_mensagem'); // Set up virtual field value
		} else {
			$this->nu_mensagem->VirtualValue = ""; // Clear value
		}
		$this->ds_acao->setDbValue($rs->fields('ds_acao'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_uc->DbValue = $row['nu_uc'];
		$this->nu_mensagem->DbValue = $row['nu_mensagem'];
		$this->ds_acao->DbValue = $row['ds_acao'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_uc")) <> "")
			$this->nu_uc->CurrentValue = $this->getKey("nu_uc"); // nu_uc
		else
			$bValidKey = FALSE;
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
		// nu_uc
		// nu_mensagem
		// ds_acao

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_uc
			if ($this->nu_uc->VirtualValue <> "") {
				$this->nu_uc->ViewValue = $this->nu_uc->VirtualValue;
			} else {
			if (strval($this->nu_uc->CurrentValue) <> "") {
				$sFilterWrk = "[nu_uc]" . ew_SearchString("=", $this->nu_uc->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT DISTINCT [nu_uc], [co_alternativo] AS [DispFld], [no_uc] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[uc]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_uc, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [co_alternativo] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_uc->ViewValue = $rswrk->fields('DispFld');
					$this->nu_uc->ViewValue .= ew_ValueSeparator(1,$this->nu_uc) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_uc->ViewValue = $this->nu_uc->CurrentValue;
				}
			} else {
				$this->nu_uc->ViewValue = NULL;
			}
			}
			$this->nu_uc->ViewCustomAttributes = "";

			// nu_mensagem
			if ($this->nu_mensagem->VirtualValue <> "") {
				$this->nu_mensagem->ViewValue = $this->nu_mensagem->VirtualValue;
			} else {
			if (strval($this->nu_mensagem->CurrentValue) <> "") {
				$sFilterWrk = "[nu_mensagem]" . ew_SearchString("=", $this->nu_mensagem->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT DISTINCT [nu_mensagem], [co_alternativo] AS [DispFld], [no_mensagem] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[mensagem]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_mensagem, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [co_alternativo] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_mensagem->ViewValue = $rswrk->fields('DispFld');
					$this->nu_mensagem->ViewValue .= ew_ValueSeparator(1,$this->nu_mensagem) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_mensagem->ViewValue = $this->nu_mensagem->CurrentValue;
				}
			} else {
				$this->nu_mensagem->ViewValue = NULL;
			}
			}
			$this->nu_mensagem->ViewCustomAttributes = "";

			// ds_acao
			$this->ds_acao->ViewValue = $this->ds_acao->CurrentValue;
			$this->ds_acao->ViewCustomAttributes = "";

			// nu_uc
			$this->nu_uc->LinkCustomAttributes = "";
			$this->nu_uc->HrefValue = "";
			$this->nu_uc->TooltipValue = "";

			// nu_mensagem
			$this->nu_mensagem->LinkCustomAttributes = "";
			$this->nu_mensagem->HrefValue = "";
			$this->nu_mensagem->TooltipValue = "";

			// ds_acao
			$this->ds_acao->LinkCustomAttributes = "";
			$this->ds_acao->HrefValue = "";
			$this->ds_acao->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nu_uc
			$this->nu_uc->EditCustomAttributes = "";
			if ($this->nu_uc->getSessionValue() <> "") {
				$this->nu_uc->CurrentValue = $this->nu_uc->getSessionValue();
			if ($this->nu_uc->VirtualValue <> "") {
				$this->nu_uc->ViewValue = $this->nu_uc->VirtualValue;
			} else {
			if (strval($this->nu_uc->CurrentValue) <> "") {
				$sFilterWrk = "[nu_uc]" . ew_SearchString("=", $this->nu_uc->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT DISTINCT [nu_uc], [co_alternativo] AS [DispFld], [no_uc] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[uc]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_uc, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [co_alternativo] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_uc->ViewValue = $rswrk->fields('DispFld');
					$this->nu_uc->ViewValue .= ew_ValueSeparator(1,$this->nu_uc) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_uc->ViewValue = $this->nu_uc->CurrentValue;
				}
			} else {
				$this->nu_uc->ViewValue = NULL;
			}
			}
			$this->nu_uc->ViewCustomAttributes = "";
			} else {
			$sFilterWrk = "";
			$sSqlWrk = "SELECT DISTINCT [nu_uc], [co_alternativo] AS [DispFld], [no_uc] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[uc]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_uc, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [co_alternativo] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_uc->EditValue = $arwrk;
			}

			// nu_mensagem
			$this->nu_mensagem->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT DISTINCT [nu_mensagem], [co_alternativo] AS [DispFld], [no_mensagem] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[mensagem]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_mensagem, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [co_alternativo] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_mensagem->EditValue = $arwrk;

			// ds_acao
			$this->ds_acao->EditCustomAttributes = "";
			$this->ds_acao->EditValue = $this->ds_acao->CurrentValue;
			$this->ds_acao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_acao->FldCaption()));

			// Edit refer script
			// nu_uc

			$this->nu_uc->HrefValue = "";

			// nu_mensagem
			$this->nu_mensagem->HrefValue = "";

			// ds_acao
			$this->ds_acao->HrefValue = "";
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
		if (!$this->nu_uc->FldIsDetailKey && !is_null($this->nu_uc->FormValue) && $this->nu_uc->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_uc->FldCaption());
		}
		if (!$this->nu_mensagem->FldIsDetailKey && !is_null($this->nu_mensagem->FormValue) && $this->nu_mensagem->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_mensagem->FldCaption());
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

		// Check referential integrity for master table 'uc'
		$bValidMasterRecord = TRUE;
		$sMasterFilter = $this->SqlMasterFilter_uc();
		if (strval($this->nu_uc->CurrentValue) <> "") {
			$sMasterFilter = str_replace("@nu_uc@", ew_AdjustSql($this->nu_uc->CurrentValue), $sMasterFilter);
		} else {
			$bValidMasterRecord = FALSE;
		}
		if ($bValidMasterRecord) {
			$rsmaster = $GLOBALS["uc"]->LoadRs($sMasterFilter);
			$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
			$rsmaster->Close();
		}
		if (!$bValidMasterRecord) {
			$sRelatedRecordMsg = str_replace("%t", "uc", $Language->Phrase("RelatedRecordRequired"));
			$this->setFailureMessage($sRelatedRecordMsg);
			return FALSE;
		}

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// nu_uc
		$this->nu_uc->SetDbValueDef($rsnew, $this->nu_uc->CurrentValue, 0, FALSE);

		// nu_mensagem
		$this->nu_mensagem->SetDbValueDef($rsnew, $this->nu_mensagem->CurrentValue, 0, FALSE);

		// ds_acao
		$this->ds_acao->SetDbValueDef($rsnew, $this->ds_acao->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && $this->nu_uc->CurrentValue == "" && $this->nu_uc->getSessionValue() == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && $this->nu_mensagem->CurrentValue == "" && $this->nu_mensagem->getSessionValue() == "") {
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
			if ($sMasterTblVar == "uc") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_uc"] <> "") {
					$GLOBALS["uc"]->nu_uc->setQueryStringValue($_GET["nu_uc"]);
					$this->nu_uc->setQueryStringValue($GLOBALS["uc"]->nu_uc->QueryStringValue);
					$this->nu_uc->setSessionValue($this->nu_uc->QueryStringValue);
					if (!is_numeric($GLOBALS["uc"]->nu_uc->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "uc") {
				if ($this->nu_uc->QueryStringValue == "") $this->nu_uc->setSessionValue("");
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "uc_mensagemlist.php", $this->TableVar);
		$PageCaption = ($this->CurrentAction == "C") ? $Language->Phrase("Copy") : $Language->Phrase("Add");
		$Breadcrumb->Add("add", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'uc_mensagem';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'uc_mensagem';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_uc'];
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
if (!isset($uc_mensagem_add)) $uc_mensagem_add = new cuc_mensagem_add();

// Page init
$uc_mensagem_add->Page_Init();

// Page main
$uc_mensagem_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$uc_mensagem_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var uc_mensagem_add = new ew_Page("uc_mensagem_add");
uc_mensagem_add.PageID = "add"; // Page ID
var EW_PAGE_ID = uc_mensagem_add.PageID; // For backward compatibility

// Form object
var fuc_mensagemadd = new ew_Form("fuc_mensagemadd");

// Validate form
fuc_mensagemadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_uc");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($uc_mensagem->nu_uc->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_mensagem");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($uc_mensagem->nu_mensagem->FldCaption()) ?>");

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
fuc_mensagemadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fuc_mensagemadd.ValidateRequired = true;
<?php } else { ?>
fuc_mensagemadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fuc_mensagemadd.Lists["x_nu_uc"] = {"LinkField":"x_nu_uc","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_alternativo","x_no_uc","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fuc_mensagemadd.Lists["x_nu_mensagem"] = {"LinkField":"x_nu_mensagem","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_alternativo","x_no_mensagem","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $uc_mensagem_add->ShowPageHeader(); ?>
<?php
$uc_mensagem_add->ShowMessage();
?>
<form name="fuc_mensagemadd" id="fuc_mensagemadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="uc_mensagem">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_uc_mensagemadd" class="table table-bordered table-striped">
<?php if ($uc_mensagem->nu_uc->Visible) { // nu_uc ?>
	<tr id="r_nu_uc">
		<td><span id="elh_uc_mensagem_nu_uc"><?php echo $uc_mensagem->nu_uc->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $uc_mensagem->nu_uc->CellAttributes() ?>>
<?php if ($uc_mensagem->nu_uc->getSessionValue() <> "") { ?>
<span<?php echo $uc_mensagem->nu_uc->ViewAttributes() ?>>
<?php echo $uc_mensagem->nu_uc->ViewValue ?></span>
<input type="hidden" id="x_nu_uc" name="x_nu_uc" value="<?php echo ew_HtmlEncode($uc_mensagem->nu_uc->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_nu_uc" id="x_nu_uc" name="x_nu_uc"<?php echo $uc_mensagem->nu_uc->EditAttributes() ?>>
<?php
if (is_array($uc_mensagem->nu_uc->EditValue)) {
	$arwrk = $uc_mensagem->nu_uc->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($uc_mensagem->nu_uc->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$uc_mensagem->nu_uc) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "uc")) { ?>
&nbsp;<a id="aol_x_nu_uc" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_nu_uc',url:'ucaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $uc_mensagem->nu_uc->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
fuc_mensagemadd.Lists["x_nu_uc"].Options = <?php echo (is_array($uc_mensagem->nu_uc->EditValue)) ? ew_ArrayToJson($uc_mensagem->nu_uc->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<?php echo $uc_mensagem->nu_uc->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($uc_mensagem->nu_mensagem->Visible) { // nu_mensagem ?>
	<tr id="r_nu_mensagem">
		<td><span id="elh_uc_mensagem_nu_mensagem"><?php echo $uc_mensagem->nu_mensagem->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $uc_mensagem->nu_mensagem->CellAttributes() ?>>
<span id="el_uc_mensagem_nu_mensagem" class="control-group">
<select data-field="x_nu_mensagem" id="x_nu_mensagem" name="x_nu_mensagem"<?php echo $uc_mensagem->nu_mensagem->EditAttributes() ?>>
<?php
if (is_array($uc_mensagem->nu_mensagem->EditValue)) {
	$arwrk = $uc_mensagem->nu_mensagem->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($uc_mensagem->nu_mensagem->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$uc_mensagem->nu_mensagem) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "mensagem")) { ?>
&nbsp;<a id="aol_x_nu_mensagem" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_nu_mensagem',url:'mensagemaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $uc_mensagem->nu_mensagem->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
fuc_mensagemadd.Lists["x_nu_mensagem"].Options = <?php echo (is_array($uc_mensagem->nu_mensagem->EditValue)) ? ew_ArrayToJson($uc_mensagem->nu_mensagem->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $uc_mensagem->nu_mensagem->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($uc_mensagem->ds_acao->Visible) { // ds_acao ?>
	<tr id="r_ds_acao">
		<td><span id="elh_uc_mensagem_ds_acao"><?php echo $uc_mensagem->ds_acao->FldCaption() ?></span></td>
		<td<?php echo $uc_mensagem->ds_acao->CellAttributes() ?>>
<span id="el_uc_mensagem_ds_acao" class="control-group">
<textarea data-field="x_ds_acao" name="x_ds_acao" id="x_ds_acao" cols="35" rows="4" placeholder="<?php echo $uc_mensagem->ds_acao->PlaceHolder ?>"<?php echo $uc_mensagem->ds_acao->EditAttributes() ?>><?php echo $uc_mensagem->ds_acao->EditValue ?></textarea>
</span>
<?php echo $uc_mensagem->ds_acao->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fuc_mensagemadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$uc_mensagem_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$uc_mensagem_add->Page_Terminate();
?>
