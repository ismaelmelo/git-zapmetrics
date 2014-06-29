<?php

// Global variable for table object
$solicitacaoMetricas = NULL;

//
// Table class for solicitacaoMetricas
//
class csolicitacaoMetricas extends cTable {
	var $nu_solMetricas;
	var $nu_tpSolicitacao;
	var $nu_projeto;
	var $no_atividadeMaeRedmine;
	var $ds_observacoes;
	var $ds_documentacaoAux;
	var $ds_imapactoDb;
	var $ic_stSolicitacao;
	var $nu_usuarioAlterou;
	var $dh_alteracao;
	var $nu_usuarioIncluiu;
	var $dh_inclusao;
	var $dt_stSolicitacao;
	var $qt_pfTotal;
	var $vr_pfContForn;
	var $nu_tpMetrica;
	var $ds_observacoesContForn;
	var $im_anexosContForn;
	var $nu_contagemAnt;
	var $ds_observaocoesContAnt;
	var $im_anexosContAnt;
	var $ic_bloqueio;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'solicitacaoMetricas';
		$this->TableName = 'solicitacaoMetricas';
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

		// nu_solMetricas
		$this->nu_solMetricas = new cField('solicitacaoMetricas', 'solicitacaoMetricas', 'x_nu_solMetricas', 'nu_solMetricas', '[nu_solMetricas]', 'CAST([nu_solMetricas] AS NVARCHAR)', 3, -1, FALSE, '[nu_solMetricas]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_solMetricas->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_solMetricas'] = &$this->nu_solMetricas;

		// nu_tpSolicitacao
		$this->nu_tpSolicitacao = new cField('solicitacaoMetricas', 'solicitacaoMetricas', 'x_nu_tpSolicitacao', 'nu_tpSolicitacao', '[nu_tpSolicitacao]', 'CAST([nu_tpSolicitacao] AS NVARCHAR)', 3, -1, FALSE, '[nu_tpSolicitacao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_tpSolicitacao->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_tpSolicitacao'] = &$this->nu_tpSolicitacao;

