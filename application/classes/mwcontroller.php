<?php

// Controller Class
class MwController {

	private $render_this_url = false;
	private $isolate_by_html_id = false;

	function index() {

		if ($this -> render_this_url == false and isAjax() == FALSE) {
			$page_url = url_string();
		} else {
			$page_url = $this -> render_this_url;
			$this -> render_this_url = false;
		}

		$page_url = rtrim($page_url, '/');
		$is_admin = is_admin();

		$simply_a_file = false;
		// if this is a file path it will load it
		if (isset($_GET['view'])) {
			$is_custom_view = $_GET['view'];
		} else {
			$is_custom_view = url_param('view');
			if ($is_custom_view and $is_custom_view != false) {

				$page_url = url_param_unset('view', $page_url);

			}

		}

		$is_editmode = url_param('editmode');
		$is_no_editmode = url_param('no_editmode');
		if ($is_editmode and $is_no_editmode == false) {
			$editmode_sess = session_get('editmode');

			$page_url = url_param_unset('editmode', $page_url);
			if ($is_admin == true) {
				if ($editmode_sess == false) {
					session_set('editmode', true);
				}
				safe_redirect(site_url($page_url));
				exit();
			} else {
				$is_editmode = false;
			}
		}
		if (!$is_no_editmode) {
			$is_editmode = session_get('editmode');
		} else {
			$is_editmode = false;
			$page_url = url_param_unset('no_editmode', $page_url);
		}

		$is_preview_template = url_param('preview_template');
		if (!$is_preview_template) {
			$is_preview_template = false;
		} else {

			$page_url = url_param_unset('preview_template', $page_url);
		}

		$is_layout_file = url_param('preview_layout');
		if (!$is_layout_file) {
			$is_layout_file = false;
		} else {

			$page_url = url_param_unset('preview_layout', $page_url);
		}

		if (trim($page_url) == '') {
			//
			$page = get_homepage();
		} else {

			$page = get_page_by_url($page_url);

			if (empty($page)) {
				$page_url_segment_1 = url_segment(0);

				$page_url_segment_2 = url_segment(1);

				$td = TEMPLATEFILES . DS . $page_url_segment_1;

				$fname1 = 'index.php';
				$fname2 = $page_url_segment_2 . '.php';
				$fname3 = $page_url_segment_2;

				$tf1 = $td . DS . $fname1;
				$tf2 = $td . DS . $fname2;
				$tf3 = $td . DS . $fname3;

				$the_new_page_file = false;

				if (is_dir($td)) {

					if (is_file($tf1)) {
						$simply_a_file = $tf1;
						$the_new_page_file = $fname1;
					}

					if (is_file($tf2)) {
						$simply_a_file = $tf2;
						$the_new_page_file = $fname2;
					}
					if (is_file($tf3)) {
						$simply_a_file = $tf3;
						$the_new_page_file = $fname3;
					}

					if (($simply_a_file) != false) {
						$simply_a_file = str_replace('..', '', $simply_a_file);
					}

					//   d($simply_a_file);
				}
				if ($simply_a_file == false) {
					$page = get_homepage();
				} else {
					$page['id'] = 0;
					$page['content_type'] = 'page';
					$page['parent'] = '0';
					$page['url'] = url_string();
					$page['active_site_template'] = $page_url_segment_1;
					$page['layout_file'] = $the_new_page_file;
					$page['simply_a_file'] = $simply_a_file;

					template_var('new_page', $page);
					//template_var('new_page');
				}
			}
			//
		}
		//

		if ($page['content_type'] == "post") {
			$content = $page;
			$page = get_content_by_id($page['parent']);
		} else {
			$content = $page;
		}

		if ($is_preview_template != false and $is_admin == true) {
			$is_preview_template = str_replace('____', DS, $is_preview_template);
			$content['active_site_template'] = $is_preview_template;
		}

		if ($is_layout_file != false and $is_admin == true) {
			$is_layout_file = str_replace('____', DS, $is_layout_file);
			$content['layout_file'] = $is_layout_file;
		}
		if ($is_custom_view and $is_custom_view != false) {
			$content['custom_view'] = $is_custom_view;
		}

		define_constants($content);

		$render_file = get_layout_for_page($content);

		if ($render_file) {
			$l = new MwView($render_file);
			// $l->content = $content;
			// $l->set($l);
			$l = $l -> __toString();

			// d($l);
			//exit();

			$l = parse_micrwober_tags($l, $options = false);
			$apijs_loaded = site_url('apijs');

			$default_css = '<link rel="stylesheet" href="' . INCLUDES_URL . 'default.css" type="text/css" />';

			// $l = str_ireplace('</head>', $default_css . '</head>', $l);
			$l = str_ireplace('<head>', '<head>' . $default_css, $l);
			if (!stristr($l, $apijs_loaded)) {
				$default_css = '<script src="' . $apijs_loaded . '"></script>';

				$l = str_ireplace('<head>', '<head>' . $default_css, $l);
			}
			if ($is_editmode == true and $this -> isolate_by_html_id == false) {
				$is_admin = is_admin();
				if ($is_admin == true) {

					$tb = INCLUDES_DIR . DS . 'toolbar' . DS . 'toolbar.php';

					$layout_toolbar = new MwView($tb);
					$layout_toolbar = $layout_toolbar -> __toString();
					if ($layout_toolbar != '') {
						$layout_toolbar = parse_micrwober_tags($layout_toolbar, $options = array('no_apc' => 1));

						$l = str_ireplace('</body>', $layout_toolbar . '</body>', $l, $c = 1);
					}
				}
			}

			$l = str_replace('{TEMPLATE_URL}', TEMPLATE_URL, $l);
			$l = str_replace('%7BTEMPLATE_URL%7D', TEMPLATE_URL, $l);

			// d(TEMPLATE_URL);

			$l = execute_document_ready($l);

			$is_embed = url_param('embed');

			if ($is_embed != false) {
				$this -> isolate_by_html_id = $is_embed;
			}

			if ($this -> isolate_by_html_id != false) {
				$id_sel = $this -> isolate_by_html_id;
				$this -> isolate_by_html_id = false;
				require_once (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'parser' . DIRECTORY_SEPARATOR . 'phpQuery.php');
				$pq = phpQuery::newDocument($l);
				foreach ($pq ['#' . $id_sel] as $elem) {

					$l = pq($elem) -> htmlOuter();
				}

				// return $pq->htmlOuter();
			}

			if (!headers_sent()) {
				setcookie('last_page', $page_url);
			}

			print $l;

			if (isset($_GET['test'])) {
				debug_info();
				$is_admin = is_admin();
				if ($is_admin == true) {

				}
			}

			// print (round(microtime()-T,5)*1000);
			//
			exit();
		} else {

			print 'NO LAYOUT IN ' . __FILE__;
			d($template_view);
			d($page);
			exit();
		}
		// var_dump ( $page );
		// var_dump($ab);
	}

