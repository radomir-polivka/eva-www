<?php

require_once(__DIR__ . '/util.php');


$icon = array(
  'index'            => 'atelier'             ,
  'zivotopis'        => 'salvator-cervena-R'  ,
  'concerts'         => 'salvator-zelena'     ,
  'concerts-archive' => 'salvator-zelena'     ,
  'cd-mp3'           => 'les-fresques'        ,
  'pozitiv'          => 'salvator-ruce'       ,
  'foto'             => 'salvator-cerna'      ,
  'pro-poradatele'   => 'kristiansand-varhany',
  'kontakt'          => 'kristiansand'        ,
);

$menu_cz = array(
/* page id                   page file name            menu img file name     text label */
  'zivotopis'      => array('zivotopis'             , 'zivotopis'             , 'Životopis'             ),
  'concerts'       => array('koncerty'              , 'koncerty'              , 'Koncerty'              ),
  'cd-mp3'         => array('cd-mp3'                , 'cd-mp3'                , 'CD / MP3'              ),
  'pozitiv'        => array('varhanni-pozitiv'      , 'pozitiv'               , 'Varhanní pozitiv'      ),
  'foto'           => array('foto'                  , 'foto'                  , 'Foto'                  ),
  'pro-poradatele' => array('pro-poradatele'        , 'pro-poradatele'        , 'Pro pořadatele'        ),
  'kontakt'        => array('kontakt'               , 'kontakt'               , 'Kontakt'               ),
);

$menu_en = array(
  'zivotopis'      => array('curriculum-vitae'      , 'curriculum-vitae'      , 'Curriculum vitae'      ),
  'concerts'       => array('concerts'              , 'concerts'              , 'Concerts'              ),
  'cd-mp3'         => array('cd-mp3'                , 'cd-mp3'                , 'CD / MP3'              ),
  'pozitiv'        => array('positive-organ'        , 'positive-organ'        , 'Positive organ'        ),
  'foto'           => array('photo-gallery'         , 'photo-gallery'         , 'Photo gallery'         ),
  'pro-poradatele' => array('for-concert-organizers', 'for-concert-organizers', 'For concert organizers'),
  'kontakt'        => array('contact'               , 'contact'               , 'Contact'               ),
);

$menu = array(
  'cz' => $menu_cz,
  'en' => $menu_en,
);


function format_menu($page_id, $lang) {

  global $menu, $base_path;

  $hmtl = '';
  $text_items = '';
  foreach ($menu[$lang] as $id => $m) {
    if ($page_id == $id) {
      $anchor_attr = 'class="inactive"';
      $dir = 'inactive';
      $text_anchor_attr = 'class="menu-text-inactive"';
    } else {
      $anchor_attr = 'href="' . $base_path . '/' . $lang . '/' . $m[0] . '.php"';
      $dir = 'active';
      $text_anchor_attr = 'href="' . $base_path . '/' . $lang . '/' . $m[0] . '.php"';
    }

    $icon_fname = $dir . '/' . $m[1] . '.png';
    $label = $m[2];

    $hmtl .='
<a ' . $anchor_attr . ' target="_self"><img src="/img/_menu/' . $icon_fname . '" /></a>';

    $text_items .= '
<a ' . $text_anchor_attr . ' target="_self">' . $label . '</a>';
  }

  return $hmtl . '
<div id="menu-toggle" onclick="var m=document.getElementById(\'menu-items\');m.style.display=m.style.display===\'block\'?\'none\':\'block\';">Menu &#9660;</div>
<div id="menu-items">' . $text_items . '
</div>';
}

function mobile_menu($page_id, $lang) {

  global $menu, $base_path;

  $text_items = '';
  foreach ($menu[$lang] as $id => $m) {
    if ($page_id == $id) {
      $text_anchor_attr = 'class="menu-text-inactive"';
    } else {
      $text_anchor_attr = 'href="' . $base_path . '/' . $lang . '/' . $m[0] . '.php"';
    }

    $label = $m[2];

    $text_items .= '
<a ' . $text_anchor_attr . ' target="_self">' . $label . '</a>';
  }

  return $text_items; 
}

