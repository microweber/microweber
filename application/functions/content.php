<?php
function define_constants($content = false) {
	$page_data = false;
	if (is_array ( $content )) {
		if (isset ( $content ['id'] )) {
			
			$page = $content;
		}
	} else {
	}
	
	if (isset ( $page )) {
		if ($page ['content_type'] == "post") {
			$content = $page;
			
			$page = get_content_by_id ( $page ['content_parent'] );
			if (defined ( 'POST_ID' ) == false) {
				define ( 'POST_ID', $content ['id'] );
			}
		} else {
			$content = $page;
		}
		
		if (defined ( 'ACTIVE_PAGE_ID' ) == false) {
			
			define ( 'ACTIVE_PAGE_ID', $page ['id'] );
		}
		
		if (defined ( 'CONTENT_ID' ) == false) {
			define ( 'CONTENT_ID', $content ['id'] );
		}
		
		if (defined ( 'PAGE_ID' ) == false) {
			define ( 'PAGE_ID', $page ['id'] );
		}
		
		if (defined ( 'MAIN_PAGE_ID' ) == false) {
			define ( 'MAIN_PAGE_ID', $page ['content_parent'] );
		}
	}
	
	if (isset ( $page ) and ($page ['active_site_template']) != '' and strtolower ( $page ['active_site_template'] ) != 'default') {
		$the_active_site_template = $page ['active_site_template'];
	} else {
		$the_active_site_template = get_option ( 'curent_template' );
	}
	$the_active_site_template_dir = normalize_path ( TEMPLATEFILES . $the_active_site_template . '/' );
	
	if (defined ( 'ACTIVE_TEMPLATE_DIR' ) == false) {
		
		define ( 'ACTIVE_TEMPLATE_DIR', $the_active_site_template_dir );
	}
	
	if (defined ( 'TEMPLATE_DIR' ) == false) {
		
		define ( 'TEMPLATE_DIR', $the_active_site_template_dir );
	}
	
	$the_template_url = site_url ( 'userfiles/' . TEMPLATEFILES_DIRNAME . '/' . $the_active_site_template );
	// d($the_template_url);
	$the_template_url = $the_template_url . '/';
	if (defined ( 'TEMPLATE_URL' ) == false) {
		define ( "TEMPLATE_URL", $the_template_url );
	}
	
	if (defined ( 'USERFILES_URL' ) == false) {
		define ( "USERFILES_URL", site_url ( 'userfiles/' ) );
	}
	
	if (defined ( 'USERFILES_DIR' ) == false) {
		
		define ( "USERFILES_DIR", USERFILES );
	}
	
	if (defined ( 'MODULES_DIR' ) == false) {
		
		define ( "MODULES_DIR", USERFILES . 'modules/' );
	}
	
	if (defined ( 'LAYOUTS_DIR' ) == false) {
		
		$layouts_dir = TEMPLATE_DIR . 'layouts/';
		
		define ( "LAYOUTS_DIR", $layouts_dir );
	} else {
		
		$layouts_dir = LAYOUTS_DIR;
	}
	
	if (defined ( 'LAYOUTS_URL' ) == false) {
		
		$layouts_url = reduce_double_slashes ( dirToURL ( $layouts_dir ) . '/' );
		
		define ( "LAYOUTS_URL", $layouts_url );
	} else {
		
		$layouts_url = LAYOUTS_URL;
	}
	
	// d ( $the_active_site_template_dir );
	
	return true;
}
function get_layout_for_page($page = array()) {
	$render_file = false;
	
	if (trim ( $page ['content_layout_name'] ) != '') {
		$template_view = ACTIVE_TEMPLATE_DIR . 'layouts' . DS . $page ['content_layout_name'] . DS . 'index.php';
		// d($template_view);
		if (is_file ( $template_view ) == true) {
			$render_file = $template_view;
		}
	}
	
	if ($render_file == false and strtolower ( $page ['active_site_template'] ) == 'default') {
		$template_view = ACTIVE_TEMPLATE_DIR . 'index.php';
		if (is_file ( $template_view ) == true) {
			$render_file = $template_view;
		}
	}
	
	if ($render_file == false and strtolower ( $page ['active_site_template'] ) == 'default') {
		$template_view = ACTIVE_TEMPLATE_DIR . 'index.php';
		if (is_file ( $template_view ) == true) {
			$render_file = $template_view;
		}
	}
	
	if ($render_file == false and ($page ['content_layout_name']) == false and ($page ['content_layout_style']) == false) {
		$template_view = ACTIVE_TEMPLATE_DIR . 'index.php';
		if (is_file ( $template_view ) == true) {
			$render_file = $template_view;
		}
	}
	
	return $render_file;
}
function get_homepage() {
	$table = c ( 'db_tables' ); // ->'table_content';
	$table = $table ['table_content'];
	
	$sql = "SELECT * from $table where is_home='y'  order by updated_on desc limit 0,1 ";
	
	$q = db_query ( $sql, __FUNCTION__ . crc32 ( $sql ), 'content/global' );
	// var_dump($q);
	$result = $q;
	
	$content = $result [0];
	
	return $content;
}
function get_page_by_url($url = '', $no_recursive = false) {
	if (strval ( $url ) == '') {
		
		$url = url_string ();
	}
	
	$table = c ( 'db_tables' ); // ->'table_content';
	$table = $table ['table_content'];
	
	$url = strtolower ( $url );
	$url = string_clean ( $url );
	$url = addslashes ( $url );
	$sql = "SELECT id,content_url from $table where content_url='{$url}' or content_title LIKE '{$url}'   order by updated_on desc limit 0,1 ";
	
	$q = db_query ( $sql, __FUNCTION__ . crc32 ( $sql ), 'content/global' );
	
	$result = $q;
	// d ( $result );
	$content = $result [0];
	
	if (! empty ( $content )) {
		
		$get_by_id = get_content_by_id ( $content ['id'] );
		
		return $get_by_id;
	}
	
	if ($no_recursive == false) {
		
		if (empty ( $content ) == true) {
			
			// /var_dump ( $url );
			
			$segs = explode ( '/', $url );
			
			$segs_qty = count ( $segs );
			
			for($counter = 0; $counter <= $segs_qty; $counter += 1) {
				
				$test = array_slice ( $segs, 0, $segs_qty - $counter );
				
				$test = array_reverse ( $test );
				
				if (isset ( $test [0] )) {
					$url = get_page_by_url ( $test [0], true );
				}
				if (! empty ( $url )) {
					
					return $url;
				}
			}
		}
	} else {
		$content ['id'] = (( int ) $content ['id']);
		$get_by_id = get_content_by_id ( $content ['id'] );
		
		return $get_by_id;
	}
}

