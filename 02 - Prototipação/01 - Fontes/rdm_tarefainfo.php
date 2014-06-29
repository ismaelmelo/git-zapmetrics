<?php

// Global variable for table object
$rdm_tarefa = NULL;

//
// Table class for rdm_tarefa
//
class crdm_tarefa extends cTable {
	var $id;
	var $project_id;
	var $tracker_id;
	var $subject;
	var $author_id;
	var $status_id;
	var $priority_id;
	var $assigned_to;
	var $start_date;
	var $done_ratio;
	var $created_on;
	var $updated_on;
	var $due_date;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'rdm_tarefa';
		$this->TableName = 'rdm_tarefa';
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

		// id
		$this->id = new cField('rdm_tarefa', 'rdm_tarefa', 'x_id', 'id', '[id]', 'CAST([id] AS NVARCHAR)', 3, -1, FALSE, '[id]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// project_id
		$this->project_id = new cField('rdm_tarefa', 'rdm_tarefa', 'x_project_id', 'project_id', '[project_id]', 'CAST([project_id] AS NVARCHAR)', 3, -1, FALSE, '[project_id]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->project_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['project_id'] = &$this->project_id;

		// tracker_id
		$this->tracker_id = new cField('rdm_tarefa', 'rdm_tarefa', 'x_tracker_id', 'tracker_id', '[tracker_id]', 'CAST([tracker_id] AS NVARCHAR)', 3, -1, FALSE, '[tracker_id]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->tracker_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['tracker_id'] = &$this->tracker_id;

		// subject
		$this->subject = new cField('rdm_tarefa', 'rdm_tarefa', 'x_subject', 'subject', '[subject]', '[subject]', 200, -1, FALSE, '[subject]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['subject'] = &$this->subject;

		// author_id
		$this->author_id = new cField('rdm_tarefa', 'rdm_tarefa', 'x_author_id', 'author_id', '[author_id]', 'CAST([author_id] AS NVARCHAR)', 3, -1, FALSE, '[author_id]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->author_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['author_id'] = &$this->author_id;

		// status_id
		$this->status_id = new cField('rdm_tarefa', 'rdm_tarefa', 'x_status_id', 'status_id', '[status_id]', 'CAST([status_id] AS NVARCHAR)', 3, -1, FALSE, '[status_id]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->status_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['status_id'] = &$this->status_id;

		// priority_id
		$this->priority_id = new cField('rdm_tarefa', 'rdm_tarefa', 'x_priority_id', 'priority_id', '[priority_id]', 'CAST([priority_id] AS NVARCHAR)', 3, -1, FALSE, '[priority_id]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->priority_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['priority_id'] = &$this->priority_id;

		// assigned_to
		$this->assigned_to = new cField('rdm_tarefa', 'rdm_tarefa', 'x_assigned_to', 'assigned_to', '[assigned_to]', 'CAST([assigned_to] AS NVARCHAR)', 3, -1, FALSE, '[assigned_to]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->assigned_to->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['assigned_to'] = &$this->assigned_to;

		// start_date
		$this->start_date = new cField('rdm_tarefa', 'rdm_tarefa', 'x_start_date', 'start_date', '[start_date]', 'CAST([start_date] AS NVARCHAR)', 3, 14, FALSE, '[start_date]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->start_date->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['start_date'] = &$this->start_date;

		// done_ratio
		$this->done_ratio = new cField('rdm_tarefa', 'rdm_tarefa', 'x_done_ratio', 'done_ratio', '[done_ratio]', 'CAST([done_ratio] AS NVARCHAR)', 3, -1, FALSE, '[done_ratio]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->done_ratio->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['done_ratio'] = &$this->done_ratio;

		// created_on
		$this->created_on = new cField('rdm_tarefa', 'rdm_tarefa', 'x_created_on', 'created_on', '[created_on]', 'CAST([created_on] AS NVARCHAR)', 3, 17, FALSE, '[created_on]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->created_on->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['created_on'] = &$this->created_on;

		// updated_on
		$this->updated_on = new cField('rdm_tarefa', 'rdm_tarefa', 'x_updated_on', 'updated_on', '[updated_on]', 'CAST([updated_on] AS NVARCHAR)', 3, 17, FALSE, '[updated_on]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->updated_on->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['updated_on'] = &$this->updated_on;

		// due_date
		$this->due_date = new cField('rdm_tarefa', 'rdm_tarefa', 'x_due_date', 'due_date', '[due_date]', 'CAST([due_date] AS NVARCHAR)', 3, 7, FALSE, '[due_date]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->due_date->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['due_date'] = &$this->due_date;
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
		return "[dbo].[rdm_tarefa]";
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
	var $UpdateTable = "[dbo].[rdm_tarefa]";

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
			if (array_key_exists('id', $rs))
				ew_AddFilter($where, ew_QuotedName('id') . '=' . ew_QuotedValue($rs['id'], $this->id->FldDataType));
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
		return "[id] = @id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id@", ew_AdjustSql($this->id->CurrentValue), $sKeyFilter); // Replace key value
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
			return "rdm_tarefalist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "rdm_tarefalist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("rdm_tarefaview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("rdm_tarefaview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "rdm_tarefaadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("rdm_tarefaedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("rdm_tarefaadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("rdm_tarefadelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id->CurrentValue)) {
			$sUrl .= "id=" . urlencode($this->id->CurrentValue);
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
			$arKeys[] = @$_GET["id"]; // id

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
			$this->id->CurrentValue = $key;
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
		$this->tracker_id->setDbValue($rs->fields('tracker_id'));
		$this->subject->setDbValue($rs->fields('subject'));
		$this->author_id->setDbValue($rs->fields('author_id'));
		$this->status_id->setDbValue($rs->fields('status_id'));
		$this->priority_id->setDbValue($rs->fields('priority_id'));
		$this->assigned_to->setDbValue($rs->fields('assigned_to'));
		$this->start_date->setDbValue($rs->fields('start_date'));
		$this->done_ratio->setDbValue($rs->fields('done_ratio'));
		$this->created_on->setDbValue($rs->fields('created_on'));
		$this->updated_on->setDbValue($rs->fields('updated_on'));
		$this->due_date->setDbValue($rs->fields('due_date'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id
		// project_id
		// tracker_id
		// subject
		// author_id
		// status_id
		// priority_id
		// assigned_to
		// start_date
		// done_ratio
		// created_on
		// updated_on
		// due_date
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// project_id
		if (strval($this->project_id->CurrentValue) <> "") {
			$sFilterWrk = "[id]" . ew_SearchString("=", $this->project_id->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[rdm_projeto]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->project_id, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->project_id->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->project_id->ViewValue = $this->project_id->CurrentValue;
			}
		} else {
			$this->project_id->ViewValue = NULL;
		}
		$this->project_id->ViewCustomAttributes = "";

		// tracker_id
		if (strval($this->tracker_id->CurrentValue) <> "") {
			$sFilterWrk = "[id]" . ew_SearchString("=", $this->tracker_id->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[rdm_rastreador]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->tracker_id, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->tracker_id->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->tracker_id->ViewValue = $this->tracker_id->CurrentValue;
			}
		} else {
			$this->tracker_id->ViewValue = NULL;
		}
		$this->tracker_id->ViewCustomAttributes = "";

		// subject
		$this->subject->ViewValue = $this->subject->CurrentValue;
		$this->subject->ViewCustomAttributes = "";

		// author_id
		if (strval($this->author_id->CurrentValue) <> "") {
			$sFilterWrk = "[id]" . ew_SearchString("=", $this->author_id->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[rdm_usuario]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->author_id, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->author_id->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->author_id->ViewValue = $this->author_id->CurrentValue;
			}
		} else {
			$this->author_id->ViewValue = NULL;
		}
		$this->author_id->ViewCustomAttributes = "";

		// status_id
		$this->status_id->ViewValue = $this->status_id->CurrentValue;
		$this->status_id->ViewCustomAttributes = "";

		// priority_id
		if (strval($this->priority_id->CurrentValue) <> "") {
			$sFilterWrk = "[id]" . ew_SearchString("=", $this->priority_id->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[rdm_prioridade]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->priority_id, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->priority_id->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->priority_id->ViewValue = $this->priority_id->CurrentValue;
			}
		} else {
			$this->priority_id->ViewValue = NULL;
		}
		$this->priority_id->ViewCustomAttributes = "";

		// assigned_to
		if (strval($this->assigned_to->CurrentValue) <> "") {
			$sFilterWrk = "[id]" . ew_SearchString("=", $this->assigned_to->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[rdm_usuario]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->assigned_to, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->assigned_to->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->assigned_to->ViewValue = $this->assigned_to->CurrentValue;
			}
		} else {
			$this->assigned_to->ViewValue = NULL;
		}
		$this->assigned_to->ViewCustomAttributes = "";

		// start_date
		$this->start_date->ViewValue = $this->start_date->CurrentValue;
		$this->start_date->ViewValue = ew_FormatDateTime($this->start_date->ViewValue, 14);
		$this->start_date->ViewCustomAttributes = "";

		// done_ratio
		$this->done_ratio->ViewValue = $this->done_ratio->CurrentValue;
		$this->done_ratio->ViewCustomAttributes = "";

		// created_on
		$this->created_on->ViewValue = $this->created_on->CurrentValue;
		$this->created_on->ViewValue = ew_FormatDateTime($this->created_on->ViewValue, 17);
		$this->created_on->ViewCustomAttributes = "";

		// updated_on
		$this->updated_on->ViewValue = $this->updated_on->CurrentValue;
		$this->updated_on->ViewValue = ew_FormatDateTime($this->updated_on->ViewValue, 17);
		$this->updated_on->ViewCustomAttributes = "";

		// due_date
		$this->due_date->ViewValue = $this->due_date->CurrentValue;
		$this->due_date->ViewValue = ew_FormatDateTime($this->due_date->ViewValue, 7);
		$this->due_date->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// project_id
		$this->project_id->LinkCustomAttributes = "";
		$this->project_id->HrefValue = "";
		$this->project_id->TooltipValue = "";

		// tracker_id
		$this->tracker_id->LinkCustomAttributes = "";
		$this->tracker_id->HrefValue = "";
		$this->tracker_id->TooltipValue = "";

		// subject
		$this->subject->LinkCustomAttributes = "";
		$this->subject->HrefValue = "";
		$this->subject->TooltipValue = "";

		// author_id
		$this->author_id->LinkCustomAttributes = "";
		$this->author_id->HrefValue = "";
		$this->author_id->TooltipValue = "";

		// status_id
		$this->status_id->LinkCustomAttributes = "";
		$this->status_id->HrefValue = "";
		$this->status_id->TooltipValue = "";

		// priority_id
		$this->priority_id->LinkCustomAttributes = "";
		$this->priority_id->HrefValue = "";
		$this->priority_id->TooltipValue = "";

		// assigned_to
		$this->assigned_to->LinkCustomAttributes = "";
		$this->assigned_to->HrefValue = "";
		$this->assigned_to->TooltipValue = "";

		// start_date
		$this->start_date->LinkCustomAttributes = "";
		$this->start_date->HrefValue = "";
		$this->start_date->TooltipValue = "";

		// done_ratio
		$this->done_ratio->LinkCustomAttributes = "";
		$this->done_ratio->HrefValue = "";
		$this->done_ratio->TooltipValue = "";

		// created_on
		$this->created_on->LinkCustomAttributes = "";
		$this->created_on->HrefValue = "";
		$this->created_on->TooltipValue = "";

		// updated_on
		$this->updated_on->LinkCustomAttributes = "";
		$this->updated_on->HrefValue = "";
		$this->updated_on->TooltipValue = "";

		// due_date
		$this->due_date->LinkCustomAttributes = "";
		$this->due_date->HrefValue = "";
		$this->due_date->TooltipValue = "";

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
				if ($this->tracker_id->Exportable) $Doc->ExportCaption($this->tracker_id);
				if ($this->subject->Exportable) $Doc->ExportCaption($this->subject);
				if ($this->author_id->Exportable) $Doc->ExportCaption($this->author_id);
				if ($this->status_id->Exportable) $Doc->ExportCaption($this->status_id);
				if ($this->priority_id->Exportable) $Doc->ExportCaption($this->priority_id);
				if ($this->assigned_to->Exportable) $Doc->ExportCaption($this->assigned_to);
				if ($this->start_date->Exportable) $Doc->ExportCaption($this->start_date);
				if ($this->done_ratio->Exportable) $Doc->ExportCaption($this->done_ratio);
				if ($this->created_on->Exportable) $Doc->ExportCaption($this->created_on);
				if ($this->updated_on->Exportable) $Doc->ExportCaption($this->updated_on);
				if ($this->due_date->Exportable) $Doc->ExportCaption($this->due_date);
			} else {
				if ($this->id->Exportable) $Doc->ExportCaption($this->id);
				if ($this->project_id->Exportable) $Doc->ExportCaption($this->project_id);
				if ($this->tracker_id->Exportable) $Doc->ExportCaption($this->tracker_id);
				if ($this->subject->Exportable) $Doc->ExportCaption($this->subject);
				if ($this->author_id->Exportable) $Doc->ExportCaption($this->author_id);
				if ($this->status_id->Exportable) $Doc->ExportCaption($this->status_id);
				if ($this->priority_id->Exportable) $Doc->ExportCaption($this->priority_id);
				if ($this->assigned_to->Exportable) $Doc->ExportCaption($this->assigned_to);
				if ($this->start_date->Exportable) $Doc->ExportCaption($this->start_date);
				if ($this->done_ratio->Exportable) $Doc->ExportCaption($this->done_ratio);
				if ($this->created_on->Exportable) $Doc->ExportCaption($this->created_on);
				if ($this->updated_on->Exportable) $Doc->ExportCaption($this->updated_on);
				if ($this->due_date->Exportable) $Doc->ExportCaption($this->due_date);
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
					if ($this->tracker_id->Exportable) $Doc->ExportField($this->tracker_id);
					if ($this->subject->Exportable) $Doc->ExportField($this->subject);
					if ($this->author_id->Exportable) $Doc->ExportField($this->author_id);
					if ($this->status_id->Exportable) $Doc->ExportField($this->status_id);
					if ($this->priority_id->Exportable) $Doc->ExportField($this->priority_id);
					if ($this->assigned_to->Exportable) $Doc->ExportField($this->assigned_to);
					if ($this->start_date->Exportable) $Doc->ExportField($this->start_date);
					if ($this->done_ratio->Exportable) $Doc->ExportField($this->done_ratio);
					if ($this->created_on->Exportable) $Doc->ExportField($this->created_on);
					if ($this->updated_on->Exportable) $Doc->ExportField($this->updated_on);
					if ($this->due_date->Exportable) $Doc->ExportField($this->due_date);
				} else {
					if ($this->id->Exportable) $Doc->ExportField($this->id);
					if ($this->project_id->Exportable) $Doc->ExportField($this->project_id);
					if ($this->tracker_id->Exportable) $Doc->ExportField($this->tracker_id);
					if ($this->subject->Exportable) $Doc->ExportField($this->subject);
					if ($this->author_id->Exportable) $Doc->ExportField($this->author_id);
					if ($this->status_id->Exportable) $Doc->ExportField($this->status_id);
					if ($this->priority_id->Exportable) $Doc->ExportField($this->priority_id);
					if ($this->assigned_to->Exportable) $Doc->ExportField($this->assigned_to);
					if ($this->start_date->Exportable) $Doc->ExportField($this->start_date);
					if ($this->done_ratio->Exportable) $Doc->ExportField($this->done_ratio);
					if ($this->created_on->Exportable) $Doc->ExportField($this->created_on);
					if ($this->updated_on->Exportable) $Doc->ExportField($this->updated_on);
					if ($this->due_date->Exportable) $Doc->ExportField($this->due_date);
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
