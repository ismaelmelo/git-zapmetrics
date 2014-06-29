<?php

// Global variable for table object
$projeto = NULL;

//
// Table class for projeto
//
class cprojeto extends cTable {
	var $nu_projeto;
	var $nu_contrato;
	var $nu_itemContrato;
	var $nu_prospecto;
	var $nu_tpProjeto;
	var $nu_projetoInteg;
	var $no_projeto;
	var $id_tarefaTpProj;
	var $ic_complexProjeto;
	var $ic_passivelContPf;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'projeto';
		$this->TableName = 'projeto';
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

		// nu_projeto
		$this->nu_projeto = new cField('projeto', 'projeto', 'x_nu_projeto', 'nu_projeto', '[nu_projeto]', 'CAST([nu_projeto] AS NVARCHAR)', 3, -1, FALSE, '[nu_projeto]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_projeto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_projeto'] = &$this->nu_projeto;

		// nu_contrato
		$this->nu_contrato = new cField('projeto', 'projeto', 'x_nu_contrato', 'nu_contrato', '[nu_contrato]', 'CAST([nu_contrato] AS NVARCHAR)', 3, -1, FALSE, '[EV__nu_contrato]', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->nu_contrato->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_contrato'] = &$this->nu_contrato;

		// nu_itemContrato
		$this->nu_itemContrato = new cField('projeto', 'projeto', 'x_nu_itemContrato', 'nu_itemContrato', '[nu_itemContrato]', 'CAST([nu_itemContrato] AS NVARCHAR)', 3, -1, FALSE, '[EV__nu_itemContrato]', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->nu_itemContrato->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_itemContrato'] = &$this->nu_itemContrato;

		// nu_prospecto
		$this->nu_prospecto = new cField('projeto', 'projeto', 'x_nu_prospecto', 'nu_prospecto', '[nu_prospecto]', 'CAST([nu_prospecto] AS NVARCHAR)', 3, -1, FALSE, '[nu_prospecto]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_prospecto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_prospecto'] = &$this->nu_prospecto;

		// nu_tpProjeto
		$this->nu_tpProjeto = new cField('projeto', 'projeto', 'x_nu_tpProjeto', 'nu_tpProjeto', '[nu_tpProjeto]', 'CAST([nu_tpProjeto] AS NVARCHAR)', 3, -1, FALSE, '[nu_tpProjeto]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_tpProjeto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_tpProjeto'] = &$this->nu_tpProjeto;

		// nu_projetoInteg
		$this->nu_projetoInteg = new cField('projeto', 'projeto', 'x_nu_projetoInteg', 'nu_projetoInteg', '[nu_projetoInteg]', 'CAST([nu_projetoInteg] AS NVARCHAR)', 3, -1, FALSE, '[nu_projetoInteg]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_projetoInteg->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_projetoInteg'] = &$this->nu_projetoInteg;

		// no_projeto
		$this->no_projeto = new cField('projeto', 'projeto', 'x_no_projeto', 'no_projeto', '[no_projeto]', '[no_projeto]', 200, -1, FALSE, '[no_projeto]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_projeto'] = &$this->no_projeto;

		// id_tarefaTpProj
		$this->id_tarefaTpProj = new cField('projeto', 'projeto', 'x_id_tarefaTpProj', 'id_tarefaTpProj', '[id_tarefaTpProj]', 'CAST([id_tarefaTpProj] AS NVARCHAR)', 3, -1, FALSE, '[id_tarefaTpProj]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_tarefaTpProj->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_tarefaTpProj'] = &$this->id_tarefaTpProj;

		// ic_complexProjeto
		$this->ic_complexProjeto = new cField('projeto', 'projeto', 'x_ic_complexProjeto', 'ic_complexProjeto', '[ic_complexProjeto]', '[ic_complexProjeto]', 129, -1, FALSE, '[ic_complexProjeto]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_complexProjeto'] = &$this->ic_complexProjeto;

		// ic_passivelContPf
		$this->ic_passivelContPf = new cField('projeto', 'projeto', 'x_ic_passivelContPf', 'ic_passivelContPf', '[ic_passivelContPf]', '[ic_passivelContPf]', 129, -1, FALSE, '[ic_passivelContPf]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->ic_passivelContPf->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['ic_passivelContPf'] = &$this->ic_passivelContPf;
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
		if ($this->getCurrentDetailTable() == "projeto_centrocusto") {
			$sDetailUrl = $GLOBALS["projeto_centrocusto"]->GetListUrl() . "?showmaster=" . $this->TableVar;
			$sDetailUrl .= "&nu_projeto=" . $this->nu_projeto->CurrentValue;
		}
		if ($this->getCurrentDetailTable() == "riscoprojeto") {
			$sDetailUrl = $GLOBALS["riscoprojeto"]->GetListUrl() . "?showmaster=" . $this->TableVar;
			$sDetailUrl .= "&nu_projeto=" . $this->nu_projeto->CurrentValue;
		}
		if ($sDetailUrl == "") {
			$sDetailUrl = "projetolist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	function SqlFrom() { // From
		return "[dbo].[projeto]";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlSelectList() { // Select for List page
		return "SELECT * FROM (" .
			"SELECT *, (SELECT TOP 1 [nu_contrato] + '" . ew_ValueSeparator(1, $this->nu_contrato) . "' + [no_contrato] FROM [contrato] [EW_TMP_LOOKUPTABLE] WHERE [EW_TMP_LOOKUPTABLE].[nu_contrato] = [projeto].[nu_contrato]) AS [EV__nu_contrato], (SELECT TOP 1 [nu_itemOc] + '" . ew_ValueSeparator(1, $this->nu_itemContrato) . "' + [no_itemContratado] FROM [item_contratado] [EW_TMP_LOOKUPTABLE] WHERE [EW_TMP_LOOKUPTABLE].[nu_itemContratado] = [projeto].[nu_itemContrato]) AS [EV__nu_itemContrato] FROM [dbo].[projeto]" .
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
		return "[nu_projeto] DESC";
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
		if ($this->nu_contrato->AdvancedSearch->SearchValue <> "" ||
			$this->nu_contrato->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->nu_contrato->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->nu_contrato->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->nu_itemContrato->AdvancedSearch->SearchValue <> "" ||
			$this->nu_itemContrato->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->nu_itemContrato->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->nu_itemContrato->FldVirtualExpression . " ") !== FALSE)
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
	var $UpdateTable = "[dbo].[projeto]";

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

		// Cascade update detail field 'nu_projeto'
		if (!is_null($rsold) && (isset($rs['nu_projeto']) && $rsold['nu_projeto'] <> $rs['nu_projeto'])) {
			if (!isset($GLOBALS["projeto_centrocusto"])) $GLOBALS["projeto_centrocusto"] = new cprojeto_centrocusto();
			$rscascade = array();
			$rscascade['nu_projeto'] = $rs['nu_projeto']; 
			$GLOBALS["projeto_centrocusto"]->Update($rscascade, "[nu_projeto] = " . ew_QuotedValue($rsold['nu_projeto'], EW_DATATYPE_NUMBER));
		}
		return $conn->Execute($this->UpdateSQL($rs, $where));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "") {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if ($rs) {
			if (array_key_exists('nu_projeto', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_projeto') . '=' . ew_QuotedValue($rs['nu_projeto'], $this->nu_projeto->FldDataType));
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

		// Cascade delete detail table 'projeto_centrocusto'
		if (!isset($GLOBALS["projeto_centrocusto"])) $GLOBALS["projeto_centrocusto"] = new cprojeto_centrocusto();
		$rscascade = array();
		$GLOBALS["projeto_centrocusto"]->Delete($rscascade, "[nu_projeto] = " . ew_QuotedValue($rs['nu_projeto'], EW_DATATYPE_NUMBER));
		return $conn->Execute($this->DeleteSQL($rs, $where));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "[nu_projeto] = @nu_projeto@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nu_projeto->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_projeto@", ew_AdjustSql($this->nu_projeto->CurrentValue), $sKeyFilter); // Replace key value
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
			return "projetolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "projetolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("projetoview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("projetoview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "projetoadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("projetoedit.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("projetoedit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("projetoadd.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("projetoadd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("projetodelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nu_projeto->CurrentValue)) {
			$sUrl .= "nu_projeto=" . urlencode($this->nu_projeto->CurrentValue);
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
			$arKeys[] = @$_GET["nu_projeto"]; // nu_projeto

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
			$this->nu_projeto->CurrentValue = $key;
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
		$this->nu_projeto->setDbValue($rs->fields('nu_projeto'));
		$this->nu_contrato->setDbValue($rs->fields('nu_contrato'));
		$this->nu_itemContrato->setDbValue($rs->fields('nu_itemContrato'));
		$this->nu_prospecto->setDbValue($rs->fields('nu_prospecto'));
		$this->nu_tpProjeto->setDbValue($rs->fields('nu_tpProjeto'));
		$this->nu_projetoInteg->setDbValue($rs->fields('nu_projetoInteg'));
		$this->no_projeto->setDbValue($rs->fields('no_projeto'));
		$this->id_tarefaTpProj->setDbValue($rs->fields('id_tarefaTpProj'));
		$this->ic_complexProjeto->setDbValue($rs->fields('ic_complexProjeto'));
		$this->ic_passivelContPf->setDbValue($rs->fields('ic_passivelContPf'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_projeto
		// nu_contrato
		// nu_itemContrato
		// nu_prospecto
		// nu_tpProjeto
		// nu_projetoInteg
		// no_projeto
		// id_tarefaTpProj
		// ic_complexProjeto
		// ic_passivelContPf
		// nu_projeto

		$this->nu_projeto->ViewValue = $this->nu_projeto->CurrentValue;
		$this->nu_projeto->ViewCustomAttributes = "";

		// nu_contrato
		if ($this->nu_contrato->VirtualValue <> "") {
			$this->nu_contrato->ViewValue = $this->nu_contrato->VirtualValue;
		} else {
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
		$sSqlWrk .= " ORDER BY [no_contrato] ASC";
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
		}
		$this->nu_contrato->ViewCustomAttributes = "";

		// nu_itemContrato
		if ($this->nu_itemContrato->VirtualValue <> "") {
			$this->nu_itemContrato->ViewValue = $this->nu_itemContrato->VirtualValue;
		} else {
		if (strval($this->nu_itemContrato->CurrentValue) <> "") {
			$sFilterWrk = "[nu_itemContratado]" . ew_SearchString("=", $this->nu_itemContrato->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_itemContratado], [nu_itemOc] AS [DispFld], [no_itemContratado] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[item_contratado]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_itemContrato, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_itemContrato->ViewValue = $rswrk->fields('DispFld');
				$this->nu_itemContrato->ViewValue .= ew_ValueSeparator(1,$this->nu_itemContrato) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->nu_itemContrato->ViewValue = $this->nu_itemContrato->CurrentValue;
			}
		} else {
			$this->nu_itemContrato->ViewValue = NULL;
		}
		}
		$this->nu_itemContrato->ViewCustomAttributes = "";

		// nu_prospecto
		if (strval($this->nu_prospecto->CurrentValue) <> "") {
			$sFilterWrk = "[nu_prospecto]" . ew_SearchString("=", $this->nu_prospecto->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_prospecto], [no_prospecto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[prospecto]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_prospecto, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_prospecto] DESC";
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
		$this->nu_prospecto->ViewCustomAttributes = "";

		// nu_tpProjeto
		if (strval($this->nu_tpProjeto->CurrentValue) <> "") {
			$sFilterWrk = "[nu_tpProjeto]" . ew_SearchString("=", $this->nu_tpProjeto->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_tpProjeto], [no_tpProjeto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpprojeto]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S' AND ([ic_tpProjDem]='P' OR [ic_tpProjDem]='D')";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_tpProjeto, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_tpProjeto] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_tpProjeto->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_tpProjeto->ViewValue = $this->nu_tpProjeto->CurrentValue;
			}
		} else {
			$this->nu_tpProjeto->ViewValue = NULL;
		}
		$this->nu_tpProjeto->ViewCustomAttributes = "";

		// nu_projetoInteg
		if (strval($this->nu_projetoInteg->CurrentValue) <> "") {
			$sFilterWrk = "[id]" . ew_SearchString("=", $this->nu_projetoInteg->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tbrdm_projects]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_projetoInteg, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [created_on] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_projetoInteg->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_projetoInteg->ViewValue = $this->nu_projetoInteg->CurrentValue;
			}
		} else {
			$this->nu_projetoInteg->ViewValue = NULL;
		}
		$this->nu_projetoInteg->ViewCustomAttributes = "";

		// no_projeto
		$this->no_projeto->ViewValue = $this->no_projeto->CurrentValue;
		$this->no_projeto->ViewCustomAttributes = "";

		// id_tarefaTpProj
		$this->id_tarefaTpProj->ViewValue = $this->id_tarefaTpProj->CurrentValue;
		if (strval($this->id_tarefaTpProj->CurrentValue) <> "") {
			$sFilterWrk = "[id]" . ew_SearchString("=", $this->id_tarefaTpProj->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [id], [subject] AS [DispFld], [id] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tbrdm_issues]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->id_tarefaTpProj, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [id] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->id_tarefaTpProj->ViewValue = $rswrk->fields('DispFld');
				$this->id_tarefaTpProj->ViewValue .= ew_ValueSeparator(1,$this->id_tarefaTpProj) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->id_tarefaTpProj->ViewValue = $this->id_tarefaTpProj->CurrentValue;
			}
		} else {
			$this->id_tarefaTpProj->ViewValue = NULL;
		}
		$this->id_tarefaTpProj->ViewCustomAttributes = "";

		// ic_complexProjeto
		if (strval($this->ic_complexProjeto->CurrentValue) <> "") {
			switch ($this->ic_complexProjeto->CurrentValue) {
				case $this->ic_complexProjeto->FldTagValue(1):
					$this->ic_complexProjeto->ViewValue = $this->ic_complexProjeto->FldTagCaption(1) <> "" ? $this->ic_complexProjeto->FldTagCaption(1) : $this->ic_complexProjeto->CurrentValue;
					break;
				case $this->ic_complexProjeto->FldTagValue(2):
					$this->ic_complexProjeto->ViewValue = $this->ic_complexProjeto->FldTagCaption(2) <> "" ? $this->ic_complexProjeto->FldTagCaption(2) : $this->ic_complexProjeto->CurrentValue;
					break;
				case $this->ic_complexProjeto->FldTagValue(3):
					$this->ic_complexProjeto->ViewValue = $this->ic_complexProjeto->FldTagCaption(3) <> "" ? $this->ic_complexProjeto->FldTagCaption(3) : $this->ic_complexProjeto->CurrentValue;
					break;
				default:
					$this->ic_complexProjeto->ViewValue = $this->ic_complexProjeto->CurrentValue;
			}
		} else {
			$this->ic_complexProjeto->ViewValue = NULL;
		}
		$this->ic_complexProjeto->ViewCustomAttributes = "";

		// ic_passivelContPf
		if (strval($this->ic_passivelContPf->CurrentValue) <> "") {
			switch ($this->ic_passivelContPf->CurrentValue) {
				case $this->ic_passivelContPf->FldTagValue(1):
					$this->ic_passivelContPf->ViewValue = $this->ic_passivelContPf->FldTagCaption(1) <> "" ? $this->ic_passivelContPf->FldTagCaption(1) : $this->ic_passivelContPf->CurrentValue;
					break;
				case $this->ic_passivelContPf->FldTagValue(2):
					$this->ic_passivelContPf->ViewValue = $this->ic_passivelContPf->FldTagCaption(2) <> "" ? $this->ic_passivelContPf->FldTagCaption(2) : $this->ic_passivelContPf->CurrentValue;
					break;
				default:
					$this->ic_passivelContPf->ViewValue = $this->ic_passivelContPf->CurrentValue;
			}
		} else {
			$this->ic_passivelContPf->ViewValue = NULL;
		}
		$this->ic_passivelContPf->ViewCustomAttributes = "";

		// nu_projeto
		$this->nu_projeto->LinkCustomAttributes = "";
		$this->nu_projeto->HrefValue = "";
		$this->nu_projeto->TooltipValue = "";

		// nu_contrato
		$this->nu_contrato->LinkCustomAttributes = "";
		$this->nu_contrato->HrefValue = "";
		$this->nu_contrato->TooltipValue = "";

		// nu_itemContrato
		$this->nu_itemContrato->LinkCustomAttributes = "";
		$this->nu_itemContrato->HrefValue = "";
		$this->nu_itemContrato->TooltipValue = "";

		// nu_prospecto
		$this->nu_prospecto->LinkCustomAttributes = "";
		$this->nu_prospecto->HrefValue = "";
		$this->nu_prospecto->TooltipValue = "";

		// nu_tpProjeto
		$this->nu_tpProjeto->LinkCustomAttributes = "";
		$this->nu_tpProjeto->HrefValue = "";
		$this->nu_tpProjeto->TooltipValue = "";

		// nu_projetoInteg
		$this->nu_projetoInteg->LinkCustomAttributes = "";
		$this->nu_projetoInteg->HrefValue = "";
		$this->nu_projetoInteg->TooltipValue = "";

		// no_projeto
		$this->no_projeto->LinkCustomAttributes = "";
		$this->no_projeto->HrefValue = "";
		$this->no_projeto->TooltipValue = "";

		// id_tarefaTpProj
		$this->id_tarefaTpProj->LinkCustomAttributes = "";
		$this->id_tarefaTpProj->HrefValue = "";
		$this->id_tarefaTpProj->TooltipValue = "";

		// ic_complexProjeto
		$this->ic_complexProjeto->LinkCustomAttributes = "";
		$this->ic_complexProjeto->HrefValue = "";
		$this->ic_complexProjeto->TooltipValue = "";

		// ic_passivelContPf
		$this->ic_passivelContPf->LinkCustomAttributes = "";
		$this->ic_passivelContPf->HrefValue = "";
		$this->ic_passivelContPf->TooltipValue = "";

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
				if ($this->nu_projeto->Exportable) $Doc->ExportCaption($this->nu_projeto);
				if ($this->nu_contrato->Exportable) $Doc->ExportCaption($this->nu_contrato);
				if ($this->nu_itemContrato->Exportable) $Doc->ExportCaption($this->nu_itemContrato);
				if ($this->nu_prospecto->Exportable) $Doc->ExportCaption($this->nu_prospecto);
				if ($this->nu_tpProjeto->Exportable) $Doc->ExportCaption($this->nu_tpProjeto);
				if ($this->nu_projetoInteg->Exportable) $Doc->ExportCaption($this->nu_projetoInteg);
				if ($this->no_projeto->Exportable) $Doc->ExportCaption($this->no_projeto);
				if ($this->id_tarefaTpProj->Exportable) $Doc->ExportCaption($this->id_tarefaTpProj);
				if ($this->ic_complexProjeto->Exportable) $Doc->ExportCaption($this->ic_complexProjeto);
				if ($this->ic_passivelContPf->Exportable) $Doc->ExportCaption($this->ic_passivelContPf);
			} else {
				if ($this->nu_projeto->Exportable) $Doc->ExportCaption($this->nu_projeto);
				if ($this->nu_contrato->Exportable) $Doc->ExportCaption($this->nu_contrato);
				if ($this->nu_itemContrato->Exportable) $Doc->ExportCaption($this->nu_itemContrato);
				if ($this->nu_prospecto->Exportable) $Doc->ExportCaption($this->nu_prospecto);
				if ($this->nu_tpProjeto->Exportable) $Doc->ExportCaption($this->nu_tpProjeto);
				if ($this->nu_projetoInteg->Exportable) $Doc->ExportCaption($this->nu_projetoInteg);
				if ($this->no_projeto->Exportable) $Doc->ExportCaption($this->no_projeto);
				if ($this->id_tarefaTpProj->Exportable) $Doc->ExportCaption($this->id_tarefaTpProj);
				if ($this->ic_complexProjeto->Exportable) $Doc->ExportCaption($this->ic_complexProjeto);
				if ($this->ic_passivelContPf->Exportable) $Doc->ExportCaption($this->ic_passivelContPf);
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
					if ($this->nu_projeto->Exportable) $Doc->ExportField($this->nu_projeto);
					if ($this->nu_contrato->Exportable) $Doc->ExportField($this->nu_contrato);
					if ($this->nu_itemContrato->Exportable) $Doc->ExportField($this->nu_itemContrato);
					if ($this->nu_prospecto->Exportable) $Doc->ExportField($this->nu_prospecto);
					if ($this->nu_tpProjeto->Exportable) $Doc->ExportField($this->nu_tpProjeto);
					if ($this->nu_projetoInteg->Exportable) $Doc->ExportField($this->nu_projetoInteg);
					if ($this->no_projeto->Exportable) $Doc->ExportField($this->no_projeto);
					if ($this->id_tarefaTpProj->Exportable) $Doc->ExportField($this->id_tarefaTpProj);
					if ($this->ic_complexProjeto->Exportable) $Doc->ExportField($this->ic_complexProjeto);
					if ($this->ic_passivelContPf->Exportable) $Doc->ExportField($this->ic_passivelContPf);
				} else {
					if ($this->nu_projeto->Exportable) $Doc->ExportField($this->nu_projeto);
					if ($this->nu_contrato->Exportable) $Doc->ExportField($this->nu_contrato);
					if ($this->nu_itemContrato->Exportable) $Doc->ExportField($this->nu_itemContrato);
					if ($this->nu_prospecto->Exportable) $Doc->ExportField($this->nu_prospecto);
					if ($this->nu_tpProjeto->Exportable) $Doc->ExportField($this->nu_tpProjeto);
					if ($this->nu_projetoInteg->Exportable) $Doc->ExportField($this->nu_projetoInteg);
					if ($this->no_projeto->Exportable) $Doc->ExportField($this->no_projeto);
					if ($this->id_tarefaTpProj->Exportable) $Doc->ExportField($this->id_tarefaTpProj);
					if ($this->ic_complexProjeto->Exportable) $Doc->ExportField($this->ic_complexProjeto);
					if ($this->ic_passivelContPf->Exportable) $Doc->ExportField($this->ic_passivelContPf);
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
