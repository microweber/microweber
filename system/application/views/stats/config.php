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


//
// !! These settings must be changed
//

// Database Connection
$db_host = 'localhost'; // database server (e.g. localhost)
$db_user = 'db_user'; // user
$db_pass = 'db_pass'; // password
$db_name = 'db_name';// database name
$db_prefix = 'chilli_stats_1_'; // database prefix


//
// Optional settings
//

$style = "dark"; // Counter Style "dark" or "light"
$show = "totally"; // Counter shows "totally"  or "last24h"  visitors
$size = "big"; // Size of the counter "small" or "big"

$reload=3*60*60; // Reload lock in seconds (3 * 60 * 60 => 3 hours)
$online=3*60; // online time in seconds (3 * 60 => 3 minutes)
$oldentries=7; // delete Visitor infos after x days (7 => 7 days)

//
// End of settings
//

// connect to database
$serverID = @mysql_connect($db_host, $db_user, $db_pass);
if(!$serverID) {echo "The DB server is not reachable!"; exit;}
$datenbank = @mysql_select_db($db_name, $serverID);
if(!$datenbank) {echo "The database was not found!"; exit;}
?>