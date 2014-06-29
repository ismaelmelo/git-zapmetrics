<?php

// Global variable for table object
$indicadorversao = NULL;

//
// Table class for indicadorversao
//
class cindicadorversao extends cTable {
	var $nu_indicador;
	var $nu_versao;
	var $ic_periodicidadeGeracao;
	var $ds_origemIndicador;
	var $ic_reponsavelColetaCtrl;
	var $ds_codigoSql;
	var $dh_versao;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'indicadorversao';
		$this->TableName = 'indicadorversao';
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

		// nu_indicador
		$this->nu_indicador = new cField('indicadorversao', 'indicadorversao', 'x_nu_indicador', 'nu_indicador', '[nu_indicador]', 'CAST([nu_indicador] AS NVARCHAR)', 3, -1, FALSE, '[nu_indicador]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_indicador->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_indicador'] = &$this->nu_indicador;

		// nu_versao
		$this->nu_versao = new cField('indicadorversao', 'indicadorversao', 'x_nu_versao', 'nu_versao', '[nu_versao]', 'CAST([nu_versao] AS NVARCHAR)', 3, -1, FALSE, '[nu_versao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_versao->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_versao'] = &$this->nu_versao;

		// ic_periodicidadeGeracao
		$this->ic_periodicidadeGeracao = new cField('indicadorversao', 'indicadorversao', 'x_ic_periodicidadeGeracao', 'ic_periodicidadeGeracao', '[ic_periodicidadeGeracao]', '[ic_periodicidadeGeracao]', 129, -1, FALSE, '[ic_periodicidadeGeracao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_periodicidadeGeracao'] = &$this->ic_periodicidadeGeracao;

		// ds_origemIndicador
		$this->ds_origemIndicador = new cField('indicadorversao', 'indicadorversao', 'x_ds_origemIndicador', 'ds_origemIndicador', '[ds_origemIndicador]', '[ds_origemIndicador]', 201, -1, FALSE, '[ds_origemIndicador]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_origemIndicador'] = &$this->ds_origemIndicador;

		// ic_reponsavelColetaCtrl
		$this->ic_reponsavelColetaCtrl = new cField('indicadorversao', 'indicadorversao', 'x_ic_reponsavelColetaCtrl', 'ic_reponsavelColetaCtrl', '[ic_reponsavelColetaCtrl]', '[ic_reponsavelColetaCtrl]', 129, -1, FALSE, '[ic_reponsavelColetaCtrl]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_reponsavelColetaCtrl'] = &$this->ic_reponsavelColetaCtrl;

		// ds_codigoSql
		$this->ds_codigoSql = new cField('indicadorversao', 'indicadorversao', 'x_ds_codigoSql', 'ds_codigoSql', '[ds_codigoSql]', '[ds_codigoSql]', 201, -1, FALSE, '[ds_codigoSql]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_codigoSql'] = &$this->ds_codigoSql;

