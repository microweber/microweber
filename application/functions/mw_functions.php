<?php

function url_string($string) {
	 // $CI = get_instance ();
	$string =  get_instance()->core_model->url_title ( $string );
	return $string;

}

function url($skip_ajax = false) {
	if ($skip_ajax == false) {
		$is_ajax = isAjax ();
		
		if ($is_ajax == false) {
		} else {
			if ($_SERVER ['HTTP_REFERER'] != false) {
				return $_SERVER ['HTTP_REFERER'];
			} else {
			
			}
		
		}
	}
	$pageURL = 'http';
	
	if (isset ( $_SERVER ["HTTPS"] )) {
		
		if ($_SERVER ["HTTPS"] == "on") {
			
			$pageURL .= "s";
		
		}
	
	}
	
	$pageURL .= "://";
	
	if ($_SERVER ["SERVER_PORT"] != "80") {
		
		$pageURL .= $_SERVER ["SERVER_NAME"] . ":" . $_SERVER ["SERVER_PORT"] . $_SERVER ["REQUEST_URI"];
	
	} else {
		
		$pageURL .= $_SERVER ["SERVER_NAME"] . $_SERVER ["REQUEST_URI"];
	
	}
	
	return $pageURL;

}

function clean_word($html_to_save) {
	if (strstr ( $html_to_save, '<!--[if gte mso' )) {
		//word mess up tags
		$tags = extract_tags ( $html_to_save, 'xml', $selfclosing = false, $return_the_entire_tag = true, $charset = 'UTF-8' );
		
		$matches = $tags;
		if (! empty ( $matches )) {
			foreach ( $matches as $m ) {
				$html_to_save = str_replace ( $m ['full_tag'], '', $html_to_save );
			}
			
			$html_to_save = str_replace ( '<!--[if gte mso 8]><![endif]-->', '', $html_to_save );
			
			$html_to_save = str_replace ( '<!--[if gte mso 9]><![endif]-->', '', $html_to_save );
			$html_to_save = str_replace ( '<!--[if gte mso 10]><![endif]-->', '', $html_to_save );
			$html_to_save = str_replace ( '<!--[if gte mso 11]><![endif]-->', '', $html_to_save );
			$html_to_save = str_replace ( 'class="MsoNormal"', '', $html_to_save );
		}
	
		//		$tags = extract_tags ( $html_to_save, 'style', $selfclosing = false, $return_the_entire_tag = true, $charset = 'UTF-8' );
	//		
	//		$matches = $tags;
	//		if (! empty ( $matches )) {
	//			foreach ( $matches as $m ) {
	//				$html_to_save = str_replace ( $m ['full_tag'], '', $html_to_save );
	//			}
	//		}
	

	}
	$html_to_save = str_replace ( 'class="exec"', '', $html_to_save );
	$html_to_save = str_replace ( 'style=""', '', $html_to_save );
	
	$html_to_save = str_replace ( 'ui-draggable', '', $html_to_save );
	$html_to_save = str_replace ( 'class="ui-droppable"', '', $html_to_save );
	$html_to_save = str_replace ( 'ui-droppable', '', $html_to_save );
	$html_to_save = str_replace ( 'mw_edited', '', $html_to_save );
	$html_to_save = str_replace ( '_moz_dirty=""', '', $html_to_save );
	$html_to_save = str_replace ( 'ui-droppable', '', $html_to_save );
	$html_to_save = str_replace ( '<br >', '<br />', $html_to_save );
	$html_to_save = str_replace ( '<br>', '<br />', $html_to_save );
	$html_to_save = str_replace ( ' class=""', '', $html_to_save );
	$html_to_save = str_replace ( ' class=" "', '', $html_to_save );
	//$html_to_save = str_replace ( '<br><br>', '<div><br><br></div>', $html_to_save );
	//$html_to_save = str_replace ( '<br /><br />', '<div><br /><br /></div>', $html_to_save );
	$html_to_save = preg_replace ( '/<!--(.*)-->/Uis', '', $html_to_save );
	//$html_to_save = '<p>' . str_replace("<br />","<br />", str_replace("<br /><br />", "</p><p>", $html_to_save)) . '</p>';
	//$html_to_save = str_replace(array("<p></p>", "<p><h2>", "<p><h1>", "<p><div", "</pre></p>", "<p><pre>", "</p></p>", "<p></td>", "<p><p", "<p><table", "<p><p", "<p><table"), array("<p>&nbsp;</p>", "<h2>", "<h1>", "<div",  "</pre>", "<pre>", "</p>", "</td>", "<p", "<table", "<p", "<table"), $html_to_save);
	

	//p($html_to_save);
	return $html_to_save;
}

function url_param_unset($param, $url = false) {
	if ($url == false) {
		$url = getCurentURL ();
	}
	$site = site_url ();
	
	$url = str_ireplace ( $site, '', $url );
	
	$segs = explode ( '/', $url );
	
	$segs_clean = array ();
	
	foreach ( $segs as $segment ) {
		
		$origsegment = ($segment);
		
		$segment = explode ( ':', $segment );
		
		if ($segment [0] == $param) {
		
		//return $segment [1];
		} else {
			
			$segs_clean [] = $origsegment;
		
		}
	
	}
	
	$segs_clean = implode ( '/', $segs_clean );
	
	$site = site_url ( $segs_clean );
	return $site;
}

function enc($var) {
	 // $CI = get_instance ();
	$var =  get_instance()->core_model->securityEncryptString ( $var );
	return $var;
}
function dec($var) {
	 // $CI = get_instance ();
	$var =  get_instance()->core_model->securityDecryptString ( $var );
	return $var;

}

function encode_var($var) {
	
	if ($var == '') {
		return '';
	}
	
	$var = serialize ( $var );
	$var = base64_encode ( $var );
	return $var;
}

function decode_var($var) {
	if ($var == '') {
		return '';
	}
	$var = base64_decode ( $var );
	$var = unserialize ( $var );
	return $var;
}

function mw_var($var, $print = true) {
	// // $CI = get_instance ();
	

	$var = json_encode ( $var );
	//print $var;
	if ($print == true) {
		print $var;
	} else {
		return $var;
	}
}

function mw_get_var($var) {
	// // $CI = get_instance ();
	$var = json_decode ( $var );
	if ($var != false) {
		
		$var = objectToArray ( $var );
	
	}
	//print $var;
	return $var;
}

function template_var($var) {
	 // $CI = get_instance ();
	//$var =  get_instance()->template [$var];
	//return $var;
}
function app_var($k, $new_value) {
	if ($k == false) {
		return false;
	}
	
	 // $CI = get_instance ();
	if ($k == true and ! isset ( $new_value )) {
		//return  get_instance()->appvar->get ( $k );
	} else {
		// get_instance()->appvar->set ( $k, $new_value );
	}

}

function add_post_form($params) {
	
	$display = $params ['display'];
	
	 // $CI = get_instance ();
	
	if (! $display) {
		$display = 'add';
	}
	$list_file = trim ( $display );
	if (stristr ( $list_file, '.php' ) == false) {
		$try_file_template_dir = MODULES_DIR . $list_file . '.php';
	} else {
		$try_file_template_dir = MODULES_DIR . $list_file . '';
	}
	
	if (is_file ( $try_file_template_dir ) == false) {
		$try_file1 = MODULES_DIR . DS . 'posts' . DS . 'views' . DS . $list_file . '.php';
		$try_file = MODULES_DIR . DS . 'posts' . DS . 'views' . DS . '_add_post_default' . '.php';
		$try_file1 = normalize_path ( $try_file1, false );
		$try_file = normalize_path ( $try_file, false );
		
		if ($list_file == 'default') {
			$try_file1 = $try_file;
		}
		if (is_file ( $try_file1 ) == false) {
			
			$dir = dirname ( $try_file1 );
			if (is_dir ( $dir ) == false) {
				@mkdir_recursive ( $dir );
			}
			if ($list_file != 'default') {
				if (! copy ( $try_file, $try_file1 )) {
					//echo "failed to copy $file...\n";
				}
			}
		
		}
	} else {
		$try_file1 = $try_file_template_dir;
	}
	
	if (is_file ( $try_file1 )) {
		 
		foreach ( $params as $k => $v ) {  
			 	get_instance()-> load -> vars(array($k =>$v));
		}
		
		if ($params ['id']) {
			$the_post = get_post ( $params ['id'] );
			if (($the_post ['created_by']) == user_id ()) {
				 
				 	get_instance()-> load -> vars(array('the_post' =>$the_post));
			}
		}
		
 
		 get_instance()-> load -> vars(array('params' =>$params));
 		
		$content_filename =  get_instance()->load->file ( $try_file1, true );
		print $content_filename;
	
	}

		//return $post;
}
function cf_get($content_id, $field_name) {
	return cf_val ( $content_id, $field_name );
}
/**
 * cf_val
 
 * Returns custom field value
 *
 * @return string or array
 * @author  Peter Ivanov
 */

