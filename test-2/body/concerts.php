<?php

require('../include/concert.php');

$archive_ref = array(
  'cz' => 'koncerty-archiv',
  'en' => 'concerts-archive',
);

$archive_label = array(
  'cz' => 'archiv',
  'en' => 'achive',
);


$cnt = format_concert_list('normal');

$cnt .= '
<p>
  <a href="' . $archive_ref[$lang] . '.php" target="_self">' . $archive_label[$lang] . '</a>
</p>
';

?>
