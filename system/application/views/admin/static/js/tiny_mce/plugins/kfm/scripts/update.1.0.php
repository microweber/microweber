<?php
$version=$kfm_parameters['version'];
if($version==''||$version=='7.0'||$version<'0.9.2')require 'scripts/update.0.9.2.php';
$kfm_parameters['version']='1.0';
$kfmdb->query("delete from ".KFM_DB_PREFIX."parameters where name='version'");
$kfmdb->query("insert into ".KFM_DB_PREFIX."parameters (name,value) values ('version','1.0')");
?>
