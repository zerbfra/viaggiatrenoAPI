<?php

include "classes/class.treno.php";

$data = json_decode(file_get_contents('php://input'), true);

$tragitto = $data['tratta'];
//print_r($tragitto);
$cambi = array();
$treni = array();

foreach($tragitto as $trenoFornito) {

  $treno = new Treno();
  $treno->numero = $trenoFornito;
  $stazioniPartenza = $treno->trovaStazioniPossibili();
  // assumo che quella giusta sia la prima altrimenti AMEN
  if(count ($stazioniPartenza) > 0) {
     $treno->stazioneP = $stazioniPartenza[0];
     array_push($treni,$treno);
  }

}
//print_r($treni);
for($index = 1; $index < count($treni); $index++) {
  $trenoCambio = $treni[$index-1];
  $nextTreno = $treni[$index];
  $cambio = $trenoCambio->trovaCambioCon($nextTreno);

  array_push($cambi,$cambio);
}

header('Content-Type: application/json');
echo json_encode(array('status'=>"ok", 'response'=>$cambi));

/*
for($index = 1; $index < count($tragitto); $index++) {

  $treno = new Treno;
  $treno->numero = $tragitto[$index-1];

  $stazioniPartenza = $treno->trovaStazioniPossibili();

  $treni = array();

  foreach($stazioniPartenza as $stazionePossibile) {
      $trenoTmp = new Treno;
      $trenoTmp->numero = $tragitto[$index-1];
      $trenoTmp->stazioneP = $stazionePossibile;


      array_push($treni,$trenoTmp);
  }

  $treno2 = new Treno;

  $treno2->numero = $tragitto[$index];

  $stazioniPartenza = $treno2->trovaStazioniPossibili();



  foreach($stazioniPartenza as $stazionePossibile) {
      $trenoTmp = new Treno;

      $trenoTmp->stazioneP = $stazionePossibile;
      $trenoTmp->numero = $tragitto[$index];

      foreach($treni as $trenoCambio) {
        $cambio = $trenoCambio->trovaCambioCon($trenoTmp);

        array_push($cambi,$cambio);
      }
  }


}
*/


?>
