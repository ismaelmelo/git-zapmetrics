<?php

// Global variable for table object
$gpcomite = NULL;

//
// Table class for gpcomite
//
class cgpcomite extends cTable {
	var $nu_gpComite;
	var $no_gpComite;
	var $ic_tpGpOuComite;
	var $ds_descricao;
	var $ds_finalidade;
	var $ic_natureza;
	var $ds_competencias;
	var $ic_periodicidadeReunioes;
	var $dt_basePeriodicidade;
	var $no_localDocDiretrizes;
	var $im_anexoDiretrizes;
	var $no_localDocComunicacao;
	var $im_anexoComunicacao;
	var $no_localParecerJuridico;
	var $im_anexoParecerJuridico;
	var $no_localDocDesignacao;
	var $im_anexoDesignacao;
	var $ds_partesInteressadas;
	var $nu_usuario;
	var $ts_datahora;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'gpcomite';
		$this->TableName = 'gpcomite';
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

		// nu_gpComite
		$this->nu_gpComite = new cField('gpcomite', 'gpcomite', 'x_nu_gpComite', 'nu_gpComite', '[nu_gpComite]', 'CAST([nu_gpComite] AS NVARCHAR)', 3, -1, FALSE, '[nu_gpComite]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_gpComite->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_gpComite'] = &$this->nu_gpComite;

		// no_gpComite
		$this->no_gpComite = new cField('gpcomite', 'gpcomite', 'x_no_gpComite', 'no_gpComite', '[no_gpComite]', '[no_gpComite]', 200, -1, FALSE, '[no_gpComite]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_gpComite'] = &$this->no_gpComite;

		// ic_tpGpOuComite
		$this->ic_tpGpOuComite = new cField('gpcomite', 'gpcomite', 'x_ic_tpGpOuComite', 'ic_tpGpOuComite', '[ic_tpGpOuComite]', '[ic_tpGpOuComite]', 129, -1, FALSE, '[ic_tpGpOuComite]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_tpGpOuComite'] = &$this->ic_tpGpOuComite;

		// ds_descricao
		$this->ds_descricao = new cField('gpcomite', 'gpcomite', 'x_ds_descricao', 'ds_descricao', '[ds_descricao]', '[ds_descricao]', 201, -1, FALSE, '[ds_descricao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_descricao'] = &$this->ds_descricao;

		// ds_finalidade
		$this->ds_finalidade = new cField('gpcomite', 'gpcomite', 'x_ds_finalidade', 'ds_finalidade', '[ds_finalidade]', '[ds_finalidade]', 201, -1, FALSE, '[ds_finalidade]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_finalidade'] = &$this->ds_finalidade;

		// ic_natureza
		$this->ic_natureza = new cField('gpcomite', 'gpcomite', 'x_ic_natureza', 'ic_natureza', '[ic_natureza]', '[ic_natureza]', 129, -1, FALSE, '[ic_natureza]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_natureza'] = &$this->ic_natureza;

		// ds_competencias
		$this->ds_competencias = new cField('gpcomite', 'gpcomite', 'x_ds_competencias', 'ds_competencias', '[ds_competencias]', '[ds_competencias]', 201, -1, FALSE, '[ds_competencias]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_competencias'] = &$this->ds_competencias;

		// ic_periodicidadeReunioes
		$this->ic_periodicidadeReunioes = new cField('gpcomite', 'gpcomite', 'x_ic_periodicidadeReunioes', 'ic_periodicidadeReunioes', '[ic_periodicidadeReunioes]', '[ic_periodicidadeReunioes]', 129, -1, FALSE, '[ic_periodicidadeReunioes]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_periodicidadeReunioes'] = &$this->ic_periodicidadeReunioes;

		// dt_basePeriodicidade
		$this->dt_basePeriodicidade = new cField('gpcomite', 'gpcomite', 'x_dt_basePeriodicidade', 'dt_basePeriodicidade', '[dt_basePeriodicidade]', '(REPLACE(STR(DAY([dt_basePeriodicidade]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dt_basePeriodicidade]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dt_basePeriodicidade]),4,0))', 135, 7, FALSE, '[dt_basePeriodicidade]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dt_basePeriodicidade->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dt_basePeriodicidade'] = &$this->dt_basePeriodicidade;

