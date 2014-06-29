<?php

// Global variable for table object
$vwrdmd_lanHorasUsuarioMes = NULL;

//
// Table class for vwrdmd_lanHorasUsuarioMes
//
class cvwrdmd_lanHorasUsuarioMes extends cTable {
	var $nu_usuario;
	var $no_usuario;
	var $no_periodo;
	var $nu_mes;
	var $nu_ano;
	var $qt_horas;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'vwrdmd_lanHorasUsuarioMes';
		$this->TableName = 'vwrdmd_lanHorasUsuarioMes';
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
		$this->nu_usuario = new cField('vwrdmd_lanHorasUsuarioMes', 'vwrdmd_lanHorasUsuarioMes', 'x_nu_usuario', 'nu_usuario', '[nu_usuario]', 'CAST([nu_usuario] AS NVARCHAR)', 3, -1, FALSE, '[nu_usuario]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_usuario->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_usuario'] = &$this->nu_usuario;

		// no_usuario
		$this->no_usuario = new cField('vwrdmd_lanHorasUsuarioMes', 'vwrdmd_lanHorasUsuarioMes', 'x_no_usuario', 'no_usuario', '[no_usuario]', '[no_usuario]', 202, -1, FALSE, '[no_usuario]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_usuario'] = &$this->no_usuario;

		// no_periodo
		$this->no_periodo = new cField('vwrdmd_lanHorasUsuarioMes', 'vwrdmd_lanHorasUsuarioMes', 'x_no_periodo', 'no_periodo', '[no_periodo]', '[no_periodo]', 202, -1, FALSE, '[no_periodo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_periodo'] = &$this->no_periodo;

		// nu_mes
		$this->nu_mes = new cField('vwrdmd_lanHorasUsuarioMes', 'vwrdmd_lanHorasUsuarioMes', 'x_nu_mes', 'nu_mes', '[nu_mes]', 'CAST([nu_mes] AS NVARCHAR)', 3, -1, FALSE, '[nu_mes]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_mes->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_mes'] = &$this->nu_mes;

		// nu_ano
		$this->nu_ano = new cField('vwrdmd_lanHorasUsuarioMes', 'vwrdmd_lanHorasUsuarioMes', 'x_nu_ano', 'nu_ano', '[nu_ano]', 'CAST([nu_ano] AS NVARCHAR)', 3, -1, FALSE, '[nu_ano]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_ano->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_ano'] = &$this->nu_ano;

