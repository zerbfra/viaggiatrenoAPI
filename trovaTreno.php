<?php

include "classes/class.treno.php";

$data = json_decode(file_get_contents('php://input'), true);

$numTreno = $data['numero'];
$trovaFermate = $data['includiFermate'];
//$numTreno = "20754"; //752 doppio
if(isset($data['origine'])) $origine = $data['origine'];
else $origine = null;

$treno = new Treno;

$treno->numero = $numTreno;

$treni = array();

if($origine == null) {

  $stazioniPartenza = $treno->trovaStazioniPossibili();

  foreach($stazioniPartenza as $stazionePossibile) {
    $trenoTmp = new Treno;
    $trenoTmp->numero = $numTreno;
    $trenoTmp->stazioneP = $stazionePossibile;

    $trenoTmp->dettagliTreno();
    if($trovaFermate) $trenoTmp->trovaFermate();
    array_push($treni,$trenoTmp);
  }
} else {
  // origine fornita
  $trenoTmp = new Treno;
  $trenoTmp->numero = $numTreno;

  $stazione = new Stazione;
  $stazione->id = $origine;

  $trenoTmp->stazioneP = $stazione;
  $trenoTmp->dettagliTreno();
  if($trovaFermate) $trenoTmp->trovaFermate();
  array_push($treni,$trenoTmp);
}

header('Content-Type: application/json');
echo json_encode(array('status'=>"ok", 'response'=>$treni));

?>
