<?php include_once "contagempf_funcaoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php

//
// Page class
//

$contagempf_funcao_grid = NULL; // Initialize page object first

class ccontagempf_funcao_grid extends ccontagempf_funcao {

	// Page ID
	var $PageID = 'grid';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'contagempf_funcao';

	// Page object name
	var $PageObjName = 'contagempf_funcao_grid';

	// Grid form hidden field names
	var $FormName = 'fcontagempf_funcaogrid';
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

		// Table object (contagempf_funcao)
		if (!isset($GLOBALS["contagempf_funcao"])) {
			$GLOBALS["contagempf_funcao"] = &$this;

//			$GLOBALS["MasterTable"] = &$GLOBALS["Table"];
//			if (!isset($GLOBALS["Table"])) $GLOBALS["Table"] = &$GLOBALS["contagempf_funcao"];

		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'grid', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'contagempf_funcao', TRUE);

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
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "contagempf") {
			global $contagempf;
			$rsmaster = $contagempf->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("contagempflist.php"); // Return to master page
			} else {
				$contagempf->LoadListRowValues($rsmaster);
				$contagempf->RowType = EW_ROWTYPE_MASTER; // Master row
				$contagempf->RenderListRow();
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
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
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
		if (count($arrKeyFlds) >= 1) {
			$this->nu_funcao->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->nu_funcao->FormValue))
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
					$sKey .= $this->nu_funcao->CurrentValue;

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
			$this->ClearInlineMode(); // Clear grid add mode
		} else {
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
		if ($objForm->HasValue("x_nu_agrupador") && $objForm->HasValue("o_nu_agrupador") && $this->nu_agrupador->CurrentValue <> $this->nu_agrupador->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_nu_uc") && $objForm->HasValue("o_nu_uc") && $this->nu_uc->CurrentValue <> $this->nu_uc->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_no_funcao") && $objForm->HasValue("o_no_funcao") && $this->no_funcao->CurrentValue <> $this->no_funcao->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_nu_tpManutencao") && $objForm->HasValue("o_nu_tpManutencao") && $this->nu_tpManutencao->CurrentValue <> $this->nu_tpManutencao->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_nu_tpElemento") && $objForm->HasValue("o_nu_tpElemento") && $this->nu_tpElemento->CurrentValue <> $this->nu_tpElemento->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_qt_alr") && $objForm->HasValue("o_qt_alr") && $this->qt_alr->CurrentValue <> $this->qt_alr->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_qt_der") && $objForm->HasValue("o_qt_der") && $this->qt_der->CurrentValue <> $this->qt_der->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_ic_complexApf") && $objForm->HasValue("o_ic_complexApf") && $this->ic_complexApf->CurrentValue <> $this->ic_complexApf->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_vr_contribuicao") && $objForm->HasValue("o_vr_contribuicao") && $this->vr_contribuicao->CurrentValue <> $this->vr_contribuicao->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_vr_fatorReducao") && $objForm->HasValue("o_vr_fatorReducao") && $this->vr_fatorReducao->CurrentValue <> $this->vr_fatorReducao->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_pc_varFasesRoteiro") && $objForm->HasValue("o_pc_varFasesRoteiro") && $this->pc_varFasesRoteiro->CurrentValue <> $this->pc_varFasesRoteiro->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_vr_qtPf") && $objForm->HasValue("o_vr_qtPf") && $this->vr_qtPf->CurrentValue <> $this->vr_qtPf->OldValue)
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
				$this->nu_agrupador->setSort("ASC");
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
				$this->nu_contagem->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->setSessionOrderByList($sOrderBy);
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
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->nu_funcao->CurrentValue . "\">";
		}
		$this->RenderListOptionsExt();
	}

	// Set record key
	function SetRecordKey(&$key, $rs) {
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs->fields('nu_funcao');
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
		$this->nu_agrupador->CurrentValue = NULL;
		$this->nu_agrupador->OldValue = $this->nu_agrupador->CurrentValue;
		$this->nu_uc->CurrentValue = NULL;
		$this->nu_uc->OldValue = $this->nu_uc->CurrentValue;
		$this->no_funcao->CurrentValue = NULL;
		$this->no_funcao->OldValue = $this->no_funcao->CurrentValue;
		$this->nu_tpManutencao->CurrentValue = NULL;
		$this->nu_tpManutencao->OldValue = $this->nu_tpManutencao->CurrentValue;
		$this->nu_tpElemento->CurrentValue = NULL;
		$this->nu_tpElemento->OldValue = $this->nu_tpElemento->CurrentValue;
		$this->qt_alr->CurrentValue = NULL;
		$this->qt_alr->OldValue = $this->qt_alr->CurrentValue;
		$this->qt_der->CurrentValue = NULL;
		$this->qt_der->OldValue = $this->qt_der->CurrentValue;
		$this->ic_complexApf->CurrentValue = NULL;
		$this->ic_complexApf->OldValue = $this->ic_complexApf->CurrentValue;
		$this->vr_contribuicao->CurrentValue = NULL;
		$this->vr_contribuicao->OldValue = $this->vr_contribuicao->CurrentValue;
		$this->vr_fatorReducao->CurrentValue = NULL;
		$this->vr_fatorReducao->OldValue = $this->vr_fatorReducao->CurrentValue;
		$this->pc_varFasesRoteiro->CurrentValue = 100.00;
		$this->pc_varFasesRoteiro->OldValue = $this->pc_varFasesRoteiro->CurrentValue;
		$this->vr_qtPf->CurrentValue = NULL;
		$this->vr_qtPf->OldValue = $this->vr_qtPf->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_agrupador->FldIsDetailKey) {
			$this->nu_agrupador->setFormValue($objForm->GetValue("x_nu_agrupador"));
		}
		$this->nu_agrupador->setOldValue($objForm->GetValue("o_nu_agrupador"));
		if (!$this->nu_uc->FldIsDetailKey) {
			$this->nu_uc->setFormValue($objForm->GetValue("x_nu_uc"));
		}
		$this->nu_uc->setOldValue($objForm->GetValue("o_nu_uc"));
		if (!$this->no_funcao->FldIsDetailKey) {
			$this->no_funcao->setFormValue($objForm->GetValue("x_no_funcao"));
		}
		$this->no_funcao->setOldValue($objForm->GetValue("o_no_funcao"));
		if (!$this->nu_tpManutencao->FldIsDetailKey) {
			$this->nu_tpManutencao->setFormValue($objForm->GetValue("x_nu_tpManutencao"));
		}
		$this->nu_tpManutencao->setOldValue($objForm->GetValue("o_nu_tpManutencao"));
		if (!$this->nu_tpElemento->FldIsDetailKey) {
			$this->nu_tpElemento->setFormValue($objForm->GetValue("x_nu_tpElemento"));
		}
		$this->nu_tpElemento->setOldValue($objForm->GetValue("o_nu_tpElemento"));
		if (!$this->qt_alr->FldIsDetailKey) {
			$this->qt_alr->setFormValue($objForm->GetValue("x_qt_alr"));
		}
		$this->qt_alr->setOldValue($objForm->GetValue("o_qt_alr"));
		if (!$this->qt_der->FldIsDetailKey) {
			$this->qt_der->setFormValue($objForm->GetValue("x_qt_der"));
		}
		$this->qt_der->setOldValue($objForm->GetValue("o_qt_der"));
		if (!$this->ic_complexApf->FldIsDetailKey) {
			$this->ic_complexApf->setFormValue($objForm->GetValue("x_ic_complexApf"));
		}
		$this->ic_complexApf->setOldValue($objForm->GetValue("o_ic_complexApf"));
		if (!$this->vr_contribuicao->FldIsDetailKey) {
			$this->vr_contribuicao->setFormValue($objForm->GetValue("x_vr_contribuicao"));
		}
		$this->vr_contribuicao->setOldValue($objForm->GetValue("o_vr_contribuicao"));
		if (!$this->vr_fatorReducao->FldIsDetailKey) {
			$this->vr_fatorReducao->setFormValue($objForm->GetValue("x_vr_fatorReducao"));
		}
		$this->vr_fatorReducao->setOldValue($objForm->GetValue("o_vr_fatorReducao"));
		if (!$this->pc_varFasesRoteiro->FldIsDetailKey) {
			$this->pc_varFasesRoteiro->setFormValue($objForm->GetValue("x_pc_varFasesRoteiro"));
		}
		$this->pc_varFasesRoteiro->setOldValue($objForm->GetValue("o_pc_varFasesRoteiro"));
		if (!$this->vr_qtPf->FldIsDetailKey) {
			$this->vr_qtPf->setFormValue($objForm->GetValue("x_vr_qtPf"));
		}
		$this->vr_qtPf->setOldValue($objForm->GetValue("o_vr_qtPf"));
		if (!$this->nu_funcao->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->nu_funcao->setFormValue($objForm->GetValue("x_nu_funcao"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->nu_funcao->CurrentValue = $this->nu_funcao->FormValue;
		$this->nu_agrupador->CurrentValue = $this->nu_agrupador->FormValue;
		$this->nu_uc->CurrentValue = $this->nu_uc->FormValue;
		$this->no_funcao->CurrentValue = $this->no_funcao->FormValue;
		$this->nu_tpManutencao->CurrentValue = $this->nu_tpManutencao->FormValue;
		$this->nu_tpElemento->CurrentValue = $this->nu_tpElemento->FormValue;
		$this->qt_alr->CurrentValue = $this->qt_alr->FormValue;
		$this->qt_der->CurrentValue = $this->qt_der->FormValue;
		$this->ic_complexApf->CurrentValue = $this->ic_complexApf->FormValue;
		$this->vr_contribuicao->CurrentValue = $this->vr_contribuicao->FormValue;
		$this->vr_fatorReducao->CurrentValue = $this->vr_fatorReducao->FormValue;
		$this->pc_varFasesRoteiro->CurrentValue = $this->pc_varFasesRoteiro->FormValue;
		$this->vr_qtPf->CurrentValue = $this->vr_qtPf->FormValue;
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
		$this->nu_contagem->setDbValue($rs->fields('nu_contagem'));
		$this->nu_funcao->setDbValue($rs->fields('nu_funcao'));
		$this->nu_agrupador->setDbValue($rs->fields('nu_agrupador'));
		if (array_key_exists('EV__nu_agrupador', $rs->fields)) {
			$this->nu_agrupador->VirtualValue = $rs->fields('EV__nu_agrupador'); // Set up virtual field value
		} else {
			$this->nu_agrupador->VirtualValue = ""; // Clear value
		}
		$this->nu_uc->setDbValue($rs->fields('nu_uc'));
		$this->no_funcao->setDbValue($rs->fields('no_funcao'));
		$this->nu_tpManutencao->setDbValue($rs->fields('nu_tpManutencao'));
		$this->nu_tpElemento->setDbValue($rs->fields('nu_tpElemento'));
		$this->qt_alr->setDbValue($rs->fields('qt_alr'));
		$this->ds_alr->setDbValue($rs->fields('ds_alr'));
		$this->qt_der->setDbValue($rs->fields('qt_der'));
		$this->ds_der->setDbValue($rs->fields('ds_der'));
		$this->ic_complexApf->setDbValue($rs->fields('ic_complexApf'));
		$this->vr_contribuicao->setDbValue($rs->fields('vr_contribuicao'));
		$this->vr_fatorReducao->setDbValue($rs->fields('vr_fatorReducao'));
		$this->pc_varFasesRoteiro->setDbValue($rs->fields('pc_varFasesRoteiro'));
		$this->vr_qtPf->setDbValue($rs->fields('vr_qtPf'));
		$this->ic_analalogia->setDbValue($rs->fields('ic_analalogia'));
		$this->ds_observacoes->setDbValue($rs->fields('ds_observacoes'));
		$this->nu_usuarioLogado->setDbValue($rs->fields('nu_usuarioLogado'));
		$this->dh_inclusao->setDbValue($rs->fields('dh_inclusao'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_contagem->DbValue = $row['nu_contagem'];
		$this->nu_funcao->DbValue = $row['nu_funcao'];
		$this->nu_agrupador->DbValue = $row['nu_agrupador'];
		$this->nu_uc->DbValue = $row['nu_uc'];
		$this->no_funcao->DbValue = $row['no_funcao'];
		$this->nu_tpManutencao->DbValue = $row['nu_tpManutencao'];
		$this->nu_tpElemento->DbValue = $row['nu_tpElemento'];
		$this->qt_alr->DbValue = $row['qt_alr'];
		$this->ds_alr->DbValue = $row['ds_alr'];
		$this->qt_der->DbValue = $row['qt_der'];
		$this->ds_der->DbValue = $row['ds_der'];
		$this->ic_complexApf->DbValue = $row['ic_complexApf'];
		$this->vr_contribuicao->DbValue = $row['vr_contribuicao'];
		$this->vr_fatorReducao->DbValue = $row['vr_fatorReducao'];
		$this->pc_varFasesRoteiro->DbValue = $row['pc_varFasesRoteiro'];
		$this->vr_qtPf->DbValue = $row['vr_qtPf'];
		$this->ic_analalogia->DbValue = $row['ic_analalogia'];
		$this->ds_observacoes->DbValue = $row['ds_observacoes'];
		$this->nu_usuarioLogado->DbValue = $row['nu_usuarioLogado'];
		$this->dh_inclusao->DbValue = $row['dh_inclusao'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		$arKeys[] = $this->RowOldKey;
		$cnt = count($arKeys);
		if ($cnt >= 1) {
			if (strval($arKeys[0]) <> "")
				$this->nu_funcao->CurrentValue = strval($arKeys[0]); // nu_funcao
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

		if ($this->vr_fatorReducao->FormValue == $this->vr_fatorReducao->CurrentValue && is_numeric(ew_StrToFloat($this->vr_fatorReducao->CurrentValue)))
			$this->vr_fatorReducao->CurrentValue = ew_StrToFloat($this->vr_fatorReducao->CurrentValue);

		// Convert decimal values if posted back
		if ($this->pc_varFasesRoteiro->FormValue == $this->pc_varFasesRoteiro->CurrentValue && is_numeric(ew_StrToFloat($this->pc_varFasesRoteiro->CurrentValue)))
			$this->pc_varFasesRoteiro->CurrentValue = ew_StrToFloat($this->pc_varFasesRoteiro->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_qtPf->FormValue == $this->vr_qtPf->CurrentValue && is_numeric(ew_StrToFloat($this->vr_qtPf->CurrentValue)))
			$this->vr_qtPf->CurrentValue = ew_StrToFloat($this->vr_qtPf->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_contagem

		$this->nu_contagem->CellCssStyle = "white-space: nowrap;";

		// nu_funcao
		// nu_agrupador
		// nu_uc
		// no_funcao
		// nu_tpManutencao
		// nu_tpElemento
		// qt_alr
		// ds_alr
		// qt_der
		// ds_der
		// ic_complexApf
		// vr_contribuicao
		// vr_fatorReducao
		// pc_varFasesRoteiro
		// vr_qtPf
		// ic_analalogia
		// ds_observacoes
		// nu_usuarioLogado
		// dh_inclusao

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_agrupador
			if ($this->nu_agrupador->VirtualValue <> "") {
				$this->nu_agrupador->ViewValue = $this->nu_agrupador->VirtualValue;
			} else {
			if (strval($this->nu_agrupador->CurrentValue) <> "") {
				$sFilterWrk = "[nu_agrupador]" . ew_SearchString("=", $this->nu_agrupador->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_agrupador], [no_agrupador] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[contagempf_agrupador]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_agrupador, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_agrupador] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_agrupador->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_agrupador->ViewValue = $this->nu_agrupador->CurrentValue;
				}
			} else {
				$this->nu_agrupador->ViewValue = NULL;
			}
			}
			$this->nu_agrupador->ViewCustomAttributes = "";

			// nu_uc
			if (strval($this->nu_uc->CurrentValue) <> "") {
				$sFilterWrk = "[nu_uc]" . ew_SearchString("=", $this->nu_uc->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_uc], [co_alternativo] AS [DispFld], [no_uc] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[uc]";
			$sWhereWrk = "";
			$lookuptblfilter = "[nu_sistema] = (SELECT nu_sistema FROM contagempf WHERE nu_contagem = " . strval(CurrentPage()->nu_contagem->CurrentValue) . ")";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_uc, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [co_alternativo] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_uc->ViewValue = $rswrk->fields('DispFld');
					$this->nu_uc->ViewValue .= ew_ValueSeparator(1,$this->nu_uc) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_uc->ViewValue = $this->nu_uc->CurrentValue;
				}
			} else {
				$this->nu_uc->ViewValue = NULL;
			}
			$this->nu_uc->ViewCustomAttributes = "";

			// no_funcao
			$this->no_funcao->ViewValue = $this->no_funcao->CurrentValue;
			$this->no_funcao->ViewCustomAttributes = "";

			// nu_tpManutencao
			if (strval($this->nu_tpManutencao->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpManutencao]" . ew_SearchString("=", $this->nu_tpManutencao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpManutencao], [no_tpManutencao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpmanutencao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[nu_tpContagem]=(SELECT nu_tpContagem FROM contagempf WHERE nu_contagem = " . strval($this->nu_contagem->CurrentValue) . ")";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpManutencao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpManutencao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpManutencao->ViewValue = $this->nu_tpManutencao->CurrentValue;
				}
			} else {
				$this->nu_tpManutencao->ViewValue = NULL;
			}
			$this->nu_tpManutencao->ViewCustomAttributes = "";

			// nu_tpElemento
			if (strval($this->nu_tpElemento->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpElemento]" . ew_SearchString("=", $this->nu_tpElemento->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpElemento], [no_tpElemento] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpElemento]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpElemento, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_tpElemento] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpElemento->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpElemento->ViewValue = $this->nu_tpElemento->CurrentValue;
				}
			} else {
				$this->nu_tpElemento->ViewValue = NULL;
			}
			$this->nu_tpElemento->ViewCustomAttributes = "";

			// qt_alr
			$this->qt_alr->ViewValue = $this->qt_alr->CurrentValue;
			$this->qt_alr->ViewValue = ew_FormatNumber($this->qt_alr->ViewValue, 0, 0, 0, 0);
			$this->qt_alr->ViewCustomAttributes = "";

			// qt_der
			$this->qt_der->ViewValue = $this->qt_der->CurrentValue;
			$this->qt_der->ViewValue = ew_FormatNumber($this->qt_der->ViewValue, 0, 0, 0, 0);
			$this->qt_der->ViewCustomAttributes = "";

			// ic_complexApf
			$this->ic_complexApf->ViewValue = $this->ic_complexApf->CurrentValue;
			$this->ic_complexApf->ViewCustomAttributes = "";

			// vr_contribuicao
			$this->vr_contribuicao->ViewValue = $this->vr_contribuicao->CurrentValue;
			$this->vr_contribuicao->ViewValue = ew_FormatNumber($this->vr_contribuicao->ViewValue, 0, 0, 0, 0);
			$this->vr_contribuicao->ViewCustomAttributes = "";

			// vr_fatorReducao
			$this->vr_fatorReducao->ViewValue = $this->vr_fatorReducao->CurrentValue;
			$this->vr_fatorReducao->ViewCustomAttributes = "";

			// pc_varFasesRoteiro
			$this->pc_varFasesRoteiro->ViewValue = $this->pc_varFasesRoteiro->CurrentValue;
			$this->pc_varFasesRoteiro->ViewCustomAttributes = "";

			// vr_qtPf
			$this->vr_qtPf->ViewValue = $this->vr_qtPf->CurrentValue;
			$this->vr_qtPf->ViewCustomAttributes = "";

			// ic_analalogia
			if (strval($this->ic_analalogia->CurrentValue) <> "") {
				switch ($this->ic_analalogia->CurrentValue) {
					case $this->ic_analalogia->FldTagValue(1):
						$this->ic_analalogia->ViewValue = $this->ic_analalogia->FldTagCaption(1) <> "" ? $this->ic_analalogia->FldTagCaption(1) : $this->ic_analalogia->CurrentValue;
						break;
					case $this->ic_analalogia->FldTagValue(2):
						$this->ic_analalogia->ViewValue = $this->ic_analalogia->FldTagCaption(2) <> "" ? $this->ic_analalogia->FldTagCaption(2) : $this->ic_analalogia->CurrentValue;
						break;
					default:
						$this->ic_analalogia->ViewValue = $this->ic_analalogia->CurrentValue;
				}
			} else {
				$this->ic_analalogia->ViewValue = NULL;
			}
			$this->ic_analalogia->ViewCustomAttributes = "";

			// nu_agrupador
			$this->nu_agrupador->LinkCustomAttributes = "";
			$this->nu_agrupador->HrefValue = "";
			$this->nu_agrupador->TooltipValue = "";

			// nu_uc
			$this->nu_uc->LinkCustomAttributes = "";
			$this->nu_uc->HrefValue = "";
			$this->nu_uc->TooltipValue = "";

			// no_funcao
			$this->no_funcao->LinkCustomAttributes = "";
			$this->no_funcao->HrefValue = "";
			$this->no_funcao->TooltipValue = "";

			// nu_tpManutencao
			$this->nu_tpManutencao->LinkCustomAttributes = "";
			$this->nu_tpManutencao->HrefValue = "";
			$this->nu_tpManutencao->TooltipValue = "";

			// nu_tpElemento
			$this->nu_tpElemento->LinkCustomAttributes = "";
			$this->nu_tpElemento->HrefValue = "";
			$this->nu_tpElemento->TooltipValue = "";

			// qt_alr
			$this->qt_alr->LinkCustomAttributes = "";
			$this->qt_alr->HrefValue = "";
			$this->qt_alr->TooltipValue = "";

			// qt_der
			$this->qt_der->LinkCustomAttributes = "";
			$this->qt_der->HrefValue = "";
			$this->qt_der->TooltipValue = "";

			// ic_complexApf
			$this->ic_complexApf->LinkCustomAttributes = "";
			$this->ic_complexApf->HrefValue = "";
			$this->ic_complexApf->TooltipValue = "";

			// vr_contribuicao
			$this->vr_contribuicao->LinkCustomAttributes = "";
			$this->vr_contribuicao->HrefValue = "";
			$this->vr_contribuicao->TooltipValue = "";

			// vr_fatorReducao
			$this->vr_fatorReducao->LinkCustomAttributes = "";
			$this->vr_fatorReducao->HrefValue = "";
			$this->vr_fatorReducao->TooltipValue = "";

			// pc_varFasesRoteiro
			$this->pc_varFasesRoteiro->LinkCustomAttributes = "";
			$this->pc_varFasesRoteiro->HrefValue = "";
			$this->pc_varFasesRoteiro->TooltipValue = "";

			// vr_qtPf
			$this->vr_qtPf->LinkCustomAttributes = "";
			$this->vr_qtPf->HrefValue = "";
			$this->vr_qtPf->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nu_agrupador
			$this->nu_agrupador->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_agrupador], [no_agrupador] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [nu_contagem] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[contagempf_agrupador]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_agrupador, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_agrupador] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_agrupador->EditValue = $arwrk;

			// nu_uc
			$this->nu_uc->EditCustomAttributes = "";
			if (trim(strval($this->nu_uc->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "[nu_uc]" . ew_SearchString("=", $this->nu_uc->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT [nu_uc], [co_alternativo] AS [DispFld], [no_uc] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[uc]";
			$sWhereWrk = "";
			$lookuptblfilter = "[nu_sistema] = (SELECT nu_sistema FROM contagempf WHERE nu_contagem = " . strval(CurrentPage()->nu_contagem->CurrentValue) . ")";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_uc, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [co_alternativo] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_uc->EditValue = $arwrk;

			// no_funcao
			$this->no_funcao->EditCustomAttributes = "";
			$this->no_funcao->EditValue = ew_HtmlEncode($this->no_funcao->CurrentValue);
			$this->no_funcao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_funcao->FldCaption()));

			// nu_tpManutencao
			$this->nu_tpManutencao->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_tpManutencao], [no_tpManutencao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[tpmanutencao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[nu_tpContagem]=(SELECT nu_tpContagem FROM contagempf WHERE nu_contagem = " . strval($this->nu_contagem->CurrentValue) . ")";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpManutencao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_tpManutencao->EditValue = $arwrk;

			// nu_tpElemento
			$this->nu_tpElemento->EditCustomAttributes = "onchange='CalcularPF()'";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_tpElemento], [no_tpElemento] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [nu_tpManutencao] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[tpElemento]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpElemento, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_tpElemento] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_tpElemento->EditValue = $arwrk;

			// qt_alr
			$this->qt_alr->EditCustomAttributes = "autocomplete='off' onchange='CalcularPF()'";
			$this->qt_alr->EditValue = ew_HtmlEncode($this->qt_alr->CurrentValue);
			$this->qt_alr->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_alr->FldCaption()));

			// qt_der
			$this->qt_der->EditCustomAttributes = "autocomplete='off' onchange='CalcularPF()'";
			$this->qt_der->EditValue = ew_HtmlEncode($this->qt_der->CurrentValue);
			$this->qt_der->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_der->FldCaption()));

			// ic_complexApf
			$this->ic_complexApf->EditCustomAttributes = "readonly";
			$this->ic_complexApf->EditValue = ew_HtmlEncode($this->ic_complexApf->CurrentValue);
			$this->ic_complexApf->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ic_complexApf->FldCaption()));

			// vr_contribuicao
			$this->vr_contribuicao->EditCustomAttributes = "readonly";
			$this->vr_contribuicao->EditValue = ew_HtmlEncode($this->vr_contribuicao->CurrentValue);
			$this->vr_contribuicao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_contribuicao->FldCaption()));

			// vr_fatorReducao
			$this->vr_fatorReducao->EditCustomAttributes = "readonly";
			$this->vr_fatorReducao->EditValue = ew_HtmlEncode($this->vr_fatorReducao->CurrentValue);
			$this->vr_fatorReducao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_fatorReducao->FldCaption()));
			if (strval($this->vr_fatorReducao->EditValue) <> "" && is_numeric($this->vr_fatorReducao->EditValue)) {
			$this->vr_fatorReducao->EditValue = ew_FormatNumber($this->vr_fatorReducao->EditValue, -2, -1, -2, 0);
			$this->vr_fatorReducao->OldValue = $this->vr_fatorReducao->EditValue;
			}

			// pc_varFasesRoteiro
			$this->pc_varFasesRoteiro->EditCustomAttributes = "readonly";
			$this->pc_varFasesRoteiro->EditValue = ew_HtmlEncode($this->pc_varFasesRoteiro->CurrentValue);
			$this->pc_varFasesRoteiro->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->pc_varFasesRoteiro->FldCaption()));
			if (strval($this->pc_varFasesRoteiro->EditValue) <> "" && is_numeric($this->pc_varFasesRoteiro->EditValue)) {
			$this->pc_varFasesRoteiro->EditValue = ew_FormatNumber($this->pc_varFasesRoteiro->EditValue, -2, -1, -2, 0);
			$this->pc_varFasesRoteiro->OldValue = $this->pc_varFasesRoteiro->EditValue;
			}

			// vr_qtPf
			$this->vr_qtPf->EditCustomAttributes = "readonly";
			$this->vr_qtPf->EditValue = ew_HtmlEncode($this->vr_qtPf->CurrentValue);
			$this->vr_qtPf->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_qtPf->FldCaption()));
			if (strval($this->vr_qtPf->EditValue) <> "" && is_numeric($this->vr_qtPf->EditValue)) {
			$this->vr_qtPf->EditValue = ew_FormatNumber($this->vr_qtPf->EditValue, -2, -1, -2, 0);
			$this->vr_qtPf->OldValue = $this->vr_qtPf->EditValue;
			}

			// Edit refer script
			// nu_agrupador

			$this->nu_agrupador->HrefValue = "";

			// nu_uc
			$this->nu_uc->HrefValue = "";

			// no_funcao
			$this->no_funcao->HrefValue = "";

			// nu_tpManutencao
			$this->nu_tpManutencao->HrefValue = "";

			// nu_tpElemento
			$this->nu_tpElemento->HrefValue = "";

			// qt_alr
			$this->qt_alr->HrefValue = "";

			// qt_der
			$this->qt_der->HrefValue = "";

			// ic_complexApf
			$this->ic_complexApf->HrefValue = "";

			// vr_contribuicao
			$this->vr_contribuicao->HrefValue = "";

			// vr_fatorReducao
			$this->vr_fatorReducao->HrefValue = "";

			// pc_varFasesRoteiro
			$this->pc_varFasesRoteiro->HrefValue = "";

			// vr_qtPf
			$this->vr_qtPf->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// nu_agrupador
			$this->nu_agrupador->EditCustomAttributes = "";
			if ($this->nu_agrupador->VirtualValue <> "") {
				$this->nu_agrupador->ViewValue = $this->nu_agrupador->VirtualValue;
			} else {
			if (strval($this->nu_agrupador->CurrentValue) <> "") {
				$sFilterWrk = "[nu_agrupador]" . ew_SearchString("=", $this->nu_agrupador->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_agrupador], [no_agrupador] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[contagempf_agrupador]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_agrupador, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_agrupador] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_agrupador->EditValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_agrupador->EditValue = $this->nu_agrupador->CurrentValue;
				}
			} else {
				$this->nu_agrupador->EditValue = NULL;
			}
			}
			$this->nu_agrupador->ViewCustomAttributes = "";

			// nu_uc
			$this->nu_uc->EditCustomAttributes = "";
			if (trim(strval($this->nu_uc->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "[nu_uc]" . ew_SearchString("=", $this->nu_uc->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT [nu_uc], [co_alternativo] AS [DispFld], [no_uc] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[uc]";
			$sWhereWrk = "";
			$lookuptblfilter = "[nu_sistema] = (SELECT nu_sistema FROM contagempf WHERE nu_contagem = " . strval(CurrentPage()->nu_contagem->CurrentValue) . ")";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_uc, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [co_alternativo] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_uc->EditValue = $arwrk;

			// no_funcao
			$this->no_funcao->EditCustomAttributes = "";
			$this->no_funcao->EditValue = ew_HtmlEncode($this->no_funcao->CurrentValue);
			$this->no_funcao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_funcao->FldCaption()));

			// nu_tpManutencao
			$this->nu_tpManutencao->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_tpManutencao], [no_tpManutencao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[tpmanutencao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[nu_tpContagem]=(SELECT nu_tpContagem FROM contagempf WHERE nu_contagem = " . strval($this->nu_contagem->CurrentValue) . ")";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpManutencao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_tpManutencao->EditValue = $arwrk;

			// nu_tpElemento
			$this->nu_tpElemento->EditCustomAttributes = "onchange='CalcularPF()'";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_tpElemento], [no_tpElemento] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [nu_tpManutencao] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[tpElemento]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpElemento, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_tpElemento] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_tpElemento->EditValue = $arwrk;

			// qt_alr
			$this->qt_alr->EditCustomAttributes = "autocomplete='off' onchange='CalcularPF()'";
			$this->qt_alr->EditValue = ew_HtmlEncode($this->qt_alr->CurrentValue);
			$this->qt_alr->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_alr->FldCaption()));

			// qt_der
			$this->qt_der->EditCustomAttributes = "autocomplete='off' onchange='CalcularPF()'";
			$this->qt_der->EditValue = ew_HtmlEncode($this->qt_der->CurrentValue);
			$this->qt_der->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_der->FldCaption()));

			// ic_complexApf
			$this->ic_complexApf->EditCustomAttributes = "readonly";
			$this->ic_complexApf->EditValue = ew_HtmlEncode($this->ic_complexApf->CurrentValue);
			$this->ic_complexApf->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ic_complexApf->FldCaption()));

			// vr_contribuicao
			$this->vr_contribuicao->EditCustomAttributes = "readonly";
			$this->vr_contribuicao->EditValue = ew_HtmlEncode($this->vr_contribuicao->CurrentValue);
			$this->vr_contribuicao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_contribuicao->FldCaption()));

			// vr_fatorReducao
			$this->vr_fatorReducao->EditCustomAttributes = "readonly";
			$this->vr_fatorReducao->EditValue = ew_HtmlEncode($this->vr_fatorReducao->CurrentValue);
			$this->vr_fatorReducao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_fatorReducao->FldCaption()));
			if (strval($this->vr_fatorReducao->EditValue) <> "" && is_numeric($this->vr_fatorReducao->EditValue)) {
			$this->vr_fatorReducao->EditValue = ew_FormatNumber($this->vr_fatorReducao->EditValue, -2, -1, -2, 0);
			$this->vr_fatorReducao->OldValue = $this->vr_fatorReducao->EditValue;
			}

			// pc_varFasesRoteiro
			$this->pc_varFasesRoteiro->EditCustomAttributes = "readonly";
			$this->pc_varFasesRoteiro->EditValue = $this->pc_varFasesRoteiro->CurrentValue;
			$this->pc_varFasesRoteiro->ViewCustomAttributes = "";

			// vr_qtPf
			$this->vr_qtPf->EditCustomAttributes = "readonly";
			$this->vr_qtPf->EditValue = ew_HtmlEncode($this->vr_qtPf->CurrentValue);
			$this->vr_qtPf->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_qtPf->FldCaption()));
			if (strval($this->vr_qtPf->EditValue) <> "" && is_numeric($this->vr_qtPf->EditValue)) {
			$this->vr_qtPf->EditValue = ew_FormatNumber($this->vr_qtPf->EditValue, -2, -1, -2, 0);
			$this->vr_qtPf->OldValue = $this->vr_qtPf->EditValue;
			}

			// Edit refer script
			// nu_agrupador

			$this->nu_agrupador->HrefValue = "";

			// nu_uc
			$this->nu_uc->HrefValue = "";

			// no_funcao
			$this->no_funcao->HrefValue = "";

			// nu_tpManutencao
			$this->nu_tpManutencao->HrefValue = "";

			// nu_tpElemento
			$this->nu_tpElemento->HrefValue = "";

			// qt_alr
			$this->qt_alr->HrefValue = "";

			// qt_der
			$this->qt_der->HrefValue = "";

			// ic_complexApf
			$this->ic_complexApf->HrefValue = "";

			// vr_contribuicao
			$this->vr_contribuicao->HrefValue = "";

			// vr_fatorReducao
			$this->vr_fatorReducao->HrefValue = "";

			// pc_varFasesRoteiro
			$this->pc_varFasesRoteiro->HrefValue = "";

			// vr_qtPf
			$this->vr_qtPf->HrefValue = "";
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
		if (!$this->nu_agrupador->FldIsDetailKey && !is_null($this->nu_agrupador->FormValue) && $this->nu_agrupador->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_agrupador->FldCaption());
		}
		if (!$this->no_funcao->FldIsDetailKey && !is_null($this->no_funcao->FormValue) && $this->no_funcao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_funcao->FldCaption());
		}
		if (!$this->nu_tpManutencao->FldIsDetailKey && !is_null($this->nu_tpManutencao->FormValue) && $this->nu_tpManutencao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_tpManutencao->FldCaption());
		}
		if (!$this->nu_tpElemento->FldIsDetailKey && !is_null($this->nu_tpElemento->FormValue) && $this->nu_tpElemento->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_tpElemento->FldCaption());
		}
		if (!ew_CheckInteger($this->qt_alr->FormValue)) {
			ew_AddMessage($gsFormError, $this->qt_alr->FldErrMsg());
		}
		if (!ew_CheckInteger($this->qt_der->FormValue)) {
			ew_AddMessage($gsFormError, $this->qt_der->FldErrMsg());
		}
		if (!ew_CheckInteger($this->vr_contribuicao->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_contribuicao->FldErrMsg());
		}
		if (!ew_CheckNumber($this->vr_qtPf->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_qtPf->FldErrMsg());
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
				$sThisKey .= $row['nu_funcao'];
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

			// nu_uc
			$this->nu_uc->SetDbValueDef($rsnew, $this->nu_uc->CurrentValue, NULL, $this->nu_uc->ReadOnly);

			// no_funcao
			$this->no_funcao->SetDbValueDef($rsnew, $this->no_funcao->CurrentValue, "", $this->no_funcao->ReadOnly);

			// nu_tpManutencao
			$this->nu_tpManutencao->SetDbValueDef($rsnew, $this->nu_tpManutencao->CurrentValue, NULL, $this->nu_tpManutencao->ReadOnly);

			// nu_tpElemento
			$this->nu_tpElemento->SetDbValueDef($rsnew, $this->nu_tpElemento->CurrentValue, NULL, $this->nu_tpElemento->ReadOnly);

			// qt_alr
			$this->qt_alr->SetDbValueDef($rsnew, $this->qt_alr->CurrentValue, NULL, $this->qt_alr->ReadOnly);

			// qt_der
			$this->qt_der->SetDbValueDef($rsnew, $this->qt_der->CurrentValue, NULL, $this->qt_der->ReadOnly);

			// ic_complexApf
			$this->ic_complexApf->SetDbValueDef($rsnew, $this->ic_complexApf->CurrentValue, NULL, $this->ic_complexApf->ReadOnly);

			// vr_contribuicao
			$this->vr_contribuicao->SetDbValueDef($rsnew, $this->vr_contribuicao->CurrentValue, NULL, $this->vr_contribuicao->ReadOnly);

			// vr_fatorReducao
			$this->vr_fatorReducao->SetDbValueDef($rsnew, $this->vr_fatorReducao->CurrentValue, NULL, $this->vr_fatorReducao->ReadOnly);

			// vr_qtPf
			$this->vr_qtPf->SetDbValueDef($rsnew, $this->vr_qtPf->CurrentValue, NULL, $this->vr_qtPf->ReadOnly);

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
		$rs->Close();
		return $EditRow;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Set up foreign key field value from Session
			if ($this->getCurrentMasterTable() == "contagempf") {
				$this->nu_contagem->CurrentValue = $this->nu_contagem->getSessionValue();
			}

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// nu_agrupador
		$this->nu_agrupador->SetDbValueDef($rsnew, $this->nu_agrupador->CurrentValue, 0, FALSE);

		// nu_uc
		$this->nu_uc->SetDbValueDef($rsnew, $this->nu_uc->CurrentValue, NULL, FALSE);

		// no_funcao
		$this->no_funcao->SetDbValueDef($rsnew, $this->no_funcao->CurrentValue, "", FALSE);

		// nu_tpManutencao
		$this->nu_tpManutencao->SetDbValueDef($rsnew, $this->nu_tpManutencao->CurrentValue, NULL, FALSE);

		// nu_tpElemento
		$this->nu_tpElemento->SetDbValueDef($rsnew, $this->nu_tpElemento->CurrentValue, NULL, FALSE);

		// qt_alr
		$this->qt_alr->SetDbValueDef($rsnew, $this->qt_alr->CurrentValue, NULL, FALSE);

		// qt_der
		$this->qt_der->SetDbValueDef($rsnew, $this->qt_der->CurrentValue, NULL, FALSE);

		// ic_complexApf
		$this->ic_complexApf->SetDbValueDef($rsnew, $this->ic_complexApf->CurrentValue, NULL, FALSE);

		// vr_contribuicao
		$this->vr_contribuicao->SetDbValueDef($rsnew, $this->vr_contribuicao->CurrentValue, NULL, FALSE);

		// vr_fatorReducao
		$this->vr_fatorReducao->SetDbValueDef($rsnew, $this->vr_fatorReducao->CurrentValue, NULL, FALSE);

		// pc_varFasesRoteiro
		$this->pc_varFasesRoteiro->SetDbValueDef($rsnew, $this->pc_varFasesRoteiro->CurrentValue, NULL, FALSE);

		// vr_qtPf
		$this->vr_qtPf->SetDbValueDef($rsnew, $this->vr_qtPf->CurrentValue, NULL, FALSE);

		// nu_contagem
		if ($this->nu_contagem->getSessionValue() <> "") {
			$rsnew['nu_contagem'] = $this->nu_contagem->getSessionValue();
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
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
			$this->nu_funcao->setDbValue($conn->Insert_ID());
			$rsnew['nu_funcao'] = $this->nu_funcao->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {

		// Hide foreign keys
		$sMasterTblVar = $this->getCurrentMasterTable();
		if ($sMasterTblVar == "contagempf") {
			$this->nu_contagem->Visible = FALSE;
			if ($GLOBALS["contagempf"]->EventCancelled) $this->EventCancelled = TRUE;
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); //  Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
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