	function admin() {

		$recycle_bin_f = CACHEDIR . 'db' . DS . 'recycle_bin_clear_' . date("Ymd") . '.php';
		if (!is_file($recycle_bin_f)) {
			cache_clear_recycle();
			@touch($recycle_bin_f);
		}

		create_mw_default_options();
		define_constants();
		$l = new MwView(ADMIN_VIEWS_PATH . 'admin.php');
		$l = $l -> __toString();
		// var_dump($l);
		$layout = parse_micrwober_tags($l, $options = false);
		$layout = execute_document_ready($layout);

		print $layout;
		
		if (isset($_GET['test'])) {
			debug_info();
			 
		}
		exit();
	}

	function api_html() {
		if (!defined('MW_API_HTML_OUTPUT')) {
			define('MW_API_HTML_OUTPUT', true);
		}
		$this -> api();
	}

	function api($api_function = false) {

		if (!defined('MW_API_CALL')) {
			define('MW_API_CALL', true);
		}

		define_constants();
		$api_exposed = '';

		// user functions
		$api_exposed .= 'user_login user_logout ';

		// content functions
		$api_exposed .= 'save_edit ';
		$api_exposed .= 'set_language ';
		$api_exposed .= (api_expose(true));
		$api_exposed = explode(' ', $api_exposed);
		$api_exposed = array_unique($api_exposed);
		$api_exposed = array_trim($api_exposed);
		if ($api_function == false) {
			$api_function = url_segment(1);
		}
		if ($api_function) {

		} else {
			$api_function = 'index';
		}

		if ($api_function == 'module') {
			$this -> module();
		} else {
			if (in_array($api_function, $api_exposed)) {
				if (function_exists($api_function) or class_exists($api_function)) {
					if (!$_POST and !$_GET) {
						//  $data = url(2);
						$data = url_params(true);
						if (empty($data)) {
							$data = url(2);
						}
					} else {
						$data = $_REQUEST;
					}

					if (function_exists($api_function)) {
						$res = $api_function($data);
					} elseif (class_exists($api_function, false)) {
						//	d($api_function);
						$segs = url();
						$mmethod = array_pop($segs);
						//d($segs);
						$res = new $api_function($data);
						if (method_exists($res, $mmethod)) {
							$res = $res -> $mmethod($data);
						}

					}

					$hooks = api_hook(true);
					if (isset($hooks[$api_function]) and is_array($hooks[$api_function]) and !empty($hooks[$api_function])) {
						foreach ($hooks[$api_function] as $hook_key => $hook_value) {
							$hook_value($res);
							//d($hook_value);
						}
					}

					//d($hooks);
					if (!defined('MW_API_HTML_OUTPUT')) {
						print json_encode($res);
					} else {

						print($res);
					}
				} else {
					error('The api function does not exist', __FILE__, __LINE__);
				}

				// print $api_function;
			} else {
				error('The api function ' . $api_function . ' is not defined in the allowed functions list');
			}
			exit();
		}
		// exit ( $api_function );
	}

