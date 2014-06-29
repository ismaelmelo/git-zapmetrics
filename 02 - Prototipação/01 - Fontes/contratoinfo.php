<?php

// Global variable for table object
$contrato = NULL;

//
// Table class for contrato
//
class ccontrato extends cTable {
	var $nu_contrato;
	var $co_alternativo;
	var $nu_fornecedor;
	var $no_contrato;
	var $ds_contrato;
	var $dt_vencimento;
	var $im_contrato;
	var $nu_stContrato;
	var $ds_observacoes;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'contrato';
		$this->TableName = 'contrato';
		$this->TableType = 'TABLE';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->DetailAdd = TRUE; // Allow detail add
		$this->DetailEdit = TRUE; // Allow detail edit
		$this->DetailView = TRUE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 1;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// nu_contrato
		$this->nu_contrato = new cField('contrato', 'contrato', 'x_nu_contrato', 'nu_contrato', '[nu_contrato]', 'CAST([nu_contrato] AS NVARCHAR)', 3, -1, FALSE, '[nu_contrato]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_contrato->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_contrato'] = &$this->nu_contrato;

		// co_alternativo
		$this->co_alternativo = new cField('contrato', 'contrato', 'x_co_alternativo', 'co_alternativo', '[co_alternativo]', 'CAST([co_alternativo] AS NVARCHAR)', 3, -1, FALSE, '[co_alternativo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->co_alternativo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['co_alternativo'] = &$this->co_alternativo;

		// nu_fornecedor
		$this->nu_fornecedor = new cField('contrato', 'contrato', 'x_nu_fornecedor', 'nu_fornecedor', '[nu_fornecedor]', 'CAST([nu_fornecedor] AS NVARCHAR)', 3, -1, FALSE, '[nu_fornecedor]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_fornecedor->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_fornecedor'] = &$this->nu_fornecedor;

		// no_contrato
		$this->no_contrato = new cField('contrato', 'contrato', 'x_no_contrato', 'no_contrato', '[no_contrato]', '[no_contrato]', 200, -1, FALSE, '[no_contrato]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_contrato'] = &$this->no_contrato;

		// ds_contrato
		$this->ds_contrato = new cField('contrato', 'contrato', 'x_ds_contrato', 'ds_contrato', '[ds_contrato]', '[ds_contrato]', 201, -1, FALSE, '[ds_contrato]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_contrato'] = &$this->ds_contrato;

		// dt_vencimento
		$this->dt_vencimento = new cField('contrato', 'contrato', 'x_dt_vencimento', 'dt_vencimento', '[dt_vencimento]', '(REPLACE(STR(DAY([dt_vencimento]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dt_vencimento]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dt_vencimento]),4,0))', 135, 7, FALSE, '[dt_vencimento]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dt_vencimento->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dt_vencimento'] = &$this->dt_vencimento;

		// im_contrato
		$this->im_contrato = new cField('contrato', 'contrato', 'x_im_contrato', 'im_contrato', '[im_contrato]', '[im_contrato]', 201, -1, TRUE, '[im_contrato]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->im_contrato->UploadMultiple = TRUE;
		$this->fields['im_contrato'] = &$this->im_contrato;

		// nu_stContrato
		$this->nu_stContrato = new cField('contrato', 'contrato', 'x_nu_stContrato', 'nu_stContrato', '[nu_stContrato]', 'CAST([nu_stContrato] AS NVARCHAR)', 3, -1, FALSE, '[nu_stContrato]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_stContrato->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_stContrato'] = &$this->nu_stContrato;

		// ds_observacoes
		$this->ds_observacoes = new cField('contrato', 'contrato', 'x_ds_observacoes', 'ds_observacoes', '[ds_observacoes]', '[ds_observacoes]', 201, -1, FALSE, '[ds_observacoes]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_observacoes'] = &$this->ds_observacoes;
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

	// Current detail table name
	function getCurrentDetailTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE];
	}

