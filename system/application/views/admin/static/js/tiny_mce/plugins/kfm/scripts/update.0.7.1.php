<?php
switch($kfm_db_type){
	case 'mysql':{
		require 'scripts/db.mysql.update.0.7.1.php';
		break;
	}
	case 'pgsql':{
		require 'scripts/db.pgsql.update.0.7.1.php';
		break;
	}
	case 'sqlite': case 'sqlitepdo':{
		require 'scripts/db.sqlite.update.0.7.1.php';
		break;
	}
	default:{
		echo 'error: unknown database specified in scripts/update.0.7.1.php';
		exit;
	}
}
?>
