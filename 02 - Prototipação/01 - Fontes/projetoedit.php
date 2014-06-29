<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "projetoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "projeto_centrocustogridcls.php" ?>
<?php include_once "riscoprojetogridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$projeto_edit = NULL; // Initialize page object first

class cprojeto_edit extends cprojeto {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'projeto';

	// Page object name
	var $PageObjName = 'projeto_edit';

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
	var $AuditTrailOnEdit = TRUE;

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
			define("EW_PAGE_ID", 'edit', TRUE);

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
		if (!$Security->CanEdit()) {
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
		$this->nu_projeto->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["nu_projeto"] <> "") {
			$this->nu_projeto->setQueryStringValue($_GET["nu_projeto"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values

			// Set up detail parameters
			$this->SetUpDetailParms();
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->nu_projeto->CurrentValue == "")
			$this->Page_Terminate("projetolist.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("projetolist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					if ($this->getCurrentDetailTable() <> "") // Master/detail edit
						$sReturnUrl = $this->GetDetailUrl();
					else
						$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "projetoview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to View page directly
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed

					// Set up detail parameters
					$this->SetUpDetailParms();
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_projeto->FldIsDetailKey)
			$this->nu_projeto->setFormValue($objForm->GetValue("x_nu_projeto"));
		if (!$this->nu_contrato->FldIsDetailKey) {
			$this->nu_contrato->setFormValue($objForm->GetValue("x_nu_contrato"));
		}
		if (!$this->nu_itemContrato->FldIsDetailKey) {
			$this->nu_itemContrato->setFormValue($objForm->GetValue("x_nu_itemContrato"));
		}
		if (!$this->nu_prospecto->FldIsDetailKey) {
			$this->nu_prospecto->setFormValue($objForm->GetValue("x_nu_prospecto"));
		}
		if (!$this->nu_tpProjeto->FldIsDetailKey) {
			$this->nu_tpProjeto->setFormValue($objForm->GetValue("x_nu_tpProjeto"));
		}
		if (!$this->nu_projetoInteg->FldIsDetailKey) {
			$this->nu_projetoInteg->setFormValue($objForm->GetValue("x_nu_projetoInteg"));
		}
		if (!$this->no_projeto->FldIsDetailKey) {
			$this->no_projeto->setFormValue($objForm->GetValue("x_no_projeto"));
		}
		if (!$this->id_tarefaTpProj->FldIsDetailKey) {
			$this->id_tarefaTpProj->setFormValue($objForm->GetValue("x_id_tarefaTpProj"));
		}
		if (!$this->ic_complexProjeto->FldIsDetailKey) {
			$this->ic_complexProjeto->setFormValue($objForm->GetValue("x_ic_complexProjeto"));
		}
		if (!$this->ic_passivelContPf->FldIsDetailKey) {
			$this->ic_passivelContPf->setFormValue($objForm->GetValue("x_ic_passivelContPf"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->nu_projeto->CurrentValue = $this->nu_projeto->FormValue;
		$this->nu_contrato->CurrentValue = $this->nu_contrato->FormValue;
		$this->nu_itemContrato->CurrentValue = $this->nu_itemContrato->FormValue;
		$this->nu_prospecto->CurrentValue = $this->nu_prospecto->FormValue;
		$this->nu_tpProjeto->CurrentValue = $this->nu_tpProjeto->FormValue;
		$this->nu_projetoInteg->CurrentValue = $this->nu_projetoInteg->FormValue;
		$this->no_projeto->CurrentValue = $this->no_projeto->FormValue;
		$this->id_tarefaTpProj->CurrentValue = $this->id_tarefaTpProj->FormValue;
		$this->ic_complexProjeto->CurrentValue = $this->ic_complexProjeto->FormValue;
		$this->ic_passivelContPf->CurrentValue = $this->ic_passivelContPf->FormValue;
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

			// nu_projeto
			$this->nu_projeto->LinkCustomAttributes = "";
			$this->nu_projeto->HrefValue = "";
			$this->nu_projeto->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// nu_projeto
			$this->nu_projeto->EditCustomAttributes = "";
			$this->nu_projeto->EditValue = $this->nu_projeto->CurrentValue;
			$this->nu_projeto->ViewCustomAttributes = "";

			// nu_contrato
			$this->nu_contrato->EditCustomAttributes = "";
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
					$this->nu_contrato->EditValue = $rswrk->fields('DispFld');
					$this->nu_contrato->EditValue .= ew_ValueSeparator(1,$this->nu_contrato) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_contrato->EditValue = $this->nu_contrato->CurrentValue;
				}
			} else {
				$this->nu_contrato->EditValue = NULL;
			}
			}
			$this->nu_contrato->ViewCustomAttributes = "";

			// nu_itemContrato
			$this->nu_itemContrato->EditCustomAttributes = "";
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
					$this->nu_itemContrato->EditValue = $rswrk->fields('DispFld');
					$this->nu_itemContrato->EditValue .= ew_ValueSeparator(1,$this->nu_itemContrato) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_itemContrato->EditValue = $this->nu_itemContrato->CurrentValue;
				}
			} else {
				$this->nu_itemContrato->EditValue = NULL;
			}
			}
			$this->nu_itemContrato->ViewCustomAttributes = "";

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
					$this->nu_tpProjeto->EditValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpProjeto->EditValue = $this->nu_tpProjeto->CurrentValue;
				}
			} else {
				$this->nu_tpProjeto->EditValue = NULL;
			}
			$this->nu_tpProjeto->ViewCustomAttributes = "";

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
			if (strval($this->ic_passivelContPf->CurrentValue) <> "") {
				switch ($this->ic_passivelContPf->CurrentValue) {
					case $this->ic_passivelContPf->FldTagValue(1):
						$this->ic_passivelContPf->EditValue = $this->ic_passivelContPf->FldTagCaption(1) <> "" ? $this->ic_passivelContPf->FldTagCaption(1) : $this->ic_passivelContPf->CurrentValue;
						break;
					case $this->ic_passivelContPf->FldTagValue(2):
						$this->ic_passivelContPf->EditValue = $this->ic_passivelContPf->FldTagCaption(2) <> "" ? $this->ic_passivelContPf->FldTagCaption(2) : $this->ic_passivelContPf->CurrentValue;
						break;
					default:
						$this->ic_passivelContPf->EditValue = $this->ic_passivelContPf->CurrentValue;
				}
			} else {
				$this->ic_passivelContPf->EditValue = NULL;
			}
			$this->ic_passivelContPf->ViewCustomAttributes = "";

			// Edit refer script
			// nu_projeto

			$this->nu_projeto->HrefValue = "";

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
		if (!$this->no_projeto->FldIsDetailKey && !is_null($this->no_projeto->FormValue) && $this->no_projeto->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_projeto->FldCaption());
		}
		if (!ew_CheckInteger($this->id_tarefaTpProj->FormValue)) {
			ew_AddMessage($gsFormError, $this->id_tarefaTpProj->FldErrMsg());
		}
		if ($this->ic_complexProjeto->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_complexProjeto->FldCaption());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("projeto_centrocusto", $DetailTblVar) && $GLOBALS["projeto_centrocusto"]->DetailEdit) {
			if (!isset($GLOBALS["projeto_centrocusto_grid"])) $GLOBALS["projeto_centrocusto_grid"] = new cprojeto_centrocusto_grid(); // get detail page object
			$GLOBALS["projeto_centrocusto_grid"]->ValidateGridForm();
		}
		if (in_array("riscoprojeto", $DetailTblVar) && $GLOBALS["riscoprojeto"]->DetailEdit) {
			if (!isset($GLOBALS["riscoprojeto_grid"])) $GLOBALS["riscoprojeto_grid"] = new criscoprojeto_grid(); // get detail page object
			$GLOBALS["riscoprojeto_grid"]->ValidateGridForm();
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

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Begin transaction
			if ($this->getCurrentDetailTable() <> "")
				$conn->BeginTrans();

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// nu_prospecto
			$this->nu_prospecto->SetDbValueDef($rsnew, $this->nu_prospecto->CurrentValue, NULL, $this->nu_prospecto->ReadOnly);

			// nu_projetoInteg
			$this->nu_projetoInteg->SetDbValueDef($rsnew, $this->nu_projetoInteg->CurrentValue, NULL, $this->nu_projetoInteg->ReadOnly);

			// no_projeto
			$this->no_projeto->SetDbValueDef($rsnew, $this->no_projeto->CurrentValue, "", $this->no_projeto->ReadOnly);

			// id_tarefaTpProj
			$this->id_tarefaTpProj->SetDbValueDef($rsnew, $this->id_tarefaTpProj->CurrentValue, NULL, $this->id_tarefaTpProj->ReadOnly);

			// ic_complexProjeto
			$this->ic_complexProjeto->SetDbValueDef($rsnew, $this->ic_complexProjeto->CurrentValue, NULL, $this->ic_complexProjeto->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = 'ew_ErrorFn';
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}

				// Update detail records
				if ($EditRow) {
					$DetailTblVar = explode(",", $this->getCurrentDetailTable());
					if (in_array("projeto_centrocusto", $DetailTblVar) && $GLOBALS["projeto_centrocusto"]->DetailEdit) {
						if (!isset($GLOBALS["projeto_centrocusto_grid"])) $GLOBALS["projeto_centrocusto_grid"] = new cprojeto_centrocusto_grid(); // Get detail page object
						$EditRow = $GLOBALS["projeto_centrocusto_grid"]->GridUpdate();
					}
					if (in_array("riscoprojeto", $DetailTblVar) && $GLOBALS["riscoprojeto"]->DetailEdit) {
						if (!isset($GLOBALS["riscoprojeto_grid"])) $GLOBALS["riscoprojeto_grid"] = new criscoprojeto_grid(); // Get detail page object
						$EditRow = $GLOBALS["riscoprojeto_grid"]->GridUpdate();
					}
				}

				// Commit/Rollback transaction
				if ($this->getCurrentDetailTable() <> "") {
					if ($EditRow) {
						$conn->CommitTrans(); // Commit transaction
					} else {
						$conn->RollbackTrans(); // Rollback transaction
					}
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		if ($EditRow) {
			$this->WriteAuditTrailOnEdit($rsold, $rsnew);
		}
		$rs->Close();
		return $EditRow;
	}

	// Set up detail parms based on QueryString
	function SetUpDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
			if (in_array("projeto_centrocusto", $DetailTblVar)) {
				if (!isset($GLOBALS["projeto_centrocusto_grid"]))
					$GLOBALS["projeto_centrocusto_grid"] = new cprojeto_centrocusto_grid;
				if ($GLOBALS["projeto_centrocusto_grid"]->DetailEdit) {
					$GLOBALS["projeto_centrocusto_grid"]->CurrentMode = "edit";
					$GLOBALS["projeto_centrocusto_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["projeto_centrocusto_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["projeto_centrocusto_grid"]->setStartRecordNumber(1);
					$GLOBALS["projeto_centrocusto_grid"]->nu_projeto->FldIsDetailKey = TRUE;
					$GLOBALS["projeto_centrocusto_grid"]->nu_projeto->CurrentValue = $this->nu_projeto->CurrentValue;
					$GLOBALS["projeto_centrocusto_grid"]->nu_projeto->setSessionValue($GLOBALS["projeto_centrocusto_grid"]->nu_projeto->CurrentValue);
				}
			}
			if (in_array("riscoprojeto", $DetailTblVar)) {
				if (!isset($GLOBALS["riscoprojeto_grid"]))
					$GLOBALS["riscoprojeto_grid"] = new criscoprojeto_grid;
				if ($GLOBALS["riscoprojeto_grid"]->DetailEdit) {
					$GLOBALS["riscoprojeto_grid"]->CurrentMode = "edit";
					$GLOBALS["riscoprojeto_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["riscoprojeto_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["riscoprojeto_grid"]->setStartRecordNumber(1);
					$GLOBALS["riscoprojeto_grid"]->nu_projeto->FldIsDetailKey = TRUE;
					$GLOBALS["riscoprojeto_grid"]->nu_projeto->CurrentValue = $this->nu_projeto->CurrentValue;
					$GLOBALS["riscoprojeto_grid"]->nu_projeto->setSessionValue($GLOBALS["riscoprojeto_grid"]->nu_projeto->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "projetolist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("edit");
		$Breadcrumb->Add("edit", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'projeto';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		if (!$this->AuditTrailOnEdit) return;
		$table = 'projeto';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['nu_projeto'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
	  $usr = CurrentUserID();
		foreach (array_keys($rsnew) as $fldname) {
			if ($this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_DATE) { // DateTime field
					$modified = (ew_FormatDateTime($rsold[$fldname], 0) <> ew_FormatDateTime($rsnew[$fldname], 0));
				} else {
					$modified = !ew_CompareValue($rsold[$fldname], $rsnew[$fldname]);
				}
				if ($modified) {
					if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) { // Memo field
						if (EW_AUDIT_TRAIL_TO_DATABASE) {
							$oldvalue = $rsold[$fldname];
							$newvalue = $rsnew[$fldname];
						} else {
							$oldvalue = "[MEMO]";
							$newvalue = "[MEMO]";
						}
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) { // XML field
						$oldvalue = "[XML]";
						$newvalue = "[XML]";
					} else {
						$oldvalue = $rsold[$fldname];
						$newvalue = $rsnew[$fldname];
					}
					ew_WriteAuditTrail("log", $dt, $id, $usr, "U", $table, $fldname, $key, $oldvalue, $newvalue);
				}
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
if (!isset($projeto_edit)) $projeto_edit = new cprojeto_edit();

// Page init
$projeto_edit->Page_Init();

// Page main
$projeto_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$projeto_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var projeto_edit = new ew_Page("projeto_edit");
projeto_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = projeto_edit.PageID; // For backward compatibility

// Form object
var fprojetoedit = new ew_Form("fprojetoedit");

// Validate form
fprojetoedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_no_projeto");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($projeto->no_projeto->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_id_tarefaTpProj");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($projeto->id_tarefaTpProj->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ic_complexProjeto");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($projeto->ic_complexProjeto->FldCaption()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fprojetoedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fprojetoedit.ValidateRequired = true;
<?php } else { ?>
fprojetoedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fprojetoedit.Lists["x_nu_contrato"] = {"LinkField":"x_nu_contrato","Ajax":null,"AutoFill":false,"DisplayFields":["x_nu_contrato","x_no_contrato","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fprojetoedit.Lists["x_nu_itemContrato"] = {"LinkField":"x_nu_itemContratado","Ajax":null,"AutoFill":false,"DisplayFields":["x_nu_itemOc","x_no_itemContratado","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fprojetoedit.Lists["x_nu_prospecto"] = {"LinkField":"x_nu_prospecto","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_prospecto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fprojetoedit.Lists["x_nu_tpProjeto"] = {"LinkField":"x_nu_tpProjeto","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpProjeto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fprojetoedit.Lists["x_nu_projetoInteg"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fprojetoedit.Lists["x_id_tarefaTpProj"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_subject","x_id","",""],"ParentFields":["x_nu_projetoInteg"],"FilterFields":["x_project_id"],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $projeto_edit->ShowPageHeader(); ?>
<?php
$projeto_edit->ShowMessage();
?>
<form name="fprojetoedit" id="fprojetoedit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="projeto">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_projetoedit" class="table table-bordered table-striped">
<?php if ($projeto->nu_projeto->Visible) { // nu_projeto ?>
	<tr id="r_nu_projeto">
		<td><span id="elh_projeto_nu_projeto"><?php echo $projeto->nu_projeto->FldCaption() ?></span></td>
		<td<?php echo $projeto->nu_projeto->CellAttributes() ?>>
<span id="el_projeto_nu_projeto" class="control-group">
<span<?php echo $projeto->nu_projeto->ViewAttributes() ?>>
<?php echo $projeto->nu_projeto->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_projeto" name="x_nu_projeto" id="x_nu_projeto" value="<?php echo ew_HtmlEncode($projeto->nu_projeto->CurrentValue) ?>">
<?php echo $projeto->nu_projeto->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($projeto->nu_contrato->Visible) { // nu_contrato ?>
	<tr id="r_nu_contrato">
		<td><span id="elh_projeto_nu_contrato"><?php echo $projeto->nu_contrato->FldCaption() ?></span></td>
		<td<?php echo $projeto->nu_contrato->CellAttributes() ?>>
<span id="el_projeto_nu_contrato" class="control-group">
<span<?php echo $projeto->nu_contrato->ViewAttributes() ?>>
<?php echo $projeto->nu_contrato->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_contrato" name="x_nu_contrato" id="x_nu_contrato" value="<?php echo ew_HtmlEncode($projeto->nu_contrato->CurrentValue) ?>">
<?php echo $projeto->nu_contrato->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($projeto->nu_itemContrato->Visible) { // nu_itemContrato ?>
	<tr id="r_nu_itemContrato">
		<td><span id="elh_projeto_nu_itemContrato"><?php echo $projeto->nu_itemContrato->FldCaption() ?></span></td>
		<td<?php echo $projeto->nu_itemContrato->CellAttributes() ?>>
<span id="el_projeto_nu_itemContrato" class="control-group">
<span<?php echo $projeto->nu_itemContrato->ViewAttributes() ?>>
<?php echo $projeto->nu_itemContrato->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_itemContrato" name="x_nu_itemContrato" id="x_nu_itemContrato" value="<?php echo ew_HtmlEncode($projeto->nu_itemContrato->CurrentValue) ?>">
<?php echo $projeto->nu_itemContrato->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($projeto->nu_prospecto->Visible) { // nu_prospecto ?>
	<tr id="r_nu_prospecto">
		<td><span id="elh_projeto_nu_prospecto"><?php echo $projeto->nu_prospecto->FldCaption() ?></span></td>
		<td<?php echo $projeto->nu_prospecto->CellAttributes() ?>>
<span id="el_projeto_nu_prospecto" class="control-group">
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
<?php if (AllowAdd(CurrentProjectID() . "prospecto")) { ?>
&nbsp;<a id="aol_x_nu_prospecto" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_nu_prospecto',url:'prospectoaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $projeto->nu_prospecto->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
fprojetoedit.Lists["x_nu_prospecto"].Options = <?php echo (is_array($projeto->nu_prospecto->EditValue)) ? ew_ArrayToJson($projeto->nu_prospecto->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $projeto->nu_prospecto->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($projeto->nu_tpProjeto->Visible) { // nu_tpProjeto ?>
	<tr id="r_nu_tpProjeto">
		<td><span id="elh_projeto_nu_tpProjeto"><?php echo $projeto->nu_tpProjeto->FldCaption() ?></span></td>
		<td<?php echo $projeto->nu_tpProjeto->CellAttributes() ?>>
<span id="el_projeto_nu_tpProjeto" class="control-group">
<span<?php echo $projeto->nu_tpProjeto->ViewAttributes() ?>>
<?php echo $projeto->nu_tpProjeto->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_tpProjeto" name="x_nu_tpProjeto" id="x_nu_tpProjeto" value="<?php echo ew_HtmlEncode($projeto->nu_tpProjeto->CurrentValue) ?>">
<?php echo $projeto->nu_tpProjeto->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($projeto->nu_projetoInteg->Visible) { // nu_projetoInteg ?>
	<tr id="r_nu_projetoInteg">
		<td><span id="elh_projeto_nu_projetoInteg"><?php echo $projeto->nu_projetoInteg->FldCaption() ?></span></td>
		<td<?php echo $projeto->nu_projetoInteg->CellAttributes() ?>>
<span id="el_projeto_nu_projetoInteg" class="control-group">
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
fprojetoedit.Lists["x_nu_projetoInteg"].Options = <?php echo (is_array($projeto->nu_projetoInteg->EditValue)) ? ew_ArrayToJson($projeto->nu_projetoInteg->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $projeto->nu_projetoInteg->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($projeto->no_projeto->Visible) { // no_projeto ?>
	<tr id="r_no_projeto">
		<td><span id="elh_projeto_no_projeto"><?php echo $projeto->no_projeto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $projeto->no_projeto->CellAttributes() ?>>
<span id="el_projeto_no_projeto" class="control-group">
<input type="text" data-field="x_no_projeto" name="x_no_projeto" id="x_no_projeto" size="120" maxlength="120" placeholder="<?php echo $projeto->no_projeto->PlaceHolder ?>" value="<?php echo $projeto->no_projeto->EditValue ?>"<?php echo $projeto->no_projeto->EditAttributes() ?>>
</span>
<?php echo $projeto->no_projeto->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($projeto->id_tarefaTpProj->Visible) { // id_tarefaTpProj ?>
	<tr id="r_id_tarefaTpProj">
		<td><span id="elh_projeto_id_tarefaTpProj"><?php echo $projeto->id_tarefaTpProj->FldCaption() ?></span></td>
		<td<?php echo $projeto->id_tarefaTpProj->CellAttributes() ?>>
<span id="el_projeto_id_tarefaTpProj" class="control-group">
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
var oas = new ew_AutoSuggest("x_id_tarefaTpProj", fprojetoedit, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_id_tarefaTpProj") + ar[i] : "";
	return dv;
}
fprojetoedit.AutoSuggests["x_id_tarefaTpProj"] = oas;
</script>
</span>
<?php echo $projeto->id_tarefaTpProj->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($projeto->ic_complexProjeto->Visible) { // ic_complexProjeto ?>
	<tr id="r_ic_complexProjeto">
		<td><span id="elh_projeto_ic_complexProjeto"><?php echo $projeto->ic_complexProjeto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $projeto->ic_complexProjeto->CellAttributes() ?>>
<span id="el_projeto_ic_complexProjeto" class="control-group">
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
</span>
<?php echo $projeto->ic_complexProjeto->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($projeto->ic_passivelContPf->Visible) { // ic_passivelContPf ?>
	<tr id="r_ic_passivelContPf">
		<td><span id="elh_projeto_ic_passivelContPf"><?php echo $projeto->ic_passivelContPf->FldCaption() ?></span></td>
		<td<?php echo $projeto->ic_passivelContPf->CellAttributes() ?>>
<span id="el_projeto_ic_passivelContPf" class="control-group">
<span<?php echo $projeto->ic_passivelContPf->ViewAttributes() ?>>
<?php echo $projeto->ic_passivelContPf->EditValue ?></span>
</span>
<input type="hidden" data-field="x_ic_passivelContPf" name="x_ic_passivelContPf" id="x_ic_passivelContPf" value="<?php echo ew_HtmlEncode($projeto->ic_passivelContPf->CurrentValue) ?>">
<?php echo $projeto->ic_passivelContPf->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<?php
	if (in_array("projeto_centrocusto", explode(",", $projeto->getCurrentDetailTable())) && $projeto_centrocusto->DetailEdit) {
?>
<?php include_once "projeto_centrocustogrid.php" ?>
<?php } ?>
<?php
	if (in_array("riscoprojeto", explode(",", $projeto->getCurrentDetailTable())) && $riscoprojeto->DetailEdit) {
?>
<?php include_once "riscoprojetogrid.php" ?>
<?php } ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fprojetoedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$projeto_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$projeto_edit->Page_Terminate();
?>
