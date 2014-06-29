<?php

// Global variable for table object
$metaneg = NULL;

//
// Table class for metaneg
//
class cmetaneg extends cTable {
	var $nu_metaneg;
	var $nu_periodoPei;
	var $nu_necessidade;
	var $ic_perspectiva;
	var $no_metaneg;
	var $ds_metaneg;
	var $ic_situacao;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'metaneg';
		$this->TableName = 'metaneg';
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

		// nu_metaneg
		$this->nu_metaneg = new cField('metaneg', 'metaneg', 'x_nu_metaneg', 'nu_metaneg', '[nu_metaneg]', 'CAST([nu_metaneg] AS NVARCHAR)', 3, -1, FALSE, '[nu_metaneg]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_metaneg->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_metaneg'] = &$this->nu_metaneg;

		// nu_periodoPei
		$this->nu_periodoPei = new cField('metaneg', 'metaneg', 'x_nu_periodoPei', 'nu_periodoPei', '[nu_periodoPei]', 'CAST([nu_periodoPei] AS NVARCHAR)', 3, -1, FALSE, '[nu_periodoPei]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_periodoPei->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_periodoPei'] = &$this->nu_periodoPei;

		// nu_necessidade
		$this->nu_necessidade = new cField('metaneg', 'metaneg', 'x_nu_necessidade', 'nu_necessidade', '[nu_necessidade]', 'CAST([nu_necessidade] AS NVARCHAR)', 3, -1, FALSE, '[nu_necessidade]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_necessidade->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_necessidade'] = &$this->nu_necessidade;

		// ic_perspectiva
		$this->ic_perspectiva = new cField('metaneg', 'metaneg', 'x_ic_perspectiva', 'ic_perspectiva', '[ic_perspectiva]', '[ic_perspectiva]', 129, -1, FALSE, '[ic_perspectiva]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_perspectiva'] = &$this->ic_perspectiva;

		// no_metaneg
		$this->no_metaneg = new cField('metaneg', 'metaneg', 'x_no_metaneg', 'no_metaneg', '[no_metaneg]', '[no_metaneg]', 200, -1, FALSE, '[no_metaneg]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_metaneg'] = &$this->no_metaneg;

		// ds_metaneg
		$this->ds_metaneg = new cField('metaneg', 'metaneg', 'x_ds_metaneg', 'ds_metaneg', '[ds_metaneg]', '[ds_metaneg]', 201, -1, FALSE, '[ds_metaneg]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_metaneg'] = &$this->ds_metaneg;

