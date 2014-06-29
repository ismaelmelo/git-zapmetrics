<?php

// Global variable for table object
$pargerais = NULL;

//
// Table class for pargerais
//
class cpargerais extends cTable {
	var $nu_parametro;
	var $nu_orgBase;
	var $nu_area;
	var $nu_usuarioRespAreaTi;
	var $qt_horasMes;
	var $nu_sistema;
	var $dt_inicioOpSistema;
	var $tx_htmlHomeNaoLogado;
	var $nu_orgMetricas;
	var $nu_areaMetricas;
	var $nu_fornMetricas;
	var $no_areaMetricas;
	var $nu_modeloMetricasPadrao;
	var $nu_areaVincEscritProj;
	var $no_areaEscritProj;
	var $nu_fornecedorAuditoria;
	var $nu_fornPadraoFsw;
	var $nu_contFornPadraoFsw;
	var $nu_itemContFornPadraoFsw;
	var $nu_pesoProbRisco;
	var $nu_pesoImpacRisco;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'pargerais';
		$this->TableName = 'pargerais';
		$this->TableType = 'TABLE';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 1;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// nu_parametro
		$this->nu_parametro = new cField('pargerais', 'pargerais', 'x_nu_parametro', 'nu_parametro', '[nu_parametro]', 'CAST([nu_parametro] AS NVARCHAR)', 3, -1, FALSE, '[nu_parametro]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_parametro->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_parametro'] = &$this->nu_parametro;

		// nu_orgBase
		$this->nu_orgBase = new cField('pargerais', 'pargerais', 'x_nu_orgBase', 'nu_orgBase', '[nu_orgBase]', 'CAST([nu_orgBase] AS NVARCHAR)', 3, -1, FALSE, '[nu_orgBase]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_orgBase->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_orgBase'] = &$this->nu_orgBase;

		// nu_area
		$this->nu_area = new cField('pargerais', 'pargerais', 'x_nu_area', 'nu_area', '[nu_area]', 'CAST([nu_area] AS NVARCHAR)', 3, -1, FALSE, '[nu_area]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_area->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_area'] = &$this->nu_area;

