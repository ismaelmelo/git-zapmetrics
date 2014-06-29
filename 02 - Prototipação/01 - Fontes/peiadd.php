<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "peiinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$pei_add = NULL; // Initialize page object first

class cpei_add extends cpei {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'pei';

	// Page object name
	var $PageObjName = 'pei_add';

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

		// Table object (pei)
		if (!isset($GLOBALS["pei"])) {
			$GLOBALS["pei"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["pei"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'pei', TRUE);

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
			$this->Page_Terminate("peilist.php");
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
			if (@$_GET["nu_pei"] != "") {
				$this->nu_pei->setQueryStringValue($_GET["nu_pei"]);
				$this->setKey("nu_pei", $this->nu_pei->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_pei", ""); // Clear key
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
					$this->Page_Terminate("peilist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "peiview.php")
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
		$this->nu_periodoPei->CurrentValue = NULL;
		$this->nu_periodoPei->OldValue = $this->nu_periodoPei->CurrentValue;
		$this->no_capaPei->CurrentValue = NULL;
		$this->no_capaPei->OldValue = $this->no_capaPei->CurrentValue;
		$this->ds_introducao->CurrentValue = NULL;
		$this->ds_introducao->OldValue = $this->ds_introducao->CurrentValue;
		$this->ds_missao->CurrentValue = NULL;
		$this->ds_missao->OldValue = $this->ds_missao->CurrentValue;
		$this->ds_visao->CurrentValue = NULL;
		$this->ds_visao->OldValue = $this->ds_visao->CurrentValue;
		$this->ds_valores->CurrentValue = NULL;
		$this->ds_valores->OldValue = $this->ds_valores->CurrentValue;
		$this->ic_situacao->CurrentValue = "D";
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_periodoPei->FldIsDetailKey) {
			$this->nu_periodoPei->setFormValue($objForm->GetValue("x_nu_periodoPei"));
		}
		if (!$this->no_capaPei->FldIsDetailKey) {
			$this->no_capaPei->setFormValue($objForm->GetValue("x_no_capaPei"));
		}
		if (!$this->ds_introducao->FldIsDetailKey) {
			$this->ds_introducao->setFormValue($objForm->GetValue("x_ds_introducao"));
		}
		if (!$this->ds_missao->FldIsDetailKey) {
			$this->ds_missao->setFormValue($objForm->GetValue("x_ds_missao"));
		}
		if (!$this->ds_visao->FldIsDetailKey) {
			$this->ds_visao->setFormValue($objForm->GetValue("x_ds_visao"));
		}
		if (!$this->ds_valores->FldIsDetailKey) {
			$this->ds_valores->setFormValue($objForm->GetValue("x_ds_valores"));
		}
		if (!$this->ic_situacao->FldIsDetailKey) {
			$this->ic_situacao->setFormValue($objForm->GetValue("x_ic_situacao"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->nu_periodoPei->CurrentValue = $this->nu_periodoPei->FormValue;
		$this->no_capaPei->CurrentValue = $this->no_capaPei->FormValue;
		$this->ds_introducao->CurrentValue = $this->ds_introducao->FormValue;
		$this->ds_missao->CurrentValue = $this->ds_missao->FormValue;
		$this->ds_visao->CurrentValue = $this->ds_visao->FormValue;
		$this->ds_valores->CurrentValue = $this->ds_valores->FormValue;
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
		$this->nu_pei->setDbValue($rs->fields('nu_pei'));
		$this->nu_periodoPei->setDbValue($rs->fields('nu_periodoPei'));
		if (array_key_exists('EV__nu_periodoPei', $rs->fields)) {
			$this->nu_periodoPei->VirtualValue = $rs->fields('EV__nu_periodoPei'); // Set up virtual field value
		} else {
			$this->nu_periodoPei->VirtualValue = ""; // Clear value
		}
		$this->no_capaPei->setDbValue($rs->fields('no_capaPei'));
		$this->ds_introducao->setDbValue($rs->fields('ds_introducao'));
		$this->ds_missao->setDbValue($rs->fields('ds_missao'));
		$this->ds_visao->setDbValue($rs->fields('ds_visao'));
		$this->ds_valores->setDbValue($rs->fields('ds_valores'));
		$this->ic_situacao->setDbValue($rs->fields('ic_situacao'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_pei->DbValue = $row['nu_pei'];
		$this->nu_periodoPei->DbValue = $row['nu_periodoPei'];
		$this->no_capaPei->DbValue = $row['no_capaPei'];
		$this->ds_introducao->DbValue = $row['ds_introducao'];
		$this->ds_missao->DbValue = $row['ds_missao'];
		$this->ds_visao->DbValue = $row['ds_visao'];
		$this->ds_valores->DbValue = $row['ds_valores'];
		$this->ic_situacao->DbValue = $row['ic_situacao'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_pei")) <> "")
			$this->nu_pei->CurrentValue = $this->getKey("nu_pei"); // nu_pei
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
		// nu_pei
		// nu_periodoPei
		// no_capaPei
		// ds_introducao
		// ds_missao
		// ds_visao
		// ds_valores
		// ic_situacao

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_pei
			$this->nu_pei->ViewValue = $this->nu_pei->CurrentValue;
			$this->nu_pei->ViewCustomAttributes = "";

			// nu_periodoPei
			if ($this->nu_periodoPei->VirtualValue <> "") {
				$this->nu_periodoPei->ViewValue = $this->nu_periodoPei->VirtualValue;
			} else {
			if (strval($this->nu_periodoPei->CurrentValue) <> "") {
				$sFilterWrk = "[nu_periodoPei]" . ew_SearchString("=", $this->nu_periodoPei->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_periodoPei], [nu_anoInicio] AS [DispFld], [nu_anoFim] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[periodopei]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_periodoPei, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_anoInicio] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_periodoPei->ViewValue = $rswrk->fields('DispFld');
					$this->nu_periodoPei->ViewValue .= ew_ValueSeparator(1,$this->nu_periodoPei) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_periodoPei->ViewValue = $this->nu_periodoPei->CurrentValue;
				}
			} else {
				$this->nu_periodoPei->ViewValue = NULL;
			}
			}
			$this->nu_periodoPei->ViewCustomAttributes = "";

			// no_capaPei
			$this->no_capaPei->ViewValue = $this->no_capaPei->CurrentValue;
			$this->no_capaPei->ViewCustomAttributes = "";

			// ds_introducao
			$this->ds_introducao->ViewValue = $this->ds_introducao->CurrentValue;
			$this->ds_introducao->ViewCustomAttributes = "";

			// ds_missao
			$this->ds_missao->ViewValue = $this->ds_missao->CurrentValue;
			$this->ds_missao->ViewCustomAttributes = "";

			// ds_visao
			$this->ds_visao->ViewValue = $this->ds_visao->CurrentValue;
			$this->ds_visao->ViewCustomAttributes = "";

			// ds_valores
			$this->ds_valores->ViewValue = $this->ds_valores->CurrentValue;
			$this->ds_valores->ViewCustomAttributes = "";

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

			// nu_periodoPei
			$this->nu_periodoPei->LinkCustomAttributes = "";
			$this->nu_periodoPei->HrefValue = "";
			$this->nu_periodoPei->TooltipValue = "";

			// no_capaPei
			$this->no_capaPei->LinkCustomAttributes = "";
			$this->no_capaPei->HrefValue = "";
			$this->no_capaPei->TooltipValue = "";

			// ds_introducao
			$this->ds_introducao->LinkCustomAttributes = "";
			$this->ds_introducao->HrefValue = "";
			$this->ds_introducao->TooltipValue = "";

			// ds_missao
			$this->ds_missao->LinkCustomAttributes = "";
			$this->ds_missao->HrefValue = "";
			$this->ds_missao->TooltipValue = "";

			// ds_visao
			$this->ds_visao->LinkCustomAttributes = "";
			$this->ds_visao->HrefValue = "";
			$this->ds_visao->TooltipValue = "";

			// ds_valores
			$this->ds_valores->LinkCustomAttributes = "";
			$this->ds_valores->HrefValue = "";
			$this->ds_valores->TooltipValue = "";

			// ic_situacao
			$this->ic_situacao->LinkCustomAttributes = "";
			$this->ic_situacao->HrefValue = "";
			$this->ic_situacao->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nu_periodoPei
			$this->nu_periodoPei->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_periodoPei], [nu_anoInicio] AS [DispFld], [nu_anoFim] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[periodopei]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_periodoPei, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_anoInicio] DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_periodoPei->EditValue = $arwrk;

			// no_capaPei
			$this->no_capaPei->EditCustomAttributes = "";
			$this->no_capaPei->EditValue = ew_HtmlEncode($this->no_capaPei->CurrentValue);
			$this->no_capaPei->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_capaPei->FldCaption()));

			// ds_introducao
			$this->ds_introducao->EditCustomAttributes = "";
			$this->ds_introducao->EditValue = $this->ds_introducao->CurrentValue;
			$this->ds_introducao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_introducao->FldCaption()));

			// ds_missao
			$this->ds_missao->EditCustomAttributes = "";
			$this->ds_missao->EditValue = $this->ds_missao->CurrentValue;
			$this->ds_missao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_missao->FldCaption()));

