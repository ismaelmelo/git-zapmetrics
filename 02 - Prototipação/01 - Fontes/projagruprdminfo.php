<?php

// Global variable for table object
$projagruprdm = NULL;

//
// Table class for projagruprdm
//
class cprojagruprdm extends cTable {
	var $nu_projAgrupRedmine;
	var $nu_nivel;
	var $nu_projAgrupPai;
	var $ds_projredmine;
	var $nu_usuarioInc;
	var $dh_inclusao;
	var $nu_usuarioAlt;
	var $dh_alteracao;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'projagruprdm';
		$this->TableName = 'projagruprdm';
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

		// nu_projAgrupRedmine
		$this->nu_projAgrupRedmine = new cField('projagruprdm', 'projagruprdm', 'x_nu_projAgrupRedmine', 'nu_projAgrupRedmine', '[nu_projAgrupRedmine]', 'CAST([nu_projAgrupRedmine] AS NVARCHAR)', 3, -1, FALSE, '[nu_projAgrupRedmine]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_projAgrupRedmine->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_projAgrupRedmine'] = &$this->nu_projAgrupRedmine;

		// nu_nivel
		$this->nu_nivel = new cField('projagruprdm', 'projagruprdm', 'x_nu_nivel', 'nu_nivel', '[nu_nivel]', 'CAST([nu_nivel] AS NVARCHAR)', 3, -1, FALSE, '[nu_nivel]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_nivel->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_nivel'] = &$this->nu_nivel;

		// nu_projAgrupPai
		$this->nu_projAgrupPai = new cField('projagruprdm', 'projagruprdm', 'x_nu_projAgrupPai', 'nu_projAgrupPai', '[nu_projAgrupPai]', 'CAST([nu_projAgrupPai] AS NVARCHAR)', 3, -1, FALSE, '[nu_projAgrupPai]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_projAgrupPai->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_projAgrupPai'] = &$this->nu_projAgrupPai;

		// ds_projredmine
		$this->ds_projredmine = new cField('projagruprdm', 'projagruprdm', 'x_ds_projredmine', 'ds_projredmine', '[ds_projredmine]', '[ds_projredmine]', 201, -1, FALSE, '[ds_projredmine]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_projredmine'] = &$this->ds_projredmine;

		// nu_usuarioInc
		$this->nu_usuarioInc = new cField('projagruprdm', 'projagruprdm', 'x_nu_usuarioInc', 'nu_usuarioInc', '[nu_usuarioInc]', 'CAST([nu_usuarioInc] AS NVARCHAR)', 3, -1, FALSE, '[nu_usuarioInc]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_usuarioInc->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_usuarioInc'] = &$this->nu_usuarioInc;

		// dh_inclusao
		$this->dh_inclusao = new cField('projagruprdm', 'projagruprdm', 'x_dh_inclusao', 'dh_inclusao', '[dh_inclusao]', '(REPLACE(STR(DAY([dh_inclusao]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dh_inclusao]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dh_inclusao]),4,0))', 135, 7, FALSE, '[dh_inclusao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dh_inclusao->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dh_inclusao'] = &$this->dh_inclusao;

		// nu_usuarioAlt
		$this->nu_usuarioAlt = new cField('projagruprdm', 'projagruprdm', 'x_nu_usuarioAlt', 'nu_usuarioAlt', '[nu_usuarioAlt]', 'CAST([nu_usuarioAlt] AS NVARCHAR)', 3, -1, FALSE, '[nu_usuarioAlt]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_usuarioAlt->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_usuarioAlt'] = &$this->nu_usuarioAlt;

