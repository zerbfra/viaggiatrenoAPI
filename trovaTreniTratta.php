<?php

include "classes/class.treno.php";

$data = json_decode(file_get_contents('php://input'), true);

$tragitto = $data['tratta'];

//$numTreno = $data['numero'];
$trovaFermate = $data['includiFermate'];
//$numTreno = "20754"; //752 doppio

$treni = array();

foreach($tragitto as $parziale) {

  $treno = new Treno;
  $treno->numero = $parziale;
  $stazioniPartenza = $treno->trovaStazioniPossibili();

  foreach($stazioniPartenza as $stazionePossibile) {
    $trenoTmp = new Treno;
    $trenoTmp->numero = $parziale;
    $trenoTmp->stazioneP = $stazionePossibile;

    $trenoTmp->dettagliTreno();
    if($trovaFermate) $trenoTmp->trovaFermate();

    array_push($treni,$trenoTmp);

  }



}

header('Content-Type: application/json');
echo json_encode(array('status'=>"ok", 'response'=>$treni));

?>
