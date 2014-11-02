<?php

include "classes/class.treno.php";
include "classes/class.viaggio.php";



$stazioneP = "11145";
$stazioneA = "1700";

$data = "2014-10-31T22:00:00";


$soluzioni = trovaSoluzioniViaggio($stazioneP,$stazioneA,$data);

header('Content-Type: application/json');
echo json_encode($soluzioni);

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

      $viaggio->origine = $record->origine;
      $viaggio->destinazione = $record->destinazione;

      $viaggio->durata = $record->durata;



      $treniTragitto = array();

      foreach($record->vehicles as $recordTreno) {
        $treno = new Treno;

        $treno->numero = $recordTreno->numeroTreno;
        $treno->categoria = $recordTreno->categoriaDescrizione;


        $treno->orarioPartenza = completeToTimestamp($recordTreno->orarioPartenza);
        $treno->orarioArrivo = completeTime($recordTreno->orarioPartenza,$recordTreno->orarioArrivo);


        array_push($treniTragitto,$treno);


      }

      $viaggio->tragitto = $treniTragitto;
      array_push($viaggi,$viaggio);



  }

  return $viaggi;


}





?>