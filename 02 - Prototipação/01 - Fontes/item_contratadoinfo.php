<?php

// Global variable for table object
$item_contratado = NULL;

//
// Table class for item_contratado
//
class citem_contratado extends cTable {
	var $nu_itemContratado;
	var $nu_contrato;
	var $nu_itemOc;
	var $no_itemContratado;
	var $nu_unidade;
	var $qt_maximo;
	var $vr_maximo;
	var $dt_inclusao;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'item_contratado';
		$this->TableName = 'item_contratado';
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

		// nu_itemContratado
		$this->nu_itemContratado = new cField('item_contratado', 'item_contratado', 'x_nu_itemContratado', 'nu_itemContratado', '[nu_itemContratado]', 'CAST([nu_itemContratado] AS NVARCHAR)', 3, -1, FALSE, '[nu_itemContratado]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_itemContratado->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_itemContratado'] = &$this->nu_itemContratado;

		// nu_contrato
		$this->nu_contrato = new cField('item_contratado', 'item_contratado', 'x_nu_contrato', 'nu_contrato', '[nu_contrato]', 'CAST([nu_contrato] AS NVARCHAR)', 3, -1, FALSE, '[nu_contrato]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_contrato->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_contrato'] = &$this->nu_contrato;

		// nu_itemOc
		$this->nu_itemOc = new cField('item_contratado', 'item_contratado', 'x_nu_itemOc', 'nu_itemOc', '[nu_itemOc]', 'CAST([nu_itemOc] AS NVARCHAR)', 3, -1, FALSE, '[nu_itemOc]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_itemOc->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_itemOc'] = &$this->nu_itemOc;

		// no_itemContratado
		$this->no_itemContratado = new cField('item_contratado', 'item_contratado', 'x_no_itemContratado', 'no_itemContratado', '[no_itemContratado]', '[no_itemContratado]', 200, -1, FALSE, '[no_itemContratado]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_itemContratado'] = &$this->no_itemContratado;

		// nu_unidade
		$this->nu_unidade = new cField('item_contratado', 'item_contratado', 'x_nu_unidade', 'nu_unidade', '[nu_unidade]', 'CAST([nu_unidade] AS NVARCHAR)', 3, -1, FALSE, '[nu_unidade]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_unidade->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_unidade'] = &$this->nu_unidade;

