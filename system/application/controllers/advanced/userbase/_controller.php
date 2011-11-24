<?php
 require (APPPATH . 'controllers/advanced/requre_login_or_redirect.php');
$curent_page = CI::model('core')->getParamFromURL ( 'curent_page' );

if (intval ( $curent_page ) < 1) {
	
	$curent_page = 1;

}

$items_per_page = CI::model('core')->optionsGetByKey ( 'default_items_per_page' );

$items_per_page = intval ( $items_per_page );

$layout = false;

$global_template_replaceables = false;

$content = array ();

$content ['content_layout_file'] = 'default_layout.php';

$action = CI::model('core')->getParamFromURL ( 'action' );

$username = CI::model('core')->getParamFromURL ( 'username' );
$action = CI::model('core')->getParamFromURL ( 'user_action' );
if ($action == false) {
	
	$action = CI::model('core')->getParamFromURL ( 'action' );
}

$this->template ['user_action'] = $action;

$id = CI::model('core')->getParamFromURL ( $id );

$the_active_site_template = CI::model('core')->optionsGetByKey ( 'curent_template' );

$the_active_site_template_dir = TEMPLATEFILES . $the_active_site_template . '/';

if (defined ( 'ACTIVE_TEMPLATE_DIR' ) == false) {
	
	define ( 'ACTIVE_TEMPLATE_DIR', $the_active_site_template_dir );

}

$the_active_site_template = CI::model('core')->optionsGetByKey ( 'curent_template' );

$the_active_site_template_dir = TEMPLATEFILES . $the_active_site_template . '/';

$the_template_url = site_url ( 'userfiles/' . TEMPLATEFILES_DIRNAME . '/' . $the_active_site_template );

$the_template_url = $the_template_url . '/';

define ( "TEMPLATE_URL", $the_template_url );

$user_session = array ();

$user_session = CI::library('session')->userdata ( 'user_session' );

$this->load->vars ( $this->template );

$page_start = ($curent_page - 1) * $items_per_page;

$page_end = ($page_start) + $items_per_page;

//	$data = CI::model('content')->getContent ( $posts_data, false, array ($page_start, $page_end ), false );


$file_maybe = (TEMPLATE_DIR . '/users/userbase/' . $action . '.php');
//p($file_maybe); 
if (is_file ( $file_maybe )) {
	
	$content ['content_filename'] = '/users/userbase/' . $action . '.php';
	
	$content ['content_layout_file']= '/dashboard/layout.php';
	
} else {
	switch ($action) {
		
		case 'profile' :
			
			require (APPPATH . 'controllers/advanced/userbase/profile.php');
			
			break;
		case 'articles' :
		case 'blog' :
		case 'trainings' :
		case 'services' :
		case 'products' :
		case 'gallery' :
			
			require (APPPATH . 'controllers/advanced/userbase/articles.php');
			
			break;
		
		case 'about' :
			
			require (APPPATH . 'controllers/advanced/userbase/about.php');
			
			break;
		
		case 'contacts' :
			
			require (APPPATH . 'controllers/advanced/userbase/contacts.php');
			
			break;
		
		default :

	 			/*
				 * Get request params, and redirect to proper location
				 */
				if ($_POST ['search_by_keyword']) {
				
				$searchTags = "/keyword:{$_POST['search_by_keyword']}";
				$location = 'userbase' . $searchTags;
				redirect ( $location );
			}
			
			$filter = array ();
			
			/*
				 * Get tags if such are requested
				 */
			$tags = CI::model('core')->getParamFromURL ( 'keyword' );
			if ($tags) {
				$this->template ['search_by_keyword'] = $tags;
				$filter ['search_by_keyword'] = $tags;
			}
			
			$type = CI::model('core')->getParamFromURL ( 'type' );
			if ($type) {
				switch ($type) {
					case 'top-contributors' :
						$ids = CI::model('users')->rankingsTopContibutors ( $limit = 1000 );
						//p($ids);
						$filter ['ids'] = $ids;
						break;
					case 'top-comentators' :
						$ids = CI::model('users')->rankingsTopCommenters ( $limit = 1000 );
						//p($ids);
						$filter ['ids'] = $ids;
						break;
					
					default :
						;
						break;
				}
			}
			
			$users_list = CI::model('users')->getUsers ( $filter, array ($page_start, $page_end ) );
			
			$this->template ['users_list'] = $users_list;
			
			$results_count = CI::model('users')->getUsers ( $filter, false, true );
			
			$content_pages_count = ceil ( $results_count / $items_per_page );
			
			//var_dump ( $results_count, $items_per_page );
			$this->template ['content_pages_count'] = $content_pages_count;
			
			$this->template ['content_pages_curent_page'] = $curent_page;
			
			//get paging urls
			$content_pages = CI::model('content')->pagingPrepareUrls ( false, $content_pages_count );
			
			//var_dump($content_pages);
			$this->template ['content_pages_links'] = $content_pages;
			
			$user_session ['user_action'] = $action;
			
			$this->load->vars ( $this->template );
			
			$content ['content_filename'] = 'users/userbase/list_users.php';
			
			break;
	
	}
}
if (is_dir ( $the_active_site_template_dir ) == false) {
	
	header ( "HTTP/1.1 500 Internal Server Error" );
	
	show_error ( 'No such template: ' . $the_active_site_template );
	
	exit ();

}