		// nu_projeto
		$this->nu_projeto = new cField('solicitacaoMetricas', 'solicitacaoMetricas', 'x_nu_projeto', 'nu_projeto', '[nu_projeto]', 'CAST([nu_projeto] AS NVARCHAR)', 3, -1, FALSE, '[EV__nu_projeto]', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->nu_projeto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_projeto'] = &$this->nu_projeto;

		// no_atividadeMaeRedmine
		$this->no_atividadeMaeRedmine = new cField('solicitacaoMetricas', 'solicitacaoMetricas', 'x_no_atividadeMaeRedmine', 'no_atividadeMaeRedmine', '[no_atividadeMaeRedmine]', '[no_atividadeMaeRedmine]', 200, -1, FALSE, '[no_atividadeMaeRedmine]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['no_atividadeMaeRedmine'] = &$this->no_atividadeMaeRedmine;

		// ds_observacoes
		$this->ds_observacoes = new cField('solicitacaoMetricas', 'solicitacaoMetricas', 'x_ds_observacoes', 'ds_observacoes', '[ds_observacoes]', '[ds_observacoes]', 201, -1, FALSE, '[ds_observacoes]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_observacoes'] = &$this->ds_observacoes;

		// ds_documentacaoAux
		$this->ds_documentacaoAux = new cField('solicitacaoMetricas', 'solicitacaoMetricas', 'x_ds_documentacaoAux', 'ds_documentacaoAux', '[ds_documentacaoAux]', '[ds_documentacaoAux]', 201, -1, FALSE, '[ds_documentacaoAux]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_documentacaoAux'] = &$this->ds_documentacaoAux;

		// ds_imapactoDb
		$this->ds_imapactoDb = new cField('solicitacaoMetricas', 'solicitacaoMetricas', 'x_ds_imapactoDb', 'ds_imapactoDb', '[ds_imapactoDb]', '[ds_imapactoDb]', 201, -1, FALSE, '[ds_imapactoDb]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_imapactoDb'] = &$this->ds_imapactoDb;

		// ic_stSolicitacao
		$this->ic_stSolicitacao = new cField('solicitacaoMetricas', 'solicitacaoMetricas', 'x_ic_stSolicitacao', 'ic_stSolicitacao', '[ic_stSolicitacao]', '[ic_stSolicitacao]', 129, -1, FALSE, '[ic_stSolicitacao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_stSolicitacao'] = &$this->ic_stSolicitacao;

		// nu_usuarioAlterou
		$this->nu_usuarioAlterou = new cField('solicitacaoMetricas', 'solicitacaoMetricas', 'x_nu_usuarioAlterou', 'nu_usuarioAlterou', '[nu_usuarioAlterou]', 'CAST([nu_usuarioAlterou] AS NVARCHAR)', 3, -1, FALSE, '[nu_usuarioAlterou]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_usuarioAlterou->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_usuarioAlterou'] = &$this->nu_usuarioAlterou;

		// dh_alteracao
		$this->dh_alteracao = new cField('solicitacaoMetricas', 'solicitacaoMetricas', 'x_dh_alteracao', 'dh_alteracao', '[dh_alteracao]', '(REPLACE(STR(DAY([dh_alteracao]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dh_alteracao]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dh_alteracao]),4,0))', 135, 10, FALSE, '[dh_alteracao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dh_alteracao->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dh_alteracao'] = &$this->dh_alteracao;

		// nu_usuarioIncluiu
		$this->nu_usuarioIncluiu = new cField('solicitacaoMetricas', 'solicitacaoMetricas', 'x_nu_usuarioIncluiu', 'nu_usuarioIncluiu', '[nu_usuarioIncluiu]', 'CAST([nu_usuarioIncluiu] AS NVARCHAR)', 3, -1, FALSE, '[nu_usuarioIncluiu]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_usuarioIncluiu->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_usuarioIncluiu'] = &$this->nu_usuarioIncluiu;

		// dh_inclusao
		$this->dh_inclusao = new cField('solicitacaoMetricas', 'solicitacaoMetricas', 'x_dh_inclusao', 'dh_inclusao', '[dh_inclusao]', '(REPLACE(STR(DAY([dh_inclusao]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dh_inclusao]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dh_inclusao]),4,0))', 135, 7, FALSE, '[dh_inclusao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dh_inclusao->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dh_inclusao'] = &$this->dh_inclusao;

		// dt_stSolicitacao
		$this->dt_stSolicitacao = new cField('solicitacaoMetricas', 'solicitacaoMetricas', 'x_dt_stSolicitacao', 'dt_stSolicitacao', '[dt_stSolicitacao]', '(REPLACE(STR(DAY([dt_stSolicitacao]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dt_stSolicitacao]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dt_stSolicitacao]),4,0))', 135, 7, FALSE, '[dt_stSolicitacao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dt_stSolicitacao->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dt_stSolicitacao'] = &$this->dt_stSolicitacao;

		// qt_pfTotal
		$this->qt_pfTotal = new cField('solicitacaoMetricas', 'solicitacaoMetricas', 'x_qt_pfTotal', 'qt_pfTotal', '[qt_pfTotal]', 'CAST([qt_pfTotal] AS NVARCHAR)', 131, -1, FALSE, '[qt_pfTotal]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->qt_pfTotal->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['qt_pfTotal'] = &$this->qt_pfTotal;

		// vr_pfContForn
		$this->vr_pfContForn = new cField('solicitacaoMetricas', 'solicitacaoMetricas', 'x_vr_pfContForn', 'vr_pfContForn', '[vr_pfContForn]', 'CAST([vr_pfContForn] AS NVARCHAR)', 131, -1, FALSE, '[vr_pfContForn]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->vr_pfContForn->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['vr_pfContForn'] = &$this->vr_pfContForn;

		// nu_tpMetrica
		$this->nu_tpMetrica = new cField('solicitacaoMetricas', 'solicitacaoMetricas', 'x_nu_tpMetrica', 'nu_tpMetrica', '[nu_tpMetrica]', 'CAST([nu_tpMetrica] AS NVARCHAR)', 3, -1, FALSE, '[nu_tpMetrica]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_tpMetrica->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_tpMetrica'] = &$this->nu_tpMetrica;

		// ds_observacoesContForn
		$this->ds_observacoesContForn = new cField('solicitacaoMetricas', 'solicitacaoMetricas', 'x_ds_observacoesContForn', 'ds_observacoesContForn', '[ds_observacoesContForn]', '[ds_observacoesContForn]', 201, -1, FALSE, '[ds_observacoesContForn]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_observacoesContForn'] = &$this->ds_observacoesContForn;

		// im_anexosContForn
		$this->im_anexosContForn = new cField('solicitacaoMetricas', 'solicitacaoMetricas', 'x_im_anexosContForn', 'im_anexosContForn', '[im_anexosContForn]', '[im_anexosContForn]', 201, -1, TRUE, '[im_anexosContForn]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->im_anexosContForn->UploadMultiple = TRUE;
		$this->fields['im_anexosContForn'] = &$this->im_anexosContForn;

		// nu_contagemAnt
		$this->nu_contagemAnt = new cField('solicitacaoMetricas', 'solicitacaoMetricas', 'x_nu_contagemAnt', 'nu_contagemAnt', '[nu_contagemAnt]', 'CAST([nu_contagemAnt] AS NVARCHAR)', 3, -1, FALSE, '[EV__nu_contagemAnt]', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->nu_contagemAnt->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_contagemAnt'] = &$this->nu_contagemAnt;

		// ds_observaocoesContAnt
		$this->ds_observaocoesContAnt = new cField('solicitacaoMetricas', 'solicitacaoMetricas', 'x_ds_observaocoesContAnt', 'ds_observaocoesContAnt', '[ds_observaocoesContAnt]', '[ds_observaocoesContAnt]', 201, -1, FALSE, '[ds_observaocoesContAnt]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_observaocoesContAnt'] = &$this->ds_observaocoesContAnt;

		// im_anexosContAnt
		$this->im_anexosContAnt = new cField('solicitacaoMetricas', 'solicitacaoMetricas', 'x_im_anexosContAnt', 'im_anexosContAnt', '[im_anexosContAnt]', '[im_anexosContAnt]', 201, -1, TRUE, '[im_anexosContAnt]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->im_anexosContAnt->UploadMultiple = TRUE;
		$this->fields['im_anexosContAnt'] = &$this->im_anexosContAnt;

		// ic_bloqueio
		$this->ic_bloqueio = new cField('solicitacaoMetricas', 'solicitacaoMetricas', 'x_ic_bloqueio', 'ic_bloqueio', '[ic_bloqueio]', '[ic_bloqueio]', 129, -1, FALSE, '[ic_bloqueio]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_bloqueio'] = &$this->ic_bloqueio;
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
			$sSortFieldList = ($ofld->FldVirtualExpression <> "") ? $ofld->FldVirtualExpression : $sSortField;
			if ($ctrl) {
				$sOrderByList = $this->getSessionOrderByList();
				if (strpos($sOrderByList, $sSortFieldList . " " . $sLastSort) !== FALSE) {
					$sOrderByList = str_replace($sSortFieldList . " " . $sLastSort, $sSortFieldList . " " . $sThisSort, $sOrderByList);
				} else {
					if ($sOrderByList <> "") $sOrderByList .= ", ";
					$sOrderByList .= $sSortFieldList . " " . $sThisSort;
				}
				$this->setSessionOrderByList($sOrderByList); // Save to Session
			} else {
				$this->setSessionOrderByList($sSortFieldList . " " . $sThisSort); // Save to Session
			}
		} else {
			if (!$ctrl) $ofld->setSort("");
		}
	}

	// Session ORDER BY for List page
	function getSessionOrderByList() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST];
	}

	function setSessionOrderByList($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST] = $v;
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
		if ($this->getCurrentDetailTable() == "solicitacao_ocorrencia") {
			$sDetailUrl = $GLOBALS["solicitacao_ocorrencia"]->GetListUrl() . "?showmaster=" . $this->TableVar;
			$sDetailUrl .= "&nu_solicitacao=" . $this->nu_solMetricas->CurrentValue;
		}
		if ($this->getCurrentDetailTable() == "contagempf") {
			$sDetailUrl = $GLOBALS["contagempf"]->GetListUrl() . "?showmaster=" . $this->TableVar;
			$sDetailUrl .= "&nu_solMetricas=" . $this->nu_solMetricas->CurrentValue;
		}
		if ($this->getCurrentDetailTable() == "estimativa") {
			$sDetailUrl = $GLOBALS["estimativa"]->GetListUrl() . "?showmaster=" . $this->TableVar;
			$sDetailUrl .= "&nu_solMetricas=" . $this->nu_solMetricas->CurrentValue;
		}
		if ($this->getCurrentDetailTable() == "laudo") {
			$sDetailUrl = $GLOBALS["laudo"]->GetListUrl() . "?showmaster=" . $this->TableVar;
			$sDetailUrl .= "&nu_solicitacao=" . $this->nu_solMetricas->CurrentValue;
		}
		if ($sDetailUrl == "") {
			$sDetailUrl = "solicitacaometricaslist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	function SqlFrom() { // From
		return "[dbo].[solicitacaoMetricas]";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlSelectList() { // Select for List page
		return "SELECT * FROM (" .
			"SELECT *, (SELECT TOP 1 [no_projeto] FROM [projeto] [EW_TMP_LOOKUPTABLE] WHERE [EW_TMP_LOOKUPTABLE].[nu_projeto] = [solicitacaoMetricas].[nu_projeto]) AS [EV__nu_projeto], (SELECT TOP 1 [nu_solMetricas] FROM [solicitacaoMetricas] [EW_TMP_LOOKUPTABLE] WHERE [EW_TMP_LOOKUPTABLE].[nu_solMetricas] = [solicitacaoMetricas].[nu_contagemAnt]) AS [EV__nu_contagemAnt] FROM [dbo].[solicitacaoMetricas]" .
			") [EW_TMP_TABLE]";
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
		return "[nu_solMetricas] DESC";
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
		if ($this->UseVirtualFields()) {
			$sSort = $this->getSessionOrderByList();
			return ew_BuildSelectSql($this->SqlSelectList(), $this->SqlWhere(), $this->SqlGroupBy(), 
				$this->SqlHaving(), $this->SqlOrderBy(), $sFilter, $sSort);
		} else {
			$sSort = $this->getSessionOrderBy();
			return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(), $this->SqlGroupBy(),
				$this->SqlHaving(), $this->SqlOrderBy(), $sFilter, $sSort);
		}
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = ($this->UseVirtualFields()) ? $this->getSessionOrderByList() : $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->SqlOrderBy(), "", $sSort);
	}

	// Check if virtual fields is used in SQL
	function UseVirtualFields() {
		$sWhere = $this->getSessionWhere();
		$sOrderBy = $this->getSessionOrderByList();
		if ($sWhere <> "")
			$sWhere = " " . str_replace(array("(",")"), array("",""), $sWhere) . " ";
		if ($sOrderBy <> "")
			$sOrderBy = " " . str_replace(array("(",")"), array("",""), $sOrderBy) . " ";
		if ($this->nu_projeto->AdvancedSearch->SearchValue <> "" ||
			$this->nu_projeto->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->nu_projeto->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->nu_projeto->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->nu_contagemAnt->AdvancedSearch->SearchValue <> "" ||
			$this->nu_contagemAnt->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->nu_contagemAnt->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->nu_contagemAnt->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		return FALSE;
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
	var $UpdateTable = "[dbo].[solicitacaoMetricas]";

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
			if (array_key_exists('nu_solMetricas', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_solMetricas') . '=' . ew_QuotedValue($rs['nu_solMetricas'], $this->nu_solMetricas->FldDataType));
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
		return "[nu_solMetricas] = @nu_solMetricas@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nu_solMetricas->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_solMetricas@", ew_AdjustSql($this->nu_solMetricas->CurrentValue), $sKeyFilter); // Replace key value
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
			return "solicitacaometricaslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "solicitacaometricaslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("solicitacaometricasview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("solicitacaometricasview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "solicitacaometricasadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("solicitacaometricasedit.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("solicitacaometricasedit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("solicitacaometricasadd.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("solicitacaometricasadd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("solicitacaometricasdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nu_solMetricas->CurrentValue)) {
			$sUrl .= "nu_solMetricas=" . urlencode($this->nu_solMetricas->CurrentValue);
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
			$arKeys[] = @$_GET["nu_solMetricas"]; // nu_solMetricas

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
			$this->nu_solMetricas->CurrentValue = $key;
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
		$this->nu_solMetricas->setDbValue($rs->fields('nu_solMetricas'));
		$this->nu_tpSolicitacao->setDbValue($rs->fields('nu_tpSolicitacao'));
		$this->nu_projeto->setDbValue($rs->fields('nu_projeto'));
		$this->no_atividadeMaeRedmine->setDbValue($rs->fields('no_atividadeMaeRedmine'));
		$this->ds_observacoes->setDbValue($rs->fields('ds_observacoes'));
		$this->ds_documentacaoAux->setDbValue($rs->fields('ds_documentacaoAux'));
		$this->ds_imapactoDb->setDbValue($rs->fields('ds_imapactoDb'));
		$this->ic_stSolicitacao->setDbValue($rs->fields('ic_stSolicitacao'));
		$this->nu_usuarioAlterou->setDbValue($rs->fields('nu_usuarioAlterou'));
		$this->dh_alteracao->setDbValue($rs->fields('dh_alteracao'));
		$this->nu_usuarioIncluiu->setDbValue($rs->fields('nu_usuarioIncluiu'));
		$this->dh_inclusao->setDbValue($rs->fields('dh_inclusao'));
		$this->dt_stSolicitacao->setDbValue($rs->fields('dt_stSolicitacao'));
		$this->qt_pfTotal->setDbValue($rs->fields('qt_pfTotal'));
		$this->vr_pfContForn->setDbValue($rs->fields('vr_pfContForn'));
		$this->nu_tpMetrica->setDbValue($rs->fields('nu_tpMetrica'));
		$this->ds_observacoesContForn->setDbValue($rs->fields('ds_observacoesContForn'));
		$this->im_anexosContForn->Upload->DbValue = $rs->fields('im_anexosContForn');
		$this->nu_contagemAnt->setDbValue($rs->fields('nu_contagemAnt'));
		$this->ds_observaocoesContAnt->setDbValue($rs->fields('ds_observaocoesContAnt'));
		$this->im_anexosContAnt->Upload->DbValue = $rs->fields('im_anexosContAnt');
		$this->ic_bloqueio->setDbValue($rs->fields('ic_bloqueio'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_solMetricas
		// nu_tpSolicitacao
		// nu_projeto
		// no_atividadeMaeRedmine
		// ds_observacoes
		// ds_documentacaoAux
		// ds_imapactoDb
		// ic_stSolicitacao
		// nu_usuarioAlterou
		// dh_alteracao
		// nu_usuarioIncluiu
		// dh_inclusao
		// dt_stSolicitacao
		// qt_pfTotal
		// vr_pfContForn
		// nu_tpMetrica
		// ds_observacoesContForn
		// im_anexosContForn
		// nu_contagemAnt
		// ds_observaocoesContAnt
		// im_anexosContAnt
		// ic_bloqueio

		$this->ic_bloqueio->CellCssStyle = "white-space: nowrap;";

		// nu_solMetricas
		$this->nu_solMetricas->ViewValue = $this->nu_solMetricas->CurrentValue;
		$this->nu_solMetricas->ViewCustomAttributes = "";

		// nu_tpSolicitacao
		if (strval($this->nu_tpSolicitacao->CurrentValue) <> "") {
			$sFilterWrk = "[nu_tpSolicitacao]" . ew_SearchString("=", $this->nu_tpSolicitacao->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_tpSolicitacao], [no_tpSolicitacao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpsolicitacao]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_tpSolicitacao, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_tpSolicitacao] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_tpSolicitacao->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_tpSolicitacao->ViewValue = $this->nu_tpSolicitacao->CurrentValue;
			}
		} else {
			$this->nu_tpSolicitacao->ViewValue = NULL;
		}
		$this->nu_tpSolicitacao->ViewCustomAttributes = "";

		// nu_projeto
		if ($this->nu_projeto->VirtualValue <> "") {
			$this->nu_projeto->ViewValue = $this->nu_projeto->VirtualValue;
		} else {
		if (strval($this->nu_projeto->CurrentValue) <> "") {
			$sFilterWrk = "[nu_projeto]" . ew_SearchString("=", $this->nu_projeto->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_projeto], [no_projeto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[projeto]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_passivelContPf]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_projeto, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_projeto] DESC";
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
		}
		$this->nu_projeto->ViewCustomAttributes = "";

		// no_atividadeMaeRedmine
		$this->no_atividadeMaeRedmine->ViewValue = $this->no_atividadeMaeRedmine->CurrentValue;
		$this->no_atividadeMaeRedmine->ViewCustomAttributes = "";

		// ds_observacoes
		$this->ds_observacoes->ViewValue = $this->ds_observacoes->CurrentValue;
		$this->ds_observacoes->ViewCustomAttributes = "";

		// ds_documentacaoAux
		$this->ds_documentacaoAux->ViewValue = $this->ds_documentacaoAux->CurrentValue;
		$this->ds_documentacaoAux->ViewCustomAttributes = "";

		// ds_imapactoDb
		$this->ds_imapactoDb->ViewValue = $this->ds_imapactoDb->CurrentValue;
		$this->ds_imapactoDb->ViewCustomAttributes = "";

		// ic_stSolicitacao
		if (strval($this->ic_stSolicitacao->CurrentValue) <> "") {
			switch ($this->ic_stSolicitacao->CurrentValue) {
				case $this->ic_stSolicitacao->FldTagValue(1):
					$this->ic_stSolicitacao->ViewValue = $this->ic_stSolicitacao->FldTagCaption(1) <> "" ? $this->ic_stSolicitacao->FldTagCaption(1) : $this->ic_stSolicitacao->CurrentValue;
					break;
				case $this->ic_stSolicitacao->FldTagValue(2):
					$this->ic_stSolicitacao->ViewValue = $this->ic_stSolicitacao->FldTagCaption(2) <> "" ? $this->ic_stSolicitacao->FldTagCaption(2) : $this->ic_stSolicitacao->CurrentValue;
					break;
				case $this->ic_stSolicitacao->FldTagValue(3):
					$this->ic_stSolicitacao->ViewValue = $this->ic_stSolicitacao->FldTagCaption(3) <> "" ? $this->ic_stSolicitacao->FldTagCaption(3) : $this->ic_stSolicitacao->CurrentValue;
					break;
				case $this->ic_stSolicitacao->FldTagValue(4):
					$this->ic_stSolicitacao->ViewValue = $this->ic_stSolicitacao->FldTagCaption(4) <> "" ? $this->ic_stSolicitacao->FldTagCaption(4) : $this->ic_stSolicitacao->CurrentValue;
					break;
				default:
					$this->ic_stSolicitacao->ViewValue = $this->ic_stSolicitacao->CurrentValue;
			}
		} else {
			$this->ic_stSolicitacao->ViewValue = NULL;
		}
		$this->ic_stSolicitacao->ViewCustomAttributes = "";

		// nu_usuarioAlterou
		if (strval($this->nu_usuarioAlterou->CurrentValue) <> "") {
			$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioAlterou->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_usuarioAlterou, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_usuarioAlterou->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_usuarioAlterou->ViewValue = $this->nu_usuarioAlterou->CurrentValue;
			}
		} else {
			$this->nu_usuarioAlterou->ViewValue = NULL;
		}
		$this->nu_usuarioAlterou->ViewCustomAttributes = "";

		// dh_alteracao
		$this->dh_alteracao->ViewValue = $this->dh_alteracao->CurrentValue;
		$this->dh_alteracao->ViewValue = ew_FormatDateTime($this->dh_alteracao->ViewValue, 10);
		$this->dh_alteracao->ViewCustomAttributes = "";

		// nu_usuarioIncluiu
		if (strval($this->nu_usuarioIncluiu->CurrentValue) <> "") {
			$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioIncluiu->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_usuarioIncluiu, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_usuarioIncluiu->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_usuarioIncluiu->ViewValue = $this->nu_usuarioIncluiu->CurrentValue;
			}
		} else {
			$this->nu_usuarioIncluiu->ViewValue = NULL;
		}
		$this->nu_usuarioIncluiu->ViewCustomAttributes = "";

		// dh_inclusao
		$this->dh_inclusao->ViewValue = $this->dh_inclusao->CurrentValue;
		$this->dh_inclusao->ViewValue = ew_FormatDateTime($this->dh_inclusao->ViewValue, 7);
		$this->dh_inclusao->ViewCustomAttributes = "";

		// dt_stSolicitacao
		$this->dt_stSolicitacao->ViewValue = $this->dt_stSolicitacao->CurrentValue;
		$this->dt_stSolicitacao->ViewValue = ew_FormatDateTime($this->dt_stSolicitacao->ViewValue, 7);
		$this->dt_stSolicitacao->ViewCustomAttributes = "";

		// qt_pfTotal
		$this->qt_pfTotal->ViewValue = $this->qt_pfTotal->CurrentValue;
		$this->qt_pfTotal->ViewCustomAttributes = "";

		// vr_pfContForn
		$this->vr_pfContForn->ViewValue = $this->vr_pfContForn->CurrentValue;
		$this->vr_pfContForn->ViewCustomAttributes = "";

		// nu_tpMetrica
		if (strval($this->nu_tpMetrica->CurrentValue) <> "") {
			$sFilterWrk = "[nu_tpMetrica]" . ew_SearchString("=", $this->nu_tpMetrica->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_tpMetrica], [no_tpMetrica] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpmetrica]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_tpMetrica, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_tpMetrica] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_tpMetrica->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_tpMetrica->ViewValue = $this->nu_tpMetrica->CurrentValue;
			}
		} else {
			$this->nu_tpMetrica->ViewValue = NULL;
		}
		$this->nu_tpMetrica->ViewCustomAttributes = "";

		// ds_observacoesContForn
		$this->ds_observacoesContForn->ViewValue = $this->ds_observacoesContForn->CurrentValue;
		$this->ds_observacoesContForn->ViewCustomAttributes = "";

		// im_anexosContForn
		$this->im_anexosContForn->UploadPath = "contagem_fornecedor";
		if (!ew_Empty($this->im_anexosContForn->Upload->DbValue)) {
			$this->im_anexosContForn->ViewValue = $this->im_anexosContForn->Upload->DbValue;
		} else {
			$this->im_anexosContForn->ViewValue = "";
		}
		$this->im_anexosContForn->ViewCustomAttributes = "";

		// nu_contagemAnt
		if ($this->nu_contagemAnt->VirtualValue <> "") {
			$this->nu_contagemAnt->ViewValue = $this->nu_contagemAnt->VirtualValue;
		} else {
		if (strval($this->nu_contagemAnt->CurrentValue) <> "") {
			$sFilterWrk = "[nu_solMetricas]" . ew_SearchString("=", $this->nu_contagemAnt->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_solMetricas], [nu_solMetricas] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[solicitacaoMetricas]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_contagemAnt, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_solMetricas] DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_contagemAnt->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_contagemAnt->ViewValue = $this->nu_contagemAnt->CurrentValue;
			}
		} else {
			$this->nu_contagemAnt->ViewValue = NULL;
		}
		}
		$this->nu_contagemAnt->ViewCustomAttributes = "";

