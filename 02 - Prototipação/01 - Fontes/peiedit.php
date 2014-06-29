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

$pei_edit = NULL; // Initialize page object first

class cpei_edit extends cpei {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'pei';

	// Page object name
	var $PageObjName = 'pei_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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
		if (!$Security->CanEdit()) {
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
		$this->nu_pei->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		if (@$_GET["nu_pei"] <> "") {
			$this->nu_pei->setQueryStringValue($_GET["nu_pei"]);
		}

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
		if ($this->nu_pei->CurrentValue == "")
			$this->Page_Terminate("peilist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("peilist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "peiview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to View page directly
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
		if (!$this->nu_pei->FldIsDetailKey)
			$this->nu_pei->setFormValue($objForm->GetValue("x_nu_pei"));
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
		$this->LoadRow();
		$this->nu_pei->CurrentValue = $this->nu_pei->FormValue;
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

			// nu_pei
			$this->nu_pei->LinkCustomAttributes = "";
			$this->nu_pei->HrefValue = "";
			$this->nu_pei->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// nu_pei
			$this->nu_pei->EditCustomAttributes = "";
			$this->nu_pei->EditValue = $this->nu_pei->CurrentValue;
			$this->nu_pei->ViewCustomAttributes = "";

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
			// nu_pei

			$this->nu_pei->HrefValue = "";

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

			// nu_periodoPei
			$this->nu_periodoPei->SetDbValueDef($rsnew, $this->nu_periodoPei->CurrentValue, NULL, $this->nu_periodoPei->ReadOnly);

			// no_capaPei
			$this->no_capaPei->SetDbValueDef($rsnew, $this->no_capaPei->CurrentValue, "", $this->no_capaPei->ReadOnly);

			// ds_introducao
			$this->ds_introducao->SetDbValueDef($rsnew, $this->ds_introducao->CurrentValue, NULL, $this->ds_introducao->ReadOnly);

			// ds_missao
			$this->ds_missao->SetDbValueDef($rsnew, $this->ds_missao->CurrentValue, NULL, $this->ds_missao->ReadOnly);

			// ds_visao
			$this->ds_visao->SetDbValueDef($rsnew, $this->ds_visao->CurrentValue, NULL, $this->ds_visao->ReadOnly);

			// ds_valores
			$this->ds_valores->SetDbValueDef($rsnew, $this->ds_valores->CurrentValue, NULL, $this->ds_valores->ReadOnly);

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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "peilist.php", $this->TableVar);
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
if (!isset($pei_edit)) $pei_edit = new cpei_edit();

// Page init
$pei_edit->Page_Init();

// Page main
$pei_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pei_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var pei_edit = new ew_Page("pei_edit");
pei_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = pei_edit.PageID; // For backward compatibility

// Form object
var fpeiedit = new ew_Form("fpeiedit");

// Validate form
fpeiedit.Validate = function() {
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
fpeiedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpeiedit.ValidateRequired = true;
<?php } else { ?>
fpeiedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fpeiedit.Lists["x_nu_periodoPei"] = {"LinkField":"x_nu_periodoPei","Ajax":null,"AutoFill":false,"DisplayFields":["x_nu_anoInicio","x_nu_anoFim","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $pei_edit->ShowPageHeader(); ?>
<?php
$pei_edit->ShowMessage();
?>
<form name="fpeiedit" id="fpeiedit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="pei">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_peiedit" class="table table-bordered table-striped">
<?php if ($pei->nu_pei->Visible) { // nu_pei ?>
	<tr id="r_nu_pei">
		<td><span id="elh_pei_nu_pei"><?php echo $pei->nu_pei->FldCaption() ?></span></td>
		<td<?php echo $pei->nu_pei->CellAttributes() ?>>
<span id="el_pei_nu_pei" class="control-group">
<span<?php echo $pei->nu_pei->ViewAttributes() ?>>
<?php echo $pei->nu_pei->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_pei" name="x_nu_pei" id="x_nu_pei" value="<?php echo ew_HtmlEncode($pei->nu_pei->CurrentValue) ?>">
<?php echo $pei->nu_pei->CustomMsg ?></td>
	</tr>
<?php } ?>
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
fpeiedit.Lists["x_nu_periodoPei"].Options = <?php echo (is_array($pei->nu_periodoPei->EditValue)) ? ew_ArrayToJson($pei->nu_periodoPei->EditValue, 1) : "[]" ?>;
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
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fpeiedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$pei_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$pei_edit->Page_Terminate();
?>