function display() {

  global $menu, $icon, $base_path;

  global $id;
  global $title, $descr, $keywords, $lang;
  global $cnt;

  $title_base = array(
    'cz' => 'Eva Bublová',
    'en' => 'Eva Bublova',
  );

  $keyw_base = array (
    'cz' => 'Eva Bublová, varhanice, cembalistka, varhany, cembalo',
    'en' => 'Eva Bublova, organist, harpsichordist, organ, harpsichord',
  );

  $lang_switch = array (
    'cz' => '<img src="/img/_menu/inactive/cesky.png" /><img src="/img/_menu/inactive/bar.png" /><a href="' . $base_path . '/en/index.php"><img src="/img/_menu/active/english.png" /></a>',
    'en' => '<a href="' . $base_path . '/index.php"><img src="/img/_menu/active/cesky.png" /></a><img src="/img/_menu/inactive/bar.png" /><img src="/img/_menu/inactive/english.png" />',
  );

  $lang_switch_text = array (
    'cz' => '<span id="lang-text"><a href="' . $base_path . '/en/index.php">English</a></span>',
    'en' => '<span id="lang-text"><a href="' . $base_path . '/index.php">Česky</a></span>',
  );

  $home_link = array (
    'cz' => $base_path . '/index.php',
    'en' => $base_path . '/en/index.php',
  );

  if (!isset($descr))
    $descr = $title;
  $title = append($title_base[$lang], ' / ', $title);
  $descr = append($title_base[$lang], ' - ', $descr);
  $keyw = append($keyw_base[$lang], ', ', $keywords);

  $base = '';
  if ($id == 'concerts' OR $id == 'concerts-archive')
    $base = '<base target="_blank">';

  echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cs" lang="cs">

  <head>
    <meta http-equiv="content-type" content="text/html;  charset=utf-8" />
    <meta http-equiv="content-language" content="cs" />
    <meta http-equiv="description" content="' . $descr . '" />
    <meta http-equiv="keywords" content="' . $keyw . '" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>' . $title . '</title>
    <link rel="StyleSheet" href="' . $base_path . '/style.css" type="text/css">
    ' . $base . '
  </head>

  <body>
    <div id="universe">

      <div id="canvas">

        <div id="name">
          <a href="' . $home_link[$lang] . '">
            <h1>Eva Bublová - varhany, cembalo</h1>
            <img src="/img/_icon/name-brown.png" height="337" width="24" 
              alt="Eva Bublová - varhany, cembalo" />
          </a>
        </div>

        <div id="sheet-width">
        <div id="sheet-border">
        <div id="sheet-bckg">
        <div id="sheet">

          <div id="menu-lang">
            ' . $lang_switch[$lang] . '
          </div> <!-- menu-lang -->

          <div id="icon">
            <a href="' . $home_link[$lang] . '"><img src="/img/_corner/' . 
              $icon[$id] . '.jpg" height=100 width=100/></a>
          </div>

          <div id="menu-mobile">
            <span id="menu-toggle" onclick="var m=document.getElementById(\'menu-items\');m.style.display=m.style.display===\'block\'?\'none\':\'block\';">Menu &#9660;</span>
            ' . $lang_switch_text[$lang] . '
            <div id="menu-items">
            ' . mobile_menu($id, $lang) . '
            </div>
          </div>

          <div id="menu"> 
' . format_menu($id, $lang) . '
          </div> <!-- menu -->

          <div id="text">
';

  echo ($cnt);

  echo '
          </div> <!-- text -->
          <div class="clear">&nbsp;</div>
        </div> <!-- sheet -->
        </div> <!-- sheet-border -->
        </div> <!-- sheet-border -->
        </div> <!-- sheet-width -->
        <div class="clear">
          &nbsp;
        </div>
      </div>
    </div>
    
<!-- Pocitadlo.cz  pocitadlo:80536  uzivatel:53774 -->
<script language="JavaScript" type="text/javascript">
<!--
Tmp=Math.floor(1000000 * Math.random());
document.write("<scr" + "ipt src=\"http://cnt2.pocitadlo.cz/counter.php?poc=80536&amp;ref="+escape(top.document.referrer)+"&amp;depth="+screen.colorDepth+"&amp;width="+screen.width+"&amp;height="+screen.height+"&amp;tmp="+Tmp+"\" language=\"JavaScript\" type=\"text/javascript\"></scr" + "ipt>");
// -->
</script>
<noscript>
<img src="http://cnt2.pocitadlo.cz/counter.php?poc=80536&amp;ns=1" width="1" height="1" alt="" border="0" />
</noscript>
<!-- Pocitadlo.cz konec -->
<a href="http://www.toplist.cz/stat/1001475"><script language="JavaScript" type="text/javascript">

<!-- toplist -->
<!--
document.write (\'<img src="http://toplist.cz/dot.asp?id=1001475&http=\'+escape(document.referrer)+\'&wi=\'+escape(window.screen.width)+\'&he=\'+escape(window.screen.height)+\'&cd=\'+escape(window.screen.colorDepth)+\'&t=\'+escape(document.title)+\'" width="1" height="1" border=0 alt="TOPlist" />\');
//--></script><noscript><img src="http://toplist.cz/dot.asp?id=1001475" border="0"
alt="TOPlist" width="1" height="1" /></noscript></a>

  </body>
</html>
';

}
