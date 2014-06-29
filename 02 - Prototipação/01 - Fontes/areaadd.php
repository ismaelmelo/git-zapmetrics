<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "areainfo.php" ?>
<?php include_once "organizacaoinfo.php" ?>
<?php include_once "tpareainfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "divisaogridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$area_add = NULL; // Initialize page object first

class carea_add extends carea {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'area';

	// Page object name
	var $PageObjName = 'area_add';

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

		// Table object (area)
		if (!isset($GLOBALS["area"])) {
			$GLOBALS["area"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["area"];
		}

		// Table object (organizacao)
		if (!isset($GLOBALS['organizacao'])) $GLOBALS['organizacao'] = new corganizacao();

		// Table object (tparea)
		if (!isset($GLOBALS['tparea'])) $GLOBALS['tparea'] = new ctparea();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'area', TRUE);

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
			$this->Page_Terminate("arealist.php");
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
			if (@$_GET["nu_area"] != "") {
				$this->nu_area->setQueryStringValue($_GET["nu_area"]);
				$this->setKey("nu_area", $this->nu_area->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_area", ""); // Clear key
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
					$this->Page_Terminate("arealist.php"); // No matching record, return to list
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
					if (ew_GetPageName($sReturnUrl) == "areaview.php")
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
		$this->nu_organizacao->CurrentValue = NULL;
		$this->nu_organizacao->OldValue = $this->nu_organizacao->CurrentValue;
		$this->no_area->CurrentValue = NULL;
		$this->no_area->OldValue = $this->no_area->CurrentValue;
		$this->ds_area->CurrentValue = NULL;
		$this->ds_area->OldValue = $this->ds_area->CurrentValue;
		$this->nu_tpArea->CurrentValue = NULL;
		$this->nu_tpArea->OldValue = $this->nu_tpArea->CurrentValue;
		$this->nu_pessoaResp->CurrentValue = NULL;
		$this->nu_pessoaResp->OldValue = $this->nu_pessoaResp->CurrentValue;
		$this->ic_ativo->CurrentValue = "S";
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_organizacao->FldIsDetailKey) {
			$this->nu_organizacao->setFormValue($objForm->GetValue("x_nu_organizacao"));
		}
		if (!$this->no_area->FldIsDetailKey) {
			$this->no_area->setFormValue($objForm->GetValue("x_no_area"));
		}
		if (!$this->ds_area->FldIsDetailKey) {
			$this->ds_area->setFormValue($objForm->GetValue("x_ds_area"));
		}
		if (!$this->nu_tpArea->FldIsDetailKey) {
			$this->nu_tpArea->setFormValue($objForm->GetValue("x_nu_tpArea"));
		}
		if (!$this->nu_pessoaResp->FldIsDetailKey) {
			$this->nu_pessoaResp->setFormValue($objForm->GetValue("x_nu_pessoaResp"));
		}
		if (!$this->ic_ativo->FldIsDetailKey) {
			$this->ic_ativo->setFormValue($objForm->GetValue("x_ic_ativo"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->nu_organizacao->CurrentValue = $this->nu_organizacao->FormValue;
		$this->no_area->CurrentValue = $this->no_area->FormValue;
		$this->ds_area->CurrentValue = $this->ds_area->FormValue;
		$this->nu_tpArea->CurrentValue = $this->nu_tpArea->FormValue;
		$this->nu_pessoaResp->CurrentValue = $this->nu_pessoaResp->FormValue;
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
		$this->nu_area->setDbValue($rs->fields('nu_area'));
		$this->nu_organizacao->setDbValue($rs->fields('nu_organizacao'));
		$this->no_area->setDbValue($rs->fields('no_area'));
		$this->ds_area->setDbValue($rs->fields('ds_area'));
		$this->nu_tpArea->setDbValue($rs->fields('nu_tpArea'));
		$this->nu_pessoaResp->setDbValue($rs->fields('nu_pessoaResp'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_area->DbValue = $row['nu_area'];
		$this->nu_organizacao->DbValue = $row['nu_organizacao'];
		$this->no_area->DbValue = $row['no_area'];
		$this->ds_area->DbValue = $row['ds_area'];
		$this->nu_tpArea->DbValue = $row['nu_tpArea'];
		$this->nu_pessoaResp->DbValue = $row['nu_pessoaResp'];
		$this->ic_ativo->DbValue = $row['ic_ativo'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_area")) <> "")
			$this->nu_area->CurrentValue = $this->getKey("nu_area"); // nu_area
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
		// nu_area
		// nu_organizacao
		// no_area
		// ds_area
		// nu_tpArea
		// nu_pessoaResp
		// ic_ativo

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_organizacao
			if (strval($this->nu_organizacao->CurrentValue) <> "") {
				$sFilterWrk = "[nu_organizacao]" . ew_SearchString("=", $this->nu_organizacao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_organizacao], [no_organizacao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[organizacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_organizacao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_organizacao] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_organizacao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_organizacao->ViewValue = $this->nu_organizacao->CurrentValue;
				}
			} else {
				$this->nu_organizacao->ViewValue = NULL;
			}
			$this->nu_organizacao->ViewCustomAttributes = "";

			// no_area
			$this->no_area->ViewValue = $this->no_area->CurrentValue;
			$this->no_area->ViewCustomAttributes = "";

			// ds_area
			$this->ds_area->ViewValue = $this->ds_area->CurrentValue;
			$this->ds_area->ViewCustomAttributes = "";

			// nu_tpArea
			if (strval($this->nu_tpArea->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpArea]" . ew_SearchString("=", $this->nu_tpArea->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpArea], [no_tpArea] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tparea]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpArea, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpArea] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpArea->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpArea->ViewValue = $this->nu_tpArea->CurrentValue;
				}
			} else {
				$this->nu_tpArea->ViewValue = NULL;
			}
			$this->nu_tpArea->ViewCustomAttributes = "";

			// nu_pessoaResp
			if (strval($this->nu_pessoaResp->CurrentValue) <> "") {
				$sFilterWrk = "[nu_pessoa]" . ew_SearchString("=", $this->nu_pessoaResp->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_pessoa], [no_pessoa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[pessoa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_pessoaResp, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_pessoa] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_pessoaResp->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_pessoaResp->ViewValue = $this->nu_pessoaResp->CurrentValue;
				}
			} else {
				$this->nu_pessoaResp->ViewValue = NULL;
			}
			$this->nu_pessoaResp->ViewCustomAttributes = "";

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

			// nu_organizacao
			$this->nu_organizacao->LinkCustomAttributes = "";
			$this->nu_organizacao->HrefValue = "";
			$this->nu_organizacao->TooltipValue = "";

			// no_area
			$this->no_area->LinkCustomAttributes = "";
			$this->no_area->HrefValue = "";
			$this->no_area->TooltipValue = "";

			// ds_area
			$this->ds_area->LinkCustomAttributes = "";
			$this->ds_area->HrefValue = "";
			$this->ds_area->TooltipValue = "";

			// nu_tpArea
			$this->nu_tpArea->LinkCustomAttributes = "";
			$this->nu_tpArea->HrefValue = "";
			$this->nu_tpArea->TooltipValue = "";

			// nu_pessoaResp
			$this->nu_pessoaResp->LinkCustomAttributes = "";
			$this->nu_pessoaResp->HrefValue = "";
			$this->nu_pessoaResp->TooltipValue = "";

			// ic_ativo
			$this->ic_ativo->LinkCustomAttributes = "";
			$this->ic_ativo->HrefValue = "";
			$this->ic_ativo->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nu_organizacao
			$this->nu_organizacao->EditCustomAttributes = "";
			if ($this->nu_organizacao->getSessionValue() <> "") {
				$this->nu_organizacao->CurrentValue = $this->nu_organizacao->getSessionValue();
			if (strval($this->nu_organizacao->CurrentValue) <> "") {
				$sFilterWrk = "[nu_organizacao]" . ew_SearchString("=", $this->nu_organizacao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_organizacao], [no_organizacao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[organizacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_organizacao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_organizacao] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_organizacao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_organizacao->ViewValue = $this->nu_organizacao->CurrentValue;
				}
			} else {
				$this->nu_organizacao->ViewValue = NULL;
			}
			$this->nu_organizacao->ViewCustomAttributes = "";
			} else {
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_organizacao], [no_organizacao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[organizacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_organizacao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_organizacao] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_organizacao->EditValue = $arwrk;
			}

			// no_area
			$this->no_area->EditCustomAttributes = "";
			$this->no_area->EditValue = ew_HtmlEncode($this->no_area->CurrentValue);
			$this->no_area->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_area->FldCaption()));

			// ds_area
			$this->ds_area->EditCustomAttributes = "";
			$this->ds_area->EditValue = $this->ds_area->CurrentValue;
			$this->ds_area->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_area->FldCaption()));

			// nu_tpArea
			$this->nu_tpArea->EditCustomAttributes = "";
			if ($this->nu_tpArea->getSessionValue() <> "") {
				$this->nu_tpArea->CurrentValue = $this->nu_tpArea->getSessionValue();
			if (strval($this->nu_tpArea->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpArea]" . ew_SearchString("=", $this->nu_tpArea->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpArea], [no_tpArea] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tparea]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpArea, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpArea] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpArea->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpArea->ViewValue = $this->nu_tpArea->CurrentValue;
				}
			} else {
				$this->nu_tpArea->ViewValue = NULL;
			}
			$this->nu_tpArea->ViewCustomAttributes = "";
			} else {
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_tpArea], [no_tpArea] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[tparea]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpArea, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpArea] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_tpArea->EditValue = $arwrk;
			}

			// nu_pessoaResp
			$this->nu_pessoaResp->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_pessoa], [no_pessoa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[pessoa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_pessoaResp, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_pessoa] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_pessoaResp->EditValue = $arwrk;

			// ic_ativo
			$this->ic_ativo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_ativo->FldTagValue(1), $this->ic_ativo->FldTagCaption(1) <> "" ? $this->ic_ativo->FldTagCaption(1) : $this->ic_ativo->FldTagValue(1));
			$arwrk[] = array($this->ic_ativo->FldTagValue(2), $this->ic_ativo->FldTagCaption(2) <> "" ? $this->ic_ativo->FldTagCaption(2) : $this->ic_ativo->FldTagValue(2));
			$this->ic_ativo->EditValue = $arwrk;

			// Edit refer script
			// nu_organizacao

			$this->nu_organizacao->HrefValue = "";

			// no_area
			$this->no_area->HrefValue = "";

			// ds_area
			$this->ds_area->HrefValue = "";

			// nu_tpArea
			$this->nu_tpArea->HrefValue = "";

			// nu_pessoaResp
			$this->nu_pessoaResp->HrefValue = "";

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
		if (!$this->nu_organizacao->FldIsDetailKey && !is_null($this->nu_organizacao->FormValue) && $this->nu_organizacao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_organizacao->FldCaption());
		}
		if (!$this->no_area->FldIsDetailKey && !is_null($this->no_area->FormValue) && $this->no_area->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_area->FldCaption());
		}
		if (!$this->nu_tpArea->FldIsDetailKey && !is_null($this->nu_tpArea->FormValue) && $this->nu_tpArea->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_tpArea->FldCaption());
		}
		if ($this->ic_ativo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_ativo->FldCaption());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("divisao", $DetailTblVar) && $GLOBALS["divisao"]->DetailAdd) {
			if (!isset($GLOBALS["divisao_grid"])) $GLOBALS["divisao_grid"] = new cdivisao_grid(); // get detail page object
			$GLOBALS["divisao_grid"]->ValidateGridForm();
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

		// Check referential integrity for master table 'tparea'
		$bValidMasterRecord = TRUE;
		$sMasterFilter = $this->SqlMasterFilter_tparea();
		if (strval($this->nu_tpArea->CurrentValue) <> "") {
			$sMasterFilter = str_replace("@nu_tpArea@", ew_AdjustSql($this->nu_tpArea->CurrentValue), $sMasterFilter);
		} else {
			$bValidMasterRecord = FALSE;
		}
		if ($bValidMasterRecord) {
			$rsmaster = $GLOBALS["tparea"]->LoadRs($sMasterFilter);
			$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
			$rsmaster->Close();
		}
		if (!$bValidMasterRecord) {
			$sRelatedRecordMsg = str_replace("%t", "tparea", $Language->Phrase("RelatedRecordRequired"));
			$this->setFailureMessage($sRelatedRecordMsg);
			return FALSE;
		}

		// Check referential integrity for master table 'organizacao'
		$bValidMasterRecord = TRUE;
		$sMasterFilter = $this->SqlMasterFilter_organizacao();
		if (strval($this->nu_organizacao->CurrentValue) <> "") {
			$sMasterFilter = str_replace("@nu_organizacao@", ew_AdjustSql($this->nu_organizacao->CurrentValue), $sMasterFilter);
		} else {
			$bValidMasterRecord = FALSE;
		}
		if ($bValidMasterRecord) {
			$rsmaster = $GLOBALS["organizacao"]->LoadRs($sMasterFilter);
			$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
			$rsmaster->Close();
		}
		if (!$bValidMasterRecord) {
			$sRelatedRecordMsg = str_replace("%t", "organizacao", $Language->Phrase("RelatedRecordRequired"));
			$this->setFailureMessage($sRelatedRecordMsg);
			return FALSE;
		}

		// Begin transaction
		if ($this->getCurrentDetailTable() <> "")
			$conn->BeginTrans();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// nu_organizacao
		$this->nu_organizacao->SetDbValueDef($rsnew, $this->nu_organizacao->CurrentValue, 0, FALSE);

		// no_area
		$this->no_area->SetDbValueDef($rsnew, $this->no_area->CurrentValue, "", FALSE);

		// ds_area
		$this->ds_area->SetDbValueDef($rsnew, $this->ds_area->CurrentValue, NULL, FALSE);

		// nu_tpArea
		$this->nu_tpArea->SetDbValueDef($rsnew, $this->nu_tpArea->CurrentValue, 0, FALSE);

		// nu_pessoaResp
		$this->nu_pessoaResp->SetDbValueDef($rsnew, $this->nu_pessoaResp->CurrentValue, NULL, FALSE);

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
			$this->nu_area->setDbValue($conn->Insert_ID());
			$rsnew['nu_area'] = $this->nu_area->DbValue;
		}

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("divisao", $DetailTblVar) && $GLOBALS["divisao"]->DetailAdd) {
				$GLOBALS["divisao"]->nu_area->setSessionValue($this->nu_area->CurrentValue); // Set master key
				if (!isset($GLOBALS["divisao_grid"])) $GLOBALS["divisao_grid"] = new cdivisao_grid(); // Get detail page object
				$AddRow = $GLOBALS["divisao_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["divisao"]->nu_area->setSessionValue(""); // Clear master key if insert failed
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
			if ($sMasterTblVar == "tparea") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_tpArea"] <> "") {
					$GLOBALS["tparea"]->nu_tpArea->setQueryStringValue($_GET["nu_tpArea"]);
					$this->nu_tpArea->setQueryStringValue($GLOBALS["tparea"]->nu_tpArea->QueryStringValue);
					$this->nu_tpArea->setSessionValue($this->nu_tpArea->QueryStringValue);
					if (!is_numeric($GLOBALS["tparea"]->nu_tpArea->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
			if ($sMasterTblVar == "organizacao") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_organizacao"] <> "") {
					$GLOBALS["organizacao"]->nu_organizacao->setQueryStringValue($_GET["nu_organizacao"]);
					$this->nu_organizacao->setQueryStringValue($GLOBALS["organizacao"]->nu_organizacao->QueryStringValue);
					$this->nu_organizacao->setSessionValue($this->nu_organizacao->QueryStringValue);
					if (!is_numeric($GLOBALS["organizacao"]->nu_organizacao->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "tparea") {
				if ($this->nu_tpArea->QueryStringValue == "") $this->nu_tpArea->setSessionValue("");
			}
			if ($sMasterTblVar <> "organizacao") {
				if ($this->nu_organizacao->QueryStringValue == "") $this->nu_organizacao->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); //  Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
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
			if (in_array("divisao", $DetailTblVar)) {
				if (!isset($GLOBALS["divisao_grid"]))
					$GLOBALS["divisao_grid"] = new cdivisao_grid;
				if ($GLOBALS["divisao_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["divisao_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["divisao_grid"]->CurrentMode = "add";
					$GLOBALS["divisao_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["divisao_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["divisao_grid"]->setStartRecordNumber(1);
					$GLOBALS["divisao_grid"]->nu_area->FldIsDetailKey = TRUE;
					$GLOBALS["divisao_grid"]->nu_area->CurrentValue = $this->nu_area->CurrentValue;
					$GLOBALS["divisao_grid"]->nu_area->setSessionValue($GLOBALS["divisao_grid"]->nu_area->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "arealist.php", $this->TableVar);
		$PageCaption = ($this->CurrentAction == "C") ? $Language->Phrase("Copy") : $Language->Phrase("Add");
		$Breadcrumb->Add("add", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'area';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'area';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_area'];

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
if (!isset($area_add)) $area_add = new carea_add();

// Page init
$area_add->Page_Init();

// Page main
$area_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$area_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var area_add = new ew_Page("area_add");
area_add.PageID = "add"; // Page ID
var EW_PAGE_ID = area_add.PageID; // For backward compatibility

// Form object
var fareaadd = new ew_Form("fareaadd");

// Validate form
fareaadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_organizacao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($area->nu_organizacao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no_area");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($area->no_area->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_tpArea");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($area->nu_tpArea->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_ativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($area->ic_ativo->FldCaption()) ?>");

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
fareaadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fareaadd.ValidateRequired = true;
<?php } else { ?>
fareaadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fareaadd.Lists["x_nu_organizacao"] = {"LinkField":"x_nu_organizacao","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_organizacao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fareaadd.Lists["x_nu_tpArea"] = {"LinkField":"x_nu_tpArea","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpArea","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fareaadd.Lists["x_nu_pessoaResp"] = {"LinkField":"x_nu_pessoa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_pessoa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $area_add->ShowPageHeader(); ?>
<?php
$area_add->ShowMessage();
?>
<form name="fareaadd" id="fareaadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="area">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_areaadd" class="table table-bordered table-striped">
<?php if ($area->nu_organizacao->Visible) { // nu_organizacao ?>
	<tr id="r_nu_organizacao">
		<td><span id="elh_area_nu_organizacao"><?php echo $area->nu_organizacao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $area->nu_organizacao->CellAttributes() ?>>
<?php if ($area->nu_organizacao->getSessionValue() <> "") { ?>
<span<?php echo $area->nu_organizacao->ViewAttributes() ?>>
<?php echo $area->nu_organizacao->ViewValue ?></span>
<input type="hidden" id="x_nu_organizacao" name="x_nu_organizacao" value="<?php echo ew_HtmlEncode($area->nu_organizacao->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_nu_organizacao" id="x_nu_organizacao" name="x_nu_organizacao"<?php echo $area->nu_organizacao->EditAttributes() ?>>
<?php
if (is_array($area->nu_organizacao->EditValue)) {
	$arwrk = $area->nu_organizacao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($area->nu_organizacao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fareaadd.Lists["x_nu_organizacao"].Options = <?php echo (is_array($area->nu_organizacao->EditValue)) ? ew_ArrayToJson($area->nu_organizacao->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<?php echo $area->nu_organizacao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($area->no_area->Visible) { // no_area ?>
	<tr id="r_no_area">
		<td><span id="elh_area_no_area"><?php echo $area->no_area->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $area->no_area->CellAttributes() ?>>
<span id="el_area_no_area" class="control-group">
<input type="text" data-field="x_no_area" name="x_no_area" id="x_no_area" size="30" maxlength="100" placeholder="<?php echo $area->no_area->PlaceHolder ?>" value="<?php echo $area->no_area->EditValue ?>"<?php echo $area->no_area->EditAttributes() ?>>
</span>
<?php echo $area->no_area->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($area->ds_area->Visible) { // ds_area ?>
	<tr id="r_ds_area">
		<td><span id="elh_area_ds_area"><?php echo $area->ds_area->FldCaption() ?></span></td>
		<td<?php echo $area->ds_area->CellAttributes() ?>>
<span id="el_area_ds_area" class="control-group">
<textarea data-field="x_ds_area" name="x_ds_area" id="x_ds_area" cols="35" rows="4" placeholder="<?php echo $area->ds_area->PlaceHolder ?>"<?php echo $area->ds_area->EditAttributes() ?>><?php echo $area->ds_area->EditValue ?></textarea>
</span>
<?php echo $area->ds_area->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($area->nu_tpArea->Visible) { // nu_tpArea ?>
	<tr id="r_nu_tpArea">
		<td><span id="elh_area_nu_tpArea"><?php echo $area->nu_tpArea->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $area->nu_tpArea->CellAttributes() ?>>
<?php if ($area->nu_tpArea->getSessionValue() <> "") { ?>
<span<?php echo $area->nu_tpArea->ViewAttributes() ?>>
<?php echo $area->nu_tpArea->ViewValue ?></span>
<input type="hidden" id="x_nu_tpArea" name="x_nu_tpArea" value="<?php echo ew_HtmlEncode($area->nu_tpArea->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_nu_tpArea" id="x_nu_tpArea" name="x_nu_tpArea"<?php echo $area->nu_tpArea->EditAttributes() ?>>
<?php
if (is_array($area->nu_tpArea->EditValue)) {
	$arwrk = $area->nu_tpArea->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($area->nu_tpArea->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if (AllowAdd(CurrentProjectID() . "tparea")) { ?>
&nbsp;<a id="aol_x_nu_tpArea" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_nu_tpArea',url:'tpareaaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $area->nu_tpArea->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
fareaadd.Lists["x_nu_tpArea"].Options = <?php echo (is_array($area->nu_tpArea->EditValue)) ? ew_ArrayToJson($area->nu_tpArea->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<?php echo $area->nu_tpArea->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($area->nu_pessoaResp->Visible) { // nu_pessoaResp ?>
	<tr id="r_nu_pessoaResp">
		<td><span id="elh_area_nu_pessoaResp"><?php echo $area->nu_pessoaResp->FldCaption() ?></span></td>
		<td<?php echo $area->nu_pessoaResp->CellAttributes() ?>>
<span id="el_area_nu_pessoaResp" class="control-group">
<select data-field="x_nu_pessoaResp" id="x_nu_pessoaResp" name="x_nu_pessoaResp"<?php echo $area->nu_pessoaResp->EditAttributes() ?>>
<?php
if (is_array($area->nu_pessoaResp->EditValue)) {
	$arwrk = $area->nu_pessoaResp->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($area->nu_pessoaResp->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fareaadd.Lists["x_nu_pessoaResp"].Options = <?php echo (is_array($area->nu_pessoaResp->EditValue)) ? ew_ArrayToJson($area->nu_pessoaResp->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $area->nu_pessoaResp->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($area->ic_ativo->Visible) { // ic_ativo ?>
	<tr id="r_ic_ativo">
		<td><span id="elh_area_ic_ativo"><?php echo $area->ic_ativo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $area->ic_ativo->CellAttributes() ?>>
<span id="el_area_ic_ativo" class="control-group">
<div id="tp_x_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_ativo" id="x_ic_ativo" value="{value}"<?php echo $area->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $area->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($area->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x_ic_ativo" id="x_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $area->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $area->ic_ativo->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<?php
	if (in_array("divisao", explode(",", $area->getCurrentDetailTable())) && $divisao->DetailAdd) {
?>
<?php include_once "divisaogrid.php" ?>
<?php } ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fareaadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$area_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$area_add->Page_Terminate();
?>
