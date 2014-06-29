<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "widget_perfilinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$widget_perfil_edit = NULL; // Initialize page object first

class cwidget_perfil_edit extends cwidget_perfil {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'widget_perfil';

	// Page object name
	var $PageObjName = 'widget_perfil_edit';

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

		// Table object (widget_perfil)
		if (!isset($GLOBALS["widget_perfil"])) {
			$GLOBALS["widget_perfil"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["widget_perfil"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'widget_perfil', TRUE);

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
			$this->Page_Terminate("widget_perfillist.php");
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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["nu_perfil"] <> "") {
			$this->nu_perfil->setQueryStringValue($_GET["nu_perfil"]);
		}
		if (@$_GET["nu_widget"] <> "") {
			$this->nu_widget->setQueryStringValue($_GET["nu_widget"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->nu_perfil->CurrentValue == "")
			$this->Page_Terminate("widget_perfillist.php"); // Invalid key, return to list
		if ($this->nu_widget->CurrentValue == "")
			$this->Page_Terminate("widget_perfillist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("widget_perfillist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "widget_perfilview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to View page directly
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
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
		if (!$this->nu_perfil->FldIsDetailKey) {
			$this->nu_perfil->setFormValue($objForm->GetValue("x_nu_perfil"));
		}
		if (!$this->nu_widget->FldIsDetailKey) {
			$this->nu_widget->setFormValue($objForm->GetValue("x_nu_widget"));
		}
		if (!$this->no_titulo->FldIsDetailKey) {
			$this->no_titulo->setFormValue($objForm->GetValue("x_no_titulo"));
		}
		if (!$this->no_legTexto->FldIsDetailKey) {
			$this->no_legTexto->setFormValue($objForm->GetValue("x_no_legTexto"));
		}
		if (!$this->no_legValores->FldIsDetailKey) {
			$this->no_legValores->setFormValue($objForm->GetValue("x_no_legValores"));
		}
		if (!$this->nu_posicao->FldIsDetailKey) {
			$this->nu_posicao->setFormValue($objForm->GetValue("x_nu_posicao"));
		}
		if (!$this->vr_larguraEmPx->FldIsDetailKey) {
			$this->vr_larguraEmPx->setFormValue($objForm->GetValue("x_vr_larguraEmPx"));
		}
		if (!$this->vr_alturaEmPx->FldIsDetailKey) {
			$this->vr_alturaEmPx->setFormValue($objForm->GetValue("x_vr_alturaEmPx"));
		}
		if (!$this->ic_ativo->FldIsDetailKey) {
			$this->ic_ativo->setFormValue($objForm->GetValue("x_ic_ativo"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->nu_perfil->CurrentValue = $this->nu_perfil->FormValue;
		$this->nu_widget->CurrentValue = $this->nu_widget->FormValue;
		$this->no_titulo->CurrentValue = $this->no_titulo->FormValue;
		$this->no_legTexto->CurrentValue = $this->no_legTexto->FormValue;
		$this->no_legValores->CurrentValue = $this->no_legValores->FormValue;
		$this->nu_posicao->CurrentValue = $this->nu_posicao->FormValue;
		$this->vr_larguraEmPx->CurrentValue = $this->vr_larguraEmPx->FormValue;
		$this->vr_alturaEmPx->CurrentValue = $this->vr_alturaEmPx->FormValue;
		$this->ic_ativo->CurrentValue = $this->ic_ativo->FormValue;
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
		$this->nu_perfil->setDbValue($rs->fields('nu_perfil'));
		if (array_key_exists('EV__nu_perfil', $rs->fields)) {
			$this->nu_perfil->VirtualValue = $rs->fields('EV__nu_perfil'); // Set up virtual field value
		} else {
			$this->nu_perfil->VirtualValue = ""; // Clear value
		}
		$this->nu_widget->setDbValue($rs->fields('nu_widget'));
		$this->no_titulo->setDbValue($rs->fields('no_titulo'));
		$this->no_legTexto->setDbValue($rs->fields('no_legTexto'));
		$this->no_legValores->setDbValue($rs->fields('no_legValores'));
		$this->nu_posicao->setDbValue($rs->fields('nu_posicao'));
		$this->vr_larguraEmPx->setDbValue($rs->fields('vr_larguraEmPx'));
		$this->vr_alturaEmPx->setDbValue($rs->fields('vr_alturaEmPx'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_perfil->DbValue = $row['nu_perfil'];
		$this->nu_widget->DbValue = $row['nu_widget'];
		$this->no_titulo->DbValue = $row['no_titulo'];
		$this->no_legTexto->DbValue = $row['no_legTexto'];
		$this->no_legValores->DbValue = $row['no_legValores'];
		$this->nu_posicao->DbValue = $row['nu_posicao'];
		$this->vr_larguraEmPx->DbValue = $row['vr_larguraEmPx'];
		$this->vr_alturaEmPx->DbValue = $row['vr_alturaEmPx'];
		$this->ic_ativo->DbValue = $row['ic_ativo'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_perfil
		// nu_widget
		// no_titulo
		// no_legTexto
		// no_legValores
		// nu_posicao
		// vr_larguraEmPx
		// vr_alturaEmPx
		// ic_ativo

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_perfil
			if ($this->nu_perfil->VirtualValue <> "") {
				$this->nu_perfil->ViewValue = $this->nu_perfil->VirtualValue;
			} else {
			if (strval($this->nu_perfil->CurrentValue) <> "") {
				$sFilterWrk = "[nu_level]" . ew_SearchString("=", $this->nu_perfil->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_level], [no_level] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario_permissoes]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_perfil, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_level] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_perfil->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_perfil->ViewValue = $this->nu_perfil->CurrentValue;
				}
			} else {
				$this->nu_perfil->ViewValue = NULL;
			}
			}
			$this->nu_perfil->ViewCustomAttributes = "";

			// nu_widget
			if (strval($this->nu_widget->CurrentValue) <> "") {
				$sFilterWrk = "[nu_widget]" . ew_SearchString("=", $this->nu_widget->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_widget], [no_widget] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[widget]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_widget, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [ic_ativo] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_widget->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_widget->ViewValue = $this->nu_widget->CurrentValue;
				}
			} else {
				$this->nu_widget->ViewValue = NULL;
			}
			$this->nu_widget->ViewCustomAttributes = "";

			// no_titulo
			$this->no_titulo->ViewValue = $this->no_titulo->CurrentValue;
			$this->no_titulo->ViewCustomAttributes = "";

			// no_legTexto
			$this->no_legTexto->ViewValue = $this->no_legTexto->CurrentValue;
			$this->no_legTexto->ViewCustomAttributes = "";

			// no_legValores
			$this->no_legValores->ViewValue = $this->no_legValores->CurrentValue;
			$this->no_legValores->ViewCustomAttributes = "";

			// nu_posicao
			if (strval($this->nu_posicao->CurrentValue) <> "") {
				switch ($this->nu_posicao->CurrentValue) {
					case $this->nu_posicao->FldTagValue(1):
						$this->nu_posicao->ViewValue = $this->nu_posicao->FldTagCaption(1) <> "" ? $this->nu_posicao->FldTagCaption(1) : $this->nu_posicao->CurrentValue;
						break;
					case $this->nu_posicao->FldTagValue(2):
						$this->nu_posicao->ViewValue = $this->nu_posicao->FldTagCaption(2) <> "" ? $this->nu_posicao->FldTagCaption(2) : $this->nu_posicao->CurrentValue;
						break;
					case $this->nu_posicao->FldTagValue(3):
						$this->nu_posicao->ViewValue = $this->nu_posicao->FldTagCaption(3) <> "" ? $this->nu_posicao->FldTagCaption(3) : $this->nu_posicao->CurrentValue;
						break;
					case $this->nu_posicao->FldTagValue(4):
						$this->nu_posicao->ViewValue = $this->nu_posicao->FldTagCaption(4) <> "" ? $this->nu_posicao->FldTagCaption(4) : $this->nu_posicao->CurrentValue;
						break;
					case $this->nu_posicao->FldTagValue(5):
						$this->nu_posicao->ViewValue = $this->nu_posicao->FldTagCaption(5) <> "" ? $this->nu_posicao->FldTagCaption(5) : $this->nu_posicao->CurrentValue;
						break;
					case $this->nu_posicao->FldTagValue(6):
						$this->nu_posicao->ViewValue = $this->nu_posicao->FldTagCaption(6) <> "" ? $this->nu_posicao->FldTagCaption(6) : $this->nu_posicao->CurrentValue;
						break;
					case $this->nu_posicao->FldTagValue(7):
						$this->nu_posicao->ViewValue = $this->nu_posicao->FldTagCaption(7) <> "" ? $this->nu_posicao->FldTagCaption(7) : $this->nu_posicao->CurrentValue;
						break;
					case $this->nu_posicao->FldTagValue(8):
						$this->nu_posicao->ViewValue = $this->nu_posicao->FldTagCaption(8) <> "" ? $this->nu_posicao->FldTagCaption(8) : $this->nu_posicao->CurrentValue;
						break;
					case $this->nu_posicao->FldTagValue(9):
						$this->nu_posicao->ViewValue = $this->nu_posicao->FldTagCaption(9) <> "" ? $this->nu_posicao->FldTagCaption(9) : $this->nu_posicao->CurrentValue;
						break;
					default:
						$this->nu_posicao->ViewValue = $this->nu_posicao->CurrentValue;
				}
			} else {
				$this->nu_posicao->ViewValue = NULL;
			}
			$this->nu_posicao->ViewCustomAttributes = "";

			// vr_larguraEmPx
			$this->vr_larguraEmPx->ViewValue = $this->vr_larguraEmPx->CurrentValue;
			$this->vr_larguraEmPx->ViewCustomAttributes = "";

			// vr_alturaEmPx
			$this->vr_alturaEmPx->ViewValue = $this->vr_alturaEmPx->CurrentValue;
			$this->vr_alturaEmPx->ViewCustomAttributes = "";

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

			// nu_perfil
			$this->nu_perfil->LinkCustomAttributes = "";
			$this->nu_perfil->HrefValue = "";
			$this->nu_perfil->TooltipValue = "";

			// nu_widget
			$this->nu_widget->LinkCustomAttributes = "";
			$this->nu_widget->HrefValue = "";
			$this->nu_widget->TooltipValue = "";

			// no_titulo
			$this->no_titulo->LinkCustomAttributes = "";
			$this->no_titulo->HrefValue = "";
			$this->no_titulo->TooltipValue = "";

			// no_legTexto
			$this->no_legTexto->LinkCustomAttributes = "";
			$this->no_legTexto->HrefValue = "";
			$this->no_legTexto->TooltipValue = "";

			// no_legValores
			$this->no_legValores->LinkCustomAttributes = "";
			$this->no_legValores->HrefValue = "";
			$this->no_legValores->TooltipValue = "";

			// nu_posicao
			$this->nu_posicao->LinkCustomAttributes = "";
			$this->nu_posicao->HrefValue = "";
			$this->nu_posicao->TooltipValue = "";

			// vr_larguraEmPx
			$this->vr_larguraEmPx->LinkCustomAttributes = "";
			$this->vr_larguraEmPx->HrefValue = "";
			$this->vr_larguraEmPx->TooltipValue = "";

			// vr_alturaEmPx
			$this->vr_alturaEmPx->LinkCustomAttributes = "";
			$this->vr_alturaEmPx->HrefValue = "";
			$this->vr_alturaEmPx->TooltipValue = "";

			// ic_ativo
			$this->ic_ativo->LinkCustomAttributes = "";
			$this->ic_ativo->HrefValue = "";
			$this->ic_ativo->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// nu_perfil
			$this->nu_perfil->EditCustomAttributes = "";
			if ($this->nu_perfil->VirtualValue <> "") {
				$this->nu_perfil->ViewValue = $this->nu_perfil->VirtualValue;
			} else {
			if (strval($this->nu_perfil->CurrentValue) <> "") {
				$sFilterWrk = "[nu_level]" . ew_SearchString("=", $this->nu_perfil->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_level], [no_level] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario_permissoes]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_perfil, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_level] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_perfil->EditValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_perfil->EditValue = $this->nu_perfil->CurrentValue;
				}
			} else {
				$this->nu_perfil->EditValue = NULL;
			}
			}
			$this->nu_perfil->ViewCustomAttributes = "";

			// nu_widget
			$this->nu_widget->EditCustomAttributes = "";
			if (strval($this->nu_widget->CurrentValue) <> "") {
				$sFilterWrk = "[nu_widget]" . ew_SearchString("=", $this->nu_widget->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_widget], [no_widget] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[widget]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_widget, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [ic_ativo] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_widget->EditValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_widget->EditValue = $this->nu_widget->CurrentValue;
				}
			} else {
				$this->nu_widget->EditValue = NULL;
			}
			$this->nu_widget->ViewCustomAttributes = "";

			// no_titulo
			$this->no_titulo->EditCustomAttributes = "";
			$this->no_titulo->EditValue = ew_HtmlEncode($this->no_titulo->CurrentValue);
			$this->no_titulo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_titulo->FldCaption()));

