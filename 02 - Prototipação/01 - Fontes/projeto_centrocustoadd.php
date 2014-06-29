<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "projeto_centrocustoinfo.php" ?>
<?php include_once "centrocustoinfo.php" ?>
<?php include_once "projetoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$projeto_centrocusto_add = NULL; // Initialize page object first

class cprojeto_centrocusto_add extends cprojeto_centrocusto {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'projeto_centrocusto';

	// Page object name
	var $PageObjName = 'projeto_centrocusto_add';

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

		// Table object (projeto_centrocusto)
		if (!isset($GLOBALS["projeto_centrocusto"])) {
			$GLOBALS["projeto_centrocusto"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["projeto_centrocusto"];
		}

		// Table object (centrocusto)
		if (!isset($GLOBALS['centrocusto'])) $GLOBALS['centrocusto'] = new ccentrocusto();

		// Table object (projeto)
		if (!isset($GLOBALS['projeto'])) $GLOBALS['projeto'] = new cprojeto();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'projeto_centrocusto', TRUE);

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
			$this->Page_Terminate("projeto_centrocustolist.php");
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
			if (@$_GET["nu_projeto"] != "") {
				$this->nu_projeto->setQueryStringValue($_GET["nu_projeto"]);
				$this->setKey("nu_projeto", $this->nu_projeto->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_projeto", ""); // Clear key
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
					$this->Page_Terminate("projeto_centrocustolist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "projeto_centrocustoview.php")
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
		$this->nu_projeto->CurrentValue = NULL;
		$this->nu_projeto->OldValue = $this->nu_projeto->CurrentValue;
		$this->nu_centroCusto_->CurrentValue = NULL;
		$this->nu_centroCusto_->OldValue = $this->nu_centroCusto_->CurrentValue;
		$this->pc_participacao->CurrentValue = "1";
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_projeto->FldIsDetailKey) {
			$this->nu_projeto->setFormValue($objForm->GetValue("x_nu_projeto"));
		}
		if (!$this->nu_centroCusto_->FldIsDetailKey) {
			$this->nu_centroCusto_->setFormValue($objForm->GetValue("x_nu_centroCusto_"));
		}
		if (!$this->pc_participacao->FldIsDetailKey) {
			$this->pc_participacao->setFormValue($objForm->GetValue("x_pc_participacao"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->nu_projeto->CurrentValue = $this->nu_projeto->FormValue;
		$this->nu_centroCusto_->CurrentValue = $this->nu_centroCusto_->FormValue;
		$this->pc_participacao->CurrentValue = $this->pc_participacao->FormValue;
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
		$this->nu_projeto->setDbValue($rs->fields('nu_projeto'));
		$this->nu_centroCusto_->setDbValue($rs->fields('nu_centroCusto '));
		$this->pc_participacao->setDbValue($rs->fields('pc_participacao'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_projeto->DbValue = $row['nu_projeto'];
		$this->nu_centroCusto_->DbValue = $row['nu_centroCusto '];
		$this->pc_participacao->DbValue = $row['pc_participacao'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_projeto")) <> "")
			$this->nu_projeto->CurrentValue = $this->getKey("nu_projeto"); // nu_projeto
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
		// Convert decimal values if posted back

		if ($this->pc_participacao->FormValue == $this->pc_participacao->CurrentValue && is_numeric(ew_StrToFloat($this->pc_participacao->CurrentValue)))
			$this->pc_participacao->CurrentValue = ew_StrToFloat($this->pc_participacao->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_projeto
		// nu_centroCusto 
		// pc_participacao

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_projeto
			if (strval($this->nu_projeto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_projeto]" . ew_SearchString("=", $this->nu_projeto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_projeto], [no_projeto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[projeto]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_projeto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_projeto] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_projeto->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_projeto->ViewValue = $this->nu_projeto->CurrentValue;
				}
			} else {
				$this->nu_projeto->ViewValue = NULL;
			}
			$this->nu_projeto->ViewCustomAttributes = "";

			// nu_centroCusto 
			if (strval($this->nu_centroCusto_->CurrentValue) <> "") {
				$sFilterWrk = "[nu_centroCusto]" . ew_SearchString("=", $this->nu_centroCusto_->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_centroCusto], [co_alternativo] AS [DispFld], [no_centroCusto] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[centrocusto]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_centroCusto_, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_centroCusto] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_centroCusto_->ViewValue = $rswrk->fields('DispFld');
					$this->nu_centroCusto_->ViewValue .= ew_ValueSeparator(1,$this->nu_centroCusto_) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_centroCusto_->ViewValue = $this->nu_centroCusto_->CurrentValue;
				}
			} else {
				$this->nu_centroCusto_->ViewValue = NULL;
			}
			$this->nu_centroCusto_->ViewCustomAttributes = "";

			// pc_participacao
			$this->pc_participacao->ViewValue = $this->pc_participacao->CurrentValue;
			$this->pc_participacao->ViewValue = ew_FormatPercent($this->pc_participacao->ViewValue, 0, -2, -2, -2);
			$this->pc_participacao->ViewCustomAttributes = "";

			// nu_projeto
			$this->nu_projeto->LinkCustomAttributes = "";
			$this->nu_projeto->HrefValue = "";
			$this->nu_projeto->TooltipValue = "";

			// nu_centroCusto 
			$this->nu_centroCusto_->LinkCustomAttributes = "";
			$this->nu_centroCusto_->HrefValue = "";
			$this->nu_centroCusto_->TooltipValue = "";

			// pc_participacao
			$this->pc_participacao->LinkCustomAttributes = "";
			$this->pc_participacao->HrefValue = "";
			$this->pc_participacao->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nu_projeto
			$this->nu_projeto->EditCustomAttributes = "";
			if ($this->nu_projeto->getSessionValue() <> "") {
				$this->nu_projeto->CurrentValue = $this->nu_projeto->getSessionValue();
			if (strval($this->nu_projeto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_projeto]" . ew_SearchString("=", $this->nu_projeto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_projeto], [no_projeto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[projeto]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_projeto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_projeto] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_projeto->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_projeto->ViewValue = $this->nu_projeto->CurrentValue;
				}
			} else {
				$this->nu_projeto->ViewValue = NULL;
			}
			$this->nu_projeto->ViewCustomAttributes = "";
			} else {
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_projeto], [no_projeto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[projeto]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_projeto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_projeto] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_projeto->EditValue = $arwrk;
			}

			// nu_centroCusto 
			$this->nu_centroCusto_->EditCustomAttributes = "";
			if ($this->nu_centroCusto_->getSessionValue() <> "") {
				$this->nu_centroCusto_->CurrentValue = $this->nu_centroCusto_->getSessionValue();
			if (strval($this->nu_centroCusto_->CurrentValue) <> "") {
				$sFilterWrk = "[nu_centroCusto]" . ew_SearchString("=", $this->nu_centroCusto_->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_centroCusto], [co_alternativo] AS [DispFld], [no_centroCusto] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[centrocusto]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_centroCusto_, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_centroCusto] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_centroCusto_->ViewValue = $rswrk->fields('DispFld');
					$this->nu_centroCusto_->ViewValue .= ew_ValueSeparator(1,$this->nu_centroCusto_) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_centroCusto_->ViewValue = $this->nu_centroCusto_->CurrentValue;
				}
			} else {
				$this->nu_centroCusto_->ViewValue = NULL;
			}
			$this->nu_centroCusto_->ViewCustomAttributes = "";
			} else {
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_centroCusto], [co_alternativo] AS [DispFld], [no_centroCusto] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[centrocusto]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_centroCusto_, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_centroCusto] DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_centroCusto_->EditValue = $arwrk;
			}

