<?php

// Global variable for table object
$demanda_artefato = NULL;

//
// Table class for demanda_artefato
//
class cdemanda_artefato extends cTable {
	var $nu_artefato;
	var $nu_demanda;
	var $ic_tpArtefato;
	var $no_local;
	var $im_anexo;
	var $ic_situacao;
	var $nu_pessoaResp;
	var $nu_usuario;
	var $ts_datahora;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'demanda_artefato';
		$this->TableName = 'demanda_artefato';
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

		// nu_artefato
		$this->nu_artefato = new cField('demanda_artefato', 'demanda_artefato', 'x_nu_artefato', 'nu_artefato', '[nu_artefato]', 'CAST([nu_artefato] AS NVARCHAR)', 3, -1, FALSE, '[nu_artefato]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_artefato->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_artefato'] = &$this->nu_artefato;

		// nu_demanda
		$this->nu_demanda = new cField('demanda_artefato', 'demanda_artefato', 'x_nu_demanda', 'nu_demanda', '[nu_demanda]', 'CAST([nu_demanda] AS NVARCHAR)', 3, -1, FALSE, '[nu_demanda]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_demanda->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_demanda'] = &$this->nu_demanda;

		// ic_tpArtefato
		$this->ic_tpArtefato = new cField('demanda_artefato', 'demanda_artefato', 'x_ic_tpArtefato', 'ic_tpArtefato', '[ic_tpArtefato]', '[ic_tpArtefato]', 129, -1, FALSE, '[ic_tpArtefato]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_tpArtefato'] = &$this->ic_tpArtefato;

		// no_local
		$this->no_local = new cField('demanda_artefato', 'demanda_artefato', 'x_no_local', 'no_local', '[no_local]', '[no_local]', 200, -1, FALSE, '[no_local]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_local'] = &$this->no_local;