			// ds_visao
			$this->ds_visao->EditCustomAttributes = "";
			$this->ds_visao->EditValue = $this->ds_visao->CurrentValue;
			$this->ds_visao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_visao->FldCaption()));

			// ds_valores
			$this->ds_valores->EditCustomAttributes = "";
			$this->ds_valores->EditValue = $this->ds_valores->CurrentValue;
			$this->ds_valores->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_valores->FldCaption()));

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
			// nu_periodoPei

			$this->nu_periodoPei->HrefValue = "";

			// no_capaPei
			$this->no_capaPei->HrefValue = "";

			// ds_introducao
			$this->ds_introducao->HrefValue = "";

			// ds_missao
			$this->ds_missao->HrefValue = "";

			// ds_visao
			$this->ds_visao->HrefValue = "";

			// ds_valores
			$this->ds_valores->HrefValue = "";

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
		if (!$this->nu_periodoPei->FldIsDetailKey && !is_null($this->nu_periodoPei->FormValue) && $this->nu_periodoPei->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_periodoPei->FldCaption());
		}
		if (!$this->no_capaPei->FldIsDetailKey && !is_null($this->no_capaPei->FormValue) && $this->no_capaPei->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_capaPei->FldCaption());
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

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// nu_periodoPei
		$this->nu_periodoPei->SetDbValueDef($rsnew, $this->nu_periodoPei->CurrentValue, NULL, FALSE);

		// no_capaPei
		$this->no_capaPei->SetDbValueDef($rsnew, $this->no_capaPei->CurrentValue, "", FALSE);

		// ds_introducao
		$this->ds_introducao->SetDbValueDef($rsnew, $this->ds_introducao->CurrentValue, NULL, FALSE);

		// ds_missao
		$this->ds_missao->SetDbValueDef($rsnew, $this->ds_missao->CurrentValue, NULL, FALSE);

		// ds_visao
		$this->ds_visao->SetDbValueDef($rsnew, $this->ds_visao->CurrentValue, NULL, FALSE);

		// ds_valores
		$this->ds_valores->SetDbValueDef($rsnew, $this->ds_valores->CurrentValue, NULL, FALSE);

		// ic_situacao
		$this->ic_situacao->SetDbValueDef($rsnew, $this->ic_situacao->CurrentValue, NULL, FALSE);

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
			$this->nu_pei->setDbValue($conn->Insert_ID());
			$rsnew['nu_pei'] = $this->nu_pei->DbValue;
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "peilist.php", $this->TableVar);
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
if (!isset($pei_add)) $pei_add = new cpei_add();

