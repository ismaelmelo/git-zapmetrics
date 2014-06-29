<?php

// Global variable for table object
$tpmanutencao = NULL;

//
// Table class for tpmanutencao
//
class ctpmanutencao extends cTable {
	var $nu_tpManutencao;
	var $nu_tpContagem;
	var $no_tpManutencao;
	var $ic_modeloCalculo;
	var $ic_utilizaFaseRoteiroCalculo;
	var $nu_parametro;
	var $ds_helpTela;
	var $ic_ativo;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'tpmanutencao';
		$this->TableName = 'tpmanutencao';
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

		// nu_tpManutencao
		$this->nu_tpManutencao = new cField('tpmanutencao', 'tpmanutencao', 'x_nu_tpManutencao', 'nu_tpManutencao', '[nu_tpManutencao]', 'CAST([nu_tpManutencao] AS NVARCHAR)', 3, -1, FALSE, '[nu_tpManutencao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_tpManutencao->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_tpManutencao'] = &$this->nu_tpManutencao;

		// nu_tpContagem
		$this->nu_tpContagem = new cField('tpmanutencao', 'tpmanutencao', 'x_nu_tpContagem', 'nu_tpContagem', '[nu_tpContagem]', 'CAST([nu_tpContagem] AS NVARCHAR)', 3, -1, FALSE, '[nu_tpContagem]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_tpContagem->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_tpContagem'] = &$this->nu_tpContagem;

		// no_tpManutencao
		$this->no_tpManutencao = new cField('tpmanutencao', 'tpmanutencao', 'x_no_tpManutencao', 'no_tpManutencao', '[no_tpManutencao]', '[no_tpManutencao]', 200, -1, FALSE, '[no_tpManutencao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_tpManutencao'] = &$this->no_tpManutencao;

		// ic_modeloCalculo
		$this->ic_modeloCalculo = new cField('tpmanutencao', 'tpmanutencao', 'x_ic_modeloCalculo', 'ic_modeloCalculo', '[ic_modeloCalculo]', '[ic_modeloCalculo]', 129, -1, FALSE, '[ic_modeloCalculo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_modeloCalculo'] = &$this->ic_modeloCalculo;

		// ic_utilizaFaseRoteiroCalculo
		$this->ic_utilizaFaseRoteiroCalculo = new cField('tpmanutencao', 'tpmanutencao', 'x_ic_utilizaFaseRoteiroCalculo', 'ic_utilizaFaseRoteiroCalculo', '[ic_utilizaFaseRoteiroCalculo]', '[ic_utilizaFaseRoteiroCalculo]', 129, -1, FALSE, '[ic_utilizaFaseRoteiroCalculo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_utilizaFaseRoteiroCalculo'] = &$this->ic_utilizaFaseRoteiroCalculo;

		// nu_parametro
		$this->nu_parametro = new cField('tpmanutencao', 'tpmanutencao', 'x_nu_parametro', 'nu_parametro', '[nu_parametro]', 'CAST([nu_parametro] AS NVARCHAR)', 3, -1, FALSE, '[nu_parametro]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_parametro->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_parametro'] = &$this->nu_parametro;

		// ds_helpTela
		$this->ds_helpTela = new cField('tpmanutencao', 'tpmanutencao', 'x_ds_helpTela', 'ds_helpTela', '[ds_helpTela]', '[ds_helpTela]', 201, -1, FALSE, '[ds_helpTela]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_helpTela'] = &$this->ds_helpTela;

