<?php

// Global variable for table object
$oc = NULL;

//
// Table class for oc
//
class coc extends cTable {
	var $nu_oc;
	var $ic_tpOc;
	var $co_alternativo;
	var $ds_oc;
	var $dt_oc;
	var $nu_stOc;
	var $ds_observacoes;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'oc';
		$this->TableName = 'oc';
		$this->TableType = 'TABLE';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->DetailAdd = TRUE; // Allow detail add
		$this->DetailEdit = TRUE; // Allow detail edit
		$this->DetailView = TRUE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 1;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// nu_oc
		$this->nu_oc = new cField('oc', 'oc', 'x_nu_oc', 'nu_oc', '[nu_oc]', 'CAST([nu_oc] AS NVARCHAR)', 3, -1, FALSE, '[nu_oc]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_oc->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_oc'] = &$this->nu_oc;

		// ic_tpOc
		$this->ic_tpOc = new cField('oc', 'oc', 'x_ic_tpOc', 'ic_tpOc', '[ic_tpOc]', '[ic_tpOc]', 129, -1, FALSE, '[ic_tpOc]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_tpOc'] = &$this->ic_tpOc;

		// co_alternativo
		$this->co_alternativo = new cField('oc', 'oc', 'x_co_alternativo', 'co_alternativo', '[co_alternativo]', '[co_alternativo]', 200, -1, FALSE, '[co_alternativo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_alternativo'] = &$this->co_alternativo;

		// ds_oc
		$this->ds_oc = new cField('oc', 'oc', 'x_ds_oc', 'ds_oc', '[ds_oc]', '[ds_oc]', 201, -1, FALSE, '[ds_oc]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_oc'] = &$this->ds_oc;

		// dt_oc
		$this->dt_oc = new cField('oc', 'oc', 'x_dt_oc', 'dt_oc', '[dt_oc]', '(REPLACE(STR(DAY([dt_oc]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dt_oc]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dt_oc]),4,0))', 135, 7, FALSE, '[dt_oc]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dt_oc->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dt_oc'] = &$this->dt_oc;

		// nu_stOc
		$this->nu_stOc = new cField('oc', 'oc', 'x_nu_stOc', 'nu_stOc', '[nu_stOc]', 'CAST([nu_stOc] AS NVARCHAR)', 3, -1, FALSE, '[nu_stOc]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_stOc->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_stOc'] = &$this->nu_stOc;

		// ds_observacoes
		$this->ds_observacoes = new cField('oc', 'oc', 'x_ds_observacoes', 'ds_observacoes', '[ds_observacoes]', '[ds_observacoes]', 201, -1, FALSE, '[ds_observacoes]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_observacoes'] = &$this->ds_observacoes;
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
		if ($this->getCurrentDetailTable() == "itemoc") {
			$sDetailUrl = $GLOBALS["itemoc"]->GetListUrl() . "?showmaster=" . $this->TableVar;
			$sDetailUrl .= "&nu_oc=" . $this->nu_oc->CurrentValue;
		}
		if ($sDetailUrl == "") {
			$sDetailUrl = "oclist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	function SqlFrom() { // From
		return "[dbo].[oc]";
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
		return "[nu_oc] DESC";
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
	var $UpdateTable = "[dbo].[oc]";

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
			if (array_key_exists('nu_oc', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_oc') . '=' . ew_QuotedValue($rs['nu_oc'], $this->nu_oc->FldDataType));
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
		return "[nu_oc] = @nu_oc@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nu_oc->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_oc@", ew_AdjustSql($this->nu_oc->CurrentValue), $sKeyFilter); // Replace key value
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
			return "oclist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "oclist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("ocview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("ocview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "ocadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("ocedit.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("ocedit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("ocadd.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("ocadd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("ocdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nu_oc->CurrentValue)) {
			$sUrl .= "nu_oc=" . urlencode($this->nu_oc->CurrentValue);
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
			$arKeys[] = @$_GET["nu_oc"]; // nu_oc

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
			$this->nu_oc->CurrentValue = $key;
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
		$this->nu_oc->setDbValue($rs->fields('nu_oc'));
		$this->ic_tpOc->setDbValue($rs->fields('ic_tpOc'));
		$this->co_alternativo->setDbValue($rs->fields('co_alternativo'));
		$this->ds_oc->setDbValue($rs->fields('ds_oc'));
		$this->dt_oc->setDbValue($rs->fields('dt_oc'));
		$this->nu_stOc->setDbValue($rs->fields('nu_stOc'));
		$this->ds_observacoes->setDbValue($rs->fields('ds_observacoes'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_oc

		$this->nu_oc->CellCssStyle = "white-space: nowrap;";

		// ic_tpOc
		// co_alternativo
		// ds_oc
		// dt_oc
		// nu_stOc
		// ds_observacoes
		// nu_oc

		$this->nu_oc->ViewValue = $this->nu_oc->CurrentValue;
		$this->nu_oc->ViewCustomAttributes = "";

		// ic_tpOc
		if (strval($this->ic_tpOc->CurrentValue) <> "") {
			switch ($this->ic_tpOc->CurrentValue) {
				case $this->ic_tpOc->FldTagValue(1):
					$this->ic_tpOc->ViewValue = $this->ic_tpOc->FldTagCaption(1) <> "" ? $this->ic_tpOc->FldTagCaption(1) : $this->ic_tpOc->CurrentValue;
					break;
				case $this->ic_tpOc->FldTagValue(2):
					$this->ic_tpOc->ViewValue = $this->ic_tpOc->FldTagCaption(2) <> "" ? $this->ic_tpOc->FldTagCaption(2) : $this->ic_tpOc->CurrentValue;
					break;
				case $this->ic_tpOc->FldTagValue(3):
					$this->ic_tpOc->ViewValue = $this->ic_tpOc->FldTagCaption(3) <> "" ? $this->ic_tpOc->FldTagCaption(3) : $this->ic_tpOc->CurrentValue;
					break;
				default:
					$this->ic_tpOc->ViewValue = $this->ic_tpOc->CurrentValue;
			}
		} else {
			$this->ic_tpOc->ViewValue = NULL;
		}
		$this->ic_tpOc->ViewCustomAttributes = "";

		// co_alternativo
		$this->co_alternativo->ViewValue = $this->co_alternativo->CurrentValue;
		$this->co_alternativo->ViewCustomAttributes = "";

		// ds_oc
		$this->ds_oc->ViewValue = $this->ds_oc->CurrentValue;
		$this->ds_oc->ViewCustomAttributes = "";

		// dt_oc
		$this->dt_oc->ViewValue = $this->dt_oc->CurrentValue;
		$this->dt_oc->ViewValue = ew_FormatDateTime($this->dt_oc->ViewValue, 7);
		$this->dt_oc->ViewCustomAttributes = "";

		// nu_stOc
		if (strval($this->nu_stOc->CurrentValue) <> "") {
			$sFilterWrk = "[nu_stOc]" . ew_SearchString("=", $this->nu_stOc->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_stOc], [no_stOc] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[stoc]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_stOc, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_stOc] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_stOc->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_stOc->ViewValue = $this->nu_stOc->CurrentValue;
			}
		} else {
			$this->nu_stOc->ViewValue = NULL;
		}
		$this->nu_stOc->ViewCustomAttributes = "";

		// ds_observacoes
		$this->ds_observacoes->ViewValue = $this->ds_observacoes->CurrentValue;
		$this->ds_observacoes->ViewCustomAttributes = "";

		// nu_oc
		$this->nu_oc->LinkCustomAttributes = "";
		$this->nu_oc->HrefValue = "";
		$this->nu_oc->TooltipValue = "";

		// ic_tpOc
		$this->ic_tpOc->LinkCustomAttributes = "";
		$this->ic_tpOc->HrefValue = "";
		$this->ic_tpOc->TooltipValue = "";

		// co_alternativo
		$this->co_alternativo->LinkCustomAttributes = "";
		$this->co_alternativo->HrefValue = "";
		$this->co_alternativo->TooltipValue = "";

		// ds_oc
		$this->ds_oc->LinkCustomAttributes = "";
		$this->ds_oc->HrefValue = "";
		$this->ds_oc->TooltipValue = "";

		// dt_oc
		$this->dt_oc->LinkCustomAttributes = "";
		$this->dt_oc->HrefValue = "";
		$this->dt_oc->TooltipValue = "";

		// nu_stOc
		$this->nu_stOc->LinkCustomAttributes = "";
		$this->nu_stOc->HrefValue = "";
		$this->nu_stOc->TooltipValue = "";

		// ds_observacoes
		$this->ds_observacoes->LinkCustomAttributes = "";
		$this->ds_observacoes->HrefValue = "";
		$this->ds_observacoes->TooltipValue = "";

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
				if ($this->ic_tpOc->Exportable) $Doc->ExportCaption($this->ic_tpOc);
				if ($this->co_alternativo->Exportable) $Doc->ExportCaption($this->co_alternativo);
				if ($this->ds_oc->Exportable) $Doc->ExportCaption($this->ds_oc);
				if ($this->dt_oc->Exportable) $Doc->ExportCaption($this->dt_oc);
				if ($this->nu_stOc->Exportable) $Doc->ExportCaption($this->nu_stOc);
				if ($this->ds_observacoes->Exportable) $Doc->ExportCaption($this->ds_observacoes);
			} else {
				if ($this->nu_oc->Exportable) $Doc->ExportCaption($this->nu_oc);
				if ($this->ic_tpOc->Exportable) $Doc->ExportCaption($this->ic_tpOc);
				if ($this->co_alternativo->Exportable) $Doc->ExportCaption($this->co_alternativo);
				if ($this->dt_oc->Exportable) $Doc->ExportCaption($this->dt_oc);
				if ($this->nu_stOc->Exportable) $Doc->ExportCaption($this->nu_stOc);
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
					if ($this->ic_tpOc->Exportable) $Doc->ExportField($this->ic_tpOc);
					if ($this->co_alternativo->Exportable) $Doc->ExportField($this->co_alternativo);
					if ($this->ds_oc->Exportable) $Doc->ExportField($this->ds_oc);
					if ($this->dt_oc->Exportable) $Doc->ExportField($this->dt_oc);
					if ($this->nu_stOc->Exportable) $Doc->ExportField($this->nu_stOc);
					if ($this->ds_observacoes->Exportable) $Doc->ExportField($this->ds_observacoes);
				} else {
					if ($this->nu_oc->Exportable) $Doc->ExportField($this->nu_oc);
					if ($this->ic_tpOc->Exportable) $Doc->ExportField($this->ic_tpOc);
					if ($this->co_alternativo->Exportable) $Doc->ExportField($this->co_alternativo);
					if ($this->dt_oc->Exportable) $Doc->ExportField($this->dt_oc);
					if ($this->nu_stOc->Exportable) $Doc->ExportField($this->nu_stOc);
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
