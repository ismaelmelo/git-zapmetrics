<?php

// Global variable for table object
$ambiente_phistorico = NULL;

//
// Table class for ambiente_phistorico
//
class cambiente_phistorico extends cTable {
	var $nu_projhist;
	var $nu_ambiente;
	var $no_projeto;
	var $ds_projeto;
	var $qt_pf;
	var $qt_sloc;
	var $qt_slocPf;
	var $qt_esforcoReal;
	var $qt_esforcoRealPm;
	var $qt_prazoRealM;
	var $ic_situacao;
	var $ds_acoes;
	var $nu_usuarioInc;
	var $dh_inclusao;
	var $nu_usuarioAlt;
	var $dh_alteracao;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'ambiente_phistorico';
		$this->TableName = 'ambiente_phistorico';
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

		// nu_projhist
		$this->nu_projhist = new cField('ambiente_phistorico', 'ambiente_phistorico', 'x_nu_projhist', 'nu_projhist', '[nu_projhist]', 'CAST([nu_projhist] AS NVARCHAR)', 3, -1, FALSE, '[nu_projhist]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_projhist->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_projhist'] = &$this->nu_projhist;

		// nu_ambiente
		$this->nu_ambiente = new cField('ambiente_phistorico', 'ambiente_phistorico', 'x_nu_ambiente', 'nu_ambiente', '[nu_ambiente]', 'CAST([nu_ambiente] AS NVARCHAR)', 3, -1, FALSE, '[nu_ambiente]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_ambiente->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_ambiente'] = &$this->nu_ambiente;

		// no_projeto
		$this->no_projeto = new cField('ambiente_phistorico', 'ambiente_phistorico', 'x_no_projeto', 'no_projeto', '[no_projeto]', '[no_projeto]', 200, -1, FALSE, '[no_projeto]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_projeto'] = &$this->no_projeto;

		// ds_projeto
		$this->ds_projeto = new cField('ambiente_phistorico', 'ambiente_phistorico', 'x_ds_projeto', 'ds_projeto', '[ds_projeto]', '[ds_projeto]', 201, -1, FALSE, '[ds_projeto]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_projeto'] = &$this->ds_projeto;

