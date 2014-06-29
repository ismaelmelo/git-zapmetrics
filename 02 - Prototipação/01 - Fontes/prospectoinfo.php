<?php

// Global variable for table object
$prospecto = NULL;

//
// Table class for prospecto
//
class cprospecto extends cTable {
	var $nu_prospecto;
	var $no_prospecto;
	var $nu_area;
	var $no_solicitante;
	var $no_patrocinador;
	var $ar_entidade;
	var $ar_nivel;
	var $nu_categoriaProspecto;
	var $nu_alternativaImpacto;
	var $ds_sistemas;
	var $ds_impactoNaoImplem;
	var $nu_alternativaAlinhamento;
	var $nu_alternativaAbrangencia;
	var $nu_alternativaUrgencia;
	var $dt_prazo;
	var $nu_alternativaTmpEstimado;
	var $nu_alternativaTmpFila;
	var $ic_implicacaoLegal;
	var $ic_risco;
	var $ic_stProspecto;
	var $ds_observacoes;
	var $ic_ativo;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'prospecto';
		$this->TableName = 'prospecto';
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

		// nu_prospecto
		$this->nu_prospecto = new cField('prospecto', 'prospecto', 'x_nu_prospecto', 'nu_prospecto', '[nu_prospecto]', 'CAST([nu_prospecto] AS NVARCHAR)', 3, -1, FALSE, '[nu_prospecto]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_prospecto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_prospecto'] = &$this->nu_prospecto;

		// no_prospecto
		$this->no_prospecto = new cField('prospecto', 'prospecto', 'x_no_prospecto', 'no_prospecto', '[no_prospecto]', '[no_prospecto]', 200, -1, FALSE, '[no_prospecto]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_prospecto'] = &$this->no_prospecto;

		// nu_area
		$this->nu_area = new cField('prospecto', 'prospecto', 'x_nu_area', 'nu_area', '[nu_area]', 'CAST([nu_area] AS NVARCHAR)', 3, -1, FALSE, '[nu_area]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_area->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_area'] = &$this->nu_area;

		// no_solicitante
		$this->no_solicitante = new cField('prospecto', 'prospecto', 'x_no_solicitante', 'no_solicitante', '[no_solicitante]', '[no_solicitante]', 200, -1, FALSE, '[no_solicitante]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_solicitante'] = &$this->no_solicitante;

		// no_patrocinador
		$this->no_patrocinador = new cField('prospecto', 'prospecto', 'x_no_patrocinador', 'no_patrocinador', '[no_patrocinador]', '[no_patrocinador]', 200, -1, FALSE, '[no_patrocinador]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_patrocinador'] = &$this->no_patrocinador;

		// ar_entidade
		$this->ar_entidade = new cField('prospecto', 'prospecto', 'x_ar_entidade', 'ar_entidade', '[ar_entidade]', '[ar_entidade]', 200, -1, FALSE, '[ar_entidade]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ar_entidade'] = &$this->ar_entidade;

		// ar_nivel
		$this->ar_nivel = new cField('prospecto', 'prospecto', 'x_ar_nivel', 'ar_nivel', '[ar_nivel]', '[ar_nivel]', 200, -1, FALSE, '[ar_nivel]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ar_nivel'] = &$this->ar_nivel;

		// nu_categoriaProspecto
		$this->nu_categoriaProspecto = new cField('prospecto', 'prospecto', 'x_nu_categoriaProspecto', 'nu_categoriaProspecto', '[nu_categoriaProspecto]', 'CAST([nu_categoriaProspecto] AS NVARCHAR)', 3, -1, FALSE, '[nu_categoriaProspecto]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_categoriaProspecto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_categoriaProspecto'] = &$this->nu_categoriaProspecto;

		// nu_alternativaImpacto
		$this->nu_alternativaImpacto = new cField('prospecto', 'prospecto', 'x_nu_alternativaImpacto', 'nu_alternativaImpacto', '[nu_alternativaImpacto]', 'CAST([nu_alternativaImpacto] AS NVARCHAR)', 3, -1, FALSE, '[nu_alternativaImpacto]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_alternativaImpacto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_alternativaImpacto'] = &$this->nu_alternativaImpacto;

		// ds_sistemas
		$this->ds_sistemas = new cField('prospecto', 'prospecto', 'x_ds_sistemas', 'ds_sistemas', '[ds_sistemas]', '[ds_sistemas]', 201, -1, FALSE, '[ds_sistemas]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_sistemas'] = &$this->ds_sistemas;

		// ds_impactoNaoImplem
		$this->ds_impactoNaoImplem = new cField('prospecto', 'prospecto', 'x_ds_impactoNaoImplem', 'ds_impactoNaoImplem', '[ds_impactoNaoImplem]', '[ds_impactoNaoImplem]', 201, -1, FALSE, '[ds_impactoNaoImplem]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_impactoNaoImplem'] = &$this->ds_impactoNaoImplem;

		// nu_alternativaAlinhamento
		$this->nu_alternativaAlinhamento = new cField('prospecto', 'prospecto', 'x_nu_alternativaAlinhamento', 'nu_alternativaAlinhamento', '[nu_alternativaAlinhamento]', 'CAST([nu_alternativaAlinhamento] AS NVARCHAR)', 3, -1, FALSE, '[nu_alternativaAlinhamento]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_alternativaAlinhamento->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_alternativaAlinhamento'] = &$this->nu_alternativaAlinhamento;

