<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "parintredmineinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$parintredmine_delete = NULL; // Initialize page object first

class cparintredmine_delete extends cparintredmine {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{FE479719-4CC0-498B-BE07-C9817DD0435B}";

	// Table name
	var $TableName = 'parintredmine';

	// Page object name
	var $PageObjName = 'parintredmine_delete';

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

		// Table object (parintredmine)
		if (!isset($GLOBALS["parintredmine"])) {
			$GLOBALS["parintredmine"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["parintredmine"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'parintredmine', TRUE);

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
			$this->Page_Terminate("parintredminelist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action
		$this->nu_parIntRedmine->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			$this->Page_Terminate("parintredminelist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in parintredmine class, parintredmineinfo.php

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
		$this->nu_parIntRedmine->setDbValue($rs->fields('nu_parIntRedmine'));
		$this->no_parIntRedmine->setDbValue($rs->fields('no_parIntRedmine'));
		$this->ic_grupoParIntRedmine->setDbValue($rs->fields('ic_grupoParIntRedmine'));
		$this->vr_variavel->setDbValue($rs->fields('vr_variavel'));
		$this->no_variavel->setDbValue($rs->fields('no_variavel'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_parIntRedmine->DbValue = $row['nu_parIntRedmine'];
		$this->no_parIntRedmine->DbValue = $row['no_parIntRedmine'];
		$this->ic_grupoParIntRedmine->DbValue = $row['ic_grupoParIntRedmine'];
		$this->vr_variavel->DbValue = $row['vr_variavel'];
		$this->no_variavel->DbValue = $row['no_variavel'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->vr_variavel->FormValue == $this->vr_variavel->CurrentValue && is_numeric(ew_StrToFloat($this->vr_variavel->CurrentValue)))
			$this->vr_variavel->CurrentValue = ew_StrToFloat($this->vr_variavel->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_parIntRedmine
		// no_parIntRedmine
		// ic_grupoParIntRedmine
		// vr_variavel
		// no_variavel

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_parIntRedmine
			$this->nu_parIntRedmine->ViewValue = $this->nu_parIntRedmine->CurrentValue;
			$this->nu_parIntRedmine->ViewCustomAttributes = "";

			// no_parIntRedmine
			$this->no_parIntRedmine->ViewValue = $this->no_parIntRedmine->CurrentValue;
			$this->no_parIntRedmine->ViewCustomAttributes = "";

			// ic_grupoParIntRedmine
			if (strval($this->ic_grupoParIntRedmine->CurrentValue) <> "") {
				switch ($this->ic_grupoParIntRedmine->CurrentValue) {
					case $this->ic_grupoParIntRedmine->FldTagValue(1):
						$this->ic_grupoParIntRedmine->ViewValue = $this->ic_grupoParIntRedmine->FldTagCaption(1) <> "" ? $this->ic_grupoParIntRedmine->FldTagCaption(1) : $this->ic_grupoParIntRedmine->CurrentValue;
						break;
					default:
						$this->ic_grupoParIntRedmine->ViewValue = $this->ic_grupoParIntRedmine->CurrentValue;
				}
			} else {
				$this->ic_grupoParIntRedmine->ViewValue = NULL;
			}
			$this->ic_grupoParIntRedmine->ViewCustomAttributes = "";

			// vr_variavel
			$this->vr_variavel->ViewValue = $this->vr_variavel->CurrentValue;
			$this->vr_variavel->ViewCustomAttributes = "";

			// no_variavel
			$this->no_variavel->ViewValue = $this->no_variavel->CurrentValue;
			$this->no_variavel->ViewCustomAttributes = "";

			// nu_parIntRedmine
			$this->nu_parIntRedmine->LinkCustomAttributes = "";
			$this->nu_parIntRedmine->HrefValue = "";
			$this->nu_parIntRedmine->TooltipValue = "";

			// no_parIntRedmine
			$this->no_parIntRedmine->LinkCustomAttributes = "";
			$this->no_parIntRedmine->HrefValue = "";
			$this->no_parIntRedmine->TooltipValue = "";

			// ic_grupoParIntRedmine
			$this->ic_grupoParIntRedmine->LinkCustomAttributes = "";
			$this->ic_grupoParIntRedmine->HrefValue = "";
			$this->ic_grupoParIntRedmine->TooltipValue = "";

			// vr_variavel
			$this->vr_variavel->LinkCustomAttributes = "";
			$this->vr_variavel->HrefValue = "";
			$this->vr_variavel->TooltipValue = "";

			// no_variavel
			$this->no_variavel->LinkCustomAttributes = "";
			$this->no_variavel->HrefValue = "";
			$this->no_variavel->TooltipValue = "";
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
				$sThisKey .= $row['nu_parIntRedmine'];
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "parintredminelist.php", $this->TableVar);
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
if (!isset($parintredmine_delete)) $parintredmine_delete = new cparintredmine_delete();

// Page init
$parintredmine_delete->Page_Init();

// Page main
$parintredmine_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$parintredmine_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var parintredmine_delete = new ew_Page("parintredmine_delete");
parintredmine_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = parintredmine_delete.PageID; // For backward compatibility

// Form object
var fparintredminedelete = new ew_Form("fparintredminedelete");

// Form_CustomValidate event
fparintredminedelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fparintredminedelete.ValidateRequired = true;
<?php } else { ?>
fparintredminedelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($parintredmine_delete->Recordset = $parintredmine_delete->LoadRecordset())
	$parintredmine_deleteTotalRecs = $parintredmine_delete->Recordset->RecordCount(); // Get record count
if ($parintredmine_deleteTotalRecs <= 0) { // No record found, exit
	if ($parintredmine_delete->Recordset)
		$parintredmine_delete->Recordset->Close();
	$parintredmine_delete->Page_Terminate("parintredminelist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $parintredmine_delete->ShowPageHeader(); ?>
<?php
$parintredmine_delete->ShowMessage();
?>
<form name="fparintredminedelete" id="fparintredminedelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="parintredmine">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($parintredmine_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_parintredminedelete" class="ewTable ewTableSeparate">
<?php echo $parintredmine->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_parintredmine_nu_parIntRedmine" class="parintredmine_nu_parIntRedmine"><?php echo $parintredmine->nu_parIntRedmine->FldCaption() ?></span></td>
		<td><span id="elh_parintredmine_no_parIntRedmine" class="parintredmine_no_parIntRedmine"><?php echo $parintredmine->no_parIntRedmine->FldCaption() ?></span></td>
		<td><span id="elh_parintredmine_ic_grupoParIntRedmine" class="parintredmine_ic_grupoParIntRedmine"><?php echo $parintredmine->ic_grupoParIntRedmine->FldCaption() ?></span></td>
		<td><span id="elh_parintredmine_vr_variavel" class="parintredmine_vr_variavel"><?php echo $parintredmine->vr_variavel->FldCaption() ?></span></td>
		<td><span id="elh_parintredmine_no_variavel" class="parintredmine_no_variavel"><?php echo $parintredmine->no_variavel->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$parintredmine_delete->RecCnt = 0;
$i = 0;
while (!$parintredmine_delete->Recordset->EOF) {
	$parintredmine_delete->RecCnt++;
	$parintredmine_delete->RowCnt++;

	// Set row properties
	$parintredmine->ResetAttrs();
	$parintredmine->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$parintredmine_delete->LoadRowValues($parintredmine_delete->Recordset);

	// Render row
	$parintredmine_delete->RenderRow();
?>
	<tr<?php echo $parintredmine->RowAttributes() ?>>
		<td<?php echo $parintredmine->nu_parIntRedmine->CellAttributes() ?>>
<span id="el<?php echo $parintredmine_delete->RowCnt ?>_parintredmine_nu_parIntRedmine" class="control-group parintredmine_nu_parIntRedmine">
<span<?php echo $parintredmine->nu_parIntRedmine->ViewAttributes() ?>>
<?php echo $parintredmine->nu_parIntRedmine->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $parintredmine->no_parIntRedmine->CellAttributes() ?>>
<span id="el<?php echo $parintredmine_delete->RowCnt ?>_parintredmine_no_parIntRedmine" class="control-group parintredmine_no_parIntRedmine">
<span<?php echo $parintredmine->no_parIntRedmine->ViewAttributes() ?>>
<?php echo $parintredmine->no_parIntRedmine->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $parintredmine->ic_grupoParIntRedmine->CellAttributes() ?>>
<span id="el<?php echo $parintredmine_delete->RowCnt ?>_parintredmine_ic_grupoParIntRedmine" class="control-group parintredmine_ic_grupoParIntRedmine">
<span<?php echo $parintredmine->ic_grupoParIntRedmine->ViewAttributes() ?>>
<?php echo $parintredmine->ic_grupoParIntRedmine->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $parintredmine->vr_variavel->CellAttributes() ?>>
<span id="el<?php echo $parintredmine_delete->RowCnt ?>_parintredmine_vr_variavel" class="control-group parintredmine_vr_variavel">
<span<?php echo $parintredmine->vr_variavel->ViewAttributes() ?>>
<?php echo $parintredmine->vr_variavel->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $parintredmine->no_variavel->CellAttributes() ?>>
<span id="el<?php echo $parintredmine_delete->RowCnt ?>_parintredmine_no_variavel" class="control-group parintredmine_no_variavel">
<span<?php echo $parintredmine->no_variavel->ViewAttributes() ?>>
<?php echo $parintredmine->no_variavel->ListViewValue() ?></span>
</span>
</td>
	</tr>
<?php
	$parintredmine_delete->Recordset->MoveNext();
}
$parintredmine_delete->Recordset->Close();
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
fparintredminedelete.Init();
</script>
<?php
$parintredmine_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$parintredmine_delete->Page_Terminate();
?>
