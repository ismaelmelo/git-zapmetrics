<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "ambiente_valoracaoinfo.php" ?>
<?php include_once "ambienteinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$ambiente_valoracao_edit = NULL; // Initialize page object first

class cambiente_valoracao_edit extends cambiente_valoracao {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'ambiente_valoracao';

	// Page object name
	var $PageObjName = 'ambiente_valoracao_edit';

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

		// Table object (ambiente_valoracao)
		if (!isset($GLOBALS["ambiente_valoracao"])) {
			$GLOBALS["ambiente_valoracao"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["ambiente_valoracao"];
		}

		// Table object (ambiente)
		if (!isset($GLOBALS['ambiente'])) $GLOBALS['ambiente'] = new cambiente();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'ambiente_valoracao', TRUE);

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
			$this->Page_Terminate("ambiente_valoracaolist.php");
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
		if (@$_GET["nu_ambiente"] <> "") {
			$this->nu_ambiente->setQueryStringValue($_GET["nu_ambiente"]);
		}
		if (@$_GET["nu_versaoValoracao"] <> "") {
			$this->nu_versaoValoracao->setQueryStringValue($_GET["nu_versaoValoracao"]);
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
		if ($this->nu_ambiente->CurrentValue == "")
			$this->Page_Terminate("ambiente_valoracaolist.php"); // Invalid key, return to list
		if ($this->nu_versaoValoracao->CurrentValue == "")
			$this->Page_Terminate("ambiente_valoracaolist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("ambiente_valoracaolist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "ambiente_valoracaoview.php")
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
		if (!$this->nu_versaoValoracao->FldIsDetailKey) {
			$this->nu_versaoValoracao->setFormValue($objForm->GetValue("x_nu_versaoValoracao"));
		}
		if (!$this->ic_metCalibracao->FldIsDetailKey) {
			$this->ic_metCalibracao->setFormValue($objForm->GetValue("x_ic_metCalibracao"));
		}
		if (!$this->dh_inclusao->FldIsDetailKey) {
			$this->dh_inclusao->setFormValue($objForm->GetValue("x_dh_inclusao"));
			$this->dh_inclusao->CurrentValue = ew_UnFormatDateTime($this->dh_inclusao->CurrentValue, 7);
		}
		if (!$this->nu_usuarioResp->FldIsDetailKey) {
			$this->nu_usuarioResp->setFormValue($objForm->GetValue("x_nu_usuarioResp"));
		}
		if (!$this->qt_linhasCodLingPf->FldIsDetailKey) {
			$this->qt_linhasCodLingPf->setFormValue($objForm->GetValue("x_qt_linhasCodLingPf"));
		}
		if (!$this->vr_ipMin->FldIsDetailKey) {
			$this->vr_ipMin->setFormValue($objForm->GetValue("x_vr_ipMin"));
		}
		if (!$this->vr_ipMed->FldIsDetailKey) {
			$this->vr_ipMed->setFormValue($objForm->GetValue("x_vr_ipMed"));
		}
		if (!$this->vr_ipMax->FldIsDetailKey) {
			$this->vr_ipMax->setFormValue($objForm->GetValue("x_vr_ipMax"));
		}
		if (!$this->vr_constanteA->FldIsDetailKey) {
			$this->vr_constanteA->setFormValue($objForm->GetValue("x_vr_constanteA"));
		}
		if (!$this->vr_constanteB->FldIsDetailKey) {
			$this->vr_constanteB->setFormValue($objForm->GetValue("x_vr_constanteB"));
		}
		if (!$this->vr_constanteC->FldIsDetailKey) {
			$this->vr_constanteC->setFormValue($objForm->GetValue("x_vr_constanteC"));
		}
		if (!$this->vr_constanteD->FldIsDetailKey) {
			$this->vr_constanteD->setFormValue($objForm->GetValue("x_vr_constanteD"));
		}
		if (!$this->nu_altPREC->FldIsDetailKey) {
			$this->nu_altPREC->setFormValue($objForm->GetValue("x_nu_altPREC"));
		}
		if (!$this->nu_altFLEX->FldIsDetailKey) {
			$this->nu_altFLEX->setFormValue($objForm->GetValue("x_nu_altFLEX"));
		}
		if (!$this->nu_altRESL->FldIsDetailKey) {
			$this->nu_altRESL->setFormValue($objForm->GetValue("x_nu_altRESL"));
		}
		if (!$this->nu_altTEAM->FldIsDetailKey) {
			$this->nu_altTEAM->setFormValue($objForm->GetValue("x_nu_altTEAM"));
		}
		if (!$this->nu_altPMAT->FldIsDetailKey) {
			$this->nu_altPMAT->setFormValue($objForm->GetValue("x_nu_altPMAT"));
		}
		if (!$this->nu_altRELY->FldIsDetailKey) {
			$this->nu_altRELY->setFormValue($objForm->GetValue("x_nu_altRELY"));
		}
		if (!$this->nu_altDATA->FldIsDetailKey) {
			$this->nu_altDATA->setFormValue($objForm->GetValue("x_nu_altDATA"));
		}
		if (!$this->nu_altCPLX1->FldIsDetailKey) {
			$this->nu_altCPLX1->setFormValue($objForm->GetValue("x_nu_altCPLX1"));
		}
		if (!$this->nu_altCPLX2->FldIsDetailKey) {
			$this->nu_altCPLX2->setFormValue($objForm->GetValue("x_nu_altCPLX2"));
		}
		if (!$this->nu_altCPLX3->FldIsDetailKey) {
			$this->nu_altCPLX3->setFormValue($objForm->GetValue("x_nu_altCPLX3"));
		}
		if (!$this->nu_altCPLX4->FldIsDetailKey) {
			$this->nu_altCPLX4->setFormValue($objForm->GetValue("x_nu_altCPLX4"));
		}
		if (!$this->nu_altCPLX5->FldIsDetailKey) {
			$this->nu_altCPLX5->setFormValue($objForm->GetValue("x_nu_altCPLX5"));
		}
		if (!$this->nu_altDOCU->FldIsDetailKey) {
			$this->nu_altDOCU->setFormValue($objForm->GetValue("x_nu_altDOCU"));
		}
		if (!$this->nu_altRUSE->FldIsDetailKey) {
			$this->nu_altRUSE->setFormValue($objForm->GetValue("x_nu_altRUSE"));
		}
		if (!$this->nu_altTIME->FldIsDetailKey) {
			$this->nu_altTIME->setFormValue($objForm->GetValue("x_nu_altTIME"));
		}
		if (!$this->nu_altSTOR->FldIsDetailKey) {
			$this->nu_altSTOR->setFormValue($objForm->GetValue("x_nu_altSTOR"));
		}
		if (!$this->nu_altPVOL->FldIsDetailKey) {
			$this->nu_altPVOL->setFormValue($objForm->GetValue("x_nu_altPVOL"));
		}
		if (!$this->nu_altACAP->FldIsDetailKey) {
			$this->nu_altACAP->setFormValue($objForm->GetValue("x_nu_altACAP"));
		}
		if (!$this->nu_altPCAP->FldIsDetailKey) {
			$this->nu_altPCAP->setFormValue($objForm->GetValue("x_nu_altPCAP"));
		}
		if (!$this->nu_altPCON->FldIsDetailKey) {
			$this->nu_altPCON->setFormValue($objForm->GetValue("x_nu_altPCON"));
		}
		if (!$this->nu_altAPEX->FldIsDetailKey) {
			$this->nu_altAPEX->setFormValue($objForm->GetValue("x_nu_altAPEX"));
		}
		if (!$this->nu_altPLEX->FldIsDetailKey) {
			$this->nu_altPLEX->setFormValue($objForm->GetValue("x_nu_altPLEX"));
		}
		if (!$this->nu_altLTEX->FldIsDetailKey) {
			$this->nu_altLTEX->setFormValue($objForm->GetValue("x_nu_altLTEX"));
		}
		if (!$this->nu_altTOOL->FldIsDetailKey) {
			$this->nu_altTOOL->setFormValue($objForm->GetValue("x_nu_altTOOL"));
		}
		if (!$this->nu_altSITE->FldIsDetailKey) {
			$this->nu_altSITE->setFormValue($objForm->GetValue("x_nu_altSITE"));
		}
		if (!$this->co_quePREC->FldIsDetailKey) {
			$this->co_quePREC->setFormValue($objForm->GetValue("x_co_quePREC"));
		}
		if (!$this->co_queFLEX->FldIsDetailKey) {
			$this->co_queFLEX->setFormValue($objForm->GetValue("x_co_queFLEX"));
		}
		if (!$this->co_queRESL->FldIsDetailKey) {
			$this->co_queRESL->setFormValue($objForm->GetValue("x_co_queRESL"));
		}
		if (!$this->co_queTEAM->FldIsDetailKey) {
			$this->co_queTEAM->setFormValue($objForm->GetValue("x_co_queTEAM"));
		}
		if (!$this->co_quePMAT->FldIsDetailKey) {
			$this->co_quePMAT->setFormValue($objForm->GetValue("x_co_quePMAT"));
		}
		if (!$this->co_queRELY->FldIsDetailKey) {
			$this->co_queRELY->setFormValue($objForm->GetValue("x_co_queRELY"));
		}
		if (!$this->co_queDATA->FldIsDetailKey) {
			$this->co_queDATA->setFormValue($objForm->GetValue("x_co_queDATA"));
		}
		if (!$this->co_queCPLX1->FldIsDetailKey) {
			$this->co_queCPLX1->setFormValue($objForm->GetValue("x_co_queCPLX1"));
		}
		if (!$this->co_queCPLX2->FldIsDetailKey) {
			$this->co_queCPLX2->setFormValue($objForm->GetValue("x_co_queCPLX2"));
		}
		if (!$this->co_queCPLX3->FldIsDetailKey) {
			$this->co_queCPLX3->setFormValue($objForm->GetValue("x_co_queCPLX3"));
		}
		if (!$this->co_queCPLX4->FldIsDetailKey) {
			$this->co_queCPLX4->setFormValue($objForm->GetValue("x_co_queCPLX4"));
		}
		if (!$this->co_queCPLX5->FldIsDetailKey) {
			$this->co_queCPLX5->setFormValue($objForm->GetValue("x_co_queCPLX5"));
		}
		if (!$this->co_queDOCU->FldIsDetailKey) {
			$this->co_queDOCU->setFormValue($objForm->GetValue("x_co_queDOCU"));
		}
		if (!$this->co_queRUSE->FldIsDetailKey) {
			$this->co_queRUSE->setFormValue($objForm->GetValue("x_co_queRUSE"));
		}
		if (!$this->co_queTIME->FldIsDetailKey) {
			$this->co_queTIME->setFormValue($objForm->GetValue("x_co_queTIME"));
		}
		if (!$this->co_queSTOR->FldIsDetailKey) {
			$this->co_queSTOR->setFormValue($objForm->GetValue("x_co_queSTOR"));
		}
		if (!$this->co_quePVOL->FldIsDetailKey) {
			$this->co_quePVOL->setFormValue($objForm->GetValue("x_co_quePVOL"));
		}
		if (!$this->co_queACAP->FldIsDetailKey) {
			$this->co_queACAP->setFormValue($objForm->GetValue("x_co_queACAP"));
		}
		if (!$this->co_quePCAP->FldIsDetailKey) {
			$this->co_quePCAP->setFormValue($objForm->GetValue("x_co_quePCAP"));
		}
		if (!$this->co_quePCON->FldIsDetailKey) {
			$this->co_quePCON->setFormValue($objForm->GetValue("x_co_quePCON"));
		}
		if (!$this->co_queAPEX->FldIsDetailKey) {
			$this->co_queAPEX->setFormValue($objForm->GetValue("x_co_queAPEX"));
		}
		if (!$this->co_quePLEX->FldIsDetailKey) {
			$this->co_quePLEX->setFormValue($objForm->GetValue("x_co_quePLEX"));
		}
		if (!$this->co_queLTEX->FldIsDetailKey) {
			$this->co_queLTEX->setFormValue($objForm->GetValue("x_co_queLTEX"));
		}
		if (!$this->co_queTOOL->FldIsDetailKey) {
			$this->co_queTOOL->setFormValue($objForm->GetValue("x_co_queTOOL"));
		}
		if (!$this->co_queSITE->FldIsDetailKey) {
			$this->co_queSITE->setFormValue($objForm->GetValue("x_co_queSITE"));
		}
		if (!$this->nu_ambiente->FldIsDetailKey)
			$this->nu_ambiente->setFormValue($objForm->GetValue("x_nu_ambiente"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->nu_ambiente->CurrentValue = $this->nu_ambiente->FormValue;
		$this->nu_versaoValoracao->CurrentValue = $this->nu_versaoValoracao->FormValue;
		$this->ic_metCalibracao->CurrentValue = $this->ic_metCalibracao->FormValue;
		$this->dh_inclusao->CurrentValue = $this->dh_inclusao->FormValue;
		$this->dh_inclusao->CurrentValue = ew_UnFormatDateTime($this->dh_inclusao->CurrentValue, 7);
		$this->nu_usuarioResp->CurrentValue = $this->nu_usuarioResp->FormValue;
		$this->qt_linhasCodLingPf->CurrentValue = $this->qt_linhasCodLingPf->FormValue;
		$this->vr_ipMin->CurrentValue = $this->vr_ipMin->FormValue;
		$this->vr_ipMed->CurrentValue = $this->vr_ipMed->FormValue;
		$this->vr_ipMax->CurrentValue = $this->vr_ipMax->FormValue;
		$this->vr_constanteA->CurrentValue = $this->vr_constanteA->FormValue;
		$this->vr_constanteB->CurrentValue = $this->vr_constanteB->FormValue;
		$this->vr_constanteC->CurrentValue = $this->vr_constanteC->FormValue;
		$this->vr_constanteD->CurrentValue = $this->vr_constanteD->FormValue;
		$this->nu_altPREC->CurrentValue = $this->nu_altPREC->FormValue;
		$this->nu_altFLEX->CurrentValue = $this->nu_altFLEX->FormValue;
		$this->nu_altRESL->CurrentValue = $this->nu_altRESL->FormValue;
		$this->nu_altTEAM->CurrentValue = $this->nu_altTEAM->FormValue;
		$this->nu_altPMAT->CurrentValue = $this->nu_altPMAT->FormValue;
		$this->nu_altRELY->CurrentValue = $this->nu_altRELY->FormValue;
		$this->nu_altDATA->CurrentValue = $this->nu_altDATA->FormValue;
		$this->nu_altCPLX1->CurrentValue = $this->nu_altCPLX1->FormValue;
		$this->nu_altCPLX2->CurrentValue = $this->nu_altCPLX2->FormValue;
		$this->nu_altCPLX3->CurrentValue = $this->nu_altCPLX3->FormValue;
		$this->nu_altCPLX4->CurrentValue = $this->nu_altCPLX4->FormValue;
		$this->nu_altCPLX5->CurrentValue = $this->nu_altCPLX5->FormValue;
		$this->nu_altDOCU->CurrentValue = $this->nu_altDOCU->FormValue;
		$this->nu_altRUSE->CurrentValue = $this->nu_altRUSE->FormValue;
		$this->nu_altTIME->CurrentValue = $this->nu_altTIME->FormValue;
		$this->nu_altSTOR->CurrentValue = $this->nu_altSTOR->FormValue;
		$this->nu_altPVOL->CurrentValue = $this->nu_altPVOL->FormValue;
		$this->nu_altACAP->CurrentValue = $this->nu_altACAP->FormValue;
		$this->nu_altPCAP->CurrentValue = $this->nu_altPCAP->FormValue;
		$this->nu_altPCON->CurrentValue = $this->nu_altPCON->FormValue;
		$this->nu_altAPEX->CurrentValue = $this->nu_altAPEX->FormValue;
		$this->nu_altPLEX->CurrentValue = $this->nu_altPLEX->FormValue;
		$this->nu_altLTEX->CurrentValue = $this->nu_altLTEX->FormValue;
		$this->nu_altTOOL->CurrentValue = $this->nu_altTOOL->FormValue;
		$this->nu_altSITE->CurrentValue = $this->nu_altSITE->FormValue;
		$this->co_quePREC->CurrentValue = $this->co_quePREC->FormValue;
		$this->co_queFLEX->CurrentValue = $this->co_queFLEX->FormValue;
		$this->co_queRESL->CurrentValue = $this->co_queRESL->FormValue;
		$this->co_queTEAM->CurrentValue = $this->co_queTEAM->FormValue;
		$this->co_quePMAT->CurrentValue = $this->co_quePMAT->FormValue;
		$this->co_queRELY->CurrentValue = $this->co_queRELY->FormValue;
		$this->co_queDATA->CurrentValue = $this->co_queDATA->FormValue;
		$this->co_queCPLX1->CurrentValue = $this->co_queCPLX1->FormValue;
		$this->co_queCPLX2->CurrentValue = $this->co_queCPLX2->FormValue;
		$this->co_queCPLX3->CurrentValue = $this->co_queCPLX3->FormValue;
		$this->co_queCPLX4->CurrentValue = $this->co_queCPLX4->FormValue;
		$this->co_queCPLX5->CurrentValue = $this->co_queCPLX5->FormValue;
		$this->co_queDOCU->CurrentValue = $this->co_queDOCU->FormValue;
		$this->co_queRUSE->CurrentValue = $this->co_queRUSE->FormValue;
		$this->co_queTIME->CurrentValue = $this->co_queTIME->FormValue;
		$this->co_queSTOR->CurrentValue = $this->co_queSTOR->FormValue;
		$this->co_quePVOL->CurrentValue = $this->co_quePVOL->FormValue;
		$this->co_queACAP->CurrentValue = $this->co_queACAP->FormValue;
		$this->co_quePCAP->CurrentValue = $this->co_quePCAP->FormValue;
		$this->co_quePCON->CurrentValue = $this->co_quePCON->FormValue;
		$this->co_queAPEX->CurrentValue = $this->co_queAPEX->FormValue;
		$this->co_quePLEX->CurrentValue = $this->co_quePLEX->FormValue;
		$this->co_queLTEX->CurrentValue = $this->co_queLTEX->FormValue;
		$this->co_queTOOL->CurrentValue = $this->co_queTOOL->FormValue;
		$this->co_queSITE->CurrentValue = $this->co_queSITE->FormValue;
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
		$this->nu_ambiente->setDbValue($rs->fields('nu_ambiente'));
		$this->nu_versaoValoracao->setDbValue($rs->fields('nu_versaoValoracao'));
		$this->ic_metCalibracao->setDbValue($rs->fields('ic_metCalibracao'));
		$this->dh_inclusao->setDbValue($rs->fields('dh_inclusao'));
		$this->nu_usuarioResp->setDbValue($rs->fields('nu_usuarioResp'));
		$this->ic_tpAtualizacao->setDbValue($rs->fields('ic_tpAtualizacao'));
		$this->qt_linhasCodLingPf->setDbValue($rs->fields('qt_linhasCodLingPf'));
		$this->vr_ipMin->setDbValue($rs->fields('vr_ipMin'));
		$this->vr_ipMed->setDbValue($rs->fields('vr_ipMed'));
		$this->vr_ipMax->setDbValue($rs->fields('vr_ipMax'));
		$this->vr_constanteA->setDbValue($rs->fields('vr_constanteA'));
		$this->vr_constanteB->setDbValue($rs->fields('vr_constanteB'));
		$this->vr_constanteC->setDbValue($rs->fields('vr_constanteC'));
		$this->vr_constanteD->setDbValue($rs->fields('vr_constanteD'));
		$this->nu_altPREC->setDbValue($rs->fields('nu_altPREC'));
		if (array_key_exists('EV__nu_altPREC', $rs->fields)) {
			$this->nu_altPREC->VirtualValue = $rs->fields('EV__nu_altPREC'); // Set up virtual field value
		} else {
			$this->nu_altPREC->VirtualValue = ""; // Clear value
		}
		$this->nu_altFLEX->setDbValue($rs->fields('nu_altFLEX'));
		$this->nu_altRESL->setDbValue($rs->fields('nu_altRESL'));
		$this->nu_altTEAM->setDbValue($rs->fields('nu_altTEAM'));
		if (array_key_exists('EV__nu_altTEAM', $rs->fields)) {
			$this->nu_altTEAM->VirtualValue = $rs->fields('EV__nu_altTEAM'); // Set up virtual field value
		} else {
			$this->nu_altTEAM->VirtualValue = ""; // Clear value
		}
		$this->nu_altPMAT->setDbValue($rs->fields('nu_altPMAT'));
		$this->nu_altRELY->setDbValue($rs->fields('nu_altRELY'));
		$this->nu_altDATA->setDbValue($rs->fields('nu_altDATA'));
		$this->nu_altCPLX1->setDbValue($rs->fields('nu_altCPLX1'));
		$this->nu_altCPLX2->setDbValue($rs->fields('nu_altCPLX2'));
		$this->nu_altCPLX3->setDbValue($rs->fields('nu_altCPLX3'));
		if (array_key_exists('EV__nu_altCPLX3', $rs->fields)) {
			$this->nu_altCPLX3->VirtualValue = $rs->fields('EV__nu_altCPLX3'); // Set up virtual field value
		} else {
			$this->nu_altCPLX3->VirtualValue = ""; // Clear value
		}
		$this->nu_altCPLX4->setDbValue($rs->fields('nu_altCPLX4'));
		if (array_key_exists('EV__nu_altCPLX4', $rs->fields)) {
			$this->nu_altCPLX4->VirtualValue = $rs->fields('EV__nu_altCPLX4'); // Set up virtual field value
		} else {
			$this->nu_altCPLX4->VirtualValue = ""; // Clear value
		}
		$this->nu_altCPLX5->setDbValue($rs->fields('nu_altCPLX5'));
		$this->nu_altDOCU->setDbValue($rs->fields('nu_altDOCU'));
		$this->nu_altRUSE->setDbValue($rs->fields('nu_altRUSE'));
		$this->nu_altTIME->setDbValue($rs->fields('nu_altTIME'));
		$this->nu_altSTOR->setDbValue($rs->fields('nu_altSTOR'));
		$this->nu_altPVOL->setDbValue($rs->fields('nu_altPVOL'));
		$this->nu_altACAP->setDbValue($rs->fields('nu_altACAP'));
		$this->nu_altPCAP->setDbValue($rs->fields('nu_altPCAP'));
		$this->nu_altPCON->setDbValue($rs->fields('nu_altPCON'));
		$this->nu_altAPEX->setDbValue($rs->fields('nu_altAPEX'));
		$this->nu_altPLEX->setDbValue($rs->fields('nu_altPLEX'));
		$this->nu_altLTEX->setDbValue($rs->fields('nu_altLTEX'));
		$this->nu_altTOOL->setDbValue($rs->fields('nu_altTOOL'));
		$this->nu_altSITE->setDbValue($rs->fields('nu_altSITE'));
		$this->co_quePREC->setDbValue($rs->fields('co_quePREC'));
		$this->co_queFLEX->setDbValue($rs->fields('co_queFLEX'));
		$this->co_queRESL->setDbValue($rs->fields('co_queRESL'));
		$this->co_queTEAM->setDbValue($rs->fields('co_queTEAM'));
		$this->co_quePMAT->setDbValue($rs->fields('co_quePMAT'));
		$this->co_queRELY->setDbValue($rs->fields('co_queRELY'));
		$this->co_queDATA->setDbValue($rs->fields('co_queDATA'));
		$this->co_queCPLX1->setDbValue($rs->fields('co_queCPLX1'));
		$this->co_queCPLX2->setDbValue($rs->fields('co_queCPLX2'));
		$this->co_queCPLX3->setDbValue($rs->fields('co_queCPLX3'));
		$this->co_queCPLX4->setDbValue($rs->fields('co_queCPLX4'));
		$this->co_queCPLX5->setDbValue($rs->fields('co_queCPLX5'));
		$this->co_queDOCU->setDbValue($rs->fields('co_queDOCU'));
		$this->co_queRUSE->setDbValue($rs->fields('co_queRUSE'));
		$this->co_queTIME->setDbValue($rs->fields('co_queTIME'));
		$this->co_queSTOR->setDbValue($rs->fields('co_queSTOR'));
		$this->co_quePVOL->setDbValue($rs->fields('co_quePVOL'));
		$this->co_queACAP->setDbValue($rs->fields('co_queACAP'));
		$this->co_quePCAP->setDbValue($rs->fields('co_quePCAP'));
		$this->co_quePCON->setDbValue($rs->fields('co_quePCON'));
		$this->co_queAPEX->setDbValue($rs->fields('co_queAPEX'));
		$this->co_quePLEX->setDbValue($rs->fields('co_quePLEX'));
		$this->co_queLTEX->setDbValue($rs->fields('co_queLTEX'));
		$this->co_queTOOL->setDbValue($rs->fields('co_queTOOL'));
		$this->co_queSITE->setDbValue($rs->fields('co_queSITE'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_ambiente->DbValue = $row['nu_ambiente'];
		$this->nu_versaoValoracao->DbValue = $row['nu_versaoValoracao'];
		$this->ic_metCalibracao->DbValue = $row['ic_metCalibracao'];
		$this->dh_inclusao->DbValue = $row['dh_inclusao'];
		$this->nu_usuarioResp->DbValue = $row['nu_usuarioResp'];
		$this->ic_tpAtualizacao->DbValue = $row['ic_tpAtualizacao'];
		$this->qt_linhasCodLingPf->DbValue = $row['qt_linhasCodLingPf'];
		$this->vr_ipMin->DbValue = $row['vr_ipMin'];
		$this->vr_ipMed->DbValue = $row['vr_ipMed'];
		$this->vr_ipMax->DbValue = $row['vr_ipMax'];
		$this->vr_constanteA->DbValue = $row['vr_constanteA'];
		$this->vr_constanteB->DbValue = $row['vr_constanteB'];
		$this->vr_constanteC->DbValue = $row['vr_constanteC'];
		$this->vr_constanteD->DbValue = $row['vr_constanteD'];
		$this->nu_altPREC->DbValue = $row['nu_altPREC'];
		$this->nu_altFLEX->DbValue = $row['nu_altFLEX'];
		$this->nu_altRESL->DbValue = $row['nu_altRESL'];
		$this->nu_altTEAM->DbValue = $row['nu_altTEAM'];
		$this->nu_altPMAT->DbValue = $row['nu_altPMAT'];
		$this->nu_altRELY->DbValue = $row['nu_altRELY'];
		$this->nu_altDATA->DbValue = $row['nu_altDATA'];
		$this->nu_altCPLX1->DbValue = $row['nu_altCPLX1'];
		$this->nu_altCPLX2->DbValue = $row['nu_altCPLX2'];
		$this->nu_altCPLX3->DbValue = $row['nu_altCPLX3'];
		$this->nu_altCPLX4->DbValue = $row['nu_altCPLX4'];
		$this->nu_altCPLX5->DbValue = $row['nu_altCPLX5'];
		$this->nu_altDOCU->DbValue = $row['nu_altDOCU'];
		$this->nu_altRUSE->DbValue = $row['nu_altRUSE'];
		$this->nu_altTIME->DbValue = $row['nu_altTIME'];
		$this->nu_altSTOR->DbValue = $row['nu_altSTOR'];
		$this->nu_altPVOL->DbValue = $row['nu_altPVOL'];
		$this->nu_altACAP->DbValue = $row['nu_altACAP'];
		$this->nu_altPCAP->DbValue = $row['nu_altPCAP'];
		$this->nu_altPCON->DbValue = $row['nu_altPCON'];
		$this->nu_altAPEX->DbValue = $row['nu_altAPEX'];
		$this->nu_altPLEX->DbValue = $row['nu_altPLEX'];
		$this->nu_altLTEX->DbValue = $row['nu_altLTEX'];
		$this->nu_altTOOL->DbValue = $row['nu_altTOOL'];
		$this->nu_altSITE->DbValue = $row['nu_altSITE'];
		$this->co_quePREC->DbValue = $row['co_quePREC'];
		$this->co_queFLEX->DbValue = $row['co_queFLEX'];
		$this->co_queRESL->DbValue = $row['co_queRESL'];
		$this->co_queTEAM->DbValue = $row['co_queTEAM'];
		$this->co_quePMAT->DbValue = $row['co_quePMAT'];
		$this->co_queRELY->DbValue = $row['co_queRELY'];
		$this->co_queDATA->DbValue = $row['co_queDATA'];
		$this->co_queCPLX1->DbValue = $row['co_queCPLX1'];
		$this->co_queCPLX2->DbValue = $row['co_queCPLX2'];
		$this->co_queCPLX3->DbValue = $row['co_queCPLX3'];
		$this->co_queCPLX4->DbValue = $row['co_queCPLX4'];
		$this->co_queCPLX5->DbValue = $row['co_queCPLX5'];
		$this->co_queDOCU->DbValue = $row['co_queDOCU'];
		$this->co_queRUSE->DbValue = $row['co_queRUSE'];
		$this->co_queTIME->DbValue = $row['co_queTIME'];
		$this->co_queSTOR->DbValue = $row['co_queSTOR'];
		$this->co_quePVOL->DbValue = $row['co_quePVOL'];
		$this->co_queACAP->DbValue = $row['co_queACAP'];
		$this->co_quePCAP->DbValue = $row['co_quePCAP'];
		$this->co_quePCON->DbValue = $row['co_quePCON'];
		$this->co_queAPEX->DbValue = $row['co_queAPEX'];
		$this->co_quePLEX->DbValue = $row['co_quePLEX'];
		$this->co_queLTEX->DbValue = $row['co_queLTEX'];
		$this->co_queTOOL->DbValue = $row['co_queTOOL'];
		$this->co_queSITE->DbValue = $row['co_queSITE'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->vr_ipMin->FormValue == $this->vr_ipMin->CurrentValue && is_numeric(ew_StrToFloat($this->vr_ipMin->CurrentValue)))
			$this->vr_ipMin->CurrentValue = ew_StrToFloat($this->vr_ipMin->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_ipMed->FormValue == $this->vr_ipMed->CurrentValue && is_numeric(ew_StrToFloat($this->vr_ipMed->CurrentValue)))
			$this->vr_ipMed->CurrentValue = ew_StrToFloat($this->vr_ipMed->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_ipMax->FormValue == $this->vr_ipMax->CurrentValue && is_numeric(ew_StrToFloat($this->vr_ipMax->CurrentValue)))
			$this->vr_ipMax->CurrentValue = ew_StrToFloat($this->vr_ipMax->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_constanteA->FormValue == $this->vr_constanteA->CurrentValue && is_numeric(ew_StrToFloat($this->vr_constanteA->CurrentValue)))
			$this->vr_constanteA->CurrentValue = ew_StrToFloat($this->vr_constanteA->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_constanteB->FormValue == $this->vr_constanteB->CurrentValue && is_numeric(ew_StrToFloat($this->vr_constanteB->CurrentValue)))
			$this->vr_constanteB->CurrentValue = ew_StrToFloat($this->vr_constanteB->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_constanteC->FormValue == $this->vr_constanteC->CurrentValue && is_numeric(ew_StrToFloat($this->vr_constanteC->CurrentValue)))
			$this->vr_constanteC->CurrentValue = ew_StrToFloat($this->vr_constanteC->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_constanteD->FormValue == $this->vr_constanteD->CurrentValue && is_numeric(ew_StrToFloat($this->vr_constanteD->CurrentValue)))
			$this->vr_constanteD->CurrentValue = ew_StrToFloat($this->vr_constanteD->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_ambiente
		// nu_versaoValoracao
		// ic_metCalibracao
		// dh_inclusao
		// nu_usuarioResp
		// ic_tpAtualizacao
		// qt_linhasCodLingPf
		// vr_ipMin
		// vr_ipMed
		// vr_ipMax
		// vr_constanteA
		// vr_constanteB
		// vr_constanteC
		// vr_constanteD
		// nu_altPREC
		// nu_altFLEX
		// nu_altRESL
		// nu_altTEAM
		// nu_altPMAT
		// nu_altRELY
		// nu_altDATA
		// nu_altCPLX1
		// nu_altCPLX2
		// nu_altCPLX3
		// nu_altCPLX4
		// nu_altCPLX5
		// nu_altDOCU
		// nu_altRUSE
		// nu_altTIME
		// nu_altSTOR
		// nu_altPVOL
		// nu_altACAP
		// nu_altPCAP
		// nu_altPCON
		// nu_altAPEX
		// nu_altPLEX
		// nu_altLTEX
		// nu_altTOOL
		// nu_altSITE
		// co_quePREC
		// co_queFLEX
		// co_queRESL
		// co_queTEAM
		// co_quePMAT
		// co_queRELY
		// co_queDATA
		// co_queCPLX1
		// co_queCPLX2
		// co_queCPLX3
		// co_queCPLX4
		// co_queCPLX5
		// co_queDOCU
		// co_queRUSE
		// co_queTIME
		// co_queSTOR
		// co_quePVOL
		// co_queACAP
		// co_quePCAP
		// co_quePCON
		// co_queAPEX
		// co_quePLEX
		// co_queLTEX
		// co_queTOOL
		// co_queSITE

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_ambiente
			$this->nu_ambiente->ViewValue = $this->nu_ambiente->CurrentValue;
			if (strval($this->nu_ambiente->CurrentValue) <> "") {
				$sFilterWrk = "[nu_ambiente]" . ew_SearchString("=", $this->nu_ambiente->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_ambiente], [nu_ambiente] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[contagempf]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_ambiente, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_ambiente->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_ambiente->ViewValue = $this->nu_ambiente->CurrentValue;
				}
			} else {
				$this->nu_ambiente->ViewValue = NULL;
			}
			$this->nu_ambiente->ViewCustomAttributes = "";

			// nu_versaoValoracao
			$this->nu_versaoValoracao->ViewValue = $this->nu_versaoValoracao->CurrentValue;
			$this->nu_versaoValoracao->ViewCustomAttributes = "";

			// ic_metCalibracao
			if (strval($this->ic_metCalibracao->CurrentValue) <> "") {
				switch ($this->ic_metCalibracao->CurrentValue) {
					case $this->ic_metCalibracao->FldTagValue(1):
						$this->ic_metCalibracao->ViewValue = $this->ic_metCalibracao->FldTagCaption(1) <> "" ? $this->ic_metCalibracao->FldTagCaption(1) : $this->ic_metCalibracao->CurrentValue;
						break;
					case $this->ic_metCalibracao->FldTagValue(2):
						$this->ic_metCalibracao->ViewValue = $this->ic_metCalibracao->FldTagCaption(2) <> "" ? $this->ic_metCalibracao->FldTagCaption(2) : $this->ic_metCalibracao->CurrentValue;
						break;
					default:
						$this->ic_metCalibracao->ViewValue = $this->ic_metCalibracao->CurrentValue;
				}
			} else {
				$this->ic_metCalibracao->ViewValue = NULL;
			}
			$this->ic_metCalibracao->ViewCustomAttributes = "";

			// dh_inclusao
			$this->dh_inclusao->ViewValue = $this->dh_inclusao->CurrentValue;
			$this->dh_inclusao->ViewValue = ew_FormatDateTime($this->dh_inclusao->ViewValue, 7);
			$this->dh_inclusao->ViewCustomAttributes = "";

			// nu_usuarioResp
			$this->nu_usuarioResp->ViewValue = $this->nu_usuarioResp->CurrentValue;
			if (strval($this->nu_usuarioResp->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioResp->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioResp, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioResp->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioResp->ViewValue = $this->nu_usuarioResp->CurrentValue;
				}
			} else {
				$this->nu_usuarioResp->ViewValue = NULL;
			}
			$this->nu_usuarioResp->ViewCustomAttributes = "";

			// ic_tpAtualizacao
			if (strval($this->ic_tpAtualizacao->CurrentValue) <> "") {
				switch ($this->ic_tpAtualizacao->CurrentValue) {
					case $this->ic_tpAtualizacao->FldTagValue(1):
						$this->ic_tpAtualizacao->ViewValue = $this->ic_tpAtualizacao->FldTagCaption(1) <> "" ? $this->ic_tpAtualizacao->FldTagCaption(1) : $this->ic_tpAtualizacao->CurrentValue;
						break;
					case $this->ic_tpAtualizacao->FldTagValue(2):
						$this->ic_tpAtualizacao->ViewValue = $this->ic_tpAtualizacao->FldTagCaption(2) <> "" ? $this->ic_tpAtualizacao->FldTagCaption(2) : $this->ic_tpAtualizacao->CurrentValue;
						break;
					default:
						$this->ic_tpAtualizacao->ViewValue = $this->ic_tpAtualizacao->CurrentValue;
				}
			} else {
				$this->ic_tpAtualizacao->ViewValue = NULL;
			}
			$this->ic_tpAtualizacao->ViewCustomAttributes = "";

			// qt_linhasCodLingPf
			$this->qt_linhasCodLingPf->ViewValue = $this->qt_linhasCodLingPf->CurrentValue;
			$this->qt_linhasCodLingPf->ViewCustomAttributes = "";

			// vr_ipMin
			$this->vr_ipMin->ViewValue = $this->vr_ipMin->CurrentValue;
			$this->vr_ipMin->ViewCustomAttributes = "";

			// vr_ipMed
			$this->vr_ipMed->ViewValue = $this->vr_ipMed->CurrentValue;
			$this->vr_ipMed->ViewCustomAttributes = "";

			// vr_ipMax
			$this->vr_ipMax->ViewValue = $this->vr_ipMax->CurrentValue;
			$this->vr_ipMax->ViewCustomAttributes = "";

			// vr_constanteA
			$this->vr_constanteA->ViewValue = $this->vr_constanteA->CurrentValue;
			$this->vr_constanteA->ViewCustomAttributes = "";

			// vr_constanteB
			$this->vr_constanteB->ViewValue = $this->vr_constanteB->CurrentValue;
			$this->vr_constanteB->ViewCustomAttributes = "";

			// vr_constanteC
			$this->vr_constanteC->ViewValue = $this->vr_constanteC->CurrentValue;
			$this->vr_constanteC->ViewCustomAttributes = "";

			// vr_constanteD
			$this->vr_constanteD->ViewValue = $this->vr_constanteD->CurrentValue;
			$this->vr_constanteD->ViewCustomAttributes = "";

			// nu_altPREC
			if ($this->nu_altPREC->VirtualValue <> "") {
				$this->nu_altPREC->ViewValue = $this->nu_altPREC->VirtualValue;
			} else {
			if (strval($this->nu_altPREC->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altPREC->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPREC, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altPREC->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altPREC->ViewValue .= ew_ValueSeparator(1,$this->nu_altPREC) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altPREC->ViewValue = $this->nu_altPREC->CurrentValue;
				}
			} else {
				$this->nu_altPREC->ViewValue = NULL;
			}
			}
			$this->nu_altPREC->ViewCustomAttributes = "";

			// nu_altFLEX
			if (strval($this->nu_altFLEX->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altFLEX->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altFLEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altFLEX->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altFLEX->ViewValue .= ew_ValueSeparator(1,$this->nu_altFLEX) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altFLEX->ViewValue = $this->nu_altFLEX->CurrentValue;
				}
			} else {
				$this->nu_altFLEX->ViewValue = NULL;
			}
			$this->nu_altFLEX->ViewCustomAttributes = "";

			// nu_altRESL
			if (strval($this->nu_altRESL->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altRESL->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altRESL, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altRESL->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altRESL->ViewValue .= ew_ValueSeparator(1,$this->nu_altRESL) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altRESL->ViewValue = $this->nu_altRESL->CurrentValue;
				}
			} else {
				$this->nu_altRESL->ViewValue = NULL;
			}
			$this->nu_altRESL->ViewCustomAttributes = "";

			// nu_altTEAM
			if ($this->nu_altTEAM->VirtualValue <> "") {
				$this->nu_altTEAM->ViewValue = $this->nu_altTEAM->VirtualValue;
			} else {
			if (strval($this->nu_altTEAM->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altTEAM->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altTEAM, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altTEAM->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altTEAM->ViewValue .= ew_ValueSeparator(1,$this->nu_altTEAM) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altTEAM->ViewValue = $this->nu_altTEAM->CurrentValue;
				}
			} else {
				$this->nu_altTEAM->ViewValue = NULL;
			}
			}
			$this->nu_altTEAM->ViewCustomAttributes = "";

			// nu_altPMAT
			if (strval($this->nu_altPMAT->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altPMAT->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPMAT, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altPMAT->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altPMAT->ViewValue .= ew_ValueSeparator(1,$this->nu_altPMAT) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altPMAT->ViewValue = $this->nu_altPMAT->CurrentValue;
				}
			} else {
				$this->nu_altPMAT->ViewValue = NULL;
			}
			$this->nu_altPMAT->ViewCustomAttributes = "";

			// nu_altRELY
			if (strval($this->nu_altRELY->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altRELY->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altRELY, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altRELY->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altRELY->ViewValue .= ew_ValueSeparator(1,$this->nu_altRELY) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altRELY->ViewValue = $this->nu_altRELY->CurrentValue;
				}
			} else {
				$this->nu_altRELY->ViewValue = NULL;
			}
			$this->nu_altRELY->ViewCustomAttributes = "";

			// nu_altDATA
			if (strval($this->nu_altDATA->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altDATA->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altDATA, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altDATA->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altDATA->ViewValue .= ew_ValueSeparator(1,$this->nu_altDATA) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altDATA->ViewValue = $this->nu_altDATA->CurrentValue;
				}
			} else {
				$this->nu_altDATA->ViewValue = NULL;
			}
			$this->nu_altDATA->ViewCustomAttributes = "";

			// nu_altCPLX1
			if (strval($this->nu_altCPLX1->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX1->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX1, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altCPLX1->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altCPLX1->ViewValue .= ew_ValueSeparator(1,$this->nu_altCPLX1) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altCPLX1->ViewValue = $this->nu_altCPLX1->CurrentValue;
				}
			} else {
				$this->nu_altCPLX1->ViewValue = NULL;
			}
			$this->nu_altCPLX1->ViewCustomAttributes = "";

			// nu_altCPLX2
			if (strval($this->nu_altCPLX2->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX2->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX2, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altCPLX2->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altCPLX2->ViewValue .= ew_ValueSeparator(1,$this->nu_altCPLX2) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altCPLX2->ViewValue = $this->nu_altCPLX2->CurrentValue;
				}
			} else {
				$this->nu_altCPLX2->ViewValue = NULL;
			}
			$this->nu_altCPLX2->ViewCustomAttributes = "";

			// nu_altCPLX3
			if ($this->nu_altCPLX3->VirtualValue <> "") {
				$this->nu_altCPLX3->ViewValue = $this->nu_altCPLX3->VirtualValue;
			} else {
			if (strval($this->nu_altCPLX3->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX3->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX3, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altCPLX3->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altCPLX3->ViewValue .= ew_ValueSeparator(1,$this->nu_altCPLX3) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altCPLX3->ViewValue = $this->nu_altCPLX3->CurrentValue;
				}
			} else {
				$this->nu_altCPLX3->ViewValue = NULL;
			}
			}
			$this->nu_altCPLX3->ViewCustomAttributes = "";

			// nu_altCPLX4
			if ($this->nu_altCPLX4->VirtualValue <> "") {
				$this->nu_altCPLX4->ViewValue = $this->nu_altCPLX4->VirtualValue;
			} else {
			if (strval($this->nu_altCPLX4->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX4->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX4, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altCPLX4->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altCPLX4->ViewValue .= ew_ValueSeparator(1,$this->nu_altCPLX4) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altCPLX4->ViewValue = $this->nu_altCPLX4->CurrentValue;
				}
			} else {
				$this->nu_altCPLX4->ViewValue = NULL;
			}
			}
			$this->nu_altCPLX4->ViewCustomAttributes = "";

			// nu_altCPLX5
			if (strval($this->nu_altCPLX5->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX5->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX5, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altCPLX5->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altCPLX5->ViewValue .= ew_ValueSeparator(1,$this->nu_altCPLX5) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altCPLX5->ViewValue = $this->nu_altCPLX5->CurrentValue;
				}
			} else {
				$this->nu_altCPLX5->ViewValue = NULL;
			}
			$this->nu_altCPLX5->ViewCustomAttributes = "";

			// nu_altDOCU
			if (strval($this->nu_altDOCU->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altDOCU->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altDOCU, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altDOCU->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altDOCU->ViewValue .= ew_ValueSeparator(1,$this->nu_altDOCU) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altDOCU->ViewValue = $this->nu_altDOCU->CurrentValue;
				}
			} else {
				$this->nu_altDOCU->ViewValue = NULL;
			}
			$this->nu_altDOCU->ViewCustomAttributes = "";

			// nu_altRUSE
			if (strval($this->nu_altRUSE->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altRUSE->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altRUSE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altRUSE->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altRUSE->ViewValue .= ew_ValueSeparator(1,$this->nu_altRUSE) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altRUSE->ViewValue = $this->nu_altRUSE->CurrentValue;
				}
			} else {
				$this->nu_altRUSE->ViewValue = NULL;
			}
			$this->nu_altRUSE->ViewCustomAttributes = "";

			// nu_altTIME
			if (strval($this->nu_altTIME->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altTIME->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altTIME, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altTIME->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altTIME->ViewValue .= ew_ValueSeparator(1,$this->nu_altTIME) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altTIME->ViewValue = $this->nu_altTIME->CurrentValue;
				}
			} else {
				$this->nu_altTIME->ViewValue = NULL;
			}
			$this->nu_altTIME->ViewCustomAttributes = "";

			// nu_altSTOR
			if (strval($this->nu_altSTOR->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altSTOR->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altSTOR, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altSTOR->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altSTOR->ViewValue .= ew_ValueSeparator(1,$this->nu_altSTOR) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altSTOR->ViewValue = $this->nu_altSTOR->CurrentValue;
				}
			} else {
				$this->nu_altSTOR->ViewValue = NULL;
			}
			$this->nu_altSTOR->ViewCustomAttributes = "";

			// nu_altPVOL
			if (strval($this->nu_altPVOL->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altPVOL->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPVOL, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altPVOL->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altPVOL->ViewValue .= ew_ValueSeparator(1,$this->nu_altPVOL) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altPVOL->ViewValue = $this->nu_altPVOL->CurrentValue;
				}
			} else {
				$this->nu_altPVOL->ViewValue = NULL;
			}
			$this->nu_altPVOL->ViewCustomAttributes = "";

			// nu_altACAP
			if (strval($this->nu_altACAP->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altACAP->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altACAP, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_alternativa] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altACAP->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altACAP->ViewValue .= ew_ValueSeparator(1,$this->nu_altACAP) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altACAP->ViewValue = $this->nu_altACAP->CurrentValue;
				}
			} else {
				$this->nu_altACAP->ViewValue = NULL;
			}
			$this->nu_altACAP->ViewCustomAttributes = "";

			// nu_altPCAP
			if (strval($this->nu_altPCAP->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altPCAP->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPCAP, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altPCAP->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altPCAP->ViewValue .= ew_ValueSeparator(1,$this->nu_altPCAP) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altPCAP->ViewValue = $this->nu_altPCAP->CurrentValue;
				}
			} else {
				$this->nu_altPCAP->ViewValue = NULL;
			}
			$this->nu_altPCAP->ViewCustomAttributes = "";

			// nu_altPCON
			if (strval($this->nu_altPCON->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altPCON->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPCON, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [ic_ativo] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altPCON->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altPCON->ViewValue .= ew_ValueSeparator(1,$this->nu_altPCON) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altPCON->ViewValue = $this->nu_altPCON->CurrentValue;
				}
			} else {
				$this->nu_altPCON->ViewValue = NULL;
			}
			$this->nu_altPCON->ViewCustomAttributes = "";

			// nu_altAPEX
			if (strval($this->nu_altAPEX->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altAPEX->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altAPEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altAPEX->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altAPEX->ViewValue .= ew_ValueSeparator(1,$this->nu_altAPEX) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altAPEX->ViewValue = $this->nu_altAPEX->CurrentValue;
				}
			} else {
				$this->nu_altAPEX->ViewValue = NULL;
			}
			$this->nu_altAPEX->ViewCustomAttributes = "";

			// nu_altPLEX
			if (strval($this->nu_altPLEX->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altPLEX->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPLEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altPLEX->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altPLEX->ViewValue .= ew_ValueSeparator(1,$this->nu_altPLEX) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altPLEX->ViewValue = $this->nu_altPLEX->CurrentValue;
				}
			} else {
				$this->nu_altPLEX->ViewValue = NULL;
			}
			$this->nu_altPLEX->ViewCustomAttributes = "";

			// nu_altLTEX
			if (strval($this->nu_altLTEX->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altLTEX->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altLTEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altLTEX->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altLTEX->ViewValue .= ew_ValueSeparator(1,$this->nu_altLTEX) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altLTEX->ViewValue = $this->nu_altLTEX->CurrentValue;
				}
			} else {
				$this->nu_altLTEX->ViewValue = NULL;
			}
			$this->nu_altLTEX->ViewCustomAttributes = "";

			// nu_altTOOL
			if (strval($this->nu_altTOOL->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altTOOL->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altTOOL, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altTOOL->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altTOOL->ViewValue .= ew_ValueSeparator(1,$this->nu_altTOOL) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altTOOL->ViewValue = $this->nu_altTOOL->CurrentValue;
				}
			} else {
				$this->nu_altTOOL->ViewValue = NULL;
			}
			$this->nu_altTOOL->ViewCustomAttributes = "";

			// nu_altSITE
			if (strval($this->nu_altSITE->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altSITE->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altSITE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altSITE->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altSITE->ViewValue .= ew_ValueSeparator(1,$this->nu_altSITE) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altSITE->ViewValue = $this->nu_altSITE->CurrentValue;
				}
			} else {
				$this->nu_altSITE->ViewValue = NULL;
			}
			$this->nu_altSITE->ViewCustomAttributes = "";

			// co_quePREC
			if (strval($this->co_quePREC->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_quePREC->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_quePREC, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_quePREC->ViewValue = $rswrk->fields('DispFld');
					$this->co_quePREC->ViewValue .= ew_ValueSeparator(1,$this->co_quePREC) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_quePREC->ViewValue = $this->co_quePREC->CurrentValue;
				}
			} else {
				$this->co_quePREC->ViewValue = NULL;
			}
			$this->co_quePREC->ViewCustomAttributes = "";

			// co_queFLEX
			if (strval($this->co_queFLEX->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queFLEX->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queFLEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queFLEX->ViewValue = $rswrk->fields('DispFld');
					$this->co_queFLEX->ViewValue .= ew_ValueSeparator(1,$this->co_queFLEX) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queFLEX->ViewValue = $this->co_queFLEX->CurrentValue;
				}
			} else {
				$this->co_queFLEX->ViewValue = NULL;
			}
			$this->co_queFLEX->ViewCustomAttributes = "";

			// co_queRESL
			if (strval($this->co_queRESL->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queRESL->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queRESL, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queRESL->ViewValue = $rswrk->fields('DispFld');
					$this->co_queRESL->ViewValue .= ew_ValueSeparator(1,$this->co_queRESL) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queRESL->ViewValue = $this->co_queRESL->CurrentValue;
				}
			} else {
				$this->co_queRESL->ViewValue = NULL;
			}
			$this->co_queRESL->ViewCustomAttributes = "";

			// co_queTEAM
			if (strval($this->co_queTEAM->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queTEAM->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queTEAM, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queTEAM->ViewValue = $rswrk->fields('DispFld');
					$this->co_queTEAM->ViewValue .= ew_ValueSeparator(1,$this->co_queTEAM) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queTEAM->ViewValue = $this->co_queTEAM->CurrentValue;
				}
			} else {
				$this->co_queTEAM->ViewValue = NULL;
			}
			$this->co_queTEAM->ViewCustomAttributes = "";

			// co_quePMAT
			if (strval($this->co_quePMAT->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_quePMAT->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_quePMAT, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_quePMAT->ViewValue = $rswrk->fields('DispFld');
					$this->co_quePMAT->ViewValue .= ew_ValueSeparator(1,$this->co_quePMAT) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_quePMAT->ViewValue = $this->co_quePMAT->CurrentValue;
				}
			} else {
				$this->co_quePMAT->ViewValue = NULL;
			}
			$this->co_quePMAT->ViewCustomAttributes = "";

			// co_queRELY
			if (strval($this->co_queRELY->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queRELY->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queRELY, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queRELY->ViewValue = $rswrk->fields('DispFld');
					$this->co_queRELY->ViewValue .= ew_ValueSeparator(1,$this->co_queRELY) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queRELY->ViewValue = $this->co_queRELY->CurrentValue;
				}
			} else {
				$this->co_queRELY->ViewValue = NULL;
			}
			$this->co_queRELY->ViewCustomAttributes = "";

			// co_queDATA
			if (strval($this->co_queDATA->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queDATA->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queDATA, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queDATA->ViewValue = $rswrk->fields('DispFld');
					$this->co_queDATA->ViewValue .= ew_ValueSeparator(1,$this->co_queDATA) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queDATA->ViewValue = $this->co_queDATA->CurrentValue;
				}
			} else {
				$this->co_queDATA->ViewValue = NULL;
			}
			$this->co_queDATA->ViewCustomAttributes = "";

			// co_queCPLX1
			if (strval($this->co_queCPLX1->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queCPLX1->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queCPLX1, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queCPLX1->ViewValue = $rswrk->fields('DispFld');
					$this->co_queCPLX1->ViewValue .= ew_ValueSeparator(1,$this->co_queCPLX1) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queCPLX1->ViewValue = $this->co_queCPLX1->CurrentValue;
				}
			} else {
				$this->co_queCPLX1->ViewValue = NULL;
			}
			$this->co_queCPLX1->ViewCustomAttributes = "";

			// co_queCPLX2
			if (strval($this->co_queCPLX2->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queCPLX2->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queCPLX2, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queCPLX2->ViewValue = $rswrk->fields('DispFld');
					$this->co_queCPLX2->ViewValue .= ew_ValueSeparator(1,$this->co_queCPLX2) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queCPLX2->ViewValue = $this->co_queCPLX2->CurrentValue;
				}
			} else {
				$this->co_queCPLX2->ViewValue = NULL;
			}
			$this->co_queCPLX2->ViewCustomAttributes = "";

			// co_queCPLX3
			if (strval($this->co_queCPLX3->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queCPLX3->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queCPLX3, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queCPLX3->ViewValue = $rswrk->fields('DispFld');
					$this->co_queCPLX3->ViewValue .= ew_ValueSeparator(1,$this->co_queCPLX3) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queCPLX3->ViewValue = $this->co_queCPLX3->CurrentValue;
				}
			} else {
				$this->co_queCPLX3->ViewValue = NULL;
			}
			$this->co_queCPLX3->ViewCustomAttributes = "";

			// co_queCPLX4
			if (strval($this->co_queCPLX4->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queCPLX4->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queCPLX4, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queCPLX4->ViewValue = $rswrk->fields('DispFld');
					$this->co_queCPLX4->ViewValue .= ew_ValueSeparator(1,$this->co_queCPLX4) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queCPLX4->ViewValue = $this->co_queCPLX4->CurrentValue;
				}
			} else {
				$this->co_queCPLX4->ViewValue = NULL;
			}
			$this->co_queCPLX4->ViewCustomAttributes = "";

			// co_queCPLX5
			if (strval($this->co_queCPLX5->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queCPLX5->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queCPLX5, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queCPLX5->ViewValue = $rswrk->fields('DispFld');
					$this->co_queCPLX5->ViewValue .= ew_ValueSeparator(1,$this->co_queCPLX5) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queCPLX5->ViewValue = $this->co_queCPLX5->CurrentValue;
				}
			} else {
				$this->co_queCPLX5->ViewValue = NULL;
			}
			$this->co_queCPLX5->ViewCustomAttributes = "";

			// co_queDOCU
			if (strval($this->co_queDOCU->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queDOCU->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queDOCU, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queDOCU->ViewValue = $rswrk->fields('DispFld');
					$this->co_queDOCU->ViewValue .= ew_ValueSeparator(1,$this->co_queDOCU) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queDOCU->ViewValue = $this->co_queDOCU->CurrentValue;
				}
			} else {
				$this->co_queDOCU->ViewValue = NULL;
			}
			$this->co_queDOCU->ViewCustomAttributes = "";

			// co_queRUSE
			if (strval($this->co_queRUSE->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queRUSE->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queRUSE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queRUSE->ViewValue = $rswrk->fields('DispFld');
					$this->co_queRUSE->ViewValue .= ew_ValueSeparator(1,$this->co_queRUSE) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queRUSE->ViewValue = $this->co_queRUSE->CurrentValue;
				}
			} else {
				$this->co_queRUSE->ViewValue = NULL;
			}
			$this->co_queRUSE->ViewCustomAttributes = "";

			// co_queTIME
			if (strval($this->co_queTIME->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queTIME->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queTIME, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queTIME->ViewValue = $rswrk->fields('DispFld');
					$this->co_queTIME->ViewValue .= ew_ValueSeparator(1,$this->co_queTIME) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queTIME->ViewValue = $this->co_queTIME->CurrentValue;
				}
			} else {
				$this->co_queTIME->ViewValue = NULL;
			}
			$this->co_queTIME->ViewCustomAttributes = "";

			// co_queSTOR
			if (strval($this->co_queSTOR->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queSTOR->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queSTOR, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queSTOR->ViewValue = $rswrk->fields('DispFld');
					$this->co_queSTOR->ViewValue .= ew_ValueSeparator(1,$this->co_queSTOR) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queSTOR->ViewValue = $this->co_queSTOR->CurrentValue;
				}
			} else {
				$this->co_queSTOR->ViewValue = NULL;
			}
			$this->co_queSTOR->ViewCustomAttributes = "";

			// co_quePVOL
			if (strval($this->co_quePVOL->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_quePVOL->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_quePVOL, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_quePVOL->ViewValue = $rswrk->fields('DispFld');
					$this->co_quePVOL->ViewValue .= ew_ValueSeparator(1,$this->co_quePVOL) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_quePVOL->ViewValue = $this->co_quePVOL->CurrentValue;
				}
			} else {
				$this->co_quePVOL->ViewValue = NULL;
			}
			$this->co_quePVOL->ViewCustomAttributes = "";

			// co_queACAP
			if (strval($this->co_queACAP->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queACAP->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queACAP, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queACAP->ViewValue = $rswrk->fields('DispFld');
					$this->co_queACAP->ViewValue .= ew_ValueSeparator(1,$this->co_queACAP) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queACAP->ViewValue = $this->co_queACAP->CurrentValue;
				}
			} else {
				$this->co_queACAP->ViewValue = NULL;
			}
			$this->co_queACAP->ViewCustomAttributes = "";

			// co_quePCAP
			if (strval($this->co_quePCAP->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_quePCAP->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_quePCAP, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_quePCAP->ViewValue = $rswrk->fields('DispFld');
					$this->co_quePCAP->ViewValue .= ew_ValueSeparator(1,$this->co_quePCAP) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_quePCAP->ViewValue = $this->co_quePCAP->CurrentValue;
				}
			} else {
				$this->co_quePCAP->ViewValue = NULL;
			}
			$this->co_quePCAP->ViewCustomAttributes = "";

			// co_quePCON
			if (strval($this->co_quePCON->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_quePCON->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_quePCON, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_quePCON->ViewValue = $rswrk->fields('DispFld');
					$this->co_quePCON->ViewValue .= ew_ValueSeparator(1,$this->co_quePCON) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_quePCON->ViewValue = $this->co_quePCON->CurrentValue;
				}
			} else {
				$this->co_quePCON->ViewValue = NULL;
			}
			$this->co_quePCON->ViewCustomAttributes = "";

			// co_queAPEX
			if (strval($this->co_queAPEX->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queAPEX->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queAPEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queAPEX->ViewValue = $rswrk->fields('DispFld');
					$this->co_queAPEX->ViewValue .= ew_ValueSeparator(1,$this->co_queAPEX) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queAPEX->ViewValue = $this->co_queAPEX->CurrentValue;
				}
			} else {
				$this->co_queAPEX->ViewValue = NULL;
			}
			$this->co_queAPEX->ViewCustomAttributes = "";

			// co_quePLEX
			if (strval($this->co_quePLEX->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_quePLEX->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_quePLEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_quePLEX->ViewValue = $rswrk->fields('DispFld');
					$this->co_quePLEX->ViewValue .= ew_ValueSeparator(1,$this->co_quePLEX) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_quePLEX->ViewValue = $this->co_quePLEX->CurrentValue;
				}
			} else {
				$this->co_quePLEX->ViewValue = NULL;
			}
			$this->co_quePLEX->ViewCustomAttributes = "";

			// co_queLTEX
			if (strval($this->co_queLTEX->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queLTEX->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queLTEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queLTEX->ViewValue = $rswrk->fields('DispFld');
					$this->co_queLTEX->ViewValue .= ew_ValueSeparator(1,$this->co_queLTEX) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queLTEX->ViewValue = $this->co_queLTEX->CurrentValue;
				}
			} else {
				$this->co_queLTEX->ViewValue = NULL;
			}
			$this->co_queLTEX->ViewCustomAttributes = "";

			// co_queTOOL
			if (strval($this->co_queTOOL->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queTOOL->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queTOOL, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queTOOL->ViewValue = $rswrk->fields('DispFld');
					$this->co_queTOOL->ViewValue .= ew_ValueSeparator(1,$this->co_queTOOL) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queTOOL->ViewValue = $this->co_queTOOL->CurrentValue;
				}
			} else {
				$this->co_queTOOL->ViewValue = NULL;
			}
			$this->co_queTOOL->ViewCustomAttributes = "";

			// co_queSITE
			if (strval($this->co_queSITE->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queSITE->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queSITE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queSITE->ViewValue = $rswrk->fields('DispFld');
					$this->co_queSITE->ViewValue .= ew_ValueSeparator(1,$this->co_queSITE) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queSITE->ViewValue = $this->co_queSITE->CurrentValue;
				}
			} else {
				$this->co_queSITE->ViewValue = NULL;
			}
			$this->co_queSITE->ViewCustomAttributes = "";

			// nu_versaoValoracao
			$this->nu_versaoValoracao->LinkCustomAttributes = "";
			$this->nu_versaoValoracao->HrefValue = "";
			$this->nu_versaoValoracao->TooltipValue = "";

			// ic_metCalibracao
			$this->ic_metCalibracao->LinkCustomAttributes = "";
			$this->ic_metCalibracao->HrefValue = "";
			$this->ic_metCalibracao->TooltipValue = "";

			// dh_inclusao
			$this->dh_inclusao->LinkCustomAttributes = "";
			$this->dh_inclusao->HrefValue = "";
			$this->dh_inclusao->TooltipValue = "";

			// nu_usuarioResp
			$this->nu_usuarioResp->LinkCustomAttributes = "";
			$this->nu_usuarioResp->HrefValue = "";
			$this->nu_usuarioResp->TooltipValue = "";

			// qt_linhasCodLingPf
			$this->qt_linhasCodLingPf->LinkCustomAttributes = "";
			$this->qt_linhasCodLingPf->HrefValue = "";
			$this->qt_linhasCodLingPf->TooltipValue = "";

			// vr_ipMin
			$this->vr_ipMin->LinkCustomAttributes = "";
			$this->vr_ipMin->HrefValue = "";
			$this->vr_ipMin->TooltipValue = "";

			// vr_ipMed
			$this->vr_ipMed->LinkCustomAttributes = "";
			$this->vr_ipMed->HrefValue = "";
			$this->vr_ipMed->TooltipValue = "";

			// vr_ipMax
			$this->vr_ipMax->LinkCustomAttributes = "";
			$this->vr_ipMax->HrefValue = "";
			$this->vr_ipMax->TooltipValue = "";

			// vr_constanteA
			$this->vr_constanteA->LinkCustomAttributes = "";
			$this->vr_constanteA->HrefValue = "";
			$this->vr_constanteA->TooltipValue = "";

			// vr_constanteB
			$this->vr_constanteB->LinkCustomAttributes = "";
			$this->vr_constanteB->HrefValue = "";
			$this->vr_constanteB->TooltipValue = "";

			// vr_constanteC
			$this->vr_constanteC->LinkCustomAttributes = "";
			$this->vr_constanteC->HrefValue = "";
			$this->vr_constanteC->TooltipValue = "";

			// vr_constanteD
			$this->vr_constanteD->LinkCustomAttributes = "";
			$this->vr_constanteD->HrefValue = "";
			$this->vr_constanteD->TooltipValue = "";

			// nu_altPREC
			$this->nu_altPREC->LinkCustomAttributes = "";
			$this->nu_altPREC->HrefValue = "";
			$this->nu_altPREC->TooltipValue = "";

			// nu_altFLEX
			$this->nu_altFLEX->LinkCustomAttributes = "";
			$this->nu_altFLEX->HrefValue = "";
			$this->nu_altFLEX->TooltipValue = "";

			// nu_altRESL
			$this->nu_altRESL->LinkCustomAttributes = "";
			$this->nu_altRESL->HrefValue = "";
			$this->nu_altRESL->TooltipValue = "";

			// nu_altTEAM
			$this->nu_altTEAM->LinkCustomAttributes = "";
			$this->nu_altTEAM->HrefValue = "";
			$this->nu_altTEAM->TooltipValue = "";

			// nu_altPMAT
			$this->nu_altPMAT->LinkCustomAttributes = "";
			$this->nu_altPMAT->HrefValue = "";
			$this->nu_altPMAT->TooltipValue = "";

			// nu_altRELY
			$this->nu_altRELY->LinkCustomAttributes = "";
			$this->nu_altRELY->HrefValue = "";
			$this->nu_altRELY->TooltipValue = "";

			// nu_altDATA
			$this->nu_altDATA->LinkCustomAttributes = "";
			$this->nu_altDATA->HrefValue = "";
			$this->nu_altDATA->TooltipValue = "";

			// nu_altCPLX1
			$this->nu_altCPLX1->LinkCustomAttributes = "";
			$this->nu_altCPLX1->HrefValue = "";
			$this->nu_altCPLX1->TooltipValue = "";

			// nu_altCPLX2
			$this->nu_altCPLX2->LinkCustomAttributes = "";
			$this->nu_altCPLX2->HrefValue = "";
			$this->nu_altCPLX2->TooltipValue = "";

			// nu_altCPLX3
			$this->nu_altCPLX3->LinkCustomAttributes = "";
			$this->nu_altCPLX3->HrefValue = "";
			$this->nu_altCPLX3->TooltipValue = "";

			// nu_altCPLX4
			$this->nu_altCPLX4->LinkCustomAttributes = "";
			$this->nu_altCPLX4->HrefValue = "";
			$this->nu_altCPLX4->TooltipValue = "";

			// nu_altCPLX5
			$this->nu_altCPLX5->LinkCustomAttributes = "";
			$this->nu_altCPLX5->HrefValue = "";
			$this->nu_altCPLX5->TooltipValue = "";

			// nu_altDOCU
			$this->nu_altDOCU->LinkCustomAttributes = "";
			$this->nu_altDOCU->HrefValue = "";
			$this->nu_altDOCU->TooltipValue = "";

			// nu_altRUSE
			$this->nu_altRUSE->LinkCustomAttributes = "";
			$this->nu_altRUSE->HrefValue = "";
			$this->nu_altRUSE->TooltipValue = "";

			// nu_altTIME
			$this->nu_altTIME->LinkCustomAttributes = "";
			$this->nu_altTIME->HrefValue = "";
			$this->nu_altTIME->TooltipValue = "";

			// nu_altSTOR
			$this->nu_altSTOR->LinkCustomAttributes = "";
			$this->nu_altSTOR->HrefValue = "";
			$this->nu_altSTOR->TooltipValue = "";

			// nu_altPVOL
			$this->nu_altPVOL->LinkCustomAttributes = "";
			$this->nu_altPVOL->HrefValue = "";
			$this->nu_altPVOL->TooltipValue = "";

			// nu_altACAP
			$this->nu_altACAP->LinkCustomAttributes = "";
			$this->nu_altACAP->HrefValue = "";
			$this->nu_altACAP->TooltipValue = "";

			// nu_altPCAP
			$this->nu_altPCAP->LinkCustomAttributes = "";
			$this->nu_altPCAP->HrefValue = "";
			$this->nu_altPCAP->TooltipValue = "";

			// nu_altPCON
			$this->nu_altPCON->LinkCustomAttributes = "";
			$this->nu_altPCON->HrefValue = "";
			$this->nu_altPCON->TooltipValue = "";

			// nu_altAPEX
			$this->nu_altAPEX->LinkCustomAttributes = "";
			$this->nu_altAPEX->HrefValue = "";
			$this->nu_altAPEX->TooltipValue = "";

			// nu_altPLEX
			$this->nu_altPLEX->LinkCustomAttributes = "";
			$this->nu_altPLEX->HrefValue = "";
			$this->nu_altPLEX->TooltipValue = "";

			// nu_altLTEX
			$this->nu_altLTEX->LinkCustomAttributes = "";
			$this->nu_altLTEX->HrefValue = "";
			$this->nu_altLTEX->TooltipValue = "";

			// nu_altTOOL
			$this->nu_altTOOL->LinkCustomAttributes = "";
			$this->nu_altTOOL->HrefValue = "";
			$this->nu_altTOOL->TooltipValue = "";

			// nu_altSITE
			$this->nu_altSITE->LinkCustomAttributes = "";
			$this->nu_altSITE->HrefValue = "";
			$this->nu_altSITE->TooltipValue = "";

			// co_quePREC
			$this->co_quePREC->LinkCustomAttributes = "";
			$this->co_quePREC->HrefValue = "";
			$this->co_quePREC->TooltipValue = "";

			// co_queFLEX
			$this->co_queFLEX->LinkCustomAttributes = "";
			$this->co_queFLEX->HrefValue = "";
			$this->co_queFLEX->TooltipValue = "";

			// co_queRESL
			$this->co_queRESL->LinkCustomAttributes = "";
			$this->co_queRESL->HrefValue = "";
			$this->co_queRESL->TooltipValue = "";

			// co_queTEAM
			$this->co_queTEAM->LinkCustomAttributes = "";
			$this->co_queTEAM->HrefValue = "";
			$this->co_queTEAM->TooltipValue = "";

			// co_quePMAT
			$this->co_quePMAT->LinkCustomAttributes = "";
			$this->co_quePMAT->HrefValue = "";
			$this->co_quePMAT->TooltipValue = "";

			// co_queRELY
			$this->co_queRELY->LinkCustomAttributes = "";
			$this->co_queRELY->HrefValue = "";
			$this->co_queRELY->TooltipValue = "";

			// co_queDATA
			$this->co_queDATA->LinkCustomAttributes = "";
			$this->co_queDATA->HrefValue = "";
			$this->co_queDATA->TooltipValue = "";

			// co_queCPLX1
			$this->co_queCPLX1->LinkCustomAttributes = "";
			$this->co_queCPLX1->HrefValue = "";
			$this->co_queCPLX1->TooltipValue = "";

			// co_queCPLX2
			$this->co_queCPLX2->LinkCustomAttributes = "";
			$this->co_queCPLX2->HrefValue = "";
			$this->co_queCPLX2->TooltipValue = "";

			// co_queCPLX3
			$this->co_queCPLX3->LinkCustomAttributes = "";
			$this->co_queCPLX3->HrefValue = "";
			$this->co_queCPLX3->TooltipValue = "";

			// co_queCPLX4
			$this->co_queCPLX4->LinkCustomAttributes = "";
			$this->co_queCPLX4->HrefValue = "";
			$this->co_queCPLX4->TooltipValue = "";

			// co_queCPLX5
			$this->co_queCPLX5->LinkCustomAttributes = "";
			$this->co_queCPLX5->HrefValue = "";
			$this->co_queCPLX5->TooltipValue = "";

			// co_queDOCU
			$this->co_queDOCU->LinkCustomAttributes = "";
			$this->co_queDOCU->HrefValue = "";
			$this->co_queDOCU->TooltipValue = "";

			// co_queRUSE
			$this->co_queRUSE->LinkCustomAttributes = "";
			$this->co_queRUSE->HrefValue = "";
			$this->co_queRUSE->TooltipValue = "";

			// co_queTIME
			$this->co_queTIME->LinkCustomAttributes = "";
			$this->co_queTIME->HrefValue = "";
			$this->co_queTIME->TooltipValue = "";

			// co_queSTOR
			$this->co_queSTOR->LinkCustomAttributes = "";
			$this->co_queSTOR->HrefValue = "";
			$this->co_queSTOR->TooltipValue = "";

			// co_quePVOL
			$this->co_quePVOL->LinkCustomAttributes = "";
			$this->co_quePVOL->HrefValue = "";
			$this->co_quePVOL->TooltipValue = "";

			// co_queACAP
			$this->co_queACAP->LinkCustomAttributes = "";
			$this->co_queACAP->HrefValue = "";
			$this->co_queACAP->TooltipValue = "";

			// co_quePCAP
			$this->co_quePCAP->LinkCustomAttributes = "";
			$this->co_quePCAP->HrefValue = "";
			$this->co_quePCAP->TooltipValue = "";

			// co_quePCON
			$this->co_quePCON->LinkCustomAttributes = "";
			$this->co_quePCON->HrefValue = "";
			$this->co_quePCON->TooltipValue = "";

			// co_queAPEX
			$this->co_queAPEX->LinkCustomAttributes = "";
			$this->co_queAPEX->HrefValue = "";
			$this->co_queAPEX->TooltipValue = "";

			// co_quePLEX
			$this->co_quePLEX->LinkCustomAttributes = "";
			$this->co_quePLEX->HrefValue = "";
			$this->co_quePLEX->TooltipValue = "";

			// co_queLTEX
			$this->co_queLTEX->LinkCustomAttributes = "";
			$this->co_queLTEX->HrefValue = "";
			$this->co_queLTEX->TooltipValue = "";

			// co_queTOOL
			$this->co_queTOOL->LinkCustomAttributes = "";
			$this->co_queTOOL->HrefValue = "";
			$this->co_queTOOL->TooltipValue = "";

			// co_queSITE
			$this->co_queSITE->LinkCustomAttributes = "";
			$this->co_queSITE->HrefValue = "";
			$this->co_queSITE->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// nu_versaoValoracao
			$this->nu_versaoValoracao->EditCustomAttributes = "";
			$this->nu_versaoValoracao->EditValue = $this->nu_versaoValoracao->CurrentValue;
			$this->nu_versaoValoracao->ViewCustomAttributes = "";

			// ic_metCalibracao
			$this->ic_metCalibracao->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_metCalibracao->FldTagValue(1), $this->ic_metCalibracao->FldTagCaption(1) <> "" ? $this->ic_metCalibracao->FldTagCaption(1) : $this->ic_metCalibracao->FldTagValue(1));
			$arwrk[] = array($this->ic_metCalibracao->FldTagValue(2), $this->ic_metCalibracao->FldTagCaption(2) <> "" ? $this->ic_metCalibracao->FldTagCaption(2) : $this->ic_metCalibracao->FldTagValue(2));
			$this->ic_metCalibracao->EditValue = $arwrk;

			// dh_inclusao
			// nu_usuarioResp
			// qt_linhasCodLingPf

			$this->qt_linhasCodLingPf->EditCustomAttributes = "";
			$this->qt_linhasCodLingPf->EditValue = ew_HtmlEncode($this->qt_linhasCodLingPf->CurrentValue);
			$this->qt_linhasCodLingPf->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_linhasCodLingPf->FldCaption()));

			// vr_ipMin
			$this->vr_ipMin->EditCustomAttributes = "";
			$this->vr_ipMin->EditValue = ew_HtmlEncode($this->vr_ipMin->CurrentValue);
			$this->vr_ipMin->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_ipMin->FldCaption()));
			if (strval($this->vr_ipMin->EditValue) <> "" && is_numeric($this->vr_ipMin->EditValue)) $this->vr_ipMin->EditValue = ew_FormatNumber($this->vr_ipMin->EditValue, -2, -1, -2, 0);

			// vr_ipMed
			$this->vr_ipMed->EditCustomAttributes = "";
			$this->vr_ipMed->EditValue = ew_HtmlEncode($this->vr_ipMed->CurrentValue);
			$this->vr_ipMed->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_ipMed->FldCaption()));
			if (strval($this->vr_ipMed->EditValue) <> "" && is_numeric($this->vr_ipMed->EditValue)) $this->vr_ipMed->EditValue = ew_FormatNumber($this->vr_ipMed->EditValue, -2, -1, -2, 0);

			// vr_ipMax
			$this->vr_ipMax->EditCustomAttributes = "";
			$this->vr_ipMax->EditValue = ew_HtmlEncode($this->vr_ipMax->CurrentValue);
			$this->vr_ipMax->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_ipMax->FldCaption()));
			if (strval($this->vr_ipMax->EditValue) <> "" && is_numeric($this->vr_ipMax->EditValue)) $this->vr_ipMax->EditValue = ew_FormatNumber($this->vr_ipMax->EditValue, -2, -1, -2, 0);

			// vr_constanteA
			$this->vr_constanteA->EditCustomAttributes = "";
			$this->vr_constanteA->EditValue = ew_HtmlEncode($this->vr_constanteA->CurrentValue);
			$this->vr_constanteA->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_constanteA->FldCaption()));
			if (strval($this->vr_constanteA->EditValue) <> "" && is_numeric($this->vr_constanteA->EditValue)) $this->vr_constanteA->EditValue = ew_FormatNumber($this->vr_constanteA->EditValue, -2, -1, -2, 0);

			// vr_constanteB
			$this->vr_constanteB->EditCustomAttributes = "";
			$this->vr_constanteB->EditValue = ew_HtmlEncode($this->vr_constanteB->CurrentValue);
			$this->vr_constanteB->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_constanteB->FldCaption()));
			if (strval($this->vr_constanteB->EditValue) <> "" && is_numeric($this->vr_constanteB->EditValue)) $this->vr_constanteB->EditValue = ew_FormatNumber($this->vr_constanteB->EditValue, -2, -1, -2, 0);

			// vr_constanteC
			$this->vr_constanteC->EditCustomAttributes = "";
			$this->vr_constanteC->EditValue = ew_HtmlEncode($this->vr_constanteC->CurrentValue);
			$this->vr_constanteC->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_constanteC->FldCaption()));
			if (strval($this->vr_constanteC->EditValue) <> "" && is_numeric($this->vr_constanteC->EditValue)) $this->vr_constanteC->EditValue = ew_FormatNumber($this->vr_constanteC->EditValue, -2, -1, -2, 0);

			// vr_constanteD
			$this->vr_constanteD->EditCustomAttributes = "";
			$this->vr_constanteD->EditValue = ew_HtmlEncode($this->vr_constanteD->CurrentValue);
			$this->vr_constanteD->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_constanteD->FldCaption()));
			if (strval($this->vr_constanteD->EditValue) <> "" && is_numeric($this->vr_constanteD->EditValue)) $this->vr_constanteD->EditValue = ew_FormatNumber($this->vr_constanteD->EditValue, -2, -1, -2, 0);

			// nu_altPREC
			$this->nu_altPREC->EditCustomAttributes = " width='600'";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [co_questao] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPREC, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altPREC->EditValue = $arwrk;

			// nu_altFLEX
			$this->nu_altFLEX->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [co_questao] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altFLEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altFLEX->EditValue = $arwrk;

			// nu_altRESL
			$this->nu_altRESL->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [co_questao] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altRESL, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altRESL->EditValue = $arwrk;

			// nu_altTEAM
			$this->nu_altTEAM->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [co_questao] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altTEAM, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altTEAM->EditValue = $arwrk;

			// nu_altPMAT
			$this->nu_altPMAT->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [co_questao] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPMAT, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altPMAT->EditValue = $arwrk;

			// nu_altRELY
			$this->nu_altRELY->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [co_questao] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altRELY, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altRELY->EditValue = $arwrk;

			// nu_altDATA
			$this->nu_altDATA->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [co_questao] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altDATA, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altDATA->EditValue = $arwrk;

			// nu_altCPLX1
			$this->nu_altCPLX1->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [co_questao] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX1, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altCPLX1->EditValue = $arwrk;

			// nu_altCPLX2
			$this->nu_altCPLX2->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [co_questao] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX2, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altCPLX2->EditValue = $arwrk;

			// nu_altCPLX3
			$this->nu_altCPLX3->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [co_questao] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX3, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altCPLX3->EditValue = $arwrk;

			// nu_altCPLX4
			$this->nu_altCPLX4->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [co_questao] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX4, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altCPLX4->EditValue = $arwrk;

			// nu_altCPLX5
			$this->nu_altCPLX5->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [co_questao] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX5, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altCPLX5->EditValue = $arwrk;

			// nu_altDOCU
			$this->nu_altDOCU->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [co_questao] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altDOCU, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altDOCU->EditValue = $arwrk;

			// nu_altRUSE
			$this->nu_altRUSE->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [co_questao] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altRUSE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altRUSE->EditValue = $arwrk;

			// nu_altTIME
			$this->nu_altTIME->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [co_questao] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altTIME, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altTIME->EditValue = $arwrk;

			// nu_altSTOR
			$this->nu_altSTOR->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [co_questao] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altSTOR, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altSTOR->EditValue = $arwrk;

			// nu_altPVOL
			$this->nu_altPVOL->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [co_questao] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPVOL, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altPVOL->EditValue = $arwrk;

			// nu_altACAP
			$this->nu_altACAP->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [co_questao] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altACAP, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_alternativa] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altACAP->EditValue = $arwrk;

			// nu_altPCAP
			$this->nu_altPCAP->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [co_questao] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPCAP, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altPCAP->EditValue = $arwrk;

			// nu_altPCON
			$this->nu_altPCON->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [co_questao] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPCON, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [ic_ativo] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altPCON->EditValue = $arwrk;

			// nu_altAPEX
			$this->nu_altAPEX->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [co_questao] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altAPEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altAPEX->EditValue = $arwrk;

			// nu_altPLEX
			$this->nu_altPLEX->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [co_questao] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPLEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altPLEX->EditValue = $arwrk;

			// nu_altLTEX
			$this->nu_altLTEX->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [co_questao] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altLTEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altLTEX->EditValue = $arwrk;

			// nu_altTOOL
			$this->nu_altTOOL->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [co_questao] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altTOOL, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altTOOL->EditValue = $arwrk;

			// nu_altSITE
			$this->nu_altSITE->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [co_questao] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altSITE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_altSITE->EditValue = $arwrk;

			// co_quePREC
			$this->co_quePREC->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_quePREC, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->co_quePREC->EditValue = $arwrk;

			// co_queFLEX
			$this->co_queFLEX->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queFLEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->co_queFLEX->EditValue = $arwrk;

			// co_queRESL
			$this->co_queRESL->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queRESL, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->co_queRESL->EditValue = $arwrk;

			// co_queTEAM
			$this->co_queTEAM->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queTEAM, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->co_queTEAM->EditValue = $arwrk;

			// co_quePMAT
			$this->co_quePMAT->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_quePMAT, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->co_quePMAT->EditValue = $arwrk;

			// co_queRELY
			$this->co_queRELY->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queRELY, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->co_queRELY->EditValue = $arwrk;

			// co_queDATA
			$this->co_queDATA->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queDATA, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->co_queDATA->EditValue = $arwrk;

			// co_queCPLX1
			$this->co_queCPLX1->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queCPLX1, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->co_queCPLX1->EditValue = $arwrk;

			// co_queCPLX2
			$this->co_queCPLX2->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queCPLX2, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->co_queCPLX2->EditValue = $arwrk;

			// co_queCPLX3
			$this->co_queCPLX3->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queCPLX3, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->co_queCPLX3->EditValue = $arwrk;

			// co_queCPLX4
			$this->co_queCPLX4->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queCPLX4, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->co_queCPLX4->EditValue = $arwrk;

			// co_queCPLX5
			$this->co_queCPLX5->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queCPLX5, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->co_queCPLX5->EditValue = $arwrk;

			// co_queDOCU
			$this->co_queDOCU->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queDOCU, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->co_queDOCU->EditValue = $arwrk;

			// co_queRUSE
			$this->co_queRUSE->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queRUSE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->co_queRUSE->EditValue = $arwrk;

			// co_queTIME
			$this->co_queTIME->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queTIME, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->co_queTIME->EditValue = $arwrk;

			// co_queSTOR
			$this->co_queSTOR->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queSTOR, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->co_queSTOR->EditValue = $arwrk;

			// co_quePVOL
			$this->co_quePVOL->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_quePVOL, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->co_quePVOL->EditValue = $arwrk;

			// co_queACAP
			$this->co_queACAP->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queACAP, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->co_queACAP->EditValue = $arwrk;

			// co_quePCAP
			$this->co_quePCAP->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_quePCAP, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->co_quePCAP->EditValue = $arwrk;

			// co_quePCON
			$this->co_quePCON->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_quePCON, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->co_quePCON->EditValue = $arwrk;

			// co_queAPEX
			$this->co_queAPEX->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queAPEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->co_queAPEX->EditValue = $arwrk;

			// co_quePLEX
			$this->co_quePLEX->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_quePLEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->co_quePLEX->EditValue = $arwrk;

			// co_queLTEX
			$this->co_queLTEX->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queLTEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->co_queLTEX->EditValue = $arwrk;

			// co_queTOOL
			$this->co_queTOOL->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queTOOL, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->co_queTOOL->EditValue = $arwrk;

			// co_queSITE
			$this->co_queSITE->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queSITE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->co_queSITE->EditValue = $arwrk;

			// Edit refer script
			// nu_versaoValoracao

			$this->nu_versaoValoracao->HrefValue = "";

			// ic_metCalibracao
			$this->ic_metCalibracao->HrefValue = "";

			// dh_inclusao
			$this->dh_inclusao->HrefValue = "";

			// nu_usuarioResp
			$this->nu_usuarioResp->HrefValue = "";

			// qt_linhasCodLingPf
			$this->qt_linhasCodLingPf->HrefValue = "";

			// vr_ipMin
			$this->vr_ipMin->HrefValue = "";

			// vr_ipMed
			$this->vr_ipMed->HrefValue = "";

			// vr_ipMax
			$this->vr_ipMax->HrefValue = "";

			// vr_constanteA
			$this->vr_constanteA->HrefValue = "";

			// vr_constanteB
			$this->vr_constanteB->HrefValue = "";

			// vr_constanteC
			$this->vr_constanteC->HrefValue = "";

			// vr_constanteD
			$this->vr_constanteD->HrefValue = "";

			// nu_altPREC
			$this->nu_altPREC->HrefValue = "";

			// nu_altFLEX
			$this->nu_altFLEX->HrefValue = "";

			// nu_altRESL
			$this->nu_altRESL->HrefValue = "";

			// nu_altTEAM
			$this->nu_altTEAM->HrefValue = "";

			// nu_altPMAT
			$this->nu_altPMAT->HrefValue = "";

			// nu_altRELY
			$this->nu_altRELY->HrefValue = "";

			// nu_altDATA
			$this->nu_altDATA->HrefValue = "";

			// nu_altCPLX1
			$this->nu_altCPLX1->HrefValue = "";

			// nu_altCPLX2
			$this->nu_altCPLX2->HrefValue = "";

			// nu_altCPLX3
			$this->nu_altCPLX3->HrefValue = "";

			// nu_altCPLX4
			$this->nu_altCPLX4->HrefValue = "";

			// nu_altCPLX5
			$this->nu_altCPLX5->HrefValue = "";

			// nu_altDOCU
			$this->nu_altDOCU->HrefValue = "";

			// nu_altRUSE
			$this->nu_altRUSE->HrefValue = "";

			// nu_altTIME
			$this->nu_altTIME->HrefValue = "";

			// nu_altSTOR
			$this->nu_altSTOR->HrefValue = "";

			// nu_altPVOL
			$this->nu_altPVOL->HrefValue = "";

			// nu_altACAP
			$this->nu_altACAP->HrefValue = "";

			// nu_altPCAP
			$this->nu_altPCAP->HrefValue = "";

			// nu_altPCON
			$this->nu_altPCON->HrefValue = "";

			// nu_altAPEX
			$this->nu_altAPEX->HrefValue = "";

			// nu_altPLEX
			$this->nu_altPLEX->HrefValue = "";

			// nu_altLTEX
			$this->nu_altLTEX->HrefValue = "";

			// nu_altTOOL
			$this->nu_altTOOL->HrefValue = "";

			// nu_altSITE
			$this->nu_altSITE->HrefValue = "";

			// co_quePREC
			$this->co_quePREC->HrefValue = "";

			// co_queFLEX
			$this->co_queFLEX->HrefValue = "";

			// co_queRESL
			$this->co_queRESL->HrefValue = "";

			// co_queTEAM
			$this->co_queTEAM->HrefValue = "";

			// co_quePMAT
			$this->co_quePMAT->HrefValue = "";

			// co_queRELY
			$this->co_queRELY->HrefValue = "";

			// co_queDATA
			$this->co_queDATA->HrefValue = "";

			// co_queCPLX1
			$this->co_queCPLX1->HrefValue = "";

			// co_queCPLX2
			$this->co_queCPLX2->HrefValue = "";

			// co_queCPLX3
			$this->co_queCPLX3->HrefValue = "";

			// co_queCPLX4
			$this->co_queCPLX4->HrefValue = "";

			// co_queCPLX5
			$this->co_queCPLX5->HrefValue = "";

			// co_queDOCU
			$this->co_queDOCU->HrefValue = "";

			// co_queRUSE
			$this->co_queRUSE->HrefValue = "";

			// co_queTIME
			$this->co_queTIME->HrefValue = "";

			// co_queSTOR
			$this->co_queSTOR->HrefValue = "";

			// co_quePVOL
			$this->co_quePVOL->HrefValue = "";

			// co_queACAP
			$this->co_queACAP->HrefValue = "";

			// co_quePCAP
			$this->co_quePCAP->HrefValue = "";

			// co_quePCON
			$this->co_quePCON->HrefValue = "";

			// co_queAPEX
			$this->co_queAPEX->HrefValue = "";

			// co_quePLEX
			$this->co_quePLEX->HrefValue = "";

			// co_queLTEX
			$this->co_queLTEX->HrefValue = "";

			// co_queTOOL
			$this->co_queTOOL->HrefValue = "";

			// co_queSITE
			$this->co_queSITE->HrefValue = "";
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
		if (!$this->nu_versaoValoracao->FldIsDetailKey && !is_null($this->nu_versaoValoracao->FormValue) && $this->nu_versaoValoracao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_versaoValoracao->FldCaption());
		}
		if (!ew_CheckInteger($this->nu_versaoValoracao->FormValue)) {
			ew_AddMessage($gsFormError, $this->nu_versaoValoracao->FldErrMsg());
		}
		if ($this->ic_metCalibracao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_metCalibracao->FldCaption());
		}
		if (!ew_CheckInteger($this->qt_linhasCodLingPf->FormValue)) {
			ew_AddMessage($gsFormError, $this->qt_linhasCodLingPf->FldErrMsg());
		}
		if (!$this->vr_ipMin->FldIsDetailKey && !is_null($this->vr_ipMin->FormValue) && $this->vr_ipMin->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->vr_ipMin->FldCaption());
		}
		if (!ew_CheckNumber($this->vr_ipMin->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_ipMin->FldErrMsg());
		}
		if (!$this->vr_ipMed->FldIsDetailKey && !is_null($this->vr_ipMed->FormValue) && $this->vr_ipMed->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->vr_ipMed->FldCaption());
		}
		if (!ew_CheckNumber($this->vr_ipMed->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_ipMed->FldErrMsg());
		}
		if (!$this->vr_ipMax->FldIsDetailKey && !is_null($this->vr_ipMax->FormValue) && $this->vr_ipMax->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->vr_ipMax->FldCaption());
		}
		if (!ew_CheckNumber($this->vr_ipMax->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_ipMax->FldErrMsg());
		}
		if (!ew_CheckNumber($this->vr_constanteA->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_constanteA->FldErrMsg());
		}
		if (!ew_CheckNumber($this->vr_constanteB->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_constanteB->FldErrMsg());
		}
		if (!ew_CheckNumber($this->vr_constanteC->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_constanteC->FldErrMsg());
		}
		if (!ew_CheckNumber($this->vr_constanteD->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_constanteD->FldErrMsg());
		}
		if (!$this->nu_altPREC->FldIsDetailKey && !is_null($this->nu_altPREC->FormValue) && $this->nu_altPREC->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_altPREC->FldCaption());
		}
		if (!$this->nu_altFLEX->FldIsDetailKey && !is_null($this->nu_altFLEX->FormValue) && $this->nu_altFLEX->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_altFLEX->FldCaption());
		}
		if (!$this->nu_altRESL->FldIsDetailKey && !is_null($this->nu_altRESL->FormValue) && $this->nu_altRESL->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_altRESL->FldCaption());
		}
		if (!$this->nu_altTEAM->FldIsDetailKey && !is_null($this->nu_altTEAM->FormValue) && $this->nu_altTEAM->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_altTEAM->FldCaption());
		}
		if (!$this->nu_altPMAT->FldIsDetailKey && !is_null($this->nu_altPMAT->FormValue) && $this->nu_altPMAT->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_altPMAT->FldCaption());
		}
		if (!$this->co_quePREC->FldIsDetailKey && !is_null($this->co_quePREC->FormValue) && $this->co_quePREC->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->co_quePREC->FldCaption());
		}
		if (!$this->co_queFLEX->FldIsDetailKey && !is_null($this->co_queFLEX->FormValue) && $this->co_queFLEX->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->co_queFLEX->FldCaption());
		}
		if (!$this->co_queRESL->FldIsDetailKey && !is_null($this->co_queRESL->FormValue) && $this->co_queRESL->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->co_queRESL->FldCaption());
		}
		if (!$this->co_queTEAM->FldIsDetailKey && !is_null($this->co_queTEAM->FormValue) && $this->co_queTEAM->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->co_queTEAM->FldCaption());
		}
		if (!$this->co_quePMAT->FldIsDetailKey && !is_null($this->co_quePMAT->FormValue) && $this->co_quePMAT->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->co_quePMAT->FldCaption());
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

			// nu_versaoValoracao
			// ic_metCalibracao

			$this->ic_metCalibracao->SetDbValueDef($rsnew, $this->ic_metCalibracao->CurrentValue, NULL, $this->ic_metCalibracao->ReadOnly);

			// dh_inclusao
			$this->dh_inclusao->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
			$rsnew['dh_inclusao'] = &$this->dh_inclusao->DbValue;

			// nu_usuarioResp
			$this->nu_usuarioResp->SetDbValueDef($rsnew, CurrentUserID(), NULL);
			$rsnew['nu_usuarioResp'] = &$this->nu_usuarioResp->DbValue;

			// qt_linhasCodLingPf
			$this->qt_linhasCodLingPf->SetDbValueDef($rsnew, $this->qt_linhasCodLingPf->CurrentValue, NULL, $this->qt_linhasCodLingPf->ReadOnly);

			// vr_ipMin
			$this->vr_ipMin->SetDbValueDef($rsnew, $this->vr_ipMin->CurrentValue, NULL, $this->vr_ipMin->ReadOnly);

			// vr_ipMed
			$this->vr_ipMed->SetDbValueDef($rsnew, $this->vr_ipMed->CurrentValue, NULL, $this->vr_ipMed->ReadOnly);

			// vr_ipMax
			$this->vr_ipMax->SetDbValueDef($rsnew, $this->vr_ipMax->CurrentValue, NULL, $this->vr_ipMax->ReadOnly);

			// vr_constanteA
			$this->vr_constanteA->SetDbValueDef($rsnew, $this->vr_constanteA->CurrentValue, NULL, $this->vr_constanteA->ReadOnly);

			// vr_constanteB
			$this->vr_constanteB->SetDbValueDef($rsnew, $this->vr_constanteB->CurrentValue, NULL, $this->vr_constanteB->ReadOnly);

			// vr_constanteC
			$this->vr_constanteC->SetDbValueDef($rsnew, $this->vr_constanteC->CurrentValue, NULL, $this->vr_constanteC->ReadOnly);

			// vr_constanteD
			$this->vr_constanteD->SetDbValueDef($rsnew, $this->vr_constanteD->CurrentValue, NULL, $this->vr_constanteD->ReadOnly);

			// nu_altPREC
			$this->nu_altPREC->SetDbValueDef($rsnew, $this->nu_altPREC->CurrentValue, NULL, $this->nu_altPREC->ReadOnly);

			// nu_altFLEX
			$this->nu_altFLEX->SetDbValueDef($rsnew, $this->nu_altFLEX->CurrentValue, NULL, $this->nu_altFLEX->ReadOnly);

			// nu_altRESL
			$this->nu_altRESL->SetDbValueDef($rsnew, $this->nu_altRESL->CurrentValue, NULL, $this->nu_altRESL->ReadOnly);

			// nu_altTEAM
			$this->nu_altTEAM->SetDbValueDef($rsnew, $this->nu_altTEAM->CurrentValue, NULL, $this->nu_altTEAM->ReadOnly);

			// nu_altPMAT
			$this->nu_altPMAT->SetDbValueDef($rsnew, $this->nu_altPMAT->CurrentValue, NULL, $this->nu_altPMAT->ReadOnly);

			// nu_altRELY
			$this->nu_altRELY->SetDbValueDef($rsnew, $this->nu_altRELY->CurrentValue, NULL, $this->nu_altRELY->ReadOnly);

			// nu_altDATA
			$this->nu_altDATA->SetDbValueDef($rsnew, $this->nu_altDATA->CurrentValue, NULL, $this->nu_altDATA->ReadOnly);

			// nu_altCPLX1
			$this->nu_altCPLX1->SetDbValueDef($rsnew, $this->nu_altCPLX1->CurrentValue, NULL, $this->nu_altCPLX1->ReadOnly);

			// nu_altCPLX2
			$this->nu_altCPLX2->SetDbValueDef($rsnew, $this->nu_altCPLX2->CurrentValue, NULL, $this->nu_altCPLX2->ReadOnly);

			// nu_altCPLX3
			$this->nu_altCPLX3->SetDbValueDef($rsnew, $this->nu_altCPLX3->CurrentValue, NULL, $this->nu_altCPLX3->ReadOnly);

			// nu_altCPLX4
			$this->nu_altCPLX4->SetDbValueDef($rsnew, $this->nu_altCPLX4->CurrentValue, NULL, $this->nu_altCPLX4->ReadOnly);

			// nu_altCPLX5
			$this->nu_altCPLX5->SetDbValueDef($rsnew, $this->nu_altCPLX5->CurrentValue, NULL, $this->nu_altCPLX5->ReadOnly);

			// nu_altDOCU
			$this->nu_altDOCU->SetDbValueDef($rsnew, $this->nu_altDOCU->CurrentValue, NULL, $this->nu_altDOCU->ReadOnly);

			// nu_altRUSE
			$this->nu_altRUSE->SetDbValueDef($rsnew, $this->nu_altRUSE->CurrentValue, NULL, $this->nu_altRUSE->ReadOnly);

			// nu_altTIME
			$this->nu_altTIME->SetDbValueDef($rsnew, $this->nu_altTIME->CurrentValue, NULL, $this->nu_altTIME->ReadOnly);

			// nu_altSTOR
			$this->nu_altSTOR->SetDbValueDef($rsnew, $this->nu_altSTOR->CurrentValue, NULL, $this->nu_altSTOR->ReadOnly);

			// nu_altPVOL
			$this->nu_altPVOL->SetDbValueDef($rsnew, $this->nu_altPVOL->CurrentValue, NULL, $this->nu_altPVOL->ReadOnly);

			// nu_altACAP
			$this->nu_altACAP->SetDbValueDef($rsnew, $this->nu_altACAP->CurrentValue, NULL, $this->nu_altACAP->ReadOnly);

			// nu_altPCAP
			$this->nu_altPCAP->SetDbValueDef($rsnew, $this->nu_altPCAP->CurrentValue, NULL, $this->nu_altPCAP->ReadOnly);

			// nu_altPCON
			$this->nu_altPCON->SetDbValueDef($rsnew, $this->nu_altPCON->CurrentValue, NULL, $this->nu_altPCON->ReadOnly);

			// nu_altAPEX
			$this->nu_altAPEX->SetDbValueDef($rsnew, $this->nu_altAPEX->CurrentValue, NULL, $this->nu_altAPEX->ReadOnly);

			// nu_altPLEX
			$this->nu_altPLEX->SetDbValueDef($rsnew, $this->nu_altPLEX->CurrentValue, NULL, $this->nu_altPLEX->ReadOnly);

			// nu_altLTEX
			$this->nu_altLTEX->SetDbValueDef($rsnew, $this->nu_altLTEX->CurrentValue, NULL, $this->nu_altLTEX->ReadOnly);

			// nu_altTOOL
			$this->nu_altTOOL->SetDbValueDef($rsnew, $this->nu_altTOOL->CurrentValue, NULL, $this->nu_altTOOL->ReadOnly);

			// nu_altSITE
			$this->nu_altSITE->SetDbValueDef($rsnew, $this->nu_altSITE->CurrentValue, NULL, $this->nu_altSITE->ReadOnly);

			// co_quePREC
			$this->co_quePREC->SetDbValueDef($rsnew, $this->co_quePREC->CurrentValue, NULL, $this->co_quePREC->ReadOnly);

			// co_queFLEX
			$this->co_queFLEX->SetDbValueDef($rsnew, $this->co_queFLEX->CurrentValue, NULL, $this->co_queFLEX->ReadOnly);

			// co_queRESL
			$this->co_queRESL->SetDbValueDef($rsnew, $this->co_queRESL->CurrentValue, NULL, $this->co_queRESL->ReadOnly);

			// co_queTEAM
			$this->co_queTEAM->SetDbValueDef($rsnew, $this->co_queTEAM->CurrentValue, NULL, $this->co_queTEAM->ReadOnly);

			// co_quePMAT
			$this->co_quePMAT->SetDbValueDef($rsnew, $this->co_quePMAT->CurrentValue, NULL, $this->co_quePMAT->ReadOnly);

			// co_queRELY
			$this->co_queRELY->SetDbValueDef($rsnew, $this->co_queRELY->CurrentValue, NULL, $this->co_queRELY->ReadOnly);

			// co_queDATA
			$this->co_queDATA->SetDbValueDef($rsnew, $this->co_queDATA->CurrentValue, NULL, $this->co_queDATA->ReadOnly);

			// co_queCPLX1
			$this->co_queCPLX1->SetDbValueDef($rsnew, $this->co_queCPLX1->CurrentValue, NULL, $this->co_queCPLX1->ReadOnly);

			// co_queCPLX2
			$this->co_queCPLX2->SetDbValueDef($rsnew, $this->co_queCPLX2->CurrentValue, NULL, $this->co_queCPLX2->ReadOnly);

			// co_queCPLX3
			$this->co_queCPLX3->SetDbValueDef($rsnew, $this->co_queCPLX3->CurrentValue, NULL, $this->co_queCPLX3->ReadOnly);

			// co_queCPLX4
			$this->co_queCPLX4->SetDbValueDef($rsnew, $this->co_queCPLX4->CurrentValue, NULL, $this->co_queCPLX4->ReadOnly);

			// co_queCPLX5
			$this->co_queCPLX5->SetDbValueDef($rsnew, $this->co_queCPLX5->CurrentValue, NULL, $this->co_queCPLX5->ReadOnly);

			// co_queDOCU
			$this->co_queDOCU->SetDbValueDef($rsnew, $this->co_queDOCU->CurrentValue, NULL, $this->co_queDOCU->ReadOnly);

			// co_queRUSE
			$this->co_queRUSE->SetDbValueDef($rsnew, $this->co_queRUSE->CurrentValue, NULL, $this->co_queRUSE->ReadOnly);

			// co_queTIME
			$this->co_queTIME->SetDbValueDef($rsnew, $this->co_queTIME->CurrentValue, NULL, $this->co_queTIME->ReadOnly);

			// co_queSTOR
			$this->co_queSTOR->SetDbValueDef($rsnew, $this->co_queSTOR->CurrentValue, NULL, $this->co_queSTOR->ReadOnly);

			// co_quePVOL
			$this->co_quePVOL->SetDbValueDef($rsnew, $this->co_quePVOL->CurrentValue, NULL, $this->co_quePVOL->ReadOnly);

			// co_queACAP
			$this->co_queACAP->SetDbValueDef($rsnew, $this->co_queACAP->CurrentValue, NULL, $this->co_queACAP->ReadOnly);

			// co_quePCAP
			$this->co_quePCAP->SetDbValueDef($rsnew, $this->co_quePCAP->CurrentValue, NULL, $this->co_quePCAP->ReadOnly);

			// co_quePCON
			$this->co_quePCON->SetDbValueDef($rsnew, $this->co_quePCON->CurrentValue, NULL, $this->co_quePCON->ReadOnly);

			// co_queAPEX
			$this->co_queAPEX->SetDbValueDef($rsnew, $this->co_queAPEX->CurrentValue, NULL, $this->co_queAPEX->ReadOnly);

			// co_quePLEX
			$this->co_quePLEX->SetDbValueDef($rsnew, $this->co_quePLEX->CurrentValue, NULL, $this->co_quePLEX->ReadOnly);

			// co_queLTEX
			$this->co_queLTEX->SetDbValueDef($rsnew, $this->co_queLTEX->CurrentValue, NULL, $this->co_queLTEX->ReadOnly);

			// co_queTOOL
			$this->co_queTOOL->SetDbValueDef($rsnew, $this->co_queTOOL->CurrentValue, NULL, $this->co_queTOOL->ReadOnly);

			// co_queSITE
			$this->co_queSITE->SetDbValueDef($rsnew, $this->co_queSITE->CurrentValue, NULL, $this->co_queSITE->ReadOnly);

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
			if ($sMasterTblVar == "ambiente") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_ambiente"] <> "") {
					$GLOBALS["ambiente"]->nu_ambiente->setQueryStringValue($_GET["nu_ambiente"]);
					$this->nu_ambiente->setQueryStringValue($GLOBALS["ambiente"]->nu_ambiente->QueryStringValue);
					$this->nu_ambiente->setSessionValue($this->nu_ambiente->QueryStringValue);
					if (!is_numeric($GLOBALS["ambiente"]->nu_ambiente->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "ambiente") {
				if ($this->nu_ambiente->QueryStringValue == "") $this->nu_ambiente->setSessionValue("");
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "ambiente_valoracaolist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("edit");
		$Breadcrumb->Add("edit", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'ambiente_valoracao';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		if (!$this->AuditTrailOnEdit) return;
		$table = 'ambiente_valoracao';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['nu_ambiente'];
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['nu_versaoValoracao'];

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
if (!isset($ambiente_valoracao_edit)) $ambiente_valoracao_edit = new cambiente_valoracao_edit();

// Page init
$ambiente_valoracao_edit->Page_Init();

// Page main
$ambiente_valoracao_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$ambiente_valoracao_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var ambiente_valoracao_edit = new ew_Page("ambiente_valoracao_edit");
ambiente_valoracao_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = ambiente_valoracao_edit.PageID; // For backward compatibility

// Form object
var fambiente_valoracaoedit = new ew_Form("fambiente_valoracaoedit");

// Validate form
fambiente_valoracaoedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_versaoValoracao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ambiente_valoracao->nu_versaoValoracao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_versaoValoracao");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ambiente_valoracao->nu_versaoValoracao->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ic_metCalibracao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ambiente_valoracao->ic_metCalibracao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_qt_linhasCodLingPf");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ambiente_valoracao->qt_linhasCodLingPf->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vr_ipMin");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ambiente_valoracao->vr_ipMin->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_ipMin");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ambiente_valoracao->vr_ipMin->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vr_ipMed");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ambiente_valoracao->vr_ipMed->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_ipMed");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ambiente_valoracao->vr_ipMed->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vr_ipMax");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ambiente_valoracao->vr_ipMax->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_ipMax");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ambiente_valoracao->vr_ipMax->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vr_constanteA");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ambiente_valoracao->vr_constanteA->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vr_constanteB");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ambiente_valoracao->vr_constanteB->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vr_constanteC");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ambiente_valoracao->vr_constanteC->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vr_constanteD");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ambiente_valoracao->vr_constanteD->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nu_altPREC");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ambiente_valoracao->nu_altPREC->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_altFLEX");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ambiente_valoracao->nu_altFLEX->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_altRESL");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ambiente_valoracao->nu_altRESL->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_altTEAM");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ambiente_valoracao->nu_altTEAM->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_altPMAT");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ambiente_valoracao->nu_altPMAT->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_co_quePREC");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ambiente_valoracao->co_quePREC->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_co_queFLEX");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ambiente_valoracao->co_queFLEX->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_co_queRESL");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ambiente_valoracao->co_queRESL->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_co_queTEAM");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ambiente_valoracao->co_queTEAM->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_co_quePMAT");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ambiente_valoracao->co_quePMAT->FldCaption()) ?>");

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
fambiente_valoracaoedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fambiente_valoracaoedit.ValidateRequired = true;
<?php } else { ?>
fambiente_valoracaoedit.ValidateRequired = false; 
<?php } ?>

