<?php

// Global variable for table object
$rprojsis = NULL;

//
// Table class for rprojsis
//
class crprojsis extends cTable {
	var $nu_contrato;
	var $nu_itemContrato;
	var $nu_ambiente;
	var $nu_metodologia;
	var $nu_sistema;
	var $ic_ativo;
	var $nu_stSistema;
	var $nu_tpProjeto;
	var $nu_projeto;
	var $nu_projetoInteg;
	var $ic_passivelContPf;
	var $id_tarefaTpProj;
	var $status_id;
	var $start_date;
	var $due_date;
	var $assigned_to;
	var $ic_stContagem;
	var $vr_pfFaturamento;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'rprojsis';
		$this->TableName = 'rprojsis';
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

		// nu_contrato
		$this->nu_contrato = new cField('rprojsis', 'rprojsis', 'x_nu_contrato', 'nu_contrato', '[nu_contrato]', 'CAST([nu_contrato] AS NVARCHAR)', 3, -1, FALSE, '[nu_contrato]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_contrato->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_contrato'] = &$this->nu_contrato;

		// nu_itemContrato
		$this->nu_itemContrato = new cField('rprojsis', 'rprojsis', 'x_nu_itemContrato', 'nu_itemContrato', '[nu_itemContrato]', 'CAST([nu_itemContrato] AS NVARCHAR)', 3, -1, FALSE, '[nu_itemContrato]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_itemContrato->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_itemContrato'] = &$this->nu_itemContrato;

		// nu_ambiente
		$this->nu_ambiente = new cField('rprojsis', 'rprojsis', 'x_nu_ambiente', 'nu_ambiente', '[nu_ambiente]', 'CAST([nu_ambiente] AS NVARCHAR)', 3, -1, FALSE, '[nu_ambiente]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_ambiente->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_ambiente'] = &$this->nu_ambiente;

		// nu_metodologia
		$this->nu_metodologia = new cField('rprojsis', 'rprojsis', 'x_nu_metodologia', 'nu_metodologia', '[nu_metodologia]', 'CAST([nu_metodologia] AS NVARCHAR)', 3, -1, FALSE, '[nu_metodologia]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_metodologia->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_metodologia'] = &$this->nu_metodologia;

		// nu_sistema
		$this->nu_sistema = new cField('rprojsis', 'rprojsis', 'x_nu_sistema', 'nu_sistema', '[nu_sistema]', 'CAST([nu_sistema] AS NVARCHAR)', 3, -1, FALSE, '[nu_sistema]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_sistema->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_sistema'] = &$this->nu_sistema;

		// ic_ativo
		$this->ic_ativo = new cField('rprojsis', 'rprojsis', 'x_ic_ativo', 'ic_ativo', '[ic_ativo]', '[ic_ativo]', 129, -1, FALSE, '[ic_ativo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_ativo'] = &$this->ic_ativo;

		// nu_stSistema
		$this->nu_stSistema = new cField('rprojsis', 'rprojsis', 'x_nu_stSistema', 'nu_stSistema', '[nu_stSistema]', 'CAST([nu_stSistema] AS NVARCHAR)', 3, -1, FALSE, '[nu_stSistema]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_stSistema->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_stSistema'] = &$this->nu_stSistema;

		// nu_tpProjeto
		$this->nu_tpProjeto = new cField('rprojsis', 'rprojsis', 'x_nu_tpProjeto', 'nu_tpProjeto', '[nu_tpProjeto]', 'CAST([nu_tpProjeto] AS NVARCHAR)', 3, -1, FALSE, '[nu_tpProjeto]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_tpProjeto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_tpProjeto'] = &$this->nu_tpProjeto;

		// nu_projeto
		$this->nu_projeto = new cField('rprojsis', 'rprojsis', 'x_nu_projeto', 'nu_projeto', '[nu_projeto]', 'CAST([nu_projeto] AS NVARCHAR)', 3, -1, FALSE, '[nu_projeto]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_projeto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_projeto'] = &$this->nu_projeto;

