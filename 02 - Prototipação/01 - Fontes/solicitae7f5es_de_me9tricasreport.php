<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php

// Global variable for table object
$SolicitaE7F5es_de_ME9tricas = NULL;

//
// Table class for Solicitações de Métricas
//
class cSolicitaE7F5es_de_ME9tricas extends cTableBase {
	var $nu_solMetricas;
	var $nu_tpSolicitacao;
	var $nu_projeto;
	var $no_atividadeMaeRedmine;
	var $ds_observacoes;
	var $ds_documentacaoAux;
	var $ds_imapactoDb;
	var $ic_stSolicitacao;
	var $nu_usuarioIncluiu;
	var $dh_inclusao;
	var $dt_stSolicitacao;
	var $nu_usuarioAlterou;
	var $dh_alteracao;
	var $ic_bloqueio;
	var $qt_pfTotal;
	var $vr_pfContForn;
	var $nu_tpMetrica;
	var $ds_observacoesContForn;
	var $im_anexosContForn;
	var $nu_contagemAnt;
	var $ds_observaocoesContAnt;
	var $im_anexosContAnt;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'SolicitaE7F5es_de_ME9tricas';
		$this->TableName = 'Solicitações de Métricas';
		$this->TableType = 'REPORT';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->PrinterFriendlyForPdf = TRUE;
		$this->UserIDAllowSecurity = 0; // User ID Allow

		// nu_solMetricas
		$this->nu_solMetricas = new cField('SolicitaE7F5es_de_ME9tricas', 'Solicitações de Métricas', 'x_nu_solMetricas', 'nu_solMetricas', '[nu_solMetricas]', 'CAST([nu_solMetricas] AS NVARCHAR)', 3, -1, FALSE, '[nu_solMetricas]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_solMetricas->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_solMetricas'] = &$this->nu_solMetricas;

		// nu_tpSolicitacao
		$this->nu_tpSolicitacao = new cField('SolicitaE7F5es_de_ME9tricas', 'Solicitações de Métricas', 'x_nu_tpSolicitacao', 'nu_tpSolicitacao', '[nu_tpSolicitacao]', 'CAST([nu_tpSolicitacao] AS NVARCHAR)', 3, -1, FALSE, '[nu_tpSolicitacao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_tpSolicitacao->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_tpSolicitacao'] = &$this->nu_tpSolicitacao;

		// nu_projeto
		$this->nu_projeto = new cField('SolicitaE7F5es_de_ME9tricas', 'Solicitações de Métricas', 'x_nu_projeto', 'nu_projeto', '[nu_projeto]', 'CAST([nu_projeto] AS NVARCHAR)', 3, -1, FALSE, '[nu_projeto]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_projeto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_projeto'] = &$this->nu_projeto;

		// no_atividadeMaeRedmine
		$this->no_atividadeMaeRedmine = new cField('SolicitaE7F5es_de_ME9tricas', 'Solicitações de Métricas', 'x_no_atividadeMaeRedmine', 'no_atividadeMaeRedmine', '[no_atividadeMaeRedmine]', '[no_atividadeMaeRedmine]', 200, -1, FALSE, '[no_atividadeMaeRedmine]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_atividadeMaeRedmine'] = &$this->no_atividadeMaeRedmine;

		// ds_observacoes
		$this->ds_observacoes = new cField('SolicitaE7F5es_de_ME9tricas', 'Solicitações de Métricas', 'x_ds_observacoes', 'ds_observacoes', '[ds_observacoes]', '[ds_observacoes]', 201, -1, FALSE, '[ds_observacoes]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_observacoes'] = &$this->ds_observacoes;

		// ds_documentacaoAux
		$this->ds_documentacaoAux = new cField('SolicitaE7F5es_de_ME9tricas', 'Solicitações de Métricas', 'x_ds_documentacaoAux', 'ds_documentacaoAux', '[ds_documentacaoAux]', '[ds_documentacaoAux]', 201, -1, FALSE, '[ds_documentacaoAux]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_documentacaoAux'] = &$this->ds_documentacaoAux;

		// ds_imapactoDb
		$this->ds_imapactoDb = new cField('SolicitaE7F5es_de_ME9tricas', 'Solicitações de Métricas', 'x_ds_imapactoDb', 'ds_imapactoDb', '[ds_imapactoDb]', '[ds_imapactoDb]', 201, -1, FALSE, '[ds_imapactoDb]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_imapactoDb'] = &$this->ds_imapactoDb;

		// ic_stSolicitacao
		$this->ic_stSolicitacao = new cField('SolicitaE7F5es_de_ME9tricas', 'Solicitações de Métricas', 'x_ic_stSolicitacao', 'ic_stSolicitacao', '[ic_stSolicitacao]', '[ic_stSolicitacao]', 129, -1, FALSE, '[ic_stSolicitacao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_stSolicitacao'] = &$this->ic_stSolicitacao;

		// nu_usuarioIncluiu
		$this->nu_usuarioIncluiu = new cField('SolicitaE7F5es_de_ME9tricas', 'Solicitações de Métricas', 'x_nu_usuarioIncluiu', 'nu_usuarioIncluiu', '[nu_usuarioIncluiu]', 'CAST([nu_usuarioIncluiu] AS NVARCHAR)', 3, -1, FALSE, '[nu_usuarioIncluiu]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_usuarioIncluiu->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_usuarioIncluiu'] = &$this->nu_usuarioIncluiu;

		// dh_inclusao
		$this->dh_inclusao = new cField('SolicitaE7F5es_de_ME9tricas', 'Solicitações de Métricas', 'x_dh_inclusao', 'dh_inclusao', '[dh_inclusao]', '(REPLACE(STR(DAY([dh_inclusao]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dh_inclusao]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dh_inclusao]),4,0))', 135, 7, FALSE, '[dh_inclusao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dh_inclusao->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dh_inclusao'] = &$this->dh_inclusao;

