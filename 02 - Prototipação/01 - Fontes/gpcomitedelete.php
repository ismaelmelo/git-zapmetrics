<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "gpcomiteinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$gpcomite_delete = NULL; // Initialize page object first

class cgpcomite_delete extends cgpcomite {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'gpcomite';

	// Page object name
	var $PageObjName = 'gpcomite_delete';

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

		// Table object (gpcomite)
		if (!isset($GLOBALS["gpcomite"])) {
			$GLOBALS["gpcomite"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["gpcomite"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'gpcomite', TRUE);

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
			$this->Page_Terminate("gpcomitelist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action
		$this->nu_gpComite->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			$this->Page_Terminate("gpcomitelist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in gpcomite class, gpcomiteinfo.php

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
		$this->nu_gpComite->setDbValue($rs->fields('nu_gpComite'));
		$this->no_gpComite->setDbValue($rs->fields('no_gpComite'));
		$this->ic_tpGpOuComite->setDbValue($rs->fields('ic_tpGpOuComite'));
		$this->ds_descricao->setDbValue($rs->fields('ds_descricao'));
		$this->ds_finalidade->setDbValue($rs->fields('ds_finalidade'));
		$this->ic_natureza->setDbValue($rs->fields('ic_natureza'));
		$this->ds_competencias->setDbValue($rs->fields('ds_competencias'));
		$this->ic_periodicidadeReunioes->setDbValue($rs->fields('ic_periodicidadeReunioes'));
		$this->dt_basePeriodicidade->setDbValue($rs->fields('dt_basePeriodicidade'));
		$this->no_localDocDiretrizes->setDbValue($rs->fields('no_localDocDiretrizes'));
		$this->im_anexoDiretrizes->Upload->DbValue = $rs->fields('im_anexoDiretrizes');
		$this->no_localDocComunicacao->setDbValue($rs->fields('no_localDocComunicacao'));
		$this->im_anexoComunicacao->Upload->DbValue = $rs->fields('im_anexoComunicacao');
		$this->no_localParecerJuridico->setDbValue($rs->fields('no_localParecerJuridico'));
		$this->im_anexoParecerJuridico->Upload->DbValue = $rs->fields('im_anexoParecerJuridico');
		$this->no_localDocDesignacao->setDbValue($rs->fields('no_localDocDesignacao'));
		$this->im_anexoDesignacao->Upload->DbValue = $rs->fields('im_anexoDesignacao');
		$this->ds_partesInteressadas->setDbValue($rs->fields('ds_partesInteressadas'));
		$this->nu_usuario->setDbValue($rs->fields('nu_usuario'));
		$this->ts_datahora->setDbValue($rs->fields('ts_datahora'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_gpComite->DbValue = $row['nu_gpComite'];
		$this->no_gpComite->DbValue = $row['no_gpComite'];
		$this->ic_tpGpOuComite->DbValue = $row['ic_tpGpOuComite'];
		$this->ds_descricao->DbValue = $row['ds_descricao'];
		$this->ds_finalidade->DbValue = $row['ds_finalidade'];
		$this->ic_natureza->DbValue = $row['ic_natureza'];
		$this->ds_competencias->DbValue = $row['ds_competencias'];
		$this->ic_periodicidadeReunioes->DbValue = $row['ic_periodicidadeReunioes'];
		$this->dt_basePeriodicidade->DbValue = $row['dt_basePeriodicidade'];
		$this->no_localDocDiretrizes->DbValue = $row['no_localDocDiretrizes'];
		$this->im_anexoDiretrizes->Upload->DbValue = $row['im_anexoDiretrizes'];
		$this->no_localDocComunicacao->DbValue = $row['no_localDocComunicacao'];
		$this->im_anexoComunicacao->Upload->DbValue = $row['im_anexoComunicacao'];
		$this->no_localParecerJuridico->DbValue = $row['no_localParecerJuridico'];
		$this->im_anexoParecerJuridico->Upload->DbValue = $row['im_anexoParecerJuridico'];
		$this->no_localDocDesignacao->DbValue = $row['no_localDocDesignacao'];
		$this->im_anexoDesignacao->Upload->DbValue = $row['im_anexoDesignacao'];
		$this->ds_partesInteressadas->DbValue = $row['ds_partesInteressadas'];
		$this->nu_usuario->DbValue = $row['nu_usuario'];
		$this->ts_datahora->DbValue = $row['ts_datahora'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_gpComite
		// no_gpComite
		// ic_tpGpOuComite
		// ds_descricao
		// ds_finalidade
		// ic_natureza
		// ds_competencias
		// ic_periodicidadeReunioes
		// dt_basePeriodicidade
		// no_localDocDiretrizes
		// im_anexoDiretrizes
		// no_localDocComunicacao
		// im_anexoComunicacao
		// no_localParecerJuridico
		// im_anexoParecerJuridico
		// no_localDocDesignacao
		// im_anexoDesignacao
		// ds_partesInteressadas
		// nu_usuario
		// ts_datahora

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_gpComite
			$this->nu_gpComite->ViewValue = $this->nu_gpComite->CurrentValue;
			$this->nu_gpComite->ViewCustomAttributes = "";

			// no_gpComite
			$this->no_gpComite->ViewValue = $this->no_gpComite->CurrentValue;
			$this->no_gpComite->ViewCustomAttributes = "";

			// ic_tpGpOuComite
			if (strval($this->ic_tpGpOuComite->CurrentValue) <> "") {
				switch ($this->ic_tpGpOuComite->CurrentValue) {
					case $this->ic_tpGpOuComite->FldTagValue(1):
						$this->ic_tpGpOuComite->ViewValue = $this->ic_tpGpOuComite->FldTagCaption(1) <> "" ? $this->ic_tpGpOuComite->FldTagCaption(1) : $this->ic_tpGpOuComite->CurrentValue;
						break;
					case $this->ic_tpGpOuComite->FldTagValue(2):
						$this->ic_tpGpOuComite->ViewValue = $this->ic_tpGpOuComite->FldTagCaption(2) <> "" ? $this->ic_tpGpOuComite->FldTagCaption(2) : $this->ic_tpGpOuComite->CurrentValue;
						break;
					case $this->ic_tpGpOuComite->FldTagValue(3):
						$this->ic_tpGpOuComite->ViewValue = $this->ic_tpGpOuComite->FldTagCaption(3) <> "" ? $this->ic_tpGpOuComite->FldTagCaption(3) : $this->ic_tpGpOuComite->CurrentValue;
						break;
					default:
						$this->ic_tpGpOuComite->ViewValue = $this->ic_tpGpOuComite->CurrentValue;
				}
			} else {
				$this->ic_tpGpOuComite->ViewValue = NULL;
			}
			$this->ic_tpGpOuComite->ViewCustomAttributes = "";

			// ic_natureza
			if (strval($this->ic_natureza->CurrentValue) <> "") {
				switch ($this->ic_natureza->CurrentValue) {
					case $this->ic_natureza->FldTagValue(1):
						$this->ic_natureza->ViewValue = $this->ic_natureza->FldTagCaption(1) <> "" ? $this->ic_natureza->FldTagCaption(1) : $this->ic_natureza->CurrentValue;
						break;
					case $this->ic_natureza->FldTagValue(2):
						$this->ic_natureza->ViewValue = $this->ic_natureza->FldTagCaption(2) <> "" ? $this->ic_natureza->FldTagCaption(2) : $this->ic_natureza->CurrentValue;
						break;
					default:
						$this->ic_natureza->ViewValue = $this->ic_natureza->CurrentValue;
				}
			} else {
				$this->ic_natureza->ViewValue = NULL;
			}
			$this->ic_natureza->ViewCustomAttributes = "";

			// ic_periodicidadeReunioes
			if (strval($this->ic_periodicidadeReunioes->CurrentValue) <> "") {
				switch ($this->ic_periodicidadeReunioes->CurrentValue) {
					case $this->ic_periodicidadeReunioes->FldTagValue(1):
						$this->ic_periodicidadeReunioes->ViewValue = $this->ic_periodicidadeReunioes->FldTagCaption(1) <> "" ? $this->ic_periodicidadeReunioes->FldTagCaption(1) : $this->ic_periodicidadeReunioes->CurrentValue;
						break;
					case $this->ic_periodicidadeReunioes->FldTagValue(2):
						$this->ic_periodicidadeReunioes->ViewValue = $this->ic_periodicidadeReunioes->FldTagCaption(2) <> "" ? $this->ic_periodicidadeReunioes->FldTagCaption(2) : $this->ic_periodicidadeReunioes->CurrentValue;
						break;
					case $this->ic_periodicidadeReunioes->FldTagValue(3):
						$this->ic_periodicidadeReunioes->ViewValue = $this->ic_periodicidadeReunioes->FldTagCaption(3) <> "" ? $this->ic_periodicidadeReunioes->FldTagCaption(3) : $this->ic_periodicidadeReunioes->CurrentValue;
						break;
					case $this->ic_periodicidadeReunioes->FldTagValue(4):
						$this->ic_periodicidadeReunioes->ViewValue = $this->ic_periodicidadeReunioes->FldTagCaption(4) <> "" ? $this->ic_periodicidadeReunioes->FldTagCaption(4) : $this->ic_periodicidadeReunioes->CurrentValue;
						break;
					case $this->ic_periodicidadeReunioes->FldTagValue(5):
						$this->ic_periodicidadeReunioes->ViewValue = $this->ic_periodicidadeReunioes->FldTagCaption(5) <> "" ? $this->ic_periodicidadeReunioes->FldTagCaption(5) : $this->ic_periodicidadeReunioes->CurrentValue;
						break;
					case $this->ic_periodicidadeReunioes->FldTagValue(6):
						$this->ic_periodicidadeReunioes->ViewValue = $this->ic_periodicidadeReunioes->FldTagCaption(6) <> "" ? $this->ic_periodicidadeReunioes->FldTagCaption(6) : $this->ic_periodicidadeReunioes->CurrentValue;
						break;
					default:
						$this->ic_periodicidadeReunioes->ViewValue = $this->ic_periodicidadeReunioes->CurrentValue;
				}
			} else {
				$this->ic_periodicidadeReunioes->ViewValue = NULL;
			}
			$this->ic_periodicidadeReunioes->ViewCustomAttributes = "";

			// dt_basePeriodicidade
			$this->dt_basePeriodicidade->ViewValue = $this->dt_basePeriodicidade->CurrentValue;
			$this->dt_basePeriodicidade->ViewValue = ew_FormatDateTime($this->dt_basePeriodicidade->ViewValue, 7);
			$this->dt_basePeriodicidade->ViewCustomAttributes = "";

			// no_localDocDiretrizes
			$this->no_localDocDiretrizes->ViewValue = $this->no_localDocDiretrizes->CurrentValue;
			$this->no_localDocDiretrizes->ViewCustomAttributes = "";

			// im_anexoDiretrizes
			$this->im_anexoDiretrizes->UploadPath = "arquivos/grupocti_diretrizes";
			if (!ew_Empty($this->im_anexoDiretrizes->Upload->DbValue)) {
				$this->im_anexoDiretrizes->ViewValue = $this->im_anexoDiretrizes->Upload->DbValue;
			} else {
				$this->im_anexoDiretrizes->ViewValue = "";
			}
			$this->im_anexoDiretrizes->ViewCustomAttributes = "";

			// no_localDocComunicacao
			$this->no_localDocComunicacao->ViewValue = $this->no_localDocComunicacao->CurrentValue;
			$this->no_localDocComunicacao->ViewCustomAttributes = "";

			// im_anexoComunicacao
			$this->im_anexoComunicacao->UploadPath = "arquivos/grupocti_comunicacao";
			if (!ew_Empty($this->im_anexoComunicacao->Upload->DbValue)) {
				$this->im_anexoComunicacao->ViewValue = $this->im_anexoComunicacao->Upload->DbValue;
			} else {
				$this->im_anexoComunicacao->ViewValue = "";
			}
			$this->im_anexoComunicacao->ViewCustomAttributes = "";

			// no_localParecerJuridico
			$this->no_localParecerJuridico->ViewValue = $this->no_localParecerJuridico->CurrentValue;
			$this->no_localParecerJuridico->ViewCustomAttributes = "";

			// im_anexoParecerJuridico
			$this->im_anexoParecerJuridico->UploadPath = "arquivos/grupocti_parjuridico";
			if (!ew_Empty($this->im_anexoParecerJuridico->Upload->DbValue)) {
				$this->im_anexoParecerJuridico->ViewValue = $this->im_anexoParecerJuridico->Upload->DbValue;
			} else {
				$this->im_anexoParecerJuridico->ViewValue = "";
			}
			$this->im_anexoParecerJuridico->ViewCustomAttributes = "";

			// no_localDocDesignacao
			$this->no_localDocDesignacao->ViewValue = $this->no_localDocDesignacao->CurrentValue;
			$this->no_localDocDesignacao->ViewCustomAttributes = "";

			// im_anexoDesignacao
			$this->im_anexoDesignacao->UploadPath = "arquivos/grupocti_designacao";
			if (!ew_Empty($this->im_anexoDesignacao->Upload->DbValue)) {
				$this->im_anexoDesignacao->ViewValue = $this->im_anexoDesignacao->Upload->DbValue;
			} else {
				$this->im_anexoDesignacao->ViewValue = "";
			}
			$this->im_anexoDesignacao->ViewCustomAttributes = "";

			// nu_usuario
			$this->nu_usuario->ViewValue = $this->nu_usuario->CurrentValue;
			$this->nu_usuario->ViewCustomAttributes = "";

			// ts_datahora
			$this->ts_datahora->ViewValue = $this->ts_datahora->CurrentValue;
			$this->ts_datahora->ViewValue = ew_FormatDateTime($this->ts_datahora->ViewValue, 7);
			$this->ts_datahora->ViewCustomAttributes = "";

			// nu_gpComite
			$this->nu_gpComite->LinkCustomAttributes = "";
			$this->nu_gpComite->HrefValue = "";
			$this->nu_gpComite->TooltipValue = "";

			// no_gpComite
			$this->no_gpComite->LinkCustomAttributes = "";
			$this->no_gpComite->HrefValue = "";
			$this->no_gpComite->TooltipValue = "";

			// ic_tpGpOuComite
			$this->ic_tpGpOuComite->LinkCustomAttributes = "";
			$this->ic_tpGpOuComite->HrefValue = "";
			$this->ic_tpGpOuComite->TooltipValue = "";

			// ic_natureza
			$this->ic_natureza->LinkCustomAttributes = "";
			$this->ic_natureza->HrefValue = "";
			$this->ic_natureza->TooltipValue = "";
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
				$sThisKey .= $row['nu_gpComite'];
				$this->LoadDbValues($row);
				$this->im_anexoDiretrizes->OldUploadPath = "arquivos/grupocti_diretrizes";
				@unlink(ew_UploadPathEx(TRUE, $this->im_anexoDiretrizes->OldUploadPath) . $row['im_anexoDiretrizes']);
				$this->im_anexoComunicacao->OldUploadPath = "arquivos/grupocti_comunicacao";
				@unlink(ew_UploadPathEx(TRUE, $this->im_anexoComunicacao->OldUploadPath) . $row['im_anexoComunicacao']);
				$this->im_anexoParecerJuridico->OldUploadPath = "arquivos/grupocti_parjuridico";
				@unlink(ew_UploadPathEx(TRUE, $this->im_anexoParecerJuridico->OldUploadPath) . $row['im_anexoParecerJuridico']);
				$this->im_anexoDesignacao->OldUploadPath = "arquivos/grupocti_designacao";
				@unlink(ew_UploadPathEx(TRUE, $this->im_anexoDesignacao->OldUploadPath) . $row['im_anexoDesignacao']);
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "gpcomitelist.php", $this->TableVar);
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
if (!isset($gpcomite_delete)) $gpcomite_delete = new cgpcomite_delete();

// Page init
$gpcomite_delete->Page_Init();

// Page main
$gpcomite_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$gpcomite_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var gpcomite_delete = new ew_Page("gpcomite_delete");
gpcomite_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = gpcomite_delete.PageID; // For backward compatibility

// Form object
var fgpcomitedelete = new ew_Form("fgpcomitedelete");

// Form_CustomValidate event
fgpcomitedelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgpcomitedelete.ValidateRequired = true;
<?php } else { ?>
fgpcomitedelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($gpcomite_delete->Recordset = $gpcomite_delete->LoadRecordset())
	$gpcomite_deleteTotalRecs = $gpcomite_delete->Recordset->RecordCount(); // Get record count
if ($gpcomite_deleteTotalRecs <= 0) { // No record found, exit
	if ($gpcomite_delete->Recordset)
		$gpcomite_delete->Recordset->Close();
	$gpcomite_delete->Page_Terminate("gpcomitelist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $gpcomite_delete->ShowPageHeader(); ?>
<?php
$gpcomite_delete->ShowMessage();
?>
<form name="fgpcomitedelete" id="fgpcomitedelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="gpcomite">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($gpcomite_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_gpcomitedelete" class="ewTable ewTableSeparate">
<?php echo $gpcomite->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_gpcomite_nu_gpComite" class="gpcomite_nu_gpComite"><?php echo $gpcomite->nu_gpComite->FldCaption() ?></span></td>
		<td><span id="elh_gpcomite_no_gpComite" class="gpcomite_no_gpComite"><?php echo $gpcomite->no_gpComite->FldCaption() ?></span></td>
		<td><span id="elh_gpcomite_ic_tpGpOuComite" class="gpcomite_ic_tpGpOuComite"><?php echo $gpcomite->ic_tpGpOuComite->FldCaption() ?></span></td>
		<td><span id="elh_gpcomite_ic_natureza" class="gpcomite_ic_natureza"><?php echo $gpcomite->ic_natureza->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$gpcomite_delete->RecCnt = 0;
$i = 0;
while (!$gpcomite_delete->Recordset->EOF) {
	$gpcomite_delete->RecCnt++;
	$gpcomite_delete->RowCnt++;

	// Set row properties
	$gpcomite->ResetAttrs();
	$gpcomite->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$gpcomite_delete->LoadRowValues($gpcomite_delete->Recordset);

	// Render row
	$gpcomite_delete->RenderRow();
?>
	<tr<?php echo $gpcomite->RowAttributes() ?>>
		<td<?php echo $gpcomite->nu_gpComite->CellAttributes() ?>>
<span id="el<?php echo $gpcomite_delete->RowCnt ?>_gpcomite_nu_gpComite" class="control-group gpcomite_nu_gpComite">
<span<?php echo $gpcomite->nu_gpComite->ViewAttributes() ?>>
<?php echo $gpcomite->nu_gpComite->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $gpcomite->no_gpComite->CellAttributes() ?>>
<span id="el<?php echo $gpcomite_delete->RowCnt ?>_gpcomite_no_gpComite" class="control-group gpcomite_no_gpComite">
<span<?php echo $gpcomite->no_gpComite->ViewAttributes() ?>>
<?php echo $gpcomite->no_gpComite->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $gpcomite->ic_tpGpOuComite->CellAttributes() ?>>
<span id="el<?php echo $gpcomite_delete->RowCnt ?>_gpcomite_ic_tpGpOuComite" class="control-group gpcomite_ic_tpGpOuComite">
<span<?php echo $gpcomite->ic_tpGpOuComite->ViewAttributes() ?>>
<?php echo $gpcomite->ic_tpGpOuComite->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $gpcomite->ic_natureza->CellAttributes() ?>>
<span id="el<?php echo $gpcomite_delete->RowCnt ?>_gpcomite_ic_natureza" class="control-group gpcomite_ic_natureza">
<span<?php echo $gpcomite->ic_natureza->ViewAttributes() ?>>
<?php echo $gpcomite->ic_natureza->ListViewValue() ?></span>
</span>
</td>
	</tr>
<?php
	$gpcomite_delete->Recordset->MoveNext();
}
$gpcomite_delete->Recordset->Close();
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
fgpcomitedelete.Init();
</script>
<?php
$gpcomite_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$gpcomite_delete->Page_Terminate();
?>
