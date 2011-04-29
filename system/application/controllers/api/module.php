<?php

class Module extends Controller {
	
	function __construct() {
		
		parent::Controller ();
		
		require_once (APPPATH . 'controllers/default_constructor.php');
		//  require_once (APPPATH . 'controllers/api/default_constructor.php');
	

	}
	
	function index() {
		
		$is_iframe = url_param ( 'iframe' );
		
		$base64 = url_param ( 'base64', true );
		
		$admin = url_param ( 'admin', true );
		
		$mod1 = url_param ( 'module_name', true );
		
		$decode_vars = url_param ( 'decode_vars', true );
		
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
		//p($mod_to_edit);
		if ($base64 == false) {
			if ($is_iframe) {
				$data = $is_iframe;
				$data = base64_decode ( $data );
				
				$data = unserialize ( $data );
			
			} else {
				$data = $_POST;
			}
			
			if ($decode_vars) {
				$decode_vars = decode_var ( $decode_vars );
				$data = $decode_vars;
				//p($data);
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
					//p($test);
				}
				
				if ($is_rel == 'post') {
					//$refpage = get_ref_page ();
					$refpost = get_ref_post ();
					if (! empty ( $refpost )) {
						if ($data ['post_id'] == false) {
							$data ['post_id'] = $refpost ['id'];
						}
					}
				}
				
				if ($is_rel == 'category') {
					//$refpage = get_ref_page ();
					$refpost = get_ref_post ();
					if (! empty ( $refpost )) {
						if ($data ['post_id'] == false) {
							$data ['post_id'] = $refpost ['id'];
						}
					}
				}
			
			}
			
			$tags = false;
			
			foreach ( $data as $k => $v ) {
				$tags .= "{$k}=\"$v\" ";
			
			}
			
			$tags = "<microweber {$tags}></microweber>";
		} else {
			if ($base64 == 'undefined') {
				exit ();
			}
			
			//$base64 = base64_decode ( $base64 );
			if (is_string ( $base64 ) == false) {
			//	$base64 = false;
			}
			//$base64 = unserialize($base64);
		//p ( $base64 );
		//
		}
		if ($base64 != false) {
			$tags = $base64;
			
			if($mod_iframe == true){
				$mod_iframe = 'quick_edit="true"';
			}
			
			$tags = "<microweber module='{$mod_to_edit}' module_id='{$element_id}' {$mod_iframe} ></microweber>";
		}
		//p ( $tags );
		//exit; 
		$opts = array ();
		$opts ['admin'] = $admin;
		
		if (($base64 != false) or $is_iframe != false) {
			$opts ['do_not_wrap'] = true;
		}
		
		$res = CI::model ( 'template' )->parseMicrwoberTags ( $tags, $opts );
		if ($admin == true) {
			$is_admin = is_admin ();
			if ($is_admin == false) {
				$go = site_url ( 'login' );
				safe_redirect ( $go );
			}
			$opts ['no_cache'] = true;
			
			$res_1 = CI::view ( 'admin/module_admin', true, true );
			$res_1 = CI::model ( 'template' )->parseMicrwoberTags ( $res_1, $opts );
		}
		
		if ($res_1) {
			$res = str_replace ( '{content}', $res, $res_1 );
		}
		print $res;
		exit ();
		//  phpinfo();
	}

}



