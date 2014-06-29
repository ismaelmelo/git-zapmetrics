<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "tpelementoinfo.php" ?>
<?php include_once "tpmanutencaoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$tpElemento_add = NULL; // Initialize page object first

class ctpElemento_add extends ctpElemento {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'tpElemento';

	// Page object name
	var $PageObjName = 'tpElemento_add';

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

		// Table object (tpElemento)
		if (!isset($GLOBALS["tpElemento"])) {
			$GLOBALS["tpElemento"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tpElemento"];
		}

		// Table object (tpmanutencao)
		if (!isset($GLOBALS['tpmanutencao'])) $GLOBALS['tpmanutencao'] = new ctpmanutencao();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tpElemento', TRUE);

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
			$this->Page_Terminate("tpelementolist.php");
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
			if (@$_GET["nu_tpElemento"] != "") {
				$this->nu_tpElemento->setQueryStringValue($_GET["nu_tpElemento"]);
				$this->setKey("nu_tpElemento", $this->nu_tpElemento->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_tpElemento", ""); // Clear key
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
					$this->Page_Terminate("tpelementolist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "tpelementoview.php")
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
		$this->nu_tpManutencao->CurrentValue = NULL;
		$this->nu_tpManutencao->OldValue = $this->nu_tpManutencao->CurrentValue;
		$this->no_tpElemento->CurrentValue = NULL;
		$this->no_tpElemento->OldValue = $this->no_tpElemento->CurrentValue;
		$this->ic_funcional->CurrentValue = "S";
		$this->ds_helpTela->CurrentValue = NULL;
		$this->ds_helpTela->OldValue = $this->ds_helpTela->CurrentValue;
		$this->ic_ativo->CurrentValue = "S";
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_tpManutencao->FldIsDetailKey) {
			$this->nu_tpManutencao->setFormValue($objForm->GetValue("x_nu_tpManutencao"));
		}
		if (!$this->no_tpElemento->FldIsDetailKey) {
			$this->no_tpElemento->setFormValue($objForm->GetValue("x_no_tpElemento"));
		}
		if (!$this->ic_funcional->FldIsDetailKey) {
			$this->ic_funcional->setFormValue($objForm->GetValue("x_ic_funcional"));
		}
		if (!$this->ds_helpTela->FldIsDetailKey) {
			$this->ds_helpTela->setFormValue($objForm->GetValue("x_ds_helpTela"));
		}
		if (!$this->ic_ativo->FldIsDetailKey) {
			$this->ic_ativo->setFormValue($objForm->GetValue("x_ic_ativo"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->nu_tpManutencao->CurrentValue = $this->nu_tpManutencao->FormValue;
		$this->no_tpElemento->CurrentValue = $this->no_tpElemento->FormValue;
		$this->ic_funcional->CurrentValue = $this->ic_funcional->FormValue;
		$this->ds_helpTela->CurrentValue = $this->ds_helpTela->FormValue;
		$this->ic_ativo->CurrentValue = $this->ic_ativo->FormValue;
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
		$this->nu_tpElemento->setDbValue($rs->fields('nu_tpElemento'));
		$this->nu_tpManutencao->setDbValue($rs->fields('nu_tpManutencao'));
		$this->no_tpElemento->setDbValue($rs->fields('no_tpElemento'));
		$this->ic_funcional->setDbValue($rs->fields('ic_funcional'));
		$this->ds_helpTela->setDbValue($rs->fields('ds_helpTela'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_tpElemento->DbValue = $row['nu_tpElemento'];
		$this->nu_tpManutencao->DbValue = $row['nu_tpManutencao'];
		$this->no_tpElemento->DbValue = $row['no_tpElemento'];
		$this->ic_funcional->DbValue = $row['ic_funcional'];
		$this->ds_helpTela->DbValue = $row['ds_helpTela'];
		$this->ic_ativo->DbValue = $row['ic_ativo'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_tpElemento")) <> "")
			$this->nu_tpElemento->CurrentValue = $this->getKey("nu_tpElemento"); // nu_tpElemento
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
		// nu_tpElemento
		// nu_tpManutencao
		// no_tpElemento
		// ic_funcional
		// ds_helpTela
		// ic_ativo

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_tpElemento
			$this->nu_tpElemento->ViewValue = $this->nu_tpElemento->CurrentValue;
			$this->nu_tpElemento->ViewCustomAttributes = "";

			// nu_tpManutencao
			if (strval($this->nu_tpManutencao->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpManutencao]" . ew_SearchString("=", $this->nu_tpManutencao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpManutencao], [no_tpManutencao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpmanutencao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpManutencao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpManutencao] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpManutencao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpManutencao->ViewValue = $this->nu_tpManutencao->CurrentValue;
				}
			} else {
				$this->nu_tpManutencao->ViewValue = NULL;
			}
			$this->nu_tpManutencao->ViewCustomAttributes = "";

			// no_tpElemento
			$this->no_tpElemento->ViewValue = $this->no_tpElemento->CurrentValue;
			$this->no_tpElemento->ViewCustomAttributes = "";

			// ic_funcional
			if (strval($this->ic_funcional->CurrentValue) <> "") {
				switch ($this->ic_funcional->CurrentValue) {
					case $this->ic_funcional->FldTagValue(1):
						$this->ic_funcional->ViewValue = $this->ic_funcional->FldTagCaption(1) <> "" ? $this->ic_funcional->FldTagCaption(1) : $this->ic_funcional->CurrentValue;
						break;
					case $this->ic_funcional->FldTagValue(2):
						$this->ic_funcional->ViewValue = $this->ic_funcional->FldTagCaption(2) <> "" ? $this->ic_funcional->FldTagCaption(2) : $this->ic_funcional->CurrentValue;
						break;
					default:
						$this->ic_funcional->ViewValue = $this->ic_funcional->CurrentValue;
				}
			} else {
				$this->ic_funcional->ViewValue = NULL;
			}
			$this->ic_funcional->ViewCustomAttributes = "";

			// ds_helpTela
			$this->ds_helpTela->ViewValue = $this->ds_helpTela->CurrentValue;
			$this->ds_helpTela->ViewCustomAttributes = "";

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

			// nu_tpManutencao
			$this->nu_tpManutencao->LinkCustomAttributes = "";
			$this->nu_tpManutencao->HrefValue = "";
			$this->nu_tpManutencao->TooltipValue = "";

			// no_tpElemento
			$this->no_tpElemento->LinkCustomAttributes = "";
			$this->no_tpElemento->HrefValue = "";
			$this->no_tpElemento->TooltipValue = "";

			// ic_funcional
			$this->ic_funcional->LinkCustomAttributes = "";
			$this->ic_funcional->HrefValue = "";
			$this->ic_funcional->TooltipValue = "";

			// ds_helpTela
			$this->ds_helpTela->LinkCustomAttributes = "";
			$this->ds_helpTela->HrefValue = "";
			$this->ds_helpTela->TooltipValue = "";

			// ic_ativo
			$this->ic_ativo->LinkCustomAttributes = "";
			$this->ic_ativo->HrefValue = "";
			$this->ic_ativo->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nu_tpManutencao
			$this->nu_tpManutencao->EditCustomAttributes = "";
			if ($this->nu_tpManutencao->getSessionValue() <> "") {
				$this->nu_tpManutencao->CurrentValue = $this->nu_tpManutencao->getSessionValue();
			if (strval($this->nu_tpManutencao->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpManutencao]" . ew_SearchString("=", $this->nu_tpManutencao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpManutencao], [no_tpManutencao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpmanutencao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpManutencao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpManutencao] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpManutencao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpManutencao->ViewValue = $this->nu_tpManutencao->CurrentValue;
				}
			} else {
				$this->nu_tpManutencao->ViewValue = NULL;
			}
			$this->nu_tpManutencao->ViewCustomAttributes = "";
			} else {
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_tpManutencao], [no_tpManutencao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[tpmanutencao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpManutencao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpManutencao] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_tpManutencao->EditValue = $arwrk;
			}

			// no_tpElemento
			$this->no_tpElemento->EditCustomAttributes = "";
			$this->no_tpElemento->EditValue = ew_HtmlEncode($this->no_tpElemento->CurrentValue);
			$this->no_tpElemento->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_tpElemento->FldCaption()));

			// ic_funcional
			$this->ic_funcional->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_funcional->FldTagValue(1), $this->ic_funcional->FldTagCaption(1) <> "" ? $this->ic_funcional->FldTagCaption(1) : $this->ic_funcional->FldTagValue(1));
			$arwrk[] = array($this->ic_funcional->FldTagValue(2), $this->ic_funcional->FldTagCaption(2) <> "" ? $this->ic_funcional->FldTagCaption(2) : $this->ic_funcional->FldTagValue(2));
			$this->ic_funcional->EditValue = $arwrk;

			// ds_helpTela
			$this->ds_helpTela->EditCustomAttributes = "";
			$this->ds_helpTela->EditValue = $this->ds_helpTela->CurrentValue;
			$this->ds_helpTela->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_helpTela->FldCaption()));

			// ic_ativo
			$this->ic_ativo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_ativo->FldTagValue(1), $this->ic_ativo->FldTagCaption(1) <> "" ? $this->ic_ativo->FldTagCaption(1) : $this->ic_ativo->FldTagValue(1));
			$arwrk[] = array($this->ic_ativo->FldTagValue(2), $this->ic_ativo->FldTagCaption(2) <> "" ? $this->ic_ativo->FldTagCaption(2) : $this->ic_ativo->FldTagValue(2));
			$this->ic_ativo->EditValue = $arwrk;

			// Edit refer script
			// nu_tpManutencao

			$this->nu_tpManutencao->HrefValue = "";

			// no_tpElemento
			$this->no_tpElemento->HrefValue = "";

			// ic_funcional
			$this->ic_funcional->HrefValue = "";

			// ds_helpTela
			$this->ds_helpTela->HrefValue = "";

			// ic_ativo
			$this->ic_ativo->HrefValue = "";
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
		if (!$this->nu_tpManutencao->FldIsDetailKey && !is_null($this->nu_tpManutencao->FormValue) && $this->nu_tpManutencao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_tpManutencao->FldCaption());
		}
		if (!$this->no_tpElemento->FldIsDetailKey && !is_null($this->no_tpElemento->FormValue) && $this->no_tpElemento->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_tpElemento->FldCaption());
		}
		if ($this->ic_funcional->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_funcional->FldCaption());
		}
		if ($this->ic_ativo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_ativo->FldCaption());
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

		// nu_tpManutencao
		$this->nu_tpManutencao->SetDbValueDef($rsnew, $this->nu_tpManutencao->CurrentValue, NULL, FALSE);

		// no_tpElemento
		$this->no_tpElemento->SetDbValueDef($rsnew, $this->no_tpElemento->CurrentValue, NULL, FALSE);

		// ic_funcional
		$this->ic_funcional->SetDbValueDef($rsnew, $this->ic_funcional->CurrentValue, NULL, FALSE);

		// ds_helpTela
		$this->ds_helpTela->SetDbValueDef($rsnew, $this->ds_helpTela->CurrentValue, NULL, FALSE);

		// ic_ativo
		$this->ic_ativo->SetDbValueDef($rsnew, $this->ic_ativo->CurrentValue, NULL, FALSE);

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
			$this->nu_tpElemento->setDbValue($conn->Insert_ID());
			$rsnew['nu_tpElemento'] = $this->nu_tpElemento->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
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
			if ($sMasterTblVar == "tpmanutencao") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_tpManutencao"] <> "") {
					$GLOBALS["tpmanutencao"]->nu_tpManutencao->setQueryStringValue($_GET["nu_tpManutencao"]);
					$this->nu_tpManutencao->setQueryStringValue($GLOBALS["tpmanutencao"]->nu_tpManutencao->QueryStringValue);
					$this->nu_tpManutencao->setSessionValue($this->nu_tpManutencao->QueryStringValue);
					if (!is_numeric($GLOBALS["tpmanutencao"]->nu_tpManutencao->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "tpmanutencao") {
				if ($this->nu_tpManutencao->QueryStringValue == "") $this->nu_tpManutencao->setSessionValue("");
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "tpelementolist.php", $this->TableVar);
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
if (!isset($tpElemento_add)) $tpElemento_add = new ctpElemento_add();

// Page init
$tpElemento_add->Page_Init();

// Page main
$tpElemento_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tpElemento_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var tpElemento_add = new ew_Page("tpElemento_add");
tpElemento_add.PageID = "add"; // Page ID
var EW_PAGE_ID = tpElemento_add.PageID; // For backward compatibility

// Form object
var ftpElementoadd = new ew_Form("ftpElementoadd");

// Validate form
ftpElementoadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_tpManutencao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tpElemento->nu_tpManutencao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no_tpElemento");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tpElemento->no_tpElemento->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_funcional");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tpElemento->ic_funcional->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_ativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tpElemento->ic_ativo->FldCaption()) ?>");

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
ftpElementoadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftpElementoadd.ValidateRequired = true;
<?php } else { ?>
ftpElementoadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftpElementoadd.Lists["x_nu_tpManutencao"] = {"LinkField":"x_nu_tpManutencao","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpManutencao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $tpElemento_add->ShowPageHeader(); ?>
<?php
$tpElemento_add->ShowMessage();
?>
<form name="ftpElementoadd" id="ftpElementoadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="tpElemento">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_tpElementoadd" class="table table-bordered table-striped">
<?php if ($tpElemento->nu_tpManutencao->Visible) { // nu_tpManutencao ?>
	<tr id="r_nu_tpManutencao">
		<td><span id="elh_tpElemento_nu_tpManutencao"><?php echo $tpElemento->nu_tpManutencao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tpElemento->nu_tpManutencao->CellAttributes() ?>>
<?php if ($tpElemento->nu_tpManutencao->getSessionValue() <> "") { ?>
<span<?php echo $tpElemento->nu_tpManutencao->ViewAttributes() ?>>
<?php echo $tpElemento->nu_tpManutencao->ViewValue ?></span>
<input type="hidden" id="x_nu_tpManutencao" name="x_nu_tpManutencao" value="<?php echo ew_HtmlEncode($tpElemento->nu_tpManutencao->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_nu_tpManutencao" id="x_nu_tpManutencao" name="x_nu_tpManutencao"<?php echo $tpElemento->nu_tpManutencao->EditAttributes() ?>>
<?php
if (is_array($tpElemento->nu_tpManutencao->EditValue)) {
	$arwrk = $tpElemento->nu_tpManutencao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tpElemento->nu_tpManutencao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
ftpElementoadd.Lists["x_nu_tpManutencao"].Options = <?php echo (is_array($tpElemento->nu_tpManutencao->EditValue)) ? ew_ArrayToJson($tpElemento->nu_tpManutencao->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<?php echo $tpElemento->nu_tpManutencao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tpElemento->no_tpElemento->Visible) { // no_tpElemento ?>
	<tr id="r_no_tpElemento">
		<td><span id="elh_tpElemento_no_tpElemento"><?php echo $tpElemento->no_tpElemento->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tpElemento->no_tpElemento->CellAttributes() ?>>
<span id="el_tpElemento_no_tpElemento" class="control-group">
<input type="text" data-field="x_no_tpElemento" name="x_no_tpElemento" id="x_no_tpElemento" size="30" maxlength="50" placeholder="<?php echo $tpElemento->no_tpElemento->PlaceHolder ?>" value="<?php echo $tpElemento->no_tpElemento->EditValue ?>"<?php echo $tpElemento->no_tpElemento->EditAttributes() ?>>
</span>
<?php echo $tpElemento->no_tpElemento->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tpElemento->ic_funcional->Visible) { // ic_funcional ?>
	<tr id="r_ic_funcional">
		<td><span id="elh_tpElemento_ic_funcional"><?php echo $tpElemento->ic_funcional->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tpElemento->ic_funcional->CellAttributes() ?>>
<span id="el_tpElemento_ic_funcional" class="control-group">
<div id="tp_x_ic_funcional" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_funcional" id="x_ic_funcional" value="{value}"<?php echo $tpElemento->ic_funcional->EditAttributes() ?>></div>
<div id="dsl_x_ic_funcional" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $tpElemento->ic_funcional->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tpElemento->ic_funcional->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_funcional" name="x_ic_funcional" id="x_ic_funcional_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $tpElemento->ic_funcional->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $tpElemento->ic_funcional->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tpElemento->ds_helpTela->Visible) { // ds_helpTela ?>
	<tr id="r_ds_helpTela">
		<td><span id="elh_tpElemento_ds_helpTela"><?php echo $tpElemento->ds_helpTela->FldCaption() ?></span></td>
		<td<?php echo $tpElemento->ds_helpTela->CellAttributes() ?>>
<span id="el_tpElemento_ds_helpTela" class="control-group">
<textarea data-field="x_ds_helpTela" name="x_ds_helpTela" id="x_ds_helpTela" cols="35" rows="4" placeholder="<?php echo $tpElemento->ds_helpTela->PlaceHolder ?>"<?php echo $tpElemento->ds_helpTela->EditAttributes() ?>><?php echo $tpElemento->ds_helpTela->EditValue ?></textarea>
</span>
<?php echo $tpElemento->ds_helpTela->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tpElemento->ic_ativo->Visible) { // ic_ativo ?>
	<tr id="r_ic_ativo">
		<td><span id="elh_tpElemento_ic_ativo"><?php echo $tpElemento->ic_ativo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tpElemento->ic_ativo->CellAttributes() ?>>
<span id="el_tpElemento_ic_ativo" class="control-group">
<div id="tp_x_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_ativo" id="x_ic_ativo" value="{value}"<?php echo $tpElemento->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $tpElemento->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tpElemento->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x_ic_ativo" id="x_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $tpElemento->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $tpElemento->ic_ativo->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
ftpElementoadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$tpElemento_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tpElemento_add->Page_Terminate();
?>
