<?php

// Global variable for table object
$solicitacao_ocorrencia = NULL;

//
// Table class for solicitacao_ocorrencia
//
class csolicitacao_ocorrencia extends cTable {
	var $nu_solicitacao;
	var $nu_ocorrencia;
	var $ic_tpOcorrencia;
	var $ic_exibirNoLaudo;
	var $ds_observacao;
	var $nu_usuarioInc;
	var $dh_inclusao;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'solicitacao_ocorrencia';
		$this->TableName = 'solicitacao_ocorrencia';
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

		// nu_solicitacao
		$this->nu_solicitacao = new cField('solicitacao_ocorrencia', 'solicitacao_ocorrencia', 'x_nu_solicitacao', 'nu_solicitacao', '[nu_solicitacao]', 'CAST([nu_solicitacao] AS NVARCHAR)', 3, -1, FALSE, '[nu_solicitacao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_solicitacao->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_solicitacao'] = &$this->nu_solicitacao;

		// nu_ocorrencia
		$this->nu_ocorrencia = new cField('solicitacao_ocorrencia', 'solicitacao_ocorrencia', 'x_nu_ocorrencia', 'nu_ocorrencia', '[nu_ocorrencia]', 'CAST([nu_ocorrencia] AS NVARCHAR)', 3, -1, FALSE, '[nu_ocorrencia]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_ocorrencia->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_ocorrencia'] = &$this->nu_ocorrencia;

		// ic_tpOcorrencia
		$this->ic_tpOcorrencia = new cField('solicitacao_ocorrencia', 'solicitacao_ocorrencia', 'x_ic_tpOcorrencia', 'ic_tpOcorrencia', '[ic_tpOcorrencia]', '[ic_tpOcorrencia]', 129, -1, FALSE, '[ic_tpOcorrencia]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_tpOcorrencia'] = &$this->ic_tpOcorrencia;

		// ic_exibirNoLaudo
		$this->ic_exibirNoLaudo = new cField('solicitacao_ocorrencia', 'solicitacao_ocorrencia', 'x_ic_exibirNoLaudo', 'ic_exibirNoLaudo', '[ic_exibirNoLaudo]', '[ic_exibirNoLaudo]', 129, -1, FALSE, '[ic_exibirNoLaudo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_exibirNoLaudo'] = &$this->ic_exibirNoLaudo;

		// ds_observacao
		$this->ds_observacao = new cField('solicitacao_ocorrencia', 'solicitacao_ocorrencia', 'x_ds_observacao', 'ds_observacao', '[ds_observacao]', '[ds_observacao]', 201, -1, FALSE, '[ds_observacao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_observacao'] = &$this->ds_observacao;

		// nu_usuarioInc
		$this->nu_usuarioInc = new cField('solicitacao_ocorrencia', 'solicitacao_ocorrencia', 'x_nu_usuarioInc', 'nu_usuarioInc', '[nu_usuarioInc]', 'CAST([nu_usuarioInc] AS NVARCHAR)', 3, -1, FALSE, '[nu_usuarioInc]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_usuarioInc->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_usuarioInc'] = &$this->nu_usuarioInc;