	function m() {

		if (!defined('MW_API_CALL')) {
			define('MW_API_CALL', true);
		}

		if (!defined('MW_NO_OUTPUT')) {
			define('MW_NO_OUTPUT', true);
		}
		return $this -> module();
	}

	function module() {
		if (!defined('MW_API_CALL')) {
			//	define('MW_API_CALL', true);
		}
		$page = false;

		$custom_display = false;
		if (isset($_REQUEST['data-display']) and $_REQUEST['data-display'] == 'custom') {
			$custom_display = true;
		}

		if (isset($_REQUEST['data-module-name'])) {
			$_REQUEST['module'] = $_REQUEST['data-module-name'];
			$_REQUEST['data-type'] = $_REQUEST['data-module-name'];

			if (!isset($_REQUEST['id'])) {
				$_REQUEST['id'] = url_title($_REQUEST['data-module-name'] . '-' . date("YmdHis"));
			}

		}

		if (isset($_REQUEST['data-type'])) {
			$_REQUEST['module'] = $_REQUEST['data-type'];
		}

		if (isset($_REQUEST['display']) and $_REQUEST['display'] == 'custom') {
			$custom_display = true;
		}
		if (isset($_REQUEST['view']) and $_REQUEST['view'] == 'admin') {
			$custom_display = FALSE;
		}

		if ($custom_display == true) {
			$custom_display_id = false;
			if (isset($_REQUEST['id'])) {
				$custom_display_id = $_REQUEST['id'];
			}
			if (isset($_REQUEST['data-id'])) {
				$custom_display_id = $_REQUEST['data-id'];
			}
		}

		if (isset($_SERVER["HTTP_REFERER"])) {
			$url = $_SERVER["HTTP_REFERER"];
			$url = explode('?', $url);
			$url = $url[0];

			if (trim($url) == '' or trim($url) == site_url()) {
				//$page = get_content_by_url($url);
				$page = get_homepage();
				// var_dump($page);
			} else {

				$page = get_content_by_url($url);
			}
		} else {
			$url = url_string();
		}

		define_constants($page);

		if ($custom_display == true) {

			$u2 = site_url();
			$u1 = str_replace($u2, '', $url);
			$this -> render_this_url = $u1;
			$this -> isolate_by_html_id = $custom_display_id;
			$this -> index();
			exit();
		}
		$url_last = false;
		if (!isset($_REQUEST['module'])) {
			$url = url_string(1);
			$url = str_replace_once('module/', '', $url);
			$url = str_replace_once('module_api/', '', $url);
			$url = str_replace_once('m/', '', $url);
			//d($url);
			if (is_module($url)) {
				$_REQUEST['module'] = $url;
				$mod_from_url = $url;
			} else {
				$url1 = $url_temp = explode('/', $url);
				$url_last = array_pop($url_temp);

				$try_intil_found = false;
				$temp1 = array();
				foreach ($url_temp as $item) {

					$temp1[] = implode('/', $url_temp);
					$url_laset = array_pop($url_temp);

				}

				$i = 0;
				foreach ($temp1 as $item) {
					if ($try_intil_found == false) {

						if (is_module($item)) {

							$url_tempx = explode('/', $url);

							$_REQUEST['module'] = $item;
							$url_prev = $url_last;
							$url_last = array_pop($url_tempx);
							$url_prev = array_pop($url_tempx);

							// d($url_prev);
							$mod_from_url = $item;
							$try_intil_found = true;
						}

					}
					$i++;
				}

			}
		}

		$module_info = url_param('module_info', true);

		if ($module_info) {
			if ($_REQUEST['module']) {
				$_REQUEST['module'] = str_replace('..', '', $_REQUEST['module']);
				$try_config_file = MODULES_DIR . '' . $_REQUEST['module'] . '_config.php';
				$try_config_file = normalize_path($try_config_file, false);
				if (is_file($try_config_file)) {
					include ($try_config_file);
					if ($config['icon'] == false) {
						$config['icon'] = MODULES_DIR . '' . $_REQUEST['module'] . '.png';
						;
						$config['icon'] = pathToURL($config['icon']);
					}
					print json_encode($config);
					exit();
				}
			}
		}

		$admin = url_param('admin', true);

		$mod_to_edit = url_param('module_to_edit', true);
		$embed = url_param('embed', true);

		$mod_iframe = false;
		if ($mod_to_edit != false) {
			$mod_to_edit = str_ireplace('_mw_slash_replace_', '/', $mod_to_edit);
			$mod_iframe = true;
		}
		//$data = $_REQUEST;

		if (($_POST)) {
			$data = $_POST;
		} else {
			$url = url();

			if (!empty($url)) {
				foreach ($url as $k => $v) {
					$kv = explode(':', $v);
					if (isset($kv[0]) and isset($kv[1])) {
						$data[$kv[0]] = $kv[1];
					}
				}
			}
		}

		$is_page_id = url_param('page_id', true);
		if ($is_page_id != '') {
			//s  $data['page_id'] = $is_page_id;
		}

		$is_REQUEST_id = url_param('post_id', true);
		if ($is_REQUEST_id != '') {
			//  $data['post_id'] = $is_REQUEST_id;
		}

		$is_category_id = url_param('category_id', true);
		if ($is_category_id != '') {
			//   $data['category_id'] = $is_category_id;
		}

		$is_rel = url_param('rel', true);
		if ($is_rel != '') {
			//   $data['rel'] = $is_rel;

			if ($is_rel == 'page') {
				$test = get_ref_page();
				if (!empty($test)) {
					if ($data['page_id'] == false) {
						//   $data['page_id'] = $test['id'];
					}
				}

			}

			if ($is_rel == 'post') {
				// $refpage = get_ref_page ();
				$refpost = get_ref_post();
				if (!empty($refpost)) {
					if ($data['post_id'] == false) {
						// $data['post_id'] = $refpost['id'];
					}
				}
			}

			if ($is_rel == 'category') {
				// $refpage = get_ref_page ();
				$refpost = get_ref_post();
				if (!empty($refpost)) {
					if ($data['post_id'] == false) {
						//  $data['post_id'] = $refpost['id'];
					}
				}
			}
		}

		$tags = false;
		$mod_n = false;

		if (isset($data['type']) != false) {
			if (trim($data['type']) != '') {
				$mod_n = $data['data-type'] = $data['type'];
			}
		}

		if (isset($data['data-module-name'])) {
			$mod_n = $data['data-type'] = $data['data-module-name'];
			unset($data['data-module-name']);
		}

		if (isset($data['data-type']) != false) {
			$mod_n = $data['data-type'];
		}
		if (isset($data['data-module']) != false) {
			if (trim($data['data-module']) != '') {
				$mod_n = $data['module'] = $data['data-module'];
			}
		}

		if (isset($data['module'])) {
			$mod_n = $data['data-type'] = $data['module'];
			unset($data['module']);
		}

		if (isset($data['type'])) {
			$mod_n = $data['data-type'] = $data['type'];
			unset($data['type']);
		}
		if (isset($data['data-type']) != false) {
			$data['data-type'] = rtrim($data['data-type'], '/');
			$data['data-type'] = rtrim($data['data-type'], '\\');
			$data['data-type'] = str_replace('__', '/', $data['data-type']);
		}
		if (!isset($data)) {
			$data = $_REQUEST;
		}
		if (!isset($data['module']) and isset($mod_from_url) and $mod_from_url != false) {
			$data['module'] = ($mod_from_url);
		}

		$has_id = false;
		if (isset($data) and isarr($data)) {
			foreach ($data as $k => $v) {

				if ($k == 'id') {
					$has_id = true;
				}

				if (is_array($v)) {
					$v1 = encode_var($v);
					$tags .= "{$k}=\"$v1\" ";
				} else {
					$tags .= "{$k}=\"$v\" ";
				}
			}
		}
		if ($has_id == false) {

			$mod_n = url_title($mod_n) . '-' . date("YmdHis");
			$tags .= "id=\"$mod_n\" ";
		}

		$tags = "<module {$tags} />";

		$opts = array();
		if ($_REQUEST) {
			$opts = $_REQUEST;
		}
		$opts['admin'] = $admin;

		$res = parse_micrwober_tags($tags, $opts);
		$res = preg_replace('~<(?:!DOCTYPE|/?(?:html|head|body))[^>]*>\s*~i', '', $res);

		if ($embed != false) {
			$p_index = INCLUDES_PATH . 'api/index.php';
			$p_index = normalize_path($p_index, false);
			$l = new MwView($p_index);
			$layout = $l -> __toString();
			$res = str_replace('{content}', $res, $layout);
		}
		$res = execute_document_ready($res);
		if (!defined('MW_NO_OUTPUT')) {
			print $res;
		}
		if (function_exists($url_last)) {
			//d($url_last);
			$this -> api($url_last);
		} else if (isset($url_prev) and function_exists($url_prev)) {
			$this -> api($url_last);
		} elseif (class_exists($url_last, false)) {
			$this -> api($url_last);
		} elseif (isset($url_prev) and class_exists($url_prev, false)) {
			$this -> api($url_prev);
		}

		exit();
	}

