<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "ambienteinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$ambiente_delete = NULL; // Initialize page object first

class cambiente_delete extends cambiente {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'ambiente';

	// Page object name
	var $PageObjName = 'ambiente_delete';

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

		// Table object (ambiente)
		if (!isset($GLOBALS["ambiente"])) {
			$GLOBALS["ambiente"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["ambiente"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'ambiente', TRUE);

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
			$this->Page_Terminate("ambientelist.php");
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
			$this->Page_Terminate("ambientelist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in ambiente class, ambienteinfo.php

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
		$this->nu_ambiente->setDbValue($rs->fields('nu_ambiente'));
		$this->no_ambiente->setDbValue($rs->fields('no_ambiente'));
		$this->ds_caracteristicas->setDbValue($rs->fields('ds_caracteristicas'));
		$this->nu_tpNegocio->setDbValue($rs->fields('nu_tpNegocio'));
		$this->nu_plataforma->setDbValue($rs->fields('nu_plataforma'));
		$this->nu_tpSistema->setDbValue($rs->fields('nu_tpSistema'));
		$this->nu_roteiro->setDbValue($rs->fields('nu_roteiro'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
		$this->nu_ordem->setDbValue($rs->fields('nu_ordem'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_ambiente->DbValue = $row['nu_ambiente'];
		$this->no_ambiente->DbValue = $row['no_ambiente'];
		$this->ds_caracteristicas->DbValue = $row['ds_caracteristicas'];
		$this->nu_tpNegocio->DbValue = $row['nu_tpNegocio'];
		$this->nu_plataforma->DbValue = $row['nu_plataforma'];
		$this->nu_tpSistema->DbValue = $row['nu_tpSistema'];
		$this->nu_roteiro->DbValue = $row['nu_roteiro'];
		$this->ic_ativo->DbValue = $row['ic_ativo'];
		$this->nu_ordem->DbValue = $row['nu_ordem'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_ambiente

		$this->nu_ambiente->CellCssStyle = "white-space: nowrap;";

		// no_ambiente
		// ds_caracteristicas
		// nu_tpNegocio
		// nu_plataforma
		// nu_tpSistema
		// nu_roteiro
		// ic_ativo
		// nu_ordem

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// no_ambiente
			$this->no_ambiente->ViewValue = $this->no_ambiente->CurrentValue;
			$this->no_ambiente->ViewCustomAttributes = "";

			// nu_tpNegocio
			if (strval($this->nu_tpNegocio->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpNegocio]" . ew_SearchString("=", $this->nu_tpNegocio->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpNegocio], [no_tpNegocio] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpnegocio]";
			$sWhereWrk = "";
			$lookuptblfilter = "[co_ativo] = 'S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpNegocio, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpNegocio->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpNegocio->ViewValue = $this->nu_tpNegocio->CurrentValue;
				}
			} else {
				$this->nu_tpNegocio->ViewValue = NULL;
			}
			$this->nu_tpNegocio->ViewCustomAttributes = "";

			// nu_plataforma
			if (strval($this->nu_plataforma->CurrentValue) <> "") {
				$sFilterWrk = "[nu_plataforma]" . ew_SearchString("=", $this->nu_plataforma->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_plataforma], [no_plataforma] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[plataforma]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_plataforma, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_plataforma->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_plataforma->ViewValue = $this->nu_plataforma->CurrentValue;
				}
			} else {
				$this->nu_plataforma->ViewValue = NULL;
			}
			$this->nu_plataforma->ViewCustomAttributes = "";

			// nu_tpSistema
			if (strval($this->nu_tpSistema->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpSistema]" . ew_SearchString("=", $this->nu_tpSistema->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpSistema], [no_tpSistema] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpsistema]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpSistema, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpSistema->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpSistema->ViewValue = $this->nu_tpSistema->CurrentValue;
				}
			} else {
				$this->nu_tpSistema->ViewValue = NULL;
			}
			$this->nu_tpSistema->ViewCustomAttributes = "";

			// nu_roteiro
			if (strval($this->nu_roteiro->CurrentValue) <> "") {
				$sFilterWrk = "[nu_roteiro]" . ew_SearchString("=", $this->nu_roteiro->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_roteiro], [no_roteiro] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[roteiro]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_roteiro, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_roteiro->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_roteiro->ViewValue = $this->nu_roteiro->CurrentValue;
				}
			} else {
				$this->nu_roteiro->ViewValue = NULL;
			}
			$this->nu_roteiro->ViewCustomAttributes = "";

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

			// nu_ordem
			$this->nu_ordem->ViewValue = $this->nu_ordem->CurrentValue;
			$this->nu_ordem->ViewCustomAttributes = "";

			// no_ambiente
			$this->no_ambiente->LinkCustomAttributes = "";
			$this->no_ambiente->HrefValue = "";
			$this->no_ambiente->TooltipValue = "";

			// nu_tpNegocio
			$this->nu_tpNegocio->LinkCustomAttributes = "";
			$this->nu_tpNegocio->HrefValue = "";
			$this->nu_tpNegocio->TooltipValue = "";

			// nu_plataforma
			$this->nu_plataforma->LinkCustomAttributes = "";
			$this->nu_plataforma->HrefValue = "";
			$this->nu_plataforma->TooltipValue = "";

			// nu_tpSistema
			$this->nu_tpSistema->LinkCustomAttributes = "";
			$this->nu_tpSistema->HrefValue = "";
			$this->nu_tpSistema->TooltipValue = "";

			// nu_roteiro
			$this->nu_roteiro->LinkCustomAttributes = "";
			$this->nu_roteiro->HrefValue = "";
			$this->nu_roteiro->TooltipValue = "";

			// ic_ativo
			$this->ic_ativo->LinkCustomAttributes = "";
			$this->ic_ativo->HrefValue = "";
			$this->ic_ativo->TooltipValue = "";

			// nu_ordem
			$this->nu_ordem->LinkCustomAttributes = "";
			$this->nu_ordem->HrefValue = "";
			$this->nu_ordem->TooltipValue = "";
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
				$sThisKey .= $row['nu_ambiente'];
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "ambientelist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("delete");
		$Breadcrumb->Add("delete", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'ambiente';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (delete page)
	function WriteAuditTrailOnDelete(&$rs) {
		if (!$this->AuditTrailOnDelete) return;
		$table = 'ambiente';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_ambiente'];

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
if (!isset($ambiente_delete)) $ambiente_delete = new cambiente_delete();

// Page init
$ambiente_delete->Page_Init();

// Page main
$ambiente_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$ambiente_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var ambiente_delete = new ew_Page("ambiente_delete");
ambiente_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = ambiente_delete.PageID; // For backward compatibility

// Form object
var fambientedelete = new ew_Form("fambientedelete");

// Form_CustomValidate event
fambientedelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fambientedelete.ValidateRequired = true;
<?php } else { ?>
fambientedelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fambientedelete.Lists["x_nu_tpNegocio"] = {"LinkField":"x_nu_tpNegocio","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpNegocio","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambientedelete.Lists["x_nu_plataforma"] = {"LinkField":"x_nu_plataforma","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_plataforma","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambientedelete.Lists["x_nu_tpSistema"] = {"LinkField":"x_nu_tpSistema","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpSistema","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambientedelete.Lists["x_nu_roteiro"] = {"LinkField":"x_nu_roteiro","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_roteiro","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($ambiente_delete->Recordset = $ambiente_delete->LoadRecordset())
	$ambiente_deleteTotalRecs = $ambiente_delete->Recordset->RecordCount(); // Get record count
if ($ambiente_deleteTotalRecs <= 0) { // No record found, exit
	if ($ambiente_delete->Recordset)
		$ambiente_delete->Recordset->Close();
	$ambiente_delete->Page_Terminate("ambientelist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $ambiente_delete->ShowPageHeader(); ?>
<?php
$ambiente_delete->ShowMessage();
?>
<form name="fambientedelete" id="fambientedelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="ambiente">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($ambiente_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_ambientedelete" class="ewTable ewTableSeparate">
<?php echo $ambiente->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_ambiente_no_ambiente" class="ambiente_no_ambiente"><?php echo $ambiente->no_ambiente->FldCaption() ?></span></td>
		<td><span id="elh_ambiente_nu_tpNegocio" class="ambiente_nu_tpNegocio"><?php echo $ambiente->nu_tpNegocio->FldCaption() ?></span></td>
		<td><span id="elh_ambiente_nu_plataforma" class="ambiente_nu_plataforma"><?php echo $ambiente->nu_plataforma->FldCaption() ?></span></td>
		<td><span id="elh_ambiente_nu_tpSistema" class="ambiente_nu_tpSistema"><?php echo $ambiente->nu_tpSistema->FldCaption() ?></span></td>
		<td><span id="elh_ambiente_nu_roteiro" class="ambiente_nu_roteiro"><?php echo $ambiente->nu_roteiro->FldCaption() ?></span></td>
		<td><span id="elh_ambiente_ic_ativo" class="ambiente_ic_ativo"><?php echo $ambiente->ic_ativo->FldCaption() ?></span></td>
		<td><span id="elh_ambiente_nu_ordem" class="ambiente_nu_ordem"><?php echo $ambiente->nu_ordem->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$ambiente_delete->RecCnt = 0;
$i = 0;
while (!$ambiente_delete->Recordset->EOF) {
	$ambiente_delete->RecCnt++;
	$ambiente_delete->RowCnt++;

	// Set row properties
	$ambiente->ResetAttrs();
	$ambiente->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$ambiente_delete->LoadRowValues($ambiente_delete->Recordset);

	// Render row
	$ambiente_delete->RenderRow();
?>
	<tr<?php echo $ambiente->RowAttributes() ?>>
		<td<?php echo $ambiente->no_ambiente->CellAttributes() ?>>
<span id="el<?php echo $ambiente_delete->RowCnt ?>_ambiente_no_ambiente" class="control-group ambiente_no_ambiente">
<span<?php echo $ambiente->no_ambiente->ViewAttributes() ?>>
<?php echo $ambiente->no_ambiente->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $ambiente->nu_tpNegocio->CellAttributes() ?>>
<span id="el<?php echo $ambiente_delete->RowCnt ?>_ambiente_nu_tpNegocio" class="control-group ambiente_nu_tpNegocio">
<span<?php echo $ambiente->nu_tpNegocio->ViewAttributes() ?>>
<?php echo $ambiente->nu_tpNegocio->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $ambiente->nu_plataforma->CellAttributes() ?>>
<span id="el<?php echo $ambiente_delete->RowCnt ?>_ambiente_nu_plataforma" class="control-group ambiente_nu_plataforma">
<span<?php echo $ambiente->nu_plataforma->ViewAttributes() ?>>
<?php echo $ambiente->nu_plataforma->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $ambiente->nu_tpSistema->CellAttributes() ?>>
<span id="el<?php echo $ambiente_delete->RowCnt ?>_ambiente_nu_tpSistema" class="control-group ambiente_nu_tpSistema">
<span<?php echo $ambiente->nu_tpSistema->ViewAttributes() ?>>
<?php echo $ambiente->nu_tpSistema->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $ambiente->nu_roteiro->CellAttributes() ?>>
<span id="el<?php echo $ambiente_delete->RowCnt ?>_ambiente_nu_roteiro" class="control-group ambiente_nu_roteiro">
<span<?php echo $ambiente->nu_roteiro->ViewAttributes() ?>>
<?php echo $ambiente->nu_roteiro->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $ambiente->ic_ativo->CellAttributes() ?>>
<span id="el<?php echo $ambiente_delete->RowCnt ?>_ambiente_ic_ativo" class="control-group ambiente_ic_ativo">
<span<?php echo $ambiente->ic_ativo->ViewAttributes() ?>>
<?php echo $ambiente->ic_ativo->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $ambiente->nu_ordem->CellAttributes() ?>>
<span id="el<?php echo $ambiente_delete->RowCnt ?>_ambiente_nu_ordem" class="control-group ambiente_nu_ordem">
<span<?php echo $ambiente->nu_ordem->ViewAttributes() ?>>
<?php echo $ambiente->nu_ordem->ListViewValue() ?></span>
</span>
</td>
	</tr>
<?php
	$ambiente_delete->Recordset->MoveNext();
}
$ambiente_delete->Recordset->Close();
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
fambientedelete.Init();
</script>
<?php
$ambiente_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$ambiente_delete->Page_Terminate();
?>
