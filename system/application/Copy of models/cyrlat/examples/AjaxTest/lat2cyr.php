<?php
require_once "lib/config.php";
require_once "lib/Php.php";
require_once "../../cyrlat.class.php"; 

$JsHttpRequest = new Subsys_JsHttpRequest_Php("windows-1251");

$q = $_REQUEST['q'];

$text=new CyrLat;

$_RESULT = array(
  "q"     => $q,
  "cyr"   => $text->lat2cyr($q),
); 
?>