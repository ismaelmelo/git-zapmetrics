<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "necstakeinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "metaneginfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$necstake_edit = NULL; // Initialize page object first

class cnecstake_edit extends cnecstake {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{FE479719-4CC0-498B-BE07-C9817DD0435B}";

	// Table name
	var $TableName = 'necstake';

	// Page object name
	var $PageObjName = 'necstake_edit';

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

		// Table object (necstake)
		if (!isset($GLOBALS["necstake"])) {
			$GLOBALS["necstake"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["necstake"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Table object (metaneg)
		if (!isset($GLOBALS['metaneg'])) $GLOBALS['metaneg'] = new cmetaneg();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'necstake', TRUE);

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
			$this->Page_Terminate("necstakelist.php");
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
		if (@$_GET["nu_necessidade"] <> "") {
			$this->nu_necessidade->setQueryStringValue($_GET["nu_necessidade"]);
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
		if ($this->nu_necessidade->CurrentValue == "")
			$this->Page_Terminate("necstakelist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("necstakelist.php"); // No matching record, return to list
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
		if (!$this->nu_periodoPei->FldIsDetailKey) {
			$this->nu_periodoPei->setFormValue($objForm->GetValue("x_nu_periodoPei"));
		}
		if (!$this->nu_motivador->FldIsDetailKey) {
			$this->nu_motivador->setFormValue($objForm->GetValue("x_nu_motivador"));
		}
		if (!$this->no_necessidade->FldIsDetailKey) {
			$this->no_necessidade->setFormValue($objForm->GetValue("x_no_necessidade"));
		}
		if (!$this->ds_necessidade->FldIsDetailKey) {
			$this->ds_necessidade->setFormValue($objForm->GetValue("x_ds_necessidade"));
		}
		if (!$this->ic_situacao->FldIsDetailKey) {
			$this->ic_situacao->setFormValue($objForm->GetValue("x_ic_situacao"));
		}
		if (!$this->nu_necessidade->FldIsDetailKey)
			$this->nu_necessidade->setFormValue($objForm->GetValue("x_nu_necessidade"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->nu_necessidade->CurrentValue = $this->nu_necessidade->FormValue;
		$this->nu_periodoPei->CurrentValue = $this->nu_periodoPei->FormValue;
		$this->nu_motivador->CurrentValue = $this->nu_motivador->FormValue;
		$this->no_necessidade->CurrentValue = $this->no_necessidade->FormValue;
		$this->ds_necessidade->CurrentValue = $this->ds_necessidade->FormValue;
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
		$this->nu_necessidade->setDbValue($rs->fields('nu_necessidade'));
		$this->nu_periodoPei->setDbValue($rs->fields('nu_periodoPei'));
		if (array_key_exists('EV__nu_periodoPei', $rs->fields)) {
			$this->nu_periodoPei->VirtualValue = $rs->fields('EV__nu_periodoPei'); // Set up virtual field value
		} else {
			$this->nu_periodoPei->VirtualValue = ""; // Clear value
		}
		$this->nu_motivador->setDbValue($rs->fields('nu_motivador'));
		$this->no_necessidade->setDbValue($rs->fields('no_necessidade'));
		$this->ds_necessidade->setDbValue($rs->fields('ds_necessidade'));
		$this->ic_situacao->setDbValue($rs->fields('ic_situacao'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_necessidade->DbValue = $row['nu_necessidade'];
		$this->nu_periodoPei->DbValue = $row['nu_periodoPei'];
		$this->nu_motivador->DbValue = $row['nu_motivador'];
		$this->no_necessidade->DbValue = $row['no_necessidade'];
		$this->ds_necessidade->DbValue = $row['ds_necessidade'];
		$this->ic_situacao->DbValue = $row['ic_situacao'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_necessidade
		// nu_periodoPei
		// nu_motivador
		// no_necessidade
		// ds_necessidade
		// ic_situacao

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_necessidade
			$this->nu_necessidade->ViewValue = $this->nu_necessidade->CurrentValue;
			$this->nu_necessidade->ViewCustomAttributes = "";

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
			$sSqlWrk .= " ORDER BY [nu_periodoPei] DESC";
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

			// nu_motivador
			if (strval($this->nu_motivador->CurrentValue) <> "") {
				$sFilterWrk = "[nu_motivador]" . ew_SearchString("=", $this->nu_motivador->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_motivador], [no_motivador] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[motivstake]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_motivador, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_motivador] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_motivador->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_motivador->ViewValue = $this->nu_motivador->CurrentValue;
				}
			} else {
				$this->nu_motivador->ViewValue = NULL;
			}
			$this->nu_motivador->ViewCustomAttributes = "";

			// no_necessidade
			$this->no_necessidade->ViewValue = $this->no_necessidade->CurrentValue;
			$this->no_necessidade->ViewCustomAttributes = "";

			// ds_necessidade
			$this->ds_necessidade->ViewValue = $this->ds_necessidade->CurrentValue;
			$this->ds_necessidade->ViewCustomAttributes = "";

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

			// nu_motivador
			$this->nu_motivador->LinkCustomAttributes = "";
			$this->nu_motivador->HrefValue = "";
			$this->nu_motivador->TooltipValue = "";

			// no_necessidade
			$this->no_necessidade->LinkCustomAttributes = "";
			$this->no_necessidade->HrefValue = "";
			$this->no_necessidade->TooltipValue = "";

			// ds_necessidade
			$this->ds_necessidade->LinkCustomAttributes = "";
			$this->ds_necessidade->HrefValue = "";
			$this->ds_necessidade->TooltipValue = "";

			// ic_situacao
			$this->ic_situacao->LinkCustomAttributes = "";
			$this->ic_situacao->HrefValue = "";
			$this->ic_situacao->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

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
			$sSqlWrk .= " ORDER BY [nu_periodoPei] DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_periodoPei->EditValue = $arwrk;

			// nu_motivador
			$this->nu_motivador->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_motivador], [no_motivador] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[motivstake]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_motivador, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_motivador] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_motivador->EditValue = $arwrk;

			// no_necessidade
			$this->no_necessidade->EditCustomAttributes = "";
			$this->no_necessidade->EditValue = ew_HtmlEncode($this->no_necessidade->CurrentValue);
			$this->no_necessidade->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_necessidade->FldCaption()));

			// ds_necessidade
			$this->ds_necessidade->EditCustomAttributes = "";
			$this->ds_necessidade->EditValue = $this->ds_necessidade->CurrentValue;
			$this->ds_necessidade->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_necessidade->FldCaption()));

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

			// nu_motivador
			$this->nu_motivador->HrefValue = "";

			// no_necessidade
			$this->no_necessidade->HrefValue = "";

			// ds_necessidade
			$this->ds_necessidade->HrefValue = "";

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
		if (!$this->nu_motivador->FldIsDetailKey && !is_null($this->nu_motivador->FormValue) && $this->nu_motivador->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_motivador->FldCaption());
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

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
			if ($this->no_necessidade->CurrentValue <> "") { // Check field with unique index
			$sFilterChk = "([no_necessidade] = '" . ew_AdjustSql($this->no_necessidade->CurrentValue) . "')";
			$sFilterChk .= " AND NOT (" . $sFilter . ")";
			$this->CurrentFilter = $sFilterChk;
			$sSqlChk = $this->SQL();
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$rsChk = $conn->Execute($sSqlChk);
			$conn->raiseErrorFn = '';
			if ($rsChk === FALSE) {
				return FALSE;
			} elseif (!$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->no_necessidade->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->no_necessidade->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
			$rsChk->Close();
		}
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

			// nu_periodoPei
			$this->nu_periodoPei->SetDbValueDef($rsnew, $this->nu_periodoPei->CurrentValue, NULL, $this->nu_periodoPei->ReadOnly);

			// nu_motivador
			$this->nu_motivador->SetDbValueDef($rsnew, $this->nu_motivador->CurrentValue, NULL, $this->nu_motivador->ReadOnly);

			// no_necessidade
			$this->no_necessidade->SetDbValueDef($rsnew, $this->no_necessidade->CurrentValue, NULL, $this->no_necessidade->ReadOnly);

			// ds_necessidade
			$this->ds_necessidade->SetDbValueDef($rsnew, $this->ds_necessidade->CurrentValue, NULL, $this->ds_necessidade->ReadOnly);

			// ic_situacao
			$this->ic_situacao->SetDbValueDef($rsnew, $this->ic_situacao->CurrentValue, NULL, $this->ic_situacao->ReadOnly);

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
			if ($sMasterTblVar == "metaneg") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_necessidade"] <> "") {
					$GLOBALS["metaneg"]->nu_necessidade->setQueryStringValue($_GET["nu_necessidade"]);
					$this->nu_necessidade->setQueryStringValue($GLOBALS["metaneg"]->nu_necessidade->QueryStringValue);
					$this->nu_necessidade->setSessionValue($this->nu_necessidade->QueryStringValue);
					if (!is_numeric($GLOBALS["metaneg"]->nu_necessidade->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "metaneg") {
				if ($this->nu_necessidade->QueryStringValue == "") $this->nu_necessidade->setSessionValue("");
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "necstakelist.php", $this->TableVar);
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
if (!isset($necstake_edit)) $necstake_edit = new cnecstake_edit();

// Page init
$necstake_edit->Page_Init();

// Page main
$necstake_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$necstake_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var necstake_edit = new ew_Page("necstake_edit");
necstake_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = necstake_edit.PageID; // For backward compatibility

// Form object
var fnecstakeedit = new ew_Form("fnecstakeedit");

// Validate form
fnecstakeedit.Validate = function() {
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
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($necstake->nu_periodoPei->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_motivador");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($necstake->nu_motivador->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_situacao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($necstake->ic_situacao->FldCaption()) ?>");

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
fnecstakeedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fnecstakeedit.ValidateRequired = true;
<?php } else { ?>
fnecstakeedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fnecstakeedit.Lists["x_nu_periodoPei"] = {"LinkField":"x_nu_periodoPei","Ajax":null,"AutoFill":false,"DisplayFields":["x_nu_anoInicio","x_nu_anoFim","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fnecstakeedit.Lists["x_nu_motivador"] = {"LinkField":"x_nu_motivador","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_motivador","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $necstake_edit->ShowPageHeader(); ?>
<?php
$necstake_edit->ShowMessage();
?>
<form name="fnecstakeedit" id="fnecstakeedit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="necstake">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_necstakeedit" class="table table-bordered table-striped">
<?php if ($necstake->nu_periodoPei->Visible) { // nu_periodoPei ?>
	<tr id="r_nu_periodoPei">
		<td><span id="elh_necstake_nu_periodoPei"><?php echo $necstake->nu_periodoPei->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $necstake->nu_periodoPei->CellAttributes() ?>>
<span id="el_necstake_nu_periodoPei" class="control-group">
<select data-field="x_nu_periodoPei" id="x_nu_periodoPei" name="x_nu_periodoPei"<?php echo $necstake->nu_periodoPei->EditAttributes() ?>>
<?php
if (is_array($necstake->nu_periodoPei->EditValue)) {
	$arwrk = $necstake->nu_periodoPei->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($necstake->nu_periodoPei->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$necstake->nu_periodoPei) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fnecstakeedit.Lists["x_nu_periodoPei"].Options = <?php echo (is_array($necstake->nu_periodoPei->EditValue)) ? ew_ArrayToJson($necstake->nu_periodoPei->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $necstake->nu_periodoPei->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($necstake->nu_motivador->Visible) { // nu_motivador ?>
	<tr id="r_nu_motivador">
		<td><span id="elh_necstake_nu_motivador"><?php echo $necstake->nu_motivador->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $necstake->nu_motivador->CellAttributes() ?>>
<span id="el_necstake_nu_motivador" class="control-group">
<select data-field="x_nu_motivador" id="x_nu_motivador" name="x_nu_motivador"<?php echo $necstake->nu_motivador->EditAttributes() ?>>
<?php
if (is_array($necstake->nu_motivador->EditValue)) {
	$arwrk = $necstake->nu_motivador->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($necstake->nu_motivador->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fnecstakeedit.Lists["x_nu_motivador"].Options = <?php echo (is_array($necstake->nu_motivador->EditValue)) ? ew_ArrayToJson($necstake->nu_motivador->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $necstake->nu_motivador->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($necstake->no_necessidade->Visible) { // no_necessidade ?>
	<tr id="r_no_necessidade">
		<td><span id="elh_necstake_no_necessidade"><?php echo $necstake->no_necessidade->FldCaption() ?></span></td>
		<td<?php echo $necstake->no_necessidade->CellAttributes() ?>>
<span id="el_necstake_no_necessidade" class="control-group">
<input type="text" data-field="x_no_necessidade" name="x_no_necessidade" id="x_no_necessidade" size="30" maxlength="255" placeholder="<?php echo $necstake->no_necessidade->PlaceHolder ?>" value="<?php echo $necstake->no_necessidade->EditValue ?>"<?php echo $necstake->no_necessidade->EditAttributes() ?>>
</span>
<?php echo $necstake->no_necessidade->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($necstake->ds_necessidade->Visible) { // ds_necessidade ?>
	<tr id="r_ds_necessidade">
		<td><span id="elh_necstake_ds_necessidade"><?php echo $necstake->ds_necessidade->FldCaption() ?></span></td>
		<td<?php echo $necstake->ds_necessidade->CellAttributes() ?>>
<span id="el_necstake_ds_necessidade" class="control-group">
<textarea data-field="x_ds_necessidade" name="x_ds_necessidade" id="x_ds_necessidade" cols="35" rows="4" placeholder="<?php echo $necstake->ds_necessidade->PlaceHolder ?>"<?php echo $necstake->ds_necessidade->EditAttributes() ?>><?php echo $necstake->ds_necessidade->EditValue ?></textarea>
</span>
<?php echo $necstake->ds_necessidade->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($necstake->ic_situacao->Visible) { // ic_situacao ?>
	<tr id="r_ic_situacao">
		<td><span id="elh_necstake_ic_situacao"><?php echo $necstake->ic_situacao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $necstake->ic_situacao->CellAttributes() ?>>
<span id="el_necstake_ic_situacao" class="control-group">
<select data-field="x_ic_situacao" id="x_ic_situacao" name="x_ic_situacao"<?php echo $necstake->ic_situacao->EditAttributes() ?>>
<?php
if (is_array($necstake->ic_situacao->EditValue)) {
	$arwrk = $necstake->ic_situacao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($necstake->ic_situacao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $necstake->ic_situacao->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<input type="hidden" data-field="x_nu_necessidade" name="x_nu_necessidade" id="x_nu_necessidade" value="<?php echo ew_HtmlEncode($necstake->nu_necessidade->CurrentValue) ?>">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fnecstakeedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$necstake_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$necstake_edit->Page_Terminate();
?>
