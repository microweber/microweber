<?php

//if (USER_IP == '77.70.8.202') {


$the_curent_link123 = mw_curent_url ();

if (stristr ( $the_curent_link123, 'api' ) == false) {
	
	if (stristr ( $the_curent_link123, 'admin' ) == false) {
		
	//	CI::library('output')->enable_profiler ( true );
	

	}

}

//}


$profiler = getParamFromURL ( 'debug' );
if (! $profiler) {
	$profiler = getParamFromURL ( 'dbg' );
}
if (! $profiler) {
	$profiler = getParamFromURL ( 'deb' );
}

if ($profiler) {
	
	CI::library ( 'output' )->enable_profiler ( true );

}

/*print $cms_db_tables ['table_stats_sites'];

define('PIWIK_INCLUDE_PATH', APPPATH.'/stats/');
define('PIWIK_USER_PATH',  APPPATH.'/stats/');
define('PIWIK_ENABLE_DISPATCH', false);
define('PIWIK_ENABLE_ERROR_HANDLER', false);
define('PIWIK_ENABLE_SESSION_START', false);
require_once PIWIK_INCLUDE_PATH . "/index.php";
require_once PIWIK_INCLUDE_PATH . "/core/API/Request.php";
Piwik_FrontController::getInstance()->init();

//l = Piwik::getCurrentUserLogin();*/

// This inits the API Request with the specified parameters


//$this->benchmark->mark ( 'start' );


//$mw_cache_storage = array ();
//$mw_cache_storage_decoded = array ();
//$mw_cache_storage_hits = array ();


//$before = memory_get_usage();


//must be in this order!
//$this->load->model ( 'Init_model', 'init_model' );
/*$this->load->model ( 'Core_model', 'core_model' );
$this->load->model ( 'Taxonomy_model', 'taxonomy_model' );
$this->load->model ( 'Content_model', 'content_model' );
$this->load->model ( 'Comments_model', 'comments_model' );
$this->load->model ( 'Reports_model', 'reports_model' );

$this->load->model ( 'Users_model', 'users_model' );
$this->load->model ( 'Statuses_model', 'statuses_model' );
$this->load->model ( 'Messages_model', 'messages_model' );
$this->load->model ( 'Notifications_model', 'notifications_model' );

$this->load->model ( 'Votes_model', 'votes_model' );

$this->load->model ( 'Cart_model', 'cart_model' );
$this->load->model ( 'Template_model', 'template_model' );
$this->load->model ( 'Mw_model', 'mw' );*/
//$CI = get_instance ();


require (APPPATH . 'functions' . '/mw_functions.php');

$db_setup = CACHEDIR_ROOT . '/db_tmp/index.php';
if (is_file ( $db_setup ) == false) {
	CI::model ( 'init' )->db_setup ();
	CI::model ( 'core' )->options_setup_default ();
}

//some random factor
$rand = rand ( 1, 30 );
if ($rand < 4) {
	CI::model ( 'messages' )->cleanup ();
}

$edit = getParamFromURL ( 'edit' );
if ($edit) {
	$this->template ['edit'] = true;
}

$editmode = getParamFromURL ( 'editmode' );
if ($editmode != false) {
	if ($editmode == 'y') {
		$adm = CI::model ( 'core' )->is_admin ();
		if ($adm == true) {
			CI::library ( 'session' )->set_userdata ( 'editmode', true );
		
		}
	}
	
	if ($editmode == 'n') {
		CI::library ( 'session' )->set_userdata ( 'editmode', false );
	}
	
	$url = getCurentURL ();
	
	$site = site_url ();
	
	$url = str_ireplace ( $site, '', $url );
	
	$segs = explode ( '/', $url );
	
	$segs_clean = array ();
	
	foreach ( $segs as $segment ) {
		
		$origsegment = ($segment);
		
		$segment = explode ( ':', $segment );
		
		if ($segment [0] == 'editmode') {
			
		//return $segment [1];
		} else {
			
			$segs_clean [] = $origsegment;
		
		}
	
	}
	
	$segs_clean = implode ( '/', $segs_clean );
	
	$site = site_url ( $segs_clean );
	
	safe_redirect ( 'Location: ' . $site );
	
	exit ();

}