		// ic_ativo
		$this->ic_ativo = new cField('tpmanutencao', 'tpmanutencao', 'x_ic_ativo', 'ic_ativo', '[ic_ativo]', '[ic_ativo]', 129, -1, FALSE, '[ic_ativo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
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
		if ($this->getCurrentMasterTable() == "tpcontagem") {
			if ($this->nu_tpContagem->getSessionValue() <> "")
				$sMasterFilter .= "[nu_tpContagem]=" . ew_QuotedValue($this->nu_tpContagem->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "tpcontagem") {
			if ($this->nu_tpContagem->getSessionValue() <> "")
				$sDetailFilter .= "[nu_tpContagem]=" . ew_QuotedValue($this->nu_tpContagem->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_tpcontagem() {
		return "[nu_tpContagem]=@nu_tpContagem@";
	}

	// Detail filter
	function SqlDetailFilter_tpcontagem() {
		return "[nu_tpContagem]=@nu_tpContagem@";
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
		if ($this->getCurrentDetailTable() == "tpElemento") {
			$sDetailUrl = $GLOBALS["tpElemento"]->GetListUrl() . "?showmaster=" . $this->TableVar;
			$sDetailUrl .= "&nu_tpManutencao=" . $this->nu_tpManutencao->CurrentValue;
		}
		if ($sDetailUrl == "") {
			$sDetailUrl = "tpmanutencaolist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	function SqlFrom() { // From
		return "[dbo].[tpmanutencao]";
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
	var $UpdateTable = "[dbo].[tpmanutencao]";

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
			if (array_key_exists('nu_tpManutencao', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_tpManutencao') . '=' . ew_QuotedValue($rs['nu_tpManutencao'], $this->nu_tpManutencao->FldDataType));
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
		return "[nu_tpManutencao] = @nu_tpManutencao@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nu_tpManutencao->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_tpManutencao@", ew_AdjustSql($this->nu_tpManutencao->CurrentValue), $sKeyFilter); // Replace key value
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
			return "tpmanutencaolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "tpmanutencaolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("tpmanutencaoview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("tpmanutencaoview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "tpmanutencaoadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("tpmanutencaoedit.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("tpmanutencaoedit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("tpmanutencaoadd.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("tpmanutencaoadd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("tpmanutencaodelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nu_tpManutencao->CurrentValue)) {
			$sUrl .= "nu_tpManutencao=" . urlencode($this->nu_tpManutencao->CurrentValue);
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
			$arKeys[] = @$_GET["nu_tpManutencao"]; // nu_tpManutencao

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
			$this->nu_tpManutencao->CurrentValue = $key;
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
		$this->nu_tpManutencao->setDbValue($rs->fields('nu_tpManutencao'));
		$this->nu_tpContagem->setDbValue($rs->fields('nu_tpContagem'));
		$this->no_tpManutencao->setDbValue($rs->fields('no_tpManutencao'));
		$this->ic_modeloCalculo->setDbValue($rs->fields('ic_modeloCalculo'));
		$this->ic_utilizaFaseRoteiroCalculo->setDbValue($rs->fields('ic_utilizaFaseRoteiroCalculo'));
		$this->nu_parametro->setDbValue($rs->fields('nu_parametro'));
		$this->ds_helpTela->setDbValue($rs->fields('ds_helpTela'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_tpManutencao

		$this->nu_tpManutencao->CellCssStyle = "white-space: nowrap;";

		// nu_tpContagem
		// no_tpManutencao
		// ic_modeloCalculo
		// ic_utilizaFaseRoteiroCalculo
		// nu_parametro
		// ds_helpTela
		// ic_ativo
		// nu_tpManutencao

		$this->nu_tpManutencao->ViewValue = $this->nu_tpManutencao->CurrentValue;
		$this->nu_tpManutencao->ViewCustomAttributes = "";

		// nu_tpContagem
		$this->nu_tpContagem->ViewValue = $this->nu_tpContagem->CurrentValue;
		$this->nu_tpContagem->ViewCustomAttributes = "";

		// no_tpManutencao
		$this->no_tpManutencao->ViewValue = $this->no_tpManutencao->CurrentValue;
		$this->no_tpManutencao->ViewCustomAttributes = "";

		// ic_modeloCalculo
		if (strval($this->ic_modeloCalculo->CurrentValue) <> "") {
			switch ($this->ic_modeloCalculo->CurrentValue) {
				case $this->ic_modeloCalculo->FldTagValue(1):
					$this->ic_modeloCalculo->ViewValue = $this->ic_modeloCalculo->FldTagCaption(1) <> "" ? $this->ic_modeloCalculo->FldTagCaption(1) : $this->ic_modeloCalculo->CurrentValue;
					break;
				case $this->ic_modeloCalculo->FldTagValue(2):
					$this->ic_modeloCalculo->ViewValue = $this->ic_modeloCalculo->FldTagCaption(2) <> "" ? $this->ic_modeloCalculo->FldTagCaption(2) : $this->ic_modeloCalculo->CurrentValue;
					break;
				case $this->ic_modeloCalculo->FldTagValue(3):
					$this->ic_modeloCalculo->ViewValue = $this->ic_modeloCalculo->FldTagCaption(3) <> "" ? $this->ic_modeloCalculo->FldTagCaption(3) : $this->ic_modeloCalculo->CurrentValue;
					break;
				default:
					$this->ic_modeloCalculo->ViewValue = $this->ic_modeloCalculo->CurrentValue;
			}
		} else {
			$this->ic_modeloCalculo->ViewValue = NULL;
		}
		$this->ic_modeloCalculo->ViewCustomAttributes = "";

		// ic_utilizaFaseRoteiroCalculo
		if (strval($this->ic_utilizaFaseRoteiroCalculo->CurrentValue) <> "") {
			switch ($this->ic_utilizaFaseRoteiroCalculo->CurrentValue) {
				case $this->ic_utilizaFaseRoteiroCalculo->FldTagValue(1):
					$this->ic_utilizaFaseRoteiroCalculo->ViewValue = $this->ic_utilizaFaseRoteiroCalculo->FldTagCaption(1) <> "" ? $this->ic_utilizaFaseRoteiroCalculo->FldTagCaption(1) : $this->ic_utilizaFaseRoteiroCalculo->CurrentValue;
					break;
				case $this->ic_utilizaFaseRoteiroCalculo->FldTagValue(2):
					$this->ic_utilizaFaseRoteiroCalculo->ViewValue = $this->ic_utilizaFaseRoteiroCalculo->FldTagCaption(2) <> "" ? $this->ic_utilizaFaseRoteiroCalculo->FldTagCaption(2) : $this->ic_utilizaFaseRoteiroCalculo->CurrentValue;
					break;
				default:
					$this->ic_utilizaFaseRoteiroCalculo->ViewValue = $this->ic_utilizaFaseRoteiroCalculo->CurrentValue;
			}
		} else {
			$this->ic_utilizaFaseRoteiroCalculo->ViewValue = NULL;
		}
		$this->ic_utilizaFaseRoteiroCalculo->ViewCustomAttributes = "";

		// nu_parametro
		if (strval($this->nu_parametro->CurrentValue) <> "") {
			$sFilterWrk = "[nu_parSisp]" . ew_SearchString("=", $this->nu_parametro->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_parSisp], [no_parSisp] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[parSisp]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_parametro, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_parSisp] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_parametro->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_parametro->ViewValue = $this->nu_parametro->CurrentValue;
			}
		} else {
			$this->nu_parametro->ViewValue = NULL;
		}
		$this->nu_parametro->ViewCustomAttributes = "";

		// ds_helpTela
		$this->ds_helpTela->ViewValue = $this->ds_helpTela->CurrentValue;
		$this->ds_helpTela->ViewCustomAttributes = "";

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

		// nu_tpManutencao
		$this->nu_tpManutencao->LinkCustomAttributes = "";
		$this->nu_tpManutencao->HrefValue = "";
		$this->nu_tpManutencao->TooltipValue = "";

		// nu_tpContagem
		$this->nu_tpContagem->LinkCustomAttributes = "";
		$this->nu_tpContagem->HrefValue = "";
		$this->nu_tpContagem->TooltipValue = "";

		// no_tpManutencao
		$this->no_tpManutencao->LinkCustomAttributes = "";
		$this->no_tpManutencao->HrefValue = "";
		$this->no_tpManutencao->TooltipValue = "";

		// ic_modeloCalculo
		$this->ic_modeloCalculo->LinkCustomAttributes = "";
		$this->ic_modeloCalculo->HrefValue = "";
		$this->ic_modeloCalculo->TooltipValue = "";

		// ic_utilizaFaseRoteiroCalculo
		$this->ic_utilizaFaseRoteiroCalculo->LinkCustomAttributes = "";
		$this->ic_utilizaFaseRoteiroCalculo->HrefValue = "";
		$this->ic_utilizaFaseRoteiroCalculo->TooltipValue = "";

		// nu_parametro
		$this->nu_parametro->LinkCustomAttributes = "";
		$this->nu_parametro->HrefValue = "";
		$this->nu_parametro->TooltipValue = "";

		// ds_helpTela
		$this->ds_helpTela->LinkCustomAttributes = "";
		$this->ds_helpTela->HrefValue = "";
		$this->ds_helpTela->TooltipValue = "";

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
				if ($this->nu_tpContagem->Exportable) $Doc->ExportCaption($this->nu_tpContagem);
				if ($this->no_tpManutencao->Exportable) $Doc->ExportCaption($this->no_tpManutencao);
				if ($this->ic_modeloCalculo->Exportable) $Doc->ExportCaption($this->ic_modeloCalculo);
				if ($this->ic_utilizaFaseRoteiroCalculo->Exportable) $Doc->ExportCaption($this->ic_utilizaFaseRoteiroCalculo);
				if ($this->nu_parametro->Exportable) $Doc->ExportCaption($this->nu_parametro);
				if ($this->ds_helpTela->Exportable) $Doc->ExportCaption($this->ds_helpTela);
				if ($this->ic_ativo->Exportable) $Doc->ExportCaption($this->ic_ativo);
			} else {
				if ($this->nu_tpManutencao->Exportable) $Doc->ExportCaption($this->nu_tpManutencao);
				if ($this->nu_tpContagem->Exportable) $Doc->ExportCaption($this->nu_tpContagem);
				if ($this->no_tpManutencao->Exportable) $Doc->ExportCaption($this->no_tpManutencao);
				if ($this->ic_modeloCalculo->Exportable) $Doc->ExportCaption($this->ic_modeloCalculo);
				if ($this->ic_utilizaFaseRoteiroCalculo->Exportable) $Doc->ExportCaption($this->ic_utilizaFaseRoteiroCalculo);
				if ($this->nu_parametro->Exportable) $Doc->ExportCaption($this->nu_parametro);
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
					if ($this->nu_tpContagem->Exportable) $Doc->ExportField($this->nu_tpContagem);
					if ($this->no_tpManutencao->Exportable) $Doc->ExportField($this->no_tpManutencao);
					if ($this->ic_modeloCalculo->Exportable) $Doc->ExportField($this->ic_modeloCalculo);
					if ($this->ic_utilizaFaseRoteiroCalculo->Exportable) $Doc->ExportField($this->ic_utilizaFaseRoteiroCalculo);
					if ($this->nu_parametro->Exportable) $Doc->ExportField($this->nu_parametro);
					if ($this->ds_helpTela->Exportable) $Doc->ExportField($this->ds_helpTela);
					if ($this->ic_ativo->Exportable) $Doc->ExportField($this->ic_ativo);
				} else {
					if ($this->nu_tpManutencao->Exportable) $Doc->ExportField($this->nu_tpManutencao);
					if ($this->nu_tpContagem->Exportable) $Doc->ExportField($this->nu_tpContagem);
					if ($this->no_tpManutencao->Exportable) $Doc->ExportField($this->no_tpManutencao);
					if ($this->ic_modeloCalculo->Exportable) $Doc->ExportField($this->ic_modeloCalculo);
					if ($this->ic_utilizaFaseRoteiroCalculo->Exportable) $Doc->ExportField($this->ic_utilizaFaseRoteiroCalculo);
					if ($this->nu_parametro->Exportable) $Doc->ExportField($this->nu_parametro);
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
