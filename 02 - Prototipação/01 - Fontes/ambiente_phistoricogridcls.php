<?php include_once "ambiente_phistoricoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php

//
// Page class
//

$ambiente_phistorico_grid = NULL; // Initialize page object first

class cambiente_phistorico_grid extends cambiente_phistorico {

	// Page ID
	var $PageID = 'grid';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'ambiente_phistorico';

	// Page object name
	var $PageObjName = 'ambiente_phistorico_grid';

	// Grid form hidden field names
	var $FormName = 'fambiente_phistoricogrid';
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

		// Table object (ambiente_phistorico)
		if (!isset($GLOBALS["ambiente_phistorico"])) {
			$GLOBALS["ambiente_phistorico"] = &$this;

//			$GLOBALS["MasterTable"] = &$GLOBALS["Table"];
//			if (!isset($GLOBALS["Table"])) $GLOBALS["Table"] = &$GLOBALS["ambiente_phistorico"];

		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'grid', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'ambiente_phistorico', TRUE);

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
		$this->nu_projhist->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "ambiente") {
			global $ambiente;
			$rsmaster = $ambiente->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("ambientelist.php"); // Return to master page
			} else {
				$ambiente->LoadListRowValues($rsmaster);
				$ambiente->RowType = EW_ROWTYPE_MASTER; // Master row
				$ambiente->RenderListRow();
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
			$this->nu_projhist->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->nu_projhist->FormValue))
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
					$sKey .= $this->nu_projhist->CurrentValue;

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
		if ($objForm->HasValue("x_no_projeto") && $objForm->HasValue("o_no_projeto") && $this->no_projeto->CurrentValue <> $this->no_projeto->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_qt_pf") && $objForm->HasValue("o_qt_pf") && $this->qt_pf->CurrentValue <> $this->qt_pf->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_qt_sloc") && $objForm->HasValue("o_qt_sloc") && $this->qt_sloc->CurrentValue <> $this->qt_sloc->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_qt_slocPf") && $objForm->HasValue("o_qt_slocPf") && $this->qt_slocPf->CurrentValue <> $this->qt_slocPf->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_qt_esforcoReal") && $objForm->HasValue("o_qt_esforcoReal") && $this->qt_esforcoReal->CurrentValue <> $this->qt_esforcoReal->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_qt_esforcoRealPm") && $objForm->HasValue("o_qt_esforcoRealPm") && $this->qt_esforcoRealPm->CurrentValue <> $this->qt_esforcoRealPm->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_qt_prazoRealM") && $objForm->HasValue("o_qt_prazoRealM") && $this->qt_prazoRealM->CurrentValue <> $this->qt_prazoRealM->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_ic_situacao") && $objForm->HasValue("o_ic_situacao") && $this->ic_situacao->CurrentValue <> $this->ic_situacao->OldValue)
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
				$this->nu_ambiente->setSessionValue("");
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
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->nu_projhist->CurrentValue . "\">";
		}
		$this->RenderListOptionsExt();
	}

	// Set record key
	function SetRecordKey(&$key, $rs) {
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs->fields('nu_projhist');
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
		$this->nu_projhist->CurrentValue = NULL;
		$this->nu_projhist->OldValue = $this->nu_projhist->CurrentValue;
		$this->no_projeto->CurrentValue = NULL;
		$this->no_projeto->OldValue = $this->no_projeto->CurrentValue;
		$this->qt_pf->CurrentValue = NULL;
		$this->qt_pf->OldValue = $this->qt_pf->CurrentValue;
		$this->qt_sloc->CurrentValue = NULL;
		$this->qt_sloc->OldValue = $this->qt_sloc->CurrentValue;
		$this->qt_slocPf->CurrentValue = NULL;
		$this->qt_slocPf->OldValue = $this->qt_slocPf->CurrentValue;
		$this->qt_esforcoReal->CurrentValue = NULL;
		$this->qt_esforcoReal->OldValue = $this->qt_esforcoReal->CurrentValue;
		$this->qt_esforcoRealPm->CurrentValue = NULL;
		$this->qt_esforcoRealPm->OldValue = $this->qt_esforcoRealPm->CurrentValue;
		$this->qt_prazoRealM->CurrentValue = NULL;
		$this->qt_prazoRealM->OldValue = $this->qt_prazoRealM->CurrentValue;
		$this->ic_situacao->CurrentValue = "C";
		$this->ic_situacao->OldValue = $this->ic_situacao->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_projhist->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->nu_projhist->setFormValue($objForm->GetValue("x_nu_projhist"));
		if (!$this->no_projeto->FldIsDetailKey) {
			$this->no_projeto->setFormValue($objForm->GetValue("x_no_projeto"));
		}
		$this->no_projeto->setOldValue($objForm->GetValue("o_no_projeto"));
		if (!$this->qt_pf->FldIsDetailKey) {
			$this->qt_pf->setFormValue($objForm->GetValue("x_qt_pf"));
		}
		$this->qt_pf->setOldValue($objForm->GetValue("o_qt_pf"));
		if (!$this->qt_sloc->FldIsDetailKey) {
			$this->qt_sloc->setFormValue($objForm->GetValue("x_qt_sloc"));
		}
		$this->qt_sloc->setOldValue($objForm->GetValue("o_qt_sloc"));
		if (!$this->qt_slocPf->FldIsDetailKey) {
			$this->qt_slocPf->setFormValue($objForm->GetValue("x_qt_slocPf"));
		}
		$this->qt_slocPf->setOldValue($objForm->GetValue("o_qt_slocPf"));
		if (!$this->qt_esforcoReal->FldIsDetailKey) {
			$this->qt_esforcoReal->setFormValue($objForm->GetValue("x_qt_esforcoReal"));
		}
		$this->qt_esforcoReal->setOldValue($objForm->GetValue("o_qt_esforcoReal"));
		if (!$this->qt_esforcoRealPm->FldIsDetailKey) {
			$this->qt_esforcoRealPm->setFormValue($objForm->GetValue("x_qt_esforcoRealPm"));
		}
		$this->qt_esforcoRealPm->setOldValue($objForm->GetValue("o_qt_esforcoRealPm"));
		if (!$this->qt_prazoRealM->FldIsDetailKey) {
			$this->qt_prazoRealM->setFormValue($objForm->GetValue("x_qt_prazoRealM"));
		}
		$this->qt_prazoRealM->setOldValue($objForm->GetValue("o_qt_prazoRealM"));
		if (!$this->ic_situacao->FldIsDetailKey) {
			$this->ic_situacao->setFormValue($objForm->GetValue("x_ic_situacao"));
		}
		$this->ic_situacao->setOldValue($objForm->GetValue("o_ic_situacao"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->nu_projhist->CurrentValue = $this->nu_projhist->FormValue;
		$this->no_projeto->CurrentValue = $this->no_projeto->FormValue;
		$this->qt_pf->CurrentValue = $this->qt_pf->FormValue;
		$this->qt_sloc->CurrentValue = $this->qt_sloc->FormValue;
		$this->qt_slocPf->CurrentValue = $this->qt_slocPf->FormValue;
		$this->qt_esforcoReal->CurrentValue = $this->qt_esforcoReal->FormValue;
		$this->qt_esforcoRealPm->CurrentValue = $this->qt_esforcoRealPm->FormValue;
		$this->qt_prazoRealM->CurrentValue = $this->qt_prazoRealM->FormValue;
		$this->ic_situacao->CurrentValue = $this->ic_situacao->FormValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		$arKeys[] = $this->RowOldKey;
		$cnt = count($arKeys);
		if ($cnt >= 1) {
			if (strval($arKeys[0]) <> "")
				$this->nu_projhist->CurrentValue = strval($arKeys[0]); // nu_projhist
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nu_projhist
			// no_projeto

			$this->no_projeto->EditCustomAttributes = "";
			$this->no_projeto->EditValue = ew_HtmlEncode($this->no_projeto->CurrentValue);
			$this->no_projeto->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_projeto->FldCaption()));

			// qt_pf
			$this->qt_pf->EditCustomAttributes = "";
			$this->qt_pf->EditValue = ew_HtmlEncode($this->qt_pf->CurrentValue);
			$this->qt_pf->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_pf->FldCaption()));
			if (strval($this->qt_pf->EditValue) <> "" && is_numeric($this->qt_pf->EditValue)) {
			$this->qt_pf->EditValue = ew_FormatNumber($this->qt_pf->EditValue, -2, -1, -2, 0);
			$this->qt_pf->OldValue = $this->qt_pf->EditValue;
			}

			// qt_sloc
			$this->qt_sloc->EditCustomAttributes = "";
			$this->qt_sloc->EditValue = ew_HtmlEncode($this->qt_sloc->CurrentValue);
			$this->qt_sloc->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_sloc->FldCaption()));
			if (strval($this->qt_sloc->EditValue) <> "" && is_numeric($this->qt_sloc->EditValue)) {
			$this->qt_sloc->EditValue = ew_FormatNumber($this->qt_sloc->EditValue, -2, -1, -2, 0);
			$this->qt_sloc->OldValue = $this->qt_sloc->EditValue;
			}

			// qt_slocPf
			$this->qt_slocPf->EditCustomAttributes = "";
			$this->qt_slocPf->EditValue = ew_HtmlEncode($this->qt_slocPf->CurrentValue);
			$this->qt_slocPf->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_slocPf->FldCaption()));
			if (strval($this->qt_slocPf->EditValue) <> "" && is_numeric($this->qt_slocPf->EditValue)) {
			$this->qt_slocPf->EditValue = ew_FormatNumber($this->qt_slocPf->EditValue, -2, -1, -2, 0);
			$this->qt_slocPf->OldValue = $this->qt_slocPf->EditValue;
			}

			// qt_esforcoReal
			$this->qt_esforcoReal->EditCustomAttributes = "";
			$this->qt_esforcoReal->EditValue = ew_HtmlEncode($this->qt_esforcoReal->CurrentValue);
			$this->qt_esforcoReal->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_esforcoReal->FldCaption()));
			if (strval($this->qt_esforcoReal->EditValue) <> "" && is_numeric($this->qt_esforcoReal->EditValue)) {
			$this->qt_esforcoReal->EditValue = ew_FormatNumber($this->qt_esforcoReal->EditValue, -2, -1, -2, 0);
			$this->qt_esforcoReal->OldValue = $this->qt_esforcoReal->EditValue;
			}

			// qt_esforcoRealPm
			$this->qt_esforcoRealPm->EditCustomAttributes = "";
			$this->qt_esforcoRealPm->EditValue = ew_HtmlEncode($this->qt_esforcoRealPm->CurrentValue);
			$this->qt_esforcoRealPm->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_esforcoRealPm->FldCaption()));
			if (strval($this->qt_esforcoRealPm->EditValue) <> "" && is_numeric($this->qt_esforcoRealPm->EditValue)) {
			$this->qt_esforcoRealPm->EditValue = ew_FormatNumber($this->qt_esforcoRealPm->EditValue, -2, -1, -2, 0);
			$this->qt_esforcoRealPm->OldValue = $this->qt_esforcoRealPm->EditValue;
			}

			// qt_prazoRealM
			$this->qt_prazoRealM->EditCustomAttributes = "";
			$this->qt_prazoRealM->EditValue = ew_HtmlEncode($this->qt_prazoRealM->CurrentValue);
			$this->qt_prazoRealM->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_prazoRealM->FldCaption()));
			if (strval($this->qt_prazoRealM->EditValue) <> "" && is_numeric($this->qt_prazoRealM->EditValue)) {
			$this->qt_prazoRealM->EditValue = ew_FormatNumber($this->qt_prazoRealM->EditValue, -2, -1, -2, 0);
			$this->qt_prazoRealM->OldValue = $this->qt_prazoRealM->EditValue;
			}

			// ic_situacao
			$this->ic_situacao->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_situacao->FldTagValue(1), $this->ic_situacao->FldTagCaption(1) <> "" ? $this->ic_situacao->FldTagCaption(1) : $this->ic_situacao->FldTagValue(1));
			$arwrk[] = array($this->ic_situacao->FldTagValue(2), $this->ic_situacao->FldTagCaption(2) <> "" ? $this->ic_situacao->FldTagCaption(2) : $this->ic_situacao->FldTagValue(2));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_situacao->EditValue = $arwrk;

			// Edit refer script
			// nu_projhist

			$this->nu_projhist->HrefValue = "";

			// no_projeto
			$this->no_projeto->HrefValue = "";

			// qt_pf
			$this->qt_pf->HrefValue = "";

			// qt_sloc
			$this->qt_sloc->HrefValue = "";

			// qt_slocPf
			$this->qt_slocPf->HrefValue = "";

			// qt_esforcoReal
			$this->qt_esforcoReal->HrefValue = "";

			// qt_esforcoRealPm
			$this->qt_esforcoRealPm->HrefValue = "";

			// qt_prazoRealM
			$this->qt_prazoRealM->HrefValue = "";

			// ic_situacao
			$this->ic_situacao->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// nu_projhist
			$this->nu_projhist->EditCustomAttributes = "";
			$this->nu_projhist->EditValue = $this->nu_projhist->CurrentValue;
			$this->nu_projhist->ViewCustomAttributes = "";

			// no_projeto
			$this->no_projeto->EditCustomAttributes = "";
			$this->no_projeto->EditValue = ew_HtmlEncode($this->no_projeto->CurrentValue);
			$this->no_projeto->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_projeto->FldCaption()));

			// qt_pf
			$this->qt_pf->EditCustomAttributes = "";
			$this->qt_pf->EditValue = ew_HtmlEncode($this->qt_pf->CurrentValue);
			$this->qt_pf->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_pf->FldCaption()));
			if (strval($this->qt_pf->EditValue) <> "" && is_numeric($this->qt_pf->EditValue)) {
			$this->qt_pf->EditValue = ew_FormatNumber($this->qt_pf->EditValue, -2, -1, -2, 0);
			$this->qt_pf->OldValue = $this->qt_pf->EditValue;
			}

			// qt_sloc
			$this->qt_sloc->EditCustomAttributes = "";
			$this->qt_sloc->EditValue = ew_HtmlEncode($this->qt_sloc->CurrentValue);
			$this->qt_sloc->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_sloc->FldCaption()));
			if (strval($this->qt_sloc->EditValue) <> "" && is_numeric($this->qt_sloc->EditValue)) {
			$this->qt_sloc->EditValue = ew_FormatNumber($this->qt_sloc->EditValue, -2, -1, -2, 0);
			$this->qt_sloc->OldValue = $this->qt_sloc->EditValue;
			}

			// qt_slocPf
			$this->qt_slocPf->EditCustomAttributes = "";
			$this->qt_slocPf->EditValue = ew_HtmlEncode($this->qt_slocPf->CurrentValue);
			$this->qt_slocPf->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_slocPf->FldCaption()));
			if (strval($this->qt_slocPf->EditValue) <> "" && is_numeric($this->qt_slocPf->EditValue)) {
			$this->qt_slocPf->EditValue = ew_FormatNumber($this->qt_slocPf->EditValue, -2, -1, -2, 0);
			$this->qt_slocPf->OldValue = $this->qt_slocPf->EditValue;
			}

			// qt_esforcoReal
			$this->qt_esforcoReal->EditCustomAttributes = "";
			$this->qt_esforcoReal->EditValue = ew_HtmlEncode($this->qt_esforcoReal->CurrentValue);
			$this->qt_esforcoReal->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_esforcoReal->FldCaption()));
			if (strval($this->qt_esforcoReal->EditValue) <> "" && is_numeric($this->qt_esforcoReal->EditValue)) {
			$this->qt_esforcoReal->EditValue = ew_FormatNumber($this->qt_esforcoReal->EditValue, -2, -1, -2, 0);
			$this->qt_esforcoReal->OldValue = $this->qt_esforcoReal->EditValue;
			}

			// qt_esforcoRealPm
			$this->qt_esforcoRealPm->EditCustomAttributes = "";
			$this->qt_esforcoRealPm->EditValue = ew_HtmlEncode($this->qt_esforcoRealPm->CurrentValue);
			$this->qt_esforcoRealPm->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_esforcoRealPm->FldCaption()));
			if (strval($this->qt_esforcoRealPm->EditValue) <> "" && is_numeric($this->qt_esforcoRealPm->EditValue)) {
			$this->qt_esforcoRealPm->EditValue = ew_FormatNumber($this->qt_esforcoRealPm->EditValue, -2, -1, -2, 0);
			$this->qt_esforcoRealPm->OldValue = $this->qt_esforcoRealPm->EditValue;
			}

			// qt_prazoRealM
			$this->qt_prazoRealM->EditCustomAttributes = "";
			$this->qt_prazoRealM->EditValue = ew_HtmlEncode($this->qt_prazoRealM->CurrentValue);
			$this->qt_prazoRealM->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_prazoRealM->FldCaption()));
			if (strval($this->qt_prazoRealM->EditValue) <> "" && is_numeric($this->qt_prazoRealM->EditValue)) {
			$this->qt_prazoRealM->EditValue = ew_FormatNumber($this->qt_prazoRealM->EditValue, -2, -1, -2, 0);
			$this->qt_prazoRealM->OldValue = $this->qt_prazoRealM->EditValue;
			}

			// ic_situacao
			$this->ic_situacao->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_situacao->FldTagValue(1), $this->ic_situacao->FldTagCaption(1) <> "" ? $this->ic_situacao->FldTagCaption(1) : $this->ic_situacao->FldTagValue(1));
			$arwrk[] = array($this->ic_situacao->FldTagValue(2), $this->ic_situacao->FldTagCaption(2) <> "" ? $this->ic_situacao->FldTagCaption(2) : $this->ic_situacao->FldTagValue(2));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_situacao->EditValue = $arwrk;

			// Edit refer script
			// nu_projhist

			$this->nu_projhist->HrefValue = "";

			// no_projeto
			$this->no_projeto->HrefValue = "";

			// qt_pf
			$this->qt_pf->HrefValue = "";

			// qt_sloc
			$this->qt_sloc->HrefValue = "";

			// qt_slocPf
			$this->qt_slocPf->HrefValue = "";

			// qt_esforcoReal
			$this->qt_esforcoReal->HrefValue = "";

			// qt_esforcoRealPm
			$this->qt_esforcoRealPm->HrefValue = "";

			// qt_prazoRealM
			$this->qt_prazoRealM->HrefValue = "";

			// ic_situacao
			$this->ic_situacao->HrefValue = "";
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
		if (!ew_CheckNumber($this->qt_pf->FormValue)) {
			ew_AddMessage($gsFormError, $this->qt_pf->FldErrMsg());
		}
		if (!ew_CheckNumber($this->qt_sloc->FormValue)) {
			ew_AddMessage($gsFormError, $this->qt_sloc->FldErrMsg());
		}
		if (!ew_CheckNumber($this->qt_slocPf->FormValue)) {
			ew_AddMessage($gsFormError, $this->qt_slocPf->FldErrMsg());
		}
		if (!ew_CheckNumber($this->qt_esforcoReal->FormValue)) {
			ew_AddMessage($gsFormError, $this->qt_esforcoReal->FldErrMsg());
		}
		if (!ew_CheckNumber($this->qt_esforcoRealPm->FormValue)) {
			ew_AddMessage($gsFormError, $this->qt_esforcoRealPm->FldErrMsg());
		}
		if (!ew_CheckNumber($this->qt_prazoRealM->FormValue)) {
			ew_AddMessage($gsFormError, $this->qt_prazoRealM->FldErrMsg());
		}
		if (!$this->ic_situacao->FldIsDetailKey && !is_null($this->ic_situacao->FormValue) && $this->ic_situacao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_situacao->FldCaption());
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

			// no_projeto
			$this->no_projeto->SetDbValueDef($rsnew, $this->no_projeto->CurrentValue, NULL, $this->no_projeto->ReadOnly);

			// qt_pf
			$this->qt_pf->SetDbValueDef($rsnew, $this->qt_pf->CurrentValue, NULL, $this->qt_pf->ReadOnly);

			// qt_sloc
			$this->qt_sloc->SetDbValueDef($rsnew, $this->qt_sloc->CurrentValue, NULL, $this->qt_sloc->ReadOnly);

			// qt_slocPf
			$this->qt_slocPf->SetDbValueDef($rsnew, $this->qt_slocPf->CurrentValue, NULL, $this->qt_slocPf->ReadOnly);

			// qt_esforcoReal
			$this->qt_esforcoReal->SetDbValueDef($rsnew, $this->qt_esforcoReal->CurrentValue, NULL, $this->qt_esforcoReal->ReadOnly);

			// qt_esforcoRealPm
			$this->qt_esforcoRealPm->SetDbValueDef($rsnew, $this->qt_esforcoRealPm->CurrentValue, NULL, $this->qt_esforcoRealPm->ReadOnly);

			// qt_prazoRealM
			$this->qt_prazoRealM->SetDbValueDef($rsnew, $this->qt_prazoRealM->CurrentValue, NULL, $this->qt_prazoRealM->ReadOnly);

			// ic_situacao
			$this->ic_situacao->SetDbValueDef($rsnew, $this->ic_situacao->CurrentValue, NULL, $this->ic_situacao->ReadOnly);

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
			if ($this->getCurrentMasterTable() == "ambiente") {
				$this->nu_ambiente->CurrentValue = $this->nu_ambiente->getSessionValue();
			}

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// no_projeto
		$this->no_projeto->SetDbValueDef($rsnew, $this->no_projeto->CurrentValue, NULL, FALSE);

		// qt_pf
		$this->qt_pf->SetDbValueDef($rsnew, $this->qt_pf->CurrentValue, NULL, FALSE);

		// qt_sloc
		$this->qt_sloc->SetDbValueDef($rsnew, $this->qt_sloc->CurrentValue, NULL, FALSE);

		// qt_slocPf
		$this->qt_slocPf->SetDbValueDef($rsnew, $this->qt_slocPf->CurrentValue, NULL, FALSE);

		// qt_esforcoReal
		$this->qt_esforcoReal->SetDbValueDef($rsnew, $this->qt_esforcoReal->CurrentValue, NULL, FALSE);

		// qt_esforcoRealPm
		$this->qt_esforcoRealPm->SetDbValueDef($rsnew, $this->qt_esforcoRealPm->CurrentValue, NULL, FALSE);

		// qt_prazoRealM
		$this->qt_prazoRealM->SetDbValueDef($rsnew, $this->qt_prazoRealM->CurrentValue, NULL, FALSE);

		// ic_situacao
		$this->ic_situacao->SetDbValueDef($rsnew, $this->ic_situacao->CurrentValue, NULL, FALSE);

		// nu_ambiente
		if ($this->nu_ambiente->getSessionValue() <> "") {
			$rsnew['nu_ambiente'] = $this->nu_ambiente->getSessionValue();
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
			$this->nu_projhist->setDbValue($conn->Insert_ID());
			$rsnew['nu_projhist'] = $this->nu_projhist->DbValue;
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
		if ($sMasterTblVar == "ambiente") {
			$this->nu_ambiente->Visible = FALSE;
			if ($GLOBALS["ambiente"]->EventCancelled) $this->EventCancelled = TRUE;
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
