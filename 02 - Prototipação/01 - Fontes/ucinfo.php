<?php

// Global variable for table object
$uc = NULL;

//
// Table class for uc
//
class cuc extends cTable {
	var $nu_uc;
	var $nu_sistema;
	var $nu_modulo;
	var $co_alternativo;
	var $no_uc;
	var $ds_uc;
	var $nu_stUc;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'uc';
		$this->TableName = 'uc';
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

		// nu_uc
		$this->nu_uc = new cField('uc', 'uc', 'x_nu_uc', 'nu_uc', '[nu_uc]', 'CAST([nu_uc] AS NVARCHAR)', 3, -1, FALSE, '[nu_uc]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_uc->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_uc'] = &$this->nu_uc;

		// nu_sistema
		$this->nu_sistema = new cField('uc', 'uc', 'x_nu_sistema', 'nu_sistema', '[nu_sistema]', 'CAST([nu_sistema] AS NVARCHAR)', 3, -1, FALSE, '[nu_sistema]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_sistema->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_sistema'] = &$this->nu_sistema;

		// nu_modulo
		$this->nu_modulo = new cField('uc', 'uc', 'x_nu_modulo', 'nu_modulo', '[nu_modulo]', 'CAST([nu_modulo] AS NVARCHAR)', 3, -1, FALSE, '[nu_modulo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_modulo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_modulo'] = &$this->nu_modulo;

		// co_alternativo
		$this->co_alternativo = new cField('uc', 'uc', 'x_co_alternativo', 'co_alternativo', '[co_alternativo]', '[co_alternativo]', 200, -1, FALSE, '[co_alternativo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_alternativo'] = &$this->co_alternativo;

		// no_uc
		$this->no_uc = new cField('uc', 'uc', 'x_no_uc', 'no_uc', '[no_uc]', '[no_uc]', 200, -1, FALSE, '[no_uc]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_uc'] = &$this->no_uc;

		// ds_uc
		$this->ds_uc = new cField('uc', 'uc', 'x_ds_uc', 'ds_uc', '[ds_uc]', '[ds_uc]', 201, -1, FALSE, '[ds_uc]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_uc'] = &$this->ds_uc;

