<?php

require_once 'initialise.php';
if(!isset($_REQUEST['md5']))exit;

$md5=$_REQUEST['md5'];
$filename=WORKPATH.'css_sprites/'.$md5.'.png';
if(!file_exists($filename))die('file doesn\'t exist: '.$filename);
header('Content-type: image/png');
readfile($filename);

?>
