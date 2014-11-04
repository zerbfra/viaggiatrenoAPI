<?php

include "classes/class.treno.php";

//$data = json_decode(file_get_contents('php://input'), true);

//$numeroTreno = $data['numero'];

//$numTreno = "20754"; //752 doppio

$idStazione = "S01700";
$timestamp = time();

$stazione = new Stazione;

$treniInArrivo = $stazione->trovaTreniInArrivo($idStazione,$timestamp);

header('Content-Type: application/json');
echo json_encode($treniInArrivo);


?>
