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

$ambiente_valoracao_delete = NULL; // Initialize page object first

class cambiente_valoracao_delete extends cambiente_valoracao {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'ambiente_valoracao';

	// Page object name
	var $PageObjName = 'ambiente_valoracao_delete';

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
	var $AuditTrailOnDelete = TRUE;

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("ambiente_valoracaolist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
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
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("ambiente_valoracaolist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in ambiente_valoracao class, ambiente_valoracaoinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

// No functions
	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Call Recordset Selecting event
		$this->Recordset_Selecting($this->CurrentFilter);

		// Load List page SQL
		$sSql = $this->SelectSQL();

		// Load recordset
		$rs = ew_LoadRecordset($sSql);

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$conn->BeginTrans();
		if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteBegin")); // Batch delete begin

		// Clone old rows
		$rsold = ($rs) ? $rs->GetRows() : array();
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['nu_ambiente'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['nu_versaoValoracao'];
				$this->LoadDbValues($row);
				$conn->raiseErrorFn = 'ew_ErrorFn';
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
			if ($DeleteRows) {
				foreach ($rsold as $row)
					$this->WriteAuditTrailOnDelete($row);
			}
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteSuccess")); // Batch delete success
		} else {
			$conn->RollbackTrans(); // Rollback changes
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteRollback")); // Batch delete rollback
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "ambiente_valoracaolist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("delete");
		$Breadcrumb->Add("delete", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'ambiente_valoracao';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (delete page)
	function WriteAuditTrailOnDelete(&$rs) {
		if (!$this->AuditTrailOnDelete) return;
		$table = 'ambiente_valoracao';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_ambiente'];
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_versaoValoracao'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
	  $curUser = CurrentUserID();
		foreach (array_keys($rs) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$oldvalue = $rs[$fldname];
					else
						$oldvalue = "[MEMO]"; // Memo field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$oldvalue = "[XML]"; // XML field
				} else {
					$oldvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $curUser, "D", $table, $fldname, $key, $oldvalue, "");
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($ambiente_valoracao_delete)) $ambiente_valoracao_delete = new cambiente_valoracao_delete();

// Page init
$ambiente_valoracao_delete->Page_Init();

// Page main
$ambiente_valoracao_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$ambiente_valoracao_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var ambiente_valoracao_delete = new ew_Page("ambiente_valoracao_delete");
ambiente_valoracao_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = ambiente_valoracao_delete.PageID; // For backward compatibility

// Form object
var fambiente_valoracaodelete = new ew_Form("fambiente_valoracaodelete");

// Form_CustomValidate event
fambiente_valoracaodelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fambiente_valoracaodelete.ValidateRequired = true;
<?php } else { ?>
fambiente_valoracaodelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($ambiente_valoracao_delete->Recordset = $ambiente_valoracao_delete->LoadRecordset())
	$ambiente_valoracao_deleteTotalRecs = $ambiente_valoracao_delete->Recordset->RecordCount(); // Get record count
if ($ambiente_valoracao_deleteTotalRecs <= 0) { // No record found, exit
	if ($ambiente_valoracao_delete->Recordset)
		$ambiente_valoracao_delete->Recordset->Close();
	$ambiente_valoracao_delete->Page_Terminate("ambiente_valoracaolist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $ambiente_valoracao_delete->ShowPageHeader(); ?>
<?php
$ambiente_valoracao_delete->ShowMessage();
?>
<form name="fambiente_valoracaodelete" id="fambiente_valoracaodelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="ambiente_valoracao">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($ambiente_valoracao_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_ambiente_valoracaodelete" class="ewTable ewTableSeparate">
<?php echo $ambiente_valoracao->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_ambiente_valoracao_nu_versaoValoracao" class="ambiente_valoracao_nu_versaoValoracao"><?php echo $ambiente_valoracao->nu_versaoValoracao->FldCaption() ?></span></td>
		<td><span id="elh_ambiente_valoracao_ic_metCalibracao" class="ambiente_valoracao_ic_metCalibracao"><?php echo $ambiente_valoracao->ic_metCalibracao->FldCaption() ?></span></td>
		<td><span id="elh_ambiente_valoracao_dh_inclusao" class="ambiente_valoracao_dh_inclusao"><?php echo $ambiente_valoracao->dh_inclusao->FldCaption() ?></span></td>
		<td><span id="elh_ambiente_valoracao_qt_linhasCodLingPf" class="ambiente_valoracao_qt_linhasCodLingPf"><?php echo $ambiente_valoracao->qt_linhasCodLingPf->FldCaption() ?></span></td>
		<td><span id="elh_ambiente_valoracao_vr_ipMin" class="ambiente_valoracao_vr_ipMin"><?php echo $ambiente_valoracao->vr_ipMin->FldCaption() ?></span></td>
		<td><span id="elh_ambiente_valoracao_vr_ipMed" class="ambiente_valoracao_vr_ipMed"><?php echo $ambiente_valoracao->vr_ipMed->FldCaption() ?></span></td>
		<td><span id="elh_ambiente_valoracao_vr_ipMax" class="ambiente_valoracao_vr_ipMax"><?php echo $ambiente_valoracao->vr_ipMax->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$ambiente_valoracao_delete->RecCnt = 0;
$i = 0;
while (!$ambiente_valoracao_delete->Recordset->EOF) {
	$ambiente_valoracao_delete->RecCnt++;
	$ambiente_valoracao_delete->RowCnt++;

	// Set row properties
	$ambiente_valoracao->ResetAttrs();
	$ambiente_valoracao->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$ambiente_valoracao_delete->LoadRowValues($ambiente_valoracao_delete->Recordset);

	// Render row
	$ambiente_valoracao_delete->RenderRow();
?>
	<tr<?php echo $ambiente_valoracao->RowAttributes() ?>>
		<td<?php echo $ambiente_valoracao->nu_versaoValoracao->CellAttributes() ?>>
<span id="el<?php echo $ambiente_valoracao_delete->RowCnt ?>_ambiente_valoracao_nu_versaoValoracao" class="control-group ambiente_valoracao_nu_versaoValoracao">
<span<?php echo $ambiente_valoracao->nu_versaoValoracao->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->nu_versaoValoracao->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $ambiente_valoracao->ic_metCalibracao->CellAttributes() ?>>
<span id="el<?php echo $ambiente_valoracao_delete->RowCnt ?>_ambiente_valoracao_ic_metCalibracao" class="control-group ambiente_valoracao_ic_metCalibracao">
<span<?php echo $ambiente_valoracao->ic_metCalibracao->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->ic_metCalibracao->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $ambiente_valoracao->dh_inclusao->CellAttributes() ?>>
<span id="el<?php echo $ambiente_valoracao_delete->RowCnt ?>_ambiente_valoracao_dh_inclusao" class="control-group ambiente_valoracao_dh_inclusao">
<span<?php echo $ambiente_valoracao->dh_inclusao->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->dh_inclusao->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $ambiente_valoracao->qt_linhasCodLingPf->CellAttributes() ?>>
<span id="el<?php echo $ambiente_valoracao_delete->RowCnt ?>_ambiente_valoracao_qt_linhasCodLingPf" class="control-group ambiente_valoracao_qt_linhasCodLingPf">
<span<?php echo $ambiente_valoracao->qt_linhasCodLingPf->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->qt_linhasCodLingPf->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $ambiente_valoracao->vr_ipMin->CellAttributes() ?>>
<span id="el<?php echo $ambiente_valoracao_delete->RowCnt ?>_ambiente_valoracao_vr_ipMin" class="control-group ambiente_valoracao_vr_ipMin">
<span<?php echo $ambiente_valoracao->vr_ipMin->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->vr_ipMin->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $ambiente_valoracao->vr_ipMed->CellAttributes() ?>>
<span id="el<?php echo $ambiente_valoracao_delete->RowCnt ?>_ambiente_valoracao_vr_ipMed" class="control-group ambiente_valoracao_vr_ipMed">
<span<?php echo $ambiente_valoracao->vr_ipMed->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->vr_ipMed->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $ambiente_valoracao->vr_ipMax->CellAttributes() ?>>
<span id="el<?php echo $ambiente_valoracao_delete->RowCnt ?>_ambiente_valoracao_vr_ipMax" class="control-group ambiente_valoracao_vr_ipMax">
<span<?php echo $ambiente_valoracao->vr_ipMax->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->vr_ipMax->ListViewValue() ?></span>
</span>
</td>
	</tr>
<?php
	$ambiente_valoracao_delete->Recordset->MoveNext();
}
$ambiente_valoracao_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</td></tr></table>
<div class="btn-group ewButtonGroup">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fambiente_valoracaodelete.Init();
</script>
<?php
$ambiente_valoracao_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$ambiente_valoracao_delete->Page_Terminate();
?>
