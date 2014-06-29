<?php

// Global variable for table object
$mbcomti = NULL;

//
// Table class for mbcomti
//
class cmbcomti extends cTable {
	var $nu_recursoHum;
	var $nu_pessoa;
	var $nu_organizacao;
	var $nu_papelMembro;
	var $dt_inicio;
	var $dt_fim;
	var $ds_observacoes;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'mbcomti';
		$this->TableName = 'mbcomti';
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

		// nu_recursoHum
		$this->nu_recursoHum = new cField('mbcomti', 'mbcomti', 'x_nu_recursoHum', 'nu_recursoHum', '[nu_recursoHum]', 'CAST([nu_recursoHum] AS NVARCHAR)', 3, -1, FALSE, '[nu_recursoHum]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_recursoHum->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_recursoHum'] = &$this->nu_recursoHum;

		// nu_pessoa
		$this->nu_pessoa = new cField('mbcomti', 'mbcomti', 'x_nu_pessoa', 'nu_pessoa', '[nu_pessoa]', 'CAST([nu_pessoa] AS NVARCHAR)', 3, -1, FALSE, '[EV__nu_pessoa]', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->nu_pessoa->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_pessoa'] = &$this->nu_pessoa;

		// nu_organizacao
		$this->nu_organizacao = new cField('mbcomti', 'mbcomti', 'x_nu_organizacao', 'nu_organizacao', '[nu_organizacao]', 'CAST([nu_organizacao] AS NVARCHAR)', 3, -1, FALSE, '[nu_organizacao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_organizacao->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_organizacao'] = &$this->nu_organizacao;

		// nu_papelMembro
		$this->nu_papelMembro = new cField('mbcomti', 'mbcomti', 'x_nu_papelMembro', 'nu_papelMembro', '[nu_papelMembro]', 'CAST([nu_papelMembro] AS NVARCHAR)', 3, -1, FALSE, '[nu_papelMembro]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_papelMembro->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_papelMembro'] = &$this->nu_papelMembro;

		// dt_inicio
		$this->dt_inicio = new cField('mbcomti', 'mbcomti', 'x_dt_inicio', 'dt_inicio', '[dt_inicio]', '(REPLACE(STR(DAY([dt_inicio]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dt_inicio]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dt_inicio]),4,0))', 135, 7, FALSE, '[dt_inicio]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dt_inicio->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dt_inicio'] = &$this->dt_inicio;

		// dt_fim
		$this->dt_fim = new cField('mbcomti', 'mbcomti', 'x_dt_fim', 'dt_fim', '[dt_fim]', '(REPLACE(STR(DAY([dt_fim]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dt_fim]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dt_fim]),4,0))', 135, 7, FALSE, '[dt_fim]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dt_fim->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dt_fim'] = &$this->dt_fim;

		// ds_observacoes
		$this->ds_observacoes = new cField('mbcomti', 'mbcomti', 'x_ds_observacoes', 'ds_observacoes', '[ds_observacoes]', '[ds_observacoes]', 201, -1, FALSE, '[ds_observacoes]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
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
			$sSortFieldList = ($ofld->FldVirtualExpression <> "") ? $ofld->FldVirtualExpression : $sSortField;
			if ($ctrl) {
				$sOrderByList = $this->getSessionOrderByList();
				if (strpos($sOrderByList, $sSortFieldList . " " . $sLastSort) !== FALSE) {
					$sOrderByList = str_replace($sSortFieldList . " " . $sLastSort, $sSortFieldList . " " . $sThisSort, $sOrderByList);
				} else {
					if ($sOrderByList <> "") $sOrderByList .= ", ";
					$sOrderByList .= $sSortFieldList . " " . $sThisSort;
				}
				$this->setSessionOrderByList($sOrderByList); // Save to Session
			} else {
				$this->setSessionOrderByList($sSortFieldList . " " . $sThisSort); // Save to Session
			}
		} else {
			if (!$ctrl) $ofld->setSort("");
		}
	}