		// nu_usuarioRespAreaTi
		$this->nu_usuarioRespAreaTi = new cField('pargerais', 'pargerais', 'x_nu_usuarioRespAreaTi', 'nu_usuarioRespAreaTi', '[nu_usuarioRespAreaTi]', 'CAST([nu_usuarioRespAreaTi] AS NVARCHAR)', 3, -1, FALSE, '[EV__nu_usuarioRespAreaTi]', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->nu_usuarioRespAreaTi->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_usuarioRespAreaTi'] = &$this->nu_usuarioRespAreaTi;

		// qt_horasMes
		$this->qt_horasMes = new cField('pargerais', 'pargerais', 'x_qt_horasMes', 'qt_horasMes', '[qt_horasMes]', 'CAST([qt_horasMes] AS NVARCHAR)', 3, -1, FALSE, '[qt_horasMes]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->qt_horasMes->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['qt_horasMes'] = &$this->qt_horasMes;

		// nu_sistema
		$this->nu_sistema = new cField('pargerais', 'pargerais', 'x_nu_sistema', 'nu_sistema', '[nu_sistema]', 'CAST([nu_sistema] AS NVARCHAR)', 3, -1, FALSE, '[nu_sistema]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_sistema->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_sistema'] = &$this->nu_sistema;

		// dt_inicioOpSistema
		$this->dt_inicioOpSistema = new cField('pargerais', 'pargerais', 'x_dt_inicioOpSistema', 'dt_inicioOpSistema', '[dt_inicioOpSistema]', '(REPLACE(STR(DAY([dt_inicioOpSistema]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dt_inicioOpSistema]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dt_inicioOpSistema]),4,0))', 135, 7, FALSE, '[dt_inicioOpSistema]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dt_inicioOpSistema->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dt_inicioOpSistema'] = &$this->dt_inicioOpSistema;

		// tx_htmlHomeNaoLogado
		$this->tx_htmlHomeNaoLogado = new cField('pargerais', 'pargerais', 'x_tx_htmlHomeNaoLogado', 'tx_htmlHomeNaoLogado', '[tx_htmlHomeNaoLogado]', '[tx_htmlHomeNaoLogado]', 201, -1, FALSE, '[tx_htmlHomeNaoLogado]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->tx_htmlHomeNaoLogado->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['tx_htmlHomeNaoLogado'] = &$this->tx_htmlHomeNaoLogado;

		// nu_orgMetricas
		$this->nu_orgMetricas = new cField('pargerais', 'pargerais', 'x_nu_orgMetricas', 'nu_orgMetricas', '[nu_orgMetricas]', 'CAST([nu_orgMetricas] AS NVARCHAR)', 3, -1, FALSE, '[nu_orgMetricas]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_orgMetricas->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_orgMetricas'] = &$this->nu_orgMetricas;

		// nu_areaMetricas
		$this->nu_areaMetricas = new cField('pargerais', 'pargerais', 'x_nu_areaMetricas', 'nu_areaMetricas', '[nu_areaMetricas]', 'CAST([nu_areaMetricas] AS NVARCHAR)', 3, -1, FALSE, '[EV__nu_areaMetricas]', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->nu_areaMetricas->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_areaMetricas'] = &$this->nu_areaMetricas;

		// nu_fornMetricas
		$this->nu_fornMetricas = new cField('pargerais', 'pargerais', 'x_nu_fornMetricas', 'nu_fornMetricas', '[nu_fornMetricas]', 'CAST([nu_fornMetricas] AS NVARCHAR)', 3, -1, FALSE, '[nu_fornMetricas]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_fornMetricas->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_fornMetricas'] = &$this->nu_fornMetricas;

		// no_areaMetricas
		$this->no_areaMetricas = new cField('pargerais', 'pargerais', 'x_no_areaMetricas', 'no_areaMetricas', '[no_areaMetricas]', '[no_areaMetricas]', 200, -1, FALSE, '[no_areaMetricas]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_areaMetricas'] = &$this->no_areaMetricas;

		// nu_modeloMetricasPadrao
		$this->nu_modeloMetricasPadrao = new cField('pargerais', 'pargerais', 'x_nu_modeloMetricasPadrao', 'nu_modeloMetricasPadrao', '[nu_modeloMetricasPadrao]', 'CAST([nu_modeloMetricasPadrao] AS NVARCHAR)', 3, -1, FALSE, '[EV__nu_modeloMetricasPadrao]', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->nu_modeloMetricasPadrao->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_modeloMetricasPadrao'] = &$this->nu_modeloMetricasPadrao;

		// nu_areaVincEscritProj
		$this->nu_areaVincEscritProj = new cField('pargerais', 'pargerais', 'x_nu_areaVincEscritProj', 'nu_areaVincEscritProj', '[nu_areaVincEscritProj]', 'CAST([nu_areaVincEscritProj] AS NVARCHAR)', 3, -1, FALSE, '[nu_areaVincEscritProj]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_areaVincEscritProj->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_areaVincEscritProj'] = &$this->nu_areaVincEscritProj;

		// no_areaEscritProj
		$this->no_areaEscritProj = new cField('pargerais', 'pargerais', 'x_no_areaEscritProj', 'no_areaEscritProj', '[no_areaEscritProj]', '[no_areaEscritProj]', 200, -1, FALSE, '[no_areaEscritProj]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_areaEscritProj'] = &$this->no_areaEscritProj;

		// nu_fornecedorAuditoria
		$this->nu_fornecedorAuditoria = new cField('pargerais', 'pargerais', 'x_nu_fornecedorAuditoria', 'nu_fornecedorAuditoria', '[nu_fornecedorAuditoria]', 'CAST([nu_fornecedorAuditoria] AS NVARCHAR)', 3, -1, FALSE, '[nu_fornecedorAuditoria]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_fornecedorAuditoria->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_fornecedorAuditoria'] = &$this->nu_fornecedorAuditoria;

		// nu_fornPadraoFsw
		$this->nu_fornPadraoFsw = new cField('pargerais', 'pargerais', 'x_nu_fornPadraoFsw', 'nu_fornPadraoFsw', '[nu_fornPadraoFsw]', 'CAST([nu_fornPadraoFsw] AS NVARCHAR)', 3, -1, FALSE, '[EV__nu_fornPadraoFsw]', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->nu_fornPadraoFsw->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_fornPadraoFsw'] = &$this->nu_fornPadraoFsw;

		// nu_contFornPadraoFsw
		$this->nu_contFornPadraoFsw = new cField('pargerais', 'pargerais', 'x_nu_contFornPadraoFsw', 'nu_contFornPadraoFsw', '[nu_contFornPadraoFsw]', 'CAST([nu_contFornPadraoFsw] AS NVARCHAR)', 3, -1, FALSE, '[EV__nu_contFornPadraoFsw]', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->nu_contFornPadraoFsw->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_contFornPadraoFsw'] = &$this->nu_contFornPadraoFsw;

		// nu_itemContFornPadraoFsw
		$this->nu_itemContFornPadraoFsw = new cField('pargerais', 'pargerais', 'x_nu_itemContFornPadraoFsw', 'nu_itemContFornPadraoFsw', '[nu_itemContFornPadraoFsw]', 'CAST([nu_itemContFornPadraoFsw] AS NVARCHAR)', 3, -1, FALSE, '[nu_itemContFornPadraoFsw]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_itemContFornPadraoFsw->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_itemContFornPadraoFsw'] = &$this->nu_itemContFornPadraoFsw;

		// nu_pesoProbRisco
		$this->nu_pesoProbRisco = new cField('pargerais', 'pargerais', 'x_nu_pesoProbRisco', 'nu_pesoProbRisco', '[nu_pesoProbRisco]', 'CAST([nu_pesoProbRisco] AS NVARCHAR)', 3, -1, FALSE, '[nu_pesoProbRisco]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_pesoProbRisco->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_pesoProbRisco'] = &$this->nu_pesoProbRisco;

		// nu_pesoImpacRisco
		$this->nu_pesoImpacRisco = new cField('pargerais', 'pargerais', 'x_nu_pesoImpacRisco', 'nu_pesoImpacRisco', '[nu_pesoImpacRisco]', 'CAST([nu_pesoImpacRisco] AS NVARCHAR)', 3, -1, FALSE, '[nu_pesoImpacRisco]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_pesoImpacRisco->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_pesoImpacRisco'] = &$this->nu_pesoImpacRisco;
	}

	// Multiple column sort
	function UpdateSort(&$ofld, $ctrl) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			if ($ctrl) {
				$sOrderBy = $this->getSessionOrderBy();
				if (strpos($sOrderBy, $sSortField . " " . $sLastSort) !== FALSE) {
					$sOrderBy = str_replace($sSortField . " " . $sLastSort, $sSortField . " " . $sThisSort, $sOrderBy);
				} else {
					if ($sOrderBy <> "") $sOrderBy .= ", ";
					$sOrderBy .= $sSortField . " " . $sThisSort;
				}
				$this->setSessionOrderBy($sOrderBy); // Save to Session
			} else {
				$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
			}
			$sSortFieldList = ($ofld->FldVirtualExpression <> "") ? $ofld->FldVirtualExpression : $sSortField;
			if ($ctrl) {
				$sOrderByList = $this->getSessionOrderByList();
				if (strpos($sOrderByList, $sSortFieldList . " " . $sLastSort) !== FALSE) {
					$sOrderByList = str_replace($sSortFieldList . " " . $sLastSort, $sSortFieldList . " " . $sThisSort, $sOrderByList);
				} else {
					if ($sOrderByList <> "") $sOrderByList .= ", ";
					$sOrderByList .= $sSortFieldList . " " . $sThisSort;
				}
				$this->setSessionOrderByList($sOrderByList); // Save to Session
			} else {
				$this->setSessionOrderByList($sSortFieldList . " " . $sThisSort); // Save to Session
			}
		} else {
			if (!$ctrl) $ofld->setSort("");
		}
	}

	// Session ORDER BY for List page
	function getSessionOrderByList() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST];
	}

	function setSessionOrderByList($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST] = $v;
	}

	// Current master table name
	function getCurrentMasterTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE];
	}

	function setCurrentMasterTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE] = $v;
	}

	// Session master WHERE clause
	function GetMasterFilter() {

		// Master filter
		$sMasterFilter = "";
		if ($this->getCurrentMasterTable() == "organizacao") {
			if ($this->nu_orgBase->getSessionValue() <> "")
				$sMasterFilter .= "[nu_organizacao]=" . ew_QuotedValue($this->nu_orgBase->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "organizacao") {
			if ($this->nu_orgBase->getSessionValue() <> "")
				$sDetailFilter .= "[nu_orgBase]=" . ew_QuotedValue($this->nu_orgBase->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_organizacao() {
		return "[nu_organizacao]=@nu_organizacao@";
	}

	// Detail filter
	function SqlDetailFilter_organizacao() {
		return "[nu_orgBase]=@nu_orgBase@";
	}

	// Table level SQL
	function SqlFrom() { // From
		return "[dbo].[pargerais]";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlSelectList() { // Select for List page
		return "SELECT * FROM (" .
			"SELECT *, (SELECT TOP 1 [no_usuario] FROM [usuario] [EW_TMP_LOOKUPTABLE] WHERE [EW_TMP_LOOKUPTABLE].[nu_usuario] = [pargerais].[nu_usuarioRespAreaTi]) AS [EV__nu_usuarioRespAreaTi], (SELECT TOP 1 [no_area] FROM [area] [EW_TMP_LOOKUPTABLE] WHERE [EW_TMP_LOOKUPTABLE].[nu_area] = [pargerais].[nu_areaMetricas]) AS [EV__nu_areaMetricas], (SELECT TOP 1 [no_tpMetrica] FROM [tpmetrica] [EW_TMP_LOOKUPTABLE] WHERE [EW_TMP_LOOKUPTABLE].[nu_tpMetrica] = [pargerais].[nu_modeloMetricasPadrao]) AS [EV__nu_modeloMetricasPadrao], (SELECT TOP 1 [no_fornecedor] FROM [fornecedor] [EW_TMP_LOOKUPTABLE] WHERE [EW_TMP_LOOKUPTABLE].[nu_fornecedor] = [pargerais].[nu_fornPadraoFsw]) AS [EV__nu_fornPadraoFsw], (SELECT TOP 1 [no_contrato] FROM [contrato] [EW_TMP_LOOKUPTABLE] WHERE [EW_TMP_LOOKUPTABLE].[nu_contrato] = [pargerais].[nu_contFornPadraoFsw]) AS [EV__nu_contFornPadraoFsw] FROM [dbo].[pargerais]" .
			") [EW_TMP_TABLE]";
	}

	function SqlWhere() { // Where
		$sWhere = "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlGroupBy() { // Group By
		return "";
	}

	function SqlHaving() { // Having
		return "";
	}

	function SqlOrderBy() { // Order By
		return "";
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

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		if ($this->UseVirtualFields()) {
			$sSort = $this->getSessionOrderByList();
			return ew_BuildSelectSql($this->SqlSelectList(), $this->SqlWhere(), $this->SqlGroupBy(), 
				$this->SqlHaving(), $this->SqlOrderBy(), $sFilter, $sSort);
		} else {
			$sSort = $this->getSessionOrderBy();
			return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(), $this->SqlGroupBy(),
				$this->SqlHaving(), $this->SqlOrderBy(), $sFilter, $sSort);
		}
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = ($this->UseVirtualFields()) ? $this->getSessionOrderByList() : $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->SqlOrderBy(), "", $sSort);
	}

	// Check if virtual fields is used in SQL
	function UseVirtualFields() {
		$sWhere = $this->getSessionWhere();
		$sOrderBy = $this->getSessionOrderByList();
		if ($sWhere <> "")
			$sWhere = " " . str_replace(array("(",")"), array("",""), $sWhere) . " ";
		if ($sOrderBy <> "")
			$sOrderBy = " " . str_replace(array("(",")"), array("",""), $sOrderBy) . " ";
		if ($this->nu_usuarioRespAreaTi->AdvancedSearch->SearchValue <> "" ||
			$this->nu_usuarioRespAreaTi->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->nu_usuarioRespAreaTi->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->nu_usuarioRespAreaTi->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->nu_areaMetricas->AdvancedSearch->SearchValue <> "" ||
			$this->nu_areaMetricas->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->nu_areaMetricas->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->nu_areaMetricas->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->nu_modeloMetricasPadrao->AdvancedSearch->SearchValue <> "" ||
			$this->nu_modeloMetricasPadrao->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->nu_modeloMetricasPadrao->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->nu_modeloMetricasPadrao->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->nu_fornPadraoFsw->AdvancedSearch->SearchValue <> "" ||
			$this->nu_fornPadraoFsw->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->nu_fornPadraoFsw->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->nu_fornPadraoFsw->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->nu_contFornPadraoFsw->AdvancedSearch->SearchValue <> "" ||
			$this->nu_contFornPadraoFsw->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->nu_contFornPadraoFsw->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->nu_contFornPadraoFsw->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		return FALSE;
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		global $conn;
		$cnt = -1;
		if ($this->TableType == 'TABLE' || $this->TableType == 'VIEW') {
			$sSql = "SELECT COUNT(*) FROM" . substr($sSql, 13);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		global $conn;
		$origFilter = $this->CurrentFilter;
		$this->Recordset_Selecting($this->CurrentFilter);
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Update Table
	var $UpdateTable = "[dbo].[pargerais]";

	// INSERT statement
	function InsertSQL(&$rs) {
		global $conn;
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			if (in_array($this->fields[$name]->FldType, array(130, 202, 203)) && !is_null($value))
				$values .= 'N';
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		global $conn;
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "") {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			if (in_array($this->fields[$name]->FldType, array(130, 202, 203)) && !is_null($value))
				$sql .= 'N';
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL) {
		global $conn;
		return $conn->Execute($this->UpdateSQL($rs, $where));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "") {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if ($rs) {
			if (array_key_exists('nu_parametro', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_parametro') . '=' . ew_QuotedValue($rs['nu_parametro'], $this->nu_parametro->FldDataType));
		}
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "") {
		global $conn;
		return $conn->Execute($this->DeleteSQL($rs, $where));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "[nu_parametro] = @nu_parametro@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nu_parametro->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_parametro@", ew_AdjustSql($this->nu_parametro->CurrentValue), $sKeyFilter); // Replace key value
		return $sKeyFilter;
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
			return "pargeraislist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "pargeraislist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("pargeraisview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("pargeraisview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "pargeraisadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("pargeraisedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("pargeraisadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("pargeraisdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nu_parametro->CurrentValue)) {
			$sUrl .= "nu_parametro=" . urlencode($this->nu_parametro->CurrentValue);
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
			$arKeys[] = @$_GET["nu_parametro"]; // nu_parametro

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
			$this->nu_parametro->CurrentValue = $key;
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

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->nu_parametro->setDbValue($rs->fields('nu_parametro'));
		$this->nu_orgBase->setDbValue($rs->fields('nu_orgBase'));
		$this->nu_area->setDbValue($rs->fields('nu_area'));
		$this->nu_usuarioRespAreaTi->setDbValue($rs->fields('nu_usuarioRespAreaTi'));
		$this->qt_horasMes->setDbValue($rs->fields('qt_horasMes'));
		$this->nu_sistema->setDbValue($rs->fields('nu_sistema'));
		$this->dt_inicioOpSistema->setDbValue($rs->fields('dt_inicioOpSistema'));
		$this->tx_htmlHomeNaoLogado->setDbValue($rs->fields('tx_htmlHomeNaoLogado'));
		$this->nu_orgMetricas->setDbValue($rs->fields('nu_orgMetricas'));
		$this->nu_areaMetricas->setDbValue($rs->fields('nu_areaMetricas'));
		$this->nu_fornMetricas->setDbValue($rs->fields('nu_fornMetricas'));
		$this->no_areaMetricas->setDbValue($rs->fields('no_areaMetricas'));
		$this->nu_modeloMetricasPadrao->setDbValue($rs->fields('nu_modeloMetricasPadrao'));
		$this->nu_areaVincEscritProj->setDbValue($rs->fields('nu_areaVincEscritProj'));
		$this->no_areaEscritProj->setDbValue($rs->fields('no_areaEscritProj'));
		$this->nu_fornecedorAuditoria->setDbValue($rs->fields('nu_fornecedorAuditoria'));
		$this->nu_fornPadraoFsw->setDbValue($rs->fields('nu_fornPadraoFsw'));
		$this->nu_contFornPadraoFsw->setDbValue($rs->fields('nu_contFornPadraoFsw'));
		$this->nu_itemContFornPadraoFsw->setDbValue($rs->fields('nu_itemContFornPadraoFsw'));
		$this->nu_pesoProbRisco->setDbValue($rs->fields('nu_pesoProbRisco'));
		$this->nu_pesoImpacRisco->setDbValue($rs->fields('nu_pesoImpacRisco'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_parametro

		$this->nu_parametro->CellCssStyle = "white-space: nowrap;";

		// nu_orgBase
		// nu_area
		// nu_usuarioRespAreaTi
		// qt_horasMes
		// nu_sistema
		// dt_inicioOpSistema
		// tx_htmlHomeNaoLogado
		// nu_orgMetricas
		// nu_areaMetricas
		// nu_fornMetricas
		// no_areaMetricas
		// nu_modeloMetricasPadrao
		// nu_areaVincEscritProj
		// no_areaEscritProj
		// nu_fornecedorAuditoria
		// nu_fornPadraoFsw
		// nu_contFornPadraoFsw
		// nu_itemContFornPadraoFsw
		// nu_pesoProbRisco
		// nu_pesoImpacRisco
		// nu_parametro

		$this->nu_parametro->ViewValue = $this->nu_parametro->CurrentValue;
		$this->nu_parametro->ViewCustomAttributes = "";

		// nu_orgBase
		if (strval($this->nu_orgBase->CurrentValue) <> "") {
			$sFilterWrk = "[nu_organizacao]" . ew_SearchString("=", $this->nu_orgBase->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_organizacao], [no_organizacao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[organizacao]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_orgBase, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_organizacao] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_orgBase->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_orgBase->ViewValue = $this->nu_orgBase->CurrentValue;
			}
		} else {
			$this->nu_orgBase->ViewValue = NULL;
		}
		$this->nu_orgBase->ViewCustomAttributes = "";

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

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_area, $sWhereWrk);
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

		// nu_usuarioRespAreaTi
		if ($this->nu_usuarioRespAreaTi->VirtualValue <> "") {
			$this->nu_usuarioRespAreaTi->ViewValue = $this->nu_usuarioRespAreaTi->VirtualValue;
		} else {
		if (strval($this->nu_usuarioRespAreaTi->CurrentValue) <> "") {
			$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioRespAreaTi->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_usuarioRespAreaTi, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_usuario] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_usuarioRespAreaTi->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_usuarioRespAreaTi->ViewValue = $this->nu_usuarioRespAreaTi->CurrentValue;
			}
		} else {
			$this->nu_usuarioRespAreaTi->ViewValue = NULL;
		}
		}
		$this->nu_usuarioRespAreaTi->ViewCustomAttributes = "";

		// qt_horasMes
		$this->qt_horasMes->ViewValue = $this->qt_horasMes->CurrentValue;
		$this->qt_horasMes->ViewCustomAttributes = "";

		// nu_sistema
		if (strval($this->nu_sistema->CurrentValue) <> "") {
			$sFilterWrk = "[nu_sistema]" . ew_SearchString("=", $this->nu_sistema->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_sistema], [co_alternativo] AS [DispFld], [no_sistema] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[sistema]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_sistema, $sWhereWrk);
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

		// dt_inicioOpSistema
		$this->dt_inicioOpSistema->ViewValue = $this->dt_inicioOpSistema->CurrentValue;
		$this->dt_inicioOpSistema->ViewValue = ew_FormatDateTime($this->dt_inicioOpSistema->ViewValue, 7);
		$this->dt_inicioOpSistema->ViewCustomAttributes = "";

		// tx_htmlHomeNaoLogado
		$this->tx_htmlHomeNaoLogado->ViewValue = $this->tx_htmlHomeNaoLogado->CurrentValue;
		$this->tx_htmlHomeNaoLogado->ViewCustomAttributes = "";

		// nu_orgMetricas
		if (strval($this->nu_orgMetricas->CurrentValue) <> "") {
			$sFilterWrk = "[nu_organizacao]" . ew_SearchString("=", $this->nu_orgMetricas->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_organizacao], [no_organizacao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[organizacao]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_orgMetricas, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_organizacao] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_orgMetricas->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_orgMetricas->ViewValue = $this->nu_orgMetricas->CurrentValue;
			}
		} else {
			$this->nu_orgMetricas->ViewValue = NULL;
		}
		$this->nu_orgMetricas->ViewCustomAttributes = "";

		// nu_areaMetricas
		if ($this->nu_areaMetricas->VirtualValue <> "") {
			$this->nu_areaMetricas->ViewValue = $this->nu_areaMetricas->VirtualValue;
		} else {
		if (strval($this->nu_areaMetricas->CurrentValue) <> "") {
			$sFilterWrk = "[nu_area]" . ew_SearchString("=", $this->nu_areaMetricas->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_area], [no_area] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[area]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_areaMetricas, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_area] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_areaMetricas->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_areaMetricas->ViewValue = $this->nu_areaMetricas->CurrentValue;
			}
		} else {
			$this->nu_areaMetricas->ViewValue = NULL;
		}
		}
		$this->nu_areaMetricas->ViewCustomAttributes = "";

		// nu_fornMetricas
		if (strval($this->nu_fornMetricas->CurrentValue) <> "") {
			$sFilterWrk = "[nu_fornecedor]" . ew_SearchString("=", $this->nu_fornMetricas->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_fornecedor], [no_fornecedor] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[fornecedor]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_fornMetricas, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_fornecedor] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_fornMetricas->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_fornMetricas->ViewValue = $this->nu_fornMetricas->CurrentValue;
			}
		} else {
			$this->nu_fornMetricas->ViewValue = NULL;
		}
		$this->nu_fornMetricas->ViewCustomAttributes = "";

		// no_areaMetricas
		$this->no_areaMetricas->ViewValue = $this->no_areaMetricas->CurrentValue;
		$this->no_areaMetricas->ViewCustomAttributes = "";

		// nu_modeloMetricasPadrao
		if ($this->nu_modeloMetricasPadrao->VirtualValue <> "") {
			$this->nu_modeloMetricasPadrao->ViewValue = $this->nu_modeloMetricasPadrao->VirtualValue;
		} else {
		if (strval($this->nu_modeloMetricasPadrao->CurrentValue) <> "") {
			$sFilterWrk = "[nu_tpMetrica]" . ew_SearchString("=", $this->nu_modeloMetricasPadrao->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_tpMetrica], [no_tpMetrica] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpmetrica]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_modeloMetricasPadrao, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_tpMetrica] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_modeloMetricasPadrao->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_modeloMetricasPadrao->ViewValue = $this->nu_modeloMetricasPadrao->CurrentValue;
			}
		} else {
			$this->nu_modeloMetricasPadrao->ViewValue = NULL;
		}
		}
		$this->nu_modeloMetricasPadrao->ViewCustomAttributes = "";

		// nu_areaVincEscritProj
		if (strval($this->nu_areaVincEscritProj->CurrentValue) <> "") {
			$sFilterWrk = "[nu_area]" . ew_SearchString("=", $this->nu_areaVincEscritProj->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_area], [no_area] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[area]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_areaVincEscritProj, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_area] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_areaVincEscritProj->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_areaVincEscritProj->ViewValue = $this->nu_areaVincEscritProj->CurrentValue;
			}
		} else {
			$this->nu_areaVincEscritProj->ViewValue = NULL;
		}
		$this->nu_areaVincEscritProj->ViewCustomAttributes = "";

		// no_areaEscritProj
		$this->no_areaEscritProj->ViewValue = $this->no_areaEscritProj->CurrentValue;
		$this->no_areaEscritProj->ViewCustomAttributes = "";

		// nu_fornecedorAuditoria
		if (strval($this->nu_fornecedorAuditoria->CurrentValue) <> "") {
			$sFilterWrk = "[nu_fornecedor]" . ew_SearchString("=", $this->nu_fornecedorAuditoria->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_fornecedor], [no_fornecedor] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[fornecedor]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_fornecedorAuditoria, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_fornecedor] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_fornecedorAuditoria->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_fornecedorAuditoria->ViewValue = $this->nu_fornecedorAuditoria->CurrentValue;
			}
		} else {
			$this->nu_fornecedorAuditoria->ViewValue = NULL;
		}
		$this->nu_fornecedorAuditoria->ViewCustomAttributes = "";

		// nu_fornPadraoFsw
		if ($this->nu_fornPadraoFsw->VirtualValue <> "") {
			$this->nu_fornPadraoFsw->ViewValue = $this->nu_fornPadraoFsw->VirtualValue;
		} else {
		if (strval($this->nu_fornPadraoFsw->CurrentValue) <> "") {
			$sFilterWrk = "[nu_fornecedor]" . ew_SearchString("=", $this->nu_fornPadraoFsw->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_fornecedor], [no_fornecedor] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[fornecedor]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_fornPadraoFsw, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_fornPadraoFsw->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_fornPadraoFsw->ViewValue = $this->nu_fornPadraoFsw->CurrentValue;
			}
		} else {
			$this->nu_fornPadraoFsw->ViewValue = NULL;
		}
		}
		$this->nu_fornPadraoFsw->ViewCustomAttributes = "";

		// nu_contFornPadraoFsw
		if ($this->nu_contFornPadraoFsw->VirtualValue <> "") {
			$this->nu_contFornPadraoFsw->ViewValue = $this->nu_contFornPadraoFsw->VirtualValue;
		} else {
		if (strval($this->nu_contFornPadraoFsw->CurrentValue) <> "") {
			$sFilterWrk = "[nu_contrato]" . ew_SearchString("=", $this->nu_contFornPadraoFsw->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_contrato], [no_contrato] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[contrato]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_contFornPadraoFsw, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_contrato] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_contFornPadraoFsw->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_contFornPadraoFsw->ViewValue = $this->nu_contFornPadraoFsw->CurrentValue;
			}
		} else {
			$this->nu_contFornPadraoFsw->ViewValue = NULL;
		}
		}
		$this->nu_contFornPadraoFsw->ViewCustomAttributes = "";

		// nu_itemContFornPadraoFsw
		if (strval($this->nu_itemContFornPadraoFsw->CurrentValue) <> "") {
			$sFilterWrk = "[nu_itemContratado]" . ew_SearchString("=", $this->nu_itemContFornPadraoFsw->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_itemContratado], [no_itemContratado] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[item_contratado]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_itemContFornPadraoFsw, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_itemContratado] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_itemContFornPadraoFsw->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_itemContFornPadraoFsw->ViewValue = $this->nu_itemContFornPadraoFsw->CurrentValue;
			}
		} else {
			$this->nu_itemContFornPadraoFsw->ViewValue = NULL;
		}
		$this->nu_itemContFornPadraoFsw->ViewCustomAttributes = "";

		// nu_pesoProbRisco
		$this->nu_pesoProbRisco->ViewValue = $this->nu_pesoProbRisco->CurrentValue;
		$this->nu_pesoProbRisco->ViewCustomAttributes = "";

		// nu_pesoImpacRisco
		$this->nu_pesoImpacRisco->ViewValue = $this->nu_pesoImpacRisco->CurrentValue;
		$this->nu_pesoImpacRisco->ViewCustomAttributes = "";

		// nu_parametro
		$this->nu_parametro->LinkCustomAttributes = "";
		$this->nu_parametro->HrefValue = "";
		$this->nu_parametro->TooltipValue = "";

		// nu_orgBase
		$this->nu_orgBase->LinkCustomAttributes = "";
		$this->nu_orgBase->HrefValue = "";
		$this->nu_orgBase->TooltipValue = "";

		// nu_area
		$this->nu_area->LinkCustomAttributes = "";
		$this->nu_area->HrefValue = "";
		$this->nu_area->TooltipValue = "";

		// nu_usuarioRespAreaTi
		$this->nu_usuarioRespAreaTi->LinkCustomAttributes = "";
		$this->nu_usuarioRespAreaTi->HrefValue = "";
		$this->nu_usuarioRespAreaTi->TooltipValue = "";

		// qt_horasMes
		$this->qt_horasMes->LinkCustomAttributes = "";
		$this->qt_horasMes->HrefValue = "";
		$this->qt_horasMes->TooltipValue = "";

		// nu_sistema
		$this->nu_sistema->LinkCustomAttributes = "";
		$this->nu_sistema->HrefValue = "";
		$this->nu_sistema->TooltipValue = "";

		// dt_inicioOpSistema
		$this->dt_inicioOpSistema->LinkCustomAttributes = "";
		$this->dt_inicioOpSistema->HrefValue = "";
		$this->dt_inicioOpSistema->TooltipValue = "";

		// tx_htmlHomeNaoLogado
		$this->tx_htmlHomeNaoLogado->LinkCustomAttributes = "";
		$this->tx_htmlHomeNaoLogado->HrefValue = "";
		$this->tx_htmlHomeNaoLogado->TooltipValue = "";

		// nu_orgMetricas
		$this->nu_orgMetricas->LinkCustomAttributes = "";
		$this->nu_orgMetricas->HrefValue = "";
		$this->nu_orgMetricas->TooltipValue = "";

		// nu_areaMetricas
		$this->nu_areaMetricas->LinkCustomAttributes = "";
		$this->nu_areaMetricas->HrefValue = "";
		$this->nu_areaMetricas->TooltipValue = "";

		// nu_fornMetricas
		$this->nu_fornMetricas->LinkCustomAttributes = "";
		$this->nu_fornMetricas->HrefValue = "";
		$this->nu_fornMetricas->TooltipValue = "";

		// no_areaMetricas
		$this->no_areaMetricas->LinkCustomAttributes = "";
		$this->no_areaMetricas->HrefValue = "";
		$this->no_areaMetricas->TooltipValue = "";

		// nu_modeloMetricasPadrao
		$this->nu_modeloMetricasPadrao->LinkCustomAttributes = "";
		$this->nu_modeloMetricasPadrao->HrefValue = "";
		$this->nu_modeloMetricasPadrao->TooltipValue = "";

		// nu_areaVincEscritProj
		$this->nu_areaVincEscritProj->LinkCustomAttributes = "";
		$this->nu_areaVincEscritProj->HrefValue = "";
		$this->nu_areaVincEscritProj->TooltipValue = "";

		// no_areaEscritProj
		$this->no_areaEscritProj->LinkCustomAttributes = "";
		$this->no_areaEscritProj->HrefValue = "";
		$this->no_areaEscritProj->TooltipValue = "";

		// nu_fornecedorAuditoria
		$this->nu_fornecedorAuditoria->LinkCustomAttributes = "";
		$this->nu_fornecedorAuditoria->HrefValue = "";
		$this->nu_fornecedorAuditoria->TooltipValue = "";

		// nu_fornPadraoFsw
		$this->nu_fornPadraoFsw->LinkCustomAttributes = "";
		$this->nu_fornPadraoFsw->HrefValue = "";
		$this->nu_fornPadraoFsw->TooltipValue = "";

		// nu_contFornPadraoFsw
		$this->nu_contFornPadraoFsw->LinkCustomAttributes = "";
		$this->nu_contFornPadraoFsw->HrefValue = "";
		$this->nu_contFornPadraoFsw->TooltipValue = "";

		// nu_itemContFornPadraoFsw
		$this->nu_itemContFornPadraoFsw->LinkCustomAttributes = "";
		$this->nu_itemContFornPadraoFsw->HrefValue = "";
		$this->nu_itemContFornPadraoFsw->TooltipValue = "";

		// nu_pesoProbRisco
		$this->nu_pesoProbRisco->LinkCustomAttributes = "";
		$this->nu_pesoProbRisco->HrefValue = "";
		$this->nu_pesoProbRisco->TooltipValue = "";

		// nu_pesoImpacRisco
		$this->nu_pesoImpacRisco->LinkCustomAttributes = "";
		$this->nu_pesoImpacRisco->HrefValue = "";
		$this->nu_pesoImpacRisco->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
	}

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;

		// Write header
		$Doc->ExportTableHeader();
		if ($Doc->Horizontal) { // Horizontal format, write header
			$Doc->BeginExportRow();
			if ($ExportPageType == "view") {
				if ($this->nu_orgBase->Exportable) $Doc->ExportCaption($this->nu_orgBase);
				if ($this->nu_area->Exportable) $Doc->ExportCaption($this->nu_area);
				if ($this->nu_usuarioRespAreaTi->Exportable) $Doc->ExportCaption($this->nu_usuarioRespAreaTi);
				if ($this->qt_horasMes->Exportable) $Doc->ExportCaption($this->qt_horasMes);
				if ($this->nu_sistema->Exportable) $Doc->ExportCaption($this->nu_sistema);
				if ($this->dt_inicioOpSistema->Exportable) $Doc->ExportCaption($this->dt_inicioOpSistema);
				if ($this->tx_htmlHomeNaoLogado->Exportable) $Doc->ExportCaption($this->tx_htmlHomeNaoLogado);
				if ($this->nu_orgMetricas->Exportable) $Doc->ExportCaption($this->nu_orgMetricas);
				if ($this->nu_areaMetricas->Exportable) $Doc->ExportCaption($this->nu_areaMetricas);
				if ($this->nu_fornMetricas->Exportable) $Doc->ExportCaption($this->nu_fornMetricas);
				if ($this->no_areaMetricas->Exportable) $Doc->ExportCaption($this->no_areaMetricas);
				if ($this->nu_modeloMetricasPadrao->Exportable) $Doc->ExportCaption($this->nu_modeloMetricasPadrao);
				if ($this->nu_areaVincEscritProj->Exportable) $Doc->ExportCaption($this->nu_areaVincEscritProj);
				if ($this->no_areaEscritProj->Exportable) $Doc->ExportCaption($this->no_areaEscritProj);
				if ($this->nu_fornecedorAuditoria->Exportable) $Doc->ExportCaption($this->nu_fornecedorAuditoria);
				if ($this->nu_fornPadraoFsw->Exportable) $Doc->ExportCaption($this->nu_fornPadraoFsw);
				if ($this->nu_contFornPadraoFsw->Exportable) $Doc->ExportCaption($this->nu_contFornPadraoFsw);
				if ($this->nu_itemContFornPadraoFsw->Exportable) $Doc->ExportCaption($this->nu_itemContFornPadraoFsw);
				if ($this->nu_pesoProbRisco->Exportable) $Doc->ExportCaption($this->nu_pesoProbRisco);
				if ($this->nu_pesoImpacRisco->Exportable) $Doc->ExportCaption($this->nu_pesoImpacRisco);
			} else {
				if ($this->nu_orgBase->Exportable) $Doc->ExportCaption($this->nu_orgBase);
				if ($this->nu_area->Exportable) $Doc->ExportCaption($this->nu_area);
				if ($this->nu_usuarioRespAreaTi->Exportable) $Doc->ExportCaption($this->nu_usuarioRespAreaTi);
				if ($this->qt_horasMes->Exportable) $Doc->ExportCaption($this->qt_horasMes);
				if ($this->nu_sistema->Exportable) $Doc->ExportCaption($this->nu_sistema);
				if ($this->dt_inicioOpSistema->Exportable) $Doc->ExportCaption($this->dt_inicioOpSistema);
				if ($this->tx_htmlHomeNaoLogado->Exportable) $Doc->ExportCaption($this->tx_htmlHomeNaoLogado);
				if ($this->nu_orgMetricas->Exportable) $Doc->ExportCaption($this->nu_orgMetricas);
				if ($this->nu_areaMetricas->Exportable) $Doc->ExportCaption($this->nu_areaMetricas);
				if ($this->nu_fornMetricas->Exportable) $Doc->ExportCaption($this->nu_fornMetricas);
				if ($this->no_areaMetricas->Exportable) $Doc->ExportCaption($this->no_areaMetricas);
				if ($this->nu_modeloMetricasPadrao->Exportable) $Doc->ExportCaption($this->nu_modeloMetricasPadrao);
				if ($this->nu_areaVincEscritProj->Exportable) $Doc->ExportCaption($this->nu_areaVincEscritProj);
				if ($this->no_areaEscritProj->Exportable) $Doc->ExportCaption($this->no_areaEscritProj);
				if ($this->nu_fornecedorAuditoria->Exportable) $Doc->ExportCaption($this->nu_fornecedorAuditoria);
				if ($this->nu_fornPadraoFsw->Exportable) $Doc->ExportCaption($this->nu_fornPadraoFsw);
				if ($this->nu_contFornPadraoFsw->Exportable) $Doc->ExportCaption($this->nu_contFornPadraoFsw);
				if ($this->nu_itemContFornPadraoFsw->Exportable) $Doc->ExportCaption($this->nu_itemContFornPadraoFsw);
				if ($this->nu_pesoProbRisco->Exportable) $Doc->ExportCaption($this->nu_pesoProbRisco);
				if ($this->nu_pesoImpacRisco->Exportable) $Doc->ExportCaption($this->nu_pesoImpacRisco);
			}
			$Doc->EndExportRow();
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
				if ($ExportPageType == "view") {
					if ($this->nu_orgBase->Exportable) $Doc->ExportField($this->nu_orgBase);
					if ($this->nu_area->Exportable) $Doc->ExportField($this->nu_area);
					if ($this->nu_usuarioRespAreaTi->Exportable) $Doc->ExportField($this->nu_usuarioRespAreaTi);
					if ($this->qt_horasMes->Exportable) $Doc->ExportField($this->qt_horasMes);
					if ($this->nu_sistema->Exportable) $Doc->ExportField($this->nu_sistema);
					if ($this->dt_inicioOpSistema->Exportable) $Doc->ExportField($this->dt_inicioOpSistema);
					if ($this->tx_htmlHomeNaoLogado->Exportable) $Doc->ExportField($this->tx_htmlHomeNaoLogado);
					if ($this->nu_orgMetricas->Exportable) $Doc->ExportField($this->nu_orgMetricas);
					if ($this->nu_areaMetricas->Exportable) $Doc->ExportField($this->nu_areaMetricas);
					if ($this->nu_fornMetricas->Exportable) $Doc->ExportField($this->nu_fornMetricas);
					if ($this->no_areaMetricas->Exportable) $Doc->ExportField($this->no_areaMetricas);
					if ($this->nu_modeloMetricasPadrao->Exportable) $Doc->ExportField($this->nu_modeloMetricasPadrao);
					if ($this->nu_areaVincEscritProj->Exportable) $Doc->ExportField($this->nu_areaVincEscritProj);
					if ($this->no_areaEscritProj->Exportable) $Doc->ExportField($this->no_areaEscritProj);
					if ($this->nu_fornecedorAuditoria->Exportable) $Doc->ExportField($this->nu_fornecedorAuditoria);
					if ($this->nu_fornPadraoFsw->Exportable) $Doc->ExportField($this->nu_fornPadraoFsw);
					if ($this->nu_contFornPadraoFsw->Exportable) $Doc->ExportField($this->nu_contFornPadraoFsw);
					if ($this->nu_itemContFornPadraoFsw->Exportable) $Doc->ExportField($this->nu_itemContFornPadraoFsw);
					if ($this->nu_pesoProbRisco->Exportable) $Doc->ExportField($this->nu_pesoProbRisco);
					if ($this->nu_pesoImpacRisco->Exportable) $Doc->ExportField($this->nu_pesoImpacRisco);
				} else {
					if ($this->nu_orgBase->Exportable) $Doc->ExportField($this->nu_orgBase);
					if ($this->nu_area->Exportable) $Doc->ExportField($this->nu_area);
					if ($this->nu_usuarioRespAreaTi->Exportable) $Doc->ExportField($this->nu_usuarioRespAreaTi);
					if ($this->qt_horasMes->Exportable) $Doc->ExportField($this->qt_horasMes);
					if ($this->nu_sistema->Exportable) $Doc->ExportField($this->nu_sistema);
					if ($this->dt_inicioOpSistema->Exportable) $Doc->ExportField($this->dt_inicioOpSistema);
					if ($this->tx_htmlHomeNaoLogado->Exportable) $Doc->ExportField($this->tx_htmlHomeNaoLogado);
					if ($this->nu_orgMetricas->Exportable) $Doc->ExportField($this->nu_orgMetricas);
					if ($this->nu_areaMetricas->Exportable) $Doc->ExportField($this->nu_areaMetricas);
					if ($this->nu_fornMetricas->Exportable) $Doc->ExportField($this->nu_fornMetricas);
					if ($this->no_areaMetricas->Exportable) $Doc->ExportField($this->no_areaMetricas);
					if ($this->nu_modeloMetricasPadrao->Exportable) $Doc->ExportField($this->nu_modeloMetricasPadrao);
					if ($this->nu_areaVincEscritProj->Exportable) $Doc->ExportField($this->nu_areaVincEscritProj);
					if ($this->no_areaEscritProj->Exportable) $Doc->ExportField($this->no_areaEscritProj);
					if ($this->nu_fornecedorAuditoria->Exportable) $Doc->ExportField($this->nu_fornecedorAuditoria);
					if ($this->nu_fornPadraoFsw->Exportable) $Doc->ExportField($this->nu_fornPadraoFsw);
					if ($this->nu_contFornPadraoFsw->Exportable) $Doc->ExportField($this->nu_contFornPadraoFsw);
					if ($this->nu_itemContFornPadraoFsw->Exportable) $Doc->ExportField($this->nu_itemContFornPadraoFsw);
					if ($this->nu_pesoProbRisco->Exportable) $Doc->ExportField($this->nu_pesoProbRisco);
					if ($this->nu_pesoImpacRisco->Exportable) $Doc->ExportField($this->nu_pesoImpacRisco);
				}
				$Doc->EndExportRow();
			}
			$Recordset->MoveNext();
		}
		$Doc->ExportTableFooter();
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		// Enter your code here
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
