<?
$mw_site_url = "http://api.microweber.net/";
define('MW_BARE_BONES', 1);
include('../../index.php');
if (isset($_GET['api_function'])) {
	$method_name = trim($_GET['api_function']);
	if (isset($_REQUEST['api_function'])) {
		unset($_REQUEST['api_function']);
	}
	$mw_update_server = new mw_update_server();
	if (method_exists($mw_update_server, $method_name)) {
		$result = $mw_update_server -> $method_name($_REQUEST);
		if($result != false){
			$result = replace_site_vars_back($result);
				print json_encode($result);
		}
 	}

}

class mw_update_server {

	function debug($method, $params) {
		print "Called: <b>" . $method . '</b> with params: ';
		print_r($params);
	}
	function download($params = false){
		
		
	}
 
	function get_content($params = false){
		if($params != false){
		$params = parse_params($params);
		} else {
		$params = array();	
		}
		
		
		
		$here = dirname(__FILE__).DS;
		
		
		$params['is_active'] = 'y';
		$params['content_type'] = 'post';
		
		$data = get_content($params);
		if(!empty($data)){
		 foreach($data as $k=> $item){
			 $cf = get_custom_fields("for=content&for_id=".$item['id']);
			 if($cf != false){
				 $data[$k]['custom_fields'] =  $cf ;
				 
				 
				  $data[$k]['name'] =  $data[$k]['title'];
				 
				 
				 foreach($cf as $cfk => $custom_field){
					
					if(trim($cfk) == 'folder'){
						
						 
						
						
						 $data[$k]['module'] =  str_replace_once('modules/' ,'' ,$custom_field );
						$list_recources_params = array();
						$list_recources_params['dir_name'] = $here.$custom_field; //get modules in dir
						$list_recources_params['skip_save'] = 1; //if true skips module install
												$list_recources_params['skip_cache'] = 1;  

						$list_recources_params['cache_group'] = 'modules/global'; // allows custom cache group
					 
						 $list_recources_params = modules_list($list_recources_params);
						 
						 $data[$k]['resources'] =  $list_recources_params ;
				 
					}
					
					 
					 
				 }
				 
				 
			 }
			 
			  $iu = get_picture($item['id'], $for = 'post', $full = false);
		 
			if($iu != false){
				  $data[$k]['image'] = $data[$k]['icon'] = $iu;
			} else {
				 $data[$k]['image'] = $data[$k]['icon'] = false;
			}
		 }
		}
 		return $data;
	
}
	

	function get_modules($params = false) {
		
		if($params != false){
		$params = parse_params($params);
		} else {
		$params = array();	
		}
	 
		 $params['parent'] = 2;
	$data = $this->get_content($params);
	return $data;
	}

	 

}
