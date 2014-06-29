<?php

// Global variable for table object
$riscoprojeto = NULL;

//
// Table class for riscoprojeto
//
class criscoprojeto extends cTable {
	var $nu_riscoProjeto;
	var $nu_projeto;
	var $nu_catRisco;
	var $ic_tpRisco;
	var $ds_risco;
	var $ds_consequencia;
	var $nu_probabilidade;
	var $nu_impacto;
	var $nu_severidade;
	var $nu_acao;
	var $ds_gatilho;
	var $ds_respRisco;
	var $nu_usuarioResp;
	var $ic_stRisco;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'riscoprojeto';
		$this->TableName = 'riscoprojeto';
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

		// nu_riscoProjeto
		$this->nu_riscoProjeto = new cField('riscoprojeto', 'riscoprojeto', 'x_nu_riscoProjeto', 'nu_riscoProjeto', '[nu_riscoProjeto]', 'CAST([nu_riscoProjeto] AS NVARCHAR)', 3, -1, FALSE, '[nu_riscoProjeto]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_riscoProjeto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_riscoProjeto'] = &$this->nu_riscoProjeto;

		// nu_projeto
		$this->nu_projeto = new cField('riscoprojeto', 'riscoprojeto', 'x_nu_projeto', 'nu_projeto', '[nu_projeto]', 'CAST([nu_projeto] AS NVARCHAR)', 3, -1, FALSE, '[nu_projeto]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_projeto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_projeto'] = &$this->nu_projeto;

		// nu_catRisco
		$this->nu_catRisco = new cField('riscoprojeto', 'riscoprojeto', 'x_nu_catRisco', 'nu_catRisco', '[nu_catRisco]', 'CAST([nu_catRisco] AS NVARCHAR)', 3, -1, FALSE, '[nu_catRisco]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_catRisco->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_catRisco'] = &$this->nu_catRisco;

		// ic_tpRisco
		$this->ic_tpRisco = new cField('riscoprojeto', 'riscoprojeto', 'x_ic_tpRisco', 'ic_tpRisco', '[ic_tpRisco]', '[ic_tpRisco]', 129, -1, FALSE, '[ic_tpRisco]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_tpRisco'] = &$this->ic_tpRisco;

		// ds_risco
		$this->ds_risco = new cField('riscoprojeto', 'riscoprojeto', 'x_ds_risco', 'ds_risco', '[ds_risco]', '[ds_risco]', 201, -1, FALSE, '[ds_risco]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_risco'] = &$this->ds_risco;

		// ds_consequencia
		$this->ds_consequencia = new cField('riscoprojeto', 'riscoprojeto', 'x_ds_consequencia', 'ds_consequencia', '[ds_consequencia]', '[ds_consequencia]', 201, -1, FALSE, '[ds_consequencia]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_consequencia'] = &$this->ds_consequencia;

		// nu_probabilidade
		$this->nu_probabilidade = new cField('riscoprojeto', 'riscoprojeto', 'x_nu_probabilidade', 'nu_probabilidade', '[nu_probabilidade]', 'CAST([nu_probabilidade] AS NVARCHAR)', 3, -1, FALSE, '[nu_probabilidade]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_probabilidade->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_probabilidade'] = &$this->nu_probabilidade;

		// nu_impacto
		$this->nu_impacto = new cField('riscoprojeto', 'riscoprojeto', 'x_nu_impacto', 'nu_impacto', '[nu_impacto]', 'CAST([nu_impacto] AS NVARCHAR)', 3, -1, FALSE, '[nu_impacto]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_impacto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_impacto'] = &$this->nu_impacto;

		// nu_severidade
		$this->nu_severidade = new cField('riscoprojeto', 'riscoprojeto', 'x_nu_severidade', 'nu_severidade', '[nu_severidade]', 'CAST([nu_severidade] AS NVARCHAR)', 3, -1, FALSE, '[nu_severidade]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_severidade->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_severidade'] = &$this->nu_severidade;

		// nu_acao
		$this->nu_acao = new cField('riscoprojeto', 'riscoprojeto', 'x_nu_acao', 'nu_acao', '[nu_acao]', 'CAST([nu_acao] AS NVARCHAR)', 3, -1, FALSE, '[nu_acao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_acao->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_acao'] = &$this->nu_acao;

		// ds_gatilho
		$this->ds_gatilho = new cField('riscoprojeto', 'riscoprojeto', 'x_ds_gatilho', 'ds_gatilho', '[ds_gatilho]', '[ds_gatilho]', 201, -1, FALSE, '[ds_gatilho]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_gatilho'] = &$this->ds_gatilho;

