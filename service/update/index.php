<?
$mw_site_url = "http://api.microweber.net/";
define('MW_BARE_BONES', 1);
include ('../../index.php');


if (isset($_REQUEST['base64'])) {
	
	$_REQUEST = unserialize(base64_decode($_REQUEST['base64']));
 
}




if (isset($_REQUEST['api_function'])) {
	$method_name = trim($_REQUEST['api_function']);
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
		$this -> modules_dir =  $this -> here . 'mw_git/Microweber' . DS. 'userfiles/modules' . DS;
		$this -> layouts_dir =  $this -> here . 'mw_git/Microweber' . DS. 'userfiles/elements' . DS;
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
		$list_recources_params['dir_name'] = $this -> modules_dir;
		$list_recources_params['skip_save'] = true;
		//$list_recources_params['is_elements'] = false;
		//if true skips module install
		$list_recources_params['skip_cache'] = true;
		 $local_res = modules_list($list_recources_params);
		$to_return = array();
		
		
		
		//$local_res = false;
		// $to_return['orig_params'] = $params;
		
		
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
	 	 // $to_return[] = $local_modules;
 		$updates_data = array();
		if (isset($params['modules'])) {
			foreach ($params['modules'] as $module) {
				foreach ($local_modules as $local_module) {
					if (isset($module['version']) and isset($module['module']) and isset($local_module['module_base'])) {
						 	$local_module['module'] = str_replace($this -> modules_dir, '',$local_module['module']); 
						 	 $local_module['module'] = rtrim($local_module['module'], DS);
							 
						 if ($local_module['module'] == $module['module'] or $module['module'] == $local_module['module_base']) {
							if (!isset($module['version']) or trim($module['version']) == '') { 
								$module['version'] = '0.01';
							} else {
								$module['version'] =  trim($module['version']) ;
							}
							 
							  if (isset($local_module['version']) and ( floatval($local_module['version']) > floatval($module['version']))) {
								$dl_params = array();
								$dl_params['module'] =  $local_module['module'];
								 $module_download_link = $this -> get_download_link($dl_params);

							 	 $module['download_link'] = $module_download_link;
								$module['module_orig'] = $module['module'];
								$module['module_remote'] = $local_module['module'];;
								$module['module_remote_version'] = trim($local_module['version']);;

	 
								 $updates_data[] = $module;
							
							   }
						 }
					}
				}
			}
			  $to_return['modules'] = $updates_data;
		}
		
	
		$updates_data = array();
		if (isset($params['elements'])) {
			
			
			
			$local_modules = array();
				 
			$list_recources_params = array();
		$list_recources_params['dir_name'] = $this -> layouts_dir;
		$list_recources_params['skip_save'] = true;
		//$list_recources_params['is_elements'] = false;
		//if true skips module install
		$list_recources_params['skip_cache'] = true;
		 $local_modules = modules_list($list_recources_params);
			
			 
			foreach ($params['elements'] as $module) {
				foreach ($local_modules as $local_module) {
					
					
					
					if (isset($module['module']) and isset($local_module['module_base'])) {
						 	$local_module['module'] = str_replace($this -> layouts_dir, '',$local_module['module_base']); 
						 	 $local_module['module'] = rtrim($local_module['module'], DS);
							 
						 if ($local_module['module'] == $module['module']) {
							if (!isset($module['version'])) {
								$module['version'] = '0.01';
							} else {
								$module['version'] =  trim($module['version']) ;
							}
						 
							  if (isset($local_module['version']) and floatval($local_module['version']) > floatval($module['version'])) {
								$dl_params = array();
								$dl_params['module'] =  $local_module['module'];
								 //$module_download_link = $this -> get_download_link($dl_params);

							 	// $module['download_link'] = $module_download_link;
								$module['module_orig'] = $module['module'];
								$module['module_local'] = $local_module['module'];;

	 
								 $updates_data[] = $module;
							
							   }
						 }
					}
				}
			}
			  $to_return['elements'] = $updates_data;
		}
		
		
				if (isset($params['mw_version'])) {
		 					$ver = $version_last = $this->get_latest_core_version();
						$to_return['version'] = trim($ver );
							if (isset( $ver) and floatval( $ver) > floatval($params['mw_version'])) {
								$dl_params = array();
								$dl_params['core_update'] = 1;
								$module_download_link = $this -> get_download_link($dl_params);
								if(isset($module_download_link['core_update'])){
									$to_return['core_update'] = $module_download_link['core_update'];
 								}
				}
			
		}
		
		
		$updates_data = array();
		if (isset($params['module_templates'])) {
			$module_templates = $params['module_templates'];
			
			foreach($module_templates as $k => $module_templates_all){
				
				$options = array();
			$options['no_cache'] = 1;
			//$options['for_modules'] = 1;
			$options['path'] = normalize_path($this -> modules_dir.DS.$k.DS.'templates');
			$module_templates_for_this = layouts_list($options);
				
				
				//$module_templates_for_this = module_templates($k);
							if(isarr($module_templates_for_this)){
								
								foreach($module_templates_all as $module_template){
								
								
									
								//$module_templates_for_this_diff = array_diff($module_templates,$module_templates_for_this);
								foreach($module_templates_for_this as $local_module){
									
								if(isset($local_module['layout_file']) and isset($module_template['layout_file']) and isset($local_module['name'])){
									if(!in_array($local_module['layout_file'],$updates_data)){
										$loc1 = normalize_path($local_module['layout_file'], false);
										$loc2 = normalize_path($module_template['layout_file'], false);
									 
									
								 if ((!isset($module_template['version']) and isset($local_module['version'])) or (isset($local_module['version']) and floatval($local_module['version']) > floatval($module_template['version']))) {
											$dl_params = array();
											$dl_params['name'] =  $local_module['name'];
									     	$dl_params['module'] = $k;

											$dl_params['layout_file'] = $local_module['layout_file'];
											$dl_params['version'] = $local_module['version'];
										
											//$dl_params['data'] = $local_module;
			
				 
											 $updates_data[$local_module['layout_file']] = $dl_params;
										
										   }
								
								}
								
							}
								
								
								
								
									 
							}
							//
						}
						
				 }
				 
				 
				 
						
			}
			
			
			  $to_return['module_templates'] = $updates_data;
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
		
		$all_params = $params;
		$to_return = array();
		if (isset($params['module'])) {
			$to_return['modules'] = array();
			$params['module'] = str_replace('..', '', $params['module']);
			$list_recources_params = array();
			$list_recources_params['dir_name'] = $this -> modules_dir . DS . $params['module'] . DS;
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
						
							$locations = array();
							$locations[] = $mod_base;   
					 		$fileTime = date("D, d M Y H:i:s T"); 
								
								$zip = new \mw\utils\zip($filename);
								
								$zip->setZipFile($filename); 
								$zip->setComment("Microweber module update.\nCreated on " . date('l jS \of F Y h:i:s A')); 
 						
							foreach($locations as $location){
								$rel_d = str_replace($this -> repo_dir, '', $location);
								if(is_dir($location)){
									$zip->addDirectoryContent($location,$rel_d,true); 
								} else if(is_file($location)){
									 $zip->addFile(file_get_contents($location),$rel_d, filectime($location)); 
								}
					  
						}
						$zip1 = $zip->finalize(); 
						
					 
						
						
						
						}
						$to_return['modules'][$mod_rel_name] = dir2url($filename);
						
					}

				}
			}
		}
		
		if (isset($all_params['element'])) {
			$to_return['elements'] = array();
			$params['element'] = str_replace('..', '', $params['element']);
			$list_recources_params = array();
			$list_recources_params['dir_name'] = $this ->layouts_dir;;
			//get modules in dir
			$list_recources_params['skip_save'] = 1;
			//if true skips module install
			$list_recources_params['skip_cache'] = 1;

			 $params = modules_list($list_recources_params);
			 
			if (!empty($params)) {
				foreach ($params as $conf) {
					if (isset($conf['element'])) {
						$mod_base = $this ->layouts_dir.$conf['element'];
						$mod_base = normalize_path($mod_base, 1);
						$zip_name = $mod_rel_name = str_replace($this ->layouts_dir, '', $mod_base);
						if (!isset($conf['version'])) {
							$conf['version'] = '0.01';
						}
						$zip_name = str_replace(DS, '-', $zip_name);
						$zip_name = trim($zip_name . $conf['version']) . ".zip";
						$filename = $this -> downloads_dir . $zip_name;
						
						
						d($filename);
						
						
					/*	if(!is_file($filename)){
						
							$locations = array();
							$locations[] = $mod_base;   
					 		$fileTime = date("D, d M Y H:i:s T"); 
								
								$zip = new \mw\utils\zip($filename);
								
								$zip->setZipFile($filename); 
								$zip->setComment("Microweber module update.\nCreated on " . date('l jS \of F Y h:i:s A')); 
 						
							foreach($locations as $location){
								$rel_d = str_replace($this -> repo_dir, '', $location);
								if(is_dir($location)){
									$zip->addDirectoryContent($location,$rel_d,true); 
								} else if(is_file($location)){
									 $zip->addFile(file_get_contents($location),$rel_d, filectime($location)); 
								}
					  
						}
						$zip1 = $zip->finalize(); 
						
					
						
						
						
						} */
						$to_return['elements'][$mod_rel_name] = dir2url($filename);
						
					}

				}
			}
		}
		
		
		
		
		
		if (isset($params['core_update'])) {
				$ver = $this->get_latest_core_version();
				 
 						 
						$zip_name = trim('microweber-' . $ver) . ".zip";
						$filename = $this -> downloads_dir . $zip_name;
						//if(!is_file($filename)){
							
							$locations = array();
							$locations[] = $this -> repo_dir.'application'.DS;
							$locations[] = $this -> repo_dir.'userfiles/elements'.DS;
							$locations[] = $this -> repo_dir.'userfiles/templates/default'.DS;
							//$locations[] = $this -> repo_dir.'userfiles/modules'.DS;

							 // A 
							 $locations[] = $this -> repo_dir.'userfiles/modules/audio'.DS;
							 $locations[] = $this -> repo_dir.'userfiles/modules/admin'.DS;
							 
							 // B 
							 $locations[] = $this -> repo_dir.'userfiles/modules/btn'.DS;

							 // C
							$locations[] = $this -> repo_dir.'userfiles/modules/content'.DS;
							$locations[] = $this -> repo_dir.'userfiles/modules/categories'.DS;
							$locations[] = $this -> repo_dir.'userfiles/modules/comments'.DS;
							$locations[] = $this -> repo_dir.'userfiles/modules/contact_form'.DS;
							$locations[] = $this -> repo_dir.'userfiles/modules/custom_fields'.DS;
							 
							 // D
						 	
							
							 // E
							$locations[] = $this -> repo_dir.'userfiles/modules/embed'.DS;
							 
							// F
							$locations[] = $this -> repo_dir.'userfiles/modules/files'.DS;
							$locations[] = $this -> repo_dir.'userfiles/modules/forms'.DS;
							$locations[] = $this -> repo_dir.'userfiles/modules/free'.DS;
							
							//G
							$locations[] = $this -> repo_dir.'userfiles/modules/google_maps'.DS;
						
							
							//H
							$locations[] = $this -> repo_dir.'userfiles/modules/help'.DS;
							$locations[] = $this -> repo_dir.'userfiles/modules/highlight_code'.DS;


							//I
							$locations[] = $this -> repo_dir.'userfiles/modules/ip2country'.DS;
							
							//L
							$locations[] = $this -> repo_dir.'userfiles/modules/layout'.DS;
							
							
							// M 
							$locations[] = $this -> repo_dir.'userfiles/modules/media'.DS;
							$locations[] = $this -> repo_dir.'userfiles/modules/mics'.DS;
							
							//N
							$locations[] = $this -> repo_dir.'userfiles/modules/nav'.DS;
							$locations[] = $this -> repo_dir.'userfiles/modules/newsletter'.DS;
							
							
							//O
							$locations[] = $this -> repo_dir.'userfiles/modules/options'.DS;
							
							
							//P
							$locations[] = $this -> repo_dir.'userfiles/modules/pictures'.DS;
							$locations[] = $this -> repo_dir.'userfiles/modules/posts'.DS;
							$locations[] = $this -> repo_dir.'userfiles/modules/pages'.DS;
							
							
							//S
							$locations[] = $this -> repo_dir.'userfiles/modules/settings'.DS;
							$locations[] = $this -> repo_dir.'userfiles/modules/shop'.DS;
							$locations[] = $this -> repo_dir.'userfiles/modules/site_stats'.DS;
							$locations[] = $this -> repo_dir.'userfiles/modules/subscribe_form'.DS;
							$locations[] = $this -> repo_dir.'userfiles/modules/survey'.DS;
							
							 
						 
							
							// T
							$locations[] = $this -> repo_dir.'userfiles/modules/text'.DS;
							$locations[] = $this -> repo_dir.'userfiles/modules/title'.DS; 


 
 
							// U
							$locations[] = $this -> repo_dir.'userfiles/modules/users'.DS;
							$locations[] = $this -> repo_dir.'userfiles/modules/updates'.DS;
							$locations[] = $this -> repo_dir.'userfiles/modules/user_profile'.DS;
							$locations[] = $this -> repo_dir.'userfiles/modules/user_search'.DS;
							$locations[] = $this -> repo_dir.'userfiles/modules/users_list'.DS;
							
							// V
							$locations[] = $this -> repo_dir.'userfiles/modules/video'.DS;
							
 							$locations[] = $this -> repo_dir.'userfiles/modules/default.php';
							$locations[] = $this -> repo_dir.'userfiles/modules/default.png';
							$locations[] = $this -> repo_dir.'userfiles/modules/non_existing.php';

							 
							$locations[] = $this -> repo_dir.'index.php';
							$locations[] = $this -> repo_dir.'bootstrap.php';
							 $locations[] = $this -> repo_dir.'.htaccess';   
					 		$fileTime = date("D, d M Y H:i:s T"); 
								
								$zip = new \mw\utils\zip($filename);
								
								$zip->setZipFile($filename); 
								$zip->setComment("Microweber core version ". $ver .".\nCreated on " . date('l jS \of F Y h:i:s A')); 
 

						 // $zip = new \mw\utils\zip();
						
						foreach($locations as $location){
							$rel_d = str_replace($this -> repo_dir, '', $location);
							
							
							if(is_dir($location)){
								$zip->addDirectoryContent($location,$rel_d,true); 
							} else if(is_file($location)){
								 $zip->addFile(file_get_contents($location),$rel_d, filectime($location)); 
								
							}
					  
						}
						$zip1 = $zip->finalize() ; 
						
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
