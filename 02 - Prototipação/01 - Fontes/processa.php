<?php
$parametro = $_POST['parametro'];
$tpacao = $_POST['tpacao'];

include_once "config.php";

$id = (int) $parametro;

// Dados da conexão
$conn = new COM ("ADODB.Connection") or die("Nâo foi possível carregar o ADO");
//SQLNCLI11 SQLOLEDB
$connStr = "Provider=" . $argProvider . ";Persist Security Info=False;Data Source=" . $argHostname . ";Initial Catalog=" . $argDatabasename . ";User Id=" . $argUsername . ";Password=" . $argPassword . "";

//--------------------------------------------

If ($tpacao == "CalcularPF") {	

	$conn->open($connStr);

	//Dados da seleção 1
	$instrucaoSQL = "select nu_parametro from tpmanutencao where nu_tpManutencao = " . $id . "";
	$rs = $conn->execute($instrucaoSQL);
	 
	$num_columns = $rs->Fields->Count();
	 
	for ($i=0; $i < $num_columns; $i++) {
		$fld[$i] = $rs->Fields($i);
	}

	$par = (int) $fld[0]->value;

	//Dados da seleção 2
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

	//Dados da seleção 1
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

	//Dados da seleção 1
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

	//Dados da seleção 1
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

	//Dados da seleção 1
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

//Calcular média ponderada do fator de complexidade sistêmica de Jones das contagens da mesma solicitação:
if ($tpacao == "CalcularMediaJones") {
	
}

//Calcular quantidade de pontos de função devido ao fator de criticidade
if ($tpacao == "CalcularPfPorCriticidade") {
	
}

//Calcular quantidade de pontos de função final, para faturamento
if ($tpacao == "CalcularTamanhoBaseFaturamento") {
	
}

//Calcular prazo estimado (em meses)
if ($tpacao == "CalcularPzEstimadoMeses") {
	
}

//Calcular prazo estimado (em dias)
if ($tpacao == "CalcularPzEstimadoDias") {
	
}

//Calcular esforço estimado (em horas)
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