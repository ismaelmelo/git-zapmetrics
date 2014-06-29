<?php

// Global variable for table object
$contagempf = NULL;

//
// Table class for contagempf
//
class ccontagempf extends cTable {
	var $nu_contagem;
	var $nu_solMetricas;
	var $nu_tpMetrica;
	var $nu_tpContagem;
	var $nu_proposito;
	var $nu_sistema;
	var $nu_ambiente;
	var $nu_metodologia;
	var $nu_roteiro;
	var $nu_faseMedida;
	var $nu_usuarioLogado;
	var $dh_inicio;
	var $ic_stContagem;
	var $ar_fasesRoteiro;
	var $pc_varFasesRoteiro;
	var $vr_pfFaturamento;
	var $ic_bloqueio;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'contagempf';
		$this->TableName = 'contagempf';
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
		$this->nu_contagem = new cField('contagempf', 'contagempf', 'x_nu_contagem', 'nu_contagem', '[nu_contagem]', 'CAST([nu_contagem] AS NVARCHAR)', 3, -1, FALSE, '[nu_contagem]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_contagem->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_contagem'] = &$this->nu_contagem;

		// nu_solMetricas
		$this->nu_solMetricas = new cField('contagempf', 'contagempf', 'x_nu_solMetricas', 'nu_solMetricas', '[nu_solMetricas]', 'CAST([nu_solMetricas] AS NVARCHAR)', 3, -1, FALSE, '[nu_solMetricas]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_solMetricas->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_solMetricas'] = &$this->nu_solMetricas;

		// nu_tpMetrica
		$this->nu_tpMetrica = new cField('contagempf', 'contagempf', 'x_nu_tpMetrica', 'nu_tpMetrica', '[nu_tpMetrica]', 'CAST([nu_tpMetrica] AS NVARCHAR)', 3, -1, FALSE, '[nu_tpMetrica]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_tpMetrica->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_tpMetrica'] = &$this->nu_tpMetrica;

		// nu_tpContagem
		$this->nu_tpContagem = new cField('contagempf', 'contagempf', 'x_nu_tpContagem', 'nu_tpContagem', '[nu_tpContagem]', 'CAST([nu_tpContagem] AS NVARCHAR)', 3, -1, FALSE, '[nu_tpContagem]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_tpContagem->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_tpContagem'] = &$this->nu_tpContagem;

		// nu_proposito
		$this->nu_proposito = new cField('contagempf', 'contagempf', 'x_nu_proposito', 'nu_proposito', '[nu_proposito]', 'CAST([nu_proposito] AS NVARCHAR)', 3, -1, FALSE, '[nu_proposito]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_proposito->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_proposito'] = &$this->nu_proposito;

		// nu_sistema
		$this->nu_sistema = new cField('contagempf', 'contagempf', 'x_nu_sistema', 'nu_sistema', '[nu_sistema]', 'CAST([nu_sistema] AS NVARCHAR)', 3, -1, FALSE, '[nu_sistema]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_sistema->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_sistema'] = &$this->nu_sistema;

		// nu_ambiente
		$this->nu_ambiente = new cField('contagempf', 'contagempf', 'x_nu_ambiente', 'nu_ambiente', '[nu_ambiente]', 'CAST([nu_ambiente] AS NVARCHAR)', 3, -1, FALSE, '[nu_ambiente]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_ambiente->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_ambiente'] = &$this->nu_ambiente;

		// nu_metodologia
		$this->nu_metodologia = new cField('contagempf', 'contagempf', 'x_nu_metodologia', 'nu_metodologia', '[nu_metodologia]', 'CAST([nu_metodologia] AS NVARCHAR)', 3, -1, FALSE, '[nu_metodologia]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_metodologia->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_metodologia'] = &$this->nu_metodologia;

		// nu_roteiro
		$this->nu_roteiro = new cField('contagempf', 'contagempf', 'x_nu_roteiro', 'nu_roteiro', '[nu_roteiro]', 'CAST([nu_roteiro] AS NVARCHAR)', 3, -1, FALSE, '[nu_roteiro]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_roteiro->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_roteiro'] = &$this->nu_roteiro;

