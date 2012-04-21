<?php
/*
######################      CWB\     #######################
############################################################
CWB PRO			$Name$
Revision		$Revision: 8713 $
Author			$Author: apuskas $
Created 03/01/03        $Date: 2008-02-25 11:49:14 +0200 (L, 25 feb. 2008) $
Writed by               GraFX (webmaster@grafxsoftware.com)
Scripts Home:           http://www.grafxsoftware.com
############################################################
*/
//print DB_HOST."=".DB_USR."=".DB_PWD;
$cid = mysql_connect(DB_HOST,DB_USR,DB_PWD) or die ("I cannot open a connection to database. If this is a new site, please <a href='installer/'> use installer</a>");
mysql_select_db(DB_NAME) or die("I cannot select the database! If this is a new site, please <a href='installer/'> use installer</a>");
?>