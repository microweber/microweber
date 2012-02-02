<?PHP
/*
	--------------------------------------------------------
	ChiliStats der neue Statistik Counter 
	basierend auf dem Statistik Counter von pawlita.de
	-------------------------------------------------------
	Das Skript unterliegt dem Urheberschutz Gesetz. Alle Rechte und
	copyrights liegen bei dem Autor:
	Adam Pawlita, http://www.chiliscripts.com
	Dies Skript darf frei verwendet und weitergegeben werden, solange
	die angegebenen Copyrightvermerke in allen Teilen des Scripts vor-
	handen bleiben. Für den fehlerfreien Betrieb, oder Schäden die durch
	den Betrieb dieses Skriptes entstehen, übernimmt der Autor keinerlei
	Gewährleistung. Die Inbetriebnahme erfolgt in jedem Falle 
	auf eigenes Risiko des Betreibers.
	-------------------------------------------------------
*/

require_once('config.php');

//
// initialization and visitor Information
//

// Date Time
$time=time();
$day=date("Y.m.d",$time); // YYYY.MM.DD
$month=date("Y.m",$time); // YYYY.MM

// IP adress
$ip=$_SERVER['REMOTE_ADDR']; 

// Get Referrer and Page
if ($_GET["ref"] <> "" ) 
	{
	// from javascript
	$referer = $_GET["ref"];
	$page = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);	
	} 
else 
	{
	// from php
	$referer=$_SERVER['HTTP_REFERER'];
	$page=$_SERVER['PHP_SELF']; // with include via php		
	} 	
// cleanup
if (basename($page) == basename(__FILE__)) $page="" ; // count not counter.php

$server_host=$_SERVER["HTTP_HOST"]; // Server Host
if (substr($server_host,0,4) == "www.") $server_host=substr($server_host,4); // Server Host without www.

$referer_host=parse_url($referer, PHP_URL_HOST); // Referrer Host
if (substr($referer_host,0,4) == "www.") $referer_host=substr($referer_host,4); // Referer Host without www.

// adjust search engines 
if (strstr($referer_host, "google."))
	{
	$referer_query=parse_url($referer, PHP_URL_QUERY);
	$referer_query.="&";
	preg_match('/q=(.*)&/UiS', $referer_query, $keys);
	
	$keyword=urldecode($keys[1]); // These are the search terms
	$referer_host="Google"; // adjust host
	}
if (strstr($referer_host, "yahoo."))
	{
	$referer_query=parse_url($referer, PHP_URL_QUERY);
	$referer_query.="&";
	preg_match('/p=(.*)&/UiS', $referer_query, $keys);
	
	$keyword=urldecode($keys[1]); // These are the search terms
	$referer_host="Yahoo"; // adjust host
	}
if (strstr($referer_host, "bing."))
	{
	$referer_query=parse_url($referer, PHP_URL_QUERY);
	$referer_query.="&";
	preg_match('/q=(.*)&/UiS', $referer_query, $keys);
	
	$keyword=urldecode($keys[1]); // These are the search terms
	$referer_host="Bing"; // adjust host
	}
		
// Language
$language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2);

//
// Counter
//

// delete old IPs
$anfangGestern = mktime(0, 0, 0, date(n), date(j), date(Y)) - 48*60*60 ; // 48*60*60 => after 48 hours
$delete=mysql_query("delete from ".$db_prefix."IPs where time<'$anfangGestern'");
if (!$delete) {echo"Es ist ein Fehler aufgetreten, möglicherweise ist die Tabelle nicht angelegt."; exit;}

// delete old page,referrer,language and keywords
$old_day=date("Y.m.d",mktime(0, 0, 0, date("n"), date("j")-$oldentries, date("Y"))); // delete older than $oldentries(config.php) days
$delete=mysql_query("delete from ".$db_prefix."Page where day<='$old_day'");
$delete=mysql_query("delete from ".$db_prefix."Referer where day<='$old_day'");
$delete=mysql_query("delete from ".$db_prefix."Keyword where day<='$old_day'");
$delete=mysql_query("delete from ".$db_prefix."Language where day<='$old_day'");
if (!$delete) {echo"Es ist ein Fehler aufgetreten, möglicherweise ist die Tabelle nicht angelegt."; exit;}

