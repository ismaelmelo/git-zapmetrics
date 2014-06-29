<?php

// Global variable for table object
$regranegocio = NULL;

//
// Table class for regranegocio
//
class cregranegocio extends cTable {
	var $co_alternativo;
	var $nu_versao;
	var $no_regraNegocio;
	var $ds_regraNegocio;
	var $nu_area;
	var $ds_origemRegra;
	var $nu_projeto;
	var $no_tags;
	var $nu_stRegraNegocio;
	var $nu_usuario;
	var $dt_versao;
	var $hh_versao;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'regranegocio';
		$this->TableName = 'regranegocio';
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

		// co_alternativo
		$this->co_alternativo = new cField('regranegocio', 'regranegocio', 'x_co_alternativo', 'co_alternativo', '[co_alternativo]', '[co_alternativo]', 200, -1, FALSE, '[co_alternativo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_alternativo'] = &$this->co_alternativo;

		// nu_versao
		$this->nu_versao = new cField('regranegocio', 'regranegocio', 'x_nu_versao', 'nu_versao', '[nu_versao]', 'CAST([nu_versao] AS NVARCHAR)', 3, -1, FALSE, '[nu_versao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_versao->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_versao'] = &$this->nu_versao;

		// no_regraNegocio
		$this->no_regraNegocio = new cField('regranegocio', 'regranegocio', 'x_no_regraNegocio', 'no_regraNegocio', '[no_regraNegocio]', '[no_regraNegocio]', 200, -1, FALSE, '[no_regraNegocio]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_regraNegocio'] = &$this->no_regraNegocio;

		// ds_regraNegocio
		$this->ds_regraNegocio = new cField('regranegocio', 'regranegocio', 'x_ds_regraNegocio', 'ds_regraNegocio', '[ds_regraNegocio]', '[ds_regraNegocio]', 201, -1, FALSE, '[ds_regraNegocio]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_regraNegocio'] = &$this->ds_regraNegocio;

		// nu_area
		$this->nu_area = new cField('regranegocio', 'regranegocio', 'x_nu_area', 'nu_area', '[nu_area]', 'CAST([nu_area] AS NVARCHAR)', 3, -1, FALSE, '[nu_area]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_area->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_area'] = &$this->nu_area;

		// ds_origemRegra
		$this->ds_origemRegra = new cField('regranegocio', 'regranegocio', 'x_ds_origemRegra', 'ds_origemRegra', '[ds_origemRegra]', '[ds_origemRegra]', 201, -1, FALSE, '[ds_origemRegra]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_origemRegra'] = &$this->ds_origemRegra;

		// nu_projeto
		$this->nu_projeto = new cField('regranegocio', 'regranegocio', 'x_nu_projeto', 'nu_projeto', '[nu_projeto]', 'CAST([nu_projeto] AS NVARCHAR)', 3, -1, FALSE, '[nu_projeto]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_projeto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_projeto'] = &$this->nu_projeto;

		// no_tags
		$this->no_tags = new cField('regranegocio', 'regranegocio', 'x_no_tags', 'no_tags', '[no_tags]', '[no_tags]', 200, -1, FALSE, '[no_tags]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_tags'] = &$this->no_tags;

		// nu_stRegraNegocio
		$this->nu_stRegraNegocio = new cField('regranegocio', 'regranegocio', 'x_nu_stRegraNegocio', 'nu_stRegraNegocio', '[nu_stRegraNegocio]', 'CAST([nu_stRegraNegocio] AS NVARCHAR)', 3, -1, FALSE, '[nu_stRegraNegocio]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_stRegraNegocio->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_stRegraNegocio'] = &$this->nu_stRegraNegocio;

