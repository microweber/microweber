<?php

class Content extends CI_Controller {

	function __construct() {
		parent :: __construct();
		require_once (APPPATH . 'controllers/default_constructor.php');
		require_once (APPPATH . 'controllers/admin/default_constructor.php');

	}

	function index() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );

		if (CI::library('session')->userdata ( 'user' ) == false) {
			//redirect ( 'index' );
		}

		$this->load->vars ( $this->template );

		$layout =$this->load->view ( 'admin/layout', true, true );
		$primarycontent = '';
		$secondarycontent = '';

		$primarycontent =$this->load->view ( 'admin/content/index', true, true );
		$secondarycontent =$this->load->view ( 'admin/content/sidebar', true, true );
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );
		//CI::view('welcome_message');
		CI::library('output')->set_output ( $layout );
	}

	function pages_index() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );

		$data = array ( );
		$data ['content_type'] = 'page';
		$data = CI::model('content')->getContent ( $data );
		//var_dump($data);
		$dbdata ['pages'] = $data;

		$this->template ['dbdata'] = $dbdata;

		$this->load->vars ( $this->template );
		$layout =$this->load->view ( 'admin/layout', true, true );
		$nav =$this->load->view ( 'admin/content/pages_nav', true, true );
		$primarycontent =$this->load->view ( 'admin/content/pages_index', true, true );
		$primarycontent = $nav . $primarycontent;
		$secondarycontent =$this->load->view ( 'admin/content/sidebar', true, true );
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );
		//CI::view('welcome_message');
		CI::library('output')->set_output ( $layout );
	}
	function pages_delete() {
		$id = CI::model('core')->getParamFromURL ( 'id' );
		CI::model('content')->deleteContent ( $id );
		redirect ( 'admin/content/pages_index' );
	}

	function posts_manage_do_search_by_keyword() {
		if ($_POST ['search_this_category']) {
			if ($_POST ['categories']) {
				$togo_categories = "/categories:{$_POST['categories']}";
			} else {
				$togo_categories = false;
			}
		} else {
			$togo_categories = false;
		}

		if ($_POST ['keyword']) {
			$togo_keyword = "/keyword:{$_POST['keyword']}";
		} else {
			$togo_keyword = false;
		}

		if ($_POST ['voted']) {
			$togo_voted = "/voted:{$_POST['voted']}";
		} else {
			$togo_voted = false;
		}

		if ($_POST ['items_per_page']) {
			$togo_items_per_page = "/items_per_page:{$_POST['items_per_page']}";
		} else {
			$togo_items_per_page = false;
		}

		if ($_POST ['tags']) {
			$tags = explode ( ',', $_POST ['tags'] );
			$tags = trimArray ( $tags );
			$tags = array_unique_FULL ( $tags );
			asort ( $tags );
			$tags = implode ( ',', $tags );
			$togo_tags = "/tags:$tags";

		} else {
			$togo_tags = false;
		}

		if ($_POST ['is_from_rss']) {
			$togo_is_from_rss = "/is_from_rss:{$_POST['is_from_rss']}";
		} else {
			$togo_is_from_rss = false;
		}

		if ($_POST ['is_featured']) {
			$togo_is_is_featured = "/is_featured:{$_POST['is_featured']}";
		} else {
			$togo_is_is_featured = false;
		}

		if ($_POST ['with_comments']) {
			$togo_with_comments = "/with_comments:{$_POST['with_comments']}";
		} else {
			$togo_with_comments = false;
		}

		$gogo = site_url ( 'admin/content/posts_manage' ) . $togo_categories . $togo_keyword . $togo_tags . $togo_is_from_rss . $togo_is_is_featured . $togo_with_comments . $togo_voted . $togo_items_per_page;
		$gogo = reduce_double_slashes ( $gogo );
		header ( "Location: $gogo " );
		//var_dump ( $gogo );
		exit ();

	}

	function posts_manage_do_search() {

		if ($_POST ['categories']) {
			$togo_categories = "/categories:{$_POST['categories']}";
		} else {
			$togo_categories = false;
		}

		if ($_POST ['tags']) {
			$tags = explode ( ',', $_POST ['tags'] );
			$tags = trimArray ( $tags );
			$tags = array_unique_FULL ( $tags );
			asort ( $tags );
			$tags = array_remove_empty ( $tags );
			$tags = implode ( ',', $tags );
			$togo_tags = "/tags:$tags";
		} else {
			$togo_tags = false;
		}

		$val = implode ( ',', $_POST ['categories'] );
		//setcookie ( "admin_content_posts_manage_selected_categories", $val, time () + 36000 );
		$val = implode ( ',', $_POST ['tags'] );
		//setcookie ( "admin_content_posts_manage_selected_tags", $val, time () + 36000 );


		$gogo = site_url ( 'admin/content/posts_manage' ) . $togo_categories . $togo_tags;
		$gogo = reduce_double_slashes ( $gogo );
		header ( "Location: $gogo " );
		//var_dump ( $gogo );
		exit ();
	}

	function posts_manage() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		$data = array ( );
		$data ['content_type'] = 'post';

		$categories = CI::model('core')->getParamFromURL ( 'categories' );
		if ($categories != '') {
			setcookie ( "admin_content_posts_manage_selected_categories", $categories, time () + 36000 );
			$categories = explode ( ',', $categories );
			if (! empty ( $categories )) {

			}
		} else {
			/*if ($_COOKIE ['admin_content_posts_manage_selected_categories'] == '') {
			$categories = false;
			//$
			$taxonomy_data = array ( );
			$taxonomy_data ['taxonomy_type'] = 'category';
			$taxonomy_data ['to_table'] = 'table_content';
			$taxonomy_data = CI::model('taxonomy')->taxonomyGet ( $taxonomy_data );
			if (! empty ( $taxonomy_data )) {
			foreach ( $taxonomy_data as $item ) {
			$categories [] = intval ( $item ['id'] );
			}
			}
			$val = implode ( ',', $categories );
			setcookie ( "admin_content_posts_manage_selected_categories", $val, time () + 36000 );
			} else {
			$categories = explode ( ',', $_COOKIE ['admin_content_posts_manage_selected_categories'] );
			}*/
		}

		$data ['selected_categories'] = $categories;
		$this->template ['content_selected_categories'] = $categories;

		$keywords = CI::model('core')->getParamFromURL ( 'keyword' );
		$data ['search_by_keyword'] = $keywords;
		$this->template ['search_by_keyword'] = $keywords;

		$tags = CI::model('core')->getParamFromURL ( 'tags' );
		//var_dump ( $tags );
		//exit;
		if ($tags != '') {
			//setcookie ( "admin_content_posts_manage_selected_tags", $tags, time () + 36000 );
			$tags = explode ( ',', $tags );
			//var_dump($tags);
			//exit;
			if (empty ( $tags )) {
				$avalable_tags = CI::model('taxonomy')->taxonomyGetAvailableTags ( 'table_content' );
				$tags = $avalable_tags;
			}

		} else {
			/*if ($_COOKIE ['admin_content_posts_manage_selected_tags'] == '') {
			$val = implode ( ',', $tags );
			setcookie ( "admin_content_posts_manage_selected_tags", $val, time () + 36000 );
			} else {
			$tags = explode ( ',', $_COOKIE ['admin_content_posts_manage_selected_tags'] );
			}*/
		}

		if (! empty ( $tags )) {
			$data ['selected_tags'] = $tags;
			$this->template ['content_selected_tags'] = implode ( ',', $tags );
		}
		//var_dump ( $tags );


		//voted?
		$voted = CI::model('core')->getParamFromURL ( 'voted' );
		if (intval ( $voted ) > 0) {
			$data ['voted'] = $voted;
			$this->template ['selected_voted'] = intval ( $voted );
		} else {
			$this->template ['selected_voted'] = false;
		}
		$this->load->vars ( $this->template );

		$is_from_rss = CI::model('core')->getParamFromURL ( 'is_from_rss' );
		$data ['search_by_is_from_rss'] = $is_from_rss;
		$this->template ['search_by_is_from_rss'] = $is_from_rss;

		$is_featured = CI::model('core')->getParamFromURL ( 'is_featured' );
		$data ['is_featured'] = $is_featured;
		$this->template ['search_by_is_is_featured'] = $is_featured;

		$with_comments = CI::model('core')->getParamFromURL ( 'with_comments' );
		$data ['with_comments'] = $with_comments;
		$this->template ['search_by_with_comments'] = $with_comments;

		$original_criteria = $data;
		//var_dump($original_criteria);
		//paging
		$items_per_page = CI::model('core')->optionsGetByKey ( 'admin_default_items_per_page' );
		$items_per_page = intval ( $items_per_page );
		//	var_dump($items_per_page);
		if ($items_per_page != $_SESSION ['items_per_page']) {

			if ($_SESSION ['items_per_page'] > 10) {
				$items_per_page = $_SESSION ['items_per_page'];
			}
		}

		$user_items_per_page = CI::model('core')->getParamFromURL ( 'items_per_page' );
		if ((intval ( $user_items_per_page )) != 0) {
			$items_per_page = $user_items_per_page;
		}

		if ($items_per_page != $_SESSION ['items_per_page']) {
			$_SESSION ['items_per_page'] = $items_per_page;
		}
		//var_dump($_SESSION);
		$this->template ['search_items_per_page'] = $_SESSION ['items_per_page'];

		$items_per_page = $_SESSION ['items_per_page'];
		if (intval ( $items_per_page ) < 10) {

			$items_per_page = 10;
		}
		$curent_page = CI::model('core')->getParamFromURL ( 'curent_page' );
		if (intval ( $curent_page ) < 1) {
			$curent_page = 1;
		}

		$page_start = ($curent_page - 1) * $items_per_page;
		$page_end = ($page_start) + $items_per_page;
		//	var_dump($original_criteria);
		$results_count = CI::model('content')->getContent ( $original_criteria, false, false, true );

		$content_pages_count = ceil ( $results_count / $items_per_page );
		$this->template ['content_pages_count'] = $content_pages_count;
		//var_dump($content_pages_count);
		$this->template ['content_pages_curent_page'] = $curent_page;

		//get paging urls
		$content_pages = CI::model('content')->pagingPrepareUrls ( false, $content_pages_count );
		$this->template ['content_pages_links'] = $content_pages;

		$data = CI::model('content')->getContent ( $original_criteria, false, array ($page_start, $page_end ), false );

		//var_dump( $data);
		//exit;


		$this->load->vars ( $this->template );
		$this->template ['form_values'] = $data;
		$this->load->vars ( $this->template );

		$latest_posts = array ( );
		$latest_posts ['content_type'] = 'post';
		$latest_posts = CI::model('content')->getContent ( $latest_posts, false, array (0, 5 ), false );
		$this->template ['latest_posts'] = $latest_posts;

		$avalable_tags = CI::model('taxonomy')->taxonomyGetAvailableTags ( 'table_content' );
		$this->template ['avalable_tags'] = $avalable_tags;

		$this->load->vars ( $this->template );
		$layout =$this->load->view ( 'admin/layout', true, true );
		//$layout =$this->load->view ( 'admin/layout', true, true );
		$primarycontent =$this->load->view ( 'admin/content/posts_manage', true, true );
		$nav =$this->load->view ( 'admin/content/posts_nav', true, true );
		$primarycontent = $nav . $primarycontent;
		$secondarycontent =$this->load->view ( 'admin/content/sidebar', true, true );
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );
		//CI::view('welcome_message');
		//$nav =$this->load->view ( 'admin/content/posts_nav', true, true );
		CI::library('output')->set_output ( $layout );
	}

	function posts_edit_done() {
		$this->load->helper ( array ('form', 'url' ) );
		$this->load->library ( 'form_validation' );
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		$id = CI::model('core')->getParamFromURL ( 'id' );

		if ($id != 0) {
			$data = array ( );
			$data ['id'] = $id;
			$data ['content_type'] = 'post';
			//print $data;
			$data = CI::model('content')->getContent ( $data, false, array (0, 1 ) );
			//var_dump($data[0]);
			
			
			
			
			
			$this->template ['form_values'] = $data [0];
			$this->load->vars ( $this->template );
		}

		$this->template ['id'] = $id;
		$this->load->vars ( $this->template );
		$layout =$this->load->view ( 'admin/content/posts_edit_done', true, true );
		CI::library('output')->set_output ( $layout );
	}

	function posts_delete() {
		$id = CI::model('core')->getParamFromURL ( 'id' );
		//CI::model('content')->deleteContent ( $id );
		CI::model('content')->contentDelete ( $id );
		redirect ( 'admin/content/posts_manage' );
	}

	function posts_edit() {
		$this->load->helper ( array ('form', 'url' ) );
		$this->load->library ( 'form_validation' );
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );

		$avalable_tags = CI::model('taxonomy')->taxonomyGetAvailableTags ( 'table_content' );
		$this->template ['avalable_tags'] = $avalable_tags;
		$this->template ['load_google_map'] = true;

		$id = CI::model('core')->getParamFromURL ( 'id' );
		//print $id;
		//exit;
		if ($id != 0) {

			$this->template ['the_action'] = 'posts_edit';
			$this->load->vars ( $this->template );

			$data = array ( );
			$data ['id'] = $id;
			$data ['content_type'] = 'post';
				$data ['include_taxonomy'] = 'y';
			
			//print $data;
			$data = CI::model('content')->getContent ( $data, false, array (0, 1 ) );

			
			$this->template ['form_values'] = $data [0];
			$this->load->vars ( $this->template );
		} else {
			$this->template ['the_action'] = 'posts_add';
		}
		$this->form_validation->set_rules ( 'content_url', 'content url', 'trim|required' );
		$this->form_validation->set_rules ( 'content_title', 'content title', 'trim|required' );
		//$this->form_validation->set_message ( 'required', 'Your custom message here' );
		$this->form_validation->set_error_delimiters ( '<div class="error">', '</div>' );

		if ($this->form_validation->run () == FALSE) {
			//get all pages
			//$data = array ( );
			//$data ['content_type'] = 'post';
			//$data = CI::model('content')->getContent ( $data );
			//$this->template ['form_values_all_pages'] = $data;
			//	$this-Core_model::helpers_treeRead($data);
			if ($_POST) {
				$this->template ['form_values'] = $_POST;
			}
			$this->template ['form_validation_errors'] = $this->form_validation->_error_array;
			$this->load->vars ( $this->template );

		} else {
			//var_dump($_POST);
			//exit;
			$to_save = array ( );
			$to_save = $_POST;
			$to_save ['content_type'] = 'post';
			$to_save = CI::model('content')->saveContent ( $to_save );
			//exit(var_dump($to_save));
			redirect ( 'admin/content/posts_edit_done/id:' . $to_save );
			//redirect ( 'admin/content/posts_manage' );


			//CI::view ( 'formsuccess' );
		}

		$this->load->vars ( $this->template );

		$editsmall = CI::model('core')->getParamFromURL ( 'editsmall' );

		if ($editsmall == 'y') {
			$layout =$this->load->view ( 'admin/layout_small', true, true );
		} else {
			$layout =$this->load->view ( 'admin/layout', true, true );
		}

		//$layout =$this->load->view ( 'admin/layout', true, true );
		$nav =$this->load->view ( 'admin/content/posts_nav', true, true );
		$primarycontent =$this->load->view ( 'admin/content/posts_edit', true, true );
		$primarycontent = $nav . $primarycontent;
		$secondarycontent =$this->load->view ( 'admin/content/sidebar', true, true );
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );
		//CI::view('welcome_message');


		CI::library('output')->set_output ( $layout );

	}

	function pages_edit() {
		$this->load->helper ( array ('form', 'url' ) );
		$this->load->library ( 'form_validation' );
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		$id = CI::model('core')->getParamFromURL ( 'id' );
		if ($id != 0) {
			$data = array ( );
			$data ['id'] = $id;
			$data ['content_type'] = 'page';
			$data ['include_taxonomy'] = 'y';
			$data = CI::model('content')->getContent ( $data );
			//var_dump($data);
			$this->template ['form_values'] = $data [0];
			$this->load->vars ( $this->template );
		}

		$this->form_validation->set_rules ( 'content_url', 'content url', 'trim|required' );
		$this->form_validation->set_rules ( 'content_title', 'content title', 'trim|required' );
		//$this->form_validation->set_message ( 'required', 'Your custom message here' );
		$this->form_validation->set_error_delimiters ( '<div class="error">', '</div>' );

		if ($this->form_validation->run () == FALSE) {
			//get all pages
			$data = array ( );
			$data ['content_type'] = 'page';
			$data = CI::model('content')->getContent ( $data );
			$this->template ['form_values_all_pages'] = $data;
			//	$this-Core_model::helpers_treeRead($data);
			if ($_POST) {
				$this->template ['form_values'] = $_POST;
			}
			$this->template ['form_validation_errors'] = $this->form_validation->_error_array;
			$this->load->vars ( $this->template );

		} else {
			//var_dump($_POST);
			//exit;
			$to_save = array ( );
			$to_save = $_POST;
			$to_save ['content_type'] = 'page';
			$to_save = CI::model('content')->saveContent ( $to_save );

			redirect ( 'admin/content/pages_index' );

			//CI::view ( 'formsuccess' );
		}

		$this->load->vars ( $this->template );
		$layout =$this->load->view ( 'admin/layout', true, true );
		//$layout =$this->load->view ( 'admin/layout', true, true );


		$nav =$this->load->view ( 'admin/content/pages_nav', true, true );
		//$primarycontent =$this->load->view ( 'admin/content/posts_edit', true, true );
		$primarycontent = $nav . $primarycontent;

		$primarycontent =$this->load->view ( 'admin/content/pages_edit', true, true );
		$primarycontent = $nav . $primarycontent;
		$secondarycontent =$this->load->view ( 'admin/content/sidebar', true, true );
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );
		//CI::view('welcome_message');
		CI::library('output')->set_output ( $layout );
	}

	function pages_edit_ajax_content_subtype() {
		//var_dump ( $_POST );


		// $this->firecms = get_instance();
		//if($_POST['content_subtype'] == 'blog_section'){
		$this->template ['form_values'] = $_POST;
		$this->load->vars ( $this->template );
		$layout =$this->load->view ( 'admin/content/pages_edit_ajax_content_subtype', true, true );
		CI::library('output')->set_output ( $layout );
		//}
	}

	function menus_show_menu_ajax() {
		$edit = CI::model('core')->getParamFromURL ( 'id' );
		//var_dump ( $edit );
		if (intval ( $edit ) != 0) {
			$data = array ( );
			$data ['item_type'] = 'menu';
			$data ['id'] = $edit;
			$menu = CI::model('content')->getMenus ( $data );
			$this->template ['item'] = $menu [0];
		}

		$this->load->vars ( $this->template );
		$layout =$this->load->view ( 'admin/content/menus_show_menu_ajax.php', true, true );
		exit ( $layout );
	}

	function menus_edit_small_menu_item() {
		$edit = CI::model('core')->getParamFromURL ( 'id' );
		$form = CI::model('core')->getParamFromURL ( 'form' );
		$this->template ['form_id'] = $form;
		//var_dump ( $edit );
		if (intval ( $edit ) != 0) {
			$data = array ( );
			$data ['item_type'] = 'menu_item';
			$data ['id'] = $edit;
			$menu = CI::model('content')->getMenus ( $data );
			$this->template ['form_values'] = $menu [0];
		}
		//	var_dump ( $menu );
		$this->load->vars ( $this->template );
		$layout =$this->load->view ( 'admin/content/menus_edit_small_menu_item.php', true, true );
		exit ( $layout );
	}

	function menus_edit_small() {
		if ($_POST) {
			$to_save = $_POST;
			$to_save ['item_type'] = 'menu';
			CI::model('content')->saveMenu ( $to_save );
		}

		$edit = CI::model('core')->getParamFromURL ( 'id' );
		//var_dump ( $edit );
		if (intval ( $edit ) != 0) {
			$data = array ( );
			$data ['item_type'] = 'menu';
			$data ['id'] = $edit;
			$menu = CI::model('content')->getMenus ( $data );
			$this->template ['form_values'] = $menu [0];
		}

		$this->load->vars ( $this->template );
		$layout =$this->load->view ( 'admin/content/menus_edit_small.php', true, true );
		exit ( $layout );
	}

	function menus_delete_menu_item() {
		$delete_menu_item = CI::model('core')->getParamFromURL ( 'delete_menu_item' );
		$delete_menu_item = $_POST ['delete_menu_item'];

		if (intval ( $delete_menu_item ) != 0) {
			$table = TABLE_PREFIX . 'menus';
			$data = array ( );
			$data ['id'] = $delete_menu_item;
			$del = CI::model('core')->deleteData ( $table, $data, 'menus' );
			//redirect ( 'admin/content/menus' );
			exit ( 'ok' );
		}
	}

	function menus_save_menu_item() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );

		$data = $_POST;
		$data ['item_type'] = 'menu_item';

		if ($data ['content_id'] != 0) {
			$data ['taxonomy_id'] = '0';
			$data ['menu_url'] = '0';
		}

		if ($data ['taxonomy_id'] != 0) {
			$data ['content_id'] = '0';
			$data ['menu_url'] = '0';
		}

		if ($data ['menu_url'] != 0) {
			$data ['taxonomy_id'] = '0';
			$data ['content_id'] = '0';
		}

		CI::model('content')->saveMenu ( $data );
		redirect ( 'admin/content/menus' );
		exit ();

	}

	function menus() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
