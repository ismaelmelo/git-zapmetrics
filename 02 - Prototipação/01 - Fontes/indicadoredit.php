<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "indicadorinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "indicadorversaoinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$indicador_edit = NULL; // Initialize page object first

class cindicador_edit extends cindicador {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{FE479719-4CC0-498B-BE07-C9817DD0435B}";

	// Table name
	var $TableName = 'indicador';

	// Page object name
	var $PageObjName = 'indicador_edit';

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

		// Table object (indicador)
		if (!isset($GLOBALS["indicador"])) {
			$GLOBALS["indicador"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["indicador"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Table object (indicadorversao)
		if (!isset($GLOBALS['indicadorversao'])) $GLOBALS['indicadorversao'] = new cindicadorversao();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'indicador', TRUE);

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
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("indicadorlist.php");
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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["nu_indicador"] <> "") {
			$this->nu_indicador->setQueryStringValue($_GET["nu_indicador"]);
		}

		// Set up master detail parameters
		$this->SetUpMasterParms();

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->nu_indicador->CurrentValue == "")
			$this->Page_Terminate("indicadorlist.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("indicadorlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->no_indicador->FldIsDetailKey) {
			$this->no_indicador->setFormValue($objForm->GetValue("x_no_indicador"));
		}
		if (!$this->ds_indicador->FldIsDetailKey) {
			$this->ds_indicador->setFormValue($objForm->GetValue("x_ds_indicador"));
		}
		if (!$this->ic_tpIndicador->FldIsDetailKey) {
			$this->ic_tpIndicador->setFormValue($objForm->GetValue("x_ic_tpIndicador"));
		}
		if (!$this->nu_processoCobit5->FldIsDetailKey) {
			$this->nu_processoCobit5->setFormValue($objForm->GetValue("x_nu_processoCobit5"));
		}
		if (!$this->ic_ativo->FldIsDetailKey) {
			$this->ic_ativo->setFormValue($objForm->GetValue("x_ic_ativo"));
		}
		if (!$this->nu_indicador->FldIsDetailKey)
			$this->nu_indicador->setFormValue($objForm->GetValue("x_nu_indicador"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->nu_indicador->CurrentValue = $this->nu_indicador->FormValue;
		$this->no_indicador->CurrentValue = $this->no_indicador->FormValue;
		$this->ds_indicador->CurrentValue = $this->ds_indicador->FormValue;
		$this->ic_tpIndicador->CurrentValue = $this->ic_tpIndicador->FormValue;
		$this->nu_processoCobit5->CurrentValue = $this->nu_processoCobit5->FormValue;
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
		$this->nu_indicador->setDbValue($rs->fields('nu_indicador'));
		$this->no_indicador->setDbValue($rs->fields('no_indicador'));
		$this->ds_indicador->setDbValue($rs->fields('ds_indicador'));
		$this->ic_tpIndicador->setDbValue($rs->fields('ic_tpIndicador'));
		$this->nu_processoCobit5->setDbValue($rs->fields('nu_processoCobit5'));
		if (array_key_exists('EV__nu_processoCobit5', $rs->fields)) {
			$this->nu_processoCobit5->VirtualValue = $rs->fields('EV__nu_processoCobit5'); // Set up virtual field value
		} else {
			$this->nu_processoCobit5->VirtualValue = ""; // Clear value
		}
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_indicador->DbValue = $row['nu_indicador'];
		$this->no_indicador->DbValue = $row['no_indicador'];
		$this->ds_indicador->DbValue = $row['ds_indicador'];
		$this->ic_tpIndicador->DbValue = $row['ic_tpIndicador'];
		$this->nu_processoCobit5->DbValue = $row['nu_processoCobit5'];
		$this->ic_ativo->DbValue = $row['ic_ativo'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_indicador
		// no_indicador
		// ds_indicador
		// ic_tpIndicador
		// nu_processoCobit5
		// ic_ativo

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_indicador
			$this->nu_indicador->ViewValue = $this->nu_indicador->CurrentValue;
			$this->nu_indicador->ViewCustomAttributes = "";

			// no_indicador
			$this->no_indicador->ViewValue = $this->no_indicador->CurrentValue;
			$this->no_indicador->ViewCustomAttributes = "";

			// ds_indicador
			$this->ds_indicador->ViewValue = $this->ds_indicador->CurrentValue;
			$this->ds_indicador->ViewCustomAttributes = "";

			// ic_tpIndicador
			if (strval($this->ic_tpIndicador->CurrentValue) <> "") {
				switch ($this->ic_tpIndicador->CurrentValue) {
					case $this->ic_tpIndicador->FldTagValue(1):
						$this->ic_tpIndicador->ViewValue = $this->ic_tpIndicador->FldTagCaption(1) <> "" ? $this->ic_tpIndicador->FldTagCaption(1) : $this->ic_tpIndicador->CurrentValue;
						break;
					case $this->ic_tpIndicador->FldTagValue(2):
						$this->ic_tpIndicador->ViewValue = $this->ic_tpIndicador->FldTagCaption(2) <> "" ? $this->ic_tpIndicador->FldTagCaption(2) : $this->ic_tpIndicador->CurrentValue;
						break;
					case $this->ic_tpIndicador->FldTagValue(3):
						$this->ic_tpIndicador->ViewValue = $this->ic_tpIndicador->FldTagCaption(3) <> "" ? $this->ic_tpIndicador->FldTagCaption(3) : $this->ic_tpIndicador->CurrentValue;
						break;
					case $this->ic_tpIndicador->FldTagValue(4):
						$this->ic_tpIndicador->ViewValue = $this->ic_tpIndicador->FldTagCaption(4) <> "" ? $this->ic_tpIndicador->FldTagCaption(4) : $this->ic_tpIndicador->CurrentValue;
						break;
					default:
						$this->ic_tpIndicador->ViewValue = $this->ic_tpIndicador->CurrentValue;
				}
			} else {
				$this->ic_tpIndicador->ViewValue = NULL;
			}
			$this->ic_tpIndicador->ViewCustomAttributes = "";

			// nu_processoCobit5
			if ($this->nu_processoCobit5->VirtualValue <> "") {
				$this->nu_processoCobit5->ViewValue = $this->nu_processoCobit5->VirtualValue;
			} else {
			if (strval($this->nu_processoCobit5->CurrentValue) <> "") {
				$sFilterWrk = "[nu_processo]" . ew_SearchString("=", $this->nu_processoCobit5->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_processo], [co_alternativo] AS [DispFld], [no_processo] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[processocobit5]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_processoCobit5, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [ic_dominio] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_processoCobit5->ViewValue = $rswrk->fields('DispFld');
					$this->nu_processoCobit5->ViewValue .= ew_ValueSeparator(1,$this->nu_processoCobit5) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_processoCobit5->ViewValue = $this->nu_processoCobit5->CurrentValue;
				}
			} else {
				$this->nu_processoCobit5->ViewValue = NULL;
			}
			}
			$this->nu_processoCobit5->ViewCustomAttributes = "";

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

			// no_indicador
			$this->no_indicador->LinkCustomAttributes = "";
			$this->no_indicador->HrefValue = "";
			$this->no_indicador->TooltipValue = "";

			// ds_indicador
			$this->ds_indicador->LinkCustomAttributes = "";
			$this->ds_indicador->HrefValue = "";
			$this->ds_indicador->TooltipValue = "";

			// ic_tpIndicador
			$this->ic_tpIndicador->LinkCustomAttributes = "";
			$this->ic_tpIndicador->HrefValue = "";
			$this->ic_tpIndicador->TooltipValue = "";

			// nu_processoCobit5
			$this->nu_processoCobit5->LinkCustomAttributes = "";
			$this->nu_processoCobit5->HrefValue = "";
			$this->nu_processoCobit5->TooltipValue = "";

			// ic_ativo
			$this->ic_ativo->LinkCustomAttributes = "";
			$this->ic_ativo->HrefValue = "";
			$this->ic_ativo->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// no_indicador
			$this->no_indicador->EditCustomAttributes = "";
			$this->no_indicador->EditValue = ew_HtmlEncode($this->no_indicador->CurrentValue);
			$this->no_indicador->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_indicador->FldCaption()));