		// nu_faseMedida
		$this->nu_faseMedida = new cField('contagempf', 'contagempf', 'x_nu_faseMedida', 'nu_faseMedida', '[nu_faseMedida]', 'CAST([nu_faseMedida] AS NVARCHAR)', 3, -1, FALSE, '[nu_faseMedida]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_faseMedida->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_faseMedida'] = &$this->nu_faseMedida;

		// nu_usuarioLogado
		$this->nu_usuarioLogado = new cField('contagempf', 'contagempf', 'x_nu_usuarioLogado', 'nu_usuarioLogado', '[nu_usuarioLogado]', 'CAST([nu_usuarioLogado] AS NVARCHAR)', 3, -1, FALSE, '[nu_usuarioLogado]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_usuarioLogado->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_usuarioLogado'] = &$this->nu_usuarioLogado;

		// dh_inicio
		$this->dh_inicio = new cField('contagempf', 'contagempf', 'x_dh_inicio', 'dh_inicio', '[dh_inicio]', '(REPLACE(STR(DAY([dh_inicio]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dh_inicio]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dh_inicio]),4,0))', 135, 7, FALSE, '[dh_inicio]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dh_inicio->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dh_inicio'] = &$this->dh_inicio;

		// ic_stContagem
		$this->ic_stContagem = new cField('contagempf', 'contagempf', 'x_ic_stContagem', 'ic_stContagem', '[ic_stContagem]', '[ic_stContagem]', 129, -1, FALSE, '[ic_stContagem]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_stContagem'] = &$this->ic_stContagem;

		// ar_fasesRoteiro
		$this->ar_fasesRoteiro = new cField('contagempf', 'contagempf', 'x_ar_fasesRoteiro', 'ar_fasesRoteiro', '[ar_fasesRoteiro]', '[ar_fasesRoteiro]', 200, -1, FALSE, '[ar_fasesRoteiro]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ar_fasesRoteiro'] = &$this->ar_fasesRoteiro;

