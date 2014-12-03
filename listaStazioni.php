<?php

include "classes/class.stazione.php";




$stazioni = recuperaStazioni();

header('Content-Type: application/json');
echo json_encode($stazioni);


/**** FUNZIONI ****/


// restituisce tutte le stazioni italiane ordinate in ordine alfabetico come oggetto Stazione
function recuperaStazioni() {
  //modificare con lettere da recuperare!
  $letters = range('a', 'z');

  $stazioniTmp = array();

  foreach ($letters as $value) {

    $response = file_get_contents("http://www.viaggiatreno.it/viaggiatrenonew/resteasy/viaggiatreno/autocompletaStazione/$value",false);

    $lines = explode("\n", $response);


    $stazioniTmp = array_merge($stazioniTmp, $lines);

  }

  //print_r($stazioniTmp);

$db = new SQLite3('seguitreno.db');
  $stazioni = array();

  foreach ($stazioniTmp as $line) {

    $record = explode("|",$line);

    $stazione = new Stazione;

    $stazione->id = $record[1];


    $nome = $record[0];

    $nome = strtolower($nome);

    $nome = str_replace("`","'",$nome);

    $nome = str_replace("a'","à",$nome);
    $nome = str_replace("e'","è",$nome);
    $nome = str_replace("i'","ì",$nome);
    $nome = str_replace("o'","ò",$nome);
    $nome = str_replace("u'","ù",$nome);

    $nome = ucwords($nome);

    $nome = str_replace("'a","'A",$nome);
    $nome = str_replace("'e","'E",$nome);
    $nome = str_replace("'i","'I",$nome);
    $nome = str_replace("'o","'O",$nome);
    $nome = str_replace("'u","'U",$nome);


    $stazione->nome = $nome;


    $stazione->trovaStazione($record[1],$nome);

    //$stazione->display();
    if(!empty($stazione->id))  {
      array_push($stazioni, $stazione);
      $nome = str_replace("'","''",$stazione->nome);
      if($stazione->lat) {
        $stazione->lat = round($stazione->lat,6);
        $stazione->lon = round($stazione->lon,6);
      }
      $db->query("INSERT INTO stazioni (id,nome,regione,lat,lon) VALUES('$stazione->id','$nome','$stazione->regione','$stazione->lat','$stazione->lon')");


    }
  }

  return $stazioni;



}





?>
