<?php
define('MW_BARE_BONES', 1);
include('../../index.php');
$cache_here_now = dirname(__FILE__).DS.'cache'.DS;
if(!is_dir($cache_here_now)){
mkdir_recursive($cache_here_now);
}

function proper_parse_str1($str) {
  # result array
  $arr = array();

  # split on outer delimiter
  $pairs = explode('&', $str);

  # loop through each pair
  foreach ($pairs as $i) {
    # split into name and value
    @list($name,$value) = explode('=', $i, 2);
    
    # if name already exists
    if( isset($arr[$name]) ) {
      # stick multiple values into an array
      if( is_array($arr[$name]) ) {
        $arr[$name][] = $value;
      }
      else {
        $arr[$name] = array($arr[$name], $value);
      }
    }
    # otherwise, simply stick it in a scalar
    else {
      $arr[$name] = $value;
    }
  }

  # return result array
  return $arr;
}





$uip = USER_IP;
 
if(isset($_REQUEST['ip'])){
$uip = $_REQUEST['ip'];

}

$uip = str_replace('..', '',$uip);

$cache_here_now_file = $cache_here_now.$uip.'.txt';
if(is_file($cache_here_now_file)){
print file_get_contents($cache_here_now_file);
} else {
$geo = 'http://www.geojoe.co.uk/api/batch/?ips='.$uip; 
$geo1 = url_download($geo);
 
$result = proper_parse_str1($geo1);
$result = json_encode($result);
print $result;
file_put_contents($cache_here_now_file, $result);
}
exit();


//d($geo1) ;
//d($result) ;




 ?>