function cf_val($content_id, $field_name, $use_vals_array = true) {
	
	$fields = get_custom_fields_for_content ( $content_id );
	if (empty ( $fields )) {
		return false;
	}
	//p($fields);
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
		
		//p ( $field );
		

		}
	}

}

function get_custom_fields($content_id) {
	return get_custom_fields_for_content ( $content_id );
}

function get_custom_fields_config_for_content($content_id, $page_id) {
	$cf_cfg = array ();
	if (intval ( $content_id ) == 0) {
		return false;
	}
	
	$cf_from_id ['to_table_id'] = $content_id;
	
	$cf_cfg1 = get_custom_fields_for_content ( $content_id );
	//p($cf_cfg1);
	$to_return = array ();
	if (! empty ( $cf_cfg1 )) {
		
		//$cf_cfg = ($cf_cfg1);
		//$cf_cfg ['default'] = $cf_from_id ['custom_field_value'];
		foreach ( $cf_cfg1 as $value ) {
			//	$to_return [] = $value;
		}
	
	}
	
	//if (! empty ( $cf_cfg1 )) {
	$cf_cfg1 = array ();
	$cf_cfg1 ['post_id'] = $content_id;
	//$cf_cfg1 ['param'] = $cf_from_id ['custom_field_name'];
	//    p( $cf_cfg1);
	
 // $CI = get_instance ();
	$cf_cfg1 =  get_instance()->core_model->getCustomFieldsConfig ( $cf_cfg1 );
	//p($cf_cfg1);
	//p($cf_cfg1);
	//$to_return = array ();
	if (! empty ( $cf_cfg1 )) {
		
		//$cf_cfg = ($cf_cfg1);
		//$cf_cfg ['default'] = $cf_from_id ['custom_field_value'];
		foreach ( $cf_cfg1 as $value ) {
			$to_return [] = $value;
		}
	
	} else {
	
	}
	
	//}
	

	$cf_cfg1 = array ();
	
	if ($page_id == false) {
		$page_for_post = get_page_for_post ( $content_id );
		
		$cf_cfg1 ['page_id'] = $page_for_post ['id'];
	} else {
		$cf_cfg1 ['page_id'] = $page_id;
	
	}
	//$cf_cfg1 ['param'] = $cf_from_id ['custom_field_name'];
	//    p( $cf_cfg1);
	$cf_cfg1 =  get_instance()->core_model->getCustomFieldsConfig ( $cf_cfg1 );
	
	//	p ( $to_return );
	// p ( $cf_cfg1 );
	

	if (! empty ( $cf_cfg1 )) {
		
		$cf_cfg = ($cf_cfg1);
		//	$cf_cfg ['default'] = $cf_from_id ['custom_field_value'];
		//	$to_return [] = $cf_cfg1;
		foreach ( $cf_cfg1 as $value ) {
			
			//	p($value );
			

			$f = false;
			foreach ( $to_return as $d ) {
				if ($d ["name"] != '') {
					if ($d ["name"] == $value ["name"]) {
						$f = true;
					
					}
				}
				
				if ($d ["custom_field_name"] != '') {
					if ($d ["custom_field_name"] == $value ["param"]) {
						$f = true;
					
					}
				}
				if ($d ["param"] == $value ["param"]) {
					$f = true;
				
				}
				if ($d ["custom_field_name"] != '') {
					if ($d ["custom_field_name"] == $value ["custom_field_name"]) {
						$f = true;
					
					}
				}
				
				if ($d ["custom_field_name"] == $value ["param"]) {
					$f = true;
				
				}
				
				if ($d ["id"] == $value ["id"]) {
					//$f = true;
				

				}
			
			}
			if ($f == false) {
				$to_return [] = $value;
			
			}
		
		}
	
	}
	
	return $to_return;
}

//p($cf_cfg);


function get_custom_fields_for_content($content_id) {
	//p($content_id);
	 // $CI = get_instance ();
	$more = false;
	$more =  get_instance()->core_model->getCustomFields ( 'table_content', $content_id, true );
	return $more;
}

function option_get($key, $group = false) {
		 // $CI = get_instance ();
	$more =  get_instance()->core_model->optionsGetByKey ( $key, $return_full = false, $orderby = false, $option_group = $group );
	
	return $more;

}

function get_post($id, $display = false) {
	if (intval ( $id ) == 0) {
		return false;
	}
	 // $CI = get_instance ();
	$post =  get_instance()->content_model->contentGetByIdAndCache ( $id );
	if ($display == false) {
		$more = false;
		$more =  get_instance()->core_model->getCustomFields ( 'table_content', $post ['id'] );
		$post ['custom_fields'] = $more;
		return $post;
	} else {
		
		$the_post = $post;
		$list_file = $display;
		
		$list_file = trim ( $list_file );
		if (stristr ( $list_file, '.php' ) == false) {
			$try_file_template_dir = TEMPLATE_DIR . $list_file . '.php';
		} else {
			$try_file_template_dir = TEMPLATE_DIR . $list_file . '';
		}
		
		if (is_file ( $try_file_template_dir ) == false) {
			$try_file1 = TEMPLATE_DIR . 'modules/posts/views/' . $list_file . '.php';
			$try_file = TEMPLATE_DIR . 'modules/posts/views/' . '_post_read_default' . '.php';
			if ($list_file == 'default') {
				$try_file1 = $try_file;
			}
			if (is_file ( $try_file1 ) == false) {
				
				$dir = dirname ( $try_file1 );
				if (is_dir ( $dir ) == false) {
					@mkdir_recursive ( $dir );
				}
				if ($list_file != 'default') {
					if (! copy ( $try_file, $try_file1 )) {
						//echo "failed to copy $file...\n";
					}
				}
			
			}
		} else {
			$try_file1 = $try_file_template_dir;
		}
		
		if (is_file ( $try_file1 )) {
			
			$more = false;
			$more =  get_instance()->core_model->getCustomFields ( 'table_content', $the_post ['id'] );
			$the_post ['custom_fields'] = $more;
			
			 
			 
			 get_instance()-> load -> vars(array('post' =>$the_post));
			 
			 
			$content_filename =  get_instance()->load->file ( $try_file1, true );
			print $content_filename;
		
		} else {
			$more = false;
			$more =  get_instance()->core_model->getCustomFields ( 'table_content', $post ['id'] );
			$post ['custom_fields'] = $more;
			return $post;
		}
	
	}
	$more = false;
	$more =  get_instance()->core_model->getCustomFields ( 'table_content', $post ['id'] );
	$post ['custom_fields'] = $more;
	return $post;
}

function get_sub_pages($page_id = false) {
	 // $CI = get_instance ();
	if ($page_id == false) {
		$page_id = PAGE_ID;
	}
	$params ['content_parent'] = $page_id;
	$posts =  get_instance()->content_model->contentGetByParams ( $params );
	
	return $posts ['posts'];

}

function get_pages($params = array()) {
	 // $CI = get_instance ();
	
	$params ['content_type'] = 'page';
	$posts =  get_instance()->content_model->contentGetByParams ( $params );
	
	return $posts ['posts'];

}

function get_page_id_for_category_id($category_id) {
	 // $CI = get_instance ();
	get_instance()->load->model ( 'Taxonomy_model', 'taxonomy_model' );
	$res =  get_instance()->taxonomy_model->get_page_for_category ( $category_id );
	
	if (! empty ( $res )) {
		return $res ['id'];
	}

}

