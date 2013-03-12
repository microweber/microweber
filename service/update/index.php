<?
$mw_site_url = "http://api.microweber.net/";
define('MW_BARE_BONES', 1);
include ('../../index.php');
if (isset($_GET['api_function'])) {
	$method_name = trim($_GET['api_function']);
	if (isset($_REQUEST['api_function'])) {
		unset($_REQUEST['api_function']);
	}
	$mw_update_server = new mw_update_server();
	if (method_exists($mw_update_server, $method_name)) {
		$result = $mw_update_server -> $method_name($_REQUEST);
		if ($result != false) {
			$result = replace_site_vars_back($result);
			print json_encode($result);
		}
	}

}

class mw_update_server {
	private $here;
	private $repo_dir;
	private $downloads_dir;
	private $modules_dir;
	function __construct() {
		$this -> here = dirname(__FILE__) . DS;

		$this -> downloads_dir = $this -> here . 'downloads' . DS;
		if (!is_dir($this -> downloads_dir)) {
			mkdir_recursive($this -> downloads_dir);
		}
		$this -> repo_dir = $this -> here . 'mw_git/Microweber' . DS;
		$this -> modules_dir = $this -> repo_dir . 'userfiles/modules' . DS;
	}


function get_latest_core_version() {
	 $to_return = false;
		 $core_file_v = $this -> repo_dir.'application/functions.php';
		 if(is_file($core_file_v)){
			 $core_file_v = file_get_contents($core_file_v);
			 $core_v = string_get_between($core_file_v,"define('MW_VERSION',",';');
			 $to_return = floatval($core_v);
			 
			 
		 }
	return $to_return;
}


	function get_latest_core_update($params = false) {
		
$ver = $this->get_latest_core_version();
 

	return $ver;	
	}
	
	
	
	function check_for_update($params) {

		$list_recources_params = array();
		$list_recources_params['dir_name'] = $this -> here . 'modules';
		$list_recources_params['skip_save'] = 1;
		//if true skips module install
		$list_recources_params['skip_cache'] = 1;
		$local_res = modules_list($list_recources_params);
		$to_return = array();
		$local_modules = array();
		foreach ($local_res as $conf) {

			if (isset($conf['module_base'])) {

				$mod_base = $conf['module_base'];

				$mod_base = normalize_path($mod_base, 1);

				$mod_rel_name = str_replace($this -> modules_dir, '', $mod_base);

				$mod_rel_name = rtrim($mod_rel_name, DS);
				$mod_rel_name = rtrim($mod_rel_name, '/');
				$conf['module_base'] = $mod_rel_name;

				if (!isset($conf['version'])) {
					$conf['version'] = '0.01';
				}
				$local_modules[] = $conf;

			}
		}
		$updates_data = array();
		if (isset($params['modules'])) {
			foreach ($params['modules'] as $module) {
				foreach ($local_modules as $local_module) {
					if (isset($module['module_base']) and isset($local_module['module_base'])) {
						if ($module['module_base'] == $local_module['module_base']) {
							if (!isset($module['version'])) {
								$module['version'] = '0.01';
							}
							if (isset($local_module['version']) and floatval($local_module['version']) > floatval($module['version'])) {
								$dl_params = array();
								$dl_params['module'] = $local_module['module_base'];
								$module_download_link = $this -> get_download_link($dl_params);

								$module['download_link'] = $module_download_link;

								$updates_data[] = $module;
							}
						}
					}
				}
			}
			$to_return['modules'] = $updates_data;
		}
		
		
				if (isset($params['mw_version'])) {
		 					$ver = $this->get_latest_core_version();
							if (isset( $ver) and floatval( $ver) > floatval($params['mw_version'])) {
								$dl_params = array();
								$dl_params['core_update'] = 1;
								$module_download_link = $this -> get_download_link($dl_params);
								$to_return['core_update'] = $module_download_link;
							 
			}
			
		}
		
		
		
		

		return $to_return;
	}

	function debug($method, $params) {
		print "Called: <b>" . $method . '</b> with params: ';
		print_r($params);
	}

