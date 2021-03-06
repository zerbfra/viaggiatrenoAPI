<?php

include "classes/class.treno.php";
include "classes/class.viaggio.php";

$data = json_decode(file_get_contents('php://input'), true);

$stazioneP = $data['partenza']; //"5032";
$stazioneA = $data['arrivo']; //"1700";

$date = toTrenitaliaDate(round($data['data'],0));

$soluzioni = trovaSoluzioniViaggio($stazioneP,$stazioneA,$date);

header('Content-Type: application/json');
echo json_encode(array('status'=>"ok", 'response'=>$soluzioni));
//echo json_encode($soluzioni);

function trovaSoluzioniViaggio($stazioneP,$stazioneA,$data) {

  $link = "http://www.viaggiatreno.it/viaggiatrenonew/resteasy/viaggiatreno/soluzioniViaggioNew/$stazioneP/$stazioneA/$data";

  $response = file_get_contents($link);

  $json = json_decode($response);

  $soluzioni = $json->soluzioni;
  $viaggi = array();

  foreach($soluzioni as $record) {
      $viaggio = new Viaggio;
      $viaggio->codOrigine = $stazioneP;
      $viaggio->codDestinazione = $stazioneA;


      // sembra che non vengano più restituiti dal json di trenitalia!
      $viaggio->origine = $json->origine;
      $viaggio->destinazione = $json->destinazione;

      $viaggio->durata = $record->durata;



      $treniTragitto = array();

      foreach($record->vehicles as $recordTreno) {
        $treno = new Treno;

        $treno->numero = $recordTreno->numeroTreno;
        $treno->categoria = $recordTreno->categoriaDescrizione;

        $treno->origine = normalizzaNome($recordTreno->origine);
        $treno->destinazione = normalizzaNome($recordTreno->destinazione);

        $treno->orarioPartenza = completeToTimestamp($recordTreno->orarioPartenza);

        $treno->orarioArrivo = completeToTimestamp($recordTreno->orarioArrivo);


        array_push($treniTragitto,$treno);


      }

      $viaggio->tragitto = $treniTragitto;
      array_push($viaggi,$viaggio);



  }

  return $viaggi;

}






?>
