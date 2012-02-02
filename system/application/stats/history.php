<?PHP
require_once('config.php');

// Get Month and Year
$time=time();
if (is_numeric($_GET["m"]) AND $_GET["m"] >= 1 AND $_GET["m"] <= 12 ) {$show_month = $_GET["m"];} 
else {$show_month=date("n",$time);}
if (is_numeric($_GET["y"]) AND $_GET["y"] >= 1 AND $_GET["y"] <= 9999 ) {$show_year = $_GET["y"];} 
else {$show_year=date("Y",$time);}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>ChiliStats - History</title>
<link href="chilistats.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="container">

<div id="menu">
 <ul>
  <li><a href="stats.php">OneView</a></li>
  <li><a href="visitors.php">Visitors</a></li>
  <li><a href="history.php">History</a></li> 
 </ul>
</div>
  <div class="middle">
    <h3>History</h3>
      <?PHP
	  // Gesamt Besucher ermitteln
	  $abfrage=mysql_query("select sum(user),sum(view),min(day),avg(user) from ".$db_prefix."Day");
	  $visitors=mysql_result($abfrage,0,0);
	  $visits=mysql_result($abfrage,0,1);
	  $since=mysql_result($abfrage,0,2);
	  $since=str_replace(".", "-", $since);
	  $since=strtotime($since);
	  $since=date("d F Y",$since);
	  $total_avg=round(mysql_result($abfrage,0,3),2);
	  mysql_free_result($abfrage);
	  ?>
	<table width="100%" border="0" cellpadding="5" cellspacing="0">
      <tr valign="top">
	  <td colspan="4"><strong>Total since <?PHP echo $since;?></strong></td>
	  </tr>
	  <tr valign="top">
	  <td width="30%">Visitors</td><td width="20%"><?PHP echo $visitors; ?></td>
	  <td width="30%">Visits</td><td width="20%"><?PHP echo $visits; ?></td>
	  </tr>
	  <tr valign="top">
	  <td width="30%">&Oslash; Day</td><td width="20%"><?PHP echo $total_avg; ?></td>
	  <td width="30%">&nbsp;</td><td width="20%">&nbsp;</td>
	  </tr>
	</table>
	<br />
		  <?PHP
	  // selected Moth
	  $sel_timestamp = mktime(0, 0, 0, $show_month, 1, $show_year);
	  $sel_month = date("Y.m.%",$sel_timestamp);
	  $abfrage=mysql_query("select sum(user), sum(view), avg(user) from ".$db_prefix."Day where day LIKE '$sel_month'");
	  $visitors=mysql_result($abfrage,0,0);
	  $visits=mysql_result($abfrage,0,1);
	  $day_avg=round(mysql_result($abfrage,0,2),2);
	  mysql_free_result($abfrage);	  
	  ?>
	<table width="100%" border="0" cellpadding="5" cellspacing="0">
	  <tr valign="top">
		<td colspan="4"><strong>Selected is <?PHP echo date("F Y",mktime(0, 0, 0, $show_month, 1, $show_year)); ?></strong></td>
	  </tr>
	  <tr valign="top">
	    <td>Visitors</td><td><?PHP echo $visitors; ?></td><td>Visits</td><td><?PHP echo $visits; ?></td>
	  </tr>
	  <tr valign="top">
		<td>&Oslash; Day</td><td><?PHP echo $day_avg; ?></td><td>&nbsp;</td><td>&nbsp;</td>
	  </tr>
    </table>
  </div>
  <div class="middle">
    <h3>
	<?PHP 
	echo "Year ".date("Y",mktime(0, 0, 0, $show_month, 1, $show_year)); 
	
	$back_month=date("n",mktime(0, 0, 0, $show_month, 1, $show_year-1));
	$back_yaer=date("Y",mktime(0, 0, 0, $show_month, 1, $show_year-1));
	$next_month=date("n",mktime(0, 0, 0, $show_month, 1, $show_year+1));
	$next_yaer=date("Y",mktime(0, 0, 0, $show_month, 1, $show_year+1));
	
	echo "<span><a href=\"history.php?m=$back_month&y=$back_yaer\"><</a>&nbsp;<a href=\"history.php?m=$next_month&y=$next_yaer\">></a></span>";
	?>
	</h3>
	<table height="200" width="100%" cellpadding="0" cellspacing="0" align="right">
	<tr valign="bottom" height="180">
	<?PHP
	// Max Month
	$abfrage=mysql_query("select LEFT(day,7) as month, sum(user) as user_month from ".$db_prefix."Day GROUP BY month ORDER BY user_month DESC LIMIT 1");
	$max_month=mysql_result($abfrage,0,1);
	// Monat abfragen
	$bar_nr=0;
	for($month=1; $month<=12; $month++)
		{
		$sel_timestamp = mktime(0, 0, 0, $month, 1, $show_year);
		$sel_month = date("Y.m.%",$sel_timestamp);
		$abfrage=mysql_query("select sum(user) from ".$db_prefix."Day where day LIKE '$sel_month'");
		$User=mysql_result($abfrage,0,0);
		mysql_free_result($abfrage);
		
		$bar[$bar_nr]=$User; // Im Array Speichern
		$bar_title[$bar_nr] = date("M.Y",$sel_timestamp);
		$bar_month[$bar_nr]=$month;
		
		$bar_nr++;
		}
	// Diagramm 		
	for($i=0; $i<$bar_nr; $i++)
		{
		$value=$bar[$i];
		if ($value == "") $value = 0;
		if ($max_month > 0) {$bar_hight=round((170/$max_month)*$value);} else $bar_hight = 0;
		if ($bar_hight == 0) $bar_hight = 1;	

		echo "<td width=\"38\">";
		echo "<a href=\"history.php?m=".$bar_month[$i]."&y=$show_year\">";
		echo "<div class=\"bar\" style=\"height:".$bar_hight."px;\" title=\"".$bar_title[$i]." - $value Visitors\"></div>";
		echo "</a></td>\n";
		}
	?>
    </tr><tr height="20">
	<td colspan="3" width="25%" class="timeline"><?PHP echo date("M.Y",mktime(0, 0, 0, 1, 1, $show_year)); ?></td>
	<td colspan="3" width="25%" class="timeline"><?PHP echo date("M.Y",mktime(0, 0, 0, 4, 1, $show_year)); ?></td>
	<td colspan="3" width="25%" class="timeline"><?PHP echo date("M.Y",mktime(0, 0, 0, 7, 1, $show_year)); ?></td>
	<td colspan="3" width="25%" class="timeline"><?PHP echo date("M.Y",mktime(0, 0, 0, 10, 1, $show_year)); ?></td>
	</tr></table>
  </div>
  <div style="clear:both"></div>
  <div class="full">
    <h3>
	<?PHP 
	echo date("F Y",mktime(0, 0, 0, $show_month, 1, $show_year)); 
	
	$back_month=date("n",mktime(0, 0, 0, $show_month-1, 1, $show_year));
	$back_yaer=date("Y",mktime(0, 0, 0, $show_month-1, 1, $show_year));
	$next_month=date("n",mktime(0, 0, 0, $show_month+1, 1, $show_year));
	$next_yaer=date("Y",mktime(0, 0, 0, $show_month+1, 1, $show_year));
	
	echo "<span><a href=\"history.php?m=$back_month&y=$back_yaer\"><</a>&nbsp;<a href=\"history.php?m=$next_month&y=$next_yaer\">></a></span>";
	?>
	</h3>
	<table height="230" width="100%" cellpadding="0" cellspacing="0" align="right">
	<tr valign="bottom" height="210">
	<?PHP
	// Ausgewählten Monat anzeigen
	$bar_nr=0;
	$month_days=date(t,mktime(0,0,0,$show_month,1,$show_year));
	for($day=1; $day<=$month_days; $day++)
		{
		$sel_timestamp = mktime(0, 0, 0, $show_month, $day, $show_year);
		$sel_tag = date("Y.m.d",$sel_timestamp);
		$abfrage=mysql_query("select sum(user) from ".$db_prefix."Day where day='$sel_tag'");
		$User=mysql_result($abfrage,0,0);
		mysql_free_result($abfrage);
		
		$bar[$bar_nr]=$User; // Im Array Speichern
		$bar_title[$bar_nr] = date("j.M.Y",$sel_timestamp);
		
		if (date("j")-$day == 1) $bar_mark = $bar_nr;
		if ( date("w", $sel_timestamp) == 6 OR date("w", $sel_timestamp)== 0) {$weekend[$bar_nr]=true;}
		else {$weekend[$bar_nr]=false;}
		
		$bar_nr++;
		}
	// Diagramm 		
	for($i=0; $i<$bar_nr; $i++)
		{
		$value=$bar[$i];
		if ($value == "") $value = 0;
		if (max($bar) > 0) {$bar_hight=round((200/max($bar))*$value);} else $bar_hight = 0;
		if ($bar_hight == 0) $bar_hight = 1;	
		echo "<td width=\"30\">";
		echo "<div class=\"bar\" style=\"height:".$bar_hight."px;\" title=\"".$bar_title[$i]." - $value Visitors\"></div></td>\n";
		}
	?>
    </tr><tr height="20">
	<td colspan="6" class="timeline"><?PHP echo date("j.M",mktime(0, 0, 0, $show_month, 1, $show_yaer)); ?></td>
	<td colspan="6" class="timeline"><?PHP echo date("j.M",mktime(0, 0, 0, $show_month, 7, $show_yaer)); ?></td>
	<td colspan="6" class="timeline"><?PHP echo date("j.M",mktime(0, 0, 0, $show_month, 13, $show_yaer)); ?></td>
	<td colspan="6" class="timeline"><?PHP echo date("j.M",mktime(0, 0, 0, $show_month, 19, $show_yaer)); ?></td>
	<td colspan="7" class="timeline"><?PHP echo date("j.M",mktime(0, 0, 0, $show_month, 25, $show_yaer)); ?></td>
	</tr></table>
  </div>
  <div style="clear:both"></div>
</div>
</body>
</html>