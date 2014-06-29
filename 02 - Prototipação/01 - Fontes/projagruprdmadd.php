<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "projagruprdminfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$projagruprdm_add = NULL; // Initialize page object first

class cprojagruprdm_add extends cprojagruprdm {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'projagruprdm';

	// Page object name
	var $PageObjName = 'projagruprdm_add';

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

		// Table object (projagruprdm)
		if (!isset($GLOBALS["projagruprdm"])) {
			$GLOBALS["projagruprdm"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["projagruprdm"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'projagruprdm', TRUE);

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
			$this->Page_Terminate("projagruprdmlist.php");
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
			if (@$_GET["nu_projAgrupRedmine"] != "") {
				$this->nu_projAgrupRedmine->setQueryStringValue($_GET["nu_projAgrupRedmine"]);
				$this->setKey("nu_projAgrupRedmine", $this->nu_projAgrupRedmine->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_projAgrupRedmine", ""); // Clear key
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
					$this->Page_Terminate("projagruprdmlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "projagruprdmview.php")
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
		$this->nu_projAgrupRedmine->CurrentValue = NULL;
		$this->nu_projAgrupRedmine->OldValue = $this->nu_projAgrupRedmine->CurrentValue;
		$this->nu_nivel->CurrentValue = NULL;
		$this->nu_nivel->OldValue = $this->nu_nivel->CurrentValue;
		$this->nu_projAgrupPai->CurrentValue = NULL;
		$this->nu_projAgrupPai->OldValue = $this->nu_projAgrupPai->CurrentValue;
		$this->ds_projredmine->CurrentValue = NULL;
		$this->ds_projredmine->OldValue = $this->ds_projredmine->CurrentValue;
		$this->nu_usuarioInc->CurrentValue = NULL;
		$this->nu_usuarioInc->OldValue = $this->nu_usuarioInc->CurrentValue;
		$this->dh_inclusao->CurrentValue = NULL;
		$this->dh_inclusao->OldValue = $this->dh_inclusao->CurrentValue;
		$this->nu_usuarioAlt->CurrentValue = NULL;
		$this->nu_usuarioAlt->OldValue = $this->nu_usuarioAlt->CurrentValue;
		$this->dh_alteracao->CurrentValue = NULL;
		$this->dh_alteracao->OldValue = $this->dh_alteracao->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_projAgrupRedmine->FldIsDetailKey) {
			$this->nu_projAgrupRedmine->setFormValue($objForm->GetValue("x_nu_projAgrupRedmine"));
		}
		if (!$this->nu_nivel->FldIsDetailKey) {
			$this->nu_nivel->setFormValue($objForm->GetValue("x_nu_nivel"));
		}
		if (!$this->nu_projAgrupPai->FldIsDetailKey) {
			$this->nu_projAgrupPai->setFormValue($objForm->GetValue("x_nu_projAgrupPai"));
		}
		if (!$this->ds_projredmine->FldIsDetailKey) {
			$this->ds_projredmine->setFormValue($objForm->GetValue("x_ds_projredmine"));
		}
		if (!$this->nu_usuarioInc->FldIsDetailKey) {
			$this->nu_usuarioInc->setFormValue($objForm->GetValue("x_nu_usuarioInc"));
		}
		if (!$this->dh_inclusao->FldIsDetailKey) {
			$this->dh_inclusao->setFormValue($objForm->GetValue("x_dh_inclusao"));
			$this->dh_inclusao->CurrentValue = ew_UnFormatDateTime($this->dh_inclusao->CurrentValue, 7);
		}
		if (!$this->nu_usuarioAlt->FldIsDetailKey) {
			$this->nu_usuarioAlt->setFormValue($objForm->GetValue("x_nu_usuarioAlt"));
		}
		if (!$this->dh_alteracao->FldIsDetailKey) {
			$this->dh_alteracao->setFormValue($objForm->GetValue("x_dh_alteracao"));
			$this->dh_alteracao->CurrentValue = ew_UnFormatDateTime($this->dh_alteracao->CurrentValue, 7);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->nu_projAgrupRedmine->CurrentValue = $this->nu_projAgrupRedmine->FormValue;
		$this->nu_nivel->CurrentValue = $this->nu_nivel->FormValue;
		$this->nu_projAgrupPai->CurrentValue = $this->nu_projAgrupPai->FormValue;
		$this->ds_projredmine->CurrentValue = $this->ds_projredmine->FormValue;
		$this->nu_usuarioInc->CurrentValue = $this->nu_usuarioInc->FormValue;
		$this->dh_inclusao->CurrentValue = $this->dh_inclusao->FormValue;
		$this->dh_inclusao->CurrentValue = ew_UnFormatDateTime($this->dh_inclusao->CurrentValue, 7);
		$this->nu_usuarioAlt->CurrentValue = $this->nu_usuarioAlt->FormValue;
		$this->dh_alteracao->CurrentValue = $this->dh_alteracao->FormValue;
		$this->dh_alteracao->CurrentValue = ew_UnFormatDateTime($this->dh_alteracao->CurrentValue, 7);
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
		$this->nu_projAgrupRedmine->setDbValue($rs->fields('nu_projAgrupRedmine'));
		$this->nu_nivel->setDbValue($rs->fields('nu_nivel'));
		$this->nu_projAgrupPai->setDbValue($rs->fields('nu_projAgrupPai'));
		$this->ds_projredmine->setDbValue($rs->fields('ds_projredmine'));
		$this->nu_usuarioInc->setDbValue($rs->fields('nu_usuarioInc'));
		$this->dh_inclusao->setDbValue($rs->fields('dh_inclusao'));
		$this->nu_usuarioAlt->setDbValue($rs->fields('nu_usuarioAlt'));
		$this->dh_alteracao->setDbValue($rs->fields('dh_alteracao'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_projAgrupRedmine->DbValue = $row['nu_projAgrupRedmine'];
		$this->nu_nivel->DbValue = $row['nu_nivel'];
		$this->nu_projAgrupPai->DbValue = $row['nu_projAgrupPai'];
		$this->ds_projredmine->DbValue = $row['ds_projredmine'];
		$this->nu_usuarioInc->DbValue = $row['nu_usuarioInc'];
		$this->dh_inclusao->DbValue = $row['dh_inclusao'];
		$this->nu_usuarioAlt->DbValue = $row['nu_usuarioAlt'];
		$this->dh_alteracao->DbValue = $row['dh_alteracao'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_projAgrupRedmine")) <> "")
			$this->nu_projAgrupRedmine->CurrentValue = $this->getKey("nu_projAgrupRedmine"); // nu_projAgrupRedmine
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
		// nu_projAgrupRedmine
		// nu_nivel
		// nu_projAgrupPai
		// ds_projredmine
		// nu_usuarioInc
		// dh_inclusao
		// nu_usuarioAlt
		// dh_alteracao

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_projAgrupRedmine
			$this->nu_projAgrupRedmine->ViewValue = $this->nu_projAgrupRedmine->CurrentValue;
			$this->nu_projAgrupRedmine->ViewCustomAttributes = "";

			// nu_nivel
			if (strval($this->nu_nivel->CurrentValue) <> "") {
				switch ($this->nu_nivel->CurrentValue) {
					case $this->nu_nivel->FldTagValue(1):
						$this->nu_nivel->ViewValue = $this->nu_nivel->FldTagCaption(1) <> "" ? $this->nu_nivel->FldTagCaption(1) : $this->nu_nivel->CurrentValue;
						break;
					case $this->nu_nivel->FldTagValue(2):
						$this->nu_nivel->ViewValue = $this->nu_nivel->FldTagCaption(2) <> "" ? $this->nu_nivel->FldTagCaption(2) : $this->nu_nivel->CurrentValue;
						break;
					case $this->nu_nivel->FldTagValue(3):
						$this->nu_nivel->ViewValue = $this->nu_nivel->FldTagCaption(3) <> "" ? $this->nu_nivel->FldTagCaption(3) : $this->nu_nivel->CurrentValue;
						break;
					case $this->nu_nivel->FldTagValue(4):
						$this->nu_nivel->ViewValue = $this->nu_nivel->FldTagCaption(4) <> "" ? $this->nu_nivel->FldTagCaption(4) : $this->nu_nivel->CurrentValue;
						break;
					case $this->nu_nivel->FldTagValue(5):
						$this->nu_nivel->ViewValue = $this->nu_nivel->FldTagCaption(5) <> "" ? $this->nu_nivel->FldTagCaption(5) : $this->nu_nivel->CurrentValue;
						break;
					default:
						$this->nu_nivel->ViewValue = $this->nu_nivel->CurrentValue;
				}
			} else {
				$this->nu_nivel->ViewValue = NULL;
			}
			$this->nu_nivel->ViewCustomAttributes = "";

			// nu_projAgrupPai
			$this->nu_projAgrupPai->ViewValue = $this->nu_projAgrupPai->CurrentValue;
			$this->nu_projAgrupPai->ViewCustomAttributes = "";

			// ds_projredmine
			$this->ds_projredmine->ViewValue = $this->ds_projredmine->CurrentValue;
			$this->ds_projredmine->ViewCustomAttributes = "";

			// nu_usuarioInc
			if (strval($this->nu_usuarioInc->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioInc->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioInc, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioInc->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioInc->ViewValue = $this->nu_usuarioInc->CurrentValue;
				}
			} else {
				$this->nu_usuarioInc->ViewValue = NULL;
			}
			$this->nu_usuarioInc->ViewCustomAttributes = "";

			// dh_inclusao
			$this->dh_inclusao->ViewValue = $this->dh_inclusao->CurrentValue;
			$this->dh_inclusao->ViewValue = ew_FormatDateTime($this->dh_inclusao->ViewValue, 7);
			$this->dh_inclusao->ViewCustomAttributes = "";

			// nu_usuarioAlt
			if (strval($this->nu_usuarioAlt->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioAlt->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioAlt, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioAlt->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioAlt->ViewValue = $this->nu_usuarioAlt->CurrentValue;
				}
			} else {
				$this->nu_usuarioAlt->ViewValue = NULL;
			}
			$this->nu_usuarioAlt->ViewCustomAttributes = "";

			// dh_alteracao
			$this->dh_alteracao->ViewValue = $this->dh_alteracao->CurrentValue;
			$this->dh_alteracao->ViewValue = ew_FormatDateTime($this->dh_alteracao->ViewValue, 7);
			$this->dh_alteracao->ViewCustomAttributes = "";

			// nu_projAgrupRedmine
			$this->nu_projAgrupRedmine->LinkCustomAttributes = "";
			$this->nu_projAgrupRedmine->HrefValue = "";
			$this->nu_projAgrupRedmine->TooltipValue = "";

			// nu_nivel
			$this->nu_nivel->LinkCustomAttributes = "";
			$this->nu_nivel->HrefValue = "";
			$this->nu_nivel->TooltipValue = "";

			// nu_projAgrupPai
			$this->nu_projAgrupPai->LinkCustomAttributes = "";
			$this->nu_projAgrupPai->HrefValue = "";
			$this->nu_projAgrupPai->TooltipValue = "";

			// ds_projredmine
			$this->ds_projredmine->LinkCustomAttributes = "";
			$this->ds_projredmine->HrefValue = "";
			$this->ds_projredmine->TooltipValue = "";

			// nu_usuarioInc
			$this->nu_usuarioInc->LinkCustomAttributes = "";
			$this->nu_usuarioInc->HrefValue = "";
			$this->nu_usuarioInc->TooltipValue = "";

			// dh_inclusao
			$this->dh_inclusao->LinkCustomAttributes = "";
			$this->dh_inclusao->HrefValue = "";
			$this->dh_inclusao->TooltipValue = "";

			// nu_usuarioAlt
			$this->nu_usuarioAlt->LinkCustomAttributes = "";
			$this->nu_usuarioAlt->HrefValue = "";
			$this->nu_usuarioAlt->TooltipValue = "";

			// dh_alteracao
			$this->dh_alteracao->LinkCustomAttributes = "";
			$this->dh_alteracao->HrefValue = "";
			$this->dh_alteracao->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nu_projAgrupRedmine
			$this->nu_projAgrupRedmine->EditCustomAttributes = "";
			$this->nu_projAgrupRedmine->EditValue = ew_HtmlEncode($this->nu_projAgrupRedmine->CurrentValue);
			$this->nu_projAgrupRedmine->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_projAgrupRedmine->FldCaption()));

			// nu_nivel
			$this->nu_nivel->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->nu_nivel->FldTagValue(1), $this->nu_nivel->FldTagCaption(1) <> "" ? $this->nu_nivel->FldTagCaption(1) : $this->nu_nivel->FldTagValue(1));
			$arwrk[] = array($this->nu_nivel->FldTagValue(2), $this->nu_nivel->FldTagCaption(2) <> "" ? $this->nu_nivel->FldTagCaption(2) : $this->nu_nivel->FldTagValue(2));
			$arwrk[] = array($this->nu_nivel->FldTagValue(3), $this->nu_nivel->FldTagCaption(3) <> "" ? $this->nu_nivel->FldTagCaption(3) : $this->nu_nivel->FldTagValue(3));
			$arwrk[] = array($this->nu_nivel->FldTagValue(4), $this->nu_nivel->FldTagCaption(4) <> "" ? $this->nu_nivel->FldTagCaption(4) : $this->nu_nivel->FldTagValue(4));
			$arwrk[] = array($this->nu_nivel->FldTagValue(5), $this->nu_nivel->FldTagCaption(5) <> "" ? $this->nu_nivel->FldTagCaption(5) : $this->nu_nivel->FldTagValue(5));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->nu_nivel->EditValue = $arwrk;

			// nu_projAgrupPai
			$this->nu_projAgrupPai->EditCustomAttributes = "";
			$this->nu_projAgrupPai->EditValue = ew_HtmlEncode($this->nu_projAgrupPai->CurrentValue);
			$this->nu_projAgrupPai->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_projAgrupPai->FldCaption()));

			// ds_projredmine
			$this->ds_projredmine->EditCustomAttributes = "";
			$this->ds_projredmine->EditValue = $this->ds_projredmine->CurrentValue;
			$this->ds_projredmine->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_projredmine->FldCaption()));

			// nu_usuarioInc
			// dh_inclusao
			// nu_usuarioAlt
			// dh_alteracao
			// Edit refer script
			// nu_projAgrupRedmine

			$this->nu_projAgrupRedmine->HrefValue = "";

			// nu_nivel
			$this->nu_nivel->HrefValue = "";

			// nu_projAgrupPai
			$this->nu_projAgrupPai->HrefValue = "";

			// ds_projredmine
			$this->ds_projredmine->HrefValue = "";

			// nu_usuarioInc
			$this->nu_usuarioInc->HrefValue = "";

			// dh_inclusao
			$this->dh_inclusao->HrefValue = "";

			// nu_usuarioAlt
			$this->nu_usuarioAlt->HrefValue = "";

			// dh_alteracao
			$this->dh_alteracao->HrefValue = "";
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
		if (!$this->nu_projAgrupRedmine->FldIsDetailKey && !is_null($this->nu_projAgrupRedmine->FormValue) && $this->nu_projAgrupRedmine->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_projAgrupRedmine->FldCaption());
		}
		if (!ew_CheckInteger($this->nu_projAgrupRedmine->FormValue)) {
			ew_AddMessage($gsFormError, $this->nu_projAgrupRedmine->FldErrMsg());
		}
		if (!$this->nu_nivel->FldIsDetailKey && !is_null($this->nu_nivel->FormValue) && $this->nu_nivel->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_nivel->FldCaption());
		}
		if (!ew_CheckInteger($this->nu_projAgrupPai->FormValue)) {
			ew_AddMessage($gsFormError, $this->nu_projAgrupPai->FldErrMsg());
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
		if ($this->nu_projAgrupRedmine->CurrentValue <> "") { // Check field with unique index
			$sFilter = "(nu_projAgrupRedmine = " . ew_AdjustSql($this->nu_projAgrupRedmine->CurrentValue) . ")";
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->nu_projAgrupRedmine->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->nu_projAgrupRedmine->CurrentValue, $sIdxErrMsg);
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

		// nu_projAgrupRedmine
		$this->nu_projAgrupRedmine->SetDbValueDef($rsnew, $this->nu_projAgrupRedmine->CurrentValue, 0, FALSE);

		// nu_nivel
		$this->nu_nivel->SetDbValueDef($rsnew, $this->nu_nivel->CurrentValue, 0, FALSE);

		// nu_projAgrupPai
		$this->nu_projAgrupPai->SetDbValueDef($rsnew, $this->nu_projAgrupPai->CurrentValue, NULL, FALSE);

		// ds_projredmine
		$this->ds_projredmine->SetDbValueDef($rsnew, $this->ds_projredmine->CurrentValue, NULL, FALSE);

		// nu_usuarioInc
		$this->nu_usuarioInc->SetDbValueDef($rsnew, CurrentUserID(), NULL);
		$rsnew['nu_usuarioInc'] = &$this->nu_usuarioInc->DbValue;

		// dh_inclusao
		$this->dh_inclusao->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
		$rsnew['dh_inclusao'] = &$this->dh_inclusao->DbValue;

		// nu_usuarioAlt
		$this->nu_usuarioAlt->SetDbValueDef($rsnew, CurrentUserID(), NULL);
		$rsnew['nu_usuarioAlt'] = &$this->nu_usuarioAlt->DbValue;

		// dh_alteracao
		$this->dh_alteracao->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
		$rsnew['dh_alteracao'] = &$this->dh_alteracao->DbValue;

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && $this->nu_projAgrupRedmine->CurrentValue == "" && $this->nu_projAgrupRedmine->getSessionValue() == "") {
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
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "projagruprdmlist.php", $this->TableVar);
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
if (!isset($projagruprdm_add)) $projagruprdm_add = new cprojagruprdm_add();

