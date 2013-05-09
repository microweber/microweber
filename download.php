<? 

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
	$st_c = file_get_contents($stats_f);
}

$st_c++;
file_put_contents($stats_f,$st_c);
if(isset($_GET['webinstall'])){
		header("Location: https://s3.amazonaws.com/mw-download/webinstall.zip");

	
} else {
	header("Location: https://s3.amazonaws.com/mw-download/mw-latest.zip");

}
exit;

//