	function get_download_link($params = false) {
		if ($params != false) {
			$params = parse_params($params);
		} else {
			$params = array();
		}
		$to_return = array();
		if (isset($params['module'])) {
			$to_return['modules'] = array();
			$params['module'] = str_replace('..', '', $params['module']);
			$list_recources_params = array();
			$list_recources_params['dir_name'] = $this -> here . 'modules' . DS . $params['module'] . DS;
			//get modules in dir
			$list_recources_params['skip_save'] = 1;
			//if true skips module install
			$list_recources_params['skip_cache'] = 1;

			$params = modules_list($list_recources_params);
			if (!empty($params)) {
				foreach ($params as $conf) {
					if (isset($conf['module_base'])) {
						$mod_base = $conf['module_base'];
						$mod_base = normalize_path($mod_base, 1);
						$zip_name = $mod_rel_name = str_replace($this -> modules_dir, '', $mod_base);
						if (!isset($conf['version'])) {
							$conf['version'] = '0.01';
						}
						$zip_name = str_replace(DS, '-', $zip_name);
						$zip_name = trim($zip_name . $conf['version']) . ".zip";
						$filename = $this -> downloads_dir . $zip_name;
						if(!is_file($filename)){
						$zip = new \mw\utils\zip();
						$result = $zip -> compress($mod_base, $filename);
						}
						$to_return['modules'][$mod_rel_name] = dir2url($filename);
						
					}

				}
			}
		}
		
		if (isset($params['core_update'])) {
				$ver = $this->get_latest_core_version();
				 
 						 
						$zip_name = trim('microweber-' . $ver) . ".zip";
						$filename = $this -> downloads_dir . $zip_name;
						if(!is_file($filename)){
							
							$locations = array();
							$locations[] = $this -> repo_dir.'application'.DS;
							$locations[] = $this -> repo_dir.'userfiles/modules/admin'.DS;
							 
							
						 $zip = new \mw\utils\zip();
						
						foreach($locations as $location){
							
							$files = rglob($location.'.*');
							foreach($files as $file){
								
								$fileb=basename($file);
								if($fileb != '.' and $fileb != '..'){
									$result = $zip -> compress($file, $filename);
									d($result );d($file );
								}
							//	d($fileb);
								//
							}
						
						//
						
						}
						
						
						}
						$to_return['core_update']  = dir2url($filename);

			
		}
		
		

		return $to_return;
	}

	function get_content($params = false) {
		if ($params != false) {
			$params = parse_params($params);
		} else {
			$params = array();
		}

		$here = $this -> here;

		$params['is_active'] = 'y';
		$params['content_type'] = 'post';

		$data = get_content($params);
		if (!empty($data)) {
			foreach ($data as $k => $item) {
				$cf = get_custom_fields("for=content&for_id=" . $item['id']);
				if ($cf != false) {
					$data[$k]['custom_fields'] = $cf;

					$data[$k]['name'] = $data[$k]['title'];

					foreach ($cf as $cfk => $custom_field) {

						if (trim($cfk) == 'folder') {

							$data[$k]['module'] = str_replace_once('modules/', '', $custom_field);
							$list_recources_params = array();
							$list_recources_params['dir_name'] = $here . $custom_field;
							//get modules in dir
							$list_recources_params['skip_save'] = 1;
							//if true skips module install
							$list_recources_params['skip_cache'] = 1;

							$list_recources_params['cache_group'] = 'modules/global';
							// allows custom cache group

							$list_recources_params = modules_list($list_recources_params);

							$data[$k]['resources'] = $list_recources_params;

						}

					}

				}

				$iu = get_picture($item['id'], $for = 'post', $full = false);

				if ($iu != false) {
					$data[$k]['image'] = $data[$k]['icon'] = $iu;
				} else {
					$data[$k]['image'] = $data[$k]['icon'] = false;
				}
			}
		}
		return $data;

	}

	function get_modules($params = false) {

		if ($params != false) {
			$params = parse_params($params);
		} else {
			$params = array();
		}

		$params['parent'] = 2;
		$data = $this -> get_content($params);
		return $data;
	}

}
