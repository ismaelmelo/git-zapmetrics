<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "ambienteinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$ambiente_addopt = NULL; // Initialize page object first

class cambiente_addopt extends cambiente {

	// Page ID
	var $PageID = 'addopt';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'ambiente';

	// Page object name
	var $PageObjName = 'ambiente_addopt';

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
	var $AuditTrailOnAdd = TRUE;

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

		// Table object (ambiente)
		if (!isset($GLOBALS["ambiente"])) {
			$GLOBALS["ambiente"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["ambiente"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'addopt', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'ambiente', TRUE);

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
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("ambientelist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();

		// Create form object
		$objForm = new cFormObj();
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

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		set_error_handler("ew_ErrorHandler");

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if ($objForm->GetValue("a_addopt") <> "") {
			$this->CurrentAction = $objForm->GetValue("a_addopt"); // Get form action
			$this->LoadFormValues(); // Load form values

			// Validate form
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->setFailureMessage($gsFormError);
			}
		} else { // Not post back
			$this->CurrentAction = "I"; // Display blank record
			$this->LoadDefaultValues(); // Load default values
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow()) { // Add successful
					$row = array();
					$row["x_nu_ambiente"] = $this->nu_ambiente->DbValue;
					$row["x_no_ambiente"] = $this->no_ambiente->DbValue;
					$row["x_ds_caracteristicas"] = $this->ds_caracteristicas->DbValue;
					$row["x_nu_tpNegocio"] = $this->nu_tpNegocio->DbValue;
					$row["x_nu_plataforma"] = $this->nu_plataforma->DbValue;
					$row["x_nu_tpSistema"] = $this->nu_tpSistema->DbValue;
					$row["x_nu_roteiro"] = $this->nu_roteiro->DbValue;
					$row["x_ic_ativo"] = $this->ic_ativo->DbValue;
					$row["x_nu_ordem"] = $this->nu_ordem->DbValue;
					if (!EW_DEBUG_ENABLED && ob_get_length())
						ob_end_clean();
					echo ew_ArrayToJson(array($row));
				} else {
					$this->ShowMessage();
				}
				$this->Page_Terminate();
				exit();
		}

		// Render row
		$this->RowType = EW_ROWTYPE_ADD; // Render add type
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->no_ambiente->CurrentValue = NULL;
		$this->no_ambiente->OldValue = $this->no_ambiente->CurrentValue;
		$this->ds_caracteristicas->CurrentValue = NULL;
		$this->ds_caracteristicas->OldValue = $this->ds_caracteristicas->CurrentValue;
		$this->nu_tpNegocio->CurrentValue = NULL;
		$this->nu_tpNegocio->OldValue = $this->nu_tpNegocio->CurrentValue;
		$this->nu_plataforma->CurrentValue = NULL;
		$this->nu_plataforma->OldValue = $this->nu_plataforma->CurrentValue;
		$this->nu_tpSistema->CurrentValue = NULL;
		$this->nu_tpSistema->OldValue = $this->nu_tpSistema->CurrentValue;
		$this->nu_roteiro->CurrentValue = NULL;
		$this->nu_roteiro->OldValue = $this->nu_roteiro->CurrentValue;
		$this->ic_ativo->CurrentValue = "S";
		$this->nu_ordem->CurrentValue = NULL;
		$this->nu_ordem->OldValue = $this->nu_ordem->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->no_ambiente->FldIsDetailKey) {
			$this->no_ambiente->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_no_ambiente")));
		}
		if (!$this->ds_caracteristicas->FldIsDetailKey) {
			$this->ds_caracteristicas->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_ds_caracteristicas")));
		}
		if (!$this->nu_tpNegocio->FldIsDetailKey) {
			$this->nu_tpNegocio->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_nu_tpNegocio")));
		}
		if (!$this->nu_plataforma->FldIsDetailKey) {
			$this->nu_plataforma->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_nu_plataforma")));
		}
		if (!$this->nu_tpSistema->FldIsDetailKey) {
			$this->nu_tpSistema->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_nu_tpSistema")));
		}
		if (!$this->nu_roteiro->FldIsDetailKey) {
			$this->nu_roteiro->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_nu_roteiro")));
		}
		if (!$this->ic_ativo->FldIsDetailKey) {
			$this->ic_ativo->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_ic_ativo")));
		}
		if (!$this->nu_ordem->FldIsDetailKey) {
			$this->nu_ordem->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_nu_ordem")));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->no_ambiente->CurrentValue = ew_ConvertToUtf8($this->no_ambiente->FormValue);
		$this->ds_caracteristicas->CurrentValue = ew_ConvertToUtf8($this->ds_caracteristicas->FormValue);
		$this->nu_tpNegocio->CurrentValue = ew_ConvertToUtf8($this->nu_tpNegocio->FormValue);
		$this->nu_plataforma->CurrentValue = ew_ConvertToUtf8($this->nu_plataforma->FormValue);
		$this->nu_tpSistema->CurrentValue = ew_ConvertToUtf8($this->nu_tpSistema->FormValue);
		$this->nu_roteiro->CurrentValue = ew_ConvertToUtf8($this->nu_roteiro->FormValue);
		$this->ic_ativo->CurrentValue = ew_ConvertToUtf8($this->ic_ativo->FormValue);
		$this->nu_ordem->CurrentValue = ew_ConvertToUtf8($this->nu_ordem->FormValue);
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
		$this->no_ambiente->setDbValue($rs->fields('no_ambiente'));
		$this->ds_caracteristicas->setDbValue($rs->fields('ds_caracteristicas'));
		$this->nu_tpNegocio->setDbValue($rs->fields('nu_tpNegocio'));
		$this->nu_plataforma->setDbValue($rs->fields('nu_plataforma'));
		$this->nu_tpSistema->setDbValue($rs->fields('nu_tpSistema'));
		$this->nu_roteiro->setDbValue($rs->fields('nu_roteiro'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
		$this->nu_ordem->setDbValue($rs->fields('nu_ordem'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_ambiente->DbValue = $row['nu_ambiente'];
		$this->no_ambiente->DbValue = $row['no_ambiente'];
		$this->ds_caracteristicas->DbValue = $row['ds_caracteristicas'];
		$this->nu_tpNegocio->DbValue = $row['nu_tpNegocio'];
		$this->nu_plataforma->DbValue = $row['nu_plataforma'];
		$this->nu_tpSistema->DbValue = $row['nu_tpSistema'];
		$this->nu_roteiro->DbValue = $row['nu_roteiro'];
		$this->ic_ativo->DbValue = $row['ic_ativo'];
		$this->nu_ordem->DbValue = $row['nu_ordem'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_ambiente
		// no_ambiente
		// ds_caracteristicas
		// nu_tpNegocio
		// nu_plataforma
		// nu_tpSistema
		// nu_roteiro
		// ic_ativo
		// nu_ordem

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// no_ambiente
			$this->no_ambiente->ViewValue = $this->no_ambiente->CurrentValue;
			$this->no_ambiente->ViewCustomAttributes = "";

			// ds_caracteristicas
			$this->ds_caracteristicas->ViewValue = $this->ds_caracteristicas->CurrentValue;
			$this->ds_caracteristicas->ViewCustomAttributes = "";

			// nu_tpNegocio
			if (strval($this->nu_tpNegocio->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpNegocio]" . ew_SearchString("=", $this->nu_tpNegocio->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpNegocio], [no_tpNegocio] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpnegocio]";
			$sWhereWrk = "";
			$lookuptblfilter = "[co_ativo] = 'S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpNegocio, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpNegocio->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpNegocio->ViewValue = $this->nu_tpNegocio->CurrentValue;
				}
			} else {
				$this->nu_tpNegocio->ViewValue = NULL;
			}
			$this->nu_tpNegocio->ViewCustomAttributes = "";

			// nu_plataforma
			if (strval($this->nu_plataforma->CurrentValue) <> "") {
				$sFilterWrk = "[nu_plataforma]" . ew_SearchString("=", $this->nu_plataforma->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_plataforma], [no_plataforma] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[plataforma]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_plataforma, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_plataforma->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_plataforma->ViewValue = $this->nu_plataforma->CurrentValue;
				}
			} else {
				$this->nu_plataforma->ViewValue = NULL;
			}
			$this->nu_plataforma->ViewCustomAttributes = "";

			// nu_tpSistema
			if (strval($this->nu_tpSistema->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpSistema]" . ew_SearchString("=", $this->nu_tpSistema->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpSistema], [no_tpSistema] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpsistema]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpSistema, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpSistema->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpSistema->ViewValue = $this->nu_tpSistema->CurrentValue;
				}
			} else {
				$this->nu_tpSistema->ViewValue = NULL;
			}
			$this->nu_tpSistema->ViewCustomAttributes = "";

			// nu_roteiro
			if (strval($this->nu_roteiro->CurrentValue) <> "") {
				$sFilterWrk = "[nu_roteiro]" . ew_SearchString("=", $this->nu_roteiro->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_roteiro], [no_roteiro] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[roteiro]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_roteiro, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
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

			// ic_ativo
			if (strval($this->ic_ativo->CurrentValue) <> "") {
				switch ($this->ic_ativo->CurrentValue) {
					case $this->ic_ativo->FldTagValue(1):
						$this->ic_ativo->ViewValue = $this->ic_ativo->FldTagCaption(1) <> "" ? $this->ic_ativo->FldTagCaption(1) : $this->ic_ativo->CurrentValue;
						break;
					case $this->ic_ativo->FldTagValue(2):
						$this->ic_ativo->ViewValue = $this->ic_ativo->FldTagCaption(2) <> "" ? $this->ic_ativo->FldTagCaption(2) : $this->ic_ativo->CurrentValue;
						break;
					default:
						$this->ic_ativo->ViewValue = $this->ic_ativo->CurrentValue;
				}
			} else {
				$this->ic_ativo->ViewValue = NULL;
			}
			$this->ic_ativo->ViewCustomAttributes = "";

			// nu_ordem
			$this->nu_ordem->ViewValue = $this->nu_ordem->CurrentValue;
			$this->nu_ordem->ViewCustomAttributes = "";

			// no_ambiente
			$this->no_ambiente->LinkCustomAttributes = "";
			$this->no_ambiente->HrefValue = "";
			$this->no_ambiente->TooltipValue = "";

			// ds_caracteristicas
			$this->ds_caracteristicas->LinkCustomAttributes = "";
			$this->ds_caracteristicas->HrefValue = "";
			$this->ds_caracteristicas->TooltipValue = "";

			// nu_tpNegocio
			$this->nu_tpNegocio->LinkCustomAttributes = "";
			$this->nu_tpNegocio->HrefValue = "";
			$this->nu_tpNegocio->TooltipValue = "";

			// nu_plataforma
			$this->nu_plataforma->LinkCustomAttributes = "";
			$this->nu_plataforma->HrefValue = "";
			$this->nu_plataforma->TooltipValue = "";

			// nu_tpSistema
			$this->nu_tpSistema->LinkCustomAttributes = "";
			$this->nu_tpSistema->HrefValue = "";
			$this->nu_tpSistema->TooltipValue = "";

			// nu_roteiro
			$this->nu_roteiro->LinkCustomAttributes = "";
			$this->nu_roteiro->HrefValue = "";
			$this->nu_roteiro->TooltipValue = "";

			// ic_ativo
			$this->ic_ativo->LinkCustomAttributes = "";
			$this->ic_ativo->HrefValue = "";
			$this->ic_ativo->TooltipValue = "";

			// nu_ordem
			$this->nu_ordem->LinkCustomAttributes = "";
			$this->nu_ordem->HrefValue = "";
			$this->nu_ordem->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// no_ambiente
			$this->no_ambiente->EditCustomAttributes = "";
			$this->no_ambiente->EditValue = ew_HtmlEncode($this->no_ambiente->CurrentValue);
			$this->no_ambiente->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_ambiente->FldCaption()));

			// ds_caracteristicas
			$this->ds_caracteristicas->EditCustomAttributes = "";
			$this->ds_caracteristicas->EditValue = $this->ds_caracteristicas->CurrentValue;
			$this->ds_caracteristicas->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_caracteristicas->FldCaption()));

