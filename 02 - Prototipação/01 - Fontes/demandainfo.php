<?php

// Global variable for table object
$demanda = NULL;

//
// Table class for demanda
//
class cdemanda extends cTable {
	var $nu_demanda;
	var $ds_demanda;
	var $nu_pessoaResponsavel;
	var $nu_itemPDTI;
	var $dt_registro;
	var $im_anexo;
	var $ic_situacao;
	var $dt_aprovacao;
	var $nu_pessoaAprovadora;
	var $nu_usuario;
	var $ts_datahora;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'demanda';
		$this->TableName = 'demanda';
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

		// nu_demanda
		$this->nu_demanda = new cField('demanda', 'demanda', 'x_nu_demanda', 'nu_demanda', '[nu_demanda]', 'CAST([nu_demanda] AS NVARCHAR)', 3, -1, FALSE, '[nu_demanda]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_demanda->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_demanda'] = &$this->nu_demanda;

		// ds_demanda
		$this->ds_demanda = new cField('demanda', 'demanda', 'x_ds_demanda', 'ds_demanda', '[ds_demanda]', '[ds_demanda]', 201, -1, FALSE, '[ds_demanda]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_demanda'] = &$this->ds_demanda;

		// nu_pessoaResponsavel
		$this->nu_pessoaResponsavel = new cField('demanda', 'demanda', 'x_nu_pessoaResponsavel', 'nu_pessoaResponsavel', '[nu_pessoaResponsavel]', 'CAST([nu_pessoaResponsavel] AS NVARCHAR)', 3, -1, FALSE, '[nu_pessoaResponsavel]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_pessoaResponsavel->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_pessoaResponsavel'] = &$this->nu_pessoaResponsavel;

		// nu_itemPDTI
		$this->nu_itemPDTI = new cField('demanda', 'demanda', 'x_nu_itemPDTI', 'nu_itemPDTI', '[nu_itemPDTI]', 'CAST([nu_itemPDTI] AS NVARCHAR)', 3, -1, FALSE, '[nu_itemPDTI]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_itemPDTI->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_itemPDTI'] = &$this->nu_itemPDTI;

		// dt_registro
		$this->dt_registro = new cField('demanda', 'demanda', 'x_dt_registro', 'dt_registro', '[dt_registro]', '(REPLACE(STR(DAY([dt_registro]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dt_registro]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dt_registro]),4,0))', 135, 7, FALSE, '[dt_registro]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dt_registro->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dt_registro'] = &$this->dt_registro;

