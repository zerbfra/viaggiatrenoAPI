<?php

include "classes/class.treno.php";

//$data = json_decode(file_get_contents('php://input'), true);

//$numeroTreno = $data['numero'];

//$numTreno = "20754"; //752 doppio

$data = json_decode(file_get_contents('php://input'), true);

$idStazione = $data['stazione'];

$timestamp = time();

$stazione = new Stazione;

$treniInPartenza = $stazione->trovaTreniInPartenza($idStazione,$timestamp);

header('Content-Type: application/json');
echo json_encode(array('status'=>"ok", 'response'=>$treniInPartenza));


?>