$debugmode = getParamFromURL ( 'debugmode' );
if ($debugmode != false) {
	if ($debugmode == 'y') {
		$adm = CI::model ( 'core' )->is_admin ();
		if ($adm == true) {
			CI::library ( 'session' )->set_userdata ( 'debugmode', true );
		
		}
	}
	
	if ($debugmode == 'n') {
		CI::library ( 'session' )->set_userdata ( 'debugmode', false );
	}
	
	$url = getCurentURL ();
	
	$site = site_url ();
	
	$url = str_ireplace ( $site, '', $url );
	
	$segs = explode ( '/', $url );
	
	$segs_clean = array ();
	
	foreach ( $segs as $segment ) {
		
		$origsegment = ($segment);
		
		$segment = explode ( ':', $segment );
		
		if ($segment [0] == 'debugmode') {
			
		//return $segment [1];
		} else {
			
			$segs_clean [] = $origsegment;
		
		}
	
	}
	
	$segs_clean = implode ( '/', $segs_clean );
	
	$site = site_url ( $segs_clean );
	
	safe_redirect ( 'Location: ' . $site );
	
	exit ();

}

$debugmode = CI::library ( 'session' )->userdata ( 'debugmode' );
if ($debugmode == true) {
	CI::library ( 'output' )->enable_profiler ( true );
}

//exit(1);
//$after = memory_get_usage();
//$val = (($after - $before)/1024)/1024;
// print $after;
//$this->load->model ( 'Masterdebate_model', 'masterdebate_model' );
//$this->load->model ( 'Webservices_model', 'webservices_model' );
//$this->load->model('Cacaomail_model', 'cacaomail_model');
//$table = 'ooyes_country';
//$countries_list = CI::model('core')->getDbData ( $table, false, $limit = false, $offset = false, array ('printable_name', 'ASC' ), $cache_group = 'country' );
//$this->template ['countries_list'] = $countries_list;


//$map_search_search_country = CI::model('core')->getParamFromURL ( 'country' );
//if ($map_search_search_country == false) {
//$map_search_search_country = 'Bulgaria';
//}
//$this->template ['map_search_search_country'] = $map_search_search_country;


if ($_POST ['search_by_keyword'] != '') {
	
	if (($_POST ['search_by_keyword_auto_append_params']) == false) {
		
		$url_params = array ();
		
		$url_params ['keyword'] = stripslashes ( $_POST ['search_by_keyword'] );
		
		$url = CI::model ( 'core' )->urlConstruct ( false, $url_params );
	
	}
	
	if (($_POST ['search_by_keyword'])) {
		
		$url_params = array ();
		
		$url_params ['keyword'] = stripslashes ( $_POST ['search_by_keyword'] );
		
		$url = CI::model ( 'core' )->urlConstruct ( false, $url_params );
	
	}

}
/*
if (is_dir ( PLUGINS_DIRNAME )) {
	
	if ($handle = opendir ( PLUGINS_DIRNAME )) {
		
		while ( false !== ($file = readdir ( $handle )) ) {
			
			if (($file != '.') and ($file != '..')) {
				
				$dirname = $file;
				
				if (is_dir ( PLUGINS_DIRNAME . $dirname )) {
					
					if (is_file ( PLUGINS_DIRNAME . $dirname . '/' . $dirname . '_model.php' )) {
  
						require_once PLUGINS_DIRNAME . $dirname . '/' . $dirname . '_model.php';
						
						CI::model('core')->plugins_setLoadedPlugin ( $dirname );
		 
					}
				
				}
			
			}
		
		}
	
	}

}*/

if (ROOTPATH != $_COOKIE ['root_path']) {
	
//	setcookie ( "root_path", ROOTPATH, time () + 60 * 60 * 24 * 90, '/' ); // 90 days
}