		// ds_observaocoesContAnt
		$this->ds_observaocoesContAnt->ViewValue = $this->ds_observaocoesContAnt->CurrentValue;
		$this->ds_observaocoesContAnt->ViewCustomAttributes = "";

		// im_anexosContAnt
		$this->im_anexosContAnt->UploadPath = "contagem_anterior";
		if (!ew_Empty($this->im_anexosContAnt->Upload->DbValue)) {
			$this->im_anexosContAnt->ViewValue = $this->im_anexosContAnt->Upload->DbValue;
		} else {
			$this->im_anexosContAnt->ViewValue = "";
		}
		$this->im_anexosContAnt->ViewCustomAttributes = "";

		// ic_bloqueio
		$this->ic_bloqueio->ViewValue = $this->ic_bloqueio->CurrentValue;
		$this->ic_bloqueio->ViewCustomAttributes = "";

		// nu_solMetricas
		$this->nu_solMetricas->LinkCustomAttributes = "";
		$this->nu_solMetricas->HrefValue = "";
		$this->nu_solMetricas->TooltipValue = "";

		// nu_tpSolicitacao
		$this->nu_tpSolicitacao->LinkCustomAttributes = "";
		$this->nu_tpSolicitacao->HrefValue = "";
		$this->nu_tpSolicitacao->TooltipValue = "";

