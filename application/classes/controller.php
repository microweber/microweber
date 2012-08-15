<?php
// Controller Class
class Controller {
	function index() {
		$page_url = url_string ();
		$page_url = rtrim ( $page_url, '/' );
		
		$is_editmode = url_param ( 'editmode' );
		if ($is_editmode) {
			$editmode_sess = session_get ( 'editmode' );
			
			$page_url = url_param_unset ( 'editmode', $page_url );
			if (is_admin () == true) {
				if ($editmode_sess == false) {
					session_set ( 'editmode', true );
				}
				// safe_redirect ( site_url ( $page_url ) );
				exit ();
			} else {
				$is_editmode = false;
			}
		}
		$is_editmode = session_get ( 'editmode' );
		
		if (trim ( $page_url ) == '') {
			//
			$page = get_homepage ();
		} else {
			
			$page = get_page_by_url ( $page_url );
			
			if (empty ( $page )) {
				$page = get_homepage ();
			}
		}
		// d($page);
		if ($page ['content_type'] == "post") {
			$content = $page;
			$page = get_content_by_id ( $page ['content_parent'] );
		} else {
			$content = $page;
		}
		// d($page);
		define_constants ( $content );
		
		$render_file = get_layout_for_page ( $page );
		
		// d ( $render_file );
		
		if ($render_file) {
			$l = new View ( $render_file );
			// $this->content = $content;
			// var_dump($l);
			
			// $l->set ( $this );
			$l = $l->__toString ();
			
			if ($is_editmode == true) {
				$is_admin = is_admin ();
				if ($is_admin == true) {
					
					$tb = INCLUDES_DIR . DS . 'toolbar' . DS . 'toolbar.php';
					
					$layout_toolbar = new View ( $tb );
					$layout_toolbar = $layout_toolbar->__toString ();
					if ($layout_toolbar != '') {
						$l = str_replace ( '<body>', '<body>' . $layout_toolbar, $l );
					}
				}
			}
			
			// var_dump($l);
			$l = parse_micrwober_tags ( $l, $options = false );
			print $l;
			exit ();
		} else {
			print 'NO LAYOUT IN ' . __FILE__;
			d ( $template_view );
			d ( $page );
			exit ();
		}
		// var_dump ( $page );
		// var_dump($ab);
	}
	function show_404() {
		header ( "HTTP/1.0 404 Not Found" );
		$v = new View ( ADMIN_VIEWS_PATH . '404.php' );
		echo $v;
	}
	function admin() {
		define_constants ();
		$l = new View ( ADMIN_VIEWS_PATH . 'admin.php' );
		$l = $l->__toString ();
		// var_dump($l);
		$layout = parse_micrwober_tags ( $l, $options = false );
		print $layout;
		exit ();
	}
	function api() {
		define_constants ();
		$api_exposed = '';
		
		// user functions
		$api_exposed .= 'user_login, user_logout,';
		
		// content functions
		$api_exposed = 'save_field,'; 
		
		
		$api_exposed = explode ( ',', $api_exposed );
		$api_exposed = array_trim ( $api_exposed );
		
		$api_function = url ( 1 ) ?  : 'index';
		 
		if ($api_function == 'module') {
			$this->module ();
		} else {
			if (in_array ( $api_function, $api_exposed )) {
				if (function_exists ( $api_function )) {
					$res = $api_function ( $_POST );
					print json_encode ( $res );
				} else {
					error ( 'The api function does not exist', __FILE__, __LINE__ );
				}
				
				// print $api_function;
			} else {
				error ( 'The api function is not defined in the allowed functions list' );
			}
			exit ();
		}
		// exit ( $api_function );
	}
	function module() {
		$page = false;
		if (isset($_SERVER ["HTTP_REFERER"])) {
			$url = $_SERVER ["HTTP_REFERER"];
			if (trim ( $url ) == '') {
				
				$page = get_homepage ();
				// var_dump($page);
			} else {
				 
					$page = get_content_by_url ( $url );
				 
			}
		}
		define_constants($page);
		$module_info = url_param ( 'module_info', true );
		
		if ($module_info) {
			if ($_POST ['module']) {
				$_POST ['module'] = str_replace ( '..', '', $_POST ['module'] );
				$try_config_file = MODULES_DIR . '' . $_POST ['module'] . '_config.php';
				$try_config_file = normalize_path ( $try_config_file, false );
				if (is_file ( $try_config_file )) {
					include ($try_config_file);
					if ($config ['icon'] == false) {
						$config ['icon'] = MODULES_DIR . '' . $_POST ['module'] . '.png';
						;
						$config ['icon'] = pathToURL ( $config ['icon'] );
					}
					print json_encode ( $config );
					exit ();
				}
			}
		}
		
		$is_iframe = url_param ( 'iframe' );
		
		$base64 = url_param ( 'base64', true );
		
		$admin = url_param ( 'admin', true );
		
		$mod1 = url_param ( 'module_name', true );
		
		$decode_vars = url_param ( 'decode_vars', true );
		$reload_module = url_param ( 'reload_module', true );
		
		$mod_to_edit = url_param ( 'module_to_edit', true );
		$element_id = url_param ( 'element_id', true );
		
		if ($mod1 != false) {
			$mod1 = urldecode ( $mod1 );
		}
		$mod_iframe = false;
		if ($mod_to_edit != false) {
			$mod_to_edit = str_ireplace ( '_mw_slash_replace_', '/', $mod_to_edit );
			$mod_iframe = true;
		}
		// p($mod_to_edit);
		if ($base64 == false) {
			if ($is_iframe) {
				$data = $is_iframe;
				$data = base64_decode ( $data );
				
				$data = unserialize ( $data );
			} else {
				$data = $_POST;
			}
			
			if ($reload_module == 'edit_tag') {
				$reload_module = ($_POST);
				$data = $reload_module;
				// p($data);
			}
			
			if ($mod1 != '') {
				$data ['module'] = $mod1;
			}
			
			$is_page_id = url_param ( 'page_id', true );
			if ($is_page_id != '') {
				$data ['page_id'] = $is_page_id;
			}
			
			$is_post_id = url_param ( 'post_id', true );
			if ($is_post_id != '') {
				$data ['post_id'] = $is_post_id;
			}
			
			$is_category_id = url_param ( 'category_id', true );
			if ($is_category_id != '') {
				$data ['category_id'] = $is_category_id;
			}
			
			$is_rel = url_param ( 'rel', true );
			if ($is_rel != '') {
				$data ['rel'] = $is_rel;
				
				if ($is_rel == 'page') {
					$test = get_ref_page ();
					if (! empty ( $test )) {
						if ($data ['page_id'] == false) {
							$data ['page_id'] = $test ['id'];
						}
					}
					// p($test);
				}
				
				if ($is_rel == 'post') {
					// $refpage = get_ref_page ();
					$refpost = get_ref_post ();
					if (! empty ( $refpost )) {
						if ($data ['post_id'] == false) {
							$data ['post_id'] = $refpost ['id'];
						}
					}
				}
				
				if ($is_rel == 'category') {
					// $refpage = get_ref_page ();
					$refpost = get_ref_post ();
					if (! empty ( $refpost )) {
						if ($data ['post_id'] == false) {
							$data ['post_id'] = $refpost ['id'];
						}
					}
				}
			}
			
			$tags = false;
			$mod_n = false;
			if (isset($data ['mw_params_module'])) {
				if (trim ( $data ['mw_params_module'] ) != '') {
					$mod_n = $data ['module'] = $data ['mw_params_module'];
				}
			}
			
			if (isset($data ['type']) != false) {
				if (trim ( $data ['type'] ) != '') {
					$mod_n = $data ['data-type'] = $data ['type'];
				}
			}
			
			if (isset($data ['data-type']) == false) {
				$mod_n = $data ['data-type'] = $data ['module'];
			}
			if (isset($data ['data-module']) != false) {
				if (trim ( $data ['data-module'] ) != '') {
					$mod_n = $data ['module'] = $data ['data-module'];
				}
			}
			
			if (isset($data ['module'])) {
				unset ( $data ['module'] );
			}
			
			if (isset($data ['type'])) {
				unset ( $data ['type'] );
			}
			
			$data ['data-type'] = rtrim ( $data ['data-type'], '/' );
			$data ['data-type'] = rtrim ( $data ['data-type'], '\\' );
			
			$has_id = false;
			foreach ( $data as $k => $v ) {
				
				if ($k == 'id') {
					$has_id = true;
				}
				
				if (is_array ( $v )) {
					$v1 = encode_var ( $v );
					$tags .= "{$k}=\"$v1\" ";
				} else {
					$tags .= "{$k}=\"$v\" ";
				}
			}
			
			if ($has_id == false) {
				
				$mod_n = url_title ( $mod_n ) . '-' . date ( "YmdHis" );
				$tags .= "id=\"$mod_n\" ";
			}
			
			$tags = "<module {$tags} />";
		} else {
		}
		
		$opts = array ();
		if ($_POST) {
			$opts = $_POST;
		}
		$opts ['admin'] = $admin;
		
		if (($base64 != false) or $is_iframe != false) {
			$opts ['do_not_wrap'] = true;
		}
		// $this->load->model ( 'Template_model', 'template_model' );
		// $res = $this->template_model->parseMicrwoberTags ( $tags, $opts );
		$res = parse_micrwober_tags ( $tags, $opts );
		$res = preg_replace ( '~<(?:!DOCTYPE|/?(?:html|head|body))[^>]*>\s*~i', '', $res );
		
		print $res;
		exit ();
		// phpinfo();
	}
}
