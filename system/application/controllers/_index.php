<?php
class Index extends Controller {
	
	function __construct() {
		
		parent::Controller ();
		
		require_once (APPPATH . 'controllers/default_constructor.php');
	
	}
	
	function index() {
		
		//var_dump($_COOKIE); 
		

		$content_display_mode = false;
		
		if (defined ( 'INTERNAL_API_CALL' ) == true) {
			$func = INTERNAL_API_CALL_EXEC;
			$microweber_api = $this;
			switch ($func) {
				case 'user_get_info_as_array' :
					$the_user = CI::library('session')->userdata ( 'the_user' );
					$api_data = $the_user;
					//return($api_data);
					break;
				
				default :
					break;
			}
			
			$content_display_mode = 'basic_api_only';
		
		}
		
		if (defined ( 'LOAD_EXTENDED_API' ) == true) {
			$the_user = CI::library('session')->userdata ( 'the_user' );
			$api_data = $the_user;
			$content_display_mode = 'extended_api_with_no_template';
		
		}
		
		if ($content_display_mode == 'basic_api_only') {
			$func = INTERNAL_API_CALL_EXEC;
			$microweber_api = $this;
			switch ($func) {
				case 'user_get_info_as_array' :
					$the_user = CI::library('session')->userdata ( 'the_user' );
					$api_data = $the_user;
					//return($api_data);
					break;
				
				default :
					break;
			}
			
			$CI = & get_instance ();
			return $CI;
		} else {
			$subdomain_user = CI::library('session')->userdata ( 'subdomain_user' );
			$this->template ['subdomain_user'] = $subdomain_user;
			
			if ($content_display_mode != 'extended_api_with_no_template') {
				
				if (strpos ( $_SERVER ['HTTP_USER_AGENT'], "MSIE 8" )) {
					
				//	header ( "X-UA-Compatible: IE=7" );
				

				}
				
				if (strpos ( $_SERVER ['HTTP_USER_AGENT'], "MSIE 8" )) {
					
				//	header ( "X-UA-Compatible: IE=EmulateIE7" );
				

				}
			}
			//ref:2
			

			$site_cache_time = 60 * 60 * 60;
			
			//$site_cache_time = 1;
			//$site_cache_time = false;
			$url = $this->uri->uri_string ();
			
			$url = str_ireplace ( '\\', '', $url );
			
			$cache_content = false;
			
			$whole_site_cache_id = 'url_' . md5 ( $url );
			
			if (! $_POST) {
				
			//$cache_content = CI::model('core')->cacheGetContentAndDecode ( $whole_site_cache_id, $site_cache_time );
			}
			
			$cache_content = false;
			
			if (($cache_content) != false) {
				
			//$layout = $cache_content;
			

			//CI::library('output')->set_output ( $layout );
			

			} else {
				
				//$this->template ['load_google_map'] = false;
				
				//is slash
				$slash = substr ( "$url", 0, 1 );
				
				if ($slash == '/') {
					
					$url = substr ( "$url", 1, strlen ( $url ) );
				
				}
				
				//$post_maybe = CI::model('content')->getContentByURLAndCache ( $url );
				$post_maybe = CI::model('content')->getContentByURLAndCache ( $url );
				if (intval ( $post_maybe ['id'] ) != 0) {
					$post_maybe = CI::model('content')->contentGetByIdAndCache ( $post_maybe ['id'] );
				}
				//var_Dump($post_maybe);
				$page = CI::model('content')->getPageByURLAndCache ( $url );
				
				if ($post_maybe ['content_type'] == 'post') {
					
					//$post = CI::model('content')->getPostByURLAndCache ( $content ['id'], $url );
					$post = $post_maybe;
				
				}
				
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
				
				$the_active_site_template = CI::model('content')->optionsGetByKey ( 'curent_template' );
				
				$the_active_site_template_dir = TEMPLATEFILES . $the_active_site_template . '/';
				
				if (defined ( 'ACTIVE_TEMPLATE_DIR' ) == false) {
					
					define ( 'ACTIVE_TEMPLATE_DIR', $the_active_site_template_dir );
				
				}
				
				$the_active_site_template = CI::model('content')->optionsGetByKey ( 'curent_template' );
				
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
				

				$ipto = $_SERVER ["REMOTE_ADDR"];
				
				if ($ipto == "78.90.64.107") {
					
				//	var_dump ( $active_categories );
				}
				
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
					
					if ($_POST ['search_by_keyword'] != '') {
						
						//$togo = site_url('search/keyword:').$searchsite ;
						//redirect($togo);
						if (empty ( $active_categories2 )) {
							
							$togo = CI::model('content')->getContentURLByIdAndCache ( $page ['id'] );
						
						} else {
							
							$togo = CI::model('content')->taxonomyGetUrlForTaxonomyId ( $active_categories2 [0] );
						
						}
						
						$togo = $togo . '/keyword:' . stripslashes ( $_POST ['search_by_keyword'] );
						
						header ( 'Location: ' . $togo );
						
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
					
					$posts_data ['selected_tags'] = ($selected_tags);
					
					$posts_data ['content_type'] = 'post';
					
					$items_per_page = CI::model('content')->optionsGetByKey ( 'default_items_per_page' );
					
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
					
					if (intval ( $voted ) > 0) {
						
						$posts_data ['voted'] = $voted;
						
						$this->template ['selected_voted'] = true;
					
					} else {
						
						$this->template ['selected_voted'] = false;
					
					}
					
					$this->load->vars ( $this->template );
					
					$orderby1 = array ();
					
					$orderby1 [0] = 'updated_on';
					
					$orderby1 [1] = 'DESC';
					
					//function getContent($data, $orderby = false, $limit = false, $count_only = false, $short_data = false, $only_fields = false) {
					

					$page_start = ($curent_page - 1) * $items_per_page;
					
					$page_end = ($page_start) + $items_per_page;
					if (! empty ( $posts_data )) {
						
						//	var_dump( array ($page_start, $page_end ));
						

						//$data = CI::model('content')->getContentAndCache ( $posts_data, $orderby1, array ($page_start, $page_end ), false, $only_fields = false );
						

						//var_dump($posts_data);
						$data = CI::model('content')->getContentAndCache ( $posts_data, $orderby1, array ($page_start, $page_end ), $short_data = false, $only_fields = array ('id', 'content_title', 'content_body', 'content_url', 'content_filename', 'content_parent', 'content_filename_sync_with_editor', 'content_body_filename' ) );
						
						//var_dump($data);
						

						$this->template ['posts'] = $data;
						
						$posts = $data;
						
						$results_count = CI::model('content')->getContentAndCache ( $posts_data, $orderby1, false, true, $short_data = true, $only_fields = array ('id' ) );
						
						$content_pages_count = ceil ( $results_count / $items_per_page );
						
						//var_dump ( $results_count, $items_per_page );
						$this->template ['posts_pages_count'] = $content_pages_count;
						
						$this->template ['posts_pages_curent_page'] = $curent_page;
						
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
						
						$taxonomy_tree = array_merge ( $taxonomy_tree, $temp );
					
					}
				
				}
				
				if (! empty ( $taxonomy_tree )) {
					
					$taxonomy_tree = array_unique ( $taxonomy_tree );
				
				}
				
				//var_dump($taxonomy_tree);
				//print '<pre>';
				

				$taxonomy_tree_reverse = ($taxonomy_tree);
				
				if (! empty ( $taxonomy_tree_reverse )) {
					
					rsort ( $taxonomy_tree_reverse );
				
				}
				
				if (! empty ( $taxonomy_tree )) {
					
					$the_active_site_template = CI::model('content')->optionsGetByKey ( 'curent_template' );
					
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
				
				//print '</pre>';
				//var_dump ( $taxonomy_tree );
				

				$global_template_replaceables = array ();
				
				$global_template_replaceables ["content_meta_title"] = $content ['content_title'];
				
				$global_template_replaceables ["content_meta_title"] = ($content ['content_meta_title'] != '') ? $content ['content_meta_title'] : CI::model('content')->optionsGetByKey ( 'content_meta_title' );
				
				$global_template_replaceables ["content_meta_description"] = ($content ['content_meta_description'] != '') ? $content ['content_meta_description'] : CI::model('content')->optionsGetByKey ( 'content_meta_description' );
				
				$global_template_replaceables ["content_meta_keywords"] = ($content ['content_meta_keywords'] != '') ? $content ['content_meta_keywords'] : CI::model('content')->optionsGetByKey ( 'content_meta_keywords' );
				
				$global_template_replaceables ["content_meta_other_code"] = ($content ['content_meta_other_code'] != '') ? $content ['content_meta_other_code'] : CI::model('content')->optionsGetByKey ( 'content_meta_other_code' );
				
				$global_template_replaceables ["content_meta_other_code"] = htmlspecialchars_decode ( $global_template_replaceables ["content_meta_other_code"], ENT_QUOTES );
				$global_template_replaceables ["content_meta_other_code"] = html_entity_decode ( $global_template_replaceables ["content_meta_other_code"] );
				
				//	var_dump($the_template_url);
				//	exit;
				

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
				
				if (trim ( $post ['content_filename'] ) != '') {
					
					if (is_readable ( $the_active_site_template_dir . $post ['content_filename'] ) == true) {
						
						$this->load->vars ( $this->template );
						
						$content_from_filename_post = $this->load->file ( $the_active_site_template_dir . $post ['content_filename'], true );
						
						$this->load->vars ( $this->template );
					
					}
				
				}
				
				//if ( empty ( $subdomain_user )) {
					
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
					
					} else {
						
						if ($content ['content_type'] == 'page') {
							
							if ($content ['content_layout_file'] == '') {
								
								$use_the_parent_page_layout = false;
								
								$parent_pages = CI::model('content')->getParentPagesIdsForPageIdAndCache ( $content ['id'] );
								
								if (! empty ( $parent_pages )) {
									
									//	var_dump($parent_pages);
									foreach ( $parent_pages as $parent_page ) {
										
										if ($use_the_parent_page_layout == false) {
											
											$parent_page_info = CI::model('content')->contentGetByIdAndCache ( $parent_page );
											
											//var_dump($parent_page_info);
											if (strval ( $parent_page_info ['content_layout_file'] ) != '') {
												
												if (is_readable ( $the_active_site_template_dir . $parent_page_info ['content_layout_file'] ) == true) {
													
													$use_the_parent_page_layout = $parent_page_info ['content_layout_file'];
												
												}
											
											}
										
										}
									
									}
									
								//
								}
								
								//var_dump($use_the_parent_page_layout );
								if (is_readable ( $the_active_site_template_dir . $use_the_parent_page_layout ) == true) {
									
									$this->load->vars ( $this->template );
									
									$layout = $this->load->file ( $the_active_site_template_dir . $use_the_parent_page_layout, true );
								
								}
								if (strval ( $layout == '' )) {
									if (is_readable ( $the_active_site_template_dir . 'default_layout.php' ) == true) {
										
										$this->load->vars ( $this->template );
										
										$layout = $this->load->file ( $the_active_site_template_dir . 'default_layout.php', true );
									
									} else {
										
										header ( "HTTP/1.1 500 Internal Server Error" );
										
										show_error ( "Layout file {$content ['content_layout_file']} is not readable or doesn't exist in the templates directory!" );
										
										exit ();
									
									}
								
								}
							
							}
						
						}
					
					}
				//} else {
				//	$this->load->vars ( $this->template );
					
				//	$layout = $this->load->file ( $the_active_site_template_dir . 'affiliate_site_1/default_layout.php', true );
				//}
				
				if (trim ( $content ['content_filename'] ) != '') {
					
					if (is_readable ( $the_active_site_template_dir . $content ['content_filename'] ) == true) {
						
						$this->load->vars ( $this->template );
						
						//$content_filename = $this->load->file ( $the_active_site_template_dir . $content ['content_filename'], true );
						//$layout = str_ireplace ( '{content}', $content_filename, $layout );
						$layout = str_ireplace ( '{content}', $content_filename_pre, $layout );
					
					}
				
				}
				
				if ($content ['content_body_filename'] != false) {
					if (trim ( $content ['content_body_filename'] ) != '') {
						$the_active_site_template12 = CI::model('content')->optionsGetByKey ( 'curent_template' );
						$the_active_site_template_dir1 = TEMPLATEFILES . $the_active_site_template12 . '/content_files/';
						if (is_file ( $the_active_site_template_dir1 . $content ['content_body_filename'] ) == true) {
							{
								//$v1 = file_get_contents ( $the_active_site_template_dir . $content ['content_body_filename'] );
								//$v1 = html_entity_decode ( $v1 );
								$this->load->vars ( $this->template );
								$content_filename1 = $this->load->file ( $the_active_site_template_dir1 . $content ['content_body_filename'], true );
								
								//print($content ['content_body']);
								$layout = str_ireplace ( '{content}', $content_filename1, $layout );
								//$v = htmlspecialchars_decode ( $v );
							}
						}
					
					}
				
				} else {
					
					if (trim ( $content ['content_body'] ) != '') {
						
						$this->load->vars ( $this->template );
						
						//print($content ['content_body']);
						$layout = str_ireplace ( '{content}', $content ['content_body'], $layout );
					
					}
				}
				
				if (trim ( $taxonomy_data ) != '') {
					
					$this->load->vars ( $this->template );
					
					$layout = str_ireplace ( '{content}', $taxonomy_data, $layout );
				
				}
				
				if (trim ( $content_from_filename_post ) != '') {
					
					//var_dump($content_from_filename_post);
					$this->load->vars ( $this->template );
					
					$layout = str_ireplace ( '{post_content}', $content_from_filename_post, $layout );
				
				}
				
				//just remove it if its still there
				$this->load->vars ( $this->template );
				
				$layout = str_ireplace ( '{content}', '', $layout );
				$layout = str_ireplace ( '{SITEURL}', site_url (), $layout );
				//var_dump($global_template_replaceables);
				//
				

				$layout = CI::model('content')->applyGlobalTemplateReplaceables ( $layout, $global_template_replaceables );
				
				if ($content_display_mode == 'extended_api_with_no_template') {
					
					$the_user = CI::library('session')->userdata ( 'the_user' );
					$api_data = $the_user;
					
					$CI = & get_instance ();
					return $CI;
				
				} else {
					//CI::model('core')->cacheWriteAndEncode ( $layout, $whole_site_cache_id, $cache_group = 'global' );
					

					//p(CI::model('core')->cache_storage_hits);
					//p(CI::model('core')->cache_storage_decoded);
					

					CI::library('output')->set_output ( $layout );
				}
			
			}
		}
	}
	
