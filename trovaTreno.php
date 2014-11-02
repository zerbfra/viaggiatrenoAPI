<?php

include "classes/class.treno.php";


$numTreno = "20754"; //752 doppio

$treno = new Treno;

$treno->numero = $numTreno;

$stazioniPartenza = $treno->trovaStazioniPossibili();

$treni = array();

foreach($stazioniPartenza as $stazionePossibile) {
    $trenoTmp = new Treno;
    $trenoTmp->numero = $numTreno;
    $trenoTmp->stazioneP = $stazionePossibile;

    $trenoTmp->dettagliTreno();
    $trenoTmp->trovaFermate();
    array_push($treni,$trenoTmp);
}


header('Content-Type: application/json');
echo json_encode($treni);

?>