// Multi-Page properties
fambiente_valoracaoedit.MultiPage = new ew_MultiPage("fambiente_valoracaoedit",
	[["x_nu_versaoValoracao",1],["x_ic_metCalibracao",1],["x_qt_linhasCodLingPf",1],["x_vr_ipMin",5],["x_vr_ipMed",5],["x_vr_ipMax",5],["x_vr_constanteA",2],["x_vr_constanteB",2],["x_vr_constanteC",2],["x_vr_constanteD",2],["x_nu_altPREC",4],["x_nu_altFLEX",4],["x_nu_altRESL",4],["x_nu_altTEAM",4],["x_nu_altPMAT",4],["x_nu_altRELY",4],["x_nu_altDATA",4],["x_nu_altCPLX1",4],["x_nu_altCPLX2",4],["x_nu_altCPLX3",4],["x_nu_altCPLX4",4],["x_nu_altCPLX5",4],["x_nu_altDOCU",4],["x_nu_altRUSE",4],["x_nu_altTIME",4],["x_nu_altSTOR",4],["x_nu_altPVOL",4],["x_nu_altACAP",4],["x_nu_altPCAP",4],["x_nu_altPCON",4],["x_nu_altAPEX",4],["x_nu_altPLEX",4],["x_nu_altLTEX",4],["x_nu_altTOOL",4],["x_nu_altSITE",4],["x_co_quePREC",3],["x_co_queFLEX",3],["x_co_queRESL",3],["x_co_queTEAM",3],["x_co_quePMAT",3],["x_co_queRELY",3],["x_co_queDATA",3],["x_co_queCPLX1",3],["x_co_queCPLX2",3],["x_co_queCPLX3",3],["x_co_queCPLX4",3],["x_co_queCPLX5",3],["x_co_queDOCU",3],["x_co_queRUSE",3],["x_co_queTIME",3],["x_co_queSTOR",3],["x_co_quePVOL",3],["x_co_queACAP",3],["x_co_quePCAP",3],["x_co_quePCON",3],["x_co_queAPEX",3],["x_co_quePLEX",3],["x_co_queLTEX",3],["x_co_queTOOL",3],["x_co_queSITE",3]]
);