		// no_localDocDiretrizes
		$this->no_localDocDiretrizes = new cField('gpcomite', 'gpcomite', 'x_no_localDocDiretrizes', 'no_localDocDiretrizes', '[no_localDocDiretrizes]', '[no_localDocDiretrizes]', 200, -1, FALSE, '[no_localDocDiretrizes]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_localDocDiretrizes'] = &$this->no_localDocDiretrizes;

		// im_anexoDiretrizes
		$this->im_anexoDiretrizes = new cField('gpcomite', 'gpcomite', 'x_im_anexoDiretrizes', 'im_anexoDiretrizes', '[im_anexoDiretrizes]', '[im_anexoDiretrizes]', 200, -1, TRUE, '[im_anexoDiretrizes]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->im_anexoDiretrizes->UploadMultiple = TRUE;
		$this->fields['im_anexoDiretrizes'] = &$this->im_anexoDiretrizes;

		// no_localDocComunicacao
		$this->no_localDocComunicacao = new cField('gpcomite', 'gpcomite', 'x_no_localDocComunicacao', 'no_localDocComunicacao', '[no_localDocComunicacao]', '[no_localDocComunicacao]', 200, -1, FALSE, '[no_localDocComunicacao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_localDocComunicacao'] = &$this->no_localDocComunicacao;

		// im_anexoComunicacao
		$this->im_anexoComunicacao = new cField('gpcomite', 'gpcomite', 'x_im_anexoComunicacao', 'im_anexoComunicacao', '[im_anexoComunicacao]', '[im_anexoComunicacao]', 200, -1, TRUE, '[im_anexoComunicacao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->im_anexoComunicacao->UploadMultiple = TRUE;
		$this->fields['im_anexoComunicacao'] = &$this->im_anexoComunicacao;

		// no_localParecerJuridico
		$this->no_localParecerJuridico = new cField('gpcomite', 'gpcomite', 'x_no_localParecerJuridico', 'no_localParecerJuridico', '[no_localParecerJuridico]', '[no_localParecerJuridico]', 200, -1, FALSE, '[no_localParecerJuridico]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_localParecerJuridico'] = &$this->no_localParecerJuridico;

		// im_anexoParecerJuridico
		$this->im_anexoParecerJuridico = new cField('gpcomite', 'gpcomite', 'x_im_anexoParecerJuridico', 'im_anexoParecerJuridico', '[im_anexoParecerJuridico]', '[im_anexoParecerJuridico]', 200, -1, TRUE, '[im_anexoParecerJuridico]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->im_anexoParecerJuridico->UploadMultiple = TRUE;
		$this->fields['im_anexoParecerJuridico'] = &$this->im_anexoParecerJuridico;

		// no_localDocDesignacao
		$this->no_localDocDesignacao = new cField('gpcomite', 'gpcomite', 'x_no_localDocDesignacao', 'no_localDocDesignacao', '[no_localDocDesignacao]', '[no_localDocDesignacao]', 200, -1, FALSE, '[no_localDocDesignacao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_localDocDesignacao'] = &$this->no_localDocDesignacao;

		// im_anexoDesignacao
		$this->im_anexoDesignacao = new cField('gpcomite', 'gpcomite', 'x_im_anexoDesignacao', 'im_anexoDesignacao', '[im_anexoDesignacao]', '[im_anexoDesignacao]', 200, -1, TRUE, '[im_anexoDesignacao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->im_anexoDesignacao->UploadMultiple = TRUE;
		$this->fields['im_anexoDesignacao'] = &$this->im_anexoDesignacao;

		// ds_partesInteressadas
		$this->ds_partesInteressadas = new cField('gpcomite', 'gpcomite', 'x_ds_partesInteressadas', 'ds_partesInteressadas', '[ds_partesInteressadas]', '[ds_partesInteressadas]', 201, -1, FALSE, '[ds_partesInteressadas]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_partesInteressadas'] = &$this->ds_partesInteressadas;

		// nu_usuario
		$this->nu_usuario = new cField('gpcomite', 'gpcomite', 'x_nu_usuario', 'nu_usuario', '[nu_usuario]', 'CAST([nu_usuario] AS NVARCHAR)', 3, -1, FALSE, '[nu_usuario]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_usuario->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_usuario'] = &$this->nu_usuario;

		// ts_datahora
		$this->ts_datahora = new cField('gpcomite', 'gpcomite', 'x_ts_datahora', 'ts_datahora', '[ts_datahora]', '(REPLACE(STR(DAY([ts_datahora]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([ts_datahora]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([ts_datahora]),4,0))', 135, 7, FALSE, '[ts_datahora]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
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
		return "[dbo].[gpcomite]";
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
	var $UpdateTable = "[dbo].[gpcomite]";

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
			if (array_key_exists('nu_gpComite', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_gpComite') . '=' . ew_QuotedValue($rs['nu_gpComite'], $this->nu_gpComite->FldDataType));
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
		return "[nu_gpComite] = @nu_gpComite@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nu_gpComite->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_gpComite@", ew_AdjustSql($this->nu_gpComite->CurrentValue), $sKeyFilter); // Replace key value
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
			return "gpcomitelist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "gpcomitelist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("gpcomiteview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("gpcomiteview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "gpcomiteadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("gpcomiteedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("gpcomiteadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("gpcomitedelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nu_gpComite->CurrentValue)) {
			$sUrl .= "nu_gpComite=" . urlencode($this->nu_gpComite->CurrentValue);
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
			$arKeys[] = @$_GET["nu_gpComite"]; // nu_gpComite

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
			$this->nu_gpComite->CurrentValue = $key;
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
		$this->nu_gpComite->setDbValue($rs->fields('nu_gpComite'));
		$this->no_gpComite->setDbValue($rs->fields('no_gpComite'));
		$this->ic_tpGpOuComite->setDbValue($rs->fields('ic_tpGpOuComite'));
		$this->ds_descricao->setDbValue($rs->fields('ds_descricao'));
		$this->ds_finalidade->setDbValue($rs->fields('ds_finalidade'));
		$this->ic_natureza->setDbValue($rs->fields('ic_natureza'));
		$this->ds_competencias->setDbValue($rs->fields('ds_competencias'));
		$this->ic_periodicidadeReunioes->setDbValue($rs->fields('ic_periodicidadeReunioes'));
		$this->dt_basePeriodicidade->setDbValue($rs->fields('dt_basePeriodicidade'));
		$this->no_localDocDiretrizes->setDbValue($rs->fields('no_localDocDiretrizes'));
		$this->im_anexoDiretrizes->Upload->DbValue = $rs->fields('im_anexoDiretrizes');
		$this->no_localDocComunicacao->setDbValue($rs->fields('no_localDocComunicacao'));
		$this->im_anexoComunicacao->Upload->DbValue = $rs->fields('im_anexoComunicacao');
		$this->no_localParecerJuridico->setDbValue($rs->fields('no_localParecerJuridico'));
		$this->im_anexoParecerJuridico->Upload->DbValue = $rs->fields('im_anexoParecerJuridico');
		$this->no_localDocDesignacao->setDbValue($rs->fields('no_localDocDesignacao'));
		$this->im_anexoDesignacao->Upload->DbValue = $rs->fields('im_anexoDesignacao');
		$this->ds_partesInteressadas->setDbValue($rs->fields('ds_partesInteressadas'));
		$this->nu_usuario->setDbValue($rs->fields('nu_usuario'));
		$this->ts_datahora->setDbValue($rs->fields('ts_datahora'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_gpComite
		// no_gpComite
		// ic_tpGpOuComite
		// ds_descricao
		// ds_finalidade
		// ic_natureza
		// ds_competencias
		// ic_periodicidadeReunioes
		// dt_basePeriodicidade
		// no_localDocDiretrizes
		// im_anexoDiretrizes
		// no_localDocComunicacao
		// im_anexoComunicacao
		// no_localParecerJuridico
		// im_anexoParecerJuridico
		// no_localDocDesignacao
		// im_anexoDesignacao
		// ds_partesInteressadas
		// nu_usuario
		// ts_datahora
		// nu_gpComite

		$this->nu_gpComite->ViewValue = $this->nu_gpComite->CurrentValue;
		$this->nu_gpComite->ViewCustomAttributes = "";

		// no_gpComite
		$this->no_gpComite->ViewValue = $this->no_gpComite->CurrentValue;
		$this->no_gpComite->ViewCustomAttributes = "";

		// ic_tpGpOuComite
		if (strval($this->ic_tpGpOuComite->CurrentValue) <> "") {
			switch ($this->ic_tpGpOuComite->CurrentValue) {
				case $this->ic_tpGpOuComite->FldTagValue(1):
					$this->ic_tpGpOuComite->ViewValue = $this->ic_tpGpOuComite->FldTagCaption(1) <> "" ? $this->ic_tpGpOuComite->FldTagCaption(1) : $this->ic_tpGpOuComite->CurrentValue;
					break;
				case $this->ic_tpGpOuComite->FldTagValue(2):
					$this->ic_tpGpOuComite->ViewValue = $this->ic_tpGpOuComite->FldTagCaption(2) <> "" ? $this->ic_tpGpOuComite->FldTagCaption(2) : $this->ic_tpGpOuComite->CurrentValue;
					break;
				case $this->ic_tpGpOuComite->FldTagValue(3):
					$this->ic_tpGpOuComite->ViewValue = $this->ic_tpGpOuComite->FldTagCaption(3) <> "" ? $this->ic_tpGpOuComite->FldTagCaption(3) : $this->ic_tpGpOuComite->CurrentValue;
					break;
				default:
					$this->ic_tpGpOuComite->ViewValue = $this->ic_tpGpOuComite->CurrentValue;
			}
		} else {
			$this->ic_tpGpOuComite->ViewValue = NULL;
		}
		$this->ic_tpGpOuComite->ViewCustomAttributes = "";

		// ds_descricao
		$this->ds_descricao->ViewValue = $this->ds_descricao->CurrentValue;
		$this->ds_descricao->ViewCustomAttributes = "";

		// ds_finalidade
		$this->ds_finalidade->ViewValue = $this->ds_finalidade->CurrentValue;
		$this->ds_finalidade->ViewCustomAttributes = "";

		// ic_natureza
		if (strval($this->ic_natureza->CurrentValue) <> "") {
			switch ($this->ic_natureza->CurrentValue) {
				case $this->ic_natureza->FldTagValue(1):
					$this->ic_natureza->ViewValue = $this->ic_natureza->FldTagCaption(1) <> "" ? $this->ic_natureza->FldTagCaption(1) : $this->ic_natureza->CurrentValue;
					break;
				case $this->ic_natureza->FldTagValue(2):
					$this->ic_natureza->ViewValue = $this->ic_natureza->FldTagCaption(2) <> "" ? $this->ic_natureza->FldTagCaption(2) : $this->ic_natureza->CurrentValue;
					break;
				default:
					$this->ic_natureza->ViewValue = $this->ic_natureza->CurrentValue;
			}
		} else {
			$this->ic_natureza->ViewValue = NULL;
		}
		$this->ic_natureza->ViewCustomAttributes = "";

		// ds_competencias
		$this->ds_competencias->ViewValue = $this->ds_competencias->CurrentValue;
		$this->ds_competencias->ViewCustomAttributes = "";

		// ic_periodicidadeReunioes
		if (strval($this->ic_periodicidadeReunioes->CurrentValue) <> "") {
			switch ($this->ic_periodicidadeReunioes->CurrentValue) {
				case $this->ic_periodicidadeReunioes->FldTagValue(1):
					$this->ic_periodicidadeReunioes->ViewValue = $this->ic_periodicidadeReunioes->FldTagCaption(1) <> "" ? $this->ic_periodicidadeReunioes->FldTagCaption(1) : $this->ic_periodicidadeReunioes->CurrentValue;
					break;
				case $this->ic_periodicidadeReunioes->FldTagValue(2):
					$this->ic_periodicidadeReunioes->ViewValue = $this->ic_periodicidadeReunioes->FldTagCaption(2) <> "" ? $this->ic_periodicidadeReunioes->FldTagCaption(2) : $this->ic_periodicidadeReunioes->CurrentValue;
					break;
				case $this->ic_periodicidadeReunioes->FldTagValue(3):
					$this->ic_periodicidadeReunioes->ViewValue = $this->ic_periodicidadeReunioes->FldTagCaption(3) <> "" ? $this->ic_periodicidadeReunioes->FldTagCaption(3) : $this->ic_periodicidadeReunioes->CurrentValue;
					break;
				case $this->ic_periodicidadeReunioes->FldTagValue(4):
					$this->ic_periodicidadeReunioes->ViewValue = $this->ic_periodicidadeReunioes->FldTagCaption(4) <> "" ? $this->ic_periodicidadeReunioes->FldTagCaption(4) : $this->ic_periodicidadeReunioes->CurrentValue;
					break;
				case $this->ic_periodicidadeReunioes->FldTagValue(5):
					$this->ic_periodicidadeReunioes->ViewValue = $this->ic_periodicidadeReunioes->FldTagCaption(5) <> "" ? $this->ic_periodicidadeReunioes->FldTagCaption(5) : $this->ic_periodicidadeReunioes->CurrentValue;
					break;
				case $this->ic_periodicidadeReunioes->FldTagValue(6):
					$this->ic_periodicidadeReunioes->ViewValue = $this->ic_periodicidadeReunioes->FldTagCaption(6) <> "" ? $this->ic_periodicidadeReunioes->FldTagCaption(6) : $this->ic_periodicidadeReunioes->CurrentValue;
					break;
				default:
					$this->ic_periodicidadeReunioes->ViewValue = $this->ic_periodicidadeReunioes->CurrentValue;
			}
		} else {
			$this->ic_periodicidadeReunioes->ViewValue = NULL;
		}
		$this->ic_periodicidadeReunioes->ViewCustomAttributes = "";

		// dt_basePeriodicidade
		$this->dt_basePeriodicidade->ViewValue = $this->dt_basePeriodicidade->CurrentValue;
		$this->dt_basePeriodicidade->ViewValue = ew_FormatDateTime($this->dt_basePeriodicidade->ViewValue, 7);
		$this->dt_basePeriodicidade->ViewCustomAttributes = "";

		// no_localDocDiretrizes
		$this->no_localDocDiretrizes->ViewValue = $this->no_localDocDiretrizes->CurrentValue;
		$this->no_localDocDiretrizes->ViewCustomAttributes = "";

		// im_anexoDiretrizes
		$this->im_anexoDiretrizes->UploadPath = "arquivos/grupocti_diretrizes";
		if (!ew_Empty($this->im_anexoDiretrizes->Upload->DbValue)) {
			$this->im_anexoDiretrizes->ViewValue = $this->im_anexoDiretrizes->Upload->DbValue;
		} else {
			$this->im_anexoDiretrizes->ViewValue = "";
		}
		$this->im_anexoDiretrizes->ViewCustomAttributes = "";

		// no_localDocComunicacao
		$this->no_localDocComunicacao->ViewValue = $this->no_localDocComunicacao->CurrentValue;
		$this->no_localDocComunicacao->ViewCustomAttributes = "";

		// im_anexoComunicacao
		$this->im_anexoComunicacao->UploadPath = "arquivos/grupocti_comunicacao";
		if (!ew_Empty($this->im_anexoComunicacao->Upload->DbValue)) {
			$this->im_anexoComunicacao->ViewValue = $this->im_anexoComunicacao->Upload->DbValue;
		} else {
			$this->im_anexoComunicacao->ViewValue = "";
		}
		$this->im_anexoComunicacao->ViewCustomAttributes = "";

		// no_localParecerJuridico
		$this->no_localParecerJuridico->ViewValue = $this->no_localParecerJuridico->CurrentValue;
		$this->no_localParecerJuridico->ViewCustomAttributes = "";

		// im_anexoParecerJuridico
		$this->im_anexoParecerJuridico->UploadPath = "arquivos/grupocti_parjuridico";
		if (!ew_Empty($this->im_anexoParecerJuridico->Upload->DbValue)) {
			$this->im_anexoParecerJuridico->ViewValue = $this->im_anexoParecerJuridico->Upload->DbValue;
		} else {
			$this->im_anexoParecerJuridico->ViewValue = "";
		}
		$this->im_anexoParecerJuridico->ViewCustomAttributes = "";

		// no_localDocDesignacao
		$this->no_localDocDesignacao->ViewValue = $this->no_localDocDesignacao->CurrentValue;
		$this->no_localDocDesignacao->ViewCustomAttributes = "";

		// im_anexoDesignacao
		$this->im_anexoDesignacao->UploadPath = "arquivos/grupocti_designacao";
		if (!ew_Empty($this->im_anexoDesignacao->Upload->DbValue)) {
			$this->im_anexoDesignacao->ViewValue = $this->im_anexoDesignacao->Upload->DbValue;
		} else {
			$this->im_anexoDesignacao->ViewValue = "";
		}
		$this->im_anexoDesignacao->ViewCustomAttributes = "";

		// ds_partesInteressadas
		$this->ds_partesInteressadas->ViewValue = $this->ds_partesInteressadas->CurrentValue;
		$this->ds_partesInteressadas->ViewCustomAttributes = "";

		// nu_usuario
		$this->nu_usuario->ViewValue = $this->nu_usuario->CurrentValue;
		$this->nu_usuario->ViewCustomAttributes = "";

		// ts_datahora
		$this->ts_datahora->ViewValue = $this->ts_datahora->CurrentValue;
		$this->ts_datahora->ViewValue = ew_FormatDateTime($this->ts_datahora->ViewValue, 7);
		$this->ts_datahora->ViewCustomAttributes = "";

		// nu_gpComite
		$this->nu_gpComite->LinkCustomAttributes = "";
		$this->nu_gpComite->HrefValue = "";
		$this->nu_gpComite->TooltipValue = "";

		// no_gpComite
		$this->no_gpComite->LinkCustomAttributes = "";
		$this->no_gpComite->HrefValue = "";
		$this->no_gpComite->TooltipValue = "";

		// ic_tpGpOuComite
		$this->ic_tpGpOuComite->LinkCustomAttributes = "";
		$this->ic_tpGpOuComite->HrefValue = "";
		$this->ic_tpGpOuComite->TooltipValue = "";

		// ds_descricao
		$this->ds_descricao->LinkCustomAttributes = "";
		$this->ds_descricao->HrefValue = "";
		$this->ds_descricao->TooltipValue = "";

		// ds_finalidade
		$this->ds_finalidade->LinkCustomAttributes = "";
		$this->ds_finalidade->HrefValue = "";
		$this->ds_finalidade->TooltipValue = "";

		// ic_natureza
		$this->ic_natureza->LinkCustomAttributes = "";
		$this->ic_natureza->HrefValue = "";
		$this->ic_natureza->TooltipValue = "";

		// ds_competencias
		$this->ds_competencias->LinkCustomAttributes = "";
		$this->ds_competencias->HrefValue = "";
		$this->ds_competencias->TooltipValue = "";

		// ic_periodicidadeReunioes
		$this->ic_periodicidadeReunioes->LinkCustomAttributes = "";
		$this->ic_periodicidadeReunioes->HrefValue = "";
		$this->ic_periodicidadeReunioes->TooltipValue = "";

		// dt_basePeriodicidade
		$this->dt_basePeriodicidade->LinkCustomAttributes = "";
		$this->dt_basePeriodicidade->HrefValue = "";
		$this->dt_basePeriodicidade->TooltipValue = "";

		// no_localDocDiretrizes
		$this->no_localDocDiretrizes->LinkCustomAttributes = "";
		$this->no_localDocDiretrizes->HrefValue = "";
		$this->no_localDocDiretrizes->TooltipValue = "";

		// im_anexoDiretrizes
		$this->im_anexoDiretrizes->LinkCustomAttributes = "";
		$this->im_anexoDiretrizes->HrefValue = "";
		$this->im_anexoDiretrizes->HrefValue2 = $this->im_anexoDiretrizes->UploadPath . $this->im_anexoDiretrizes->Upload->DbValue;
		$this->im_anexoDiretrizes->TooltipValue = "";

		// no_localDocComunicacao
		$this->no_localDocComunicacao->LinkCustomAttributes = "";
		$this->no_localDocComunicacao->HrefValue = "";
		$this->no_localDocComunicacao->TooltipValue = "";

		// im_anexoComunicacao
		$this->im_anexoComunicacao->LinkCustomAttributes = "";
		$this->im_anexoComunicacao->HrefValue = "";
		$this->im_anexoComunicacao->HrefValue2 = $this->im_anexoComunicacao->UploadPath . $this->im_anexoComunicacao->Upload->DbValue;
		$this->im_anexoComunicacao->TooltipValue = "";

		// no_localParecerJuridico
		$this->no_localParecerJuridico->LinkCustomAttributes = "";
		$this->no_localParecerJuridico->HrefValue = "";
		$this->no_localParecerJuridico->TooltipValue = "";

		// im_anexoParecerJuridico
		$this->im_anexoParecerJuridico->LinkCustomAttributes = "";
		$this->im_anexoParecerJuridico->HrefValue = "";
		$this->im_anexoParecerJuridico->HrefValue2 = $this->im_anexoParecerJuridico->UploadPath . $this->im_anexoParecerJuridico->Upload->DbValue;
		$this->im_anexoParecerJuridico->TooltipValue = "";

		// no_localDocDesignacao
		$this->no_localDocDesignacao->LinkCustomAttributes = "";
		$this->no_localDocDesignacao->HrefValue = "";
		$this->no_localDocDesignacao->TooltipValue = "";

		// im_anexoDesignacao
		$this->im_anexoDesignacao->LinkCustomAttributes = "";
		$this->im_anexoDesignacao->HrefValue = "";
		$this->im_anexoDesignacao->HrefValue2 = $this->im_anexoDesignacao->UploadPath . $this->im_anexoDesignacao->Upload->DbValue;
		$this->im_anexoDesignacao->TooltipValue = "";

		// ds_partesInteressadas
		$this->ds_partesInteressadas->LinkCustomAttributes = "";
		$this->ds_partesInteressadas->HrefValue = "";
		$this->ds_partesInteressadas->TooltipValue = "";

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
				if ($this->nu_gpComite->Exportable) $Doc->ExportCaption($this->nu_gpComite);
				if ($this->no_gpComite->Exportable) $Doc->ExportCaption($this->no_gpComite);
				if ($this->ic_tpGpOuComite->Exportable) $Doc->ExportCaption($this->ic_tpGpOuComite);
				if ($this->ds_descricao->Exportable) $Doc->ExportCaption($this->ds_descricao);
				if ($this->ds_finalidade->Exportable) $Doc->ExportCaption($this->ds_finalidade);
				if ($this->ic_natureza->Exportable) $Doc->ExportCaption($this->ic_natureza);
				if ($this->ds_competencias->Exportable) $Doc->ExportCaption($this->ds_competencias);
				if ($this->ic_periodicidadeReunioes->Exportable) $Doc->ExportCaption($this->ic_periodicidadeReunioes);
				if ($this->dt_basePeriodicidade->Exportable) $Doc->ExportCaption($this->dt_basePeriodicidade);
				if ($this->no_localDocDiretrizes->Exportable) $Doc->ExportCaption($this->no_localDocDiretrizes);
				if ($this->im_anexoDiretrizes->Exportable) $Doc->ExportCaption($this->im_anexoDiretrizes);
				if ($this->no_localDocComunicacao->Exportable) $Doc->ExportCaption($this->no_localDocComunicacao);
				if ($this->im_anexoComunicacao->Exportable) $Doc->ExportCaption($this->im_anexoComunicacao);
				if ($this->no_localParecerJuridico->Exportable) $Doc->ExportCaption($this->no_localParecerJuridico);
				if ($this->im_anexoParecerJuridico->Exportable) $Doc->ExportCaption($this->im_anexoParecerJuridico);
				if ($this->no_localDocDesignacao->Exportable) $Doc->ExportCaption($this->no_localDocDesignacao);
				if ($this->im_anexoDesignacao->Exportable) $Doc->ExportCaption($this->im_anexoDesignacao);
				if ($this->ds_partesInteressadas->Exportable) $Doc->ExportCaption($this->ds_partesInteressadas);
				if ($this->nu_usuario->Exportable) $Doc->ExportCaption($this->nu_usuario);
				if ($this->ts_datahora->Exportable) $Doc->ExportCaption($this->ts_datahora);
			} else {
				if ($this->nu_gpComite->Exportable) $Doc->ExportCaption($this->nu_gpComite);
				if ($this->no_gpComite->Exportable) $Doc->ExportCaption($this->no_gpComite);
				if ($this->ic_tpGpOuComite->Exportable) $Doc->ExportCaption($this->ic_tpGpOuComite);
				if ($this->ic_natureza->Exportable) $Doc->ExportCaption($this->ic_natureza);
				if ($this->ic_periodicidadeReunioes->Exportable) $Doc->ExportCaption($this->ic_periodicidadeReunioes);
				if ($this->dt_basePeriodicidade->Exportable) $Doc->ExportCaption($this->dt_basePeriodicidade);
				if ($this->no_localDocDiretrizes->Exportable) $Doc->ExportCaption($this->no_localDocDiretrizes);
				if ($this->im_anexoDiretrizes->Exportable) $Doc->ExportCaption($this->im_anexoDiretrizes);
				if ($this->no_localDocComunicacao->Exportable) $Doc->ExportCaption($this->no_localDocComunicacao);
				if ($this->im_anexoComunicacao->Exportable) $Doc->ExportCaption($this->im_anexoComunicacao);
				if ($this->no_localParecerJuridico->Exportable) $Doc->ExportCaption($this->no_localParecerJuridico);
				if ($this->im_anexoParecerJuridico->Exportable) $Doc->ExportCaption($this->im_anexoParecerJuridico);
				if ($this->no_localDocDesignacao->Exportable) $Doc->ExportCaption($this->no_localDocDesignacao);
				if ($this->im_anexoDesignacao->Exportable) $Doc->ExportCaption($this->im_anexoDesignacao);
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
					if ($this->nu_gpComite->Exportable) $Doc->ExportField($this->nu_gpComite);
					if ($this->no_gpComite->Exportable) $Doc->ExportField($this->no_gpComite);
					if ($this->ic_tpGpOuComite->Exportable) $Doc->ExportField($this->ic_tpGpOuComite);
					if ($this->ds_descricao->Exportable) $Doc->ExportField($this->ds_descricao);
					if ($this->ds_finalidade->Exportable) $Doc->ExportField($this->ds_finalidade);
					if ($this->ic_natureza->Exportable) $Doc->ExportField($this->ic_natureza);
					if ($this->ds_competencias->Exportable) $Doc->ExportField($this->ds_competencias);
					if ($this->ic_periodicidadeReunioes->Exportable) $Doc->ExportField($this->ic_periodicidadeReunioes);
					if ($this->dt_basePeriodicidade->Exportable) $Doc->ExportField($this->dt_basePeriodicidade);
					if ($this->no_localDocDiretrizes->Exportable) $Doc->ExportField($this->no_localDocDiretrizes);
					if ($this->im_anexoDiretrizes->Exportable) $Doc->ExportField($this->im_anexoDiretrizes);
					if ($this->no_localDocComunicacao->Exportable) $Doc->ExportField($this->no_localDocComunicacao);
					if ($this->im_anexoComunicacao->Exportable) $Doc->ExportField($this->im_anexoComunicacao);
					if ($this->no_localParecerJuridico->Exportable) $Doc->ExportField($this->no_localParecerJuridico);
					if ($this->im_anexoParecerJuridico->Exportable) $Doc->ExportField($this->im_anexoParecerJuridico);
					if ($this->no_localDocDesignacao->Exportable) $Doc->ExportField($this->no_localDocDesignacao);
					if ($this->im_anexoDesignacao->Exportable) $Doc->ExportField($this->im_anexoDesignacao);
					if ($this->ds_partesInteressadas->Exportable) $Doc->ExportField($this->ds_partesInteressadas);
					if ($this->nu_usuario->Exportable) $Doc->ExportField($this->nu_usuario);
					if ($this->ts_datahora->Exportable) $Doc->ExportField($this->ts_datahora);
				} else {
					if ($this->nu_gpComite->Exportable) $Doc->ExportField($this->nu_gpComite);
					if ($this->no_gpComite->Exportable) $Doc->ExportField($this->no_gpComite);
					if ($this->ic_tpGpOuComite->Exportable) $Doc->ExportField($this->ic_tpGpOuComite);
					if ($this->ic_natureza->Exportable) $Doc->ExportField($this->ic_natureza);
					if ($this->ic_periodicidadeReunioes->Exportable) $Doc->ExportField($this->ic_periodicidadeReunioes);
					if ($this->dt_basePeriodicidade->Exportable) $Doc->ExportField($this->dt_basePeriodicidade);
					if ($this->no_localDocDiretrizes->Exportable) $Doc->ExportField($this->no_localDocDiretrizes);
					if ($this->im_anexoDiretrizes->Exportable) $Doc->ExportField($this->im_anexoDiretrizes);
					if ($this->no_localDocComunicacao->Exportable) $Doc->ExportField($this->no_localDocComunicacao);
					if ($this->im_anexoComunicacao->Exportable) $Doc->ExportField($this->im_anexoComunicacao);
					if ($this->no_localParecerJuridico->Exportable) $Doc->ExportField($this->no_localParecerJuridico);
					if ($this->im_anexoParecerJuridico->Exportable) $Doc->ExportField($this->im_anexoParecerJuridico);
					if ($this->no_localDocDesignacao->Exportable) $Doc->ExportField($this->no_localDocDesignacao);
					if ($this->im_anexoDesignacao->Exportable) $Doc->ExportField($this->im_anexoDesignacao);
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