/**
 * Function to get single content item by id from the content_table
 *
 * @param
 *        	int
 * @return array
 * @author Peter Ivanov
 *        
 */
function get_content_by_id($id) {
	$table = c ( 'db_tables' ); // ->'table_content';
	$table = $table ['table_content'];
	
	$id = intval ( $id );
	$q = "SELECT * from $table where id='$id'  limit 0,1 ";
	
	$q = db_query ( $q, __FUNCTION__ . crc32 ( $q ), 'content/' . $id );
	$content = $q [0];
	
	return $content;
}

/**
 * Function to get single content item by id from the content_table
 *
 * @param
 *        	array
 * @return array
 * @author Peter Ivanov
 *        
 */
function get_content($params) {
	$params2 = array ();
	
	if (is_string ( $params )) {
		$params = parse_str ( $params, $params2 );
		$params = $params2;
	}
	
	$function_cache_id = false;
	
	$args = func_get_args ();
	
	foreach ( $args as $k => $v ) {
		
		$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
	}
	
	$function_cache_id = __FUNCTION__ . crc32 ( $function_cache_id );
	
	$cache_content = cache_get_content ( $function_cache_id, $cache_group = 'content' );
	if (($cache_content) == '--false--') {
		return false;
	}
	// $cache_content = false;
	if (($cache_content) != false) {
		
		return $cache_content;
	} else {
		
		$table = c ( 'db_tables' );
		$table = $table ['table_content'];
		// $params['orderby'];
		if (isset ( $params ['orderby'] )) {
			$orderby = $params ['orderby'];
		}
		if ($orderby == false) {
			
			$orderby [0] = 'created_on';
			
			$orderby [1] = 'DESC';
		}
		
		if (isset ( $params ['limit'] )) {
			$limit = $params ['limit'];
		} else {
			$limit = array ();
			$limit [0] = '0';
			
			$limit [1] = '30';
		}
		
		$get = db_get ( $table, $params, $limit, $offset = false, $orderby, $cache_group = 'content' );
		
		if (! empty ( $get )) {
			
			cache_store_data ( $get, $function_cache_id, $cache_group = 'content' );
			
			return $get;
		} else {
			cache_store_data ( '--false--', $function_cache_id, $cache_group = 'content' );
			
			return FALSE;
		}
	}
}
function post_link($id = false) {
	if (is_string ( $id )) {
		// $link = page_link_to_layout ( $id );
	}
	if ($id == false) {
		if (defined ( 'PAGE_ID' ) == true) {
			$id = PAGE_ID;
		}
	}
	
	$link = get_content_by_id ( $id );
	if (strval ( $link ) == '') {
		$link = get_page_by_url ( $id );
	}
	$link = site_url ( $link ['content_url'] );
	return $link;
}
function page_link($id = false) {
	if (is_string ( $id )) {
		// $link = page_link_to_layout ( $id );
	}
	if ($id == false) {
		if (defined ( 'PAGE_ID' ) == true) {
			$id = PAGE_ID;
		}
	}
	
	$link = get_content_by_id ( $id );
	if (strval ( $link ) == '') {
		$link = get_page_by_url ( $id );
	}
	$link = site_url ( $link ['content_url'] );
	return $link;
}