// Page init
$projagruprdm_add->Page_Init();

// Page main
$projagruprdm_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$projagruprdm_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var projagruprdm_add = new ew_Page("projagruprdm_add");
projagruprdm_add.PageID = "add"; // Page ID
var EW_PAGE_ID = projagruprdm_add.PageID; // For backward compatibility

// Form object
var fprojagruprdmadd = new ew_Form("fprojagruprdmadd");

// Validate form
fprojagruprdmadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_projAgrupRedmine");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($projagruprdm->nu_projAgrupRedmine->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_projAgrupRedmine");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($projagruprdm->nu_projAgrupRedmine->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nu_nivel");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($projagruprdm->nu_nivel->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_projAgrupPai");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($projagruprdm->nu_projAgrupPai->FldErrMsg()) ?>");

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
fprojagruprdmadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fprojagruprdmadd.ValidateRequired = true;
<?php } else { ?>
fprojagruprdmadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fprojagruprdmadd.Lists["x_nu_usuarioInc"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fprojagruprdmadd.Lists["x_nu_usuarioAlt"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $projagruprdm_add->ShowPageHeader(); ?>
<?php
$projagruprdm_add->ShowMessage();
?>
<form name="fprojagruprdmadd" id="fprojagruprdmadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="projagruprdm">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_projagruprdmadd" class="table table-bordered table-striped">
<?php if ($projagruprdm->nu_projAgrupRedmine->Visible) { // nu_projAgrupRedmine ?>
	<tr id="r_nu_projAgrupRedmine">
		<td><span id="elh_projagruprdm_nu_projAgrupRedmine"><?php echo $projagruprdm->nu_projAgrupRedmine->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $projagruprdm->nu_projAgrupRedmine->CellAttributes() ?>>
<span id="el_projagruprdm_nu_projAgrupRedmine" class="control-group">
<input type="text" data-field="x_nu_projAgrupRedmine" name="x_nu_projAgrupRedmine" id="x_nu_projAgrupRedmine" placeholder="<?php echo $projagruprdm->nu_projAgrupRedmine->PlaceHolder ?>" value="<?php echo $projagruprdm->nu_projAgrupRedmine->EditValue ?>"<?php echo $projagruprdm->nu_projAgrupRedmine->EditAttributes() ?>>
</span>
<?php echo $projagruprdm->nu_projAgrupRedmine->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($projagruprdm->nu_nivel->Visible) { // nu_nivel ?>
	<tr id="r_nu_nivel">
		<td><span id="elh_projagruprdm_nu_nivel"><?php echo $projagruprdm->nu_nivel->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $projagruprdm->nu_nivel->CellAttributes() ?>>
<span id="el_projagruprdm_nu_nivel" class="control-group">
<select data-field="x_nu_nivel" id="x_nu_nivel" name="x_nu_nivel"<?php echo $projagruprdm->nu_nivel->EditAttributes() ?>>
<?php
if (is_array($projagruprdm->nu_nivel->EditValue)) {
	$arwrk = $projagruprdm->nu_nivel->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($projagruprdm->nu_nivel->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $projagruprdm->nu_nivel->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($projagruprdm->nu_projAgrupPai->Visible) { // nu_projAgrupPai ?>
	<tr id="r_nu_projAgrupPai">
		<td><span id="elh_projagruprdm_nu_projAgrupPai"><?php echo $projagruprdm->nu_projAgrupPai->FldCaption() ?></span></td>
		<td<?php echo $projagruprdm->nu_projAgrupPai->CellAttributes() ?>>
<span id="el_projagruprdm_nu_projAgrupPai" class="control-group">
<input type="text" data-field="x_nu_projAgrupPai" name="x_nu_projAgrupPai" id="x_nu_projAgrupPai" size="30" placeholder="<?php echo $projagruprdm->nu_projAgrupPai->PlaceHolder ?>" value="<?php echo $projagruprdm->nu_projAgrupPai->EditValue ?>"<?php echo $projagruprdm->nu_projAgrupPai->EditAttributes() ?>>
</span>
<?php echo $projagruprdm->nu_projAgrupPai->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($projagruprdm->ds_projredmine->Visible) { // ds_projredmine ?>
	<tr id="r_ds_projredmine">
		<td><span id="elh_projagruprdm_ds_projredmine"><?php echo $projagruprdm->ds_projredmine->FldCaption() ?></span></td>
		<td<?php echo $projagruprdm->ds_projredmine->CellAttributes() ?>>
<span id="el_projagruprdm_ds_projredmine" class="control-group">
<textarea data-field="x_ds_projredmine" name="x_ds_projredmine" id="x_ds_projredmine" cols="35" rows="4" placeholder="<?php echo $projagruprdm->ds_projredmine->PlaceHolder ?>"<?php echo $projagruprdm->ds_projredmine->EditAttributes() ?>><?php echo $projagruprdm->ds_projredmine->EditValue ?></textarea>
</span>
<?php echo $projagruprdm->ds_projredmine->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fprojagruprdmadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$projagruprdm_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$projagruprdm_add->Page_Terminate();
?>
