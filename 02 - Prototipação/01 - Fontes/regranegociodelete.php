<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "regranegocioinfo.php" ?>
<?php include_once "corninfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$regranegocio_delete = NULL; // Initialize page object first

class cregranegocio_delete extends cregranegocio {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'regranegocio';

	// Page object name
	var $PageObjName = 'regranegocio_delete';

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

		// Table object (regranegocio)
		if (!isset($GLOBALS["regranegocio"])) {
			$GLOBALS["regranegocio"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["regranegocio"];
		}

		// Table object (corn)
		if (!isset($GLOBALS['corn'])) $GLOBALS['corn'] = new ccorn();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'regranegocio', TRUE);

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
			$this->Page_Terminate("regranegociolist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		if ($Security->IsLoggedIn() && strval($Security->CurrentUserID()) == "") {
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("regranegociolist.php");
		}
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
			$this->Page_Terminate("regranegociolist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in regranegocio class, regranegocioinfo.php

		$this->CurrentFilter = $sFilter;

		// Check if valid user id
		$sql = $this->GetSQL($this->CurrentFilter, "");
		if ($this->Recordset = ew_LoadRecordset($sql)) {
			$res = TRUE;
			while (!$this->Recordset->EOF) {
				$this->LoadRowValues($this->Recordset);
				if (!$this->ShowOptionLink('delete')) {
					$sUserIdMsg = $Language->Phrase("NoDeletePermission");
					$this->setFailureMessage($sUserIdMsg);
					$res = FALSE;
					break;
				}
				$this->Recordset->MoveNext();
			}
			$this->Recordset->Close();
			if (!$res) $this->Page_Terminate("regranegociolist.php"); // Return to list
		}

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
		$this->co_alternativo->setDbValue($rs->fields('co_alternativo'));
		$this->nu_versao->setDbValue($rs->fields('nu_versao'));
		$this->no_regraNegocio->setDbValue($rs->fields('no_regraNegocio'));
		$this->ds_regraNegocio->setDbValue($rs->fields('ds_regraNegocio'));
		$this->nu_area->setDbValue($rs->fields('nu_area'));
		$this->ds_origemRegra->setDbValue($rs->fields('ds_origemRegra'));
		$this->nu_projeto->setDbValue($rs->fields('nu_projeto'));
		$this->no_tags->setDbValue($rs->fields('no_tags'));
		$this->nu_stRegraNegocio->setDbValue($rs->fields('nu_stRegraNegocio'));
		$this->nu_usuario->setDbValue($rs->fields('nu_usuario'));
		$this->dt_versao->setDbValue($rs->fields('dt_versao'));
		$this->hh_versao->setDbValue($rs->fields('hh_versao'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->co_alternativo->DbValue = $row['co_alternativo'];
		$this->nu_versao->DbValue = $row['nu_versao'];
		$this->no_regraNegocio->DbValue = $row['no_regraNegocio'];
		$this->ds_regraNegocio->DbValue = $row['ds_regraNegocio'];
		$this->nu_area->DbValue = $row['nu_area'];
		$this->ds_origemRegra->DbValue = $row['ds_origemRegra'];
		$this->nu_projeto->DbValue = $row['nu_projeto'];
		$this->no_tags->DbValue = $row['no_tags'];
		$this->nu_stRegraNegocio->DbValue = $row['nu_stRegraNegocio'];
		$this->nu_usuario->DbValue = $row['nu_usuario'];
		$this->dt_versao->DbValue = $row['dt_versao'];
		$this->hh_versao->DbValue = $row['hh_versao'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// co_alternativo
		// nu_versao
		// no_regraNegocio
		// ds_regraNegocio
		// nu_area
		// ds_origemRegra
		// nu_projeto
		// no_tags
		// nu_stRegraNegocio
		// nu_usuario
		// dt_versao
		// hh_versao

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// co_alternativo
			if (strval($this->co_alternativo->CurrentValue) <> "") {
				$sFilterWrk = "[co_rn]" . ew_SearchString("=", $this->co_alternativo->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_rn], [co_rn] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[corn]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_alternativo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [co_rn] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_alternativo->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->co_alternativo->ViewValue = $this->co_alternativo->CurrentValue;
				}
			} else {
				$this->co_alternativo->ViewValue = NULL;
			}
			$this->co_alternativo->ViewCustomAttributes = "";

			// nu_versao
			$this->nu_versao->ViewValue = $this->nu_versao->CurrentValue;
			$this->nu_versao->ViewCustomAttributes = "";

			// no_regraNegocio
			$this->no_regraNegocio->ViewValue = $this->no_regraNegocio->CurrentValue;
			$this->no_regraNegocio->ViewCustomAttributes = "";

			// nu_area
			if (strval($this->nu_area->CurrentValue) <> "") {
				$sFilterWrk = "[nu_area]" . ew_SearchString("=", $this->nu_area->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_area], [no_area] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[area]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_area, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_area] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_area->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_area->ViewValue = $this->nu_area->CurrentValue;
				}
			} else {
				$this->nu_area->ViewValue = NULL;
			}
			$this->nu_area->ViewCustomAttributes = "";

			// nu_projeto
			if (strval($this->nu_projeto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_projeto]" . ew_SearchString("=", $this->nu_projeto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_projeto], [no_projeto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[projeto]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_projeto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_projeto] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_projeto->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_projeto->ViewValue = $this->nu_projeto->CurrentValue;
				}
			} else {
				$this->nu_projeto->ViewValue = NULL;
			}
			$this->nu_projeto->ViewCustomAttributes = "";

