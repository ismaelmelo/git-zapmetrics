<?php

// Global variable for table object
$usuario = NULL;

//
// Table class for usuario
//
class cusuario extends cTable {
	var $nu_usuario;
	var $nu_pessoa;
	var $nu_usuarioRedmine;
	var $no_usuario;
	var $no_login;
	var $no_senha;
	var $no_email;
	var $nu_perfil;
	var $nu_stUsuario;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'usuario';
		$this->TableName = 'usuario';
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

		// nu_usuario
		$this->nu_usuario = new cField('usuario', 'usuario', 'x_nu_usuario', 'nu_usuario', '[nu_usuario]', 'CAST([nu_usuario] AS NVARCHAR)', 3, -1, FALSE, '[nu_usuario]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_usuario->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_usuario'] = &$this->nu_usuario;

		// nu_pessoa
		$this->nu_pessoa = new cField('usuario', 'usuario', 'x_nu_pessoa', 'nu_pessoa', '[nu_pessoa]', 'CAST([nu_pessoa] AS NVARCHAR)', 3, -1, FALSE, '[EV__nu_pessoa]', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->nu_pessoa->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_pessoa'] = &$this->nu_pessoa;

		// nu_usuarioRedmine
		$this->nu_usuarioRedmine = new cField('usuario', 'usuario', 'x_nu_usuarioRedmine', 'nu_usuarioRedmine', '[nu_usuarioRedmine]', 'CAST([nu_usuarioRedmine] AS NVARCHAR)', 3, -1, FALSE, '[nu_usuarioRedmine]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_usuarioRedmine->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_usuarioRedmine'] = &$this->nu_usuarioRedmine;

		// no_usuario
		$this->no_usuario = new cField('usuario', 'usuario', 'x_no_usuario', 'no_usuario', '[no_usuario]', '[no_usuario]', 200, -1, FALSE, '[no_usuario]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_usuario'] = &$this->no_usuario;

		// no_login
		$this->no_login = new cField('usuario', 'usuario', 'x_no_login', 'no_login', '[no_login]', '[no_login]', 200, -1, FALSE, '[no_login]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_login'] = &$this->no_login;

		// no_senha
		$this->no_senha = new cField('usuario', 'usuario', 'x_no_senha', 'no_senha', '[no_senha]', '[no_senha]', 200, -1, FALSE, '[no_senha]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_senha'] = &$this->no_senha;

		// no_email
		$this->no_email = new cField('usuario', 'usuario', 'x_no_email', 'no_email', '[no_email]', '[no_email]', 200, -1, FALSE, '[no_email]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->no_email->FldDefaultErrMsg = $Language->Phrase("IncorrectEmail");
		$this->fields['no_email'] = &$this->no_email;

		// nu_perfil
		$this->nu_perfil = new cField('usuario', 'usuario', 'x_nu_perfil', 'nu_perfil', '[nu_perfil]', 'CAST([nu_perfil] AS NVARCHAR)', 3, -1, FALSE, '[nu_perfil]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_perfil->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_perfil'] = &$this->nu_perfil;

		// nu_stUsuario
		$this->nu_stUsuario = new cField('usuario', 'usuario', 'x_nu_stUsuario', 'nu_stUsuario', '[nu_stUsuario]', 'CAST([nu_stUsuario] AS NVARCHAR)', 3, -1, FALSE, '[nu_stUsuario]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_stUsuario->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_stUsuario'] = &$this->nu_stUsuario;
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
		return "[dbo].[usuario]";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlSelectList() { // Select for List page
		return "SELECT * FROM (" .
			"SELECT *, (SELECT TOP 1 [no_pessoa] FROM [pessoa] [EW_TMP_LOOKUPTABLE] WHERE [EW_TMP_LOOKUPTABLE].[nu_pessoa] = [usuario].[nu_pessoa]) AS [EV__nu_pessoa] FROM [dbo].[usuario]" .
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
		return "[no_usuario] ASC";
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
		global $Security;

		// Add User ID filter
		if (!$this->AllowAnonymousUser() && $Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
			$sFilter = $this->AddUserIDFilter($sFilter);
		}
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = $this->UserIDAllowSecurity;
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
	var $UpdateTable = "[dbo].[usuario]";

	// INSERT statement
	function InsertSQL(&$rs) {
		global $conn;
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			if (EW_ENCRYPTED_PASSWORD && $name == 'no_senha')
				$value = (EW_CASE_SENSITIVE_PASSWORD) ? ew_EncryptPassword($value) : ew_EncryptPassword(strtolower($value));
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
			if (EW_ENCRYPTED_PASSWORD && $name == 'no_senha') {
				$value = (EW_CASE_SENSITIVE_PASSWORD) ? ew_EncryptPassword($value) : ew_EncryptPassword(strtolower($value));
			}
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
			if (array_key_exists('nu_usuario', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_usuario') . '=' . ew_QuotedValue($rs['nu_usuario'], $this->nu_usuario->FldDataType));
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
		return "[nu_usuario] = @nu_usuario@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nu_usuario->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_usuario@", ew_AdjustSql($this->nu_usuario->CurrentValue), $sKeyFilter); // Replace key value
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
			return "usuariolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "usuariolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("usuarioview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("usuarioview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "usuarioadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("usuarioedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("usuarioadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("usuariodelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nu_usuario->CurrentValue)) {
			$sUrl .= "nu_usuario=" . urlencode($this->nu_usuario->CurrentValue);
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
			$arKeys[] = @$_GET["nu_usuario"]; // nu_usuario

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
			$this->nu_usuario->CurrentValue = $key;
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
		$this->nu_usuario->setDbValue($rs->fields('nu_usuario'));
		$this->nu_pessoa->setDbValue($rs->fields('nu_pessoa'));
		$this->nu_usuarioRedmine->setDbValue($rs->fields('nu_usuarioRedmine'));
		$this->no_usuario->setDbValue($rs->fields('no_usuario'));
		$this->no_login->setDbValue($rs->fields('no_login'));
		$this->no_senha->setDbValue($rs->fields('no_senha'));
		$this->no_email->setDbValue($rs->fields('no_email'));
		$this->nu_perfil->setDbValue($rs->fields('nu_perfil'));
		$this->nu_stUsuario->setDbValue($rs->fields('nu_stUsuario'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_usuario

		$this->nu_usuario->CellCssStyle = "white-space: nowrap;";

		// nu_pessoa
		// nu_usuarioRedmine
		// no_usuario
		// no_login
		// no_senha
		// no_email
		// nu_perfil
		// nu_stUsuario
		// nu_usuario

		$this->nu_usuario->ViewValue = $this->nu_usuario->CurrentValue;
		$this->nu_usuario->ViewCustomAttributes = "";

		// nu_pessoa
		if ($this->nu_pessoa->VirtualValue <> "") {
			$this->nu_pessoa->ViewValue = $this->nu_pessoa->VirtualValue;
		} else {
		if (strval($this->nu_pessoa->CurrentValue) <> "") {
			$sFilterWrk = "[nu_pessoa]" . ew_SearchString("=", $this->nu_pessoa->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_pessoa], [no_pessoa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[pessoa]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
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

		// nu_usuarioRedmine
		if (strval($this->nu_usuarioRedmine->CurrentValue) <> "") {
			$sFilterWrk = "[id]" . ew_SearchString("=", $this->nu_usuarioRedmine->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [id], [login] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tbrdm_users]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_usuarioRedmine, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [name] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_usuarioRedmine->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_usuarioRedmine->ViewValue = $this->nu_usuarioRedmine->CurrentValue;
			}
		} else {
			$this->nu_usuarioRedmine->ViewValue = NULL;
		}
		$this->nu_usuarioRedmine->ViewCustomAttributes = "";

		// no_usuario
		$this->no_usuario->ViewValue = $this->no_usuario->CurrentValue;
		$this->no_usuario->ViewCustomAttributes = "";

		// no_login
		$this->no_login->ViewValue = $this->no_login->CurrentValue;
		$this->no_login->ViewCustomAttributes = "";

		// no_senha
		$this->no_senha->ViewValue = "********";
		$this->no_senha->ViewCustomAttributes = "";

		// no_email
		$this->no_email->ViewValue = $this->no_email->CurrentValue;
		$this->no_email->ViewCustomAttributes = "";

		// nu_perfil
		if ($Security->CanAdmin()) { // System admin
		if (strval($this->nu_perfil->CurrentValue) <> "") {
			$sFilterWrk = "[nu_level]" . ew_SearchString("=", $this->nu_perfil->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_level], [no_level] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario_permissoes]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_perfil, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_perfil->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_perfil->ViewValue = $this->nu_perfil->CurrentValue;
			}
		} else {
			$this->nu_perfil->ViewValue = NULL;
		}
		} else {
			$this->nu_perfil->ViewValue = "********";
		}
		$this->nu_perfil->ViewCustomAttributes = "";

		// nu_stUsuario
		if (strval($this->nu_stUsuario->CurrentValue) <> "") {
			$sFilterWrk = "[nu_stUsuario]" . ew_SearchString("=", $this->nu_stUsuario->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_stUsuario], [no_stUsuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[stusuario]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_stUsuario, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_stUsuario->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_stUsuario->ViewValue = $this->nu_stUsuario->CurrentValue;
			}
		} else {
			$this->nu_stUsuario->ViewValue = NULL;
		}
		$this->nu_stUsuario->ViewCustomAttributes = "";

		// nu_usuario
		$this->nu_usuario->LinkCustomAttributes = "";
		$this->nu_usuario->HrefValue = "";
		$this->nu_usuario->TooltipValue = "";

		// nu_pessoa
		$this->nu_pessoa->LinkCustomAttributes = "";
		$this->nu_pessoa->HrefValue = "";
		$this->nu_pessoa->TooltipValue = "";

		// nu_usuarioRedmine
		$this->nu_usuarioRedmine->LinkCustomAttributes = "";
		$this->nu_usuarioRedmine->HrefValue = "";
		$this->nu_usuarioRedmine->TooltipValue = "";

		// no_usuario
		$this->no_usuario->LinkCustomAttributes = "";
		$this->no_usuario->HrefValue = "";
		$this->no_usuario->TooltipValue = "";

		// no_login
		$this->no_login->LinkCustomAttributes = "";
		$this->no_login->HrefValue = "";
		$this->no_login->TooltipValue = "";

		// no_senha
		$this->no_senha->LinkCustomAttributes = "";
		$this->no_senha->HrefValue = "";
		$this->no_senha->TooltipValue = "";

		// no_email
		$this->no_email->LinkCustomAttributes = "";
		$this->no_email->HrefValue = "";
		$this->no_email->TooltipValue = "";

		// nu_perfil
		$this->nu_perfil->LinkCustomAttributes = "";
		$this->nu_perfil->HrefValue = "";
		$this->nu_perfil->TooltipValue = "";

		// nu_stUsuario
		$this->nu_stUsuario->LinkCustomAttributes = "";
		$this->nu_stUsuario->HrefValue = "";
		$this->nu_stUsuario->TooltipValue = "";

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
				if ($this->nu_usuarioRedmine->Exportable) $Doc->ExportCaption($this->nu_usuarioRedmine);
				if ($this->no_usuario->Exportable) $Doc->ExportCaption($this->no_usuario);
				if ($this->no_login->Exportable) $Doc->ExportCaption($this->no_login);
				if ($this->no_senha->Exportable) $Doc->ExportCaption($this->no_senha);
				if ($this->no_email->Exportable) $Doc->ExportCaption($this->no_email);
				if ($this->nu_perfil->Exportable) $Doc->ExportCaption($this->nu_perfil);
				if ($this->nu_stUsuario->Exportable) $Doc->ExportCaption($this->nu_stUsuario);
			} else {
				if ($this->nu_usuario->Exportable) $Doc->ExportCaption($this->nu_usuario);
				if ($this->nu_pessoa->Exportable) $Doc->ExportCaption($this->nu_pessoa);
				if ($this->nu_usuarioRedmine->Exportable) $Doc->ExportCaption($this->nu_usuarioRedmine);
				if ($this->no_usuario->Exportable) $Doc->ExportCaption($this->no_usuario);
				if ($this->no_login->Exportable) $Doc->ExportCaption($this->no_login);
				if ($this->no_senha->Exportable) $Doc->ExportCaption($this->no_senha);
				if ($this->no_email->Exportable) $Doc->ExportCaption($this->no_email);
				if ($this->nu_perfil->Exportable) $Doc->ExportCaption($this->nu_perfil);
				if ($this->nu_stUsuario->Exportable) $Doc->ExportCaption($this->nu_stUsuario);
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
					if ($this->nu_usuarioRedmine->Exportable) $Doc->ExportField($this->nu_usuarioRedmine);
					if ($this->no_usuario->Exportable) $Doc->ExportField($this->no_usuario);
					if ($this->no_login->Exportable) $Doc->ExportField($this->no_login);
					if ($this->no_senha->Exportable) $Doc->ExportField($this->no_senha);
					if ($this->no_email->Exportable) $Doc->ExportField($this->no_email);
					if ($this->nu_perfil->Exportable) $Doc->ExportField($this->nu_perfil);
					if ($this->nu_stUsuario->Exportable) $Doc->ExportField($this->nu_stUsuario);
				} else {
					if ($this->nu_usuario->Exportable) $Doc->ExportField($this->nu_usuario);
					if ($this->nu_pessoa->Exportable) $Doc->ExportField($this->nu_pessoa);
					if ($this->nu_usuarioRedmine->Exportable) $Doc->ExportField($this->nu_usuarioRedmine);
					if ($this->no_usuario->Exportable) $Doc->ExportField($this->no_usuario);
					if ($this->no_login->Exportable) $Doc->ExportField($this->no_login);
					if ($this->no_senha->Exportable) $Doc->ExportField($this->no_senha);
					if ($this->no_email->Exportable) $Doc->ExportField($this->no_email);
					if ($this->nu_perfil->Exportable) $Doc->ExportField($this->nu_perfil);
					if ($this->nu_stUsuario->Exportable) $Doc->ExportField($this->nu_stUsuario);
				}
				$Doc->EndExportRow();
			}
			$Recordset->MoveNext();
		}
		$Doc->ExportTableFooter();
	}

	// User ID filter
	function UserIDFilter($userid) {
		$sUserIDFilter = '[nu_usuario] = ' . ew_QuotedValue($userid, EW_DATATYPE_NUMBER);
		return $sUserIDFilter;
	}

	// Add User ID filter
	function AddUserIDFilter($sFilter) {
		global $Security;
		$sFilterWrk = "";
		$id = (CurrentPageID() == "list") ? $this->CurrentAction : CurrentPageID();
		if (!$this->UserIDAllow($id) && !$Security->IsAdmin()) {
			$sFilterWrk = $Security->UserIDList();
			if ($sFilterWrk <> "")
				$sFilterWrk = '[nu_usuario] IN (' . $sFilterWrk . ')';
		}

		// Call Row Rendered event
		$this->UserID_Filtering($sFilterWrk);
		ew_AddFilter($sFilter, $sFilterWrk);
		return $sFilter;
	}

	// User ID subquery
	function GetUserIDSubquery(&$fld, &$masterfld) {
		global $conn;
		$sWrk = "";
		$sSql = "SELECT " . $masterfld->FldExpression . " FROM [dbo].[usuario]";
		$sFilter = $this->AddUserIDFilter("");
		if ($sFilter <> "") $sSql .= " WHERE " . $sFilter;

		// Use subquery
		if (EW_USE_SUBQUERY_FOR_MASTER_USER_ID) {
			$sWrk = $sSql;
		} else {

			// List all values
			if ($rs = $conn->Execute($sSql)) {
				while (!$rs->EOF) {
					if ($sWrk <> "") $sWrk .= ",";
					$sWrk .= ew_QuotedValue($rs->fields[0], $masterfld->FldDataType);
					$rs->MoveNext();
				}
				$rs->Close();
			}
		}
		if ($sWrk <> "") {
			$sWrk = $fld->FldExpression . " IN (" . $sWrk . ")";
		}
		return $sWrk;
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