// Dynamic selection lists
fambiente_valoracaoedit.Lists["x_nu_usuarioResp"] = {"LinkField":"x_nu_usuario","Ajax":true,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoedit.Lists["x_nu_altPREC"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":["x_co_quePREC"],"FilterFields":["x_co_questao"],"Options":[]};
fambiente_valoracaoedit.Lists["x_nu_altFLEX"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":["x_co_queFLEX"],"FilterFields":["x_co_questao"],"Options":[]};
fambiente_valoracaoedit.Lists["x_nu_altRESL"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":["x_co_queRESL"],"FilterFields":["x_co_questao"],"Options":[]};
fambiente_valoracaoedit.Lists["x_nu_altTEAM"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":["x_co_queTEAM"],"FilterFields":["x_co_questao"],"Options":[]};
fambiente_valoracaoedit.Lists["x_nu_altPMAT"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":["x_co_quePMAT"],"FilterFields":["x_co_questao"],"Options":[]};
fambiente_valoracaoedit.Lists["x_nu_altRELY"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":["x_co_queRELY"],"FilterFields":["x_co_questao"],"Options":[]};
fambiente_valoracaoedit.Lists["x_nu_altDATA"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":["x_co_queDATA"],"FilterFields":["x_co_questao"],"Options":[]};
fambiente_valoracaoedit.Lists["x_nu_altCPLX1"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":["x_co_queCPLX1"],"FilterFields":["x_co_questao"],"Options":[]};
fambiente_valoracaoedit.Lists["x_nu_altCPLX2"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":["x_co_queCPLX2"],"FilterFields":["x_co_questao"],"Options":[]};
fambiente_valoracaoedit.Lists["x_nu_altCPLX3"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":["x_co_queCPLX3"],"FilterFields":["x_co_questao"],"Options":[]};
fambiente_valoracaoedit.Lists["x_nu_altCPLX4"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":["x_co_queCPLX4"],"FilterFields":["x_co_questao"],"Options":[]};
fambiente_valoracaoedit.Lists["x_nu_altCPLX5"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":["x_co_queCPLX5"],"FilterFields":["x_co_questao"],"Options":[]};
fambiente_valoracaoedit.Lists["x_nu_altDOCU"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":["x_co_queDOCU"],"FilterFields":["x_co_questao"],"Options":[]};
fambiente_valoracaoedit.Lists["x_nu_altRUSE"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":["x_co_queRUSE"],"FilterFields":["x_co_questao"],"Options":[]};
fambiente_valoracaoedit.Lists["x_nu_altTIME"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":["x_co_queTIME"],"FilterFields":["x_co_questao"],"Options":[]};
fambiente_valoracaoedit.Lists["x_nu_altSTOR"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":["x_co_queSTOR"],"FilterFields":["x_co_questao"],"Options":[]};
fambiente_valoracaoedit.Lists["x_nu_altPVOL"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":["x_co_quePVOL"],"FilterFields":["x_co_questao"],"Options":[]};
fambiente_valoracaoedit.Lists["x_nu_altACAP"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":["x_co_queACAP"],"FilterFields":["x_co_questao"],"Options":[]};
fambiente_valoracaoedit.Lists["x_nu_altPCAP"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":["x_co_quePCAP"],"FilterFields":["x_co_questao"],"Options":[]};
fambiente_valoracaoedit.Lists["x_nu_altPCON"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":["x_co_quePCON"],"FilterFields":["x_co_questao"],"Options":[]};
fambiente_valoracaoedit.Lists["x_nu_altAPEX"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":["x_co_queAPEX"],"FilterFields":["x_co_questao"],"Options":[]};
fambiente_valoracaoedit.Lists["x_nu_altPLEX"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":["x_co_quePLEX"],"FilterFields":["x_co_questao"],"Options":[]};
fambiente_valoracaoedit.Lists["x_nu_altLTEX"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":["x_co_queLTEX"],"FilterFields":["x_co_questao"],"Options":[]};
fambiente_valoracaoedit.Lists["x_nu_altTOOL"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":["x_co_queTOOL"],"FilterFields":["x_co_questao"],"Options":[]};
fambiente_valoracaoedit.Lists["x_nu_altSITE"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":["x_co_queSITE"],"FilterFields":["x_co_questao"],"Options":[]};
fambiente_valoracaoedit.Lists["x_co_quePREC"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoedit.Lists["x_co_queFLEX"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoedit.Lists["x_co_queRESL"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoedit.Lists["x_co_queTEAM"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoedit.Lists["x_co_quePMAT"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoedit.Lists["x_co_queRELY"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoedit.Lists["x_co_queDATA"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoedit.Lists["x_co_queCPLX1"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoedit.Lists["x_co_queCPLX2"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoedit.Lists["x_co_queCPLX3"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoedit.Lists["x_co_queCPLX4"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoedit.Lists["x_co_queCPLX5"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoedit.Lists["x_co_queDOCU"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoedit.Lists["x_co_queRUSE"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoedit.Lists["x_co_queTIME"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoedit.Lists["x_co_queSTOR"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoedit.Lists["x_co_quePVOL"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoedit.Lists["x_co_queACAP"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoedit.Lists["x_co_quePCAP"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoedit.Lists["x_co_quePCON"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoedit.Lists["x_co_queAPEX"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoedit.Lists["x_co_quePLEX"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoedit.Lists["x_co_queLTEX"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoedit.Lists["x_co_queTOOL"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoedit.Lists["x_co_queSITE"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $ambiente_valoracao_edit->ShowPageHeader(); ?>
<?php
$ambiente_valoracao_edit->ShowMessage();
?>
<form name="fambiente_valoracaoedit" id="fambiente_valoracaoedit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="ambiente_valoracao">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table class="ewStdTable"><tbody><tr><td>
<div class="tabbable" id="ambiente_valoracao_edit">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_ambiente_valoracao1" data-toggle="tab"><?php echo $ambiente_valoracao->PageCaption(1) ?></a></li>
		<li><a href="#tab_ambiente_valoracao2" data-toggle="tab"><?php echo $ambiente_valoracao->PageCaption(2) ?></a></li>
		<li><a href="#tab_ambiente_valoracao3" data-toggle="tab"><?php echo $ambiente_valoracao->PageCaption(3) ?></a></li>
		<li><a href="#tab_ambiente_valoracao4" data-toggle="tab"><?php echo $ambiente_valoracao->PageCaption(4) ?></a></li>
		<li><a href="#tab_ambiente_valoracao5" data-toggle="tab"><?php echo $ambiente_valoracao->PageCaption(5) ?></a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab_ambiente_valoracao1">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_ambiente_valoracaoedit1" class="table table-bordered table-striped">
<?php if ($ambiente_valoracao->nu_versaoValoracao->Visible) { // nu_versaoValoracao ?>
	<tr id="r_nu_versaoValoracao">
		<td><span id="elh_ambiente_valoracao_nu_versaoValoracao"><?php echo $ambiente_valoracao->nu_versaoValoracao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_versaoValoracao->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_versaoValoracao" class="control-group">
<span<?php echo $ambiente_valoracao->nu_versaoValoracao->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->nu_versaoValoracao->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_versaoValoracao" name="x_nu_versaoValoracao" id="x_nu_versaoValoracao" value="<?php echo ew_HtmlEncode($ambiente_valoracao->nu_versaoValoracao->CurrentValue) ?>">
<?php echo $ambiente_valoracao->nu_versaoValoracao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->ic_metCalibracao->Visible) { // ic_metCalibracao ?>
	<tr id="r_ic_metCalibracao">
		<td><span id="elh_ambiente_valoracao_ic_metCalibracao"><?php echo $ambiente_valoracao->ic_metCalibracao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $ambiente_valoracao->ic_metCalibracao->CellAttributes() ?>>
<span id="el_ambiente_valoracao_ic_metCalibracao" class="control-group">
<div id="tp_x_ic_metCalibracao" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_metCalibracao" id="x_ic_metCalibracao" value="{value}"<?php echo $ambiente_valoracao->ic_metCalibracao->EditAttributes() ?>></div>
<div id="dsl_x_ic_metCalibracao" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $ambiente_valoracao->ic_metCalibracao->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->ic_metCalibracao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_metCalibracao" name="x_ic_metCalibracao" id="x_ic_metCalibracao_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $ambiente_valoracao->ic_metCalibracao->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $ambiente_valoracao->ic_metCalibracao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->qt_linhasCodLingPf->Visible) { // qt_linhasCodLingPf ?>
	<tr id="r_qt_linhasCodLingPf">
		<td><span id="elh_ambiente_valoracao_qt_linhasCodLingPf"><?php echo $ambiente_valoracao->qt_linhasCodLingPf->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->qt_linhasCodLingPf->CellAttributes() ?>>
<span id="el_ambiente_valoracao_qt_linhasCodLingPf" class="control-group">
<input type="text" data-field="x_qt_linhasCodLingPf" name="x_qt_linhasCodLingPf" id="x_qt_linhasCodLingPf" size="30" placeholder="<?php echo $ambiente_valoracao->qt_linhasCodLingPf->PlaceHolder ?>" value="<?php echo $ambiente_valoracao->qt_linhasCodLingPf->EditValue ?>"<?php echo $ambiente_valoracao->qt_linhasCodLingPf->EditAttributes() ?>>
</span>
<?php echo $ambiente_valoracao->qt_linhasCodLingPf->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_ambiente_valoracao2">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_ambiente_valoracaoedit2" class="table table-bordered table-striped">
<?php if ($ambiente_valoracao->vr_constanteA->Visible) { // vr_constanteA ?>
	<tr id="r_vr_constanteA">
		<td><span id="elh_ambiente_valoracao_vr_constanteA"><?php echo $ambiente_valoracao->vr_constanteA->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->vr_constanteA->CellAttributes() ?>>
<span id="el_ambiente_valoracao_vr_constanteA" class="control-group">
<input type="text" data-field="x_vr_constanteA" name="x_vr_constanteA" id="x_vr_constanteA" size="30" placeholder="<?php echo $ambiente_valoracao->vr_constanteA->PlaceHolder ?>" value="<?php echo $ambiente_valoracao->vr_constanteA->EditValue ?>"<?php echo $ambiente_valoracao->vr_constanteA->EditAttributes() ?>>
</span>
<?php echo $ambiente_valoracao->vr_constanteA->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->vr_constanteB->Visible) { // vr_constanteB ?>
	<tr id="r_vr_constanteB">
		<td><span id="elh_ambiente_valoracao_vr_constanteB"><?php echo $ambiente_valoracao->vr_constanteB->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->vr_constanteB->CellAttributes() ?>>
<span id="el_ambiente_valoracao_vr_constanteB" class="control-group">
<input type="text" data-field="x_vr_constanteB" name="x_vr_constanteB" id="x_vr_constanteB" size="30" placeholder="<?php echo $ambiente_valoracao->vr_constanteB->PlaceHolder ?>" value="<?php echo $ambiente_valoracao->vr_constanteB->EditValue ?>"<?php echo $ambiente_valoracao->vr_constanteB->EditAttributes() ?>>
</span>
<?php echo $ambiente_valoracao->vr_constanteB->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->vr_constanteC->Visible) { // vr_constanteC ?>
	<tr id="r_vr_constanteC">
		<td><span id="elh_ambiente_valoracao_vr_constanteC"><?php echo $ambiente_valoracao->vr_constanteC->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->vr_constanteC->CellAttributes() ?>>
<span id="el_ambiente_valoracao_vr_constanteC" class="control-group">
<input type="text" data-field="x_vr_constanteC" name="x_vr_constanteC" id="x_vr_constanteC" size="30" placeholder="<?php echo $ambiente_valoracao->vr_constanteC->PlaceHolder ?>" value="<?php echo $ambiente_valoracao->vr_constanteC->EditValue ?>"<?php echo $ambiente_valoracao->vr_constanteC->EditAttributes() ?>>
</span>
<?php echo $ambiente_valoracao->vr_constanteC->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->vr_constanteD->Visible) { // vr_constanteD ?>
	<tr id="r_vr_constanteD">
		<td><span id="elh_ambiente_valoracao_vr_constanteD"><?php echo $ambiente_valoracao->vr_constanteD->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->vr_constanteD->CellAttributes() ?>>
<span id="el_ambiente_valoracao_vr_constanteD" class="control-group">
<input type="text" data-field="x_vr_constanteD" name="x_vr_constanteD" id="x_vr_constanteD" size="30" placeholder="<?php echo $ambiente_valoracao->vr_constanteD->PlaceHolder ?>" value="<?php echo $ambiente_valoracao->vr_constanteD->EditValue ?>"<?php echo $ambiente_valoracao->vr_constanteD->EditAttributes() ?>>
</span>
<?php echo $ambiente_valoracao->vr_constanteD->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_ambiente_valoracao3">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_ambiente_valoracaoedit3" class="table table-bordered table-striped">
<?php if ($ambiente_valoracao->co_quePREC->Visible) { // co_quePREC ?>
	<tr id="r_co_quePREC">
		<td><span id="elh_ambiente_valoracao_co_quePREC"><?php echo $ambiente_valoracao->co_quePREC->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $ambiente_valoracao->co_quePREC->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_quePREC" class="control-group">
<?php $ambiente_valoracao->co_quePREC->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_altPREC']); " . @$ambiente_valoracao->co_quePREC->EditAttrs["onchange"]; ?>
<select data-field="x_co_quePREC" id="x_co_quePREC" name="x_co_quePREC"<?php echo $ambiente_valoracao->co_quePREC->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->co_quePREC->EditValue)) {
	$arwrk = $ambiente_valoracao->co_quePREC->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->co_quePREC->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->co_quePREC) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_co_quePREC"].Options = <?php echo (is_array($ambiente_valoracao->co_quePREC->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->co_quePREC->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->co_quePREC->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queFLEX->Visible) { // co_queFLEX ?>
	<tr id="r_co_queFLEX">
		<td><span id="elh_ambiente_valoracao_co_queFLEX"><?php echo $ambiente_valoracao->co_queFLEX->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queFLEX->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queFLEX" class="control-group">
<?php $ambiente_valoracao->co_queFLEX->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_altFLEX']); " . @$ambiente_valoracao->co_queFLEX->EditAttrs["onchange"]; ?>
<select data-field="x_co_queFLEX" id="x_co_queFLEX" name="x_co_queFLEX"<?php echo $ambiente_valoracao->co_queFLEX->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->co_queFLEX->EditValue)) {
	$arwrk = $ambiente_valoracao->co_queFLEX->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->co_queFLEX->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->co_queFLEX) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_co_queFLEX"].Options = <?php echo (is_array($ambiente_valoracao->co_queFLEX->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->co_queFLEX->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->co_queFLEX->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queRESL->Visible) { // co_queRESL ?>
	<tr id="r_co_queRESL">
		<td><span id="elh_ambiente_valoracao_co_queRESL"><?php echo $ambiente_valoracao->co_queRESL->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queRESL->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queRESL" class="control-group">
<?php $ambiente_valoracao->co_queRESL->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_altRESL']); " . @$ambiente_valoracao->co_queRESL->EditAttrs["onchange"]; ?>
<select data-field="x_co_queRESL" id="x_co_queRESL" name="x_co_queRESL"<?php echo $ambiente_valoracao->co_queRESL->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->co_queRESL->EditValue)) {
	$arwrk = $ambiente_valoracao->co_queRESL->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->co_queRESL->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->co_queRESL) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_co_queRESL"].Options = <?php echo (is_array($ambiente_valoracao->co_queRESL->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->co_queRESL->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->co_queRESL->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queTEAM->Visible) { // co_queTEAM ?>
	<tr id="r_co_queTEAM">
		<td><span id="elh_ambiente_valoracao_co_queTEAM"><?php echo $ambiente_valoracao->co_queTEAM->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queTEAM->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queTEAM" class="control-group">
<?php $ambiente_valoracao->co_queTEAM->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_altTEAM']); " . @$ambiente_valoracao->co_queTEAM->EditAttrs["onchange"]; ?>
<select data-field="x_co_queTEAM" id="x_co_queTEAM" name="x_co_queTEAM"<?php echo $ambiente_valoracao->co_queTEAM->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->co_queTEAM->EditValue)) {
	$arwrk = $ambiente_valoracao->co_queTEAM->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->co_queTEAM->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->co_queTEAM) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_co_queTEAM"].Options = <?php echo (is_array($ambiente_valoracao->co_queTEAM->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->co_queTEAM->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->co_queTEAM->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_quePMAT->Visible) { // co_quePMAT ?>
	<tr id="r_co_quePMAT">
		<td><span id="elh_ambiente_valoracao_co_quePMAT"><?php echo $ambiente_valoracao->co_quePMAT->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $ambiente_valoracao->co_quePMAT->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_quePMAT" class="control-group">
<?php $ambiente_valoracao->co_quePMAT->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_altPMAT']); " . @$ambiente_valoracao->co_quePMAT->EditAttrs["onchange"]; ?>
<select data-field="x_co_quePMAT" id="x_co_quePMAT" name="x_co_quePMAT"<?php echo $ambiente_valoracao->co_quePMAT->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->co_quePMAT->EditValue)) {
	$arwrk = $ambiente_valoracao->co_quePMAT->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->co_quePMAT->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->co_quePMAT) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_co_quePMAT"].Options = <?php echo (is_array($ambiente_valoracao->co_quePMAT->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->co_quePMAT->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->co_quePMAT->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queRELY->Visible) { // co_queRELY ?>
	<tr id="r_co_queRELY">
		<td><span id="elh_ambiente_valoracao_co_queRELY"><?php echo $ambiente_valoracao->co_queRELY->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queRELY->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queRELY" class="control-group">
<?php $ambiente_valoracao->co_queRELY->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_altRELY']); " . @$ambiente_valoracao->co_queRELY->EditAttrs["onchange"]; ?>
<select data-field="x_co_queRELY" id="x_co_queRELY" name="x_co_queRELY"<?php echo $ambiente_valoracao->co_queRELY->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->co_queRELY->EditValue)) {
	$arwrk = $ambiente_valoracao->co_queRELY->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->co_queRELY->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->co_queRELY) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_co_queRELY"].Options = <?php echo (is_array($ambiente_valoracao->co_queRELY->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->co_queRELY->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->co_queRELY->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queDATA->Visible) { // co_queDATA ?>
	<tr id="r_co_queDATA">
		<td><span id="elh_ambiente_valoracao_co_queDATA"><?php echo $ambiente_valoracao->co_queDATA->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queDATA->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queDATA" class="control-group">
<?php $ambiente_valoracao->co_queDATA->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_altDATA']); " . @$ambiente_valoracao->co_queDATA->EditAttrs["onchange"]; ?>
<select data-field="x_co_queDATA" id="x_co_queDATA" name="x_co_queDATA"<?php echo $ambiente_valoracao->co_queDATA->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->co_queDATA->EditValue)) {
	$arwrk = $ambiente_valoracao->co_queDATA->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->co_queDATA->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->co_queDATA) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_co_queDATA"].Options = <?php echo (is_array($ambiente_valoracao->co_queDATA->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->co_queDATA->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->co_queDATA->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queCPLX1->Visible) { // co_queCPLX1 ?>
	<tr id="r_co_queCPLX1">
		<td><span id="elh_ambiente_valoracao_co_queCPLX1"><?php echo $ambiente_valoracao->co_queCPLX1->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queCPLX1->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queCPLX1" class="control-group">
<?php $ambiente_valoracao->co_queCPLX1->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_altCPLX1']); " . @$ambiente_valoracao->co_queCPLX1->EditAttrs["onchange"]; ?>
<select data-field="x_co_queCPLX1" id="x_co_queCPLX1" name="x_co_queCPLX1"<?php echo $ambiente_valoracao->co_queCPLX1->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->co_queCPLX1->EditValue)) {
	$arwrk = $ambiente_valoracao->co_queCPLX1->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->co_queCPLX1->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->co_queCPLX1) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_co_queCPLX1"].Options = <?php echo (is_array($ambiente_valoracao->co_queCPLX1->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->co_queCPLX1->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->co_queCPLX1->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queCPLX2->Visible) { // co_queCPLX2 ?>
	<tr id="r_co_queCPLX2">
		<td><span id="elh_ambiente_valoracao_co_queCPLX2"><?php echo $ambiente_valoracao->co_queCPLX2->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queCPLX2->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queCPLX2" class="control-group">
<?php $ambiente_valoracao->co_queCPLX2->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_altCPLX2']); " . @$ambiente_valoracao->co_queCPLX2->EditAttrs["onchange"]; ?>
<select data-field="x_co_queCPLX2" id="x_co_queCPLX2" name="x_co_queCPLX2"<?php echo $ambiente_valoracao->co_queCPLX2->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->co_queCPLX2->EditValue)) {
	$arwrk = $ambiente_valoracao->co_queCPLX2->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->co_queCPLX2->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->co_queCPLX2) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_co_queCPLX2"].Options = <?php echo (is_array($ambiente_valoracao->co_queCPLX2->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->co_queCPLX2->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->co_queCPLX2->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queCPLX3->Visible) { // co_queCPLX3 ?>
	<tr id="r_co_queCPLX3">
		<td><span id="elh_ambiente_valoracao_co_queCPLX3"><?php echo $ambiente_valoracao->co_queCPLX3->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queCPLX3->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queCPLX3" class="control-group">
<?php $ambiente_valoracao->co_queCPLX3->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_altCPLX3']); " . @$ambiente_valoracao->co_queCPLX3->EditAttrs["onchange"]; ?>
<select data-field="x_co_queCPLX3" id="x_co_queCPLX3" name="x_co_queCPLX3"<?php echo $ambiente_valoracao->co_queCPLX3->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->co_queCPLX3->EditValue)) {
	$arwrk = $ambiente_valoracao->co_queCPLX3->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->co_queCPLX3->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->co_queCPLX3) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_co_queCPLX3"].Options = <?php echo (is_array($ambiente_valoracao->co_queCPLX3->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->co_queCPLX3->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->co_queCPLX3->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queCPLX4->Visible) { // co_queCPLX4 ?>
	<tr id="r_co_queCPLX4">
		<td><span id="elh_ambiente_valoracao_co_queCPLX4"><?php echo $ambiente_valoracao->co_queCPLX4->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queCPLX4->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queCPLX4" class="control-group">
<?php $ambiente_valoracao->co_queCPLX4->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_altCPLX4']); " . @$ambiente_valoracao->co_queCPLX4->EditAttrs["onchange"]; ?>
<select data-field="x_co_queCPLX4" id="x_co_queCPLX4" name="x_co_queCPLX4"<?php echo $ambiente_valoracao->co_queCPLX4->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->co_queCPLX4->EditValue)) {
	$arwrk = $ambiente_valoracao->co_queCPLX4->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->co_queCPLX4->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->co_queCPLX4) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_co_queCPLX4"].Options = <?php echo (is_array($ambiente_valoracao->co_queCPLX4->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->co_queCPLX4->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->co_queCPLX4->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queCPLX5->Visible) { // co_queCPLX5 ?>
	<tr id="r_co_queCPLX5">
		<td><span id="elh_ambiente_valoracao_co_queCPLX5"><?php echo $ambiente_valoracao->co_queCPLX5->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queCPLX5->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queCPLX5" class="control-group">
<?php $ambiente_valoracao->co_queCPLX5->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_altCPLX5']); " . @$ambiente_valoracao->co_queCPLX5->EditAttrs["onchange"]; ?>
<select data-field="x_co_queCPLX5" id="x_co_queCPLX5" name="x_co_queCPLX5"<?php echo $ambiente_valoracao->co_queCPLX5->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->co_queCPLX5->EditValue)) {
	$arwrk = $ambiente_valoracao->co_queCPLX5->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->co_queCPLX5->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->co_queCPLX5) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_co_queCPLX5"].Options = <?php echo (is_array($ambiente_valoracao->co_queCPLX5->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->co_queCPLX5->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->co_queCPLX5->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queDOCU->Visible) { // co_queDOCU ?>
	<tr id="r_co_queDOCU">
		<td><span id="elh_ambiente_valoracao_co_queDOCU"><?php echo $ambiente_valoracao->co_queDOCU->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queDOCU->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queDOCU" class="control-group">
<?php $ambiente_valoracao->co_queDOCU->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_altDOCU']); " . @$ambiente_valoracao->co_queDOCU->EditAttrs["onchange"]; ?>
<select data-field="x_co_queDOCU" id="x_co_queDOCU" name="x_co_queDOCU"<?php echo $ambiente_valoracao->co_queDOCU->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->co_queDOCU->EditValue)) {
	$arwrk = $ambiente_valoracao->co_queDOCU->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->co_queDOCU->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->co_queDOCU) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_co_queDOCU"].Options = <?php echo (is_array($ambiente_valoracao->co_queDOCU->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->co_queDOCU->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->co_queDOCU->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queRUSE->Visible) { // co_queRUSE ?>
	<tr id="r_co_queRUSE">
		<td><span id="elh_ambiente_valoracao_co_queRUSE"><?php echo $ambiente_valoracao->co_queRUSE->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queRUSE->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queRUSE" class="control-group">
<?php $ambiente_valoracao->co_queRUSE->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_altRUSE']); " . @$ambiente_valoracao->co_queRUSE->EditAttrs["onchange"]; ?>
<select data-field="x_co_queRUSE" id="x_co_queRUSE" name="x_co_queRUSE"<?php echo $ambiente_valoracao->co_queRUSE->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->co_queRUSE->EditValue)) {
	$arwrk = $ambiente_valoracao->co_queRUSE->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->co_queRUSE->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->co_queRUSE) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_co_queRUSE"].Options = <?php echo (is_array($ambiente_valoracao->co_queRUSE->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->co_queRUSE->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->co_queRUSE->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queTIME->Visible) { // co_queTIME ?>
	<tr id="r_co_queTIME">
		<td><span id="elh_ambiente_valoracao_co_queTIME"><?php echo $ambiente_valoracao->co_queTIME->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queTIME->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queTIME" class="control-group">
<?php $ambiente_valoracao->co_queTIME->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_altTIME']); " . @$ambiente_valoracao->co_queTIME->EditAttrs["onchange"]; ?>
<select data-field="x_co_queTIME" id="x_co_queTIME" name="x_co_queTIME"<?php echo $ambiente_valoracao->co_queTIME->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->co_queTIME->EditValue)) {
	$arwrk = $ambiente_valoracao->co_queTIME->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->co_queTIME->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->co_queTIME) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_co_queTIME"].Options = <?php echo (is_array($ambiente_valoracao->co_queTIME->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->co_queTIME->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->co_queTIME->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queSTOR->Visible) { // co_queSTOR ?>
	<tr id="r_co_queSTOR">
		<td><span id="elh_ambiente_valoracao_co_queSTOR"><?php echo $ambiente_valoracao->co_queSTOR->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queSTOR->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queSTOR" class="control-group">
<?php $ambiente_valoracao->co_queSTOR->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_altSTOR']); " . @$ambiente_valoracao->co_queSTOR->EditAttrs["onchange"]; ?>
<select data-field="x_co_queSTOR" id="x_co_queSTOR" name="x_co_queSTOR"<?php echo $ambiente_valoracao->co_queSTOR->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->co_queSTOR->EditValue)) {
	$arwrk = $ambiente_valoracao->co_queSTOR->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->co_queSTOR->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->co_queSTOR) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_co_queSTOR"].Options = <?php echo (is_array($ambiente_valoracao->co_queSTOR->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->co_queSTOR->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->co_queSTOR->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_quePVOL->Visible) { // co_quePVOL ?>
	<tr id="r_co_quePVOL">
		<td><span id="elh_ambiente_valoracao_co_quePVOL"><?php echo $ambiente_valoracao->co_quePVOL->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_quePVOL->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_quePVOL" class="control-group">
<?php $ambiente_valoracao->co_quePVOL->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_altPVOL']); " . @$ambiente_valoracao->co_quePVOL->EditAttrs["onchange"]; ?>
<select data-field="x_co_quePVOL" id="x_co_quePVOL" name="x_co_quePVOL"<?php echo $ambiente_valoracao->co_quePVOL->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->co_quePVOL->EditValue)) {
	$arwrk = $ambiente_valoracao->co_quePVOL->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->co_quePVOL->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->co_quePVOL) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_co_quePVOL"].Options = <?php echo (is_array($ambiente_valoracao->co_quePVOL->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->co_quePVOL->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->co_quePVOL->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queACAP->Visible) { // co_queACAP ?>
	<tr id="r_co_queACAP">
		<td><span id="elh_ambiente_valoracao_co_queACAP"><?php echo $ambiente_valoracao->co_queACAP->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queACAP->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queACAP" class="control-group">
<?php $ambiente_valoracao->co_queACAP->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_altACAP']); " . @$ambiente_valoracao->co_queACAP->EditAttrs["onchange"]; ?>
<select data-field="x_co_queACAP" id="x_co_queACAP" name="x_co_queACAP"<?php echo $ambiente_valoracao->co_queACAP->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->co_queACAP->EditValue)) {
	$arwrk = $ambiente_valoracao->co_queACAP->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->co_queACAP->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->co_queACAP) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_co_queACAP"].Options = <?php echo (is_array($ambiente_valoracao->co_queACAP->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->co_queACAP->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->co_queACAP->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_quePCAP->Visible) { // co_quePCAP ?>
	<tr id="r_co_quePCAP">
		<td><span id="elh_ambiente_valoracao_co_quePCAP"><?php echo $ambiente_valoracao->co_quePCAP->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_quePCAP->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_quePCAP" class="control-group">
<?php $ambiente_valoracao->co_quePCAP->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_altPCAP']); " . @$ambiente_valoracao->co_quePCAP->EditAttrs["onchange"]; ?>
<select data-field="x_co_quePCAP" id="x_co_quePCAP" name="x_co_quePCAP"<?php echo $ambiente_valoracao->co_quePCAP->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->co_quePCAP->EditValue)) {
	$arwrk = $ambiente_valoracao->co_quePCAP->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->co_quePCAP->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->co_quePCAP) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_co_quePCAP"].Options = <?php echo (is_array($ambiente_valoracao->co_quePCAP->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->co_quePCAP->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->co_quePCAP->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_quePCON->Visible) { // co_quePCON ?>
	<tr id="r_co_quePCON">
		<td><span id="elh_ambiente_valoracao_co_quePCON"><?php echo $ambiente_valoracao->co_quePCON->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_quePCON->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_quePCON" class="control-group">
<?php $ambiente_valoracao->co_quePCON->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_altPCON']); " . @$ambiente_valoracao->co_quePCON->EditAttrs["onchange"]; ?>
<select data-field="x_co_quePCON" id="x_co_quePCON" name="x_co_quePCON"<?php echo $ambiente_valoracao->co_quePCON->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->co_quePCON->EditValue)) {
	$arwrk = $ambiente_valoracao->co_quePCON->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->co_quePCON->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->co_quePCON) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_co_quePCON"].Options = <?php echo (is_array($ambiente_valoracao->co_quePCON->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->co_quePCON->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->co_quePCON->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queAPEX->Visible) { // co_queAPEX ?>
	<tr id="r_co_queAPEX">
		<td><span id="elh_ambiente_valoracao_co_queAPEX"><?php echo $ambiente_valoracao->co_queAPEX->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queAPEX->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queAPEX" class="control-group">
<?php $ambiente_valoracao->co_queAPEX->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_altAPEX']); " . @$ambiente_valoracao->co_queAPEX->EditAttrs["onchange"]; ?>
<select data-field="x_co_queAPEX" id="x_co_queAPEX" name="x_co_queAPEX"<?php echo $ambiente_valoracao->co_queAPEX->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->co_queAPEX->EditValue)) {
	$arwrk = $ambiente_valoracao->co_queAPEX->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->co_queAPEX->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->co_queAPEX) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_co_queAPEX"].Options = <?php echo (is_array($ambiente_valoracao->co_queAPEX->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->co_queAPEX->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->co_queAPEX->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_quePLEX->Visible) { // co_quePLEX ?>
	<tr id="r_co_quePLEX">
		<td><span id="elh_ambiente_valoracao_co_quePLEX"><?php echo $ambiente_valoracao->co_quePLEX->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_quePLEX->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_quePLEX" class="control-group">
<?php $ambiente_valoracao->co_quePLEX->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_altPLEX']); " . @$ambiente_valoracao->co_quePLEX->EditAttrs["onchange"]; ?>
<select data-field="x_co_quePLEX" id="x_co_quePLEX" name="x_co_quePLEX"<?php echo $ambiente_valoracao->co_quePLEX->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->co_quePLEX->EditValue)) {
	$arwrk = $ambiente_valoracao->co_quePLEX->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->co_quePLEX->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->co_quePLEX) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_co_quePLEX"].Options = <?php echo (is_array($ambiente_valoracao->co_quePLEX->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->co_quePLEX->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->co_quePLEX->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queLTEX->Visible) { // co_queLTEX ?>
	<tr id="r_co_queLTEX">
		<td><span id="elh_ambiente_valoracao_co_queLTEX"><?php echo $ambiente_valoracao->co_queLTEX->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queLTEX->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queLTEX" class="control-group">
<?php $ambiente_valoracao->co_queLTEX->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_altLTEX']); " . @$ambiente_valoracao->co_queLTEX->EditAttrs["onchange"]; ?>
<select data-field="x_co_queLTEX" id="x_co_queLTEX" name="x_co_queLTEX"<?php echo $ambiente_valoracao->co_queLTEX->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->co_queLTEX->EditValue)) {
	$arwrk = $ambiente_valoracao->co_queLTEX->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->co_queLTEX->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->co_queLTEX) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_co_queLTEX"].Options = <?php echo (is_array($ambiente_valoracao->co_queLTEX->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->co_queLTEX->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->co_queLTEX->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queTOOL->Visible) { // co_queTOOL ?>
	<tr id="r_co_queTOOL">
		<td><span id="elh_ambiente_valoracao_co_queTOOL"><?php echo $ambiente_valoracao->co_queTOOL->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queTOOL->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queTOOL" class="control-group">
<?php $ambiente_valoracao->co_queTOOL->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_altTOOL']); " . @$ambiente_valoracao->co_queTOOL->EditAttrs["onchange"]; ?>
<select data-field="x_co_queTOOL" id="x_co_queTOOL" name="x_co_queTOOL"<?php echo $ambiente_valoracao->co_queTOOL->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->co_queTOOL->EditValue)) {
	$arwrk = $ambiente_valoracao->co_queTOOL->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->co_queTOOL->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->co_queTOOL) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_co_queTOOL"].Options = <?php echo (is_array($ambiente_valoracao->co_queTOOL->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->co_queTOOL->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->co_queTOOL->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queSITE->Visible) { // co_queSITE ?>
	<tr id="r_co_queSITE">
		<td><span id="elh_ambiente_valoracao_co_queSITE"><?php echo $ambiente_valoracao->co_queSITE->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queSITE->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queSITE" class="control-group">
<?php $ambiente_valoracao->co_queSITE->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_altSITE']); " . @$ambiente_valoracao->co_queSITE->EditAttrs["onchange"]; ?>
<select data-field="x_co_queSITE" id="x_co_queSITE" name="x_co_queSITE"<?php echo $ambiente_valoracao->co_queSITE->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->co_queSITE->EditValue)) {
	$arwrk = $ambiente_valoracao->co_queSITE->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->co_queSITE->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->co_queSITE) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_co_queSITE"].Options = <?php echo (is_array($ambiente_valoracao->co_queSITE->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->co_queSITE->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->co_queSITE->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_ambiente_valoracao4">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_ambiente_valoracaoedit4" class="table table-bordered table-striped">
<?php if ($ambiente_valoracao->nu_altPREC->Visible) { // nu_altPREC ?>
	<tr id="r_nu_altPREC">
		<td><span id="elh_ambiente_valoracao_nu_altPREC"><?php echo $ambiente_valoracao->nu_altPREC->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altPREC->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altPREC" class="control-group">
<select data-field="x_nu_altPREC" id="x_nu_altPREC" name="x_nu_altPREC" size=7<?php echo $ambiente_valoracao->nu_altPREC->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->nu_altPREC->EditValue)) {
	$arwrk = $ambiente_valoracao->nu_altPREC->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->nu_altPREC->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->nu_altPREC) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_nu_altPREC"].Options = <?php echo (is_array($ambiente_valoracao->nu_altPREC->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->nu_altPREC->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->nu_altPREC->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altFLEX->Visible) { // nu_altFLEX ?>
	<tr id="r_nu_altFLEX">
		<td><span id="elh_ambiente_valoracao_nu_altFLEX"><?php echo $ambiente_valoracao->nu_altFLEX->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altFLEX->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altFLEX" class="control-group">
<select data-field="x_nu_altFLEX" id="x_nu_altFLEX" name="x_nu_altFLEX" size=7<?php echo $ambiente_valoracao->nu_altFLEX->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->nu_altFLEX->EditValue)) {
	$arwrk = $ambiente_valoracao->nu_altFLEX->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->nu_altFLEX->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->nu_altFLEX) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_nu_altFLEX"].Options = <?php echo (is_array($ambiente_valoracao->nu_altFLEX->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->nu_altFLEX->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->nu_altFLEX->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altRESL->Visible) { // nu_altRESL ?>
	<tr id="r_nu_altRESL">
		<td><span id="elh_ambiente_valoracao_nu_altRESL"><?php echo $ambiente_valoracao->nu_altRESL->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altRESL->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altRESL" class="control-group">
<select data-field="x_nu_altRESL" id="x_nu_altRESL" name="x_nu_altRESL" size=7<?php echo $ambiente_valoracao->nu_altRESL->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->nu_altRESL->EditValue)) {
	$arwrk = $ambiente_valoracao->nu_altRESL->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->nu_altRESL->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->nu_altRESL) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_nu_altRESL"].Options = <?php echo (is_array($ambiente_valoracao->nu_altRESL->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->nu_altRESL->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->nu_altRESL->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altTEAM->Visible) { // nu_altTEAM ?>
	<tr id="r_nu_altTEAM">
		<td><span id="elh_ambiente_valoracao_nu_altTEAM"><?php echo $ambiente_valoracao->nu_altTEAM->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altTEAM->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altTEAM" class="control-group">
