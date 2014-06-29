<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "item_contratadoinfo.php" ?>
<?php include_once "contratoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "item_contratado_valorgridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$item_contratado_edit = NULL; // Initialize page object first

class citem_contratado_edit extends citem_contratado {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'item_contratado';

	// Page object name
	var $PageObjName = 'item_contratado_edit';

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
	var $AuditTrailOnEdit = TRUE;

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

		// Table object (item_contratado)
		if (!isset($GLOBALS["item_contratado"])) {
			$GLOBALS["item_contratado"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["item_contratado"];
		}

		// Table object (contrato)
		if (!isset($GLOBALS['contrato'])) $GLOBALS['contrato'] = new ccontrato();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'item_contratado', TRUE);

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
			$this->Page_Terminate("item_contratadolist.php");
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
		if (@$_GET["nu_itemContratado"] <> "") {
			$this->nu_itemContratado->setQueryStringValue($_GET["nu_itemContratado"]);
		}

		// Set up master detail parameters
		$this->SetUpMasterParms();

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values

			// Set up detail parameters
			$this->SetUpDetailParms();
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->nu_itemContratado->CurrentValue == "")
			$this->Page_Terminate("item_contratadolist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("item_contratadolist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					if ($this->getCurrentDetailTable() <> "") // Master/detail edit
						$sReturnUrl = $this->GetDetailUrl();
					else
						$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "item_contratadoview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to View page directly
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed

					// Set up detail parameters
					$this->SetUpDetailParms();
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
		if (!$this->nu_contrato->FldIsDetailKey) {
			$this->nu_contrato->setFormValue($objForm->GetValue("x_nu_contrato"));
		}
		if (!$this->nu_itemOc->FldIsDetailKey) {
			$this->nu_itemOc->setFormValue($objForm->GetValue("x_nu_itemOc"));
		}
		if (!$this->no_itemContratado->FldIsDetailKey) {
			$this->no_itemContratado->setFormValue($objForm->GetValue("x_no_itemContratado"));
		}
		if (!$this->nu_unidade->FldIsDetailKey) {
			$this->nu_unidade->setFormValue($objForm->GetValue("x_nu_unidade"));
		}
		if (!$this->qt_maximo->FldIsDetailKey) {
			$this->qt_maximo->setFormValue($objForm->GetValue("x_qt_maximo"));
		}
		if (!$this->vr_maximo->FldIsDetailKey) {
			$this->vr_maximo->setFormValue($objForm->GetValue("x_vr_maximo"));
		}
		if (!$this->dt_inclusao->FldIsDetailKey) {
			$this->dt_inclusao->setFormValue($objForm->GetValue("x_dt_inclusao"));
			$this->dt_inclusao->CurrentValue = ew_UnFormatDateTime($this->dt_inclusao->CurrentValue, 7);
		}
		if (!$this->nu_itemContratado->FldIsDetailKey)
			$this->nu_itemContratado->setFormValue($objForm->GetValue("x_nu_itemContratado"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->nu_itemContratado->CurrentValue = $this->nu_itemContratado->FormValue;
		$this->nu_contrato->CurrentValue = $this->nu_contrato->FormValue;
		$this->nu_itemOc->CurrentValue = $this->nu_itemOc->FormValue;
		$this->no_itemContratado->CurrentValue = $this->no_itemContratado->FormValue;
		$this->nu_unidade->CurrentValue = $this->nu_unidade->FormValue;
		$this->qt_maximo->CurrentValue = $this->qt_maximo->FormValue;
		$this->vr_maximo->CurrentValue = $this->vr_maximo->FormValue;
		$this->dt_inclusao->CurrentValue = $this->dt_inclusao->FormValue;
		$this->dt_inclusao->CurrentValue = ew_UnFormatDateTime($this->dt_inclusao->CurrentValue, 7);
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
		$this->nu_itemContratado->setDbValue($rs->fields('nu_itemContratado'));
		$this->nu_contrato->setDbValue($rs->fields('nu_contrato'));
		$this->nu_itemOc->setDbValue($rs->fields('nu_itemOc'));
		$this->no_itemContratado->setDbValue($rs->fields('no_itemContratado'));
		$this->nu_unidade->setDbValue($rs->fields('nu_unidade'));
		$this->qt_maximo->setDbValue($rs->fields('qt_maximo'));
		$this->vr_maximo->setDbValue($rs->fields('vr_maximo'));
		$this->dt_inclusao->setDbValue($rs->fields('dt_inclusao'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_itemContratado->DbValue = $row['nu_itemContratado'];
		$this->nu_contrato->DbValue = $row['nu_contrato'];
		$this->nu_itemOc->DbValue = $row['nu_itemOc'];
		$this->no_itemContratado->DbValue = $row['no_itemContratado'];
		$this->nu_unidade->DbValue = $row['nu_unidade'];
		$this->qt_maximo->DbValue = $row['qt_maximo'];
		$this->vr_maximo->DbValue = $row['vr_maximo'];
		$this->dt_inclusao->DbValue = $row['dt_inclusao'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->qt_maximo->FormValue == $this->qt_maximo->CurrentValue && is_numeric(ew_StrToFloat($this->qt_maximo->CurrentValue)))
			$this->qt_maximo->CurrentValue = ew_StrToFloat($this->qt_maximo->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_maximo->FormValue == $this->vr_maximo->CurrentValue && is_numeric(ew_StrToFloat($this->vr_maximo->CurrentValue)))
			$this->vr_maximo->CurrentValue = ew_StrToFloat($this->vr_maximo->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_itemContratado
		// nu_contrato
		// nu_itemOc
		// no_itemContratado
		// nu_unidade
		// qt_maximo
		// vr_maximo
		// dt_inclusao

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_contrato
			if (strval($this->nu_contrato->CurrentValue) <> "") {
				$sFilterWrk = "[nu_contrato]" . ew_SearchString("=", $this->nu_contrato->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_contrato], [no_contrato] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[contrato]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_contrato, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_contrato] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_contrato->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_contrato->ViewValue = $this->nu_contrato->CurrentValue;
				}
			} else {
				$this->nu_contrato->ViewValue = NULL;
			}
			$this->nu_contrato->ViewCustomAttributes = "";

			// nu_itemOc
			if (strval($this->nu_itemOc->CurrentValue) <> "") {
				$sFilterWrk = "[nu_itemOc]" . ew_SearchString("=", $this->nu_itemOc->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_itemOc], [no_itemOc] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[itemoc]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_itemOc, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_itemOc] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_itemOc->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_itemOc->ViewValue = $this->nu_itemOc->CurrentValue;
				}
			} else {
				$this->nu_itemOc->ViewValue = NULL;
			}
			$this->nu_itemOc->ViewCustomAttributes = "";

			// no_itemContratado
			$this->no_itemContratado->ViewValue = $this->no_itemContratado->CurrentValue;
			$this->no_itemContratado->ViewCustomAttributes = "";

			// nu_unidade
			if (strval($this->nu_unidade->CurrentValue) <> "") {
				$sFilterWrk = "[nu_unidade]" . ew_SearchString("=", $this->nu_unidade->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_unidade], [no_unidade] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[unidade]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_unidade, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_unidade] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_unidade->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_unidade->ViewValue = $this->nu_unidade->CurrentValue;
				}
			} else {
				$this->nu_unidade->ViewValue = NULL;
			}
			$this->nu_unidade->ViewCustomAttributes = "";

			// qt_maximo
			$this->qt_maximo->ViewValue = $this->qt_maximo->CurrentValue;
			$this->qt_maximo->ViewCustomAttributes = "";

			// vr_maximo
			$this->vr_maximo->ViewValue = $this->vr_maximo->CurrentValue;
			$this->vr_maximo->ViewValue = ew_FormatCurrency($this->vr_maximo->ViewValue, 2, -2, -2, -2);
			$this->vr_maximo->ViewCustomAttributes = "";

			// dt_inclusao
			$this->dt_inclusao->ViewValue = $this->dt_inclusao->CurrentValue;
			$this->dt_inclusao->ViewValue = ew_FormatDateTime($this->dt_inclusao->ViewValue, 7);
			$this->dt_inclusao->ViewCustomAttributes = "";

			// nu_contrato
			$this->nu_contrato->LinkCustomAttributes = "";
			$this->nu_contrato->HrefValue = "";
			$this->nu_contrato->TooltipValue = "";

			// nu_itemOc
			$this->nu_itemOc->LinkCustomAttributes = "";
			$this->nu_itemOc->HrefValue = "";
			$this->nu_itemOc->TooltipValue = "";

			// no_itemContratado
			$this->no_itemContratado->LinkCustomAttributes = "";
			$this->no_itemContratado->HrefValue = "";
			$this->no_itemContratado->TooltipValue = "";

			// nu_unidade
			$this->nu_unidade->LinkCustomAttributes = "";
			$this->nu_unidade->HrefValue = "";
			$this->nu_unidade->TooltipValue = "";

			// qt_maximo
			$this->qt_maximo->LinkCustomAttributes = "";
			$this->qt_maximo->HrefValue = "";
			$this->qt_maximo->TooltipValue = "";

			// vr_maximo
			$this->vr_maximo->LinkCustomAttributes = "";
			$this->vr_maximo->HrefValue = "";
			$this->vr_maximo->TooltipValue = "";

			// dt_inclusao
			$this->dt_inclusao->LinkCustomAttributes = "";
			$this->dt_inclusao->HrefValue = "";
			$this->dt_inclusao->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// nu_contrato
			$this->nu_contrato->EditCustomAttributes = "";
			if (strval($this->nu_contrato->CurrentValue) <> "") {
				$sFilterWrk = "[nu_contrato]" . ew_SearchString("=", $this->nu_contrato->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_contrato], [no_contrato] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[contrato]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_contrato, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_contrato] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_contrato->EditValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_contrato->EditValue = $this->nu_contrato->CurrentValue;
				}
			} else {
				$this->nu_contrato->EditValue = NULL;
			}
			$this->nu_contrato->ViewCustomAttributes = "";

			// nu_itemOc
			$this->nu_itemOc->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_itemOc], [no_itemOc] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[itemoc]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_itemOc, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_itemOc] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_itemOc->EditValue = $arwrk;

			// no_itemContratado
			$this->no_itemContratado->EditCustomAttributes = "";
			$this->no_itemContratado->EditValue = ew_HtmlEncode($this->no_itemContratado->CurrentValue);
			$this->no_itemContratado->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_itemContratado->FldCaption()));

			// nu_unidade
			$this->nu_unidade->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_unidade], [no_unidade] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[unidade]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_unidade, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_unidade] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_unidade->EditValue = $arwrk;

			// qt_maximo
			$this->qt_maximo->EditCustomAttributes = "";
			$this->qt_maximo->EditValue = ew_HtmlEncode($this->qt_maximo->CurrentValue);
			$this->qt_maximo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_maximo->FldCaption()));
			if (strval($this->qt_maximo->EditValue) <> "" && is_numeric($this->qt_maximo->EditValue)) $this->qt_maximo->EditValue = ew_FormatNumber($this->qt_maximo->EditValue, -2, -1, -2, 0);

			// vr_maximo
			$this->vr_maximo->EditCustomAttributes = "";
			$this->vr_maximo->EditValue = ew_HtmlEncode($this->vr_maximo->CurrentValue);
			$this->vr_maximo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_maximo->FldCaption()));
			if (strval($this->vr_maximo->EditValue) <> "" && is_numeric($this->vr_maximo->EditValue)) $this->vr_maximo->EditValue = ew_FormatNumber($this->vr_maximo->EditValue, -2, -2, -2, -2);

			// dt_inclusao
			// Edit refer script
			// nu_contrato

			$this->nu_contrato->HrefValue = "";

			// nu_itemOc
			$this->nu_itemOc->HrefValue = "";

			// no_itemContratado
			$this->no_itemContratado->HrefValue = "";

			// nu_unidade
			$this->nu_unidade->HrefValue = "";

			// qt_maximo
			$this->qt_maximo->HrefValue = "";

			// vr_maximo
			$this->vr_maximo->HrefValue = "";

			// dt_inclusao
			$this->dt_inclusao->HrefValue = "";
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
		if (!$this->nu_itemOc->FldIsDetailKey && !is_null($this->nu_itemOc->FormValue) && $this->nu_itemOc->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_itemOc->FldCaption());
		}
		if (!$this->no_itemContratado->FldIsDetailKey && !is_null($this->no_itemContratado->FormValue) && $this->no_itemContratado->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_itemContratado->FldCaption());
		}
		if (!$this->nu_unidade->FldIsDetailKey && !is_null($this->nu_unidade->FormValue) && $this->nu_unidade->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_unidade->FldCaption());
		}
		if (!$this->qt_maximo->FldIsDetailKey && !is_null($this->qt_maximo->FormValue) && $this->qt_maximo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->qt_maximo->FldCaption());
		}
		if (!ew_CheckNumber($this->qt_maximo->FormValue)) {
			ew_AddMessage($gsFormError, $this->qt_maximo->FldErrMsg());
		}
		if (!$this->vr_maximo->FldIsDetailKey && !is_null($this->vr_maximo->FormValue) && $this->vr_maximo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->vr_maximo->FldCaption());
		}
		if (!ew_CheckNumber($this->vr_maximo->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_maximo->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("Item_contratado_valor", $DetailTblVar) && $GLOBALS["Item_contratado_valor"]->DetailEdit) {
			if (!isset($GLOBALS["Item_contratado_valor_grid"])) $GLOBALS["Item_contratado_valor_grid"] = new cItem_contratado_valor_grid(); // get detail page object
			$GLOBALS["Item_contratado_valor_grid"]->ValidateGridForm();
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

			// Begin transaction
			if ($this->getCurrentDetailTable() <> "")
				$conn->BeginTrans();

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// nu_itemOc
			$this->nu_itemOc->SetDbValueDef($rsnew, $this->nu_itemOc->CurrentValue, 0, $this->nu_itemOc->ReadOnly);

			// no_itemContratado
			$this->no_itemContratado->SetDbValueDef($rsnew, $this->no_itemContratado->CurrentValue, NULL, $this->no_itemContratado->ReadOnly);

			// nu_unidade
			$this->nu_unidade->SetDbValueDef($rsnew, $this->nu_unidade->CurrentValue, 0, $this->nu_unidade->ReadOnly);

			// qt_maximo
			$this->qt_maximo->SetDbValueDef($rsnew, $this->qt_maximo->CurrentValue, 0, $this->qt_maximo->ReadOnly);

			// vr_maximo
			$this->vr_maximo->SetDbValueDef($rsnew, $this->vr_maximo->CurrentValue, 0, $this->vr_maximo->ReadOnly);

			// dt_inclusao
			$this->dt_inclusao->SetDbValueDef($rsnew, ew_CurrentDate(), ew_CurrentDate());
			$rsnew['dt_inclusao'] = &$this->dt_inclusao->DbValue;

			// Check referential integrity for master table 'contrato'
			$bValidMasterRecord = TRUE;
			$sMasterFilter = $this->SqlMasterFilter_contrato();
			$KeyValue = isset($rsnew['nu_contrato']) ? $rsnew['nu_contrato'] : $rsold['nu_contrato'];
			if (strval($KeyValue) <> "") {
				$sMasterFilter = str_replace("@nu_contrato@", ew_AdjustSql($KeyValue), $sMasterFilter);
			} else {
				$bValidMasterRecord = FALSE;
			}
			if ($bValidMasterRecord) {
				$rsmaster = $GLOBALS["contrato"]->LoadRs($sMasterFilter);
				$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
				$rsmaster->Close();
			}
			if (!$bValidMasterRecord) {
				$sRelatedRecordMsg = str_replace("%t", "contrato", $Language->Phrase("RelatedRecordRequired"));
				$this->setFailureMessage($sRelatedRecordMsg);
				$rs->Close();
				return FALSE;
			}

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

				// Update detail records
				if ($EditRow) {
					$DetailTblVar = explode(",", $this->getCurrentDetailTable());
					if (in_array("Item_contratado_valor", $DetailTblVar) && $GLOBALS["Item_contratado_valor"]->DetailEdit) {
						if (!isset($GLOBALS["Item_contratado_valor_grid"])) $GLOBALS["Item_contratado_valor_grid"] = new cItem_contratado_valor_grid(); // Get detail page object
						$EditRow = $GLOBALS["Item_contratado_valor_grid"]->GridUpdate();
					}
				}

				// Commit/Rollback transaction
				if ($this->getCurrentDetailTable() <> "") {
					if ($EditRow) {
						$conn->CommitTrans(); // Commit transaction
					} else {
						$conn->RollbackTrans(); // Rollback transaction
					}
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
		if ($EditRow) {
			$this->WriteAuditTrailOnEdit($rsold, $rsnew);
		}
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
			if ($sMasterTblVar == "contrato") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_contrato"] <> "") {
					$GLOBALS["contrato"]->nu_contrato->setQueryStringValue($_GET["nu_contrato"]);
					$this->nu_contrato->setQueryStringValue($GLOBALS["contrato"]->nu_contrato->QueryStringValue);
					$this->nu_contrato->setSessionValue($this->nu_contrato->QueryStringValue);
					if (!is_numeric($GLOBALS["contrato"]->nu_contrato->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "contrato") {
				if ($this->nu_contrato->QueryStringValue == "") $this->nu_contrato->setSessionValue("");
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
			if (in_array("Item_contratado_valor", $DetailTblVar)) {
				if (!isset($GLOBALS["Item_contratado_valor_grid"]))
					$GLOBALS["Item_contratado_valor_grid"] = new cItem_contratado_valor_grid;
				if ($GLOBALS["Item_contratado_valor_grid"]->DetailEdit) {
					$GLOBALS["Item_contratado_valor_grid"]->CurrentMode = "edit";
					$GLOBALS["Item_contratado_valor_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["Item_contratado_valor_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["Item_contratado_valor_grid"]->setStartRecordNumber(1);
					$GLOBALS["Item_contratado_valor_grid"]->nu_itemContratado->FldIsDetailKey = TRUE;
					$GLOBALS["Item_contratado_valor_grid"]->nu_itemContratado->CurrentValue = $this->nu_itemContratado->CurrentValue;
					$GLOBALS["Item_contratado_valor_grid"]->nu_itemContratado->setSessionValue($GLOBALS["Item_contratado_valor_grid"]->nu_itemContratado->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "item_contratadolist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("edit");
		$Breadcrumb->Add("edit", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'item_contratado';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		if (!$this->AuditTrailOnEdit) return;
		$table = 'item_contratado';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['nu_itemContratado'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
	  $usr = CurrentUserID();
		foreach (array_keys($rsnew) as $fldname) {
			if ($this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_DATE) { // DateTime field
					$modified = (ew_FormatDateTime($rsold[$fldname], 0) <> ew_FormatDateTime($rsnew[$fldname], 0));
				} else {
					$modified = !ew_CompareValue($rsold[$fldname], $rsnew[$fldname]);
				}
				if ($modified) {
					if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) { // Memo field
						if (EW_AUDIT_TRAIL_TO_DATABASE) {
							$oldvalue = $rsold[$fldname];
							$newvalue = $rsnew[$fldname];
						} else {
							$oldvalue = "[MEMO]";
							$newvalue = "[MEMO]";
						}
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) { // XML field
						$oldvalue = "[XML]";
						$newvalue = "[XML]";
					} else {
						$oldvalue = $rsold[$fldname];
						$newvalue = $rsnew[$fldname];
					}
					ew_WriteAuditTrail("log", $dt, $id, $usr, "U", $table, $fldname, $key, $oldvalue, $newvalue);
				}
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
if (!isset($item_contratado_edit)) $item_contratado_edit = new citem_contratado_edit();

// Page init
$item_contratado_edit->Page_Init();

// Page main
$item_contratado_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$item_contratado_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var item_contratado_edit = new ew_Page("item_contratado_edit");
item_contratado_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = item_contratado_edit.PageID; // For backward compatibility

// Form object
var fitem_contratadoedit = new ew_Form("fitem_contratadoedit");

// Validate form
fitem_contratadoedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_itemOc");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($item_contratado->nu_itemOc->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no_itemContratado");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($item_contratado->no_itemContratado->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_unidade");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($item_contratado->nu_unidade->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_qt_maximo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($item_contratado->qt_maximo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_qt_maximo");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($item_contratado->qt_maximo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vr_maximo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($item_contratado->vr_maximo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_maximo");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($item_contratado->vr_maximo->FldErrMsg()) ?>");

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
fitem_contratadoedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fitem_contratadoedit.ValidateRequired = true;
<?php } else { ?>
fitem_contratadoedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fitem_contratadoedit.Lists["x_nu_contrato"] = {"LinkField":"x_nu_contrato","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_contrato","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fitem_contratadoedit.Lists["x_nu_itemOc"] = {"LinkField":"x_nu_itemOc","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_itemOc","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fitem_contratadoedit.Lists["x_nu_unidade"] = {"LinkField":"x_nu_unidade","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_unidade","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $item_contratado_edit->ShowPageHeader(); ?>
<?php
$item_contratado_edit->ShowMessage();
?>
<form name="fitem_contratadoedit" id="fitem_contratadoedit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="item_contratado">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_item_contratadoedit" class="table table-bordered table-striped">
<?php if ($item_contratado->nu_contrato->Visible) { // nu_contrato ?>
	<tr id="r_nu_contrato">
		<td><span id="elh_item_contratado_nu_contrato"><?php echo $item_contratado->nu_contrato->FldCaption() ?></span></td>
		<td<?php echo $item_contratado->nu_contrato->CellAttributes() ?>>
<span id="el_item_contratado_nu_contrato" class="control-group">
<span<?php echo $item_contratado->nu_contrato->ViewAttributes() ?>>
<?php echo $item_contratado->nu_contrato->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_contrato" name="x_nu_contrato" id="x_nu_contrato" value="<?php echo ew_HtmlEncode($item_contratado->nu_contrato->CurrentValue) ?>">
<?php echo $item_contratado->nu_contrato->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($item_contratado->nu_itemOc->Visible) { // nu_itemOc ?>
	<tr id="r_nu_itemOc">
		<td><span id="elh_item_contratado_nu_itemOc"><?php echo $item_contratado->nu_itemOc->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $item_contratado->nu_itemOc->CellAttributes() ?>>
<span id="el_item_contratado_nu_itemOc" class="control-group">
<select data-field="x_nu_itemOc" id="x_nu_itemOc" name="x_nu_itemOc"<?php echo $item_contratado->nu_itemOc->EditAttributes() ?>>
<?php
if (is_array($item_contratado->nu_itemOc->EditValue)) {
	$arwrk = $item_contratado->nu_itemOc->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($item_contratado->nu_itemOc->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fitem_contratadoedit.Lists["x_nu_itemOc"].Options = <?php echo (is_array($item_contratado->nu_itemOc->EditValue)) ? ew_ArrayToJson($item_contratado->nu_itemOc->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $item_contratado->nu_itemOc->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($item_contratado->no_itemContratado->Visible) { // no_itemContratado ?>
	<tr id="r_no_itemContratado">
		<td><span id="elh_item_contratado_no_itemContratado"><?php echo $item_contratado->no_itemContratado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $item_contratado->no_itemContratado->CellAttributes() ?>>
<span id="el_item_contratado_no_itemContratado" class="control-group">
<input type="text" data-field="x_no_itemContratado" name="x_no_itemContratado" id="x_no_itemContratado" size="30" maxlength="100" placeholder="<?php echo $item_contratado->no_itemContratado->PlaceHolder ?>" value="<?php echo $item_contratado->no_itemContratado->EditValue ?>"<?php echo $item_contratado->no_itemContratado->EditAttributes() ?>>
</span>
<?php echo $item_contratado->no_itemContratado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($item_contratado->nu_unidade->Visible) { // nu_unidade ?>
	<tr id="r_nu_unidade">
		<td><span id="elh_item_contratado_nu_unidade"><?php echo $item_contratado->nu_unidade->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $item_contratado->nu_unidade->CellAttributes() ?>>
<span id="el_item_contratado_nu_unidade" class="control-group">
<select data-field="x_nu_unidade" id="x_nu_unidade" name="x_nu_unidade"<?php echo $item_contratado->nu_unidade->EditAttributes() ?>>
<?php
if (is_array($item_contratado->nu_unidade->EditValue)) {
	$arwrk = $item_contratado->nu_unidade->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($item_contratado->nu_unidade->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fitem_contratadoedit.Lists["x_nu_unidade"].Options = <?php echo (is_array($item_contratado->nu_unidade->EditValue)) ? ew_ArrayToJson($item_contratado->nu_unidade->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $item_contratado->nu_unidade->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($item_contratado->qt_maximo->Visible) { // qt_maximo ?>
	<tr id="r_qt_maximo">
		<td><span id="elh_item_contratado_qt_maximo"><?php echo $item_contratado->qt_maximo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $item_contratado->qt_maximo->CellAttributes() ?>>
<span id="el_item_contratado_qt_maximo" class="control-group">
<input type="text" data-field="x_qt_maximo" name="x_qt_maximo" id="x_qt_maximo" size="30" placeholder="<?php echo $item_contratado->qt_maximo->PlaceHolder ?>" value="<?php echo $item_contratado->qt_maximo->EditValue ?>"<?php echo $item_contratado->qt_maximo->EditAttributes() ?>>
</span>
<?php echo $item_contratado->qt_maximo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($item_contratado->vr_maximo->Visible) { // vr_maximo ?>
	<tr id="r_vr_maximo">
		<td><span id="elh_item_contratado_vr_maximo"><?php echo $item_contratado->vr_maximo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $item_contratado->vr_maximo->CellAttributes() ?>>
<span id="el_item_contratado_vr_maximo" class="control-group">
<input type="text" data-field="x_vr_maximo" name="x_vr_maximo" id="x_vr_maximo" size="30" placeholder="<?php echo $item_contratado->vr_maximo->PlaceHolder ?>" value="<?php echo $item_contratado->vr_maximo->EditValue ?>"<?php echo $item_contratado->vr_maximo->EditAttributes() ?>>
</span>
<?php echo $item_contratado->vr_maximo->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<input type="hidden" data-field="x_nu_itemContratado" name="x_nu_itemContratado" id="x_nu_itemContratado" value="<?php echo ew_HtmlEncode($item_contratado->nu_itemContratado->CurrentValue) ?>">
<?php
	if (in_array("Item_contratado_valor", explode(",", $item_contratado->getCurrentDetailTable())) && $Item_contratado_valor->DetailEdit) {
?>
<?php include_once "item_contratado_valorgrid.php" ?>
<?php } ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fitem_contratadoedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$item_contratado_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$item_contratado_edit->Page_Terminate();
?>