			// pc_participacao
			$this->pc_participacao->EditCustomAttributes = "";
			$this->pc_participacao->EditValue = ew_HtmlEncode($this->pc_participacao->CurrentValue);
			$this->pc_participacao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->pc_participacao->FldCaption()));
			if (strval($this->pc_participacao->EditValue) <> "" && is_numeric($this->pc_participacao->EditValue)) $this->pc_participacao->EditValue = ew_FormatNumber($this->pc_participacao->EditValue, -2, -1, -2, 0);

			// Edit refer script
			// nu_projeto

			$this->nu_projeto->HrefValue = "";

			// nu_centroCusto 
			$this->nu_centroCusto_->HrefValue = "";

			// pc_participacao
			$this->pc_participacao->HrefValue = "";
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
		if (!$this->nu_projeto->FldIsDetailKey && !is_null($this->nu_projeto->FormValue) && $this->nu_projeto->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_projeto->FldCaption());
		}
		if (!$this->nu_centroCusto_->FldIsDetailKey && !is_null($this->nu_centroCusto_->FormValue) && $this->nu_centroCusto_->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_centroCusto_->FldCaption());
		}
		if (!$this->pc_participacao->FldIsDetailKey && !is_null($this->pc_participacao->FormValue) && $this->pc_participacao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->pc_participacao->FldCaption());
		}
		if (!ew_CheckNumber($this->pc_participacao->FormValue)) {
			ew_AddMessage($gsFormError, $this->pc_participacao->FldErrMsg());
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

		// Check referential integrity for master table 'projeto'
		$bValidMasterRecord = TRUE;
		$sMasterFilter = $this->SqlMasterFilter_projeto();
		if (strval($this->nu_projeto->CurrentValue) <> "") {
			$sMasterFilter = str_replace("@nu_projeto@", ew_AdjustSql($this->nu_projeto->CurrentValue), $sMasterFilter);
		} else {
			$bValidMasterRecord = FALSE;
		}
		if ($bValidMasterRecord) {
			$rsmaster = $GLOBALS["projeto"]->LoadRs($sMasterFilter);
			$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
			$rsmaster->Close();
		}
		if (!$bValidMasterRecord) {
			$sRelatedRecordMsg = str_replace("%t", "projeto", $Language->Phrase("RelatedRecordRequired"));
			$this->setFailureMessage($sRelatedRecordMsg);
			return FALSE;
		}

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// nu_projeto
		$this->nu_projeto->SetDbValueDef($rsnew, $this->nu_projeto->CurrentValue, 0, FALSE);

		// nu_centroCusto 
		$this->nu_centroCusto_->SetDbValueDef($rsnew, $this->nu_centroCusto_->CurrentValue, 0, FALSE);

		// pc_participacao
		$this->pc_participacao->SetDbValueDef($rsnew, $this->pc_participacao->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && $this->nu_projeto->CurrentValue == "" && $this->nu_projeto->getSessionValue() == "") {
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
			if ($sMasterTblVar == "centrocusto") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_centroCusto"] <> "") {
					$GLOBALS["centrocusto"]->nu_centroCusto->setQueryStringValue($_GET["nu_centroCusto"]);
					$this->nu_centroCusto_->setQueryStringValue($GLOBALS["centrocusto"]->nu_centroCusto->QueryStringValue);
					$this->nu_centroCusto_->setSessionValue($this->nu_centroCusto_->QueryStringValue);
					if (!is_numeric($GLOBALS["centrocusto"]->nu_centroCusto->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
			if ($sMasterTblVar == "projeto") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_projeto"] <> "") {
					$GLOBALS["projeto"]->nu_projeto->setQueryStringValue($_GET["nu_projeto"]);
					$this->nu_projeto->setQueryStringValue($GLOBALS["projeto"]->nu_projeto->QueryStringValue);
					$this->nu_projeto->setSessionValue($this->nu_projeto->QueryStringValue);
					if (!is_numeric($GLOBALS["projeto"]->nu_projeto->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "centrocusto") {
				if ($this->nu_centroCusto_->QueryStringValue == "") $this->nu_centroCusto_->setSessionValue("");
			}
			if ($sMasterTblVar <> "projeto") {
				if ($this->nu_projeto->QueryStringValue == "") $this->nu_projeto->setSessionValue("");
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "projeto_centrocustolist.php", $this->TableVar);
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
if (!isset($projeto_centrocusto_add)) $projeto_centrocusto_add = new cprojeto_centrocusto_add();

// Page init
$projeto_centrocusto_add->Page_Init();

// Page main
$projeto_centrocusto_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$projeto_centrocusto_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var projeto_centrocusto_add = new ew_Page("projeto_centrocusto_add");
projeto_centrocusto_add.PageID = "add"; // Page ID
var EW_PAGE_ID = projeto_centrocusto_add.PageID; // For backward compatibility

// Form object
var fprojeto_centrocustoadd = new ew_Form("fprojeto_centrocustoadd");

// Validate form
fprojeto_centrocustoadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_projeto");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($projeto_centrocusto->nu_projeto->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_centroCusto_");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($projeto_centrocusto->nu_centroCusto_->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_pc_participacao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($projeto_centrocusto->pc_participacao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_pc_participacao");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($projeto_centrocusto->pc_participacao->FldErrMsg()) ?>");

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
fprojeto_centrocustoadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fprojeto_centrocustoadd.ValidateRequired = true;
<?php } else { ?>
fprojeto_centrocustoadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fprojeto_centrocustoadd.Lists["x_nu_projeto"] = {"LinkField":"x_nu_projeto","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_projeto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fprojeto_centrocustoadd.Lists["x_nu_centroCusto_"] = {"LinkField":"x_nu_centroCusto","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_alternativo","x_no_centroCusto","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $projeto_centrocusto_add->ShowPageHeader(); ?>
<?php
$projeto_centrocusto_add->ShowMessage();
?>
<form name="fprojeto_centrocustoadd" id="fprojeto_centrocustoadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="projeto_centrocusto">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_projeto_centrocustoadd" class="table table-bordered table-striped">
<?php if ($projeto_centrocusto->nu_projeto->Visible) { // nu_projeto ?>
	<tr id="r_nu_projeto">
		<td><span id="elh_projeto_centrocusto_nu_projeto"><?php echo $projeto_centrocusto->nu_projeto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $projeto_centrocusto->nu_projeto->CellAttributes() ?>>
<?php if ($projeto_centrocusto->nu_projeto->getSessionValue() <> "") { ?>
<span<?php echo $projeto_centrocusto->nu_projeto->ViewAttributes() ?>>
<?php echo $projeto_centrocusto->nu_projeto->ViewValue ?></span>
<input type="hidden" id="x_nu_projeto" name="x_nu_projeto" value="<?php echo ew_HtmlEncode($projeto_centrocusto->nu_projeto->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_nu_projeto" id="x_nu_projeto" name="x_nu_projeto"<?php echo $projeto_centrocusto->nu_projeto->EditAttributes() ?>>
<?php
if (is_array($projeto_centrocusto->nu_projeto->EditValue)) {
	$arwrk = $projeto_centrocusto->nu_projeto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($projeto_centrocusto->nu_projeto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fprojeto_centrocustoadd.Lists["x_nu_projeto"].Options = <?php echo (is_array($projeto_centrocusto->nu_projeto->EditValue)) ? ew_ArrayToJson($projeto_centrocusto->nu_projeto->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<?php echo $projeto_centrocusto->nu_projeto->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($projeto_centrocusto->nu_centroCusto_->Visible) { // nu_centroCusto  ?>
	<tr id="r_nu_centroCusto_">
		<td><span id="elh_projeto_centrocusto_nu_centroCusto_"><?php echo $projeto_centrocusto->nu_centroCusto_->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $projeto_centrocusto->nu_centroCusto_->CellAttributes() ?>>
<?php if ($projeto_centrocusto->nu_centroCusto_->getSessionValue() <> "") { ?>
<span<?php echo $projeto_centrocusto->nu_centroCusto_->ViewAttributes() ?>>
<?php echo $projeto_centrocusto->nu_centroCusto_->ViewValue ?></span>
<input type="hidden" id="x_nu_centroCusto_" name="x_nu_centroCusto_" value="<?php echo ew_HtmlEncode($projeto_centrocusto->nu_centroCusto_->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_nu_centroCusto_" id="x_nu_centroCusto_" name="x_nu_centroCusto_"<?php echo $projeto_centrocusto->nu_centroCusto_->EditAttributes() ?>>
<?php
if (is_array($projeto_centrocusto->nu_centroCusto_->EditValue)) {
	$arwrk = $projeto_centrocusto->nu_centroCusto_->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($projeto_centrocusto->nu_centroCusto_->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$projeto_centrocusto->nu_centroCusto_) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fprojeto_centrocustoadd.Lists["x_nu_centroCusto_"].Options = <?php echo (is_array($projeto_centrocusto->nu_centroCusto_->EditValue)) ? ew_ArrayToJson($projeto_centrocusto->nu_centroCusto_->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<?php echo $projeto_centrocusto->nu_centroCusto_->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($projeto_centrocusto->pc_participacao->Visible) { // pc_participacao ?>
	<tr id="r_pc_participacao">
		<td><span id="elh_projeto_centrocusto_pc_participacao"><?php echo $projeto_centrocusto->pc_participacao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $projeto_centrocusto->pc_participacao->CellAttributes() ?>>
<span id="el_projeto_centrocusto_pc_participacao" class="control-group">
<input type="text" data-field="x_pc_participacao" name="x_pc_participacao" id="x_pc_participacao" size="30" placeholder="<?php echo $projeto_centrocusto->pc_participacao->PlaceHolder ?>" value="<?php echo $projeto_centrocusto->pc_participacao->EditValue ?>"<?php echo $projeto_centrocusto->pc_participacao->EditAttributes() ?>>
</span>
<?php echo $projeto_centrocusto->pc_participacao->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fprojeto_centrocustoadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$projeto_centrocusto_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$projeto_centrocusto_add->Page_Terminate();
?>