<select data-field="x_nu_altTEAM" id="x_nu_altTEAM" name="x_nu_altTEAM" size=7<?php echo $ambiente_valoracao->nu_altTEAM->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->nu_altTEAM->EditValue)) {
	$arwrk = $ambiente_valoracao->nu_altTEAM->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->nu_altTEAM->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->nu_altTEAM) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_nu_altTEAM"].Options = <?php echo (is_array($ambiente_valoracao->nu_altTEAM->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->nu_altTEAM->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->nu_altTEAM->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altPMAT->Visible) { // nu_altPMAT ?>
	<tr id="r_nu_altPMAT">
		<td><span id="elh_ambiente_valoracao_nu_altPMAT"><?php echo $ambiente_valoracao->nu_altPMAT->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altPMAT->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altPMAT" class="control-group">
<select data-field="x_nu_altPMAT" id="x_nu_altPMAT" name="x_nu_altPMAT" size=7<?php echo $ambiente_valoracao->nu_altPMAT->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->nu_altPMAT->EditValue)) {
	$arwrk = $ambiente_valoracao->nu_altPMAT->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->nu_altPMAT->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->nu_altPMAT) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_nu_altPMAT"].Options = <?php echo (is_array($ambiente_valoracao->nu_altPMAT->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->nu_altPMAT->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->nu_altPMAT->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altRELY->Visible) { // nu_altRELY ?>
	<tr id="r_nu_altRELY">
		<td><span id="elh_ambiente_valoracao_nu_altRELY"><?php echo $ambiente_valoracao->nu_altRELY->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altRELY->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altRELY" class="control-group">
