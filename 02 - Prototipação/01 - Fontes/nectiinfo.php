<?php

// Global variable for table object
$necti = NULL;

//
// Table class for necti
//
class cnecti extends cTable {
	var $nu_necTi;
	var $nu_periodoPei;
	var $nu_periodoPdti;
	var $nu_tpNecTi;
	var $ic_tpNec;
	var $nu_metaneg;
	var $nu_origem;
	var $nu_area;
	var $ic_gravidade;
	var $ic_urgencia;
	var $ic_tendencia;
	var $ic_prioridade;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'necti';
		$this->TableName = 'necti';
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

		// nu_necTi
		$this->nu_necTi = new cField('necti', 'necti', 'x_nu_necTi', 'nu_necTi', '[nu_necTi]', 'CAST([nu_necTi] AS NVARCHAR)', 3, -1, FALSE, '[nu_necTi]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_necTi->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_necTi'] = &$this->nu_necTi;

		// nu_periodoPei
		$this->nu_periodoPei = new cField('necti', 'necti', 'x_nu_periodoPei', 'nu_periodoPei', '[nu_periodoPei]', 'CAST([nu_periodoPei] AS NVARCHAR)', 3, -1, FALSE, '[EV__nu_periodoPei]', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->nu_periodoPei->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_periodoPei'] = &$this->nu_periodoPei;

		// nu_periodoPdti
		$this->nu_periodoPdti = new cField('necti', 'necti', 'x_nu_periodoPdti', 'nu_periodoPdti', '[nu_periodoPdti]', 'CAST([nu_periodoPdti] AS NVARCHAR)', 3, -1, FALSE, '[EV__nu_periodoPdti]', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->nu_periodoPdti->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_periodoPdti'] = &$this->nu_periodoPdti;

		// nu_tpNecTi
		$this->nu_tpNecTi = new cField('necti', 'necti', 'x_nu_tpNecTi', 'nu_tpNecTi', '[nu_tpNecTi]', 'CAST([nu_tpNecTi] AS NVARCHAR)', 3, -1, FALSE, '[nu_tpNecTi]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_tpNecTi->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_tpNecTi'] = &$this->nu_tpNecTi;

		// ic_tpNec
		$this->ic_tpNec = new cField('necti', 'necti', 'x_ic_tpNec', 'ic_tpNec', '[ic_tpNec]', '[ic_tpNec]', 129, -1, FALSE, '[ic_tpNec]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_tpNec'] = &$this->ic_tpNec;

		// nu_metaneg
		$this->nu_metaneg = new cField('necti', 'necti', 'x_nu_metaneg', 'nu_metaneg', '[nu_metaneg]', 'CAST([nu_metaneg] AS NVARCHAR)', 3, -1, FALSE, '[nu_metaneg]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_metaneg->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_metaneg'] = &$this->nu_metaneg;

		// nu_origem
		$this->nu_origem = new cField('necti', 'necti', 'x_nu_origem', 'nu_origem', '[nu_origem]', 'CAST([nu_origem] AS NVARCHAR)', 3, -1, FALSE, '[nu_origem]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_origem->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_origem'] = &$this->nu_origem;

		// nu_area
		$this->nu_area = new cField('necti', 'necti', 'x_nu_area', 'nu_area', '[nu_area]', 'CAST([nu_area] AS NVARCHAR)', 3, -1, FALSE, '[nu_area]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_area->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_area'] = &$this->nu_area;

		// ic_gravidade
		$this->ic_gravidade = new cField('necti', 'necti', 'x_ic_gravidade', 'ic_gravidade', '[ic_gravidade]', '[ic_gravidade]', 129, -1, FALSE, '[ic_gravidade]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_gravidade'] = &$this->ic_gravidade;

		// ic_urgencia
		$this->ic_urgencia = new cField('necti', 'necti', 'x_ic_urgencia', 'ic_urgencia', '[ic_urgencia]', '[ic_urgencia]', 129, -1, FALSE, '[ic_urgencia]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_urgencia'] = &$this->ic_urgencia;

		// ic_tendencia
		$this->ic_tendencia = new cField('necti', 'necti', 'x_ic_tendencia', 'ic_tendencia', '[ic_tendencia]', '[ic_tendencia]', 129, -1, FALSE, '[ic_tendencia]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_tendencia'] = &$this->ic_tendencia;

		// ic_prioridade
		$this->ic_prioridade = new cField('necti', 'necti', 'x_ic_prioridade', 'ic_prioridade', '[ic_prioridade]', '[ic_prioridade]', 129, -1, FALSE, '[ic_prioridade]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_prioridade'] = &$this->ic_prioridade;
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
		return "[dbo].[necti]";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlSelectList() { // Select for List page
		return "SELECT * FROM (" .
			"SELECT *, (SELECT TOP 1 [nu_anoInicio] + '" . ew_ValueSeparator(1, $this->nu_periodoPei) . "' + [nu_anoFim] FROM [periodopei] [EW_TMP_LOOKUPTABLE] WHERE [EW_TMP_LOOKUPTABLE].[nu_periodoPei] = [necti].[nu_periodoPei]) AS [EV__nu_periodoPei], (SELECT TOP 1 [no_periodo] FROM [perplanejamento] [EW_TMP_LOOKUPTABLE] WHERE [EW_TMP_LOOKUPTABLE].[nu_periodo] = [necti].[nu_periodoPdti]) AS [EV__nu_periodoPdti] FROM [dbo].[necti]" .
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
		if ($this->nu_periodoPei->AdvancedSearch->SearchValue <> "" ||
			$this->nu_periodoPei->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->nu_periodoPei->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->nu_periodoPei->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->nu_periodoPdti->AdvancedSearch->SearchValue <> "" ||
			$this->nu_periodoPdti->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->nu_periodoPdti->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->nu_periodoPdti->FldVirtualExpression . " ") !== FALSE)
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
	var $UpdateTable = "[dbo].[necti]";

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
			if (array_key_exists('nu_necTi', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_necTi') . '=' . ew_QuotedValue($rs['nu_necTi'], $this->nu_necTi->FldDataType));
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
		return "[nu_necTi] = @nu_necTi@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nu_necTi->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_necTi@", ew_AdjustSql($this->nu_necTi->CurrentValue), $sKeyFilter); // Replace key value
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
			return "nectilist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "nectilist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("nectiview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("nectiview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "nectiadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("nectiedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("nectiadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("nectidelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nu_necTi->CurrentValue)) {
			$sUrl .= "nu_necTi=" . urlencode($this->nu_necTi->CurrentValue);
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
			$arKeys[] = @$_GET["nu_necTi"]; // nu_necTi

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
			$this->nu_necTi->CurrentValue = $key;
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
		$this->nu_necTi->setDbValue($rs->fields('nu_necTi'));
		$this->nu_periodoPei->setDbValue($rs->fields('nu_periodoPei'));
		$this->nu_periodoPdti->setDbValue($rs->fields('nu_periodoPdti'));
		$this->nu_tpNecTi->setDbValue($rs->fields('nu_tpNecTi'));
		$this->ic_tpNec->setDbValue($rs->fields('ic_tpNec'));
		$this->nu_metaneg->setDbValue($rs->fields('nu_metaneg'));
		$this->nu_origem->setDbValue($rs->fields('nu_origem'));
		$this->nu_area->setDbValue($rs->fields('nu_area'));
		$this->ic_gravidade->setDbValue($rs->fields('ic_gravidade'));
		$this->ic_urgencia->setDbValue($rs->fields('ic_urgencia'));
		$this->ic_tendencia->setDbValue($rs->fields('ic_tendencia'));
		$this->ic_prioridade->setDbValue($rs->fields('ic_prioridade'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_necTi
		// nu_periodoPei
		// nu_periodoPdti
		// nu_tpNecTi
		// ic_tpNec
		// nu_metaneg
		// nu_origem
		// nu_area
		// ic_gravidade
		// ic_urgencia
		// ic_tendencia
		// ic_prioridade
		// nu_necTi

		$this->nu_necTi->ViewValue = $this->nu_necTi->CurrentValue;
		$this->nu_necTi->ViewCustomAttributes = "";

		// nu_periodoPei
		if ($this->nu_periodoPei->VirtualValue <> "") {
			$this->nu_periodoPei->ViewValue = $this->nu_periodoPei->VirtualValue;
		} else {
		if (strval($this->nu_periodoPei->CurrentValue) <> "") {
			$sFilterWrk = "[nu_periodoPei]" . ew_SearchString("=", $this->nu_periodoPei->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_periodoPei], [nu_anoInicio] AS [DispFld], [nu_anoFim] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[periodopei]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_periodoPei, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_anoInicio] DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_periodoPei->ViewValue = $rswrk->fields('DispFld');
				$this->nu_periodoPei->ViewValue .= ew_ValueSeparator(1,$this->nu_periodoPei) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->nu_periodoPei->ViewValue = $this->nu_periodoPei->CurrentValue;
			}
		} else {
			$this->nu_periodoPei->ViewValue = NULL;
		}
		}
		$this->nu_periodoPei->ViewCustomAttributes = "";

		// nu_periodoPdti
		if ($this->nu_periodoPdti->VirtualValue <> "") {
			$this->nu_periodoPdti->ViewValue = $this->nu_periodoPdti->VirtualValue;
		} else {
		if (strval($this->nu_periodoPdti->CurrentValue) <> "") {
			$sFilterWrk = "[nu_periodo]" . ew_SearchString("=", $this->nu_periodoPdti->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_periodo], [no_periodo] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[perplanejamento]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_periodoPdti, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_anoInicio] DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_periodoPdti->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_periodoPdti->ViewValue = $this->nu_periodoPdti->CurrentValue;
			}
		} else {
			$this->nu_periodoPdti->ViewValue = NULL;
		}
		}
		$this->nu_periodoPdti->ViewCustomAttributes = "";

		// nu_tpNecTi
		if (strval($this->nu_tpNecTi->CurrentValue) <> "") {
			$sFilterWrk = "[nu_tpNecTi]" . ew_SearchString("=", $this->nu_tpNecTi->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT DISTINCT [nu_tpNecTi], [no_tpNecTi] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpnecti]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_tpNecTi, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_tpNecTi] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_tpNecTi->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_tpNecTi->ViewValue = $this->nu_tpNecTi->CurrentValue;
			}
		} else {
			$this->nu_tpNecTi->ViewValue = NULL;
		}
		$this->nu_tpNecTi->ViewCustomAttributes = "";

