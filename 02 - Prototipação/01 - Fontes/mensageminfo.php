<?php

// Global variable for table object
$mensagem = NULL;

//
// Table class for mensagem
//
class cmensagem extends cTable {
	var $nu_mensagem;
	var $co_alternativo;
	var $no_mensagem;
	var $ds_mensagem;
	var $ds_ajuda;
	var $nu_stMensagem;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'mensagem';
		$this->TableName = 'mensagem';
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

		// nu_mensagem
		$this->nu_mensagem = new cField('mensagem', 'mensagem', 'x_nu_mensagem', 'nu_mensagem', '[nu_mensagem]', 'CAST([nu_mensagem] AS NVARCHAR)', 3, -1, FALSE, '[nu_mensagem]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_mensagem->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_mensagem'] = &$this->nu_mensagem;

		// co_alternativo
		$this->co_alternativo = new cField('mensagem', 'mensagem', 'x_co_alternativo', 'co_alternativo', '[co_alternativo]', '[co_alternativo]', 200, -1, FALSE, '[co_alternativo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_alternativo'] = &$this->co_alternativo;

		// no_mensagem
		$this->no_mensagem = new cField('mensagem', 'mensagem', 'x_no_mensagem', 'no_mensagem', '[no_mensagem]', '[no_mensagem]', 200, -1, FALSE, '[no_mensagem]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_mensagem'] = &$this->no_mensagem;

		// ds_mensagem
		$this->ds_mensagem = new cField('mensagem', 'mensagem', 'x_ds_mensagem', 'ds_mensagem', '[ds_mensagem]', '[ds_mensagem]', 201, -1, FALSE, '[ds_mensagem]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_mensagem'] = &$this->ds_mensagem;

		// ds_ajuda
		$this->ds_ajuda = new cField('mensagem', 'mensagem', 'x_ds_ajuda', 'ds_ajuda', '[ds_ajuda]', '[ds_ajuda]', 201, -1, FALSE, '[ds_ajuda]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_ajuda'] = &$this->ds_ajuda;