/**
 * get_posts
 *
 * get_posts is used to get content by parameters
 *
 * @category posts
 *          
 *          
 */
function get_posts($params = false) {
	$params2 = array ();
	
	if (is_string ( $params )) {
		$params = parse_str ( $params, $params2 );
		$params = $params2;
	}
	return get_content ( $params );
}
function paging_links($base_url = false, $pages_count, $paging_param = 'curent_page', $keyword_param = 'keyword') {
	
	// getCurentURL()
	if ($base_url == false) {
		if (PAGE_ID != false and CATEGORY_ID == false) {
			$base_url = page_link ( PAGE_ID );
			
			// p($base_url);
		} elseif (PAGE_ID != false and CATEGORY_ID != false) {
			$base_url = category_link ( CATEGORY_ID );
		} 

		else {
			$base_url = url_string ();
		}
	}
	
	// print $base_url;
	
	$page_links = array ();
	
	$the_url = parse_url ( $base_url, PHP_URL_QUERY );
	
	$the_url = $base_url;
	
	$the_url = explode ( '/', $the_url );
	
	// var_dump ( $the_url );
	
	for($x = 1; $x <= $pages_count; $x ++) {
		
		$new_url = array ();
		
		$new = array ();
		
		foreach ( $the_url as $itm ) {
			
			$itm = explode ( ':', $itm );
			
			if ($itm [0] == $paging_param) {
				
				$itm [1] = $x;
			}
			
			$new [] = implode ( ':', $itm );
		}
		
		$new_url = implode ( '/', $new );
		
		// var_dump ( $new_url);
		
		$page_links [$x] = $new_url;
	}
	
	for($x = 1; $x <= count ( $page_links ); $x ++) {
		
		if (stristr ( $page_links [$x], $paging_param . ':' ) == false) {
			
			$page_links [$x] = reduce_double_slashes ( $page_links [$x] . '/' . $paging_param . ':' . $x );
		}
	}
	
	return $page_links;
}

