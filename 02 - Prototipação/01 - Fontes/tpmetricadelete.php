<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "tpmetricainfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$tpmetrica_delete = NULL; // Initialize page object first

class ctpmetrica_delete extends ctpmetrica {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'tpmetrica';

	// Page object name
	var $PageObjName = 'tpmetrica_delete';

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

		// Table object (tpmetrica)
		if (!isset($GLOBALS["tpmetrica"])) {
			$GLOBALS["tpmetrica"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tpmetrica"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tpmetrica', TRUE);

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
			$this->Page_Terminate("tpmetricalist.php");
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
			$this->Page_Terminate("tpmetricalist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in tpmetrica class, tpmetricainfo.php

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
		$this->nu_tpMetrica->setDbValue($rs->fields('nu_tpMetrica'));
		$this->no_tpMetrica->setDbValue($rs->fields('no_tpMetrica'));
		$this->ic_tpMetrica->setDbValue($rs->fields('ic_tpMetrica'));
		$this->ic_tpAplicacao->setDbValue($rs->fields('ic_tpAplicacao'));
		$this->ds_helpTela->setDbValue($rs->fields('ds_helpTela'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
		$this->ic_metodoEsforco->setDbValue($rs->fields('ic_metodoEsforco'));
		$this->ic_metodoPrazo->setDbValue($rs->fields('ic_metodoPrazo'));
		$this->ic_metodoCusto->setDbValue($rs->fields('ic_metodoCusto'));
		$this->ic_metodoRecursos->setDbValue($rs->fields('ic_metodoRecursos'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_tpMetrica->DbValue = $row['nu_tpMetrica'];
		$this->no_tpMetrica->DbValue = $row['no_tpMetrica'];
		$this->ic_tpMetrica->DbValue = $row['ic_tpMetrica'];
		$this->ic_tpAplicacao->DbValue = $row['ic_tpAplicacao'];
		$this->ds_helpTela->DbValue = $row['ds_helpTela'];
		$this->ic_ativo->DbValue = $row['ic_ativo'];
		$this->ic_metodoEsforco->DbValue = $row['ic_metodoEsforco'];
		$this->ic_metodoPrazo->DbValue = $row['ic_metodoPrazo'];
		$this->ic_metodoCusto->DbValue = $row['ic_metodoCusto'];
		$this->ic_metodoRecursos->DbValue = $row['ic_metodoRecursos'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_tpMetrica
		// no_tpMetrica
		// ic_tpMetrica
		// ic_tpAplicacao
		// ds_helpTela
		// ic_ativo
		// ic_metodoEsforco
		// ic_metodoPrazo
		// ic_metodoCusto
		// ic_metodoRecursos

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_tpMetrica
			$this->nu_tpMetrica->ViewValue = $this->nu_tpMetrica->CurrentValue;
			$this->nu_tpMetrica->ViewCustomAttributes = "";

			// no_tpMetrica
			$this->no_tpMetrica->ViewValue = $this->no_tpMetrica->CurrentValue;
			$this->no_tpMetrica->ViewCustomAttributes = "";

			// ic_tpMetrica
			if (strval($this->ic_tpMetrica->CurrentValue) <> "") {
				switch ($this->ic_tpMetrica->CurrentValue) {
					case $this->ic_tpMetrica->FldTagValue(1):
						$this->ic_tpMetrica->ViewValue = $this->ic_tpMetrica->FldTagCaption(1) <> "" ? $this->ic_tpMetrica->FldTagCaption(1) : $this->ic_tpMetrica->CurrentValue;
						break;
					case $this->ic_tpMetrica->FldTagValue(2):
						$this->ic_tpMetrica->ViewValue = $this->ic_tpMetrica->FldTagCaption(2) <> "" ? $this->ic_tpMetrica->FldTagCaption(2) : $this->ic_tpMetrica->CurrentValue;
						break;
					case $this->ic_tpMetrica->FldTagValue(3):
						$this->ic_tpMetrica->ViewValue = $this->ic_tpMetrica->FldTagCaption(3) <> "" ? $this->ic_tpMetrica->FldTagCaption(3) : $this->ic_tpMetrica->CurrentValue;
						break;
					default:
						$this->ic_tpMetrica->ViewValue = $this->ic_tpMetrica->CurrentValue;
				}
			} else {
				$this->ic_tpMetrica->ViewValue = NULL;
			}
			$this->ic_tpMetrica->ViewCustomAttributes = "";

			// ic_tpAplicacao
			if (strval($this->ic_tpAplicacao->CurrentValue) <> "") {
				$this->ic_tpAplicacao->ViewValue = "";
				$arwrk = explode(",", strval($this->ic_tpAplicacao->CurrentValue));
				$cnt = count($arwrk);
				for ($ari = 0; $ari < $cnt; $ari++) {
					switch (trim($arwrk[$ari])) {
						case $this->ic_tpAplicacao->FldTagValue(1):
							$this->ic_tpAplicacao->ViewValue .= $this->ic_tpAplicacao->FldTagCaption(1) <> "" ? $this->ic_tpAplicacao->FldTagCaption(1) : trim($arwrk[$ari]);
							break;
						case $this->ic_tpAplicacao->FldTagValue(2):
							$this->ic_tpAplicacao->ViewValue .= $this->ic_tpAplicacao->FldTagCaption(2) <> "" ? $this->ic_tpAplicacao->FldTagCaption(2) : trim($arwrk[$ari]);
							break;
						case $this->ic_tpAplicacao->FldTagValue(3):
							$this->ic_tpAplicacao->ViewValue .= $this->ic_tpAplicacao->FldTagCaption(3) <> "" ? $this->ic_tpAplicacao->FldTagCaption(3) : trim($arwrk[$ari]);
							break;
						default:
							$this->ic_tpAplicacao->ViewValue .= trim($arwrk[$ari]);
					}
					if ($ari < $cnt-1) $this->ic_tpAplicacao->ViewValue .= ew_ViewOptionSeparator($ari);
				}
			} else {
				$this->ic_tpAplicacao->ViewValue = NULL;
			}
			$this->ic_tpAplicacao->ViewCustomAttributes = "";

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

			// ic_metodoEsforco
			if (strval($this->ic_metodoEsforco->CurrentValue) <> "") {
				switch ($this->ic_metodoEsforco->CurrentValue) {
					case $this->ic_metodoEsforco->FldTagValue(1):
						$this->ic_metodoEsforco->ViewValue = $this->ic_metodoEsforco->FldTagCaption(1) <> "" ? $this->ic_metodoEsforco->FldTagCaption(1) : $this->ic_metodoEsforco->CurrentValue;
						break;
					case $this->ic_metodoEsforco->FldTagValue(2):
						$this->ic_metodoEsforco->ViewValue = $this->ic_metodoEsforco->FldTagCaption(2) <> "" ? $this->ic_metodoEsforco->FldTagCaption(2) : $this->ic_metodoEsforco->CurrentValue;
						break;
					default:
						$this->ic_metodoEsforco->ViewValue = $this->ic_metodoEsforco->CurrentValue;
				}
			} else {
				$this->ic_metodoEsforco->ViewValue = NULL;
			}
			$this->ic_metodoEsforco->ViewCustomAttributes = "";

			// ic_metodoPrazo
			if (strval($this->ic_metodoPrazo->CurrentValue) <> "") {
				switch ($this->ic_metodoPrazo->CurrentValue) {
					case $this->ic_metodoPrazo->FldTagValue(1):
						$this->ic_metodoPrazo->ViewValue = $this->ic_metodoPrazo->FldTagCaption(1) <> "" ? $this->ic_metodoPrazo->FldTagCaption(1) : $this->ic_metodoPrazo->CurrentValue;
						break;
					case $this->ic_metodoPrazo->FldTagValue(2):
						$this->ic_metodoPrazo->ViewValue = $this->ic_metodoPrazo->FldTagCaption(2) <> "" ? $this->ic_metodoPrazo->FldTagCaption(2) : $this->ic_metodoPrazo->CurrentValue;
						break;
					case $this->ic_metodoPrazo->FldTagValue(3):
						$this->ic_metodoPrazo->ViewValue = $this->ic_metodoPrazo->FldTagCaption(3) <> "" ? $this->ic_metodoPrazo->FldTagCaption(3) : $this->ic_metodoPrazo->CurrentValue;
						break;
					case $this->ic_metodoPrazo->FldTagValue(4):
						$this->ic_metodoPrazo->ViewValue = $this->ic_metodoPrazo->FldTagCaption(4) <> "" ? $this->ic_metodoPrazo->FldTagCaption(4) : $this->ic_metodoPrazo->CurrentValue;
						break;
					default:
						$this->ic_metodoPrazo->ViewValue = $this->ic_metodoPrazo->CurrentValue;
				}
			} else {
				$this->ic_metodoPrazo->ViewValue = NULL;
			}
			$this->ic_metodoPrazo->ViewCustomAttributes = "";

			// ic_metodoCusto
			if (strval($this->ic_metodoCusto->CurrentValue) <> "") {
				switch ($this->ic_metodoCusto->CurrentValue) {
					case $this->ic_metodoCusto->FldTagValue(1):
						$this->ic_metodoCusto->ViewValue = $this->ic_metodoCusto->FldTagCaption(1) <> "" ? $this->ic_metodoCusto->FldTagCaption(1) : $this->ic_metodoCusto->CurrentValue;
						break;
					case $this->ic_metodoCusto->FldTagValue(2):
						$this->ic_metodoCusto->ViewValue = $this->ic_metodoCusto->FldTagCaption(2) <> "" ? $this->ic_metodoCusto->FldTagCaption(2) : $this->ic_metodoCusto->CurrentValue;
						break;
					default:
						$this->ic_metodoCusto->ViewValue = $this->ic_metodoCusto->CurrentValue;
				}
			} else {
				$this->ic_metodoCusto->ViewValue = NULL;
			}
			$this->ic_metodoCusto->ViewCustomAttributes = "";

			// ic_metodoRecursos
			if (strval($this->ic_metodoRecursos->CurrentValue) <> "") {
				switch ($this->ic_metodoRecursos->CurrentValue) {
					case $this->ic_metodoRecursos->FldTagValue(1):
						$this->ic_metodoRecursos->ViewValue = $this->ic_metodoRecursos->FldTagCaption(1) <> "" ? $this->ic_metodoRecursos->FldTagCaption(1) : $this->ic_metodoRecursos->CurrentValue;
						break;
					case $this->ic_metodoRecursos->FldTagValue(2):
						$this->ic_metodoRecursos->ViewValue = $this->ic_metodoRecursos->FldTagCaption(2) <> "" ? $this->ic_metodoRecursos->FldTagCaption(2) : $this->ic_metodoRecursos->CurrentValue;
						break;
					default:
						$this->ic_metodoRecursos->ViewValue = $this->ic_metodoRecursos->CurrentValue;
				}
			} else {
				$this->ic_metodoRecursos->ViewValue = NULL;
			}
			$this->ic_metodoRecursos->ViewCustomAttributes = "";

			// no_tpMetrica
			$this->no_tpMetrica->LinkCustomAttributes = "";
			$this->no_tpMetrica->HrefValue = "";
			$this->no_tpMetrica->TooltipValue = "";

			// ic_tpMetrica
			$this->ic_tpMetrica->LinkCustomAttributes = "";
			$this->ic_tpMetrica->HrefValue = "";
			$this->ic_tpMetrica->TooltipValue = "";

			// ic_tpAplicacao
			$this->ic_tpAplicacao->LinkCustomAttributes = "";
			$this->ic_tpAplicacao->HrefValue = "";
			$this->ic_tpAplicacao->TooltipValue = "";

			// ic_ativo
			$this->ic_ativo->LinkCustomAttributes = "";
			$this->ic_ativo->HrefValue = "";
			$this->ic_ativo->TooltipValue = "";

			// ic_metodoEsforco
			$this->ic_metodoEsforco->LinkCustomAttributes = "";
			$this->ic_metodoEsforco->HrefValue = "";
			$this->ic_metodoEsforco->TooltipValue = "";

			// ic_metodoPrazo
			$this->ic_metodoPrazo->LinkCustomAttributes = "";
			$this->ic_metodoPrazo->HrefValue = "";
			$this->ic_metodoPrazo->TooltipValue = "";

			// ic_metodoCusto
			$this->ic_metodoCusto->LinkCustomAttributes = "";
			$this->ic_metodoCusto->HrefValue = "";
			$this->ic_metodoCusto->TooltipValue = "";

			// ic_metodoRecursos
			$this->ic_metodoRecursos->LinkCustomAttributes = "";
			$this->ic_metodoRecursos->HrefValue = "";
			$this->ic_metodoRecursos->TooltipValue = "";
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
				$sThisKey .= $row['nu_tpMetrica'];
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "tpmetricalist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("delete");
		$Breadcrumb->Add("delete", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'tpmetrica';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (delete page)
	function WriteAuditTrailOnDelete(&$rs) {
		if (!$this->AuditTrailOnDelete) return;
		$table = 'tpmetrica';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_tpMetrica'];

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
if (!isset($tpmetrica_delete)) $tpmetrica_delete = new ctpmetrica_delete();

// Page init
$tpmetrica_delete->Page_Init();

// Page main
$tpmetrica_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tpmetrica_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var tpmetrica_delete = new ew_Page("tpmetrica_delete");
tpmetrica_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = tpmetrica_delete.PageID; // For backward compatibility

// Form object
var ftpmetricadelete = new ew_Form("ftpmetricadelete");

// Form_CustomValidate event
ftpmetricadelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftpmetricadelete.ValidateRequired = true;
<?php } else { ?>
ftpmetricadelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($tpmetrica_delete->Recordset = $tpmetrica_delete->LoadRecordset())
	$tpmetrica_deleteTotalRecs = $tpmetrica_delete->Recordset->RecordCount(); // Get record count
if ($tpmetrica_deleteTotalRecs <= 0) { // No record found, exit
	if ($tpmetrica_delete->Recordset)
		$tpmetrica_delete->Recordset->Close();
	$tpmetrica_delete->Page_Terminate("tpmetricalist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $tpmetrica_delete->ShowPageHeader(); ?>
<?php
$tpmetrica_delete->ShowMessage();
?>
<form name="ftpmetricadelete" id="ftpmetricadelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="tpmetrica">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($tpmetrica_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_tpmetricadelete" class="ewTable ewTableSeparate">
<?php echo $tpmetrica->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_tpmetrica_no_tpMetrica" class="tpmetrica_no_tpMetrica"><?php echo $tpmetrica->no_tpMetrica->FldCaption() ?></span></td>
		<td><span id="elh_tpmetrica_ic_tpMetrica" class="tpmetrica_ic_tpMetrica"><?php echo $tpmetrica->ic_tpMetrica->FldCaption() ?></span></td>
		<td><span id="elh_tpmetrica_ic_tpAplicacao" class="tpmetrica_ic_tpAplicacao"><?php echo $tpmetrica->ic_tpAplicacao->FldCaption() ?></span></td>
		<td><span id="elh_tpmetrica_ic_ativo" class="tpmetrica_ic_ativo"><?php echo $tpmetrica->ic_ativo->FldCaption() ?></span></td>
		<td><span id="elh_tpmetrica_ic_metodoEsforco" class="tpmetrica_ic_metodoEsforco"><?php echo $tpmetrica->ic_metodoEsforco->FldCaption() ?></span></td>
		<td><span id="elh_tpmetrica_ic_metodoPrazo" class="tpmetrica_ic_metodoPrazo"><?php echo $tpmetrica->ic_metodoPrazo->FldCaption() ?></span></td>
		<td><span id="elh_tpmetrica_ic_metodoCusto" class="tpmetrica_ic_metodoCusto"><?php echo $tpmetrica->ic_metodoCusto->FldCaption() ?></span></td>
		<td><span id="elh_tpmetrica_ic_metodoRecursos" class="tpmetrica_ic_metodoRecursos"><?php echo $tpmetrica->ic_metodoRecursos->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$tpmetrica_delete->RecCnt = 0;
$i = 0;
while (!$tpmetrica_delete->Recordset->EOF) {
	$tpmetrica_delete->RecCnt++;
	$tpmetrica_delete->RowCnt++;

	// Set row properties
	$tpmetrica->ResetAttrs();
	$tpmetrica->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$tpmetrica_delete->LoadRowValues($tpmetrica_delete->Recordset);

	// Render row
	$tpmetrica_delete->RenderRow();
?>
	<tr<?php echo $tpmetrica->RowAttributes() ?>>
		<td<?php echo $tpmetrica->no_tpMetrica->CellAttributes() ?>>
<span id="el<?php echo $tpmetrica_delete->RowCnt ?>_tpmetrica_no_tpMetrica" class="control-group tpmetrica_no_tpMetrica">
<span<?php echo $tpmetrica->no_tpMetrica->ViewAttributes() ?>>
<?php echo $tpmetrica->no_tpMetrica->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $tpmetrica->ic_tpMetrica->CellAttributes() ?>>
<span id="el<?php echo $tpmetrica_delete->RowCnt ?>_tpmetrica_ic_tpMetrica" class="control-group tpmetrica_ic_tpMetrica">
<span<?php echo $tpmetrica->ic_tpMetrica->ViewAttributes() ?>>
<?php echo $tpmetrica->ic_tpMetrica->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $tpmetrica->ic_tpAplicacao->CellAttributes() ?>>
<span id="el<?php echo $tpmetrica_delete->RowCnt ?>_tpmetrica_ic_tpAplicacao" class="control-group tpmetrica_ic_tpAplicacao">
<span<?php echo $tpmetrica->ic_tpAplicacao->ViewAttributes() ?>>
<?php echo $tpmetrica->ic_tpAplicacao->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $tpmetrica->ic_ativo->CellAttributes() ?>>
<span id="el<?php echo $tpmetrica_delete->RowCnt ?>_tpmetrica_ic_ativo" class="control-group tpmetrica_ic_ativo">
<span<?php echo $tpmetrica->ic_ativo->ViewAttributes() ?>>
<?php echo $tpmetrica->ic_ativo->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $tpmetrica->ic_metodoEsforco->CellAttributes() ?>>
<span id="el<?php echo $tpmetrica_delete->RowCnt ?>_tpmetrica_ic_metodoEsforco" class="control-group tpmetrica_ic_metodoEsforco">
<span<?php echo $tpmetrica->ic_metodoEsforco->ViewAttributes() ?>>
<?php echo $tpmetrica->ic_metodoEsforco->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $tpmetrica->ic_metodoPrazo->CellAttributes() ?>>
<span id="el<?php echo $tpmetrica_delete->RowCnt ?>_tpmetrica_ic_metodoPrazo" class="control-group tpmetrica_ic_metodoPrazo">
<span<?php echo $tpmetrica->ic_metodoPrazo->ViewAttributes() ?>>
<?php echo $tpmetrica->ic_metodoPrazo->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $tpmetrica->ic_metodoCusto->CellAttributes() ?>>
<span id="el<?php echo $tpmetrica_delete->RowCnt ?>_tpmetrica_ic_metodoCusto" class="control-group tpmetrica_ic_metodoCusto">
<span<?php echo $tpmetrica->ic_metodoCusto->ViewAttributes() ?>>
<?php echo $tpmetrica->ic_metodoCusto->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $tpmetrica->ic_metodoRecursos->CellAttributes() ?>>
<span id="el<?php echo $tpmetrica_delete->RowCnt ?>_tpmetrica_ic_metodoRecursos" class="control-group tpmetrica_ic_metodoRecursos">
<span<?php echo $tpmetrica->ic_metodoRecursos->ViewAttributes() ?>>
<?php echo $tpmetrica->ic_metodoRecursos->ListViewValue() ?></span>
</span>
</td>
	</tr>
<?php
	$tpmetrica_delete->Recordset->MoveNext();
}
$tpmetrica_delete->Recordset->Close();
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
ftpmetricadelete.Init();
</script>
<?php
$tpmetrica_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tpmetrica_delete->Page_Terminate();
?>
