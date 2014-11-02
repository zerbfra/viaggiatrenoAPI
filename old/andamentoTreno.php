<?php

include "classes/class.fermata.php";
//include "classes/class.stazione.php";

$stazioneP = "S01700";
$numero = "2657";

$response = file_get_contents("http://www.viaggiatreno.it/viaggiatrenonew/resteasy/viaggiatreno/andamentoTreno/$stazioneP/$numero",false);


$json = json_decode($response);


$listaFermate = $json->fermate;


//var_dump($listaFermate);


$fermate = array();

foreach ($listaFermate as $key => $value) {

	$record = new Fermata;



	$record->id = $listaFermate[$key]->id;
	$record->stazione = $listaFermate[$key]->stazione;
	$record->progressivo = $listaFermate[$key]->progressivo;

	$record->binarioProgrammatoArrivoDescrizione = str_replace(" ","",$listaFermate[$key]->binarioProgrammatoArrivoDescrizione);
	$record->binarioEffettivoArrivoDescrizione = str_replace(" ","",$listaFermate[$key]->binarioEffettivoArrivoDescrizione);

	$record->binarioProgrammatoPartenzaDescrizione = $listaFermate[$key]->binarioProgrammatoPartenzaDescrizione;
	$record->binarioEffettivoPartenzaDescrizione = $listaFermate[$key]->binarioEffettivoPartenzaDescrizione;
	
	$record->programmata = $listaFermate[$key]->programmata;
	$record->effettiva = $listaFermate[$key]->effettiva;
	
	$record->ritardo = $listaFermate[$key]->ritardo;
	
	$record->actualFermataType = $listaFermate[$key]->actualFermataType;




	array_push($fermate,$record);
	

}

echo json_encode($fermate);


?>