<select data-field="x_nu_altRELY" id="x_nu_altRELY" name="x_nu_altRELY" size=7<?php echo $ambiente_valoracao->nu_altRELY->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->nu_altRELY->EditValue)) {
	$arwrk = $ambiente_valoracao->nu_altRELY->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->nu_altRELY->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->nu_altRELY) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_nu_altRELY"].Options = <?php echo (is_array($ambiente_valoracao->nu_altRELY->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->nu_altRELY->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->nu_altRELY->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altDATA->Visible) { // nu_altDATA ?>
	<tr id="r_nu_altDATA">
		<td><span id="elh_ambiente_valoracao_nu_altDATA"><?php echo $ambiente_valoracao->nu_altDATA->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altDATA->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altDATA" class="control-group">
<select data-field="x_nu_altDATA" id="x_nu_altDATA" name="x_nu_altDATA" size=7<?php echo $ambiente_valoracao->nu_altDATA->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->nu_altDATA->EditValue)) {
	$arwrk = $ambiente_valoracao->nu_altDATA->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->nu_altDATA->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->nu_altDATA) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_nu_altDATA"].Options = <?php echo (is_array($ambiente_valoracao->nu_altDATA->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->nu_altDATA->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->nu_altDATA->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altCPLX1->Visible) { // nu_altCPLX1 ?>
	<tr id="r_nu_altCPLX1">
		<td><span id="elh_ambiente_valoracao_nu_altCPLX1"><?php echo $ambiente_valoracao->nu_altCPLX1->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altCPLX1->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altCPLX1" class="control-group">
