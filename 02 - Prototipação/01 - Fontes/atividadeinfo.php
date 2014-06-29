<?php

// Global variable for table object
$atividade = NULL;

//
// Table class for atividade
//
class catividade extends cTable {
	var $nu_atividade;
	var $nu_processo;
	var $nu_atividadePred;
	var $no_atividade;
	var $ds_atividade;
	var $vr_duracao;
	var $ic_ativo;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'atividade';
		$this->TableName = 'atividade';
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

		// nu_atividade
		$this->nu_atividade = new cField('atividade', 'atividade', 'x_nu_atividade', 'nu_atividade', '[nu_atividade]', 'CAST([nu_atividade] AS NVARCHAR)', 3, -1, FALSE, '[nu_atividade]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_atividade->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_atividade'] = &$this->nu_atividade;

		// nu_processo
		$this->nu_processo = new cField('atividade', 'atividade', 'x_nu_processo', 'nu_processo', '[nu_processo]', 'CAST([nu_processo] AS NVARCHAR)', 3, -1, FALSE, '[nu_processo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_processo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_processo'] = &$this->nu_processo;

		// nu_atividadePred
		$this->nu_atividadePred = new cField('atividade', 'atividade', 'x_nu_atividadePred', 'nu_atividadePred', '[nu_atividadePred]', 'CAST([nu_atividadePred] AS NVARCHAR)', 3, -1, FALSE, '[nu_atividadePred]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_atividadePred->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_atividadePred'] = &$this->nu_atividadePred;

		// no_atividade
		$this->no_atividade = new cField('atividade', 'atividade', 'x_no_atividade', 'no_atividade', '[no_atividade]', '[no_atividade]', 200, -1, FALSE, '[no_atividade]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_atividade'] = &$this->no_atividade;

		// ds_atividade
		$this->ds_atividade = new cField('atividade', 'atividade', 'x_ds_atividade', 'ds_atividade', '[ds_atividade]', '[ds_atividade]', 201, -1, FALSE, '[ds_atividade]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_atividade'] = &$this->ds_atividade;

