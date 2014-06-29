<?php

// Global variable for table object
$os = NULL;

//
// Table class for os
//
class cos extends cTable {
	var $nu_os;
	var $co_os;
	var $no_titulo;
	var $nu_contrato;
	var $nu_itemContratado;
	var $nu_areaSolicitante;
	var $nu_projeto;
	var $dt_criacaoOs;
	var $dt_entrega;
	var $nu_stOs;
	var $dt_stOs;
	var $nu_usuarioAnalista;
	var $ds_observacoes;
	var $vr_os;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'os';
		$this->TableName = 'os';
		$this->TableType = 'TABLE';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->DetailAdd = TRUE; // Allow detail add
		$this->DetailEdit = TRUE; // Allow detail edit
		$this->DetailView = TRUE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 1;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// nu_os
		$this->nu_os = new cField('os', 'os', 'x_nu_os', 'nu_os', '[nu_os]', 'CAST([nu_os] AS NVARCHAR)', 3, -1, FALSE, '[nu_os]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_os->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_os'] = &$this->nu_os;

		// co_os
		$this->co_os = new cField('os', 'os', 'x_co_os', 'co_os', '[co_os]', 'CAST([co_os] AS NVARCHAR)', 3, -1, FALSE, '[co_os]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->co_os->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['co_os'] = &$this->co_os;

		// no_titulo
		$this->no_titulo = new cField('os', 'os', 'x_no_titulo', 'no_titulo', '[no_titulo]', '[no_titulo]', 200, -1, FALSE, '[no_titulo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_titulo'] = &$this->no_titulo;

		// nu_contrato
		$this->nu_contrato = new cField('os', 'os', 'x_nu_contrato', 'nu_contrato', '[nu_contrato]', 'CAST([nu_contrato] AS NVARCHAR)', 3, -1, FALSE, '[nu_contrato]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_contrato->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_contrato'] = &$this->nu_contrato;

		// nu_itemContratado
		$this->nu_itemContratado = new cField('os', 'os', 'x_nu_itemContratado', 'nu_itemContratado', '[nu_itemContratado]', 'CAST([nu_itemContratado] AS NVARCHAR)', 3, -1, FALSE, '[nu_itemContratado]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_itemContratado->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_itemContratado'] = &$this->nu_itemContratado;

		// nu_areaSolicitante
		$this->nu_areaSolicitante = new cField('os', 'os', 'x_nu_areaSolicitante', 'nu_areaSolicitante', '[nu_areaSolicitante]', 'CAST([nu_areaSolicitante] AS NVARCHAR)', 3, -1, FALSE, '[nu_areaSolicitante]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_areaSolicitante->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_areaSolicitante'] = &$this->nu_areaSolicitante;

		// nu_projeto
		$this->nu_projeto = new cField('os', 'os', 'x_nu_projeto', 'nu_projeto', '[nu_projeto]', 'CAST([nu_projeto] AS NVARCHAR)', 3, -1, FALSE, '[EV__nu_projeto]', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->nu_projeto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_projeto'] = &$this->nu_projeto;

		// dt_criacaoOs
		$this->dt_criacaoOs = new cField('os', 'os', 'x_dt_criacaoOs', 'dt_criacaoOs', '[dt_criacaoOs]', '(REPLACE(STR(DAY([dt_criacaoOs]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dt_criacaoOs]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dt_criacaoOs]),4,0))', 135, 7, FALSE, '[dt_criacaoOs]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dt_criacaoOs->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dt_criacaoOs'] = &$this->dt_criacaoOs;

		// dt_entrega
		$this->dt_entrega = new cField('os', 'os', 'x_dt_entrega', 'dt_entrega', '[dt_entrega]', '(REPLACE(STR(DAY([dt_entrega]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dt_entrega]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dt_entrega]),4,0))', 135, 7, FALSE, '[dt_entrega]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dt_entrega->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dt_entrega'] = &$this->dt_entrega;

		// nu_stOs
		$this->nu_stOs = new cField('os', 'os', 'x_nu_stOs', 'nu_stOs', '[nu_stOs]', 'CAST([nu_stOs] AS NVARCHAR)', 3, -1, FALSE, '[nu_stOs]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_stOs->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_stOs'] = &$this->nu_stOs;

		// dt_stOs
		$this->dt_stOs = new cField('os', 'os', 'x_dt_stOs', 'dt_stOs', '[dt_stOs]', '(REPLACE(STR(DAY([dt_stOs]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dt_stOs]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dt_stOs]),4,0))', 135, 7, FALSE, '[dt_stOs]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dt_stOs->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dt_stOs'] = &$this->dt_stOs;

		// nu_usuarioAnalista
		$this->nu_usuarioAnalista = new cField('os', 'os', 'x_nu_usuarioAnalista', 'nu_usuarioAnalista', '[nu_usuarioAnalista]', 'CAST([nu_usuarioAnalista] AS NVARCHAR)', 3, -1, FALSE, '[nu_usuarioAnalista]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_usuarioAnalista->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_usuarioAnalista'] = &$this->nu_usuarioAnalista;

		// ds_observacoes
		$this->ds_observacoes = new cField('os', 'os', 'x_ds_observacoes', 'ds_observacoes', '[ds_observacoes]', '[ds_observacoes]', 201, -1, FALSE, '[ds_observacoes]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_observacoes'] = &$this->ds_observacoes;

		// vr_os
		$this->vr_os = new cField('os', 'os', 'x_vr_os', 'vr_os', '[vr_os]', 'CAST([vr_os] AS NVARCHAR)', 131, -1, FALSE, '[vr_os]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->vr_os->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['vr_os'] = &$this->vr_os;
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
		return "[dbo].[os]";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlSelectList() { // Select for List page
		return "SELECT * FROM (" .
			"SELECT *, (SELECT TOP 1 [no_projeto] FROM [projeto] [EW_TMP_LOOKUPTABLE] WHERE [EW_TMP_LOOKUPTABLE].[nu_projeto] = [os].[nu_projeto]) AS [EV__nu_projeto] FROM [dbo].[os]" .
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
		return "[nu_os] DESC";
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
	var $UpdateTable = "[dbo].[os]";

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
			if (array_key_exists('nu_os', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_os') . '=' . ew_QuotedValue($rs['nu_os'], $this->nu_os->FldDataType));
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
		return "[nu_os] = @nu_os@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nu_os->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_os@", ew_AdjustSql($this->nu_os->CurrentValue), $sKeyFilter); // Replace key value
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
			return "oslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "oslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("osview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("osview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "osadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("osedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("osadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("osdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nu_os->CurrentValue)) {
			$sUrl .= "nu_os=" . urlencode($this->nu_os->CurrentValue);
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
			$arKeys[] = @$_GET["nu_os"]; // nu_os

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
			$this->nu_os->CurrentValue = $key;
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
		$this->nu_os->setDbValue($rs->fields('nu_os'));
		$this->co_os->setDbValue($rs->fields('co_os'));
		$this->no_titulo->setDbValue($rs->fields('no_titulo'));
		$this->nu_contrato->setDbValue($rs->fields('nu_contrato'));
		$this->nu_itemContratado->setDbValue($rs->fields('nu_itemContratado'));
		$this->nu_areaSolicitante->setDbValue($rs->fields('nu_areaSolicitante'));
		$this->nu_projeto->setDbValue($rs->fields('nu_projeto'));
		$this->dt_criacaoOs->setDbValue($rs->fields('dt_criacaoOs'));
		$this->dt_entrega->setDbValue($rs->fields('dt_entrega'));
		$this->nu_stOs->setDbValue($rs->fields('nu_stOs'));
		$this->dt_stOs->setDbValue($rs->fields('dt_stOs'));
		$this->nu_usuarioAnalista->setDbValue($rs->fields('nu_usuarioAnalista'));
		$this->ds_observacoes->setDbValue($rs->fields('ds_observacoes'));
		$this->vr_os->setDbValue($rs->fields('vr_os'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_os

		$this->nu_os->CellCssStyle = "white-space: nowrap;";

		// co_os
		// no_titulo
		// nu_contrato
		// nu_itemContratado
		// nu_areaSolicitante
		// nu_projeto
		// dt_criacaoOs
		// dt_entrega
		// nu_stOs
		// dt_stOs
		// nu_usuarioAnalista
		// ds_observacoes
		// vr_os
		// nu_os

		$this->nu_os->ViewValue = $this->nu_os->CurrentValue;
		$this->nu_os->ViewCustomAttributes = "";

		// co_os
		$this->co_os->ViewValue = $this->co_os->CurrentValue;
		$this->co_os->ViewValue = ew_FormatNumber($this->co_os->ViewValue, 0, 0, 0, 0);
		$this->co_os->ViewCustomAttributes = "";

		// no_titulo
		$this->no_titulo->ViewValue = $this->no_titulo->CurrentValue;
		$this->no_titulo->ViewCustomAttributes = "";

		// nu_contrato
		if (strval($this->nu_contrato->CurrentValue) <> "") {
			$sFilterWrk = "[nu_contrato]" . ew_SearchString("=", $this->nu_contrato->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_contrato], [nu_contrato] AS [DispFld], [no_contrato] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[contrato]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_contrato, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_contrato] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_contrato->ViewValue = $rswrk->fields('DispFld');
				$this->nu_contrato->ViewValue .= ew_ValueSeparator(1,$this->nu_contrato) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->nu_contrato->ViewValue = $this->nu_contrato->CurrentValue;
			}
		} else {
			$this->nu_contrato->ViewValue = NULL;
		}
		$this->nu_contrato->ViewCustomAttributes = "";

		// nu_itemContratado
		if (strval($this->nu_itemContratado->CurrentValue) <> "") {
			$sFilterWrk = "[nu_itemContratado]" . ew_SearchString("=", $this->nu_itemContratado->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_itemContratado], [nu_itemOc] AS [DispFld], [no_itemContratado] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[item_contratado]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_itemContratado, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_itemContratado->ViewValue = $rswrk->fields('DispFld');
				$this->nu_itemContratado->ViewValue .= ew_ValueSeparator(1,$this->nu_itemContratado) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->nu_itemContratado->ViewValue = $this->nu_itemContratado->CurrentValue;
			}
		} else {
			$this->nu_itemContratado->ViewValue = NULL;
		}
		$this->nu_itemContratado->ViewCustomAttributes = "";

		// nu_areaSolicitante
		if (strval($this->nu_areaSolicitante->CurrentValue) <> "") {
			$sFilterWrk = "[nu_area]" . ew_SearchString("=", $this->nu_areaSolicitante->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_area], [no_area] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[area]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_areaSolicitante, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_area] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_areaSolicitante->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_areaSolicitante->ViewValue = $this->nu_areaSolicitante->CurrentValue;
			}
		} else {
			$this->nu_areaSolicitante->ViewValue = NULL;
		}
		$this->nu_areaSolicitante->ViewCustomAttributes = "";

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
		}
		$this->nu_projeto->ViewCustomAttributes = "";

		// dt_criacaoOs
		$this->dt_criacaoOs->ViewValue = $this->dt_criacaoOs->CurrentValue;
		$this->dt_criacaoOs->ViewValue = ew_FormatDateTime($this->dt_criacaoOs->ViewValue, 7);
		$this->dt_criacaoOs->ViewCustomAttributes = "";

		// dt_entrega
		$this->dt_entrega->ViewValue = $this->dt_entrega->CurrentValue;
		$this->dt_entrega->ViewValue = ew_FormatDateTime($this->dt_entrega->ViewValue, 7);
		$this->dt_entrega->ViewCustomAttributes = "";

		// nu_stOs
		if (strval($this->nu_stOs->CurrentValue) <> "") {
			$sFilterWrk = "[nu_stOs]" . ew_SearchString("=", $this->nu_stOs->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_stOs], [no_stUc] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[stos]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_stOs, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_stUc] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_stOs->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_stOs->ViewValue = $this->nu_stOs->CurrentValue;
			}
		} else {
			$this->nu_stOs->ViewValue = NULL;
		}
		$this->nu_stOs->ViewCustomAttributes = "";