	function userbase() {
		
		$curent_page = CI::model('core')->getParamFromURL ( 'curent_page' );
		
		if (intval ( $curent_page ) < 1) {
			
			$curent_page = 1;
		
		}
		
		$items_per_page = CI::model('content')->optionsGetByKey ( 'default_items_per_page' );
		
		$items_per_page = intval ( $items_per_page );
		
		$layout = false;
		
		$global_template_replaceables = false;
		
		$content = array ();
		
		$content ['content_layout_file'] = 'default_layout.php';
		
		$action = CI::model('core')->getParamFromURL ( 'action' );
		
		$username = CI::model('core')->getParamFromURL ( 'username' );
		
		$id = CI::model('core')->getParamFromURL ( $id );
		
		$the_active_site_template = CI::model('content')->optionsGetByKey ( 'curent_template' );
		
		$the_active_site_template_dir = TEMPLATEFILES . $the_active_site_template . '/';
		
		if (defined ( 'ACTIVE_TEMPLATE_DIR' ) == false) {
			
			define ( 'ACTIVE_TEMPLATE_DIR', $the_active_site_template_dir );
		
		}
		
		$the_active_site_template = CI::model('content')->optionsGetByKey ( 'curent_template' );
		
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
		

		switch ($action) {
			
			case 'profile' :
				
				$users_list = array ();
				
				$users_list ['username'] = $username;
				
				$users_list = CI::model('users')->getUsers ( $users_list, false );
				
				$this->template ['user_data'] = $users_list [0];
				
				$this->load->vars ( $this->template );
				
				$content ['content_filename'] = 'users/userbase/user_profile.php';
				
				break;
			
			default :
				
				$users_list = array ();
				
				$users_list = CI::model('users')->getUsers ( $users_list, array ($page_start, $page_end ) );
				
				$this->template ['users_list'] = $users_list;
				
				$results_count = CI::model('users')->getUsers ( $users_list, false, true );
				
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
		

		CI::library('output')->set_output ( $layout );
	
	}
	
	function users() {
		global $cms_db_tables;
		$table = $cms_db_tables ['table_users'];
		
		$layout = false;
		
		$global_template_replaceables = false;
		
		$content = array ();
		
		$content ['content_layout_file'] = 'default_layout.php';
		
		$user_action = CI::model('core')->getParamFromURL ( 'user_action' );
		
		$the_active_site_template = CI::model('content')->optionsGetByKey ( 'curent_template' );
		
		$the_active_site_template_dir = TEMPLATEFILES . $the_active_site_template . '/';
		
		if (defined ( 'ACTIVE_TEMPLATE_DIR' ) == false) {
			
			define ( 'ACTIVE_TEMPLATE_DIR', $the_active_site_template_dir );
		
		}
		
		$the_active_site_template = CI::model('content')->optionsGetByKey ( 'curent_template' );
		
		$the_active_site_template_dir = TEMPLATEFILES . $the_active_site_template . '/';
		
		$the_template_url = site_url ( 'userfiles/' . TEMPLATEFILES_DIRNAME . '/' . $the_active_site_template );
		
		$the_template_url = $the_template_url . '/';
		
		define ( "TEMPLATE_URL", $the_template_url );
		
		$user_session = array ();
		
		$user_session = CI::library('session')->userdata ( 'user_session' );
		$the_user = CI::library('session')->userdata ( 'the_user' );
		
		//
		if ($user_session ['is_logged'] != 'yes') {
			
			// 	var_dump($user_session);
			if (($user_action != 'login') && ($user_action != 'register') && ($user_action != 'forgotten_pass')) {
				
				redirect ( 'users/user_action:login' );
			
			}
		
		}
		
		$this->load->vars ( $this->template );
		
		//var_dump($user_action);
		switch ($user_action) {
			
			case 'profile' :
				
				$this->template ['load_wysiwyg'] = true;
				$this->template ['user_edit_done'] = FALSE;
				
				$reg_is_error = false;
				
				$this->load->vars ( $this->template );
				
				if ($_POST) {
					
					$username = $this->input->post ( 'username' );
					
					$email = $this->input->post ( 'email' );
					
					$userdata_check = array ();
					
					$userdata_check ['username'] = $username;
					
					$userdata_check = CI::model('users')->getUsers ( $userdata_check );
					
					$userdata_check = $userdata_check [0];
					
					if ($username != '') {
						
						if (! empty ( $the_user )) {
							//var_dump($the_user, $userdata_check);
							if ($userdata_check ['id'] != $the_user ['id']) {
								
								$reg_is_error = true;
								
								$user_edit_errors ['username_taken'] = 'This username is owned by another user!';
							
							}
						} else {
							if ($userdata_check ['id'] != $user_session ['user_id']) {
								
								$reg_is_error = true;
								
								$user_edit_errors ['username_taken'] = 'This username is owned by another user!';
							
							}
						}
					
					} else {
						
						$reg_is_error = true;
						
						$user_edit_errors ['username_blank'] = 'Cannot use blank username!';
					
					}
					
					if ($email != '') {
						
						$userdata_check = array ();
						
						$userdata_check ['email'] = $email;
						
						$userdata_check = CI::model('users')->getUsers ( $userdata_check );
						
						$userdata_check = $userdata_check [0];
						
						//var_dump ( $userdata_check );
						//var_dump ( $user_session );
						if (! empty ( $userdata_check )) {
							
							if ($userdata_check ['id'] != $user_session ['user_id']) {
								
								$reg_is_error = true;
								
								$user_edit_errors ['email_taken'] = 'This email is owned by another user!';
							
							}
						
						}
					
					} else {
						
						$reg_is_error = true;
						
						$user_edit_errors ['email_blankn'] = 'Cannot use blank email!';
					
					}
					
					if ($reg_is_error == true) {
						
						$this->template ['user_edit_errors'] = $user_edit_errors;
					
					} else {
						
						$to_save = $_POST;
						
						$to_save ['id'] = $user_session ['user_id'];
						
						//var_dump ( $to_save );
						

						CI::model('users')->saveUser ( $to_save );
						
						$this->template ['user_edit_errors'] = false;
						
						$this->template ['user_edit_done'] = true;
						
						$this->load->vars ( $this->template );
					
					}
				
				}
				
				$userdata = array ();
				
				$userdata ['id'] = $user_session ['user_id'];
				
				$userdata = CI::model('users')->getUsers ( $userdata );
				
				$userdata = $userdata [0];
				
				$this->template ['form_values'] = $userdata;
				
				$this->load->vars ( $this->template );
				
				$user_session ['user_action'] = $user_action;
				
				$content ['content_filename'] = 'users/profile.php';
				
				break;
			
			case 'register' :
				$user_action = 'register';
				
				$this->load->library('captcha');
				
				if ($_POST) {
					
					$this->template ['form_values'] = $_POST;
					
					$user_register_errors = array ();
					
					$username = $this->input->post ( 'username' );
					
					$godfather = $this->input->post ( 'parent_affil' );
					
					$email = $this->input->post ( 'email' );
					
					$password = $this->input->post ( 'password' );
					
					$password2 = $this->input->post ( 'password2' );
					
					$to_reg = array ();
					
					$to_reg ['username'] = $username;
					
					$to_reg ['email'] = $email;
					
					$to_reg ['password'] = $password;
					
					$to_reg ['is_active'] = 'y';
					
					$to_reg ['is_admin'] = 'n';
					
					//parrent  aff 

					if ($godfather) {
						
						$godfatherRow = CI::model('users')->getUsers(array('username' => $godfather));

						if (!$godfatherRow) {
							$reg_is_error = true;
							$user_register_errors ['username_not_here'] = 'Invalid gotfather username!';
						} else {
							$godfatherRow = $godfatherRow[0];
							$to_reg ['parent_affil'] = $godfatherRow['id'];
						}
						
					} elseif (isset ( $_COOKIE ["microweber_referrer_user_id"] )) {
						$aff = intval ( $_COOKIE ["microweber_referrer_user_id"] );
						if ($aff != 0) {
							$to_reg ['parent_affil'] = $aff;
						}
					
					} 
					
					$reg_is_error = false;
					
					$check_if_exist = CI::model('users')->checkUser ( 'username', $to_reg ['username'] );
					
					$check_if_exist_email = CI::model('users')->checkUser ( 'email', $to_reg ['email'] );
					
					if ($username == '') {
						
						$reg_is_error = true;
						
						$user_register_errors ['username_not_here'] = 'Please enter username!';
					
					}
					
					if ($password == '') {
						
						$reg_is_error = true;
						
						$user_register_errors ['password_not_here'] = 'Please enter password!';
					
					}
					
					if ($email == '') {
						
						$reg_is_error = true;
						
						$user_register_errors ['email_not_here'] = 'Please enter email!';
					
					}
					
					if ($username != '') {
						
						if ($check_if_exist != 0) {
							
							$reg_is_error = true;
							
							$user_register_errors ['username_taken'] = 'This username is taken!';
						
						}
					
					}
					
					if ($email != '') {
						
						if ($check_if_exist_email != 0) {
							
							$reg_is_error = true;
							
							$user_register_errors ['username_emailtaken'] = 'User with this email already exists!';
						
						}
					
					}
					
					if ($password != '') {
						
						if ($password != $password2) {
							
							$reg_is_error = true;
							
							$user_register_errors ['passwords_dont_match'] = 'Password does not match!';
						
						}
					
					}
					
					/*
					 * Captcha validation
					 */
					if (strtolower($this->input->post('captcha_code')) != strtolower(CI::library('session')->userdata('captcha_word'))) {
						
						$reg_is_error = true;
						$user_register_errors ['invalid_captcha'] = 'Invalid or missing secure text!';
					
					}
					
					
					if ($reg_is_error == true) {
						
						$this->template ['user_register_errors'] = $user_register_errors;
					
					} else {
						
						//Send mail
						$userdata = array ();
						$userdata ['id'] = $to_reg['parent_affil'];
						$parent = CI::model('users')->getUsers ( $userdata );
						//$this->dbQuery("select * from firecms_users where id={$to_reg ['parent_affil']}");
						$to_reg ['parent'] = $parent [0] ['username'];
						
						$to_reg ['option_key'] = 'mail_new_user_reg';
						CI::model('core')->sendMail ( $to_reg, true );
						
						//$primarycontent = CI::view ( 'me/register_done', true, true );
						$this->template ['user_registration_done'] = true;
						CI::model('users')->saveUser ( $to_reg );
					
					}
					
					$this->load->vars ( $this->template );
				
				}
				
				$options = array(
					'word'        	=> $this->captcha->generate_word(),
                    'img_path'     	=> USERFILES.'uploads/',
                    'img_url'     	=> USERFILES_URL.'/uploads/',
                    'font_path'     => BASEPATH.'fonts/arial.ttf',
                    'img_width'     => '160',
                    'img_height' 	=> '35',
                    'expiration' 	=> '3600'
				);
               
				$cap = $this->captcha->create_captcha($options);
				
				CI::library('session')->set_userdata('captcha_word', $cap['word']);
			    $this->template ['captcha'] = $cap['image'];
				
				//$user_session ['user_action'] = $user_action;
				$this->load->vars ( $this->template );
				
				$content ['content_filename'] = 'users/register.php';
				
				break;
			
			case 'login' :
				
				$this->load->library('captcha');
		
				if ($_POST) {
					
					// log login attepts
					$loginAttempts = (int)CI::library('session')->userdata('login_attempts') + 1;
					CI::library('session')->set_userdata ('login_attempts', $loginAttempts);
					
					$this->template ['form_values'] = $_POST;

//					p(strtolower($this->input->post('captcha_code')) == strtolower(CI::library('session')->userdata('captcha_word')));
					
					/*
					 * Captcha validation
					 */
					if (!(isset($_POST['captcha_code'])) || 
						(strtolower($this->input->post('captcha_code')) == strtolower(CI::library('session')->userdata('captcha_word')))) {
						
						$username_or_email = $this->input->post ( 'username' );
						
						$password = $this->input->post ( 'password' );
						
						$check = array ();
						
						$check ['email'] = $username_or_email;
						
						$check ['password'] = $password;
						
						$check ['is_active'] = 'y';
						
						$check = CI::model('users')->getUsers ( $check );
						
						//	var_dump($check);
						if (empty ( $check [0] )) {
							
							$check = array ();
							
							$check ['username'] = $username_or_email;
							
							$check ['password'] = $password;
							
							$check ['is_active'] = 'y';
							
							$check = CI::model('users')->getUsers ( $check );
						
						}
						
//						p($check);
						
						if (empty ( $check [0] )) {
							
						} else {
							
							$user_session ['is_logged'] = 'yes';
							
							$user_session ['user_id'] = $check [0] ['id'];
							
							CI::library('session')->set_userdata ( 'user_session', $user_session );
							CI::library('session')->set_userdata ( 'user', $check [0] );
							
							CI::library('session')->unset_userdata('login_attempts');
							
							//var_dump($check);
							redirect ( 'users' );
						
						}
					} else {
						
//						p('invalid secure text');
						
						
					}
				
				}
				
				$options = array(
					'word'        	=> $this->captcha->generate_word(),
                    'img_path'     	=> USERFILES.'uploads/',
                    'img_url'     	=> USERFILES_URL.'/uploads/',
                    'font_path'     => BASEPATH.'fonts/arial.ttf',
                    'img_width'     => '200',
                    'img_height' 	=> '50',
                    'expiration' 	=> '3600'
				);
               
				$cap = $this->captcha->create_captcha($options);
				
				CI::library('session')->set_userdata('captcha_word', $cap['word']);
			    $this->template ['captcha'] = $cap['image'];
				
				$this->load->vars ( $this->template );
				
				$user_session ['user_action'] = $user_action;
				
				$content ['content_filename'] = 'users/login.php';
				
				break;
			
			case 'post_delete' :
				
				$content_id = CI::model('core')->getParamFromURL ( 'id' );
				
				$check_is_permisiions_error = false;
				
				if (intval ( $content_id ) != 0) {
					
					$get_id = array ();
					
					$get_id ['id'] = $content_id;
					
					$get_id = CI::model('content')->getContent ( $get_id );
					
					$get_id = $get_id [0];
					
					//var_dump($get_id);
					if (! empty ( $get_id )) {
						
						if ($get_id ['created_by'] != $user_session ['user_id']) {
							
							//var_dump($get_id ['created_by'], $user_session ['user_id']);
							redirect ( 'users' );
						
						} else {
							
							//$this->template ['form_values'] = $get_id;
							

							//var_dump($content_id);
							CI::model('content')->deleteContent ( $content_id );
							
							redirect ('users');
						
						}
					
					} else {
						
						redirect('users');
					
					}
				
				}
				
				break;
			
			case 'test123':
				
				die('asd');
//				
//				$url = site_url('/affiliate_center/callbacks/callback_microweber.php');
//				if (strstr($url, 'www.') === false) {
//					$url = str_replace('://', '://www.', $url, 1);
//				}
//				p($url);
//				die('asd');
//				
//				$curl = curl_init();
//				
//				  // Setup headers - I used the same headers from Firefox version 2.0.0.6
//				  // below was split up because php.net said the line was too long. :/
//				  $header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
//				  $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
//				  $header[] = "Cache-Control: max-age=0";
//				  $header[] = "Connection: keep-alive";
//				  $header[] = "Keep-Alive: 300";
//				  $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
//				  $header[] = "Accept-Language: en-us,en;q=0.5";
//				  $header[] = "Pragma: "; // browsers keep this blank.
//				
//				  curl_setopt($curl, CURLOPT_URL, "http://waterforlifeusa.com/info.php");//72.47.197.141
//				  curl_setopt($curl, CURLOPT_USERAGENT, 'Googlebot/2.1 (+http://www.google.com/bot.html)');
//				  curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
//				  curl_setopt($curl, CURLOPT_REFERER, 'http://www.google.com');
//				  curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
//				  curl_setopt($curl, CURLOPT_AUTOREFERER, true);
//				  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//				  curl_setopt ( $curl, CURLOPT_FOLLOWLOCATION, 1 ); // allow redirects
//				  curl_setopt($curl, CURLOPT_TIMEOUT, 10);
//				
//				  $html = curl_exec($curl); // execute the curl command
//				  curl_close($curl); // close the connection
//			
//				echo $html;
//				die('----');
//				
//				$url = "https://waterforlifeusa.com/affiliate_center/callbacks/callback_test.php";
//				
//				$ch = curl_init ();
//
//				curl_setopt ( $ch, CURLOPT_URL, $url ); // set url to post to
//				
//				curl_setopt ( $ch, CURLOPT_FAILONERROR, 0 );
//				curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 ); // allow redirects 
//				curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 ); // return into a variable 
//				curl_setopt ( $ch, CURLOPT_TIMEOUT, 60 ); // times out after Ns 
//				curl_setopt ( $ch, CURLOPT_POST, 1 ); // set POST method 
//				curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
//				curl_setopt ( $ch, CURLOPT_VERBOSE, 1 );
//				curl_setopt ( $ch, CURLOPT_HEADER, 1 );
//				//curl_setopt ( $ch, CURLOPT_COOKIEFILE, 1 );
//				
//				$poststring = 'a=b';
//				
//				curl_setopt ( $ch, CURLOPT_POSTFIELDS, $poststring );
//				
//				$result = curl_exec ( $ch ); // run the whole process 
//				
//				curl_close($ch);
//
//				echo '------';
//				p($result);
//				die('+++++++');
//				
//				
////				p($_COOKIE ['referrer_id']);die;
//				
//				$r = $this->cart_model->billingProcessCreditCard();p($r);die;
//				
//				$this->cart_model->payPalTest();
				
				break;
				
			case 'post' :
				
				$content_id = CI::model('core')->getParamFromURL ( 'id' );
				
				$categories_ids_to_remove = array ();
				
				$categories_ids_to_remove ['taxonomy_type'] = 'category';
				
				$categories_ids_to_remove ['users_can_create_content'] = 'n';
				
				$categories_ids_to_remove = CI::model('content')->taxonomyGetIds ( $data = $categories_ids_to_remove, $orderby = false );
				
				//var_dump($categories_ids_to_remove);
				$this->template ['categories_ids_to_remove'] = $categories_ids_to_remove;
				
				$check_is_permisiions_error = false;
				
				if (intval ( $content_id ) != 0) {
					
					$get_id = array ();
					
					$get_id ['id'] = $content_id;
					
					$get_id = CI::model('content')->getContent ( $get_id );
					
					$get_id = $get_id [0];
					
					//var_dump($get_id);
					if (! empty ( $get_id )) {
						
						if ($get_id ['created_by'] != $user_session ['user_id']) {
							
							//var_dump($get_id ['created_by'], $user_session ['user_id']);
							redirect ( 'users' );
						
						} else {
							
							$this->template ['form_values'] = $get_id;
						
						}
					
					} else {
						
						redirect ( 'users' );
					
					}
				
				}
				
				if ($_POST) {
					
					$categories = $_POST ['taxonomy_categories'];
					
					$category = $categories [0];
					
					if (in_array ( $category, $categories_ids_to_remove ) == true) {
						
					//error
					} else {
						
						$check_title = array ();
						
						$check_title ['content_title'] = $_POST ['content_title'];
						
						$check_title ['content_type'] = 'post';
						
						$check_title = CI::model('content')->getContent ( $check_title, $orderby = false, $limit = false, $count_only = false );
						
						$check_title_error = false;
						
						if (! empty ( $check_title )) {
							
							if ($_POST ['id']) {
								
								if ($check_title [0] ['id'] != $_POST ['id']) {
									
									$check_title_error = true;
								
								} else {
									
									$check_title_error = false;
								
								}
							
							} else {
								
								$check_title_error = true;
							
							}
							
						//
						} else {
						
						}
						
						if ($check_title_error == true) {
							
						//errror
						} else {
							
							$taxonomy_categories = array ($category );
							
							$taxonomy = CI::model('content')->taxonomyGetParentItemsAndReturnOnlyIds ( $category );
							
							if (! empty ( $taxonomy )) {
								
								foreach ( $taxonomy as $i ) {
									
									$taxonomy_categories [] = $i;
								
								}
							
							}
							
							//var_dump($taxonomy);
							//exit;
							$to_save = $_POST;
							
							$to_save ['content_type'] = 'post';
							
							$to_save ['taxonomy_categories'] = $taxonomy_categories;
							
							$parent_page = CI::model('content')->contentsGetTheFirstBlogSectionForCategory ( $category );
							
							if (empty ( $parent_page )) {
								
							//errror
							} else {
								
								$to_save ['content_parent'] = $parent_page ['id'];
								
								$to_save = CI::model('content')->saveContent ( $to_save );
								
							//var_dump($to_save);
							}
						
						}
					
					}
					
				//var_dump ( $_POST );
				//exit ();
				

				}
				
				$this->load->vars ( $this->template );
				
				$user_session ['user_action'] = $user_action;
				
				$content ['content_filename'] = 'users/post.php';
				
				break;
			
			case 'exit' :
				
				CI::library('session')->unset_userdata ( 'user_session' );
				CI::library('session')->unset_userdata ( 'user' );
				CI::library('session')->unset_userdata ( 'the_user' );
				
				redirect ( 'users' );
				
				break;
			case 'forgotten_pass' :
				$this->template ['error'] = false;
				$this->template ['ok'] = false;
				$opt = array ();
				if (getenv ( "REQUEST_METHOD" ) == 'POST') {
					$email = trim ( $_POST ['email'] );
					$q = "SELECT username,password,email FROM $table WHERE email='$email'";
					$res = CI::model('core')->dbQuery ( $q );
					if (empty ( $res )) {
						$this->template ['error'] = "No found email '$email'";
					} else {
						$this->template ['ok'] = "Password was send to email '$email'";
						
						$opt ['email'] = $email;
						$opt ['username'] = $res [0] ['username'];
						$opt ['password'] = $res [0] ['password'];
						$opt ['object'] = 'Forgotten Password';
						$opt ['site'] = site_url ();
						;
						
						CI::model('users')->sendMail ( $opt );
					}
				}
				$content ['content_filename'] = 'users/forgotten_pass.php';
				break;
			
			default :
				
				$user_content = array ();
				
				$user_content ['content_type'] = 'post';
				
				$user_content ['created_by'] = $user_session ['user_id'];
				
				$user_content = CI::model('content')->getContent ( $user_content, $orderby = array ('updated_on', 'DESC' ), $limit = false, $count_only = false );
				
				//var_dump ( $user_content );
				$this->template ['user_content'] = $user_content;
				
				$user_session ['user_action'] = $user_action;
				
				$this->load->vars ( $this->template );
				
				$content ['content_filename'] = 'users/default.php';
				
				break;
		
		}
		
		//if(!empty($user_session)){
		$user_session ['user_action'] = $user_action;
		
		$this->template ['user_action'] = $user_action;
		
		$this->load->vars ( $this->template );
		
		CI::library('session')->set_userdata ( 'user_session', $user_session );
		
		//}
		

		if (is_dir ( $the_active_site_template_dir ) == false) {
			
			header ( "HTTP/1.1 500 Internal Server Error" );
			
			show_error ( 'No such template: ' . $the_active_site_template );
			
			exit ();
		
		}
		
		//var_dump ( $the_active_site_template_dir . $content ['content_filename'] );
		

		//exit ();
		

		if (trim ( $content ['content_filename'] ) != '') {
			
			if (is_readable ( $the_active_site_template_dir . $content ['content_filename'] ) == true) {
				
				$this->load->vars ( $this->template );
				
				$content_filename_pre = $this->load->file ( $the_active_site_template_dir . $content ['content_filename'], true );
				
				$this->load->vars ( $this->template );
			
			} else {
				
				header ( "HTTP/1.1 500 Internal Server Error" );
				
				show_error ( "File {$content ['content_filename']} is not readable or doesn't exist in the templates directory!" );
				
				exit ();
			
			}
		
		}
		
		if (is_readable ( $the_active_site_template_dir . 'users/layout.php' ) == true) {
			
			$this->load->vars ( $this->template );
			
			$layout = $this->load->file ( $the_active_site_template_dir . 'users/layout.php', true );
		
		} else {
			
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
		

		CI::library('output')->set_output ( $layout );
	
	}
	
	function login() {
		$back_to = CI::model('core')->getParamFromURL ( 'back_to' );
		
		global $cms_db_tables;
		$table = $cms_db_tables ['table_users'];
		
		$username = $this->input->post ( 'username', TRUE );
		
		$password = $this->input->post ( 'password', TRUE );
		
		$user_action = $this->input->post ( 'user_action', TRUE );
		
		if ($user_action == 'register') {
			
			$q = "select username from " . $table . " where username='$username' ";
			
			$query = CI::db()->query ( $q );
			
			$query = $query->row_array ();
			
			$query = (array_values ( $query ));
			
			$username = $query [0];
			
			if ($username != '') {
				
				$this->template ['message'] = 'This user exists, try another one!';
			
			} else {
				
				$data = array ('updated_on' => date ( "Y-m-d h:i:s" ), 'is_active' => 0, 'username' => $username, 'password' => $password );
				
				$this->db->insert ( $table, $data );
				
				$this->template ['message'] = 'Success, try login now!';
			
			}
		
		} else {
			
			$q = "select * from " . $table . " where username='$username' and password='$password' and is_active=1";
			
			$query = CI::db()->query ( $q );
			
			$query = $query->row_array ();
			
			if (empty ( $query )) {
				
				$this->template ['message'] = 'Wrong username or password, or user is disabled!';
			
			} else {
				
				CI::library('session')->set_userdata ( 'user', $query );
				if ($back_to == false) {
					redirect ( 'index' );
				} else {
					redirect ( base64_decode ( $back_to ) );
				
				}
			
			}
		
		}
		
		$this->load->vars ( $this->template );
		
		$layout = CI::view ( 'layout', true, true );
		
		$primarycontent = '';
		
		$secondarycontent = '';
		
		$primarycontent = CI::view ( 'login', true, true );
		
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		
		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );
		
	//CI::view('welcome_message');
	

	//CI::library('output')->set_output ( $layout );
	}

	
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */