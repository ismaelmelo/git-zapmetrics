<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php

// Global variable for table object
$Regras_de_NegF3cio = NULL;

//
// Table class for Regras de Negócio
//
class cRegras_de_NegF3cio extends cTableBase {
	var $co_alternativo;
	var $nu_versao;
	var $no_regraNegocio;
	var $ds_regraNegocio;
	var $nu_area;
	var $ds_origemRegra;
	var $nu_projeto;
	var $no_tags;
	var $nu_stRegraNegocio;
	var $nu_usuario;
	var $dt_versao;
	var $hh_versao;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'Regras_de_NegF3cio';
		$this->TableName = 'Regras de Negócio';
		$this->TableType = 'REPORT';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->PrinterFriendlyForPdf = TRUE;
		$this->UserIDAllowSecurity = 0; // User ID Allow

		// co_alternativo
		$this->co_alternativo = new cField('Regras_de_NegF3cio', 'Regras de Negócio', 'x_co_alternativo', 'co_alternativo', '[co_alternativo]', '[co_alternativo]', 200, -1, FALSE, '[co_alternativo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_alternativo'] = &$this->co_alternativo;

		// nu_versao
		$this->nu_versao = new cField('Regras_de_NegF3cio', 'Regras de Negócio', 'x_nu_versao', 'nu_versao', '[nu_versao]', 'CAST([nu_versao] AS NVARCHAR)', 3, -1, FALSE, '[nu_versao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_versao->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_versao'] = &$this->nu_versao;

		// no_regraNegocio
		$this->no_regraNegocio = new cField('Regras_de_NegF3cio', 'Regras de Negócio', 'x_no_regraNegocio', 'no_regraNegocio', '[no_regraNegocio]', '[no_regraNegocio]', 200, -1, FALSE, '[no_regraNegocio]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_regraNegocio'] = &$this->no_regraNegocio;

		// ds_regraNegocio
		$this->ds_regraNegocio = new cField('Regras_de_NegF3cio', 'Regras de Negócio', 'x_ds_regraNegocio', 'ds_regraNegocio', '[ds_regraNegocio]', '[ds_regraNegocio]', 201, -1, FALSE, '[ds_regraNegocio]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_regraNegocio'] = &$this->ds_regraNegocio;

		// nu_area
		$this->nu_area = new cField('Regras_de_NegF3cio', 'Regras de Negócio', 'x_nu_area', 'nu_area', '[nu_area]', 'CAST([nu_area] AS NVARCHAR)', 3, -1, FALSE, '[nu_area]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_area->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_area'] = &$this->nu_area;

		// ds_origemRegra
		$this->ds_origemRegra = new cField('Regras_de_NegF3cio', 'Regras de Negócio', 'x_ds_origemRegra', 'ds_origemRegra', '[ds_origemRegra]', '[ds_origemRegra]', 201, -1, FALSE, '[ds_origemRegra]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_origemRegra'] = &$this->ds_origemRegra;

		// nu_projeto
		$this->nu_projeto = new cField('Regras_de_NegF3cio', 'Regras de Negócio', 'x_nu_projeto', 'nu_projeto', '[nu_projeto]', 'CAST([nu_projeto] AS NVARCHAR)', 3, -1, FALSE, '[nu_projeto]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_projeto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_projeto'] = &$this->nu_projeto;

		// no_tags
		$this->no_tags = new cField('Regras_de_NegF3cio', 'Regras de Negócio', 'x_no_tags', 'no_tags', '[no_tags]', '[no_tags]', 200, -1, FALSE, '[no_tags]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_tags'] = &$this->no_tags;

		// nu_stRegraNegocio
		$this->nu_stRegraNegocio = new cField('Regras_de_NegF3cio', 'Regras de Negócio', 'x_nu_stRegraNegocio', 'nu_stRegraNegocio', '[nu_stRegraNegocio]', 'CAST([nu_stRegraNegocio] AS NVARCHAR)', 3, -1, FALSE, '[nu_stRegraNegocio]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_stRegraNegocio->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_stRegraNegocio'] = &$this->nu_stRegraNegocio;

