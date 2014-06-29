<?php

// Global variable for table object
$itembaseconhecimento = NULL;

//
// Table class for itembaseconhecimento
//
class citembaseconhecimento extends cTable {
	var $nu_item;
	var $no_tituloItem;
	var $ic_tpItem;
	var $ds_item;
	var $ic_situacao;
	var $ds_acoes;
	var $nu_usuarioInc;
	var $dh_inclusao;
	var $nu_usuarioAlt;
	var $dh_alteracao;
	var $nu_sistema;
	var $nu_modulo;
	var $nu_uc;
	var $nu_processoCobit;
	var $nu_prospecto;
	var $nu_projeto;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'itembaseconhecimento';
		$this->TableName = 'itembaseconhecimento';
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

		// nu_item
		$this->nu_item = new cField('itembaseconhecimento', 'itembaseconhecimento', 'x_nu_item', 'nu_item', '[nu_item]', 'CAST([nu_item] AS NVARCHAR)', 3, -1, FALSE, '[nu_item]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_item->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_item'] = &$this->nu_item;

		// no_tituloItem
		$this->no_tituloItem = new cField('itembaseconhecimento', 'itembaseconhecimento', 'x_no_tituloItem', 'no_tituloItem', '[no_tituloItem]', '[no_tituloItem]', 200, -1, FALSE, '[no_tituloItem]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_tituloItem'] = &$this->no_tituloItem;

		// ic_tpItem
		$this->ic_tpItem = new cField('itembaseconhecimento', 'itembaseconhecimento', 'x_ic_tpItem', 'ic_tpItem', '[ic_tpItem]', '[ic_tpItem]', 129, -1, FALSE, '[ic_tpItem]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_tpItem'] = &$this->ic_tpItem;

		// ds_item
		$this->ds_item = new cField('itembaseconhecimento', 'itembaseconhecimento', 'x_ds_item', 'ds_item', '[ds_item]', '[ds_item]', 201, -1, FALSE, '[ds_item]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_item'] = &$this->ds_item;

		// ic_situacao
		$this->ic_situacao = new cField('itembaseconhecimento', 'itembaseconhecimento', 'x_ic_situacao', 'ic_situacao', '[ic_situacao]', '[ic_situacao]', 129, -1, FALSE, '[ic_situacao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_situacao'] = &$this->ic_situacao;

		// ds_acoes
		$this->ds_acoes = new cField('itembaseconhecimento', 'itembaseconhecimento', 'x_ds_acoes', 'ds_acoes', '[ds_acoes]', '[ds_acoes]', 201, -1, FALSE, '[ds_acoes]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_acoes'] = &$this->ds_acoes;

		// nu_usuarioInc
		$this->nu_usuarioInc = new cField('itembaseconhecimento', 'itembaseconhecimento', 'x_nu_usuarioInc', 'nu_usuarioInc', '[nu_usuarioInc]', 'CAST([nu_usuarioInc] AS NVARCHAR)', 3, -1, FALSE, '[nu_usuarioInc]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_usuarioInc->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_usuarioInc'] = &$this->nu_usuarioInc;

		// dh_inclusao
		$this->dh_inclusao = new cField('itembaseconhecimento', 'itembaseconhecimento', 'x_dh_inclusao', 'dh_inclusao', '[dh_inclusao]', '(REPLACE(STR(DAY([dh_inclusao]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dh_inclusao]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dh_inclusao]),4,0))', 135, 9, FALSE, '[dh_inclusao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dh_inclusao->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dh_inclusao'] = &$this->dh_inclusao;

		// nu_usuarioAlt
		$this->nu_usuarioAlt = new cField('itembaseconhecimento', 'itembaseconhecimento', 'x_nu_usuarioAlt', 'nu_usuarioAlt', '[nu_usuarioAlt]', 'CAST([nu_usuarioAlt] AS NVARCHAR)', 3, -1, FALSE, '[nu_usuarioAlt]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_usuarioAlt->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_usuarioAlt'] = &$this->nu_usuarioAlt;

