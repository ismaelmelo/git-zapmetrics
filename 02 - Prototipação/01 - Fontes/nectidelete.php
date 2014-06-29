<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "nectiinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$necti_delete = NULL; // Initialize page object first

class cnecti_delete extends cnecti {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'necti';

	// Page object name
	var $PageObjName = 'necti_delete';

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

		// Table object (necti)
		if (!isset($GLOBALS["necti"])) {
			$GLOBALS["necti"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["necti"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'necti', TRUE);

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
			$this->Page_Terminate("nectilist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action
		$this->nu_necTi->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			$this->Page_Terminate("nectilist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in necti class, nectiinfo.php

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
		$this->nu_necTi->setDbValue($rs->fields('nu_necTi'));
		$this->nu_periodoPei->setDbValue($rs->fields('nu_periodoPei'));
		if (array_key_exists('EV__nu_periodoPei', $rs->fields)) {
			$this->nu_periodoPei->VirtualValue = $rs->fields('EV__nu_periodoPei'); // Set up virtual field value
		} else {
			$this->nu_periodoPei->VirtualValue = ""; // Clear value
		}
		$this->nu_periodoPdti->setDbValue($rs->fields('nu_periodoPdti'));
		if (array_key_exists('EV__nu_periodoPdti', $rs->fields)) {
			$this->nu_periodoPdti->VirtualValue = $rs->fields('EV__nu_periodoPdti'); // Set up virtual field value
		} else {
			$this->nu_periodoPdti->VirtualValue = ""; // Clear value
		}
		$this->nu_tpNecTi->setDbValue($rs->fields('nu_tpNecTi'));
		$this->ic_tpNec->setDbValue($rs->fields('ic_tpNec'));
		$this->nu_metaneg->setDbValue($rs->fields('nu_metaneg'));
		$this->nu_origem->setDbValue($rs->fields('nu_origem'));
		$this->nu_area->setDbValue($rs->fields('nu_area'));
		$this->ic_gravidade->setDbValue($rs->fields('ic_gravidade'));
		$this->ic_urgencia->setDbValue($rs->fields('ic_urgencia'));
		$this->ic_tendencia->setDbValue($rs->fields('ic_tendencia'));
		$this->ic_prioridade->setDbValue($rs->fields('ic_prioridade'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_necTi->DbValue = $row['nu_necTi'];
		$this->nu_periodoPei->DbValue = $row['nu_periodoPei'];
		$this->nu_periodoPdti->DbValue = $row['nu_periodoPdti'];
		$this->nu_tpNecTi->DbValue = $row['nu_tpNecTi'];
		$this->ic_tpNec->DbValue = $row['ic_tpNec'];
		$this->nu_metaneg->DbValue = $row['nu_metaneg'];
		$this->nu_origem->DbValue = $row['nu_origem'];
		$this->nu_area->DbValue = $row['nu_area'];
		$this->ic_gravidade->DbValue = $row['ic_gravidade'];
		$this->ic_urgencia->DbValue = $row['ic_urgencia'];
		$this->ic_tendencia->DbValue = $row['ic_tendencia'];
		$this->ic_prioridade->DbValue = $row['ic_prioridade'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_necTi
		// nu_periodoPei
		// nu_periodoPdti
		// nu_tpNecTi
		// ic_tpNec
		// nu_metaneg
		// nu_origem
		// nu_area
		// ic_gravidade
		// ic_urgencia
		// ic_tendencia
		// ic_prioridade

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_necTi
			$this->nu_necTi->ViewValue = $this->nu_necTi->CurrentValue;
			$this->nu_necTi->ViewCustomAttributes = "";

			// nu_periodoPei
			if ($this->nu_periodoPei->VirtualValue <> "") {
				$this->nu_periodoPei->ViewValue = $this->nu_periodoPei->VirtualValue;
			} else {
			if (strval($this->nu_periodoPei->CurrentValue) <> "") {
				$sFilterWrk = "[nu_periodoPei]" . ew_SearchString("=", $this->nu_periodoPei->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_periodoPei], [nu_anoInicio] AS [DispFld], [nu_anoFim] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[periodopei]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_periodoPei, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_anoInicio] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_periodoPei->ViewValue = $rswrk->fields('DispFld');
					$this->nu_periodoPei->ViewValue .= ew_ValueSeparator(1,$this->nu_periodoPei) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_periodoPei->ViewValue = $this->nu_periodoPei->CurrentValue;
				}
			} else {
				$this->nu_periodoPei->ViewValue = NULL;
			}
			}
			$this->nu_periodoPei->ViewCustomAttributes = "";

			// nu_periodoPdti
			if ($this->nu_periodoPdti->VirtualValue <> "") {
				$this->nu_periodoPdti->ViewValue = $this->nu_periodoPdti->VirtualValue;
			} else {
			if (strval($this->nu_periodoPdti->CurrentValue) <> "") {
				$sFilterWrk = "[nu_periodo]" . ew_SearchString("=", $this->nu_periodoPdti->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_periodo], [no_periodo] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[perplanejamento]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_periodoPdti, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_anoInicio] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_periodoPdti->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_periodoPdti->ViewValue = $this->nu_periodoPdti->CurrentValue;
				}
			} else {
				$this->nu_periodoPdti->ViewValue = NULL;
			}
			}
			$this->nu_periodoPdti->ViewCustomAttributes = "";

			// nu_tpNecTi
			if (strval($this->nu_tpNecTi->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpNecTi]" . ew_SearchString("=", $this->nu_tpNecTi->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT DISTINCT [nu_tpNecTi], [no_tpNecTi] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpnecti]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpNecTi, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpNecTi] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpNecTi->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpNecTi->ViewValue = $this->nu_tpNecTi->CurrentValue;
				}
			} else {
				$this->nu_tpNecTi->ViewValue = NULL;
			}
			$this->nu_tpNecTi->ViewCustomAttributes = "";

			// ic_tpNec
			if (strval($this->ic_tpNec->CurrentValue) <> "") {
				switch ($this->ic_tpNec->CurrentValue) {
					case $this->ic_tpNec->FldTagValue(1):
						$this->ic_tpNec->ViewValue = $this->ic_tpNec->FldTagCaption(1) <> "" ? $this->ic_tpNec->FldTagCaption(1) : $this->ic_tpNec->CurrentValue;
						break;
					case $this->ic_tpNec->FldTagValue(2):
						$this->ic_tpNec->ViewValue = $this->ic_tpNec->FldTagCaption(2) <> "" ? $this->ic_tpNec->FldTagCaption(2) : $this->ic_tpNec->CurrentValue;
						break;
					default:
						$this->ic_tpNec->ViewValue = $this->ic_tpNec->CurrentValue;
				}
			} else {
				$this->ic_tpNec->ViewValue = NULL;
			}
			$this->ic_tpNec->ViewCustomAttributes = "";

			// nu_metaneg
			if (strval($this->nu_metaneg->CurrentValue) <> "") {
				$sFilterWrk = "[nu_metaneg]" . ew_SearchString("=", $this->nu_metaneg->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_metaneg], [no_metaneg] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[metaneg]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_metaneg, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_metaneg->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_metaneg->ViewValue = $this->nu_metaneg->CurrentValue;
				}
			} else {
				$this->nu_metaneg->ViewValue = NULL;
			}
			$this->nu_metaneg->ViewCustomAttributes = "";

			// nu_origem
			if (strval($this->nu_origem->CurrentValue) <> "") {
				$sFilterWrk = "[nu_origem]" . ew_SearchString("=", $this->nu_origem->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT DISTINCT [nu_origem], [no_origem] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[origemnecti]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_origem, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_origem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_origem->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_origem->ViewValue = $this->nu_origem->CurrentValue;
				}
			} else {
				$this->nu_origem->ViewValue = NULL;
			}
			$this->nu_origem->ViewCustomAttributes = "";

			// nu_area
			$this->nu_area->ViewValue = $this->nu_area->CurrentValue;
			if (strval($this->nu_area->CurrentValue) <> "") {
				$sFilterWrk = "[nu_area]" . ew_SearchString("=", $this->nu_area->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_area], [no_area] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[area]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]=S && [nu_organizacao] = (SELECT nu_orgBase from organizacao)";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_area, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_area] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_area->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_area->ViewValue = $this->nu_area->CurrentValue;
				}
			} else {
				$this->nu_area->ViewValue = NULL;
			}
			$this->nu_area->ViewCustomAttributes = "";

			// ic_gravidade
			if (strval($this->ic_gravidade->CurrentValue) <> "") {
				switch ($this->ic_gravidade->CurrentValue) {
					case $this->ic_gravidade->FldTagValue(1):
						$this->ic_gravidade->ViewValue = $this->ic_gravidade->FldTagCaption(1) <> "" ? $this->ic_gravidade->FldTagCaption(1) : $this->ic_gravidade->CurrentValue;
						break;
					case $this->ic_gravidade->FldTagValue(2):
						$this->ic_gravidade->ViewValue = $this->ic_gravidade->FldTagCaption(2) <> "" ? $this->ic_gravidade->FldTagCaption(2) : $this->ic_gravidade->CurrentValue;
						break;
					case $this->ic_gravidade->FldTagValue(3):
						$this->ic_gravidade->ViewValue = $this->ic_gravidade->FldTagCaption(3) <> "" ? $this->ic_gravidade->FldTagCaption(3) : $this->ic_gravidade->CurrentValue;
						break;
					case $this->ic_gravidade->FldTagValue(4):
						$this->ic_gravidade->ViewValue = $this->ic_gravidade->FldTagCaption(4) <> "" ? $this->ic_gravidade->FldTagCaption(4) : $this->ic_gravidade->CurrentValue;
						break;
					case $this->ic_gravidade->FldTagValue(5):
						$this->ic_gravidade->ViewValue = $this->ic_gravidade->FldTagCaption(5) <> "" ? $this->ic_gravidade->FldTagCaption(5) : $this->ic_gravidade->CurrentValue;
						break;
					default:
						$this->ic_gravidade->ViewValue = $this->ic_gravidade->CurrentValue;
				}
			} else {
				$this->ic_gravidade->ViewValue = NULL;
			}
			$this->ic_gravidade->ViewCustomAttributes = "";

			// ic_urgencia
			if (strval($this->ic_urgencia->CurrentValue) <> "") {
				switch ($this->ic_urgencia->CurrentValue) {
					case $this->ic_urgencia->FldTagValue(1):
						$this->ic_urgencia->ViewValue = $this->ic_urgencia->FldTagCaption(1) <> "" ? $this->ic_urgencia->FldTagCaption(1) : $this->ic_urgencia->CurrentValue;
						break;
					case $this->ic_urgencia->FldTagValue(2):
						$this->ic_urgencia->ViewValue = $this->ic_urgencia->FldTagCaption(2) <> "" ? $this->ic_urgencia->FldTagCaption(2) : $this->ic_urgencia->CurrentValue;
						break;
					case $this->ic_urgencia->FldTagValue(3):
						$this->ic_urgencia->ViewValue = $this->ic_urgencia->FldTagCaption(3) <> "" ? $this->ic_urgencia->FldTagCaption(3) : $this->ic_urgencia->CurrentValue;
						break;
					case $this->ic_urgencia->FldTagValue(4):
						$this->ic_urgencia->ViewValue = $this->ic_urgencia->FldTagCaption(4) <> "" ? $this->ic_urgencia->FldTagCaption(4) : $this->ic_urgencia->CurrentValue;
						break;
					case $this->ic_urgencia->FldTagValue(5):
						$this->ic_urgencia->ViewValue = $this->ic_urgencia->FldTagCaption(5) <> "" ? $this->ic_urgencia->FldTagCaption(5) : $this->ic_urgencia->CurrentValue;
						break;
					default:
						$this->ic_urgencia->ViewValue = $this->ic_urgencia->CurrentValue;
				}
			} else {
				$this->ic_urgencia->ViewValue = NULL;
			}
			$this->ic_urgencia->ViewCustomAttributes = "";

			// ic_tendencia
			if (strval($this->ic_tendencia->CurrentValue) <> "") {
				switch ($this->ic_tendencia->CurrentValue) {
					case $this->ic_tendencia->FldTagValue(1):
						$this->ic_tendencia->ViewValue = $this->ic_tendencia->FldTagCaption(1) <> "" ? $this->ic_tendencia->FldTagCaption(1) : $this->ic_tendencia->CurrentValue;
						break;
					case $this->ic_tendencia->FldTagValue(2):
						$this->ic_tendencia->ViewValue = $this->ic_tendencia->FldTagCaption(2) <> "" ? $this->ic_tendencia->FldTagCaption(2) : $this->ic_tendencia->CurrentValue;
						break;
					case $this->ic_tendencia->FldTagValue(3):
						$this->ic_tendencia->ViewValue = $this->ic_tendencia->FldTagCaption(3) <> "" ? $this->ic_tendencia->FldTagCaption(3) : $this->ic_tendencia->CurrentValue;
						break;
					case $this->ic_tendencia->FldTagValue(4):
						$this->ic_tendencia->ViewValue = $this->ic_tendencia->FldTagCaption(4) <> "" ? $this->ic_tendencia->FldTagCaption(4) : $this->ic_tendencia->CurrentValue;
						break;
					case $this->ic_tendencia->FldTagValue(5):
						$this->ic_tendencia->ViewValue = $this->ic_tendencia->FldTagCaption(5) <> "" ? $this->ic_tendencia->FldTagCaption(5) : $this->ic_tendencia->CurrentValue;
						break;
					default:
						$this->ic_tendencia->ViewValue = $this->ic_tendencia->CurrentValue;
				}
			} else {
				$this->ic_tendencia->ViewValue = NULL;
			}
			$this->ic_tendencia->ViewCustomAttributes = "";

			// ic_prioridade
			if (strval($this->ic_prioridade->CurrentValue) <> "") {
				switch ($this->ic_prioridade->CurrentValue) {
					case $this->ic_prioridade->FldTagValue(1):
						$this->ic_prioridade->ViewValue = $this->ic_prioridade->FldTagCaption(1) <> "" ? $this->ic_prioridade->FldTagCaption(1) : $this->ic_prioridade->CurrentValue;
						break;
					case $this->ic_prioridade->FldTagValue(2):
						$this->ic_prioridade->ViewValue = $this->ic_prioridade->FldTagCaption(2) <> "" ? $this->ic_prioridade->FldTagCaption(2) : $this->ic_prioridade->CurrentValue;
						break;
					case $this->ic_prioridade->FldTagValue(3):
						$this->ic_prioridade->ViewValue = $this->ic_prioridade->FldTagCaption(3) <> "" ? $this->ic_prioridade->FldTagCaption(3) : $this->ic_prioridade->CurrentValue;
						break;
					case $this->ic_prioridade->FldTagValue(4):
						$this->ic_prioridade->ViewValue = $this->ic_prioridade->FldTagCaption(4) <> "" ? $this->ic_prioridade->FldTagCaption(4) : $this->ic_prioridade->CurrentValue;
						break;
					case $this->ic_prioridade->FldTagValue(5):
						$this->ic_prioridade->ViewValue = $this->ic_prioridade->FldTagCaption(5) <> "" ? $this->ic_prioridade->FldTagCaption(5) : $this->ic_prioridade->CurrentValue;
						break;
					default:
						$this->ic_prioridade->ViewValue = $this->ic_prioridade->CurrentValue;
				}
			} else {
				$this->ic_prioridade->ViewValue = NULL;
			}
			$this->ic_prioridade->ViewCustomAttributes = "";

			// nu_necTi
			$this->nu_necTi->LinkCustomAttributes = "";
			$this->nu_necTi->HrefValue = "";
			$this->nu_necTi->TooltipValue = "";

			// nu_periodoPei
			$this->nu_periodoPei->LinkCustomAttributes = "";
			$this->nu_periodoPei->HrefValue = "";
			$this->nu_periodoPei->TooltipValue = "";

			// nu_periodoPdti
			$this->nu_periodoPdti->LinkCustomAttributes = "";
			$this->nu_periodoPdti->HrefValue = "";
			$this->nu_periodoPdti->TooltipValue = "";

			// nu_tpNecTi
			$this->nu_tpNecTi->LinkCustomAttributes = "";
			$this->nu_tpNecTi->HrefValue = "";
			$this->nu_tpNecTi->TooltipValue = "";

			// ic_tpNec
			$this->ic_tpNec->LinkCustomAttributes = "";
			$this->ic_tpNec->HrefValue = "";
			$this->ic_tpNec->TooltipValue = "";

			// nu_metaneg
			$this->nu_metaneg->LinkCustomAttributes = "";
			$this->nu_metaneg->HrefValue = "";
			$this->nu_metaneg->TooltipValue = "";

			// nu_origem
			$this->nu_origem->LinkCustomAttributes = "";
			$this->nu_origem->HrefValue = "";
			$this->nu_origem->TooltipValue = "";

			// nu_area
			$this->nu_area->LinkCustomAttributes = "";
			$this->nu_area->HrefValue = "";
			$this->nu_area->TooltipValue = "";

			// ic_gravidade
			$this->ic_gravidade->LinkCustomAttributes = "";
			$this->ic_gravidade->HrefValue = "";
			$this->ic_gravidade->TooltipValue = "";

			// ic_urgencia
			$this->ic_urgencia->LinkCustomAttributes = "";
			$this->ic_urgencia->HrefValue = "";
			$this->ic_urgencia->TooltipValue = "";

			// ic_tendencia
			$this->ic_tendencia->LinkCustomAttributes = "";
			$this->ic_tendencia->HrefValue = "";
			$this->ic_tendencia->TooltipValue = "";

			// ic_prioridade
			$this->ic_prioridade->LinkCustomAttributes = "";
			$this->ic_prioridade->HrefValue = "";
			$this->ic_prioridade->TooltipValue = "";
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
				$sThisKey .= $row['nu_necTi'];
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "nectilist.php", $this->TableVar);
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
if (!isset($necti_delete)) $necti_delete = new cnecti_delete();

