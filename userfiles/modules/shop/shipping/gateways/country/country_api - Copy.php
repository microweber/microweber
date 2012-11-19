<?

$ns = $config['ns'];
api_expose('shipping_to_country');
api_expose('shipping_to_country/d');
class shipping_to_country {

	// singleton instance
	private static $table;

	// private constructor function
	// to prevent external instantiation
	function __construct() {
		$this -> table = TABLE_PREFIX . 'cart_shipping';
	}

	// getInstance method
	function save($data) {
		if (is_admin() == false) {
			error('Must be admin');

		}

		if (isset($data['shiping_country'])) {
			if($data['shiping_country'] == 'none'){
				error('Please choose country');
			}
			if (isset($data['id']) and intval($data['id']) > 0) {
				
				} else {
			$check = $this -> get('shiping_country=' . $data['shiping_country']);
			if ($check != false and isarr($check[0]) and isset($check[0]['id'])) {
				$data['id'] = $check[0]['id'];
			}
				}
		}
		
			 
		

		$data = save_data($this -> table, $data);
		return ($data);
	}

	function get($params = false) {

		$params2 = array();
		if ($params == false) {
			$params = array();
		}
		if (is_string($params)) {
			$params = parse_str($params, $params2);
			$params = $params2;
		}

		$params['table'] = $this -> table;

		if (!isset($params['group_by'])) {
			//$params['group_by'] = 'shiping_country';
		}

		// d($params);
		return get($params);

	}

}