		// qt_pf
		$this->qt_pf = new cField('ambiente_phistorico', 'ambiente_phistorico', 'x_qt_pf', 'qt_pf', '[qt_pf]', 'CAST([qt_pf] AS NVARCHAR)', 131, -1, FALSE, '[qt_pf]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->qt_pf->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['qt_pf'] = &$this->qt_pf;

		// qt_sloc
		$this->qt_sloc = new cField('ambiente_phistorico', 'ambiente_phistorico', 'x_qt_sloc', 'qt_sloc', '[qt_sloc]', 'CAST([qt_sloc] AS NVARCHAR)', 131, -1, FALSE, '[qt_sloc]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->qt_sloc->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['qt_sloc'] = &$this->qt_sloc;

		// qt_slocPf
		$this->qt_slocPf = new cField('ambiente_phistorico', 'ambiente_phistorico', 'x_qt_slocPf', 'qt_slocPf', '[qt_slocPf]', 'CAST([qt_slocPf] AS NVARCHAR)', 131, -1, FALSE, '[qt_slocPf]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->qt_slocPf->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['qt_slocPf'] = &$this->qt_slocPf;

		// qt_esforcoReal
		$this->qt_esforcoReal = new cField('ambiente_phistorico', 'ambiente_phistorico', 'x_qt_esforcoReal', 'qt_esforcoReal', '[qt_esforcoReal]', 'CAST([qt_esforcoReal] AS NVARCHAR)', 131, -1, FALSE, '[qt_esforcoReal]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->qt_esforcoReal->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['qt_esforcoReal'] = &$this->qt_esforcoReal;

		// qt_esforcoRealPm
		$this->qt_esforcoRealPm = new cField('ambiente_phistorico', 'ambiente_phistorico', 'x_qt_esforcoRealPm', 'qt_esforcoRealPm', '[qt_esforcoRealPm]', 'CAST([qt_esforcoRealPm] AS NVARCHAR)', 131, -1, FALSE, '[qt_esforcoRealPm]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->qt_esforcoRealPm->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['qt_esforcoRealPm'] = &$this->qt_esforcoRealPm;

		// qt_prazoRealM
		$this->qt_prazoRealM = new cField('ambiente_phistorico', 'ambiente_phistorico', 'x_qt_prazoRealM', 'qt_prazoRealM', '[qt_prazoRealM]', 'CAST([qt_prazoRealM] AS NVARCHAR)', 131, -1, FALSE, '[qt_prazoRealM]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->qt_prazoRealM->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['qt_prazoRealM'] = &$this->qt_prazoRealM;

		// ic_situacao
		$this->ic_situacao = new cField('ambiente_phistorico', 'ambiente_phistorico', 'x_ic_situacao', 'ic_situacao', '[ic_situacao]', '[ic_situacao]', 129, -1, FALSE, '[ic_situacao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_situacao'] = &$this->ic_situacao;

		// ds_acoes
		$this->ds_acoes = new cField('ambiente_phistorico', 'ambiente_phistorico', 'x_ds_acoes', 'ds_acoes', '[ds_acoes]', '[ds_acoes]', 201, -1, FALSE, '[ds_acoes]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_acoes'] = &$this->ds_acoes;

		// nu_usuarioInc
		$this->nu_usuarioInc = new cField('ambiente_phistorico', 'ambiente_phistorico', 'x_nu_usuarioInc', 'nu_usuarioInc', '[nu_usuarioInc]', 'CAST([nu_usuarioInc] AS NVARCHAR)', 3, -1, FALSE, '[nu_usuarioInc]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_usuarioInc->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_usuarioInc'] = &$this->nu_usuarioInc;

		// dh_inclusao
		$this->dh_inclusao = new cField('ambiente_phistorico', 'ambiente_phistorico', 'x_dh_inclusao', 'dh_inclusao', '[dh_inclusao]', '(REPLACE(STR(DAY([dh_inclusao]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dh_inclusao]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dh_inclusao]),4,0))', 135, 7, FALSE, '[dh_inclusao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dh_inclusao->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dh_inclusao'] = &$this->dh_inclusao;

		// nu_usuarioAlt
		$this->nu_usuarioAlt = new cField('ambiente_phistorico', 'ambiente_phistorico', 'x_nu_usuarioAlt', 'nu_usuarioAlt', '[nu_usuarioAlt]', 'CAST([nu_usuarioAlt] AS NVARCHAR)', 3, -1, FALSE, '[nu_usuarioAlt]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_usuarioAlt->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_usuarioAlt'] = &$this->nu_usuarioAlt;

		// dh_alteracao
		$this->dh_alteracao = new cField('ambiente_phistorico', 'ambiente_phistorico', 'x_dh_alteracao', 'dh_alteracao', '[dh_alteracao]', '(REPLACE(STR(DAY([dh_alteracao]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dh_alteracao]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dh_alteracao]),4,0))', 135, 7, FALSE, '[dh_alteracao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dh_alteracao->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dh_alteracao'] = &$this->dh_alteracao;
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
		if ($this->getCurrentMasterTable() == "ambiente") {
			if ($this->nu_ambiente->getSessionValue() <> "")
				$sMasterFilter .= "[nu_ambiente]=" . ew_QuotedValue($this->nu_ambiente->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "ambiente") {
			if ($this->nu_ambiente->getSessionValue() <> "")
				$sDetailFilter .= "[nu_ambiente]=" . ew_QuotedValue($this->nu_ambiente->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_ambiente() {
		return "[nu_ambiente]=@nu_ambiente@";
	}

	// Detail filter
	function SqlDetailFilter_ambiente() {
		return "[nu_ambiente]=@nu_ambiente@";
	}

	// Table level SQL
	function SqlFrom() { // From
		return "[dbo].[ambiente_phistorico]";
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
	var $UpdateTable = "[dbo].[ambiente_phistorico]";

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
			if (array_key_exists('nu_projhist', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_projhist') . '=' . ew_QuotedValue($rs['nu_projhist'], $this->nu_projhist->FldDataType));
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
		return "[nu_projhist] = @nu_projhist@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nu_projhist->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_projhist@", ew_AdjustSql($this->nu_projhist->CurrentValue), $sKeyFilter); // Replace key value
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
			return "ambiente_phistoricolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "ambiente_phistoricolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("ambiente_phistoricoview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("ambiente_phistoricoview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "ambiente_phistoricoadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("ambiente_phistoricoedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("ambiente_phistoricoadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("ambiente_phistoricodelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nu_projhist->CurrentValue)) {
			$sUrl .= "nu_projhist=" . urlencode($this->nu_projhist->CurrentValue);
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
			$arKeys[] = @$_GET["nu_projhist"]; // nu_projhist

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
			$this->nu_projhist->CurrentValue = $key;
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
		$this->nu_projhist->setDbValue($rs->fields('nu_projhist'));
		$this->nu_ambiente->setDbValue($rs->fields('nu_ambiente'));
		$this->no_projeto->setDbValue($rs->fields('no_projeto'));
		$this->ds_projeto->setDbValue($rs->fields('ds_projeto'));
		$this->qt_pf->setDbValue($rs->fields('qt_pf'));
		$this->qt_sloc->setDbValue($rs->fields('qt_sloc'));
		$this->qt_slocPf->setDbValue($rs->fields('qt_slocPf'));
		$this->qt_esforcoReal->setDbValue($rs->fields('qt_esforcoReal'));
		$this->qt_esforcoRealPm->setDbValue($rs->fields('qt_esforcoRealPm'));
		$this->qt_prazoRealM->setDbValue($rs->fields('qt_prazoRealM'));
		$this->ic_situacao->setDbValue($rs->fields('ic_situacao'));
		$this->ds_acoes->setDbValue($rs->fields('ds_acoes'));
		$this->nu_usuarioInc->setDbValue($rs->fields('nu_usuarioInc'));
		$this->dh_inclusao->setDbValue($rs->fields('dh_inclusao'));
		$this->nu_usuarioAlt->setDbValue($rs->fields('nu_usuarioAlt'));
		$this->dh_alteracao->setDbValue($rs->fields('dh_alteracao'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_projhist
		// nu_ambiente
		// no_projeto
		// ds_projeto
		// qt_pf
		// qt_sloc
		// qt_slocPf
		// qt_esforcoReal
		// qt_esforcoRealPm
		// qt_prazoRealM
		// ic_situacao
		// ds_acoes
		// nu_usuarioInc
		// dh_inclusao
		// nu_usuarioAlt
		// dh_alteracao
		// nu_projhist

		$this->nu_projhist->ViewValue = $this->nu_projhist->CurrentValue;
		$this->nu_projhist->ViewCustomAttributes = "";

		// nu_ambiente
		if (strval($this->nu_ambiente->CurrentValue) <> "") {
			$sFilterWrk = "[nu_ambiente]" . ew_SearchString("=", $this->nu_ambiente->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_ambiente], [no_ambiente] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ambiente]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_ambiente, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_ambiente] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_ambiente->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_ambiente->ViewValue = $this->nu_ambiente->CurrentValue;
			}
		} else {
			$this->nu_ambiente->ViewValue = NULL;
		}
		$this->nu_ambiente->ViewCustomAttributes = "";

		// no_projeto
		$this->no_projeto->ViewValue = $this->no_projeto->CurrentValue;
		$this->no_projeto->ViewCustomAttributes = "";

		// ds_projeto
		$this->ds_projeto->ViewValue = $this->ds_projeto->CurrentValue;
		$this->ds_projeto->ViewCustomAttributes = "";

		// qt_pf
		$this->qt_pf->ViewValue = $this->qt_pf->CurrentValue;
		$this->qt_pf->ViewCustomAttributes = "";

		// qt_sloc
		$this->qt_sloc->ViewValue = $this->qt_sloc->CurrentValue;
		$this->qt_sloc->ViewCustomAttributes = "";

		// qt_slocPf
		$this->qt_slocPf->ViewValue = $this->qt_slocPf->CurrentValue;
		$this->qt_slocPf->ViewCustomAttributes = "";

		// qt_esforcoReal
		$this->qt_esforcoReal->ViewValue = $this->qt_esforcoReal->CurrentValue;
		$this->qt_esforcoReal->ViewCustomAttributes = "";

		// qt_esforcoRealPm
		$this->qt_esforcoRealPm->ViewValue = $this->qt_esforcoRealPm->CurrentValue;
		$this->qt_esforcoRealPm->ViewCustomAttributes = "";

		// qt_prazoRealM
		$this->qt_prazoRealM->ViewValue = $this->qt_prazoRealM->CurrentValue;
		$this->qt_prazoRealM->ViewCustomAttributes = "";

		// ic_situacao
		if (strval($this->ic_situacao->CurrentValue) <> "") {
			switch ($this->ic_situacao->CurrentValue) {
				case $this->ic_situacao->FldTagValue(1):
					$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(1) <> "" ? $this->ic_situacao->FldTagCaption(1) : $this->ic_situacao->CurrentValue;
					break;
				case $this->ic_situacao->FldTagValue(2):
					$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(2) <> "" ? $this->ic_situacao->FldTagCaption(2) : $this->ic_situacao->CurrentValue;
					break;
				default:
					$this->ic_situacao->ViewValue = $this->ic_situacao->CurrentValue;
			}
		} else {
			$this->ic_situacao->ViewValue = NULL;
		}
		$this->ic_situacao->ViewCustomAttributes = "";

		// ds_acoes
		$this->ds_acoes->ViewValue = $this->ds_acoes->CurrentValue;
		$this->ds_acoes->ViewCustomAttributes = "";

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
		$this->dh_inclusao->ViewValue = ew_FormatDateTime($this->dh_inclusao->ViewValue, 7);
		$this->dh_inclusao->ViewCustomAttributes = "";

		// nu_usuarioAlt
		$this->nu_usuarioAlt->ViewValue = $this->nu_usuarioAlt->CurrentValue;
		if (strval($this->nu_usuarioAlt->CurrentValue) <> "") {
			$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioAlt->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_usuarioAlt, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_usuarioAlt->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_usuarioAlt->ViewValue = $this->nu_usuarioAlt->CurrentValue;
			}
		} else {
			$this->nu_usuarioAlt->ViewValue = NULL;
		}
		$this->nu_usuarioAlt->ViewCustomAttributes = "";

		// dh_alteracao
		$this->dh_alteracao->ViewValue = $this->dh_alteracao->CurrentValue;
		$this->dh_alteracao->ViewValue = ew_FormatDateTime($this->dh_alteracao->ViewValue, 7);
		$this->dh_alteracao->ViewCustomAttributes = "";

		// nu_projhist
		$this->nu_projhist->LinkCustomAttributes = "";
		$this->nu_projhist->HrefValue = "";
		$this->nu_projhist->TooltipValue = "";

		// nu_ambiente
		$this->nu_ambiente->LinkCustomAttributes = "";
		$this->nu_ambiente->HrefValue = "";
		$this->nu_ambiente->TooltipValue = "";

		// no_projeto
		$this->no_projeto->LinkCustomAttributes = "";
		$this->no_projeto->HrefValue = "";
		$this->no_projeto->TooltipValue = "";

		// ds_projeto
		$this->ds_projeto->LinkCustomAttributes = "";
		$this->ds_projeto->HrefValue = "";
		$this->ds_projeto->TooltipValue = "";

		// qt_pf
		$this->qt_pf->LinkCustomAttributes = "";
		$this->qt_pf->HrefValue = "";
		$this->qt_pf->TooltipValue = "";

		// qt_sloc
		$this->qt_sloc->LinkCustomAttributes = "";
		$this->qt_sloc->HrefValue = "";
		$this->qt_sloc->TooltipValue = "";

		// qt_slocPf
		$this->qt_slocPf->LinkCustomAttributes = "";
		$this->qt_slocPf->HrefValue = "";
		$this->qt_slocPf->TooltipValue = "";

		// qt_esforcoReal
		$this->qt_esforcoReal->LinkCustomAttributes = "";
		$this->qt_esforcoReal->HrefValue = "";
		$this->qt_esforcoReal->TooltipValue = "";

		// qt_esforcoRealPm
		$this->qt_esforcoRealPm->LinkCustomAttributes = "";
		$this->qt_esforcoRealPm->HrefValue = "";
		$this->qt_esforcoRealPm->TooltipValue = "";

		// qt_prazoRealM
		$this->qt_prazoRealM->LinkCustomAttributes = "";
		$this->qt_prazoRealM->HrefValue = "";
		$this->qt_prazoRealM->TooltipValue = "";

		// ic_situacao
		$this->ic_situacao->LinkCustomAttributes = "";
		$this->ic_situacao->HrefValue = "";
		$this->ic_situacao->TooltipValue = "";

		// ds_acoes
		$this->ds_acoes->LinkCustomAttributes = "";
		$this->ds_acoes->HrefValue = "";
		$this->ds_acoes->TooltipValue = "";

		// nu_usuarioInc
		$this->nu_usuarioInc->LinkCustomAttributes = "";
		$this->nu_usuarioInc->HrefValue = "";
		$this->nu_usuarioInc->TooltipValue = "";

		// dh_inclusao
		$this->dh_inclusao->LinkCustomAttributes = "";
		$this->dh_inclusao->HrefValue = "";
		$this->dh_inclusao->TooltipValue = "";

		// nu_usuarioAlt
		$this->nu_usuarioAlt->LinkCustomAttributes = "";
		$this->nu_usuarioAlt->HrefValue = "";
		$this->nu_usuarioAlt->TooltipValue = "";

		// dh_alteracao
		$this->dh_alteracao->LinkCustomAttributes = "";
		$this->dh_alteracao->HrefValue = "";
		$this->dh_alteracao->TooltipValue = "";

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
				if ($this->nu_projhist->Exportable) $Doc->ExportCaption($this->nu_projhist);
				if ($this->nu_ambiente->Exportable) $Doc->ExportCaption($this->nu_ambiente);
				if ($this->no_projeto->Exportable) $Doc->ExportCaption($this->no_projeto);
				if ($this->ds_projeto->Exportable) $Doc->ExportCaption($this->ds_projeto);
				if ($this->qt_pf->Exportable) $Doc->ExportCaption($this->qt_pf);
				if ($this->qt_sloc->Exportable) $Doc->ExportCaption($this->qt_sloc);
				if ($this->qt_slocPf->Exportable) $Doc->ExportCaption($this->qt_slocPf);
				if ($this->qt_esforcoReal->Exportable) $Doc->ExportCaption($this->qt_esforcoReal);
				if ($this->qt_esforcoRealPm->Exportable) $Doc->ExportCaption($this->qt_esforcoRealPm);
				if ($this->qt_prazoRealM->Exportable) $Doc->ExportCaption($this->qt_prazoRealM);
				if ($this->ic_situacao->Exportable) $Doc->ExportCaption($this->ic_situacao);
				if ($this->ds_acoes->Exportable) $Doc->ExportCaption($this->ds_acoes);
				if ($this->nu_usuarioInc->Exportable) $Doc->ExportCaption($this->nu_usuarioInc);
				if ($this->dh_inclusao->Exportable) $Doc->ExportCaption($this->dh_inclusao);
				if ($this->nu_usuarioAlt->Exportable) $Doc->ExportCaption($this->nu_usuarioAlt);
				if ($this->dh_alteracao->Exportable) $Doc->ExportCaption($this->dh_alteracao);
			} else {
				if ($this->nu_projhist->Exportable) $Doc->ExportCaption($this->nu_projhist);
				if ($this->nu_ambiente->Exportable) $Doc->ExportCaption($this->nu_ambiente);
				if ($this->no_projeto->Exportable) $Doc->ExportCaption($this->no_projeto);
				if ($this->qt_pf->Exportable) $Doc->ExportCaption($this->qt_pf);
				if ($this->qt_sloc->Exportable) $Doc->ExportCaption($this->qt_sloc);
				if ($this->qt_slocPf->Exportable) $Doc->ExportCaption($this->qt_slocPf);
				if ($this->qt_esforcoReal->Exportable) $Doc->ExportCaption($this->qt_esforcoReal);
				if ($this->qt_esforcoRealPm->Exportable) $Doc->ExportCaption($this->qt_esforcoRealPm);
				if ($this->qt_prazoRealM->Exportable) $Doc->ExportCaption($this->qt_prazoRealM);
				if ($this->ic_situacao->Exportable) $Doc->ExportCaption($this->ic_situacao);
				if ($this->nu_usuarioInc->Exportable) $Doc->ExportCaption($this->nu_usuarioInc);
				if ($this->dh_inclusao->Exportable) $Doc->ExportCaption($this->dh_inclusao);
				if ($this->nu_usuarioAlt->Exportable) $Doc->ExportCaption($this->nu_usuarioAlt);
				if ($this->dh_alteracao->Exportable) $Doc->ExportCaption($this->dh_alteracao);
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
					if ($this->nu_projhist->Exportable) $Doc->ExportField($this->nu_projhist);
					if ($this->nu_ambiente->Exportable) $Doc->ExportField($this->nu_ambiente);
					if ($this->no_projeto->Exportable) $Doc->ExportField($this->no_projeto);
					if ($this->ds_projeto->Exportable) $Doc->ExportField($this->ds_projeto);
					if ($this->qt_pf->Exportable) $Doc->ExportField($this->qt_pf);
					if ($this->qt_sloc->Exportable) $Doc->ExportField($this->qt_sloc);
					if ($this->qt_slocPf->Exportable) $Doc->ExportField($this->qt_slocPf);
					if ($this->qt_esforcoReal->Exportable) $Doc->ExportField($this->qt_esforcoReal);
					if ($this->qt_esforcoRealPm->Exportable) $Doc->ExportField($this->qt_esforcoRealPm);
					if ($this->qt_prazoRealM->Exportable) $Doc->ExportField($this->qt_prazoRealM);
					if ($this->ic_situacao->Exportable) $Doc->ExportField($this->ic_situacao);
					if ($this->ds_acoes->Exportable) $Doc->ExportField($this->ds_acoes);
					if ($this->nu_usuarioInc->Exportable) $Doc->ExportField($this->nu_usuarioInc);
					if ($this->dh_inclusao->Exportable) $Doc->ExportField($this->dh_inclusao);
					if ($this->nu_usuarioAlt->Exportable) $Doc->ExportField($this->nu_usuarioAlt);
					if ($this->dh_alteracao->Exportable) $Doc->ExportField($this->dh_alteracao);
				} else {
					if ($this->nu_projhist->Exportable) $Doc->ExportField($this->nu_projhist);
					if ($this->nu_ambiente->Exportable) $Doc->ExportField($this->nu_ambiente);
					if ($this->no_projeto->Exportable) $Doc->ExportField($this->no_projeto);
					if ($this->qt_pf->Exportable) $Doc->ExportField($this->qt_pf);
					if ($this->qt_sloc->Exportable) $Doc->ExportField($this->qt_sloc);
					if ($this->qt_slocPf->Exportable) $Doc->ExportField($this->qt_slocPf);
					if ($this->qt_esforcoReal->Exportable) $Doc->ExportField($this->qt_esforcoReal);
					if ($this->qt_esforcoRealPm->Exportable) $Doc->ExportField($this->qt_esforcoRealPm);
					if ($this->qt_prazoRealM->Exportable) $Doc->ExportField($this->qt_prazoRealM);
					if ($this->ic_situacao->Exportable) $Doc->ExportField($this->ic_situacao);
					if ($this->nu_usuarioInc->Exportable) $Doc->ExportField($this->nu_usuarioInc);
					if ($this->dh_inclusao->Exportable) $Doc->ExportField($this->dh_inclusao);
					if ($this->nu_usuarioAlt->Exportable) $Doc->ExportField($this->nu_usuarioAlt);
					if ($this->dh_alteracao->Exportable) $Doc->ExportField($this->dh_alteracao);
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