		// nu_alternativaAbrangencia
		$this->nu_alternativaAbrangencia = new cField('prospecto', 'prospecto', 'x_nu_alternativaAbrangencia', 'nu_alternativaAbrangencia', '[nu_alternativaAbrangencia]', 'CAST([nu_alternativaAbrangencia] AS NVARCHAR)', 3, -1, FALSE, '[nu_alternativaAbrangencia]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_alternativaAbrangencia->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_alternativaAbrangencia'] = &$this->nu_alternativaAbrangencia;

		// nu_alternativaUrgencia
		$this->nu_alternativaUrgencia = new cField('prospecto', 'prospecto', 'x_nu_alternativaUrgencia', 'nu_alternativaUrgencia', '[nu_alternativaUrgencia]', 'CAST([nu_alternativaUrgencia] AS NVARCHAR)', 3, -1, FALSE, '[nu_alternativaUrgencia]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_alternativaUrgencia->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_alternativaUrgencia'] = &$this->nu_alternativaUrgencia;

		// dt_prazo
		$this->dt_prazo = new cField('prospecto', 'prospecto', 'x_dt_prazo', 'dt_prazo', '[dt_prazo]', '(REPLACE(STR(DAY([dt_prazo]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dt_prazo]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dt_prazo]),4,0))', 135, 7, FALSE, '[dt_prazo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dt_prazo->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dt_prazo'] = &$this->dt_prazo;

		// nu_alternativaTmpEstimado
		$this->nu_alternativaTmpEstimado = new cField('prospecto', 'prospecto', 'x_nu_alternativaTmpEstimado', 'nu_alternativaTmpEstimado', '[nu_alternativaTmpEstimado]', 'CAST([nu_alternativaTmpEstimado] AS NVARCHAR)', 3, -1, FALSE, '[nu_alternativaTmpEstimado]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_alternativaTmpEstimado->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_alternativaTmpEstimado'] = &$this->nu_alternativaTmpEstimado;

		// nu_alternativaTmpFila
		$this->nu_alternativaTmpFila = new cField('prospecto', 'prospecto', 'x_nu_alternativaTmpFila', 'nu_alternativaTmpFila', '[nu_alternativaTmpFila]', 'CAST([nu_alternativaTmpFila] AS NVARCHAR)', 3, -1, FALSE, '[nu_alternativaTmpFila]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_alternativaTmpFila->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_alternativaTmpFila'] = &$this->nu_alternativaTmpFila;

		// ic_implicacaoLegal
		$this->ic_implicacaoLegal = new cField('prospecto', 'prospecto', 'x_ic_implicacaoLegal', 'ic_implicacaoLegal', '[ic_implicacaoLegal]', '[ic_implicacaoLegal]', 129, -1, FALSE, '[ic_implicacaoLegal]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_implicacaoLegal'] = &$this->ic_implicacaoLegal;

		// ic_risco
		$this->ic_risco = new cField('prospecto', 'prospecto', 'x_ic_risco', 'ic_risco', '[ic_risco]', '[ic_risco]', 129, -1, FALSE, '[ic_risco]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_risco'] = &$this->ic_risco;

		// ic_stProspecto
		$this->ic_stProspecto = new cField('prospecto', 'prospecto', 'x_ic_stProspecto', 'ic_stProspecto', '[ic_stProspecto]', '[ic_stProspecto]', 129, -1, FALSE, '[ic_stProspecto]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_stProspecto'] = &$this->ic_stProspecto;

		// ds_observacoes
		$this->ds_observacoes = new cField('prospecto', 'prospecto', 'x_ds_observacoes', 'ds_observacoes', '[ds_observacoes]', '[ds_observacoes]', 201, -1, FALSE, '[ds_observacoes]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_observacoes'] = &$this->ds_observacoes;