			// no_tags
			$this->no_tags->ViewValue = $this->no_tags->CurrentValue;
			$this->no_tags->ViewCustomAttributes = "";

			// nu_stRegraNegocio
			if (strval($this->nu_stRegraNegocio->CurrentValue) <> "") {
				$sFilterWrk = "[nu_stRegraNegocio]" . ew_SearchString("=", $this->nu_stRegraNegocio->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_stRegraNegocio], [no_stRegraNegocio] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[stregranegocio]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_stRegraNegocio, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_stRegraNegocio->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_stRegraNegocio->ViewValue = $this->nu_stRegraNegocio->CurrentValue;
				}
			} else {
				$this->nu_stRegraNegocio->ViewValue = NULL;
			}
			$this->nu_stRegraNegocio->ViewCustomAttributes = "";

			// nu_usuario
			$this->nu_usuario->ViewValue = $this->nu_usuario->CurrentValue;
			if (strval($this->nu_usuario->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuario->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuario, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuario->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuario->ViewValue = $this->nu_usuario->CurrentValue;
				}
			} else {
				$this->nu_usuario->ViewValue = NULL;
			}
			$this->nu_usuario->ViewCustomAttributes = "";

			// dt_versao
			$this->dt_versao->ViewValue = $this->dt_versao->CurrentValue;
			$this->dt_versao->ViewValue = ew_FormatDateTime($this->dt_versao->ViewValue, 7);
			$this->dt_versao->ViewCustomAttributes = "";

			// hh_versao
			$this->hh_versao->ViewValue = $this->hh_versao->CurrentValue;
			$this->hh_versao->ViewValue = ew_FormatDateTime($this->hh_versao->ViewValue, 4);
			$this->hh_versao->ViewCustomAttributes = "";

			// nu_versao
			$this->nu_versao->LinkCustomAttributes = "";
			$this->nu_versao->HrefValue = "";
			$this->nu_versao->TooltipValue = "";

			// no_regraNegocio
			$this->no_regraNegocio->LinkCustomAttributes = "";
			$this->no_regraNegocio->HrefValue = "";
			$this->no_regraNegocio->TooltipValue = "";

			// nu_projeto
			$this->nu_projeto->LinkCustomAttributes = "";
			$this->nu_projeto->HrefValue = "";
			$this->nu_projeto->TooltipValue = "";

			// nu_stRegraNegocio
			$this->nu_stRegraNegocio->LinkCustomAttributes = "";
			$this->nu_stRegraNegocio->HrefValue = "";
			$this->nu_stRegraNegocio->TooltipValue = "";

			// nu_usuario
			$this->nu_usuario->LinkCustomAttributes = "";
			$this->nu_usuario->HrefValue = "";
			$this->nu_usuario->TooltipValue = "";

			// dt_versao
			$this->dt_versao->LinkCustomAttributes = "";
			$this->dt_versao->HrefValue = "";
			$this->dt_versao->TooltipValue = "";
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
				$sThisKey .= $row['co_alternativo'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['nu_versao'];
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

	// Show link optionally based on User ID
	function ShowOptionLink($id = "") {
		global $Security;
		if ($Security->IsLoggedIn() && !$Security->IsAdmin() && !$this->UserIDAllow($id))
			return $Security->IsValidUserID($this->nu_usuario->CurrentValue);
		return TRUE;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "regranegociolist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("delete");
		$Breadcrumb->Add("delete", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'regranegocio';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (delete page)
	function WriteAuditTrailOnDelete(&$rs) {
		if (!$this->AuditTrailOnDelete) return;
		$table = 'regranegocio';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['co_alternativo'];
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_versao'];

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
if (!isset($regranegocio_delete)) $regranegocio_delete = new cregranegocio_delete();

// Page init
$regranegocio_delete->Page_Init();

// Page main
$regranegocio_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$regranegocio_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var regranegocio_delete = new ew_Page("regranegocio_delete");
regranegocio_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = regranegocio_delete.PageID; // For backward compatibility

// Form object
var fregranegociodelete = new ew_Form("fregranegociodelete");

// Form_CustomValidate event
fregranegociodelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fregranegociodelete.ValidateRequired = true;
<?php } else { ?>
fregranegociodelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fregranegociodelete.Lists["x_nu_projeto"] = {"LinkField":"x_nu_projeto","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_projeto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fregranegociodelete.Lists["x_nu_stRegraNegocio"] = {"LinkField":"x_nu_stRegraNegocio","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_stRegraNegocio","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fregranegociodelete.Lists["x_nu_usuario"] = {"LinkField":"x_nu_usuario","Ajax":true,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($regranegocio_delete->Recordset = $regranegocio_delete->LoadRecordset())
	$regranegocio_deleteTotalRecs = $regranegocio_delete->Recordset->RecordCount(); // Get record count
if ($regranegocio_deleteTotalRecs <= 0) { // No record found, exit
	if ($regranegocio_delete->Recordset)
		$regranegocio_delete->Recordset->Close();
	$regranegocio_delete->Page_Terminate("regranegociolist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $regranegocio_delete->ShowPageHeader(); ?>
<?php
$regranegocio_delete->ShowMessage();
?>
<form name="fregranegociodelete" id="fregranegociodelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="regranegocio">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($regranegocio_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_regranegociodelete" class="ewTable ewTableSeparate">
<?php echo $regranegocio->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_regranegocio_nu_versao" class="regranegocio_nu_versao"><?php echo $regranegocio->nu_versao->FldCaption() ?></span></td>
		<td><span id="elh_regranegocio_no_regraNegocio" class="regranegocio_no_regraNegocio"><?php echo $regranegocio->no_regraNegocio->FldCaption() ?></span></td>
		<td><span id="elh_regranegocio_nu_projeto" class="regranegocio_nu_projeto"><?php echo $regranegocio->nu_projeto->FldCaption() ?></span></td>
		<td><span id="elh_regranegocio_nu_stRegraNegocio" class="regranegocio_nu_stRegraNegocio"><?php echo $regranegocio->nu_stRegraNegocio->FldCaption() ?></span></td>
		<td><span id="elh_regranegocio_nu_usuario" class="regranegocio_nu_usuario"><?php echo $regranegocio->nu_usuario->FldCaption() ?></span></td>
		<td><span id="elh_regranegocio_dt_versao" class="regranegocio_dt_versao"><?php echo $regranegocio->dt_versao->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$regranegocio_delete->RecCnt = 0;
$i = 0;
while (!$regranegocio_delete->Recordset->EOF) {
	$regranegocio_delete->RecCnt++;
	$regranegocio_delete->RowCnt++;

	// Set row properties
	$regranegocio->ResetAttrs();
	$regranegocio->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$regranegocio_delete->LoadRowValues($regranegocio_delete->Recordset);

	// Render row
	$regranegocio_delete->RenderRow();
?>
	<tr<?php echo $regranegocio->RowAttributes() ?>>
		<td<?php echo $regranegocio->nu_versao->CellAttributes() ?>>
<span id="el<?php echo $regranegocio_delete->RowCnt ?>_regranegocio_nu_versao" class="control-group regranegocio_nu_versao">
<span<?php echo $regranegocio->nu_versao->ViewAttributes() ?>>
<?php echo $regranegocio->nu_versao->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $regranegocio->no_regraNegocio->CellAttributes() ?>>
<span id="el<?php echo $regranegocio_delete->RowCnt ?>_regranegocio_no_regraNegocio" class="control-group regranegocio_no_regraNegocio">
<span<?php echo $regranegocio->no_regraNegocio->ViewAttributes() ?>>
<?php echo $regranegocio->no_regraNegocio->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $regranegocio->nu_projeto->CellAttributes() ?>>
<span id="el<?php echo $regranegocio_delete->RowCnt ?>_regranegocio_nu_projeto" class="control-group regranegocio_nu_projeto">
<span<?php echo $regranegocio->nu_projeto->ViewAttributes() ?>>
<?php echo $regranegocio->nu_projeto->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $regranegocio->nu_stRegraNegocio->CellAttributes() ?>>
<span id="el<?php echo $regranegocio_delete->RowCnt ?>_regranegocio_nu_stRegraNegocio" class="control-group regranegocio_nu_stRegraNegocio">
<span<?php echo $regranegocio->nu_stRegraNegocio->ViewAttributes() ?>>
<?php echo $regranegocio->nu_stRegraNegocio->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $regranegocio->nu_usuario->CellAttributes() ?>>
<span id="el<?php echo $regranegocio_delete->RowCnt ?>_regranegocio_nu_usuario" class="control-group regranegocio_nu_usuario">
<span<?php echo $regranegocio->nu_usuario->ViewAttributes() ?>>
<?php echo $regranegocio->nu_usuario->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $regranegocio->dt_versao->CellAttributes() ?>>
<span id="el<?php echo $regranegocio_delete->RowCnt ?>_regranegocio_dt_versao" class="control-group regranegocio_dt_versao">
<span<?php echo $regranegocio->dt_versao->ViewAttributes() ?>>
<?php echo $regranegocio->dt_versao->ListViewValue() ?></span>
</span>
</td>
	</tr>
<?php
	$regranegocio_delete->Recordset->MoveNext();
}
$regranegocio_delete->Recordset->Close();
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
fregranegociodelete.Init();
</script>
<?php
$regranegocio_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$regranegocio_delete->Page_Terminate();
?>
