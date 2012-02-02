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

// Verbinden mit Server
	$serverID = @mysql_connect($db_host, $db_user, $db_pass);
	if(!$serverID) {echo "<font color=\"#CC0000\">- Der DB Server ist leider nicht erreichbar!</font><br>"; }
	else {echo "<font color=\"#00CC00\">- Der DB Server wurde erfolgreich erreicht!</font><br>";}
// Verbinden mit der Datenbank
	$datenbank = @mysql_select_db($db_name, $serverID);
	if (!$datenbank) {echo "<font color=\"#CC0000\">- Die Datenbank wurde nicht gefunden!</font><br>"; }
	else {echo "<font color=\"#00CC00\">- Die Datenbank wurde gefunden!</font><br>";}	

// Tabellenstruktur für Tabelle `...Day`
	$anlegen = @mysql_query("CREATE TABLE `".$db_prefix."Day` (
  	`id` int(11) NOT NULL auto_increment,
	`day` varchar(10) NOT NULL default '',
	`user` int(10) NOT NULL default '0',
	`view` int(10) NOT NULL default '0',
	PRIMARY KEY  (`id`)
	) TYPE=MyISAM COMMENT='ChilliStats Days by ChilliScripts';");
	if (!$anlegen) {echo "<font color=\"#CC0000\">- Tabelle ".$db_prefix."Day konnte nicht angelegt werden!</font><br>";}
	else {echo "<font color=\"#00CC00\">- Tabelle ".$db_prefix."Day wurde erfolgreich angelegt!<br>";}

// Tabellenstruktur für Tabelle `...IPs`
	$anlegen = @mysql_query("CREATE TABLE `".$db_prefix."IPs` (
	`id` int(11) NOT NULL auto_increment,
	`ip` varchar(15) NOT NULL default '',
	`time` int(20) NOT NULL default '0',
	`online` int(20) NOT NULL default '0',
	PRIMARY KEY  (`id`)
	) TYPE=MyISAM COMMENT='ChilliStats IPs by ChilliScripts' ;");
	if (!$anlegen) {echo "<font color=\"#CC0000\">- Tabelle ".$db_prefix."IPs konnte nicht angelegt werden!</font><br>";}
	else {echo "<font color=\"#00CC00\">- Tabelle ".$db_prefix."IPs wurde erfolgreich angelegt!<br>";}

// Tabellenstruktur für Tabelle `...Page`
	$anlegen = @mysql_query("CREATE TABLE `".$db_prefix."Page` (
	`id` int(11) NOT NULL auto_increment,
	`day` varchar(10) NOT NULL default '',
	`page` varchar(255) NOT NULL default '',
	`view` int(10) NOT NULL default '0',
	PRIMARY KEY  (`id`)
	) TYPE=MyISAM COMMENT='ChilliStats Page by ChilliScripts';");
	if (!$anlegen) {echo "<font color=\"#CC0000\">- Tabelle ".$db_prefix."Page konnte nicht angelegt werden!</font><br>";}
	else {echo "<font color=\"#00CC00\">- Tabelle ".$db_prefix."Page wurde erfolgreich angelegt!<br>";}

// Tabellenstruktur für Tabelle `...Referer`
	$anlegen = @mysql_query("CREATE TABLE `".$db_prefix."Referer` (
	`id` int(11) NOT NULL auto_increment,
	`day` varchar(10) NOT NULL default '',
	`referer` varchar(255) NOT NULL default '',
	`view` int(10) NOT NULL default '0',
	PRIMARY KEY  (`id`)
	) TYPE=MyISAM COMMENT='ChilliStats Referer by ChilliScripts' ;");
	if (!$anlegen) {echo "<font color=\"#CC0000\">- Tabelle ".$db_prefix."Referer konnte nicht angelegt werden!</font><br>";}
	else {echo "<font color=\"#00CC00\">- Tabelle ".$db_prefix."Referer wurde erfolgreich angelegt!<br>";}

// Tabellenstruktur für Tabelle `...Keyword`
	$anlegen = @mysql_query("CREATE TABLE `".$db_prefix."Keyword` (
	`id` int(11) NOT NULL auto_increment,
	`day` varchar(10) NOT NULL default '',
	`keyword` varchar(255) NOT NULL default '',
	`view` int(10) NOT NULL default '0',
	PRIMARY KEY  (`id`)
	) TYPE=MyISAM COMMENT='ChilliStats Keyword by ChilliScripts' ;");
	if (!$anlegen) {echo "<font color=\"#CC0000\">- Tabelle ".$db_prefix."Keyword konnte nicht angelegt werden!</font><br>";}
	else {echo "<font color=\"#00CC00\">- Tabelle ".$db_prefix."Keyword wurde erfolgreich angelegt!<br>";}

// Tabellenstruktur für Tabelle `...Language`
	$anlegen = @mysql_query("CREATE TABLE `".$db_prefix."Language` (
	`id` int(11) NOT NULL auto_increment,
	`day` varchar(10) NOT NULL default '',
	`language` varchar(2) NOT NULL default '',
	`view` int(10) NOT NULL default '0',
	PRIMARY KEY  (`id`)
	) TYPE=MyISAM COMMENT='ChilliStats Language by ChilliScripts' ;");
	if (!$anlegen) {echo "<font color=\"#CC0000\">- Tabelle ".$db_prefix."Language konnte nicht angelegt werden!</font><br>";}
	else {echo "<font color=\"#00CC00\">- Tabelle ".$db_prefix."Language wurde erfolgreich angelegt!<br>";}

?>
<p>
Copy and Paste: <br />
<textarea name="textfield" cols="80" rows="5" wrap="off" readonly="readonly"><script type="text/javascript">
document.write('<a href="chilistats/stats.php"><img src="chilistats/counter.php?ref=' + escape(document.referrer) + '"></a>')
</script>
<noscript><a href="chilistats/stats.php"><img src="chilistats/counter.php" /></a></noscript></textarea>
</p>
<div align="center"><a href="http://www.chiliscripts.com" target="_blank" style="text-decoration:none"><font size="2" color="#000000">ChiliStats &copy;chiliscripts</font></a></div>