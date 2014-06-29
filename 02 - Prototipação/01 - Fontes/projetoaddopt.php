<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "projetoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$projeto_addopt = NULL; // Initialize page object first

class cprojeto_addopt extends cprojeto {

	// Page ID
	var $PageID = 'addopt';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'projeto';

	// Page object name
	var $PageObjName = 'projeto_addopt';

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

		// Table object (projeto)
		if (!isset($GLOBALS["projeto"])) {
			$GLOBALS["projeto"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["projeto"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'addopt', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'projeto', TRUE);

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
			$this->Page_Terminate("projetolist.php");
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
					$row["x_nu_projeto"] = $this->nu_projeto->DbValue;
					$row["x_nu_contrato"] = $this->nu_contrato->DbValue;
					$row["x_nu_itemContrato"] = $this->nu_itemContrato->DbValue;
					$row["x_nu_prospecto"] = $this->nu_prospecto->DbValue;
					$row["x_nu_tpProjeto"] = $this->nu_tpProjeto->DbValue;
					$row["x_nu_projetoInteg"] = $this->nu_projetoInteg->DbValue;
					$row["x_no_projeto"] = $this->no_projeto->DbValue;
					$row["x_id_tarefaTpProj"] = $this->id_tarefaTpProj->DbValue;
					$row["x_ic_complexProjeto"] = $this->ic_complexProjeto->DbValue;
					$row["x_ic_passivelContPf"] = $this->ic_passivelContPf->DbValue;
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
		$this->nu_contrato->CurrentValue = NULL;
		$this->nu_contrato->OldValue = $this->nu_contrato->CurrentValue;
		$this->nu_itemContrato->CurrentValue = NULL;
		$this->nu_itemContrato->OldValue = $this->nu_itemContrato->CurrentValue;
		$this->nu_prospecto->CurrentValue = NULL;
		$this->nu_prospecto->OldValue = $this->nu_prospecto->CurrentValue;
		$this->nu_tpProjeto->CurrentValue = NULL;
		$this->nu_tpProjeto->OldValue = $this->nu_tpProjeto->CurrentValue;
		$this->nu_projetoInteg->CurrentValue = NULL;
		$this->nu_projetoInteg->OldValue = $this->nu_projetoInteg->CurrentValue;
		$this->no_projeto->CurrentValue = NULL;
		$this->no_projeto->OldValue = $this->no_projeto->CurrentValue;
		$this->id_tarefaTpProj->CurrentValue = NULL;
		$this->id_tarefaTpProj->OldValue = $this->id_tarefaTpProj->CurrentValue;
		$this->ic_complexProjeto->CurrentValue = "B";
		$this->ic_passivelContPf->CurrentValue = "S";
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_contrato->FldIsDetailKey) {
			$this->nu_contrato->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_nu_contrato")));
		}
		if (!$this->nu_itemContrato->FldIsDetailKey) {
			$this->nu_itemContrato->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_nu_itemContrato")));
		}
		if (!$this->nu_prospecto->FldIsDetailKey) {
			$this->nu_prospecto->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_nu_prospecto")));
		}
		if (!$this->nu_tpProjeto->FldIsDetailKey) {
			$this->nu_tpProjeto->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_nu_tpProjeto")));
		}
		if (!$this->nu_projetoInteg->FldIsDetailKey) {
			$this->nu_projetoInteg->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_nu_projetoInteg")));
		}
		if (!$this->no_projeto->FldIsDetailKey) {
			$this->no_projeto->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_no_projeto")));
		}
		if (!$this->id_tarefaTpProj->FldIsDetailKey) {
			$this->id_tarefaTpProj->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_id_tarefaTpProj")));
		}
		if (!$this->ic_complexProjeto->FldIsDetailKey) {
			$this->ic_complexProjeto->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_ic_complexProjeto")));
		}
		if (!$this->ic_passivelContPf->FldIsDetailKey) {
			$this->ic_passivelContPf->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_ic_passivelContPf")));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->nu_contrato->CurrentValue = ew_ConvertToUtf8($this->nu_contrato->FormValue);
		$this->nu_itemContrato->CurrentValue = ew_ConvertToUtf8($this->nu_itemContrato->FormValue);
		$this->nu_prospecto->CurrentValue = ew_ConvertToUtf8($this->nu_prospecto->FormValue);
		$this->nu_tpProjeto->CurrentValue = ew_ConvertToUtf8($this->nu_tpProjeto->FormValue);
		$this->nu_projetoInteg->CurrentValue = ew_ConvertToUtf8($this->nu_projetoInteg->FormValue);
		$this->no_projeto->CurrentValue = ew_ConvertToUtf8($this->no_projeto->FormValue);
		$this->id_tarefaTpProj->CurrentValue = ew_ConvertToUtf8($this->id_tarefaTpProj->FormValue);
		$this->ic_complexProjeto->CurrentValue = ew_ConvertToUtf8($this->ic_complexProjeto->FormValue);
		$this->ic_passivelContPf->CurrentValue = ew_ConvertToUtf8($this->ic_passivelContPf->FormValue);
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
		$this->nu_projeto->setDbValue($rs->fields('nu_projeto'));
		$this->nu_contrato->setDbValue($rs->fields('nu_contrato'));
		if (array_key_exists('EV__nu_contrato', $rs->fields)) {
			$this->nu_contrato->VirtualValue = $rs->fields('EV__nu_contrato'); // Set up virtual field value
		} else {
			$this->nu_contrato->VirtualValue = ""; // Clear value
		}
		$this->nu_itemContrato->setDbValue($rs->fields('nu_itemContrato'));
		if (array_key_exists('EV__nu_itemContrato', $rs->fields)) {
			$this->nu_itemContrato->VirtualValue = $rs->fields('EV__nu_itemContrato'); // Set up virtual field value
		} else {
			$this->nu_itemContrato->VirtualValue = ""; // Clear value
		}
		$this->nu_prospecto->setDbValue($rs->fields('nu_prospecto'));
		$this->nu_tpProjeto->setDbValue($rs->fields('nu_tpProjeto'));
		$this->nu_projetoInteg->setDbValue($rs->fields('nu_projetoInteg'));
		$this->no_projeto->setDbValue($rs->fields('no_projeto'));
		$this->id_tarefaTpProj->setDbValue($rs->fields('id_tarefaTpProj'));
		$this->ic_complexProjeto->setDbValue($rs->fields('ic_complexProjeto'));
		$this->ic_passivelContPf->setDbValue($rs->fields('ic_passivelContPf'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_projeto->DbValue = $row['nu_projeto'];
		$this->nu_contrato->DbValue = $row['nu_contrato'];
		$this->nu_itemContrato->DbValue = $row['nu_itemContrato'];
		$this->nu_prospecto->DbValue = $row['nu_prospecto'];
		$this->nu_tpProjeto->DbValue = $row['nu_tpProjeto'];
		$this->nu_projetoInteg->DbValue = $row['nu_projetoInteg'];
		$this->no_projeto->DbValue = $row['no_projeto'];
		$this->id_tarefaTpProj->DbValue = $row['id_tarefaTpProj'];
		$this->ic_complexProjeto->DbValue = $row['ic_complexProjeto'];
		$this->ic_passivelContPf->DbValue = $row['ic_passivelContPf'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_projeto
		// nu_contrato
		// nu_itemContrato
		// nu_prospecto
		// nu_tpProjeto
		// nu_projetoInteg
		// no_projeto
		// id_tarefaTpProj
		// ic_complexProjeto
		// ic_passivelContPf

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_projeto
			$this->nu_projeto->ViewValue = $this->nu_projeto->CurrentValue;
			$this->nu_projeto->ViewCustomAttributes = "";

			// nu_contrato
			if ($this->nu_contrato->VirtualValue <> "") {
				$this->nu_contrato->ViewValue = $this->nu_contrato->VirtualValue;
			} else {
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
			$sSqlWrk .= " ORDER BY [no_contrato] ASC";
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
			}
			$this->nu_contrato->ViewCustomAttributes = "";

			// nu_itemContrato
			if ($this->nu_itemContrato->VirtualValue <> "") {
				$this->nu_itemContrato->ViewValue = $this->nu_itemContrato->VirtualValue;
			} else {
			if (strval($this->nu_itemContrato->CurrentValue) <> "") {
				$sFilterWrk = "[nu_itemContratado]" . ew_SearchString("=", $this->nu_itemContrato->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_itemContratado], [nu_itemOc] AS [DispFld], [no_itemContratado] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[item_contratado]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_itemContrato, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_itemContrato->ViewValue = $rswrk->fields('DispFld');
					$this->nu_itemContrato->ViewValue .= ew_ValueSeparator(1,$this->nu_itemContrato) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_itemContrato->ViewValue = $this->nu_itemContrato->CurrentValue;
				}
			} else {
				$this->nu_itemContrato->ViewValue = NULL;
			}
			}
			$this->nu_itemContrato->ViewCustomAttributes = "";

			// nu_prospecto
			if (strval($this->nu_prospecto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_prospecto]" . ew_SearchString("=", $this->nu_prospecto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_prospecto], [no_prospecto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[prospecto]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_prospecto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_prospecto] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_prospecto->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_prospecto->ViewValue = $this->nu_prospecto->CurrentValue;
				}
			} else {
				$this->nu_prospecto->ViewValue = NULL;
			}
			$this->nu_prospecto->ViewCustomAttributes = "";

			// nu_tpProjeto
			if (strval($this->nu_tpProjeto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpProjeto]" . ew_SearchString("=", $this->nu_tpProjeto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpProjeto], [no_tpProjeto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpprojeto]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S' AND ([ic_tpProjDem]='P' OR [ic_tpProjDem]='D')";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpProjeto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpProjeto] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpProjeto->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpProjeto->ViewValue = $this->nu_tpProjeto->CurrentValue;
				}
			} else {
				$this->nu_tpProjeto->ViewValue = NULL;
			}
			$this->nu_tpProjeto->ViewCustomAttributes = "";

			// nu_projetoInteg
			if (strval($this->nu_projetoInteg->CurrentValue) <> "") {
				$sFilterWrk = "[id]" . ew_SearchString("=", $this->nu_projetoInteg->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tbrdm_projects]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_projetoInteg, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [created_on] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_projetoInteg->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_projetoInteg->ViewValue = $this->nu_projetoInteg->CurrentValue;
				}
			} else {
				$this->nu_projetoInteg->ViewValue = NULL;
			}
			$this->nu_projetoInteg->ViewCustomAttributes = "";

