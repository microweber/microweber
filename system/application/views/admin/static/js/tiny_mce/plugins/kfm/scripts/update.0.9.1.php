<?php
$version=$kfm_parameters['version'];
if($version==''||$version=='7.0'||$version<'0.9.1')require 'scripts/update.0.8.php';
switch($kfm_db_type){
	case 'mysql':{
		require 'scripts/db.mysql.update.0.9.1.php';
		break;
	}
	case 'pgsql':{
		require 'scripts/db.pgsql.update.0.9.1.php';
		break;
	}
	case 'sqlite': case 'sqlitepdo':{
		require 'scripts/db.sqlite.update.0.9.1.php';
		break;
	}
	default:{
		echo 'error: unknown database specified in scripts/update.0.9.1.php';
		exit;
	}
}
?>
