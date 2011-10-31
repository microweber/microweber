<?php
$version=$kfm_parameters['version'];
if($version==''||$version=='7.0'||$version<'1.4')require KFM_BASE_PATH.'scripts/update.1.3.php';
$kfm_parameters['version']='1.4';
$kfmdb->query('update '.KFM_DB_PREFIX.'parameters set value="'.$kfm_parameters['version'].'" where name="version"');
switch($kfm_db_type){
	case 'mysql':{
		require 'scripts/db.mysql.update.1.4.php';
		break;
	}
	case 'pgsql':{
		require 'scripts/db.pgsql.update.1.4.php';
		break;
	}
	case 'sqlite': case 'sqlitepdo':{
		require 'scripts/db.sqlite.update.1.4.php';
		break;
	}
	default:{
		echo 'error: unknown database specified in scripts/update.1.4.php';
		exit;
	}
}
