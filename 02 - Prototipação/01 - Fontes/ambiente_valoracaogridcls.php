<?php include_once "ambiente_valoracaoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php

//
// Page class
//

$ambiente_valoracao_grid = NULL; // Initialize page object first

class cambiente_valoracao_grid extends cambiente_valoracao {

	// Page ID
	var $PageID = 'grid';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'ambiente_valoracao';

	// Page object name
	var $PageObjName = 'ambiente_valoracao_grid';

	// Grid form hidden field names
	var $FormName = 'fambiente_valoracaogrid';
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

		// Table object (ambiente_valoracao)
		if (!isset($GLOBALS["ambiente_valoracao"])) {
			$GLOBALS["ambiente_valoracao"] = &$this;

//			$GLOBALS["MasterTable"] = &$GLOBALS["Table"];
//			if (!isset($GLOBALS["Table"])) $GLOBALS["Table"] = &$GLOBALS["ambiente_valoracao"];

		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'grid', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'ambiente_valoracao', TRUE);

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
		$this->dh_inclusao->Visible = !$this->IsAddOrEdit();

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
			$this->nu_ambiente->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->nu_ambiente->FormValue))
				return FALSE;
			$this->nu_versaoValoracao->setFormValue($arrKeyFlds[1]);
			if (!is_numeric($this->nu_versaoValoracao->FormValue))
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
					$sKey .= $this->nu_ambiente->CurrentValue;
					if ($sKey <> "") $sKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
					$sKey .= $this->nu_versaoValoracao->CurrentValue;

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
		if ($objForm->HasValue("x_nu_versaoValoracao") && $objForm->HasValue("o_nu_versaoValoracao") && $this->nu_versaoValoracao->CurrentValue <> $this->nu_versaoValoracao->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_ic_metCalibracao") && $objForm->HasValue("o_ic_metCalibracao") && $this->ic_metCalibracao->CurrentValue <> $this->ic_metCalibracao->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_qt_linhasCodLingPf") && $objForm->HasValue("o_qt_linhasCodLingPf") && $this->qt_linhasCodLingPf->CurrentValue <> $this->qt_linhasCodLingPf->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_vr_ipMin") && $objForm->HasValue("o_vr_ipMin") && $this->vr_ipMin->CurrentValue <> $this->vr_ipMin->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_vr_ipMed") && $objForm->HasValue("o_vr_ipMed") && $this->vr_ipMed->CurrentValue <> $this->vr_ipMed->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_vr_ipMax") && $objForm->HasValue("o_vr_ipMax") && $this->vr_ipMax->CurrentValue <> $this->vr_ipMax->OldValue)
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
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->nu_ambiente->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->nu_versaoValoracao->CurrentValue . "\">";
		}
		$this->RenderListOptionsExt();
	}

	// Set record key
	function SetRecordKey(&$key, $rs) {
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs->fields('nu_ambiente');
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs->fields('nu_versaoValoracao');
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
		$this->nu_versaoValoracao->CurrentValue = "1";
		$this->nu_versaoValoracao->OldValue = $this->nu_versaoValoracao->CurrentValue;
		$this->ic_metCalibracao->CurrentValue = NULL;
		$this->ic_metCalibracao->OldValue = $this->ic_metCalibracao->CurrentValue;
		$this->dh_inclusao->CurrentValue = NULL;
		$this->dh_inclusao->OldValue = $this->dh_inclusao->CurrentValue;
		$this->qt_linhasCodLingPf->CurrentValue = NULL;
		$this->qt_linhasCodLingPf->OldValue = $this->qt_linhasCodLingPf->CurrentValue;
		$this->vr_ipMin->CurrentValue = NULL;
		$this->vr_ipMin->OldValue = $this->vr_ipMin->CurrentValue;
		$this->vr_ipMed->CurrentValue = NULL;
		$this->vr_ipMed->OldValue = $this->vr_ipMed->CurrentValue;
		$this->vr_ipMax->CurrentValue = NULL;
		$this->vr_ipMax->OldValue = $this->vr_ipMax->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_versaoValoracao->FldIsDetailKey) {
			$this->nu_versaoValoracao->setFormValue($objForm->GetValue("x_nu_versaoValoracao"));
		}
		$this->nu_versaoValoracao->setOldValue($objForm->GetValue("o_nu_versaoValoracao"));
		if (!$this->ic_metCalibracao->FldIsDetailKey) {
			$this->ic_metCalibracao->setFormValue($objForm->GetValue("x_ic_metCalibracao"));
		}
		$this->ic_metCalibracao->setOldValue($objForm->GetValue("o_ic_metCalibracao"));
		if (!$this->dh_inclusao->FldIsDetailKey) {
			$this->dh_inclusao->setFormValue($objForm->GetValue("x_dh_inclusao"));
			$this->dh_inclusao->CurrentValue = ew_UnFormatDateTime($this->dh_inclusao->CurrentValue, 7);
		}
		$this->dh_inclusao->setOldValue($objForm->GetValue("o_dh_inclusao"));
		if (!$this->qt_linhasCodLingPf->FldIsDetailKey) {
			$this->qt_linhasCodLingPf->setFormValue($objForm->GetValue("x_qt_linhasCodLingPf"));
		}
		$this->qt_linhasCodLingPf->setOldValue($objForm->GetValue("o_qt_linhasCodLingPf"));
		if (!$this->vr_ipMin->FldIsDetailKey) {
			$this->vr_ipMin->setFormValue($objForm->GetValue("x_vr_ipMin"));
		}
		$this->vr_ipMin->setOldValue($objForm->GetValue("o_vr_ipMin"));
		if (!$this->vr_ipMed->FldIsDetailKey) {
			$this->vr_ipMed->setFormValue($objForm->GetValue("x_vr_ipMed"));
		}
		$this->vr_ipMed->setOldValue($objForm->GetValue("o_vr_ipMed"));
		if (!$this->vr_ipMax->FldIsDetailKey) {
			$this->vr_ipMax->setFormValue($objForm->GetValue("x_vr_ipMax"));
		}
		$this->vr_ipMax->setOldValue($objForm->GetValue("o_vr_ipMax"));
		if (!$this->nu_ambiente->FldIsDetailKey)
			$this->nu_ambiente->setFormValue($objForm->GetValue("x_nu_ambiente"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->nu_ambiente->CurrentValue = $this->nu_ambiente->FormValue;
		$this->nu_versaoValoracao->CurrentValue = $this->nu_versaoValoracao->FormValue;
		$this->ic_metCalibracao->CurrentValue = $this->ic_metCalibracao->FormValue;
		$this->dh_inclusao->CurrentValue = $this->dh_inclusao->FormValue;
		$this->dh_inclusao->CurrentValue = ew_UnFormatDateTime($this->dh_inclusao->CurrentValue, 7);
		$this->qt_linhasCodLingPf->CurrentValue = $this->qt_linhasCodLingPf->FormValue;
		$this->vr_ipMin->CurrentValue = $this->vr_ipMin->FormValue;
		$this->vr_ipMed->CurrentValue = $this->vr_ipMed->FormValue;
		$this->vr_ipMax->CurrentValue = $this->vr_ipMax->FormValue;
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
		$this->nu_ambiente->setDbValue($rs->fields('nu_ambiente'));
		$this->nu_versaoValoracao->setDbValue($rs->fields('nu_versaoValoracao'));
		$this->ic_metCalibracao->setDbValue($rs->fields('ic_metCalibracao'));
		$this->dh_inclusao->setDbValue($rs->fields('dh_inclusao'));
		$this->nu_usuarioResp->setDbValue($rs->fields('nu_usuarioResp'));
		$this->ic_tpAtualizacao->setDbValue($rs->fields('ic_tpAtualizacao'));
		$this->qt_linhasCodLingPf->setDbValue($rs->fields('qt_linhasCodLingPf'));
		$this->vr_ipMin->setDbValue($rs->fields('vr_ipMin'));
		$this->vr_ipMed->setDbValue($rs->fields('vr_ipMed'));
		$this->vr_ipMax->setDbValue($rs->fields('vr_ipMax'));
		$this->vr_constanteA->setDbValue($rs->fields('vr_constanteA'));
		$this->vr_constanteB->setDbValue($rs->fields('vr_constanteB'));
		$this->vr_constanteC->setDbValue($rs->fields('vr_constanteC'));
		$this->vr_constanteD->setDbValue($rs->fields('vr_constanteD'));
		$this->nu_altPREC->setDbValue($rs->fields('nu_altPREC'));
		if (array_key_exists('EV__nu_altPREC', $rs->fields)) {
			$this->nu_altPREC->VirtualValue = $rs->fields('EV__nu_altPREC'); // Set up virtual field value
		} else {
			$this->nu_altPREC->VirtualValue = ""; // Clear value
		}
		$this->nu_altFLEX->setDbValue($rs->fields('nu_altFLEX'));
		$this->nu_altRESL->setDbValue($rs->fields('nu_altRESL'));
		$this->nu_altTEAM->setDbValue($rs->fields('nu_altTEAM'));
		if (array_key_exists('EV__nu_altTEAM', $rs->fields)) {
			$this->nu_altTEAM->VirtualValue = $rs->fields('EV__nu_altTEAM'); // Set up virtual field value
		} else {
			$this->nu_altTEAM->VirtualValue = ""; // Clear value
		}
		$this->nu_altPMAT->setDbValue($rs->fields('nu_altPMAT'));
		$this->nu_altRELY->setDbValue($rs->fields('nu_altRELY'));
		$this->nu_altDATA->setDbValue($rs->fields('nu_altDATA'));
		$this->nu_altCPLX1->setDbValue($rs->fields('nu_altCPLX1'));
		$this->nu_altCPLX2->setDbValue($rs->fields('nu_altCPLX2'));
		$this->nu_altCPLX3->setDbValue($rs->fields('nu_altCPLX3'));
		if (array_key_exists('EV__nu_altCPLX3', $rs->fields)) {
			$this->nu_altCPLX3->VirtualValue = $rs->fields('EV__nu_altCPLX3'); // Set up virtual field value
		} else {
			$this->nu_altCPLX3->VirtualValue = ""; // Clear value
		}
		$this->nu_altCPLX4->setDbValue($rs->fields('nu_altCPLX4'));
		if (array_key_exists('EV__nu_altCPLX4', $rs->fields)) {
			$this->nu_altCPLX4->VirtualValue = $rs->fields('EV__nu_altCPLX4'); // Set up virtual field value
		} else {
			$this->nu_altCPLX4->VirtualValue = ""; // Clear value
		}
		$this->nu_altCPLX5->setDbValue($rs->fields('nu_altCPLX5'));
		$this->nu_altDOCU->setDbValue($rs->fields('nu_altDOCU'));
		$this->nu_altRUSE->setDbValue($rs->fields('nu_altRUSE'));
		$this->nu_altTIME->setDbValue($rs->fields('nu_altTIME'));
		$this->nu_altSTOR->setDbValue($rs->fields('nu_altSTOR'));
		$this->nu_altPVOL->setDbValue($rs->fields('nu_altPVOL'));
		$this->nu_altACAP->setDbValue($rs->fields('nu_altACAP'));
		$this->nu_altPCAP->setDbValue($rs->fields('nu_altPCAP'));
		$this->nu_altPCON->setDbValue($rs->fields('nu_altPCON'));
		$this->nu_altAPEX->setDbValue($rs->fields('nu_altAPEX'));
		$this->nu_altPLEX->setDbValue($rs->fields('nu_altPLEX'));
		$this->nu_altLTEX->setDbValue($rs->fields('nu_altLTEX'));
		$this->nu_altTOOL->setDbValue($rs->fields('nu_altTOOL'));
		$this->nu_altSITE->setDbValue($rs->fields('nu_altSITE'));
		$this->co_quePREC->setDbValue($rs->fields('co_quePREC'));
		$this->co_queFLEX->setDbValue($rs->fields('co_queFLEX'));
		$this->co_queRESL->setDbValue($rs->fields('co_queRESL'));
		$this->co_queTEAM->setDbValue($rs->fields('co_queTEAM'));
		$this->co_quePMAT->setDbValue($rs->fields('co_quePMAT'));
		$this->co_queRELY->setDbValue($rs->fields('co_queRELY'));
		$this->co_queDATA->setDbValue($rs->fields('co_queDATA'));
		$this->co_queCPLX1->setDbValue($rs->fields('co_queCPLX1'));
		$this->co_queCPLX2->setDbValue($rs->fields('co_queCPLX2'));
		$this->co_queCPLX3->setDbValue($rs->fields('co_queCPLX3'));
		$this->co_queCPLX4->setDbValue($rs->fields('co_queCPLX4'));
		$this->co_queCPLX5->setDbValue($rs->fields('co_queCPLX5'));
		$this->co_queDOCU->setDbValue($rs->fields('co_queDOCU'));
		$this->co_queRUSE->setDbValue($rs->fields('co_queRUSE'));
		$this->co_queTIME->setDbValue($rs->fields('co_queTIME'));
		$this->co_queSTOR->setDbValue($rs->fields('co_queSTOR'));
		$this->co_quePVOL->setDbValue($rs->fields('co_quePVOL'));
		$this->co_queACAP->setDbValue($rs->fields('co_queACAP'));
		$this->co_quePCAP->setDbValue($rs->fields('co_quePCAP'));
		$this->co_quePCON->setDbValue($rs->fields('co_quePCON'));
		$this->co_queAPEX->setDbValue($rs->fields('co_queAPEX'));
		$this->co_quePLEX->setDbValue($rs->fields('co_quePLEX'));
		$this->co_queLTEX->setDbValue($rs->fields('co_queLTEX'));
		$this->co_queTOOL->setDbValue($rs->fields('co_queTOOL'));
		$this->co_queSITE->setDbValue($rs->fields('co_queSITE'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_ambiente->DbValue = $row['nu_ambiente'];
		$this->nu_versaoValoracao->DbValue = $row['nu_versaoValoracao'];
		$this->ic_metCalibracao->DbValue = $row['ic_metCalibracao'];
		$this->dh_inclusao->DbValue = $row['dh_inclusao'];
		$this->nu_usuarioResp->DbValue = $row['nu_usuarioResp'];
		$this->ic_tpAtualizacao->DbValue = $row['ic_tpAtualizacao'];
		$this->qt_linhasCodLingPf->DbValue = $row['qt_linhasCodLingPf'];
		$this->vr_ipMin->DbValue = $row['vr_ipMin'];
		$this->vr_ipMed->DbValue = $row['vr_ipMed'];
		$this->vr_ipMax->DbValue = $row['vr_ipMax'];
		$this->vr_constanteA->DbValue = $row['vr_constanteA'];
		$this->vr_constanteB->DbValue = $row['vr_constanteB'];
		$this->vr_constanteC->DbValue = $row['vr_constanteC'];
		$this->vr_constanteD->DbValue = $row['vr_constanteD'];
		$this->nu_altPREC->DbValue = $row['nu_altPREC'];
		$this->nu_altFLEX->DbValue = $row['nu_altFLEX'];
		$this->nu_altRESL->DbValue = $row['nu_altRESL'];
		$this->nu_altTEAM->DbValue = $row['nu_altTEAM'];
		$this->nu_altPMAT->DbValue = $row['nu_altPMAT'];
		$this->nu_altRELY->DbValue = $row['nu_altRELY'];
		$this->nu_altDATA->DbValue = $row['nu_altDATA'];
		$this->nu_altCPLX1->DbValue = $row['nu_altCPLX1'];
		$this->nu_altCPLX2->DbValue = $row['nu_altCPLX2'];
		$this->nu_altCPLX3->DbValue = $row['nu_altCPLX3'];
		$this->nu_altCPLX4->DbValue = $row['nu_altCPLX4'];
		$this->nu_altCPLX5->DbValue = $row['nu_altCPLX5'];
		$this->nu_altDOCU->DbValue = $row['nu_altDOCU'];
		$this->nu_altRUSE->DbValue = $row['nu_altRUSE'];
		$this->nu_altTIME->DbValue = $row['nu_altTIME'];
		$this->nu_altSTOR->DbValue = $row['nu_altSTOR'];
		$this->nu_altPVOL->DbValue = $row['nu_altPVOL'];
		$this->nu_altACAP->DbValue = $row['nu_altACAP'];
		$this->nu_altPCAP->DbValue = $row['nu_altPCAP'];
		$this->nu_altPCON->DbValue = $row['nu_altPCON'];
		$this->nu_altAPEX->DbValue = $row['nu_altAPEX'];
		$this->nu_altPLEX->DbValue = $row['nu_altPLEX'];
		$this->nu_altLTEX->DbValue = $row['nu_altLTEX'];
		$this->nu_altTOOL->DbValue = $row['nu_altTOOL'];
		$this->nu_altSITE->DbValue = $row['nu_altSITE'];
		$this->co_quePREC->DbValue = $row['co_quePREC'];
		$this->co_queFLEX->DbValue = $row['co_queFLEX'];
		$this->co_queRESL->DbValue = $row['co_queRESL'];
		$this->co_queTEAM->DbValue = $row['co_queTEAM'];
		$this->co_quePMAT->DbValue = $row['co_quePMAT'];
		$this->co_queRELY->DbValue = $row['co_queRELY'];
		$this->co_queDATA->DbValue = $row['co_queDATA'];
		$this->co_queCPLX1->DbValue = $row['co_queCPLX1'];
		$this->co_queCPLX2->DbValue = $row['co_queCPLX2'];
		$this->co_queCPLX3->DbValue = $row['co_queCPLX3'];
		$this->co_queCPLX4->DbValue = $row['co_queCPLX4'];
		$this->co_queCPLX5->DbValue = $row['co_queCPLX5'];
		$this->co_queDOCU->DbValue = $row['co_queDOCU'];
		$this->co_queRUSE->DbValue = $row['co_queRUSE'];
		$this->co_queTIME->DbValue = $row['co_queTIME'];
		$this->co_queSTOR->DbValue = $row['co_queSTOR'];
		$this->co_quePVOL->DbValue = $row['co_quePVOL'];
		$this->co_queACAP->DbValue = $row['co_queACAP'];
		$this->co_quePCAP->DbValue = $row['co_quePCAP'];
		$this->co_quePCON->DbValue = $row['co_quePCON'];
		$this->co_queAPEX->DbValue = $row['co_queAPEX'];
		$this->co_quePLEX->DbValue = $row['co_quePLEX'];
		$this->co_queLTEX->DbValue = $row['co_queLTEX'];
		$this->co_queTOOL->DbValue = $row['co_queTOOL'];
		$this->co_queSITE->DbValue = $row['co_queSITE'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		$arKeys[] = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $this->RowOldKey);
		$cnt = count($arKeys);
		if ($cnt >= 2) {
			if (strval($arKeys[0]) <> "")
				$this->nu_ambiente->CurrentValue = strval($arKeys[0]); // nu_ambiente
			else
				$bValidKey = FALSE;
			if (strval($arKeys[1]) <> "")
				$this->nu_versaoValoracao->CurrentValue = strval($arKeys[1]); // nu_versaoValoracao
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

		if ($this->vr_ipMin->FormValue == $this->vr_ipMin->CurrentValue && is_numeric(ew_StrToFloat($this->vr_ipMin->CurrentValue)))
			$this->vr_ipMin->CurrentValue = ew_StrToFloat($this->vr_ipMin->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_ipMed->FormValue == $this->vr_ipMed->CurrentValue && is_numeric(ew_StrToFloat($this->vr_ipMed->CurrentValue)))
			$this->vr_ipMed->CurrentValue = ew_StrToFloat($this->vr_ipMed->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_ipMax->FormValue == $this->vr_ipMax->CurrentValue && is_numeric(ew_StrToFloat($this->vr_ipMax->CurrentValue)))
			$this->vr_ipMax->CurrentValue = ew_StrToFloat($this->vr_ipMax->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_ambiente
		// nu_versaoValoracao
		// ic_metCalibracao
		// dh_inclusao
		// nu_usuarioResp
		// ic_tpAtualizacao
		// qt_linhasCodLingPf
		// vr_ipMin
		// vr_ipMed
		// vr_ipMax
		// vr_constanteA
		// vr_constanteB
		// vr_constanteC
		// vr_constanteD
		// nu_altPREC
		// nu_altFLEX
		// nu_altRESL
		// nu_altTEAM
		// nu_altPMAT
		// nu_altRELY
		// nu_altDATA
		// nu_altCPLX1
		// nu_altCPLX2
		// nu_altCPLX3
		// nu_altCPLX4
		// nu_altCPLX5
		// nu_altDOCU
		// nu_altRUSE
		// nu_altTIME
		// nu_altSTOR
		// nu_altPVOL
		// nu_altACAP
		// nu_altPCAP
		// nu_altPCON
		// nu_altAPEX
		// nu_altPLEX
		// nu_altLTEX
		// nu_altTOOL
		// nu_altSITE
		// co_quePREC
		// co_queFLEX
		// co_queRESL
		// co_queTEAM
		// co_quePMAT
		// co_queRELY
		// co_queDATA
		// co_queCPLX1
		// co_queCPLX2
		// co_queCPLX3
		// co_queCPLX4
		// co_queCPLX5
		// co_queDOCU
		// co_queRUSE
		// co_queTIME
		// co_queSTOR
		// co_quePVOL
		// co_queACAP
		// co_quePCAP
		// co_quePCON
		// co_queAPEX
		// co_quePLEX
		// co_queLTEX
		// co_queTOOL
		// co_queSITE

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_ambiente
			$this->nu_ambiente->ViewValue = $this->nu_ambiente->CurrentValue;
			if (strval($this->nu_ambiente->CurrentValue) <> "") {
				$sFilterWrk = "[nu_ambiente]" . ew_SearchString("=", $this->nu_ambiente->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_ambiente], [nu_ambiente] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[contagempf]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_ambiente, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
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

			// nu_versaoValoracao
			$this->nu_versaoValoracao->ViewValue = $this->nu_versaoValoracao->CurrentValue;
			$this->nu_versaoValoracao->ViewCustomAttributes = "";

			// ic_metCalibracao
			if (strval($this->ic_metCalibracao->CurrentValue) <> "") {
				switch ($this->ic_metCalibracao->CurrentValue) {
					case $this->ic_metCalibracao->FldTagValue(1):
						$this->ic_metCalibracao->ViewValue = $this->ic_metCalibracao->FldTagCaption(1) <> "" ? $this->ic_metCalibracao->FldTagCaption(1) : $this->ic_metCalibracao->CurrentValue;
						break;
					case $this->ic_metCalibracao->FldTagValue(2):
						$this->ic_metCalibracao->ViewValue = $this->ic_metCalibracao->FldTagCaption(2) <> "" ? $this->ic_metCalibracao->FldTagCaption(2) : $this->ic_metCalibracao->CurrentValue;
						break;
					default:
						$this->ic_metCalibracao->ViewValue = $this->ic_metCalibracao->CurrentValue;
				}
			} else {
				$this->ic_metCalibracao->ViewValue = NULL;
			}
			$this->ic_metCalibracao->ViewCustomAttributes = "";

			// dh_inclusao
			$this->dh_inclusao->ViewValue = $this->dh_inclusao->CurrentValue;
			$this->dh_inclusao->ViewValue = ew_FormatDateTime($this->dh_inclusao->ViewValue, 7);
			$this->dh_inclusao->ViewCustomAttributes = "";

			// nu_usuarioResp
			$this->nu_usuarioResp->ViewValue = $this->nu_usuarioResp->CurrentValue;
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

			// ic_tpAtualizacao
			if (strval($this->ic_tpAtualizacao->CurrentValue) <> "") {
				switch ($this->ic_tpAtualizacao->CurrentValue) {
					case $this->ic_tpAtualizacao->FldTagValue(1):
						$this->ic_tpAtualizacao->ViewValue = $this->ic_tpAtualizacao->FldTagCaption(1) <> "" ? $this->ic_tpAtualizacao->FldTagCaption(1) : $this->ic_tpAtualizacao->CurrentValue;
						break;
					case $this->ic_tpAtualizacao->FldTagValue(2):
						$this->ic_tpAtualizacao->ViewValue = $this->ic_tpAtualizacao->FldTagCaption(2) <> "" ? $this->ic_tpAtualizacao->FldTagCaption(2) : $this->ic_tpAtualizacao->CurrentValue;
						break;
					default:
						$this->ic_tpAtualizacao->ViewValue = $this->ic_tpAtualizacao->CurrentValue;
				}
			} else {
				$this->ic_tpAtualizacao->ViewValue = NULL;
			}
			$this->ic_tpAtualizacao->ViewCustomAttributes = "";

			// qt_linhasCodLingPf
			$this->qt_linhasCodLingPf->ViewValue = $this->qt_linhasCodLingPf->CurrentValue;
			$this->qt_linhasCodLingPf->ViewCustomAttributes = "";

			// vr_ipMin
			$this->vr_ipMin->ViewValue = $this->vr_ipMin->CurrentValue;
			$this->vr_ipMin->ViewCustomAttributes = "";

			// vr_ipMed
			$this->vr_ipMed->ViewValue = $this->vr_ipMed->CurrentValue;
			$this->vr_ipMed->ViewCustomAttributes = "";

			// vr_ipMax
			$this->vr_ipMax->ViewValue = $this->vr_ipMax->CurrentValue;
			$this->vr_ipMax->ViewCustomAttributes = "";

			// vr_constanteA
			$this->vr_constanteA->ViewValue = $this->vr_constanteA->CurrentValue;
			$this->vr_constanteA->ViewCustomAttributes = "";

			// vr_constanteB
			$this->vr_constanteB->ViewValue = $this->vr_constanteB->CurrentValue;
			$this->vr_constanteB->ViewCustomAttributes = "";

			// vr_constanteC
			$this->vr_constanteC->ViewValue = $this->vr_constanteC->CurrentValue;
			$this->vr_constanteC->ViewCustomAttributes = "";

			// vr_constanteD
			$this->vr_constanteD->ViewValue = $this->vr_constanteD->CurrentValue;
			$this->vr_constanteD->ViewCustomAttributes = "";

			// nu_altPREC
			if ($this->nu_altPREC->VirtualValue <> "") {
				$this->nu_altPREC->ViewValue = $this->nu_altPREC->VirtualValue;
			} else {
			if (strval($this->nu_altPREC->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altPREC->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPREC, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altPREC->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altPREC->ViewValue .= ew_ValueSeparator(1,$this->nu_altPREC) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altPREC->ViewValue = $this->nu_altPREC->CurrentValue;
				}
			} else {
				$this->nu_altPREC->ViewValue = NULL;
			}
			}
			$this->nu_altPREC->ViewCustomAttributes = "";

			// nu_altFLEX
			if (strval($this->nu_altFLEX->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altFLEX->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altFLEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altFLEX->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altFLEX->ViewValue .= ew_ValueSeparator(1,$this->nu_altFLEX) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altFLEX->ViewValue = $this->nu_altFLEX->CurrentValue;
				}
			} else {
				$this->nu_altFLEX->ViewValue = NULL;
			}
			$this->nu_altFLEX->ViewCustomAttributes = "";

			// nu_altRESL
			if (strval($this->nu_altRESL->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altRESL->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altRESL, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altRESL->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altRESL->ViewValue .= ew_ValueSeparator(1,$this->nu_altRESL) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altRESL->ViewValue = $this->nu_altRESL->CurrentValue;
				}
			} else {
				$this->nu_altRESL->ViewValue = NULL;
			}
			$this->nu_altRESL->ViewCustomAttributes = "";

			// nu_altTEAM
			if ($this->nu_altTEAM->VirtualValue <> "") {
				$this->nu_altTEAM->ViewValue = $this->nu_altTEAM->VirtualValue;
			} else {
			if (strval($this->nu_altTEAM->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altTEAM->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altTEAM, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altTEAM->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altTEAM->ViewValue .= ew_ValueSeparator(1,$this->nu_altTEAM) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altTEAM->ViewValue = $this->nu_altTEAM->CurrentValue;
				}
			} else {
				$this->nu_altTEAM->ViewValue = NULL;
			}
			}
			$this->nu_altTEAM->ViewCustomAttributes = "";

			// nu_altPMAT
			if (strval($this->nu_altPMAT->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altPMAT->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPMAT, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altPMAT->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altPMAT->ViewValue .= ew_ValueSeparator(1,$this->nu_altPMAT) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altPMAT->ViewValue = $this->nu_altPMAT->CurrentValue;
				}
			} else {
				$this->nu_altPMAT->ViewValue = NULL;
			}
			$this->nu_altPMAT->ViewCustomAttributes = "";

			// nu_altRELY
			if (strval($this->nu_altRELY->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altRELY->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altRELY, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altRELY->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altRELY->ViewValue .= ew_ValueSeparator(1,$this->nu_altRELY) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altRELY->ViewValue = $this->nu_altRELY->CurrentValue;
				}
			} else {
				$this->nu_altRELY->ViewValue = NULL;
			}
			$this->nu_altRELY->ViewCustomAttributes = "";

			// nu_altDATA
			if (strval($this->nu_altDATA->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altDATA->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altDATA, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altDATA->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altDATA->ViewValue .= ew_ValueSeparator(1,$this->nu_altDATA) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altDATA->ViewValue = $this->nu_altDATA->CurrentValue;
				}
			} else {
				$this->nu_altDATA->ViewValue = NULL;
			}
			$this->nu_altDATA->ViewCustomAttributes = "";

			// nu_altCPLX1
			if (strval($this->nu_altCPLX1->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX1->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX1, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altCPLX1->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altCPLX1->ViewValue .= ew_ValueSeparator(1,$this->nu_altCPLX1) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altCPLX1->ViewValue = $this->nu_altCPLX1->CurrentValue;
				}
			} else {
				$this->nu_altCPLX1->ViewValue = NULL;
			}
			$this->nu_altCPLX1->ViewCustomAttributes = "";

			// nu_altCPLX2
			if (strval($this->nu_altCPLX2->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX2->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX2, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altCPLX2->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altCPLX2->ViewValue .= ew_ValueSeparator(1,$this->nu_altCPLX2) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altCPLX2->ViewValue = $this->nu_altCPLX2->CurrentValue;
				}
			} else {
				$this->nu_altCPLX2->ViewValue = NULL;
			}
			$this->nu_altCPLX2->ViewCustomAttributes = "";

			// nu_altCPLX3
			if ($this->nu_altCPLX3->VirtualValue <> "") {
				$this->nu_altCPLX3->ViewValue = $this->nu_altCPLX3->VirtualValue;
			} else {
			if (strval($this->nu_altCPLX3->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX3->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX3, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altCPLX3->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altCPLX3->ViewValue .= ew_ValueSeparator(1,$this->nu_altCPLX3) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altCPLX3->ViewValue = $this->nu_altCPLX3->CurrentValue;
				}
			} else {
				$this->nu_altCPLX3->ViewValue = NULL;
			}
			}
			$this->nu_altCPLX3->ViewCustomAttributes = "";

			// nu_altCPLX4
			if ($this->nu_altCPLX4->VirtualValue <> "") {
				$this->nu_altCPLX4->ViewValue = $this->nu_altCPLX4->VirtualValue;
			} else {
			if (strval($this->nu_altCPLX4->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX4->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX4, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altCPLX4->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altCPLX4->ViewValue .= ew_ValueSeparator(1,$this->nu_altCPLX4) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altCPLX4->ViewValue = $this->nu_altCPLX4->CurrentValue;
				}
			} else {
				$this->nu_altCPLX4->ViewValue = NULL;
			}
			}
			$this->nu_altCPLX4->ViewCustomAttributes = "";

			// nu_altCPLX5
			if (strval($this->nu_altCPLX5->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX5->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX5, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altCPLX5->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altCPLX5->ViewValue .= ew_ValueSeparator(1,$this->nu_altCPLX5) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altCPLX5->ViewValue = $this->nu_altCPLX5->CurrentValue;
				}
			} else {
				$this->nu_altCPLX5->ViewValue = NULL;
			}
			$this->nu_altCPLX5->ViewCustomAttributes = "";

			// nu_altDOCU
			if (strval($this->nu_altDOCU->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altDOCU->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altDOCU, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altDOCU->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altDOCU->ViewValue .= ew_ValueSeparator(1,$this->nu_altDOCU) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altDOCU->ViewValue = $this->nu_altDOCU->CurrentValue;
				}
			} else {
				$this->nu_altDOCU->ViewValue = NULL;
			}
			$this->nu_altDOCU->ViewCustomAttributes = "";

			// nu_altRUSE
			if (strval($this->nu_altRUSE->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altRUSE->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altRUSE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altRUSE->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altRUSE->ViewValue .= ew_ValueSeparator(1,$this->nu_altRUSE) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altRUSE->ViewValue = $this->nu_altRUSE->CurrentValue;
				}
			} else {
				$this->nu_altRUSE->ViewValue = NULL;
			}
			$this->nu_altRUSE->ViewCustomAttributes = "";

			// nu_altTIME
			if (strval($this->nu_altTIME->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altTIME->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altTIME, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altTIME->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altTIME->ViewValue .= ew_ValueSeparator(1,$this->nu_altTIME) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altTIME->ViewValue = $this->nu_altTIME->CurrentValue;
				}
			} else {
				$this->nu_altTIME->ViewValue = NULL;
			}
			$this->nu_altTIME->ViewCustomAttributes = "";

			// nu_altSTOR
			if (strval($this->nu_altSTOR->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altSTOR->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altSTOR, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altSTOR->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altSTOR->ViewValue .= ew_ValueSeparator(1,$this->nu_altSTOR) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altSTOR->ViewValue = $this->nu_altSTOR->CurrentValue;
				}
			} else {
				$this->nu_altSTOR->ViewValue = NULL;
			}
			$this->nu_altSTOR->ViewCustomAttributes = "";

			// nu_altPVOL
			if (strval($this->nu_altPVOL->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altPVOL->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPVOL, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altPVOL->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altPVOL->ViewValue .= ew_ValueSeparator(1,$this->nu_altPVOL) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altPVOL->ViewValue = $this->nu_altPVOL->CurrentValue;
				}
			} else {
				$this->nu_altPVOL->ViewValue = NULL;
			}
			$this->nu_altPVOL->ViewCustomAttributes = "";

			// nu_altACAP
			if (strval($this->nu_altACAP->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altACAP->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altACAP, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_alternativa] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altACAP->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altACAP->ViewValue .= ew_ValueSeparator(1,$this->nu_altACAP) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altACAP->ViewValue = $this->nu_altACAP->CurrentValue;
				}
			} else {
				$this->nu_altACAP->ViewValue = NULL;
			}
			$this->nu_altACAP->ViewCustomAttributes = "";

			// nu_altPCAP
			if (strval($this->nu_altPCAP->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altPCAP->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPCAP, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altPCAP->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altPCAP->ViewValue .= ew_ValueSeparator(1,$this->nu_altPCAP) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altPCAP->ViewValue = $this->nu_altPCAP->CurrentValue;
				}
			} else {
				$this->nu_altPCAP->ViewValue = NULL;
			}
			$this->nu_altPCAP->ViewCustomAttributes = "";

			// nu_altPCON
			if (strval($this->nu_altPCON->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altPCON->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPCON, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [ic_ativo] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altPCON->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altPCON->ViewValue .= ew_ValueSeparator(1,$this->nu_altPCON) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altPCON->ViewValue = $this->nu_altPCON->CurrentValue;
				}
			} else {
				$this->nu_altPCON->ViewValue = NULL;
			}
			$this->nu_altPCON->ViewCustomAttributes = "";

			// nu_altAPEX
			if (strval($this->nu_altAPEX->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altAPEX->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altAPEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altAPEX->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altAPEX->ViewValue .= ew_ValueSeparator(1,$this->nu_altAPEX) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altAPEX->ViewValue = $this->nu_altAPEX->CurrentValue;
				}
			} else {
				$this->nu_altAPEX->ViewValue = NULL;
			}
			$this->nu_altAPEX->ViewCustomAttributes = "";

			// nu_altPLEX
			if (strval($this->nu_altPLEX->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altPLEX->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPLEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altPLEX->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altPLEX->ViewValue .= ew_ValueSeparator(1,$this->nu_altPLEX) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altPLEX->ViewValue = $this->nu_altPLEX->CurrentValue;
				}
			} else {
				$this->nu_altPLEX->ViewValue = NULL;
			}
			$this->nu_altPLEX->ViewCustomAttributes = "";

			// nu_altLTEX
			if (strval($this->nu_altLTEX->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altLTEX->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altLTEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altLTEX->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altLTEX->ViewValue .= ew_ValueSeparator(1,$this->nu_altLTEX) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altLTEX->ViewValue = $this->nu_altLTEX->CurrentValue;
				}
			} else {
				$this->nu_altLTEX->ViewValue = NULL;
			}
			$this->nu_altLTEX->ViewCustomAttributes = "";

			// nu_altTOOL
			if (strval($this->nu_altTOOL->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altTOOL->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altTOOL, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altTOOL->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altTOOL->ViewValue .= ew_ValueSeparator(1,$this->nu_altTOOL) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altTOOL->ViewValue = $this->nu_altTOOL->CurrentValue;
				}
			} else {
				$this->nu_altTOOL->ViewValue = NULL;
			}
			$this->nu_altTOOL->ViewCustomAttributes = "";

			// nu_altSITE
			if (strval($this->nu_altSITE->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altSITE->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altSITE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altSITE->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altSITE->ViewValue .= ew_ValueSeparator(1,$this->nu_altSITE) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altSITE->ViewValue = $this->nu_altSITE->CurrentValue;
				}
			} else {
				$this->nu_altSITE->ViewValue = NULL;
			}
			$this->nu_altSITE->ViewCustomAttributes = "";

			// co_quePREC
			if (strval($this->co_quePREC->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_quePREC->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_quePREC, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_quePREC->ViewValue = $rswrk->fields('DispFld');
					$this->co_quePREC->ViewValue .= ew_ValueSeparator(1,$this->co_quePREC) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_quePREC->ViewValue = $this->co_quePREC->CurrentValue;
				}
			} else {
				$this->co_quePREC->ViewValue = NULL;
			}
			$this->co_quePREC->ViewCustomAttributes = "";

			// co_queFLEX
			if (strval($this->co_queFLEX->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queFLEX->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queFLEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queFLEX->ViewValue = $rswrk->fields('DispFld');
					$this->co_queFLEX->ViewValue .= ew_ValueSeparator(1,$this->co_queFLEX) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queFLEX->ViewValue = $this->co_queFLEX->CurrentValue;
				}
			} else {
				$this->co_queFLEX->ViewValue = NULL;
			}
			$this->co_queFLEX->ViewCustomAttributes = "";

			// co_queRESL
			if (strval($this->co_queRESL->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queRESL->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queRESL, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queRESL->ViewValue = $rswrk->fields('DispFld');
					$this->co_queRESL->ViewValue .= ew_ValueSeparator(1,$this->co_queRESL) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queRESL->ViewValue = $this->co_queRESL->CurrentValue;
				}
			} else {
				$this->co_queRESL->ViewValue = NULL;
			}
			$this->co_queRESL->ViewCustomAttributes = "";

			// co_queTEAM
			if (strval($this->co_queTEAM->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queTEAM->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queTEAM, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queTEAM->ViewValue = $rswrk->fields('DispFld');
					$this->co_queTEAM->ViewValue .= ew_ValueSeparator(1,$this->co_queTEAM) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queTEAM->ViewValue = $this->co_queTEAM->CurrentValue;
				}
			} else {
				$this->co_queTEAM->ViewValue = NULL;
			}
			$this->co_queTEAM->ViewCustomAttributes = "";

			// co_quePMAT
			if (strval($this->co_quePMAT->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_quePMAT->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_quePMAT, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_quePMAT->ViewValue = $rswrk->fields('DispFld');
					$this->co_quePMAT->ViewValue .= ew_ValueSeparator(1,$this->co_quePMAT) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_quePMAT->ViewValue = $this->co_quePMAT->CurrentValue;
				}
			} else {
				$this->co_quePMAT->ViewValue = NULL;
			}
			$this->co_quePMAT->ViewCustomAttributes = "";

			// co_queRELY
			if (strval($this->co_queRELY->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queRELY->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queRELY, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queRELY->ViewValue = $rswrk->fields('DispFld');
					$this->co_queRELY->ViewValue .= ew_ValueSeparator(1,$this->co_queRELY) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queRELY->ViewValue = $this->co_queRELY->CurrentValue;
				}
			} else {
				$this->co_queRELY->ViewValue = NULL;
			}
			$this->co_queRELY->ViewCustomAttributes = "";

			// co_queDATA
			if (strval($this->co_queDATA->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queDATA->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queDATA, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queDATA->ViewValue = $rswrk->fields('DispFld');
					$this->co_queDATA->ViewValue .= ew_ValueSeparator(1,$this->co_queDATA) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queDATA->ViewValue = $this->co_queDATA->CurrentValue;
				}
			} else {
				$this->co_queDATA->ViewValue = NULL;
			}
			$this->co_queDATA->ViewCustomAttributes = "";

			// co_queCPLX1
			if (strval($this->co_queCPLX1->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queCPLX1->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queCPLX1, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queCPLX1->ViewValue = $rswrk->fields('DispFld');
					$this->co_queCPLX1->ViewValue .= ew_ValueSeparator(1,$this->co_queCPLX1) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queCPLX1->ViewValue = $this->co_queCPLX1->CurrentValue;
				}
			} else {
				$this->co_queCPLX1->ViewValue = NULL;
			}
			$this->co_queCPLX1->ViewCustomAttributes = "";

			// co_queCPLX2
			if (strval($this->co_queCPLX2->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queCPLX2->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queCPLX2, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queCPLX2->ViewValue = $rswrk->fields('DispFld');
					$this->co_queCPLX2->ViewValue .= ew_ValueSeparator(1,$this->co_queCPLX2) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queCPLX2->ViewValue = $this->co_queCPLX2->CurrentValue;
				}
			} else {
				$this->co_queCPLX2->ViewValue = NULL;
			}
			$this->co_queCPLX2->ViewCustomAttributes = "";

			// co_queCPLX3
			if (strval($this->co_queCPLX3->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queCPLX3->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queCPLX3, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queCPLX3->ViewValue = $rswrk->fields('DispFld');
					$this->co_queCPLX3->ViewValue .= ew_ValueSeparator(1,$this->co_queCPLX3) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queCPLX3->ViewValue = $this->co_queCPLX3->CurrentValue;
				}
			} else {
				$this->co_queCPLX3->ViewValue = NULL;
			}
			$this->co_queCPLX3->ViewCustomAttributes = "";

			// co_queCPLX4
			if (strval($this->co_queCPLX4->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queCPLX4->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queCPLX4, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queCPLX4->ViewValue = $rswrk->fields('DispFld');
					$this->co_queCPLX4->ViewValue .= ew_ValueSeparator(1,$this->co_queCPLX4) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queCPLX4->ViewValue = $this->co_queCPLX4->CurrentValue;
				}
			} else {
				$this->co_queCPLX4->ViewValue = NULL;
			}
			$this->co_queCPLX4->ViewCustomAttributes = "";

			// co_queCPLX5
			if (strval($this->co_queCPLX5->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queCPLX5->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queCPLX5, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queCPLX5->ViewValue = $rswrk->fields('DispFld');
					$this->co_queCPLX5->ViewValue .= ew_ValueSeparator(1,$this->co_queCPLX5) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queCPLX5->ViewValue = $this->co_queCPLX5->CurrentValue;
				}
			} else {
				$this->co_queCPLX5->ViewValue = NULL;
			}
			$this->co_queCPLX5->ViewCustomAttributes = "";

			// co_queDOCU
			if (strval($this->co_queDOCU->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queDOCU->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queDOCU, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queDOCU->ViewValue = $rswrk->fields('DispFld');
					$this->co_queDOCU->ViewValue .= ew_ValueSeparator(1,$this->co_queDOCU) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queDOCU->ViewValue = $this->co_queDOCU->CurrentValue;
				}
			} else {
				$this->co_queDOCU->ViewValue = NULL;
			}
			$this->co_queDOCU->ViewCustomAttributes = "";

			// co_queRUSE
			if (strval($this->co_queRUSE->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queRUSE->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queRUSE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queRUSE->ViewValue = $rswrk->fields('DispFld');
					$this->co_queRUSE->ViewValue .= ew_ValueSeparator(1,$this->co_queRUSE) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queRUSE->ViewValue = $this->co_queRUSE->CurrentValue;
				}
			} else {
				$this->co_queRUSE->ViewValue = NULL;
			}
			$this->co_queRUSE->ViewCustomAttributes = "";

			// co_queTIME
			if (strval($this->co_queTIME->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queTIME->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queTIME, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queTIME->ViewValue = $rswrk->fields('DispFld');
					$this->co_queTIME->ViewValue .= ew_ValueSeparator(1,$this->co_queTIME) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queTIME->ViewValue = $this->co_queTIME->CurrentValue;
				}
			} else {
				$this->co_queTIME->ViewValue = NULL;
			}
			$this->co_queTIME->ViewCustomAttributes = "";

			// co_queSTOR
			if (strval($this->co_queSTOR->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queSTOR->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queSTOR, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queSTOR->ViewValue = $rswrk->fields('DispFld');
					$this->co_queSTOR->ViewValue .= ew_ValueSeparator(1,$this->co_queSTOR) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queSTOR->ViewValue = $this->co_queSTOR->CurrentValue;
				}
			} else {
				$this->co_queSTOR->ViewValue = NULL;
			}
			$this->co_queSTOR->ViewCustomAttributes = "";

			// co_quePVOL
			if (strval($this->co_quePVOL->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_quePVOL->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_quePVOL, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_quePVOL->ViewValue = $rswrk->fields('DispFld');
					$this->co_quePVOL->ViewValue .= ew_ValueSeparator(1,$this->co_quePVOL) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_quePVOL->ViewValue = $this->co_quePVOL->CurrentValue;
				}
			} else {
				$this->co_quePVOL->ViewValue = NULL;
			}
			$this->co_quePVOL->ViewCustomAttributes = "";

			// co_queACAP
			if (strval($this->co_queACAP->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queACAP->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queACAP, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queACAP->ViewValue = $rswrk->fields('DispFld');
					$this->co_queACAP->ViewValue .= ew_ValueSeparator(1,$this->co_queACAP) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queACAP->ViewValue = $this->co_queACAP->CurrentValue;
				}
			} else {
				$this->co_queACAP->ViewValue = NULL;
			}
			$this->co_queACAP->ViewCustomAttributes = "";

			// co_quePCAP
			if (strval($this->co_quePCAP->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_quePCAP->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_quePCAP, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_quePCAP->ViewValue = $rswrk->fields('DispFld');
					$this->co_quePCAP->ViewValue .= ew_ValueSeparator(1,$this->co_quePCAP) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_quePCAP->ViewValue = $this->co_quePCAP->CurrentValue;
				}
			} else {
				$this->co_quePCAP->ViewValue = NULL;
			}
			$this->co_quePCAP->ViewCustomAttributes = "";

			// co_quePCON
			if (strval($this->co_quePCON->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_quePCON->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_quePCON, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_quePCON->ViewValue = $rswrk->fields('DispFld');
					$this->co_quePCON->ViewValue .= ew_ValueSeparator(1,$this->co_quePCON) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_quePCON->ViewValue = $this->co_quePCON->CurrentValue;
				}
			} else {
				$this->co_quePCON->ViewValue = NULL;
			}
			$this->co_quePCON->ViewCustomAttributes = "";

			// co_queAPEX
			if (strval($this->co_queAPEX->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queAPEX->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queAPEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queAPEX->ViewValue = $rswrk->fields('DispFld');
					$this->co_queAPEX->ViewValue .= ew_ValueSeparator(1,$this->co_queAPEX) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queAPEX->ViewValue = $this->co_queAPEX->CurrentValue;
				}
			} else {
				$this->co_queAPEX->ViewValue = NULL;
			}
			$this->co_queAPEX->ViewCustomAttributes = "";

			// co_quePLEX
			if (strval($this->co_quePLEX->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_quePLEX->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_quePLEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_quePLEX->ViewValue = $rswrk->fields('DispFld');
					$this->co_quePLEX->ViewValue .= ew_ValueSeparator(1,$this->co_quePLEX) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_quePLEX->ViewValue = $this->co_quePLEX->CurrentValue;
				}
			} else {
				$this->co_quePLEX->ViewValue = NULL;
			}
			$this->co_quePLEX->ViewCustomAttributes = "";

			// co_queLTEX
			if (strval($this->co_queLTEX->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queLTEX->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queLTEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queLTEX->ViewValue = $rswrk->fields('DispFld');
					$this->co_queLTEX->ViewValue .= ew_ValueSeparator(1,$this->co_queLTEX) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queLTEX->ViewValue = $this->co_queLTEX->CurrentValue;
				}
			} else {
				$this->co_queLTEX->ViewValue = NULL;
			}
			$this->co_queLTEX->ViewCustomAttributes = "";

			// co_queTOOL
			if (strval($this->co_queTOOL->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queTOOL->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queTOOL, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queTOOL->ViewValue = $rswrk->fields('DispFld');
					$this->co_queTOOL->ViewValue .= ew_ValueSeparator(1,$this->co_queTOOL) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queTOOL->ViewValue = $this->co_queTOOL->CurrentValue;
				}
			} else {
				$this->co_queTOOL->ViewValue = NULL;
			}
			$this->co_queTOOL->ViewCustomAttributes = "";

			// co_queSITE
			if (strval($this->co_queSITE->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queSITE->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queSITE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queSITE->ViewValue = $rswrk->fields('DispFld');
					$this->co_queSITE->ViewValue .= ew_ValueSeparator(1,$this->co_queSITE) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queSITE->ViewValue = $this->co_queSITE->CurrentValue;
				}
			} else {
				$this->co_queSITE->ViewValue = NULL;
			}
			$this->co_queSITE->ViewCustomAttributes = "";

			// nu_versaoValoracao
			$this->nu_versaoValoracao->LinkCustomAttributes = "";
			$this->nu_versaoValoracao->HrefValue = "";
			$this->nu_versaoValoracao->TooltipValue = "";

			// ic_metCalibracao
			$this->ic_metCalibracao->LinkCustomAttributes = "";
			$this->ic_metCalibracao->HrefValue = "";
			$this->ic_metCalibracao->TooltipValue = "";

			// dh_inclusao
			$this->dh_inclusao->LinkCustomAttributes = "";
			$this->dh_inclusao->HrefValue = "";
			$this->dh_inclusao->TooltipValue = "";

			// qt_linhasCodLingPf
			$this->qt_linhasCodLingPf->LinkCustomAttributes = "";
			$this->qt_linhasCodLingPf->HrefValue = "";
			$this->qt_linhasCodLingPf->TooltipValue = "";

			// vr_ipMin
			$this->vr_ipMin->LinkCustomAttributes = "";
			$this->vr_ipMin->HrefValue = "";
			$this->vr_ipMin->TooltipValue = "";

			// vr_ipMed
			$this->vr_ipMed->LinkCustomAttributes = "";
			$this->vr_ipMed->HrefValue = "";
			$this->vr_ipMed->TooltipValue = "";

			// vr_ipMax
			$this->vr_ipMax->LinkCustomAttributes = "";
			$this->vr_ipMax->HrefValue = "";
			$this->vr_ipMax->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nu_versaoValoracao
			$this->nu_versaoValoracao->EditCustomAttributes = "";
			$this->nu_versaoValoracao->EditValue = ew_HtmlEncode($this->nu_versaoValoracao->CurrentValue);
			$this->nu_versaoValoracao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_versaoValoracao->FldCaption()));

			// ic_metCalibracao
			$this->ic_metCalibracao->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_metCalibracao->FldTagValue(1), $this->ic_metCalibracao->FldTagCaption(1) <> "" ? $this->ic_metCalibracao->FldTagCaption(1) : $this->ic_metCalibracao->FldTagValue(1));
			$arwrk[] = array($this->ic_metCalibracao->FldTagValue(2), $this->ic_metCalibracao->FldTagCaption(2) <> "" ? $this->ic_metCalibracao->FldTagCaption(2) : $this->ic_metCalibracao->FldTagValue(2));
			$this->ic_metCalibracao->EditValue = $arwrk;

			// dh_inclusao
			// qt_linhasCodLingPf

			$this->qt_linhasCodLingPf->EditCustomAttributes = "";
			$this->qt_linhasCodLingPf->EditValue = ew_HtmlEncode($this->qt_linhasCodLingPf->CurrentValue);
			$this->qt_linhasCodLingPf->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_linhasCodLingPf->FldCaption()));

			// vr_ipMin
			$this->vr_ipMin->EditCustomAttributes = "";
			$this->vr_ipMin->EditValue = ew_HtmlEncode($this->vr_ipMin->CurrentValue);
			$this->vr_ipMin->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_ipMin->FldCaption()));
			if (strval($this->vr_ipMin->EditValue) <> "" && is_numeric($this->vr_ipMin->EditValue)) {
			$this->vr_ipMin->EditValue = ew_FormatNumber($this->vr_ipMin->EditValue, -2, -1, -2, 0);
			$this->vr_ipMin->OldValue = $this->vr_ipMin->EditValue;
			}

			// vr_ipMed
			$this->vr_ipMed->EditCustomAttributes = "";
			$this->vr_ipMed->EditValue = ew_HtmlEncode($this->vr_ipMed->CurrentValue);
			$this->vr_ipMed->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_ipMed->FldCaption()));
			if (strval($this->vr_ipMed->EditValue) <> "" && is_numeric($this->vr_ipMed->EditValue)) {
			$this->vr_ipMed->EditValue = ew_FormatNumber($this->vr_ipMed->EditValue, -2, -1, -2, 0);
			$this->vr_ipMed->OldValue = $this->vr_ipMed->EditValue;
			}

			// vr_ipMax
			$this->vr_ipMax->EditCustomAttributes = "";
			$this->vr_ipMax->EditValue = ew_HtmlEncode($this->vr_ipMax->CurrentValue);
			$this->vr_ipMax->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_ipMax->FldCaption()));
			if (strval($this->vr_ipMax->EditValue) <> "" && is_numeric($this->vr_ipMax->EditValue)) {
			$this->vr_ipMax->EditValue = ew_FormatNumber($this->vr_ipMax->EditValue, -2, -1, -2, 0);
			$this->vr_ipMax->OldValue = $this->vr_ipMax->EditValue;
			}

			// Edit refer script
			// nu_versaoValoracao

			$this->nu_versaoValoracao->HrefValue = "";

			// ic_metCalibracao
			$this->ic_metCalibracao->HrefValue = "";

			// dh_inclusao
			$this->dh_inclusao->HrefValue = "";

			// qt_linhasCodLingPf
			$this->qt_linhasCodLingPf->HrefValue = "";

			// vr_ipMin
			$this->vr_ipMin->HrefValue = "";

			// vr_ipMed
			$this->vr_ipMed->HrefValue = "";

			// vr_ipMax
			$this->vr_ipMax->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// nu_versaoValoracao
			$this->nu_versaoValoracao->EditCustomAttributes = "";
			$this->nu_versaoValoracao->EditValue = $this->nu_versaoValoracao->CurrentValue;
			$this->nu_versaoValoracao->ViewCustomAttributes = "";

			// ic_metCalibracao
			$this->ic_metCalibracao->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_metCalibracao->FldTagValue(1), $this->ic_metCalibracao->FldTagCaption(1) <> "" ? $this->ic_metCalibracao->FldTagCaption(1) : $this->ic_metCalibracao->FldTagValue(1));
			$arwrk[] = array($this->ic_metCalibracao->FldTagValue(2), $this->ic_metCalibracao->FldTagCaption(2) <> "" ? $this->ic_metCalibracao->FldTagCaption(2) : $this->ic_metCalibracao->FldTagValue(2));
			$this->ic_metCalibracao->EditValue = $arwrk;

			// dh_inclusao
			// qt_linhasCodLingPf

			$this->qt_linhasCodLingPf->EditCustomAttributes = "";
			$this->qt_linhasCodLingPf->EditValue = ew_HtmlEncode($this->qt_linhasCodLingPf->CurrentValue);
			$this->qt_linhasCodLingPf->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_linhasCodLingPf->FldCaption()));

			// vr_ipMin
			$this->vr_ipMin->EditCustomAttributes = "";
			$this->vr_ipMin->EditValue = ew_HtmlEncode($this->vr_ipMin->CurrentValue);
			$this->vr_ipMin->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_ipMin->FldCaption()));
			if (strval($this->vr_ipMin->EditValue) <> "" && is_numeric($this->vr_ipMin->EditValue)) {
			$this->vr_ipMin->EditValue = ew_FormatNumber($this->vr_ipMin->EditValue, -2, -1, -2, 0);
			$this->vr_ipMin->OldValue = $this->vr_ipMin->EditValue;
			}

			// vr_ipMed
			$this->vr_ipMed->EditCustomAttributes = "";
			$this->vr_ipMed->EditValue = ew_HtmlEncode($this->vr_ipMed->CurrentValue);
			$this->vr_ipMed->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_ipMed->FldCaption()));
			if (strval($this->vr_ipMed->EditValue) <> "" && is_numeric($this->vr_ipMed->EditValue)) {
			$this->vr_ipMed->EditValue = ew_FormatNumber($this->vr_ipMed->EditValue, -2, -1, -2, 0);
			$this->vr_ipMed->OldValue = $this->vr_ipMed->EditValue;
			}

			// vr_ipMax
			$this->vr_ipMax->EditCustomAttributes = "";
			$this->vr_ipMax->EditValue = ew_HtmlEncode($this->vr_ipMax->CurrentValue);
			$this->vr_ipMax->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_ipMax->FldCaption()));
			if (strval($this->vr_ipMax->EditValue) <> "" && is_numeric($this->vr_ipMax->EditValue)) {
			$this->vr_ipMax->EditValue = ew_FormatNumber($this->vr_ipMax->EditValue, -2, -1, -2, 0);
			$this->vr_ipMax->OldValue = $this->vr_ipMax->EditValue;
			}

			// Edit refer script
			// nu_versaoValoracao

			$this->nu_versaoValoracao->HrefValue = "";

			// ic_metCalibracao
			$this->ic_metCalibracao->HrefValue = "";

			// dh_inclusao
			$this->dh_inclusao->HrefValue = "";

			// qt_linhasCodLingPf
			$this->qt_linhasCodLingPf->HrefValue = "";

			// vr_ipMin
			$this->vr_ipMin->HrefValue = "";

			// vr_ipMed
			$this->vr_ipMed->HrefValue = "";

			// vr_ipMax
			$this->vr_ipMax->HrefValue = "";
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
		if (!$this->nu_versaoValoracao->FldIsDetailKey && !is_null($this->nu_versaoValoracao->FormValue) && $this->nu_versaoValoracao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_versaoValoracao->FldCaption());
		}
		if (!ew_CheckInteger($this->nu_versaoValoracao->FormValue)) {
			ew_AddMessage($gsFormError, $this->nu_versaoValoracao->FldErrMsg());
		}
		if ($this->ic_metCalibracao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_metCalibracao->FldCaption());
		}
		if (!ew_CheckInteger($this->qt_linhasCodLingPf->FormValue)) {
			ew_AddMessage($gsFormError, $this->qt_linhasCodLingPf->FldErrMsg());
		}
		if (!$this->vr_ipMin->FldIsDetailKey && !is_null($this->vr_ipMin->FormValue) && $this->vr_ipMin->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->vr_ipMin->FldCaption());
		}
		if (!ew_CheckNumber($this->vr_ipMin->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_ipMin->FldErrMsg());
		}
		if (!$this->vr_ipMed->FldIsDetailKey && !is_null($this->vr_ipMed->FormValue) && $this->vr_ipMed->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->vr_ipMed->FldCaption());
		}
		if (!ew_CheckNumber($this->vr_ipMed->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_ipMed->FldErrMsg());
		}
		if (!$this->vr_ipMax->FldIsDetailKey && !is_null($this->vr_ipMax->FormValue) && $this->vr_ipMax->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->vr_ipMax->FldCaption());
		}
		if (!ew_CheckNumber($this->vr_ipMax->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_ipMax->FldErrMsg());
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
				$sThisKey .= $row['nu_ambiente'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['nu_versaoValoracao'];
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

			// nu_versaoValoracao
			// ic_metCalibracao

			$this->ic_metCalibracao->SetDbValueDef($rsnew, $this->ic_metCalibracao->CurrentValue, NULL, $this->ic_metCalibracao->ReadOnly);

			// dh_inclusao
			$this->dh_inclusao->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
			$rsnew['dh_inclusao'] = &$this->dh_inclusao->DbValue;

			// qt_linhasCodLingPf
			$this->qt_linhasCodLingPf->SetDbValueDef($rsnew, $this->qt_linhasCodLingPf->CurrentValue, NULL, $this->qt_linhasCodLingPf->ReadOnly);

			// vr_ipMin
			$this->vr_ipMin->SetDbValueDef($rsnew, $this->vr_ipMin->CurrentValue, NULL, $this->vr_ipMin->ReadOnly);

			// vr_ipMed
			$this->vr_ipMed->SetDbValueDef($rsnew, $this->vr_ipMed->CurrentValue, NULL, $this->vr_ipMed->ReadOnly);

			// vr_ipMax
			$this->vr_ipMax->SetDbValueDef($rsnew, $this->vr_ipMax->CurrentValue, NULL, $this->vr_ipMax->ReadOnly);

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
			if ($this->getCurrentMasterTable() == "ambiente") {
				$this->nu_ambiente->CurrentValue = $this->nu_ambiente->getSessionValue();
			}

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// nu_versaoValoracao
		$this->nu_versaoValoracao->SetDbValueDef($rsnew, $this->nu_versaoValoracao->CurrentValue, 0, FALSE);

		// ic_metCalibracao
		$this->ic_metCalibracao->SetDbValueDef($rsnew, $this->ic_metCalibracao->CurrentValue, NULL, FALSE);

		// dh_inclusao
		$this->dh_inclusao->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
		$rsnew['dh_inclusao'] = &$this->dh_inclusao->DbValue;

		// qt_linhasCodLingPf
		$this->qt_linhasCodLingPf->SetDbValueDef($rsnew, $this->qt_linhasCodLingPf->CurrentValue, NULL, FALSE);

		// vr_ipMin
		$this->vr_ipMin->SetDbValueDef($rsnew, $this->vr_ipMin->CurrentValue, NULL, FALSE);

		// vr_ipMed
		$this->vr_ipMed->SetDbValueDef($rsnew, $this->vr_ipMed->CurrentValue, NULL, FALSE);

		// vr_ipMax
		$this->vr_ipMax->SetDbValueDef($rsnew, $this->vr_ipMax->CurrentValue, NULL, FALSE);

		// nu_ambiente
		if ($this->nu_ambiente->getSessionValue() <> "") {
			$rsnew['nu_ambiente'] = $this->nu_ambiente->getSessionValue();
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && $this->nu_ambiente->CurrentValue == "" && $this->nu_ambiente->getSessionValue() == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && $this->nu_versaoValoracao->CurrentValue == "" && $this->nu_versaoValoracao->getSessionValue() == "") {
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
		if ($sMasterTblVar == "ambiente") {
			$this->nu_ambiente->Visible = FALSE;
			if ($GLOBALS["ambiente"]->EventCancelled) $this->EventCancelled = TRUE;
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); //  Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'ambiente_valoracao';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'ambiente_valoracao';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_ambiente'];
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_versaoValoracao'];

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
		$table = 'ambiente_valoracao';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['nu_ambiente'];
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['nu_versaoValoracao'];

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
		$table = 'ambiente_valoracao';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_ambiente'];
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_versaoValoracao'];

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
