<?php

// Global variable for table object
$contagempf_funcao = NULL;

//
// Table class for contagempf_funcao
//
class ccontagempf_funcao extends cTable {
	var $nu_contagem;
	var $nu_funcao;
	var $nu_agrupador;
	var $nu_uc;
	var $no_funcao;
	var $nu_tpManutencao;
	var $nu_tpElemento;
	var $qt_alr;
	var $ds_alr;
	var $qt_der;
	var $ds_der;
	var $ic_complexApf;
	var $vr_contribuicao;
	var $vr_fatorReducao;
	var $pc_varFasesRoteiro;
	var $vr_qtPf;
	var $ic_analalogia;
	var $ds_observacoes;
	var $nu_usuarioLogado;
	var $dh_inclusao;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'contagempf_funcao';
		$this->TableName = 'contagempf_funcao';
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

		// nu_contagem
		$this->nu_contagem = new cField('contagempf_funcao', 'contagempf_funcao', 'x_nu_contagem', 'nu_contagem', '[nu_contagem]', 'CAST([nu_contagem] AS NVARCHAR)', 3, -1, FALSE, '[nu_contagem]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_contagem->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_contagem'] = &$this->nu_contagem;

		// nu_funcao
		$this->nu_funcao = new cField('contagempf_funcao', 'contagempf_funcao', 'x_nu_funcao', 'nu_funcao', '[nu_funcao]', 'CAST([nu_funcao] AS NVARCHAR)', 3, -1, FALSE, '[nu_funcao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_funcao->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_funcao'] = &$this->nu_funcao;

