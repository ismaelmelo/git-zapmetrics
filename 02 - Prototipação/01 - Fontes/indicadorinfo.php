<?php

// Global variable for table object
$indicador = NULL;

//
// Table class for indicador
//
class cindicador extends cTable {
	var $nu_indicador;
	var $no_indicador;
	var $ds_indicador;
	var $ic_tpIndicador;
	var $nu_processoCobit5;
	var $ic_ativo;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'indicador';
		$this->TableName = 'indicador';
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

		// nu_indicador
		$this->nu_indicador = new cField('indicador', 'indicador', 'x_nu_indicador', 'nu_indicador', '[nu_indicador]', 'CAST([nu_indicador] AS NVARCHAR)', 3, -1, FALSE, '[nu_indicador]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_indicador->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_indicador'] = &$this->nu_indicador;

		// no_indicador
		$this->no_indicador = new cField('indicador', 'indicador', 'x_no_indicador', 'no_indicador', '[no_indicador]', '[no_indicador]', 200, -1, FALSE, '[no_indicador]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_indicador'] = &$this->no_indicador;

		// ds_indicador
		$this->ds_indicador = new cField('indicador', 'indicador', 'x_ds_indicador', 'ds_indicador', '[ds_indicador]', '[ds_indicador]', 201, -1, FALSE, '[ds_indicador]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_indicador'] = &$this->ds_indicador;

		// ic_tpIndicador
		$this->ic_tpIndicador = new cField('indicador', 'indicador', 'x_ic_tpIndicador', 'ic_tpIndicador', '[ic_tpIndicador]', '[ic_tpIndicador]', 129, -1, FALSE, '[ic_tpIndicador]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_tpIndicador'] = &$this->ic_tpIndicador;

