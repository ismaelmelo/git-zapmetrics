<?php

// Global variable for table object
$relhoras = NULL;

//
// Table class for relhoras
//
class crelhoras extends cTable {
	var $id_lancamento;
	var $id_usuario;
	var $ddmmyyyy;
	var $ddmm;
	var $dia;
	var $mes;
	var $ano;
	var $id_projeto;
	var $id_tarefa;
	var $titulo;
	var $qt_horas;
	var $obs;
	var $tp_tarefa;
	var $situacao;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'relhoras';
		$this->TableName = 'relhoras';
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

		// id_lancamento
		$this->id_lancamento = new cField('relhoras', 'relhoras', 'x_id_lancamento', 'id_lancamento', '[id_lancamento]', 'CAST([id_lancamento] AS NVARCHAR)', 3, -1, FALSE, '[id_lancamento]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_lancamento->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_lancamento'] = &$this->id_lancamento;

		// id_usuario
		$this->id_usuario = new cField('relhoras', 'relhoras', 'x_id_usuario', 'id_usuario', '[id_usuario]', 'CAST([id_usuario] AS NVARCHAR)', 3, -1, FALSE, '[id_usuario]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_usuario->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_usuario'] = &$this->id_usuario;

		// ddmmyyyy
		$this->ddmmyyyy = new cField('relhoras', 'relhoras', 'x_ddmmyyyy', 'ddmmyyyy', '[ddmmyyyy]', 'CAST([ddmmyyyy] AS NVARCHAR)', 3, 7, FALSE, '[ddmmyyyy]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->ddmmyyyy->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['ddmmyyyy'] = &$this->ddmmyyyy;

		// ddmm
		$this->ddmm = new cField('relhoras', 'relhoras', 'x_ddmm', 'ddmm', '[ddmm]', 'CAST([ddmm] AS NVARCHAR)', 3, -1, FALSE, '[ddmm]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->ddmm->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['ddmm'] = &$this->ddmm;

		// dia
		$this->dia = new cField('relhoras', 'relhoras', 'x_dia', 'dia', '[dia]', 'CAST([dia] AS NVARCHAR)', 3, -1, FALSE, '[dia]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dia->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['dia'] = &$this->dia;

		// mes
		$this->mes = new cField('relhoras', 'relhoras', 'x_mes', 'mes', '[mes]', 'CAST([mes] AS NVARCHAR)', 3, -1, FALSE, '[mes]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->mes->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['mes'] = &$this->mes;

		// ano
		$this->ano = new cField('relhoras', 'relhoras', 'x_ano', 'ano', '[ano]', 'CAST([ano] AS NVARCHAR)', 3, -1, FALSE, '[ano]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->ano->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['ano'] = &$this->ano;

		// id_projeto
		$this->id_projeto = new cField('relhoras', 'relhoras', 'x_id_projeto', 'id_projeto', '[id_projeto]', 'CAST([id_projeto] AS NVARCHAR)', 3, -1, FALSE, '[id_projeto]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_projeto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_projeto'] = &$this->id_projeto;

		// id_tarefa
		$this->id_tarefa = new cField('relhoras', 'relhoras', 'x_id_tarefa', 'id_tarefa', '[id_tarefa]', 'CAST([id_tarefa] AS NVARCHAR)', 3, -1, FALSE, '[id_tarefa]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_tarefa->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_tarefa'] = &$this->id_tarefa;

		// titulo
		$this->titulo = new cField('relhoras', 'relhoras', 'x_titulo', 'titulo', '[titulo]', '[titulo]', 200, -1, FALSE, '[titulo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['titulo'] = &$this->titulo;

		// qt_horas
		$this->qt_horas = new cField('relhoras', 'relhoras', 'x_qt_horas', 'qt_horas', '[qt_horas]', 'CAST([qt_horas] AS NVARCHAR)', 131, -1, FALSE, '[qt_horas]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->qt_horas->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['qt_horas'] = &$this->qt_horas;

		// obs
		$this->obs = new cField('relhoras', 'relhoras', 'x_obs', 'obs', '[obs]', '[obs]', 201, -1, FALSE, '[obs]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['obs'] = &$this->obs;

		// tp_tarefa
		$this->tp_tarefa = new cField('relhoras', 'relhoras', 'x_tp_tarefa', 'tp_tarefa', '[tp_tarefa]', 'CAST([tp_tarefa] AS NVARCHAR)', 3, -1, FALSE, '[tp_tarefa]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->tp_tarefa->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['tp_tarefa'] = &$this->tp_tarefa;

		// situacao
		$this->situacao = new cField('relhoras', 'relhoras', 'x_situacao', 'situacao', '[situacao]', 'CAST([situacao] AS NVARCHAR)', 3, -1, FALSE, '[situacao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->situacao->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['situacao'] = &$this->situacao;
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
		return "[db_owner].[relhoras]";
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
	var $UpdateTable = "[db_owner].[relhoras]";

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
			return "relhoraslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "relhoraslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("relhorasview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("relhorasview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "relhorasadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("relhorasedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("relhorasadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("relhorasdelete.php", $this->UrlParm());
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
		$this->id_lancamento->setDbValue($rs->fields('id_lancamento'));
		$this->id_usuario->setDbValue($rs->fields('id_usuario'));
		$this->ddmmyyyy->setDbValue($rs->fields('ddmmyyyy'));
		$this->ddmm->setDbValue($rs->fields('ddmm'));
		$this->dia->setDbValue($rs->fields('dia'));
		$this->mes->setDbValue($rs->fields('mes'));
		$this->ano->setDbValue($rs->fields('ano'));
		$this->id_projeto->setDbValue($rs->fields('id_projeto'));
		$this->id_tarefa->setDbValue($rs->fields('id_tarefa'));
		$this->titulo->setDbValue($rs->fields('titulo'));
		$this->qt_horas->setDbValue($rs->fields('qt_horas'));
		$this->obs->setDbValue($rs->fields('obs'));
		$this->tp_tarefa->setDbValue($rs->fields('tp_tarefa'));
		$this->situacao->setDbValue($rs->fields('situacao'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id_lancamento
		// id_usuario
		// ddmmyyyy
		// ddmm
		// dia
		// mes
		// ano
		// id_projeto
		// id_tarefa
		// titulo
		// qt_horas
		// obs
		// tp_tarefa
		// situacao
		// id_lancamento

		$this->id_lancamento->ViewValue = $this->id_lancamento->CurrentValue;
		$this->id_lancamento->ViewCustomAttributes = "";

		// id_usuario
		if (strval($this->id_usuario->CurrentValue) <> "") {
			$sFilterWrk = "[id]" . ew_SearchString("=", $this->id_usuario->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[rdm_usuario]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->id_usuario, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->id_usuario->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->id_usuario->ViewValue = $this->id_usuario->CurrentValue;
			}
		} else {
			$this->id_usuario->ViewValue = NULL;
		}
		$this->id_usuario->ViewCustomAttributes = "";

		// ddmmyyyy
		$this->ddmmyyyy->ViewValue = $this->ddmmyyyy->CurrentValue;
		$this->ddmmyyyy->ViewValue = ew_FormatDateTime($this->ddmmyyyy->ViewValue, 7);
		$this->ddmmyyyy->ViewCustomAttributes = "";

		// ddmm
		$this->ddmm->ViewValue = $this->ddmm->CurrentValue;
		$this->ddmm->ViewCustomAttributes = "";

		// dia
		$this->dia->ViewValue = $this->dia->CurrentValue;
		$this->dia->ViewCustomAttributes = "";

		// mes
		if (strval($this->mes->CurrentValue) <> "") {
			switch ($this->mes->CurrentValue) {
				case $this->mes->FldTagValue(1):
					$this->mes->ViewValue = $this->mes->FldTagCaption(1) <> "" ? $this->mes->FldTagCaption(1) : $this->mes->CurrentValue;
					break;
				case $this->mes->FldTagValue(2):
					$this->mes->ViewValue = $this->mes->FldTagCaption(2) <> "" ? $this->mes->FldTagCaption(2) : $this->mes->CurrentValue;
					break;
				case $this->mes->FldTagValue(3):
					$this->mes->ViewValue = $this->mes->FldTagCaption(3) <> "" ? $this->mes->FldTagCaption(3) : $this->mes->CurrentValue;
					break;
				case $this->mes->FldTagValue(4):
					$this->mes->ViewValue = $this->mes->FldTagCaption(4) <> "" ? $this->mes->FldTagCaption(4) : $this->mes->CurrentValue;
					break;
				case $this->mes->FldTagValue(5):
					$this->mes->ViewValue = $this->mes->FldTagCaption(5) <> "" ? $this->mes->FldTagCaption(5) : $this->mes->CurrentValue;
					break;
				case $this->mes->FldTagValue(6):
					$this->mes->ViewValue = $this->mes->FldTagCaption(6) <> "" ? $this->mes->FldTagCaption(6) : $this->mes->CurrentValue;
					break;
				case $this->mes->FldTagValue(7):
					$this->mes->ViewValue = $this->mes->FldTagCaption(7) <> "" ? $this->mes->FldTagCaption(7) : $this->mes->CurrentValue;
					break;
				case $this->mes->FldTagValue(8):
					$this->mes->ViewValue = $this->mes->FldTagCaption(8) <> "" ? $this->mes->FldTagCaption(8) : $this->mes->CurrentValue;
					break;
				case $this->mes->FldTagValue(9):
					$this->mes->ViewValue = $this->mes->FldTagCaption(9) <> "" ? $this->mes->FldTagCaption(9) : $this->mes->CurrentValue;
					break;
				case $this->mes->FldTagValue(10):
					$this->mes->ViewValue = $this->mes->FldTagCaption(10) <> "" ? $this->mes->FldTagCaption(10) : $this->mes->CurrentValue;
					break;
				case $this->mes->FldTagValue(11):
					$this->mes->ViewValue = $this->mes->FldTagCaption(11) <> "" ? $this->mes->FldTagCaption(11) : $this->mes->CurrentValue;
					break;
				case $this->mes->FldTagValue(12):
					$this->mes->ViewValue = $this->mes->FldTagCaption(12) <> "" ? $this->mes->FldTagCaption(12) : $this->mes->CurrentValue;
					break;
				default:
					$this->mes->ViewValue = $this->mes->CurrentValue;
			}
		} else {
			$this->mes->ViewValue = NULL;
		}
		$this->mes->ViewCustomAttributes = "";

		// ano
		if (strval($this->ano->CurrentValue) <> "") {
			switch ($this->ano->CurrentValue) {
				case $this->ano->FldTagValue(1):
					$this->ano->ViewValue = $this->ano->FldTagCaption(1) <> "" ? $this->ano->FldTagCaption(1) : $this->ano->CurrentValue;
					break;
				case $this->ano->FldTagValue(2):
					$this->ano->ViewValue = $this->ano->FldTagCaption(2) <> "" ? $this->ano->FldTagCaption(2) : $this->ano->CurrentValue;
					break;
				case $this->ano->FldTagValue(3):
					$this->ano->ViewValue = $this->ano->FldTagCaption(3) <> "" ? $this->ano->FldTagCaption(3) : $this->ano->CurrentValue;
					break;
				case $this->ano->FldTagValue(4):
					$this->ano->ViewValue = $this->ano->FldTagCaption(4) <> "" ? $this->ano->FldTagCaption(4) : $this->ano->CurrentValue;
					break;
				case $this->ano->FldTagValue(5):
					$this->ano->ViewValue = $this->ano->FldTagCaption(5) <> "" ? $this->ano->FldTagCaption(5) : $this->ano->CurrentValue;
					break;
				case $this->ano->FldTagValue(6):
					$this->ano->ViewValue = $this->ano->FldTagCaption(6) <> "" ? $this->ano->FldTagCaption(6) : $this->ano->CurrentValue;
					break;
				case $this->ano->FldTagValue(7):
					$this->ano->ViewValue = $this->ano->FldTagCaption(7) <> "" ? $this->ano->FldTagCaption(7) : $this->ano->CurrentValue;
					break;
				case $this->ano->FldTagValue(8):
					$this->ano->ViewValue = $this->ano->FldTagCaption(8) <> "" ? $this->ano->FldTagCaption(8) : $this->ano->CurrentValue;
					break;
				case $this->ano->FldTagValue(9):
					$this->ano->ViewValue = $this->ano->FldTagCaption(9) <> "" ? $this->ano->FldTagCaption(9) : $this->ano->CurrentValue;
					break;
				default:
					$this->ano->ViewValue = $this->ano->CurrentValue;
			}
		} else {
			$this->ano->ViewValue = NULL;
		}
		$this->ano->ViewCustomAttributes = "";

		// id_projeto
		if (strval($this->id_projeto->CurrentValue) <> "") {
			$sFilterWrk = "[id]" . ew_SearchString("=", $this->id_projeto->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[rdm_projeto]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->id_projeto, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->id_projeto->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->id_projeto->ViewValue = $this->id_projeto->CurrentValue;
			}
		} else {
			$this->id_projeto->ViewValue = NULL;
		}
		$this->id_projeto->ViewCustomAttributes = "";

		// id_tarefa
		$this->id_tarefa->ViewValue = $this->id_tarefa->CurrentValue;
		$this->id_tarefa->ViewCustomAttributes = "";

		// titulo
		$this->titulo->ViewValue = $this->titulo->CurrentValue;
		$this->titulo->ViewCustomAttributes = "";

		// qt_horas
		$this->qt_horas->ViewValue = $this->qt_horas->CurrentValue;
		$this->qt_horas->ViewCustomAttributes = "";

		// obs
		$this->obs->ViewValue = $this->obs->CurrentValue;
		$this->obs->ViewCustomAttributes = "";

		// tp_tarefa
		if (strval($this->tp_tarefa->CurrentValue) <> "") {
			$sFilterWrk = "[id]" . ew_SearchString("=", $this->tp_tarefa->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[rdm_rastreador]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->tp_tarefa, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->tp_tarefa->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->tp_tarefa->ViewValue = $this->tp_tarefa->CurrentValue;
			}
		} else {
			$this->tp_tarefa->ViewValue = NULL;
		}
		$this->tp_tarefa->ViewCustomAttributes = "";

		// situacao
		if (strval($this->situacao->CurrentValue) <> "") {
			$sFilterWrk = "[id]" . ew_SearchString("=", $this->situacao->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[rdm_sttarefa]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->situacao, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->situacao->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->situacao->ViewValue = $this->situacao->CurrentValue;
			}
		} else {
			$this->situacao->ViewValue = NULL;
		}
		$this->situacao->ViewCustomAttributes = "";

		// id_lancamento
		$this->id_lancamento->LinkCustomAttributes = "";
		$this->id_lancamento->HrefValue = "";
		$this->id_lancamento->TooltipValue = "";

		// id_usuario
		$this->id_usuario->LinkCustomAttributes = "";
		$this->id_usuario->HrefValue = "";
		$this->id_usuario->TooltipValue = "";

		// ddmmyyyy
		$this->ddmmyyyy->LinkCustomAttributes = "";
		$this->ddmmyyyy->HrefValue = "";
		$this->ddmmyyyy->TooltipValue = "";

		// ddmm
		$this->ddmm->LinkCustomAttributes = "";
		$this->ddmm->HrefValue = "";
		$this->ddmm->TooltipValue = "";

		// dia
		$this->dia->LinkCustomAttributes = "";
		$this->dia->HrefValue = "";
		$this->dia->TooltipValue = "";

		// mes
		$this->mes->LinkCustomAttributes = "";
		$this->mes->HrefValue = "";
		$this->mes->TooltipValue = "";

		// ano
		$this->ano->LinkCustomAttributes = "";
		$this->ano->HrefValue = "";
		$this->ano->TooltipValue = "";

		// id_projeto
		$this->id_projeto->LinkCustomAttributes = "";
		$this->id_projeto->HrefValue = "";
		$this->id_projeto->TooltipValue = "";

		// id_tarefa
		$this->id_tarefa->LinkCustomAttributes = "";
		$this->id_tarefa->HrefValue = "";
		$this->id_tarefa->TooltipValue = "";

		// titulo
		$this->titulo->LinkCustomAttributes = "";
		$this->titulo->HrefValue = "";
		$this->titulo->TooltipValue = "";

		// qt_horas
		$this->qt_horas->LinkCustomAttributes = "";
		$this->qt_horas->HrefValue = "";
		$this->qt_horas->TooltipValue = "";

		// obs
		$this->obs->LinkCustomAttributes = "";
		$this->obs->HrefValue = "";
		$this->obs->TooltipValue = "";

		// tp_tarefa
		$this->tp_tarefa->LinkCustomAttributes = "";
		$this->tp_tarefa->HrefValue = "";
		$this->tp_tarefa->TooltipValue = "";

		// situacao
		$this->situacao->LinkCustomAttributes = "";
		$this->situacao->HrefValue = "";
		$this->situacao->TooltipValue = "";

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
				if ($this->id_lancamento->Exportable) $Doc->ExportCaption($this->id_lancamento);
				if ($this->id_usuario->Exportable) $Doc->ExportCaption($this->id_usuario);
				if ($this->ddmmyyyy->Exportable) $Doc->ExportCaption($this->ddmmyyyy);
				if ($this->ddmm->Exportable) $Doc->ExportCaption($this->ddmm);
				if ($this->dia->Exportable) $Doc->ExportCaption($this->dia);
				if ($this->mes->Exportable) $Doc->ExportCaption($this->mes);
				if ($this->ano->Exportable) $Doc->ExportCaption($this->ano);
				if ($this->id_projeto->Exportable) $Doc->ExportCaption($this->id_projeto);
				if ($this->id_tarefa->Exportable) $Doc->ExportCaption($this->id_tarefa);
				if ($this->titulo->Exportable) $Doc->ExportCaption($this->titulo);
				if ($this->qt_horas->Exportable) $Doc->ExportCaption($this->qt_horas);
				if ($this->obs->Exportable) $Doc->ExportCaption($this->obs);
				if ($this->tp_tarefa->Exportable) $Doc->ExportCaption($this->tp_tarefa);
				if ($this->situacao->Exportable) $Doc->ExportCaption($this->situacao);
			} else {
				if ($this->id_lancamento->Exportable) $Doc->ExportCaption($this->id_lancamento);
				if ($this->id_usuario->Exportable) $Doc->ExportCaption($this->id_usuario);
				if ($this->ddmmyyyy->Exportable) $Doc->ExportCaption($this->ddmmyyyy);
				if ($this->ddmm->Exportable) $Doc->ExportCaption($this->ddmm);
				if ($this->dia->Exportable) $Doc->ExportCaption($this->dia);
				if ($this->mes->Exportable) $Doc->ExportCaption($this->mes);
				if ($this->ano->Exportable) $Doc->ExportCaption($this->ano);
				if ($this->id_projeto->Exportable) $Doc->ExportCaption($this->id_projeto);
				if ($this->id_tarefa->Exportable) $Doc->ExportCaption($this->id_tarefa);
				if ($this->titulo->Exportable) $Doc->ExportCaption($this->titulo);
				if ($this->qt_horas->Exportable) $Doc->ExportCaption($this->qt_horas);
				if ($this->tp_tarefa->Exportable) $Doc->ExportCaption($this->tp_tarefa);
				if ($this->situacao->Exportable) $Doc->ExportCaption($this->situacao);
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
					if ($this->id_lancamento->Exportable) $Doc->ExportField($this->id_lancamento);
					if ($this->id_usuario->Exportable) $Doc->ExportField($this->id_usuario);
					if ($this->ddmmyyyy->Exportable) $Doc->ExportField($this->ddmmyyyy);
					if ($this->ddmm->Exportable) $Doc->ExportField($this->ddmm);
					if ($this->dia->Exportable) $Doc->ExportField($this->dia);
					if ($this->mes->Exportable) $Doc->ExportField($this->mes);
					if ($this->ano->Exportable) $Doc->ExportField($this->ano);
					if ($this->id_projeto->Exportable) $Doc->ExportField($this->id_projeto);
					if ($this->id_tarefa->Exportable) $Doc->ExportField($this->id_tarefa);
					if ($this->titulo->Exportable) $Doc->ExportField($this->titulo);
					if ($this->qt_horas->Exportable) $Doc->ExportField($this->qt_horas);
					if ($this->obs->Exportable) $Doc->ExportField($this->obs);
					if ($this->tp_tarefa->Exportable) $Doc->ExportField($this->tp_tarefa);
					if ($this->situacao->Exportable) $Doc->ExportField($this->situacao);
				} else {
					if ($this->id_lancamento->Exportable) $Doc->ExportField($this->id_lancamento);
					if ($this->id_usuario->Exportable) $Doc->ExportField($this->id_usuario);
					if ($this->ddmmyyyy->Exportable) $Doc->ExportField($this->ddmmyyyy);
					if ($this->ddmm->Exportable) $Doc->ExportField($this->ddmm);
					if ($this->dia->Exportable) $Doc->ExportField($this->dia);
					if ($this->mes->Exportable) $Doc->ExportField($this->mes);
					if ($this->ano->Exportable) $Doc->ExportField($this->ano);
					if ($this->id_projeto->Exportable) $Doc->ExportField($this->id_projeto);
					if ($this->id_tarefa->Exportable) $Doc->ExportField($this->id_tarefa);
					if ($this->titulo->Exportable) $Doc->ExportField($this->titulo);
					if ($this->qt_horas->Exportable) $Doc->ExportField($this->qt_horas);
					if ($this->tp_tarefa->Exportable) $Doc->ExportField($this->tp_tarefa);
					if ($this->situacao->Exportable) $Doc->ExportField($this->situacao);
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
