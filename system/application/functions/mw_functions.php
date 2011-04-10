<?php
function url_string($string) {
	
	$string = CI::model ( 'core' )->url_title ( $string );
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
		
		$tags = extract_tags ( $html_to_save, 'style', $selfclosing = false, $return_the_entire_tag = true, $charset = 'UTF-8' );
		
		$matches = $tags;
		if (! empty ( $matches )) {
			foreach ( $matches as $m ) {
				$html_to_save = str_replace ( $m ['full_tag'], '', $html_to_save );
			}
		}
	
	}
	$html_to_save = str_replace ( 'class="exec"', '', $html_to_save );
	$html_to_save = str_replace ( 'style=""', '', $html_to_save );
	$html_to_save = preg_replace ( '/<!--(.*)-->/Uis', '', $html_to_save );
	
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
	//global $CI;
	

	$var = json_encode ( $var );
	//print $var;
	if ($print == true) {
		print $var;
	} else {
		return $var;
	}
}

function mw_get_var($var) {
	//global $CI;
	$var = json_decode ( $var );
	if ($var != false) {
		
		$var = objectToArray ( $var );
	
	}
	//print $var;
	return $var;
}

function template_var($var) {
	global $CI;
	$var = $CI->template [$var];
	return $var;
}
function app_var($k, $new_value) {
	if ($k == false) {
		return false;
	}
	
	global $CI;
	if ($k == true and ! isset ( $new_value )) {
		//return $CI->appvar->get ( $k );
	} else {
		//$CI->appvar->set ( $k, $new_value );
	}

}

function add_post_form($params) {
	
	$display = $params ['display'];
	
	global $CI;
	
	if (! $display) {
		$display = 'default';
	}
	$list_file = trim ( $display );
	if (stristr ( $list_file, '.php' ) == false) {
		$try_file_template_dir = TEMPLATE_DIR . $list_file . '.php';
	} else {
		$try_file_template_dir = TEMPLATE_DIR . $list_file . '';
	}
	
	if (is_file ( $try_file_template_dir ) == false) {
		$try_file1 = TEMPLATE_DIR . 'modules/posts/views/' . $list_file . '.php';
		$try_file = TEMPLATE_DIR . 'modules/posts/views/' . '_add_post_default' . '.php';
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
		
		/*		$more = false;
		$more = CI::model('core')->getCustomFields ( 'table_content', $the_post ['id'] );
		$the_post ['custom_fields'] = $more;
		
		$CI->template ['the_post'] = $the_post;
		$CI->template ['post'] = $the_post;
		*/
		foreach ( $params as $k => $v ) {
			$CI->template ['$k'] = $v;
		}
		
		if ($params ['id']) {
			$the_post = get_post ( $params ['id'] );
			if (($the_post ['created_by']) == user_id ()) {
				$CI->template ['the_post'] = $the_post;
			}
		}
		
		$CI->template ['params'] = $params;
		$CI->load->vars ( $CI->template );
		
		$content_filename = $CI->load->file ( $try_file1, true );
		print $content_filename;
	
	}
	
//return $post;
}
function get_custom_fields($content_id) {
	return get_custom_fields_for_content ( $content_id );
}

function get_custom_fields_for_content($content_id) {
	
	$more = false;
	$more = CI::model ( 'core' )->getCustomFields ( 'table_content', $content_id, true );
	
	return $more;

}