/**
 * paging
 *
 * paging
 *
 * @access public
 * @category posts
 * @author Microweber
 * @link
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 * @param $display =
 *        	'default' //sets the default paging display with <ul> and </li>
 *        	tags. If $display = false, the function will return the paging
 *        	array which is the same as $posts_pages_links in every template
 *        	
 *        	
 */
function paging($display = 'default', $data = false) {
	print "paging()";
	return true;
	
	// $CI = get_instance ();
	if ($display) {
		$list_file = $display;
		$display = strtolower ( $display );
	}
	if ($data == false) {
		$posts_pages_links = get_instance ()->template ['posts_pages_links'];
	} else {
		$posts_pages_links = $data;
	}
	
	if ($posts_pages_curent_page == false) {
		
		$posts_pages_curent_page = url_param ( 'curent_page' );
	}
	
	if ($posts_pages_curent_page == false) {
		
		$posts_pages_curent_page = 1;
	}
	
	//
	switch ($display) {
		case 'default' :
		case 'ul' :
		case 'uls' :
			get_instance ()->template ['posts_pages_links'] = $posts_pages_links;
			get_instance ()->template ['posts_pages_curent_page'] = $posts_pages_curent_page;
			get_instance ()->load->vars ( get_instance ()->template );
			
			// p(RESOURCES_DIR . 'blocks/nav/default.php');
			
			$content_filename = get_instance ()->load->file ( RESOURCES_DIR . 'blocks/nav/nav_default.php', true );
			print ($content_filename) ;
			break;
		
		case 'divs' :
			get_instance ()->template ['posts_pages_links'] = $posts_pages_links;
			get_instance ()->template ['posts_pages_curent_page'] = $posts_pages_curent_page;
			get_instance ()->load->vars ( get_instance ()->template );
			
			$content_filename = get_instance ()->load->file ( RESOURCES_DIR . 'blocks/nav/nav_divs.php', true );
			
			print ($content_filename) ;
			break;
		
		case false :
		default :
			return $posts_pages_links;
			break;
	}
}

/**
 * cf_val
 *
 * Returns custom field value
 *
 * @return string or array
 * @author Peter Ivanov
 */
