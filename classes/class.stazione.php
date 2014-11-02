<?php

/**** Zerbinati Francesco ****/
/* Classe Stazione	         */
/* Version 0.13              */
/* Updated: 30/10/14		 */
/*****************************/

class Stazione {

  public $id;
  public $nome;
  public $regione;

  public $lat;
  public $lon;


  function display() {

    echo "<pre>";
    echo "<b>ID: </b> ".$this->id;
    echo "<br>";
    echo "<b>Nome: </b>".$this->nome;
    echo "<br>";
    echo "<b>Regione: </b> ".$this->regione;
    echo "</pre>";
    echo "<hr>";


  }


  function trovaStazione($id,$nome) {
    $this->id = $id;
    $this->nome = $nome;

    $responseRegione = @file_get_contents("http://www.viaggiatreno.it/viaggiatrenonew/resteasy/viaggiatreno/regione/$this->id",false);
    
    if($responseRegione != FALSE) {

      $lines = explode("\n", $responseRegione);

      $this->regione = $lines[0];


      $response = file_get_contents("http://www.viaggiatreno.it/viaggiatrenonew/resteasy/viaggiatreno/elencoStazioni/$this->regione");
      $json = json_decode($response);

      if(!empty($json)) {
        $dettaglioStazione = array_filter($json, function($obj)
        {
          return $obj->codiceStazione == $this->id;
        });

        $dettaglioStazione = array_values($dettaglioStazione);


        $this->lat = $dettaglioStazione[0]->lat;
        $this->lon = $dettaglioStazione[0]->lon;
      }

    }

  }



}
?>