		// nu_projeto
		$this->nu_projeto->LinkCustomAttributes = "";
		$this->nu_projeto->HrefValue = "";
		$this->nu_projeto->TooltipValue = "";

		// no_atividadeMaeRedmine
		$this->no_atividadeMaeRedmine->LinkCustomAttributes = "";
		$this->no_atividadeMaeRedmine->HrefValue = "";
		$this->no_atividadeMaeRedmine->TooltipValue = "";

		// ds_observacoes
		$this->ds_observacoes->LinkCustomAttributes = "";
		$this->ds_observacoes->HrefValue = "";
		$this->ds_observacoes->TooltipValue = "";

		// ds_documentacaoAux
		$this->ds_documentacaoAux->LinkCustomAttributes = "";
		$this->ds_documentacaoAux->HrefValue = "";
		$this->ds_documentacaoAux->TooltipValue = "";

		// ds_imapactoDb
		$this->ds_imapactoDb->LinkCustomAttributes = "";
		$this->ds_imapactoDb->HrefValue = "";
		$this->ds_imapactoDb->TooltipValue = "";

		// ic_stSolicitacao
		$this->ic_stSolicitacao->LinkCustomAttributes = "";
		$this->ic_stSolicitacao->HrefValue = "";
		$this->ic_stSolicitacao->TooltipValue = "";

