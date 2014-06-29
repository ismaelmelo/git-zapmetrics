<?php

// Global variable for table object
$vwrdm_projects = NULL;

//
// Table class for vwrdm_projects
//
class cvwrdm_projects extends cTable {
	var $id;
	var $name;
	var $description;
	var $homepage;
	var $is_public;
	var $parent_id;
	var $created_on;
	var $updated_on;
	var $identifier;
	var $status;
	var $lft;
	var $rgt;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'vwrdm_projects';
		$this->TableName = 'vwrdm_projects';
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
		$this->id = new cField('vwrdm_projects', 'vwrdm_projects', 'x_id', 'id', '[id]', 'CAST([id] AS NVARCHAR)', 3, -1, FALSE, '[id]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// name
		$this->name = new cField('vwrdm_projects', 'vwrdm_projects', 'x_name', 'name', '[name]', '[name]', 202, -1, FALSE, '[name]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['name'] = &$this->name;

		// description
		$this->description = new cField('vwrdm_projects', 'vwrdm_projects', 'x_description', 'description', '[description]', '[description]', 203, -1, FALSE, '[description]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['description'] = &$this->description;

		// homepage
		$this->homepage = new cField('vwrdm_projects', 'vwrdm_projects', 'x_homepage', 'homepage', '[homepage]', '[homepage]', 202, -1, FALSE, '[homepage]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['homepage'] = &$this->homepage;

		// is_public
		$this->is_public = new cField('vwrdm_projects', 'vwrdm_projects', 'x_is_public', 'is_public', '[is_public]', 'CAST([is_public] AS NVARCHAR)', 131, -1, FALSE, '[is_public]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->is_public->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['is_public'] = &$this->is_public;

		// parent_id
		$this->parent_id = new cField('vwrdm_projects', 'vwrdm_projects', 'x_parent_id', 'parent_id', '[parent_id]', 'CAST([parent_id] AS NVARCHAR)', 3, -1, FALSE, '[parent_id]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->parent_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['parent_id'] = &$this->parent_id;

		// created_on
		$this->created_on = new cField('vwrdm_projects', 'vwrdm_projects', 'x_created_on', 'created_on', '[created_on]', '(REPLACE(STR(DAY([created_on]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([created_on]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([created_on]),4,0))', 135, 7, FALSE, '[created_on]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->created_on->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['created_on'] = &$this->created_on;

		// updated_on
		$this->updated_on = new cField('vwrdm_projects', 'vwrdm_projects', 'x_updated_on', 'updated_on', '[updated_on]', '(REPLACE(STR(DAY([updated_on]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([updated_on]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([updated_on]),4,0))', 135, 7, FALSE, '[updated_on]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->updated_on->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['updated_on'] = &$this->updated_on;

		// identifier
		$this->identifier = new cField('vwrdm_projects', 'vwrdm_projects', 'x_identifier', 'identifier', '[identifier]', '[identifier]', 202, -1, FALSE, '[identifier]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['identifier'] = &$this->identifier;

		// status
		$this->status = new cField('vwrdm_projects', 'vwrdm_projects', 'x_status', 'status', '[status]', 'CAST([status] AS NVARCHAR)', 3, -1, FALSE, '[status]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->status->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['status'] = &$this->status;

		// lft
		$this->lft = new cField('vwrdm_projects', 'vwrdm_projects', 'x_lft', 'lft', '[lft]', 'CAST([lft] AS NVARCHAR)', 3, -1, FALSE, '[lft]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->lft->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['lft'] = &$this->lft;

		// rgt
		$this->rgt = new cField('vwrdm_projects', 'vwrdm_projects', 'x_rgt', 'rgt', '[rgt]', 'CAST([rgt] AS NVARCHAR)', 3, -1, FALSE, '[rgt]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->rgt->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['rgt'] = &$this->rgt;
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
		return "[db_owner].[vwrdm_projects]";
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
	var $UpdateTable = "[db_owner].[vwrdm_projects]";

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
			return "vwrdm_projectslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "vwrdm_projectslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("vwrdm_projectsview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("vwrdm_projectsview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "vwrdm_projectsadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("vwrdm_projectsedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("vwrdm_projectsadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("vwrdm_projectsdelete.php", $this->UrlParm());
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
		$this->name->setDbValue($rs->fields('name'));
		$this->description->setDbValue($rs->fields('description'));
		$this->homepage->setDbValue($rs->fields('homepage'));
		$this->is_public->setDbValue($rs->fields('is_public'));
		$this->parent_id->setDbValue($rs->fields('parent_id'));
		$this->created_on->setDbValue($rs->fields('created_on'));
		$this->updated_on->setDbValue($rs->fields('updated_on'));
		$this->identifier->setDbValue($rs->fields('identifier'));
		$this->status->setDbValue($rs->fields('status'));
		$this->lft->setDbValue($rs->fields('lft'));
		$this->rgt->setDbValue($rs->fields('rgt'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id
		// name
		// description
		// homepage
		// is_public
		// parent_id
		// created_on
		// updated_on
		// identifier
		// status
		// lft
		// rgt
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// name
		$this->name->ViewValue = $this->name->CurrentValue;
		$this->name->ViewCustomAttributes = "";

		// description
		$this->description->ViewValue = $this->description->CurrentValue;
		$this->description->ViewCustomAttributes = "";

		// homepage
		$this->homepage->ViewValue = $this->homepage->CurrentValue;
		$this->homepage->ViewCustomAttributes = "";

		// is_public
		$this->is_public->ViewValue = $this->is_public->CurrentValue;
		$this->is_public->ViewCustomAttributes = "";

		// parent_id
		$this->parent_id->ViewValue = $this->parent_id->CurrentValue;
		$this->parent_id->ViewCustomAttributes = "";

		// created_on
		$this->created_on->ViewValue = $this->created_on->CurrentValue;
		$this->created_on->ViewValue = ew_FormatDateTime($this->created_on->ViewValue, 7);
		$this->created_on->ViewCustomAttributes = "";

		// updated_on
		$this->updated_on->ViewValue = $this->updated_on->CurrentValue;
		$this->updated_on->ViewValue = ew_FormatDateTime($this->updated_on->ViewValue, 7);
		$this->updated_on->ViewCustomAttributes = "";

		// identifier
		$this->identifier->ViewValue = $this->identifier->CurrentValue;
		$this->identifier->ViewCustomAttributes = "";

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		$this->status->ViewCustomAttributes = "";

		// lft
		$this->lft->ViewValue = $this->lft->CurrentValue;
		$this->lft->ViewCustomAttributes = "";

		// rgt
		$this->rgt->ViewValue = $this->rgt->CurrentValue;
		$this->rgt->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// name
		$this->name->LinkCustomAttributes = "";
		$this->name->HrefValue = "";
		$this->name->TooltipValue = "";

		// description
		$this->description->LinkCustomAttributes = "";
		$this->description->HrefValue = "";
		$this->description->TooltipValue = "";

		// homepage
		$this->homepage->LinkCustomAttributes = "";
		$this->homepage->HrefValue = "";
		$this->homepage->TooltipValue = "";

		// is_public
		$this->is_public->LinkCustomAttributes = "";
		$this->is_public->HrefValue = "";
		$this->is_public->TooltipValue = "";

		// parent_id
		$this->parent_id->LinkCustomAttributes = "";
		$this->parent_id->HrefValue = "";
		$this->parent_id->TooltipValue = "";

		// created_on
		$this->created_on->LinkCustomAttributes = "";
		$this->created_on->HrefValue = "";
		$this->created_on->TooltipValue = "";

		// updated_on
		$this->updated_on->LinkCustomAttributes = "";
		$this->updated_on->HrefValue = "";
		$this->updated_on->TooltipValue = "";

		// identifier
		$this->identifier->LinkCustomAttributes = "";
		$this->identifier->HrefValue = "";
		$this->identifier->TooltipValue = "";

		// status
		$this->status->LinkCustomAttributes = "";
		$this->status->HrefValue = "";
		$this->status->TooltipValue = "";

		// lft
		$this->lft->LinkCustomAttributes = "";
		$this->lft->HrefValue = "";
		$this->lft->TooltipValue = "";

		// rgt
		$this->rgt->LinkCustomAttributes = "";
		$this->rgt->HrefValue = "";
		$this->rgt->TooltipValue = "";

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
				if ($this->name->Exportable) $Doc->ExportCaption($this->name);
				if ($this->description->Exportable) $Doc->ExportCaption($this->description);
				if ($this->homepage->Exportable) $Doc->ExportCaption($this->homepage);
				if ($this->is_public->Exportable) $Doc->ExportCaption($this->is_public);
				if ($this->parent_id->Exportable) $Doc->ExportCaption($this->parent_id);
				if ($this->created_on->Exportable) $Doc->ExportCaption($this->created_on);
				if ($this->updated_on->Exportable) $Doc->ExportCaption($this->updated_on);
				if ($this->identifier->Exportable) $Doc->ExportCaption($this->identifier);
				if ($this->status->Exportable) $Doc->ExportCaption($this->status);
				if ($this->lft->Exportable) $Doc->ExportCaption($this->lft);
				if ($this->rgt->Exportable) $Doc->ExportCaption($this->rgt);
			} else {
				if ($this->id->Exportable) $Doc->ExportCaption($this->id);
				if ($this->name->Exportable) $Doc->ExportCaption($this->name);
				if ($this->homepage->Exportable) $Doc->ExportCaption($this->homepage);
				if ($this->is_public->Exportable) $Doc->ExportCaption($this->is_public);
				if ($this->parent_id->Exportable) $Doc->ExportCaption($this->parent_id);
				if ($this->created_on->Exportable) $Doc->ExportCaption($this->created_on);
				if ($this->updated_on->Exportable) $Doc->ExportCaption($this->updated_on);
				if ($this->identifier->Exportable) $Doc->ExportCaption($this->identifier);
				if ($this->status->Exportable) $Doc->ExportCaption($this->status);
				if ($this->lft->Exportable) $Doc->ExportCaption($this->lft);
				if ($this->rgt->Exportable) $Doc->ExportCaption($this->rgt);
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
					if ($this->name->Exportable) $Doc->ExportField($this->name);
					if ($this->description->Exportable) $Doc->ExportField($this->description);
					if ($this->homepage->Exportable) $Doc->ExportField($this->homepage);
					if ($this->is_public->Exportable) $Doc->ExportField($this->is_public);
					if ($this->parent_id->Exportable) $Doc->ExportField($this->parent_id);
					if ($this->created_on->Exportable) $Doc->ExportField($this->created_on);
					if ($this->updated_on->Exportable) $Doc->ExportField($this->updated_on);
					if ($this->identifier->Exportable) $Doc->ExportField($this->identifier);
					if ($this->status->Exportable) $Doc->ExportField($this->status);
					if ($this->lft->Exportable) $Doc->ExportField($this->lft);
					if ($this->rgt->Exportable) $Doc->ExportField($this->rgt);
				} else {
					if ($this->id->Exportable) $Doc->ExportField($this->id);
					if ($this->name->Exportable) $Doc->ExportField($this->name);
					if ($this->homepage->Exportable) $Doc->ExportField($this->homepage);
					if ($this->is_public->Exportable) $Doc->ExportField($this->is_public);
					if ($this->parent_id->Exportable) $Doc->ExportField($this->parent_id);
					if ($this->created_on->Exportable) $Doc->ExportField($this->created_on);
					if ($this->updated_on->Exportable) $Doc->ExportField($this->updated_on);
					if ($this->identifier->Exportable) $Doc->ExportField($this->identifier);
					if ($this->status->Exportable) $Doc->ExportField($this->status);
					if ($this->lft->Exportable) $Doc->ExportField($this->lft);
					if ($this->rgt->Exportable) $Doc->ExportField($this->rgt);
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
