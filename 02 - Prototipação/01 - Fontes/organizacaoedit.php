<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "organizacaoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "areagridcls.php" ?>
<?php include_once "pargeraisgridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$organizacao_edit = NULL; // Initialize page object first

class corganizacao_edit extends corganizacao {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'organizacao';

	// Page object name
	var $PageObjName = 'organizacao_edit';

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

		// Table object (organizacao)
		if (!isset($GLOBALS["organizacao"])) {
			$GLOBALS["organizacao"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["organizacao"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'organizacao', TRUE);

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
			$this->Page_Terminate("organizacaolist.php");
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
		if (@$_GET["nu_organizacao"] <> "") {
			$this->nu_organizacao->setQueryStringValue($_GET["nu_organizacao"]);
		}

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
		if ($this->nu_organizacao->CurrentValue == "")
			$this->Page_Terminate("organizacaolist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("organizacaolist.php"); // No matching record, return to list
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
					if (ew_GetPageName($sReturnUrl) == "organizacaoview.php")
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
		if (!$this->no_organizacao->FldIsDetailKey) {
			$this->no_organizacao->setFormValue($objForm->GetValue("x_no_organizacao"));
		}
		if (!$this->ds_organizacao->FldIsDetailKey) {
			$this->ds_organizacao->setFormValue($objForm->GetValue("x_ds_organizacao"));
		}
		if (!$this->nu_verticalNegocio->FldIsDetailKey) {
			$this->nu_verticalNegocio->setFormValue($objForm->GetValue("x_nu_verticalNegocio"));
		}
		if (!$this->ic_ativo->FldIsDetailKey) {
			$this->ic_ativo->setFormValue($objForm->GetValue("x_ic_ativo"));
		}
		if (!$this->ic_padrao->FldIsDetailKey) {
			$this->ic_padrao->setFormValue($objForm->GetValue("x_ic_padrao"));
		}
		if (!$this->nu_organizacao->FldIsDetailKey)
			$this->nu_organizacao->setFormValue($objForm->GetValue("x_nu_organizacao"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->nu_organizacao->CurrentValue = $this->nu_organizacao->FormValue;
		$this->no_organizacao->CurrentValue = $this->no_organizacao->FormValue;
		$this->ds_organizacao->CurrentValue = $this->ds_organizacao->FormValue;
		$this->nu_verticalNegocio->CurrentValue = $this->nu_verticalNegocio->FormValue;
		$this->ic_ativo->CurrentValue = $this->ic_ativo->FormValue;
		$this->ic_padrao->CurrentValue = $this->ic_padrao->FormValue;
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
		$this->nu_organizacao->setDbValue($rs->fields('nu_organizacao'));
		$this->no_organizacao->setDbValue($rs->fields('no_organizacao'));
		$this->ds_organizacao->setDbValue($rs->fields('ds_organizacao'));
		$this->nu_verticalNegocio->setDbValue($rs->fields('nu_verticalNegocio'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
		$this->ic_padrao->setDbValue($rs->fields('ic_padrao'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_organizacao->DbValue = $row['nu_organizacao'];
		$this->no_organizacao->DbValue = $row['no_organizacao'];
		$this->ds_organizacao->DbValue = $row['ds_organizacao'];
		$this->nu_verticalNegocio->DbValue = $row['nu_verticalNegocio'];
		$this->ic_ativo->DbValue = $row['ic_ativo'];
		$this->ic_padrao->DbValue = $row['ic_padrao'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_organizacao
		// no_organizacao
		// ds_organizacao
		// nu_verticalNegocio
		// ic_ativo
		// ic_padrao

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// no_organizacao
			$this->no_organizacao->ViewValue = $this->no_organizacao->CurrentValue;
			$this->no_organizacao->ViewCustomAttributes = "";

			// ds_organizacao
			$this->ds_organizacao->ViewValue = $this->ds_organizacao->CurrentValue;
			$this->ds_organizacao->ViewCustomAttributes = "";

			// nu_verticalNegocio
			if (strval($this->nu_verticalNegocio->CurrentValue) <> "") {
				$sFilterWrk = "[nu_verticalNegocio]" . ew_SearchString("=", $this->nu_verticalNegocio->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_verticalNegocio], [no_vertical] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[verticalnegocio]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_verticalNegocio, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_vertical] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_verticalNegocio->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_verticalNegocio->ViewValue = $this->nu_verticalNegocio->CurrentValue;
				}
			} else {
				$this->nu_verticalNegocio->ViewValue = NULL;
			}
			$this->nu_verticalNegocio->ViewCustomAttributes = "";

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

			// ic_padrao
			if (strval($this->ic_padrao->CurrentValue) <> "") {
				switch ($this->ic_padrao->CurrentValue) {
					case $this->ic_padrao->FldTagValue(1):
						$this->ic_padrao->ViewValue = $this->ic_padrao->FldTagCaption(1) <> "" ? $this->ic_padrao->FldTagCaption(1) : $this->ic_padrao->CurrentValue;
						break;
					case $this->ic_padrao->FldTagValue(2):
						$this->ic_padrao->ViewValue = $this->ic_padrao->FldTagCaption(2) <> "" ? $this->ic_padrao->FldTagCaption(2) : $this->ic_padrao->CurrentValue;
						break;
					case $this->ic_padrao->FldTagValue(3):
						$this->ic_padrao->ViewValue = $this->ic_padrao->FldTagCaption(3) <> "" ? $this->ic_padrao->FldTagCaption(3) : $this->ic_padrao->CurrentValue;
						break;
					default:
						$this->ic_padrao->ViewValue = $this->ic_padrao->CurrentValue;
				}
			} else {
				$this->ic_padrao->ViewValue = NULL;
			}
			$this->ic_padrao->ViewCustomAttributes = "";

			// no_organizacao
			$this->no_organizacao->LinkCustomAttributes = "";
			$this->no_organizacao->HrefValue = "";
			$this->no_organizacao->TooltipValue = "";

			// ds_organizacao
			$this->ds_organizacao->LinkCustomAttributes = "";
			$this->ds_organizacao->HrefValue = "";
			$this->ds_organizacao->TooltipValue = "";

			// nu_verticalNegocio
			$this->nu_verticalNegocio->LinkCustomAttributes = "";
			$this->nu_verticalNegocio->HrefValue = "";
			$this->nu_verticalNegocio->TooltipValue = "";

			// ic_ativo
			$this->ic_ativo->LinkCustomAttributes = "";
			$this->ic_ativo->HrefValue = "";
			$this->ic_ativo->TooltipValue = "";

			// ic_padrao
			$this->ic_padrao->LinkCustomAttributes = "";
			$this->ic_padrao->HrefValue = "";
			$this->ic_padrao->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// no_organizacao
			$this->no_organizacao->EditCustomAttributes = "";
			$this->no_organizacao->EditValue = ew_HtmlEncode($this->no_organizacao->CurrentValue);
			$this->no_organizacao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_organizacao->FldCaption()));

			// ds_organizacao
			$this->ds_organizacao->EditCustomAttributes = "";
			$this->ds_organizacao->EditValue = $this->ds_organizacao->CurrentValue;
			$this->ds_organizacao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_organizacao->FldCaption()));

			// nu_verticalNegocio
			$this->nu_verticalNegocio->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_verticalNegocio], [no_vertical] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[verticalnegocio]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_verticalNegocio, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_vertical] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_verticalNegocio->EditValue = $arwrk;

			// ic_ativo
			$this->ic_ativo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_ativo->FldTagValue(1), $this->ic_ativo->FldTagCaption(1) <> "" ? $this->ic_ativo->FldTagCaption(1) : $this->ic_ativo->FldTagValue(1));
			$arwrk[] = array($this->ic_ativo->FldTagValue(2), $this->ic_ativo->FldTagCaption(2) <> "" ? $this->ic_ativo->FldTagCaption(2) : $this->ic_ativo->FldTagValue(2));
			$this->ic_ativo->EditValue = $arwrk;

			// ic_padrao
			$this->ic_padrao->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_padrao->FldTagValue(1), $this->ic_padrao->FldTagCaption(1) <> "" ? $this->ic_padrao->FldTagCaption(1) : $this->ic_padrao->FldTagValue(1));
			$arwrk[] = array($this->ic_padrao->FldTagValue(2), $this->ic_padrao->FldTagCaption(2) <> "" ? $this->ic_padrao->FldTagCaption(2) : $this->ic_padrao->FldTagValue(2));
			$arwrk[] = array($this->ic_padrao->FldTagValue(3), $this->ic_padrao->FldTagCaption(3) <> "" ? $this->ic_padrao->FldTagCaption(3) : $this->ic_padrao->FldTagValue(3));
			$this->ic_padrao->EditValue = $arwrk;

