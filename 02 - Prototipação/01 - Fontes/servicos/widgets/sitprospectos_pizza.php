<?php
header("Content-Type: text/html; charset=ISO-8859-1",true);
$legTexto = $_GET['legTexto'];
$legValor = $_GET['legValor'];
$largura = 430;
$altura = 370;
$titulo = $_GET['titulo'];

include_once "../../config.php";

$conn1 = new COM ("ADODB.Connection") or die("Não foi possível carregar o ADO");
$conn2 = new COM ("ADODB.Connection") or die("Não foi possível carregar o ADO");
$conn3 = new COM ("ADODB.Connection") or die("Não foi possível carregar o ADO");
$conn4 = new COM ("ADODB.Connection") or die("Não foi possível carregar o ADO");
$conn5 = new COM ("ADODB.Connection") or die("Não foi possível carregar o ADO");

$connStr = "Provider=" . $argProvider . ";Persist Security Info=False;Data Source=" . $argHostname . ";Initial Catalog=" . $argDatabasename . ";User Id=" . $argUsername . ";Password=" . $argPassword . "";

$conn1->open($connStr);
//Dados da seleção 1
$instrucaoSQL = "select count(nu_prospecto) from prospecto where ic_stProspecto = 'F' AND ic_ativo = 'S'";
$rs1 = $conn1->execute($instrucaoSQL);
$num_columns = $rs1->Fields->Count();
for ($i=0; $i < $num_columns; $i++) {
	$fld[$i] = $rs1->Fields($i);
}	
$qtdeProspectosF = $fld[0]->value;
$rs1->Close();
$conn1->Close();
$rs1 = null;
$conn1 = null;

$conn2->open($connStr);
//Dados da seleção 2
$instrucaoSQL = "select count(nu_prospecto) from prospecto where ic_stProspecto = 'E' AND ic_ativo = 'S'";
$rs2 = $conn2->execute($instrucaoSQL);
$num_columns = $rs2->Fields->Count();
for ($i=0; $i < $num_columns; $i++) {
	$fld[$i] = $rs2->Fields($i);
}	
$qtdeProspectosE = $fld[0]->value;
$rs2->Close();
$conn2->Close();
$rs2 = null;
$conn2 = null;

$conn3->open($connStr);
//Dados da seleção 3
$instrucaoSQL = "select count(nu_prospecto) from prospecto where ic_stProspecto = 'C' AND ic_ativo = 'S'";
$rs3 = $conn3->execute($instrucaoSQL);
$num_columns = $rs3->Fields->Count();
for ($i=0; $i < $num_columns; $i++) {
	$fld[$i] = $rs3->Fields($i);
}	
$qtdeProspectosC = $fld[0]->value;
$rs3->Close();
$conn3->Close();
$rs3 = null;
$conn3 = null;

$conn4->open($connStr);
//Dados da seleção 4
$instrucaoSQL = "select count(nu_prospecto) from prospecto where ic_stProspecto = 'S' AND ic_ativo = 'S'";
$rs4 = $conn4->execute($instrucaoSQL);
$num_columns = $rs4->Fields->Count();
for ($i=0; $i < $num_columns; $i++) {
	$fld[$i] = $rs4->Fields($i);
}	
$qtdeProspectosS = $fld[0]->value;
$rs4->Close();
$conn4->Close();
$rs4 = null;
$conn4 = null;

$conn5->open($connStr);
//Dados da seleção 5
$instrucaoSQL = "select count(nu_prospecto) from prospecto where ic_stProspecto = 'O' AND ic_ativo = 'S'";
$rs5 = $conn5->execute($instrucaoSQL);
$num_columns = $rs5->Fields->Count();
for ($i=0; $i < $num_columns; $i++) {
	$fld[$i] = $rs5->Fields($i);
}	
$qtdeProspectosO = $fld[0]->value;
$rs5->Close();
$conn5->Close();
$rs5 = null;
$conn5 = null;
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
          ['Fila',     <?php echo $qtdeProspectosF; ?>],
          ['Executando',      <?php echo $qtdeProspectosE; ?>],
          ['Suspenso',  <?php echo $qtdeProspectosS; ?>],
          ['Cancelado', <?php echo $qtdeProspectosC; ?>],
          ['Concluído',    <?php echo $qtdeProspectosO; ?>]
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