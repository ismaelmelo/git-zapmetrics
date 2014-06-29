<?php

// Global variable for table object
$laudo = NULL;

//
// Table class for laudo
//
class claudo extends cTable {
	var $nu_solicitacao;
	var $nu_versao;
	var $ds_sobreDocumentacao;
	var $ds_sobreMetrificacao;
	var $qt_pf;
	var $qt_horas;
	var $qt_prazoMeses;
	var $qt_prazoDias;
	var $vr_contratacao;
	var $nu_usuarioResp;
	var $dt_inicioSolicitacao;
	var $dt_inicioContagem;
	var $dt_emissao;
	var $hh_emissao;
	var $ic_tamanho;
	var $ic_esforco;
	var $ic_prazo;
	var $ic_custo;
	var $ic_bloqueio;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'laudo';
		$this->TableName = 'laudo';
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

		// nu_solicitacao
		$this->nu_solicitacao = new cField('laudo', 'laudo', 'x_nu_solicitacao', 'nu_solicitacao', '[nu_solicitacao]', 'CAST([nu_solicitacao] AS NVARCHAR)', 3, -1, FALSE, '[nu_solicitacao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_solicitacao->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_solicitacao'] = &$this->nu_solicitacao;

		// nu_versao
		$this->nu_versao = new cField('laudo', 'laudo', 'x_nu_versao', 'nu_versao', '[nu_versao]', 'CAST([nu_versao] AS NVARCHAR)', 3, -1, FALSE, '[nu_versao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_versao->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_versao'] = &$this->nu_versao;

		// ds_sobreDocumentacao
		$this->ds_sobreDocumentacao = new cField('laudo', 'laudo', 'x_ds_sobreDocumentacao', 'ds_sobreDocumentacao', '[ds_sobreDocumentacao]', '[ds_sobreDocumentacao]', 201, -1, FALSE, '[ds_sobreDocumentacao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_sobreDocumentacao'] = &$this->ds_sobreDocumentacao;

		// ds_sobreMetrificacao
		$this->ds_sobreMetrificacao = new cField('laudo', 'laudo', 'x_ds_sobreMetrificacao', 'ds_sobreMetrificacao', '[ds_sobreMetrificacao]', '[ds_sobreMetrificacao]', 201, -1, FALSE, '[ds_sobreMetrificacao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_sobreMetrificacao'] = &$this->ds_sobreMetrificacao;