			// no_legTexto
			$this->no_legTexto->EditCustomAttributes = "";
			$this->no_legTexto->EditValue = ew_HtmlEncode($this->no_legTexto->CurrentValue);
			$this->no_legTexto->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_legTexto->FldCaption()));

			// no_legValores
			$this->no_legValores->EditCustomAttributes = "";
			$this->no_legValores->EditValue = ew_HtmlEncode($this->no_legValores->CurrentValue);
			$this->no_legValores->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_legValores->FldCaption()));

			// nu_posicao
			$this->nu_posicao->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->nu_posicao->FldTagValue(1), $this->nu_posicao->FldTagCaption(1) <> "" ? $this->nu_posicao->FldTagCaption(1) : $this->nu_posicao->FldTagValue(1));
			$arwrk[] = array($this->nu_posicao->FldTagValue(2), $this->nu_posicao->FldTagCaption(2) <> "" ? $this->nu_posicao->FldTagCaption(2) : $this->nu_posicao->FldTagValue(2));
			$arwrk[] = array($this->nu_posicao->FldTagValue(3), $this->nu_posicao->FldTagCaption(3) <> "" ? $this->nu_posicao->FldTagCaption(3) : $this->nu_posicao->FldTagValue(3));
			$arwrk[] = array($this->nu_posicao->FldTagValue(4), $this->nu_posicao->FldTagCaption(4) <> "" ? $this->nu_posicao->FldTagCaption(4) : $this->nu_posicao->FldTagValue(4));
			$arwrk[] = array($this->nu_posicao->FldTagValue(5), $this->nu_posicao->FldTagCaption(5) <> "" ? $this->nu_posicao->FldTagCaption(5) : $this->nu_posicao->FldTagValue(5));
			$arwrk[] = array($this->nu_posicao->FldTagValue(6), $this->nu_posicao->FldTagCaption(6) <> "" ? $this->nu_posicao->FldTagCaption(6) : $this->nu_posicao->FldTagValue(6));
			$arwrk[] = array($this->nu_posicao->FldTagValue(7), $this->nu_posicao->FldTagCaption(7) <> "" ? $this->nu_posicao->FldTagCaption(7) : $this->nu_posicao->FldTagValue(7));
			$arwrk[] = array($this->nu_posicao->FldTagValue(8), $this->nu_posicao->FldTagCaption(8) <> "" ? $this->nu_posicao->FldTagCaption(8) : $this->nu_posicao->FldTagValue(8));
			$arwrk[] = array($this->nu_posicao->FldTagValue(9), $this->nu_posicao->FldTagCaption(9) <> "" ? $this->nu_posicao->FldTagCaption(9) : $this->nu_posicao->FldTagValue(9));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->nu_posicao->EditValue = $arwrk;

			// vr_larguraEmPx
			$this->vr_larguraEmPx->EditCustomAttributes = "";
			$this->vr_larguraEmPx->EditValue = ew_HtmlEncode($this->vr_larguraEmPx->CurrentValue);
			$this->vr_larguraEmPx->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_larguraEmPx->FldCaption()));

			// vr_alturaEmPx
			$this->vr_alturaEmPx->EditCustomAttributes = "";
			$this->vr_alturaEmPx->EditValue = ew_HtmlEncode($this->vr_alturaEmPx->CurrentValue);
			$this->vr_alturaEmPx->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_alturaEmPx->FldCaption()));

			// ic_ativo
			$this->ic_ativo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_ativo->FldTagValue(1), $this->ic_ativo->FldTagCaption(1) <> "" ? $this->ic_ativo->FldTagCaption(1) : $this->ic_ativo->FldTagValue(1));
			$arwrk[] = array($this->ic_ativo->FldTagValue(2), $this->ic_ativo->FldTagCaption(2) <> "" ? $this->ic_ativo->FldTagCaption(2) : $this->ic_ativo->FldTagValue(2));
			$this->ic_ativo->EditValue = $arwrk;

			// Edit refer script
			// nu_perfil

			$this->nu_perfil->HrefValue = "";

			// nu_widget
			$this->nu_widget->HrefValue = "";

			// no_titulo
			$this->no_titulo->HrefValue = "";

			// no_legTexto
			$this->no_legTexto->HrefValue = "";

			// no_legValores
			$this->no_legValores->HrefValue = "";

			// nu_posicao
			$this->nu_posicao->HrefValue = "";

			// vr_larguraEmPx
			$this->vr_larguraEmPx->HrefValue = "";

			// vr_alturaEmPx
			$this->vr_alturaEmPx->HrefValue = "";

			// ic_ativo
			$this->ic_ativo->HrefValue = "";
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
		if (!$this->no_titulo->FldIsDetailKey && !is_null($this->no_titulo->FormValue) && $this->no_titulo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_titulo->FldCaption());
		}
		if (!$this->nu_posicao->FldIsDetailKey && !is_null($this->nu_posicao->FormValue) && $this->nu_posicao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_posicao->FldCaption());
		}
		if (!ew_CheckInteger($this->vr_larguraEmPx->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_larguraEmPx->FldErrMsg());
		}
		if (!ew_CheckInteger($this->vr_alturaEmPx->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_alturaEmPx->FldErrMsg());
		}
		if ($this->ic_ativo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_ativo->FldCaption());
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

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// no_titulo
			$this->no_titulo->SetDbValueDef($rsnew, $this->no_titulo->CurrentValue, NULL, $this->no_titulo->ReadOnly);

			// no_legTexto
			$this->no_legTexto->SetDbValueDef($rsnew, $this->no_legTexto->CurrentValue, NULL, $this->no_legTexto->ReadOnly);

			// no_legValores
			$this->no_legValores->SetDbValueDef($rsnew, $this->no_legValores->CurrentValue, NULL, $this->no_legValores->ReadOnly);

			// nu_posicao
			$this->nu_posicao->SetDbValueDef($rsnew, $this->nu_posicao->CurrentValue, NULL, $this->nu_posicao->ReadOnly);

			// vr_larguraEmPx
			$this->vr_larguraEmPx->SetDbValueDef($rsnew, $this->vr_larguraEmPx->CurrentValue, NULL, $this->vr_larguraEmPx->ReadOnly);

			// vr_alturaEmPx
			$this->vr_alturaEmPx->SetDbValueDef($rsnew, $this->vr_alturaEmPx->CurrentValue, NULL, $this->vr_alturaEmPx->ReadOnly);

			// ic_ativo
			$this->ic_ativo->SetDbValueDef($rsnew, $this->ic_ativo->CurrentValue, NULL, $this->ic_ativo->ReadOnly);

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
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "widget_perfillist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("edit");
		$Breadcrumb->Add("edit", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
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
if (!isset($widget_perfil_edit)) $widget_perfil_edit = new cwidget_perfil_edit();

// Page init
$widget_perfil_edit->Page_Init();

// Page main
$widget_perfil_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$widget_perfil_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var widget_perfil_edit = new ew_Page("widget_perfil_edit");
widget_perfil_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = widget_perfil_edit.PageID; // For backward compatibility

// Form object
var fwidget_perfiledit = new ew_Form("fwidget_perfiledit");

// Validate form
fwidget_perfiledit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_no_titulo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($widget_perfil->no_titulo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_posicao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($widget_perfil->nu_posicao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_larguraEmPx");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($widget_perfil->vr_larguraEmPx->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vr_alturaEmPx");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($widget_perfil->vr_alturaEmPx->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ic_ativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($widget_perfil->ic_ativo->FldCaption()) ?>");

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
fwidget_perfiledit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fwidget_perfiledit.ValidateRequired = true;
<?php } else { ?>
fwidget_perfiledit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fwidget_perfiledit.Lists["x_nu_perfil"] = {"LinkField":"x_nu_level","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_level","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fwidget_perfiledit.Lists["x_nu_widget"] = {"LinkField":"x_nu_widget","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_widget","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $widget_perfil_edit->ShowPageHeader(); ?>
<?php
$widget_perfil_edit->ShowMessage();
?>
<form name="fwidget_perfiledit" id="fwidget_perfiledit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="widget_perfil">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_widget_perfiledit" class="table table-bordered table-striped">
<?php if ($widget_perfil->nu_perfil->Visible) { // nu_perfil ?>
	<tr id="r_nu_perfil">
		<td><span id="elh_widget_perfil_nu_perfil"><?php echo $widget_perfil->nu_perfil->FldCaption() ?></span></td>
		<td<?php echo $widget_perfil->nu_perfil->CellAttributes() ?>>
<span id="el_widget_perfil_nu_perfil" class="control-group">
<span<?php echo $widget_perfil->nu_perfil->ViewAttributes() ?>>
<?php echo $widget_perfil->nu_perfil->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_perfil" name="x_nu_perfil" id="x_nu_perfil" value="<?php echo ew_HtmlEncode($widget_perfil->nu_perfil->CurrentValue) ?>">
<?php echo $widget_perfil->nu_perfil->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($widget_perfil->nu_widget->Visible) { // nu_widget ?>
	<tr id="r_nu_widget">
		<td><span id="elh_widget_perfil_nu_widget"><?php echo $widget_perfil->nu_widget->FldCaption() ?></span></td>
		<td<?php echo $widget_perfil->nu_widget->CellAttributes() ?>>
<span id="el_widget_perfil_nu_widget" class="control-group">
<span<?php echo $widget_perfil->nu_widget->ViewAttributes() ?>>
<?php echo $widget_perfil->nu_widget->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_widget" name="x_nu_widget" id="x_nu_widget" value="<?php echo ew_HtmlEncode($widget_perfil->nu_widget->CurrentValue) ?>">
<?php echo $widget_perfil->nu_widget->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($widget_perfil->no_titulo->Visible) { // no_titulo ?>
	<tr id="r_no_titulo">
		<td><span id="elh_widget_perfil_no_titulo"><?php echo $widget_perfil->no_titulo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $widget_perfil->no_titulo->CellAttributes() ?>>
<span id="el_widget_perfil_no_titulo" class="control-group">
<input type="text" data-field="x_no_titulo" name="x_no_titulo" id="x_no_titulo" size="30" maxlength="100" placeholder="<?php echo $widget_perfil->no_titulo->PlaceHolder ?>" value="<?php echo $widget_perfil->no_titulo->EditValue ?>"<?php echo $widget_perfil->no_titulo->EditAttributes() ?>>
</span>
<?php echo $widget_perfil->no_titulo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($widget_perfil->no_legTexto->Visible) { // no_legTexto ?>
	<tr id="r_no_legTexto">
		<td><span id="elh_widget_perfil_no_legTexto"><?php echo $widget_perfil->no_legTexto->FldCaption() ?></span></td>
		<td<?php echo $widget_perfil->no_legTexto->CellAttributes() ?>>
<span id="el_widget_perfil_no_legTexto" class="control-group">
<input type="text" data-field="x_no_legTexto" name="x_no_legTexto" id="x_no_legTexto" size="30" maxlength="100" placeholder="<?php echo $widget_perfil->no_legTexto->PlaceHolder ?>" value="<?php echo $widget_perfil->no_legTexto->EditValue ?>"<?php echo $widget_perfil->no_legTexto->EditAttributes() ?>>
</span>
<?php echo $widget_perfil->no_legTexto->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($widget_perfil->no_legValores->Visible) { // no_legValores ?>
	<tr id="r_no_legValores">
		<td><span id="elh_widget_perfil_no_legValores"><?php echo $widget_perfil->no_legValores->FldCaption() ?></span></td>
		<td<?php echo $widget_perfil->no_legValores->CellAttributes() ?>>
<span id="el_widget_perfil_no_legValores" class="control-group">
<input type="text" data-field="x_no_legValores" name="x_no_legValores" id="x_no_legValores" size="30" maxlength="100" placeholder="<?php echo $widget_perfil->no_legValores->PlaceHolder ?>" value="<?php echo $widget_perfil->no_legValores->EditValue ?>"<?php echo $widget_perfil->no_legValores->EditAttributes() ?>>
</span>
<?php echo $widget_perfil->no_legValores->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($widget_perfil->nu_posicao->Visible) { // nu_posicao ?>
	<tr id="r_nu_posicao">
		<td><span id="elh_widget_perfil_nu_posicao"><?php echo $widget_perfil->nu_posicao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $widget_perfil->nu_posicao->CellAttributes() ?>>
<span id="el_widget_perfil_nu_posicao" class="control-group">
<select data-field="x_nu_posicao" id="x_nu_posicao" name="x_nu_posicao"<?php echo $widget_perfil->nu_posicao->EditAttributes() ?>>
<?php
if (is_array($widget_perfil->nu_posicao->EditValue)) {
	$arwrk = $widget_perfil->nu_posicao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($widget_perfil->nu_posicao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $widget_perfil->nu_posicao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($widget_perfil->vr_larguraEmPx->Visible) { // vr_larguraEmPx ?>
	<tr id="r_vr_larguraEmPx">
		<td><span id="elh_widget_perfil_vr_larguraEmPx"><?php echo $widget_perfil->vr_larguraEmPx->FldCaption() ?></span></td>
		<td<?php echo $widget_perfil->vr_larguraEmPx->CellAttributes() ?>>
<span id="el_widget_perfil_vr_larguraEmPx" class="control-group">
<input type="text" data-field="x_vr_larguraEmPx" name="x_vr_larguraEmPx" id="x_vr_larguraEmPx" size="30" placeholder="<?php echo $widget_perfil->vr_larguraEmPx->PlaceHolder ?>" value="<?php echo $widget_perfil->vr_larguraEmPx->EditValue ?>"<?php echo $widget_perfil->vr_larguraEmPx->EditAttributes() ?>>
</span>
<?php echo $widget_perfil->vr_larguraEmPx->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($widget_perfil->vr_alturaEmPx->Visible) { // vr_alturaEmPx ?>
	<tr id="r_vr_alturaEmPx">
		<td><span id="elh_widget_perfil_vr_alturaEmPx"><?php echo $widget_perfil->vr_alturaEmPx->FldCaption() ?></span></td>
		<td<?php echo $widget_perfil->vr_alturaEmPx->CellAttributes() ?>>
<span id="el_widget_perfil_vr_alturaEmPx" class="control-group">
<input type="text" data-field="x_vr_alturaEmPx" name="x_vr_alturaEmPx" id="x_vr_alturaEmPx" size="30" placeholder="<?php echo $widget_perfil->vr_alturaEmPx->PlaceHolder ?>" value="<?php echo $widget_perfil->vr_alturaEmPx->EditValue ?>"<?php echo $widget_perfil->vr_alturaEmPx->EditAttributes() ?>>
</span>
<?php echo $widget_perfil->vr_alturaEmPx->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($widget_perfil->ic_ativo->Visible) { // ic_ativo ?>
	<tr id="r_ic_ativo">
		<td><span id="elh_widget_perfil_ic_ativo"><?php echo $widget_perfil->ic_ativo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $widget_perfil->ic_ativo->CellAttributes() ?>>
<span id="el_widget_perfil_ic_ativo" class="control-group">
<div id="tp_x_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_ativo" id="x_ic_ativo" value="{value}"<?php echo $widget_perfil->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $widget_perfil->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($widget_perfil->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x_ic_ativo" id="x_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $widget_perfil->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $widget_perfil->ic_ativo->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fwidget_perfiledit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$widget_perfil_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$widget_perfil_edit->Page_Terminate();
?>
