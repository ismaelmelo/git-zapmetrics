<?php

// Global variable for table object
$estimativa = NULL;

//
// Table class for estimativa
//
class cestimativa extends cTable {
	var $nu_solMetricas;
	var $nu_estimativa;
	var $ic_solicitacaoCritica;
	var $nu_ambienteMaisRepresentativo;
	var $qt_tamBase;
	var $ic_modeloCocomo;
	var $nu_metPrazo;
	var $vr_doPf;
	var $pz_estimadoMeses;
	var $pz_estimadoDias;
	var $vr_ipMaximo;
	var $vr_ipMedio;
	var $vr_ipMinimo;
	var $vr_ipInformado;
	var $qt_esforco;
	var $vr_custoDesenv;
	var $vr_outrosCustos;
	var $vr_custoTotal;
	var $qt_tamBaseFaturamento;
	var $qt_recursosEquipe;
	var $ds_observacoes;
	var $ic_bloqueio;
	var $nu_altRELY;
	var $nu_altDATA;
	var $nu_altCPLX1;
	var $nu_altCPLX2;
	var $nu_altCPLX3;
	var $nu_altCPLX4;
	var $nu_altCPLX5;
	var $nu_altDOCU;
	var $nu_altRUSE;
	var $nu_altTIME;
	var $nu_altSTOR;
	var $nu_altPVOL;
	var $nu_altACAP;
	var $nu_altPCAP;
	var $nu_altPCON;
	var $nu_altAPEX;
	var $nu_altPLEX;
	var $nu_altLTEX;
	var $nu_altTOOL;
	var $nu_altSITE;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'estimativa';
		$this->TableName = 'estimativa';
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
		$this->nu_solMetricas = new cField('estimativa', 'estimativa', 'x_nu_solMetricas', 'nu_solMetricas', '[nu_solMetricas]', 'CAST([nu_solMetricas] AS NVARCHAR)', 3, -1, FALSE, '[nu_solMetricas]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_solMetricas->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_solMetricas'] = &$this->nu_solMetricas;

		// nu_estimativa
		$this->nu_estimativa = new cField('estimativa', 'estimativa', 'x_nu_estimativa', 'nu_estimativa', '[nu_estimativa]', 'CAST([nu_estimativa] AS NVARCHAR)', 3, -1, FALSE, '[nu_estimativa]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_estimativa->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_estimativa'] = &$this->nu_estimativa;

		// ic_solicitacaoCritica
		$this->ic_solicitacaoCritica = new cField('estimativa', 'estimativa', 'x_ic_solicitacaoCritica', 'ic_solicitacaoCritica', '[ic_solicitacaoCritica]', '[ic_solicitacaoCritica]', 129, -1, FALSE, '[ic_solicitacaoCritica]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_solicitacaoCritica'] = &$this->ic_solicitacaoCritica;

		// nu_ambienteMaisRepresentativo
		$this->nu_ambienteMaisRepresentativo = new cField('estimativa', 'estimativa', 'x_nu_ambienteMaisRepresentativo', 'nu_ambienteMaisRepresentativo', '[nu_ambienteMaisRepresentativo]', 'CAST([nu_ambienteMaisRepresentativo] AS NVARCHAR)', 3, -1, FALSE, '[nu_ambienteMaisRepresentativo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_ambienteMaisRepresentativo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_ambienteMaisRepresentativo'] = &$this->nu_ambienteMaisRepresentativo;

