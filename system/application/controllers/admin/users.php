<?php

class Users extends Controller {
	
	function __construct() {
		
		parent::Controller ();
		
		require_once (APPPATH . 'controllers/default_constructor.php');
		require_once (APPPATH . 'controllers/admin/default_constructor.php');
	
	}
	
	function delete() {
		
		$id = CI::model('core')->getParamFromURL ( 'id' );
		CI::model('users')->userDeleteById ( $id );
		redirect ( 'admin/users/index' );
	
	}
	
	function users_do_search() {
		if ($_POST ['search_by_keyword']) {
			$togo_tags = "/keyword:{$_POST ['search_by_keyword']}";
			$gogo = site_url ( 'admin/users/index' ) . $togo_tags;
			$gogo = reduce_double_slashes ( $gogo );
			header ( "Location: $gogo " );
			exit ();
		} else {
			$togo_tags = false;
			$tags = CI::model('core')->getParamFromURL ( 'keyword' );
			$togo_tags = "/keyword:{$tags}";
			$gogo = site_url ( 'admin/users/index' ) . $togo_tags;
			$gogo = reduce_double_slashes ( $gogo );
			header ( "Location: $gogo " );
			exit ();
		}
	
	}
	
	function index() {
		
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		
		if (CI::library('session')->userdata ( 'user' ) == false) {
			
		//redirect ( 'index' );
		

		}
		
		if (array_key_exists ( 'savedata', $_POST )) {
			
			CI::model('users')->saveUser ( $_POST );
		
		}
		$tags = CI::model('core')->getParamFromURL ( 'keyword' );
		
		$criteria = array ();
		$this->template ['search_by_keyword'] = '';
		if ($tags) {
			$criteria ['search_by_keyword'] = $tags;
			$this->template ['search_by_keyword'] = $tags;
		}
		
		$results_count = CI::model('users')->getUsers ( $criteria, false, true );
		
		$items_per_page = CI::model('core')->optionsGetByKey ( 'admin_default_items_per_page' );
		
		$content_pages_count = ceil ( $results_count / $items_per_page );
		
		$curent_page = CI::model('core')->getParamFromURL ( 'curent_page' );
		if (intval ( $curent_page ) < 1 || intval ( $curent_page ) > $content_pages_count) {
			$curent_page = 1;
		}
		
		$page_start = ($curent_page - 1) * $items_per_page;
		$page_end = ($page_start) + $items_per_page;
		
		$users = CI::model('users')->getUsers ( $criteria, array ($page_start, $page_end ), false );
		
		$this->template ['content_pages_count'] = $content_pages_count;
		//var_dump($content_pages_count);
		$this->template ['content_pages_curent_page'] = $curent_page;
		
		//get paging urls
		$content_pages = CI::model('content')->pagingPrepareUrls ( false, $content_pages_count );
		$this->template ['content_pages_links'] = $content_pages;
		
		$this->template ['users'] = $users;
		
		$this->load->vars ( $this->template );
		
		$layout = CI::view ( 'admin/layout', true, true );
		
		$primarycontent = '';
		
		$secondarycontent = '';
		
		$primarycontent = CI::view ( 'admin/users/index', true, true );
		
		$secondarycontent = CI::view ( 'admin/users/sidebar', true, true );
		
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		
		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );
		
		//CI::view('welcome_message');
		

		CI::library('output')->set_output ( $layout );
	
	}
	
	function email_users() {
		$this->template ['form_values'] = $users;
		
		$this->load->vars ( $this->template );
		
		$layout = CI::view ( 'admin/layout', true, true );
		
		$primarycontent = '';
		
		$secondarycontent = '';
		
		$primarycontent = CI::view ( 'admin/users/email_users', true, true );
		
		$secondarycontent = '';
		
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		
		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );
		
		CI::library('output')->set_output ( $layout );
	
	}
	
	function email_to() {
		
		$id = $_POST ['id'];
		$id = intval ( $id );
		if ($id == 0) {
			exit ( 'error id is 0' );
		} else {
			
			$test = $_POST ['email'];
			
			$user = get_user ( $id );
			
			$subj = $_POST ['subject'];
			$msg = $_POST ['message'];
			$from = $_POST ['from'];
			foreach ( $user as $k => $v ) {
				$subj = str_ireplace ( '{' . $k . '}', $v, $subj );
				$msg = str_ireplace ( '{' . $k . '}', $v, $msg );
			}
			
			if ($test != false) {
				$m = mail ( $test, $subj, $msg, "From: $from\nReply-To: $from\nContent-Type: text/html;charset=\"utf-8\"\nContent-Transfer-Encoding: 8bit" );
			
			} else {
				$m = mail ( $user ['email'], $subj, $msg, "From: $from\nReply-To: $from\nContent-Type: text/html;charset=\"utf-8\"\nContent-Transfer-Encoding: 8bit" );
			
			}
			//print  $user ['email'] . $subj . $msg .  "From: $from\nReply-To: $from\nContent-Type: text/html;charset=\"utf-8\"\nContent-Transfer-Encoding: 8bit";
			//$m = mail ( $user ['email'], $subj, $msg, "From: $from\nReply-To: $from\nContent-Type: text/html;charset=\"utf-8\"\nContent-Transfer-Encoding: 8bit" );
			print $m;
		}
	}
	
	function edit() {
		
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		
		//if (CI::library('session')->userdata ( 'user' ) == false) {
		

		//redirect ( 'index' );
		

		//}
		

		if ($_POST) {
			
			CI::model('users')->saveUser ( $_POST );
			$gogo = site_url ( 'admin/users/index' );
			$gogo = reduce_double_slashes ( $gogo );
			header ( "Location: $gogo " );
			exit ();
		
		}
		$tags = CI::model('core')->getParamFromURL ( 'id' );
		if (intval ( $tags ) != 0) {
			$criteria = array ();
			//$this->template ['search_by_keyword'] = '';
			if ($tags) {
				$criteria ['id'] = $tags;
				//$this->template ['search_by_keyword'] = $tags;
			}
			
			$users = CI::model('users')->getUsers ( $criteria, false, false );
			$users = $users [0];
		} else {
			$userz = CI::model('core')->dbGetTableFields ( TABLE_PREFIX . 'users' );
			foreach ( $userz as $item ) {
				$users [$item] = '';
			}
		}
		//p($users);
		

		$this->template ['form_values'] = $users;
		
		$this->load->vars ( $this->template );
		
		$layout = CI::view ( 'admin/layout', true, true );
		
		$primarycontent = '';
		
		$secondarycontent = '';
		
		$primarycontent = CI::view ( 'admin/users/edit', true, true );
		
		$secondarycontent = '';
		
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		
		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );
		
		//CI::view('welcome_message');
		

		CI::library('output')->set_output ( $layout );
	
	}

}

?>