		// nu_usuario
		$this->nu_usuario = new cField('regranegocio', 'regranegocio', 'x_nu_usuario', 'nu_usuario', '[nu_usuario]', 'CAST([nu_usuario] AS NVARCHAR)', 3, -1, FALSE, '[nu_usuario]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['nu_usuario'] = &$this->nu_usuario;

		// dt_versao
		$this->dt_versao = new cField('regranegocio', 'regranegocio', 'x_dt_versao', 'dt_versao', '[dt_versao]', '(REPLACE(STR(DAY([dt_versao]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dt_versao]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dt_versao]),4,0))', 135, 7, FALSE, '[dt_versao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dt_versao->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dt_versao'] = &$this->dt_versao;

		// hh_versao
		$this->hh_versao = new cField('regranegocio', 'regranegocio', 'x_hh_versao', 'hh_versao', '[hh_versao]', '(REPLACE(STR(DAY([hh_versao]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([hh_versao]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([hh_versao]),4,0))', 145, 4, FALSE, '[hh_versao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->hh_versao->FldDefaultErrMsg = $Language->Phrase("IncorrectTime");
		$this->fields['hh_versao'] = &$this->hh_versao;
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
		if ($this->getCurrentMasterTable() == "corn") {
			if ($this->co_alternativo->getSessionValue() <> "")
				$sMasterFilter .= "[co_rn]=" . ew_QuotedValue($this->co_alternativo->getSessionValue(), EW_DATATYPE_STRING);
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "corn") {
			if ($this->co_alternativo->getSessionValue() <> "")
				$sDetailFilter .= "[co_alternativo]=" . ew_QuotedValue($this->co_alternativo->getSessionValue(), EW_DATATYPE_STRING);
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_corn() {
		return "[co_rn]='@co_rn@'";
	}

	// Detail filter
	function SqlDetailFilter_corn() {
		return "[co_alternativo]='@co_alternativo@'";
	}

	// Table level SQL
	function SqlFrom() { // From
		return "[dbo].[regranegocio]";
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
		return "[nu_versao] DESC";
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
		global $Security;

		// Add User ID filter
		if (!$this->AllowAnonymousUser() && $Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
			$sFilter = $this->AddUserIDFilter($sFilter);
		}
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = $this->UserIDAllowSecurity;
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
	var $UpdateTable = "[dbo].[regranegocio]";

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
			if (array_key_exists('co_alternativo', $rs))
				ew_AddFilter($where, ew_QuotedName('co_alternativo') . '=' . ew_QuotedValue($rs['co_alternativo'], $this->co_alternativo->FldDataType));
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
		return "[co_alternativo] = '@co_alternativo@' AND [nu_versao] = @nu_versao@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		$sKeyFilter = str_replace("@co_alternativo@", ew_AdjustSql($this->co_alternativo->CurrentValue), $sKeyFilter); // Replace key value
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
			return "regranegociolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "regranegociolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("regranegocioview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("regranegocioview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "regranegocioadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("regranegocioedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("regranegocioadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("regranegociodelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->co_alternativo->CurrentValue)) {
			$sUrl .= "co_alternativo=" . urlencode($this->co_alternativo->CurrentValue);
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
			$arKey[] = @$_GET["co_alternativo"]; // co_alternativo
			$arKey[] = @$_GET["nu_versao"]; // nu_versao
			$arKeys[] = $arKey;

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_array($key) || count($key) <> 2)
				continue; // Just skip so other keys will still work
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
			$this->co_alternativo->CurrentValue = $key[0];
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
		$this->co_alternativo->setDbValue($rs->fields('co_alternativo'));
		$this->nu_versao->setDbValue($rs->fields('nu_versao'));
		$this->no_regraNegocio->setDbValue($rs->fields('no_regraNegocio'));
		$this->ds_regraNegocio->setDbValue($rs->fields('ds_regraNegocio'));
		$this->nu_area->setDbValue($rs->fields('nu_area'));
		$this->ds_origemRegra->setDbValue($rs->fields('ds_origemRegra'));
		$this->nu_projeto->setDbValue($rs->fields('nu_projeto'));
		$this->no_tags->setDbValue($rs->fields('no_tags'));
		$this->nu_stRegraNegocio->setDbValue($rs->fields('nu_stRegraNegocio'));
		$this->nu_usuario->setDbValue($rs->fields('nu_usuario'));
		$this->dt_versao->setDbValue($rs->fields('dt_versao'));
		$this->hh_versao->setDbValue($rs->fields('hh_versao'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// co_alternativo
		// nu_versao
		// no_regraNegocio
		// ds_regraNegocio
		// nu_area
		// ds_origemRegra
		// nu_projeto
		// no_tags
		// nu_stRegraNegocio
		// nu_usuario
		// dt_versao
		// hh_versao
		// co_alternativo

		if (strval($this->co_alternativo->CurrentValue) <> "") {
			$sFilterWrk = "[co_rn]" . ew_SearchString("=", $this->co_alternativo->CurrentValue, EW_DATATYPE_STRING);
		$sSqlWrk = "SELECT [co_rn], [co_rn] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[corn]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->co_alternativo, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [co_rn] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->co_alternativo->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->co_alternativo->ViewValue = $this->co_alternativo->CurrentValue;
			}
		} else {
			$this->co_alternativo->ViewValue = NULL;
		}
		$this->co_alternativo->ViewCustomAttributes = "";

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
		$this->nu_projeto->ViewCustomAttributes = "";

		// no_tags
		$this->no_tags->ViewValue = $this->no_tags->CurrentValue;
		$this->no_tags->ViewCustomAttributes = "";

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

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_stRegraNegocio, $sWhereWrk);
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

		// nu_usuario
		$this->nu_usuario->ViewValue = $this->nu_usuario->CurrentValue;
		if (strval($this->nu_usuario->CurrentValue) <> "") {
			$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuario->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_usuario, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_usuario->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_usuario->ViewValue = $this->nu_usuario->CurrentValue;
			}
		} else {
			$this->nu_usuario->ViewValue = NULL;
		}
		$this->nu_usuario->ViewCustomAttributes = "";

		// dt_versao
		$this->dt_versao->ViewValue = $this->dt_versao->CurrentValue;
		$this->dt_versao->ViewValue = ew_FormatDateTime($this->dt_versao->ViewValue, 7);
		$this->dt_versao->ViewCustomAttributes = "";

		// hh_versao
		$this->hh_versao->ViewValue = $this->hh_versao->CurrentValue;
		$this->hh_versao->ViewValue = ew_FormatDateTime($this->hh_versao->ViewValue, 4);
		$this->hh_versao->ViewCustomAttributes = "";

		// co_alternativo
		$this->co_alternativo->LinkCustomAttributes = "";
		$this->co_alternativo->HrefValue = "";
		$this->co_alternativo->TooltipValue = "";

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

		// no_tags
		$this->no_tags->LinkCustomAttributes = "";
		$this->no_tags->HrefValue = "";
		$this->no_tags->TooltipValue = "";

		// nu_stRegraNegocio
		$this->nu_stRegraNegocio->LinkCustomAttributes = "";
		$this->nu_stRegraNegocio->HrefValue = "";
		$this->nu_stRegraNegocio->TooltipValue = "";

		// nu_usuario
		$this->nu_usuario->LinkCustomAttributes = "";
		$this->nu_usuario->HrefValue = "";
		$this->nu_usuario->TooltipValue = "";

		// dt_versao
		$this->dt_versao->LinkCustomAttributes = "";
		$this->dt_versao->HrefValue = "";
		$this->dt_versao->TooltipValue = "";

		// hh_versao
		$this->hh_versao->LinkCustomAttributes = "";
		$this->hh_versao->HrefValue = "";
		$this->hh_versao->TooltipValue = "";

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
				if ($this->co_alternativo->Exportable) $Doc->ExportCaption($this->co_alternativo);
				if ($this->nu_versao->Exportable) $Doc->ExportCaption($this->nu_versao);
				if ($this->no_regraNegocio->Exportable) $Doc->ExportCaption($this->no_regraNegocio);
				if ($this->ds_regraNegocio->Exportable) $Doc->ExportCaption($this->ds_regraNegocio);
				if ($this->nu_area->Exportable) $Doc->ExportCaption($this->nu_area);
				if ($this->ds_origemRegra->Exportable) $Doc->ExportCaption($this->ds_origemRegra);
				if ($this->nu_projeto->Exportable) $Doc->ExportCaption($this->nu_projeto);
				if ($this->no_tags->Exportable) $Doc->ExportCaption($this->no_tags);
				if ($this->nu_stRegraNegocio->Exportable) $Doc->ExportCaption($this->nu_stRegraNegocio);
				if ($this->nu_usuario->Exportable) $Doc->ExportCaption($this->nu_usuario);
				if ($this->dt_versao->Exportable) $Doc->ExportCaption($this->dt_versao);
				if ($this->hh_versao->Exportable) $Doc->ExportCaption($this->hh_versao);
			} else {
				if ($this->co_alternativo->Exportable) $Doc->ExportCaption($this->co_alternativo);
				if ($this->nu_versao->Exportable) $Doc->ExportCaption($this->nu_versao);
				if ($this->no_regraNegocio->Exportable) $Doc->ExportCaption($this->no_regraNegocio);
				if ($this->nu_area->Exportable) $Doc->ExportCaption($this->nu_area);
				if ($this->nu_projeto->Exportable) $Doc->ExportCaption($this->nu_projeto);
				if ($this->no_tags->Exportable) $Doc->ExportCaption($this->no_tags);
				if ($this->nu_stRegraNegocio->Exportable) $Doc->ExportCaption($this->nu_stRegraNegocio);
				if ($this->nu_usuario->Exportable) $Doc->ExportCaption($this->nu_usuario);
				if ($this->dt_versao->Exportable) $Doc->ExportCaption($this->dt_versao);
				if ($this->hh_versao->Exportable) $Doc->ExportCaption($this->hh_versao);
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
					if ($this->co_alternativo->Exportable) $Doc->ExportField($this->co_alternativo);
					if ($this->nu_versao->Exportable) $Doc->ExportField($this->nu_versao);
					if ($this->no_regraNegocio->Exportable) $Doc->ExportField($this->no_regraNegocio);
					if ($this->ds_regraNegocio->Exportable) $Doc->ExportField($this->ds_regraNegocio);
					if ($this->nu_area->Exportable) $Doc->ExportField($this->nu_area);
					if ($this->ds_origemRegra->Exportable) $Doc->ExportField($this->ds_origemRegra);
					if ($this->nu_projeto->Exportable) $Doc->ExportField($this->nu_projeto);
					if ($this->no_tags->Exportable) $Doc->ExportField($this->no_tags);
					if ($this->nu_stRegraNegocio->Exportable) $Doc->ExportField($this->nu_stRegraNegocio);
					if ($this->nu_usuario->Exportable) $Doc->ExportField($this->nu_usuario);
					if ($this->dt_versao->Exportable) $Doc->ExportField($this->dt_versao);
					if ($this->hh_versao->Exportable) $Doc->ExportField($this->hh_versao);
				} else {
					if ($this->co_alternativo->Exportable) $Doc->ExportField($this->co_alternativo);
					if ($this->nu_versao->Exportable) $Doc->ExportField($this->nu_versao);
					if ($this->no_regraNegocio->Exportable) $Doc->ExportField($this->no_regraNegocio);
					if ($this->nu_area->Exportable) $Doc->ExportField($this->nu_area);
					if ($this->nu_projeto->Exportable) $Doc->ExportField($this->nu_projeto);
					if ($this->no_tags->Exportable) $Doc->ExportField($this->no_tags);
					if ($this->nu_stRegraNegocio->Exportable) $Doc->ExportField($this->nu_stRegraNegocio);
					if ($this->nu_usuario->Exportable) $Doc->ExportField($this->nu_usuario);
					if ($this->dt_versao->Exportable) $Doc->ExportField($this->dt_versao);
					if ($this->hh_versao->Exportable) $Doc->ExportField($this->hh_versao);
				}
				$Doc->EndExportRow();
			}
			$Recordset->MoveNext();
		}
		$Doc->ExportTableFooter();
	}

	// Add User ID filter
	function AddUserIDFilter($sFilter) {
		global $Security;
		$sFilterWrk = "";
		$id = (CurrentPageID() == "list") ? $this->CurrentAction : CurrentPageID();
		if (!$this->UserIDAllow($id) && !$Security->IsAdmin()) {
			$sFilterWrk = $Security->UserIDList();
			if ($sFilterWrk <> "")
				$sFilterWrk = '[nu_usuario] IN (' . $sFilterWrk . ')';
		}

		// Call Row Rendered event
		$this->UserID_Filtering($sFilterWrk);
		ew_AddFilter($sFilter, $sFilterWrk);
		return $sFilter;
	}

	// User ID subquery
	function GetUserIDSubquery(&$fld, &$masterfld) {
		global $conn;
		$sWrk = "";
		$sSql = "SELECT " . $masterfld->FldExpression . " FROM [dbo].[regranegocio]";
		$sFilter = $this->AddUserIDFilter("");
		if ($sFilter <> "") $sSql .= " WHERE " . $sFilter;

		// Use subquery
		if (EW_USE_SUBQUERY_FOR_MASTER_USER_ID) {
			$sWrk = $sSql;
		} else {

			// List all values
			if ($rs = $conn->Execute($sSql)) {
				while (!$rs->EOF) {
					if ($sWrk <> "") $sWrk .= ",";
					$sWrk .= ew_QuotedValue($rs->fields[0], $masterfld->FldDataType);
					$rs->MoveNext();
				}
				$rs->Close();
			}
		}
		if ($sWrk <> "") {
			$sWrk = $fld->FldExpression . " IN (" . $sWrk . ")";
		}
		return $sWrk;
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
		//$rs = GetFieldValues("FormValue"); // Get the form values as array

		$valor = ew_ExecuteScalar("select max(nu_versao) From regranegocio where co_alternativo = '" . $rsnew["co_alternativo"] . "'");
		$rsnew["nu_versao"] = $valor + 1; 

		//$rsnew["nu_usuario"] = "". ;  
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

		$valor = ew_ExecuteScalar("select max(nu_versao) From regranegocio where co_alternativo = '" . $this->co_alternativo->ViewValue . "'");
		$this->nu_versao->EditValue = "" . $valor + 1 . "";
	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
