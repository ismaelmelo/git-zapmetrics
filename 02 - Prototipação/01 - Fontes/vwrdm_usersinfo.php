<?php

// Global variable for table object
$vwrdm_users = NULL;

//
// Table class for vwrdm_users
//
class cvwrdm_users extends cTable {
	var $id;
	var $_login;
	var $hashed_password;
	var $firstname;
	var $lastname;
	var $mail;
	var $admin;
	var $status;
	var $last_login_on;
	var $_language;
	var $auth_source_id;
	var $created_on;
	var $updated_on;
	var $type;
	var $identity_url;
	var $mail_notification;
	var $salt;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'vwrdm_users';
		$this->TableName = 'vwrdm_users';
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
		$this->id = new cField('vwrdm_users', 'vwrdm_users', 'x_id', 'id', '[id]', 'CAST([id] AS NVARCHAR)', 3, -1, FALSE, '[id]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// login
		$this->_login = new cField('vwrdm_users', 'vwrdm_users', 'x__login', 'login', '[login]', '[login]', 202, -1, FALSE, '[login]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['login'] = &$this->_login;

		// hashed_password
		$this->hashed_password = new cField('vwrdm_users', 'vwrdm_users', 'x_hashed_password', 'hashed_password', '[hashed_password]', '[hashed_password]', 202, -1, FALSE, '[hashed_password]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['hashed_password'] = &$this->hashed_password;

		// firstname
		$this->firstname = new cField('vwrdm_users', 'vwrdm_users', 'x_firstname', 'firstname', '[firstname]', '[firstname]', 202, -1, FALSE, '[firstname]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['firstname'] = &$this->firstname;

		// lastname
		$this->lastname = new cField('vwrdm_users', 'vwrdm_users', 'x_lastname', 'lastname', '[lastname]', '[lastname]', 202, -1, FALSE, '[lastname]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['lastname'] = &$this->lastname;

		// mail
		$this->mail = new cField('vwrdm_users', 'vwrdm_users', 'x_mail', 'mail', '[mail]', '[mail]', 202, -1, FALSE, '[mail]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['mail'] = &$this->mail;

		// admin
		$this->admin = new cField('vwrdm_users', 'vwrdm_users', 'x_admin', 'admin', '[admin]', 'CAST([admin] AS NVARCHAR)', 131, -1, FALSE, '[admin]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->admin->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['admin'] = &$this->admin;

		// status
		$this->status = new cField('vwrdm_users', 'vwrdm_users', 'x_status', 'status', '[status]', 'CAST([status] AS NVARCHAR)', 3, -1, FALSE, '[status]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->status->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['status'] = &$this->status;

		// last_login_on
		$this->last_login_on = new cField('vwrdm_users', 'vwrdm_users', 'x_last_login_on', 'last_login_on', '[last_login_on]', '(REPLACE(STR(DAY([last_login_on]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([last_login_on]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([last_login_on]),4,0))', 135, 7, FALSE, '[last_login_on]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->last_login_on->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['last_login_on'] = &$this->last_login_on;

		// language
		$this->_language = new cField('vwrdm_users', 'vwrdm_users', 'x__language', 'language', '[language]', '[language]', 202, -1, FALSE, '[language]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['language'] = &$this->_language;

		// auth_source_id
		$this->auth_source_id = new cField('vwrdm_users', 'vwrdm_users', 'x_auth_source_id', 'auth_source_id', '[auth_source_id]', 'CAST([auth_source_id] AS NVARCHAR)', 3, -1, FALSE, '[auth_source_id]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->auth_source_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['auth_source_id'] = &$this->auth_source_id;

		// created_on
		$this->created_on = new cField('vwrdm_users', 'vwrdm_users', 'x_created_on', 'created_on', '[created_on]', '(REPLACE(STR(DAY([created_on]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([created_on]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([created_on]),4,0))', 135, 7, FALSE, '[created_on]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->created_on->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['created_on'] = &$this->created_on;

		// updated_on
		$this->updated_on = new cField('vwrdm_users', 'vwrdm_users', 'x_updated_on', 'updated_on', '[updated_on]', '(REPLACE(STR(DAY([updated_on]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([updated_on]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([updated_on]),4,0))', 135, 7, FALSE, '[updated_on]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->updated_on->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['updated_on'] = &$this->updated_on;

		// type
		$this->type = new cField('vwrdm_users', 'vwrdm_users', 'x_type', 'type', '[type]', '[type]', 202, -1, FALSE, '[type]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['type'] = &$this->type;

		// identity_url
		$this->identity_url = new cField('vwrdm_users', 'vwrdm_users', 'x_identity_url', 'identity_url', '[identity_url]', '[identity_url]', 202, -1, FALSE, '[identity_url]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['identity_url'] = &$this->identity_url;

		// mail_notification
		$this->mail_notification = new cField('vwrdm_users', 'vwrdm_users', 'x_mail_notification', 'mail_notification', '[mail_notification]', '[mail_notification]', 202, -1, FALSE, '[mail_notification]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['mail_notification'] = &$this->mail_notification;

		// salt
		$this->salt = new cField('vwrdm_users', 'vwrdm_users', 'x_salt', 'salt', '[salt]', '[salt]', 202, -1, FALSE, '[salt]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['salt'] = &$this->salt;
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
		return "[db_owner].[vwrdm_users]";
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
	var $UpdateTable = "[db_owner].[vwrdm_users]";

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
			return "vwrdm_userslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "vwrdm_userslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("vwrdm_usersview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("vwrdm_usersview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "vwrdm_usersadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("vwrdm_usersedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("vwrdm_usersadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("vwrdm_usersdelete.php", $this->UrlParm());
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
		$this->_login->setDbValue($rs->fields('login'));
		$this->hashed_password->setDbValue($rs->fields('hashed_password'));
		$this->firstname->setDbValue($rs->fields('firstname'));
		$this->lastname->setDbValue($rs->fields('lastname'));
		$this->mail->setDbValue($rs->fields('mail'));
		$this->admin->setDbValue($rs->fields('admin'));
		$this->status->setDbValue($rs->fields('status'));
		$this->last_login_on->setDbValue($rs->fields('last_login_on'));
		$this->_language->setDbValue($rs->fields('language'));
		$this->auth_source_id->setDbValue($rs->fields('auth_source_id'));
		$this->created_on->setDbValue($rs->fields('created_on'));
		$this->updated_on->setDbValue($rs->fields('updated_on'));
		$this->type->setDbValue($rs->fields('type'));
		$this->identity_url->setDbValue($rs->fields('identity_url'));
		$this->mail_notification->setDbValue($rs->fields('mail_notification'));
		$this->salt->setDbValue($rs->fields('salt'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id
		// login
		// hashed_password
		// firstname
		// lastname
		// mail
		// admin
		// status
		// last_login_on
		// language
		// auth_source_id
		// created_on
		// updated_on
		// type
		// identity_url
		// mail_notification
		// salt
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// login
		$this->_login->ViewValue = $this->_login->CurrentValue;
		$this->_login->ViewCustomAttributes = "";

		// hashed_password
		$this->hashed_password->ViewValue = $this->hashed_password->CurrentValue;
		$this->hashed_password->ViewCustomAttributes = "";

		// firstname
		$this->firstname->ViewValue = $this->firstname->CurrentValue;
		$this->firstname->ViewCustomAttributes = "";

		// lastname
		$this->lastname->ViewValue = $this->lastname->CurrentValue;
		$this->lastname->ViewCustomAttributes = "";

		// mail
		$this->mail->ViewValue = $this->mail->CurrentValue;
		$this->mail->ViewCustomAttributes = "";

		// admin
		$this->admin->ViewValue = $this->admin->CurrentValue;
		$this->admin->ViewCustomAttributes = "";

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		$this->status->ViewCustomAttributes = "";

		// last_login_on
		$this->last_login_on->ViewValue = $this->last_login_on->CurrentValue;
		$this->last_login_on->ViewValue = ew_FormatDateTime($this->last_login_on->ViewValue, 7);
		$this->last_login_on->ViewCustomAttributes = "";

		// language
		$this->_language->ViewValue = $this->_language->CurrentValue;
		$this->_language->ViewCustomAttributes = "";

		// auth_source_id
		$this->auth_source_id->ViewValue = $this->auth_source_id->CurrentValue;
		$this->auth_source_id->ViewCustomAttributes = "";

		// created_on
		$this->created_on->ViewValue = $this->created_on->CurrentValue;
		$this->created_on->ViewValue = ew_FormatDateTime($this->created_on->ViewValue, 7);
		$this->created_on->ViewCustomAttributes = "";

		// updated_on
		$this->updated_on->ViewValue = $this->updated_on->CurrentValue;
		$this->updated_on->ViewValue = ew_FormatDateTime($this->updated_on->ViewValue, 7);
		$this->updated_on->ViewCustomAttributes = "";

		// type
		$this->type->ViewValue = $this->type->CurrentValue;
		$this->type->ViewCustomAttributes = "";

		// identity_url
		$this->identity_url->ViewValue = $this->identity_url->CurrentValue;
		$this->identity_url->ViewCustomAttributes = "";

		// mail_notification
		$this->mail_notification->ViewValue = $this->mail_notification->CurrentValue;
		$this->mail_notification->ViewCustomAttributes = "";

		// salt
		$this->salt->ViewValue = $this->salt->CurrentValue;
		$this->salt->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// login
		$this->_login->LinkCustomAttributes = "";
		$this->_login->HrefValue = "";
		$this->_login->TooltipValue = "";

		// hashed_password
		$this->hashed_password->LinkCustomAttributes = "";
		$this->hashed_password->HrefValue = "";
		$this->hashed_password->TooltipValue = "";

		// firstname
		$this->firstname->LinkCustomAttributes = "";
		$this->firstname->HrefValue = "";
		$this->firstname->TooltipValue = "";

		// lastname
		$this->lastname->LinkCustomAttributes = "";
		$this->lastname->HrefValue = "";
		$this->lastname->TooltipValue = "";

		// mail
		$this->mail->LinkCustomAttributes = "";
		$this->mail->HrefValue = "";
		$this->mail->TooltipValue = "";

		// admin
		$this->admin->LinkCustomAttributes = "";
		$this->admin->HrefValue = "";
		$this->admin->TooltipValue = "";

		// status
		$this->status->LinkCustomAttributes = "";
		$this->status->HrefValue = "";
		$this->status->TooltipValue = "";

		// last_login_on
		$this->last_login_on->LinkCustomAttributes = "";
		$this->last_login_on->HrefValue = "";
		$this->last_login_on->TooltipValue = "";

		// language
		$this->_language->LinkCustomAttributes = "";
		$this->_language->HrefValue = "";
		$this->_language->TooltipValue = "";

		// auth_source_id
		$this->auth_source_id->LinkCustomAttributes = "";
		$this->auth_source_id->HrefValue = "";
		$this->auth_source_id->TooltipValue = "";

		// created_on
		$this->created_on->LinkCustomAttributes = "";
		$this->created_on->HrefValue = "";
		$this->created_on->TooltipValue = "";

		// updated_on
		$this->updated_on->LinkCustomAttributes = "";
		$this->updated_on->HrefValue = "";
		$this->updated_on->TooltipValue = "";

		// type
		$this->type->LinkCustomAttributes = "";
		$this->type->HrefValue = "";
		$this->type->TooltipValue = "";

		// identity_url
		$this->identity_url->LinkCustomAttributes = "";
		$this->identity_url->HrefValue = "";
		$this->identity_url->TooltipValue = "";

		// mail_notification
		$this->mail_notification->LinkCustomAttributes = "";
		$this->mail_notification->HrefValue = "";
		$this->mail_notification->TooltipValue = "";

		// salt
		$this->salt->LinkCustomAttributes = "";
		$this->salt->HrefValue = "";
		$this->salt->TooltipValue = "";

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
				if ($this->_login->Exportable) $Doc->ExportCaption($this->_login);
				if ($this->hashed_password->Exportable) $Doc->ExportCaption($this->hashed_password);
				if ($this->firstname->Exportable) $Doc->ExportCaption($this->firstname);
				if ($this->lastname->Exportable) $Doc->ExportCaption($this->lastname);
				if ($this->mail->Exportable) $Doc->ExportCaption($this->mail);
				if ($this->admin->Exportable) $Doc->ExportCaption($this->admin);
				if ($this->status->Exportable) $Doc->ExportCaption($this->status);
				if ($this->last_login_on->Exportable) $Doc->ExportCaption($this->last_login_on);
				if ($this->_language->Exportable) $Doc->ExportCaption($this->_language);
				if ($this->auth_source_id->Exportable) $Doc->ExportCaption($this->auth_source_id);
				if ($this->created_on->Exportable) $Doc->ExportCaption($this->created_on);
				if ($this->updated_on->Exportable) $Doc->ExportCaption($this->updated_on);
				if ($this->type->Exportable) $Doc->ExportCaption($this->type);
				if ($this->identity_url->Exportable) $Doc->ExportCaption($this->identity_url);
				if ($this->mail_notification->Exportable) $Doc->ExportCaption($this->mail_notification);
				if ($this->salt->Exportable) $Doc->ExportCaption($this->salt);
			} else {
				if ($this->id->Exportable) $Doc->ExportCaption($this->id);
				if ($this->_login->Exportable) $Doc->ExportCaption($this->_login);
				if ($this->hashed_password->Exportable) $Doc->ExportCaption($this->hashed_password);
				if ($this->firstname->Exportable) $Doc->ExportCaption($this->firstname);
				if ($this->lastname->Exportable) $Doc->ExportCaption($this->lastname);
				if ($this->mail->Exportable) $Doc->ExportCaption($this->mail);
				if ($this->admin->Exportable) $Doc->ExportCaption($this->admin);
				if ($this->status->Exportable) $Doc->ExportCaption($this->status);
				if ($this->last_login_on->Exportable) $Doc->ExportCaption($this->last_login_on);
				if ($this->_language->Exportable) $Doc->ExportCaption($this->_language);
				if ($this->auth_source_id->Exportable) $Doc->ExportCaption($this->auth_source_id);
				if ($this->created_on->Exportable) $Doc->ExportCaption($this->created_on);
				if ($this->updated_on->Exportable) $Doc->ExportCaption($this->updated_on);
				if ($this->type->Exportable) $Doc->ExportCaption($this->type);
				if ($this->identity_url->Exportable) $Doc->ExportCaption($this->identity_url);
				if ($this->mail_notification->Exportable) $Doc->ExportCaption($this->mail_notification);
				if ($this->salt->Exportable) $Doc->ExportCaption($this->salt);
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
					if ($this->_login->Exportable) $Doc->ExportField($this->_login);
					if ($this->hashed_password->Exportable) $Doc->ExportField($this->hashed_password);
					if ($this->firstname->Exportable) $Doc->ExportField($this->firstname);
					if ($this->lastname->Exportable) $Doc->ExportField($this->lastname);
					if ($this->mail->Exportable) $Doc->ExportField($this->mail);
					if ($this->admin->Exportable) $Doc->ExportField($this->admin);
					if ($this->status->Exportable) $Doc->ExportField($this->status);
					if ($this->last_login_on->Exportable) $Doc->ExportField($this->last_login_on);
					if ($this->_language->Exportable) $Doc->ExportField($this->_language);
					if ($this->auth_source_id->Exportable) $Doc->ExportField($this->auth_source_id);
					if ($this->created_on->Exportable) $Doc->ExportField($this->created_on);
					if ($this->updated_on->Exportable) $Doc->ExportField($this->updated_on);
					if ($this->type->Exportable) $Doc->ExportField($this->type);
					if ($this->identity_url->Exportable) $Doc->ExportField($this->identity_url);
					if ($this->mail_notification->Exportable) $Doc->ExportField($this->mail_notification);
					if ($this->salt->Exportable) $Doc->ExportField($this->salt);
				} else {
					if ($this->id->Exportable) $Doc->ExportField($this->id);
					if ($this->_login->Exportable) $Doc->ExportField($this->_login);
					if ($this->hashed_password->Exportable) $Doc->ExportField($this->hashed_password);
					if ($this->firstname->Exportable) $Doc->ExportField($this->firstname);
					if ($this->lastname->Exportable) $Doc->ExportField($this->lastname);
					if ($this->mail->Exportable) $Doc->ExportField($this->mail);
					if ($this->admin->Exportable) $Doc->ExportField($this->admin);
					if ($this->status->Exportable) $Doc->ExportField($this->status);
					if ($this->last_login_on->Exportable) $Doc->ExportField($this->last_login_on);
					if ($this->_language->Exportable) $Doc->ExportField($this->_language);
					if ($this->auth_source_id->Exportable) $Doc->ExportField($this->auth_source_id);
					if ($this->created_on->Exportable) $Doc->ExportField($this->created_on);
					if ($this->updated_on->Exportable) $Doc->ExportField($this->updated_on);
					if ($this->type->Exportable) $Doc->ExportField($this->type);
					if ($this->identity_url->Exportable) $Doc->ExportField($this->identity_url);
					if ($this->mail_notification->Exportable) $Doc->ExportField($this->mail_notification);
					if ($this->salt->Exportable) $Doc->ExportField($this->salt);
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
