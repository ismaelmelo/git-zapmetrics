<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php

// Global variable for table object
$Ambientes_por_Tipo_de_NegF3cio = NULL;

//
// Table class for Ambientes por Tipo de Negócio
//
class cAmbientes_por_Tipo_de_NegF3cio extends cTableBase {
	var $nu_ambiente;
	var $no_ambiente;
	var $ds_caracteristicas;
	var $nu_tpNegocio;
	var $nu_plataforma;
	var $nu_tpSistema;
	var $nu_roteiro;
	var $ic_ativo;
	var $nu_ordem;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'Ambientes_por_Tipo_de_NegF3cio';
		$this->TableName = 'Ambientes por Tipo de Negócio';
		$this->TableType = 'REPORT';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->PrinterFriendlyForPdf = TRUE;
		$this->UserIDAllowSecurity = 0; // User ID Allow

		// nu_ambiente
		$this->nu_ambiente = new cField('Ambientes_por_Tipo_de_NegF3cio', 'Ambientes por Tipo de Negócio', 'x_nu_ambiente', 'nu_ambiente', '[nu_ambiente]', 'CAST([nu_ambiente] AS NVARCHAR)', 3, -1, FALSE, '[nu_ambiente]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_ambiente->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_ambiente'] = &$this->nu_ambiente;

		// no_ambiente
		$this->no_ambiente = new cField('Ambientes_por_Tipo_de_NegF3cio', 'Ambientes por Tipo de Negócio', 'x_no_ambiente', 'no_ambiente', '[no_ambiente]', '[no_ambiente]', 200, -1, FALSE, '[no_ambiente]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_ambiente'] = &$this->no_ambiente;

		// ds_caracteristicas
		$this->ds_caracteristicas = new cField('Ambientes_por_Tipo_de_NegF3cio', 'Ambientes por Tipo de Negócio', 'x_ds_caracteristicas', 'ds_caracteristicas', '[ds_caracteristicas]', '[ds_caracteristicas]', 201, -1, FALSE, '[ds_caracteristicas]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_caracteristicas'] = &$this->ds_caracteristicas;

		// nu_tpNegocio
		$this->nu_tpNegocio = new cField('Ambientes_por_Tipo_de_NegF3cio', 'Ambientes por Tipo de Negócio', 'x_nu_tpNegocio', 'nu_tpNegocio', '[nu_tpNegocio]', 'CAST([nu_tpNegocio] AS NVARCHAR)', 3, -1, FALSE, '[nu_tpNegocio]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_tpNegocio->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_tpNegocio'] = &$this->nu_tpNegocio;

		// nu_plataforma
		$this->nu_plataforma = new cField('Ambientes_por_Tipo_de_NegF3cio', 'Ambientes por Tipo de Negócio', 'x_nu_plataforma', 'nu_plataforma', '[nu_plataforma]', 'CAST([nu_plataforma] AS NVARCHAR)', 3, -1, FALSE, '[nu_plataforma]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_plataforma->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_plataforma'] = &$this->nu_plataforma;

		// nu_tpSistema
		$this->nu_tpSistema = new cField('Ambientes_por_Tipo_de_NegF3cio', 'Ambientes por Tipo de Negócio', 'x_nu_tpSistema', 'nu_tpSistema', '[nu_tpSistema]', 'CAST([nu_tpSistema] AS NVARCHAR)', 3, -1, FALSE, '[nu_tpSistema]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_tpSistema->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_tpSistema'] = &$this->nu_tpSistema;

		// nu_roteiro
		$this->nu_roteiro = new cField('Ambientes_por_Tipo_de_NegF3cio', 'Ambientes por Tipo de Negócio', 'x_nu_roteiro', 'nu_roteiro', '[nu_roteiro]', 'CAST([nu_roteiro] AS NVARCHAR)', 3, -1, FALSE, '[nu_roteiro]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_roteiro->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_roteiro'] = &$this->nu_roteiro;

		// ic_ativo
		$this->ic_ativo = new cField('Ambientes_por_Tipo_de_NegF3cio', 'Ambientes por Tipo de Negócio', 'x_ic_ativo', 'ic_ativo', '[ic_ativo]', '[ic_ativo]', 129, -1, FALSE, '[ic_ativo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_ativo'] = &$this->ic_ativo;