		// dh_alteracao
		$this->dh_alteracao = new cField('projagruprdm', 'projagruprdm', 'x_dh_alteracao', 'dh_alteracao', '[dh_alteracao]', '(REPLACE(STR(DAY([dh_alteracao]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dh_alteracao]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dh_alteracao]),4,0))', 135, 7, FALSE, '[dh_alteracao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dh_alteracao->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dh_alteracao'] = &$this->dh_alteracao;
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
		return "[dbo].[projagruprdm]";
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
	var $UpdateTable = "[dbo].[projagruprdm]";

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
			if (array_key_exists('nu_projAgrupRedmine', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_projAgrupRedmine') . '=' . ew_QuotedValue($rs['nu_projAgrupRedmine'], $this->nu_projAgrupRedmine->FldDataType));
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
		return "[nu_projAgrupRedmine] = @nu_projAgrupRedmine@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nu_projAgrupRedmine->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_projAgrupRedmine@", ew_AdjustSql($this->nu_projAgrupRedmine->CurrentValue), $sKeyFilter); // Replace key value
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
			return "projagruprdmlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "projagruprdmlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("projagruprdmview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("projagruprdmview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "projagruprdmadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("projagruprdmedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("projagruprdmadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("projagruprdmdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nu_projAgrupRedmine->CurrentValue)) {
			$sUrl .= "nu_projAgrupRedmine=" . urlencode($this->nu_projAgrupRedmine->CurrentValue);
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
			$arKeys[] = @$_GET["nu_projAgrupRedmine"]; // nu_projAgrupRedmine

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
			$this->nu_projAgrupRedmine->CurrentValue = $key;
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
		$this->nu_projAgrupRedmine->setDbValue($rs->fields('nu_projAgrupRedmine'));
		$this->nu_nivel->setDbValue($rs->fields('nu_nivel'));
		$this->nu_projAgrupPai->setDbValue($rs->fields('nu_projAgrupPai'));
		$this->ds_projredmine->setDbValue($rs->fields('ds_projredmine'));
		$this->nu_usuarioInc->setDbValue($rs->fields('nu_usuarioInc'));
		$this->dh_inclusao->setDbValue($rs->fields('dh_inclusao'));
		$this->nu_usuarioAlt->setDbValue($rs->fields('nu_usuarioAlt'));
		$this->dh_alteracao->setDbValue($rs->fields('dh_alteracao'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_projAgrupRedmine
		// nu_nivel
		// nu_projAgrupPai
		// ds_projredmine
		// nu_usuarioInc
		// dh_inclusao
		// nu_usuarioAlt
		// dh_alteracao
		// nu_projAgrupRedmine

		$this->nu_projAgrupRedmine->ViewValue = $this->nu_projAgrupRedmine->CurrentValue;
		$this->nu_projAgrupRedmine->ViewCustomAttributes = "";

		// nu_nivel
		if (strval($this->nu_nivel->CurrentValue) <> "") {
			switch ($this->nu_nivel->CurrentValue) {
				case $this->nu_nivel->FldTagValue(1):
					$this->nu_nivel->ViewValue = $this->nu_nivel->FldTagCaption(1) <> "" ? $this->nu_nivel->FldTagCaption(1) : $this->nu_nivel->CurrentValue;
					break;
				case $this->nu_nivel->FldTagValue(2):
					$this->nu_nivel->ViewValue = $this->nu_nivel->FldTagCaption(2) <> "" ? $this->nu_nivel->FldTagCaption(2) : $this->nu_nivel->CurrentValue;
					break;
				case $this->nu_nivel->FldTagValue(3):
					$this->nu_nivel->ViewValue = $this->nu_nivel->FldTagCaption(3) <> "" ? $this->nu_nivel->FldTagCaption(3) : $this->nu_nivel->CurrentValue;
					break;
				case $this->nu_nivel->FldTagValue(4):
					$this->nu_nivel->ViewValue = $this->nu_nivel->FldTagCaption(4) <> "" ? $this->nu_nivel->FldTagCaption(4) : $this->nu_nivel->CurrentValue;
					break;
				case $this->nu_nivel->FldTagValue(5):
					$this->nu_nivel->ViewValue = $this->nu_nivel->FldTagCaption(5) <> "" ? $this->nu_nivel->FldTagCaption(5) : $this->nu_nivel->CurrentValue;
					break;
				default:
					$this->nu_nivel->ViewValue = $this->nu_nivel->CurrentValue;
			}
		} else {
			$this->nu_nivel->ViewValue = NULL;
		}
		$this->nu_nivel->ViewCustomAttributes = "";

		// nu_projAgrupPai
		$this->nu_projAgrupPai->ViewValue = $this->nu_projAgrupPai->CurrentValue;
		$this->nu_projAgrupPai->ViewCustomAttributes = "";

		// ds_projredmine
		$this->ds_projredmine->ViewValue = $this->ds_projredmine->CurrentValue;
		$this->ds_projredmine->ViewCustomAttributes = "";

		// nu_usuarioInc
		if (strval($this->nu_usuarioInc->CurrentValue) <> "") {
			$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioInc->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_usuarioInc, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_usuarioInc->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_usuarioInc->ViewValue = $this->nu_usuarioInc->CurrentValue;
			}
		} else {
			$this->nu_usuarioInc->ViewValue = NULL;
		}
		$this->nu_usuarioInc->ViewCustomAttributes = "";

		// dh_inclusao
		$this->dh_inclusao->ViewValue = $this->dh_inclusao->CurrentValue;
		$this->dh_inclusao->ViewValue = ew_FormatDateTime($this->dh_inclusao->ViewValue, 7);
		$this->dh_inclusao->ViewCustomAttributes = "";

		// nu_usuarioAlt
		if (strval($this->nu_usuarioAlt->CurrentValue) <> "") {
			$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioAlt->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_usuarioAlt, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_usuarioAlt->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_usuarioAlt->ViewValue = $this->nu_usuarioAlt->CurrentValue;
			}
		} else {
			$this->nu_usuarioAlt->ViewValue = NULL;
		}
		$this->nu_usuarioAlt->ViewCustomAttributes = "";

		// dh_alteracao
		$this->dh_alteracao->ViewValue = $this->dh_alteracao->CurrentValue;
		$this->dh_alteracao->ViewValue = ew_FormatDateTime($this->dh_alteracao->ViewValue, 7);
		$this->dh_alteracao->ViewCustomAttributes = "";

		// nu_projAgrupRedmine
		$this->nu_projAgrupRedmine->LinkCustomAttributes = "";
		$this->nu_projAgrupRedmine->HrefValue = "";
		$this->nu_projAgrupRedmine->TooltipValue = "";

		// nu_nivel
		$this->nu_nivel->LinkCustomAttributes = "";
		$this->nu_nivel->HrefValue = "";
		$this->nu_nivel->TooltipValue = "";

		// nu_projAgrupPai
		$this->nu_projAgrupPai->LinkCustomAttributes = "";
		$this->nu_projAgrupPai->HrefValue = "";
		$this->nu_projAgrupPai->TooltipValue = "";

		// ds_projredmine
		$this->ds_projredmine->LinkCustomAttributes = "";
		$this->ds_projredmine->HrefValue = "";
		$this->ds_projredmine->TooltipValue = "";

		// nu_usuarioInc
		$this->nu_usuarioInc->LinkCustomAttributes = "";
		$this->nu_usuarioInc->HrefValue = "";
		$this->nu_usuarioInc->TooltipValue = "";

		// dh_inclusao
		$this->dh_inclusao->LinkCustomAttributes = "";
		$this->dh_inclusao->HrefValue = "";
		$this->dh_inclusao->TooltipValue = "";

		// nu_usuarioAlt
		$this->nu_usuarioAlt->LinkCustomAttributes = "";
		$this->nu_usuarioAlt->HrefValue = "";
		$this->nu_usuarioAlt->TooltipValue = "";

		// dh_alteracao
		$this->dh_alteracao->LinkCustomAttributes = "";
		$this->dh_alteracao->HrefValue = "";
		$this->dh_alteracao->TooltipValue = "";

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
				if ($this->nu_projAgrupRedmine->Exportable) $Doc->ExportCaption($this->nu_projAgrupRedmine);
				if ($this->nu_nivel->Exportable) $Doc->ExportCaption($this->nu_nivel);
				if ($this->nu_projAgrupPai->Exportable) $Doc->ExportCaption($this->nu_projAgrupPai);
				if ($this->ds_projredmine->Exportable) $Doc->ExportCaption($this->ds_projredmine);
				if ($this->nu_usuarioInc->Exportable) $Doc->ExportCaption($this->nu_usuarioInc);
				if ($this->dh_inclusao->Exportable) $Doc->ExportCaption($this->dh_inclusao);
				if ($this->nu_usuarioAlt->Exportable) $Doc->ExportCaption($this->nu_usuarioAlt);
				if ($this->dh_alteracao->Exportable) $Doc->ExportCaption($this->dh_alteracao);
			} else {
				if ($this->nu_projAgrupRedmine->Exportable) $Doc->ExportCaption($this->nu_projAgrupRedmine);
				if ($this->nu_nivel->Exportable) $Doc->ExportCaption($this->nu_nivel);
				if ($this->nu_projAgrupPai->Exportable) $Doc->ExportCaption($this->nu_projAgrupPai);
				if ($this->ds_projredmine->Exportable) $Doc->ExportCaption($this->ds_projredmine);
				if ($this->nu_usuarioInc->Exportable) $Doc->ExportCaption($this->nu_usuarioInc);
				if ($this->dh_inclusao->Exportable) $Doc->ExportCaption($this->dh_inclusao);
				if ($this->nu_usuarioAlt->Exportable) $Doc->ExportCaption($this->nu_usuarioAlt);
				if ($this->dh_alteracao->Exportable) $Doc->ExportCaption($this->dh_alteracao);
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
					if ($this->nu_projAgrupRedmine->Exportable) $Doc->ExportField($this->nu_projAgrupRedmine);
					if ($this->nu_nivel->Exportable) $Doc->ExportField($this->nu_nivel);
					if ($this->nu_projAgrupPai->Exportable) $Doc->ExportField($this->nu_projAgrupPai);
					if ($this->ds_projredmine->Exportable) $Doc->ExportField($this->ds_projredmine);
					if ($this->nu_usuarioInc->Exportable) $Doc->ExportField($this->nu_usuarioInc);
					if ($this->dh_inclusao->Exportable) $Doc->ExportField($this->dh_inclusao);
					if ($this->nu_usuarioAlt->Exportable) $Doc->ExportField($this->nu_usuarioAlt);
					if ($this->dh_alteracao->Exportable) $Doc->ExportField($this->dh_alteracao);
				} else {
					if ($this->nu_projAgrupRedmine->Exportable) $Doc->ExportField($this->nu_projAgrupRedmine);
					if ($this->nu_nivel->Exportable) $Doc->ExportField($this->nu_nivel);
					if ($this->nu_projAgrupPai->Exportable) $Doc->ExportField($this->nu_projAgrupPai);
					if ($this->ds_projredmine->Exportable) $Doc->ExportField($this->ds_projredmine);
					if ($this->nu_usuarioInc->Exportable) $Doc->ExportField($this->nu_usuarioInc);
					if ($this->dh_inclusao->Exportable) $Doc->ExportField($this->dh_inclusao);
					if ($this->nu_usuarioAlt->Exportable) $Doc->ExportField($this->nu_usuarioAlt);
					if ($this->dh_alteracao->Exportable) $Doc->ExportField($this->dh_alteracao);
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