		// dt_stSolicitacao
		$this->dt_stSolicitacao = new cField('SolicitaE7F5es_de_ME9tricas', 'Solicitações de Métricas', 'x_dt_stSolicitacao', 'dt_stSolicitacao', '[dt_stSolicitacao]', '(REPLACE(STR(DAY([dt_stSolicitacao]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dt_stSolicitacao]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dt_stSolicitacao]),4,0))', 135, 7, FALSE, '[dt_stSolicitacao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dt_stSolicitacao->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dt_stSolicitacao'] = &$this->dt_stSolicitacao;

		// nu_usuarioAlterou
		$this->nu_usuarioAlterou = new cField('SolicitaE7F5es_de_ME9tricas', 'Solicitações de Métricas', 'x_nu_usuarioAlterou', 'nu_usuarioAlterou', '[nu_usuarioAlterou]', 'CAST([nu_usuarioAlterou] AS NVARCHAR)', 3, -1, FALSE, '[nu_usuarioAlterou]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_usuarioAlterou->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_usuarioAlterou'] = &$this->nu_usuarioAlterou;

		// dh_alteracao
		$this->dh_alteracao = new cField('SolicitaE7F5es_de_ME9tricas', 'Solicitações de Métricas', 'x_dh_alteracao', 'dh_alteracao', '[dh_alteracao]', '(REPLACE(STR(DAY([dh_alteracao]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dh_alteracao]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dh_alteracao]),4,0))', 135, 10, FALSE, '[dh_alteracao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dh_alteracao->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dh_alteracao'] = &$this->dh_alteracao;

		// ic_bloqueio
		$this->ic_bloqueio = new cField('SolicitaE7F5es_de_ME9tricas', 'Solicitações de Métricas', 'x_ic_bloqueio', 'ic_bloqueio', '[ic_bloqueio]', '[ic_bloqueio]', 129, -1, FALSE, '[ic_bloqueio]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_bloqueio'] = &$this->ic_bloqueio;

