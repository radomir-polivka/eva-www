<?php

require('../include/concert.php');

$upcoming_ref = array(
  'cz' => 'koncerty',
  'en' => 'concerts',
);

$note = array(
  'cz' => array(
    'Toto je archivní seznam koncertů od roku 2009.',
    'Aktuální koncerty', 
    'najdete na jiné stránce.',
  ),
  'en' => array(
    'This is just an archive list. See', 
    'upcoming concerts', 
    'on another page.'
  ),
);


$cnt = '';

$cnt .= '
<p>
  '. $note[$lang][0] . 
  ' <a href="' . $upcoming_ref[$lang] . '.php">' . $note[$lang][1] . '</a> ' .
  $note[$lang][2] . '
</p>
';

$cnt .= format_concert_list('archive');

$cnt .= '
<p>
  '. $note[$lang][0] . 
  ' <a href="' . $upcoming_ref[$lang] . '.php">' . $note[$lang][1] . '</a> ' .
  $note[$lang][2] . '
</p>
';

?>
