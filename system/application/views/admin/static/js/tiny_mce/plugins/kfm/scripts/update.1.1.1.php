<?php
$version=$kfm_parameters['version'];
if($version==''||$version=='7.0'||$version<'1.1.1')require KFM_BASE_PATH.'scripts/update.1.1.php';
$kfm_parameters['version']='1.1.1';
$kfmdb->query("delete from ".KFM_DB_PREFIX."parameters where name='version'");
$kfmdb->query("insert into ".KFM_DB_PREFIX."parameters (name,value) values ('version','".$kfm_parameters['version']."')");
?>
