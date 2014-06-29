<?php include_once "estimativainfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php

//
// Page class
//

$estimativa_grid = NULL; // Initialize page object first

class cestimativa_grid extends cestimativa {

	// Page ID
	var $PageID = 'grid';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'estimativa';

	// Page object name
	var $PageObjName = 'estimativa_grid';

	// Grid form hidden field names
	var $FormName = 'festimativagrid';
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

		// Table object (estimativa)
		if (!isset($GLOBALS["estimativa"])) {
			$GLOBALS["estimativa"] = &$this;

//			$GLOBALS["MasterTable"] = &$GLOBALS["Table"];
//			if (!isset($GLOBALS["Table"])) $GLOBALS["Table"] = &$GLOBALS["estimativa"];

		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'grid', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'estimativa', TRUE);

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
		if (count($arrKeyFlds) >= 1) {
			$this->nu_estimativa->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->nu_estimativa->FormValue))
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
					$sKey .= $this->nu_estimativa->CurrentValue;

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
		if ($objForm->HasValue("x_ic_solicitacaoCritica") && $objForm->HasValue("o_ic_solicitacaoCritica") && $this->ic_solicitacaoCritica->CurrentValue <> $this->ic_solicitacaoCritica->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_nu_ambienteMaisRepresentativo") && $objForm->HasValue("o_nu_ambienteMaisRepresentativo") && $this->nu_ambienteMaisRepresentativo->CurrentValue <> $this->nu_ambienteMaisRepresentativo->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_qt_tamBase") && $objForm->HasValue("o_qt_tamBase") && $this->qt_tamBase->CurrentValue <> $this->qt_tamBase->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_ic_modeloCocomo") && $objForm->HasValue("o_ic_modeloCocomo") && $this->ic_modeloCocomo->CurrentValue <> $this->ic_modeloCocomo->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_nu_metPrazo") && $objForm->HasValue("o_nu_metPrazo") && $this->nu_metPrazo->CurrentValue <> $this->nu_metPrazo->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_vr_doPf") && $objForm->HasValue("o_vr_doPf") && $this->vr_doPf->CurrentValue <> $this->vr_doPf->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_pz_estimadoMeses") && $objForm->HasValue("o_pz_estimadoMeses") && $this->pz_estimadoMeses->CurrentValue <> $this->pz_estimadoMeses->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_pz_estimadoDias") && $objForm->HasValue("o_pz_estimadoDias") && $this->pz_estimadoDias->CurrentValue <> $this->pz_estimadoDias->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_vr_ipMaximo") && $objForm->HasValue("o_vr_ipMaximo") && $this->vr_ipMaximo->CurrentValue <> $this->vr_ipMaximo->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_vr_ipMedio") && $objForm->HasValue("o_vr_ipMedio") && $this->vr_ipMedio->CurrentValue <> $this->vr_ipMedio->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_vr_ipMinimo") && $objForm->HasValue("o_vr_ipMinimo") && $this->vr_ipMinimo->CurrentValue <> $this->vr_ipMinimo->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_vr_ipInformado") && $objForm->HasValue("o_vr_ipInformado") && $this->vr_ipInformado->CurrentValue <> $this->vr_ipInformado->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_qt_esforco") && $objForm->HasValue("o_qt_esforco") && $this->qt_esforco->CurrentValue <> $this->qt_esforco->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_vr_custoDesenv") && $objForm->HasValue("o_vr_custoDesenv") && $this->vr_custoDesenv->CurrentValue <> $this->vr_custoDesenv->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_vr_outrosCustos") && $objForm->HasValue("o_vr_outrosCustos") && $this->vr_outrosCustos->CurrentValue <> $this->vr_outrosCustos->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_vr_custoTotal") && $objForm->HasValue("o_vr_custoTotal") && $this->vr_custoTotal->CurrentValue <> $this->vr_custoTotal->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_qt_tamBaseFaturamento") && $objForm->HasValue("o_qt_tamBaseFaturamento") && $this->qt_tamBaseFaturamento->CurrentValue <> $this->qt_tamBaseFaturamento->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_qt_recursosEquipe") && $objForm->HasValue("o_qt_recursosEquipe") && $this->qt_recursosEquipe->CurrentValue <> $this->qt_recursosEquipe->OldValue)
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
				$this->nu_solMetricas->setSessionValue("");
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
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->nu_estimativa->CurrentValue . "\">";
		}
		$this->RenderListOptionsExt();
	}

	// Set record key
	function SetRecordKey(&$key, $rs) {
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs->fields('nu_estimativa');
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
		$this->ic_solicitacaoCritica->CurrentValue = "N";
		$this->ic_solicitacaoCritica->OldValue = $this->ic_solicitacaoCritica->CurrentValue;
		$this->nu_ambienteMaisRepresentativo->CurrentValue = NULL;
		$this->nu_ambienteMaisRepresentativo->OldValue = $this->nu_ambienteMaisRepresentativo->CurrentValue;
		$this->qt_tamBase->CurrentValue = NULL;
		$this->qt_tamBase->OldValue = $this->qt_tamBase->CurrentValue;
		$this->ic_modeloCocomo->CurrentValue = NULL;
		$this->ic_modeloCocomo->OldValue = $this->ic_modeloCocomo->CurrentValue;
		$this->nu_metPrazo->CurrentValue = "4";
		$this->nu_metPrazo->OldValue = $this->nu_metPrazo->CurrentValue;
		$this->vr_doPf->CurrentValue = NULL;
		$this->vr_doPf->OldValue = $this->vr_doPf->CurrentValue;
		$this->pz_estimadoMeses->CurrentValue = NULL;
		$this->pz_estimadoMeses->OldValue = $this->pz_estimadoMeses->CurrentValue;
		$this->pz_estimadoDias->CurrentValue = NULL;
		$this->pz_estimadoDias->OldValue = $this->pz_estimadoDias->CurrentValue;
		$this->vr_ipMaximo->CurrentValue = NULL;
		$this->vr_ipMaximo->OldValue = $this->vr_ipMaximo->CurrentValue;
		$this->vr_ipMedio->CurrentValue = NULL;
		$this->vr_ipMedio->OldValue = $this->vr_ipMedio->CurrentValue;
		$this->vr_ipMinimo->CurrentValue = NULL;
		$this->vr_ipMinimo->OldValue = $this->vr_ipMinimo->CurrentValue;
		$this->vr_ipInformado->CurrentValue = NULL;
		$this->vr_ipInformado->OldValue = $this->vr_ipInformado->CurrentValue;
		$this->qt_esforco->CurrentValue = NULL;
		$this->qt_esforco->OldValue = $this->qt_esforco->CurrentValue;
		$this->vr_custoDesenv->CurrentValue = NULL;
		$this->vr_custoDesenv->OldValue = $this->vr_custoDesenv->CurrentValue;
		$this->vr_outrosCustos->CurrentValue = NULL;
		$this->vr_outrosCustos->OldValue = $this->vr_outrosCustos->CurrentValue;
		$this->vr_custoTotal->CurrentValue = NULL;
		$this->vr_custoTotal->OldValue = $this->vr_custoTotal->CurrentValue;
		$this->qt_tamBaseFaturamento->CurrentValue = NULL;
		$this->qt_tamBaseFaturamento->OldValue = $this->qt_tamBaseFaturamento->CurrentValue;
		$this->qt_recursosEquipe->CurrentValue = NULL;
		$this->qt_recursosEquipe->OldValue = $this->qt_recursosEquipe->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->ic_solicitacaoCritica->FldIsDetailKey) {
			$this->ic_solicitacaoCritica->setFormValue($objForm->GetValue("x_ic_solicitacaoCritica"));
		}
		$this->ic_solicitacaoCritica->setOldValue($objForm->GetValue("o_ic_solicitacaoCritica"));
		if (!$this->nu_ambienteMaisRepresentativo->FldIsDetailKey) {
			$this->nu_ambienteMaisRepresentativo->setFormValue($objForm->GetValue("x_nu_ambienteMaisRepresentativo"));
		}
		$this->nu_ambienteMaisRepresentativo->setOldValue($objForm->GetValue("o_nu_ambienteMaisRepresentativo"));
		if (!$this->qt_tamBase->FldIsDetailKey) {
			$this->qt_tamBase->setFormValue($objForm->GetValue("x_qt_tamBase"));
		}
		$this->qt_tamBase->setOldValue($objForm->GetValue("o_qt_tamBase"));
		if (!$this->ic_modeloCocomo->FldIsDetailKey) {
			$this->ic_modeloCocomo->setFormValue($objForm->GetValue("x_ic_modeloCocomo"));
		}
		$this->ic_modeloCocomo->setOldValue($objForm->GetValue("o_ic_modeloCocomo"));
		if (!$this->nu_metPrazo->FldIsDetailKey) {
			$this->nu_metPrazo->setFormValue($objForm->GetValue("x_nu_metPrazo"));
		}
		$this->nu_metPrazo->setOldValue($objForm->GetValue("o_nu_metPrazo"));
		if (!$this->vr_doPf->FldIsDetailKey) {
			$this->vr_doPf->setFormValue($objForm->GetValue("x_vr_doPf"));
		}
		$this->vr_doPf->setOldValue($objForm->GetValue("o_vr_doPf"));
		if (!$this->pz_estimadoMeses->FldIsDetailKey) {
			$this->pz_estimadoMeses->setFormValue($objForm->GetValue("x_pz_estimadoMeses"));
		}
		$this->pz_estimadoMeses->setOldValue($objForm->GetValue("o_pz_estimadoMeses"));
		if (!$this->pz_estimadoDias->FldIsDetailKey) {
			$this->pz_estimadoDias->setFormValue($objForm->GetValue("x_pz_estimadoDias"));
		}
		$this->pz_estimadoDias->setOldValue($objForm->GetValue("o_pz_estimadoDias"));
		if (!$this->vr_ipMaximo->FldIsDetailKey) {
			$this->vr_ipMaximo->setFormValue($objForm->GetValue("x_vr_ipMaximo"));
		}
		$this->vr_ipMaximo->setOldValue($objForm->GetValue("o_vr_ipMaximo"));
		if (!$this->vr_ipMedio->FldIsDetailKey) {
			$this->vr_ipMedio->setFormValue($objForm->GetValue("x_vr_ipMedio"));
		}
		$this->vr_ipMedio->setOldValue($objForm->GetValue("o_vr_ipMedio"));
		if (!$this->vr_ipMinimo->FldIsDetailKey) {
			$this->vr_ipMinimo->setFormValue($objForm->GetValue("x_vr_ipMinimo"));
		}
		$this->vr_ipMinimo->setOldValue($objForm->GetValue("o_vr_ipMinimo"));
		if (!$this->vr_ipInformado->FldIsDetailKey) {
			$this->vr_ipInformado->setFormValue($objForm->GetValue("x_vr_ipInformado"));
		}
		$this->vr_ipInformado->setOldValue($objForm->GetValue("o_vr_ipInformado"));
		if (!$this->qt_esforco->FldIsDetailKey) {
			$this->qt_esforco->setFormValue($objForm->GetValue("x_qt_esforco"));
		}
		$this->qt_esforco->setOldValue($objForm->GetValue("o_qt_esforco"));
		if (!$this->vr_custoDesenv->FldIsDetailKey) {
			$this->vr_custoDesenv->setFormValue($objForm->GetValue("x_vr_custoDesenv"));
		}
		$this->vr_custoDesenv->setOldValue($objForm->GetValue("o_vr_custoDesenv"));
		if (!$this->vr_outrosCustos->FldIsDetailKey) {
			$this->vr_outrosCustos->setFormValue($objForm->GetValue("x_vr_outrosCustos"));
		}
		$this->vr_outrosCustos->setOldValue($objForm->GetValue("o_vr_outrosCustos"));
		if (!$this->vr_custoTotal->FldIsDetailKey) {
			$this->vr_custoTotal->setFormValue($objForm->GetValue("x_vr_custoTotal"));
		}
		$this->vr_custoTotal->setOldValue($objForm->GetValue("o_vr_custoTotal"));
		if (!$this->qt_tamBaseFaturamento->FldIsDetailKey) {
			$this->qt_tamBaseFaturamento->setFormValue($objForm->GetValue("x_qt_tamBaseFaturamento"));
		}
		$this->qt_tamBaseFaturamento->setOldValue($objForm->GetValue("o_qt_tamBaseFaturamento"));
		if (!$this->qt_recursosEquipe->FldIsDetailKey) {
			$this->qt_recursosEquipe->setFormValue($objForm->GetValue("x_qt_recursosEquipe"));
		}
		$this->qt_recursosEquipe->setOldValue($objForm->GetValue("o_qt_recursosEquipe"));
		if (!$this->nu_estimativa->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->nu_estimativa->setFormValue($objForm->GetValue("x_nu_estimativa"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->nu_estimativa->CurrentValue = $this->nu_estimativa->FormValue;
		$this->ic_solicitacaoCritica->CurrentValue = $this->ic_solicitacaoCritica->FormValue;
		$this->nu_ambienteMaisRepresentativo->CurrentValue = $this->nu_ambienteMaisRepresentativo->FormValue;
		$this->qt_tamBase->CurrentValue = $this->qt_tamBase->FormValue;
		$this->ic_modeloCocomo->CurrentValue = $this->ic_modeloCocomo->FormValue;
		$this->nu_metPrazo->CurrentValue = $this->nu_metPrazo->FormValue;
		$this->vr_doPf->CurrentValue = $this->vr_doPf->FormValue;
		$this->pz_estimadoMeses->CurrentValue = $this->pz_estimadoMeses->FormValue;
		$this->pz_estimadoDias->CurrentValue = $this->pz_estimadoDias->FormValue;
		$this->vr_ipMaximo->CurrentValue = $this->vr_ipMaximo->FormValue;
		$this->vr_ipMedio->CurrentValue = $this->vr_ipMedio->FormValue;
		$this->vr_ipMinimo->CurrentValue = $this->vr_ipMinimo->FormValue;
		$this->vr_ipInformado->CurrentValue = $this->vr_ipInformado->FormValue;
		$this->qt_esforco->CurrentValue = $this->qt_esforco->FormValue;
		$this->vr_custoDesenv->CurrentValue = $this->vr_custoDesenv->FormValue;
		$this->vr_outrosCustos->CurrentValue = $this->vr_outrosCustos->FormValue;
		$this->vr_custoTotal->CurrentValue = $this->vr_custoTotal->FormValue;
		$this->qt_tamBaseFaturamento->CurrentValue = $this->qt_tamBaseFaturamento->FormValue;
		$this->qt_recursosEquipe->CurrentValue = $this->qt_recursosEquipe->FormValue;
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
		$this->nu_solMetricas->setDbValue($rs->fields('nu_solMetricas'));
		$this->nu_estimativa->setDbValue($rs->fields('nu_estimativa'));
		$this->ic_solicitacaoCritica->setDbValue($rs->fields('ic_solicitacaoCritica'));
		$this->nu_ambienteMaisRepresentativo->setDbValue($rs->fields('nu_ambienteMaisRepresentativo'));
		$this->qt_tamBase->setDbValue($rs->fields('qt_tamBase'));
		$this->ic_modeloCocomo->setDbValue($rs->fields('ic_modeloCocomo'));
		$this->nu_metPrazo->setDbValue($rs->fields('nu_metPrazo'));
		$this->vr_doPf->setDbValue($rs->fields('vr_doPf'));
		$this->pz_estimadoMeses->setDbValue($rs->fields('pz_estimadoMeses'));
		$this->pz_estimadoDias->setDbValue($rs->fields('pz_estimadoDias'));
		$this->vr_ipMaximo->setDbValue($rs->fields('vr_ipMaximo'));
		$this->vr_ipMedio->setDbValue($rs->fields('vr_ipMedio'));
		$this->vr_ipMinimo->setDbValue($rs->fields('vr_ipMinimo'));
		$this->vr_ipInformado->setDbValue($rs->fields('vr_ipInformado'));
		$this->qt_esforco->setDbValue($rs->fields('qt_esforco'));
		$this->vr_custoDesenv->setDbValue($rs->fields('vr_custoDesenv'));
		$this->vr_outrosCustos->setDbValue($rs->fields('vr_outrosCustos'));
		$this->vr_custoTotal->setDbValue($rs->fields('vr_custoTotal'));
		$this->qt_tamBaseFaturamento->setDbValue($rs->fields('qt_tamBaseFaturamento'));
		$this->qt_recursosEquipe->setDbValue($rs->fields('qt_recursosEquipe'));
		$this->ds_observacoes->setDbValue($rs->fields('ds_observacoes'));
		$this->ic_bloqueio->setDbValue($rs->fields('ic_bloqueio'));
		$this->nu_altRELY->setDbValue($rs->fields('nu_altRELY'));
		$this->nu_altDATA->setDbValue($rs->fields('nu_altDATA'));
		$this->nu_altCPLX1->setDbValue($rs->fields('nu_altCPLX1'));
		$this->nu_altCPLX2->setDbValue($rs->fields('nu_altCPLX2'));
		$this->nu_altCPLX3->setDbValue($rs->fields('nu_altCPLX3'));
		$this->nu_altCPLX4->setDbValue($rs->fields('nu_altCPLX4'));
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
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_solMetricas->DbValue = $row['nu_solMetricas'];
		$this->nu_estimativa->DbValue = $row['nu_estimativa'];
		$this->ic_solicitacaoCritica->DbValue = $row['ic_solicitacaoCritica'];
		$this->nu_ambienteMaisRepresentativo->DbValue = $row['nu_ambienteMaisRepresentativo'];
		$this->qt_tamBase->DbValue = $row['qt_tamBase'];
		$this->ic_modeloCocomo->DbValue = $row['ic_modeloCocomo'];
		$this->nu_metPrazo->DbValue = $row['nu_metPrazo'];
		$this->vr_doPf->DbValue = $row['vr_doPf'];
		$this->pz_estimadoMeses->DbValue = $row['pz_estimadoMeses'];
		$this->pz_estimadoDias->DbValue = $row['pz_estimadoDias'];
		$this->vr_ipMaximo->DbValue = $row['vr_ipMaximo'];
		$this->vr_ipMedio->DbValue = $row['vr_ipMedio'];
		$this->vr_ipMinimo->DbValue = $row['vr_ipMinimo'];
		$this->vr_ipInformado->DbValue = $row['vr_ipInformado'];
		$this->qt_esforco->DbValue = $row['qt_esforco'];
		$this->vr_custoDesenv->DbValue = $row['vr_custoDesenv'];
		$this->vr_outrosCustos->DbValue = $row['vr_outrosCustos'];
		$this->vr_custoTotal->DbValue = $row['vr_custoTotal'];
		$this->qt_tamBaseFaturamento->DbValue = $row['qt_tamBaseFaturamento'];
		$this->qt_recursosEquipe->DbValue = $row['qt_recursosEquipe'];
		$this->ds_observacoes->DbValue = $row['ds_observacoes'];
		$this->ic_bloqueio->DbValue = $row['ic_bloqueio'];
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
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		$arKeys[] = $this->RowOldKey;
		$cnt = count($arKeys);
		if ($cnt >= 1) {
			if (strval($arKeys[0]) <> "")
				$this->nu_estimativa->CurrentValue = strval($arKeys[0]); // nu_estimativa
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

		if ($this->qt_tamBase->FormValue == $this->qt_tamBase->CurrentValue && is_numeric(ew_StrToFloat($this->qt_tamBase->CurrentValue)))
			$this->qt_tamBase->CurrentValue = ew_StrToFloat($this->qt_tamBase->CurrentValue);

		// Convert decimal values if posted back
		if ($this->pz_estimadoMeses->FormValue == $this->pz_estimadoMeses->CurrentValue && is_numeric(ew_StrToFloat($this->pz_estimadoMeses->CurrentValue)))
			$this->pz_estimadoMeses->CurrentValue = ew_StrToFloat($this->pz_estimadoMeses->CurrentValue);

		// Convert decimal values if posted back
		if ($this->pz_estimadoDias->FormValue == $this->pz_estimadoDias->CurrentValue && is_numeric(ew_StrToFloat($this->pz_estimadoDias->CurrentValue)))
			$this->pz_estimadoDias->CurrentValue = ew_StrToFloat($this->pz_estimadoDias->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_ipMaximo->FormValue == $this->vr_ipMaximo->CurrentValue && is_numeric(ew_StrToFloat($this->vr_ipMaximo->CurrentValue)))
			$this->vr_ipMaximo->CurrentValue = ew_StrToFloat($this->vr_ipMaximo->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_ipMedio->FormValue == $this->vr_ipMedio->CurrentValue && is_numeric(ew_StrToFloat($this->vr_ipMedio->CurrentValue)))
			$this->vr_ipMedio->CurrentValue = ew_StrToFloat($this->vr_ipMedio->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_ipMinimo->FormValue == $this->vr_ipMinimo->CurrentValue && is_numeric(ew_StrToFloat($this->vr_ipMinimo->CurrentValue)))
			$this->vr_ipMinimo->CurrentValue = ew_StrToFloat($this->vr_ipMinimo->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_esforco->FormValue == $this->qt_esforco->CurrentValue && is_numeric(ew_StrToFloat($this->qt_esforco->CurrentValue)))
			$this->qt_esforco->CurrentValue = ew_StrToFloat($this->qt_esforco->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_custoDesenv->FormValue == $this->vr_custoDesenv->CurrentValue && is_numeric(ew_StrToFloat($this->vr_custoDesenv->CurrentValue)))
			$this->vr_custoDesenv->CurrentValue = ew_StrToFloat($this->vr_custoDesenv->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_outrosCustos->FormValue == $this->vr_outrosCustos->CurrentValue && is_numeric(ew_StrToFloat($this->vr_outrosCustos->CurrentValue)))
			$this->vr_outrosCustos->CurrentValue = ew_StrToFloat($this->vr_outrosCustos->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_custoTotal->FormValue == $this->vr_custoTotal->CurrentValue && is_numeric(ew_StrToFloat($this->vr_custoTotal->CurrentValue)))
			$this->vr_custoTotal->CurrentValue = ew_StrToFloat($this->vr_custoTotal->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_tamBaseFaturamento->FormValue == $this->qt_tamBaseFaturamento->CurrentValue && is_numeric(ew_StrToFloat($this->qt_tamBaseFaturamento->CurrentValue)))
			$this->qt_tamBaseFaturamento->CurrentValue = ew_StrToFloat($this->qt_tamBaseFaturamento->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_recursosEquipe->FormValue == $this->qt_recursosEquipe->CurrentValue && is_numeric(ew_StrToFloat($this->qt_recursosEquipe->CurrentValue)))
			$this->qt_recursosEquipe->CurrentValue = ew_StrToFloat($this->qt_recursosEquipe->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_solMetricas
		// nu_estimativa
		// ic_solicitacaoCritica
		// nu_ambienteMaisRepresentativo
		// qt_tamBase
		// ic_modeloCocomo
		// nu_metPrazo
		// vr_doPf
		// pz_estimadoMeses
		// pz_estimadoDias
		// vr_ipMaximo
		// vr_ipMedio
		// vr_ipMinimo
		// vr_ipInformado
		// qt_esforco
		// vr_custoDesenv
		// vr_outrosCustos
		// vr_custoTotal
		// qt_tamBaseFaturamento
		// qt_recursosEquipe
		// ds_observacoes
		// ic_bloqueio

		$this->ic_bloqueio->CellCssStyle = "white-space: nowrap;";

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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_solMetricas
			if (strval($this->nu_solMetricas->CurrentValue) <> "") {
				$sFilterWrk = "[nu_solMetricas]" . ew_SearchString("=", $this->nu_solMetricas->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_solMetricas], [nu_solMetricas] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[solicitacaoMetricas]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_solMetricas, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_solMetricas] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_solMetricas->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_solMetricas->ViewValue = $this->nu_solMetricas->CurrentValue;
				}
			} else {
				$this->nu_solMetricas->ViewValue = NULL;
			}
			$this->nu_solMetricas->ViewCustomAttributes = "";

			// nu_estimativa
			$this->nu_estimativa->ViewValue = $this->nu_estimativa->CurrentValue;
			$this->nu_estimativa->ViewCustomAttributes = "";

			// ic_solicitacaoCritica
			if (strval($this->ic_solicitacaoCritica->CurrentValue) <> "") {
				switch ($this->ic_solicitacaoCritica->CurrentValue) {
					case $this->ic_solicitacaoCritica->FldTagValue(1):
						$this->ic_solicitacaoCritica->ViewValue = $this->ic_solicitacaoCritica->FldTagCaption(1) <> "" ? $this->ic_solicitacaoCritica->FldTagCaption(1) : $this->ic_solicitacaoCritica->CurrentValue;
						break;
					case $this->ic_solicitacaoCritica->FldTagValue(2):
						$this->ic_solicitacaoCritica->ViewValue = $this->ic_solicitacaoCritica->FldTagCaption(2) <> "" ? $this->ic_solicitacaoCritica->FldTagCaption(2) : $this->ic_solicitacaoCritica->CurrentValue;
						break;
					default:
						$this->ic_solicitacaoCritica->ViewValue = $this->ic_solicitacaoCritica->CurrentValue;
				}
			} else {
				$this->ic_solicitacaoCritica->ViewValue = NULL;
			}
			$this->ic_solicitacaoCritica->ViewCustomAttributes = "";

			// nu_ambienteMaisRepresentativo
			$this->nu_ambienteMaisRepresentativo->ViewValue = $this->nu_ambienteMaisRepresentativo->CurrentValue;
			if (strval($this->nu_ambienteMaisRepresentativo->CurrentValue) <> "") {
				$sFilterWrk = "[nu_ambiente]" . ew_SearchString("=", $this->nu_ambienteMaisRepresentativo->CurrentValue, EW_DATATYPE_NUMBER);
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
			$this->Lookup_Selecting($this->nu_ambienteMaisRepresentativo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_ambiente] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_ambienteMaisRepresentativo->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_ambienteMaisRepresentativo->ViewValue = $this->nu_ambienteMaisRepresentativo->CurrentValue;
				}
			} else {
				$this->nu_ambienteMaisRepresentativo->ViewValue = NULL;
			}
			$this->nu_ambienteMaisRepresentativo->ViewCustomAttributes = "";

			// qt_tamBase
			$this->qt_tamBase->ViewValue = $this->qt_tamBase->CurrentValue;
			$this->qt_tamBase->ViewCustomAttributes = "";

			// ic_modeloCocomo
			if (strval($this->ic_modeloCocomo->CurrentValue) <> "") {
				switch ($this->ic_modeloCocomo->CurrentValue) {
					case $this->ic_modeloCocomo->FldTagValue(1):
						$this->ic_modeloCocomo->ViewValue = $this->ic_modeloCocomo->FldTagCaption(1) <> "" ? $this->ic_modeloCocomo->FldTagCaption(1) : $this->ic_modeloCocomo->CurrentValue;
						break;
					case $this->ic_modeloCocomo->FldTagValue(2):
						$this->ic_modeloCocomo->ViewValue = $this->ic_modeloCocomo->FldTagCaption(2) <> "" ? $this->ic_modeloCocomo->FldTagCaption(2) : $this->ic_modeloCocomo->CurrentValue;
						break;
					default:
						$this->ic_modeloCocomo->ViewValue = $this->ic_modeloCocomo->CurrentValue;
				}
			} else {
				$this->ic_modeloCocomo->ViewValue = NULL;
			}
			$this->ic_modeloCocomo->ViewCustomAttributes = "";

			// nu_metPrazo
			if (strval($this->nu_metPrazo->CurrentValue) <> "") {
				switch ($this->nu_metPrazo->CurrentValue) {
					case $this->nu_metPrazo->FldTagValue(1):
						$this->nu_metPrazo->ViewValue = $this->nu_metPrazo->FldTagCaption(1) <> "" ? $this->nu_metPrazo->FldTagCaption(1) : $this->nu_metPrazo->CurrentValue;
						break;
					case $this->nu_metPrazo->FldTagValue(2):
						$this->nu_metPrazo->ViewValue = $this->nu_metPrazo->FldTagCaption(2) <> "" ? $this->nu_metPrazo->FldTagCaption(2) : $this->nu_metPrazo->CurrentValue;
						break;
					case $this->nu_metPrazo->FldTagValue(3):
						$this->nu_metPrazo->ViewValue = $this->nu_metPrazo->FldTagCaption(3) <> "" ? $this->nu_metPrazo->FldTagCaption(3) : $this->nu_metPrazo->CurrentValue;
						break;
					case $this->nu_metPrazo->FldTagValue(4):
						$this->nu_metPrazo->ViewValue = $this->nu_metPrazo->FldTagCaption(4) <> "" ? $this->nu_metPrazo->FldTagCaption(4) : $this->nu_metPrazo->CurrentValue;
						break;
					case $this->nu_metPrazo->FldTagValue(5):
						$this->nu_metPrazo->ViewValue = $this->nu_metPrazo->FldTagCaption(5) <> "" ? $this->nu_metPrazo->FldTagCaption(5) : $this->nu_metPrazo->CurrentValue;
						break;
					default:
						$this->nu_metPrazo->ViewValue = $this->nu_metPrazo->CurrentValue;
				}
			} else {
				$this->nu_metPrazo->ViewValue = NULL;
			}
			$this->nu_metPrazo->ViewCustomAttributes = "";

			// vr_doPf
			$this->vr_doPf->ViewValue = $this->vr_doPf->CurrentValue;
			$this->vr_doPf->ViewCustomAttributes = "";

			// pz_estimadoMeses
			$this->pz_estimadoMeses->ViewValue = $this->pz_estimadoMeses->CurrentValue;
			$this->pz_estimadoMeses->ViewCustomAttributes = "";

			// pz_estimadoDias
			$this->pz_estimadoDias->ViewValue = $this->pz_estimadoDias->CurrentValue;
			$this->pz_estimadoDias->ViewCustomAttributes = "";

			// vr_ipMaximo
			$this->vr_ipMaximo->ViewValue = $this->vr_ipMaximo->CurrentValue;
			$this->vr_ipMaximo->ViewCustomAttributes = "";

			// vr_ipMedio
			$this->vr_ipMedio->ViewValue = $this->vr_ipMedio->CurrentValue;
			$this->vr_ipMedio->ViewCustomAttributes = "";

			// vr_ipMinimo
			$this->vr_ipMinimo->ViewValue = $this->vr_ipMinimo->CurrentValue;
			$this->vr_ipMinimo->ViewCustomAttributes = "";

			// vr_ipInformado
			$this->vr_ipInformado->ViewValue = $this->vr_ipInformado->CurrentValue;
			$this->vr_ipInformado->ViewCustomAttributes = "";

			// qt_esforco
			$this->qt_esforco->ViewValue = $this->qt_esforco->CurrentValue;
			$this->qt_esforco->ViewCustomAttributes = "";

			// vr_custoDesenv
			$this->vr_custoDesenv->ViewValue = $this->vr_custoDesenv->CurrentValue;
			$this->vr_custoDesenv->ViewCustomAttributes = "";

			// vr_outrosCustos
			$this->vr_outrosCustos->ViewValue = $this->vr_outrosCustos->CurrentValue;
			$this->vr_outrosCustos->ViewCustomAttributes = "";

			// vr_custoTotal
			$this->vr_custoTotal->ViewValue = $this->vr_custoTotal->CurrentValue;
			$this->vr_custoTotal->ViewCustomAttributes = "";

			// qt_tamBaseFaturamento
			$this->qt_tamBaseFaturamento->ViewValue = $this->qt_tamBaseFaturamento->CurrentValue;
			$this->qt_tamBaseFaturamento->ViewCustomAttributes = "";

			// qt_recursosEquipe
			$this->qt_recursosEquipe->ViewValue = $this->qt_recursosEquipe->CurrentValue;
			$this->qt_recursosEquipe->ViewCustomAttributes = "";

			// ic_bloqueio
			$this->ic_bloqueio->ViewValue = $this->ic_bloqueio->CurrentValue;
			$this->ic_bloqueio->ViewCustomAttributes = "";

			// nu_altRELY
			if (strval($this->nu_altRELY->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altRELY->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[co_questao]=(select co_quePREC FROM ambiente_valoracao where nu_ambiente = '2' and nu_versaoValoracao = '1') AND [ic_ativo]='S'";
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[co_questao]=(select co_queDATA FROM ambiente_valoracao where nu_ambiente = '2' and nu_versaoValoracao = '1') AND [ic_ativo]='S'";
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX2, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altCPLX2->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_altCPLX2->ViewValue = $this->nu_altCPLX2->CurrentValue;
				}
			} else {
				$this->nu_altCPLX2->ViewValue = NULL;
			}
			$this->nu_altCPLX2->ViewCustomAttributes = "";

			// nu_altCPLX3
			if (strval($this->nu_altCPLX3->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX3->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX3, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altCPLX3->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_altCPLX3->ViewValue = $this->nu_altCPLX3->CurrentValue;
				}
			} else {
				$this->nu_altCPLX3->ViewValue = NULL;
			}
			$this->nu_altCPLX3->ViewCustomAttributes = "";

			// nu_altCPLX4
			if (strval($this->nu_altCPLX4->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX4->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX4, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altCPLX4->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_altCPLX4->ViewValue = $this->nu_altCPLX4->CurrentValue;
				}
			} else {
				$this->nu_altCPLX4->ViewValue = NULL;
			}
			$this->nu_altCPLX4->ViewCustomAttributes = "";

			// nu_altCPLX5
			if (strval($this->nu_altCPLX5->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX5->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX5, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altCPLX5->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altDOCU, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altDOCU->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altRUSE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altRUSE->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altTIME, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altTIME->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altSTOR, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altSTOR->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPVOL, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altPVOL->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altACAP, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altACAP->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPCAP, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altPCAP->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPCON, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altPCON->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altAPEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altAPEX->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPLEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altPLEX->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altLTEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altLTEX->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altTOOL, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altTOOL->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altSITE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altSITE->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_altSITE->ViewValue = $this->nu_altSITE->CurrentValue;
				}
			} else {
				$this->nu_altSITE->ViewValue = NULL;
			}
			$this->nu_altSITE->ViewCustomAttributes = "";

			// ic_solicitacaoCritica
			$this->ic_solicitacaoCritica->LinkCustomAttributes = "";
			$this->ic_solicitacaoCritica->HrefValue = "";
			$this->ic_solicitacaoCritica->TooltipValue = "";

			// nu_ambienteMaisRepresentativo
			$this->nu_ambienteMaisRepresentativo->LinkCustomAttributes = "";
			$this->nu_ambienteMaisRepresentativo->HrefValue = "";
			$this->nu_ambienteMaisRepresentativo->TooltipValue = "";

			// qt_tamBase
			$this->qt_tamBase->LinkCustomAttributes = "";
			$this->qt_tamBase->HrefValue = "";
			$this->qt_tamBase->TooltipValue = "";

			// ic_modeloCocomo
			$this->ic_modeloCocomo->LinkCustomAttributes = "";
			$this->ic_modeloCocomo->HrefValue = "";
			$this->ic_modeloCocomo->TooltipValue = "";

			// nu_metPrazo
			$this->nu_metPrazo->LinkCustomAttributes = "";
			$this->nu_metPrazo->HrefValue = "";
			$this->nu_metPrazo->TooltipValue = "";

			// vr_doPf
			$this->vr_doPf->LinkCustomAttributes = "";
			$this->vr_doPf->HrefValue = "";
			$this->vr_doPf->TooltipValue = "";

			// pz_estimadoMeses
			$this->pz_estimadoMeses->LinkCustomAttributes = "";
			$this->pz_estimadoMeses->HrefValue = "";
			$this->pz_estimadoMeses->TooltipValue = "";

			// pz_estimadoDias
			$this->pz_estimadoDias->LinkCustomAttributes = "";
			$this->pz_estimadoDias->HrefValue = "";
			$this->pz_estimadoDias->TooltipValue = "";

			// vr_ipMaximo
			$this->vr_ipMaximo->LinkCustomAttributes = "";
			$this->vr_ipMaximo->HrefValue = "";
			$this->vr_ipMaximo->TooltipValue = "";

			// vr_ipMedio
			$this->vr_ipMedio->LinkCustomAttributes = "";
			$this->vr_ipMedio->HrefValue = "";
			$this->vr_ipMedio->TooltipValue = "";

			// vr_ipMinimo
			$this->vr_ipMinimo->LinkCustomAttributes = "";
			$this->vr_ipMinimo->HrefValue = "";
			$this->vr_ipMinimo->TooltipValue = "";

			// vr_ipInformado
			$this->vr_ipInformado->LinkCustomAttributes = "";
			$this->vr_ipInformado->HrefValue = "";
			$this->vr_ipInformado->TooltipValue = "";

			// qt_esforco
			$this->qt_esforco->LinkCustomAttributes = "";
			$this->qt_esforco->HrefValue = "";
			$this->qt_esforco->TooltipValue = "";

			// vr_custoDesenv
			$this->vr_custoDesenv->LinkCustomAttributes = "";
			$this->vr_custoDesenv->HrefValue = "";
			$this->vr_custoDesenv->TooltipValue = "";

			// vr_outrosCustos
			$this->vr_outrosCustos->LinkCustomAttributes = "";
			$this->vr_outrosCustos->HrefValue = "";
			$this->vr_outrosCustos->TooltipValue = "";

			// vr_custoTotal
			$this->vr_custoTotal->LinkCustomAttributes = "";
			$this->vr_custoTotal->HrefValue = "";
			$this->vr_custoTotal->TooltipValue = "";

			// qt_tamBaseFaturamento
			$this->qt_tamBaseFaturamento->LinkCustomAttributes = "";
			$this->qt_tamBaseFaturamento->HrefValue = "";
			$this->qt_tamBaseFaturamento->TooltipValue = "";

			// qt_recursosEquipe
			$this->qt_recursosEquipe->LinkCustomAttributes = "";
			$this->qt_recursosEquipe->HrefValue = "";
			$this->qt_recursosEquipe->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// ic_solicitacaoCritica
			$this->ic_solicitacaoCritica->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_solicitacaoCritica->FldTagValue(1), $this->ic_solicitacaoCritica->FldTagCaption(1) <> "" ? $this->ic_solicitacaoCritica->FldTagCaption(1) : $this->ic_solicitacaoCritica->FldTagValue(1));
			$arwrk[] = array($this->ic_solicitacaoCritica->FldTagValue(2), $this->ic_solicitacaoCritica->FldTagCaption(2) <> "" ? $this->ic_solicitacaoCritica->FldTagCaption(2) : $this->ic_solicitacaoCritica->FldTagValue(2));
			$this->ic_solicitacaoCritica->EditValue = $arwrk;

			// nu_ambienteMaisRepresentativo
			$this->nu_ambienteMaisRepresentativo->EditCustomAttributes = "";
			$this->nu_ambienteMaisRepresentativo->EditValue = ew_HtmlEncode($this->nu_ambienteMaisRepresentativo->CurrentValue);
			if (strval($this->nu_ambienteMaisRepresentativo->CurrentValue) <> "") {
				$sFilterWrk = "[nu_ambiente]" . ew_SearchString("=", $this->nu_ambienteMaisRepresentativo->CurrentValue, EW_DATATYPE_NUMBER);
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
			$this->Lookup_Selecting($this->nu_ambienteMaisRepresentativo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_ambiente] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_ambienteMaisRepresentativo->EditValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_ambienteMaisRepresentativo->EditValue = $this->nu_ambienteMaisRepresentativo->CurrentValue;
				}
			} else {
				$this->nu_ambienteMaisRepresentativo->EditValue = NULL;
			}
			$this->nu_ambienteMaisRepresentativo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_ambienteMaisRepresentativo->FldCaption()));

			// qt_tamBase
			$this->qt_tamBase->EditCustomAttributes = "readonly";
			$this->qt_tamBase->EditValue = ew_HtmlEncode($this->qt_tamBase->CurrentValue);
			$this->qt_tamBase->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_tamBase->FldCaption()));
			if (strval($this->qt_tamBase->EditValue) <> "" && is_numeric($this->qt_tamBase->EditValue)) {
			$this->qt_tamBase->EditValue = ew_FormatNumber($this->qt_tamBase->EditValue, -2, -1, -2, 0);
			$this->qt_tamBase->OldValue = $this->qt_tamBase->EditValue;
			}

			// ic_modeloCocomo
			$this->ic_modeloCocomo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_modeloCocomo->FldTagValue(1), $this->ic_modeloCocomo->FldTagCaption(1) <> "" ? $this->ic_modeloCocomo->FldTagCaption(1) : $this->ic_modeloCocomo->FldTagValue(1));
			$arwrk[] = array($this->ic_modeloCocomo->FldTagValue(2), $this->ic_modeloCocomo->FldTagCaption(2) <> "" ? $this->ic_modeloCocomo->FldTagCaption(2) : $this->ic_modeloCocomo->FldTagValue(2));
			$this->ic_modeloCocomo->EditValue = $arwrk;

			// nu_metPrazo
			$this->nu_metPrazo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->nu_metPrazo->FldTagValue(1), $this->nu_metPrazo->FldTagCaption(1) <> "" ? $this->nu_metPrazo->FldTagCaption(1) : $this->nu_metPrazo->FldTagValue(1));
			$arwrk[] = array($this->nu_metPrazo->FldTagValue(2), $this->nu_metPrazo->FldTagCaption(2) <> "" ? $this->nu_metPrazo->FldTagCaption(2) : $this->nu_metPrazo->FldTagValue(2));
			$arwrk[] = array($this->nu_metPrazo->FldTagValue(3), $this->nu_metPrazo->FldTagCaption(3) <> "" ? $this->nu_metPrazo->FldTagCaption(3) : $this->nu_metPrazo->FldTagValue(3));
			$arwrk[] = array($this->nu_metPrazo->FldTagValue(4), $this->nu_metPrazo->FldTagCaption(4) <> "" ? $this->nu_metPrazo->FldTagCaption(4) : $this->nu_metPrazo->FldTagValue(4));
			$arwrk[] = array($this->nu_metPrazo->FldTagValue(5), $this->nu_metPrazo->FldTagCaption(5) <> "" ? $this->nu_metPrazo->FldTagCaption(5) : $this->nu_metPrazo->FldTagValue(5));
			$this->nu_metPrazo->EditValue = $arwrk;

			// vr_doPf
			$this->vr_doPf->EditCustomAttributes = "";
			$this->vr_doPf->EditValue = ew_HtmlEncode($this->vr_doPf->CurrentValue);
			$this->vr_doPf->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_doPf->FldCaption()));

			// pz_estimadoMeses
			$this->pz_estimadoMeses->EditCustomAttributes = "readonly";
			$this->pz_estimadoMeses->EditValue = ew_HtmlEncode($this->pz_estimadoMeses->CurrentValue);
			$this->pz_estimadoMeses->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->pz_estimadoMeses->FldCaption()));
			if (strval($this->pz_estimadoMeses->EditValue) <> "" && is_numeric($this->pz_estimadoMeses->EditValue)) {
			$this->pz_estimadoMeses->EditValue = ew_FormatNumber($this->pz_estimadoMeses->EditValue, -2, -1, -2, 0);
			$this->pz_estimadoMeses->OldValue = $this->pz_estimadoMeses->EditValue;
			}

			// pz_estimadoDias
			$this->pz_estimadoDias->EditCustomAttributes = "readonly";
			$this->pz_estimadoDias->EditValue = ew_HtmlEncode($this->pz_estimadoDias->CurrentValue);
			$this->pz_estimadoDias->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->pz_estimadoDias->FldCaption()));
			if (strval($this->pz_estimadoDias->EditValue) <> "" && is_numeric($this->pz_estimadoDias->EditValue)) {
			$this->pz_estimadoDias->EditValue = ew_FormatNumber($this->pz_estimadoDias->EditValue, -2, -1, -2, 0);
			$this->pz_estimadoDias->OldValue = $this->pz_estimadoDias->EditValue;
			}

			// vr_ipMaximo
			$this->vr_ipMaximo->EditCustomAttributes = "readonly";
			$this->vr_ipMaximo->EditValue = ew_HtmlEncode($this->vr_ipMaximo->CurrentValue);
			$this->vr_ipMaximo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_ipMaximo->FldCaption()));
			if (strval($this->vr_ipMaximo->EditValue) <> "" && is_numeric($this->vr_ipMaximo->EditValue)) {
			$this->vr_ipMaximo->EditValue = ew_FormatNumber($this->vr_ipMaximo->EditValue, -2, -1, -2, 0);
			$this->vr_ipMaximo->OldValue = $this->vr_ipMaximo->EditValue;
			}

			// vr_ipMedio
			$this->vr_ipMedio->EditCustomAttributes = "readonly";
			$this->vr_ipMedio->EditValue = ew_HtmlEncode($this->vr_ipMedio->CurrentValue);
			$this->vr_ipMedio->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_ipMedio->FldCaption()));
			if (strval($this->vr_ipMedio->EditValue) <> "" && is_numeric($this->vr_ipMedio->EditValue)) {
			$this->vr_ipMedio->EditValue = ew_FormatNumber($this->vr_ipMedio->EditValue, -2, -1, -2, 0);
			$this->vr_ipMedio->OldValue = $this->vr_ipMedio->EditValue;
			}

			// vr_ipMinimo
			$this->vr_ipMinimo->EditCustomAttributes = "readonly";
			$this->vr_ipMinimo->EditValue = ew_HtmlEncode($this->vr_ipMinimo->CurrentValue);
			$this->vr_ipMinimo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_ipMinimo->FldCaption()));
			if (strval($this->vr_ipMinimo->EditValue) <> "" && is_numeric($this->vr_ipMinimo->EditValue)) {
			$this->vr_ipMinimo->EditValue = ew_FormatNumber($this->vr_ipMinimo->EditValue, -2, -1, -2, 0);
			$this->vr_ipMinimo->OldValue = $this->vr_ipMinimo->EditValue;
			}

			// vr_ipInformado
			$this->vr_ipInformado->EditCustomAttributes = "";
			$this->vr_ipInformado->EditValue = ew_HtmlEncode($this->vr_ipInformado->CurrentValue);
			$this->vr_ipInformado->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_ipInformado->FldCaption()));

			// qt_esforco
			$this->qt_esforco->EditCustomAttributes = "readonly";
			$this->qt_esforco->EditValue = ew_HtmlEncode($this->qt_esforco->CurrentValue);
			$this->qt_esforco->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_esforco->FldCaption()));
			if (strval($this->qt_esforco->EditValue) <> "" && is_numeric($this->qt_esforco->EditValue)) {
			$this->qt_esforco->EditValue = ew_FormatNumber($this->qt_esforco->EditValue, -2, -1, -2, 0);
			$this->qt_esforco->OldValue = $this->qt_esforco->EditValue;
			}

			// vr_custoDesenv
			$this->vr_custoDesenv->EditCustomAttributes = "readonly";
			$this->vr_custoDesenv->EditValue = ew_HtmlEncode($this->vr_custoDesenv->CurrentValue);
			$this->vr_custoDesenv->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_custoDesenv->FldCaption()));
			if (strval($this->vr_custoDesenv->EditValue) <> "" && is_numeric($this->vr_custoDesenv->EditValue)) {
			$this->vr_custoDesenv->EditValue = ew_FormatNumber($this->vr_custoDesenv->EditValue, -2, -1, -2, 0);
			$this->vr_custoDesenv->OldValue = $this->vr_custoDesenv->EditValue;
			}

			// vr_outrosCustos
			$this->vr_outrosCustos->EditCustomAttributes = "";
			$this->vr_outrosCustos->EditValue = ew_HtmlEncode($this->vr_outrosCustos->CurrentValue);
			$this->vr_outrosCustos->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_outrosCustos->FldCaption()));
			if (strval($this->vr_outrosCustos->EditValue) <> "" && is_numeric($this->vr_outrosCustos->EditValue)) {
			$this->vr_outrosCustos->EditValue = ew_FormatNumber($this->vr_outrosCustos->EditValue, -2, -1, -2, 0);
			$this->vr_outrosCustos->OldValue = $this->vr_outrosCustos->EditValue;
			}

			// vr_custoTotal
			$this->vr_custoTotal->EditCustomAttributes = "readonly";
			$this->vr_custoTotal->EditValue = ew_HtmlEncode($this->vr_custoTotal->CurrentValue);
			$this->vr_custoTotal->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_custoTotal->FldCaption()));
			if (strval($this->vr_custoTotal->EditValue) <> "" && is_numeric($this->vr_custoTotal->EditValue)) {
			$this->vr_custoTotal->EditValue = ew_FormatNumber($this->vr_custoTotal->EditValue, -2, -1, -2, 0);
			$this->vr_custoTotal->OldValue = $this->vr_custoTotal->EditValue;
			}

			// qt_tamBaseFaturamento
			$this->qt_tamBaseFaturamento->EditCustomAttributes = "readonly";
			$this->qt_tamBaseFaturamento->EditValue = ew_HtmlEncode($this->qt_tamBaseFaturamento->CurrentValue);
			$this->qt_tamBaseFaturamento->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_tamBaseFaturamento->FldCaption()));
			if (strval($this->qt_tamBaseFaturamento->EditValue) <> "" && is_numeric($this->qt_tamBaseFaturamento->EditValue)) {
			$this->qt_tamBaseFaturamento->EditValue = ew_FormatNumber($this->qt_tamBaseFaturamento->EditValue, -2, -1, -2, 0);
			$this->qt_tamBaseFaturamento->OldValue = $this->qt_tamBaseFaturamento->EditValue;
			}

			// qt_recursosEquipe
			$this->qt_recursosEquipe->EditCustomAttributes = "readonly";
			$this->qt_recursosEquipe->EditValue = ew_HtmlEncode($this->qt_recursosEquipe->CurrentValue);
			$this->qt_recursosEquipe->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_recursosEquipe->FldCaption()));
			if (strval($this->qt_recursosEquipe->EditValue) <> "" && is_numeric($this->qt_recursosEquipe->EditValue)) {
			$this->qt_recursosEquipe->EditValue = ew_FormatNumber($this->qt_recursosEquipe->EditValue, -2, -1, -2, 0);
			$this->qt_recursosEquipe->OldValue = $this->qt_recursosEquipe->EditValue;
			}

			// Edit refer script
			// ic_solicitacaoCritica

			$this->ic_solicitacaoCritica->HrefValue = "";

			// nu_ambienteMaisRepresentativo
			$this->nu_ambienteMaisRepresentativo->HrefValue = "";

			// qt_tamBase
			$this->qt_tamBase->HrefValue = "";

			// ic_modeloCocomo
			$this->ic_modeloCocomo->HrefValue = "";

			// nu_metPrazo
			$this->nu_metPrazo->HrefValue = "";

			// vr_doPf
			$this->vr_doPf->HrefValue = "";

			// pz_estimadoMeses
			$this->pz_estimadoMeses->HrefValue = "";

			// pz_estimadoDias
			$this->pz_estimadoDias->HrefValue = "";

			// vr_ipMaximo
			$this->vr_ipMaximo->HrefValue = "";

			// vr_ipMedio
			$this->vr_ipMedio->HrefValue = "";

			// vr_ipMinimo
			$this->vr_ipMinimo->HrefValue = "";

			// vr_ipInformado
			$this->vr_ipInformado->HrefValue = "";

			// qt_esforco
			$this->qt_esforco->HrefValue = "";

			// vr_custoDesenv
			$this->vr_custoDesenv->HrefValue = "";

			// vr_outrosCustos
			$this->vr_outrosCustos->HrefValue = "";

			// vr_custoTotal
			$this->vr_custoTotal->HrefValue = "";

			// qt_tamBaseFaturamento
			$this->qt_tamBaseFaturamento->HrefValue = "";

			// qt_recursosEquipe
			$this->qt_recursosEquipe->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// ic_solicitacaoCritica
			$this->ic_solicitacaoCritica->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_solicitacaoCritica->FldTagValue(1), $this->ic_solicitacaoCritica->FldTagCaption(1) <> "" ? $this->ic_solicitacaoCritica->FldTagCaption(1) : $this->ic_solicitacaoCritica->FldTagValue(1));
			$arwrk[] = array($this->ic_solicitacaoCritica->FldTagValue(2), $this->ic_solicitacaoCritica->FldTagCaption(2) <> "" ? $this->ic_solicitacaoCritica->FldTagCaption(2) : $this->ic_solicitacaoCritica->FldTagValue(2));
			$this->ic_solicitacaoCritica->EditValue = $arwrk;

			// nu_ambienteMaisRepresentativo
			$this->nu_ambienteMaisRepresentativo->EditCustomAttributes = "";
			$this->nu_ambienteMaisRepresentativo->EditValue = ew_HtmlEncode($this->nu_ambienteMaisRepresentativo->CurrentValue);
			if (strval($this->nu_ambienteMaisRepresentativo->CurrentValue) <> "") {
				$sFilterWrk = "[nu_ambiente]" . ew_SearchString("=", $this->nu_ambienteMaisRepresentativo->CurrentValue, EW_DATATYPE_NUMBER);
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
			$this->Lookup_Selecting($this->nu_ambienteMaisRepresentativo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_ambiente] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_ambienteMaisRepresentativo->EditValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_ambienteMaisRepresentativo->EditValue = $this->nu_ambienteMaisRepresentativo->CurrentValue;
				}
			} else {
				$this->nu_ambienteMaisRepresentativo->EditValue = NULL;
			}
			$this->nu_ambienteMaisRepresentativo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_ambienteMaisRepresentativo->FldCaption()));

			// qt_tamBase
			$this->qt_tamBase->EditCustomAttributes = "readonly";
			$this->qt_tamBase->EditValue = ew_HtmlEncode($this->qt_tamBase->CurrentValue);
			$this->qt_tamBase->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_tamBase->FldCaption()));
			if (strval($this->qt_tamBase->EditValue) <> "" && is_numeric($this->qt_tamBase->EditValue)) {
			$this->qt_tamBase->EditValue = ew_FormatNumber($this->qt_tamBase->EditValue, -2, -1, -2, 0);
			$this->qt_tamBase->OldValue = $this->qt_tamBase->EditValue;
			}

			// ic_modeloCocomo
			$this->ic_modeloCocomo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_modeloCocomo->FldTagValue(1), $this->ic_modeloCocomo->FldTagCaption(1) <> "" ? $this->ic_modeloCocomo->FldTagCaption(1) : $this->ic_modeloCocomo->FldTagValue(1));
			$arwrk[] = array($this->ic_modeloCocomo->FldTagValue(2), $this->ic_modeloCocomo->FldTagCaption(2) <> "" ? $this->ic_modeloCocomo->FldTagCaption(2) : $this->ic_modeloCocomo->FldTagValue(2));
			$this->ic_modeloCocomo->EditValue = $arwrk;

			// nu_metPrazo
			$this->nu_metPrazo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->nu_metPrazo->FldTagValue(1), $this->nu_metPrazo->FldTagCaption(1) <> "" ? $this->nu_metPrazo->FldTagCaption(1) : $this->nu_metPrazo->FldTagValue(1));
			$arwrk[] = array($this->nu_metPrazo->FldTagValue(2), $this->nu_metPrazo->FldTagCaption(2) <> "" ? $this->nu_metPrazo->FldTagCaption(2) : $this->nu_metPrazo->FldTagValue(2));
			$arwrk[] = array($this->nu_metPrazo->FldTagValue(3), $this->nu_metPrazo->FldTagCaption(3) <> "" ? $this->nu_metPrazo->FldTagCaption(3) : $this->nu_metPrazo->FldTagValue(3));
			$arwrk[] = array($this->nu_metPrazo->FldTagValue(4), $this->nu_metPrazo->FldTagCaption(4) <> "" ? $this->nu_metPrazo->FldTagCaption(4) : $this->nu_metPrazo->FldTagValue(4));
			$arwrk[] = array($this->nu_metPrazo->FldTagValue(5), $this->nu_metPrazo->FldTagCaption(5) <> "" ? $this->nu_metPrazo->FldTagCaption(5) : $this->nu_metPrazo->FldTagValue(5));
			$this->nu_metPrazo->EditValue = $arwrk;

			// vr_doPf
			$this->vr_doPf->EditCustomAttributes = "";
			$this->vr_doPf->EditValue = ew_HtmlEncode($this->vr_doPf->CurrentValue);
			$this->vr_doPf->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_doPf->FldCaption()));

			// pz_estimadoMeses
			$this->pz_estimadoMeses->EditCustomAttributes = "readonly";
			$this->pz_estimadoMeses->EditValue = ew_HtmlEncode($this->pz_estimadoMeses->CurrentValue);
			$this->pz_estimadoMeses->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->pz_estimadoMeses->FldCaption()));
			if (strval($this->pz_estimadoMeses->EditValue) <> "" && is_numeric($this->pz_estimadoMeses->EditValue)) {
			$this->pz_estimadoMeses->EditValue = ew_FormatNumber($this->pz_estimadoMeses->EditValue, -2, -1, -2, 0);
			$this->pz_estimadoMeses->OldValue = $this->pz_estimadoMeses->EditValue;
			}

			// pz_estimadoDias
			$this->pz_estimadoDias->EditCustomAttributes = "readonly";
			$this->pz_estimadoDias->EditValue = ew_HtmlEncode($this->pz_estimadoDias->CurrentValue);
			$this->pz_estimadoDias->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->pz_estimadoDias->FldCaption()));
			if (strval($this->pz_estimadoDias->EditValue) <> "" && is_numeric($this->pz_estimadoDias->EditValue)) {
			$this->pz_estimadoDias->EditValue = ew_FormatNumber($this->pz_estimadoDias->EditValue, -2, -1, -2, 0);
			$this->pz_estimadoDias->OldValue = $this->pz_estimadoDias->EditValue;
			}

			// vr_ipMaximo
			$this->vr_ipMaximo->EditCustomAttributes = "readonly";
			$this->vr_ipMaximo->EditValue = ew_HtmlEncode($this->vr_ipMaximo->CurrentValue);
			$this->vr_ipMaximo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_ipMaximo->FldCaption()));
			if (strval($this->vr_ipMaximo->EditValue) <> "" && is_numeric($this->vr_ipMaximo->EditValue)) {
			$this->vr_ipMaximo->EditValue = ew_FormatNumber($this->vr_ipMaximo->EditValue, -2, -1, -2, 0);
			$this->vr_ipMaximo->OldValue = $this->vr_ipMaximo->EditValue;
			}

			// vr_ipMedio
			$this->vr_ipMedio->EditCustomAttributes = "readonly";
			$this->vr_ipMedio->EditValue = ew_HtmlEncode($this->vr_ipMedio->CurrentValue);
			$this->vr_ipMedio->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_ipMedio->FldCaption()));
			if (strval($this->vr_ipMedio->EditValue) <> "" && is_numeric($this->vr_ipMedio->EditValue)) {
			$this->vr_ipMedio->EditValue = ew_FormatNumber($this->vr_ipMedio->EditValue, -2, -1, -2, 0);
			$this->vr_ipMedio->OldValue = $this->vr_ipMedio->EditValue;
			}

			// vr_ipMinimo
			$this->vr_ipMinimo->EditCustomAttributes = "readonly";
			$this->vr_ipMinimo->EditValue = ew_HtmlEncode($this->vr_ipMinimo->CurrentValue);
			$this->vr_ipMinimo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_ipMinimo->FldCaption()));
			if (strval($this->vr_ipMinimo->EditValue) <> "" && is_numeric($this->vr_ipMinimo->EditValue)) {
			$this->vr_ipMinimo->EditValue = ew_FormatNumber($this->vr_ipMinimo->EditValue, -2, -1, -2, 0);
			$this->vr_ipMinimo->OldValue = $this->vr_ipMinimo->EditValue;
			}

			// vr_ipInformado
			$this->vr_ipInformado->EditCustomAttributes = "";
			$this->vr_ipInformado->EditValue = ew_HtmlEncode($this->vr_ipInformado->CurrentValue);
			$this->vr_ipInformado->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_ipInformado->FldCaption()));

			// qt_esforco
			$this->qt_esforco->EditCustomAttributes = "readonly";
			$this->qt_esforco->EditValue = ew_HtmlEncode($this->qt_esforco->CurrentValue);
			$this->qt_esforco->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_esforco->FldCaption()));
			if (strval($this->qt_esforco->EditValue) <> "" && is_numeric($this->qt_esforco->EditValue)) {
			$this->qt_esforco->EditValue = ew_FormatNumber($this->qt_esforco->EditValue, -2, -1, -2, 0);
			$this->qt_esforco->OldValue = $this->qt_esforco->EditValue;
			}

			// vr_custoDesenv
			$this->vr_custoDesenv->EditCustomAttributes = "readonly";
			$this->vr_custoDesenv->EditValue = ew_HtmlEncode($this->vr_custoDesenv->CurrentValue);
			$this->vr_custoDesenv->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_custoDesenv->FldCaption()));
			if (strval($this->vr_custoDesenv->EditValue) <> "" && is_numeric($this->vr_custoDesenv->EditValue)) {
			$this->vr_custoDesenv->EditValue = ew_FormatNumber($this->vr_custoDesenv->EditValue, -2, -1, -2, 0);
			$this->vr_custoDesenv->OldValue = $this->vr_custoDesenv->EditValue;
			}

			// vr_outrosCustos
			$this->vr_outrosCustos->EditCustomAttributes = "";
			$this->vr_outrosCustos->EditValue = ew_HtmlEncode($this->vr_outrosCustos->CurrentValue);
			$this->vr_outrosCustos->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_outrosCustos->FldCaption()));
			if (strval($this->vr_outrosCustos->EditValue) <> "" && is_numeric($this->vr_outrosCustos->EditValue)) {
			$this->vr_outrosCustos->EditValue = ew_FormatNumber($this->vr_outrosCustos->EditValue, -2, -1, -2, 0);
			$this->vr_outrosCustos->OldValue = $this->vr_outrosCustos->EditValue;
			}

			// vr_custoTotal
			$this->vr_custoTotal->EditCustomAttributes = "readonly";
			$this->vr_custoTotal->EditValue = ew_HtmlEncode($this->vr_custoTotal->CurrentValue);
			$this->vr_custoTotal->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_custoTotal->FldCaption()));
			if (strval($this->vr_custoTotal->EditValue) <> "" && is_numeric($this->vr_custoTotal->EditValue)) {
			$this->vr_custoTotal->EditValue = ew_FormatNumber($this->vr_custoTotal->EditValue, -2, -1, -2, 0);
			$this->vr_custoTotal->OldValue = $this->vr_custoTotal->EditValue;
			}

			// qt_tamBaseFaturamento
			$this->qt_tamBaseFaturamento->EditCustomAttributes = "readonly";
			$this->qt_tamBaseFaturamento->EditValue = ew_HtmlEncode($this->qt_tamBaseFaturamento->CurrentValue);
			$this->qt_tamBaseFaturamento->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_tamBaseFaturamento->FldCaption()));
			if (strval($this->qt_tamBaseFaturamento->EditValue) <> "" && is_numeric($this->qt_tamBaseFaturamento->EditValue)) {
			$this->qt_tamBaseFaturamento->EditValue = ew_FormatNumber($this->qt_tamBaseFaturamento->EditValue, -2, -1, -2, 0);
			$this->qt_tamBaseFaturamento->OldValue = $this->qt_tamBaseFaturamento->EditValue;
			}

			// qt_recursosEquipe
			$this->qt_recursosEquipe->EditCustomAttributes = "readonly";
			$this->qt_recursosEquipe->EditValue = ew_HtmlEncode($this->qt_recursosEquipe->CurrentValue);
			$this->qt_recursosEquipe->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_recursosEquipe->FldCaption()));
			if (strval($this->qt_recursosEquipe->EditValue) <> "" && is_numeric($this->qt_recursosEquipe->EditValue)) {
			$this->qt_recursosEquipe->EditValue = ew_FormatNumber($this->qt_recursosEquipe->EditValue, -2, -1, -2, 0);
			$this->qt_recursosEquipe->OldValue = $this->qt_recursosEquipe->EditValue;
			}

			// Edit refer script
			// ic_solicitacaoCritica

			$this->ic_solicitacaoCritica->HrefValue = "";

			// nu_ambienteMaisRepresentativo
			$this->nu_ambienteMaisRepresentativo->HrefValue = "";

			// qt_tamBase
			$this->qt_tamBase->HrefValue = "";

			// ic_modeloCocomo
			$this->ic_modeloCocomo->HrefValue = "";

			// nu_metPrazo
			$this->nu_metPrazo->HrefValue = "";

			// vr_doPf
			$this->vr_doPf->HrefValue = "";

			// pz_estimadoMeses
			$this->pz_estimadoMeses->HrefValue = "";

			// pz_estimadoDias
			$this->pz_estimadoDias->HrefValue = "";

			// vr_ipMaximo
			$this->vr_ipMaximo->HrefValue = "";

			// vr_ipMedio
			$this->vr_ipMedio->HrefValue = "";

			// vr_ipMinimo
			$this->vr_ipMinimo->HrefValue = "";

			// vr_ipInformado
			$this->vr_ipInformado->HrefValue = "";

			// qt_esforco
			$this->qt_esforco->HrefValue = "";

			// vr_custoDesenv
			$this->vr_custoDesenv->HrefValue = "";

			// vr_outrosCustos
			$this->vr_outrosCustos->HrefValue = "";

			// vr_custoTotal
			$this->vr_custoTotal->HrefValue = "";

			// qt_tamBaseFaturamento
			$this->qt_tamBaseFaturamento->HrefValue = "";

			// qt_recursosEquipe
			$this->qt_recursosEquipe->HrefValue = "";
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
		if ($this->ic_solicitacaoCritica->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_solicitacaoCritica->FldCaption());
		}
		if (!$this->nu_ambienteMaisRepresentativo->FldIsDetailKey && !is_null($this->nu_ambienteMaisRepresentativo->FormValue) && $this->nu_ambienteMaisRepresentativo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_ambienteMaisRepresentativo->FldCaption());
		}
		if (!ew_CheckInteger($this->nu_ambienteMaisRepresentativo->FormValue)) {
			ew_AddMessage($gsFormError, $this->nu_ambienteMaisRepresentativo->FldErrMsg());
		}
		if (!$this->qt_tamBase->FldIsDetailKey && !is_null($this->qt_tamBase->FormValue) && $this->qt_tamBase->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->qt_tamBase->FldCaption());
		}
		if (!ew_CheckNumber($this->qt_tamBase->FormValue)) {
			ew_AddMessage($gsFormError, $this->qt_tamBase->FldErrMsg());
		}
		if ($this->nu_metPrazo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_metPrazo->FldCaption());
		}
		if (!ew_CheckInteger($this->vr_doPf->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_doPf->FldErrMsg());
		}
		if (!ew_CheckNumber($this->pz_estimadoMeses->FormValue)) {
			ew_AddMessage($gsFormError, $this->pz_estimadoMeses->FldErrMsg());
		}
		if (!ew_CheckNumber($this->pz_estimadoDias->FormValue)) {
			ew_AddMessage($gsFormError, $this->pz_estimadoDias->FldErrMsg());
		}
		if (!ew_CheckNumber($this->vr_ipMaximo->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_ipMaximo->FldErrMsg());
		}
		if (!ew_CheckNumber($this->vr_ipMedio->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_ipMedio->FldErrMsg());
		}
		if (!ew_CheckNumber($this->vr_ipMinimo->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_ipMinimo->FldErrMsg());
		}
		if (!$this->vr_ipInformado->FldIsDetailKey && !is_null($this->vr_ipInformado->FormValue) && $this->vr_ipInformado->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->vr_ipInformado->FldCaption());
		}
		if (!ew_CheckInteger($this->vr_ipInformado->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_ipInformado->FldErrMsg());
		}
		if (!ew_CheckNumber($this->qt_esforco->FormValue)) {
			ew_AddMessage($gsFormError, $this->qt_esforco->FldErrMsg());
		}
		if (!ew_CheckNumber($this->vr_custoDesenv->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_custoDesenv->FldErrMsg());
		}
		if (!ew_CheckNumber($this->vr_outrosCustos->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_outrosCustos->FldErrMsg());
		}
		if (!ew_CheckNumber($this->vr_custoTotal->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_custoTotal->FldErrMsg());
		}
		if (!ew_CheckNumber($this->qt_tamBaseFaturamento->FormValue)) {
			ew_AddMessage($gsFormError, $this->qt_tamBaseFaturamento->FldErrMsg());
		}
		if (!ew_CheckNumber($this->qt_recursosEquipe->FormValue)) {
			ew_AddMessage($gsFormError, $this->qt_recursosEquipe->FldErrMsg());
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
				$sThisKey .= $row['nu_estimativa'];
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

			// ic_solicitacaoCritica
			$this->ic_solicitacaoCritica->SetDbValueDef($rsnew, $this->ic_solicitacaoCritica->CurrentValue, NULL, $this->ic_solicitacaoCritica->ReadOnly);

			// nu_ambienteMaisRepresentativo
			$this->nu_ambienteMaisRepresentativo->SetDbValueDef($rsnew, $this->nu_ambienteMaisRepresentativo->CurrentValue, NULL, $this->nu_ambienteMaisRepresentativo->ReadOnly);

			// qt_tamBase
			$this->qt_tamBase->SetDbValueDef($rsnew, $this->qt_tamBase->CurrentValue, NULL, $this->qt_tamBase->ReadOnly);

			// ic_modeloCocomo
			$this->ic_modeloCocomo->SetDbValueDef($rsnew, $this->ic_modeloCocomo->CurrentValue, NULL, $this->ic_modeloCocomo->ReadOnly);

			// nu_metPrazo
			$this->nu_metPrazo->SetDbValueDef($rsnew, $this->nu_metPrazo->CurrentValue, NULL, $this->nu_metPrazo->ReadOnly);

			// vr_doPf
			$this->vr_doPf->SetDbValueDef($rsnew, $this->vr_doPf->CurrentValue, NULL, $this->vr_doPf->ReadOnly);

			// pz_estimadoMeses
			$this->pz_estimadoMeses->SetDbValueDef($rsnew, $this->pz_estimadoMeses->CurrentValue, NULL, $this->pz_estimadoMeses->ReadOnly);

			// pz_estimadoDias
			$this->pz_estimadoDias->SetDbValueDef($rsnew, $this->pz_estimadoDias->CurrentValue, NULL, $this->pz_estimadoDias->ReadOnly);

			// vr_ipMaximo
			$this->vr_ipMaximo->SetDbValueDef($rsnew, $this->vr_ipMaximo->CurrentValue, NULL, $this->vr_ipMaximo->ReadOnly);

			// vr_ipMedio
			$this->vr_ipMedio->SetDbValueDef($rsnew, $this->vr_ipMedio->CurrentValue, NULL, $this->vr_ipMedio->ReadOnly);

			// vr_ipMinimo
			$this->vr_ipMinimo->SetDbValueDef($rsnew, $this->vr_ipMinimo->CurrentValue, NULL, $this->vr_ipMinimo->ReadOnly);

			// vr_ipInformado
			$this->vr_ipInformado->SetDbValueDef($rsnew, $this->vr_ipInformado->CurrentValue, NULL, $this->vr_ipInformado->ReadOnly);

			// qt_esforco
			$this->qt_esforco->SetDbValueDef($rsnew, $this->qt_esforco->CurrentValue, NULL, $this->qt_esforco->ReadOnly);

			// vr_custoDesenv
			$this->vr_custoDesenv->SetDbValueDef($rsnew, $this->vr_custoDesenv->CurrentValue, NULL, $this->vr_custoDesenv->ReadOnly);

			// vr_outrosCustos
			$this->vr_outrosCustos->SetDbValueDef($rsnew, $this->vr_outrosCustos->CurrentValue, NULL, $this->vr_outrosCustos->ReadOnly);

			// vr_custoTotal
			$this->vr_custoTotal->SetDbValueDef($rsnew, $this->vr_custoTotal->CurrentValue, NULL, $this->vr_custoTotal->ReadOnly);

			// qt_tamBaseFaturamento
			$this->qt_tamBaseFaturamento->SetDbValueDef($rsnew, $this->qt_tamBaseFaturamento->CurrentValue, NULL, $this->qt_tamBaseFaturamento->ReadOnly);

			// qt_recursosEquipe
			$this->qt_recursosEquipe->SetDbValueDef($rsnew, $this->qt_recursosEquipe->CurrentValue, NULL, $this->qt_recursosEquipe->ReadOnly);

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
				$this->nu_solMetricas->CurrentValue = $this->nu_solMetricas->getSessionValue();
			}

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// ic_solicitacaoCritica
		$this->ic_solicitacaoCritica->SetDbValueDef($rsnew, $this->ic_solicitacaoCritica->CurrentValue, NULL, FALSE);

		// nu_ambienteMaisRepresentativo
		$this->nu_ambienteMaisRepresentativo->SetDbValueDef($rsnew, $this->nu_ambienteMaisRepresentativo->CurrentValue, NULL, FALSE);

		// qt_tamBase
		$this->qt_tamBase->SetDbValueDef($rsnew, $this->qt_tamBase->CurrentValue, NULL, FALSE);

		// ic_modeloCocomo
		$this->ic_modeloCocomo->SetDbValueDef($rsnew, $this->ic_modeloCocomo->CurrentValue, NULL, FALSE);

		// nu_metPrazo
		$this->nu_metPrazo->SetDbValueDef($rsnew, $this->nu_metPrazo->CurrentValue, NULL, FALSE);

		// vr_doPf
		$this->vr_doPf->SetDbValueDef($rsnew, $this->vr_doPf->CurrentValue, NULL, FALSE);

		// pz_estimadoMeses
		$this->pz_estimadoMeses->SetDbValueDef($rsnew, $this->pz_estimadoMeses->CurrentValue, NULL, FALSE);

		// pz_estimadoDias
		$this->pz_estimadoDias->SetDbValueDef($rsnew, $this->pz_estimadoDias->CurrentValue, NULL, FALSE);

		// vr_ipMaximo
		$this->vr_ipMaximo->SetDbValueDef($rsnew, $this->vr_ipMaximo->CurrentValue, NULL, FALSE);

		// vr_ipMedio
		$this->vr_ipMedio->SetDbValueDef($rsnew, $this->vr_ipMedio->CurrentValue, NULL, FALSE);

		// vr_ipMinimo
		$this->vr_ipMinimo->SetDbValueDef($rsnew, $this->vr_ipMinimo->CurrentValue, NULL, FALSE);

		// vr_ipInformado
		$this->vr_ipInformado->SetDbValueDef($rsnew, $this->vr_ipInformado->CurrentValue, NULL, FALSE);

		// qt_esforco
		$this->qt_esforco->SetDbValueDef($rsnew, $this->qt_esforco->CurrentValue, NULL, FALSE);

		// vr_custoDesenv
		$this->vr_custoDesenv->SetDbValueDef($rsnew, $this->vr_custoDesenv->CurrentValue, NULL, FALSE);

		// vr_outrosCustos
		$this->vr_outrosCustos->SetDbValueDef($rsnew, $this->vr_outrosCustos->CurrentValue, NULL, FALSE);

		// vr_custoTotal
		$this->vr_custoTotal->SetDbValueDef($rsnew, $this->vr_custoTotal->CurrentValue, NULL, FALSE);

		// qt_tamBaseFaturamento
		$this->qt_tamBaseFaturamento->SetDbValueDef($rsnew, $this->qt_tamBaseFaturamento->CurrentValue, NULL, FALSE);

		// qt_recursosEquipe
		$this->qt_recursosEquipe->SetDbValueDef($rsnew, $this->qt_recursosEquipe->CurrentValue, NULL, FALSE);

		// nu_solMetricas
		if ($this->nu_solMetricas->getSessionValue() <> "") {
			$rsnew['nu_solMetricas'] = $this->nu_solMetricas->getSessionValue();
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
			$this->nu_estimativa->setDbValue($conn->Insert_ID());
			$rsnew['nu_estimativa'] = $this->nu_estimativa->DbValue;
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
			$this->nu_solMetricas->Visible = FALSE;
			if ($GLOBALS["solicitacaoMetricas"]->EventCancelled) $this->EventCancelled = TRUE;
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); //  Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'estimativa';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'estimativa';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_estimativa'];

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
		$table = 'estimativa';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['nu_estimativa'];

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
		$table = 'estimativa';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_estimativa'];

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
