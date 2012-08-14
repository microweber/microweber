<?php
// Controller Class
class Controller {
	function index() {
		$page_url = url_string ();
		$page_url = rtrim ( $page_url, '/' );
		
		// var_dump ( $page_url );
		
		if (trim ( $page_url ) == '') {
			//
			$page = get_homepage ();
		} else {
			
			$page = get_page_by_url ( $page_url );
			
			if (empty ( $page )) {
				$page = get_homepage ();
			}
		}
		// d($page);
		if ($page ['content_type'] == "post") {
			$content = $page;
			$page = get_content_by_id ( $page ['content_parent'] );
		} else {
			$content = $page;
		}
		
		define_constants ( $content );
		
		$render_file = get_layout_for_page ( $page );
		
		// d ( $render_file );
		
		if ($render_file) {
			$l = new View ( $render_file );
			// $this->content = $content;
			// var_dump($l);
			
			// $l->set ( $this );
			$l = $l->__toString ();
			// var_dump($l);
			$layout = parse_micrwober_tags ( $l, $options = false );
			print $layout;
			exit ();
		} else {
			print 'NO LAYOUT IN ' . __FILE__;
			d ( $template_view );
			d ( $page );
			exit ();
		}
		// var_dump ( $page );
		// var_dump($ab);
	}
	function show_404() {
		header ( "HTTP/1.0 404 Not Found" );
		$v = new View ( ADMIN_VIEWS_PATH . '404.php' );
		echo $v;
	}
	function admin() {
		define_constants();
		$l = new View ( ADMIN_VIEWS_PATH . 'admin.php' );
		$l = $l->__toString ();
		// var_dump($l);
		$layout = parse_micrwober_tags ( $l, $options = false );
		print $layout;
		exit ();
	}
}
