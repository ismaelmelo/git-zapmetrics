<?php

// Global variable for table object
$vwrdm_custom_fields = NULL;

//
// Table class for vwrdm_custom_fields
//
class cvwrdm_custom_fields extends cTable {
	var $id;
	var $type;
	var $name;
	var $field_format;
	var $possible_values;
	var $regexp;
	var $min_length;
	var $max_length;
	var $is_required;
	var $is_for_all;
	var $is_filter;
	var $position;
	var $searchable;
	var $default_value;
	var $editable;
	var $visible;
	var $multiple;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'vwrdm_custom_fields';
		$this->TableName = 'vwrdm_custom_fields';
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
		$this->id = new cField('vwrdm_custom_fields', 'vwrdm_custom_fields', 'x_id', 'id', '[id]', 'CAST([id] AS NVARCHAR)', 3, -1, FALSE, '[id]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// type
		$this->type = new cField('vwrdm_custom_fields', 'vwrdm_custom_fields', 'x_type', 'type', '[type]', '[type]', 202, -1, FALSE, '[type]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['type'] = &$this->type;

		// name
		$this->name = new cField('vwrdm_custom_fields', 'vwrdm_custom_fields', 'x_name', 'name', '[name]', '[name]', 202, -1, FALSE, '[name]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['name'] = &$this->name;

		// field_format
		$this->field_format = new cField('vwrdm_custom_fields', 'vwrdm_custom_fields', 'x_field_format', 'field_format', '[field_format]', '[field_format]', 202, -1, FALSE, '[field_format]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['field_format'] = &$this->field_format;

		// possible_values
		$this->possible_values = new cField('vwrdm_custom_fields', 'vwrdm_custom_fields', 'x_possible_values', 'possible_values', '[possible_values]', '[possible_values]', 203, -1, FALSE, '[possible_values]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['possible_values'] = &$this->possible_values;

		// regexp
		$this->regexp = new cField('vwrdm_custom_fields', 'vwrdm_custom_fields', 'x_regexp', 'regexp', '[regexp]', '[regexp]', 202, -1, FALSE, '[regexp]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['regexp'] = &$this->regexp;

		// min_length
		$this->min_length = new cField('vwrdm_custom_fields', 'vwrdm_custom_fields', 'x_min_length', 'min_length', '[min_length]', 'CAST([min_length] AS NVARCHAR)', 3, -1, FALSE, '[min_length]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->min_length->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['min_length'] = &$this->min_length;

		// max_length
		$this->max_length = new cField('vwrdm_custom_fields', 'vwrdm_custom_fields', 'x_max_length', 'max_length', '[max_length]', 'CAST([max_length] AS NVARCHAR)', 3, -1, FALSE, '[max_length]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->max_length->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['max_length'] = &$this->max_length;

		// is_required
		$this->is_required = new cField('vwrdm_custom_fields', 'vwrdm_custom_fields', 'x_is_required', 'is_required', '[is_required]', 'CAST([is_required] AS NVARCHAR)', 131, -1, FALSE, '[is_required]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->is_required->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['is_required'] = &$this->is_required;

		// is_for_all
		$this->is_for_all = new cField('vwrdm_custom_fields', 'vwrdm_custom_fields', 'x_is_for_all', 'is_for_all', '[is_for_all]', 'CAST([is_for_all] AS NVARCHAR)', 131, -1, FALSE, '[is_for_all]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->is_for_all->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['is_for_all'] = &$this->is_for_all;

		// is_filter
		$this->is_filter = new cField('vwrdm_custom_fields', 'vwrdm_custom_fields', 'x_is_filter', 'is_filter', '[is_filter]', 'CAST([is_filter] AS NVARCHAR)', 131, -1, FALSE, '[is_filter]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->is_filter->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['is_filter'] = &$this->is_filter;

		// position
		$this->position = new cField('vwrdm_custom_fields', 'vwrdm_custom_fields', 'x_position', 'position', '[position]', 'CAST([position] AS NVARCHAR)', 3, -1, FALSE, '[position]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->position->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['position'] = &$this->position;

		// searchable
		$this->searchable = new cField('vwrdm_custom_fields', 'vwrdm_custom_fields', 'x_searchable', 'searchable', '[searchable]', 'CAST([searchable] AS NVARCHAR)', 131, -1, FALSE, '[searchable]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->searchable->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['searchable'] = &$this->searchable;

		// default_value
		$this->default_value = new cField('vwrdm_custom_fields', 'vwrdm_custom_fields', 'x_default_value', 'default_value', '[default_value]', '[default_value]', 203, -1, FALSE, '[default_value]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['default_value'] = &$this->default_value;

		// editable
		$this->editable = new cField('vwrdm_custom_fields', 'vwrdm_custom_fields', 'x_editable', 'editable', '[editable]', 'CAST([editable] AS NVARCHAR)', 131, -1, FALSE, '[editable]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->editable->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['editable'] = &$this->editable;

		// visible
		$this->visible = new cField('vwrdm_custom_fields', 'vwrdm_custom_fields', 'x_visible', 'visible', '[visible]', 'CAST([visible] AS NVARCHAR)', 131, -1, FALSE, '[visible]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->visible->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['visible'] = &$this->visible;

		// multiple
		$this->multiple = new cField('vwrdm_custom_fields', 'vwrdm_custom_fields', 'x_multiple', 'multiple', '[multiple]', 'CAST([multiple] AS NVARCHAR)', 131, -1, FALSE, '[multiple]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->multiple->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['multiple'] = &$this->multiple;
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
		return "[db_owner].[vwrdm_custom_fields]";
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
	var $UpdateTable = "[db_owner].[vwrdm_custom_fields]";

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
			return "vwrdm_custom_fieldslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "vwrdm_custom_fieldslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("vwrdm_custom_fieldsview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("vwrdm_custom_fieldsview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "vwrdm_custom_fieldsadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("vwrdm_custom_fieldsedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("vwrdm_custom_fieldsadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("vwrdm_custom_fieldsdelete.php", $this->UrlParm());
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
		$this->type->setDbValue($rs->fields('type'));
		$this->name->setDbValue($rs->fields('name'));
		$this->field_format->setDbValue($rs->fields('field_format'));
		$this->possible_values->setDbValue($rs->fields('possible_values'));
		$this->regexp->setDbValue($rs->fields('regexp'));
		$this->min_length->setDbValue($rs->fields('min_length'));
		$this->max_length->setDbValue($rs->fields('max_length'));
		$this->is_required->setDbValue($rs->fields('is_required'));
		$this->is_for_all->setDbValue($rs->fields('is_for_all'));
		$this->is_filter->setDbValue($rs->fields('is_filter'));
		$this->position->setDbValue($rs->fields('position'));
		$this->searchable->setDbValue($rs->fields('searchable'));
		$this->default_value->setDbValue($rs->fields('default_value'));
		$this->editable->setDbValue($rs->fields('editable'));
		$this->visible->setDbValue($rs->fields('visible'));
		$this->multiple->setDbValue($rs->fields('multiple'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id
		// type
		// name
		// field_format
		// possible_values
		// regexp
		// min_length
		// max_length
		// is_required
		// is_for_all
		// is_filter
		// position
		// searchable
		// default_value
		// editable
		// visible
		// multiple
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// type
		$this->type->ViewValue = $this->type->CurrentValue;
		$this->type->ViewCustomAttributes = "";

		// name
		$this->name->ViewValue = $this->name->CurrentValue;
		$this->name->ViewCustomAttributes = "";

		// field_format
		$this->field_format->ViewValue = $this->field_format->CurrentValue;
		$this->field_format->ViewCustomAttributes = "";

		// possible_values
		$this->possible_values->ViewValue = $this->possible_values->CurrentValue;
		$this->possible_values->ViewCustomAttributes = "";

		// regexp
		$this->regexp->ViewValue = $this->regexp->CurrentValue;
		$this->regexp->ViewCustomAttributes = "";

		// min_length
		$this->min_length->ViewValue = $this->min_length->CurrentValue;
		$this->min_length->ViewCustomAttributes = "";

		// max_length
		$this->max_length->ViewValue = $this->max_length->CurrentValue;
		$this->max_length->ViewCustomAttributes = "";

		// is_required
		$this->is_required->ViewValue = $this->is_required->CurrentValue;
		$this->is_required->ViewCustomAttributes = "";

		// is_for_all
		$this->is_for_all->ViewValue = $this->is_for_all->CurrentValue;
		$this->is_for_all->ViewCustomAttributes = "";

		// is_filter
		$this->is_filter->ViewValue = $this->is_filter->CurrentValue;
		$this->is_filter->ViewCustomAttributes = "";

		// position
		$this->position->ViewValue = $this->position->CurrentValue;
		$this->position->ViewCustomAttributes = "";

		// searchable
		$this->searchable->ViewValue = $this->searchable->CurrentValue;
		$this->searchable->ViewCustomAttributes = "";

		// default_value
		$this->default_value->ViewValue = $this->default_value->CurrentValue;
		$this->default_value->ViewCustomAttributes = "";

		// editable
		$this->editable->ViewValue = $this->editable->CurrentValue;
		$this->editable->ViewCustomAttributes = "";

		// visible
		$this->visible->ViewValue = $this->visible->CurrentValue;
		$this->visible->ViewCustomAttributes = "";

		// multiple
		$this->multiple->ViewValue = $this->multiple->CurrentValue;
		$this->multiple->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// type
		$this->type->LinkCustomAttributes = "";
		$this->type->HrefValue = "";
		$this->type->TooltipValue = "";

		// name
		$this->name->LinkCustomAttributes = "";
		$this->name->HrefValue = "";
		$this->name->TooltipValue = "";

		// field_format
		$this->field_format->LinkCustomAttributes = "";
		$this->field_format->HrefValue = "";
		$this->field_format->TooltipValue = "";

		// possible_values
		$this->possible_values->LinkCustomAttributes = "";
		$this->possible_values->HrefValue = "";
		$this->possible_values->TooltipValue = "";

		// regexp
		$this->regexp->LinkCustomAttributes = "";
		$this->regexp->HrefValue = "";
		$this->regexp->TooltipValue = "";

		// min_length
		$this->min_length->LinkCustomAttributes = "";
		$this->min_length->HrefValue = "";
		$this->min_length->TooltipValue = "";

		// max_length
		$this->max_length->LinkCustomAttributes = "";
		$this->max_length->HrefValue = "";
		$this->max_length->TooltipValue = "";

		// is_required
		$this->is_required->LinkCustomAttributes = "";
		$this->is_required->HrefValue = "";
		$this->is_required->TooltipValue = "";

		// is_for_all
		$this->is_for_all->LinkCustomAttributes = "";
		$this->is_for_all->HrefValue = "";
		$this->is_for_all->TooltipValue = "";

		// is_filter
		$this->is_filter->LinkCustomAttributes = "";
		$this->is_filter->HrefValue = "";
		$this->is_filter->TooltipValue = "";

		// position
		$this->position->LinkCustomAttributes = "";
		$this->position->HrefValue = "";
		$this->position->TooltipValue = "";

		// searchable
		$this->searchable->LinkCustomAttributes = "";
		$this->searchable->HrefValue = "";
		$this->searchable->TooltipValue = "";

		// default_value
		$this->default_value->LinkCustomAttributes = "";
		$this->default_value->HrefValue = "";
		$this->default_value->TooltipValue = "";

		// editable
		$this->editable->LinkCustomAttributes = "";
		$this->editable->HrefValue = "";
		$this->editable->TooltipValue = "";

		// visible
		$this->visible->LinkCustomAttributes = "";
		$this->visible->HrefValue = "";
		$this->visible->TooltipValue = "";

		// multiple
		$this->multiple->LinkCustomAttributes = "";
		$this->multiple->HrefValue = "";
		$this->multiple->TooltipValue = "";

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
				if ($this->type->Exportable) $Doc->ExportCaption($this->type);
				if ($this->name->Exportable) $Doc->ExportCaption($this->name);
				if ($this->field_format->Exportable) $Doc->ExportCaption($this->field_format);
				if ($this->possible_values->Exportable) $Doc->ExportCaption($this->possible_values);
				if ($this->regexp->Exportable) $Doc->ExportCaption($this->regexp);
				if ($this->min_length->Exportable) $Doc->ExportCaption($this->min_length);
				if ($this->max_length->Exportable) $Doc->ExportCaption($this->max_length);
				if ($this->is_required->Exportable) $Doc->ExportCaption($this->is_required);
				if ($this->is_for_all->Exportable) $Doc->ExportCaption($this->is_for_all);
				if ($this->is_filter->Exportable) $Doc->ExportCaption($this->is_filter);
				if ($this->position->Exportable) $Doc->ExportCaption($this->position);
				if ($this->searchable->Exportable) $Doc->ExportCaption($this->searchable);
				if ($this->default_value->Exportable) $Doc->ExportCaption($this->default_value);
				if ($this->editable->Exportable) $Doc->ExportCaption($this->editable);
				if ($this->visible->Exportable) $Doc->ExportCaption($this->visible);
				if ($this->multiple->Exportable) $Doc->ExportCaption($this->multiple);
			} else {
				if ($this->id->Exportable) $Doc->ExportCaption($this->id);
				if ($this->type->Exportable) $Doc->ExportCaption($this->type);
				if ($this->name->Exportable) $Doc->ExportCaption($this->name);
				if ($this->field_format->Exportable) $Doc->ExportCaption($this->field_format);
				if ($this->regexp->Exportable) $Doc->ExportCaption($this->regexp);
				if ($this->min_length->Exportable) $Doc->ExportCaption($this->min_length);
				if ($this->max_length->Exportable) $Doc->ExportCaption($this->max_length);
				if ($this->is_required->Exportable) $Doc->ExportCaption($this->is_required);
				if ($this->is_for_all->Exportable) $Doc->ExportCaption($this->is_for_all);
				if ($this->is_filter->Exportable) $Doc->ExportCaption($this->is_filter);
				if ($this->position->Exportable) $Doc->ExportCaption($this->position);
				if ($this->searchable->Exportable) $Doc->ExportCaption($this->searchable);
				if ($this->editable->Exportable) $Doc->ExportCaption($this->editable);
				if ($this->visible->Exportable) $Doc->ExportCaption($this->visible);
				if ($this->multiple->Exportable) $Doc->ExportCaption($this->multiple);
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
					if ($this->type->Exportable) $Doc->ExportField($this->type);
					if ($this->name->Exportable) $Doc->ExportField($this->name);
					if ($this->field_format->Exportable) $Doc->ExportField($this->field_format);
					if ($this->possible_values->Exportable) $Doc->ExportField($this->possible_values);
					if ($this->regexp->Exportable) $Doc->ExportField($this->regexp);
					if ($this->min_length->Exportable) $Doc->ExportField($this->min_length);
					if ($this->max_length->Exportable) $Doc->ExportField($this->max_length);
					if ($this->is_required->Exportable) $Doc->ExportField($this->is_required);
					if ($this->is_for_all->Exportable) $Doc->ExportField($this->is_for_all);
					if ($this->is_filter->Exportable) $Doc->ExportField($this->is_filter);
					if ($this->position->Exportable) $Doc->ExportField($this->position);
					if ($this->searchable->Exportable) $Doc->ExportField($this->searchable);
					if ($this->default_value->Exportable) $Doc->ExportField($this->default_value);
					if ($this->editable->Exportable) $Doc->ExportField($this->editable);
					if ($this->visible->Exportable) $Doc->ExportField($this->visible);
					if ($this->multiple->Exportable) $Doc->ExportField($this->multiple);
				} else {
					if ($this->id->Exportable) $Doc->ExportField($this->id);
					if ($this->type->Exportable) $Doc->ExportField($this->type);
					if ($this->name->Exportable) $Doc->ExportField($this->name);
					if ($this->field_format->Exportable) $Doc->ExportField($this->field_format);
					if ($this->regexp->Exportable) $Doc->ExportField($this->regexp);
					if ($this->min_length->Exportable) $Doc->ExportField($this->min_length);
					if ($this->max_length->Exportable) $Doc->ExportField($this->max_length);
					if ($this->is_required->Exportable) $Doc->ExportField($this->is_required);
					if ($this->is_for_all->Exportable) $Doc->ExportField($this->is_for_all);
					if ($this->is_filter->Exportable) $Doc->ExportField($this->is_filter);
					if ($this->position->Exportable) $Doc->ExportField($this->position);
					if ($this->searchable->Exportable) $Doc->ExportField($this->searchable);
					if ($this->editable->Exportable) $Doc->ExportField($this->editable);
					if ($this->visible->Exportable) $Doc->ExportField($this->visible);
					if ($this->multiple->Exportable) $Doc->ExportField($this->multiple);
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