		// nu_usuarioAlterou
		$this->nu_usuarioAlterou->LinkCustomAttributes = "";
		$this->nu_usuarioAlterou->HrefValue = "";
		$this->nu_usuarioAlterou->TooltipValue = "";

		// dh_alteracao
		$this->dh_alteracao->LinkCustomAttributes = "";
		$this->dh_alteracao->HrefValue = "";
		$this->dh_alteracao->TooltipValue = "";

		// nu_usuarioIncluiu
		$this->nu_usuarioIncluiu->LinkCustomAttributes = "";
		$this->nu_usuarioIncluiu->HrefValue = "";
		$this->nu_usuarioIncluiu->TooltipValue = "";

		// dh_inclusao
		$this->dh_inclusao->LinkCustomAttributes = "";
		$this->dh_inclusao->HrefValue = "";
		$this->dh_inclusao->TooltipValue = "";

		// dt_stSolicitacao
		$this->dt_stSolicitacao->LinkCustomAttributes = "";
		$this->dt_stSolicitacao->HrefValue = "";
		$this->dt_stSolicitacao->TooltipValue = "";

		// qt_pfTotal
		$this->qt_pfTotal->LinkCustomAttributes = "";
		$this->qt_pfTotal->HrefValue = "";
		$this->qt_pfTotal->TooltipValue = "";

