<?php

require_once('util.php');


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
/* page id                   page file name            menu img file name */
  'zivotopis'      => array('zivotopis'             , 'zivotopis'             ),
  'concerts'       => array('koncerty'              , 'koncerty'              ),
  'cd-mp3'         => array('cd-mp3'                , 'cd-mp3'                ),
  'pozitiv'        => array('varhanni-pozitiv'      , 'pozitiv'               ),
  'foto'           => array('foto'                  , 'foto'                  ),
  'pro-poradatele' => array('pro-poradatele'        , 'pro-poradatele'        ),
  'kontakt'        => array('kontakt'               , 'kontakt'               ),
);

$menu_en = array(
  'zivotopis'      => array('curriculum-vitae'      , 'curriculum-vitae'      ),
  'concerts'       => array('concerts'              , 'concerts'              ),
  'cd-mp3'         => array('cd-mp3'                , 'cd-mp3'                ),
  'pozitiv'        => array('positive-organ'        , 'positive-organ'        ),
  'foto'           => array('photo-gallery'         , 'photo-gallery'         ),
  'pro-poradatele' => array('for-concert-organizers', 'for-concert-organizers'),
  'kontakt'        => array('contact'               , 'contact'               ),
);

$menu = array(
  'cz' => $menu_cz,
  'en' => $menu_en,
);


function format_menu($page_id, $lang) {

  global $menu;

  $hmtl = '';
  foreach ($menu[$lang] as $id => $m) {
    if ($page_id == $id) {
      $anchor_attr = 'class="inactive"';
      $dir = 'inactive';
    } else {
      $anchor_attr = 'href="/' . $lang . '/' . $m[0] . '.php"';
      $dir = 'active';
    }

    $icon_fname = $dir . '/' . $m[1] . '.png';

    $hmtl .='
<a ' . $anchor_attr . ' target="_self"><img src="/img/_menu/' . $icon_fname . '" /></a>';
  }
  return $hmtl;
}


function display() {

  global $menu, $icon;

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
    'cz' => '<img src="/img/_menu/inactive/cesky.png" /><img src="/img/_menu/inactive/bar.png" /><a href="/en/index.php"><img src="/img/_menu/active/english.png" /></a>',
    'en' => '<a href="/index.php"><img src="/img/_menu/active/cesky.png" /></a><img src="/img/_menu/inactive/bar.png" /><img src="/img/_menu/inactive/english.png" />',
  );

  $home_link = array (
    'cz' => '/index.php',
    'en' => '/en/index.php',
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
    <title>' . $title . '</title>
    <link rel="StyleSheet" href="/style.css" type="text/css">
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
