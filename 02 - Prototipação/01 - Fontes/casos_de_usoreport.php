<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php

// Global variable for table object
$Casos_de_Uso = NULL;

//
// Table class for Casos de Uso
//
class cCasos_de_Uso extends cTableBase {
	var $nu_uc;
	var $nu_sistema;
	var $nu_modulo;
	var $co_alternativo;
	var $no_uc;
	var $ds_uc;
	var $nu_stUc;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'Casos_de_Uso';
		$this->TableName = 'Casos de Uso';
		$this->TableType = 'REPORT';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->PrinterFriendlyForPdf = TRUE;
		$this->UserIDAllowSecurity = 0; // User ID Allow

		// nu_uc
		$this->nu_uc = new cField('Casos_de_Uso', 'Casos de Uso', 'x_nu_uc', 'nu_uc', '[nu_uc]', 'CAST([nu_uc] AS NVARCHAR)', 3, -1, FALSE, '[nu_uc]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_uc->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_uc'] = &$this->nu_uc;

		// nu_sistema
		$this->nu_sistema = new cField('Casos_de_Uso', 'Casos de Uso', 'x_nu_sistema', 'nu_sistema', '[nu_sistema]', 'CAST([nu_sistema] AS NVARCHAR)', 3, -1, FALSE, '[nu_sistema]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_sistema->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_sistema'] = &$this->nu_sistema;

		// nu_modulo
		$this->nu_modulo = new cField('Casos_de_Uso', 'Casos de Uso', 'x_nu_modulo', 'nu_modulo', '[nu_modulo]', 'CAST([nu_modulo] AS NVARCHAR)', 3, -1, FALSE, '[nu_modulo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_modulo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_modulo'] = &$this->nu_modulo;

		// co_alternativo
		$this->co_alternativo = new cField('Casos_de_Uso', 'Casos de Uso', 'x_co_alternativo', 'co_alternativo', '[co_alternativo]', '[co_alternativo]', 200, -1, FALSE, '[co_alternativo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_alternativo'] = &$this->co_alternativo;

		// no_uc
		$this->no_uc = new cField('Casos_de_Uso', 'Casos de Uso', 'x_no_uc', 'no_uc', '[no_uc]', '[no_uc]', 200, -1, FALSE, '[no_uc]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_uc'] = &$this->no_uc;

		// ds_uc
		$this->ds_uc = new cField('Casos_de_Uso', 'Casos de Uso', 'x_ds_uc', 'ds_uc', '[ds_uc]', '[ds_uc]', 201, -1, FALSE, '[ds_uc]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_uc'] = &$this->ds_uc;

