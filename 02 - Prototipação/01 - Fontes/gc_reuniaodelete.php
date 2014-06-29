<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "gc_reuniaoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$gc_reuniao_delete = NULL; // Initialize page object first

class cgc_reuniao_delete extends cgc_reuniao {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'gc_reuniao';

	// Page object name
	var $PageObjName = 'gc_reuniao_delete';

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

		// Table object (gc_reuniao)
		if (!isset($GLOBALS["gc_reuniao"])) {
			$GLOBALS["gc_reuniao"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["gc_reuniao"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'gc_reuniao', TRUE);

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
			$this->Page_Terminate("gc_reuniaolist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action
		$this->nu_reuniao->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			$this->Page_Terminate("gc_reuniaolist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in gc_reuniao class, gc_reuniaoinfo.php

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
		$this->nu_reuniao->setDbValue($rs->fields('nu_reuniao'));
		$this->nu_grupoOuComite->setDbValue($rs->fields('nu_grupoOuComite'));
		$this->ds_pauta->setDbValue($rs->fields('ds_pauta'));
		$this->no_local->setDbValue($rs->fields('no_local'));
		$this->dt_reuniao->setDbValue($rs->fields('dt_reuniao'));
		$this->hh_inicio->setDbValue($rs->fields('hh_inicio'));
		$this->hh_fim->setDbValue($rs->fields('hh_fim'));
		$this->ic_situacao->setDbValue($rs->fields('ic_situacao'));
		$this->nu_usuario->setDbValue($rs->fields('nu_usuario'));
		$this->ts_datahora->setDbValue($rs->fields('ts_datahora'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_reuniao->DbValue = $row['nu_reuniao'];
		$this->nu_grupoOuComite->DbValue = $row['nu_grupoOuComite'];
		$this->ds_pauta->DbValue = $row['ds_pauta'];
		$this->no_local->DbValue = $row['no_local'];
		$this->dt_reuniao->DbValue = $row['dt_reuniao'];
		$this->hh_inicio->DbValue = $row['hh_inicio'];
		$this->hh_fim->DbValue = $row['hh_fim'];
		$this->ic_situacao->DbValue = $row['ic_situacao'];
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
		// nu_reuniao
		// nu_grupoOuComite
		// ds_pauta
		// no_local
		// dt_reuniao
		// hh_inicio
		// hh_fim
		// ic_situacao
		// nu_usuario
		// ts_datahora

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_reuniao
			$this->nu_reuniao->ViewValue = $this->nu_reuniao->CurrentValue;
			$this->nu_reuniao->ViewCustomAttributes = "";

			// nu_grupoOuComite
			if (strval($this->nu_grupoOuComite->CurrentValue) <> "") {
				$sFilterWrk = "[nu_gpComite]" . ew_SearchString("=", $this->nu_grupoOuComite->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_gpComite], [no_gpComite] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[gpcomite]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_grupoOuComite, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_grupoOuComite->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_grupoOuComite->ViewValue = $this->nu_grupoOuComite->CurrentValue;
				}
			} else {
				$this->nu_grupoOuComite->ViewValue = NULL;
			}
			$this->nu_grupoOuComite->ViewCustomAttributes = "";

			// no_local
			$this->no_local->ViewValue = $this->no_local->CurrentValue;
			$this->no_local->ViewCustomAttributes = "";

			// dt_reuniao
			$this->dt_reuniao->ViewValue = $this->dt_reuniao->CurrentValue;
			$this->dt_reuniao->ViewValue = ew_FormatDateTime($this->dt_reuniao->ViewValue, 7);
			$this->dt_reuniao->ViewCustomAttributes = "";

			// hh_inicio
			$this->hh_inicio->ViewValue = $this->hh_inicio->CurrentValue;
			$this->hh_inicio->ViewValue = ew_FormatDateTime($this->hh_inicio->ViewValue, 4);
			$this->hh_inicio->ViewCustomAttributes = "";

			// hh_fim
			$this->hh_fim->ViewValue = $this->hh_fim->CurrentValue;
			$this->hh_fim->ViewValue = ew_FormatDateTime($this->hh_fim->ViewValue, 4);
			$this->hh_fim->ViewCustomAttributes = "";

			// ic_situacao
			if (strval($this->ic_situacao->CurrentValue) <> "") {
				switch ($this->ic_situacao->CurrentValue) {
					case $this->ic_situacao->FldTagValue(1):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(1) <> "" ? $this->ic_situacao->FldTagCaption(1) : $this->ic_situacao->CurrentValue;
						break;
					case $this->ic_situacao->FldTagValue(2):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(2) <> "" ? $this->ic_situacao->FldTagCaption(2) : $this->ic_situacao->CurrentValue;
						break;
					case $this->ic_situacao->FldTagValue(3):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(3) <> "" ? $this->ic_situacao->FldTagCaption(3) : $this->ic_situacao->CurrentValue;
						break;
					default:
						$this->ic_situacao->ViewValue = $this->ic_situacao->CurrentValue;
				}
			} else {
				$this->ic_situacao->ViewValue = NULL;
			}
			$this->ic_situacao->ViewCustomAttributes = "";

			// nu_usuario
			$this->nu_usuario->ViewValue = $this->nu_usuario->CurrentValue;
			$this->nu_usuario->ViewCustomAttributes = "";

			// ts_datahora
			$this->ts_datahora->ViewValue = $this->ts_datahora->CurrentValue;
			$this->ts_datahora->ViewValue = ew_FormatDateTime($this->ts_datahora->ViewValue, 7);
			$this->ts_datahora->ViewCustomAttributes = "";

			// nu_reuniao
			$this->nu_reuniao->LinkCustomAttributes = "";
			$this->nu_reuniao->HrefValue = "";
			$this->nu_reuniao->TooltipValue = "";

			// nu_grupoOuComite
			$this->nu_grupoOuComite->LinkCustomAttributes = "";
			$this->nu_grupoOuComite->HrefValue = "";
			$this->nu_grupoOuComite->TooltipValue = "";

			// no_local
			$this->no_local->LinkCustomAttributes = "";
			$this->no_local->HrefValue = "";
			$this->no_local->TooltipValue = "";

			// dt_reuniao
			$this->dt_reuniao->LinkCustomAttributes = "";
			$this->dt_reuniao->HrefValue = "";
			$this->dt_reuniao->TooltipValue = "";

			// hh_inicio
			$this->hh_inicio->LinkCustomAttributes = "";
			$this->hh_inicio->HrefValue = "";
			$this->hh_inicio->TooltipValue = "";

			// hh_fim
			$this->hh_fim->LinkCustomAttributes = "";
			$this->hh_fim->HrefValue = "";
			$this->hh_fim->TooltipValue = "";

			// ic_situacao
			$this->ic_situacao->LinkCustomAttributes = "";
			$this->ic_situacao->HrefValue = "";
			$this->ic_situacao->TooltipValue = "";
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
				$sThisKey .= $row['nu_reuniao'];
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "gc_reuniaolist.php", $this->TableVar);
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
if (!isset($gc_reuniao_delete)) $gc_reuniao_delete = new cgc_reuniao_delete();

// Page init
$gc_reuniao_delete->Page_Init();

// Page main
$gc_reuniao_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$gc_reuniao_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var gc_reuniao_delete = new ew_Page("gc_reuniao_delete");
gc_reuniao_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = gc_reuniao_delete.PageID; // For backward compatibility

// Form object
var fgc_reuniaodelete = new ew_Form("fgc_reuniaodelete");

// Form_CustomValidate event
fgc_reuniaodelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgc_reuniaodelete.ValidateRequired = true;
<?php } else { ?>
fgc_reuniaodelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fgc_reuniaodelete.Lists["x_nu_grupoOuComite"] = {"LinkField":"x_nu_gpComite","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_gpComite","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($gc_reuniao_delete->Recordset = $gc_reuniao_delete->LoadRecordset())
	$gc_reuniao_deleteTotalRecs = $gc_reuniao_delete->Recordset->RecordCount(); // Get record count
if ($gc_reuniao_deleteTotalRecs <= 0) { // No record found, exit
	if ($gc_reuniao_delete->Recordset)
		$gc_reuniao_delete->Recordset->Close();
	$gc_reuniao_delete->Page_Terminate("gc_reuniaolist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $gc_reuniao_delete->ShowPageHeader(); ?>
<?php
$gc_reuniao_delete->ShowMessage();
?>
<form name="fgc_reuniaodelete" id="fgc_reuniaodelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="gc_reuniao">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($gc_reuniao_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_gc_reuniaodelete" class="ewTable ewTableSeparate">
<?php echo $gc_reuniao->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_gc_reuniao_nu_reuniao" class="gc_reuniao_nu_reuniao"><?php echo $gc_reuniao->nu_reuniao->FldCaption() ?></span></td>
		<td><span id="elh_gc_reuniao_nu_grupoOuComite" class="gc_reuniao_nu_grupoOuComite"><?php echo $gc_reuniao->nu_grupoOuComite->FldCaption() ?></span></td>
		<td><span id="elh_gc_reuniao_no_local" class="gc_reuniao_no_local"><?php echo $gc_reuniao->no_local->FldCaption() ?></span></td>
		<td><span id="elh_gc_reuniao_dt_reuniao" class="gc_reuniao_dt_reuniao"><?php echo $gc_reuniao->dt_reuniao->FldCaption() ?></span></td>
		<td><span id="elh_gc_reuniao_hh_inicio" class="gc_reuniao_hh_inicio"><?php echo $gc_reuniao->hh_inicio->FldCaption() ?></span></td>
		<td><span id="elh_gc_reuniao_hh_fim" class="gc_reuniao_hh_fim"><?php echo $gc_reuniao->hh_fim->FldCaption() ?></span></td>
		<td><span id="elh_gc_reuniao_ic_situacao" class="gc_reuniao_ic_situacao"><?php echo $gc_reuniao->ic_situacao->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$gc_reuniao_delete->RecCnt = 0;
$i = 0;
while (!$gc_reuniao_delete->Recordset->EOF) {
	$gc_reuniao_delete->RecCnt++;
	$gc_reuniao_delete->RowCnt++;

	// Set row properties
	$gc_reuniao->ResetAttrs();
	$gc_reuniao->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$gc_reuniao_delete->LoadRowValues($gc_reuniao_delete->Recordset);

	// Render row
	$gc_reuniao_delete->RenderRow();
?>
	<tr<?php echo $gc_reuniao->RowAttributes() ?>>
		<td<?php echo $gc_reuniao->nu_reuniao->CellAttributes() ?>>
<span id="el<?php echo $gc_reuniao_delete->RowCnt ?>_gc_reuniao_nu_reuniao" class="control-group gc_reuniao_nu_reuniao">
<span<?php echo $gc_reuniao->nu_reuniao->ViewAttributes() ?>>
<?php echo $gc_reuniao->nu_reuniao->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $gc_reuniao->nu_grupoOuComite->CellAttributes() ?>>
<span id="el<?php echo $gc_reuniao_delete->RowCnt ?>_gc_reuniao_nu_grupoOuComite" class="control-group gc_reuniao_nu_grupoOuComite">
<span<?php echo $gc_reuniao->nu_grupoOuComite->ViewAttributes() ?>>
<?php echo $gc_reuniao->nu_grupoOuComite->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $gc_reuniao->no_local->CellAttributes() ?>>
<span id="el<?php echo $gc_reuniao_delete->RowCnt ?>_gc_reuniao_no_local" class="control-group gc_reuniao_no_local">
<span<?php echo $gc_reuniao->no_local->ViewAttributes() ?>>
<?php echo $gc_reuniao->no_local->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $gc_reuniao->dt_reuniao->CellAttributes() ?>>
<span id="el<?php echo $gc_reuniao_delete->RowCnt ?>_gc_reuniao_dt_reuniao" class="control-group gc_reuniao_dt_reuniao">
<span<?php echo $gc_reuniao->dt_reuniao->ViewAttributes() ?>>
<?php echo $gc_reuniao->dt_reuniao->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $gc_reuniao->hh_inicio->CellAttributes() ?>>
<span id="el<?php echo $gc_reuniao_delete->RowCnt ?>_gc_reuniao_hh_inicio" class="control-group gc_reuniao_hh_inicio">
<span<?php echo $gc_reuniao->hh_inicio->ViewAttributes() ?>>
<?php echo $gc_reuniao->hh_inicio->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $gc_reuniao->hh_fim->CellAttributes() ?>>
<span id="el<?php echo $gc_reuniao_delete->RowCnt ?>_gc_reuniao_hh_fim" class="control-group gc_reuniao_hh_fim">
<span<?php echo $gc_reuniao->hh_fim->ViewAttributes() ?>>
<?php echo $gc_reuniao->hh_fim->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $gc_reuniao->ic_situacao->CellAttributes() ?>>
<span id="el<?php echo $gc_reuniao_delete->RowCnt ?>_gc_reuniao_ic_situacao" class="control-group gc_reuniao_ic_situacao">
<span<?php echo $gc_reuniao->ic_situacao->ViewAttributes() ?>>
<?php echo $gc_reuniao->ic_situacao->ListViewValue() ?></span>
</span>
</td>
	</tr>
<?php
	$gc_reuniao_delete->Recordset->MoveNext();
}
$gc_reuniao_delete->Recordset->Close();
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
fgc_reuniaodelete.Init();
</script>
<?php
$gc_reuniao_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$gc_reuniao_delete->Page_Terminate();
?>
