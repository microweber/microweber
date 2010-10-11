<?php
class Sql extends Controller {
	
	function __construct() {
		parent::Controller ();
		require_once (APPPATH . 'controllers/default_constructor.php');
	}
	function dummy_posts() {
		
		header ( 'Content-type: text/plain; charset=utf-8' );
		$to_save = $_POST;
		$i = 0;
		
		for($i = 0; $i < 10000; $i ++) {
			
			//print $sentence . "\n\n";
			//print $vic . "\n\n";
			

			$to_save ['content_body'] = 'Lorem ipsum  lementum. Duis ligula lacus, eleifend rutrum tincidunt accumsan, adipiscing at purus. Morbi nec urna sit amet augue consequat rhoncus eu id odio. Nulla vel commodo lacus. Quisque sodales accumsan urna sit amet congue. Vivamus ut posuere magna. Curabitur faucibus enim id ante sollicitudin varius luctus felis tristique. Fusce leo sapien, fringilla ac porta vel, volutpat ac orci. Ut pharetra dictum augue, vel ornare massa mattis sit amet. Curabitur lacus tortor, tristique et semper sit amet, consectetur vel nisi.';
			
			global $cms_db_tables;
			$table = $cms_db_tables ['table_taxonomy'];
			$q = " select * from $table where taxonomy_type = 'category'     order by RAND() limit 0,5   ";
			$q = $this->core_model->dbQuery ( $q );
			
			$to_save ['content_title'] = 'random post in ' . $q [0] ['taxonomy_value'] . ' on ' . date ( "Y-m-d H:i:s" );
			
			$table_users = $cms_db_tables ['table_users'];
			$q_users = " select id from $table_users     order by RAND() limit 0,10   ";
			$q_users = $this->core_model->dbQuery ( $q_users );
			$to_save ['created_by'] = $q_users [0] ['id'];
			$to_save ['edited_by'] = $q_users [0] ['id'];
			
			$errors = array ();
			
			
			
			$categories =  ($this->core_model->dbExtractIdsFromArray($q) );
			
			if (! empty ( $categories )) {
				
				foreach ( $categories as $cat ) {
					$parrent_cats = $this->taxonomy_model->taxonomyGetParentItemsAndReturnOnlyIds ( $cat );
					
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
				$check_title = $this->content_model->getContent ( $check_title, $orderby = false, $limit = false, $count_only = false );
				if (! empty ( $check_title )) {
					$check_title_error = true;
				}*/
				
				if ($check_title_error == true) {
					print 'title exist' . $to_save ['content_title'];
					//errror
				} else {
					
					$taxonomy_categories = array ($category );
					$taxonomy = $this->taxonomy_model->taxonomyGetParentItemsAndReturnOnlyIds ( $category );
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
						if($parent_page == false){
						$parent_page = $this->content_model->contentsGetTheFirstBlogSectionForCategory ( $cat );
						//vAR_dump($parent_page);
						}
					}
					
					if (! empty ( $categories )) {
						if (empty ( $parent_page )) {
						}
					}
					if (empty ( $errors )) {
						if($parent_page != false){
						$to_save ['content_parent'] = $parent_page ['id'];
						$to_save ['is_home'] = 'n';
						$to_save ['preserve_cache'] = true;
						$to_save ['content_type'] = 'post';
						//var_dump ( $to_save );
						print $i . " - ". $to_save ['content_title']. "\n";
						
						 $saved = $this->content_model->saveContent ( $to_save );
						}
					

					} else {
						var_dump ( $errors );
					}
				
				}
			
			}
		
		}
	
	}
	
	function index() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		/*
		$this->load->vars ( $this->template );
		$layout = $this->load->view ( 'layout', true, true );
		$primarycontent = $this->load->view ( 'me/index', true, true );
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		$this->output->set_output ( $layout );
		*/
		
		$sql = <<<STR
	select u.id, u.username from firecms_users u
    	
STR;
		$users = $this->core_model->dbQuery ( $sql );
		
		for($i = 0; $i < count ( $users ); $i ++) {
			$sql_update = "UPDATE affiliate_users set parent_affil='{$users[$i]['username']}' WHERE parent_affil_id={$users[$i]['id']} ";
			$this->core_model->dbQ ( $sql_update );
		}
		
		$sql = <<<STR
	select u.id, u.parent_affil_id,u.parent_affil from affiliate_users u
    	
STR;
		$users = $this->core_model->dbQuery ( $sql );
		p ( $users );
		
		die ();
		
		$sql = <<<STR
	select c1.username, c1.tier from cosmic_affiliates c1
    	
STR;
		$tiers = $this->core_model->dbQuery ( $sql );
		$ids = array ();
		for($i = 0; $i < count ( $tiers ); $i ++) {
			$res = array ();
			$uname = htmlentities ( $tiers [$i] ['tier'], ENT_QUOTES );
			$sql_update = "select id from firecms_users WHERE username='$uname' ";
			
			$res = $this->core_model->dbQuery ( $sql_update );
			
			if (! empty ( $res ))
				$ids [$tiers [$i] ['username']] = $res [0] ['id'];
		
		}
		
		$sql_update = '';
		foreach ( $ids as $kol => $val ) {
			$uname = htmlentities ( $kol, ENT_QUOTES );
			$sql_update = "UPDATE firecms_users set parent_affil='{$val}' WHERE username='$uname' ";
			
			$this->core_model->dbQ ( $sql_update );
		
		}
		$sql = 'select id,username, parent_affil from firecms_users';
		$res = $this->core_model->dbQuery ( $sql );
		p ( $res );
	}
}

?>