		// vr_pfContForn
		$this->vr_pfContForn->LinkCustomAttributes = "";
		$this->vr_pfContForn->HrefValue = "";
		$this->vr_pfContForn->TooltipValue = "";

		// nu_tpMetrica
		$this->nu_tpMetrica->LinkCustomAttributes = "";
		$this->nu_tpMetrica->HrefValue = "";
		$this->nu_tpMetrica->TooltipValue = "";

		// ds_observacoesContForn
		$this->ds_observacoesContForn->LinkCustomAttributes = "";
		$this->ds_observacoesContForn->HrefValue = "";
		$this->ds_observacoesContForn->TooltipValue = "";

		// im_anexosContForn
		$this->im_anexosContForn->LinkCustomAttributes = "";
		$this->im_anexosContForn->HrefValue = "";
		$this->im_anexosContForn->HrefValue2 = $this->im_anexosContForn->UploadPath . $this->im_anexosContForn->Upload->DbValue;
		$this->im_anexosContForn->TooltipValue = "";

		// nu_contagemAnt
		$this->nu_contagemAnt->LinkCustomAttributes = "";
		$this->nu_contagemAnt->HrefValue = "";
		$this->nu_contagemAnt->TooltipValue = "";

		// ds_observaocoesContAnt
		$this->ds_observaocoesContAnt->LinkCustomAttributes = "";
		$this->ds_observaocoesContAnt->HrefValue = "";
		$this->ds_observaocoesContAnt->TooltipValue = "";