			// no_projeto
			$this->no_projeto->ViewValue = $this->no_projeto->CurrentValue;
			$this->no_projeto->ViewCustomAttributes = "";

			// id_tarefaTpProj
			$this->id_tarefaTpProj->ViewValue = $this->id_tarefaTpProj->CurrentValue;
			if (strval($this->id_tarefaTpProj->CurrentValue) <> "") {
				$sFilterWrk = "[id]" . ew_SearchString("=", $this->id_tarefaTpProj->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [id], [subject] AS [DispFld], [id] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tbrdm_issues]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_tarefaTpProj, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [id] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_tarefaTpProj->ViewValue = $rswrk->fields('DispFld');
					$this->id_tarefaTpProj->ViewValue .= ew_ValueSeparator(1,$this->id_tarefaTpProj) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->id_tarefaTpProj->ViewValue = $this->id_tarefaTpProj->CurrentValue;
				}
			} else {
				$this->id_tarefaTpProj->ViewValue = NULL;
			}
			$this->id_tarefaTpProj->ViewCustomAttributes = "";

			// ic_complexProjeto
			if (strval($this->ic_complexProjeto->CurrentValue) <> "") {
				switch ($this->ic_complexProjeto->CurrentValue) {
					case $this->ic_complexProjeto->FldTagValue(1):
						$this->ic_complexProjeto->ViewValue = $this->ic_complexProjeto->FldTagCaption(1) <> "" ? $this->ic_complexProjeto->FldTagCaption(1) : $this->ic_complexProjeto->CurrentValue;
						break;
					case $this->ic_complexProjeto->FldTagValue(2):
						$this->ic_complexProjeto->ViewValue = $this->ic_complexProjeto->FldTagCaption(2) <> "" ? $this->ic_complexProjeto->FldTagCaption(2) : $this->ic_complexProjeto->CurrentValue;
						break;
					case $this->ic_complexProjeto->FldTagValue(3):
						$this->ic_complexProjeto->ViewValue = $this->ic_complexProjeto->FldTagCaption(3) <> "" ? $this->ic_complexProjeto->FldTagCaption(3) : $this->ic_complexProjeto->CurrentValue;
						break;
					default:
						$this->ic_complexProjeto->ViewValue = $this->ic_complexProjeto->CurrentValue;
				}
			} else {
				$this->ic_complexProjeto->ViewValue = NULL;
			}
			$this->ic_complexProjeto->ViewCustomAttributes = "";

			// ic_passivelContPf
			if (strval($this->ic_passivelContPf->CurrentValue) <> "") {
				switch ($this->ic_passivelContPf->CurrentValue) {
					case $this->ic_passivelContPf->FldTagValue(1):
						$this->ic_passivelContPf->ViewValue = $this->ic_passivelContPf->FldTagCaption(1) <> "" ? $this->ic_passivelContPf->FldTagCaption(1) : $this->ic_passivelContPf->CurrentValue;
						break;
					case $this->ic_passivelContPf->FldTagValue(2):
						$this->ic_passivelContPf->ViewValue = $this->ic_passivelContPf->FldTagCaption(2) <> "" ? $this->ic_passivelContPf->FldTagCaption(2) : $this->ic_passivelContPf->CurrentValue;
						break;
					default:
						$this->ic_passivelContPf->ViewValue = $this->ic_passivelContPf->CurrentValue;
				}
			} else {
				$this->ic_passivelContPf->ViewValue = NULL;
			}
			$this->ic_passivelContPf->ViewCustomAttributes = "";

			// nu_contrato
			$this->nu_contrato->LinkCustomAttributes = "";
			$this->nu_contrato->HrefValue = "";
			$this->nu_contrato->TooltipValue = "";

			// nu_itemContrato
			$this->nu_itemContrato->LinkCustomAttributes = "";
			$this->nu_itemContrato->HrefValue = "";
			$this->nu_itemContrato->TooltipValue = "";

			// nu_prospecto
			$this->nu_prospecto->LinkCustomAttributes = "";
			$this->nu_prospecto->HrefValue = "";
			$this->nu_prospecto->TooltipValue = "";

			// nu_tpProjeto
			$this->nu_tpProjeto->LinkCustomAttributes = "";
			$this->nu_tpProjeto->HrefValue = "";
			$this->nu_tpProjeto->TooltipValue = "";

			// nu_projetoInteg
			$this->nu_projetoInteg->LinkCustomAttributes = "";
			$this->nu_projetoInteg->HrefValue = "";
			$this->nu_projetoInteg->TooltipValue = "";

			// no_projeto
			$this->no_projeto->LinkCustomAttributes = "";
			$this->no_projeto->HrefValue = "";
			$this->no_projeto->TooltipValue = "";

			// id_tarefaTpProj
			$this->id_tarefaTpProj->LinkCustomAttributes = "";
			$this->id_tarefaTpProj->HrefValue = "";
			$this->id_tarefaTpProj->TooltipValue = "";

			// ic_complexProjeto
			$this->ic_complexProjeto->LinkCustomAttributes = "";
			$this->ic_complexProjeto->HrefValue = "";
			$this->ic_complexProjeto->TooltipValue = "";

			// ic_passivelContPf
			$this->ic_passivelContPf->LinkCustomAttributes = "";
			$this->ic_passivelContPf->HrefValue = "";
			$this->ic_passivelContPf->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nu_contrato
			$this->nu_contrato->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_contrato], [nu_contrato] AS [DispFld], [no_contrato] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[contrato]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_contrato, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_contrato] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_contrato->EditValue = $arwrk;

			// nu_itemContrato
			$this->nu_itemContrato->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_itemContratado], [nu_itemOc] AS [DispFld], [no_itemContratado] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [nu_contrato] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[item_contratado]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_itemContrato, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_itemContrato->EditValue = $arwrk;

			// nu_prospecto
			$this->nu_prospecto->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_prospecto], [no_prospecto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[prospecto]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_prospecto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_prospecto] DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_prospecto->EditValue = $arwrk;

			// nu_tpProjeto
			$this->nu_tpProjeto->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_tpProjeto], [no_tpProjeto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[tpprojeto]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S' AND ([ic_tpProjDem]='P' OR [ic_tpProjDem]='D')";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpProjeto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpProjeto] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_tpProjeto->EditValue = $arwrk;

			// nu_projetoInteg
			$this->nu_projetoInteg->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[tbrdm_projects]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_projetoInteg, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [created_on] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_projetoInteg->EditValue = $arwrk;

			// no_projeto
			$this->no_projeto->EditCustomAttributes = "";
			$this->no_projeto->EditValue = ew_HtmlEncode($this->no_projeto->CurrentValue);
			$this->no_projeto->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_projeto->FldCaption()));

			// id_tarefaTpProj
			$this->id_tarefaTpProj->EditCustomAttributes = "";
			$this->id_tarefaTpProj->EditValue = ew_HtmlEncode($this->id_tarefaTpProj->CurrentValue);
			if (strval($this->id_tarefaTpProj->CurrentValue) <> "") {
				$sFilterWrk = "[id]" . ew_SearchString("=", $this->id_tarefaTpProj->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [id], [subject] AS [DispFld], [id] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tbrdm_issues]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_tarefaTpProj, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [id] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_tarefaTpProj->EditValue = $rswrk->fields('DispFld');
					$this->id_tarefaTpProj->EditValue .= ew_ValueSeparator(1,$this->id_tarefaTpProj) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->id_tarefaTpProj->EditValue = $this->id_tarefaTpProj->CurrentValue;
				}
			} else {
				$this->id_tarefaTpProj->EditValue = NULL;
			}
			$this->id_tarefaTpProj->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->id_tarefaTpProj->FldCaption()));

			// ic_complexProjeto
			$this->ic_complexProjeto->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_complexProjeto->FldTagValue(1), $this->ic_complexProjeto->FldTagCaption(1) <> "" ? $this->ic_complexProjeto->FldTagCaption(1) : $this->ic_complexProjeto->FldTagValue(1));
			$arwrk[] = array($this->ic_complexProjeto->FldTagValue(2), $this->ic_complexProjeto->FldTagCaption(2) <> "" ? $this->ic_complexProjeto->FldTagCaption(2) : $this->ic_complexProjeto->FldTagValue(2));
			$arwrk[] = array($this->ic_complexProjeto->FldTagValue(3), $this->ic_complexProjeto->FldTagCaption(3) <> "" ? $this->ic_complexProjeto->FldTagCaption(3) : $this->ic_complexProjeto->FldTagValue(3));
			$this->ic_complexProjeto->EditValue = $arwrk;

			// ic_passivelContPf
			$this->ic_passivelContPf->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_passivelContPf->FldTagValue(1), $this->ic_passivelContPf->FldTagCaption(1) <> "" ? $this->ic_passivelContPf->FldTagCaption(1) : $this->ic_passivelContPf->FldTagValue(1));
			$arwrk[] = array($this->ic_passivelContPf->FldTagValue(2), $this->ic_passivelContPf->FldTagCaption(2) <> "" ? $this->ic_passivelContPf->FldTagCaption(2) : $this->ic_passivelContPf->FldTagValue(2));
			$this->ic_passivelContPf->EditValue = $arwrk;

			// Edit refer script
			// nu_contrato

			$this->nu_contrato->HrefValue = "";

			// nu_itemContrato
			$this->nu_itemContrato->HrefValue = "";

			// nu_prospecto
			$this->nu_prospecto->HrefValue = "";

			// nu_tpProjeto
			$this->nu_tpProjeto->HrefValue = "";

			// nu_projetoInteg
			$this->nu_projetoInteg->HrefValue = "";

			// no_projeto
			$this->no_projeto->HrefValue = "";

			// id_tarefaTpProj
			$this->id_tarefaTpProj->HrefValue = "";

			// ic_complexProjeto
			$this->ic_complexProjeto->HrefValue = "";

			// ic_passivelContPf
			$this->ic_passivelContPf->HrefValue = "";
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
		if (!$this->nu_tpProjeto->FldIsDetailKey && !is_null($this->nu_tpProjeto->FormValue) && $this->nu_tpProjeto->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_tpProjeto->FldCaption());
		}
		if (!$this->no_projeto->FldIsDetailKey && !is_null($this->no_projeto->FormValue) && $this->no_projeto->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_projeto->FldCaption());
		}
		if (!ew_CheckInteger($this->id_tarefaTpProj->FormValue)) {
			ew_AddMessage($gsFormError, $this->id_tarefaTpProj->FldErrMsg());
		}
		if ($this->ic_complexProjeto->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_complexProjeto->FldCaption());
		}
		if ($this->ic_passivelContPf->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_passivelContPf->FldCaption());
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

		// nu_contrato
		$this->nu_contrato->SetDbValueDef($rsnew, $this->nu_contrato->CurrentValue, NULL, FALSE);

		// nu_itemContrato
		$this->nu_itemContrato->SetDbValueDef($rsnew, $this->nu_itemContrato->CurrentValue, NULL, FALSE);

		// nu_prospecto
		$this->nu_prospecto->SetDbValueDef($rsnew, $this->nu_prospecto->CurrentValue, NULL, FALSE);

		// nu_tpProjeto
		$this->nu_tpProjeto->SetDbValueDef($rsnew, $this->nu_tpProjeto->CurrentValue, NULL, FALSE);

		// nu_projetoInteg
		$this->nu_projetoInteg->SetDbValueDef($rsnew, $this->nu_projetoInteg->CurrentValue, NULL, FALSE);

		// no_projeto
		$this->no_projeto->SetDbValueDef($rsnew, $this->no_projeto->CurrentValue, "", FALSE);

		// id_tarefaTpProj
		$this->id_tarefaTpProj->SetDbValueDef($rsnew, $this->id_tarefaTpProj->CurrentValue, NULL, FALSE);

		// ic_complexProjeto
		$this->ic_complexProjeto->SetDbValueDef($rsnew, $this->ic_complexProjeto->CurrentValue, NULL, FALSE);

		// ic_passivelContPf
		$this->ic_passivelContPf->SetDbValueDef($rsnew, $this->ic_passivelContPf->CurrentValue, NULL, FALSE);

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
			$this->nu_projeto->setDbValue($conn->Insert_ID());
			$rsnew['nu_projeto'] = $this->nu_projeto->DbValue;
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "projetolist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("addopt");
		$Breadcrumb->Add("addopt", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'projeto';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'projeto';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_projeto'];

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
if (!isset($projeto_addopt)) $projeto_addopt = new cprojeto_addopt();

// Page init
$projeto_addopt->Page_Init();

// Page main
$projeto_addopt->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$projeto_addopt->Page_Render();
?>
<script type="text/javascript">

// Page object
var projeto_addopt = new ew_Page("projeto_addopt");
projeto_addopt.PageID = "addopt"; // Page ID
var EW_PAGE_ID = projeto_addopt.PageID; // For backward compatibility

// Form object
var fprojetoaddopt = new ew_Form("fprojetoaddopt");

// Validate form
fprojetoaddopt.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_tpProjeto");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($projeto->nu_tpProjeto->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no_projeto");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($projeto->no_projeto->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_id_tarefaTpProj");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($projeto->id_tarefaTpProj->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ic_complexProjeto");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($projeto->ic_complexProjeto->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_passivelContPf");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($projeto->ic_passivelContPf->FldCaption()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
fprojetoaddopt.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fprojetoaddopt.ValidateRequired = true;
<?php } else { ?>
fprojetoaddopt.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fprojetoaddopt.Lists["x_nu_contrato"] = {"LinkField":"x_nu_contrato","Ajax":null,"AutoFill":false,"DisplayFields":["x_nu_contrato","x_no_contrato","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fprojetoaddopt.Lists["x_nu_itemContrato"] = {"LinkField":"x_nu_itemContratado","Ajax":null,"AutoFill":false,"DisplayFields":["x_nu_itemOc","x_no_itemContratado","",""],"ParentFields":["x_nu_contrato"],"FilterFields":["x_nu_contrato"],"Options":[]};
fprojetoaddopt.Lists["x_nu_prospecto"] = {"LinkField":"x_nu_prospecto","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_prospecto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fprojetoaddopt.Lists["x_nu_tpProjeto"] = {"LinkField":"x_nu_tpProjeto","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpProjeto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fprojetoaddopt.Lists["x_nu_projetoInteg"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fprojetoaddopt.Lists["x_id_tarefaTpProj"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_subject","x_id","",""],"ParentFields":["x_nu_projetoInteg"],"FilterFields":["x_project_id"],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
$projeto_addopt->ShowMessage();
?>
<form name="fprojetoaddopt" id="fprojetoaddopt" class="ewForm form-horizontal" action="projetoaddopt.php" method="post">
<input type="hidden" name="t" value="projeto">
<input type="hidden" name="a_addopt" id="a_addopt" value="A">
<div id="tbl_projetoaddopt">
	<div class="control-group">
		<label class="control-label" for="x_nu_contrato"><?php echo $projeto->nu_contrato->FldCaption() ?></label>
		<div class="controls">
<?php $projeto->nu_contrato->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_itemContrato']); " . @$projeto->nu_contrato->EditAttrs["onchange"]; ?>
<select data-field="x_nu_contrato" id="x_nu_contrato" name="x_nu_contrato"<?php echo $projeto->nu_contrato->EditAttributes() ?>>
<?php
if (is_array($projeto->nu_contrato->EditValue)) {
	$arwrk = $projeto->nu_contrato->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($projeto->nu_contrato->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$projeto->nu_contrato) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fprojetoaddopt.Lists["x_nu_contrato"].Options = <?php echo (is_array($projeto->nu_contrato->EditValue)) ? ew_ArrayToJson($projeto->nu_contrato->EditValue, 1) : "[]" ?>;
</script>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_nu_itemContrato"><?php echo $projeto->nu_itemContrato->FldCaption() ?></label>
		<div class="controls">
<select data-field="x_nu_itemContrato" id="x_nu_itemContrato" name="x_nu_itemContrato"<?php echo $projeto->nu_itemContrato->EditAttributes() ?>>
<?php
if (is_array($projeto->nu_itemContrato->EditValue)) {
	$arwrk = $projeto->nu_itemContrato->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($projeto->nu_itemContrato->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$projeto->nu_itemContrato) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fprojetoaddopt.Lists["x_nu_itemContrato"].Options = <?php echo (is_array($projeto->nu_itemContrato->EditValue)) ? ew_ArrayToJson($projeto->nu_itemContrato->EditValue, 1) : "[]" ?>;
</script>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_nu_prospecto"><?php echo $projeto->nu_prospecto->FldCaption() ?></label>
		<div class="controls">
<select data-field="x_nu_prospecto" id="x_nu_prospecto" name="x_nu_prospecto"<?php echo $projeto->nu_prospecto->EditAttributes() ?>>
<?php
if (is_array($projeto->nu_prospecto->EditValue)) {
	$arwrk = $projeto->nu_prospecto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($projeto->nu_prospecto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fprojetoaddopt.Lists["x_nu_prospecto"].Options = <?php echo (is_array($projeto->nu_prospecto->EditValue)) ? ew_ArrayToJson($projeto->nu_prospecto->EditValue, 1) : "[]" ?>;
</script>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_nu_tpProjeto"><?php echo $projeto->nu_tpProjeto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="controls">
<select data-field="x_nu_tpProjeto" id="x_nu_tpProjeto" name="x_nu_tpProjeto"<?php echo $projeto->nu_tpProjeto->EditAttributes() ?>>
<?php
if (is_array($projeto->nu_tpProjeto->EditValue)) {
	$arwrk = $projeto->nu_tpProjeto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($projeto->nu_tpProjeto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fprojetoaddopt.Lists["x_nu_tpProjeto"].Options = <?php echo (is_array($projeto->nu_tpProjeto->EditValue)) ? ew_ArrayToJson($projeto->nu_tpProjeto->EditValue, 1) : "[]" ?>;
</script>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_nu_projetoInteg"><?php echo $projeto->nu_projetoInteg->FldCaption() ?></label>
		<div class="controls">
<?php $projeto->nu_projetoInteg->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_id_tarefaTpProj']); " . @$projeto->nu_projetoInteg->EditAttrs["onchange"]; ?>
<select data-field="x_nu_projetoInteg" id="x_nu_projetoInteg" name="x_nu_projetoInteg"<?php echo $projeto->nu_projetoInteg->EditAttributes() ?>>
<?php
if (is_array($projeto->nu_projetoInteg->EditValue)) {
	$arwrk = $projeto->nu_projetoInteg->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($projeto->nu_projetoInteg->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fprojetoaddopt.Lists["x_nu_projetoInteg"].Options = <?php echo (is_array($projeto->nu_projetoInteg->EditValue)) ? ew_ArrayToJson($projeto->nu_projetoInteg->EditValue, 1) : "[]" ?>;
</script>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_no_projeto"><?php echo $projeto->no_projeto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="controls">
<input type="text" data-field="x_no_projeto" name="x_no_projeto" id="x_no_projeto" size="120" maxlength="120" placeholder="<?php echo $projeto->no_projeto->PlaceHolder ?>" value="<?php echo $projeto->no_projeto->EditValue ?>"<?php echo $projeto->no_projeto->EditAttributes() ?>>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_id_tarefaTpProj"><?php echo $projeto->id_tarefaTpProj->FldCaption() ?></label>
		<div class="controls">
<?php
	$wrkonchange = trim(" " . @$projeto->id_tarefaTpProj->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$projeto->id_tarefaTpProj->EditAttrs["onchange"] = "";
?>
<span id="as_x_id_tarefaTpProj" style="white-space: nowrap; z-index: 8920">
	<input type="text" name="sv_x_id_tarefaTpProj" id="sv_x_id_tarefaTpProj" value="<?php echo $projeto->id_tarefaTpProj->EditValue ?>" size="30" placeholder="<?php echo $projeto->id_tarefaTpProj->PlaceHolder ?>"<?php echo $projeto->id_tarefaTpProj->EditAttributes() ?>>&nbsp;<span id="em_x_id_tarefaTpProj" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_id_tarefaTpProj" style="display: inline; z-index: 8920"></div>
</span>
<input type="hidden" data-field="x_id_tarefaTpProj" name="x_id_tarefaTpProj" id="x_id_tarefaTpProj" value="<?php echo $projeto->id_tarefaTpProj->CurrentValue ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT  TOP " . EW_AUTO_SUGGEST_MAX_ENTRIES . " [id], [subject] AS [DispFld], [id] AS [Disp2Fld] FROM [dbo].[tbrdm_issues]";
$sWhereWrk = "([subject] LIKE '%{query_value}%' OR CAST([id] AS NVARCHAR) LIKE '%{query_value}%' OR [subject] + '" . ew_ValueSeparator(1, $Page->id_tarefaTpProj) . "' + CAST([id] AS NVARCHAR) LIKE '{query_value}%') AND ({filter})";

// Call Lookup selecting
$projeto->Lookup_Selecting($projeto->id_tarefaTpProj, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY [id] ASC";
?>
<input type="hidden" name="q_x_id_tarefaTpProj" id="q_x_id_tarefaTpProj" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&f1=<?php echo ew_Encrypt("[project_id] IN ({filter_value})"); ?>&t1=3">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x_id_tarefaTpProj", fprojetoaddopt, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_id_tarefaTpProj") + ar[i] : "";
	return dv;
}
fprojetoaddopt.AutoSuggests["x_id_tarefaTpProj"] = oas;
</script>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_ic_complexProjeto"><?php echo $projeto->ic_complexProjeto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="controls">
<div id="tp_x_ic_complexProjeto" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_complexProjeto" id="x_ic_complexProjeto" value="{value}"<?php echo $projeto->ic_complexProjeto->EditAttributes() ?>></div>
<div id="dsl_x_ic_complexProjeto" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $projeto->ic_complexProjeto->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($projeto->ic_complexProjeto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_complexProjeto" name="x_ic_complexProjeto" id="x_ic_complexProjeto_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $projeto->ic_complexProjeto->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_ic_passivelContPf"><?php echo $projeto->ic_passivelContPf->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="controls">
<div id="tp_x_ic_passivelContPf" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_passivelContPf" id="x_ic_passivelContPf" value="{value}"<?php echo $projeto->ic_passivelContPf->EditAttributes() ?>></div>
<div id="dsl_x_ic_passivelContPf" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $projeto->ic_passivelContPf->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($projeto->ic_passivelContPf->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_passivelContPf" name="x_ic_passivelContPf" id="x_ic_passivelContPf_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $projeto->ic_passivelContPf->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</div>
	</div>
</div>
</form>
<script type="text/javascript">
fprojetoaddopt.Init();
</script>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php
$projeto_addopt->Page_Terminate();
?>
