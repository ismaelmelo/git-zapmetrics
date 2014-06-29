<?php

// Global variable for table object
$rprospresumo = NULL;

//
// Table class for rprospresumo
//
class crprospresumo extends cTable {
	var $nu_prospecto;
	var $no_prospecto;
	var $nu_categoriaProspecto;
	var $vr_prioridade;
	var $no_solPatr;
	var $ar_entidade;
	var $nu_area;
	var $ar_nivel;
	var $ds_sistemas;
	var $ic_implicacaoLegal;
	var $ic_risco;
	var $vr_impacto;
	var $vr_alinhamento;
	var $vr_abrangencia;
	var $vr_urgencia;
	var $vr_duracao;
	var $vr_tmpFila;
	var $ic_stProspecto;
	var $ic_ativo;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'rprospresumo';
		$this->TableName = 'rprospresumo';
		$this->TableType = 'VIEW';
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
		$this->nu_prospecto = new cField('rprospresumo', 'rprospresumo', 'x_nu_prospecto', 'nu_prospecto', '[nu_prospecto]', 'CAST([nu_prospecto] AS NVARCHAR)', 3, -1, FALSE, '[nu_prospecto]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_prospecto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_prospecto'] = &$this->nu_prospecto;

		// no_prospecto
		$this->no_prospecto = new cField('rprospresumo', 'rprospresumo', 'x_no_prospecto', 'no_prospecto', '[no_prospecto]', '[no_prospecto]', 200, -1, FALSE, '[no_prospecto]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_prospecto'] = &$this->no_prospecto;

		// nu_categoriaProspecto
		$this->nu_categoriaProspecto = new cField('rprospresumo', 'rprospresumo', 'x_nu_categoriaProspecto', 'nu_categoriaProspecto', '[nu_categoriaProspecto]', 'CAST([nu_categoriaProspecto] AS NVARCHAR)', 3, -1, FALSE, '[nu_categoriaProspecto]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_categoriaProspecto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_categoriaProspecto'] = &$this->nu_categoriaProspecto;