// Page init
$pei_add->Page_Init();

// Page main
$pei_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pei_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var pei_add = new ew_Page("pei_add");
pei_add.PageID = "add"; // Page ID
var EW_PAGE_ID = pei_add.PageID; // For backward compatibility

// Form object
var fpeiadd = new ew_Form("fpeiadd");

// Validate form
fpeiadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_periodoPei");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($pei->nu_periodoPei->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no_capaPei");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($pei->no_capaPei->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_situacao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($pei->ic_situacao->FldCaption()) ?>");

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
fpeiadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpeiadd.ValidateRequired = true;
<?php } else { ?>
fpeiadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fpeiadd.Lists["x_nu_periodoPei"] = {"LinkField":"x_nu_periodoPei","Ajax":null,"AutoFill":false,"DisplayFields":["x_nu_anoInicio","x_nu_anoFim","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $pei_add->ShowPageHeader(); ?>
<?php
$pei_add->ShowMessage();
?>
<form name="fpeiadd" id="fpeiadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="pei">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_peiadd" class="table table-bordered table-striped">
<?php if ($pei->nu_periodoPei->Visible) { // nu_periodoPei ?>
	<tr id="r_nu_periodoPei">
		<td><span id="elh_pei_nu_periodoPei"><?php echo $pei->nu_periodoPei->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $pei->nu_periodoPei->CellAttributes() ?>>
<span id="el_pei_nu_periodoPei" class="control-group">
<select data-field="x_nu_periodoPei" id="x_nu_periodoPei" name="x_nu_periodoPei"<?php echo $pei->nu_periodoPei->EditAttributes() ?>>
<?php
if (is_array($pei->nu_periodoPei->EditValue)) {
	$arwrk = $pei->nu_periodoPei->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pei->nu_periodoPei->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$pei->nu_periodoPei) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "periodopei")) { ?>
&nbsp;<a id="aol_x_nu_periodoPei" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_nu_periodoPei',url:'periodopeiaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $pei->nu_periodoPei->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
fpeiadd.Lists["x_nu_periodoPei"].Options = <?php echo (is_array($pei->nu_periodoPei->EditValue)) ? ew_ArrayToJson($pei->nu_periodoPei->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $pei->nu_periodoPei->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($pei->no_capaPei->Visible) { // no_capaPei ?>
	<tr id="r_no_capaPei">
		<td><span id="elh_pei_no_capaPei"><?php echo $pei->no_capaPei->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $pei->no_capaPei->CellAttributes() ?>>
<span id="el_pei_no_capaPei" class="control-group">
<input type="text" data-field="x_no_capaPei" name="x_no_capaPei" id="x_no_capaPei" size="30" maxlength="200" placeholder="<?php echo $pei->no_capaPei->PlaceHolder ?>" value="<?php echo $pei->no_capaPei->EditValue ?>"<?php echo $pei->no_capaPei->EditAttributes() ?>>
</span>
<?php echo $pei->no_capaPei->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($pei->ds_introducao->Visible) { // ds_introducao ?>
	<tr id="r_ds_introducao">
		<td><span id="elh_pei_ds_introducao"><?php echo $pei->ds_introducao->FldCaption() ?></span></td>
		<td<?php echo $pei->ds_introducao->CellAttributes() ?>>
<span id="el_pei_ds_introducao" class="control-group">
<textarea data-field="x_ds_introducao" name="x_ds_introducao" id="x_ds_introducao" cols="35" rows="4" placeholder="<?php echo $pei->ds_introducao->PlaceHolder ?>"<?php echo $pei->ds_introducao->EditAttributes() ?>><?php echo $pei->ds_introducao->EditValue ?></textarea>
</span>
<?php echo $pei->ds_introducao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($pei->ds_missao->Visible) { // ds_missao ?>
	<tr id="r_ds_missao">
		<td><span id="elh_pei_ds_missao"><?php echo $pei->ds_missao->FldCaption() ?></span></td>
		<td<?php echo $pei->ds_missao->CellAttributes() ?>>
<span id="el_pei_ds_missao" class="control-group">
<textarea data-field="x_ds_missao" name="x_ds_missao" id="x_ds_missao" cols="35" rows="4" placeholder="<?php echo $pei->ds_missao->PlaceHolder ?>"<?php echo $pei->ds_missao->EditAttributes() ?>><?php echo $pei->ds_missao->EditValue ?></textarea>
</span>
<?php echo $pei->ds_missao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($pei->ds_visao->Visible) { // ds_visao ?>
	<tr id="r_ds_visao">
		<td><span id="elh_pei_ds_visao"><?php echo $pei->ds_visao->FldCaption() ?></span></td>
		<td<?php echo $pei->ds_visao->CellAttributes() ?>>
<span id="el_pei_ds_visao" class="control-group">
<textarea data-field="x_ds_visao" name="x_ds_visao" id="x_ds_visao" cols="35" rows="4" placeholder="<?php echo $pei->ds_visao->PlaceHolder ?>"<?php echo $pei->ds_visao->EditAttributes() ?>><?php echo $pei->ds_visao->EditValue ?></textarea>
</span>
<?php echo $pei->ds_visao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($pei->ds_valores->Visible) { // ds_valores ?>
	<tr id="r_ds_valores">
		<td><span id="elh_pei_ds_valores"><?php echo $pei->ds_valores->FldCaption() ?></span></td>
		<td<?php echo $pei->ds_valores->CellAttributes() ?>>
<span id="el_pei_ds_valores" class="control-group">
<textarea data-field="x_ds_valores" name="x_ds_valores" id="x_ds_valores" cols="35" rows="4" placeholder="<?php echo $pei->ds_valores->PlaceHolder ?>"<?php echo $pei->ds_valores->EditAttributes() ?>><?php echo $pei->ds_valores->EditValue ?></textarea>
</span>
<?php echo $pei->ds_valores->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($pei->ic_situacao->Visible) { // ic_situacao ?>
	<tr id="r_ic_situacao">
		<td><span id="elh_pei_ic_situacao"><?php echo $pei->ic_situacao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $pei->ic_situacao->CellAttributes() ?>>
<span id="el_pei_ic_situacao" class="control-group">
<select data-field="x_ic_situacao" id="x_ic_situacao" name="x_ic_situacao"<?php echo $pei->ic_situacao->EditAttributes() ?>>
<?php
if (is_array($pei->ic_situacao->EditValue)) {
	$arwrk = $pei->ic_situacao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pei->ic_situacao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $pei->ic_situacao->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fpeiadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$pei_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$pei_add->Page_Terminate();
?>
