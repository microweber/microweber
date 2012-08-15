<?php

function option_get($key, $return_full = false, $orderby = false, $option_group = false){
	return get_option($key, $return_full, $orderby, $option_group);
}
function get_option($key, $return_full = false, $orderby = false, $option_group = false) {
	$function_cache_id = false;
	
	$args = func_get_args ();
	
	foreach ( $args as $k => $v ) {
		
		$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
	}
	
	$function_cache_id = __FUNCTION__ . crc32 ( $function_cache_id );
	
	$cache_content = cache_get_content ( $function_cache_id, $cache_group = 'options' );
	if (($cache_content) == '--false--') {
		return false;
	}
	// $cache_content = false;
	if (($cache_content) != false) {
		
		return $cache_content;
	} else {
		
		$table = c ( 'db_tables' ); // ->'table_options';
		$table = $table ['table_options'];
		
		if ($orderby == false) {
			
			$orderby [0] = 'created_on';
			
			$orderby [1] = 'DESC';
		}
		
		$data = array ();
		if (is_array ( $key )) {
			$data = $key;
		} else {
			$data ['option_key'] = $key;
		}
		
		if ($option_group != false) {
			$data ['option_group'] = $option_group;
		}
		
		$get = db_get ( $table, $data,$cache_group = 'options' );
		
		if (! empty ( $get )) {
			
			if ($return_full == false) {
				
				$get = $get [0] ['option_value'];
				
				cache_store_data ( $get, $function_cache_id, $cache_group = 'options' );
				
				return $get;
			} else {
				
				$get = $get [0];
				
				cache_store_data ( $get, $function_cache_id, $cache_group = 'options' );
				
				return $get;
			}
		} else {
			cache_store_data ( '--false--', $function_cache_id, $cache_group = 'options' );
			
			return FALSE;
		}
	}
}