		// qt_tamBase
		$this->qt_tamBase = new cField('estimativa', 'estimativa', 'x_qt_tamBase', 'qt_tamBase', '[qt_tamBase]', 'CAST([qt_tamBase] AS NVARCHAR)', 131, -1, FALSE, '[qt_tamBase]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->qt_tamBase->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['qt_tamBase'] = &$this->qt_tamBase;

		// ic_modeloCocomo
		$this->ic_modeloCocomo = new cField('estimativa', 'estimativa', 'x_ic_modeloCocomo', 'ic_modeloCocomo', '[ic_modeloCocomo]', '[ic_modeloCocomo]', 129, -1, FALSE, '[ic_modeloCocomo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_modeloCocomo'] = &$this->ic_modeloCocomo;

		// nu_metPrazo
		$this->nu_metPrazo = new cField('estimativa', 'estimativa', 'x_nu_metPrazo', 'nu_metPrazo', '[nu_metPrazo]', 'CAST([nu_metPrazo] AS NVARCHAR)', 3, -1, FALSE, '[nu_metPrazo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_metPrazo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_metPrazo'] = &$this->nu_metPrazo;

		// vr_doPf
		$this->vr_doPf = new cField('estimativa', 'estimativa', 'x_vr_doPf', 'vr_doPf', '[vr_doPf]', 'CAST([vr_doPf] AS NVARCHAR)', 3, -1, FALSE, '[vr_doPf]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->vr_doPf->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['vr_doPf'] = &$this->vr_doPf;

		// pz_estimadoMeses
		$this->pz_estimadoMeses = new cField('estimativa', 'estimativa', 'x_pz_estimadoMeses', 'pz_estimadoMeses', '[pz_estimadoMeses]', 'CAST([pz_estimadoMeses] AS NVARCHAR)', 131, -1, FALSE, '[pz_estimadoMeses]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->pz_estimadoMeses->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['pz_estimadoMeses'] = &$this->pz_estimadoMeses;

		// pz_estimadoDias
		$this->pz_estimadoDias = new cField('estimativa', 'estimativa', 'x_pz_estimadoDias', 'pz_estimadoDias', '[pz_estimadoDias]', 'CAST([pz_estimadoDias] AS NVARCHAR)', 131, -1, FALSE, '[pz_estimadoDias]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->pz_estimadoDias->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['pz_estimadoDias'] = &$this->pz_estimadoDias;

		// vr_ipMaximo
		$this->vr_ipMaximo = new cField('estimativa', 'estimativa', 'x_vr_ipMaximo', 'vr_ipMaximo', '[vr_ipMaximo]', 'CAST([vr_ipMaximo] AS NVARCHAR)', 131, -1, FALSE, '[vr_ipMaximo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->vr_ipMaximo->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['vr_ipMaximo'] = &$this->vr_ipMaximo;

		// vr_ipMedio
		$this->vr_ipMedio = new cField('estimativa', 'estimativa', 'x_vr_ipMedio', 'vr_ipMedio', '[vr_ipMedio]', 'CAST([vr_ipMedio] AS NVARCHAR)', 131, -1, FALSE, '[vr_ipMedio]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->vr_ipMedio->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['vr_ipMedio'] = &$this->vr_ipMedio;

		// vr_ipMinimo
		$this->vr_ipMinimo = new cField('estimativa', 'estimativa', 'x_vr_ipMinimo', 'vr_ipMinimo', '[vr_ipMinimo]', 'CAST([vr_ipMinimo] AS NVARCHAR)', 131, -1, FALSE, '[vr_ipMinimo]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->vr_ipMinimo->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['vr_ipMinimo'] = &$this->vr_ipMinimo;

		// vr_ipInformado
		$this->vr_ipInformado = new cField('estimativa', 'estimativa', 'x_vr_ipInformado', 'vr_ipInformado', '[vr_ipInformado]', 'CAST([vr_ipInformado] AS NVARCHAR)', 3, -1, FALSE, '[vr_ipInformado]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->vr_ipInformado->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['vr_ipInformado'] = &$this->vr_ipInformado;

		// qt_esforco
		$this->qt_esforco = new cField('estimativa', 'estimativa', 'x_qt_esforco', 'qt_esforco', '[qt_esforco]', 'CAST([qt_esforco] AS NVARCHAR)', 131, -1, FALSE, '[qt_esforco]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->qt_esforco->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['qt_esforco'] = &$this->qt_esforco;

		// vr_custoDesenv
		$this->vr_custoDesenv = new cField('estimativa', 'estimativa', 'x_vr_custoDesenv', 'vr_custoDesenv', '[vr_custoDesenv]', 'CAST([vr_custoDesenv] AS NVARCHAR)', 131, -1, FALSE, '[vr_custoDesenv]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->vr_custoDesenv->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['vr_custoDesenv'] = &$this->vr_custoDesenv;

		// vr_outrosCustos
		$this->vr_outrosCustos = new cField('estimativa', 'estimativa', 'x_vr_outrosCustos', 'vr_outrosCustos', '[vr_outrosCustos]', 'CAST([vr_outrosCustos] AS NVARCHAR)', 131, -1, FALSE, '[vr_outrosCustos]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->vr_outrosCustos->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['vr_outrosCustos'] = &$this->vr_outrosCustos;

		// vr_custoTotal
		$this->vr_custoTotal = new cField('estimativa', 'estimativa', 'x_vr_custoTotal', 'vr_custoTotal', '[vr_custoTotal]', 'CAST([vr_custoTotal] AS NVARCHAR)', 131, -1, FALSE, '[vr_custoTotal]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->vr_custoTotal->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['vr_custoTotal'] = &$this->vr_custoTotal;

		// qt_tamBaseFaturamento
		$this->qt_tamBaseFaturamento = new cField('estimativa', 'estimativa', 'x_qt_tamBaseFaturamento', 'qt_tamBaseFaturamento', '[qt_tamBaseFaturamento]', 'CAST([qt_tamBaseFaturamento] AS NVARCHAR)', 131, -1, FALSE, '[qt_tamBaseFaturamento]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->qt_tamBaseFaturamento->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['qt_tamBaseFaturamento'] = &$this->qt_tamBaseFaturamento;

		// qt_recursosEquipe
		$this->qt_recursosEquipe = new cField('estimativa', 'estimativa', 'x_qt_recursosEquipe', 'qt_recursosEquipe', '[qt_recursosEquipe]', 'CAST([qt_recursosEquipe] AS NVARCHAR)', 131, -1, FALSE, '[qt_recursosEquipe]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->qt_recursosEquipe->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['qt_recursosEquipe'] = &$this->qt_recursosEquipe;

		// ds_observacoes
		$this->ds_observacoes = new cField('estimativa', 'estimativa', 'x_ds_observacoes', 'ds_observacoes', '[ds_observacoes]', '[ds_observacoes]', 201, -1, FALSE, '[ds_observacoes]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ds_observacoes'] = &$this->ds_observacoes;

		// ic_bloqueio
		$this->ic_bloqueio = new cField('estimativa', 'estimativa', 'x_ic_bloqueio', 'ic_bloqueio', '[ic_bloqueio]', '[ic_bloqueio]', 129, -1, FALSE, '[ic_bloqueio]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_bloqueio'] = &$this->ic_bloqueio;

		// nu_altRELY
		$this->nu_altRELY = new cField('estimativa', 'estimativa', 'x_nu_altRELY', 'nu_altRELY', '[nu_altRELY]', 'CAST([nu_altRELY] AS NVARCHAR)', 3, -1, FALSE, '[nu_altRELY]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altRELY->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altRELY'] = &$this->nu_altRELY;

		// nu_altDATA
		$this->nu_altDATA = new cField('estimativa', 'estimativa', 'x_nu_altDATA', 'nu_altDATA', '[nu_altDATA]', 'CAST([nu_altDATA] AS NVARCHAR)', 3, -1, FALSE, '[nu_altDATA]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altDATA->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altDATA'] = &$this->nu_altDATA;

		// nu_altCPLX1
		$this->nu_altCPLX1 = new cField('estimativa', 'estimativa', 'x_nu_altCPLX1', 'nu_altCPLX1', '[nu_altCPLX1]', 'CAST([nu_altCPLX1] AS NVARCHAR)', 3, -1, FALSE, '[nu_altCPLX1]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altCPLX1->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altCPLX1'] = &$this->nu_altCPLX1;

		// nu_altCPLX2
		$this->nu_altCPLX2 = new cField('estimativa', 'estimativa', 'x_nu_altCPLX2', 'nu_altCPLX2', '[nu_altCPLX2]', 'CAST([nu_altCPLX2] AS NVARCHAR)', 3, -1, FALSE, '[nu_altCPLX2]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altCPLX2->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altCPLX2'] = &$this->nu_altCPLX2;

		// nu_altCPLX3
		$this->nu_altCPLX3 = new cField('estimativa', 'estimativa', 'x_nu_altCPLX3', 'nu_altCPLX3', '[nu_altCPLX3]', 'CAST([nu_altCPLX3] AS NVARCHAR)', 3, -1, FALSE, '[nu_altCPLX3]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altCPLX3->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altCPLX3'] = &$this->nu_altCPLX3;

		// nu_altCPLX4
		$this->nu_altCPLX4 = new cField('estimativa', 'estimativa', 'x_nu_altCPLX4', 'nu_altCPLX4', '[nu_altCPLX4]', 'CAST([nu_altCPLX4] AS NVARCHAR)', 3, -1, FALSE, '[nu_altCPLX4]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altCPLX4->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altCPLX4'] = &$this->nu_altCPLX4;

		// nu_altCPLX5
		$this->nu_altCPLX5 = new cField('estimativa', 'estimativa', 'x_nu_altCPLX5', 'nu_altCPLX5', '[nu_altCPLX5]', 'CAST([nu_altCPLX5] AS NVARCHAR)', 3, -1, FALSE, '[nu_altCPLX5]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altCPLX5->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altCPLX5'] = &$this->nu_altCPLX5;

		// nu_altDOCU
		$this->nu_altDOCU = new cField('estimativa', 'estimativa', 'x_nu_altDOCU', 'nu_altDOCU', '[nu_altDOCU]', 'CAST([nu_altDOCU] AS NVARCHAR)', 3, -1, FALSE, '[nu_altDOCU]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altDOCU->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altDOCU'] = &$this->nu_altDOCU;

		// nu_altRUSE
		$this->nu_altRUSE = new cField('estimativa', 'estimativa', 'x_nu_altRUSE', 'nu_altRUSE', '[nu_altRUSE]', 'CAST([nu_altRUSE] AS NVARCHAR)', 3, -1, FALSE, '[nu_altRUSE]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altRUSE->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altRUSE'] = &$this->nu_altRUSE;

		// nu_altTIME
		$this->nu_altTIME = new cField('estimativa', 'estimativa', 'x_nu_altTIME', 'nu_altTIME', '[nu_altTIME]', 'CAST([nu_altTIME] AS NVARCHAR)', 3, -1, FALSE, '[nu_altTIME]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altTIME->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altTIME'] = &$this->nu_altTIME;

		// nu_altSTOR
		$this->nu_altSTOR = new cField('estimativa', 'estimativa', 'x_nu_altSTOR', 'nu_altSTOR', '[nu_altSTOR]', 'CAST([nu_altSTOR] AS NVARCHAR)', 3, -1, FALSE, '[nu_altSTOR]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altSTOR->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altSTOR'] = &$this->nu_altSTOR;

		// nu_altPVOL
		$this->nu_altPVOL = new cField('estimativa', 'estimativa', 'x_nu_altPVOL', 'nu_altPVOL', '[nu_altPVOL]', 'CAST([nu_altPVOL] AS NVARCHAR)', 3, -1, FALSE, '[nu_altPVOL]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altPVOL->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altPVOL'] = &$this->nu_altPVOL;

		// nu_altACAP
		$this->nu_altACAP = new cField('estimativa', 'estimativa', 'x_nu_altACAP', 'nu_altACAP', '[nu_altACAP]', 'CAST([nu_altACAP] AS NVARCHAR)', 3, -1, FALSE, '[nu_altACAP]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altACAP->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altACAP'] = &$this->nu_altACAP;

		// nu_altPCAP
		$this->nu_altPCAP = new cField('estimativa', 'estimativa', 'x_nu_altPCAP', 'nu_altPCAP', '[nu_altPCAP]', 'CAST([nu_altPCAP] AS NVARCHAR)', 3, -1, FALSE, '[nu_altPCAP]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altPCAP->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altPCAP'] = &$this->nu_altPCAP;

		// nu_altPCON
		$this->nu_altPCON = new cField('estimativa', 'estimativa', 'x_nu_altPCON', 'nu_altPCON', '[nu_altPCON]', 'CAST([nu_altPCON] AS NVARCHAR)', 3, -1, FALSE, '[nu_altPCON]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altPCON->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altPCON'] = &$this->nu_altPCON;

		// nu_altAPEX
		$this->nu_altAPEX = new cField('estimativa', 'estimativa', 'x_nu_altAPEX', 'nu_altAPEX', '[nu_altAPEX]', 'CAST([nu_altAPEX] AS NVARCHAR)', 3, -1, FALSE, '[nu_altAPEX]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altAPEX->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altAPEX'] = &$this->nu_altAPEX;

		// nu_altPLEX
		$this->nu_altPLEX = new cField('estimativa', 'estimativa', 'x_nu_altPLEX', 'nu_altPLEX', '[nu_altPLEX]', 'CAST([nu_altPLEX] AS NVARCHAR)', 3, -1, FALSE, '[nu_altPLEX]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altPLEX->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altPLEX'] = &$this->nu_altPLEX;

		// nu_altLTEX
		$this->nu_altLTEX = new cField('estimativa', 'estimativa', 'x_nu_altLTEX', 'nu_altLTEX', '[nu_altLTEX]', 'CAST([nu_altLTEX] AS NVARCHAR)', 3, -1, FALSE, '[nu_altLTEX]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altLTEX->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altLTEX'] = &$this->nu_altLTEX;

		// nu_altTOOL
		$this->nu_altTOOL = new cField('estimativa', 'estimativa', 'x_nu_altTOOL', 'nu_altTOOL', '[nu_altTOOL]', 'CAST([nu_altTOOL] AS NVARCHAR)', 3, -1, FALSE, '[nu_altTOOL]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altTOOL->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altTOOL'] = &$this->nu_altTOOL;

		// nu_altSITE
		$this->nu_altSITE = new cField('estimativa', 'estimativa', 'x_nu_altSITE', 'nu_altSITE', '[nu_altSITE]', 'CAST([nu_altSITE] AS NVARCHAR)', 3, -1, FALSE, '[nu_altSITE]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altSITE->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altSITE'] = &$this->nu_altSITE;
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

	// Current master table name
	function getCurrentMasterTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE];
	}

	function setCurrentMasterTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE] = $v;
	}

	// Session master WHERE clause
	function GetMasterFilter() {

		// Master filter
		$sMasterFilter = "";
		if ($this->getCurrentMasterTable() == "solicitacaoMetricas") {
			if ($this->nu_solMetricas->getSessionValue() <> "")
				$sMasterFilter .= "[nu_solMetricas]=" . ew_QuotedValue($this->nu_solMetricas->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "solicitacaoMetricas") {
			if ($this->nu_solMetricas->getSessionValue() <> "")
				$sDetailFilter .= "[nu_solMetricas]=" . ew_QuotedValue($this->nu_solMetricas->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_solicitacaoMetricas() {
		return "[nu_solMetricas]=@nu_solMetricas@";
	}

	// Detail filter
	function SqlDetailFilter_solicitacaoMetricas() {
		return "[nu_solMetricas]=@nu_solMetricas@";
	}

	// Table level SQL
	function SqlFrom() { // From
		return "[dbo].[estimativa]";
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
		return "[nu_estimativa] DESC";
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
	var $UpdateTable = "[dbo].[estimativa]";

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
			if (array_key_exists('nu_estimativa', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_estimativa') . '=' . ew_QuotedValue($rs['nu_estimativa'], $this->nu_estimativa->FldDataType));
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
		return "[nu_estimativa] = @nu_estimativa@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nu_estimativa->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_estimativa@", ew_AdjustSql($this->nu_estimativa->CurrentValue), $sKeyFilter); // Replace key value
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
			return "estimativalist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "estimativalist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("estimativaview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("estimativaview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "estimativaadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("estimativaedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("estimativaadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("estimativadelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nu_estimativa->CurrentValue)) {
			$sUrl .= "nu_estimativa=" . urlencode($this->nu_estimativa->CurrentValue);
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
			$arKeys[] = @$_GET["nu_estimativa"]; // nu_estimativa

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
			$this->nu_estimativa->CurrentValue = $key;
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
		$this->nu_estimativa->setDbValue($rs->fields('nu_estimativa'));
		$this->ic_solicitacaoCritica->setDbValue($rs->fields('ic_solicitacaoCritica'));
		$this->nu_ambienteMaisRepresentativo->setDbValue($rs->fields('nu_ambienteMaisRepresentativo'));
		$this->qt_tamBase->setDbValue($rs->fields('qt_tamBase'));
		$this->ic_modeloCocomo->setDbValue($rs->fields('ic_modeloCocomo'));
		$this->nu_metPrazo->setDbValue($rs->fields('nu_metPrazo'));
		$this->vr_doPf->setDbValue($rs->fields('vr_doPf'));
		$this->pz_estimadoMeses->setDbValue($rs->fields('pz_estimadoMeses'));
		$this->pz_estimadoDias->setDbValue($rs->fields('pz_estimadoDias'));
		$this->vr_ipMaximo->setDbValue($rs->fields('vr_ipMaximo'));
		$this->vr_ipMedio->setDbValue($rs->fields('vr_ipMedio'));
		$this->vr_ipMinimo->setDbValue($rs->fields('vr_ipMinimo'));
		$this->vr_ipInformado->setDbValue($rs->fields('vr_ipInformado'));
		$this->qt_esforco->setDbValue($rs->fields('qt_esforco'));
		$this->vr_custoDesenv->setDbValue($rs->fields('vr_custoDesenv'));
		$this->vr_outrosCustos->setDbValue($rs->fields('vr_outrosCustos'));
		$this->vr_custoTotal->setDbValue($rs->fields('vr_custoTotal'));
		$this->qt_tamBaseFaturamento->setDbValue($rs->fields('qt_tamBaseFaturamento'));
		$this->qt_recursosEquipe->setDbValue($rs->fields('qt_recursosEquipe'));
		$this->ds_observacoes->setDbValue($rs->fields('ds_observacoes'));
		$this->ic_bloqueio->setDbValue($rs->fields('ic_bloqueio'));
		$this->nu_altRELY->setDbValue($rs->fields('nu_altRELY'));
		$this->nu_altDATA->setDbValue($rs->fields('nu_altDATA'));
		$this->nu_altCPLX1->setDbValue($rs->fields('nu_altCPLX1'));
		$this->nu_altCPLX2->setDbValue($rs->fields('nu_altCPLX2'));
		$this->nu_altCPLX3->setDbValue($rs->fields('nu_altCPLX3'));
		$this->nu_altCPLX4->setDbValue($rs->fields('nu_altCPLX4'));
		$this->nu_altCPLX5->setDbValue($rs->fields('nu_altCPLX5'));
		$this->nu_altDOCU->setDbValue($rs->fields('nu_altDOCU'));
		$this->nu_altRUSE->setDbValue($rs->fields('nu_altRUSE'));
		$this->nu_altTIME->setDbValue($rs->fields('nu_altTIME'));
		$this->nu_altSTOR->setDbValue($rs->fields('nu_altSTOR'));
		$this->nu_altPVOL->setDbValue($rs->fields('nu_altPVOL'));
		$this->nu_altACAP->setDbValue($rs->fields('nu_altACAP'));
		$this->nu_altPCAP->setDbValue($rs->fields('nu_altPCAP'));
		$this->nu_altPCON->setDbValue($rs->fields('nu_altPCON'));
		$this->nu_altAPEX->setDbValue($rs->fields('nu_altAPEX'));
		$this->nu_altPLEX->setDbValue($rs->fields('nu_altPLEX'));
		$this->nu_altLTEX->setDbValue($rs->fields('nu_altLTEX'));
		$this->nu_altTOOL->setDbValue($rs->fields('nu_altTOOL'));
		$this->nu_altSITE->setDbValue($rs->fields('nu_altSITE'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_solMetricas
		// nu_estimativa
		// ic_solicitacaoCritica
		// nu_ambienteMaisRepresentativo
		// qt_tamBase
		// ic_modeloCocomo
		// nu_metPrazo
		// vr_doPf
		// pz_estimadoMeses
		// pz_estimadoDias
		// vr_ipMaximo
		// vr_ipMedio
		// vr_ipMinimo
		// vr_ipInformado
		// qt_esforco
		// vr_custoDesenv
		// vr_outrosCustos
		// vr_custoTotal
		// qt_tamBaseFaturamento
		// qt_recursosEquipe
		// ds_observacoes
		// ic_bloqueio

		$this->ic_bloqueio->CellCssStyle = "white-space: nowrap;";

		// nu_altRELY
		// nu_altDATA
		// nu_altCPLX1
		// nu_altCPLX2
		// nu_altCPLX3
		// nu_altCPLX4
		// nu_altCPLX5
		// nu_altDOCU
		// nu_altRUSE
		// nu_altTIME
		// nu_altSTOR
		// nu_altPVOL
		// nu_altACAP
		// nu_altPCAP
		// nu_altPCON
		// nu_altAPEX
		// nu_altPLEX
		// nu_altLTEX
		// nu_altTOOL
		// nu_altSITE
		// nu_solMetricas

		if (strval($this->nu_solMetricas->CurrentValue) <> "") {
			$sFilterWrk = "[nu_solMetricas]" . ew_SearchString("=", $this->nu_solMetricas->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_solMetricas], [nu_solMetricas] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[solicitacaoMetricas]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_solMetricas, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_solMetricas] DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_solMetricas->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_solMetricas->ViewValue = $this->nu_solMetricas->CurrentValue;
			}
		} else {
			$this->nu_solMetricas->ViewValue = NULL;
		}
		$this->nu_solMetricas->ViewCustomAttributes = "";

		// nu_estimativa
		$this->nu_estimativa->ViewValue = $this->nu_estimativa->CurrentValue;
		$this->nu_estimativa->ViewCustomAttributes = "";

		// ic_solicitacaoCritica
		if (strval($this->ic_solicitacaoCritica->CurrentValue) <> "") {
			switch ($this->ic_solicitacaoCritica->CurrentValue) {
				case $this->ic_solicitacaoCritica->FldTagValue(1):
					$this->ic_solicitacaoCritica->ViewValue = $this->ic_solicitacaoCritica->FldTagCaption(1) <> "" ? $this->ic_solicitacaoCritica->FldTagCaption(1) : $this->ic_solicitacaoCritica->CurrentValue;
					break;
				case $this->ic_solicitacaoCritica->FldTagValue(2):
					$this->ic_solicitacaoCritica->ViewValue = $this->ic_solicitacaoCritica->FldTagCaption(2) <> "" ? $this->ic_solicitacaoCritica->FldTagCaption(2) : $this->ic_solicitacaoCritica->CurrentValue;
					break;
				default:
					$this->ic_solicitacaoCritica->ViewValue = $this->ic_solicitacaoCritica->CurrentValue;
			}
		} else {
			$this->ic_solicitacaoCritica->ViewValue = NULL;
		}
		$this->ic_solicitacaoCritica->ViewCustomAttributes = "";

		// nu_ambienteMaisRepresentativo
		$this->nu_ambienteMaisRepresentativo->ViewValue = $this->nu_ambienteMaisRepresentativo->CurrentValue;
		if (strval($this->nu_ambienteMaisRepresentativo->CurrentValue) <> "") {
			$sFilterWrk = "[nu_ambiente]" . ew_SearchString("=", $this->nu_ambienteMaisRepresentativo->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_ambiente], [no_ambiente] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ambiente]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_ambienteMaisRepresentativo, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_ambiente] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_ambienteMaisRepresentativo->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_ambienteMaisRepresentativo->ViewValue = $this->nu_ambienteMaisRepresentativo->CurrentValue;
			}
		} else {
			$this->nu_ambienteMaisRepresentativo->ViewValue = NULL;
		}
		$this->nu_ambienteMaisRepresentativo->ViewCustomAttributes = "";

		// qt_tamBase
		$this->qt_tamBase->ViewValue = $this->qt_tamBase->CurrentValue;
		$this->qt_tamBase->ViewCustomAttributes = "";

		// ic_modeloCocomo
		if (strval($this->ic_modeloCocomo->CurrentValue) <> "") {
			switch ($this->ic_modeloCocomo->CurrentValue) {
				case $this->ic_modeloCocomo->FldTagValue(1):
					$this->ic_modeloCocomo->ViewValue = $this->ic_modeloCocomo->FldTagCaption(1) <> "" ? $this->ic_modeloCocomo->FldTagCaption(1) : $this->ic_modeloCocomo->CurrentValue;
					break;
				case $this->ic_modeloCocomo->FldTagValue(2):
					$this->ic_modeloCocomo->ViewValue = $this->ic_modeloCocomo->FldTagCaption(2) <> "" ? $this->ic_modeloCocomo->FldTagCaption(2) : $this->ic_modeloCocomo->CurrentValue;
					break;
				default:
					$this->ic_modeloCocomo->ViewValue = $this->ic_modeloCocomo->CurrentValue;
			}
		} else {
			$this->ic_modeloCocomo->ViewValue = NULL;
		}
		$this->ic_modeloCocomo->ViewCustomAttributes = "";

		// nu_metPrazo
		if (strval($this->nu_metPrazo->CurrentValue) <> "") {
			switch ($this->nu_metPrazo->CurrentValue) {
				case $this->nu_metPrazo->FldTagValue(1):
					$this->nu_metPrazo->ViewValue = $this->nu_metPrazo->FldTagCaption(1) <> "" ? $this->nu_metPrazo->FldTagCaption(1) : $this->nu_metPrazo->CurrentValue;
					break;
				case $this->nu_metPrazo->FldTagValue(2):
					$this->nu_metPrazo->ViewValue = $this->nu_metPrazo->FldTagCaption(2) <> "" ? $this->nu_metPrazo->FldTagCaption(2) : $this->nu_metPrazo->CurrentValue;
					break;
				case $this->nu_metPrazo->FldTagValue(3):
					$this->nu_metPrazo->ViewValue = $this->nu_metPrazo->FldTagCaption(3) <> "" ? $this->nu_metPrazo->FldTagCaption(3) : $this->nu_metPrazo->CurrentValue;
					break;
				case $this->nu_metPrazo->FldTagValue(4):
					$this->nu_metPrazo->ViewValue = $this->nu_metPrazo->FldTagCaption(4) <> "" ? $this->nu_metPrazo->FldTagCaption(4) : $this->nu_metPrazo->CurrentValue;
					break;
				case $this->nu_metPrazo->FldTagValue(5):
					$this->nu_metPrazo->ViewValue = $this->nu_metPrazo->FldTagCaption(5) <> "" ? $this->nu_metPrazo->FldTagCaption(5) : $this->nu_metPrazo->CurrentValue;
					break;
				default:
					$this->nu_metPrazo->ViewValue = $this->nu_metPrazo->CurrentValue;
			}
		} else {
			$this->nu_metPrazo->ViewValue = NULL;
		}
		$this->nu_metPrazo->ViewCustomAttributes = "";

		// vr_doPf
		$this->vr_doPf->ViewValue = $this->vr_doPf->CurrentValue;
		$this->vr_doPf->ViewCustomAttributes = "";

		// pz_estimadoMeses
		$this->pz_estimadoMeses->ViewValue = $this->pz_estimadoMeses->CurrentValue;
		$this->pz_estimadoMeses->ViewCustomAttributes = "";

		// pz_estimadoDias
		$this->pz_estimadoDias->ViewValue = $this->pz_estimadoDias->CurrentValue;
		$this->pz_estimadoDias->ViewCustomAttributes = "";

		// vr_ipMaximo
		$this->vr_ipMaximo->ViewValue = $this->vr_ipMaximo->CurrentValue;
		$this->vr_ipMaximo->ViewCustomAttributes = "";

		// vr_ipMedio
		$this->vr_ipMedio->ViewValue = $this->vr_ipMedio->CurrentValue;
		$this->vr_ipMedio->ViewCustomAttributes = "";

		// vr_ipMinimo
		$this->vr_ipMinimo->ViewValue = $this->vr_ipMinimo->CurrentValue;
		$this->vr_ipMinimo->ViewCustomAttributes = "";

		// vr_ipInformado
		$this->vr_ipInformado->ViewValue = $this->vr_ipInformado->CurrentValue;
		$this->vr_ipInformado->ViewCustomAttributes = "";

		// qt_esforco
		$this->qt_esforco->ViewValue = $this->qt_esforco->CurrentValue;
		$this->qt_esforco->ViewCustomAttributes = "";

		// vr_custoDesenv
		$this->vr_custoDesenv->ViewValue = $this->vr_custoDesenv->CurrentValue;
		$this->vr_custoDesenv->ViewCustomAttributes = "";

		// vr_outrosCustos
		$this->vr_outrosCustos->ViewValue = $this->vr_outrosCustos->CurrentValue;
		$this->vr_outrosCustos->ViewCustomAttributes = "";

		// vr_custoTotal
		$this->vr_custoTotal->ViewValue = $this->vr_custoTotal->CurrentValue;
		$this->vr_custoTotal->ViewCustomAttributes = "";

		// qt_tamBaseFaturamento
		$this->qt_tamBaseFaturamento->ViewValue = $this->qt_tamBaseFaturamento->CurrentValue;
		$this->qt_tamBaseFaturamento->ViewCustomAttributes = "";

		// qt_recursosEquipe
		$this->qt_recursosEquipe->ViewValue = $this->qt_recursosEquipe->CurrentValue;
		$this->qt_recursosEquipe->ViewCustomAttributes = "";

		// ds_observacoes
		$this->ds_observacoes->ViewValue = $this->ds_observacoes->CurrentValue;
		$this->ds_observacoes->ViewCustomAttributes = "";

		// ic_bloqueio
		$this->ic_bloqueio->ViewValue = $this->ic_bloqueio->CurrentValue;
		$this->ic_bloqueio->ViewCustomAttributes = "";

		// nu_altRELY
		if (strval($this->nu_altRELY->CurrentValue) <> "") {
			$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altRELY->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		$lookuptblfilter = "[co_questao]=(select co_quePREC FROM ambiente_valoracao where nu_ambiente = '2' and nu_versaoValoracao = '1') AND [ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altRELY, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altRELY->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_altRELY->ViewValue = $this->nu_altRELY->CurrentValue;
			}
		} else {
			$this->nu_altRELY->ViewValue = NULL;
		}
		$this->nu_altRELY->ViewCustomAttributes = "";

		// nu_altDATA
		if (strval($this->nu_altDATA->CurrentValue) <> "") {
			$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altDATA->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		$lookuptblfilter = "[co_questao]=(select co_queDATA FROM ambiente_valoracao where nu_ambiente = '2' and nu_versaoValoracao = '1') AND [ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altDATA, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altDATA->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_altDATA->ViewValue = $this->nu_altDATA->CurrentValue;
			}
		} else {
			$this->nu_altDATA->ViewValue = NULL;
		}
		$this->nu_altDATA->ViewCustomAttributes = "";

		// nu_altCPLX1
		if (strval($this->nu_altCPLX1->CurrentValue) <> "") {
			$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX1->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altCPLX1, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altCPLX1->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_altCPLX1->ViewValue = $this->nu_altCPLX1->CurrentValue;
			}
		} else {
			$this->nu_altCPLX1->ViewValue = NULL;
		}
		$this->nu_altCPLX1->ViewCustomAttributes = "";

		// nu_altCPLX2
		if (strval($this->nu_altCPLX2->CurrentValue) <> "") {
			$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX2->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altCPLX2, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altCPLX2->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_altCPLX2->ViewValue = $this->nu_altCPLX2->CurrentValue;
			}
		} else {
			$this->nu_altCPLX2->ViewValue = NULL;
		}
		$this->nu_altCPLX2->ViewCustomAttributes = "";

		// nu_altCPLX3
		if (strval($this->nu_altCPLX3->CurrentValue) <> "") {
			$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX3->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altCPLX3, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altCPLX3->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_altCPLX3->ViewValue = $this->nu_altCPLX3->CurrentValue;
			}
		} else {
			$this->nu_altCPLX3->ViewValue = NULL;
		}
		$this->nu_altCPLX3->ViewCustomAttributes = "";

		// nu_altCPLX4
		if (strval($this->nu_altCPLX4->CurrentValue) <> "") {
			$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX4->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altCPLX4, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altCPLX4->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_altCPLX4->ViewValue = $this->nu_altCPLX4->CurrentValue;
			}
		} else {
			$this->nu_altCPLX4->ViewValue = NULL;
		}
		$this->nu_altCPLX4->ViewCustomAttributes = "";

		// nu_altCPLX5
		if (strval($this->nu_altCPLX5->CurrentValue) <> "") {
			$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX5->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altCPLX5, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altCPLX5->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_altCPLX5->ViewValue = $this->nu_altCPLX5->CurrentValue;
			}
		} else {
			$this->nu_altCPLX5->ViewValue = NULL;
		}
		$this->nu_altCPLX5->ViewCustomAttributes = "";

		// nu_altDOCU
		if (strval($this->nu_altDOCU->CurrentValue) <> "") {
			$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altDOCU->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altDOCU, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altDOCU->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_altDOCU->ViewValue = $this->nu_altDOCU->CurrentValue;
			}
		} else {
			$this->nu_altDOCU->ViewValue = NULL;
		}
		$this->nu_altDOCU->ViewCustomAttributes = "";

		// nu_altRUSE
		if (strval($this->nu_altRUSE->CurrentValue) <> "") {
			$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altRUSE->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altRUSE, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altRUSE->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_altRUSE->ViewValue = $this->nu_altRUSE->CurrentValue;
			}
		} else {
			$this->nu_altRUSE->ViewValue = NULL;
		}
		$this->nu_altRUSE->ViewCustomAttributes = "";

		// nu_altTIME
		if (strval($this->nu_altTIME->CurrentValue) <> "") {
			$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altTIME->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altTIME, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altTIME->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_altTIME->ViewValue = $this->nu_altTIME->CurrentValue;
			}
		} else {
			$this->nu_altTIME->ViewValue = NULL;
		}
		$this->nu_altTIME->ViewCustomAttributes = "";

		// nu_altSTOR
		if (strval($this->nu_altSTOR->CurrentValue) <> "") {
			$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altSTOR->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altSTOR, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altSTOR->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_altSTOR->ViewValue = $this->nu_altSTOR->CurrentValue;
			}
		} else {
			$this->nu_altSTOR->ViewValue = NULL;
		}
		$this->nu_altSTOR->ViewCustomAttributes = "";

		// nu_altPVOL
		if (strval($this->nu_altPVOL->CurrentValue) <> "") {
			$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altPVOL->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altPVOL, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altPVOL->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_altPVOL->ViewValue = $this->nu_altPVOL->CurrentValue;
			}
		} else {
			$this->nu_altPVOL->ViewValue = NULL;
		}
		$this->nu_altPVOL->ViewCustomAttributes = "";

		// nu_altACAP
		if (strval($this->nu_altACAP->CurrentValue) <> "") {
			$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altACAP->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altACAP, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altACAP->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_altACAP->ViewValue = $this->nu_altACAP->CurrentValue;
			}
		} else {
			$this->nu_altACAP->ViewValue = NULL;
		}
		$this->nu_altACAP->ViewCustomAttributes = "";

		// nu_altPCAP
		if (strval($this->nu_altPCAP->CurrentValue) <> "") {
			$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altPCAP->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altPCAP, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altPCAP->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_altPCAP->ViewValue = $this->nu_altPCAP->CurrentValue;
			}
		} else {
			$this->nu_altPCAP->ViewValue = NULL;
		}
		$this->nu_altPCAP->ViewCustomAttributes = "";

