<?php
$version=$kfm_parameters['version'];
if($version==''||$version=='7.0'||$version<'1.3')require KFM_BASE_PATH.'scripts/update.1.2.php';
$kfm_parameters['version']='1.3';
$kfmdb->query('update '.KFM_DB_PREFIX.'directories set name="root" where id=1');

$kfmdb->query('update '.KFM_DB_PREFIX.'parameters set value="'.$kfm_parameters['version'].'" where name="version"');
?>