		// qt_pfTotal
		$this->qt_pfTotal = new cField('SolicitaE7F5es_de_ME9tricas', 'Solicitações de Métricas', 'x_qt_pfTotal', 'qt_pfTotal', '[qt_pfTotal]', 'CAST([qt_pfTotal] AS NVARCHAR)', 131, -1, FALSE, '[qt_pfTotal]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->qt_pfTotal->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['qt_pfTotal'] = &$this->qt_pfTotal;

		// vr_pfContForn
		$this->vr_pfContForn = new cField('SolicitaE7F5es_de_ME9tricas', 'Solicitações de Métricas', 'x_vr_pfContForn', 'vr_pfContForn', '[vr_pfContForn]', 'CAST([vr_pfContForn] AS NVARCHAR)', 131, -1, FALSE, '[vr_pfContForn]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->vr_pfContForn->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['vr_pfContForn'] = &$this->vr_pfContForn;

		// nu_tpMetrica
		$this->nu_tpMetrica = new cField('SolicitaE7F5es_de_ME9tricas', 'Solicitações de Métricas', 'x_nu_tpMetrica', 'nu_tpMetrica', '[nu_tpMetrica]', 'CAST([nu_tpMetrica] AS NVARCHAR)', 3, -1, FALSE, '[nu_tpMetrica]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_tpMetrica->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_tpMetrica'] = &$this->nu_tpMetrica;

		// ds_observacoesContForn
		$this->ds_observacoesContForn = new cField('SolicitaE7F5es_de_ME9tricas', 'Solicitações de Métricas', 'x_ds_observacoesContForn', 'ds_observacoesContForn', '[ds_observacoesContForn]', '[ds_observacoesContForn]', 201, -1, FALSE, '[ds_observacoesContForn]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_observacoesContForn'] = &$this->ds_observacoesContForn;

		// im_anexosContForn
		$this->im_anexosContForn = new cField('SolicitaE7F5es_de_ME9tricas', 'Solicitações de Métricas', 'x_im_anexosContForn', 'im_anexosContForn', '[im_anexosContForn]', '[im_anexosContForn]', 201, -1, FALSE, '[im_anexosContForn]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['im_anexosContForn'] = &$this->im_anexosContForn;

		// nu_contagemAnt
		$this->nu_contagemAnt = new cField('SolicitaE7F5es_de_ME9tricas', 'Solicitações de Métricas', 'x_nu_contagemAnt', 'nu_contagemAnt', '[nu_contagemAnt]', 'CAST([nu_contagemAnt] AS NVARCHAR)', 3, -1, FALSE, '[nu_contagemAnt]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_contagemAnt->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_contagemAnt'] = &$this->nu_contagemAnt;

		// ds_observaocoesContAnt
		$this->ds_observaocoesContAnt = new cField('SolicitaE7F5es_de_ME9tricas', 'Solicitações de Métricas', 'x_ds_observaocoesContAnt', 'ds_observaocoesContAnt', '[ds_observaocoesContAnt]', '[ds_observaocoesContAnt]', 201, -1, FALSE, '[ds_observaocoesContAnt]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_observaocoesContAnt'] = &$this->ds_observaocoesContAnt;

		// im_anexosContAnt
		$this->im_anexosContAnt = new cField('SolicitaE7F5es_de_ME9tricas', 'Solicitações de Métricas', 'x_im_anexosContAnt', 'im_anexosContAnt', '[im_anexosContAnt]', '[im_anexosContAnt]', 201, -1, FALSE, '[im_anexosContAnt]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['im_anexosContAnt'] = &$this->im_anexosContAnt;
	}

	// Report group level SQL
	function SqlGroupSelect() { // Select
		return "SELECT DISTINCT [nu_tpSolicitacao],[nu_projeto] FROM [dbo].[solicitacaoMetricas]";
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
		return "[nu_tpSolicitacao] ASC,[nu_projeto] ASC";
	}

	// Report detail level SQL
	function SqlDetailSelect() { // Select
		return "SELECT * FROM [dbo].[solicitacaoMetricas]";
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
		return "[nu_solMetricas] DESC";
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
			return "solicitae7f5es_de_me9tricasreport.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "solicitae7f5es_de_me9tricasreport.php";
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
		if (!is_null($this->nu_solMetricas->CurrentValue)) {
			$sUrl .= "nu_solMetricas=" . urlencode($this->nu_solMetricas->CurrentValue);
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
			$arKeys[] = @$_GET["nu_solMetricas"]; // nu_solMetricas

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
			$this->nu_solMetricas->CurrentValue = $key;
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

$SolicitaE7F5es_de_ME9tricas_report = NULL; // Initialize page object first

class cSolicitaE7F5es_de_ME9tricas_report extends cSolicitaE7F5es_de_ME9tricas {

	// Page ID
	var $PageID = 'report';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'Solicitações de Métricas';

	// Page object name
	var $PageObjName = 'SolicitaE7F5es_de_ME9tricas_report';

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

		// Table object (SolicitaE7F5es_de_ME9tricas)
		if (!isset($GLOBALS["SolicitaE7F5es_de_ME9tricas"])) {
			$GLOBALS["SolicitaE7F5es_de_ME9tricas"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["SolicitaE7F5es_de_ME9tricas"];
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
			define("EW_TABLE_NAME", 'Solicitações de Métricas', TRUE);

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
		$this->ReportTotals = &ew_Init2DArray(3, 13, 0);
		$this->ReportMaxs = &ew_Init2DArray(3, 13, 0);
		$this->ReportMins = &ew_Init2DArray(3, 13, 0);

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
			if (!ew_CompareValue($this->nu_tpSolicitacao->CurrentValue, $this->ReportGroups[0])) {
				$this->LevelBreak[1] = TRUE;
				$this->LevelBreak[2] = TRUE;
			}
			if (!ew_CompareValue($this->nu_projeto->CurrentValue, $this->ReportGroups[1])) {
				$this->LevelBreak[2] = TRUE;
			}
		}
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->qt_pfTotal->FormValue == $this->qt_pfTotal->CurrentValue && is_numeric(ew_StrToFloat($this->qt_pfTotal->CurrentValue)))
			$this->qt_pfTotal->CurrentValue = ew_StrToFloat($this->qt_pfTotal->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_pfContForn->FormValue == $this->vr_pfContForn->CurrentValue && is_numeric(ew_StrToFloat($this->vr_pfContForn->CurrentValue)))
			$this->vr_pfContForn->CurrentValue = ew_StrToFloat($this->vr_pfContForn->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_solMetricas
		// nu_tpSolicitacao
		// nu_projeto
		// no_atividadeMaeRedmine
		// ds_observacoes
		// ds_documentacaoAux
		// ds_imapactoDb
		// ic_stSolicitacao
		// nu_usuarioIncluiu
		// dh_inclusao
		// dt_stSolicitacao
		// nu_usuarioAlterou
		// dh_alteracao
		// ic_bloqueio
		// qt_pfTotal
		// vr_pfContForn
		// nu_tpMetrica
		// ds_observacoesContForn
		// im_anexosContForn
		// nu_contagemAnt
		// ds_observaocoesContAnt
		// im_anexosContAnt

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_solMetricas
			$this->nu_solMetricas->ViewValue = $this->nu_solMetricas->CurrentValue;
			$this->nu_solMetricas->ViewCustomAttributes = "";

			// nu_tpSolicitacao
			if (strval($this->nu_tpSolicitacao->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpSolicitacao]" . ew_SearchString("=", $this->nu_tpSolicitacao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpSolicitacao], [no_tpSolicitacao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpsolicitacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpSolicitacao] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpSolicitacao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpSolicitacao->ViewValue = $this->nu_tpSolicitacao->CurrentValue;
				}
			} else {
				$this->nu_tpSolicitacao->ViewValue = NULL;
			}
			$this->nu_tpSolicitacao->ViewCustomAttributes = "";

			// nu_projeto
			if (strval($this->nu_projeto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_projeto]" . ew_SearchString("=", $this->nu_projeto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_projeto], [no_projeto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[projeto]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_projeto] ASC";
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

			// no_atividadeMaeRedmine
			$this->no_atividadeMaeRedmine->ViewValue = $this->no_atividadeMaeRedmine->CurrentValue;
			$this->no_atividadeMaeRedmine->ViewCustomAttributes = "";

			// ic_stSolicitacao
			if (strval($this->ic_stSolicitacao->CurrentValue) <> "") {
				switch ($this->ic_stSolicitacao->CurrentValue) {
					case $this->ic_stSolicitacao->FldTagValue(1):
						$this->ic_stSolicitacao->ViewValue = $this->ic_stSolicitacao->FldTagCaption(1) <> "" ? $this->ic_stSolicitacao->FldTagCaption(1) : $this->ic_stSolicitacao->CurrentValue;
						break;
					case $this->ic_stSolicitacao->FldTagValue(2):
						$this->ic_stSolicitacao->ViewValue = $this->ic_stSolicitacao->FldTagCaption(2) <> "" ? $this->ic_stSolicitacao->FldTagCaption(2) : $this->ic_stSolicitacao->CurrentValue;
						break;
					case $this->ic_stSolicitacao->FldTagValue(3):
						$this->ic_stSolicitacao->ViewValue = $this->ic_stSolicitacao->FldTagCaption(3) <> "" ? $this->ic_stSolicitacao->FldTagCaption(3) : $this->ic_stSolicitacao->CurrentValue;
						break;
					case $this->ic_stSolicitacao->FldTagValue(4):
						$this->ic_stSolicitacao->ViewValue = $this->ic_stSolicitacao->FldTagCaption(4) <> "" ? $this->ic_stSolicitacao->FldTagCaption(4) : $this->ic_stSolicitacao->CurrentValue;
						break;
					default:
						$this->ic_stSolicitacao->ViewValue = $this->ic_stSolicitacao->CurrentValue;
				}
			} else {
				$this->ic_stSolicitacao->ViewValue = NULL;
			}
			$this->ic_stSolicitacao->ViewCustomAttributes = "";

			// nu_usuarioIncluiu
			if (strval($this->nu_usuarioIncluiu->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioIncluiu->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioIncluiu->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioIncluiu->ViewValue = $this->nu_usuarioIncluiu->CurrentValue;
				}
			} else {
				$this->nu_usuarioIncluiu->ViewValue = NULL;
			}
			$this->nu_usuarioIncluiu->ViewCustomAttributes = "";

			// dh_inclusao
			$this->dh_inclusao->ViewValue = $this->dh_inclusao->CurrentValue;
			$this->dh_inclusao->ViewValue = ew_FormatDateTime($this->dh_inclusao->ViewValue, 7);
			$this->dh_inclusao->ViewCustomAttributes = "";

			// dt_stSolicitacao
			$this->dt_stSolicitacao->ViewValue = $this->dt_stSolicitacao->CurrentValue;
			$this->dt_stSolicitacao->ViewValue = ew_FormatDateTime($this->dt_stSolicitacao->ViewValue, 7);
			$this->dt_stSolicitacao->ViewCustomAttributes = "";

			// nu_usuarioAlterou
			if (strval($this->nu_usuarioAlterou->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioAlterou->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioAlterou->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioAlterou->ViewValue = $this->nu_usuarioAlterou->CurrentValue;
				}
			} else {
				$this->nu_usuarioAlterou->ViewValue = NULL;
			}
			$this->nu_usuarioAlterou->ViewCustomAttributes = "";

			// dh_alteracao
			$this->dh_alteracao->ViewValue = $this->dh_alteracao->CurrentValue;
			$this->dh_alteracao->ViewValue = ew_FormatDateTime($this->dh_alteracao->ViewValue, 10);
			$this->dh_alteracao->ViewCustomAttributes = "";

			// ic_bloqueio
			$this->ic_bloqueio->ViewValue = $this->ic_bloqueio->CurrentValue;
			$this->ic_bloqueio->ViewCustomAttributes = "";

			// qt_pfTotal
			$this->qt_pfTotal->ViewValue = $this->qt_pfTotal->CurrentValue;
			$this->qt_pfTotal->ViewCustomAttributes = "";

			// vr_pfContForn
			$this->vr_pfContForn->ViewValue = $this->vr_pfContForn->CurrentValue;
			$this->vr_pfContForn->ViewCustomAttributes = "";

			// nu_tpMetrica
			$this->nu_tpMetrica->ViewValue = $this->nu_tpMetrica->CurrentValue;
			$this->nu_tpMetrica->ViewCustomAttributes = "";

			// nu_contagemAnt
			$this->nu_contagemAnt->ViewValue = $this->nu_contagemAnt->CurrentValue;
			$this->nu_contagemAnt->ViewCustomAttributes = "";

			// nu_solMetricas
			$this->nu_solMetricas->LinkCustomAttributes = "";
			$this->nu_solMetricas->HrefValue = "";
			$this->nu_solMetricas->TooltipValue = "";

			// nu_tpSolicitacao
			$this->nu_tpSolicitacao->LinkCustomAttributes = "";
			$this->nu_tpSolicitacao->HrefValue = "";
			$this->nu_tpSolicitacao->TooltipValue = "";

			// nu_projeto
			$this->nu_projeto->LinkCustomAttributes = "";
			$this->nu_projeto->HrefValue = "";
			$this->nu_projeto->TooltipValue = "";

			// no_atividadeMaeRedmine
			$this->no_atividadeMaeRedmine->LinkCustomAttributes = "";
			$this->no_atividadeMaeRedmine->HrefValue = "";
			$this->no_atividadeMaeRedmine->TooltipValue = "";

			// ic_stSolicitacao
			$this->ic_stSolicitacao->LinkCustomAttributes = "";
			$this->ic_stSolicitacao->HrefValue = "";
			$this->ic_stSolicitacao->TooltipValue = "";

			// nu_usuarioIncluiu
			$this->nu_usuarioIncluiu->LinkCustomAttributes = "";
			$this->nu_usuarioIncluiu->HrefValue = "";
			$this->nu_usuarioIncluiu->TooltipValue = "";

			// dh_inclusao
			$this->dh_inclusao->LinkCustomAttributes = "";
			$this->dh_inclusao->HrefValue = "";
			$this->dh_inclusao->TooltipValue = "";

			// dt_stSolicitacao
			$this->dt_stSolicitacao->LinkCustomAttributes = "";
			$this->dt_stSolicitacao->HrefValue = "";
			$this->dt_stSolicitacao->TooltipValue = "";

			// nu_usuarioAlterou
			$this->nu_usuarioAlterou->LinkCustomAttributes = "";
			$this->nu_usuarioAlterou->HrefValue = "";
			$this->nu_usuarioAlterou->TooltipValue = "";

			// dh_alteracao
			$this->dh_alteracao->LinkCustomAttributes = "";
			$this->dh_alteracao->HrefValue = "";
			$this->dh_alteracao->TooltipValue = "";

			// qt_pfTotal
			$this->qt_pfTotal->LinkCustomAttributes = "";
			$this->qt_pfTotal->HrefValue = "";
			$this->qt_pfTotal->TooltipValue = "";

			// vr_pfContForn
			$this->vr_pfContForn->LinkCustomAttributes = "";
			$this->vr_pfContForn->HrefValue = "";
			$this->vr_pfContForn->TooltipValue = "";

			// nu_tpMetrica
			$this->nu_tpMetrica->LinkCustomAttributes = "";
			$this->nu_tpMetrica->HrefValue = "";
			$this->nu_tpMetrica->TooltipValue = "";

			// nu_contagemAnt
			$this->nu_contagemAnt->LinkCustomAttributes = "";
			$this->nu_contagemAnt->HrefValue = "";
			$this->nu_contagemAnt->TooltipValue = "";
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
if (!isset($SolicitaE7F5es_de_ME9tricas_report)) $SolicitaE7F5es_de_ME9tricas_report = new cSolicitaE7F5es_de_ME9tricas_report();

// Page init
$SolicitaE7F5es_de_ME9tricas_report->Page_Init();

// Page main
$SolicitaE7F5es_de_ME9tricas_report->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$SolicitaE7F5es_de_ME9tricas_report->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($SolicitaE7F5es_de_ME9tricas->Export == "") { ?>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($SolicitaE7F5es_de_ME9tricas->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php
$SolicitaE7F5es_de_ME9tricas_report->DefaultFilter = "";
$SolicitaE7F5es_de_ME9tricas_report->ReportFilter = $SolicitaE7F5es_de_ME9tricas_report->DefaultFilter;
if (!$Security->CanReport()) {
	if ($SolicitaE7F5es_de_ME9tricas_report->ReportFilter <> "") $SolicitaE7F5es_de_ME9tricas_report->ReportFilter .= " AND ";
	$SolicitaE7F5es_de_ME9tricas_report->ReportFilter .= "(0=1)";
}
if ($SolicitaE7F5es_de_ME9tricas_report->DbDetailFilter <> "") {
	if ($SolicitaE7F5es_de_ME9tricas_report->ReportFilter <> "") $SolicitaE7F5es_de_ME9tricas_report->ReportFilter .= " AND ";
	$SolicitaE7F5es_de_ME9tricas_report->ReportFilter .= "(" . $SolicitaE7F5es_de_ME9tricas_report->DbDetailFilter . ")";
}

// Set up filter and load Group level sql
$SolicitaE7F5es_de_ME9tricas->CurrentFilter = $SolicitaE7F5es_de_ME9tricas_report->ReportFilter;
$SolicitaE7F5es_de_ME9tricas_report->ReportSql = $SolicitaE7F5es_de_ME9tricas->GroupSQL();

// Load recordset
$SolicitaE7F5es_de_ME9tricas_report->Recordset = $conn->Execute($SolicitaE7F5es_de_ME9tricas_report->ReportSql);
$SolicitaE7F5es_de_ME9tricas_report->RecordExists = !$SolicitaE7F5es_de_ME9tricas_report->Recordset->EOF;
?>
<?php if ($SolicitaE7F5es_de_ME9tricas->Export == "") { ?>
<?php if ($SolicitaE7F5es_de_ME9tricas_report->RecordExists) { ?>
<div class="ewViewExportOptions"><?php $SolicitaE7F5es_de_ME9tricas_report->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php } ?>
<?php $SolicitaE7F5es_de_ME9tricas_report->ShowPageHeader(); ?>
<form method="post">
<table class="ewReportTable">
<?php

// Get First Row
if ($SolicitaE7F5es_de_ME9tricas_report->RecordExists) {
	$SolicitaE7F5es_de_ME9tricas->nu_tpSolicitacao->setDbValue($SolicitaE7F5es_de_ME9tricas_report->Recordset->fields('nu_tpSolicitacao'));
	$SolicitaE7F5es_de_ME9tricas_report->ReportGroups[0] = $SolicitaE7F5es_de_ME9tricas->nu_tpSolicitacao->DbValue;
	$SolicitaE7F5es_de_ME9tricas->nu_projeto->setDbValue($SolicitaE7F5es_de_ME9tricas_report->Recordset->fields('nu_projeto'));
	$SolicitaE7F5es_de_ME9tricas_report->ReportGroups[1] = $SolicitaE7F5es_de_ME9tricas->nu_projeto->DbValue;
}
$SolicitaE7F5es_de_ME9tricas_report->RecCnt = 0;
$SolicitaE7F5es_de_ME9tricas_report->ReportCounts[0] = 0;
$SolicitaE7F5es_de_ME9tricas_report->ChkLvlBreak();
while (!$SolicitaE7F5es_de_ME9tricas_report->Recordset->EOF) {

	// Render for view
	$SolicitaE7F5es_de_ME9tricas->RowType = EW_ROWTYPE_VIEW;
	$SolicitaE7F5es_de_ME9tricas->ResetAttrs();
	$SolicitaE7F5es_de_ME9tricas_report->RenderRow();

	// Show group headers
	if ($SolicitaE7F5es_de_ME9tricas_report->LevelBreak[1]) { // Reset counter and aggregation
?>
	<tr><td colspan=2 class="ewGroupField"><?php echo $SolicitaE7F5es_de_ME9tricas->nu_tpSolicitacao->FldCaption() ?></td>
	<td colspan=12 class="ewGroupName">
<span<?php echo $SolicitaE7F5es_de_ME9tricas->nu_tpSolicitacao->ViewAttributes() ?>>
<?php echo $SolicitaE7F5es_de_ME9tricas->nu_tpSolicitacao->ViewValue ?></span>
</td></tr>
<?php
	}
	if ($SolicitaE7F5es_de_ME9tricas_report->LevelBreak[2]) { // Reset counter and aggregation
?>
	<tr><td><div class="ewGroupIndent"></div></td><td class="ewGroupField"><?php echo $SolicitaE7F5es_de_ME9tricas->nu_projeto->FldCaption() ?></td>
	<td colspan=12 class="ewGroupName">
<span<?php echo $SolicitaE7F5es_de_ME9tricas->nu_projeto->ViewAttributes() ?>>
<?php echo $SolicitaE7F5es_de_ME9tricas->nu_projeto->ViewValue ?></span>
</td></tr>
<?php
	}

	// Get detail records
	$SolicitaE7F5es_de_ME9tricas_report->ReportFilter = $SolicitaE7F5es_de_ME9tricas_report->DefaultFilter;
	if ($SolicitaE7F5es_de_ME9tricas_report->ReportFilter <> "") $SolicitaE7F5es_de_ME9tricas_report->ReportFilter .= " AND ";
	if (is_null($SolicitaE7F5es_de_ME9tricas->nu_tpSolicitacao->CurrentValue)) {
		$SolicitaE7F5es_de_ME9tricas_report->ReportFilter .= "([nu_tpSolicitacao] IS NULL)";
	} else {
		$SolicitaE7F5es_de_ME9tricas_report->ReportFilter .= "([nu_tpSolicitacao] = " . ew_AdjustSql($SolicitaE7F5es_de_ME9tricas->nu_tpSolicitacao->CurrentValue) . ")";
	}
	if ($SolicitaE7F5es_de_ME9tricas_report->ReportFilter <> "") $SolicitaE7F5es_de_ME9tricas_report->ReportFilter .= " AND ";
	if (is_null($SolicitaE7F5es_de_ME9tricas->nu_projeto->CurrentValue)) {
		$SolicitaE7F5es_de_ME9tricas_report->ReportFilter .= "([nu_projeto] IS NULL)";
	} else {
		$SolicitaE7F5es_de_ME9tricas_report->ReportFilter .= "([nu_projeto] = " . ew_AdjustSql($SolicitaE7F5es_de_ME9tricas->nu_projeto->CurrentValue) . ")";
	}
	if ($SolicitaE7F5es_de_ME9tricas_report->DbDetailFilter <> "") {
		if ($SolicitaE7F5es_de_ME9tricas_report->ReportFilter <> "")
			$SolicitaE7F5es_de_ME9tricas_report->ReportFilter .= " AND ";
		$SolicitaE7F5es_de_ME9tricas_report->ReportFilter .= "(" . $SolicitaE7F5es_de_ME9tricas_report->DbDetailFilter . ")";
	}
	if (!$Security->CanReport()) {
		if ($sFilter <> "") $sFilter .= " AND ";
		$sFilter .= "(0=1)";
	}

	// Set up detail SQL
	$SolicitaE7F5es_de_ME9tricas->CurrentFilter = $SolicitaE7F5es_de_ME9tricas_report->ReportFilter;
	$SolicitaE7F5es_de_ME9tricas_report->ReportSql = $SolicitaE7F5es_de_ME9tricas->DetailSQL();

	// Load detail records
	$SolicitaE7F5es_de_ME9tricas_report->DetailRecordset = $conn->Execute($SolicitaE7F5es_de_ME9tricas_report->ReportSql);
	$SolicitaE7F5es_de_ME9tricas_report->DtlRecordCount = $SolicitaE7F5es_de_ME9tricas_report->DetailRecordset->RecordCount();

	// Initialize aggregates
	if (!$SolicitaE7F5es_de_ME9tricas_report->DetailRecordset->EOF) {
		$SolicitaE7F5es_de_ME9tricas_report->RecCnt++;
	}
	if ($SolicitaE7F5es_de_ME9tricas_report->RecCnt == 1) {
		$SolicitaE7F5es_de_ME9tricas_report->ReportCounts[0] = 0;
	}
	for ($i = 1; $i <= 2; $i++) {
		if ($SolicitaE7F5es_de_ME9tricas_report->LevelBreak[$i]) { // Reset counter and aggregation
			$SolicitaE7F5es_de_ME9tricas_report->ReportCounts[$i] = 0;
		}
	}
	$SolicitaE7F5es_de_ME9tricas_report->ReportCounts[0] += $SolicitaE7F5es_de_ME9tricas_report->DtlRecordCount;
	$SolicitaE7F5es_de_ME9tricas_report->ReportCounts[1] += $SolicitaE7F5es_de_ME9tricas_report->DtlRecordCount;
	$SolicitaE7F5es_de_ME9tricas_report->ReportCounts[2] += $SolicitaE7F5es_de_ME9tricas_report->DtlRecordCount;
	if ($SolicitaE7F5es_de_ME9tricas_report->RecordExists) {
?>
	<tr>
		<td><div class="ewGroupIndent"></div></td>
		<td><div class="ewGroupIndent"></div></td>
		<td class="ewGroupHeader"><?php echo $SolicitaE7F5es_de_ME9tricas->nu_solMetricas->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $SolicitaE7F5es_de_ME9tricas->no_atividadeMaeRedmine->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $SolicitaE7F5es_de_ME9tricas->ic_stSolicitacao->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $SolicitaE7F5es_de_ME9tricas->nu_usuarioIncluiu->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $SolicitaE7F5es_de_ME9tricas->dh_inclusao->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $SolicitaE7F5es_de_ME9tricas->dt_stSolicitacao->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $SolicitaE7F5es_de_ME9tricas->nu_usuarioAlterou->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $SolicitaE7F5es_de_ME9tricas->dh_alteracao->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $SolicitaE7F5es_de_ME9tricas->qt_pfTotal->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $SolicitaE7F5es_de_ME9tricas->vr_pfContForn->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $SolicitaE7F5es_de_ME9tricas->nu_tpMetrica->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $SolicitaE7F5es_de_ME9tricas->nu_contagemAnt->FldCaption() ?></td>
	</tr>
<?php
	}
	while (!$SolicitaE7F5es_de_ME9tricas_report->DetailRecordset->EOF) {
		$SolicitaE7F5es_de_ME9tricas->nu_solMetricas->setDbValue($SolicitaE7F5es_de_ME9tricas_report->DetailRecordset->fields('nu_solMetricas'));
		$SolicitaE7F5es_de_ME9tricas->no_atividadeMaeRedmine->setDbValue($SolicitaE7F5es_de_ME9tricas_report->DetailRecordset->fields('no_atividadeMaeRedmine'));
		$SolicitaE7F5es_de_ME9tricas->ic_stSolicitacao->setDbValue($SolicitaE7F5es_de_ME9tricas_report->DetailRecordset->fields('ic_stSolicitacao'));
		$SolicitaE7F5es_de_ME9tricas->nu_usuarioIncluiu->setDbValue($SolicitaE7F5es_de_ME9tricas_report->DetailRecordset->fields('nu_usuarioIncluiu'));
		$SolicitaE7F5es_de_ME9tricas->dh_inclusao->setDbValue($SolicitaE7F5es_de_ME9tricas_report->DetailRecordset->fields('dh_inclusao'));
		$SolicitaE7F5es_de_ME9tricas->dt_stSolicitacao->setDbValue($SolicitaE7F5es_de_ME9tricas_report->DetailRecordset->fields('dt_stSolicitacao'));
		$SolicitaE7F5es_de_ME9tricas->nu_usuarioAlterou->setDbValue($SolicitaE7F5es_de_ME9tricas_report->DetailRecordset->fields('nu_usuarioAlterou'));
		$SolicitaE7F5es_de_ME9tricas->dh_alteracao->setDbValue($SolicitaE7F5es_de_ME9tricas_report->DetailRecordset->fields('dh_alteracao'));
		$SolicitaE7F5es_de_ME9tricas->qt_pfTotal->setDbValue($SolicitaE7F5es_de_ME9tricas_report->DetailRecordset->fields('qt_pfTotal'));
		$SolicitaE7F5es_de_ME9tricas->vr_pfContForn->setDbValue($SolicitaE7F5es_de_ME9tricas_report->DetailRecordset->fields('vr_pfContForn'));
		$SolicitaE7F5es_de_ME9tricas->nu_tpMetrica->setDbValue($SolicitaE7F5es_de_ME9tricas_report->DetailRecordset->fields('nu_tpMetrica'));
		$SolicitaE7F5es_de_ME9tricas->nu_contagemAnt->setDbValue($SolicitaE7F5es_de_ME9tricas_report->DetailRecordset->fields('nu_contagemAnt'));

		// Render for view
		$SolicitaE7F5es_de_ME9tricas->RowType = EW_ROWTYPE_VIEW;
		$SolicitaE7F5es_de_ME9tricas->ResetAttrs();
		$SolicitaE7F5es_de_ME9tricas_report->RenderRow();
?>
	<tr>
		<td><div class="ewGroupIndent"></div></td>
		<td><div class="ewGroupIndent"></div></td>
		<td<?php echo $SolicitaE7F5es_de_ME9tricas->nu_solMetricas->CellAttributes() ?>>
<span<?php echo $SolicitaE7F5es_de_ME9tricas->nu_solMetricas->ViewAttributes() ?>>
<?php echo $SolicitaE7F5es_de_ME9tricas->nu_solMetricas->ViewValue ?></span>
</td>
		<td<?php echo $SolicitaE7F5es_de_ME9tricas->no_atividadeMaeRedmine->CellAttributes() ?>>
<span<?php echo $SolicitaE7F5es_de_ME9tricas->no_atividadeMaeRedmine->ViewAttributes() ?>>
<?php echo $SolicitaE7F5es_de_ME9tricas->no_atividadeMaeRedmine->ViewValue ?></span>
</td>
		<td<?php echo $SolicitaE7F5es_de_ME9tricas->ic_stSolicitacao->CellAttributes() ?>>
<span<?php echo $SolicitaE7F5es_de_ME9tricas->ic_stSolicitacao->ViewAttributes() ?>>
<?php echo $SolicitaE7F5es_de_ME9tricas->ic_stSolicitacao->ViewValue ?></span>
</td>
		<td<?php echo $SolicitaE7F5es_de_ME9tricas->nu_usuarioIncluiu->CellAttributes() ?>>
<span<?php echo $SolicitaE7F5es_de_ME9tricas->nu_usuarioIncluiu->ViewAttributes() ?>>
<?php echo $SolicitaE7F5es_de_ME9tricas->nu_usuarioIncluiu->ViewValue ?></span>
</td>
		<td<?php echo $SolicitaE7F5es_de_ME9tricas->dh_inclusao->CellAttributes() ?>>
<span<?php echo $SolicitaE7F5es_de_ME9tricas->dh_inclusao->ViewAttributes() ?>>
<?php echo $SolicitaE7F5es_de_ME9tricas->dh_inclusao->ViewValue ?></span>
</td>
		<td<?php echo $SolicitaE7F5es_de_ME9tricas->dt_stSolicitacao->CellAttributes() ?>>
<span<?php echo $SolicitaE7F5es_de_ME9tricas->dt_stSolicitacao->ViewAttributes() ?>>
<?php echo $SolicitaE7F5es_de_ME9tricas->dt_stSolicitacao->ViewValue ?></span>
</td>
		<td<?php echo $SolicitaE7F5es_de_ME9tricas->nu_usuarioAlterou->CellAttributes() ?>>
<span<?php echo $SolicitaE7F5es_de_ME9tricas->nu_usuarioAlterou->ViewAttributes() ?>>
<?php echo $SolicitaE7F5es_de_ME9tricas->nu_usuarioAlterou->ViewValue ?></span>
</td>
		<td<?php echo $SolicitaE7F5es_de_ME9tricas->dh_alteracao->CellAttributes() ?>>
<span<?php echo $SolicitaE7F5es_de_ME9tricas->dh_alteracao->ViewAttributes() ?>>
<?php echo $SolicitaE7F5es_de_ME9tricas->dh_alteracao->ViewValue ?></span>
</td>
		<td<?php echo $SolicitaE7F5es_de_ME9tricas->qt_pfTotal->CellAttributes() ?>>
<span<?php echo $SolicitaE7F5es_de_ME9tricas->qt_pfTotal->ViewAttributes() ?>>
<?php echo $SolicitaE7F5es_de_ME9tricas->qt_pfTotal->ViewValue ?></span>
</td>
		<td<?php echo $SolicitaE7F5es_de_ME9tricas->vr_pfContForn->CellAttributes() ?>>
<span<?php echo $SolicitaE7F5es_de_ME9tricas->vr_pfContForn->ViewAttributes() ?>>
<?php echo $SolicitaE7F5es_de_ME9tricas->vr_pfContForn->ViewValue ?></span>
</td>
		<td<?php echo $SolicitaE7F5es_de_ME9tricas->nu_tpMetrica->CellAttributes() ?>>
<span<?php echo $SolicitaE7F5es_de_ME9tricas->nu_tpMetrica->ViewAttributes() ?>>
<?php echo $SolicitaE7F5es_de_ME9tricas->nu_tpMetrica->ViewValue ?></span>
</td>
		<td<?php echo $SolicitaE7F5es_de_ME9tricas->nu_contagemAnt->CellAttributes() ?>>
<span<?php echo $SolicitaE7F5es_de_ME9tricas->nu_contagemAnt->ViewAttributes() ?>>
<?php echo $SolicitaE7F5es_de_ME9tricas->nu_contagemAnt->ViewValue ?></span>
</td>
	</tr>
<?php
		$SolicitaE7F5es_de_ME9tricas_report->DetailRecordset->MoveNext();
	}
	$SolicitaE7F5es_de_ME9tricas_report->DetailRecordset->Close();

	// Save old group data
	$SolicitaE7F5es_de_ME9tricas_report->ReportGroups[0] = $SolicitaE7F5es_de_ME9tricas->nu_tpSolicitacao->CurrentValue;
	$SolicitaE7F5es_de_ME9tricas_report->ReportGroups[1] = $SolicitaE7F5es_de_ME9tricas->nu_projeto->CurrentValue;

	// Get next record
	$SolicitaE7F5es_de_ME9tricas_report->Recordset->MoveNext();
	if ($SolicitaE7F5es_de_ME9tricas_report->Recordset->EOF) {
		$SolicitaE7F5es_de_ME9tricas_report->RecCnt = 0; // EOF, force all level breaks
	} else {
		$SolicitaE7F5es_de_ME9tricas->nu_tpSolicitacao->setDbValue($SolicitaE7F5es_de_ME9tricas_report->Recordset->fields('nu_tpSolicitacao'));
		$SolicitaE7F5es_de_ME9tricas->nu_projeto->setDbValue($SolicitaE7F5es_de_ME9tricas_report->Recordset->fields('nu_projeto'));
	}
	$SolicitaE7F5es_de_ME9tricas_report->ChkLvlBreak();

	// Show footers
	if ($SolicitaE7F5es_de_ME9tricas_report->LevelBreak[2]) {
		$SolicitaE7F5es_de_ME9tricas->nu_projeto->CurrentValue = $SolicitaE7F5es_de_ME9tricas_report->ReportGroups[1];

		// Render row for view
		$SolicitaE7F5es_de_ME9tricas->RowType = EW_ROWTYPE_VIEW;
		$SolicitaE7F5es_de_ME9tricas->ResetAttrs();
		$SolicitaE7F5es_de_ME9tricas_report->RenderRow();
		$SolicitaE7F5es_de_ME9tricas->nu_projeto->CurrentValue = $SolicitaE7F5es_de_ME9tricas->nu_projeto->DbValue;
?>
<?php
}
	if ($SolicitaE7F5es_de_ME9tricas_report->LevelBreak[1]) {
		$SolicitaE7F5es_de_ME9tricas->nu_tpSolicitacao->CurrentValue = $SolicitaE7F5es_de_ME9tricas_report->ReportGroups[0];

		// Render row for view
		$SolicitaE7F5es_de_ME9tricas->RowType = EW_ROWTYPE_VIEW;
		$SolicitaE7F5es_de_ME9tricas->ResetAttrs();
		$SolicitaE7F5es_de_ME9tricas_report->RenderRow();
		$SolicitaE7F5es_de_ME9tricas->nu_tpSolicitacao->CurrentValue = $SolicitaE7F5es_de_ME9tricas->nu_tpSolicitacao->DbValue;
?>
<?php
}
}

// Close recordset
$SolicitaE7F5es_de_ME9tricas_report->Recordset->Close();
?>
<?php if ($SolicitaE7F5es_de_ME9tricas_report->RecordExists) { ?>
	<tr><td colspan=14>&nbsp;<br></td></tr>
	<tr><td colspan=14 class="ewGrandSummary"><?php echo $Language->Phrase("RptGrandTotal") ?>&nbsp;(<?php echo ew_FormatNumber($SolicitaE7F5es_de_ME9tricas_report->ReportCounts[0], 0) ?>&nbsp;<?php echo $Language->Phrase("RptDtlRec") ?>)</td></tr>
<?php } ?>
<?php if ($SolicitaE7F5es_de_ME9tricas_report->RecordExists) { ?>
	<tr><td colspan=14>&nbsp;<br></td></tr>
<?php } else { ?>
	<tr><td><?php echo $Language->Phrase("NoRecord") ?></td></tr>
<?php } ?>
</table>
</form>
<?php
$SolicitaE7F5es_de_ME9tricas_report->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($SolicitaE7F5es_de_ME9tricas->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$SolicitaE7F5es_de_ME9tricas_report->Page_Terminate();
?>