		// vr_prioridade
		$this->vr_prioridade = new cField('rprospresumo', 'rprospresumo', 'x_vr_prioridade', 'vr_prioridade', '[vr_prioridade]', 'CAST([vr_prioridade] AS NVARCHAR)', 131, -1, FALSE, '[vr_prioridade]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->vr_prioridade->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['vr_prioridade'] = &$this->vr_prioridade;

		// no_solPatr
		$this->no_solPatr = new cField('rprospresumo', 'rprospresumo', 'x_no_solPatr', 'no_solPatr', '[no_solPatr]', '[no_solPatr]', 200, -1, FALSE, '[no_solPatr]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_solPatr'] = &$this->no_solPatr;

		// ar_entidade
		$this->ar_entidade = new cField('rprospresumo', 'rprospresumo', 'x_ar_entidade', 'ar_entidade', '[ar_entidade]', '[ar_entidade]', 200, -1, FALSE, '[ar_entidade]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ar_entidade'] = &$this->ar_entidade;

		// nu_area
		$this->nu_area = new cField('rprospresumo', 'rprospresumo', 'x_nu_area', 'nu_area', '[nu_area]', 'CAST([nu_area] AS NVARCHAR)', 3, -1, FALSE, '[nu_area]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_area->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_area'] = &$this->nu_area;

		// ar_nivel
		$this->ar_nivel = new cField('rprospresumo', 'rprospresumo', 'x_ar_nivel', 'ar_nivel', '[ar_nivel]', '[ar_nivel]', 200, -1, FALSE, '[ar_nivel]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ar_nivel'] = &$this->ar_nivel;

		// ds_sistemas
		$this->ds_sistemas = new cField('rprospresumo', 'rprospresumo', 'x_ds_sistemas', 'ds_sistemas', '[ds_sistemas]', '[ds_sistemas]', 201, -1, FALSE, '[ds_sistemas]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_sistemas'] = &$this->ds_sistemas;

		// ic_implicacaoLegal
		$this->ic_implicacaoLegal = new cField('rprospresumo', 'rprospresumo', 'x_ic_implicacaoLegal', 'ic_implicacaoLegal', '[ic_implicacaoLegal]', '[ic_implicacaoLegal]', 129, -1, FALSE, '[ic_implicacaoLegal]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_implicacaoLegal'] = &$this->ic_implicacaoLegal;

		// ic_risco
		$this->ic_risco = new cField('rprospresumo', 'rprospresumo', 'x_ic_risco', 'ic_risco', '[ic_risco]', '[ic_risco]', 129, -1, FALSE, '[ic_risco]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_risco'] = &$this->ic_risco;

		// vr_impacto
		$this->vr_impacto = new cField('rprospresumo', 'rprospresumo', 'x_vr_impacto', 'vr_impacto', '[vr_impacto]', 'CAST([vr_impacto] AS NVARCHAR)', 131, -1, FALSE, '[vr_impacto]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->vr_impacto->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['vr_impacto'] = &$this->vr_impacto;

		// vr_alinhamento
		$this->vr_alinhamento = new cField('rprospresumo', 'rprospresumo', 'x_vr_alinhamento', 'vr_alinhamento', '[vr_alinhamento]', 'CAST([vr_alinhamento] AS NVARCHAR)', 131, -1, FALSE, '[vr_alinhamento]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->vr_alinhamento->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['vr_alinhamento'] = &$this->vr_alinhamento;

		// vr_abrangencia
		$this->vr_abrangencia = new cField('rprospresumo', 'rprospresumo', 'x_vr_abrangencia', 'vr_abrangencia', '[vr_abrangencia]', 'CAST([vr_abrangencia] AS NVARCHAR)', 131, -1, FALSE, '[vr_abrangencia]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->vr_abrangencia->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['vr_abrangencia'] = &$this->vr_abrangencia;

		// vr_urgencia
		$this->vr_urgencia = new cField('rprospresumo', 'rprospresumo', 'x_vr_urgencia', 'vr_urgencia', '[vr_urgencia]', 'CAST([vr_urgencia] AS NVARCHAR)', 131, -1, FALSE, '[vr_urgencia]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->vr_urgencia->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['vr_urgencia'] = &$this->vr_urgencia;

		// vr_duracao
		$this->vr_duracao = new cField('rprospresumo', 'rprospresumo', 'x_vr_duracao', 'vr_duracao', '[vr_duracao]', 'CAST([vr_duracao] AS NVARCHAR)', 131, -1, FALSE, '[vr_duracao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->vr_duracao->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['vr_duracao'] = &$this->vr_duracao;

		// vr_tmpFila
		$this->vr_tmpFila = new cField('rprospresumo', 'rprospresumo', 'x_vr_tmpFila', 'vr_tmpFila', '[vr_tmpFila]', 'CAST([vr_tmpFila] AS NVARCHAR)', 131, -1, FALSE, '[vr_tmpFila]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->vr_tmpFila->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['vr_tmpFila'] = &$this->vr_tmpFila;

		// ic_stProspecto
		$this->ic_stProspecto = new cField('rprospresumo', 'rprospresumo', 'x_ic_stProspecto', 'ic_stProspecto', '[ic_stProspecto]', '[ic_stProspecto]', 129, -1, FALSE, '[ic_stProspecto]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_stProspecto'] = &$this->ic_stProspecto;

		// ic_ativo
		$this->ic_ativo = new cField('rprospresumo', 'rprospresumo', 'x_ic_ativo', 'ic_ativo', '[ic_ativo]', '[ic_ativo]', 129, -1, FALSE, '[ic_ativo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
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
		if ($this->getCurrentDetailTable() == "prospecto") {
			$sDetailUrl = $GLOBALS["prospecto"]->GetListUrl() . "?showmaster=" . $this->TableVar;
			$sDetailUrl .= "&nu_prospecto=" . $this->nu_prospecto->CurrentValue;
		}
		if ($sDetailUrl == "") {
			$sDetailUrl = "rprospresumolist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	function SqlFrom() { // From
		return "[db_owner].[rprospresumo]";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlWhere() { // Where
		$sWhere = "";
		$this->TableFilter = "[ic_ativo]='S'";
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
		return "[vr_prioridade] DESC";
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
	var $UpdateTable = "[db_owner].[rprospresumo]";

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
		return "";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
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
			return "rprospresumolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "rprospresumolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("rprospresumoview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("rprospresumoview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "rprospresumoadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("rprospresumoedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("rprospresumoadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("rprospresumodelete.php", $this->UrlParm());
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

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->nu_prospecto->setDbValue($rs->fields('nu_prospecto'));
		$this->no_prospecto->setDbValue($rs->fields('no_prospecto'));
		$this->nu_categoriaProspecto->setDbValue($rs->fields('nu_categoriaProspecto'));
		$this->vr_prioridade->setDbValue($rs->fields('vr_prioridade'));
		$this->no_solPatr->setDbValue($rs->fields('no_solPatr'));
		$this->ar_entidade->setDbValue($rs->fields('ar_entidade'));
		$this->nu_area->setDbValue($rs->fields('nu_area'));
		$this->ar_nivel->setDbValue($rs->fields('ar_nivel'));
		$this->ds_sistemas->setDbValue($rs->fields('ds_sistemas'));
		$this->ic_implicacaoLegal->setDbValue($rs->fields('ic_implicacaoLegal'));
		$this->ic_risco->setDbValue($rs->fields('ic_risco'));
		$this->vr_impacto->setDbValue($rs->fields('vr_impacto'));
		$this->vr_alinhamento->setDbValue($rs->fields('vr_alinhamento'));
		$this->vr_abrangencia->setDbValue($rs->fields('vr_abrangencia'));
		$this->vr_urgencia->setDbValue($rs->fields('vr_urgencia'));
		$this->vr_duracao->setDbValue($rs->fields('vr_duracao'));
		$this->vr_tmpFila->setDbValue($rs->fields('vr_tmpFila'));
		$this->ic_stProspecto->setDbValue($rs->fields('ic_stProspecto'));
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
		// nu_categoriaProspecto
		// vr_prioridade
		// no_solPatr
		// ar_entidade
		// nu_area
		// ar_nivel
		// ds_sistemas
		// ic_implicacaoLegal
		// ic_risco
		// vr_impacto
		// vr_alinhamento
		// vr_abrangencia
		// vr_urgencia
		// vr_duracao
		// vr_tmpFila
		// ic_stProspecto
		// ic_ativo
		// nu_prospecto

		$this->nu_prospecto->ViewValue = $this->nu_prospecto->CurrentValue;
		$this->nu_prospecto->ViewCustomAttributes = "";

		// no_prospecto
		$this->no_prospecto->ViewValue = $this->no_prospecto->CurrentValue;
		$this->no_prospecto->CssStyle = "font-weight: bold;";
		$this->no_prospecto->ViewCustomAttributes = "";

		// nu_categoriaProspecto
		if (strval($this->nu_categoriaProspecto->CurrentValue) <> "") {
			$sFilterWrk = "[nu_categoria]" . ew_SearchString("=", $this->nu_categoriaProspecto->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_categoria], [no_categoria] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[catprospecto]";
		$sWhereWrk = "";
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

		// vr_prioridade
		$this->vr_prioridade->ViewValue = $this->vr_prioridade->CurrentValue;
		$this->vr_prioridade->ViewValue = ew_FormatNumber($this->vr_prioridade->ViewValue, 0, -2, -2, -2);
		$this->vr_prioridade->ViewCustomAttributes = "";

		// no_solPatr
		$this->no_solPatr->ViewValue = $this->no_solPatr->CurrentValue;
		$this->no_solPatr->ViewCustomAttributes = "";

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

		// nu_area
		if (strval($this->nu_area->CurrentValue) <> "") {
			$sFilterWrk = "[nu_area]" . ew_SearchString("=", $this->nu_area->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_area], [no_area] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[area]";
		$sWhereWrk = "";
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

		// ds_sistemas
		$this->ds_sistemas->ViewValue = $this->ds_sistemas->CurrentValue;
		$this->ds_sistemas->ViewCustomAttributes = "";

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

		// vr_impacto
		$this->vr_impacto->ViewValue = $this->vr_impacto->CurrentValue;
		$this->vr_impacto->ViewValue = ew_FormatNumber($this->vr_impacto->ViewValue, 0, -2, -2, -2);
		$this->vr_impacto->ViewCustomAttributes = "";

		// vr_alinhamento
		$this->vr_alinhamento->ViewValue = $this->vr_alinhamento->CurrentValue;
		$this->vr_alinhamento->ViewValue = ew_FormatNumber($this->vr_alinhamento->ViewValue, 0, -2, -2, -2);
		$this->vr_alinhamento->ViewCustomAttributes = "";

		// vr_abrangencia
		$this->vr_abrangencia->ViewValue = $this->vr_abrangencia->CurrentValue;
		$this->vr_abrangencia->ViewValue = ew_FormatNumber($this->vr_abrangencia->ViewValue, 0, -2, -2, -2);
		$this->vr_abrangencia->ViewCustomAttributes = "";

		// vr_urgencia
		$this->vr_urgencia->ViewValue = $this->vr_urgencia->CurrentValue;
		$this->vr_urgencia->ViewValue = ew_FormatNumber($this->vr_urgencia->ViewValue, 0, -2, -2, -2);
		$this->vr_urgencia->ViewCustomAttributes = "";

		// vr_duracao
		$this->vr_duracao->ViewValue = $this->vr_duracao->CurrentValue;
		$this->vr_duracao->ViewValue = ew_FormatNumber($this->vr_duracao->ViewValue, 0, -2, -2, -2);
		$this->vr_duracao->ViewCustomAttributes = "";

		// vr_tmpFila
		$this->vr_tmpFila->ViewValue = $this->vr_tmpFila->CurrentValue;
		$this->vr_tmpFila->ViewValue = ew_FormatNumber($this->vr_tmpFila->ViewValue, 0, -2, -2, -2);
		$this->vr_tmpFila->ViewCustomAttributes = "";

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
		if (!ew_Empty($this->nu_prospecto->CurrentValue)) {
			$this->no_prospecto->HrefValue = "prospectoview.php?showdetail=&nu_prospecto=" . $this->nu_prospecto->CurrentValue; // Add prefix/suffix
			$this->no_prospecto->LinkAttrs["target"] = ""; // Add target
			if ($this->Export <> "") $this->no_prospecto->HrefValue = ew_ConvertFullUrl($this->no_prospecto->HrefValue);
		} else {
			$this->no_prospecto->HrefValue = "";
		}
		$this->no_prospecto->TooltipValue = "";

		// nu_categoriaProspecto
		$this->nu_categoriaProspecto->LinkCustomAttributes = "";
		$this->nu_categoriaProspecto->HrefValue = "";
		$this->nu_categoriaProspecto->TooltipValue = "";

		// vr_prioridade
		$this->vr_prioridade->LinkCustomAttributes = "";
		$this->vr_prioridade->HrefValue = "";
		$this->vr_prioridade->TooltipValue = "";

		// no_solPatr
		$this->no_solPatr->LinkCustomAttributes = "";
		$this->no_solPatr->HrefValue = "";
		$this->no_solPatr->TooltipValue = "";

		// ar_entidade
		$this->ar_entidade->LinkCustomAttributes = "";
		$this->ar_entidade->HrefValue = "";
		$this->ar_entidade->TooltipValue = "";

		// nu_area
		$this->nu_area->LinkCustomAttributes = "";
		$this->nu_area->HrefValue = "";
		$this->nu_area->TooltipValue = "";

		// ar_nivel
		$this->ar_nivel->LinkCustomAttributes = "";
		$this->ar_nivel->HrefValue = "";
		$this->ar_nivel->TooltipValue = "";

		// ds_sistemas
		$this->ds_sistemas->LinkCustomAttributes = "";
		$this->ds_sistemas->HrefValue = "";
		$this->ds_sistemas->TooltipValue = "";

		// ic_implicacaoLegal
		$this->ic_implicacaoLegal->LinkCustomAttributes = "";
		$this->ic_implicacaoLegal->HrefValue = "";
		$this->ic_implicacaoLegal->TooltipValue = "";

		// ic_risco
		$this->ic_risco->LinkCustomAttributes = "";
		$this->ic_risco->HrefValue = "";
		$this->ic_risco->TooltipValue = "";

		// vr_impacto
		$this->vr_impacto->LinkCustomAttributes = "";
		$this->vr_impacto->HrefValue = "";
		$this->vr_impacto->TooltipValue = "";

		// vr_alinhamento
		$this->vr_alinhamento->LinkCustomAttributes = "";
		$this->vr_alinhamento->HrefValue = "";
		$this->vr_alinhamento->TooltipValue = "";

		// vr_abrangencia
		$this->vr_abrangencia->LinkCustomAttributes = "";
		$this->vr_abrangencia->HrefValue = "";
		$this->vr_abrangencia->TooltipValue = "";

		// vr_urgencia
		$this->vr_urgencia->LinkCustomAttributes = "";
		$this->vr_urgencia->HrefValue = "";
		$this->vr_urgencia->TooltipValue = "";

		// vr_duracao
		$this->vr_duracao->LinkCustomAttributes = "";
		$this->vr_duracao->HrefValue = "";
		$this->vr_duracao->TooltipValue = "";

		// vr_tmpFila
		$this->vr_tmpFila->LinkCustomAttributes = "";
		$this->vr_tmpFila->HrefValue = "";
		$this->vr_tmpFila->TooltipValue = "";

		// ic_stProspecto
		$this->ic_stProspecto->LinkCustomAttributes = "";
		$this->ic_stProspecto->HrefValue = "";
		$this->ic_stProspecto->TooltipValue = "";

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
				if ($this->nu_categoriaProspecto->Exportable) $Doc->ExportCaption($this->nu_categoriaProspecto);
				if ($this->vr_prioridade->Exportable) $Doc->ExportCaption($this->vr_prioridade);
				if ($this->no_solPatr->Exportable) $Doc->ExportCaption($this->no_solPatr);
				if ($this->ar_entidade->Exportable) $Doc->ExportCaption($this->ar_entidade);
				if ($this->nu_area->Exportable) $Doc->ExportCaption($this->nu_area);
				if ($this->ar_nivel->Exportable) $Doc->ExportCaption($this->ar_nivel);
				if ($this->ds_sistemas->Exportable) $Doc->ExportCaption($this->ds_sistemas);
				if ($this->ic_implicacaoLegal->Exportable) $Doc->ExportCaption($this->ic_implicacaoLegal);
				if ($this->ic_risco->Exportable) $Doc->ExportCaption($this->ic_risco);
				if ($this->vr_impacto->Exportable) $Doc->ExportCaption($this->vr_impacto);
				if ($this->vr_alinhamento->Exportable) $Doc->ExportCaption($this->vr_alinhamento);
				if ($this->vr_abrangencia->Exportable) $Doc->ExportCaption($this->vr_abrangencia);
				if ($this->vr_urgencia->Exportable) $Doc->ExportCaption($this->vr_urgencia);
				if ($this->vr_duracao->Exportable) $Doc->ExportCaption($this->vr_duracao);
				if ($this->vr_tmpFila->Exportable) $Doc->ExportCaption($this->vr_tmpFila);
				if ($this->ic_stProspecto->Exportable) $Doc->ExportCaption($this->ic_stProspecto);
				if ($this->ic_ativo->Exportable) $Doc->ExportCaption($this->ic_ativo);
			} else {
				if ($this->nu_prospecto->Exportable) $Doc->ExportCaption($this->nu_prospecto);
				if ($this->no_prospecto->Exportable) $Doc->ExportCaption($this->no_prospecto);
				if ($this->nu_categoriaProspecto->Exportable) $Doc->ExportCaption($this->nu_categoriaProspecto);
				if ($this->vr_prioridade->Exportable) $Doc->ExportCaption($this->vr_prioridade);
				if ($this->no_solPatr->Exportable) $Doc->ExportCaption($this->no_solPatr);
				if ($this->ar_entidade->Exportable) $Doc->ExportCaption($this->ar_entidade);
				if ($this->nu_area->Exportable) $Doc->ExportCaption($this->nu_area);
				if ($this->ar_nivel->Exportable) $Doc->ExportCaption($this->ar_nivel);
				if ($this->ds_sistemas->Exportable) $Doc->ExportCaption($this->ds_sistemas);
				if ($this->ic_implicacaoLegal->Exportable) $Doc->ExportCaption($this->ic_implicacaoLegal);
				if ($this->ic_risco->Exportable) $Doc->ExportCaption($this->ic_risco);
				if ($this->vr_impacto->Exportable) $Doc->ExportCaption($this->vr_impacto);
				if ($this->vr_alinhamento->Exportable) $Doc->ExportCaption($this->vr_alinhamento);
				if ($this->vr_abrangencia->Exportable) $Doc->ExportCaption($this->vr_abrangencia);
				if ($this->vr_urgencia->Exportable) $Doc->ExportCaption($this->vr_urgencia);
				if ($this->vr_duracao->Exportable) $Doc->ExportCaption($this->vr_duracao);
				if ($this->vr_tmpFila->Exportable) $Doc->ExportCaption($this->vr_tmpFila);
				if ($this->ic_stProspecto->Exportable) $Doc->ExportCaption($this->ic_stProspecto);
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
					if ($this->nu_categoriaProspecto->Exportable) $Doc->ExportField($this->nu_categoriaProspecto);
					if ($this->vr_prioridade->Exportable) $Doc->ExportField($this->vr_prioridade);
					if ($this->no_solPatr->Exportable) $Doc->ExportField($this->no_solPatr);
					if ($this->ar_entidade->Exportable) $Doc->ExportField($this->ar_entidade);
					if ($this->nu_area->Exportable) $Doc->ExportField($this->nu_area);
					if ($this->ar_nivel->Exportable) $Doc->ExportField($this->ar_nivel);
					if ($this->ds_sistemas->Exportable) $Doc->ExportField($this->ds_sistemas);
					if ($this->ic_implicacaoLegal->Exportable) $Doc->ExportField($this->ic_implicacaoLegal);
					if ($this->ic_risco->Exportable) $Doc->ExportField($this->ic_risco);
					if ($this->vr_impacto->Exportable) $Doc->ExportField($this->vr_impacto);
					if ($this->vr_alinhamento->Exportable) $Doc->ExportField($this->vr_alinhamento);
					if ($this->vr_abrangencia->Exportable) $Doc->ExportField($this->vr_abrangencia);
					if ($this->vr_urgencia->Exportable) $Doc->ExportField($this->vr_urgencia);
					if ($this->vr_duracao->Exportable) $Doc->ExportField($this->vr_duracao);
					if ($this->vr_tmpFila->Exportable) $Doc->ExportField($this->vr_tmpFila);
					if ($this->ic_stProspecto->Exportable) $Doc->ExportField($this->ic_stProspecto);
					if ($this->ic_ativo->Exportable) $Doc->ExportField($this->ic_ativo);
				} else {
					if ($this->nu_prospecto->Exportable) $Doc->ExportField($this->nu_prospecto);
					if ($this->no_prospecto->Exportable) $Doc->ExportField($this->no_prospecto);
					if ($this->nu_categoriaProspecto->Exportable) $Doc->ExportField($this->nu_categoriaProspecto);
					if ($this->vr_prioridade->Exportable) $Doc->ExportField($this->vr_prioridade);
					if ($this->no_solPatr->Exportable) $Doc->ExportField($this->no_solPatr);
					if ($this->ar_entidade->Exportable) $Doc->ExportField($this->ar_entidade);
					if ($this->nu_area->Exportable) $Doc->ExportField($this->nu_area);
					if ($this->ar_nivel->Exportable) $Doc->ExportField($this->ar_nivel);
					if ($this->ds_sistemas->Exportable) $Doc->ExportField($this->ds_sistemas);
					if ($this->ic_implicacaoLegal->Exportable) $Doc->ExportField($this->ic_implicacaoLegal);
					if ($this->ic_risco->Exportable) $Doc->ExportField($this->ic_risco);
					if ($this->vr_impacto->Exportable) $Doc->ExportField($this->vr_impacto);
					if ($this->vr_alinhamento->Exportable) $Doc->ExportField($this->vr_alinhamento);
					if ($this->vr_abrangencia->Exportable) $Doc->ExportField($this->vr_abrangencia);
					if ($this->vr_urgencia->Exportable) $Doc->ExportField($this->vr_urgencia);
					if ($this->vr_duracao->Exportable) $Doc->ExportField($this->vr_duracao);
					if ($this->vr_tmpFila->Exportable) $Doc->ExportField($this->vr_tmpFila);
					if ($this->ic_stProspecto->Exportable) $Doc->ExportField($this->ic_stProspecto);
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

		if ($this->ic_stProspecto->ViewValue == "Fila") {
			$this->RowAttrs["style"] = "background-color: #FFFACD";      
		}                                               
		if ($this->ic_stProspecto->ViewValue == "Executando") {
			$this->RowAttrs["style"] = "background-color: #CAFF70";
		}                                             
		if ($this->ic_stProspecto->ViewValue == "Cancelado") {
			$this->RowAttrs["style"] = "background-color: #D3D3D3";
		}       
		if ($this->ic_stProspecto->ViewValue == "Suspenso") {        
			$this->RowAttrs["style"] = "background-color: #FFE4E1";
		}                                      
		if ($this->ic_stProspecto->ViewValue == "Concluído") {
			$this->RowAttrs["style"] = "background-color: #00FF00";   
		}
	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
