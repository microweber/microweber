<?php
function old_get_menu_id($name) {
	$name = trim ( $name );
	
	if ($name == '') {
		return false;
	}
	$function_cache_id = false;
	$args = func_get_args ();
	foreach ( $args as $k => $v ) {
		
		$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
	}
	
	$function_cache_id = __FUNCTION__ . crc32 ( $function_cache_id );
	
	$cache_content = cache_get_content ( $function_cache_id, 'menus' );
	
	if (($cache_content) != false) {
		
		return $cache_content;
	}
	
	$data = false;
	
	$data ['menu_unique_id'] = $name;
	
	$data = get_menu ( $data );
	
	// $data = object_2_array ( $data );
	
	$data = $data [0];
	
	$id = $data ['id'];
	$id = intval ( $id );
	if ($id == 0) {
		$sav = array ();
		$sav ['menu_unique_id'] = $name;
		$sav ['item_type'] = 'menu';
		$id = save_menu ( $sav );
	}
	cache_store_data ( $id, $function_cache_id, $cache_group = 'menus' );
	
	return $id;
}
function old_save_menu($data) {
	$menu = new menu ();
	foreach ( $data as $k => $v ) {
		$menu->$k = $v;
	}
	$menu = $menu->save ();
}
function old_get_menu($params = array()) {
	$table = c ( 'db_tables' );
	$table = $table ['table_menus'];
	return db_get ( $table, $params, 'menus' );
}
function old_menu_tree($menu_id, $maxdepth = false) {
	$args = func_get_args ();
	$function_cache_id = '';
	foreach ( $args as $k => $v ) {
		
		$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
	}
	$function_cache_id = __FUNCTION__ . crc32 ( $function_cache_id );
	$cache_content = cache_get_content ( $function_cache_id, 'menus' );
	if (($cache_content) != false) {
		// p($cache_content);
		return $cache_content;
	}
	$passed_ids = array ();
	
  $cms_db_tables 	 = c ( 'db_tables' ); ;
	
	$params = array ();
	$params ['item_parent'] = $menu_id;
	// $params ['item_parent<>'] = $menu_id;
	
	$params_order = array ();
	$params_order ['position'] = 'ASC';
	
	 
	$table_menus = MW_TABLE_PREFIX . 'menus';
	
	$sql = "SELECT id from {$table_menus}
	where item_parent=$menu_id
	and item_parent<>{$table_menus}.id
	order by position ASC ";
	// p ( $sql );
	  $q = db_query ( $sql, __FUNCTION__ . crc32 ( $sql ), 'menus' );
	
	// $data = $q;
	if (empty ( $data )) {
		return false;
	}
	// $to_print = '<ul class="menu" id="menu_item_' .$menu_id . '">';
	$to_print = '<ul class="menu menu_' . $menu_id . '">';
	$cur_depth = 0;
	foreach ( $data as $item ) {
		$full_item = get_menu_items ( false, $item ['id'] );
		$full_item = $full_item [0];
		//
		
		$active_class = '';
		if ($full_item ['content_id'] == PAGE_ID) {
			$active_class = ' active';
		}
		if ($full_item ['content_id'] == POST_ID) {
			$active_class = ' active';
		}
		
		if ($full_item ['content_id'] == MAIN_PAGE_ID) {
			$active_class = ' active';
		}
		$thetitle = get_content_by_id ( $full_item ['content_id'] );
		$active_class_titl = url_title ( strtolower ( $thetitle ['title'] ) );
		
		if ($full_item ['item_title'] == '') {
			$full_item ['item_title'] = $full_item ['id'];
		}
		
		$full_item ['the_url'] = page_link ( $full_item ['content_id'] );
		
		$to_print .= '<li class="menu_element ' . $active_class_titl . ' ' . $active_class . '" id="menu_item_' . $item ['id'] . '"><a class="menu_element_link ' . $active_class_titl . ' ' . $active_class . '" href="' . $full_item ['the_url'] . '">' . $full_item ['item_title'] . '</a></li>';
		
		if (in_array ( $item ['id'], $passed_ids ) == false) {
			
			if ($maxdepth == false) {
				$test1 = menu_tree ( $item ['id'] );
				if (strval ( $test1 ) != '') {
					$to_print .= strval ( $test1 );
				}
			} else {
				
				if (($maxdepth != false) and ($cur_depth <= $maxdepth)) {
					$test1 = menu_tree ( $item ['id'] );
					if (strval ( $test1 ) != '') {
						$to_print .= strval ( $test1 );
					}
				}
			}
		}
		
		$passed_ids [] = $item ['id'];
		// }
		// }
		$cur_depth ++;
	}
	
	// print "[[ $time ]]seconds\n";
	$to_print .= '</ul>';
	cache_store_data ( $to_print, $function_cache_id, 'menus' );
	return $to_print;
}
function old_get_menu_items($menu_id = false, $id = false) {
	$args = func_get_args ();
	$function_cache_id = '';
	foreach ( $args as $k => $v ) {
		
		$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
	}
	
	$function_cache_id = __FUNCTION__ . crc32 ( $function_cache_id );
	
	$cache_content = cache_get_content ( $function_cache_id, 'menus' );
	
	if (($cache_content) != false) {
		
		return $cache_content;
	}
	
	$cms_db_tables = c ( 'db_tables' );
	
	$table_menus = MW_TABLE_PREFIX . 'menus';
	
	$table_content = MW_TABLE_PREFIX . 'content';
	
	$data = false;
	if ($id == false) {
		$data ['item_parent'] = $menu_id;
		$data ['item_type'] = 'menu_item';
	} else {
		$data ['id'] = $id;
	}
	
	$orderby = array ();
	
	$orderby [0] = 'position';
	
	$orderby [1] = 'asc';
	
	$data = get_menu ( $data, $orderby );
	
	$return = array ();
	
	foreach ( $data as $item ) {
		
		if (intval ( $item ['content_id'] ) == 0) {
		}
		
		// $menu_item = $item;
		
		if ($item ['content_id'] != 0) {
			
			$check_if_this_is_page_or_post = array ();
			
			$check_if_this_is_page_or_post ['id'] = $item ['content_id'];
			
			$check_if_this_is_page_or_post ['only_those_fields'] = array (
					'content_type',
					'id' 
			);
			
			// //
			
			$check_if_this_is_page_or_post = get_content ( $check_if_this_is_page_or_post, $orderby = false, $limit = false, $count_only = false, $short_data = true );
			
			$check_if_this_is_page_or_post = $check_if_this_is_page_or_post [0] ['content_type'];
			
			if ($check_if_this_is_page_or_post == 'post') {
				
				$url = post_link ( $item ['content_id'] );
			}
			
			if ($check_if_this_is_page_or_post == 'page') {
				
				$url = page_link ( $item ['content_id'] );
			}
			
			if (intval ( $item ['content_id'] ) != 0) {
				
				if (strval ( $item ['item_title'] ) == '') {
					
					$q = " select title from $table_content where id={$item ['content_id']}  limit 0,1 ";
					
					$q = db_query ( $q, __FUNCTION__ . crc32 ( q ), 'content/' . $item ['content_id'] );
					
					$fix_title = $q [0] ['title'];
					
					// print $fix_title;
					
					$item ['item_title'] = $fix_title;
					
					$q = "update $table_menus set item_title='$fix_title' where id={$item['id']}";
					
					// $this->core_model->dbQ ( $q );
					
					var_Dump ( __FUNCTION__, __LINE__, $q );
					
					// exit ();
				}
			}
		} 

		elseif ($item ['taxonomy_id'] != 0) {
			
			if (strval ( $item ['item_title'] ) == '') {
				$this->load->model ( 'Taxonomy_model', 'taxonomy_model' );
				$get_taxonomy = $this->taxonomy_model->getSingleItem ( $item ['taxonomy_id'] );
				
				$fix_title = $get_taxonomy ['title'];
				
				$item ['item_title'] = $fix_title;
				
				$q = "update $table_menus set item_title='$fix_title' where id={$item['id']}";
				
				var_Dump ( __FUNCTION__, __LINE__, $q );
				$this->core_model->dbQ ( $q );
				
				// var_Dump ( $q );
				
				// exit ();
			}
			
			//
			
			$url = $this->taxonomy_model->getUrlForId ( $item ['taxonomy_id'] );
		} else {
			
			$url = trim ( $item ['menu_url'] );
		}
		
		$item ['the_url'] = $url;
		
		// $sub_items = $this->content_model->getMenuItems($item ['id']);
		
		if (! empty ( $sub_items )) {
			// $item ['submenu'] = $sub_items;
		}
		
		$return [] = $item;
	}
	
	cache_store_data ( $return, $function_cache_id, 'menus' );
	
	return $return;
}
 