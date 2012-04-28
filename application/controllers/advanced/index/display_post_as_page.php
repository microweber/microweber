<?php

$content = $page;
$post = $page;
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
	
	/*if (trim ( $post ['page_301_redirect_to_post_id'] ) != '') {
		
		$gogo = $this->content_model->getContentURLByIdAndCache ( $post ['page_301_redirect_to_post_id'] );
		
		if ($this->core_model->validators_isUrl ( $gogo ) == true) {
			
			header ( 'Location: ' . $gogo );
			
			exit ();
		
		} else {
			
			exit ( "Trying to go to invalid url: $gogo" );
		
		}
	
	}*/
}

$this->template ['page'] = $page;
$this->template ['post'] = $post;
// $this->load->vars ( $this->template );

$GLOBALS ['ACTIVE_PAGE_ID'] = $content ['id'];

if (defined ( 'ACTIVE_PAGE_ID' ) == false) {
	
	define ( 'ACTIVE_PAGE_ID', $page ['id'] );

}

$the_active_site_template = $this->core_model->optionsGetByKey ( 'curent_template' );

$the_active_site_template_dir = TEMPLATEFILES . $the_active_site_template . '/';

$the_template_url = site_url ( 'userfiles/' . TEMPLATEFILES_DIRNAME . '/' . $the_active_site_template );

// $this->load->vars ( $this->template );

if ($page ['content_subtype'] == 'module') {
	
	$dirname = $page ['content_subtype_value'];
	
	if (is_dir ( PLUGINS_DIRNAME . $dirname )) {
		
		if (is_file ( PLUGINS_DIRNAME . $dirname . '/controller.php' )) {
			
			$this->core_model->plugins_setRunningPlugin ( $dirname );
			
			//$this->load->file ( PLUGINS_DIRNAME . $dirname . '/controller.php', true );
			include_once PLUGINS_DIRNAME . $dirname . '/controller.php';
		
		}
	
	}

}

if (! empty ( $post )) {
	$meta = $this->content_model->metaTagsGenerateByContentId ( $post ['id'] );
	$content ['content_meta_title'] = $meta ['content_meta_title'];
	$content ['content_meta_description'] = $meta ['content_meta_description'];
	$content ['content_meta_keywords'] = $meta ['content_meta_keywords'];
}
?>