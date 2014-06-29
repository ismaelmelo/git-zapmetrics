<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "riscoprojetoinfo.php" ?>
<?php include_once "projetoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$riscoprojeto_delete = NULL; // Initialize page object first

class criscoprojeto_delete extends criscoprojeto {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'riscoprojeto';

	// Page object name
	var $PageObjName = 'riscoprojeto_delete';

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

		// Table object (riscoprojeto)
		if (!isset($GLOBALS["riscoprojeto"])) {
			$GLOBALS["riscoprojeto"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["riscoprojeto"];
		}

		// Table object (projeto)
		if (!isset($GLOBALS['projeto'])) $GLOBALS['projeto'] = new cprojeto();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'riscoprojeto', TRUE);

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
			$this->Page_Terminate("riscoprojetolist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action
		$this->nu_riscoProjeto->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			$this->Page_Terminate("riscoprojetolist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in riscoprojeto class, riscoprojetoinfo.php

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
		$this->nu_riscoProjeto->setDbValue($rs->fields('nu_riscoProjeto'));
		$this->nu_projeto->setDbValue($rs->fields('nu_projeto'));
		$this->nu_catRisco->setDbValue($rs->fields('nu_catRisco'));
		$this->ic_tpRisco->setDbValue($rs->fields('ic_tpRisco'));
		$this->ds_risco->setDbValue($rs->fields('ds_risco'));
		$this->ds_consequencia->setDbValue($rs->fields('ds_consequencia'));
		$this->nu_probabilidade->setDbValue($rs->fields('nu_probabilidade'));
		$this->nu_impacto->setDbValue($rs->fields('nu_impacto'));
		$this->nu_severidade->setDbValue($rs->fields('nu_severidade'));
		$this->nu_acao->setDbValue($rs->fields('nu_acao'));
		$this->ds_gatilho->setDbValue($rs->fields('ds_gatilho'));
		$this->ds_respRisco->setDbValue($rs->fields('ds_respRisco'));
		$this->nu_usuarioResp->setDbValue($rs->fields('nu_usuarioResp'));
		$this->ic_stRisco->setDbValue($rs->fields('ic_stRisco'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_riscoProjeto->DbValue = $row['nu_riscoProjeto'];
		$this->nu_projeto->DbValue = $row['nu_projeto'];
		$this->nu_catRisco->DbValue = $row['nu_catRisco'];
		$this->ic_tpRisco->DbValue = $row['ic_tpRisco'];
		$this->ds_risco->DbValue = $row['ds_risco'];
		$this->ds_consequencia->DbValue = $row['ds_consequencia'];
		$this->nu_probabilidade->DbValue = $row['nu_probabilidade'];
		$this->nu_impacto->DbValue = $row['nu_impacto'];
		$this->nu_severidade->DbValue = $row['nu_severidade'];
		$this->nu_acao->DbValue = $row['nu_acao'];
		$this->ds_gatilho->DbValue = $row['ds_gatilho'];
		$this->ds_respRisco->DbValue = $row['ds_respRisco'];
		$this->nu_usuarioResp->DbValue = $row['nu_usuarioResp'];
		$this->ic_stRisco->DbValue = $row['ic_stRisco'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_riscoProjeto
		// nu_projeto
		// nu_catRisco
		// ic_tpRisco
		// ds_risco
		// ds_consequencia
		// nu_probabilidade
		// nu_impacto
		// nu_severidade
		// nu_acao
		// ds_gatilho
		// ds_respRisco
		// nu_usuarioResp
		// ic_stRisco

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_riscoProjeto
			$this->nu_riscoProjeto->ViewValue = $this->nu_riscoProjeto->CurrentValue;
			$this->nu_riscoProjeto->ViewCustomAttributes = "";

			// nu_projeto
			if (strval($this->nu_projeto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_projeto]" . ew_SearchString("=", $this->nu_projeto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_projeto], [no_projeto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[projeto]";
			$sWhereWrk = "";
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
			$this->nu_projeto->ViewCustomAttributes = "";

			// nu_catRisco
			if (strval($this->nu_catRisco->CurrentValue) <> "") {
				$sFilterWrk = "[nu_catRisco]" . ew_SearchString("=", $this->nu_catRisco->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_catRisco], [no_catRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[catriscoproj]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_catRisco, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_catRisco] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_catRisco->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_catRisco->ViewValue = $this->nu_catRisco->CurrentValue;
				}
			} else {
				$this->nu_catRisco->ViewValue = NULL;
			}
			$this->nu_catRisco->ViewCustomAttributes = "";

			// ic_tpRisco
			if (strval($this->ic_tpRisco->CurrentValue) <> "") {
				switch ($this->ic_tpRisco->CurrentValue) {
					case $this->ic_tpRisco->FldTagValue(1):
						$this->ic_tpRisco->ViewValue = $this->ic_tpRisco->FldTagCaption(1) <> "" ? $this->ic_tpRisco->FldTagCaption(1) : $this->ic_tpRisco->CurrentValue;
						break;
					case $this->ic_tpRisco->FldTagValue(2):
						$this->ic_tpRisco->ViewValue = $this->ic_tpRisco->FldTagCaption(2) <> "" ? $this->ic_tpRisco->FldTagCaption(2) : $this->ic_tpRisco->CurrentValue;
						break;
					default:
						$this->ic_tpRisco->ViewValue = $this->ic_tpRisco->CurrentValue;
				}
			} else {
				$this->ic_tpRisco->ViewValue = NULL;
			}
			$this->ic_tpRisco->ViewCustomAttributes = "";

			// nu_probabilidade
			if (strval($this->nu_probabilidade->CurrentValue) <> "") {
				$sFilterWrk = "[nu_probOcoRisco]" . ew_SearchString("=", $this->nu_probabilidade->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_probOcoRisco], [no_probOcoRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[probocorisco]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_probabilidade, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_valor] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_probabilidade->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_probabilidade->ViewValue = $this->nu_probabilidade->CurrentValue;
				}
			} else {
				$this->nu_probabilidade->ViewValue = NULL;
			}
			$this->nu_probabilidade->ViewCustomAttributes = "";

			// nu_impacto
			if (strval($this->nu_impacto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_impactoRisco]" . ew_SearchString("=", $this->nu_impacto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_impactoRisco], [no_impactoRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[impactorisco]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_impacto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_valor] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_impacto->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_impacto->ViewValue = $this->nu_impacto->CurrentValue;
				}
			} else {
				$this->nu_impacto->ViewValue = NULL;
			}
			$this->nu_impacto->ViewCustomAttributes = "";

			// nu_severidade
			$this->nu_severidade->ViewValue = $this->nu_severidade->CurrentValue;
			$this->nu_severidade->ViewCustomAttributes = "";

			// nu_acao
			if (strval($this->nu_acao->CurrentValue) <> "") {
				$sFilterWrk = "[nu_acaoRisco]" . ew_SearchString("=", $this->nu_acao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_acaoRisco], [no_acaoRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[acaorisco]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_acao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_acaoRisco] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_acao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_acao->ViewValue = $this->nu_acao->CurrentValue;
				}
			} else {
				$this->nu_acao->ViewValue = NULL;
			}
			$this->nu_acao->ViewCustomAttributes = "";

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

			// ic_stRisco
			if (strval($this->ic_stRisco->CurrentValue) <> "") {
				switch ($this->ic_stRisco->CurrentValue) {
					case $this->ic_stRisco->FldTagValue(1):
						$this->ic_stRisco->ViewValue = $this->ic_stRisco->FldTagCaption(1) <> "" ? $this->ic_stRisco->FldTagCaption(1) : $this->ic_stRisco->CurrentValue;
						break;
					case $this->ic_stRisco->FldTagValue(2):
						$this->ic_stRisco->ViewValue = $this->ic_stRisco->FldTagCaption(2) <> "" ? $this->ic_stRisco->FldTagCaption(2) : $this->ic_stRisco->CurrentValue;
						break;
					default:
						$this->ic_stRisco->ViewValue = $this->ic_stRisco->CurrentValue;
				}
			} else {
				$this->ic_stRisco->ViewValue = NULL;
			}
			$this->ic_stRisco->ViewCustomAttributes = "";

			// nu_riscoProjeto
			$this->nu_riscoProjeto->LinkCustomAttributes = "";
			$this->nu_riscoProjeto->HrefValue = "";
			$this->nu_riscoProjeto->TooltipValue = "";

			// nu_projeto
			$this->nu_projeto->LinkCustomAttributes = "";
			$this->nu_projeto->HrefValue = "";
			$this->nu_projeto->TooltipValue = "";

			// nu_catRisco
			$this->nu_catRisco->LinkCustomAttributes = "";
			$this->nu_catRisco->HrefValue = "";
			$this->nu_catRisco->TooltipValue = "";

			// ic_tpRisco
			$this->ic_tpRisco->LinkCustomAttributes = "";
			$this->ic_tpRisco->HrefValue = "";
			$this->ic_tpRisco->TooltipValue = "";

			// nu_probabilidade
			$this->nu_probabilidade->LinkCustomAttributes = "";
			$this->nu_probabilidade->HrefValue = "";
			$this->nu_probabilidade->TooltipValue = "";

			// nu_impacto
			$this->nu_impacto->LinkCustomAttributes = "";
			$this->nu_impacto->HrefValue = "";
			$this->nu_impacto->TooltipValue = "";

			// nu_severidade
			$this->nu_severidade->LinkCustomAttributes = "";
			$this->nu_severidade->HrefValue = "";
			$this->nu_severidade->TooltipValue = "";

			// nu_acao
			$this->nu_acao->LinkCustomAttributes = "";
			$this->nu_acao->HrefValue = "";
			$this->nu_acao->TooltipValue = "";

			// nu_usuarioResp
			$this->nu_usuarioResp->LinkCustomAttributes = "";
			$this->nu_usuarioResp->HrefValue = "";
			$this->nu_usuarioResp->TooltipValue = "";

			// ic_stRisco
			$this->ic_stRisco->LinkCustomAttributes = "";
			$this->ic_stRisco->HrefValue = "";
			$this->ic_stRisco->TooltipValue = "";
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
				$sThisKey .= $row['nu_riscoProjeto'];
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "riscoprojetolist.php", $this->TableVar);
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
if (!isset($riscoprojeto_delete)) $riscoprojeto_delete = new criscoprojeto_delete();

// Page init
$riscoprojeto_delete->Page_Init();

// Page main
$riscoprojeto_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$riscoprojeto_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var riscoprojeto_delete = new ew_Page("riscoprojeto_delete");
riscoprojeto_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = riscoprojeto_delete.PageID; // For backward compatibility

// Form object
var friscoprojetodelete = new ew_Form("friscoprojetodelete");

// Form_CustomValidate event
friscoprojetodelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
friscoprojetodelete.ValidateRequired = true;
<?php } else { ?>
friscoprojetodelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
friscoprojetodelete.Lists["x_nu_projeto"] = {"LinkField":"x_nu_projeto","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_projeto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
friscoprojetodelete.Lists["x_nu_catRisco"] = {"LinkField":"x_nu_catRisco","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_catRisco","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
friscoprojetodelete.Lists["x_nu_probabilidade"] = {"LinkField":"x_nu_probOcoRisco","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_probOcoRisco","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
friscoprojetodelete.Lists["x_nu_impacto"] = {"LinkField":"x_nu_impactoRisco","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_impactoRisco","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
friscoprojetodelete.Lists["x_nu_acao"] = {"LinkField":"x_nu_acaoRisco","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_acaoRisco","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
friscoprojetodelete.Lists["x_nu_usuarioResp"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($riscoprojeto_delete->Recordset = $riscoprojeto_delete->LoadRecordset())
	$riscoprojeto_deleteTotalRecs = $riscoprojeto_delete->Recordset->RecordCount(); // Get record count
if ($riscoprojeto_deleteTotalRecs <= 0) { // No record found, exit
	if ($riscoprojeto_delete->Recordset)
		$riscoprojeto_delete->Recordset->Close();
	$riscoprojeto_delete->Page_Terminate("riscoprojetolist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $riscoprojeto_delete->ShowPageHeader(); ?>
<?php
$riscoprojeto_delete->ShowMessage();
?>
<form name="friscoprojetodelete" id="friscoprojetodelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="riscoprojeto">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($riscoprojeto_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_riscoprojetodelete" class="ewTable ewTableSeparate">
<?php echo $riscoprojeto->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_riscoprojeto_nu_riscoProjeto" class="riscoprojeto_nu_riscoProjeto"><?php echo $riscoprojeto->nu_riscoProjeto->FldCaption() ?></span></td>
		<td><span id="elh_riscoprojeto_nu_projeto" class="riscoprojeto_nu_projeto"><?php echo $riscoprojeto->nu_projeto->FldCaption() ?></span></td>
		<td><span id="elh_riscoprojeto_nu_catRisco" class="riscoprojeto_nu_catRisco"><?php echo $riscoprojeto->nu_catRisco->FldCaption() ?></span></td>
		<td><span id="elh_riscoprojeto_ic_tpRisco" class="riscoprojeto_ic_tpRisco"><?php echo $riscoprojeto->ic_tpRisco->FldCaption() ?></span></td>
		<td><span id="elh_riscoprojeto_nu_probabilidade" class="riscoprojeto_nu_probabilidade"><?php echo $riscoprojeto->nu_probabilidade->FldCaption() ?></span></td>
		<td><span id="elh_riscoprojeto_nu_impacto" class="riscoprojeto_nu_impacto"><?php echo $riscoprojeto->nu_impacto->FldCaption() ?></span></td>
		<td><span id="elh_riscoprojeto_nu_severidade" class="riscoprojeto_nu_severidade"><?php echo $riscoprojeto->nu_severidade->FldCaption() ?></span></td>
		<td><span id="elh_riscoprojeto_nu_acao" class="riscoprojeto_nu_acao"><?php echo $riscoprojeto->nu_acao->FldCaption() ?></span></td>
		<td><span id="elh_riscoprojeto_nu_usuarioResp" class="riscoprojeto_nu_usuarioResp"><?php echo $riscoprojeto->nu_usuarioResp->FldCaption() ?></span></td>
		<td><span id="elh_riscoprojeto_ic_stRisco" class="riscoprojeto_ic_stRisco"><?php echo $riscoprojeto->ic_stRisco->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$riscoprojeto_delete->RecCnt = 0;
$i = 0;
while (!$riscoprojeto_delete->Recordset->EOF) {
	$riscoprojeto_delete->RecCnt++;
	$riscoprojeto_delete->RowCnt++;

	// Set row properties
	$riscoprojeto->ResetAttrs();
	$riscoprojeto->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$riscoprojeto_delete->LoadRowValues($riscoprojeto_delete->Recordset);

	// Render row
	$riscoprojeto_delete->RenderRow();
?>
	<tr<?php echo $riscoprojeto->RowAttributes() ?>>
		<td<?php echo $riscoprojeto->nu_riscoProjeto->CellAttributes() ?>>
<span id="el<?php echo $riscoprojeto_delete->RowCnt ?>_riscoprojeto_nu_riscoProjeto" class="control-group riscoprojeto_nu_riscoProjeto">
<span<?php echo $riscoprojeto->nu_riscoProjeto->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_riscoProjeto->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $riscoprojeto->nu_projeto->CellAttributes() ?>>
<span id="el<?php echo $riscoprojeto_delete->RowCnt ?>_riscoprojeto_nu_projeto" class="control-group riscoprojeto_nu_projeto">
<span<?php echo $riscoprojeto->nu_projeto->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_projeto->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $riscoprojeto->nu_catRisco->CellAttributes() ?>>
<span id="el<?php echo $riscoprojeto_delete->RowCnt ?>_riscoprojeto_nu_catRisco" class="control-group riscoprojeto_nu_catRisco">
<span<?php echo $riscoprojeto->nu_catRisco->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_catRisco->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $riscoprojeto->ic_tpRisco->CellAttributes() ?>>
<span id="el<?php echo $riscoprojeto_delete->RowCnt ?>_riscoprojeto_ic_tpRisco" class="control-group riscoprojeto_ic_tpRisco">
<span<?php echo $riscoprojeto->ic_tpRisco->ViewAttributes() ?>>
<?php echo $riscoprojeto->ic_tpRisco->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $riscoprojeto->nu_probabilidade->CellAttributes() ?>>
<span id="el<?php echo $riscoprojeto_delete->RowCnt ?>_riscoprojeto_nu_probabilidade" class="control-group riscoprojeto_nu_probabilidade">
<span<?php echo $riscoprojeto->nu_probabilidade->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_probabilidade->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $riscoprojeto->nu_impacto->CellAttributes() ?>>
<span id="el<?php echo $riscoprojeto_delete->RowCnt ?>_riscoprojeto_nu_impacto" class="control-group riscoprojeto_nu_impacto">
<span<?php echo $riscoprojeto->nu_impacto->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_impacto->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $riscoprojeto->nu_severidade->CellAttributes() ?>>
<span id="el<?php echo $riscoprojeto_delete->RowCnt ?>_riscoprojeto_nu_severidade" class="control-group riscoprojeto_nu_severidade">
<span<?php echo $riscoprojeto->nu_severidade->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_severidade->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $riscoprojeto->nu_acao->CellAttributes() ?>>
<span id="el<?php echo $riscoprojeto_delete->RowCnt ?>_riscoprojeto_nu_acao" class="control-group riscoprojeto_nu_acao">
<span<?php echo $riscoprojeto->nu_acao->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_acao->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $riscoprojeto->nu_usuarioResp->CellAttributes() ?>>
<span id="el<?php echo $riscoprojeto_delete->RowCnt ?>_riscoprojeto_nu_usuarioResp" class="control-group riscoprojeto_nu_usuarioResp">
<span<?php echo $riscoprojeto->nu_usuarioResp->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_usuarioResp->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $riscoprojeto->ic_stRisco->CellAttributes() ?>>
<span id="el<?php echo $riscoprojeto_delete->RowCnt ?>_riscoprojeto_ic_stRisco" class="control-group riscoprojeto_ic_stRisco">
<span<?php echo $riscoprojeto->ic_stRisco->ViewAttributes() ?>>
<?php echo $riscoprojeto->ic_stRisco->ListViewValue() ?></span>
</span>
</td>
	</tr>
<?php
	$riscoprojeto_delete->Recordset->MoveNext();
}
$riscoprojeto_delete->Recordset->Close();
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
friscoprojetodelete.Init();
</script>
<?php
$riscoprojeto_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$riscoprojeto_delete->Page_Terminate();
?>