	function apijs() {

		$ref_page = false;
		if (isset($_SERVER['HTTP_REFERER'])) {
			$ref_page = $_SERVER['HTTP_REFERER'];
			if ($ref_page != '') {
				$ref_page = $the_ref_page = get_content_by_url($ref_page);
				$page_id = $ref_page['id'];
				$ref_page['custom_fields'] = get_custom_fields_for_content($page_id, false);
			}
		}
		header("Content-type: text/javascript");
		define_constants($ref_page);
		$l = new MwView(INCLUDES_PATH . 'api' . DS . 'api.js');
		$l = $l -> __toString();
		// var_dump($l);
		$l = parse_micrwober_tags($l, $options = array('parse_only_vars' => 1));
		print $l;
		exit();
	}

	function plupload() {
		define_constants();
		$f = MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'plupload.php';
		require ($f);
		exit();
	}

	function install() {
		$installed = MW_IS_INSTALLED;

		if ($installed == false) {
			$f = INCLUDES_PATH . 'install' . DIRECTORY_SEPARATOR . 'index.php';
			require ($f);
			exit();
		} else {
			if (is_admin() == true) {
				$f = INCLUDES_PATH . 'install' . DIRECTORY_SEPARATOR . 'index.php';
				require ($f);
				exit();
			} else {
				error('You must login as admin');
			}
		}
	}