		// nu_stUc
		$this->nu_stUc = new cField('Casos_de_Uso', 'Casos de Uso', 'x_nu_stUc', 'nu_stUc', '[nu_stUc]', 'CAST([nu_stUc] AS NVARCHAR)', 3, -1, FALSE, '[nu_stUc]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_stUc->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_stUc'] = &$this->nu_stUc;
	}

	// Report group level SQL
	function SqlGroupSelect() { // Select
		return "SELECT DISTINCT [nu_sistema] FROM [dbo].[uc]";
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
		return "[nu_sistema] ASC";
	}

	// Report detail level SQL
	function SqlDetailSelect() { // Select
		return "SELECT * FROM [dbo].[uc]";
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
		return "[co_alternativo] ASC";
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
			return "casos_de_usoreport.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "casos_de_usoreport.php";
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
		if (!is_null($this->nu_uc->CurrentValue)) {
			$sUrl .= "nu_uc=" . urlencode($this->nu_uc->CurrentValue);
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
			$arKeys[] = @$_GET["nu_uc"]; // nu_uc

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
			$this->nu_uc->CurrentValue = $key;
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

$Casos_de_Uso_report = NULL; // Initialize page object first

class cCasos_de_Uso_report extends cCasos_de_Uso {

	// Page ID
	var $PageID = 'report';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'Casos de Uso';

	// Page object name
	var $PageObjName = 'Casos_de_Uso_report';

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

		// Table object (Casos_de_Uso)
		if (!isset($GLOBALS["Casos_de_Uso"])) {
			$GLOBALS["Casos_de_Uso"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["Casos_de_Uso"];
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
			define("EW_TABLE_NAME", 'Casos de Uso', TRUE);

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
		$this->ReportTotals = &ew_Init2DArray(2, 7, 0);
		$this->ReportMaxs = &ew_Init2DArray(2, 7, 0);
		$this->ReportMins = &ew_Init2DArray(2, 7, 0);

		// Set up Breadcrumb
		$this->SetupBreadcrumb();
	}

	// Check level break
	function ChkLvlBreak() {
		$this->LevelBreak[1] = FALSE;
		if ($this->RecCnt == 0) { // Start Or End of Recordset
			$this->LevelBreak[1] = TRUE;
		} else {
			if (!ew_CompareValue($this->nu_sistema->CurrentValue, $this->ReportGroups[0])) {
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
		// nu_uc
		// nu_sistema
		// nu_modulo
		// co_alternativo
		// no_uc
		// ds_uc
		// nu_stUc

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_uc
			$this->nu_uc->ViewValue = $this->nu_uc->CurrentValue;
			$this->nu_uc->ViewCustomAttributes = "";

			// nu_sistema
			if (strval($this->nu_sistema->CurrentValue) <> "") {
				$sFilterWrk = "[nu_sistema]" . ew_SearchString("=", $this->nu_sistema->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_sistema], [co_alternativo] AS [DispFld], [no_sistema] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[sistema]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_sistema->ViewValue = $rswrk->fields('DispFld');
					$this->nu_sistema->ViewValue .= ew_ValueSeparator(1,$this->nu_sistema) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_sistema->ViewValue = $this->nu_sistema->CurrentValue;
				}
			} else {
				$this->nu_sistema->ViewValue = NULL;
			}
			$this->nu_sistema->ViewCustomAttributes = "";

			// nu_modulo
			if (strval($this->nu_modulo->CurrentValue) <> "") {
				$sFilterWrk = "[nu_modulo]" . ew_SearchString("=", $this->nu_modulo->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_modulo], [no_modulo] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[modulo]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_modulo->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_modulo->ViewValue = $this->nu_modulo->CurrentValue;
				}
			} else {
				$this->nu_modulo->ViewValue = NULL;
			}
			$this->nu_modulo->ViewCustomAttributes = "";

			// co_alternativo
			$this->co_alternativo->ViewValue = $this->co_alternativo->CurrentValue;
			$this->co_alternativo->ViewCustomAttributes = "";

			// no_uc
			$this->no_uc->ViewValue = $this->no_uc->CurrentValue;
			$this->no_uc->ViewCustomAttributes = "";

			// ds_uc
			$this->ds_uc->ViewValue = $this->ds_uc->CurrentValue;
			$this->ds_uc->ViewCustomAttributes = "";

			// nu_stUc
			if (strval($this->nu_stUc->CurrentValue) <> "") {
				$sFilterWrk = "[nu_stUc]" . ew_SearchString("=", $this->nu_stUc->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_stUc], [no_stUc] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[stuc]";
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
					$this->nu_stUc->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_stUc->ViewValue = $this->nu_stUc->CurrentValue;
				}
			} else {
				$this->nu_stUc->ViewValue = NULL;
			}
			$this->nu_stUc->ViewCustomAttributes = "";

			// nu_uc
			$this->nu_uc->LinkCustomAttributes = "";
			$this->nu_uc->HrefValue = "";
			$this->nu_uc->TooltipValue = "";

			// nu_sistema
			$this->nu_sistema->LinkCustomAttributes = "";
			$this->nu_sistema->HrefValue = "";
			$this->nu_sistema->TooltipValue = "";

			// nu_modulo
			$this->nu_modulo->LinkCustomAttributes = "";
			$this->nu_modulo->HrefValue = "";
			$this->nu_modulo->TooltipValue = "";

			// co_alternativo
			$this->co_alternativo->LinkCustomAttributes = "";
			$this->co_alternativo->HrefValue = "";
			$this->co_alternativo->TooltipValue = "";

			// no_uc
			$this->no_uc->LinkCustomAttributes = "";
			$this->no_uc->HrefValue = "";
			$this->no_uc->TooltipValue = "";

			// ds_uc
			$this->ds_uc->LinkCustomAttributes = "";
			$this->ds_uc->HrefValue = "";
			$this->ds_uc->TooltipValue = "";

			// nu_stUc
			$this->nu_stUc->LinkCustomAttributes = "";
			$this->nu_stUc->HrefValue = "";
			$this->nu_stUc->TooltipValue = "";
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
if (!isset($Casos_de_Uso_report)) $Casos_de_Uso_report = new cCasos_de_Uso_report();

// Page init
$Casos_de_Uso_report->Page_Init();

// Page main
$Casos_de_Uso_report->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$Casos_de_Uso_report->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($Casos_de_Uso->Export == "") { ?>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($Casos_de_Uso->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php
$Casos_de_Uso_report->DefaultFilter = "";
$Casos_de_Uso_report->ReportFilter = $Casos_de_Uso_report->DefaultFilter;
if (!$Security->CanReport()) {
	if ($Casos_de_Uso_report->ReportFilter <> "") $Casos_de_Uso_report->ReportFilter .= " AND ";
	$Casos_de_Uso_report->ReportFilter .= "(0=1)";
}
if ($Casos_de_Uso_report->DbDetailFilter <> "") {
	if ($Casos_de_Uso_report->ReportFilter <> "") $Casos_de_Uso_report->ReportFilter .= " AND ";
	$Casos_de_Uso_report->ReportFilter .= "(" . $Casos_de_Uso_report->DbDetailFilter . ")";
}

// Set up filter and load Group level sql
$Casos_de_Uso->CurrentFilter = $Casos_de_Uso_report->ReportFilter;
$Casos_de_Uso_report->ReportSql = $Casos_de_Uso->GroupSQL();

// Load recordset
$Casos_de_Uso_report->Recordset = $conn->Execute($Casos_de_Uso_report->ReportSql);
$Casos_de_Uso_report->RecordExists = !$Casos_de_Uso_report->Recordset->EOF;
?>
<?php if ($Casos_de_Uso->Export == "") { ?>
<?php if ($Casos_de_Uso_report->RecordExists) { ?>
<div class="ewViewExportOptions"><?php $Casos_de_Uso_report->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php } ?>
<?php $Casos_de_Uso_report->ShowPageHeader(); ?>
<form method="post">
<table class="ewReportTable">
<?php

// Get First Row
if ($Casos_de_Uso_report->RecordExists) {
	$Casos_de_Uso->nu_sistema->setDbValue($Casos_de_Uso_report->Recordset->fields('nu_sistema'));
	$Casos_de_Uso_report->ReportGroups[0] = $Casos_de_Uso->nu_sistema->DbValue;
}
$Casos_de_Uso_report->RecCnt = 0;
$Casos_de_Uso_report->ReportCounts[0] = 0;
$Casos_de_Uso_report->ChkLvlBreak();
while (!$Casos_de_Uso_report->Recordset->EOF) {

	// Render for view
	$Casos_de_Uso->RowType = EW_ROWTYPE_VIEW;
	$Casos_de_Uso->ResetAttrs();
	$Casos_de_Uso_report->RenderRow();

	// Show group headers
	if ($Casos_de_Uso_report->LevelBreak[1]) { // Reset counter and aggregation
?>
	<tr><td class="ewGroupField"><?php echo $Casos_de_Uso->nu_sistema->FldCaption() ?></td>
	<td colspan=6 class="ewGroupName">
<span<?php echo $Casos_de_Uso->nu_sistema->ViewAttributes() ?>>
<?php echo $Casos_de_Uso->nu_sistema->ViewValue ?></span>
</td></tr>
<?php
	}

	// Get detail records
	$Casos_de_Uso_report->ReportFilter = $Casos_de_Uso_report->DefaultFilter;
	if ($Casos_de_Uso_report->ReportFilter <> "") $Casos_de_Uso_report->ReportFilter .= " AND ";
	if (is_null($Casos_de_Uso->nu_sistema->CurrentValue)) {
		$Casos_de_Uso_report->ReportFilter .= "([nu_sistema] IS NULL)";
	} else {
		$Casos_de_Uso_report->ReportFilter .= "([nu_sistema] = " . ew_AdjustSql($Casos_de_Uso->nu_sistema->CurrentValue) . ")";
	}
	if ($Casos_de_Uso_report->DbDetailFilter <> "") {
		if ($Casos_de_Uso_report->ReportFilter <> "")
			$Casos_de_Uso_report->ReportFilter .= " AND ";
		$Casos_de_Uso_report->ReportFilter .= "(" . $Casos_de_Uso_report->DbDetailFilter . ")";
	}
	if (!$Security->CanReport()) {
		if ($sFilter <> "") $sFilter .= " AND ";
		$sFilter .= "(0=1)";
	}

	// Set up detail SQL
	$Casos_de_Uso->CurrentFilter = $Casos_de_Uso_report->ReportFilter;
	$Casos_de_Uso_report->ReportSql = $Casos_de_Uso->DetailSQL();

	// Load detail records
	$Casos_de_Uso_report->DetailRecordset = $conn->Execute($Casos_de_Uso_report->ReportSql);
	$Casos_de_Uso_report->DtlRecordCount = $Casos_de_Uso_report->DetailRecordset->RecordCount();

	// Initialize aggregates
	if (!$Casos_de_Uso_report->DetailRecordset->EOF) {
		$Casos_de_Uso_report->RecCnt++;
	}
	if ($Casos_de_Uso_report->RecCnt == 1) {
		$Casos_de_Uso_report->ReportCounts[0] = 0;
	}
	for ($i = 1; $i <= 1; $i++) {
		if ($Casos_de_Uso_report->LevelBreak[$i]) { // Reset counter and aggregation
			$Casos_de_Uso_report->ReportCounts[$i] = 0;
		}
	}
	$Casos_de_Uso_report->ReportCounts[0] += $Casos_de_Uso_report->DtlRecordCount;
	$Casos_de_Uso_report->ReportCounts[1] += $Casos_de_Uso_report->DtlRecordCount;
	if ($Casos_de_Uso_report->RecordExists) {
?>
	<tr>
		<td><div class="ewGroupIndent"></div></td>
		<td class="ewGroupHeader"><?php echo $Casos_de_Uso->nu_uc->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Casos_de_Uso->nu_modulo->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Casos_de_Uso->co_alternativo->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Casos_de_Uso->no_uc->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Casos_de_Uso->ds_uc->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Casos_de_Uso->nu_stUc->FldCaption() ?></td>
	</tr>
<?php
	}
	while (!$Casos_de_Uso_report->DetailRecordset->EOF) {
		$Casos_de_Uso->nu_uc->setDbValue($Casos_de_Uso_report->DetailRecordset->fields('nu_uc'));
		$Casos_de_Uso->nu_modulo->setDbValue($Casos_de_Uso_report->DetailRecordset->fields('nu_modulo'));
		$Casos_de_Uso->co_alternativo->setDbValue($Casos_de_Uso_report->DetailRecordset->fields('co_alternativo'));
		$Casos_de_Uso->no_uc->setDbValue($Casos_de_Uso_report->DetailRecordset->fields('no_uc'));
		$Casos_de_Uso->ds_uc->setDbValue($Casos_de_Uso_report->DetailRecordset->fields('ds_uc'));
		$Casos_de_Uso->nu_stUc->setDbValue($Casos_de_Uso_report->DetailRecordset->fields('nu_stUc'));

		// Render for view
		$Casos_de_Uso->RowType = EW_ROWTYPE_VIEW;
		$Casos_de_Uso->ResetAttrs();
		$Casos_de_Uso_report->RenderRow();
?>
	<tr>
		<td><div class="ewGroupIndent"></div></td>
		<td<?php echo $Casos_de_Uso->nu_uc->CellAttributes() ?>>
<span<?php echo $Casos_de_Uso->nu_uc->ViewAttributes() ?>>
<?php echo $Casos_de_Uso->nu_uc->ViewValue ?></span>
</td>
		<td<?php echo $Casos_de_Uso->nu_modulo->CellAttributes() ?>>
<span<?php echo $Casos_de_Uso->nu_modulo->ViewAttributes() ?>>
<?php echo $Casos_de_Uso->nu_modulo->ViewValue ?></span>
</td>
		<td<?php echo $Casos_de_Uso->co_alternativo->CellAttributes() ?>>
<span<?php echo $Casos_de_Uso->co_alternativo->ViewAttributes() ?>>
<?php echo $Casos_de_Uso->co_alternativo->ViewValue ?></span>
</td>
		<td<?php echo $Casos_de_Uso->no_uc->CellAttributes() ?>>
<span<?php echo $Casos_de_Uso->no_uc->ViewAttributes() ?>>
<?php echo $Casos_de_Uso->no_uc->ViewValue ?></span>
</td>
		<td<?php echo $Casos_de_Uso->ds_uc->CellAttributes() ?>>
<span<?php echo $Casos_de_Uso->ds_uc->ViewAttributes() ?>>
<?php echo $Casos_de_Uso->ds_uc->ViewValue ?></span>
</td>
		<td<?php echo $Casos_de_Uso->nu_stUc->CellAttributes() ?>>
<span<?php echo $Casos_de_Uso->nu_stUc->ViewAttributes() ?>>
<?php echo $Casos_de_Uso->nu_stUc->ViewValue ?></span>
</td>
	</tr>
<?php
		$Casos_de_Uso_report->DetailRecordset->MoveNext();
	}
	$Casos_de_Uso_report->DetailRecordset->Close();

	// Save old group data
	$Casos_de_Uso_report->ReportGroups[0] = $Casos_de_Uso->nu_sistema->CurrentValue;

	// Get next record
	$Casos_de_Uso_report->Recordset->MoveNext();
	if ($Casos_de_Uso_report->Recordset->EOF) {
		$Casos_de_Uso_report->RecCnt = 0; // EOF, force all level breaks
	} else {
		$Casos_de_Uso->nu_sistema->setDbValue($Casos_de_Uso_report->Recordset->fields('nu_sistema'));
	}
	$Casos_de_Uso_report->ChkLvlBreak();

	// Show footers
	if ($Casos_de_Uso_report->LevelBreak[1]) {
		$Casos_de_Uso->nu_sistema->CurrentValue = $Casos_de_Uso_report->ReportGroups[0];

		// Render row for view
		$Casos_de_Uso->RowType = EW_ROWTYPE_VIEW;
		$Casos_de_Uso->ResetAttrs();
		$Casos_de_Uso_report->RenderRow();
		$Casos_de_Uso->nu_sistema->CurrentValue = $Casos_de_Uso->nu_sistema->DbValue;
?>
<?php
}
}

// Close recordset
$Casos_de_Uso_report->Recordset->Close();
?>
<?php if ($Casos_de_Uso_report->RecordExists) { ?>
	<tr><td colspan=7>&nbsp;<br></td></tr>
	<tr><td colspan=7 class="ewGrandSummary"><?php echo $Language->Phrase("RptGrandTotal") ?>&nbsp;(<?php echo ew_FormatNumber($Casos_de_Uso_report->ReportCounts[0], 0) ?>&nbsp;<?php echo $Language->Phrase("RptDtlRec") ?>)</td></tr>
<?php } ?>
<?php if ($Casos_de_Uso_report->RecordExists) { ?>
	<tr><td colspan=7>&nbsp;<br></td></tr>
<?php } else { ?>
	<tr><td><?php echo $Language->Phrase("NoRecord") ?></td></tr>
<?php } ?>
</table>
</form>
<?php
$Casos_de_Uso_report->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($Casos_de_Uso->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$Casos_de_Uso_report->Page_Terminate();
?>
