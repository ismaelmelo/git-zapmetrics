<?php

// Global variable for table object
$tpmetrica = NULL;

//
// Table class for tpmetrica
//
class ctpmetrica extends cTable {
	var $nu_tpMetrica;
	var $no_tpMetrica;
	var $ic_tpMetrica;
	var $ic_tpAplicacao;
	var $ds_helpTela;
	var $ic_ativo;
	var $ic_metodoEsforco;
	var $ic_metodoPrazo;
	var $ic_metodoCusto;
	var $ic_metodoRecursos;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'tpmetrica';
		$this->TableName = 'tpmetrica';
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

		// nu_tpMetrica
		$this->nu_tpMetrica = new cField('tpmetrica', 'tpmetrica', 'x_nu_tpMetrica', 'nu_tpMetrica', '[nu_tpMetrica]', 'CAST([nu_tpMetrica] AS NVARCHAR)', 3, -1, FALSE, '[nu_tpMetrica]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_tpMetrica->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_tpMetrica'] = &$this->nu_tpMetrica;

		// no_tpMetrica
		$this->no_tpMetrica = new cField('tpmetrica', 'tpmetrica', 'x_no_tpMetrica', 'no_tpMetrica', '[no_tpMetrica]', '[no_tpMetrica]', 200, -1, FALSE, '[no_tpMetrica]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_tpMetrica'] = &$this->no_tpMetrica;

		// ic_tpMetrica
		$this->ic_tpMetrica = new cField('tpmetrica', 'tpmetrica', 'x_ic_tpMetrica', 'ic_tpMetrica', '[ic_tpMetrica]', '[ic_tpMetrica]', 129, -1, FALSE, '[ic_tpMetrica]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_tpMetrica'] = &$this->ic_tpMetrica;

		// ic_tpAplicacao
		$this->ic_tpAplicacao = new cField('tpmetrica', 'tpmetrica', 'x_ic_tpAplicacao', 'ic_tpAplicacao', '[ic_tpAplicacao]', '[ic_tpAplicacao]', 200, -1, FALSE, '[ic_tpAplicacao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_tpAplicacao'] = &$this->ic_tpAplicacao;

		// ds_helpTela
		$this->ds_helpTela = new cField('tpmetrica', 'tpmetrica', 'x_ds_helpTela', 'ds_helpTela', '[ds_helpTela]', '[ds_helpTela]', 201, -1, FALSE, '[ds_helpTela]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_helpTela'] = &$this->ds_helpTela;

		// ic_ativo
		$this->ic_ativo = new cField('tpmetrica', 'tpmetrica', 'x_ic_ativo', 'ic_ativo', '[ic_ativo]', '[ic_ativo]', 129, -1, FALSE, '[ic_ativo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_ativo'] = &$this->ic_ativo;

		// ic_metodoEsforco
		$this->ic_metodoEsforco = new cField('tpmetrica', 'tpmetrica', 'x_ic_metodoEsforco', 'ic_metodoEsforco', '[ic_metodoEsforco]', '[ic_metodoEsforco]', 129, -1, FALSE, '[ic_metodoEsforco]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_metodoEsforco'] = &$this->ic_metodoEsforco;

		// ic_metodoPrazo
		$this->ic_metodoPrazo = new cField('tpmetrica', 'tpmetrica', 'x_ic_metodoPrazo', 'ic_metodoPrazo', '[ic_metodoPrazo]', '[ic_metodoPrazo]', 129, -1, FALSE, '[ic_metodoPrazo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_metodoPrazo'] = &$this->ic_metodoPrazo;

		// ic_metodoCusto
		$this->ic_metodoCusto = new cField('tpmetrica', 'tpmetrica', 'x_ic_metodoCusto', 'ic_metodoCusto', '[ic_metodoCusto]', '[ic_metodoCusto]', 129, -1, FALSE, '[ic_metodoCusto]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_metodoCusto'] = &$this->ic_metodoCusto;

