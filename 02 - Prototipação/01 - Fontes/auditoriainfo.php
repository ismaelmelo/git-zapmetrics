<?php

// Global variable for table object
$auditoria = NULL;

//
// Table class for auditoria
//
class cauditoria extends cTable {
	var $nu_identificador;
	var $dt_data;
	var $ds_dominioArquivo;
	var $no_perfil;
	var $ic_acao;
	var $no_tabela;
	var $no_campo;
	var $nu_chaveCampo;
	var $im_antes;
	var $im_depois;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'auditoria';
		$this->TableName = 'auditoria';
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

		// nu_identificador
		$this->nu_identificador = new cField('auditoria', 'auditoria', 'x_nu_identificador', 'nu_identificador', '[nu_identificador]', 'CAST([nu_identificador] AS NVARCHAR)', 3, -1, FALSE, '[nu_identificador]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_identificador->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_identificador'] = &$this->nu_identificador;

		// dt_data
		$this->dt_data = new cField('auditoria', 'auditoria', 'x_dt_data', 'dt_data', '[dt_data]', '(REPLACE(STR(DAY([dt_data]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dt_data]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dt_data]),4,0))', 135, 7, FALSE, '[dt_data]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dt_data->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dt_data'] = &$this->dt_data;

		// ds_dominioArquivo
		$this->ds_dominioArquivo = new cField('auditoria', 'auditoria', 'x_ds_dominioArquivo', 'ds_dominioArquivo', '[ds_dominioArquivo]', '[ds_dominioArquivo]', 202, -1, FALSE, '[ds_dominioArquivo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_dominioArquivo'] = &$this->ds_dominioArquivo;

		// no_perfil
		$this->no_perfil = new cField('auditoria', 'auditoria', 'x_no_perfil', 'no_perfil', '[no_perfil]', '[no_perfil]', 202, -1, FALSE, '[no_perfil]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_perfil'] = &$this->no_perfil;

		// ic_acao
		$this->ic_acao = new cField('auditoria', 'auditoria', 'x_ic_acao', 'ic_acao', '[ic_acao]', '[ic_acao]', 202, -1, FALSE, '[ic_acao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_acao'] = &$this->ic_acao;

		// no_tabela
		$this->no_tabela = new cField('auditoria', 'auditoria', 'x_no_tabela', 'no_tabela', '[no_tabela]', '[no_tabela]', 202, -1, FALSE, '[no_tabela]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_tabela'] = &$this->no_tabela;

		// no_campo
		$this->no_campo = new cField('auditoria', 'auditoria', 'x_no_campo', 'no_campo', '[no_campo]', '[no_campo]', 202, -1, FALSE, '[no_campo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_campo'] = &$this->no_campo;