function custom_field_value($content_id, $field_name, $use_vals_array = true) {
	$fields = get_custom_fields_for_content ( $content_id );
	if (empty ( $fields )) {
		return false;
	}
	// p($fields);
	foreach ( $fields as $field ) {
		if ((strtolower ( $field_name )) == strtolower ( $field ['custom_field_name'] )) {
			
			if (! empty ( $field ['custom_field_values'] ) and $use_vals_array == true) {
				return $field ['custom_field_values'];
			}
			
			if ($field ['custom_field_value'] != 'Array' and $field ['custom_field_value'] != '') {
				return $field ['custom_field_value'];
			} else {
				
				if ($field ['custom_field_values']) {
					return $field ['custom_field_values'];
				}
			}
			
			// p ( $field );
		}
	}
}
function get_custom_fields_for_content($content_id) {
	// p($content_id);
	// $CI = get_instance ();
	$more = false;
	$more = get_custom_fields ( 'table_content', $content_id, true );
	return $more;
}
function get_custom_fields($table, $id = 0, $return_full = false, $field_for = false, $debug = false) {
	
	// $id = intval ( $id );
	if ($id == 0) {
		
		// return false;
	}
	
	if ($table != false) {
		$table_assoc_name = db_get_table_name ( $table );
	} else {
		
		$table_assoc_name = "MW_ANY_TABLE";
	}
	
	$table_custom_field = c ( 'db_tables' ); // ->'table_custom_fields';
	$table_custom_field = $table_custom_field ['table_custom_fields'];
	
	$the_data_with_custom_field__stuff = array ();
	
	if (strval ( $table_assoc_name ) != '') {
		
		if ($field_for != false) {
			$field_for = trim ( $field_for );
			$field_for_q = " and  (field_for='{$field_for} OR custom_field_name='{$field_for}')'";
		} else {
			$field_for_q = " ";
		}
		
		if ($table_assoc_name == 'MW_ANY_TABLE') {
			
			$qt = '';
		} else {
			$qt = "to_table = '{$table_assoc_name}' and";
		}
		
		if ($return_full == true) {
			
			$select_what = '*';
		} else {
			$select_what = '*';
		}
		
		$q = " SELECT
		{$select_what} from  $table_custom_field where
		{$qt}
		to_table_id='{$id}'
		$field_for_q
		order by field_order asc  ";
		
		if ($debug != false) {
			p ( $q );
		}
		
		$cache_id = __FUNCTION__ . '_' . crc32 ( $q );
		
		$q = db_query ( $q, $cache_id, 'custom_fields' );
		// $q = $this->dbQuery ( $q );
		// p($q);
		if (! empty ( $q )) {
			
			if ($return_full == true) {
				$to_ret = array ();
				foreach ( $q as $it ) {
					
					// $it ['value'] = $it ['custom_field_value'];
					$it ['value'] = $it ['custom_field_value'];
					$it ['values'] = $it ['custom_field_value'];
					if (strtolower ( trim ( $it ['custom_field_value'] ) ) == "array" or trim ( $it ['custom_field_values'] ) != '') {
						if ($it ['custom_field_values']) {
							// $it ['custom_field_values'] = str_replace
							// ( '&quot;', '"', $it
							// ['custom_field_values'] );
							// $it ['custom_field_values'] =
							// html_entity_decode($it
							// ['custom_field_values']);
							
							$a1 = (base64_decode ( $it ['custom_field_values'] ));
							// p ( $a1 );
							$a1 = stripslashes ( $a1 );
							// p ( $a1 );
							// $a1 = json_decode ( $a1 );
							$a1 = unserialize ( $a1 );
							// $a1 = $this->objectToArray ( $a1 );
							// p ( $a1 );
							// p($it ['custom_field_values']);
							// $a = unserialize( base64_decode ( $it
							// ['custom_field_values'] ) );
							$a = $a1;
							// p ( $a );
							$it ['values'] = $a;
							$it ['value'] = $a;
							$it ['custom_field_values'] = $a;
							
							// $it ['value'] = $it
							// ['custom_field_values'];
						}
					}
					$it ['cssClass'] = $it ['custom_field_type'];
					$it ['type'] = $it ['custom_field_type'];
					
					$it ['baseline'] = "undefined";
					
					$it ['title'] = $it ['custom_field_name'];
					$it ['required'] = $it ['custom_field_required'];
					
					$to_ret [] = $it;
				}
				return $to_ret;
			}
			
			$append_this = array ();
			
			foreach ( $q as $q2 ) {
				
				$i = 0;
				
				$the_name = false;
				
				$the_val = false;
				
				foreach ( $q2 as $cfk => $cfv ) {
					
					if ($cfk == 'custom_field_name') {
						
						$the_name = $cfv;
					}
					
					if ($cfk == 'custom_field_value') {
						
						$the_val = $cfv;
					}
					
					$i ++;
				}
				
				if ($the_name != false and $the_val != false) {
					if ($return_full == false) {
						$the_data_with_custom_field__stuff [$the_name] = $the_val;
					} else {
						$cf_cfg = array ();
						$cf_cfg ['name'] = $the_name;
						$cf_cfg = $this->getCustomFieldsConfig ( $cf_cfg );
						if (! empty ( $cf_cfg )) {
							$cf_cfg = $cf_cfg [0];
							$q2 ['config'] = $cf_cfg;
						}
						
						$the_data_with_custom_field__stuff [$the_name] = $q2;
					}
				}
			}
		}
	}
	
	$result = $the_data_with_custom_field__stuff;
	$result = (array_change_key_case ( $result, CASE_LOWER ));
	return $result;
}
