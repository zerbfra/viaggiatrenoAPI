<?php

include "classes/class.stazione.php";

$parziale = "MA";

$stazioni = completaStazione($parziale);

echo json_encode($stazioni);


/**** FUNZIONI ****/



function completaStazione($parziale) {


  $stazioniTmp = [];
  $link =  "www.viaggiatreno.it/viaggiatrenonew/resteasy/viaggiatreno/autocompletaStazione/$parziale";

  $response = curl_file_get_contents($link,false);
  $lines = explode("\n", $response);
  $stazioniTmp = array_merge($stazioniTmp, $lines);


  $stazioni = [];

  foreach ($stazioniTmp as $line) {
    $record = explode("|",$line);
    $stazione = new Stazione;

    $stazione->nome = $record[0];
    $stazione->id = $record[1];

    $stazione->trovaRegione();
    //$stazione->display();
    if(!empty($stazione->id)) array_push($stazioni, $stazione);
  }

  return $stazioni;

}



?>