		// dh_alteracao
		$this->dh_alteracao = new cField('itembaseconhecimento', 'itembaseconhecimento', 'x_dh_alteracao', 'dh_alteracao', '[dh_alteracao]', '(REPLACE(STR(DAY([dh_alteracao]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dh_alteracao]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dh_alteracao]),4,0))', 135, 9, FALSE, '[dh_alteracao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dh_alteracao->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dh_alteracao'] = &$this->dh_alteracao;

		// nu_sistema
		$this->nu_sistema = new cField('itembaseconhecimento', 'itembaseconhecimento', 'x_nu_sistema', 'nu_sistema', '[nu_sistema]', 'CAST([nu_sistema] AS NVARCHAR)', 3, -1, FALSE, '[EV__nu_sistema]', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->nu_sistema->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_sistema'] = &$this->nu_sistema;

		// nu_modulo
		$this->nu_modulo = new cField('itembaseconhecimento', 'itembaseconhecimento', 'x_nu_modulo', 'nu_modulo', '[nu_modulo]', 'CAST([nu_modulo] AS NVARCHAR)', 3, -1, FALSE, '[EV__nu_modulo]', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->nu_modulo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_modulo'] = &$this->nu_modulo;

		// nu_uc
		$this->nu_uc = new cField('itembaseconhecimento', 'itembaseconhecimento', 'x_nu_uc', 'nu_uc', '[nu_uc]', 'CAST([nu_uc] AS NVARCHAR)', 3, -1, FALSE, '[EV__nu_uc]', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->nu_uc->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_uc'] = &$this->nu_uc;

		// nu_processoCobit
		$this->nu_processoCobit = new cField('itembaseconhecimento', 'itembaseconhecimento', 'x_nu_processoCobit', 'nu_processoCobit', '[nu_processoCobit]', 'CAST([nu_processoCobit] AS NVARCHAR)', 3, -1, FALSE, '[nu_processoCobit]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_processoCobit->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_processoCobit'] = &$this->nu_processoCobit;

		// nu_prospecto
		$this->nu_prospecto = new cField('itembaseconhecimento', 'itembaseconhecimento', 'x_nu_prospecto', 'nu_prospecto', '[nu_prospecto]', 'CAST([nu_prospecto] AS NVARCHAR)', 3, -1, FALSE, '[EV__nu_prospecto]', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->nu_prospecto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_prospecto'] = &$this->nu_prospecto;

		// nu_projeto
		$this->nu_projeto = new cField('itembaseconhecimento', 'itembaseconhecimento', 'x_nu_projeto', 'nu_projeto', '[nu_projeto]', 'CAST([nu_projeto] AS NVARCHAR)', 3, -1, FALSE, '[EV__nu_projeto]', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->nu_projeto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_projeto'] = &$this->nu_projeto;
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

	// Table level SQL
	function SqlFrom() { // From
		return "[dbo].[itembaseconhecimento]";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlSelectList() { // Select for List page
		return "SELECT * FROM (" .
			"SELECT *, (SELECT TOP 1 [co_alternativo] + '" . ew_ValueSeparator(1, $this->nu_sistema) . "' + [no_sistema] FROM [sistema] [EW_TMP_LOOKUPTABLE] WHERE [EW_TMP_LOOKUPTABLE].[nu_sistema] = [itembaseconhecimento].[nu_sistema]) AS [EV__nu_sistema], (SELECT TOP 1 [no_modulo] FROM [modulo] [EW_TMP_LOOKUPTABLE] WHERE [EW_TMP_LOOKUPTABLE].[nu_modulo] = [itembaseconhecimento].[nu_modulo]) AS [EV__nu_modulo], (SELECT TOP 1 [co_alternativo] + '" . ew_ValueSeparator(1, $this->nu_uc) . "' + [no_uc] FROM [uc] [EW_TMP_LOOKUPTABLE] WHERE [EW_TMP_LOOKUPTABLE].[nu_uc] = [itembaseconhecimento].[nu_uc]) AS [EV__nu_uc], (SELECT TOP 1 [no_prospecto] FROM [prospecto] [EW_TMP_LOOKUPTABLE] WHERE [EW_TMP_LOOKUPTABLE].[nu_prospecto] = [itembaseconhecimento].[nu_prospecto]) AS [EV__nu_prospecto], (SELECT TOP 1 [no_projeto] FROM [projeto] [EW_TMP_LOOKUPTABLE] WHERE [EW_TMP_LOOKUPTABLE].[nu_projeto] = [itembaseconhecimento].[nu_projeto]) AS [EV__nu_projeto] FROM [dbo].[itembaseconhecimento]" .
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
		if ($this->nu_sistema->AdvancedSearch->SearchValue <> "" ||
			$this->nu_sistema->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->nu_sistema->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->nu_sistema->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->nu_modulo->AdvancedSearch->SearchValue <> "" ||
			$this->nu_modulo->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->nu_modulo->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->nu_modulo->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->nu_uc->AdvancedSearch->SearchValue <> "" ||
			$this->nu_uc->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->nu_uc->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->nu_uc->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->nu_prospecto->AdvancedSearch->SearchValue <> "" ||
			$this->nu_prospecto->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->nu_prospecto->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->nu_prospecto->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->nu_projeto->AdvancedSearch->SearchValue <> "" ||
			$this->nu_projeto->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->nu_projeto->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->nu_projeto->FldVirtualExpression . " ") !== FALSE)
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
	var $UpdateTable = "[dbo].[itembaseconhecimento]";

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
			if (array_key_exists('nu_item', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_item') . '=' . ew_QuotedValue($rs['nu_item'], $this->nu_item->FldDataType));
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
		return "[nu_item] = @nu_item@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nu_item->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_item@", ew_AdjustSql($this->nu_item->CurrentValue), $sKeyFilter); // Replace key value
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
			return "itembaseconhecimentolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "itembaseconhecimentolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("itembaseconhecimentoview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("itembaseconhecimentoview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "itembaseconhecimentoadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("itembaseconhecimentoedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("itembaseconhecimentoadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("itembaseconhecimentodelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nu_item->CurrentValue)) {
			$sUrl .= "nu_item=" . urlencode($this->nu_item->CurrentValue);
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
			$arKeys[] = @$_GET["nu_item"]; // nu_item

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
			$this->nu_item->CurrentValue = $key;
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
		$this->nu_item->setDbValue($rs->fields('nu_item'));
		$this->no_tituloItem->setDbValue($rs->fields('no_tituloItem'));
		$this->ic_tpItem->setDbValue($rs->fields('ic_tpItem'));
		$this->ds_item->setDbValue($rs->fields('ds_item'));
		$this->ic_situacao->setDbValue($rs->fields('ic_situacao'));
		$this->ds_acoes->setDbValue($rs->fields('ds_acoes'));
		$this->nu_usuarioInc->setDbValue($rs->fields('nu_usuarioInc'));
		$this->dh_inclusao->setDbValue($rs->fields('dh_inclusao'));
		$this->nu_usuarioAlt->setDbValue($rs->fields('nu_usuarioAlt'));
		$this->dh_alteracao->setDbValue($rs->fields('dh_alteracao'));
		$this->nu_sistema->setDbValue($rs->fields('nu_sistema'));
		$this->nu_modulo->setDbValue($rs->fields('nu_modulo'));
		$this->nu_uc->setDbValue($rs->fields('nu_uc'));
		$this->nu_processoCobit->setDbValue($rs->fields('nu_processoCobit'));
		$this->nu_prospecto->setDbValue($rs->fields('nu_prospecto'));
		$this->nu_projeto->setDbValue($rs->fields('nu_projeto'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_item
		// no_tituloItem
		// ic_tpItem
		// ds_item
		// ic_situacao
		// ds_acoes
		// nu_usuarioInc
		// dh_inclusao
		// nu_usuarioAlt
		// dh_alteracao
		// nu_sistema
		// nu_modulo
		// nu_uc
		// nu_processoCobit
		// nu_prospecto
		// nu_projeto
		// nu_item

		$this->nu_item->ViewValue = $this->nu_item->CurrentValue;
		$this->nu_item->ViewCustomAttributes = "";

		// no_tituloItem
		$this->no_tituloItem->ViewValue = $this->no_tituloItem->CurrentValue;
		$this->no_tituloItem->ViewCustomAttributes = "";

		// ic_tpItem
		if (strval($this->ic_tpItem->CurrentValue) <> "") {
			switch ($this->ic_tpItem->CurrentValue) {
				case $this->ic_tpItem->FldTagValue(1):
					$this->ic_tpItem->ViewValue = $this->ic_tpItem->FldTagCaption(1) <> "" ? $this->ic_tpItem->FldTagCaption(1) : $this->ic_tpItem->CurrentValue;
					break;
				case $this->ic_tpItem->FldTagValue(2):
					$this->ic_tpItem->ViewValue = $this->ic_tpItem->FldTagCaption(2) <> "" ? $this->ic_tpItem->FldTagCaption(2) : $this->ic_tpItem->CurrentValue;
					break;
				case $this->ic_tpItem->FldTagValue(3):
					$this->ic_tpItem->ViewValue = $this->ic_tpItem->FldTagCaption(3) <> "" ? $this->ic_tpItem->FldTagCaption(3) : $this->ic_tpItem->CurrentValue;
					break;
				default:
					$this->ic_tpItem->ViewValue = $this->ic_tpItem->CurrentValue;
			}
		} else {
			$this->ic_tpItem->ViewValue = NULL;
		}
		$this->ic_tpItem->ViewCustomAttributes = "";

		// ds_item
		$this->ds_item->ViewValue = $this->ds_item->CurrentValue;
		$this->ds_item->ViewCustomAttributes = "";

		// ic_situacao
		if (strval($this->ic_situacao->CurrentValue) <> "") {
			switch ($this->ic_situacao->CurrentValue) {
				case $this->ic_situacao->FldTagValue(1):
					$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(1) <> "" ? $this->ic_situacao->FldTagCaption(1) : $this->ic_situacao->CurrentValue;
					break;
				case $this->ic_situacao->FldTagValue(2):
					$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(2) <> "" ? $this->ic_situacao->FldTagCaption(2) : $this->ic_situacao->CurrentValue;
					break;
				case $this->ic_situacao->FldTagValue(3):
					$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(3) <> "" ? $this->ic_situacao->FldTagCaption(3) : $this->ic_situacao->CurrentValue;
					break;
				case $this->ic_situacao->FldTagValue(4):
					$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(4) <> "" ? $this->ic_situacao->FldTagCaption(4) : $this->ic_situacao->CurrentValue;
					break;
				case $this->ic_situacao->FldTagValue(5):
					$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(5) <> "" ? $this->ic_situacao->FldTagCaption(5) : $this->ic_situacao->CurrentValue;
					break;
				default:
					$this->ic_situacao->ViewValue = $this->ic_situacao->CurrentValue;
			}
		} else {
			$this->ic_situacao->ViewValue = NULL;
		}
		$this->ic_situacao->ViewCustomAttributes = "";

		// ds_acoes
		$this->ds_acoes->ViewValue = $this->ds_acoes->CurrentValue;
		$this->ds_acoes->ViewCustomAttributes = "";

		// nu_usuarioInc
		if (strval($this->nu_usuarioInc->CurrentValue) <> "") {
			$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioInc->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_usuarioInc, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_usuarioInc->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_usuarioInc->ViewValue = $this->nu_usuarioInc->CurrentValue;
			}
		} else {
			$this->nu_usuarioInc->ViewValue = NULL;
		}
		$this->nu_usuarioInc->ViewCustomAttributes = "";

		// dh_inclusao
		$this->dh_inclusao->ViewValue = $this->dh_inclusao->CurrentValue;
		$this->dh_inclusao->ViewValue = ew_FormatDateTime($this->dh_inclusao->ViewValue, 9);
		$this->dh_inclusao->ViewCustomAttributes = "";

		// nu_usuarioAlt
		if (strval($this->nu_usuarioAlt->CurrentValue) <> "") {
			$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioAlt->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_usuarioAlt, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_usuarioAlt->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_usuarioAlt->ViewValue = $this->nu_usuarioAlt->CurrentValue;
			}
		} else {
			$this->nu_usuarioAlt->ViewValue = NULL;
		}
		$this->nu_usuarioAlt->ViewCustomAttributes = "";

		// dh_alteracao
		$this->dh_alteracao->ViewValue = $this->dh_alteracao->CurrentValue;
		$this->dh_alteracao->ViewValue = ew_FormatDateTime($this->dh_alteracao->ViewValue, 9);
		$this->dh_alteracao->ViewCustomAttributes = "";

		// nu_sistema
		if ($this->nu_sistema->VirtualValue <> "") {
			$this->nu_sistema->ViewValue = $this->nu_sistema->VirtualValue;
		} else {
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
		}
		$this->nu_sistema->ViewCustomAttributes = "";

		// nu_modulo
		if ($this->nu_modulo->VirtualValue <> "") {
			$this->nu_modulo->ViewValue = $this->nu_modulo->VirtualValue;
		} else {
		if (strval($this->nu_modulo->CurrentValue) <> "") {
			$sFilterWrk = "[nu_modulo]" . ew_SearchString("=", $this->nu_modulo->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_modulo], [no_modulo] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[modulo]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_modulo, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_modulo] ASC";
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
		}
		$this->nu_modulo->ViewCustomAttributes = "";

		// nu_uc
		if ($this->nu_uc->VirtualValue <> "") {
			$this->nu_uc->ViewValue = $this->nu_uc->VirtualValue;
		} else {
		if (strval($this->nu_uc->CurrentValue) <> "") {
			$sFilterWrk = "[nu_uc]" . ew_SearchString("=", $this->nu_uc->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_uc], [co_alternativo] AS [DispFld], [no_uc] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[uc]";
		$sWhereWrk = "";
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
		}
		$this->nu_uc->ViewCustomAttributes = "";

		// nu_processoCobit
		if (strval($this->nu_processoCobit->CurrentValue) <> "") {
			$sFilterWrk = "[nu_processo]" . ew_SearchString("=", $this->nu_processoCobit->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_processo], [co_alternativo] AS [DispFld], [no_processo] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[processocobit5]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_processoCobit, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_processoCobit->ViewValue = $rswrk->fields('DispFld');
				$this->nu_processoCobit->ViewValue .= ew_ValueSeparator(1,$this->nu_processoCobit) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->nu_processoCobit->ViewValue = $this->nu_processoCobit->CurrentValue;
			}
		} else {
			$this->nu_processoCobit->ViewValue = NULL;
		}
		$this->nu_processoCobit->ViewCustomAttributes = "";

		// nu_prospecto
		if ($this->nu_prospecto->VirtualValue <> "") {
			$this->nu_prospecto->ViewValue = $this->nu_prospecto->VirtualValue;
		} else {
		if (strval($this->nu_prospecto->CurrentValue) <> "") {
			$sFilterWrk = "[nu_prospecto]" . ew_SearchString("=", $this->nu_prospecto->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_prospecto], [no_prospecto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[prospecto]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_prospecto, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_prospecto] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_prospecto->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_prospecto->ViewValue = $this->nu_prospecto->CurrentValue;
			}
		} else {
			$this->nu_prospecto->ViewValue = NULL;
		}
		}
		$this->nu_prospecto->ViewCustomAttributes = "";

		// nu_projeto
		if ($this->nu_projeto->VirtualValue <> "") {
			$this->nu_projeto->ViewValue = $this->nu_projeto->VirtualValue;
		} else {
		if (strval($this->nu_projeto->CurrentValue) <> "") {
			$sFilterWrk = "[nu_projeto]" . ew_SearchString("=", $this->nu_projeto->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_projeto], [no_projeto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[projeto]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_projeto, $sWhereWrk);
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
		}
		$this->nu_projeto->ViewCustomAttributes = "";

		// nu_item
		$this->nu_item->LinkCustomAttributes = "";
		$this->nu_item->HrefValue = "";
		$this->nu_item->TooltipValue = "";

		// no_tituloItem
		$this->no_tituloItem->LinkCustomAttributes = "";
		$this->no_tituloItem->HrefValue = "";
		$this->no_tituloItem->TooltipValue = "";

		// ic_tpItem
		$this->ic_tpItem->LinkCustomAttributes = "";
		$this->ic_tpItem->HrefValue = "";
		$this->ic_tpItem->TooltipValue = "";

		// ds_item
		$this->ds_item->LinkCustomAttributes = "";
		$this->ds_item->HrefValue = "";
		$this->ds_item->TooltipValue = "";

		// ic_situacao
		$this->ic_situacao->LinkCustomAttributes = "";
		$this->ic_situacao->HrefValue = "";
		$this->ic_situacao->TooltipValue = "";

		// ds_acoes
		$this->ds_acoes->LinkCustomAttributes = "";
		$this->ds_acoes->HrefValue = "";
		$this->ds_acoes->TooltipValue = "";

		// nu_usuarioInc
		$this->nu_usuarioInc->LinkCustomAttributes = "";
		$this->nu_usuarioInc->HrefValue = "";
		$this->nu_usuarioInc->TooltipValue = "";

		// dh_inclusao
		$this->dh_inclusao->LinkCustomAttributes = "";
		$this->dh_inclusao->HrefValue = "";
		$this->dh_inclusao->TooltipValue = "";

		// nu_usuarioAlt
		$this->nu_usuarioAlt->LinkCustomAttributes = "";
		$this->nu_usuarioAlt->HrefValue = "";
		$this->nu_usuarioAlt->TooltipValue = "";

		// dh_alteracao
		$this->dh_alteracao->LinkCustomAttributes = "";
		$this->dh_alteracao->HrefValue = "";
		$this->dh_alteracao->TooltipValue = "";

		// nu_sistema
		$this->nu_sistema->LinkCustomAttributes = "";
		$this->nu_sistema->HrefValue = "";
		$this->nu_sistema->TooltipValue = "";

		// nu_modulo
		$this->nu_modulo->LinkCustomAttributes = "";
		$this->nu_modulo->HrefValue = "";
		$this->nu_modulo->TooltipValue = "";

		// nu_uc
		$this->nu_uc->LinkCustomAttributes = "";
		$this->nu_uc->HrefValue = "";
		$this->nu_uc->TooltipValue = "";

		// nu_processoCobit
		$this->nu_processoCobit->LinkCustomAttributes = "";
		$this->nu_processoCobit->HrefValue = "";
		$this->nu_processoCobit->TooltipValue = "";

		// nu_prospecto
		$this->nu_prospecto->LinkCustomAttributes = "";
		$this->nu_prospecto->HrefValue = "";
		$this->nu_prospecto->TooltipValue = "";

		// nu_projeto
		$this->nu_projeto->LinkCustomAttributes = "";
		$this->nu_projeto->HrefValue = "";
		$this->nu_projeto->TooltipValue = "";

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
				if ($this->nu_item->Exportable) $Doc->ExportCaption($this->nu_item);
				if ($this->no_tituloItem->Exportable) $Doc->ExportCaption($this->no_tituloItem);
				if ($this->ic_tpItem->Exportable) $Doc->ExportCaption($this->ic_tpItem);
				if ($this->ds_item->Exportable) $Doc->ExportCaption($this->ds_item);
				if ($this->ic_situacao->Exportable) $Doc->ExportCaption($this->ic_situacao);
				if ($this->ds_acoes->Exportable) $Doc->ExportCaption($this->ds_acoes);
				if ($this->nu_usuarioInc->Exportable) $Doc->ExportCaption($this->nu_usuarioInc);
				if ($this->dh_inclusao->Exportable) $Doc->ExportCaption($this->dh_inclusao);
				if ($this->nu_usuarioAlt->Exportable) $Doc->ExportCaption($this->nu_usuarioAlt);
				if ($this->dh_alteracao->Exportable) $Doc->ExportCaption($this->dh_alteracao);
				if ($this->nu_sistema->Exportable) $Doc->ExportCaption($this->nu_sistema);
				if ($this->nu_modulo->Exportable) $Doc->ExportCaption($this->nu_modulo);
				if ($this->nu_uc->Exportable) $Doc->ExportCaption($this->nu_uc);
				if ($this->nu_processoCobit->Exportable) $Doc->ExportCaption($this->nu_processoCobit);
				if ($this->nu_prospecto->Exportable) $Doc->ExportCaption($this->nu_prospecto);
				if ($this->nu_projeto->Exportable) $Doc->ExportCaption($this->nu_projeto);
			} else {
				if ($this->nu_item->Exportable) $Doc->ExportCaption($this->nu_item);
				if ($this->no_tituloItem->Exportable) $Doc->ExportCaption($this->no_tituloItem);
				if ($this->ic_tpItem->Exportable) $Doc->ExportCaption($this->ic_tpItem);
				if ($this->ic_situacao->Exportable) $Doc->ExportCaption($this->ic_situacao);
				if ($this->nu_usuarioInc->Exportable) $Doc->ExportCaption($this->nu_usuarioInc);
				if ($this->dh_inclusao->Exportable) $Doc->ExportCaption($this->dh_inclusao);
				if ($this->nu_usuarioAlt->Exportable) $Doc->ExportCaption($this->nu_usuarioAlt);
				if ($this->dh_alteracao->Exportable) $Doc->ExportCaption($this->dh_alteracao);
				if ($this->nu_sistema->Exportable) $Doc->ExportCaption($this->nu_sistema);
				if ($this->nu_modulo->Exportable) $Doc->ExportCaption($this->nu_modulo);
				if ($this->nu_uc->Exportable) $Doc->ExportCaption($this->nu_uc);
				if ($this->nu_processoCobit->Exportable) $Doc->ExportCaption($this->nu_processoCobit);
				if ($this->nu_prospecto->Exportable) $Doc->ExportCaption($this->nu_prospecto);
				if ($this->nu_projeto->Exportable) $Doc->ExportCaption($this->nu_projeto);
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
					if ($this->nu_item->Exportable) $Doc->ExportField($this->nu_item);
					if ($this->no_tituloItem->Exportable) $Doc->ExportField($this->no_tituloItem);
					if ($this->ic_tpItem->Exportable) $Doc->ExportField($this->ic_tpItem);
					if ($this->ds_item->Exportable) $Doc->ExportField($this->ds_item);
					if ($this->ic_situacao->Exportable) $Doc->ExportField($this->ic_situacao);
					if ($this->ds_acoes->Exportable) $Doc->ExportField($this->ds_acoes);
					if ($this->nu_usuarioInc->Exportable) $Doc->ExportField($this->nu_usuarioInc);
					if ($this->dh_inclusao->Exportable) $Doc->ExportField($this->dh_inclusao);
					if ($this->nu_usuarioAlt->Exportable) $Doc->ExportField($this->nu_usuarioAlt);
					if ($this->dh_alteracao->Exportable) $Doc->ExportField($this->dh_alteracao);
					if ($this->nu_sistema->Exportable) $Doc->ExportField($this->nu_sistema);
					if ($this->nu_modulo->Exportable) $Doc->ExportField($this->nu_modulo);
					if ($this->nu_uc->Exportable) $Doc->ExportField($this->nu_uc);
					if ($this->nu_processoCobit->Exportable) $Doc->ExportField($this->nu_processoCobit);
					if ($this->nu_prospecto->Exportable) $Doc->ExportField($this->nu_prospecto);
					if ($this->nu_projeto->Exportable) $Doc->ExportField($this->nu_projeto);
				} else {
					if ($this->nu_item->Exportable) $Doc->ExportField($this->nu_item);
					if ($this->no_tituloItem->Exportable) $Doc->ExportField($this->no_tituloItem);
					if ($this->ic_tpItem->Exportable) $Doc->ExportField($this->ic_tpItem);
					if ($this->ic_situacao->Exportable) $Doc->ExportField($this->ic_situacao);
					if ($this->nu_usuarioInc->Exportable) $Doc->ExportField($this->nu_usuarioInc);
					if ($this->dh_inclusao->Exportable) $Doc->ExportField($this->dh_inclusao);
					if ($this->nu_usuarioAlt->Exportable) $Doc->ExportField($this->nu_usuarioAlt);
					if ($this->dh_alteracao->Exportable) $Doc->ExportField($this->dh_alteracao);
					if ($this->nu_sistema->Exportable) $Doc->ExportField($this->nu_sistema);
					if ($this->nu_modulo->Exportable) $Doc->ExportField($this->nu_modulo);
					if ($this->nu_uc->Exportable) $Doc->ExportField($this->nu_uc);
					if ($this->nu_processoCobit->Exportable) $Doc->ExportField($this->nu_processoCobit);
					if ($this->nu_prospecto->Exportable) $Doc->ExportField($this->nu_prospecto);
					if ($this->nu_projeto->Exportable) $Doc->ExportField($this->nu_projeto);
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