	function setCurrentDetailTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE] = $v;
	}

	// Get detail url
	function GetDetailUrl() {

		// Detail url
		$sDetailUrl = "";
		if ($this->getCurrentDetailTable() == "item_contratado") {
			$sDetailUrl = $GLOBALS["item_contratado"]->GetListUrl() . "?showmaster=" . $this->TableVar;
			$sDetailUrl .= "&nu_contrato=" . $this->nu_contrato->CurrentValue;
		}
		if ($sDetailUrl == "") {
			$sDetailUrl = "contratolist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	function SqlFrom() { // From
		return "[dbo].[contrato]";
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
		return "[co_alternativo] DESC";
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
	var $UpdateTable = "[dbo].[contrato]";

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

		// Cascade update detail field 'nu_contrato'
		if (!is_null($rsold) && (isset($rs['nu_contrato']) && $rsold['nu_contrato'] <> $rs['nu_contrato'])) {
			if (!isset($GLOBALS["item_contratado"])) $GLOBALS["item_contratado"] = new citem_contratado();
			$rscascade = array();
			$rscascade['nu_contrato'] = $rs['nu_contrato']; 
			$GLOBALS["item_contratado"]->Update($rscascade, "[nu_contrato] = " . ew_QuotedValue($rsold['nu_contrato'], EW_DATATYPE_NUMBER));
		}
		return $conn->Execute($this->UpdateSQL($rs, $where));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "") {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if ($rs) {
			if (array_key_exists('nu_contrato', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_contrato') . '=' . ew_QuotedValue($rs['nu_contrato'], $this->nu_contrato->FldDataType));
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

		// Cascade delete detail table 'item_contratado'
		if (!isset($GLOBALS["item_contratado"])) $GLOBALS["item_contratado"] = new citem_contratado();
		$rscascade = array();
		$GLOBALS["item_contratado"]->Delete($rscascade, "[nu_contrato] = " . ew_QuotedValue($rs['nu_contrato'], EW_DATATYPE_NUMBER));
		return $conn->Execute($this->DeleteSQL($rs, $where));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "[nu_contrato] = @nu_contrato@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nu_contrato->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_contrato@", ew_AdjustSql($this->nu_contrato->CurrentValue), $sKeyFilter); // Replace key value
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
			return "contratolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "contratolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("contratoview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("contratoview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "contratoadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("contratoedit.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("contratoedit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("contratoadd.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("contratoadd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("contratodelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nu_contrato->CurrentValue)) {
			$sUrl .= "nu_contrato=" . urlencode($this->nu_contrato->CurrentValue);
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
			$arKeys[] = @$_GET["nu_contrato"]; // nu_contrato

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
			$this->nu_contrato->CurrentValue = $key;
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
		$this->co_alternativo->setDbValue($rs->fields('co_alternativo'));
		$this->nu_fornecedor->setDbValue($rs->fields('nu_fornecedor'));
		$this->no_contrato->setDbValue($rs->fields('no_contrato'));
		$this->ds_contrato->setDbValue($rs->fields('ds_contrato'));
		$this->dt_vencimento->setDbValue($rs->fields('dt_vencimento'));
		$this->im_contrato->Upload->DbValue = $rs->fields('im_contrato');
		$this->nu_stContrato->setDbValue($rs->fields('nu_stContrato'));
		$this->ds_observacoes->setDbValue($rs->fields('ds_observacoes'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_contrato

		$this->nu_contrato->CellCssStyle = "white-space: nowrap;";

		// co_alternativo
		// nu_fornecedor
		// no_contrato
		// ds_contrato
		// dt_vencimento
		// im_contrato
		// nu_stContrato
		// ds_observacoes
		// nu_contrato

		$this->nu_contrato->ViewValue = $this->nu_contrato->CurrentValue;
		$this->nu_contrato->ViewCustomAttributes = "";

		// co_alternativo
		$this->co_alternativo->ViewValue = $this->co_alternativo->CurrentValue;
		$this->co_alternativo->ViewCustomAttributes = "";

		// nu_fornecedor
		if (strval($this->nu_fornecedor->CurrentValue) <> "") {
			$sFilterWrk = "[nu_fornecedor]" . ew_SearchString("=", $this->nu_fornecedor->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_fornecedor], [no_fornecedor] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[fornecedor]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_fornecedor, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_fornecedor] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_fornecedor->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_fornecedor->ViewValue = $this->nu_fornecedor->CurrentValue;
			}
		} else {
			$this->nu_fornecedor->ViewValue = NULL;
		}
		$this->nu_fornecedor->ViewCustomAttributes = "";

		// no_contrato
		$this->no_contrato->ViewValue = $this->no_contrato->CurrentValue;
		$this->no_contrato->ViewCustomAttributes = "";

		// ds_contrato
		$this->ds_contrato->ViewValue = $this->ds_contrato->CurrentValue;
		$this->ds_contrato->ViewCustomAttributes = "";

		// dt_vencimento
		$this->dt_vencimento->ViewValue = $this->dt_vencimento->CurrentValue;
		$this->dt_vencimento->ViewValue = ew_FormatDateTime($this->dt_vencimento->ViewValue, 7);
		$this->dt_vencimento->ViewCustomAttributes = "";

		// im_contrato
		$this->im_contrato->UploadPath = "arquivos/contratos";
		if (!ew_Empty($this->im_contrato->Upload->DbValue)) {
			$this->im_contrato->ViewValue = $this->im_contrato->Upload->DbValue;
		} else {
			$this->im_contrato->ViewValue = "";
		}
		$this->im_contrato->ViewCustomAttributes = "";

		// nu_stContrato
		if (strval($this->nu_stContrato->CurrentValue) <> "") {
			$sFilterWrk = "[nu_stContrato]" . ew_SearchString("=", $this->nu_stContrato->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_stContrato], [no_stContrato] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[stcontrato]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_stContrato, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_stContrato] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_stContrato->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_stContrato->ViewValue = $this->nu_stContrato->CurrentValue;
			}
		} else {
			$this->nu_stContrato->ViewValue = NULL;
		}
		$this->nu_stContrato->ViewCustomAttributes = "";

		// ds_observacoes
		$this->ds_observacoes->ViewValue = $this->ds_observacoes->CurrentValue;
		$this->ds_observacoes->ViewCustomAttributes = "";

		// nu_contrato
		$this->nu_contrato->LinkCustomAttributes = "";
		$this->nu_contrato->HrefValue = "";
		$this->nu_contrato->TooltipValue = "";

		// co_alternativo
		$this->co_alternativo->LinkCustomAttributes = "";
		$this->co_alternativo->HrefValue = "";
		$this->co_alternativo->TooltipValue = "";

		// nu_fornecedor
		$this->nu_fornecedor->LinkCustomAttributes = "";
		$this->nu_fornecedor->HrefValue = "";
		$this->nu_fornecedor->TooltipValue = "";

		// no_contrato
		$this->no_contrato->LinkCustomAttributes = "";
		$this->no_contrato->HrefValue = "";
		$this->no_contrato->TooltipValue = "";

		// ds_contrato
		$this->ds_contrato->LinkCustomAttributes = "";
		$this->ds_contrato->HrefValue = "";
		$this->ds_contrato->TooltipValue = "";

		// dt_vencimento
		$this->dt_vencimento->LinkCustomAttributes = "";
		$this->dt_vencimento->HrefValue = "";
		$this->dt_vencimento->TooltipValue = "";

		// im_contrato
		$this->im_contrato->LinkCustomAttributes = "";
		$this->im_contrato->UploadPath = "arquivos/contratos";
		if (!ew_Empty($this->im_contrato->Upload->DbValue)) {
			$this->im_contrato->HrefValue = "%u"; // Add prefix/suffix
			$this->im_contrato->LinkAttrs["target"] = ""; // Add target
			if ($this->Export <> "") $this->im_contrato->HrefValue = ew_ConvertFullUrl($this->im_contrato->HrefValue);
		} else {
			$this->im_contrato->HrefValue = "";
		}
		$this->im_contrato->HrefValue2 = $this->im_contrato->UploadPath . $this->im_contrato->Upload->DbValue;
		$this->im_contrato->TooltipValue = "";

		// nu_stContrato
		$this->nu_stContrato->LinkCustomAttributes = "";
		$this->nu_stContrato->HrefValue = "";
		$this->nu_stContrato->TooltipValue = "";

		// ds_observacoes
		$this->ds_observacoes->LinkCustomAttributes = "";
		$this->ds_observacoes->HrefValue = "";
		$this->ds_observacoes->TooltipValue = "";

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
				if ($this->co_alternativo->Exportable) $Doc->ExportCaption($this->co_alternativo);
				if ($this->nu_fornecedor->Exportable) $Doc->ExportCaption($this->nu_fornecedor);
				if ($this->no_contrato->Exportable) $Doc->ExportCaption($this->no_contrato);
				if ($this->ds_contrato->Exportable) $Doc->ExportCaption($this->ds_contrato);
				if ($this->dt_vencimento->Exportable) $Doc->ExportCaption($this->dt_vencimento);
				if ($this->im_contrato->Exportable) $Doc->ExportCaption($this->im_contrato);
				if ($this->nu_stContrato->Exportable) $Doc->ExportCaption($this->nu_stContrato);
				if ($this->ds_observacoes->Exportable) $Doc->ExportCaption($this->ds_observacoes);
			} else {
				if ($this->nu_contrato->Exportable) $Doc->ExportCaption($this->nu_contrato);
				if ($this->co_alternativo->Exportable) $Doc->ExportCaption($this->co_alternativo);
				if ($this->nu_fornecedor->Exportable) $Doc->ExportCaption($this->nu_fornecedor);
				if ($this->no_contrato->Exportable) $Doc->ExportCaption($this->no_contrato);
				if ($this->dt_vencimento->Exportable) $Doc->ExportCaption($this->dt_vencimento);
				if ($this->nu_stContrato->Exportable) $Doc->ExportCaption($this->nu_stContrato);
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
					if ($this->co_alternativo->Exportable) $Doc->ExportField($this->co_alternativo);
					if ($this->nu_fornecedor->Exportable) $Doc->ExportField($this->nu_fornecedor);
					if ($this->no_contrato->Exportable) $Doc->ExportField($this->no_contrato);
					if ($this->ds_contrato->Exportable) $Doc->ExportField($this->ds_contrato);
					if ($this->dt_vencimento->Exportable) $Doc->ExportField($this->dt_vencimento);
					if ($this->im_contrato->Exportable) $Doc->ExportField($this->im_contrato);
					if ($this->nu_stContrato->Exportable) $Doc->ExportField($this->nu_stContrato);
					if ($this->ds_observacoes->Exportable) $Doc->ExportField($this->ds_observacoes);
				} else {
					if ($this->nu_contrato->Exportable) $Doc->ExportField($this->nu_contrato);
					if ($this->co_alternativo->Exportable) $Doc->ExportField($this->co_alternativo);
					if ($this->nu_fornecedor->Exportable) $Doc->ExportField($this->nu_fornecedor);
					if ($this->no_contrato->Exportable) $Doc->ExportField($this->no_contrato);
					if ($this->dt_vencimento->Exportable) $Doc->ExportField($this->dt_vencimento);
					if ($this->nu_stContrato->Exportable) $Doc->ExportField($this->nu_stContrato);
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