			// Edit refer script
			// no_organizacao

			$this->no_organizacao->HrefValue = "";

			// ds_organizacao
			$this->ds_organizacao->HrefValue = "";

			// nu_verticalNegocio
			$this->nu_verticalNegocio->HrefValue = "";

			// ic_ativo
			$this->ic_ativo->HrefValue = "";

			// ic_padrao
			$this->ic_padrao->HrefValue = "";
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
		if (!$this->no_organizacao->FldIsDetailKey && !is_null($this->no_organizacao->FormValue) && $this->no_organizacao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_organizacao->FldCaption());
		}
		if (!$this->nu_verticalNegocio->FldIsDetailKey && !is_null($this->nu_verticalNegocio->FormValue) && $this->nu_verticalNegocio->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_verticalNegocio->FldCaption());
		}
		if ($this->ic_ativo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_ativo->FldCaption());
		}
		if ($this->ic_padrao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_padrao->FldCaption());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("area", $DetailTblVar) && $GLOBALS["area"]->DetailEdit) {
			if (!isset($GLOBALS["area_grid"])) $GLOBALS["area_grid"] = new carea_grid(); // get detail page object
			$GLOBALS["area_grid"]->ValidateGridForm();
		}
		if (in_array("pargerais", $DetailTblVar) && $GLOBALS["pargerais"]->DetailEdit) {
			if (!isset($GLOBALS["pargerais_grid"])) $GLOBALS["pargerais_grid"] = new cpargerais_grid(); // get detail page object
			$GLOBALS["pargerais_grid"]->ValidateGridForm();
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
			if ($this->ic_padrao->CurrentValue <> "") { // Check field with unique index
			$sFilterChk = "([ic_padrao] = '" . ew_AdjustSql($this->ic_padrao->CurrentValue) . "')";
			$sFilterChk .= " AND NOT (" . $sFilter . ")";
			$this->CurrentFilter = $sFilterChk;
			$sSqlChk = $this->SQL();
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$rsChk = $conn->Execute($sSqlChk);
			$conn->raiseErrorFn = '';
			if ($rsChk === FALSE) {
				return FALSE;
			} elseif (!$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->ic_padrao->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->ic_padrao->CurrentValue, $sIdxErrMsg);
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

			// Begin transaction
			if ($this->getCurrentDetailTable() <> "")
				$conn->BeginTrans();

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// no_organizacao
			$this->no_organizacao->SetDbValueDef($rsnew, $this->no_organizacao->CurrentValue, "", $this->no_organizacao->ReadOnly);

			// ds_organizacao
			$this->ds_organizacao->SetDbValueDef($rsnew, $this->ds_organizacao->CurrentValue, NULL, $this->ds_organizacao->ReadOnly);

			// nu_verticalNegocio
			$this->nu_verticalNegocio->SetDbValueDef($rsnew, $this->nu_verticalNegocio->CurrentValue, NULL, $this->nu_verticalNegocio->ReadOnly);

			// ic_ativo
			$this->ic_ativo->SetDbValueDef($rsnew, $this->ic_ativo->CurrentValue, NULL, $this->ic_ativo->ReadOnly);

			// ic_padrao
			$this->ic_padrao->SetDbValueDef($rsnew, $this->ic_padrao->CurrentValue, NULL, $this->ic_padrao->ReadOnly);

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
					if (in_array("area", $DetailTblVar) && $GLOBALS["area"]->DetailEdit) {
						if (!isset($GLOBALS["area_grid"])) $GLOBALS["area_grid"] = new carea_grid(); // Get detail page object
						$EditRow = $GLOBALS["area_grid"]->GridUpdate();
					}
					if (in_array("pargerais", $DetailTblVar) && $GLOBALS["pargerais"]->DetailEdit) {
						if (!isset($GLOBALS["pargerais_grid"])) $GLOBALS["pargerais_grid"] = new cpargerais_grid(); // Get detail page object
						$EditRow = $GLOBALS["pargerais_grid"]->GridUpdate();
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
			if (in_array("area", $DetailTblVar)) {
				if (!isset($GLOBALS["area_grid"]))
					$GLOBALS["area_grid"] = new carea_grid;
				if ($GLOBALS["area_grid"]->DetailEdit) {
					$GLOBALS["area_grid"]->CurrentMode = "edit";
					$GLOBALS["area_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["area_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["area_grid"]->setStartRecordNumber(1);
					$GLOBALS["area_grid"]->nu_organizacao->FldIsDetailKey = TRUE;
					$GLOBALS["area_grid"]->nu_organizacao->CurrentValue = $this->nu_organizacao->CurrentValue;
					$GLOBALS["area_grid"]->nu_organizacao->setSessionValue($GLOBALS["area_grid"]->nu_organizacao->CurrentValue);
				}
			}
			if (in_array("pargerais", $DetailTblVar)) {
				if (!isset($GLOBALS["pargerais_grid"]))
					$GLOBALS["pargerais_grid"] = new cpargerais_grid;
				if ($GLOBALS["pargerais_grid"]->DetailEdit) {
					$GLOBALS["pargerais_grid"]->CurrentMode = "edit";
					$GLOBALS["pargerais_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["pargerais_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["pargerais_grid"]->setStartRecordNumber(1);
					$GLOBALS["pargerais_grid"]->nu_orgBase->FldIsDetailKey = TRUE;
					$GLOBALS["pargerais_grid"]->nu_orgBase->CurrentValue = $this->nu_organizacao->CurrentValue;
					$GLOBALS["pargerais_grid"]->nu_orgBase->setSessionValue($GLOBALS["pargerais_grid"]->nu_orgBase->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "organizacaolist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("edit");
		$Breadcrumb->Add("edit", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'organizacao';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		if (!$this->AuditTrailOnEdit) return;
		$table = 'organizacao';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['nu_organizacao'];

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
if (!isset($organizacao_edit)) $organizacao_edit = new corganizacao_edit();

// Page init
$organizacao_edit->Page_Init();

// Page main
$organizacao_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$organizacao_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var organizacao_edit = new ew_Page("organizacao_edit");
organizacao_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = organizacao_edit.PageID; // For backward compatibility

// Form object
var forganizacaoedit = new ew_Form("forganizacaoedit");

// Validate form
forganizacaoedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_no_organizacao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($organizacao->no_organizacao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_verticalNegocio");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($organizacao->nu_verticalNegocio->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_ativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($organizacao->ic_ativo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_padrao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($organizacao->ic_padrao->FldCaption()) ?>");

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
forganizacaoedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
forganizacaoedit.ValidateRequired = true;
<?php } else { ?>
forganizacaoedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
forganizacaoedit.Lists["x_nu_verticalNegocio"] = {"LinkField":"x_nu_verticalNegocio","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_vertical","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $organizacao_edit->ShowPageHeader(); ?>
<?php
$organizacao_edit->ShowMessage();
?>
<form name="forganizacaoedit" id="forganizacaoedit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="organizacao">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_organizacaoedit" class="table table-bordered table-striped">
<?php if ($organizacao->no_organizacao->Visible) { // no_organizacao ?>
	<tr id="r_no_organizacao">
		<td><span id="elh_organizacao_no_organizacao"><?php echo $organizacao->no_organizacao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $organizacao->no_organizacao->CellAttributes() ?>>
<span id="el_organizacao_no_organizacao" class="control-group">
<input type="text" data-field="x_no_organizacao" name="x_no_organizacao" id="x_no_organizacao" size="30" maxlength="100" placeholder="<?php echo $organizacao->no_organizacao->PlaceHolder ?>" value="<?php echo $organizacao->no_organizacao->EditValue ?>"<?php echo $organizacao->no_organizacao->EditAttributes() ?>>
</span>
<?php echo $organizacao->no_organizacao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($organizacao->ds_organizacao->Visible) { // ds_organizacao ?>
	<tr id="r_ds_organizacao">
		<td><span id="elh_organizacao_ds_organizacao"><?php echo $organizacao->ds_organizacao->FldCaption() ?></span></td>
		<td<?php echo $organizacao->ds_organizacao->CellAttributes() ?>>
<span id="el_organizacao_ds_organizacao" class="control-group">
<textarea data-field="x_ds_organizacao" name="x_ds_organizacao" id="x_ds_organizacao" cols="35" rows="4" placeholder="<?php echo $organizacao->ds_organizacao->PlaceHolder ?>"<?php echo $organizacao->ds_organizacao->EditAttributes() ?>><?php echo $organizacao->ds_organizacao->EditValue ?></textarea>
</span>
<?php echo $organizacao->ds_organizacao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($organizacao->nu_verticalNegocio->Visible) { // nu_verticalNegocio ?>
	<tr id="r_nu_verticalNegocio">
		<td><span id="elh_organizacao_nu_verticalNegocio"><?php echo $organizacao->nu_verticalNegocio->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $organizacao->nu_verticalNegocio->CellAttributes() ?>>
<span id="el_organizacao_nu_verticalNegocio" class="control-group">
<select data-field="x_nu_verticalNegocio" id="x_nu_verticalNegocio" name="x_nu_verticalNegocio"<?php echo $organizacao->nu_verticalNegocio->EditAttributes() ?>>
<?php
if (is_array($organizacao->nu_verticalNegocio->EditValue)) {
	$arwrk = $organizacao->nu_verticalNegocio->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($organizacao->nu_verticalNegocio->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if (AllowAdd(CurrentProjectID() . "verticalnegocio")) { ?>
&nbsp;<a id="aol_x_nu_verticalNegocio" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_nu_verticalNegocio',url:'verticalnegocioaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $organizacao->nu_verticalNegocio->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
forganizacaoedit.Lists["x_nu_verticalNegocio"].Options = <?php echo (is_array($organizacao->nu_verticalNegocio->EditValue)) ? ew_ArrayToJson($organizacao->nu_verticalNegocio->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $organizacao->nu_verticalNegocio->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($organizacao->ic_ativo->Visible) { // ic_ativo ?>
	<tr id="r_ic_ativo">
		<td><span id="elh_organizacao_ic_ativo"><?php echo $organizacao->ic_ativo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $organizacao->ic_ativo->CellAttributes() ?>>
<span id="el_organizacao_ic_ativo" class="control-group">
<div id="tp_x_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_ativo" id="x_ic_ativo" value="{value}"<?php echo $organizacao->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $organizacao->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($organizacao->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x_ic_ativo" id="x_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $organizacao->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $organizacao->ic_ativo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($organizacao->ic_padrao->Visible) { // ic_padrao ?>
	<tr id="r_ic_padrao">
		<td><span id="elh_organizacao_ic_padrao"><?php echo $organizacao->ic_padrao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $organizacao->ic_padrao->CellAttributes() ?>>
<span id="el_organizacao_ic_padrao" class="control-group">
<div id="tp_x_ic_padrao" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_padrao" id="x_ic_padrao" value="{value}"<?php echo $organizacao->ic_padrao->EditAttributes() ?>></div>
<div id="dsl_x_ic_padrao" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $organizacao->ic_padrao->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($organizacao->ic_padrao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_padrao" name="x_ic_padrao" id="x_ic_padrao_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $organizacao->ic_padrao->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $organizacao->ic_padrao->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<input type="hidden" data-field="x_nu_organizacao" name="x_nu_organizacao" id="x_nu_organizacao" value="<?php echo ew_HtmlEncode($organizacao->nu_organizacao->CurrentValue) ?>">
<?php
	if (in_array("area", explode(",", $organizacao->getCurrentDetailTable())) && $area->DetailEdit) {
?>
<?php include_once "areagrid.php" ?>
<?php } ?>
<?php
	if (in_array("pargerais", explode(",", $organizacao->getCurrentDetailTable())) && $pargerais->DetailEdit) {
?>
<?php include_once "pargeraisgrid.php" ?>
<?php } ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
forganizacaoedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$organizacao_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$organizacao_edit->Page_Terminate();
?>
