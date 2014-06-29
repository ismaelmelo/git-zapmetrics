<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "estimativainfo.php" ?>
<?php include_once "solicitacaometricasinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$estimativa_delete = NULL; // Initialize page object first

class cestimativa_delete extends cestimativa {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'estimativa';

	// Page object name
	var $PageObjName = 'estimativa_delete';

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

		// Table object (estimativa)
		if (!isset($GLOBALS["estimativa"])) {
			$GLOBALS["estimativa"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["estimativa"];
		}

		// Table object (solicitacaoMetricas)
		if (!isset($GLOBALS['solicitacaoMetricas'])) $GLOBALS['solicitacaoMetricas'] = new csolicitacaoMetricas();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'estimativa', TRUE);

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
			$this->Page_Terminate("estimativalist.php");
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
			$this->Page_Terminate("estimativalist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in estimativa class, estimativainfo.php

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
		$this->nu_solMetricas->setDbValue($rs->fields('nu_solMetricas'));
		$this->nu_estimativa->setDbValue($rs->fields('nu_estimativa'));
		$this->ic_solicitacaoCritica->setDbValue($rs->fields('ic_solicitacaoCritica'));
		$this->nu_ambienteMaisRepresentativo->setDbValue($rs->fields('nu_ambienteMaisRepresentativo'));
		$this->qt_tamBase->setDbValue($rs->fields('qt_tamBase'));
		$this->ic_modeloCocomo->setDbValue($rs->fields('ic_modeloCocomo'));
		$this->nu_metPrazo->setDbValue($rs->fields('nu_metPrazo'));
		$this->vr_doPf->setDbValue($rs->fields('vr_doPf'));
		$this->pz_estimadoMeses->setDbValue($rs->fields('pz_estimadoMeses'));
		$this->pz_estimadoDias->setDbValue($rs->fields('pz_estimadoDias'));
		$this->vr_ipMaximo->setDbValue($rs->fields('vr_ipMaximo'));
		$this->vr_ipMedio->setDbValue($rs->fields('vr_ipMedio'));
		$this->vr_ipMinimo->setDbValue($rs->fields('vr_ipMinimo'));
		$this->vr_ipInformado->setDbValue($rs->fields('vr_ipInformado'));
		$this->qt_esforco->setDbValue($rs->fields('qt_esforco'));
		$this->vr_custoDesenv->setDbValue($rs->fields('vr_custoDesenv'));
		$this->vr_outrosCustos->setDbValue($rs->fields('vr_outrosCustos'));
		$this->vr_custoTotal->setDbValue($rs->fields('vr_custoTotal'));
		$this->qt_tamBaseFaturamento->setDbValue($rs->fields('qt_tamBaseFaturamento'));
		$this->qt_recursosEquipe->setDbValue($rs->fields('qt_recursosEquipe'));
		$this->ds_observacoes->setDbValue($rs->fields('ds_observacoes'));
		$this->ic_bloqueio->setDbValue($rs->fields('ic_bloqueio'));
		$this->nu_altRELY->setDbValue($rs->fields('nu_altRELY'));
		$this->nu_altDATA->setDbValue($rs->fields('nu_altDATA'));
		$this->nu_altCPLX1->setDbValue($rs->fields('nu_altCPLX1'));
		$this->nu_altCPLX2->setDbValue($rs->fields('nu_altCPLX2'));
		$this->nu_altCPLX3->setDbValue($rs->fields('nu_altCPLX3'));
		$this->nu_altCPLX4->setDbValue($rs->fields('nu_altCPLX4'));
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
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_solMetricas->DbValue = $row['nu_solMetricas'];
		$this->nu_estimativa->DbValue = $row['nu_estimativa'];
		$this->ic_solicitacaoCritica->DbValue = $row['ic_solicitacaoCritica'];
		$this->nu_ambienteMaisRepresentativo->DbValue = $row['nu_ambienteMaisRepresentativo'];
		$this->qt_tamBase->DbValue = $row['qt_tamBase'];
		$this->ic_modeloCocomo->DbValue = $row['ic_modeloCocomo'];
		$this->nu_metPrazo->DbValue = $row['nu_metPrazo'];
		$this->vr_doPf->DbValue = $row['vr_doPf'];
		$this->pz_estimadoMeses->DbValue = $row['pz_estimadoMeses'];
		$this->pz_estimadoDias->DbValue = $row['pz_estimadoDias'];
		$this->vr_ipMaximo->DbValue = $row['vr_ipMaximo'];
		$this->vr_ipMedio->DbValue = $row['vr_ipMedio'];
		$this->vr_ipMinimo->DbValue = $row['vr_ipMinimo'];
		$this->vr_ipInformado->DbValue = $row['vr_ipInformado'];
		$this->qt_esforco->DbValue = $row['qt_esforco'];
		$this->vr_custoDesenv->DbValue = $row['vr_custoDesenv'];
		$this->vr_outrosCustos->DbValue = $row['vr_outrosCustos'];
		$this->vr_custoTotal->DbValue = $row['vr_custoTotal'];
		$this->qt_tamBaseFaturamento->DbValue = $row['qt_tamBaseFaturamento'];
		$this->qt_recursosEquipe->DbValue = $row['qt_recursosEquipe'];
		$this->ds_observacoes->DbValue = $row['ds_observacoes'];
		$this->ic_bloqueio->DbValue = $row['ic_bloqueio'];
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
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->qt_tamBase->FormValue == $this->qt_tamBase->CurrentValue && is_numeric(ew_StrToFloat($this->qt_tamBase->CurrentValue)))
			$this->qt_tamBase->CurrentValue = ew_StrToFloat($this->qt_tamBase->CurrentValue);

