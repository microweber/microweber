<?

class numia_api {
	private $remote_api_url = 'http://54.243.113.235/service/numia_api/numia_api_server.php';

	function call($method = false, $post_params = false) {
		$cookie = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cookies' . DIRECTORY_SEPARATOR;
		if (!is_dir($cookie)) {
			mkdir($cookie);
		}
		$cookie_file = $cookie . 'cookie.txt';
		$requestUrl = $this -> remote_api_url;
		if ($method != false) {
			$requestUrl = $requestUrl . '?numia_api_function=' . $method;
		}
		$ch = curl_init($requestUrl);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01;)");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: multipart/form-data"));

		if ($post_params != false and is_array($post_params)) {

			$post_params = $this -> http_build_query_for_curl($post_params);

			curl_setopt($ch, CURLOPT_POST, count($post_params));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
		}
		$result = curl_exec($ch);

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
				$this -> http_build_query_for_curl($value, $new, $k);
			} else {
				$new[$k] = $value;
			}
		}
		return $new;
	}

}

$numia_api = new numia_api();

$params = array();
$params['email'] = 'my@email';
$params['password'] = 'pass';
$result = $numia_api -> call('user_register', $params);
print($result);

print('<hr>');
$params = array();
$params['email'] = 'login@email';
$params['password'] = 'loginpass';
$result = $numia_api -> call('user_login', $params);
print($result);

print('<hr>');
$params = array();
$result = $numia_api -> call('customer_list', $params);
print($result);

print('<hr>');
$params = array();

$params['invoice_number'] = '123';
$params['currency'] = 'USD';

$params['customer_name'] = 'New customer';
$params['email'] = 'customer@example.com';
$params['phone'] = '123-456-54';

$params['country'] = 'Bulgaria';
$params['city'] = 'Sofia';
$params['state'] = '';
$params['zip'] = '1000';

$params['address'] = 'Pimen Zografski 10';
$params['products'] = array();
$params['products'][] = array('title' => 'T-Shirt', 'amount' => '5', 'quantity' => '3');
$params['products'][] = array('title' => 'Notebook', 'amount' => '1500', 'quantity' => '1');

$result = $numia_api -> call('create_invoice', $params);
print($result);

print('<hr>');
$params = array();
$params['invoice_number'] = '123';
$result = $numia_api -> call('get_invoice', $params);
print($result);


print('<hr>');
$params = array();
$params['keyword'] = '123';
$result = $numia_api -> call('invoice_list', $params);
print($result);

print('<hr>');
$params = array();
$params['email'] = 'bigcustomer@email.com';
$result = $numia_api -> call('invoice_list', $params);
print($result);

print('<hr>');
$params = array();
$params['invoice_number'] = '123';
$params['email'] = 'customer@example.com';
$params['email_content'] = 'HTML STRING';


$result = $numia_api -> call('send_invoice', $params);
print($result);


