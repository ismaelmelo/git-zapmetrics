<?php

// Global variable for table object
$vwrdmd_ativCronograma = NULL;

//
// Table class for vwrdmd_ativCronograma
//
class cvwrdmd_ativCronograma extends cTable {
	var $nu_projeto;
	var $nu_versao;
	var $nu_tarefaPai;
	var $nu_tarefa;
	var $no_tarefa;
	var $nu_catAtividade;
	var $nu_situacao;
	var $qt_horasReal;
	var $qt_horasEstimada;
	var $nu_autor;
	var $no_autor;
	var $nu_responsavel;
	var $no_responsavel;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'vwrdmd_ativCronograma';
		$this->TableName = 'vwrdmd_ativCronograma';
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

		// nu_projeto
		$this->nu_projeto = new cField('vwrdmd_ativCronograma', 'vwrdmd_ativCronograma', 'x_nu_projeto', 'nu_projeto', '[nu_projeto]', 'CAST([nu_projeto] AS NVARCHAR)', 3, -1, FALSE, '[nu_projeto]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_projeto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_projeto'] = &$this->nu_projeto;

		// nu_versao
		$this->nu_versao = new cField('vwrdmd_ativCronograma', 'vwrdmd_ativCronograma', 'x_nu_versao', 'nu_versao', '[nu_versao]', 'CAST([nu_versao] AS NVARCHAR)', 3, -1, FALSE, '[nu_versao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_versao->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_versao'] = &$this->nu_versao;

		// nu_tarefaPai
		$this->nu_tarefaPai = new cField('vwrdmd_ativCronograma', 'vwrdmd_ativCronograma', 'x_nu_tarefaPai', 'nu_tarefaPai', '[nu_tarefaPai]', 'CAST([nu_tarefaPai] AS NVARCHAR)', 3, -1, FALSE, '[nu_tarefaPai]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_tarefaPai->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_tarefaPai'] = &$this->nu_tarefaPai;

		// nu_tarefa
		$this->nu_tarefa = new cField('vwrdmd_ativCronograma', 'vwrdmd_ativCronograma', 'x_nu_tarefa', 'nu_tarefa', '[nu_tarefa]', 'CAST([nu_tarefa] AS NVARCHAR)', 3, -1, FALSE, '[nu_tarefa]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_tarefa->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_tarefa'] = &$this->nu_tarefa;

		// no_tarefa
		$this->no_tarefa = new cField('vwrdmd_ativCronograma', 'vwrdmd_ativCronograma', 'x_no_tarefa', 'no_tarefa', '[no_tarefa]', '[no_tarefa]', 202, -1, FALSE, '[no_tarefa]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_tarefa'] = &$this->no_tarefa;

		// nu_catAtividade
		$this->nu_catAtividade = new cField('vwrdmd_ativCronograma', 'vwrdmd_ativCronograma', 'x_nu_catAtividade', 'nu_catAtividade', '[nu_catAtividade]', 'CAST([nu_catAtividade] AS NVARCHAR)', 3, -1, FALSE, '[nu_catAtividade]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_catAtividade->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_catAtividade'] = &$this->nu_catAtividade;

		// nu_situacao
		$this->nu_situacao = new cField('vwrdmd_ativCronograma', 'vwrdmd_ativCronograma', 'x_nu_situacao', 'nu_situacao', '[nu_situacao]', 'CAST([nu_situacao] AS NVARCHAR)', 3, -1, FALSE, '[nu_situacao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_situacao->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_situacao'] = &$this->nu_situacao;

