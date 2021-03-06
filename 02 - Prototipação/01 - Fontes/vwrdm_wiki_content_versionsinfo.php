<?php

// Global variable for table object
$vwrdm_wiki_content_versions = NULL;

//
// Table class for vwrdm_wiki_content_versions
//
class cvwrdm_wiki_content_versions extends cTable {
	var $id;
	var $wiki_content_id;
	var $page_id;
	var $author_id;
	var $data;
	var $compression;
	var $comments;
	var $updated_on;
	var $version;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'vwrdm_wiki_content_versions';
		$this->TableName = 'vwrdm_wiki_content_versions';
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
		$this->id = new cField('vwrdm_wiki_content_versions', 'vwrdm_wiki_content_versions', 'x_id', 'id', '[id]', 'CAST([id] AS NVARCHAR)', 3, -1, FALSE, '[id]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// wiki_content_id
		$this->wiki_content_id = new cField('vwrdm_wiki_content_versions', 'vwrdm_wiki_content_versions', 'x_wiki_content_id', 'wiki_content_id', '[wiki_content_id]', 'CAST([wiki_content_id] AS NVARCHAR)', 3, -1, FALSE, '[wiki_content_id]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->wiki_content_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['wiki_content_id'] = &$this->wiki_content_id;

		// page_id
		$this->page_id = new cField('vwrdm_wiki_content_versions', 'vwrdm_wiki_content_versions', 'x_page_id', 'page_id', '[page_id]', 'CAST([page_id] AS NVARCHAR)', 3, -1, FALSE, '[page_id]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->page_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['page_id'] = &$this->page_id;

		// author_id
		$this->author_id = new cField('vwrdm_wiki_content_versions', 'vwrdm_wiki_content_versions', 'x_author_id', 'author_id', '[author_id]', 'CAST([author_id] AS NVARCHAR)', 3, -1, FALSE, '[author_id]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->author_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['author_id'] = &$this->author_id;

		// data
		$this->data = new cField('vwrdm_wiki_content_versions', 'vwrdm_wiki_content_versions', 'x_data', 'data', '[data]', '[data]', 205, -1, TRUE, '[data]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['data'] = &$this->data;

		// compression
		$this->compression = new cField('vwrdm_wiki_content_versions', 'vwrdm_wiki_content_versions', 'x_compression', 'compression', '[compression]', '[compression]', 202, -1, FALSE, '[compression]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['compression'] = &$this->compression;

		// comments
		$this->comments = new cField('vwrdm_wiki_content_versions', 'vwrdm_wiki_content_versions', 'x_comments', 'comments', '[comments]', '[comments]', 202, -1, FALSE, '[comments]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['comments'] = &$this->comments;

		// updated_on
		$this->updated_on = new cField('vwrdm_wiki_content_versions', 'vwrdm_wiki_content_versions', 'x_updated_on', 'updated_on', '[updated_on]', '(REPLACE(STR(DAY([updated_on]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([updated_on]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([updated_on]),4,0))', 135, 7, FALSE, '[updated_on]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->updated_on->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['updated_on'] = &$this->updated_on;

		// version
		$this->version = new cField('vwrdm_wiki_content_versions', 'vwrdm_wiki_content_versions', 'x_version', 'version', '[version]', 'CAST([version] AS NVARCHAR)', 3, -1, FALSE, '[version]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->version->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['version'] = &$this->version;
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
		return "[db_owner].[vwrdm_wiki_content_versions]";
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
	var $UpdateTable = "[db_owner].[vwrdm_wiki_content_versions]";

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
			return "vwrdm_wiki_content_versionslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "vwrdm_wiki_content_versionslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("vwrdm_wiki_content_versionsview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("vwrdm_wiki_content_versionsview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "vwrdm_wiki_content_versionsadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("vwrdm_wiki_content_versionsedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("vwrdm_wiki_content_versionsadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("vwrdm_wiki_content_versionsdelete.php", $this->UrlParm());
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
		$this->wiki_content_id->setDbValue($rs->fields('wiki_content_id'));
		$this->page_id->setDbValue($rs->fields('page_id'));
		$this->author_id->setDbValue($rs->fields('author_id'));
		$this->data->Upload->DbValue = $rs->fields('data');
		$this->compression->setDbValue($rs->fields('compression'));
		$this->comments->setDbValue($rs->fields('comments'));
		$this->updated_on->setDbValue($rs->fields('updated_on'));
		$this->version->setDbValue($rs->fields('version'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id
		// wiki_content_id
		// page_id
		// author_id
		// data
		// compression
		// comments
		// updated_on
		// version
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// wiki_content_id
		$this->wiki_content_id->ViewValue = $this->wiki_content_id->CurrentValue;
		$this->wiki_content_id->ViewCustomAttributes = "";

		// page_id
		$this->page_id->ViewValue = $this->page_id->CurrentValue;
		$this->page_id->ViewCustomAttributes = "";

		// author_id
		$this->author_id->ViewValue = $this->author_id->CurrentValue;
		$this->author_id->ViewCustomAttributes = "";

		// data
		if (!ew_Empty($this->data->Upload->DbValue)) {
			$this->data->ViewValue = $this->data->FldCaption();
		} else {
			$this->data->ViewValue = "";
		}
		$this->data->ViewCustomAttributes = "";

		// compression
		$this->compression->ViewValue = $this->compression->CurrentValue;
		$this->compression->ViewCustomAttributes = "";

		// comments
		$this->comments->ViewValue = $this->comments->CurrentValue;
		$this->comments->ViewCustomAttributes = "";

		// updated_on
		$this->updated_on->ViewValue = $this->updated_on->CurrentValue;
		$this->updated_on->ViewValue = ew_FormatDateTime($this->updated_on->ViewValue, 7);
		$this->updated_on->ViewCustomAttributes = "";

		// version
		$this->version->ViewValue = $this->version->CurrentValue;
		$this->version->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// wiki_content_id
		$this->wiki_content_id->LinkCustomAttributes = "";
		$this->wiki_content_id->HrefValue = "";
		$this->wiki_content_id->TooltipValue = "";

		// page_id
		$this->page_id->LinkCustomAttributes = "";
		$this->page_id->HrefValue = "";
		$this->page_id->TooltipValue = "";

		// author_id
		$this->author_id->LinkCustomAttributes = "";
		$this->author_id->HrefValue = "";
		$this->author_id->TooltipValue = "";

		// data
		$this->data->LinkCustomAttributes = "";
		if (!empty($this->data->Upload->DbValue)) {
			$this->data->HrefValue = "";
			$this->data->LinkAttrs["target"] = "_blank";
			if ($this->Export <> "") $this->data->HrefValue = ew_ConvertFullUrl($this->data->HrefValue);
		} else {
			$this->data->HrefValue = "";
		}
		$this->data->HrefValue2 = "";
		$this->data->TooltipValue = "";

		// compression
		$this->compression->LinkCustomAttributes = "";
		$this->compression->HrefValue = "";
		$this->compression->TooltipValue = "";

		// comments
		$this->comments->LinkCustomAttributes = "";
		$this->comments->HrefValue = "";
		$this->comments->TooltipValue = "";

		// updated_on
		$this->updated_on->LinkCustomAttributes = "";
		$this->updated_on->HrefValue = "";
		$this->updated_on->TooltipValue = "";

		// version
		$this->version->LinkCustomAttributes = "";
		$this->version->HrefValue = "";
		$this->version->TooltipValue = "";

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
				if ($this->wiki_content_id->Exportable) $Doc->ExportCaption($this->wiki_content_id);
				if ($this->page_id->Exportable) $Doc->ExportCaption($this->page_id);
				if ($this->author_id->Exportable) $Doc->ExportCaption($this->author_id);
				if ($this->data->Exportable) $Doc->ExportCaption($this->data);
				if ($this->compression->Exportable) $Doc->ExportCaption($this->compression);
				if ($this->comments->Exportable) $Doc->ExportCaption($this->comments);
				if ($this->updated_on->Exportable) $Doc->ExportCaption($this->updated_on);
				if ($this->version->Exportable) $Doc->ExportCaption($this->version);
			} else {
				if ($this->id->Exportable) $Doc->ExportCaption($this->id);
				if ($this->wiki_content_id->Exportable) $Doc->ExportCaption($this->wiki_content_id);
				if ($this->page_id->Exportable) $Doc->ExportCaption($this->page_id);
				if ($this->author_id->Exportable) $Doc->ExportCaption($this->author_id);
				if ($this->compression->Exportable) $Doc->ExportCaption($this->compression);
				if ($this->comments->Exportable) $Doc->ExportCaption($this->comments);
				if ($this->updated_on->Exportable) $Doc->ExportCaption($this->updated_on);
				if ($this->version->Exportable) $Doc->ExportCaption($this->version);
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
					if ($this->wiki_content_id->Exportable) $Doc->ExportField($this->wiki_content_id);
					if ($this->page_id->Exportable) $Doc->ExportField($this->page_id);
					if ($this->author_id->Exportable) $Doc->ExportField($this->author_id);
					if ($this->data->Exportable) $Doc->ExportField($this->data);
					if ($this->compression->Exportable) $Doc->ExportField($this->compression);
					if ($this->comments->Exportable) $Doc->ExportField($this->comments);
					if ($this->updated_on->Exportable) $Doc->ExportField($this->updated_on);
					if ($this->version->Exportable) $Doc->ExportField($this->version);
				} else {
					if ($this->id->Exportable) $Doc->ExportField($this->id);
					if ($this->wiki_content_id->Exportable) $Doc->ExportField($this->wiki_content_id);
					if ($this->page_id->Exportable) $Doc->ExportField($this->page_id);
					if ($this->author_id->Exportable) $Doc->ExportField($this->author_id);
					if ($this->compression->Exportable) $Doc->ExportField($this->compression);
					if ($this->comments->Exportable) $Doc->ExportField($this->comments);
					if ($this->updated_on->Exportable) $Doc->ExportField($this->updated_on);
					if ($this->version->Exportable) $Doc->ExportField($this->version);
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
