<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "contagempfinfo.php" ?>
<?php include_once "solicitacaometricasinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$contagempf_search = NULL; // Initialize page object first

class ccontagempf_search extends ccontagempf {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'contagempf';

	// Page object name
	var $PageObjName = 'contagempf_search';

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

		// Table object (contagempf)
		if (!isset($GLOBALS["contagempf"])) {
			$GLOBALS["contagempf"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["contagempf"];
		}

		// Table object (solicitacaoMetricas)
		if (!isset($GLOBALS['solicitacaoMetricas'])) $GLOBALS['solicitacaoMetricas'] = new csolicitacaoMetricas();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'contagempf', TRUE);

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
		if (!$Security->CanSearch()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("contagempflist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action
		$this->nu_contagem->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsSearchError;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request

			// Get action
			$this->CurrentAction = $objForm->GetValue("a_search");
			switch ($this->CurrentAction) {
				case "S": // Get search criteria

					// Build search string for advanced search, remove blank field
					$this->LoadSearchValues(); // Get search values
					if ($this->ValidateSearch()) {
						$sSrchStr = $this->BuildAdvancedSearch();
					} else {
						$sSrchStr = "";
						$this->setFailureMessage($gsSearchError);
					}
					if ($sSrchStr <> "") {
						$sSrchStr = $this->UrlParm($sSrchStr);
						$this->Page_Terminate("contagempflist.php" . "?" . $sSrchStr); // Go to list page
					}
			}
		}

		// Restore search settings from Session
		if ($gsSearchError == "")
			$this->LoadAdvancedSearch();

		// Render row for search
		$this->RowType = EW_ROWTYPE_SEARCH;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Build advanced search
	function BuildAdvancedSearch() {
		$sSrchUrl = "";
		$this->BuildSearchUrl($sSrchUrl, $this->nu_contagem); // nu_contagem
		$this->BuildSearchUrl($sSrchUrl, $this->nu_solMetricas); // nu_solMetricas
		$this->BuildSearchUrl($sSrchUrl, $this->nu_tpMetrica); // nu_tpMetrica
		$this->BuildSearchUrl($sSrchUrl, $this->nu_tpContagem); // nu_tpContagem
		$this->BuildSearchUrl($sSrchUrl, $this->nu_proposito); // nu_proposito
		$this->BuildSearchUrl($sSrchUrl, $this->nu_sistema); // nu_sistema
		$this->BuildSearchUrl($sSrchUrl, $this->nu_ambiente); // nu_ambiente
		$this->BuildSearchUrl($sSrchUrl, $this->nu_metodologia); // nu_metodologia
		$this->BuildSearchUrl($sSrchUrl, $this->nu_roteiro); // nu_roteiro
		$this->BuildSearchUrl($sSrchUrl, $this->nu_faseMedida); // nu_faseMedida
		$this->BuildSearchUrl($sSrchUrl, $this->nu_usuarioLogado); // nu_usuarioLogado
		$this->BuildSearchUrl($sSrchUrl, $this->dh_inicio); // dh_inicio
		$this->BuildSearchUrl($sSrchUrl, $this->ic_stContagem); // ic_stContagem
		$this->BuildSearchUrl($sSrchUrl, $this->ar_fasesRoteiro); // ar_fasesRoteiro
		$this->BuildSearchUrl($sSrchUrl, $this->pc_varFasesRoteiro); // pc_varFasesRoteiro
		$this->BuildSearchUrl($sSrchUrl, $this->vr_pfFaturamento); // vr_pfFaturamento
		if ($sSrchUrl <> "") $sSrchUrl .= "&";
		$sSrchUrl .= "cmd=search";
		return $sSrchUrl;
	}

	// Build search URL
	function BuildSearchUrl(&$Url, &$Fld, $OprOnly=FALSE) {
		global $objForm;
		$sWrk = "";
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = $objForm->GetValue("x_$FldParm");
		$FldOpr = $objForm->GetValue("z_$FldParm");
		$FldCond = $objForm->GetValue("v_$FldParm");
		$FldVal2 = $objForm->GetValue("y_$FldParm");
		$FldOpr2 = $objForm->GetValue("w_$FldParm");
		$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);
		$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		$lFldDataType = ($Fld->FldIsVirtual) ? EW_DATATYPE_STRING : $Fld->FldDataType;
		if ($FldOpr == "BETWEEN") {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal) && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal <> "" && $FldVal2 <> "" && $IsValidValue) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			}
		} else {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal));
			if ($FldVal <> "" && $IsValidValue && ew_IsValidOpr($FldOpr, $lFldDataType)) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			} elseif ($FldOpr == "IS NULL" || $FldOpr == "IS NOT NULL" || ($FldOpr <> "" && $OprOnly && ew_IsValidOpr($FldOpr, $lFldDataType))) {
				$sWrk = "z_" . $FldParm . "=" . urlencode($FldOpr);
			}
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal2 <> "" && $IsValidValue && ew_IsValidOpr($FldOpr2, $lFldDataType)) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&w_" . $FldParm . "=" . urlencode($FldOpr2);
			} elseif ($FldOpr2 == "IS NULL" || $FldOpr2 == "IS NOT NULL" || ($FldOpr2 <> "" && $OprOnly && ew_IsValidOpr($FldOpr2, $lFldDataType))) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "w_" . $FldParm . "=" . urlencode($FldOpr2);
			}
		}
		if ($sWrk <> "") {
			if ($Url <> "") $Url .= "&";
			$Url .= $sWrk;
		}
	}

	function SearchValueIsNumeric($Fld, $Value) {
		if (ew_IsFloatFormat($Fld->FldType)) $Value = ew_StrToFloat($Value);
		return is_numeric($Value);
	}

	//  Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// nu_contagem

		$this->nu_contagem->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_nu_contagem"));
		$this->nu_contagem->AdvancedSearch->SearchOperator = $objForm->GetValue("z_nu_contagem");

		// nu_solMetricas
		$this->nu_solMetricas->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_nu_solMetricas"));
		$this->nu_solMetricas->AdvancedSearch->SearchOperator = $objForm->GetValue("z_nu_solMetricas");

		// nu_tpMetrica
		$this->nu_tpMetrica->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_nu_tpMetrica"));
		$this->nu_tpMetrica->AdvancedSearch->SearchOperator = $objForm->GetValue("z_nu_tpMetrica");

		// nu_tpContagem
		$this->nu_tpContagem->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_nu_tpContagem"));
		$this->nu_tpContagem->AdvancedSearch->SearchOperator = $objForm->GetValue("z_nu_tpContagem");

		// nu_proposito
		$this->nu_proposito->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_nu_proposito"));
		$this->nu_proposito->AdvancedSearch->SearchOperator = $objForm->GetValue("z_nu_proposito");

		// nu_sistema
		$this->nu_sistema->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_nu_sistema"));
		$this->nu_sistema->AdvancedSearch->SearchOperator = $objForm->GetValue("z_nu_sistema");

		// nu_ambiente
		$this->nu_ambiente->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_nu_ambiente"));
		$this->nu_ambiente->AdvancedSearch->SearchOperator = $objForm->GetValue("z_nu_ambiente");

		// nu_metodologia
		$this->nu_metodologia->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_nu_metodologia"));
		$this->nu_metodologia->AdvancedSearch->SearchOperator = $objForm->GetValue("z_nu_metodologia");

		// nu_roteiro
		$this->nu_roteiro->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_nu_roteiro"));
		$this->nu_roteiro->AdvancedSearch->SearchOperator = $objForm->GetValue("z_nu_roteiro");

		// nu_faseMedida
		$this->nu_faseMedida->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_nu_faseMedida"));
		$this->nu_faseMedida->AdvancedSearch->SearchOperator = $objForm->GetValue("z_nu_faseMedida");

		// nu_usuarioLogado
		$this->nu_usuarioLogado->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_nu_usuarioLogado"));
		$this->nu_usuarioLogado->AdvancedSearch->SearchOperator = $objForm->GetValue("z_nu_usuarioLogado");

		// dh_inicio
		$this->dh_inicio->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_dh_inicio"));
		$this->dh_inicio->AdvancedSearch->SearchOperator = $objForm->GetValue("z_dh_inicio");

		// ic_stContagem
		$this->ic_stContagem->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_ic_stContagem"));
		$this->ic_stContagem->AdvancedSearch->SearchOperator = $objForm->GetValue("z_ic_stContagem");

		// ar_fasesRoteiro
		$this->ar_fasesRoteiro->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_ar_fasesRoteiro"));
		$this->ar_fasesRoteiro->AdvancedSearch->SearchOperator = $objForm->GetValue("z_ar_fasesRoteiro");
		if (is_array($this->ar_fasesRoteiro->AdvancedSearch->SearchValue)) $this->ar_fasesRoteiro->AdvancedSearch->SearchValue = implode(",", $this->ar_fasesRoteiro->AdvancedSearch->SearchValue);
		if (is_array($this->ar_fasesRoteiro->AdvancedSearch->SearchValue2)) $this->ar_fasesRoteiro->AdvancedSearch->SearchValue2 = implode(",", $this->ar_fasesRoteiro->AdvancedSearch->SearchValue2);

		// pc_varFasesRoteiro
		$this->pc_varFasesRoteiro->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_pc_varFasesRoteiro"));
		$this->pc_varFasesRoteiro->AdvancedSearch->SearchOperator = $objForm->GetValue("z_pc_varFasesRoteiro");

		// vr_pfFaturamento
		$this->vr_pfFaturamento->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_vr_pfFaturamento"));
		$this->vr_pfFaturamento->AdvancedSearch->SearchOperator = $objForm->GetValue("z_vr_pfFaturamento");
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->vr_pfFaturamento->FormValue == $this->vr_pfFaturamento->CurrentValue && is_numeric(ew_StrToFloat($this->vr_pfFaturamento->CurrentValue)))
			$this->vr_pfFaturamento->CurrentValue = ew_StrToFloat($this->vr_pfFaturamento->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_contagem
		// nu_solMetricas
		// nu_tpMetrica
		// nu_tpContagem
		// nu_proposito
		// nu_sistema
		// nu_ambiente
		// nu_metodologia
		// nu_roteiro
		// nu_faseMedida
		// nu_usuarioLogado
		// dh_inicio
		// ic_stContagem
		// ar_fasesRoteiro
		// pc_varFasesRoteiro
		// vr_pfFaturamento
		// ic_bloqueio

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_contagem
			$this->nu_contagem->ViewValue = $this->nu_contagem->CurrentValue;
			$this->nu_contagem->ViewCustomAttributes = "";

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
			$sSqlWrk .= " ORDER BY [nu_solMetricas] ASC";
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

			// nu_tpMetrica
			if (strval($this->nu_tpMetrica->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpMetrica]" . ew_SearchString("=", $this->nu_tpMetrica->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpMetrica], [no_tpMetrica] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpmetrica]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo] = 'S'";
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

			// nu_tpContagem
			if (strval($this->nu_tpContagem->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpContagem]" . ew_SearchString("=", $this->nu_tpContagem->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpContagem], [no_tpContagem] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpcontagem]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpContagem, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpContagem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpContagem->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpContagem->ViewValue = $this->nu_tpContagem->CurrentValue;
				}
			} else {
				$this->nu_tpContagem->ViewValue = NULL;
			}
			$this->nu_tpContagem->ViewCustomAttributes = "";

			// nu_proposito
			if (strval($this->nu_proposito->CurrentValue) <> "") {
				$sFilterWrk = "[nu_proposito]" . ew_SearchString("=", $this->nu_proposito->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_proposito], [no_proposito] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[proposito]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_proposito, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_proposito] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_proposito->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_proposito->ViewValue = $this->nu_proposito->CurrentValue;
				}
			} else {
				$this->nu_proposito->ViewValue = NULL;
			}
			$this->nu_proposito->ViewCustomAttributes = "";

			// nu_sistema
			if (strval($this->nu_sistema->CurrentValue) <> "") {
				$sFilterWrk = "[nu_sistema]" . ew_SearchString("=", $this->nu_sistema->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_sistema], [co_alternativo] AS [DispFld], [no_sistema] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[sistema]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_sistema, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [co_alternativo] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_sistema->ViewValue = $rswrk->fields('DispFld');
					$this->nu_sistema->ViewValue .= ew_ValueSeparator(1,$this->nu_sistema) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_sistema->ViewValue = $this->nu_sistema->CurrentValue;
				}
			} else {
				$this->nu_sistema->ViewValue = NULL;
			}
			$this->nu_sistema->ViewCustomAttributes = "";

			// nu_ambiente
			if (strval($this->nu_ambiente->CurrentValue) <> "") {
				$sFilterWrk = "[nu_ambiente]" . ew_SearchString("=", $this->nu_ambiente->CurrentValue, EW_DATATYPE_NUMBER);
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
			$this->Lookup_Selecting($this->nu_ambiente, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_ambiente] ASC";
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

			// nu_metodologia
			if (strval($this->nu_metodologia->CurrentValue) <> "") {
				$sFilterWrk = "[nu_metodologia]" . ew_SearchString("=", $this->nu_metodologia->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_metodologia], [no_metodologia] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[metodologia]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_metodologia, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_metodologia] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_metodologia->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_metodologia->ViewValue = $this->nu_metodologia->CurrentValue;
				}
			} else {
				$this->nu_metodologia->ViewValue = NULL;
			}
			$this->nu_metodologia->ViewCustomAttributes = "";

			// nu_roteiro
			if (strval($this->nu_roteiro->CurrentValue) <> "") {
				$sFilterWrk = "[nu_roteiro]" . ew_SearchString("=", $this->nu_roteiro->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_roteiro], [no_roteiro] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[roteiro]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_roteiro, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_roteiro] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_roteiro->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_roteiro->ViewValue = $this->nu_roteiro->CurrentValue;
				}
			} else {
				$this->nu_roteiro->ViewValue = NULL;
			}
			$this->nu_roteiro->ViewCustomAttributes = "";

			// nu_faseMedida
			if (strval($this->nu_faseMedida->CurrentValue) <> "") {
				$sFilterWrk = "[nu_faseRoteiro]" . ew_SearchString("=", $this->nu_faseMedida->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_faseRoteiro], [no_faseRoteiro] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[faseroteiro]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_faseMedida, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_faseMedida->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_faseMedida->ViewValue = $this->nu_faseMedida->CurrentValue;
				}
			} else {
				$this->nu_faseMedida->ViewValue = NULL;
			}
			$this->nu_faseMedida->ViewCustomAttributes = "";

			// nu_usuarioLogado
			if (strval($this->nu_usuarioLogado->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioLogado->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioLogado, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_usuario] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioLogado->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioLogado->ViewValue = $this->nu_usuarioLogado->CurrentValue;
				}
			} else {
				$this->nu_usuarioLogado->ViewValue = NULL;
			}
			$this->nu_usuarioLogado->ViewCustomAttributes = "";

			// dh_inicio
			$this->dh_inicio->ViewValue = $this->dh_inicio->CurrentValue;
			$this->dh_inicio->ViewValue = ew_FormatDateTime($this->dh_inicio->ViewValue, 7);
			$this->dh_inicio->ViewCustomAttributes = "";

			// ic_stContagem
			if (strval($this->ic_stContagem->CurrentValue) <> "") {
				switch ($this->ic_stContagem->CurrentValue) {
					case $this->ic_stContagem->FldTagValue(1):
						$this->ic_stContagem->ViewValue = $this->ic_stContagem->FldTagCaption(1) <> "" ? $this->ic_stContagem->FldTagCaption(1) : $this->ic_stContagem->CurrentValue;
						break;
					case $this->ic_stContagem->FldTagValue(2):
						$this->ic_stContagem->ViewValue = $this->ic_stContagem->FldTagCaption(2) <> "" ? $this->ic_stContagem->FldTagCaption(2) : $this->ic_stContagem->CurrentValue;
						break;
					case $this->ic_stContagem->FldTagValue(3):
						$this->ic_stContagem->ViewValue = $this->ic_stContagem->FldTagCaption(3) <> "" ? $this->ic_stContagem->FldTagCaption(3) : $this->ic_stContagem->CurrentValue;
						break;
					case $this->ic_stContagem->FldTagValue(4):
						$this->ic_stContagem->ViewValue = $this->ic_stContagem->FldTagCaption(4) <> "" ? $this->ic_stContagem->FldTagCaption(4) : $this->ic_stContagem->CurrentValue;
						break;
					default:
						$this->ic_stContagem->ViewValue = $this->ic_stContagem->CurrentValue;
				}
			} else {
				$this->ic_stContagem->ViewValue = NULL;
			}
			$this->ic_stContagem->ViewCustomAttributes = "";

			// ar_fasesRoteiro
			if (strval($this->ar_fasesRoteiro->CurrentValue) <> "") {
				$arwrk = explode(",", $this->ar_fasesRoteiro->CurrentValue);
				$sFilterWrk = "";
				foreach ($arwrk as $wrk) {
					if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
					$sFilterWrk .= "[nu_faseRoteiro]" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER);
				}	
			$sSqlWrk = "SELECT [nu_faseRoteiro], [no_faseRoteiro] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[faseroteiro]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->ar_fasesRoteiro, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->ar_fasesRoteiro->ViewValue = "";
					$ari = 0;
					while (!$rswrk->EOF) {
						$this->ar_fasesRoteiro->ViewValue .= $rswrk->fields('DispFld');
						$rswrk->MoveNext();
						if (!$rswrk->EOF) $this->ar_fasesRoteiro->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
						$ari++;
					}
					$rswrk->Close();
				} else {
					$this->ar_fasesRoteiro->ViewValue = $this->ar_fasesRoteiro->CurrentValue;
				}
			} else {
				$this->ar_fasesRoteiro->ViewValue = NULL;
			}
			$this->ar_fasesRoteiro->ViewCustomAttributes = "";

			// pc_varFasesRoteiro
			$this->pc_varFasesRoteiro->ViewValue = $this->pc_varFasesRoteiro->CurrentValue;
			$this->pc_varFasesRoteiro->ViewCustomAttributes = "";

			// vr_pfFaturamento
			$this->vr_pfFaturamento->ViewValue = $this->vr_pfFaturamento->CurrentValue;
			$this->vr_pfFaturamento->ViewCustomAttributes = "";

			// ic_bloqueio
			$this->ic_bloqueio->ViewValue = $this->ic_bloqueio->CurrentValue;
			$this->ic_bloqueio->ViewCustomAttributes = "";

			// nu_contagem
			$this->nu_contagem->LinkCustomAttributes = "";
			$this->nu_contagem->HrefValue = "";
			$this->nu_contagem->TooltipValue = "";

			// nu_solMetricas
			$this->nu_solMetricas->LinkCustomAttributes = "";
			$this->nu_solMetricas->HrefValue = "";
			$this->nu_solMetricas->TooltipValue = "";

			// nu_tpMetrica
			$this->nu_tpMetrica->LinkCustomAttributes = "";
			$this->nu_tpMetrica->HrefValue = "";
			$this->nu_tpMetrica->TooltipValue = "";

			// nu_tpContagem
			$this->nu_tpContagem->LinkCustomAttributes = "";
			$this->nu_tpContagem->HrefValue = "";
			$this->nu_tpContagem->TooltipValue = "";

			// nu_proposito
			$this->nu_proposito->LinkCustomAttributes = "";
			$this->nu_proposito->HrefValue = "";
			$this->nu_proposito->TooltipValue = "";

			// nu_sistema
			$this->nu_sistema->LinkCustomAttributes = "";
			$this->nu_sistema->HrefValue = "";
			$this->nu_sistema->TooltipValue = "";

			// nu_ambiente
			$this->nu_ambiente->LinkCustomAttributes = "";
			$this->nu_ambiente->HrefValue = "";
			$this->nu_ambiente->TooltipValue = "";

			// nu_metodologia
			$this->nu_metodologia->LinkCustomAttributes = "";
			$this->nu_metodologia->HrefValue = "";
			$this->nu_metodologia->TooltipValue = "";

			// nu_roteiro
			$this->nu_roteiro->LinkCustomAttributes = "";
			$this->nu_roteiro->HrefValue = "";
			$this->nu_roteiro->TooltipValue = "";

			// nu_faseMedida
			$this->nu_faseMedida->LinkCustomAttributes = "";
			$this->nu_faseMedida->HrefValue = "";
			$this->nu_faseMedida->TooltipValue = "";

			// nu_usuarioLogado
			$this->nu_usuarioLogado->LinkCustomAttributes = "";
			$this->nu_usuarioLogado->HrefValue = "";
			$this->nu_usuarioLogado->TooltipValue = "";

			// dh_inicio
			$this->dh_inicio->LinkCustomAttributes = "";
			$this->dh_inicio->HrefValue = "";
			$this->dh_inicio->TooltipValue = "";

			// ic_stContagem
			$this->ic_stContagem->LinkCustomAttributes = "";
			$this->ic_stContagem->HrefValue = "";
			$this->ic_stContagem->TooltipValue = "";

			// ar_fasesRoteiro
			$this->ar_fasesRoteiro->LinkCustomAttributes = "";
			$this->ar_fasesRoteiro->HrefValue = "";
			$this->ar_fasesRoteiro->TooltipValue = "";

			// pc_varFasesRoteiro
			$this->pc_varFasesRoteiro->LinkCustomAttributes = "";
			$this->pc_varFasesRoteiro->HrefValue = "";
			$this->pc_varFasesRoteiro->TooltipValue = "";

			// vr_pfFaturamento
			$this->vr_pfFaturamento->LinkCustomAttributes = "";
			$this->vr_pfFaturamento->HrefValue = "";
			$this->vr_pfFaturamento->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// nu_contagem
			$this->nu_contagem->EditCustomAttributes = "";
			$this->nu_contagem->EditValue = ew_HtmlEncode($this->nu_contagem->AdvancedSearch->SearchValue);
			$this->nu_contagem->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_contagem->FldCaption()));

			// nu_solMetricas
			$this->nu_solMetricas->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_solMetricas], [nu_solMetricas] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[solicitacaoMetricas]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_solMetricas, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_solMetricas] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_solMetricas->EditValue = $arwrk;

			// nu_tpMetrica
			$this->nu_tpMetrica->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_tpMetrica], [no_tpMetrica] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[tpmetrica]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo] = 'S'";
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
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_tpMetrica->EditValue = $arwrk;

			// nu_tpContagem
			$this->nu_tpContagem->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_tpContagem], [no_tpContagem] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [nu_tpMetrica] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[tpcontagem]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpContagem, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpContagem] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_tpContagem->EditValue = $arwrk;

			// nu_proposito
			$this->nu_proposito->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_proposito], [no_proposito] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [nu_tpContagem] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[proposito]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_proposito, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_proposito] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_proposito->EditValue = $arwrk;

			// nu_sistema
			$this->nu_sistema->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_sistema], [co_alternativo] AS [DispFld], [no_sistema] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[sistema]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_sistema, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [co_alternativo] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_sistema->EditValue = $arwrk;

			// nu_ambiente
			$this->nu_ambiente->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_ambiente], [no_ambiente] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[ambiente]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_ambiente, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_ambiente] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_ambiente->EditValue = $arwrk;

			// nu_metodologia
			$this->nu_metodologia->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_metodologia], [no_metodologia] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[metodologia]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_metodologia, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_metodologia] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_metodologia->EditValue = $arwrk;

			// nu_roteiro
			$this->nu_roteiro->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_roteiro], [no_roteiro] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [nu_metodologia] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[roteiro]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_roteiro, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_roteiro] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_roteiro->EditValue = $arwrk;

			// nu_faseMedida
			$this->nu_faseMedida->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_faseRoteiro], [no_faseRoteiro] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [nu_roteiro] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[faseroteiro]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_faseMedida, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_faseMedida->EditValue = $arwrk;

			// nu_usuarioLogado
			$this->nu_usuarioLogado->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if (!$GLOBALS["contagempf"]->UserIDAllow("search")) $sWhereWrk = $GLOBALS["usuario"]->AddUserIDFilter($sWhereWrk);

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioLogado, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_usuario] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_usuarioLogado->EditValue = $arwrk;

			// dh_inicio
			$this->dh_inicio->EditCustomAttributes = "";
			$this->dh_inicio->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->dh_inicio->AdvancedSearch->SearchValue, 7), 7));
			$this->dh_inicio->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dh_inicio->FldCaption()));

			// ic_stContagem
			$this->ic_stContagem->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_stContagem->FldTagValue(1), $this->ic_stContagem->FldTagCaption(1) <> "" ? $this->ic_stContagem->FldTagCaption(1) : $this->ic_stContagem->FldTagValue(1));
			$arwrk[] = array($this->ic_stContagem->FldTagValue(2), $this->ic_stContagem->FldTagCaption(2) <> "" ? $this->ic_stContagem->FldTagCaption(2) : $this->ic_stContagem->FldTagValue(2));
			$arwrk[] = array($this->ic_stContagem->FldTagValue(3), $this->ic_stContagem->FldTagCaption(3) <> "" ? $this->ic_stContagem->FldTagCaption(3) : $this->ic_stContagem->FldTagValue(3));
			$arwrk[] = array($this->ic_stContagem->FldTagValue(4), $this->ic_stContagem->FldTagCaption(4) <> "" ? $this->ic_stContagem->FldTagCaption(4) : $this->ic_stContagem->FldTagValue(4));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_stContagem->EditValue = $arwrk;

			// ar_fasesRoteiro
			$this->ar_fasesRoteiro->EditCustomAttributes = " checked=checked";
			if (trim(strval($this->ar_fasesRoteiro->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$arwrk = explode(",", $this->ar_fasesRoteiro->AdvancedSearch->SearchValue);
				$sFilterWrk = "";
				foreach ($arwrk as $wrk) {
					if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
					$sFilterWrk .= "[nu_faseRoteiro]" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER);
				}
			}
			$sSqlWrk = "SELECT [nu_faseRoteiro], [no_faseRoteiro] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [nu_roteiro] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[faseroteiro]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->ar_fasesRoteiro, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->ar_fasesRoteiro->EditValue = $arwrk;

			// pc_varFasesRoteiro
			$this->pc_varFasesRoteiro->EditCustomAttributes = "";
			$this->pc_varFasesRoteiro->EditValue = ew_HtmlEncode($this->pc_varFasesRoteiro->AdvancedSearch->SearchValue);
			$this->pc_varFasesRoteiro->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->pc_varFasesRoteiro->FldCaption()));

			// vr_pfFaturamento
			$this->vr_pfFaturamento->EditCustomAttributes = "";
			$this->vr_pfFaturamento->EditValue = ew_HtmlEncode($this->vr_pfFaturamento->AdvancedSearch->SearchValue);
			$this->vr_pfFaturamento->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_pfFaturamento->FldCaption()));
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

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;
		if (!ew_CheckInteger($this->nu_contagem->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->nu_contagem->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->dh_inicio->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->dh_inicio->FldErrMsg());
		}
		if (!ew_CheckNumber($this->vr_pfFaturamento->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->vr_pfFaturamento->FldErrMsg());
		}

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->nu_contagem->AdvancedSearch->Load();
		$this->nu_solMetricas->AdvancedSearch->Load();
		$this->nu_tpMetrica->AdvancedSearch->Load();
		$this->nu_tpContagem->AdvancedSearch->Load();
		$this->nu_proposito->AdvancedSearch->Load();
		$this->nu_sistema->AdvancedSearch->Load();
		$this->nu_ambiente->AdvancedSearch->Load();
		$this->nu_metodologia->AdvancedSearch->Load();
		$this->nu_roteiro->AdvancedSearch->Load();
		$this->nu_faseMedida->AdvancedSearch->Load();
		$this->nu_usuarioLogado->AdvancedSearch->Load();
		$this->dh_inicio->AdvancedSearch->Load();
		$this->ic_stContagem->AdvancedSearch->Load();
		$this->ar_fasesRoteiro->AdvancedSearch->Load();
		$this->pc_varFasesRoteiro->AdvancedSearch->Load();
		$this->vr_pfFaturamento->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "contagempflist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("search");
		$Breadcrumb->Add("search", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
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
if (!isset($contagempf_search)) $contagempf_search = new ccontagempf_search();

// Page init
$contagempf_search->Page_Init();

// Page main
$contagempf_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$contagempf_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var contagempf_search = new ew_Page("contagempf_search");
contagempf_search.PageID = "search"; // Page ID
var EW_PAGE_ID = contagempf_search.PageID; // For backward compatibility

// Form object
var fcontagempfsearch = new ew_Form("fcontagempfsearch");

// Form_CustomValidate event
fcontagempfsearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcontagempfsearch.ValidateRequired = true;
<?php } else { ?>
fcontagempfsearch.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcontagempfsearch.Lists["x_nu_solMetricas"] = {"LinkField":"x_nu_solMetricas","Ajax":null,"AutoFill":false,"DisplayFields":["x_nu_solMetricas","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempfsearch.Lists["x_nu_tpMetrica"] = {"LinkField":"x_nu_tpMetrica","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpMetrica","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempfsearch.Lists["x_nu_tpContagem"] = {"LinkField":"x_nu_tpContagem","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpContagem","","",""],"ParentFields":["x_nu_tpMetrica"],"FilterFields":["x_nu_tpMetrica"],"Options":[]};
fcontagempfsearch.Lists["x_nu_proposito"] = {"LinkField":"x_nu_proposito","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_proposito","","",""],"ParentFields":["x_nu_tpContagem"],"FilterFields":["x_nu_tpContagem"],"Options":[]};
fcontagempfsearch.Lists["x_nu_sistema"] = {"LinkField":"x_nu_sistema","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_alternativo","x_no_sistema","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempfsearch.Lists["x_nu_ambiente"] = {"LinkField":"x_nu_ambiente","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_ambiente","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempfsearch.Lists["x_nu_metodologia"] = {"LinkField":"x_nu_metodologia","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_metodologia","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempfsearch.Lists["x_nu_roteiro"] = {"LinkField":"x_nu_roteiro","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_roteiro","","",""],"ParentFields":["x_nu_metodologia"],"FilterFields":["x_nu_metodologia"],"Options":[]};
fcontagempfsearch.Lists["x_nu_faseMedida"] = {"LinkField":"x_nu_faseRoteiro","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_faseRoteiro","","",""],"ParentFields":["x_nu_roteiro"],"FilterFields":["x_nu_roteiro"],"Options":[]};
fcontagempfsearch.Lists["x_nu_usuarioLogado"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempfsearch.Lists["x_ar_fasesRoteiro[]"] = {"LinkField":"x_nu_faseRoteiro","Ajax":true,"AutoFill":false,"DisplayFields":["x_no_faseRoteiro","","",""],"ParentFields":["x_nu_roteiro"],"FilterFields":["x_nu_roteiro"],"Options":[]};

// Form object for search
// Validate function for search

fcontagempfsearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_nu_contagem");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($contagempf->nu_contagem->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_dh_inicio");
	if (elm && !ew_CheckEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($contagempf->dh_inicio->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_vr_pfFaturamento");
	if (elm && !ew_CheckNumber(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($contagempf->vr_pfFaturamento->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fcontagempfsearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcontagempfsearch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fcontagempfsearch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fcontagempfsearch.Lists["x_nu_tpMetrica"] = {"LinkField":"x_nu_tpMetrica","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpMetrica","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempfsearch.Lists["x_nu_tpContagem"] = {"LinkField":"x_nu_tpContagem","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpContagem","","",""],"ParentFields":["x_nu_tpMetrica"],"FilterFields":["x_nu_tpMetrica"],"Options":[]};
fcontagempfsearch.Lists["x_nu_sistema"] = {"LinkField":"x_nu_sistema","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_alternativo","x_no_sistema","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempfsearch.Lists["x_nu_faseMedida"] = {"LinkField":"x_nu_faseRoteiro","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_faseRoteiro","","",""],"ParentFields":["x_nu_roteiro"],"FilterFields":["x_nu_roteiro"],"Options":[]};
fcontagempfsearch.Lists["x_nu_usuarioLogado"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $contagempf_search->ShowPageHeader(); ?>
<?php
$contagempf_search->ShowMessage();
?>
<form name="fcontagempfsearch" id="fcontagempfsearch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="contagempf">
<input type="hidden" name="a_search" id="a_search" value="S">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_contagempfsearch" class="table table-bordered table-striped">
<?php if ($contagempf->nu_contagem->Visible) { // nu_contagem ?>
	<tr id="r_nu_contagem">
		<td><span id="elh_contagempf_nu_contagem"><?php echo $contagempf->nu_contagem->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_contagem" id="z_nu_contagem" value="="></span></td>
		<td<?php echo $contagempf->nu_contagem->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_contagempf_nu_contagem" class="control-group">
<input type="text" data-field="x_nu_contagem" name="x_nu_contagem" id="x_nu_contagem" placeholder="<?php echo $contagempf->nu_contagem->PlaceHolder ?>" value="<?php echo $contagempf->nu_contagem->EditValue ?>"<?php echo $contagempf->nu_contagem->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($contagempf->nu_solMetricas->Visible) { // nu_solMetricas ?>
	<tr id="r_nu_solMetricas">
		<td><span id="elh_contagempf_nu_solMetricas"><?php echo $contagempf->nu_solMetricas->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_solMetricas" id="z_nu_solMetricas" value="="></span></td>
		<td<?php echo $contagempf->nu_solMetricas->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_contagempf_nu_solMetricas" class="control-group">
<select data-field="x_nu_solMetricas" id="x_nu_solMetricas" name="x_nu_solMetricas"<?php echo $contagempf->nu_solMetricas->EditAttributes() ?>>
<?php
if (is_array($contagempf->nu_solMetricas->EditValue)) {
	$arwrk = $contagempf->nu_solMetricas->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->nu_solMetricas->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fcontagempfsearch.Lists["x_nu_solMetricas"].Options = <?php echo (is_array($contagempf->nu_solMetricas->EditValue)) ? ew_ArrayToJson($contagempf->nu_solMetricas->EditValue, 1) : "[]" ?>;
</script>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($contagempf->nu_tpMetrica->Visible) { // nu_tpMetrica ?>
	<tr id="r_nu_tpMetrica">
		<td><span id="elh_contagempf_nu_tpMetrica"><?php echo $contagempf->nu_tpMetrica->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_tpMetrica" id="z_nu_tpMetrica" value="="></span></td>
		<td<?php echo $contagempf->nu_tpMetrica->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_contagempf_nu_tpMetrica" class="control-group">
<?php $contagempf->nu_tpMetrica->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_tpContagem']); " . @$contagempf->nu_tpMetrica->EditAttrs["onchange"]; ?>
<select data-field="x_nu_tpMetrica" id="x_nu_tpMetrica" name="x_nu_tpMetrica"<?php echo $contagempf->nu_tpMetrica->EditAttributes() ?>>
<?php
if (is_array($contagempf->nu_tpMetrica->EditValue)) {
	$arwrk = $contagempf->nu_tpMetrica->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->nu_tpMetrica->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fcontagempfsearch.Lists["x_nu_tpMetrica"].Options = <?php echo (is_array($contagempf->nu_tpMetrica->EditValue)) ? ew_ArrayToJson($contagempf->nu_tpMetrica->EditValue, 1) : "[]" ?>;
</script>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($contagempf->nu_tpContagem->Visible) { // nu_tpContagem ?>
	<tr id="r_nu_tpContagem">
		<td><span id="elh_contagempf_nu_tpContagem"><?php echo $contagempf->nu_tpContagem->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_tpContagem" id="z_nu_tpContagem" value="="></span></td>
		<td<?php echo $contagempf->nu_tpContagem->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_contagempf_nu_tpContagem" class="control-group">
<?php $contagempf->nu_tpContagem->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_proposito']); " . @$contagempf->nu_tpContagem->EditAttrs["onchange"]; ?>
<select data-field="x_nu_tpContagem" id="x_nu_tpContagem" name="x_nu_tpContagem"<?php echo $contagempf->nu_tpContagem->EditAttributes() ?>>
<?php
if (is_array($contagempf->nu_tpContagem->EditValue)) {
	$arwrk = $contagempf->nu_tpContagem->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->nu_tpContagem->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fcontagempfsearch.Lists["x_nu_tpContagem"].Options = <?php echo (is_array($contagempf->nu_tpContagem->EditValue)) ? ew_ArrayToJson($contagempf->nu_tpContagem->EditValue, 1) : "[]" ?>;
</script>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($contagempf->nu_proposito->Visible) { // nu_proposito ?>
	<tr id="r_nu_proposito">
		<td><span id="elh_contagempf_nu_proposito"><?php echo $contagempf->nu_proposito->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_proposito" id="z_nu_proposito" value="="></span></td>
		<td<?php echo $contagempf->nu_proposito->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_contagempf_nu_proposito" class="control-group">
<select data-field="x_nu_proposito" id="x_nu_proposito" name="x_nu_proposito"<?php echo $contagempf->nu_proposito->EditAttributes() ?>>
<?php
if (is_array($contagempf->nu_proposito->EditValue)) {
	$arwrk = $contagempf->nu_proposito->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->nu_proposito->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fcontagempfsearch.Lists["x_nu_proposito"].Options = <?php echo (is_array($contagempf->nu_proposito->EditValue)) ? ew_ArrayToJson($contagempf->nu_proposito->EditValue, 1) : "[]" ?>;
</script>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($contagempf->nu_sistema->Visible) { // nu_sistema ?>
	<tr id="r_nu_sistema">
		<td><span id="elh_contagempf_nu_sistema"><?php echo $contagempf->nu_sistema->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_sistema" id="z_nu_sistema" value="="></span></td>
		<td<?php echo $contagempf->nu_sistema->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_contagempf_nu_sistema" class="control-group">
<select data-field="x_nu_sistema" id="x_nu_sistema" name="x_nu_sistema"<?php echo $contagempf->nu_sistema->EditAttributes() ?>>
<?php
if (is_array($contagempf->nu_sistema->EditValue)) {
	$arwrk = $contagempf->nu_sistema->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->nu_sistema->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$contagempf->nu_sistema) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fcontagempfsearch.Lists["x_nu_sistema"].Options = <?php echo (is_array($contagempf->nu_sistema->EditValue)) ? ew_ArrayToJson($contagempf->nu_sistema->EditValue, 1) : "[]" ?>;
</script>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($contagempf->nu_ambiente->Visible) { // nu_ambiente ?>
	<tr id="r_nu_ambiente">
		<td><span id="elh_contagempf_nu_ambiente"><?php echo $contagempf->nu_ambiente->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_ambiente" id="z_nu_ambiente" value="="></span></td>
		<td<?php echo $contagempf->nu_ambiente->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_contagempf_nu_ambiente" class="control-group">
<select data-field="x_nu_ambiente" id="x_nu_ambiente" name="x_nu_ambiente"<?php echo $contagempf->nu_ambiente->EditAttributes() ?>>
<?php
if (is_array($contagempf->nu_ambiente->EditValue)) {
	$arwrk = $contagempf->nu_ambiente->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->nu_ambiente->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fcontagempfsearch.Lists["x_nu_ambiente"].Options = <?php echo (is_array($contagempf->nu_ambiente->EditValue)) ? ew_ArrayToJson($contagempf->nu_ambiente->EditValue, 1) : "[]" ?>;
</script>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($contagempf->nu_metodologia->Visible) { // nu_metodologia ?>
	<tr id="r_nu_metodologia">
		<td><span id="elh_contagempf_nu_metodologia"><?php echo $contagempf->nu_metodologia->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_metodologia" id="z_nu_metodologia" value="="></span></td>
		<td<?php echo $contagempf->nu_metodologia->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_contagempf_nu_metodologia" class="control-group">
<?php $contagempf->nu_metodologia->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_roteiro']); " . @$contagempf->nu_metodologia->EditAttrs["onchange"]; ?>
<select data-field="x_nu_metodologia" id="x_nu_metodologia" name="x_nu_metodologia"<?php echo $contagempf->nu_metodologia->EditAttributes() ?>>
<?php
if (is_array($contagempf->nu_metodologia->EditValue)) {
	$arwrk = $contagempf->nu_metodologia->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->nu_metodologia->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fcontagempfsearch.Lists["x_nu_metodologia"].Options = <?php echo (is_array($contagempf->nu_metodologia->EditValue)) ? ew_ArrayToJson($contagempf->nu_metodologia->EditValue, 1) : "[]" ?>;
</script>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($contagempf->nu_roteiro->Visible) { // nu_roteiro ?>
	<tr id="r_nu_roteiro">
		<td><span id="elh_contagempf_nu_roteiro"><?php echo $contagempf->nu_roteiro->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_roteiro" id="z_nu_roteiro" value="="></span></td>
		<td<?php echo $contagempf->nu_roteiro->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_contagempf_nu_roteiro" class="control-group">
<?php $contagempf->nu_roteiro->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_faseMedida','x_ar_fasesRoteiro[]']); " . @$contagempf->nu_roteiro->EditAttrs["onchange"]; ?>
<select data-field="x_nu_roteiro" id="x_nu_roteiro" name="x_nu_roteiro"<?php echo $contagempf->nu_roteiro->EditAttributes() ?>>
<?php
if (is_array($contagempf->nu_roteiro->EditValue)) {
	$arwrk = $contagempf->nu_roteiro->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->nu_roteiro->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fcontagempfsearch.Lists["x_nu_roteiro"].Options = <?php echo (is_array($contagempf->nu_roteiro->EditValue)) ? ew_ArrayToJson($contagempf->nu_roteiro->EditValue, 1) : "[]" ?>;
</script>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($contagempf->nu_faseMedida->Visible) { // nu_faseMedida ?>
	<tr id="r_nu_faseMedida">
		<td><span id="elh_contagempf_nu_faseMedida"><?php echo $contagempf->nu_faseMedida->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_faseMedida" id="z_nu_faseMedida" value="="></span></td>
		<td<?php echo $contagempf->nu_faseMedida->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_contagempf_nu_faseMedida" class="control-group">
<select data-field="x_nu_faseMedida" id="x_nu_faseMedida" name="x_nu_faseMedida"<?php echo $contagempf->nu_faseMedida->EditAttributes() ?>>
<?php
if (is_array($contagempf->nu_faseMedida->EditValue)) {
	$arwrk = $contagempf->nu_faseMedida->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->nu_faseMedida->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fcontagempfsearch.Lists["x_nu_faseMedida"].Options = <?php echo (is_array($contagempf->nu_faseMedida->EditValue)) ? ew_ArrayToJson($contagempf->nu_faseMedida->EditValue, 1) : "[]" ?>;
</script>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($contagempf->nu_usuarioLogado->Visible) { // nu_usuarioLogado ?>
	<tr id="r_nu_usuarioLogado">
		<td><span id="elh_contagempf_nu_usuarioLogado"><?php echo $contagempf->nu_usuarioLogado->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_usuarioLogado" id="z_nu_usuarioLogado" value="="></span></td>
		<td<?php echo $contagempf->nu_usuarioLogado->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_contagempf_nu_usuarioLogado" class="control-group">
<select data-field="x_nu_usuarioLogado" id="x_nu_usuarioLogado" name="x_nu_usuarioLogado"<?php echo $contagempf->nu_usuarioLogado->EditAttributes() ?>>
<?php
if (is_array($contagempf->nu_usuarioLogado->EditValue)) {
	$arwrk = $contagempf->nu_usuarioLogado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->nu_usuarioLogado->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fcontagempfsearch.Lists["x_nu_usuarioLogado"].Options = <?php echo (is_array($contagempf->nu_usuarioLogado->EditValue)) ? ew_ArrayToJson($contagempf->nu_usuarioLogado->EditValue, 1) : "[]" ?>;
</script>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($contagempf->dh_inicio->Visible) { // dh_inicio ?>
	<tr id="r_dh_inicio">
		<td><span id="elh_contagempf_dh_inicio"><?php echo $contagempf->dh_inicio->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_dh_inicio" id="z_dh_inicio" value="="></span></td>
		<td<?php echo $contagempf->dh_inicio->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_contagempf_dh_inicio" class="control-group">
<input type="text" data-field="x_dh_inicio" name="x_dh_inicio" id="x_dh_inicio" placeholder="<?php echo $contagempf->dh_inicio->PlaceHolder ?>" value="<?php echo $contagempf->dh_inicio->EditValue ?>"<?php echo $contagempf->dh_inicio->EditAttributes() ?>>
<?php if (!$contagempf->dh_inicio->ReadOnly && !$contagempf->dh_inicio->Disabled && @$contagempf->dh_inicio->EditAttrs["readonly"] == "" && @$contagempf->dh_inicio->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_dh_inicio" name="cal_x_dh_inicio" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x_dh_inicio" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fcontagempfsearch", "x_dh_inicio", "%d/%m/%Y");
</script>
<?php } ?>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($contagempf->ic_stContagem->Visible) { // ic_stContagem ?>
	<tr id="r_ic_stContagem">
		<td><span id="elh_contagempf_ic_stContagem"><?php echo $contagempf->ic_stContagem->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_ic_stContagem" id="z_ic_stContagem" value="="></span></td>
		<td<?php echo $contagempf->ic_stContagem->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_contagempf_ic_stContagem" class="control-group">
<select data-field="x_ic_stContagem" id="x_ic_stContagem" name="x_ic_stContagem"<?php echo $contagempf->ic_stContagem->EditAttributes() ?>>
<?php
if (is_array($contagempf->ic_stContagem->EditValue)) {
	$arwrk = $contagempf->ic_stContagem->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->ic_stContagem->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($contagempf->ar_fasesRoteiro->Visible) { // ar_fasesRoteiro ?>
	<tr id="r_ar_fasesRoteiro">
		<td><span id="elh_contagempf_ar_fasesRoteiro"><?php echo $contagempf->ar_fasesRoteiro->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ar_fasesRoteiro" id="z_ar_fasesRoteiro" value="LIKE"></span></td>
		<td<?php echo $contagempf->ar_fasesRoteiro->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_contagempf_ar_fasesRoteiro" class="control-group">
<div id="tp_x_ar_fasesRoteiro" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME; ?>"><input type="checkbox" name="x_ar_fasesRoteiro[]" id="x_ar_fasesRoteiro[]" value="{value}"<?php echo $contagempf->ar_fasesRoteiro->EditAttributes() ?>></div>
<div id="dsl_x_ar_fasesRoteiro" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $contagempf->ar_fasesRoteiro->EditValue;
if (is_array($arwrk)) {
	$armultiwrk= explode(",", strval($contagempf->ar_fasesRoteiro->AdvancedSearch->SearchValue));
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = "";
		$cnt = count($armultiwrk);
		for ($ari = 0; $ari < $cnt; $ari++) {
			if (strval($arwrk[$rowcntwrk][0]) == trim(strval($armultiwrk[$ari]))) {
				$selwrk = " checked=\"checked\"";
				if ($selwrk <> "") $emptywrk = FALSE;
				break;
			}
		}

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="checkbox"><input type="checkbox" data-field="x_ar_fasesRoteiro" name="x_ar_fasesRoteiro[]" id="x_ar_fasesRoteiro_<?php echo $rowcntwrk ?>[]" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $contagempf->ar_fasesRoteiro->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
<?php
$sSqlWrk = "SELECT [nu_faseRoteiro], [no_faseRoteiro] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[faseroteiro]";
$sWhereWrk = "{filter}";
$lookuptblfilter = "[ic_ativo]='S'";
if (strval($lookuptblfilter) <> "") {
	ew_AddFilter($sWhereWrk, $lookuptblfilter);
}

// Call Lookup selecting
$contagempf->Lookup_Selecting($contagempf->ar_fasesRoteiro, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
?>
<input type="hidden" name="s_x_ar_fasesRoteiro" id="s_x_ar_fasesRoteiro" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&f0=<?php echo ew_Encrypt("[nu_faseRoteiro] = {filter_value}"); ?>&t0=3&f1=<?php echo ew_Encrypt("[nu_roteiro] IN ({filter_value})"); ?>&t1=3">
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($contagempf->pc_varFasesRoteiro->Visible) { // pc_varFasesRoteiro ?>
	<tr id="r_pc_varFasesRoteiro">
		<td><span id="elh_contagempf_pc_varFasesRoteiro"><?php echo $contagempf->pc_varFasesRoteiro->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_pc_varFasesRoteiro" id="z_pc_varFasesRoteiro" value="="></span></td>
		<td<?php echo $contagempf->pc_varFasesRoteiro->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_contagempf_pc_varFasesRoteiro" class="control-group">
<input type="text" data-field="x_pc_varFasesRoteiro" name="x_pc_varFasesRoteiro" id="x_pc_varFasesRoteiro" size="30" placeholder="<?php echo $contagempf->pc_varFasesRoteiro->PlaceHolder ?>" value="<?php echo $contagempf->pc_varFasesRoteiro->EditValue ?>"<?php echo $contagempf->pc_varFasesRoteiro->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($contagempf->vr_pfFaturamento->Visible) { // vr_pfFaturamento ?>
	<tr id="r_vr_pfFaturamento">
		<td><span id="elh_contagempf_vr_pfFaturamento"><?php echo $contagempf->vr_pfFaturamento->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_vr_pfFaturamento" id="z_vr_pfFaturamento" value="="></span></td>
		<td<?php echo $contagempf->vr_pfFaturamento->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_contagempf_vr_pfFaturamento" class="control-group">
<input type="text" data-field="x_vr_pfFaturamento" name="x_vr_pfFaturamento" id="x_vr_pfFaturamento" size="30" placeholder="<?php echo $contagempf->vr_pfFaturamento->PlaceHolder ?>" value="<?php echo $contagempf->vr_pfFaturamento->EditValue ?>"<?php echo $contagempf->vr_pfFaturamento->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
</form>
<script type="text/javascript">
fcontagempfsearch.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$contagempf_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$contagempf_search->Page_Terminate();
?>
