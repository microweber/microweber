<?php
function get_modules($options = false) {
	// p($options);
	$args = func_get_args ();
	$function_cache_id = '';
	foreach ( $args as $k => $v ) {
		
		$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
	}
	
	$cache_id = $function_cache_id = __FUNCTION__ . crc32 ( $function_cache_id );
	
	$cache_group = 'modules';
	
	$cache_content = cache_get_content ( $cache_id, $cache_group );
	
	if (($cache_content) != false) {
		
		  return $cache_content;
	}
	
	if (isset ( $options ['glob'] )) {
		$glob_patern = $options ['glob'];
	} else {
		$glob_patern = '*config.php';
	}
	
	if (isset ( $options ['dir_name'] )) {
		$dir_name = $options ['dir_name'];
	} else {
		$dir_name = normalize_path ( MODULES_DIR );
	}
	
	$dir = rglob ( $glob_patern, 0, $dir_name );
	
	if (! empty ( $dir )) {
		$configs = array ();
		foreach ( $dir as $key => $value ) {
			$skip_module = false;
			if (isset ( $options ['skip_admin'] ) and $options ['skip_admin'] == true) {
				// p($value);
				if (strstr ( $value, 'admin' )) {
					$skip_module = true;
				}
			}
			
			if ($skip_module == false) {
				
				$config = array ();
				$value = normalize_path ( $value, false );
				$value_fn = $mod_name = str_replace ( '_config.php', '', $value );
				$value_fn = $mod_name = str_replace ( 'config.php', '', $value_fn );
				$value_fn = $mod_name = str_replace ( 'index.php', '', $value_fn );
				$value_fn = str_replace ( $dir_name, '', $value_fn );
				
				$value_fn = reduce_double_slashes ( $value_fn );
				
				$try_icon = $mod_name . '.png';
				$def_icon = MODULES_DIR . 'default.png';
				
				ob_start ();
				include ($value);
				 
				$content = ob_get_contents ();
				ob_end_clean ();
				
				 
				
			
				
				
				
				$config ['module'] = $value_fn . '';
				$config ['module_base'] = str_replace ( 'admin/', '', $value_fn );
				
				if (is_file ( $try_icon )) {
					// p($try_icon);
					$config ['icon'] = pathToURL ( $try_icon );
				} else {
					$config ['icon'] = pathToURL ( $def_icon );
				}
				
				$mmd5 = crc32 ( $config ['module'] );
				$check_if_uninstalled = MODULES_DIR . '_system/' . $mmd5 . '.php';
				if (is_file ( $check_if_uninstalled )) {
					$config ['uninstalled'] = true;
					$config ['installed'] = false;
				} else {
					$config ['uninstalled'] = false;
					$config ['installed'] = true;
				}
				
				if (isset ( $options ['ui'] ) and $options ['ui'] == true) {
					if ($config ['ui'] == false) {
						$skip_module = true;
					}
				}
				
				if ($skip_module == false) {
					$configs [] = $config;
				}
			}
			
			// p ( $value );
		}
		$cfg_ordered = array ();
		$cfg_ordered2 = array ();
		$cfg = $configs;
		foreach ( $cfg as $k => $item ) {
			if (isset ( $item ['position'] )) {
				$cfg_ordered2 [$item ['position']] [] = $item;
				unset ( $cfg [$k] );
			}
		}
		ksort ( $cfg_ordered2 );
		foreach ( $cfg_ordered2 as $k => $item ) {
			foreach ( $item as $ite ) {
				$cfg_ordered [] = $ite;
			}
		}
		
		$c2 = array_merge ( $cfg_ordered, $cfg );
		
		cache_save ( $c2, $function_cache_id, $cache_group );
		
		return $c2;
	}
}
function get_elements($options = array()) {
	//$options ['glob'] = '*.php';
	$options ['dir_name'] = normalize_path ( ELEMENTS_DIR );
	
	return get_modules ( $options );
	
	$args = func_get_args ();
	$function_cache_id = '';
	foreach ( $args as $k => $v ) {
		
		$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
	}
	
	$cache_id = $function_cache_id = __FUNCTION__ . crc32 ( $function_cache_id );
	
	$cache_group = 'elements';
	
	$cache_content = cache_get_content ( $cache_id, $cache_group );
	
	if (($cache_content) != false) {
		
		return $cache_content;
	}
	
	$dir_name = normalize_path ( ELEMENTS_DIR );
	$a1 = array ();
	$dirs = array_filter ( glob ( $dir_name . '*' ), 'is_dir' );
	foreach ( $dirs as $dir ) {
		$a1 [] = basename ( $dir );
	}
	cache_save ( $a1, $function_cache_id, $cache_group );
	
	return $a1;
}
function load_module($module_name, $attrs = array()) {
	$function_cache_id = false;
	$args = func_get_args ();
	foreach ( $args as $k => $v ) {
		$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
	}
	$function_cache_id = __FUNCTION__ . crc32 ( $function_cache_id );
	$cache_content = 'CACHE_LOAD_MODULE_' . $function_cache_id;
	
	if (! defined ( $cache_content )) {
	} else {
		
		// p((constant($cache_content)));
		return (constant ( $cache_content ));
	}
	$custom_view = false;
	if (isset ( $attrs ['view'] )) {
		
		$custom_view = $attrs ['view'];
		$custom_view = trim ( $custom_view );
		$custom_view = str_replace ( '\\', '/', $custom_view );
		$custom_view = str_replace ( '..', '', $custom_view );
	}
	
	// $CI = get_instance();
	$module_name = trim ( $module_name );
	$module_name = str_replace ( '\\', '/', $module_name );
	$module_name = str_replace ( '..', '', $module_name );
	// prevent hack of the directory
	$module_name = reduce_double_slashes ( $module_name );
	
	$module_in_template_dir = ACTIVE_TEMPLATE_DIR . 'modules/' . $module_name . '';
	$module_in_template_dir = normalize_path ( $module_in_template_dir, 1 );
	$module_in_template_file = ACTIVE_TEMPLATE_DIR . 'modules/' . $module_name . '.php';
	$module_in_template_file = normalize_path ( $module_in_template_file, false );
	
	$try_file1 = false;
	
	if (is_dir ( $module_in_template_dir )) {
		$mod_d = $module_in_template_dir;
		$mod_d1 = normalize_path ( $mod_d, 1 );
		$try_file1 = $mod_d1 . 'index.php';
	} elseif (is_file ( $module_in_template_file )) {
		$try_file1 = $module_in_template_file;
	} else {
		
		$module_in_default_dir = MODULES_DIR . $module_name . '';
		$module_in_default_dir = normalize_path ( $module_in_default_dir, 1 );
		// d($module_in_default_dir);
		$module_in_default_file = MODULES_DIR . $module_name . '.php';
		$module_in_default_file_custom_view = MODULES_DIR . $module_name . '_' . $custom_view . '.php';
		
		$module_in_default_file = normalize_path ( $module_in_default_file, false );
		
		if (is_dir ( $module_in_default_dir )) {
			
			$mod_d1 = normalize_path ( $module_in_default_dir, 1 );
			
			if ($custom_view == true) {
				$try_file1 = $mod_d1 . trim ( $custom_view ) . '.php';
			} else {
				$try_file1 = $mod_d1 . 'index.php';
			}
		} elseif (is_file ( $module_in_default_file )) {
			
			if ($custom_view == true and is_file ( $module_in_default_file_custom_view )) {
				$try_file1 = $module_in_default_file_custom_view;
			} else {
				
				$try_file1 = $module_in_default_file;
			}
		}
	}
	//
	
	if (isset ( $try_file1 ) != false and $try_file1 != false and is_file ( $try_file1 )) {
		
		$config ['path_to_module'] = normalize_path ( (dirname ( $try_file1 )) . '/', true );
		$config ['url_to_module'] = pathToURL ( $config ['path_to_module'] ) . '/';
		
		$l1 = new View ( $try_file1 );
		$l1->config = $config;
		$l1->params = $attrs;
		
		// $l->set ( $this );
		$module_file = $l1->__toString ();
		
		// $CI -> load -> vars($c);
		
		// $module_file = $CI -> load -> file($try_file1, true);
		
		if (! defined ( $cache_content )) {
			define ( $cache_content, $module_file );
		}
		
		return $module_file;
	} else {
		define ( $cache_content, FALSE );
		
		return false;
	}
}
