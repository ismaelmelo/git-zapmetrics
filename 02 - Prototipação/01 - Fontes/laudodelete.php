<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "laudoinfo.php" ?>
<?php include_once "solicitacaometricasinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$laudo_delete = NULL; // Initialize page object first

class claudo_delete extends claudo {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'laudo';

	// Page object name
	var $PageObjName = 'laudo_delete';

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

		// Table object (laudo)
		if (!isset($GLOBALS["laudo"])) {
			$GLOBALS["laudo"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["laudo"];
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
			define("EW_TABLE_NAME", 'laudo', TRUE);

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
			$this->Page_Terminate("laudolist.php");
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
			$this->Page_Terminate("laudolist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in laudo class, laudoinfo.php

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
		$this->nu_solicitacao->setDbValue($rs->fields('nu_solicitacao'));
		$this->nu_versao->setDbValue($rs->fields('nu_versao'));
		$this->ds_sobreDocumentacao->setDbValue($rs->fields('ds_sobreDocumentacao'));
		$this->ds_sobreMetrificacao->setDbValue($rs->fields('ds_sobreMetrificacao'));
		$this->qt_pf->setDbValue($rs->fields('qt_pf'));
		$this->qt_horas->setDbValue($rs->fields('qt_horas'));
		$this->qt_prazoMeses->setDbValue($rs->fields('qt_prazoMeses'));
		$this->qt_prazoDias->setDbValue($rs->fields('qt_prazoDias'));
		$this->vr_contratacao->setDbValue($rs->fields('vr_contratacao'));
		$this->nu_usuarioResp->setDbValue($rs->fields('nu_usuarioResp'));
		$this->dt_inicioSolicitacao->setDbValue($rs->fields('dt_inicioSolicitacao'));
		$this->dt_inicioContagem->setDbValue($rs->fields('dt_inicioContagem'));
		$this->dt_emissao->setDbValue($rs->fields('dt_emissao'));
		$this->hh_emissao->setDbValue($rs->fields('hh_emissao'));
		$this->ic_tamanho->setDbValue($rs->fields('ic_tamanho'));
		$this->ic_esforco->setDbValue($rs->fields('ic_esforco'));
		$this->ic_prazo->setDbValue($rs->fields('ic_prazo'));
		$this->ic_custo->setDbValue($rs->fields('ic_custo'));
		$this->ic_bloqueio->setDbValue($rs->fields('ic_bloqueio'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_solicitacao->DbValue = $row['nu_solicitacao'];
		$this->nu_versao->DbValue = $row['nu_versao'];
		$this->ds_sobreDocumentacao->DbValue = $row['ds_sobreDocumentacao'];
		$this->ds_sobreMetrificacao->DbValue = $row['ds_sobreMetrificacao'];
		$this->qt_pf->DbValue = $row['qt_pf'];
		$this->qt_horas->DbValue = $row['qt_horas'];
		$this->qt_prazoMeses->DbValue = $row['qt_prazoMeses'];
		$this->qt_prazoDias->DbValue = $row['qt_prazoDias'];
		$this->vr_contratacao->DbValue = $row['vr_contratacao'];
		$this->nu_usuarioResp->DbValue = $row['nu_usuarioResp'];
		$this->dt_inicioSolicitacao->DbValue = $row['dt_inicioSolicitacao'];
		$this->dt_inicioContagem->DbValue = $row['dt_inicioContagem'];
		$this->dt_emissao->DbValue = $row['dt_emissao'];
		$this->hh_emissao->DbValue = $row['hh_emissao'];
		$this->ic_tamanho->DbValue = $row['ic_tamanho'];
		$this->ic_esforco->DbValue = $row['ic_esforco'];
		$this->ic_prazo->DbValue = $row['ic_prazo'];
		$this->ic_custo->DbValue = $row['ic_custo'];
		$this->ic_bloqueio->DbValue = $row['ic_bloqueio'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->qt_pf->FormValue == $this->qt_pf->CurrentValue && is_numeric(ew_StrToFloat($this->qt_pf->CurrentValue)))
			$this->qt_pf->CurrentValue = ew_StrToFloat($this->qt_pf->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_horas->FormValue == $this->qt_horas->CurrentValue && is_numeric(ew_StrToFloat($this->qt_horas->CurrentValue)))
			$this->qt_horas->CurrentValue = ew_StrToFloat($this->qt_horas->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_prazoMeses->FormValue == $this->qt_prazoMeses->CurrentValue && is_numeric(ew_StrToFloat($this->qt_prazoMeses->CurrentValue)))
			$this->qt_prazoMeses->CurrentValue = ew_StrToFloat($this->qt_prazoMeses->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_contratacao->FormValue == $this->vr_contratacao->CurrentValue && is_numeric(ew_StrToFloat($this->vr_contratacao->CurrentValue)))
			$this->vr_contratacao->CurrentValue = ew_StrToFloat($this->vr_contratacao->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_solicitacao
		// nu_versao
		// ds_sobreDocumentacao
		// ds_sobreMetrificacao
		// qt_pf
		// qt_horas
		// qt_prazoMeses
		// qt_prazoDias
		// vr_contratacao
		// nu_usuarioResp
		// dt_inicioSolicitacao
		// dt_inicioContagem
		// dt_emissao
		// hh_emissao
		// ic_tamanho
		// ic_esforco
		// ic_prazo
		// ic_custo
		// ic_bloqueio

		$this->ic_bloqueio->CellCssStyle = "white-space: nowrap;";
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_solicitacao
			if (strval($this->nu_solicitacao->CurrentValue) <> "") {
				$sFilterWrk = "[nu_solMetricas]" . ew_SearchString("=", $this->nu_solicitacao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_solMetricas], [nu_solMetricas] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[solicitacaoMetricas]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_solicitacao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_solMetricas] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_solicitacao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_solicitacao->ViewValue = $this->nu_solicitacao->CurrentValue;
				}
			} else {
				$this->nu_solicitacao->ViewValue = NULL;
			}
			$this->nu_solicitacao->ViewCustomAttributes = "";

			// nu_versao
			$this->nu_versao->ViewValue = $this->nu_versao->CurrentValue;
			$this->nu_versao->ViewCustomAttributes = "";

			// qt_pf
			$this->qt_pf->ViewValue = $this->qt_pf->CurrentValue;
			$this->qt_pf->ViewCustomAttributes = "";

			// qt_horas
			$this->qt_horas->ViewValue = $this->qt_horas->CurrentValue;
			$this->qt_horas->ViewCustomAttributes = "";

			// qt_prazoMeses
			$this->qt_prazoMeses->ViewValue = $this->qt_prazoMeses->CurrentValue;
			$this->qt_prazoMeses->ViewCustomAttributes = "";

			// qt_prazoDias
			$this->qt_prazoDias->ViewValue = $this->qt_prazoDias->CurrentValue;
			$this->qt_prazoDias->ViewCustomAttributes = "";

			// vr_contratacao
			$this->vr_contratacao->ViewValue = $this->vr_contratacao->CurrentValue;
			$this->vr_contratacao->ViewValue = ew_FormatCurrency($this->vr_contratacao->ViewValue, 2, -2, -2, -2);
			$this->vr_contratacao->ViewCustomAttributes = "";

			// nu_usuarioResp
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
			$sSqlWrk .= " ORDER BY [no_usuario] ASC";
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

			// dt_inicioSolicitacao
			$this->dt_inicioSolicitacao->ViewValue = $this->dt_inicioSolicitacao->CurrentValue;
			$this->dt_inicioSolicitacao->ViewValue = ew_FormatDateTime($this->dt_inicioSolicitacao->ViewValue, 7);
			$this->dt_inicioSolicitacao->ViewCustomAttributes = "";

			// dt_inicioContagem
			$this->dt_inicioContagem->ViewValue = $this->dt_inicioContagem->CurrentValue;
			$this->dt_inicioContagem->ViewValue = ew_FormatDateTime($this->dt_inicioContagem->ViewValue, 7);
			$this->dt_inicioContagem->ViewCustomAttributes = "";

			// dt_emissao
			$this->dt_emissao->ViewValue = $this->dt_emissao->CurrentValue;
			$this->dt_emissao->ViewValue = ew_FormatDateTime($this->dt_emissao->ViewValue, 7);
			$this->dt_emissao->ViewCustomAttributes = "";

			// hh_emissao
			$this->hh_emissao->ViewValue = $this->hh_emissao->CurrentValue;
			$this->hh_emissao->ViewValue = ew_FormatDateTime($this->hh_emissao->ViewValue, 4);
			$this->hh_emissao->ViewCustomAttributes = "";

			// ic_tamanho
			if (strval($this->ic_tamanho->CurrentValue) <> "") {
				switch ($this->ic_tamanho->CurrentValue) {
					case $this->ic_tamanho->FldTagValue(1):
						$this->ic_tamanho->ViewValue = $this->ic_tamanho->FldTagCaption(1) <> "" ? $this->ic_tamanho->FldTagCaption(1) : $this->ic_tamanho->CurrentValue;
						break;
					case $this->ic_tamanho->FldTagValue(2):
						$this->ic_tamanho->ViewValue = $this->ic_tamanho->FldTagCaption(2) <> "" ? $this->ic_tamanho->FldTagCaption(2) : $this->ic_tamanho->CurrentValue;
						break;
					default:
						$this->ic_tamanho->ViewValue = $this->ic_tamanho->CurrentValue;
				}
			} else {
				$this->ic_tamanho->ViewValue = NULL;
			}
			$this->ic_tamanho->ViewCustomAttributes = "";

			// ic_esforco
			if (strval($this->ic_esforco->CurrentValue) <> "") {
				switch ($this->ic_esforco->CurrentValue) {
					case $this->ic_esforco->FldTagValue(1):
						$this->ic_esforco->ViewValue = $this->ic_esforco->FldTagCaption(1) <> "" ? $this->ic_esforco->FldTagCaption(1) : $this->ic_esforco->CurrentValue;
						break;
					case $this->ic_esforco->FldTagValue(2):
						$this->ic_esforco->ViewValue = $this->ic_esforco->FldTagCaption(2) <> "" ? $this->ic_esforco->FldTagCaption(2) : $this->ic_esforco->CurrentValue;
						break;
					default:
						$this->ic_esforco->ViewValue = $this->ic_esforco->CurrentValue;
				}
			} else {
				$this->ic_esforco->ViewValue = NULL;
			}
			$this->ic_esforco->ViewCustomAttributes = "";

			// ic_prazo
			if (strval($this->ic_prazo->CurrentValue) <> "") {
				switch ($this->ic_prazo->CurrentValue) {
					case $this->ic_prazo->FldTagValue(1):
						$this->ic_prazo->ViewValue = $this->ic_prazo->FldTagCaption(1) <> "" ? $this->ic_prazo->FldTagCaption(1) : $this->ic_prazo->CurrentValue;
						break;
					case $this->ic_prazo->FldTagValue(2):
						$this->ic_prazo->ViewValue = $this->ic_prazo->FldTagCaption(2) <> "" ? $this->ic_prazo->FldTagCaption(2) : $this->ic_prazo->CurrentValue;
						break;
					default:
						$this->ic_prazo->ViewValue = $this->ic_prazo->CurrentValue;
				}
			} else {
				$this->ic_prazo->ViewValue = NULL;
			}
			$this->ic_prazo->ViewCustomAttributes = "";

			// ic_custo
			if (strval($this->ic_custo->CurrentValue) <> "") {
				switch ($this->ic_custo->CurrentValue) {
					case $this->ic_custo->FldTagValue(1):
						$this->ic_custo->ViewValue = $this->ic_custo->FldTagCaption(1) <> "" ? $this->ic_custo->FldTagCaption(1) : $this->ic_custo->CurrentValue;
						break;
					case $this->ic_custo->FldTagValue(2):
						$this->ic_custo->ViewValue = $this->ic_custo->FldTagCaption(2) <> "" ? $this->ic_custo->FldTagCaption(2) : $this->ic_custo->CurrentValue;
						break;
					default:
						$this->ic_custo->ViewValue = $this->ic_custo->CurrentValue;
				}
			} else {
				$this->ic_custo->ViewValue = NULL;
			}
			$this->ic_custo->ViewCustomAttributes = "";

			// ic_bloqueio
			$this->ic_bloqueio->ViewValue = $this->ic_bloqueio->CurrentValue;
			$this->ic_bloqueio->ViewCustomAttributes = "";

			// nu_solicitacao
			$this->nu_solicitacao->LinkCustomAttributes = "";
			$this->nu_solicitacao->HrefValue = "";
			$this->nu_solicitacao->TooltipValue = "";

			// nu_versao
			$this->nu_versao->LinkCustomAttributes = "";
			$this->nu_versao->HrefValue = "";
			$this->nu_versao->TooltipValue = "";

			// qt_pf
			$this->qt_pf->LinkCustomAttributes = "";
			$this->qt_pf->HrefValue = "";
			$this->qt_pf->TooltipValue = "";

			// qt_horas
			$this->qt_horas->LinkCustomAttributes = "";
			$this->qt_horas->HrefValue = "";
			$this->qt_horas->TooltipValue = "";

			// qt_prazoMeses
			$this->qt_prazoMeses->LinkCustomAttributes = "";
			$this->qt_prazoMeses->HrefValue = "";
			$this->qt_prazoMeses->TooltipValue = "";

			// qt_prazoDias
			$this->qt_prazoDias->LinkCustomAttributes = "";
			$this->qt_prazoDias->HrefValue = "";
			$this->qt_prazoDias->TooltipValue = "";

			// vr_contratacao
			$this->vr_contratacao->LinkCustomAttributes = "";
			$this->vr_contratacao->HrefValue = "";
			$this->vr_contratacao->TooltipValue = "";

			// nu_usuarioResp
			$this->nu_usuarioResp->LinkCustomAttributes = "";
			$this->nu_usuarioResp->HrefValue = "";
			$this->nu_usuarioResp->TooltipValue = "";

			// dt_inicioSolicitacao
			$this->dt_inicioSolicitacao->LinkCustomAttributes = "";
			$this->dt_inicioSolicitacao->HrefValue = "";
			$this->dt_inicioSolicitacao->TooltipValue = "";

			// dt_inicioContagem
			$this->dt_inicioContagem->LinkCustomAttributes = "";
			$this->dt_inicioContagem->HrefValue = "";
			$this->dt_inicioContagem->TooltipValue = "";

			// dt_emissao
			$this->dt_emissao->LinkCustomAttributes = "";
			$this->dt_emissao->HrefValue = "";
			$this->dt_emissao->TooltipValue = "";

			// hh_emissao
			$this->hh_emissao->LinkCustomAttributes = "";
			$this->hh_emissao->HrefValue = "";
			$this->hh_emissao->TooltipValue = "";

			// ic_tamanho
			$this->ic_tamanho->LinkCustomAttributes = "";
			$this->ic_tamanho->HrefValue = "";
			$this->ic_tamanho->TooltipValue = "";

			// ic_esforco
			$this->ic_esforco->LinkCustomAttributes = "";
			$this->ic_esforco->HrefValue = "";
			$this->ic_esforco->TooltipValue = "";

			// ic_prazo
			$this->ic_prazo->LinkCustomAttributes = "";
			$this->ic_prazo->HrefValue = "";
			$this->ic_prazo->TooltipValue = "";

			// ic_custo
			$this->ic_custo->LinkCustomAttributes = "";
			$this->ic_custo->HrefValue = "";
			$this->ic_custo->TooltipValue = "";
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
				$sThisKey .= $row['nu_solicitacao'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['nu_versao'];
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "laudolist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("delete");
		$Breadcrumb->Add("delete", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'laudo';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (delete page)
	function WriteAuditTrailOnDelete(&$rs) {
		if (!$this->AuditTrailOnDelete) return;
		$table = 'laudo';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_solicitacao'];
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_versao'];

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
if (!isset($laudo_delete)) $laudo_delete = new claudo_delete();

// Page init
$laudo_delete->Page_Init();

// Page main
$laudo_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$laudo_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var laudo_delete = new ew_Page("laudo_delete");
laudo_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = laudo_delete.PageID; // For backward compatibility

// Form object
var flaudodelete = new ew_Form("flaudodelete");

// Form_CustomValidate event
flaudodelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
flaudodelete.ValidateRequired = true;
<?php } else { ?>
flaudodelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
flaudodelete.Lists["x_nu_solicitacao"] = {"LinkField":"x_nu_solMetricas","Ajax":null,"AutoFill":false,"DisplayFields":["x_nu_solMetricas","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
flaudodelete.Lists["x_nu_usuarioResp"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($laudo_delete->Recordset = $laudo_delete->LoadRecordset())
	$laudo_deleteTotalRecs = $laudo_delete->Recordset->RecordCount(); // Get record count
if ($laudo_deleteTotalRecs <= 0) { // No record found, exit
	if ($laudo_delete->Recordset)
		$laudo_delete->Recordset->Close();
	$laudo_delete->Page_Terminate("laudolist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $laudo_delete->ShowPageHeader(); ?>
<?php
$laudo_delete->ShowMessage();
?>
<form name="flaudodelete" id="flaudodelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="laudo">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($laudo_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_laudodelete" class="ewTable ewTableSeparate">
<?php echo $laudo->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_laudo_nu_solicitacao" class="laudo_nu_solicitacao"><?php echo $laudo->nu_solicitacao->FldCaption() ?></span></td>
		<td><span id="elh_laudo_nu_versao" class="laudo_nu_versao"><?php echo $laudo->nu_versao->FldCaption() ?></span></td>
		<td><span id="elh_laudo_qt_pf" class="laudo_qt_pf"><?php echo $laudo->qt_pf->FldCaption() ?></span></td>
		<td><span id="elh_laudo_qt_horas" class="laudo_qt_horas"><?php echo $laudo->qt_horas->FldCaption() ?></span></td>
		<td><span id="elh_laudo_qt_prazoMeses" class="laudo_qt_prazoMeses"><?php echo $laudo->qt_prazoMeses->FldCaption() ?></span></td>
		<td><span id="elh_laudo_qt_prazoDias" class="laudo_qt_prazoDias"><?php echo $laudo->qt_prazoDias->FldCaption() ?></span></td>
		<td><span id="elh_laudo_vr_contratacao" class="laudo_vr_contratacao"><?php echo $laudo->vr_contratacao->FldCaption() ?></span></td>
		<td><span id="elh_laudo_nu_usuarioResp" class="laudo_nu_usuarioResp"><?php echo $laudo->nu_usuarioResp->FldCaption() ?></span></td>
		<td><span id="elh_laudo_dt_inicioSolicitacao" class="laudo_dt_inicioSolicitacao"><?php echo $laudo->dt_inicioSolicitacao->FldCaption() ?></span></td>
		<td><span id="elh_laudo_dt_inicioContagem" class="laudo_dt_inicioContagem"><?php echo $laudo->dt_inicioContagem->FldCaption() ?></span></td>
		<td><span id="elh_laudo_dt_emissao" class="laudo_dt_emissao"><?php echo $laudo->dt_emissao->FldCaption() ?></span></td>
		<td><span id="elh_laudo_hh_emissao" class="laudo_hh_emissao"><?php echo $laudo->hh_emissao->FldCaption() ?></span></td>
		<td><span id="elh_laudo_ic_tamanho" class="laudo_ic_tamanho"><?php echo $laudo->ic_tamanho->FldCaption() ?></span></td>
		<td><span id="elh_laudo_ic_esforco" class="laudo_ic_esforco"><?php echo $laudo->ic_esforco->FldCaption() ?></span></td>
		<td><span id="elh_laudo_ic_prazo" class="laudo_ic_prazo"><?php echo $laudo->ic_prazo->FldCaption() ?></span></td>
		<td><span id="elh_laudo_ic_custo" class="laudo_ic_custo"><?php echo $laudo->ic_custo->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$laudo_delete->RecCnt = 0;
$i = 0;
while (!$laudo_delete->Recordset->EOF) {
	$laudo_delete->RecCnt++;
	$laudo_delete->RowCnt++;

	// Set row properties
	$laudo->ResetAttrs();
	$laudo->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$laudo_delete->LoadRowValues($laudo_delete->Recordset);

	// Render row
	$laudo_delete->RenderRow();
?>
	<tr<?php echo $laudo->RowAttributes() ?>>
		<td<?php echo $laudo->nu_solicitacao->CellAttributes() ?>>
<span id="el<?php echo $laudo_delete->RowCnt ?>_laudo_nu_solicitacao" class="control-group laudo_nu_solicitacao">
<span<?php echo $laudo->nu_solicitacao->ViewAttributes() ?>>
<?php echo $laudo->nu_solicitacao->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $laudo->nu_versao->CellAttributes() ?>>
<span id="el<?php echo $laudo_delete->RowCnt ?>_laudo_nu_versao" class="control-group laudo_nu_versao">
<span<?php echo $laudo->nu_versao->ViewAttributes() ?>>
<?php echo $laudo->nu_versao->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $laudo->qt_pf->CellAttributes() ?>>
<span id="el<?php echo $laudo_delete->RowCnt ?>_laudo_qt_pf" class="control-group laudo_qt_pf">
<span<?php echo $laudo->qt_pf->ViewAttributes() ?>>
<?php echo $laudo->qt_pf->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $laudo->qt_horas->CellAttributes() ?>>
<span id="el<?php echo $laudo_delete->RowCnt ?>_laudo_qt_horas" class="control-group laudo_qt_horas">
<span<?php echo $laudo->qt_horas->ViewAttributes() ?>>
<?php echo $laudo->qt_horas->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $laudo->qt_prazoMeses->CellAttributes() ?>>
<span id="el<?php echo $laudo_delete->RowCnt ?>_laudo_qt_prazoMeses" class="control-group laudo_qt_prazoMeses">
<span<?php echo $laudo->qt_prazoMeses->ViewAttributes() ?>>
<?php echo $laudo->qt_prazoMeses->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $laudo->qt_prazoDias->CellAttributes() ?>>
<span id="el<?php echo $laudo_delete->RowCnt ?>_laudo_qt_prazoDias" class="control-group laudo_qt_prazoDias">
<span<?php echo $laudo->qt_prazoDias->ViewAttributes() ?>>
<?php echo $laudo->qt_prazoDias->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $laudo->vr_contratacao->CellAttributes() ?>>
<span id="el<?php echo $laudo_delete->RowCnt ?>_laudo_vr_contratacao" class="control-group laudo_vr_contratacao">
<span<?php echo $laudo->vr_contratacao->ViewAttributes() ?>>
<?php echo $laudo->vr_contratacao->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $laudo->nu_usuarioResp->CellAttributes() ?>>
<span id="el<?php echo $laudo_delete->RowCnt ?>_laudo_nu_usuarioResp" class="control-group laudo_nu_usuarioResp">
<span<?php echo $laudo->nu_usuarioResp->ViewAttributes() ?>>
<?php echo $laudo->nu_usuarioResp->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $laudo->dt_inicioSolicitacao->CellAttributes() ?>>
<span id="el<?php echo $laudo_delete->RowCnt ?>_laudo_dt_inicioSolicitacao" class="control-group laudo_dt_inicioSolicitacao">
<span<?php echo $laudo->dt_inicioSolicitacao->ViewAttributes() ?>>
<?php echo $laudo->dt_inicioSolicitacao->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $laudo->dt_inicioContagem->CellAttributes() ?>>
<span id="el<?php echo $laudo_delete->RowCnt ?>_laudo_dt_inicioContagem" class="control-group laudo_dt_inicioContagem">
<span<?php echo $laudo->dt_inicioContagem->ViewAttributes() ?>>
<?php echo $laudo->dt_inicioContagem->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $laudo->dt_emissao->CellAttributes() ?>>
<span id="el<?php echo $laudo_delete->RowCnt ?>_laudo_dt_emissao" class="control-group laudo_dt_emissao">
<span<?php echo $laudo->dt_emissao->ViewAttributes() ?>>
<?php echo $laudo->dt_emissao->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $laudo->hh_emissao->CellAttributes() ?>>
<span id="el<?php echo $laudo_delete->RowCnt ?>_laudo_hh_emissao" class="control-group laudo_hh_emissao">
<span<?php echo $laudo->hh_emissao->ViewAttributes() ?>>
<?php echo $laudo->hh_emissao->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $laudo->ic_tamanho->CellAttributes() ?>>
<span id="el<?php echo $laudo_delete->RowCnt ?>_laudo_ic_tamanho" class="control-group laudo_ic_tamanho">
<span<?php echo $laudo->ic_tamanho->ViewAttributes() ?>>
<?php echo $laudo->ic_tamanho->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $laudo->ic_esforco->CellAttributes() ?>>
<span id="el<?php echo $laudo_delete->RowCnt ?>_laudo_ic_esforco" class="control-group laudo_ic_esforco">
<span<?php echo $laudo->ic_esforco->ViewAttributes() ?>>
<?php echo $laudo->ic_esforco->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $laudo->ic_prazo->CellAttributes() ?>>
<span id="el<?php echo $laudo_delete->RowCnt ?>_laudo_ic_prazo" class="control-group laudo_ic_prazo">
<span<?php echo $laudo->ic_prazo->ViewAttributes() ?>>
<?php echo $laudo->ic_prazo->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $laudo->ic_custo->CellAttributes() ?>>
<span id="el<?php echo $laudo_delete->RowCnt ?>_laudo_ic_custo" class="control-group laudo_ic_custo">
<span<?php echo $laudo->ic_custo->ViewAttributes() ?>>
<?php echo $laudo->ic_custo->ListViewValue() ?></span>
</span>
</td>
	</tr>
<?php
	$laudo_delete->Recordset->MoveNext();
}
$laudo_delete->Recordset->Close();
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
flaudodelete.Init();
</script>
<?php
$laudo_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$laudo_delete->Page_Terminate();
?>
