<?php

// Global variable for table object
$vwrdmd_tarAgrTpTarSitMMAA = NULL;

//
// Table class for vwrdmd_tarAgrTpTarSitMMAA
//
class cvwrdmd_tarAgrTpTarSitMMAA extends cTable {
	var $tracker_id;
	var $status_id;
	var $tmonth;
	var $tyear;
	var $qt_issues;
	var $qt_hours;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'vwrdmd_tarAgrTpTarSitMMAA';
		$this->TableName = 'vwrdmd_tarAgrTpTarSitMMAA';
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

		// tracker_id
		$this->tracker_id = new cField('vwrdmd_tarAgrTpTarSitMMAA', 'vwrdmd_tarAgrTpTarSitMMAA', 'x_tracker_id', 'tracker_id', '[tracker_id]', 'CAST([tracker_id] AS NVARCHAR)', 3, -1, FALSE, '[tracker_id]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->tracker_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['tracker_id'] = &$this->tracker_id;

		// status_id
		$this->status_id = new cField('vwrdmd_tarAgrTpTarSitMMAA', 'vwrdmd_tarAgrTpTarSitMMAA', 'x_status_id', 'status_id', '[status_id]', 'CAST([status_id] AS NVARCHAR)', 3, -1, FALSE, '[status_id]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->status_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['status_id'] = &$this->status_id;

		// tmonth
		$this->tmonth = new cField('vwrdmd_tarAgrTpTarSitMMAA', 'vwrdmd_tarAgrTpTarSitMMAA', 'x_tmonth', 'tmonth', '[tmonth]', 'CAST([tmonth] AS NVARCHAR)', 3, -1, FALSE, '[tmonth]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->tmonth->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['tmonth'] = &$this->tmonth;

		// tyear
		$this->tyear = new cField('vwrdmd_tarAgrTpTarSitMMAA', 'vwrdmd_tarAgrTpTarSitMMAA', 'x_tyear', 'tyear', '[tyear]', 'CAST([tyear] AS NVARCHAR)', 3, -1, FALSE, '[tyear]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->tyear->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['tyear'] = &$this->tyear;

		// qt_issues
		$this->qt_issues = new cField('vwrdmd_tarAgrTpTarSitMMAA', 'vwrdmd_tarAgrTpTarSitMMAA', 'x_qt_issues', 'qt_issues', '[qt_issues]', 'CAST([qt_issues] AS NVARCHAR)', 131, -1, FALSE, '[qt_issues]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->qt_issues->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['qt_issues'] = &$this->qt_issues;

		// qt_hours
		$this->qt_hours = new cField('vwrdmd_tarAgrTpTarSitMMAA', 'vwrdmd_tarAgrTpTarSitMMAA', 'x_qt_hours', 'qt_hours', '[qt_hours]', 'CAST([qt_hours] AS NVARCHAR)', 131, -1, FALSE, '[qt_hours]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->qt_hours->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['qt_hours'] = &$this->qt_hours;
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
		return "[db_owner].[vwrdmd_tarAgrTpTarSitMMAA]";
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
		return "[tyear] ASC,[tmonth] ASC,[tracker_id] ASC";
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
	var $UpdateTable = "[db_owner].[vwrdmd_tarAgrTpTarSitMMAA]";

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
			return "vwrdmd_taragrtptarsitmmaalist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "vwrdmd_taragrtptarsitmmaalist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("vwrdmd_taragrtptarsitmmaaview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("vwrdmd_taragrtptarsitmmaaview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "vwrdmd_taragrtptarsitmmaaadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("vwrdmd_taragrtptarsitmmaaedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("vwrdmd_taragrtptarsitmmaaadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("vwrdmd_taragrtptarsitmmaadelete.php", $this->UrlParm());
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
		$this->tracker_id->setDbValue($rs->fields('tracker_id'));
		$this->status_id->setDbValue($rs->fields('status_id'));
		$this->tmonth->setDbValue($rs->fields('tmonth'));
		$this->tyear->setDbValue($rs->fields('tyear'));
		$this->qt_issues->setDbValue($rs->fields('qt_issues'));
		$this->qt_hours->setDbValue($rs->fields('qt_hours'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// tracker_id
		// status_id
		// tmonth
		// tyear
		// qt_issues
		// qt_hours
		// tracker_id

		if (strval($this->tracker_id->CurrentValue) <> "") {
			$sFilterWrk = "[id]" . ew_SearchString("=", $this->tracker_id->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tbrdm_trackers]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->tracker_id, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->tracker_id->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->tracker_id->ViewValue = $this->tracker_id->CurrentValue;
			}
		} else {
			$this->tracker_id->ViewValue = NULL;
		}
		$this->tracker_id->ViewCustomAttributes = "";

		// status_id
		if (strval($this->status_id->CurrentValue) <> "") {
			$sFilterWrk = "[id]" . ew_SearchString("=", $this->status_id->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tbrdm_issue_statuses]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->status_id, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->status_id->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->status_id->ViewValue = $this->status_id->CurrentValue;
			}
		} else {
			$this->status_id->ViewValue = NULL;
		}
		$this->status_id->ViewCustomAttributes = "";

		// tmonth
		if (strval($this->tmonth->CurrentValue) <> "") {
			switch ($this->tmonth->CurrentValue) {
				case $this->tmonth->FldTagValue(1):
					$this->tmonth->ViewValue = $this->tmonth->FldTagCaption(1) <> "" ? $this->tmonth->FldTagCaption(1) : $this->tmonth->CurrentValue;
					break;
				case $this->tmonth->FldTagValue(2):
					$this->tmonth->ViewValue = $this->tmonth->FldTagCaption(2) <> "" ? $this->tmonth->FldTagCaption(2) : $this->tmonth->CurrentValue;
					break;
				case $this->tmonth->FldTagValue(3):
					$this->tmonth->ViewValue = $this->tmonth->FldTagCaption(3) <> "" ? $this->tmonth->FldTagCaption(3) : $this->tmonth->CurrentValue;
					break;
				case $this->tmonth->FldTagValue(4):
					$this->tmonth->ViewValue = $this->tmonth->FldTagCaption(4) <> "" ? $this->tmonth->FldTagCaption(4) : $this->tmonth->CurrentValue;
					break;
				case $this->tmonth->FldTagValue(5):
					$this->tmonth->ViewValue = $this->tmonth->FldTagCaption(5) <> "" ? $this->tmonth->FldTagCaption(5) : $this->tmonth->CurrentValue;
					break;
				case $this->tmonth->FldTagValue(6):
					$this->tmonth->ViewValue = $this->tmonth->FldTagCaption(6) <> "" ? $this->tmonth->FldTagCaption(6) : $this->tmonth->CurrentValue;
					break;
				case $this->tmonth->FldTagValue(7):
					$this->tmonth->ViewValue = $this->tmonth->FldTagCaption(7) <> "" ? $this->tmonth->FldTagCaption(7) : $this->tmonth->CurrentValue;
					break;
				case $this->tmonth->FldTagValue(8):
					$this->tmonth->ViewValue = $this->tmonth->FldTagCaption(8) <> "" ? $this->tmonth->FldTagCaption(8) : $this->tmonth->CurrentValue;
					break;
				case $this->tmonth->FldTagValue(9):
					$this->tmonth->ViewValue = $this->tmonth->FldTagCaption(9) <> "" ? $this->tmonth->FldTagCaption(9) : $this->tmonth->CurrentValue;
					break;
				case $this->tmonth->FldTagValue(10):
					$this->tmonth->ViewValue = $this->tmonth->FldTagCaption(10) <> "" ? $this->tmonth->FldTagCaption(10) : $this->tmonth->CurrentValue;
					break;
				case $this->tmonth->FldTagValue(11):
					$this->tmonth->ViewValue = $this->tmonth->FldTagCaption(11) <> "" ? $this->tmonth->FldTagCaption(11) : $this->tmonth->CurrentValue;
					break;
				case $this->tmonth->FldTagValue(12):
					$this->tmonth->ViewValue = $this->tmonth->FldTagCaption(12) <> "" ? $this->tmonth->FldTagCaption(12) : $this->tmonth->CurrentValue;
					break;
				default:
					$this->tmonth->ViewValue = $this->tmonth->CurrentValue;
			}
		} else {
			$this->tmonth->ViewValue = NULL;
		}
		$this->tmonth->ViewCustomAttributes = "";

		// tyear
		if (strval($this->tyear->CurrentValue) <> "") {
			switch ($this->tyear->CurrentValue) {
				case $this->tyear->FldTagValue(1):
					$this->tyear->ViewValue = $this->tyear->FldTagCaption(1) <> "" ? $this->tyear->FldTagCaption(1) : $this->tyear->CurrentValue;
					break;
				case $this->tyear->FldTagValue(2):
					$this->tyear->ViewValue = $this->tyear->FldTagCaption(2) <> "" ? $this->tyear->FldTagCaption(2) : $this->tyear->CurrentValue;
					break;
				case $this->tyear->FldTagValue(3):
					$this->tyear->ViewValue = $this->tyear->FldTagCaption(3) <> "" ? $this->tyear->FldTagCaption(3) : $this->tyear->CurrentValue;
					break;
				case $this->tyear->FldTagValue(4):
					$this->tyear->ViewValue = $this->tyear->FldTagCaption(4) <> "" ? $this->tyear->FldTagCaption(4) : $this->tyear->CurrentValue;
					break;
				case $this->tyear->FldTagValue(5):
					$this->tyear->ViewValue = $this->tyear->FldTagCaption(5) <> "" ? $this->tyear->FldTagCaption(5) : $this->tyear->CurrentValue;
					break;
				case $this->tyear->FldTagValue(6):
					$this->tyear->ViewValue = $this->tyear->FldTagCaption(6) <> "" ? $this->tyear->FldTagCaption(6) : $this->tyear->CurrentValue;
					break;
				case $this->tyear->FldTagValue(7):
					$this->tyear->ViewValue = $this->tyear->FldTagCaption(7) <> "" ? $this->tyear->FldTagCaption(7) : $this->tyear->CurrentValue;
					break;
				case $this->tyear->FldTagValue(8):
					$this->tyear->ViewValue = $this->tyear->FldTagCaption(8) <> "" ? $this->tyear->FldTagCaption(8) : $this->tyear->CurrentValue;
					break;
				case $this->tyear->FldTagValue(9):
					$this->tyear->ViewValue = $this->tyear->FldTagCaption(9) <> "" ? $this->tyear->FldTagCaption(9) : $this->tyear->CurrentValue;
					break;
				case $this->tyear->FldTagValue(10):
					$this->tyear->ViewValue = $this->tyear->FldTagCaption(10) <> "" ? $this->tyear->FldTagCaption(10) : $this->tyear->CurrentValue;
					break;
				default:
					$this->tyear->ViewValue = $this->tyear->CurrentValue;
			}
		} else {
			$this->tyear->ViewValue = NULL;
		}
		$this->tyear->ViewCustomAttributes = "";

		// qt_issues
		$this->qt_issues->ViewValue = $this->qt_issues->CurrentValue;
		$this->qt_issues->ViewCustomAttributes = "";

		// qt_hours
		$this->qt_hours->ViewValue = $this->qt_hours->CurrentValue;
		$this->qt_hours->ViewCustomAttributes = "";

		// tracker_id
		$this->tracker_id->LinkCustomAttributes = "";
		$this->tracker_id->HrefValue = "";
		$this->tracker_id->TooltipValue = "";

		// status_id
		$this->status_id->LinkCustomAttributes = "";
		$this->status_id->HrefValue = "";
		$this->status_id->TooltipValue = "";

		// tmonth
		$this->tmonth->LinkCustomAttributes = "";
		$this->tmonth->HrefValue = "";
		$this->tmonth->TooltipValue = "";

		// tyear
		$this->tyear->LinkCustomAttributes = "";
		$this->tyear->HrefValue = "";
		$this->tyear->TooltipValue = "";

		// qt_issues
		$this->qt_issues->LinkCustomAttributes = "";
		$this->qt_issues->HrefValue = "";
		$this->qt_issues->TooltipValue = "";

		// qt_hours
		$this->qt_hours->LinkCustomAttributes = "";
		$this->qt_hours->HrefValue = "";
		$this->qt_hours->TooltipValue = "";

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
				if ($this->tracker_id->Exportable) $Doc->ExportCaption($this->tracker_id);
				if ($this->status_id->Exportable) $Doc->ExportCaption($this->status_id);
				if ($this->tmonth->Exportable) $Doc->ExportCaption($this->tmonth);
				if ($this->tyear->Exportable) $Doc->ExportCaption($this->tyear);
				if ($this->qt_issues->Exportable) $Doc->ExportCaption($this->qt_issues);
				if ($this->qt_hours->Exportable) $Doc->ExportCaption($this->qt_hours);
			} else {
				if ($this->tracker_id->Exportable) $Doc->ExportCaption($this->tracker_id);
				if ($this->status_id->Exportable) $Doc->ExportCaption($this->status_id);
				if ($this->tmonth->Exportable) $Doc->ExportCaption($this->tmonth);
				if ($this->tyear->Exportable) $Doc->ExportCaption($this->tyear);
				if ($this->qt_issues->Exportable) $Doc->ExportCaption($this->qt_issues);
				if ($this->qt_hours->Exportable) $Doc->ExportCaption($this->qt_hours);
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
					if ($this->tracker_id->Exportable) $Doc->ExportField($this->tracker_id);
					if ($this->status_id->Exportable) $Doc->ExportField($this->status_id);
					if ($this->tmonth->Exportable) $Doc->ExportField($this->tmonth);
					if ($this->tyear->Exportable) $Doc->ExportField($this->tyear);
					if ($this->qt_issues->Exportable) $Doc->ExportField($this->qt_issues);
					if ($this->qt_hours->Exportable) $Doc->ExportField($this->qt_hours);
				} else {
					if ($this->tracker_id->Exportable) $Doc->ExportField($this->tracker_id);
					if ($this->status_id->Exportable) $Doc->ExportField($this->status_id);
					if ($this->tmonth->Exportable) $Doc->ExportField($this->tmonth);
					if ($this->tyear->Exportable) $Doc->ExportField($this->tyear);
					if ($this->qt_issues->Exportable) $Doc->ExportField($this->qt_issues);
					if ($this->qt_hours->Exportable) $Doc->ExportField($this->qt_hours);
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