	function editor_tools() {
		if (!defined('IN_ADMIN')) {
			define('IN_ADMIN', true);
		}
		$tool = url(1);

		if ($tool) {

		} else {
			$tool = 'index';
		}

		$tool = str_replace('..', '', $tool);

		$p_index = INCLUDES_PATH . 'toolbar/editor_tools/index.php';
		$p_index = normalize_path($p_index, false);

		$p = INCLUDES_PATH . 'toolbar/editor_tools/' . $tool . '/index.php';
		$p = normalize_path($p, false);

		$l = new MwView($p_index);
		$layout = $l -> __toString();
		// var_dump($l);

		if (is_file($p)) {
			$p = new MwView($p);
			$layout_tool = $p -> __toString();
			$layout = str_replace('{content}', $layout_tool, $layout);
		} else {
			$layout = str_replace('{content}', 'Not found!', $layout);
		}

		$layout = parse_micrwober_tags($layout, $options = false);

		$layout = execute_document_ready($layout);

		print $layout;
		exit();
		//
		//header("HTTP/1.0 404 Not Found");
		//$v = new MwView(ADMIN_VIEWS_PATH . '404.php');
		//echo $v;
	}

	function show_404() {
		header("HTTP/1.0 404 Not Found");
		$v = new MwView(ADMIN_VIEWS_PATH . '404.php');
		echo $v;
	}

	function __destruct() {
		//print 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';

	}

}