function get_page_for_category_id($category_id) {
	 // $CI = get_instance ();
	get_instance()->load->model ( 'Taxonomy_model', 'taxonomy_model' );
	$res =  get_instance()->taxonomy_model->get_page_for_category ( $category_id );
	if (! empty ( $res )) {
		return $res;
	}

}

function get_pages_old($params = array()) {
	 // $CI = get_instance ();
	
	$params ['content_type'] = 'page';
	$posts =  get_instance()->content_model->contentGetByParams ( $params );
	$to_return = array ();
	return $posts;
	/*	foreach ( $posts as $the_post ) {
 
		$more = false;
		$more = get_instance()->core_model->getCustomFields ( 'table_content', $the_post ['id'] );
		$the_post ['custom_fields'] = $more;
	//	$to_return [] = $the_post;

	}
	return $to_return;*/
}

/**
 * get_comments 
 *
 * @desc get_comments 
 * @access      public
 * @category    comments
 * @author      Microweber 
 * @param 
   
   
   $comments = array ();
	$comments ['to_table'] = $to_table;
	$comments ['to_table_id'] = $content_id;
	//	p($comments);
	$comments = get_comments($comments);

	
 * 
 * 
  
 */

function get_comments($params) {
	$params2 = array ();
	
	if (is_string ( $params )) {
		$params = parse_str ( $params, $params2 );
		$params = $params2;
	
	}
	//	p($params2);
	//p($params);
	

	if (! is_array ( $params )) {
	
	}
	 // $CI = get_instance ();
	$try =  get_instance()->comments_model->commentsGet ( $params, $limit = false, $count_only = false, $orderby = false );
	
	return $try;
}

/**
 * get_posts 
 *
 * @desc get_posts is used to get content by parameters
 * @access      public
 * @category    posts
 * @author      Microweber 
 * @link        http://microweber.info/documentation/get_posts
 * @param 
   $params = array(); 
   //params for the output
   $params['display'] = 'post_item.php';
   
   //params for the posts
     
  	$params['selected_categories'] = array(1,2); //if false will get the articles from the curent category. use 'all' to get all articles from evrywhere
  	$params['items_per_page'] = 5; //limits the results by paging
	$params['curent_page'] = 1; //curent result page
	$params['without_custom_fields'] = true; //if true it will get only basic posts info. Use this parameter for large queries
    $params['created_by'] = 1; the user id who created the post
    
 * 
 * 
  
 */

function get_posts($params = false) {
	$params2 = array ();
	
	if (is_string ( $params )) {
		$params = parse_str ( $params, $params2 );
		$params = $params2;
	
	}
	//	p($params2);
	//p($params);
	

	if (! is_array ( $params )) {
	
	}
	
	 // $CI = get_instance ();
	if ($params ['display']) {
		$list_file = $params ['display'];
		unset ( $params ['display'] );
	}
	
	if ($params ['file']) {
		$file = $params ['file'];
		unset ( $params ['file'] );
	}
	
	if ($params ['limit']) {
		$limit = $params ['limit'];
		$params ['items_per_page'] = $limit;
		unset ( $params ['limit'] );
	}
	
	if ($params ['data']) {
		$data = $params ['data'];
		unset ( $params ['data'] );
	}
	
	if (! $params ['items_per_page']) {
		$params ['items_per_page'] = 30;
	}
	app_var ( 'items_per_page', $params ['items_per_page'] );
	//var_Dump($params ['selected_categories']);
	if ($params ['category']) {
		$params ['selected_categories'] [] = $params ['category'];
	}
	
	if ($params ['category_id']) {
		$params ['selected_categories'] [] = $params ['category'];
	}
	
	if ($params ['page']) {
		$params ['page_id'] = $params ['page'];
	}
	
	if ($params ['page_id']) {
		
		$content1 =  get_instance()->content_model->contentGetById ( $params ['page_id'] );
		if ($content1 ['content_subtype'] == 'dynamic') {
			
			$base_category = $content1 ['content_subtype_value'];
			$params ['selected_categories'] [] = $base_category;
		}
	
	}
	
	if ($data == false) {
		if (is_array ( $params ['selected_categories'] )) {
		
		} else {
			if (($params ['selected_categories']) == 'all') {
				
				unset ( $params ['selected_categories'] );
			} else {
				$params ['selected_categories'] =  get_instance()->template ['active_categories'];
				if ($params ['selected_categories'] == false) {
					$try_post =  get_instance()->template ['post'];
					
					if (! empty ( $try_post )) {
						$try =  get_instance()->taxonomy_model->getCategoriesForContent ( $try_post ['id'], $return_only_ids = true );
						
						if (! empty ( $try )) {
						
						}
						
						$params ['selected_categories'] = $try;
					}
				}
			}
		}
	}
	if ($params ['without_custom_fields']) {
		$without_custom_fields = $params ['without_custom_fields'];
		unset ( $params ['without_custom_fields'] );
	}
	
	if ($data == false) {
		if (! empty ( $params )) {
			
			$posts =  get_instance()->content_model->contentGetByParams ( $params );
			return $posts;
			//p($posts);
			if (! empty ( $posts )) {
				if ($posts ["posts_pages_count"]) {
					app_var ( 'posts_pages_count', $posts ["posts_pages_count"] );
				}
			}
			if (! empty ( $posts )) {
				if ($posts ["posts_pages_curent_page"]) {
					app_var ( 'posts_pages_curent_page', $posts ["posts_pages_curent_page"] );
				}
			}
			if (! empty ( $posts )) {
				if ($posts ["posts_pages_links"]) {
					app_var ( 'posts_pages_links', $posts ["posts_pages_links"] );
				}
			}
			if (! empty ( $posts )) {
				$posts = $posts ['posts'];
			}
		} else {
			$posts =  get_instance()->template ['posts'];
		}
	} else {
		$posts = $data;
	
		//p($data);
	}
	
	if (! empty ( $posts )) {
		$posts_cf = array ();
		foreach ( $posts as $post ) {
			//$more = false;
			//$more =  get_instance()->core_model->getCustomFields ( 'table_content', $post ['id'] );
			//$post ['custom_fields'] = $more;
			$posts_cf [] = $post;
		}
		$posts = $posts_cf;
	}
	
	if ($file != false) {
		
		$try_file1 = TEMPLATE_DIR . 'modules/posts/views/' . $file . '.php';
		
		if (is_file ( $try_file1 )) {
			 get_instance()->template ['posts'] = $posts;
			 get_instance()->load->vars (  get_instance()->template );
			
			$content_filename =  get_instance()->load->file ( $try_file1, true );
			print $content_filename;
		} else {
			$try_file1 = TEMPLATE_DIR . '' . $file . '.php';
			
			if (is_file ( $try_file1 )) {
				 get_instance()->template ['posts'] = $posts;
				 get_instance()->load->vars (  get_instance()->template );
				
				$content_filename =  get_instance()->load->file ( $try_file1, true );
				print $content_filename;
			}
		}
	
	} elseif ($list_file != false) {
		if (! empty ( $posts )) {
			$list_file = trim ( $list_file );
			if (stristr ( $list_file, '.php' ) == false) {
				$try_file_template_dir = TEMPLATE_DIR . $list_file . '.php';
			} else {
				$try_file_template_dir = TEMPLATE_DIR . $list_file . '';
			}
			
			if (is_file ( $try_file_template_dir ) == false) {
				$try_file1 = TEMPLATE_DIR . 'modules/posts/views/' . $list_file . '.php';
				$try_file = TEMPLATE_DIR . 'modules/posts/views/' . '_default' . '.php';
				if ($list_file == 'default') {
					$try_file1 = $try_file;
				}
				if (is_file ( $try_file1 ) == false) {
					
					$dir = dirname ( $try_file1 );
					if (is_dir ( $dir ) == false) {
						@mkdir_recursive ( $dir );
					}
					if ($list_file != 'default') {
						if (! copy ( $try_file, $try_file1 )) {
							//echo "failed to copy $file...\n";
						}
					}
				
				}
			} else {
				$try_file1 = $try_file_template_dir;
			}
			
			if (is_file ( $try_file1 )) {
				
				foreach ( $posts as $the_post ) {
					if ($without_custom_fields == false) {
						$more = false;
						$more =  get_instance()->core_model->getCustomFields ( 'table_content', $the_post ['id'] );
						$the_post ['custom_fields'] = $more;
					}
					 get_instance()->template ['the_post'] = $the_post;
					 get_instance()->load->vars (  get_instance()->template );
					
					$content_filename =  get_instance()->load->file ( $try_file1, true );
					print $content_filename;
				
				}
			
			} else {
				return $posts;
			}
		}
	} else {
		return $posts;
	}
	return $posts;
}