if (trim ( $content ['content_filename'] ) != '') {
	
	if (is_readable ( $the_active_site_template_dir . $content ['content_filename'] ) == true) {
		
		$this->load->vars ( $this->template );
		
		$content_filename_pre = $this->load->file ( $the_active_site_template_dir . $content ['content_filename'], true );
		
		$this->load->vars ( $this->template );
	
	}

}

if ($content ['content_layout_file'] != '') {
	
	//$this->template ['title'] = 'adasdsad';
	if (is_readable ( $the_active_site_template_dir . $content ['content_layout_file'] ) == true) {
		
		$this->load->vars ( $this->template );
		
		$layout = $this->load->file ( $the_active_site_template_dir . $content ['content_layout_file'], true );
	
	} elseif (is_readable ( $the_active_site_template_dir . 'default_layout.php' ) == true) {
		
		$this->load->vars ( $this->template );
		
		$layout = $this->load->file ( $the_active_site_template_dir . 'default_layout.php', true );
	
	} else {
		
		header ( "HTTP/1.1 500 Internal Server Error" );
		
		show_error ( "Layout file {$content ['content_layout_file']} is not readable or doesn't exist in the templates directory!" );
		
		exit ();
	
	}

}

if (trim ( $content ['content_filename'] ) != '') {
	
	if (is_readable ( $the_active_site_template_dir . $content ['content_filename'] ) == true) {
		
		$this->load->vars ( $this->template );
		
		$content_filename = $this->load->file ( $the_active_site_template_dir . $content ['content_filename'], true );
		
		$layout = str_ireplace ( '{content}', $content_filename, $layout );
		
	//	$layout = str_ireplace ( '{content}', $content_filename_pre, $layout );
	}

}

if (trim ( $content ['content_body'] ) != '') {
	
	$this->load->vars ( $this->template );
	
	$layout = str_ireplace ( '{content}', $content ['content_body'], $layout );

}

if (trim ( $taxonomy_data ) != '') {
	
	$this->load->vars ( $this->template );
	
	$layout = str_ireplace ( '{content}', $taxonomy_data, $layout );

}

$layout = CI::model('content')->applyGlobalTemplateReplaceables ( $layout, $global_template_replaceables = false );

//var_dump($layout);


$layout = CI::model('template')->parseMicrwoberTags ( $layout );
CI::library('output')->set_output ( $layout );

?>