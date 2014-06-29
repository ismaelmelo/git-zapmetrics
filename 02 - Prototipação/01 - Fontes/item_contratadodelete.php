<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "item_contratadoinfo.php" ?>
<?php include_once "contratoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$item_contratado_delete = NULL; // Initialize page object first

class citem_contratado_delete extends citem_contratado {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'item_contratado';

	// Page object name
	var $PageObjName = 'item_contratado_delete';

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
	var $AuditTrailOnDelete = TRUE;

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

		// Table object (item_contratado)
		if (!isset($GLOBALS["item_contratado"])) {
			$GLOBALS["item_contratado"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["item_contratado"];
		}

		// Table object (contrato)
		if (!isset($GLOBALS['contrato'])) $GLOBALS['contrato'] = new ccontrato();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'item_contratado', TRUE);

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
			$this->Page_Terminate("item_contratadolist.php");
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
			$this->Page_Terminate("item_contratadolist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in item_contratado class, item_contratadoinfo.php

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
		$this->nu_itemContratado->setDbValue($rs->fields('nu_itemContratado'));
		$this->nu_contrato->setDbValue($rs->fields('nu_contrato'));
		$this->nu_itemOc->setDbValue($rs->fields('nu_itemOc'));
		$this->no_itemContratado->setDbValue($rs->fields('no_itemContratado'));
		$this->nu_unidade->setDbValue($rs->fields('nu_unidade'));
		$this->qt_maximo->setDbValue($rs->fields('qt_maximo'));
		$this->vr_maximo->setDbValue($rs->fields('vr_maximo'));
		$this->dt_inclusao->setDbValue($rs->fields('dt_inclusao'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_itemContratado->DbValue = $row['nu_itemContratado'];
		$this->nu_contrato->DbValue = $row['nu_contrato'];
		$this->nu_itemOc->DbValue = $row['nu_itemOc'];
		$this->no_itemContratado->DbValue = $row['no_itemContratado'];
		$this->nu_unidade->DbValue = $row['nu_unidade'];
		$this->qt_maximo->DbValue = $row['qt_maximo'];
		$this->vr_maximo->DbValue = $row['vr_maximo'];
		$this->dt_inclusao->DbValue = $row['dt_inclusao'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->qt_maximo->FormValue == $this->qt_maximo->CurrentValue && is_numeric(ew_StrToFloat($this->qt_maximo->CurrentValue)))
			$this->qt_maximo->CurrentValue = ew_StrToFloat($this->qt_maximo->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_maximo->FormValue == $this->vr_maximo->CurrentValue && is_numeric(ew_StrToFloat($this->vr_maximo->CurrentValue)))
			$this->vr_maximo->CurrentValue = ew_StrToFloat($this->vr_maximo->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_itemContratado

		$this->nu_itemContratado->CellCssStyle = "white-space: nowrap;";

		// nu_contrato
		// nu_itemOc
		// no_itemContratado
		// nu_unidade
		// qt_maximo
		// vr_maximo
		// dt_inclusao

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_contrato
			if (strval($this->nu_contrato->CurrentValue) <> "") {
				$sFilterWrk = "[nu_contrato]" . ew_SearchString("=", $this->nu_contrato->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_contrato], [no_contrato] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[contrato]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_contrato, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_contrato] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_contrato->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_contrato->ViewValue = $this->nu_contrato->CurrentValue;
				}
			} else {
				$this->nu_contrato->ViewValue = NULL;
			}
			$this->nu_contrato->ViewCustomAttributes = "";

			// nu_itemOc
			if (strval($this->nu_itemOc->CurrentValue) <> "") {
				$sFilterWrk = "[nu_itemOc]" . ew_SearchString("=", $this->nu_itemOc->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_itemOc], [no_itemOc] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[itemoc]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_itemOc, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_itemOc] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_itemOc->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_itemOc->ViewValue = $this->nu_itemOc->CurrentValue;
				}
			} else {
				$this->nu_itemOc->ViewValue = NULL;
			}
			$this->nu_itemOc->ViewCustomAttributes = "";

			// no_itemContratado
			$this->no_itemContratado->ViewValue = $this->no_itemContratado->CurrentValue;
			$this->no_itemContratado->ViewCustomAttributes = "";