		// im_anexosContAnt
		$this->im_anexosContAnt->LinkCustomAttributes = "";
		$this->im_anexosContAnt->HrefValue = "";
		$this->im_anexosContAnt->HrefValue2 = $this->im_anexosContAnt->UploadPath . $this->im_anexosContAnt->Upload->DbValue;
		$this->im_anexosContAnt->TooltipValue = "";

		// ic_bloqueio
		$this->ic_bloqueio->LinkCustomAttributes = "";
		$this->ic_bloqueio->HrefValue = "";
		$this->ic_bloqueio->TooltipValue = "";

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
				if ($this->nu_solMetricas->Exportable) $Doc->ExportCaption($this->nu_solMetricas);
				if ($this->nu_tpSolicitacao->Exportable) $Doc->ExportCaption($this->nu_tpSolicitacao);
				if ($this->nu_projeto->Exportable) $Doc->ExportCaption($this->nu_projeto);
				if ($this->no_atividadeMaeRedmine->Exportable) $Doc->ExportCaption($this->no_atividadeMaeRedmine);
				if ($this->ds_observacoes->Exportable) $Doc->ExportCaption($this->ds_observacoes);
				if ($this->ds_documentacaoAux->Exportable) $Doc->ExportCaption($this->ds_documentacaoAux);
				if ($this->ds_imapactoDb->Exportable) $Doc->ExportCaption($this->ds_imapactoDb);
				if ($this->ic_stSolicitacao->Exportable) $Doc->ExportCaption($this->ic_stSolicitacao);
				if ($this->nu_usuarioAlterou->Exportable) $Doc->ExportCaption($this->nu_usuarioAlterou);
				if ($this->dh_alteracao->Exportable) $Doc->ExportCaption($this->dh_alteracao);
				if ($this->nu_usuarioIncluiu->Exportable) $Doc->ExportCaption($this->nu_usuarioIncluiu);
				if ($this->dh_inclusao->Exportable) $Doc->ExportCaption($this->dh_inclusao);
				if ($this->dt_stSolicitacao->Exportable) $Doc->ExportCaption($this->dt_stSolicitacao);
				if ($this->vr_pfContForn->Exportable) $Doc->ExportCaption($this->vr_pfContForn);
				if ($this->nu_tpMetrica->Exportable) $Doc->ExportCaption($this->nu_tpMetrica);
				if ($this->ds_observacoesContForn->Exportable) $Doc->ExportCaption($this->ds_observacoesContForn);
				if ($this->im_anexosContForn->Exportable) $Doc->ExportCaption($this->im_anexosContForn);
				if ($this->nu_contagemAnt->Exportable) $Doc->ExportCaption($this->nu_contagemAnt);
				if ($this->ds_observaocoesContAnt->Exportable) $Doc->ExportCaption($this->ds_observaocoesContAnt);
				if ($this->im_anexosContAnt->Exportable) $Doc->ExportCaption($this->im_anexosContAnt);
			} else {
				if ($this->nu_solMetricas->Exportable) $Doc->ExportCaption($this->nu_solMetricas);
				if ($this->nu_tpSolicitacao->Exportable) $Doc->ExportCaption($this->nu_tpSolicitacao);
				if ($this->nu_projeto->Exportable) $Doc->ExportCaption($this->nu_projeto);
				if ($this->no_atividadeMaeRedmine->Exportable) $Doc->ExportCaption($this->no_atividadeMaeRedmine);
				if ($this->ds_observacoes->Exportable) $Doc->ExportCaption($this->ds_observacoes);
				if ($this->ds_documentacaoAux->Exportable) $Doc->ExportCaption($this->ds_documentacaoAux);
				if ($this->ds_imapactoDb->Exportable) $Doc->ExportCaption($this->ds_imapactoDb);
				if ($this->ic_stSolicitacao->Exportable) $Doc->ExportCaption($this->ic_stSolicitacao);
				if ($this->nu_usuarioAlterou->Exportable) $Doc->ExportCaption($this->nu_usuarioAlterou);
				if ($this->dh_alteracao->Exportable) $Doc->ExportCaption($this->dh_alteracao);
				if ($this->nu_usuarioIncluiu->Exportable) $Doc->ExportCaption($this->nu_usuarioIncluiu);
				if ($this->dh_inclusao->Exportable) $Doc->ExportCaption($this->dh_inclusao);
				if ($this->dt_stSolicitacao->Exportable) $Doc->ExportCaption($this->dt_stSolicitacao);
				if ($this->qt_pfTotal->Exportable) $Doc->ExportCaption($this->qt_pfTotal);
				if ($this->vr_pfContForn->Exportable) $Doc->ExportCaption($this->vr_pfContForn);
				if ($this->nu_tpMetrica->Exportable) $Doc->ExportCaption($this->nu_tpMetrica);
				if ($this->ds_observacoesContForn->Exportable) $Doc->ExportCaption($this->ds_observacoesContForn);
				if ($this->im_anexosContForn->Exportable) $Doc->ExportCaption($this->im_anexosContForn);
				if ($this->nu_contagemAnt->Exportable) $Doc->ExportCaption($this->nu_contagemAnt);
				if ($this->ds_observaocoesContAnt->Exportable) $Doc->ExportCaption($this->ds_observaocoesContAnt);
				if ($this->im_anexosContAnt->Exportable) $Doc->ExportCaption($this->im_anexosContAnt);
				if ($this->ic_bloqueio->Exportable) $Doc->ExportCaption($this->ic_bloqueio);
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
					if ($this->nu_solMetricas->Exportable) $Doc->ExportField($this->nu_solMetricas);
					if ($this->nu_tpSolicitacao->Exportable) $Doc->ExportField($this->nu_tpSolicitacao);
					if ($this->nu_projeto->Exportable) $Doc->ExportField($this->nu_projeto);
					if ($this->no_atividadeMaeRedmine->Exportable) $Doc->ExportField($this->no_atividadeMaeRedmine);
					if ($this->ds_observacoes->Exportable) $Doc->ExportField($this->ds_observacoes);
					if ($this->ds_documentacaoAux->Exportable) $Doc->ExportField($this->ds_documentacaoAux);
					if ($this->ds_imapactoDb->Exportable) $Doc->ExportField($this->ds_imapactoDb);
					if ($this->ic_stSolicitacao->Exportable) $Doc->ExportField($this->ic_stSolicitacao);
					if ($this->nu_usuarioAlterou->Exportable) $Doc->ExportField($this->nu_usuarioAlterou);
					if ($this->dh_alteracao->Exportable) $Doc->ExportField($this->dh_alteracao);
					if ($this->nu_usuarioIncluiu->Exportable) $Doc->ExportField($this->nu_usuarioIncluiu);
					if ($this->dh_inclusao->Exportable) $Doc->ExportField($this->dh_inclusao);
					if ($this->dt_stSolicitacao->Exportable) $Doc->ExportField($this->dt_stSolicitacao);
					if ($this->vr_pfContForn->Exportable) $Doc->ExportField($this->vr_pfContForn);
					if ($this->nu_tpMetrica->Exportable) $Doc->ExportField($this->nu_tpMetrica);
					if ($this->ds_observacoesContForn->Exportable) $Doc->ExportField($this->ds_observacoesContForn);
					if ($this->im_anexosContForn->Exportable) $Doc->ExportField($this->im_anexosContForn);
					if ($this->nu_contagemAnt->Exportable) $Doc->ExportField($this->nu_contagemAnt);
					if ($this->ds_observaocoesContAnt->Exportable) $Doc->ExportField($this->ds_observaocoesContAnt);
					if ($this->im_anexosContAnt->Exportable) $Doc->ExportField($this->im_anexosContAnt);
				} else {
					if ($this->nu_solMetricas->Exportable) $Doc->ExportField($this->nu_solMetricas);
					if ($this->nu_tpSolicitacao->Exportable) $Doc->ExportField($this->nu_tpSolicitacao);
					if ($this->nu_projeto->Exportable) $Doc->ExportField($this->nu_projeto);
					if ($this->no_atividadeMaeRedmine->Exportable) $Doc->ExportField($this->no_atividadeMaeRedmine);
					if ($this->ds_observacoes->Exportable) $Doc->ExportField($this->ds_observacoes);
					if ($this->ds_documentacaoAux->Exportable) $Doc->ExportField($this->ds_documentacaoAux);
					if ($this->ds_imapactoDb->Exportable) $Doc->ExportField($this->ds_imapactoDb);
					if ($this->ic_stSolicitacao->Exportable) $Doc->ExportField($this->ic_stSolicitacao);
					if ($this->nu_usuarioAlterou->Exportable) $Doc->ExportField($this->nu_usuarioAlterou);
					if ($this->dh_alteracao->Exportable) $Doc->ExportField($this->dh_alteracao);
					if ($this->nu_usuarioIncluiu->Exportable) $Doc->ExportField($this->nu_usuarioIncluiu);
					if ($this->dh_inclusao->Exportable) $Doc->ExportField($this->dh_inclusao);
					if ($this->dt_stSolicitacao->Exportable) $Doc->ExportField($this->dt_stSolicitacao);
					if ($this->qt_pfTotal->Exportable) $Doc->ExportField($this->qt_pfTotal);
					if ($this->vr_pfContForn->Exportable) $Doc->ExportField($this->vr_pfContForn);
					if ($this->nu_tpMetrica->Exportable) $Doc->ExportField($this->nu_tpMetrica);
					if ($this->ds_observacoesContForn->Exportable) $Doc->ExportField($this->ds_observacoesContForn);
					if ($this->im_anexosContForn->Exportable) $Doc->ExportField($this->im_anexosContForn);
					if ($this->nu_contagemAnt->Exportable) $Doc->ExportField($this->nu_contagemAnt);
					if ($this->ds_observaocoesContAnt->Exportable) $Doc->ExportField($this->ds_observaocoesContAnt);
					if ($this->im_anexosContAnt->Exportable) $Doc->ExportField($this->im_anexosContAnt);
					if ($this->ic_bloqueio->Exportable) $Doc->ExportField($this->ic_bloqueio);
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

		if ($this->ic_stSolicitacao->ViewValue == "Cancelada") {
			$this->RowAttrs["style"] = "color: red";
		}
		if ($this->ic_stSolicitacao->ViewValue == "Aberta") {
			$this->RowAttrs["style"] = "background-color: #ccffcc";
		}       
		if ($this->ic_stSolicitacao->ViewValue == "Em andamento") {
			$this->RowAttrs["style"] = "background-color: #ccffcc";  
		}  
	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