		// ic_metodoRecursos
		$this->ic_metodoRecursos = new cField('tpmetrica', 'tpmetrica', 'x_ic_metodoRecursos', 'ic_metodoRecursos', '[ic_metodoRecursos]', '[ic_metodoRecursos]', 129, -1, FALSE, '[ic_metodoRecursos]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_metodoRecursos'] = &$this->ic_metodoRecursos;
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
		if ($this->getCurrentDetailTable() == "tpcontagem") {
			$sDetailUrl = $GLOBALS["tpcontagem"]->GetListUrl() . "?showmaster=" . $this->TableVar;
			$sDetailUrl .= "&nu_tpMetrica=" . $this->nu_tpMetrica->CurrentValue;
		}
		if ($sDetailUrl == "") {
			$sDetailUrl = "tpmetricalist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	function SqlFrom() { // From
		return "[dbo].[tpmetrica]";
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
		return "[no_tpMetrica] ASC";
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
	var $UpdateTable = "[dbo].[tpmetrica]";

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
			if (array_key_exists('nu_tpMetrica', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_tpMetrica') . '=' . ew_QuotedValue($rs['nu_tpMetrica'], $this->nu_tpMetrica->FldDataType));
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
		return "[nu_tpMetrica] = @nu_tpMetrica@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nu_tpMetrica->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_tpMetrica@", ew_AdjustSql($this->nu_tpMetrica->CurrentValue), $sKeyFilter); // Replace key value
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
			return "tpmetricalist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "tpmetricalist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("tpmetricaview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("tpmetricaview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "tpmetricaadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("tpmetricaedit.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("tpmetricaedit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("tpmetricaadd.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("tpmetricaadd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("tpmetricadelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nu_tpMetrica->CurrentValue)) {
			$sUrl .= "nu_tpMetrica=" . urlencode($this->nu_tpMetrica->CurrentValue);
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
			$arKeys[] = @$_GET["nu_tpMetrica"]; // nu_tpMetrica

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
			$this->nu_tpMetrica->CurrentValue = $key;
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
		$this->nu_tpMetrica->setDbValue($rs->fields('nu_tpMetrica'));
		$this->no_tpMetrica->setDbValue($rs->fields('no_tpMetrica'));
		$this->ic_tpMetrica->setDbValue($rs->fields('ic_tpMetrica'));
		$this->ic_tpAplicacao->setDbValue($rs->fields('ic_tpAplicacao'));
		$this->ds_helpTela->setDbValue($rs->fields('ds_helpTela'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
		$this->ic_metodoEsforco->setDbValue($rs->fields('ic_metodoEsforco'));
		$this->ic_metodoPrazo->setDbValue($rs->fields('ic_metodoPrazo'));
		$this->ic_metodoCusto->setDbValue($rs->fields('ic_metodoCusto'));
		$this->ic_metodoRecursos->setDbValue($rs->fields('ic_metodoRecursos'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_tpMetrica
		// no_tpMetrica
		// ic_tpMetrica
		// ic_tpAplicacao
		// ds_helpTela
		// ic_ativo
		// ic_metodoEsforco
		// ic_metodoPrazo
		// ic_metodoCusto
		// ic_metodoRecursos
		// nu_tpMetrica

		$this->nu_tpMetrica->ViewValue = $this->nu_tpMetrica->CurrentValue;
		$this->nu_tpMetrica->ViewCustomAttributes = "";

		// no_tpMetrica
		$this->no_tpMetrica->ViewValue = $this->no_tpMetrica->CurrentValue;
		$this->no_tpMetrica->ViewCustomAttributes = "";

		// ic_tpMetrica
		if (strval($this->ic_tpMetrica->CurrentValue) <> "") {
			switch ($this->ic_tpMetrica->CurrentValue) {
				case $this->ic_tpMetrica->FldTagValue(1):
					$this->ic_tpMetrica->ViewValue = $this->ic_tpMetrica->FldTagCaption(1) <> "" ? $this->ic_tpMetrica->FldTagCaption(1) : $this->ic_tpMetrica->CurrentValue;
					break;
				case $this->ic_tpMetrica->FldTagValue(2):
					$this->ic_tpMetrica->ViewValue = $this->ic_tpMetrica->FldTagCaption(2) <> "" ? $this->ic_tpMetrica->FldTagCaption(2) : $this->ic_tpMetrica->CurrentValue;
					break;
				case $this->ic_tpMetrica->FldTagValue(3):
					$this->ic_tpMetrica->ViewValue = $this->ic_tpMetrica->FldTagCaption(3) <> "" ? $this->ic_tpMetrica->FldTagCaption(3) : $this->ic_tpMetrica->CurrentValue;
					break;
				default:
					$this->ic_tpMetrica->ViewValue = $this->ic_tpMetrica->CurrentValue;
			}
		} else {
			$this->ic_tpMetrica->ViewValue = NULL;
		}
		$this->ic_tpMetrica->ViewCustomAttributes = "";

		// ic_tpAplicacao
		if (strval($this->ic_tpAplicacao->CurrentValue) <> "") {
			$this->ic_tpAplicacao->ViewValue = "";
			$arwrk = explode(",", strval($this->ic_tpAplicacao->CurrentValue));
			$cnt = count($arwrk);
			for ($ari = 0; $ari < $cnt; $ari++) {
				switch (trim($arwrk[$ari])) {
					case $this->ic_tpAplicacao->FldTagValue(1):
						$this->ic_tpAplicacao->ViewValue .= $this->ic_tpAplicacao->FldTagCaption(1) <> "" ? $this->ic_tpAplicacao->FldTagCaption(1) : trim($arwrk[$ari]);
						break;
					case $this->ic_tpAplicacao->FldTagValue(2):
						$this->ic_tpAplicacao->ViewValue .= $this->ic_tpAplicacao->FldTagCaption(2) <> "" ? $this->ic_tpAplicacao->FldTagCaption(2) : trim($arwrk[$ari]);
						break;
					case $this->ic_tpAplicacao->FldTagValue(3):
						$this->ic_tpAplicacao->ViewValue .= $this->ic_tpAplicacao->FldTagCaption(3) <> "" ? $this->ic_tpAplicacao->FldTagCaption(3) : trim($arwrk[$ari]);
						break;
					default:
						$this->ic_tpAplicacao->ViewValue .= trim($arwrk[$ari]);
				}
				if ($ari < $cnt-1) $this->ic_tpAplicacao->ViewValue .= ew_ViewOptionSeparator($ari);
			}
		} else {
			$this->ic_tpAplicacao->ViewValue = NULL;
		}
		$this->ic_tpAplicacao->ViewCustomAttributes = "";

		// ds_helpTela
		$this->ds_helpTela->ViewValue = $this->ds_helpTela->CurrentValue;
		$this->ds_helpTela->ViewCustomAttributes = "";

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

		// ic_metodoEsforco
		if (strval($this->ic_metodoEsforco->CurrentValue) <> "") {
			switch ($this->ic_metodoEsforco->CurrentValue) {
				case $this->ic_metodoEsforco->FldTagValue(1):
					$this->ic_metodoEsforco->ViewValue = $this->ic_metodoEsforco->FldTagCaption(1) <> "" ? $this->ic_metodoEsforco->FldTagCaption(1) : $this->ic_metodoEsforco->CurrentValue;
					break;
				case $this->ic_metodoEsforco->FldTagValue(2):
					$this->ic_metodoEsforco->ViewValue = $this->ic_metodoEsforco->FldTagCaption(2) <> "" ? $this->ic_metodoEsforco->FldTagCaption(2) : $this->ic_metodoEsforco->CurrentValue;
					break;
				default:
					$this->ic_metodoEsforco->ViewValue = $this->ic_metodoEsforco->CurrentValue;
			}
		} else {
			$this->ic_metodoEsforco->ViewValue = NULL;
		}
		$this->ic_metodoEsforco->ViewCustomAttributes = "";

		// ic_metodoPrazo
		if (strval($this->ic_metodoPrazo->CurrentValue) <> "") {
			switch ($this->ic_metodoPrazo->CurrentValue) {
				case $this->ic_metodoPrazo->FldTagValue(1):
					$this->ic_metodoPrazo->ViewValue = $this->ic_metodoPrazo->FldTagCaption(1) <> "" ? $this->ic_metodoPrazo->FldTagCaption(1) : $this->ic_metodoPrazo->CurrentValue;
					break;
				case $this->ic_metodoPrazo->FldTagValue(2):
					$this->ic_metodoPrazo->ViewValue = $this->ic_metodoPrazo->FldTagCaption(2) <> "" ? $this->ic_metodoPrazo->FldTagCaption(2) : $this->ic_metodoPrazo->CurrentValue;
					break;
				case $this->ic_metodoPrazo->FldTagValue(3):
					$this->ic_metodoPrazo->ViewValue = $this->ic_metodoPrazo->FldTagCaption(3) <> "" ? $this->ic_metodoPrazo->FldTagCaption(3) : $this->ic_metodoPrazo->CurrentValue;
					break;
				case $this->ic_metodoPrazo->FldTagValue(4):
					$this->ic_metodoPrazo->ViewValue = $this->ic_metodoPrazo->FldTagCaption(4) <> "" ? $this->ic_metodoPrazo->FldTagCaption(4) : $this->ic_metodoPrazo->CurrentValue;
					break;
				default:
					$this->ic_metodoPrazo->ViewValue = $this->ic_metodoPrazo->CurrentValue;
			}
		} else {
			$this->ic_metodoPrazo->ViewValue = NULL;
		}
		$this->ic_metodoPrazo->ViewCustomAttributes = "";

		// ic_metodoCusto
		if (strval($this->ic_metodoCusto->CurrentValue) <> "") {
			switch ($this->ic_metodoCusto->CurrentValue) {
				case $this->ic_metodoCusto->FldTagValue(1):
					$this->ic_metodoCusto->ViewValue = $this->ic_metodoCusto->FldTagCaption(1) <> "" ? $this->ic_metodoCusto->FldTagCaption(1) : $this->ic_metodoCusto->CurrentValue;
					break;
				case $this->ic_metodoCusto->FldTagValue(2):
					$this->ic_metodoCusto->ViewValue = $this->ic_metodoCusto->FldTagCaption(2) <> "" ? $this->ic_metodoCusto->FldTagCaption(2) : $this->ic_metodoCusto->CurrentValue;
					break;
				default:
					$this->ic_metodoCusto->ViewValue = $this->ic_metodoCusto->CurrentValue;
			}
		} else {
			$this->ic_metodoCusto->ViewValue = NULL;
		}
		$this->ic_metodoCusto->ViewCustomAttributes = "";

		// ic_metodoRecursos
		if (strval($this->ic_metodoRecursos->CurrentValue) <> "") {
			switch ($this->ic_metodoRecursos->CurrentValue) {
				case $this->ic_metodoRecursos->FldTagValue(1):
					$this->ic_metodoRecursos->ViewValue = $this->ic_metodoRecursos->FldTagCaption(1) <> "" ? $this->ic_metodoRecursos->FldTagCaption(1) : $this->ic_metodoRecursos->CurrentValue;
					break;
				case $this->ic_metodoRecursos->FldTagValue(2):
					$this->ic_metodoRecursos->ViewValue = $this->ic_metodoRecursos->FldTagCaption(2) <> "" ? $this->ic_metodoRecursos->FldTagCaption(2) : $this->ic_metodoRecursos->CurrentValue;
					break;
				default:
					$this->ic_metodoRecursos->ViewValue = $this->ic_metodoRecursos->CurrentValue;
			}
		} else {
			$this->ic_metodoRecursos->ViewValue = NULL;
		}
		$this->ic_metodoRecursos->ViewCustomAttributes = "";

		// nu_tpMetrica
		$this->nu_tpMetrica->LinkCustomAttributes = "";
		$this->nu_tpMetrica->HrefValue = "";
		$this->nu_tpMetrica->TooltipValue = "";

		// no_tpMetrica
		$this->no_tpMetrica->LinkCustomAttributes = "";
		$this->no_tpMetrica->HrefValue = "";
		$this->no_tpMetrica->TooltipValue = "";

		// ic_tpMetrica
		$this->ic_tpMetrica->LinkCustomAttributes = "";
		$this->ic_tpMetrica->HrefValue = "";
		$this->ic_tpMetrica->TooltipValue = "";

		// ic_tpAplicacao
		$this->ic_tpAplicacao->LinkCustomAttributes = "";
		$this->ic_tpAplicacao->HrefValue = "";
		$this->ic_tpAplicacao->TooltipValue = "";

		// ds_helpTela
		$this->ds_helpTela->LinkCustomAttributes = "";
		$this->ds_helpTela->HrefValue = "";
		$this->ds_helpTela->TooltipValue = "";

		// ic_ativo
		$this->ic_ativo->LinkCustomAttributes = "";
		$this->ic_ativo->HrefValue = "";
		$this->ic_ativo->TooltipValue = "";

		// ic_metodoEsforco
		$this->ic_metodoEsforco->LinkCustomAttributes = "";
		$this->ic_metodoEsforco->HrefValue = "";
		$this->ic_metodoEsforco->TooltipValue = "";

		// ic_metodoPrazo
		$this->ic_metodoPrazo->LinkCustomAttributes = "";
		$this->ic_metodoPrazo->HrefValue = "";
		$this->ic_metodoPrazo->TooltipValue = "";

		// ic_metodoCusto
		$this->ic_metodoCusto->LinkCustomAttributes = "";
		$this->ic_metodoCusto->HrefValue = "";
		$this->ic_metodoCusto->TooltipValue = "";

		// ic_metodoRecursos
		$this->ic_metodoRecursos->LinkCustomAttributes = "";
		$this->ic_metodoRecursos->HrefValue = "";
		$this->ic_metodoRecursos->TooltipValue = "";

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
				if ($this->no_tpMetrica->Exportable) $Doc->ExportCaption($this->no_tpMetrica);
				if ($this->ic_tpMetrica->Exportable) $Doc->ExportCaption($this->ic_tpMetrica);
				if ($this->ic_tpAplicacao->Exportable) $Doc->ExportCaption($this->ic_tpAplicacao);
				if ($this->ds_helpTela->Exportable) $Doc->ExportCaption($this->ds_helpTela);
				if ($this->ic_ativo->Exportable) $Doc->ExportCaption($this->ic_ativo);
				if ($this->ic_metodoEsforco->Exportable) $Doc->ExportCaption($this->ic_metodoEsforco);
				if ($this->ic_metodoPrazo->Exportable) $Doc->ExportCaption($this->ic_metodoPrazo);
				if ($this->ic_metodoCusto->Exportable) $Doc->ExportCaption($this->ic_metodoCusto);
				if ($this->ic_metodoRecursos->Exportable) $Doc->ExportCaption($this->ic_metodoRecursos);
			} else {
				if ($this->nu_tpMetrica->Exportable) $Doc->ExportCaption($this->nu_tpMetrica);
				if ($this->no_tpMetrica->Exportable) $Doc->ExportCaption($this->no_tpMetrica);
				if ($this->ic_tpMetrica->Exportable) $Doc->ExportCaption($this->ic_tpMetrica);
				if ($this->ic_tpAplicacao->Exportable) $Doc->ExportCaption($this->ic_tpAplicacao);
				if ($this->ic_ativo->Exportable) $Doc->ExportCaption($this->ic_ativo);
				if ($this->ic_metodoEsforco->Exportable) $Doc->ExportCaption($this->ic_metodoEsforco);
				if ($this->ic_metodoPrazo->Exportable) $Doc->ExportCaption($this->ic_metodoPrazo);
				if ($this->ic_metodoCusto->Exportable) $Doc->ExportCaption($this->ic_metodoCusto);
				if ($this->ic_metodoRecursos->Exportable) $Doc->ExportCaption($this->ic_metodoRecursos);
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
					if ($this->no_tpMetrica->Exportable) $Doc->ExportField($this->no_tpMetrica);
					if ($this->ic_tpMetrica->Exportable) $Doc->ExportField($this->ic_tpMetrica);
					if ($this->ic_tpAplicacao->Exportable) $Doc->ExportField($this->ic_tpAplicacao);
					if ($this->ds_helpTela->Exportable) $Doc->ExportField($this->ds_helpTela);
					if ($this->ic_ativo->Exportable) $Doc->ExportField($this->ic_ativo);
					if ($this->ic_metodoEsforco->Exportable) $Doc->ExportField($this->ic_metodoEsforco);
					if ($this->ic_metodoPrazo->Exportable) $Doc->ExportField($this->ic_metodoPrazo);
					if ($this->ic_metodoCusto->Exportable) $Doc->ExportField($this->ic_metodoCusto);
					if ($this->ic_metodoRecursos->Exportable) $Doc->ExportField($this->ic_metodoRecursos);
				} else {
					if ($this->nu_tpMetrica->Exportable) $Doc->ExportField($this->nu_tpMetrica);
					if ($this->no_tpMetrica->Exportable) $Doc->ExportField($this->no_tpMetrica);
					if ($this->ic_tpMetrica->Exportable) $Doc->ExportField($this->ic_tpMetrica);
					if ($this->ic_tpAplicacao->Exportable) $Doc->ExportField($this->ic_tpAplicacao);
					if ($this->ic_ativo->Exportable) $Doc->ExportField($this->ic_ativo);
					if ($this->ic_metodoEsforco->Exportable) $Doc->ExportField($this->ic_metodoEsforco);
					if ($this->ic_metodoPrazo->Exportable) $Doc->ExportField($this->ic_metodoPrazo);
					if ($this->ic_metodoCusto->Exportable) $Doc->ExportField($this->ic_metodoCusto);
					if ($this->ic_metodoRecursos->Exportable) $Doc->ExportField($this->ic_metodoRecursos);
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
