<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "ambiente_phistoricoinfo.php" ?>
<?php include_once "ambienteinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$ambiente_phistorico_delete = NULL; // Initialize page object first

class cambiente_phistorico_delete extends cambiente_phistorico {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'ambiente_phistorico';

	// Page object name
	var $PageObjName = 'ambiente_phistorico_delete';

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

		// Table object (ambiente_phistorico)
		if (!isset($GLOBALS["ambiente_phistorico"])) {
			$GLOBALS["ambiente_phistorico"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["ambiente_phistorico"];
		}

		// Table object (ambiente)
		if (!isset($GLOBALS['ambiente'])) $GLOBALS['ambiente'] = new cambiente();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'ambiente_phistorico', TRUE);

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
			$this->Page_Terminate("ambiente_phistoricolist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action
		$this->nu_projhist->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			$this->Page_Terminate("ambiente_phistoricolist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in ambiente_phistorico class, ambiente_phistoricoinfo.php

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
		$this->nu_projhist->setDbValue($rs->fields('nu_projhist'));
		$this->nu_ambiente->setDbValue($rs->fields('nu_ambiente'));
		$this->no_projeto->setDbValue($rs->fields('no_projeto'));
		$this->ds_projeto->setDbValue($rs->fields('ds_projeto'));
		$this->qt_pf->setDbValue($rs->fields('qt_pf'));
		$this->qt_sloc->setDbValue($rs->fields('qt_sloc'));
		$this->qt_slocPf->setDbValue($rs->fields('qt_slocPf'));
		$this->qt_esforcoReal->setDbValue($rs->fields('qt_esforcoReal'));
		$this->qt_esforcoRealPm->setDbValue($rs->fields('qt_esforcoRealPm'));
		$this->qt_prazoRealM->setDbValue($rs->fields('qt_prazoRealM'));
		$this->ic_situacao->setDbValue($rs->fields('ic_situacao'));
		$this->ds_acoes->setDbValue($rs->fields('ds_acoes'));
		$this->nu_usuarioInc->setDbValue($rs->fields('nu_usuarioInc'));
		$this->dh_inclusao->setDbValue($rs->fields('dh_inclusao'));
		$this->nu_usuarioAlt->setDbValue($rs->fields('nu_usuarioAlt'));
		$this->dh_alteracao->setDbValue($rs->fields('dh_alteracao'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_projhist->DbValue = $row['nu_projhist'];
		$this->nu_ambiente->DbValue = $row['nu_ambiente'];
		$this->no_projeto->DbValue = $row['no_projeto'];
		$this->ds_projeto->DbValue = $row['ds_projeto'];
		$this->qt_pf->DbValue = $row['qt_pf'];
		$this->qt_sloc->DbValue = $row['qt_sloc'];
		$this->qt_slocPf->DbValue = $row['qt_slocPf'];
		$this->qt_esforcoReal->DbValue = $row['qt_esforcoReal'];
		$this->qt_esforcoRealPm->DbValue = $row['qt_esforcoRealPm'];
		$this->qt_prazoRealM->DbValue = $row['qt_prazoRealM'];
		$this->ic_situacao->DbValue = $row['ic_situacao'];
		$this->ds_acoes->DbValue = $row['ds_acoes'];
		$this->nu_usuarioInc->DbValue = $row['nu_usuarioInc'];
		$this->dh_inclusao->DbValue = $row['dh_inclusao'];
		$this->nu_usuarioAlt->DbValue = $row['nu_usuarioAlt'];
		$this->dh_alteracao->DbValue = $row['dh_alteracao'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->qt_pf->FormValue == $this->qt_pf->CurrentValue && is_numeric(ew_StrToFloat($this->qt_pf->CurrentValue)))
			$this->qt_pf->CurrentValue = ew_StrToFloat($this->qt_pf->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_sloc->FormValue == $this->qt_sloc->CurrentValue && is_numeric(ew_StrToFloat($this->qt_sloc->CurrentValue)))
			$this->qt_sloc->CurrentValue = ew_StrToFloat($this->qt_sloc->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_slocPf->FormValue == $this->qt_slocPf->CurrentValue && is_numeric(ew_StrToFloat($this->qt_slocPf->CurrentValue)))
			$this->qt_slocPf->CurrentValue = ew_StrToFloat($this->qt_slocPf->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_esforcoReal->FormValue == $this->qt_esforcoReal->CurrentValue && is_numeric(ew_StrToFloat($this->qt_esforcoReal->CurrentValue)))
			$this->qt_esforcoReal->CurrentValue = ew_StrToFloat($this->qt_esforcoReal->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_esforcoRealPm->FormValue == $this->qt_esforcoRealPm->CurrentValue && is_numeric(ew_StrToFloat($this->qt_esforcoRealPm->CurrentValue)))
			$this->qt_esforcoRealPm->CurrentValue = ew_StrToFloat($this->qt_esforcoRealPm->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_prazoRealM->FormValue == $this->qt_prazoRealM->CurrentValue && is_numeric(ew_StrToFloat($this->qt_prazoRealM->CurrentValue)))
			$this->qt_prazoRealM->CurrentValue = ew_StrToFloat($this->qt_prazoRealM->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_projhist
		// nu_ambiente
		// no_projeto
		// ds_projeto
		// qt_pf
		// qt_sloc
		// qt_slocPf
		// qt_esforcoReal
		// qt_esforcoRealPm
		// qt_prazoRealM
		// ic_situacao
		// ds_acoes
		// nu_usuarioInc
		// dh_inclusao
		// nu_usuarioAlt
		// dh_alteracao

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_projhist
			$this->nu_projhist->ViewValue = $this->nu_projhist->CurrentValue;
			$this->nu_projhist->ViewCustomAttributes = "";

			// nu_ambiente
			if (strval($this->nu_ambiente->CurrentValue) <> "") {
				$sFilterWrk = "[nu_ambiente]" . ew_SearchString("=", $this->nu_ambiente->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_ambiente], [no_ambiente] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ambiente]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_ambiente, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_ambiente] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_ambiente->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_ambiente->ViewValue = $this->nu_ambiente->CurrentValue;
				}
			} else {
				$this->nu_ambiente->ViewValue = NULL;
			}
			$this->nu_ambiente->ViewCustomAttributes = "";

			// no_projeto
			$this->no_projeto->ViewValue = $this->no_projeto->CurrentValue;
			$this->no_projeto->ViewCustomAttributes = "";

			// qt_pf
			$this->qt_pf->ViewValue = $this->qt_pf->CurrentValue;
			$this->qt_pf->ViewCustomAttributes = "";

			// qt_sloc
			$this->qt_sloc->ViewValue = $this->qt_sloc->CurrentValue;
			$this->qt_sloc->ViewCustomAttributes = "";

			// qt_slocPf
			$this->qt_slocPf->ViewValue = $this->qt_slocPf->CurrentValue;
			$this->qt_slocPf->ViewCustomAttributes = "";

			// qt_esforcoReal
			$this->qt_esforcoReal->ViewValue = $this->qt_esforcoReal->CurrentValue;
			$this->qt_esforcoReal->ViewCustomAttributes = "";

			// qt_esforcoRealPm
			$this->qt_esforcoRealPm->ViewValue = $this->qt_esforcoRealPm->CurrentValue;
			$this->qt_esforcoRealPm->ViewCustomAttributes = "";

			// qt_prazoRealM
			$this->qt_prazoRealM->ViewValue = $this->qt_prazoRealM->CurrentValue;
			$this->qt_prazoRealM->ViewCustomAttributes = "";

			// ic_situacao
			if (strval($this->ic_situacao->CurrentValue) <> "") {
				switch ($this->ic_situacao->CurrentValue) {
					case $this->ic_situacao->FldTagValue(1):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(1) <> "" ? $this->ic_situacao->FldTagCaption(1) : $this->ic_situacao->CurrentValue;
						break;
					case $this->ic_situacao->FldTagValue(2):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(2) <> "" ? $this->ic_situacao->FldTagCaption(2) : $this->ic_situacao->CurrentValue;
						break;
					default:
						$this->ic_situacao->ViewValue = $this->ic_situacao->CurrentValue;
				}
			} else {
				$this->ic_situacao->ViewValue = NULL;
			}
			$this->ic_situacao->ViewCustomAttributes = "";

			// nu_usuarioInc
			if (strval($this->nu_usuarioInc->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioInc->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioInc, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioInc->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioInc->ViewValue = $this->nu_usuarioInc->CurrentValue;
				}
			} else {
				$this->nu_usuarioInc->ViewValue = NULL;
			}
			$this->nu_usuarioInc->ViewCustomAttributes = "";

			// dh_inclusao
			$this->dh_inclusao->ViewValue = $this->dh_inclusao->CurrentValue;
			$this->dh_inclusao->ViewValue = ew_FormatDateTime($this->dh_inclusao->ViewValue, 7);
			$this->dh_inclusao->ViewCustomAttributes = "";

			// nu_usuarioAlt
			$this->nu_usuarioAlt->ViewValue = $this->nu_usuarioAlt->CurrentValue;
			if (strval($this->nu_usuarioAlt->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioAlt->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioAlt, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioAlt->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioAlt->ViewValue = $this->nu_usuarioAlt->CurrentValue;
				}
			} else {
				$this->nu_usuarioAlt->ViewValue = NULL;
			}
			$this->nu_usuarioAlt->ViewCustomAttributes = "";

			// dh_alteracao
			$this->dh_alteracao->ViewValue = $this->dh_alteracao->CurrentValue;
			$this->dh_alteracao->ViewValue = ew_FormatDateTime($this->dh_alteracao->ViewValue, 7);
			$this->dh_alteracao->ViewCustomAttributes = "";

			// nu_projhist
			$this->nu_projhist->LinkCustomAttributes = "";
			$this->nu_projhist->HrefValue = "";
			$this->nu_projhist->TooltipValue = "";

			// no_projeto
			$this->no_projeto->LinkCustomAttributes = "";
			$this->no_projeto->HrefValue = "";
			$this->no_projeto->TooltipValue = "";

			// qt_pf
			$this->qt_pf->LinkCustomAttributes = "";
			$this->qt_pf->HrefValue = "";
			$this->qt_pf->TooltipValue = "";

			// qt_sloc
			$this->qt_sloc->LinkCustomAttributes = "";
			$this->qt_sloc->HrefValue = "";
			$this->qt_sloc->TooltipValue = "";

			// qt_slocPf
			$this->qt_slocPf->LinkCustomAttributes = "";
			$this->qt_slocPf->HrefValue = "";
			$this->qt_slocPf->TooltipValue = "";

			// qt_esforcoReal
			$this->qt_esforcoReal->LinkCustomAttributes = "";
			$this->qt_esforcoReal->HrefValue = "";
			$this->qt_esforcoReal->TooltipValue = "";

			// qt_esforcoRealPm
			$this->qt_esforcoRealPm->LinkCustomAttributes = "";
			$this->qt_esforcoRealPm->HrefValue = "";
			$this->qt_esforcoRealPm->TooltipValue = "";

			// qt_prazoRealM
			$this->qt_prazoRealM->LinkCustomAttributes = "";
			$this->qt_prazoRealM->HrefValue = "";
			$this->qt_prazoRealM->TooltipValue = "";

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
				$sThisKey .= $row['nu_projhist'];
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "ambiente_phistoricolist.php", $this->TableVar);
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
if (!isset($ambiente_phistorico_delete)) $ambiente_phistorico_delete = new cambiente_phistorico_delete();

// Page init
$ambiente_phistorico_delete->Page_Init();

// Page main
$ambiente_phistorico_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$ambiente_phistorico_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var ambiente_phistorico_delete = new ew_Page("ambiente_phistorico_delete");
ambiente_phistorico_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = ambiente_phistorico_delete.PageID; // For backward compatibility

// Form object
var fambiente_phistoricodelete = new ew_Form("fambiente_phistoricodelete");

// Form_CustomValidate event
fambiente_phistoricodelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fambiente_phistoricodelete.ValidateRequired = true;
<?php } else { ?>
fambiente_phistoricodelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($ambiente_phistorico_delete->Recordset = $ambiente_phistorico_delete->LoadRecordset())
	$ambiente_phistorico_deleteTotalRecs = $ambiente_phistorico_delete->Recordset->RecordCount(); // Get record count
if ($ambiente_phistorico_deleteTotalRecs <= 0) { // No record found, exit
	if ($ambiente_phistorico_delete->Recordset)
		$ambiente_phistorico_delete->Recordset->Close();
	$ambiente_phistorico_delete->Page_Terminate("ambiente_phistoricolist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $ambiente_phistorico_delete->ShowPageHeader(); ?>
<?php
$ambiente_phistorico_delete->ShowMessage();
?>
<form name="fambiente_phistoricodelete" id="fambiente_phistoricodelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="ambiente_phistorico">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($ambiente_phistorico_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_ambiente_phistoricodelete" class="ewTable ewTableSeparate">
<?php echo $ambiente_phistorico->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_ambiente_phistorico_nu_projhist" class="ambiente_phistorico_nu_projhist"><?php echo $ambiente_phistorico->nu_projhist->FldCaption() ?></span></td>
		<td><span id="elh_ambiente_phistorico_no_projeto" class="ambiente_phistorico_no_projeto"><?php echo $ambiente_phistorico->no_projeto->FldCaption() ?></span></td>
		<td><span id="elh_ambiente_phistorico_qt_pf" class="ambiente_phistorico_qt_pf"><?php echo $ambiente_phistorico->qt_pf->FldCaption() ?></span></td>
		<td><span id="elh_ambiente_phistorico_qt_sloc" class="ambiente_phistorico_qt_sloc"><?php echo $ambiente_phistorico->qt_sloc->FldCaption() ?></span></td>
		<td><span id="elh_ambiente_phistorico_qt_slocPf" class="ambiente_phistorico_qt_slocPf"><?php echo $ambiente_phistorico->qt_slocPf->FldCaption() ?></span></td>
		<td><span id="elh_ambiente_phistorico_qt_esforcoReal" class="ambiente_phistorico_qt_esforcoReal"><?php echo $ambiente_phistorico->qt_esforcoReal->FldCaption() ?></span></td>
		<td><span id="elh_ambiente_phistorico_qt_esforcoRealPm" class="ambiente_phistorico_qt_esforcoRealPm"><?php echo $ambiente_phistorico->qt_esforcoRealPm->FldCaption() ?></span></td>
		<td><span id="elh_ambiente_phistorico_qt_prazoRealM" class="ambiente_phistorico_qt_prazoRealM"><?php echo $ambiente_phistorico->qt_prazoRealM->FldCaption() ?></span></td>
		<td><span id="elh_ambiente_phistorico_ic_situacao" class="ambiente_phistorico_ic_situacao"><?php echo $ambiente_phistorico->ic_situacao->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$ambiente_phistorico_delete->RecCnt = 0;
$i = 0;
while (!$ambiente_phistorico_delete->Recordset->EOF) {
	$ambiente_phistorico_delete->RecCnt++;
	$ambiente_phistorico_delete->RowCnt++;

	// Set row properties
	$ambiente_phistorico->ResetAttrs();
	$ambiente_phistorico->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$ambiente_phistorico_delete->LoadRowValues($ambiente_phistorico_delete->Recordset);

	// Render row
	$ambiente_phistorico_delete->RenderRow();
?>
	<tr<?php echo $ambiente_phistorico->RowAttributes() ?>>
		<td<?php echo $ambiente_phistorico->nu_projhist->CellAttributes() ?>>
<span id="el<?php echo $ambiente_phistorico_delete->RowCnt ?>_ambiente_phistorico_nu_projhist" class="control-group ambiente_phistorico_nu_projhist">
<span<?php echo $ambiente_phistorico->nu_projhist->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->nu_projhist->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $ambiente_phistorico->no_projeto->CellAttributes() ?>>
<span id="el<?php echo $ambiente_phistorico_delete->RowCnt ?>_ambiente_phistorico_no_projeto" class="control-group ambiente_phistorico_no_projeto">
<span<?php echo $ambiente_phistorico->no_projeto->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->no_projeto->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $ambiente_phistorico->qt_pf->CellAttributes() ?>>
<span id="el<?php echo $ambiente_phistorico_delete->RowCnt ?>_ambiente_phistorico_qt_pf" class="control-group ambiente_phistorico_qt_pf">
<span<?php echo $ambiente_phistorico->qt_pf->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->qt_pf->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $ambiente_phistorico->qt_sloc->CellAttributes() ?>>
<span id="el<?php echo $ambiente_phistorico_delete->RowCnt ?>_ambiente_phistorico_qt_sloc" class="control-group ambiente_phistorico_qt_sloc">
<span<?php echo $ambiente_phistorico->qt_sloc->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->qt_sloc->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $ambiente_phistorico->qt_slocPf->CellAttributes() ?>>
<span id="el<?php echo $ambiente_phistorico_delete->RowCnt ?>_ambiente_phistorico_qt_slocPf" class="control-group ambiente_phistorico_qt_slocPf">
<span<?php echo $ambiente_phistorico->qt_slocPf->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->qt_slocPf->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $ambiente_phistorico->qt_esforcoReal->CellAttributes() ?>>
<span id="el<?php echo $ambiente_phistorico_delete->RowCnt ?>_ambiente_phistorico_qt_esforcoReal" class="control-group ambiente_phistorico_qt_esforcoReal">
<span<?php echo $ambiente_phistorico->qt_esforcoReal->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->qt_esforcoReal->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $ambiente_phistorico->qt_esforcoRealPm->CellAttributes() ?>>
<span id="el<?php echo $ambiente_phistorico_delete->RowCnt ?>_ambiente_phistorico_qt_esforcoRealPm" class="control-group ambiente_phistorico_qt_esforcoRealPm">
<span<?php echo $ambiente_phistorico->qt_esforcoRealPm->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->qt_esforcoRealPm->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $ambiente_phistorico->qt_prazoRealM->CellAttributes() ?>>
<span id="el<?php echo $ambiente_phistorico_delete->RowCnt ?>_ambiente_phistorico_qt_prazoRealM" class="control-group ambiente_phistorico_qt_prazoRealM">
<span<?php echo $ambiente_phistorico->qt_prazoRealM->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->qt_prazoRealM->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $ambiente_phistorico->ic_situacao->CellAttributes() ?>>
<span id="el<?php echo $ambiente_phistorico_delete->RowCnt ?>_ambiente_phistorico_ic_situacao" class="control-group ambiente_phistorico_ic_situacao">
<span<?php echo $ambiente_phistorico->ic_situacao->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->ic_situacao->ListViewValue() ?></span>
</span>
</td>
	</tr>
<?php
	$ambiente_phistorico_delete->Recordset->MoveNext();
}
$ambiente_phistorico_delete->Recordset->Close();
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
fambiente_phistoricodelete.Init();
</script>
<?php
$ambiente_phistorico_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$ambiente_phistorico_delete->Page_Terminate();
?>
