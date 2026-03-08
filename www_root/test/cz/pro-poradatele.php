<?php

$base_path = '..';
require(__DIR__ . '/../include/page.php');

$id = 'pro-poradatele';
$lang = 'cz';
$title = 'Pro pořadatele';
$descr = 'informace pro pořadatele';
$keywords = 'repertoár';

$cnt = '
<p>
  Na této stránce by měli pořadatelé najít vše potřebné pro organizaci
  a&nbsp;propagaci koncertu.
</p>

<p>
  Nejprve bych vás ráda upozornila na možnost uspořádat varhanní koncert
  i&nbsp;v&nbsp;prostoru, kde nejsou varhany instalovány. Pro tyto účely mám 
  k&nbsp;dispozici přenosný 
  <a href="varhanni-pozitiv.php">varhanní pozitiv</a>.
</p>

<p>
  Program koncertu mohu sestavit z veškeré hudby od renesance až po současnou
  tvorbu. Skladby, které nemám v repertoáru, pro vás mohu nastudovat na přání.
</p>

<p>
  Varhanní koncert mohu připravit také ve spolupráci 
  se zpěvákem či instrumentalistou. Ráda a často koncertuji např. se 
  zpěvačkami Hanou Blažíkovou, Karolínou Bubleovou Berkovou, 
  houslistkami Adélou Štajnochrovou a Evou Nachmilnerovou, 
  hobojistou Janem Součkem, 
  trumpetisty Markem Zvolánkem a Ladislavem Kozderkou.
</p>

<p>
	Pokud máte zájem o můj koncert, kontaktujte mne telefonicky 
	(605&nbsp;063&nbsp;912) nebo e&ndash;mailem 
	(<a href="mailto:evabublova@hotmail.com">evabublova@hotmail.com</a>).
</p>

<p>
  Životopis Evy Bublové ke stažení ve formátu 
  <a href="/text/bublova-zivotopis.pdf">PDF</a>.
</p>

<p>
  Fotografie ve vysokém rozlišení pro tisk (&copy;&nbsp;Martin&nbsp;Stanovský):
</p>

<!-- changed from tif to jpeg for 10MB freehosting -->
<div class="clear">
	<a href="/img/_press/eva-bublova-b1.jpg"><img class="left" src="/img/2008/martin-stanovsky/eva-bublova-b1-150.jpg" height="150" width="100"/></a>
	<a href="/img/_press/eva-bublova-b2.jpg"><img class="left" src="/img/2008/martin-stanovsky/eva-bublova-b2-150.jpg" height="150" width="100"/></a>
	<a href="/img/_press/eva-bublova-r1.jpg"><img class="left" src="/img/2008/martin-stanovsky/eva-bublova-r1-150.jpg" height="150" width="100"/></a>
	<a href="/img/_press/eva-bublova-r2.jpg"><img class="left" src="/img/2008/martin-stanovsky/eva-bublova-r2-150.jpg" height="150" width="100"/></a>
</div>

<div class="clear">
	<a href="/img/_press/eva-bublova-g0.jpg"><img class="left" src="/img/2008/martin-stanovsky/eva-bublova-g0-150.jpg" height="150" width="100"/></a>
	<a href="/img/_press/eva-bublova-g1.jpg"><img class="left" src="/img/2008/martin-stanovsky/eva-bublova-g1-150.jpg" height="150" width="100"/></a>
	<a href="/img/_press/eva-bublova-g2.jpg"><img class="left" src="/img/2008/martin-stanovsky/eva-bublova-g2-150.jpg" height="150" width="100"/></a>
</div>

<!--
<div class="clear">
	<a href="/img/_press/eva-bublova-b1.tif"><img class="left" src="/img/2008/martin-stanovsky/eva-bublova-b1-150.jpg" height="150" width="100"/></a>
	<a href="/img/_press/eva-bublova-b2.tif"><img class="left" src="/img/2008/martin-stanovsky/eva-bublova-b2-150.jpg" height="150" width="100"/></a>
	<a href="/img/_press/eva-bublova-r1.tif"><img class="left" src="/img/2008/martin-stanovsky/eva-bublova-r1-150.jpg" height="150" width="100"/></a>
	<a href="/img/_press/eva-bublova-r2.tif"><img class="left" src="/img/2008/martin-stanovsky/eva-bublova-r2-150.jpg" height="150" width="100"/></a>
</div>

<div class="clear">
	<a href="/img/_press/eva-bublova-g0.tif"><img class="left" src="/img/2008/martin-stanovsky/eva-bublova-g0-150.jpg" height="150" width="100"/></a>
	<a href="/img/_press/eva-bublova-g1.tif"><img class="left" src="/img/2008/martin-stanovsky/eva-bublova-g1-150.jpg" height="150" width="100"/></a>
	<a href="/img/_press/eva-bublova-g2.tif"><img class="left" src="/img/2008/martin-stanovsky/eva-bublova-g2-150.jpg" height="150" width="100"/></a>
</div>
-->
';

/* 
//just a test of server PHP restrictions 
$fname="test.txt";
$fp=fopen($fname, "a");
if ($fp === false)
{
  $cnt .= "fopen false <br/>";
}
else
{
  $written=fwrite($fp, "test");
  $cnt .= "written $written bytes <br/>";
}
*/

display()

?>
