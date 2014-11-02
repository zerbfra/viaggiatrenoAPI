<?php

//dato un timestamp di trenitalia ritorna uno valido
function toValidTimestamp($date) {
  if($date) return $date/1000;
  else return null;
}


// data una data nel formato 2014-10-31T22:10:00 la traduce in un timestamp
function completeToTimestamp($dateString) {

  $datetime = DateTime::createFromFormat('Y-m-d\TH:i:s', $dateString);
  return $datetime->format('U');

}

// data una data nel formato 2014-10-31T22:10:00 e un orario di arrivo (nel formato 22:10:00) trova l'orario di arrivo completo di data
function completeTime($previous,$time) {

  $prevDate = DateTime::createFromFormat('Y-m-d\TH:i:s', $previous);

  $hour =  DateTime::createFromFormat('H:i:s', $time);
  //ora arrivo minore
  //ora uguale minuto minore

  if($hour->format('H') < $prevDate->format('H')) {

    $tomorrow = clone $prevDate;
    $tomorrow->modify('+1 day');

    $next_date = new DateTime($tomorrow->format('Y-m-d') .' '.$time);
  }
  else $next_date = new DateTime($prevDate->format('Y-m-d') .' '.$time);

  return $next_date->format('U');

}

?>