		// nu_agrupador
		$this->nu_agrupador = new cField('contagempf_funcao', 'contagempf_funcao', 'x_nu_agrupador', 'nu_agrupador', '[nu_agrupador]', 'CAST([nu_agrupador] AS NVARCHAR)', 3, -1, FALSE, '[EV__nu_agrupador]', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->nu_agrupador->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_agrupador'] = &$this->nu_agrupador;

		// nu_uc
		$this->nu_uc = new cField('contagempf_funcao', 'contagempf_funcao', 'x_nu_uc', 'nu_uc', '[nu_uc]', 'CAST([nu_uc] AS NVARCHAR)', 3, -1, FALSE, '[nu_uc]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_uc->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_uc'] = &$this->nu_uc;

		// no_funcao
		$this->no_funcao = new cField('contagempf_funcao', 'contagempf_funcao', 'x_no_funcao', 'no_funcao', '[no_funcao]', '[no_funcao]', 200, -1, FALSE, '[no_funcao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_funcao'] = &$this->no_funcao;

		// nu_tpManutencao
		$this->nu_tpManutencao = new cField('contagempf_funcao', 'contagempf_funcao', 'x_nu_tpManutencao', 'nu_tpManutencao', '[nu_tpManutencao]', 'CAST([nu_tpManutencao] AS NVARCHAR)', 3, -1, FALSE, '[nu_tpManutencao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_tpManutencao->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_tpManutencao'] = &$this->nu_tpManutencao;

		// nu_tpElemento
		$this->nu_tpElemento = new cField('contagempf_funcao', 'contagempf_funcao', 'x_nu_tpElemento', 'nu_tpElemento', '[nu_tpElemento]', 'CAST([nu_tpElemento] AS NVARCHAR)', 3, -1, FALSE, '[nu_tpElemento]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_tpElemento->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_tpElemento'] = &$this->nu_tpElemento;

		// qt_alr
		$this->qt_alr = new cField('contagempf_funcao', 'contagempf_funcao', 'x_qt_alr', 'qt_alr', '[qt_alr]', 'CAST([qt_alr] AS NVARCHAR)', 3, -1, FALSE, '[qt_alr]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->qt_alr->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['qt_alr'] = &$this->qt_alr;

		// ds_alr
		$this->ds_alr = new cField('contagempf_funcao', 'contagempf_funcao', 'x_ds_alr', 'ds_alr', '[ds_alr]', '[ds_alr]', 201, -1, FALSE, '[ds_alr]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_alr'] = &$this->ds_alr;

		// qt_der
		$this->qt_der = new cField('contagempf_funcao', 'contagempf_funcao', 'x_qt_der', 'qt_der', '[qt_der]', 'CAST([qt_der] AS NVARCHAR)', 3, -1, FALSE, '[qt_der]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->qt_der->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['qt_der'] = &$this->qt_der;

		// ds_der
		$this->ds_der = new cField('contagempf_funcao', 'contagempf_funcao', 'x_ds_der', 'ds_der', '[ds_der]', '[ds_der]', 201, -1, FALSE, '[ds_der]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_der'] = &$this->ds_der;

		// ic_complexApf
		$this->ic_complexApf = new cField('contagempf_funcao', 'contagempf_funcao', 'x_ic_complexApf', 'ic_complexApf', '[ic_complexApf]', '[ic_complexApf]', 129, -1, FALSE, '[ic_complexApf]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_complexApf'] = &$this->ic_complexApf;

		// vr_contribuicao
		$this->vr_contribuicao = new cField('contagempf_funcao', 'contagempf_funcao', 'x_vr_contribuicao', 'vr_contribuicao', '[vr_contribuicao]', 'CAST([vr_contribuicao] AS NVARCHAR)', 3, -1, FALSE, '[vr_contribuicao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->vr_contribuicao->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['vr_contribuicao'] = &$this->vr_contribuicao;

		// vr_fatorReducao
		$this->vr_fatorReducao = new cField('contagempf_funcao', 'contagempf_funcao', 'x_vr_fatorReducao', 'vr_fatorReducao', '[vr_fatorReducao]', 'CAST([vr_fatorReducao] AS NVARCHAR)', 131, -1, FALSE, '[vr_fatorReducao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['vr_fatorReducao'] = &$this->vr_fatorReducao;

		// pc_varFasesRoteiro
		$this->pc_varFasesRoteiro = new cField('contagempf_funcao', 'contagempf_funcao', 'x_pc_varFasesRoteiro', 'pc_varFasesRoteiro', '[pc_varFasesRoteiro]', 'CAST([pc_varFasesRoteiro] AS NVARCHAR)', 131, -1, FALSE, '[pc_varFasesRoteiro]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->pc_varFasesRoteiro->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['pc_varFasesRoteiro'] = &$this->pc_varFasesRoteiro;

		// vr_qtPf
		$this->vr_qtPf = new cField('contagempf_funcao', 'contagempf_funcao', 'x_vr_qtPf', 'vr_qtPf', '[vr_qtPf]', 'CAST([vr_qtPf] AS NVARCHAR)', 131, -1, FALSE, '[vr_qtPf]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->vr_qtPf->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['vr_qtPf'] = &$this->vr_qtPf;

		// ic_analalogia
		$this->ic_analalogia = new cField('contagempf_funcao', 'contagempf_funcao', 'x_ic_analalogia', 'ic_analalogia', '[ic_analalogia]', '[ic_analalogia]', 129, -1, FALSE, '[ic_analalogia]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_analalogia'] = &$this->ic_analalogia;

		// ds_observacoes
		$this->ds_observacoes = new cField('contagempf_funcao', 'contagempf_funcao', 'x_ds_observacoes', 'ds_observacoes', '[ds_observacoes]', '[ds_observacoes]', 201, -1, FALSE, '[ds_observacoes]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_observacoes'] = &$this->ds_observacoes;

		// nu_usuarioLogado
		$this->nu_usuarioLogado = new cField('contagempf_funcao', 'contagempf_funcao', 'x_nu_usuarioLogado', 'nu_usuarioLogado', '[nu_usuarioLogado]', 'CAST([nu_usuarioLogado] AS NVARCHAR)', 3, -1, FALSE, '[nu_usuarioLogado]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_usuarioLogado->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_usuarioLogado'] = &$this->nu_usuarioLogado;

		// dh_inclusao
		$this->dh_inclusao = new cField('contagempf_funcao', 'contagempf_funcao', 'x_dh_inclusao', 'dh_inclusao', '[dh_inclusao]', '(REPLACE(STR(DAY([dh_inclusao]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dh_inclusao]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dh_inclusao]),4,0))', 135, 11, FALSE, '[dh_inclusao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['dh_inclusao'] = &$this->dh_inclusao;
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
		if ($this->getCurrentMasterTable() == "contagempf") {
			if ($this->nu_contagem->getSessionValue() <> "")
				$sMasterFilter .= "[nu_contagem]=" . ew_QuotedValue($this->nu_contagem->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "contagempf") {
			if ($this->nu_contagem->getSessionValue() <> "")
				$sDetailFilter .= "[nu_contagem]=" . ew_QuotedValue($this->nu_contagem->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_contagempf() {
		return "[nu_contagem]=@nu_contagem@";
	}

	// Detail filter
	function SqlDetailFilter_contagempf() {
		return "[nu_contagem]=@nu_contagem@";
	}

	// Table level SQL
	function SqlFrom() { // From
		return "[dbo].[contagempf_funcao]";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlSelectList() { // Select for List page
		return "SELECT * FROM (" .
			"SELECT *, (SELECT TOP 1 [no_agrupador] FROM [contagempf_agrupador] [EW_TMP_LOOKUPTABLE] WHERE [EW_TMP_LOOKUPTABLE].[nu_agrupador] = [contagempf_funcao].[nu_agrupador]) AS [EV__nu_agrupador] FROM [dbo].[contagempf_funcao]" .
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
		return "[nu_contagem] ASC,[nu_agrupador] ASC";
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
		if ($this->nu_agrupador->AdvancedSearch->SearchValue <> "" ||
			$this->nu_agrupador->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->nu_agrupador->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->nu_agrupador->FldVirtualExpression . " ") !== FALSE)
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
	var $UpdateTable = "[dbo].[contagempf_funcao]";

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
			if (array_key_exists('nu_funcao', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_funcao') . '=' . ew_QuotedValue($rs['nu_funcao'], $this->nu_funcao->FldDataType));
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
		return "[nu_funcao] = @nu_funcao@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nu_funcao->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_funcao@", ew_AdjustSql($this->nu_funcao->CurrentValue), $sKeyFilter); // Replace key value
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
			return "contagempf_funcaolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "contagempf_funcaolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("contagempf_funcaoview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("contagempf_funcaoview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "contagempf_funcaoadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("contagempf_funcaoedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("contagempf_funcaoadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("contagempf_funcaodelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nu_funcao->CurrentValue)) {
			$sUrl .= "nu_funcao=" . urlencode($this->nu_funcao->CurrentValue);
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
			$arKeys[] = @$_GET["nu_funcao"]; // nu_funcao

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
			$this->nu_funcao->CurrentValue = $key;
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
		$this->nu_contagem->setDbValue($rs->fields('nu_contagem'));
		$this->nu_funcao->setDbValue($rs->fields('nu_funcao'));
		$this->nu_agrupador->setDbValue($rs->fields('nu_agrupador'));
		$this->nu_uc->setDbValue($rs->fields('nu_uc'));
		$this->no_funcao->setDbValue($rs->fields('no_funcao'));
		$this->nu_tpManutencao->setDbValue($rs->fields('nu_tpManutencao'));
		$this->nu_tpElemento->setDbValue($rs->fields('nu_tpElemento'));
		$this->qt_alr->setDbValue($rs->fields('qt_alr'));
		$this->ds_alr->setDbValue($rs->fields('ds_alr'));
		$this->qt_der->setDbValue($rs->fields('qt_der'));
		$this->ds_der->setDbValue($rs->fields('ds_der'));
		$this->ic_complexApf->setDbValue($rs->fields('ic_complexApf'));
		$this->vr_contribuicao->setDbValue($rs->fields('vr_contribuicao'));
		$this->vr_fatorReducao->setDbValue($rs->fields('vr_fatorReducao'));
		$this->pc_varFasesRoteiro->setDbValue($rs->fields('pc_varFasesRoteiro'));
		$this->vr_qtPf->setDbValue($rs->fields('vr_qtPf'));
		$this->ic_analalogia->setDbValue($rs->fields('ic_analalogia'));
		$this->ds_observacoes->setDbValue($rs->fields('ds_observacoes'));
		$this->nu_usuarioLogado->setDbValue($rs->fields('nu_usuarioLogado'));
		$this->dh_inclusao->setDbValue($rs->fields('dh_inclusao'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_contagem

		$this->nu_contagem->CellCssStyle = "white-space: nowrap;";

		// nu_funcao
		// nu_agrupador
		// nu_uc
		// no_funcao
		// nu_tpManutencao
		// nu_tpElemento
		// qt_alr
		// ds_alr
		// qt_der
		// ds_der
		// ic_complexApf
		// vr_contribuicao
		// vr_fatorReducao
		// pc_varFasesRoteiro
		// vr_qtPf
		// ic_analalogia
		// ds_observacoes
		// nu_usuarioLogado
		// dh_inclusao
		// nu_contagem

		$this->nu_contagem->ViewValue = $this->nu_contagem->CurrentValue;
		if (strval($this->nu_contagem->CurrentValue) <> "") {
			$sFilterWrk = "[nu_contagem]" . ew_SearchString("=", $this->nu_contagem->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_contagem], [nu_contagem] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[contagempf]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_contagem, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_contagem->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_contagem->ViewValue = $this->nu_contagem->CurrentValue;
			}
		} else {
			$this->nu_contagem->ViewValue = NULL;
		}
		$this->nu_contagem->ViewCustomAttributes = "";

		// nu_funcao
		$this->nu_funcao->ViewValue = $this->nu_funcao->CurrentValue;
		$this->nu_funcao->ViewCustomAttributes = "";

		// nu_agrupador
		if ($this->nu_agrupador->VirtualValue <> "") {
			$this->nu_agrupador->ViewValue = $this->nu_agrupador->VirtualValue;
		} else {
		if (strval($this->nu_agrupador->CurrentValue) <> "") {
			$sFilterWrk = "[nu_agrupador]" . ew_SearchString("=", $this->nu_agrupador->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_agrupador], [no_agrupador] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[contagempf_agrupador]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_agrupador, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_agrupador] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_agrupador->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_agrupador->ViewValue = $this->nu_agrupador->CurrentValue;
			}
		} else {
			$this->nu_agrupador->ViewValue = NULL;
		}
		}
		$this->nu_agrupador->ViewCustomAttributes = "";

		// nu_uc
		if (strval($this->nu_uc->CurrentValue) <> "") {
			$sFilterWrk = "[nu_uc]" . ew_SearchString("=", $this->nu_uc->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_uc], [co_alternativo] AS [DispFld], [no_uc] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[uc]";
		$sWhereWrk = "";
		$lookuptblfilter = "[nu_sistema] = (SELECT nu_sistema FROM contagempf WHERE nu_contagem = " . strval(CurrentPage()->nu_contagem->CurrentValue) . ")";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_uc, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [co_alternativo] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_uc->ViewValue = $rswrk->fields('DispFld');
				$this->nu_uc->ViewValue .= ew_ValueSeparator(1,$this->nu_uc) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->nu_uc->ViewValue = $this->nu_uc->CurrentValue;
			}
		} else {
			$this->nu_uc->ViewValue = NULL;
		}
		$this->nu_uc->ViewCustomAttributes = "";

		// no_funcao
		$this->no_funcao->ViewValue = $this->no_funcao->CurrentValue;
		$this->no_funcao->ViewCustomAttributes = "";

		// nu_tpManutencao
		if (strval($this->nu_tpManutencao->CurrentValue) <> "") {
			$sFilterWrk = "[nu_tpManutencao]" . ew_SearchString("=", $this->nu_tpManutencao->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_tpManutencao], [no_tpManutencao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpmanutencao]";
		$sWhereWrk = "";
		$lookuptblfilter = "[nu_tpContagem]=(SELECT nu_tpContagem FROM contagempf WHERE nu_contagem = " . strval($this->nu_contagem->CurrentValue) . ")";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_tpManutencao, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_tpManutencao->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_tpManutencao->ViewValue = $this->nu_tpManutencao->CurrentValue;
			}
		} else {
			$this->nu_tpManutencao->ViewValue = NULL;
		}
		$this->nu_tpManutencao->ViewCustomAttributes = "";

		// nu_tpElemento
		if (strval($this->nu_tpElemento->CurrentValue) <> "") {
			$sFilterWrk = "[nu_tpElemento]" . ew_SearchString("=", $this->nu_tpElemento->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_tpElemento], [no_tpElemento] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpElemento]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_tpElemento, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_tpElemento] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_tpElemento->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_tpElemento->ViewValue = $this->nu_tpElemento->CurrentValue;
			}
		} else {
			$this->nu_tpElemento->ViewValue = NULL;
		}
		$this->nu_tpElemento->ViewCustomAttributes = "";

		// qt_alr
		$this->qt_alr->ViewValue = $this->qt_alr->CurrentValue;
		$this->qt_alr->ViewValue = ew_FormatNumber($this->qt_alr->ViewValue, 0, 0, 0, 0);
		$this->qt_alr->ViewCustomAttributes = "";

		// ds_alr
		$this->ds_alr->ViewValue = $this->ds_alr->CurrentValue;
		$this->ds_alr->ViewCustomAttributes = "";

		// qt_der
		$this->qt_der->ViewValue = $this->qt_der->CurrentValue;
		$this->qt_der->ViewValue = ew_FormatNumber($this->qt_der->ViewValue, 0, 0, 0, 0);
		$this->qt_der->ViewCustomAttributes = "";

		// ds_der
		$this->ds_der->ViewValue = $this->ds_der->CurrentValue;
		$this->ds_der->ViewCustomAttributes = "";

		// ic_complexApf
		$this->ic_complexApf->ViewValue = $this->ic_complexApf->CurrentValue;
		$this->ic_complexApf->ViewCustomAttributes = "";

		// vr_contribuicao
		$this->vr_contribuicao->ViewValue = $this->vr_contribuicao->CurrentValue;
		$this->vr_contribuicao->ViewValue = ew_FormatNumber($this->vr_contribuicao->ViewValue, 0, 0, 0, 0);
		$this->vr_contribuicao->ViewCustomAttributes = "";

		// vr_fatorReducao
		$this->vr_fatorReducao->ViewValue = $this->vr_fatorReducao->CurrentValue;
		$this->vr_fatorReducao->ViewCustomAttributes = "";

		// pc_varFasesRoteiro
		$this->pc_varFasesRoteiro->ViewValue = $this->pc_varFasesRoteiro->CurrentValue;
		$this->pc_varFasesRoteiro->ViewCustomAttributes = "";

		// vr_qtPf
		$this->vr_qtPf->ViewValue = $this->vr_qtPf->CurrentValue;
		$this->vr_qtPf->ViewCustomAttributes = "";

		// ic_analalogia
		if (strval($this->ic_analalogia->CurrentValue) <> "") {
			switch ($this->ic_analalogia->CurrentValue) {
				case $this->ic_analalogia->FldTagValue(1):
					$this->ic_analalogia->ViewValue = $this->ic_analalogia->FldTagCaption(1) <> "" ? $this->ic_analalogia->FldTagCaption(1) : $this->ic_analalogia->CurrentValue;
					break;
				case $this->ic_analalogia->FldTagValue(2):
					$this->ic_analalogia->ViewValue = $this->ic_analalogia->FldTagCaption(2) <> "" ? $this->ic_analalogia->FldTagCaption(2) : $this->ic_analalogia->CurrentValue;
					break;
				default:
					$this->ic_analalogia->ViewValue = $this->ic_analalogia->CurrentValue;
			}
		} else {
			$this->ic_analalogia->ViewValue = NULL;
		}
		$this->ic_analalogia->ViewCustomAttributes = "";

		// ds_observacoes
		$this->ds_observacoes->ViewValue = $this->ds_observacoes->CurrentValue;
		$this->ds_observacoes->ViewCustomAttributes = "";

		// nu_usuarioLogado
		$this->nu_usuarioLogado->ViewValue = $this->nu_usuarioLogado->CurrentValue;
		$this->nu_usuarioLogado->ViewCustomAttributes = "";

		// dh_inclusao
		$this->dh_inclusao->ViewValue = $this->dh_inclusao->CurrentValue;
		$this->dh_inclusao->ViewValue = ew_FormatDateTime($this->dh_inclusao->ViewValue, 11);
		$this->dh_inclusao->ViewCustomAttributes = "";

		// nu_contagem
		$this->nu_contagem->LinkCustomAttributes = "";
		$this->nu_contagem->HrefValue = "";
		$this->nu_contagem->TooltipValue = "";

		// nu_funcao
		$this->nu_funcao->LinkCustomAttributes = "";
		$this->nu_funcao->HrefValue = "";
		$this->nu_funcao->TooltipValue = "";

		// nu_agrupador
		$this->nu_agrupador->LinkCustomAttributes = "";
		$this->nu_agrupador->HrefValue = "";
		$this->nu_agrupador->TooltipValue = "";

		// nu_uc
		$this->nu_uc->LinkCustomAttributes = "";
		$this->nu_uc->HrefValue = "";
		$this->nu_uc->TooltipValue = "";

		// no_funcao
		$this->no_funcao->LinkCustomAttributes = "";
		$this->no_funcao->HrefValue = "";
		$this->no_funcao->TooltipValue = "";

		// nu_tpManutencao
		$this->nu_tpManutencao->LinkCustomAttributes = "";
		$this->nu_tpManutencao->HrefValue = "";
		$this->nu_tpManutencao->TooltipValue = "";

		// nu_tpElemento
		$this->nu_tpElemento->LinkCustomAttributes = "";
		$this->nu_tpElemento->HrefValue = "";
		$this->nu_tpElemento->TooltipValue = "";

		// qt_alr
		$this->qt_alr->LinkCustomAttributes = "";
		$this->qt_alr->HrefValue = "";
		$this->qt_alr->TooltipValue = "";

		// ds_alr
		$this->ds_alr->LinkCustomAttributes = "";
		$this->ds_alr->HrefValue = "";
		$this->ds_alr->TooltipValue = "";

		// qt_der
		$this->qt_der->LinkCustomAttributes = "";
		$this->qt_der->HrefValue = "";
		$this->qt_der->TooltipValue = "";

		// ds_der
		$this->ds_der->LinkCustomAttributes = "";
		$this->ds_der->HrefValue = "";
		$this->ds_der->TooltipValue = "";

		// ic_complexApf
		$this->ic_complexApf->LinkCustomAttributes = "";
		$this->ic_complexApf->HrefValue = "";
		$this->ic_complexApf->TooltipValue = "";

		// vr_contribuicao
		$this->vr_contribuicao->LinkCustomAttributes = "";
		$this->vr_contribuicao->HrefValue = "";
		$this->vr_contribuicao->TooltipValue = "";

		// vr_fatorReducao
		$this->vr_fatorReducao->LinkCustomAttributes = "";
		$this->vr_fatorReducao->HrefValue = "";
		$this->vr_fatorReducao->TooltipValue = "";

		// pc_varFasesRoteiro
		$this->pc_varFasesRoteiro->LinkCustomAttributes = "";
		$this->pc_varFasesRoteiro->HrefValue = "";
		$this->pc_varFasesRoteiro->TooltipValue = "";

		// vr_qtPf
		$this->vr_qtPf->LinkCustomAttributes = "";
		$this->vr_qtPf->HrefValue = "";
		$this->vr_qtPf->TooltipValue = "";

		// ic_analalogia
		$this->ic_analalogia->LinkCustomAttributes = "";
		$this->ic_analalogia->HrefValue = "";
		$this->ic_analalogia->TooltipValue = "";

		// ds_observacoes
		$this->ds_observacoes->LinkCustomAttributes = "";
		$this->ds_observacoes->HrefValue = "";
		$this->ds_observacoes->TooltipValue = "";

		// nu_usuarioLogado
		$this->nu_usuarioLogado->LinkCustomAttributes = "";
		$this->nu_usuarioLogado->HrefValue = "";
		$this->nu_usuarioLogado->TooltipValue = "";

		// dh_inclusao
		$this->dh_inclusao->LinkCustomAttributes = "";
		$this->dh_inclusao->HrefValue = "";
		$this->dh_inclusao->TooltipValue = "";

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
				if ($this->nu_funcao->Exportable) $Doc->ExportCaption($this->nu_funcao);
				if ($this->nu_agrupador->Exportable) $Doc->ExportCaption($this->nu_agrupador);
				if ($this->nu_uc->Exportable) $Doc->ExportCaption($this->nu_uc);
				if ($this->no_funcao->Exportable) $Doc->ExportCaption($this->no_funcao);
				if ($this->nu_tpManutencao->Exportable) $Doc->ExportCaption($this->nu_tpManutencao);
				if ($this->nu_tpElemento->Exportable) $Doc->ExportCaption($this->nu_tpElemento);
				if ($this->qt_alr->Exportable) $Doc->ExportCaption($this->qt_alr);
				if ($this->ds_alr->Exportable) $Doc->ExportCaption($this->ds_alr);
				if ($this->qt_der->Exportable) $Doc->ExportCaption($this->qt_der);
				if ($this->ds_der->Exportable) $Doc->ExportCaption($this->ds_der);
				if ($this->ic_complexApf->Exportable) $Doc->ExportCaption($this->ic_complexApf);
				if ($this->vr_contribuicao->Exportable) $Doc->ExportCaption($this->vr_contribuicao);
				if ($this->vr_fatorReducao->Exportable) $Doc->ExportCaption($this->vr_fatorReducao);
				if ($this->pc_varFasesRoteiro->Exportable) $Doc->ExportCaption($this->pc_varFasesRoteiro);
				if ($this->vr_qtPf->Exportable) $Doc->ExportCaption($this->vr_qtPf);
				if ($this->ic_analalogia->Exportable) $Doc->ExportCaption($this->ic_analalogia);
				if ($this->ds_observacoes->Exportable) $Doc->ExportCaption($this->ds_observacoes);
				if ($this->nu_usuarioLogado->Exportable) $Doc->ExportCaption($this->nu_usuarioLogado);
				if ($this->dh_inclusao->Exportable) $Doc->ExportCaption($this->dh_inclusao);
			} else {
				if ($this->nu_agrupador->Exportable) $Doc->ExportCaption($this->nu_agrupador);
				if ($this->nu_uc->Exportable) $Doc->ExportCaption($this->nu_uc);
				if ($this->no_funcao->Exportable) $Doc->ExportCaption($this->no_funcao);
				if ($this->nu_tpManutencao->Exportable) $Doc->ExportCaption($this->nu_tpManutencao);
				if ($this->nu_tpElemento->Exportable) $Doc->ExportCaption($this->nu_tpElemento);
				if ($this->qt_alr->Exportable) $Doc->ExportCaption($this->qt_alr);
				if ($this->qt_der->Exportable) $Doc->ExportCaption($this->qt_der);
				if ($this->ic_complexApf->Exportable) $Doc->ExportCaption($this->ic_complexApf);
				if ($this->vr_contribuicao->Exportable) $Doc->ExportCaption($this->vr_contribuicao);
				if ($this->vr_fatorReducao->Exportable) $Doc->ExportCaption($this->vr_fatorReducao);
				if ($this->pc_varFasesRoteiro->Exportable) $Doc->ExportCaption($this->pc_varFasesRoteiro);
				if ($this->vr_qtPf->Exportable) $Doc->ExportCaption($this->vr_qtPf);
				if ($this->ic_analalogia->Exportable) $Doc->ExportCaption($this->ic_analalogia);
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
					if ($this->nu_funcao->Exportable) $Doc->ExportField($this->nu_funcao);
					if ($this->nu_agrupador->Exportable) $Doc->ExportField($this->nu_agrupador);
					if ($this->nu_uc->Exportable) $Doc->ExportField($this->nu_uc);
					if ($this->no_funcao->Exportable) $Doc->ExportField($this->no_funcao);
					if ($this->nu_tpManutencao->Exportable) $Doc->ExportField($this->nu_tpManutencao);
					if ($this->nu_tpElemento->Exportable) $Doc->ExportField($this->nu_tpElemento);
					if ($this->qt_alr->Exportable) $Doc->ExportField($this->qt_alr);
					if ($this->ds_alr->Exportable) $Doc->ExportField($this->ds_alr);
					if ($this->qt_der->Exportable) $Doc->ExportField($this->qt_der);
					if ($this->ds_der->Exportable) $Doc->ExportField($this->ds_der);
					if ($this->ic_complexApf->Exportable) $Doc->ExportField($this->ic_complexApf);
					if ($this->vr_contribuicao->Exportable) $Doc->ExportField($this->vr_contribuicao);
					if ($this->vr_fatorReducao->Exportable) $Doc->ExportField($this->vr_fatorReducao);
					if ($this->pc_varFasesRoteiro->Exportable) $Doc->ExportField($this->pc_varFasesRoteiro);
					if ($this->vr_qtPf->Exportable) $Doc->ExportField($this->vr_qtPf);
					if ($this->ic_analalogia->Exportable) $Doc->ExportField($this->ic_analalogia);
					if ($this->ds_observacoes->Exportable) $Doc->ExportField($this->ds_observacoes);
					if ($this->nu_usuarioLogado->Exportable) $Doc->ExportField($this->nu_usuarioLogado);
					if ($this->dh_inclusao->Exportable) $Doc->ExportField($this->dh_inclusao);
				} else {
					if ($this->nu_agrupador->Exportable) $Doc->ExportField($this->nu_agrupador);
					if ($this->nu_uc->Exportable) $Doc->ExportField($this->nu_uc);
					if ($this->no_funcao->Exportable) $Doc->ExportField($this->no_funcao);
					if ($this->nu_tpManutencao->Exportable) $Doc->ExportField($this->nu_tpManutencao);
					if ($this->nu_tpElemento->Exportable) $Doc->ExportField($this->nu_tpElemento);
					if ($this->qt_alr->Exportable) $Doc->ExportField($this->qt_alr);
					if ($this->qt_der->Exportable) $Doc->ExportField($this->qt_der);
					if ($this->ic_complexApf->Exportable) $Doc->ExportField($this->ic_complexApf);
					if ($this->vr_contribuicao->Exportable) $Doc->ExportField($this->vr_contribuicao);
					if ($this->vr_fatorReducao->Exportable) $Doc->ExportField($this->vr_fatorReducao);
					if ($this->pc_varFasesRoteiro->Exportable) $Doc->ExportField($this->pc_varFasesRoteiro);
					if ($this->vr_qtPf->Exportable) $Doc->ExportField($this->vr_qtPf);
					if ($this->ic_analalogia->Exportable) $Doc->ExportField($this->ic_analalogia);
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
		ew_Execute("UPDATE contagempf SET vr_pfFaturamento = (SELECT SUM(vr_qtPf) FROM contagempf_funcao WHERE nu_contagem = " . $rsnew["nu_contagem"] . ") WHERE nu_contagem = " . $rsnew["nu_contagem"] . "");
		$nu_sol = ew_ExecuteScalar("select nu_solMetricas from contagemPf where nu_contagem = " . $rsnew["nu_contagem"] . "");                       
		ew_Execute("UPDATE solicitacaoMetricas SET qt_pfTotal = (SELECT SUM(vr_pfFaturamento) FROM contagempf WHERE nu_solMetricas = " . $nu_sol . ") WHERE nu_solMetricas = " . $nu_sol . "");        
	}                                                                              

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated"           
		ew_Execute("UPDATE contagempf SET vr_pfFaturamento = (SELECT SUM(vr_qtPf) FROM contagempf_funcao WHERE nu_contagem = " . $rsold["nu_contagem"] . ") WHERE nu_contagem = " . $rsold["nu_contagem"] . "");
		$nu_sol = ew_ExecuteScalar("select nu_solMetricas from contagemPf where nu_contagem = " . $rsold["nu_contagem"] . "");   
		ew_Execute("UPDATE solicitacaoMetricas SET qt_pfTotal = (SELECT SUM(vr_pfFaturamento) FROM contagempf WHERE nu_solMetricas = " . $nu_sol . ") WHERE nu_solMetricas = " . $nu_sol . "");        
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
		ew_Execute("UPDATE contagempf SET vr_pfFaturamento = (SELECT SUM(vr_qtPf) FROM contagempf_funcao WHERE nu_contagem = " . $rsold["nu_contagem"] . ") WHERE nu_contagem = " . $rsold["nu_contagem"] . ""); 
		$nu_sol = ew_ExecuteScalar("select nu_solMetricas from contagemPf where nu_contagem = " . $rsold["nu_contagem"] . "");   
		ew_Execute("UPDATE solicitacaoMetricas SET qt_pfTotal = (SELECT SUM(vr_pfFaturamento) FROM contagempf WHERE nu_solMetricas = " . $nu_sol . ") WHERE nu_solMetricas = " . $nu_sol . "");        
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
