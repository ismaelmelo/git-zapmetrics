<?php

// Global variable for table object
$vwrdm_repositories = NULL;

//
// Table class for vwrdm_repositories
//
class cvwrdm_repositories extends cTable {
	var $id;
	var $project_id;
	var $url;
	var $_login;
	var $password;
	var $root_url;
	var $type;
	var $path_encoding;
	var $log_encoding;
	var $extra_info;
	var $identifier;
	var $is_default;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'vwrdm_repositories';
		$this->TableName = 'vwrdm_repositories';
		$this->TableType = 'VIEW';
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

		// id
		$this->id = new cField('vwrdm_repositories', 'vwrdm_repositories', 'x_id', 'id', '[id]', 'CAST([id] AS NVARCHAR)', 3, -1, FALSE, '[id]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// project_id
		$this->project_id = new cField('vwrdm_repositories', 'vwrdm_repositories', 'x_project_id', 'project_id', '[project_id]', 'CAST([project_id] AS NVARCHAR)', 3, -1, FALSE, '[project_id]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->project_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['project_id'] = &$this->project_id;

		// url
		$this->url = new cField('vwrdm_repositories', 'vwrdm_repositories', 'x_url', 'url', '[url]', '[url]', 202, -1, FALSE, '[url]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['url'] = &$this->url;

		// login
		$this->_login = new cField('vwrdm_repositories', 'vwrdm_repositories', 'x__login', 'login', '[login]', '[login]', 202, -1, FALSE, '[login]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['login'] = &$this->_login;

		// password
		$this->password = new cField('vwrdm_repositories', 'vwrdm_repositories', 'x_password', 'password', '[password]', '[password]', 202, -1, FALSE, '[password]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['password'] = &$this->password;

		// root_url
		$this->root_url = new cField('vwrdm_repositories', 'vwrdm_repositories', 'x_root_url', 'root_url', '[root_url]', '[root_url]', 202, -1, FALSE, '[root_url]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['root_url'] = &$this->root_url;

		// type
		$this->type = new cField('vwrdm_repositories', 'vwrdm_repositories', 'x_type', 'type', '[type]', '[type]', 202, -1, FALSE, '[type]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['type'] = &$this->type;

		// path_encoding
		$this->path_encoding = new cField('vwrdm_repositories', 'vwrdm_repositories', 'x_path_encoding', 'path_encoding', '[path_encoding]', '[path_encoding]', 202, -1, FALSE, '[path_encoding]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['path_encoding'] = &$this->path_encoding;

		// log_encoding
		$this->log_encoding = new cField('vwrdm_repositories', 'vwrdm_repositories', 'x_log_encoding', 'log_encoding', '[log_encoding]', '[log_encoding]', 202, -1, FALSE, '[log_encoding]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['log_encoding'] = &$this->log_encoding;

		// extra_info
		$this->extra_info = new cField('vwrdm_repositories', 'vwrdm_repositories', 'x_extra_info', 'extra_info', '[extra_info]', '[extra_info]', 203, -1, FALSE, '[extra_info]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['extra_info'] = &$this->extra_info;

		// identifier
		$this->identifier = new cField('vwrdm_repositories', 'vwrdm_repositories', 'x_identifier', 'identifier', '[identifier]', '[identifier]', 202, -1, FALSE, '[identifier]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['identifier'] = &$this->identifier;

		// is_default
		$this->is_default = new cField('vwrdm_repositories', 'vwrdm_repositories', 'x_is_default', 'is_default', '[is_default]', 'CAST([is_default] AS NVARCHAR)', 131, -1, FALSE, '[is_default]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->is_default->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['is_default'] = &$this->is_default;
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
		return "[db_owner].[vwrdm_repositories]";
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
	var $UpdateTable = "[db_owner].[vwrdm_repositories]";

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
		return "";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
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
			return "vwrdm_repositorieslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "vwrdm_repositorieslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("vwrdm_repositoriesview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("vwrdm_repositoriesview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "vwrdm_repositoriesadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("vwrdm_repositoriesedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("vwrdm_repositoriesadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("vwrdm_repositoriesdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
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

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
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
		$this->id->setDbValue($rs->fields('id'));
		$this->project_id->setDbValue($rs->fields('project_id'));
		$this->url->setDbValue($rs->fields('url'));
		$this->_login->setDbValue($rs->fields('login'));
		$this->password->setDbValue($rs->fields('password'));
		$this->root_url->setDbValue($rs->fields('root_url'));
		$this->type->setDbValue($rs->fields('type'));
		$this->path_encoding->setDbValue($rs->fields('path_encoding'));
		$this->log_encoding->setDbValue($rs->fields('log_encoding'));
		$this->extra_info->setDbValue($rs->fields('extra_info'));
		$this->identifier->setDbValue($rs->fields('identifier'));
		$this->is_default->setDbValue($rs->fields('is_default'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id
		// project_id
		// url
		// login
		// password
		// root_url
		// type
		// path_encoding
		// log_encoding
		// extra_info
		// identifier
		// is_default
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// project_id
		$this->project_id->ViewValue = $this->project_id->CurrentValue;
		$this->project_id->ViewCustomAttributes = "";

		// url
		$this->url->ViewValue = $this->url->CurrentValue;
		$this->url->ViewCustomAttributes = "";

		// login
		$this->_login->ViewValue = $this->_login->CurrentValue;
		$this->_login->ViewCustomAttributes = "";

		// password
		$this->password->ViewValue = $this->password->CurrentValue;
		$this->password->ViewCustomAttributes = "";

		// root_url
		$this->root_url->ViewValue = $this->root_url->CurrentValue;
		$this->root_url->ViewCustomAttributes = "";

		// type
		$this->type->ViewValue = $this->type->CurrentValue;
		$this->type->ViewCustomAttributes = "";

		// path_encoding
		$this->path_encoding->ViewValue = $this->path_encoding->CurrentValue;
		$this->path_encoding->ViewCustomAttributes = "";

		// log_encoding
		$this->log_encoding->ViewValue = $this->log_encoding->CurrentValue;
		$this->log_encoding->ViewCustomAttributes = "";

		// extra_info
		$this->extra_info->ViewValue = $this->extra_info->CurrentValue;
		$this->extra_info->ViewCustomAttributes = "";

		// identifier
		$this->identifier->ViewValue = $this->identifier->CurrentValue;
		$this->identifier->ViewCustomAttributes = "";

		// is_default
		$this->is_default->ViewValue = $this->is_default->CurrentValue;
		$this->is_default->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// project_id
		$this->project_id->LinkCustomAttributes = "";
		$this->project_id->HrefValue = "";
		$this->project_id->TooltipValue = "";

		// url
		$this->url->LinkCustomAttributes = "";
		$this->url->HrefValue = "";
		$this->url->TooltipValue = "";

		// login
		$this->_login->LinkCustomAttributes = "";
		$this->_login->HrefValue = "";
		$this->_login->TooltipValue = "";

		// password
		$this->password->LinkCustomAttributes = "";
		$this->password->HrefValue = "";
		$this->password->TooltipValue = "";

		// root_url
		$this->root_url->LinkCustomAttributes = "";
		$this->root_url->HrefValue = "";
		$this->root_url->TooltipValue = "";

		// type
		$this->type->LinkCustomAttributes = "";
		$this->type->HrefValue = "";
		$this->type->TooltipValue = "";

		// path_encoding
		$this->path_encoding->LinkCustomAttributes = "";
		$this->path_encoding->HrefValue = "";
		$this->path_encoding->TooltipValue = "";

		// log_encoding
		$this->log_encoding->LinkCustomAttributes = "";
		$this->log_encoding->HrefValue = "";
		$this->log_encoding->TooltipValue = "";

		// extra_info
		$this->extra_info->LinkCustomAttributes = "";
		$this->extra_info->HrefValue = "";
		$this->extra_info->TooltipValue = "";

		// identifier
		$this->identifier->LinkCustomAttributes = "";
		$this->identifier->HrefValue = "";
		$this->identifier->TooltipValue = "";

		// is_default
		$this->is_default->LinkCustomAttributes = "";
		$this->is_default->HrefValue = "";
		$this->is_default->TooltipValue = "";

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
				if ($this->id->Exportable) $Doc->ExportCaption($this->id);
				if ($this->project_id->Exportable) $Doc->ExportCaption($this->project_id);
				if ($this->url->Exportable) $Doc->ExportCaption($this->url);
				if ($this->_login->Exportable) $Doc->ExportCaption($this->_login);
				if ($this->password->Exportable) $Doc->ExportCaption($this->password);
				if ($this->root_url->Exportable) $Doc->ExportCaption($this->root_url);
				if ($this->type->Exportable) $Doc->ExportCaption($this->type);
				if ($this->path_encoding->Exportable) $Doc->ExportCaption($this->path_encoding);
				if ($this->log_encoding->Exportable) $Doc->ExportCaption($this->log_encoding);
				if ($this->extra_info->Exportable) $Doc->ExportCaption($this->extra_info);
				if ($this->identifier->Exportable) $Doc->ExportCaption($this->identifier);
				if ($this->is_default->Exportable) $Doc->ExportCaption($this->is_default);
			} else {
				if ($this->id->Exportable) $Doc->ExportCaption($this->id);
				if ($this->project_id->Exportable) $Doc->ExportCaption($this->project_id);
				if ($this->url->Exportable) $Doc->ExportCaption($this->url);
				if ($this->_login->Exportable) $Doc->ExportCaption($this->_login);
				if ($this->password->Exportable) $Doc->ExportCaption($this->password);
				if ($this->root_url->Exportable) $Doc->ExportCaption($this->root_url);
				if ($this->type->Exportable) $Doc->ExportCaption($this->type);
				if ($this->path_encoding->Exportable) $Doc->ExportCaption($this->path_encoding);
				if ($this->log_encoding->Exportable) $Doc->ExportCaption($this->log_encoding);
				if ($this->identifier->Exportable) $Doc->ExportCaption($this->identifier);
				if ($this->is_default->Exportable) $Doc->ExportCaption($this->is_default);
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
					if ($this->id->Exportable) $Doc->ExportField($this->id);
					if ($this->project_id->Exportable) $Doc->ExportField($this->project_id);
					if ($this->url->Exportable) $Doc->ExportField($this->url);
					if ($this->_login->Exportable) $Doc->ExportField($this->_login);
					if ($this->password->Exportable) $Doc->ExportField($this->password);
					if ($this->root_url->Exportable) $Doc->ExportField($this->root_url);
					if ($this->type->Exportable) $Doc->ExportField($this->type);
					if ($this->path_encoding->Exportable) $Doc->ExportField($this->path_encoding);
					if ($this->log_encoding->Exportable) $Doc->ExportField($this->log_encoding);
					if ($this->extra_info->Exportable) $Doc->ExportField($this->extra_info);
					if ($this->identifier->Exportable) $Doc->ExportField($this->identifier);
					if ($this->is_default->Exportable) $Doc->ExportField($this->is_default);
				} else {
					if ($this->id->Exportable) $Doc->ExportField($this->id);
					if ($this->project_id->Exportable) $Doc->ExportField($this->project_id);
					if ($this->url->Exportable) $Doc->ExportField($this->url);
					if ($this->_login->Exportable) $Doc->ExportField($this->_login);
					if ($this->password->Exportable) $Doc->ExportField($this->password);
					if ($this->root_url->Exportable) $Doc->ExportField($this->root_url);
					if ($this->type->Exportable) $Doc->ExportField($this->type);
					if ($this->path_encoding->Exportable) $Doc->ExportField($this->path_encoding);
					if ($this->log_encoding->Exportable) $Doc->ExportField($this->log_encoding);
					if ($this->identifier->Exportable) $Doc->ExportField($this->identifier);
					if ($this->is_default->Exportable) $Doc->ExportField($this->is_default);
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
