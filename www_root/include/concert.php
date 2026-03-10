<?php

require_once('util.php');

function format_concert($date, $concert) {

  $html = '';

  $date  = strtotime($date);
  $time  = $concert[0];
  $place = $concert[1];
  $descr = $concert[2];

  $formated_date = date('d. m. Y', $date);
  $first =  append($formated_date, ', ', $time);

  $html .= '
<p>
  ' . $first . '<br />
  <em>' . $place . '</em><br />
  ' . $descr . '
</p>';

  return $html;
}


function format_concert_list($mode = 'normal') {

  //old db format:
//require('../userconfig/concerts-cfg.php'); 
  
  //new db format:
  $concert_list = read_concerts();

  $html = '';

  if ($mode == 'normal') {
    foreach ($concert_list as $date => $c) {
      if (date('Ymd') <= $date) {
        $html .= format_concert($date, $c);
      } else {
        continue;
      }
    }
  } elseif ($mode = 'archive') {
    krsort ($concert_list);
    foreach ($concert_list as $date => $c) {
      if (date('Ymd') <= $date) {
        continue;
      } else {
        $html .= format_concert($date, $c);
      }
    }
  }

  /*
  if ($html == '') $html = '<p>žádné koncerty</p>';
   */

  return $html;
}


function read_concerts() {

  $handle = fopen($_SERVER['DOCUMENT_ROOT'] . "/userconfig/concert.csv", "r");
  $concert_list = array();

  while ($line = fgetcsv($handle, '1000', '|')) {
    $date = trim($line[0]);
    $concert[0]  = trim($line[1]); //time
    $concert[1] = trim($line[2]);  //place
    $concert[2] = trim($line[3]);  //descr
    $concert_list[$date] = $concert;
  }
  
  fclose($handle);
  return $concert_list;
}