		// im_anexo
		$this->im_anexo = new cField('demanda', 'demanda', 'x_im_anexo', 'im_anexo', '[im_anexo]', '[im_anexo]', 200, -1, TRUE, '[im_anexo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['im_anexo'] = &$this->im_anexo;

		// ic_situacao
		$this->ic_situacao = new cField('demanda', 'demanda', 'x_ic_situacao', 'ic_situacao', '[ic_situacao]', '[ic_situacao]', 129, -1, FALSE, '[ic_situacao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_situacao'] = &$this->ic_situacao;

		// dt_aprovacao
		$this->dt_aprovacao = new cField('demanda', 'demanda', 'x_dt_aprovacao', 'dt_aprovacao', '[dt_aprovacao]', '(REPLACE(STR(DAY([dt_aprovacao]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dt_aprovacao]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dt_aprovacao]),4,0))', 135, 7, FALSE, '[dt_aprovacao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dt_aprovacao->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dt_aprovacao'] = &$this->dt_aprovacao;

		// nu_pessoaAprovadora
		$this->nu_pessoaAprovadora = new cField('demanda', 'demanda', 'x_nu_pessoaAprovadora', 'nu_pessoaAprovadora', '[nu_pessoaAprovadora]', 'CAST([nu_pessoaAprovadora] AS NVARCHAR)', 3, -1, FALSE, '[nu_pessoaAprovadora]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_pessoaAprovadora->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_pessoaAprovadora'] = &$this->nu_pessoaAprovadora;

		// nu_usuario
		$this->nu_usuario = new cField('demanda', 'demanda', 'x_nu_usuario', 'nu_usuario', '[nu_usuario]', 'CAST([nu_usuario] AS NVARCHAR)', 3, -1, FALSE, '[nu_usuario]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_usuario->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_usuario'] = &$this->nu_usuario;

		// ts_datahora
		$this->ts_datahora = new cField('demanda', 'demanda', 'x_ts_datahora', 'ts_datahora', '[ts_datahora]', '(REPLACE(STR(DAY([ts_datahora]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([ts_datahora]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([ts_datahora]),4,0))', 135, 7, FALSE, '[ts_datahora]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->ts_datahora->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['ts_datahora'] = &$this->ts_datahora;
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
		return "[dbo].[demanda]";
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
	var $UpdateTable = "[dbo].[demanda]";

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
			if (array_key_exists('nu_demanda', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_demanda') . '=' . ew_QuotedValue($rs['nu_demanda'], $this->nu_demanda->FldDataType));
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
		return "[nu_demanda] = @nu_demanda@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nu_demanda->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_demanda@", ew_AdjustSql($this->nu_demanda->CurrentValue), $sKeyFilter); // Replace key value
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
			return "demandalist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "demandalist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("demandaview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("demandaview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "demandaadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("demandaedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("demandaadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("demandadelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nu_demanda->CurrentValue)) {
			$sUrl .= "nu_demanda=" . urlencode($this->nu_demanda->CurrentValue);
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
			$arKeys[] = @$_GET["nu_demanda"]; // nu_demanda

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
			$this->nu_demanda->CurrentValue = $key;
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
		$this->nu_demanda->setDbValue($rs->fields('nu_demanda'));
		$this->ds_demanda->setDbValue($rs->fields('ds_demanda'));
		$this->nu_pessoaResponsavel->setDbValue($rs->fields('nu_pessoaResponsavel'));
		$this->nu_itemPDTI->setDbValue($rs->fields('nu_itemPDTI'));
		$this->dt_registro->setDbValue($rs->fields('dt_registro'));
		$this->im_anexo->Upload->DbValue = $rs->fields('im_anexo');
		$this->ic_situacao->setDbValue($rs->fields('ic_situacao'));
		$this->dt_aprovacao->setDbValue($rs->fields('dt_aprovacao'));
		$this->nu_pessoaAprovadora->setDbValue($rs->fields('nu_pessoaAprovadora'));
		$this->nu_usuario->setDbValue($rs->fields('nu_usuario'));
		$this->ts_datahora->setDbValue($rs->fields('ts_datahora'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_demanda
		// ds_demanda
		// nu_pessoaResponsavel
		// nu_itemPDTI
		// dt_registro
		// im_anexo
		// ic_situacao
		// dt_aprovacao
		// nu_pessoaAprovadora
		// nu_usuario
		// ts_datahora
		// nu_demanda

		$this->nu_demanda->ViewValue = $this->nu_demanda->CurrentValue;
		$this->nu_demanda->ViewCustomAttributes = "";

		// ds_demanda
		$this->ds_demanda->ViewValue = $this->ds_demanda->CurrentValue;
		$this->ds_demanda->ViewCustomAttributes = "";

		// nu_pessoaResponsavel
		if (strval($this->nu_pessoaResponsavel->CurrentValue) <> "") {
			$sFilterWrk = "[nu_pessoa]" . ew_SearchString("=", $this->nu_pessoaResponsavel->CurrentValue, EW_DATATYPE_NUMBER);
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
		$this->Lookup_Selecting($this->nu_pessoaResponsavel, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_pessoa] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_pessoaResponsavel->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_pessoaResponsavel->ViewValue = $this->nu_pessoaResponsavel->CurrentValue;
			}
		} else {
			$this->nu_pessoaResponsavel->ViewValue = NULL;
		}
		$this->nu_pessoaResponsavel->ViewCustomAttributes = "";

		// nu_itemPDTI
		$this->nu_itemPDTI->ViewValue = $this->nu_itemPDTI->CurrentValue;
		$this->nu_itemPDTI->ViewCustomAttributes = "";

		// dt_registro
		$this->dt_registro->ViewValue = $this->dt_registro->CurrentValue;
		$this->dt_registro->ViewValue = ew_FormatDateTime($this->dt_registro->ViewValue, 7);
		$this->dt_registro->ViewCustomAttributes = "";

		// im_anexo
		if (!ew_Empty($this->im_anexo->Upload->DbValue)) {
			$this->im_anexo->ViewValue = $this->im_anexo->Upload->DbValue;
		} else {
			$this->im_anexo->ViewValue = "";
		}
		$this->im_anexo->ViewCustomAttributes = "";

		// ic_situacao
		if (strval($this->ic_situacao->CurrentValue) <> "") {
			switch ($this->ic_situacao->CurrentValue) {
				case $this->ic_situacao->FldTagValue(1):
					$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(1) <> "" ? $this->ic_situacao->FldTagCaption(1) : $this->ic_situacao->CurrentValue;
					break;
				case $this->ic_situacao->FldTagValue(2):
					$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(2) <> "" ? $this->ic_situacao->FldTagCaption(2) : $this->ic_situacao->CurrentValue;
					break;
				case $this->ic_situacao->FldTagValue(3):
					$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(3) <> "" ? $this->ic_situacao->FldTagCaption(3) : $this->ic_situacao->CurrentValue;
					break;
				case $this->ic_situacao->FldTagValue(4):
					$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(4) <> "" ? $this->ic_situacao->FldTagCaption(4) : $this->ic_situacao->CurrentValue;
					break;
				default:
					$this->ic_situacao->ViewValue = $this->ic_situacao->CurrentValue;
			}
		} else {
			$this->ic_situacao->ViewValue = NULL;
		}
		$this->ic_situacao->ViewCustomAttributes = "";

		// dt_aprovacao
		$this->dt_aprovacao->ViewValue = $this->dt_aprovacao->CurrentValue;
		$this->dt_aprovacao->ViewValue = ew_FormatDateTime($this->dt_aprovacao->ViewValue, 7);
		$this->dt_aprovacao->ViewCustomAttributes = "";

		// nu_pessoaAprovadora
		if (strval($this->nu_pessoaAprovadora->CurrentValue) <> "") {
			$sFilterWrk = "[nu_pessoa]" . ew_SearchString("=", $this->nu_pessoaAprovadora->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_pessoa], [no_pessoa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[pessoa]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_pessoaAprovadora, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_pessoaAprovadora->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_pessoaAprovadora->ViewValue = $this->nu_pessoaAprovadora->CurrentValue;
			}
		} else {
			$this->nu_pessoaAprovadora->ViewValue = NULL;
		}
		$this->nu_pessoaAprovadora->ViewCustomAttributes = "";

		// nu_usuario
		$this->nu_usuario->ViewValue = $this->nu_usuario->CurrentValue;
		$this->nu_usuario->ViewCustomAttributes = "";

		// ts_datahora
		$this->ts_datahora->ViewValue = $this->ts_datahora->CurrentValue;
		$this->ts_datahora->ViewValue = ew_FormatDateTime($this->ts_datahora->ViewValue, 7);
		$this->ts_datahora->ViewCustomAttributes = "";

		// nu_demanda
		$this->nu_demanda->LinkCustomAttributes = "";
		$this->nu_demanda->HrefValue = "";
		$this->nu_demanda->TooltipValue = "";

		// ds_demanda
		$this->ds_demanda->LinkCustomAttributes = "";
		$this->ds_demanda->HrefValue = "";
		$this->ds_demanda->TooltipValue = "";

		// nu_pessoaResponsavel
		$this->nu_pessoaResponsavel->LinkCustomAttributes = "";
		$this->nu_pessoaResponsavel->HrefValue = "";
		$this->nu_pessoaResponsavel->TooltipValue = "";

		// nu_itemPDTI
		$this->nu_itemPDTI->LinkCustomAttributes = "";
		$this->nu_itemPDTI->HrefValue = "";
		$this->nu_itemPDTI->TooltipValue = "";

		// dt_registro
		$this->dt_registro->LinkCustomAttributes = "";
		$this->dt_registro->HrefValue = "";
		$this->dt_registro->TooltipValue = "";

		// im_anexo
		$this->im_anexo->LinkCustomAttributes = "";
		$this->im_anexo->HrefValue = "";
		$this->im_anexo->HrefValue2 = $this->im_anexo->UploadPath . $this->im_anexo->Upload->DbValue;
		$this->im_anexo->TooltipValue = "";

		// ic_situacao
		$this->ic_situacao->LinkCustomAttributes = "";
		$this->ic_situacao->HrefValue = "";
		$this->ic_situacao->TooltipValue = "";

		// dt_aprovacao
		$this->dt_aprovacao->LinkCustomAttributes = "";
		$this->dt_aprovacao->HrefValue = "";
		$this->dt_aprovacao->TooltipValue = "";

		// nu_pessoaAprovadora
		$this->nu_pessoaAprovadora->LinkCustomAttributes = "";
		$this->nu_pessoaAprovadora->HrefValue = "";
		$this->nu_pessoaAprovadora->TooltipValue = "";

		// nu_usuario
		$this->nu_usuario->LinkCustomAttributes = "";
		$this->nu_usuario->HrefValue = "";
		$this->nu_usuario->TooltipValue = "";

		// ts_datahora
		$this->ts_datahora->LinkCustomAttributes = "";
		$this->ts_datahora->HrefValue = "";
		$this->ts_datahora->TooltipValue = "";

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
				if ($this->nu_demanda->Exportable) $Doc->ExportCaption($this->nu_demanda);
				if ($this->ds_demanda->Exportable) $Doc->ExportCaption($this->ds_demanda);
				if ($this->nu_pessoaResponsavel->Exportable) $Doc->ExportCaption($this->nu_pessoaResponsavel);
				if ($this->nu_itemPDTI->Exportable) $Doc->ExportCaption($this->nu_itemPDTI);
				if ($this->dt_registro->Exportable) $Doc->ExportCaption($this->dt_registro);
				if ($this->im_anexo->Exportable) $Doc->ExportCaption($this->im_anexo);
				if ($this->ic_situacao->Exportable) $Doc->ExportCaption($this->ic_situacao);
				if ($this->dt_aprovacao->Exportable) $Doc->ExportCaption($this->dt_aprovacao);
				if ($this->nu_pessoaAprovadora->Exportable) $Doc->ExportCaption($this->nu_pessoaAprovadora);
				if ($this->nu_usuario->Exportable) $Doc->ExportCaption($this->nu_usuario);
				if ($this->ts_datahora->Exportable) $Doc->ExportCaption($this->ts_datahora);
			} else {
				if ($this->nu_demanda->Exportable) $Doc->ExportCaption($this->nu_demanda);
				if ($this->nu_pessoaResponsavel->Exportable) $Doc->ExportCaption($this->nu_pessoaResponsavel);
				if ($this->nu_itemPDTI->Exportable) $Doc->ExportCaption($this->nu_itemPDTI);
				if ($this->dt_registro->Exportable) $Doc->ExportCaption($this->dt_registro);
				if ($this->im_anexo->Exportable) $Doc->ExportCaption($this->im_anexo);
				if ($this->ic_situacao->Exportable) $Doc->ExportCaption($this->ic_situacao);
				if ($this->dt_aprovacao->Exportable) $Doc->ExportCaption($this->dt_aprovacao);
				if ($this->nu_pessoaAprovadora->Exportable) $Doc->ExportCaption($this->nu_pessoaAprovadora);
				if ($this->nu_usuario->Exportable) $Doc->ExportCaption($this->nu_usuario);
				if ($this->ts_datahora->Exportable) $Doc->ExportCaption($this->ts_datahora);
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
					if ($this->nu_demanda->Exportable) $Doc->ExportField($this->nu_demanda);
					if ($this->ds_demanda->Exportable) $Doc->ExportField($this->ds_demanda);
					if ($this->nu_pessoaResponsavel->Exportable) $Doc->ExportField($this->nu_pessoaResponsavel);
					if ($this->nu_itemPDTI->Exportable) $Doc->ExportField($this->nu_itemPDTI);
					if ($this->dt_registro->Exportable) $Doc->ExportField($this->dt_registro);
					if ($this->im_anexo->Exportable) $Doc->ExportField($this->im_anexo);
					if ($this->ic_situacao->Exportable) $Doc->ExportField($this->ic_situacao);
					if ($this->dt_aprovacao->Exportable) $Doc->ExportField($this->dt_aprovacao);
					if ($this->nu_pessoaAprovadora->Exportable) $Doc->ExportField($this->nu_pessoaAprovadora);
					if ($this->nu_usuario->Exportable) $Doc->ExportField($this->nu_usuario);
					if ($this->ts_datahora->Exportable) $Doc->ExportField($this->ts_datahora);
				} else {
					if ($this->nu_demanda->Exportable) $Doc->ExportField($this->nu_demanda);
					if ($this->nu_pessoaResponsavel->Exportable) $Doc->ExportField($this->nu_pessoaResponsavel);
					if ($this->nu_itemPDTI->Exportable) $Doc->ExportField($this->nu_itemPDTI);
					if ($this->dt_registro->Exportable) $Doc->ExportField($this->dt_registro);
					if ($this->im_anexo->Exportable) $Doc->ExportField($this->im_anexo);
					if ($this->ic_situacao->Exportable) $Doc->ExportField($this->ic_situacao);
					if ($this->dt_aprovacao->Exportable) $Doc->ExportField($this->dt_aprovacao);
					if ($this->nu_pessoaAprovadora->Exportable) $Doc->ExportField($this->nu_pessoaAprovadora);
					if ($this->nu_usuario->Exportable) $Doc->ExportField($this->nu_usuario);
					if ($this->ts_datahora->Exportable) $Doc->ExportField($this->ts_datahora);
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
