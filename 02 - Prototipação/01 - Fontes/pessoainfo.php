<?php

// Global variable for table object
$pessoa = NULL;

//
// Table class for pessoa
//
class cpessoa extends cTable {
	var $nu_pessoa;
	var $no_pessoa;
	var $ic_tpEnvolvimento;
	var $nu_cargo;
	var $nu_areaLotacao;
	var $no_email;
	var $ds_telefone1;
	var $ds_telefone2;
	var $ic_ativo;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'pessoa';
		$this->TableName = 'pessoa';
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

		// nu_pessoa
		$this->nu_pessoa = new cField('pessoa', 'pessoa', 'x_nu_pessoa', 'nu_pessoa', '[nu_pessoa]', 'CAST([nu_pessoa] AS NVARCHAR)', 3, -1, FALSE, '[nu_pessoa]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_pessoa->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_pessoa'] = &$this->nu_pessoa;

		// no_pessoa
		$this->no_pessoa = new cField('pessoa', 'pessoa', 'x_no_pessoa', 'no_pessoa', '[no_pessoa]', '[no_pessoa]', 200, -1, FALSE, '[no_pessoa]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_pessoa'] = &$this->no_pessoa;

		// ic_tpEnvolvimento
		$this->ic_tpEnvolvimento = new cField('pessoa', 'pessoa', 'x_ic_tpEnvolvimento', 'ic_tpEnvolvimento', '[ic_tpEnvolvimento]', '[ic_tpEnvolvimento]', 129, -1, FALSE, '[ic_tpEnvolvimento]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->ic_tpEnvolvimento->AdvancedSearch->SearchValueDefault = "F";
		$this->ic_tpEnvolvimento->AdvancedSearch->SearchOperatorDefault = "LIKE";
		$this->ic_tpEnvolvimento->AdvancedSearch->SearchOperatorDefault2 = "";
		$this->ic_tpEnvolvimento->AdvancedSearch->SearchConditionDefault = "AND";
		$this->fields['ic_tpEnvolvimento'] = &$this->ic_tpEnvolvimento;

		// nu_cargo
		$this->nu_cargo = new cField('pessoa', 'pessoa', 'x_nu_cargo', 'nu_cargo', '[nu_cargo]', 'CAST([nu_cargo] AS NVARCHAR)', 3, -1, FALSE, '[nu_cargo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_cargo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_cargo'] = &$this->nu_cargo;

		// nu_areaLotacao
		$this->nu_areaLotacao = new cField('pessoa', 'pessoa', 'x_nu_areaLotacao', 'nu_areaLotacao', '[nu_areaLotacao]', 'CAST([nu_areaLotacao] AS NVARCHAR)', 3, -1, FALSE, '[nu_areaLotacao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_areaLotacao->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_areaLotacao'] = &$this->nu_areaLotacao;

		// no_email
		$this->no_email = new cField('pessoa', 'pessoa', 'x_no_email', 'no_email', '[no_email]', '[no_email]', 200, -1, FALSE, '[no_email]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->no_email->FldDefaultErrMsg = $Language->Phrase("IncorrectEmail");
		$this->fields['no_email'] = &$this->no_email;

		// ds_telefone1
		$this->ds_telefone1 = new cField('pessoa', 'pessoa', 'x_ds_telefone1', 'ds_telefone1', '[ds_telefone1]', '[ds_telefone1]', 200, -1, FALSE, '[ds_telefone1]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_telefone1'] = &$this->ds_telefone1;

		// ds_telefone2
		$this->ds_telefone2 = new cField('pessoa', 'pessoa', 'x_ds_telefone2', 'ds_telefone2', '[ds_telefone2]', '[ds_telefone2]', 200, -1, FALSE, '[ds_telefone2]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_telefone2'] = &$this->ds_telefone2;

		// ic_ativo
		$this->ic_ativo = new cField('pessoa', 'pessoa', 'x_ic_ativo', 'ic_ativo', '[ic_ativo]', '[ic_ativo]', 129, -1, FALSE, '[ic_ativo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->ic_ativo->AdvancedSearch->SearchValueDefault = "S";
		$this->ic_ativo->AdvancedSearch->SearchOperatorDefault = "LIKE";
		$this->ic_ativo->AdvancedSearch->SearchOperatorDefault2 = "";
		$this->ic_ativo->AdvancedSearch->SearchConditionDefault = "AND";
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

	// Table level SQL
	function SqlFrom() { // From
		return "[dbo].[pessoa]";
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
	var $UpdateTable = "[dbo].[pessoa]";

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
			if (array_key_exists('nu_pessoa', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_pessoa') . '=' . ew_QuotedValue($rs['nu_pessoa'], $this->nu_pessoa->FldDataType));
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
		return "[nu_pessoa] = @nu_pessoa@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nu_pessoa->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_pessoa@", ew_AdjustSql($this->nu_pessoa->CurrentValue), $sKeyFilter); // Replace key value
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
			return "pessoalist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "pessoalist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("pessoaview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("pessoaview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "pessoaadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("pessoaedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("pessoaadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("pessoadelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nu_pessoa->CurrentValue)) {
			$sUrl .= "nu_pessoa=" . urlencode($this->nu_pessoa->CurrentValue);
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
			$arKeys[] = @$_GET["nu_pessoa"]; // nu_pessoa

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
			$this->nu_pessoa->CurrentValue = $key;
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
		$this->nu_pessoa->setDbValue($rs->fields('nu_pessoa'));
		$this->no_pessoa->setDbValue($rs->fields('no_pessoa'));
		$this->ic_tpEnvolvimento->setDbValue($rs->fields('ic_tpEnvolvimento'));
		$this->nu_cargo->setDbValue($rs->fields('nu_cargo'));
		$this->nu_areaLotacao->setDbValue($rs->fields('nu_areaLotacao'));
		$this->no_email->setDbValue($rs->fields('no_email'));
		$this->ds_telefone1->setDbValue($rs->fields('ds_telefone1'));
		$this->ds_telefone2->setDbValue($rs->fields('ds_telefone2'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_pessoa
		// no_pessoa
		// ic_tpEnvolvimento
		// nu_cargo
		// nu_areaLotacao
		// no_email
		// ds_telefone1
		// ds_telefone2
		// ic_ativo
		// nu_pessoa

		$this->nu_pessoa->ViewValue = $this->nu_pessoa->CurrentValue;
		$this->nu_pessoa->ViewCustomAttributes = "";

		// no_pessoa
		$this->no_pessoa->ViewValue = $this->no_pessoa->CurrentValue;
		$this->no_pessoa->ViewCustomAttributes = "";

		// ic_tpEnvolvimento
		if (strval($this->ic_tpEnvolvimento->CurrentValue) <> "") {
			switch ($this->ic_tpEnvolvimento->CurrentValue) {
				case $this->ic_tpEnvolvimento->FldTagValue(1):
					$this->ic_tpEnvolvimento->ViewValue = $this->ic_tpEnvolvimento->FldTagCaption(1) <> "" ? $this->ic_tpEnvolvimento->FldTagCaption(1) : $this->ic_tpEnvolvimento->CurrentValue;
					break;
				case $this->ic_tpEnvolvimento->FldTagValue(2):
					$this->ic_tpEnvolvimento->ViewValue = $this->ic_tpEnvolvimento->FldTagCaption(2) <> "" ? $this->ic_tpEnvolvimento->FldTagCaption(2) : $this->ic_tpEnvolvimento->CurrentValue;
					break;
				case $this->ic_tpEnvolvimento->FldTagValue(3):
					$this->ic_tpEnvolvimento->ViewValue = $this->ic_tpEnvolvimento->FldTagCaption(3) <> "" ? $this->ic_tpEnvolvimento->FldTagCaption(3) : $this->ic_tpEnvolvimento->CurrentValue;
					break;
				case $this->ic_tpEnvolvimento->FldTagValue(4):
					$this->ic_tpEnvolvimento->ViewValue = $this->ic_tpEnvolvimento->FldTagCaption(4) <> "" ? $this->ic_tpEnvolvimento->FldTagCaption(4) : $this->ic_tpEnvolvimento->CurrentValue;
					break;
				default:
					$this->ic_tpEnvolvimento->ViewValue = $this->ic_tpEnvolvimento->CurrentValue;
			}
		} else {
			$this->ic_tpEnvolvimento->ViewValue = NULL;
		}
		$this->ic_tpEnvolvimento->ViewCustomAttributes = "";

		// nu_cargo
		if (strval($this->nu_cargo->CurrentValue) <> "") {
			$sFilterWrk = "[nu_cargo]" . ew_SearchString("=", $this->nu_cargo->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_cargo], [no_cargo] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[cargo]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_cargo, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_cargo->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_cargo->ViewValue = $this->nu_cargo->CurrentValue;
			}
		} else {
			$this->nu_cargo->ViewValue = NULL;
		}
		$this->nu_cargo->ViewCustomAttributes = "";

		// nu_areaLotacao
		if (strval($this->nu_areaLotacao->CurrentValue) <> "") {
			$sFilterWrk = "[nu_area]" . ew_SearchString("=", $this->nu_areaLotacao->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_area], [no_area] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[area]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_areaLotacao, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_areaLotacao->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_areaLotacao->ViewValue = $this->nu_areaLotacao->CurrentValue;
			}
		} else {
			$this->nu_areaLotacao->ViewValue = NULL;
		}
		$this->nu_areaLotacao->ViewCustomAttributes = "";

		// no_email
		$this->no_email->ViewValue = $this->no_email->CurrentValue;
		$this->no_email->ViewCustomAttributes = "";

		// ds_telefone1
		$this->ds_telefone1->ViewValue = $this->ds_telefone1->CurrentValue;
		$this->ds_telefone1->ViewCustomAttributes = "";

		// ds_telefone2
		$this->ds_telefone2->ViewValue = $this->ds_telefone2->CurrentValue;
		$this->ds_telefone2->ViewCustomAttributes = "";

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

		// nu_pessoa
		$this->nu_pessoa->LinkCustomAttributes = "";
		$this->nu_pessoa->HrefValue = "";
		$this->nu_pessoa->TooltipValue = "";

		// no_pessoa
		$this->no_pessoa->LinkCustomAttributes = "";
		$this->no_pessoa->HrefValue = "";
		$this->no_pessoa->TooltipValue = "";

		// ic_tpEnvolvimento
		$this->ic_tpEnvolvimento->LinkCustomAttributes = "";
		$this->ic_tpEnvolvimento->HrefValue = "";
		$this->ic_tpEnvolvimento->TooltipValue = "";

		// nu_cargo
		$this->nu_cargo->LinkCustomAttributes = "";
		$this->nu_cargo->HrefValue = "";
		$this->nu_cargo->TooltipValue = "";

		// nu_areaLotacao
		$this->nu_areaLotacao->LinkCustomAttributes = "";
		$this->nu_areaLotacao->HrefValue = "";
		$this->nu_areaLotacao->TooltipValue = "";

		// no_email
		$this->no_email->LinkCustomAttributes = "";
		$this->no_email->HrefValue = "";
		$this->no_email->TooltipValue = "";

		// ds_telefone1
		$this->ds_telefone1->LinkCustomAttributes = "";
		$this->ds_telefone1->HrefValue = "";
		$this->ds_telefone1->TooltipValue = "";

		// ds_telefone2
		$this->ds_telefone2->LinkCustomAttributes = "";
		$this->ds_telefone2->HrefValue = "";
		$this->ds_telefone2->TooltipValue = "";

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
				if ($this->nu_pessoa->Exportable) $Doc->ExportCaption($this->nu_pessoa);
				if ($this->no_pessoa->Exportable) $Doc->ExportCaption($this->no_pessoa);
				if ($this->ic_tpEnvolvimento->Exportable) $Doc->ExportCaption($this->ic_tpEnvolvimento);
				if ($this->nu_cargo->Exportable) $Doc->ExportCaption($this->nu_cargo);
				if ($this->nu_areaLotacao->Exportable) $Doc->ExportCaption($this->nu_areaLotacao);
				if ($this->no_email->Exportable) $Doc->ExportCaption($this->no_email);
				if ($this->ds_telefone1->Exportable) $Doc->ExportCaption($this->ds_telefone1);
				if ($this->ds_telefone2->Exportable) $Doc->ExportCaption($this->ds_telefone2);
				if ($this->ic_ativo->Exportable) $Doc->ExportCaption($this->ic_ativo);
			} else {
				if ($this->nu_pessoa->Exportable) $Doc->ExportCaption($this->nu_pessoa);
				if ($this->no_pessoa->Exportable) $Doc->ExportCaption($this->no_pessoa);
				if ($this->ic_tpEnvolvimento->Exportable) $Doc->ExportCaption($this->ic_tpEnvolvimento);
				if ($this->nu_cargo->Exportable) $Doc->ExportCaption($this->nu_cargo);
				if ($this->nu_areaLotacao->Exportable) $Doc->ExportCaption($this->nu_areaLotacao);
				if ($this->no_email->Exportable) $Doc->ExportCaption($this->no_email);
				if ($this->ds_telefone1->Exportable) $Doc->ExportCaption($this->ds_telefone1);
				if ($this->ds_telefone2->Exportable) $Doc->ExportCaption($this->ds_telefone2);
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
					if ($this->nu_pessoa->Exportable) $Doc->ExportField($this->nu_pessoa);
					if ($this->no_pessoa->Exportable) $Doc->ExportField($this->no_pessoa);
					if ($this->ic_tpEnvolvimento->Exportable) $Doc->ExportField($this->ic_tpEnvolvimento);
					if ($this->nu_cargo->Exportable) $Doc->ExportField($this->nu_cargo);
					if ($this->nu_areaLotacao->Exportable) $Doc->ExportField($this->nu_areaLotacao);
					if ($this->no_email->Exportable) $Doc->ExportField($this->no_email);
					if ($this->ds_telefone1->Exportable) $Doc->ExportField($this->ds_telefone1);
					if ($this->ds_telefone2->Exportable) $Doc->ExportField($this->ds_telefone2);
					if ($this->ic_ativo->Exportable) $Doc->ExportField($this->ic_ativo);
				} else {
					if ($this->nu_pessoa->Exportable) $Doc->ExportField($this->nu_pessoa);
					if ($this->no_pessoa->Exportable) $Doc->ExportField($this->no_pessoa);
					if ($this->ic_tpEnvolvimento->Exportable) $Doc->ExportField($this->ic_tpEnvolvimento);
					if ($this->nu_cargo->Exportable) $Doc->ExportField($this->nu_cargo);
					if ($this->nu_areaLotacao->Exportable) $Doc->ExportField($this->nu_areaLotacao);
					if ($this->no_email->Exportable) $Doc->ExportField($this->no_email);
					if ($this->ds_telefone1->Exportable) $Doc->ExportField($this->ds_telefone1);
					if ($this->ds_telefone2->Exportable) $Doc->ExportField($this->ds_telefone2);
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