$this->load->helper(array('form', 'url'));
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules ( 'menu_unique_id', 'Menu unique ID', 'trim|required' );
		//$this->form_validation->set_rules ( 'content_title', 'content title', 'trim|required' );
		//$this->form_validation->set_message ( 'required', 'Your custom message here' );
		$this->form_validation->set_error_delimiters ( '<div class="error">', '</div>' );

		//delete menu item


		$move_up = CI::model('core')->getParamFromURL ( 'move_up' );
		$move_down = CI::model('core')->getParamFromURL ( 'move_down' );

		if (intval ( $move_down ) != 0) {
			CI::model('content')->reorderMenuItem ( 'down', $move_down );
			redirect ( 'admin/content/menus' );
		}

		if (intval ( $move_up ) != 0) {
			CI::model('content')->reorderMenuItem ( 'up', $move_up );
			redirect ( 'admin/content/menus' );
		}

		$data = array ( );
		$data ['item_type'] = 'menu';
		$menus = CI::model('content')->getMenus ( $data );
		$this->template ['menus'] = $menus;

		//edit menu
		$edit = CI::model('core')->getParamFromURL ( 'edit' );
		//var_dump ( $edit );
		if (intval ( $edit ) != 0) {
			$data = array ( );
			$data ['item_type'] = 'menu';
			$data ['id'] = $edit;
			$menu = CI::model('content')->getMenus ( $data );
			$this->template ['form_values'] = $menu [0];
		}

		//delete menu
		$delete = CI::model('core')->getParamFromURL ( 'delete' );
		if (intval ( $delete ) != 0) {
			$table = TABLE_PREFIX . 'menus';
			$data = array ( );
			$data ['id'] = $delete;
			$del = CI::model('core')->deleteData ( $table, $data, 'menus' );
			CI::model('content')->fixMenusPositions ();
			redirect ( 'admin/content/menus' );
		}

		if ($this->form_validation->run () == FALSE) {
			//get all pages
			//$data = array ( );
			//$data ['content_type'] = 'page';
			//$data = CI::model('content')->getContent ( $data );
			//$this->template ['form_values_all_pages'] = $data;
			//$this-Core_model::helpers_treeRead($data);


			if ($_POST) {
				$this->template ['form_values'] = $_POST;
			}
			$this->template ['form_validation_errors'] = $this->form_validation->_error_array;
			$this->load->vars ( $this->template );

		} else {
			$to_save = $_POST;
			$to_save ['item_type'] = 'menu';
			CI::model('content')->saveMenu ( $to_save );
			redirect ( 'admin/content/menus' );
		}

		$this->load->vars ( $this->template );
		$layout =$this->load->view ( 'admin/layout', true, true );
		//$layout =$this->load->view ( 'admin/layout', true, true );
		$primarycontent =$this->load->view ( 'admin/content/menus_list', true, true );
		$secondarycontent =$this->load->view ( 'admin/content/sidebar', true, true );
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );
		//CI::view('welcome_message');
		CI::library('output')->set_output ( $layout );

	}

	function taxonomy_categories_delete() {
		$delete_id = getParamFromURL ( 'id' );
		$to_go = site_url ( 'admin/content/taxonomy_categories' );
		$delete_id = intval ( $delete_id );

		CI::model('taxonomy')->taxonomyDelete ( $delete_id );
		//exit ('1');
		//header("Location: $to_go");
		//redirect (  );
		redirect ( 'admin/content/taxonomy_categories' );

	}

	function taxonomy_categories_move() {

		$id = CI::model('core')->getParamFromURL ( 'id' );
		$dir = CI::model('core')->getParamFromURL ( 'direction' );

		CI::model('taxonomy')->taxonomyChangePosition ( $id, $dir );

		redirect ( 'admin/content/taxonomy_categories' );
	}

	function taxonomy_categories() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		$this->template ['load_google_map'] = true;
		//print $delete_id;