// Page init
$necti_delete->Page_Init();

// Page main
$necti_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$necti_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var necti_delete = new ew_Page("necti_delete");
necti_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = necti_delete.PageID; // For backward compatibility

// Form object
var fnectidelete = new ew_Form("fnectidelete");

// Form_CustomValidate event
fnectidelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fnectidelete.ValidateRequired = true;
<?php } else { ?>
fnectidelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fnectidelete.Lists["x_nu_periodoPei"] = {"LinkField":"x_nu_periodoPei","Ajax":null,"AutoFill":false,"DisplayFields":["x_nu_anoInicio","x_nu_anoFim","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fnectidelete.Lists["x_nu_periodoPdti"] = {"LinkField":"x_nu_periodo","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_periodo","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fnectidelete.Lists["x_nu_tpNecTi"] = {"LinkField":"x_nu_tpNecTi","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpNecTi","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fnectidelete.Lists["x_nu_metaneg"] = {"LinkField":"x_nu_metaneg","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_metaneg","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fnectidelete.Lists["x_nu_origem"] = {"LinkField":"x_nu_origem","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_origem","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fnectidelete.Lists["x_nu_area"] = {"LinkField":"x_nu_area","Ajax":true,"AutoFill":false,"DisplayFields":["x_no_area","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($necti_delete->Recordset = $necti_delete->LoadRecordset())
	$necti_deleteTotalRecs = $necti_delete->Recordset->RecordCount(); // Get record count
if ($necti_deleteTotalRecs <= 0) { // No record found, exit
	if ($necti_delete->Recordset)
		$necti_delete->Recordset->Close();
	$necti_delete->Page_Terminate("nectilist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $necti_delete->ShowPageHeader(); ?>
<?php
$necti_delete->ShowMessage();
?>
<form name="fnectidelete" id="fnectidelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="necti">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($necti_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_nectidelete" class="ewTable ewTableSeparate">
<?php echo $necti->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_necti_nu_necTi" class="necti_nu_necTi"><?php echo $necti->nu_necTi->FldCaption() ?></span></td>
		<td><span id="elh_necti_nu_periodoPei" class="necti_nu_periodoPei"><?php echo $necti->nu_periodoPei->FldCaption() ?></span></td>
		<td><span id="elh_necti_nu_periodoPdti" class="necti_nu_periodoPdti"><?php echo $necti->nu_periodoPdti->FldCaption() ?></span></td>
		<td><span id="elh_necti_nu_tpNecTi" class="necti_nu_tpNecTi"><?php echo $necti->nu_tpNecTi->FldCaption() ?></span></td>
		<td><span id="elh_necti_ic_tpNec" class="necti_ic_tpNec"><?php echo $necti->ic_tpNec->FldCaption() ?></span></td>
		<td><span id="elh_necti_nu_metaneg" class="necti_nu_metaneg"><?php echo $necti->nu_metaneg->FldCaption() ?></span></td>
		<td><span id="elh_necti_nu_origem" class="necti_nu_origem"><?php echo $necti->nu_origem->FldCaption() ?></span></td>
		<td><span id="elh_necti_nu_area" class="necti_nu_area"><?php echo $necti->nu_area->FldCaption() ?></span></td>
		<td><span id="elh_necti_ic_gravidade" class="necti_ic_gravidade"><?php echo $necti->ic_gravidade->FldCaption() ?></span></td>
		<td><span id="elh_necti_ic_urgencia" class="necti_ic_urgencia"><?php echo $necti->ic_urgencia->FldCaption() ?></span></td>
		<td><span id="elh_necti_ic_tendencia" class="necti_ic_tendencia"><?php echo $necti->ic_tendencia->FldCaption() ?></span></td>
		<td><span id="elh_necti_ic_prioridade" class="necti_ic_prioridade"><?php echo $necti->ic_prioridade->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$necti_delete->RecCnt = 0;
$i = 0;
while (!$necti_delete->Recordset->EOF) {
	$necti_delete->RecCnt++;
	$necti_delete->RowCnt++;

	// Set row properties
	$necti->ResetAttrs();
	$necti->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$necti_delete->LoadRowValues($necti_delete->Recordset);

	// Render row
	$necti_delete->RenderRow();
?>
	<tr<?php echo $necti->RowAttributes() ?>>
		<td<?php echo $necti->nu_necTi->CellAttributes() ?>>
<span id="el<?php echo $necti_delete->RowCnt ?>_necti_nu_necTi" class="control-group necti_nu_necTi">
<span<?php echo $necti->nu_necTi->ViewAttributes() ?>>
<?php echo $necti->nu_necTi->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $necti->nu_periodoPei->CellAttributes() ?>>
<span id="el<?php echo $necti_delete->RowCnt ?>_necti_nu_periodoPei" class="control-group necti_nu_periodoPei">
<span<?php echo $necti->nu_periodoPei->ViewAttributes() ?>>
<?php echo $necti->nu_periodoPei->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $necti->nu_periodoPdti->CellAttributes() ?>>
<span id="el<?php echo $necti_delete->RowCnt ?>_necti_nu_periodoPdti" class="control-group necti_nu_periodoPdti">
<span<?php echo $necti->nu_periodoPdti->ViewAttributes() ?>>
<?php echo $necti->nu_periodoPdti->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $necti->nu_tpNecTi->CellAttributes() ?>>
<span id="el<?php echo $necti_delete->RowCnt ?>_necti_nu_tpNecTi" class="control-group necti_nu_tpNecTi">
<span<?php echo $necti->nu_tpNecTi->ViewAttributes() ?>>
<?php echo $necti->nu_tpNecTi->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $necti->ic_tpNec->CellAttributes() ?>>
<span id="el<?php echo $necti_delete->RowCnt ?>_necti_ic_tpNec" class="control-group necti_ic_tpNec">
<span<?php echo $necti->ic_tpNec->ViewAttributes() ?>>
<?php echo $necti->ic_tpNec->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $necti->nu_metaneg->CellAttributes() ?>>
<span id="el<?php echo $necti_delete->RowCnt ?>_necti_nu_metaneg" class="control-group necti_nu_metaneg">
<span<?php echo $necti->nu_metaneg->ViewAttributes() ?>>
<?php echo $necti->nu_metaneg->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $necti->nu_origem->CellAttributes() ?>>
<span id="el<?php echo $necti_delete->RowCnt ?>_necti_nu_origem" class="control-group necti_nu_origem">
<span<?php echo $necti->nu_origem->ViewAttributes() ?>>
<?php echo $necti->nu_origem->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $necti->nu_area->CellAttributes() ?>>
<span id="el<?php echo $necti_delete->RowCnt ?>_necti_nu_area" class="control-group necti_nu_area">
<span<?php echo $necti->nu_area->ViewAttributes() ?>>
<?php echo $necti->nu_area->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $necti->ic_gravidade->CellAttributes() ?>>
<span id="el<?php echo $necti_delete->RowCnt ?>_necti_ic_gravidade" class="control-group necti_ic_gravidade">
<span<?php echo $necti->ic_gravidade->ViewAttributes() ?>>
<?php echo $necti->ic_gravidade->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $necti->ic_urgencia->CellAttributes() ?>>
<span id="el<?php echo $necti_delete->RowCnt ?>_necti_ic_urgencia" class="control-group necti_ic_urgencia">
<span<?php echo $necti->ic_urgencia->ViewAttributes() ?>>
<?php echo $necti->ic_urgencia->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $necti->ic_tendencia->CellAttributes() ?>>
<span id="el<?php echo $necti_delete->RowCnt ?>_necti_ic_tendencia" class="control-group necti_ic_tendencia">
<span<?php echo $necti->ic_tendencia->ViewAttributes() ?>>
<?php echo $necti->ic_tendencia->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $necti->ic_prioridade->CellAttributes() ?>>
<span id="el<?php echo $necti_delete->RowCnt ?>_necti_ic_prioridade" class="control-group necti_ic_prioridade">
<span<?php echo $necti->ic_prioridade->ViewAttributes() ?>>
<?php echo $necti->ic_prioridade->ListViewValue() ?></span>
</span>
</td>
	</tr>
<?php
	$necti_delete->Recordset->MoveNext();
}
$necti_delete->Recordset->Close();
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
fnectidelete.Init();
</script>
<?php
$necti_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$necti_delete->Page_Terminate();
?>