/**
 * get_paging 
 *
 * @desc get_paging
 * @access      public
 * @category    posts
 * @author      Microweber 
 * @link        
 * @param  
 * $display = 'default' //sets the default paging display with <ul> and </li> tags. If $display = false, the function will return the paging array which is the same as $posts_pages_links in every template
   
 */

function paging($display = 'default', $data = false) {
	 // $CI = get_instance ();
	if ($display) {
		$list_file = $display;
		$display = strtolower ( $display );
	
	}
	if ($data == false) {
		$posts_pages_links =  get_instance()->template ['posts_pages_links'];
	} else {
		$posts_pages_links = $data;
	}
	//
	switch ($display) {
		case 'default' :
		case 'ul' :
		case 'uls' :
			 get_instance()->template ['posts_pages_links'] = $posts_pages_links;
			 get_instance()->load->vars (  get_instance()->template );
			
			//p(RESOURCES_DIR . 'blocks/nav/default.php');
			

			$content_filename =  get_instance()->load->file ( RESOURCES_DIR . 'blocks/nav/nav_default.php', true );
			print ($content_filename) ;
			break;
		
		case 'divs' :
			 get_instance()->template ['posts_pages_links'] = $posts_pages_links;
			 get_instance()->load->vars (  get_instance()->template );
			
			$content_filename =  get_instance()->load->file ( RESOURCES_DIR . 'blocks/nav/nav_divs.php', true );
			
			print ($content_filename) ;
			break;
		
		case false :
		default :
			return $posts_pages_links;
			break;
	}
}

/**
 * get_post_link 
 *
 * @desc get the link of the post and return the variable
 * @access      public
 * @category    posts
 * @author      Microweber 
 * @link        http://microweber.com
 * @param $id - the content id
 
 */

function get_post_link($id) {
	 // $CI = get_instance ();
	$link =  get_instance()->content_model->contentGetHrefForPostId ( $id );
	return $link;
}
function post_link($id) {
	return get_post_link ( $id );
}

function page_link_to_layout($laout_name) {
	
	$page = array ();
	$page ['content_layout_name'] = trim ( $laout_name );
	
	$page = get_pages ( $page );
	$page = $page [0];
	return page_link ( $page ['id'] );

}

function page_url() {
	return page_link ( $id );
}
function page_link($id = false) {
	
	if (is_string ( $id )) {
		//	$link = page_link_to_layout ( $id );
	}
	if (strval ( $link ) == '') {
		if ($id == false) {
			if (defined ( 'PAGE_ID' ) == true) {
				$id = PAGE_ID;
			}
		}
		 // $CI = get_instance ();
		$link =  get_instance()->content_model->getContentURLByIdAndCache ( $id );
		if (strval ( $link ) == '') {
			$link =  get_instance()->content_model->getContentByURL ( $id );
			$link =  get_instance()->content_model->getContentURLByIdAndCache ( $link ['id'] );
		}
	}
	return $link;

}

function menu_tree($menu_id, $max_depth = false) {
	 // $CI = get_instance ();
	$menu_items =  get_instance()->content_model->menuTree ( $menu_id, $max_depth );
	
	print $menu_items;
}

function get_page_for_post($post_id) {
	
	$url = post_link ( $post_id );
	 // $CI = get_instance ();
	$page =  get_instance()->content_model->getPageByURLAndCache ( $url );
	//p($url_page);
	return $page;

}
function get_content($id) {
	 // $CI = get_instance ();
	$page =  get_instance()->content_model->contentGetById ( $id );
	
	if (empty ( $page )) {
		$page =  get_instance()->content_model->getContentByURL ( $id );
	}
	
	if (! empty ( $page )) {
		$more = false;
		$more =  get_instance()->core_model->getCustomFields ( 'table_content', $page ['id'] );
		$page ['custom_fields'] = $more;
	}
	return $page;
}
function get_page($id = false) {
	if ($id == false) {
		return false;
	}
	
	
	
	 // $CI = get_instance ();
	if (intval ( $id ) != 0) {
		$page =  get_instance()->content_model->contentGetById ( $id );
		
		if (empty ( $page )) {
			$page =  get_instance()->content_model->getContentByURL ( $id );
		}
	} else {
		if (empty ( $page )) {
			$page = array ();
			$page ['content_layout_name'] = trim ( $id );
			
			$page = get_pages ( $page );
			$page = $page [0];
		}
	}
	if (! empty ( $page )) {
		$more = false;
		$more =  get_instance()->core_model->getCustomFields ( 'table_content', $page ['id'] );
		$page ['custom_fields'] = $more;
	}
	
	return $page;

		//$link = get_instance()->content_model->getContentURLByIdAndCache ( $link['id'] );


}

function page_save($data) {
	 // $CI = get_instance ();
	$errors = array ();
	if ($data) {
		if ($data ['id']) {
			$page = get_page ( $data ['id'] );
			
			$is_admin = is_admin ();
			if ($is_admin == false) {
				if ($page ['created_by'] != user_id ()) {
					$errors ['security_error'] = "You dont have the rights to edit this page.";
				}
			
			}
		}
		if ($data ['skip_errors'] == false) {
			if (strval ( $data ['content_title'] ) == '') {
				$errors ['title_error'] = "You must enter a title!";
			}
			
			if (strval ( $data ['content_url'] ) == '') {
				$errors ['content_url'] = "You must enter page url!";
			} else {
				$data ['content_url'] =  get_instance()->core_model->url_title ( $data ['content_url'] );
			}
		}
		if (empty ( $errors )) {
			$to_save = array ();
			$to_save = $data;
			$to_save ['content_type'] = 'page';
			$to_save ['content_parent'] = intval ( $to_save ['content_parent'] );
			
			$saved =  get_instance()->content_model->saveContent ( $to_save );
			
			$return = array ();
			$return ['form_values'] = $data;
			$return ['id'] = $saved;
			$return ['success'] = true;
			$return ['form_errors'] = false;
			return $return;
		} else {
			$return = array ();
			$return ['form_values'] = $data;
			$return ['form_errors'] = $errors;
			$return ['success'] = false;
			return $return;
		}
	}

}

