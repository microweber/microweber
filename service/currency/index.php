<? function google_conv_currency($from_Currency,$to_Currency,$amount) {
$amount = urlencode($amount);
$from_Currency = urlencode($from_Currency);
$to_Currency = urlencode($to_Currency);
$url = "http://www.google.com/ig/calculator?hl=en&q=$amount$from_Currency=?$to_Currency";
$ch = curl_init();
$timeout = 0;
curl_setopt ($ch, CURLOPT_URL, $url);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch,  CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
$rawdata = curl_exec($ch);
curl_close($ch);
$data = explode('"', $rawdata);
$data = explode(' ', $data['3']);
$var = $data['0'];
return round($var,2);
}
 
define('MW_BARE_BONES', 1);
include('../../index.php');
$cache_here_now = dirname(__FILE__).DS.'cache'.DS;
if(!is_dir($cache_here_now)){
mkdir_recursive($cache_here_now);
}
 

 if(!isset($_REQUEST['from'])){
	$from = "USD"; 
 } else {
	$from = strtoupper($_REQUEST['from']);; 
 }

 if(!isset($_REQUEST['to'])){
	$to = "EUR"; 
 } else {
	$to = strtoupper($_REQUEST['to']);; 
 }
 
 
$uip = str_replace('..', '',$from.$to);
 
$cache_here_now_file = $cache_here_now.$uip.'.txt';
if(is_file($cache_here_now_file) and filemtime($cache_here_now_file) > time()-60*60 ){
print file_get_contents($cache_here_now_file);
} else {
 
$result = google_conv_currency($from,$to,1);
$result = ($result);
print $result;
file_put_contents($cache_here_now_file, $result);
}
exit();


 


 
 