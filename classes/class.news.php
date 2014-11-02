<?php

/**** Zerbinati Francesco ****/
/* Classe News  	    	 */  
/* Version 0.13              */
/* Updated: 30/10/14		 */ 
/*****************************/

class News {
	
	public $titolo;
	public $data; // oggetto Stazione
	public $primoPiano;
	public $testo;

	function display() {

		echo "<pre>";
		echo "<b>Titolo: </b> ".$this->titolo;
		echo "<br>";
		echo "<b>Data: </b>".$this->data;
		echo "<b>Primopiano: </b> ".$this->primoPiano;
		echo "<br>";
		echo "<b>Testo: </b> ".$this->testo;
		echo "</pre>";
		echo "<hr>";


	}

} 
      
?>