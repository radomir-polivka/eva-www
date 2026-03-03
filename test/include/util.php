<?php

function append($text, $separator, $append)
{
  if ($text != '' && $append !='')
    return $text . $separator . $append;
  else 
    return $text . $append;
}

?>