		// im_anexo
		$this->im_anexo = new cField('demanda_artefato', 'demanda_artefato', 'x_im_anexo', 'im_anexo', '[im_anexo]', '[im_anexo]', 200, -1, FALSE, '[im_anexo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['im_anexo'] = &$this->im_anexo;

		// ic_situacao
		$this->ic_situacao = new cField('demanda_artefato', 'demanda_artefato', 'x_ic_situacao', 'ic_situacao', '[ic_situacao]', '[ic_situacao]', 129, -1, FALSE, '[ic_situacao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_situacao'] = &$this->ic_situacao;

		// nu_pessoaResp
		$this->nu_pessoaResp = new cField('demanda_artefato', 'demanda_artefato', 'x_nu_pessoaResp', 'nu_pessoaResp', '[nu_pessoaResp]', 'CAST([nu_pessoaResp] AS NVARCHAR)', 3, -1, FALSE, '[nu_pessoaResp]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_pessoaResp->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_pessoaResp'] = &$this->nu_pessoaResp;

		// nu_usuario
		$this->nu_usuario = new cField('demanda_artefato', 'demanda_artefato', 'x_nu_usuario', 'nu_usuario', '[nu_usuario]', 'CAST([nu_usuario] AS NVARCHAR)', 3, -1, FALSE, '[nu_usuario]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_usuario->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_usuario'] = &$this->nu_usuario;

		// ts_datahora
		$this->ts_datahora = new cField('demanda_artefato', 'demanda_artefato', 'x_ts_datahora', 'ts_datahora', '[ts_datahora]', '(REPLACE(STR(DAY([ts_datahora]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([ts_datahora]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([ts_datahora]),4,0))', 135, 7, FALSE, '[ts_datahora]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
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
		return "[dbo].[demanda_artefato]";
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
	var $UpdateTable = "[dbo].[demanda_artefato]";

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
			if (array_key_exists('nu_artefato', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_artefato') . '=' . ew_QuotedValue($rs['nu_artefato'], $this->nu_artefato->FldDataType));
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
		return "[nu_artefato] = @nu_artefato@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nu_artefato->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_artefato@", ew_AdjustSql($this->nu_artefato->CurrentValue), $sKeyFilter); // Replace key value
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
			return "demanda_artefatolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "demanda_artefatolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("demanda_artefatoview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("demanda_artefatoview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "demanda_artefatoadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("demanda_artefatoedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("demanda_artefatoadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("demanda_artefatodelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nu_artefato->CurrentValue)) {
			$sUrl .= "nu_artefato=" . urlencode($this->nu_artefato->CurrentValue);
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
			$arKeys[] = @$_GET["nu_artefato"]; // nu_artefato

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
			$this->nu_artefato->CurrentValue = $key;
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
		$this->nu_artefato->setDbValue($rs->fields('nu_artefato'));
		$this->nu_demanda->setDbValue($rs->fields('nu_demanda'));
		$this->ic_tpArtefato->setDbValue($rs->fields('ic_tpArtefato'));
		$this->no_local->setDbValue($rs->fields('no_local'));
		$this->im_anexo->setDbValue($rs->fields('im_anexo'));
		$this->ic_situacao->setDbValue($rs->fields('ic_situacao'));
		$this->nu_pessoaResp->setDbValue($rs->fields('nu_pessoaResp'));
		$this->nu_usuario->setDbValue($rs->fields('nu_usuario'));
		$this->ts_datahora->setDbValue($rs->fields('ts_datahora'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_artefato
		// nu_demanda
		// ic_tpArtefato
		// no_local
		// im_anexo
		// ic_situacao
		// nu_pessoaResp
		// nu_usuario
		// ts_datahora
		// nu_artefato

		$this->nu_artefato->ViewValue = $this->nu_artefato->CurrentValue;
		$this->nu_artefato->ViewCustomAttributes = "";

		// nu_demanda
		if (strval($this->nu_demanda->CurrentValue) <> "") {
			$sFilterWrk = "[nu_demanda]" . ew_SearchString("=", $this->nu_demanda->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_demanda], [nu_demanda] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[demanda]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_demanda, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_demanda->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_demanda->ViewValue = $this->nu_demanda->CurrentValue;
			}
		} else {
			$this->nu_demanda->ViewValue = NULL;
		}
		$this->nu_demanda->ViewCustomAttributes = "";

		// ic_tpArtefato
		if (strval($this->ic_tpArtefato->CurrentValue) <> "") {
			switch ($this->ic_tpArtefato->CurrentValue) {
				case $this->ic_tpArtefato->FldTagValue(1):
					$this->ic_tpArtefato->ViewValue = $this->ic_tpArtefato->FldTagCaption(1) <> "" ? $this->ic_tpArtefato->FldTagCaption(1) : $this->ic_tpArtefato->CurrentValue;
					break;
				case $this->ic_tpArtefato->FldTagValue(2):
					$this->ic_tpArtefato->ViewValue = $this->ic_tpArtefato->FldTagCaption(2) <> "" ? $this->ic_tpArtefato->FldTagCaption(2) : $this->ic_tpArtefato->CurrentValue;
					break;
				case $this->ic_tpArtefato->FldTagValue(3):
					$this->ic_tpArtefato->ViewValue = $this->ic_tpArtefato->FldTagCaption(3) <> "" ? $this->ic_tpArtefato->FldTagCaption(3) : $this->ic_tpArtefato->CurrentValue;
					break;
				case $this->ic_tpArtefato->FldTagValue(4):
					$this->ic_tpArtefato->ViewValue = $this->ic_tpArtefato->FldTagCaption(4) <> "" ? $this->ic_tpArtefato->FldTagCaption(4) : $this->ic_tpArtefato->CurrentValue;
					break;
				case $this->ic_tpArtefato->FldTagValue(5):
					$this->ic_tpArtefato->ViewValue = $this->ic_tpArtefato->FldTagCaption(5) <> "" ? $this->ic_tpArtefato->FldTagCaption(5) : $this->ic_tpArtefato->CurrentValue;
					break;
				case $this->ic_tpArtefato->FldTagValue(6):
					$this->ic_tpArtefato->ViewValue = $this->ic_tpArtefato->FldTagCaption(6) <> "" ? $this->ic_tpArtefato->FldTagCaption(6) : $this->ic_tpArtefato->CurrentValue;
					break;
				case $this->ic_tpArtefato->FldTagValue(7):
					$this->ic_tpArtefato->ViewValue = $this->ic_tpArtefato->FldTagCaption(7) <> "" ? $this->ic_tpArtefato->FldTagCaption(7) : $this->ic_tpArtefato->CurrentValue;
					break;
				case $this->ic_tpArtefato->FldTagValue(8):
					$this->ic_tpArtefato->ViewValue = $this->ic_tpArtefato->FldTagCaption(8) <> "" ? $this->ic_tpArtefato->FldTagCaption(8) : $this->ic_tpArtefato->CurrentValue;
					break;
				default:
					$this->ic_tpArtefato->ViewValue = $this->ic_tpArtefato->CurrentValue;
			}
		} else {
			$this->ic_tpArtefato->ViewValue = NULL;
		}
		$this->ic_tpArtefato->ViewCustomAttributes = "";

		// no_local
		$this->no_local->ViewValue = $this->no_local->CurrentValue;
		$this->no_local->ViewCustomAttributes = "";

		// im_anexo
		$this->im_anexo->ViewValue = $this->im_anexo->CurrentValue;
		$this->im_anexo->ViewCustomAttributes = "";

		// ic_situacao
		$this->ic_situacao->ViewValue = $this->ic_situacao->CurrentValue;
		$this->ic_situacao->ViewCustomAttributes = "";

		// nu_pessoaResp
		if (strval($this->nu_pessoaResp->CurrentValue) <> "") {
			$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_pessoaResp->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_pessoaResp, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_pessoaResp->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_pessoaResp->ViewValue = $this->nu_pessoaResp->CurrentValue;
			}
		} else {
			$this->nu_pessoaResp->ViewValue = NULL;
		}
		$this->nu_pessoaResp->ViewCustomAttributes = "";

		// nu_usuario
		$this->nu_usuario->ViewValue = $this->nu_usuario->CurrentValue;
		$this->nu_usuario->ViewCustomAttributes = "";

		// ts_datahora
		$this->ts_datahora->ViewValue = $this->ts_datahora->CurrentValue;
		$this->ts_datahora->ViewValue = ew_FormatDateTime($this->ts_datahora->ViewValue, 7);
		$this->ts_datahora->ViewCustomAttributes = "";

		// nu_artefato
		$this->nu_artefato->LinkCustomAttributes = "";
		$this->nu_artefato->HrefValue = "";
		$this->nu_artefato->TooltipValue = "";

		// nu_demanda
		$this->nu_demanda->LinkCustomAttributes = "";
		$this->nu_demanda->HrefValue = "";
		$this->nu_demanda->TooltipValue = "";

		// ic_tpArtefato
		$this->ic_tpArtefato->LinkCustomAttributes = "";
		$this->ic_tpArtefato->HrefValue = "";
		$this->ic_tpArtefato->TooltipValue = "";

		// no_local
		$this->no_local->LinkCustomAttributes = "";
		$this->no_local->HrefValue = "";
		$this->no_local->TooltipValue = "";

		// im_anexo
		$this->im_anexo->LinkCustomAttributes = "";
		$this->im_anexo->HrefValue = "";
		$this->im_anexo->TooltipValue = "";

		// ic_situacao
		$this->ic_situacao->LinkCustomAttributes = "";
		$this->ic_situacao->HrefValue = "";
		$this->ic_situacao->TooltipValue = "";

		// nu_pessoaResp
		$this->nu_pessoaResp->LinkCustomAttributes = "";
		$this->nu_pessoaResp->HrefValue = "";
		$this->nu_pessoaResp->TooltipValue = "";

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
				if ($this->nu_artefato->Exportable) $Doc->ExportCaption($this->nu_artefato);
				if ($this->nu_demanda->Exportable) $Doc->ExportCaption($this->nu_demanda);
				if ($this->ic_tpArtefato->Exportable) $Doc->ExportCaption($this->ic_tpArtefato);
				if ($this->no_local->Exportable) $Doc->ExportCaption($this->no_local);
				if ($this->im_anexo->Exportable) $Doc->ExportCaption($this->im_anexo);
				if ($this->ic_situacao->Exportable) $Doc->ExportCaption($this->ic_situacao);
				if ($this->nu_pessoaResp->Exportable) $Doc->ExportCaption($this->nu_pessoaResp);
				if ($this->nu_usuario->Exportable) $Doc->ExportCaption($this->nu_usuario);
				if ($this->ts_datahora->Exportable) $Doc->ExportCaption($this->ts_datahora);
			} else {
				if ($this->nu_artefato->Exportable) $Doc->ExportCaption($this->nu_artefato);
				if ($this->nu_demanda->Exportable) $Doc->ExportCaption($this->nu_demanda);
				if ($this->ic_tpArtefato->Exportable) $Doc->ExportCaption($this->ic_tpArtefato);
				if ($this->no_local->Exportable) $Doc->ExportCaption($this->no_local);
				if ($this->im_anexo->Exportable) $Doc->ExportCaption($this->im_anexo);
				if ($this->ic_situacao->Exportable) $Doc->ExportCaption($this->ic_situacao);
				if ($this->nu_pessoaResp->Exportable) $Doc->ExportCaption($this->nu_pessoaResp);
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
					if ($this->nu_artefato->Exportable) $Doc->ExportField($this->nu_artefato);
					if ($this->nu_demanda->Exportable) $Doc->ExportField($this->nu_demanda);
					if ($this->ic_tpArtefato->Exportable) $Doc->ExportField($this->ic_tpArtefato);
					if ($this->no_local->Exportable) $Doc->ExportField($this->no_local);
					if ($this->im_anexo->Exportable) $Doc->ExportField($this->im_anexo);
					if ($this->ic_situacao->Exportable) $Doc->ExportField($this->ic_situacao);
					if ($this->nu_pessoaResp->Exportable) $Doc->ExportField($this->nu_pessoaResp);
					if ($this->nu_usuario->Exportable) $Doc->ExportField($this->nu_usuario);
					if ($this->ts_datahora->Exportable) $Doc->ExportField($this->ts_datahora);
				} else {
					if ($this->nu_artefato->Exportable) $Doc->ExportField($this->nu_artefato);
					if ($this->nu_demanda->Exportable) $Doc->ExportField($this->nu_demanda);
					if ($this->ic_tpArtefato->Exportable) $Doc->ExportField($this->ic_tpArtefato);
					if ($this->no_local->Exportable) $Doc->ExportField($this->no_local);
					if ($this->im_anexo->Exportable) $Doc->ExportField($this->im_anexo);
					if ($this->ic_situacao->Exportable) $Doc->ExportField($this->ic_situacao);
					if ($this->nu_pessoaResp->Exportable) $Doc->ExportField($this->nu_pessoaResp);
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