function get_post($id, $display = false) {
	if (intval ( $id ) == 0) {
		return false;
	}
	global $CI;
	$post = CI::model ( 'content' )->contentGetByIdAndCache ( $id );
	if ($display == false) {
		$more = false;
		$more = CI::model ( 'core' )->getCustomFields ( 'table_content', $post ['id'] );
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
			$more = CI::model ( 'core' )->getCustomFields ( 'table_content', $the_post ['id'] );
			$the_post ['custom_fields'] = $more;
			
			$CI->template ['the_post'] = $the_post;
			$CI->template ['post'] = $the_post;
			$CI->template ['data'] = $the_post;
			$CI->load->vars ( $CI->template );
			
			$content_filename = $CI->load->file ( $try_file1, true );
			print $content_filename;
		
		} else {
			$more = false;
			$more = CI::model ( 'core' )->getCustomFields ( 'table_content', $post ['id'] );
			$post ['custom_fields'] = $more;
			return $post;
		}
	
	}
	$more = false;
	$more = CI::model ( 'core' )->getCustomFields ( 'table_content', $post ['id'] );
	$post ['custom_fields'] = $more;
	return $post;
}
function get_pages($params = array()) {
	global $CI;
	
	$params ['content_type'] = 'page';
	$posts = CI::model ( 'content' )->contentGetByParams ( $params );
	
	return $posts ['posts'];

}
function get_pages_old($params = array()) {
	global $CI;
	
	$params ['content_type'] = 'page';
	$posts = CI::model ( 'content' )->contentGetByParams ( $params );
	$to_return = array ();
	return $posts;
	/*	foreach ( $posts as $the_post ) {
 
		$more = false;
		$more = CI::model('core')->getCustomFields ( 'table_content', $the_post ['id'] );
		$the_post ['custom_fields'] = $more;
	//	$to_return [] = $the_post;

	}
	return $to_return;*/
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
    
    
 * 
 * 
  
 */

function get_posts($params = array()) {
	
	global $CI;
	if ($params ['display']) {
		$list_file = $params ['display'];
		unset ( $params ['display'] );
	}
	
	if ($params ['file']) {
		$file = $params ['file'];
		unset ( $params ['file'] );
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
	
	if ($data == false) {
		if (is_array ( $params ['selected_categories'] )) {
		
		} else {
			if (($params ['selected_categories']) == 'all') {
				
				unset ( $params ['selected_categories'] );
			} else {
				$params ['selected_categories'] = $CI->template ['active_categories'];
				if ($params ['selected_categories'] == false) {
					$try_post = $CI->template ['post'];
					
					if (! empty ( $try_post )) {
						$try = CI::model ( 'taxonomy' )->getCategoriesForContent ( $try_post ['id'], $return_only_ids = true );
						
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
			
			$posts = CI::model ( 'content' )->contentGetByParams ( $params );
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
			$posts = $CI->template ['posts'];
		}
	} else {
		$posts = $data;
		//p($data);
	}
	
	if (! empty ( $posts )) {
		$posts_cf = array ();
		foreach ( $posts as $post ) {
			//$more = false;
			//$more = CI::model ( 'core' )->getCustomFields ( 'table_content', $post ['id'] );
			//$post ['custom_fields'] = $more;
			$posts_cf [] = $post;
		}
		$posts = $posts_cf;
	}
	
	if ($file != false) {
		
		$try_file1 = TEMPLATE_DIR . 'modules/posts/views/' . $file . '.php';
		
		if (is_file ( $try_file1 )) {
			$CI->template ['posts'] = $posts;
			$CI->load->vars ( $CI->template );
			
			$content_filename = $CI->load->file ( $try_file1, true );
			print $content_filename;
		} else {
			$try_file1 = TEMPLATE_DIR . '' . $file . '.php';
			
			if (is_file ( $try_file1 )) {
				$CI->template ['posts'] = $posts;
				$CI->load->vars ( $CI->template );
				
				$content_filename = $CI->load->file ( $try_file1, true );
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
						$more = CI::model ( 'core' )->getCustomFields ( 'table_content', $the_post ['id'] );
						$the_post ['custom_fields'] = $more;
					}
					$CI->template ['the_post'] = $the_post;
					$CI->load->vars ( $CI->template );
					
					$content_filename = $CI->load->file ( $try_file1, true );
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
	global $CI;
	if ($display) {
		$list_file = $display;
		$display = strtolower ( $display );
	
	}
	if ($data == false) {
		$posts_pages_links = $CI->template ['posts_pages_links'];
	} else {
		$posts_pages_links = $data;
	}
	//
	switch ($display) {
		case 'default' :
			$CI->template ['posts_pages_links'] = $posts_pages_links;
			$CI->load->vars ( $CI->template );
			$content_filename = $CI->load->file ( DEFAULT_TEMPLATE_DIR . 'blocks/nav/default.php', true );
			print ($content_filename) ;
			break;
		
		case 'divs' :
			$CI->template ['posts_pages_links'] = $posts_pages_links;
			$CI->load->vars ( $CI->template );
			
			$content_filename = $CI->load->file ( DEFAULT_TEMPLATE_DIR . 'blocks/nav/divs.php', true );
			
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
	global $CI;
	$link = CI::model ( 'content' )->contentGetHrefForPostId ( $id );
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
function page_link($id) {
	
	global $CI;
	$link = CI::model ( 'content' )->getContentURLByIdAndCache ( $id );
	if (strval ( $link ) == '') {
		$link = CI::model ( 'content' )->getContentByURL ( $id );
		$link = CI::model ( 'content' )->getContentURLByIdAndCache ( $link ['id'] );
	}
	return $link;

}

function menu_tree($menu_id, $max_depth = false) {
	
	$menu_items = CI::model ( 'content' )->menuTree ( $menu_id, $max_depth );
	
	print $menu_items;
}

function get_page_for_post($post_id) {
	
	$url = post_link ( $post_id );
	
	$page = CI::model ( 'content' )->getPageByURLAndCache ( $url );
	//p($url_page);
	return $page;

}
function get_content($id) {
	global $CI;
	$page = CI::model ( 'content' )->contentGetById ( $id );
	
	if (empty ( $page )) {
		$page = CI::model ( 'content' )->getContentByURL ( $id );
	}
	
	if (! empty ( $page )) {
		$more = false;
		$more = CI::model ( 'core' )->getCustomFields ( 'table_content', $page ['id'] );
		$page ['custom_fields'] = $more;
	}
	return $page;
}
function get_page($id) {
	
	global $CI;
	if (intval ( $id ) != 0) {
		$page = CI::model ( 'content' )->contentGetById ( $id );
		
		if (empty ( $page )) {
			$page = CI::model ( 'content' )->getContentByURL ( $id );
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
		$more = CI::model ( 'core' )->getCustomFields ( 'table_content', $page ['id'] );
		$page ['custom_fields'] = $more;
	}
	
	return $page;
	//$link = CI::model('content')->getContentURLByIdAndCache ( $link['id'] );


}

function page_save($data) {
	global $CI;
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
				$data ['content_url'] = CI::model ( 'core' )->url_title ( $data ['content_url'] );
			}
		}
		if (empty ( $errors )) {
			$to_save = array ();
			$to_save = $data;
			$to_save ['content_type'] = 'page';
			$to_save ['content_parent'] = intval ( $to_save ['content_parent'] );
			
			$saved = CI::model ( 'content' )->saveContent ( $to_save );
			
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
	global $CI;
	
	if ($data) {
		
		$categories_ids_to_remove = array ();
		$categories_ids_to_remove ['taxonomy_type'] = 'category';
		$categories_ids_to_remove ['users_can_create_content'] = 'n';
		$categories_ids_to_remove = CI::model ( 'taxonomy' )->getIds ( $categories_ids_to_remove, $orderby = false );
		//$CI->template ['categories_ids_to_remove'] = $categories_ids_to_remove;
		

		$categories_ids_to_add = array ();
		$categories_ids_to_add ['taxonomy_type'] = 'category';
		$categories_ids_to_add ['users_can_create_content'] = 'y';
		$categories_ids_to_add = CI::model ( 'taxonomy' )->getIds ( $categories_ids_to_add, $orderby = false );
		//$CI->template ['categories_ids_to_add'] = $categories_ids_to_add;
		

		$errors = array ();
		$categories = $data ['taxonomy_categories'];
		
		if (strstr ( $categories, ',' )) {
			$data ['taxonomy_categories'] = explode ( ',', $data ['taxonomy_categories'] );
			$categories = $data ['taxonomy_categories'];
			$categories = array_unique ( $categories );
		}
		
		//var_Dump($categories);
		

		$user_id = is_admin ();
		if ($user_id == false) {
			if (! empty ( $categories )) {
				
				foreach ( $categories as $cat ) {
					$cat = CI::model ( 'taxonomy' )->getIdByName ( $cat );
					$parrent_cats = CI::model ( 'taxonomy' )->getParents ( $cat );
					
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
			
			$check_title = CI::model ( 'content' )->getContent ( $check_title, $orderby = false, $limit = false, $count_only = false );
			
			$check_title_error = false;
			
			$taxonomy_categories = array ($category );
			
			$taxonomy = CI::model ( 'taxonomy' )->getParents ( $category );
			
			if (! empty ( $taxonomy )) {
				
				foreach ( $taxonomy as $i ) {
					$i = CI::model ( 'taxonomy' )->getIdByName ( $i );
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
					$parent_page = CI::model ( 'content' )->contentsGetTheLastBlogSectionForCategory ( $cat );
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
				
				//p ( $to_save );
				

				$saved = CI::model ( 'content' )->saveContent ( $to_save );
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
   @example 	Use <? print post_thumbnail($post['id']); ?>
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
	global $CI;
	
	if (! isset ( $params ['size'] )) {
		$params ['size'] = 200;
	}
	
	$thumb = CI::model ( 'content' )->contentGetThumbnailForContentId ( $params ['id'], $params ['size'] );
	
	return $thumb;
}

function post_pictures($post_id, $size = 128) {
	if (intval ( $post_id ) == 0) {
		return false;
	}
	
	global $CI;
	$thumb = CI::model ( 'core' )->mediaGetImages ( $to_table = 'table_content', $post_id, $size, $order = "ASC" );
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
	
	global $CI;
	
	$content_parent = ($params ['content_parent']) ? $params ['content_parent'] : $params ['content_subtype_value'];
	$link = ($params ['link']) ? $params ['link'] : false;
	
	if ($link == false) {
		$link = "<a href='{taxonomy_url}' >{taxonomy_value}</a>";
	}
	
	$actve_ids = ($params ['actve_ids']) ? $params ['actve_ids'] : false;
	$active_code = ($params ['active_code']) ? $params ['active_code'] : false;
	$remove_ids = ($params ['remove_ids']) ? $params ['remove_ids'] : false;
	$removed_ids_code = ($params ['removed_ids_code']) ? $params ['removed_ids_code'] : false;
	$ul_class_name = ($params ['ul_class_name']) ? $params ['ul_class_name'] : false;
	$include_first = ($params ['include_first']) ? $params ['include_first'] : false;
	$content_type = ($params ['content_type']) ? $params ['content_type'] : false;
	$add_ids = ($params ['add_ids']) ? $params ['add_ids'] : false;
	$orderby = ($params ['orderby']) ? $params ['orderby'] : false;
	
	if ($params ['for_page'] != false) {
		//	p($params);
		$page = get_page ( $params ['for_page'] );
		//p($page);
		$content_parent = $page ['content_subtype_value'];
		//$categories = CI::model ( 'taxonomy' )->getCategoriesForContent ( $content_id = $params ['for_content'], $return_only_ids = true );
	}
	if ($params ['content_subtype_value'] != false) {
		$content_parent = $params ['content_subtype_value'];
	}
	
	//$content_parent, $link = false, $actve_ids = false, $active_code = false, $remove_ids = false, $removed_ids_code = false, $ul_class_name = false, $include_first = false, $content_type = false, $add_ids = false, $orderby = false, $only_with_content = false
	CI::model ( 'content' )->content_helpers_getCaregoriesUlTreeAndCache ( $content_parent, $link, $actve_ids, $active_code, $remove_ids, $removed_ids_code, $ul_class_name, $include_first, $content_type, $add_ids, $orderby, $only_with_content );

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
   $get_categories_params = array(); 
   //params for categories
    $get_categories_params['parent'] = false; //begin from this parent category
    $get_categories_params['get_only_ids'] = false; //if true will return only the category ids
    $get_categories_params['inclide_main_category'] = false; //if true will include the main category too
    $get_categories_params['for_content'] = false; //if integer - will get the categories for given content it (post)
      
    
 */

function get_categories($get_categories_params = array()) {
	$params = $get_categories_params;
	//p($params);
	global $CI;
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
		$categories = CI::model ( 'taxonomy' )->getChildrensRecursiveAndCache ( $page ['content_subtype_value'], $type = 'category', $visible_on_frontend = false );
		
	//$categories = CI::model ( 'taxonomy' )->getCategoriesForContent ( $content_id = $params ['for_content'], $return_only_ids = true );
	} else {
		
		if ($params ['for_content'] != false) {
			$categories = CI::model ( 'taxonomy' )->getCategoriesForContent ( $content_id = $params ['for_content'], $return_only_ids = true );
		} else {
			
			if ($params ['parent'] == false) {
				$page = $CI->template ['page'];
				if (! empty ( $page )) {
					$the_category = $page ['content_subtype_value'];
					$categories = CI::model ( 'taxonomy' )->getChildrensRecursiveAndCache ( $the_category, $type = 'category', $visible_on_frontend = false );
				} else {
					$the_category = 0;
					$categories = CI::model ( 'taxonomy' )->getChildrensRecursiveAndCache ( $the_category, $type = 'category', $visible_on_frontend = false );
				
				}
			} else {
				$the_category = $params ['parent'];
				
				$categories = CI::model ( 'taxonomy' )->getChildrensRecursiveAndCache ( $the_category, $type = 'category', $visible_on_frontend = false );
			
			}
		
		}
	}
	$cats = array ();
	foreach ( $categories as $category ) {
		$category_id = $category;
		if ($get_only_ids == false) {
			$temp = CI::model ( 'taxonomy' )->getSingleItem ( $category );
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

function get_category($category_id) {
	global $CI;
	
	$category_id = CI::model ( 'taxonomy' )->getIdByName ( $category_id );
	$c = CI::model ( 'taxonomy' )->getSingleItem ( $category_id );
	if (! empty ( $c )) {
		$more = false;
		$more = CI::model ( 'core' )->getCustomFields ( 'table_taxonomy', $category_id );
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
	global $CI;
	$qty = CI::model ( 'taxonomy' )->getChildrensCount ( $category_id );
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
	global $CI;
	$url = CI::model ( 'content' )->taxonomyGetUrlForId ( $category_id );
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
	global $CI;
	$active_categories = $CI->template ['active_categories'];
	
	if ($active_categories == false) {
		$try_post = $CI->template ['post'];
		
		if (! empty ( $try_post )) {
			$try = CI::model ( 'taxonomy' )->getCategoriesForContent ( $try_post ['id'], $return_only_ids = true );
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
	
	global $CI;
	
	$to_table = CI::model ( 'core' )->guessDbTable ( $for );
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
	
	global $CI;
	
	$to_table = CI::model ( 'core' )->guessDbTable ( $for );
	
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
	
	global $CI;
	
	$to_table = CI::model ( 'core' )->guessDbTable ( $for );
	
	$string1 = CI::model ( 'core' )->securityEncryptString ( $to_table );
	$string2 = CI::model ( 'core' )->securityEncryptString ( $content_id );
	
	$return = "javascript:mw.content.Vote('$string1','$string2', '$counter_selector');";
	
	return $return;

}

function sess_id() {
	$session_id = CI::library ( 'session' )->userdata ( 'session_id' );
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
	
	global $CI;
	
	$quick_nav = CI::model ( 'content' )->getBreadcrumbsByURLAsArray ( $the_url = false, $include_home = false, $options = array () );
	
	$CI->template ['quick_nav'] = $quick_nav;
	$CI->template ['seperator'] = $seperator;
	$CI->load->vars ( $CI->template );
	switch ($seperator) {
		
		case 'ul' :
			
			$content_filename = $CI->load->file ( DEFAULT_TEMPLATE_DIR . 'blocks/nav/breadcrumbs_ul.php', true );
			print ($content_filename) ;
			break;
		
		case false :
		default :
			$content_filename = $CI->load->file ( DEFAULT_TEMPLATE_DIR . 'blocks/nav/breadcrumbs_default.php', true );
			print ($content_filename) ;
			break;
			break;
	}
	
	return $quick_nav;

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
function get_media($id, $for = 'post', $media_type = false, $queue_id = false) {
	$content_id = intval ( $id );
	if ($content_id == 0 and $queue_id == false) {
		return false;
	}
	
	if ($content_id == 0 and $queue_id != false) {
		$content_id = false;
	}
	
	if ($content_id != 0 and $queue_id != false) {
		$queue_id = false;
	}
	
	global $CI;
	
	$to_table = CI::model ( 'core' )->guessDbTable ( $for );
	//var_dump($to_table, $content_id);
	$media = CI::model ( 'core' )->mediaGet ( $to_table, $content_id, $media_type, $order = "ASC", $queue_id, $no_cache = false, $id = false );
	return $media;
	// p($media);


//p($content_id);


}

function get_media_thumbnail($id, $size_width = 128, $size_height = false) {
	$media = CI::model ( 'core' )->mediaGetThumbnailForMediaId ( $id, $size_width, $size_height );
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
	
	global $CI;
	
	$to_table = CI::model ( 'core' )->guessDbTable ( $for );
	
	$qty = CI::model ( 'comments' )->commentsGetCount ( $to_table, $content_id, $is_moderated );
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
	global $CI;
	if ($display) {
		$list_file = $display;
		$display = strtolower ( $display );
	
	}
	
	if ($content_id == false) {
		$post = $CI->template ['post'];
		if (! empty ( $post )) {
			$content_id = $post ['id'];
		}
	}
	
	$to_table = CI::model ( 'core' )->guessDbTable ( $for );
	
	$CI->template ['comment_to_id'] = $content_id;
	$CI->template ['comment_to_table'] = $to_table;
	$CI->load->vars ( $CI->template );
	switch ($display) {
		
		case 'default' :
			
			$content_filename = $CI->load->file ( DEFAULT_TEMPLATE_DIR . 'blocks/comments/form_default.php', true );
			print ($content_filename) ;
			break;
		
		case false :
		default :
			$list_file = $display;
			if (is_file ( TEMPLATE_DIR . $list_file )) {
				$content_filename = $CI->load->file ( TEMPLATE_DIR . $list_file, true );
				print $content_filename;
			} else {
				$file = DEFAULT_TEMPLATE_DIR . 'blocks/comments/form_default.php';
				$newfile = TEMPLATE_DIR . $list_file;
				
				if (! copy ( $file, $newfile )) {
					//echo "failed to copy $file...\n";
				} else {
					$content_filename = $CI->load->file ( TEMPLATE_DIR . $list_file, true );
					print $content_filename;
				}
			}
			break;
	}
	
	return false;

}

function inc($fn) {
	global $CI;
	$fn1 = TEMPLATE_DIR . $fn;
	if (is_file ( $fn1 )) {
		$fn = TEMPLATE_DIR . $fn;
	} else {
	
	}
	$CI->load->vars ( $CI->template );
	print '<!-- mw-file-start:{' . $fn . '} -->' . "\n";
	$content_filename = $CI->load->file ( $fn, true );
	//$content_filename = $CI->load->view($fn, '', true);
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
	global $CI;
	if ($display) {
		$list_file = $display;
		$display = strtolower ( $display );
	
	} else {
		//	$display = 'default'
	}
	
	if ($content_id == false) {
		$post = $CI->template ['post'];
		if (! empty ( $post )) {
			$content_id = $post ['id'];
		}
	}
	
	$to_table = CI::model ( 'core' )->guessDbTable ( $for );
	//	p($to_table);
	$CI->template ['comment_to_id'] = $content_id;
	$CI->template ['comment_to_table'] = $to_table;
	
	//	$comm = commentsGetForContentId($id, $is_moderated = false);
	if ($to_table == false) {
		$to_table = 'table_content';
	}
	
	$comments = array ();
	$comments ['to_table'] = $to_table;
	$comments ['to_table_id'] = $content_id;
	//	p($comments);
	$comments = CI::model ( 'comments' )->commentsGet ( $comments, $limit = false, $count_only = false, $orderby = array ('id', 'desc' ) );
	//p($comments);
	

	$CI->template ['comments'] = $comments;
	$CI->load->vars ( $CI->template );
	switch ($display) {
		
		case 'default' :
			
			$content_filename = $CI->load->file ( DEFAULT_TEMPLATE_DIR . 'blocks/comments/list_default.php', true );
			
			$content_filename = CI::model ( 'template' )->parseMicrwoberTags ( $content_filename );
			
			print ($content_filename) ;
			break;
		
		case false :
		default :
			$list_file = $display;
			
			if (is_file ( $list_file )) {
				$list_file_load = $list_file;
			} else {
				$list_file_load = TEMPLATE_DIR . $list_file;
			}
			
			if (is_file ( $list_file_load )) {
				$content_filename = $CI->load->file ( $list_file_load, true );
				$content_filename = CI::model ( 'template' )->parseMicrwoberTags ( $content_filename );
				print $content_filename;
			} else {
				
				$file = DEFAULT_TEMPLATE_DIR . 'blocks/comments/list_default.php';
				$newfile = TEMPLATE_DIR . $list_file;
				
				if (! copy ( $file, $newfile )) {
					//echo "failed to copy $file...\n";
				} else {
					$content_filename = $CI->load->file ( TEMPLATE_DIR . $list_file, true );
					$content_filename = CI::model ( 'template' )->parseMicrwoberTags ( $content_filename );
					print $content_filename;
				}
			}
			
			break;
	}

}

function url_param($param, $skip_ajax = false) {
	global $CI;
	
	$param = CI::model ( 'core' )->getParamFromURL ( $param, $param_sub_position = false, $skip_ajax );
	return $param;

}

function loop($array_of_data, $file, $array_variable_name = 'data') {
	global $CI;
	
	$list_file = $file;
	
	if (is_file ( $list_file )) {
	
	} else {
		$list_file = TEMPLATE_DIR . $list_file;
	}
	
	if (is_file ( $list_file )) {
		if (! empty ( $array_of_data )) {
			foreach ( $array_of_data as $data ) {
				$CI->template [$array_variable_name] = $data;
				$CI->load->vars ( $CI->template );
				$content_filename = $CI->load->file ( $list_file, true );
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
		$page = CI::model ( 'content' )->getPageByURLAndCache ( $ref_page );
		return $page;
	}
	return false;

}

function get_ref_post() {
	$ref_page = $_SERVER ['HTTP_REFERER'];
	//p($ref_page);
	if ($ref_page != '') {
		$page = CI::model ( 'content' )->getContentByURL ( $ref_page );
		return $page;
	}
	return false;

}

function get_ref_category() {
	$ref_page = $_SERVER ['HTTP_REFERER'];
	//p($ref_page);
	if ($ref_page != '') {
		$page = CI::model ( 'content' )->getContentByURL ( $ref_page );
		return $page;
	}
	return false;

}

require (APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'users.php');
require (APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'dashboard.php');
require (APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'cart.php');

 