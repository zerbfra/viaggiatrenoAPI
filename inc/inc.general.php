<?php

function setTimezone($date) {
  $usersTimezone = new DateTimeZone('Europe/Rome');
  $date->setTimeZone($usersTimezone);
}

//dato un timestamp di trenitalia ritorna uno valido
function toValidTimestamp($date) {
  if($date) return $date/1000;
  else return null;
}


// data una data nel formato 2014-10-31T22:10:00 la traduce in un timestamp
function completeToTimestamp($dateString) {
  $datetime = DateTime::createFromFormat('Y-m-d\TH:i:s', $dateString);
  setTimezone($datetime);
  return $datetime->format('U');

}

//dato un timestamp lo traduce nel formato trenitalia Thu Oct 30 2014 14:20:00 GMT+0100 (CET)
function toTrenitaliaDateTextual($timestamp) {
  $datetime = DateTime::createFromFormat('U', $timestamp);
  setTimezone($datetime);
  $dateString = $datetime->format('D M d Y H:i:s \G\M\T\+\0\1\0\0 \(\C\E\T\)');
  return str_replace(' ', '%20', $dateString);
}

//dato un timestamp lo traduce nel formato trenitalia 2014-10-31T22:10:00
function toTrenitaliaDate($timestamp) {
  $datetime = DateTime::createFromFormat('U', $timestamp);
  setTimezone($datetime);
  return $datetime->format('Y-m-d\TH:i:s');
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

  setTimezone($nextDate);
  return $next_date->format('U');

}

?>
