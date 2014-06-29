<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "faseroteiroinfo.php" ?>
<?php include_once "roteiroinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "ativroteirogridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$faseroteiro_add = NULL; // Initialize page object first

class cfaseroteiro_add extends cfaseroteiro {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'faseroteiro';

	// Page object name
	var $PageObjName = 'faseroteiro_add';

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

		// Table object (faseroteiro)
		if (!isset($GLOBALS["faseroteiro"])) {
			$GLOBALS["faseroteiro"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["faseroteiro"];
		}

		// Table object (roteiro)
		if (!isset($GLOBALS['roteiro'])) $GLOBALS['roteiro'] = new croteiro();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'faseroteiro', TRUE);

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
			$this->Page_Terminate("faseroteirolist.php");
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
			if (@$_GET["nu_faseRoteiro"] != "") {
				$this->nu_faseRoteiro->setQueryStringValue($_GET["nu_faseRoteiro"]);
				$this->setKey("nu_faseRoteiro", $this->nu_faseRoteiro->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_faseRoteiro", ""); // Clear key
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
					$this->Page_Terminate("faseroteirolist.php"); // No matching record, return to list
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
					if (ew_GetPageName($sReturnUrl) == "faseroteiroview.php")
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
		$this->nu_roteiro->CurrentValue = NULL;
		$this->nu_roteiro->OldValue = $this->nu_roteiro->CurrentValue;
		$this->no_faseRoteiro->CurrentValue = NULL;
		$this->no_faseRoteiro->OldValue = $this->no_faseRoteiro->CurrentValue;
		$this->pc_distribuicao->CurrentValue = NULL;
		$this->pc_distribuicao->OldValue = $this->pc_distribuicao->CurrentValue;
		$this->ic_ativo->CurrentValue = "S";
		$this->nu_ordem->CurrentValue = NULL;
		$this->nu_ordem->OldValue = $this->nu_ordem->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_roteiro->FldIsDetailKey) {
			$this->nu_roteiro->setFormValue($objForm->GetValue("x_nu_roteiro"));
		}
		if (!$this->no_faseRoteiro->FldIsDetailKey) {
			$this->no_faseRoteiro->setFormValue($objForm->GetValue("x_no_faseRoteiro"));
		}
		if (!$this->pc_distribuicao->FldIsDetailKey) {
			$this->pc_distribuicao->setFormValue($objForm->GetValue("x_pc_distribuicao"));
		}
		if (!$this->ic_ativo->FldIsDetailKey) {
			$this->ic_ativo->setFormValue($objForm->GetValue("x_ic_ativo"));
		}
		if (!$this->nu_ordem->FldIsDetailKey) {
			$this->nu_ordem->setFormValue($objForm->GetValue("x_nu_ordem"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->nu_roteiro->CurrentValue = $this->nu_roteiro->FormValue;
		$this->no_faseRoteiro->CurrentValue = $this->no_faseRoteiro->FormValue;
		$this->pc_distribuicao->CurrentValue = $this->pc_distribuicao->FormValue;
		$this->ic_ativo->CurrentValue = $this->ic_ativo->FormValue;
		$this->nu_ordem->CurrentValue = $this->nu_ordem->FormValue;
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
		$this->nu_faseRoteiro->setDbValue($rs->fields('nu_faseRoteiro'));
		$this->nu_roteiro->setDbValue($rs->fields('nu_roteiro'));
		$this->no_faseRoteiro->setDbValue($rs->fields('no_faseRoteiro'));
		$this->pc_distribuicao->setDbValue($rs->fields('pc_distribuicao'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
		$this->nu_ordem->setDbValue($rs->fields('nu_ordem'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_faseRoteiro->DbValue = $row['nu_faseRoteiro'];
		$this->nu_roteiro->DbValue = $row['nu_roteiro'];
		$this->no_faseRoteiro->DbValue = $row['no_faseRoteiro'];
		$this->pc_distribuicao->DbValue = $row['pc_distribuicao'];
		$this->ic_ativo->DbValue = $row['ic_ativo'];
		$this->nu_ordem->DbValue = $row['nu_ordem'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_faseRoteiro")) <> "")
			$this->nu_faseRoteiro->CurrentValue = $this->getKey("nu_faseRoteiro"); // nu_faseRoteiro
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

		if ($this->pc_distribuicao->FormValue == $this->pc_distribuicao->CurrentValue && is_numeric(ew_StrToFloat($this->pc_distribuicao->CurrentValue)))
			$this->pc_distribuicao->CurrentValue = ew_StrToFloat($this->pc_distribuicao->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_faseRoteiro
		// nu_roteiro
		// no_faseRoteiro
		// pc_distribuicao
		// ic_ativo
		// nu_ordem

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_roteiro
			if (strval($this->nu_roteiro->CurrentValue) <> "") {
				$sFilterWrk = "[nu_roteiro]" . ew_SearchString("=", $this->nu_roteiro->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_roteiro], [no_roteiro] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[roteiro]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_roteiro, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_roteiro->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_roteiro->ViewValue = $this->nu_roteiro->CurrentValue;
				}
			} else {
				$this->nu_roteiro->ViewValue = NULL;
			}
			$this->nu_roteiro->ViewCustomAttributes = "";

			// no_faseRoteiro
			$this->no_faseRoteiro->ViewValue = $this->no_faseRoteiro->CurrentValue;
			$this->no_faseRoteiro->ViewCustomAttributes = "";

			// pc_distribuicao
			$this->pc_distribuicao->ViewValue = $this->pc_distribuicao->CurrentValue;
			$this->pc_distribuicao->ViewValue = ew_FormatNumber($this->pc_distribuicao->ViewValue, 2, 0, 0, 0);
			$this->pc_distribuicao->ViewCustomAttributes = "";

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

			// nu_ordem
			$this->nu_ordem->ViewValue = $this->nu_ordem->CurrentValue;
			$this->nu_ordem->ViewCustomAttributes = "";

			// nu_roteiro
			$this->nu_roteiro->LinkCustomAttributes = "";
			$this->nu_roteiro->HrefValue = "";
			$this->nu_roteiro->TooltipValue = "";

			// no_faseRoteiro
			$this->no_faseRoteiro->LinkCustomAttributes = "";
			$this->no_faseRoteiro->HrefValue = "";
			$this->no_faseRoteiro->TooltipValue = "";

			// pc_distribuicao
			$this->pc_distribuicao->LinkCustomAttributes = "";
			$this->pc_distribuicao->HrefValue = "";
			$this->pc_distribuicao->TooltipValue = "";

			// ic_ativo
			$this->ic_ativo->LinkCustomAttributes = "";
			$this->ic_ativo->HrefValue = "";
			$this->ic_ativo->TooltipValue = "";

			// nu_ordem
			$this->nu_ordem->LinkCustomAttributes = "";
			$this->nu_ordem->HrefValue = "";
			$this->nu_ordem->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nu_roteiro
			$this->nu_roteiro->EditCustomAttributes = "";
			if ($this->nu_roteiro->getSessionValue() <> "") {
				$this->nu_roteiro->CurrentValue = $this->nu_roteiro->getSessionValue();
			if (strval($this->nu_roteiro->CurrentValue) <> "") {
				$sFilterWrk = "[nu_roteiro]" . ew_SearchString("=", $this->nu_roteiro->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_roteiro], [no_roteiro] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[roteiro]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_roteiro, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_roteiro->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_roteiro->ViewValue = $this->nu_roteiro->CurrentValue;
				}
			} else {
				$this->nu_roteiro->ViewValue = NULL;
			}
			$this->nu_roteiro->ViewCustomAttributes = "";
			} else {
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_roteiro], [no_roteiro] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[roteiro]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_roteiro, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_roteiro->EditValue = $arwrk;
			}

			// no_faseRoteiro
			$this->no_faseRoteiro->EditCustomAttributes = "";
			$this->no_faseRoteiro->EditValue = ew_HtmlEncode($this->no_faseRoteiro->CurrentValue);
			$this->no_faseRoteiro->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_faseRoteiro->FldCaption()));

			// pc_distribuicao
			$this->pc_distribuicao->EditCustomAttributes = "";
			$this->pc_distribuicao->EditValue = ew_HtmlEncode($this->pc_distribuicao->CurrentValue);
			$this->pc_distribuicao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->pc_distribuicao->FldCaption()));
			if (strval($this->pc_distribuicao->EditValue) <> "" && is_numeric($this->pc_distribuicao->EditValue)) $this->pc_distribuicao->EditValue = ew_FormatNumber($this->pc_distribuicao->EditValue, -2, 0, 0, 0);

			// ic_ativo
			$this->ic_ativo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_ativo->FldTagValue(1), $this->ic_ativo->FldTagCaption(1) <> "" ? $this->ic_ativo->FldTagCaption(1) : $this->ic_ativo->FldTagValue(1));
			$arwrk[] = array($this->ic_ativo->FldTagValue(2), $this->ic_ativo->FldTagCaption(2) <> "" ? $this->ic_ativo->FldTagCaption(2) : $this->ic_ativo->FldTagValue(2));
			$this->ic_ativo->EditValue = $arwrk;

			// nu_ordem
			$this->nu_ordem->EditCustomAttributes = "";
			$this->nu_ordem->EditValue = ew_HtmlEncode($this->nu_ordem->CurrentValue);
			$this->nu_ordem->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_ordem->FldCaption()));