// insert a new day
$neuerTag=mysql_query("select id from ".$db_prefix."Day where day='$day'");
if (!$neuerTag) {echo"Es ist ein Fehler aufgetreten, möglicherweise ist die Tabelle nicht angelegt."; exit;}
if (mysql_num_rows($neuerTag)==0)
	{ 
	mysql_query("insert into ".$db_prefix."Day (day, user, view) values ('$day', '0', '0')");
	}
	
// check reload and set online time
$newuser=0;
$oldreload = $time-$reload;
$gesperrt=mysql_query("select id from ".$db_prefix."IPs where ip='$ip' AND time>'$oldreload' order by id desc limit 1");
if (!$gesperrt) {echo"Es ist ein Fehler aufgetreten, möglicherweise ist die Tabelle nicht angelegt."; exit;}
if (mysql_num_rows($gesperrt)==0)
	{
	// new visitor
	$newuser=1;
	mysql_query("insert into ".$db_prefix."IPs (ip, time, online) values ('$ip', '$time', '$time')");
	mysql_query("update ".$db_prefix."Day set user=user+1, view=view+1 where day='$day'");
	}
else
	{
	// reload visitor
	$gesperrtID=mysql_result($gesperrt,0,0);
	mysql_query("update ".$db_prefix."IPs set online='$time' where id='$gesperrtID'");
	mysql_query("update ".$db_prefix."Day set view=view+1 where day='$day'");
	}

// Page
if($page <> "") {
	$ergebnis = mysql_query("SELECT id from ".$db_prefix."Page WHERE page='$page' AND day='$day'");
	if (!$ergebnis) {echo"Es ist ein Fehler aufgetreten, möglicherweise ist die Tabelle nicht angelegt."; exit;}
	if (mysql_num_rows($ergebnis)==0)
		{
		mysql_query("insert into ".$db_prefix."Page (day, page, view) values ('$day', '$page', '1')");
		}
	else
		{ 
		$pageid=mysql_result($ergebnis,0,0);
		mysql_query("update ".$db_prefix."Page set view=view+1 where id='$pageid'");
		}
	}
// Referer
if(stristr($server_host, $referer_host) === FALSE AND $referer_host<>"" AND $newuser == 1) {
	$ergebnis = mysql_query("SELECT id from ".$db_prefix."Referer WHERE referer='$referer_host' AND day='$day'");
	if (!$ergebnis) {echo"Es ist ein Fehler aufgetreten, möglicherweise ist die Tabelle nicht angelegt."; exit;}
	if (mysql_num_rows($ergebnis)==0)
		{
		mysql_query("insert into ".$db_prefix."Referer (day, referer, view) values ('$day', '$referer_host', '1')");
		}
	else
		{ 
		$refererid=mysql_result($ergebnis,0,0);
		mysql_query("update ".$db_prefix."Referer set view=view+1 where id='$refererid'");
		}
	}

// keywords 
if($keyword<>"" AND $newuser == 1) {
	$ergebnis = mysql_query("SELECT id from ".$db_prefix."Keyword WHERE keyword='$keyword' AND day='$day'");
	if (!$ergebnis) {echo"Es ist ein Fehler aufgetreten, möglicherweise ist die Tabelle nicht angelegt."; exit;}
	if (mysql_num_rows($ergebnis)==0)
		{
		mysql_query("insert into ".$db_prefix."Keyword (day, keyword, view) values ('$day', '$keyword', '1')");
		}
	else
		{ 
		$keywordid=mysql_result($ergebnis,0,0);
		mysql_query("update ".$db_prefix."Keyword set view=view+1 where id='$keywordid'");
		}
	}
// Language 
if($language<>"" AND $newuser == 1) {
	$ergebnis = mysql_query("SELECT id from ".$db_prefix."Language WHERE language='$language'");
	if (!$ergebnis) {echo"Es ist ein Fehler aufgetreten, möglicherweise ist die Tabelle nicht angelegt."; exit;}
	if (mysql_num_rows($ergebnis)==0)
		{
		mysql_query("insert into ".$db_prefix."Language (day, language, view) values ('$day', '$language', '1')");
		}
	else
		{ 
		$languageid=mysql_result($ergebnis,0,0);
		mysql_query("update ".$db_prefix."Language set view=view+1 where id='$languageid'");
		}
	}

