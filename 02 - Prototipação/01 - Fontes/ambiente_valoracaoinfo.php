<?php

// Global variable for table object
$ambiente_valoracao = NULL;

//
// Table class for ambiente_valoracao
//
class cambiente_valoracao extends cTable {
	var $nu_ambiente;
	var $nu_versaoValoracao;
	var $ic_metCalibracao;
	var $dh_inclusao;
	var $nu_usuarioResp;
	var $ic_tpAtualizacao;
	var $qt_linhasCodLingPf;
	var $vr_ipMin;
	var $vr_ipMed;
	var $vr_ipMax;
	var $vr_constanteA;
	var $vr_constanteB;
	var $vr_constanteC;
	var $vr_constanteD;
	var $nu_altPREC;
	var $nu_altFLEX;
	var $nu_altRESL;
	var $nu_altTEAM;
	var $nu_altPMAT;
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
	var $co_quePREC;
	var $co_queFLEX;
	var $co_queRESL;
	var $co_queTEAM;
	var $co_quePMAT;
	var $co_queRELY;
	var $co_queDATA;
	var $co_queCPLX1;
	var $co_queCPLX2;
	var $co_queCPLX3;
	var $co_queCPLX4;
	var $co_queCPLX5;
	var $co_queDOCU;
	var $co_queRUSE;
	var $co_queTIME;
	var $co_queSTOR;
	var $co_quePVOL;
	var $co_queACAP;
	var $co_quePCAP;
	var $co_quePCON;
	var $co_queAPEX;
	var $co_quePLEX;
	var $co_queLTEX;
	var $co_queTOOL;
	var $co_queSITE;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'ambiente_valoracao';
		$this->TableName = 'ambiente_valoracao';
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

		// nu_ambiente
		$this->nu_ambiente = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_nu_ambiente', 'nu_ambiente', '[nu_ambiente]', 'CAST([nu_ambiente] AS NVARCHAR)', 3, -1, FALSE, '[nu_ambiente]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_ambiente->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_ambiente'] = &$this->nu_ambiente;

