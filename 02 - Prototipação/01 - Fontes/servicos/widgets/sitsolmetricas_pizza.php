<?php
header("Content-Type: text/html; charset=ISO-8859-1",true);
$legTexto = $_GET['legTexto'];
$legValor = $_GET['legValor'];
$largura = 430;
$altura = 370;
$titulo = $_GET['titulo'];

include_once "../../config.php";

$connStr = "Provider=" . $argProvider . ";Persist Security Info=False;Data Source=" . $argHostname . ";Initial Catalog=" . $argDatabasename . ";User Id=" . $argUsername . ";Password=" . $argPassword . "";

$conn1 = new COM ("ADODB.Connection") or die("Não foi possível carregar o ADO");
$conn1->open($connStr);
//Dados da seleção 1
$instrucaoSQL = "select count(nu_solMetricas) from solicitacaoMetricas where ic_stSolicitacao = 'A'";
$rs1 = $conn1->execute($instrucaoSQL);
$num_columns = $rs1->Fields->Count();
for ($i=0; $i < $num_columns; $i++) {
	$fld[$i] = $rs1->Fields($i);
}	
$qtdeSolMetA = $fld[0]->value;
$rs1->Close();
$conn1->Close();
$rs1 = null;
$conn1 = null;

$conn2 = new COM ("ADODB.Connection") or die("Não foi possível carregar o ADO");
$conn2->open($connStr);
//Dados da seleção 2
$instrucaoSQL = "select count(nu_solMetricas) from solicitacaoMetricas where ic_stSolicitacao = 'E'";
$rs2 = $conn2->execute($instrucaoSQL);
$num_columns = $rs2->Fields->Count();
for ($i=0; $i < $num_columns; $i++) {
	$fld[$i] = $rs2->Fields($i);
}	
$qtdeSolMetE = $fld[0]->value;
$rs2->Close();
$conn2->Close();
$rs2 = null;
$conn2 = null;

$conn3 = new COM ("ADODB.Connection") or die("Não foi possível carregar o ADO");
$conn3->open($connStr);
//Dados da seleção 3
$instrucaoSQL = "select count(nu_solMetricas) from solicitacaoMetricas where ic_stSolicitacao = 'F'";
$rs3 = $conn3->execute($instrucaoSQL);
$num_columns = $rs3->Fields->Count();
for ($i=0; $i < $num_columns; $i++) {
	$fld[$i] = $rs3->Fields($i);
}	
$qtdeSolMetF = $fld[0]->value;
$rs3->Close();
$conn3->Close();
$rs3 = null;
$conn3 = null;

$conn4 = new COM ("ADODB.Connection") or die("Não foi possível carregar o ADO");
$conn4->open($connStr);
//Dados da seleção 4
$instrucaoSQL = "select count(nu_solMetricas) from solicitacaoMetricas where ic_stSolicitacao = 'C'";
$rs4 = $conn4->execute($instrucaoSQL);
$num_columns = $rs4->Fields->Count();
for ($i=0; $i < $num_columns; $i++) {
	$fld[$i] = $rs4->Fields($i);
}	
$qtdeSolMetC = $fld[0]->value;
$rs4->Close();
$conn4->Close();
$rs4 = null;
$conn4 = null;
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<html>
  <head>
	<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" />
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $legTexto; ?>', '<?php echo $legValor; ?>'],
          ['Abertas',     <?php echo $qtdeSolMetA; ?>],
          ['Em andamento',      <?php echo $qtdeSolMetE; ?>],
          ['Finalizadas',  <?php echo $qtdeSolMetF; ?>],
          ['Canceladas', <?php echo $qtdeSolMetC; ?>]
        ]);

        var options = {
          title: '<?php echo $titulo; ?>',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="piechart_3d" style="width: <?php echo $largura; ?>px; height: <?php echo $altura; ?>px;"></div>
  </body>
</html>