<select data-field="x_nu_altCPLX1" id="x_nu_altCPLX1" name="x_nu_altCPLX1" size=7<?php echo $ambiente_valoracao->nu_altCPLX1->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->nu_altCPLX1->EditValue)) {
	$arwrk = $ambiente_valoracao->nu_altCPLX1->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->nu_altCPLX1->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->nu_altCPLX1) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_nu_altCPLX1"].Options = <?php echo (is_array($ambiente_valoracao->nu_altCPLX1->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->nu_altCPLX1->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->nu_altCPLX1->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altCPLX2->Visible) { // nu_altCPLX2 ?>
	<tr id="r_nu_altCPLX2">
		<td><span id="elh_ambiente_valoracao_nu_altCPLX2"><?php echo $ambiente_valoracao->nu_altCPLX2->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altCPLX2->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altCPLX2" class="control-group">
<select data-field="x_nu_altCPLX2" id="x_nu_altCPLX2" name="x_nu_altCPLX2" size=7<?php echo $ambiente_valoracao->nu_altCPLX2->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->nu_altCPLX2->EditValue)) {
	$arwrk = $ambiente_valoracao->nu_altCPLX2->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->nu_altCPLX2->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->nu_altCPLX2) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_nu_altCPLX2"].Options = <?php echo (is_array($ambiente_valoracao->nu_altCPLX2->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->nu_altCPLX2->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->nu_altCPLX2->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altCPLX3->Visible) { // nu_altCPLX3 ?>
	<tr id="r_nu_altCPLX3">
		<td><span id="elh_ambiente_valoracao_nu_altCPLX3"><?php echo $ambiente_valoracao->nu_altCPLX3->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altCPLX3->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altCPLX3" class="control-group">
