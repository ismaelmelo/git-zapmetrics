<?php
$parametro = $_POST['parametro'];
$tpacao = $_POST['tpacao'];

include_once "config.php";

$id = (int) $parametro;

// Dados da conex�o
$conn = new COM ("ADODB.Connection") or die("N�o foi poss�vel carregar o ADO");
//SQLNCLI11 SQLOLEDB
$connStr = "Provider=" . $argProvider . ";Persist Security Info=False;Data Source=" . $argHostname . ";Initial Catalog=" . $argDatabasename . ";User Id=" . $argUsername . ";Password=" . $argPassword . "";

//--------------------------------------------

If ($tpacao == "CalcularPF") {	

	$conn->open($connStr);

	//Dados da sele��o 1
	$instrucaoSQL = "select nu_parametro from tpmanutencao where nu_tpManutencao = " . $id . "";
	$rs = $conn->execute($instrucaoSQL);
	 
	$num_columns = $rs->Fields->Count();
	 
	for ($i=0; $i < $num_columns; $i++) {
		$fld[$i] = $rs->Fields($i);
	}

	$par = (int) $fld[0]->value;

	//Dados da sele��o 2
	$instrucaoSQL = "select vr_parSisp From parsisp_versao where nu_parSisp = " . $par . " AND nu_versao = (select max(nu_versao) from parsisp_versao where nu_parSisp = " . $par . ")";
	$rs = $conn->execute($instrucaoSQL);
	 
	$num_columns = $rs->Fields->Count();
	 
	for ($i=0; $i < $num_columns; $i++)
	{
		$fld[$i] = $rs->Fields($i);
	}

	$vrparametro = (float) $fld[0]->value;

	echo $vrparametro;
	
	$rs->Close();
	$conn->Close();
	 
	$rs = null;
	$conn = null;
	
} 

if ($tpacao == "ObterDistribuicaoFase") {

	$conn->open($connStr);
	
	$soma = 0;

	//Dados da sele��o 1
	$instrucaoSQL = "select pc_varFasesRoteiro from contagempf where nu_contagem = " . $id . "";
	$rs = $conn->execute($instrucaoSQL);
	 
	$num_columns = $rs->Fields->Count();
	 

	for ($i=0; $i < $num_columns; $i++) {
		$fld[$i] = $rs->Fields($i);
	}	

	$vrparametro = $fld[0]->value;
	
	echo $vrparametro;
	
	$rs->Close();
	$conn->Close();
	 
	$rs = null;
	$conn = null;

}

if ($tpacao == "OTM") {

	$conn->open($connStr);

	//Dados da sele��o 1
	$instrucaoSQL = "select m.ic_tpMetrica from tpmetrica m right join contagempf c on m.nu_tpMetrica = c.nu_tpMetrica where nu_contagem = " . $id . "";
	$rs = $conn->execute($instrucaoSQL);
	 
	$num_columns = $rs->Fields->Count();
	 

	for ($i=0; $i < $num_columns; $i++) {
		$fld[$i] = $rs->Fields($i);
	}	

	$vrparametro = $fld[0]->value;
	
	echo "D";

	$rs->Close();
	$conn->Close();
	 
	$rs = null;
	$conn = null;
}

if ($tpacao == "OMC") {

	$conn->open($connStr);

	//Dados da sele��o 1
	$instrucaoSQL = "select ic_modeloCalculo from tpmanutencao where nu_tpManutencao = " . $id . "";
	$rs = $conn->execute($instrucaoSQL);
	 
	$num_columns = $rs->Fields->Count();
	 

	for ($i=0; $i < $num_columns; $i++) {
		$fld[$i] = $rs->Fields($i);
	}	

	$vrparametro = $fld[0]->value;
	
	echo "I";

	$rs->Close();
	$conn->Close();
	 
	$rs = null;
	$conn = null;
}

if ($tpacao == "VerificaAplicabilidadeCalculoPfFases") {

	$conn->open($connStr);

	//Dados da sele��o 1
	$instrucaoSQL = "select ic_utilizaFaseRoteiro from tpmanutencao where nu_tpManutencao = " . $id . "";
	$rs = $conn->execute($instrucaoSQL);
	 
	$num_columns = $rs->Fields->Count();
	 

	for ($i=0; $i < $num_columns; $i++) {
		$fld[$i] = $rs->Fields($i);
	}	

	$vrparametro = $fld[0]->value;
	
	echo $vrparametro;

	$rs->Close();
	$conn->Close();
	 
	$rs = null;
	$conn = null;
}

//Calcular m�dia ponderada do fator de complexidade sist�mica de Jones das contagens da mesma solicita��o:
if ($tpacao == "CalcularMediaJones") {
	
}

//Calcular quantidade de pontos de fun��o devido ao fator de criticidade
if ($tpacao == "CalcularPfPorCriticidade") {
	
}

//Calcular quantidade de pontos de fun��o final, para faturamento
if ($tpacao == "CalcularTamanhoBaseFaturamento") {
	
}

//Calcular prazo estimado (em meses)
if ($tpacao == "CalcularPzEstimadoMeses") {
	
}

//Calcular prazo estimado (em dias)
if ($tpacao == "CalcularPzEstimadoDias") {
	
}

//Calcular esfor�o estimado (em horas)
if ($tpacao == "CalcularEsforcoEstimadoHoras") {
	
}

//Calcular custo/investimento de desenvolvimento (em R$)
if ($tpacao == "CalcularCustoDev") {
	
}

//Calcular custo/investimento total (em R$)
if ($tpacao == "CalcularCustoTotal") {
	
}

//Calcular dimensionamento da equipe de desenvolvimento
if ($tpacao == "CalcularDimEquipe") {
	
}
 
?>