/*
 * If there is referrer, store it in cookie and redirect to clean location
 */

$ref = CI::model ( 'core' )->getParamFromURL ( 'ref' );

if ($ref != '') {
	
	setcookie ( "microweber_referrer_user_id", $ref, time () + 60 * 60 * 24 * 90, '/' ); // 90 days
	setcookie ( "referrer_id", $ref, time () + 60 * 60 * 24 * 90, '/' ); // 90 days
	

	CI::library ( 'session' )->set_userdata ( 'ref', $ref );
	$url = getCurentURL ();
	
	$site = site_url ();
	
	$url = str_ireplace ( $site, '', $url );
	
	$segs = explode ( '/', $url );
	
	$segs_clean = array ();
	
	foreach ( $segs as $segment ) {
		
		$origsegment = ($segment);
		
		$segment = explode ( ':', $segment );
		
		if ($segment [0] == 'ref') {
			
		//return $segment [1];
		} else {
			
			$segs_clean [] = $origsegment;
		
		}
	
	}
	
	$segs_clean = implode ( '/', $segs_clean );
	
	$site = site_url ( $segs_clean );
	
	//	print $site;
	safe_redirect ( 'Location: ' . $site );
	
	exit ();

} else {
	
	// Set back_to url into session. Reset this session component when redirect.
	$back_to = CI::model ( 'core' )->getParamFromURL ( 'back_to' );
	
	if ($back_to) {
		
		//		var_dump($back_to);
		CI::library ( 'session' )->set_userdata ( 'back_to', $back_to );
	
	}
	
	$url = getCurentURL ();
	
	$segs = explode ( '.', $url );
	
	$segs = str_ireplace ( 'http://', '', $segs );
	
	$segs = str_ireplace ( 'https://', '', $segs );
	
	$segs = $segs [0];
	
	$test_if_user_subdomain = addslashes ( $segs );
	
	$subdomain_user = array ();
	
	//$subdomain_user ['username'] = $test_if_user_subdomain;
	//$subdomain_user = CI::model('users')->getUsers ( $subdomain_user , array(0,1));
	if (! empty ( $subdomain_user )) {
		
		$subdomain_user = $subdomain_user [0];
		
		setcookie ( "microweber_referrer_user_id", $subdomain_user ['id'], time () + 60 * 60 * 24 * 90, '/' ); // 90 days
		setcookie ( "referrer_id", $subdomain_user ['id'], time () + 60 * 60 * 24 * 90, '/' ); // 90 days
		

		$subdomain_user_test = CI::library ( 'session' )->userdata ( 'subdomain_user' );
		
		$subdomain_user_test = serialize ( $subdomain_user_test );
		
		$subdomain_user_test = md5 ( $subdomain_user_test );
		
		$subdomain_user_test2 = serialize ( $subdomain_user );
		
		$subdomain_user_test2 = md5 ( $subdomain_user_test2 );
		
		if ($subdomain_user_test != $subdomain_user_test2) {
			
			CI::library ( 'session' )->set_userdata ( 'subdomain_user', $subdomain_user );
		
		}
		
		//set the cannonical URL for duplicated content
		// http://googlewebmastercentral.blogspot.com/2009/02/specify-your-canonical.html
		

		$url = getCurentURL ();
		
		$url = str_ireplace ( $subdomain_user ['username'] . '.', '', $url );
		
		$this->template ['meta_cannonical_url'] = $url;
	
	} else {
		
		$subdomain_user_test = CI::library ( 'session' )->userdata ( 'subdomain_user' );
		
		$subdomain_user_test = serialize ( $subdomain_user_test );
		
		$subdomain_user_test = md5 ( $subdomain_user_test );
		
		$subdomain_user_test2 = serialize ( false );
		
		$subdomain_user_test2 = md5 ( $subdomain_user_test2 );
		
		if ($subdomain_user_test != $subdomain_user_test2) {
			
			CI::library ( 'session' )->set_userdata ( 'subdomain_user', false );
		
		}
		
		$this->template ['meta_cannonical_url'] = false;
	
	}
	
//p($subdomain_user);


//$this->template ['created_by'] = false;
}

