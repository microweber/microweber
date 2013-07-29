<?php 
namespace accounting\numia;
 
class api {
	private $remote_api_url = 'http://dev.numia.biz/numia_api_server.php';

	function call($method = false, $post_params = false) {
		$cookie = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cookies' . DIRECTORY_SEPARATOR;
		if (!is_dir($cookie)) {
			mkdir($cookie);
		}
		$cookie_file = $cookie . 'cookie.txt';
		$requestUrl = $this->remote_api_url;
		if ($method != false) {
			$requestUrl = $requestUrl . '?numia_api_function=' . $method;
		}
		$ch = curl_init($requestUrl);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_USERAGENT, "Numia/Microweber API Client");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: multipart/form-data"));

		if ($post_params != false and is_array($post_params)) {

			$post_params = $this->http_build_query_for_curl($post_params);

			curl_setopt($ch, CURLOPT_POST, count($post_params));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
		}
		$result = curl_exec($ch);
		if($result != false){
		$result_dec = json_decode($result,1);	
		if($result_dec != false){
		$result = $result_dec;	
		}
		}

		curl_close($ch);
		return $result;
	}

	function http_build_query_for_curl($arrays, &$new = array(), $prefix = null) {

		if (is_object($arrays)) {
			$arrays = get_object_vars($arrays);
		}

		foreach ($arrays AS $key => $value) {
			$k = isset($prefix) ? $prefix . '[' . $key . ']' : $key;
			if (is_array($value) OR is_object($value)) {
				$this->http_build_query_for_curl($value, $new, $k);
			} else {
				$new[$k] = $value;
			}
		}
		return $new;
	}

}