<select data-field="x_nu_altCPLX3" id="x_nu_altCPLX3" name="x_nu_altCPLX3" size=7<?php echo $ambiente_valoracao->nu_altCPLX3->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->nu_altCPLX3->EditValue)) {
	$arwrk = $ambiente_valoracao->nu_altCPLX3->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->nu_altCPLX3->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->nu_altCPLX3) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_nu_altCPLX3"].Options = <?php echo (is_array($ambiente_valoracao->nu_altCPLX3->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->nu_altCPLX3->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->nu_altCPLX3->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altCPLX4->Visible) { // nu_altCPLX4 ?>
	<tr id="r_nu_altCPLX4">
		<td><span id="elh_ambiente_valoracao_nu_altCPLX4"><?php echo $ambiente_valoracao->nu_altCPLX4->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altCPLX4->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altCPLX4" class="control-group">
<select data-field="x_nu_altCPLX4" id="x_nu_altCPLX4" name="x_nu_altCPLX4" size=7<?php echo $ambiente_valoracao->nu_altCPLX4->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->nu_altCPLX4->EditValue)) {
	$arwrk = $ambiente_valoracao->nu_altCPLX4->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->nu_altCPLX4->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->nu_altCPLX4) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_nu_altCPLX4"].Options = <?php echo (is_array($ambiente_valoracao->nu_altCPLX4->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->nu_altCPLX4->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->nu_altCPLX4->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altCPLX5->Visible) { // nu_altCPLX5 ?>
	<tr id="r_nu_altCPLX5">
		<td><span id="elh_ambiente_valoracao_nu_altCPLX5"><?php echo $ambiente_valoracao->nu_altCPLX5->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altCPLX5->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altCPLX5" class="control-group">
