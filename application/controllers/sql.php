<?php
class Sql extends CI_Controller {
	
	function __construct() {
		parent :: __construct();
		require_once (APPPATH . 'controllers/default_constructor.php');
		//require_once (APPPATH . 'controllers/admin/default_constructor.php');
	}
	
	function dummy_posts() {
		
		header ( 'Content-type: text/html; charset=utf-8' );
		$to_save = $_POST;
		$i = 0;
		
		for($i = 0; $i < 100; $i ++) {
			
			//print $sentence . "\n\n";
			//print $vic . "\n\n";
			

			$to_save ['content_body'] = 'Lorem ipsum  lementum. Duis ligula lacus, eleifend rutrum tincidunt accumsan, adipiscing at purus. Morbi nec urna sit amet augue consequat rhoncus eu id odio. Nulla vel commodo lacus. Quisque sodales accumsan urna sit amet congue. Vivamus ut posuere magna. Curabitur faucibus enim id ante sollicitudin varius luctus felis tristique. Fusce leo sapien, fringilla ac porta vel, volutpat ac orci. Ut pharetra dictum augue, vel ornare massa mattis sit amet. Curabitur lacus tortor, tristique et semper sit amet, consectetur vel nisi.';
			
			global $cms_db_tables;
			$table = $cms_db_tables ['table_taxonomy'];
			$q = " select * from $table where taxonomy_type = 'category'     order by RAND() limit 0,5   ";
			$q = CI::model('core')->dbQuery ( $q );
			
			$to_save ['content_title'] = 'random post in ' . $q [0] ['taxonomy_value'] . ' on ' . date ( "Y-m-d H:i:s" );
			
			$table_users = $cms_db_tables ['table_users'];
			$q_users = " select id from $table_users     order by RAND() limit 0,10   ";
			$q_users = CI::model('core')->dbQuery ( $q_users );
			$to_save ['created_by'] = $q_users [0] ['id'];
			$to_save ['edited_by'] = $q_users [0] ['id'];
			
			$errors = array ();
			
			$categories = (CI::model('core')->dbExtractIdsFromArray ( $q ));
			
			if (! empty ( $categories )) {
				
				foreach ( $categories as $cat ) {
					$parrent_cats = CI::model('taxonomy')->getParents ( $cat );
					
					foreach ( $parrent_cats as $par_cat ) {
						$categories [] = $par_cat;
					}
				
				}
				$categories = array_unique ( $categories );
			}
			
			if ((! empty ( $categories_ids_to_remove )) and (in_array ( $category, $categories_ids_to_remove ) == true)) {
				exit ( 'WOW invalid category! How this can be?' );
				//error
			} else {
				$check_title = array ();
				if (trim ( strval ( $to_save ['content_title'] ) ) == '') {
					$errors ['content_title'] = "Please enter title";
				}
				
				/*$check_title ['content_title'] = $to_save ['content_title'];
				$check_title ['content_type'] = 'post';
				$check_title = CI::model('content')->getContent ( $check_title, $orderby = false, $limit = false, $count_only = false );
				if (! empty ( $check_title )) {
					$check_title_error = true;
				}*/
				
				if ($check_title_error == true) {
					print 'title exist' . $to_save ['content_title'];
					//errror
				} else {
					
					$taxonomy_categories = array ($category );
					$taxonomy = CI::model('taxonomy')->getParents ( $category );
					if (! empty ( $taxonomy )) {
						foreach ( $taxonomy as $i ) {
							$taxonomy_categories [] = $i;
						}
					}
					
					$to_save ['content_type'] = 'post';
					
					if (empty ( $categories )) {
						$errors ['taxonomy_categories'] = "Please choose at least one category";
					}
					$categories = array_reverse ( $categories );
					$to_save ['taxonomy_categories'] = $categories;
					
					$parent_page = false;
					
					foreach ( $categories as $cat ) {
						//vAR_dump($parent_page);
						if ($parent_page == false) {
							$parent_page = CI::model('content')->contentsGetTheFirstBlogSectionForCategory ( $cat );
							//vAR_dump($parent_page);
						}
					}
					
					if (! empty ( $categories )) {
						if (empty ( $parent_page )) {
						}
					}
					if (empty ( $errors )) {
						if ($parent_page != false) {
							$to_save ['content_parent'] = $parent_page ['id'];
							$to_save ['is_home'] = 'n';
							$to_save ['preserve_cache'] = true;
							$to_save ['content_type'] = 'post';
							//var_dump ( $to_save );
							print $i . " - " . $to_save ['content_title'] . "\n";
							
							$saved = CI::model('content')->saveContent ( $to_save );
						}
					
					} else {
						var_dump ( $errors );
					}
				
				}
			
			}
		
		}
		print '<meta http-equiv="refresh" content="2">';
	
	}
	
