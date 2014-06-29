<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "gc_membroinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$gc_membro_edit = NULL; // Initialize page object first

class cgc_membro_edit extends cgc_membro {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'gc_membro';

	// Page object name
	var $PageObjName = 'gc_membro_edit';

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

		// Table object (gc_membro)
		if (!isset($GLOBALS["gc_membro"])) {
			$GLOBALS["gc_membro"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["gc_membro"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'gc_membro', TRUE);

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
			$this->Page_Terminate("gc_membrolist.php");
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
		if (@$_GET["nu_membro"] <> "") {
			$this->nu_membro->setQueryStringValue($_GET["nu_membro"]);
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
		if ($this->nu_membro->CurrentValue == "")
			$this->Page_Terminate("gc_membrolist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("gc_membrolist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "gc_membroview.php")
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
		if (!$this->nu_grupoOuComite->FldIsDetailKey) {
			$this->nu_grupoOuComite->setFormValue($objForm->GetValue("x_nu_grupoOuComite"));
		}
		if (!$this->nu_pessoa->FldIsDetailKey) {
			$this->nu_pessoa->setFormValue($objForm->GetValue("x_nu_pessoa"));
		}
		if (!$this->nu_papel->FldIsDetailKey) {
			$this->nu_papel->setFormValue($objForm->GetValue("x_nu_papel"));
		}
		if (!$this->dt_inicio->FldIsDetailKey) {
			$this->dt_inicio->setFormValue($objForm->GetValue("x_dt_inicio"));
			$this->dt_inicio->CurrentValue = ew_UnFormatDateTime($this->dt_inicio->CurrentValue, 7);
		}
		if (!$this->dt_fim->FldIsDetailKey) {
			$this->dt_fim->setFormValue($objForm->GetValue("x_dt_fim"));
			$this->dt_fim->CurrentValue = ew_UnFormatDateTime($this->dt_fim->CurrentValue, 7);
		}
		if (!$this->ic_ativo->FldIsDetailKey) {
			$this->ic_ativo->setFormValue($objForm->GetValue("x_ic_ativo"));
		}
		if (!$this->nu_membro->FldIsDetailKey)
			$this->nu_membro->setFormValue($objForm->GetValue("x_nu_membro"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->nu_membro->CurrentValue = $this->nu_membro->FormValue;
		$this->nu_grupoOuComite->CurrentValue = $this->nu_grupoOuComite->FormValue;
		$this->nu_pessoa->CurrentValue = $this->nu_pessoa->FormValue;
		$this->nu_papel->CurrentValue = $this->nu_papel->FormValue;
		$this->dt_inicio->CurrentValue = $this->dt_inicio->FormValue;
		$this->dt_inicio->CurrentValue = ew_UnFormatDateTime($this->dt_inicio->CurrentValue, 7);
		$this->dt_fim->CurrentValue = $this->dt_fim->FormValue;
		$this->dt_fim->CurrentValue = ew_UnFormatDateTime($this->dt_fim->CurrentValue, 7);
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
		$this->nu_membro->setDbValue($rs->fields('nu_membro'));
		$this->nu_grupoOuComite->setDbValue($rs->fields('nu_grupoOuComite'));
		$this->nu_pessoa->setDbValue($rs->fields('nu_pessoa'));
		$this->nu_papel->setDbValue($rs->fields('nu_papel'));
		$this->dt_inicio->setDbValue($rs->fields('dt_inicio'));
		$this->dt_fim->setDbValue($rs->fields('dt_fim'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
		$this->nu_usuario->setDbValue($rs->fields('nu_usuario'));
		$this->ts_datahora->setDbValue($rs->fields('ts_datahora'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_membro->DbValue = $row['nu_membro'];
		$this->nu_grupoOuComite->DbValue = $row['nu_grupoOuComite'];
		$this->nu_pessoa->DbValue = $row['nu_pessoa'];
		$this->nu_papel->DbValue = $row['nu_papel'];
		$this->dt_inicio->DbValue = $row['dt_inicio'];
		$this->dt_fim->DbValue = $row['dt_fim'];
		$this->ic_ativo->DbValue = $row['ic_ativo'];
		$this->nu_usuario->DbValue = $row['nu_usuario'];
		$this->ts_datahora->DbValue = $row['ts_datahora'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_membro
		// nu_grupoOuComite
		// nu_pessoa
		// nu_papel
		// dt_inicio
		// dt_fim
		// ic_ativo
		// nu_usuario
		// ts_datahora

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_membro
			$this->nu_membro->ViewValue = $this->nu_membro->CurrentValue;
			$this->nu_membro->ViewCustomAttributes = "";

			// nu_grupoOuComite
			if (strval($this->nu_grupoOuComite->CurrentValue) <> "") {
				$sFilterWrk = "[nu_gpComite]" . ew_SearchString("=", $this->nu_grupoOuComite->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_gpComite], [no_gpComite] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[gpcomite]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_grupoOuComite, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_grupoOuComite->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_grupoOuComite->ViewValue = $this->nu_grupoOuComite->CurrentValue;
				}
			} else {
				$this->nu_grupoOuComite->ViewValue = NULL;
			}
			$this->nu_grupoOuComite->ViewCustomAttributes = "";

			// nu_pessoa
			if (strval($this->nu_pessoa->CurrentValue) <> "") {
				$sFilterWrk = "[nu_pessoa]" . ew_SearchString("=", $this->nu_pessoa->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_pessoa], [no_pessoa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[pessoa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_pessoa, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_pessoa->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_pessoa->ViewValue = $this->nu_pessoa->CurrentValue;
				}
			} else {
				$this->nu_pessoa->ViewValue = NULL;
			}
			$this->nu_pessoa->ViewCustomAttributes = "";

			// nu_papel
			if (strval($this->nu_papel->CurrentValue) <> "") {
				$sFilterWrk = "[co_papel]" . ew_SearchString("=", $this->nu_papel->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [co_papel], [no_papel] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[papel]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_papel, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_papel] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_papel->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_papel->ViewValue = $this->nu_papel->CurrentValue;
				}
			} else {
				$this->nu_papel->ViewValue = NULL;
			}
			$this->nu_papel->ViewCustomAttributes = "";

			// dt_inicio
			$this->dt_inicio->ViewValue = $this->dt_inicio->CurrentValue;
			$this->dt_inicio->ViewValue = ew_FormatDateTime($this->dt_inicio->ViewValue, 7);
			$this->dt_inicio->ViewCustomAttributes = "";

			// dt_fim
			$this->dt_fim->ViewValue = $this->dt_fim->CurrentValue;
			$this->dt_fim->ViewValue = ew_FormatDateTime($this->dt_fim->ViewValue, 7);
			$this->dt_fim->ViewCustomAttributes = "";

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

			// nu_usuario
			$this->nu_usuario->ViewValue = $this->nu_usuario->CurrentValue;
			$this->nu_usuario->ViewCustomAttributes = "";

			// ts_datahora
			$this->ts_datahora->ViewValue = $this->ts_datahora->CurrentValue;
			$this->ts_datahora->ViewValue = ew_FormatDateTime($this->ts_datahora->ViewValue, 7);
			$this->ts_datahora->ViewCustomAttributes = "";

			// nu_grupoOuComite
			$this->nu_grupoOuComite->LinkCustomAttributes = "";
			$this->nu_grupoOuComite->HrefValue = "";
			$this->nu_grupoOuComite->TooltipValue = "";

			// nu_pessoa
			$this->nu_pessoa->LinkCustomAttributes = "";
			$this->nu_pessoa->HrefValue = "";
			$this->nu_pessoa->TooltipValue = "";

			// nu_papel
			$this->nu_papel->LinkCustomAttributes = "";
			$this->nu_papel->HrefValue = "";
			$this->nu_papel->TooltipValue = "";

			// dt_inicio
			$this->dt_inicio->LinkCustomAttributes = "";
			$this->dt_inicio->HrefValue = "";
			$this->dt_inicio->TooltipValue = "";

			// dt_fim
			$this->dt_fim->LinkCustomAttributes = "";
			$this->dt_fim->HrefValue = "";
			$this->dt_fim->TooltipValue = "";

			// ic_ativo
			$this->ic_ativo->LinkCustomAttributes = "";
			$this->ic_ativo->HrefValue = "";
			$this->ic_ativo->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// nu_grupoOuComite
			$this->nu_grupoOuComite->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_gpComite], [no_gpComite] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[gpcomite]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_grupoOuComite, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_grupoOuComite->EditValue = $arwrk;

			// nu_pessoa
			$this->nu_pessoa->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_pessoa], [no_pessoa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[pessoa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_pessoa, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_pessoa->EditValue = $arwrk;

			// nu_papel
			$this->nu_papel->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [co_papel], [no_papel] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[papel]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_papel, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_papel] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_papel->EditValue = $arwrk;

			// dt_inicio
			$this->dt_inicio->EditCustomAttributes = "";
			$this->dt_inicio->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->dt_inicio->CurrentValue, 7));
			$this->dt_inicio->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dt_inicio->FldCaption()));

			// dt_fim
			$this->dt_fim->EditCustomAttributes = "";
			$this->dt_fim->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->dt_fim->CurrentValue, 7));
			$this->dt_fim->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dt_fim->FldCaption()));