<select data-field="x_nu_altCPLX5" id="x_nu_altCPLX5" name="x_nu_altCPLX5" size=7<?php echo $ambiente_valoracao->nu_altCPLX5->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->nu_altCPLX5->EditValue)) {
	$arwrk = $ambiente_valoracao->nu_altCPLX5->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->nu_altCPLX5->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->nu_altCPLX5) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_nu_altCPLX5"].Options = <?php echo (is_array($ambiente_valoracao->nu_altCPLX5->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->nu_altCPLX5->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->nu_altCPLX5->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altDOCU->Visible) { // nu_altDOCU ?>
	<tr id="r_nu_altDOCU">
		<td><span id="elh_ambiente_valoracao_nu_altDOCU"><?php echo $ambiente_valoracao->nu_altDOCU->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altDOCU->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altDOCU" class="control-group">
<select data-field="x_nu_altDOCU" id="x_nu_altDOCU" name="x_nu_altDOCU" size=7<?php echo $ambiente_valoracao->nu_altDOCU->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->nu_altDOCU->EditValue)) {
	$arwrk = $ambiente_valoracao->nu_altDOCU->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->nu_altDOCU->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->nu_altDOCU) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_nu_altDOCU"].Options = <?php echo (is_array($ambiente_valoracao->nu_altDOCU->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->nu_altDOCU->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->nu_altDOCU->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altRUSE->Visible) { // nu_altRUSE ?>
	<tr id="r_nu_altRUSE">
		<td><span id="elh_ambiente_valoracao_nu_altRUSE"><?php echo $ambiente_valoracao->nu_altRUSE->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altRUSE->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altRUSE" class="control-group">
<select data-field="x_nu_altRUSE" id="x_nu_altRUSE" name="x_nu_altRUSE" size=7<?php echo $ambiente_valoracao->nu_altRUSE->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->nu_altRUSE->EditValue)) {
	$arwrk = $ambiente_valoracao->nu_altRUSE->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->nu_altRUSE->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->nu_altRUSE) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_nu_altRUSE"].Options = <?php echo (is_array($ambiente_valoracao->nu_altRUSE->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->nu_altRUSE->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->nu_altRUSE->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altTIME->Visible) { // nu_altTIME ?>
	<tr id="r_nu_altTIME">
		<td><span id="elh_ambiente_valoracao_nu_altTIME"><?php echo $ambiente_valoracao->nu_altTIME->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altTIME->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altTIME" class="control-group">
<select data-field="x_nu_altTIME" id="x_nu_altTIME" name="x_nu_altTIME" size=7<?php echo $ambiente_valoracao->nu_altTIME->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->nu_altTIME->EditValue)) {
	$arwrk = $ambiente_valoracao->nu_altTIME->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->nu_altTIME->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->nu_altTIME) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_nu_altTIME"].Options = <?php echo (is_array($ambiente_valoracao->nu_altTIME->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->nu_altTIME->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->nu_altTIME->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altSTOR->Visible) { // nu_altSTOR ?>
	<tr id="r_nu_altSTOR">
		<td><span id="elh_ambiente_valoracao_nu_altSTOR"><?php echo $ambiente_valoracao->nu_altSTOR->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altSTOR->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altSTOR" class="control-group">
<select data-field="x_nu_altSTOR" id="x_nu_altSTOR" name="x_nu_altSTOR" size=7<?php echo $ambiente_valoracao->nu_altSTOR->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->nu_altSTOR->EditValue)) {
	$arwrk = $ambiente_valoracao->nu_altSTOR->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->nu_altSTOR->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->nu_altSTOR) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_nu_altSTOR"].Options = <?php echo (is_array($ambiente_valoracao->nu_altSTOR->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->nu_altSTOR->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->nu_altSTOR->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altPVOL->Visible) { // nu_altPVOL ?>
	<tr id="r_nu_altPVOL">
		<td><span id="elh_ambiente_valoracao_nu_altPVOL"><?php echo $ambiente_valoracao->nu_altPVOL->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altPVOL->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altPVOL" class="control-group">
<select data-field="x_nu_altPVOL" id="x_nu_altPVOL" name="x_nu_altPVOL" size=7<?php echo $ambiente_valoracao->nu_altPVOL->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->nu_altPVOL->EditValue)) {
	$arwrk = $ambiente_valoracao->nu_altPVOL->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->nu_altPVOL->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->nu_altPVOL) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_nu_altPVOL"].Options = <?php echo (is_array($ambiente_valoracao->nu_altPVOL->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->nu_altPVOL->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->nu_altPVOL->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altACAP->Visible) { // nu_altACAP ?>
	<tr id="r_nu_altACAP">
		<td><span id="elh_ambiente_valoracao_nu_altACAP"><?php echo $ambiente_valoracao->nu_altACAP->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altACAP->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altACAP" class="control-group">
<select data-field="x_nu_altACAP" id="x_nu_altACAP" name="x_nu_altACAP" size=7<?php echo $ambiente_valoracao->nu_altACAP->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->nu_altACAP->EditValue)) {
	$arwrk = $ambiente_valoracao->nu_altACAP->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->nu_altACAP->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->nu_altACAP) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_nu_altACAP"].Options = <?php echo (is_array($ambiente_valoracao->nu_altACAP->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->nu_altACAP->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->nu_altACAP->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altPCAP->Visible) { // nu_altPCAP ?>
	<tr id="r_nu_altPCAP">
		<td><span id="elh_ambiente_valoracao_nu_altPCAP"><?php echo $ambiente_valoracao->nu_altPCAP->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altPCAP->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altPCAP" class="control-group">
<select data-field="x_nu_altPCAP" id="x_nu_altPCAP" name="x_nu_altPCAP" size=7<?php echo $ambiente_valoracao->nu_altPCAP->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->nu_altPCAP->EditValue)) {
	$arwrk = $ambiente_valoracao->nu_altPCAP->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->nu_altPCAP->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->nu_altPCAP) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_nu_altPCAP"].Options = <?php echo (is_array($ambiente_valoracao->nu_altPCAP->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->nu_altPCAP->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->nu_altPCAP->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altPCON->Visible) { // nu_altPCON ?>
	<tr id="r_nu_altPCON">
		<td><span id="elh_ambiente_valoracao_nu_altPCON"><?php echo $ambiente_valoracao->nu_altPCON->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altPCON->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altPCON" class="control-group">
<select data-field="x_nu_altPCON" id="x_nu_altPCON" name="x_nu_altPCON" size=7<?php echo $ambiente_valoracao->nu_altPCON->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->nu_altPCON->EditValue)) {
	$arwrk = $ambiente_valoracao->nu_altPCON->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->nu_altPCON->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->nu_altPCON) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_nu_altPCON"].Options = <?php echo (is_array($ambiente_valoracao->nu_altPCON->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->nu_altPCON->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->nu_altPCON->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altAPEX->Visible) { // nu_altAPEX ?>
	<tr id="r_nu_altAPEX">
		<td><span id="elh_ambiente_valoracao_nu_altAPEX"><?php echo $ambiente_valoracao->nu_altAPEX->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altAPEX->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altAPEX" class="control-group">
<select data-field="x_nu_altAPEX" id="x_nu_altAPEX" name="x_nu_altAPEX" size=7<?php echo $ambiente_valoracao->nu_altAPEX->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->nu_altAPEX->EditValue)) {
	$arwrk = $ambiente_valoracao->nu_altAPEX->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->nu_altAPEX->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->nu_altAPEX) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_nu_altAPEX"].Options = <?php echo (is_array($ambiente_valoracao->nu_altAPEX->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->nu_altAPEX->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->nu_altAPEX->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altPLEX->Visible) { // nu_altPLEX ?>
	<tr id="r_nu_altPLEX">
		<td><span id="elh_ambiente_valoracao_nu_altPLEX"><?php echo $ambiente_valoracao->nu_altPLEX->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altPLEX->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altPLEX" class="control-group">
<select data-field="x_nu_altPLEX" id="x_nu_altPLEX" name="x_nu_altPLEX" size=7<?php echo $ambiente_valoracao->nu_altPLEX->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->nu_altPLEX->EditValue)) {
	$arwrk = $ambiente_valoracao->nu_altPLEX->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->nu_altPLEX->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->nu_altPLEX) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_nu_altPLEX"].Options = <?php echo (is_array($ambiente_valoracao->nu_altPLEX->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->nu_altPLEX->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->nu_altPLEX->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altLTEX->Visible) { // nu_altLTEX ?>
	<tr id="r_nu_altLTEX">
		<td><span id="elh_ambiente_valoracao_nu_altLTEX"><?php echo $ambiente_valoracao->nu_altLTEX->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altLTEX->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altLTEX" class="control-group">
<select data-field="x_nu_altLTEX" id="x_nu_altLTEX" name="x_nu_altLTEX" size=7<?php echo $ambiente_valoracao->nu_altLTEX->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->nu_altLTEX->EditValue)) {
	$arwrk = $ambiente_valoracao->nu_altLTEX->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->nu_altLTEX->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->nu_altLTEX) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_nu_altLTEX"].Options = <?php echo (is_array($ambiente_valoracao->nu_altLTEX->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->nu_altLTEX->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->nu_altLTEX->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altTOOL->Visible) { // nu_altTOOL ?>
	<tr id="r_nu_altTOOL">
		<td><span id="elh_ambiente_valoracao_nu_altTOOL"><?php echo $ambiente_valoracao->nu_altTOOL->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altTOOL->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altTOOL" class="control-group">
<select data-field="x_nu_altTOOL" id="x_nu_altTOOL" name="x_nu_altTOOL" size=7<?php echo $ambiente_valoracao->nu_altTOOL->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->nu_altTOOL->EditValue)) {
	$arwrk = $ambiente_valoracao->nu_altTOOL->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->nu_altTOOL->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->nu_altTOOL) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_nu_altTOOL"].Options = <?php echo (is_array($ambiente_valoracao->nu_altTOOL->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->nu_altTOOL->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->nu_altTOOL->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altSITE->Visible) { // nu_altSITE ?>
	<tr id="r_nu_altSITE">
		<td><span id="elh_ambiente_valoracao_nu_altSITE"><?php echo $ambiente_valoracao->nu_altSITE->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altSITE->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altSITE" class="control-group">
<select data-field="x_nu_altSITE" id="x_nu_altSITE" name="x_nu_altSITE" size=7<?php echo $ambiente_valoracao->nu_altSITE->EditAttributes() ?>>
<?php
if (is_array($ambiente_valoracao->nu_altSITE->EditValue)) {
	$arwrk = $ambiente_valoracao->nu_altSITE->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->nu_altSITE->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$ambiente_valoracao->nu_altSITE) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fambiente_valoracaoedit.Lists["x_nu_altSITE"].Options = <?php echo (is_array($ambiente_valoracao->nu_altSITE->EditValue)) ? ew_ArrayToJson($ambiente_valoracao->nu_altSITE->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $ambiente_valoracao->nu_altSITE->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_ambiente_valoracao5">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_ambiente_valoracaoedit5" class="table table-bordered table-striped">
<?php if ($ambiente_valoracao->vr_ipMin->Visible) { // vr_ipMin ?>
	<tr id="r_vr_ipMin">
		<td><span id="elh_ambiente_valoracao_vr_ipMin"><?php echo $ambiente_valoracao->vr_ipMin->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $ambiente_valoracao->vr_ipMin->CellAttributes() ?>>
<span id="el_ambiente_valoracao_vr_ipMin" class="control-group">
<input type="text" data-field="x_vr_ipMin" name="x_vr_ipMin" id="x_vr_ipMin" size="30" placeholder="<?php echo $ambiente_valoracao->vr_ipMin->PlaceHolder ?>" value="<?php echo $ambiente_valoracao->vr_ipMin->EditValue ?>"<?php echo $ambiente_valoracao->vr_ipMin->EditAttributes() ?>>
</span>
<?php echo $ambiente_valoracao->vr_ipMin->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->vr_ipMed->Visible) { // vr_ipMed ?>
	<tr id="r_vr_ipMed">
		<td><span id="elh_ambiente_valoracao_vr_ipMed"><?php echo $ambiente_valoracao->vr_ipMed->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $ambiente_valoracao->vr_ipMed->CellAttributes() ?>>
<span id="el_ambiente_valoracao_vr_ipMed" class="control-group">
<input type="text" data-field="x_vr_ipMed" name="x_vr_ipMed" id="x_vr_ipMed" size="30" placeholder="<?php echo $ambiente_valoracao->vr_ipMed->PlaceHolder ?>" value="<?php echo $ambiente_valoracao->vr_ipMed->EditValue ?>"<?php echo $ambiente_valoracao->vr_ipMed->EditAttributes() ?>>
</span>
<?php echo $ambiente_valoracao->vr_ipMed->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->vr_ipMax->Visible) { // vr_ipMax ?>
	<tr id="r_vr_ipMax">
		<td><span id="elh_ambiente_valoracao_vr_ipMax"><?php echo $ambiente_valoracao->vr_ipMax->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $ambiente_valoracao->vr_ipMax->CellAttributes() ?>>
<span id="el_ambiente_valoracao_vr_ipMax" class="control-group">
<input type="text" data-field="x_vr_ipMax" name="x_vr_ipMax" id="x_vr_ipMax" size="30" placeholder="<?php echo $ambiente_valoracao->vr_ipMax->PlaceHolder ?>" value="<?php echo $ambiente_valoracao->vr_ipMax->EditValue ?>"<?php echo $ambiente_valoracao->vr_ipMax->EditAttributes() ?>>
</span>
<?php echo $ambiente_valoracao->vr_ipMax->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
	</div>
</div>
</td></tr></tbody></table>
<input type="hidden" data-field="x_nu_ambiente" name="x_nu_ambiente" id="x_nu_ambiente" value="<?php echo ew_HtmlEncode($ambiente_valoracao->nu_ambiente->CurrentValue) ?>">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fambiente_valoracaoedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$ambiente_valoracao_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$ambiente_valoracao_edit->Page_Terminate();
?>
