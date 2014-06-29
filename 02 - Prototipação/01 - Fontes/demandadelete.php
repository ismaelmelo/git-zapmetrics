<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "demandainfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$demanda_delete = NULL; // Initialize page object first

class cdemanda_delete extends cdemanda {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'demanda';

	// Page object name
	var $PageObjName = 'demanda_delete';

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

		// Table object (demanda)
		if (!isset($GLOBALS["demanda"])) {
			$GLOBALS["demanda"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["demanda"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'demanda', TRUE);

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
			$this->Page_Terminate("demandalist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action
		$this->nu_demanda->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			$this->Page_Terminate("demandalist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in demanda class, demandainfo.php

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
		$this->nu_demanda->setDbValue($rs->fields('nu_demanda'));
		$this->ds_demanda->setDbValue($rs->fields('ds_demanda'));
		$this->nu_pessoaResponsavel->setDbValue($rs->fields('nu_pessoaResponsavel'));
		$this->nu_itemPDTI->setDbValue($rs->fields('nu_itemPDTI'));
		$this->dt_registro->setDbValue($rs->fields('dt_registro'));
		$this->im_anexo->Upload->DbValue = $rs->fields('im_anexo');
		$this->ic_situacao->setDbValue($rs->fields('ic_situacao'));
		$this->dt_aprovacao->setDbValue($rs->fields('dt_aprovacao'));
		$this->nu_pessoaAprovadora->setDbValue($rs->fields('nu_pessoaAprovadora'));
		$this->nu_usuario->setDbValue($rs->fields('nu_usuario'));
		$this->ts_datahora->setDbValue($rs->fields('ts_datahora'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_demanda->DbValue = $row['nu_demanda'];
		$this->ds_demanda->DbValue = $row['ds_demanda'];
		$this->nu_pessoaResponsavel->DbValue = $row['nu_pessoaResponsavel'];
		$this->nu_itemPDTI->DbValue = $row['nu_itemPDTI'];
		$this->dt_registro->DbValue = $row['dt_registro'];
		$this->im_anexo->Upload->DbValue = $row['im_anexo'];
		$this->ic_situacao->DbValue = $row['ic_situacao'];
		$this->dt_aprovacao->DbValue = $row['dt_aprovacao'];
		$this->nu_pessoaAprovadora->DbValue = $row['nu_pessoaAprovadora'];
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
		// nu_demanda
		// ds_demanda
		// nu_pessoaResponsavel
		// nu_itemPDTI
		// dt_registro
		// im_anexo
		// ic_situacao
		// dt_aprovacao
		// nu_pessoaAprovadora
		// nu_usuario
		// ts_datahora

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_demanda
			$this->nu_demanda->ViewValue = $this->nu_demanda->CurrentValue;
			$this->nu_demanda->ViewCustomAttributes = "";

			// nu_pessoaResponsavel
			if (strval($this->nu_pessoaResponsavel->CurrentValue) <> "") {
				$sFilterWrk = "[nu_pessoa]" . ew_SearchString("=", $this->nu_pessoaResponsavel->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_pessoa], [no_pessoa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[pessoa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_pessoaResponsavel, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_pessoa] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_pessoaResponsavel->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_pessoaResponsavel->ViewValue = $this->nu_pessoaResponsavel->CurrentValue;
				}
			} else {
				$this->nu_pessoaResponsavel->ViewValue = NULL;
			}
			$this->nu_pessoaResponsavel->ViewCustomAttributes = "";

			// nu_itemPDTI
			$this->nu_itemPDTI->ViewValue = $this->nu_itemPDTI->CurrentValue;
			$this->nu_itemPDTI->ViewCustomAttributes = "";

			// dt_registro
			$this->dt_registro->ViewValue = $this->dt_registro->CurrentValue;
			$this->dt_registro->ViewValue = ew_FormatDateTime($this->dt_registro->ViewValue, 7);
			$this->dt_registro->ViewCustomAttributes = "";

			// im_anexo
			if (!ew_Empty($this->im_anexo->Upload->DbValue)) {
				$this->im_anexo->ViewValue = $this->im_anexo->Upload->DbValue;
			} else {
				$this->im_anexo->ViewValue = "";
			}
			$this->im_anexo->ViewCustomAttributes = "";

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

			// dt_aprovacao
			$this->dt_aprovacao->ViewValue = $this->dt_aprovacao->CurrentValue;
			$this->dt_aprovacao->ViewValue = ew_FormatDateTime($this->dt_aprovacao->ViewValue, 7);
			$this->dt_aprovacao->ViewCustomAttributes = "";

			// nu_pessoaAprovadora
			if (strval($this->nu_pessoaAprovadora->CurrentValue) <> "") {
				$sFilterWrk = "[nu_pessoa]" . ew_SearchString("=", $this->nu_pessoaAprovadora->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_pessoa], [no_pessoa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[pessoa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_pessoaAprovadora, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_pessoaAprovadora->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_pessoaAprovadora->ViewValue = $this->nu_pessoaAprovadora->CurrentValue;
				}
			} else {
				$this->nu_pessoaAprovadora->ViewValue = NULL;
			}
			$this->nu_pessoaAprovadora->ViewCustomAttributes = "";

			// nu_usuario
			$this->nu_usuario->ViewValue = $this->nu_usuario->CurrentValue;
			$this->nu_usuario->ViewCustomAttributes = "";

			// ts_datahora
			$this->ts_datahora->ViewValue = $this->ts_datahora->CurrentValue;
			$this->ts_datahora->ViewValue = ew_FormatDateTime($this->ts_datahora->ViewValue, 7);
			$this->ts_datahora->ViewCustomAttributes = "";

			// nu_demanda
			$this->nu_demanda->LinkCustomAttributes = "";
			$this->nu_demanda->HrefValue = "";
			$this->nu_demanda->TooltipValue = "";

			// nu_pessoaResponsavel
			$this->nu_pessoaResponsavel->LinkCustomAttributes = "";
			$this->nu_pessoaResponsavel->HrefValue = "";
			$this->nu_pessoaResponsavel->TooltipValue = "";

			// nu_itemPDTI
			$this->nu_itemPDTI->LinkCustomAttributes = "";
			$this->nu_itemPDTI->HrefValue = "";
			$this->nu_itemPDTI->TooltipValue = "";

			// dt_registro
			$this->dt_registro->LinkCustomAttributes = "";
			$this->dt_registro->HrefValue = "";
			$this->dt_registro->TooltipValue = "";

			// im_anexo
			$this->im_anexo->LinkCustomAttributes = "";
			$this->im_anexo->HrefValue = "";
			$this->im_anexo->HrefValue2 = $this->im_anexo->UploadPath . $this->im_anexo->Upload->DbValue;
			$this->im_anexo->TooltipValue = "";

			// ic_situacao
			$this->ic_situacao->LinkCustomAttributes = "";
			$this->ic_situacao->HrefValue = "";
			$this->ic_situacao->TooltipValue = "";

			// dt_aprovacao
			$this->dt_aprovacao->LinkCustomAttributes = "";
			$this->dt_aprovacao->HrefValue = "";
			$this->dt_aprovacao->TooltipValue = "";

			// nu_pessoaAprovadora
			$this->nu_pessoaAprovadora->LinkCustomAttributes = "";
			$this->nu_pessoaAprovadora->HrefValue = "";
			$this->nu_pessoaAprovadora->TooltipValue = "";

			// nu_usuario
			$this->nu_usuario->LinkCustomAttributes = "";
			$this->nu_usuario->HrefValue = "";
			$this->nu_usuario->TooltipValue = "";

			// ts_datahora
			$this->ts_datahora->LinkCustomAttributes = "";
			$this->ts_datahora->HrefValue = "";
			$this->ts_datahora->TooltipValue = "";
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
				$sThisKey .= $row['nu_demanda'];
				$this->LoadDbValues($row);
				@unlink(ew_UploadPathEx(TRUE, $this->im_anexo->OldUploadPath) . $row['im_anexo']);
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "demandalist.php", $this->TableVar);
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
if (!isset($demanda_delete)) $demanda_delete = new cdemanda_delete();

// Page init
$demanda_delete->Page_Init();

// Page main
$demanda_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$demanda_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var demanda_delete = new ew_Page("demanda_delete");
demanda_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = demanda_delete.PageID; // For backward compatibility

// Form object
var fdemandadelete = new ew_Form("fdemandadelete");

// Form_CustomValidate event
fdemandadelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdemandadelete.ValidateRequired = true;
<?php } else { ?>
fdemandadelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fdemandadelete.Lists["x_nu_pessoaResponsavel"] = {"LinkField":"x_nu_pessoa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_pessoa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fdemandadelete.Lists["x_nu_pessoaAprovadora"] = {"LinkField":"x_nu_pessoa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_pessoa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($demanda_delete->Recordset = $demanda_delete->LoadRecordset())
	$demanda_deleteTotalRecs = $demanda_delete->Recordset->RecordCount(); // Get record count
if ($demanda_deleteTotalRecs <= 0) { // No record found, exit
	if ($demanda_delete->Recordset)
		$demanda_delete->Recordset->Close();
	$demanda_delete->Page_Terminate("demandalist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $demanda_delete->ShowPageHeader(); ?>
<?php
$demanda_delete->ShowMessage();
?>
<form name="fdemandadelete" id="fdemandadelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="demanda">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($demanda_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_demandadelete" class="ewTable ewTableSeparate">
<?php echo $demanda->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_demanda_nu_demanda" class="demanda_nu_demanda"><?php echo $demanda->nu_demanda->FldCaption() ?></span></td>
		<td><span id="elh_demanda_nu_pessoaResponsavel" class="demanda_nu_pessoaResponsavel"><?php echo $demanda->nu_pessoaResponsavel->FldCaption() ?></span></td>
		<td><span id="elh_demanda_nu_itemPDTI" class="demanda_nu_itemPDTI"><?php echo $demanda->nu_itemPDTI->FldCaption() ?></span></td>
		<td><span id="elh_demanda_dt_registro" class="demanda_dt_registro"><?php echo $demanda->dt_registro->FldCaption() ?></span></td>
		<td><span id="elh_demanda_im_anexo" class="demanda_im_anexo"><?php echo $demanda->im_anexo->FldCaption() ?></span></td>
		<td><span id="elh_demanda_ic_situacao" class="demanda_ic_situacao"><?php echo $demanda->ic_situacao->FldCaption() ?></span></td>
		<td><span id="elh_demanda_dt_aprovacao" class="demanda_dt_aprovacao"><?php echo $demanda->dt_aprovacao->FldCaption() ?></span></td>
		<td><span id="elh_demanda_nu_pessoaAprovadora" class="demanda_nu_pessoaAprovadora"><?php echo $demanda->nu_pessoaAprovadora->FldCaption() ?></span></td>
		<td><span id="elh_demanda_nu_usuario" class="demanda_nu_usuario"><?php echo $demanda->nu_usuario->FldCaption() ?></span></td>
		<td><span id="elh_demanda_ts_datahora" class="demanda_ts_datahora"><?php echo $demanda->ts_datahora->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$demanda_delete->RecCnt = 0;
$i = 0;
while (!$demanda_delete->Recordset->EOF) {
	$demanda_delete->RecCnt++;
	$demanda_delete->RowCnt++;

	// Set row properties
	$demanda->ResetAttrs();
	$demanda->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$demanda_delete->LoadRowValues($demanda_delete->Recordset);

	// Render row
	$demanda_delete->RenderRow();
?>
	<tr<?php echo $demanda->RowAttributes() ?>>
		<td<?php echo $demanda->nu_demanda->CellAttributes() ?>>
<span id="el<?php echo $demanda_delete->RowCnt ?>_demanda_nu_demanda" class="control-group demanda_nu_demanda">
<span<?php echo $demanda->nu_demanda->ViewAttributes() ?>>
<?php echo $demanda->nu_demanda->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $demanda->nu_pessoaResponsavel->CellAttributes() ?>>
<span id="el<?php echo $demanda_delete->RowCnt ?>_demanda_nu_pessoaResponsavel" class="control-group demanda_nu_pessoaResponsavel">
<span<?php echo $demanda->nu_pessoaResponsavel->ViewAttributes() ?>>
<?php echo $demanda->nu_pessoaResponsavel->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $demanda->nu_itemPDTI->CellAttributes() ?>>
<span id="el<?php echo $demanda_delete->RowCnt ?>_demanda_nu_itemPDTI" class="control-group demanda_nu_itemPDTI">
<span<?php echo $demanda->nu_itemPDTI->ViewAttributes() ?>>
<?php echo $demanda->nu_itemPDTI->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $demanda->dt_registro->CellAttributes() ?>>
<span id="el<?php echo $demanda_delete->RowCnt ?>_demanda_dt_registro" class="control-group demanda_dt_registro">
<span<?php echo $demanda->dt_registro->ViewAttributes() ?>>
<?php echo $demanda->dt_registro->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $demanda->im_anexo->CellAttributes() ?>>
<span id="el<?php echo $demanda_delete->RowCnt ?>_demanda_im_anexo" class="control-group demanda_im_anexo">
<span<?php echo $demanda->im_anexo->ViewAttributes() ?>>
<?php if ($demanda->im_anexo->LinkAttributes() <> "") { ?>
<?php if (!empty($demanda->im_anexo->Upload->DbValue)) { ?>
<?php echo $demanda->im_anexo->ListViewValue() ?>
<?php } elseif (!in_array($demanda->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($demanda->im_anexo->Upload->DbValue)) { ?>
<?php echo $demanda->im_anexo->ListViewValue() ?>
<?php } elseif (!in_array($demanda->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</span>
</td>
		<td<?php echo $demanda->ic_situacao->CellAttributes() ?>>
<span id="el<?php echo $demanda_delete->RowCnt ?>_demanda_ic_situacao" class="control-group demanda_ic_situacao">
<span<?php echo $demanda->ic_situacao->ViewAttributes() ?>>
<?php echo $demanda->ic_situacao->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $demanda->dt_aprovacao->CellAttributes() ?>>
<span id="el<?php echo $demanda_delete->RowCnt ?>_demanda_dt_aprovacao" class="control-group demanda_dt_aprovacao">
<span<?php echo $demanda->dt_aprovacao->ViewAttributes() ?>>
<?php echo $demanda->dt_aprovacao->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $demanda->nu_pessoaAprovadora->CellAttributes() ?>>
<span id="el<?php echo $demanda_delete->RowCnt ?>_demanda_nu_pessoaAprovadora" class="control-group demanda_nu_pessoaAprovadora">
<span<?php echo $demanda->nu_pessoaAprovadora->ViewAttributes() ?>>
<?php echo $demanda->nu_pessoaAprovadora->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $demanda->nu_usuario->CellAttributes() ?>>
<span id="el<?php echo $demanda_delete->RowCnt ?>_demanda_nu_usuario" class="control-group demanda_nu_usuario">
<span<?php echo $demanda->nu_usuario->ViewAttributes() ?>>
<?php echo $demanda->nu_usuario->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $demanda->ts_datahora->CellAttributes() ?>>
<span id="el<?php echo $demanda_delete->RowCnt ?>_demanda_ts_datahora" class="control-group demanda_ts_datahora">
<span<?php echo $demanda->ts_datahora->ViewAttributes() ?>>
<?php echo $demanda->ts_datahora->ListViewValue() ?></span>
</span>
</td>
	</tr>
<?php
	$demanda_delete->Recordset->MoveNext();
}
$demanda_delete->Recordset->Close();
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
fdemandadelete.Init();
</script>
<?php
$demanda_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$demanda_delete->Page_Terminate();
?>