	function dummy_comments() {
		
		header ( 'Content-type: text/html; charset=utf-8' );
		$to_save = $_POST;
		$i = 0;
		
		for($i = 0; $i < 10; $i ++) {
			
			//print $sentence . "\n\n";
			//print $vic . "\n\n";
			

			global $cms_db_tables;
			$table = $cms_db_tables ['table_taxonomy'];
			$table_content = $cms_db_tables ['table_content'];
			$q = " select id, content_title from $table_content where content_type = 'post'     order by RAND() limit 0,1   ";
			$q = CI::model('core')->dbQuery ( $q );
			
			$table_users = $cms_db_tables ['table_users'];
			$q_users = " select id from $table_users     order by RAND() limit 0,1   ";
			$q_users = CI::model('core')->dbQuery ( $q_users );
			
			$to_save = array ();
			$to_save ['created_by'] = $q_users [0] ['id'];
			$to_save ['edited_by'] = $q_users [0] ['id'];
			$to_save ['to_table'] = 'table_content';
			$to_save ['to_table_id'] = $q [0] ['id'];
			$to_save ['comment_body'] = 'Random comment for ' . $q [0] ['content_title'];
			CI::model('comments')->commentsSave ( $to_save );
			
			CI::model('votes')->votesCast ( 'table_content', $q [0] ['id'], $user_id = $q_users [0] ['id'] );
			
			var_Dump ( $to_save );
		
		}
		print '<meta http-equiv="refresh" content="2">';
	
	}
	
	function dummy_users() {
		
		header ( 'Content-type: text/html; charset=utf-8' );
		$to_save = $_POST;
		$i = 0;
		
		for($i = 0; $i < 10; $i ++) {
			
			$to_save = array ();
			
			$to_save ['username'] = 'user_' . rand ();
			$to_save ['first_name'] = 'first_name' . rand ();
			$to_save ['last_name'] = 'last_name' . rand ();
			$to_save ['password'] = '123456';
			$to_save ['email'] = 'user_' . rand () . '@email.com';
			$to_save ['is_active'] = 'y';
			$to_save ['is_admin'] = 'n';
			$to_save ['is_verified'] = 'y';
			CI::model('users')->saveUser ( $to_save );
			
			var_Dump ( $to_save );
		
		}
		print '<meta http-equiv="refresh" content="2">';
	
	}
	
	function index() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		/*
		$this->load->vars ( $this->template );
		$layout =$this->load->view ( 'layout', true, true );
		$primarycontent =$this->load->view ( 'me/index', true, true );
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		CI::library('output')->set_output ( $layout );
		*/
		
		$sql = <<<STR
	select u.id, u.username from firecms_users u
    	
STR;
		$users = CI::model('core')->dbQuery ( $sql );
		
		for($i = 0; $i < count ( $users ); $i ++) {
			$sql_update = "UPDATE affiliate_users set parent_affil='{$users[$i]['username']}' WHERE parent_affil_id={$users[$i]['id']} ";
			CI::model('core')->dbQ ( $sql_update );
		}
		
		$sql = <<<STR
	select u.id, u.parent_affil_id,u.parent_affil from affiliate_users u
    	
STR;
		$users = CI::model('core')->dbQuery ( $sql );
		p ( $users );
		
		die ();
		
		$sql = <<<STR
	select c1.username, c1.tier from cosmic_affiliates c1
    	
STR;
		$tiers = CI::model('core')->dbQuery ( $sql );
		$ids = array ();
		for($i = 0; $i < count ( $tiers ); $i ++) {
			$res = array ();
			$uname = htmlentities ( $tiers [$i] ['tier'], ENT_QUOTES );
			$sql_update = "select id from firecms_users WHERE username='$uname' ";
			
			$res = CI::model('core')->dbQuery ( $sql_update );
			
			if (! empty ( $res ))
				$ids [$tiers [$i] ['username']] = $res [0] ['id'];
		
		}
		
		$sql_update = '';
		foreach ( $ids as $kol => $val ) {
			$uname = htmlentities ( $kol, ENT_QUOTES );
			$sql_update = "UPDATE firecms_users set parent_affil='{$val}' WHERE username='$uname' ";
			
			CI::model('core')->dbQ ( $sql_update );
		
		}
		$sql = 'select id,username, parent_affil from firecms_users';
		$res = CI::model('core')->dbQuery ( $sql );
		p ( $res );
	}
}

?>