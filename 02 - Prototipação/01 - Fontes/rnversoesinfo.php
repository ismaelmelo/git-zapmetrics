<?php

// Global variable for table object
$RnVersoes = NULL;

//
// Table class for RnVersoes
//
class cRnVersoes extends cTable {
	var $co_alternativo1;
	var $no_sistema;
	var $no_uc;
	var $co_alternativo;
	var $ic_ativo;
	var $co_alternativo2;
	var $no_regraNegocio;
	var $ds_regraNegocio;
	var $nu_versao;
	var $nu_area;
	var $ds_origemRegra;
	var $nu_projeto;
	var $nu_fornecedor;
	var $nu_stRegraNegocio;
	var $dt_versao;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'RnVersoes';
		$this->TableName = 'RnVersoes';
		$this->TableType = 'CUSTOMVIEW';
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

		// co_alternativo1
		$this->co_alternativo1 = new cField('RnVersoes', 'RnVersoes', 'x_co_alternativo1', 'co_alternativo1', 'dbo.sistema.co_alternativo', 'dbo.sistema.co_alternativo', 200, -1, FALSE, 'dbo.sistema.co_alternativo', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_alternativo1'] = &$this->co_alternativo1;

		// no_sistema
		$this->no_sistema = new cField('RnVersoes', 'RnVersoes', 'x_no_sistema', 'no_sistema', 'dbo.sistema.no_sistema', 'dbo.sistema.no_sistema', 200, -1, FALSE, 'dbo.sistema.no_sistema', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_sistema'] = &$this->no_sistema;

		// no_uc
		$this->no_uc = new cField('RnVersoes', 'RnVersoes', 'x_no_uc', 'no_uc', 'dbo.uc.no_uc', 'dbo.uc.no_uc', 200, -1, FALSE, 'dbo.uc.no_uc', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_uc'] = &$this->no_uc;

		// co_alternativo
		$this->co_alternativo = new cField('RnVersoes', 'RnVersoes', 'x_co_alternativo', 'co_alternativo', 'dbo.uc.co_alternativo', 'dbo.uc.co_alternativo', 200, -1, FALSE, 'dbo.uc.co_alternativo', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_alternativo'] = &$this->co_alternativo;

		// ic_ativo
		$this->ic_ativo = new cField('RnVersoes', 'RnVersoes', 'x_ic_ativo', 'ic_ativo', 'dbo.sistema.ic_ativo', 'dbo.sistema.ic_ativo', 129, -1, FALSE, 'dbo.sistema.ic_ativo', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_ativo'] = &$this->ic_ativo;

		// co_alternativo2
		$this->co_alternativo2 = new cField('RnVersoes', 'RnVersoes', 'x_co_alternativo2', 'co_alternativo2', 'dbo.regranegocio.co_alternativo', 'dbo.regranegocio.co_alternativo', 200, -1, FALSE, 'dbo.regranegocio.co_alternativo', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_alternativo2'] = &$this->co_alternativo2;

		// no_regraNegocio
		$this->no_regraNegocio = new cField('RnVersoes', 'RnVersoes', 'x_no_regraNegocio', 'no_regraNegocio', 'dbo.regranegocio.no_regraNegocio', 'dbo.regranegocio.no_regraNegocio', 200, -1, FALSE, 'dbo.regranegocio.no_regraNegocio', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_regraNegocio'] = &$this->no_regraNegocio;

		// ds_regraNegocio
		$this->ds_regraNegocio = new cField('RnVersoes', 'RnVersoes', 'x_ds_regraNegocio', 'ds_regraNegocio', 'dbo.regranegocio.ds_regraNegocio', 'dbo.regranegocio.ds_regraNegocio', 201, -1, FALSE, 'dbo.regranegocio.ds_regraNegocio', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_regraNegocio'] = &$this->ds_regraNegocio;

		// nu_versao
		$this->nu_versao = new cField('RnVersoes', 'RnVersoes', 'x_nu_versao', 'nu_versao', 'dbo.regranegocio.nu_versao', 'CAST(dbo.regranegocio.nu_versao AS NVARCHAR)', 3, -1, FALSE, 'dbo.regranegocio.nu_versao', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_versao->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_versao'] = &$this->nu_versao;

		// nu_area
		$this->nu_area = new cField('RnVersoes', 'RnVersoes', 'x_nu_area', 'nu_area', 'dbo.regranegocio.nu_area', 'CAST(dbo.regranegocio.nu_area AS NVARCHAR)', 3, -1, FALSE, 'dbo.regranegocio.nu_area', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_area->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_area'] = &$this->nu_area;

		// ds_origemRegra
		$this->ds_origemRegra = new cField('RnVersoes', 'RnVersoes', 'x_ds_origemRegra', 'ds_origemRegra', 'dbo.regranegocio.ds_origemRegra', 'dbo.regranegocio.ds_origemRegra', 201, -1, FALSE, 'dbo.regranegocio.ds_origemRegra', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_origemRegra'] = &$this->ds_origemRegra;

		// nu_projeto
		$this->nu_projeto = new cField('RnVersoes', 'RnVersoes', 'x_nu_projeto', 'nu_projeto', 'dbo.regranegocio.nu_projeto', 'CAST(dbo.regranegocio.nu_projeto AS NVARCHAR)', 3, -1, FALSE, 'dbo.regranegocio.nu_projeto', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_projeto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_projeto'] = &$this->nu_projeto;

		// nu_fornecedor
		$this->nu_fornecedor = new cField('RnVersoes', 'RnVersoes', 'x_nu_fornecedor', 'nu_fornecedor', 'dbo.sistema.nu_fornecedor', 'CAST(dbo.sistema.nu_fornecedor AS NVARCHAR)', 3, -1, FALSE, 'dbo.sistema.nu_fornecedor', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_fornecedor->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_fornecedor'] = &$this->nu_fornecedor;

		// nu_stRegraNegocio
		$this->nu_stRegraNegocio = new cField('RnVersoes', 'RnVersoes', 'x_nu_stRegraNegocio', 'nu_stRegraNegocio', 'dbo.regranegocio.nu_stRegraNegocio', 'CAST(dbo.regranegocio.nu_stRegraNegocio AS NVARCHAR)', 3, -1, FALSE, 'dbo.regranegocio.nu_stRegraNegocio', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_stRegraNegocio->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_stRegraNegocio'] = &$this->nu_stRegraNegocio;

		// dt_versao
		$this->dt_versao = new cField('RnVersoes', 'RnVersoes', 'x_dt_versao', 'dt_versao', 'dbo.regranegocio.dt_versao', '(REPLACE(STR(DAY(dbo.regranegocio.dt_versao),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH(dbo.regranegocio.dt_versao),2,0),\' \',\'0\') + \'/\' + STR(YEAR(dbo.regranegocio.dt_versao),4,0))', 135, 7, FALSE, 'dbo.regranegocio.dt_versao', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dt_versao->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dt_versao'] = &$this->dt_versao;
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

	// Table level SQL
	function SqlFrom() { // From
		return "dbo.sistema INNER JOIN dbo.uc ON dbo.sistema.nu_sistema = dbo.uc.nu_sistema INNER JOIN dbo.uc_regranegocio ON dbo.uc.nu_uc = dbo.uc_regranegocio.nu_uc INNER JOIN dbo.regranegocio ON dbo.uc_regranegocio.co_rn = dbo.regranegocio.co_alternativo";
	}

	function SqlSelect() { // Select
		return "SELECT dbo.sistema.no_sistema, dbo.uc.no_uc, dbo.uc.co_alternativo, dbo.sistema.co_alternativo AS co_alternativo1, dbo.sistema.nu_fornecedor, dbo.sistema.ic_ativo, dbo.regranegocio.co_alternativo AS co_alternativo2, dbo.regranegocio.nu_versao, dbo.regranegocio.no_regraNegocio, dbo.regranegocio.ds_regraNegocio, dbo.regranegocio.nu_area, dbo.regranegocio.ds_origemRegra, dbo.regranegocio.nu_projeto, dbo.regranegocio.nu_stRegraNegocio, dbo.regranegocio.dt_versao FROM " . $this->SqlFrom();
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
		return "dbo.regranegocio.dt_versao DESC";
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
	var $UpdateTable = "dbo.sistema INNER JOIN dbo.uc ON dbo.sistema.nu_sistema = dbo.uc.nu_sistema INNER JOIN dbo.uc_regranegocio ON dbo.uc.nu_uc = dbo.uc_regranegocio.nu_uc INNER JOIN dbo.regranegocio ON dbo.uc_regranegocio.co_rn = dbo.regranegocio.co_alternativo";

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
			return "rnversoeslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "rnversoeslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("rnversoesview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("rnversoesview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "rnversoesadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("rnversoesedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("rnversoesadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("rnversoesdelete.php", $this->UrlParm());
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
		$this->co_alternativo1->setDbValue($rs->fields('co_alternativo1'));
		$this->no_sistema->setDbValue($rs->fields('no_sistema'));
		$this->no_uc->setDbValue($rs->fields('no_uc'));
		$this->co_alternativo->setDbValue($rs->fields('co_alternativo'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
		$this->co_alternativo2->setDbValue($rs->fields('co_alternativo2'));
		$this->no_regraNegocio->setDbValue($rs->fields('no_regraNegocio'));
		$this->ds_regraNegocio->setDbValue($rs->fields('ds_regraNegocio'));
		$this->nu_versao->setDbValue($rs->fields('nu_versao'));
		$this->nu_area->setDbValue($rs->fields('nu_area'));
		$this->ds_origemRegra->setDbValue($rs->fields('ds_origemRegra'));
		$this->nu_projeto->setDbValue($rs->fields('nu_projeto'));
		$this->nu_fornecedor->setDbValue($rs->fields('nu_fornecedor'));
		$this->nu_stRegraNegocio->setDbValue($rs->fields('nu_stRegraNegocio'));
		$this->dt_versao->setDbValue($rs->fields('dt_versao'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// co_alternativo1
		// no_sistema
		// no_uc
		// co_alternativo
		// ic_ativo
		// co_alternativo2
		// no_regraNegocio
		// ds_regraNegocio
		// nu_versao
		// nu_area
		// ds_origemRegra
		// nu_projeto
		// nu_fornecedor
		// nu_stRegraNegocio
		// dt_versao
		// co_alternativo1

		$this->co_alternativo1->ViewValue = $this->co_alternativo1->CurrentValue;
		$this->co_alternativo1->ViewCustomAttributes = "";

		// no_sistema
		$this->no_sistema->ViewValue = $this->no_sistema->CurrentValue;
		$this->no_sistema->ViewCustomAttributes = "";

		// no_uc
		$this->no_uc->ViewValue = $this->no_uc->CurrentValue;
		$this->no_uc->ViewCustomAttributes = "";

		// co_alternativo
		$this->co_alternativo->ViewValue = $this->co_alternativo->CurrentValue;
		$this->co_alternativo->ViewCustomAttributes = "";

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

		// co_alternativo2
		$this->co_alternativo2->ViewValue = $this->co_alternativo2->CurrentValue;
		$this->co_alternativo2->ViewCustomAttributes = "";

		// no_regraNegocio
		$this->no_regraNegocio->ViewValue = $this->no_regraNegocio->CurrentValue;
		$this->no_regraNegocio->ViewCustomAttributes = "";

		// ds_regraNegocio
		$this->ds_regraNegocio->ViewValue = $this->ds_regraNegocio->CurrentValue;
		$this->ds_regraNegocio->ViewCustomAttributes = "";

		// nu_versao
		$this->nu_versao->ViewValue = $this->nu_versao->CurrentValue;
		$this->nu_versao->ViewCustomAttributes = "";

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

		// nu_fornecedor
		if (strval($this->nu_fornecedor->CurrentValue) <> "") {
			$sFilterWrk = "[nu_fornecedor]" . ew_SearchString("=", $this->nu_fornecedor->CurrentValue, EW_DATATYPE_NUMBER);
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
		$this->Lookup_Selecting($this->nu_fornecedor, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_fornecedor] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_fornecedor->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_fornecedor->ViewValue = $this->nu_fornecedor->CurrentValue;
			}
		} else {
			$this->nu_fornecedor->ViewValue = NULL;
		}
		$this->nu_fornecedor->ViewCustomAttributes = "";

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

		// dt_versao
		$this->dt_versao->ViewValue = $this->dt_versao->CurrentValue;
		$this->dt_versao->ViewValue = ew_FormatDateTime($this->dt_versao->ViewValue, 7);
		$this->dt_versao->ViewCustomAttributes = "";

		// co_alternativo1
		$this->co_alternativo1->LinkCustomAttributes = "";
		$this->co_alternativo1->HrefValue = "";
		$this->co_alternativo1->TooltipValue = "";

		// no_sistema
		$this->no_sistema->LinkCustomAttributes = "";
		$this->no_sistema->HrefValue = "";
		$this->no_sistema->TooltipValue = "";

		// no_uc
		$this->no_uc->LinkCustomAttributes = "";
		$this->no_uc->HrefValue = "";
		$this->no_uc->TooltipValue = "";

		// co_alternativo
		$this->co_alternativo->LinkCustomAttributes = "";
		$this->co_alternativo->HrefValue = "";
		$this->co_alternativo->TooltipValue = "";

		// ic_ativo
		$this->ic_ativo->LinkCustomAttributes = "";
		$this->ic_ativo->HrefValue = "";
		$this->ic_ativo->TooltipValue = "";

		// co_alternativo2
		$this->co_alternativo2->LinkCustomAttributes = "";
		$this->co_alternativo2->HrefValue = "";
		$this->co_alternativo2->TooltipValue = "";

		// no_regraNegocio
		$this->no_regraNegocio->LinkCustomAttributes = "";
		$this->no_regraNegocio->HrefValue = "";
		$this->no_regraNegocio->TooltipValue = "";

		// ds_regraNegocio
		$this->ds_regraNegocio->LinkCustomAttributes = "";
		$this->ds_regraNegocio->HrefValue = "";
		$this->ds_regraNegocio->TooltipValue = "";

		// nu_versao
		$this->nu_versao->LinkCustomAttributes = "";
		$this->nu_versao->HrefValue = "";
		$this->nu_versao->TooltipValue = "";

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

		// nu_fornecedor
		$this->nu_fornecedor->LinkCustomAttributes = "";
		$this->nu_fornecedor->HrefValue = "";
		$this->nu_fornecedor->TooltipValue = "";

		// nu_stRegraNegocio
		$this->nu_stRegraNegocio->LinkCustomAttributes = "";
		$this->nu_stRegraNegocio->HrefValue = "";
		$this->nu_stRegraNegocio->TooltipValue = "";

		// dt_versao
		$this->dt_versao->LinkCustomAttributes = "";
		$this->dt_versao->HrefValue = "";
		$this->dt_versao->TooltipValue = "";

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
				if ($this->co_alternativo1->Exportable) $Doc->ExportCaption($this->co_alternativo1);
				if ($this->no_sistema->Exportable) $Doc->ExportCaption($this->no_sistema);
				if ($this->no_uc->Exportable) $Doc->ExportCaption($this->no_uc);
				if ($this->co_alternativo->Exportable) $Doc->ExportCaption($this->co_alternativo);
				if ($this->ic_ativo->Exportable) $Doc->ExportCaption($this->ic_ativo);
				if ($this->co_alternativo2->Exportable) $Doc->ExportCaption($this->co_alternativo2);
				if ($this->no_regraNegocio->Exportable) $Doc->ExportCaption($this->no_regraNegocio);
				if ($this->ds_regraNegocio->Exportable) $Doc->ExportCaption($this->ds_regraNegocio);
				if ($this->nu_versao->Exportable) $Doc->ExportCaption($this->nu_versao);
				if ($this->nu_area->Exportable) $Doc->ExportCaption($this->nu_area);
				if ($this->ds_origemRegra->Exportable) $Doc->ExportCaption($this->ds_origemRegra);
				if ($this->nu_projeto->Exportable) $Doc->ExportCaption($this->nu_projeto);
				if ($this->nu_fornecedor->Exportable) $Doc->ExportCaption($this->nu_fornecedor);
				if ($this->nu_stRegraNegocio->Exportable) $Doc->ExportCaption($this->nu_stRegraNegocio);
				if ($this->dt_versao->Exportable) $Doc->ExportCaption($this->dt_versao);
			} else {
				if ($this->co_alternativo1->Exportable) $Doc->ExportCaption($this->co_alternativo1);
				if ($this->no_sistema->Exportable) $Doc->ExportCaption($this->no_sistema);
				if ($this->no_uc->Exportable) $Doc->ExportCaption($this->no_uc);
				if ($this->co_alternativo->Exportable) $Doc->ExportCaption($this->co_alternativo);
				if ($this->ic_ativo->Exportable) $Doc->ExportCaption($this->ic_ativo);
				if ($this->co_alternativo2->Exportable) $Doc->ExportCaption($this->co_alternativo2);
				if ($this->no_regraNegocio->Exportable) $Doc->ExportCaption($this->no_regraNegocio);
				if ($this->nu_versao->Exportable) $Doc->ExportCaption($this->nu_versao);
				if ($this->nu_area->Exportable) $Doc->ExportCaption($this->nu_area);
				if ($this->nu_projeto->Exportable) $Doc->ExportCaption($this->nu_projeto);
				if ($this->nu_fornecedor->Exportable) $Doc->ExportCaption($this->nu_fornecedor);
				if ($this->nu_stRegraNegocio->Exportable) $Doc->ExportCaption($this->nu_stRegraNegocio);
				if ($this->dt_versao->Exportable) $Doc->ExportCaption($this->dt_versao);
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
					if ($this->co_alternativo1->Exportable) $Doc->ExportField($this->co_alternativo1);
					if ($this->no_sistema->Exportable) $Doc->ExportField($this->no_sistema);
					if ($this->no_uc->Exportable) $Doc->ExportField($this->no_uc);
					if ($this->co_alternativo->Exportable) $Doc->ExportField($this->co_alternativo);
					if ($this->ic_ativo->Exportable) $Doc->ExportField($this->ic_ativo);
					if ($this->co_alternativo2->Exportable) $Doc->ExportField($this->co_alternativo2);
					if ($this->no_regraNegocio->Exportable) $Doc->ExportField($this->no_regraNegocio);
					if ($this->ds_regraNegocio->Exportable) $Doc->ExportField($this->ds_regraNegocio);
					if ($this->nu_versao->Exportable) $Doc->ExportField($this->nu_versao);
					if ($this->nu_area->Exportable) $Doc->ExportField($this->nu_area);
					if ($this->ds_origemRegra->Exportable) $Doc->ExportField($this->ds_origemRegra);
					if ($this->nu_projeto->Exportable) $Doc->ExportField($this->nu_projeto);
					if ($this->nu_fornecedor->Exportable) $Doc->ExportField($this->nu_fornecedor);
					if ($this->nu_stRegraNegocio->Exportable) $Doc->ExportField($this->nu_stRegraNegocio);
					if ($this->dt_versao->Exportable) $Doc->ExportField($this->dt_versao);
				} else {
					if ($this->co_alternativo1->Exportable) $Doc->ExportField($this->co_alternativo1);
					if ($this->no_sistema->Exportable) $Doc->ExportField($this->no_sistema);
					if ($this->no_uc->Exportable) $Doc->ExportField($this->no_uc);
					if ($this->co_alternativo->Exportable) $Doc->ExportField($this->co_alternativo);
					if ($this->ic_ativo->Exportable) $Doc->ExportField($this->ic_ativo);
					if ($this->co_alternativo2->Exportable) $Doc->ExportField($this->co_alternativo2);
					if ($this->no_regraNegocio->Exportable) $Doc->ExportField($this->no_regraNegocio);
					if ($this->nu_versao->Exportable) $Doc->ExportField($this->nu_versao);
					if ($this->nu_area->Exportable) $Doc->ExportField($this->nu_area);
					if ($this->nu_projeto->Exportable) $Doc->ExportField($this->nu_projeto);
					if ($this->nu_fornecedor->Exportable) $Doc->ExportField($this->nu_fornecedor);
					if ($this->nu_stRegraNegocio->Exportable) $Doc->ExportField($this->nu_stRegraNegocio);
					if ($this->dt_versao->Exportable) $Doc->ExportField($this->dt_versao);
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