		// pc_varFasesRoteiro
		$this->pc_varFasesRoteiro = new cField('contagempf', 'contagempf', 'x_pc_varFasesRoteiro', 'pc_varFasesRoteiro', '[pc_varFasesRoteiro]', 'CAST([pc_varFasesRoteiro] AS NVARCHAR)', 131, -1, FALSE, '[pc_varFasesRoteiro]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->pc_varFasesRoteiro->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['pc_varFasesRoteiro'] = &$this->pc_varFasesRoteiro;

		// vr_pfFaturamento
		$this->vr_pfFaturamento = new cField('contagempf', 'contagempf', 'x_vr_pfFaturamento', 'vr_pfFaturamento', '[vr_pfFaturamento]', 'CAST([vr_pfFaturamento] AS NVARCHAR)', 131, -1, FALSE, '[vr_pfFaturamento]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->vr_pfFaturamento->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['vr_pfFaturamento'] = &$this->vr_pfFaturamento;

		// ic_bloqueio
		$this->ic_bloqueio = new cField('contagempf', 'contagempf', 'x_ic_bloqueio', 'ic_bloqueio', '[ic_bloqueio]', '[ic_bloqueio]', 129, -1, FALSE, '[ic_bloqueio]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
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
			if ($this->nu_solMetricas->getSessionValue() <> "")
				$sMasterFilter .= "[nu_solMetricas]=" . ew_QuotedValue($this->nu_solMetricas->getSessionValue(), EW_DATATYPE_NUMBER);
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
			if ($this->nu_solMetricas->getSessionValue() <> "")
				$sDetailFilter .= "[nu_solMetricas]=" . ew_QuotedValue($this->nu_solMetricas->getSessionValue(), EW_DATATYPE_NUMBER);
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
		return "[nu_solMetricas]=@nu_solMetricas@";
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
		if ($this->getCurrentDetailTable() == "contagempf_agrupador") {
			$sDetailUrl = $GLOBALS["contagempf_agrupador"]->GetListUrl() . "?showmaster=" . $this->TableVar;
			$sDetailUrl .= "&nu_contagem=" . $this->nu_contagem->CurrentValue;
		}
		if ($this->getCurrentDetailTable() == "contagempf_funcao") {
			$sDetailUrl = $GLOBALS["contagempf_funcao"]->GetListUrl() . "?showmaster=" . $this->TableVar;
			$sDetailUrl .= "&nu_contagem=" . $this->nu_contagem->CurrentValue;
		}
		if ($sDetailUrl == "") {
			$sDetailUrl = "contagempflist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	function SqlFrom() { // From
		return "[dbo].[contagempf]";
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
	var $UpdateTable = "[dbo].[contagempf]";

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
			if (array_key_exists('nu_contagem', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_contagem') . '=' . ew_QuotedValue($rs['nu_contagem'], $this->nu_contagem->FldDataType));
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
		return "[nu_contagem] = @nu_contagem@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nu_contagem->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_contagem@", ew_AdjustSql($this->nu_contagem->CurrentValue), $sKeyFilter); // Replace key value
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
			return "contagempflist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "contagempflist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("contagempfview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("contagempfview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "contagempfadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("contagempfedit.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("contagempfedit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("contagempfadd.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("contagempfadd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("contagempfdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nu_contagem->CurrentValue)) {
			$sUrl .= "nu_contagem=" . urlencode($this->nu_contagem->CurrentValue);
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
			$arKeys[] = @$_GET["nu_contagem"]; // nu_contagem

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
			$this->nu_contagem->CurrentValue = $key;
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
		$this->nu_solMetricas->setDbValue($rs->fields('nu_solMetricas'));
		$this->nu_tpMetrica->setDbValue($rs->fields('nu_tpMetrica'));
		$this->nu_tpContagem->setDbValue($rs->fields('nu_tpContagem'));
		$this->nu_proposito->setDbValue($rs->fields('nu_proposito'));
		$this->nu_sistema->setDbValue($rs->fields('nu_sistema'));
		$this->nu_ambiente->setDbValue($rs->fields('nu_ambiente'));
		$this->nu_metodologia->setDbValue($rs->fields('nu_metodologia'));
		$this->nu_roteiro->setDbValue($rs->fields('nu_roteiro'));
		$this->nu_faseMedida->setDbValue($rs->fields('nu_faseMedida'));
		$this->nu_usuarioLogado->setDbValue($rs->fields('nu_usuarioLogado'));
		$this->dh_inicio->setDbValue($rs->fields('dh_inicio'));
		$this->ic_stContagem->setDbValue($rs->fields('ic_stContagem'));
		$this->ar_fasesRoteiro->setDbValue($rs->fields('ar_fasesRoteiro'));
		$this->pc_varFasesRoteiro->setDbValue($rs->fields('pc_varFasesRoteiro'));
		$this->vr_pfFaturamento->setDbValue($rs->fields('vr_pfFaturamento'));
		$this->ic_bloqueio->setDbValue($rs->fields('ic_bloqueio'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_contagem
		// nu_solMetricas
		// nu_tpMetrica
		// nu_tpContagem
		// nu_proposito
		// nu_sistema
		// nu_ambiente
		// nu_metodologia
		// nu_roteiro
		// nu_faseMedida
		// nu_usuarioLogado
		// dh_inicio
		// ic_stContagem
		// ar_fasesRoteiro
		// pc_varFasesRoteiro
		// vr_pfFaturamento
		// ic_bloqueio

		$this->ic_bloqueio->CellCssStyle = "white-space: nowrap;";

		// nu_contagem
		$this->nu_contagem->ViewValue = $this->nu_contagem->CurrentValue;
		$this->nu_contagem->ViewCustomAttributes = "";

		// nu_solMetricas
		if (strval($this->nu_solMetricas->CurrentValue) <> "") {
			$sFilterWrk = "[nu_solMetricas]" . ew_SearchString("=", $this->nu_solMetricas->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_solMetricas], [nu_solMetricas] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[solicitacaoMetricas]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_solMetricas, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_solMetricas] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_solMetricas->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_solMetricas->ViewValue = $this->nu_solMetricas->CurrentValue;
			}
		} else {
			$this->nu_solMetricas->ViewValue = NULL;
		}
		$this->nu_solMetricas->ViewCustomAttributes = "";

		// nu_tpMetrica
		if (strval($this->nu_tpMetrica->CurrentValue) <> "") {
			$sFilterWrk = "[nu_tpMetrica]" . ew_SearchString("=", $this->nu_tpMetrica->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_tpMetrica], [no_tpMetrica] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpmetrica]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo] = 'S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_tpMetrica, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_tpMetrica] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_tpMetrica->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_tpMetrica->ViewValue = $this->nu_tpMetrica->CurrentValue;
			}
		} else {
			$this->nu_tpMetrica->ViewValue = NULL;
		}
		$this->nu_tpMetrica->ViewCustomAttributes = "";

		// nu_tpContagem
		if (strval($this->nu_tpContagem->CurrentValue) <> "") {
			$sFilterWrk = "[nu_tpContagem]" . ew_SearchString("=", $this->nu_tpContagem->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_tpContagem], [no_tpContagem] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpcontagem]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_tpContagem, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_tpContagem] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_tpContagem->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_tpContagem->ViewValue = $this->nu_tpContagem->CurrentValue;
			}
		} else {
			$this->nu_tpContagem->ViewValue = NULL;
		}
		$this->nu_tpContagem->ViewCustomAttributes = "";

		// nu_proposito
		if (strval($this->nu_proposito->CurrentValue) <> "") {
			$sFilterWrk = "[nu_proposito]" . ew_SearchString("=", $this->nu_proposito->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_proposito], [no_proposito] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[proposito]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_proposito, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_proposito] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_proposito->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_proposito->ViewValue = $this->nu_proposito->CurrentValue;
			}
		} else {
			$this->nu_proposito->ViewValue = NULL;
		}
		$this->nu_proposito->ViewCustomAttributes = "";

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
		$sSqlWrk .= " ORDER BY [co_alternativo] ASC";
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

		// nu_ambiente
		if (strval($this->nu_ambiente->CurrentValue) <> "") {
			$sFilterWrk = "[nu_ambiente]" . ew_SearchString("=", $this->nu_ambiente->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_ambiente], [no_ambiente] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ambiente]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_ambiente, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_ambiente] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_ambiente->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_ambiente->ViewValue = $this->nu_ambiente->CurrentValue;
			}
		} else {
			$this->nu_ambiente->ViewValue = NULL;
		}
		$this->nu_ambiente->ViewCustomAttributes = "";

		// nu_metodologia
		if (strval($this->nu_metodologia->CurrentValue) <> "") {
			$sFilterWrk = "[nu_metodologia]" . ew_SearchString("=", $this->nu_metodologia->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_metodologia], [no_metodologia] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[metodologia]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_metodologia, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_metodologia] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_metodologia->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_metodologia->ViewValue = $this->nu_metodologia->CurrentValue;
			}
		} else {
			$this->nu_metodologia->ViewValue = NULL;
		}
		$this->nu_metodologia->ViewCustomAttributes = "";

		// nu_roteiro
		if (strval($this->nu_roteiro->CurrentValue) <> "") {
			$sFilterWrk = "[nu_roteiro]" . ew_SearchString("=", $this->nu_roteiro->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_roteiro], [no_roteiro] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[roteiro]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_roteiro, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_roteiro] ASC";
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

		// nu_faseMedida
		if (strval($this->nu_faseMedida->CurrentValue) <> "") {
			$sFilterWrk = "[nu_faseRoteiro]" . ew_SearchString("=", $this->nu_faseMedida->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_faseRoteiro], [no_faseRoteiro] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[faseroteiro]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_faseMedida, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_faseMedida->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_faseMedida->ViewValue = $this->nu_faseMedida->CurrentValue;
			}
		} else {
			$this->nu_faseMedida->ViewValue = NULL;
		}
		$this->nu_faseMedida->ViewCustomAttributes = "";

		// nu_usuarioLogado
		if (strval($this->nu_usuarioLogado->CurrentValue) <> "") {
			$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioLogado->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_usuarioLogado, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_usuario] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_usuarioLogado->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_usuarioLogado->ViewValue = $this->nu_usuarioLogado->CurrentValue;
			}
		} else {
			$this->nu_usuarioLogado->ViewValue = NULL;
		}
		$this->nu_usuarioLogado->ViewCustomAttributes = "";

		// dh_inicio
		$this->dh_inicio->ViewValue = $this->dh_inicio->CurrentValue;
		$this->dh_inicio->ViewValue = ew_FormatDateTime($this->dh_inicio->ViewValue, 7);
		$this->dh_inicio->ViewCustomAttributes = "";

		// ic_stContagem
		if (strval($this->ic_stContagem->CurrentValue) <> "") {
			switch ($this->ic_stContagem->CurrentValue) {
				case $this->ic_stContagem->FldTagValue(1):
					$this->ic_stContagem->ViewValue = $this->ic_stContagem->FldTagCaption(1) <> "" ? $this->ic_stContagem->FldTagCaption(1) : $this->ic_stContagem->CurrentValue;
					break;
				case $this->ic_stContagem->FldTagValue(2):
					$this->ic_stContagem->ViewValue = $this->ic_stContagem->FldTagCaption(2) <> "" ? $this->ic_stContagem->FldTagCaption(2) : $this->ic_stContagem->CurrentValue;
					break;
				case $this->ic_stContagem->FldTagValue(3):
					$this->ic_stContagem->ViewValue = $this->ic_stContagem->FldTagCaption(3) <> "" ? $this->ic_stContagem->FldTagCaption(3) : $this->ic_stContagem->CurrentValue;
					break;
				case $this->ic_stContagem->FldTagValue(4):
					$this->ic_stContagem->ViewValue = $this->ic_stContagem->FldTagCaption(4) <> "" ? $this->ic_stContagem->FldTagCaption(4) : $this->ic_stContagem->CurrentValue;
					break;
				default:
					$this->ic_stContagem->ViewValue = $this->ic_stContagem->CurrentValue;
			}
		} else {
			$this->ic_stContagem->ViewValue = NULL;
		}
		$this->ic_stContagem->ViewCustomAttributes = "";

		// ar_fasesRoteiro
		if (strval($this->ar_fasesRoteiro->CurrentValue) <> "") {
			$arwrk = explode(",", $this->ar_fasesRoteiro->CurrentValue);
			$sFilterWrk = "";
			foreach ($arwrk as $wrk) {
				if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
				$sFilterWrk .= "[nu_faseRoteiro]" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER);
			}	
		$sSqlWrk = "SELECT [nu_faseRoteiro], [no_faseRoteiro] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[faseroteiro]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->ar_fasesRoteiro, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->ar_fasesRoteiro->ViewValue = "";
				$ari = 0;
				while (!$rswrk->EOF) {
					$this->ar_fasesRoteiro->ViewValue .= $rswrk->fields('DispFld');
					$rswrk->MoveNext();
					if (!$rswrk->EOF) $this->ar_fasesRoteiro->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
					$ari++;
				}
				$rswrk->Close();
			} else {
				$this->ar_fasesRoteiro->ViewValue = $this->ar_fasesRoteiro->CurrentValue;
			}
		} else {
			$this->ar_fasesRoteiro->ViewValue = NULL;
		}
		$this->ar_fasesRoteiro->ViewCustomAttributes = "";

		// pc_varFasesRoteiro
		$this->pc_varFasesRoteiro->ViewValue = $this->pc_varFasesRoteiro->CurrentValue;
		$this->pc_varFasesRoteiro->ViewCustomAttributes = "";

		// vr_pfFaturamento
		$this->vr_pfFaturamento->ViewValue = $this->vr_pfFaturamento->CurrentValue;
		$this->vr_pfFaturamento->ViewCustomAttributes = "";

		// ic_bloqueio
		$this->ic_bloqueio->ViewValue = $this->ic_bloqueio->CurrentValue;
		$this->ic_bloqueio->ViewCustomAttributes = "";

		// nu_contagem
		$this->nu_contagem->LinkCustomAttributes = "";
		$this->nu_contagem->HrefValue = "";
		$this->nu_contagem->TooltipValue = "";

		// nu_solMetricas
		$this->nu_solMetricas->LinkCustomAttributes = "";
		$this->nu_solMetricas->HrefValue = "";
		$this->nu_solMetricas->TooltipValue = "";

		// nu_tpMetrica
		$this->nu_tpMetrica->LinkCustomAttributes = "";
		$this->nu_tpMetrica->HrefValue = "";
		$this->nu_tpMetrica->TooltipValue = "";

		// nu_tpContagem
		$this->nu_tpContagem->LinkCustomAttributes = "";
		$this->nu_tpContagem->HrefValue = "";
		$this->nu_tpContagem->TooltipValue = "";

		// nu_proposito
		$this->nu_proposito->LinkCustomAttributes = "";
		$this->nu_proposito->HrefValue = "";
		$this->nu_proposito->TooltipValue = "";

		// nu_sistema
		$this->nu_sistema->LinkCustomAttributes = "";
		$this->nu_sistema->HrefValue = "";
		$this->nu_sistema->TooltipValue = "";

		// nu_ambiente
		$this->nu_ambiente->LinkCustomAttributes = "";
		$this->nu_ambiente->HrefValue = "";
		$this->nu_ambiente->TooltipValue = "";

		// nu_metodologia
		$this->nu_metodologia->LinkCustomAttributes = "";
		$this->nu_metodologia->HrefValue = "";
		$this->nu_metodologia->TooltipValue = "";

		// nu_roteiro
		$this->nu_roteiro->LinkCustomAttributes = "";
		$this->nu_roteiro->HrefValue = "";
		$this->nu_roteiro->TooltipValue = "";

		// nu_faseMedida
		$this->nu_faseMedida->LinkCustomAttributes = "";
		$this->nu_faseMedida->HrefValue = "";
		$this->nu_faseMedida->TooltipValue = "";

		// nu_usuarioLogado
		$this->nu_usuarioLogado->LinkCustomAttributes = "";
		$this->nu_usuarioLogado->HrefValue = "";
		$this->nu_usuarioLogado->TooltipValue = "";

		// dh_inicio
		$this->dh_inicio->LinkCustomAttributes = "";
		$this->dh_inicio->HrefValue = "";
		$this->dh_inicio->TooltipValue = "";

		// ic_stContagem
		$this->ic_stContagem->LinkCustomAttributes = "";
		$this->ic_stContagem->HrefValue = "";
		$this->ic_stContagem->TooltipValue = "";

		// ar_fasesRoteiro
		$this->ar_fasesRoteiro->LinkCustomAttributes = "";
		$this->ar_fasesRoteiro->HrefValue = "";
		$this->ar_fasesRoteiro->TooltipValue = "";

		// pc_varFasesRoteiro
		$this->pc_varFasesRoteiro->LinkCustomAttributes = "";
		$this->pc_varFasesRoteiro->HrefValue = "";
		$this->pc_varFasesRoteiro->TooltipValue = "";

		// vr_pfFaturamento
		$this->vr_pfFaturamento->LinkCustomAttributes = "";
		$this->vr_pfFaturamento->HrefValue = "";
		$this->vr_pfFaturamento->TooltipValue = "";

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
				if ($this->nu_contagem->Exportable) $Doc->ExportCaption($this->nu_contagem);
				if ($this->nu_solMetricas->Exportable) $Doc->ExportCaption($this->nu_solMetricas);
				if ($this->nu_tpMetrica->Exportable) $Doc->ExportCaption($this->nu_tpMetrica);
				if ($this->nu_tpContagem->Exportable) $Doc->ExportCaption($this->nu_tpContagem);
				if ($this->nu_proposito->Exportable) $Doc->ExportCaption($this->nu_proposito);
				if ($this->nu_sistema->Exportable) $Doc->ExportCaption($this->nu_sistema);
				if ($this->nu_ambiente->Exportable) $Doc->ExportCaption($this->nu_ambiente);
				if ($this->nu_metodologia->Exportable) $Doc->ExportCaption($this->nu_metodologia);
				if ($this->nu_roteiro->Exportable) $Doc->ExportCaption($this->nu_roteiro);
				if ($this->nu_faseMedida->Exportable) $Doc->ExportCaption($this->nu_faseMedida);
				if ($this->nu_usuarioLogado->Exportable) $Doc->ExportCaption($this->nu_usuarioLogado);
				if ($this->dh_inicio->Exportable) $Doc->ExportCaption($this->dh_inicio);
				if ($this->ic_stContagem->Exportable) $Doc->ExportCaption($this->ic_stContagem);
				if ($this->ar_fasesRoteiro->Exportable) $Doc->ExportCaption($this->ar_fasesRoteiro);
				if ($this->pc_varFasesRoteiro->Exportable) $Doc->ExportCaption($this->pc_varFasesRoteiro);
				if ($this->vr_pfFaturamento->Exportable) $Doc->ExportCaption($this->vr_pfFaturamento);
			} else {
				if ($this->nu_contagem->Exportable) $Doc->ExportCaption($this->nu_contagem);
				if ($this->nu_solMetricas->Exportable) $Doc->ExportCaption($this->nu_solMetricas);
				if ($this->nu_tpMetrica->Exportable) $Doc->ExportCaption($this->nu_tpMetrica);
				if ($this->nu_tpContagem->Exportable) $Doc->ExportCaption($this->nu_tpContagem);
				if ($this->nu_proposito->Exportable) $Doc->ExportCaption($this->nu_proposito);
				if ($this->nu_sistema->Exportable) $Doc->ExportCaption($this->nu_sistema);
				if ($this->nu_ambiente->Exportable) $Doc->ExportCaption($this->nu_ambiente);
				if ($this->nu_metodologia->Exportable) $Doc->ExportCaption($this->nu_metodologia);
				if ($this->nu_roteiro->Exportable) $Doc->ExportCaption($this->nu_roteiro);
				if ($this->nu_faseMedida->Exportable) $Doc->ExportCaption($this->nu_faseMedida);
				if ($this->nu_usuarioLogado->Exportable) $Doc->ExportCaption($this->nu_usuarioLogado);
				if ($this->dh_inicio->Exportable) $Doc->ExportCaption($this->dh_inicio);
				if ($this->ic_stContagem->Exportable) $Doc->ExportCaption($this->ic_stContagem);
				if ($this->ar_fasesRoteiro->Exportable) $Doc->ExportCaption($this->ar_fasesRoteiro);
				if ($this->pc_varFasesRoteiro->Exportable) $Doc->ExportCaption($this->pc_varFasesRoteiro);
				if ($this->vr_pfFaturamento->Exportable) $Doc->ExportCaption($this->vr_pfFaturamento);
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
					if ($this->nu_contagem->Exportable) $Doc->ExportField($this->nu_contagem);
					if ($this->nu_solMetricas->Exportable) $Doc->ExportField($this->nu_solMetricas);
					if ($this->nu_tpMetrica->Exportable) $Doc->ExportField($this->nu_tpMetrica);
					if ($this->nu_tpContagem->Exportable) $Doc->ExportField($this->nu_tpContagem);
					if ($this->nu_proposito->Exportable) $Doc->ExportField($this->nu_proposito);
					if ($this->nu_sistema->Exportable) $Doc->ExportField($this->nu_sistema);
					if ($this->nu_ambiente->Exportable) $Doc->ExportField($this->nu_ambiente);
					if ($this->nu_metodologia->Exportable) $Doc->ExportField($this->nu_metodologia);
					if ($this->nu_roteiro->Exportable) $Doc->ExportField($this->nu_roteiro);
					if ($this->nu_faseMedida->Exportable) $Doc->ExportField($this->nu_faseMedida);
					if ($this->nu_usuarioLogado->Exportable) $Doc->ExportField($this->nu_usuarioLogado);
					if ($this->dh_inicio->Exportable) $Doc->ExportField($this->dh_inicio);
					if ($this->ic_stContagem->Exportable) $Doc->ExportField($this->ic_stContagem);
					if ($this->ar_fasesRoteiro->Exportable) $Doc->ExportField($this->ar_fasesRoteiro);
					if ($this->pc_varFasesRoteiro->Exportable) $Doc->ExportField($this->pc_varFasesRoteiro);
					if ($this->vr_pfFaturamento->Exportable) $Doc->ExportField($this->vr_pfFaturamento);
				} else {
					if ($this->nu_contagem->Exportable) $Doc->ExportField($this->nu_contagem);
					if ($this->nu_solMetricas->Exportable) $Doc->ExportField($this->nu_solMetricas);
					if ($this->nu_tpMetrica->Exportable) $Doc->ExportField($this->nu_tpMetrica);
					if ($this->nu_tpContagem->Exportable) $Doc->ExportField($this->nu_tpContagem);
					if ($this->nu_proposito->Exportable) $Doc->ExportField($this->nu_proposito);
					if ($this->nu_sistema->Exportable) $Doc->ExportField($this->nu_sistema);
					if ($this->nu_ambiente->Exportable) $Doc->ExportField($this->nu_ambiente);
					if ($this->nu_metodologia->Exportable) $Doc->ExportField($this->nu_metodologia);
					if ($this->nu_roteiro->Exportable) $Doc->ExportField($this->nu_roteiro);
					if ($this->nu_faseMedida->Exportable) $Doc->ExportField($this->nu_faseMedida);
					if ($this->nu_usuarioLogado->Exportable) $Doc->ExportField($this->nu_usuarioLogado);
					if ($this->dh_inicio->Exportable) $Doc->ExportField($this->dh_inicio);
					if ($this->ic_stContagem->Exportable) $Doc->ExportField($this->ic_stContagem);
					if ($this->ar_fasesRoteiro->Exportable) $Doc->ExportField($this->ar_fasesRoteiro);
					if ($this->pc_varFasesRoteiro->Exportable) $Doc->ExportField($this->pc_varFasesRoteiro);
					if ($this->vr_pfFaturamento->Exportable) $Doc->ExportField($this->vr_pfFaturamento);
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
		if (strval($rsnew["ar_fasesRoteiro"]) <> "") {
			$arwrk = explode(",", $rsnew["ar_fasesRoteiro"]);
			$sFilterWrk = "";
			foreach ($arwrk as $wrk) {
				if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
				$sFilterWrk .= "[nu_faseRoteiro]" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER);
			}    
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			$valor = ew_ExecuteScalar("select sum(pc_distribuicao) from faseroteiro WHERE " . $sWhereWrk);       
			ew_Execute("update contagempf SET pc_varFasesRoteiro = " . $valor . " where nu_contagem = '" . $rsnew["nu_contagem"] . "'");
		}  
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
		if (strval($rsold["ar_fasesRoteiro"]) <> "") {
			$arwrk = explode(",", $rsold["ar_fasesRoteiro"]);
			$sFilterWrk = "";
			foreach ($arwrk as $wrk) {
				if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
				$sFilterWrk .= "[nu_faseRoteiro]" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER);
			}    
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			$valor = ew_ExecuteScalar("select sum(pc_distribuicao) from faseroteiro WHERE " . $sWhereWrk);       
			ew_Execute("update contagempf SET pc_varFasesRoteiro = " . $valor . " where nu_contagem = '" . $rsold["nu_contagem"] . "'");
		}        
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