		// nu_usuario
		$this->nu_usuario = new cField('Regras_de_NegF3cio', 'Regras de Negócio', 'x_nu_usuario', 'nu_usuario', '[nu_usuario]', 'CAST([nu_usuario] AS NVARCHAR)', 3, -1, FALSE, '[nu_usuario]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['nu_usuario'] = &$this->nu_usuario;

		// dt_versao
		$this->dt_versao = new cField('Regras_de_NegF3cio', 'Regras de Negócio', 'x_dt_versao', 'dt_versao', '[dt_versao]', '(REPLACE(STR(DAY([dt_versao]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dt_versao]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dt_versao]),4,0))', 135, 7, FALSE, '[dt_versao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dt_versao->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dt_versao'] = &$this->dt_versao;

		// hh_versao
		$this->hh_versao = new cField('Regras_de_NegF3cio', 'Regras de Negócio', 'x_hh_versao', 'hh_versao', '[hh_versao]', '(REPLACE(STR(DAY([hh_versao]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([hh_versao]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([hh_versao]),4,0))', 145, 4, FALSE, '[hh_versao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->hh_versao->FldDefaultErrMsg = $Language->Phrase("IncorrectTime");
		$this->fields['hh_versao'] = &$this->hh_versao;
	}

	// Report group level SQL
	function SqlGroupSelect() { // Select
		return "SELECT DISTINCT [co_alternativo] FROM [dbo].[regranegocio]";
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
		return "[co_alternativo] ASC";
	}

	// Report detail level SQL
	function SqlDetailSelect() { // Select
		return "SELECT * FROM [dbo].[regranegocio]";
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
		return "[nu_versao] DESC";
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
		global $Security;

		// Add User ID filter
		if (!$this->AllowAnonymousUser() && $Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
			$sFilter = $this->AddUserIDFilter($sFilter);
		}
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = $this->UserIDAllowSecurity;
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
			return "regras_de_negf3cioreport.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "regras_de_negf3cioreport.php";
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
		if (!is_null($this->co_alternativo->CurrentValue)) {
			$sUrl .= "co_alternativo=" . urlencode($this->co_alternativo->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->nu_versao->CurrentValue)) {
			$sUrl .= "&nu_versao=" . urlencode($this->nu_versao->CurrentValue);
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
			for ($i = 0; $i < $cnt; $i++)
				$arKeys[$i] = explode($EW_COMPOSITE_KEY_SEPARATOR, $arKeys[$i]);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
			for ($i = 0; $i < $cnt; $i++)
				$arKeys[$i] = explode($EW_COMPOSITE_KEY_SEPARATOR, $arKeys[$i]);
		} elseif (isset($_GET)) {
			$arKey[] = @$_GET["co_alternativo"]; // co_alternativo
			$arKey[] = @$_GET["nu_versao"]; // nu_versao
			$arKeys[] = $arKey;

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_array($key) || count($key) <> 2)
				continue; // Just skip so other keys will still work
			if (!is_numeric($key[1])) // nu_versao
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
			$this->co_alternativo->CurrentValue = $key[0];
			$this->nu_versao->CurrentValue = $key[1];
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

	// Add User ID filter
	function AddUserIDFilter($sFilter) {
		global $Security;
		$sFilterWrk = "";
		$id = (CurrentPageID() == "list") ? $this->CurrentAction : CurrentPageID();
		if (!$this->UserIDAllow($id) && !$Security->IsAdmin()) {
			$sFilterWrk = $Security->UserIDList();
			if ($sFilterWrk <> "")
				$sFilterWrk = '[nu_usuario] IN (' . $sFilterWrk . ')';
		}

		// Call Row Rendered event
		$this->UserID_Filtering($sFilterWrk);
		ew_AddFilter($sFilter, $sFilterWrk);
		return $sFilter;
	}

	// User ID subquery
	function GetUserIDSubquery(&$fld, &$masterfld) {
		global $conn;
		$sWrk = "";
		$sSql = "SELECT " . $masterfld->FldExpression . " FROM [dbo].[regranegocio]";
		$sFilter = $this->AddUserIDFilter("");
		if ($sFilter <> "") $sSql .= " WHERE " . $sFilter;

		// Use subquery
		if (EW_USE_SUBQUERY_FOR_MASTER_USER_ID) {
			$sWrk = $sSql;
		} else {

			// List all values
			if ($rs = $conn->Execute($sSql)) {
				while (!$rs->EOF) {
					if ($sWrk <> "") $sWrk .= ",";
					$sWrk .= ew_QuotedValue($rs->fields[0], $masterfld->FldDataType);
					$rs->MoveNext();
				}
				$rs->Close();
			}
		}
		if ($sWrk <> "") {
			$sWrk = $fld->FldExpression . " IN (" . $sWrk . ")";
		}
		return $sWrk;
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

$Regras_de_NegF3cio_report = NULL; // Initialize page object first

class cRegras_de_NegF3cio_report extends cRegras_de_NegF3cio {

	// Page ID
	var $PageID = 'report';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'Regras de Negócio';

	// Page object name
	var $PageObjName = 'Regras_de_NegF3cio_report';

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

		// Table object (Regras_de_NegF3cio)
		if (!isset($GLOBALS["Regras_de_NegF3cio"])) {
			$GLOBALS["Regras_de_NegF3cio"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["Regras_de_NegF3cio"];
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
			define("EW_TABLE_NAME", 'Regras de Negócio', TRUE);

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
		if ($Security->IsLoggedIn() && strval($Security->CurrentUserID()) == "") {
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("regras_de_negf3ciolist.php");
		}

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
		$this->ReportTotals = &ew_Init2DArray(2, 12, 0);
		$this->ReportMaxs = &ew_Init2DArray(2, 12, 0);
		$this->ReportMins = &ew_Init2DArray(2, 12, 0);

		// Set up Breadcrumb
		$this->SetupBreadcrumb();
	}

	// Check level break
	function ChkLvlBreak() {
		$this->LevelBreak[1] = FALSE;
		if ($this->RecCnt == 0) { // Start Or End of Recordset
			$this->LevelBreak[1] = TRUE;
		} else {
			if (!ew_CompareValue($this->co_alternativo->CurrentValue, $this->ReportGroups[0])) {
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
		// co_alternativo
		// nu_versao
		// no_regraNegocio
		// ds_regraNegocio
		// nu_area
		// ds_origemRegra
		// nu_projeto
		// no_tags
		// nu_stRegraNegocio
		// nu_usuario
		// dt_versao
		// hh_versao

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// co_alternativo
			if (strval($this->co_alternativo->CurrentValue) <> "") {
				$sFilterWrk = "[co_rn]" . ew_SearchString("=", $this->co_alternativo->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_rn], [co_rn] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[corn]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [co_rn] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_alternativo->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->co_alternativo->ViewValue = $this->co_alternativo->CurrentValue;
				}
			} else {
				$this->co_alternativo->ViewValue = NULL;
			}
			$this->co_alternativo->ViewCustomAttributes = "";

			// nu_versao
			$this->nu_versao->ViewValue = $this->nu_versao->CurrentValue;
			$this->nu_versao->ViewCustomAttributes = "";

			// no_regraNegocio
			$this->no_regraNegocio->ViewValue = $this->no_regraNegocio->CurrentValue;
			$this->no_regraNegocio->ViewCustomAttributes = "";

			// ds_regraNegocio
			$this->ds_regraNegocio->ViewValue = $this->ds_regraNegocio->CurrentValue;
			$this->ds_regraNegocio->ViewCustomAttributes = "";

			// nu_area
			if (strval($this->nu_area->CurrentValue) <> "") {
				$sFilterWrk = "[nu_area]" . ew_SearchString("=", $this->nu_area->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_area], [no_area] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[area]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_area] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_area->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_area->ViewValue = $this->nu_area->CurrentValue;
				}
			} else {
				$this->nu_area->ViewValue = NULL;
			}
			$this->nu_area->ViewCustomAttributes = "";

			// ds_origemRegra
			$this->ds_origemRegra->ViewValue = $this->ds_origemRegra->CurrentValue;
			$this->ds_origemRegra->ViewCustomAttributes = "";

			// nu_projeto
			if (strval($this->nu_projeto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_projeto]" . ew_SearchString("=", $this->nu_projeto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_projeto], [no_projeto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[projeto]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
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

			// no_tags
			$this->no_tags->ViewValue = $this->no_tags->CurrentValue;
			$this->no_tags->ViewCustomAttributes = "";

			// nu_stRegraNegocio
			if (strval($this->nu_stRegraNegocio->CurrentValue) <> "") {
				$sFilterWrk = "[nu_stRegraNegocio]" . ew_SearchString("=", $this->nu_stRegraNegocio->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_stRegraNegocio], [no_stRegraNegocio] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[stregranegocio]";
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
					$this->nu_stRegraNegocio->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_stRegraNegocio->ViewValue = $this->nu_stRegraNegocio->CurrentValue;
				}
			} else {
				$this->nu_stRegraNegocio->ViewValue = NULL;
			}
			$this->nu_stRegraNegocio->ViewCustomAttributes = "";

			// nu_usuario
			$this->nu_usuario->ViewValue = $this->nu_usuario->CurrentValue;
			if (strval($this->nu_usuario->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuario->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuario->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuario->ViewValue = $this->nu_usuario->CurrentValue;
				}
			} else {
				$this->nu_usuario->ViewValue = NULL;
			}
			$this->nu_usuario->ViewCustomAttributes = "";

			// dt_versao
			$this->dt_versao->ViewValue = $this->dt_versao->CurrentValue;
			$this->dt_versao->ViewValue = ew_FormatDateTime($this->dt_versao->ViewValue, 7);
			$this->dt_versao->ViewCustomAttributes = "";

			// hh_versao
			$this->hh_versao->ViewValue = $this->hh_versao->CurrentValue;
			$this->hh_versao->ViewValue = ew_FormatDateTime($this->hh_versao->ViewValue, 4);
			$this->hh_versao->ViewCustomAttributes = "";

			// co_alternativo
			$this->co_alternativo->LinkCustomAttributes = "";
			$this->co_alternativo->HrefValue = "";
			$this->co_alternativo->TooltipValue = "";

			// nu_versao
			$this->nu_versao->LinkCustomAttributes = "";
			$this->nu_versao->HrefValue = "";
			$this->nu_versao->TooltipValue = "";

			// no_regraNegocio
			$this->no_regraNegocio->LinkCustomAttributes = "";
			$this->no_regraNegocio->HrefValue = "";
			$this->no_regraNegocio->TooltipValue = "";

			// ds_regraNegocio
			$this->ds_regraNegocio->LinkCustomAttributes = "";
			$this->ds_regraNegocio->HrefValue = "";
			$this->ds_regraNegocio->TooltipValue = "";

			// nu_area
			$this->nu_area->LinkCustomAttributes = "";
			$this->nu_area->HrefValue = "";
			$this->nu_area->TooltipValue = "";

			// ds_origemRegra
			$this->ds_origemRegra->LinkCustomAttributes = "";
			$this->ds_origemRegra->HrefValue = "";
			$this->ds_origemRegra->TooltipValue = "";

			// nu_projeto
			$this->nu_projeto->LinkCustomAttributes = "";
			$this->nu_projeto->HrefValue = "";
			$this->nu_projeto->TooltipValue = "";

			// no_tags
			$this->no_tags->LinkCustomAttributes = "";
			$this->no_tags->HrefValue = "";
			$this->no_tags->TooltipValue = "";

			// nu_stRegraNegocio
			$this->nu_stRegraNegocio->LinkCustomAttributes = "";
			$this->nu_stRegraNegocio->HrefValue = "";
			$this->nu_stRegraNegocio->TooltipValue = "";

			// nu_usuario
			$this->nu_usuario->LinkCustomAttributes = "";
			$this->nu_usuario->HrefValue = "";
			$this->nu_usuario->TooltipValue = "";

			// dt_versao
			$this->dt_versao->LinkCustomAttributes = "";
			$this->dt_versao->HrefValue = "";
			$this->dt_versao->TooltipValue = "";

			// hh_versao
			$this->hh_versao->LinkCustomAttributes = "";
			$this->hh_versao->HrefValue = "";
			$this->hh_versao->TooltipValue = "";
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
if (!isset($Regras_de_NegF3cio_report)) $Regras_de_NegF3cio_report = new cRegras_de_NegF3cio_report();

// Page init
$Regras_de_NegF3cio_report->Page_Init();

// Page main
$Regras_de_NegF3cio_report->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$Regras_de_NegF3cio_report->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($Regras_de_NegF3cio->Export == "") { ?>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($Regras_de_NegF3cio->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php
$Regras_de_NegF3cio_report->DefaultFilter = "";
$Regras_de_NegF3cio_report->ReportFilter = $Regras_de_NegF3cio_report->DefaultFilter;
if (!$Security->CanReport()) {
	if ($Regras_de_NegF3cio_report->ReportFilter <> "") $Regras_de_NegF3cio_report->ReportFilter .= " AND ";
	$Regras_de_NegF3cio_report->ReportFilter .= "(0=1)";
}
if ($Regras_de_NegF3cio_report->DbDetailFilter <> "") {
	if ($Regras_de_NegF3cio_report->ReportFilter <> "") $Regras_de_NegF3cio_report->ReportFilter .= " AND ";
	$Regras_de_NegF3cio_report->ReportFilter .= "(" . $Regras_de_NegF3cio_report->DbDetailFilter . ")";
}

// Set up filter and load Group level sql
$Regras_de_NegF3cio->CurrentFilter = $Regras_de_NegF3cio_report->ReportFilter;
$Regras_de_NegF3cio_report->ReportSql = $Regras_de_NegF3cio->GroupSQL();

// Load recordset
$Regras_de_NegF3cio_report->Recordset = $conn->Execute($Regras_de_NegF3cio_report->ReportSql);
$Regras_de_NegF3cio_report->RecordExists = !$Regras_de_NegF3cio_report->Recordset->EOF;
?>
<?php if ($Regras_de_NegF3cio->Export == "") { ?>
<?php if ($Regras_de_NegF3cio_report->RecordExists) { ?>
<div class="ewViewExportOptions"><?php $Regras_de_NegF3cio_report->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php } ?>
<?php $Regras_de_NegF3cio_report->ShowPageHeader(); ?>
<form method="post">
<table class="ewReportTable">
<?php

// Get First Row
if ($Regras_de_NegF3cio_report->RecordExists) {
	$Regras_de_NegF3cio->co_alternativo->setDbValue($Regras_de_NegF3cio_report->Recordset->fields('co_alternativo'));
	$Regras_de_NegF3cio_report->ReportGroups[0] = $Regras_de_NegF3cio->co_alternativo->DbValue;
}
$Regras_de_NegF3cio_report->RecCnt = 0;
$Regras_de_NegF3cio_report->ReportCounts[0] = 0;
$Regras_de_NegF3cio_report->ChkLvlBreak();
while (!$Regras_de_NegF3cio_report->Recordset->EOF) {

	// Render for view
	$Regras_de_NegF3cio->RowType = EW_ROWTYPE_VIEW;
	$Regras_de_NegF3cio->ResetAttrs();
	$Regras_de_NegF3cio_report->RenderRow();

	// Show group headers
	if ($Regras_de_NegF3cio_report->LevelBreak[1]) { // Reset counter and aggregation
?>
	<tr><td class="ewGroupField"><?php echo $Regras_de_NegF3cio->co_alternativo->FldCaption() ?></td>
	<td colspan=11 class="ewGroupName">
<span<?php echo $Regras_de_NegF3cio->co_alternativo->ViewAttributes() ?>>
<?php echo $Regras_de_NegF3cio->co_alternativo->ViewValue ?></span>
</td></tr>
<?php
	}

	// Get detail records
	$Regras_de_NegF3cio_report->ReportFilter = $Regras_de_NegF3cio_report->DefaultFilter;
	if ($Regras_de_NegF3cio_report->ReportFilter <> "") $Regras_de_NegF3cio_report->ReportFilter .= " AND ";
	if (is_null($Regras_de_NegF3cio->co_alternativo->CurrentValue)) {
		$Regras_de_NegF3cio_report->ReportFilter .= "([co_alternativo] IS NULL)";
	} else {
		$Regras_de_NegF3cio_report->ReportFilter .= "([co_alternativo] = '" . ew_AdjustSql($Regras_de_NegF3cio->co_alternativo->CurrentValue) . "')";
	}
	if ($Regras_de_NegF3cio_report->DbDetailFilter <> "") {
		if ($Regras_de_NegF3cio_report->ReportFilter <> "")
			$Regras_de_NegF3cio_report->ReportFilter .= " AND ";
		$Regras_de_NegF3cio_report->ReportFilter .= "(" . $Regras_de_NegF3cio_report->DbDetailFilter . ")";
	}
	if (!$Security->CanReport()) {
		if ($sFilter <> "") $sFilter .= " AND ";
		$sFilter .= "(0=1)";
	}

	// Set up detail SQL
	$Regras_de_NegF3cio->CurrentFilter = $Regras_de_NegF3cio_report->ReportFilter;
	$Regras_de_NegF3cio_report->ReportSql = $Regras_de_NegF3cio->DetailSQL();

	// Load detail records
	$Regras_de_NegF3cio_report->DetailRecordset = $conn->Execute($Regras_de_NegF3cio_report->ReportSql);
	$Regras_de_NegF3cio_report->DtlRecordCount = $Regras_de_NegF3cio_report->DetailRecordset->RecordCount();

	// Initialize aggregates
	if (!$Regras_de_NegF3cio_report->DetailRecordset->EOF) {
		$Regras_de_NegF3cio_report->RecCnt++;
	}
	if ($Regras_de_NegF3cio_report->RecCnt == 1) {
		$Regras_de_NegF3cio_report->ReportCounts[0] = 0;
	}
	for ($i = 1; $i <= 1; $i++) {
		if ($Regras_de_NegF3cio_report->LevelBreak[$i]) { // Reset counter and aggregation
			$Regras_de_NegF3cio_report->ReportCounts[$i] = 0;
		}
	}
	$Regras_de_NegF3cio_report->ReportCounts[0] += $Regras_de_NegF3cio_report->DtlRecordCount;
	$Regras_de_NegF3cio_report->ReportCounts[1] += $Regras_de_NegF3cio_report->DtlRecordCount;
	if ($Regras_de_NegF3cio_report->RecordExists) {
?>
	<tr>
		<td><div class="ewGroupIndent"></div></td>
		<td class="ewGroupHeader"><?php echo $Regras_de_NegF3cio->nu_versao->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Regras_de_NegF3cio->no_regraNegocio->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Regras_de_NegF3cio->ds_regraNegocio->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Regras_de_NegF3cio->nu_area->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Regras_de_NegF3cio->ds_origemRegra->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Regras_de_NegF3cio->nu_projeto->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Regras_de_NegF3cio->no_tags->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Regras_de_NegF3cio->nu_stRegraNegocio->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Regras_de_NegF3cio->nu_usuario->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Regras_de_NegF3cio->dt_versao->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Regras_de_NegF3cio->hh_versao->FldCaption() ?></td>
	</tr>
<?php
	}
	while (!$Regras_de_NegF3cio_report->DetailRecordset->EOF) {
		$Regras_de_NegF3cio->nu_versao->setDbValue($Regras_de_NegF3cio_report->DetailRecordset->fields('nu_versao'));
		$Regras_de_NegF3cio->no_regraNegocio->setDbValue($Regras_de_NegF3cio_report->DetailRecordset->fields('no_regraNegocio'));
		$Regras_de_NegF3cio->ds_regraNegocio->setDbValue($Regras_de_NegF3cio_report->DetailRecordset->fields('ds_regraNegocio'));
		$Regras_de_NegF3cio->nu_area->setDbValue($Regras_de_NegF3cio_report->DetailRecordset->fields('nu_area'));
		$Regras_de_NegF3cio->ds_origemRegra->setDbValue($Regras_de_NegF3cio_report->DetailRecordset->fields('ds_origemRegra'));
		$Regras_de_NegF3cio->nu_projeto->setDbValue($Regras_de_NegF3cio_report->DetailRecordset->fields('nu_projeto'));
		$Regras_de_NegF3cio->no_tags->setDbValue($Regras_de_NegF3cio_report->DetailRecordset->fields('no_tags'));
		$Regras_de_NegF3cio->nu_stRegraNegocio->setDbValue($Regras_de_NegF3cio_report->DetailRecordset->fields('nu_stRegraNegocio'));
		$Regras_de_NegF3cio->nu_usuario->setDbValue($Regras_de_NegF3cio_report->DetailRecordset->fields('nu_usuario'));
		$Regras_de_NegF3cio->dt_versao->setDbValue($Regras_de_NegF3cio_report->DetailRecordset->fields('dt_versao'));
		$Regras_de_NegF3cio->hh_versao->setDbValue($Regras_de_NegF3cio_report->DetailRecordset->fields('hh_versao'));

		// Render for view
		$Regras_de_NegF3cio->RowType = EW_ROWTYPE_VIEW;
		$Regras_de_NegF3cio->ResetAttrs();
		$Regras_de_NegF3cio_report->RenderRow();
?>
	<tr>
		<td><div class="ewGroupIndent"></div></td>
		<td<?php echo $Regras_de_NegF3cio->nu_versao->CellAttributes() ?>>
<span<?php echo $Regras_de_NegF3cio->nu_versao->ViewAttributes() ?>>
<?php echo $Regras_de_NegF3cio->nu_versao->ViewValue ?></span>
</td>
		<td<?php echo $Regras_de_NegF3cio->no_regraNegocio->CellAttributes() ?>>
<span<?php echo $Regras_de_NegF3cio->no_regraNegocio->ViewAttributes() ?>>
<?php echo $Regras_de_NegF3cio->no_regraNegocio->ViewValue ?></span>
</td>
		<td<?php echo $Regras_de_NegF3cio->ds_regraNegocio->CellAttributes() ?>>
<span<?php echo $Regras_de_NegF3cio->ds_regraNegocio->ViewAttributes() ?>>
<?php echo $Regras_de_NegF3cio->ds_regraNegocio->ViewValue ?></span>
</td>
		<td<?php echo $Regras_de_NegF3cio->nu_area->CellAttributes() ?>>
<span<?php echo $Regras_de_NegF3cio->nu_area->ViewAttributes() ?>>
<?php echo $Regras_de_NegF3cio->nu_area->ViewValue ?></span>
</td>
		<td<?php echo $Regras_de_NegF3cio->ds_origemRegra->CellAttributes() ?>>
<span<?php echo $Regras_de_NegF3cio->ds_origemRegra->ViewAttributes() ?>>
<?php echo $Regras_de_NegF3cio->ds_origemRegra->ViewValue ?></span>
</td>
		<td<?php echo $Regras_de_NegF3cio->nu_projeto->CellAttributes() ?>>
<span<?php echo $Regras_de_NegF3cio->nu_projeto->ViewAttributes() ?>>
<?php echo $Regras_de_NegF3cio->nu_projeto->ViewValue ?></span>
</td>
		<td<?php echo $Regras_de_NegF3cio->no_tags->CellAttributes() ?>>
<span<?php echo $Regras_de_NegF3cio->no_tags->ViewAttributes() ?>>
<?php echo $Regras_de_NegF3cio->no_tags->ViewValue ?></span>
</td>
		<td<?php echo $Regras_de_NegF3cio->nu_stRegraNegocio->CellAttributes() ?>>
<span<?php echo $Regras_de_NegF3cio->nu_stRegraNegocio->ViewAttributes() ?>>
<?php echo $Regras_de_NegF3cio->nu_stRegraNegocio->ViewValue ?></span>
</td>
		<td<?php echo $Regras_de_NegF3cio->nu_usuario->CellAttributes() ?>>
<span<?php echo $Regras_de_NegF3cio->nu_usuario->ViewAttributes() ?>>
<?php echo $Regras_de_NegF3cio->nu_usuario->ViewValue ?></span>
</td>
		<td<?php echo $Regras_de_NegF3cio->dt_versao->CellAttributes() ?>>
<span<?php echo $Regras_de_NegF3cio->dt_versao->ViewAttributes() ?>>
<?php echo $Regras_de_NegF3cio->dt_versao->ViewValue ?></span>
</td>
		<td<?php echo $Regras_de_NegF3cio->hh_versao->CellAttributes() ?>>
<span<?php echo $Regras_de_NegF3cio->hh_versao->ViewAttributes() ?>>
<?php echo $Regras_de_NegF3cio->hh_versao->ViewValue ?></span>
</td>
	</tr>
<?php
		$Regras_de_NegF3cio_report->DetailRecordset->MoveNext();
	}
	$Regras_de_NegF3cio_report->DetailRecordset->Close();

	// Save old group data
	$Regras_de_NegF3cio_report->ReportGroups[0] = $Regras_de_NegF3cio->co_alternativo->CurrentValue;

	// Get next record
	$Regras_de_NegF3cio_report->Recordset->MoveNext();
	if ($Regras_de_NegF3cio_report->Recordset->EOF) {
		$Regras_de_NegF3cio_report->RecCnt = 0; // EOF, force all level breaks
	} else {
		$Regras_de_NegF3cio->co_alternativo->setDbValue($Regras_de_NegF3cio_report->Recordset->fields('co_alternativo'));
	}
	$Regras_de_NegF3cio_report->ChkLvlBreak();

	// Show footers
	if ($Regras_de_NegF3cio_report->LevelBreak[1]) {
		$Regras_de_NegF3cio->co_alternativo->CurrentValue = $Regras_de_NegF3cio_report->ReportGroups[0];

		// Render row for view
		$Regras_de_NegF3cio->RowType = EW_ROWTYPE_VIEW;
		$Regras_de_NegF3cio->ResetAttrs();
		$Regras_de_NegF3cio_report->RenderRow();
		$Regras_de_NegF3cio->co_alternativo->CurrentValue = $Regras_de_NegF3cio->co_alternativo->DbValue;
?>
<?php
}
}

// Close recordset
$Regras_de_NegF3cio_report->Recordset->Close();
?>
<?php if ($Regras_de_NegF3cio_report->RecordExists) { ?>
	<tr><td colspan=12>&nbsp;<br></td></tr>
	<tr><td colspan=12 class="ewGrandSummary"><?php echo $Language->Phrase("RptGrandTotal") ?>&nbsp;(<?php echo ew_FormatNumber($Regras_de_NegF3cio_report->ReportCounts[0], 0) ?>&nbsp;<?php echo $Language->Phrase("RptDtlRec") ?>)</td></tr>
<?php } ?>
<?php if ($Regras_de_NegF3cio_report->RecordExists) { ?>
	<tr><td colspan=12>&nbsp;<br></td></tr>
<?php } else { ?>
	<tr><td><?php echo $Language->Phrase("NoRecord") ?></td></tr>
<?php } ?>
</table>
</form>
<?php
$Regras_de_NegF3cio_report->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($Regras_de_NegF3cio->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$Regras_de_NegF3cio_report->Page_Terminate();
?>
