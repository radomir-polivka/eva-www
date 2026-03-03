<?php

$base_path = '..';
require(__DIR__ . '/../include/page.php');

$id = 'concerts';
$lang = 'cz';
$title = 'Plánované koncerty';

require('../body/concerts.php');

display();

?>
