<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "metaneginfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$metaneg_delete = NULL; // Initialize page object first

class cmetaneg_delete extends cmetaneg {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'metaneg';

	// Page object name
	var $PageObjName = 'metaneg_delete';

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

		// Table object (metaneg)
		if (!isset($GLOBALS["metaneg"])) {
			$GLOBALS["metaneg"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["metaneg"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'metaneg', TRUE);

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
			$this->Page_Terminate("metaneglist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action
		$this->nu_metaneg->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			$this->Page_Terminate("metaneglist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in metaneg class, metaneginfo.php

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
		$this->nu_metaneg->setDbValue($rs->fields('nu_metaneg'));
		$this->nu_periodoPei->setDbValue($rs->fields('nu_periodoPei'));
		$this->nu_necessidade->setDbValue($rs->fields('nu_necessidade'));
		$this->ic_perspectiva->setDbValue($rs->fields('ic_perspectiva'));
		$this->no_metaneg->setDbValue($rs->fields('no_metaneg'));
		$this->ds_metaneg->setDbValue($rs->fields('ds_metaneg'));
		$this->ic_situacao->setDbValue($rs->fields('ic_situacao'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_metaneg->DbValue = $row['nu_metaneg'];
		$this->nu_periodoPei->DbValue = $row['nu_periodoPei'];
		$this->nu_necessidade->DbValue = $row['nu_necessidade'];
		$this->ic_perspectiva->DbValue = $row['ic_perspectiva'];
		$this->no_metaneg->DbValue = $row['no_metaneg'];
		$this->ds_metaneg->DbValue = $row['ds_metaneg'];
		$this->ic_situacao->DbValue = $row['ic_situacao'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_metaneg
		// nu_periodoPei
		// nu_necessidade
		// ic_perspectiva
		// no_metaneg
		// ds_metaneg
		// ic_situacao

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_metaneg
			$this->nu_metaneg->ViewValue = $this->nu_metaneg->CurrentValue;
			$this->nu_metaneg->ViewCustomAttributes = "";

			// nu_periodoPei
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
			$this->nu_periodoPei->ViewCustomAttributes = "";

			// nu_necessidade
			$this->nu_necessidade->ViewValue = $this->nu_necessidade->CurrentValue;
			$this->nu_necessidade->ViewCustomAttributes = "";

			// ic_perspectiva
			if (strval($this->ic_perspectiva->CurrentValue) <> "") {
				switch ($this->ic_perspectiva->CurrentValue) {
					case $this->ic_perspectiva->FldTagValue(1):
						$this->ic_perspectiva->ViewValue = $this->ic_perspectiva->FldTagCaption(1) <> "" ? $this->ic_perspectiva->FldTagCaption(1) : $this->ic_perspectiva->CurrentValue;
						break;
					case $this->ic_perspectiva->FldTagValue(2):
						$this->ic_perspectiva->ViewValue = $this->ic_perspectiva->FldTagCaption(2) <> "" ? $this->ic_perspectiva->FldTagCaption(2) : $this->ic_perspectiva->CurrentValue;
						break;
					case $this->ic_perspectiva->FldTagValue(3):
						$this->ic_perspectiva->ViewValue = $this->ic_perspectiva->FldTagCaption(3) <> "" ? $this->ic_perspectiva->FldTagCaption(3) : $this->ic_perspectiva->CurrentValue;
						break;
					case $this->ic_perspectiva->FldTagValue(4):
						$this->ic_perspectiva->ViewValue = $this->ic_perspectiva->FldTagCaption(4) <> "" ? $this->ic_perspectiva->FldTagCaption(4) : $this->ic_perspectiva->CurrentValue;
						break;
					default:
						$this->ic_perspectiva->ViewValue = $this->ic_perspectiva->CurrentValue;
				}
			} else {
				$this->ic_perspectiva->ViewValue = NULL;
			}
			$this->ic_perspectiva->ViewCustomAttributes = "";

			// no_metaneg
			$this->no_metaneg->ViewValue = $this->no_metaneg->CurrentValue;
			$this->no_metaneg->ViewCustomAttributes = "";

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
					case $this->ic_situacao->FldTagValue(4):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(4) <> "" ? $this->ic_situacao->FldTagCaption(4) : $this->ic_situacao->CurrentValue;
						break;
					default:
						$this->ic_situacao->ViewValue = $this->ic_situacao->CurrentValue;
				}
			} else {
				$this->ic_situacao->ViewValue = NULL;
			}
			$this->ic_situacao->ViewCustomAttributes = "";

			// nu_metaneg
			$this->nu_metaneg->LinkCustomAttributes = "";
			$this->nu_metaneg->HrefValue = "";
			$this->nu_metaneg->TooltipValue = "";

			// nu_periodoPei
			$this->nu_periodoPei->LinkCustomAttributes = "";
			$this->nu_periodoPei->HrefValue = "";
			$this->nu_periodoPei->TooltipValue = "";

			// nu_necessidade
			$this->nu_necessidade->LinkCustomAttributes = "";
			$this->nu_necessidade->HrefValue = "";
			$this->nu_necessidade->TooltipValue = "";

			// ic_perspectiva
			$this->ic_perspectiva->LinkCustomAttributes = "";
			$this->ic_perspectiva->HrefValue = "";
			$this->ic_perspectiva->TooltipValue = "";

			// no_metaneg
			$this->no_metaneg->LinkCustomAttributes = "";
			$this->no_metaneg->HrefValue = "";
			$this->no_metaneg->TooltipValue = "";

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
				$sThisKey .= $row['nu_metaneg'];
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "metaneglist.php", $this->TableVar);
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
if (!isset($metaneg_delete)) $metaneg_delete = new cmetaneg_delete();

// Page init
$metaneg_delete->Page_Init();

// Page main
$metaneg_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$metaneg_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var metaneg_delete = new ew_Page("metaneg_delete");
metaneg_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = metaneg_delete.PageID; // For backward compatibility

// Form object
var fmetanegdelete = new ew_Form("fmetanegdelete");

// Form_CustomValidate event
fmetanegdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmetanegdelete.ValidateRequired = true;
<?php } else { ?>
fmetanegdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fmetanegdelete.Lists["x_nu_periodoPei"] = {"LinkField":"x_nu_periodoPei","Ajax":null,"AutoFill":false,"DisplayFields":["x_nu_anoInicio","x_nu_anoFim","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($metaneg_delete->Recordset = $metaneg_delete->LoadRecordset())
	$metaneg_deleteTotalRecs = $metaneg_delete->Recordset->RecordCount(); // Get record count
if ($metaneg_deleteTotalRecs <= 0) { // No record found, exit
	if ($metaneg_delete->Recordset)
		$metaneg_delete->Recordset->Close();
	$metaneg_delete->Page_Terminate("metaneglist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $metaneg_delete->ShowPageHeader(); ?>
<?php
$metaneg_delete->ShowMessage();
?>
<form name="fmetanegdelete" id="fmetanegdelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="metaneg">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($metaneg_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_metanegdelete" class="ewTable ewTableSeparate">
<?php echo $metaneg->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_metaneg_nu_metaneg" class="metaneg_nu_metaneg"><?php echo $metaneg->nu_metaneg->FldCaption() ?></span></td>
		<td><span id="elh_metaneg_nu_periodoPei" class="metaneg_nu_periodoPei"><?php echo $metaneg->nu_periodoPei->FldCaption() ?></span></td>
		<td><span id="elh_metaneg_nu_necessidade" class="metaneg_nu_necessidade"><?php echo $metaneg->nu_necessidade->FldCaption() ?></span></td>
		<td><span id="elh_metaneg_ic_perspectiva" class="metaneg_ic_perspectiva"><?php echo $metaneg->ic_perspectiva->FldCaption() ?></span></td>
		<td><span id="elh_metaneg_no_metaneg" class="metaneg_no_metaneg"><?php echo $metaneg->no_metaneg->FldCaption() ?></span></td>
		<td><span id="elh_metaneg_ic_situacao" class="metaneg_ic_situacao"><?php echo $metaneg->ic_situacao->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$metaneg_delete->RecCnt = 0;
$i = 0;
while (!$metaneg_delete->Recordset->EOF) {
	$metaneg_delete->RecCnt++;
	$metaneg_delete->RowCnt++;

	// Set row properties
	$metaneg->ResetAttrs();
	$metaneg->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$metaneg_delete->LoadRowValues($metaneg_delete->Recordset);

	// Render row
	$metaneg_delete->RenderRow();
?>
	<tr<?php echo $metaneg->RowAttributes() ?>>
		<td<?php echo $metaneg->nu_metaneg->CellAttributes() ?>>
<span id="el<?php echo $metaneg_delete->RowCnt ?>_metaneg_nu_metaneg" class="control-group metaneg_nu_metaneg">
<span<?php echo $metaneg->nu_metaneg->ViewAttributes() ?>>
<?php echo $metaneg->nu_metaneg->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $metaneg->nu_periodoPei->CellAttributes() ?>>
<span id="el<?php echo $metaneg_delete->RowCnt ?>_metaneg_nu_periodoPei" class="control-group metaneg_nu_periodoPei">
<span<?php echo $metaneg->nu_periodoPei->ViewAttributes() ?>>
<?php echo $metaneg->nu_periodoPei->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $metaneg->nu_necessidade->CellAttributes() ?>>
<span id="el<?php echo $metaneg_delete->RowCnt ?>_metaneg_nu_necessidade" class="control-group metaneg_nu_necessidade">
<span<?php echo $metaneg->nu_necessidade->ViewAttributes() ?>>
<?php echo $metaneg->nu_necessidade->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $metaneg->ic_perspectiva->CellAttributes() ?>>
<span id="el<?php echo $metaneg_delete->RowCnt ?>_metaneg_ic_perspectiva" class="control-group metaneg_ic_perspectiva">
<span<?php echo $metaneg->ic_perspectiva->ViewAttributes() ?>>
<?php echo $metaneg->ic_perspectiva->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $metaneg->no_metaneg->CellAttributes() ?>>
<span id="el<?php echo $metaneg_delete->RowCnt ?>_metaneg_no_metaneg" class="control-group metaneg_no_metaneg">
<span<?php echo $metaneg->no_metaneg->ViewAttributes() ?>>
<?php echo $metaneg->no_metaneg->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $metaneg->ic_situacao->CellAttributes() ?>>
<span id="el<?php echo $metaneg_delete->RowCnt ?>_metaneg_ic_situacao" class="control-group metaneg_ic_situacao">
<span<?php echo $metaneg->ic_situacao->ViewAttributes() ?>>
<?php echo $metaneg->ic_situacao->ListViewValue() ?></span>
</span>
</td>
	</tr>
<?php
	$metaneg_delete->Recordset->MoveNext();
}
$metaneg_delete->Recordset->Close();
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
fmetanegdelete.Init();
</script>
<?php
$metaneg_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$metaneg_delete->Page_Terminate();
?>