		// dt_stOs
		$this->dt_stOs->ViewValue = $this->dt_stOs->CurrentValue;
		$this->dt_stOs->ViewValue = ew_FormatDateTime($this->dt_stOs->ViewValue, 7);
		$this->dt_stOs->ViewCustomAttributes = "";

		// nu_usuarioAnalista
		if (strval($this->nu_usuarioAnalista->CurrentValue) <> "") {
			$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioAnalista->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_usuarioAnalista, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_usuarioAnalista->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_usuarioAnalista->ViewValue = $this->nu_usuarioAnalista->CurrentValue;
			}
		} else {
			$this->nu_usuarioAnalista->ViewValue = NULL;
		}
		$this->nu_usuarioAnalista->ViewCustomAttributes = "";

		// ds_observacoes
		$this->ds_observacoes->ViewValue = $this->ds_observacoes->CurrentValue;
		$this->ds_observacoes->ViewCustomAttributes = "";

		// vr_os
		$this->vr_os->ViewValue = $this->vr_os->CurrentValue;
		$this->vr_os->ViewValue = ew_FormatCurrency($this->vr_os->ViewValue, 2, -2, -2, -2);
		$this->vr_os->ViewCustomAttributes = "";

		// nu_os
		$this->nu_os->LinkCustomAttributes = "";
		$this->nu_os->HrefValue = "";
		$this->nu_os->TooltipValue = "";

		// co_os
		$this->co_os->LinkCustomAttributes = "";
		$this->co_os->HrefValue = "";
		$this->co_os->TooltipValue = "";

		// no_titulo
		$this->no_titulo->LinkCustomAttributes = "";
		$this->no_titulo->HrefValue = "";
		$this->no_titulo->TooltipValue = "";

		// nu_contrato
		$this->nu_contrato->LinkCustomAttributes = "";
		$this->nu_contrato->HrefValue = "";
		$this->nu_contrato->TooltipValue = "";

		// nu_itemContratado
		$this->nu_itemContratado->LinkCustomAttributes = "";
		$this->nu_itemContratado->HrefValue = "";
		$this->nu_itemContratado->TooltipValue = "";

		// nu_areaSolicitante
		$this->nu_areaSolicitante->LinkCustomAttributes = "";
		$this->nu_areaSolicitante->HrefValue = "";
		$this->nu_areaSolicitante->TooltipValue = "";

		// nu_projeto
		$this->nu_projeto->LinkCustomAttributes = "";
		$this->nu_projeto->HrefValue = "";
		$this->nu_projeto->TooltipValue = "";

		// dt_criacaoOs
		$this->dt_criacaoOs->LinkCustomAttributes = "";
		$this->dt_criacaoOs->HrefValue = "";
		$this->dt_criacaoOs->TooltipValue = "";

		// dt_entrega
		$this->dt_entrega->LinkCustomAttributes = "";
		$this->dt_entrega->HrefValue = "";
		$this->dt_entrega->TooltipValue = "";

		// nu_stOs
		$this->nu_stOs->LinkCustomAttributes = "";
		$this->nu_stOs->HrefValue = "";
		$this->nu_stOs->TooltipValue = "";

		// dt_stOs
		$this->dt_stOs->LinkCustomAttributes = "";
		$this->dt_stOs->HrefValue = "";
		$this->dt_stOs->TooltipValue = "";

		// nu_usuarioAnalista
		$this->nu_usuarioAnalista->LinkCustomAttributes = "";
		$this->nu_usuarioAnalista->HrefValue = "";
		$this->nu_usuarioAnalista->TooltipValue = "";

		// ds_observacoes
		$this->ds_observacoes->LinkCustomAttributes = "";
		$this->ds_observacoes->HrefValue = "";
		$this->ds_observacoes->TooltipValue = "";

		// vr_os
		$this->vr_os->LinkCustomAttributes = "";
		$this->vr_os->HrefValue = "";
		$this->vr_os->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
			if (is_numeric($this->vr_os->CurrentValue))
				$this->vr_os->Total += $this->vr_os->CurrentValue; // Accumulate total
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
			$this->vr_os->CurrentValue = $this->vr_os->Total;
			$this->vr_os->ViewValue = $this->vr_os->CurrentValue;
			$this->vr_os->ViewValue = ew_FormatCurrency($this->vr_os->ViewValue, 2, -2, -2, -2);
			$this->vr_os->ViewCustomAttributes = "";
			$this->vr_os->HrefValue = ""; // Clear href value
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
				if ($this->co_os->Exportable) $Doc->ExportCaption($this->co_os);
				if ($this->no_titulo->Exportable) $Doc->ExportCaption($this->no_titulo);
				if ($this->nu_contrato->Exportable) $Doc->ExportCaption($this->nu_contrato);
				if ($this->nu_itemContratado->Exportable) $Doc->ExportCaption($this->nu_itemContratado);
				if ($this->nu_areaSolicitante->Exportable) $Doc->ExportCaption($this->nu_areaSolicitante);
				if ($this->nu_projeto->Exportable) $Doc->ExportCaption($this->nu_projeto);
				if ($this->dt_criacaoOs->Exportable) $Doc->ExportCaption($this->dt_criacaoOs);
				if ($this->dt_entrega->Exportable) $Doc->ExportCaption($this->dt_entrega);
				if ($this->nu_stOs->Exportable) $Doc->ExportCaption($this->nu_stOs);
				if ($this->dt_stOs->Exportable) $Doc->ExportCaption($this->dt_stOs);
				if ($this->nu_usuarioAnalista->Exportable) $Doc->ExportCaption($this->nu_usuarioAnalista);
				if ($this->ds_observacoes->Exportable) $Doc->ExportCaption($this->ds_observacoes);
				if ($this->vr_os->Exportable) $Doc->ExportCaption($this->vr_os);
			} else {
				if ($this->nu_os->Exportable) $Doc->ExportCaption($this->nu_os);
				if ($this->co_os->Exportable) $Doc->ExportCaption($this->co_os);
				if ($this->no_titulo->Exportable) $Doc->ExportCaption($this->no_titulo);
				if ($this->nu_contrato->Exportable) $Doc->ExportCaption($this->nu_contrato);
				if ($this->nu_itemContratado->Exportable) $Doc->ExportCaption($this->nu_itemContratado);
				if ($this->nu_areaSolicitante->Exportable) $Doc->ExportCaption($this->nu_areaSolicitante);
				if ($this->nu_projeto->Exportable) $Doc->ExportCaption($this->nu_projeto);
				if ($this->dt_criacaoOs->Exportable) $Doc->ExportCaption($this->dt_criacaoOs);
				if ($this->dt_entrega->Exportable) $Doc->ExportCaption($this->dt_entrega);
				if ($this->nu_stOs->Exportable) $Doc->ExportCaption($this->nu_stOs);
				if ($this->dt_stOs->Exportable) $Doc->ExportCaption($this->dt_stOs);
				if ($this->nu_usuarioAnalista->Exportable) $Doc->ExportCaption($this->nu_usuarioAnalista);
				if ($this->vr_os->Exportable) $Doc->ExportCaption($this->vr_os);
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
				$this->AggregateListRowValues(); // Aggregate row values

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
				if ($ExportPageType == "view") {
					if ($this->co_os->Exportable) $Doc->ExportField($this->co_os);
					if ($this->no_titulo->Exportable) $Doc->ExportField($this->no_titulo);
					if ($this->nu_contrato->Exportable) $Doc->ExportField($this->nu_contrato);
					if ($this->nu_itemContratado->Exportable) $Doc->ExportField($this->nu_itemContratado);
					if ($this->nu_areaSolicitante->Exportable) $Doc->ExportField($this->nu_areaSolicitante);
					if ($this->nu_projeto->Exportable) $Doc->ExportField($this->nu_projeto);
					if ($this->dt_criacaoOs->Exportable) $Doc->ExportField($this->dt_criacaoOs);
					if ($this->dt_entrega->Exportable) $Doc->ExportField($this->dt_entrega);
					if ($this->nu_stOs->Exportable) $Doc->ExportField($this->nu_stOs);
					if ($this->dt_stOs->Exportable) $Doc->ExportField($this->dt_stOs);
					if ($this->nu_usuarioAnalista->Exportable) $Doc->ExportField($this->nu_usuarioAnalista);
					if ($this->ds_observacoes->Exportable) $Doc->ExportField($this->ds_observacoes);
					if ($this->vr_os->Exportable) $Doc->ExportField($this->vr_os);
				} else {
					if ($this->nu_os->Exportable) $Doc->ExportField($this->nu_os);
					if ($this->co_os->Exportable) $Doc->ExportField($this->co_os);
					if ($this->no_titulo->Exportable) $Doc->ExportField($this->no_titulo);
					if ($this->nu_contrato->Exportable) $Doc->ExportField($this->nu_contrato);
					if ($this->nu_itemContratado->Exportable) $Doc->ExportField($this->nu_itemContratado);
					if ($this->nu_areaSolicitante->Exportable) $Doc->ExportField($this->nu_areaSolicitante);
					if ($this->nu_projeto->Exportable) $Doc->ExportField($this->nu_projeto);
					if ($this->dt_criacaoOs->Exportable) $Doc->ExportField($this->dt_criacaoOs);
					if ($this->dt_entrega->Exportable) $Doc->ExportField($this->dt_entrega);
					if ($this->nu_stOs->Exportable) $Doc->ExportField($this->nu_stOs);
					if ($this->dt_stOs->Exportable) $Doc->ExportField($this->dt_stOs);
					if ($this->nu_usuarioAnalista->Exportable) $Doc->ExportField($this->nu_usuarioAnalista);
					if ($this->vr_os->Exportable) $Doc->ExportField($this->vr_os);
				}
				$Doc->EndExportRow();
			}
			$Recordset->MoveNext();
		}

		// Export aggregates (horizontal format only)
		if ($Doc->Horizontal) {
			$this->RowType = EW_ROWTYPE_AGGREGATE;
			$this->ResetAttrs();
			$this->AggregateListRow();
			$Doc->BeginExportRow(-1);
			$Doc->ExportAggregate($this->nu_os, '');
			$Doc->ExportAggregate($this->co_os, '');
			$Doc->ExportAggregate($this->no_titulo, '');
			$Doc->ExportAggregate($this->nu_contrato, '');
			$Doc->ExportAggregate($this->nu_itemContratado, '');
			$Doc->ExportAggregate($this->nu_areaSolicitante, '');
			$Doc->ExportAggregate($this->nu_projeto, '');
			$Doc->ExportAggregate($this->dt_criacaoOs, '');
			$Doc->ExportAggregate($this->dt_entrega, '');
			$Doc->ExportAggregate($this->nu_stOs, '');
			$Doc->ExportAggregate($this->dt_stOs, '');
			$Doc->ExportAggregate($this->nu_usuarioAnalista, '');
			$Doc->ExportAggregate($this->vr_os, 'TOTAL');
			$Doc->EndExportRow();
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

		$valor = ew_ExecuteScalar("select max(co_os) From os");
		$rsnew["co_os"] = $valor + 1;                                                                                                                                           
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

		$valor = ew_ExecuteScalar("select max(co_os) From os");                                    
		$this->nu_versao->EditValue = "" . $valor + 1 . ""; 
	}     

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
