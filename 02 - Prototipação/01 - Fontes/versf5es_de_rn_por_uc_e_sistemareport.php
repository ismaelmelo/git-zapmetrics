<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php

// Global variable for table object
$VersF5es_de_RN_por_UC_e_Sistema = NULL;

//
// Table class for Versões de RN por UC e Sistema
//
class cVersF5es_de_RN_por_UC_e_Sistema extends cTableBase {
	var $no_sistema;
	var $no_uc;
	var $co_alternativo;
	var $co_alternativo1;
	var $nu_fornecedor;
	var $ic_ativo;
	var $co_alternativo2;
	var $nu_versao;
	var $no_regraNegocio;
	var $ds_regraNegocio;
	var $nu_area;
	var $ds_origemRegra;
	var $nu_projeto;
	var $nu_stRegraNegocio;
	var $dt_versao;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'VersF5es_de_RN_por_UC_e_Sistema';
		$this->TableName = 'Versões de RN por UC e Sistema';
		$this->TableType = 'REPORT';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->PrinterFriendlyForPdf = TRUE;
		$this->UserIDAllowSecurity = 0; // User ID Allow

		// no_sistema
		$this->no_sistema = new cField('VersF5es_de_RN_por_UC_e_Sistema', 'Versões de RN por UC e Sistema', 'x_no_sistema', 'no_sistema', 'dbo.sistema.no_sistema', 'dbo.sistema.no_sistema', 200, -1, FALSE, 'dbo.sistema.no_sistema', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_sistema'] = &$this->no_sistema;

		// no_uc
		$this->no_uc = new cField('VersF5es_de_RN_por_UC_e_Sistema', 'Versões de RN por UC e Sistema', 'x_no_uc', 'no_uc', 'dbo.uc.no_uc', 'dbo.uc.no_uc', 200, -1, FALSE, 'dbo.uc.no_uc', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_uc'] = &$this->no_uc;

		// co_alternativo
		$this->co_alternativo = new cField('VersF5es_de_RN_por_UC_e_Sistema', 'Versões de RN por UC e Sistema', 'x_co_alternativo', 'co_alternativo', 'dbo.uc.co_alternativo', 'dbo.uc.co_alternativo', 200, -1, FALSE, 'dbo.uc.co_alternativo', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_alternativo'] = &$this->co_alternativo;

		// co_alternativo1
		$this->co_alternativo1 = new cField('VersF5es_de_RN_por_UC_e_Sistema', 'Versões de RN por UC e Sistema', 'x_co_alternativo1', 'co_alternativo1', 'dbo.sistema.co_alternativo', 'dbo.sistema.co_alternativo', 200, -1, FALSE, 'dbo.sistema.co_alternativo', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_alternativo1'] = &$this->co_alternativo1;

		// nu_fornecedor
		$this->nu_fornecedor = new cField('VersF5es_de_RN_por_UC_e_Sistema', 'Versões de RN por UC e Sistema', 'x_nu_fornecedor', 'nu_fornecedor', 'dbo.sistema.nu_fornecedor', 'CAST(dbo.sistema.nu_fornecedor AS NVARCHAR)', 3, -1, FALSE, 'dbo.sistema.nu_fornecedor', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_fornecedor->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_fornecedor'] = &$this->nu_fornecedor;

		// ic_ativo
		$this->ic_ativo = new cField('VersF5es_de_RN_por_UC_e_Sistema', 'Versões de RN por UC e Sistema', 'x_ic_ativo', 'ic_ativo', 'dbo.sistema.ic_ativo', 'dbo.sistema.ic_ativo', 129, -1, FALSE, 'dbo.sistema.ic_ativo', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_ativo'] = &$this->ic_ativo;

		// co_alternativo2
		$this->co_alternativo2 = new cField('VersF5es_de_RN_por_UC_e_Sistema', 'Versões de RN por UC e Sistema', 'x_co_alternativo2', 'co_alternativo2', 'dbo.regranegocio.co_alternativo', 'dbo.regranegocio.co_alternativo', 200, -1, FALSE, 'dbo.regranegocio.co_alternativo', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_alternativo2'] = &$this->co_alternativo2;

		// nu_versao
		$this->nu_versao = new cField('VersF5es_de_RN_por_UC_e_Sistema', 'Versões de RN por UC e Sistema', 'x_nu_versao', 'nu_versao', 'dbo.regranegocio.nu_versao', 'CAST(dbo.regranegocio.nu_versao AS NVARCHAR)', 3, -1, FALSE, 'dbo.regranegocio.nu_versao', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_versao->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_versao'] = &$this->nu_versao;

		// no_regraNegocio
		$this->no_regraNegocio = new cField('VersF5es_de_RN_por_UC_e_Sistema', 'Versões de RN por UC e Sistema', 'x_no_regraNegocio', 'no_regraNegocio', 'dbo.regranegocio.no_regraNegocio', 'dbo.regranegocio.no_regraNegocio', 200, -1, FALSE, 'dbo.regranegocio.no_regraNegocio', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_regraNegocio'] = &$this->no_regraNegocio;

		// ds_regraNegocio
		$this->ds_regraNegocio = new cField('VersF5es_de_RN_por_UC_e_Sistema', 'Versões de RN por UC e Sistema', 'x_ds_regraNegocio', 'ds_regraNegocio', 'dbo.regranegocio.ds_regraNegocio', 'dbo.regranegocio.ds_regraNegocio', 201, -1, FALSE, 'dbo.regranegocio.ds_regraNegocio', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_regraNegocio'] = &$this->ds_regraNegocio;

		// nu_area
		$this->nu_area = new cField('VersF5es_de_RN_por_UC_e_Sistema', 'Versões de RN por UC e Sistema', 'x_nu_area', 'nu_area', 'dbo.regranegocio.nu_area', 'CAST(dbo.regranegocio.nu_area AS NVARCHAR)', 3, -1, FALSE, 'dbo.regranegocio.nu_area', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_area->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_area'] = &$this->nu_area;

		// ds_origemRegra
		$this->ds_origemRegra = new cField('VersF5es_de_RN_por_UC_e_Sistema', 'Versões de RN por UC e Sistema', 'x_ds_origemRegra', 'ds_origemRegra', 'dbo.regranegocio.ds_origemRegra', 'dbo.regranegocio.ds_origemRegra', 201, -1, FALSE, 'dbo.regranegocio.ds_origemRegra', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_origemRegra'] = &$this->ds_origemRegra;

		// nu_projeto
		$this->nu_projeto = new cField('VersF5es_de_RN_por_UC_e_Sistema', 'Versões de RN por UC e Sistema', 'x_nu_projeto', 'nu_projeto', 'dbo.regranegocio.nu_projeto', 'CAST(dbo.regranegocio.nu_projeto AS NVARCHAR)', 3, -1, FALSE, 'dbo.regranegocio.nu_projeto', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_projeto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_projeto'] = &$this->nu_projeto;

		// nu_stRegraNegocio
		$this->nu_stRegraNegocio = new cField('VersF5es_de_RN_por_UC_e_Sistema', 'Versões de RN por UC e Sistema', 'x_nu_stRegraNegocio', 'nu_stRegraNegocio', 'dbo.regranegocio.nu_stRegraNegocio', 'CAST(dbo.regranegocio.nu_stRegraNegocio AS NVARCHAR)', 3, -1, FALSE, 'dbo.regranegocio.nu_stRegraNegocio', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_stRegraNegocio->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_stRegraNegocio'] = &$this->nu_stRegraNegocio;

		// dt_versao
		$this->dt_versao = new cField('VersF5es_de_RN_por_UC_e_Sistema', 'Versões de RN por UC e Sistema', 'x_dt_versao', 'dt_versao', 'dbo.regranegocio.dt_versao', '(REPLACE(STR(DAY(dbo.regranegocio.dt_versao),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH(dbo.regranegocio.dt_versao),2,0),\' \',\'0\') + \'/\' + STR(YEAR(dbo.regranegocio.dt_versao),4,0))', 135, 7, FALSE, 'dbo.regranegocio.dt_versao', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dt_versao->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dt_versao'] = &$this->dt_versao;
	}

	// Report group level SQL
	function SqlGroupSelect() { // Select
		return "SELECT DISTINCT dbo.sistema.no_sistema,dbo.uc.co_alternativo FROM dbo.sistema INNER JOIN dbo.uc ON dbo.sistema.nu_sistema = dbo.uc.nu_sistema INNER JOIN dbo.uc_regranegocio ON dbo.uc.nu_uc = dbo.uc_regranegocio.nu_uc INNER JOIN dbo.regranegocio ON dbo.uc_regranegocio.co_rn = dbo.regranegocio.co_alternativo";
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
		return "dbo.sistema.no_sistema ASC,dbo.uc.co_alternativo ASC";
	}

	// Report detail level SQL
	function SqlDetailSelect() { // Select
		return "SELECT dbo.sistema.no_sistema, dbo.uc.no_uc, dbo.uc.co_alternativo, dbo.sistema.co_alternativo AS co_alternativo1, dbo.sistema.nu_fornecedor, dbo.sistema.ic_ativo, dbo.regranegocio.co_alternativo AS co_alternativo2, dbo.regranegocio.nu_versao, dbo.regranegocio.no_regraNegocio, dbo.regranegocio.ds_regraNegocio, dbo.regranegocio.nu_area, dbo.regranegocio.ds_origemRegra, dbo.regranegocio.nu_projeto, dbo.regranegocio.nu_stRegraNegocio, dbo.regranegocio.dt_versao FROM dbo.sistema INNER JOIN dbo.uc ON dbo.sistema.nu_sistema = dbo.uc.nu_sistema INNER JOIN dbo.uc_regranegocio ON dbo.uc.nu_uc = dbo.uc_regranegocio.nu_uc INNER JOIN dbo.regranegocio ON dbo.uc_regranegocio.co_rn = dbo.regranegocio.co_alternativo";
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
		return "dbo.uc.no_uc ASC,dbo.sistema.co_alternativo ASC,dbo.regranegocio.co_alternativo ASC,dbo.regranegocio.nu_versao ASC, dbo.regranegocio.dt_versao DESC";
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
			return "versf5es_de_rn_por_uc_e_sistemareport.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "versf5es_de_rn_por_uc_e_sistemareport.php";
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

$VersF5es_de_RN_por_UC_e_Sistema_report = NULL; // Initialize page object first

class cVersF5es_de_RN_por_UC_e_Sistema_report extends cVersF5es_de_RN_por_UC_e_Sistema {

	// Page ID
	var $PageID = 'report';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'Versões de RN por UC e Sistema';

	// Page object name
	var $PageObjName = 'VersF5es_de_RN_por_UC_e_Sistema_report';

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

		// Table object (VersF5es_de_RN_por_UC_e_Sistema)
		if (!isset($GLOBALS["VersF5es_de_RN_por_UC_e_Sistema"])) {
			$GLOBALS["VersF5es_de_RN_por_UC_e_Sistema"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["VersF5es_de_RN_por_UC_e_Sistema"];
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
			define("EW_TABLE_NAME", 'Versões de RN por UC e Sistema', TRUE);

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
		$this->ReportGroups = &ew_InitArray(3, NULL);
		$this->ReportCounts = &ew_InitArray(3, 0);
		$this->LevelBreak = &ew_InitArray(3, FALSE);
		$this->ReportTotals = &ew_Init2DArray(3, 14, 0);
		$this->ReportMaxs = &ew_Init2DArray(3, 14, 0);
		$this->ReportMins = &ew_Init2DArray(3, 14, 0);

		// Set up Breadcrumb
		$this->SetupBreadcrumb();
	}

	// Check level break
	function ChkLvlBreak() {
		$this->LevelBreak[1] = FALSE;
		$this->LevelBreak[2] = FALSE;
		if ($this->RecCnt == 0) { // Start Or End of Recordset
			$this->LevelBreak[1] = TRUE;
			$this->LevelBreak[2] = TRUE;
		} else {
			if (!ew_CompareValue($this->no_sistema->CurrentValue, $this->ReportGroups[0])) {
				$this->LevelBreak[1] = TRUE;
				$this->LevelBreak[2] = TRUE;
			}
			if (!ew_CompareValue($this->co_alternativo->CurrentValue, $this->ReportGroups[1])) {
				$this->LevelBreak[2] = TRUE;
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
		// no_sistema
		// no_uc
		// co_alternativo
		// co_alternativo1
		// nu_fornecedor
		// ic_ativo
		// co_alternativo2
		// nu_versao
		// no_regraNegocio
		// ds_regraNegocio
		// nu_area
		// ds_origemRegra
		// nu_projeto
		// nu_stRegraNegocio
		// dt_versao

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// no_sistema
			$this->no_sistema->ViewValue = $this->no_sistema->CurrentValue;
			$this->no_sistema->ViewCustomAttributes = "";

			// no_uc
			$this->no_uc->ViewValue = $this->no_uc->CurrentValue;
			$this->no_uc->ViewCustomAttributes = "";

			// co_alternativo
			$this->co_alternativo->ViewValue = $this->co_alternativo->CurrentValue;
			$this->co_alternativo->ViewCustomAttributes = "";

			// co_alternativo1
			$this->co_alternativo1->ViewValue = $this->co_alternativo1->CurrentValue;
			$this->co_alternativo1->ViewCustomAttributes = "";

			// nu_fornecedor
			if (strval($this->nu_fornecedor->CurrentValue) <> "") {
				$sFilterWrk = "[nu_fornecedor]" . ew_SearchString("=", $this->nu_fornecedor->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_fornecedor], [no_fornecedor] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[fornecedor]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_fornecedor] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_fornecedor->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_fornecedor->ViewValue = $this->nu_fornecedor->CurrentValue;
				}
			} else {
				$this->nu_fornecedor->ViewValue = NULL;
			}
			$this->nu_fornecedor->ViewCustomAttributes = "";

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

			// co_alternativo2
			$this->co_alternativo2->ViewValue = $this->co_alternativo2->CurrentValue;
			$this->co_alternativo2->ViewCustomAttributes = "";

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

			// dt_versao
			$this->dt_versao->ViewValue = $this->dt_versao->CurrentValue;
			$this->dt_versao->ViewValue = ew_FormatDateTime($this->dt_versao->ViewValue, 7);
			$this->dt_versao->ViewCustomAttributes = "";

			// no_sistema
			$this->no_sistema->LinkCustomAttributes = "";
			$this->no_sistema->HrefValue = "";
			$this->no_sistema->TooltipValue = "";

			// no_uc
			$this->no_uc->LinkCustomAttributes = "";
			$this->no_uc->HrefValue = "";
			$this->no_uc->TooltipValue = "";

			// co_alternativo
			$this->co_alternativo->LinkCustomAttributes = "";
			$this->co_alternativo->HrefValue = "";
			$this->co_alternativo->TooltipValue = "";

			// co_alternativo1
			$this->co_alternativo1->LinkCustomAttributes = "";
			$this->co_alternativo1->HrefValue = "";
			$this->co_alternativo1->TooltipValue = "";

			// nu_fornecedor
			$this->nu_fornecedor->LinkCustomAttributes = "";
			$this->nu_fornecedor->HrefValue = "";
			$this->nu_fornecedor->TooltipValue = "";

			// ic_ativo
			$this->ic_ativo->LinkCustomAttributes = "";
			$this->ic_ativo->HrefValue = "";
			$this->ic_ativo->TooltipValue = "";

			// co_alternativo2
			$this->co_alternativo2->LinkCustomAttributes = "";
			$this->co_alternativo2->HrefValue = "";
			$this->co_alternativo2->TooltipValue = "";

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

			// nu_stRegraNegocio
			$this->nu_stRegraNegocio->LinkCustomAttributes = "";
			$this->nu_stRegraNegocio->HrefValue = "";
			$this->nu_stRegraNegocio->TooltipValue = "";

			// dt_versao
			$this->dt_versao->LinkCustomAttributes = "";
			$this->dt_versao->HrefValue = "";
			$this->dt_versao->TooltipValue = "";
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
if (!isset($VersF5es_de_RN_por_UC_e_Sistema_report)) $VersF5es_de_RN_por_UC_e_Sistema_report = new cVersF5es_de_RN_por_UC_e_Sistema_report();

// Page init
$VersF5es_de_RN_por_UC_e_Sistema_report->Page_Init();

// Page main
$VersF5es_de_RN_por_UC_e_Sistema_report->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$VersF5es_de_RN_por_UC_e_Sistema_report->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($VersF5es_de_RN_por_UC_e_Sistema->Export == "") { ?>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($VersF5es_de_RN_por_UC_e_Sistema->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php
$VersF5es_de_RN_por_UC_e_Sistema_report->DefaultFilter = "";
$VersF5es_de_RN_por_UC_e_Sistema_report->ReportFilter = $VersF5es_de_RN_por_UC_e_Sistema_report->DefaultFilter;
if (!$Security->CanReport()) {
	if ($VersF5es_de_RN_por_UC_e_Sistema_report->ReportFilter <> "") $VersF5es_de_RN_por_UC_e_Sistema_report->ReportFilter .= " AND ";
	$VersF5es_de_RN_por_UC_e_Sistema_report->ReportFilter .= "(0=1)";
}
if ($VersF5es_de_RN_por_UC_e_Sistema_report->DbDetailFilter <> "") {
	if ($VersF5es_de_RN_por_UC_e_Sistema_report->ReportFilter <> "") $VersF5es_de_RN_por_UC_e_Sistema_report->ReportFilter .= " AND ";
	$VersF5es_de_RN_por_UC_e_Sistema_report->ReportFilter .= "(" . $VersF5es_de_RN_por_UC_e_Sistema_report->DbDetailFilter . ")";
}

// Set up filter and load Group level sql
$VersF5es_de_RN_por_UC_e_Sistema->CurrentFilter = $VersF5es_de_RN_por_UC_e_Sistema_report->ReportFilter;
$VersF5es_de_RN_por_UC_e_Sistema_report->ReportSql = $VersF5es_de_RN_por_UC_e_Sistema->GroupSQL();

// Load recordset
$VersF5es_de_RN_por_UC_e_Sistema_report->Recordset = $conn->Execute($VersF5es_de_RN_por_UC_e_Sistema_report->ReportSql);
$VersF5es_de_RN_por_UC_e_Sistema_report->RecordExists = !$VersF5es_de_RN_por_UC_e_Sistema_report->Recordset->EOF;
?>
<?php if ($VersF5es_de_RN_por_UC_e_Sistema->Export == "") { ?>
<?php if ($VersF5es_de_RN_por_UC_e_Sistema_report->RecordExists) { ?>
<div class="ewViewExportOptions"><?php $VersF5es_de_RN_por_UC_e_Sistema_report->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php } ?>
<?php $VersF5es_de_RN_por_UC_e_Sistema_report->ShowPageHeader(); ?>
<form method="post">
<table class="ewReportTable">
<?php

// Get First Row
if ($VersF5es_de_RN_por_UC_e_Sistema_report->RecordExists) {
	$VersF5es_de_RN_por_UC_e_Sistema->no_sistema->setDbValue($VersF5es_de_RN_por_UC_e_Sistema_report->Recordset->fields('no_sistema'));
	$VersF5es_de_RN_por_UC_e_Sistema_report->ReportGroups[0] = $VersF5es_de_RN_por_UC_e_Sistema->no_sistema->DbValue;
	$VersF5es_de_RN_por_UC_e_Sistema->co_alternativo->setDbValue($VersF5es_de_RN_por_UC_e_Sistema_report->Recordset->fields('co_alternativo'));
	$VersF5es_de_RN_por_UC_e_Sistema_report->ReportGroups[1] = $VersF5es_de_RN_por_UC_e_Sistema->co_alternativo->DbValue;
}
$VersF5es_de_RN_por_UC_e_Sistema_report->RecCnt = 0;
$VersF5es_de_RN_por_UC_e_Sistema_report->ReportCounts[0] = 0;
$VersF5es_de_RN_por_UC_e_Sistema_report->ChkLvlBreak();
while (!$VersF5es_de_RN_por_UC_e_Sistema_report->Recordset->EOF) {

	// Render for view
	$VersF5es_de_RN_por_UC_e_Sistema->RowType = EW_ROWTYPE_VIEW;
	$VersF5es_de_RN_por_UC_e_Sistema->ResetAttrs();
	$VersF5es_de_RN_por_UC_e_Sistema_report->RenderRow();

	// Show group headers
	if ($VersF5es_de_RN_por_UC_e_Sistema_report->LevelBreak[1]) { // Reset counter and aggregation
?>
	<tr><td colspan=2 class="ewGroupField"><?php echo $VersF5es_de_RN_por_UC_e_Sistema->no_sistema->FldCaption() ?></td>
	<td colspan=13 class="ewGroupName">
<span<?php echo $VersF5es_de_RN_por_UC_e_Sistema->no_sistema->ViewAttributes() ?>>
<?php echo $VersF5es_de_RN_por_UC_e_Sistema->no_sistema->ViewValue ?></span>
</td></tr>
<?php
	}
	if ($VersF5es_de_RN_por_UC_e_Sistema_report->LevelBreak[2]) { // Reset counter and aggregation
?>
	<tr><td><div class="ewGroupIndent"></div></td><td class="ewGroupField"><?php echo $VersF5es_de_RN_por_UC_e_Sistema->co_alternativo->FldCaption() ?></td>
	<td colspan=13 class="ewGroupName">
<span<?php echo $VersF5es_de_RN_por_UC_e_Sistema->co_alternativo->ViewAttributes() ?>>
<?php echo $VersF5es_de_RN_por_UC_e_Sistema->co_alternativo->ViewValue ?></span>
</td></tr>
<?php
	}

	// Get detail records
	$VersF5es_de_RN_por_UC_e_Sistema_report->ReportFilter = $VersF5es_de_RN_por_UC_e_Sistema_report->DefaultFilter;
	if ($VersF5es_de_RN_por_UC_e_Sistema_report->ReportFilter <> "") $VersF5es_de_RN_por_UC_e_Sistema_report->ReportFilter .= " AND ";
	if (is_null($VersF5es_de_RN_por_UC_e_Sistema->no_sistema->CurrentValue)) {
		$VersF5es_de_RN_por_UC_e_Sistema_report->ReportFilter .= "(dbo.sistema.no_sistema IS NULL)";
	} else {
		$VersF5es_de_RN_por_UC_e_Sistema_report->ReportFilter .= "(dbo.sistema.no_sistema = '" . ew_AdjustSql($VersF5es_de_RN_por_UC_e_Sistema->no_sistema->CurrentValue) . "')";
	}
	if ($VersF5es_de_RN_por_UC_e_Sistema_report->ReportFilter <> "") $VersF5es_de_RN_por_UC_e_Sistema_report->ReportFilter .= " AND ";
	if (is_null($VersF5es_de_RN_por_UC_e_Sistema->co_alternativo->CurrentValue)) {
		$VersF5es_de_RN_por_UC_e_Sistema_report->ReportFilter .= "(dbo.uc.co_alternativo IS NULL)";
	} else {
		$VersF5es_de_RN_por_UC_e_Sistema_report->ReportFilter .= "(dbo.uc.co_alternativo = '" . ew_AdjustSql($VersF5es_de_RN_por_UC_e_Sistema->co_alternativo->CurrentValue) . "')";
	}
	if ($VersF5es_de_RN_por_UC_e_Sistema_report->DbDetailFilter <> "") {
		if ($VersF5es_de_RN_por_UC_e_Sistema_report->ReportFilter <> "")
			$VersF5es_de_RN_por_UC_e_Sistema_report->ReportFilter .= " AND ";
		$VersF5es_de_RN_por_UC_e_Sistema_report->ReportFilter .= "(" . $VersF5es_de_RN_por_UC_e_Sistema_report->DbDetailFilter . ")";
	}
	if (!$Security->CanReport()) {
		if ($sFilter <> "") $sFilter .= " AND ";
		$sFilter .= "(0=1)";
	}

	// Set up detail SQL
	$VersF5es_de_RN_por_UC_e_Sistema->CurrentFilter = $VersF5es_de_RN_por_UC_e_Sistema_report->ReportFilter;
	$VersF5es_de_RN_por_UC_e_Sistema_report->ReportSql = $VersF5es_de_RN_por_UC_e_Sistema->DetailSQL();

	// Load detail records
	$VersF5es_de_RN_por_UC_e_Sistema_report->DetailRecordset = $conn->Execute($VersF5es_de_RN_por_UC_e_Sistema_report->ReportSql);
	$VersF5es_de_RN_por_UC_e_Sistema_report->DtlRecordCount = $VersF5es_de_RN_por_UC_e_Sistema_report->DetailRecordset->RecordCount();

	// Initialize aggregates
	if (!$VersF5es_de_RN_por_UC_e_Sistema_report->DetailRecordset->EOF) {
		$VersF5es_de_RN_por_UC_e_Sistema_report->RecCnt++;
	}
	if ($VersF5es_de_RN_por_UC_e_Sistema_report->RecCnt == 1) {
		$VersF5es_de_RN_por_UC_e_Sistema_report->ReportCounts[0] = 0;
	}
	for ($i = 1; $i <= 2; $i++) {
		if ($VersF5es_de_RN_por_UC_e_Sistema_report->LevelBreak[$i]) { // Reset counter and aggregation
			$VersF5es_de_RN_por_UC_e_Sistema_report->ReportCounts[$i] = 0;
		}
	}
	$VersF5es_de_RN_por_UC_e_Sistema_report->ReportCounts[0] += $VersF5es_de_RN_por_UC_e_Sistema_report->DtlRecordCount;
	$VersF5es_de_RN_por_UC_e_Sistema_report->ReportCounts[1] += $VersF5es_de_RN_por_UC_e_Sistema_report->DtlRecordCount;
	$VersF5es_de_RN_por_UC_e_Sistema_report->ReportCounts[2] += $VersF5es_de_RN_por_UC_e_Sistema_report->DtlRecordCount;
	if ($VersF5es_de_RN_por_UC_e_Sistema_report->RecordExists) {
?>
	<tr>
		<td><div class="ewGroupIndent"></div></td>
		<td><div class="ewGroupIndent"></div></td>
		<td class="ewGroupHeader"><?php echo $VersF5es_de_RN_por_UC_e_Sistema->no_uc->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $VersF5es_de_RN_por_UC_e_Sistema->co_alternativo1->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $VersF5es_de_RN_por_UC_e_Sistema->nu_fornecedor->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $VersF5es_de_RN_por_UC_e_Sistema->ic_ativo->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $VersF5es_de_RN_por_UC_e_Sistema->co_alternativo2->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $VersF5es_de_RN_por_UC_e_Sistema->nu_versao->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $VersF5es_de_RN_por_UC_e_Sistema->no_regraNegocio->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $VersF5es_de_RN_por_UC_e_Sistema->ds_regraNegocio->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $VersF5es_de_RN_por_UC_e_Sistema->nu_area->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $VersF5es_de_RN_por_UC_e_Sistema->ds_origemRegra->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $VersF5es_de_RN_por_UC_e_Sistema->nu_projeto->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $VersF5es_de_RN_por_UC_e_Sistema->nu_stRegraNegocio->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $VersF5es_de_RN_por_UC_e_Sistema->dt_versao->FldCaption() ?></td>
	</tr>
<?php
	}
	while (!$VersF5es_de_RN_por_UC_e_Sistema_report->DetailRecordset->EOF) {
		$VersF5es_de_RN_por_UC_e_Sistema->no_uc->setDbValue($VersF5es_de_RN_por_UC_e_Sistema_report->DetailRecordset->fields('no_uc'));
		$VersF5es_de_RN_por_UC_e_Sistema->co_alternativo1->setDbValue($VersF5es_de_RN_por_UC_e_Sistema_report->DetailRecordset->fields('co_alternativo1'));
		$VersF5es_de_RN_por_UC_e_Sistema->nu_fornecedor->setDbValue($VersF5es_de_RN_por_UC_e_Sistema_report->DetailRecordset->fields('nu_fornecedor'));
		$VersF5es_de_RN_por_UC_e_Sistema->ic_ativo->setDbValue($VersF5es_de_RN_por_UC_e_Sistema_report->DetailRecordset->fields('ic_ativo'));
		$VersF5es_de_RN_por_UC_e_Sistema->co_alternativo2->setDbValue($VersF5es_de_RN_por_UC_e_Sistema_report->DetailRecordset->fields('co_alternativo2'));
		$VersF5es_de_RN_por_UC_e_Sistema->nu_versao->setDbValue($VersF5es_de_RN_por_UC_e_Sistema_report->DetailRecordset->fields('nu_versao'));
		$VersF5es_de_RN_por_UC_e_Sistema->no_regraNegocio->setDbValue($VersF5es_de_RN_por_UC_e_Sistema_report->DetailRecordset->fields('no_regraNegocio'));
		$VersF5es_de_RN_por_UC_e_Sistema->ds_regraNegocio->setDbValue($VersF5es_de_RN_por_UC_e_Sistema_report->DetailRecordset->fields('ds_regraNegocio'));
		$VersF5es_de_RN_por_UC_e_Sistema->nu_area->setDbValue($VersF5es_de_RN_por_UC_e_Sistema_report->DetailRecordset->fields('nu_area'));
		$VersF5es_de_RN_por_UC_e_Sistema->ds_origemRegra->setDbValue($VersF5es_de_RN_por_UC_e_Sistema_report->DetailRecordset->fields('ds_origemRegra'));
		$VersF5es_de_RN_por_UC_e_Sistema->nu_projeto->setDbValue($VersF5es_de_RN_por_UC_e_Sistema_report->DetailRecordset->fields('nu_projeto'));
		$VersF5es_de_RN_por_UC_e_Sistema->nu_stRegraNegocio->setDbValue($VersF5es_de_RN_por_UC_e_Sistema_report->DetailRecordset->fields('nu_stRegraNegocio'));
		$VersF5es_de_RN_por_UC_e_Sistema->dt_versao->setDbValue($VersF5es_de_RN_por_UC_e_Sistema_report->DetailRecordset->fields('dt_versao'));

		// Render for view
		$VersF5es_de_RN_por_UC_e_Sistema->RowType = EW_ROWTYPE_VIEW;
		$VersF5es_de_RN_por_UC_e_Sistema->ResetAttrs();
		$VersF5es_de_RN_por_UC_e_Sistema_report->RenderRow();
?>
	<tr>
		<td><div class="ewGroupIndent"></div></td>
		<td><div class="ewGroupIndent"></div></td>
		<td<?php echo $VersF5es_de_RN_por_UC_e_Sistema->no_uc->CellAttributes() ?>>
<span<?php echo $VersF5es_de_RN_por_UC_e_Sistema->no_uc->ViewAttributes() ?>>
<?php echo $VersF5es_de_RN_por_UC_e_Sistema->no_uc->ViewValue ?></span>
</td>
		<td<?php echo $VersF5es_de_RN_por_UC_e_Sistema->co_alternativo1->CellAttributes() ?>>
<span<?php echo $VersF5es_de_RN_por_UC_e_Sistema->co_alternativo1->ViewAttributes() ?>>
<?php echo $VersF5es_de_RN_por_UC_e_Sistema->co_alternativo1->ViewValue ?></span>
</td>
		<td<?php echo $VersF5es_de_RN_por_UC_e_Sistema->nu_fornecedor->CellAttributes() ?>>
<span<?php echo $VersF5es_de_RN_por_UC_e_Sistema->nu_fornecedor->ViewAttributes() ?>>
<?php echo $VersF5es_de_RN_por_UC_e_Sistema->nu_fornecedor->ViewValue ?></span>
</td>
		<td<?php echo $VersF5es_de_RN_por_UC_e_Sistema->ic_ativo->CellAttributes() ?>>
<span<?php echo $VersF5es_de_RN_por_UC_e_Sistema->ic_ativo->ViewAttributes() ?>>
<?php echo $VersF5es_de_RN_por_UC_e_Sistema->ic_ativo->ViewValue ?></span>
</td>
		<td<?php echo $VersF5es_de_RN_por_UC_e_Sistema->co_alternativo2->CellAttributes() ?>>
<span<?php echo $VersF5es_de_RN_por_UC_e_Sistema->co_alternativo2->ViewAttributes() ?>>
<?php echo $VersF5es_de_RN_por_UC_e_Sistema->co_alternativo2->ViewValue ?></span>
</td>
		<td<?php echo $VersF5es_de_RN_por_UC_e_Sistema->nu_versao->CellAttributes() ?>>
<span<?php echo $VersF5es_de_RN_por_UC_e_Sistema->nu_versao->ViewAttributes() ?>>
<?php echo $VersF5es_de_RN_por_UC_e_Sistema->nu_versao->ViewValue ?></span>
</td>
		<td<?php echo $VersF5es_de_RN_por_UC_e_Sistema->no_regraNegocio->CellAttributes() ?>>
<span<?php echo $VersF5es_de_RN_por_UC_e_Sistema->no_regraNegocio->ViewAttributes() ?>>
<?php echo $VersF5es_de_RN_por_UC_e_Sistema->no_regraNegocio->ViewValue ?></span>
</td>
		<td<?php echo $VersF5es_de_RN_por_UC_e_Sistema->ds_regraNegocio->CellAttributes() ?>>
<span<?php echo $VersF5es_de_RN_por_UC_e_Sistema->ds_regraNegocio->ViewAttributes() ?>>
<?php echo $VersF5es_de_RN_por_UC_e_Sistema->ds_regraNegocio->ViewValue ?></span>
</td>
		<td<?php echo $VersF5es_de_RN_por_UC_e_Sistema->nu_area->CellAttributes() ?>>
<span<?php echo $VersF5es_de_RN_por_UC_e_Sistema->nu_area->ViewAttributes() ?>>
<?php echo $VersF5es_de_RN_por_UC_e_Sistema->nu_area->ViewValue ?></span>
</td>
		<td<?php echo $VersF5es_de_RN_por_UC_e_Sistema->ds_origemRegra->CellAttributes() ?>>
<span<?php echo $VersF5es_de_RN_por_UC_e_Sistema->ds_origemRegra->ViewAttributes() ?>>
<?php echo $VersF5es_de_RN_por_UC_e_Sistema->ds_origemRegra->ViewValue ?></span>
</td>
		<td<?php echo $VersF5es_de_RN_por_UC_e_Sistema->nu_projeto->CellAttributes() ?>>
<span<?php echo $VersF5es_de_RN_por_UC_e_Sistema->nu_projeto->ViewAttributes() ?>>
<?php echo $VersF5es_de_RN_por_UC_e_Sistema->nu_projeto->ViewValue ?></span>
</td>
		<td<?php echo $VersF5es_de_RN_por_UC_e_Sistema->nu_stRegraNegocio->CellAttributes() ?>>
<span<?php echo $VersF5es_de_RN_por_UC_e_Sistema->nu_stRegraNegocio->ViewAttributes() ?>>
<?php echo $VersF5es_de_RN_por_UC_e_Sistema->nu_stRegraNegocio->ViewValue ?></span>
</td>
		<td<?php echo $VersF5es_de_RN_por_UC_e_Sistema->dt_versao->CellAttributes() ?>>
<span<?php echo $VersF5es_de_RN_por_UC_e_Sistema->dt_versao->ViewAttributes() ?>>
<?php echo $VersF5es_de_RN_por_UC_e_Sistema->dt_versao->ViewValue ?></span>
</td>
	</tr>
<?php
		$VersF5es_de_RN_por_UC_e_Sistema_report->DetailRecordset->MoveNext();
	}
	$VersF5es_de_RN_por_UC_e_Sistema_report->DetailRecordset->Close();

	// Save old group data
	$VersF5es_de_RN_por_UC_e_Sistema_report->ReportGroups[0] = $VersF5es_de_RN_por_UC_e_Sistema->no_sistema->CurrentValue;
	$VersF5es_de_RN_por_UC_e_Sistema_report->ReportGroups[1] = $VersF5es_de_RN_por_UC_e_Sistema->co_alternativo->CurrentValue;

	// Get next record
	$VersF5es_de_RN_por_UC_e_Sistema_report->Recordset->MoveNext();
	if ($VersF5es_de_RN_por_UC_e_Sistema_report->Recordset->EOF) {
		$VersF5es_de_RN_por_UC_e_Sistema_report->RecCnt = 0; // EOF, force all level breaks
	} else {
		$VersF5es_de_RN_por_UC_e_Sistema->no_sistema->setDbValue($VersF5es_de_RN_por_UC_e_Sistema_report->Recordset->fields('no_sistema'));
		$VersF5es_de_RN_por_UC_e_Sistema->co_alternativo->setDbValue($VersF5es_de_RN_por_UC_e_Sistema_report->Recordset->fields('co_alternativo'));
	}
	$VersF5es_de_RN_por_UC_e_Sistema_report->ChkLvlBreak();

	// Show footers
	if ($VersF5es_de_RN_por_UC_e_Sistema_report->LevelBreak[2]) {
		$VersF5es_de_RN_por_UC_e_Sistema->co_alternativo->CurrentValue = $VersF5es_de_RN_por_UC_e_Sistema_report->ReportGroups[1];

		// Render row for view
		$VersF5es_de_RN_por_UC_e_Sistema->RowType = EW_ROWTYPE_VIEW;
		$VersF5es_de_RN_por_UC_e_Sistema->ResetAttrs();
		$VersF5es_de_RN_por_UC_e_Sistema_report->RenderRow();
		$VersF5es_de_RN_por_UC_e_Sistema->co_alternativo->CurrentValue = $VersF5es_de_RN_por_UC_e_Sistema->co_alternativo->DbValue;
?>
<?php
}
	if ($VersF5es_de_RN_por_UC_e_Sistema_report->LevelBreak[1]) {
		$VersF5es_de_RN_por_UC_e_Sistema->no_sistema->CurrentValue = $VersF5es_de_RN_por_UC_e_Sistema_report->ReportGroups[0];

		// Render row for view
		$VersF5es_de_RN_por_UC_e_Sistema->RowType = EW_ROWTYPE_VIEW;
		$VersF5es_de_RN_por_UC_e_Sistema->ResetAttrs();
		$VersF5es_de_RN_por_UC_e_Sistema_report->RenderRow();
		$VersF5es_de_RN_por_UC_e_Sistema->no_sistema->CurrentValue = $VersF5es_de_RN_por_UC_e_Sistema->no_sistema->DbValue;
?>
<?php
}
}

// Close recordset
$VersF5es_de_RN_por_UC_e_Sistema_report->Recordset->Close();
?>
<?php if ($VersF5es_de_RN_por_UC_e_Sistema_report->RecordExists) { ?>
	<tr><td colspan=15>&nbsp;<br></td></tr>
	<tr><td colspan=15 class="ewGrandSummary"><?php echo $Language->Phrase("RptGrandTotal") ?>&nbsp;(<?php echo ew_FormatNumber($VersF5es_de_RN_por_UC_e_Sistema_report->ReportCounts[0], 0) ?>&nbsp;<?php echo $Language->Phrase("RptDtlRec") ?>)</td></tr>
<?php } ?>
<?php if ($VersF5es_de_RN_por_UC_e_Sistema_report->RecordExists) { ?>
	<tr><td colspan=15>&nbsp;<br></td></tr>
<?php } else { ?>
	<tr><td><?php echo $Language->Phrase("NoRecord") ?></td></tr>
<?php } ?>
</table>
</form>
<?php
$VersF5es_de_RN_por_UC_e_Sistema_report->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($VersF5es_de_RN_por_UC_e_Sistema->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$VersF5es_de_RN_por_UC_e_Sistema_report->Page_Terminate();
?>