		// nu_ordem
		$this->nu_ordem = new cField('Ambientes_por_Tipo_de_NegF3cio', 'Ambientes por Tipo de Negócio', 'x_nu_ordem', 'nu_ordem', '[nu_ordem]', 'CAST([nu_ordem] AS NVARCHAR)', 3, -1, FALSE, '[nu_ordem]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_ordem->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_ordem'] = &$this->nu_ordem;
	}

	// Report group level SQL
	function SqlGroupSelect() { // Select
		return "SELECT DISTINCT [nu_tpNegocio] FROM [dbo].[ambiente]";
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
		return "[nu_tpNegocio] ASC";
	}

	// Report detail level SQL
	function SqlDetailSelect() { // Select
		return "SELECT * FROM [dbo].[ambiente]";
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
		return "[no_ambiente] ASC";
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
			return "ambientes_por_tipo_de_negf3cioreport.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "ambientes_por_tipo_de_negf3cioreport.php";
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
		if (!is_null($this->nu_ambiente->CurrentValue)) {
			$sUrl .= "nu_ambiente=" . urlencode($this->nu_ambiente->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
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
			$arKeys[] = @$_GET["nu_ambiente"]; // nu_ambiente

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_numeric($key))
				continue;
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
			$this->nu_ambiente->CurrentValue = $key;
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

$Ambientes_por_Tipo_de_NegF3cio_report = NULL; // Initialize page object first

class cAmbientes_por_Tipo_de_NegF3cio_report extends cAmbientes_por_Tipo_de_NegF3cio {

	// Page ID
	var $PageID = 'report';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'Ambientes por Tipo de Negócio';

	// Page object name
	var $PageObjName = 'Ambientes_por_Tipo_de_NegF3cio_report';

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

		// Table object (Ambientes_por_Tipo_de_NegF3cio)
		if (!isset($GLOBALS["Ambientes_por_Tipo_de_NegF3cio"])) {
			$GLOBALS["Ambientes_por_Tipo_de_NegF3cio"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["Ambientes_por_Tipo_de_NegF3cio"];
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
			define("EW_TABLE_NAME", 'Ambientes por Tipo de Negócio', TRUE);

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
			if (!ew_CompareValue($this->nu_tpNegocio->CurrentValue, $this->ReportGroups[0])) {
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
		// nu_ambiente
		// no_ambiente
		// ds_caracteristicas
		// nu_tpNegocio
		// nu_plataforma
		// nu_tpSistema
		// nu_roteiro
		// ic_ativo
		// nu_ordem

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// no_ambiente
			$this->no_ambiente->ViewValue = $this->no_ambiente->CurrentValue;
			$this->no_ambiente->ViewCustomAttributes = "";

			// nu_tpNegocio
			if (strval($this->nu_tpNegocio->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpNegocio]" . ew_SearchString("=", $this->nu_tpNegocio->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpNegocio], [no_tpNegocio] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpnegocio]";
			$sWhereWrk = "";
			$lookuptblfilter = "[co_ativo] = 'S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpNegocio->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpNegocio->ViewValue = $this->nu_tpNegocio->CurrentValue;
				}
			} else {
				$this->nu_tpNegocio->ViewValue = NULL;
			}
			$this->nu_tpNegocio->ViewCustomAttributes = "";

			// nu_plataforma
			if (strval($this->nu_plataforma->CurrentValue) <> "") {
				$sFilterWrk = "[nu_plataforma]" . ew_SearchString("=", $this->nu_plataforma->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_plataforma], [no_plataforma] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[plataforma]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_plataforma->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_plataforma->ViewValue = $this->nu_plataforma->CurrentValue;
				}
			} else {
				$this->nu_plataforma->ViewValue = NULL;
			}
			$this->nu_plataforma->ViewCustomAttributes = "";

			// nu_tpSistema
			if (strval($this->nu_tpSistema->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpSistema]" . ew_SearchString("=", $this->nu_tpSistema->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpSistema], [no_tpSistema] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpsistema]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpSistema->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpSistema->ViewValue = $this->nu_tpSistema->CurrentValue;
				}
			} else {
				$this->nu_tpSistema->ViewValue = NULL;
			}
			$this->nu_tpSistema->ViewCustomAttributes = "";

			// nu_roteiro
			if (strval($this->nu_roteiro->CurrentValue) <> "") {
				$sFilterWrk = "[nu_roteiro]" . ew_SearchString("=", $this->nu_roteiro->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_roteiro], [no_roteiro] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[roteiro]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_roteiro->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_roteiro->ViewValue = $this->nu_roteiro->CurrentValue;
				}
			} else {
				$this->nu_roteiro->ViewValue = NULL;
			}
			$this->nu_roteiro->ViewCustomAttributes = "";

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

			// no_ambiente
			$this->no_ambiente->LinkCustomAttributes = "";
			$this->no_ambiente->HrefValue = "";
			$this->no_ambiente->TooltipValue = "";

			// nu_tpNegocio
			$this->nu_tpNegocio->LinkCustomAttributes = "";
			$this->nu_tpNegocio->HrefValue = "";
			$this->nu_tpNegocio->TooltipValue = "";

			// nu_plataforma
			$this->nu_plataforma->LinkCustomAttributes = "";
			$this->nu_plataforma->HrefValue = "";
			$this->nu_plataforma->TooltipValue = "";

			// nu_tpSistema
			$this->nu_tpSistema->LinkCustomAttributes = "";
			$this->nu_tpSistema->HrefValue = "";
			$this->nu_tpSistema->TooltipValue = "";

			// nu_roteiro
			$this->nu_roteiro->LinkCustomAttributes = "";
			$this->nu_roteiro->HrefValue = "";
			$this->nu_roteiro->TooltipValue = "";

			// ic_ativo
			$this->ic_ativo->LinkCustomAttributes = "";
			$this->ic_ativo->HrefValue = "";
			$this->ic_ativo->TooltipValue = "";
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
if (!isset($Ambientes_por_Tipo_de_NegF3cio_report)) $Ambientes_por_Tipo_de_NegF3cio_report = new cAmbientes_por_Tipo_de_NegF3cio_report();

// Page init
$Ambientes_por_Tipo_de_NegF3cio_report->Page_Init();

// Page main
$Ambientes_por_Tipo_de_NegF3cio_report->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$Ambientes_por_Tipo_de_NegF3cio_report->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($Ambientes_por_Tipo_de_NegF3cio->Export == "") { ?>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($Ambientes_por_Tipo_de_NegF3cio->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php
$Ambientes_por_Tipo_de_NegF3cio_report->DefaultFilter = "";
$Ambientes_por_Tipo_de_NegF3cio_report->ReportFilter = $Ambientes_por_Tipo_de_NegF3cio_report->DefaultFilter;
if (!$Security->CanReport()) {
	if ($Ambientes_por_Tipo_de_NegF3cio_report->ReportFilter <> "") $Ambientes_por_Tipo_de_NegF3cio_report->ReportFilter .= " AND ";
	$Ambientes_por_Tipo_de_NegF3cio_report->ReportFilter .= "(0=1)";
}
if ($Ambientes_por_Tipo_de_NegF3cio_report->DbDetailFilter <> "") {
	if ($Ambientes_por_Tipo_de_NegF3cio_report->ReportFilter <> "") $Ambientes_por_Tipo_de_NegF3cio_report->ReportFilter .= " AND ";
	$Ambientes_por_Tipo_de_NegF3cio_report->ReportFilter .= "(" . $Ambientes_por_Tipo_de_NegF3cio_report->DbDetailFilter . ")";
}

// Set up filter and load Group level sql
$Ambientes_por_Tipo_de_NegF3cio->CurrentFilter = $Ambientes_por_Tipo_de_NegF3cio_report->ReportFilter;
$Ambientes_por_Tipo_de_NegF3cio_report->ReportSql = $Ambientes_por_Tipo_de_NegF3cio->GroupSQL();

// Load recordset
$Ambientes_por_Tipo_de_NegF3cio_report->Recordset = $conn->Execute($Ambientes_por_Tipo_de_NegF3cio_report->ReportSql);
$Ambientes_por_Tipo_de_NegF3cio_report->RecordExists = !$Ambientes_por_Tipo_de_NegF3cio_report->Recordset->EOF;
?>
<?php if ($Ambientes_por_Tipo_de_NegF3cio->Export == "") { ?>
<?php if ($Ambientes_por_Tipo_de_NegF3cio_report->RecordExists) { ?>
<div class="ewViewExportOptions"><?php $Ambientes_por_Tipo_de_NegF3cio_report->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php } ?>
<?php $Ambientes_por_Tipo_de_NegF3cio_report->ShowPageHeader(); ?>
<form method="post">
<table class="ewReportTable">
<?php

// Get First Row
if ($Ambientes_por_Tipo_de_NegF3cio_report->RecordExists) {
	$Ambientes_por_Tipo_de_NegF3cio->nu_tpNegocio->setDbValue($Ambientes_por_Tipo_de_NegF3cio_report->Recordset->fields('nu_tpNegocio'));
	$Ambientes_por_Tipo_de_NegF3cio_report->ReportGroups[0] = $Ambientes_por_Tipo_de_NegF3cio->nu_tpNegocio->DbValue;
}
$Ambientes_por_Tipo_de_NegF3cio_report->RecCnt = 0;
$Ambientes_por_Tipo_de_NegF3cio_report->ReportCounts[0] = 0;
$Ambientes_por_Tipo_de_NegF3cio_report->ChkLvlBreak();
while (!$Ambientes_por_Tipo_de_NegF3cio_report->Recordset->EOF) {

	// Render for view
	$Ambientes_por_Tipo_de_NegF3cio->RowType = EW_ROWTYPE_VIEW;
	$Ambientes_por_Tipo_de_NegF3cio->ResetAttrs();
	$Ambientes_por_Tipo_de_NegF3cio_report->RenderRow();

	// Show group headers
	if ($Ambientes_por_Tipo_de_NegF3cio_report->LevelBreak[1]) { // Reset counter and aggregation
?>
	<tr><td class="ewGroupField"><?php echo $Ambientes_por_Tipo_de_NegF3cio->nu_tpNegocio->FldCaption() ?></td>
	<td colspan=5 class="ewGroupName">
<span<?php echo $Ambientes_por_Tipo_de_NegF3cio->nu_tpNegocio->ViewAttributes() ?>>
<?php echo $Ambientes_por_Tipo_de_NegF3cio->nu_tpNegocio->ViewValue ?></span>
</td></tr>
<?php
	}

	// Get detail records
	$Ambientes_por_Tipo_de_NegF3cio_report->ReportFilter = $Ambientes_por_Tipo_de_NegF3cio_report->DefaultFilter;
	if ($Ambientes_por_Tipo_de_NegF3cio_report->ReportFilter <> "") $Ambientes_por_Tipo_de_NegF3cio_report->ReportFilter .= " AND ";
	if (is_null($Ambientes_por_Tipo_de_NegF3cio->nu_tpNegocio->CurrentValue)) {
		$Ambientes_por_Tipo_de_NegF3cio_report->ReportFilter .= "([nu_tpNegocio] IS NULL)";
	} else {
		$Ambientes_por_Tipo_de_NegF3cio_report->ReportFilter .= "([nu_tpNegocio] = " . ew_AdjustSql($Ambientes_por_Tipo_de_NegF3cio->nu_tpNegocio->CurrentValue) . ")";
	}
	if ($Ambientes_por_Tipo_de_NegF3cio_report->DbDetailFilter <> "") {
		if ($Ambientes_por_Tipo_de_NegF3cio_report->ReportFilter <> "")
			$Ambientes_por_Tipo_de_NegF3cio_report->ReportFilter .= " AND ";
		$Ambientes_por_Tipo_de_NegF3cio_report->ReportFilter .= "(" . $Ambientes_por_Tipo_de_NegF3cio_report->DbDetailFilter . ")";
	}
	if (!$Security->CanReport()) {
		if ($sFilter <> "") $sFilter .= " AND ";
		$sFilter .= "(0=1)";
	}

	// Set up detail SQL
	$Ambientes_por_Tipo_de_NegF3cio->CurrentFilter = $Ambientes_por_Tipo_de_NegF3cio_report->ReportFilter;
	$Ambientes_por_Tipo_de_NegF3cio_report->ReportSql = $Ambientes_por_Tipo_de_NegF3cio->DetailSQL();

	// Load detail records
	$Ambientes_por_Tipo_de_NegF3cio_report->DetailRecordset = $conn->Execute($Ambientes_por_Tipo_de_NegF3cio_report->ReportSql);
	$Ambientes_por_Tipo_de_NegF3cio_report->DtlRecordCount = $Ambientes_por_Tipo_de_NegF3cio_report->DetailRecordset->RecordCount();

	// Initialize aggregates
	if (!$Ambientes_por_Tipo_de_NegF3cio_report->DetailRecordset->EOF) {
		$Ambientes_por_Tipo_de_NegF3cio_report->RecCnt++;
	}
	if ($Ambientes_por_Tipo_de_NegF3cio_report->RecCnt == 1) {
		$Ambientes_por_Tipo_de_NegF3cio_report->ReportCounts[0] = 0;
	}
	for ($i = 1; $i <= 1; $i++) {
		if ($Ambientes_por_Tipo_de_NegF3cio_report->LevelBreak[$i]) { // Reset counter and aggregation
			$Ambientes_por_Tipo_de_NegF3cio_report->ReportCounts[$i] = 0;
		}
	}
	$Ambientes_por_Tipo_de_NegF3cio_report->ReportCounts[0] += $Ambientes_por_Tipo_de_NegF3cio_report->DtlRecordCount;
	$Ambientes_por_Tipo_de_NegF3cio_report->ReportCounts[1] += $Ambientes_por_Tipo_de_NegF3cio_report->DtlRecordCount;
	if ($Ambientes_por_Tipo_de_NegF3cio_report->RecordExists) {
?>
	<tr>
		<td><div class="ewGroupIndent"></div></td>
		<td class="ewGroupHeader"><?php echo $Ambientes_por_Tipo_de_NegF3cio->no_ambiente->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Ambientes_por_Tipo_de_NegF3cio->nu_plataforma->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Ambientes_por_Tipo_de_NegF3cio->nu_tpSistema->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Ambientes_por_Tipo_de_NegF3cio->nu_roteiro->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Ambientes_por_Tipo_de_NegF3cio->ic_ativo->FldCaption() ?></td>
	</tr>
<?php
	}
	while (!$Ambientes_por_Tipo_de_NegF3cio_report->DetailRecordset->EOF) {
		$Ambientes_por_Tipo_de_NegF3cio->no_ambiente->setDbValue($Ambientes_por_Tipo_de_NegF3cio_report->DetailRecordset->fields('no_ambiente'));
		$Ambientes_por_Tipo_de_NegF3cio->nu_plataforma->setDbValue($Ambientes_por_Tipo_de_NegF3cio_report->DetailRecordset->fields('nu_plataforma'));
		$Ambientes_por_Tipo_de_NegF3cio->nu_tpSistema->setDbValue($Ambientes_por_Tipo_de_NegF3cio_report->DetailRecordset->fields('nu_tpSistema'));
		$Ambientes_por_Tipo_de_NegF3cio->nu_roteiro->setDbValue($Ambientes_por_Tipo_de_NegF3cio_report->DetailRecordset->fields('nu_roteiro'));
		$Ambientes_por_Tipo_de_NegF3cio->ic_ativo->setDbValue($Ambientes_por_Tipo_de_NegF3cio_report->DetailRecordset->fields('ic_ativo'));

		// Render for view
		$Ambientes_por_Tipo_de_NegF3cio->RowType = EW_ROWTYPE_VIEW;
		$Ambientes_por_Tipo_de_NegF3cio->ResetAttrs();
		$Ambientes_por_Tipo_de_NegF3cio_report->RenderRow();
?>
	<tr>
		<td><div class="ewGroupIndent"></div></td>
		<td<?php echo $Ambientes_por_Tipo_de_NegF3cio->no_ambiente->CellAttributes() ?>>
<span<?php echo $Ambientes_por_Tipo_de_NegF3cio->no_ambiente->ViewAttributes() ?>>
<?php echo $Ambientes_por_Tipo_de_NegF3cio->no_ambiente->ViewValue ?></span>
</td>
		<td<?php echo $Ambientes_por_Tipo_de_NegF3cio->nu_plataforma->CellAttributes() ?>>
<span<?php echo $Ambientes_por_Tipo_de_NegF3cio->nu_plataforma->ViewAttributes() ?>>
<?php echo $Ambientes_por_Tipo_de_NegF3cio->nu_plataforma->ViewValue ?></span>
</td>
		<td<?php echo $Ambientes_por_Tipo_de_NegF3cio->nu_tpSistema->CellAttributes() ?>>
<span<?php echo $Ambientes_por_Tipo_de_NegF3cio->nu_tpSistema->ViewAttributes() ?>>
<?php echo $Ambientes_por_Tipo_de_NegF3cio->nu_tpSistema->ViewValue ?></span>
</td>
		<td<?php echo $Ambientes_por_Tipo_de_NegF3cio->nu_roteiro->CellAttributes() ?>>
<span<?php echo $Ambientes_por_Tipo_de_NegF3cio->nu_roteiro->ViewAttributes() ?>>
<?php echo $Ambientes_por_Tipo_de_NegF3cio->nu_roteiro->ViewValue ?></span>
</td>
		<td<?php echo $Ambientes_por_Tipo_de_NegF3cio->ic_ativo->CellAttributes() ?>>
<span<?php echo $Ambientes_por_Tipo_de_NegF3cio->ic_ativo->ViewAttributes() ?>>
<?php echo $Ambientes_por_Tipo_de_NegF3cio->ic_ativo->ViewValue ?></span>
</td>
	</tr>
<?php
		$Ambientes_por_Tipo_de_NegF3cio_report->DetailRecordset->MoveNext();
	}
	$Ambientes_por_Tipo_de_NegF3cio_report->DetailRecordset->Close();

	// Save old group data
	$Ambientes_por_Tipo_de_NegF3cio_report->ReportGroups[0] = $Ambientes_por_Tipo_de_NegF3cio->nu_tpNegocio->CurrentValue;

	// Get next record
	$Ambientes_por_Tipo_de_NegF3cio_report->Recordset->MoveNext();
	if ($Ambientes_por_Tipo_de_NegF3cio_report->Recordset->EOF) {
		$Ambientes_por_Tipo_de_NegF3cio_report->RecCnt = 0; // EOF, force all level breaks
	} else {
		$Ambientes_por_Tipo_de_NegF3cio->nu_tpNegocio->setDbValue($Ambientes_por_Tipo_de_NegF3cio_report->Recordset->fields('nu_tpNegocio'));
	}
	$Ambientes_por_Tipo_de_NegF3cio_report->ChkLvlBreak();

	// Show footers
	if ($Ambientes_por_Tipo_de_NegF3cio_report->LevelBreak[1]) {
		$Ambientes_por_Tipo_de_NegF3cio->nu_tpNegocio->CurrentValue = $Ambientes_por_Tipo_de_NegF3cio_report->ReportGroups[0];

		// Render row for view
		$Ambientes_por_Tipo_de_NegF3cio->RowType = EW_ROWTYPE_VIEW;
		$Ambientes_por_Tipo_de_NegF3cio->ResetAttrs();
		$Ambientes_por_Tipo_de_NegF3cio_report->RenderRow();
		$Ambientes_por_Tipo_de_NegF3cio->nu_tpNegocio->CurrentValue = $Ambientes_por_Tipo_de_NegF3cio->nu_tpNegocio->DbValue;
?>
<?php
}
}

// Close recordset
$Ambientes_por_Tipo_de_NegF3cio_report->Recordset->Close();
?>
<?php if ($Ambientes_por_Tipo_de_NegF3cio_report->RecordExists) { ?>
	<tr><td colspan=6>&nbsp;<br></td></tr>
	<tr><td colspan=6 class="ewGrandSummary"><?php echo $Language->Phrase("RptGrandTotal") ?>&nbsp;(<?php echo ew_FormatNumber($Ambientes_por_Tipo_de_NegF3cio_report->ReportCounts[0], 0) ?>&nbsp;<?php echo $Language->Phrase("RptDtlRec") ?>)</td></tr>
<?php } ?>
<?php if ($Ambientes_por_Tipo_de_NegF3cio_report->RecordExists) { ?>
	<tr><td colspan=6>&nbsp;<br></td></tr>
<?php } else { ?>
	<tr><td><?php echo $Language->Phrase("NoRecord") ?></td></tr>
<?php } ?>
</table>
</form>
<?php
$Ambientes_por_Tipo_de_NegF3cio_report->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($Ambientes_por_Tipo_de_NegF3cio->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$Ambientes_por_Tipo_de_NegF3cio_report->Page_Terminate();
?>