			// nu_unidade
			if (strval($this->nu_unidade->CurrentValue) <> "") {
				$sFilterWrk = "[nu_unidade]" . ew_SearchString("=", $this->nu_unidade->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_unidade], [no_unidade] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[unidade]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_unidade, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_unidade] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_unidade->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_unidade->ViewValue = $this->nu_unidade->CurrentValue;
				}
			} else {
				$this->nu_unidade->ViewValue = NULL;
			}
			$this->nu_unidade->ViewCustomAttributes = "";

			// qt_maximo
			$this->qt_maximo->ViewValue = $this->qt_maximo->CurrentValue;
			$this->qt_maximo->ViewCustomAttributes = "";

			// vr_maximo
			$this->vr_maximo->ViewValue = $this->vr_maximo->CurrentValue;
			$this->vr_maximo->ViewValue = ew_FormatCurrency($this->vr_maximo->ViewValue, 2, -2, -2, -2);
			$this->vr_maximo->ViewCustomAttributes = "";

			// dt_inclusao
			$this->dt_inclusao->ViewValue = $this->dt_inclusao->CurrentValue;
			$this->dt_inclusao->ViewValue = ew_FormatDateTime($this->dt_inclusao->ViewValue, 7);
			$this->dt_inclusao->ViewCustomAttributes = "";

			// nu_itemOc
			$this->nu_itemOc->LinkCustomAttributes = "";
			$this->nu_itemOc->HrefValue = "";
			$this->nu_itemOc->TooltipValue = "";

			// no_itemContratado
			$this->no_itemContratado->LinkCustomAttributes = "";
			$this->no_itemContratado->HrefValue = "";
			$this->no_itemContratado->TooltipValue = "";

			// nu_unidade
			$this->nu_unidade->LinkCustomAttributes = "";
			$this->nu_unidade->HrefValue = "";
			$this->nu_unidade->TooltipValue = "";

			// qt_maximo
			$this->qt_maximo->LinkCustomAttributes = "";
			$this->qt_maximo->HrefValue = "";
			$this->qt_maximo->TooltipValue = "";

			// vr_maximo
			$this->vr_maximo->LinkCustomAttributes = "";
			$this->vr_maximo->HrefValue = "";
			$this->vr_maximo->TooltipValue = "";

			// dt_inclusao
			$this->dt_inclusao->LinkCustomAttributes = "";
			$this->dt_inclusao->HrefValue = "";
			$this->dt_inclusao->TooltipValue = "";
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
		if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteBegin")); // Batch delete begin

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
				$sThisKey .= $row['nu_itemContratado'];
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
			if ($DeleteRows) {
				foreach ($rsold as $row)
					$this->WriteAuditTrailOnDelete($row);
			}
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteSuccess")); // Batch delete success
		} else {
			$conn->RollbackTrans(); // Rollback changes
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteRollback")); // Batch delete rollback
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "item_contratadolist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("delete");
		$Breadcrumb->Add("delete", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'item_contratado';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (delete page)
	function WriteAuditTrailOnDelete(&$rs) {
		if (!$this->AuditTrailOnDelete) return;
		$table = 'item_contratado';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_itemContratado'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
	  $curUser = CurrentUserID();
		foreach (array_keys($rs) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$oldvalue = $rs[$fldname];
					else
						$oldvalue = "[MEMO]"; // Memo field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$oldvalue = "[XML]"; // XML field
				} else {
					$oldvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $curUser, "D", $table, $fldname, $key, $oldvalue, "");
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($item_contratado_delete)) $item_contratado_delete = new citem_contratado_delete();

// Page init
$item_contratado_delete->Page_Init();

// Page main
$item_contratado_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$item_contratado_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var item_contratado_delete = new ew_Page("item_contratado_delete");
item_contratado_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = item_contratado_delete.PageID; // For backward compatibility

// Form object
var fitem_contratadodelete = new ew_Form("fitem_contratadodelete");

// Form_CustomValidate event
fitem_contratadodelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fitem_contratadodelete.ValidateRequired = true;
<?php } else { ?>
fitem_contratadodelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fitem_contratadodelete.Lists["x_nu_itemOc"] = {"LinkField":"x_nu_itemOc","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_itemOc","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fitem_contratadodelete.Lists["x_nu_unidade"] = {"LinkField":"x_nu_unidade","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_unidade","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($item_contratado_delete->Recordset = $item_contratado_delete->LoadRecordset())
	$item_contratado_deleteTotalRecs = $item_contratado_delete->Recordset->RecordCount(); // Get record count
if ($item_contratado_deleteTotalRecs <= 0) { // No record found, exit
	if ($item_contratado_delete->Recordset)
		$item_contratado_delete->Recordset->Close();
	$item_contratado_delete->Page_Terminate("item_contratadolist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $item_contratado_delete->ShowPageHeader(); ?>
<?php
$item_contratado_delete->ShowMessage();
?>
<form name="fitem_contratadodelete" id="fitem_contratadodelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="item_contratado">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($item_contratado_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_item_contratadodelete" class="ewTable ewTableSeparate">
<?php echo $item_contratado->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_item_contratado_nu_itemOc" class="item_contratado_nu_itemOc"><?php echo $item_contratado->nu_itemOc->FldCaption() ?></span></td>
		<td><span id="elh_item_contratado_no_itemContratado" class="item_contratado_no_itemContratado"><?php echo $item_contratado->no_itemContratado->FldCaption() ?></span></td>
		<td><span id="elh_item_contratado_nu_unidade" class="item_contratado_nu_unidade"><?php echo $item_contratado->nu_unidade->FldCaption() ?></span></td>
		<td><span id="elh_item_contratado_qt_maximo" class="item_contratado_qt_maximo"><?php echo $item_contratado->qt_maximo->FldCaption() ?></span></td>
		<td><span id="elh_item_contratado_vr_maximo" class="item_contratado_vr_maximo"><?php echo $item_contratado->vr_maximo->FldCaption() ?></span></td>
		<td><span id="elh_item_contratado_dt_inclusao" class="item_contratado_dt_inclusao"><?php echo $item_contratado->dt_inclusao->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$item_contratado_delete->RecCnt = 0;
$i = 0;
while (!$item_contratado_delete->Recordset->EOF) {
	$item_contratado_delete->RecCnt++;
	$item_contratado_delete->RowCnt++;

	// Set row properties
	$item_contratado->ResetAttrs();
	$item_contratado->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$item_contratado_delete->LoadRowValues($item_contratado_delete->Recordset);

	// Render row
	$item_contratado_delete->RenderRow();
?>
	<tr<?php echo $item_contratado->RowAttributes() ?>>
		<td<?php echo $item_contratado->nu_itemOc->CellAttributes() ?>>
<span id="el<?php echo $item_contratado_delete->RowCnt ?>_item_contratado_nu_itemOc" class="control-group item_contratado_nu_itemOc">
<span<?php echo $item_contratado->nu_itemOc->ViewAttributes() ?>>
<?php echo $item_contratado->nu_itemOc->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $item_contratado->no_itemContratado->CellAttributes() ?>>
<span id="el<?php echo $item_contratado_delete->RowCnt ?>_item_contratado_no_itemContratado" class="control-group item_contratado_no_itemContratado">
<span<?php echo $item_contratado->no_itemContratado->ViewAttributes() ?>>
<?php echo $item_contratado->no_itemContratado->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $item_contratado->nu_unidade->CellAttributes() ?>>
<span id="el<?php echo $item_contratado_delete->RowCnt ?>_item_contratado_nu_unidade" class="control-group item_contratado_nu_unidade">
<span<?php echo $item_contratado->nu_unidade->ViewAttributes() ?>>
<?php echo $item_contratado->nu_unidade->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $item_contratado->qt_maximo->CellAttributes() ?>>
<span id="el<?php echo $item_contratado_delete->RowCnt ?>_item_contratado_qt_maximo" class="control-group item_contratado_qt_maximo">
<span<?php echo $item_contratado->qt_maximo->ViewAttributes() ?>>
<?php echo $item_contratado->qt_maximo->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $item_contratado->vr_maximo->CellAttributes() ?>>
<span id="el<?php echo $item_contratado_delete->RowCnt ?>_item_contratado_vr_maximo" class="control-group item_contratado_vr_maximo">
<span<?php echo $item_contratado->vr_maximo->ViewAttributes() ?>>
<?php echo $item_contratado->vr_maximo->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $item_contratado->dt_inclusao->CellAttributes() ?>>
<span id="el<?php echo $item_contratado_delete->RowCnt ?>_item_contratado_dt_inclusao" class="control-group item_contratado_dt_inclusao">
<span<?php echo $item_contratado->dt_inclusao->ViewAttributes() ?>>
<?php echo $item_contratado->dt_inclusao->ListViewValue() ?></span>
</span>
</td>
	</tr>
<?php
	$item_contratado_delete->Recordset->MoveNext();
}
$item_contratado_delete->Recordset->Close();
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
fitem_contratadodelete.Init();
</script>
<?php
$item_contratado_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$item_contratado_delete->Page_Terminate();
?>
