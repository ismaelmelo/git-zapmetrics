<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "solicitacaometricasinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$solicitacaoMetricas_delete = NULL; // Initialize page object first

class csolicitacaoMetricas_delete extends csolicitacaoMetricas {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'solicitacaoMetricas';

	// Page object name
	var $PageObjName = 'solicitacaoMetricas_delete';

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

		// Table object (solicitacaoMetricas)
		if (!isset($GLOBALS["solicitacaoMetricas"])) {
			$GLOBALS["solicitacaoMetricas"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["solicitacaoMetricas"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'solicitacaoMetricas', TRUE);

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
			$this->Page_Terminate("solicitacaometricaslist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action
		$this->nu_solMetricas->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			$this->Page_Terminate("solicitacaometricaslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in solicitacaoMetricas class, solicitacaoMetricasinfo.php

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
		$this->nu_tpSolicitacao->setDbValue($rs->fields('nu_tpSolicitacao'));
		$this->nu_projeto->setDbValue($rs->fields('nu_projeto'));
		if (array_key_exists('EV__nu_projeto', $rs->fields)) {
			$this->nu_projeto->VirtualValue = $rs->fields('EV__nu_projeto'); // Set up virtual field value
		} else {
			$this->nu_projeto->VirtualValue = ""; // Clear value
		}
		$this->no_atividadeMaeRedmine->setDbValue($rs->fields('no_atividadeMaeRedmine'));
		$this->ds_observacoes->setDbValue($rs->fields('ds_observacoes'));
		$this->ds_documentacaoAux->setDbValue($rs->fields('ds_documentacaoAux'));
		$this->ds_imapactoDb->setDbValue($rs->fields('ds_imapactoDb'));
		$this->ic_stSolicitacao->setDbValue($rs->fields('ic_stSolicitacao'));
		$this->nu_usuarioAlterou->setDbValue($rs->fields('nu_usuarioAlterou'));
		$this->dh_alteracao->setDbValue($rs->fields('dh_alteracao'));
		$this->nu_usuarioIncluiu->setDbValue($rs->fields('nu_usuarioIncluiu'));
		$this->dh_inclusao->setDbValue($rs->fields('dh_inclusao'));
		$this->dt_stSolicitacao->setDbValue($rs->fields('dt_stSolicitacao'));
		$this->qt_pfTotal->setDbValue($rs->fields('qt_pfTotal'));
		$this->vr_pfContForn->setDbValue($rs->fields('vr_pfContForn'));
		$this->nu_tpMetrica->setDbValue($rs->fields('nu_tpMetrica'));
		$this->ds_observacoesContForn->setDbValue($rs->fields('ds_observacoesContForn'));
		$this->im_anexosContForn->Upload->DbValue = $rs->fields('im_anexosContForn');
		$this->nu_contagemAnt->setDbValue($rs->fields('nu_contagemAnt'));
		if (array_key_exists('EV__nu_contagemAnt', $rs->fields)) {
			$this->nu_contagemAnt->VirtualValue = $rs->fields('EV__nu_contagemAnt'); // Set up virtual field value
		} else {
			$this->nu_contagemAnt->VirtualValue = ""; // Clear value
		}
		$this->ds_observaocoesContAnt->setDbValue($rs->fields('ds_observaocoesContAnt'));
		$this->im_anexosContAnt->Upload->DbValue = $rs->fields('im_anexosContAnt');
		$this->ic_bloqueio->setDbValue($rs->fields('ic_bloqueio'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_solMetricas->DbValue = $row['nu_solMetricas'];
		$this->nu_tpSolicitacao->DbValue = $row['nu_tpSolicitacao'];
		$this->nu_projeto->DbValue = $row['nu_projeto'];
		$this->no_atividadeMaeRedmine->DbValue = $row['no_atividadeMaeRedmine'];
		$this->ds_observacoes->DbValue = $row['ds_observacoes'];
		$this->ds_documentacaoAux->DbValue = $row['ds_documentacaoAux'];
		$this->ds_imapactoDb->DbValue = $row['ds_imapactoDb'];
		$this->ic_stSolicitacao->DbValue = $row['ic_stSolicitacao'];
		$this->nu_usuarioAlterou->DbValue = $row['nu_usuarioAlterou'];
		$this->dh_alteracao->DbValue = $row['dh_alteracao'];
		$this->nu_usuarioIncluiu->DbValue = $row['nu_usuarioIncluiu'];
		$this->dh_inclusao->DbValue = $row['dh_inclusao'];
		$this->dt_stSolicitacao->DbValue = $row['dt_stSolicitacao'];
		$this->qt_pfTotal->DbValue = $row['qt_pfTotal'];
		$this->vr_pfContForn->DbValue = $row['vr_pfContForn'];
		$this->nu_tpMetrica->DbValue = $row['nu_tpMetrica'];
		$this->ds_observacoesContForn->DbValue = $row['ds_observacoesContForn'];
		$this->im_anexosContForn->Upload->DbValue = $row['im_anexosContForn'];
		$this->nu_contagemAnt->DbValue = $row['nu_contagemAnt'];
		$this->ds_observaocoesContAnt->DbValue = $row['ds_observaocoesContAnt'];
		$this->im_anexosContAnt->Upload->DbValue = $row['im_anexosContAnt'];
		$this->ic_bloqueio->DbValue = $row['ic_bloqueio'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->qt_pfTotal->FormValue == $this->qt_pfTotal->CurrentValue && is_numeric(ew_StrToFloat($this->qt_pfTotal->CurrentValue)))
			$this->qt_pfTotal->CurrentValue = ew_StrToFloat($this->qt_pfTotal->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_pfContForn->FormValue == $this->vr_pfContForn->CurrentValue && is_numeric(ew_StrToFloat($this->vr_pfContForn->CurrentValue)))
			$this->vr_pfContForn->CurrentValue = ew_StrToFloat($this->vr_pfContForn->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_solMetricas
		// nu_tpSolicitacao
		// nu_projeto
		// no_atividadeMaeRedmine
		// ds_observacoes
		// ds_documentacaoAux
		// ds_imapactoDb
		// ic_stSolicitacao
		// nu_usuarioAlterou
		// dh_alteracao
		// nu_usuarioIncluiu
		// dh_inclusao
		// dt_stSolicitacao
		// qt_pfTotal
		// vr_pfContForn
		// nu_tpMetrica
		// ds_observacoesContForn
		// im_anexosContForn
		// nu_contagemAnt
		// ds_observaocoesContAnt
		// im_anexosContAnt
		// ic_bloqueio

		$this->ic_bloqueio->CellCssStyle = "white-space: nowrap;";
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_solMetricas
			$this->nu_solMetricas->ViewValue = $this->nu_solMetricas->CurrentValue;
			$this->nu_solMetricas->ViewCustomAttributes = "";

			// nu_tpSolicitacao
			if (strval($this->nu_tpSolicitacao->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpSolicitacao]" . ew_SearchString("=", $this->nu_tpSolicitacao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpSolicitacao], [no_tpSolicitacao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpsolicitacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpSolicitacao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpSolicitacao] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpSolicitacao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpSolicitacao->ViewValue = $this->nu_tpSolicitacao->CurrentValue;
				}
			} else {
				$this->nu_tpSolicitacao->ViewValue = NULL;
			}
			$this->nu_tpSolicitacao->ViewCustomAttributes = "";

			// nu_projeto
			if ($this->nu_projeto->VirtualValue <> "") {
				$this->nu_projeto->ViewValue = $this->nu_projeto->VirtualValue;
			} else {
			if (strval($this->nu_projeto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_projeto]" . ew_SearchString("=", $this->nu_projeto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_projeto], [no_projeto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[projeto]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_passivelContPf]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_projeto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_projeto] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_projeto->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_projeto->ViewValue = $this->nu_projeto->CurrentValue;
				}
			} else {
				$this->nu_projeto->ViewValue = NULL;
			}
			}
			$this->nu_projeto->ViewCustomAttributes = "";

			// no_atividadeMaeRedmine
			$this->no_atividadeMaeRedmine->ViewValue = $this->no_atividadeMaeRedmine->CurrentValue;
			$this->no_atividadeMaeRedmine->ViewCustomAttributes = "";

			// ds_observacoes
			$this->ds_observacoes->ViewValue = $this->ds_observacoes->CurrentValue;
			$this->ds_observacoes->ViewCustomAttributes = "";

			// ds_documentacaoAux
			$this->ds_documentacaoAux->ViewValue = $this->ds_documentacaoAux->CurrentValue;
			$this->ds_documentacaoAux->ViewCustomAttributes = "";

			// ds_imapactoDb
			$this->ds_imapactoDb->ViewValue = $this->ds_imapactoDb->CurrentValue;
			$this->ds_imapactoDb->ViewCustomAttributes = "";

			// ic_stSolicitacao
			if (strval($this->ic_stSolicitacao->CurrentValue) <> "") {
				switch ($this->ic_stSolicitacao->CurrentValue) {
					case $this->ic_stSolicitacao->FldTagValue(1):
						$this->ic_stSolicitacao->ViewValue = $this->ic_stSolicitacao->FldTagCaption(1) <> "" ? $this->ic_stSolicitacao->FldTagCaption(1) : $this->ic_stSolicitacao->CurrentValue;
						break;
					case $this->ic_stSolicitacao->FldTagValue(2):
						$this->ic_stSolicitacao->ViewValue = $this->ic_stSolicitacao->FldTagCaption(2) <> "" ? $this->ic_stSolicitacao->FldTagCaption(2) : $this->ic_stSolicitacao->CurrentValue;
						break;
					case $this->ic_stSolicitacao->FldTagValue(3):
						$this->ic_stSolicitacao->ViewValue = $this->ic_stSolicitacao->FldTagCaption(3) <> "" ? $this->ic_stSolicitacao->FldTagCaption(3) : $this->ic_stSolicitacao->CurrentValue;
						break;
					case $this->ic_stSolicitacao->FldTagValue(4):
						$this->ic_stSolicitacao->ViewValue = $this->ic_stSolicitacao->FldTagCaption(4) <> "" ? $this->ic_stSolicitacao->FldTagCaption(4) : $this->ic_stSolicitacao->CurrentValue;
						break;
					default:
						$this->ic_stSolicitacao->ViewValue = $this->ic_stSolicitacao->CurrentValue;
				}
			} else {
				$this->ic_stSolicitacao->ViewValue = NULL;
			}
			$this->ic_stSolicitacao->ViewCustomAttributes = "";

			// nu_usuarioAlterou
			if (strval($this->nu_usuarioAlterou->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioAlterou->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioAlterou, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioAlterou->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioAlterou->ViewValue = $this->nu_usuarioAlterou->CurrentValue;
				}
			} else {
				$this->nu_usuarioAlterou->ViewValue = NULL;
			}
			$this->nu_usuarioAlterou->ViewCustomAttributes = "";

			// dh_alteracao
			$this->dh_alteracao->ViewValue = $this->dh_alteracao->CurrentValue;
			$this->dh_alteracao->ViewValue = ew_FormatDateTime($this->dh_alteracao->ViewValue, 10);
			$this->dh_alteracao->ViewCustomAttributes = "";

			// nu_usuarioIncluiu
			if (strval($this->nu_usuarioIncluiu->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioIncluiu->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioIncluiu, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioIncluiu->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioIncluiu->ViewValue = $this->nu_usuarioIncluiu->CurrentValue;
				}
			} else {
				$this->nu_usuarioIncluiu->ViewValue = NULL;
			}
			$this->nu_usuarioIncluiu->ViewCustomAttributes = "";

			// dh_inclusao
			$this->dh_inclusao->ViewValue = $this->dh_inclusao->CurrentValue;
			$this->dh_inclusao->ViewValue = ew_FormatDateTime($this->dh_inclusao->ViewValue, 7);
			$this->dh_inclusao->ViewCustomAttributes = "";

			// dt_stSolicitacao
			$this->dt_stSolicitacao->ViewValue = $this->dt_stSolicitacao->CurrentValue;
			$this->dt_stSolicitacao->ViewValue = ew_FormatDateTime($this->dt_stSolicitacao->ViewValue, 7);
			$this->dt_stSolicitacao->ViewCustomAttributes = "";

			// qt_pfTotal
			$this->qt_pfTotal->ViewValue = $this->qt_pfTotal->CurrentValue;
			$this->qt_pfTotal->ViewCustomAttributes = "";

			// vr_pfContForn
			$this->vr_pfContForn->ViewValue = $this->vr_pfContForn->CurrentValue;
			$this->vr_pfContForn->ViewCustomAttributes = "";

			// nu_tpMetrica
			if (strval($this->nu_tpMetrica->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpMetrica]" . ew_SearchString("=", $this->nu_tpMetrica->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpMetrica], [no_tpMetrica] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpmetrica]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpMetrica, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpMetrica] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpMetrica->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpMetrica->ViewValue = $this->nu_tpMetrica->CurrentValue;
				}
			} else {
				$this->nu_tpMetrica->ViewValue = NULL;
			}
			$this->nu_tpMetrica->ViewCustomAttributes = "";

			// ds_observacoesContForn
			$this->ds_observacoesContForn->ViewValue = $this->ds_observacoesContForn->CurrentValue;
			$this->ds_observacoesContForn->ViewCustomAttributes = "";

			// im_anexosContForn
			$this->im_anexosContForn->UploadPath = "contagem_fornecedor";
			if (!ew_Empty($this->im_anexosContForn->Upload->DbValue)) {
				$this->im_anexosContForn->ViewValue = $this->im_anexosContForn->Upload->DbValue;
			} else {
				$this->im_anexosContForn->ViewValue = "";
			}
			$this->im_anexosContForn->ViewCustomAttributes = "";

			// nu_contagemAnt
			if ($this->nu_contagemAnt->VirtualValue <> "") {
				$this->nu_contagemAnt->ViewValue = $this->nu_contagemAnt->VirtualValue;
			} else {
			if (strval($this->nu_contagemAnt->CurrentValue) <> "") {
				$sFilterWrk = "[nu_solMetricas]" . ew_SearchString("=", $this->nu_contagemAnt->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_solMetricas], [nu_solMetricas] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[solicitacaoMetricas]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_contagemAnt, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_solMetricas] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_contagemAnt->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_contagemAnt->ViewValue = $this->nu_contagemAnt->CurrentValue;
				}
			} else {
				$this->nu_contagemAnt->ViewValue = NULL;
			}
			}
			$this->nu_contagemAnt->ViewCustomAttributes = "";

			// ds_observaocoesContAnt
			$this->ds_observaocoesContAnt->ViewValue = $this->ds_observaocoesContAnt->CurrentValue;
			$this->ds_observaocoesContAnt->ViewCustomAttributes = "";

			// im_anexosContAnt
			$this->im_anexosContAnt->UploadPath = "contagem_anterior";
			if (!ew_Empty($this->im_anexosContAnt->Upload->DbValue)) {
				$this->im_anexosContAnt->ViewValue = $this->im_anexosContAnt->Upload->DbValue;
			} else {
				$this->im_anexosContAnt->ViewValue = "";
			}
			$this->im_anexosContAnt->ViewCustomAttributes = "";

			// ic_bloqueio
			$this->ic_bloqueio->ViewValue = $this->ic_bloqueio->CurrentValue;
			$this->ic_bloqueio->ViewCustomAttributes = "";

			// nu_solMetricas
			$this->nu_solMetricas->LinkCustomAttributes = "";
			$this->nu_solMetricas->HrefValue = "";
			$this->nu_solMetricas->TooltipValue = "";

			// nu_tpSolicitacao
			$this->nu_tpSolicitacao->LinkCustomAttributes = "";
			$this->nu_tpSolicitacao->HrefValue = "";
			$this->nu_tpSolicitacao->TooltipValue = "";

			// nu_projeto
			$this->nu_projeto->LinkCustomAttributes = "";
			$this->nu_projeto->HrefValue = "";
			$this->nu_projeto->TooltipValue = "";

			// ic_stSolicitacao
			$this->ic_stSolicitacao->LinkCustomAttributes = "";
			$this->ic_stSolicitacao->HrefValue = "";
			$this->ic_stSolicitacao->TooltipValue = "";

			// nu_usuarioAlterou
			$this->nu_usuarioAlterou->LinkCustomAttributes = "";
			$this->nu_usuarioAlterou->HrefValue = "";
			$this->nu_usuarioAlterou->TooltipValue = "";

			// dt_stSolicitacao
			$this->dt_stSolicitacao->LinkCustomAttributes = "";
			$this->dt_stSolicitacao->HrefValue = "";
			$this->dt_stSolicitacao->TooltipValue = "";

			// qt_pfTotal
			$this->qt_pfTotal->LinkCustomAttributes = "";
			$this->qt_pfTotal->HrefValue = "";
			$this->qt_pfTotal->TooltipValue = "";

			// vr_pfContForn
			$this->vr_pfContForn->LinkCustomAttributes = "";
			$this->vr_pfContForn->HrefValue = "";
			$this->vr_pfContForn->TooltipValue = "";
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

		// Check if records exist for detail table 'solicitacao_ocorrencia'
		$rows = ($rs) ? $rs->GetRows() : array();
		if (!isset($GLOBALS["solicitacao_ocorrencia"])) $GLOBALS["solicitacao_ocorrencia"] = new csolicitacao_ocorrencia();
		foreach ($rows as $row) {
			$rsdetail = $GLOBALS["solicitacao_ocorrencia"]->LoadRs("[nu_solicitacao] = " . ew_QuotedValue($row['nu_solMetricas'], EW_DATATYPE_NUMBER));
			if ($rsdetail && !$rsdetail->EOF) {
				$sRelatedRecordMsg = str_replace("%t", "solicitacao_ocorrencia", $Language->Phrase("RelatedRecordExists"));
				$this->setFailureMessage($sRelatedRecordMsg);
				return FALSE;
			}
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
				$sThisKey .= $row['nu_solMetricas'];
				$this->LoadDbValues($row);
				$this->im_anexosContForn->OldUploadPath = "contagem_fornecedor";
				@unlink(ew_UploadPathEx(TRUE, $this->im_anexosContForn->OldUploadPath) . $row['im_anexosContForn']);
				$this->im_anexosContAnt->OldUploadPath = "contagem_anterior";
				@unlink(ew_UploadPathEx(TRUE, $this->im_anexosContAnt->OldUploadPath) . $row['im_anexosContAnt']);
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "solicitacaometricaslist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("delete");
		$Breadcrumb->Add("delete", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'solicitacaoMetricas';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (delete page)
	function WriteAuditTrailOnDelete(&$rs) {
		if (!$this->AuditTrailOnDelete) return;
		$table = 'solicitacaoMetricas';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_solMetricas'];

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
if (!isset($solicitacaoMetricas_delete)) $solicitacaoMetricas_delete = new csolicitacaoMetricas_delete();

// Page init
$solicitacaoMetricas_delete->Page_Init();

// Page main
$solicitacaoMetricas_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$solicitacaoMetricas_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var solicitacaoMetricas_delete = new ew_Page("solicitacaoMetricas_delete");
solicitacaoMetricas_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = solicitacaoMetricas_delete.PageID; // For backward compatibility

// Form object
var fsolicitacaoMetricasdelete = new ew_Form("fsolicitacaoMetricasdelete");

// Form_CustomValidate event
fsolicitacaoMetricasdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsolicitacaoMetricasdelete.ValidateRequired = true;
<?php } else { ?>
fsolicitacaoMetricasdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsolicitacaoMetricasdelete.Lists["x_nu_tpSolicitacao"] = {"LinkField":"x_nu_tpSolicitacao","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpSolicitacao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fsolicitacaoMetricasdelete.Lists["x_nu_projeto"] = {"LinkField":"x_nu_projeto","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_projeto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fsolicitacaoMetricasdelete.Lists["x_nu_usuarioAlterou"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($solicitacaoMetricas_delete->Recordset = $solicitacaoMetricas_delete->LoadRecordset())
	$solicitacaoMetricas_deleteTotalRecs = $solicitacaoMetricas_delete->Recordset->RecordCount(); // Get record count
if ($solicitacaoMetricas_deleteTotalRecs <= 0) { // No record found, exit
	if ($solicitacaoMetricas_delete->Recordset)
		$solicitacaoMetricas_delete->Recordset->Close();
	$solicitacaoMetricas_delete->Page_Terminate("solicitacaometricaslist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $solicitacaoMetricas_delete->ShowPageHeader(); ?>
<?php
$solicitacaoMetricas_delete->ShowMessage();
?>
<form name="fsolicitacaoMetricasdelete" id="fsolicitacaoMetricasdelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="solicitacaoMetricas">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($solicitacaoMetricas_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_solicitacaoMetricasdelete" class="ewTable ewTableSeparate">
<?php echo $solicitacaoMetricas->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_solicitacaoMetricas_nu_solMetricas" class="solicitacaoMetricas_nu_solMetricas"><?php echo $solicitacaoMetricas->nu_solMetricas->FldCaption() ?></span></td>
		<td><span id="elh_solicitacaoMetricas_nu_tpSolicitacao" class="solicitacaoMetricas_nu_tpSolicitacao"><?php echo $solicitacaoMetricas->nu_tpSolicitacao->FldCaption() ?></span></td>
		<td><span id="elh_solicitacaoMetricas_nu_projeto" class="solicitacaoMetricas_nu_projeto"><?php echo $solicitacaoMetricas->nu_projeto->FldCaption() ?></span></td>
		<td><span id="elh_solicitacaoMetricas_ic_stSolicitacao" class="solicitacaoMetricas_ic_stSolicitacao"><?php echo $solicitacaoMetricas->ic_stSolicitacao->FldCaption() ?></span></td>
		<td><span id="elh_solicitacaoMetricas_nu_usuarioAlterou" class="solicitacaoMetricas_nu_usuarioAlterou"><?php echo $solicitacaoMetricas->nu_usuarioAlterou->FldCaption() ?></span></td>
		<td><span id="elh_solicitacaoMetricas_dt_stSolicitacao" class="solicitacaoMetricas_dt_stSolicitacao"><?php echo $solicitacaoMetricas->dt_stSolicitacao->FldCaption() ?></span></td>
		<td><span id="elh_solicitacaoMetricas_qt_pfTotal" class="solicitacaoMetricas_qt_pfTotal"><?php echo $solicitacaoMetricas->qt_pfTotal->FldCaption() ?></span></td>
		<td><span id="elh_solicitacaoMetricas_vr_pfContForn" class="solicitacaoMetricas_vr_pfContForn"><?php echo $solicitacaoMetricas->vr_pfContForn->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$solicitacaoMetricas_delete->RecCnt = 0;
$i = 0;
while (!$solicitacaoMetricas_delete->Recordset->EOF) {
	$solicitacaoMetricas_delete->RecCnt++;
	$solicitacaoMetricas_delete->RowCnt++;

	// Set row properties
	$solicitacaoMetricas->ResetAttrs();
	$solicitacaoMetricas->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$solicitacaoMetricas_delete->LoadRowValues($solicitacaoMetricas_delete->Recordset);

	// Render row
	$solicitacaoMetricas_delete->RenderRow();
?>
	<tr<?php echo $solicitacaoMetricas->RowAttributes() ?>>
		<td<?php echo $solicitacaoMetricas->nu_solMetricas->CellAttributes() ?>>
<span id="el<?php echo $solicitacaoMetricas_delete->RowCnt ?>_solicitacaoMetricas_nu_solMetricas" class="control-group solicitacaoMetricas_nu_solMetricas">
<span<?php echo $solicitacaoMetricas->nu_solMetricas->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->nu_solMetricas->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $solicitacaoMetricas->nu_tpSolicitacao->CellAttributes() ?>>
<span id="el<?php echo $solicitacaoMetricas_delete->RowCnt ?>_solicitacaoMetricas_nu_tpSolicitacao" class="control-group solicitacaoMetricas_nu_tpSolicitacao">
<span<?php echo $solicitacaoMetricas->nu_tpSolicitacao->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->nu_tpSolicitacao->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $solicitacaoMetricas->nu_projeto->CellAttributes() ?>>
<span id="el<?php echo $solicitacaoMetricas_delete->RowCnt ?>_solicitacaoMetricas_nu_projeto" class="control-group solicitacaoMetricas_nu_projeto">
<span<?php echo $solicitacaoMetricas->nu_projeto->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->nu_projeto->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $solicitacaoMetricas->ic_stSolicitacao->CellAttributes() ?>>
<span id="el<?php echo $solicitacaoMetricas_delete->RowCnt ?>_solicitacaoMetricas_ic_stSolicitacao" class="control-group solicitacaoMetricas_ic_stSolicitacao">
<span<?php echo $solicitacaoMetricas->ic_stSolicitacao->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->ic_stSolicitacao->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $solicitacaoMetricas->nu_usuarioAlterou->CellAttributes() ?>>
<span id="el<?php echo $solicitacaoMetricas_delete->RowCnt ?>_solicitacaoMetricas_nu_usuarioAlterou" class="control-group solicitacaoMetricas_nu_usuarioAlterou">
<span<?php echo $solicitacaoMetricas->nu_usuarioAlterou->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->nu_usuarioAlterou->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $solicitacaoMetricas->dt_stSolicitacao->CellAttributes() ?>>
<span id="el<?php echo $solicitacaoMetricas_delete->RowCnt ?>_solicitacaoMetricas_dt_stSolicitacao" class="control-group solicitacaoMetricas_dt_stSolicitacao">
<span<?php echo $solicitacaoMetricas->dt_stSolicitacao->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->dt_stSolicitacao->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $solicitacaoMetricas->qt_pfTotal->CellAttributes() ?>>
<span id="el<?php echo $solicitacaoMetricas_delete->RowCnt ?>_solicitacaoMetricas_qt_pfTotal" class="control-group solicitacaoMetricas_qt_pfTotal">
<span<?php echo $solicitacaoMetricas->qt_pfTotal->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->qt_pfTotal->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $solicitacaoMetricas->vr_pfContForn->CellAttributes() ?>>
<span id="el<?php echo $solicitacaoMetricas_delete->RowCnt ?>_solicitacaoMetricas_vr_pfContForn" class="control-group solicitacaoMetricas_vr_pfContForn">
<span<?php echo $solicitacaoMetricas->vr_pfContForn->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->vr_pfContForn->ListViewValue() ?></span>
</span>
</td>
	</tr>
<?php
	$solicitacaoMetricas_delete->Recordset->MoveNext();
}
$solicitacaoMetricas_delete->Recordset->Close();
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
fsolicitacaoMetricasdelete.Init();
</script>
<?php
$solicitacaoMetricas_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$solicitacaoMetricas_delete->Page_Terminate();
?>
