<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "estimativainfo.php" ?>
<?php include_once "solicitacaometricasinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$estimativa_list = NULL; // Initialize page object first

class cestimativa_list extends cestimativa {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'estimativa';

	// Page object name
	var $PageObjName = 'estimativa_list';

	// Grid form hidden field names
	var $FormName = 'festimativalist';
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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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

		// Table object (estimativa)
		if (!isset($GLOBALS["estimativa"])) {
			$GLOBALS["estimativa"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["estimativa"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "estimativaadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "estimativadelete.php";
		$this->MultiUpdateUrl = "estimativaupdate.php";

		// Table object (solicitacaoMetricas)
		if (!isset($GLOBALS['solicitacaoMetricas'])) $GLOBALS['solicitacaoMetricas'] = new csolicitacaoMetricas();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "span";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "span";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "span";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "span";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
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

		// Get export parameters
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExport = $this->Export; // Get export parameter, used in header
		$gsExportFile = $this->TableVar; // Get export file, used in header
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

		// Setup export options
		$this->SetupExportOptions();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Setup other options
		$this->SetupOtherOptions();

		// Update url if printer friendly for Pdf
		if ($this->PrinterFriendlyForPdf)
			$this->ExportOptions->Items["pdf"]->Body = str_replace($this->ExportPdfUrl, $this->ExportPrintUrl . "&pdf=1", $this->ExportOptions->Items["pdf"]->Body);
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;

		// Page Unload event
		$this->Page_Unload();
		if ($this->Export == "print" && @$_GET["pdf"] == "1") { // Printer friendly version and with pdf=1 in URL parameters
			$pdf = new cExportPdf($GLOBALS["Table"]);
			$pdf->Text = ob_get_contents(); // Set the content as the HTML of current page (printer friendly version)
			ob_end_clean();
			$pdf->Export();
		}

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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
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

			// Process custom action first
			$this->ProcessCustomAction();

			// Handle reset command
			$this->ResetCmd();

			// Set up master detail parameters
			$this->SetUpMasterParms();

			// Set up Breadcrumb
			$this->SetupBreadcrumb();

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

			// Hide export options
			if ($this->Export <> "" || $this->CurrentAction <> "")
				$this->ExportOptions->HideAllOptions();

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
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

		// Export data only
		if (in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
		}
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

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->ic_solicitacaoCritica, $bCtrl); // ic_solicitacaoCritica
			$this->UpdateSort($this->nu_ambienteMaisRepresentativo, $bCtrl); // nu_ambienteMaisRepresentativo
			$this->UpdateSort($this->qt_tamBase, $bCtrl); // qt_tamBase
			$this->UpdateSort($this->ic_modeloCocomo, $bCtrl); // ic_modeloCocomo
			$this->UpdateSort($this->nu_metPrazo, $bCtrl); // nu_metPrazo
			$this->UpdateSort($this->vr_doPf, $bCtrl); // vr_doPf
			$this->UpdateSort($this->pz_estimadoMeses, $bCtrl); // pz_estimadoMeses
			$this->UpdateSort($this->pz_estimadoDias, $bCtrl); // pz_estimadoDias
			$this->UpdateSort($this->vr_ipMaximo, $bCtrl); // vr_ipMaximo
			$this->UpdateSort($this->vr_ipMedio, $bCtrl); // vr_ipMedio
			$this->UpdateSort($this->vr_ipMinimo, $bCtrl); // vr_ipMinimo
			$this->UpdateSort($this->vr_ipInformado, $bCtrl); // vr_ipInformado
			$this->UpdateSort($this->qt_esforco, $bCtrl); // qt_esforco
			$this->UpdateSort($this->vr_custoDesenv, $bCtrl); // vr_custoDesenv
			$this->UpdateSort($this->vr_outrosCustos, $bCtrl); // vr_outrosCustos
			$this->UpdateSort($this->vr_custoTotal, $bCtrl); // vr_custoTotal
			$this->UpdateSort($this->qt_tamBaseFaturamento, $bCtrl); // qt_tamBaseFaturamento
			$this->UpdateSort($this->qt_recursosEquipe, $bCtrl); // qt_recursosEquipe
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
				$this->ic_solicitacaoCritica->setSort("");
				$this->nu_ambienteMaisRepresentativo->setSort("");
				$this->qt_tamBase->setSort("");
				$this->ic_modeloCocomo->setSort("");
				$this->nu_metPrazo->setSort("");
				$this->vr_doPf->setSort("");
				$this->pz_estimadoMeses->setSort("");
				$this->pz_estimadoDias->setSort("");
				$this->vr_ipMaximo->setSort("");
				$this->vr_ipMedio->setSort("");
				$this->vr_ipMinimo->setSort("");
				$this->vr_ipInformado->setSort("");
				$this->qt_esforco->setSort("");
				$this->vr_custoDesenv->setSort("");
				$this->vr_outrosCustos->setSort("");
				$this->vr_custoTotal->setSort("");
				$this->qt_tamBaseFaturamento->setSort("");
				$this->qt_recursosEquipe->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanView();
		$item->OnLeft = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = FALSE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanDelete();
		$item->OnLeft = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = FALSE;
		$item->Header = "<label class=\"checkbox\"><input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\"></label>";
		if (count($this->CustomActions) > 0) $item->Visible = TRUE;
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		$this->ListOptions->ButtonClass = "btn-small"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if ($Security->CanView())
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		else
			$oListOpt->Body = "";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->CanDelete())
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAddEdit ewAdd\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseDropDownButton = TRUE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-small"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];
			foreach ($this->CustomActions as $action => $name) {

				// Add custom action
				$item = &$option->Add("custom_" . $action);
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.festimativalist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
			}

			// Hide grid edit, multi-delete and multi-update
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$item = &$option->GetItem("multidelete");
				if ($item) $item->Visible = FALSE;
				$item = &$option->GetItem("multiupdate");
				if ($item) $item->Visible = FALSE;
			}
	}

	// Process custom action
	function ProcessCustomAction() {
		global $conn, $Language, $Security;
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$rsuser = ($rs) ? $rs->GetRows() : array();
			if ($rs)
				$rs->Close();

			// Call row custom action event
			if (count($rsuser) > 0) {
				$conn->BeginTrans();
				foreach ($rsuser as $row) {
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCancelled")));
					}
				}
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
		if (strval($this->getKey("nu_estimativa")) <> "")
			$this->nu_estimativa->CurrentValue = $this->getKey("nu_estimativa"); // nu_estimativa
		else
			$bValidKey = FALSE;

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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = TRUE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = FALSE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = FALSE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = FALSE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$item->Body = "<a id=\"emf_estimativa\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_estimativa',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.festimativalist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
		$item->Visible = TRUE;

		// Drop down button for export
		$this->ExportOptions->UseDropDownButton = FALSE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = EW_SELECT_LIMIT;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if ($rs = $this->LoadRecordset())
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;

		// Export all
		if ($this->ExportAll) {
			set_time_limit(EW_EXPORT_ALL_TIME_LIMIT);
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetUpStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->DisplayRecs <= 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$ExportDoc = ew_ExportDocument($this, "h");
		$ParentTable = "";

		// Export master record
		if (EW_EXPORT_MASTER_RECORD && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "solicitacaoMetricas") {
			global $solicitacaoMetricas;
			$rsmaster = $solicitacaoMetricas->LoadRs($this->DbMasterFilter); // Load master record
			if ($rsmaster && !$rsmaster->EOF) {
				$ExportStyle = $ExportDoc->Style;
				$ExportDoc->SetStyle("v"); // Change to vertical
				if ($this->Export <> "csv" || EW_EXPORT_MASTER_RECORD_FOR_CSV) {
					$solicitacaoMetricas->ExportDocument($ExportDoc, $rsmaster, 1, 1);
					$ExportDoc->ExportEmptyRow();
				}
				$ExportDoc->SetStyle($ExportStyle); // Restore
				$rsmaster->Close();
			}
		}
		if ($bSelectLimit) {
			$StartRec = 1;
			$StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {
			$StartRec = $this->StartRec;
			$StopRec = $this->StopRec;
		}
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$ExportDoc->Text .= $sHeader;
		$this->ExportDocument($ExportDoc, $rs, $StartRec, $StopRec, "");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$ExportDoc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Export header and footer
		$ExportDoc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED)
			echo ew_DebugMsg();

		// Output data
		if ($this->Export == "email") {
			echo $this->ExportEmail($ExportDoc->Text);
		} else {
			$ExportDoc->Export();
		}
	}

	// Export email
	function ExportEmail($EmailContent) {
		global $gTmpImages, $Language;
		$sSender = @$_GET["sender"];
		$sRecipient = @$_GET["recipient"];
		$sCc = @$_GET["cc"];
		$sBcc = @$_GET["bcc"];
		$sContentType = @$_GET["contenttype"];

		// Subject
		$sSubject = ew_StripSlashes(@$_GET["subject"]);
		$sEmailSubject = $sSubject;

		// Message
		$sContent = ew_StripSlashes(@$_GET["message"]);
		$sEmailMessage = $sContent;

		// Check sender
		if ($sSender == "") {
			return "<p class=\"text-error\">" . $Language->Phrase("EnterSenderEmail") . "</p>";
		}
		if (!ew_CheckEmail($sSender)) {
			return "<p class=\"text-error\">" . $Language->Phrase("EnterProperSenderEmail") . "</p>";
		}

		// Check recipient
		if (!ew_CheckEmailList($sRecipient, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-error\">" . $Language->Phrase("EnterProperRecipientEmail") . "</p>";
		}

		// Check cc
		if (!ew_CheckEmailList($sCc, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-error\">" . $Language->Phrase("EnterProperCcEmail") . "</p>";
		}

		// Check bcc
		if (!ew_CheckEmailList($sBcc, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-error\">" . $Language->Phrase("EnterProperBccEmail") . "</p>";
		}

		// Check email sent count
		if (!isset($_SESSION[EW_EXPORT_EMAIL_COUNTER]))
			$_SESSION[EW_EXPORT_EMAIL_COUNTER] = 0;
		if (intval($_SESSION[EW_EXPORT_EMAIL_COUNTER]) > EW_MAX_EMAIL_SENT_COUNT) {
			return "<p class=\"text-error\">" . $Language->Phrase("ExceedMaxEmailExport") . "</p>";
		}

		// Send email
		$Email = new cEmail();
		$Email->Sender = $sSender; // Sender
		$Email->Recipient = $sRecipient; // Recipient
		$Email->Cc = $sCc; // Cc
		$Email->Bcc = $sBcc; // Bcc
		$Email->Subject = $sEmailSubject; // Subject
		$Email->Format = ($sContentType == "url") ? "text" : "html";
		$Email->Charset = EW_EMAIL_CHARSET;
		if ($sEmailMessage <> "") {
			$sEmailMessage = ew_RemoveXSS($sEmailMessage);
			$sEmailMessage .= ($sContentType == "url") ? "\r\n\r\n" : "<br><br>";
		}
		if ($sContentType == "url") {
			$sUrl = ew_ConvertFullUrl(ew_CurrentPage() . "?" . $this->ExportQueryString());
			$sEmailMessage .= $sUrl; // Send URL only
		} else {
			foreach ($gTmpImages as $tmpimage)
				$Email->AddEmbeddedImage($tmpimage);
			$sEmailMessage .= $EmailContent; // Send HTML
		}
		$Email->Content = $sEmailMessage; // Content
		$EventArgs = array();
		$bEmailSent = FALSE;
		if ($this->Email_Sending($Email, $EventArgs))
			$bEmailSent = $Email->Send();

		// Check email sent status
		if ($bEmailSent) {

			// Update email sent count
			$_SESSION[EW_EXPORT_EMAIL_COUNTER]++;

			// Sent email success
			return "<p class=\"text-success\">" . $Language->Phrase("SendEmailSuccess") . "</p>"; // Set up success message
		} else {

			// Sent email failure
			return "<p class=\"text-error\">" . $Email->SendErrDescription . "</p>";
		}
	}

	// Export QueryString
	function ExportQueryString() {

		// Initialize
		$sQry = "export=html";

		// Build QueryString for search
		// Build QueryString for pager

		$sQry .= "&" . EW_TABLE_REC_PER_PAGE . "=" . urlencode($this->getRecordsPerPage()) . "&" . EW_TABLE_START_REC . "=" . urlencode($this->getStartRecordNumber());
		return $sQry;
	}

	// Add search QueryString
	function AddSearchQueryString(&$Qry, &$Fld) {
		$FldSearchValue = $Fld->AdvancedSearch->getValue("x");
		$FldParm = substr($Fld->FldVar,2);
		if (strval($FldSearchValue) <> "") {
			$Qry .= "&x_" . $FldParm . "=" . urlencode($FldSearchValue) .
				"&z_" . $FldParm . "=" . urlencode($Fld->AdvancedSearch->getValue("z"));
		}
		$FldSearchValue2 = $Fld->AdvancedSearch->getValue("y");
		if (strval($FldSearchValue2) <> "") {
			$Qry .= "&v_" . $FldParm . "=" . urlencode($Fld->AdvancedSearch->getValue("v")) .
				"&y_" . $FldParm . "=" . urlencode($FldSearchValue2) .
				"&w_" . $FldParm . "=" . urlencode($Fld->AdvancedSearch->getValue("w"));
		}
	}

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "solicitacaoMetricas") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_solMetricas"] <> "") {
					$GLOBALS["solicitacaoMetricas"]->nu_solMetricas->setQueryStringValue($_GET["nu_solMetricas"]);
					$this->nu_solMetricas->setQueryStringValue($GLOBALS["solicitacaoMetricas"]->nu_solMetricas->QueryStringValue);
					$this->nu_solMetricas->setSessionValue($this->nu_solMetricas->QueryStringValue);
					if (!is_numeric($GLOBALS["solicitacaoMetricas"]->nu_solMetricas->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "solicitacaoMetricas") {
				if ($this->nu_solMetricas->QueryStringValue == "") $this->nu_solMetricas->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); //  Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$url = ew_CurrentUrl();
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", $url, $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'estimativa';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($estimativa_list)) $estimativa_list = new cestimativa_list();

// Page init
$estimativa_list->Page_Init();

// Page main
$estimativa_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$estimativa_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($estimativa->Export == "") { ?>
<script type="text/javascript">

// Page object
var estimativa_list = new ew_Page("estimativa_list");
estimativa_list.PageID = "list"; // Page ID
var EW_PAGE_ID = estimativa_list.PageID; // For backward compatibility

// Form object
var festimativalist = new ew_Form("festimativalist");
festimativalist.FormKeyCountName = '<?php echo $estimativa_list->FormKeyCountName ?>';

// Form_CustomValidate event
festimativalist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
festimativalist.ValidateRequired = true;
<?php } else { ?>
festimativalist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
festimativalist.Lists["x_nu_ambienteMaisRepresentativo"] = {"LinkField":"x_nu_ambiente","Ajax":true,"AutoFill":false,"DisplayFields":["x_no_ambiente","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($estimativa->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($estimativa->getCurrentMasterTable() == "" && $estimativa_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $estimativa_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php if (($estimativa->Export == "") || (EW_EXPORT_MASTER_RECORD && $estimativa->Export == "print")) { ?>
<?php
$gsMasterReturnUrl = "solicitacaometricaslist.php";
if ($estimativa_list->DbMasterFilter <> "" && $estimativa->getCurrentMasterTable() == "solicitacaoMetricas") {
	if ($estimativa_list->MasterRecordExists) {
		if ($estimativa->getCurrentMasterTable() == $estimativa->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<?php if ($estimativa_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $estimativa_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php include_once "solicitacaometricasmaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$estimativa_list->TotalRecs = $estimativa->SelectRecordCount();
	} else {
		if ($estimativa_list->Recordset = $estimativa_list->LoadRecordset())
			$estimativa_list->TotalRecs = $estimativa_list->Recordset->RecordCount();
	}
	$estimativa_list->StartRec = 1;
	if ($estimativa_list->DisplayRecs <= 0 || ($estimativa->Export <> "" && $estimativa->ExportAll)) // Display all records
		$estimativa_list->DisplayRecs = $estimativa_list->TotalRecs;
	if (!($estimativa->Export <> "" && $estimativa->ExportAll))
		$estimativa_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$estimativa_list->Recordset = $estimativa_list->LoadRecordset($estimativa_list->StartRec-1, $estimativa_list->DisplayRecs);
$estimativa_list->RenderOtherOptions();
?>
<?php $estimativa_list->ShowPageHeader(); ?>
<?php
$estimativa_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="festimativalist" id="festimativalist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="estimativa">
<div id="gmp_estimativa" class="ewGridMiddlePanel">
<?php if ($estimativa_list->TotalRecs > 0) { ?>
<table id="tbl_estimativalist" class="ewTable ewTableSeparate">
<?php echo $estimativa->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$estimativa_list->RenderListOptions();

// Render list options (header, left)
$estimativa_list->ListOptions->Render("header", "left");
?>
<?php if ($estimativa->ic_solicitacaoCritica->Visible) { // ic_solicitacaoCritica ?>
	<?php if ($estimativa->SortUrl($estimativa->ic_solicitacaoCritica) == "") { ?>
		<td><div id="elh_estimativa_ic_solicitacaoCritica" class="estimativa_ic_solicitacaoCritica"><div class="ewTableHeaderCaption"><?php echo $estimativa->ic_solicitacaoCritica->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estimativa->SortUrl($estimativa->ic_solicitacaoCritica) ?>',2);"><div id="elh_estimativa_ic_solicitacaoCritica" class="estimativa_ic_solicitacaoCritica">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estimativa->ic_solicitacaoCritica->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estimativa->ic_solicitacaoCritica->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estimativa->ic_solicitacaoCritica->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($estimativa->nu_ambienteMaisRepresentativo->Visible) { // nu_ambienteMaisRepresentativo ?>
	<?php if ($estimativa->SortUrl($estimativa->nu_ambienteMaisRepresentativo) == "") { ?>
		<td><div id="elh_estimativa_nu_ambienteMaisRepresentativo" class="estimativa_nu_ambienteMaisRepresentativo"><div class="ewTableHeaderCaption"><?php echo $estimativa->nu_ambienteMaisRepresentativo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estimativa->SortUrl($estimativa->nu_ambienteMaisRepresentativo) ?>',2);"><div id="elh_estimativa_nu_ambienteMaisRepresentativo" class="estimativa_nu_ambienteMaisRepresentativo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estimativa->nu_ambienteMaisRepresentativo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estimativa->nu_ambienteMaisRepresentativo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estimativa->nu_ambienteMaisRepresentativo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($estimativa->qt_tamBase->Visible) { // qt_tamBase ?>
	<?php if ($estimativa->SortUrl($estimativa->qt_tamBase) == "") { ?>
		<td><div id="elh_estimativa_qt_tamBase" class="estimativa_qt_tamBase"><div class="ewTableHeaderCaption"><?php echo $estimativa->qt_tamBase->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estimativa->SortUrl($estimativa->qt_tamBase) ?>',2);"><div id="elh_estimativa_qt_tamBase" class="estimativa_qt_tamBase">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estimativa->qt_tamBase->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estimativa->qt_tamBase->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estimativa->qt_tamBase->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($estimativa->ic_modeloCocomo->Visible) { // ic_modeloCocomo ?>
	<?php if ($estimativa->SortUrl($estimativa->ic_modeloCocomo) == "") { ?>
		<td><div id="elh_estimativa_ic_modeloCocomo" class="estimativa_ic_modeloCocomo"><div class="ewTableHeaderCaption"><?php echo $estimativa->ic_modeloCocomo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estimativa->SortUrl($estimativa->ic_modeloCocomo) ?>',2);"><div id="elh_estimativa_ic_modeloCocomo" class="estimativa_ic_modeloCocomo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estimativa->ic_modeloCocomo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estimativa->ic_modeloCocomo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estimativa->ic_modeloCocomo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($estimativa->nu_metPrazo->Visible) { // nu_metPrazo ?>
	<?php if ($estimativa->SortUrl($estimativa->nu_metPrazo) == "") { ?>
		<td><div id="elh_estimativa_nu_metPrazo" class="estimativa_nu_metPrazo"><div class="ewTableHeaderCaption"><?php echo $estimativa->nu_metPrazo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estimativa->SortUrl($estimativa->nu_metPrazo) ?>',2);"><div id="elh_estimativa_nu_metPrazo" class="estimativa_nu_metPrazo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estimativa->nu_metPrazo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estimativa->nu_metPrazo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estimativa->nu_metPrazo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($estimativa->vr_doPf->Visible) { // vr_doPf ?>
	<?php if ($estimativa->SortUrl($estimativa->vr_doPf) == "") { ?>
		<td><div id="elh_estimativa_vr_doPf" class="estimativa_vr_doPf"><div class="ewTableHeaderCaption"><?php echo $estimativa->vr_doPf->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estimativa->SortUrl($estimativa->vr_doPf) ?>',2);"><div id="elh_estimativa_vr_doPf" class="estimativa_vr_doPf">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estimativa->vr_doPf->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estimativa->vr_doPf->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estimativa->vr_doPf->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($estimativa->pz_estimadoMeses->Visible) { // pz_estimadoMeses ?>
	<?php if ($estimativa->SortUrl($estimativa->pz_estimadoMeses) == "") { ?>
		<td><div id="elh_estimativa_pz_estimadoMeses" class="estimativa_pz_estimadoMeses"><div class="ewTableHeaderCaption"><?php echo $estimativa->pz_estimadoMeses->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estimativa->SortUrl($estimativa->pz_estimadoMeses) ?>',2);"><div id="elh_estimativa_pz_estimadoMeses" class="estimativa_pz_estimadoMeses">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estimativa->pz_estimadoMeses->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estimativa->pz_estimadoMeses->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estimativa->pz_estimadoMeses->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($estimativa->pz_estimadoDias->Visible) { // pz_estimadoDias ?>
	<?php if ($estimativa->SortUrl($estimativa->pz_estimadoDias) == "") { ?>
		<td><div id="elh_estimativa_pz_estimadoDias" class="estimativa_pz_estimadoDias"><div class="ewTableHeaderCaption"><?php echo $estimativa->pz_estimadoDias->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estimativa->SortUrl($estimativa->pz_estimadoDias) ?>',2);"><div id="elh_estimativa_pz_estimadoDias" class="estimativa_pz_estimadoDias">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estimativa->pz_estimadoDias->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estimativa->pz_estimadoDias->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estimativa->pz_estimadoDias->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($estimativa->vr_ipMaximo->Visible) { // vr_ipMaximo ?>
	<?php if ($estimativa->SortUrl($estimativa->vr_ipMaximo) == "") { ?>
		<td><div id="elh_estimativa_vr_ipMaximo" class="estimativa_vr_ipMaximo"><div class="ewTableHeaderCaption"><?php echo $estimativa->vr_ipMaximo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estimativa->SortUrl($estimativa->vr_ipMaximo) ?>',2);"><div id="elh_estimativa_vr_ipMaximo" class="estimativa_vr_ipMaximo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estimativa->vr_ipMaximo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estimativa->vr_ipMaximo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estimativa->vr_ipMaximo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($estimativa->vr_ipMedio->Visible) { // vr_ipMedio ?>
	<?php if ($estimativa->SortUrl($estimativa->vr_ipMedio) == "") { ?>
		<td><div id="elh_estimativa_vr_ipMedio" class="estimativa_vr_ipMedio"><div class="ewTableHeaderCaption"><?php echo $estimativa->vr_ipMedio->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estimativa->SortUrl($estimativa->vr_ipMedio) ?>',2);"><div id="elh_estimativa_vr_ipMedio" class="estimativa_vr_ipMedio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estimativa->vr_ipMedio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estimativa->vr_ipMedio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estimativa->vr_ipMedio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($estimativa->vr_ipMinimo->Visible) { // vr_ipMinimo ?>
	<?php if ($estimativa->SortUrl($estimativa->vr_ipMinimo) == "") { ?>
		<td><div id="elh_estimativa_vr_ipMinimo" class="estimativa_vr_ipMinimo"><div class="ewTableHeaderCaption"><?php echo $estimativa->vr_ipMinimo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estimativa->SortUrl($estimativa->vr_ipMinimo) ?>',2);"><div id="elh_estimativa_vr_ipMinimo" class="estimativa_vr_ipMinimo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estimativa->vr_ipMinimo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estimativa->vr_ipMinimo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estimativa->vr_ipMinimo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($estimativa->vr_ipInformado->Visible) { // vr_ipInformado ?>
	<?php if ($estimativa->SortUrl($estimativa->vr_ipInformado) == "") { ?>
		<td><div id="elh_estimativa_vr_ipInformado" class="estimativa_vr_ipInformado"><div class="ewTableHeaderCaption"><?php echo $estimativa->vr_ipInformado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estimativa->SortUrl($estimativa->vr_ipInformado) ?>',2);"><div id="elh_estimativa_vr_ipInformado" class="estimativa_vr_ipInformado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estimativa->vr_ipInformado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estimativa->vr_ipInformado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estimativa->vr_ipInformado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($estimativa->qt_esforco->Visible) { // qt_esforco ?>
	<?php if ($estimativa->SortUrl($estimativa->qt_esforco) == "") { ?>
		<td><div id="elh_estimativa_qt_esforco" class="estimativa_qt_esforco"><div class="ewTableHeaderCaption"><?php echo $estimativa->qt_esforco->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estimativa->SortUrl($estimativa->qt_esforco) ?>',2);"><div id="elh_estimativa_qt_esforco" class="estimativa_qt_esforco">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estimativa->qt_esforco->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estimativa->qt_esforco->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estimativa->qt_esforco->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($estimativa->vr_custoDesenv->Visible) { // vr_custoDesenv ?>
	<?php if ($estimativa->SortUrl($estimativa->vr_custoDesenv) == "") { ?>
		<td><div id="elh_estimativa_vr_custoDesenv" class="estimativa_vr_custoDesenv"><div class="ewTableHeaderCaption"><?php echo $estimativa->vr_custoDesenv->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estimativa->SortUrl($estimativa->vr_custoDesenv) ?>',2);"><div id="elh_estimativa_vr_custoDesenv" class="estimativa_vr_custoDesenv">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estimativa->vr_custoDesenv->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estimativa->vr_custoDesenv->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estimativa->vr_custoDesenv->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($estimativa->vr_outrosCustos->Visible) { // vr_outrosCustos ?>
	<?php if ($estimativa->SortUrl($estimativa->vr_outrosCustos) == "") { ?>
		<td><div id="elh_estimativa_vr_outrosCustos" class="estimativa_vr_outrosCustos"><div class="ewTableHeaderCaption"><?php echo $estimativa->vr_outrosCustos->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estimativa->SortUrl($estimativa->vr_outrosCustos) ?>',2);"><div id="elh_estimativa_vr_outrosCustos" class="estimativa_vr_outrosCustos">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estimativa->vr_outrosCustos->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estimativa->vr_outrosCustos->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estimativa->vr_outrosCustos->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($estimativa->vr_custoTotal->Visible) { // vr_custoTotal ?>
	<?php if ($estimativa->SortUrl($estimativa->vr_custoTotal) == "") { ?>
		<td><div id="elh_estimativa_vr_custoTotal" class="estimativa_vr_custoTotal"><div class="ewTableHeaderCaption"><?php echo $estimativa->vr_custoTotal->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estimativa->SortUrl($estimativa->vr_custoTotal) ?>',2);"><div id="elh_estimativa_vr_custoTotal" class="estimativa_vr_custoTotal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estimativa->vr_custoTotal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estimativa->vr_custoTotal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estimativa->vr_custoTotal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($estimativa->qt_tamBaseFaturamento->Visible) { // qt_tamBaseFaturamento ?>
	<?php if ($estimativa->SortUrl($estimativa->qt_tamBaseFaturamento) == "") { ?>
		<td><div id="elh_estimativa_qt_tamBaseFaturamento" class="estimativa_qt_tamBaseFaturamento"><div class="ewTableHeaderCaption"><?php echo $estimativa->qt_tamBaseFaturamento->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estimativa->SortUrl($estimativa->qt_tamBaseFaturamento) ?>',2);"><div id="elh_estimativa_qt_tamBaseFaturamento" class="estimativa_qt_tamBaseFaturamento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estimativa->qt_tamBaseFaturamento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estimativa->qt_tamBaseFaturamento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estimativa->qt_tamBaseFaturamento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($estimativa->qt_recursosEquipe->Visible) { // qt_recursosEquipe ?>
	<?php if ($estimativa->SortUrl($estimativa->qt_recursosEquipe) == "") { ?>
		<td><div id="elh_estimativa_qt_recursosEquipe" class="estimativa_qt_recursosEquipe"><div class="ewTableHeaderCaption"><?php echo $estimativa->qt_recursosEquipe->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estimativa->SortUrl($estimativa->qt_recursosEquipe) ?>',2);"><div id="elh_estimativa_qt_recursosEquipe" class="estimativa_qt_recursosEquipe">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estimativa->qt_recursosEquipe->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estimativa->qt_recursosEquipe->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estimativa->qt_recursosEquipe->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$estimativa_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($estimativa->ExportAll && $estimativa->Export <> "") {
	$estimativa_list->StopRec = $estimativa_list->TotalRecs;
} else {

	// Set the last record to display
	if ($estimativa_list->TotalRecs > $estimativa_list->StartRec + $estimativa_list->DisplayRecs - 1)
		$estimativa_list->StopRec = $estimativa_list->StartRec + $estimativa_list->DisplayRecs - 1;
	else
		$estimativa_list->StopRec = $estimativa_list->TotalRecs;
}
$estimativa_list->RecCnt = $estimativa_list->StartRec - 1;
if ($estimativa_list->Recordset && !$estimativa_list->Recordset->EOF) {
	$estimativa_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $estimativa_list->StartRec > 1)
		$estimativa_list->Recordset->Move($estimativa_list->StartRec - 1);
} elseif (!$estimativa->AllowAddDeleteRow && $estimativa_list->StopRec == 0) {
	$estimativa_list->StopRec = $estimativa->GridAddRowCount;
}

// Initialize aggregate
$estimativa->RowType = EW_ROWTYPE_AGGREGATEINIT;
$estimativa->ResetAttrs();
$estimativa_list->RenderRow();
while ($estimativa_list->RecCnt < $estimativa_list->StopRec) {
	$estimativa_list->RecCnt++;
	if (intval($estimativa_list->RecCnt) >= intval($estimativa_list->StartRec)) {
		$estimativa_list->RowCnt++;

		// Set up key count
		$estimativa_list->KeyCount = $estimativa_list->RowIndex;

		// Init row class and style
		$estimativa->ResetAttrs();
		$estimativa->CssClass = "";
		if ($estimativa->CurrentAction == "gridadd") {
		} else {
			$estimativa_list->LoadRowValues($estimativa_list->Recordset); // Load row values
		}
		$estimativa->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$estimativa->RowAttrs = array_merge($estimativa->RowAttrs, array('data-rowindex'=>$estimativa_list->RowCnt, 'id'=>'r' . $estimativa_list->RowCnt . '_estimativa', 'data-rowtype'=>$estimativa->RowType));

		// Render row
		$estimativa_list->RenderRow();

		// Render list options
		$estimativa_list->RenderListOptions();
?>
	<tr<?php echo $estimativa->RowAttributes() ?>>
<?php

// Render list options (body, left)
$estimativa_list->ListOptions->Render("body", "left", $estimativa_list->RowCnt);
?>
	<?php if ($estimativa->ic_solicitacaoCritica->Visible) { // ic_solicitacaoCritica ?>
		<td<?php echo $estimativa->ic_solicitacaoCritica->CellAttributes() ?>>
<span<?php echo $estimativa->ic_solicitacaoCritica->ViewAttributes() ?>>
<?php echo $estimativa->ic_solicitacaoCritica->ListViewValue() ?></span>
<a id="<?php echo $estimativa_list->PageObjName . "_row_" . $estimativa_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($estimativa->nu_ambienteMaisRepresentativo->Visible) { // nu_ambienteMaisRepresentativo ?>
		<td<?php echo $estimativa->nu_ambienteMaisRepresentativo->CellAttributes() ?>>
<span<?php echo $estimativa->nu_ambienteMaisRepresentativo->ViewAttributes() ?>>
<?php echo $estimativa->nu_ambienteMaisRepresentativo->ListViewValue() ?></span>
<a id="<?php echo $estimativa_list->PageObjName . "_row_" . $estimativa_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($estimativa->qt_tamBase->Visible) { // qt_tamBase ?>
		<td<?php echo $estimativa->qt_tamBase->CellAttributes() ?>>
<span<?php echo $estimativa->qt_tamBase->ViewAttributes() ?>>
<?php echo $estimativa->qt_tamBase->ListViewValue() ?></span>
<a id="<?php echo $estimativa_list->PageObjName . "_row_" . $estimativa_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($estimativa->ic_modeloCocomo->Visible) { // ic_modeloCocomo ?>
		<td<?php echo $estimativa->ic_modeloCocomo->CellAttributes() ?>>
<span<?php echo $estimativa->ic_modeloCocomo->ViewAttributes() ?>>
<?php echo $estimativa->ic_modeloCocomo->ListViewValue() ?></span>
<a id="<?php echo $estimativa_list->PageObjName . "_row_" . $estimativa_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($estimativa->nu_metPrazo->Visible) { // nu_metPrazo ?>
		<td<?php echo $estimativa->nu_metPrazo->CellAttributes() ?>>
<span<?php echo $estimativa->nu_metPrazo->ViewAttributes() ?>>
<?php echo $estimativa->nu_metPrazo->ListViewValue() ?></span>
<a id="<?php echo $estimativa_list->PageObjName . "_row_" . $estimativa_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($estimativa->vr_doPf->Visible) { // vr_doPf ?>
		<td<?php echo $estimativa->vr_doPf->CellAttributes() ?>>
<span<?php echo $estimativa->vr_doPf->ViewAttributes() ?>>
<?php echo $estimativa->vr_doPf->ListViewValue() ?></span>
<a id="<?php echo $estimativa_list->PageObjName . "_row_" . $estimativa_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($estimativa->pz_estimadoMeses->Visible) { // pz_estimadoMeses ?>
		<td<?php echo $estimativa->pz_estimadoMeses->CellAttributes() ?>>
<span<?php echo $estimativa->pz_estimadoMeses->ViewAttributes() ?>>
<?php echo $estimativa->pz_estimadoMeses->ListViewValue() ?></span>
<a id="<?php echo $estimativa_list->PageObjName . "_row_" . $estimativa_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($estimativa->pz_estimadoDias->Visible) { // pz_estimadoDias ?>
		<td<?php echo $estimativa->pz_estimadoDias->CellAttributes() ?>>
<span<?php echo $estimativa->pz_estimadoDias->ViewAttributes() ?>>
<?php echo $estimativa->pz_estimadoDias->ListViewValue() ?></span>
<a id="<?php echo $estimativa_list->PageObjName . "_row_" . $estimativa_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($estimativa->vr_ipMaximo->Visible) { // vr_ipMaximo ?>
		<td<?php echo $estimativa->vr_ipMaximo->CellAttributes() ?>>
<span<?php echo $estimativa->vr_ipMaximo->ViewAttributes() ?>>
<?php echo $estimativa->vr_ipMaximo->ListViewValue() ?></span>
<a id="<?php echo $estimativa_list->PageObjName . "_row_" . $estimativa_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($estimativa->vr_ipMedio->Visible) { // vr_ipMedio ?>
		<td<?php echo $estimativa->vr_ipMedio->CellAttributes() ?>>
<span<?php echo $estimativa->vr_ipMedio->ViewAttributes() ?>>
<?php echo $estimativa->vr_ipMedio->ListViewValue() ?></span>
<a id="<?php echo $estimativa_list->PageObjName . "_row_" . $estimativa_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($estimativa->vr_ipMinimo->Visible) { // vr_ipMinimo ?>
		<td<?php echo $estimativa->vr_ipMinimo->CellAttributes() ?>>
<span<?php echo $estimativa->vr_ipMinimo->ViewAttributes() ?>>
<?php echo $estimativa->vr_ipMinimo->ListViewValue() ?></span>
<a id="<?php echo $estimativa_list->PageObjName . "_row_" . $estimativa_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($estimativa->vr_ipInformado->Visible) { // vr_ipInformado ?>
		<td<?php echo $estimativa->vr_ipInformado->CellAttributes() ?>>
<span<?php echo $estimativa->vr_ipInformado->ViewAttributes() ?>>
<?php echo $estimativa->vr_ipInformado->ListViewValue() ?></span>
<a id="<?php echo $estimativa_list->PageObjName . "_row_" . $estimativa_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($estimativa->qt_esforco->Visible) { // qt_esforco ?>
		<td<?php echo $estimativa->qt_esforco->CellAttributes() ?>>
<span<?php echo $estimativa->qt_esforco->ViewAttributes() ?>>
<?php echo $estimativa->qt_esforco->ListViewValue() ?></span>
<a id="<?php echo $estimativa_list->PageObjName . "_row_" . $estimativa_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($estimativa->vr_custoDesenv->Visible) { // vr_custoDesenv ?>
		<td<?php echo $estimativa->vr_custoDesenv->CellAttributes() ?>>
<span<?php echo $estimativa->vr_custoDesenv->ViewAttributes() ?>>
<?php echo $estimativa->vr_custoDesenv->ListViewValue() ?></span>
<a id="<?php echo $estimativa_list->PageObjName . "_row_" . $estimativa_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($estimativa->vr_outrosCustos->Visible) { // vr_outrosCustos ?>
		<td<?php echo $estimativa->vr_outrosCustos->CellAttributes() ?>>
<span<?php echo $estimativa->vr_outrosCustos->ViewAttributes() ?>>
<?php echo $estimativa->vr_outrosCustos->ListViewValue() ?></span>
<a id="<?php echo $estimativa_list->PageObjName . "_row_" . $estimativa_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($estimativa->vr_custoTotal->Visible) { // vr_custoTotal ?>
		<td<?php echo $estimativa->vr_custoTotal->CellAttributes() ?>>
<span<?php echo $estimativa->vr_custoTotal->ViewAttributes() ?>>
<?php echo $estimativa->vr_custoTotal->ListViewValue() ?></span>
<a id="<?php echo $estimativa_list->PageObjName . "_row_" . $estimativa_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($estimativa->qt_tamBaseFaturamento->Visible) { // qt_tamBaseFaturamento ?>
		<td<?php echo $estimativa->qt_tamBaseFaturamento->CellAttributes() ?>>
<span<?php echo $estimativa->qt_tamBaseFaturamento->ViewAttributes() ?>>
<?php echo $estimativa->qt_tamBaseFaturamento->ListViewValue() ?></span>
<a id="<?php echo $estimativa_list->PageObjName . "_row_" . $estimativa_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($estimativa->qt_recursosEquipe->Visible) { // qt_recursosEquipe ?>
		<td<?php echo $estimativa->qt_recursosEquipe->CellAttributes() ?>>
<span<?php echo $estimativa->qt_recursosEquipe->ViewAttributes() ?>>
<?php echo $estimativa->qt_recursosEquipe->ListViewValue() ?></span>
<a id="<?php echo $estimativa_list->PageObjName . "_row_" . $estimativa_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$estimativa_list->ListOptions->Render("body", "right", $estimativa_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($estimativa->CurrentAction <> "gridadd")
		$estimativa_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($estimativa->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($estimativa_list->Recordset)
	$estimativa_list->Recordset->Close();
?>
<?php if ($estimativa->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($estimativa->CurrentAction <> "gridadd" && $estimativa->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($estimativa_list->Pager)) $estimativa_list->Pager = new cNumericPager($estimativa_list->StartRec, $estimativa_list->DisplayRecs, $estimativa_list->TotalRecs, $estimativa_list->RecRange) ?>
<?php if ($estimativa_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($estimativa_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $estimativa_list->PageUrl() ?>start=<?php echo $estimativa_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($estimativa_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $estimativa_list->PageUrl() ?>start=<?php echo $estimativa_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($estimativa_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $estimativa_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($estimativa_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $estimativa_list->PageUrl() ?>start=<?php echo $estimativa_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($estimativa_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $estimativa_list->PageUrl() ?>start=<?php echo $estimativa_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
<td>
	<?php if ($estimativa_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $estimativa_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $estimativa_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $estimativa_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($estimativa_list->SearchWhere == "0=101") { ?>
	<p><?php echo $Language->Phrase("EnterSearchCriteria") ?></p>
	<?php } else { ?>
	<p><?php echo $Language->Phrase("NoRecord") ?></p>
	<?php } ?>
	<?php } else { ?>
	<p><?php echo $Language->Phrase("NoPermission") ?></p>
	<?php } ?>
<?php } ?>
</td>
</tr></table>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($estimativa_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($estimativa->Export == "") { ?>
<script type="text/javascript">
festimativalist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$estimativa_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($estimativa->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$estimativa_list->Page_Terminate();
?>
