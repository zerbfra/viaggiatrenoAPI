<?php

include "stazione.php";

$stazioni = recuperaStazioni();

echo json_encode($stazioni);


/**** FUNZIONI ****/



function recuperaStazioni() {
  $letters = range('a', 'z');

  $stazioniTmp = [];

  foreach ($letters as $value) {

    $link =  "www.viaggiatreno.it/viaggiatrenonew/resteasy/viaggiatreno/autocompletaStazione/$value";
    //echo $link;

    $response = curl_file_get_contents($link,false);

    $lines = explode("\n", $response);


    if(!empty($lines)) $stazioniTmp = array_merge($stazioniTmp, $lines); 

  }

  $stazioni = [];

  foreach ($stazioniTmp as $line) {
    $record = explode("|",$line);
    $stazione = new Stazione;

    $stazione->nome = $record[0];
    $stazione->id = $record[1];

      //$stazione->display();

    array_push($stazioni, $stazione);
  }

  return $stazioni;

}



function curl_file_get_contents($url,$verbose) {
  $curl = curl_init();

  curl_setopt($curl, CURLOPT_HEADER, 0);

  curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64; rv:11.0) Gecko/20100101 Firefox/16.0.2');

  curl_setopt($curl,CURLOPT_URL,$url); //The URL to fetch. This can also be set when initializing a session with curl_init().
  curl_setopt($curl,CURLOPT_RETURNTRANSFER,TRUE); //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
  curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,10); //The number of seconds to wait while trying to connect.  


  curl_setopt($curl, CURLOPT_TIMEOUT, 30); //The maximum number of seconds to allow cURL functions to execute.  

  $contents = curl_exec($curl);

  if($verbose) {
    if(curl_exec($curl) === false)
    {
      if($verbose) echo "\nCurl error: ".curl_error($curl);
    }
    else
    {
      $info = curl_getinfo($curl);
      if($verbose) echo "\nCURL time:\t".$info['total_time'];
    }
  }

  curl_close($curl);
  return $contents;
}

?>