		// qt_maximo
		$this->qt_maximo = new cField('item_contratado', 'item_contratado', 'x_qt_maximo', 'qt_maximo', '[qt_maximo]', 'CAST([qt_maximo] AS NVARCHAR)', 131, -1, FALSE, '[qt_maximo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->qt_maximo->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['qt_maximo'] = &$this->qt_maximo;

		// vr_maximo
		$this->vr_maximo = new cField('item_contratado', 'item_contratado', 'x_vr_maximo', 'vr_maximo', '[vr_maximo]', 'CAST([vr_maximo] AS NVARCHAR)', 131, -1, FALSE, '[vr_maximo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->vr_maximo->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['vr_maximo'] = &$this->vr_maximo;

		// dt_inclusao
		$this->dt_inclusao = new cField('item_contratado', 'item_contratado', 'x_dt_inclusao', 'dt_inclusao', '[dt_inclusao]', '(REPLACE(STR(DAY([dt_inclusao]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dt_inclusao]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dt_inclusao]),4,0))', 135, 7, FALSE, '[dt_inclusao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dt_inclusao->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dt_inclusao'] = &$this->dt_inclusao;
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
		if ($this->getCurrentMasterTable() == "contrato") {
			if ($this->nu_contrato->getSessionValue() <> "")
				$sMasterFilter .= "[nu_contrato]=" . ew_QuotedValue($this->nu_contrato->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "contrato") {
			if ($this->nu_contrato->getSessionValue() <> "")
				$sDetailFilter .= "[nu_contrato]=" . ew_QuotedValue($this->nu_contrato->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_contrato() {
		return "[nu_contrato]=@nu_contrato@";
	}

	// Detail filter
	function SqlDetailFilter_contrato() {
		return "[nu_contrato]=@nu_contrato@";
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
		if ($this->getCurrentDetailTable() == "Item_contratado_valor") {
			$sDetailUrl = $GLOBALS["Item_contratado_valor"]->GetListUrl() . "?showmaster=" . $this->TableVar;
			$sDetailUrl .= "&nu_itemContratado=" . $this->nu_itemContratado->CurrentValue;
		}
		if ($sDetailUrl == "") {
			$sDetailUrl = "item_contratadolist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	function SqlFrom() { // From
		return "[dbo].[item_contratado]";
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
	var $UpdateTable = "[dbo].[item_contratado]";

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

		// Cascade update detail field 'nu_itemContratado'
		if (!is_null($rsold) && (isset($rs['nu_itemContratado']) && $rsold['nu_itemContratado'] <> $rs['nu_itemContratado'])) {
			if (!isset($GLOBALS["Item_contratado_valor"])) $GLOBALS["Item_contratado_valor"] = new cItem_contratado_valor();
			$rscascade = array();
			$rscascade['nu_itemContratado'] = $rs['nu_itemContratado']; 
			$GLOBALS["Item_contratado_valor"]->Update($rscascade, "[nu_itemContratado] = " . ew_QuotedValue($rsold['nu_itemContratado'], EW_DATATYPE_NUMBER));
		}
		return $conn->Execute($this->UpdateSQL($rs, $where));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "") {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if ($rs) {
			if (array_key_exists('nu_itemContratado', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_itemContratado') . '=' . ew_QuotedValue($rs['nu_itemContratado'], $this->nu_itemContratado->FldDataType));
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

		// Cascade delete detail table 'Item_contratado_valor'
		if (!isset($GLOBALS["Item_contratado_valor"])) $GLOBALS["Item_contratado_valor"] = new cItem_contratado_valor();
		$rscascade = array();
		$GLOBALS["Item_contratado_valor"]->Delete($rscascade, "[nu_itemContratado] = " . ew_QuotedValue($rs['nu_itemContratado'], EW_DATATYPE_NUMBER));
		return $conn->Execute($this->DeleteSQL($rs, $where));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "[nu_itemContratado] = @nu_itemContratado@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nu_itemContratado->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_itemContratado@", ew_AdjustSql($this->nu_itemContratado->CurrentValue), $sKeyFilter); // Replace key value
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
			return "item_contratadolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "item_contratadolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("item_contratadoview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("item_contratadoview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "item_contratadoadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("item_contratadoedit.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("item_contratadoedit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("item_contratadoadd.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("item_contratadoadd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("item_contratadodelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nu_itemContratado->CurrentValue)) {
			$sUrl .= "nu_itemContratado=" . urlencode($this->nu_itemContratado->CurrentValue);
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
			$arKeys[] = @$_GET["nu_itemContratado"]; // nu_itemContratado

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
			$this->nu_itemContratado->CurrentValue = $key;
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
		$this->nu_itemContratado->setDbValue($rs->fields('nu_itemContratado'));
		$this->nu_contrato->setDbValue($rs->fields('nu_contrato'));
		$this->nu_itemOc->setDbValue($rs->fields('nu_itemOc'));
		$this->no_itemContratado->setDbValue($rs->fields('no_itemContratado'));
		$this->nu_unidade->setDbValue($rs->fields('nu_unidade'));
		$this->qt_maximo->setDbValue($rs->fields('qt_maximo'));
		$this->vr_maximo->setDbValue($rs->fields('vr_maximo'));
		$this->dt_inclusao->setDbValue($rs->fields('dt_inclusao'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_itemContratado

		$this->nu_itemContratado->CellCssStyle = "white-space: nowrap;";

		// nu_contrato
		// nu_itemOc
		// no_itemContratado
		// nu_unidade
		// qt_maximo
		// vr_maximo
		// dt_inclusao
		// nu_itemContratado

		$this->nu_itemContratado->ViewValue = $this->nu_itemContratado->CurrentValue;
		$this->nu_itemContratado->ViewCustomAttributes = "";

		// nu_contrato
		if (strval($this->nu_contrato->CurrentValue) <> "") {
			$sFilterWrk = "[nu_contrato]" . ew_SearchString("=", $this->nu_contrato->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_contrato], [no_contrato] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[contrato]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_contrato, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_contrato] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_contrato->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_contrato->ViewValue = $this->nu_contrato->CurrentValue;
			}
		} else {
			$this->nu_contrato->ViewValue = NULL;
		}
		$this->nu_contrato->ViewCustomAttributes = "";

		// nu_itemOc
		if (strval($this->nu_itemOc->CurrentValue) <> "") {
			$sFilterWrk = "[nu_itemOc]" . ew_SearchString("=", $this->nu_itemOc->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_itemOc], [no_itemOc] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[itemoc]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_itemOc, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_itemOc] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_itemOc->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_itemOc->ViewValue = $this->nu_itemOc->CurrentValue;
			}
		} else {
			$this->nu_itemOc->ViewValue = NULL;
		}
		$this->nu_itemOc->ViewCustomAttributes = "";

		// no_itemContratado
		$this->no_itemContratado->ViewValue = $this->no_itemContratado->CurrentValue;
		$this->no_itemContratado->ViewCustomAttributes = "";

		// nu_unidade
		if (strval($this->nu_unidade->CurrentValue) <> "") {
			$sFilterWrk = "[nu_unidade]" . ew_SearchString("=", $this->nu_unidade->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_unidade], [no_unidade] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[unidade]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_unidade, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_unidade] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_unidade->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_unidade->ViewValue = $this->nu_unidade->CurrentValue;
			}
		} else {
			$this->nu_unidade->ViewValue = NULL;
		}
		$this->nu_unidade->ViewCustomAttributes = "";

		// qt_maximo
		$this->qt_maximo->ViewValue = $this->qt_maximo->CurrentValue;
		$this->qt_maximo->ViewCustomAttributes = "";

		// vr_maximo
		$this->vr_maximo->ViewValue = $this->vr_maximo->CurrentValue;
		$this->vr_maximo->ViewValue = ew_FormatCurrency($this->vr_maximo->ViewValue, 2, -2, -2, -2);
		$this->vr_maximo->ViewCustomAttributes = "";

		// dt_inclusao
		$this->dt_inclusao->ViewValue = $this->dt_inclusao->CurrentValue;
		$this->dt_inclusao->ViewValue = ew_FormatDateTime($this->dt_inclusao->ViewValue, 7);
		$this->dt_inclusao->ViewCustomAttributes = "";

		// nu_itemContratado
		$this->nu_itemContratado->LinkCustomAttributes = "";
		$this->nu_itemContratado->HrefValue = "";
		$this->nu_itemContratado->TooltipValue = "";

		// nu_contrato
		$this->nu_contrato->LinkCustomAttributes = "";
		$this->nu_contrato->HrefValue = "";
		$this->nu_contrato->TooltipValue = "";

		// nu_itemOc
		$this->nu_itemOc->LinkCustomAttributes = "";
		$this->nu_itemOc->HrefValue = "";
		$this->nu_itemOc->TooltipValue = "";

		// no_itemContratado
		$this->no_itemContratado->LinkCustomAttributes = "";
		$this->no_itemContratado->HrefValue = "";
		$this->no_itemContratado->TooltipValue = "";

		// nu_unidade
		$this->nu_unidade->LinkCustomAttributes = "";
		$this->nu_unidade->HrefValue = "";
		$this->nu_unidade->TooltipValue = "";

		// qt_maximo
		$this->qt_maximo->LinkCustomAttributes = "";
		$this->qt_maximo->HrefValue = "";
		$this->qt_maximo->TooltipValue = "";

		// vr_maximo
		$this->vr_maximo->LinkCustomAttributes = "";
		$this->vr_maximo->HrefValue = "";
		$this->vr_maximo->TooltipValue = "";

		// dt_inclusao
		$this->dt_inclusao->LinkCustomAttributes = "";
		$this->dt_inclusao->HrefValue = "";
		$this->dt_inclusao->TooltipValue = "";

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
				if ($this->nu_contrato->Exportable) $Doc->ExportCaption($this->nu_contrato);
				if ($this->nu_itemOc->Exportable) $Doc->ExportCaption($this->nu_itemOc);
				if ($this->no_itemContratado->Exportable) $Doc->ExportCaption($this->no_itemContratado);
				if ($this->nu_unidade->Exportable) $Doc->ExportCaption($this->nu_unidade);
				if ($this->qt_maximo->Exportable) $Doc->ExportCaption($this->qt_maximo);
				if ($this->vr_maximo->Exportable) $Doc->ExportCaption($this->vr_maximo);
				if ($this->dt_inclusao->Exportable) $Doc->ExportCaption($this->dt_inclusao);
			} else {
				if ($this->nu_contrato->Exportable) $Doc->ExportCaption($this->nu_contrato);
				if ($this->nu_itemOc->Exportable) $Doc->ExportCaption($this->nu_itemOc);
				if ($this->no_itemContratado->Exportable) $Doc->ExportCaption($this->no_itemContratado);
				if ($this->nu_unidade->Exportable) $Doc->ExportCaption($this->nu_unidade);
				if ($this->qt_maximo->Exportable) $Doc->ExportCaption($this->qt_maximo);
				if ($this->vr_maximo->Exportable) $Doc->ExportCaption($this->vr_maximo);
				if ($this->dt_inclusao->Exportable) $Doc->ExportCaption($this->dt_inclusao);
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
					if ($this->nu_contrato->Exportable) $Doc->ExportField($this->nu_contrato);
					if ($this->nu_itemOc->Exportable) $Doc->ExportField($this->nu_itemOc);
					if ($this->no_itemContratado->Exportable) $Doc->ExportField($this->no_itemContratado);
					if ($this->nu_unidade->Exportable) $Doc->ExportField($this->nu_unidade);
					if ($this->qt_maximo->Exportable) $Doc->ExportField($this->qt_maximo);
					if ($this->vr_maximo->Exportable) $Doc->ExportField($this->vr_maximo);
					if ($this->dt_inclusao->Exportable) $Doc->ExportField($this->dt_inclusao);
				} else {
					if ($this->nu_contrato->Exportable) $Doc->ExportField($this->nu_contrato);
					if ($this->nu_itemOc->Exportable) $Doc->ExportField($this->nu_itemOc);
					if ($this->no_itemContratado->Exportable) $Doc->ExportField($this->no_itemContratado);
					if ($this->nu_unidade->Exportable) $Doc->ExportField($this->nu_unidade);
					if ($this->qt_maximo->Exportable) $Doc->ExportField($this->qt_maximo);
					if ($this->vr_maximo->Exportable) $Doc->ExportField($this->vr_maximo);
					if ($this->dt_inclusao->Exportable) $Doc->ExportField($this->dt_inclusao);
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