			// Edit refer script
			// nu_roteiro

			$this->nu_roteiro->HrefValue = "";

			// no_faseRoteiro
			$this->no_faseRoteiro->HrefValue = "";

			// pc_distribuicao
			$this->pc_distribuicao->HrefValue = "";

			// ic_ativo
			$this->ic_ativo->HrefValue = "";

			// nu_ordem
			$this->nu_ordem->HrefValue = "";
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
		if (!$this->nu_roteiro->FldIsDetailKey && !is_null($this->nu_roteiro->FormValue) && $this->nu_roteiro->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_roteiro->FldCaption());
		}
		if (!$this->no_faseRoteiro->FldIsDetailKey && !is_null($this->no_faseRoteiro->FormValue) && $this->no_faseRoteiro->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_faseRoteiro->FldCaption());
		}
		if (!$this->pc_distribuicao->FldIsDetailKey && !is_null($this->pc_distribuicao->FormValue) && $this->pc_distribuicao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->pc_distribuicao->FldCaption());
		}
		if (!ew_CheckNumber($this->pc_distribuicao->FormValue)) {
			ew_AddMessage($gsFormError, $this->pc_distribuicao->FldErrMsg());
		}
		if ($this->ic_ativo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_ativo->FldCaption());
		}
		if (!ew_CheckInteger($this->nu_ordem->FormValue)) {
			ew_AddMessage($gsFormError, $this->nu_ordem->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("ativroteiro", $DetailTblVar) && $GLOBALS["ativroteiro"]->DetailAdd) {
			if (!isset($GLOBALS["ativroteiro_grid"])) $GLOBALS["ativroteiro_grid"] = new cativroteiro_grid(); // get detail page object
			$GLOBALS["ativroteiro_grid"]->ValidateGridForm();
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

		// Begin transaction
		if ($this->getCurrentDetailTable() <> "")
			$conn->BeginTrans();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// nu_roteiro
		$this->nu_roteiro->SetDbValueDef($rsnew, $this->nu_roteiro->CurrentValue, 0, FALSE);

		// no_faseRoteiro
		$this->no_faseRoteiro->SetDbValueDef($rsnew, $this->no_faseRoteiro->CurrentValue, "", FALSE);

		// pc_distribuicao
		$this->pc_distribuicao->SetDbValueDef($rsnew, $this->pc_distribuicao->CurrentValue, NULL, FALSE);

		// ic_ativo
		$this->ic_ativo->SetDbValueDef($rsnew, $this->ic_ativo->CurrentValue, "", FALSE);

		// nu_ordem
		$this->nu_ordem->SetDbValueDef($rsnew, $this->nu_ordem->CurrentValue, NULL, FALSE);

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
			$this->nu_faseRoteiro->setDbValue($conn->Insert_ID());
			$rsnew['nu_faseRoteiro'] = $this->nu_faseRoteiro->DbValue;
		}

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("ativroteiro", $DetailTblVar) && $GLOBALS["ativroteiro"]->DetailAdd) {
				$GLOBALS["ativroteiro"]->nu_faseRoteiro->setSessionValue($this->nu_faseRoteiro->CurrentValue); // Set master key
				if (!isset($GLOBALS["ativroteiro_grid"])) $GLOBALS["ativroteiro_grid"] = new cativroteiro_grid(); // Get detail page object
				$AddRow = $GLOBALS["ativroteiro_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["ativroteiro"]->nu_faseRoteiro->setSessionValue(""); // Clear master key if insert failed
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
			if ($sMasterTblVar == "roteiro") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_roteiro"] <> "") {
					$GLOBALS["roteiro"]->nu_roteiro->setQueryStringValue($_GET["nu_roteiro"]);
					$this->nu_roteiro->setQueryStringValue($GLOBALS["roteiro"]->nu_roteiro->QueryStringValue);
					$this->nu_roteiro->setSessionValue($this->nu_roteiro->QueryStringValue);
					if (!is_numeric($GLOBALS["roteiro"]->nu_roteiro->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "roteiro") {
				if ($this->nu_roteiro->QueryStringValue == "") $this->nu_roteiro->setSessionValue("");
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
			if (in_array("ativroteiro", $DetailTblVar)) {
				if (!isset($GLOBALS["ativroteiro_grid"]))
					$GLOBALS["ativroteiro_grid"] = new cativroteiro_grid;
				if ($GLOBALS["ativroteiro_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["ativroteiro_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["ativroteiro_grid"]->CurrentMode = "add";
					$GLOBALS["ativroteiro_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["ativroteiro_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["ativroteiro_grid"]->setStartRecordNumber(1);
					$GLOBALS["ativroteiro_grid"]->nu_faseRoteiro->FldIsDetailKey = TRUE;
					$GLOBALS["ativroteiro_grid"]->nu_faseRoteiro->CurrentValue = $this->nu_faseRoteiro->CurrentValue;
					$GLOBALS["ativroteiro_grid"]->nu_faseRoteiro->setSessionValue($GLOBALS["ativroteiro_grid"]->nu_faseRoteiro->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "faseroteirolist.php", $this->TableVar);
		$PageCaption = ($this->CurrentAction == "C") ? $Language->Phrase("Copy") : $Language->Phrase("Add");
		$Breadcrumb->Add("add", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'faseroteiro';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'faseroteiro';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_faseRoteiro'];

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
if (!isset($faseroteiro_add)) $faseroteiro_add = new cfaseroteiro_add();

// Page init
$faseroteiro_add->Page_Init();

// Page main
$faseroteiro_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$faseroteiro_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var faseroteiro_add = new ew_Page("faseroteiro_add");
faseroteiro_add.PageID = "add"; // Page ID
var EW_PAGE_ID = faseroteiro_add.PageID; // For backward compatibility

// Form object
var ffaseroteiroadd = new ew_Form("ffaseroteiroadd");

// Validate form
ffaseroteiroadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_roteiro");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($faseroteiro->nu_roteiro->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no_faseRoteiro");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($faseroteiro->no_faseRoteiro->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_pc_distribuicao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($faseroteiro->pc_distribuicao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_pc_distribuicao");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($faseroteiro->pc_distribuicao->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ic_ativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($faseroteiro->ic_ativo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_ordem");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($faseroteiro->nu_ordem->FldErrMsg()) ?>");

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
ffaseroteiroadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffaseroteiroadd.ValidateRequired = true;
<?php } else { ?>
ffaseroteiroadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ffaseroteiroadd.Lists["x_nu_roteiro"] = {"LinkField":"x_nu_roteiro","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_roteiro","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $faseroteiro_add->ShowPageHeader(); ?>
<?php
$faseroteiro_add->ShowMessage();
?>
<form name="ffaseroteiroadd" id="ffaseroteiroadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="faseroteiro">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_faseroteiroadd" class="table table-bordered table-striped">
<?php if ($faseroteiro->nu_roteiro->Visible) { // nu_roteiro ?>
	<tr id="r_nu_roteiro">
		<td><span id="elh_faseroteiro_nu_roteiro"><?php echo $faseroteiro->nu_roteiro->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $faseroteiro->nu_roteiro->CellAttributes() ?>>
<?php if ($faseroteiro->nu_roteiro->getSessionValue() <> "") { ?>
<span<?php echo $faseroteiro->nu_roteiro->ViewAttributes() ?>>
<?php echo $faseroteiro->nu_roteiro->ViewValue ?></span>
<input type="hidden" id="x_nu_roteiro" name="x_nu_roteiro" value="<?php echo ew_HtmlEncode($faseroteiro->nu_roteiro->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_nu_roteiro" id="x_nu_roteiro" name="x_nu_roteiro"<?php echo $faseroteiro->nu_roteiro->EditAttributes() ?>>
<?php
if (is_array($faseroteiro->nu_roteiro->EditValue)) {
	$arwrk = $faseroteiro->nu_roteiro->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($faseroteiro->nu_roteiro->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
ffaseroteiroadd.Lists["x_nu_roteiro"].Options = <?php echo (is_array($faseroteiro->nu_roteiro->EditValue)) ? ew_ArrayToJson($faseroteiro->nu_roteiro->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<?php echo $faseroteiro->nu_roteiro->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($faseroteiro->no_faseRoteiro->Visible) { // no_faseRoteiro ?>
	<tr id="r_no_faseRoteiro">
		<td><span id="elh_faseroteiro_no_faseRoteiro"><?php echo $faseroteiro->no_faseRoteiro->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $faseroteiro->no_faseRoteiro->CellAttributes() ?>>
<span id="el_faseroteiro_no_faseRoteiro" class="control-group">
<input type="text" data-field="x_no_faseRoteiro" name="x_no_faseRoteiro" id="x_no_faseRoteiro" size="30" maxlength="75" placeholder="<?php echo $faseroteiro->no_faseRoteiro->PlaceHolder ?>" value="<?php echo $faseroteiro->no_faseRoteiro->EditValue ?>"<?php echo $faseroteiro->no_faseRoteiro->EditAttributes() ?>>
</span>
<?php echo $faseroteiro->no_faseRoteiro->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($faseroteiro->pc_distribuicao->Visible) { // pc_distribuicao ?>
	<tr id="r_pc_distribuicao">
		<td><span id="elh_faseroteiro_pc_distribuicao"><?php echo $faseroteiro->pc_distribuicao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $faseroteiro->pc_distribuicao->CellAttributes() ?>>
<span id="el_faseroteiro_pc_distribuicao" class="control-group">
<input type="text" data-field="x_pc_distribuicao" name="x_pc_distribuicao" id="x_pc_distribuicao" size="30" placeholder="<?php echo $faseroteiro->pc_distribuicao->PlaceHolder ?>" value="<?php echo $faseroteiro->pc_distribuicao->EditValue ?>"<?php echo $faseroteiro->pc_distribuicao->EditAttributes() ?>>
</span>
<?php echo $faseroteiro->pc_distribuicao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($faseroteiro->ic_ativo->Visible) { // ic_ativo ?>
	<tr id="r_ic_ativo">
		<td><span id="elh_faseroteiro_ic_ativo"><?php echo $faseroteiro->ic_ativo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $faseroteiro->ic_ativo->CellAttributes() ?>>
<span id="el_faseroteiro_ic_ativo" class="control-group">
<div id="tp_x_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_ativo" id="x_ic_ativo" value="{value}"<?php echo $faseroteiro->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $faseroteiro->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($faseroteiro->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x_ic_ativo" id="x_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $faseroteiro->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $faseroteiro->ic_ativo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($faseroteiro->nu_ordem->Visible) { // nu_ordem ?>
	<tr id="r_nu_ordem">
		<td><span id="elh_faseroteiro_nu_ordem"><?php echo $faseroteiro->nu_ordem->FldCaption() ?></span></td>
		<td<?php echo $faseroteiro->nu_ordem->CellAttributes() ?>>
<span id="el_faseroteiro_nu_ordem" class="control-group">
<input type="text" data-field="x_nu_ordem" name="x_nu_ordem" id="x_nu_ordem" size="30" placeholder="<?php echo $faseroteiro->nu_ordem->PlaceHolder ?>" value="<?php echo $faseroteiro->nu_ordem->EditValue ?>"<?php echo $faseroteiro->nu_ordem->EditAttributes() ?>>
</span>
<?php echo $faseroteiro->nu_ordem->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<?php
	if (in_array("ativroteiro", explode(",", $faseroteiro->getCurrentDetailTable())) && $ativroteiro->DetailAdd) {
?>
<?php include_once "ativroteirogrid.php" ?>
<?php } ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
ffaseroteiroadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$faseroteiro_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$faseroteiro_add->Page_Terminate();
?>
