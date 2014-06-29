<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "tpmanutencaoinfo.php" ?>
<?php include_once "tpcontageminfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "tpelementogridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$tpmanutencao_edit = NULL; // Initialize page object first

class ctpmanutencao_edit extends ctpmanutencao {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'tpmanutencao';

	// Page object name
	var $PageObjName = 'tpmanutencao_edit';

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

		// Table object (tpmanutencao)
		if (!isset($GLOBALS["tpmanutencao"])) {
			$GLOBALS["tpmanutencao"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tpmanutencao"];
		}

		// Table object (tpcontagem)
		if (!isset($GLOBALS['tpcontagem'])) $GLOBALS['tpcontagem'] = new ctpcontagem();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tpmanutencao', TRUE);

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
			$this->Page_Terminate("tpmanutencaolist.php");
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
		if (@$_GET["nu_tpManutencao"] <> "") {
			$this->nu_tpManutencao->setQueryStringValue($_GET["nu_tpManutencao"]);
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
		if ($this->nu_tpManutencao->CurrentValue == "")
			$this->Page_Terminate("tpmanutencaolist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("tpmanutencaolist.php"); // No matching record, return to list
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
					if (ew_GetPageName($sReturnUrl) == "tpmanutencaoview.php")
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
		if (!$this->nu_tpContagem->FldIsDetailKey) {
			$this->nu_tpContagem->setFormValue($objForm->GetValue("x_nu_tpContagem"));
		}
		if (!$this->no_tpManutencao->FldIsDetailKey) {
			$this->no_tpManutencao->setFormValue($objForm->GetValue("x_no_tpManutencao"));
		}
		if (!$this->ic_modeloCalculo->FldIsDetailKey) {
			$this->ic_modeloCalculo->setFormValue($objForm->GetValue("x_ic_modeloCalculo"));
		}
		if (!$this->ic_utilizaFaseRoteiroCalculo->FldIsDetailKey) {
			$this->ic_utilizaFaseRoteiroCalculo->setFormValue($objForm->GetValue("x_ic_utilizaFaseRoteiroCalculo"));
		}
		if (!$this->nu_parametro->FldIsDetailKey) {
			$this->nu_parametro->setFormValue($objForm->GetValue("x_nu_parametro"));
		}
		if (!$this->ds_helpTela->FldIsDetailKey) {
			$this->ds_helpTela->setFormValue($objForm->GetValue("x_ds_helpTela"));
		}
		if (!$this->ic_ativo->FldIsDetailKey) {
			$this->ic_ativo->setFormValue($objForm->GetValue("x_ic_ativo"));
		}
		if (!$this->nu_tpManutencao->FldIsDetailKey)
			$this->nu_tpManutencao->setFormValue($objForm->GetValue("x_nu_tpManutencao"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->nu_tpManutencao->CurrentValue = $this->nu_tpManutencao->FormValue;
		$this->nu_tpContagem->CurrentValue = $this->nu_tpContagem->FormValue;
		$this->no_tpManutencao->CurrentValue = $this->no_tpManutencao->FormValue;
		$this->ic_modeloCalculo->CurrentValue = $this->ic_modeloCalculo->FormValue;
		$this->ic_utilizaFaseRoteiroCalculo->CurrentValue = $this->ic_utilizaFaseRoteiroCalculo->FormValue;
		$this->nu_parametro->CurrentValue = $this->nu_parametro->FormValue;
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
		$this->nu_tpManutencao->setDbValue($rs->fields('nu_tpManutencao'));
		$this->nu_tpContagem->setDbValue($rs->fields('nu_tpContagem'));
		$this->no_tpManutencao->setDbValue($rs->fields('no_tpManutencao'));
		$this->ic_modeloCalculo->setDbValue($rs->fields('ic_modeloCalculo'));
		$this->ic_utilizaFaseRoteiroCalculo->setDbValue($rs->fields('ic_utilizaFaseRoteiroCalculo'));
		$this->nu_parametro->setDbValue($rs->fields('nu_parametro'));
		$this->ds_helpTela->setDbValue($rs->fields('ds_helpTela'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_tpManutencao->DbValue = $row['nu_tpManutencao'];
		$this->nu_tpContagem->DbValue = $row['nu_tpContagem'];
		$this->no_tpManutencao->DbValue = $row['no_tpManutencao'];
		$this->ic_modeloCalculo->DbValue = $row['ic_modeloCalculo'];
		$this->ic_utilizaFaseRoteiroCalculo->DbValue = $row['ic_utilizaFaseRoteiroCalculo'];
		$this->nu_parametro->DbValue = $row['nu_parametro'];
		$this->ds_helpTela->DbValue = $row['ds_helpTela'];
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
		// nu_tpManutencao
		// nu_tpContagem
		// no_tpManutencao
		// ic_modeloCalculo
		// ic_utilizaFaseRoteiroCalculo
		// nu_parametro
		// ds_helpTela
		// ic_ativo

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_tpManutencao
			$this->nu_tpManutencao->ViewValue = $this->nu_tpManutencao->CurrentValue;
			$this->nu_tpManutencao->ViewCustomAttributes = "";

			// nu_tpContagem
			$this->nu_tpContagem->ViewValue = $this->nu_tpContagem->CurrentValue;
			$this->nu_tpContagem->ViewCustomAttributes = "";

			// no_tpManutencao
			$this->no_tpManutencao->ViewValue = $this->no_tpManutencao->CurrentValue;
			$this->no_tpManutencao->ViewCustomAttributes = "";

			// ic_modeloCalculo
			if (strval($this->ic_modeloCalculo->CurrentValue) <> "") {
				switch ($this->ic_modeloCalculo->CurrentValue) {
					case $this->ic_modeloCalculo->FldTagValue(1):
						$this->ic_modeloCalculo->ViewValue = $this->ic_modeloCalculo->FldTagCaption(1) <> "" ? $this->ic_modeloCalculo->FldTagCaption(1) : $this->ic_modeloCalculo->CurrentValue;
						break;
					case $this->ic_modeloCalculo->FldTagValue(2):
						$this->ic_modeloCalculo->ViewValue = $this->ic_modeloCalculo->FldTagCaption(2) <> "" ? $this->ic_modeloCalculo->FldTagCaption(2) : $this->ic_modeloCalculo->CurrentValue;
						break;
					case $this->ic_modeloCalculo->FldTagValue(3):
						$this->ic_modeloCalculo->ViewValue = $this->ic_modeloCalculo->FldTagCaption(3) <> "" ? $this->ic_modeloCalculo->FldTagCaption(3) : $this->ic_modeloCalculo->CurrentValue;
						break;
					default:
						$this->ic_modeloCalculo->ViewValue = $this->ic_modeloCalculo->CurrentValue;
				}
			} else {
				$this->ic_modeloCalculo->ViewValue = NULL;
			}
			$this->ic_modeloCalculo->ViewCustomAttributes = "";

			// ic_utilizaFaseRoteiroCalculo
			if (strval($this->ic_utilizaFaseRoteiroCalculo->CurrentValue) <> "") {
				switch ($this->ic_utilizaFaseRoteiroCalculo->CurrentValue) {
					case $this->ic_utilizaFaseRoteiroCalculo->FldTagValue(1):
						$this->ic_utilizaFaseRoteiroCalculo->ViewValue = $this->ic_utilizaFaseRoteiroCalculo->FldTagCaption(1) <> "" ? $this->ic_utilizaFaseRoteiroCalculo->FldTagCaption(1) : $this->ic_utilizaFaseRoteiroCalculo->CurrentValue;
						break;
					case $this->ic_utilizaFaseRoteiroCalculo->FldTagValue(2):
						$this->ic_utilizaFaseRoteiroCalculo->ViewValue = $this->ic_utilizaFaseRoteiroCalculo->FldTagCaption(2) <> "" ? $this->ic_utilizaFaseRoteiroCalculo->FldTagCaption(2) : $this->ic_utilizaFaseRoteiroCalculo->CurrentValue;
						break;
					default:
						$this->ic_utilizaFaseRoteiroCalculo->ViewValue = $this->ic_utilizaFaseRoteiroCalculo->CurrentValue;
				}
			} else {
				$this->ic_utilizaFaseRoteiroCalculo->ViewValue = NULL;
			}
			$this->ic_utilizaFaseRoteiroCalculo->ViewCustomAttributes = "";

			// nu_parametro
			if (strval($this->nu_parametro->CurrentValue) <> "") {
				$sFilterWrk = "[nu_parSisp]" . ew_SearchString("=", $this->nu_parametro->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_parSisp], [no_parSisp] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[parSisp]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_parametro, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_parSisp] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_parametro->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_parametro->ViewValue = $this->nu_parametro->CurrentValue;
				}
			} else {
				$this->nu_parametro->ViewValue = NULL;
			}
			$this->nu_parametro->ViewCustomAttributes = "";

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

			// nu_tpContagem
			$this->nu_tpContagem->LinkCustomAttributes = "";
			$this->nu_tpContagem->HrefValue = "";
			$this->nu_tpContagem->TooltipValue = "";

			// no_tpManutencao
			$this->no_tpManutencao->LinkCustomAttributes = "";
			$this->no_tpManutencao->HrefValue = "";
			$this->no_tpManutencao->TooltipValue = "";

			// ic_modeloCalculo
			$this->ic_modeloCalculo->LinkCustomAttributes = "";
			$this->ic_modeloCalculo->HrefValue = "";
			$this->ic_modeloCalculo->TooltipValue = "";

			// ic_utilizaFaseRoteiroCalculo
			$this->ic_utilizaFaseRoteiroCalculo->LinkCustomAttributes = "";
			$this->ic_utilizaFaseRoteiroCalculo->HrefValue = "";
			$this->ic_utilizaFaseRoteiroCalculo->TooltipValue = "";

			// nu_parametro
			$this->nu_parametro->LinkCustomAttributes = "";
			$this->nu_parametro->HrefValue = "";
			$this->nu_parametro->TooltipValue = "";

			// ds_helpTela
			$this->ds_helpTela->LinkCustomAttributes = "";
			$this->ds_helpTela->HrefValue = "";
			$this->ds_helpTela->TooltipValue = "";

			// ic_ativo
			$this->ic_ativo->LinkCustomAttributes = "";
			$this->ic_ativo->HrefValue = "";
			$this->ic_ativo->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// nu_tpContagem
			$this->nu_tpContagem->EditCustomAttributes = "";
			$this->nu_tpContagem->EditValue = $this->nu_tpContagem->CurrentValue;
			$this->nu_tpContagem->ViewCustomAttributes = "";

			// no_tpManutencao
			$this->no_tpManutencao->EditCustomAttributes = "";
			$this->no_tpManutencao->EditValue = $this->no_tpManutencao->CurrentValue;
			$this->no_tpManutencao->ViewCustomAttributes = "";

			// ic_modeloCalculo
			$this->ic_modeloCalculo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_modeloCalculo->FldTagValue(1), $this->ic_modeloCalculo->FldTagCaption(1) <> "" ? $this->ic_modeloCalculo->FldTagCaption(1) : $this->ic_modeloCalculo->FldTagValue(1));
			$arwrk[] = array($this->ic_modeloCalculo->FldTagValue(2), $this->ic_modeloCalculo->FldTagCaption(2) <> "" ? $this->ic_modeloCalculo->FldTagCaption(2) : $this->ic_modeloCalculo->FldTagValue(2));
			$arwrk[] = array($this->ic_modeloCalculo->FldTagValue(3), $this->ic_modeloCalculo->FldTagCaption(3) <> "" ? $this->ic_modeloCalculo->FldTagCaption(3) : $this->ic_modeloCalculo->FldTagValue(3));
			$this->ic_modeloCalculo->EditValue = $arwrk;

			// ic_utilizaFaseRoteiroCalculo
			$this->ic_utilizaFaseRoteiroCalculo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_utilizaFaseRoteiroCalculo->FldTagValue(1), $this->ic_utilizaFaseRoteiroCalculo->FldTagCaption(1) <> "" ? $this->ic_utilizaFaseRoteiroCalculo->FldTagCaption(1) : $this->ic_utilizaFaseRoteiroCalculo->FldTagValue(1));
			$arwrk[] = array($this->ic_utilizaFaseRoteiroCalculo->FldTagValue(2), $this->ic_utilizaFaseRoteiroCalculo->FldTagCaption(2) <> "" ? $this->ic_utilizaFaseRoteiroCalculo->FldTagCaption(2) : $this->ic_utilizaFaseRoteiroCalculo->FldTagValue(2));
			$this->ic_utilizaFaseRoteiroCalculo->EditValue = $arwrk;

			// nu_parametro
			$this->nu_parametro->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_parSisp], [no_parSisp] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[parSisp]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_parametro, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_parSisp] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_parametro->EditValue = $arwrk;

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
			// nu_tpContagem

			$this->nu_tpContagem->HrefValue = "";

			// no_tpManutencao
			$this->no_tpManutencao->HrefValue = "";

			// ic_modeloCalculo
			$this->ic_modeloCalculo->HrefValue = "";

			// ic_utilizaFaseRoteiroCalculo
			$this->ic_utilizaFaseRoteiroCalculo->HrefValue = "";

			// nu_parametro
			$this->nu_parametro->HrefValue = "";

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
		if ($this->ic_modeloCalculo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_modeloCalculo->FldCaption());
		}
		if ($this->ic_utilizaFaseRoteiroCalculo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_utilizaFaseRoteiroCalculo->FldCaption());
		}
		if (!$this->nu_parametro->FldIsDetailKey && !is_null($this->nu_parametro->FormValue) && $this->nu_parametro->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_parametro->FldCaption());
		}
		if ($this->ic_ativo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_ativo->FldCaption());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("tpElemento", $DetailTblVar) && $GLOBALS["tpElemento"]->DetailEdit) {
			if (!isset($GLOBALS["tpElemento_grid"])) $GLOBALS["tpElemento_grid"] = new ctpElemento_grid(); // get detail page object
			$GLOBALS["tpElemento_grid"]->ValidateGridForm();
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

			// ic_modeloCalculo
			$this->ic_modeloCalculo->SetDbValueDef($rsnew, $this->ic_modeloCalculo->CurrentValue, NULL, $this->ic_modeloCalculo->ReadOnly);

			// ic_utilizaFaseRoteiroCalculo
			$this->ic_utilizaFaseRoteiroCalculo->SetDbValueDef($rsnew, $this->ic_utilizaFaseRoteiroCalculo->CurrentValue, NULL, $this->ic_utilizaFaseRoteiroCalculo->ReadOnly);

			// nu_parametro
			$this->nu_parametro->SetDbValueDef($rsnew, $this->nu_parametro->CurrentValue, NULL, $this->nu_parametro->ReadOnly);

			// ds_helpTela
			$this->ds_helpTela->SetDbValueDef($rsnew, $this->ds_helpTela->CurrentValue, NULL, $this->ds_helpTela->ReadOnly);

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

				// Update detail records
				if ($EditRow) {
					$DetailTblVar = explode(",", $this->getCurrentDetailTable());
					if (in_array("tpElemento", $DetailTblVar) && $GLOBALS["tpElemento"]->DetailEdit) {
						if (!isset($GLOBALS["tpElemento_grid"])) $GLOBALS["tpElemento_grid"] = new ctpElemento_grid(); // Get detail page object
						$EditRow = $GLOBALS["tpElemento_grid"]->GridUpdate();
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
			if ($sMasterTblVar == "tpcontagem") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_tpContagem"] <> "") {
					$GLOBALS["tpcontagem"]->nu_tpContagem->setQueryStringValue($_GET["nu_tpContagem"]);
					$this->nu_tpContagem->setQueryStringValue($GLOBALS["tpcontagem"]->nu_tpContagem->QueryStringValue);
					$this->nu_tpContagem->setSessionValue($this->nu_tpContagem->QueryStringValue);
					if (!is_numeric($GLOBALS["tpcontagem"]->nu_tpContagem->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "tpcontagem") {
				if ($this->nu_tpContagem->QueryStringValue == "") $this->nu_tpContagem->setSessionValue("");
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
			if (in_array("tpElemento", $DetailTblVar)) {
				if (!isset($GLOBALS["tpElemento_grid"]))
					$GLOBALS["tpElemento_grid"] = new ctpElemento_grid;
				if ($GLOBALS["tpElemento_grid"]->DetailEdit) {
					$GLOBALS["tpElemento_grid"]->CurrentMode = "edit";
					$GLOBALS["tpElemento_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["tpElemento_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["tpElemento_grid"]->setStartRecordNumber(1);
					$GLOBALS["tpElemento_grid"]->nu_tpManutencao->FldIsDetailKey = TRUE;
					$GLOBALS["tpElemento_grid"]->nu_tpManutencao->CurrentValue = $this->nu_tpManutencao->CurrentValue;
					$GLOBALS["tpElemento_grid"]->nu_tpManutencao->setSessionValue($GLOBALS["tpElemento_grid"]->nu_tpManutencao->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "tpmanutencaolist.php", $this->TableVar);
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
if (!isset($tpmanutencao_edit)) $tpmanutencao_edit = new ctpmanutencao_edit();

// Page init
$tpmanutencao_edit->Page_Init();

// Page main
$tpmanutencao_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tpmanutencao_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var tpmanutencao_edit = new ew_Page("tpmanutencao_edit");
tpmanutencao_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = tpmanutencao_edit.PageID; // For backward compatibility

// Form object
var ftpmanutencaoedit = new ew_Form("ftpmanutencaoedit");

// Validate form
ftpmanutencaoedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_ic_modeloCalculo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tpmanutencao->ic_modeloCalculo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_utilizaFaseRoteiroCalculo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tpmanutencao->ic_utilizaFaseRoteiroCalculo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_parametro");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tpmanutencao->nu_parametro->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_ativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tpmanutencao->ic_ativo->FldCaption()) ?>");

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
ftpmanutencaoedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftpmanutencaoedit.ValidateRequired = true;
<?php } else { ?>
ftpmanutencaoedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftpmanutencaoedit.Lists["x_nu_parametro"] = {"LinkField":"x_nu_parSisp","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_parSisp","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $tpmanutencao_edit->ShowPageHeader(); ?>
<?php
$tpmanutencao_edit->ShowMessage();
?>
<form name="ftpmanutencaoedit" id="ftpmanutencaoedit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="tpmanutencao">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_tpmanutencaoedit" class="table table-bordered table-striped">
<?php if ($tpmanutencao->nu_tpContagem->Visible) { // nu_tpContagem ?>
	<tr id="r_nu_tpContagem">
		<td><span id="elh_tpmanutencao_nu_tpContagem"><?php echo $tpmanutencao->nu_tpContagem->FldCaption() ?></span></td>
		<td<?php echo $tpmanutencao->nu_tpContagem->CellAttributes() ?>>
<span id="el_tpmanutencao_nu_tpContagem" class="control-group">
<span<?php echo $tpmanutencao->nu_tpContagem->ViewAttributes() ?>>
<?php echo $tpmanutencao->nu_tpContagem->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_tpContagem" name="x_nu_tpContagem" id="x_nu_tpContagem" value="<?php echo ew_HtmlEncode($tpmanutencao->nu_tpContagem->CurrentValue) ?>">
<?php echo $tpmanutencao->nu_tpContagem->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tpmanutencao->no_tpManutencao->Visible) { // no_tpManutencao ?>
	<tr id="r_no_tpManutencao">
		<td><span id="elh_tpmanutencao_no_tpManutencao"><?php echo $tpmanutencao->no_tpManutencao->FldCaption() ?></span></td>
		<td<?php echo $tpmanutencao->no_tpManutencao->CellAttributes() ?>>
<span id="el_tpmanutencao_no_tpManutencao" class="control-group">
<span<?php echo $tpmanutencao->no_tpManutencao->ViewAttributes() ?>>
<?php echo $tpmanutencao->no_tpManutencao->EditValue ?></span>
</span>
<input type="hidden" data-field="x_no_tpManutencao" name="x_no_tpManutencao" id="x_no_tpManutencao" value="<?php echo ew_HtmlEncode($tpmanutencao->no_tpManutencao->CurrentValue) ?>">
<?php echo $tpmanutencao->no_tpManutencao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tpmanutencao->ic_modeloCalculo->Visible) { // ic_modeloCalculo ?>
	<tr id="r_ic_modeloCalculo">
		<td><span id="elh_tpmanutencao_ic_modeloCalculo"><?php echo $tpmanutencao->ic_modeloCalculo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tpmanutencao->ic_modeloCalculo->CellAttributes() ?>>
<span id="el_tpmanutencao_ic_modeloCalculo" class="control-group">
<div id="tp_x_ic_modeloCalculo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_modeloCalculo" id="x_ic_modeloCalculo" value="{value}"<?php echo $tpmanutencao->ic_modeloCalculo->EditAttributes() ?>></div>
<div id="dsl_x_ic_modeloCalculo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $tpmanutencao->ic_modeloCalculo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tpmanutencao->ic_modeloCalculo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_modeloCalculo" name="x_ic_modeloCalculo" id="x_ic_modeloCalculo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $tpmanutencao->ic_modeloCalculo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $tpmanutencao->ic_modeloCalculo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tpmanutencao->ic_utilizaFaseRoteiroCalculo->Visible) { // ic_utilizaFaseRoteiroCalculo ?>
	<tr id="r_ic_utilizaFaseRoteiroCalculo">
		<td><span id="elh_tpmanutencao_ic_utilizaFaseRoteiroCalculo"><?php echo $tpmanutencao->ic_utilizaFaseRoteiroCalculo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tpmanutencao->ic_utilizaFaseRoteiroCalculo->CellAttributes() ?>>
<span id="el_tpmanutencao_ic_utilizaFaseRoteiroCalculo" class="control-group">
<div id="tp_x_ic_utilizaFaseRoteiroCalculo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_utilizaFaseRoteiroCalculo" id="x_ic_utilizaFaseRoteiroCalculo" value="{value}"<?php echo $tpmanutencao->ic_utilizaFaseRoteiroCalculo->EditAttributes() ?>></div>
<div id="dsl_x_ic_utilizaFaseRoteiroCalculo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $tpmanutencao->ic_utilizaFaseRoteiroCalculo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tpmanutencao->ic_utilizaFaseRoteiroCalculo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_utilizaFaseRoteiroCalculo" name="x_ic_utilizaFaseRoteiroCalculo" id="x_ic_utilizaFaseRoteiroCalculo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $tpmanutencao->ic_utilizaFaseRoteiroCalculo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $tpmanutencao->ic_utilizaFaseRoteiroCalculo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tpmanutencao->nu_parametro->Visible) { // nu_parametro ?>
	<tr id="r_nu_parametro">
		<td><span id="elh_tpmanutencao_nu_parametro"><?php echo $tpmanutencao->nu_parametro->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tpmanutencao->nu_parametro->CellAttributes() ?>>
<span id="el_tpmanutencao_nu_parametro" class="control-group">
<select data-field="x_nu_parametro" id="x_nu_parametro" name="x_nu_parametro"<?php echo $tpmanutencao->nu_parametro->EditAttributes() ?>>
<?php
if (is_array($tpmanutencao->nu_parametro->EditValue)) {
	$arwrk = $tpmanutencao->nu_parametro->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tpmanutencao->nu_parametro->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
ftpmanutencaoedit.Lists["x_nu_parametro"].Options = <?php echo (is_array($tpmanutencao->nu_parametro->EditValue)) ? ew_ArrayToJson($tpmanutencao->nu_parametro->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $tpmanutencao->nu_parametro->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tpmanutencao->ds_helpTela->Visible) { // ds_helpTela ?>
	<tr id="r_ds_helpTela">
		<td><span id="elh_tpmanutencao_ds_helpTela"><?php echo $tpmanutencao->ds_helpTela->FldCaption() ?></span></td>
		<td<?php echo $tpmanutencao->ds_helpTela->CellAttributes() ?>>
<span id="el_tpmanutencao_ds_helpTela" class="control-group">
<textarea data-field="x_ds_helpTela" name="x_ds_helpTela" id="x_ds_helpTela" cols="35" rows="4" placeholder="<?php echo $tpmanutencao->ds_helpTela->PlaceHolder ?>"<?php echo $tpmanutencao->ds_helpTela->EditAttributes() ?>><?php echo $tpmanutencao->ds_helpTela->EditValue ?></textarea>
</span>
<?php echo $tpmanutencao->ds_helpTela->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tpmanutencao->ic_ativo->Visible) { // ic_ativo ?>
	<tr id="r_ic_ativo">
		<td><span id="elh_tpmanutencao_ic_ativo"><?php echo $tpmanutencao->ic_ativo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tpmanutencao->ic_ativo->CellAttributes() ?>>
<span id="el_tpmanutencao_ic_ativo" class="control-group">
<div id="tp_x_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_ativo" id="x_ic_ativo" value="{value}"<?php echo $tpmanutencao->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $tpmanutencao->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tpmanutencao->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x_ic_ativo" id="x_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $tpmanutencao->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $tpmanutencao->ic_ativo->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<input type="hidden" data-field="x_nu_tpManutencao" name="x_nu_tpManutencao" id="x_nu_tpManutencao" value="<?php echo ew_HtmlEncode($tpmanutencao->nu_tpManutencao->CurrentValue) ?>">
<?php
	if (in_array("tpElemento", explode(",", $tpmanutencao->getCurrentDetailTable())) && $tpElemento->DetailEdit) {
?>
<?php include_once "tpelementogrid.php" ?>
<?php } ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
ftpmanutencaoedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$tpmanutencao_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tpmanutencao_edit->Page_Terminate();
?>