		// Convert decimal values if posted back
		if ($this->pz_estimadoMeses->FormValue == $this->pz_estimadoMeses->CurrentValue && is_numeric(ew_StrToFloat($this->pz_estimadoMeses->CurrentValue)))
			$this->pz_estimadoMeses->CurrentValue = ew_StrToFloat($this->pz_estimadoMeses->CurrentValue);

		// Convert decimal values if posted back
		if ($this->pz_estimadoDias->FormValue == $this->pz_estimadoDias->CurrentValue && is_numeric(ew_StrToFloat($this->pz_estimadoDias->CurrentValue)))
			$this->pz_estimadoDias->CurrentValue = ew_StrToFloat($this->pz_estimadoDias->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_ipMaximo->FormValue == $this->vr_ipMaximo->CurrentValue && is_numeric(ew_StrToFloat($this->vr_ipMaximo->CurrentValue)))
			$this->vr_ipMaximo->CurrentValue = ew_StrToFloat($this->vr_ipMaximo->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_ipMedio->FormValue == $this->vr_ipMedio->CurrentValue && is_numeric(ew_StrToFloat($this->vr_ipMedio->CurrentValue)))
			$this->vr_ipMedio->CurrentValue = ew_StrToFloat($this->vr_ipMedio->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_ipMinimo->FormValue == $this->vr_ipMinimo->CurrentValue && is_numeric(ew_StrToFloat($this->vr_ipMinimo->CurrentValue)))
			$this->vr_ipMinimo->CurrentValue = ew_StrToFloat($this->vr_ipMinimo->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_esforco->FormValue == $this->qt_esforco->CurrentValue && is_numeric(ew_StrToFloat($this->qt_esforco->CurrentValue)))
			$this->qt_esforco->CurrentValue = ew_StrToFloat($this->qt_esforco->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_custoDesenv->FormValue == $this->vr_custoDesenv->CurrentValue && is_numeric(ew_StrToFloat($this->vr_custoDesenv->CurrentValue)))
			$this->vr_custoDesenv->CurrentValue = ew_StrToFloat($this->vr_custoDesenv->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_outrosCustos->FormValue == $this->vr_outrosCustos->CurrentValue && is_numeric(ew_StrToFloat($this->vr_outrosCustos->CurrentValue)))
			$this->vr_outrosCustos->CurrentValue = ew_StrToFloat($this->vr_outrosCustos->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_custoTotal->FormValue == $this->vr_custoTotal->CurrentValue && is_numeric(ew_StrToFloat($this->vr_custoTotal->CurrentValue)))
			$this->vr_custoTotal->CurrentValue = ew_StrToFloat($this->vr_custoTotal->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_tamBaseFaturamento->FormValue == $this->qt_tamBaseFaturamento->CurrentValue && is_numeric(ew_StrToFloat($this->qt_tamBaseFaturamento->CurrentValue)))
			$this->qt_tamBaseFaturamento->CurrentValue = ew_StrToFloat($this->qt_tamBaseFaturamento->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_recursosEquipe->FormValue == $this->qt_recursosEquipe->CurrentValue && is_numeric(ew_StrToFloat($this->qt_recursosEquipe->CurrentValue)))
			$this->qt_recursosEquipe->CurrentValue = ew_StrToFloat($this->qt_recursosEquipe->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_solMetricas
		// nu_estimativa
		// ic_solicitacaoCritica
		// nu_ambienteMaisRepresentativo
		// qt_tamBase
		// ic_modeloCocomo
		// nu_metPrazo
		// vr_doPf
		// pz_estimadoMeses
		// pz_estimadoDias
		// vr_ipMaximo
		// vr_ipMedio
		// vr_ipMinimo
		// vr_ipInformado
		// qt_esforco
		// vr_custoDesenv
		// vr_outrosCustos
		// vr_custoTotal
		// qt_tamBaseFaturamento
		// qt_recursosEquipe
		// ds_observacoes
		// ic_bloqueio

		$this->ic_bloqueio->CellCssStyle = "white-space: nowrap;";

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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_solMetricas
			if (strval($this->nu_solMetricas->CurrentValue) <> "") {
				$sFilterWrk = "[nu_solMetricas]" . ew_SearchString("=", $this->nu_solMetricas->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_solMetricas], [nu_solMetricas] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[solicitacaoMetricas]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_solMetricas, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_solMetricas] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_solMetricas->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_solMetricas->ViewValue = $this->nu_solMetricas->CurrentValue;
				}
			} else {
				$this->nu_solMetricas->ViewValue = NULL;
			}
			$this->nu_solMetricas->ViewCustomAttributes = "";

			// nu_estimativa
			$this->nu_estimativa->ViewValue = $this->nu_estimativa->CurrentValue;
			$this->nu_estimativa->ViewCustomAttributes = "";

			// ic_solicitacaoCritica
			if (strval($this->ic_solicitacaoCritica->CurrentValue) <> "") {
				switch ($this->ic_solicitacaoCritica->CurrentValue) {
					case $this->ic_solicitacaoCritica->FldTagValue(1):
						$this->ic_solicitacaoCritica->ViewValue = $this->ic_solicitacaoCritica->FldTagCaption(1) <> "" ? $this->ic_solicitacaoCritica->FldTagCaption(1) : $this->ic_solicitacaoCritica->CurrentValue;
						break;
					case $this->ic_solicitacaoCritica->FldTagValue(2):
						$this->ic_solicitacaoCritica->ViewValue = $this->ic_solicitacaoCritica->FldTagCaption(2) <> "" ? $this->ic_solicitacaoCritica->FldTagCaption(2) : $this->ic_solicitacaoCritica->CurrentValue;
						break;
					default:
						$this->ic_solicitacaoCritica->ViewValue = $this->ic_solicitacaoCritica->CurrentValue;
				}
			} else {
				$this->ic_solicitacaoCritica->ViewValue = NULL;
			}
			$this->ic_solicitacaoCritica->ViewCustomAttributes = "";

			// nu_ambienteMaisRepresentativo
			$this->nu_ambienteMaisRepresentativo->ViewValue = $this->nu_ambienteMaisRepresentativo->CurrentValue;
			if (strval($this->nu_ambienteMaisRepresentativo->CurrentValue) <> "") {
				$sFilterWrk = "[nu_ambiente]" . ew_SearchString("=", $this->nu_ambienteMaisRepresentativo->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_ambiente], [no_ambiente] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ambiente]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_ambienteMaisRepresentativo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_ambiente] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_ambienteMaisRepresentativo->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_ambienteMaisRepresentativo->ViewValue = $this->nu_ambienteMaisRepresentativo->CurrentValue;
				}
			} else {
				$this->nu_ambienteMaisRepresentativo->ViewValue = NULL;
			}
			$this->nu_ambienteMaisRepresentativo->ViewCustomAttributes = "";

			// qt_tamBase
			$this->qt_tamBase->ViewValue = $this->qt_tamBase->CurrentValue;
			$this->qt_tamBase->ViewCustomAttributes = "";

			// ic_modeloCocomo
			if (strval($this->ic_modeloCocomo->CurrentValue) <> "") {
				switch ($this->ic_modeloCocomo->CurrentValue) {
					case $this->ic_modeloCocomo->FldTagValue(1):
						$this->ic_modeloCocomo->ViewValue = $this->ic_modeloCocomo->FldTagCaption(1) <> "" ? $this->ic_modeloCocomo->FldTagCaption(1) : $this->ic_modeloCocomo->CurrentValue;
						break;
					case $this->ic_modeloCocomo->FldTagValue(2):
						$this->ic_modeloCocomo->ViewValue = $this->ic_modeloCocomo->FldTagCaption(2) <> "" ? $this->ic_modeloCocomo->FldTagCaption(2) : $this->ic_modeloCocomo->CurrentValue;
						break;
					default:
						$this->ic_modeloCocomo->ViewValue = $this->ic_modeloCocomo->CurrentValue;
				}
			} else {
				$this->ic_modeloCocomo->ViewValue = NULL;
			}
			$this->ic_modeloCocomo->ViewCustomAttributes = "";

			// nu_metPrazo
			if (strval($this->nu_metPrazo->CurrentValue) <> "") {
				switch ($this->nu_metPrazo->CurrentValue) {
					case $this->nu_metPrazo->FldTagValue(1):
						$this->nu_metPrazo->ViewValue = $this->nu_metPrazo->FldTagCaption(1) <> "" ? $this->nu_metPrazo->FldTagCaption(1) : $this->nu_metPrazo->CurrentValue;
						break;
					case $this->nu_metPrazo->FldTagValue(2):
						$this->nu_metPrazo->ViewValue = $this->nu_metPrazo->FldTagCaption(2) <> "" ? $this->nu_metPrazo->FldTagCaption(2) : $this->nu_metPrazo->CurrentValue;
						break;
					case $this->nu_metPrazo->FldTagValue(3):
						$this->nu_metPrazo->ViewValue = $this->nu_metPrazo->FldTagCaption(3) <> "" ? $this->nu_metPrazo->FldTagCaption(3) : $this->nu_metPrazo->CurrentValue;
						break;
					case $this->nu_metPrazo->FldTagValue(4):
						$this->nu_metPrazo->ViewValue = $this->nu_metPrazo->FldTagCaption(4) <> "" ? $this->nu_metPrazo->FldTagCaption(4) : $this->nu_metPrazo->CurrentValue;
						break;
					case $this->nu_metPrazo->FldTagValue(5):
						$this->nu_metPrazo->ViewValue = $this->nu_metPrazo->FldTagCaption(5) <> "" ? $this->nu_metPrazo->FldTagCaption(5) : $this->nu_metPrazo->CurrentValue;
						break;
					default:
						$this->nu_metPrazo->ViewValue = $this->nu_metPrazo->CurrentValue;
				}
			} else {
				$this->nu_metPrazo->ViewValue = NULL;
			}
			$this->nu_metPrazo->ViewCustomAttributes = "";

			// vr_doPf
			$this->vr_doPf->ViewValue = $this->vr_doPf->CurrentValue;
			$this->vr_doPf->ViewCustomAttributes = "";

			// pz_estimadoMeses
			$this->pz_estimadoMeses->ViewValue = $this->pz_estimadoMeses->CurrentValue;
			$this->pz_estimadoMeses->ViewCustomAttributes = "";

			// pz_estimadoDias
			$this->pz_estimadoDias->ViewValue = $this->pz_estimadoDias->CurrentValue;
			$this->pz_estimadoDias->ViewCustomAttributes = "";

			// vr_ipMaximo
			$this->vr_ipMaximo->ViewValue = $this->vr_ipMaximo->CurrentValue;
			$this->vr_ipMaximo->ViewCustomAttributes = "";

			// vr_ipMedio
			$this->vr_ipMedio->ViewValue = $this->vr_ipMedio->CurrentValue;
			$this->vr_ipMedio->ViewCustomAttributes = "";

			// vr_ipMinimo
			$this->vr_ipMinimo->ViewValue = $this->vr_ipMinimo->CurrentValue;
			$this->vr_ipMinimo->ViewCustomAttributes = "";

			// vr_ipInformado
			$this->vr_ipInformado->ViewValue = $this->vr_ipInformado->CurrentValue;
			$this->vr_ipInformado->ViewCustomAttributes = "";

			// qt_esforco
			$this->qt_esforco->ViewValue = $this->qt_esforco->CurrentValue;
			$this->qt_esforco->ViewCustomAttributes = "";

			// vr_custoDesenv
			$this->vr_custoDesenv->ViewValue = $this->vr_custoDesenv->CurrentValue;
			$this->vr_custoDesenv->ViewCustomAttributes = "";

			// vr_outrosCustos
			$this->vr_outrosCustos->ViewValue = $this->vr_outrosCustos->CurrentValue;
			$this->vr_outrosCustos->ViewCustomAttributes = "";

			// vr_custoTotal
			$this->vr_custoTotal->ViewValue = $this->vr_custoTotal->CurrentValue;
			$this->vr_custoTotal->ViewCustomAttributes = "";

			// qt_tamBaseFaturamento
			$this->qt_tamBaseFaturamento->ViewValue = $this->qt_tamBaseFaturamento->CurrentValue;
			$this->qt_tamBaseFaturamento->ViewCustomAttributes = "";

			// qt_recursosEquipe
			$this->qt_recursosEquipe->ViewValue = $this->qt_recursosEquipe->CurrentValue;
			$this->qt_recursosEquipe->ViewCustomAttributes = "";

			// ic_bloqueio
			$this->ic_bloqueio->ViewValue = $this->ic_bloqueio->CurrentValue;
			$this->ic_bloqueio->ViewCustomAttributes = "";

			// nu_altRELY
			if (strval($this->nu_altRELY->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altRELY->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[co_questao]=(select co_quePREC FROM ambiente_valoracao where nu_ambiente = '2' and nu_versaoValoracao = '1') AND [ic_ativo]='S'";
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[co_questao]=(select co_queDATA FROM ambiente_valoracao where nu_ambiente = '2' and nu_versaoValoracao = '1') AND [ic_ativo]='S'";
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX2, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altCPLX2->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_altCPLX2->ViewValue = $this->nu_altCPLX2->CurrentValue;
				}
			} else {
				$this->nu_altCPLX2->ViewValue = NULL;
			}
			$this->nu_altCPLX2->ViewCustomAttributes = "";

			// nu_altCPLX3
			if (strval($this->nu_altCPLX3->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX3->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX3, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altCPLX3->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_altCPLX3->ViewValue = $this->nu_altCPLX3->CurrentValue;
				}
			} else {
				$this->nu_altCPLX3->ViewValue = NULL;
			}
			$this->nu_altCPLX3->ViewCustomAttributes = "";

			// nu_altCPLX4
			if (strval($this->nu_altCPLX4->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX4->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX4, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altCPLX4->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_altCPLX4->ViewValue = $this->nu_altCPLX4->CurrentValue;
				}
			} else {
				$this->nu_altCPLX4->ViewValue = NULL;
			}
			$this->nu_altCPLX4->ViewCustomAttributes = "";

			// nu_altCPLX5
			if (strval($this->nu_altCPLX5->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX5->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX5, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altCPLX5->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altDOCU, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altDOCU->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altRUSE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altRUSE->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altTIME, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altTIME->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altSTOR, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altSTOR->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPVOL, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altPVOL->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altACAP, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altACAP->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPCAP, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altPCAP->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPCON, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altPCON->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altAPEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altAPEX->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPLEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altPLEX->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altLTEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altLTEX->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altTOOL, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altTOOL->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altSITE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altSITE->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_altSITE->ViewValue = $this->nu_altSITE->CurrentValue;
				}
			} else {
				$this->nu_altSITE->ViewValue = NULL;
			}
			$this->nu_altSITE->ViewCustomAttributes = "";

			// ic_solicitacaoCritica
			$this->ic_solicitacaoCritica->LinkCustomAttributes = "";
			$this->ic_solicitacaoCritica->HrefValue = "";
			$this->ic_solicitacaoCritica->TooltipValue = "";

			// nu_ambienteMaisRepresentativo
			$this->nu_ambienteMaisRepresentativo->LinkCustomAttributes = "";
			$this->nu_ambienteMaisRepresentativo->HrefValue = "";
			$this->nu_ambienteMaisRepresentativo->TooltipValue = "";

			// qt_tamBase
			$this->qt_tamBase->LinkCustomAttributes = "";
			$this->qt_tamBase->HrefValue = "";
			$this->qt_tamBase->TooltipValue = "";

			// ic_modeloCocomo
			$this->ic_modeloCocomo->LinkCustomAttributes = "";
			$this->ic_modeloCocomo->HrefValue = "";
			$this->ic_modeloCocomo->TooltipValue = "";

			// nu_metPrazo
			$this->nu_metPrazo->LinkCustomAttributes = "";
			$this->nu_metPrazo->HrefValue = "";
			$this->nu_metPrazo->TooltipValue = "";

			// vr_doPf
			$this->vr_doPf->LinkCustomAttributes = "";
			$this->vr_doPf->HrefValue = "";
			$this->vr_doPf->TooltipValue = "";

			// pz_estimadoMeses
			$this->pz_estimadoMeses->LinkCustomAttributes = "";
			$this->pz_estimadoMeses->HrefValue = "";
			$this->pz_estimadoMeses->TooltipValue = "";

			// pz_estimadoDias
			$this->pz_estimadoDias->LinkCustomAttributes = "";
			$this->pz_estimadoDias->HrefValue = "";
			$this->pz_estimadoDias->TooltipValue = "";

			// vr_ipMaximo
			$this->vr_ipMaximo->LinkCustomAttributes = "";
			$this->vr_ipMaximo->HrefValue = "";
			$this->vr_ipMaximo->TooltipValue = "";

			// vr_ipMedio
			$this->vr_ipMedio->LinkCustomAttributes = "";
			$this->vr_ipMedio->HrefValue = "";
			$this->vr_ipMedio->TooltipValue = "";

			// vr_ipMinimo
			$this->vr_ipMinimo->LinkCustomAttributes = "";
			$this->vr_ipMinimo->HrefValue = "";
			$this->vr_ipMinimo->TooltipValue = "";

			// vr_ipInformado
			$this->vr_ipInformado->LinkCustomAttributes = "";
			$this->vr_ipInformado->HrefValue = "";
			$this->vr_ipInformado->TooltipValue = "";

			// qt_esforco
			$this->qt_esforco->LinkCustomAttributes = "";
			$this->qt_esforco->HrefValue = "";
			$this->qt_esforco->TooltipValue = "";

			// vr_custoDesenv
			$this->vr_custoDesenv->LinkCustomAttributes = "";
			$this->vr_custoDesenv->HrefValue = "";
			$this->vr_custoDesenv->TooltipValue = "";

			// vr_outrosCustos
			$this->vr_outrosCustos->LinkCustomAttributes = "";
			$this->vr_outrosCustos->HrefValue = "";
			$this->vr_outrosCustos->TooltipValue = "";

			// vr_custoTotal
			$this->vr_custoTotal->LinkCustomAttributes = "";
			$this->vr_custoTotal->HrefValue = "";
			$this->vr_custoTotal->TooltipValue = "";

			// qt_tamBaseFaturamento
			$this->qt_tamBaseFaturamento->LinkCustomAttributes = "";
			$this->qt_tamBaseFaturamento->HrefValue = "";
			$this->qt_tamBaseFaturamento->TooltipValue = "";

			// qt_recursosEquipe
			$this->qt_recursosEquipe->LinkCustomAttributes = "";
			$this->qt_recursosEquipe->HrefValue = "";
			$this->qt_recursosEquipe->TooltipValue = "";
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
				$sThisKey .= $row['nu_estimativa'];
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "estimativalist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("delete");
		$Breadcrumb->Add("delete", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'estimativa';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (delete page)
	function WriteAuditTrailOnDelete(&$rs) {
		if (!$this->AuditTrailOnDelete) return;
		$table = 'estimativa';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_estimativa'];

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
if (!isset($estimativa_delete)) $estimativa_delete = new cestimativa_delete();

// Page init
$estimativa_delete->Page_Init();

// Page main
$estimativa_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$estimativa_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var estimativa_delete = new ew_Page("estimativa_delete");
estimativa_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = estimativa_delete.PageID; // For backward compatibility

// Form object
var festimativadelete = new ew_Form("festimativadelete");

// Form_CustomValidate event
festimativadelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
festimativadelete.ValidateRequired = true;
<?php } else { ?>
festimativadelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
festimativadelete.Lists["x_nu_ambienteMaisRepresentativo"] = {"LinkField":"x_nu_ambiente","Ajax":true,"AutoFill":false,"DisplayFields":["x_no_ambiente","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($estimativa_delete->Recordset = $estimativa_delete->LoadRecordset())
	$estimativa_deleteTotalRecs = $estimativa_delete->Recordset->RecordCount(); // Get record count
if ($estimativa_deleteTotalRecs <= 0) { // No record found, exit
	if ($estimativa_delete->Recordset)
		$estimativa_delete->Recordset->Close();
	$estimativa_delete->Page_Terminate("estimativalist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $estimativa_delete->ShowPageHeader(); ?>
<?php
$estimativa_delete->ShowMessage();
?>
<form name="festimativadelete" id="festimativadelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="estimativa">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($estimativa_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_estimativadelete" class="ewTable ewTableSeparate">
<?php echo $estimativa->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_estimativa_ic_solicitacaoCritica" class="estimativa_ic_solicitacaoCritica"><?php echo $estimativa->ic_solicitacaoCritica->FldCaption() ?></span></td>
		<td><span id="elh_estimativa_nu_ambienteMaisRepresentativo" class="estimativa_nu_ambienteMaisRepresentativo"><?php echo $estimativa->nu_ambienteMaisRepresentativo->FldCaption() ?></span></td>
		<td><span id="elh_estimativa_qt_tamBase" class="estimativa_qt_tamBase"><?php echo $estimativa->qt_tamBase->FldCaption() ?></span></td>
		<td><span id="elh_estimativa_ic_modeloCocomo" class="estimativa_ic_modeloCocomo"><?php echo $estimativa->ic_modeloCocomo->FldCaption() ?></span></td>
		<td><span id="elh_estimativa_nu_metPrazo" class="estimativa_nu_metPrazo"><?php echo $estimativa->nu_metPrazo->FldCaption() ?></span></td>
		<td><span id="elh_estimativa_vr_doPf" class="estimativa_vr_doPf"><?php echo $estimativa->vr_doPf->FldCaption() ?></span></td>
		<td><span id="elh_estimativa_pz_estimadoMeses" class="estimativa_pz_estimadoMeses"><?php echo $estimativa->pz_estimadoMeses->FldCaption() ?></span></td>
		<td><span id="elh_estimativa_pz_estimadoDias" class="estimativa_pz_estimadoDias"><?php echo $estimativa->pz_estimadoDias->FldCaption() ?></span></td>
		<td><span id="elh_estimativa_vr_ipMaximo" class="estimativa_vr_ipMaximo"><?php echo $estimativa->vr_ipMaximo->FldCaption() ?></span></td>
		<td><span id="elh_estimativa_vr_ipMedio" class="estimativa_vr_ipMedio"><?php echo $estimativa->vr_ipMedio->FldCaption() ?></span></td>
		<td><span id="elh_estimativa_vr_ipMinimo" class="estimativa_vr_ipMinimo"><?php echo $estimativa->vr_ipMinimo->FldCaption() ?></span></td>
		<td><span id="elh_estimativa_vr_ipInformado" class="estimativa_vr_ipInformado"><?php echo $estimativa->vr_ipInformado->FldCaption() ?></span></td>
		<td><span id="elh_estimativa_qt_esforco" class="estimativa_qt_esforco"><?php echo $estimativa->qt_esforco->FldCaption() ?></span></td>
		<td><span id="elh_estimativa_vr_custoDesenv" class="estimativa_vr_custoDesenv"><?php echo $estimativa->vr_custoDesenv->FldCaption() ?></span></td>
		<td><span id="elh_estimativa_vr_outrosCustos" class="estimativa_vr_outrosCustos"><?php echo $estimativa->vr_outrosCustos->FldCaption() ?></span></td>
		<td><span id="elh_estimativa_vr_custoTotal" class="estimativa_vr_custoTotal"><?php echo $estimativa->vr_custoTotal->FldCaption() ?></span></td>
		<td><span id="elh_estimativa_qt_tamBaseFaturamento" class="estimativa_qt_tamBaseFaturamento"><?php echo $estimativa->qt_tamBaseFaturamento->FldCaption() ?></span></td>
		<td><span id="elh_estimativa_qt_recursosEquipe" class="estimativa_qt_recursosEquipe"><?php echo $estimativa->qt_recursosEquipe->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$estimativa_delete->RecCnt = 0;
$i = 0;
while (!$estimativa_delete->Recordset->EOF) {
	$estimativa_delete->RecCnt++;
	$estimativa_delete->RowCnt++;

	// Set row properties
	$estimativa->ResetAttrs();
	$estimativa->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$estimativa_delete->LoadRowValues($estimativa_delete->Recordset);

	// Render row
	$estimativa_delete->RenderRow();
?>
	<tr<?php echo $estimativa->RowAttributes() ?>>
		<td<?php echo $estimativa->ic_solicitacaoCritica->CellAttributes() ?>>
<span id="el<?php echo $estimativa_delete->RowCnt ?>_estimativa_ic_solicitacaoCritica" class="control-group estimativa_ic_solicitacaoCritica">
<span<?php echo $estimativa->ic_solicitacaoCritica->ViewAttributes() ?>>
<?php echo $estimativa->ic_solicitacaoCritica->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $estimativa->nu_ambienteMaisRepresentativo->CellAttributes() ?>>
<span id="el<?php echo $estimativa_delete->RowCnt ?>_estimativa_nu_ambienteMaisRepresentativo" class="control-group estimativa_nu_ambienteMaisRepresentativo">
<span<?php echo $estimativa->nu_ambienteMaisRepresentativo->ViewAttributes() ?>>
<?php echo $estimativa->nu_ambienteMaisRepresentativo->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $estimativa->qt_tamBase->CellAttributes() ?>>
<span id="el<?php echo $estimativa_delete->RowCnt ?>_estimativa_qt_tamBase" class="control-group estimativa_qt_tamBase">
<span<?php echo $estimativa->qt_tamBase->ViewAttributes() ?>>
<?php echo $estimativa->qt_tamBase->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $estimativa->ic_modeloCocomo->CellAttributes() ?>>
<span id="el<?php echo $estimativa_delete->RowCnt ?>_estimativa_ic_modeloCocomo" class="control-group estimativa_ic_modeloCocomo">
<span<?php echo $estimativa->ic_modeloCocomo->ViewAttributes() ?>>
<?php echo $estimativa->ic_modeloCocomo->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $estimativa->nu_metPrazo->CellAttributes() ?>>
<span id="el<?php echo $estimativa_delete->RowCnt ?>_estimativa_nu_metPrazo" class="control-group estimativa_nu_metPrazo">
<span<?php echo $estimativa->nu_metPrazo->ViewAttributes() ?>>
<?php echo $estimativa->nu_metPrazo->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $estimativa->vr_doPf->CellAttributes() ?>>
<span id="el<?php echo $estimativa_delete->RowCnt ?>_estimativa_vr_doPf" class="control-group estimativa_vr_doPf">
<span<?php echo $estimativa->vr_doPf->ViewAttributes() ?>>
<?php echo $estimativa->vr_doPf->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $estimativa->pz_estimadoMeses->CellAttributes() ?>>
<span id="el<?php echo $estimativa_delete->RowCnt ?>_estimativa_pz_estimadoMeses" class="control-group estimativa_pz_estimadoMeses">
<span<?php echo $estimativa->pz_estimadoMeses->ViewAttributes() ?>>
<?php echo $estimativa->pz_estimadoMeses->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $estimativa->pz_estimadoDias->CellAttributes() ?>>
<span id="el<?php echo $estimativa_delete->RowCnt ?>_estimativa_pz_estimadoDias" class="control-group estimativa_pz_estimadoDias">
<span<?php echo $estimativa->pz_estimadoDias->ViewAttributes() ?>>
<?php echo $estimativa->pz_estimadoDias->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $estimativa->vr_ipMaximo->CellAttributes() ?>>
<span id="el<?php echo $estimativa_delete->RowCnt ?>_estimativa_vr_ipMaximo" class="control-group estimativa_vr_ipMaximo">
<span<?php echo $estimativa->vr_ipMaximo->ViewAttributes() ?>>
<?php echo $estimativa->vr_ipMaximo->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $estimativa->vr_ipMedio->CellAttributes() ?>>
<span id="el<?php echo $estimativa_delete->RowCnt ?>_estimativa_vr_ipMedio" class="control-group estimativa_vr_ipMedio">
<span<?php echo $estimativa->vr_ipMedio->ViewAttributes() ?>>
<?php echo $estimativa->vr_ipMedio->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $estimativa->vr_ipMinimo->CellAttributes() ?>>
<span id="el<?php echo $estimativa_delete->RowCnt ?>_estimativa_vr_ipMinimo" class="control-group estimativa_vr_ipMinimo">
<span<?php echo $estimativa->vr_ipMinimo->ViewAttributes() ?>>
<?php echo $estimativa->vr_ipMinimo->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $estimativa->vr_ipInformado->CellAttributes() ?>>
<span id="el<?php echo $estimativa_delete->RowCnt ?>_estimativa_vr_ipInformado" class="control-group estimativa_vr_ipInformado">
<span<?php echo $estimativa->vr_ipInformado->ViewAttributes() ?>>
<?php echo $estimativa->vr_ipInformado->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $estimativa->qt_esforco->CellAttributes() ?>>
<span id="el<?php echo $estimativa_delete->RowCnt ?>_estimativa_qt_esforco" class="control-group estimativa_qt_esforco">
<span<?php echo $estimativa->qt_esforco->ViewAttributes() ?>>
<?php echo $estimativa->qt_esforco->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $estimativa->vr_custoDesenv->CellAttributes() ?>>
<span id="el<?php echo $estimativa_delete->RowCnt ?>_estimativa_vr_custoDesenv" class="control-group estimativa_vr_custoDesenv">
<span<?php echo $estimativa->vr_custoDesenv->ViewAttributes() ?>>
<?php echo $estimativa->vr_custoDesenv->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $estimativa->vr_outrosCustos->CellAttributes() ?>>
<span id="el<?php echo $estimativa_delete->RowCnt ?>_estimativa_vr_outrosCustos" class="control-group estimativa_vr_outrosCustos">
<span<?php echo $estimativa->vr_outrosCustos->ViewAttributes() ?>>
<?php echo $estimativa->vr_outrosCustos->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $estimativa->vr_custoTotal->CellAttributes() ?>>
<span id="el<?php echo $estimativa_delete->RowCnt ?>_estimativa_vr_custoTotal" class="control-group estimativa_vr_custoTotal">
<span<?php echo $estimativa->vr_custoTotal->ViewAttributes() ?>>
<?php echo $estimativa->vr_custoTotal->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $estimativa->qt_tamBaseFaturamento->CellAttributes() ?>>
<span id="el<?php echo $estimativa_delete->RowCnt ?>_estimativa_qt_tamBaseFaturamento" class="control-group estimativa_qt_tamBaseFaturamento">
<span<?php echo $estimativa->qt_tamBaseFaturamento->ViewAttributes() ?>>
<?php echo $estimativa->qt_tamBaseFaturamento->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $estimativa->qt_recursosEquipe->CellAttributes() ?>>
<span id="el<?php echo $estimativa_delete->RowCnt ?>_estimativa_qt_recursosEquipe" class="control-group estimativa_qt_recursosEquipe">
<span<?php echo $estimativa->qt_recursosEquipe->ViewAttributes() ?>>
<?php echo $estimativa->qt_recursosEquipe->ListViewValue() ?></span>
</span>
</td>
	</tr>
<?php
	$estimativa_delete->Recordset->MoveNext();
}
$estimativa_delete->Recordset->Close();
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
festimativadelete.Init();
</script>
<?php
$estimativa_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$estimativa_delete->Page_Terminate();
?>
