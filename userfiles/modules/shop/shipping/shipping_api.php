<?

$ns = $config['ns'];
api_expose('shipping_api');
 
class shipping_api {

	// singleton instance
	private  static $here;
	private  static $modules_list;

	// private constructor function
	// to prevent external instantiation
	function __construct() {
		$this -> here = dirname(__FILE__).DS.'gateways'.DS;;
  $here = $this -> here;
  
  
 
  
 $this -> modules_list = modules_list("cache_group=modules/global&dir_name={$here}");
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
	function get_active() {
$active = array();
		 $m = $this->modules_list;
		 foreach($m as $item){
			 if(option_get('shipping_gw_'.$item['module'], 'shipping') == 'y'){
				$active [] =  $item; 
			 }
		 }
 return $active;
	}
	
	

	
	

	function get($params = false) {

		 return $this->modules_list;

	}
	
	function delete($data) {

	$adm = is_admin();
	if ($adm == false) {
		error('Error: not logged in as admin.');
	}

	if (isset($data['id'])) {
		$c_id = intval($data['id']);
		db_delete_by_id($this -> table, $c_id);

		//d($c_id);
	}
}
	function reorder($data) {

    $adm = is_admin();
    if ($adm == false) {
        error('Error: not logged in as admin.');
    }
 
	$table =  $this -> table;

    
    foreach ($data as $value) {
        if (is_arr($value)) {
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
