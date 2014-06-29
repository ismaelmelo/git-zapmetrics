<?php

// Global variable for table object
$prodambiente = NULL;

//
// Table class for prodambiente
//
class cprodambiente extends cTable {
	var $nu_ambiente;
	var $no_ambiente;
	var $ic_tpAtualizacao;
	var $ic_metCalibracao;
	var $nu_usuarioResp;
	var $qt_linhasCodLingPf;
	var $vr_ipMin;
	var $vr_ipMed;
	var $vr_ipMax;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'prodambiente';
		$this->TableName = 'prodambiente';
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

		// nu_ambiente
		$this->nu_ambiente = new cField('prodambiente', 'prodambiente', 'x_nu_ambiente', 'nu_ambiente', '[nu_ambiente]', 'CAST([nu_ambiente] AS NVARCHAR)', 3, -1, FALSE, '[nu_ambiente]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_ambiente->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_ambiente'] = &$this->nu_ambiente;

		// no_ambiente
		$this->no_ambiente = new cField('prodambiente', 'prodambiente', 'x_no_ambiente', 'no_ambiente', '[no_ambiente]', '[no_ambiente]', 200, -1, FALSE, '[no_ambiente]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_ambiente'] = &$this->no_ambiente;

		// ic_tpAtualizacao
		$this->ic_tpAtualizacao = new cField('prodambiente', 'prodambiente', 'x_ic_tpAtualizacao', 'ic_tpAtualizacao', '[ic_tpAtualizacao]', '[ic_tpAtualizacao]', 129, -1, FALSE, '[ic_tpAtualizacao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_tpAtualizacao'] = &$this->ic_tpAtualizacao;

		// ic_metCalibracao
		$this->ic_metCalibracao = new cField('prodambiente', 'prodambiente', 'x_ic_metCalibracao', 'ic_metCalibracao', '[ic_metCalibracao]', '[ic_metCalibracao]', 129, -1, FALSE, '[ic_metCalibracao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_metCalibracao'] = &$this->ic_metCalibracao;

		// nu_usuarioResp
		$this->nu_usuarioResp = new cField('prodambiente', 'prodambiente', 'x_nu_usuarioResp', 'nu_usuarioResp', '[nu_usuarioResp]', 'CAST([nu_usuarioResp] AS NVARCHAR)', 3, -1, FALSE, '[nu_usuarioResp]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_usuarioResp->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_usuarioResp'] = &$this->nu_usuarioResp;