		// nu_chaveCampo
		$this->nu_chaveCampo = new cField('auditoria', 'auditoria', 'x_nu_chaveCampo', 'nu_chaveCampo', '[nu_chaveCampo]', '[nu_chaveCampo]', 203, -1, FALSE, '[nu_chaveCampo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['nu_chaveCampo'] = &$this->nu_chaveCampo;

		// im_antes
		$this->im_antes = new cField('auditoria', 'auditoria', 'x_im_antes', 'im_antes', '[im_antes]', '[im_antes]', 203, -1, FALSE, '[im_antes]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['im_antes'] = &$this->im_antes;

		// im_depois
		$this->im_depois = new cField('auditoria', 'auditoria', 'x_im_depois', 'im_depois', '[im_depois]', '[im_depois]', 203, -1, FALSE, '[im_depois]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['im_depois'] = &$this->im_depois;
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
		return "[dbo].[auditoria]";
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
		return "[nu_identificador] ASC,[dt_data] ASC";
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
	var $UpdateTable = "[dbo].[auditoria]";

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
			if (array_key_exists('nu_identificador', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_identificador') . '=' . ew_QuotedValue($rs['nu_identificador'], $this->nu_identificador->FldDataType));
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
		return "[nu_identificador] = @nu_identificador@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nu_identificador->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_identificador@", ew_AdjustSql($this->nu_identificador->CurrentValue), $sKeyFilter); // Replace key value
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
			return "auditorialist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "auditorialist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("auditoriaview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("auditoriaview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "auditoriaadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("auditoriaedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("auditoriaadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("auditoriadelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nu_identificador->CurrentValue)) {
			$sUrl .= "nu_identificador=" . urlencode($this->nu_identificador->CurrentValue);
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
			$arKeys[] = @$_GET["nu_identificador"]; // nu_identificador

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
			$this->nu_identificador->CurrentValue = $key;
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
		$this->nu_identificador->setDbValue($rs->fields('nu_identificador'));
		$this->dt_data->setDbValue($rs->fields('dt_data'));
		$this->ds_dominioArquivo->setDbValue($rs->fields('ds_dominioArquivo'));
		$this->no_perfil->setDbValue($rs->fields('no_perfil'));
		$this->ic_acao->setDbValue($rs->fields('ic_acao'));
		$this->no_tabela->setDbValue($rs->fields('no_tabela'));
		$this->no_campo->setDbValue($rs->fields('no_campo'));
		$this->nu_chaveCampo->setDbValue($rs->fields('nu_chaveCampo'));
		$this->im_antes->setDbValue($rs->fields('im_antes'));
		$this->im_depois->setDbValue($rs->fields('im_depois'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_identificador
		// dt_data
		// ds_dominioArquivo
		// no_perfil
		// ic_acao
		// no_tabela
		// no_campo
		// nu_chaveCampo
		// im_antes
		// im_depois
		// nu_identificador

		$this->nu_identificador->ViewValue = $this->nu_identificador->CurrentValue;
		$this->nu_identificador->ViewCustomAttributes = "";

		// dt_data
		$this->dt_data->ViewValue = $this->dt_data->CurrentValue;
		$this->dt_data->ViewValue = ew_FormatDateTime($this->dt_data->ViewValue, 7);
		$this->dt_data->ViewCustomAttributes = "";

		// ds_dominioArquivo
		$this->ds_dominioArquivo->ViewValue = $this->ds_dominioArquivo->CurrentValue;
		$this->ds_dominioArquivo->ViewCustomAttributes = "";

		// no_perfil
		$this->no_perfil->ViewValue = $this->no_perfil->CurrentValue;
		$this->no_perfil->ViewCustomAttributes = "";

		// ic_acao
		$this->ic_acao->ViewValue = $this->ic_acao->CurrentValue;
		$this->ic_acao->ViewCustomAttributes = "";

		// no_tabela
		$this->no_tabela->ViewValue = $this->no_tabela->CurrentValue;
		$this->no_tabela->ViewCustomAttributes = "";

		// no_campo
		$this->no_campo->ViewValue = $this->no_campo->CurrentValue;
		$this->no_campo->ViewCustomAttributes = "";

		// nu_chaveCampo
		$this->nu_chaveCampo->ViewValue = $this->nu_chaveCampo->CurrentValue;
		$this->nu_chaveCampo->ViewCustomAttributes = "";

		// im_antes
		$this->im_antes->ViewValue = $this->im_antes->CurrentValue;
		$this->im_antes->ViewCustomAttributes = "";

		// im_depois
		$this->im_depois->ViewValue = $this->im_depois->CurrentValue;
		$this->im_depois->ViewCustomAttributes = "";

		// nu_identificador
		$this->nu_identificador->LinkCustomAttributes = "";
		$this->nu_identificador->HrefValue = "";
		$this->nu_identificador->TooltipValue = "";

		// dt_data
		$this->dt_data->LinkCustomAttributes = "";
		$this->dt_data->HrefValue = "";
		$this->dt_data->TooltipValue = "";

		// ds_dominioArquivo
		$this->ds_dominioArquivo->LinkCustomAttributes = "";
		$this->ds_dominioArquivo->HrefValue = "";
		$this->ds_dominioArquivo->TooltipValue = "";

		// no_perfil
		$this->no_perfil->LinkCustomAttributes = "";
		$this->no_perfil->HrefValue = "";
		$this->no_perfil->TooltipValue = "";

		// ic_acao
		$this->ic_acao->LinkCustomAttributes = "";
		$this->ic_acao->HrefValue = "";
		$this->ic_acao->TooltipValue = "";

		// no_tabela
		$this->no_tabela->LinkCustomAttributes = "";
		$this->no_tabela->HrefValue = "";
		$this->no_tabela->TooltipValue = "";

		// no_campo
		$this->no_campo->LinkCustomAttributes = "";
		$this->no_campo->HrefValue = "";
		$this->no_campo->TooltipValue = "";

		// nu_chaveCampo
		$this->nu_chaveCampo->LinkCustomAttributes = "";
		$this->nu_chaveCampo->HrefValue = "";
		$this->nu_chaveCampo->TooltipValue = "";

		// im_antes
		$this->im_antes->LinkCustomAttributes = "";
		$this->im_antes->HrefValue = "";
		$this->im_antes->TooltipValue = "";

		// im_depois
		$this->im_depois->LinkCustomAttributes = "";
		$this->im_depois->HrefValue = "";
		$this->im_depois->TooltipValue = "";

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
				if ($this->nu_identificador->Exportable) $Doc->ExportCaption($this->nu_identificador);
				if ($this->dt_data->Exportable) $Doc->ExportCaption($this->dt_data);
				if ($this->ds_dominioArquivo->Exportable) $Doc->ExportCaption($this->ds_dominioArquivo);
				if ($this->no_perfil->Exportable) $Doc->ExportCaption($this->no_perfil);
				if ($this->ic_acao->Exportable) $Doc->ExportCaption($this->ic_acao);
				if ($this->no_tabela->Exportable) $Doc->ExportCaption($this->no_tabela);
				if ($this->no_campo->Exportable) $Doc->ExportCaption($this->no_campo);
				if ($this->nu_chaveCampo->Exportable) $Doc->ExportCaption($this->nu_chaveCampo);
				if ($this->im_antes->Exportable) $Doc->ExportCaption($this->im_antes);
				if ($this->im_depois->Exportable) $Doc->ExportCaption($this->im_depois);
			} else {
				if ($this->nu_identificador->Exportable) $Doc->ExportCaption($this->nu_identificador);
				if ($this->dt_data->Exportable) $Doc->ExportCaption($this->dt_data);
				if ($this->ds_dominioArquivo->Exportable) $Doc->ExportCaption($this->ds_dominioArquivo);
				if ($this->no_perfil->Exportable) $Doc->ExportCaption($this->no_perfil);
				if ($this->ic_acao->Exportable) $Doc->ExportCaption($this->ic_acao);
				if ($this->no_tabela->Exportable) $Doc->ExportCaption($this->no_tabela);
				if ($this->no_campo->Exportable) $Doc->ExportCaption($this->no_campo);
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
					if ($this->nu_identificador->Exportable) $Doc->ExportField($this->nu_identificador);
					if ($this->dt_data->Exportable) $Doc->ExportField($this->dt_data);
					if ($this->ds_dominioArquivo->Exportable) $Doc->ExportField($this->ds_dominioArquivo);
					if ($this->no_perfil->Exportable) $Doc->ExportField($this->no_perfil);
					if ($this->ic_acao->Exportable) $Doc->ExportField($this->ic_acao);
					if ($this->no_tabela->Exportable) $Doc->ExportField($this->no_tabela);
					if ($this->no_campo->Exportable) $Doc->ExportField($this->no_campo);
					if ($this->nu_chaveCampo->Exportable) $Doc->ExportField($this->nu_chaveCampo);
					if ($this->im_antes->Exportable) $Doc->ExportField($this->im_antes);
					if ($this->im_depois->Exportable) $Doc->ExportField($this->im_depois);
				} else {
					if ($this->nu_identificador->Exportable) $Doc->ExportField($this->nu_identificador);
					if ($this->dt_data->Exportable) $Doc->ExportField($this->dt_data);
					if ($this->ds_dominioArquivo->Exportable) $Doc->ExportField($this->ds_dominioArquivo);
					if ($this->no_perfil->Exportable) $Doc->ExportField($this->no_perfil);
					if ($this->ic_acao->Exportable) $Doc->ExportField($this->ic_acao);
					if ($this->no_tabela->Exportable) $Doc->ExportField($this->no_tabela);
					if ($this->no_campo->Exportable) $Doc->ExportField($this->no_campo);
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
