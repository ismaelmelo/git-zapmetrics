<?php
echo "Por favor aguarde! Não finalize esta janela até que o processo seja finalizado.</P><BR>";
// Iniciamos o "contador"
list($usec, $sec) = explode(' ', microtime());
$script_start = (float) $sec + (float) $usec;

include_once "../../config.php";

$conn = odbc_connect("Driver={SQL Server Native Client 11.0};Server=" . $argHostname . ";Database=" . $argDatabasename . ";", $argUsername, $argPassword);
$exec = odbc_exec($conn, "Exec AtualizaRegistrosRedmine");

// Terminamos o "contador" e exibimos
list($usec, $sec) = explode(' ', microtime());
$script_end = (float) $sec + (float) $usec;
$elapsed_time = round($script_end - $script_start, 5);
echo "<br>Base de dados atualizada com sucesso!</P>";
echo 'Tempo de execução: ', $elapsed_time, ' segundos. Uso de memória: ', round(((memory_get_peak_usage(true) / 1024) / 1024), 2), 'Mb';
?>