		// dh_inclusao
		$this->dh_inclusao = new cField('solicitacao_ocorrencia', 'solicitacao_ocorrencia', 'x_dh_inclusao', 'dh_inclusao', '[dh_inclusao]', '(REPLACE(STR(DAY([dh_inclusao]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dh_inclusao]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dh_inclusao]),4,0))', 135, 11, FALSE, '[dh_inclusao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
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
		if ($this->getCurrentMasterTable() == "solicitacaoMetricas") {
			if ($this->nu_solicitacao->getSessionValue() <> "")
				$sMasterFilter .= "[nu_solMetricas]=" . ew_QuotedValue($this->nu_solicitacao->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "solicitacaoMetricas") {
			if ($this->nu_solicitacao->getSessionValue() <> "")
				$sDetailFilter .= "[nu_solicitacao]=" . ew_QuotedValue($this->nu_solicitacao->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_solicitacaoMetricas() {
		return "[nu_solMetricas]=@nu_solMetricas@";
	}

	// Detail filter
	function SqlDetailFilter_solicitacaoMetricas() {
		return "[nu_solicitacao]=@nu_solicitacao@";
	}

	// Table level SQL
	function SqlFrom() { // From
		return "[dbo].[solicitacao_ocorrencia]";
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
		return "[nu_solicitacao] ASC,[nu_ocorrencia] ASC";
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
	var $UpdateTable = "[dbo].[solicitacao_ocorrencia]";

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
			if (array_key_exists('nu_solicitacao', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_solicitacao') . '=' . ew_QuotedValue($rs['nu_solicitacao'], $this->nu_solicitacao->FldDataType));
			if (array_key_exists('nu_ocorrencia', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_ocorrencia') . '=' . ew_QuotedValue($rs['nu_ocorrencia'], $this->nu_ocorrencia->FldDataType));
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
		return "[nu_solicitacao] = @nu_solicitacao@ AND [nu_ocorrencia] = @nu_ocorrencia@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nu_solicitacao->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_solicitacao@", ew_AdjustSql($this->nu_solicitacao->CurrentValue), $sKeyFilter); // Replace key value
		if (!is_numeric($this->nu_ocorrencia->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_ocorrencia@", ew_AdjustSql($this->nu_ocorrencia->CurrentValue), $sKeyFilter); // Replace key value
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
			return "solicitacao_ocorrencialist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "solicitacao_ocorrencialist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("solicitacao_ocorrenciaview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("solicitacao_ocorrenciaview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "solicitacao_ocorrenciaadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("solicitacao_ocorrenciaedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("solicitacao_ocorrenciaadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("solicitacao_ocorrenciadelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nu_solicitacao->CurrentValue)) {
			$sUrl .= "nu_solicitacao=" . urlencode($this->nu_solicitacao->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->nu_ocorrencia->CurrentValue)) {
			$sUrl .= "&nu_ocorrencia=" . urlencode($this->nu_ocorrencia->CurrentValue);
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
			$arKey[] = @$_GET["nu_solicitacao"]; // nu_solicitacao
			$arKey[] = @$_GET["nu_ocorrencia"]; // nu_ocorrencia
			$arKeys[] = $arKey;

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_array($key) || count($key) <> 2)
				continue; // Just skip so other keys will still work
			if (!is_numeric($key[0])) // nu_solicitacao
				continue;
			if (!is_numeric($key[1])) // nu_ocorrencia
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
			$this->nu_solicitacao->CurrentValue = $key[0];
			$this->nu_ocorrencia->CurrentValue = $key[1];
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
		$this->nu_solicitacao->setDbValue($rs->fields('nu_solicitacao'));
		$this->nu_ocorrencia->setDbValue($rs->fields('nu_ocorrencia'));
		$this->ic_tpOcorrencia->setDbValue($rs->fields('ic_tpOcorrencia'));
		$this->ic_exibirNoLaudo->setDbValue($rs->fields('ic_exibirNoLaudo'));
		$this->ds_observacao->setDbValue($rs->fields('ds_observacao'));
		$this->nu_usuarioInc->setDbValue($rs->fields('nu_usuarioInc'));
		$this->dh_inclusao->setDbValue($rs->fields('dh_inclusao'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_solicitacao

		$this->nu_solicitacao->CellCssStyle = "white-space: nowrap;";

		// nu_ocorrencia
		$this->nu_ocorrencia->CellCssStyle = "white-space: nowrap;";

		// ic_tpOcorrencia
		// ic_exibirNoLaudo
		// ds_observacao
		// nu_usuarioInc
		// dh_inclusao
		// nu_solicitacao

		$this->nu_solicitacao->ViewValue = $this->nu_solicitacao->CurrentValue;
		$this->nu_solicitacao->ViewCustomAttributes = "";

		// nu_ocorrencia
		$this->nu_ocorrencia->ViewValue = $this->nu_ocorrencia->CurrentValue;
		$this->nu_ocorrencia->ViewCustomAttributes = "";

		// ic_tpOcorrencia
		if (strval($this->ic_tpOcorrencia->CurrentValue) <> "") {
			switch ($this->ic_tpOcorrencia->CurrentValue) {
				case $this->ic_tpOcorrencia->FldTagValue(1):
					$this->ic_tpOcorrencia->ViewValue = $this->ic_tpOcorrencia->FldTagCaption(1) <> "" ? $this->ic_tpOcorrencia->FldTagCaption(1) : $this->ic_tpOcorrencia->CurrentValue;
					break;
				case $this->ic_tpOcorrencia->FldTagValue(2):
					$this->ic_tpOcorrencia->ViewValue = $this->ic_tpOcorrencia->FldTagCaption(2) <> "" ? $this->ic_tpOcorrencia->FldTagCaption(2) : $this->ic_tpOcorrencia->CurrentValue;
					break;
				case $this->ic_tpOcorrencia->FldTagValue(3):
					$this->ic_tpOcorrencia->ViewValue = $this->ic_tpOcorrencia->FldTagCaption(3) <> "" ? $this->ic_tpOcorrencia->FldTagCaption(3) : $this->ic_tpOcorrencia->CurrentValue;
					break;
				case $this->ic_tpOcorrencia->FldTagValue(4):
					$this->ic_tpOcorrencia->ViewValue = $this->ic_tpOcorrencia->FldTagCaption(4) <> "" ? $this->ic_tpOcorrencia->FldTagCaption(4) : $this->ic_tpOcorrencia->CurrentValue;
					break;
				default:
					$this->ic_tpOcorrencia->ViewValue = $this->ic_tpOcorrencia->CurrentValue;
			}
		} else {
			$this->ic_tpOcorrencia->ViewValue = NULL;
		}
		$this->ic_tpOcorrencia->ViewCustomAttributes = "";

		// ic_exibirNoLaudo
		if (strval($this->ic_exibirNoLaudo->CurrentValue) <> "") {
			switch ($this->ic_exibirNoLaudo->CurrentValue) {
				case $this->ic_exibirNoLaudo->FldTagValue(1):
					$this->ic_exibirNoLaudo->ViewValue = $this->ic_exibirNoLaudo->FldTagCaption(1) <> "" ? $this->ic_exibirNoLaudo->FldTagCaption(1) : $this->ic_exibirNoLaudo->CurrentValue;
					break;
				case $this->ic_exibirNoLaudo->FldTagValue(2):
					$this->ic_exibirNoLaudo->ViewValue = $this->ic_exibirNoLaudo->FldTagCaption(2) <> "" ? $this->ic_exibirNoLaudo->FldTagCaption(2) : $this->ic_exibirNoLaudo->CurrentValue;
					break;
				default:
					$this->ic_exibirNoLaudo->ViewValue = $this->ic_exibirNoLaudo->CurrentValue;
			}
		} else {
			$this->ic_exibirNoLaudo->ViewValue = NULL;
		}
		$this->ic_exibirNoLaudo->ViewCustomAttributes = "";

		// ds_observacao
		$this->ds_observacao->ViewValue = $this->ds_observacao->CurrentValue;
		$this->ds_observacao->ViewCustomAttributes = "";

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
		$this->dh_inclusao->ViewValue = ew_FormatDateTime($this->dh_inclusao->ViewValue, 11);
		$this->dh_inclusao->ViewCustomAttributes = "";

		// nu_solicitacao
		$this->nu_solicitacao->LinkCustomAttributes = "";
		$this->nu_solicitacao->HrefValue = "";
		$this->nu_solicitacao->TooltipValue = "";

		// nu_ocorrencia
		$this->nu_ocorrencia->LinkCustomAttributes = "";
		$this->nu_ocorrencia->HrefValue = "";
		$this->nu_ocorrencia->TooltipValue = "";

		// ic_tpOcorrencia
		$this->ic_tpOcorrencia->LinkCustomAttributes = "";
		$this->ic_tpOcorrencia->HrefValue = "";
		$this->ic_tpOcorrencia->TooltipValue = "";

		// ic_exibirNoLaudo
		$this->ic_exibirNoLaudo->LinkCustomAttributes = "";
		$this->ic_exibirNoLaudo->HrefValue = "";
		$this->ic_exibirNoLaudo->TooltipValue = "";

		// ds_observacao
		$this->ds_observacao->LinkCustomAttributes = "";
		$this->ds_observacao->HrefValue = "";
		$this->ds_observacao->TooltipValue = "";

		// nu_usuarioInc
		$this->nu_usuarioInc->LinkCustomAttributes = "";
		$this->nu_usuarioInc->HrefValue = "";
		$this->nu_usuarioInc->TooltipValue = "";

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
				if ($this->nu_solicitacao->Exportable) $Doc->ExportCaption($this->nu_solicitacao);
				if ($this->nu_ocorrencia->Exportable) $Doc->ExportCaption($this->nu_ocorrencia);
				if ($this->ic_tpOcorrencia->Exportable) $Doc->ExportCaption($this->ic_tpOcorrencia);
				if ($this->ic_exibirNoLaudo->Exportable) $Doc->ExportCaption($this->ic_exibirNoLaudo);
				if ($this->ds_observacao->Exportable) $Doc->ExportCaption($this->ds_observacao);
				if ($this->nu_usuarioInc->Exportable) $Doc->ExportCaption($this->nu_usuarioInc);
				if ($this->dh_inclusao->Exportable) $Doc->ExportCaption($this->dh_inclusao);
			} else {
				if ($this->nu_solicitacao->Exportable) $Doc->ExportCaption($this->nu_solicitacao);
				if ($this->nu_ocorrencia->Exportable) $Doc->ExportCaption($this->nu_ocorrencia);
				if ($this->ic_tpOcorrencia->Exportable) $Doc->ExportCaption($this->ic_tpOcorrencia);
				if ($this->ic_exibirNoLaudo->Exportable) $Doc->ExportCaption($this->ic_exibirNoLaudo);
				if ($this->nu_usuarioInc->Exportable) $Doc->ExportCaption($this->nu_usuarioInc);
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
					if ($this->nu_solicitacao->Exportable) $Doc->ExportField($this->nu_solicitacao);
					if ($this->nu_ocorrencia->Exportable) $Doc->ExportField($this->nu_ocorrencia);
					if ($this->ic_tpOcorrencia->Exportable) $Doc->ExportField($this->ic_tpOcorrencia);
					if ($this->ic_exibirNoLaudo->Exportable) $Doc->ExportField($this->ic_exibirNoLaudo);
					if ($this->ds_observacao->Exportable) $Doc->ExportField($this->ds_observacao);
					if ($this->nu_usuarioInc->Exportable) $Doc->ExportField($this->nu_usuarioInc);
					if ($this->dh_inclusao->Exportable) $Doc->ExportField($this->dh_inclusao);
				} else {
					if ($this->nu_solicitacao->Exportable) $Doc->ExportField($this->nu_solicitacao);
					if ($this->nu_ocorrencia->Exportable) $Doc->ExportField($this->nu_ocorrencia);
					if ($this->ic_tpOcorrencia->Exportable) $Doc->ExportField($this->ic_tpOcorrencia);
					if ($this->ic_exibirNoLaudo->Exportable) $Doc->ExportField($this->ic_exibirNoLaudo);
					if ($this->nu_usuarioInc->Exportable) $Doc->ExportField($this->nu_usuarioInc);
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