		// nu_projetoInteg
		$this->nu_projetoInteg = new cField('rprojsis', 'rprojsis', 'x_nu_projetoInteg', 'nu_projetoInteg', '[nu_projetoInteg]', 'CAST([nu_projetoInteg] AS NVARCHAR)', 3, -1, FALSE, '[nu_projetoInteg]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_projetoInteg->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_projetoInteg'] = &$this->nu_projetoInteg;

		// ic_passivelContPf
		$this->ic_passivelContPf = new cField('rprojsis', 'rprojsis', 'x_ic_passivelContPf', 'ic_passivelContPf', '[ic_passivelContPf]', '[ic_passivelContPf]', 129, -1, FALSE, '[ic_passivelContPf]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_passivelContPf'] = &$this->ic_passivelContPf;

		// id_tarefaTpProj
		$this->id_tarefaTpProj = new cField('rprojsis', 'rprojsis', 'x_id_tarefaTpProj', 'id_tarefaTpProj', '[id_tarefaTpProj]', 'CAST([id_tarefaTpProj] AS NVARCHAR)', 3, -1, FALSE, '[id_tarefaTpProj]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_tarefaTpProj->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_tarefaTpProj'] = &$this->id_tarefaTpProj;

		// status_id
		$this->status_id = new cField('rprojsis', 'rprojsis', 'x_status_id', 'status_id', '[status_id]', 'CAST([status_id] AS NVARCHAR)', 3, -1, FALSE, '[status_id]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->status_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['status_id'] = &$this->status_id;

		// start_date
		$this->start_date = new cField('rprojsis', 'rprojsis', 'x_start_date', 'start_date', '[start_date]', 'CAST([start_date] AS NVARCHAR)', 3, 7, FALSE, '[start_date]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->start_date->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['start_date'] = &$this->start_date;

		// due_date
		$this->due_date = new cField('rprojsis', 'rprojsis', 'x_due_date', 'due_date', '[due_date]', 'CAST([due_date] AS NVARCHAR)', 3, 7, FALSE, '[due_date]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->due_date->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['due_date'] = &$this->due_date;

		// assigned_to
		$this->assigned_to = new cField('rprojsis', 'rprojsis', 'x_assigned_to', 'assigned_to', '[assigned_to]', 'CAST([assigned_to] AS NVARCHAR)', 3, -1, FALSE, '[assigned_to]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->assigned_to->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['assigned_to'] = &$this->assigned_to;

		// ic_stContagem
		$this->ic_stContagem = new cField('rprojsis', 'rprojsis', 'x_ic_stContagem', 'ic_stContagem', '[ic_stContagem]', '[ic_stContagem]', 129, -1, FALSE, '[ic_stContagem]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_stContagem'] = &$this->ic_stContagem;

		// vr_pfFaturamento
		$this->vr_pfFaturamento = new cField('rprojsis', 'rprojsis', 'x_vr_pfFaturamento', 'vr_pfFaturamento', '[vr_pfFaturamento]', 'CAST([vr_pfFaturamento] AS NVARCHAR)', 131, -1, FALSE, '[vr_pfFaturamento]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->vr_pfFaturamento->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['vr_pfFaturamento'] = &$this->vr_pfFaturamento;
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
		return "[db_owner].[rprojsis]";
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
	var $UpdateTable = "[db_owner].[rprojsis]";

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
			return "rprojsislist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "rprojsislist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("rprojsisview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("rprojsisview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "rprojsisadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("rprojsisedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("rprojsisadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("rprojsisdelete.php", $this->UrlParm());
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
		$this->nu_contrato->setDbValue($rs->fields('nu_contrato'));
		$this->nu_itemContrato->setDbValue($rs->fields('nu_itemContrato'));
		$this->nu_ambiente->setDbValue($rs->fields('nu_ambiente'));
		$this->nu_metodologia->setDbValue($rs->fields('nu_metodologia'));
		$this->nu_sistema->setDbValue($rs->fields('nu_sistema'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
		$this->nu_stSistema->setDbValue($rs->fields('nu_stSistema'));
		$this->nu_tpProjeto->setDbValue($rs->fields('nu_tpProjeto'));
		$this->nu_projeto->setDbValue($rs->fields('nu_projeto'));
		$this->nu_projetoInteg->setDbValue($rs->fields('nu_projetoInteg'));
		$this->ic_passivelContPf->setDbValue($rs->fields('ic_passivelContPf'));
		$this->id_tarefaTpProj->setDbValue($rs->fields('id_tarefaTpProj'));
		$this->status_id->setDbValue($rs->fields('status_id'));
		$this->start_date->setDbValue($rs->fields('start_date'));
		$this->due_date->setDbValue($rs->fields('due_date'));
		$this->assigned_to->setDbValue($rs->fields('assigned_to'));
		$this->ic_stContagem->setDbValue($rs->fields('ic_stContagem'));
		$this->vr_pfFaturamento->setDbValue($rs->fields('vr_pfFaturamento'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_contrato
		// nu_itemContrato
		// nu_ambiente
		// nu_metodologia
		// nu_sistema
		// ic_ativo
		// nu_stSistema
		// nu_tpProjeto
		// nu_projeto
		// nu_projetoInteg
		// ic_passivelContPf
		// id_tarefaTpProj
		// status_id
		// start_date
		// due_date
		// assigned_to
		// ic_stContagem
		// vr_pfFaturamento
		// nu_contrato

		if (strval($this->nu_contrato->CurrentValue) <> "") {
			$sFilterWrk = "[nu_contrato]" . ew_SearchString("=", $this->nu_contrato->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_contrato], [no_contrato] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[contrato]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_contrato, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_contrato->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_contrato->ViewValue = $this->nu_contrato->CurrentValue;
			}
		} else {
			$this->nu_contrato->ViewValue = NULL;
		}
		$this->nu_contrato->ViewCustomAttributes = "";

		// nu_itemContrato
		if (strval($this->nu_itemContrato->CurrentValue) <> "") {
			$sFilterWrk = "[nu_itemContratado]" . ew_SearchString("=", $this->nu_itemContrato->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_itemContratado], [no_itemContratado] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[item_contratado]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_itemContrato, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_itemContrato->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_itemContrato->ViewValue = $this->nu_itemContrato->CurrentValue;
			}
		} else {
			$this->nu_itemContrato->ViewValue = NULL;
		}
		$this->nu_itemContrato->ViewCustomAttributes = "";

		// nu_ambiente
		if (strval($this->nu_ambiente->CurrentValue) <> "") {
			$sFilterWrk = "[nu_ambiente]" . ew_SearchString("=", $this->nu_ambiente->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_ambiente], [no_ambiente] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ambiente]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_ambiente, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
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

		// nu_metodologia
		if (strval($this->nu_metodologia->CurrentValue) <> "") {
			$sFilterWrk = "[nu_metodologia]" . ew_SearchString("=", $this->nu_metodologia->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_metodologia], [no_metodologia] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[metodologia]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_metodologia, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_metodologia->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_metodologia->ViewValue = $this->nu_metodologia->CurrentValue;
			}
		} else {
			$this->nu_metodologia->ViewValue = NULL;
		}
		$this->nu_metodologia->ViewCustomAttributes = "";

		// nu_sistema
		if (strval($this->nu_sistema->CurrentValue) <> "") {
			$sFilterWrk = "[nu_sistema]" . ew_SearchString("=", $this->nu_sistema->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_sistema], [co_alternativo] AS [DispFld], [no_sistema] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[sistema]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_sistema, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [co_alternativo] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_sistema->ViewValue = $rswrk->fields('DispFld');
				$this->nu_sistema->ViewValue .= ew_ValueSeparator(1,$this->nu_sistema) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->nu_sistema->ViewValue = $this->nu_sistema->CurrentValue;
			}
		} else {
			$this->nu_sistema->ViewValue = NULL;
		}
		$this->nu_sistema->ViewCustomAttributes = "";

		// ic_ativo
		if (strval($this->ic_ativo->CurrentValue) <> "") {
			switch ($this->ic_ativo->CurrentValue) {
				case $this->ic_ativo->FldTagValue(1):
					$this->ic_ativo->ViewValue = $this->ic_ativo->FldTagCaption(1) <> "" ? $this->ic_ativo->FldTagCaption(1) : $this->ic_ativo->CurrentValue;
					break;
				case $this->ic_ativo->FldTagValue(2):
					$this->ic_ativo->ViewValue = $this->ic_ativo->FldTagCaption(2) <> "" ? $this->ic_ativo->FldTagCaption(2) : $this->ic_ativo->CurrentValue;
					break;
				default:
					$this->ic_ativo->ViewValue = $this->ic_ativo->CurrentValue;
			}
		} else {
			$this->ic_ativo->ViewValue = NULL;
		}
		$this->ic_ativo->ViewCustomAttributes = "";

		// nu_stSistema
		if (strval($this->nu_stSistema->CurrentValue) <> "") {
			$sFilterWrk = "[nu_stSistema]" . ew_SearchString("=", $this->nu_stSistema->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_stSistema], [no_stSistema] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[stsistema]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_stSistema, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_stSistema->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_stSistema->ViewValue = $this->nu_stSistema->CurrentValue;
			}
		} else {
			$this->nu_stSistema->ViewValue = NULL;
		}
		$this->nu_stSistema->ViewCustomAttributes = "";

		// nu_tpProjeto
		if (strval($this->nu_tpProjeto->CurrentValue) <> "") {
			$sFilterWrk = "[nu_tpProjeto]" . ew_SearchString("=", $this->nu_tpProjeto->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_tpProjeto], [no_tpProjeto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpprojeto]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_tpProjeto, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_tpProjeto] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_tpProjeto->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_tpProjeto->ViewValue = $this->nu_tpProjeto->CurrentValue;
			}
		} else {
			$this->nu_tpProjeto->ViewValue = NULL;
		}
		$this->nu_tpProjeto->ViewCustomAttributes = "";

		// nu_projeto
		if (strval($this->nu_projeto->CurrentValue) <> "") {
			$sFilterWrk = "[nu_projeto]" . ew_SearchString("=", $this->nu_projeto->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_projeto], [no_projeto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[projeto]";
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

		// nu_projetoInteg
		if (strval($this->nu_projetoInteg->CurrentValue) <> "") {
			$sFilterWrk = "[id]" . ew_SearchString("=", $this->nu_projetoInteg->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[rdm_projeto]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_projetoInteg, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_projetoInteg->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_projetoInteg->ViewValue = $this->nu_projetoInteg->CurrentValue;
			}
		} else {
			$this->nu_projetoInteg->ViewValue = NULL;
		}
		$this->nu_projetoInteg->ViewCustomAttributes = "";

		// ic_passivelContPf
		$this->ic_passivelContPf->ViewValue = $this->ic_passivelContPf->CurrentValue;
		$this->ic_passivelContPf->ViewCustomAttributes = "";

		// id_tarefaTpProj
		if (strval($this->id_tarefaTpProj->CurrentValue) <> "") {
			$sFilterWrk = "[id]" . ew_SearchString("=", $this->id_tarefaTpProj->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [id], [subject] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[rdm_tarefa]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->id_tarefaTpProj, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->id_tarefaTpProj->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->id_tarefaTpProj->ViewValue = $this->id_tarefaTpProj->CurrentValue;
			}
		} else {
			$this->id_tarefaTpProj->ViewValue = NULL;
		}
		$this->id_tarefaTpProj->ViewCustomAttributes = "";

		// status_id
		if (strval($this->status_id->CurrentValue) <> "") {
			$sFilterWrk = "[id]" . ew_SearchString("=", $this->status_id->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[rdm_sttarefa]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->status_id, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->status_id->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->status_id->ViewValue = $this->status_id->CurrentValue;
			}
		} else {
			$this->status_id->ViewValue = NULL;
		}
		$this->status_id->ViewCustomAttributes = "";

		// start_date
		$this->start_date->ViewValue = $this->start_date->CurrentValue;
		$this->start_date->ViewValue = ew_FormatDateTime($this->start_date->ViewValue, 7);
		$this->start_date->ViewCustomAttributes = "";

		// due_date
		$this->due_date->ViewValue = $this->due_date->CurrentValue;
		$this->due_date->ViewValue = ew_FormatDateTime($this->due_date->ViewValue, 7);
		$this->due_date->ViewCustomAttributes = "";

		// assigned_to
		$this->assigned_to->ViewValue = $this->assigned_to->CurrentValue;
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

		// ic_stContagem
		$this->ic_stContagem->ViewValue = $this->ic_stContagem->CurrentValue;
		$this->ic_stContagem->ViewCustomAttributes = "";

		// vr_pfFaturamento
		$this->vr_pfFaturamento->ViewValue = $this->vr_pfFaturamento->CurrentValue;
		$this->vr_pfFaturamento->ViewCustomAttributes = "";

		// nu_contrato
		$this->nu_contrato->LinkCustomAttributes = "";
		$this->nu_contrato->HrefValue = "";
		$this->nu_contrato->TooltipValue = "";

		// nu_itemContrato
		$this->nu_itemContrato->LinkCustomAttributes = "";
		$this->nu_itemContrato->HrefValue = "";
		$this->nu_itemContrato->TooltipValue = "";

		// nu_ambiente
		$this->nu_ambiente->LinkCustomAttributes = "";
		$this->nu_ambiente->HrefValue = "";
		$this->nu_ambiente->TooltipValue = "";

		// nu_metodologia
		$this->nu_metodologia->LinkCustomAttributes = "";
		$this->nu_metodologia->HrefValue = "";
		$this->nu_metodologia->TooltipValue = "";

		// nu_sistema
		$this->nu_sistema->LinkCustomAttributes = "";
		$this->nu_sistema->HrefValue = "";
		$this->nu_sistema->TooltipValue = "";

		// ic_ativo
		$this->ic_ativo->LinkCustomAttributes = "";
		$this->ic_ativo->HrefValue = "";
		$this->ic_ativo->TooltipValue = "";

		// nu_stSistema
		$this->nu_stSistema->LinkCustomAttributes = "";
		$this->nu_stSistema->HrefValue = "";
		$this->nu_stSistema->TooltipValue = "";

		// nu_tpProjeto
		$this->nu_tpProjeto->LinkCustomAttributes = "";
		$this->nu_tpProjeto->HrefValue = "";
		$this->nu_tpProjeto->TooltipValue = "";

		// nu_projeto
		$this->nu_projeto->LinkCustomAttributes = "";
		$this->nu_projeto->HrefValue = "";
		$this->nu_projeto->TooltipValue = "";

		// nu_projetoInteg
		$this->nu_projetoInteg->LinkCustomAttributes = "";
		$this->nu_projetoInteg->HrefValue = "";
		$this->nu_projetoInteg->TooltipValue = "";

		// ic_passivelContPf
		$this->ic_passivelContPf->LinkCustomAttributes = "";
		$this->ic_passivelContPf->HrefValue = "";
		$this->ic_passivelContPf->TooltipValue = "";

		// id_tarefaTpProj
		$this->id_tarefaTpProj->LinkCustomAttributes = "";
		$this->id_tarefaTpProj->HrefValue = "";
		$this->id_tarefaTpProj->TooltipValue = "";

		// status_id
		$this->status_id->LinkCustomAttributes = "";
		$this->status_id->HrefValue = "";
		$this->status_id->TooltipValue = "";

		// start_date
		$this->start_date->LinkCustomAttributes = "";
		$this->start_date->HrefValue = "";
		$this->start_date->TooltipValue = "";

		// due_date
		$this->due_date->LinkCustomAttributes = "";
		$this->due_date->HrefValue = "";
		$this->due_date->TooltipValue = "";

		// assigned_to
		$this->assigned_to->LinkCustomAttributes = "";
		$this->assigned_to->HrefValue = "";
		$this->assigned_to->TooltipValue = "";

		// ic_stContagem
		$this->ic_stContagem->LinkCustomAttributes = "";
		$this->ic_stContagem->HrefValue = "";
		$this->ic_stContagem->TooltipValue = "";

		// vr_pfFaturamento
		$this->vr_pfFaturamento->LinkCustomAttributes = "";
		$this->vr_pfFaturamento->HrefValue = "";
		$this->vr_pfFaturamento->TooltipValue = "";

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
				if ($this->nu_contrato->Exportable) $Doc->ExportCaption($this->nu_contrato);
				if ($this->nu_itemContrato->Exportable) $Doc->ExportCaption($this->nu_itemContrato);
				if ($this->nu_ambiente->Exportable) $Doc->ExportCaption($this->nu_ambiente);
				if ($this->nu_metodologia->Exportable) $Doc->ExportCaption($this->nu_metodologia);
				if ($this->nu_sistema->Exportable) $Doc->ExportCaption($this->nu_sistema);
				if ($this->ic_ativo->Exportable) $Doc->ExportCaption($this->ic_ativo);
				if ($this->nu_stSistema->Exportable) $Doc->ExportCaption($this->nu_stSistema);
				if ($this->nu_tpProjeto->Exportable) $Doc->ExportCaption($this->nu_tpProjeto);
				if ($this->nu_projeto->Exportable) $Doc->ExportCaption($this->nu_projeto);
				if ($this->nu_projetoInteg->Exportable) $Doc->ExportCaption($this->nu_projetoInteg);
				if ($this->ic_passivelContPf->Exportable) $Doc->ExportCaption($this->ic_passivelContPf);
				if ($this->id_tarefaTpProj->Exportable) $Doc->ExportCaption($this->id_tarefaTpProj);
				if ($this->status_id->Exportable) $Doc->ExportCaption($this->status_id);
				if ($this->start_date->Exportable) $Doc->ExportCaption($this->start_date);
				if ($this->due_date->Exportable) $Doc->ExportCaption($this->due_date);
				if ($this->assigned_to->Exportable) $Doc->ExportCaption($this->assigned_to);
				if ($this->ic_stContagem->Exportable) $Doc->ExportCaption($this->ic_stContagem);
				if ($this->vr_pfFaturamento->Exportable) $Doc->ExportCaption($this->vr_pfFaturamento);
			} else {
				if ($this->nu_contrato->Exportable) $Doc->ExportCaption($this->nu_contrato);
				if ($this->nu_itemContrato->Exportable) $Doc->ExportCaption($this->nu_itemContrato);
				if ($this->nu_ambiente->Exportable) $Doc->ExportCaption($this->nu_ambiente);
				if ($this->nu_metodologia->Exportable) $Doc->ExportCaption($this->nu_metodologia);
				if ($this->nu_sistema->Exportable) $Doc->ExportCaption($this->nu_sistema);
				if ($this->ic_ativo->Exportable) $Doc->ExportCaption($this->ic_ativo);
				if ($this->nu_stSistema->Exportable) $Doc->ExportCaption($this->nu_stSistema);
				if ($this->nu_tpProjeto->Exportable) $Doc->ExportCaption($this->nu_tpProjeto);
				if ($this->nu_projeto->Exportable) $Doc->ExportCaption($this->nu_projeto);
				if ($this->nu_projetoInteg->Exportable) $Doc->ExportCaption($this->nu_projetoInteg);
				if ($this->ic_passivelContPf->Exportable) $Doc->ExportCaption($this->ic_passivelContPf);
				if ($this->id_tarefaTpProj->Exportable) $Doc->ExportCaption($this->id_tarefaTpProj);
				if ($this->status_id->Exportable) $Doc->ExportCaption($this->status_id);
				if ($this->start_date->Exportable) $Doc->ExportCaption($this->start_date);
				if ($this->due_date->Exportable) $Doc->ExportCaption($this->due_date);
				if ($this->assigned_to->Exportable) $Doc->ExportCaption($this->assigned_to);
				if ($this->ic_stContagem->Exportable) $Doc->ExportCaption($this->ic_stContagem);
				if ($this->vr_pfFaturamento->Exportable) $Doc->ExportCaption($this->vr_pfFaturamento);
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
					if ($this->nu_contrato->Exportable) $Doc->ExportField($this->nu_contrato);
					if ($this->nu_itemContrato->Exportable) $Doc->ExportField($this->nu_itemContrato);
					if ($this->nu_ambiente->Exportable) $Doc->ExportField($this->nu_ambiente);
					if ($this->nu_metodologia->Exportable) $Doc->ExportField($this->nu_metodologia);
					if ($this->nu_sistema->Exportable) $Doc->ExportField($this->nu_sistema);
					if ($this->ic_ativo->Exportable) $Doc->ExportField($this->ic_ativo);
					if ($this->nu_stSistema->Exportable) $Doc->ExportField($this->nu_stSistema);
					if ($this->nu_tpProjeto->Exportable) $Doc->ExportField($this->nu_tpProjeto);
					if ($this->nu_projeto->Exportable) $Doc->ExportField($this->nu_projeto);
					if ($this->nu_projetoInteg->Exportable) $Doc->ExportField($this->nu_projetoInteg);
					if ($this->ic_passivelContPf->Exportable) $Doc->ExportField($this->ic_passivelContPf);
					if ($this->id_tarefaTpProj->Exportable) $Doc->ExportField($this->id_tarefaTpProj);
					if ($this->status_id->Exportable) $Doc->ExportField($this->status_id);
					if ($this->start_date->Exportable) $Doc->ExportField($this->start_date);
					if ($this->due_date->Exportable) $Doc->ExportField($this->due_date);
					if ($this->assigned_to->Exportable) $Doc->ExportField($this->assigned_to);
					if ($this->ic_stContagem->Exportable) $Doc->ExportField($this->ic_stContagem);
					if ($this->vr_pfFaturamento->Exportable) $Doc->ExportField($this->vr_pfFaturamento);
				} else {
					if ($this->nu_contrato->Exportable) $Doc->ExportField($this->nu_contrato);
					if ($this->nu_itemContrato->Exportable) $Doc->ExportField($this->nu_itemContrato);
					if ($this->nu_ambiente->Exportable) $Doc->ExportField($this->nu_ambiente);
					if ($this->nu_metodologia->Exportable) $Doc->ExportField($this->nu_metodologia);
					if ($this->nu_sistema->Exportable) $Doc->ExportField($this->nu_sistema);
					if ($this->ic_ativo->Exportable) $Doc->ExportField($this->ic_ativo);
					if ($this->nu_stSistema->Exportable) $Doc->ExportField($this->nu_stSistema);
					if ($this->nu_tpProjeto->Exportable) $Doc->ExportField($this->nu_tpProjeto);
					if ($this->nu_projeto->Exportable) $Doc->ExportField($this->nu_projeto);
					if ($this->nu_projetoInteg->Exportable) $Doc->ExportField($this->nu_projetoInteg);
					if ($this->ic_passivelContPf->Exportable) $Doc->ExportField($this->ic_passivelContPf);
					if ($this->id_tarefaTpProj->Exportable) $Doc->ExportField($this->id_tarefaTpProj);
					if ($this->status_id->Exportable) $Doc->ExportField($this->status_id);
					if ($this->start_date->Exportable) $Doc->ExportField($this->start_date);
					if ($this->due_date->Exportable) $Doc->ExportField($this->due_date);
					if ($this->assigned_to->Exportable) $Doc->ExportField($this->assigned_to);
					if ($this->ic_stContagem->Exportable) $Doc->ExportField($this->ic_stContagem);
					if ($this->vr_pfFaturamento->Exportable) $Doc->ExportField($this->vr_pfFaturamento);
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
