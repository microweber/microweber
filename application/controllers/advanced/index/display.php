<? 





$content = $page;
 

if ($content_display_mode != 'extended_api_with_no_template') {
	
	if ($url != '') {
		
		if (empty ( $content )) {
			
			header ( "HTTP/1.0 404 Not Found" );
			
			show_404 ( 'page' );
			
			exit ();
		
		}
	
	} else {
		
		$content = $this->content_model->getContentHomepage ();
		
		if (empty ( $content )) {
			
			header ( "HTTP/1.1 500 Internal Server Error" );
			
			show_error ( 'No homepage set. Login in the Admin panel and set homepage!' );
			
			exit ();
		
		}
	
	}
	
	if (trim ( $post ['page_301_redirect_link'] ) != '') {
		
		$gogo = $post ['page_301_redirect_link'];
		
		header ( 'Location: ' . $gogo );
		
		exit ();
	
	}

}

$this->template ['page'] = $page;
//$this->load->vars(array('page' => $page));
if ($page ['require_login'] == 'y') {
	include_once (APPPATH . 'controllers/advanced/requre_login_or_redirect.php');
}

if (! empty ( $post )) {
	$post ['custom_fields'] = $this->core_model->getCustomFields ( 'table_content', $post ['id'] );
	$this->load->model ( 'Taxonomy_model', 'taxonomy_model' );
	$active_categories = $this->taxonomy_model->getCategoriesForContent ( $post ['id'], true );
	// p($active_categories);
	if (! empty ( $active_categories )) {
		$this->template ['active_categories'] = $active_categories;
		//$this->load->vars(array('active_categories' => $active_categories));
		$this->template ['active_category'] = ($active_categories [0]);
	//	$this->load->vars(array('active_category' =>$active_categories [0]));
		// $this->load->vars ( $this->template );
	}
	
	if ($post ['require_login'] == 'y') {
		include_once   (APPPATH . 'controllers/advanced/requre_login_or_redirect.php');
	}

}

$this->template ['post'] = $post;
	$this->load->vars(array('post' =>$post));
// $this->load->vars ( $this->template );

// var_dump($page);

$GLOBALS ['ACTIVE_PAGE_ID'] = $content ['id'];

if (defined ( 'ACTIVE_PAGE_ID' ) == false) {
	
	define ( 'ACTIVE_PAGE_ID', $page ['id'] );

}

$the_active_site_template = $this->core_model->optionsGetByKey ( 'curent_template' );

$the_active_site_template_dir = TEMPLATEFILES . $the_active_site_template . '/';