		// nu_versaoValoracao
		$this->nu_versaoValoracao = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_nu_versaoValoracao', 'nu_versaoValoracao', '[nu_versaoValoracao]', 'CAST([nu_versaoValoracao] AS NVARCHAR)', 3, -1, FALSE, '[nu_versaoValoracao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_versaoValoracao->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_versaoValoracao'] = &$this->nu_versaoValoracao;

		// ic_metCalibracao
		$this->ic_metCalibracao = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_ic_metCalibracao', 'ic_metCalibracao', '[ic_metCalibracao]', '[ic_metCalibracao]', 129, -1, FALSE, '[ic_metCalibracao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_metCalibracao'] = &$this->ic_metCalibracao;

		// dh_inclusao
		$this->dh_inclusao = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_dh_inclusao', 'dh_inclusao', '[dh_inclusao]', '(REPLACE(STR(DAY([dh_inclusao]),2,0),\' \',\'0\') + \'/\' + REPLACE(STR(MONTH([dh_inclusao]),2,0),\' \',\'0\') + \'/\' + STR(YEAR([dh_inclusao]),4,0))', 135, 7, FALSE, '[dh_inclusao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dh_inclusao->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['dh_inclusao'] = &$this->dh_inclusao;

		// nu_usuarioResp
		$this->nu_usuarioResp = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_nu_usuarioResp', 'nu_usuarioResp', '[nu_usuarioResp]', 'CAST([nu_usuarioResp] AS NVARCHAR)', 3, -1, FALSE, '[nu_usuarioResp]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_usuarioResp->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_usuarioResp'] = &$this->nu_usuarioResp;

		// ic_tpAtualizacao
		$this->ic_tpAtualizacao = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_ic_tpAtualizacao', 'ic_tpAtualizacao', '[ic_tpAtualizacao]', '[ic_tpAtualizacao]', 129, -1, FALSE, '[ic_tpAtualizacao]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ic_tpAtualizacao'] = &$this->ic_tpAtualizacao;

		// qt_linhasCodLingPf
		$this->qt_linhasCodLingPf = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_qt_linhasCodLingPf', 'qt_linhasCodLingPf', '[qt_linhasCodLingPf]', 'CAST([qt_linhasCodLingPf] AS NVARCHAR)', 3, -1, FALSE, '[qt_linhasCodLingPf]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->qt_linhasCodLingPf->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['qt_linhasCodLingPf'] = &$this->qt_linhasCodLingPf;

		// vr_ipMin
		$this->vr_ipMin = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_vr_ipMin', 'vr_ipMin', '[vr_ipMin]', 'CAST([vr_ipMin] AS NVARCHAR)', 131, -1, FALSE, '[vr_ipMin]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->vr_ipMin->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['vr_ipMin'] = &$this->vr_ipMin;

		// vr_ipMed
		$this->vr_ipMed = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_vr_ipMed', 'vr_ipMed', '[vr_ipMed]', 'CAST([vr_ipMed] AS NVARCHAR)', 131, -1, FALSE, '[vr_ipMed]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->vr_ipMed->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['vr_ipMed'] = &$this->vr_ipMed;

		// vr_ipMax
		$this->vr_ipMax = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_vr_ipMax', 'vr_ipMax', '[vr_ipMax]', 'CAST([vr_ipMax] AS NVARCHAR)', 131, -1, FALSE, '[vr_ipMax]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->vr_ipMax->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['vr_ipMax'] = &$this->vr_ipMax;

		// vr_constanteA
		$this->vr_constanteA = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_vr_constanteA', 'vr_constanteA', '[vr_constanteA]', 'CAST([vr_constanteA] AS NVARCHAR)', 131, -1, FALSE, '[vr_constanteA]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->vr_constanteA->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['vr_constanteA'] = &$this->vr_constanteA;

		// vr_constanteB
		$this->vr_constanteB = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_vr_constanteB', 'vr_constanteB', '[vr_constanteB]', 'CAST([vr_constanteB] AS NVARCHAR)', 131, -1, FALSE, '[vr_constanteB]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->vr_constanteB->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['vr_constanteB'] = &$this->vr_constanteB;

		// vr_constanteC
		$this->vr_constanteC = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_vr_constanteC', 'vr_constanteC', '[vr_constanteC]', 'CAST([vr_constanteC] AS NVARCHAR)', 131, -1, FALSE, '[vr_constanteC]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->vr_constanteC->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['vr_constanteC'] = &$this->vr_constanteC;

		// vr_constanteD
		$this->vr_constanteD = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_vr_constanteD', 'vr_constanteD', '[vr_constanteD]', 'CAST([vr_constanteD] AS NVARCHAR)', 131, -1, FALSE, '[vr_constanteD]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->vr_constanteD->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['vr_constanteD'] = &$this->vr_constanteD;

		// nu_altPREC
		$this->nu_altPREC = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_nu_altPREC', 'nu_altPREC', '[nu_altPREC]', 'CAST([nu_altPREC] AS NVARCHAR)', 3, -1, FALSE, '[EV__nu_altPREC]', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->nu_altPREC->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altPREC'] = &$this->nu_altPREC;

		// nu_altFLEX
		$this->nu_altFLEX = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_nu_altFLEX', 'nu_altFLEX', '[nu_altFLEX]', 'CAST([nu_altFLEX] AS NVARCHAR)', 3, -1, FALSE, '[nu_altFLEX]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altFLEX->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altFLEX'] = &$this->nu_altFLEX;

		// nu_altRESL
		$this->nu_altRESL = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_nu_altRESL', 'nu_altRESL', '[nu_altRESL]', 'CAST([nu_altRESL] AS NVARCHAR)', 3, -1, FALSE, '[nu_altRESL]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altRESL->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altRESL'] = &$this->nu_altRESL;

		// nu_altTEAM
		$this->nu_altTEAM = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_nu_altTEAM', 'nu_altTEAM', '[nu_altTEAM]', 'CAST([nu_altTEAM] AS NVARCHAR)', 3, -1, FALSE, '[EV__nu_altTEAM]', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->nu_altTEAM->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altTEAM'] = &$this->nu_altTEAM;

		// nu_altPMAT
		$this->nu_altPMAT = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_nu_altPMAT', 'nu_altPMAT', '[nu_altPMAT]', 'CAST([nu_altPMAT] AS NVARCHAR)', 3, -1, FALSE, '[nu_altPMAT]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altPMAT->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altPMAT'] = &$this->nu_altPMAT;

		// nu_altRELY
		$this->nu_altRELY = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_nu_altRELY', 'nu_altRELY', '[nu_altRELY]', 'CAST([nu_altRELY] AS NVARCHAR)', 3, -1, FALSE, '[nu_altRELY]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altRELY->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altRELY'] = &$this->nu_altRELY;

		// nu_altDATA
		$this->nu_altDATA = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_nu_altDATA', 'nu_altDATA', '[nu_altDATA]', 'CAST([nu_altDATA] AS NVARCHAR)', 3, -1, FALSE, '[nu_altDATA]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altDATA->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altDATA'] = &$this->nu_altDATA;

		// nu_altCPLX1
		$this->nu_altCPLX1 = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_nu_altCPLX1', 'nu_altCPLX1', '[nu_altCPLX1]', 'CAST([nu_altCPLX1] AS NVARCHAR)', 3, -1, FALSE, '[nu_altCPLX1]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altCPLX1->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altCPLX1'] = &$this->nu_altCPLX1;

		// nu_altCPLX2
		$this->nu_altCPLX2 = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_nu_altCPLX2', 'nu_altCPLX2', '[nu_altCPLX2]', 'CAST([nu_altCPLX2] AS NVARCHAR)', 3, -1, FALSE, '[nu_altCPLX2]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altCPLX2->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altCPLX2'] = &$this->nu_altCPLX2;

		// nu_altCPLX3
		$this->nu_altCPLX3 = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_nu_altCPLX3', 'nu_altCPLX3', '[nu_altCPLX3]', 'CAST([nu_altCPLX3] AS NVARCHAR)', 3, -1, FALSE, '[EV__nu_altCPLX3]', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->nu_altCPLX3->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altCPLX3'] = &$this->nu_altCPLX3;

		// nu_altCPLX4
		$this->nu_altCPLX4 = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_nu_altCPLX4', 'nu_altCPLX4', '[nu_altCPLX4]', 'CAST([nu_altCPLX4] AS NVARCHAR)', 3, -1, FALSE, '[EV__nu_altCPLX4]', TRUE, TRUE, TRUE, 'FORMATTED TEXT');
		$this->nu_altCPLX4->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altCPLX4'] = &$this->nu_altCPLX4;

		// nu_altCPLX5
		$this->nu_altCPLX5 = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_nu_altCPLX5', 'nu_altCPLX5', '[nu_altCPLX5]', 'CAST([nu_altCPLX5] AS NVARCHAR)', 3, -1, FALSE, '[nu_altCPLX5]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altCPLX5->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altCPLX5'] = &$this->nu_altCPLX5;

		// nu_altDOCU
		$this->nu_altDOCU = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_nu_altDOCU', 'nu_altDOCU', '[nu_altDOCU]', 'CAST([nu_altDOCU] AS NVARCHAR)', 3, -1, FALSE, '[nu_altDOCU]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altDOCU->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altDOCU'] = &$this->nu_altDOCU;

		// nu_altRUSE
		$this->nu_altRUSE = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_nu_altRUSE', 'nu_altRUSE', '[nu_altRUSE]', 'CAST([nu_altRUSE] AS NVARCHAR)', 3, -1, FALSE, '[nu_altRUSE]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altRUSE->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altRUSE'] = &$this->nu_altRUSE;

		// nu_altTIME
		$this->nu_altTIME = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_nu_altTIME', 'nu_altTIME', '[nu_altTIME]', 'CAST([nu_altTIME] AS NVARCHAR)', 3, -1, FALSE, '[nu_altTIME]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altTIME->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altTIME'] = &$this->nu_altTIME;

		// nu_altSTOR
		$this->nu_altSTOR = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_nu_altSTOR', 'nu_altSTOR', '[nu_altSTOR]', 'CAST([nu_altSTOR] AS NVARCHAR)', 3, -1, FALSE, '[nu_altSTOR]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altSTOR->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altSTOR'] = &$this->nu_altSTOR;

		// nu_altPVOL
		$this->nu_altPVOL = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_nu_altPVOL', 'nu_altPVOL', '[nu_altPVOL]', 'CAST([nu_altPVOL] AS NVARCHAR)', 3, -1, FALSE, '[nu_altPVOL]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altPVOL->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altPVOL'] = &$this->nu_altPVOL;

		// nu_altACAP
		$this->nu_altACAP = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_nu_altACAP', 'nu_altACAP', '[nu_altACAP]', 'CAST([nu_altACAP] AS NVARCHAR)', 3, -1, FALSE, '[nu_altACAP]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altACAP->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altACAP'] = &$this->nu_altACAP;

		// nu_altPCAP
		$this->nu_altPCAP = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_nu_altPCAP', 'nu_altPCAP', '[nu_altPCAP]', 'CAST([nu_altPCAP] AS NVARCHAR)', 3, -1, FALSE, '[nu_altPCAP]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altPCAP->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altPCAP'] = &$this->nu_altPCAP;

		// nu_altPCON
		$this->nu_altPCON = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_nu_altPCON', 'nu_altPCON', '[nu_altPCON]', 'CAST([nu_altPCON] AS NVARCHAR)', 3, -1, FALSE, '[nu_altPCON]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altPCON->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altPCON'] = &$this->nu_altPCON;

		// nu_altAPEX
		$this->nu_altAPEX = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_nu_altAPEX', 'nu_altAPEX', '[nu_altAPEX]', 'CAST([nu_altAPEX] AS NVARCHAR)', 3, -1, FALSE, '[nu_altAPEX]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altAPEX->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altAPEX'] = &$this->nu_altAPEX;

		// nu_altPLEX
		$this->nu_altPLEX = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_nu_altPLEX', 'nu_altPLEX', '[nu_altPLEX]', 'CAST([nu_altPLEX] AS NVARCHAR)', 3, -1, FALSE, '[nu_altPLEX]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altPLEX->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altPLEX'] = &$this->nu_altPLEX;

		// nu_altLTEX
		$this->nu_altLTEX = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_nu_altLTEX', 'nu_altLTEX', '[nu_altLTEX]', 'CAST([nu_altLTEX] AS NVARCHAR)', 3, -1, FALSE, '[nu_altLTEX]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altLTEX->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altLTEX'] = &$this->nu_altLTEX;

		// nu_altTOOL
		$this->nu_altTOOL = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_nu_altTOOL', 'nu_altTOOL', '[nu_altTOOL]', 'CAST([nu_altTOOL] AS NVARCHAR)', 3, -1, FALSE, '[nu_altTOOL]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altTOOL->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altTOOL'] = &$this->nu_altTOOL;

		// nu_altSITE
		$this->nu_altSITE = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_nu_altSITE', 'nu_altSITE', '[nu_altSITE]', 'CAST([nu_altSITE] AS NVARCHAR)', 3, -1, FALSE, '[nu_altSITE]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nu_altSITE->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nu_altSITE'] = &$this->nu_altSITE;

		// co_quePREC
		$this->co_quePREC = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_co_quePREC', 'co_quePREC', '[co_quePREC]', '[co_quePREC]', 200, -1, FALSE, '[co_quePREC]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_quePREC'] = &$this->co_quePREC;

		// co_queFLEX
		$this->co_queFLEX = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_co_queFLEX', 'co_queFLEX', '[co_queFLEX]', '[co_queFLEX]', 200, -1, FALSE, '[co_queFLEX]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_queFLEX'] = &$this->co_queFLEX;

		// co_queRESL
		$this->co_queRESL = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_co_queRESL', 'co_queRESL', '[co_queRESL]', '[co_queRESL]', 200, -1, FALSE, '[co_queRESL]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_queRESL'] = &$this->co_queRESL;

		// co_queTEAM
		$this->co_queTEAM = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_co_queTEAM', 'co_queTEAM', '[co_queTEAM]', '[co_queTEAM]', 200, -1, FALSE, '[co_queTEAM]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_queTEAM'] = &$this->co_queTEAM;

		// co_quePMAT
		$this->co_quePMAT = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_co_quePMAT', 'co_quePMAT', '[co_quePMAT]', '[co_quePMAT]', 200, -1, FALSE, '[co_quePMAT]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_quePMAT'] = &$this->co_quePMAT;

		// co_queRELY
		$this->co_queRELY = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_co_queRELY', 'co_queRELY', '[co_queRELY]', '[co_queRELY]', 200, -1, FALSE, '[co_queRELY]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_queRELY'] = &$this->co_queRELY;

		// co_queDATA
		$this->co_queDATA = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_co_queDATA', 'co_queDATA', '[co_queDATA]', '[co_queDATA]', 200, -1, FALSE, '[co_queDATA]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_queDATA'] = &$this->co_queDATA;

		// co_queCPLX1
		$this->co_queCPLX1 = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_co_queCPLX1', 'co_queCPLX1', '[co_queCPLX1]', '[co_queCPLX1]', 200, -1, FALSE, '[co_queCPLX1]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_queCPLX1'] = &$this->co_queCPLX1;

		// co_queCPLX2
		$this->co_queCPLX2 = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_co_queCPLX2', 'co_queCPLX2', '[co_queCPLX2]', '[co_queCPLX2]', 200, -1, FALSE, '[co_queCPLX2]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_queCPLX2'] = &$this->co_queCPLX2;

		// co_queCPLX3
		$this->co_queCPLX3 = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_co_queCPLX3', 'co_queCPLX3', '[co_queCPLX3]', '[co_queCPLX3]', 200, -1, FALSE, '[co_queCPLX3]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_queCPLX3'] = &$this->co_queCPLX3;

		// co_queCPLX4
		$this->co_queCPLX4 = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_co_queCPLX4', 'co_queCPLX4', '[co_queCPLX4]', '[co_queCPLX4]', 200, -1, FALSE, '[co_queCPLX4]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_queCPLX4'] = &$this->co_queCPLX4;

		// co_queCPLX5
		$this->co_queCPLX5 = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_co_queCPLX5', 'co_queCPLX5', '[co_queCPLX5]', '[co_queCPLX5]', 200, -1, FALSE, '[co_queCPLX5]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_queCPLX5'] = &$this->co_queCPLX5;

		// co_queDOCU
		$this->co_queDOCU = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_co_queDOCU', 'co_queDOCU', '[co_queDOCU]', '[co_queDOCU]', 200, -1, FALSE, '[co_queDOCU]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_queDOCU'] = &$this->co_queDOCU;

		// co_queRUSE
		$this->co_queRUSE = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_co_queRUSE', 'co_queRUSE', '[co_queRUSE]', '[co_queRUSE]', 200, -1, FALSE, '[co_queRUSE]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_queRUSE'] = &$this->co_queRUSE;

		// co_queTIME
		$this->co_queTIME = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_co_queTIME', 'co_queTIME', '[co_queTIME]', '[co_queTIME]', 200, -1, FALSE, '[co_queTIME]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_queTIME'] = &$this->co_queTIME;

		// co_queSTOR
		$this->co_queSTOR = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_co_queSTOR', 'co_queSTOR', '[co_queSTOR]', '[co_queSTOR]', 200, -1, FALSE, '[co_queSTOR]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_queSTOR'] = &$this->co_queSTOR;

		// co_quePVOL
		$this->co_quePVOL = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_co_quePVOL', 'co_quePVOL', '[co_quePVOL]', '[co_quePVOL]', 200, -1, FALSE, '[co_quePVOL]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_quePVOL'] = &$this->co_quePVOL;

		// co_queACAP
		$this->co_queACAP = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_co_queACAP', 'co_queACAP', '[co_queACAP]', '[co_queACAP]', 200, -1, FALSE, '[co_queACAP]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_queACAP'] = &$this->co_queACAP;

		// co_quePCAP
		$this->co_quePCAP = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_co_quePCAP', 'co_quePCAP', '[co_quePCAP]', '[co_quePCAP]', 200, -1, FALSE, '[co_quePCAP]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_quePCAP'] = &$this->co_quePCAP;

		// co_quePCON
		$this->co_quePCON = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_co_quePCON', 'co_quePCON', '[co_quePCON]', '[co_quePCON]', 200, -1, FALSE, '[co_quePCON]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_quePCON'] = &$this->co_quePCON;

		// co_queAPEX
		$this->co_queAPEX = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_co_queAPEX', 'co_queAPEX', '[co_queAPEX]', '[co_queAPEX]', 200, -1, FALSE, '[co_queAPEX]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_queAPEX'] = &$this->co_queAPEX;

		// co_quePLEX
		$this->co_quePLEX = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_co_quePLEX', 'co_quePLEX', '[co_quePLEX]', '[co_quePLEX]', 200, -1, FALSE, '[co_quePLEX]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_quePLEX'] = &$this->co_quePLEX;

		// co_queLTEX
		$this->co_queLTEX = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_co_queLTEX', 'co_queLTEX', '[co_queLTEX]', '[co_queLTEX]', 200, -1, FALSE, '[co_queLTEX]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_queLTEX'] = &$this->co_queLTEX;

		// co_queTOOL
		$this->co_queTOOL = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_co_queTOOL', 'co_queTOOL', '[co_queTOOL]', '[co_queTOOL]', 200, -1, FALSE, '[co_queTOOL]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_queTOOL'] = &$this->co_queTOOL;

		// co_queSITE
		$this->co_queSITE = new cField('ambiente_valoracao', 'ambiente_valoracao', 'x_co_queSITE', 'co_queSITE', '[co_queSITE]', '[co_queSITE]', 200, -1, FALSE, '[co_queSITE]', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['co_queSITE'] = &$this->co_queSITE;
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
		if ($this->getCurrentMasterTable() == "ambiente") {
			if ($this->nu_ambiente->getSessionValue() <> "")
				$sMasterFilter .= "[nu_ambiente]=" . ew_QuotedValue($this->nu_ambiente->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "ambiente") {
			if ($this->nu_ambiente->getSessionValue() <> "")
				$sDetailFilter .= "[nu_ambiente]=" . ew_QuotedValue($this->nu_ambiente->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_ambiente() {
		return "[nu_ambiente]=@nu_ambiente@";
	}

	// Detail filter
	function SqlDetailFilter_ambiente() {
		return "[nu_ambiente]=@nu_ambiente@";
	}

	// Table level SQL
	function SqlFrom() { // From
		return "[dbo].[ambiente_valoracao]";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlSelectList() { // Select for List page
		return "SELECT * FROM (" .
			"SELECT *, (SELECT TOP 1 [no_alternativa] + '" . ew_ValueSeparator(1, $this->nu_altPREC) . "' + [ds_alternativa] FROM [ciialternativa] [EW_TMP_LOOKUPTABLE] WHERE [EW_TMP_LOOKUPTABLE].[nu_alternativa] = [ambiente_valoracao].[nu_altPREC]) AS [EV__nu_altPREC], (SELECT TOP 1 [no_alternativa] + '" . ew_ValueSeparator(1, $this->nu_altTEAM) . "' + [ds_alternativa] FROM [ciialternativa] [EW_TMP_LOOKUPTABLE] WHERE [EW_TMP_LOOKUPTABLE].[nu_alternativa] = [ambiente_valoracao].[nu_altTEAM]) AS [EV__nu_altTEAM], (SELECT TOP 1 [no_alternativa] + '" . ew_ValueSeparator(1, $this->nu_altCPLX3) . "' + [ds_alternativa] FROM [ciialternativa] [EW_TMP_LOOKUPTABLE] WHERE [EW_TMP_LOOKUPTABLE].[nu_alternativa] = [ambiente_valoracao].[nu_altCPLX3]) AS [EV__nu_altCPLX3], (SELECT TOP 1 [no_alternativa] + '" . ew_ValueSeparator(1, $this->nu_altCPLX4) . "' + [ds_alternativa] FROM [ciialternativa] [EW_TMP_LOOKUPTABLE] WHERE [EW_TMP_LOOKUPTABLE].[nu_alternativa] = [ambiente_valoracao].[nu_altCPLX4]) AS [EV__nu_altCPLX4] FROM [dbo].[ambiente_valoracao]" .
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
		if ($this->nu_altPREC->AdvancedSearch->SearchValue <> "" ||
			$this->nu_altPREC->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->nu_altPREC->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->nu_altPREC->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->nu_altTEAM->AdvancedSearch->SearchValue <> "" ||
			$this->nu_altTEAM->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->nu_altTEAM->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->nu_altTEAM->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->nu_altCPLX3->AdvancedSearch->SearchValue <> "" ||
			$this->nu_altCPLX3->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->nu_altCPLX3->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->nu_altCPLX3->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->nu_altCPLX4->AdvancedSearch->SearchValue <> "" ||
			$this->nu_altCPLX4->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->nu_altCPLX4->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->nu_altCPLX4->FldVirtualExpression . " ") !== FALSE)
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
	var $UpdateTable = "[dbo].[ambiente_valoracao]";

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
			if (array_key_exists('nu_ambiente', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_ambiente') . '=' . ew_QuotedValue($rs['nu_ambiente'], $this->nu_ambiente->FldDataType));
			if (array_key_exists('nu_versaoValoracao', $rs))
				ew_AddFilter($where, ew_QuotedName('nu_versaoValoracao') . '=' . ew_QuotedValue($rs['nu_versaoValoracao'], $this->nu_versaoValoracao->FldDataType));
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
		return "[nu_ambiente] = @nu_ambiente@ AND [nu_versaoValoracao] = @nu_versaoValoracao@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nu_ambiente->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_ambiente@", ew_AdjustSql($this->nu_ambiente->CurrentValue), $sKeyFilter); // Replace key value
		if (!is_numeric($this->nu_versaoValoracao->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nu_versaoValoracao@", ew_AdjustSql($this->nu_versaoValoracao->CurrentValue), $sKeyFilter); // Replace key value
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
			return "ambiente_valoracaolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "ambiente_valoracaolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("ambiente_valoracaoview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("ambiente_valoracaoview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "ambiente_valoracaoadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("ambiente_valoracaoedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("ambiente_valoracaoadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("ambiente_valoracaodelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nu_ambiente->CurrentValue)) {
			$sUrl .= "nu_ambiente=" . urlencode($this->nu_ambiente->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->nu_versaoValoracao->CurrentValue)) {
			$sUrl .= "&nu_versaoValoracao=" . urlencode($this->nu_versaoValoracao->CurrentValue);
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
			for ($i = 0; $i < $cnt; $i++)
				$arKeys[$i] = explode($EW_COMPOSITE_KEY_SEPARATOR, $arKeys[$i]);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
			for ($i = 0; $i < $cnt; $i++)
				$arKeys[$i] = explode($EW_COMPOSITE_KEY_SEPARATOR, $arKeys[$i]);
		} elseif (isset($_GET)) {
			$arKey[] = @$_GET["nu_ambiente"]; // nu_ambiente
			$arKey[] = @$_GET["nu_versaoValoracao"]; // nu_versaoValoracao
			$arKeys[] = $arKey;

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_array($key) || count($key) <> 2)
				continue; // Just skip so other keys will still work
			if (!is_numeric($key[0])) // nu_ambiente
				continue;
			if (!is_numeric($key[1])) // nu_versaoValoracao
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
			$this->nu_ambiente->CurrentValue = $key[0];
			$this->nu_versaoValoracao->CurrentValue = $key[1];
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
		$this->nu_versaoValoracao->setDbValue($rs->fields('nu_versaoValoracao'));
		$this->ic_metCalibracao->setDbValue($rs->fields('ic_metCalibracao'));
		$this->dh_inclusao->setDbValue($rs->fields('dh_inclusao'));
		$this->nu_usuarioResp->setDbValue($rs->fields('nu_usuarioResp'));
		$this->ic_tpAtualizacao->setDbValue($rs->fields('ic_tpAtualizacao'));
		$this->qt_linhasCodLingPf->setDbValue($rs->fields('qt_linhasCodLingPf'));
		$this->vr_ipMin->setDbValue($rs->fields('vr_ipMin'));
		$this->vr_ipMed->setDbValue($rs->fields('vr_ipMed'));
		$this->vr_ipMax->setDbValue($rs->fields('vr_ipMax'));
		$this->vr_constanteA->setDbValue($rs->fields('vr_constanteA'));
		$this->vr_constanteB->setDbValue($rs->fields('vr_constanteB'));
		$this->vr_constanteC->setDbValue($rs->fields('vr_constanteC'));
		$this->vr_constanteD->setDbValue($rs->fields('vr_constanteD'));
		$this->nu_altPREC->setDbValue($rs->fields('nu_altPREC'));
		$this->nu_altFLEX->setDbValue($rs->fields('nu_altFLEX'));
		$this->nu_altRESL->setDbValue($rs->fields('nu_altRESL'));
		$this->nu_altTEAM->setDbValue($rs->fields('nu_altTEAM'));
		$this->nu_altPMAT->setDbValue($rs->fields('nu_altPMAT'));
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
		$this->co_quePREC->setDbValue($rs->fields('co_quePREC'));
		$this->co_queFLEX->setDbValue($rs->fields('co_queFLEX'));
		$this->co_queRESL->setDbValue($rs->fields('co_queRESL'));
		$this->co_queTEAM->setDbValue($rs->fields('co_queTEAM'));
		$this->co_quePMAT->setDbValue($rs->fields('co_quePMAT'));
		$this->co_queRELY->setDbValue($rs->fields('co_queRELY'));
		$this->co_queDATA->setDbValue($rs->fields('co_queDATA'));
		$this->co_queCPLX1->setDbValue($rs->fields('co_queCPLX1'));
		$this->co_queCPLX2->setDbValue($rs->fields('co_queCPLX2'));
		$this->co_queCPLX3->setDbValue($rs->fields('co_queCPLX3'));
		$this->co_queCPLX4->setDbValue($rs->fields('co_queCPLX4'));
		$this->co_queCPLX5->setDbValue($rs->fields('co_queCPLX5'));
		$this->co_queDOCU->setDbValue($rs->fields('co_queDOCU'));
		$this->co_queRUSE->setDbValue($rs->fields('co_queRUSE'));
		$this->co_queTIME->setDbValue($rs->fields('co_queTIME'));
		$this->co_queSTOR->setDbValue($rs->fields('co_queSTOR'));
		$this->co_quePVOL->setDbValue($rs->fields('co_quePVOL'));
		$this->co_queACAP->setDbValue($rs->fields('co_queACAP'));
		$this->co_quePCAP->setDbValue($rs->fields('co_quePCAP'));
		$this->co_quePCON->setDbValue($rs->fields('co_quePCON'));
		$this->co_queAPEX->setDbValue($rs->fields('co_queAPEX'));
		$this->co_quePLEX->setDbValue($rs->fields('co_quePLEX'));
		$this->co_queLTEX->setDbValue($rs->fields('co_queLTEX'));
		$this->co_queTOOL->setDbValue($rs->fields('co_queTOOL'));
		$this->co_queSITE->setDbValue($rs->fields('co_queSITE'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nu_ambiente
		// nu_versaoValoracao
		// ic_metCalibracao
		// dh_inclusao
		// nu_usuarioResp
		// ic_tpAtualizacao
		// qt_linhasCodLingPf
		// vr_ipMin
		// vr_ipMed
		// vr_ipMax
		// vr_constanteA
		// vr_constanteB
		// vr_constanteC
		// vr_constanteD
		// nu_altPREC
		// nu_altFLEX
		// nu_altRESL
		// nu_altTEAM
		// nu_altPMAT
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
		// co_quePREC
		// co_queFLEX
		// co_queRESL
		// co_queTEAM
		// co_quePMAT
		// co_queRELY
		// co_queDATA
		// co_queCPLX1
		// co_queCPLX2
		// co_queCPLX3
		// co_queCPLX4
		// co_queCPLX5
		// co_queDOCU
		// co_queRUSE
		// co_queTIME
		// co_queSTOR
		// co_quePVOL
		// co_queACAP
		// co_quePCAP
		// co_quePCON
		// co_queAPEX
		// co_quePLEX
		// co_queLTEX
		// co_queTOOL
		// co_queSITE
		// nu_ambiente

		$this->nu_ambiente->ViewValue = $this->nu_ambiente->CurrentValue;
		if (strval($this->nu_ambiente->CurrentValue) <> "") {
			$sFilterWrk = "[nu_ambiente]" . ew_SearchString("=", $this->nu_ambiente->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_ambiente], [nu_ambiente] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[contagempf]";
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

		// nu_versaoValoracao
		$this->nu_versaoValoracao->ViewValue = $this->nu_versaoValoracao->CurrentValue;
		$this->nu_versaoValoracao->ViewCustomAttributes = "";

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

		// dh_inclusao
		$this->dh_inclusao->ViewValue = $this->dh_inclusao->CurrentValue;
		$this->dh_inclusao->ViewValue = ew_FormatDateTime($this->dh_inclusao->ViewValue, 7);
		$this->dh_inclusao->ViewCustomAttributes = "";

		// nu_usuarioResp
		$this->nu_usuarioResp->ViewValue = $this->nu_usuarioResp->CurrentValue;
		if (strval($this->nu_usuarioResp->CurrentValue) <> "") {
			$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioResp->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_usuarioResp, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_usuarioResp->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->nu_usuarioResp->ViewValue = $this->nu_usuarioResp->CurrentValue;
			}
		} else {
			$this->nu_usuarioResp->ViewValue = NULL;
		}
		$this->nu_usuarioResp->ViewCustomAttributes = "";

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

		// vr_constanteA
		$this->vr_constanteA->ViewValue = $this->vr_constanteA->CurrentValue;
		$this->vr_constanteA->ViewCustomAttributes = "";

		// vr_constanteB
		$this->vr_constanteB->ViewValue = $this->vr_constanteB->CurrentValue;
		$this->vr_constanteB->ViewCustomAttributes = "";

		// vr_constanteC
		$this->vr_constanteC->ViewValue = $this->vr_constanteC->CurrentValue;
		$this->vr_constanteC->ViewCustomAttributes = "";

		// vr_constanteD
		$this->vr_constanteD->ViewValue = $this->vr_constanteD->CurrentValue;
		$this->vr_constanteD->ViewCustomAttributes = "";

		// nu_altPREC
		if ($this->nu_altPREC->VirtualValue <> "") {
			$this->nu_altPREC->ViewValue = $this->nu_altPREC->VirtualValue;
		} else {
		if (strval($this->nu_altPREC->CurrentValue) <> "") {
			$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altPREC->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altPREC, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [vr_alternativa] DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altPREC->ViewValue = $rswrk->fields('DispFld');
				$this->nu_altPREC->ViewValue .= ew_ValueSeparator(1,$this->nu_altPREC) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->nu_altPREC->ViewValue = $this->nu_altPREC->CurrentValue;
			}
		} else {
			$this->nu_altPREC->ViewValue = NULL;
		}
		}
		$this->nu_altPREC->ViewCustomAttributes = "";

		// nu_altFLEX
		if (strval($this->nu_altFLEX->CurrentValue) <> "") {
			$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altFLEX->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altFLEX, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [vr_alternativa] DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altFLEX->ViewValue = $rswrk->fields('DispFld');
				$this->nu_altFLEX->ViewValue .= ew_ValueSeparator(1,$this->nu_altFLEX) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->nu_altFLEX->ViewValue = $this->nu_altFLEX->CurrentValue;
			}
		} else {
			$this->nu_altFLEX->ViewValue = NULL;
		}
		$this->nu_altFLEX->ViewCustomAttributes = "";

		// nu_altRESL
		if (strval($this->nu_altRESL->CurrentValue) <> "") {
			$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altRESL->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altRESL, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [vr_alternativa] DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altRESL->ViewValue = $rswrk->fields('DispFld');
				$this->nu_altRESL->ViewValue .= ew_ValueSeparator(1,$this->nu_altRESL) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->nu_altRESL->ViewValue = $this->nu_altRESL->CurrentValue;
			}
		} else {
			$this->nu_altRESL->ViewValue = NULL;
		}
		$this->nu_altRESL->ViewCustomAttributes = "";

		// nu_altTEAM
		if ($this->nu_altTEAM->VirtualValue <> "") {
			$this->nu_altTEAM->ViewValue = $this->nu_altTEAM->VirtualValue;
		} else {
		if (strval($this->nu_altTEAM->CurrentValue) <> "") {
			$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altTEAM->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altTEAM, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [vr_alternativa] DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altTEAM->ViewValue = $rswrk->fields('DispFld');
				$this->nu_altTEAM->ViewValue .= ew_ValueSeparator(1,$this->nu_altTEAM) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->nu_altTEAM->ViewValue = $this->nu_altTEAM->CurrentValue;
			}
		} else {
			$this->nu_altTEAM->ViewValue = NULL;
		}
		}
		$this->nu_altTEAM->ViewCustomAttributes = "";

		// nu_altPMAT
		if (strval($this->nu_altPMAT->CurrentValue) <> "") {
			$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altPMAT->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altPMAT, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [vr_alternativa] DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altPMAT->ViewValue = $rswrk->fields('DispFld');
				$this->nu_altPMAT->ViewValue .= ew_ValueSeparator(1,$this->nu_altPMAT) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->nu_altPMAT->ViewValue = $this->nu_altPMAT->CurrentValue;
			}
		} else {
			$this->nu_altPMAT->ViewValue = NULL;
		}
		$this->nu_altPMAT->ViewCustomAttributes = "";

		// nu_altRELY
		if (strval($this->nu_altRELY->CurrentValue) <> "") {
			$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altRELY->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
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
				$this->nu_altRELY->ViewValue .= ew_ValueSeparator(1,$this->nu_altRELY) . $rswrk->fields('Disp2Fld');
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
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
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
				$this->nu_altDATA->ViewValue .= ew_ValueSeparator(1,$this->nu_altDATA) . $rswrk->fields('Disp2Fld');
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
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
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
				$this->nu_altCPLX1->ViewValue .= ew_ValueSeparator(1,$this->nu_altCPLX1) . $rswrk->fields('Disp2Fld');
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
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altCPLX2, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altCPLX2->ViewValue = $rswrk->fields('DispFld');
				$this->nu_altCPLX2->ViewValue .= ew_ValueSeparator(1,$this->nu_altCPLX2) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->nu_altCPLX2->ViewValue = $this->nu_altCPLX2->CurrentValue;
			}
		} else {
			$this->nu_altCPLX2->ViewValue = NULL;
		}
		$this->nu_altCPLX2->ViewCustomAttributes = "";

		// nu_altCPLX3
		if ($this->nu_altCPLX3->VirtualValue <> "") {
			$this->nu_altCPLX3->ViewValue = $this->nu_altCPLX3->VirtualValue;
		} else {
		if (strval($this->nu_altCPLX3->CurrentValue) <> "") {
			$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX3->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altCPLX3, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altCPLX3->ViewValue = $rswrk->fields('DispFld');
				$this->nu_altCPLX3->ViewValue .= ew_ValueSeparator(1,$this->nu_altCPLX3) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->nu_altCPLX3->ViewValue = $this->nu_altCPLX3->CurrentValue;
			}
		} else {
			$this->nu_altCPLX3->ViewValue = NULL;
		}
		}
		$this->nu_altCPLX3->ViewCustomAttributes = "";

		// nu_altCPLX4
		if ($this->nu_altCPLX4->VirtualValue <> "") {
			$this->nu_altCPLX4->ViewValue = $this->nu_altCPLX4->VirtualValue;
		} else {
		if (strval($this->nu_altCPLX4->CurrentValue) <> "") {
			$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX4->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altCPLX4, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altCPLX4->ViewValue = $rswrk->fields('DispFld');
				$this->nu_altCPLX4->ViewValue .= ew_ValueSeparator(1,$this->nu_altCPLX4) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->nu_altCPLX4->ViewValue = $this->nu_altCPLX4->CurrentValue;
			}
		} else {
			$this->nu_altCPLX4->ViewValue = NULL;
		}
		}
		$this->nu_altCPLX4->ViewCustomAttributes = "";

		// nu_altCPLX5
		if (strval($this->nu_altCPLX5->CurrentValue) <> "") {
			$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX5->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altCPLX5, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altCPLX5->ViewValue = $rswrk->fields('DispFld');
				$this->nu_altCPLX5->ViewValue .= ew_ValueSeparator(1,$this->nu_altCPLX5) . $rswrk->fields('Disp2Fld');
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
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altDOCU, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altDOCU->ViewValue = $rswrk->fields('DispFld');
				$this->nu_altDOCU->ViewValue .= ew_ValueSeparator(1,$this->nu_altDOCU) . $rswrk->fields('Disp2Fld');
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
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altRUSE, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altRUSE->ViewValue = $rswrk->fields('DispFld');
				$this->nu_altRUSE->ViewValue .= ew_ValueSeparator(1,$this->nu_altRUSE) . $rswrk->fields('Disp2Fld');
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
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altTIME, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altTIME->ViewValue = $rswrk->fields('DispFld');
				$this->nu_altTIME->ViewValue .= ew_ValueSeparator(1,$this->nu_altTIME) . $rswrk->fields('Disp2Fld');
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
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altSTOR, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altSTOR->ViewValue = $rswrk->fields('DispFld');
				$this->nu_altSTOR->ViewValue .= ew_ValueSeparator(1,$this->nu_altSTOR) . $rswrk->fields('Disp2Fld');
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
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altPVOL, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altPVOL->ViewValue = $rswrk->fields('DispFld');
				$this->nu_altPVOL->ViewValue .= ew_ValueSeparator(1,$this->nu_altPVOL) . $rswrk->fields('Disp2Fld');
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
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altACAP, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [no_alternativa] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altACAP->ViewValue = $rswrk->fields('DispFld');
				$this->nu_altACAP->ViewValue .= ew_ValueSeparator(1,$this->nu_altACAP) . $rswrk->fields('Disp2Fld');
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
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altPCAP, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altPCAP->ViewValue = $rswrk->fields('DispFld');
				$this->nu_altPCAP->ViewValue .= ew_ValueSeparator(1,$this->nu_altPCAP) . $rswrk->fields('Disp2Fld');
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
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altPCON, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [ic_ativo] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altPCON->ViewValue = $rswrk->fields('DispFld');
				$this->nu_altPCON->ViewValue .= ew_ValueSeparator(1,$this->nu_altPCON) . $rswrk->fields('Disp2Fld');
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
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altAPEX, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altAPEX->ViewValue = $rswrk->fields('DispFld');
				$this->nu_altAPEX->ViewValue .= ew_ValueSeparator(1,$this->nu_altAPEX) . $rswrk->fields('Disp2Fld');
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
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altPLEX, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altPLEX->ViewValue = $rswrk->fields('DispFld');
				$this->nu_altPLEX->ViewValue .= ew_ValueSeparator(1,$this->nu_altPLEX) . $rswrk->fields('Disp2Fld');
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
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altLTEX, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altLTEX->ViewValue = $rswrk->fields('DispFld');
				$this->nu_altLTEX->ViewValue .= ew_ValueSeparator(1,$this->nu_altLTEX) . $rswrk->fields('Disp2Fld');
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
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altTOOL, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altTOOL->ViewValue = $rswrk->fields('DispFld');
				$this->nu_altTOOL->ViewValue .= ew_ValueSeparator(1,$this->nu_altTOOL) . $rswrk->fields('Disp2Fld');
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
		$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
		$sWhereWrk = "";
		$lookuptblfilter = "[ic_ativo]='S'";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->nu_altSITE, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY [nu_peso] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->nu_altSITE->ViewValue = $rswrk->fields('DispFld');
				$this->nu_altSITE->ViewValue .= ew_ValueSeparator(1,$this->nu_altSITE) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->nu_altSITE->ViewValue = $this->nu_altSITE->CurrentValue;
			}
		} else {
			$this->nu_altSITE->ViewValue = NULL;
		}
		$this->nu_altSITE->ViewCustomAttributes = "";

		// co_quePREC
		if (strval($this->co_quePREC->CurrentValue) <> "") {
			$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_quePREC->CurrentValue, EW_DATATYPE_STRING);
		$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->co_quePREC, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->co_quePREC->ViewValue = $rswrk->fields('DispFld');
				$this->co_quePREC->ViewValue .= ew_ValueSeparator(1,$this->co_quePREC) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->co_quePREC->ViewValue = $this->co_quePREC->CurrentValue;
			}
		} else {
			$this->co_quePREC->ViewValue = NULL;
		}
		$this->co_quePREC->ViewCustomAttributes = "";

		// co_queFLEX
		if (strval($this->co_queFLEX->CurrentValue) <> "") {
			$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queFLEX->CurrentValue, EW_DATATYPE_STRING);
		$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->co_queFLEX, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->co_queFLEX->ViewValue = $rswrk->fields('DispFld');
				$this->co_queFLEX->ViewValue .= ew_ValueSeparator(1,$this->co_queFLEX) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->co_queFLEX->ViewValue = $this->co_queFLEX->CurrentValue;
			}
		} else {
			$this->co_queFLEX->ViewValue = NULL;
		}
		$this->co_queFLEX->ViewCustomAttributes = "";

		// co_queRESL
		if (strval($this->co_queRESL->CurrentValue) <> "") {
			$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queRESL->CurrentValue, EW_DATATYPE_STRING);
		$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->co_queRESL, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->co_queRESL->ViewValue = $rswrk->fields('DispFld');
				$this->co_queRESL->ViewValue .= ew_ValueSeparator(1,$this->co_queRESL) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->co_queRESL->ViewValue = $this->co_queRESL->CurrentValue;
			}
		} else {
			$this->co_queRESL->ViewValue = NULL;
		}
		$this->co_queRESL->ViewCustomAttributes = "";

		// co_queTEAM
		if (strval($this->co_queTEAM->CurrentValue) <> "") {
			$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queTEAM->CurrentValue, EW_DATATYPE_STRING);
		$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->co_queTEAM, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->co_queTEAM->ViewValue = $rswrk->fields('DispFld');
				$this->co_queTEAM->ViewValue .= ew_ValueSeparator(1,$this->co_queTEAM) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->co_queTEAM->ViewValue = $this->co_queTEAM->CurrentValue;
			}
		} else {
			$this->co_queTEAM->ViewValue = NULL;
		}
		$this->co_queTEAM->ViewCustomAttributes = "";

		// co_quePMAT
		if (strval($this->co_quePMAT->CurrentValue) <> "") {
			$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_quePMAT->CurrentValue, EW_DATATYPE_STRING);
		$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->co_quePMAT, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->co_quePMAT->ViewValue = $rswrk->fields('DispFld');
				$this->co_quePMAT->ViewValue .= ew_ValueSeparator(1,$this->co_quePMAT) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->co_quePMAT->ViewValue = $this->co_quePMAT->CurrentValue;
			}
		} else {
			$this->co_quePMAT->ViewValue = NULL;
		}
		$this->co_quePMAT->ViewCustomAttributes = "";

		// co_queRELY
		if (strval($this->co_queRELY->CurrentValue) <> "") {
			$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queRELY->CurrentValue, EW_DATATYPE_STRING);
		$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->co_queRELY, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->co_queRELY->ViewValue = $rswrk->fields('DispFld');
				$this->co_queRELY->ViewValue .= ew_ValueSeparator(1,$this->co_queRELY) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->co_queRELY->ViewValue = $this->co_queRELY->CurrentValue;
			}
		} else {
			$this->co_queRELY->ViewValue = NULL;
		}
		$this->co_queRELY->ViewCustomAttributes = "";

		// co_queDATA
		if (strval($this->co_queDATA->CurrentValue) <> "") {
			$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queDATA->CurrentValue, EW_DATATYPE_STRING);
		$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->co_queDATA, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->co_queDATA->ViewValue = $rswrk->fields('DispFld');
				$this->co_queDATA->ViewValue .= ew_ValueSeparator(1,$this->co_queDATA) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->co_queDATA->ViewValue = $this->co_queDATA->CurrentValue;
			}
		} else {
			$this->co_queDATA->ViewValue = NULL;
		}
		$this->co_queDATA->ViewCustomAttributes = "";

		// co_queCPLX1
		if (strval($this->co_queCPLX1->CurrentValue) <> "") {
			$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queCPLX1->CurrentValue, EW_DATATYPE_STRING);
		$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->co_queCPLX1, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->co_queCPLX1->ViewValue = $rswrk->fields('DispFld');
				$this->co_queCPLX1->ViewValue .= ew_ValueSeparator(1,$this->co_queCPLX1) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->co_queCPLX1->ViewValue = $this->co_queCPLX1->CurrentValue;
			}
		} else {
			$this->co_queCPLX1->ViewValue = NULL;
		}
		$this->co_queCPLX1->ViewCustomAttributes = "";

		// co_queCPLX2
		if (strval($this->co_queCPLX2->CurrentValue) <> "") {
			$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queCPLX2->CurrentValue, EW_DATATYPE_STRING);
		$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->co_queCPLX2, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->co_queCPLX2->ViewValue = $rswrk->fields('DispFld');
				$this->co_queCPLX2->ViewValue .= ew_ValueSeparator(1,$this->co_queCPLX2) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->co_queCPLX2->ViewValue = $this->co_queCPLX2->CurrentValue;
			}
		} else {
			$this->co_queCPLX2->ViewValue = NULL;
		}
		$this->co_queCPLX2->ViewCustomAttributes = "";

		// co_queCPLX3
		if (strval($this->co_queCPLX3->CurrentValue) <> "") {
			$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queCPLX3->CurrentValue, EW_DATATYPE_STRING);
		$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->co_queCPLX3, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->co_queCPLX3->ViewValue = $rswrk->fields('DispFld');
				$this->co_queCPLX3->ViewValue .= ew_ValueSeparator(1,$this->co_queCPLX3) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->co_queCPLX3->ViewValue = $this->co_queCPLX3->CurrentValue;
			}
		} else {
			$this->co_queCPLX3->ViewValue = NULL;
		}
		$this->co_queCPLX3->ViewCustomAttributes = "";

		// co_queCPLX4
		if (strval($this->co_queCPLX4->CurrentValue) <> "") {
			$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queCPLX4->CurrentValue, EW_DATATYPE_STRING);
		$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->co_queCPLX4, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->co_queCPLX4->ViewValue = $rswrk->fields('DispFld');
				$this->co_queCPLX4->ViewValue .= ew_ValueSeparator(1,$this->co_queCPLX4) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->co_queCPLX4->ViewValue = $this->co_queCPLX4->CurrentValue;
			}
		} else {
			$this->co_queCPLX4->ViewValue = NULL;
		}
		$this->co_queCPLX4->ViewCustomAttributes = "";

		// co_queCPLX5
		if (strval($this->co_queCPLX5->CurrentValue) <> "") {
			$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queCPLX5->CurrentValue, EW_DATATYPE_STRING);
		$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->co_queCPLX5, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->co_queCPLX5->ViewValue = $rswrk->fields('DispFld');
				$this->co_queCPLX5->ViewValue .= ew_ValueSeparator(1,$this->co_queCPLX5) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->co_queCPLX5->ViewValue = $this->co_queCPLX5->CurrentValue;
			}
		} else {
			$this->co_queCPLX5->ViewValue = NULL;
		}
		$this->co_queCPLX5->ViewCustomAttributes = "";

		// co_queDOCU
		if (strval($this->co_queDOCU->CurrentValue) <> "") {
			$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queDOCU->CurrentValue, EW_DATATYPE_STRING);
		$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->co_queDOCU, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->co_queDOCU->ViewValue = $rswrk->fields('DispFld');
				$this->co_queDOCU->ViewValue .= ew_ValueSeparator(1,$this->co_queDOCU) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->co_queDOCU->ViewValue = $this->co_queDOCU->CurrentValue;
			}
		} else {
			$this->co_queDOCU->ViewValue = NULL;
		}
		$this->co_queDOCU->ViewCustomAttributes = "";

		// co_queRUSE
		if (strval($this->co_queRUSE->CurrentValue) <> "") {
			$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queRUSE->CurrentValue, EW_DATATYPE_STRING);
		$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->co_queRUSE, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->co_queRUSE->ViewValue = $rswrk->fields('DispFld');
				$this->co_queRUSE->ViewValue .= ew_ValueSeparator(1,$this->co_queRUSE) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->co_queRUSE->ViewValue = $this->co_queRUSE->CurrentValue;
			}
		} else {
			$this->co_queRUSE->ViewValue = NULL;
		}
		$this->co_queRUSE->ViewCustomAttributes = "";

		// co_queTIME
		if (strval($this->co_queTIME->CurrentValue) <> "") {
			$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queTIME->CurrentValue, EW_DATATYPE_STRING);
		$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->co_queTIME, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->co_queTIME->ViewValue = $rswrk->fields('DispFld');
				$this->co_queTIME->ViewValue .= ew_ValueSeparator(1,$this->co_queTIME) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->co_queTIME->ViewValue = $this->co_queTIME->CurrentValue;
			}
		} else {
			$this->co_queTIME->ViewValue = NULL;
		}
		$this->co_queTIME->ViewCustomAttributes = "";

		// co_queSTOR
		if (strval($this->co_queSTOR->CurrentValue) <> "") {
			$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queSTOR->CurrentValue, EW_DATATYPE_STRING);
		$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->co_queSTOR, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->co_queSTOR->ViewValue = $rswrk->fields('DispFld');
				$this->co_queSTOR->ViewValue .= ew_ValueSeparator(1,$this->co_queSTOR) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->co_queSTOR->ViewValue = $this->co_queSTOR->CurrentValue;
			}
		} else {
			$this->co_queSTOR->ViewValue = NULL;
		}
		$this->co_queSTOR->ViewCustomAttributes = "";

		// co_quePVOL
		if (strval($this->co_quePVOL->CurrentValue) <> "") {
			$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_quePVOL->CurrentValue, EW_DATATYPE_STRING);
		$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->co_quePVOL, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->co_quePVOL->ViewValue = $rswrk->fields('DispFld');
				$this->co_quePVOL->ViewValue .= ew_ValueSeparator(1,$this->co_quePVOL) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->co_quePVOL->ViewValue = $this->co_quePVOL->CurrentValue;
			}
		} else {
			$this->co_quePVOL->ViewValue = NULL;
		}
		$this->co_quePVOL->ViewCustomAttributes = "";

		// co_queACAP
		if (strval($this->co_queACAP->CurrentValue) <> "") {
			$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queACAP->CurrentValue, EW_DATATYPE_STRING);
		$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->co_queACAP, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->co_queACAP->ViewValue = $rswrk->fields('DispFld');
				$this->co_queACAP->ViewValue .= ew_ValueSeparator(1,$this->co_queACAP) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->co_queACAP->ViewValue = $this->co_queACAP->CurrentValue;
			}
		} else {
			$this->co_queACAP->ViewValue = NULL;
		}
		$this->co_queACAP->ViewCustomAttributes = "";

		// co_quePCAP
		if (strval($this->co_quePCAP->CurrentValue) <> "") {
			$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_quePCAP->CurrentValue, EW_DATATYPE_STRING);
		$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->co_quePCAP, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->co_quePCAP->ViewValue = $rswrk->fields('DispFld');
				$this->co_quePCAP->ViewValue .= ew_ValueSeparator(1,$this->co_quePCAP) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->co_quePCAP->ViewValue = $this->co_quePCAP->CurrentValue;
			}
		} else {
			$this->co_quePCAP->ViewValue = NULL;
		}
		$this->co_quePCAP->ViewCustomAttributes = "";

		// co_quePCON
		if (strval($this->co_quePCON->CurrentValue) <> "") {
			$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_quePCON->CurrentValue, EW_DATATYPE_STRING);
		$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->co_quePCON, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->co_quePCON->ViewValue = $rswrk->fields('DispFld');
				$this->co_quePCON->ViewValue .= ew_ValueSeparator(1,$this->co_quePCON) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->co_quePCON->ViewValue = $this->co_quePCON->CurrentValue;
			}
		} else {
			$this->co_quePCON->ViewValue = NULL;
		}
		$this->co_quePCON->ViewCustomAttributes = "";

		// co_queAPEX
		if (strval($this->co_queAPEX->CurrentValue) <> "") {
			$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queAPEX->CurrentValue, EW_DATATYPE_STRING);
		$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->co_queAPEX, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->co_queAPEX->ViewValue = $rswrk->fields('DispFld');
				$this->co_queAPEX->ViewValue .= ew_ValueSeparator(1,$this->co_queAPEX) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->co_queAPEX->ViewValue = $this->co_queAPEX->CurrentValue;
			}
		} else {
			$this->co_queAPEX->ViewValue = NULL;
		}
		$this->co_queAPEX->ViewCustomAttributes = "";

		// co_quePLEX
		if (strval($this->co_quePLEX->CurrentValue) <> "") {
			$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_quePLEX->CurrentValue, EW_DATATYPE_STRING);
		$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->co_quePLEX, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->co_quePLEX->ViewValue = $rswrk->fields('DispFld');
				$this->co_quePLEX->ViewValue .= ew_ValueSeparator(1,$this->co_quePLEX) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->co_quePLEX->ViewValue = $this->co_quePLEX->CurrentValue;
			}
		} else {
			$this->co_quePLEX->ViewValue = NULL;
		}
		$this->co_quePLEX->ViewCustomAttributes = "";

		// co_queLTEX
		if (strval($this->co_queLTEX->CurrentValue) <> "") {
			$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queLTEX->CurrentValue, EW_DATATYPE_STRING);
		$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->co_queLTEX, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->co_queLTEX->ViewValue = $rswrk->fields('DispFld');
				$this->co_queLTEX->ViewValue .= ew_ValueSeparator(1,$this->co_queLTEX) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->co_queLTEX->ViewValue = $this->co_queLTEX->CurrentValue;
			}
		} else {
			$this->co_queLTEX->ViewValue = NULL;
		}
		$this->co_queLTEX->ViewCustomAttributes = "";

		// co_queTOOL
		if (strval($this->co_queTOOL->CurrentValue) <> "") {
			$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queTOOL->CurrentValue, EW_DATATYPE_STRING);
		$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->co_queTOOL, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->co_queTOOL->ViewValue = $rswrk->fields('DispFld');
				$this->co_queTOOL->ViewValue .= ew_ValueSeparator(1,$this->co_queTOOL) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->co_queTOOL->ViewValue = $this->co_queTOOL->CurrentValue;
			}
		} else {
			$this->co_queTOOL->ViewValue = NULL;
		}
		$this->co_queTOOL->ViewCustomAttributes = "";

		// co_queSITE
		if (strval($this->co_queSITE->CurrentValue) <> "") {
			$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queSITE->CurrentValue, EW_DATATYPE_STRING);
		$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->co_queSITE, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->co_queSITE->ViewValue = $rswrk->fields('DispFld');
				$this->co_queSITE->ViewValue .= ew_ValueSeparator(1,$this->co_queSITE) . $rswrk->fields('Disp2Fld');
				$rswrk->Close();
			} else {
				$this->co_queSITE->ViewValue = $this->co_queSITE->CurrentValue;
			}
		} else {
			$this->co_queSITE->ViewValue = NULL;
		}
		$this->co_queSITE->ViewCustomAttributes = "";

		// nu_ambiente
		$this->nu_ambiente->LinkCustomAttributes = "";
		$this->nu_ambiente->HrefValue = "";
		$this->nu_ambiente->TooltipValue = "";

		// nu_versaoValoracao
		$this->nu_versaoValoracao->LinkCustomAttributes = "";
		$this->nu_versaoValoracao->HrefValue = "";
		$this->nu_versaoValoracao->TooltipValue = "";

		// ic_metCalibracao
		$this->ic_metCalibracao->LinkCustomAttributes = "";
		$this->ic_metCalibracao->HrefValue = "";
		$this->ic_metCalibracao->TooltipValue = "";

		// dh_inclusao
		$this->dh_inclusao->LinkCustomAttributes = "";
		$this->dh_inclusao->HrefValue = "";
		$this->dh_inclusao->TooltipValue = "";

		// nu_usuarioResp
		$this->nu_usuarioResp->LinkCustomAttributes = "";
		$this->nu_usuarioResp->HrefValue = "";
		$this->nu_usuarioResp->TooltipValue = "";

		// ic_tpAtualizacao
		$this->ic_tpAtualizacao->LinkCustomAttributes = "";
		$this->ic_tpAtualizacao->HrefValue = "";
		$this->ic_tpAtualizacao->TooltipValue = "";

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

		// vr_constanteA
		$this->vr_constanteA->LinkCustomAttributes = "";
		$this->vr_constanteA->HrefValue = "";
		$this->vr_constanteA->TooltipValue = "";

		// vr_constanteB
		$this->vr_constanteB->LinkCustomAttributes = "";
		$this->vr_constanteB->HrefValue = "";
		$this->vr_constanteB->TooltipValue = "";

		// vr_constanteC
		$this->vr_constanteC->LinkCustomAttributes = "";
		$this->vr_constanteC->HrefValue = "";
		$this->vr_constanteC->TooltipValue = "";

		// vr_constanteD
		$this->vr_constanteD->LinkCustomAttributes = "";
		$this->vr_constanteD->HrefValue = "";
		$this->vr_constanteD->TooltipValue = "";

		// nu_altPREC
		$this->nu_altPREC->LinkCustomAttributes = "";
		$this->nu_altPREC->HrefValue = "";
		$this->nu_altPREC->TooltipValue = "";

		// nu_altFLEX
		$this->nu_altFLEX->LinkCustomAttributes = "";
		$this->nu_altFLEX->HrefValue = "";
		$this->nu_altFLEX->TooltipValue = "";

		// nu_altRESL
		$this->nu_altRESL->LinkCustomAttributes = "";
		$this->nu_altRESL->HrefValue = "";
		$this->nu_altRESL->TooltipValue = "";

		// nu_altTEAM
		$this->nu_altTEAM->LinkCustomAttributes = "";
		$this->nu_altTEAM->HrefValue = "";
		$this->nu_altTEAM->TooltipValue = "";

		// nu_altPMAT
		$this->nu_altPMAT->LinkCustomAttributes = "";
		$this->nu_altPMAT->HrefValue = "";
		$this->nu_altPMAT->TooltipValue = "";

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

		// co_quePREC
		$this->co_quePREC->LinkCustomAttributes = "";
		$this->co_quePREC->HrefValue = "";
		$this->co_quePREC->TooltipValue = "";

		// co_queFLEX
		$this->co_queFLEX->LinkCustomAttributes = "";
		$this->co_queFLEX->HrefValue = "";
		$this->co_queFLEX->TooltipValue = "";

		// co_queRESL
		$this->co_queRESL->LinkCustomAttributes = "";
		$this->co_queRESL->HrefValue = "";
		$this->co_queRESL->TooltipValue = "";

		// co_queTEAM
		$this->co_queTEAM->LinkCustomAttributes = "";
		$this->co_queTEAM->HrefValue = "";
		$this->co_queTEAM->TooltipValue = "";

		// co_quePMAT
		$this->co_quePMAT->LinkCustomAttributes = "";
		$this->co_quePMAT->HrefValue = "";
		$this->co_quePMAT->TooltipValue = "";

		// co_queRELY
		$this->co_queRELY->LinkCustomAttributes = "";
		$this->co_queRELY->HrefValue = "";
		$this->co_queRELY->TooltipValue = "";

		// co_queDATA
		$this->co_queDATA->LinkCustomAttributes = "";
		$this->co_queDATA->HrefValue = "";
		$this->co_queDATA->TooltipValue = "";

		// co_queCPLX1
		$this->co_queCPLX1->LinkCustomAttributes = "";
		$this->co_queCPLX1->HrefValue = "";
		$this->co_queCPLX1->TooltipValue = "";

		// co_queCPLX2
		$this->co_queCPLX2->LinkCustomAttributes = "";
		$this->co_queCPLX2->HrefValue = "";
		$this->co_queCPLX2->TooltipValue = "";

		// co_queCPLX3
		$this->co_queCPLX3->LinkCustomAttributes = "";
		$this->co_queCPLX3->HrefValue = "";
		$this->co_queCPLX3->TooltipValue = "";

		// co_queCPLX4
		$this->co_queCPLX4->LinkCustomAttributes = "";
		$this->co_queCPLX4->HrefValue = "";
		$this->co_queCPLX4->TooltipValue = "";

		// co_queCPLX5
		$this->co_queCPLX5->LinkCustomAttributes = "";
		$this->co_queCPLX5->HrefValue = "";
		$this->co_queCPLX5->TooltipValue = "";

		// co_queDOCU
		$this->co_queDOCU->LinkCustomAttributes = "";
		$this->co_queDOCU->HrefValue = "";
		$this->co_queDOCU->TooltipValue = "";

		// co_queRUSE
		$this->co_queRUSE->LinkCustomAttributes = "";
		$this->co_queRUSE->HrefValue = "";
		$this->co_queRUSE->TooltipValue = "";

		// co_queTIME
		$this->co_queTIME->LinkCustomAttributes = "";
		$this->co_queTIME->HrefValue = "";
		$this->co_queTIME->TooltipValue = "";

		// co_queSTOR
		$this->co_queSTOR->LinkCustomAttributes = "";
		$this->co_queSTOR->HrefValue = "";
		$this->co_queSTOR->TooltipValue = "";

		// co_quePVOL
		$this->co_quePVOL->LinkCustomAttributes = "";
		$this->co_quePVOL->HrefValue = "";
		$this->co_quePVOL->TooltipValue = "";

		// co_queACAP
		$this->co_queACAP->LinkCustomAttributes = "";
		$this->co_queACAP->HrefValue = "";
		$this->co_queACAP->TooltipValue = "";

		// co_quePCAP
		$this->co_quePCAP->LinkCustomAttributes = "";
		$this->co_quePCAP->HrefValue = "";
		$this->co_quePCAP->TooltipValue = "";

		// co_quePCON
		$this->co_quePCON->LinkCustomAttributes = "";
		$this->co_quePCON->HrefValue = "";
		$this->co_quePCON->TooltipValue = "";

		// co_queAPEX
		$this->co_queAPEX->LinkCustomAttributes = "";
		$this->co_queAPEX->HrefValue = "";
		$this->co_queAPEX->TooltipValue = "";

		// co_quePLEX
		$this->co_quePLEX->LinkCustomAttributes = "";
		$this->co_quePLEX->HrefValue = "";
		$this->co_quePLEX->TooltipValue = "";

		// co_queLTEX
		$this->co_queLTEX->LinkCustomAttributes = "";
		$this->co_queLTEX->HrefValue = "";
		$this->co_queLTEX->TooltipValue = "";

		// co_queTOOL
		$this->co_queTOOL->LinkCustomAttributes = "";
		$this->co_queTOOL->HrefValue = "";
		$this->co_queTOOL->TooltipValue = "";

		// co_queSITE
		$this->co_queSITE->LinkCustomAttributes = "";
		$this->co_queSITE->HrefValue = "";
		$this->co_queSITE->TooltipValue = "";

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
				if ($this->nu_versaoValoracao->Exportable) $Doc->ExportCaption($this->nu_versaoValoracao);
				if ($this->ic_metCalibracao->Exportable) $Doc->ExportCaption($this->ic_metCalibracao);
				if ($this->dh_inclusao->Exportable) $Doc->ExportCaption($this->dh_inclusao);
				if ($this->nu_usuarioResp->Exportable) $Doc->ExportCaption($this->nu_usuarioResp);
				if ($this->ic_tpAtualizacao->Exportable) $Doc->ExportCaption($this->ic_tpAtualizacao);
				if ($this->qt_linhasCodLingPf->Exportable) $Doc->ExportCaption($this->qt_linhasCodLingPf);
				if ($this->vr_ipMin->Exportable) $Doc->ExportCaption($this->vr_ipMin);
				if ($this->vr_ipMed->Exportable) $Doc->ExportCaption($this->vr_ipMed);
				if ($this->vr_ipMax->Exportable) $Doc->ExportCaption($this->vr_ipMax);
				if ($this->vr_constanteA->Exportable) $Doc->ExportCaption($this->vr_constanteA);
				if ($this->vr_constanteB->Exportable) $Doc->ExportCaption($this->vr_constanteB);
				if ($this->vr_constanteC->Exportable) $Doc->ExportCaption($this->vr_constanteC);
				if ($this->vr_constanteD->Exportable) $Doc->ExportCaption($this->vr_constanteD);
				if ($this->nu_altPREC->Exportable) $Doc->ExportCaption($this->nu_altPREC);
				if ($this->nu_altFLEX->Exportable) $Doc->ExportCaption($this->nu_altFLEX);
				if ($this->nu_altRESL->Exportable) $Doc->ExportCaption($this->nu_altRESL);
				if ($this->nu_altTEAM->Exportable) $Doc->ExportCaption($this->nu_altTEAM);
				if ($this->nu_altPMAT->Exportable) $Doc->ExportCaption($this->nu_altPMAT);
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
				if ($this->co_quePREC->Exportable) $Doc->ExportCaption($this->co_quePREC);
				if ($this->co_queFLEX->Exportable) $Doc->ExportCaption($this->co_queFLEX);
				if ($this->co_queRESL->Exportable) $Doc->ExportCaption($this->co_queRESL);
				if ($this->co_queTEAM->Exportable) $Doc->ExportCaption($this->co_queTEAM);
				if ($this->co_quePMAT->Exportable) $Doc->ExportCaption($this->co_quePMAT);
				if ($this->co_queRELY->Exportable) $Doc->ExportCaption($this->co_queRELY);
				if ($this->co_queDATA->Exportable) $Doc->ExportCaption($this->co_queDATA);
				if ($this->co_queCPLX1->Exportable) $Doc->ExportCaption($this->co_queCPLX1);
				if ($this->co_queCPLX2->Exportable) $Doc->ExportCaption($this->co_queCPLX2);
				if ($this->co_queCPLX3->Exportable) $Doc->ExportCaption($this->co_queCPLX3);
				if ($this->co_queCPLX4->Exportable) $Doc->ExportCaption($this->co_queCPLX4);
				if ($this->co_queCPLX5->Exportable) $Doc->ExportCaption($this->co_queCPLX5);
				if ($this->co_queDOCU->Exportable) $Doc->ExportCaption($this->co_queDOCU);
				if ($this->co_queRUSE->Exportable) $Doc->ExportCaption($this->co_queRUSE);
				if ($this->co_queTIME->Exportable) $Doc->ExportCaption($this->co_queTIME);
				if ($this->co_queSTOR->Exportable) $Doc->ExportCaption($this->co_queSTOR);
				if ($this->co_quePVOL->Exportable) $Doc->ExportCaption($this->co_quePVOL);
				if ($this->co_queACAP->Exportable) $Doc->ExportCaption($this->co_queACAP);
				if ($this->co_quePCAP->Exportable) $Doc->ExportCaption($this->co_quePCAP);
				if ($this->co_quePCON->Exportable) $Doc->ExportCaption($this->co_quePCON);
				if ($this->co_queAPEX->Exportable) $Doc->ExportCaption($this->co_queAPEX);
				if ($this->co_quePLEX->Exportable) $Doc->ExportCaption($this->co_quePLEX);
				if ($this->co_queLTEX->Exportable) $Doc->ExportCaption($this->co_queLTEX);
				if ($this->co_queTOOL->Exportable) $Doc->ExportCaption($this->co_queTOOL);
				if ($this->co_queSITE->Exportable) $Doc->ExportCaption($this->co_queSITE);
			} else {
				if ($this->nu_ambiente->Exportable) $Doc->ExportCaption($this->nu_ambiente);
				if ($this->nu_versaoValoracao->Exportable) $Doc->ExportCaption($this->nu_versaoValoracao);
				if ($this->ic_metCalibracao->Exportable) $Doc->ExportCaption($this->ic_metCalibracao);
				if ($this->dh_inclusao->Exportable) $Doc->ExportCaption($this->dh_inclusao);
				if ($this->nu_usuarioResp->Exportable) $Doc->ExportCaption($this->nu_usuarioResp);
				if ($this->ic_tpAtualizacao->Exportable) $Doc->ExportCaption($this->ic_tpAtualizacao);
				if ($this->qt_linhasCodLingPf->Exportable) $Doc->ExportCaption($this->qt_linhasCodLingPf);
				if ($this->vr_ipMin->Exportable) $Doc->ExportCaption($this->vr_ipMin);
				if ($this->vr_ipMed->Exportable) $Doc->ExportCaption($this->vr_ipMed);
				if ($this->vr_ipMax->Exportable) $Doc->ExportCaption($this->vr_ipMax);
				if ($this->vr_constanteA->Exportable) $Doc->ExportCaption($this->vr_constanteA);
				if ($this->vr_constanteB->Exportable) $Doc->ExportCaption($this->vr_constanteB);
				if ($this->vr_constanteC->Exportable) $Doc->ExportCaption($this->vr_constanteC);
				if ($this->vr_constanteD->Exportable) $Doc->ExportCaption($this->vr_constanteD);
				if ($this->nu_altPREC->Exportable) $Doc->ExportCaption($this->nu_altPREC);
				if ($this->nu_altFLEX->Exportable) $Doc->ExportCaption($this->nu_altFLEX);
				if ($this->nu_altRESL->Exportable) $Doc->ExportCaption($this->nu_altRESL);
				if ($this->nu_altTEAM->Exportable) $Doc->ExportCaption($this->nu_altTEAM);
				if ($this->nu_altPMAT->Exportable) $Doc->ExportCaption($this->nu_altPMAT);
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
				if ($this->co_quePREC->Exportable) $Doc->ExportCaption($this->co_quePREC);
				if ($this->co_queFLEX->Exportable) $Doc->ExportCaption($this->co_queFLEX);
				if ($this->co_queRESL->Exportable) $Doc->ExportCaption($this->co_queRESL);
				if ($this->co_queTEAM->Exportable) $Doc->ExportCaption($this->co_queTEAM);
				if ($this->co_quePMAT->Exportable) $Doc->ExportCaption($this->co_quePMAT);
				if ($this->co_queRELY->Exportable) $Doc->ExportCaption($this->co_queRELY);
				if ($this->co_queDATA->Exportable) $Doc->ExportCaption($this->co_queDATA);
				if ($this->co_queCPLX1->Exportable) $Doc->ExportCaption($this->co_queCPLX1);
				if ($this->co_queCPLX2->Exportable) $Doc->ExportCaption($this->co_queCPLX2);
				if ($this->co_queCPLX3->Exportable) $Doc->ExportCaption($this->co_queCPLX3);
				if ($this->co_queCPLX4->Exportable) $Doc->ExportCaption($this->co_queCPLX4);
				if ($this->co_queCPLX5->Exportable) $Doc->ExportCaption($this->co_queCPLX5);
				if ($this->co_queDOCU->Exportable) $Doc->ExportCaption($this->co_queDOCU);
				if ($this->co_queRUSE->Exportable) $Doc->ExportCaption($this->co_queRUSE);
				if ($this->co_queTIME->Exportable) $Doc->ExportCaption($this->co_queTIME);
				if ($this->co_queSTOR->Exportable) $Doc->ExportCaption($this->co_queSTOR);
				if ($this->co_quePVOL->Exportable) $Doc->ExportCaption($this->co_quePVOL);
				if ($this->co_queACAP->Exportable) $Doc->ExportCaption($this->co_queACAP);
				if ($this->co_quePCAP->Exportable) $Doc->ExportCaption($this->co_quePCAP);
				if ($this->co_quePCON->Exportable) $Doc->ExportCaption($this->co_quePCON);
				if ($this->co_queAPEX->Exportable) $Doc->ExportCaption($this->co_queAPEX);
				if ($this->co_quePLEX->Exportable) $Doc->ExportCaption($this->co_quePLEX);
				if ($this->co_queLTEX->Exportable) $Doc->ExportCaption($this->co_queLTEX);
				if ($this->co_queTOOL->Exportable) $Doc->ExportCaption($this->co_queTOOL);
				if ($this->co_queSITE->Exportable) $Doc->ExportCaption($this->co_queSITE);
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
					if ($this->nu_versaoValoracao->Exportable) $Doc->ExportField($this->nu_versaoValoracao);
					if ($this->ic_metCalibracao->Exportable) $Doc->ExportField($this->ic_metCalibracao);
					if ($this->dh_inclusao->Exportable) $Doc->ExportField($this->dh_inclusao);
					if ($this->nu_usuarioResp->Exportable) $Doc->ExportField($this->nu_usuarioResp);
					if ($this->ic_tpAtualizacao->Exportable) $Doc->ExportField($this->ic_tpAtualizacao);
					if ($this->qt_linhasCodLingPf->Exportable) $Doc->ExportField($this->qt_linhasCodLingPf);
					if ($this->vr_ipMin->Exportable) $Doc->ExportField($this->vr_ipMin);
					if ($this->vr_ipMed->Exportable) $Doc->ExportField($this->vr_ipMed);
					if ($this->vr_ipMax->Exportable) $Doc->ExportField($this->vr_ipMax);
					if ($this->vr_constanteA->Exportable) $Doc->ExportField($this->vr_constanteA);
					if ($this->vr_constanteB->Exportable) $Doc->ExportField($this->vr_constanteB);
					if ($this->vr_constanteC->Exportable) $Doc->ExportField($this->vr_constanteC);
					if ($this->vr_constanteD->Exportable) $Doc->ExportField($this->vr_constanteD);
					if ($this->nu_altPREC->Exportable) $Doc->ExportField($this->nu_altPREC);
					if ($this->nu_altFLEX->Exportable) $Doc->ExportField($this->nu_altFLEX);
					if ($this->nu_altRESL->Exportable) $Doc->ExportField($this->nu_altRESL);
					if ($this->nu_altTEAM->Exportable) $Doc->ExportField($this->nu_altTEAM);
					if ($this->nu_altPMAT->Exportable) $Doc->ExportField($this->nu_altPMAT);
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
					if ($this->co_quePREC->Exportable) $Doc->ExportField($this->co_quePREC);
					if ($this->co_queFLEX->Exportable) $Doc->ExportField($this->co_queFLEX);
					if ($this->co_queRESL->Exportable) $Doc->ExportField($this->co_queRESL);
					if ($this->co_queTEAM->Exportable) $Doc->ExportField($this->co_queTEAM);
					if ($this->co_quePMAT->Exportable) $Doc->ExportField($this->co_quePMAT);
					if ($this->co_queRELY->Exportable) $Doc->ExportField($this->co_queRELY);
					if ($this->co_queDATA->Exportable) $Doc->ExportField($this->co_queDATA);
					if ($this->co_queCPLX1->Exportable) $Doc->ExportField($this->co_queCPLX1);
					if ($this->co_queCPLX2->Exportable) $Doc->ExportField($this->co_queCPLX2);
					if ($this->co_queCPLX3->Exportable) $Doc->ExportField($this->co_queCPLX3);
					if ($this->co_queCPLX4->Exportable) $Doc->ExportField($this->co_queCPLX4);
					if ($this->co_queCPLX5->Exportable) $Doc->ExportField($this->co_queCPLX5);
					if ($this->co_queDOCU->Exportable) $Doc->ExportField($this->co_queDOCU);
					if ($this->co_queRUSE->Exportable) $Doc->ExportField($this->co_queRUSE);
					if ($this->co_queTIME->Exportable) $Doc->ExportField($this->co_queTIME);
					if ($this->co_queSTOR->Exportable) $Doc->ExportField($this->co_queSTOR);
					if ($this->co_quePVOL->Exportable) $Doc->ExportField($this->co_quePVOL);
					if ($this->co_queACAP->Exportable) $Doc->ExportField($this->co_queACAP);
					if ($this->co_quePCAP->Exportable) $Doc->ExportField($this->co_quePCAP);
					if ($this->co_quePCON->Exportable) $Doc->ExportField($this->co_quePCON);
					if ($this->co_queAPEX->Exportable) $Doc->ExportField($this->co_queAPEX);
					if ($this->co_quePLEX->Exportable) $Doc->ExportField($this->co_quePLEX);
					if ($this->co_queLTEX->Exportable) $Doc->ExportField($this->co_queLTEX);
					if ($this->co_queTOOL->Exportable) $Doc->ExportField($this->co_queTOOL);
					if ($this->co_queSITE->Exportable) $Doc->ExportField($this->co_queSITE);
				} else {
					if ($this->nu_ambiente->Exportable) $Doc->ExportField($this->nu_ambiente);
					if ($this->nu_versaoValoracao->Exportable) $Doc->ExportField($this->nu_versaoValoracao);
					if ($this->ic_metCalibracao->Exportable) $Doc->ExportField($this->ic_metCalibracao);
					if ($this->dh_inclusao->Exportable) $Doc->ExportField($this->dh_inclusao);
					if ($this->nu_usuarioResp->Exportable) $Doc->ExportField($this->nu_usuarioResp);
					if ($this->ic_tpAtualizacao->Exportable) $Doc->ExportField($this->ic_tpAtualizacao);
					if ($this->qt_linhasCodLingPf->Exportable) $Doc->ExportField($this->qt_linhasCodLingPf);
					if ($this->vr_ipMin->Exportable) $Doc->ExportField($this->vr_ipMin);
					if ($this->vr_ipMed->Exportable) $Doc->ExportField($this->vr_ipMed);
					if ($this->vr_ipMax->Exportable) $Doc->ExportField($this->vr_ipMax);
					if ($this->vr_constanteA->Exportable) $Doc->ExportField($this->vr_constanteA);
					if ($this->vr_constanteB->Exportable) $Doc->ExportField($this->vr_constanteB);
					if ($this->vr_constanteC->Exportable) $Doc->ExportField($this->vr_constanteC);
					if ($this->vr_constanteD->Exportable) $Doc->ExportField($this->vr_constanteD);
					if ($this->nu_altPREC->Exportable) $Doc->ExportField($this->nu_altPREC);
					if ($this->nu_altFLEX->Exportable) $Doc->ExportField($this->nu_altFLEX);
					if ($this->nu_altRESL->Exportable) $Doc->ExportField($this->nu_altRESL);
					if ($this->nu_altTEAM->Exportable) $Doc->ExportField($this->nu_altTEAM);
					if ($this->nu_altPMAT->Exportable) $Doc->ExportField($this->nu_altPMAT);
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
					if ($this->co_quePREC->Exportable) $Doc->ExportField($this->co_quePREC);
					if ($this->co_queFLEX->Exportable) $Doc->ExportField($this->co_queFLEX);
					if ($this->co_queRESL->Exportable) $Doc->ExportField($this->co_queRESL);
					if ($this->co_queTEAM->Exportable) $Doc->ExportField($this->co_queTEAM);
					if ($this->co_quePMAT->Exportable) $Doc->ExportField($this->co_quePMAT);
					if ($this->co_queRELY->Exportable) $Doc->ExportField($this->co_queRELY);
					if ($this->co_queDATA->Exportable) $Doc->ExportField($this->co_queDATA);
					if ($this->co_queCPLX1->Exportable) $Doc->ExportField($this->co_queCPLX1);
					if ($this->co_queCPLX2->Exportable) $Doc->ExportField($this->co_queCPLX2);
					if ($this->co_queCPLX3->Exportable) $Doc->ExportField($this->co_queCPLX3);
					if ($this->co_queCPLX4->Exportable) $Doc->ExportField($this->co_queCPLX4);
					if ($this->co_queCPLX5->Exportable) $Doc->ExportField($this->co_queCPLX5);
					if ($this->co_queDOCU->Exportable) $Doc->ExportField($this->co_queDOCU);
					if ($this->co_queRUSE->Exportable) $Doc->ExportField($this->co_queRUSE);
					if ($this->co_queTIME->Exportable) $Doc->ExportField($this->co_queTIME);
					if ($this->co_queSTOR->Exportable) $Doc->ExportField($this->co_queSTOR);
					if ($this->co_quePVOL->Exportable) $Doc->ExportField($this->co_quePVOL);
					if ($this->co_queACAP->Exportable) $Doc->ExportField($this->co_queACAP);
					if ($this->co_quePCAP->Exportable) $Doc->ExportField($this->co_quePCAP);
					if ($this->co_quePCON->Exportable) $Doc->ExportField($this->co_quePCON);
					if ($this->co_queAPEX->Exportable) $Doc->ExportField($this->co_queAPEX);
					if ($this->co_quePLEX->Exportable) $Doc->ExportField($this->co_quePLEX);
					if ($this->co_queLTEX->Exportable) $Doc->ExportField($this->co_queLTEX);
					if ($this->co_queTOOL->Exportable) $Doc->ExportField($this->co_queTOOL);
					if ($this->co_queSITE->Exportable) $Doc->ExportField($this->co_queSITE);
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

		$valor = ew_ExecuteScalar("select max(nu_versaoValoracao) From ambiente_valoracao where nu_ambiente = '" . $rsnew["nu_ambiente"] . "'");
		$rsnew["nu_versao"] = $valor + 1;                                                                                                                                           
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

		$valor = ew_ExecuteScalar("select max(nu_versaoValoracao) From ambiente_valoracao where nu_ambiente = '" . $this->nu_ambiente->ViewValue . "'");                                    
		$this->nu_versao->EditValue = "" . $valor + 1 . ""; 
	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
