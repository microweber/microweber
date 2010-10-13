<?php

//if (USER_IP == '77.70.8.202') {

$the_curent_link123 = mw_curent_url ();

if (stristr ( $the_curent_link123, 'api' ) == false) {
	
	if (stristr ( $the_curent_link123, 'admin' ) == false) {
		
		$this->output->enable_profiler ( true );
	
	}

}

//}

 

$profiler = getParamFromURL ( 'profiler' );

if ($profiler) {
	
	$this->output->enable_profiler ( true );

}

//$this->benchmark->mark ( 'start' );


//$mw_cache_storage = array ();
//$mw_cache_storage_decoded = array ();
//$mw_cache_storage_hits = array ();


//$before = memory_get_usage();



//must be in this order!
$this->load->model ( 'Init_model', 'init_model' );
$this->load->model ( 'Core_model', 'core_model' );
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




//exit(1);
//$after = memory_get_usage();
//$val = (($after - $before)/1024)/1024;
// print $after;
//$this->load->model ( 'Masterdebate_model', 'masterdebate_model' );
//$this->load->model ( 'Webservices_model', 'webservices_model' );
//$this->load->model('Cacaomail_model', 'cacaomail_model');
//$table = 'ooyes_country';
//$countries_list = $this->core_model->getDbData ( $table, false, $limit = false, $offset = false, array ('printable_name', 'ASC' ), $cache_group = 'country' );
//$this->template ['countries_list'] = $countries_list;


//$map_search_search_country = $this->core_model->getParamFromURL ( 'country' );
//if ($map_search_search_country == false) {
//$map_search_search_country = 'Bulgaria';
//}
//$this->template ['map_search_search_country'] = $map_search_search_country;


if ($_POST ['search_by_keyword'] != '') {
	
	if (($_POST ['search_by_keyword_auto_append_params']) == false) {
		
		$url_params = array ();
		
		$url_params ['keyword'] = stripslashes ( $_POST ['search_by_keyword'] );
		
		$url = $this->core_model->urlConstruct ( false, $url_params );
		
	//header ( 'Location: ' . $url );
	

	//exit ();
	

	}
	
	if (($_POST ['search_by_keyword'])) {
		
		$url_params = array ();
		
		$url_params ['keyword'] = stripslashes ( $_POST ['search_by_keyword'] );
		
		$url = $this->core_model->urlConstruct ( false, $url_params );
		
	//unset ( $_POST ['search_by_keyword'] );
	

	//header ( 'Location: ' . $url );
	

	//exit ();
	

	}

}

if (is_dir ( PLUGINS_DIRNAME )) {
	
	if ($handle = opendir ( PLUGINS_DIRNAME )) {
		
		while ( false !== ($file = readdir ( $handle )) ) {
			
			if (($file != '.') and ($file != '..')) {
				
				$dirname = $file;
				
				if (is_dir ( PLUGINS_DIRNAME . $dirname )) {
					
					if (is_file ( PLUGINS_DIRNAME . $dirname . '/' . $dirname . '_model.php' )) {
						
						//print PLUGINS_DIRNAME . $dirname .'/'.$dirname.'_model.php';
						//$this->load->file ( PLUGINS_DIRNAME . $dirname . '/' . $dirname . '_model.php', true );
						

						//	$this->load->model ( PLUGINS_DIRNAME . $dirname . '/' . $dirname . '_model.php', true );
						require_once PLUGINS_DIRNAME . $dirname . '/' . $dirname . '_model.php';
						
						$this->core_model->plugins_setLoadedPlugin ( $dirname );
						
					//$test = $this->core_model->plugins_getLoadedPlugins ();
					//var_dump($test);
					

					//plugins_setLoadedPlugin ( $dirname );
					//var_dump ( $_GLOBALS  );
					//var_dump ( $_SESSION );
					}
				
				}
			
			}
		
		}
	
	}

}

if (ROOTPATH != $_COOKIE ['root_path']) {
	
	setcookie ( "root_path", ROOTPATH, time () + 60 * 60 * 24 * 90, '/' ); // 90 days
}

/*
 * If there is referrer, store it in cookie and redirect to clean location
 */

$ref = $this->core_model->getParamFromURL ( 'ref' );