		// vr_duracao
		$this->vr_duracao = new cField('atividade', 'atividade', 'x_vr_duracao', 'vr_duracao', '[vr_duracao]', 'CAST([vr_duracao] AS NVARCHAR)', 131, -1, FALSE, '[vr_duracao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->vr_duracao->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['vr_duracao'] = &$this->vr_duracao;

		// ic_ativo
		$this->ic_ativo = new cField('atividade', 'atividade', 'x_ic_ativo', 'ic_ativo', '[ic_ativo]', '[ic_ativo]', 129, -1, FALSE, '[ic_ativo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_ativo'] = &$this->ic_ativo;
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
		if ($this->getCurrentDetailTable() == "atividade_papel") {
			$sDetailUrl = $GLOBALS["atividade_papel"]->GetListUrl() . "?showmaster=" . $this->TableVar;
			$sDetailUrl .= "&nu_atividade=" . $this->nu_atividade->CurrentValue;
		}
		if ($sDetailUrl == "") {
			$sDetailUrl = "atividadelist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	function SqlFrom() { // From
		return "[dbo].[atividade]";
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
		return "[nu_processo] ASC,[no_atividade] ASC";
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
	var $UpdateTable = "[dbo].[atividade]";

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
			if (array_key_exists('nu_atividade', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_atividade') . '=' . ew_QuotedValue($rs['nu_atividade'], $this->nu_atividade->FldDataType));
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

		// Cascade delete detail table 'atividade_papel'
		if (!isset($GLOBALS["atividade_papel"])) $GLOBALS["atividade_papel"] = new catividade_papel();
		$rscascade = array();
		$GLOBALS["atividade_papel"]->Delete($rscascade, "[nu_atividade] = " . ew_QuotedValue($rs['nu_atividade'], EW_DATATYPE_NUMBER));
		return $conn->Execute($this->DeleteSQL($rs, $where));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "[nu_atividade] = @nu_atividade@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nu_atividade->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_atividade@", ew_AdjustSql($this->nu_atividade->CurrentValue), $sKeyFilter); // Replace key value
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
			return "atividadelist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "atividadelist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("atividadeview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("atividadeview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "atividadeadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("atividadeedit.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("atividadeedit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("atividadeadd.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("atividadeadd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("atividadedelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nu_atividade->CurrentValue)) {
			$sUrl .= "nu_atividade=" . urlencode($this->nu_atividade->CurrentValue);
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
			$arKeys[] = @$_GET["nu_atividade"]; // nu_atividade

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
			$this->nu_atividade->CurrentValue = $key;
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
		$this->nu_atividade->setDbValue($rs->fields('nu_atividade'));
		$this->nu_processo->setDbValue($rs->fields('nu_processo'));
		$this->nu_atividadePred->setDbValue($rs->fields('nu_atividadePred'));
		$this->no_atividade->setDbValue($rs->fields('no_atividade'));
		$this->ds_atividade->setDbValue($rs->fields('ds_atividade'));
		$this->vr_duracao->setDbValue($rs->fields('vr_duracao'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_atividade

		$this->nu_atividade->CellCssStyle = "white-space: nowrap;";

		// nu_processo
		// nu_atividadePred
		// no_atividade
		// ds_atividade
		// vr_duracao
		// ic_ativo
		// nu_atividade

		$this->nu_atividade->ViewValue = $this->nu_atividade->CurrentValue;
		$this->nu_atividade->ViewCustomAttributes = "";

		// nu_processo
		$this->nu_processo->ViewValue = $this->nu_processo->CurrentValue;
		if (strval($this->nu_processo->CurrentValue) <> "") {
			$sFilterWrk = "[nu_processo]" . ew_SearchString("=", $this->nu_processo->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_processo], [no_processo] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[processo]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_processo, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_processo] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_processo->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_processo->ViewValue = $this->nu_processo->CurrentValue;
			}
		} else {
			$this->nu_processo->ViewValue = NULL;
		}
		$this->nu_processo->ViewCustomAttributes = "";

		// nu_atividadePred
		if (strval($this->nu_atividadePred->CurrentValue) <> "") {
			$sFilterWrk = "[nu_atividade]" . ew_SearchString("=", $this->nu_atividadePred->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_atividade], [no_atividade] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[atividade]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_atividadePred, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_atividade] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_atividadePred->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_atividadePred->ViewValue = $this->nu_atividadePred->CurrentValue;
			}
		} else {
			$this->nu_atividadePred->ViewValue = NULL;
		}
		$this->nu_atividadePred->ViewCustomAttributes = "";

		// no_atividade
		$this->no_atividade->ViewValue = $this->no_atividade->CurrentValue;
		$this->no_atividade->ViewCustomAttributes = "";

		// ds_atividade
		$this->ds_atividade->ViewValue = $this->ds_atividade->CurrentValue;
		$this->ds_atividade->ViewCustomAttributes = "";

		// vr_duracao
		$this->vr_duracao->ViewValue = $this->vr_duracao->CurrentValue;
		$this->vr_duracao->ViewValue = ew_FormatNumber($this->vr_duracao->ViewValue, 2, -2, -2, -2);
		$this->vr_duracao->ViewCustomAttributes = "";

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

		// nu_atividade
		$this->nu_atividade->LinkCustomAttributes = "";
		$this->nu_atividade->HrefValue = "";
		$this->nu_atividade->TooltipValue = "";

		// nu_processo
		$this->nu_processo->LinkCustomAttributes = "";
		$this->nu_processo->HrefValue = "";
		$this->nu_processo->TooltipValue = "";

		// nu_atividadePred
		$this->nu_atividadePred->LinkCustomAttributes = "";
		$this->nu_atividadePred->HrefValue = "";
		$this->nu_atividadePred->TooltipValue = "";

		// no_atividade
		$this->no_atividade->LinkCustomAttributes = "";
		$this->no_atividade->HrefValue = "";
		$this->no_atividade->TooltipValue = "";

		// ds_atividade
		$this->ds_atividade->LinkCustomAttributes = "";
		$this->ds_atividade->HrefValue = "";
		$this->ds_atividade->TooltipValue = "";

		// vr_duracao
		$this->vr_duracao->LinkCustomAttributes = "";
		$this->vr_duracao->HrefValue = "";
		$this->vr_duracao->TooltipValue = "";

		// ic_ativo
		$this->ic_ativo->LinkCustomAttributes = "";
		$this->ic_ativo->HrefValue = "";
		$this->ic_ativo->TooltipValue = "";

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
				if ($this->nu_processo->Exportable) $Doc->ExportCaption($this->nu_processo);
				if ($this->nu_atividadePred->Exportable) $Doc->ExportCaption($this->nu_atividadePred);
				if ($this->no_atividade->Exportable) $Doc->ExportCaption($this->no_atividade);
				if ($this->ds_atividade->Exportable) $Doc->ExportCaption($this->ds_atividade);
				if ($this->vr_duracao->Exportable) $Doc->ExportCaption($this->vr_duracao);
				if ($this->ic_ativo->Exportable) $Doc->ExportCaption($this->ic_ativo);
			} else {
				if ($this->nu_processo->Exportable) $Doc->ExportCaption($this->nu_processo);
				if ($this->nu_atividadePred->Exportable) $Doc->ExportCaption($this->nu_atividadePred);
				if ($this->no_atividade->Exportable) $Doc->ExportCaption($this->no_atividade);
				if ($this->vr_duracao->Exportable) $Doc->ExportCaption($this->vr_duracao);
				if ($this->ic_ativo->Exportable) $Doc->ExportCaption($this->ic_ativo);
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
					if ($this->nu_processo->Exportable) $Doc->ExportField($this->nu_processo);
					if ($this->nu_atividadePred->Exportable) $Doc->ExportField($this->nu_atividadePred);
					if ($this->no_atividade->Exportable) $Doc->ExportField($this->no_atividade);
					if ($this->ds_atividade->Exportable) $Doc->ExportField($this->ds_atividade);
					if ($this->vr_duracao->Exportable) $Doc->ExportField($this->vr_duracao);
					if ($this->ic_ativo->Exportable) $Doc->ExportField($this->ic_ativo);
				} else {
					if ($this->nu_processo->Exportable) $Doc->ExportField($this->nu_processo);
					if ($this->nu_atividadePred->Exportable) $Doc->ExportField($this->nu_atividadePred);
					if ($this->no_atividade->Exportable) $Doc->ExportField($this->no_atividade);
					if ($this->vr_duracao->Exportable) $Doc->ExportField($this->vr_duracao);
					if ($this->ic_ativo->Exportable) $Doc->ExportField($this->ic_ativo);
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
