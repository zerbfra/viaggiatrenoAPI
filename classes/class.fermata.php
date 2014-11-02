<?php

/**** Zerbinati Francesco ****/
/* Classe Stazione	         */  
/* Version 0.13              */
/* Updated: 30/10/14		 */ 
/*****************************/

class Fermata {
	
  	public $stazione;
  	public $progressivo;

  	// binari
  	public $binarioProgrammatoArrivoDescrizione;
  	public $binarioEffettivoArrivoDescrizione;

    public $binarioProgrammatoPartenzaDescrizione;
    public $binarioEffettivoPartenzaDescrizione;

  	//orari

	//arrivo 
	public $programmata;
	public $effettiva;

  	// partenza??


  	//ritardo
  	public $ritardo;

  	// 1 se è passato di li
  	public $actualFermataType;

  		
}       
?>