		// qt_linhasCodLingPf
		$this->qt_linhasCodLingPf = new cField('prodambiente', 'prodambiente', 'x_qt_linhasCodLingPf', 'qt_linhasCodLingPf', '[qt_linhasCodLingPf]', 'CAST([qt_linhasCodLingPf] AS NVARCHAR)', 3, -1, FALSE, '[qt_linhasCodLingPf]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->qt_linhasCodLingPf->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['qt_linhasCodLingPf'] = &$this->qt_linhasCodLingPf;

		// vr_ipMin
		$this->vr_ipMin = new cField('prodambiente', 'prodambiente', 'x_vr_ipMin', 'vr_ipMin', '[vr_ipMin]', 'CAST([vr_ipMin] AS NVARCHAR)', 131, -1, FALSE, '[vr_ipMin]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->vr_ipMin->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['vr_ipMin'] = &$this->vr_ipMin;

		// vr_ipMed
		$this->vr_ipMed = new cField('prodambiente', 'prodambiente', 'x_vr_ipMed', 'vr_ipMed', '[vr_ipMed]', 'CAST([vr_ipMed] AS NVARCHAR)', 131, -1, FALSE, '[vr_ipMed]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->vr_ipMed->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['vr_ipMed'] = &$this->vr_ipMed;

		// vr_ipMax
		$this->vr_ipMax = new cField('prodambiente', 'prodambiente', 'x_vr_ipMax', 'vr_ipMax', '[vr_ipMax]', 'CAST([vr_ipMax] AS NVARCHAR)', 131, -1, FALSE, '[vr_ipMax]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->vr_ipMax->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['vr_ipMax'] = &$this->vr_ipMax;
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
		return "[db_owner].[prodambiente]";
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
	var $UpdateTable = "[db_owner].[prodambiente]";

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
			return "prodambientelist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "prodambientelist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("prodambienteview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("prodambienteview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "prodambienteadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("prodambienteedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("prodambienteadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("prodambientedelete.php", $this->UrlParm());
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
		$this->nu_ambiente->setDbValue($rs->fields('nu_ambiente'));
		$this->no_ambiente->setDbValue($rs->fields('no_ambiente'));
		$this->ic_tpAtualizacao->setDbValue($rs->fields('ic_tpAtualizacao'));
		$this->ic_metCalibracao->setDbValue($rs->fields('ic_metCalibracao'));
		$this->nu_usuarioResp->setDbValue($rs->fields('nu_usuarioResp'));
		$this->qt_linhasCodLingPf->setDbValue($rs->fields('qt_linhasCodLingPf'));
		$this->vr_ipMin->setDbValue($rs->fields('vr_ipMin'));
		$this->vr_ipMed->setDbValue($rs->fields('vr_ipMed'));
		$this->vr_ipMax->setDbValue($rs->fields('vr_ipMax'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_ambiente
		// no_ambiente
		// ic_tpAtualizacao
		// ic_metCalibracao
		// nu_usuarioResp
		// qt_linhasCodLingPf
		// vr_ipMin
		// vr_ipMed
		// vr_ipMax
		// nu_ambiente

		$this->nu_ambiente->ViewValue = $this->nu_ambiente->CurrentValue;
		$this->nu_ambiente->ViewCustomAttributes = "";

		// no_ambiente
		$this->no_ambiente->ViewValue = $this->no_ambiente->CurrentValue;
		$this->no_ambiente->ViewCustomAttributes = "";

		// ic_tpAtualizacao
		if (strval($this->ic_tpAtualizacao->CurrentValue) <> "") {
			switch ($this->ic_tpAtualizacao->CurrentValue) {
				case $this->ic_tpAtualizacao->FldTagValue(1):
					$this->ic_tpAtualizacao->ViewValue = $this->ic_tpAtualizacao->FldTagCaption(1) <> "" ? $this->ic_tpAtualizacao->FldTagCaption(1) : $this->ic_tpAtualizacao->CurrentValue;
					break;
				case $this->ic_tpAtualizacao->FldTagValue(2):
					$this->ic_tpAtualizacao->ViewValue = $this->ic_tpAtualizacao->FldTagCaption(2) <> "" ? $this->ic_tpAtualizacao->FldTagCaption(2) : $this->ic_tpAtualizacao->CurrentValue;
					break;
				default:
					$this->ic_tpAtualizacao->ViewValue = $this->ic_tpAtualizacao->CurrentValue;
			}
		} else {
			$this->ic_tpAtualizacao->ViewValue = NULL;
		}
		$this->ic_tpAtualizacao->ViewCustomAttributes = "";

		// ic_metCalibracao
		if (strval($this->ic_metCalibracao->CurrentValue) <> "") {
			switch ($this->ic_metCalibracao->CurrentValue) {
				case $this->ic_metCalibracao->FldTagValue(1):
					$this->ic_metCalibracao->ViewValue = $this->ic_metCalibracao->FldTagCaption(1) <> "" ? $this->ic_metCalibracao->FldTagCaption(1) : $this->ic_metCalibracao->CurrentValue;
					break;
				case $this->ic_metCalibracao->FldTagValue(2):
					$this->ic_metCalibracao->ViewValue = $this->ic_metCalibracao->FldTagCaption(2) <> "" ? $this->ic_metCalibracao->FldTagCaption(2) : $this->ic_metCalibracao->CurrentValue;
					break;
				default:
					$this->ic_metCalibracao->ViewValue = $this->ic_metCalibracao->CurrentValue;
			}
		} else {
			$this->ic_metCalibracao->ViewValue = NULL;
		}
		$this->ic_metCalibracao->ViewCustomAttributes = "";

		// nu_usuarioResp
		$this->nu_usuarioResp->ViewValue = $this->nu_usuarioResp->CurrentValue;
		$this->nu_usuarioResp->ViewCustomAttributes = "";

		// qt_linhasCodLingPf
		$this->qt_linhasCodLingPf->ViewValue = $this->qt_linhasCodLingPf->CurrentValue;
		$this->qt_linhasCodLingPf->ViewCustomAttributes = "";

		// vr_ipMin
		$this->vr_ipMin->ViewValue = $this->vr_ipMin->CurrentValue;
		$this->vr_ipMin->ViewCustomAttributes = "";

		// vr_ipMed
		$this->vr_ipMed->ViewValue = $this->vr_ipMed->CurrentValue;
		$this->vr_ipMed->ViewCustomAttributes = "";

		// vr_ipMax
		$this->vr_ipMax->ViewValue = $this->vr_ipMax->CurrentValue;
		$this->vr_ipMax->ViewCustomAttributes = "";

		// nu_ambiente
		$this->nu_ambiente->LinkCustomAttributes = "";
		$this->nu_ambiente->HrefValue = "";
		$this->nu_ambiente->TooltipValue = "";

		// no_ambiente
		$this->no_ambiente->LinkCustomAttributes = "";
		$this->no_ambiente->HrefValue = "";
		$this->no_ambiente->TooltipValue = "";

		// ic_tpAtualizacao
		$this->ic_tpAtualizacao->LinkCustomAttributes = "";
		$this->ic_tpAtualizacao->HrefValue = "";
		$this->ic_tpAtualizacao->TooltipValue = "";

		// ic_metCalibracao
		$this->ic_metCalibracao->LinkCustomAttributes = "";
		$this->ic_metCalibracao->HrefValue = "";
		$this->ic_metCalibracao->TooltipValue = "";

		// nu_usuarioResp
		$this->nu_usuarioResp->LinkCustomAttributes = "";
		$this->nu_usuarioResp->HrefValue = "";
		$this->nu_usuarioResp->TooltipValue = "";

		// qt_linhasCodLingPf
		$this->qt_linhasCodLingPf->LinkCustomAttributes = "";
		$this->qt_linhasCodLingPf->HrefValue = "";
		$this->qt_linhasCodLingPf->TooltipValue = "";

		// vr_ipMin
		$this->vr_ipMin->LinkCustomAttributes = "";
		$this->vr_ipMin->HrefValue = "";
		$this->vr_ipMin->TooltipValue = "";

		// vr_ipMed
		$this->vr_ipMed->LinkCustomAttributes = "";
		$this->vr_ipMed->HrefValue = "";
		$this->vr_ipMed->TooltipValue = "";

		// vr_ipMax
		$this->vr_ipMax->LinkCustomAttributes = "";
		$this->vr_ipMax->HrefValue = "";
		$this->vr_ipMax->TooltipValue = "";

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
				if ($this->nu_ambiente->Exportable) $Doc->ExportCaption($this->nu_ambiente);
				if ($this->no_ambiente->Exportable) $Doc->ExportCaption($this->no_ambiente);
				if ($this->ic_tpAtualizacao->Exportable) $Doc->ExportCaption($this->ic_tpAtualizacao);
				if ($this->ic_metCalibracao->Exportable) $Doc->ExportCaption($this->ic_metCalibracao);
				if ($this->nu_usuarioResp->Exportable) $Doc->ExportCaption($this->nu_usuarioResp);
				if ($this->qt_linhasCodLingPf->Exportable) $Doc->ExportCaption($this->qt_linhasCodLingPf);
				if ($this->vr_ipMin->Exportable) $Doc->ExportCaption($this->vr_ipMin);
				if ($this->vr_ipMed->Exportable) $Doc->ExportCaption($this->vr_ipMed);
				if ($this->vr_ipMax->Exportable) $Doc->ExportCaption($this->vr_ipMax);
			} else {
				if ($this->nu_ambiente->Exportable) $Doc->ExportCaption($this->nu_ambiente);
				if ($this->no_ambiente->Exportable) $Doc->ExportCaption($this->no_ambiente);
				if ($this->ic_tpAtualizacao->Exportable) $Doc->ExportCaption($this->ic_tpAtualizacao);
				if ($this->ic_metCalibracao->Exportable) $Doc->ExportCaption($this->ic_metCalibracao);
				if ($this->nu_usuarioResp->Exportable) $Doc->ExportCaption($this->nu_usuarioResp);
				if ($this->qt_linhasCodLingPf->Exportable) $Doc->ExportCaption($this->qt_linhasCodLingPf);
				if ($this->vr_ipMin->Exportable) $Doc->ExportCaption($this->vr_ipMin);
				if ($this->vr_ipMed->Exportable) $Doc->ExportCaption($this->vr_ipMed);
				if ($this->vr_ipMax->Exportable) $Doc->ExportCaption($this->vr_ipMax);
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
					if ($this->nu_ambiente->Exportable) $Doc->ExportField($this->nu_ambiente);
					if ($this->no_ambiente->Exportable) $Doc->ExportField($this->no_ambiente);
					if ($this->ic_tpAtualizacao->Exportable) $Doc->ExportField($this->ic_tpAtualizacao);
					if ($this->ic_metCalibracao->Exportable) $Doc->ExportField($this->ic_metCalibracao);
					if ($this->nu_usuarioResp->Exportable) $Doc->ExportField($this->nu_usuarioResp);
					if ($this->qt_linhasCodLingPf->Exportable) $Doc->ExportField($this->qt_linhasCodLingPf);
					if ($this->vr_ipMin->Exportable) $Doc->ExportField($this->vr_ipMin);
					if ($this->vr_ipMed->Exportable) $Doc->ExportField($this->vr_ipMed);
					if ($this->vr_ipMax->Exportable) $Doc->ExportField($this->vr_ipMax);
				} else {
					if ($this->nu_ambiente->Exportable) $Doc->ExportField($this->nu_ambiente);
					if ($this->no_ambiente->Exportable) $Doc->ExportField($this->no_ambiente);
					if ($this->ic_tpAtualizacao->Exportable) $Doc->ExportField($this->ic_tpAtualizacao);
					if ($this->ic_metCalibracao->Exportable) $Doc->ExportField($this->ic_metCalibracao);
					if ($this->nu_usuarioResp->Exportable) $Doc->ExportField($this->nu_usuarioResp);
					if ($this->qt_linhasCodLingPf->Exportable) $Doc->ExportField($this->qt_linhasCodLingPf);
					if ($this->vr_ipMin->Exportable) $Doc->ExportField($this->vr_ipMin);
					if ($this->vr_ipMed->Exportable) $Doc->ExportField($this->vr_ipMed);
					if ($this->vr_ipMax->Exportable) $Doc->ExportField($this->vr_ipMax);
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