		// nu_stMensagem
		$this->nu_stMensagem = new cField('mensagem', 'mensagem', 'x_nu_stMensagem', 'nu_stMensagem', '[nu_stMensagem]', 'CAST([nu_stMensagem] AS NVARCHAR)', 3, -1, FALSE, '[nu_stMensagem]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_stMensagem->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_stMensagem'] = &$this->nu_stMensagem;
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
		return "[dbo].[mensagem]";
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
		return "[no_mensagem] ASC,[nu_stMensagem] ASC";
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
	var $UpdateTable = "[dbo].[mensagem]";

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
			if (array_key_exists('nu_mensagem', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_mensagem') . '=' . ew_QuotedValue($rs['nu_mensagem'], $this->nu_mensagem->FldDataType));
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
		return "[nu_mensagem] = @nu_mensagem@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nu_mensagem->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_mensagem@", ew_AdjustSql($this->nu_mensagem->CurrentValue), $sKeyFilter); // Replace key value
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
			return "mensagemlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "mensagemlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("mensagemview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("mensagemview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "mensagemadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("mensagemedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("mensagemadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("mensagemdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nu_mensagem->CurrentValue)) {
			$sUrl .= "nu_mensagem=" . urlencode($this->nu_mensagem->CurrentValue);
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
			$arKeys[] = @$_GET["nu_mensagem"]; // nu_mensagem

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
			$this->nu_mensagem->CurrentValue = $key;
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
		$this->nu_mensagem->setDbValue($rs->fields('nu_mensagem'));
		$this->co_alternativo->setDbValue($rs->fields('co_alternativo'));
		$this->no_mensagem->setDbValue($rs->fields('no_mensagem'));
		$this->ds_mensagem->setDbValue($rs->fields('ds_mensagem'));
		$this->ds_ajuda->setDbValue($rs->fields('ds_ajuda'));
		$this->nu_stMensagem->setDbValue($rs->fields('nu_stMensagem'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_mensagem

		$this->nu_mensagem->CellCssStyle = "white-space: nowrap;";

		// co_alternativo
		// no_mensagem
		// ds_mensagem
		// ds_ajuda
		// nu_stMensagem
		// nu_mensagem

		$this->nu_mensagem->ViewValue = $this->nu_mensagem->CurrentValue;
		$this->nu_mensagem->ViewCustomAttributes = "";

		// co_alternativo
		$this->co_alternativo->ViewValue = $this->co_alternativo->CurrentValue;
		$this->co_alternativo->ViewCustomAttributes = "";

		// no_mensagem
		$this->no_mensagem->ViewValue = $this->no_mensagem->CurrentValue;
		$this->no_mensagem->ViewCustomAttributes = "";

		// ds_mensagem
		$this->ds_mensagem->ViewValue = $this->ds_mensagem->CurrentValue;
		$this->ds_mensagem->ViewCustomAttributes = "";

		// ds_ajuda
		$this->ds_ajuda->ViewValue = $this->ds_ajuda->CurrentValue;
		$this->ds_ajuda->ViewCustomAttributes = "";

		// nu_stMensagem
		if (strval($this->nu_stMensagem->CurrentValue) <> "") {
			$sFilterWrk = "[nu_stMensagem]" . ew_SearchString("=", $this->nu_stMensagem->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_stMensagem], [no_stMensagem] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[stmensagem]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_stMensagem, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_stMensagem->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_stMensagem->ViewValue = $this->nu_stMensagem->CurrentValue;
			}
		} else {
			$this->nu_stMensagem->ViewValue = NULL;
		}
		$this->nu_stMensagem->ViewCustomAttributes = "";

		// nu_mensagem
		$this->nu_mensagem->LinkCustomAttributes = "";
		$this->nu_mensagem->HrefValue = "";
		$this->nu_mensagem->TooltipValue = "";

		// co_alternativo
		$this->co_alternativo->LinkCustomAttributes = "";
		$this->co_alternativo->HrefValue = "";
		$this->co_alternativo->TooltipValue = "";

		// no_mensagem
		$this->no_mensagem->LinkCustomAttributes = "";
		$this->no_mensagem->HrefValue = "";
		$this->no_mensagem->TooltipValue = "";

		// ds_mensagem
		$this->ds_mensagem->LinkCustomAttributes = "";
		$this->ds_mensagem->HrefValue = "";
		$this->ds_mensagem->TooltipValue = "";

		// ds_ajuda
		$this->ds_ajuda->LinkCustomAttributes = "";
		$this->ds_ajuda->HrefValue = "";
		$this->ds_ajuda->TooltipValue = "";

		// nu_stMensagem
		$this->nu_stMensagem->LinkCustomAttributes = "";
		$this->nu_stMensagem->HrefValue = "";
		$this->nu_stMensagem->TooltipValue = "";

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
				if ($this->no_mensagem->Exportable) $Doc->ExportCaption($this->no_mensagem);
				if ($this->ds_mensagem->Exportable) $Doc->ExportCaption($this->ds_mensagem);
				if ($this->ds_ajuda->Exportable) $Doc->ExportCaption($this->ds_ajuda);
				if ($this->nu_stMensagem->Exportable) $Doc->ExportCaption($this->nu_stMensagem);
			} else {
				if ($this->no_mensagem->Exportable) $Doc->ExportCaption($this->no_mensagem);
				if ($this->ds_mensagem->Exportable) $Doc->ExportCaption($this->ds_mensagem);
				if ($this->nu_stMensagem->Exportable) $Doc->ExportCaption($this->nu_stMensagem);
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
					if ($this->no_mensagem->Exportable) $Doc->ExportField($this->no_mensagem);
					if ($this->ds_mensagem->Exportable) $Doc->ExportField($this->ds_mensagem);
					if ($this->ds_ajuda->Exportable) $Doc->ExportField($this->ds_ajuda);
					if ($this->nu_stMensagem->Exportable) $Doc->ExportField($this->nu_stMensagem);
				} else {
					if ($this->no_mensagem->Exportable) $Doc->ExportField($this->no_mensagem);
					if ($this->ds_mensagem->Exportable) $Doc->ExportField($this->ds_mensagem);
					if ($this->nu_stMensagem->Exportable) $Doc->ExportField($this->nu_stMensagem);
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

		$valor = "";
		$prevalor = "";
		$valor = ew_ExecuteScalar("select max(co_alternativo) From mensagem");
		If ($valor != "") {                                     
			$valor = substr($valor, 2, 15);
			if (is_numeric($valor)) {             
				If (($valor > 0) And ($valor <= 8)) {    
					$prevalor = "0000";                                       
				} else If (($valor >= 9) And ($valor <= 98)) {             
					$prevalor = "000";         
				} else If (($valor >= 99) And ($valor <= 998)) {
					$prevalor = "00";                         
				} else If (($valor >= 999) And ($valor <= 9998)) { 
					$prevalor = "0";                                           
				} else If (($valor >= 9999) And ($valor <= 99998)) {
					$prevalor = "";          
				} else {              
					$prevalor = "";              
				}                       
				$valor = "ME" . $prevalor . ($valor + 1); 
				$this->co_alternativo->EditValue = $valor;
			} else {                            
				$this->co_alternativo->EditValue = "ERRO";    
			}  
		} else {
			 $this->co_alternativo->EditValue = "RN00001";
		}        
	}              

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
