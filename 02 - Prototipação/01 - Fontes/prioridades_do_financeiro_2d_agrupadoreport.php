<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php

// Global variable for table object
$Prioridades_do_Financeiro_2D_Agrupado = NULL;

//
// Table class for Prioridades do Financeiro - Agrupado
//
class cPrioridades_do_Financeiro_2D_Agrupado extends cTableBase {
	var $centroCusto;
	var $projeto;
	var $status;
	var $titulo;
	var $dataPrevista;
	var $prioridade;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'Prioridades_do_Financeiro_2D_Agrupado';
		$this->TableName = 'Prioridades do Financeiro - Agrupado';
		$this->TableType = 'REPORT';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->PrinterFriendlyForPdf = TRUE;
		$this->UserIDAllowSecurity = 0; // User ID Allow

		// centroCusto
		$this->centroCusto = new cField('Prioridades_do_Financeiro_2D_Agrupado', 'Prioridades do Financeiro - Agrupado', 'x_centroCusto', 'centroCusto', '[centroCusto]', '[centroCusto]', 200, -1, FALSE, '[centroCusto]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['centroCusto'] = &$this->centroCusto;

		// projeto
		$this->projeto = new cField('Prioridades_do_Financeiro_2D_Agrupado', 'Prioridades do Financeiro - Agrupado', 'x_projeto', 'projeto', '[projeto]', '[projeto]', 200, -1, FALSE, '[projeto]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['projeto'] = &$this->projeto;

		// status
		$this->status = new cField('Prioridades_do_Financeiro_2D_Agrupado', 'Prioridades do Financeiro - Agrupado', 'x_status', 'status', '[status]', '[status]', 200, -1, FALSE, '[status]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['status'] = &$this->status;

		// titulo
		$this->titulo = new cField('Prioridades_do_Financeiro_2D_Agrupado', 'Prioridades do Financeiro - Agrupado', 'x_titulo', 'titulo', '[titulo]', '[titulo]', 200, -1, FALSE, '[titulo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['titulo'] = &$this->titulo;

		// dataPrevista
		$this->dataPrevista = new cField('Prioridades_do_Financeiro_2D_Agrupado', 'Prioridades do Financeiro - Agrupado', 'x_dataPrevista', 'dataPrevista', '[dataPrevista]', '(REPLACE(STR(DAY([dataPrevista]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dataPrevista]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dataPrevista]),4,0))', 133, 7, FALSE, '[dataPrevista]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dataPrevista->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dataPrevista'] = &$this->dataPrevista;

		// prioridade
		$this->prioridade = new cField('Prioridades_do_Financeiro_2D_Agrupado', 'Prioridades do Financeiro - Agrupado', 'x_prioridade', 'prioridade', '[prioridade]', '[prioridade]', 200, -1, FALSE, '[prioridade]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['prioridade'] = &$this->prioridade;
	}

	// Report group level SQL
	function SqlGroupSelect() { // Select
		return "SELECT DISTINCT [centroCusto] FROM [db_owner].[vwrdmd_financeiroPriori]";
	}

	function SqlGroupWhere() { // Where
		return "";
	}

	function SqlGroupGroupBy() { // Group By
		return "";
	}

	function SqlGroupHaving() { // Having
		return "";
	}

	function SqlGroupOrderBy() { // Order By
		return "[centroCusto] ASC";
	}

	// Report detail level SQL
	function SqlDetailSelect() { // Select
		return "SELECT * FROM [db_owner].[vwrdmd_financeiroPriori]";
	}

	function SqlDetailWhere() { // Where
		return "";
	}

	function SqlDetailGroupBy() { // Group By
		return "";
	}

	function SqlDetailHaving() { // Having
		return "";
	}

	function SqlDetailOrderBy() { // Order By
		return "[prioridade] ASC,[titulo] ASC";
	}

	// Check if Anonymous User is allowed
	function AllowAnonymousUser() {
		switch (@$this->PageID) {
			case "add":
			case "register":
			case "addopt":
				return FALSE;
			case "edit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return FALSE;
			case "delete":
				return FALSE;
			case "view":
				return FALSE;
			case "search":
				return FALSE;
			default:
				return FALSE;
		}
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Report group SQL
	function GroupSQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = "";
		return ew_BuildSelectSql($this->SqlGroupSelect(), $this->SqlGroupWhere(),
			 $this->SqlGroupGroupBy(), $this->SqlGroupHaving(),
			 $this->SqlGroupOrderBy(), $sFilter, $sSort);
	}

	// Report detail SQL
	function DetailSQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = "";
		return ew_BuildSelectSql($this->SqlDetailSelect(), $this->SqlDetailWhere(),
			$this->SqlDetailGroupBy(), $this->SqlDetailHaving(),
			$this->SqlDetailOrderBy(), $sFilter, $sSort);
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "prioridades_do_financeiro_2d_agrupadoreport.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "prioridades_do_financeiro_2d_agrupadoreport.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("", $this->UrlParm($parm));
		else
			return $this->KeyUrl("", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(141, 201, 203, 128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET)) {

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			$ar[] = $key;
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {
		global $conn;

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$Prioridades_do_Financeiro_2D_Agrupado_report = NULL; // Initialize page object first

class cPrioridades_do_Financeiro_2D_Agrupado_report extends cPrioridades_do_Financeiro_2D_Agrupado {

	// Page ID
	var $PageID = 'report';

	// Project ID
	var $ProjectID = "{0602B820-DE72-4661-BB21-3716ACE9CB5F}";

	// Table name
	var $TableName = 'Prioridades do Financeiro - Agrupado';

	// Page object name
	var $PageObjName = 'Prioridades_do_Financeiro_2D_Agrupado_report';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
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
		return TRUE;
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

		// Table object (Prioridades_do_Financeiro_2D_Agrupado)
		if (!isset($GLOBALS["Prioridades_do_Financeiro_2D_Agrupado"])) {
			$GLOBALS["Prioridades_do_Financeiro_2D_Agrupado"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["Prioridades_do_Financeiro_2D_Agrupado"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'report', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'Prioridades do Financeiro - Agrupado', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "span";
		$this->ExportOptions->TagClassName = "ewExportOption";
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
		if (!$Security->CanReport()) {
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
		}
		$gsExport = $this->Export; // Get export parameter, used in header
		$gsExportFile = $this->TableVar; // Get export file, used in header
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action

		// Setup export options
		$this->SetupExportOptions();

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
		global $EW_EXPORT_REPORT;

		// Page Unload event
		$this->Page_Unload();

		// Export
		if ($this->Export <> "" && array_key_exists($this->Export, $EW_EXPORT_REPORT)) {
			$sContent = ob_get_contents();
			$fn = $EW_EXPORT_REPORT[$this->Export];
			$this->$fn($sContent);
			if ($this->Export == "email") { // Email
				ob_end_clean();
				$conn->Close(); // Close connection
				header("Location: " . ew_CurrentPage());
				exit();
			}
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
	var $ExportOptions; // Export options
	var $RecCnt = 0;
	var $ReportSql = "";
	var $ReportFilter = "";
	var $DefaultFilter = "";
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $MasterRecordExists;
	var $Command;
	var $DtlRecordCount;
	var $ReportGroups;
	var $ReportCounts;
	var $LevelBreak;
	var $ReportTotals;
	var $ReportMaxs;
	var $ReportMins;
	var $Recordset;
	var $DetailRecordset;
	var $RecordExists;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		$this->ReportGroups = &ew_InitArray(2, NULL);
		$this->ReportCounts = &ew_InitArray(2, 0);
		$this->LevelBreak = &ew_InitArray(2, FALSE);
		$this->ReportTotals = &ew_Init2DArray(2, 6, 0);
		$this->ReportMaxs = &ew_Init2DArray(2, 6, 0);
		$this->ReportMins = &ew_Init2DArray(2, 6, 0);

		// Set up Breadcrumb
		$this->SetupBreadcrumb();
	}

	// Check level break
	function ChkLvlBreak() {
		$this->LevelBreak[1] = FALSE;
		if ($this->RecCnt == 0) { // Start Or End of Recordset
			$this->LevelBreak[1] = TRUE;
		} else {
			if (!ew_CompareValue($this->centroCusto->CurrentValue, $this->ReportGroups[0])) {
				$this->LevelBreak[1] = TRUE;
			}
		}
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// centroCusto
		// projeto
		// status
		// titulo
		// dataPrevista
		// prioridade

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// centroCusto
			$this->centroCusto->ViewValue = $this->centroCusto->CurrentValue;
			$this->centroCusto->ViewCustomAttributes = "";

			// projeto
			$this->projeto->ViewValue = $this->projeto->CurrentValue;
			$this->projeto->ViewCustomAttributes = "";

			// status
			$this->status->ViewValue = $this->status->CurrentValue;
			$this->status->ViewCustomAttributes = "";

			// titulo
			$this->titulo->ViewValue = $this->titulo->CurrentValue;
			$this->titulo->ViewCustomAttributes = "";

			// dataPrevista
			$this->dataPrevista->ViewValue = $this->dataPrevista->CurrentValue;
			$this->dataPrevista->ViewValue = ew_FormatDateTime($this->dataPrevista->ViewValue, 7);
			$this->dataPrevista->ViewCustomAttributes = "";

			// prioridade
			$this->prioridade->ViewValue = $this->prioridade->CurrentValue;
			$this->prioridade->ViewCustomAttributes = "";

			// centroCusto
			$this->centroCusto->LinkCustomAttributes = "";
			$this->centroCusto->HrefValue = "";
			$this->centroCusto->TooltipValue = "";

			// projeto
			$this->projeto->LinkCustomAttributes = "";
			$this->projeto->HrefValue = "";
			$this->projeto->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// titulo
			$this->titulo->LinkCustomAttributes = "";
			$this->titulo->HrefValue = "";
			$this->titulo->TooltipValue = "";

			// dataPrevista
			$this->dataPrevista->LinkCustomAttributes = "";
			$this->dataPrevista->HrefValue = "";
			$this->dataPrevista->TooltipValue = "";

			// prioridade
			$this->prioridade->LinkCustomAttributes = "";
			$this->prioridade->HrefValue = "";
			$this->prioridade->TooltipValue = "";
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

		// Drop down button for export
		$this->ExportOptions->UseDropDownButton = FALSE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide options for export
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$url = ew_CurrentUrl();
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("report", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", $url, $this->TableVar);
	}

	// Export report to HTML
	function ExportReportHtml($html) {

		//global $gsExportFile;
		//header('Content-Type: text/html' . (EW_CHARSET <> '' ? ';charset=' . EW_CHARSET : ''));
		//header('Content-Disposition: attachment; filename=' . $gsExportFile . '.html');
		//echo $html;

	}

	// Export report to WORD
	function ExportReportWord($html) {
		global $gsExportFile;
		header('Content-Type: application/vnd.ms-word' . (EW_CHARSET <> '' ? ';charset=' . EW_CHARSET : ''));
		header('Content-Disposition: attachment; filename=' . $gsExportFile . '.doc');
		echo $html;
	}

	// Export report to EXCEL
	function ExportReportExcel($html) {
		global $gsExportFile;
		header('Content-Type: application/vnd.ms-excel' . (EW_CHARSET <> '' ? ';charset=' . EW_CHARSET : ''));
		header('Content-Disposition: attachment; filename=' . $gsExportFile . '.xls');
		echo $html;
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
if (!isset($Prioridades_do_Financeiro_2D_Agrupado_report)) $Prioridades_do_Financeiro_2D_Agrupado_report = new cPrioridades_do_Financeiro_2D_Agrupado_report();

// Page init
$Prioridades_do_Financeiro_2D_Agrupado_report->Page_Init();

// Page main
$Prioridades_do_Financeiro_2D_Agrupado_report->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$Prioridades_do_Financeiro_2D_Agrupado_report->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($Prioridades_do_Financeiro_2D_Agrupado->Export == "") { ?>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($Prioridades_do_Financeiro_2D_Agrupado->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php
$Prioridades_do_Financeiro_2D_Agrupado_report->DefaultFilter = "";
$Prioridades_do_Financeiro_2D_Agrupado_report->ReportFilter = $Prioridades_do_Financeiro_2D_Agrupado_report->DefaultFilter;
if (!$Security->CanReport()) {
	if ($Prioridades_do_Financeiro_2D_Agrupado_report->ReportFilter <> "") $Prioridades_do_Financeiro_2D_Agrupado_report->ReportFilter .= " AND ";
	$Prioridades_do_Financeiro_2D_Agrupado_report->ReportFilter .= "(0=1)";
}
if ($Prioridades_do_Financeiro_2D_Agrupado_report->DbDetailFilter <> "") {
	if ($Prioridades_do_Financeiro_2D_Agrupado_report->ReportFilter <> "") $Prioridades_do_Financeiro_2D_Agrupado_report->ReportFilter .= " AND ";
	$Prioridades_do_Financeiro_2D_Agrupado_report->ReportFilter .= "(" . $Prioridades_do_Financeiro_2D_Agrupado_report->DbDetailFilter . ")";
}

// Set up filter and load Group level sql
$Prioridades_do_Financeiro_2D_Agrupado->CurrentFilter = $Prioridades_do_Financeiro_2D_Agrupado_report->ReportFilter;
$Prioridades_do_Financeiro_2D_Agrupado_report->ReportSql = $Prioridades_do_Financeiro_2D_Agrupado->GroupSQL();

// Load recordset
$Prioridades_do_Financeiro_2D_Agrupado_report->Recordset = $conn->Execute($Prioridades_do_Financeiro_2D_Agrupado_report->ReportSql);
$Prioridades_do_Financeiro_2D_Agrupado_report->RecordExists = !$Prioridades_do_Financeiro_2D_Agrupado_report->Recordset->EOF;
?>
<?php if ($Prioridades_do_Financeiro_2D_Agrupado->Export == "") { ?>
<?php if ($Prioridades_do_Financeiro_2D_Agrupado_report->RecordExists) { ?>
<div class="ewViewExportOptions"><?php $Prioridades_do_Financeiro_2D_Agrupado_report->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php } ?>
<?php $Prioridades_do_Financeiro_2D_Agrupado_report->ShowPageHeader(); ?>
<form method="post">
<table class="ewReportTable">
<?php

// Get First Row
if ($Prioridades_do_Financeiro_2D_Agrupado_report->RecordExists) {
	$Prioridades_do_Financeiro_2D_Agrupado->centroCusto->setDbValue($Prioridades_do_Financeiro_2D_Agrupado_report->Recordset->fields('centroCusto'));
	$Prioridades_do_Financeiro_2D_Agrupado_report->ReportGroups[0] = $Prioridades_do_Financeiro_2D_Agrupado->centroCusto->DbValue;
}
$Prioridades_do_Financeiro_2D_Agrupado_report->RecCnt = 0;
$Prioridades_do_Financeiro_2D_Agrupado_report->ReportCounts[0] = 0;
$Prioridades_do_Financeiro_2D_Agrupado_report->ChkLvlBreak();
while (!$Prioridades_do_Financeiro_2D_Agrupado_report->Recordset->EOF) {

	// Render for view
	$Prioridades_do_Financeiro_2D_Agrupado->RowType = EW_ROWTYPE_VIEW;
	$Prioridades_do_Financeiro_2D_Agrupado->ResetAttrs();
	$Prioridades_do_Financeiro_2D_Agrupado_report->RenderRow();

	// Show group headers
	if ($Prioridades_do_Financeiro_2D_Agrupado_report->LevelBreak[1]) { // Reset counter and aggregation
?>
	<tr><td class="ewGroupField"><?php echo $Prioridades_do_Financeiro_2D_Agrupado->centroCusto->FldCaption() ?></td>
	<td colspan=5 class="ewGroupName">
<span<?php echo $Prioridades_do_Financeiro_2D_Agrupado->centroCusto->ViewAttributes() ?>>
<?php echo $Prioridades_do_Financeiro_2D_Agrupado->centroCusto->ViewValue ?></span>
</td></tr>
<?php
	}

	// Get detail records
	$Prioridades_do_Financeiro_2D_Agrupado_report->ReportFilter = $Prioridades_do_Financeiro_2D_Agrupado_report->DefaultFilter;
	if ($Prioridades_do_Financeiro_2D_Agrupado_report->ReportFilter <> "") $Prioridades_do_Financeiro_2D_Agrupado_report->ReportFilter .= " AND ";
	if (is_null($Prioridades_do_Financeiro_2D_Agrupado->centroCusto->CurrentValue)) {
		$Prioridades_do_Financeiro_2D_Agrupado_report->ReportFilter .= "([centroCusto] IS NULL)";
	} else {
		$Prioridades_do_Financeiro_2D_Agrupado_report->ReportFilter .= "([centroCusto] = '" . ew_AdjustSql($Prioridades_do_Financeiro_2D_Agrupado->centroCusto->CurrentValue) . "')";
	}
	if ($Prioridades_do_Financeiro_2D_Agrupado_report->DbDetailFilter <> "") {
		if ($Prioridades_do_Financeiro_2D_Agrupado_report->ReportFilter <> "")
			$Prioridades_do_Financeiro_2D_Agrupado_report->ReportFilter .= " AND ";
		$Prioridades_do_Financeiro_2D_Agrupado_report->ReportFilter .= "(" . $Prioridades_do_Financeiro_2D_Agrupado_report->DbDetailFilter . ")";
	}
	if (!$Security->CanReport()) {
		if ($sFilter <> "") $sFilter .= " AND ";
		$sFilter .= "(0=1)";
	}

	// Set up detail SQL
	$Prioridades_do_Financeiro_2D_Agrupado->CurrentFilter = $Prioridades_do_Financeiro_2D_Agrupado_report->ReportFilter;
	$Prioridades_do_Financeiro_2D_Agrupado_report->ReportSql = $Prioridades_do_Financeiro_2D_Agrupado->DetailSQL();

	// Load detail records
	$Prioridades_do_Financeiro_2D_Agrupado_report->DetailRecordset = $conn->Execute($Prioridades_do_Financeiro_2D_Agrupado_report->ReportSql);
	$Prioridades_do_Financeiro_2D_Agrupado_report->DtlRecordCount = $Prioridades_do_Financeiro_2D_Agrupado_report->DetailRecordset->RecordCount();

	// Initialize aggregates
	if (!$Prioridades_do_Financeiro_2D_Agrupado_report->DetailRecordset->EOF) {
		$Prioridades_do_Financeiro_2D_Agrupado_report->RecCnt++;
	}
	if ($Prioridades_do_Financeiro_2D_Agrupado_report->RecCnt == 1) {
		$Prioridades_do_Financeiro_2D_Agrupado_report->ReportCounts[0] = 0;
	}
	for ($i = 1; $i <= 1; $i++) {
		if ($Prioridades_do_Financeiro_2D_Agrupado_report->LevelBreak[$i]) { // Reset counter and aggregation
			$Prioridades_do_Financeiro_2D_Agrupado_report->ReportCounts[$i] = 0;
		}
	}
	$Prioridades_do_Financeiro_2D_Agrupado_report->ReportCounts[0] += $Prioridades_do_Financeiro_2D_Agrupado_report->DtlRecordCount;
	$Prioridades_do_Financeiro_2D_Agrupado_report->ReportCounts[1] += $Prioridades_do_Financeiro_2D_Agrupado_report->DtlRecordCount;
	if ($Prioridades_do_Financeiro_2D_Agrupado_report->RecordExists) {
?>
	<tr>
		<td><div class="ewGroupIndent"></div></td>
		<td class="ewGroupHeader"><?php echo $Prioridades_do_Financeiro_2D_Agrupado->projeto->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Prioridades_do_Financeiro_2D_Agrupado->status->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Prioridades_do_Financeiro_2D_Agrupado->titulo->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Prioridades_do_Financeiro_2D_Agrupado->dataPrevista->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Prioridades_do_Financeiro_2D_Agrupado->prioridade->FldCaption() ?></td>
	</tr>
<?php
	}
	while (!$Prioridades_do_Financeiro_2D_Agrupado_report->DetailRecordset->EOF) {
		$Prioridades_do_Financeiro_2D_Agrupado->projeto->setDbValue($Prioridades_do_Financeiro_2D_Agrupado_report->DetailRecordset->fields('projeto'));
		$Prioridades_do_Financeiro_2D_Agrupado->status->setDbValue($Prioridades_do_Financeiro_2D_Agrupado_report->DetailRecordset->fields('status'));
		$Prioridades_do_Financeiro_2D_Agrupado->titulo->setDbValue($Prioridades_do_Financeiro_2D_Agrupado_report->DetailRecordset->fields('titulo'));
		$Prioridades_do_Financeiro_2D_Agrupado->dataPrevista->setDbValue($Prioridades_do_Financeiro_2D_Agrupado_report->DetailRecordset->fields('dataPrevista'));
		$Prioridades_do_Financeiro_2D_Agrupado->prioridade->setDbValue($Prioridades_do_Financeiro_2D_Agrupado_report->DetailRecordset->fields('prioridade'));

		// Render for view
		$Prioridades_do_Financeiro_2D_Agrupado->RowType = EW_ROWTYPE_VIEW;
		$Prioridades_do_Financeiro_2D_Agrupado->ResetAttrs();
		$Prioridades_do_Financeiro_2D_Agrupado_report->RenderRow();
?>
	<tr>
		<td><div class="ewGroupIndent"></div></td>
		<td<?php echo $Prioridades_do_Financeiro_2D_Agrupado->projeto->CellAttributes() ?>>
<span<?php echo $Prioridades_do_Financeiro_2D_Agrupado->projeto->ViewAttributes() ?>>
<?php echo $Prioridades_do_Financeiro_2D_Agrupado->projeto->ViewValue ?></span>
</td>
		<td<?php echo $Prioridades_do_Financeiro_2D_Agrupado->status->CellAttributes() ?>>
<span<?php echo $Prioridades_do_Financeiro_2D_Agrupado->status->ViewAttributes() ?>>
<?php echo $Prioridades_do_Financeiro_2D_Agrupado->status->ViewValue ?></span>
</td>
		<td<?php echo $Prioridades_do_Financeiro_2D_Agrupado->titulo->CellAttributes() ?>>
<span<?php echo $Prioridades_do_Financeiro_2D_Agrupado->titulo->ViewAttributes() ?>>
<?php echo $Prioridades_do_Financeiro_2D_Agrupado->titulo->ViewValue ?></span>
</td>
		<td<?php echo $Prioridades_do_Financeiro_2D_Agrupado->dataPrevista->CellAttributes() ?>>
<span<?php echo $Prioridades_do_Financeiro_2D_Agrupado->dataPrevista->ViewAttributes() ?>>
<?php echo $Prioridades_do_Financeiro_2D_Agrupado->dataPrevista->ViewValue ?></span>
</td>
		<td<?php echo $Prioridades_do_Financeiro_2D_Agrupado->prioridade->CellAttributes() ?>>
<span<?php echo $Prioridades_do_Financeiro_2D_Agrupado->prioridade->ViewAttributes() ?>>
<?php echo $Prioridades_do_Financeiro_2D_Agrupado->prioridade->ViewValue ?></span>
</td>
	</tr>
<?php
		$Prioridades_do_Financeiro_2D_Agrupado_report->DetailRecordset->MoveNext();
	}
	$Prioridades_do_Financeiro_2D_Agrupado_report->DetailRecordset->Close();

	// Save old group data
	$Prioridades_do_Financeiro_2D_Agrupado_report->ReportGroups[0] = $Prioridades_do_Financeiro_2D_Agrupado->centroCusto->CurrentValue;

	// Get next record
	$Prioridades_do_Financeiro_2D_Agrupado_report->Recordset->MoveNext();
	if ($Prioridades_do_Financeiro_2D_Agrupado_report->Recordset->EOF) {
		$Prioridades_do_Financeiro_2D_Agrupado_report->RecCnt = 0; // EOF, force all level breaks
	} else {
		$Prioridades_do_Financeiro_2D_Agrupado->centroCusto->setDbValue($Prioridades_do_Financeiro_2D_Agrupado_report->Recordset->fields('centroCusto'));
	}
	$Prioridades_do_Financeiro_2D_Agrupado_report->ChkLvlBreak();

	// Show footers
	if ($Prioridades_do_Financeiro_2D_Agrupado_report->LevelBreak[1]) {
		$Prioridades_do_Financeiro_2D_Agrupado->centroCusto->CurrentValue = $Prioridades_do_Financeiro_2D_Agrupado_report->ReportGroups[0];

		// Render row for view
		$Prioridades_do_Financeiro_2D_Agrupado->RowType = EW_ROWTYPE_VIEW;
		$Prioridades_do_Financeiro_2D_Agrupado->ResetAttrs();
		$Prioridades_do_Financeiro_2D_Agrupado_report->RenderRow();
		$Prioridades_do_Financeiro_2D_Agrupado->centroCusto->CurrentValue = $Prioridades_do_Financeiro_2D_Agrupado->centroCusto->DbValue;
?>
<?php
}
}

// Close recordset
$Prioridades_do_Financeiro_2D_Agrupado_report->Recordset->Close();
?>
<?php if ($Prioridades_do_Financeiro_2D_Agrupado_report->RecordExists) { ?>
	<tr><td colspan=6>&nbsp;<br></td></tr>
	<tr><td colspan=6 class="ewGrandSummary"><?php echo $Language->Phrase("RptGrandTotal") ?>&nbsp;(<?php echo ew_FormatNumber($Prioridades_do_Financeiro_2D_Agrupado_report->ReportCounts[0], 0) ?>&nbsp;<?php echo $Language->Phrase("RptDtlRec") ?>)</td></tr>
<?php } ?>
<?php if ($Prioridades_do_Financeiro_2D_Agrupado_report->RecordExists) { ?>
	<tr><td colspan=6>&nbsp;<br></td></tr>
<?php } else { ?>
	<tr><td><?php echo $Language->Phrase("NoRecord") ?></td></tr>
<?php } ?>
</table>
</form>
<?php
$Prioridades_do_Financeiro_2D_Agrupado_report->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($Prioridades_do_Financeiro_2D_Agrupado->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$Prioridades_do_Financeiro_2D_Agrupado_report->Page_Terminate();
?>