$this->load->helper(array('form', 'url'));
		
		$this->load->library('form_validation');

		$this->form_validation->set_rules ( 'taxonomy_value', 'Category name', 'trim|required' );
		//$this->form_validation->set_rules ( 'content_title', 'content title', 'trim|required' );
		//$this->form_validation->set_message ( 'required', 'Your custom message here' );
		$this->form_validation->set_error_delimiters ( '<div class="error">', '</div>' );

		$data = array ( );
		$data ['taxonomy_type'] = 'category';
		$data ['to_table'] = 'table_content';

		$taxonomy = CI::model('taxonomy')->taxonomyGet ( $data );
		$this->template ['taxonomy_items'] = $taxonomy;
		//taxonomyGet


		$id = CI::model('core')->getParamFromURL ( 'category_edit' );
		if ($id != 0) {
			$data = array ( );
			$data ['id'] = $id;
$data ['include_taxonomy'] = 'y';
			$data = CI::model('taxonomy')->taxonomyGet ( $data );
			//var_dump($data);
			$this->template ['form_values'] = $data [0];
			$this->load->vars ( $this->template );
		}

		if ($this->form_validation->run () == FALSE) {

			if ($_POST) {
				$this->template ['form_values'] = $_POST;
			}
			$this->template ['form_validation_errors'] = $this->form_validation->_error_array;
			$this->load->vars ( $this->template );

		} else {
			$to_save = $_POST;
			$to_save ['taxonomy_type'] = 'category';
			$to_save ['to_table'] = 'table_content';
			CI::model('taxonomy')->taxonomySave ( $to_save );
			redirect ( 'admin/content/taxonomy_categories' );
		}

		$this->load->vars ( $this->template );
		$layout =$this->load->view ( 'admin/layout', true, true );
		//$layout =$this->load->view ( 'admin/layout', true, true );
		$primarycontent =$this->load->view ( 'admin/content/taxonomy_categories', true, true );
		$nav =$this->load->view ( 'admin/content/taxonomy_nav', true, true );
		$primarycontent = $nav . $primarycontent;
		$secondarycontent =$this->load->view ( 'admin/content/sidebar', true, true );
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );
		//CI::view('welcome_message');
		CI::library('output')->set_output ( $layout );

	}

	function taxonomy_tags_update() {
		if ($_POST) {
			CI::model('taxonomy')->taxonomyTagsCombine ( $_POST ["tag_old_name"], $_POST ["taxonomy_value"] );
		}
		redirect ( 'admin/content/taxonomy_tags' );
	}

	function taxonomy_tags_delete() {
		if ($_POST) {
			CI::model('taxonomy')->taxonomyTagsDelete ( $_POST ["taxonomy_value"] );
		}
		print 'ok';
		//	redirect ( 'admin/content/taxonomy_tags' );
	}

	function taxonomy_tags_delete_less_than() {
		if ($_POST) {
			CI::model('taxonomy')->taxonomyTagsDeleteLessThanCount ( $_POST ["less_than"] );
		}

		redirect ( 'admin/content/taxonomy_tags' );

	}

	function taxonomy_tags() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );

		$tags = CI::model('taxonomy')->taxonomyTagsGetOrderByPopularity ();
		$this->template ['form_values'] = $tags;

		$this->load->vars ( $this->template );
		$layout =$this->load->view ( 'admin/layout', true, true );
		//$layout =$this->load->view ( 'admin/layout', true, true );
		$primarycontent =$this->load->view ( 'admin/content/taxonomy_tags', true, true );
		$nav =$this->load->view ( 'admin/content/taxonomy_nav', true, true );
		$primarycontent = $nav . $primarycontent;
		$secondarycontent =$this->load->view ( 'admin/content/sidebar', true, true );
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );
		//CI::view('welcome_message');
		CI::library('output')->set_output ( $layout );

	}

	function taxonomy_delete() {
		$id = CI::model('core')->getParamFromURL ( 'id' );
		CI::model('taxonomy')->taxonomyDelete ( $id );
	}

	function content_delete() {
		$id = CI::model('core')->getParamFromURL ( 'id' );
		CI::model('content')->contentDelete ( $id );
	}

	function lookEstateParse2() {
		$table_content = 'firecms_content';

		$q = "SELECT
* FROM
  `pages111`
  where ParentPage = 26930

   ;";

		$query = CI::db()->query ( $q );
		$get_all = $query->result_array ();

		//exit();


		foreach ( $get_all as $item ) {

			$q = " select id  from $table_content where the_old_id = '{$item['id']}'   ";
			$q = CI::db()->query ( $q );
			$q = $q->result_array ();
			$q = $q [0];
			var_dump ( $item ['id'] );

			$get_contet = array ( );
			$get_contet ['id'] = $q ['id'];
			$get_contet = CI::model('content')->getContent ( $get_contet );
			$get_contet = $get_contet [0];

			$get_contet ['taxonomy_categories'] = array (11493, 35795, 35796 );
			$get_contet ['is_featured'] = 'n';
			$get_contet ['content_type'] = 'post';
			//$get_contet ['content_type'] = 'post';
			$get_contet ['content_parent'] = 12127;

			//	var_dump($get_contet);


			CI::model('content')->saveContent ( $get_contet );

			//taxonomy_categories
		}

		$table_content = 'firecms_content';
		$q = "
SELECT * from firecms_content where tag1 = 'from_old_site' and tag2 is null limit 0, 1000

		";
		//print $q;
		//$get_all = CI::model('core')->dbQuery ( $q );
		$query = CI::db()->query ( $q );
		$get_all = $query->result_array ();

		$row = 1;
		$handle = fopen ( BASEPATHSTATIC . "pages_left_join_images.csv", "r" );
		while ( ($data = fgetcsv ( $handle, 1000, ";" )) !== FALSE ) {
			$num = count ( $data );
			//echo "<p> $num fields in line $row: <br /></p>\n";
			$row ++;
			//print $data[0];
			//print $data[27];
			if (($data [27] != NULL) and $data [27] != 'NULL' and ($data [27] != '')) {
				$img = 'http://maksoft.net/' . $data [27];
				$newfilename = MEDIAFILES . $data [27];
				$copy = CI::model('core')->url_getPageToFile ( $img, $newfilename );
				//$copy = "http://test.look-estates.com/userfiles/media/".$data[27];
				$copy = "{MEDIAURL}" . $data [27];
				//exit($copy);
				//$img = $data[27];
			} else {
				$copy = false;
			}
			//var_dump($img);
			foreach ( $get_all as $the_content ) {
				//var_dump($data[0]);
				if (intval ( $the_content ['the_old_id'] ) == intval ( $data [0] )) {
					if ($img != false) {
						$content = CI::model('content')->contentGetById ( $the_content ['id'] );
						$data_to_save = $content;

						$img_src = "<img src=\"$copy\" allign=\"left\" />";
						//var_dump($img_src);
						$data_to_save ['content_body'] = $img_src . $data_to_save ['content_body'];
						$data_to_save ['tag2'] = 'parsed';
						//var_dump($data_to_save);
						$saved = CI::model('content')->saveContent ( $data_to_save );

						print $saved . "|" . $the_content ['id'] . "|" . $img;
						print "\n";
					}
				}
			}

			for($c = 0; $c < $num; $c ++) {
				//echo $data[$c] . "<br />\n";
				$val = $data [$c] . "<br />\n";
				if (stristr ( $val, 'web/images/upload' ) == true) {
					//print $val;
				}
			}
		}
		fclose ( $handle );

		exit ( 'ok' );

	}










	function lookEstateParse5() {
		$table_content = 'firecms_content';

		//$q = "SELECT * FROM   `pages111`   where ParentPage = 34671  order by date_modified ASC   ;";



		$q = 'SELECT * FROM pages111  where id = 27194  order by date_modified ASC';




		$query = CI::db()->query ( $q );
		$get_all = $query->result_array ();

		//exit();


		foreach ( $get_all as $item ) {

			$q = " select id  from $table_content where the_old_id = '{$item['id']}'   ";
			$q = CI::db()->query ( $q );
			$q = $q->result_array ();
			$q = $q [0];
			var_dump ( $item ['id'] );

			$get_contet = array ( );
			$get_contet ['id'] = $q ['id'];
			$get_contet = CI::model('content')->getContent ( $get_contet );
			$get_contet = $get_contet [0];

			//$get_contet ['taxonomy_categories'] = array (3, 5990, 37237); 
			$get_contet ['taxonomy_categories'] = array (11284, 34477, 11290);
			$get_contet ['taxonomy_tags_csv'] = 'Имоти, България, София'; 
			//$get_contet ['is_featured'] = 'n';
			$get_contet ['is_featured'] = 'n';
			$get_contet ['content_type'] = 'post'; 
			$get_contet ['content_body'] = $item['textStr'];
			$get_contet ['updated_on'] = $item['date_modified'];
			$get_contet ['created_on'] = $item['date_modified'];




			//$get_contet ['content_type'] = 'post';
			$get_contet ['content_parent'] = 7;

			//	var_dump($get_contet);
			//remove tag2



			CI::model('content')->saveContent ( $get_contet );
			$qq = " UPDATE $table_content set tag2 = null where id = {$q ['id']}   ";
			//var_dump($qq);
			CI::model('core')->dbQ( $qq );
			//taxonomy_categories
		}

		$table_content = 'firecms_content';
		$q = "
SELECT * from $table_content where tag1 = 'from_old_site' and tag2 is null limit 0, 1000

		";
		//print $q;
		$get_all = CI::model('core')->dbQuery ( $q );
		//$query = CI::db()->query ( $q );
		//$get_all = $query->result_array ();

		$row = 1;
		$handle = fopen ( BASEPATHSTATIC . "pages_left_join_images.csv", "r" );
		while ( ($data = fgetcsv ( $handle, 1000, ";" )) !== FALSE ) {
			$num = count ( $data );
			//echo "<p> $num fields in line $row: <br /></p>\n";
			$row ++;
			//print $data[0];
			//print $data[27];
			//var_dump( $data);
			if (($data [27] != NULL) and $data [27] != 'NULL' and ($data [27] != '')) {
				$img = 'http://maksoft.net/' . $data [27];
				$newfilename = MEDIAFILES . $data [27];
				//var_dump($newfilename);
				$copy = CI::model('core')->url_getPageToFile ( $img, $newfilename );
				//$copy = "http://test.look-estates.com/userfiles/media/".$data[27];
				$copy = "{MEDIAURL}" . $data [27];
				//exit($copy);
				//$img = $data[27];
			} else {
				$copy = false;
				$img = false;
			}
			//var_dump($img);
			foreach ( $get_all as $the_content ) {
				//var_dump($data[0], $the_content ['the_old_id'] );
				if (intval ( $the_content ['the_old_id'] ) == intval ( $data [0] )) {
					if ($copy != false) {
						$content = CI::model('content')->contentGetById ( $the_content ['id'] );
						$data_to_save = $content;

						$img_src = "<img src=\"$copy\" allign=\"left\" />";
						var_dump($img_src);
						//exit;
						$data_to_save ['content_body'] = $img_src . $data_to_save ['content_body'];
						$data_to_save ['tag2'] = 'parsed';
						//var_dump($data_to_save);
						$saved = CI::model('content')->saveContent ( $data_to_save );

						print $saved . "|" . $the_content ['id'] . "|" . $img;
						print "\n";
					}
				}
			}

			for($c = 0; $c < $num; $c ++) {
				//echo $data[$c] . "<br />\n";
				$val = $data [$c] . "<br />\n";
				if (stristr ( $val, 'web/images/upload' ) == true) {
					//print $val;
				}
			}
		}
		fclose ( $handle );

		exit ( 'ok' );

	}













	function lookEstateParse4() {
		$table_content = 'firecms_content';

		$regex = 'http://www.look-estates.com/';

		$q = " SELECT * from firecms_content where content_body RLIKE '$regex' 	";
		//var_dump($q );
		$query = CI::db()->query ( $q );
		$get_all = $query->result_array ();

		foreach ( $get_all as $item ) {

			$new = str_ireplace ( $regex, '{SITE_URL}', $item ['content_body'] );
			//var_dump($new);
			$q = " UPDATE firecms_content set content_body = '$new' where id={$item['id']}   ";
			var_dump ( $q );
			$query = CI::db()->query ( $q );

		}

		//	var_dump($get_all);
	}

	function lookEstateParse3() {
		$table_content = 'firecms_content';
		$q = "
SELECT * from firecms_content where tag1 = 'from_old_site' and tag2 is null limit 0, 100

		";
		//print $q;
		//$get_all = CI::model('core')->dbQuery ( $q );
		$query = CI::db()->query ( $q );
		$get_all = $query->result_array ();

		$row = 1;
		$handle = fopen ( BASEPATHSTATIC . "pages_left_join_images.csv", "r" );
		while ( ($data = fgetcsv ( $handle, 1000, ";" )) !== FALSE ) {
			$num = count ( $data );
			//echo "<p> $num fields in line $row: <br /></p>\n";
			$row ++;
			//print $data[0];
			//print $data[27];
			if (($data [27] != NULL) and $data [27] != 'NULL') {
				$img = 'http://maksoft.net/' . $data [27];
				$newfilename = MEDIAFILES . $data [27];
				$copy = CI::model('core')->url_getPageToFile ( $img, $newfilename );
				//$copy = "http://test.look-estates.com/userfiles/media/".$data[27];
				$copy = "{MEDIAURL}" . $data [27];
				//exit($copy);
				//$img = $data[27];
			} else {
				$copy = false;
			}
			//var_dump($img);
			foreach ( $get_all as $the_content ) {
				//var_dump($data[0]);
				if (intval ( $the_content ['the_old_id'] ) == intval ( $data [0] )) {
					if ($img != false) {
						$content = CI::model('content')->contentGetById ( $the_content ['id'] );
						$data_to_save = $content;

						$img_src = "<img src=\"$copy\" allign=\"left\" />";
						//var_dump($img_src);
						$data_to_save ['content_body'] = $img_src . $data_to_save ['content_body'];
						$data_to_save ['tag2'] = 'parsed';
						//var_dump($data_to_save);
						$saved = CI::model('content')->saveContent ( $data_to_save );

						print $saved . "|" . $the_content ['id'] . "|" . $img;
						print "\n";
					}
				}
			}

			for($c = 0; $c < $num; $c ++) {
				//echo $data[$c] . "<br />\n";
				$val = $data [$c] . "<br />\n";
				if (stristr ( $val, 'web/images/upload' ) == true) {
					//print $val;
				}
			}
		}
		fclose ( $handle );

		exit ( 'ok' );

	}

	function lookEstateParse1() {
		$table_content = 'firecms_content';
		$data = array ( );
		$data ['tag1'] = 'from_old_site';
		//$get_all = CI::model('content')->getContent ( $data );


		$q = " SELECT * from $table_content where tag1 = 'from_old_site' ";
		//$get_all = CI::model('core')->dbQuery ( $q );
		$query = CI::db()->query ( $q );
		$get_all = $query->result_array ();
		//var_dump ( $get_all );


		foreach ( $get_all as $item ) {
			var_dump ( $item ['id'] );
			$taxnomy_save = array ( );
			$taxnomy_save ['to_table'] = 'table_content';
			$taxnomy_save ['to_table_id'] = $item ['id'];
			$taxnomy_save ['taxonomy_type'] = 'category_item';
			$taxnomy_save ['content_type'] = 'post';
			$taxnomy_save ['parent_id'] = 5;

			$q = " INSERT INTO firecms_taxonomy set to_table='{$taxnomy_save ['to_table']}' ,
to_table_id = '{$taxnomy_save ['to_table_id']}',
taxonomy_type = '{$taxnomy_save ['taxonomy_type']}',
parent_id = '{$taxnomy_save ['parent_id']}',
content_type = '{$taxnomy_save ['content_type']}'

			";
			$qq = $query = CI::db()->query ( "  $q  " );
			//print $q;


			//CI::model('taxonomy')->taxonomySave ( $taxnomy_save );
			//var_dump ( $taxnomy_save );


		}

		exit ();

	}

	function lookEstateParse() {

		$table_content = 'firecms_content';
		//$orderby [0] = 'updated_on';
		//$orderby [1] = 'DESC';
		$data = array ( );
		//$data ['content_id'] = $content_id;
		//$data ['item_parent'] = $menu_id;
		//	$get = CI::model('core')->getDbData ( $table, $data, $limit = false, $offset = false, $orderby, $cache_group = false, false );
		//var_dump($get);
		$table = 'pages111';
		$query = CI::db()->query ( " SET NAMES utf8  " );

		$query = CI::db()->query ( " DELETE FROM $table where id=26311 and id= 26307 " );

		/*$query = CI::db()->query ( " SELECT * FROM $table where
		id!=26307
		and
		id!=26311
		and
		id!=26314
		and
		id!=26315



		limit 0, 30000 " );*/

		$query = CI::db()->query ( " SELECT * FROM pages111 where
		id not in (SELECT the_old_id from firecms_content where tag1 = 'from_old_site' group by the_old_id)



		limit 0, 50 " );

		$get = $query->result_array ();

		$queries = array ( );
		$queries2 = array ( );
		foreach ( $get as $item ) {
			//var_dump($item);


			$item = array_change_key_case ( $item, CASE_LOWER );
			$to_insert = array ( );
			$to_insert ['tag1'] = 'from_old_site';
			$to_insert ['content_type'] = 'post';

			$to_insert ['content_url'] = strtolower ( ($item ['name']) );
			$to_insert ['content_url'] = CI::model('core')->url_title ( $to_insert ['content_url'] );

			//$to_insert ['content_body'] = ($item ['textstr']);


			$body = ($item ['textstr']);
			$body = ($body);
			$rem = '<p><a title="Ð?Ð¾Ð²Ð¸Ð½Ð¸ Ð·Ð° Ð½ÐµÐ´Ð²Ð¸Ð¶Ð¸Ð¼Ð¸ Ð¸Ð¼Ð¾Ñ‚Ð¸" href="http://www.look-estates.com/page.php?n=26579&amp;SiteID=507&amp;view_id=26579"><span style="color: rgb(255, 0, 0);"><strong>Ð?ÐžÐ’Ð˜Ð?Ð˜ </strong></span></a><span style="color: rgb(255, 0, 0);"><strong>      </strong></span><a title="Ð?Ð¾Ð²Ð¸Ð½Ð¸ Ñ?Ð²ÑŠÑ€Ð·Ð°Ð½Ð¸ Ñ? Ñ†ÐµÐ½Ð¸Ñ‚Ðµ Ð½Ð° Ð½Ð°Ð´Ð²Ð¸Ð¶Ð¸Ð¼Ð¸Ñ‚Ðµ Ð¸Ð¼Ð¾Ñ‚Ð¸" href="http://www.look-estates.com/page.php?n=28760&amp;SiteID=507&amp;view_id=26316"><span style="color: rgb(0, 0, 255);"><strong>Ð—Ð? Ð¦Ð•Ð?Ð˜</strong></span></a><span style="color: rgb(255, 0, 0);"><strong>       </strong></span><a title="Ð¦ÐµÐ½Ð¸ Ð½Ð° Ð½ÐµÐ´Ð²Ð¸Ð¶Ð¸Ð¼Ð¸Ñ‚Ðµ Ð¸Ð¼Ð¾Ñ‚Ð¸" href="http://www.look-estates.com/page.php?n=26316&amp;SiteID=507&amp;view_id=26316"><span style="color: rgb(0, 128, 0);"><strong>Ð¡Ð¢Ð?Ð¢Ð˜Ð¡Ð¢Ð˜ÐšÐ? </strong></span></a><span style="color: rgb(255, 0, 0);"><strong>   </strong></span><a title="Ð¡Ð°Ð¹Ñ‚Ð¾Ð²Ðµ Ð½Ð° Ð¼ÐµÐ´Ð¸Ð¸" href="http://www.look-estates.com/page.php?n=30639&amp;SiteID=507"><span style="color: rgb(255, 102, 0);"><strong>ÐœÐ•Ð”Ð˜Ð˜</strong></span></a></p>';
			//$body = str_ireplace($rem, '',$body);


			//$rem = '<a title="Ð?Ð¾Ð²Ð¸Ð½Ð¸ Ð·Ð° Ð½ÐµÐ´Ð²Ð¸Ð¶Ð¸Ð¼Ð¸ Ð¸Ð¼Ð¾Ñ‚Ð¸" href="http://www.look-estates.com/page.php?n=26579&amp;SiteID=507&amp;view_id=26579"><span style="color: rgb(255, 0, 0);"><strong>Ð?ÐžÐ’Ð˜Ð?Ð˜ </strong></span></a>';
			//	$body = str_ireplace($rem, '',$body);
			$rem = 'class=mceVisualAid';
			$body = str_ireplace ( $rem, '  ', $body );

			//$rem = '<p><a href="http://www.look-estates.com/page.php?n=26579&amp;SiteID=507&amp;view_id=26579" title="Ã?Â?Ã?Â¾Ã?Â²Ã?Â¸Ã?Â½Ã?Â¸ Ã?Â·Ã?Â° Ã?Â½Ã?ÂµÃ?Â´Ã?Â²Ã?Â¸Ã?Â¶Ã?Â¸Ã?Â¼Ã?Â¸ Ã?Â¸Ã?Â¼Ã?Â¾Ã‘â€šÃ?Â¸"><span style="color: rgb(255, 0, 0);"><strong>Ã?Â?Ã?Å¾Ã?â€™Ã?ËœÃ?Â?Ã?ËœÃ‚&nbsp;</strong></span></a><span style="color: rgb(255, 0, 0);"><strong>Ã‚&nbsp;Ã‚&nbsp;Ã‚&nbsp;Ã‚&nbsp;Ã‚&nbsp;Ã‚&nbsp;</strong></span><a href="http://www.look-estates.com/page.php?n=28760&amp;SiteID=507&amp;view_id=26316" title="Ã?Â?Ã?Â¾Ã?Â²Ã?Â¸Ã?Â½Ã?Â¸ Ã‘Â?Ã?Â²Ã‘Å Ã‘â‚¬Ã?Â·Ã?Â°Ã?Â½Ã?Â¸ Ã‘Â? Ã‘â€ Ã?ÂµÃ?Â½Ã?Â¸Ã‘â€šÃ?Âµ Ã?Â½Ã?Â° Ã?Â½Ã?Â°Ã?Â´Ã?Â²Ã?Â¸Ã?Â¶Ã?Â¸Ã?Â¼Ã?Â¸Ã‘â€šÃ?Âµ Ã?Â¸Ã?Â¼Ã?Â¾Ã‘â€šÃ?Â¸"><span style="color: rgb(0, 0, 255);"><strong>Ã?â€”Ã?Â? Ã?Â¦Ã?â€¢Ã?Â?Ã?Ëœ</strong></span></a><span style="color: rgb(255, 0, 0);"><strong>Ã‚&nbsp;Ã‚&nbsp;Ã‚&nbsp;Ã‚&nbsp;Ã‚&nbsp;Ã‚&nbsp; </strong></span><a href="http://www.look-estates.com/page.php?n=26316&amp;SiteID=507&amp;view_id=26316" title="Ã?Â¦Ã?ÂµÃ?Â½Ã?Â¸ Ã?Â½Ã?Â° Ã?Â½Ã?ÂµÃ?Â´Ã?Â²Ã?Â¸Ã?Â¶Ã?Â¸Ã?Â¼Ã?Â¸Ã‘â€šÃ?Âµ Ã?Â¸Ã?Â¼Ã?Â¾Ã‘â€šÃ?Â¸"><span style="color: rgb(0, 128, 0);"><strong>Ã?Â¡Ã?Â¢Ã?Â?Ã?Â¢Ã?ËœÃ?Â¡Ã?Â¢Ã?ËœÃ?Å¡Ã?Â?Ã‚&nbsp;</strong></span></a><span style="color: rgb(255, 0, 0);"><strong>Ã‚&nbsp;Ã‚&nbsp; </strong></span><a href="http://www.look-estates.com/page.php?n=30639&amp;SiteID=507" title="Ã?Â¡Ã?Â°Ã?Â¹Ã‘â€šÃ?Â¾Ã?Â²Ã?Âµ Ã?Â½Ã?Â° Ã?Â¼Ã?ÂµÃ?Â´Ã?Â¸Ã?Â¸"><span style="color: rgb(255, 102, 0);"><strong>Ã?Å“Ã?â€¢Ã?â€?Ã?ËœÃ?Ëœ</strong></span></a></p>';
			//$body = str_ireplace($rem, '  ',$body);


			//var_dump($body);
			$to_insert ['content_body'] = $body;

			$to_insert ['taxonomy_categories'] = array (5 );
			$to_insert ['taxonomy_tags_csv'] = CI::model('taxonomy')->taxonomyGenerateAndGuessTagsFromString ( $item ['textstr'] );
			$to_insert ['content_title'] = $item ['name'];
			$to_insert ['the_old_id'] = $item ['id'];

			$table_content = 'firecms_content';
			$to_table_id = CI::model('content')->saveContent ( $to_insert );
			//var_dump ( $to_table_id );


			//  `created_on` DATETIME DEFAULT NULL,
			CI::model('core')->cacheDelete ( 'cache_group', 'content' );

			var_dump ( $item ['id'] );
			/*			$now = date ( "Y-m-d H:i:s" );
			$q = "INSERT INTO $table_content set
			tag1 = '{$to_insert ['tag1']}' ,
			content_type = '{$to_insert ['content_type']}' ,
			content_url = '{$to_insert ['content_url']}' ,
			content_body = '{$to_insert ['content_body']}' ,
			content_title = '{$to_insert ['content_title']}',
			created_on = '$now',
			updated_on = '$now'

			; ";
			*/

			/*	$search = '
			<p><a href="http://www.look-estates.com/page.php?n=26579&SiteID=507&view_id=26579" title="Ð?Ð¾Ð²Ð¸Ð½Ð¸ Ð·Ð° Ð½ÐµÐ´Ð²Ð¸Ð¶Ð¸Ð¼Ð¸ Ð¸Ð¼Ð¾Ñ‚Ð¸"><span style="color: #ff0000;"><strong>Ð?ÐžÐ’Ð˜Ð?Ð˜ </strong></span></a><span style="color: #ff0000;"><strong>      </strong></span><a href="http://www.look-estates.com/page.php?n=28760&SiteID=507&view_id=26316" title="Ð?Ð¾Ð²Ð¸Ð½Ð¸ Ñ?Ð²ÑŠÑ€Ð·Ð°Ð½Ð¸ Ñ? Ñ†ÐµÐ½Ð¸Ñ‚Ðµ Ð½Ð° Ð½Ð°Ð´Ð²Ð¸Ð¶Ð¸Ð¼Ð¸Ñ‚Ðµ Ð¸Ð¼Ð¾Ñ‚Ð¸"><span style="color: #0000ff;"><strong>Ð—Ð? Ð¦Ð•Ð?Ð˜</strong></span></a><span style="color: #ff0000;"><strong>       </strong></span><a href="http://www.look-estates.com/page.php?n=26316&SiteID=507&view_id=26316" title="Ð¦ÐµÐ½Ð¸ Ð½Ð° Ð½ÐµÐ´Ð²Ð¸Ð¶Ð¸Ð¼Ð¸Ñ‚Ðµ Ð¸Ð¼Ð¾Ñ‚Ð¸"><span style="color: #008000;"><strong>Ð¡Ð¢Ð?Ð¢Ð˜Ð¡Ð¢Ð˜ÐšÐ? </strong></span></a><span style="color: #ff0000;"><strong>   </strong></span><a href="http://www.look-estates.com/page.php?n=30639&SiteID=507" title="Ð¡Ð°Ð¹Ñ‚Ð¾Ð²Ðµ Ð½Ð° Ð¼ÐµÐ´Ð¸Ð¸"><span style="color: #ff6600;"><strong>ÐœÐ•Ð”Ð˜Ð˜</strong></span></a></p>';
			$search = 'http://www.look-estates.com/page.php?n=26579&SiteID=507&view_id=26579';
			//	$search = addslashes ( $search );
			$search = htmlspecialchars ( $search, ENT_QUOTES, 'UTF-8' );
			$search = mysql_real_escape_string($search);
			$q =  "
			SELECT * from $table_content where content_body REGEXP '$search';


			";
			var_Dump($q);
			$query = CI::db()->query ( $q );

			$get = $query->result_array ();
			var_dump($get);
			*/

			CI::model('core')->cacheDelete ( 'cache_group', 'content' );

			//print $q;
			//$queries [] = $q;
			//$query = CI::db()->query ( " $q  " );
			/*$table_taxonomy = 'firecms_taxonomy';
			$to_insert = array ( );
			$to_insert ['taxonomy_type'] = 'category_item';
			$to_insert ['to_table'] = 'table_content';
			$to_insert ['to_table_id'] = $to_table_id;
			$to_insert ['parent_id'] = '54';
			$to_table_id = CI::model('core')->saveData ( $table_taxonomy, $to_insert );
			var_dump ( $to_insert );*/

			//$str = ($item ['textStr']);


		}

		foreach ( $queries as $item ) {
			//	var_dump($item);
			print "<br /><br />" . $item;

			$query = CI::db()->query ( " $item  " );
		}

		exit ( 'ok' );
	}

	function contentGenerateTagsForPost() {
		ob_end_clean ();

		$what = CI::model('core')->getParamFromURL ( 'generate_what' );
		$what = trim ( $what );
		if ($what == '') {
			$what = $_POST ['generate_what'];
		}

		$data = $_POST ['data'];
		/*$data = trim ( $data );
		$data = reduce_multiples ( $data );
		$data = strip_quotes ( $data );
		$data = CI::model('taxonomy')->taxonomyGenerateTagsFromString ( $data );

		if ($data != '') {
		$data = explode ( ',', $data );
		$data = trimArray ( $data );
		$data = array_unique($data);
		$the_tags = array();
		foreach($data as $item){


		}
		}*/
		$data = CI::model('taxonomy')->taxonomyGenerateAndGuessTagsFromString ( $data );
		print $data;

	}


	function posts_sort_by_date(){


		$ids = $_POST['content_row_id'];
		if(empty($ids)){
			exit;
		}


		$ids_implode = implode(',',$ids);
		global $cms_db_tables;
		$table = $cms_db_tables ['table_content'];

		$q = " SELECT id, updated_on from $table where id IN ($ids_implode)  order by updated_on DESC  ";
		$q = CI::model('core')->dbQuery($q);
		$max_date = $q[0]['updated_on'];
		$max_date_str = strtotime($max_date);
		$i = 1;
		foreach($ids as $id){
			$max_date_str = $max_date_str - $i;
			$nw_date = date('Y-m-d H:i:s',$max_date_str );


			$q = " UPDATE $table set updated_on='$nw_date' where id = '$id'    ";
			$q = CI::model('core')->dbQ($q);
			//var_dump($nw_date);

			$i++;
		}

		CI::model('core')->cacheDelete ( 'cache_group', 'content' );

		//var_dump($q);
		exit;






	}


	function posts_batch_edit() {
		$items = $_POST ['the_batch_edit_items'];
		//$vals
		$items = explode ( ',', $items );
		$vals_to_return = implode ( ',', $items );

		foreacH ( $items as $item ) {
			$data_to_save = array ( );
			$item = trim ( $item );
			$data_to_save = $_POST;
			$data_to_save ['id'] = $item;
			$content = CI::model('content')->contentGetById ( $item );

			$data_to_save ['content_body'] = ($content ['content_body']);

			//var_dump($content);
			$save_me = array ( );
			foreach ( $content as $k => $v ) {
				//	if (is_array ( $v ) == false) {


				$new = ($data_to_save [$k]);
				//var_dump($v);
				if (is_array ( $new ) == false) {
					if (($new != $v) and ($new != false) and (strval ( $new ) != '')) {
						$save_me [$k] = $new;
					} else {
						$save_me [$k] = $v;
					}
				} else {
					$save_me [] = $new;
				}

				//}
			}
			$save_me ["taxonomy_categories"] = $_POST ["taxonomy_categories"];
			//var_dump ( $save_me );
			//var_dump ( $_POST );
			//exit ();
			CI::model('content')->saveContent ( $save_me );
			//var_dump($data_to_save);
			//exit;
			//var_dump($item);
		}

		//var_dump($_POST);


		exit ( $vals_to_return );
		redirect ( 'admin/content/posts_manage' );
		exit ();

	}

	function contentGenerateMeta() {
		ob_end_clean ();
		//var_dump($_POST);
		$what = CI::model('core')->getParamFromURL ( 'generate_what' );
		$what = trim ( $what );
		if ($what == '') {
			$what = $_POST ['generate_what'];
		}
		ob_end_clean ();
		$data = $_POST ['data'];
		$data = trim ( $data );
		$data = reduce_multiples ( $data );
		$data = strip_quotes ( $data );
		switch ( $what) {
			case 'content_meta_title' :

				$data = addslashes ( $data );
				$data = mb_trim ( $data );
				$data = trim ( $data );
				print $data;
				break;

			case 'content_meta_description' :
				$data = strip_tags ( $data );
				$data = addslashes ( $data );
				$data = mb_trim ( $data );
				$data = trim ( $data );
				$data = word_limiter ( $data, 20, '...' );
				print $data;
				break;

			case 'content_meta_keywords' :
				$data = strip_tags ( $data );
				$data = addslashes ( $data );
				$data = mb_trim ( $data );
				$data = trim ( $data );

				$data = CI::model('taxonomy')->taxonomyGenerateTagsFromString ( $data );
				$data = word_limiter ( $data, 30, ' ' );
				print $data;
				break;

			default :
				break;

		}
		exit ();
	}

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */