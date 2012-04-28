<?php
/**
 * CodeIgniter Facebook Connect Graph API Login Controller 
 * 
 * Author: Graham McCarthy (graham@hitsend.ca) HitSend inc. (http://hitsend.ca)
 * 
 * VERSION: 1.0 (2010-09-30)
 * LICENSE: GNU GENERAL PUBLIC LICENSE - Version 2, June 1991
 * 
 **/
class Fb_login extends CI_Controller {
	function __construct() {
		parent :: __construct();
		require_once (APPPATH . 'controllers/default_constructor.php');
	}
	
	function index() {
		//create blank data array to return
		$data = array ();
		session_start ();
		unset ( $_SESSION ['facebook'] );
		$this->load->library ( 'fb_connect' );
		$data = array ('facebook' => $this->fb_connect->fb, 'fbSession' => $this->fb_connect->fbSession, 'user' => $this->fb_connect->user, 'uid' => $this->fb_connect->user_id, 'fbLogoutURL' => $this->fb_connect->fbLogoutURL, 'fbLoginURL' => $this->fb_connect->fbLoginURL, 'base_url' => site_url ( 'fb_login' ), 'appkey' => $this->fb_connect->appkey );
		
		//p($data);
		

		if ($data ['user']) {
			
			if ($data ['user'] ["id"]) {
				
				/*if(stristr($k, 'fbs_')){
					//var_dump($v);
					//setcookie( $k); 
				}*/
				
				$userdata_check = array ();
				$userdata_check ['fb_uid'] = $data ['user'] ["id"];
				
				$userdata_check = $this->users_model->getUsers ( $userdata_check, $limit = false, $count_only = false );
				
				if ($userdata_check == false) {
					//p($data['user']);
					$to_save = array ();
					$to_save ['username'] = $data ['user'] ["first_name"] . $data ['user'] ["last_name"] . $data ['user'] ["id"];
					$to_save ['password'] = rand () . rand () . rand ();
					$to_save ['email'] = $data ['user'] ["email"];
					$to_save ['is_active'] = 'y';
					$to_save ['first_name'] = $data ['user'] ["first_name"];
					$to_save ['last_name'] = $data ['user'] ["last_name"];
					$to_save ['is_admin'] = 'n';
					$to_save ['fb_uid'] = $data ['user'] ["id"];
					
					$userdata_check = $this->users_model->saveUser ( $to_save );
					$userdata_check = $to_save;
				} else {
					$userdata_check = $userdata_check [0];
				}
				
				if (! empty ( $userdata_check )) {
					//$test = $this->validate_user_facebook($userdata_check ['fb_uid']);
					//var_Dump($test);
					CI::library('session')->set_userdata ( 'the_user', $userdata_check );
					$user_session = array ();
					$user_session ['is_logged'] = 'yes';
					$user_session ['user_id'] = $userdata_check ['id'];
					CI::library('session')->set_userdata ( 'user_session', $user_session );
				
				}
				
				CI::view ( 'fb_login_done', $data );
				//redirect ( site_url ('dashboard') );
			//p ( $userdata_check );
			/*CI::library('session')->set_userdata ( 'the_user', $userdata_check );
				$user_session = array ();
				$user_session ['is_logged'] = 'yes';
				$user_session ['user_id'] = $userdata_check ['id'];
				CI::library('session')->set_userdata ( 'user_session', $userdata_check );*/
			
			//
			}
		
		} else {
			/*$this->template ['data'] = $data;
			// $this->load->vars ( $this->template );
			$content_filename = $this->load->file ( DEFAULT_TEMPLATE_DIR . 'blocks/users/fb_login.php', true );
			print ($content_filename) ;
			exit ();*/
			/*	p ( $data );
			CI::library('session')->set_userdata ( 'the_user', $userdata_check );
			CI::library('session')->set_userdata ( 'user_session', $user_session );*/
			
			//CI::library('session')->unset_userdata('the_user');
			//	CI::library('session')->unset_userdata('user_session');
			

			CI::view ( 'fb_login', $data );
		
		}
	}
	
	//This won't destroy your facebook session
	function logout() {
		
		$this->load->library ( 'session' );
		
		CI::library('session')->sess_destroy ();
		//var_dump($_COOKIE);
		if (! empty ( $_COOKIE )) {
			foreach ( $_COOKIE as $k => $v ) {
				//setcookie ( $k );
				if (stristr ( $k, 'fbs_' )) {
					//var_dump($v);
				//setcookie( $k); 
				}
			}
		}
		//remove all the variables in the session 
		session_unset ();
		
		session_destroy ();
		$data ['logged_out'] = TRUE;
		//CI::view ( 'fb_login', $data );
	} // function logout()
	

	function _facebook_validate($uid = 0) {
		//this query basically sees if the users facebook user id is associated with a user.
		$bQry = $this->users_model->validate_user_facebook ( $uid );
		
		if ($bQry) { // if the user's credentials validated...
			$data = array ('user_id' => $uid, 'is_logged_in' => true, 'list_type' => 'hot' );
			
			CI::library('session')->set_userdata ( $data );
			
			$uri_var = $this->uri->segment ( 3 );
			
			if (strlen ( $uri_var ) > 0) {
				$url_location = $uri_var;
				$url_location = str_replace ( '-', '/', $url_location );
				redirect ( $url_location );
			} else {
				redirect ( '/message/index' );
			}
		
		} else {
			// incorrect username or password
			$data = array ();
			$data ["login_failed"] = TRUE;
			$this->index ( $data );
		}
	}
	function validate_user_facebook($uid = 0) {
		//confirm that facebook session data is still valid and matches  
		$this->load->library ( 'fb_connect' );
		
		//see if the facebook session is valid and the user id in the sesison is equal to the user_id you want to validate  
		$session_uid = 'fb:' . $this->fb_connect->fbSession ['uid'];
		if (! $this->fb_connect->fbSession || $session_uid != $uid) {
			return false;
		}
	}
	
	function facebook() {
		//1. Check to see if the facebook session has been declared
		$this->load->library ( 'fb_connect' );
		
		if (! $this->fb_connect->fbSession) {
			//2. If No, bounce back to login
			$this->index ();
		} else {
			
			$fb_uid = $this->fb_connect->user_id;
			$fb_usr = $this->fb_connect->user;
			
			if ($fb_uid != false) {
				//3. If yes, see if the facebook id is associated with any existing account
				$usr = $this->users_model->get_user_by_fb_uid ( $fb_uid );
				
				if (is_array ( $usr ) && count ( $usr ) == 1) {
					$usr = $usr [0]; //the model returns an object array, so get the first elemet of it which contains all of the data we need.
					//3.a. if yes, log the person in
					//echo "Logging in via facebook...";
					$this->_facebook_validate ( $usr->user_id );
				} else {
					//3.b. if no, register the new user.
					//echo "Creating a new account...";
					$fname = $fb_usr ["first_name"];
					$lname = $fb_usr ["last_name"];
					$fullname = $fb_usr ["name"];
					$pwd = ''; //left blank so user can modify this later
					$email = $fb_usr ["email"];
					
					$db_values = array ('user_id' => "fb:" . $fb_uid, 'fb_uid' => "fb:" . $fb_uid, 'full_name' => $fullname, 'pwd' => "" );
					
					//data ready, try to create the new user 
					if ($query = $this->users_model->create_user ( $db_values )) {
						$data ['account_created'] = true;
						//log user in
						$this->_facebook_validate ( $db_values ["user_id"] );
					} else {
						//Did not work, go back to login page
						$this->index ();
					}
				}
			}
		}
	}
}