		// nu_altPCON
		if (strval($this->nu_altPCON->CurrentValue) <> "") {
			$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altPCON->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altPCON, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altPCON->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_altPCON->ViewValue = $this->nu_altPCON->CurrentValue;
			}
		} else {
			$this->nu_altPCON->ViewValue = NULL;
		}
		$this->nu_altPCON->ViewCustomAttributes = "";

		// nu_altAPEX
		if (strval($this->nu_altAPEX->CurrentValue) <> "") {
			$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altAPEX->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altAPEX, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altAPEX->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_altAPEX->ViewValue = $this->nu_altAPEX->CurrentValue;
			}
		} else {
			$this->nu_altAPEX->ViewValue = NULL;
		}
		$this->nu_altAPEX->ViewCustomAttributes = "";

		// nu_altPLEX
		if (strval($this->nu_altPLEX->CurrentValue) <> "") {
			$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altPLEX->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altPLEX, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altPLEX->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_altPLEX->ViewValue = $this->nu_altPLEX->CurrentValue;
			}
		} else {
			$this->nu_altPLEX->ViewValue = NULL;
		}
		$this->nu_altPLEX->ViewCustomAttributes = "";

		// nu_altLTEX
		if (strval($this->nu_altLTEX->CurrentValue) <> "") {
			$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altLTEX->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altLTEX, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altLTEX->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_altLTEX->ViewValue = $this->nu_altLTEX->CurrentValue;
			}
		} else {
			$this->nu_altLTEX->ViewValue = NULL;
		}
		$this->nu_altLTEX->ViewCustomAttributes = "";

		// nu_altTOOL
		if (strval($this->nu_altTOOL->CurrentValue) <> "") {
			$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altTOOL->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altTOOL, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altTOOL->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_altTOOL->ViewValue = $this->nu_altTOOL->CurrentValue;
			}
		} else {
			$this->nu_altTOOL->ViewValue = NULL;
		}
		$this->nu_altTOOL->ViewCustomAttributes = "";

		// nu_altSITE
		if (strval($this->nu_altSITE->CurrentValue) <> "") {
			$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altSITE->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altSITE, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altSITE->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_altSITE->ViewValue = $this->nu_altSITE->CurrentValue;
			}
		} else {
			$this->nu_altSITE->ViewValue = NULL;
		}
		$this->nu_altSITE->ViewCustomAttributes = "";

		// nu_solMetricas
		$this->nu_solMetricas->LinkCustomAttributes = "";
		$this->nu_solMetricas->HrefValue = "";
		$this->nu_solMetricas->TooltipValue = "";

		// nu_estimativa
		$this->nu_estimativa->LinkCustomAttributes = "";
		$this->nu_estimativa->HrefValue = "";
		$this->nu_estimativa->TooltipValue = "";

		// ic_solicitacaoCritica
		$this->ic_solicitacaoCritica->LinkCustomAttributes = "";
		$this->ic_solicitacaoCritica->HrefValue = "";
		$this->ic_solicitacaoCritica->TooltipValue = "";

		// nu_ambienteMaisRepresentativo
		$this->nu_ambienteMaisRepresentativo->LinkCustomAttributes = "";
		$this->nu_ambienteMaisRepresentativo->HrefValue = "";
		$this->nu_ambienteMaisRepresentativo->TooltipValue = "";

		// qt_tamBase
		$this->qt_tamBase->LinkCustomAttributes = "";
		$this->qt_tamBase->HrefValue = "";
		$this->qt_tamBase->TooltipValue = "";

		// ic_modeloCocomo
		$this->ic_modeloCocomo->LinkCustomAttributes = "";
		$this->ic_modeloCocomo->HrefValue = "";
		$this->ic_modeloCocomo->TooltipValue = "";

		// nu_metPrazo
		$this->nu_metPrazo->LinkCustomAttributes = "";
		$this->nu_metPrazo->HrefValue = "";
		$this->nu_metPrazo->TooltipValue = "";

		// vr_doPf
		$this->vr_doPf->LinkCustomAttributes = "";
		$this->vr_doPf->HrefValue = "";
		$this->vr_doPf->TooltipValue = "";

		// pz_estimadoMeses
		$this->pz_estimadoMeses->LinkCustomAttributes = "";
		$this->pz_estimadoMeses->HrefValue = "";
		$this->pz_estimadoMeses->TooltipValue = "";

		// pz_estimadoDias
		$this->pz_estimadoDias->LinkCustomAttributes = "";
		$this->pz_estimadoDias->HrefValue = "";
		$this->pz_estimadoDias->TooltipValue = "";

		// vr_ipMaximo
		$this->vr_ipMaximo->LinkCustomAttributes = "";
		$this->vr_ipMaximo->HrefValue = "";
		$this->vr_ipMaximo->TooltipValue = "";

		// vr_ipMedio
		$this->vr_ipMedio->LinkCustomAttributes = "";
		$this->vr_ipMedio->HrefValue = "";
		$this->vr_ipMedio->TooltipValue = "";

		// vr_ipMinimo
		$this->vr_ipMinimo->LinkCustomAttributes = "";
		$this->vr_ipMinimo->HrefValue = "";
		$this->vr_ipMinimo->TooltipValue = "";

		// vr_ipInformado
		$this->vr_ipInformado->LinkCustomAttributes = "";
		$this->vr_ipInformado->HrefValue = "";
		$this->vr_ipInformado->TooltipValue = "";

		// qt_esforco
		$this->qt_esforco->LinkCustomAttributes = "";
		$this->qt_esforco->HrefValue = "";
		$this->qt_esforco->TooltipValue = "";

		// vr_custoDesenv
		$this->vr_custoDesenv->LinkCustomAttributes = "";
		$this->vr_custoDesenv->HrefValue = "";
		$this->vr_custoDesenv->TooltipValue = "";

		// vr_outrosCustos
		$this->vr_outrosCustos->LinkCustomAttributes = "";
		$this->vr_outrosCustos->HrefValue = "";
		$this->vr_outrosCustos->TooltipValue = "";

		// vr_custoTotal
		$this->vr_custoTotal->LinkCustomAttributes = "";
		$this->vr_custoTotal->HrefValue = "";
		$this->vr_custoTotal->TooltipValue = "";

		// qt_tamBaseFaturamento
		$this->qt_tamBaseFaturamento->LinkCustomAttributes = "";
		$this->qt_tamBaseFaturamento->HrefValue = "";
		$this->qt_tamBaseFaturamento->TooltipValue = "";

		// qt_recursosEquipe
		$this->qt_recursosEquipe->LinkCustomAttributes = "";
		$this->qt_recursosEquipe->HrefValue = "";
		$this->qt_recursosEquipe->TooltipValue = "";

		// ds_observacoes
		$this->ds_observacoes->LinkCustomAttributes = "";
		$this->ds_observacoes->HrefValue = "";
		$this->ds_observacoes->TooltipValue = "";

		// ic_bloqueio
		$this->ic_bloqueio->LinkCustomAttributes = "";
		$this->ic_bloqueio->HrefValue = "";
		$this->ic_bloqueio->TooltipValue = "";

		// nu_altRELY
		$this->nu_altRELY->LinkCustomAttributes = "";
		$this->nu_altRELY->HrefValue = "";
		$this->nu_altRELY->TooltipValue = "";

		// nu_altDATA
		$this->nu_altDATA->LinkCustomAttributes = "";
		$this->nu_altDATA->HrefValue = "";
		$this->nu_altDATA->TooltipValue = "";

		// nu_altCPLX1
		$this->nu_altCPLX1->LinkCustomAttributes = "";
		$this->nu_altCPLX1->HrefValue = "";
		$this->nu_altCPLX1->TooltipValue = "";

		// nu_altCPLX2
		$this->nu_altCPLX2->LinkCustomAttributes = "";
		$this->nu_altCPLX2->HrefValue = "";
		$this->nu_altCPLX2->TooltipValue = "";

		// nu_altCPLX3
		$this->nu_altCPLX3->LinkCustomAttributes = "";
		$this->nu_altCPLX3->HrefValue = "";
		$this->nu_altCPLX3->TooltipValue = "";

		// nu_altCPLX4
		$this->nu_altCPLX4->LinkCustomAttributes = "";
		$this->nu_altCPLX4->HrefValue = "";
		$this->nu_altCPLX4->TooltipValue = "";

		// nu_altCPLX5
		$this->nu_altCPLX5->LinkCustomAttributes = "";
		$this->nu_altCPLX5->HrefValue = "";
		$this->nu_altCPLX5->TooltipValue = "";

		// nu_altDOCU
		$this->nu_altDOCU->LinkCustomAttributes = "";
		$this->nu_altDOCU->HrefValue = "";
		$this->nu_altDOCU->TooltipValue = "";

		// nu_altRUSE
		$this->nu_altRUSE->LinkCustomAttributes = "";
		$this->nu_altRUSE->HrefValue = "";
		$this->nu_altRUSE->TooltipValue = "";

		// nu_altTIME
		$this->nu_altTIME->LinkCustomAttributes = "";
		$this->nu_altTIME->HrefValue = "";
		$this->nu_altTIME->TooltipValue = "";

		// nu_altSTOR
		$this->nu_altSTOR->LinkCustomAttributes = "";
		$this->nu_altSTOR->HrefValue = "";
		$this->nu_altSTOR->TooltipValue = "";

		// nu_altPVOL
		$this->nu_altPVOL->LinkCustomAttributes = "";
		$this->nu_altPVOL->HrefValue = "";
		$this->nu_altPVOL->TooltipValue = "";

		// nu_altACAP
		$this->nu_altACAP->LinkCustomAttributes = "";
		$this->nu_altACAP->HrefValue = "";
		$this->nu_altACAP->TooltipValue = "";

		// nu_altPCAP
		$this->nu_altPCAP->LinkCustomAttributes = "";
		$this->nu_altPCAP->HrefValue = "";
		$this->nu_altPCAP->TooltipValue = "";

		// nu_altPCON
		$this->nu_altPCON->LinkCustomAttributes = "";
		$this->nu_altPCON->HrefValue = "";
		$this->nu_altPCON->TooltipValue = "";

		// nu_altAPEX
		$this->nu_altAPEX->LinkCustomAttributes = "";
		$this->nu_altAPEX->HrefValue = "";
		$this->nu_altAPEX->TooltipValue = "";

		// nu_altPLEX
		$this->nu_altPLEX->LinkCustomAttributes = "";
		$this->nu_altPLEX->HrefValue = "";
		$this->nu_altPLEX->TooltipValue = "";

		// nu_altLTEX
		$this->nu_altLTEX->LinkCustomAttributes = "";
		$this->nu_altLTEX->HrefValue = "";
		$this->nu_altLTEX->TooltipValue = "";

		// nu_altTOOL
		$this->nu_altTOOL->LinkCustomAttributes = "";
		$this->nu_altTOOL->HrefValue = "";
		$this->nu_altTOOL->TooltipValue = "";

		// nu_altSITE
		$this->nu_altSITE->LinkCustomAttributes = "";
		$this->nu_altSITE->HrefValue = "";
		$this->nu_altSITE->TooltipValue = "";

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
				if ($this->nu_estimativa->Exportable) $Doc->ExportCaption($this->nu_estimativa);
				if ($this->ic_solicitacaoCritica->Exportable) $Doc->ExportCaption($this->ic_solicitacaoCritica);
				if ($this->nu_ambienteMaisRepresentativo->Exportable) $Doc->ExportCaption($this->nu_ambienteMaisRepresentativo);
				if ($this->qt_tamBase->Exportable) $Doc->ExportCaption($this->qt_tamBase);
				if ($this->ic_modeloCocomo->Exportable) $Doc->ExportCaption($this->ic_modeloCocomo);
				if ($this->nu_metPrazo->Exportable) $Doc->ExportCaption($this->nu_metPrazo);
				if ($this->vr_doPf->Exportable) $Doc->ExportCaption($this->vr_doPf);
				if ($this->pz_estimadoMeses->Exportable) $Doc->ExportCaption($this->pz_estimadoMeses);
				if ($this->pz_estimadoDias->Exportable) $Doc->ExportCaption($this->pz_estimadoDias);
				if ($this->vr_ipMaximo->Exportable) $Doc->ExportCaption($this->vr_ipMaximo);
				if ($this->vr_ipMedio->Exportable) $Doc->ExportCaption($this->vr_ipMedio);
				if ($this->vr_ipMinimo->Exportable) $Doc->ExportCaption($this->vr_ipMinimo);
				if ($this->vr_ipInformado->Exportable) $Doc->ExportCaption($this->vr_ipInformado);
				if ($this->qt_esforco->Exportable) $Doc->ExportCaption($this->qt_esforco);
				if ($this->vr_custoDesenv->Exportable) $Doc->ExportCaption($this->vr_custoDesenv);
				if ($this->vr_outrosCustos->Exportable) $Doc->ExportCaption($this->vr_outrosCustos);
				if ($this->vr_custoTotal->Exportable) $Doc->ExportCaption($this->vr_custoTotal);
				if ($this->qt_tamBaseFaturamento->Exportable) $Doc->ExportCaption($this->qt_tamBaseFaturamento);
				if ($this->qt_recursosEquipe->Exportable) $Doc->ExportCaption($this->qt_recursosEquipe);
				if ($this->ds_observacoes->Exportable) $Doc->ExportCaption($this->ds_observacoes);
				if ($this->nu_altRELY->Exportable) $Doc->ExportCaption($this->nu_altRELY);
				if ($this->nu_altDATA->Exportable) $Doc->ExportCaption($this->nu_altDATA);
				if ($this->nu_altCPLX1->Exportable) $Doc->ExportCaption($this->nu_altCPLX1);
				if ($this->nu_altCPLX2->Exportable) $Doc->ExportCaption($this->nu_altCPLX2);
				if ($this->nu_altCPLX3->Exportable) $Doc->ExportCaption($this->nu_altCPLX3);
				if ($this->nu_altCPLX4->Exportable) $Doc->ExportCaption($this->nu_altCPLX4);
				if ($this->nu_altCPLX5->Exportable) $Doc->ExportCaption($this->nu_altCPLX5);
				if ($this->nu_altDOCU->Exportable) $Doc->ExportCaption($this->nu_altDOCU);
				if ($this->nu_altRUSE->Exportable) $Doc->ExportCaption($this->nu_altRUSE);
				if ($this->nu_altTIME->Exportable) $Doc->ExportCaption($this->nu_altTIME);
				if ($this->nu_altSTOR->Exportable) $Doc->ExportCaption($this->nu_altSTOR);
				if ($this->nu_altPVOL->Exportable) $Doc->ExportCaption($this->nu_altPVOL);
				if ($this->nu_altACAP->Exportable) $Doc->ExportCaption($this->nu_altACAP);
				if ($this->nu_altPCAP->Exportable) $Doc->ExportCaption($this->nu_altPCAP);
				if ($this->nu_altPCON->Exportable) $Doc->ExportCaption($this->nu_altPCON);
				if ($this->nu_altAPEX->Exportable) $Doc->ExportCaption($this->nu_altAPEX);
				if ($this->nu_altPLEX->Exportable) $Doc->ExportCaption($this->nu_altPLEX);
				if ($this->nu_altLTEX->Exportable) $Doc->ExportCaption($this->nu_altLTEX);
				if ($this->nu_altTOOL->Exportable) $Doc->ExportCaption($this->nu_altTOOL);
				if ($this->nu_altSITE->Exportable) $Doc->ExportCaption($this->nu_altSITE);
			} else {
				if ($this->nu_solMetricas->Exportable) $Doc->ExportCaption($this->nu_solMetricas);
				if ($this->nu_estimativa->Exportable) $Doc->ExportCaption($this->nu_estimativa);
				if ($this->ic_solicitacaoCritica->Exportable) $Doc->ExportCaption($this->ic_solicitacaoCritica);
				if ($this->nu_ambienteMaisRepresentativo->Exportable) $Doc->ExportCaption($this->nu_ambienteMaisRepresentativo);
				if ($this->qt_tamBase->Exportable) $Doc->ExportCaption($this->qt_tamBase);
				if ($this->ic_modeloCocomo->Exportable) $Doc->ExportCaption($this->ic_modeloCocomo);
				if ($this->nu_metPrazo->Exportable) $Doc->ExportCaption($this->nu_metPrazo);
				if ($this->vr_doPf->Exportable) $Doc->ExportCaption($this->vr_doPf);
				if ($this->pz_estimadoMeses->Exportable) $Doc->ExportCaption($this->pz_estimadoMeses);
				if ($this->pz_estimadoDias->Exportable) $Doc->ExportCaption($this->pz_estimadoDias);
				if ($this->vr_ipMaximo->Exportable) $Doc->ExportCaption($this->vr_ipMaximo);
				if ($this->vr_ipMedio->Exportable) $Doc->ExportCaption($this->vr_ipMedio);
				if ($this->vr_ipMinimo->Exportable) $Doc->ExportCaption($this->vr_ipMinimo);
				if ($this->vr_ipInformado->Exportable) $Doc->ExportCaption($this->vr_ipInformado);
				if ($this->qt_esforco->Exportable) $Doc->ExportCaption($this->qt_esforco);
				if ($this->vr_custoDesenv->Exportable) $Doc->ExportCaption($this->vr_custoDesenv);
				if ($this->vr_outrosCustos->Exportable) $Doc->ExportCaption($this->vr_outrosCustos);
				if ($this->vr_custoTotal->Exportable) $Doc->ExportCaption($this->vr_custoTotal);
				if ($this->qt_tamBaseFaturamento->Exportable) $Doc->ExportCaption($this->qt_tamBaseFaturamento);
				if ($this->qt_recursosEquipe->Exportable) $Doc->ExportCaption($this->qt_recursosEquipe);
				if ($this->ic_bloqueio->Exportable) $Doc->ExportCaption($this->ic_bloqueio);
				if ($this->nu_altRELY->Exportable) $Doc->ExportCaption($this->nu_altRELY);
				if ($this->nu_altDATA->Exportable) $Doc->ExportCaption($this->nu_altDATA);
				if ($this->nu_altCPLX1->Exportable) $Doc->ExportCaption($this->nu_altCPLX1);
				if ($this->nu_altCPLX2->Exportable) $Doc->ExportCaption($this->nu_altCPLX2);
				if ($this->nu_altCPLX3->Exportable) $Doc->ExportCaption($this->nu_altCPLX3);
				if ($this->nu_altCPLX4->Exportable) $Doc->ExportCaption($this->nu_altCPLX4);
				if ($this->nu_altCPLX5->Exportable) $Doc->ExportCaption($this->nu_altCPLX5);
				if ($this->nu_altDOCU->Exportable) $Doc->ExportCaption($this->nu_altDOCU);
				if ($this->nu_altRUSE->Exportable) $Doc->ExportCaption($this->nu_altRUSE);
				if ($this->nu_altTIME->Exportable) $Doc->ExportCaption($this->nu_altTIME);
				if ($this->nu_altSTOR->Exportable) $Doc->ExportCaption($this->nu_altSTOR);
				if ($this->nu_altPVOL->Exportable) $Doc->ExportCaption($this->nu_altPVOL);
				if ($this->nu_altACAP->Exportable) $Doc->ExportCaption($this->nu_altACAP);
				if ($this->nu_altPCAP->Exportable) $Doc->ExportCaption($this->nu_altPCAP);
				if ($this->nu_altPCON->Exportable) $Doc->ExportCaption($this->nu_altPCON);
				if ($this->nu_altAPEX->Exportable) $Doc->ExportCaption($this->nu_altAPEX);
				if ($this->nu_altPLEX->Exportable) $Doc->ExportCaption($this->nu_altPLEX);
				if ($this->nu_altLTEX->Exportable) $Doc->ExportCaption($this->nu_altLTEX);
				if ($this->nu_altTOOL->Exportable) $Doc->ExportCaption($this->nu_altTOOL);
				if ($this->nu_altSITE->Exportable) $Doc->ExportCaption($this->nu_altSITE);
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
					if ($this->nu_estimativa->Exportable) $Doc->ExportField($this->nu_estimativa);
					if ($this->ic_solicitacaoCritica->Exportable) $Doc->ExportField($this->ic_solicitacaoCritica);
					if ($this->nu_ambienteMaisRepresentativo->Exportable) $Doc->ExportField($this->nu_ambienteMaisRepresentativo);
					if ($this->qt_tamBase->Exportable) $Doc->ExportField($this->qt_tamBase);
					if ($this->ic_modeloCocomo->Exportable) $Doc->ExportField($this->ic_modeloCocomo);
					if ($this->nu_metPrazo->Exportable) $Doc->ExportField($this->nu_metPrazo);
					if ($this->vr_doPf->Exportable) $Doc->ExportField($this->vr_doPf);
					if ($this->pz_estimadoMeses->Exportable) $Doc->ExportField($this->pz_estimadoMeses);
					if ($this->pz_estimadoDias->Exportable) $Doc->ExportField($this->pz_estimadoDias);
					if ($this->vr_ipMaximo->Exportable) $Doc->ExportField($this->vr_ipMaximo);
					if ($this->vr_ipMedio->Exportable) $Doc->ExportField($this->vr_ipMedio);
					if ($this->vr_ipMinimo->Exportable) $Doc->ExportField($this->vr_ipMinimo);
					if ($this->vr_ipInformado->Exportable) $Doc->ExportField($this->vr_ipInformado);
					if ($this->qt_esforco->Exportable) $Doc->ExportField($this->qt_esforco);
					if ($this->vr_custoDesenv->Exportable) $Doc->ExportField($this->vr_custoDesenv);
					if ($this->vr_outrosCustos->Exportable) $Doc->ExportField($this->vr_outrosCustos);
					if ($this->vr_custoTotal->Exportable) $Doc->ExportField($this->vr_custoTotal);
					if ($this->qt_tamBaseFaturamento->Exportable) $Doc->ExportField($this->qt_tamBaseFaturamento);
					if ($this->qt_recursosEquipe->Exportable) $Doc->ExportField($this->qt_recursosEquipe);
					if ($this->ds_observacoes->Exportable) $Doc->ExportField($this->ds_observacoes);
					if ($this->nu_altRELY->Exportable) $Doc->ExportField($this->nu_altRELY);
					if ($this->nu_altDATA->Exportable) $Doc->ExportField($this->nu_altDATA);
					if ($this->nu_altCPLX1->Exportable) $Doc->ExportField($this->nu_altCPLX1);
					if ($this->nu_altCPLX2->Exportable) $Doc->ExportField($this->nu_altCPLX2);
					if ($this->nu_altCPLX3->Exportable) $Doc->ExportField($this->nu_altCPLX3);
					if ($this->nu_altCPLX4->Exportable) $Doc->ExportField($this->nu_altCPLX4);
					if ($this->nu_altCPLX5->Exportable) $Doc->ExportField($this->nu_altCPLX5);
					if ($this->nu_altDOCU->Exportable) $Doc->ExportField($this->nu_altDOCU);
					if ($this->nu_altRUSE->Exportable) $Doc->ExportField($this->nu_altRUSE);
					if ($this->nu_altTIME->Exportable) $Doc->ExportField($this->nu_altTIME);
					if ($this->nu_altSTOR->Exportable) $Doc->ExportField($this->nu_altSTOR);
					if ($this->nu_altPVOL->Exportable) $Doc->ExportField($this->nu_altPVOL);
					if ($this->nu_altACAP->Exportable) $Doc->ExportField($this->nu_altACAP);
					if ($this->nu_altPCAP->Exportable) $Doc->ExportField($this->nu_altPCAP);
					if ($this->nu_altPCON->Exportable) $Doc->ExportField($this->nu_altPCON);
					if ($this->nu_altAPEX->Exportable) $Doc->ExportField($this->nu_altAPEX);
					if ($this->nu_altPLEX->Exportable) $Doc->ExportField($this->nu_altPLEX);
					if ($this->nu_altLTEX->Exportable) $Doc->ExportField($this->nu_altLTEX);
					if ($this->nu_altTOOL->Exportable) $Doc->ExportField($this->nu_altTOOL);
					if ($this->nu_altSITE->Exportable) $Doc->ExportField($this->nu_altSITE);
				} else {
					if ($this->nu_solMetricas->Exportable) $Doc->ExportField($this->nu_solMetricas);
					if ($this->nu_estimativa->Exportable) $Doc->ExportField($this->nu_estimativa);
					if ($this->ic_solicitacaoCritica->Exportable) $Doc->ExportField($this->ic_solicitacaoCritica);
					if ($this->nu_ambienteMaisRepresentativo->Exportable) $Doc->ExportField($this->nu_ambienteMaisRepresentativo);
					if ($this->qt_tamBase->Exportable) $Doc->ExportField($this->qt_tamBase);
					if ($this->ic_modeloCocomo->Exportable) $Doc->ExportField($this->ic_modeloCocomo);
					if ($this->nu_metPrazo->Exportable) $Doc->ExportField($this->nu_metPrazo);
					if ($this->vr_doPf->Exportable) $Doc->ExportField($this->vr_doPf);
					if ($this->pz_estimadoMeses->Exportable) $Doc->ExportField($this->pz_estimadoMeses);
					if ($this->pz_estimadoDias->Exportable) $Doc->ExportField($this->pz_estimadoDias);
					if ($this->vr_ipMaximo->Exportable) $Doc->ExportField($this->vr_ipMaximo);
					if ($this->vr_ipMedio->Exportable) $Doc->ExportField($this->vr_ipMedio);
					if ($this->vr_ipMinimo->Exportable) $Doc->ExportField($this->vr_ipMinimo);
					if ($this->vr_ipInformado->Exportable) $Doc->ExportField($this->vr_ipInformado);
					if ($this->qt_esforco->Exportable) $Doc->ExportField($this->qt_esforco);
					if ($this->vr_custoDesenv->Exportable) $Doc->ExportField($this->vr_custoDesenv);
					if ($this->vr_outrosCustos->Exportable) $Doc->ExportField($this->vr_outrosCustos);
					if ($this->vr_custoTotal->Exportable) $Doc->ExportField($this->vr_custoTotal);
					if ($this->qt_tamBaseFaturamento->Exportable) $Doc->ExportField($this->qt_tamBaseFaturamento);
					if ($this->qt_recursosEquipe->Exportable) $Doc->ExportField($this->qt_recursosEquipe);
					if ($this->ic_bloqueio->Exportable) $Doc->ExportField($this->ic_bloqueio);
					if ($this->nu_altRELY->Exportable) $Doc->ExportField($this->nu_altRELY);
					if ($this->nu_altDATA->Exportable) $Doc->ExportField($this->nu_altDATA);
					if ($this->nu_altCPLX1->Exportable) $Doc->ExportField($this->nu_altCPLX1);
					if ($this->nu_altCPLX2->Exportable) $Doc->ExportField($this->nu_altCPLX2);
					if ($this->nu_altCPLX3->Exportable) $Doc->ExportField($this->nu_altCPLX3);
					if ($this->nu_altCPLX4->Exportable) $Doc->ExportField($this->nu_altCPLX4);
					if ($this->nu_altCPLX5->Exportable) $Doc->ExportField($this->nu_altCPLX5);
					if ($this->nu_altDOCU->Exportable) $Doc->ExportField($this->nu_altDOCU);
					if ($this->nu_altRUSE->Exportable) $Doc->ExportField($this->nu_altRUSE);
					if ($this->nu_altTIME->Exportable) $Doc->ExportField($this->nu_altTIME);
					if ($this->nu_altSTOR->Exportable) $Doc->ExportField($this->nu_altSTOR);
					if ($this->nu_altPVOL->Exportable) $Doc->ExportField($this->nu_altPVOL);
					if ($this->nu_altACAP->Exportable) $Doc->ExportField($this->nu_altACAP);
					if ($this->nu_altPCAP->Exportable) $Doc->ExportField($this->nu_altPCAP);
					if ($this->nu_altPCON->Exportable) $Doc->ExportField($this->nu_altPCON);
					if ($this->nu_altAPEX->Exportable) $Doc->ExportField($this->nu_altAPEX);
					if ($this->nu_altPLEX->Exportable) $Doc->ExportField($this->nu_altPLEX);
					if ($this->nu_altLTEX->Exportable) $Doc->ExportField($this->nu_altLTEX);
					if ($this->nu_altTOOL->Exportable) $Doc->ExportField($this->nu_altTOOL);
					if ($this->nu_altSITE->Exportable) $Doc->ExportField($this->nu_altSITE);
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