/*
 * Make some initializations - constants, libraries, template variables
 */

//$this->load->library ( 'form_validation' );


$this->template ['className'] = strtolower ( get_class () );

//$this->template ['cache_queries_count'] = CI::model('core')->cacheGetCount ();
//$this->template ['cache_size'] = CI::model('core')->cacheGetSize ();
$this->template ['cms_db_tables'] = $cms_db_tables;

$this->template ['__GET'] = $_GET;

$this->template ['__POST'] = $_POST;

$this->template ['__REQUEST'] = $_REQUEST;

$this->load->vars ( $this->template );

$user_session = CI::library ( 'session' )->userdata ( 'user_session' );

$this->template ['user_session'] = $user_session;

$this->template ['user_id'] = CI::model ( 'core' )->userId ();

$url = $this->uri->uri_string ();

$url = str_ireplace ( '\\', '', $url );

$is_json = url_param ( 'json' );
if ($is_json) {
	$output_format = 'json';
	$url = url_param_unset ( 'json', $url );
}

$is_debug = url_param ( 'debug' );
if ($is_debug) {
	
	$url = url_param_unset ( 'debug', $url );
}

$slash = substr ( "$url", 0, 1 );
if ($slash == '/') {
	$url = substr ( "$url", 1, strlen ( $url ) );
}

$page = CI::model ( 'content' )->getPageByURLAndCache ( $url );


 

if (trim ( $page ['active_site_template'] ) != '') {
	$tmpl = trim ( $page ['active_site_template'] );
	if (strtolower ( $tmpl ) == 'default') {
		$the_active_site_template = CI::model ( 'core' )->optionsGetByKey ( 'curent_template' );
	
	} else {
		$check_dir = TEMPLATEFILES . '' . $tmpl . '/layouts/';
		if (is_dir ( $check_dir )) {
			$the_active_site_template = $tmpl;
		} else {
			$the_active_site_template = CI::model ( 'core' )->optionsGetByKey ( 'curent_template' );
		
		}
		
	}
} else {
	$the_active_site_template = CI::model ( 'core' )->optionsGetByKey ( 'curent_template' );

}
if (strtolower ( $the_active_site_template ) == 'default') {
	
	$the_active_site_template = CI::model ( 'core' )->optionsGetByKey ( 'curent_template' );
	//p($the_active_site_template,1);
//
}


	//$the_active_site_template = CI::model ( 'core' )->optionsGetByKey ( 'curent_template' );
 



$the_active_site_template_dir = TEMPLATEFILES . $the_active_site_template . '/';
$the_active_site_template_dir = normalize_path ( $the_active_site_template_dir, true );

//p($the_active_site_template_dir );

//if (defined ( 'ACTIVE_TEMPLATE_DIR' ) == false) {
	
	define ( 'ACTIVE_TEMPLATE_DIR', $the_active_site_template_dir );
	define ( 'TEMPLATE_DIR', $the_active_site_template_dir );

//}

if (defined ( 'TEMPLATES_DIR' ) == false) {
	
	define ( 'TEMPLATES_DIR', $the_active_site_template_dir );

}

if (defined ( 'DEFAULT_TEMPLATE_DIR' ) == false) {
	
	define ( 'DEFAULT_TEMPLATE_DIR', TEMPLATEFILES . 'default/' );

}

//$the_active_site_template = CI::model ( 'core' )->optionsGetByKey ( 'curent_template' );


$the_active_site_template_dir = TEMPLATEFILES . $the_active_site_template . '/';

$the_template_url = site_url ( 'userfiles/' . TEMPLATEFILES_DIRNAME . '/' . $the_active_site_template );

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
	
	$layouts_dir = TEMPLATES_DIR . 'layouts/';
	
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

$this->template ['layouts_dir'] = $layouts_dir;

$this->template ['layouts_url'] = $layouts_url;

?>