<?php

$content = $page;
if ($content_display_mode != 'extended_api_with_no_template') {
	if ($url != '') {

		if (empty ( $content )) {

			header ( "HTTP/1.0 404 Not Found" );

			show_404 ( 'page' );

			exit ();

		}

	} else {

		$content = CI::model('content')->getContentHomepage ();

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

	if (trim ( $post ['page_301_redirect_to_post_id'] ) != '') {

		$gogo = CI::model('content')->getContentURLByIdAndCache ( $post ['page_301_redirect_to_post_id'] );

		if (CI::model('core')->validators_isUrl ( $gogo ) == true) {

			header ( 'Location: ' . $gogo );

			exit ();

		} else {

			exit ( "Trying to go to invalid url: $gogo" );

		}

	}
}

$this->template ['page'] = $page;

//var_dump($post);
$this->template ['post'] = $post;

$this->load->vars ( $this->template );

//var_dump($post);


$GLOBALS ['ACTIVE_PAGE_ID'] = $content ['id'];

if (defined ( 'ACTIVE_PAGE_ID' ) == false) {

	define ( 'ACTIVE_PAGE_ID', $page ['id'] );

}

$the_active_site_template = CI::model('core')->optionsGetByKey ( 'curent_template' );

$the_active_site_template_dir = TEMPLATEFILES . $the_active_site_template . '/';

if (defined ( 'ACTIVE_TEMPLATE_DIR' ) == false) {

	define ( 'ACTIVE_TEMPLATE_DIR', $the_active_site_template_dir );

}

$the_active_site_template = CI::model('core')->optionsGetByKey ( 'curent_template' );

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

$active_categories = CI::model('content')->contentActiveCategoriesForPageIdAndCache ( $page ['id'], $url );

//
//$active_categories = CI::model('content')->contentActiveCategoriesForPageId2 ( $page ['id'], $url );


$this->template ['active_categories'] = $active_categories;

$this->load->vars ( $this->template );

//tags


$tags = CI::model('core')->getParamFromURL ( 'tags' );

//var_dump($tags);
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

$created_by = CI::model('core')->getParamFromURL ( 'author' );

//var_dump($tags);
if ($created_by != '') {

	$this->template ['created_by'] = $created_by;

} else {

//$this->template ['created_by'] = false;
}

$this->template ['selected_tags'] = $selected_tags;

$this->template ['active_tags'] = $selected_tags;

$this->load->vars ( $this->template );

if ($page ['content_subtype'] == 'blog_section' or $page ['content_subtype'] == 'dynamic') {

	/* POSTS: lets get some posts*/

	//articles list


	$active_categories2 = CI::model('content')->contentActiveCategoriesForPageIdAndCache ( $page ['id'], $url, true );

	$posts_data = false;

	//var_dump($active_categories2);
	$posts_data ['selected_categories'] = ($active_categories2);

	if (($_POST ['search_by_keyword'] != '') or ($_POST ['begin_search'] != '') ) {

		//$togo = site_url('search/keyword:').$searchsite ;
		//redirect($togo);
		if (empty ( $active_categories2 )) {

			$togo = CI::model('content')->getContentURLByIdAndCache ( $page ['id'] );

		} else {

			$togo = CI::model('content')->taxonomyGetUrlForTaxonomyId ( $active_categories2 [0] );

		}
		if ($_POST ['selected_categories'] != '') {
		$search_cats = str_replace(' ', '',$_POST ['selected_categories']);
		$search_cats = '/selected_categories:'.$search_cats;
		}
		if ($_POST ['search_by_keyword'] != '') {
		$togo = $togo . '/keyword:' . stripslashes ( $_POST ['search_by_keyword'] );
		}
		$temp1 = CI::model('core')->getParamFromURL ( 'view' );




		if (trim ( $temp1 ) != '') {
			header ( 'Location: ' . $togo . '/view:' . $temp1 . $search_cats);
		} else {
			header ( 'Location: ' . $togo. $search_cats );
		}
		exit ();

	}

	$strict_category_selection = CI::model('core')->getParamFromURL ( 'strict_category_selection' );

	if ($strict_category_selection == 'y') {

		$posts_data ['strict_category_selection'] = true;

	} else {

		$posts_data ['strict_category_selection'] = false;

	}

	$search_for = CI::model('core')->getParamFromURL ( 'keyword' );

	if ($search_for != '') {

		$search_for = html_entity_decode ( $search_for );

		$search_for = urldecode ( $search_for );

		$search_for = htmlspecialchars_decode ( $search_for );

		$posts_data ['search_by_keyword'] = $search_for;

		$this->template ['search_for_keyword'] = $search_for;

		$this->load->vars ( $this->template );

	}

	$strict_category_selection = CI::model('core')->getParamFromURL ( 'strict-category' );

	if ($strict_category_selection == 'y') {

		$posts_data ['strict_category_selection'] = true;

	} else {

	//$posts_data ['strict_category_selection'] = false;
	}

	$type = CI::model('core')->getParamFromURL ( 'type' );

	if (trim ( $type ) != '' && trim ( $type ) != 'blog') {

		$posts_data ['content_subtype'] = $type;

	} else {

		$posts_data ['content_subtype'] = 'none';
	}

	$typev = CI::model('core')->getParamFromURL ( 'typev' );

	if (trim ( $typev ) != '') {

		$posts_data ['content_subtype_value'] = $typev;

	} else {

		$posts_data ['content_subtype_value'] = 'none';
	}

	$posts_data ['selected_tags'] = ($selected_tags);

	$posts_data ['content_type'] = 'post';

	$items_per_page = CI::model('core')->optionsGetByKey ( 'default_items_per_page' );

	$items_per_page = intval ( $items_per_page );

	//var_dump($items_per_page);
	//exit;
	//	if (intval ( $items_per_page ) < 10) {
	//$items_per_page = 10;
	//	}
	//category_no_paging
	//var_dump($active_categories2);
	if (empty ( $active_categories2 )) {

		$active_categories2 = array ();

	}

	foreach ( $active_categories2 as $active_cat ) {

		if (CI::model('content')->taxonomyCheckIfParamExistForSingleItemId ( $active_cat, 'category_no_paging' ) == true) {

			$items_per_page = intval ( 9999 );

		}

		if ($post == false) {

			if (empty ( $post )) {

				//var_dump ( $post );


				$the_taxonomy_item_fulll = CI::model('content')->taxonomyGetSingleItemById ( $active_cat );

				if (trim ( $the_taxonomy_item_fulll ['page_301_redirect_link'] ) != '') {

					$gogo = $the_taxonomy_item_fulll ['page_301_redirect_link'];

					if (CI::model('core')->validators_isUrl ( $gogo ) == true) {

						header ( 'Location: ' . $gogo );

						exit ();

					} else {

						exit ( "Trying to go to invalid url: $gogo" );

					}

				} else {

				}

				if (trim ( $the_taxonomy_item_fulll ['page_301_redirect_to_post_id'] ) != '') {

					$gogo = CI::model('content')->getContentURLByIdAndCache ( $the_taxonomy_item_fulll ['page_301_redirect_to_post_id'] );

					//exit($gogo);
					if (CI::model('core')->validators_isUrl ( $gogo ) == true) {

						header ( 'Location: ' . $gogo );

						exit ();

					} else {

						exit ( "Trying to go to invalid url: $gogo" );

					}

				} else {

				}

			}

		}

	}

	$curent_page = CI::model('core')->getParamFromURL ( 'curent_page' );

	if (intval ( $curent_page ) < 1) {

		$curent_page = 1;

	}

	//voted?
	$voted = CI::model('core')->getParamFromURL ( 'voted' );

	if (($timestamp = strtotime ( $voted )) === false) {
		//echo "The string ($str) is bogus";
		$this->template ['selected_voted'] = false;
	} else {

		$posts_data ['voted'] = $voted;

		$this->template ['selected_voted'] = true;

	}

	/*
					if (intval ( $voted ) > 0) {
						$posts_data ['voted'] = $voted;
						$this->template ['selected_voted'] = true;

					} else {
						$this->template ['selected_voted'] = false;
					}
					*/

	$this->load->vars ( $this->template );
	$orderby1 = array ();
	$order_url = CI::model('core')->getParamFromURL ( 'ord' );
	$order_direction_url = CI::model('core')->getParamFromURL ( 'ord-dir' );
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

	//$orderby1 = array ();


	//$orderby1 [0] = 'updated_on';


	//$orderby1 [1] = 'DESC';


	//function getContent($data, $orderby = false, $limit = false, $count_only = false, $short_data = false, $only_fields = false) {


	$page_start = ($curent_page - 1) * $items_per_page;

	$page_end = ($page_start) + $items_per_page;
	if (! empty ( $posts_data )) {

		//	var_dump( array ($page_start, $page_end ));


		//$data = CI::model('content')->getContentAndCache ( $posts_data, $orderby1, array ($page_start, $page_end ), false, $only_fields = false );


		if (is_file ( ACTIVE_TEMPLATE_DIR . 'controllers/pre_posts_get.php' )) {
			include_once ACTIVE_TEMPLATE_DIR . 'controllers/pre_posts_get.php';
		}

		//						var_dump($posts_data);


		//@todo add and cache
		$posts_data2 = $posts_data;
		$posts_data2 ['use_fetch_db_data'] = true;
		$posts_data2 ['orderby'] = $orderby1;
		//$data = CI::model('content')->getContentAndCache ( $posts_data, $orderby1, array ($page_start, $page_end ), $short_data = false, $only_fields = array ('id', 'content_title', 'content_body', 'content_url', 'content_filename', 'content_parent', 'content_filename_sync_with_editor', 'content_body_filename' ) );
		$data = CI::model('content')->contentGetByParams ( $posts_data2 );
		//var_dump($data);
		//						p($data, 1);


		$this->template ['posts'] = $data ['posts'];

		$posts = $data;

		//	$results_count = CI::model('content')->getContentAndCache ( $posts_data, $orderby1, false, true, $short_data = true, $only_fields = array ('id' ) );


		$content_pages_count = $data ["posts_pages_count"];

		//var_dump ( $results_count, $items_per_page );
		$this->template ['posts_pages_count'] = $data ["posts_pages_count"];

		$this->template ['posts_pages_curent_page'] = $data ["posts_pages_curent_page"];

		//get paging urls
		$content_pages = CI::model('content')->pagingPrepareUrls ( false, $content_pages_count );

		//var_dump($content_pages);
		$this->template ['posts_pages_links'] = $content_pages;
	}

//define ( "ACTIVE_CONTENT_ID", $content['id'] );
/* END POSTS: lets get some posts*/

//	var_dump($content);
}

if ($page ['content_subtype'] == 'module') {

	$dirname = $page ['content_subtype_value'];

	if (is_dir ( PLUGINS_DIRNAME . $dirname )) {

		if (is_file ( PLUGINS_DIRNAME . $dirname . '/controller.php' )) {

			CI::model('core')->plugins_setRunningPlugin ( $dirname );

			//$this->load->file ( PLUGINS_DIRNAME . $dirname . '/controller.php', true );
			include_once PLUGINS_DIRNAME . $dirname . '/controller.php';

		}

	}

}

if (! empty ( $post )) {

	$cats = CI::model('content')->contentGetActiveCategoriesForPostIdAndCache ( $post ['id'] );

	$this->template ['active_categories'] = $cats;

	$this->load->vars ( $this->template );

//	var_dump($cats);
}

/*
			* We will setup the meta tags here
			*
			*
			*
			*
			*
			*
			* */

//setup meta tags


if (! empty ( $posts )) {

	$active_categories2 = CI::model('content')->contentActiveCategoriesForPageIdAndCache ( $page ['id'], $url, true );

	$meta = CI::model('content')->metaTagsGenerateByContentId ( $page ['id'], $posts_data = $posts, $selected_taxonomy = $active_categories );

}

if (! empty ( $post )) {

	//	var_dump ( $post );
	$meta = CI::model('content')->metaTagsGenerateByContentId ( $post ['id'] );

//	var_dump ( $meta );
} elseif (! empty ( $posts )) {

	$active_categories2 = CI::model('content')->contentActiveCategoriesForPageIdAndCache ( $page ['id'], $url, true );

	$meta = CI::model('content')->metaTagsGenerateByContentId ( $page ['id'], $posts_data = $posts, $selected_taxonomy = $active_categories );

}

elseif (! empty ( $page )) {

	$meta = CI::model('content')->metaTagsGenerateByContentId ( $page ['id'] );

}

if (! empty ( $post )) {

	$meta = CI::model('content')->metaTagsGenerateByContentId ( $post ['id'] );

}

//if (! empty ( $post )) {
//var_dump ( $post );
//$meta = CI::model('content')->metaTagsGenerateByContentId ( $post ['id'] );
//}
$content ['content_meta_title'] = $meta ['content_meta_title'];

$content ['content_meta_description'] = $meta ['content_meta_description'];

$content ['content_meta_keywords'] = $meta ['content_meta_keywords'];

//	$content ['content_meta_other_code'] = $meta ['content_meta_other_code'];


//	var_dump($content);
//exit;
//END setup meta tags


if (empty ( $active_categories )) {

	$active_categories = array ();

}

if (empty ( $active_categories2 )) {

	$active_categories2 = array ();

}

$active_categories_temp = array ();

if ($page ['content_subtype'] == 'blog_section' or $page ['content_subtype'] == 'dynamic') {

	if ($page ['content_subtype_value'] != '') {

		$active_categories_temp [] = $page ['content_subtype_value'];

		$content_category_temp_id = $page ['content_subtype_value'];

	}

} else {

	$content_category_temp_id = false;

}

if (is_array ( $active_categories_temp ) == false) {
	$active_categories_temp = array ();
}
if (is_array ( $active_categories ) == false) {
	$active_categories = array ();
}
if (is_array ( $active_categories2 ) == false) {
	$active_categories2 = array ();
}

$active_categories_temp = array_merge ( $active_categories_temp, $active_categories, $active_categories2 );

$active_categories_temp = array_unique ( $active_categories_temp );

if (! empty ( $active_categories_temp )) {

	$taxonomy_tree = array ();

	$taxonomy_tree [] = $content_category_temp_id;

	foreach ( $active_categories_temp as $thecategory ) {

		$temp = CI::model('content')->taxonomyGetChildrenItemsIdsRecursiveAndCache ( $thecategory, 'category' );

		$taxonomy_tree = @array_merge ( $taxonomy_tree, $temp );

	}

}

if (! empty ( $taxonomy_tree )) {

	$taxonomy_tree = @array_unique ( $taxonomy_tree );

}

//var_dump($taxonomy_tree);
//print '<pre>';


$taxonomy_tree_reverse = ($taxonomy_tree);

if (! empty ( $taxonomy_tree_reverse )) {

	rsort ( $taxonomy_tree_reverse );

}

if (! empty ( $taxonomy_tree )) {

	$the_active_site_template = CI::model('core')->optionsGetByKey ( 'curent_template' );

	$the_active_site_template_dir = TEMPLATEFILES . $the_active_site_template . '/';

	$append_files = true;

	foreach ( $taxonomy_tree_reverse as $something ) {

		$temp = array ();

		$temp ['id'] = $something;

		$temp = CI::model('content')->taxonomyGet ( $temp );

		$temp = $temp [0];

		//print
		//var_dump ( $temp );


		if ($temp ['taxonomy_filename_exclusive'] != '') {

			$the_file = $the_active_site_template_dir . $temp ['taxonomy_filename_exclusive'];

			if (is_file ( $the_file )) {

				if (is_readable ( $the_file )) {

					//print $the_file;
					//include_once $the_file;
					$this->load->vars ( $this->template );

					$taxonomy_data = $taxonomy_data . $this->load->file ( $the_file, true );

					$append_files = false;

				} else {

					exit ( $the_file );

				}

			}

		}

		if ($append_files == true) {

			if ($temp ['taxonomy_filename'] != '') {

				$the_file = $the_active_site_template_dir . $temp ['taxonomy_filename'];

				if (is_file ( $the_file )) {

					if (is_readable ( $the_file )) {

						//include_once $the_file;
						$this->load->vars ( $this->template );

						$taxonomy_data = $taxonomy_data . $this->load->file ( $the_file, true );

					} else {

						exit ( $the_file );

					}

				}

			}

		}

	}

} else {

	$taxonomy_data = false;

}

?>