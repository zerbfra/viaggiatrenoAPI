<?php

//include "classes/class.stazione.php";
include "classes/class.treno.php";


recuperaStazioni();

function recuperaStazioni() {

  $db = new SQLite3('seguitreno.db');

  $results = $db->query('SELECT id FROM stazioni');

  while(true) {
    
    $dt = new DateTime();
    $today = $dt->format('d');
    $time = $dt->format('H');

    $string  = "2014-11-$today $time:00";
    $date =  new DateTime($string);

    $timestamp = $date->format('U');

    while ($row = $results->fetchArray()) {
      //var_dump($row);
      $stazione = new Stazione();
      $idStazione = $row['id'];

      $treniP = $stazione->trovaTreniInPartenza($idStazione,$timestamp);
      for($i=0;$i<count($treniP);$i++) {
        $numero = $treniP[$i]->numero;
        $origine = $treniP[$i]->idOrigine;
        if($db->query("INSERT INTO listatreni (stazione,numero) VALUES ('$origine','$numero')")) echo "ok\n";

        sleep(3);

      }
      //array_push($treni,$treniInPartenza);
      //var_dump($treniP);

      //echo json_encode($treni);

    }

  }



}





?>