		// qt_pf
		$this->qt_pf = new cField('laudo', 'laudo', 'x_qt_pf', 'qt_pf', '[qt_pf]', 'CAST([qt_pf] AS NVARCHAR)', 131, -1, FALSE, '[qt_pf]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->qt_pf->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['qt_pf'] = &$this->qt_pf;

		// qt_horas
		$this->qt_horas = new cField('laudo', 'laudo', 'x_qt_horas', 'qt_horas', '[qt_horas]', 'CAST([qt_horas] AS NVARCHAR)', 131, -1, FALSE, '[qt_horas]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->qt_horas->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['qt_horas'] = &$this->qt_horas;

		// qt_prazoMeses
		$this->qt_prazoMeses = new cField('laudo', 'laudo', 'x_qt_prazoMeses', 'qt_prazoMeses', '[qt_prazoMeses]', 'CAST([qt_prazoMeses] AS NVARCHAR)', 131, -1, FALSE, '[qt_prazoMeses]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->qt_prazoMeses->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['qt_prazoMeses'] = &$this->qt_prazoMeses;

		// qt_prazoDias
		$this->qt_prazoDias = new cField('laudo', 'laudo', 'x_qt_prazoDias', 'qt_prazoDias', '[qt_prazoDias]', 'CAST([qt_prazoDias] AS NVARCHAR)', 3, -1, FALSE, '[qt_prazoDias]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->qt_prazoDias->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['qt_prazoDias'] = &$this->qt_prazoDias;

		// vr_contratacao
		$this->vr_contratacao = new cField('laudo', 'laudo', 'x_vr_contratacao', 'vr_contratacao', '[vr_contratacao]', 'CAST([vr_contratacao] AS NVARCHAR)', 131, -1, FALSE, '[vr_contratacao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->vr_contratacao->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['vr_contratacao'] = &$this->vr_contratacao;

		// nu_usuarioResp
		$this->nu_usuarioResp = new cField('laudo', 'laudo', 'x_nu_usuarioResp', 'nu_usuarioResp', '[nu_usuarioResp]', 'CAST([nu_usuarioResp] AS NVARCHAR)', 3, -1, FALSE, '[nu_usuarioResp]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_usuarioResp->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_usuarioResp'] = &$this->nu_usuarioResp;

		// dt_inicioSolicitacao
		$this->dt_inicioSolicitacao = new cField('laudo', 'laudo', 'x_dt_inicioSolicitacao', 'dt_inicioSolicitacao', '[dt_inicioSolicitacao]', '(REPLACE(STR(DAY([dt_inicioSolicitacao]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dt_inicioSolicitacao]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dt_inicioSolicitacao]),4,0))', 133, 7, FALSE, '[dt_inicioSolicitacao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dt_inicioSolicitacao->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dt_inicioSolicitacao'] = &$this->dt_inicioSolicitacao;

		// dt_inicioContagem
		$this->dt_inicioContagem = new cField('laudo', 'laudo', 'x_dt_inicioContagem', 'dt_inicioContagem', '[dt_inicioContagem]', '(REPLACE(STR(DAY([dt_inicioContagem]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dt_inicioContagem]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dt_inicioContagem]),4,0))', 133, 7, FALSE, '[dt_inicioContagem]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dt_inicioContagem->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dt_inicioContagem'] = &$this->dt_inicioContagem;

		// dt_emissao
		$this->dt_emissao = new cField('laudo', 'laudo', 'x_dt_emissao', 'dt_emissao', '[dt_emissao]', '(REPLACE(STR(DAY([dt_emissao]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dt_emissao]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dt_emissao]),4,0))', 135, 7, FALSE, '[dt_emissao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dt_emissao->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dt_emissao'] = &$this->dt_emissao;

		// hh_emissao
		$this->hh_emissao = new cField('laudo', 'laudo', 'x_hh_emissao', 'hh_emissao', '[hh_emissao]', 'CAST([hh_emissao] AS NVARCHAR)', 3, 4, FALSE, '[hh_emissao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->hh_emissao->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['hh_emissao'] = &$this->hh_emissao;

		// ic_tamanho
		$this->ic_tamanho = new cField('laudo', 'laudo', 'x_ic_tamanho', 'ic_tamanho', '[ic_tamanho]', '[ic_tamanho]', 129, -1, FALSE, '[ic_tamanho]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_tamanho'] = &$this->ic_tamanho;

		// ic_esforco
		$this->ic_esforco = new cField('laudo', 'laudo', 'x_ic_esforco', 'ic_esforco', '[ic_esforco]', '[ic_esforco]', 129, -1, FALSE, '[ic_esforco]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_esforco'] = &$this->ic_esforco;

		// ic_prazo
		$this->ic_prazo = new cField('laudo', 'laudo', 'x_ic_prazo', 'ic_prazo', '[ic_prazo]', '[ic_prazo]', 129, -1, FALSE, '[ic_prazo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_prazo'] = &$this->ic_prazo;

		// ic_custo
		$this->ic_custo = new cField('laudo', 'laudo', 'x_ic_custo', 'ic_custo', '[ic_custo]', '[ic_custo]', 129, -1, FALSE, '[ic_custo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_custo'] = &$this->ic_custo;

		// ic_bloqueio
		$this->ic_bloqueio = new cField('laudo', 'laudo', 'x_ic_bloqueio', 'ic_bloqueio', '[ic_bloqueio]', '[ic_bloqueio]', 129, -1, FALSE, '[ic_bloqueio]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_bloqueio'] = &$this->ic_bloqueio;
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
		if ($this->getCurrentMasterTable() == "solicitacaoMetricas") {
			if ($this->nu_solicitacao->getSessionValue() <> "")
				$sMasterFilter .= "[nu_solMetricas]=" . ew_QuotedValue($this->nu_solicitacao->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "solicitacaoMetricas") {
			if ($this->nu_solicitacao->getSessionValue() <> "")
				$sDetailFilter .= "[nu_solicitacao]=" . ew_QuotedValue($this->nu_solicitacao->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_solicitacaoMetricas() {
		return "[nu_solMetricas]=@nu_solMetricas@";
	}

	// Detail filter
	function SqlDetailFilter_solicitacaoMetricas() {
		return "[nu_solicitacao]=@nu_solicitacao@";
	}

	// Table level SQL
	function SqlFrom() { // From
		return "[dbo].[laudo]";
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
		return "[nu_solicitacao] DESC,[nu_versao] DESC";
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
	var $UpdateTable = "[dbo].[laudo]";

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
			if (array_key_exists('nu_solicitacao', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_solicitacao') . '=' . ew_QuotedValue($rs['nu_solicitacao'], $this->nu_solicitacao->FldDataType));
			if (array_key_exists('nu_versao', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_versao') . '=' . ew_QuotedValue($rs['nu_versao'], $this->nu_versao->FldDataType));
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
		return "[nu_solicitacao] = @nu_solicitacao@ AND [nu_versao] = @nu_versao@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nu_solicitacao->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_solicitacao@", ew_AdjustSql($this->nu_solicitacao->CurrentValue), $sKeyFilter); // Replace key value
		if (!is_numeric($this->nu_versao->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_versao@", ew_AdjustSql($this->nu_versao->CurrentValue), $sKeyFilter); // Replace key value
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
			return "laudolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "laudolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("laudoview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("laudoview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "laudoadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("laudoedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("laudoadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("laudodelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nu_solicitacao->CurrentValue)) {
			$sUrl .= "nu_solicitacao=" . urlencode($this->nu_solicitacao->CurrentValue);
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
			$arKey[] = @$_GET["nu_solicitacao"]; // nu_solicitacao
			$arKey[] = @$_GET["nu_versao"]; // nu_versao
			$arKeys[] = $arKey;

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_array($key) || count($key) <> 2)
				continue; // Just skip so other keys will still work
			if (!is_numeric($key[0])) // nu_solicitacao
				continue;
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
			$this->nu_solicitacao->CurrentValue = $key[0];
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

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->nu_solicitacao->setDbValue($rs->fields('nu_solicitacao'));
		$this->nu_versao->setDbValue($rs->fields('nu_versao'));
		$this->ds_sobreDocumentacao->setDbValue($rs->fields('ds_sobreDocumentacao'));
		$this->ds_sobreMetrificacao->setDbValue($rs->fields('ds_sobreMetrificacao'));
		$this->qt_pf->setDbValue($rs->fields('qt_pf'));
		$this->qt_horas->setDbValue($rs->fields('qt_horas'));
		$this->qt_prazoMeses->setDbValue($rs->fields('qt_prazoMeses'));
		$this->qt_prazoDias->setDbValue($rs->fields('qt_prazoDias'));
		$this->vr_contratacao->setDbValue($rs->fields('vr_contratacao'));
		$this->nu_usuarioResp->setDbValue($rs->fields('nu_usuarioResp'));
		$this->dt_inicioSolicitacao->setDbValue($rs->fields('dt_inicioSolicitacao'));
		$this->dt_inicioContagem->setDbValue($rs->fields('dt_inicioContagem'));
		$this->dt_emissao->setDbValue($rs->fields('dt_emissao'));
		$this->hh_emissao->setDbValue($rs->fields('hh_emissao'));
		$this->ic_tamanho->setDbValue($rs->fields('ic_tamanho'));
		$this->ic_esforco->setDbValue($rs->fields('ic_esforco'));
		$this->ic_prazo->setDbValue($rs->fields('ic_prazo'));
		$this->ic_custo->setDbValue($rs->fields('ic_custo'));
		$this->ic_bloqueio->setDbValue($rs->fields('ic_bloqueio'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_solicitacao
		// nu_versao
		// ds_sobreDocumentacao
		// ds_sobreMetrificacao
		// qt_pf
		// qt_horas
		// qt_prazoMeses
		// qt_prazoDias
		// vr_contratacao
		// nu_usuarioResp
		// dt_inicioSolicitacao
		// dt_inicioContagem
		// dt_emissao
		// hh_emissao
		// ic_tamanho
		// ic_esforco
		// ic_prazo
		// ic_custo
		// ic_bloqueio

		$this->ic_bloqueio->CellCssStyle = "white-space: nowrap;";

		// nu_solicitacao
		if (strval($this->nu_solicitacao->CurrentValue) <> "") {
			$sFilterWrk = "[nu_solMetricas]" . ew_SearchString("=", $this->nu_solicitacao->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_solMetricas], [nu_solMetricas] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[solicitacaoMetricas]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_solicitacao, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_solMetricas] DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_solicitacao->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_solicitacao->ViewValue = $this->nu_solicitacao->CurrentValue;
			}
		} else {
			$this->nu_solicitacao->ViewValue = NULL;
		}
		$this->nu_solicitacao->ViewCustomAttributes = "";

		// nu_versao
		$this->nu_versao->ViewValue = $this->nu_versao->CurrentValue;
		$this->nu_versao->ViewCustomAttributes = "";

		// ds_sobreDocumentacao
		$this->ds_sobreDocumentacao->ViewValue = $this->ds_sobreDocumentacao->CurrentValue;
		$this->ds_sobreDocumentacao->ViewCustomAttributes = "";

		// ds_sobreMetrificacao
		$this->ds_sobreMetrificacao->ViewValue = $this->ds_sobreMetrificacao->CurrentValue;
		$this->ds_sobreMetrificacao->ViewCustomAttributes = "";

		// qt_pf
		$this->qt_pf->ViewValue = $this->qt_pf->CurrentValue;
		$this->qt_pf->ViewCustomAttributes = "";

		// qt_horas
		$this->qt_horas->ViewValue = $this->qt_horas->CurrentValue;
		$this->qt_horas->ViewCustomAttributes = "";

		// qt_prazoMeses
		$this->qt_prazoMeses->ViewValue = $this->qt_prazoMeses->CurrentValue;
		$this->qt_prazoMeses->ViewCustomAttributes = "";

		// qt_prazoDias
		$this->qt_prazoDias->ViewValue = $this->qt_prazoDias->CurrentValue;
		$this->qt_prazoDias->ViewCustomAttributes = "";

		// vr_contratacao
		$this->vr_contratacao->ViewValue = $this->vr_contratacao->CurrentValue;
		$this->vr_contratacao->ViewValue = ew_FormatCurrency($this->vr_contratacao->ViewValue, 2, -2, -2, -2);
		$this->vr_contratacao->ViewCustomAttributes = "";

		// nu_usuarioResp
		if (strval($this->nu_usuarioResp->CurrentValue) <> "") {
			$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioResp->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_usuarioResp, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_usuario] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_usuarioResp->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_usuarioResp->ViewValue = $this->nu_usuarioResp->CurrentValue;
			}
		} else {
			$this->nu_usuarioResp->ViewValue = NULL;
		}
		$this->nu_usuarioResp->ViewCustomAttributes = "";

		// dt_inicioSolicitacao
		$this->dt_inicioSolicitacao->ViewValue = $this->dt_inicioSolicitacao->CurrentValue;
		$this->dt_inicioSolicitacao->ViewValue = ew_FormatDateTime($this->dt_inicioSolicitacao->ViewValue, 7);
		$this->dt_inicioSolicitacao->ViewCustomAttributes = "";

		// dt_inicioContagem
		$this->dt_inicioContagem->ViewValue = $this->dt_inicioContagem->CurrentValue;
		$this->dt_inicioContagem->ViewValue = ew_FormatDateTime($this->dt_inicioContagem->ViewValue, 7);
		$this->dt_inicioContagem->ViewCustomAttributes = "";

		// dt_emissao
		$this->dt_emissao->ViewValue = $this->dt_emissao->CurrentValue;
		$this->dt_emissao->ViewValue = ew_FormatDateTime($this->dt_emissao->ViewValue, 7);
		$this->dt_emissao->ViewCustomAttributes = "";

		// hh_emissao
		$this->hh_emissao->ViewValue = $this->hh_emissao->CurrentValue;
		$this->hh_emissao->ViewValue = ew_FormatDateTime($this->hh_emissao->ViewValue, 4);
		$this->hh_emissao->ViewCustomAttributes = "";

		// ic_tamanho
		if (strval($this->ic_tamanho->CurrentValue) <> "") {
			switch ($this->ic_tamanho->CurrentValue) {
				case $this->ic_tamanho->FldTagValue(1):
					$this->ic_tamanho->ViewValue = $this->ic_tamanho->FldTagCaption(1) <> "" ? $this->ic_tamanho->FldTagCaption(1) : $this->ic_tamanho->CurrentValue;
					break;
				case $this->ic_tamanho->FldTagValue(2):
					$this->ic_tamanho->ViewValue = $this->ic_tamanho->FldTagCaption(2) <> "" ? $this->ic_tamanho->FldTagCaption(2) : $this->ic_tamanho->CurrentValue;
					break;
				default:
					$this->ic_tamanho->ViewValue = $this->ic_tamanho->CurrentValue;
			}
		} else {
			$this->ic_tamanho->ViewValue = NULL;
		}
		$this->ic_tamanho->ViewCustomAttributes = "";

		// ic_esforco
		if (strval($this->ic_esforco->CurrentValue) <> "") {
			switch ($this->ic_esforco->CurrentValue) {
				case $this->ic_esforco->FldTagValue(1):
					$this->ic_esforco->ViewValue = $this->ic_esforco->FldTagCaption(1) <> "" ? $this->ic_esforco->FldTagCaption(1) : $this->ic_esforco->CurrentValue;
					break;
				case $this->ic_esforco->FldTagValue(2):
					$this->ic_esforco->ViewValue = $this->ic_esforco->FldTagCaption(2) <> "" ? $this->ic_esforco->FldTagCaption(2) : $this->ic_esforco->CurrentValue;
					break;
				default:
					$this->ic_esforco->ViewValue = $this->ic_esforco->CurrentValue;
			}
		} else {
			$this->ic_esforco->ViewValue = NULL;
		}
		$this->ic_esforco->ViewCustomAttributes = "";

		// ic_prazo
		if (strval($this->ic_prazo->CurrentValue) <> "") {
			switch ($this->ic_prazo->CurrentValue) {
				case $this->ic_prazo->FldTagValue(1):
					$this->ic_prazo->ViewValue = $this->ic_prazo->FldTagCaption(1) <> "" ? $this->ic_prazo->FldTagCaption(1) : $this->ic_prazo->CurrentValue;
					break;
				case $this->ic_prazo->FldTagValue(2):
					$this->ic_prazo->ViewValue = $this->ic_prazo->FldTagCaption(2) <> "" ? $this->ic_prazo->FldTagCaption(2) : $this->ic_prazo->CurrentValue;
					break;
				default:
					$this->ic_prazo->ViewValue = $this->ic_prazo->CurrentValue;
			}
		} else {
			$this->ic_prazo->ViewValue = NULL;
		}
		$this->ic_prazo->ViewCustomAttributes = "";

		// ic_custo
		if (strval($this->ic_custo->CurrentValue) <> "") {
			switch ($this->ic_custo->CurrentValue) {
				case $this->ic_custo->FldTagValue(1):
					$this->ic_custo->ViewValue = $this->ic_custo->FldTagCaption(1) <> "" ? $this->ic_custo->FldTagCaption(1) : $this->ic_custo->CurrentValue;
					break;
				case $this->ic_custo->FldTagValue(2):
					$this->ic_custo->ViewValue = $this->ic_custo->FldTagCaption(2) <> "" ? $this->ic_custo->FldTagCaption(2) : $this->ic_custo->CurrentValue;
					break;
				default:
					$this->ic_custo->ViewValue = $this->ic_custo->CurrentValue;
			}
		} else {
			$this->ic_custo->ViewValue = NULL;
		}
		$this->ic_custo->ViewCustomAttributes = "";

		// ic_bloqueio
		$this->ic_bloqueio->ViewValue = $this->ic_bloqueio->CurrentValue;
		$this->ic_bloqueio->ViewCustomAttributes = "";

		// nu_solicitacao
		$this->nu_solicitacao->LinkCustomAttributes = "";
		$this->nu_solicitacao->HrefValue = "";
		$this->nu_solicitacao->TooltipValue = "";

		// nu_versao
		$this->nu_versao->LinkCustomAttributes = "";
		$this->nu_versao->HrefValue = "";
		$this->nu_versao->TooltipValue = "";

		// ds_sobreDocumentacao
		$this->ds_sobreDocumentacao->LinkCustomAttributes = "";
		$this->ds_sobreDocumentacao->HrefValue = "";
		$this->ds_sobreDocumentacao->TooltipValue = "";

		// ds_sobreMetrificacao
		$this->ds_sobreMetrificacao->LinkCustomAttributes = "";
		$this->ds_sobreMetrificacao->HrefValue = "";
		$this->ds_sobreMetrificacao->TooltipValue = "";

		// qt_pf
		$this->qt_pf->LinkCustomAttributes = "";
		$this->qt_pf->HrefValue = "";
		$this->qt_pf->TooltipValue = "";

		// qt_horas
		$this->qt_horas->LinkCustomAttributes = "";
		$this->qt_horas->HrefValue = "";
		$this->qt_horas->TooltipValue = "";

		// qt_prazoMeses
		$this->qt_prazoMeses->LinkCustomAttributes = "";
		$this->qt_prazoMeses->HrefValue = "";
		$this->qt_prazoMeses->TooltipValue = "";

		// qt_prazoDias
		$this->qt_prazoDias->LinkCustomAttributes = "";
		$this->qt_prazoDias->HrefValue = "";
		$this->qt_prazoDias->TooltipValue = "";

		// vr_contratacao
		$this->vr_contratacao->LinkCustomAttributes = "";
		$this->vr_contratacao->HrefValue = "";
		$this->vr_contratacao->TooltipValue = "";

		// nu_usuarioResp
		$this->nu_usuarioResp->LinkCustomAttributes = "";
		$this->nu_usuarioResp->HrefValue = "";
		$this->nu_usuarioResp->TooltipValue = "";

		// dt_inicioSolicitacao
		$this->dt_inicioSolicitacao->LinkCustomAttributes = "";
		$this->dt_inicioSolicitacao->HrefValue = "";
		$this->dt_inicioSolicitacao->TooltipValue = "";

		// dt_inicioContagem
		$this->dt_inicioContagem->LinkCustomAttributes = "";
		$this->dt_inicioContagem->HrefValue = "";
		$this->dt_inicioContagem->TooltipValue = "";

		// dt_emissao
		$this->dt_emissao->LinkCustomAttributes = "";
		$this->dt_emissao->HrefValue = "";
		$this->dt_emissao->TooltipValue = "";

		// hh_emissao
		$this->hh_emissao->LinkCustomAttributes = "";
		$this->hh_emissao->HrefValue = "";
		$this->hh_emissao->TooltipValue = "";

		// ic_tamanho
		$this->ic_tamanho->LinkCustomAttributes = "";
		$this->ic_tamanho->HrefValue = "";
		$this->ic_tamanho->TooltipValue = "";

		// ic_esforco
		$this->ic_esforco->LinkCustomAttributes = "";
		$this->ic_esforco->HrefValue = "";
		$this->ic_esforco->TooltipValue = "";

		// ic_prazo
		$this->ic_prazo->LinkCustomAttributes = "";
		$this->ic_prazo->HrefValue = "";
		$this->ic_prazo->TooltipValue = "";

		// ic_custo
		$this->ic_custo->LinkCustomAttributes = "";
		$this->ic_custo->HrefValue = "";
		$this->ic_custo->TooltipValue = "";

		// ic_bloqueio
		$this->ic_bloqueio->LinkCustomAttributes = "";
		$this->ic_bloqueio->HrefValue = "";
		$this->ic_bloqueio->TooltipValue = "";

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
				if ($this->nu_solicitacao->Exportable) $Doc->ExportCaption($this->nu_solicitacao);
				if ($this->nu_versao->Exportable) $Doc->ExportCaption($this->nu_versao);
				if ($this->ds_sobreDocumentacao->Exportable) $Doc->ExportCaption($this->ds_sobreDocumentacao);
				if ($this->ds_sobreMetrificacao->Exportable) $Doc->ExportCaption($this->ds_sobreMetrificacao);
				if ($this->qt_pf->Exportable) $Doc->ExportCaption($this->qt_pf);
				if ($this->qt_horas->Exportable) $Doc->ExportCaption($this->qt_horas);
				if ($this->qt_prazoMeses->Exportable) $Doc->ExportCaption($this->qt_prazoMeses);
				if ($this->qt_prazoDias->Exportable) $Doc->ExportCaption($this->qt_prazoDias);
				if ($this->vr_contratacao->Exportable) $Doc->ExportCaption($this->vr_contratacao);
				if ($this->nu_usuarioResp->Exportable) $Doc->ExportCaption($this->nu_usuarioResp);
				if ($this->dt_inicioSolicitacao->Exportable) $Doc->ExportCaption($this->dt_inicioSolicitacao);
				if ($this->dt_inicioContagem->Exportable) $Doc->ExportCaption($this->dt_inicioContagem);
				if ($this->dt_emissao->Exportable) $Doc->ExportCaption($this->dt_emissao);
				if ($this->hh_emissao->Exportable) $Doc->ExportCaption($this->hh_emissao);
				if ($this->ic_tamanho->Exportable) $Doc->ExportCaption($this->ic_tamanho);
				if ($this->ic_esforco->Exportable) $Doc->ExportCaption($this->ic_esforco);
				if ($this->ic_prazo->Exportable) $Doc->ExportCaption($this->ic_prazo);
				if ($this->ic_custo->Exportable) $Doc->ExportCaption($this->ic_custo);
			} else {
				if ($this->nu_solicitacao->Exportable) $Doc->ExportCaption($this->nu_solicitacao);
				if ($this->nu_versao->Exportable) $Doc->ExportCaption($this->nu_versao);
				if ($this->qt_pf->Exportable) $Doc->ExportCaption($this->qt_pf);
				if ($this->qt_horas->Exportable) $Doc->ExportCaption($this->qt_horas);
				if ($this->qt_prazoMeses->Exportable) $Doc->ExportCaption($this->qt_prazoMeses);
				if ($this->qt_prazoDias->Exportable) $Doc->ExportCaption($this->qt_prazoDias);
				if ($this->vr_contratacao->Exportable) $Doc->ExportCaption($this->vr_contratacao);
				if ($this->nu_usuarioResp->Exportable) $Doc->ExportCaption($this->nu_usuarioResp);
				if ($this->dt_inicioSolicitacao->Exportable) $Doc->ExportCaption($this->dt_inicioSolicitacao);
				if ($this->dt_inicioContagem->Exportable) $Doc->ExportCaption($this->dt_inicioContagem);
				if ($this->dt_emissao->Exportable) $Doc->ExportCaption($this->dt_emissao);
				if ($this->hh_emissao->Exportable) $Doc->ExportCaption($this->hh_emissao);
				if ($this->ic_tamanho->Exportable) $Doc->ExportCaption($this->ic_tamanho);
				if ($this->ic_esforco->Exportable) $Doc->ExportCaption($this->ic_esforco);
				if ($this->ic_prazo->Exportable) $Doc->ExportCaption($this->ic_prazo);
				if ($this->ic_custo->Exportable) $Doc->ExportCaption($this->ic_custo);
				if ($this->ic_bloqueio->Exportable) $Doc->ExportCaption($this->ic_bloqueio);
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
					if ($this->nu_solicitacao->Exportable) $Doc->ExportField($this->nu_solicitacao);
					if ($this->nu_versao->Exportable) $Doc->ExportField($this->nu_versao);
					if ($this->ds_sobreDocumentacao->Exportable) $Doc->ExportField($this->ds_sobreDocumentacao);
					if ($this->ds_sobreMetrificacao->Exportable) $Doc->ExportField($this->ds_sobreMetrificacao);
					if ($this->qt_pf->Exportable) $Doc->ExportField($this->qt_pf);
					if ($this->qt_horas->Exportable) $Doc->ExportField($this->qt_horas);
					if ($this->qt_prazoMeses->Exportable) $Doc->ExportField($this->qt_prazoMeses);
					if ($this->qt_prazoDias->Exportable) $Doc->ExportField($this->qt_prazoDias);
					if ($this->vr_contratacao->Exportable) $Doc->ExportField($this->vr_contratacao);
					if ($this->nu_usuarioResp->Exportable) $Doc->ExportField($this->nu_usuarioResp);
					if ($this->dt_inicioSolicitacao->Exportable) $Doc->ExportField($this->dt_inicioSolicitacao);
					if ($this->dt_inicioContagem->Exportable) $Doc->ExportField($this->dt_inicioContagem);
					if ($this->dt_emissao->Exportable) $Doc->ExportField($this->dt_emissao);
					if ($this->hh_emissao->Exportable) $Doc->ExportField($this->hh_emissao);
					if ($this->ic_tamanho->Exportable) $Doc->ExportField($this->ic_tamanho);
					if ($this->ic_esforco->Exportable) $Doc->ExportField($this->ic_esforco);
					if ($this->ic_prazo->Exportable) $Doc->ExportField($this->ic_prazo);
					if ($this->ic_custo->Exportable) $Doc->ExportField($this->ic_custo);
				} else {
					if ($this->nu_solicitacao->Exportable) $Doc->ExportField($this->nu_solicitacao);
					if ($this->nu_versao->Exportable) $Doc->ExportField($this->nu_versao);
					if ($this->qt_pf->Exportable) $Doc->ExportField($this->qt_pf);
					if ($this->qt_horas->Exportable) $Doc->ExportField($this->qt_horas);
					if ($this->qt_prazoMeses->Exportable) $Doc->ExportField($this->qt_prazoMeses);
					if ($this->qt_prazoDias->Exportable) $Doc->ExportField($this->qt_prazoDias);
					if ($this->vr_contratacao->Exportable) $Doc->ExportField($this->vr_contratacao);
					if ($this->nu_usuarioResp->Exportable) $Doc->ExportField($this->nu_usuarioResp);
					if ($this->dt_inicioSolicitacao->Exportable) $Doc->ExportField($this->dt_inicioSolicitacao);
					if ($this->dt_inicioContagem->Exportable) $Doc->ExportField($this->dt_inicioContagem);
					if ($this->dt_emissao->Exportable) $Doc->ExportField($this->dt_emissao);
					if ($this->hh_emissao->Exportable) $Doc->ExportField($this->hh_emissao);
					if ($this->ic_tamanho->Exportable) $Doc->ExportField($this->ic_tamanho);
					if ($this->ic_esforco->Exportable) $Doc->ExportField($this->ic_esforco);
					if ($this->ic_prazo->Exportable) $Doc->ExportField($this->ic_prazo);
					if ($this->ic_custo->Exportable) $Doc->ExportField($this->ic_custo);
					if ($this->ic_bloqueio->Exportable) $Doc->ExportField($this->ic_bloqueio);
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
