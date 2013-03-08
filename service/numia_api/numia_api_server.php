<?

if (isset($_GET['numia_api_function'])) {
	$method_name = trim($_GET['numia_api_function']);
	if (isset($_REQUEST['numia_api_function'])) {
		unset($_REQUEST['numia_api_function']);
	}
	$numia_api_server = new numia_api_server();
	if (method_exists($numia_api_server, $method_name)) {
		$result = $numia_api_server -> $method_name($_REQUEST);
		if($result != false){
				print json_encode($result);
		}
 	}

}

class numia_api_server {

	function debug($method, $params) {
		print "Called: <b>" . $method . '</b> with params: ';
		print_r($params);
	}

	function user_register($params) {
		$this -> debug(__METHOD__, $params);
		//return serults as array
		$results = array();
		return $results;
	}

	function user_login($params) {
		$this -> debug(__METHOD__, $params);
		//return serults as array
		$results = array();
		return $results;
	}

	function customer_list($params) {
		$this -> debug(__METHOD__, $params);
		
		//return serults as array
		$results = array();
		return $results;
	}

	function create_invoice($params) {
		$this -> debug(__METHOD__, $params);
		//return serults as array
		$results = array();
		return $results;
	}

	function get_invoice($params) {
		$this -> debug(__METHOD__, $params);
		//return serults as array
		$results = array();
		return $results;
	}

	function invoice_list($params) {
		$this -> debug(__METHOD__, $params);
		//return serults as array
		$results = array();
		return $results;
	}

	function send_invoice($params) {
		$this -> debug(__METHOD__, $params);
		//return serults as array
		$results = array();
		return $results;
	}

}