		// ic_situacao
		$this->ic_situacao = new cField('metaneg', 'metaneg', 'x_ic_situacao', 'ic_situacao', '[ic_situacao]', '[ic_situacao]', 129, -1, FALSE, '[ic_situacao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_situacao'] = &$this->ic_situacao;
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
		return "[dbo].[metaneg]";
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
	var $UpdateTable = "[dbo].[metaneg]";

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
			if (array_key_exists('nu_metaneg', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_metaneg') . '=' . ew_QuotedValue($rs['nu_metaneg'], $this->nu_metaneg->FldDataType));
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
		return "[nu_metaneg] = @nu_metaneg@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nu_metaneg->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_metaneg@", ew_AdjustSql($this->nu_metaneg->CurrentValue), $sKeyFilter); // Replace key value
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
			return "metaneglist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "metaneglist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("metanegview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("metanegview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "metanegadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("metanegedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("metanegadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("metanegdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nu_metaneg->CurrentValue)) {
			$sUrl .= "nu_metaneg=" . urlencode($this->nu_metaneg->CurrentValue);
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
			$arKeys[] = @$_GET["nu_metaneg"]; // nu_metaneg

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
			$this->nu_metaneg->CurrentValue = $key;
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
		$this->nu_metaneg->setDbValue($rs->fields('nu_metaneg'));
		$this->nu_periodoPei->setDbValue($rs->fields('nu_periodoPei'));
		$this->nu_necessidade->setDbValue($rs->fields('nu_necessidade'));
		$this->ic_perspectiva->setDbValue($rs->fields('ic_perspectiva'));
		$this->no_metaneg->setDbValue($rs->fields('no_metaneg'));
		$this->ds_metaneg->setDbValue($rs->fields('ds_metaneg'));
		$this->ic_situacao->setDbValue($rs->fields('ic_situacao'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_metaneg
		// nu_periodoPei
		// nu_necessidade
		// ic_perspectiva
		// no_metaneg
		// ds_metaneg
		// ic_situacao
		// nu_metaneg

		$this->nu_metaneg->ViewValue = $this->nu_metaneg->CurrentValue;
		$this->nu_metaneg->ViewCustomAttributes = "";

		// nu_periodoPei
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
		$this->nu_periodoPei->ViewCustomAttributes = "";

		// nu_necessidade
		$this->nu_necessidade->ViewValue = $this->nu_necessidade->CurrentValue;
		$this->nu_necessidade->ViewCustomAttributes = "";

		// ic_perspectiva
		if (strval($this->ic_perspectiva->CurrentValue) <> "") {
			switch ($this->ic_perspectiva->CurrentValue) {
				case $this->ic_perspectiva->FldTagValue(1):
					$this->ic_perspectiva->ViewValue = $this->ic_perspectiva->FldTagCaption(1) <> "" ? $this->ic_perspectiva->FldTagCaption(1) : $this->ic_perspectiva->CurrentValue;
					break;
				case $this->ic_perspectiva->FldTagValue(2):
					$this->ic_perspectiva->ViewValue = $this->ic_perspectiva->FldTagCaption(2) <> "" ? $this->ic_perspectiva->FldTagCaption(2) : $this->ic_perspectiva->CurrentValue;
					break;
				case $this->ic_perspectiva->FldTagValue(3):
					$this->ic_perspectiva->ViewValue = $this->ic_perspectiva->FldTagCaption(3) <> "" ? $this->ic_perspectiva->FldTagCaption(3) : $this->ic_perspectiva->CurrentValue;
					break;
				case $this->ic_perspectiva->FldTagValue(4):
					$this->ic_perspectiva->ViewValue = $this->ic_perspectiva->FldTagCaption(4) <> "" ? $this->ic_perspectiva->FldTagCaption(4) : $this->ic_perspectiva->CurrentValue;
					break;
				default:
					$this->ic_perspectiva->ViewValue = $this->ic_perspectiva->CurrentValue;
			}
		} else {
			$this->ic_perspectiva->ViewValue = NULL;
		}
		$this->ic_perspectiva->ViewCustomAttributes = "";

		// no_metaneg
		$this->no_metaneg->ViewValue = $this->no_metaneg->CurrentValue;
		$this->no_metaneg->ViewCustomAttributes = "";

		// ds_metaneg
		$this->ds_metaneg->ViewValue = $this->ds_metaneg->CurrentValue;
		$this->ds_metaneg->ViewCustomAttributes = "";

		// ic_situacao
		if (strval($this->ic_situacao->CurrentValue) <> "") {
			switch ($this->ic_situacao->CurrentValue) {
				case $this->ic_situacao->FldTagValue(1):
					$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(1) <> "" ? $this->ic_situacao->FldTagCaption(1) : $this->ic_situacao->CurrentValue;
					break;
				case $this->ic_situacao->FldTagValue(2):
					$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(2) <> "" ? $this->ic_situacao->FldTagCaption(2) : $this->ic_situacao->CurrentValue;
					break;
				case $this->ic_situacao->FldTagValue(3):
					$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(3) <> "" ? $this->ic_situacao->FldTagCaption(3) : $this->ic_situacao->CurrentValue;
					break;
				case $this->ic_situacao->FldTagValue(4):
					$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(4) <> "" ? $this->ic_situacao->FldTagCaption(4) : $this->ic_situacao->CurrentValue;
					break;
				default:
					$this->ic_situacao->ViewValue = $this->ic_situacao->CurrentValue;
			}
		} else {
			$this->ic_situacao->ViewValue = NULL;
		}
		$this->ic_situacao->ViewCustomAttributes = "";

		// nu_metaneg
		$this->nu_metaneg->LinkCustomAttributes = "";
		$this->nu_metaneg->HrefValue = "";
		$this->nu_metaneg->TooltipValue = "";

		// nu_periodoPei
		$this->nu_periodoPei->LinkCustomAttributes = "";
		$this->nu_periodoPei->HrefValue = "";
		$this->nu_periodoPei->TooltipValue = "";

		// nu_necessidade
		$this->nu_necessidade->LinkCustomAttributes = "";
		$this->nu_necessidade->HrefValue = "";
		$this->nu_necessidade->TooltipValue = "";

		// ic_perspectiva
		$this->ic_perspectiva->LinkCustomAttributes = "";
		$this->ic_perspectiva->HrefValue = "";
		$this->ic_perspectiva->TooltipValue = "";

		// no_metaneg
		$this->no_metaneg->LinkCustomAttributes = "";
		$this->no_metaneg->HrefValue = "";
		$this->no_metaneg->TooltipValue = "";

		// ds_metaneg
		$this->ds_metaneg->LinkCustomAttributes = "";
		$this->ds_metaneg->HrefValue = "";
		$this->ds_metaneg->TooltipValue = "";

		// ic_situacao
		$this->ic_situacao->LinkCustomAttributes = "";
		$this->ic_situacao->HrefValue = "";
		$this->ic_situacao->TooltipValue = "";

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
				if ($this->nu_metaneg->Exportable) $Doc->ExportCaption($this->nu_metaneg);
				if ($this->nu_periodoPei->Exportable) $Doc->ExportCaption($this->nu_periodoPei);
				if ($this->nu_necessidade->Exportable) $Doc->ExportCaption($this->nu_necessidade);
				if ($this->ic_perspectiva->Exportable) $Doc->ExportCaption($this->ic_perspectiva);
				if ($this->no_metaneg->Exportable) $Doc->ExportCaption($this->no_metaneg);
				if ($this->ds_metaneg->Exportable) $Doc->ExportCaption($this->ds_metaneg);
				if ($this->ic_situacao->Exportable) $Doc->ExportCaption($this->ic_situacao);
			} else {
				if ($this->nu_metaneg->Exportable) $Doc->ExportCaption($this->nu_metaneg);
				if ($this->nu_periodoPei->Exportable) $Doc->ExportCaption($this->nu_periodoPei);
				if ($this->nu_necessidade->Exportable) $Doc->ExportCaption($this->nu_necessidade);
				if ($this->ic_perspectiva->Exportable) $Doc->ExportCaption($this->ic_perspectiva);
				if ($this->no_metaneg->Exportable) $Doc->ExportCaption($this->no_metaneg);
				if ($this->ic_situacao->Exportable) $Doc->ExportCaption($this->ic_situacao);
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
					if ($this->nu_metaneg->Exportable) $Doc->ExportField($this->nu_metaneg);
					if ($this->nu_periodoPei->Exportable) $Doc->ExportField($this->nu_periodoPei);
					if ($this->nu_necessidade->Exportable) $Doc->ExportField($this->nu_necessidade);
					if ($this->ic_perspectiva->Exportable) $Doc->ExportField($this->ic_perspectiva);
					if ($this->no_metaneg->Exportable) $Doc->ExportField($this->no_metaneg);
					if ($this->ds_metaneg->Exportable) $Doc->ExportField($this->ds_metaneg);
					if ($this->ic_situacao->Exportable) $Doc->ExportField($this->ic_situacao);
				} else {
					if ($this->nu_metaneg->Exportable) $Doc->ExportField($this->nu_metaneg);
					if ($this->nu_periodoPei->Exportable) $Doc->ExportField($this->nu_periodoPei);
					if ($this->nu_necessidade->Exportable) $Doc->ExportField($this->nu_necessidade);
					if ($this->ic_perspectiva->Exportable) $Doc->ExportField($this->ic_perspectiva);
					if ($this->no_metaneg->Exportable) $Doc->ExportField($this->no_metaneg);
					if ($this->ic_situacao->Exportable) $Doc->ExportField($this->ic_situacao);
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