	// Session ORDER BY for List page
	function getSessionOrderByList() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST];
	}

	function setSessionOrderByList($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST] = $v;
	}

	// Table level SQL
	function SqlFrom() { // From
		return "[dbo].[mbcomti]";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlSelectList() { // Select for List page
		return "SELECT * FROM (" .
			"SELECT *, (SELECT TOP 1 [no_pessoa] FROM [pessoa] [EW_TMP_LOOKUPTABLE] WHERE [EW_TMP_LOOKUPTABLE].[nu_pessoa] = [mbcomti].[nu_pessoa]) AS [EV__nu_pessoa] FROM [dbo].[mbcomti]" .
			") [EW_TMP_TABLE]";
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
		if ($this->UseVirtualFields()) {
			$sSort = $this->getSessionOrderByList();
			return ew_BuildSelectSql($this->SqlSelectList(), $this->SqlWhere(), $this->SqlGroupBy(), 
				$this->SqlHaving(), $this->SqlOrderBy(), $sFilter, $sSort);
		} else {
			$sSort = $this->getSessionOrderBy();
			return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(), $this->SqlGroupBy(),
				$this->SqlHaving(), $this->SqlOrderBy(), $sFilter, $sSort);
		}
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = ($this->UseVirtualFields()) ? $this->getSessionOrderByList() : $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->SqlOrderBy(), "", $sSort);
	}

	// Check if virtual fields is used in SQL
	function UseVirtualFields() {
		$sWhere = $this->getSessionWhere();
		$sOrderBy = $this->getSessionOrderByList();
		if ($sWhere <> "")
			$sWhere = " " . str_replace(array("(",")"), array("",""), $sWhere) . " ";
		if ($sOrderBy <> "")
			$sOrderBy = " " . str_replace(array("(",")"), array("",""), $sOrderBy) . " ";
		if ($this->nu_pessoa->AdvancedSearch->SearchValue <> "" ||
			$this->nu_pessoa->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->nu_pessoa->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->nu_pessoa->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		return FALSE;
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
	var $UpdateTable = "[dbo].[mbcomti]";

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
			if (array_key_exists('nu_recursoHum', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_recursoHum') . '=' . ew_QuotedValue($rs['nu_recursoHum'], $this->nu_recursoHum->FldDataType));
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
		return "[nu_recursoHum] = @nu_recursoHum@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nu_recursoHum->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_recursoHum@", ew_AdjustSql($this->nu_recursoHum->CurrentValue), $sKeyFilter); // Replace key value
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
			return "mbcomtilist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "mbcomtilist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("mbcomtiview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("mbcomtiview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "mbcomtiadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("mbcomtiedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("mbcomtiadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("mbcomtidelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nu_recursoHum->CurrentValue)) {
			$sUrl .= "nu_recursoHum=" . urlencode($this->nu_recursoHum->CurrentValue);
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
			$arKeys[] = @$_GET["nu_recursoHum"]; // nu_recursoHum

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
			$this->nu_recursoHum->CurrentValue = $key;
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
		$this->nu_recursoHum->setDbValue($rs->fields('nu_recursoHum'));
		$this->nu_pessoa->setDbValue($rs->fields('nu_pessoa'));
		$this->nu_organizacao->setDbValue($rs->fields('nu_organizacao'));
		$this->nu_papelMembro->setDbValue($rs->fields('nu_papelMembro'));
		$this->dt_inicio->setDbValue($rs->fields('dt_inicio'));
		$this->dt_fim->setDbValue($rs->fields('dt_fim'));
		$this->ds_observacoes->setDbValue($rs->fields('ds_observacoes'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_recursoHum
		// nu_pessoa
		// nu_organizacao
		// nu_papelMembro
		// dt_inicio
		// dt_fim
		// ds_observacoes
		// nu_recursoHum

		$this->nu_recursoHum->ViewValue = $this->nu_recursoHum->CurrentValue;
		$this->nu_recursoHum->ViewCustomAttributes = "";

		// nu_pessoa
		if ($this->nu_pessoa->VirtualValue <> "") {
			$this->nu_pessoa->ViewValue = $this->nu_pessoa->VirtualValue;
		} else {
		if (strval($this->nu_pessoa->CurrentValue) <> "") {
			$sFilterWrk = "[nu_pessoa]" . ew_SearchString("=", $this->nu_pessoa->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_pessoa], [no_pessoa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[pessoa]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_pessoa, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_pessoa->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_pessoa->ViewValue = $this->nu_pessoa->CurrentValue;
			}
		} else {
			$this->nu_pessoa->ViewValue = NULL;
		}
		}
		$this->nu_pessoa->ViewCustomAttributes = "";

		// nu_organizacao
		if (strval($this->nu_organizacao->CurrentValue) <> "") {
			$sFilterWrk = "[nu_organizacao]" . ew_SearchString("=", $this->nu_organizacao->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_organizacao], [no_organizacao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[organizacao]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_organizacao, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_organizacao->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_organizacao->ViewValue = $this->nu_organizacao->CurrentValue;
			}
		} else {
			$this->nu_organizacao->ViewValue = NULL;
		}
		$this->nu_organizacao->ViewCustomAttributes = "";

		// nu_papelMembro
		if (strval($this->nu_papelMembro->CurrentValue) <> "") {
			$sFilterWrk = "[co_papel]" . ew_SearchString("=", $this->nu_papelMembro->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [co_papel], [no_papel] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[papel]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_papelMembro, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_papel] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_papelMembro->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_papelMembro->ViewValue = $this->nu_papelMembro->CurrentValue;
			}
		} else {
			$this->nu_papelMembro->ViewValue = NULL;
		}
		$this->nu_papelMembro->ViewCustomAttributes = "";

		// dt_inicio
		$this->dt_inicio->ViewValue = $this->dt_inicio->CurrentValue;
		$this->dt_inicio->ViewValue = ew_FormatDateTime($this->dt_inicio->ViewValue, 7);
		$this->dt_inicio->ViewCustomAttributes = "";

		// dt_fim
		$this->dt_fim->ViewValue = $this->dt_fim->CurrentValue;
		$this->dt_fim->ViewValue = ew_FormatDateTime($this->dt_fim->ViewValue, 7);
		$this->dt_fim->ViewCustomAttributes = "";

		// ds_observacoes
		$this->ds_observacoes->ViewValue = $this->ds_observacoes->CurrentValue;
		$this->ds_observacoes->ViewCustomAttributes = "";

		// nu_recursoHum
		$this->nu_recursoHum->LinkCustomAttributes = "";
		$this->nu_recursoHum->HrefValue = "";
		$this->nu_recursoHum->TooltipValue = "";

		// nu_pessoa
		$this->nu_pessoa->LinkCustomAttributes = "";
		$this->nu_pessoa->HrefValue = "";
		$this->nu_pessoa->TooltipValue = "";

		// nu_organizacao
		$this->nu_organizacao->LinkCustomAttributes = "";
		$this->nu_organizacao->HrefValue = "";
		$this->nu_organizacao->TooltipValue = "";

		// nu_papelMembro
		$this->nu_papelMembro->LinkCustomAttributes = "";
		$this->nu_papelMembro->HrefValue = "";
		$this->nu_papelMembro->TooltipValue = "";

		// dt_inicio
		$this->dt_inicio->LinkCustomAttributes = "";
		$this->dt_inicio->HrefValue = "";
		$this->dt_inicio->TooltipValue = "";

		// dt_fim
		$this->dt_fim->LinkCustomAttributes = "";
		$this->dt_fim->HrefValue = "";
		$this->dt_fim->TooltipValue = "";

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
				if ($this->nu_recursoHum->Exportable) $Doc->ExportCaption($this->nu_recursoHum);
				if ($this->nu_pessoa->Exportable) $Doc->ExportCaption($this->nu_pessoa);
				if ($this->nu_organizacao->Exportable) $Doc->ExportCaption($this->nu_organizacao);
				if ($this->nu_papelMembro->Exportable) $Doc->ExportCaption($this->nu_papelMembro);
				if ($this->dt_inicio->Exportable) $Doc->ExportCaption($this->dt_inicio);
				if ($this->dt_fim->Exportable) $Doc->ExportCaption($this->dt_fim);
				if ($this->ds_observacoes->Exportable) $Doc->ExportCaption($this->ds_observacoes);
			} else {
				if ($this->nu_recursoHum->Exportable) $Doc->ExportCaption($this->nu_recursoHum);
				if ($this->nu_pessoa->Exportable) $Doc->ExportCaption($this->nu_pessoa);
				if ($this->nu_organizacao->Exportable) $Doc->ExportCaption($this->nu_organizacao);
				if ($this->nu_papelMembro->Exportable) $Doc->ExportCaption($this->nu_papelMembro);
				if ($this->dt_inicio->Exportable) $Doc->ExportCaption($this->dt_inicio);
				if ($this->dt_fim->Exportable) $Doc->ExportCaption($this->dt_fim);
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
					if ($this->nu_recursoHum->Exportable) $Doc->ExportField($this->nu_recursoHum);
					if ($this->nu_pessoa->Exportable) $Doc->ExportField($this->nu_pessoa);
					if ($this->nu_organizacao->Exportable) $Doc->ExportField($this->nu_organizacao);
					if ($this->nu_papelMembro->Exportable) $Doc->ExportField($this->nu_papelMembro);
					if ($this->dt_inicio->Exportable) $Doc->ExportField($this->dt_inicio);
					if ($this->dt_fim->Exportable) $Doc->ExportField($this->dt_fim);
					if ($this->ds_observacoes->Exportable) $Doc->ExportField($this->ds_observacoes);
				} else {
					if ($this->nu_recursoHum->Exportable) $Doc->ExportField($this->nu_recursoHum);
					if ($this->nu_pessoa->Exportable) $Doc->ExportField($this->nu_pessoa);
					if ($this->nu_organizacao->Exportable) $Doc->ExportField($this->nu_organizacao);
					if ($this->nu_papelMembro->Exportable) $Doc->ExportField($this->nu_papelMembro);
					if ($this->dt_inicio->Exportable) $Doc->ExportField($this->dt_inicio);
					if ($this->dt_fim->Exportable) $Doc->ExportField($this->dt_fim);
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