function post_save($data) {
	 // $CI = get_instance ();
	 
	if ($data) {
		get_instance()->load->model ( 'Taxonomy_model', 'taxonomy_model' );
		get_instance()->load->model ( 'Content_model', 'content_model' );
		$categories_ids_to_remove = array ();
		$categories_ids_to_remove ['taxonomy_type'] = 'category';
		$categories_ids_to_remove ['users_can_create_content'] = 'n';
		$categories_ids_to_remove =  get_instance()->taxonomy_model->getIds ( $categories_ids_to_remove, $orderby = false );
		// get_instance()->template ['categories_ids_to_remove'] = $categories_ids_to_remove;
		

		$categories_ids_to_add = array ();
		$categories_ids_to_add ['taxonomy_type'] = 'category';
		$categories_ids_to_add ['users_can_create_content'] = 'y';
		$categories_ids_to_add =  get_instance()->taxonomy_model->getIds ( $categories_ids_to_add, $orderby = false );
		// get_instance()->template ['categories_ids_to_add'] = $categories_ids_to_add;
		

		$errors = array ();
		$categories = $data ['taxonomy_categories'];
		
		if (!is_array ( $categories ) and strstr ( $categories, ',' )) {
			$data ['taxonomy_categories'] = explode ( ',', $data ['taxonomy_categories'] );
			$categories = $data ['taxonomy_categories'];
			$categories = array_unique ( $categories );
		}
		
		if (is_array ( $data ['categories'] )) {
			
			$categories = array_unique ( $data ['categories'] );
		}
		
		//var_Dump($categories);
		

		$user_id = is_admin ();
		if ($user_id == false) {
			if (! empty ( $categories )) {
				
				foreach ( $categories as $cat ) {
					$cat =  get_instance()->taxonomy_model->getIdByName ( $cat );
					$parrent_cats =  get_instance()->taxonomy_model->getParents ( $cat );
					
					foreach ( $parrent_cats as $par_cat ) {
						$categories [] = $par_cat;
					}
				
				}
				$categories = array_unique ( $categories );
			}
		
		}
		
		if ($data ['id']) {
			$page = get_post ( $data ['id'] );
			
			$is_admin = is_admin ();
			if ($is_admin == false) {
				if ($page ['created_by'] != user_id ()) {
					$errors ['security_error'] = "You dont have the rights to edit this post.";
				}
			
			}
		}
		
		$category = $categories [count ( $categories ) - 1];
		$user_id = is_admin ();
		if ($user_id == false) {
			if (! empty ( $categories )) {
				$i = 0;
				foreach ( $categories as $cat ) {
					if (! empty ( $categories_ids_to_remove )) {
						if (in_array ( $cat, $categories_ids_to_remove ) == true) {
							unset ( $categories [$i] );
						}
					}
					$i ++;
				}
			}
		}
		
		if (($user_id == false) and (! empty ( $categories_ids_to_remove )) and (in_array ( $category, $categories_ids_to_remove ) == true)) {
			exit ( 'Error: You are trying to post to one or more invalid categories!' );
		
		//error
		} else {
			
			$check_title = array ();
			
			if (trim ( strval ( $data ['content_title'] ) ) == '') {
				$errors ['content_title'] = "Please enter title";
			}
			
			if (trim ( strval ( $data ['content_description'] ) ) == '') {
				//$errors ['content_description'] = "Please enter description";
			}
			
			$check_title ['content_title'] = $data ['content_title'];
			
			$check_title ['content_type'] = 'post';
			
			$check_title =  get_instance()->content_model->getContent ( $check_title, $orderby = false, $limit = false, $count_only = false );
			
			$check_title_error = false;
			
			$taxonomy_categories = array ($category );
			
			$taxonomy =  get_instance()->taxonomy_model->getParents ( $category );
			
			if (! empty ( $taxonomy )) {
				
				foreach ( $taxonomy as $i ) {
					$i =  get_instance()->taxonomy_model->getIdByName ( $i );
					if (intval ( $i ) != 0) {
						$taxonomy_categories [] = $i;
					}
				
				}
			
			}
			
			$to_save = $data;
			
			$to_save ['content_type'] = 'post';
			
			if (empty ( $categories )) {
				$errors ['taxonomy_categories'] = "Please choose at least one category";
			}
			$categories = array_reverse ( $categories );
			$categories = array_unique ( $categories );
			$to_save ['taxonomy_categories'] = $categories;
			
			$parent_page = false;
			
			foreach ( $categories as $cat ) {
				if (empty ( $parent_page )) {
					$parent_page =  get_instance()->content_model->contentsGetTheLastBlogSectionForCategory ( $cat );
				}
			
			}
			
			if (! empty ( $categories )) {
				if (empty ( $parent_page )) {
					//$errors [] = "Invalid category. This category doesn't have defined section from the admin!";
				//errror
				}
			}
			
			if (empty ( $errors )) {
				
				if ($data ['content_parent']) {
					$to_save ['content_parent'] = $data ['content_parent'];
				} else {
					$to_save ['content_parent'] = $parent_page ['id'];
				}
				
				$to_save ['is_home'] = 'n';
				$to_save ['content_type'] = 'post';
				
				 
				

				$saved =  get_instance()->content_model->saveContent ( $to_save );
				$return = array ();
				$return ['form_values'] = $data;
				$return ['id'] = $saved;
				$return ['success'] = true;
				$return ['form_errors'] = false;
				return $return;
			
		//redirect ( 'users/user_action:posts/type:all' );
			

			} else {
				//p($errors);
				$return = array ();
				$return ['form_values'] = $data;
				$return ['form_errors'] = $errors;
				$return ['success'] = false;
				return $return;
			}
		
		}
	
	}

}

/**
 * thumbnail 
 *
 * @desc get the link of the thumbnail for post
 * @access      public
 * @category    general
 * @author      Microweber 
 * @link        http://microweber.com
 * @param 
   $params = array(); 
   $params['id'] = 15; //the post id
   $params['size'] = 200; //the thumbnail size
   @return 		string - The thumbnail link. 
   @example 	Use <? print post_thumbnail($post['id']);

 */

function thumbnail($params) {
	
	if (! is_array ( $params )) {
		$params = array ();
		$numargs = func_num_args ();
		if ($numargs > 0) {
			foreach ( func_get_args () as $name => $value )
				
				//$arg_list = func_get_args ();
				$params ['id'] = func_get_arg ( 0 );
			$params ['size'] = func_get_arg ( 1 );
		}
	}
	//var_Dump($params);
	 // $CI = get_instance ();
	
	if (! isset ( $params ['size'] )) {
		$params ['size'] = 200;
	}
	
	$thumb =  get_instance()->content_model->contentGetThumbnailForContentId ( $params ['id'], $params ['size'] );
	
	return $thumb;
}

function post_pictures($post_id, $size = 128) {
	if (intval ( $post_id ) == 0) {
		return false;
	}
	
	 // $CI = get_instance ();
	$thumb =  get_instance()->core_model->mediaGetImages ( $to_table = 'table_content', $post_id, $size, $order = "ASC" );
	return $thumb;

}

function page_title($page_id) {
	$p = get_page ( $page_id );
	return $p ['content_title'];
}

/**
 * category_tree
 *
 * @desc prints category_tree of UL and LI
 * @access      public
 * @category    categories
 * @author      Microweber
 * @link        http://microweber.info/documentation/get_categories
 * @param
$params = array();
$params['content_parent'] = false; //parent id
$params['link'] = false; // the link on for the <a href
$params['actve_ids'] = array(); //ids of active categories
$params['active_code'] = false; //inserts this code for the active ids's
$params['remove_ids'] = array(); //remove those caregory ids
$params['ul_class_name'] = false; //class name for the ul
$params['include_first'] = false; //if true it will include the main parent category
$params['content_type'] = false; //if this is set it will include only categories from desired type
$params['add_ids'] = array(); //if you send array of ids it will add them to the category
$params['orderby'] = array(); //you can order by such array $params['orderby'] = array('created_on','asc');

 */
function category_tree($params) {
	$p2 = array ();
	//p($params);
	if (! is_array ( $params )) {
		if (is_string ( $params )) {
			parse_str ( $params, $p2 );
			$params = $p2;
		}
	}
	//p($p2);
	

 
	 // $CI = get_instance ();
	 get_instance()->load->model ( 'Taxonomy_model', 'taxonomy_model' );
	$content_parent = ($params ['content_parent']) ? $params ['content_parent'] : $params ['content_subtype_value'];
	$link = ($params ['link']) ? $params ['link'] : false;
	
	if ($link == false) {
		$link = "<a href='{taxonomy_url}' {active_code} >{taxonomy_value}</a>";
	}
	
	$actve_ids = ($params ['actve_ids']) ? $params ['actve_ids'] : array (CATEGORY_ID );
	$active_code = ($params ['active_code']) ? $params ['active_code'] : " class='active' ";
	$remove_ids = ($params ['remove_ids']) ? $params ['remove_ids'] : false;
	$removed_ids_code = ($params ['removed_ids_code']) ? $params ['removed_ids_code'] : false;
	if ($params ['class']) {
		$ul_class_name = $params ['class'];
	} else {
		$ul_class_name = ($params ['ul_class_name']) ? $params ['ul_class_name'] : $params ['ul_class_name'];
	}
	
	if ($params ['ul_class']) {
		$ul_class_name = $params ['ul_class'];
	}
	
	$include_first = ($params ['include_first']) ? $params ['include_first'] : false;
	$content_type = ($params ['content_type']) ? $params ['content_type'] : false;
	$add_ids = ($params ['add_ids']) ? $params ['add_ids'] : false;
	$orderby = ($params ['orderby']) ? $params ['orderby'] : false;
	
	if ($params ['for_page'] != false) {
		//	p($params);
		$page = get_page ( $params ['for_page'] );
		//p($page);
		$content_parent = $page ['content_subtype_value'];
	
		//$categories =  get_instance()->taxonomy_model->getCategoriesForContent ( $content_id = $params ['for_content'], $return_only_ids = true );
	}
	if ($params ['content_subtype_value'] != false) {
		$content_parent = $params ['content_subtype_value'];
	}
	
	if ($params ['not_for_page'] != false) {
		//	p($params);
		$page = get_page ( $params ['not_for_page'] );
		//p($page);
		$remove_ids = array ($page ['content_subtype_value'] );
	
		//$categories =  get_instance()->taxonomy_model->getCategoriesForContent ( $content_id = $params ['for_content'], $return_only_ids = true );
	}
	 // $CI = get_instance ();
	//$content_parent, $link = false, $actve_ids = false, $active_code = false, $remove_ids = false, $removed_ids_code = false, $ul_class_name = false, $include_first = false, $content_type = false, $add_ids = false, $orderby = false, $only_with_content = false
	 get_instance()->content_model->content_helpers_getCaregoriesUlTreeAndCache ( $content_parent, $link, $actve_ids, $active_code, $remove_ids, $removed_ids_code, $ul_class_name, $include_first, $content_type, $add_ids, $orderby, $only_with_content );

}