			// ic_ativo
			$this->ic_ativo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_ativo->FldTagValue(1), $this->ic_ativo->FldTagCaption(1) <> "" ? $this->ic_ativo->FldTagCaption(1) : $this->ic_ativo->FldTagValue(1));
			$arwrk[] = array($this->ic_ativo->FldTagValue(2), $this->ic_ativo->FldTagCaption(2) <> "" ? $this->ic_ativo->FldTagCaption(2) : $this->ic_ativo->FldTagValue(2));
			$this->ic_ativo->EditValue = $arwrk;

			// Edit refer script
			// nu_grupoOuComite

			$this->nu_grupoOuComite->HrefValue = "";

			// nu_pessoa
			$this->nu_pessoa->HrefValue = "";

			// nu_papel
			$this->nu_papel->HrefValue = "";

			// dt_inicio
			$this->dt_inicio->HrefValue = "";

			// dt_fim
			$this->dt_fim->HrefValue = "";

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
		if (!$this->nu_grupoOuComite->FldIsDetailKey && !is_null($this->nu_grupoOuComite->FormValue) && $this->nu_grupoOuComite->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_grupoOuComite->FldCaption());
		}
		if (!$this->nu_pessoa->FldIsDetailKey && !is_null($this->nu_pessoa->FormValue) && $this->nu_pessoa->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_pessoa->FldCaption());
		}
		if (!$this->nu_papel->FldIsDetailKey && !is_null($this->nu_papel->FormValue) && $this->nu_papel->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_papel->FldCaption());
		}
		if (!$this->dt_inicio->FldIsDetailKey && !is_null($this->dt_inicio->FormValue) && $this->dt_inicio->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->dt_inicio->FldCaption());
		}
		if (!ew_CheckEuroDate($this->dt_inicio->FormValue)) {
			ew_AddMessage($gsFormError, $this->dt_inicio->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->dt_fim->FormValue)) {
			ew_AddMessage($gsFormError, $this->dt_fim->FldErrMsg());
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

			// nu_grupoOuComite
			$this->nu_grupoOuComite->SetDbValueDef($rsnew, $this->nu_grupoOuComite->CurrentValue, NULL, $this->nu_grupoOuComite->ReadOnly);

			// nu_pessoa
			$this->nu_pessoa->SetDbValueDef($rsnew, $this->nu_pessoa->CurrentValue, 0, $this->nu_pessoa->ReadOnly);

			// nu_papel
			$this->nu_papel->SetDbValueDef($rsnew, $this->nu_papel->CurrentValue, 0, $this->nu_papel->ReadOnly);

			// dt_inicio
			$this->dt_inicio->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->dt_inicio->CurrentValue, 7), NULL, $this->dt_inicio->ReadOnly);

			// dt_fim
			$this->dt_fim->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->dt_fim->CurrentValue, 7), NULL, $this->dt_fim->ReadOnly);

			// ic_ativo
			$this->ic_ativo->SetDbValueDef($rsnew, $this->ic_ativo->CurrentValue, NULL, $this->ic_ativo->ReadOnly);

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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "gc_membrolist.php", $this->TableVar);
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
if (!isset($gc_membro_edit)) $gc_membro_edit = new cgc_membro_edit();

