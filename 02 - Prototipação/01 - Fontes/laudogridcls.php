<?php include_once "laudoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php

//
// Page class
//

$laudo_grid = NULL; // Initialize page object first

class claudo_grid extends claudo {

	// Page ID
	var $PageID = 'grid';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'laudo';

	// Page object name
	var $PageObjName = 'laudo_grid';

	// Grid form hidden field names
	var $FormName = 'flaudogrid';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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
	var $AuditTrailOnAdd = TRUE;
	var $AuditTrailOnEdit = TRUE;
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
		$this->FormActionName .= '_' . $this->FormName;
		$this->FormKeyName .= '_' . $this->FormName;
		$this->FormOldKeyName .= '_' . $this->FormName;
		$this->FormBlankRowName .= '_' . $this->FormName;
		$this->FormKeyCountName .= '_' . $this->FormName;
		$GLOBALS["Grid"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (laudo)
		if (!isset($GLOBALS["laudo"])) {
			$GLOBALS["laudo"] = &$this;

//			$GLOBALS["MasterTable"] = &$GLOBALS["Table"];
//			if (!isset($GLOBALS["Table"])) $GLOBALS["Table"] = &$GLOBALS["laudo"];

		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'grid', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'laudo', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "span";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
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
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("login.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
		$this->nu_usuarioResp->Visible = !$this->IsAddOrEdit();
		$this->dt_emissao->Visible = !$this->IsAddOrEdit();
		$this->hh_emissao->Visible = !$this->IsAddOrEdit();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Setup other options
		$this->SetupOtherOptions();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;

//		$GLOBALS["Table"] = &$GLOBALS["MasterTable"];
		unset($GLOBALS["Grid"]);
		if ($url == "")
			return;

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();
		$this->Page_Redirecting($url);

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $ShowOtherOptions = FALSE;
	var $DisplayRecs = 100;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Handle reset command
			$this->ResetCmd();

			// Set up master detail parameters
			$this->SetUpMasterParms();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Show grid delete link for grid add / grid edit
			if ($this->AllowAddDeleteRow) {
				if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
					$item = $this->ListOptions->GetItem("griddelete");
					if ($item) $item->Visible = TRUE;
				}
			}

			// Set up sorting order
			$this->SetUpSortOrder();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 100; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records

		// Restore master/detail filter
		$this->DbMasterFilter = $this->GetMasterFilter(); // Restore master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Restore detail filter
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "solicitacaoMetricas") {
			global $solicitacaoMetricas;
			$rsmaster = $solicitacaoMetricas->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("solicitacaometricaslist.php"); // Return to master page
			} else {
				$solicitacaoMetricas->LoadListRowValues($rsmaster);
				$solicitacaoMetricas->RowType = EW_ROWTYPE_MASTER; // Master row
				$solicitacaoMetricas->RenderListRow();
				$rsmaster->Close();
			}
		}

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";
	}

	//  Exit inline mode
	function ClearInlineMode() {
		$this->LastAction = $this->CurrentAction; // Save last action
		$this->CurrentAction = ""; // Clear action
		$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
	}

	// Switch to Grid Add mode
	function GridAddMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridadd"; // Enabled grid add
	}

	// Switch to Grid Edit mode
	function GridEditMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridedit"; // Enable grid edit
	}

	// Perform update to grid
	function GridUpdate() {
		global $conn, $Language, $objForm, $gsFormError;
		$bGridUpdate = TRUE;
		if ($this->AuditTrailOnEdit) $this->WriteAuditTrailDummy($Language->Phrase("BatchUpdateBegin")); // Batch update begin

		// Get old recordset
		$this->CurrentFilter = $this->BuildKeyFilter();
		$sSql = $this->SQL();
		if ($rs = $conn->Execute($sSql)) {
			$rsold = $rs->GetRows();
			$rs->Close();
		}
		$sKey = "";

		// Update row index and get row key
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Update all rows based on key
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
			$objForm->Index = $rowindex;
			$rowkey = strval($objForm->GetValue($this->FormKeyName));
			$rowaction = strval($objForm->GetValue($this->FormActionName));

			// Load all values and keys
			if ($rowaction <> "insertdelete") { // Skip insert then deleted rows
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "" || $rowaction == "edit" || $rowaction == "delete") {
					$bGridUpdate = $this->SetupKeyValues($rowkey); // Set up key values
				} else {
					$bGridUpdate = TRUE;
				}

				// Skip empty row
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// No action required
				// Validate form and insert/update/delete record

				} elseif ($bGridUpdate) {
					if ($rowaction == "delete") {
						$this->CurrentFilter = $this->KeyFilter();
						$bGridUpdate = $this->DeleteRows(); // Delete this row
					} else if (!$this->ValidateForm()) {
						$bGridUpdate = FALSE; // Form error, reset action
						$this->setFailureMessage($gsFormError);
					} else {
						if ($rowaction == "insert") {
							$bGridUpdate = $this->AddRow(); // Insert this row
						} else {
							if ($rowkey <> "") {
								$this->SendEmail = FALSE; // Do not send email on update success
								$bGridUpdate = $this->EditRow(); // Update this row
							}
						} // End update
					}
				}
				if ($bGridUpdate) {
					if ($sKey <> "") $sKey .= ", ";
					$sKey .= $rowkey;
				} else {
					break;
				}
			}
		}
		if ($bGridUpdate) {

			// Get new recordset
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}
			if ($this->AuditTrailOnEdit) $this->WriteAuditTrailDummy($Language->Phrase("BatchUpdateSuccess")); // Batch update success
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			if ($this->AuditTrailOnEdit) $this->WriteAuditTrailDummy($Language->Phrase("BatchUpdateRollback")); // Batch update rollback
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "gridedit"; // Stay in Grid Edit mode
		}
		return $bGridUpdate;
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue("k_key"));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue("k_key"));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 2) {
			$this->nu_solicitacao->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->nu_solicitacao->FormValue))
				return FALSE;
			$this->nu_versao->setFormValue($arrKeyFlds[1]);
			if (!is_numeric($this->nu_versao->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Perform Grid Add
	function GridInsert() {
		global $conn, $Language, $objForm, $gsFormError;
		$rowindex = 1;
		$bGridInsert = FALSE;

		// Init key filter
		$sWrkFilter = "";
		$addcnt = 0;
		if ($this->AuditTrailOnAdd) $this->WriteAuditTrailDummy($Language->Phrase("BatchInsertBegin")); // Batch insert begin
		$sKey = "";

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Insert all rows
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "" && $rowaction <> "insert")
				continue; // Skip
			if ($rowaction == "insert") {
				$this->RowOldKey = strval($objForm->GetValue($this->FormOldKeyName));
				$this->LoadOldRecord(); // Load old recordset
			}
			$this->LoadFormValues(); // Get form values
			if (!$this->EmptyRow()) {
				$addcnt++;
				$this->SendEmail = FALSE; // Do not send email on insert success

				// Validate form
				if (!$this->ValidateForm()) {
					$bGridInsert = FALSE; // Form error, reset action
					$this->setFailureMessage($gsFormError);
				} else {
					$bGridInsert = $this->AddRow($this->OldRecordset); // Insert this row
				}
				if ($bGridInsert) {
					if ($sKey <> "") $sKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
					$sKey .= $this->nu_solicitacao->CurrentValue;
					if ($sKey <> "") $sKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
					$sKey .= $this->nu_versao->CurrentValue;

					// Add filter for this record
					$sFilter = $this->KeyFilter();
					if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
					$sWrkFilter .= $sFilter;
				} else {
					break;
				}
			}
		}
		if ($addcnt == 0) { // No record inserted
			$this->ClearInlineMode(); // Clear grid add mode and return
			return TRUE;
		}
		if ($bGridInsert) {

			// Get new recordset
			$this->CurrentFilter = $sWrkFilter;
			$sSql = $this->SQL();
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}
			if ($this->AuditTrailOnAdd) $this->WriteAuditTrailDummy($Language->Phrase("BatchInsertSuccess")); // Batch insert success
			$this->ClearInlineMode(); // Clear grid add mode
		} else {
			if ($this->AuditTrailOnAdd) $this->WriteAuditTrailDummy($Language->Phrase("BatchInsertRollback")); // Batch insert rollback
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("InsertFailed")); // Set insert failed message
			}
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "gridadd"; // Stay in gridadd mode
		}
		return $bGridInsert;
	}

	// Check if empty row
	function EmptyRow() {
		global $objForm;
		if ($objForm->HasValue("x_nu_solicitacao") && $objForm->HasValue("o_nu_solicitacao") && $this->nu_solicitacao->CurrentValue <> $this->nu_solicitacao->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_nu_versao") && $objForm->HasValue("o_nu_versao") && $this->nu_versao->CurrentValue <> $this->nu_versao->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_qt_pf") && $objForm->HasValue("o_qt_pf") && $this->qt_pf->CurrentValue <> $this->qt_pf->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_qt_horas") && $objForm->HasValue("o_qt_horas") && $this->qt_horas->CurrentValue <> $this->qt_horas->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_qt_prazoMeses") && $objForm->HasValue("o_qt_prazoMeses") && $this->qt_prazoMeses->CurrentValue <> $this->qt_prazoMeses->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_qt_prazoDias") && $objForm->HasValue("o_qt_prazoDias") && $this->qt_prazoDias->CurrentValue <> $this->qt_prazoDias->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_vr_contratacao") && $objForm->HasValue("o_vr_contratacao") && $this->vr_contratacao->CurrentValue <> $this->vr_contratacao->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_dt_inicioSolicitacao") && $objForm->HasValue("o_dt_inicioSolicitacao") && $this->dt_inicioSolicitacao->CurrentValue <> $this->dt_inicioSolicitacao->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_dt_inicioContagem") && $objForm->HasValue("o_dt_inicioContagem") && $this->dt_inicioContagem->CurrentValue <> $this->dt_inicioContagem->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_ic_tamanho") && $objForm->HasValue("o_ic_tamanho") && $this->ic_tamanho->CurrentValue <> $this->ic_tamanho->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_ic_esforco") && $objForm->HasValue("o_ic_esforco") && $this->ic_esforco->CurrentValue <> $this->ic_esforco->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_ic_prazo") && $objForm->HasValue("o_ic_prazo") && $this->ic_prazo->CurrentValue <> $this->ic_prazo->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_ic_custo") && $objForm->HasValue("o_ic_custo") && $this->ic_custo->CurrentValue <> $this->ic_custo->OldValue)
			return FALSE;
		return TRUE;
	}

	// Validate grid form
	function ValidateGridForm() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Validate all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else if (!$this->ValidateForm()) {
					return FALSE;
				}
			}
		}
		return TRUE;
	}

	// Restore form values for current row
	function RestoreCurrentRowFormValues($idx) {
		global $objForm;

		// Get row based on current index
		$objForm->Index = $idx;
		$this->LoadFormValues(); // Load form values
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->SqlOrderBy() <> "") {
				$sOrderBy = $this->SqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
				$this->nu_solicitacao->setSort("DESC");
				$this->nu_versao->setSort("DESC");
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset master/detail keys
			if ($this->Command == "resetall") {
				$this->setCurrentMasterTable(""); // Clear master table
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
				$this->nu_solicitacao->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// "griddelete"
		if ($this->AllowAddDeleteRow) {
			$item = &$this->ListOptions->Add("griddelete");
			$item->CssStyle = "white-space: nowrap;";
			$item->OnLeft = FALSE;
			$item->Visible = FALSE; // Default hidden
		}

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		$this->ListOptions->ButtonClass = "btn-small"; // Class for button group
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// Set up row action and key
		if (is_numeric($this->RowIndex) && $this->CurrentMode <> "view") {
			$objForm->Index = $this->RowIndex;
			$ActionName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormActionName);
			$OldKeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormOldKeyName);
			$KeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormKeyName);
			$BlankRowName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormBlankRowName);
			if ($this->RowAction <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $ActionName . "\" id=\"" . $ActionName . "\" value=\"" . $this->RowAction . "\">";
			if ($objForm->HasValue($this->FormOldKeyName))
				$this->RowOldKey = strval($objForm->GetValue($this->FormOldKeyName));
			if ($this->RowOldKey <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $OldKeyName . "\" id=\"" . $OldKeyName . "\" value=\"" . ew_HtmlEncode($this->RowOldKey) . "\">";
			if ($this->RowAction == "delete") {
				$rowkey = $objForm->GetValue($this->FormKeyName);
				$this->SetupKeyValues($rowkey);
			}
			if ($this->RowAction == "insert" && $this->CurrentAction == "F" && $this->EmptyRow())
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $BlankRowName . "\" id=\"" . $BlankRowName . "\" value=\"1\">";
		}

		// "delete"
		if ($this->AllowAddDeleteRow) {
			if ($this->CurrentMode == "add" || $this->CurrentMode == "copy" || $this->CurrentMode == "edit") {
				$option = &$this->ListOptions;
				$option->UseButtonGroup = TRUE; // Use button group for grid delete button
				$option->UseImageAndText = TRUE; // Use image and text for grid delete button
				$oListOpt = &$option->Items["griddelete"];
				if (!$Security->CanDelete() && is_numeric($this->RowIndex) && ($this->RowAction == "" || $this->RowAction == "edit")) { // Do not allow delete existing record
					$oListOpt->Body = "&nbsp;";
				} else {
					$oListOpt->Body = "<a class=\"ewGridLink ewGridDelete\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"javascript:void(0);\" onclick=\"ew_DeleteGridRow(this, " . $this->RowIndex . ");\">" . $Language->Phrase("DeleteLink") . "</a>";
				}
			}
		}
		if ($this->CurrentMode == "edit" && is_numeric($this->RowIndex)) {
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->nu_solicitacao->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->nu_versao->CurrentValue . "\">";
		}
		$this->RenderListOptionsExt();
	}

	// Set record key
	function SetRecordKey(&$key, $rs) {
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs->fields('nu_solicitacao');
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs->fields('nu_versao');
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$option = &$this->OtherOptions["addedit"];
		$option->UseDropDownButton = FALSE;
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$option->UseButtonGroup = TRUE;
		$option->ButtonClass = "btn-small"; // Class for button group
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		if (($this->CurrentMode == "add" || $this->CurrentMode == "copy" || $this->CurrentMode == "edit") && $this->CurrentAction != "F") { // Check add/copy/edit mode
			if ($this->AllowAddDeleteRow) {
				$option = &$options["addedit"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;
				$item = &$option->Add("addblankrow");
				$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
				$item->Visible = $Security->CanAdd();
				$this->ShowOtherOptions = $item->Visible;
			}
		}
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->nu_solicitacao->CurrentValue = NULL;
		$this->nu_solicitacao->OldValue = $this->nu_solicitacao->CurrentValue;
		$this->nu_versao->CurrentValue = "1";
		$this->nu_versao->OldValue = $this->nu_versao->CurrentValue;
		$this->qt_pf->CurrentValue = NULL;
		$this->qt_pf->OldValue = $this->qt_pf->CurrentValue;
		$this->qt_horas->CurrentValue = NULL;
		$this->qt_horas->OldValue = $this->qt_horas->CurrentValue;
		$this->qt_prazoMeses->CurrentValue = NULL;
		$this->qt_prazoMeses->OldValue = $this->qt_prazoMeses->CurrentValue;
		$this->qt_prazoDias->CurrentValue = NULL;
		$this->qt_prazoDias->OldValue = $this->qt_prazoDias->CurrentValue;
		$this->vr_contratacao->CurrentValue = NULL;
		$this->vr_contratacao->OldValue = $this->vr_contratacao->CurrentValue;
		$this->nu_usuarioResp->CurrentValue = NULL;
		$this->nu_usuarioResp->OldValue = $this->nu_usuarioResp->CurrentValue;
		$this->dt_inicioSolicitacao->CurrentValue = NULL;
		$this->dt_inicioSolicitacao->OldValue = $this->dt_inicioSolicitacao->CurrentValue;
		$this->dt_inicioContagem->CurrentValue = NULL;
		$this->dt_inicioContagem->OldValue = $this->dt_inicioContagem->CurrentValue;
		$this->dt_emissao->CurrentValue = NULL;
		$this->dt_emissao->OldValue = $this->dt_emissao->CurrentValue;
		$this->hh_emissao->CurrentValue = NULL;
		$this->hh_emissao->OldValue = $this->hh_emissao->CurrentValue;
		$this->ic_tamanho->CurrentValue = "S";
		$this->ic_tamanho->OldValue = $this->ic_tamanho->CurrentValue;
		$this->ic_esforco->CurrentValue = "N";
		$this->ic_esforco->OldValue = $this->ic_esforco->CurrentValue;
		$this->ic_prazo->CurrentValue = "N";
		$this->ic_prazo->OldValue = $this->ic_prazo->CurrentValue;
		$this->ic_custo->CurrentValue = "N";
		$this->ic_custo->OldValue = $this->ic_custo->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_solicitacao->FldIsDetailKey) {
			$this->nu_solicitacao->setFormValue($objForm->GetValue("x_nu_solicitacao"));
		}
		$this->nu_solicitacao->setOldValue($objForm->GetValue("o_nu_solicitacao"));
		if (!$this->nu_versao->FldIsDetailKey) {
			$this->nu_versao->setFormValue($objForm->GetValue("x_nu_versao"));
		}
		$this->nu_versao->setOldValue($objForm->GetValue("o_nu_versao"));
		if (!$this->qt_pf->FldIsDetailKey) {
			$this->qt_pf->setFormValue($objForm->GetValue("x_qt_pf"));
		}
		$this->qt_pf->setOldValue($objForm->GetValue("o_qt_pf"));
		if (!$this->qt_horas->FldIsDetailKey) {
			$this->qt_horas->setFormValue($objForm->GetValue("x_qt_horas"));
		}
		$this->qt_horas->setOldValue($objForm->GetValue("o_qt_horas"));
		if (!$this->qt_prazoMeses->FldIsDetailKey) {
			$this->qt_prazoMeses->setFormValue($objForm->GetValue("x_qt_prazoMeses"));
		}
		$this->qt_prazoMeses->setOldValue($objForm->GetValue("o_qt_prazoMeses"));
		if (!$this->qt_prazoDias->FldIsDetailKey) {
			$this->qt_prazoDias->setFormValue($objForm->GetValue("x_qt_prazoDias"));
		}
		$this->qt_prazoDias->setOldValue($objForm->GetValue("o_qt_prazoDias"));
		if (!$this->vr_contratacao->FldIsDetailKey) {
			$this->vr_contratacao->setFormValue($objForm->GetValue("x_vr_contratacao"));
		}
		$this->vr_contratacao->setOldValue($objForm->GetValue("o_vr_contratacao"));
		if (!$this->nu_usuarioResp->FldIsDetailKey) {
			$this->nu_usuarioResp->setFormValue($objForm->GetValue("x_nu_usuarioResp"));
		}
		$this->nu_usuarioResp->setOldValue($objForm->GetValue("o_nu_usuarioResp"));
		if (!$this->dt_inicioSolicitacao->FldIsDetailKey) {
			$this->dt_inicioSolicitacao->setFormValue($objForm->GetValue("x_dt_inicioSolicitacao"));
			$this->dt_inicioSolicitacao->CurrentValue = ew_UnFormatDateTime($this->dt_inicioSolicitacao->CurrentValue, 7);
		}
		$this->dt_inicioSolicitacao->setOldValue($objForm->GetValue("o_dt_inicioSolicitacao"));
		if (!$this->dt_inicioContagem->FldIsDetailKey) {
			$this->dt_inicioContagem->setFormValue($objForm->GetValue("x_dt_inicioContagem"));
			$this->dt_inicioContagem->CurrentValue = ew_UnFormatDateTime($this->dt_inicioContagem->CurrentValue, 7);
		}
		$this->dt_inicioContagem->setOldValue($objForm->GetValue("o_dt_inicioContagem"));
		if (!$this->dt_emissao->FldIsDetailKey) {
			$this->dt_emissao->setFormValue($objForm->GetValue("x_dt_emissao"));
			$this->dt_emissao->CurrentValue = ew_UnFormatDateTime($this->dt_emissao->CurrentValue, 7);
		}
		$this->dt_emissao->setOldValue($objForm->GetValue("o_dt_emissao"));
		if (!$this->hh_emissao->FldIsDetailKey) {
			$this->hh_emissao->setFormValue($objForm->GetValue("x_hh_emissao"));
		}
		$this->hh_emissao->setOldValue($objForm->GetValue("o_hh_emissao"));
		if (!$this->ic_tamanho->FldIsDetailKey) {
			$this->ic_tamanho->setFormValue($objForm->GetValue("x_ic_tamanho"));
		}
		$this->ic_tamanho->setOldValue($objForm->GetValue("o_ic_tamanho"));
		if (!$this->ic_esforco->FldIsDetailKey) {
			$this->ic_esforco->setFormValue($objForm->GetValue("x_ic_esforco"));
		}
		$this->ic_esforco->setOldValue($objForm->GetValue("o_ic_esforco"));
		if (!$this->ic_prazo->FldIsDetailKey) {
			$this->ic_prazo->setFormValue($objForm->GetValue("x_ic_prazo"));
		}
		$this->ic_prazo->setOldValue($objForm->GetValue("o_ic_prazo"));
		if (!$this->ic_custo->FldIsDetailKey) {
			$this->ic_custo->setFormValue($objForm->GetValue("x_ic_custo"));
		}
		$this->ic_custo->setOldValue($objForm->GetValue("o_ic_custo"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->nu_solicitacao->CurrentValue = $this->nu_solicitacao->FormValue;
		$this->nu_versao->CurrentValue = $this->nu_versao->FormValue;
		$this->qt_pf->CurrentValue = $this->qt_pf->FormValue;
		$this->qt_horas->CurrentValue = $this->qt_horas->FormValue;
		$this->qt_prazoMeses->CurrentValue = $this->qt_prazoMeses->FormValue;
		$this->qt_prazoDias->CurrentValue = $this->qt_prazoDias->FormValue;
		$this->vr_contratacao->CurrentValue = $this->vr_contratacao->FormValue;
		$this->nu_usuarioResp->CurrentValue = $this->nu_usuarioResp->FormValue;
		$this->dt_inicioSolicitacao->CurrentValue = $this->dt_inicioSolicitacao->FormValue;
		$this->dt_inicioSolicitacao->CurrentValue = ew_UnFormatDateTime($this->dt_inicioSolicitacao->CurrentValue, 7);
		$this->dt_inicioContagem->CurrentValue = $this->dt_inicioContagem->FormValue;
		$this->dt_inicioContagem->CurrentValue = ew_UnFormatDateTime($this->dt_inicioContagem->CurrentValue, 7);
		$this->dt_emissao->CurrentValue = $this->dt_emissao->FormValue;
		$this->dt_emissao->CurrentValue = ew_UnFormatDateTime($this->dt_emissao->CurrentValue, 7);
		$this->hh_emissao->CurrentValue = $this->hh_emissao->FormValue;
		$this->ic_tamanho->CurrentValue = $this->ic_tamanho->FormValue;
		$this->ic_esforco->CurrentValue = $this->ic_esforco->FormValue;
		$this->ic_prazo->CurrentValue = $this->ic_prazo->FormValue;
		$this->ic_custo->CurrentValue = $this->ic_custo->FormValue;
	}

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
		$this->nu_solicitacao->setDbValue($rs->fields('nu_solicitacao'));
		$this->nu_versao->setDbValue($rs->fields('nu_versao'));
		$this->ds_sobreDocumentacao->setDbValue($rs->fields('ds_sobreDocumentacao'));
		$this->ds_sobreMetrificacao->setDbValue($rs->fields('ds_sobreMetrificacao'));
		$this->qt_pf->setDbValue($rs->fields('qt_pf'));
		$this->qt_horas->setDbValue($rs->fields('qt_horas'));
		$this->qt_prazoMeses->setDbValue($rs->fields('qt_prazoMeses'));
		$this->qt_prazoDias->setDbValue($rs->fields('qt_prazoDias'));
		$this->vr_contratacao->setDbValue($rs->fields('vr_contratacao'));
		$this->nu_usuarioResp->setDbValue($rs->fields('nu_usuarioResp'));
		$this->dt_inicioSolicitacao->setDbValue($rs->fields('dt_inicioSolicitacao'));
		$this->dt_inicioContagem->setDbValue($rs->fields('dt_inicioContagem'));
		$this->dt_emissao->setDbValue($rs->fields('dt_emissao'));
		$this->hh_emissao->setDbValue($rs->fields('hh_emissao'));
		$this->ic_tamanho->setDbValue($rs->fields('ic_tamanho'));
		$this->ic_esforco->setDbValue($rs->fields('ic_esforco'));
		$this->ic_prazo->setDbValue($rs->fields('ic_prazo'));
		$this->ic_custo->setDbValue($rs->fields('ic_custo'));
		$this->ic_bloqueio->setDbValue($rs->fields('ic_bloqueio'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_solicitacao->DbValue = $row['nu_solicitacao'];
		$this->nu_versao->DbValue = $row['nu_versao'];
		$this->ds_sobreDocumentacao->DbValue = $row['ds_sobreDocumentacao'];
		$this->ds_sobreMetrificacao->DbValue = $row['ds_sobreMetrificacao'];
		$this->qt_pf->DbValue = $row['qt_pf'];
		$this->qt_horas->DbValue = $row['qt_horas'];
		$this->qt_prazoMeses->DbValue = $row['qt_prazoMeses'];
		$this->qt_prazoDias->DbValue = $row['qt_prazoDias'];
		$this->vr_contratacao->DbValue = $row['vr_contratacao'];
		$this->nu_usuarioResp->DbValue = $row['nu_usuarioResp'];
		$this->dt_inicioSolicitacao->DbValue = $row['dt_inicioSolicitacao'];
		$this->dt_inicioContagem->DbValue = $row['dt_inicioContagem'];
		$this->dt_emissao->DbValue = $row['dt_emissao'];
		$this->hh_emissao->DbValue = $row['hh_emissao'];
		$this->ic_tamanho->DbValue = $row['ic_tamanho'];
		$this->ic_esforco->DbValue = $row['ic_esforco'];
		$this->ic_prazo->DbValue = $row['ic_prazo'];
		$this->ic_custo->DbValue = $row['ic_custo'];
		$this->ic_bloqueio->DbValue = $row['ic_bloqueio'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		$arKeys[] = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $this->RowOldKey);
		$cnt = count($arKeys);
		if ($cnt >= 2) {
			if (strval($arKeys[0]) <> "")
				$this->nu_solicitacao->CurrentValue = strval($arKeys[0]); // nu_solicitacao
			else
				$bValidKey = FALSE;
			if (strval($arKeys[1]) <> "")
				$this->nu_versao->CurrentValue = strval($arKeys[1]); // nu_versao
			else
				$bValidKey = FALSE;
		} else {
			$bValidKey = FALSE;
		}

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$this->OldRecordset = ew_LoadRecordset($sSql);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
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
		if ($this->qt_horas->FormValue == $this->qt_horas->CurrentValue && is_numeric(ew_StrToFloat($this->qt_horas->CurrentValue)))
			$this->qt_horas->CurrentValue = ew_StrToFloat($this->qt_horas->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_prazoMeses->FormValue == $this->qt_prazoMeses->CurrentValue && is_numeric(ew_StrToFloat($this->qt_prazoMeses->CurrentValue)))
			$this->qt_prazoMeses->CurrentValue = ew_StrToFloat($this->qt_prazoMeses->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_contratacao->FormValue == $this->vr_contratacao->CurrentValue && is_numeric(ew_StrToFloat($this->vr_contratacao->CurrentValue)))
			$this->vr_contratacao->CurrentValue = ew_StrToFloat($this->vr_contratacao->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_solicitacao
		// nu_versao
		// ds_sobreDocumentacao
		// ds_sobreMetrificacao
		// qt_pf
		// qt_horas
		// qt_prazoMeses
		// qt_prazoDias
		// vr_contratacao
		// nu_usuarioResp
		// dt_inicioSolicitacao
		// dt_inicioContagem
		// dt_emissao
		// hh_emissao
		// ic_tamanho
		// ic_esforco
		// ic_prazo
		// ic_custo
		// ic_bloqueio

		$this->ic_bloqueio->CellCssStyle = "white-space: nowrap;";
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_solicitacao
			if (strval($this->nu_solicitacao->CurrentValue) <> "") {
				$sFilterWrk = "[nu_solMetricas]" . ew_SearchString("=", $this->nu_solicitacao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_solMetricas], [nu_solMetricas] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[solicitacaoMetricas]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_solicitacao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_solMetricas] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_solicitacao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_solicitacao->ViewValue = $this->nu_solicitacao->CurrentValue;
				}
			} else {
				$this->nu_solicitacao->ViewValue = NULL;
			}
			$this->nu_solicitacao->ViewCustomAttributes = "";

			// nu_versao
			$this->nu_versao->ViewValue = $this->nu_versao->CurrentValue;
			$this->nu_versao->ViewCustomAttributes = "";

			// qt_pf
			$this->qt_pf->ViewValue = $this->qt_pf->CurrentValue;
			$this->qt_pf->ViewCustomAttributes = "";

			// qt_horas
			$this->qt_horas->ViewValue = $this->qt_horas->CurrentValue;
			$this->qt_horas->ViewCustomAttributes = "";

			// qt_prazoMeses
			$this->qt_prazoMeses->ViewValue = $this->qt_prazoMeses->CurrentValue;
			$this->qt_prazoMeses->ViewCustomAttributes = "";

			// qt_prazoDias
			$this->qt_prazoDias->ViewValue = $this->qt_prazoDias->CurrentValue;
			$this->qt_prazoDias->ViewCustomAttributes = "";

			// vr_contratacao
			$this->vr_contratacao->ViewValue = $this->vr_contratacao->CurrentValue;
			$this->vr_contratacao->ViewValue = ew_FormatCurrency($this->vr_contratacao->ViewValue, 2, -2, -2, -2);
			$this->vr_contratacao->ViewCustomAttributes = "";

			// nu_usuarioResp
			if (strval($this->nu_usuarioResp->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioResp->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioResp, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_usuario] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioResp->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioResp->ViewValue = $this->nu_usuarioResp->CurrentValue;
				}
			} else {
				$this->nu_usuarioResp->ViewValue = NULL;
			}
			$this->nu_usuarioResp->ViewCustomAttributes = "";

			// dt_inicioSolicitacao
			$this->dt_inicioSolicitacao->ViewValue = $this->dt_inicioSolicitacao->CurrentValue;
			$this->dt_inicioSolicitacao->ViewValue = ew_FormatDateTime($this->dt_inicioSolicitacao->ViewValue, 7);
			$this->dt_inicioSolicitacao->ViewCustomAttributes = "";

			// dt_inicioContagem
			$this->dt_inicioContagem->ViewValue = $this->dt_inicioContagem->CurrentValue;
			$this->dt_inicioContagem->ViewValue = ew_FormatDateTime($this->dt_inicioContagem->ViewValue, 7);
			$this->dt_inicioContagem->ViewCustomAttributes = "";

			// dt_emissao
			$this->dt_emissao->ViewValue = $this->dt_emissao->CurrentValue;
			$this->dt_emissao->ViewValue = ew_FormatDateTime($this->dt_emissao->ViewValue, 7);
			$this->dt_emissao->ViewCustomAttributes = "";

			// hh_emissao
			$this->hh_emissao->ViewValue = $this->hh_emissao->CurrentValue;
			$this->hh_emissao->ViewValue = ew_FormatDateTime($this->hh_emissao->ViewValue, 4);
			$this->hh_emissao->ViewCustomAttributes = "";

			// ic_tamanho
			if (strval($this->ic_tamanho->CurrentValue) <> "") {
				switch ($this->ic_tamanho->CurrentValue) {
					case $this->ic_tamanho->FldTagValue(1):
						$this->ic_tamanho->ViewValue = $this->ic_tamanho->FldTagCaption(1) <> "" ? $this->ic_tamanho->FldTagCaption(1) : $this->ic_tamanho->CurrentValue;
						break;
					case $this->ic_tamanho->FldTagValue(2):
						$this->ic_tamanho->ViewValue = $this->ic_tamanho->FldTagCaption(2) <> "" ? $this->ic_tamanho->FldTagCaption(2) : $this->ic_tamanho->CurrentValue;
						break;
					default:
						$this->ic_tamanho->ViewValue = $this->ic_tamanho->CurrentValue;
				}
			} else {
				$this->ic_tamanho->ViewValue = NULL;
			}
			$this->ic_tamanho->ViewCustomAttributes = "";

			// ic_esforco
			if (strval($this->ic_esforco->CurrentValue) <> "") {
				switch ($this->ic_esforco->CurrentValue) {
					case $this->ic_esforco->FldTagValue(1):
						$this->ic_esforco->ViewValue = $this->ic_esforco->FldTagCaption(1) <> "" ? $this->ic_esforco->FldTagCaption(1) : $this->ic_esforco->CurrentValue;
						break;
					case $this->ic_esforco->FldTagValue(2):
						$this->ic_esforco->ViewValue = $this->ic_esforco->FldTagCaption(2) <> "" ? $this->ic_esforco->FldTagCaption(2) : $this->ic_esforco->CurrentValue;
						break;
					default:
						$this->ic_esforco->ViewValue = $this->ic_esforco->CurrentValue;
				}
			} else {
				$this->ic_esforco->ViewValue = NULL;
			}
			$this->ic_esforco->ViewCustomAttributes = "";

			// ic_prazo
			if (strval($this->ic_prazo->CurrentValue) <> "") {
				switch ($this->ic_prazo->CurrentValue) {
					case $this->ic_prazo->FldTagValue(1):
						$this->ic_prazo->ViewValue = $this->ic_prazo->FldTagCaption(1) <> "" ? $this->ic_prazo->FldTagCaption(1) : $this->ic_prazo->CurrentValue;
						break;
					case $this->ic_prazo->FldTagValue(2):
						$this->ic_prazo->ViewValue = $this->ic_prazo->FldTagCaption(2) <> "" ? $this->ic_prazo->FldTagCaption(2) : $this->ic_prazo->CurrentValue;
						break;
					default:
						$this->ic_prazo->ViewValue = $this->ic_prazo->CurrentValue;
				}
			} else {
				$this->ic_prazo->ViewValue = NULL;
			}
			$this->ic_prazo->ViewCustomAttributes = "";

			// ic_custo
			if (strval($this->ic_custo->CurrentValue) <> "") {
				switch ($this->ic_custo->CurrentValue) {
					case $this->ic_custo->FldTagValue(1):
						$this->ic_custo->ViewValue = $this->ic_custo->FldTagCaption(1) <> "" ? $this->ic_custo->FldTagCaption(1) : $this->ic_custo->CurrentValue;
						break;
					case $this->ic_custo->FldTagValue(2):
						$this->ic_custo->ViewValue = $this->ic_custo->FldTagCaption(2) <> "" ? $this->ic_custo->FldTagCaption(2) : $this->ic_custo->CurrentValue;
						break;
					default:
						$this->ic_custo->ViewValue = $this->ic_custo->CurrentValue;
				}
			} else {
				$this->ic_custo->ViewValue = NULL;
			}
			$this->ic_custo->ViewCustomAttributes = "";

			// ic_bloqueio
			$this->ic_bloqueio->ViewValue = $this->ic_bloqueio->CurrentValue;
			$this->ic_bloqueio->ViewCustomAttributes = "";

			// nu_solicitacao
			$this->nu_solicitacao->LinkCustomAttributes = "";
			$this->nu_solicitacao->HrefValue = "";
			$this->nu_solicitacao->TooltipValue = "";

			// nu_versao
			$this->nu_versao->LinkCustomAttributes = "";
			$this->nu_versao->HrefValue = "";
			$this->nu_versao->TooltipValue = "";

			// qt_pf
			$this->qt_pf->LinkCustomAttributes = "";
			$this->qt_pf->HrefValue = "";
			$this->qt_pf->TooltipValue = "";

			// qt_horas
			$this->qt_horas->LinkCustomAttributes = "";
			$this->qt_horas->HrefValue = "";
			$this->qt_horas->TooltipValue = "";

			// qt_prazoMeses
			$this->qt_prazoMeses->LinkCustomAttributes = "";
			$this->qt_prazoMeses->HrefValue = "";
			$this->qt_prazoMeses->TooltipValue = "";

			// qt_prazoDias
			$this->qt_prazoDias->LinkCustomAttributes = "";
			$this->qt_prazoDias->HrefValue = "";
			$this->qt_prazoDias->TooltipValue = "";

			// vr_contratacao
			$this->vr_contratacao->LinkCustomAttributes = "";
			$this->vr_contratacao->HrefValue = "";
			$this->vr_contratacao->TooltipValue = "";

			// nu_usuarioResp
			$this->nu_usuarioResp->LinkCustomAttributes = "";
			$this->nu_usuarioResp->HrefValue = "";
			$this->nu_usuarioResp->TooltipValue = "";

			// dt_inicioSolicitacao
			$this->dt_inicioSolicitacao->LinkCustomAttributes = "";
			$this->dt_inicioSolicitacao->HrefValue = "";
			$this->dt_inicioSolicitacao->TooltipValue = "";

			// dt_inicioContagem
			$this->dt_inicioContagem->LinkCustomAttributes = "";
			$this->dt_inicioContagem->HrefValue = "";
			$this->dt_inicioContagem->TooltipValue = "";

			// dt_emissao
			$this->dt_emissao->LinkCustomAttributes = "";
			$this->dt_emissao->HrefValue = "";
			$this->dt_emissao->TooltipValue = "";

			// hh_emissao
			$this->hh_emissao->LinkCustomAttributes = "";
			$this->hh_emissao->HrefValue = "";
			$this->hh_emissao->TooltipValue = "";

			// ic_tamanho
			$this->ic_tamanho->LinkCustomAttributes = "";
			$this->ic_tamanho->HrefValue = "";
			$this->ic_tamanho->TooltipValue = "";

			// ic_esforco
			$this->ic_esforco->LinkCustomAttributes = "";
			$this->ic_esforco->HrefValue = "";
			$this->ic_esforco->TooltipValue = "";

			// ic_prazo
			$this->ic_prazo->LinkCustomAttributes = "";
			$this->ic_prazo->HrefValue = "";
			$this->ic_prazo->TooltipValue = "";

			// ic_custo
			$this->ic_custo->LinkCustomAttributes = "";
			$this->ic_custo->HrefValue = "";
			$this->ic_custo->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nu_solicitacao
			$this->nu_solicitacao->EditCustomAttributes = "";
			if ($this->nu_solicitacao->getSessionValue() <> "") {
				$this->nu_solicitacao->CurrentValue = $this->nu_solicitacao->getSessionValue();
				$this->nu_solicitacao->OldValue = $this->nu_solicitacao->CurrentValue;
			if (strval($this->nu_solicitacao->CurrentValue) <> "") {
				$sFilterWrk = "[nu_solMetricas]" . ew_SearchString("=", $this->nu_solicitacao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_solMetricas], [nu_solMetricas] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[solicitacaoMetricas]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_solicitacao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_solMetricas] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_solicitacao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_solicitacao->ViewValue = $this->nu_solicitacao->CurrentValue;
				}
			} else {
				$this->nu_solicitacao->ViewValue = NULL;
			}
			$this->nu_solicitacao->ViewCustomAttributes = "";
			} else {
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_solMetricas], [nu_solMetricas] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[solicitacaoMetricas]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_solicitacao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_solMetricas] DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_solicitacao->EditValue = $arwrk;
			}

			// nu_versao
			$this->nu_versao->EditCustomAttributes = "readonly";
			$this->nu_versao->EditValue = ew_HtmlEncode($this->nu_versao->CurrentValue);
			$this->nu_versao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_versao->FldCaption()));

			// qt_pf
			$this->qt_pf->EditCustomAttributes = "readonly";
			$this->qt_pf->EditValue = ew_HtmlEncode($this->qt_pf->CurrentValue);
			$this->qt_pf->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_pf->FldCaption()));
			if (strval($this->qt_pf->EditValue) <> "" && is_numeric($this->qt_pf->EditValue)) {
			$this->qt_pf->EditValue = ew_FormatNumber($this->qt_pf->EditValue, -2, -1, -2, 0);
			$this->qt_pf->OldValue = $this->qt_pf->EditValue;
			}

			// qt_horas
			$this->qt_horas->EditCustomAttributes = "readonly";
			$this->qt_horas->EditValue = ew_HtmlEncode($this->qt_horas->CurrentValue);
			$this->qt_horas->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_horas->FldCaption()));
			if (strval($this->qt_horas->EditValue) <> "" && is_numeric($this->qt_horas->EditValue)) {
			$this->qt_horas->EditValue = ew_FormatNumber($this->qt_horas->EditValue, -2, -1, -2, 0);
			$this->qt_horas->OldValue = $this->qt_horas->EditValue;
			}

			// qt_prazoMeses
			$this->qt_prazoMeses->EditCustomAttributes = "readonly";
			$this->qt_prazoMeses->EditValue = ew_HtmlEncode($this->qt_prazoMeses->CurrentValue);
			$this->qt_prazoMeses->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_prazoMeses->FldCaption()));
			if (strval($this->qt_prazoMeses->EditValue) <> "" && is_numeric($this->qt_prazoMeses->EditValue)) {
			$this->qt_prazoMeses->EditValue = ew_FormatNumber($this->qt_prazoMeses->EditValue, -2, -1, -2, 0);
			$this->qt_prazoMeses->OldValue = $this->qt_prazoMeses->EditValue;
			}

			// qt_prazoDias
			$this->qt_prazoDias->EditCustomAttributes = "readonly";
			$this->qt_prazoDias->EditValue = ew_HtmlEncode($this->qt_prazoDias->CurrentValue);
			$this->qt_prazoDias->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_prazoDias->FldCaption()));

			// vr_contratacao
			$this->vr_contratacao->EditCustomAttributes = "readonly";
			$this->vr_contratacao->EditValue = ew_HtmlEncode($this->vr_contratacao->CurrentValue);
			$this->vr_contratacao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_contratacao->FldCaption()));
			if (strval($this->vr_contratacao->EditValue) <> "" && is_numeric($this->vr_contratacao->EditValue)) {
			$this->vr_contratacao->EditValue = ew_FormatNumber($this->vr_contratacao->EditValue, -2, -2, -2, -2);
			$this->vr_contratacao->OldValue = $this->vr_contratacao->EditValue;
			}

			// nu_usuarioResp
			// dt_inicioSolicitacao

			$this->dt_inicioSolicitacao->EditCustomAttributes = "readonly";
			$this->dt_inicioSolicitacao->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->dt_inicioSolicitacao->CurrentValue, 7));
			$this->dt_inicioSolicitacao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dt_inicioSolicitacao->FldCaption()));

			// dt_inicioContagem
			$this->dt_inicioContagem->EditCustomAttributes = "readonly";
			$this->dt_inicioContagem->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->dt_inicioContagem->CurrentValue, 7));
			$this->dt_inicioContagem->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dt_inicioContagem->FldCaption()));

			// dt_emissao
			// hh_emissao
			// ic_tamanho

			$this->ic_tamanho->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_tamanho->FldTagValue(1), $this->ic_tamanho->FldTagCaption(1) <> "" ? $this->ic_tamanho->FldTagCaption(1) : $this->ic_tamanho->FldTagValue(1));
			$arwrk[] = array($this->ic_tamanho->FldTagValue(2), $this->ic_tamanho->FldTagCaption(2) <> "" ? $this->ic_tamanho->FldTagCaption(2) : $this->ic_tamanho->FldTagValue(2));
			$this->ic_tamanho->EditValue = $arwrk;

			// ic_esforco
			$this->ic_esforco->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_esforco->FldTagValue(1), $this->ic_esforco->FldTagCaption(1) <> "" ? $this->ic_esforco->FldTagCaption(1) : $this->ic_esforco->FldTagValue(1));
			$arwrk[] = array($this->ic_esforco->FldTagValue(2), $this->ic_esforco->FldTagCaption(2) <> "" ? $this->ic_esforco->FldTagCaption(2) : $this->ic_esforco->FldTagValue(2));
			$this->ic_esforco->EditValue = $arwrk;

			// ic_prazo
			$this->ic_prazo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_prazo->FldTagValue(1), $this->ic_prazo->FldTagCaption(1) <> "" ? $this->ic_prazo->FldTagCaption(1) : $this->ic_prazo->FldTagValue(1));
			$arwrk[] = array($this->ic_prazo->FldTagValue(2), $this->ic_prazo->FldTagCaption(2) <> "" ? $this->ic_prazo->FldTagCaption(2) : $this->ic_prazo->FldTagValue(2));
			$this->ic_prazo->EditValue = $arwrk;

			// ic_custo
			$this->ic_custo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_custo->FldTagValue(1), $this->ic_custo->FldTagCaption(1) <> "" ? $this->ic_custo->FldTagCaption(1) : $this->ic_custo->FldTagValue(1));
			$arwrk[] = array($this->ic_custo->FldTagValue(2), $this->ic_custo->FldTagCaption(2) <> "" ? $this->ic_custo->FldTagCaption(2) : $this->ic_custo->FldTagValue(2));
			$this->ic_custo->EditValue = $arwrk;

			// Edit refer script
			// nu_solicitacao

			$this->nu_solicitacao->HrefValue = "";

			// nu_versao
			$this->nu_versao->HrefValue = "";

			// qt_pf
			$this->qt_pf->HrefValue = "";

			// qt_horas
			$this->qt_horas->HrefValue = "";

			// qt_prazoMeses
			$this->qt_prazoMeses->HrefValue = "";

			// qt_prazoDias
			$this->qt_prazoDias->HrefValue = "";

			// vr_contratacao
			$this->vr_contratacao->HrefValue = "";

			// nu_usuarioResp
			$this->nu_usuarioResp->HrefValue = "";

			// dt_inicioSolicitacao
			$this->dt_inicioSolicitacao->HrefValue = "";

			// dt_inicioContagem
			$this->dt_inicioContagem->HrefValue = "";

			// dt_emissao
			$this->dt_emissao->HrefValue = "";

			// hh_emissao
			$this->hh_emissao->HrefValue = "";

			// ic_tamanho
			$this->ic_tamanho->HrefValue = "";

			// ic_esforco
			$this->ic_esforco->HrefValue = "";

			// ic_prazo
			$this->ic_prazo->HrefValue = "";

			// ic_custo
			$this->ic_custo->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// nu_solicitacao
			$this->nu_solicitacao->EditCustomAttributes = "";
			if (strval($this->nu_solicitacao->CurrentValue) <> "") {
				$sFilterWrk = "[nu_solMetricas]" . ew_SearchString("=", $this->nu_solicitacao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_solMetricas], [nu_solMetricas] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[solicitacaoMetricas]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_solicitacao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_solMetricas] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_solicitacao->EditValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_solicitacao->EditValue = $this->nu_solicitacao->CurrentValue;
				}
			} else {
				$this->nu_solicitacao->EditValue = NULL;
			}
			$this->nu_solicitacao->ViewCustomAttributes = "";

			// nu_versao
			$this->nu_versao->EditCustomAttributes = "readonly";
			$this->nu_versao->EditValue = $this->nu_versao->CurrentValue;
			$this->nu_versao->ViewCustomAttributes = "";

			// qt_pf
			$this->qt_pf->EditCustomAttributes = "readonly";
			$this->qt_pf->EditValue = ew_HtmlEncode($this->qt_pf->CurrentValue);
			$this->qt_pf->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_pf->FldCaption()));
			if (strval($this->qt_pf->EditValue) <> "" && is_numeric($this->qt_pf->EditValue)) {
			$this->qt_pf->EditValue = ew_FormatNumber($this->qt_pf->EditValue, -2, -1, -2, 0);
			$this->qt_pf->OldValue = $this->qt_pf->EditValue;
			}

			// qt_horas
			$this->qt_horas->EditCustomAttributes = "readonly";
			$this->qt_horas->EditValue = ew_HtmlEncode($this->qt_horas->CurrentValue);
			$this->qt_horas->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_horas->FldCaption()));
			if (strval($this->qt_horas->EditValue) <> "" && is_numeric($this->qt_horas->EditValue)) {
			$this->qt_horas->EditValue = ew_FormatNumber($this->qt_horas->EditValue, -2, -1, -2, 0);
			$this->qt_horas->OldValue = $this->qt_horas->EditValue;
			}

			// qt_prazoMeses
			$this->qt_prazoMeses->EditCustomAttributes = "readonly";
			$this->qt_prazoMeses->EditValue = ew_HtmlEncode($this->qt_prazoMeses->CurrentValue);
			$this->qt_prazoMeses->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_prazoMeses->FldCaption()));
			if (strval($this->qt_prazoMeses->EditValue) <> "" && is_numeric($this->qt_prazoMeses->EditValue)) {
			$this->qt_prazoMeses->EditValue = ew_FormatNumber($this->qt_prazoMeses->EditValue, -2, -1, -2, 0);
			$this->qt_prazoMeses->OldValue = $this->qt_prazoMeses->EditValue;
			}

			// qt_prazoDias
			$this->qt_prazoDias->EditCustomAttributes = "readonly";
			$this->qt_prazoDias->EditValue = ew_HtmlEncode($this->qt_prazoDias->CurrentValue);
			$this->qt_prazoDias->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_prazoDias->FldCaption()));

			// vr_contratacao
			$this->vr_contratacao->EditCustomAttributes = "readonly";
			$this->vr_contratacao->EditValue = ew_HtmlEncode($this->vr_contratacao->CurrentValue);
			$this->vr_contratacao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_contratacao->FldCaption()));
			if (strval($this->vr_contratacao->EditValue) <> "" && is_numeric($this->vr_contratacao->EditValue)) {
			$this->vr_contratacao->EditValue = ew_FormatNumber($this->vr_contratacao->EditValue, -2, -2, -2, -2);
			$this->vr_contratacao->OldValue = $this->vr_contratacao->EditValue;
			}

			// nu_usuarioResp
			// dt_inicioSolicitacao

			$this->dt_inicioSolicitacao->EditCustomAttributes = "readonly";
			$this->dt_inicioSolicitacao->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->dt_inicioSolicitacao->CurrentValue, 7));
			$this->dt_inicioSolicitacao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dt_inicioSolicitacao->FldCaption()));

			// dt_inicioContagem
			$this->dt_inicioContagem->EditCustomAttributes = "readonly";
			$this->dt_inicioContagem->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->dt_inicioContagem->CurrentValue, 7));
			$this->dt_inicioContagem->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dt_inicioContagem->FldCaption()));

			// dt_emissao
			// hh_emissao
			// ic_tamanho

			$this->ic_tamanho->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_tamanho->FldTagValue(1), $this->ic_tamanho->FldTagCaption(1) <> "" ? $this->ic_tamanho->FldTagCaption(1) : $this->ic_tamanho->FldTagValue(1));
			$arwrk[] = array($this->ic_tamanho->FldTagValue(2), $this->ic_tamanho->FldTagCaption(2) <> "" ? $this->ic_tamanho->FldTagCaption(2) : $this->ic_tamanho->FldTagValue(2));
			$this->ic_tamanho->EditValue = $arwrk;

			// ic_esforco
			$this->ic_esforco->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_esforco->FldTagValue(1), $this->ic_esforco->FldTagCaption(1) <> "" ? $this->ic_esforco->FldTagCaption(1) : $this->ic_esforco->FldTagValue(1));
			$arwrk[] = array($this->ic_esforco->FldTagValue(2), $this->ic_esforco->FldTagCaption(2) <> "" ? $this->ic_esforco->FldTagCaption(2) : $this->ic_esforco->FldTagValue(2));
			$this->ic_esforco->EditValue = $arwrk;

			// ic_prazo
			$this->ic_prazo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_prazo->FldTagValue(1), $this->ic_prazo->FldTagCaption(1) <> "" ? $this->ic_prazo->FldTagCaption(1) : $this->ic_prazo->FldTagValue(1));
			$arwrk[] = array($this->ic_prazo->FldTagValue(2), $this->ic_prazo->FldTagCaption(2) <> "" ? $this->ic_prazo->FldTagCaption(2) : $this->ic_prazo->FldTagValue(2));
			$this->ic_prazo->EditValue = $arwrk;

			// ic_custo
			$this->ic_custo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_custo->FldTagValue(1), $this->ic_custo->FldTagCaption(1) <> "" ? $this->ic_custo->FldTagCaption(1) : $this->ic_custo->FldTagValue(1));
			$arwrk[] = array($this->ic_custo->FldTagValue(2), $this->ic_custo->FldTagCaption(2) <> "" ? $this->ic_custo->FldTagCaption(2) : $this->ic_custo->FldTagValue(2));
			$this->ic_custo->EditValue = $arwrk;

			// Edit refer script
			// nu_solicitacao

			$this->nu_solicitacao->HrefValue = "";

			// nu_versao
			$this->nu_versao->HrefValue = "";

			// qt_pf
			$this->qt_pf->HrefValue = "";

			// qt_horas
			$this->qt_horas->HrefValue = "";

			// qt_prazoMeses
			$this->qt_prazoMeses->HrefValue = "";

			// qt_prazoDias
			$this->qt_prazoDias->HrefValue = "";

			// vr_contratacao
			$this->vr_contratacao->HrefValue = "";

			// nu_usuarioResp
			$this->nu_usuarioResp->HrefValue = "";

			// dt_inicioSolicitacao
			$this->dt_inicioSolicitacao->HrefValue = "";

			// dt_inicioContagem
			$this->dt_inicioContagem->HrefValue = "";

			// dt_emissao
			$this->dt_emissao->HrefValue = "";

			// hh_emissao
			$this->hh_emissao->HrefValue = "";

			// ic_tamanho
			$this->ic_tamanho->HrefValue = "";

			// ic_esforco
			$this->ic_esforco->HrefValue = "";

			// ic_prazo
			$this->ic_prazo->HrefValue = "";

			// ic_custo
			$this->ic_custo->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->nu_solicitacao->FldIsDetailKey && !is_null($this->nu_solicitacao->FormValue) && $this->nu_solicitacao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_solicitacao->FldCaption());
		}
		if (!$this->nu_versao->FldIsDetailKey && !is_null($this->nu_versao->FormValue) && $this->nu_versao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_versao->FldCaption());
		}
		if (!ew_CheckInteger($this->nu_versao->FormValue)) {
			ew_AddMessage($gsFormError, $this->nu_versao->FldErrMsg());
		}
		if (!ew_CheckNumber($this->qt_pf->FormValue)) {
			ew_AddMessage($gsFormError, $this->qt_pf->FldErrMsg());
		}
		if (!ew_CheckNumber($this->qt_horas->FormValue)) {
			ew_AddMessage($gsFormError, $this->qt_horas->FldErrMsg());
		}
		if (!ew_CheckNumber($this->qt_prazoMeses->FormValue)) {
			ew_AddMessage($gsFormError, $this->qt_prazoMeses->FldErrMsg());
		}
		if (!ew_CheckInteger($this->qt_prazoDias->FormValue)) {
			ew_AddMessage($gsFormError, $this->qt_prazoDias->FldErrMsg());
		}
		if (!ew_CheckNumber($this->vr_contratacao->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_contratacao->FldErrMsg());
		}
		if (!$this->dt_inicioSolicitacao->FldIsDetailKey && !is_null($this->dt_inicioSolicitacao->FormValue) && $this->dt_inicioSolicitacao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->dt_inicioSolicitacao->FldCaption());
		}
		if (!ew_CheckEuroDate($this->dt_inicioSolicitacao->FormValue)) {
			ew_AddMessage($gsFormError, $this->dt_inicioSolicitacao->FldErrMsg());
		}
		if (!$this->dt_inicioContagem->FldIsDetailKey && !is_null($this->dt_inicioContagem->FormValue) && $this->dt_inicioContagem->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->dt_inicioContagem->FldCaption());
		}
		if (!ew_CheckEuroDate($this->dt_inicioContagem->FormValue)) {
			ew_AddMessage($gsFormError, $this->dt_inicioContagem->FldErrMsg());
		}
		if ($this->ic_tamanho->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_tamanho->FldCaption());
		}
		if ($this->ic_esforco->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_esforco->FldCaption());
		}
		if ($this->ic_prazo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_prazo->FldCaption());
		}
		if ($this->ic_custo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_custo->FldCaption());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
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
				$sThisKey .= $row['nu_solicitacao'];
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
			if ($DeleteRows) {
				foreach ($rsold as $row)
					$this->WriteAuditTrailOnDelete($row);
			}
		} else {
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// nu_solicitacao
			// nu_versao
			// qt_pf

			$this->qt_pf->SetDbValueDef($rsnew, $this->qt_pf->CurrentValue, NULL, $this->qt_pf->ReadOnly);

			// qt_horas
			$this->qt_horas->SetDbValueDef($rsnew, $this->qt_horas->CurrentValue, NULL, $this->qt_horas->ReadOnly);

			// qt_prazoMeses
			$this->qt_prazoMeses->SetDbValueDef($rsnew, $this->qt_prazoMeses->CurrentValue, NULL, $this->qt_prazoMeses->ReadOnly);

			// qt_prazoDias
			$this->qt_prazoDias->SetDbValueDef($rsnew, $this->qt_prazoDias->CurrentValue, NULL, $this->qt_prazoDias->ReadOnly);

			// vr_contratacao
			$this->vr_contratacao->SetDbValueDef($rsnew, $this->vr_contratacao->CurrentValue, NULL, $this->vr_contratacao->ReadOnly);

			// nu_usuarioResp
			$this->nu_usuarioResp->SetDbValueDef($rsnew, CurrentUserID(), NULL);
			$rsnew['nu_usuarioResp'] = &$this->nu_usuarioResp->DbValue;

			// dt_inicioSolicitacao
			$this->dt_inicioSolicitacao->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->dt_inicioSolicitacao->CurrentValue, 7), NULL, $this->dt_inicioSolicitacao->ReadOnly);

			// dt_inicioContagem
			$this->dt_inicioContagem->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->dt_inicioContagem->CurrentValue, 7), NULL, $this->dt_inicioContagem->ReadOnly);

			// dt_emissao
			$this->dt_emissao->SetDbValueDef($rsnew, ew_CurrentDate(), ew_CurrentDate());
			$rsnew['dt_emissao'] = &$this->dt_emissao->DbValue;

			// hh_emissao
			$this->hh_emissao->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
			$rsnew['hh_emissao'] = &$this->hh_emissao->DbValue;

			// ic_tamanho
			$this->ic_tamanho->SetDbValueDef($rsnew, $this->ic_tamanho->CurrentValue, NULL, $this->ic_tamanho->ReadOnly);

			// ic_esforco
			$this->ic_esforco->SetDbValueDef($rsnew, $this->ic_esforco->CurrentValue, NULL, $this->ic_esforco->ReadOnly);

			// ic_prazo
			$this->ic_prazo->SetDbValueDef($rsnew, $this->ic_prazo->CurrentValue, NULL, $this->ic_prazo->ReadOnly);

			// ic_custo
			$this->ic_custo->SetDbValueDef($rsnew, $this->ic_custo->CurrentValue, NULL, $this->ic_custo->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = 'ew_ErrorFn';
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		if ($EditRow) {
			$this->WriteAuditTrailOnEdit($rsold, $rsnew);
		}
		$rs->Close();
		return $EditRow;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Set up foreign key field value from Session
			if ($this->getCurrentMasterTable() == "solicitacaoMetricas") {
				$this->nu_solicitacao->CurrentValue = $this->nu_solicitacao->getSessionValue();
			}

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// nu_solicitacao
		$this->nu_solicitacao->SetDbValueDef($rsnew, $this->nu_solicitacao->CurrentValue, 0, FALSE);

		// nu_versao
		$this->nu_versao->SetDbValueDef($rsnew, $this->nu_versao->CurrentValue, 0, FALSE);

		// qt_pf
		$this->qt_pf->SetDbValueDef($rsnew, $this->qt_pf->CurrentValue, NULL, FALSE);

		// qt_horas
		$this->qt_horas->SetDbValueDef($rsnew, $this->qt_horas->CurrentValue, NULL, FALSE);

		// qt_prazoMeses
		$this->qt_prazoMeses->SetDbValueDef($rsnew, $this->qt_prazoMeses->CurrentValue, NULL, FALSE);

		// qt_prazoDias
		$this->qt_prazoDias->SetDbValueDef($rsnew, $this->qt_prazoDias->CurrentValue, NULL, FALSE);

		// vr_contratacao
		$this->vr_contratacao->SetDbValueDef($rsnew, $this->vr_contratacao->CurrentValue, NULL, FALSE);

		// nu_usuarioResp
		$this->nu_usuarioResp->SetDbValueDef($rsnew, CurrentUserID(), NULL);
		$rsnew['nu_usuarioResp'] = &$this->nu_usuarioResp->DbValue;

		// dt_inicioSolicitacao
		$this->dt_inicioSolicitacao->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->dt_inicioSolicitacao->CurrentValue, 7), NULL, FALSE);

		// dt_inicioContagem
		$this->dt_inicioContagem->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->dt_inicioContagem->CurrentValue, 7), NULL, FALSE);

		// dt_emissao
		$this->dt_emissao->SetDbValueDef($rsnew, ew_CurrentDate(), ew_CurrentDate());
		$rsnew['dt_emissao'] = &$this->dt_emissao->DbValue;

		// hh_emissao
		$this->hh_emissao->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
		$rsnew['hh_emissao'] = &$this->hh_emissao->DbValue;

		// ic_tamanho
		$this->ic_tamanho->SetDbValueDef($rsnew, $this->ic_tamanho->CurrentValue, NULL, FALSE);

		// ic_esforco
		$this->ic_esforco->SetDbValueDef($rsnew, $this->ic_esforco->CurrentValue, NULL, FALSE);

		// ic_prazo
		$this->ic_prazo->SetDbValueDef($rsnew, $this->ic_prazo->CurrentValue, NULL, FALSE);

		// ic_custo
		$this->ic_custo->SetDbValueDef($rsnew, $this->ic_custo->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && $this->nu_solicitacao->CurrentValue == "" && $this->nu_solicitacao->getSessionValue() == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && $this->nu_versao->CurrentValue == "" && $this->nu_versao->getSessionValue() == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check for duplicate key
		if ($bInsertRow && $this->ValidateKey) {
			$sFilter = $this->KeyFilter();
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sKeyErrMsg = str_replace("%f", $sFilter, $Language->Phrase("DupKey"));
				$this->setFailureMessage($sKeyErrMsg);
				$rsChk->Close();
				$bInsertRow = FALSE;
			}
		}
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Get insert id if necessary
		if ($AddRow) {
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
			$this->WriteAuditTrailOnAdd($rsnew);
		}
		return $AddRow;
	}

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {

		// Hide foreign keys
		$sMasterTblVar = $this->getCurrentMasterTable();
		if ($sMasterTblVar == "solicitacaoMetricas") {
			$this->nu_solicitacao->Visible = FALSE;
			if ($GLOBALS["solicitacaoMetricas"]->EventCancelled) $this->EventCancelled = TRUE;
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); //  Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'laudo';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'laudo';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_solicitacao'];
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_versao'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
	  $usr = CurrentUserID();
		foreach (array_keys($rs) as $fldname) {
			if ($this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$newvalue = $rs[$fldname];
					else
						$newvalue = "[MEMO]"; // Memo Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$newvalue = "[XML]"; // XML Field
				} else {
					$newvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $usr, "A", $table, $fldname, $key, "", $newvalue);
			}
		}
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		if (!$this->AuditTrailOnEdit) return;
		$table = 'laudo';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['nu_solicitacao'];
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['nu_versao'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
	  $usr = CurrentUserID();
		foreach (array_keys($rsnew) as $fldname) {
			if ($this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_DATE) { // DateTime field
					$modified = (ew_FormatDateTime($rsold[$fldname], 0) <> ew_FormatDateTime($rsnew[$fldname], 0));
				} else {
					$modified = !ew_CompareValue($rsold[$fldname], $rsnew[$fldname]);
				}
				if ($modified) {
					if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) { // Memo field
						if (EW_AUDIT_TRAIL_TO_DATABASE) {
							$oldvalue = $rsold[$fldname];
							$newvalue = $rsnew[$fldname];
						} else {
							$oldvalue = "[MEMO]";
							$newvalue = "[MEMO]";
						}
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) { // XML field
						$oldvalue = "[XML]";
						$newvalue = "[XML]";
					} else {
						$oldvalue = $rsold[$fldname];
						$newvalue = $rsnew[$fldname];
					}
					ew_WriteAuditTrail("log", $dt, $id, $usr, "U", $table, $fldname, $key, $oldvalue, $newvalue);
				}
			}
		}
	}

	// Write Audit Trail (delete page)
	function WriteAuditTrailOnDelete(&$rs) {
		if (!$this->AuditTrailOnDelete) return;
		$table = 'laudo';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_solicitacao'];
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