//
// Generate Image
//

	// Get Value from DB
	if ($show == "last24h")
	{
	// Last24h
	$islast=$time-24*60*60;
	$abfrage=mysql_query("select count(id) from ".$db_prefix."IPs where time>='$islast'");
	$value=mysql_result($abfrage,0,0);
	$title="Last 24 hours";
	mysql_free_result($abfrage);
	}
	else
	{
	// Totally Visitors	
	$abfrage=mysql_query("select sum(user) from ".$db_prefix."Day");
	$value=mysql_result($abfrage,0,0);
	$title="Totally Visitors";
	mysql_free_result($abfrage);	
	}
	// short value
	if ( $value > 999 ) { $value = $value / 1000; $einheit = "k"; }
	if ($value > 999) { $value = $value / 1000; $einheit = "m"; }
	if ( $value > 999 ) { $value = ">999"; $einheit = "m"; }
	else { 
	if ( $value >=10) $value=round($value,0);
	else $value=round($value,1);}
	$value.=$einheit;
	
	// Variables
	$title_font="OpenSans-Regular.ttf";
	$value_font="OpenSans-Bold.ttf";
	
	if ($size == "small")
	{
	$width=90;
	$height=20;
	$title_font_size = 8;
	$value_font_size = 9;
	$title_pos_y = 15;
	$value_pos_y = 16;	
	// short title
	if ($show == "last24h") {$title="Last24h";}
	else {$title="Visitors";}
	// left title
	$size = imagettfbbox($title_font_size, 0, $title_font, $title);
	$titleWidth = $size[2] - $size[0];
	$title_pos_x = 8;
	// right center value
	$size = imagettfbbox($value_font_size, 0, $value_font, $value);
	$valueWidth = $size[2] - $size[0];
	$space_left = $title_pos_x + $titleWidth;
	$value_pos_x = $space_left + ((($width - $space_left) / 2) - ($valueWidth / 2));
	}
	else
	{
	$width=90;
	$height=55;
	$title_font_size = 8;
	$value_font_size = 24;
	$title_pos_y = 15;
	$value_pos_y = 48;
	// center title
	$size = imagettfbbox($title_font_size, 0, $title_font, $title);
	$textWidth = $size[2] - $size[0];
	$title_pos_x = ($width / 2) - ($textWidth / 2);
	// center value
	$size = imagettfbbox($value_font_size, 0, $value_font, $value);
	$textWidth = $size[2] - $size[0];
	$value_pos_x = ($width / 2) - ($textWidth / 2);
	}

	//  Create a blank image
	$im = imagecreatetruecolor($width,$height);	
		
	// Colors
	if ($style == "light")
	{
	$bg_color = imagecolorallocatealpha($im, 235,235,235,0);
	$title_color = imagecolorallocate($im, 50,50,50);
	$value_color = imagecolorallocate($im, 25,25,25);	
	}
	else
	{
	$bg_color = imagecolorallocatealpha($im, 50,50,50,0);
	$title_color = imagecolorallocate($im, 255,255,255);
	$value_color = imagecolorallocate($im, 255,255,255);
	}
	$shadow_color = imagecolorallocatealpha($im, 0,0,0,115);
	$red = imagecolorallocate($im, 223,1,1);
	
	// Fill BG color
	imagefill($im, 0, 0, $bg_color);
	// Red line
	imageline($im,0,0,$width,0,$red);
	imageline($im,0,1,$width,1,$red);
	//  title
	imagettftext ($im, $title_font_size, 0, $title_pos_x+2, $title_pos_y+2, $shadow_color, $title_font, $title); 
	imagettftext ($im, $title_font_size, 0, $title_pos_x, $title_pos_y, $title_color, $title_font, $title); 
	// value
	imagettftext ($im, $value_font_size, 0, $value_pos_x+2, $value_pos_y+2, $shadow_color, $value_font, $value);
	imagettftext ($im, $value_font_size, 0, $value_pos_x, $value_pos_y, $value_color, $value_font, $value);
		
	// image output
	header ("Content-type: image/png");
	// create PNG
	imagepng($im);
	// destroy temp image
	imagedestroy($im);

?>