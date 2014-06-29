<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "ciialternativainfo.php" ?>
<?php include_once "ciiquestaoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$ciialternativa_delete = NULL; // Initialize page object first

class cciialternativa_delete extends cciialternativa {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'ciialternativa';

	// Page object name
	var $PageObjName = 'ciialternativa_delete';

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

		// Table object (ciialternativa)
		if (!isset($GLOBALS["ciialternativa"])) {
			$GLOBALS["ciialternativa"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["ciialternativa"];
		}

		// Table object (ciiquestao)
		if (!isset($GLOBALS['ciiquestao'])) $GLOBALS['ciiquestao'] = new cciiquestao();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'ciialternativa', TRUE);

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
			$this->Page_Terminate("ciialternativalist.php");
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
			$this->Page_Terminate("ciialternativalist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in ciialternativa class, ciialternativainfo.php

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
		$this->nu_alternativa->setDbValue($rs->fields('nu_alternativa'));
		$this->co_questao->setDbValue($rs->fields('co_questao'));
		$this->no_alternativa->setDbValue($rs->fields('no_alternativa'));
		$this->ds_alternativa->setDbValue($rs->fields('ds_alternativa'));
		$this->vr_alternativa->setDbValue($rs->fields('vr_alternativa'));
		$this->nu_peso->setDbValue($rs->fields('nu_peso'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_alternativa->DbValue = $row['nu_alternativa'];
		$this->co_questao->DbValue = $row['co_questao'];
		$this->no_alternativa->DbValue = $row['no_alternativa'];
		$this->ds_alternativa->DbValue = $row['ds_alternativa'];
		$this->vr_alternativa->DbValue = $row['vr_alternativa'];
		$this->nu_peso->DbValue = $row['nu_peso'];
		$this->ic_ativo->DbValue = $row['ic_ativo'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->vr_alternativa->FormValue == $this->vr_alternativa->CurrentValue && is_numeric(ew_StrToFloat($this->vr_alternativa->CurrentValue)))
			$this->vr_alternativa->CurrentValue = ew_StrToFloat($this->vr_alternativa->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_alternativa

		$this->nu_alternativa->CellCssStyle = "white-space: nowrap;";

		// co_questao
		// no_alternativa
		// ds_alternativa
		// vr_alternativa
		// nu_peso
		// ic_ativo

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_alternativa
			$this->nu_alternativa->ViewValue = $this->nu_alternativa->CurrentValue;
			$this->nu_alternativa->ViewCustomAttributes = "";

			// co_questao
			if (strval($this->co_questao->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_questao->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_questao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_questao->ViewValue = $rswrk->fields('DispFld');
					$this->co_questao->ViewValue .= ew_ValueSeparator(1,$this->co_questao) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_questao->ViewValue = $this->co_questao->CurrentValue;
				}
			} else {
				$this->co_questao->ViewValue = NULL;
			}
			$this->co_questao->ViewCustomAttributes = "";

			// no_alternativa
			$this->no_alternativa->ViewValue = $this->no_alternativa->CurrentValue;
			$this->no_alternativa->ViewCustomAttributes = "";

			// vr_alternativa
			$this->vr_alternativa->ViewValue = $this->vr_alternativa->CurrentValue;
			$this->vr_alternativa->ViewCustomAttributes = "";

			// nu_peso
			$this->nu_peso->ViewValue = $this->nu_peso->CurrentValue;
			$this->nu_peso->ViewCustomAttributes = "";

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

			// no_alternativa
			$this->no_alternativa->LinkCustomAttributes = "";
			$this->no_alternativa->HrefValue = "";
			$this->no_alternativa->TooltipValue = "";

			// vr_alternativa
			$this->vr_alternativa->LinkCustomAttributes = "";
			$this->vr_alternativa->HrefValue = "";
			$this->vr_alternativa->TooltipValue = "";

			// nu_peso
			$this->nu_peso->LinkCustomAttributes = "";
			$this->nu_peso->HrefValue = "";
			$this->nu_peso->TooltipValue = "";

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
				$sThisKey .= $row['nu_alternativa'];
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "ciialternativalist.php", $this->TableVar);
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
if (!isset($ciialternativa_delete)) $ciialternativa_delete = new cciialternativa_delete();

// Page init
$ciialternativa_delete->Page_Init();

// Page main
$ciialternativa_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$ciialternativa_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var ciialternativa_delete = new ew_Page("ciialternativa_delete");
ciialternativa_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = ciialternativa_delete.PageID; // For backward compatibility

// Form object
var fciialternativadelete = new ew_Form("fciialternativadelete");

// Form_CustomValidate event
fciialternativadelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fciialternativadelete.ValidateRequired = true;
<?php } else { ?>
fciialternativadelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($ciialternativa_delete->Recordset = $ciialternativa_delete->LoadRecordset())
	$ciialternativa_deleteTotalRecs = $ciialternativa_delete->Recordset->RecordCount(); // Get record count
if ($ciialternativa_deleteTotalRecs <= 0) { // No record found, exit
	if ($ciialternativa_delete->Recordset)
		$ciialternativa_delete->Recordset->Close();
	$ciialternativa_delete->Page_Terminate("ciialternativalist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $ciialternativa_delete->ShowPageHeader(); ?>
<?php
$ciialternativa_delete->ShowMessage();
?>
<form name="fciialternativadelete" id="fciialternativadelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="ciialternativa">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($ciialternativa_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_ciialternativadelete" class="ewTable ewTableSeparate">
<?php echo $ciialternativa->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_ciialternativa_no_alternativa" class="ciialternativa_no_alternativa"><?php echo $ciialternativa->no_alternativa->FldCaption() ?></span></td>
		<td><span id="elh_ciialternativa_vr_alternativa" class="ciialternativa_vr_alternativa"><?php echo $ciialternativa->vr_alternativa->FldCaption() ?></span></td>
		<td><span id="elh_ciialternativa_nu_peso" class="ciialternativa_nu_peso"><?php echo $ciialternativa->nu_peso->FldCaption() ?></span></td>
		<td><span id="elh_ciialternativa_ic_ativo" class="ciialternativa_ic_ativo"><?php echo $ciialternativa->ic_ativo->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$ciialternativa_delete->RecCnt = 0;
$i = 0;
while (!$ciialternativa_delete->Recordset->EOF) {
	$ciialternativa_delete->RecCnt++;
	$ciialternativa_delete->RowCnt++;

	// Set row properties
	$ciialternativa->ResetAttrs();
	$ciialternativa->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$ciialternativa_delete->LoadRowValues($ciialternativa_delete->Recordset);

	// Render row
	$ciialternativa_delete->RenderRow();
?>
	<tr<?php echo $ciialternativa->RowAttributes() ?>>
		<td<?php echo $ciialternativa->no_alternativa->CellAttributes() ?>>
<span id="el<?php echo $ciialternativa_delete->RowCnt ?>_ciialternativa_no_alternativa" class="control-group ciialternativa_no_alternativa">
<span<?php echo $ciialternativa->no_alternativa->ViewAttributes() ?>>
<?php echo $ciialternativa->no_alternativa->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $ciialternativa->vr_alternativa->CellAttributes() ?>>
<span id="el<?php echo $ciialternativa_delete->RowCnt ?>_ciialternativa_vr_alternativa" class="control-group ciialternativa_vr_alternativa">
<span<?php echo $ciialternativa->vr_alternativa->ViewAttributes() ?>>
<?php echo $ciialternativa->vr_alternativa->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $ciialternativa->nu_peso->CellAttributes() ?>>
<span id="el<?php echo $ciialternativa_delete->RowCnt ?>_ciialternativa_nu_peso" class="control-group ciialternativa_nu_peso">
<span<?php echo $ciialternativa->nu_peso->ViewAttributes() ?>>
<?php echo $ciialternativa->nu_peso->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $ciialternativa->ic_ativo->CellAttributes() ?>>
<span id="el<?php echo $ciialternativa_delete->RowCnt ?>_ciialternativa_ic_ativo" class="control-group ciialternativa_ic_ativo">
<span<?php echo $ciialternativa->ic_ativo->ViewAttributes() ?>>
<?php echo $ciialternativa->ic_ativo->ListViewValue() ?></span>
</span>
</td>
	</tr>
<?php
	$ciialternativa_delete->Recordset->MoveNext();
}
$ciialternativa_delete->Recordset->Close();
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
fciialternativadelete.Init();
</script>
<?php
$ciialternativa_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$ciialternativa_delete->Page_Terminate();
?>
