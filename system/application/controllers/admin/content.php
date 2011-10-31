<?php

class Content extends Controller {
	
	function __construct() {
		parent::Controller ();
		require_once (APPPATH . 'controllers/default_constructor.php');
		require_once (APPPATH . 'controllers/admin/default_constructor.php');
	
	}
	
	function index() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		
		if (CI::library('session')->userdata ( 'user' ) == false) {
			//redirect ( 'index' );
		}
		
		$this->load->vars ( $this->template );
		
		$layout = CI::view ( 'admin/layout', true, true );
		$primarycontent = '';
		$secondarycontent = '';
		
		$primarycontent = CI::view ( 'admin/content/index', true, true );
		$secondarycontent = CI::view ( 'admin/content/sidebar', true, true );
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );
		//CI::view('welcome_message');
		$layout = CI::model('template')->replaceTemplateTags ( $layout );
		CI::library('output')->set_output ( $layout );
	}
	
	function pages_index() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		
		$data = array ();
		$data ['content_type'] = 'page';
		$data = CI::model('content')->getContent ( $data );
		//var_dump($data);
		$dbdata ['pages'] = $data;
		
		$this->template ['dbdata'] = $dbdata;
		
		$this->load->vars ( $this->template );
		$layout = CI::view ( 'admin/layout', true, true );
		$nav = CI::view ( 'admin/content/pages_nav', true, true );
		$primarycontent = CI::view ( 'admin/content/pages_index', true, true );
		$primarycontent = $nav . $primarycontent;
		$secondarycontent = CI::view ( 'admin/content/sidebar', true, true );
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );
		//CI::view('welcome_message');
		$layout = CI::model('template')->replaceTemplateTags ( $layout );
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
		
		if ($_POST ['type']) {
			$togo_is_is_featured = "/type:{$_POST['type']}";
		} else {
			$togo_is_is_featured = false;
		}
		
		if ($_POST ['with_comments']) {
			$togo_with_comments = "/with_comments:{$_POST['with_comments']}";
		} else {
			$togo_with_comments = false;
		}
		
		if ($_POST ['content_subtype']) {
			$togo_with_content_subtype = "/type:{$_POST['content_subtype']}";
		} else {
			$togo_with_content_subtype = false;
		}
		
		if ($_POST ['visible_on_frontend']) {
			$togo_visible_on_frontend = "/visible_on_frontend:{$_POST['visible_on_frontend']}";
		} else {
			$togo_visible_on_frontend = false;
		}
		//var_dump($_POST);
		$gogo = site_url ( 'admin/content/posts_manage' ) . $togo_categories . $togo_keyword . $togo_tags . $togo_is_from_rss . $togo_is_is_featured . $togo_visible_on_frontend . $togo_with_comments . $togo_voted . $togo_items_per_page . $togo_with_content_subtype;
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
		$data = array ();
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
		$data ['active_categories'] = $categories;
		$this->template ['content_selected_categories'] = $categories;
		$this->template ['selected_categories'] = $categories;
		$this->template ['active_categories'] = $categories;
		
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
				//$avalable_tags = CI::model('taxonomy')->taxonomyGetAvailableTags ( 'table_content' );
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
		
		$visible_on_frontend = CI::model('core')->getParamFromURL ( 'visible_on_frontend' );
		if (trim ( $visible_on_frontend ) != '') {
			$data ['visible_on_frontend'] = $visible_on_frontend;
			$this->template ['search_by_visible_on_frontend'] = $visible_on_frontend;
		}
		$is_featured = CI::model('core')->getParamFromURL ( 'is_featured' );
		$data ['is_featured'] = $is_featured;
		$this->template ['search_by_is_is_featured'] = $is_featured;
		
		$type = CI::model('core')->getParamFromURL ( 'type' );
		if ($type) {
			$data ['content_subtype'] = $type;
			$this->template ['content_subtype'] = $type;
		}
		$with_comments = CI::model('core')->getParamFromURL ( 'with_comments' );
		if ($with_comments) {
			$data ['commented'] = $with_comments;
			$this->template ['search_by_with_comments'] = $with_comments;
		}
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
		
		//getContent($data, $orderby = false, $limit = false, $count_only = false, $short_data = false, $only_fields = false)
		

		$page_start = ($curent_page - 1) * $items_per_page;
		$page_end = ($page_start) + $items_per_page;
		//	var_dump($original_criteria);
		//$results_count = CI::model('content')->getContent ( $original_criteria, false, false, true, $short_data = true );
		

		$results_count = CI::model('content')->getContent ( $original_criteria, false, false, true, $short_data = true );
		
		$content_pages_count = ceil ( $results_count / $items_per_page );
		$this->template ['content_pages_count'] = $content_pages_count;
		//var_dump($content_pages_count);
		$this->template ['content_pages_curent_page'] = $curent_page;
		
		//get paging urls
		$content_pages = CI::model('content')->pagingPrepareUrls ( false, $content_pages_count );
		$this->template ['content_pages_links'] = $content_pages;
		
		$data = CI::model('content')->getContent ( $original_criteria, false, array ($page_start, $page_end ), false, $short_data = true );
		
		//var_dump( $data);
		//exit;
		

		$this->load->vars ( $this->template );
		$this->template ['form_values'] = $data;
		$this->load->vars ( $this->template );
		
		$latest_posts = array ();
		$latest_posts ['content_type'] = 'post';
		$latest_posts = CI::model('content')->getContent ( $latest_posts, false, array (0, 5 ), false );
		$this->template ['latest_posts'] = $latest_posts;
		
		//$avalable_tags = CI::model('taxonomy')->taxonomyGetAvailableTags ( 'table_content' );
		$this->template ['avalable_tags'] = $avalable_tags;
		
		$this->load->vars ( $this->template );
		
		$layout = CI::view ( 'admin/layout', true, true );
		
		//$layout = CI::view ( 'admin/layout', true, true );
		$primarycontent = CI::view ( 'admin/content/posts_manage', true, true );
		$nav = CI::view ( 'admin/content/posts_nav', true, true );
		$primarycontent = $nav . $primarycontent;
		$secondarycontent = CI::view ( 'admin/content/sidebar', true, true );
		//$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		

		$try_view = TEMPLATE_DIR . 'admin/content/posts_manage.php';
		
		if (is_file ( $try_view )) {
			//$primarycontent = CI::view ( $try_view, true, true );
			$primarycontent = $this->load->file ( $try_view, true );
		} else {
			$primarycontent = CI::view ( 'admin/content/posts_manage', true, true );
		
		}
		
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );
		//CI::view('welcome_message');
		//$nav = CI::view ( 'admin/content/posts_nav', true, true );
		$layout = CI::model('template')->replaceTemplateTags ( $layout );
		CI::library('output')->set_output ( $layout );
	}
	
	function posts_edit_done() {
		CI::helper ( array ('form', 'url' ) );
		$this->load->library ( 'form_validation' );
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		$id = CI::model('core')->getParamFromURL ( 'id' );
		
		if ($id != 0) {
			$data = array ();
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
		$layout = CI::view ( 'admin/content/posts_edit_done', true, true );
		$layout = CI::model('template')->replaceTemplateTags ( $layout );
		CI::library('output')->set_output ( $layout );
	}
	
	function posts_delete() {
		$id = CI::model('core')->getParamFromURL ( 'id' );
		//CI::model('content')->deleteContent ( $id );
		CI::model('content')->contentDelete ( $id );
		redirect ( 'admin/content/posts_manage' );
	}
	
	function posts_edit() {
		CI::helper ( array ('form', 'url' ) );
		$this->load->library ( 'form_validation' );
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		
		//$avalable_tags = CI::model('taxonomy')->taxonomyGetAvailableTags ( 'table_content' );
		$this->template ['avalable_tags'] = $avalable_tags;
		$this->template ['load_google_map'] = true;
		
		$id = CI::model('core')->getParamFromURL2 ( 'id' );
		$category = CI::model('core')->getParamFromURL2 ( 'category' );
		
		$this->template ['content_selected_categories'] = array ($category );
		$this->template ['selected_categories'] = array ($category );
		$this->template ['active_categories'] = array ($category );
		//var_dump($category);
		

		if (intval ( $id ) == 0) {
			$data ['active_categories'] = array ($category );
			
			$category_item = CI::model('taxonomy')->getSingleItem ( $category );
			if (! empty ( $category_item )) {
				$content_type = 'default';
				$content_type_check = $category_item ['taxonomy_content_type'];
				if (strval ( $content_type_check ) == 'inherit' or strval ( $content_type_check ) == '') {
					$lets_get_parent_cats = CI::model('taxonomy')->getParentsIds ( $category );
					if (! empty ( $lets_get_parent_cats )) {
						foreach ( $lets_get_parent_cats as $parent_cat_id ) {
							$parent_cat_item = CI::model('taxonomy')->getSingleItem ( $parent_cat_id );
							if (! empty ( $parent_cat_item )) {
								$content_type_check = $parent_cat_item ['taxonomy_content_type'];
								
								if ($content_type == 'default') {
									if (strval ( $content_type_check ) == 'inherit' or strval ( $content_type_check ) == '') {
									
									} else {
										$content_type = $content_type_check;
									}
								}
							
							}
						}
					}
				
				} else {
					$content_type = $category_item ['taxonomy_content_type'];
				}
			}
		}
		if ($id != 0) {
			$this->template ['the_action'] = 'posts_edit';
			$this->load->vars ( $this->template );
			$data = array ();
			$data ['id'] = $id;
			$data ['content_type'] = 'post';
			$data ['include_taxonomy'] = 'y';
			//$data = CI::model('content')->getContent ( $data, false, array (0, 1 ) );
			//$data = $data [0];
			$data = CI::model('content')->contentGetById ( $id );
			
			$more = false;
			$more = CI::model('core')->getCustomFields ( 'table_content', $data ['id'] );
			$data ['custom_fields'] = $more;
			
			$active_categories = CI::model('taxonomy')->getCategoriesForContent ( $data ['id'], true );
			//	var_dump($active_categories);
			$data ["active_categories"] = $active_categories;
			
			$this->template ['content_selected_categories'] = $active_categories;
			$this->template ['selected_categories'] = $active_categories;
			$this->template ['active_categories'] = $active_categories;
			
			$content_type = 'default';
			if (! empty ( $active_categories )) {
				$data ['active_categories'] = $active_categories;
				foreach ( $active_categories as $cat_id ) {
					$category_item = CI::model('taxonomy')->getSingleItem ( $cat_id );
					//var_dump($cat_item);
					if (! empty ( $category_item )) {
						$content_type = 'default';
						$content_type_check = $category_item ['taxonomy_content_type'];
						if (strval ( $content_type_check ) == 'inherit' or strval ( $content_type_check ) == '') {
							$lets_get_parent_cats = CI::model('taxonomy')->getParentsIds ( $cat_id );
							if (! empty ( $lets_get_parent_cats )) {
								foreach ( $lets_get_parent_cats as $parent_cat_id ) {
									$parent_cat_item = CI::model('taxonomy')->getSingleItem ( $parent_cat_id );
									if (! empty ( $parent_cat_item )) {
										$content_type_check = $parent_cat_item ['taxonomy_content_type'];
										//var_dump($content_type);
										if ($content_type == 'default') {
											if (strval ( $content_type_check ) == 'inherit' or strval ( $content_type_check ) == '') {
											
											} else {
												$content_type = $content_type_check;
											}
										}
									
									}
								}
							}
						
						} else {
							$content_type = $category_item ['taxonomy_content_type'];
						}
					}
				}
			}
		
		} else {
			$this->template ['the_action'] = 'posts_add';
		}
		//var_dump($content_type);
		//exit;
		CI::helper ( array ('form', 'url' ) );
		
		$this->load->library ( 'form_validation' );
		$this->template ['form_values'] = $data;
		$this->template ['content_type'] = $content_type;
		$this->load->vars ( $this->template );
		
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
			$to_save = array ();
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
			$layout = CI::view ( 'admin/layout_small', true, true );
		} else {
			$layout = CI::view ( 'admin/layout', true, true );
		}
		
		//$layout = CI::view ( 'admin/layout', true, true );
		$nav = CI::view ( 'admin/content/posts_nav', true, true );
		$try_view = TEMPLATE_DIR . 'admin/content/content_types/' . $content_type . '.php';
		$try_view_default = TEMPLATE_DIR . 'admin/content/content_types/' . 'default' . '.php';
		if (is_file ( $try_view )) {
			//$primarycontent = CI::view ( $try_view, true, true );
			$primarycontent = $this->load->file ( $try_view, true );
		} else {
			
			if (is_file ( $try_view_default )) {
				//$primarycontent = CI::view ( $try_view, true, true );
				$primarycontent = $this->load->file ( $try_view_default, true );
			} else {
				$primarycontent = CI::view ( 'admin/content/content_types/' . $content_type, true, true );
			}
		
		}
		
		if (strval ( $primarycontent ) == '') {
			$primarycontent = CI::view ( 'admin/content/content_types/default', true, true );
			if (strval ( $primarycontent ) == '') {
				$primarycontent = CI::view ( 'admin/content/posts_edit', true, true );
			}
		}
		//} else {
		//	$primarycontent = CI::view ( 'admin/content/posts_edit', true, true );
		//}
		

		$primarycontent = $nav . $primarycontent;
		//$secondarycontent = CI::view ( 'admin/content/sidebar', true, true );
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		//$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );
		//CI::view('welcome_message');
		

		$layout = trim ( $layout );
		$layout = CI::model('template')->replaceTemplateTags ( $layout );
		CI::library('output')->set_output ( $layout );
	
	}
	
	function pages_edit() {
		CI::helper ( array ('form', 'url' ) );
		$this->load->library ( 'form_validation' );
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		$id = CI::model('core')->getParamFromURL ( 'id' );
		if (! $_POST) {
			if ($id != 0) {
				$data = array ();
				$data ['id'] = $id;
				$data ['content_type'] = 'page';
				$data ['include_taxonomy'] = 'y';
				$data = CI::model('content')->getContent ( $data );
				//var_dump($data);
				$this->template ['form_values'] = $data [0];
				$this->load->vars ( $this->template );
			}
		}
		
		$this->form_validation->set_rules ( 'content_url', 'content url', 'trim|required' );
		$this->form_validation->set_rules ( 'content_title', 'content title', 'trim|required' );
		//$this->form_validation->set_message ( 'required', 'Your custom message here' );
		$this->form_validation->set_error_delimiters ( '<div class="error">', '</div>' );
		
		if ($this->form_validation->run () == FALSE) {
			//get all pages
			$data = array ();
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
			$to_save = array ();
			$to_save = $_POST;
			$to_save ['content_type'] = 'page';
			$to_save ['content_parent'] = intval ( $to_save ['content_parent'] );
			
			$to_save = CI::model('content')->saveContent ( $to_save );
			CI::model('core')->cacheDeleteAll ();
			
			redirect ( 'admin/content/pages_index' );
			
		//CI::view ( 'formsuccess' );
		}
		
		$this->load->vars ( $this->template );
		$layout = CI::view ( 'admin/layout', true, true );
		
		$nav = CI::view ( 'admin/content/pages_nav', true, true );
		$primarycontent = CI::view ( 'admin/content/pages/default.php', true, true );
		$primarycontent = $nav . $primarycontent;
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );
		$layout = CI::model('template')->replaceTemplateTags ( $layout );
		CI::library('output')->set_output ( $layout );
	}
	
	function pages_edit_ajax_content_subtype() {
		//var_dump ( $_POST );
		

		// $this->firecms = get_instance();
		//if($_POST['content_subtype'] == 'dynamic'){
		$this->template ['form_values'] = $_POST;
		$this->load->vars ( $this->template );
		$layout = CI::view ( 'admin/content/pages_edit_ajax_content_subtype', true, true );
		CI::library('output')->set_output ( $layout );
		//}
	}
	
	function menus_show_menu_ajax() {
		$edit = CI::model('core')->getParamFromURL ( 'id' );
		//var_dump ( $edit );
		if (intval ( $edit ) != 0) {
			$data = array ();
			$data ['item_type'] = 'menu';
			$data ['id'] = $edit;
			$menu = CI::model('content')->getMenus ( $data );
			$this->template ['item'] = $menu [0];
		}
		
		$this->load->vars ( $this->template );
		$layout = CI::view ( 'admin/content/menus_show_menu_ajax.php', true, true );
		exit ( $layout );
	}
	
	function menus_edit_small_menu_item() {
		$edit = CI::model('core')->getParamFromURL ( 'id' );
		$form = CI::model('core')->getParamFromURL ( 'form' );
		$this->template ['form_id'] = $form;
		//var_dump ( $edit );
		if (intval ( $edit ) != 0) {
			$data = array ();
			$data ['item_type'] = 'menu_item';
			$data ['id'] = $edit;
			$menu = CI::model('content')->getMenus ( $data );
			$this->template ['form_values'] = $menu [0];
		}
		//	var_dump ( $menu );
		$this->load->vars ( $this->template );
		$layout = CI::view ( 'admin/content/menus_edit_small_menu_item.php', true, true );
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
			$data = array ();
			$data ['item_type'] = 'menu';
			$data ['id'] = $edit;
			$menu = CI::model('content')->getMenus ( $data );
			$this->template ['form_values'] = $menu [0];
		}
		
		$this->load->vars ( $this->template );
		$layout = CI::view ( 'admin/content/menus_edit_small.php', true, true );
		exit ( $layout );
	}
	
	function menus_delete_menu_item() {
		$delete_menu_item = CI::model('core')->getParamFromURL ( 'delete_menu_item' );
		$delete_menu_item = $_POST ['delete_menu_item'];
		
		if (intval ( $delete_menu_item ) != 0) {
			$table = TABLE_PREFIX . 'menus';
			$data = array ();
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
	
	function menus_add() {
		redirect ( 'admin/menus' );
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		
		$this->load->vars ( $this->template );
		$layout = CI::view ( 'admin/layout', true, true );
		$primarycontent = CI::view ( 'admin/content/menus/menus_add', true, true );
		$nav = CI::view ( 'admin/content/menus/menus_nav', true, true );
		$primarycontent = $nav . $primarycontent;
		
		$secondarycontent = false;
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );
		CI::library('output')->set_output ( $layout );
	}
	
	function menus() {
		redirect ( 'admin/menus' );
		CI::helper ( array ('form', 'url' ) );
		
		$this->load->library ( 'form_validation' );
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		$this->form_validation->set_rules ( 'menu_unique_id', 'Menu unique ID', 'trim|required' );
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
		
		$data = array ();
		$data ['item_type'] = 'menu';
		$menus = CI::model('content')->getMenus ( $data );
		$this->template ['menus'] = $menus;
		
		//edit menu
		$edit = CI::model('core')->getParamFromURL ( 'edit' );
		//var_dump ( $edit );
		if (intval ( $edit ) != 0) {
			$data = array ();
			$data ['item_type'] = 'menu';
			$data ['id'] = $edit;
			$menu = CI::model('content')->getMenus ( $data );
			$this->template ['form_values'] = $menu [0];
		}
		
		//delete menu
		$delete = CI::model('core')->getParamFromURL ( 'delete' );
		if (intval ( $delete ) != 0) {
			$table = TABLE_PREFIX . 'menus';
			$data = array ();
			$data ['id'] = $delete;
			$del = CI::model('core')->deleteData ( $table, $data, 'menus' );
			CI::model('content')->fixMenusPositions ();
			redirect ( 'admin/content/menus' );
		}
		
		if ($this->form_validation->run () == FALSE) {
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
		$layout = CI::view ( 'admin/layout', true, true );
		$primarycontent = CI::view ( 'admin/content/menus/menus_list', true, true );
		$nav = CI::view ( 'admin/content/menus/menus_nav', true, true );
		$primarycontent = $nav . $primarycontent;
		
		$secondarycontent = false;
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );
		$layout = CI::model('template')->replaceTemplateTags ( $layout );
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
	
	function taxonomy_categories_move_ajax() {
		
		$id = CI::model('core')->getParamFromURL ( 'id' );
		$dir = CI::model('core')->getParamFromURL ( 'direction' );
		
		CI::model('taxonomy')->taxonomyChangePosition ( $id, $dir );
		CI::model('core')->cacheDeleteAll ();
		exit ();
		//redirect ( 'admin/content/taxonomy_categories' );
	}
	
	function taxonomy_categories_delete_by_ajax() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		$id = $_POST ['id'];
		$id = intval ( $id );
		CI::model('taxonomy')->taxonomyDelete ( $id );
		//CI::model('core')->cacheDelete ( 'cache_group', 'taxonomy' );
		//CI::model('core')->cleanCacheGroup ( 'taxonomy' . DIRECTORY_SEPARATOR );
		//var_dump($_POST);
		exit ( 'ok' );
	}
	
	function taxonomy_categories_edit_by_ajax() {
		
		//$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		$id = $_POST ['id'];
		$id = intval ( $id );
		//CI::helper ( array ('form', 'url' ) );
		

		/*	$this->load->library ( 'form_validation' );
		CI::helper ( 'form' );
		CI::helper ( 'url' );*/
		$parent_id = $_POST ['parent_id'];
		
		if ($parent_id == 'undefined') {
			
			$parent_id = false;
			//@unset ( $_POST ['parent_id'] );
			$_POST ['parent_id'] = 0;
		}
		
		if ($_POST ['saving'] == 'yes') {
			
			$to_save = $_POST;
			$to_save ['taxonomy_type'] = 'category';
			//var_dump($to_save);
			$save = CI::model('taxonomy')->taxonomySave ( $to_save );
			CI::model('core')->cleanCacheGroup ( 'taxonomy' . DIRECTORY_SEPARATOR );
			
			//var_dump($save);
			//	p($_POST,1);
			if (intval ( $_POST ['id'] ) == 0) {
				exit ( 'new' );
			} else {
				exit ( 'saved' );
			}
		
		} else {
			if (intval ( $id ) != 0) {
				$data = array ();
				$data ['id'] = $id;
				//$data ['include_taxonomy'] = 'y';
				$data = CI::model('taxonomy')->taxonomyGet ( $data );
				//	var_dump($data); 
				$this->template ['form_values'] = $data;
				$this->load->vars ( $this->template );
			} else {
				$this->template ['form_values'] = $_POST;
				$this->load->vars ( $this->template );
			}
		}
		
		$this->load->vars ( $this->template );
		
		$primarycontent = CI::view ( 'admin/content/taxonomy_categories_edit_by_ajax', true, true );
		$primarycontent = CI::model('template')->replaceTemplateTags ( $primarycontent );
		CI::library('output')->set_output ( $primarycontent );
	}
	
	function categories_migrate_old_category_items() {
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_taxonomy'];
		$table_items = $cms_db_tables ['table_taxonomy_items'];
		
		$q = "select * from $table where taxonomy_type='category_item' ";
		$q = CI::model('core')->dbQuery ( $q );
		if (! empty ( $q )) {
			foreacH ( $q as $item ) {
				
				$item_to_save = $item;
				$item_to_save ['id'] = false;
				$save = CI::model('taxonomy')->taxonomySave ( $item_to_save );
				
				$q1 = "delete from $table where id='{$item['id']}' ";
				$q1 = CI::model('core')->dbQ ( $q1 );
			
			}
		
		}
		p ( $q );
		
		//	var_dump($_REQUEST);
		exit ();
	}
	
	function taxonomy_categories() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		$this->template ['load_google_map'] = true;
		//print $delete_id;
		CI::helper ( array ('form', 'url' ) );
		
		$this->load->library ( 'form_validation' );
		
		$this->form_validation->set_rules ( 'taxonomy_value', 'Category name', 'trim|required' );
		//$this->form_validation->set_rules ( 'content_title', 'content title', 'trim|required' );
		//$this->form_validation->set_message ( 'required', 'Your custom message here' );
		$this->form_validation->set_error_delimiters ( '<div class="error">', '</div>' );
		
		$data = array ();
		$data ['taxonomy_type'] = 'category';
		$data ['to_table'] = 'table_content';
		
		$taxonomy = CI::model('taxonomy')->taxonomyGet ( $data );
		$this->template ['taxonomy_items'] = $taxonomy;
		//taxonomyGet
		

		$id = CI::model('core')->getParamFromURL ( 'category_edit' );
		if ($id != 0) {
			$data = array ();
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
			//var_dump($_POST);
			$to_save = $_POST;
			$to_save ['taxonomy_type'] = 'category';
			$to_save ['to_table'] = 'table_content';
			$save = CI::model('taxonomy')->taxonomySave ( $to_save );
			//.var_dump($save);
			CI::model('core')->cacheDeleteAll ();
			//sleep ( 1 );
			redirect ( 'admin/content/taxonomy_categories' );
		}
		
		$this->load->vars ( $this->template );
		$layout = CI::view ( 'admin/layout', true, true );
		//$layout = CI::view ( 'admin/layout', true, true );
		$primarycontent = CI::view ( 'admin/content/taxonomy_categories', true, true );
		$nav = CI::view ( 'admin/content/taxonomy_nav', true, true );
		$primarycontent = $nav . $primarycontent;
		$secondarycontent = CI::view ( 'admin/content/sidebar', true, true );
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );
		//CI::view('welcome_message');
		$layout = CI::model('template')->replaceTemplateTags ( $layout );
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
		$layout = CI::view ( 'admin/layout', true, true );
		//$layout = CI::view ( 'admin/layout', true, true );
		$primarycontent = CI::view ( 'admin/content/taxonomy_tags', true, true );
		$nav = CI::view ( 'admin/content/taxonomy_nav', true, true );
		$primarycontent = $nav . $primarycontent;
		$secondarycontent = CI::view ( 'admin/content/sidebar', true, true );
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );
		//CI::view('welcome_message');
		CI::library('output')->set_output ( $layout );
	
	}
	
	function taxonomy_delete() {
		$id = CI::model('core')->getParamFromURL ( 'id' ,false,true );
		CI::model('taxonomy')->taxonomyDelete ( $id );
	}
	
	function content_delete() {
		$id = CI::model('core')->getParamFromURL ( 'id', false,true );
 
	 
	//	CI::model('content')->contentDelete ( $id );
		CI::model('content')->deleteContent ( $id );
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
	
	function posts_sort_by_date() {
		
		$ids = $_POST ['content_row_id'];
		if (empty ( $ids )) {
			exit ();
		}
		
		$ids_implode = implode ( ',', $ids );
		global $cms_db_tables;
		$table = $cms_db_tables ['table_content'];
		
		$q = " SELECT id, updated_on from $table where id IN ($ids_implode)  order by updated_on DESC  ";
		$q = CI::model('core')->dbQuery ( $q );
		$max_date = $q [0] ['updated_on'];
		$max_date_str = strtotime ( $max_date );
		$i = 1;
		foreach ( $ids as $id ) {
			$max_date_str = $max_date_str - $i;
			$nw_date = date ( 'Y-m-d H:i:s', $max_date_str );
			
			$q = " UPDATE $table set updated_on='$nw_date' where id = '$id'    ";
			$q = CI::model('core')->dbQ ( $q );
			//var_dump($nw_date);
			

			$i ++;
		}
		
		CI::model('core')->cacheDelete ( 'cache_group', 'content' );
		
		//var_dump($q);
		exit ();
	
	}
	
	function posts_batch_edit() {
		$items = $_POST ['the_batch_edit_items'];
		//$vals
		$items = explode ( ',', $items );
		$vals_to_return = implode ( ',', $items );
		
		foreacH ( $items as $item ) {
			$data_to_save = array ();
			$item = trim ( $item );
			$data_to_save = $_POST;
			$data_to_save ['id'] = $item;
			$content = CI::model('content')->contentGetById ( $item );
			
			$data_to_save ['content_body'] = ($content ['content_body']);
			
			//var_dump($content);
			$save_me = array ();
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
		switch ($what) {
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