function get_categories_for_post($content_id, $only_ids = true) {
	//var_dump($content_id);
	//print '-------------------';
	//exit(1);
	 // $CI = get_instance ();
	 get_instance()->load->model ( 'Taxonomy_model', 'taxonomy_model' );
	$cat_ids =  get_instance()->taxonomy_model->getTaxonomiesForContent ( $content_id, $taxonomy_type = 'categories' );
	
	//$c =  get_instance()->taxonomy_model->getCategoriesForContent( $content_id, $only_ids );
	return $cat_ids;
}

/**
 * get_categories
 *
 * @desc get_categories is used to get categories
 * @access      public
 * @category    categories
 * @author      Microweber
 * @link        http://microweber.info/documentation/get_categories
 * @param
 * $get_categories_params = array();
 * $get_categories_params['parent'] = false; //begin from this parent category
 * $get_categories_params['get_only_ids'] = false; //if true will return only the category ids
 * $get_categories_params['inclide_main_category'] = false; //if true will include the main category too
 * $get_categories_params['for_content'] = false; //if integer - will get the categories for given content it (post)
 *
 *
 */
function get_categories($get_categories_params = array()) {
	$params = $get_categories_params;
	//p($params);
	 // $CI = get_instance ();
	 get_instance()->load->model ( 'Taxonomy_model', 'taxonomy_model' );
	if ($params ['display']) {
		$list_file = $params ['display'];
		unset ( $params ['display'] );
	}
	
	if ($params ['get_only_ids']) {
		$get_only_ids = $params ['get_only_ids'];
		unset ( $params ['get_only_ids'] );
	}
	
	if ($params ['inclide_main_category']) {
		$inclide_main_category = $params ['inclide_main_category'];
		unset ( $params ['inclide_main_category'] );
	}
	
	if ($params ['for_page'] != false) {
		$page = get_page ( $params ['for_page'] );
		//p($page);
		$categories =  get_instance()->taxonomy_model->getChildrensRecursiveAndCache ( $page ['content_subtype_value'], $type = 'category', $visible_on_frontend = false );
	
		//$categories =  get_instance()->taxonomy_model->getCategoriesForContent ( $content_id = $params ['for_content'], $return_only_ids = true );
	} else {
		
		if ($params ['for_content'] != false) {
			$categories =  get_instance()->taxonomy_model->getCategoriesForContent ( $content_id = $params ['for_content'], $return_only_ids = true );
		} else {
			
			if ($params ['parent'] == false) {
				$page =  get_instance()->template ['page'];
				if (! empty ( $page )) {
					$the_category = $page ['content_subtype_value'];
					$categories =  get_instance()->taxonomy_model->getChildrensRecursiveAndCache ( $the_category, $type = 'category', $visible_on_frontend = false );
				} else {
					$the_category = 0;
					$categories =  get_instance()->taxonomy_model->getChildrensRecursiveAndCache ( $the_category, $type = 'category', $visible_on_frontend = false );
				
				}
			} else {
				$the_category = $params ['parent'];
				
				$categories =  get_instance()->taxonomy_model->getChildrensRecursiveAndCache ( $the_category, $type = 'category', $visible_on_frontend = false );
			
			}
		
		}
	}
	$cats = array ();
	foreach ( $categories as $category ) {
		$category_id = $category;
		if ($get_only_ids == false) {
			$temp =  get_instance()->taxonomy_model->getSingleItem ( $category );
		} else {
			$temp = $category;
		}
		if ($inclide_main_category == true) {
			if (intval ( $category_id ) != intval ( $the_category )) {
				$cats [] = $temp;
			}
		} else {
			$cats [] = $temp;
		}
	
	}
	
	return $cats;

}

function get_category_id($category_name) {
	 // $CI = get_instance ();
	 get_instance()->load->model ( 'Taxonomy_model', 'taxonomy_model' );
	$category_id =  get_instance()->taxonomy_model->getIdByName ( $category_name );
	
	return $category_id;

}

function category_name($category_id) {
	 // $CI = get_instance ();
	 get_instance()->load->model ( 'Taxonomy_model', 'taxonomy_model' );
	$category_id =  get_instance()->taxonomy_model->getIdByName ( $category_id );
	$c =  get_instance()->taxonomy_model->getSingleItem ( $category_id );
	
	if (! empty ( $c )) {
		return $c ['taxonomy_value'];
	}

}

function get_category($category_id) {
	 // $CI = get_instance ();
 get_instance()->load->model ( 'Taxonomy_model', 'taxonomy_model' );
	$category_id =  get_instance()->taxonomy_model->getIdByName ( $category_id );
	$c =  get_instance()->taxonomy_model->getSingleItem ( $category_id );
	if (! empty ( $c )) {
		$more = false;
		$more =  get_instance()->core_model->getCustomFields ( 'table_taxonomy', $category_id );
		$c ['custom_fields'] = $more;
	}
	return $c;

}

/**
 * get_category_items_count
 *
 * @desc get_category_items_count is used to get the count of items in category
 * @access      public
 * @category    categories
 * @author      Microweber
 * @link        http://microweber.com
 * @param $category_id
 */

function get_category_items_count($category_id) {
	 // $CI = get_instance ();
	 get_instance()->load->model ( 'Taxonomy_model', 'taxonomy_model' );
	$qty =  get_instance()->taxonomy_model->getChildrensCount ( $category_id );
	return $qty;

}

/**
 * get_category_url
 *
 * @desc get url for_category by ID
 * @access      public
 * @category    categories
 * @author      Microweber
 * @link        http://microweber.com
 * @param  $category_id

 */

function get_category_url($category_id) {
	 // $CI = get_instance ();
	 
	$url =  get_instance()->content_model->taxonomyGetUrlForId ( $category_id );
	return $url;

}

function category_url($category_id) {
	
	return get_category_url ( $category_id );

}

function category_link($category_id) {
	
	return get_category_url ( $category_id );

}

/**
 * is_active_category
 *
 * @desc check is the the $category_id is in the active categories, if you put something as a second param it will print it, else it will return true or false;
 * @access      public
 * @category    categories
 * @author      Microweber
 * @link        http://microweber.com
 * @param  $category_id
 * @param  $string = false

 */

function is_active_category($category_id, $string = false) {
	 // $CI = get_instance ();
	$active_categories =  get_instance()->template ['active_categories'];
	
	if ($active_categories == false) {
		$try_post =  get_instance()->template ['post'];
		
		if (! empty ( $try_post )) {
			get_instance()->load->model ( 'Taxonomy_model', 'taxonomy_model' );
			$try =  get_instance()->taxonomy_model->getCategoriesForContent ( $try_post ['id'], $return_only_ids = true );
			//p($try);
			if (! empty ( $try )) {
			
			}
			
			$active_categories = $try;
		}
	}
	
	if (in_array ( $category_id, $active_categories )) {
		if ($string != '') {
			print $string;
		} else {
			return true;
		}
	} else {
		return false;
	}
}