		// qt_horasReal
		$this->qt_horasReal = new cField('vwrdmd_ativCronograma', 'vwrdmd_ativCronograma', 'x_qt_horasReal', 'qt_horasReal', '[qt_horasReal]', 'CAST([qt_horasReal] AS NVARCHAR)', 131, -1, FALSE, '[qt_horasReal]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->qt_horasReal->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['qt_horasReal'] = &$this->qt_horasReal;

		// qt_horasEstimada
		$this->qt_horasEstimada = new cField('vwrdmd_ativCronograma', 'vwrdmd_ativCronograma', 'x_qt_horasEstimada', 'qt_horasEstimada', '[qt_horasEstimada]', 'CAST([qt_horasEstimada] AS NVARCHAR)', 131, -1, FALSE, '[qt_horasEstimada]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->qt_horasEstimada->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['qt_horasEstimada'] = &$this->qt_horasEstimada;

		// nu_autor
		$this->nu_autor = new cField('vwrdmd_ativCronograma', 'vwrdmd_ativCronograma', 'x_nu_autor', 'nu_autor', '[nu_autor]', 'CAST([nu_autor] AS NVARCHAR)', 3, -1, FALSE, '[nu_autor]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_autor->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_autor'] = &$this->nu_autor;

		// no_autor
		$this->no_autor = new cField('vwrdmd_ativCronograma', 'vwrdmd_ativCronograma', 'x_no_autor', 'no_autor', '[no_autor]', '[no_autor]', 202, -1, FALSE, '[no_autor]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_autor'] = &$this->no_autor;

		// nu_responsavel
		$this->nu_responsavel = new cField('vwrdmd_ativCronograma', 'vwrdmd_ativCronograma', 'x_nu_responsavel', 'nu_responsavel', '[nu_responsavel]', 'CAST([nu_responsavel] AS NVARCHAR)', 3, -1, FALSE, '[nu_responsavel]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_responsavel->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_responsavel'] = &$this->nu_responsavel;

		// no_responsavel
		$this->no_responsavel = new cField('vwrdmd_ativCronograma', 'vwrdmd_ativCronograma', 'x_no_responsavel', 'no_responsavel', '[no_responsavel]', '[no_responsavel]', 202, -1, FALSE, '[no_responsavel]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_responsavel'] = &$this->no_responsavel;
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
		return "[db_owner].[vwrdmd_ativCronograma]";
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
	var $UpdateTable = "[db_owner].[vwrdmd_ativCronograma]";

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
			return "vwrdmd_ativcronogramalist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "vwrdmd_ativcronogramalist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("vwrdmd_ativcronogramaview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("vwrdmd_ativcronogramaview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "vwrdmd_ativcronogramaadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("vwrdmd_ativcronogramaedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("vwrdmd_ativcronogramaadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("vwrdmd_ativcronogramadelete.php", $this->UrlParm());
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
		$this->nu_projeto->setDbValue($rs->fields('nu_projeto'));
		$this->nu_versao->setDbValue($rs->fields('nu_versao'));
		$this->nu_tarefaPai->setDbValue($rs->fields('nu_tarefaPai'));
		$this->nu_tarefa->setDbValue($rs->fields('nu_tarefa'));
		$this->no_tarefa->setDbValue($rs->fields('no_tarefa'));
		$this->nu_catAtividade->setDbValue($rs->fields('nu_catAtividade'));
		$this->nu_situacao->setDbValue($rs->fields('nu_situacao'));
		$this->qt_horasReal->setDbValue($rs->fields('qt_horasReal'));
		$this->qt_horasEstimada->setDbValue($rs->fields('qt_horasEstimada'));
		$this->nu_autor->setDbValue($rs->fields('nu_autor'));
		$this->no_autor->setDbValue($rs->fields('no_autor'));
		$this->nu_responsavel->setDbValue($rs->fields('nu_responsavel'));
		$this->no_responsavel->setDbValue($rs->fields('no_responsavel'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_projeto
		// nu_versao
		// nu_tarefaPai
		// nu_tarefa
		// no_tarefa
		// nu_catAtividade
		// nu_situacao
		// qt_horasReal
		// qt_horasEstimada
		// nu_autor
		// no_autor
		// nu_responsavel
		// no_responsavel
		// nu_projeto

		if (strval($this->nu_projeto->CurrentValue) <> "") {
			$sFilterWrk = "[id]" . ew_SearchString("=", $this->nu_projeto->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [db_owner].[vwrdm_projects]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_projeto, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
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

		// nu_versao
		$this->nu_versao->ViewValue = $this->nu_versao->CurrentValue;
		$this->nu_versao->ViewCustomAttributes = "";

		// nu_tarefaPai
		$this->nu_tarefaPai->ViewCustomAttributes = "";

		// nu_tarefa
		$this->nu_tarefa->ViewCustomAttributes = "";

		// no_tarefa
		$this->no_tarefa->ViewValue = $this->no_tarefa->CurrentValue;
		$this->no_tarefa->ViewCustomAttributes = "";

		// nu_catAtividade
		if (strval($this->nu_catAtividade->CurrentValue) <> "") {
			$sFilterWrk = "[id]" . ew_SearchString("=", $this->nu_catAtividade->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [db_owner].[vwrdm_issue_categories]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_catAtividade, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_catAtividade->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_catAtividade->ViewValue = $this->nu_catAtividade->CurrentValue;
			}
		} else {
			$this->nu_catAtividade->ViewValue = NULL;
		}
		$this->nu_catAtividade->ViewCustomAttributes = "";

		// nu_situacao
		if (strval($this->nu_situacao->CurrentValue) <> "") {
			$sFilterWrk = "[id]" . ew_SearchString("=", $this->nu_situacao->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [db_owner].[vwrdm_issue_statuses]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_situacao, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_situacao->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_situacao->ViewValue = $this->nu_situacao->CurrentValue;
			}
		} else {
			$this->nu_situacao->ViewValue = NULL;
		}
		$this->nu_situacao->ViewCustomAttributes = "";

		// qt_horasReal
		$this->qt_horasReal->ViewValue = $this->qt_horasReal->CurrentValue;
		$this->qt_horasReal->ViewCustomAttributes = "";

		// qt_horasEstimada
		$this->qt_horasEstimada->ViewValue = $this->qt_horasEstimada->CurrentValue;
		$this->qt_horasEstimada->ViewCustomAttributes = "";

		// nu_autor
		$this->nu_autor->ViewValue = $this->nu_autor->CurrentValue;
		$this->nu_autor->ViewCustomAttributes = "";

		// no_autor
		$this->no_autor->ViewValue = $this->no_autor->CurrentValue;
		$this->no_autor->ViewCustomAttributes = "";

		// nu_responsavel
		$this->nu_responsavel->ViewValue = $this->nu_responsavel->CurrentValue;
		$this->nu_responsavel->ViewCustomAttributes = "";

		// no_responsavel
		$this->no_responsavel->ViewValue = $this->no_responsavel->CurrentValue;
		$this->no_responsavel->ViewCustomAttributes = "";

		// nu_projeto
		$this->nu_projeto->LinkCustomAttributes = "";
		$this->nu_projeto->HrefValue = "";
		$this->nu_projeto->TooltipValue = "";

		// nu_versao
		$this->nu_versao->LinkCustomAttributes = "";
		$this->nu_versao->HrefValue = "";
		$this->nu_versao->TooltipValue = "";

		// nu_tarefaPai
		$this->nu_tarefaPai->LinkCustomAttributes = "";
		$this->nu_tarefaPai->HrefValue = "";
		$this->nu_tarefaPai->TooltipValue = "";

		// nu_tarefa
		$this->nu_tarefa->LinkCustomAttributes = "";
		$this->nu_tarefa->HrefValue = "";
		$this->nu_tarefa->TooltipValue = "";

		// no_tarefa
		$this->no_tarefa->LinkCustomAttributes = "";
		$this->no_tarefa->HrefValue = "";
		$this->no_tarefa->TooltipValue = "";

		// nu_catAtividade
		$this->nu_catAtividade->LinkCustomAttributes = "";
		$this->nu_catAtividade->HrefValue = "";
		$this->nu_catAtividade->TooltipValue = "";

		// nu_situacao
		$this->nu_situacao->LinkCustomAttributes = "";
		$this->nu_situacao->HrefValue = "";
		$this->nu_situacao->TooltipValue = "";

		// qt_horasReal
		$this->qt_horasReal->LinkCustomAttributes = "";
		$this->qt_horasReal->HrefValue = "";
		$this->qt_horasReal->TooltipValue = "";

		// qt_horasEstimada
		$this->qt_horasEstimada->LinkCustomAttributes = "";
		$this->qt_horasEstimada->HrefValue = "";
		$this->qt_horasEstimada->TooltipValue = "";

		// nu_autor
		$this->nu_autor->LinkCustomAttributes = "";
		$this->nu_autor->HrefValue = "";
		$this->nu_autor->TooltipValue = "";

		// no_autor
		$this->no_autor->LinkCustomAttributes = "";
		$this->no_autor->HrefValue = "";
		$this->no_autor->TooltipValue = "";

		// nu_responsavel
		$this->nu_responsavel->LinkCustomAttributes = "";
		$this->nu_responsavel->HrefValue = "";
		$this->nu_responsavel->TooltipValue = "";

		// no_responsavel
		$this->no_responsavel->LinkCustomAttributes = "";
		$this->no_responsavel->HrefValue = "";
		$this->no_responsavel->TooltipValue = "";

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
				if ($this->nu_projeto->Exportable) $Doc->ExportCaption($this->nu_projeto);
				if ($this->nu_versao->Exportable) $Doc->ExportCaption($this->nu_versao);
				if ($this->nu_tarefaPai->Exportable) $Doc->ExportCaption($this->nu_tarefaPai);
				if ($this->nu_tarefa->Exportable) $Doc->ExportCaption($this->nu_tarefa);
				if ($this->no_tarefa->Exportable) $Doc->ExportCaption($this->no_tarefa);
				if ($this->nu_catAtividade->Exportable) $Doc->ExportCaption($this->nu_catAtividade);
				if ($this->nu_situacao->Exportable) $Doc->ExportCaption($this->nu_situacao);
				if ($this->qt_horasReal->Exportable) $Doc->ExportCaption($this->qt_horasReal);
				if ($this->qt_horasEstimada->Exportable) $Doc->ExportCaption($this->qt_horasEstimada);
				if ($this->nu_autor->Exportable) $Doc->ExportCaption($this->nu_autor);
				if ($this->no_autor->Exportable) $Doc->ExportCaption($this->no_autor);
				if ($this->nu_responsavel->Exportable) $Doc->ExportCaption($this->nu_responsavel);
				if ($this->no_responsavel->Exportable) $Doc->ExportCaption($this->no_responsavel);
			} else {
				if ($this->nu_projeto->Exportable) $Doc->ExportCaption($this->nu_projeto);
				if ($this->nu_versao->Exportable) $Doc->ExportCaption($this->nu_versao);
				if ($this->nu_tarefaPai->Exportable) $Doc->ExportCaption($this->nu_tarefaPai);
				if ($this->nu_tarefa->Exportable) $Doc->ExportCaption($this->nu_tarefa);
				if ($this->no_tarefa->Exportable) $Doc->ExportCaption($this->no_tarefa);
				if ($this->nu_catAtividade->Exportable) $Doc->ExportCaption($this->nu_catAtividade);
				if ($this->nu_situacao->Exportable) $Doc->ExportCaption($this->nu_situacao);
				if ($this->qt_horasReal->Exportable) $Doc->ExportCaption($this->qt_horasReal);
				if ($this->qt_horasEstimada->Exportable) $Doc->ExportCaption($this->qt_horasEstimada);
				if ($this->nu_autor->Exportable) $Doc->ExportCaption($this->nu_autor);
				if ($this->no_autor->Exportable) $Doc->ExportCaption($this->no_autor);
				if ($this->nu_responsavel->Exportable) $Doc->ExportCaption($this->nu_responsavel);
				if ($this->no_responsavel->Exportable) $Doc->ExportCaption($this->no_responsavel);
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
					if ($this->nu_projeto->Exportable) $Doc->ExportField($this->nu_projeto);
					if ($this->nu_versao->Exportable) $Doc->ExportField($this->nu_versao);
					if ($this->nu_tarefaPai->Exportable) $Doc->ExportField($this->nu_tarefaPai);
					if ($this->nu_tarefa->Exportable) $Doc->ExportField($this->nu_tarefa);
					if ($this->no_tarefa->Exportable) $Doc->ExportField($this->no_tarefa);
					if ($this->nu_catAtividade->Exportable) $Doc->ExportField($this->nu_catAtividade);
					if ($this->nu_situacao->Exportable) $Doc->ExportField($this->nu_situacao);
					if ($this->qt_horasReal->Exportable) $Doc->ExportField($this->qt_horasReal);
					if ($this->qt_horasEstimada->Exportable) $Doc->ExportField($this->qt_horasEstimada);
					if ($this->nu_autor->Exportable) $Doc->ExportField($this->nu_autor);
					if ($this->no_autor->Exportable) $Doc->ExportField($this->no_autor);
					if ($this->nu_responsavel->Exportable) $Doc->ExportField($this->nu_responsavel);
					if ($this->no_responsavel->Exportable) $Doc->ExportField($this->no_responsavel);
				} else {
					if ($this->nu_projeto->Exportable) $Doc->ExportField($this->nu_projeto);
					if ($this->nu_versao->Exportable) $Doc->ExportField($this->nu_versao);
					if ($this->nu_tarefaPai->Exportable) $Doc->ExportField($this->nu_tarefaPai);
					if ($this->nu_tarefa->Exportable) $Doc->ExportField($this->nu_tarefa);
					if ($this->no_tarefa->Exportable) $Doc->ExportField($this->no_tarefa);
					if ($this->nu_catAtividade->Exportable) $Doc->ExportField($this->nu_catAtividade);
					if ($this->nu_situacao->Exportable) $Doc->ExportField($this->nu_situacao);
					if ($this->qt_horasReal->Exportable) $Doc->ExportField($this->qt_horasReal);
					if ($this->qt_horasEstimada->Exportable) $Doc->ExportField($this->qt_horasEstimada);
					if ($this->nu_autor->Exportable) $Doc->ExportField($this->nu_autor);
					if ($this->no_autor->Exportable) $Doc->ExportField($this->no_autor);
					if ($this->nu_responsavel->Exportable) $Doc->ExportField($this->nu_responsavel);
					if ($this->no_responsavel->Exportable) $Doc->ExportField($this->no_responsavel);
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