// Page init
$gc_membro_edit->Page_Init();

// Page main
$gc_membro_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$gc_membro_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var gc_membro_edit = new ew_Page("gc_membro_edit");
gc_membro_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = gc_membro_edit.PageID; // For backward compatibility

// Form object
var fgc_membroedit = new ew_Form("fgc_membroedit");

// Validate form
fgc_membroedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_grupoOuComite");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($gc_membro->nu_grupoOuComite->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_pessoa");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($gc_membro->nu_pessoa->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_papel");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($gc_membro->nu_papel->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dt_inicio");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($gc_membro->dt_inicio->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dt_inicio");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($gc_membro->dt_inicio->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_dt_fim");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($gc_membro->dt_fim->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ic_ativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($gc_membro->ic_ativo->FldCaption()) ?>");

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
fgc_membroedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgc_membroedit.ValidateRequired = true;
<?php } else { ?>
fgc_membroedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fgc_membroedit.Lists["x_nu_grupoOuComite"] = {"LinkField":"x_nu_gpComite","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_gpComite","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgc_membroedit.Lists["x_nu_pessoa"] = {"LinkField":"x_nu_pessoa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_pessoa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgc_membroedit.Lists["x_nu_papel"] = {"LinkField":"x_co_papel","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_papel","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $gc_membro_edit->ShowPageHeader(); ?>
<?php
$gc_membro_edit->ShowMessage();
?>
<form name="fgc_membroedit" id="fgc_membroedit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="gc_membro">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_gc_membroedit" class="table table-bordered table-striped">
<?php if ($gc_membro->nu_grupoOuComite->Visible) { // nu_grupoOuComite ?>
	<tr id="r_nu_grupoOuComite">
		<td><span id="elh_gc_membro_nu_grupoOuComite"><?php echo $gc_membro->nu_grupoOuComite->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $gc_membro->nu_grupoOuComite->CellAttributes() ?>>
<span id="el_gc_membro_nu_grupoOuComite" class="control-group">
<select data-field="x_nu_grupoOuComite" id="x_nu_grupoOuComite" name="x_nu_grupoOuComite"<?php echo $gc_membro->nu_grupoOuComite->EditAttributes() ?>>
<?php
if (is_array($gc_membro->nu_grupoOuComite->EditValue)) {
	$arwrk = $gc_membro->nu_grupoOuComite->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($gc_membro->nu_grupoOuComite->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fgc_membroedit.Lists["x_nu_grupoOuComite"].Options = <?php echo (is_array($gc_membro->nu_grupoOuComite->EditValue)) ? ew_ArrayToJson($gc_membro->nu_grupoOuComite->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $gc_membro->nu_grupoOuComite->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gc_membro->nu_pessoa->Visible) { // nu_pessoa ?>
	<tr id="r_nu_pessoa">
		<td><span id="elh_gc_membro_nu_pessoa"><?php echo $gc_membro->nu_pessoa->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $gc_membro->nu_pessoa->CellAttributes() ?>>
<span id="el_gc_membro_nu_pessoa" class="control-group">
<select data-field="x_nu_pessoa" id="x_nu_pessoa" name="x_nu_pessoa"<?php echo $gc_membro->nu_pessoa->EditAttributes() ?>>
<?php
if (is_array($gc_membro->nu_pessoa->EditValue)) {
	$arwrk = $gc_membro->nu_pessoa->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($gc_membro->nu_pessoa->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if (AllowAdd(CurrentProjectID() . "pessoa")) { ?>
&nbsp;<a id="aol_x_nu_pessoa" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_nu_pessoa',url:'pessoaaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $gc_membro->nu_pessoa->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
fgc_membroedit.Lists["x_nu_pessoa"].Options = <?php echo (is_array($gc_membro->nu_pessoa->EditValue)) ? ew_ArrayToJson($gc_membro->nu_pessoa->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $gc_membro->nu_pessoa->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gc_membro->nu_papel->Visible) { // nu_papel ?>
	<tr id="r_nu_papel">
		<td><span id="elh_gc_membro_nu_papel"><?php echo $gc_membro->nu_papel->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $gc_membro->nu_papel->CellAttributes() ?>>
<span id="el_gc_membro_nu_papel" class="control-group">
<select data-field="x_nu_papel" id="x_nu_papel" name="x_nu_papel"<?php echo $gc_membro->nu_papel->EditAttributes() ?>>
<?php
if (is_array($gc_membro->nu_papel->EditValue)) {
	$arwrk = $gc_membro->nu_papel->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($gc_membro->nu_papel->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if (AllowAdd(CurrentProjectID() . "papel")) { ?>
&nbsp;<a id="aol_x_nu_papel" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_nu_papel',url:'papeladdopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $gc_membro->nu_papel->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
fgc_membroedit.Lists["x_nu_papel"].Options = <?php echo (is_array($gc_membro->nu_papel->EditValue)) ? ew_ArrayToJson($gc_membro->nu_papel->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $gc_membro->nu_papel->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gc_membro->dt_inicio->Visible) { // dt_inicio ?>
	<tr id="r_dt_inicio">
		<td><span id="elh_gc_membro_dt_inicio"><?php echo $gc_membro->dt_inicio->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $gc_membro->dt_inicio->CellAttributes() ?>>
<span id="el_gc_membro_dt_inicio" class="control-group">
<input type="text" data-field="x_dt_inicio" name="x_dt_inicio" id="x_dt_inicio" placeholder="<?php echo $gc_membro->dt_inicio->PlaceHolder ?>" value="<?php echo $gc_membro->dt_inicio->EditValue ?>"<?php echo $gc_membro->dt_inicio->EditAttributes() ?>>
<?php if (!$gc_membro->dt_inicio->ReadOnly && !$gc_membro->dt_inicio->Disabled && @$gc_membro->dt_inicio->EditAttrs["readonly"] == "" && @$gc_membro->dt_inicio->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_dt_inicio" name="cal_x_dt_inicio" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x_dt_inicio" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fgc_membroedit", "x_dt_inicio", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $gc_membro->dt_inicio->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gc_membro->dt_fim->Visible) { // dt_fim ?>
	<tr id="r_dt_fim">
		<td><span id="elh_gc_membro_dt_fim"><?php echo $gc_membro->dt_fim->FldCaption() ?></span></td>
		<td<?php echo $gc_membro->dt_fim->CellAttributes() ?>>
<span id="el_gc_membro_dt_fim" class="control-group">
<input type="text" data-field="x_dt_fim" name="x_dt_fim" id="x_dt_fim" placeholder="<?php echo $gc_membro->dt_fim->PlaceHolder ?>" value="<?php echo $gc_membro->dt_fim->EditValue ?>"<?php echo $gc_membro->dt_fim->EditAttributes() ?>>
<?php if (!$gc_membro->dt_fim->ReadOnly && !$gc_membro->dt_fim->Disabled && @$gc_membro->dt_fim->EditAttrs["readonly"] == "" && @$gc_membro->dt_fim->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_dt_fim" name="cal_x_dt_fim" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x_dt_fim" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fgc_membroedit", "x_dt_fim", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $gc_membro->dt_fim->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gc_membro->ic_ativo->Visible) { // ic_ativo ?>
	<tr id="r_ic_ativo">
		<td><span id="elh_gc_membro_ic_ativo"><?php echo $gc_membro->ic_ativo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $gc_membro->ic_ativo->CellAttributes() ?>>
<span id="el_gc_membro_ic_ativo" class="control-group">
<div id="tp_x_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_ativo" id="x_ic_ativo" value="{value}"<?php echo $gc_membro->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $gc_membro->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($gc_membro->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x_ic_ativo" id="x_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $gc_membro->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $gc_membro->ic_ativo->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<input type="hidden" data-field="x_nu_membro" name="x_nu_membro" id="x_nu_membro" value="<?php echo ew_HtmlEncode($gc_membro->nu_membro->CurrentValue) ?>">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fgc_membroedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$gc_membro_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$gc_membro_edit->Page_Terminate();
?>
