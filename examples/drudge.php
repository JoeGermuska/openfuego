<?php

/**
  * The Getter object has one method:
  *
  * getItems($quantity, $hours, $scoring, $metadata)
  *
  * $quantity (int): Number of links desired. Default 20.
  * $hours (int): How far back to look for links. Default 24.
  * $scoring (bool): TRUE to employ  "freshness vs. quality" algorithm
  *   or FALSE to simply return most frequently tweeted links. Default TRUE.
  * $metadata (bool): TRUE to hydrate URLs with Embed.ly metadata.
  *   An API key must be set in config.php. Default FALSE.
 */
 
require(__DIR__ . '../../init.php');
use OpenFuego\app\Getter as Getter;

$fuego = new Getter();
$quantity = 90;
$items = $fuego->getItems($quantity, 24, FALSE, TRUE); // quantity, hours, scoring, metadata
print '<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push([\'_setAccount\', \'UA-8481089-1\']);
  _gaq.push([\'_trackPageview\']);

  (function() {
    var ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true;
    ga.src = (\'https:\' == document.location.protocol ? \'https://ssl\' : \'http://www\') + \'.google-analytics.com/ga.js\';
    var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>';

print '<br /><a href="http://morisy.com/hard/fuego/openfuego/examples/drudge.php">NEW: WHAT BOSTON IS TALKING ABOUT — NOW!</a>';
print '<br /><a href="http://morisy.com/hard/fuego/openfuego/examples/drudge.php">REFRESH ... UPDATES EVERY MINUTE</a>';
print '<br /><br /><br /><br /><br /><br />';
print '<center>
<img src="siren.gif">
<br /><font FACE="ARIAL,VERDANA,HELVETICA"><font size="+7"><h3><a href="http://www.niemanlab.org/2013/07/introducing-openfuego-your-very-own-heat-seeking-twitter-bot/">FUEGO OPEN SOURCED</a></h3><br /><a href="http://morisy.com/hard/fuego/openfuego/examples/drudge.php"><img src="kendall.jpg" border="0" WIDTH="610" HEIGHT="85"></a>
</center><br>';
print '<pre>';
# echo implode('<br>', $items['url']);
print '<center><table CELLPADDING="3" WIDTH="100%"><tr>
<td ALIGN="LEFT" VALIGN="TOP" WIDTH="30%"><tt><b>';

$counter = 0;

foreach ($items as $post) {
        if (rand(0,10) == 5){
			echo '<a href="',$post['url'],'">','<img width="300" src="',$post['metadata']['thumbnail_url'],'"> </a><br /></br>';
			}
		echo '<a href="',$post['url'],'">',$post['metadata']['title'],'</a><br /></br>';
		if ($counter == 30){
			print '</b></tt>
			</td>
			<td align="center" valign="top" width="3"><div style="width:1px;background-color:#C0C0C0;margin-left:1px;margin-right:1px;height:2500px;"></div></td>';
			print '<td ALIGN="LEFT" VALIGN="TOP" WIDTH="30%"><tt><b>';
			}
		if ($counter == 60){
			print '</b></tt>
			</td>
			<td align="center" valign="top" width="3"><div style="width:1px;background-color:#C0C0C0;margin-left:1px;margin-right:1px;height:2500px;"></div></td>';

			print '<td ALIGN="LEFT" VALIGN="TOP" WIDTH="30%"><tt><b>';
			}
		
		$counter = $counter + 1;
    }



?>
