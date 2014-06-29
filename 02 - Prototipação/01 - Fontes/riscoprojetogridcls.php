<?php include_once "riscoprojetoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php

//
// Page class
//

$riscoprojeto_grid = NULL; // Initialize page object first

class criscoprojeto_grid extends criscoprojeto {

	// Page ID
	var $PageID = 'grid';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'riscoprojeto';

	// Page object name
	var $PageObjName = 'riscoprojeto_grid';

	// Grid form hidden field names
	var $FormName = 'friscoprojetogrid';
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

		// Table object (riscoprojeto)
		if (!isset($GLOBALS["riscoprojeto"])) {
			$GLOBALS["riscoprojeto"] = &$this;

//			$GLOBALS["MasterTable"] = &$GLOBALS["Table"];
//			if (!isset($GLOBALS["Table"])) $GLOBALS["Table"] = &$GLOBALS["riscoprojeto"];

		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'grid', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'riscoprojeto', TRUE);

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
		$this->nu_riscoProjeto->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->nu_usuarioResp->Visible = !$this->IsAddOrEdit();

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
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "projeto") {
			global $projeto;
			$rsmaster = $projeto->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("projetolist.php"); // Return to master page
			} else {
				$projeto->LoadListRowValues($rsmaster);
				$projeto->RowType = EW_ROWTYPE_MASTER; // Master row
				$projeto->RenderListRow();
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
			$this->nu_riscoProjeto->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->nu_riscoProjeto->FormValue))
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
					$sKey .= $this->nu_riscoProjeto->CurrentValue;

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
		if ($objForm->HasValue("x_nu_projeto") && $objForm->HasValue("o_nu_projeto") && $this->nu_projeto->CurrentValue <> $this->nu_projeto->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_nu_catRisco") && $objForm->HasValue("o_nu_catRisco") && $this->nu_catRisco->CurrentValue <> $this->nu_catRisco->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_ic_tpRisco") && $objForm->HasValue("o_ic_tpRisco") && $this->ic_tpRisco->CurrentValue <> $this->ic_tpRisco->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_nu_probabilidade") && $objForm->HasValue("o_nu_probabilidade") && $this->nu_probabilidade->CurrentValue <> $this->nu_probabilidade->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_nu_impacto") && $objForm->HasValue("o_nu_impacto") && $this->nu_impacto->CurrentValue <> $this->nu_impacto->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_nu_severidade") && $objForm->HasValue("o_nu_severidade") && $this->nu_severidade->CurrentValue <> $this->nu_severidade->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_nu_acao") && $objForm->HasValue("o_nu_acao") && $this->nu_acao->CurrentValue <> $this->nu_acao->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_ic_stRisco") && $objForm->HasValue("o_ic_stRisco") && $this->ic_stRisco->CurrentValue <> $this->ic_stRisco->OldValue)
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
				$this->nu_projeto->setSessionValue("");
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
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->nu_riscoProjeto->CurrentValue . "\">";
		}
		$this->RenderListOptionsExt();
	}

	// Set record key
	function SetRecordKey(&$key, $rs) {
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs->fields('nu_riscoProjeto');
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
		$this->nu_riscoProjeto->CurrentValue = NULL;
		$this->nu_riscoProjeto->OldValue = $this->nu_riscoProjeto->CurrentValue;
		$this->nu_projeto->CurrentValue = NULL;
		$this->nu_projeto->OldValue = $this->nu_projeto->CurrentValue;
		$this->nu_catRisco->CurrentValue = NULL;
		$this->nu_catRisco->OldValue = $this->nu_catRisco->CurrentValue;
		$this->ic_tpRisco->CurrentValue = NULL;
		$this->ic_tpRisco->OldValue = $this->ic_tpRisco->CurrentValue;
		$this->nu_probabilidade->CurrentValue = NULL;
		$this->nu_probabilidade->OldValue = $this->nu_probabilidade->CurrentValue;
		$this->nu_impacto->CurrentValue = NULL;
		$this->nu_impacto->OldValue = $this->nu_impacto->CurrentValue;
		$this->nu_severidade->CurrentValue = NULL;
		$this->nu_severidade->OldValue = $this->nu_severidade->CurrentValue;
		$this->nu_acao->CurrentValue = NULL;
		$this->nu_acao->OldValue = $this->nu_acao->CurrentValue;
		$this->nu_usuarioResp->CurrentValue = NULL;
		$this->nu_usuarioResp->OldValue = $this->nu_usuarioResp->CurrentValue;
		$this->ic_stRisco->CurrentValue = "A";
		$this->ic_stRisco->OldValue = $this->ic_stRisco->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_riscoProjeto->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->nu_riscoProjeto->setFormValue($objForm->GetValue("x_nu_riscoProjeto"));
		if (!$this->nu_projeto->FldIsDetailKey) {
			$this->nu_projeto->setFormValue($objForm->GetValue("x_nu_projeto"));
		}
		$this->nu_projeto->setOldValue($objForm->GetValue("o_nu_projeto"));
		if (!$this->nu_catRisco->FldIsDetailKey) {
			$this->nu_catRisco->setFormValue($objForm->GetValue("x_nu_catRisco"));
		}
		$this->nu_catRisco->setOldValue($objForm->GetValue("o_nu_catRisco"));
		if (!$this->ic_tpRisco->FldIsDetailKey) {
			$this->ic_tpRisco->setFormValue($objForm->GetValue("x_ic_tpRisco"));
		}
		$this->ic_tpRisco->setOldValue($objForm->GetValue("o_ic_tpRisco"));
		if (!$this->nu_probabilidade->FldIsDetailKey) {
			$this->nu_probabilidade->setFormValue($objForm->GetValue("x_nu_probabilidade"));
		}
		$this->nu_probabilidade->setOldValue($objForm->GetValue("o_nu_probabilidade"));
		if (!$this->nu_impacto->FldIsDetailKey) {
			$this->nu_impacto->setFormValue($objForm->GetValue("x_nu_impacto"));
		}
		$this->nu_impacto->setOldValue($objForm->GetValue("o_nu_impacto"));
		if (!$this->nu_severidade->FldIsDetailKey) {
			$this->nu_severidade->setFormValue($objForm->GetValue("x_nu_severidade"));
		}
		$this->nu_severidade->setOldValue($objForm->GetValue("o_nu_severidade"));
		if (!$this->nu_acao->FldIsDetailKey) {
			$this->nu_acao->setFormValue($objForm->GetValue("x_nu_acao"));
		}
		$this->nu_acao->setOldValue($objForm->GetValue("o_nu_acao"));
		if (!$this->nu_usuarioResp->FldIsDetailKey) {
			$this->nu_usuarioResp->setFormValue($objForm->GetValue("x_nu_usuarioResp"));
		}
		$this->nu_usuarioResp->setOldValue($objForm->GetValue("o_nu_usuarioResp"));
		if (!$this->ic_stRisco->FldIsDetailKey) {
			$this->ic_stRisco->setFormValue($objForm->GetValue("x_ic_stRisco"));
		}
		$this->ic_stRisco->setOldValue($objForm->GetValue("o_ic_stRisco"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->nu_riscoProjeto->CurrentValue = $this->nu_riscoProjeto->FormValue;
		$this->nu_projeto->CurrentValue = $this->nu_projeto->FormValue;
		$this->nu_catRisco->CurrentValue = $this->nu_catRisco->FormValue;
		$this->ic_tpRisco->CurrentValue = $this->ic_tpRisco->FormValue;
		$this->nu_probabilidade->CurrentValue = $this->nu_probabilidade->FormValue;
		$this->nu_impacto->CurrentValue = $this->nu_impacto->FormValue;
		$this->nu_severidade->CurrentValue = $this->nu_severidade->FormValue;
		$this->nu_acao->CurrentValue = $this->nu_acao->FormValue;
		$this->nu_usuarioResp->CurrentValue = $this->nu_usuarioResp->FormValue;
		$this->ic_stRisco->CurrentValue = $this->ic_stRisco->FormValue;
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
		$this->nu_riscoProjeto->setDbValue($rs->fields('nu_riscoProjeto'));
		$this->nu_projeto->setDbValue($rs->fields('nu_projeto'));
		$this->nu_catRisco->setDbValue($rs->fields('nu_catRisco'));
		$this->ic_tpRisco->setDbValue($rs->fields('ic_tpRisco'));
		$this->ds_risco->setDbValue($rs->fields('ds_risco'));
		$this->ds_consequencia->setDbValue($rs->fields('ds_consequencia'));
		$this->nu_probabilidade->setDbValue($rs->fields('nu_probabilidade'));
		$this->nu_impacto->setDbValue($rs->fields('nu_impacto'));
		$this->nu_severidade->setDbValue($rs->fields('nu_severidade'));
		$this->nu_acao->setDbValue($rs->fields('nu_acao'));
		$this->ds_gatilho->setDbValue($rs->fields('ds_gatilho'));
		$this->ds_respRisco->setDbValue($rs->fields('ds_respRisco'));
		$this->nu_usuarioResp->setDbValue($rs->fields('nu_usuarioResp'));
		$this->ic_stRisco->setDbValue($rs->fields('ic_stRisco'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_riscoProjeto->DbValue = $row['nu_riscoProjeto'];
		$this->nu_projeto->DbValue = $row['nu_projeto'];
		$this->nu_catRisco->DbValue = $row['nu_catRisco'];
		$this->ic_tpRisco->DbValue = $row['ic_tpRisco'];
		$this->ds_risco->DbValue = $row['ds_risco'];
		$this->ds_consequencia->DbValue = $row['ds_consequencia'];
		$this->nu_probabilidade->DbValue = $row['nu_probabilidade'];
		$this->nu_impacto->DbValue = $row['nu_impacto'];
		$this->nu_severidade->DbValue = $row['nu_severidade'];
		$this->nu_acao->DbValue = $row['nu_acao'];
		$this->ds_gatilho->DbValue = $row['ds_gatilho'];
		$this->ds_respRisco->DbValue = $row['ds_respRisco'];
		$this->nu_usuarioResp->DbValue = $row['nu_usuarioResp'];
		$this->ic_stRisco->DbValue = $row['ic_stRisco'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		$arKeys[] = $this->RowOldKey;
		$cnt = count($arKeys);
		if ($cnt >= 1) {
			if (strval($arKeys[0]) <> "")
				$this->nu_riscoProjeto->CurrentValue = strval($arKeys[0]); // nu_riscoProjeto
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
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_riscoProjeto
		// nu_projeto
		// nu_catRisco
		// ic_tpRisco
		// ds_risco
		// ds_consequencia
		// nu_probabilidade
		// nu_impacto
		// nu_severidade
		// nu_acao
		// ds_gatilho
		// ds_respRisco
		// nu_usuarioResp
		// ic_stRisco

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_riscoProjeto
			$this->nu_riscoProjeto->ViewValue = $this->nu_riscoProjeto->CurrentValue;
			$this->nu_riscoProjeto->ViewCustomAttributes = "";

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

			// nu_catRisco
			if (strval($this->nu_catRisco->CurrentValue) <> "") {
				$sFilterWrk = "[nu_catRisco]" . ew_SearchString("=", $this->nu_catRisco->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_catRisco], [no_catRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[catriscoproj]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_catRisco, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_catRisco] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_catRisco->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_catRisco->ViewValue = $this->nu_catRisco->CurrentValue;
				}
			} else {
				$this->nu_catRisco->ViewValue = NULL;
			}
			$this->nu_catRisco->ViewCustomAttributes = "";

			// ic_tpRisco
			if (strval($this->ic_tpRisco->CurrentValue) <> "") {
				switch ($this->ic_tpRisco->CurrentValue) {
					case $this->ic_tpRisco->FldTagValue(1):
						$this->ic_tpRisco->ViewValue = $this->ic_tpRisco->FldTagCaption(1) <> "" ? $this->ic_tpRisco->FldTagCaption(1) : $this->ic_tpRisco->CurrentValue;
						break;
					case $this->ic_tpRisco->FldTagValue(2):
						$this->ic_tpRisco->ViewValue = $this->ic_tpRisco->FldTagCaption(2) <> "" ? $this->ic_tpRisco->FldTagCaption(2) : $this->ic_tpRisco->CurrentValue;
						break;
					default:
						$this->ic_tpRisco->ViewValue = $this->ic_tpRisco->CurrentValue;
				}
			} else {
				$this->ic_tpRisco->ViewValue = NULL;
			}
			$this->ic_tpRisco->ViewCustomAttributes = "";

			// nu_probabilidade
			if (strval($this->nu_probabilidade->CurrentValue) <> "") {
				$sFilterWrk = "[nu_probOcoRisco]" . ew_SearchString("=", $this->nu_probabilidade->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_probOcoRisco], [no_probOcoRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[probocorisco]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_probabilidade, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_valor] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_probabilidade->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_probabilidade->ViewValue = $this->nu_probabilidade->CurrentValue;
				}
			} else {
				$this->nu_probabilidade->ViewValue = NULL;
			}
			$this->nu_probabilidade->ViewCustomAttributes = "";

			// nu_impacto
			if (strval($this->nu_impacto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_impactoRisco]" . ew_SearchString("=", $this->nu_impacto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_impactoRisco], [no_impactoRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[impactorisco]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_impacto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_valor] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_impacto->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_impacto->ViewValue = $this->nu_impacto->CurrentValue;
				}
			} else {
				$this->nu_impacto->ViewValue = NULL;
			}
			$this->nu_impacto->ViewCustomAttributes = "";

			// nu_severidade
			$this->nu_severidade->ViewValue = $this->nu_severidade->CurrentValue;
			$this->nu_severidade->ViewCustomAttributes = "";

			// nu_acao
			if (strval($this->nu_acao->CurrentValue) <> "") {
				$sFilterWrk = "[nu_acaoRisco]" . ew_SearchString("=", $this->nu_acao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_acaoRisco], [no_acaoRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[acaorisco]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_acao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_acaoRisco] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_acao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_acao->ViewValue = $this->nu_acao->CurrentValue;
				}
			} else {
				$this->nu_acao->ViewValue = NULL;
			}
			$this->nu_acao->ViewCustomAttributes = "";

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

			// ic_stRisco
			if (strval($this->ic_stRisco->CurrentValue) <> "") {
				switch ($this->ic_stRisco->CurrentValue) {
					case $this->ic_stRisco->FldTagValue(1):
						$this->ic_stRisco->ViewValue = $this->ic_stRisco->FldTagCaption(1) <> "" ? $this->ic_stRisco->FldTagCaption(1) : $this->ic_stRisco->CurrentValue;
						break;
					case $this->ic_stRisco->FldTagValue(2):
						$this->ic_stRisco->ViewValue = $this->ic_stRisco->FldTagCaption(2) <> "" ? $this->ic_stRisco->FldTagCaption(2) : $this->ic_stRisco->CurrentValue;
						break;
					default:
						$this->ic_stRisco->ViewValue = $this->ic_stRisco->CurrentValue;
				}
			} else {
				$this->ic_stRisco->ViewValue = NULL;
			}
			$this->ic_stRisco->ViewCustomAttributes = "";

			// nu_riscoProjeto
			$this->nu_riscoProjeto->LinkCustomAttributes = "";
			$this->nu_riscoProjeto->HrefValue = "";
			$this->nu_riscoProjeto->TooltipValue = "";

			// nu_projeto
			$this->nu_projeto->LinkCustomAttributes = "";
			$this->nu_projeto->HrefValue = "";
			$this->nu_projeto->TooltipValue = "";

			// nu_catRisco
			$this->nu_catRisco->LinkCustomAttributes = "";
			$this->nu_catRisco->HrefValue = "";
			$this->nu_catRisco->TooltipValue = "";

			// ic_tpRisco
			$this->ic_tpRisco->LinkCustomAttributes = "";
			$this->ic_tpRisco->HrefValue = "";
			$this->ic_tpRisco->TooltipValue = "";

			// nu_probabilidade
			$this->nu_probabilidade->LinkCustomAttributes = "";
			$this->nu_probabilidade->HrefValue = "";
			$this->nu_probabilidade->TooltipValue = "";

			// nu_impacto
			$this->nu_impacto->LinkCustomAttributes = "";
			$this->nu_impacto->HrefValue = "";
			$this->nu_impacto->TooltipValue = "";

			// nu_severidade
			$this->nu_severidade->LinkCustomAttributes = "";
			$this->nu_severidade->HrefValue = "";
			$this->nu_severidade->TooltipValue = "";

			// nu_acao
			$this->nu_acao->LinkCustomAttributes = "";
			$this->nu_acao->HrefValue = "";
			$this->nu_acao->TooltipValue = "";

			// nu_usuarioResp
			$this->nu_usuarioResp->LinkCustomAttributes = "";
			$this->nu_usuarioResp->HrefValue = "";
			$this->nu_usuarioResp->TooltipValue = "";

			// ic_stRisco
			$this->ic_stRisco->LinkCustomAttributes = "";
			$this->ic_stRisco->HrefValue = "";
			$this->ic_stRisco->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nu_riscoProjeto
			// nu_projeto

			$this->nu_projeto->EditCustomAttributes = "";
			if ($this->nu_projeto->getSessionValue() <> "") {
				$this->nu_projeto->CurrentValue = $this->nu_projeto->getSessionValue();
				$this->nu_projeto->OldValue = $this->nu_projeto->CurrentValue;
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
			} else {
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_projeto], [no_projeto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[projeto]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_projeto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_projeto] DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_projeto->EditValue = $arwrk;
			}

			// nu_catRisco
			$this->nu_catRisco->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_catRisco], [no_catRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[catriscoproj]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_catRisco, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_catRisco] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_catRisco->EditValue = $arwrk;

			// ic_tpRisco
			$this->ic_tpRisco->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_tpRisco->FldTagValue(1), $this->ic_tpRisco->FldTagCaption(1) <> "" ? $this->ic_tpRisco->FldTagCaption(1) : $this->ic_tpRisco->FldTagValue(1));
			$arwrk[] = array($this->ic_tpRisco->FldTagValue(2), $this->ic_tpRisco->FldTagCaption(2) <> "" ? $this->ic_tpRisco->FldTagCaption(2) : $this->ic_tpRisco->FldTagValue(2));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_tpRisco->EditValue = $arwrk;

			// nu_probabilidade
			$this->nu_probabilidade->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_probOcoRisco], [no_probOcoRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[probocorisco]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_probabilidade, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_valor] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_probabilidade->EditValue = $arwrk;

			// nu_impacto
			$this->nu_impacto->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_impactoRisco], [no_impactoRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[impactorisco]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_impacto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_valor] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_impacto->EditValue = $arwrk;

			// nu_severidade
			$this->nu_severidade->EditCustomAttributes = "";
			$this->nu_severidade->EditValue = ew_HtmlEncode($this->nu_severidade->CurrentValue);
			$this->nu_severidade->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_severidade->FldCaption()));

			// nu_acao
			$this->nu_acao->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_acaoRisco], [no_acaoRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [ic_tpRisco] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[acaorisco]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_acao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_acaoRisco] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_acao->EditValue = $arwrk;

			// nu_usuarioResp
			// ic_stRisco

			$this->ic_stRisco->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_stRisco->FldTagValue(1), $this->ic_stRisco->FldTagCaption(1) <> "" ? $this->ic_stRisco->FldTagCaption(1) : $this->ic_stRisco->FldTagValue(1));
			$arwrk[] = array($this->ic_stRisco->FldTagValue(2), $this->ic_stRisco->FldTagCaption(2) <> "" ? $this->ic_stRisco->FldTagCaption(2) : $this->ic_stRisco->FldTagValue(2));
			$this->ic_stRisco->EditValue = $arwrk;

			// Edit refer script
			// nu_riscoProjeto

			$this->nu_riscoProjeto->HrefValue = "";

			// nu_projeto
			$this->nu_projeto->HrefValue = "";

			// nu_catRisco
			$this->nu_catRisco->HrefValue = "";

			// ic_tpRisco
			$this->ic_tpRisco->HrefValue = "";

			// nu_probabilidade
			$this->nu_probabilidade->HrefValue = "";

			// nu_impacto
			$this->nu_impacto->HrefValue = "";

			// nu_severidade
			$this->nu_severidade->HrefValue = "";

			// nu_acao
			$this->nu_acao->HrefValue = "";

			// nu_usuarioResp
			$this->nu_usuarioResp->HrefValue = "";

			// ic_stRisco
			$this->ic_stRisco->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// nu_riscoProjeto
			$this->nu_riscoProjeto->EditCustomAttributes = "";
			$this->nu_riscoProjeto->EditValue = $this->nu_riscoProjeto->CurrentValue;
			$this->nu_riscoProjeto->ViewCustomAttributes = "";

			// nu_projeto
			$this->nu_projeto->EditCustomAttributes = "";
			if ($this->nu_projeto->getSessionValue() <> "") {
				$this->nu_projeto->CurrentValue = $this->nu_projeto->getSessionValue();
				$this->nu_projeto->OldValue = $this->nu_projeto->CurrentValue;
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
			} else {
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_projeto], [no_projeto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[projeto]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_projeto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_projeto] DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_projeto->EditValue = $arwrk;
			}

			// nu_catRisco
			$this->nu_catRisco->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_catRisco], [no_catRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[catriscoproj]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_catRisco, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_catRisco] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_catRisco->EditValue = $arwrk;

			// ic_tpRisco
			$this->ic_tpRisco->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_tpRisco->FldTagValue(1), $this->ic_tpRisco->FldTagCaption(1) <> "" ? $this->ic_tpRisco->FldTagCaption(1) : $this->ic_tpRisco->FldTagValue(1));
			$arwrk[] = array($this->ic_tpRisco->FldTagValue(2), $this->ic_tpRisco->FldTagCaption(2) <> "" ? $this->ic_tpRisco->FldTagCaption(2) : $this->ic_tpRisco->FldTagValue(2));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_tpRisco->EditValue = $arwrk;

			// nu_probabilidade
			$this->nu_probabilidade->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_probOcoRisco], [no_probOcoRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[probocorisco]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_probabilidade, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_valor] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_probabilidade->EditValue = $arwrk;

			// nu_impacto
			$this->nu_impacto->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_impactoRisco], [no_impactoRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[impactorisco]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_impacto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_valor] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_impacto->EditValue = $arwrk;

			// nu_severidade
			$this->nu_severidade->EditCustomAttributes = "";
			$this->nu_severidade->EditValue = ew_HtmlEncode($this->nu_severidade->CurrentValue);
			$this->nu_severidade->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_severidade->FldCaption()));

			// nu_acao
			$this->nu_acao->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_acaoRisco], [no_acaoRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [ic_tpRisco] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[acaorisco]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_acao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_acaoRisco] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_acao->EditValue = $arwrk;

			// nu_usuarioResp
			// ic_stRisco

			$this->ic_stRisco->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_stRisco->FldTagValue(1), $this->ic_stRisco->FldTagCaption(1) <> "" ? $this->ic_stRisco->FldTagCaption(1) : $this->ic_stRisco->FldTagValue(1));
			$arwrk[] = array($this->ic_stRisco->FldTagValue(2), $this->ic_stRisco->FldTagCaption(2) <> "" ? $this->ic_stRisco->FldTagCaption(2) : $this->ic_stRisco->FldTagValue(2));
			$this->ic_stRisco->EditValue = $arwrk;

			// Edit refer script
			// nu_riscoProjeto

			$this->nu_riscoProjeto->HrefValue = "";

			// nu_projeto
			$this->nu_projeto->HrefValue = "";

			// nu_catRisco
			$this->nu_catRisco->HrefValue = "";

			// ic_tpRisco
			$this->ic_tpRisco->HrefValue = "";

			// nu_probabilidade
			$this->nu_probabilidade->HrefValue = "";

			// nu_impacto
			$this->nu_impacto->HrefValue = "";

			// nu_severidade
			$this->nu_severidade->HrefValue = "";

			// nu_acao
			$this->nu_acao->HrefValue = "";

			// nu_usuarioResp
			$this->nu_usuarioResp->HrefValue = "";

			// ic_stRisco
			$this->ic_stRisco->HrefValue = "";
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
		if (!$this->nu_projeto->FldIsDetailKey && !is_null($this->nu_projeto->FormValue) && $this->nu_projeto->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_projeto->FldCaption());
		}
		if (!$this->nu_catRisco->FldIsDetailKey && !is_null($this->nu_catRisco->FormValue) && $this->nu_catRisco->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_catRisco->FldCaption());
		}
		if (!$this->ic_tpRisco->FldIsDetailKey && !is_null($this->ic_tpRisco->FormValue) && $this->ic_tpRisco->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_tpRisco->FldCaption());
		}
		if (!$this->nu_probabilidade->FldIsDetailKey && !is_null($this->nu_probabilidade->FormValue) && $this->nu_probabilidade->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_probabilidade->FldCaption());
		}
		if (!ew_CheckInteger($this->nu_severidade->FormValue)) {
			ew_AddMessage($gsFormError, $this->nu_severidade->FldErrMsg());
		}
		if (!$this->nu_acao->FldIsDetailKey && !is_null($this->nu_acao->FormValue) && $this->nu_acao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_acao->FldCaption());
		}
		if ($this->ic_stRisco->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_stRisco->FldCaption());
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
				$sThisKey .= $row['nu_riscoProjeto'];
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

			// nu_projeto
			$this->nu_projeto->SetDbValueDef($rsnew, $this->nu_projeto->CurrentValue, 0, $this->nu_projeto->ReadOnly);

			// nu_catRisco
			$this->nu_catRisco->SetDbValueDef($rsnew, $this->nu_catRisco->CurrentValue, 0, $this->nu_catRisco->ReadOnly);

			// ic_tpRisco
			$this->ic_tpRisco->SetDbValueDef($rsnew, $this->ic_tpRisco->CurrentValue, "", $this->ic_tpRisco->ReadOnly);

			// nu_probabilidade
			$this->nu_probabilidade->SetDbValueDef($rsnew, $this->nu_probabilidade->CurrentValue, NULL, $this->nu_probabilidade->ReadOnly);

			// nu_impacto
			$this->nu_impacto->SetDbValueDef($rsnew, $this->nu_impacto->CurrentValue, NULL, $this->nu_impacto->ReadOnly);

			// nu_severidade
			$this->nu_severidade->SetDbValueDef($rsnew, $this->nu_severidade->CurrentValue, NULL, $this->nu_severidade->ReadOnly);

			// nu_acao
			$this->nu_acao->SetDbValueDef($rsnew, $this->nu_acao->CurrentValue, NULL, $this->nu_acao->ReadOnly);

			// nu_usuarioResp
			$this->nu_usuarioResp->SetDbValueDef($rsnew, CurrentUserID(), NULL);
			$rsnew['nu_usuarioResp'] = &$this->nu_usuarioResp->DbValue;

			// ic_stRisco
			$this->ic_stRisco->SetDbValueDef($rsnew, $this->ic_stRisco->CurrentValue, NULL, $this->ic_stRisco->ReadOnly);

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
			if ($this->getCurrentMasterTable() == "projeto") {
				$this->nu_projeto->CurrentValue = $this->nu_projeto->getSessionValue();
			}

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// nu_projeto
		$this->nu_projeto->SetDbValueDef($rsnew, $this->nu_projeto->CurrentValue, 0, FALSE);

		// nu_catRisco
		$this->nu_catRisco->SetDbValueDef($rsnew, $this->nu_catRisco->CurrentValue, 0, FALSE);

		// ic_tpRisco
		$this->ic_tpRisco->SetDbValueDef($rsnew, $this->ic_tpRisco->CurrentValue, "", FALSE);

		// nu_probabilidade
		$this->nu_probabilidade->SetDbValueDef($rsnew, $this->nu_probabilidade->CurrentValue, NULL, FALSE);

		// nu_impacto
		$this->nu_impacto->SetDbValueDef($rsnew, $this->nu_impacto->CurrentValue, NULL, FALSE);

		// nu_severidade
		$this->nu_severidade->SetDbValueDef($rsnew, $this->nu_severidade->CurrentValue, NULL, FALSE);

		// nu_acao
		$this->nu_acao->SetDbValueDef($rsnew, $this->nu_acao->CurrentValue, NULL, FALSE);

		// nu_usuarioResp
		$this->nu_usuarioResp->SetDbValueDef($rsnew, CurrentUserID(), NULL);
		$rsnew['nu_usuarioResp'] = &$this->nu_usuarioResp->DbValue;

		// ic_stRisco
		$this->ic_stRisco->SetDbValueDef($rsnew, $this->ic_stRisco->CurrentValue, NULL, FALSE);

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
			$this->nu_riscoProjeto->setDbValue($conn->Insert_ID());
			$rsnew['nu_riscoProjeto'] = $this->nu_riscoProjeto->DbValue;
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
		if ($sMasterTblVar == "projeto") {
			$this->nu_projeto->Visible = FALSE;
			if ($GLOBALS["projeto"]->EventCancelled) $this->EventCancelled = TRUE;
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