if ($ref != '') {
	
	setcookie ( "microweber_referrer_user_id", $ref, time () + 60 * 60 * 24 * 90, '/' ); // 90 days
	setcookie ( "referrer_id", $ref, time () + 60 * 60 * 24 * 90, '/' ); // 90 days
	

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
	header ( 'Location: ' . $site );
	
	exit ();

} else {
	
	// Set back_to url into session. Reset this session component when redirect.
	$back_to = $this->core_model->getParamFromURL ( 'back_to' );
	
	if ($back_to) {
		
		//		var_dump($back_to);
		$this->session->set_userdata ( 'back_to', $back_to );
	
	}
	
	$url = getCurentURL ();
	
	$segs = explode ( '.', $url );
	
	$segs = str_ireplace ( 'http://', '', $segs );
	
	$segs = str_ireplace ( 'https://', '', $segs );
	
	$segs = $segs [0];
	
	$test_if_user_subdomain = addslashes ( $segs );
	
	$subdomain_user = array ();
	
	//$subdomain_user ['username'] = $test_if_user_subdomain;
	//$subdomain_user = $this->users_model->getUsers ( $subdomain_user , array(0,1));
	if (! empty ( $subdomain_user )) {
		
		$subdomain_user = $subdomain_user [0];
		
		setcookie ( "microweber_referrer_user_id", $subdomain_user ['id'], time () + 60 * 60 * 24 * 90, '/' ); // 90 days
		setcookie ( "referrer_id", $subdomain_user ['id'], time () + 60 * 60 * 24 * 90, '/' ); // 90 days
		

		$subdomain_user_test = $this->session->userdata ( 'subdomain_user' );
		
		$subdomain_user_test = serialize ( $subdomain_user_test );
		
		$subdomain_user_test = md5 ( $subdomain_user_test );
		
		$subdomain_user_test2 = serialize ( $subdomain_user );
		
		$subdomain_user_test2 = md5 ( $subdomain_user_test2 );
		
		if ($subdomain_user_test != $subdomain_user_test2) {
			
			$this->session->set_userdata ( 'subdomain_user', $subdomain_user );
		
		}
		
		//set the cannonical URL for duplicated content
		// http://googlewebmastercentral.blogspot.com/2009/02/specify-your-canonical.html
		

		$url = getCurentURL ();
		
		$url = str_ireplace ( $subdomain_user ['username'] . '.', '', $url );
		
		$this->template ['meta_cannonical_url'] = $url;
	
	} else {
		
		$subdomain_user_test = $this->session->userdata ( 'subdomain_user' );
		
		$subdomain_user_test = serialize ( $subdomain_user_test );
		
		$subdomain_user_test = md5 ( $subdomain_user_test );
		
		$subdomain_user_test2 = serialize ( false );
		
		$subdomain_user_test2 = md5 ( $subdomain_user_test2 );
		
		if ($subdomain_user_test != $subdomain_user_test2) {
			
			$this->session->set_userdata ( 'subdomain_user', false );
		
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

//$this->template ['cache_queries_count'] = $this->core_model->cacheGetCount ();
//$this->template ['cache_size'] = $this->core_model->cacheGetSize ();
$this->template ['cms_db_tables'] = $cms_db_tables;

$this->template ['__GET'] = $_GET;

$this->template ['__POST'] = $_POST;

$this->template ['__REQUEST'] = $_REQUEST;

$this->load->vars ( $this->template );

$user_session = $this->session->userdata ( 'user_session' );

$this->template ['user_session'] = $user_session;

 
$the_active_site_template = $this->core_model->optionsGetByKey ( 'curent_template' );

$the_active_site_template_dir = TEMPLATEFILES . $the_active_site_template . '/';

if (defined ( 'ACTIVE_TEMPLATE_DIR' ) == false) {
	
	define ( 'ACTIVE_TEMPLATE_DIR', $the_active_site_template_dir );

}

if (defined ( 'TEMPLATES_DIR' ) == false) {
	
	define ( 'TEMPLATES_DIR', $the_active_site_template_dir );

}

if (defined ( 'TEMPLATE_DIR' ) == false) {
	
	define ( 'TEMPLATE_DIR', $the_active_site_template_dir );

}

$the_active_site_template = $this->core_model->optionsGetByKey ( 'curent_template' );

$the_active_site_template_dir = TEMPLATEFILES . $the_active_site_template . '/';

$the_template_url = site_url ( 'userfiles/' . TEMPLATEFILES_DIRNAME . '/' . $the_active_site_template );

$the_template_url = $the_template_url . '/';

if (defined ( 'TEMPLATE_URL' ) == false) {
	
	define ( "TEMPLATE_URL", $the_template_url );

}

if (defined ( 'USERFILES_URL' ) == false) {
	
	define ( "USERFILES_URL", site_url ( 'userfiles/' ) );

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