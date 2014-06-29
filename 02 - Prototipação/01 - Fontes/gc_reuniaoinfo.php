<?php

// Global variable for table object
$gc_reuniao = NULL;

//
// Table class for gc_reuniao
//
class cgc_reuniao extends cTable {
	var $nu_reuniao;
	var $nu_grupoOuComite;
	var $ds_pauta;
	var $no_local;
	var $dt_reuniao;
	var $hh_inicio;
	var $hh_fim;
	var $ic_situacao;
	var $nu_usuario;
	var $ts_datahora;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'gc_reuniao';
		$this->TableName = 'gc_reuniao';
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

		// nu_reuniao
		$this->nu_reuniao = new cField('gc_reuniao', 'gc_reuniao', 'x_nu_reuniao', 'nu_reuniao', '[nu_reuniao]', 'CAST([nu_reuniao] AS NVARCHAR)', 3, -1, FALSE, '[nu_reuniao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_reuniao->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_reuniao'] = &$this->nu_reuniao;

		// nu_grupoOuComite
		$this->nu_grupoOuComite = new cField('gc_reuniao', 'gc_reuniao', 'x_nu_grupoOuComite', 'nu_grupoOuComite', '[nu_grupoOuComite]', 'CAST([nu_grupoOuComite] AS NVARCHAR)', 3, -1, FALSE, '[nu_grupoOuComite]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_grupoOuComite->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_grupoOuComite'] = &$this->nu_grupoOuComite;

		// ds_pauta
		$this->ds_pauta = new cField('gc_reuniao', 'gc_reuniao', 'x_ds_pauta', 'ds_pauta', '[ds_pauta]', '[ds_pauta]', 201, -1, FALSE, '[ds_pauta]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_pauta'] = &$this->ds_pauta;

		// no_local
		$this->no_local = new cField('gc_reuniao', 'gc_reuniao', 'x_no_local', 'no_local', '[no_local]', '[no_local]', 200, -1, FALSE, '[no_local]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_local'] = &$this->no_local;

		// dt_reuniao
		$this->dt_reuniao = new cField('gc_reuniao', 'gc_reuniao', 'x_dt_reuniao', 'dt_reuniao', '[dt_reuniao]', '(REPLACE(STR(DAY([dt_reuniao]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dt_reuniao]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dt_reuniao]),4,0))', 135, 7, FALSE, '[dt_reuniao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dt_reuniao->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dt_reuniao'] = &$this->dt_reuniao;