		// ds_respRisco
		$this->ds_respRisco = new cField('riscoprojeto', 'riscoprojeto', 'x_ds_respRisco', 'ds_respRisco', '[ds_respRisco]', '[ds_respRisco]', 201, -1, FALSE, '[ds_respRisco]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_respRisco'] = &$this->ds_respRisco;

		// nu_usuarioResp
		$this->nu_usuarioResp = new cField('riscoprojeto', 'riscoprojeto', 'x_nu_usuarioResp', 'nu_usuarioResp', '[nu_usuarioResp]', 'CAST([nu_usuarioResp] AS NVARCHAR)', 3, -1, FALSE, '[nu_usuarioResp]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_usuarioResp->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_usuarioResp'] = &$this->nu_usuarioResp;

		// ic_stRisco
		$this->ic_stRisco = new cField('riscoprojeto', 'riscoprojeto', 'x_ic_stRisco', 'ic_stRisco', '[ic_stRisco]', '[ic_stRisco]', 129, -1, FALSE, '[ic_stRisco]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_stRisco'] = &$this->ic_stRisco;
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
		if ($this->getCurrentMasterTable() == "projeto") {
			if ($this->nu_projeto->getSessionValue() <> "")
				$sMasterFilter .= "[nu_projeto]=" . ew_QuotedValue($this->nu_projeto->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "projeto") {
			if ($this->nu_projeto->getSessionValue() <> "")
				$sDetailFilter .= "[nu_projeto]=" . ew_QuotedValue($this->nu_projeto->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_projeto() {
		return "[nu_projeto]=@nu_projeto@";
	}

	// Detail filter
	function SqlDetailFilter_projeto() {
		return "[nu_projeto]=@nu_projeto@";
	}

	// Table level SQL
	function SqlFrom() { // From
		return "[dbo].[riscoprojeto]";
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
	var $UpdateTable = "[dbo].[riscoprojeto]";

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
			if (array_key_exists('nu_riscoProjeto', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_riscoProjeto') . '=' . ew_QuotedValue($rs['nu_riscoProjeto'], $this->nu_riscoProjeto->FldDataType));
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
		return "[nu_riscoProjeto] = @nu_riscoProjeto@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nu_riscoProjeto->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_riscoProjeto@", ew_AdjustSql($this->nu_riscoProjeto->CurrentValue), $sKeyFilter); // Replace key value
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
			return "riscoprojetolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "riscoprojetolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("riscoprojetoview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("riscoprojetoview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "riscoprojetoadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("riscoprojetoedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("riscoprojetoadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("riscoprojetodelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nu_riscoProjeto->CurrentValue)) {
			$sUrl .= "nu_riscoProjeto=" . urlencode($this->nu_riscoProjeto->CurrentValue);
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
			$arKeys[] = @$_GET["nu_riscoProjeto"]; // nu_riscoProjeto

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
			$this->nu_riscoProjeto->CurrentValue = $key;
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
		$this->nu_riscoProjeto->setDbValue($rs->fields('nu_riscoProjeto'));
		$this->nu_projeto->setDbValue($rs->fields('nu_projeto'));
		$this->nu_catRisco->setDbValue($rs->fields('nu_catRisco'));
		$this->ic_tpRisco->setDbValue($rs->fields('ic_tpRisco'));
		$this->ds_risco->setDbValue($rs->fields('ds_risco'));
		$this->ds_consequencia->setDbValue($rs->fields('ds_consequencia'));
		$this->nu_probabilidade->setDbValue($rs->fields('nu_probabilidade'));
		$this->nu_impacto->setDbValue($rs->fields('nu_impacto'));
		$this->nu_severidade->setDbValue($rs->fields('nu_severidade'));
		$this->nu_acao->setDbValue($rs->fields('nu_acao'));
		$this->ds_gatilho->setDbValue($rs->fields('ds_gatilho'));
		$this->ds_respRisco->setDbValue($rs->fields('ds_respRisco'));
		$this->nu_usuarioResp->setDbValue($rs->fields('nu_usuarioResp'));
		$this->ic_stRisco->setDbValue($rs->fields('ic_stRisco'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_riscoProjeto
		// nu_projeto
		// nu_catRisco
		// ic_tpRisco
		// ds_risco
		// ds_consequencia
		// nu_probabilidade
		// nu_impacto
		// nu_severidade
		// nu_acao
		// ds_gatilho
		// ds_respRisco
		// nu_usuarioResp
		// ic_stRisco
		// nu_riscoProjeto

		$this->nu_riscoProjeto->ViewValue = $this->nu_riscoProjeto->CurrentValue;
		$this->nu_riscoProjeto->ViewCustomAttributes = "";

		// nu_projeto
		if (strval($this->nu_projeto->CurrentValue) <> "") {
			$sFilterWrk = "[nu_projeto]" . ew_SearchString("=", $this->nu_projeto->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_projeto], [no_projeto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[projeto]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_projeto, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_projeto] DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_projeto->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_projeto->ViewValue = $this->nu_projeto->CurrentValue;
			}
		} else {
			$this->nu_projeto->ViewValue = NULL;
		}
		$this->nu_projeto->ViewCustomAttributes = "";

		// nu_catRisco
		if (strval($this->nu_catRisco->CurrentValue) <> "") {
			$sFilterWrk = "[nu_catRisco]" . ew_SearchString("=", $this->nu_catRisco->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_catRisco], [no_catRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[catriscoproj]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_catRisco, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_catRisco] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_catRisco->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_catRisco->ViewValue = $this->nu_catRisco->CurrentValue;
			}
		} else {
			$this->nu_catRisco->ViewValue = NULL;
		}
		$this->nu_catRisco->ViewCustomAttributes = "";

		// ic_tpRisco
		if (strval($this->ic_tpRisco->CurrentValue) <> "") {
			switch ($this->ic_tpRisco->CurrentValue) {
				case $this->ic_tpRisco->FldTagValue(1):
					$this->ic_tpRisco->ViewValue = $this->ic_tpRisco->FldTagCaption(1) <> "" ? $this->ic_tpRisco->FldTagCaption(1) : $this->ic_tpRisco->CurrentValue;
					break;
				case $this->ic_tpRisco->FldTagValue(2):
					$this->ic_tpRisco->ViewValue = $this->ic_tpRisco->FldTagCaption(2) <> "" ? $this->ic_tpRisco->FldTagCaption(2) : $this->ic_tpRisco->CurrentValue;
					break;
				default:
					$this->ic_tpRisco->ViewValue = $this->ic_tpRisco->CurrentValue;
			}
		} else {
			$this->ic_tpRisco->ViewValue = NULL;
		}
		$this->ic_tpRisco->ViewCustomAttributes = "";

		// ds_risco
		$this->ds_risco->ViewValue = $this->ds_risco->CurrentValue;
		$this->ds_risco->ViewCustomAttributes = "";

		// ds_consequencia
		$this->ds_consequencia->ViewValue = $this->ds_consequencia->CurrentValue;
		$this->ds_consequencia->ViewCustomAttributes = "";

		// nu_probabilidade
		if (strval($this->nu_probabilidade->CurrentValue) <> "") {
			$sFilterWrk = "[nu_probOcoRisco]" . ew_SearchString("=", $this->nu_probabilidade->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_probOcoRisco], [no_probOcoRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[probocorisco]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_probabilidade, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_valor] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_probabilidade->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_probabilidade->ViewValue = $this->nu_probabilidade->CurrentValue;
			}
		} else {
			$this->nu_probabilidade->ViewValue = NULL;
		}
		$this->nu_probabilidade->ViewCustomAttributes = "";

		// nu_impacto
		if (strval($this->nu_impacto->CurrentValue) <> "") {
			$sFilterWrk = "[nu_impactoRisco]" . ew_SearchString("=", $this->nu_impacto->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_impactoRisco], [no_impactoRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[impactorisco]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_impacto, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_valor] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_impacto->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_impacto->ViewValue = $this->nu_impacto->CurrentValue;
			}
		} else {
			$this->nu_impacto->ViewValue = NULL;
		}
		$this->nu_impacto->ViewCustomAttributes = "";

		// nu_severidade
		$this->nu_severidade->ViewValue = $this->nu_severidade->CurrentValue;
		$this->nu_severidade->ViewCustomAttributes = "";

		// nu_acao
		if (strval($this->nu_acao->CurrentValue) <> "") {
			$sFilterWrk = "[nu_acaoRisco]" . ew_SearchString("=", $this->nu_acao->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_acaoRisco], [no_acaoRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[acaorisco]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_acao, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_acaoRisco] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_acao->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_acao->ViewValue = $this->nu_acao->CurrentValue;
			}
		} else {
			$this->nu_acao->ViewValue = NULL;
		}
		$this->nu_acao->ViewCustomAttributes = "";

		// ds_gatilho
		$this->ds_gatilho->ViewValue = $this->ds_gatilho->CurrentValue;
		$this->ds_gatilho->ViewCustomAttributes = "";

		// ds_respRisco
		$this->ds_respRisco->ViewValue = $this->ds_respRisco->CurrentValue;
		$this->ds_respRisco->ViewCustomAttributes = "";

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

		// ic_stRisco
		if (strval($this->ic_stRisco->CurrentValue) <> "") {
			switch ($this->ic_stRisco->CurrentValue) {
				case $this->ic_stRisco->FldTagValue(1):
					$this->ic_stRisco->ViewValue = $this->ic_stRisco->FldTagCaption(1) <> "" ? $this->ic_stRisco->FldTagCaption(1) : $this->ic_stRisco->CurrentValue;
					break;
				case $this->ic_stRisco->FldTagValue(2):
					$this->ic_stRisco->ViewValue = $this->ic_stRisco->FldTagCaption(2) <> "" ? $this->ic_stRisco->FldTagCaption(2) : $this->ic_stRisco->CurrentValue;
					break;
				default:
					$this->ic_stRisco->ViewValue = $this->ic_stRisco->CurrentValue;
			}
		} else {
			$this->ic_stRisco->ViewValue = NULL;
		}
		$this->ic_stRisco->ViewCustomAttributes = "";

		// nu_riscoProjeto
		$this->nu_riscoProjeto->LinkCustomAttributes = "";
		$this->nu_riscoProjeto->HrefValue = "";
		$this->nu_riscoProjeto->TooltipValue = "";

		// nu_projeto
		$this->nu_projeto->LinkCustomAttributes = "";
		$this->nu_projeto->HrefValue = "";
		$this->nu_projeto->TooltipValue = "";

		// nu_catRisco
		$this->nu_catRisco->LinkCustomAttributes = "";
		$this->nu_catRisco->HrefValue = "";
		$this->nu_catRisco->TooltipValue = "";

		// ic_tpRisco
		$this->ic_tpRisco->LinkCustomAttributes = "";
		$this->ic_tpRisco->HrefValue = "";
		$this->ic_tpRisco->TooltipValue = "";

		// ds_risco
		$this->ds_risco->LinkCustomAttributes = "";
		$this->ds_risco->HrefValue = "";
		$this->ds_risco->TooltipValue = "";

		// ds_consequencia
		$this->ds_consequencia->LinkCustomAttributes = "";
		$this->ds_consequencia->HrefValue = "";
		$this->ds_consequencia->TooltipValue = "";

		// nu_probabilidade
		$this->nu_probabilidade->LinkCustomAttributes = "";
		$this->nu_probabilidade->HrefValue = "";
		$this->nu_probabilidade->TooltipValue = "";

		// nu_impacto
		$this->nu_impacto->LinkCustomAttributes = "";
		$this->nu_impacto->HrefValue = "";
		$this->nu_impacto->TooltipValue = "";

		// nu_severidade
		$this->nu_severidade->LinkCustomAttributes = "";
		$this->nu_severidade->HrefValue = "";
		$this->nu_severidade->TooltipValue = "";

		// nu_acao
		$this->nu_acao->LinkCustomAttributes = "";
		$this->nu_acao->HrefValue = "";
		$this->nu_acao->TooltipValue = "";

		// ds_gatilho
		$this->ds_gatilho->LinkCustomAttributes = "";
		$this->ds_gatilho->HrefValue = "";
		$this->ds_gatilho->TooltipValue = "";

		// ds_respRisco
		$this->ds_respRisco->LinkCustomAttributes = "";
		$this->ds_respRisco->HrefValue = "";
		$this->ds_respRisco->TooltipValue = "";

		// nu_usuarioResp
		$this->nu_usuarioResp->LinkCustomAttributes = "";
		$this->nu_usuarioResp->HrefValue = "";
		$this->nu_usuarioResp->TooltipValue = "";

		// ic_stRisco
		$this->ic_stRisco->LinkCustomAttributes = "";
		$this->ic_stRisco->HrefValue = "";
		$this->ic_stRisco->TooltipValue = "";

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
				if ($this->nu_riscoProjeto->Exportable) $Doc->ExportCaption($this->nu_riscoProjeto);
				if ($this->nu_projeto->Exportable) $Doc->ExportCaption($this->nu_projeto);
				if ($this->nu_catRisco->Exportable) $Doc->ExportCaption($this->nu_catRisco);
				if ($this->ic_tpRisco->Exportable) $Doc->ExportCaption($this->ic_tpRisco);
				if ($this->ds_risco->Exportable) $Doc->ExportCaption($this->ds_risco);
				if ($this->ds_consequencia->Exportable) $Doc->ExportCaption($this->ds_consequencia);
				if ($this->nu_probabilidade->Exportable) $Doc->ExportCaption($this->nu_probabilidade);
				if ($this->nu_impacto->Exportable) $Doc->ExportCaption($this->nu_impacto);
				if ($this->nu_severidade->Exportable) $Doc->ExportCaption($this->nu_severidade);
				if ($this->nu_acao->Exportable) $Doc->ExportCaption($this->nu_acao);
				if ($this->ds_gatilho->Exportable) $Doc->ExportCaption($this->ds_gatilho);
				if ($this->ds_respRisco->Exportable) $Doc->ExportCaption($this->ds_respRisco);
				if ($this->nu_usuarioResp->Exportable) $Doc->ExportCaption($this->nu_usuarioResp);
				if ($this->ic_stRisco->Exportable) $Doc->ExportCaption($this->ic_stRisco);
			} else {
				if ($this->nu_riscoProjeto->Exportable) $Doc->ExportCaption($this->nu_riscoProjeto);
				if ($this->nu_projeto->Exportable) $Doc->ExportCaption($this->nu_projeto);
				if ($this->nu_catRisco->Exportable) $Doc->ExportCaption($this->nu_catRisco);
				if ($this->ic_tpRisco->Exportable) $Doc->ExportCaption($this->ic_tpRisco);
				if ($this->nu_probabilidade->Exportable) $Doc->ExportCaption($this->nu_probabilidade);
				if ($this->nu_impacto->Exportable) $Doc->ExportCaption($this->nu_impacto);
				if ($this->nu_severidade->Exportable) $Doc->ExportCaption($this->nu_severidade);
				if ($this->nu_acao->Exportable) $Doc->ExportCaption($this->nu_acao);
				if ($this->nu_usuarioResp->Exportable) $Doc->ExportCaption($this->nu_usuarioResp);
				if ($this->ic_stRisco->Exportable) $Doc->ExportCaption($this->ic_stRisco);
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
					if ($this->nu_riscoProjeto->Exportable) $Doc->ExportField($this->nu_riscoProjeto);
					if ($this->nu_projeto->Exportable) $Doc->ExportField($this->nu_projeto);
					if ($this->nu_catRisco->Exportable) $Doc->ExportField($this->nu_catRisco);
					if ($this->ic_tpRisco->Exportable) $Doc->ExportField($this->ic_tpRisco);
					if ($this->ds_risco->Exportable) $Doc->ExportField($this->ds_risco);
					if ($this->ds_consequencia->Exportable) $Doc->ExportField($this->ds_consequencia);
					if ($this->nu_probabilidade->Exportable) $Doc->ExportField($this->nu_probabilidade);
					if ($this->nu_impacto->Exportable) $Doc->ExportField($this->nu_impacto);
					if ($this->nu_severidade->Exportable) $Doc->ExportField($this->nu_severidade);
					if ($this->nu_acao->Exportable) $Doc->ExportField($this->nu_acao);
					if ($this->ds_gatilho->Exportable) $Doc->ExportField($this->ds_gatilho);
					if ($this->ds_respRisco->Exportable) $Doc->ExportField($this->ds_respRisco);
					if ($this->nu_usuarioResp->Exportable) $Doc->ExportField($this->nu_usuarioResp);
					if ($this->ic_stRisco->Exportable) $Doc->ExportField($this->ic_stRisco);
				} else {
					if ($this->nu_riscoProjeto->Exportable) $Doc->ExportField($this->nu_riscoProjeto);
					if ($this->nu_projeto->Exportable) $Doc->ExportField($this->nu_projeto);
					if ($this->nu_catRisco->Exportable) $Doc->ExportField($this->nu_catRisco);
					if ($this->ic_tpRisco->Exportable) $Doc->ExportField($this->ic_tpRisco);
					if ($this->nu_probabilidade->Exportable) $Doc->ExportField($this->nu_probabilidade);
					if ($this->nu_impacto->Exportable) $Doc->ExportField($this->nu_impacto);
					if ($this->nu_severidade->Exportable) $Doc->ExportField($this->nu_severidade);
					if ($this->nu_acao->Exportable) $Doc->ExportField($this->nu_acao);
					if ($this->nu_usuarioResp->Exportable) $Doc->ExportField($this->nu_usuarioResp);
					if ($this->ic_stRisco->Exportable) $Doc->ExportField($this->ic_stRisco);
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