		// ic_ativo
		$this->ic_ativo = new cField('prospecto', 'prospecto', 'x_ic_ativo', 'ic_ativo', '[ic_ativo]', '[ic_ativo]', 129, -1, FALSE, '[ic_ativo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_ativo'] = &$this->ic_ativo;
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
		} else {
			if (!$ctrl) $ofld->setSort("");
		}
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
		if ($this->getCurrentMasterTable() == "rprospresumo") {
			if ($this->nu_prospecto->getSessionValue() <> "")
				$sMasterFilter .= "[nu_prospecto]=" . ew_QuotedValue($this->nu_prospecto->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "rprospresumo") {
			if ($this->nu_prospecto->getSessionValue() <> "")
				$sDetailFilter .= "[nu_prospecto]=" . ew_QuotedValue($this->nu_prospecto->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_rprospresumo() {
		return "[nu_prospecto]=@nu_prospecto@";
	}

	// Detail filter
	function SqlDetailFilter_rprospresumo() {
		return "[nu_prospecto]=@nu_prospecto@";
	}

	// Current detail table name
	function getCurrentDetailTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE];
	}

	function setCurrentDetailTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE] = $v;
	}

	// Get detail url
	function GetDetailUrl() {

		// Detail url
		$sDetailUrl = "";
		if ($this->getCurrentDetailTable() == "prospecto_itempdti") {
			$sDetailUrl = $GLOBALS["prospecto_itempdti"]->GetListUrl() . "?showmaster=" . $this->TableVar;
			$sDetailUrl .= "&nu_prospecto=" . $this->nu_prospecto->CurrentValue;
		}
		if ($this->getCurrentDetailTable() == "prospectoocorrencias") {
			$sDetailUrl = $GLOBALS["prospectoocorrencias"]->GetListUrl() . "?showmaster=" . $this->TableVar;
			$sDetailUrl .= "&nu_prospecto=" . $this->nu_prospecto->CurrentValue;
		}
		if ($sDetailUrl == "") {
			$sDetailUrl = "prospectolist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	function SqlFrom() { // From
		return "[dbo].[prospecto]";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
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
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(), $this->SqlGroupBy(),
			$this->SqlHaving(), $this->SqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->SqlOrderBy(), "", $sSort);
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
	var $UpdateTable = "[dbo].[prospecto]";

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
			if (array_key_exists('nu_prospecto', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_prospecto') . '=' . ew_QuotedValue($rs['nu_prospecto'], $this->nu_prospecto->FldDataType));
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
		return "[nu_prospecto] = @nu_prospecto@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nu_prospecto->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_prospecto@", ew_AdjustSql($this->nu_prospecto->CurrentValue), $sKeyFilter); // Replace key value
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
			return "prospectolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "prospectolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("prospectoview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("prospectoview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "prospectoadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("prospectoedit.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("prospectoedit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("prospectoadd.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("prospectoadd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("prospectodelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nu_prospecto->CurrentValue)) {
			$sUrl .= "nu_prospecto=" . urlencode($this->nu_prospecto->CurrentValue);
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
			$arKeys[] = @$_GET["nu_prospecto"]; // nu_prospecto

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
			$this->nu_prospecto->CurrentValue = $key;
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
		$this->nu_prospecto->setDbValue($rs->fields('nu_prospecto'));
		$this->no_prospecto->setDbValue($rs->fields('no_prospecto'));
		$this->nu_area->setDbValue($rs->fields('nu_area'));
		$this->no_solicitante->setDbValue($rs->fields('no_solicitante'));
		$this->no_patrocinador->setDbValue($rs->fields('no_patrocinador'));
		$this->ar_entidade->setDbValue($rs->fields('ar_entidade'));
		$this->ar_nivel->setDbValue($rs->fields('ar_nivel'));
		$this->nu_categoriaProspecto->setDbValue($rs->fields('nu_categoriaProspecto'));
		$this->nu_alternativaImpacto->setDbValue($rs->fields('nu_alternativaImpacto'));
		$this->ds_sistemas->setDbValue($rs->fields('ds_sistemas'));
		$this->ds_impactoNaoImplem->setDbValue($rs->fields('ds_impactoNaoImplem'));
		$this->nu_alternativaAlinhamento->setDbValue($rs->fields('nu_alternativaAlinhamento'));
		$this->nu_alternativaAbrangencia->setDbValue($rs->fields('nu_alternativaAbrangencia'));
		$this->nu_alternativaUrgencia->setDbValue($rs->fields('nu_alternativaUrgencia'));
		$this->dt_prazo->setDbValue($rs->fields('dt_prazo'));
		$this->nu_alternativaTmpEstimado->setDbValue($rs->fields('nu_alternativaTmpEstimado'));
		$this->nu_alternativaTmpFila->setDbValue($rs->fields('nu_alternativaTmpFila'));
		$this->ic_implicacaoLegal->setDbValue($rs->fields('ic_implicacaoLegal'));
		$this->ic_risco->setDbValue($rs->fields('ic_risco'));
		$this->ic_stProspecto->setDbValue($rs->fields('ic_stProspecto'));
		$this->ds_observacoes->setDbValue($rs->fields('ds_observacoes'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_prospecto
		// no_prospecto
		// nu_area
		// no_solicitante
		// no_patrocinador
		// ar_entidade
		// ar_nivel
		// nu_categoriaProspecto
		// nu_alternativaImpacto
		// ds_sistemas
		// ds_impactoNaoImplem
		// nu_alternativaAlinhamento
		// nu_alternativaAbrangencia
		// nu_alternativaUrgencia
		// dt_prazo
		// nu_alternativaTmpEstimado
		// nu_alternativaTmpFila
		// ic_implicacaoLegal
		// ic_risco
		// ic_stProspecto
		// ds_observacoes
		// ic_ativo
		// nu_prospecto

		$this->nu_prospecto->ViewValue = $this->nu_prospecto->CurrentValue;
		$this->nu_prospecto->ViewCustomAttributes = "";

		// no_prospecto
		$this->no_prospecto->ViewValue = $this->no_prospecto->CurrentValue;
		$this->no_prospecto->ViewCustomAttributes = "";

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

		// no_solicitante
		$this->no_solicitante->ViewValue = $this->no_solicitante->CurrentValue;
		$this->no_solicitante->ViewCustomAttributes = "";

		// no_patrocinador
		$this->no_patrocinador->ViewValue = $this->no_patrocinador->CurrentValue;
		$this->no_patrocinador->ViewCustomAttributes = "";

		// ar_entidade
		if (strval($this->ar_entidade->CurrentValue) <> "") {
			$arwrk = explode(",", $this->ar_entidade->CurrentValue);
			$sFilterWrk = "";
			foreach ($arwrk as $wrk) {
				if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
				$sFilterWrk .= "[nu_organizacao]" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER);
			}	
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
		$this->Lookup_Selecting($this->ar_entidade, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_organizacao] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->ar_entidade->ViewValue = "";
				$ari = 0;
				while (!$rswrk->EOF) {
					$this->ar_entidade->ViewValue .= $rswrk->fields('DispFld');
					$rswrk->MoveNext();
					if (!$rswrk->EOF) $this->ar_entidade->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
					$ari++;
				}
				$rswrk->Close();
			} else {
				$this->ar_entidade->ViewValue = $this->ar_entidade->CurrentValue;
			}
		} else {
			$this->ar_entidade->ViewValue = NULL;
		}
		$this->ar_entidade->ViewCustomAttributes = "";

		// ar_nivel
		if (strval($this->ar_nivel->CurrentValue) <> "") {
			$this->ar_nivel->ViewValue = "";
			$arwrk = explode(",", strval($this->ar_nivel->CurrentValue));
			$cnt = count($arwrk);
			for ($ari = 0; $ari < $cnt; $ari++) {
				switch (trim($arwrk[$ari])) {
					case $this->ar_nivel->FldTagValue(1):
						$this->ar_nivel->ViewValue .= $this->ar_nivel->FldTagCaption(1) <> "" ? $this->ar_nivel->FldTagCaption(1) : trim($arwrk[$ari]);
						break;
					case $this->ar_nivel->FldTagValue(2):
						$this->ar_nivel->ViewValue .= $this->ar_nivel->FldTagCaption(2) <> "" ? $this->ar_nivel->FldTagCaption(2) : trim($arwrk[$ari]);
						break;
					default:
						$this->ar_nivel->ViewValue .= trim($arwrk[$ari]);
				}
				if ($ari < $cnt-1) $this->ar_nivel->ViewValue .= ew_ViewOptionSeparator($ari);
			}
		} else {
			$this->ar_nivel->ViewValue = NULL;
		}
		$this->ar_nivel->ViewCustomAttributes = "";

		// nu_categoriaProspecto
		if (strval($this->nu_categoriaProspecto->CurrentValue) <> "") {
			$sFilterWrk = "[nu_categoria]" . ew_SearchString("=", $this->nu_categoriaProspecto->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_categoria], [no_categoria] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[catprospecto]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_categoriaProspecto, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_categoria] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_categoriaProspecto->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_categoriaProspecto->ViewValue = $this->nu_categoriaProspecto->CurrentValue;
			}
		} else {
			$this->nu_categoriaProspecto->ViewValue = NULL;
		}
		$this->nu_categoriaProspecto->ViewCustomAttributes = "";

		// nu_alternativaImpacto
		if (strval($this->nu_alternativaImpacto->CurrentValue) <> "") {
			$sFilterWrk = "[nu_alternativaAvaliacao]" . ew_SearchString("=", $this->nu_alternativaImpacto->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_alternativaAvaliacao], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[criterioavaliacao]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S' AND [nu_criterioPrioridade] = 10";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_alternativaImpacto, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [vr_alternativa] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_alternativaImpacto->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_alternativaImpacto->ViewValue = $this->nu_alternativaImpacto->CurrentValue;
			}
		} else {
			$this->nu_alternativaImpacto->ViewValue = NULL;
		}
		$this->nu_alternativaImpacto->ViewCustomAttributes = "";

		// ds_sistemas
		$this->ds_sistemas->ViewValue = $this->ds_sistemas->CurrentValue;
		$this->ds_sistemas->ViewCustomAttributes = "";

		// ds_impactoNaoImplem
		$this->ds_impactoNaoImplem->ViewValue = $this->ds_impactoNaoImplem->CurrentValue;
		$this->ds_impactoNaoImplem->ViewCustomAttributes = "";

		// nu_alternativaAlinhamento
		if (strval($this->nu_alternativaAlinhamento->CurrentValue) <> "") {
			$sFilterWrk = "[nu_alternativaAvaliacao]" . ew_SearchString("=", $this->nu_alternativaAlinhamento->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_alternativaAvaliacao], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[criterioavaliacao]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S' AND [nu_criterioPrioridade] = '11'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_alternativaAlinhamento, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [vr_alternativa] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_alternativaAlinhamento->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_alternativaAlinhamento->ViewValue = $this->nu_alternativaAlinhamento->CurrentValue;
			}
		} else {
			$this->nu_alternativaAlinhamento->ViewValue = NULL;
		}
		$this->nu_alternativaAlinhamento->ViewCustomAttributes = "";

		// nu_alternativaAbrangencia
		if (strval($this->nu_alternativaAbrangencia->CurrentValue) <> "") {
			$sFilterWrk = "[nu_alternativaAvaliacao]" . ew_SearchString("=", $this->nu_alternativaAbrangencia->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_alternativaAvaliacao], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[criterioavaliacao]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S' AND [nu_criterioPrioridade] = 12";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_alternativaAbrangencia, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [vr_alternativa] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_alternativaAbrangencia->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_alternativaAbrangencia->ViewValue = $this->nu_alternativaAbrangencia->CurrentValue;
			}
		} else {
			$this->nu_alternativaAbrangencia->ViewValue = NULL;
		}
		$this->nu_alternativaAbrangencia->ViewCustomAttributes = "";

		// nu_alternativaUrgencia
		if (strval($this->nu_alternativaUrgencia->CurrentValue) <> "") {
			$sFilterWrk = "[nu_alternativaAvaliacao]" . ew_SearchString("=", $this->nu_alternativaUrgencia->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_alternativaAvaliacao], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[criterioavaliacao]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S' AND [nu_criterioPrioridade] = 13";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_alternativaUrgencia, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [vr_alternativa] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_alternativaUrgencia->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_alternativaUrgencia->ViewValue = $this->nu_alternativaUrgencia->CurrentValue;
			}
		} else {
			$this->nu_alternativaUrgencia->ViewValue = NULL;
		}
		$this->nu_alternativaUrgencia->ViewCustomAttributes = "";

		// dt_prazo
		$this->dt_prazo->ViewValue = $this->dt_prazo->CurrentValue;
		$this->dt_prazo->ViewValue = ew_FormatDateTime($this->dt_prazo->ViewValue, 7);
		$this->dt_prazo->ViewCustomAttributes = "";

		// nu_alternativaTmpEstimado
		if (strval($this->nu_alternativaTmpEstimado->CurrentValue) <> "") {
			$sFilterWrk = "[nu_alternativaAvaliacao]" . ew_SearchString("=", $this->nu_alternativaTmpEstimado->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_alternativaAvaliacao], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[criterioavaliacao]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S' AND [nu_criterioPrioridade] = 14";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_alternativaTmpEstimado, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [vr_alternativa] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_alternativaTmpEstimado->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_alternativaTmpEstimado->ViewValue = $this->nu_alternativaTmpEstimado->CurrentValue;
			}
		} else {
			$this->nu_alternativaTmpEstimado->ViewValue = NULL;
		}
		$this->nu_alternativaTmpEstimado->ViewCustomAttributes = "";

		// nu_alternativaTmpFila
		if (strval($this->nu_alternativaTmpFila->CurrentValue) <> "") {
			$sFilterWrk = "[nu_alternativaAvaliacao]" . ew_SearchString("=", $this->nu_alternativaTmpFila->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_alternativaAvaliacao], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[criterioavaliacao]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S' AND [nu_criterioPrioridade] = 15";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_alternativaTmpFila, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [vr_alternativa] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_alternativaTmpFila->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_alternativaTmpFila->ViewValue = $this->nu_alternativaTmpFila->CurrentValue;
			}
		} else {
			$this->nu_alternativaTmpFila->ViewValue = NULL;
		}
		$this->nu_alternativaTmpFila->ViewCustomAttributes = "";

		// ic_implicacaoLegal
		if (strval($this->ic_implicacaoLegal->CurrentValue) <> "") {
			switch ($this->ic_implicacaoLegal->CurrentValue) {
				case $this->ic_implicacaoLegal->FldTagValue(1):
					$this->ic_implicacaoLegal->ViewValue = $this->ic_implicacaoLegal->FldTagCaption(1) <> "" ? $this->ic_implicacaoLegal->FldTagCaption(1) : $this->ic_implicacaoLegal->CurrentValue;
					break;
				case $this->ic_implicacaoLegal->FldTagValue(2):
					$this->ic_implicacaoLegal->ViewValue = $this->ic_implicacaoLegal->FldTagCaption(2) <> "" ? $this->ic_implicacaoLegal->FldTagCaption(2) : $this->ic_implicacaoLegal->CurrentValue;
					break;
				default:
					$this->ic_implicacaoLegal->ViewValue = $this->ic_implicacaoLegal->CurrentValue;
			}
		} else {
			$this->ic_implicacaoLegal->ViewValue = NULL;
		}
		$this->ic_implicacaoLegal->ViewCustomAttributes = "";

		// ic_risco
		if (strval($this->ic_risco->CurrentValue) <> "") {
			switch ($this->ic_risco->CurrentValue) {
				case $this->ic_risco->FldTagValue(1):
					$this->ic_risco->ViewValue = $this->ic_risco->FldTagCaption(1) <> "" ? $this->ic_risco->FldTagCaption(1) : $this->ic_risco->CurrentValue;
					break;
				case $this->ic_risco->FldTagValue(2):
					$this->ic_risco->ViewValue = $this->ic_risco->FldTagCaption(2) <> "" ? $this->ic_risco->FldTagCaption(2) : $this->ic_risco->CurrentValue;
					break;
				case $this->ic_risco->FldTagValue(3):
					$this->ic_risco->ViewValue = $this->ic_risco->FldTagCaption(3) <> "" ? $this->ic_risco->FldTagCaption(3) : $this->ic_risco->CurrentValue;
					break;
				default:
					$this->ic_risco->ViewValue = $this->ic_risco->CurrentValue;
			}
		} else {
			$this->ic_risco->ViewValue = NULL;
		}
		$this->ic_risco->ViewCustomAttributes = "";

		// ic_stProspecto
		if (strval($this->ic_stProspecto->CurrentValue) <> "") {
			switch ($this->ic_stProspecto->CurrentValue) {
				case $this->ic_stProspecto->FldTagValue(1):
					$this->ic_stProspecto->ViewValue = $this->ic_stProspecto->FldTagCaption(1) <> "" ? $this->ic_stProspecto->FldTagCaption(1) : $this->ic_stProspecto->CurrentValue;
					break;
				case $this->ic_stProspecto->FldTagValue(2):
					$this->ic_stProspecto->ViewValue = $this->ic_stProspecto->FldTagCaption(2) <> "" ? $this->ic_stProspecto->FldTagCaption(2) : $this->ic_stProspecto->CurrentValue;
					break;
				case $this->ic_stProspecto->FldTagValue(3):
					$this->ic_stProspecto->ViewValue = $this->ic_stProspecto->FldTagCaption(3) <> "" ? $this->ic_stProspecto->FldTagCaption(3) : $this->ic_stProspecto->CurrentValue;
					break;
				case $this->ic_stProspecto->FldTagValue(4):
					$this->ic_stProspecto->ViewValue = $this->ic_stProspecto->FldTagCaption(4) <> "" ? $this->ic_stProspecto->FldTagCaption(4) : $this->ic_stProspecto->CurrentValue;
					break;
				case $this->ic_stProspecto->FldTagValue(5):
					$this->ic_stProspecto->ViewValue = $this->ic_stProspecto->FldTagCaption(5) <> "" ? $this->ic_stProspecto->FldTagCaption(5) : $this->ic_stProspecto->CurrentValue;
					break;
				default:
					$this->ic_stProspecto->ViewValue = $this->ic_stProspecto->CurrentValue;
			}
		} else {
			$this->ic_stProspecto->ViewValue = NULL;
		}
		$this->ic_stProspecto->ViewCustomAttributes = "";

		// ds_observacoes
		$this->ds_observacoes->ViewValue = $this->ds_observacoes->CurrentValue;
		$this->ds_observacoes->ViewCustomAttributes = "";

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

		// nu_prospecto
		$this->nu_prospecto->LinkCustomAttributes = "";
		$this->nu_prospecto->HrefValue = "";
		$this->nu_prospecto->TooltipValue = "";

		// no_prospecto
		$this->no_prospecto->LinkCustomAttributes = "";
		$this->no_prospecto->HrefValue = "";
		$this->no_prospecto->TooltipValue = "";

		// nu_area
		$this->nu_area->LinkCustomAttributes = "";
		$this->nu_area->HrefValue = "";
		$this->nu_area->TooltipValue = "";

		// no_solicitante
		$this->no_solicitante->LinkCustomAttributes = "";
		$this->no_solicitante->HrefValue = "";
		$this->no_solicitante->TooltipValue = "";

		// no_patrocinador
		$this->no_patrocinador->LinkCustomAttributes = "";
		$this->no_patrocinador->HrefValue = "";
		$this->no_patrocinador->TooltipValue = "";

		// ar_entidade
		$this->ar_entidade->LinkCustomAttributes = "";
		$this->ar_entidade->HrefValue = "";
		$this->ar_entidade->TooltipValue = "";

		// ar_nivel
		$this->ar_nivel->LinkCustomAttributes = "";
		$this->ar_nivel->HrefValue = "";
		$this->ar_nivel->TooltipValue = "";

		// nu_categoriaProspecto
		$this->nu_categoriaProspecto->LinkCustomAttributes = "";
		$this->nu_categoriaProspecto->HrefValue = "";
		$this->nu_categoriaProspecto->TooltipValue = "";

		// nu_alternativaImpacto
		$this->nu_alternativaImpacto->LinkCustomAttributes = "";
		$this->nu_alternativaImpacto->HrefValue = "";
		$this->nu_alternativaImpacto->TooltipValue = "";

		// ds_sistemas
		$this->ds_sistemas->LinkCustomAttributes = "";
		$this->ds_sistemas->HrefValue = "";
		$this->ds_sistemas->TooltipValue = "";

		// ds_impactoNaoImplem
		$this->ds_impactoNaoImplem->LinkCustomAttributes = "";
		$this->ds_impactoNaoImplem->HrefValue = "";
		$this->ds_impactoNaoImplem->TooltipValue = "";

		// nu_alternativaAlinhamento
		$this->nu_alternativaAlinhamento->LinkCustomAttributes = "";
		$this->nu_alternativaAlinhamento->HrefValue = "";
		$this->nu_alternativaAlinhamento->TooltipValue = "";

		// nu_alternativaAbrangencia
		$this->nu_alternativaAbrangencia->LinkCustomAttributes = "";
		$this->nu_alternativaAbrangencia->HrefValue = "";
		$this->nu_alternativaAbrangencia->TooltipValue = "";

		// nu_alternativaUrgencia
		$this->nu_alternativaUrgencia->LinkCustomAttributes = "";
		$this->nu_alternativaUrgencia->HrefValue = "";
		$this->nu_alternativaUrgencia->TooltipValue = "";

		// dt_prazo
		$this->dt_prazo->LinkCustomAttributes = "";
		$this->dt_prazo->HrefValue = "";
		$this->dt_prazo->TooltipValue = "";

		// nu_alternativaTmpEstimado
		$this->nu_alternativaTmpEstimado->LinkCustomAttributes = "";
		$this->nu_alternativaTmpEstimado->HrefValue = "";
		$this->nu_alternativaTmpEstimado->TooltipValue = "";

		// nu_alternativaTmpFila
		$this->nu_alternativaTmpFila->LinkCustomAttributes = "";
		$this->nu_alternativaTmpFila->HrefValue = "";
		$this->nu_alternativaTmpFila->TooltipValue = "";

		// ic_implicacaoLegal
		$this->ic_implicacaoLegal->LinkCustomAttributes = "";
		$this->ic_implicacaoLegal->HrefValue = "";
		$this->ic_implicacaoLegal->TooltipValue = "";

		// ic_risco
		$this->ic_risco->LinkCustomAttributes = "";
		$this->ic_risco->HrefValue = "";
		$this->ic_risco->TooltipValue = "";

		// ic_stProspecto
		$this->ic_stProspecto->LinkCustomAttributes = "";
		$this->ic_stProspecto->HrefValue = "";
		$this->ic_stProspecto->TooltipValue = "";

		// ds_observacoes
		$this->ds_observacoes->LinkCustomAttributes = "";
		$this->ds_observacoes->HrefValue = "";
		$this->ds_observacoes->TooltipValue = "";

		// ic_ativo
		$this->ic_ativo->LinkCustomAttributes = "";
		$this->ic_ativo->HrefValue = "";
		$this->ic_ativo->TooltipValue = "";

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
				if ($this->nu_prospecto->Exportable) $Doc->ExportCaption($this->nu_prospecto);
				if ($this->no_prospecto->Exportable) $Doc->ExportCaption($this->no_prospecto);
				if ($this->nu_area->Exportable) $Doc->ExportCaption($this->nu_area);
				if ($this->no_solicitante->Exportable) $Doc->ExportCaption($this->no_solicitante);
				if ($this->no_patrocinador->Exportable) $Doc->ExportCaption($this->no_patrocinador);
				if ($this->ar_entidade->Exportable) $Doc->ExportCaption($this->ar_entidade);
				if ($this->ar_nivel->Exportable) $Doc->ExportCaption($this->ar_nivel);
				if ($this->nu_categoriaProspecto->Exportable) $Doc->ExportCaption($this->nu_categoriaProspecto);
				if ($this->nu_alternativaImpacto->Exportable) $Doc->ExportCaption($this->nu_alternativaImpacto);
				if ($this->ds_sistemas->Exportable) $Doc->ExportCaption($this->ds_sistemas);
				if ($this->ds_impactoNaoImplem->Exportable) $Doc->ExportCaption($this->ds_impactoNaoImplem);
				if ($this->nu_alternativaAlinhamento->Exportable) $Doc->ExportCaption($this->nu_alternativaAlinhamento);
				if ($this->nu_alternativaAbrangencia->Exportable) $Doc->ExportCaption($this->nu_alternativaAbrangencia);
				if ($this->nu_alternativaUrgencia->Exportable) $Doc->ExportCaption($this->nu_alternativaUrgencia);
				if ($this->dt_prazo->Exportable) $Doc->ExportCaption($this->dt_prazo);
				if ($this->nu_alternativaTmpEstimado->Exportable) $Doc->ExportCaption($this->nu_alternativaTmpEstimado);
				if ($this->nu_alternativaTmpFila->Exportable) $Doc->ExportCaption($this->nu_alternativaTmpFila);
				if ($this->ic_implicacaoLegal->Exportable) $Doc->ExportCaption($this->ic_implicacaoLegal);
				if ($this->ic_risco->Exportable) $Doc->ExportCaption($this->ic_risco);
				if ($this->ic_stProspecto->Exportable) $Doc->ExportCaption($this->ic_stProspecto);
				if ($this->ds_observacoes->Exportable) $Doc->ExportCaption($this->ds_observacoes);
				if ($this->ic_ativo->Exportable) $Doc->ExportCaption($this->ic_ativo);
			} else {
				if ($this->nu_prospecto->Exportable) $Doc->ExportCaption($this->nu_prospecto);
				if ($this->no_prospecto->Exportable) $Doc->ExportCaption($this->no_prospecto);
				if ($this->nu_area->Exportable) $Doc->ExportCaption($this->nu_area);
				if ($this->no_solicitante->Exportable) $Doc->ExportCaption($this->no_solicitante);
				if ($this->no_patrocinador->Exportable) $Doc->ExportCaption($this->no_patrocinador);
				if ($this->ar_entidade->Exportable) $Doc->ExportCaption($this->ar_entidade);
				if ($this->ar_nivel->Exportable) $Doc->ExportCaption($this->ar_nivel);
				if ($this->nu_categoriaProspecto->Exportable) $Doc->ExportCaption($this->nu_categoriaProspecto);
				if ($this->nu_alternativaImpacto->Exportable) $Doc->ExportCaption($this->nu_alternativaImpacto);
				if ($this->nu_alternativaAlinhamento->Exportable) $Doc->ExportCaption($this->nu_alternativaAlinhamento);
				if ($this->nu_alternativaAbrangencia->Exportable) $Doc->ExportCaption($this->nu_alternativaAbrangencia);
				if ($this->nu_alternativaUrgencia->Exportable) $Doc->ExportCaption($this->nu_alternativaUrgencia);
				if ($this->dt_prazo->Exportable) $Doc->ExportCaption($this->dt_prazo);
				if ($this->nu_alternativaTmpEstimado->Exportable) $Doc->ExportCaption($this->nu_alternativaTmpEstimado);
				if ($this->nu_alternativaTmpFila->Exportable) $Doc->ExportCaption($this->nu_alternativaTmpFila);
				if ($this->ic_implicacaoLegal->Exportable) $Doc->ExportCaption($this->ic_implicacaoLegal);
				if ($this->ic_risco->Exportable) $Doc->ExportCaption($this->ic_risco);
				if ($this->ic_stProspecto->Exportable) $Doc->ExportCaption($this->ic_stProspecto);
				if ($this->ic_ativo->Exportable) $Doc->ExportCaption($this->ic_ativo);
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
					if ($this->nu_prospecto->Exportable) $Doc->ExportField($this->nu_prospecto);
					if ($this->no_prospecto->Exportable) $Doc->ExportField($this->no_prospecto);
					if ($this->nu_area->Exportable) $Doc->ExportField($this->nu_area);
					if ($this->no_solicitante->Exportable) $Doc->ExportField($this->no_solicitante);
					if ($this->no_patrocinador->Exportable) $Doc->ExportField($this->no_patrocinador);
					if ($this->ar_entidade->Exportable) $Doc->ExportField($this->ar_entidade);
					if ($this->ar_nivel->Exportable) $Doc->ExportField($this->ar_nivel);
					if ($this->nu_categoriaProspecto->Exportable) $Doc->ExportField($this->nu_categoriaProspecto);
					if ($this->nu_alternativaImpacto->Exportable) $Doc->ExportField($this->nu_alternativaImpacto);
					if ($this->ds_sistemas->Exportable) $Doc->ExportField($this->ds_sistemas);
					if ($this->ds_impactoNaoImplem->Exportable) $Doc->ExportField($this->ds_impactoNaoImplem);
					if ($this->nu_alternativaAlinhamento->Exportable) $Doc->ExportField($this->nu_alternativaAlinhamento);
					if ($this->nu_alternativaAbrangencia->Exportable) $Doc->ExportField($this->nu_alternativaAbrangencia);
					if ($this->nu_alternativaUrgencia->Exportable) $Doc->ExportField($this->nu_alternativaUrgencia);
					if ($this->dt_prazo->Exportable) $Doc->ExportField($this->dt_prazo);
					if ($this->nu_alternativaTmpEstimado->Exportable) $Doc->ExportField($this->nu_alternativaTmpEstimado);
					if ($this->nu_alternativaTmpFila->Exportable) $Doc->ExportField($this->nu_alternativaTmpFila);
					if ($this->ic_implicacaoLegal->Exportable) $Doc->ExportField($this->ic_implicacaoLegal);
					if ($this->ic_risco->Exportable) $Doc->ExportField($this->ic_risco);
					if ($this->ic_stProspecto->Exportable) $Doc->ExportField($this->ic_stProspecto);
					if ($this->ds_observacoes->Exportable) $Doc->ExportField($this->ds_observacoes);
					if ($this->ic_ativo->Exportable) $Doc->ExportField($this->ic_ativo);
				} else {
					if ($this->nu_prospecto->Exportable) $Doc->ExportField($this->nu_prospecto);
					if ($this->no_prospecto->Exportable) $Doc->ExportField($this->no_prospecto);
					if ($this->nu_area->Exportable) $Doc->ExportField($this->nu_area);
					if ($this->no_solicitante->Exportable) $Doc->ExportField($this->no_solicitante);
					if ($this->no_patrocinador->Exportable) $Doc->ExportField($this->no_patrocinador);
					if ($this->ar_entidade->Exportable) $Doc->ExportField($this->ar_entidade);
					if ($this->ar_nivel->Exportable) $Doc->ExportField($this->ar_nivel);
					if ($this->nu_categoriaProspecto->Exportable) $Doc->ExportField($this->nu_categoriaProspecto);
					if ($this->nu_alternativaImpacto->Exportable) $Doc->ExportField($this->nu_alternativaImpacto);
					if ($this->nu_alternativaAlinhamento->Exportable) $Doc->ExportField($this->nu_alternativaAlinhamento);
					if ($this->nu_alternativaAbrangencia->Exportable) $Doc->ExportField($this->nu_alternativaAbrangencia);
					if ($this->nu_alternativaUrgencia->Exportable) $Doc->ExportField($this->nu_alternativaUrgencia);
					if ($this->dt_prazo->Exportable) $Doc->ExportField($this->dt_prazo);
					if ($this->nu_alternativaTmpEstimado->Exportable) $Doc->ExportField($this->nu_alternativaTmpEstimado);
					if ($this->nu_alternativaTmpFila->Exportable) $Doc->ExportField($this->nu_alternativaTmpFila);
					if ($this->ic_implicacaoLegal->Exportable) $Doc->ExportField($this->ic_implicacaoLegal);
					if ($this->ic_risco->Exportable) $Doc->ExportField($this->ic_risco);
					if ($this->ic_stProspecto->Exportable) $Doc->ExportField($this->ic_stProspecto);
					if ($this->ic_ativo->Exportable) $Doc->ExportField($this->ic_ativo);
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
