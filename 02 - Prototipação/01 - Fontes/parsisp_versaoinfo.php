<?php

// Global variable for table object
$parsisp_versao = NULL;

//
// Table class for parsisp_versao
//
class cparsisp_versao extends cTable {
	var $nu_parSisp;
	var $nu_versao;
	var $vr_parSisp;
	var $ds_codigoSql;
	var $nu_usuarioResp;
	var $ds_versao;
	var $dh_inclusao;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'parsisp_versao';
		$this->TableName = 'parsisp_versao';
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

		// nu_parSisp
		$this->nu_parSisp = new cField('parsisp_versao', 'parsisp_versao', 'x_nu_parSisp', 'nu_parSisp', '[nu_parSisp]', 'CAST([nu_parSisp] AS NVARCHAR)', 3, -1, FALSE, '[nu_parSisp]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_parSisp->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_parSisp'] = &$this->nu_parSisp;

		// nu_versao
		$this->nu_versao = new cField('parsisp_versao', 'parsisp_versao', 'x_nu_versao', 'nu_versao', '[nu_versao]', 'CAST([nu_versao] AS NVARCHAR)', 3, -1, FALSE, '[nu_versao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_versao->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_versao'] = &$this->nu_versao;

		// vr_parSisp
		$this->vr_parSisp = new cField('parsisp_versao', 'parsisp_versao', 'x_vr_parSisp', 'vr_parSisp', '[vr_parSisp]', 'CAST([vr_parSisp] AS NVARCHAR)', 131, -1, FALSE, '[vr_parSisp]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->vr_parSisp->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['vr_parSisp'] = &$this->vr_parSisp;

		// ds_codigoSql
		$this->ds_codigoSql = new cField('parsisp_versao', 'parsisp_versao', 'x_ds_codigoSql', 'ds_codigoSql', '[ds_codigoSql]', '[ds_codigoSql]', 201, -1, FALSE, '[ds_codigoSql]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_codigoSql'] = &$this->ds_codigoSql;

		// nu_usuarioResp
		$this->nu_usuarioResp = new cField('parsisp_versao', 'parsisp_versao', 'x_nu_usuarioResp', 'nu_usuarioResp', '[nu_usuarioResp]', 'CAST([nu_usuarioResp] AS NVARCHAR)', 3, -1, FALSE, '[nu_usuarioResp]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_usuarioResp->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_usuarioResp'] = &$this->nu_usuarioResp;

		// ds_versao
		$this->ds_versao = new cField('parsisp_versao', 'parsisp_versao', 'x_ds_versao', 'ds_versao', '[ds_versao]', '[ds_versao]', 201, -1, FALSE, '[ds_versao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_versao'] = &$this->ds_versao;

		// dh_inclusao
		$this->dh_inclusao = new cField('parsisp_versao', 'parsisp_versao', 'x_dh_inclusao', 'dh_inclusao', '[dh_inclusao]', '(REPLACE(STR(DAY([dh_inclusao]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dh_inclusao]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dh_inclusao]),4,0))', 135, 10, FALSE, '[dh_inclusao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dh_inclusao->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dh_inclusao'] = &$this->dh_inclusao;
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
		if ($this->getCurrentMasterTable() == "parSisp") {
			if ($this->nu_parSisp->getSessionValue() <> "")
				$sMasterFilter .= "[nu_parSisp]=" . ew_QuotedValue($this->nu_parSisp->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "parSisp") {
			if ($this->nu_parSisp->getSessionValue() <> "")
				$sDetailFilter .= "[nu_parSisp]=" . ew_QuotedValue($this->nu_parSisp->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_parSisp() {
		return "[nu_parSisp]=@nu_parSisp@";
	}

	// Detail filter
	function SqlDetailFilter_parSisp() {
		return "[nu_parSisp]=@nu_parSisp@";
	}

	// Table level SQL
	function SqlFrom() { // From
		return "[dbo].[parsisp_versao]";
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
		return "[nu_parSisp] ASC,[nu_versao] DESC";
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
	var $UpdateTable = "[dbo].[parsisp_versao]";

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
			if (array_key_exists('nu_parSisp', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_parSisp') . '=' . ew_QuotedValue($rs['nu_parSisp'], $this->nu_parSisp->FldDataType));
			if (array_key_exists('nu_versao', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_versao') . '=' . ew_QuotedValue($rs['nu_versao'], $this->nu_versao->FldDataType));
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
		return "[nu_parSisp] = @nu_parSisp@ AND [nu_versao] = @nu_versao@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nu_parSisp->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_parSisp@", ew_AdjustSql($this->nu_parSisp->CurrentValue), $sKeyFilter); // Replace key value
		if (!is_numeric($this->nu_versao->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_versao@", ew_AdjustSql($this->nu_versao->CurrentValue), $sKeyFilter); // Replace key value
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
			return "parsisp_versaolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "parsisp_versaolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("parsisp_versaoview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("parsisp_versaoview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "parsisp_versaoadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("parsisp_versaoedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("parsisp_versaoadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("parsisp_versaodelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nu_parSisp->CurrentValue)) {
			$sUrl .= "nu_parSisp=" . urlencode($this->nu_parSisp->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->nu_versao->CurrentValue)) {
			$sUrl .= "&nu_versao=" . urlencode($this->nu_versao->CurrentValue);
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
			for ($i = 0; $i < $cnt; $i++)
				$arKeys[$i] = explode($EW_COMPOSITE_KEY_SEPARATOR, $arKeys[$i]);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
			for ($i = 0; $i < $cnt; $i++)
				$arKeys[$i] = explode($EW_COMPOSITE_KEY_SEPARATOR, $arKeys[$i]);
		} elseif (isset($_GET)) {
			$arKey[] = @$_GET["nu_parSisp"]; // nu_parSisp
			$arKey[] = @$_GET["nu_versao"]; // nu_versao
			$arKeys[] = $arKey;

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_array($key) || count($key) <> 2)
				continue; // Just skip so other keys will still work
			if (!is_numeric($key[0])) // nu_parSisp
				continue;
			if (!is_numeric($key[1])) // nu_versao
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
			$this->nu_parSisp->CurrentValue = $key[0];
			$this->nu_versao->CurrentValue = $key[1];
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
		$this->nu_parSisp->setDbValue($rs->fields('nu_parSisp'));
		$this->nu_versao->setDbValue($rs->fields('nu_versao'));
		$this->vr_parSisp->setDbValue($rs->fields('vr_parSisp'));
		$this->ds_codigoSql->setDbValue($rs->fields('ds_codigoSql'));
		$this->nu_usuarioResp->setDbValue($rs->fields('nu_usuarioResp'));
		$this->ds_versao->setDbValue($rs->fields('ds_versao'));
		$this->dh_inclusao->setDbValue($rs->fields('dh_inclusao'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_parSisp
		// nu_versao
		// vr_parSisp
		// ds_codigoSql
		// nu_usuarioResp
		// ds_versao
		// dh_inclusao
		// nu_parSisp

		if (strval($this->nu_parSisp->CurrentValue) <> "") {
			$sFilterWrk = "[nu_parSisp]" . ew_SearchString("=", $this->nu_parSisp->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_parSisp], [no_parSisp] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[parSisp]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_parSisp, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_parSisp] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_parSisp->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_parSisp->ViewValue = $this->nu_parSisp->CurrentValue;
			}
		} else {
			$this->nu_parSisp->ViewValue = NULL;
		}
		$this->nu_parSisp->ViewCustomAttributes = "";

		// nu_versao
		$this->nu_versao->ViewValue = $this->nu_versao->CurrentValue;
		$this->nu_versao->ViewCustomAttributes = "";

		// vr_parSisp
		$this->vr_parSisp->ViewValue = $this->vr_parSisp->CurrentValue;
		$this->vr_parSisp->ViewCustomAttributes = "";

		// ds_codigoSql
		$this->ds_codigoSql->ViewValue = $this->ds_codigoSql->CurrentValue;
		$this->ds_codigoSql->ViewCustomAttributes = "";

		// nu_usuarioResp
		if (strval($this->nu_usuarioResp->CurrentValue) <> "") {
			$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioResp->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_usuarioResp, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_usuarioResp->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_usuarioResp->ViewValue = $this->nu_usuarioResp->CurrentValue;
			}
		} else {
			$this->nu_usuarioResp->ViewValue = NULL;
		}
		$this->nu_usuarioResp->ViewCustomAttributes = "";

		// ds_versao
		$this->ds_versao->ViewValue = $this->ds_versao->CurrentValue;
		$this->ds_versao->ViewCustomAttributes = "";

		// dh_inclusao
		$this->dh_inclusao->ViewValue = $this->dh_inclusao->CurrentValue;
		$this->dh_inclusao->ViewValue = ew_FormatDateTime($this->dh_inclusao->ViewValue, 10);
		$this->dh_inclusao->ViewCustomAttributes = "";

		// nu_parSisp
		$this->nu_parSisp->LinkCustomAttributes = "";
		$this->nu_parSisp->HrefValue = "";
		$this->nu_parSisp->TooltipValue = "";

		// nu_versao
		$this->nu_versao->LinkCustomAttributes = "";
		$this->nu_versao->HrefValue = "";
		$this->nu_versao->TooltipValue = "";

		// vr_parSisp
		$this->vr_parSisp->LinkCustomAttributes = "";
		$this->vr_parSisp->HrefValue = "";
		$this->vr_parSisp->TooltipValue = "";

		// ds_codigoSql
		$this->ds_codigoSql->LinkCustomAttributes = "";
		$this->ds_codigoSql->HrefValue = "";
		$this->ds_codigoSql->TooltipValue = "";

		// nu_usuarioResp
		$this->nu_usuarioResp->LinkCustomAttributes = "";
		$this->nu_usuarioResp->HrefValue = "";
		$this->nu_usuarioResp->TooltipValue = "";

		// ds_versao
		$this->ds_versao->LinkCustomAttributes = "";
		$this->ds_versao->HrefValue = "";
		$this->ds_versao->TooltipValue = "";

		// dh_inclusao
		$this->dh_inclusao->LinkCustomAttributes = "";
		$this->dh_inclusao->HrefValue = "";
		$this->dh_inclusao->TooltipValue = "";

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
				if ($this->nu_parSisp->Exportable) $Doc->ExportCaption($this->nu_parSisp);
				if ($this->nu_versao->Exportable) $Doc->ExportCaption($this->nu_versao);
				if ($this->vr_parSisp->Exportable) $Doc->ExportCaption($this->vr_parSisp);
				if ($this->ds_codigoSql->Exportable) $Doc->ExportCaption($this->ds_codigoSql);
				if ($this->nu_usuarioResp->Exportable) $Doc->ExportCaption($this->nu_usuarioResp);
				if ($this->ds_versao->Exportable) $Doc->ExportCaption($this->ds_versao);
				if ($this->dh_inclusao->Exportable) $Doc->ExportCaption($this->dh_inclusao);
			} else {
				if ($this->nu_parSisp->Exportable) $Doc->ExportCaption($this->nu_parSisp);
				if ($this->nu_versao->Exportable) $Doc->ExportCaption($this->nu_versao);
				if ($this->vr_parSisp->Exportable) $Doc->ExportCaption($this->vr_parSisp);
				if ($this->nu_usuarioResp->Exportable) $Doc->ExportCaption($this->nu_usuarioResp);
				if ($this->ds_versao->Exportable) $Doc->ExportCaption($this->ds_versao);
				if ($this->dh_inclusao->Exportable) $Doc->ExportCaption($this->dh_inclusao);
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
					if ($this->nu_parSisp->Exportable) $Doc->ExportField($this->nu_parSisp);
					if ($this->nu_versao->Exportable) $Doc->ExportField($this->nu_versao);
					if ($this->vr_parSisp->Exportable) $Doc->ExportField($this->vr_parSisp);
					if ($this->ds_codigoSql->Exportable) $Doc->ExportField($this->ds_codigoSql);
					if ($this->nu_usuarioResp->Exportable) $Doc->ExportField($this->nu_usuarioResp);
					if ($this->ds_versao->Exportable) $Doc->ExportField($this->ds_versao);
					if ($this->dh_inclusao->Exportable) $Doc->ExportField($this->dh_inclusao);
				} else {
					if ($this->nu_parSisp->Exportable) $Doc->ExportField($this->nu_parSisp);
					if ($this->nu_versao->Exportable) $Doc->ExportField($this->nu_versao);
					if ($this->vr_parSisp->Exportable) $Doc->ExportField($this->vr_parSisp);
					if ($this->nu_usuarioResp->Exportable) $Doc->ExportField($this->nu_usuarioResp);
					if ($this->ds_versao->Exportable) $Doc->ExportField($this->ds_versao);
					if ($this->dh_inclusao->Exportable) $Doc->ExportField($this->dh_inclusao);
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

		$valor = ew_ExecuteScalar("select max(nu_versao) From parsisp_versao where nu_parSisp = (select nu_parSisp from parsisp WHERE no_parSisp = '" . $rsnew["nu_parSisp"] . "')");
		$rsnew["nu_versao"] = $valor + 1;                                                                                                                                           
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

		$valor = ew_ExecuteScalar("select max(nu_versao) From parsisp_versao where nu_parSisp = (select nu_parSisp from parsisp WHERE no_parSisp = '" . $this->nu_parSisp->ViewValue . "')");                                    
		$this->nu_versao->EditValue = "" . $valor + 1 . ""; 
	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
