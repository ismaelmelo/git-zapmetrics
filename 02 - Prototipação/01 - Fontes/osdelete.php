<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "osinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$os_delete = NULL; // Initialize page object first

class cos_delete extends cos {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'os';

	// Page object name
	var $PageObjName = 'os_delete';

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

		// Table object (os)
		if (!isset($GLOBALS["os"])) {
			$GLOBALS["os"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["os"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'os', TRUE);

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
			$this->Page_Terminate("oslist.php");
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
			$this->Page_Terminate("oslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in os class, osinfo.php

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
		$this->nu_os->setDbValue($rs->fields('nu_os'));
		$this->co_os->setDbValue($rs->fields('co_os'));
		$this->no_titulo->setDbValue($rs->fields('no_titulo'));
		$this->nu_contrato->setDbValue($rs->fields('nu_contrato'));
		$this->nu_itemContratado->setDbValue($rs->fields('nu_itemContratado'));
		$this->nu_areaSolicitante->setDbValue($rs->fields('nu_areaSolicitante'));
		$this->nu_projeto->setDbValue($rs->fields('nu_projeto'));
		if (array_key_exists('EV__nu_projeto', $rs->fields)) {
			$this->nu_projeto->VirtualValue = $rs->fields('EV__nu_projeto'); // Set up virtual field value
		} else {
			$this->nu_projeto->VirtualValue = ""; // Clear value
		}
		$this->dt_criacaoOs->setDbValue($rs->fields('dt_criacaoOs'));
		$this->dt_entrega->setDbValue($rs->fields('dt_entrega'));
		$this->nu_stOs->setDbValue($rs->fields('nu_stOs'));
		$this->dt_stOs->setDbValue($rs->fields('dt_stOs'));
		$this->nu_usuarioAnalista->setDbValue($rs->fields('nu_usuarioAnalista'));
		$this->ds_observacoes->setDbValue($rs->fields('ds_observacoes'));
		$this->vr_os->setDbValue($rs->fields('vr_os'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_os->DbValue = $row['nu_os'];
		$this->co_os->DbValue = $row['co_os'];
		$this->no_titulo->DbValue = $row['no_titulo'];
		$this->nu_contrato->DbValue = $row['nu_contrato'];
		$this->nu_itemContratado->DbValue = $row['nu_itemContratado'];
		$this->nu_areaSolicitante->DbValue = $row['nu_areaSolicitante'];
		$this->nu_projeto->DbValue = $row['nu_projeto'];
		$this->dt_criacaoOs->DbValue = $row['dt_criacaoOs'];
		$this->dt_entrega->DbValue = $row['dt_entrega'];
		$this->nu_stOs->DbValue = $row['nu_stOs'];
		$this->dt_stOs->DbValue = $row['dt_stOs'];
		$this->nu_usuarioAnalista->DbValue = $row['nu_usuarioAnalista'];
		$this->ds_observacoes->DbValue = $row['ds_observacoes'];
		$this->vr_os->DbValue = $row['vr_os'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->vr_os->FormValue == $this->vr_os->CurrentValue && is_numeric(ew_StrToFloat($this->vr_os->CurrentValue)))
			$this->vr_os->CurrentValue = ew_StrToFloat($this->vr_os->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_os

		$this->nu_os->CellCssStyle = "white-space: nowrap;";

		// co_os
		// no_titulo
		// nu_contrato
		// nu_itemContratado
		// nu_areaSolicitante
		// nu_projeto
		// dt_criacaoOs
		// dt_entrega
		// nu_stOs
		// dt_stOs
		// nu_usuarioAnalista
		// ds_observacoes
		// vr_os

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_os
			$this->nu_os->ViewValue = $this->nu_os->CurrentValue;
			$this->nu_os->ViewCustomAttributes = "";

			// co_os
			$this->co_os->ViewValue = $this->co_os->CurrentValue;
			$this->co_os->ViewValue = ew_FormatNumber($this->co_os->ViewValue, 0, 0, 0, 0);
			$this->co_os->ViewCustomAttributes = "";

			// no_titulo
			$this->no_titulo->ViewValue = $this->no_titulo->CurrentValue;
			$this->no_titulo->ViewCustomAttributes = "";

			// nu_contrato
			if (strval($this->nu_contrato->CurrentValue) <> "") {
				$sFilterWrk = "[nu_contrato]" . ew_SearchString("=", $this->nu_contrato->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_contrato], [nu_contrato] AS [DispFld], [no_contrato] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[contrato]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_contrato, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_contrato] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_contrato->ViewValue = $rswrk->fields('DispFld');
					$this->nu_contrato->ViewValue .= ew_ValueSeparator(1,$this->nu_contrato) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_contrato->ViewValue = $this->nu_contrato->CurrentValue;
				}
			} else {
				$this->nu_contrato->ViewValue = NULL;
			}
			$this->nu_contrato->ViewCustomAttributes = "";

			// nu_itemContratado
			if (strval($this->nu_itemContratado->CurrentValue) <> "") {
				$sFilterWrk = "[nu_itemContratado]" . ew_SearchString("=", $this->nu_itemContratado->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_itemContratado], [nu_itemOc] AS [DispFld], [no_itemContratado] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[item_contratado]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_itemContratado, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_itemContratado->ViewValue = $rswrk->fields('DispFld');
					$this->nu_itemContratado->ViewValue .= ew_ValueSeparator(1,$this->nu_itemContratado) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_itemContratado->ViewValue = $this->nu_itemContratado->CurrentValue;
				}
			} else {
				$this->nu_itemContratado->ViewValue = NULL;
			}
			$this->nu_itemContratado->ViewCustomAttributes = "";

			// nu_areaSolicitante
			if (strval($this->nu_areaSolicitante->CurrentValue) <> "") {
				$sFilterWrk = "[nu_area]" . ew_SearchString("=", $this->nu_areaSolicitante->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_area], [no_area] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[area]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_areaSolicitante, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_area] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_areaSolicitante->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_areaSolicitante->ViewValue = $this->nu_areaSolicitante->CurrentValue;
				}
			} else {
				$this->nu_areaSolicitante->ViewValue = NULL;
			}
			$this->nu_areaSolicitante->ViewCustomAttributes = "";

			// nu_projeto
			if ($this->nu_projeto->VirtualValue <> "") {
				$this->nu_projeto->ViewValue = $this->nu_projeto->VirtualValue;
			} else {
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
			}
			$this->nu_projeto->ViewCustomAttributes = "";

			// dt_criacaoOs
			$this->dt_criacaoOs->ViewValue = $this->dt_criacaoOs->CurrentValue;
			$this->dt_criacaoOs->ViewValue = ew_FormatDateTime($this->dt_criacaoOs->ViewValue, 7);
			$this->dt_criacaoOs->ViewCustomAttributes = "";

			// dt_entrega
			$this->dt_entrega->ViewValue = $this->dt_entrega->CurrentValue;
			$this->dt_entrega->ViewValue = ew_FormatDateTime($this->dt_entrega->ViewValue, 7);
			$this->dt_entrega->ViewCustomAttributes = "";

			// nu_stOs
			if (strval($this->nu_stOs->CurrentValue) <> "") {
				$sFilterWrk = "[nu_stOs]" . ew_SearchString("=", $this->nu_stOs->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_stOs], [no_stUc] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[stos]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_stOs, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_stUc] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_stOs->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_stOs->ViewValue = $this->nu_stOs->CurrentValue;
				}
			} else {
				$this->nu_stOs->ViewValue = NULL;
			}
			$this->nu_stOs->ViewCustomAttributes = "";

			// dt_stOs
			$this->dt_stOs->ViewValue = $this->dt_stOs->CurrentValue;
			$this->dt_stOs->ViewValue = ew_FormatDateTime($this->dt_stOs->ViewValue, 7);
			$this->dt_stOs->ViewCustomAttributes = "";

			// nu_usuarioAnalista
			if (strval($this->nu_usuarioAnalista->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioAnalista->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioAnalista, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioAnalista->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioAnalista->ViewValue = $this->nu_usuarioAnalista->CurrentValue;
				}
			} else {
				$this->nu_usuarioAnalista->ViewValue = NULL;
			}
			$this->nu_usuarioAnalista->ViewCustomAttributes = "";

			// vr_os
			$this->vr_os->ViewValue = $this->vr_os->CurrentValue;
			$this->vr_os->ViewValue = ew_FormatCurrency($this->vr_os->ViewValue, 2, -2, -2, -2);
			$this->vr_os->ViewCustomAttributes = "";

			// co_os
			$this->co_os->LinkCustomAttributes = "";
			$this->co_os->HrefValue = "";
			$this->co_os->TooltipValue = "";

			// no_titulo
			$this->no_titulo->LinkCustomAttributes = "";
			$this->no_titulo->HrefValue = "";
			$this->no_titulo->TooltipValue = "";

			// nu_areaSolicitante
			$this->nu_areaSolicitante->LinkCustomAttributes = "";
			$this->nu_areaSolicitante->HrefValue = "";
			$this->nu_areaSolicitante->TooltipValue = "";

			// nu_stOs
			$this->nu_stOs->LinkCustomAttributes = "";
			$this->nu_stOs->HrefValue = "";
			$this->nu_stOs->TooltipValue = "";

			// nu_usuarioAnalista
			$this->nu_usuarioAnalista->LinkCustomAttributes = "";
			$this->nu_usuarioAnalista->HrefValue = "";
			$this->nu_usuarioAnalista->TooltipValue = "";

			// vr_os
			$this->vr_os->LinkCustomAttributes = "";
			$this->vr_os->HrefValue = "";
			$this->vr_os->TooltipValue = "";
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
				$sThisKey .= $row['nu_os'];
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "oslist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("delete");
		$Breadcrumb->Add("delete", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'os';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (delete page)
	function WriteAuditTrailOnDelete(&$rs) {
		if (!$this->AuditTrailOnDelete) return;
		$table = 'os';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_os'];

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
if (!isset($os_delete)) $os_delete = new cos_delete();

// Page init
$os_delete->Page_Init();

// Page main
$os_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$os_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var os_delete = new ew_Page("os_delete");
os_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = os_delete.PageID; // For backward compatibility

// Form object
var fosdelete = new ew_Form("fosdelete");

// Form_CustomValidate event
fosdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fosdelete.ValidateRequired = true;
<?php } else { ?>
fosdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fosdelete.Lists["x_nu_areaSolicitante"] = {"LinkField":"x_nu_area","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_area","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fosdelete.Lists["x_nu_stOs"] = {"LinkField":"x_nu_stOs","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_stUc","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fosdelete.Lists["x_nu_usuarioAnalista"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($os_delete->Recordset = $os_delete->LoadRecordset())
	$os_deleteTotalRecs = $os_delete->Recordset->RecordCount(); // Get record count
if ($os_deleteTotalRecs <= 0) { // No record found, exit
	if ($os_delete->Recordset)
		$os_delete->Recordset->Close();
	$os_delete->Page_Terminate("oslist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $os_delete->ShowPageHeader(); ?>
<?php
$os_delete->ShowMessage();
?>
<form name="fosdelete" id="fosdelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="os">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($os_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_osdelete" class="ewTable ewTableSeparate">
<?php echo $os->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_os_co_os" class="os_co_os"><?php echo $os->co_os->FldCaption() ?></span></td>
		<td><span id="elh_os_no_titulo" class="os_no_titulo"><?php echo $os->no_titulo->FldCaption() ?></span></td>
		<td><span id="elh_os_nu_areaSolicitante" class="os_nu_areaSolicitante"><?php echo $os->nu_areaSolicitante->FldCaption() ?></span></td>
		<td><span id="elh_os_nu_stOs" class="os_nu_stOs"><?php echo $os->nu_stOs->FldCaption() ?></span></td>
		<td><span id="elh_os_nu_usuarioAnalista" class="os_nu_usuarioAnalista"><?php echo $os->nu_usuarioAnalista->FldCaption() ?></span></td>
		<td><span id="elh_os_vr_os" class="os_vr_os"><?php echo $os->vr_os->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$os_delete->RecCnt = 0;
$i = 0;
while (!$os_delete->Recordset->EOF) {
	$os_delete->RecCnt++;
	$os_delete->RowCnt++;

	// Set row properties
	$os->ResetAttrs();
	$os->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$os_delete->LoadRowValues($os_delete->Recordset);

	// Render row
	$os_delete->RenderRow();
?>
	<tr<?php echo $os->RowAttributes() ?>>
		<td<?php echo $os->co_os->CellAttributes() ?>>
<span id="el<?php echo $os_delete->RowCnt ?>_os_co_os" class="control-group os_co_os">
<span<?php echo $os->co_os->ViewAttributes() ?>>
<?php echo $os->co_os->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $os->no_titulo->CellAttributes() ?>>
<span id="el<?php echo $os_delete->RowCnt ?>_os_no_titulo" class="control-group os_no_titulo">
<span<?php echo $os->no_titulo->ViewAttributes() ?>>
<?php echo $os->no_titulo->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $os->nu_areaSolicitante->CellAttributes() ?>>
<span id="el<?php echo $os_delete->RowCnt ?>_os_nu_areaSolicitante" class="control-group os_nu_areaSolicitante">
<span<?php echo $os->nu_areaSolicitante->ViewAttributes() ?>>
<?php echo $os->nu_areaSolicitante->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $os->nu_stOs->CellAttributes() ?>>
<span id="el<?php echo $os_delete->RowCnt ?>_os_nu_stOs" class="control-group os_nu_stOs">
<span<?php echo $os->nu_stOs->ViewAttributes() ?>>
<?php echo $os->nu_stOs->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $os->nu_usuarioAnalista->CellAttributes() ?>>
<span id="el<?php echo $os_delete->RowCnt ?>_os_nu_usuarioAnalista" class="control-group os_nu_usuarioAnalista">
<span<?php echo $os->nu_usuarioAnalista->ViewAttributes() ?>>
<?php echo $os->nu_usuarioAnalista->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $os->vr_os->CellAttributes() ?>>
<span id="el<?php echo $os_delete->RowCnt ?>_os_vr_os" class="control-group os_vr_os">
<span<?php echo $os->vr_os->ViewAttributes() ?>>
<?php echo $os->vr_os->ListViewValue() ?></span>
</span>
</td>
	</tr>
<?php
	$os_delete->Recordset->MoveNext();
}
$os_delete->Recordset->Close();
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
fosdelete.Init();
</script>
<?php
$os_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$os_delete->Page_Terminate();
?>
