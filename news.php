<?php

include "classes/class.news.php";


$response = file_get_contents("http://www.viaggiatreno.it/viaggiatrenonew/resteasy/viaggiatreno/news/0/it",false);

$json = json_decode($response);

$news = array();

foreach ($json as $key => $value) {

	$record = new News;
	$record->titolo = $json[$key]->titolo;
	$record->data = $json[$key]->data;
	$record->primoPiano = $json[$key]->primoPiano;
	$record->testo = $json[$key]->testo;

	array_push($news,$record);

}

header('Content-Type: application/json');
echo json_encode($news);


?>
