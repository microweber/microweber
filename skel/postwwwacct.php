#!/usr/bin/php
<?php

print "\nRunning postwwwacct...\n";
//var_dump($argv);
$opts = array();
$argv0 = array_shift($argv);
while(count($argv)) {
$key = array_shift($argv);
$value = array_shift($argv);
$opts[$key] = $value;
}
$c = serialize($opts);
$f = '/home/'.$opts['user'].'/mw_account.php';
print "\nUserDir line has been modified\n";
file_put_contents($f, $c);
print "\npostwwwacct finished\n";
$cpaneluser = $opts['user'];
$cpanelpass = $opts['pass'];
$databasename =  $opts['user'].'_'.'db_main';


 
$par1 = http_build_query(array($databasename));

$act1= "https://127.0.0.1:2083/xml-api/cpanel?user={$cpaneluser}&cpanel_xmlapi_module=Mysql&cpanel_xmlapi_func=adddb&cpanel_xmlapi_apiversion=1&".$par1;
$opts = array(
  'http'=>array(
    'method'=>"POST",
    'header'=>"Authorization: Basic " . base64_encode($cpaneluser.":".$cpanelpass) . "\n\r"              
  )
);

$context = stream_context_create($opts);
var_dump($act1); 

 $act_res = file_get_contents($act1, false, $context);
var_dump($act_res); 


?>