			// ds_indicador
			$this->ds_indicador->EditCustomAttributes = "";
			$this->ds_indicador->EditValue = $this->ds_indicador->CurrentValue;
			$this->ds_indicador->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_indicador->FldCaption()));

			// ic_tpIndicador
			$this->ic_tpIndicador->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_tpIndicador->FldTagValue(1), $this->ic_tpIndicador->FldTagCaption(1) <> "" ? $this->ic_tpIndicador->FldTagCaption(1) : $this->ic_tpIndicador->FldTagValue(1));
			$arwrk[] = array($this->ic_tpIndicador->FldTagValue(2), $this->ic_tpIndicador->FldTagCaption(2) <> "" ? $this->ic_tpIndicador->FldTagCaption(2) : $this->ic_tpIndicador->FldTagValue(2));
			$arwrk[] = array($this->ic_tpIndicador->FldTagValue(3), $this->ic_tpIndicador->FldTagCaption(3) <> "" ? $this->ic_tpIndicador->FldTagCaption(3) : $this->ic_tpIndicador->FldTagValue(3));
			$arwrk[] = array($this->ic_tpIndicador->FldTagValue(4), $this->ic_tpIndicador->FldTagCaption(4) <> "" ? $this->ic_tpIndicador->FldTagCaption(4) : $this->ic_tpIndicador->FldTagValue(4));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_tpIndicador->EditValue = $arwrk;

			// nu_processoCobit5
			$this->nu_processoCobit5->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_processo], [co_alternativo] AS [DispFld], [no_processo] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[processocobit5]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_processoCobit5, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [ic_dominio] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_processoCobit5->EditValue = $arwrk;

			// ic_ativo
			$this->ic_ativo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_ativo->FldTagValue(1), $this->ic_ativo->FldTagCaption(1) <> "" ? $this->ic_ativo->FldTagCaption(1) : $this->ic_ativo->FldTagValue(1));
			$arwrk[] = array($this->ic_ativo->FldTagValue(2), $this->ic_ativo->FldTagCaption(2) <> "" ? $this->ic_ativo->FldTagCaption(2) : $this->ic_ativo->FldTagValue(2));
			$this->ic_ativo->EditValue = $arwrk;

			// Edit refer script
			// no_indicador

			$this->no_indicador->HrefValue = "";

			// ds_indicador
			$this->ds_indicador->HrefValue = "";

			// ic_tpIndicador
			$this->ic_tpIndicador->HrefValue = "";

			// nu_processoCobit5
			$this->nu_processoCobit5->HrefValue = "";

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
		if (!$this->no_indicador->FldIsDetailKey && !is_null($this->no_indicador->FormValue) && $this->no_indicador->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_indicador->FldCaption());
		}
		if (!$this->ds_indicador->FldIsDetailKey && !is_null($this->ds_indicador->FormValue) && $this->ds_indicador->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ds_indicador->FldCaption());
		}
		if (!$this->ic_tpIndicador->FldIsDetailKey && !is_null($this->ic_tpIndicador->FormValue) && $this->ic_tpIndicador->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_tpIndicador->FldCaption());
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

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// no_indicador
			$this->no_indicador->SetDbValueDef($rsnew, $this->no_indicador->CurrentValue, "", $this->no_indicador->ReadOnly);

			// ds_indicador
			$this->ds_indicador->SetDbValueDef($rsnew, $this->ds_indicador->CurrentValue, NULL, $this->ds_indicador->ReadOnly);

			// ic_tpIndicador
			$this->ic_tpIndicador->SetDbValueDef($rsnew, $this->ic_tpIndicador->CurrentValue, NULL, $this->ic_tpIndicador->ReadOnly);

			// nu_processoCobit5
			$this->nu_processoCobit5->SetDbValueDef($rsnew, $this->nu_processoCobit5->CurrentValue, NULL, $this->nu_processoCobit5->ReadOnly);

			// ic_ativo
			$this->ic_ativo->SetDbValueDef($rsnew, $this->ic_ativo->CurrentValue, "", $this->ic_ativo->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = 'ew_ErrorFn';
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
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
			if ($sMasterTblVar == "indicadorversao") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_indicador"] <> "") {
					$GLOBALS["indicadorversao"]->nu_indicador->setQueryStringValue($_GET["nu_indicador"]);
					$this->nu_indicador->setQueryStringValue($GLOBALS["indicadorversao"]->nu_indicador->QueryStringValue);
					$this->nu_indicador->setSessionValue($this->nu_indicador->QueryStringValue);
					if (!is_numeric($GLOBALS["indicadorversao"]->nu_indicador->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "indicadorversao") {
				if ($this->nu_indicador->QueryStringValue == "") $this->nu_indicador->setSessionValue("");
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "indicadorlist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("edit");
		$Breadcrumb->Add("edit", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
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
if (!isset($indicador_edit)) $indicador_edit = new cindicador_edit();

// Page init
$indicador_edit->Page_Init();

// Page main
$indicador_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$indicador_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var indicador_edit = new ew_Page("indicador_edit");
indicador_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = indicador_edit.PageID; // For backward compatibility

// Form object
var findicadoredit = new ew_Form("findicadoredit");

// Validate form
findicadoredit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_no_indicador");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($indicador->no_indicador->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ds_indicador");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($indicador->ds_indicador->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_tpIndicador");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($indicador->ic_tpIndicador->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_ativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($indicador->ic_ativo->FldCaption()) ?>");

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
findicadoredit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
findicadoredit.ValidateRequired = true;
<?php } else { ?>
findicadoredit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
findicadoredit.Lists["x_nu_processoCobit5"] = {"LinkField":"x_nu_processo","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_alternativo","x_no_processo","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $indicador_edit->ShowPageHeader(); ?>
<?php
$indicador_edit->ShowMessage();
?>
<form name="findicadoredit" id="findicadoredit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="indicador">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_indicadoredit" class="table table-bordered table-striped">
<?php if ($indicador->no_indicador->Visible) { // no_indicador ?>
	<tr id="r_no_indicador">
		<td><span id="elh_indicador_no_indicador"><?php echo $indicador->no_indicador->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $indicador->no_indicador->CellAttributes() ?>>
<span id="el_indicador_no_indicador" class="control-group">
<input type="text" data-field="x_no_indicador" name="x_no_indicador" id="x_no_indicador" size="30" maxlength="100" placeholder="<?php echo $indicador->no_indicador->PlaceHolder ?>" value="<?php echo $indicador->no_indicador->EditValue ?>"<?php echo $indicador->no_indicador->EditAttributes() ?>>
</span>
<?php echo $indicador->no_indicador->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($indicador->ds_indicador->Visible) { // ds_indicador ?>
	<tr id="r_ds_indicador">
		<td><span id="elh_indicador_ds_indicador"><?php echo $indicador->ds_indicador->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $indicador->ds_indicador->CellAttributes() ?>>
<span id="el_indicador_ds_indicador" class="control-group">
<textarea data-field="x_ds_indicador" name="x_ds_indicador" id="x_ds_indicador" cols="35" rows="4" placeholder="<?php echo $indicador->ds_indicador->PlaceHolder ?>"<?php echo $indicador->ds_indicador->EditAttributes() ?>><?php echo $indicador->ds_indicador->EditValue ?></textarea>
</span>
<?php echo $indicador->ds_indicador->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($indicador->ic_tpIndicador->Visible) { // ic_tpIndicador ?>
	<tr id="r_ic_tpIndicador">
		<td><span id="elh_indicador_ic_tpIndicador"><?php echo $indicador->ic_tpIndicador->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $indicador->ic_tpIndicador->CellAttributes() ?>>
<span id="el_indicador_ic_tpIndicador" class="control-group">
<select data-field="x_ic_tpIndicador" id="x_ic_tpIndicador" name="x_ic_tpIndicador"<?php echo $indicador->ic_tpIndicador->EditAttributes() ?>>
<?php
if (is_array($indicador->ic_tpIndicador->EditValue)) {
	$arwrk = $indicador->ic_tpIndicador->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($indicador->ic_tpIndicador->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $indicador->ic_tpIndicador->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($indicador->nu_processoCobit5->Visible) { // nu_processoCobit5 ?>
	<tr id="r_nu_processoCobit5">
		<td><span id="elh_indicador_nu_processoCobit5"><?php echo $indicador->nu_processoCobit5->FldCaption() ?></span></td>
		<td<?php echo $indicador->nu_processoCobit5->CellAttributes() ?>>
<span id="el_indicador_nu_processoCobit5" class="control-group">
<select data-field="x_nu_processoCobit5" id="x_nu_processoCobit5" name="x_nu_processoCobit5"<?php echo $indicador->nu_processoCobit5->EditAttributes() ?>>
<?php
if (is_array($indicador->nu_processoCobit5->EditValue)) {
	$arwrk = $indicador->nu_processoCobit5->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($indicador->nu_processoCobit5->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$indicador->nu_processoCobit5) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
findicadoredit.Lists["x_nu_processoCobit5"].Options = <?php echo (is_array($indicador->nu_processoCobit5->EditValue)) ? ew_ArrayToJson($indicador->nu_processoCobit5->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $indicador->nu_processoCobit5->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($indicador->ic_ativo->Visible) { // ic_ativo ?>
	<tr id="r_ic_ativo">
		<td><span id="elh_indicador_ic_ativo"><?php echo $indicador->ic_ativo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $indicador->ic_ativo->CellAttributes() ?>>
<span id="el_indicador_ic_ativo" class="control-group">
<div id="tp_x_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_ativo" id="x_ic_ativo" value="{value}"<?php echo $indicador->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $indicador->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($indicador->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x_ic_ativo" id="x_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $indicador->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $indicador->ic_ativo->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<input type="hidden" data-field="x_nu_indicador" name="x_nu_indicador" id="x_nu_indicador" value="<?php echo ew_HtmlEncode($indicador->nu_indicador->CurrentValue) ?>">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
findicadoredit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$indicador_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$indicador_edit->Page_Terminate();
?>