/**
 * votes_count
 *
 * @desc votes_count get count of votes for anything
 * @access      public
 * @category    votes
 * @author      Microweber
 * @link        http://microweber.com
 * @param $content_id - the id of the content or the element
 * @param $since - if false will get the total votes for all time, you can get fotes only for the last 30 days for example:$since = '30 days'
 * @param $for - use this patameter when you want to get votes for something else than posts.
 */

function votes_count($content_id, $since = false, $for = 'post') {
	$content_id = intval ( $content_id );
	if ($content_id == 0) {
		return false;
	}
	
	 // $CI = get_instance ();
	
	$to_table =  get_instance()->core_model->guessDbTable ( $for );
	//.p($to_table);
	$qty = CI::model ( 'votes' )->votesGetCount ( $to_table, $content_id, $since );
	$qty = intval ( $qty );
	return $qty;

}

function voted_users($content_id, $since = false, $for = 'post') {
	$content_id = intval ( $content_id );
	if ($content_id == 0) {
		return false;
	}
	
	 // $CI = get_instance ();
	
	$to_table =  get_instance()->core_model->guessDbTable ( $for );
	
	$voted = CI::model ( 'votes' )->getVotedUsers ( $to_table, $content_id, $since );
	
	return $voted;

}

/**
 * voting_link
 *
 * @desc returns the voting button link, when the users press it he will vote for content
 * @access      public
 * @category    votes
 * @author      Microweber
 * @link        http://microweber.com
 * @param $content_id - the id of the content or the element
 * @param $is_moderated - counts only the moderated comments
 * @param $for - use this patameter when you want to get votes for something else than posts.
 */

function voting_link($content_id, $counter_selector = false, $for = 'post') {
	$content_id = intval ( $content_id );
	if ($content_id == 0) {
		return false;
	}
	
	 // $CI = get_instance ();
	
	$to_table =  get_instance()->core_model->guessDbTable ( $for );
	
	$string1 =  get_instance()->core_model->securityEncryptString ( $to_table );
	$string2 =  get_instance()->core_model->securityEncryptString ( $content_id );
	
	$return = "javascript:mw.content.Vote('$string1','$string2', '$counter_selector');";
	
	return $return;

}

function sess_id() {
	 // $CI = get_instance ();
	$session_id =  get_instance()->session->userdata ( 'session_id' );
	return $session_id;
}

/**
 * breadcrumbs
 *
 * @desc print breadcrumbs
 * @access      public
 * @category    navigation
 * @author      Microweber
 * @link        http://microweber.com
 * @param $seperator
 */

function breadcrumbs($seperator) {
	
	 // $CI = get_instance ();
	
	$quick_nav =  get_instance()->content_model->getBreadcrumbsByURLAsArray ( $the_url = false, $include_home = false, $options = array () );
	
	 get_instance()->template ['quick_nav'] = $quick_nav;
	 get_instance()->template ['seperator'] = $seperator;
	 get_instance()->load->vars (  get_instance()->template );
	switch ($seperator) {
		
		case 'ul' :
			
			$content_filename =  get_instance()->load->file ( DEFAULT_TEMPLATE_DIR . 'blocks/nav/breadcrumbs_ul.php', true );
			print ($content_filename) ;
			break;
		
		case false :
		default :
			$content_filename =  get_instance()->load->file ( DEFAULT_TEMPLATE_DIR . 'blocks/nav/breadcrumbs_default.php', true );
			print ($content_filename) ;
			break;
			break;
	}
	
	return $quick_nav;

}

function get_mediaby_id($id) {
	$media =  get_instance()->core_model->mediaGetById ( $id );
	return $media;
}

/**
 * get_pictures
 *
 * @desc get_pictures
 * @access      public
 * @category    media
 * @author      Microweber
 * @link        http://microweber.com
 * @param $id - the id of the element - page,post or category
 * @param $for - use this patameter when you want to get pics for something else than media for posts. possible values are page,post,category
 *
 * @todo for users too
 */
function get_pictures($content_id, $for = 'post') {
	$content_id = intval ( $content_id );
	if ($content_id == 0 and $queue_id == false and $collection == false) {
		return false;
	}
	
	if ($for != 'post') {
		 // $CI = get_instance ();
		$for =  get_instance()->core_model->guessDbTable ( $for );
	
		//
	}
	
	//	p($to_table);
	// var_dump($id, $for, $media_type, $queue_id, $collection);
	$media = get_media ( $content_id, $for, $media_type = 'pictures', $queue_id = false, $collection = false );
	
	//p($media);
	return $media ['pictures'];

		//


//p($content_id);


}

function get_picture($content_id, $for = 'post') {
	$imgages = get_pictures ( $content_id, $for );
	//..p($imgages);
	return $imgages [0];
}
/**
 * get_media
 *
 * @desc get_media
 * @access      public
 * @category    media
 * @author      Microweber
 * @link        http://microweber.com
 * @param $id - the id of the element - page,post or category
 * @param $for - use this patameter when you want to get votes for something else than media for posts. possible values are page,post,category
 * @param $media_type - counts only the moderated comments
 *
 * @todo for users too
 */
function get_media($id, $for = 'post', $media_type = false, $queue_id = false, $collection = false) {
	$content_id = intval ( $id );
	if ($content_id == 0 and $queue_id == false and $collection == false) {
		return false;
	}
	
	if ($content_id == 0 and $queue_id != false) {
		$content_id = false;
	}
	
	if ($content_id != 0 and $queue_id != false) {
		$queue_id = false;
	}
	
	 // $CI = get_instance ();
	if ($collection == false) {
		$to_table =  get_instance()->core_model->guessDbTable ( $for );
	
		//
	}
	//	p($to_table);
	//var_dump($id, $for, $media_type, $queue_id, $collection);
	$media =  get_instance()->core_model->mediaGet ( $to_table, $content_id, $media_type, $order = "ASC", $queue_id, $no_cache = false, $id = false, $collection );
	return $media;

		// p($media);


//p($content_id);


}

function get_media_thumbnail($id, $size_width = 128, $size_height = false) {
	$media =  get_instance()->core_model->mediaGetThumbnailForMediaId ( $id, $size_width, $size_height );
	return $media;
}

/**
 * comments_count
 *
 * @desc comments_count get count of comments for anything
 * @access      public
 * @category    comments
 * @author      Microweber
 * @link        http://microweber.com
 * @param $content_id - the id of the content or the element
 * @param $is_moderated - counts only the moderated comments
 * @param $for - use this patameter when you want to get votes for something else than posts.
 */

function comments_count($content_id, $is_moderated = false, $for = 'post') {
	$content_id = intval ( $content_id );
	if ($content_id == 0) {
		return false;
	}
	
	 // $CI = get_instance ();
	
	$to_table =  get_instance()->core_model->guessDbTable ( $for );
	
	$qty =  get_instance()->comments_model->commentsGetCount ( $to_table, $content_id, $is_moderated );
	return $qty;

}

/**
 * comment_post_form
 *
 * @desc comment_post_form
 * @access      public
 * @category    posts
 * @author      Microweber
 * @link
 * @param  $content_id = $post['id']; the id of the content //if false and you read a post it will use the post's id
 * @param  $display = 'default' //prints a the default comments form
 * @param $for - use this patameter when you want to get comment form for something else than posts.

 */

function comment_post_form($content_id = false, $display = 'default', $for = 'post', $display_params = array()) {
	 // $CI = get_instance ();
	if ($display) {
		$list_file = $display;
		$display = strtolower ( $display );
	
	}
	
	if ($content_id == false) {
		$post =  get_instance()->template ['post'];
		if (! empty ( $post )) {
			$content_id = $post ['id'];
		}
	}
	
	$to_table =  get_instance()->core_model->guessDbTable ( $for );
	
	 get_instance()->template ['comment_to_id'] = $content_id;
	 get_instance()->template ['comment_to_table'] = $to_table;
	 get_instance()->load->vars (  get_instance()->template );
	switch ($display) {
		
		case 'default' :
			$f = DEFAULT_TEMPLATE_DIR . 'blocks/comments/form_default.php';
			$f = normalize_path ( $f, false );
			$content_filename =  get_instance()->load->file ( $f, true );
			print ($content_filename) ;
			break;
		
		case false :
		default :
			$list_file = $display;
			
			$f = TEMPLATE_DIR . $list_file;
			$f = normalize_path ( $f, false );
			
			if (is_file ( $f )) {
				$content_filename =  get_instance()->load->file ( $f, true );
				print $content_filename;
			} else {
				$file = DEFAULT_TEMPLATE_DIR . 'blocks/comments/form_default.php';
				$newfile = TEMPLATE_DIR . $list_file;
				$file = normalize_path ( $file, false );
				$newfile = normalize_path ( $newfile, false );
				if (! copy ( $file, $newfile )) {
					//echo "failed to copy $file...\n";
				} else {
					$content_filename =  get_instance()->load->file ( TEMPLATE_DIR . $list_file, true );
					print $content_filename;
				}
			}
			break;
	}
	
	return false;

}

function inc($fn) {
	 // $CI = get_instance ();
	$fn1 = TEMPLATE_DIR . $fn;
	if (is_file ( $fn1 )) {
		$fn = TEMPLATE_DIR . $fn;
	} else {
	
	}
	 get_instance()->load->vars (  get_instance()->template );
	print '<!-- mw-file-start:{' . $fn . '} -->' . "\n";
	$content_filename =  get_instance()->load->file ( $fn, true );
	//$content_filename =  get_instance()->load->view($fn, '', true);
	print ($content_filename) ;
	print '<!-- mw-file-end:{' . $fn . '} -->' . "\n";
}

/**
 * comments_list
 *
 * @desc comments_list
 * @access      public
 * @category    posts
 * @author      Microweber
 * @link
 * @param  $content_id = $post['id']; the id of the content //if false and you read a post it will use the post's id
 * @param  $display = 'default' //prints a the default comments form
 * @param $for - use this patameter when you want to get comment form for something else than posts.

 */

function comments_list($content_id = false, $display = 'default', $for = 'post', $display_params = array()) {
	 // $CI = get_instance ();
	if ($display) {
		$list_file = $display;
		$display = strtolower ( $display );
	
	} else {
		//	$display = 'default'
	}
	
	if ($content_id == false) {
		$post =  get_instance()->template ['post'];
		if (! empty ( $post )) {
			$content_id = $post ['id'];
		}
	}
	
	$to_table =  get_instance()->core_model->guessDbTable ( $for );
	//	p($to_table);
	 get_instance()->template ['comment_to_id'] = $content_id;
	 get_instance()->template ['comment_to_table'] = $to_table;
	
	//	$comm = commentsGetForContentId($id, $is_moderated = false);
	if ($to_table == false) {
		$to_table = 'table_content';
	}
	
	$comments = array ();
	$comments ['to_table'] = $to_table;
	$comments ['to_table_id'] = $content_id;
	//	p($comments);
	$comments = $this->comments_model->commentsGet ( $comments, $limit = false, $count_only = false, $orderby = array ('id', 'desc' ) );
	//p($comments);
	

	 get_instance()->template ['comments'] = $comments;
	 get_instance()->load->vars (  get_instance()->template );
	switch ($display) {
		
		case 'default' :
			
			$content_filename =  get_instance()->load->file ( DEFAULT_TEMPLATE_DIR . 'blocks/comments/list_default.php', true );
			
			$content_filename =  get_instance()->template_model->parseMicrwoberTags ( $content_filename );
			
			print ($content_filename) ;
			break;
		
		case false :
		default :
			$list_file = $display;
			
			$list_file = normalize_path ( $list_file, false );
			
			if (is_file ( $list_file )) {
				$list_file_load = $list_file;
			} else {
				$list_file_load = TEMPLATE_DIR . $list_file;
			}
			$list_file_load = normalize_path ( $list_file_load, false );
			if (is_file ( $list_file_load )) {
				$content_filename =  get_instance()->load->file ( $list_file_load, true );
				$content_filename =  get_instance()->template_model->parseMicrwoberTags ( $content_filename );
				print $content_filename;
			} else {
				
				$file = DEFAULT_TEMPLATE_DIR . 'blocks/comments/list_default.php';
				
				$file = normalize_path ( $file, false );
				
				$newfile = TEMPLATE_DIR . $list_file;
				$newfile = normalize_path ( $newfile, false );
				
				if (! copy ( $file, $newfile )) {
					//echo "failed to copy $file...\n";
				} else {
					$content_filename =  get_instance()->load->file ( TEMPLATE_DIR . $list_file, true );
					$content_filename =  get_instance()->template_model->parseMicrwoberTags ( $content_filename );
					print $content_filename;
				}
			}
			
			break;
	}

}

function paging_prepare($total_results = 1, $results_per_page = 1) {
	 // $CI = get_instance ();
	$pages_count = @ceil ( intval ( $total_results ) / intval ( $results_per_page ) );
	
	$param =  get_instance()->content_model->pagingPrepareUrls ( url (), $pages_count, $paging_param = 'curent_page', $keyword_param = 'keyword' );
	// p($param); 
	return $param;
}
function url_param($param, $skip_ajax = false) {
	 
	  // $CI = get_instance ();

	$param =  get_instance()->core_model->getParamFromURL ( $param, $param_sub_position = false, $skip_ajax );
	return $param;

}

function loop($array_of_data, $file, $array_variable_name = 'data') {
	 // $CI = get_instance ();
	
	$list_file = $file;
	
	if (is_file ( $list_file )) {
	
	} else {
		$list_file = TEMPLATE_DIR . $list_file;
	}
	
	if (is_file ( $list_file )) {
		if (! empty ( $array_of_data )) {
			foreach ( $array_of_data as $data ) {
				 get_instance()->template [$array_variable_name] = $data;
				 get_instance()->load->vars (  get_instance()->template );
				$content_filename =  get_instance()->load->file ( $list_file, true );
				print $content_filename;
			}
		}
	}

}
function global_include($script_path) {
	// check if the file to include exists:
	if (isset ( $script_path ) && is_file ( $script_path )) {
		// extract variables from the global scope:
		extract ( $GLOBALS, EXTR_REFS );
		ob_start ();
		include ($script_path);
		return ob_get_clean ();
	} else {
		ob_clean ();
		trigger_error ( 'The script to parse in the global scope was not found' );
	}
}

function get_ref_page() {
	$ref_page = $_SERVER ['HTTP_REFERER'];
	
	if ($ref_page != '') {
		$page =  get_instance()->content_model->getPageByURLAndCache ( $ref_page );
		return $page;
	}
	return false;

}

function get_ref_post() {
	$ref_page = $_SERVER ['HTTP_REFERER'];
	//p($ref_page);
	 // $CI = get_instance ();
	if ($ref_page != '') {
		$page =  get_instance()->content_model->getContentByURL ( $ref_page );
		return $page;
	}
	return false;

}

function get_ref_category() {
	$ref_page = $_SERVER ['HTTP_REFERER'];
	//p($ref_page);
	
	if ($ref_page != '') {
		 // $CI = get_instance ();
		$page =  get_instance()->content_model->getContentByURL ( $ref_page );
		return $page;
	}
	return false;

}

function _e($str) {
	//	setlocale ( LC_MESSAGES, 'es_ES.utf8' );
	// run ok with LC_MESSAGES
	

	// Specify location of translation tables
	//bindtextdomain ( "myAppPhp", ROOTPATH .DIRECTORY_SEPARATOR .APPPATH. "/language" );
	//bind_textdomain_codeset ( "myAppPhp", 'UTF-8' );
	// It's very important
	

	//print ROOTPATH .DIRECTORY_SEPARATOR .APPPATH . "/language";
	// Choose domain
	//textdomain ( "myAppPhp" );
	// Current locale settings
	//echo "Current i18n:" . setlocale ( LC_ALL, 0 ) . "\n\n";
	

	// i18n support information here
	//$language = 'en_US';
	//$newLocale = setlocale ( LC_ALL, $language );
	//echo "After i18n:$newLocale\n\n";
	

	// Set the text domain as 'messages'
	//$domain = 'messages';
	//bindtextdomain ( $domain, APPPATH. DIRECTORY_SEPARATOR ."language" );
	//textdomain ( $domain );
	

	//	echo gettext ( $str );
	echo ($str);
}

require (APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'users.php');
require (APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'dashboard.php');
require (APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'cart.php');
require (APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'parser.php');