		// qt_horas
		$this->qt_horas = new cField('vwrdmd_lanHorasUsuarioMes', 'vwrdmd_lanHorasUsuarioMes', 'x_qt_horas', 'qt_horas', '[qt_horas]', 'CAST([qt_horas] AS NVARCHAR)', 131, -1, FALSE, '[qt_horas]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->qt_horas->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['qt_horas'] = &$this->qt_horas;
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
		return "[db_owner].[vwrdmd_lanHorasUsuarioMes]";
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
	var $UpdateTable = "[db_owner].[vwrdmd_lanHorasUsuarioMes]";

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
			return "vwrdmd_lanhorasusuariomeslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "vwrdmd_lanhorasusuariomeslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("vwrdmd_lanhorasusuariomesview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("vwrdmd_lanhorasusuariomesview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "vwrdmd_lanhorasusuariomesadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("vwrdmd_lanhorasusuariomesedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("vwrdmd_lanhorasusuariomesadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("vwrdmd_lanhorasusuariomesdelete.php", $this->UrlParm());
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
		$this->no_usuario->setDbValue($rs->fields('no_usuario'));
		$this->no_periodo->setDbValue($rs->fields('no_periodo'));
		$this->nu_mes->setDbValue($rs->fields('nu_mes'));
		$this->nu_ano->setDbValue($rs->fields('nu_ano'));
		$this->qt_horas->setDbValue($rs->fields('qt_horas'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_usuario
		// no_usuario
		// no_periodo
		// nu_mes
		// nu_ano
		// qt_horas
		// nu_usuario

		$this->nu_usuario->ViewValue = $this->nu_usuario->CurrentValue;
		$this->nu_usuario->ViewCustomAttributes = "";

		// no_usuario
		$this->no_usuario->ViewValue = $this->no_usuario->CurrentValue;
		$this->no_usuario->ViewCustomAttributes = "";

		// no_periodo
		$this->no_periodo->ViewValue = $this->no_periodo->CurrentValue;
		$this->no_periodo->ViewCustomAttributes = "";

		// nu_mes
		$this->nu_mes->ViewValue = $this->nu_mes->CurrentValue;
		$this->nu_mes->ViewCustomAttributes = "";

		// nu_ano
		if (strval($this->nu_ano->CurrentValue) <> "") {
			switch ($this->nu_ano->CurrentValue) {
				case $this->nu_ano->FldTagValue(1):
					$this->nu_ano->ViewValue = $this->nu_ano->FldTagCaption(1) <> "" ? $this->nu_ano->FldTagCaption(1) : $this->nu_ano->CurrentValue;
					break;
				case $this->nu_ano->FldTagValue(2):
					$this->nu_ano->ViewValue = $this->nu_ano->FldTagCaption(2) <> "" ? $this->nu_ano->FldTagCaption(2) : $this->nu_ano->CurrentValue;
					break;
				case $this->nu_ano->FldTagValue(3):
					$this->nu_ano->ViewValue = $this->nu_ano->FldTagCaption(3) <> "" ? $this->nu_ano->FldTagCaption(3) : $this->nu_ano->CurrentValue;
					break;
				case $this->nu_ano->FldTagValue(4):
					$this->nu_ano->ViewValue = $this->nu_ano->FldTagCaption(4) <> "" ? $this->nu_ano->FldTagCaption(4) : $this->nu_ano->CurrentValue;
					break;
				case $this->nu_ano->FldTagValue(5):
					$this->nu_ano->ViewValue = $this->nu_ano->FldTagCaption(5) <> "" ? $this->nu_ano->FldTagCaption(5) : $this->nu_ano->CurrentValue;
					break;
				case $this->nu_ano->FldTagValue(6):
					$this->nu_ano->ViewValue = $this->nu_ano->FldTagCaption(6) <> "" ? $this->nu_ano->FldTagCaption(6) : $this->nu_ano->CurrentValue;
					break;
				case $this->nu_ano->FldTagValue(7):
					$this->nu_ano->ViewValue = $this->nu_ano->FldTagCaption(7) <> "" ? $this->nu_ano->FldTagCaption(7) : $this->nu_ano->CurrentValue;
					break;
				case $this->nu_ano->FldTagValue(8):
					$this->nu_ano->ViewValue = $this->nu_ano->FldTagCaption(8) <> "" ? $this->nu_ano->FldTagCaption(8) : $this->nu_ano->CurrentValue;
					break;
				case $this->nu_ano->FldTagValue(9):
					$this->nu_ano->ViewValue = $this->nu_ano->FldTagCaption(9) <> "" ? $this->nu_ano->FldTagCaption(9) : $this->nu_ano->CurrentValue;
					break;
				case $this->nu_ano->FldTagValue(10):
					$this->nu_ano->ViewValue = $this->nu_ano->FldTagCaption(10) <> "" ? $this->nu_ano->FldTagCaption(10) : $this->nu_ano->CurrentValue;
					break;
				default:
					$this->nu_ano->ViewValue = $this->nu_ano->CurrentValue;
			}
		} else {
			$this->nu_ano->ViewValue = NULL;
		}
		$this->nu_ano->ViewCustomAttributes = "";

		// qt_horas
		$this->qt_horas->ViewValue = $this->qt_horas->CurrentValue;
		$this->qt_horas->ViewValue = ew_FormatNumber($this->qt_horas->ViewValue, 2, -2, -2, -2);
		$this->qt_horas->ViewCustomAttributes = "";

		// nu_usuario
		$this->nu_usuario->LinkCustomAttributes = "";
		$this->nu_usuario->HrefValue = "";
		$this->nu_usuario->TooltipValue = "";

		// no_usuario
		$this->no_usuario->LinkCustomAttributes = "";
		$this->no_usuario->HrefValue = "";
		$this->no_usuario->TooltipValue = "";

		// no_periodo
		$this->no_periodo->LinkCustomAttributes = "";
		$this->no_periodo->HrefValue = "";
		$this->no_periodo->TooltipValue = "";

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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
			if (is_numeric($this->qt_horas->CurrentValue))
				$this->qt_horas->Total += $this->qt_horas->CurrentValue; // Accumulate total
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
			$this->qt_horas->CurrentValue = $this->qt_horas->Total;
			$this->qt_horas->ViewValue = $this->qt_horas->CurrentValue;
			$this->qt_horas->ViewValue = ew_FormatNumber($this->qt_horas->ViewValue, 2, -2, -2, -2);
			$this->qt_horas->ViewCustomAttributes = "";
			$this->qt_horas->HrefValue = ""; // Clear href value
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
				if ($this->no_usuario->Exportable) $Doc->ExportCaption($this->no_usuario);
				if ($this->no_periodo->Exportable) $Doc->ExportCaption($this->no_periodo);
				if ($this->nu_mes->Exportable) $Doc->ExportCaption($this->nu_mes);
				if ($this->nu_ano->Exportable) $Doc->ExportCaption($this->nu_ano);
				if ($this->qt_horas->Exportable) $Doc->ExportCaption($this->qt_horas);
			} else {
				if ($this->nu_usuario->Exportable) $Doc->ExportCaption($this->nu_usuario);
				if ($this->no_usuario->Exportable) $Doc->ExportCaption($this->no_usuario);
				if ($this->no_periodo->Exportable) $Doc->ExportCaption($this->no_periodo);
				if ($this->nu_mes->Exportable) $Doc->ExportCaption($this->nu_mes);
				if ($this->nu_ano->Exportable) $Doc->ExportCaption($this->nu_ano);
				if ($this->qt_horas->Exportable) $Doc->ExportCaption($this->qt_horas);
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
					if ($this->nu_usuario->Exportable) $Doc->ExportField($this->nu_usuario);
					if ($this->no_usuario->Exportable) $Doc->ExportField($this->no_usuario);
					if ($this->no_periodo->Exportable) $Doc->ExportField($this->no_periodo);
					if ($this->nu_mes->Exportable) $Doc->ExportField($this->nu_mes);
					if ($this->nu_ano->Exportable) $Doc->ExportField($this->nu_ano);
					if ($this->qt_horas->Exportable) $Doc->ExportField($this->qt_horas);
				} else {
					if ($this->nu_usuario->Exportable) $Doc->ExportField($this->nu_usuario);
					if ($this->no_usuario->Exportable) $Doc->ExportField($this->no_usuario);
					if ($this->no_periodo->Exportable) $Doc->ExportField($this->no_periodo);
					if ($this->nu_mes->Exportable) $Doc->ExportField($this->nu_mes);
					if ($this->nu_ano->Exportable) $Doc->ExportField($this->nu_ano);
					if ($this->qt_horas->Exportable) $Doc->ExportField($this->qt_horas);
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
			$Doc->ExportAggregate($this->nu_usuario, '');
			$Doc->ExportAggregate($this->no_usuario, '');
			$Doc->ExportAggregate($this->no_periodo, '');
			$Doc->ExportAggregate($this->nu_mes, '');
			$Doc->ExportAggregate($this->nu_ano, '');
			$Doc->ExportAggregate($this->qt_horas, 'TOTAL');
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
