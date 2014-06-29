<?php

// Global variable for table object
$vwrdmd_lancamentoHoras = NULL;

//
// Table class for vwrdmd_lancamentoHoras
//
class cvwrdmd_lancamentoHoras extends cTable {
	var $nu_usuario;
	var $spent_on;
	var $nu_semana;
	var $data;
	var $nu_mes;
	var $nu_ano;
	var $qt_horas;
	var $activity_id;
	var $issue_id;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'vwrdmd_lancamentoHoras';
		$this->TableName = 'vwrdmd_lancamentoHoras';
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

		// nu_usuario
		$this->nu_usuario = new cField('vwrdmd_lancamentoHoras', 'vwrdmd_lancamentoHoras', 'x_nu_usuario', 'nu_usuario', '[nu_usuario]', 'CAST([nu_usuario] AS NVARCHAR)', 3, -1, FALSE, '[nu_usuario]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_usuario->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_usuario'] = &$this->nu_usuario;

		// spent_on
		$this->spent_on = new cField('vwrdmd_lancamentoHoras', 'vwrdmd_lancamentoHoras', 'x_spent_on', 'spent_on', '[spent_on]', '(REPLACE(STR(DAY([spent_on]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([spent_on]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([spent_on]),4,0))', 133, 7, FALSE, '[spent_on]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->spent_on->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['spent_on'] = &$this->spent_on;

		// nu_semana
		$this->nu_semana = new cField('vwrdmd_lancamentoHoras', 'vwrdmd_lancamentoHoras', 'x_nu_semana', 'nu_semana', '[nu_semana]', 'CAST([nu_semana] AS NVARCHAR)', 3, -1, FALSE, '[nu_semana]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_semana->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_semana'] = &$this->nu_semana;

		// data
		$this->data = new cField('vwrdmd_lancamentoHoras', 'vwrdmd_lancamentoHoras', 'x_data', 'data', '[data]', '[data]', 129, -1, FALSE, '[data]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['data'] = &$this->data;

		// nu_mes
		$this->nu_mes = new cField('vwrdmd_lancamentoHoras', 'vwrdmd_lancamentoHoras', 'x_nu_mes', 'nu_mes', '[nu_mes]', 'CAST([nu_mes] AS NVARCHAR)', 3, -1, FALSE, '[nu_mes]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_mes->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_mes'] = &$this->nu_mes;

		// nu_ano
		$this->nu_ano = new cField('vwrdmd_lancamentoHoras', 'vwrdmd_lancamentoHoras', 'x_nu_ano', 'nu_ano', '[nu_ano]', 'CAST([nu_ano] AS NVARCHAR)', 3, -1, FALSE, '[nu_ano]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_ano->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_ano'] = &$this->nu_ano;

		// qt_horas
		$this->qt_horas = new cField('vwrdmd_lancamentoHoras', 'vwrdmd_lancamentoHoras', 'x_qt_horas', 'qt_horas', '[qt_horas]', 'CAST([qt_horas] AS NVARCHAR)', 131, -1, FALSE, '[qt_horas]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->qt_horas->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['qt_horas'] = &$this->qt_horas;

		// activity_id
		$this->activity_id = new cField('vwrdmd_lancamentoHoras', 'vwrdmd_lancamentoHoras', 'x_activity_id', 'activity_id', '[activity_id]', 'CAST([activity_id] AS NVARCHAR)', 3, -1, FALSE, '[activity_id]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->activity_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['activity_id'] = &$this->activity_id;

		// issue_id
		$this->issue_id = new cField('vwrdmd_lancamentoHoras', 'vwrdmd_lancamentoHoras', 'x_issue_id', 'issue_id', '[issue_id]', 'CAST([issue_id] AS NVARCHAR)', 3, -1, FALSE, '[issue_id]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->issue_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['issue_id'] = &$this->issue_id;
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
		return "[db_owner].[vwrdmd_lancamentoHoras]";
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
	var $UpdateTable = "[db_owner].[vwrdmd_lancamentoHoras]";

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
			return "vwrdmd_lancamentohoraslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "vwrdmd_lancamentohoraslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("vwrdmd_lancamentohorasview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("vwrdmd_lancamentohorasview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "vwrdmd_lancamentohorasadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("vwrdmd_lancamentohorasedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("vwrdmd_lancamentohorasadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("vwrdmd_lancamentohorasdelete.php", $this->UrlParm());
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
		$this->nu_usuario->setDbValue($rs->fields('nu_usuario'));
		$this->spent_on->setDbValue($rs->fields('spent_on'));
		$this->nu_semana->setDbValue($rs->fields('nu_semana'));
		$this->data->setDbValue($rs->fields('data'));
		$this->nu_mes->setDbValue($rs->fields('nu_mes'));
		$this->nu_ano->setDbValue($rs->fields('nu_ano'));
		$this->qt_horas->setDbValue($rs->fields('qt_horas'));
		$this->activity_id->setDbValue($rs->fields('activity_id'));
		$this->issue_id->setDbValue($rs->fields('issue_id'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_usuario
		// spent_on
		// nu_semana
		// data
		// nu_mes
		// nu_ano
		// qt_horas
		// activity_id
		// issue_id
		// nu_usuario

		if (strval($this->nu_usuario->CurrentValue) <> "") {
			$sFilterWrk = "[id]" . ew_SearchString("=", $this->nu_usuario->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tbrdm_users]";
		$sWhereWrk = "";
		$lookuptblfilter = "[login]<>''";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_usuario, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [name] ASC";
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

		// spent_on
		$this->spent_on->ViewValue = $this->spent_on->CurrentValue;
		$this->spent_on->ViewValue = ew_FormatDateTime($this->spent_on->ViewValue, 7);
		$this->spent_on->ViewCustomAttributes = "";

		// nu_semana
		$this->nu_semana->ViewValue = $this->nu_semana->CurrentValue;
		$this->nu_semana->ViewCustomAttributes = "";

		// data
		$this->data->ViewValue = $this->data->CurrentValue;
		$this->data->ViewCustomAttributes = "";

		// nu_mes
		$this->nu_mes->ViewValue = $this->nu_mes->CurrentValue;
		$this->nu_mes->ViewCustomAttributes = "";

		// nu_ano
		$this->nu_ano->ViewValue = $this->nu_ano->CurrentValue;
		$this->nu_ano->ViewCustomAttributes = "";

		// qt_horas
		$this->qt_horas->ViewValue = $this->qt_horas->CurrentValue;
		$this->qt_horas->ViewCustomAttributes = "";

		// activity_id
		if (strval($this->activity_id->CurrentValue) <> "") {
			$sFilterWrk = "[id]" . ew_SearchString("=", $this->activity_id->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tbrdm_enumerations]";
		$sWhereWrk = "";
		$lookuptblfilter = "[type]='TimeEntryActivity'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->activity_id, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [name] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->activity_id->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->activity_id->ViewValue = $this->activity_id->CurrentValue;
			}
		} else {
			$this->activity_id->ViewValue = NULL;
		}
		$this->activity_id->ViewCustomAttributes = "";

		// issue_id
		if (strval($this->issue_id->CurrentValue) <> "") {
			$sFilterWrk = "[id]" . ew_SearchString("=", $this->issue_id->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [id], [id] AS [DispFld], [subject] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tbrdm_issues]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->issue_id, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->issue_id->ViewValue = $rswrk->fields('DispFld');
				$this->issue_id->ViewValue .= ew_ValueSeparator(1,$this->issue_id) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->issue_id->ViewValue = $this->issue_id->CurrentValue;
			}
		} else {
			$this->issue_id->ViewValue = NULL;
		}
		$this->issue_id->ViewCustomAttributes = "";

		// nu_usuario
		$this->nu_usuario->LinkCustomAttributes = "";
		$this->nu_usuario->HrefValue = "";
		$this->nu_usuario->TooltipValue = "";

		// spent_on
		$this->spent_on->LinkCustomAttributes = "";
		$this->spent_on->HrefValue = "";
		$this->spent_on->TooltipValue = "";

		// nu_semana
		$this->nu_semana->LinkCustomAttributes = "";
		$this->nu_semana->HrefValue = "";
		$this->nu_semana->TooltipValue = "";

		// data
		$this->data->LinkCustomAttributes = "";
		$this->data->HrefValue = "";
		$this->data->TooltipValue = "";

		// nu_mes
		$this->nu_mes->LinkCustomAttributes = "";
		$this->nu_mes->HrefValue = "";
		$this->nu_mes->TooltipValue = "";

		// nu_ano
		$this->nu_ano->LinkCustomAttributes = "";
		$this->nu_ano->HrefValue = "";
		$this->nu_ano->TooltipValue = "";

		// qt_horas
		$this->qt_horas->LinkCustomAttributes = "";
		$this->qt_horas->HrefValue = "";
		$this->qt_horas->TooltipValue = "";

		// activity_id
		$this->activity_id->LinkCustomAttributes = "";
		$this->activity_id->HrefValue = "";
		$this->activity_id->TooltipValue = "";

		// issue_id
		$this->issue_id->LinkCustomAttributes = "";
		$this->issue_id->HrefValue = "";
		$this->issue_id->TooltipValue = "";

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
				if ($this->nu_usuario->Exportable) $Doc->ExportCaption($this->nu_usuario);
				if ($this->spent_on->Exportable) $Doc->ExportCaption($this->spent_on);
				if ($this->nu_semana->Exportable) $Doc->ExportCaption($this->nu_semana);
				if ($this->data->Exportable) $Doc->ExportCaption($this->data);
				if ($this->nu_mes->Exportable) $Doc->ExportCaption($this->nu_mes);
				if ($this->nu_ano->Exportable) $Doc->ExportCaption($this->nu_ano);
				if ($this->qt_horas->Exportable) $Doc->ExportCaption($this->qt_horas);
				if ($this->activity_id->Exportable) $Doc->ExportCaption($this->activity_id);
				if ($this->issue_id->Exportable) $Doc->ExportCaption($this->issue_id);
			} else {
				if ($this->nu_usuario->Exportable) $Doc->ExportCaption($this->nu_usuario);
				if ($this->spent_on->Exportable) $Doc->ExportCaption($this->spent_on);
				if ($this->nu_semana->Exportable) $Doc->ExportCaption($this->nu_semana);
				if ($this->data->Exportable) $Doc->ExportCaption($this->data);
				if ($this->nu_mes->Exportable) $Doc->ExportCaption($this->nu_mes);
				if ($this->nu_ano->Exportable) $Doc->ExportCaption($this->nu_ano);
				if ($this->qt_horas->Exportable) $Doc->ExportCaption($this->qt_horas);
				if ($this->activity_id->Exportable) $Doc->ExportCaption($this->activity_id);
				if ($this->issue_id->Exportable) $Doc->ExportCaption($this->issue_id);
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
					if ($this->nu_usuario->Exportable) $Doc->ExportField($this->nu_usuario);
					if ($this->spent_on->Exportable) $Doc->ExportField($this->spent_on);
					if ($this->nu_semana->Exportable) $Doc->ExportField($this->nu_semana);
					if ($this->data->Exportable) $Doc->ExportField($this->data);
					if ($this->nu_mes->Exportable) $Doc->ExportField($this->nu_mes);
					if ($this->nu_ano->Exportable) $Doc->ExportField($this->nu_ano);
					if ($this->qt_horas->Exportable) $Doc->ExportField($this->qt_horas);
					if ($this->activity_id->Exportable) $Doc->ExportField($this->activity_id);
					if ($this->issue_id->Exportable) $Doc->ExportField($this->issue_id);
				} else {
					if ($this->nu_usuario->Exportable) $Doc->ExportField($this->nu_usuario);
					if ($this->spent_on->Exportable) $Doc->ExportField($this->spent_on);
					if ($this->nu_semana->Exportable) $Doc->ExportField($this->nu_semana);
					if ($this->data->Exportable) $Doc->ExportField($this->data);
					if ($this->nu_mes->Exportable) $Doc->ExportField($this->nu_mes);
					if ($this->nu_ano->Exportable) $Doc->ExportField($this->nu_ano);
					if ($this->qt_horas->Exportable) $Doc->ExportField($this->qt_horas);
					if ($this->activity_id->Exportable) $Doc->ExportField($this->activity_id);
					if ($this->issue_id->Exportable) $Doc->ExportField($this->issue_id);
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
