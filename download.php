<? 

function getfile_latest_mw($requestUrl, $save_to_file = false) {

	$opts = array('http' => array('method' => 'POST', 'header' => "User-Agent: Microweber/Web Install" . "\r\n" . 'Content-type: application/x-www-form-urlencoded' . "\r\n"));
	$requestUrl = str_replace(' ', '%20', $requestUrl);
	$context = stream_context_create($opts);

	$result = file_get_contents($requestUrl, false, $context);
	if ($save_to_file == true) {
		//  d($result);
		file_put_contents($save_to_file, $result);
	} else {
		return $result;
	}

	//..file_put_contents($dir . substr($url, strrpos($url, '/'), strlen($url)), file_get_contents($url));
}

$url = false;
$latest_url = "http://api.microweber.net/service/update/?api_function=latest";
	$latest_url = getfile_latest_mw($latest_url);
	if ($latest_url != false) {
		$latest_url = json_decode($latest_url, 1);
	}
	if ($latest_url != false and isset($latest_url['core_update'])) {
		$url = $latest_url['core_update'];
		
		
	}

 



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

	
} elseif($url != false) {
		header("Location: ".$url);
} else {
	header("Location: https://s3.amazonaws.com/mw-download/mw-latest.zip");

}
exit;

//
