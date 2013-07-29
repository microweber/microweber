<?php
api_hook('shop/shipping/gateways/country/shipping_to_country/test', 'shop/shipping/gateways/country/shipping_to_country/test2');

// print('shop/shipping/gateways/country/shipping_to_country/test'. 'shop/shipping/gateways/country/shipping_to_country/test2');
api_expose('shop/shipping/gateways/country/shipping_to_country/save');
api_expose('shop/shipping/gateways/country/shipping_to_country/set');
api_expose('shop/shipping/gateways/country/shipping_to_country/get');
api_expose('shop/shipping/gateways/country/shipping_to_country/delete');
 api_expose('shop/shipping/gateways/country/shipping_to_country/reorder');

class shipping_to_country {

	// singleton instance
	public $table;

	// private constructor function
	// to prevent external instantiation
	function __construct() {
		$this -> table = MW_TABLE_PREFIX . 'cart_shipping';
	}
	
	
	function test() {
	 return 'ping ';
	}
	
	function test2() {
	 return 'pong ';
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
			$check = $this ->mw('db')->get('shiping_country=' . $data['shiping_country']);
			if ($check != false and is_array($check[0]) and isset($check[0]['id'])) {
				$data['id'] = $check[0]['id'];
			}
				}
		}
		
			 
		

		$data = mw('db')->save($this -> table, $data);
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

		if (!isset($params['order_by'])) {
		 $params['order_by'] = 'position ASC';
		}
 		$params['limit']=1000;
		// d($params);
		return mw('db')->get($params);

	}
	
	function delete($data) {

	$adm = is_admin();
	if ($adm == false) {
		error('Error: not logged in as admin.'.__FILE__.__LINE__);
	}

	if (isset($data['id'])) {
		$c_id = intval($data['id']);
		db_delete_by_id($this -> table, $c_id);

		//d($c_id);
	}
	return true;
}
	
		function set($params = false) {
	 
			if(isset($params['country'])){
				$active = $this->get('fields=shiping_country,shiping_cost_max,shiping_cost,shiping_cost_above&one=1&is_active=y&shiping_country='.$params['country']);
				if(is_array($active)){
					foreach($active as $name => $val){
						 mw('user')->session_set($name, $val);
					}
				} else {
									$active_ww = $this->get('fields=shiping_country,shiping_cost_max,shiping_cost,shiping_cost_above&one=1&is_active=y&shiping_country=Worldwide');
									if(is_array($active_ww)){
										
										$active_ww['shiping_country'] = $params['country'];
										
										
										
										
					foreach($active_ww as $name => $val){
						 mw('user')->session_set($name, $val);
					}
					
					   return $active_ww;
					
				}

				}
       return $active;
			}
			

		

	}
	
	function reorder($data) {

    $adm = is_admin();
    if ($adm == false) {
        mw_error('Error: not logged in as admin.'.__FILE__.__LINE__);
    }
 
	$table =  $this -> table;

    
    foreach ($data as $value) {
        if (is_array($value)) {
            $indx = array();
            $i = 0;
            foreach ($value as $value2) {
                $indx[$i] = $value2;
                $i++;
            }

            db_update_position($table, $indx);
            return true;
            // d($indx);
        }
    }
}


}
