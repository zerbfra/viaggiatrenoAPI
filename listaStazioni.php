<?php

include "classes/class.stazione.php";

$stazioni = recuperaStazioni();

header('Content-Type: application/json');
echo json_encode($stazioni);


/**** FUNZIONI ****/


// restituisce tutte le stazioni italiane ordinate in ordine alfabetico come oggetto Stazione
function recuperaStazioni() {
  $letters = range('a', 'z');

  $stazioniTmp = array();

  foreach ($letters as $value) {

    $response = file_get_contents("http://www.viaggiatreno.it/viaggiatrenonew/resteasy/viaggiatreno/autocompletaStazione/$value",false);

    $lines = explode("\n", $response);


    $stazioniTmp = array_merge($stazioniTmp, $lines);

  }

  //print_r($stazioniTmp);


  $stazioni = array();

  foreach ($stazioniTmp as $line) {

    $record = explode("|",$line);

    $stazione = new Stazione;

    $stazione->id = $record[1];
    $stazione->nome = $record[0];

    //$stazione->trovaStazione($record[1],$record[0]);

    //$stazione->display();
    if(!empty($stazione->id)) array_push($stazioni, $stazione);
  }

  return $stazioni;



}





?>