		// ic_tpNec
		if (strval($this->ic_tpNec->CurrentValue) <> "") {
			switch ($this->ic_tpNec->CurrentValue) {
				case $this->ic_tpNec->FldTagValue(1):
					$this->ic_tpNec->ViewValue = $this->ic_tpNec->FldTagCaption(1) <> "" ? $this->ic_tpNec->FldTagCaption(1) : $this->ic_tpNec->CurrentValue;
					break;
				case $this->ic_tpNec->FldTagValue(2):
					$this->ic_tpNec->ViewValue = $this->ic_tpNec->FldTagCaption(2) <> "" ? $this->ic_tpNec->FldTagCaption(2) : $this->ic_tpNec->CurrentValue;
					break;
				default:
					$this->ic_tpNec->ViewValue = $this->ic_tpNec->CurrentValue;
			}
		} else {
			$this->ic_tpNec->ViewValue = NULL;
		}
		$this->ic_tpNec->ViewCustomAttributes = "";

		// nu_metaneg
		if (strval($this->nu_metaneg->CurrentValue) <> "") {
			$sFilterWrk = "[nu_metaneg]" . ew_SearchString("=", $this->nu_metaneg->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_metaneg], [no_metaneg] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[metaneg]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_metaneg, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_metaneg->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_metaneg->ViewValue = $this->nu_metaneg->CurrentValue;
			}
		} else {
			$this->nu_metaneg->ViewValue = NULL;
		}
		$this->nu_metaneg->ViewCustomAttributes = "";

		// nu_origem
		if (strval($this->nu_origem->CurrentValue) <> "") {
			$sFilterWrk = "[nu_origem]" . ew_SearchString("=", $this->nu_origem->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT DISTINCT [nu_origem], [no_origem] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[origemnecti]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_origem, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_origem] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_origem->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_origem->ViewValue = $this->nu_origem->CurrentValue;
			}
		} else {
			$this->nu_origem->ViewValue = NULL;
		}
		$this->nu_origem->ViewCustomAttributes = "";

		// nu_area
		$this->nu_area->ViewValue = $this->nu_area->CurrentValue;
		if (strval($this->nu_area->CurrentValue) <> "") {
			$sFilterWrk = "[nu_area]" . ew_SearchString("=", $this->nu_area->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_area], [no_area] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[area]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]=S && [nu_organizacao] = (SELECT nu_orgBase from organizacao)";
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

		// ic_gravidade
		if (strval($this->ic_gravidade->CurrentValue) <> "") {
			switch ($this->ic_gravidade->CurrentValue) {
				case $this->ic_gravidade->FldTagValue(1):
					$this->ic_gravidade->ViewValue = $this->ic_gravidade->FldTagCaption(1) <> "" ? $this->ic_gravidade->FldTagCaption(1) : $this->ic_gravidade->CurrentValue;
					break;
				case $this->ic_gravidade->FldTagValue(2):
					$this->ic_gravidade->ViewValue = $this->ic_gravidade->FldTagCaption(2) <> "" ? $this->ic_gravidade->FldTagCaption(2) : $this->ic_gravidade->CurrentValue;
					break;
				case $this->ic_gravidade->FldTagValue(3):
					$this->ic_gravidade->ViewValue = $this->ic_gravidade->FldTagCaption(3) <> "" ? $this->ic_gravidade->FldTagCaption(3) : $this->ic_gravidade->CurrentValue;
					break;
				case $this->ic_gravidade->FldTagValue(4):
					$this->ic_gravidade->ViewValue = $this->ic_gravidade->FldTagCaption(4) <> "" ? $this->ic_gravidade->FldTagCaption(4) : $this->ic_gravidade->CurrentValue;
					break;
				case $this->ic_gravidade->FldTagValue(5):
					$this->ic_gravidade->ViewValue = $this->ic_gravidade->FldTagCaption(5) <> "" ? $this->ic_gravidade->FldTagCaption(5) : $this->ic_gravidade->CurrentValue;
					break;
				default:
					$this->ic_gravidade->ViewValue = $this->ic_gravidade->CurrentValue;
			}
		} else {
			$this->ic_gravidade->ViewValue = NULL;
		}
		$this->ic_gravidade->ViewCustomAttributes = "";

		// ic_urgencia
		if (strval($this->ic_urgencia->CurrentValue) <> "") {
			switch ($this->ic_urgencia->CurrentValue) {
				case $this->ic_urgencia->FldTagValue(1):
					$this->ic_urgencia->ViewValue = $this->ic_urgencia->FldTagCaption(1) <> "" ? $this->ic_urgencia->FldTagCaption(1) : $this->ic_urgencia->CurrentValue;
					break;
				case $this->ic_urgencia->FldTagValue(2):
					$this->ic_urgencia->ViewValue = $this->ic_urgencia->FldTagCaption(2) <> "" ? $this->ic_urgencia->FldTagCaption(2) : $this->ic_urgencia->CurrentValue;
					break;
				case $this->ic_urgencia->FldTagValue(3):
					$this->ic_urgencia->ViewValue = $this->ic_urgencia->FldTagCaption(3) <> "" ? $this->ic_urgencia->FldTagCaption(3) : $this->ic_urgencia->CurrentValue;
					break;
				case $this->ic_urgencia->FldTagValue(4):
					$this->ic_urgencia->ViewValue = $this->ic_urgencia->FldTagCaption(4) <> "" ? $this->ic_urgencia->FldTagCaption(4) : $this->ic_urgencia->CurrentValue;
					break;
				case $this->ic_urgencia->FldTagValue(5):
					$this->ic_urgencia->ViewValue = $this->ic_urgencia->FldTagCaption(5) <> "" ? $this->ic_urgencia->FldTagCaption(5) : $this->ic_urgencia->CurrentValue;
					break;
				default:
					$this->ic_urgencia->ViewValue = $this->ic_urgencia->CurrentValue;
			}
		} else {
			$this->ic_urgencia->ViewValue = NULL;
		}
		$this->ic_urgencia->ViewCustomAttributes = "";

		// ic_tendencia
		if (strval($this->ic_tendencia->CurrentValue) <> "") {
			switch ($this->ic_tendencia->CurrentValue) {
				case $this->ic_tendencia->FldTagValue(1):
					$this->ic_tendencia->ViewValue = $this->ic_tendencia->FldTagCaption(1) <> "" ? $this->ic_tendencia->FldTagCaption(1) : $this->ic_tendencia->CurrentValue;
					break;
				case $this->ic_tendencia->FldTagValue(2):
					$this->ic_tendencia->ViewValue = $this->ic_tendencia->FldTagCaption(2) <> "" ? $this->ic_tendencia->FldTagCaption(2) : $this->ic_tendencia->CurrentValue;
					break;
				case $this->ic_tendencia->FldTagValue(3):
					$this->ic_tendencia->ViewValue = $this->ic_tendencia->FldTagCaption(3) <> "" ? $this->ic_tendencia->FldTagCaption(3) : $this->ic_tendencia->CurrentValue;
					break;
				case $this->ic_tendencia->FldTagValue(4):
					$this->ic_tendencia->ViewValue = $this->ic_tendencia->FldTagCaption(4) <> "" ? $this->ic_tendencia->FldTagCaption(4) : $this->ic_tendencia->CurrentValue;
					break;
				case $this->ic_tendencia->FldTagValue(5):
					$this->ic_tendencia->ViewValue = $this->ic_tendencia->FldTagCaption(5) <> "" ? $this->ic_tendencia->FldTagCaption(5) : $this->ic_tendencia->CurrentValue;
					break;
				default:
					$this->ic_tendencia->ViewValue = $this->ic_tendencia->CurrentValue;
			}
		} else {
			$this->ic_tendencia->ViewValue = NULL;
		}
		$this->ic_tendencia->ViewCustomAttributes = "";

		// ic_prioridade
		if (strval($this->ic_prioridade->CurrentValue) <> "") {
			switch ($this->ic_prioridade->CurrentValue) {
				case $this->ic_prioridade->FldTagValue(1):
					$this->ic_prioridade->ViewValue = $this->ic_prioridade->FldTagCaption(1) <> "" ? $this->ic_prioridade->FldTagCaption(1) : $this->ic_prioridade->CurrentValue;
					break;
				case $this->ic_prioridade->FldTagValue(2):
					$this->ic_prioridade->ViewValue = $this->ic_prioridade->FldTagCaption(2) <> "" ? $this->ic_prioridade->FldTagCaption(2) : $this->ic_prioridade->CurrentValue;
					break;
				case $this->ic_prioridade->FldTagValue(3):
					$this->ic_prioridade->ViewValue = $this->ic_prioridade->FldTagCaption(3) <> "" ? $this->ic_prioridade->FldTagCaption(3) : $this->ic_prioridade->CurrentValue;
					break;
				case $this->ic_prioridade->FldTagValue(4):
					$this->ic_prioridade->ViewValue = $this->ic_prioridade->FldTagCaption(4) <> "" ? $this->ic_prioridade->FldTagCaption(4) : $this->ic_prioridade->CurrentValue;
					break;
				case $this->ic_prioridade->FldTagValue(5):
					$this->ic_prioridade->ViewValue = $this->ic_prioridade->FldTagCaption(5) <> "" ? $this->ic_prioridade->FldTagCaption(5) : $this->ic_prioridade->CurrentValue;
					break;
				default:
					$this->ic_prioridade->ViewValue = $this->ic_prioridade->CurrentValue;
			}
		} else {
			$this->ic_prioridade->ViewValue = NULL;
		}
		$this->ic_prioridade->ViewCustomAttributes = "";

		// nu_necTi
		$this->nu_necTi->LinkCustomAttributes = "";
		$this->nu_necTi->HrefValue = "";
		$this->nu_necTi->TooltipValue = "";

		// nu_periodoPei
		$this->nu_periodoPei->LinkCustomAttributes = "";
		$this->nu_periodoPei->HrefValue = "";
		$this->nu_periodoPei->TooltipValue = "";

		// nu_periodoPdti
		$this->nu_periodoPdti->LinkCustomAttributes = "";
		$this->nu_periodoPdti->HrefValue = "";
		$this->nu_periodoPdti->TooltipValue = "";

		// nu_tpNecTi
		$this->nu_tpNecTi->LinkCustomAttributes = "";
		$this->nu_tpNecTi->HrefValue = "";
		$this->nu_tpNecTi->TooltipValue = "";

		// ic_tpNec
		$this->ic_tpNec->LinkCustomAttributes = "";
		$this->ic_tpNec->HrefValue = "";
		$this->ic_tpNec->TooltipValue = "";

		// nu_metaneg
		$this->nu_metaneg->LinkCustomAttributes = "";
		$this->nu_metaneg->HrefValue = "";
		$this->nu_metaneg->TooltipValue = "";

		// nu_origem
		$this->nu_origem->LinkCustomAttributes = "";
		$this->nu_origem->HrefValue = "";
		$this->nu_origem->TooltipValue = "";

		// nu_area
		$this->nu_area->LinkCustomAttributes = "";
		$this->nu_area->HrefValue = "";
		$this->nu_area->TooltipValue = "";

		// ic_gravidade
		$this->ic_gravidade->LinkCustomAttributes = "";
		$this->ic_gravidade->HrefValue = "";
		$this->ic_gravidade->TooltipValue = "";

		// ic_urgencia
		$this->ic_urgencia->LinkCustomAttributes = "";
		$this->ic_urgencia->HrefValue = "";
		$this->ic_urgencia->TooltipValue = "";

		// ic_tendencia
		$this->ic_tendencia->LinkCustomAttributes = "";
		$this->ic_tendencia->HrefValue = "";
		$this->ic_tendencia->TooltipValue = "";

		// ic_prioridade
		$this->ic_prioridade->LinkCustomAttributes = "";
		$this->ic_prioridade->HrefValue = "";
		$this->ic_prioridade->TooltipValue = "";

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
				if ($this->nu_necTi->Exportable) $Doc->ExportCaption($this->nu_necTi);
				if ($this->nu_periodoPei->Exportable) $Doc->ExportCaption($this->nu_periodoPei);
				if ($this->nu_periodoPdti->Exportable) $Doc->ExportCaption($this->nu_periodoPdti);
				if ($this->nu_tpNecTi->Exportable) $Doc->ExportCaption($this->nu_tpNecTi);
				if ($this->ic_tpNec->Exportable) $Doc->ExportCaption($this->ic_tpNec);
				if ($this->nu_metaneg->Exportable) $Doc->ExportCaption($this->nu_metaneg);
				if ($this->nu_origem->Exportable) $Doc->ExportCaption($this->nu_origem);
				if ($this->nu_area->Exportable) $Doc->ExportCaption($this->nu_area);
				if ($this->ic_gravidade->Exportable) $Doc->ExportCaption($this->ic_gravidade);
				if ($this->ic_urgencia->Exportable) $Doc->ExportCaption($this->ic_urgencia);
				if ($this->ic_tendencia->Exportable) $Doc->ExportCaption($this->ic_tendencia);
				if ($this->ic_prioridade->Exportable) $Doc->ExportCaption($this->ic_prioridade);
			} else {
				if ($this->nu_necTi->Exportable) $Doc->ExportCaption($this->nu_necTi);
				if ($this->nu_periodoPei->Exportable) $Doc->ExportCaption($this->nu_periodoPei);
				if ($this->nu_periodoPdti->Exportable) $Doc->ExportCaption($this->nu_periodoPdti);
				if ($this->nu_tpNecTi->Exportable) $Doc->ExportCaption($this->nu_tpNecTi);
				if ($this->ic_tpNec->Exportable) $Doc->ExportCaption($this->ic_tpNec);
				if ($this->nu_metaneg->Exportable) $Doc->ExportCaption($this->nu_metaneg);
				if ($this->nu_origem->Exportable) $Doc->ExportCaption($this->nu_origem);
				if ($this->nu_area->Exportable) $Doc->ExportCaption($this->nu_area);
				if ($this->ic_gravidade->Exportable) $Doc->ExportCaption($this->ic_gravidade);
				if ($this->ic_urgencia->Exportable) $Doc->ExportCaption($this->ic_urgencia);
				if ($this->ic_tendencia->Exportable) $Doc->ExportCaption($this->ic_tendencia);
				if ($this->ic_prioridade->Exportable) $Doc->ExportCaption($this->ic_prioridade);
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
					if ($this->nu_necTi->Exportable) $Doc->ExportField($this->nu_necTi);
					if ($this->nu_periodoPei->Exportable) $Doc->ExportField($this->nu_periodoPei);
					if ($this->nu_periodoPdti->Exportable) $Doc->ExportField($this->nu_periodoPdti);
					if ($this->nu_tpNecTi->Exportable) $Doc->ExportField($this->nu_tpNecTi);
					if ($this->ic_tpNec->Exportable) $Doc->ExportField($this->ic_tpNec);
					if ($this->nu_metaneg->Exportable) $Doc->ExportField($this->nu_metaneg);
					if ($this->nu_origem->Exportable) $Doc->ExportField($this->nu_origem);
					if ($this->nu_area->Exportable) $Doc->ExportField($this->nu_area);
					if ($this->ic_gravidade->Exportable) $Doc->ExportField($this->ic_gravidade);
					if ($this->ic_urgencia->Exportable) $Doc->ExportField($this->ic_urgencia);
					if ($this->ic_tendencia->Exportable) $Doc->ExportField($this->ic_tendencia);
					if ($this->ic_prioridade->Exportable) $Doc->ExportField($this->ic_prioridade);
				} else {
					if ($this->nu_necTi->Exportable) $Doc->ExportField($this->nu_necTi);
					if ($this->nu_periodoPei->Exportable) $Doc->ExportField($this->nu_periodoPei);
					if ($this->nu_periodoPdti->Exportable) $Doc->ExportField($this->nu_periodoPdti);
					if ($this->nu_tpNecTi->Exportable) $Doc->ExportField($this->nu_tpNecTi);
					if ($this->ic_tpNec->Exportable) $Doc->ExportField($this->ic_tpNec);
					if ($this->nu_metaneg->Exportable) $Doc->ExportField($this->nu_metaneg);
					if ($this->nu_origem->Exportable) $Doc->ExportField($this->nu_origem);
					if ($this->nu_area->Exportable) $Doc->ExportField($this->nu_area);
					if ($this->ic_gravidade->Exportable) $Doc->ExportField($this->ic_gravidade);
					if ($this->ic_urgencia->Exportable) $Doc->ExportField($this->ic_urgencia);
					if ($this->ic_tendencia->Exportable) $Doc->ExportField($this->ic_tendencia);
					if ($this->ic_prioridade->Exportable) $Doc->ExportField($this->ic_prioridade);
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