			// nu_tpNegocio
			$this->nu_tpNegocio->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_tpNegocio], [no_tpNegocio] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[tpnegocio]";
			$sWhereWrk = "";
			$lookuptblfilter = "[co_ativo] = 'S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpNegocio, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_tpNegocio->EditValue = $arwrk;

			// nu_plataforma
			$this->nu_plataforma->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_plataforma], [no_plataforma] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[plataforma]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_plataforma, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_plataforma->EditValue = $arwrk;

			// nu_tpSistema
			$this->nu_tpSistema->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_tpSistema], [no_tpSistema] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[tpsistema]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpSistema, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_tpSistema->EditValue = $arwrk;

			// nu_roteiro
			$this->nu_roteiro->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_roteiro], [no_roteiro] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[roteiro]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_roteiro, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_roteiro->EditValue = $arwrk;

			// ic_ativo
			$this->ic_ativo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_ativo->FldTagValue(1), $this->ic_ativo->FldTagCaption(1) <> "" ? $this->ic_ativo->FldTagCaption(1) : $this->ic_ativo->FldTagValue(1));
			$arwrk[] = array($this->ic_ativo->FldTagValue(2), $this->ic_ativo->FldTagCaption(2) <> "" ? $this->ic_ativo->FldTagCaption(2) : $this->ic_ativo->FldTagValue(2));
			$this->ic_ativo->EditValue = $arwrk;

			// nu_ordem
			$this->nu_ordem->EditCustomAttributes = "";
			$this->nu_ordem->EditValue = ew_HtmlEncode($this->nu_ordem->CurrentValue);
			$this->nu_ordem->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_ordem->FldCaption()));

			// Edit refer script
			// no_ambiente

			$this->no_ambiente->HrefValue = "";

			// ds_caracteristicas
			$this->ds_caracteristicas->HrefValue = "";

			// nu_tpNegocio
			$this->nu_tpNegocio->HrefValue = "";

			// nu_plataforma
			$this->nu_plataforma->HrefValue = "";

			// nu_tpSistema
			$this->nu_tpSistema->HrefValue = "";

			// nu_roteiro
			$this->nu_roteiro->HrefValue = "";

			// ic_ativo
			$this->ic_ativo->HrefValue = "";

			// nu_ordem
			$this->nu_ordem->HrefValue = "";
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

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->no_ambiente->FldIsDetailKey && !is_null($this->no_ambiente->FormValue) && $this->no_ambiente->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_ambiente->FldCaption());
		}
		if (!$this->nu_tpNegocio->FldIsDetailKey && !is_null($this->nu_tpNegocio->FormValue) && $this->nu_tpNegocio->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_tpNegocio->FldCaption());
		}
		if (!$this->nu_plataforma->FldIsDetailKey && !is_null($this->nu_plataforma->FormValue) && $this->nu_plataforma->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_plataforma->FldCaption());
		}
		if (!$this->nu_tpSistema->FldIsDetailKey && !is_null($this->nu_tpSistema->FormValue) && $this->nu_tpSistema->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_tpSistema->FldCaption());
		}
		if (!$this->nu_roteiro->FldIsDetailKey && !is_null($this->nu_roteiro->FormValue) && $this->nu_roteiro->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_roteiro->FldCaption());
		}
		if ($this->ic_ativo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_ativo->FldCaption());
		}
		if (!ew_CheckInteger($this->nu_ordem->FormValue)) {
			ew_AddMessage($gsFormError, $this->nu_ordem->FldErrMsg());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// no_ambiente
		$this->no_ambiente->SetDbValueDef($rsnew, $this->no_ambiente->CurrentValue, "", FALSE);

		// ds_caracteristicas
		$this->ds_caracteristicas->SetDbValueDef($rsnew, $this->ds_caracteristicas->CurrentValue, NULL, FALSE);

		// nu_tpNegocio
		$this->nu_tpNegocio->SetDbValueDef($rsnew, $this->nu_tpNegocio->CurrentValue, 0, FALSE);

		// nu_plataforma
		$this->nu_plataforma->SetDbValueDef($rsnew, $this->nu_plataforma->CurrentValue, 0, FALSE);

		// nu_tpSistema
		$this->nu_tpSistema->SetDbValueDef($rsnew, $this->nu_tpSistema->CurrentValue, 0, FALSE);

		// nu_roteiro
		$this->nu_roteiro->SetDbValueDef($rsnew, $this->nu_roteiro->CurrentValue, 0, FALSE);

		// ic_ativo
		$this->ic_ativo->SetDbValueDef($rsnew, $this->ic_ativo->CurrentValue, "", FALSE);

		// nu_ordem
		$this->nu_ordem->SetDbValueDef($rsnew, $this->nu_ordem->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Get insert id if necessary
		if ($AddRow) {
			$this->nu_ambiente->setDbValue($conn->Insert_ID());
			$rsnew['nu_ambiente'] = $this->nu_ambiente->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
			$this->WriteAuditTrailOnAdd($rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "ambientelist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("addopt");
		$Breadcrumb->Add("addopt", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'ambiente';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'ambiente';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_ambiente'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
	  $usr = CurrentUserID();
		foreach (array_keys($rs) as $fldname) {
			if ($this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$newvalue = $rs[$fldname];
					else
						$newvalue = "[MEMO]"; // Memo Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$newvalue = "[XML]"; // XML Field
				} else {
					$newvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $usr, "A", $table, $fldname, $key, "", $newvalue);
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

	// Custom validate event
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
if (!isset($ambiente_addopt)) $ambiente_addopt = new cambiente_addopt();

// Page init
$ambiente_addopt->Page_Init();

// Page main
$ambiente_addopt->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$ambiente_addopt->Page_Render();
?>
<script type="text/javascript">

// Page object
var ambiente_addopt = new ew_Page("ambiente_addopt");
ambiente_addopt.PageID = "addopt"; // Page ID
var EW_PAGE_ID = ambiente_addopt.PageID; // For backward compatibility

// Form object
var fambienteaddopt = new ew_Form("fambienteaddopt");

// Validate form
fambienteaddopt.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	this.PostAutoSuggest();
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_no_ambiente");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ambiente->no_ambiente->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_tpNegocio");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ambiente->nu_tpNegocio->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_plataforma");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ambiente->nu_plataforma->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_tpSistema");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ambiente->nu_tpSistema->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_roteiro");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ambiente->nu_roteiro->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_ativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ambiente->ic_ativo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_ordem");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ambiente->nu_ordem->FldErrMsg()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
fambienteaddopt.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fambienteaddopt.ValidateRequired = true;
<?php } else { ?>
fambienteaddopt.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fambienteaddopt.Lists["x_nu_tpNegocio"] = {"LinkField":"x_nu_tpNegocio","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpNegocio","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambienteaddopt.Lists["x_nu_plataforma"] = {"LinkField":"x_nu_plataforma","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_plataforma","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambienteaddopt.Lists["x_nu_tpSistema"] = {"LinkField":"x_nu_tpSistema","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpSistema","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambienteaddopt.Lists["x_nu_roteiro"] = {"LinkField":"x_nu_roteiro","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_roteiro","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
$ambiente_addopt->ShowMessage();
?>
<form name="fambienteaddopt" id="fambienteaddopt" class="ewForm form-horizontal" action="ambienteaddopt.php" method="post">
<input type="hidden" name="t" value="ambiente">
<input type="hidden" name="a_addopt" id="a_addopt" value="A">
<div id="tbl_ambienteaddopt">
	<div class="control-group">
		<label class="control-label" for="x_no_ambiente"><?php echo $ambiente->no_ambiente->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="controls">
<input type="text" data-field="x_no_ambiente" name="x_no_ambiente" id="x_no_ambiente" size="30" maxlength="50" placeholder="<?php echo $ambiente->no_ambiente->PlaceHolder ?>" value="<?php echo $ambiente->no_ambiente->EditValue ?>"<?php echo $ambiente->no_ambiente->EditAttributes() ?>>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_ds_caracteristicas"><?php echo $ambiente->ds_caracteristicas->FldCaption() ?></label>
		<div class="controls">
<textarea data-field="x_ds_caracteristicas" name="x_ds_caracteristicas" id="x_ds_caracteristicas" cols="35" rows="4" placeholder="<?php echo $ambiente->ds_caracteristicas->PlaceHolder ?>"<?php echo $ambiente->ds_caracteristicas->EditAttributes() ?>><?php echo $ambiente->ds_caracteristicas->EditValue ?></textarea>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_nu_tpNegocio"><?php echo $ambiente->nu_tpNegocio->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="controls">
<select data-field="x_nu_tpNegocio" id="x_nu_tpNegocio" name="x_nu_tpNegocio"<?php echo $ambiente->nu_tpNegocio->EditAttributes() ?>>
<?php
if (is_array($ambiente->nu_tpNegocio->EditValue)) {
	$arwrk = $ambiente->nu_tpNegocio->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente->nu_tpNegocio->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fambienteaddopt.Lists["x_nu_tpNegocio"].Options = <?php echo (is_array($ambiente->nu_tpNegocio->EditValue)) ? ew_ArrayToJson($ambiente->nu_tpNegocio->EditValue, 1) : "[]" ?>;
</script>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_nu_plataforma"><?php echo $ambiente->nu_plataforma->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="controls">
<select data-field="x_nu_plataforma" id="x_nu_plataforma" name="x_nu_plataforma"<?php echo $ambiente->nu_plataforma->EditAttributes() ?>>
<?php
if (is_array($ambiente->nu_plataforma->EditValue)) {
	$arwrk = $ambiente->nu_plataforma->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente->nu_plataforma->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fambienteaddopt.Lists["x_nu_plataforma"].Options = <?php echo (is_array($ambiente->nu_plataforma->EditValue)) ? ew_ArrayToJson($ambiente->nu_plataforma->EditValue, 1) : "[]" ?>;
</script>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_nu_tpSistema"><?php echo $ambiente->nu_tpSistema->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="controls">
<select data-field="x_nu_tpSistema" id="x_nu_tpSistema" name="x_nu_tpSistema"<?php echo $ambiente->nu_tpSistema->EditAttributes() ?>>
<?php
if (is_array($ambiente->nu_tpSistema->EditValue)) {
	$arwrk = $ambiente->nu_tpSistema->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente->nu_tpSistema->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fambienteaddopt.Lists["x_nu_tpSistema"].Options = <?php echo (is_array($ambiente->nu_tpSistema->EditValue)) ? ew_ArrayToJson($ambiente->nu_tpSistema->EditValue, 1) : "[]" ?>;
</script>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_nu_roteiro"><?php echo $ambiente->nu_roteiro->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="controls">
<select data-field="x_nu_roteiro" id="x_nu_roteiro" name="x_nu_roteiro"<?php echo $ambiente->nu_roteiro->EditAttributes() ?>>
<?php
if (is_array($ambiente->nu_roteiro->EditValue)) {
	$arwrk = $ambiente->nu_roteiro->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente->nu_roteiro->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fambienteaddopt.Lists["x_nu_roteiro"].Options = <?php echo (is_array($ambiente->nu_roteiro->EditValue)) ? ew_ArrayToJson($ambiente->nu_roteiro->EditValue, 1) : "[]" ?>;
</script>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_ic_ativo"><?php echo $ambiente->ic_ativo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="controls">
<div id="tp_x_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_ativo" id="x_ic_ativo" value="{value}"<?php echo $ambiente->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $ambiente->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x_ic_ativo" id="x_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $ambiente->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_nu_ordem"><?php echo $ambiente->nu_ordem->FldCaption() ?></label>
		<div class="controls">
<input type="text" data-field="x_nu_ordem" name="x_nu_ordem" id="x_nu_ordem" size="30" placeholder="<?php echo $ambiente->nu_ordem->PlaceHolder ?>" value="<?php echo $ambiente->nu_ordem->EditValue ?>"<?php echo $ambiente->nu_ordem->EditAttributes() ?>>
</div>
	</div>
</div>
</form>
<script type="text/javascript">
fambienteaddopt.Init();
</script>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php
$ambiente_addopt->Page_Terminate();
?>