		// nu_stUc
		$this->nu_stUc = new cField('uc', 'uc', 'x_nu_stUc', 'nu_stUc', '[nu_stUc]', 'CAST([nu_stUc] AS NVARCHAR)', 3, -1, FALSE, '[nu_stUc]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_stUc->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_stUc'] = &$this->nu_stUc;
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
		if ($this->getCurrentMasterTable() == "sistema") {
			if ($this->nu_sistema->getSessionValue() <> "")
				$sMasterFilter .= "[nu_sistema]=" . ew_QuotedValue($this->nu_sistema->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "sistema") {
			if ($this->nu_sistema->getSessionValue() <> "")
				$sDetailFilter .= "[nu_sistema]=" . ew_QuotedValue($this->nu_sistema->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_sistema() {
		return "[nu_sistema]=@nu_sistema@";
	}

	// Detail filter
	function SqlDetailFilter_sistema() {
		return "[nu_sistema]=@nu_sistema@";
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
		if ($this->getCurrentDetailTable() == "uc_ator") {
			$sDetailUrl = $GLOBALS["uc_ator"]->GetListUrl() . "?showmaster=" . $this->TableVar;
			$sDetailUrl .= "&nu_uc=" . $this->nu_uc->CurrentValue;
		}
		if ($this->getCurrentDetailTable() == "uc_mensagem") {
			$sDetailUrl = $GLOBALS["uc_mensagem"]->GetListUrl() . "?showmaster=" . $this->TableVar;
			$sDetailUrl .= "&nu_uc=" . $this->nu_uc->CurrentValue;
		}
		if ($this->getCurrentDetailTable() == "uc_regranegocio") {
			$sDetailUrl = $GLOBALS["uc_regranegocio"]->GetListUrl() . "?showmaster=" . $this->TableVar;
			$sDetailUrl .= "&nu_uc=" . $this->nu_uc->CurrentValue;
		}
		if ($sDetailUrl == "") {
			$sDetailUrl = "uclist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	function SqlFrom() { // From
		return "[dbo].[uc]";
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
		return "[nu_sistema] ASC,[nu_modulo] ASC,[co_alternativo] ASC,[no_uc] ASC";
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
	var $UpdateTable = "[dbo].[uc]";

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
			if (array_key_exists('nu_uc', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_uc') . '=' . ew_QuotedValue($rs['nu_uc'], $this->nu_uc->FldDataType));
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
		return "[nu_uc] = @nu_uc@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nu_uc->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_uc@", ew_AdjustSql($this->nu_uc->CurrentValue), $sKeyFilter); // Replace key value
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
			return "uclist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "uclist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("ucview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("ucview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "ucadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("ucedit.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("ucedit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("ucadd.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("ucadd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("ucdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nu_uc->CurrentValue)) {
			$sUrl .= "nu_uc=" . urlencode($this->nu_uc->CurrentValue);
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
			$arKeys[] = @$_GET["nu_uc"]; // nu_uc

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
			$this->nu_uc->CurrentValue = $key;
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
		$this->nu_uc->setDbValue($rs->fields('nu_uc'));
		$this->nu_sistema->setDbValue($rs->fields('nu_sistema'));
		$this->nu_modulo->setDbValue($rs->fields('nu_modulo'));
		$this->co_alternativo->setDbValue($rs->fields('co_alternativo'));
		$this->no_uc->setDbValue($rs->fields('no_uc'));
		$this->ds_uc->setDbValue($rs->fields('ds_uc'));
		$this->nu_stUc->setDbValue($rs->fields('nu_stUc'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_uc

		$this->nu_uc->CellCssStyle = "white-space: nowrap;";

		// nu_sistema
		// nu_modulo
		// co_alternativo
		// no_uc
		// ds_uc
		// nu_stUc
		// nu_uc

		$this->nu_uc->ViewValue = $this->nu_uc->CurrentValue;
		$this->nu_uc->ViewCustomAttributes = "";

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

		// nu_modulo
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
		$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
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
		$this->nu_modulo->ViewCustomAttributes = "";

		// co_alternativo
		$this->co_alternativo->ViewValue = $this->co_alternativo->CurrentValue;
		$this->co_alternativo->ViewCustomAttributes = "";

		// no_uc
		$this->no_uc->ViewValue = $this->no_uc->CurrentValue;
		$this->no_uc->ViewCustomAttributes = "";

		// ds_uc
		$this->ds_uc->ViewValue = $this->ds_uc->CurrentValue;
		$this->ds_uc->ViewCustomAttributes = "";

		// nu_stUc
		if (strval($this->nu_stUc->CurrentValue) <> "") {
			$sFilterWrk = "[nu_stUc]" . ew_SearchString("=", $this->nu_stUc->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_stUc], [no_stUc] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[stuc]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_stUc, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_stUc->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_stUc->ViewValue = $this->nu_stUc->CurrentValue;
			}
		} else {
			$this->nu_stUc->ViewValue = NULL;
		}
		$this->nu_stUc->ViewCustomAttributes = "";

		// nu_uc
		$this->nu_uc->LinkCustomAttributes = "";
		$this->nu_uc->HrefValue = "";
		$this->nu_uc->TooltipValue = "";

		// nu_sistema
		$this->nu_sistema->LinkCustomAttributes = "";
		$this->nu_sistema->HrefValue = "";
		$this->nu_sistema->TooltipValue = "";

		// nu_modulo
		$this->nu_modulo->LinkCustomAttributes = "";
		$this->nu_modulo->HrefValue = "";
		$this->nu_modulo->TooltipValue = "";

		// co_alternativo
		$this->co_alternativo->LinkCustomAttributes = "";
		$this->co_alternativo->HrefValue = "";
		$this->co_alternativo->TooltipValue = "";

		// no_uc
		$this->no_uc->LinkCustomAttributes = "";
		$this->no_uc->HrefValue = "";
		$this->no_uc->TooltipValue = "";

		// ds_uc
		$this->ds_uc->LinkCustomAttributes = "";
		$this->ds_uc->HrefValue = "";
		$this->ds_uc->TooltipValue = "";

		// nu_stUc
		$this->nu_stUc->LinkCustomAttributes = "";
		$this->nu_stUc->HrefValue = "";
		$this->nu_stUc->TooltipValue = "";

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
				if ($this->nu_sistema->Exportable) $Doc->ExportCaption($this->nu_sistema);
				if ($this->nu_modulo->Exportable) $Doc->ExportCaption($this->nu_modulo);
				if ($this->co_alternativo->Exportable) $Doc->ExportCaption($this->co_alternativo);
				if ($this->no_uc->Exportable) $Doc->ExportCaption($this->no_uc);
				if ($this->ds_uc->Exportable) $Doc->ExportCaption($this->ds_uc);
				if ($this->nu_stUc->Exportable) $Doc->ExportCaption($this->nu_stUc);
			} else {
				if ($this->nu_sistema->Exportable) $Doc->ExportCaption($this->nu_sistema);
				if ($this->nu_modulo->Exportable) $Doc->ExportCaption($this->nu_modulo);
				if ($this->co_alternativo->Exportable) $Doc->ExportCaption($this->co_alternativo);
				if ($this->no_uc->Exportable) $Doc->ExportCaption($this->no_uc);
				if ($this->nu_stUc->Exportable) $Doc->ExportCaption($this->nu_stUc);
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
					if ($this->nu_sistema->Exportable) $Doc->ExportField($this->nu_sistema);
					if ($this->nu_modulo->Exportable) $Doc->ExportField($this->nu_modulo);
					if ($this->co_alternativo->Exportable) $Doc->ExportField($this->co_alternativo);
					if ($this->no_uc->Exportable) $Doc->ExportField($this->no_uc);
					if ($this->ds_uc->Exportable) $Doc->ExportField($this->ds_uc);
					if ($this->nu_stUc->Exportable) $Doc->ExportField($this->nu_stUc);
				} else {
					if ($this->nu_sistema->Exportable) $Doc->ExportField($this->nu_sistema);
					if ($this->nu_modulo->Exportable) $Doc->ExportField($this->nu_modulo);
					if ($this->co_alternativo->Exportable) $Doc->ExportField($this->co_alternativo);
					if ($this->no_uc->Exportable) $Doc->ExportField($this->no_uc);
					if ($this->nu_stUc->Exportable) $Doc->ExportField($this->nu_stUc);
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
