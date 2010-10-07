<?php

$content_display_mode = false;

if (defined ( 'INTERNAL_API_CALL' ) == true) {
	$microweber_api = $this;
	$CI = get_instance ();
	return $CI;

} else {
	
	//require (APPPATH . 'controllers/advanced/users/force_profile_complete.php');
	

	$subdomain_user = $this->session->userdata ( 'subdomain_user' );
	$this->template ['subdomain_user'] = $subdomain_user;
	
	if ($content_display_mode != 'extended_api_with_no_template') {
	
	}
	
	$site_cache_time = 60 * 60 * 60;
	$url = $this->uri->uri_string ();
	
	$url = str_ireplace ( '\\', '', $url );
	$cache_content = false;
	$whole_site_cache_id = 'url_' . md5 ( $url );
	if (! $_POST) {
		//$cache_content = $this->core_model->cacheGetContentAndDecode ( $whole_site_cache_id, $site_cache_time );
	}
	
	$cache_content = false;
	
	if (($cache_content) != false) {
		//$layout = $cache_content;
	//$this->output->set_output ( $layout );
	} else {
		$slash = substr ( "$url", 0, 1 );
		if ($slash == '/') {
			$url = substr ( "$url", 1, strlen ( $url ) );
		}
	//	var_dump($url);
		if (trim ( $url ) == '') {
		//	var_dump($url);
			$page = $this->content_model->getContentHomepage ();
		
		} else {
			$post_maybe = $this->content_model->getContentByURLAndCache ( $url );
			if (intval ( $post_maybe ['id'] ) != 0) {
				$post_maybe = $this->content_model->contentGetByIdAndCache ( $post_maybe ['id'] );
			}
			$page = $this->content_model->getPageByURLAndCache ( $url );
			
			if (empty ( $page )) {
				return false;
			
	//exit ( '404: Nothing found on line ' . __LINE__ );
			}
		}
		//p($post);
		//	p($page);
		

		if ($post_maybe ['content_type'] == 'post') {
			$post = $post_maybe;
		}
		
		if ($page ['content_type'] == 'post') {
			require (APPPATH . 'controllers/advanced/index/display_post_as_page.php');
		}
		
		if ($page ['content_type'] == 'page') {
			require (APPPATH . 'controllers/advanced/index/display_page.php');
		}
		
		//	p ( $post ,1 );
		//print '</pre>';
		

		$global_template_replaceables = array ();
		$global_template_replaceables ["content_meta_title"] = $content ['content_title'];
		$global_template_replaceables ["content_meta_title"] = ($content ['content_meta_title'] != '') ? $content ['content_meta_title'] : $this->content_model->optionsGetByKey ( 'content_meta_title' );
		$global_template_replaceables ["content_meta_description"] = ($content ['content_meta_description'] != '') ? $content ['content_meta_description'] : $this->content_model->optionsGetByKey ( 'content_meta_description' );
		$global_template_replaceables ["content_meta_keywords"] = ($content ['content_meta_keywords'] != '') ? $content ['content_meta_keywords'] : $this->content_model->optionsGetByKey ( 'content_meta_keywords' );
		$global_template_replaceables ["content_meta_other_code"] = ($content ['content_meta_other_code'] != '') ? $content ['content_meta_other_code'] : $this->content_model->optionsGetByKey ( 'content_meta_other_code' );
		$global_template_replaceables ["content_meta_other_code"] = htmlspecialchars_decode ( $global_template_replaceables ["content_meta_other_code"], ENT_QUOTES );
		$global_template_replaceables ["content_meta_other_code"] = html_entity_decode ( $global_template_replaceables ["content_meta_other_code"] );
		
		$the_active_site_template_dir = TEMPLATES_DIR;
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
					$parent_pages = $this->content_model->getParentPagesIdsForPageIdAndCache ( $content ['id'] );
					if (! empty ( $parent_pages )) {
						foreach ( $parent_pages as $parent_page ) {
							if ($use_the_parent_page_layout == false) {
								$parent_page_info = $this->content_model->contentGetByIdAndCache ( $parent_page );
								if (strval ( $parent_page_info ['content_layout_file'] ) != '') {
									if (is_readable ( $the_active_site_template_dir . $parent_page_info ['content_layout_file'] ) == true) {
										$use_the_parent_page_layout = $parent_page_info ['content_layout_file'];
									}
								}
							}
						}
					}
					
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
		
		if ($content ['content_layout_name'] != '') {
			$this->template ['content'] = $content;
			
			$layout_dir = TEMPLATES_DIR . 'layouts/' . $content ['content_layout_name'] . '/';
			$this->template ['layout_dir'] = $layout_dir;
			$this->template ['layout_url'] = reduce_double_slashes ( dirToURL ( $layout_dir ) . '/' );
			$this->load->vars ( $this->template );
			$layout = TEMPLATES_DIR . 'layouts/' . $content ['content_layout_name'] . '/_view.php';
			
			$layout = $this->load->file ( $layout, true );
		
	//
		}
		//	p($page);
		

		//if ($content ['content_layout_file'] == '') {
		

		//}
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
		
		if ($content ['content_body_filename'] == false) {
			if ($post ['content_body_filename'] != false) {
				$content ['content_body_filename'] = $post ['content_body_filename'];
			}
		}
		//var_dump($post ['content_body_filename']);
		if ($content ['content_body_filename'] != false) {
			if (trim ( $content ['content_body_filename'] ) != '') {
				//$the_active_site_template12 = $this->content_model->optionsGetByKey ( 'curent_template' );
				//$the_active_site_template_dir1 = TEMPLATEFILES . $the_active_site_template12 . '/content_files/';
				

				$the_active_site_template_dir1 = TEMPLATE_DIR;
				
				if (is_file ( $the_active_site_template_dir1 . $content ['content_body_filename'] ) == true) {
					{
						//$v1 = file_get_contents ( $the_active_site_template_dir . $content ['content_body_filename'] );
						//$v1 = html_entity_decode ( $v1 );
						$this->load->vars ( $this->template );
						$content_filename1 = $this->load->file ( $the_active_site_template_dir1 . $content ['content_body_filename'], true );
						
						//print($content ['content_body']);
						$layout = str_ireplace ( '{content}', $content_filename1, $layout );
						$layout = str_ireplace ( '{content_body_filename}', $content_filename1, $layout );
					
	//$v = htmlspecialchars_decode ( $v );
					}
				}
			
			}
		
		} else {
			
			if (trim ( $content ['content_body'] ) != '') {
				
				$this->load->vars ( $this->template );
				
				//print($content ['content_body']);
				$layout = str_ireplace ( '{content}', $content ['the_content_body'], $layout );
			
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
		
		$content = str_ireplace ( '{content}', '', $content );
		//var_dump($global_template_replaceables);
		//
		

		//	p(array_size($this->core_model->cache_storage));
		

		$layout = $this->content_model->applyGlobalTemplateReplaceables ( $layout, $global_template_replaceables );
		
		//$layout = $this->content_model->applyGlobalTemplateReplaceables ( $layout, $global_template_replaceables );
		//	var_dump ( $taxonomy_tree );
		$layout = $this->template_model->replaceTemplateTags ( $layout );
		//var_dump ( $taxonomy_tree );
		$opts = array ();
		$opts ['no_microwber_tags'] = true;
		$opts ['no_remove_div'] = true;
		//p($layout);
		//	$layout = $this->template_model->parseMicrwoberTags ( $layout, $opts );
		if ($content_display_mode == 'extended_api_with_no_template') {
			
			$the_user = $this->session->userdata ( 'the_user' );
			$api_data = $the_user;
			
			$CI = get_instance ();
			return $CI;
		
		} else {
			//$this->core_model->cacheWriteAndEncode ( $layout, $whole_site_cache_id, $cache_group = 'global' );
			

			//p($this->core_model->cache_storage_hits);
			//p($this->core_model->cache_storage_decoded);
			

			$this->output->set_output ( $layout );
		}
	
	//var_dump($_SERVER);
	

	}
}

?>