if (defined ( 'ACTIVE_TEMPLATE_DIR' ) == false) {
	
	define ( 'ACTIVE_TEMPLATE_DIR', $the_active_site_template_dir );

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

$ipto = $_SERVER ["REMOTE_ADDR"];
if (empty ( $post )) {
	$active_categories = $this->content_model->contentActiveCategoriesForPageIdAndCache ( $page ['id'], $url );

} else {
	$active_categories = $this->taxonomy_model->getCategoriesForContent ( $post ['id'], $return_only_ids = true );
}

if (! empty ( $active_categories )) {
	
	// p($active_categories);
	
	$this->template ['active_categories'] = $active_categories;
	$this->template ['active_category'] = end ( $active_categories );
	if (defined ( 'CATEGORY_ID' ) == false) {
		define ( 'CATEGORY_ID', end ( $active_categories ) );
	}
	if (defined ( 'CATEGORY_IDS' ) == false) {
		define ( 'CATEGORY_IDS', implode ( ',', $active_categories ) );
	}
	// $this->load->vars ( $this->template );
}

//
// $active_categories = $this->content_model->contentActiveCategoriesForPageId2
// ( $page ['id'], $url );

// tags

$tags = $this->core_model->getParamFromURL ( 'tags' );

// var_dump($tags);
if ($tags != '') {
	
	$tags = explode ( ',', $tags );
	
	if (empty ( $tags )) {
		
		$selected_tags = false;
	
	} else {
		
		$selected_tags = $tags;
	
	}

} else {
	
	$selected_tags = false;

}

$created_by = $this->core_model->getParamFromURL ( 'author' );

// var_dump($tags);
if ($created_by != '') {
	
	$this->template ['created_by'] = $created_by;

} else {
	
	// $this->template ['created_by'] = false;
}

$this->template ['selected_tags'] = $selected_tags;

$this->template ['active_tags'] = $selected_tags;

// $this->load->vars ( $this->template );

if ($_POST ['keyword'] != '') {
	$_POST ['search_by_keyword'] = $_POST ['keyword'];
}

if ($page ['content_subtype'] == 'dynamic' or $page ['content_subtype'] == 'dynamic') {
	
	$active_categories2 = $this->content_model->contentActiveCategoriesForPageIdAndCache ( $page ['id'], $url, true );
	// var_dump($active_categories2);
	$posts_data = false;
	
	$posts_data ['selected_categories'] = ($active_categories2);
	
	if ($_POST ['search_by_keyword'] != '' or ($_POST ['begin_search']) or ($_POST ['search'])) {
		
		if (empty ( $active_categories2 )) {
			
			$togo = $this->content_model->getContentURLByIdAndCache ( $page ['id'] );
		
		} else {
			$this->load->model ( 'Taxonomy_model', 'taxonomy_model' );
			$togo = $this->taxonomy_model->getUrlForId ( $active_categories2 [0] );
		
		}
		 
		if ($_POST ['selected_categories'] != '') {
			
			$search_cats = str_replace ( ' ', '', $_POST ['selected_categories'] );
			
			trim ( $search_cats );
			
			$search_cats = explode ( ',', $search_cats );
			
			$search_cats = array_trim ( $search_cats );
			
			$search_cats = array_unique ( $search_cats );
			
			$search_cats = implode ( ',', $search_cats );
			
			// p($search_cats);
			
			$search_cats = '/categories:' . $search_cats;
		
		}
		
		if ($_POST ['search_by_keyword'] != '') {
			
			// $togo = $togo . '/keyword:' . stripslashes ( $_POST
			// ['search_by_keyword'] );
			// 
			 
		
		}
		 
		foreach ( $_POST as $k => $v ) {
			
			switch (strtolower ( $k )) {
				case 'x' :
				case 'y' :
				case 'search_by_keyword' :
					
					break;
				
				case 'search' :
					$togo = $togo . '/' . trim ( $k );
					break;
				 
				default :
					
				 
					if ($v != '') {
						$togo = $togo . '/' . trim ( $k );
						$togo = $togo . ':' . stripslashes ( $v );
					} else {
					
					}
					break;
			}
		
		}
	//	var_dump($_POST );
	//	exit;
		if ($_POST ['custom_fields'] != false) {
			
			$custom_fields_criteria = array ();
			
			// var_dump($_POST ['custom_fields']);
			
			foreach ( $_POST ['custom_fields'] as $k => $cf ) {
				
				if ($cf != false) {
					
					if (is_array ( $cf ) and ! empty ( $cf )) {
						
						$custom_fields_criteria [$k] = $cf;
					
					}
					
					if (is_string ( $cf )) {
						
						$cf = mb_trim ( $cf );
						
						if ($cf != '') {
							
							$custom_fields_criteria [$k] = $cf;
						
						}
					
					}
				
				}
			
			}
			
			if (! empty ( $custom_fields_criteria )) {
				
				// var_dump ( $custom_fields_criteria );
				
				$custom_fields_criteria = base64_encode ( serialize ( $custom_fields_criteria ) );
				
				$togo = $togo . '/custom_fields_criteria:' . $custom_fields_criteria;
			
			}
		
		}
		
		if ($_POST ['ord'] != '') {
			
			$ords = explode ( ',', $_POST ['ord'] );
			
			if ($ords [0] != '') {
				
				$togo_ord1 = '/ord:' . stripslashes ( trim ( $ords [0] ) );
			
			}
			
			if ($ords [1] != '') {
				
				$_POST ['ord-dir'] = trim ( $ords [1] );
			
			}
		
		}
		
		if ($_POST ['ord-dir'] != '') {
			
			$togo_ord2 = '/ord-dir:' . stripslashes ( $_POST ['ord-dir'] );
		
		}
		
		$temp1 = $this->core_model->getParamFromURL ( 'view' );
		// p($togo,1);
		// exit();
		if (trim ( $temp1 ) != '') {
			
			// header ( 'Location: ' . $togo . '/view:' . $temp1 . $search_cats
			// . $togo_ord1 . $togo_ord2 );
			safe_redirect ( $togo . '/view:' . $temp1 . $search_cats . $togo_ord1 . $togo_ord2 );
		
		} else {
			
			// header ( 'Location: ' . );
			safe_redirect ( $togo . $search_cats . $togo_ord1 . $togo_ord2 );
		
		}
		
		exit ();
	
	}
	
	$strict_category_selection = $this->core_model->getParamFromURL ( 'strict_category_selection' );
	
	if ($strict_category_selection == 'y') {
		
		$posts_data ['strict_category_selection'] = true;
	
	} else {
		
		$posts_data ['strict_category_selection'] = false;
	
	}
	
	$search_for = $this->core_model->getParamFromURL ( 'keyword' );
	
	if ($search_for != '') {
		
		$search_for = html_entity_decode ( $search_for );
		
		$search_for = urldecode ( $search_for );
		
		$search_for = htmlspecialchars_decode ( $search_for );
		
		$posts_data ['search_by_keyword'] = $search_for;
		
		$this->template ['search_for_keyword'] = $search_for;
		//	$this->load->vars(array('search_for_keyword' =>$search_for));
		// $this->load->vars ( $this->template );
	
	}
	
	$strict_category_selection = $this->core_model->getParamFromURL ( 'strict-category' );
	
	if ($strict_category_selection == 'y') {
		
		$posts_data ['strict_category_selection'] = true;
	
	} else {
	
	}
	
	$type = $this->core_model->getParamFromURL ( 'type' );
	
	if (trim ( $type ) != '' && trim ( $type ) != 'blog') {
		
		$posts_data ['content_subtype'] = $type;
	
	} else {
		
		$posts_data ['content_subtype'] = 'none';
	
	}
	
	$typev = $this->core_model->getParamFromURL ( 'typev' );
	
	if (trim ( $typev ) != '') {
		
		$posts_data ['content_subtype_value'] = $typev;
	
	} else {
		
		$posts_data ['content_subtype_value'] = 'none';
	
	}
	
	// $posts_data ['selected_tags'] = ($selected_tags);
	
	$posts_data ['content_type'] = 'post';
	
	$posts_data ['visible_on_frontend'] = 'y';
	
	$items_per_page = $this->core_model->optionsGetByKey ( 'default_items_per_page' );
	
	$items_per_page = intval ( $items_per_page );
	
	if (empty ( $active_categories2 )) {
		
		$active_categories2 = array ();
	
	} else {
	$this->load->model ( 'Taxonomy_model', 'taxonomy_model' );
	 
	
	}
	
	
	$curent_page = $this->core_model->getParamFromURL ( 'curent_page' );
	
	if (intval ( $curent_page ) < 1) {
		
		$curent_page = 1;
	
	}
	
	$voted = $this->core_model->getParamFromURL ( 'voted' );
	
	if (($timestamp = strtotime ( $voted )) === false) {
		
		$this->template ['selected_voted'] = false;
	
	} else {
		
		$posts_data ['voted'] = $voted;
		
		$this->template ['selected_voted'] = true;
	
	}
	
	// $this->load->vars ( $this->template );
	
	$orderby1 = array ();
	
	$order_url = $this->core_model->getParamFromURL ( 'ord' );
	
	$order_direction_url = $this->core_model->getParamFromURL ( 'ord-dir' );
	
	if ($order_url != false) {
		
		$orderby1 [0] = $order_url;
	
	} else {
		
		$orderby1 [0] = 'updated_on';
	
	}
	
	if ($order_direction_url != false) {
		
		$orderby1 [1] = $order_direction_url;
	
	} else {
		
		$orderby1 [1] = 'DESC';
	
	}
	
	$page_start = ($curent_page - 1) * $items_per_page;
	
	$page_end = ($page_start) + $items_per_page;
	
	if (! empty ( $posts_data )) {
		
	 
		
		$posts_data2 = $posts_data;
		
		$posts_data2 ['use_fetch_db_data'] = true;
		
		$posts_data2 ['orderby'] = $orderby1;
		
		// $posts_data2 ['visible_on_frontend'] = 'y';
		
		// $data = $this->content_model->getContentAndCache ( $posts_data,
		// $orderby1, array ($page_start, $page_end ), $short_data = false,
		// $only_fields = array ('id', 'content_title', 'content_body',
		// 'content_url', 'content_filename', 'content_parent',
		// 'content_filename_sync_with_editor', 'content_body_filename' ) );
		//$this -> benchmark -> mark('contentGetByParams_start');
	$data = $this->content_model->contentGetByParams ( $posts_data2 );
		
		  $this->template ['posts'] = $data ['posts'];
		//$this->load->vars(array('posts' => $data ['posts']));
		$posts = $data;
		
		$content_pages_count = $data ["posts_pages_count"];
		$this->template ['posts_pages_count'] = $data ["posts_pages_count"];
		//$this->load->vars(array('posts_pages_count' => $data ['posts_pages_count']));
		$this->template ['posts_pages_curent_page'] = $data ["posts_pages_curent_page"];
		//$this->load->vars(array('posts_pages_curent_page' => $data ['posts_pages_curent_page']));
		// get paging urls
		 $content_pages = $this->content_model->pagingPrepareUrls ( false, $content_pages_count );
		
		// var_dump($content_pages);
		$this->template ['posts_pages_links'] = $content_pages;
	//	$this -> benchmark -> mark('contentGetByParams_end');
	//$this->load->vars($this->template); 
	}

}






