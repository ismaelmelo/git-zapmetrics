<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "contagempf_funcaoinfo.php" ?>
<?php include_once "contagempfinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$contagempf_funcao_delete = NULL; // Initialize page object first

class ccontagempf_funcao_delete extends ccontagempf_funcao {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'contagempf_funcao';

	// Page object name
	var $PageObjName = 'contagempf_funcao_delete';

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

		// Table object (contagempf_funcao)
		if (!isset($GLOBALS["contagempf_funcao"])) {
			$GLOBALS["contagempf_funcao"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["contagempf_funcao"];
		}

		// Table object (contagempf)
		if (!isset($GLOBALS['contagempf'])) $GLOBALS['contagempf'] = new ccontagempf();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'contagempf_funcao', TRUE);

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
			$this->Page_Terminate("contagempf_funcaolist.php");
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
			$this->Page_Terminate("contagempf_funcaolist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in contagempf_funcao class, contagempf_funcaoinfo.php

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
		$this->nu_contagem->setDbValue($rs->fields('nu_contagem'));
		$this->nu_funcao->setDbValue($rs->fields('nu_funcao'));
		$this->nu_agrupador->setDbValue($rs->fields('nu_agrupador'));
		if (array_key_exists('EV__nu_agrupador', $rs->fields)) {
			$this->nu_agrupador->VirtualValue = $rs->fields('EV__nu_agrupador'); // Set up virtual field value
		} else {
			$this->nu_agrupador->VirtualValue = ""; // Clear value
		}
		$this->nu_uc->setDbValue($rs->fields('nu_uc'));
		$this->no_funcao->setDbValue($rs->fields('no_funcao'));
		$this->nu_tpManutencao->setDbValue($rs->fields('nu_tpManutencao'));
		$this->nu_tpElemento->setDbValue($rs->fields('nu_tpElemento'));
		$this->qt_alr->setDbValue($rs->fields('qt_alr'));
		$this->ds_alr->setDbValue($rs->fields('ds_alr'));
		$this->qt_der->setDbValue($rs->fields('qt_der'));
		$this->ds_der->setDbValue($rs->fields('ds_der'));
		$this->ic_complexApf->setDbValue($rs->fields('ic_complexApf'));
		$this->vr_contribuicao->setDbValue($rs->fields('vr_contribuicao'));
		$this->vr_fatorReducao->setDbValue($rs->fields('vr_fatorReducao'));
		$this->pc_varFasesRoteiro->setDbValue($rs->fields('pc_varFasesRoteiro'));
		$this->vr_qtPf->setDbValue($rs->fields('vr_qtPf'));
		$this->ic_analalogia->setDbValue($rs->fields('ic_analalogia'));
		$this->ds_observacoes->setDbValue($rs->fields('ds_observacoes'));
		$this->nu_usuarioLogado->setDbValue($rs->fields('nu_usuarioLogado'));
		$this->dh_inclusao->setDbValue($rs->fields('dh_inclusao'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_contagem->DbValue = $row['nu_contagem'];
		$this->nu_funcao->DbValue = $row['nu_funcao'];
		$this->nu_agrupador->DbValue = $row['nu_agrupador'];
		$this->nu_uc->DbValue = $row['nu_uc'];
		$this->no_funcao->DbValue = $row['no_funcao'];
		$this->nu_tpManutencao->DbValue = $row['nu_tpManutencao'];
		$this->nu_tpElemento->DbValue = $row['nu_tpElemento'];
		$this->qt_alr->DbValue = $row['qt_alr'];
		$this->ds_alr->DbValue = $row['ds_alr'];
		$this->qt_der->DbValue = $row['qt_der'];
		$this->ds_der->DbValue = $row['ds_der'];
		$this->ic_complexApf->DbValue = $row['ic_complexApf'];
		$this->vr_contribuicao->DbValue = $row['vr_contribuicao'];
		$this->vr_fatorReducao->DbValue = $row['vr_fatorReducao'];
		$this->pc_varFasesRoteiro->DbValue = $row['pc_varFasesRoteiro'];
		$this->vr_qtPf->DbValue = $row['vr_qtPf'];
		$this->ic_analalogia->DbValue = $row['ic_analalogia'];
		$this->ds_observacoes->DbValue = $row['ds_observacoes'];
		$this->nu_usuarioLogado->DbValue = $row['nu_usuarioLogado'];
		$this->dh_inclusao->DbValue = $row['dh_inclusao'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->vr_fatorReducao->FormValue == $this->vr_fatorReducao->CurrentValue && is_numeric(ew_StrToFloat($this->vr_fatorReducao->CurrentValue)))
			$this->vr_fatorReducao->CurrentValue = ew_StrToFloat($this->vr_fatorReducao->CurrentValue);

		// Convert decimal values if posted back
		if ($this->pc_varFasesRoteiro->FormValue == $this->pc_varFasesRoteiro->CurrentValue && is_numeric(ew_StrToFloat($this->pc_varFasesRoteiro->CurrentValue)))
			$this->pc_varFasesRoteiro->CurrentValue = ew_StrToFloat($this->pc_varFasesRoteiro->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_qtPf->FormValue == $this->vr_qtPf->CurrentValue && is_numeric(ew_StrToFloat($this->vr_qtPf->CurrentValue)))
			$this->vr_qtPf->CurrentValue = ew_StrToFloat($this->vr_qtPf->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_contagem

		$this->nu_contagem->CellCssStyle = "white-space: nowrap;";

		// nu_funcao
		// nu_agrupador
		// nu_uc
		// no_funcao
		// nu_tpManutencao
		// nu_tpElemento
		// qt_alr
		// ds_alr
		// qt_der
		// ds_der
		// ic_complexApf
		// vr_contribuicao
		// vr_fatorReducao
		// pc_varFasesRoteiro
		// vr_qtPf
		// ic_analalogia
		// ds_observacoes
		// nu_usuarioLogado
		// dh_inclusao

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_agrupador
			if ($this->nu_agrupador->VirtualValue <> "") {
				$this->nu_agrupador->ViewValue = $this->nu_agrupador->VirtualValue;
			} else {
			if (strval($this->nu_agrupador->CurrentValue) <> "") {
				$sFilterWrk = "[nu_agrupador]" . ew_SearchString("=", $this->nu_agrupador->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_agrupador], [no_agrupador] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[contagempf_agrupador]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_agrupador, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_agrupador] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_agrupador->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_agrupador->ViewValue = $this->nu_agrupador->CurrentValue;
				}
			} else {
				$this->nu_agrupador->ViewValue = NULL;
			}
			}
			$this->nu_agrupador->ViewCustomAttributes = "";

			// nu_uc
			if (strval($this->nu_uc->CurrentValue) <> "") {
				$sFilterWrk = "[nu_uc]" . ew_SearchString("=", $this->nu_uc->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_uc], [co_alternativo] AS [DispFld], [no_uc] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[uc]";
			$sWhereWrk = "";
			$lookuptblfilter = "[nu_sistema] = (SELECT nu_sistema FROM contagempf WHERE nu_contagem = " . strval(CurrentPage()->nu_contagem->CurrentValue) . ")";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_uc, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [co_alternativo] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_uc->ViewValue = $rswrk->fields('DispFld');
					$this->nu_uc->ViewValue .= ew_ValueSeparator(1,$this->nu_uc) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_uc->ViewValue = $this->nu_uc->CurrentValue;
				}
			} else {
				$this->nu_uc->ViewValue = NULL;
			}
			$this->nu_uc->ViewCustomAttributes = "";

			// no_funcao
			$this->no_funcao->ViewValue = $this->no_funcao->CurrentValue;
			$this->no_funcao->ViewCustomAttributes = "";

			// nu_tpManutencao
			if (strval($this->nu_tpManutencao->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpManutencao]" . ew_SearchString("=", $this->nu_tpManutencao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpManutencao], [no_tpManutencao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpmanutencao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[nu_tpContagem]=(SELECT nu_tpContagem FROM contagempf WHERE nu_contagem = " . strval($this->nu_contagem->CurrentValue) . ")";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpManutencao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpManutencao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpManutencao->ViewValue = $this->nu_tpManutencao->CurrentValue;
				}
			} else {
				$this->nu_tpManutencao->ViewValue = NULL;
			}
			$this->nu_tpManutencao->ViewCustomAttributes = "";

			// nu_tpElemento
			if (strval($this->nu_tpElemento->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpElemento]" . ew_SearchString("=", $this->nu_tpElemento->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpElemento], [no_tpElemento] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpElemento]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpElemento, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_tpElemento] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpElemento->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpElemento->ViewValue = $this->nu_tpElemento->CurrentValue;
				}
			} else {
				$this->nu_tpElemento->ViewValue = NULL;
			}
			$this->nu_tpElemento->ViewCustomAttributes = "";

			// qt_alr
			$this->qt_alr->ViewValue = $this->qt_alr->CurrentValue;
			$this->qt_alr->ViewValue = ew_FormatNumber($this->qt_alr->ViewValue, 0, 0, 0, 0);
			$this->qt_alr->ViewCustomAttributes = "";

			// qt_der
			$this->qt_der->ViewValue = $this->qt_der->CurrentValue;
			$this->qt_der->ViewValue = ew_FormatNumber($this->qt_der->ViewValue, 0, 0, 0, 0);
			$this->qt_der->ViewCustomAttributes = "";

			// ic_complexApf
			$this->ic_complexApf->ViewValue = $this->ic_complexApf->CurrentValue;
			$this->ic_complexApf->ViewCustomAttributes = "";

			// vr_contribuicao
			$this->vr_contribuicao->ViewValue = $this->vr_contribuicao->CurrentValue;
			$this->vr_contribuicao->ViewValue = ew_FormatNumber($this->vr_contribuicao->ViewValue, 0, 0, 0, 0);
			$this->vr_contribuicao->ViewCustomAttributes = "";

			// vr_fatorReducao
			$this->vr_fatorReducao->ViewValue = $this->vr_fatorReducao->CurrentValue;
			$this->vr_fatorReducao->ViewCustomAttributes = "";

			// pc_varFasesRoteiro
			$this->pc_varFasesRoteiro->ViewValue = $this->pc_varFasesRoteiro->CurrentValue;
			$this->pc_varFasesRoteiro->ViewCustomAttributes = "";

			// vr_qtPf
			$this->vr_qtPf->ViewValue = $this->vr_qtPf->CurrentValue;
			$this->vr_qtPf->ViewCustomAttributes = "";

			// ic_analalogia
			if (strval($this->ic_analalogia->CurrentValue) <> "") {
				switch ($this->ic_analalogia->CurrentValue) {
					case $this->ic_analalogia->FldTagValue(1):
						$this->ic_analalogia->ViewValue = $this->ic_analalogia->FldTagCaption(1) <> "" ? $this->ic_analalogia->FldTagCaption(1) : $this->ic_analalogia->CurrentValue;
						break;
					case $this->ic_analalogia->FldTagValue(2):
						$this->ic_analalogia->ViewValue = $this->ic_analalogia->FldTagCaption(2) <> "" ? $this->ic_analalogia->FldTagCaption(2) : $this->ic_analalogia->CurrentValue;
						break;
					default:
						$this->ic_analalogia->ViewValue = $this->ic_analalogia->CurrentValue;
				}
			} else {
				$this->ic_analalogia->ViewValue = NULL;
			}
			$this->ic_analalogia->ViewCustomAttributes = "";

			// nu_agrupador
			$this->nu_agrupador->LinkCustomAttributes = "";
			$this->nu_agrupador->HrefValue = "";
			$this->nu_agrupador->TooltipValue = "";

			// nu_uc
			$this->nu_uc->LinkCustomAttributes = "";
			$this->nu_uc->HrefValue = "";
			$this->nu_uc->TooltipValue = "";

			// no_funcao
			$this->no_funcao->LinkCustomAttributes = "";
			$this->no_funcao->HrefValue = "";
			$this->no_funcao->TooltipValue = "";

			// nu_tpManutencao
			$this->nu_tpManutencao->LinkCustomAttributes = "";
			$this->nu_tpManutencao->HrefValue = "";
			$this->nu_tpManutencao->TooltipValue = "";

			// nu_tpElemento
			$this->nu_tpElemento->LinkCustomAttributes = "";
			$this->nu_tpElemento->HrefValue = "";
			$this->nu_tpElemento->TooltipValue = "";

			// qt_alr
			$this->qt_alr->LinkCustomAttributes = "";
			$this->qt_alr->HrefValue = "";
			$this->qt_alr->TooltipValue = "";

			// qt_der
			$this->qt_der->LinkCustomAttributes = "";
			$this->qt_der->HrefValue = "";
			$this->qt_der->TooltipValue = "";

			// ic_complexApf
			$this->ic_complexApf->LinkCustomAttributes = "";
			$this->ic_complexApf->HrefValue = "";
			$this->ic_complexApf->TooltipValue = "";

			// vr_contribuicao
			$this->vr_contribuicao->LinkCustomAttributes = "";
			$this->vr_contribuicao->HrefValue = "";
			$this->vr_contribuicao->TooltipValue = "";

			// vr_fatorReducao
			$this->vr_fatorReducao->LinkCustomAttributes = "";
			$this->vr_fatorReducao->HrefValue = "";
			$this->vr_fatorReducao->TooltipValue = "";

			// pc_varFasesRoteiro
			$this->pc_varFasesRoteiro->LinkCustomAttributes = "";
			$this->pc_varFasesRoteiro->HrefValue = "";
			$this->pc_varFasesRoteiro->TooltipValue = "";

			// vr_qtPf
			$this->vr_qtPf->LinkCustomAttributes = "";
			$this->vr_qtPf->HrefValue = "";
			$this->vr_qtPf->TooltipValue = "";
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
				$sThisKey .= $row['nu_funcao'];
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
		} else {
			$conn->RollbackTrans(); // Rollback changes
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "contagempf_funcaolist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("delete");
		$Breadcrumb->Add("delete", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
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
if (!isset($contagempf_funcao_delete)) $contagempf_funcao_delete = new ccontagempf_funcao_delete();

// Page init
$contagempf_funcao_delete->Page_Init();

// Page main
$contagempf_funcao_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$contagempf_funcao_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var contagempf_funcao_delete = new ew_Page("contagempf_funcao_delete");
contagempf_funcao_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = contagempf_funcao_delete.PageID; // For backward compatibility

// Form object
var fcontagempf_funcaodelete = new ew_Form("fcontagempf_funcaodelete");

// Form_CustomValidate event
fcontagempf_funcaodelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcontagempf_funcaodelete.ValidateRequired = true;
<?php } else { ?>
fcontagempf_funcaodelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcontagempf_funcaodelete.Lists["x_nu_agrupador"] = {"LinkField":"x_nu_agrupador","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_agrupador","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempf_funcaodelete.Lists["x_nu_uc"] = {"LinkField":"x_nu_uc","Ajax":true,"AutoFill":false,"DisplayFields":["x_co_alternativo","x_no_uc","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempf_funcaodelete.Lists["x_nu_tpManutencao"] = {"LinkField":"x_nu_tpManutencao","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpManutencao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempf_funcaodelete.Lists["x_nu_tpElemento"] = {"LinkField":"x_nu_tpElemento","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpElemento","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($contagempf_funcao_delete->Recordset = $contagempf_funcao_delete->LoadRecordset())
	$contagempf_funcao_deleteTotalRecs = $contagempf_funcao_delete->Recordset->RecordCount(); // Get record count
if ($contagempf_funcao_deleteTotalRecs <= 0) { // No record found, exit
	if ($contagempf_funcao_delete->Recordset)
		$contagempf_funcao_delete->Recordset->Close();
	$contagempf_funcao_delete->Page_Terminate("contagempf_funcaolist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $contagempf_funcao_delete->ShowPageHeader(); ?>
<?php
$contagempf_funcao_delete->ShowMessage();
?>
<form name="fcontagempf_funcaodelete" id="fcontagempf_funcaodelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="contagempf_funcao">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($contagempf_funcao_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_contagempf_funcaodelete" class="ewTable ewTableSeparate">
<?php echo $contagempf_funcao->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_contagempf_funcao_nu_agrupador" class="contagempf_funcao_nu_agrupador"><?php echo $contagempf_funcao->nu_agrupador->FldCaption() ?></span></td>
		<td><span id="elh_contagempf_funcao_nu_uc" class="contagempf_funcao_nu_uc"><?php echo $contagempf_funcao->nu_uc->FldCaption() ?></span></td>
		<td><span id="elh_contagempf_funcao_no_funcao" class="contagempf_funcao_no_funcao"><?php echo $contagempf_funcao->no_funcao->FldCaption() ?></span></td>
		<td><span id="elh_contagempf_funcao_nu_tpManutencao" class="contagempf_funcao_nu_tpManutencao"><?php echo $contagempf_funcao->nu_tpManutencao->FldCaption() ?></span></td>
		<td><span id="elh_contagempf_funcao_nu_tpElemento" class="contagempf_funcao_nu_tpElemento"><?php echo $contagempf_funcao->nu_tpElemento->FldCaption() ?></span></td>
		<td><span id="elh_contagempf_funcao_qt_alr" class="contagempf_funcao_qt_alr"><?php echo $contagempf_funcao->qt_alr->FldCaption() ?></span></td>
		<td><span id="elh_contagempf_funcao_qt_der" class="contagempf_funcao_qt_der"><?php echo $contagempf_funcao->qt_der->FldCaption() ?></span></td>
		<td><span id="elh_contagempf_funcao_ic_complexApf" class="contagempf_funcao_ic_complexApf"><?php echo $contagempf_funcao->ic_complexApf->FldCaption() ?></span></td>
		<td><span id="elh_contagempf_funcao_vr_contribuicao" class="contagempf_funcao_vr_contribuicao"><?php echo $contagempf_funcao->vr_contribuicao->FldCaption() ?></span></td>
		<td><span id="elh_contagempf_funcao_vr_fatorReducao" class="contagempf_funcao_vr_fatorReducao"><?php echo $contagempf_funcao->vr_fatorReducao->FldCaption() ?></span></td>
		<td><span id="elh_contagempf_funcao_pc_varFasesRoteiro" class="contagempf_funcao_pc_varFasesRoteiro"><?php echo $contagempf_funcao->pc_varFasesRoteiro->FldCaption() ?></span></td>
		<td><span id="elh_contagempf_funcao_vr_qtPf" class="contagempf_funcao_vr_qtPf"><?php echo $contagempf_funcao->vr_qtPf->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$contagempf_funcao_delete->RecCnt = 0;
$i = 0;
while (!$contagempf_funcao_delete->Recordset->EOF) {
	$contagempf_funcao_delete->RecCnt++;
	$contagempf_funcao_delete->RowCnt++;

	// Set row properties
	$contagempf_funcao->ResetAttrs();
	$contagempf_funcao->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$contagempf_funcao_delete->LoadRowValues($contagempf_funcao_delete->Recordset);

	// Render row
	$contagempf_funcao_delete->RenderRow();
?>
	<tr<?php echo $contagempf_funcao->RowAttributes() ?>>
		<td<?php echo $contagempf_funcao->nu_agrupador->CellAttributes() ?>>
<span id="el<?php echo $contagempf_funcao_delete->RowCnt ?>_contagempf_funcao_nu_agrupador" class="control-group contagempf_funcao_nu_agrupador">
<span<?php echo $contagempf_funcao->nu_agrupador->ViewAttributes() ?>>
<?php echo $contagempf_funcao->nu_agrupador->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $contagempf_funcao->nu_uc->CellAttributes() ?>>
<span id="el<?php echo $contagempf_funcao_delete->RowCnt ?>_contagempf_funcao_nu_uc" class="control-group contagempf_funcao_nu_uc">
<span<?php echo $contagempf_funcao->nu_uc->ViewAttributes() ?>>
<?php echo $contagempf_funcao->nu_uc->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $contagempf_funcao->no_funcao->CellAttributes() ?>>
<span id="el<?php echo $contagempf_funcao_delete->RowCnt ?>_contagempf_funcao_no_funcao" class="control-group contagempf_funcao_no_funcao">
<span<?php echo $contagempf_funcao->no_funcao->ViewAttributes() ?>>
<?php echo $contagempf_funcao->no_funcao->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $contagempf_funcao->nu_tpManutencao->CellAttributes() ?>>
<span id="el<?php echo $contagempf_funcao_delete->RowCnt ?>_contagempf_funcao_nu_tpManutencao" class="control-group contagempf_funcao_nu_tpManutencao">
<span<?php echo $contagempf_funcao->nu_tpManutencao->ViewAttributes() ?>>
<?php echo $contagempf_funcao->nu_tpManutencao->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $contagempf_funcao->nu_tpElemento->CellAttributes() ?>>
<span id="el<?php echo $contagempf_funcao_delete->RowCnt ?>_contagempf_funcao_nu_tpElemento" class="control-group contagempf_funcao_nu_tpElemento">
<span<?php echo $contagempf_funcao->nu_tpElemento->ViewAttributes() ?>>
<?php echo $contagempf_funcao->nu_tpElemento->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $contagempf_funcao->qt_alr->CellAttributes() ?>>
<span id="el<?php echo $contagempf_funcao_delete->RowCnt ?>_contagempf_funcao_qt_alr" class="control-group contagempf_funcao_qt_alr">
<span<?php echo $contagempf_funcao->qt_alr->ViewAttributes() ?>>
<?php echo $contagempf_funcao->qt_alr->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $contagempf_funcao->qt_der->CellAttributes() ?>>
<span id="el<?php echo $contagempf_funcao_delete->RowCnt ?>_contagempf_funcao_qt_der" class="control-group contagempf_funcao_qt_der">
<span<?php echo $contagempf_funcao->qt_der->ViewAttributes() ?>>
<?php echo $contagempf_funcao->qt_der->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $contagempf_funcao->ic_complexApf->CellAttributes() ?>>
<span id="el<?php echo $contagempf_funcao_delete->RowCnt ?>_contagempf_funcao_ic_complexApf" class="control-group contagempf_funcao_ic_complexApf">
<span<?php echo $contagempf_funcao->ic_complexApf->ViewAttributes() ?>>
<?php echo $contagempf_funcao->ic_complexApf->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $contagempf_funcao->vr_contribuicao->CellAttributes() ?>>
<span id="el<?php echo $contagempf_funcao_delete->RowCnt ?>_contagempf_funcao_vr_contribuicao" class="control-group contagempf_funcao_vr_contribuicao">
<span<?php echo $contagempf_funcao->vr_contribuicao->ViewAttributes() ?>>
<?php echo $contagempf_funcao->vr_contribuicao->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $contagempf_funcao->vr_fatorReducao->CellAttributes() ?>>
<span id="el<?php echo $contagempf_funcao_delete->RowCnt ?>_contagempf_funcao_vr_fatorReducao" class="control-group contagempf_funcao_vr_fatorReducao">
<span<?php echo $contagempf_funcao->vr_fatorReducao->ViewAttributes() ?>>
<?php echo $contagempf_funcao->vr_fatorReducao->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $contagempf_funcao->pc_varFasesRoteiro->CellAttributes() ?>>
<span id="el<?php echo $contagempf_funcao_delete->RowCnt ?>_contagempf_funcao_pc_varFasesRoteiro" class="control-group contagempf_funcao_pc_varFasesRoteiro">
<span<?php echo $contagempf_funcao->pc_varFasesRoteiro->ViewAttributes() ?>>
<?php echo $contagempf_funcao->pc_varFasesRoteiro->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $contagempf_funcao->vr_qtPf->CellAttributes() ?>>
<span id="el<?php echo $contagempf_funcao_delete->RowCnt ?>_contagempf_funcao_vr_qtPf" class="control-group contagempf_funcao_vr_qtPf">
<span<?php echo $contagempf_funcao->vr_qtPf->ViewAttributes() ?>>
<?php echo $contagempf_funcao->vr_qtPf->ListViewValue() ?></span>
</span>
</td>
	</tr>
<?php
	$contagempf_funcao_delete->Recordset->MoveNext();
}
$contagempf_funcao_delete->Recordset->Close();
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
fcontagempf_funcaodelete.Init();
</script>
<?php
$contagempf_funcao_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$contagempf_funcao_delete->Page_Terminate();
?>
