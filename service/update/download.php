<?php 

$uip = $_SERVER['REMOTE_ADDR'];

$stats = dirname(__FILE__).DIRECTORY_SEPARATOR.'db'.DIRECTORY_SEPARATOR;
if(!is_dir($stats)){
	mkdir($stats);
}

$stats = $stats.'stats'.DIRECTORY_SEPARATOR;
if(!is_dir($stats)){
	mkdir($stats);
}


$stats_f = $stats.$uip.'.txt';
$st_c = 0;
if(is_file($stats_f)){
	$st_c1 = file_get_contents($stats_f);
	$st_c1 = json_decode($st_c1, true);
	if($st_c1 != false and isset($st_c1['count'])){
	$st_c = intval($st_c1['count']);
	}
}
$st_c++;

$to_serialize = array();
$to_serialize['request'] = $_REQUEST;
$to_serialize['server'] = $_SERVER;
$to_serialize['count'] = $st_c;
$to_serialize = @json_encode($to_serialize);




file_put_contents($stats_f,$to_serialize);
 

//