		// nu_processoCobit5
		$this->nu_processoCobit5 = new cField('indicador', 'indicador', 'x_nu_processoCobit5', 'nu_processoCobit5', '[nu_processoCobit5]', 'CAST([nu_processoCobit5] AS NVARCHAR)', 3, -1, FALSE, '[EV__nu_processoCobit5]', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->nu_processoCobit5->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_processoCobit5'] = &$this->nu_processoCobit5;

		// ic_ativo
		$this->ic_ativo = new cField('indicador', 'indicador', 'x_ic_ativo', 'ic_ativo', '[ic_ativo]', '[ic_ativo]', 129, -1, FALSE, '[ic_ativo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
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
		if ($this->getCurrentMasterTable() == "indicadorversao") {
			if ($this->nu_indicador->getSessionValue() <> "")
				$sMasterFilter .= "[nu_indicador]=" . ew_QuotedValue($this->nu_indicador->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "indicadorversao") {
			if ($this->nu_indicador->getSessionValue() <> "")
				$sDetailFilter .= "[nu_indicador]=" . ew_QuotedValue($this->nu_indicador->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_indicadorversao() {
		return "[nu_indicador]=@nu_indicador@";
	}

	// Detail filter
	function SqlDetailFilter_indicadorversao() {
		return "[nu_indicador]=@nu_indicador@";
	}

	// Table level SQL
	function SqlFrom() { // From
		return "[dbo].[indicador]";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlSelectList() { // Select for List page
		return "SELECT * FROM (" .
			"SELECT *, (SELECT TOP 1 [co_alternativo] + '" . ew_ValueSeparator(1, $this->nu_processoCobit5) . "' + [no_processo] FROM [processocobit5] [EW_TMP_LOOKUPTABLE] WHERE [EW_TMP_LOOKUPTABLE].[nu_processo] = [indicador].[nu_processoCobit5]) AS [EV__nu_processoCobit5] FROM [dbo].[indicador]" .
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
		if ($this->nu_processoCobit5->AdvancedSearch->SearchValue <> "" ||
			$this->nu_processoCobit5->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->nu_processoCobit5->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->nu_processoCobit5->FldVirtualExpression . " ") !== FALSE)
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
	var $UpdateTable = "[dbo].[indicador]";

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
			if (array_key_exists('nu_indicador', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_indicador') . '=' . ew_QuotedValue($rs['nu_indicador'], $this->nu_indicador->FldDataType));
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
		return "[nu_indicador] = @nu_indicador@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nu_indicador->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_indicador@", ew_AdjustSql($this->nu_indicador->CurrentValue), $sKeyFilter); // Replace key value
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
			return "indicadorlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "indicadorlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("indicadorview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("indicadorview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "indicadoradd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("indicadoredit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("indicadoradd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("indicadordelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nu_indicador->CurrentValue)) {
			$sUrl .= "nu_indicador=" . urlencode($this->nu_indicador->CurrentValue);
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
			$arKeys[] = @$_GET["nu_indicador"]; // nu_indicador

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
			$this->nu_indicador->CurrentValue = $key;
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
		$this->nu_indicador->setDbValue($rs->fields('nu_indicador'));
		$this->no_indicador->setDbValue($rs->fields('no_indicador'));
		$this->ds_indicador->setDbValue($rs->fields('ds_indicador'));
		$this->ic_tpIndicador->setDbValue($rs->fields('ic_tpIndicador'));
		$this->nu_processoCobit5->setDbValue($rs->fields('nu_processoCobit5'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_indicador

		$this->nu_indicador->CellCssStyle = "white-space: nowrap;";

		// no_indicador
		// ds_indicador
		// ic_tpIndicador
		// nu_processoCobit5
		// ic_ativo
		// nu_indicador

		$this->nu_indicador->ViewValue = $this->nu_indicador->CurrentValue;
		$this->nu_indicador->ViewCustomAttributes = "";

		// no_indicador
		$this->no_indicador->ViewValue = $this->no_indicador->CurrentValue;
		$this->no_indicador->ViewCustomAttributes = "";

		// ds_indicador
		$this->ds_indicador->ViewValue = $this->ds_indicador->CurrentValue;
		$this->ds_indicador->ViewCustomAttributes = "";

		// ic_tpIndicador
		if (strval($this->ic_tpIndicador->CurrentValue) <> "") {
			switch ($this->ic_tpIndicador->CurrentValue) {
				case $this->ic_tpIndicador->FldTagValue(1):
					$this->ic_tpIndicador->ViewValue = $this->ic_tpIndicador->FldTagCaption(1) <> "" ? $this->ic_tpIndicador->FldTagCaption(1) : $this->ic_tpIndicador->CurrentValue;
					break;
				case $this->ic_tpIndicador->FldTagValue(2):
					$this->ic_tpIndicador->ViewValue = $this->ic_tpIndicador->FldTagCaption(2) <> "" ? $this->ic_tpIndicador->FldTagCaption(2) : $this->ic_tpIndicador->CurrentValue;
					break;
				case $this->ic_tpIndicador->FldTagValue(3):
					$this->ic_tpIndicador->ViewValue = $this->ic_tpIndicador->FldTagCaption(3) <> "" ? $this->ic_tpIndicador->FldTagCaption(3) : $this->ic_tpIndicador->CurrentValue;
					break;
				case $this->ic_tpIndicador->FldTagValue(4):
					$this->ic_tpIndicador->ViewValue = $this->ic_tpIndicador->FldTagCaption(4) <> "" ? $this->ic_tpIndicador->FldTagCaption(4) : $this->ic_tpIndicador->CurrentValue;
					break;
				default:
					$this->ic_tpIndicador->ViewValue = $this->ic_tpIndicador->CurrentValue;
			}
		} else {
			$this->ic_tpIndicador->ViewValue = NULL;
		}
		$this->ic_tpIndicador->ViewCustomAttributes = "";

		// nu_processoCobit5
		if ($this->nu_processoCobit5->VirtualValue <> "") {
			$this->nu_processoCobit5->ViewValue = $this->nu_processoCobit5->VirtualValue;
		} else {
		if (strval($this->nu_processoCobit5->CurrentValue) <> "") {
			$sFilterWrk = "[nu_processo]" . ew_SearchString("=", $this->nu_processoCobit5->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_processo], [co_alternativo] AS [DispFld], [no_processo] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[processocobit5]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_processoCobit5, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [ic_dominio] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_processoCobit5->ViewValue = $rswrk->fields('DispFld');
				$this->nu_processoCobit5->ViewValue .= ew_ValueSeparator(1,$this->nu_processoCobit5) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->nu_processoCobit5->ViewValue = $this->nu_processoCobit5->CurrentValue;
			}
		} else {
			$this->nu_processoCobit5->ViewValue = NULL;
		}
		}
		$this->nu_processoCobit5->ViewCustomAttributes = "";

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

		// nu_indicador
		$this->nu_indicador->LinkCustomAttributes = "";
		$this->nu_indicador->HrefValue = "";
		$this->nu_indicador->TooltipValue = "";

		// no_indicador
		$this->no_indicador->LinkCustomAttributes = "";
		$this->no_indicador->HrefValue = "";
		$this->no_indicador->TooltipValue = "";

		// ds_indicador
		$this->ds_indicador->LinkCustomAttributes = "";
		$this->ds_indicador->HrefValue = "";
		$this->ds_indicador->TooltipValue = "";

		// ic_tpIndicador
		$this->ic_tpIndicador->LinkCustomAttributes = "";
		$this->ic_tpIndicador->HrefValue = "";
		$this->ic_tpIndicador->TooltipValue = "";

		// nu_processoCobit5
		$this->nu_processoCobit5->LinkCustomAttributes = "";
		$this->nu_processoCobit5->HrefValue = "";
		$this->nu_processoCobit5->TooltipValue = "";

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
				if ($this->no_indicador->Exportable) $Doc->ExportCaption($this->no_indicador);
				if ($this->ds_indicador->Exportable) $Doc->ExportCaption($this->ds_indicador);
				if ($this->ic_tpIndicador->Exportable) $Doc->ExportCaption($this->ic_tpIndicador);
				if ($this->nu_processoCobit5->Exportable) $Doc->ExportCaption($this->nu_processoCobit5);
				if ($this->ic_ativo->Exportable) $Doc->ExportCaption($this->ic_ativo);
			} else {
				if ($this->nu_indicador->Exportable) $Doc->ExportCaption($this->nu_indicador);
				if ($this->no_indicador->Exportable) $Doc->ExportCaption($this->no_indicador);
				if ($this->ic_tpIndicador->Exportable) $Doc->ExportCaption($this->ic_tpIndicador);
				if ($this->nu_processoCobit5->Exportable) $Doc->ExportCaption($this->nu_processoCobit5);
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
					if ($this->no_indicador->Exportable) $Doc->ExportField($this->no_indicador);
					if ($this->ds_indicador->Exportable) $Doc->ExportField($this->ds_indicador);
					if ($this->ic_tpIndicador->Exportable) $Doc->ExportField($this->ic_tpIndicador);
					if ($this->nu_processoCobit5->Exportable) $Doc->ExportField($this->nu_processoCobit5);
					if ($this->ic_ativo->Exportable) $Doc->ExportField($this->ic_ativo);
				} else {
					if ($this->nu_indicador->Exportable) $Doc->ExportField($this->nu_indicador);
					if ($this->no_indicador->Exportable) $Doc->ExportField($this->no_indicador);
					if ($this->ic_tpIndicador->Exportable) $Doc->ExportField($this->ic_tpIndicador);
					if ($this->nu_processoCobit5->Exportable) $Doc->ExportField($this->nu_processoCobit5);
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