		// hh_inicio
		$this->hh_inicio = new cField('gc_reuniao', 'gc_reuniao', 'x_hh_inicio', 'hh_inicio', '[hh_inicio]', '(REPLACE(STR(DAY([hh_inicio]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([hh_inicio]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([hh_inicio]),4,0))', 135, 4, FALSE, '[hh_inicio]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['hh_inicio'] = &$this->hh_inicio;

		// hh_fim
		$this->hh_fim = new cField('gc_reuniao', 'gc_reuniao', 'x_hh_fim', 'hh_fim', '[hh_fim]', '(REPLACE(STR(DAY([hh_fim]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([hh_fim]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([hh_fim]),4,0))', 135, 4, FALSE, '[hh_fim]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['hh_fim'] = &$this->hh_fim;

		// ic_situacao
		$this->ic_situacao = new cField('gc_reuniao', 'gc_reuniao', 'x_ic_situacao', 'ic_situacao', '[ic_situacao]', '[ic_situacao]', 129, -1, FALSE, '[ic_situacao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_situacao'] = &$this->ic_situacao;

		// nu_usuario
		$this->nu_usuario = new cField('gc_reuniao', 'gc_reuniao', 'x_nu_usuario', 'nu_usuario', '[nu_usuario]', 'CAST([nu_usuario] AS NVARCHAR)', 3, -1, FALSE, '[nu_usuario]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_usuario->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_usuario'] = &$this->nu_usuario;

		// ts_datahora
		$this->ts_datahora = new cField('gc_reuniao', 'gc_reuniao', 'x_ts_datahora', 'ts_datahora', '[ts_datahora]', '(REPLACE(STR(DAY([ts_datahora]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([ts_datahora]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([ts_datahora]),4,0))', 135, 7, FALSE, '[ts_datahora]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->ts_datahora->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['ts_datahora'] = &$this->ts_datahora;
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
		return "[dbo].[gc_reuniao]";
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
	var $UpdateTable = "[dbo].[gc_reuniao]";

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
			if (array_key_exists('nu_reuniao', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_reuniao') . '=' . ew_QuotedValue($rs['nu_reuniao'], $this->nu_reuniao->FldDataType));
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
		return "[nu_reuniao] = @nu_reuniao@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nu_reuniao->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_reuniao@", ew_AdjustSql($this->nu_reuniao->CurrentValue), $sKeyFilter); // Replace key value
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
			return "gc_reuniaolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "gc_reuniaolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("gc_reuniaoview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("gc_reuniaoview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "gc_reuniaoadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("gc_reuniaoedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("gc_reuniaoadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("gc_reuniaodelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nu_reuniao->CurrentValue)) {
			$sUrl .= "nu_reuniao=" . urlencode($this->nu_reuniao->CurrentValue);
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
			$arKeys[] = @$_GET["nu_reuniao"]; // nu_reuniao

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
			$this->nu_reuniao->CurrentValue = $key;
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
		$this->nu_reuniao->setDbValue($rs->fields('nu_reuniao'));
		$this->nu_grupoOuComite->setDbValue($rs->fields('nu_grupoOuComite'));
		$this->ds_pauta->setDbValue($rs->fields('ds_pauta'));
		$this->no_local->setDbValue($rs->fields('no_local'));
		$this->dt_reuniao->setDbValue($rs->fields('dt_reuniao'));
		$this->hh_inicio->setDbValue($rs->fields('hh_inicio'));
		$this->hh_fim->setDbValue($rs->fields('hh_fim'));
		$this->ic_situacao->setDbValue($rs->fields('ic_situacao'));
		$this->nu_usuario->setDbValue($rs->fields('nu_usuario'));
		$this->ts_datahora->setDbValue($rs->fields('ts_datahora'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_reuniao
		// nu_grupoOuComite
		// ds_pauta
		// no_local
		// dt_reuniao
		// hh_inicio
		// hh_fim
		// ic_situacao
		// nu_usuario
		// ts_datahora
		// nu_reuniao

		$this->nu_reuniao->ViewValue = $this->nu_reuniao->CurrentValue;
		$this->nu_reuniao->ViewCustomAttributes = "";

		// nu_grupoOuComite
		if (strval($this->nu_grupoOuComite->CurrentValue) <> "") {
			$sFilterWrk = "[nu_gpComite]" . ew_SearchString("=", $this->nu_grupoOuComite->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_gpComite], [no_gpComite] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[gpcomite]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_grupoOuComite, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_grupoOuComite->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_grupoOuComite->ViewValue = $this->nu_grupoOuComite->CurrentValue;
			}
		} else {
			$this->nu_grupoOuComite->ViewValue = NULL;
		}
		$this->nu_grupoOuComite->ViewCustomAttributes = "";

		// ds_pauta
		$this->ds_pauta->ViewValue = $this->ds_pauta->CurrentValue;
		$this->ds_pauta->ViewCustomAttributes = "";

		// no_local
		$this->no_local->ViewValue = $this->no_local->CurrentValue;
		$this->no_local->ViewCustomAttributes = "";

		// dt_reuniao
		$this->dt_reuniao->ViewValue = $this->dt_reuniao->CurrentValue;
		$this->dt_reuniao->ViewValue = ew_FormatDateTime($this->dt_reuniao->ViewValue, 7);
		$this->dt_reuniao->ViewCustomAttributes = "";

		// hh_inicio
		$this->hh_inicio->ViewValue = $this->hh_inicio->CurrentValue;
		$this->hh_inicio->ViewValue = ew_FormatDateTime($this->hh_inicio->ViewValue, 4);
		$this->hh_inicio->ViewCustomAttributes = "";

		// hh_fim
		$this->hh_fim->ViewValue = $this->hh_fim->CurrentValue;
		$this->hh_fim->ViewValue = ew_FormatDateTime($this->hh_fim->ViewValue, 4);
		$this->hh_fim->ViewCustomAttributes = "";

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
				default:
					$this->ic_situacao->ViewValue = $this->ic_situacao->CurrentValue;
			}
		} else {
			$this->ic_situacao->ViewValue = NULL;
		}
		$this->ic_situacao->ViewCustomAttributes = "";

		// nu_usuario
		$this->nu_usuario->ViewValue = $this->nu_usuario->CurrentValue;
		$this->nu_usuario->ViewCustomAttributes = "";

		// ts_datahora
		$this->ts_datahora->ViewValue = $this->ts_datahora->CurrentValue;
		$this->ts_datahora->ViewValue = ew_FormatDateTime($this->ts_datahora->ViewValue, 7);
		$this->ts_datahora->ViewCustomAttributes = "";

		// nu_reuniao
		$this->nu_reuniao->LinkCustomAttributes = "";
		$this->nu_reuniao->HrefValue = "";
		$this->nu_reuniao->TooltipValue = "";

		// nu_grupoOuComite
		$this->nu_grupoOuComite->LinkCustomAttributes = "";
		$this->nu_grupoOuComite->HrefValue = "";
		$this->nu_grupoOuComite->TooltipValue = "";

		// ds_pauta
		$this->ds_pauta->LinkCustomAttributes = "";
		$this->ds_pauta->HrefValue = "";
		$this->ds_pauta->TooltipValue = "";

		// no_local
		$this->no_local->LinkCustomAttributes = "";
		$this->no_local->HrefValue = "";
		$this->no_local->TooltipValue = "";

		// dt_reuniao
		$this->dt_reuniao->LinkCustomAttributes = "";
		$this->dt_reuniao->HrefValue = "";
		$this->dt_reuniao->TooltipValue = "";

		// hh_inicio
		$this->hh_inicio->LinkCustomAttributes = "";
		$this->hh_inicio->HrefValue = "";
		$this->hh_inicio->TooltipValue = "";

		// hh_fim
		$this->hh_fim->LinkCustomAttributes = "";
		$this->hh_fim->HrefValue = "";
		$this->hh_fim->TooltipValue = "";

		// ic_situacao
		$this->ic_situacao->LinkCustomAttributes = "";
		$this->ic_situacao->HrefValue = "";
		$this->ic_situacao->TooltipValue = "";

		// nu_usuario
		$this->nu_usuario->LinkCustomAttributes = "";
		$this->nu_usuario->HrefValue = "";
		$this->nu_usuario->TooltipValue = "";

		// ts_datahora
		$this->ts_datahora->LinkCustomAttributes = "";
		$this->ts_datahora->HrefValue = "";
		$this->ts_datahora->TooltipValue = "";

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
				if ($this->nu_reuniao->Exportable) $Doc->ExportCaption($this->nu_reuniao);
				if ($this->nu_grupoOuComite->Exportable) $Doc->ExportCaption($this->nu_grupoOuComite);
				if ($this->ds_pauta->Exportable) $Doc->ExportCaption($this->ds_pauta);
				if ($this->no_local->Exportable) $Doc->ExportCaption($this->no_local);
				if ($this->dt_reuniao->Exportable) $Doc->ExportCaption($this->dt_reuniao);
				if ($this->hh_inicio->Exportable) $Doc->ExportCaption($this->hh_inicio);
				if ($this->hh_fim->Exportable) $Doc->ExportCaption($this->hh_fim);
				if ($this->ic_situacao->Exportable) $Doc->ExportCaption($this->ic_situacao);
				if ($this->nu_usuario->Exportable) $Doc->ExportCaption($this->nu_usuario);
				if ($this->ts_datahora->Exportable) $Doc->ExportCaption($this->ts_datahora);
			} else {
				if ($this->nu_reuniao->Exportable) $Doc->ExportCaption($this->nu_reuniao);
				if ($this->nu_grupoOuComite->Exportable) $Doc->ExportCaption($this->nu_grupoOuComite);
				if ($this->no_local->Exportable) $Doc->ExportCaption($this->no_local);
				if ($this->dt_reuniao->Exportable) $Doc->ExportCaption($this->dt_reuniao);
				if ($this->hh_inicio->Exportable) $Doc->ExportCaption($this->hh_inicio);
				if ($this->hh_fim->Exportable) $Doc->ExportCaption($this->hh_fim);
				if ($this->ic_situacao->Exportable) $Doc->ExportCaption($this->ic_situacao);
				if ($this->nu_usuario->Exportable) $Doc->ExportCaption($this->nu_usuario);
				if ($this->ts_datahora->Exportable) $Doc->ExportCaption($this->ts_datahora);
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
					if ($this->nu_reuniao->Exportable) $Doc->ExportField($this->nu_reuniao);
					if ($this->nu_grupoOuComite->Exportable) $Doc->ExportField($this->nu_grupoOuComite);
					if ($this->ds_pauta->Exportable) $Doc->ExportField($this->ds_pauta);
					if ($this->no_local->Exportable) $Doc->ExportField($this->no_local);
					if ($this->dt_reuniao->Exportable) $Doc->ExportField($this->dt_reuniao);
					if ($this->hh_inicio->Exportable) $Doc->ExportField($this->hh_inicio);
					if ($this->hh_fim->Exportable) $Doc->ExportField($this->hh_fim);
					if ($this->ic_situacao->Exportable) $Doc->ExportField($this->ic_situacao);
					if ($this->nu_usuario->Exportable) $Doc->ExportField($this->nu_usuario);
					if ($this->ts_datahora->Exportable) $Doc->ExportField($this->ts_datahora);
				} else {
					if ($this->nu_reuniao->Exportable) $Doc->ExportField($this->nu_reuniao);
					if ($this->nu_grupoOuComite->Exportable) $Doc->ExportField($this->nu_grupoOuComite);
					if ($this->no_local->Exportable) $Doc->ExportField($this->no_local);
					if ($this->dt_reuniao->Exportable) $Doc->ExportField($this->dt_reuniao);
					if ($this->hh_inicio->Exportable) $Doc->ExportField($this->hh_inicio);
					if ($this->hh_fim->Exportable) $Doc->ExportField($this->hh_fim);
					if ($this->ic_situacao->Exportable) $Doc->ExportField($this->ic_situacao);
					if ($this->nu_usuario->Exportable) $Doc->ExportField($this->nu_usuario);
					if ($this->ts_datahora->Exportable) $Doc->ExportField($this->ts_datahora);
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
