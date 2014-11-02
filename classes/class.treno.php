<?php

include "classes/class.stazione.php";
include "classes/class.fermata.php";
include "inc/inc.general.php";

/**** Zerbinati Francesco ****/
/* Classe Treno	         	 */
/* Version 0.13              */
/* Updated: 30/10/14		 */
/*****************************/

class Treno {

	public $numero;
	public $stazioneP; // oggetto Stazione

	public $categoria;
	public $origine;
	public $destinazione;

	public $idOrigine;
	public $idDestinazione;

	public $circolante;

	public $orarioPartenza;
	public $orarioArrivo;

	public $stazioneUltimoRilevamento;
	public $oraUltimoRilevamento;

	public $compDurata;

	public $ritardo;

	public $sopresso;

	public $fermate;


	function display() {

		echo "<pre>";
		echo "<b>Numero: </b> ".$this->numero;
		echo "<br>";
		echo "<b>Partenza: </b>".$this->stazioneP;
		echo "</pre>";
		echo "<hr>";


	}

	// Trova la stazione di partenza del treno
	function trovaStazioniPossibili() {

		$link =  "http://www.viaggiatreno.it/viaggiatrenonew/resteasy/viaggiatreno/cercaNumeroTrenoTrenoAutocomplete/$this->numero";

		$response = file_get_contents($link);

		$lines = explode("\n",$response);


		$stazioniPossibili = array();

		foreach($lines as $line) {

			$record = explode("|",$line);


			$stazioneP = explode(" - ",$record[0]);
			$codiceP = explode("-",$record[1]);


			$stazione = new Stazione;

			$stazione->nome = $stazioneP[1];
			$stazione->id = str_replace("\n","",$codiceP[1]);

			$stazione->trovaStazione($stazione->id,$stazione->nome);

			if(!empty($stazione->id))
			array_push($stazioniPossibili,$stazione);
			//$stazione->display();

		}

		return $stazioniPossibili;
		//print_r($stazioniPossibili);
		//if(count($stazioniPossibili) > 1) $this->stazioneP = $stazioniPossibili;
		//else $this->stazioneP = $stazioniPossibili[0];

	}

	function dettagliTreno() {

		$idStazioneP = $this->stazioneP->id;

		$link = "http://www.viaggiatreno.it/viaggiatrenonew/resteasy/viaggiatreno/andamentoTreno/$idStazioneP/$this->numero";

		$response = @file_get_contents($link);


		if($response) {

			$json = json_decode($response);

			$this->categoria = $json->categoria;
			$this->origine = $json->origine;
			$this->destinazione = $json->destinazione;
			$this->idOrigine = $json->idOrigine;
			$this->idDestinazione = $json->idDestinazione;
			$this->circolante = $json->circolante;

			$this->orarioPartenza=  toValidTimestamp($json->orarioPartenza);
			$this->orarioArrivo =  toValidTimestamp($json->orarioArrivo);

			$this->stazioneUltimoRilevamento = $json->stazioneUltimoRilevamento;
			$this->oraUltimoRilevamento = toValidTimestamp($json->oraUltimoRilevamento);

			$this->compDurata = $json->compDurata;
			$this->ritardo = $json->ritardo;

			$this->nonPartito = $json->nonPartito;

			if(empty($json->fermate)) $this->sopresso = true;
			else $this->sopresso = false;
		}
	}


	function trovaFermate() {

		$idStazioneP = $this->stazioneP->id;

		$response = @file_get_contents("http://www.viaggiatreno.it/viaggiatrenonew/resteasy/viaggiatreno/andamentoTreno/$idStazioneP/$this->numero",false);

		if($response) {

			$json = json_decode($response);


			$listaFermate = $json->fermate;


			$fermate = array();

			foreach ($listaFermate as $key => $value) {

				$record = new Fermata;

				$stazione = new Stazione;
				$stazione->trovaStazione($listaFermate[$key]->id,$listaFermate[$key]->stazione);
				$record->stazione = $stazione;
				//$record->id = $listaFermate[$key]->id;
				//$record->stazione = $listaFermate[$key]->stazione;
				$record->progressivo = $listaFermate[$key]->progressivo;

				$record->binarioProgrammatoArrivoDescrizione = str_replace(" ","",$listaFermate[$key]->binarioProgrammatoArrivoDescrizione);
				$record->binarioEffettivoArrivoDescrizione = str_replace(" ","",$listaFermate[$key]->binarioEffettivoArrivoDescrizione);

				$record->binarioProgrammatoPartenzaDescrizione = $listaFermate[$key]->binarioProgrammatoPartenzaDescrizione;
				$record->binarioEffettivoPartenzaDescrizione = $listaFermate[$key]->binarioEffettivoPartenzaDescrizione;

				$record->programmata = toValidTimestamp($listaFermate[$key]->programmata);
				$record->effettiva =  toValidTimestamp($listaFermate[$key]->effettiva);

				$record->ritardo = $listaFermate[$key]->ritardo;

				$record->actualFermataType = $listaFermate[$key]->actualFermataType;




				array_push($fermate,$record);


			}

			$this->fermate = $fermate;
		}


	}

}
?>
