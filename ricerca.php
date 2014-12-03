<?php

include "classes/class.treno.php";
include "classes/class.viaggio.php";

$data = json_decode(file_get_contents('php://input'), true);

if(isset($data['partenza']) && isset($data['arrivo'])) {

  $stazioneP = $data['partenza']; //"5032";
  $stazioneA = $data['arrivo']; //"1700";
  $date = toTrenitaliaDate(round($data['data'],0));
  $soluzioni = trovaSoluzioniViaggio($stazioneP,$stazioneA,$date);

} else {
  $numero = $data['numero']; //numero treno
  $soluzioni = trovaTrenoDiretto($numero);
}

header('Content-Type: application/json');
echo json_encode(array('status'=>"ok", 'response'=>$soluzioni));
//echo json_encode($soluzioni);

function trovaSoluzioniViaggio($stazioneP,$stazioneA,$data) {

  $link = "http://www.viaggiatreno.it/viaggiatrenonew/resteasy/viaggiatreno/soluzioniViaggioNew/$stazioneP/$stazioneA/$data";

  $response = file_get_contents($link);

  $json = json_decode($response);

  $soluzioni = $json->soluzioni;
  $treni = array();

  foreach($soluzioni as $record) {

      if(count($record->vehicles) == 1) {

        $recordTreno = $record->vehicles[0];
        $treno = new Treno;

        $treno->numero = $recordTreno->numeroTreno;
        $treno->categoria = $recordTreno->categoriaDescrizione;

        $treno->origine = normalizzaNome($recordTreno->origine);
        $treno->destinazione = normalizzaNome($recordTreno->destinazione);

        $treno->orarioPartenza = completeToTimestamp($recordTreno->orarioPartenza);

        $treno->orarioArrivo = completeToTimestamp($recordTreno->orarioArrivo);

        array_push($treni,$treno);

      }

  }

  return $treni;

}

function trovaTrenoDiretto($numTreno) {

  $treno = new Treno;
  $treno->numero = $numTreno;

  $treni = array();

  $stazioniPartenza = $treno->trovaStazioniPossibili();

  foreach($stazioniPartenza as $stazionePossibile) {
    $trenoTmp = new Treno;
    $trenoTmp->numero = $numTreno;
    $trenoTmp->stazioneP = $stazionePossibile;

    $trenoTmp->dettagliTreno();
    $trenoTmp->trovaFermate();
    array_push($treni,$trenoTmp);
  }

  return $treni;

}

?>
