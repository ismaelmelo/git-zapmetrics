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

$widget_perfil_delete = NULL; // Initialize page object first

class cwidget_perfil_delete extends cwidget_perfil {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'widget_perfil';

	// Page object name
	var $PageObjName = 'widget_perfil_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("widget_perfillist.php");
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
			$this->Page_Terminate("widget_perfillist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in widget_perfil class, widget_perfilinfo.php

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
				$sThisKey .= $row['nu_perfil'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['nu_widget'];
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "widget_perfillist.php", $this->TableVar);
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
if (!isset($widget_perfil_delete)) $widget_perfil_delete = new cwidget_perfil_delete();

// Page init
$widget_perfil_delete->Page_Init();

// Page main
$widget_perfil_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$widget_perfil_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var widget_perfil_delete = new ew_Page("widget_perfil_delete");
widget_perfil_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = widget_perfil_delete.PageID; // For backward compatibility

// Form object
var fwidget_perfildelete = new ew_Form("fwidget_perfildelete");

// Form_CustomValidate event
fwidget_perfildelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fwidget_perfildelete.ValidateRequired = true;
<?php } else { ?>
fwidget_perfildelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fwidget_perfildelete.Lists["x_nu_perfil"] = {"LinkField":"x_nu_level","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_level","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fwidget_perfildelete.Lists["x_nu_widget"] = {"LinkField":"x_nu_widget","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_widget","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($widget_perfil_delete->Recordset = $widget_perfil_delete->LoadRecordset())
	$widget_perfil_deleteTotalRecs = $widget_perfil_delete->Recordset->RecordCount(); // Get record count
if ($widget_perfil_deleteTotalRecs <= 0) { // No record found, exit
	if ($widget_perfil_delete->Recordset)
		$widget_perfil_delete->Recordset->Close();
	$widget_perfil_delete->Page_Terminate("widget_perfillist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $widget_perfil_delete->ShowPageHeader(); ?>
<?php
$widget_perfil_delete->ShowMessage();
?>
<form name="fwidget_perfildelete" id="fwidget_perfildelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="widget_perfil">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($widget_perfil_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_widget_perfildelete" class="ewTable ewTableSeparate">
<?php echo $widget_perfil->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_widget_perfil_nu_perfil" class="widget_perfil_nu_perfil"><?php echo $widget_perfil->nu_perfil->FldCaption() ?></span></td>
		<td><span id="elh_widget_perfil_nu_widget" class="widget_perfil_nu_widget"><?php echo $widget_perfil->nu_widget->FldCaption() ?></span></td>
		<td><span id="elh_widget_perfil_no_titulo" class="widget_perfil_no_titulo"><?php echo $widget_perfil->no_titulo->FldCaption() ?></span></td>
		<td><span id="elh_widget_perfil_no_legTexto" class="widget_perfil_no_legTexto"><?php echo $widget_perfil->no_legTexto->FldCaption() ?></span></td>
		<td><span id="elh_widget_perfil_no_legValores" class="widget_perfil_no_legValores"><?php echo $widget_perfil->no_legValores->FldCaption() ?></span></td>
		<td><span id="elh_widget_perfil_nu_posicao" class="widget_perfil_nu_posicao"><?php echo $widget_perfil->nu_posicao->FldCaption() ?></span></td>
		<td><span id="elh_widget_perfil_vr_larguraEmPx" class="widget_perfil_vr_larguraEmPx"><?php echo $widget_perfil->vr_larguraEmPx->FldCaption() ?></span></td>
		<td><span id="elh_widget_perfil_vr_alturaEmPx" class="widget_perfil_vr_alturaEmPx"><?php echo $widget_perfil->vr_alturaEmPx->FldCaption() ?></span></td>
		<td><span id="elh_widget_perfil_ic_ativo" class="widget_perfil_ic_ativo"><?php echo $widget_perfil->ic_ativo->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$widget_perfil_delete->RecCnt = 0;
$i = 0;
while (!$widget_perfil_delete->Recordset->EOF) {
	$widget_perfil_delete->RecCnt++;
	$widget_perfil_delete->RowCnt++;

	// Set row properties
	$widget_perfil->ResetAttrs();
	$widget_perfil->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$widget_perfil_delete->LoadRowValues($widget_perfil_delete->Recordset);

	// Render row
	$widget_perfil_delete->RenderRow();
?>
	<tr<?php echo $widget_perfil->RowAttributes() ?>>
		<td<?php echo $widget_perfil->nu_perfil->CellAttributes() ?>>
<span id="el<?php echo $widget_perfil_delete->RowCnt ?>_widget_perfil_nu_perfil" class="control-group widget_perfil_nu_perfil">
<span<?php echo $widget_perfil->nu_perfil->ViewAttributes() ?>>
<?php echo $widget_perfil->nu_perfil->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $widget_perfil->nu_widget->CellAttributes() ?>>
<span id="el<?php echo $widget_perfil_delete->RowCnt ?>_widget_perfil_nu_widget" class="control-group widget_perfil_nu_widget">
<span<?php echo $widget_perfil->nu_widget->ViewAttributes() ?>>
<?php echo $widget_perfil->nu_widget->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $widget_perfil->no_titulo->CellAttributes() ?>>
<span id="el<?php echo $widget_perfil_delete->RowCnt ?>_widget_perfil_no_titulo" class="control-group widget_perfil_no_titulo">
<span<?php echo $widget_perfil->no_titulo->ViewAttributes() ?>>
<?php echo $widget_perfil->no_titulo->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $widget_perfil->no_legTexto->CellAttributes() ?>>
<span id="el<?php echo $widget_perfil_delete->RowCnt ?>_widget_perfil_no_legTexto" class="control-group widget_perfil_no_legTexto">
<span<?php echo $widget_perfil->no_legTexto->ViewAttributes() ?>>
<?php echo $widget_perfil->no_legTexto->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $widget_perfil->no_legValores->CellAttributes() ?>>
<span id="el<?php echo $widget_perfil_delete->RowCnt ?>_widget_perfil_no_legValores" class="control-group widget_perfil_no_legValores">
<span<?php echo $widget_perfil->no_legValores->ViewAttributes() ?>>
<?php echo $widget_perfil->no_legValores->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $widget_perfil->nu_posicao->CellAttributes() ?>>
<span id="el<?php echo $widget_perfil_delete->RowCnt ?>_widget_perfil_nu_posicao" class="control-group widget_perfil_nu_posicao">
<span<?php echo $widget_perfil->nu_posicao->ViewAttributes() ?>>
<?php echo $widget_perfil->nu_posicao->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $widget_perfil->vr_larguraEmPx->CellAttributes() ?>>
<span id="el<?php echo $widget_perfil_delete->RowCnt ?>_widget_perfil_vr_larguraEmPx" class="control-group widget_perfil_vr_larguraEmPx">
<span<?php echo $widget_perfil->vr_larguraEmPx->ViewAttributes() ?>>
<?php echo $widget_perfil->vr_larguraEmPx->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $widget_perfil->vr_alturaEmPx->CellAttributes() ?>>
<span id="el<?php echo $widget_perfil_delete->RowCnt ?>_widget_perfil_vr_alturaEmPx" class="control-group widget_perfil_vr_alturaEmPx">
<span<?php echo $widget_perfil->vr_alturaEmPx->ViewAttributes() ?>>
<?php echo $widget_perfil->vr_alturaEmPx->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $widget_perfil->ic_ativo->CellAttributes() ?>>
<span id="el<?php echo $widget_perfil_delete->RowCnt ?>_widget_perfil_ic_ativo" class="control-group widget_perfil_ic_ativo">
<span<?php echo $widget_perfil->ic_ativo->ViewAttributes() ?>>
<?php echo $widget_perfil->ic_ativo->ListViewValue() ?></span>
</span>
</td>
	</tr>
<?php
	$widget_perfil_delete->Recordset->MoveNext();
}
$widget_perfil_delete->Recordset->Close();
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
fwidget_perfildelete.Init();
</script>
<?php
$widget_perfil_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$widget_perfil_delete->Page_Terminate();
?>