		// dh_versao
		$this->dh_versao = new cField('indicadorversao', 'indicadorversao', 'x_dh_versao', 'dh_versao', '[dh_versao]', '(REPLACE(STR(DAY([dh_versao]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dh_versao]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dh_versao]),4,0))', 135, 11, FALSE, '[dh_versao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dh_versao->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dh_versao'] = &$this->dh_versao;
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
		if ($this->getCurrentMasterTable() == "indicadorvalor") {
			if ($this->nu_indicador->getSessionValue() <> "")
				$sMasterFilter .= "[nu_indicador]=" . ew_QuotedValue($this->nu_indicador->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
			if ($this->nu_versao->getSessionValue() <> "")
				$sMasterFilter .= " AND [nu_versao]=" . ew_QuotedValue($this->nu_versao->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "indicadorvalor") {
			if ($this->nu_indicador->getSessionValue() <> "")
				$sDetailFilter .= "[nu_indicador]=" . ew_QuotedValue($this->nu_indicador->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
			if ($this->nu_versao->getSessionValue() <> "")
				$sDetailFilter .= " AND [nu_versao]=" . ew_QuotedValue($this->nu_versao->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_indicadorvalor() {
		return "[nu_indicador]=@nu_indicador@ AND [nu_versao]=@nu_versao@";
	}

	// Detail filter
	function SqlDetailFilter_indicadorvalor() {
		return "[nu_indicador]=@nu_indicador@ AND [nu_versao]=@nu_versao@";
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
		if ($this->getCurrentDetailTable() == "indicador") {
			$sDetailUrl = $GLOBALS["indicador"]->GetListUrl() . "?showmaster=" . $this->TableVar;
			$sDetailUrl .= "&nu_indicador=" . $this->nu_indicador->CurrentValue;
		}
		if ($sDetailUrl == "") {
			$sDetailUrl = "indicadorversaolist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	function SqlFrom() { // From
		return "[dbo].[indicadorversao]";
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
	var $UpdateTable = "[dbo].[indicadorversao]";

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
			if (array_key_exists('nu_indicador', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_indicador') . '=' . ew_QuotedValue($rs['nu_indicador'], $this->nu_indicador->FldDataType));
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
		return "[nu_indicador] = @nu_indicador@ AND [nu_versao] = @nu_versao@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nu_indicador->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_indicador@", ew_AdjustSql($this->nu_indicador->CurrentValue), $sKeyFilter); // Replace key value
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
			return "indicadorversaolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "indicadorversaolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("indicadorversaoview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("indicadorversaoview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "indicadorversaoadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("indicadorversaoedit.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("indicadorversaoedit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("indicadorversaoadd.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("indicadorversaoadd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("indicadorversaodelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nu_indicador->CurrentValue)) {
			$sUrl .= "nu_indicador=" . urlencode($this->nu_indicador->CurrentValue);
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
			$arKey[] = @$_GET["nu_indicador"]; // nu_indicador
			$arKey[] = @$_GET["nu_versao"]; // nu_versao
			$arKeys[] = $arKey;

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_array($key) || count($key) <> 2)
				continue; // Just skip so other keys will still work
			if (!is_numeric($key[0])) // nu_indicador
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
			$this->nu_indicador->CurrentValue = $key[0];
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
		$this->nu_indicador->setDbValue($rs->fields('nu_indicador'));
		$this->nu_versao->setDbValue($rs->fields('nu_versao'));
		$this->ic_periodicidadeGeracao->setDbValue($rs->fields('ic_periodicidadeGeracao'));
		$this->ds_origemIndicador->setDbValue($rs->fields('ds_origemIndicador'));
		$this->ic_reponsavelColetaCtrl->setDbValue($rs->fields('ic_reponsavelColetaCtrl'));
		$this->ds_codigoSql->setDbValue($rs->fields('ds_codigoSql'));
		$this->dh_versao->setDbValue($rs->fields('dh_versao'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_indicador
		// nu_versao
		// ic_periodicidadeGeracao
		// ds_origemIndicador
		// ic_reponsavelColetaCtrl
		// ds_codigoSql
		// dh_versao
		// nu_indicador

		if (strval($this->nu_indicador->CurrentValue) <> "") {
			$sFilterWrk = "[nu_indicador]" . ew_SearchString("=", $this->nu_indicador->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_indicador], [no_indicador] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[indicador]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_indicador, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_indicador] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_indicador->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_indicador->ViewValue = $this->nu_indicador->CurrentValue;
			}
		} else {
			$this->nu_indicador->ViewValue = NULL;
		}
		$this->nu_indicador->ViewCustomAttributes = "";

		// nu_versao
		$this->nu_versao->ViewValue = $this->nu_versao->CurrentValue;
		$this->nu_versao->ViewCustomAttributes = "";

		// ic_periodicidadeGeracao
		if (strval($this->ic_periodicidadeGeracao->CurrentValue) <> "") {
			switch ($this->ic_periodicidadeGeracao->CurrentValue) {
				case $this->ic_periodicidadeGeracao->FldTagValue(1):
					$this->ic_periodicidadeGeracao->ViewValue = $this->ic_periodicidadeGeracao->FldTagCaption(1) <> "" ? $this->ic_periodicidadeGeracao->FldTagCaption(1) : $this->ic_periodicidadeGeracao->CurrentValue;
					break;
				case $this->ic_periodicidadeGeracao->FldTagValue(2):
					$this->ic_periodicidadeGeracao->ViewValue = $this->ic_periodicidadeGeracao->FldTagCaption(2) <> "" ? $this->ic_periodicidadeGeracao->FldTagCaption(2) : $this->ic_periodicidadeGeracao->CurrentValue;
					break;
				case $this->ic_periodicidadeGeracao->FldTagValue(3):
					$this->ic_periodicidadeGeracao->ViewValue = $this->ic_periodicidadeGeracao->FldTagCaption(3) <> "" ? $this->ic_periodicidadeGeracao->FldTagCaption(3) : $this->ic_periodicidadeGeracao->CurrentValue;
					break;
				case $this->ic_periodicidadeGeracao->FldTagValue(4):
					$this->ic_periodicidadeGeracao->ViewValue = $this->ic_periodicidadeGeracao->FldTagCaption(4) <> "" ? $this->ic_periodicidadeGeracao->FldTagCaption(4) : $this->ic_periodicidadeGeracao->CurrentValue;
					break;
				case $this->ic_periodicidadeGeracao->FldTagValue(5):
					$this->ic_periodicidadeGeracao->ViewValue = $this->ic_periodicidadeGeracao->FldTagCaption(5) <> "" ? $this->ic_periodicidadeGeracao->FldTagCaption(5) : $this->ic_periodicidadeGeracao->CurrentValue;
					break;
				default:
					$this->ic_periodicidadeGeracao->ViewValue = $this->ic_periodicidadeGeracao->CurrentValue;
			}
		} else {
			$this->ic_periodicidadeGeracao->ViewValue = NULL;
		}
		$this->ic_periodicidadeGeracao->ViewCustomAttributes = "";

		// ds_origemIndicador
		$this->ds_origemIndicador->ViewValue = $this->ds_origemIndicador->CurrentValue;
		$this->ds_origemIndicador->ViewCustomAttributes = "";

		// ic_reponsavelColetaCtrl
		if (strval($this->ic_reponsavelColetaCtrl->CurrentValue) <> "") {
			switch ($this->ic_reponsavelColetaCtrl->CurrentValue) {
				case $this->ic_reponsavelColetaCtrl->FldTagValue(1):
					$this->ic_reponsavelColetaCtrl->ViewValue = $this->ic_reponsavelColetaCtrl->FldTagCaption(1) <> "" ? $this->ic_reponsavelColetaCtrl->FldTagCaption(1) : $this->ic_reponsavelColetaCtrl->CurrentValue;
					break;
				case $this->ic_reponsavelColetaCtrl->FldTagValue(2):
					$this->ic_reponsavelColetaCtrl->ViewValue = $this->ic_reponsavelColetaCtrl->FldTagCaption(2) <> "" ? $this->ic_reponsavelColetaCtrl->FldTagCaption(2) : $this->ic_reponsavelColetaCtrl->CurrentValue;
					break;
				default:
					$this->ic_reponsavelColetaCtrl->ViewValue = $this->ic_reponsavelColetaCtrl->CurrentValue;
			}
		} else {
			$this->ic_reponsavelColetaCtrl->ViewValue = NULL;
		}
		$this->ic_reponsavelColetaCtrl->ViewCustomAttributes = "";

		// ds_codigoSql
		$this->ds_codigoSql->ViewValue = $this->ds_codigoSql->CurrentValue;
		$this->ds_codigoSql->ViewCustomAttributes = "";

		// dh_versao
		$this->dh_versao->ViewValue = $this->dh_versao->CurrentValue;
		$this->dh_versao->ViewValue = ew_FormatDateTime($this->dh_versao->ViewValue, 11);
		$this->dh_versao->ViewCustomAttributes = "";

		// nu_indicador
		$this->nu_indicador->LinkCustomAttributes = "";
		$this->nu_indicador->HrefValue = "";
		$this->nu_indicador->TooltipValue = "";

		// nu_versao
		$this->nu_versao->LinkCustomAttributes = "";
		$this->nu_versao->HrefValue = "";
		$this->nu_versao->TooltipValue = "";

		// ic_periodicidadeGeracao
		$this->ic_periodicidadeGeracao->LinkCustomAttributes = "";
		$this->ic_periodicidadeGeracao->HrefValue = "";
		$this->ic_periodicidadeGeracao->TooltipValue = "";

		// ds_origemIndicador
		$this->ds_origemIndicador->LinkCustomAttributes = "";
		$this->ds_origemIndicador->HrefValue = "";
		$this->ds_origemIndicador->TooltipValue = "";

		// ic_reponsavelColetaCtrl
		$this->ic_reponsavelColetaCtrl->LinkCustomAttributes = "";
		$this->ic_reponsavelColetaCtrl->HrefValue = "";
		$this->ic_reponsavelColetaCtrl->TooltipValue = "";

		// ds_codigoSql
		$this->ds_codigoSql->LinkCustomAttributes = "";
		$this->ds_codigoSql->HrefValue = "";
		$this->ds_codigoSql->TooltipValue = "";

		// dh_versao
		$this->dh_versao->LinkCustomAttributes = "";
		$this->dh_versao->HrefValue = "";
		$this->dh_versao->TooltipValue = "";

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
				if ($this->nu_indicador->Exportable) $Doc->ExportCaption($this->nu_indicador);
				if ($this->nu_versao->Exportable) $Doc->ExportCaption($this->nu_versao);
				if ($this->ic_periodicidadeGeracao->Exportable) $Doc->ExportCaption($this->ic_periodicidadeGeracao);
				if ($this->ds_origemIndicador->Exportable) $Doc->ExportCaption($this->ds_origemIndicador);
				if ($this->ic_reponsavelColetaCtrl->Exportable) $Doc->ExportCaption($this->ic_reponsavelColetaCtrl);
				if ($this->ds_codigoSql->Exportable) $Doc->ExportCaption($this->ds_codigoSql);
				if ($this->dh_versao->Exportable) $Doc->ExportCaption($this->dh_versao);
			} else {
				if ($this->nu_indicador->Exportable) $Doc->ExportCaption($this->nu_indicador);
				if ($this->nu_versao->Exportable) $Doc->ExportCaption($this->nu_versao);
				if ($this->ic_periodicidadeGeracao->Exportable) $Doc->ExportCaption($this->ic_periodicidadeGeracao);
				if ($this->ic_reponsavelColetaCtrl->Exportable) $Doc->ExportCaption($this->ic_reponsavelColetaCtrl);
				if ($this->dh_versao->Exportable) $Doc->ExportCaption($this->dh_versao);
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
					if ($this->nu_indicador->Exportable) $Doc->ExportField($this->nu_indicador);
					if ($this->nu_versao->Exportable) $Doc->ExportField($this->nu_versao);
					if ($this->ic_periodicidadeGeracao->Exportable) $Doc->ExportField($this->ic_periodicidadeGeracao);
					if ($this->ds_origemIndicador->Exportable) $Doc->ExportField($this->ds_origemIndicador);
					if ($this->ic_reponsavelColetaCtrl->Exportable) $Doc->ExportField($this->ic_reponsavelColetaCtrl);
					if ($this->ds_codigoSql->Exportable) $Doc->ExportField($this->ds_codigoSql);
					if ($this->dh_versao->Exportable) $Doc->ExportField($this->dh_versao);
				} else {
					if ($this->nu_indicador->Exportable) $Doc->ExportField($this->nu_indicador);
					if ($this->nu_versao->Exportable) $Doc->ExportField($this->nu_versao);
					if ($this->ic_periodicidadeGeracao->Exportable) $Doc->ExportField($this->ic_periodicidadeGeracao);
					if ($this->ic_reponsavelColetaCtrl->Exportable) $Doc->ExportField($this->ic_reponsavelColetaCtrl);
					if ($this->dh_versao->Exportable) $Doc->ExportField($this->dh_versao);
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
