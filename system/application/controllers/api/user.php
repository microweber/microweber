<?php

class User extends Controller {
	
	function __construct() {
		
		parent::Controller ();
		
		require_once (APPPATH . 'controllers/default_constructor.php');
		//p($user_session);
		require_once (APPPATH . 'controllers/api/default_constructor.php');
	
	}
	
	function index() {
		require_once (APPPATH . 'controllers/api/_api_require_login.php');
		//var_dump ( $_COOKIE );
		exit ();
	}
	
	function delete_log_item() {
		require_once (APPPATH . 'controllers/api/_api_require_login.php');
		
		$id = $_POST ['id'];
		if (intval ( $id ) == 0) {
			exit ( "Invalid item" );
		}
		
		$log_item = CI::model ( 'notifications' )->logGetById ( $id );
		//p($log_item);
		if (empty ( $log_item )) {
			exit ( "This item has been deleted." );
		} else {
			if ($log_item ['created_by'] == user_id ()) {
				$log_item = CI::model ( 'notifications' )->logDeleteById ( $id );
				exit ( "yes" );
			} else {
				exit ( "You dont have permission ot delete this." );
			}
		}
	
	}
	
	function delete_user() {
		
		$is_adm = is_admin ();
		
		if ($is_adm == true) {
			$id = intval ( $_POST ['id'] );
			CI::model ( 'users' )->userDeleteById ( $id );
		}
	
	}
	function loginas() {
		
		$id = url_param ( 'id' );
		
		if ($id == false) {
			exit ( 'Specify id' );
		}
		
		$cur_user = user_id ();
		if ($cur_user == false) {
			exit ( 'You need to be logged in as some of the parent users.' );
		}
		
		$other_user = get_user ( $id );
		if ($cur_user == $other_user) {
			redirect ( 'dashboard' );
		}
		//var_dump($other_user);
		if (intval ( $other_user ['parent_id'] ) == $cur_user) {
		
		} else {
			exit ( 'You need to be logged in as some of the parent users.' );
		}
		
		$user_session ['is_logged'] = 'yes';
		
		$user_session ['user_id'] = $other_user ['id'];
		
		CI::library ( 'session' )->set_userdata ( 'user_session', $user_session );
		CI::library ( 'session' )->set_userdata ( 'user', $other_user );
		$back_to = CI::model ( 'core' )->getParamFromURL ( 'back_to' );
		
		if ($back_to != '') {
			$back_to = base64_decode ( $back_to );
			
			if (trim ( $back_to ) != '') {
				
				header ( 'Location: ' . $back_to );
				
				exit ();
			
			} else {
				
				redirect ( 'dashboard' );
			
			}
		
		} else {
			redirect ( 'dashboard' );
		}
	
	}
	
	function logOut() {
		CI::model ( 'users' )->logOut ();
		CI::library ( 'session' )->sess_destroy ();
		exit ();
	}
	
	function test1() {
		//  var_dump($_POST);
		//print mt_rand(1, 65654);
		exit ();
	}
	
	function search_by_name() {
		$name = ($_POST ['term']);
		if ($name == false) {
			exit ();
		}
		global $cms_db_tables;
		$table = $cms_db_tables ['table_users'];
		//$name = $this->db->escape ( $name );
		$aOptions = array ();
		$aOptions ['search_keyword'] = $name;
		$aOptions ['only_fields'] = array ('id, first_name, username, last_name' );
		$aOptions ['limit'] = array (0, 10 );
		$data = CI::model ( 'core' )->fetchDbData ( $table, $aFilter = false, $aOptions );
		$data = json_encode ( $data );
		print $data;
		exit ();
		//p ( $data );
		if (! empty ( $data )) {
		
		}
		//print mt_rand(1, 65654);
		exit ();
	}
	
	function status_update() {
		require_once (APPPATH . 'controllers/api/_api_require_login.php');
		if ($_POST) {
			
			//$this->_requireLogin ();
			

			$currentUser_id = CI::model ( 'core' )->userId ();
			
			$status = array ('user_id' => $currentUser_id, 'status' => $_POST ['status'] );
			
			$updated = CI::model ( 'core' )->saveData ( TABLE_PREFIX . 'users_statuses', $status );
			CI::model ( 'statuses' )->cleanOldStatuses ( $currentUser );
			CI::model ( 'core' )->cleanCacheGroup ( 'users/statuses' );
			echo $updated;
		
		}
	}
	function save() {
		
		if ($_POST) {
			$is_adm = is_admin ();
			//
			//p($_POST);
			if ($_POST ['id'] == false) {
				$_POST ['id'] = user_id ();
			}
			
			if ($_POST ['id']) {
				$_POST ['id'] = intval ( $_POST ['id'] );
				if ($_POST ['id'] == 0) {
					$user_register_errors ['security1'] = ('Error: Sorry you must specify id by $_POST. Otherwise you would be able create user from this api point, which all spammers would love :)');
				} else {
					$curent_user = user_id ();
					if ($curent_user == $_POST ['id']) {
					
					} else {
						
						$other_user = get_user ( $_POST ['id'] );
						//var_dump($other_user);
						if (intval ( $other_user ['parent_id'] ) == $curent_user) {
						
						} else {
							
							if ($is_adm == false) {
								$user_register_errors ['security2'] = ('Error: Sorry, you dont have permission to edit this user.');
							}
						
						}
					
					}
				
				}
				
				if (! empty ( $user_register_errors )) {
					$retrn = array ();
					$retrn ['error'] = $user_register_errors;
					
					$retrn = json_encode ( $retrn );
					exit ( $retrn );
					//$this->template ['user_register_errors'] = $user_register_errors;
				

				}
				
				$to_save = $_POST;
				if ($is_adm == false) {
					if ($to_save ['is_admin']) {
						//oppsie
						unset ( $to_save ['is_admin'] );
					}
				}
				$to_save ['created_by'] = $_POST ['id'];
				$to_save ['edited_by'] = $_POST ['id'];
				//
				

				$userId = CI::model ( 'users' )->saveUser ( $to_save );
				
				$retrn = array ();
				$retrn ['success'] = $_POST;
				//p($_POST);
				if ($_POST ['redirect_to']) {
					$to = prep_url ( $_POST ['redirect_to'] );
					header ( 'Location: ' . $to );
					
					exit ();
				} else {
					$retrn = json_encode ( $retrn );
					exit ( $retrn );
				}
			
			}
		
		} else {
			exit ( "Error: Provide your params by post." );
		}
		exit ();
	}
	
	function login() {
		
		if ($_POST) {
			
			$user_register_errors = array ();
			
			$reg_is_error = false;
			
			$user = $_POST ['username'];
			$pass = $_POST ['password'];
			$email = $_POST ['email'];
			
			$data = array ();
			$data ['username'] = $user;
			$data ['password'] = $pass;
			$data ['is_active'] = 'y';
			//	p ( $data );
			//p ( $_POST );
			if (trim ( $user ) == '') {
				$user = $email;
				$data ['username'] = $user;
				//	p ( $data );
			}
			$data = CI::model ( 'users' )->getUsers ( $data );
			$data = $data [0];
			
			if (empty ( $data )) {
				if (trim ( $email ) != '') {
					$data = array ();
					$data ['email'] = $email;
					$data ['password'] = $pass;
					$data ['is_active'] = 'y';
				//	p ( $data );
					$data = CI::model ( 'users' )->getUsers ( $data );
					$data = $data [0];
				}
			}
			
			if (empty ( $data )) {
				CI::library ( 'session' )->unset_userdata ( 'the_user' );
				
				$reg_is_error = true;
				
				$user_register_errors ['login_error'] = 'Please enter correct username and password!';
				
			//exit ();
			} else {
				
				CI::library ( 'session' )->set_userdata ( 'the_user', $data );
				
				$user_session = array ();
				$user_session ['is_logged'] = 'yes';
				$user_session ['user_id'] = $data ['id'];
				
				//p ( $user_session );
				$retrn = array ();
				
				$retrn ['success'] = $data;
				
				CI::library ( 'session' )->set_userdata ( 'user_session', $user_session );
				
				$retrn = json_encode ( $retrn );
				exit ( $retrn );
				
				if ($data ["is_admin"] == 'y') {
					
					if ($_POST ['where_to'] == 'live_edit') {
						
						$p = get_page ();
						if (! empty ( $p )) {
							$link = page_link ( $p ['id'] );
							$link = $link . '/editmode:y';
							safe_redirect ( $link );
							//p($link,1);
						

						} else {
							safe_redirect ( 'admin' );
						}
						//p($p,1);
					

					} else {
						safe_redirect ( 'admin' );
					}
				
				} else {
					$go = site_url ();
					safe_redirect ( "$go" );
				
				}
				//$data = $data[0];
				//var_dump($data);
				//var_dump($_POST);
				exit ();
			
			}
		
		}
		
		if (! empty ( $user_register_errors )) {
			$retrn = array ();
			$retrn ['error'] = $user_register_errors;
			
			$retrn = json_encode ( $retrn );
			exit ( $retrn );
			//$this->template ['user_register_errors'] = $user_register_errors;
		

		}
		
	//$this->template ['functionName'] = strtolower ( __FUNCTION__ );
	//	$this->load->vars ( $this->template );
	//	$layout = CI::view ( 'layout', true, true );
	//$primarycontent = CI::view ( 'login', true, true );
	//p($this->template );
	// $layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
	//CI::library('output')->set_output ( $primarycontent );
	

	//	exit ('no post');
	

	}
	
	function register() {
		
		if ($_POST) {
			
			$this->template ['form_values'] = $_POST;
			
			$user_register_errors = array ();
			
			$username = $this->input->post ( 'username' );
			
			$email = $this->input->post ( 'email' );
			
			if ($username == false) {
				$username = $email;
			}
			
			$password = $this->input->post ( 'password' );
			
			$password2 = $this->input->post ( 'password2' );
			
			if ($password2 == false) {
				$password2 = $password;
			}
			
			$parent_id = $this->input->post ( 'parent_id' );
			
			$to_reg = array ();
			
			$to_reg ['username'] = $username;
			
			$to_reg ['email'] = $email;
			
			$to_reg ['password'] = $password;
			if ($_POST ['is_active'] == false) {
				$to_reg ['is_active'] = 'n';
			} else {
				//$to_reg ['is_active'] = 'y';
			}
			$to_reg ['is_admin'] = 'n';
			if ($parent_id != false) {
				$to_reg ['parent_id'] = $parent_id;
				if ($_POST ['is_active'] == false) {
					$to_reg ['is_active'] = 'y';
				}
			}
			//parrent  aff
			

			if (isset ( $_COOKIE ["microweber_referrer_user_id"] )) {
				$aff = intval ( $_COOKIE ["microweber_referrer_user_id"] );
				if ($aff != 0) {
					$to_reg ['parent_affil'] = $aff;
				}
			
			} else {
			
			}
			
			$reg_is_error = false;
			
			$check_if_exist = CI::model ( 'users' )->checkUser ( 'username', $to_reg ['username'] );
			
			$check_if_exist_email = CI::model ( 'users' )->checkUser ( 'email', $to_reg ['email'] );
			
			if ($username == '') {
				
				$reg_is_error = true;
				
				$user_register_errors ['username_not_here'] = 'Please enter username!';
			
			}
			
			if ($password == '') {
				
				$reg_is_error = true;
				
				$user_register_errors ['password_not_here'] = 'Please enter password!';
			
			}
			
			$captcha_seesion = CI::library ( 'session' )->userdata ( 'captcha' );
			$captcha = $this->input->post ( 'captcha' );
			if ($captcha == '') {
				
				$reg_is_error = true;
				
				$user_register_errors ['capcha_not_here'] = 'Please enter the captcha!';
			
			}
			
			if ($captcha != $captcha_seesion) {
				
				$reg_is_error = true;
				
				$user_register_errors ['capcha_not_here2'] = 'Please enter the correct captcha from the image!';
			
			}
			
			if ($email == '') {
				
			//$reg_is_error = true;
			

			//$user_register_errors ['email_not_here'] = 'Please enter email!';
			

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
			
			/*	include_once (APPPATH . 'libraries/recaptcha.php');
			//$this->load->library('recaptcha');
    $this->load->library('form_validation');
    CI::helper('form');
    $this->lang->load('recaptcha');
			
			$capcha = new Recaptcha ();
			
			if ($capcha->check_answer ( $this->input->ip_address (), $this->input->post ( 'recaptcha_challenge_field' ), $this->input->post ( 'recaptcha_response_field' ) )) {
			
			} else {
				$user_register_errors ['recaptcha_challenge_field'] = 'Incorrect captcha!';
			}*/
			
			if (! empty ( $user_register_errors )) {
				$retrn = array ();
				$retrn ['error'] = $user_register_errors;
				
				$retrn = json_encode ( $retrn );
				exit ( $retrn );
				//$this->template ['user_register_errors'] = $user_register_errors;
			

			} else {
				
				//Send mail
				//						$userdata = array ();
				//						$userdata ['id'] = $to_reg ['parent_affil'];
				//						$parent = CI::model('users')->getUsers ( $userdata );
				//						//$this->dbQuery("select * from firecms_users where id={$to_reg ['parent_affil']}");
				//						$to_reg ['parent'] = $parent [0] ['username'];
				//
				//						$to_reg ['option_key'] = 'mail_new_user_reg';
				//						CI::model('core')->sendMail ( $to_reg, true );
				

				//$primarycontent = CI::view ( 'me/register_done', true, true );
				

				$rett = $_POST;
				$this->template ['user_registration_done'] = true;
				$userId = CI::model ( 'users' )->saveUser ( $to_reg );
				$rett ['id'] = $userId;
				
				/*~~~~~~~~~~~~~~~ Send activation email ~~~~~~~~~~~~~~~~~~~*/
				if ($email != '') {
					$emailTemplate = CI::model ( 'core' )->optionsGetByKey ( 'registration_email', true );
					$from = CI::model ( 'core' )->optionsGetByKey ( 'reg_email_from', true );
					
					$message = str_replace ( "{activation_url}", site_url ( 'users/user_action:activate/code:' . md5 ( $userId ) ), $emailTemplate ['option_value2'] );
					
					// Send activation email
					$sendOptions = array ('subject' => $emailTemplate ['option_value'], 'message' => $message, 'from_email' => $from ['option_value'], 'from_name' => $from ['option_value2'], 'to_email' => $to_reg ['email'] );
					
					CI::model ( 'core' )->sendMail2 ( $sendOptions );
				}
				$retrn = array ();
				
				$retrn ['success'] = $rett;
				
				$retrn = json_encode ( $retrn );
				
				$other_user = get_user ( $rett ['id'] );
				
				$user_session ['is_logged'] = 'yes';
				
				$user_session ['user_id'] = $other_user ['id'];
				
				CI::library ( 'session' )->set_userdata ( 'user_session', $user_session );
				CI::library ( 'session' )->set_userdata ( 'user', $other_user );
				
				exit ( $retrn );
				
				/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
				
				// redirect user to back_url
				$back_to = CI::library ( 'session' )->userdata ( 'back_to' );
				if ($back_to) {
					
					$back_to = base64_decode ( $back_to );
					CI::library ( 'session' )->unset_userdata ( 'back_to' );
					
					if (trim ( $back_to ) != '') {
						
						header ( 'Location: ' . $back_to );
						exit ();
					}
				
				}
			
			}
			
		//$this->load->vars ( $this->template );
		

		}
	}
	
	function message_send() {
		require_once (APPPATH . 'controllers/api/_api_require_login.php');
		if ($_POST) {
			
			$currentUser = CI::library ( 'session' )->userdata ( 'user' );
			
			$messageKey = $_POST ['mk'];
			unset ( $_POST ['mk'] );
			//$messageKey =  ( $messageKey );
			$messageKey = CI::model ( 'core' )->securityDecryptString ( $messageKey );
			//var_dump( CI::model('core')->userId (), $messageKey);
			

			if (CI::model ( 'core' )->userId () != $messageKey) {
				exit ( 'Error in $messageKey' );
			}
			
			$data = $_POST;
			$data = stripFromArray ( $data );
			$data = htmlspecialchars_deep ( $data );
			
			/*
			 * Format data array
			 */
			
			// from user
			$data ['from_user'] = intval ( CI::model ( 'core' )->userId () );
			
			// to user
			if (intval ( $data ['receiver'] ) == 0) {
				$data ['receiver'] = $data ['to_user'];
			}
			
			$data ['to_user'] = intval ( $data ['receiver'] );
			unset ( $data ['receiver'] );
			
			// parent id
			if ($data ['conversation']) {
				$data ['parent_id'] = $data ['conversation'];
			}
			unset ( $data ['conversation'] );
			
			// validate 'to_user'
			if ($data ['parent_id']) {
				
				$parentMessage = CI::model ( 'core' )->fetchDbData ( TABLE_PREFIX . 'messages', array (array ('id', $data ['parent_id'] ) ) );
				
				$parentMessage = $parentMessage [0];
				
				if (! in_array ( $data ['to_user'], array ($parentMessage ['from_user'], $parentMessage ['to_user'] ) )) {
					//throw new Exception ( 'Cheating detected.' );
				}
			
			}
			$data ['is_read'] = 'n';
			
			$sent = CI::model ( 'messages' )->messageSave ( $data );
			
			if (intval ( $data ['parent_id'] ) != 0) {
				$data2 = array ();
				$data2 ['is_read'] = 'n';
				$data2 = CI::model ( 'messages' )->messageSave ( $data2 );
				$cache_group = 'users/messages/' . $data ['parent_id'];
				CI::model ( 'core' )->cleanCacheGroup ( $cache_group );
			}
			
			//echo $sent;
			echo 'Message sent';
			$cache_group = 'users/messages/global/';
			CI::model ( 'core' )->cleanCacheGroup ( $cache_group );
		
		}
	
	}
	
	/**
	 * Mark given message as read
	 */
	function notification_read() {
		require_once (APPPATH . 'controllers/api/_api_require_login.php');
		if ($_POST) {
			global $cms_db_tables;
			$table = $cms_db_tables ['table_users_notifications'];
			
			$messageId = $_POST ['id'];
			$messageId - intval ( $messageId );
			$user_id = CI::model ( 'core' )->userId ();
			
			$message = CI::model ( 'core' )->fetchDbData ( $table, array (array ('id', $messageId ) ) );
			
			$message = $message [0];
			//	p ( $currentUser );
			//	p ( $message );
			if (($user_id != $message ['to_user'])) {
				
				throw new Exception ( 'You have no rights to read this message.' );
			
			}
			
			$q = " update $table set is_read = 'y' where id=$messageId ";
			$q = CI::model ( 'core' )->dbQ ( $q );
			$q = " update $table set is_read = 'y' where
			from_user={$message['from_user']}
			and to_user={$message['to_user']}
			and to_table_id={$message['to_table_id']}
			and to_table='{$message['to_table']}'

			and is_read='n'
			";
			$q = CI::model ( 'core' )->dbQ ( $q );
			
			$cache_group = 'users/notifications';
			CI::model ( 'core' )->cleanCacheGroup ( $cache_group );
			
			//$read = CI::model('core')->saveData ( $table, array ('id' => $messageId, 'is_read' => 'y' ) );
			

			echo $messageId;
		
		}
	
	}
	
	/**
	 * Mark given message as read
	 */
	function notification_delete() {
		require_once (APPPATH . 'controllers/api/_api_require_login.php');
		if ($_POST) {
			global $cms_db_tables;
			$table = $cms_db_tables ['table_users_notifications'];
			
			$messageId = $_POST ['id'];
			
			$user_id = CI::model ( 'core' )->userId ();
			
			$message = CI::model ( 'core' )->fetchDbData ( $table, array (array ('id', $messageId ) ) );
			
			$message = $message [0];
			//	p ( $currentUser );
			//	p ( $message );
			if (($user_id != $message ['to_user'])) {
				
				throw new Exception ( 'You have no rights to delete this.' );
			
			}
			
			$read = CI::model ( 'core' )->deleteDataById ( $table, intval ( $message ['id'] ) );
			$q = "DELETE FROM $table where to_user={$user_id} and to_table='{$message['to_table']}'
and to_table_id='{$message['to_table_id']}'
			";
			CI::model ( 'core' )->dbQ ( $q );
			
			$cache_group = 'users/notifications';
			CI::model ( 'core' )->cleanCacheGroup ( $cache_group );
			echo intval ( $message ['id'] );
		
		}
	
	}
	
	/**
	 * Mark given message as read
	 */
	function message_read() {
		require_once (APPPATH . 'controllers/api/_api_require_login.php');
		if ($_POST) {
			global $cms_db_tables;
			$table = $cms_db_tables ['table_messages'];
			
			$messageId = $_POST ['id'];
			
			$user_id = CI::model ( 'core' )->userId ();
			
			$message = CI::model ( 'core' )->fetchDbData ( $table, array (array ('id', $messageId ) ) );
			
			$message = $message [0];
			//	p ( $currentUser );
			//p ( $message );
			if (($user_id != $message ['to_user'])) {
				if (($user_id == $message ['created_by'])) {
					
					throw new Exception ( 'You have no rights to read this message.' );
				}
			
			}
			
			$read = CI::model ( 'core' )->saveData ( $table, array ('id' => $message ['id'], 'is_read' => 'y' ) );
			$q = "update $table set is_read='y' where id in ({$message ['id']}) and created_by<>$user_id ";
			//p ( $q );
			CI::model ( 'core' )->dbQ ( $q );
			
			$cache_group1 = 'users/messages/' . $message ['id'];
			CI::model ( 'core' )->cleanCacheGroup ( $cache_group1 );
			
			$cache_group1 = 'users/messages/' . $message ['parent_id'];
			CI::model ( 'core' )->cleanCacheGroup ( $cache_group1 );
			
			$thread = CI::model ( 'messages' )->messagesThread ( $read );
			if (! empty ( $thread )) {
				$thread_ids = CI::model ( 'core' )->dbExtractIdsFromArray ( $thread );
				if (! empty ( $thread_ids )) {
					$thread_ids_i = implode ( ',', $thread_ids );
					$q = "update $table set is_read='y' where id in ($thread_ids_i) and created_by<>$user_id ";
					//p($q);
					CI::model ( 'core' )->dbQ ( $q );
					
					foreach ( $thread_ids as $thread_i ) {
						$cache_group1 = 'users/messages/' . $thread_i;
						CI::model ( 'core' )->cleanCacheGroup ( $cache_group1 );
					}
				}
				
			//p($thread_ids);
			}
			//p($thread); 
			

			echo $read;
			
			$cache_group = 'users/messages';
			CI::model ( 'core' )->cleanCacheGroup ( $cache_group );
		}
	
	}
	
	/**
	 * Mark given message as not read
	 */
	function message_unread() {
		require_once (APPPATH . 'controllers/api/_api_require_login.php');
		if ($_POST) {
			
			$messageId = $_POST ['id'];
			
			$user_id = CI::model ( 'core' )->userId ();
			
			$message = CI::model ( 'core' )->fetchDbData ( TABLE_PREFIX . 'messages', array (array ('id', $messageId ) ) );
			
			$message = $message [0];
			
			if ($user_id != $message ['to_user']) {
				throw new Exception ( 'You have no rights to unread this message.' );
			}
			
			$read = CI::model ( 'core' )->saveData ( TABLE_PREFIX . 'messages', array ('id' => $message ['id'], 'is_read' => 'n' ) );
			
			echo $read;
			
			$cache_group = 'users/messages';
			CI::model ( 'core' )->cleanCacheGroup ( $cache_group );
		}
	}
	
	/**
	 * Delete message
	 */
	function message_delete() {
		require_once (APPPATH . 'controllers/api/_api_require_login.php');
		if ($_POST) {
			
			$messageId = $_POST ['id'];
			
			$currentUser = CI::library ( 'session' )->userdata ( 'user' );
			
			$message = CI::model ( 'core' )->fetchDbData ( TABLE_PREFIX . 'messages', array (array ('id', $messageId ) ) );
			
			$message = $message [0];
			//p($message);
			if ($message ['from_user'] == CI::model ( 'core' )->userId ()) {
				$deletedFrom = 'sender';
			} elseif ($message ['to_user'] == CI::model ( 'core' )->userId ()) {
				$deletedFrom = 'receiver';
			} else {
				throw new Exception ( 'You have no permission to delete this message.' );
			}
			
			$thread = CI::model ( 'messages' )->messagesThread ( $message ['id'] );
			if (! empty ( $thread )) {
				$thread_ids = CI::model ( 'core' )->dbExtractIdsFromArray ( $thread );
				$table = TABLE_PREFIX . 'messages';
				if (! empty ( $thread_ids )) {
					$thread_ids_i = implode ( ',', $thread_ids );
					$q = "update $table set is_read='y' where id in ($thread_ids_i) ";
					//	p($q);
					CI::model ( 'core' )->dbQ ( $q );
					
					foreach ( $thread_ids as $thread_i ) {
						$cache_group1 = 'users/messages/' . $thread_i;
						CI::model ( 'core' )->cleanCacheGroup ( $cache_group1 );
					}
				}
				
			//p($thread_ids);
			}
			
			if (intval ( $message ['parent_id'] ) == 0) {
				
				$deleted = CI::model ( 'core' )->saveData ( TABLE_PREFIX . 'messages', array ('id' => $message ['id'], 'deleted_from_sender' => 'y', 'deleted_from_receiver' => 'y', 'is_read' => 'y' ) );
				$cache_group = 'users/messages/' . $message ['parent_id'];
				CI::model ( 'core' )->cleanCacheGroup ( $cache_group );
			} else {
				$cache_group = 'users/messages/' . $message ['id'];
				CI::model ( 'core' )->cleanCacheGroup ( $cache_group );
				$deleted = CI::model ( 'core' )->saveData ( TABLE_PREFIX . 'messages', array ('id' => $message ['id'], 'deleted_from_' . $deletedFrom => 'y', 'is_read' => 'y' ) );
			}
			echo $deleted;
			
			$cache_group = 'users/messages';
			CI::model ( 'core' )->cleanCacheGroup ( $cache_group );
		}
	
	}
	
	function followingSystem() {
		require_once (APPPATH . 'controllers/api/_api_require_login.php');
		if ($_POST) {
			$you = CI::model ( 'core' )->userId ();
			if (intval ( $you ) == 0) {
				exit ( 'You must login.' );
			}
			
			$followerId = intval ( $_POST ['follower_id'] );
			$follow = ( bool ) $_POST ['follow']; // if 0 unlollow, if 1 follow
			$special = ( bool ) $_POST ['special']; // if 1 will add special flag
			$cancel = ( bool ) $_POST ['cancel']; // if 1 will cancel the whole relatiomship
			

			if ($followerId == 0) {
				exit ( 'Error: no follower defined? Are you sure you clicked on actual person?' );
			} else {
				$follower = CI::model ( 'users' )->getUserById ( $followerId );
				//p($follower);
				if (empty ( $follower )) {
					exit ( 'Error: invalid user id ' . $followerId );
				}
			}
			
			if ($followerId == CI::model ( 'core' )->userId ()) {
				exit ( 'Error: you cant follow yourself :)' );
			}
			
			$currentUser = CI::library ( 'session' )->userdata ( 'user' );
			
			$followed = CI::model ( 'users' )->saveFollower ( array ('user' => CI::model ( 'core' )->userId (), 'follower' => $followerId, 'follow' => $follow, 'special' => $special, 'cancel' => $cancel ) );
			
			//echo $followed;
			

			$follower ['first_name'] ? $name = $follower ['first_name'] : $name = $follower ['username'];
			
			if ((intval ( $_POST ['follow'] ) == 0)) {
				$msg = "You are no longer friend with {$name}.";
			}
			
			if ((intval ( $_POST ['follow'] ) == 1)) {
				$msg = "You are now friend with {$name}.";
			}
			
			if ((intval ( $_POST ['follow'] ) == 1) and intval ( $_POST ['special'] ) == 1) {
				//$msg = "You have added {$name} to your circle.";
				$msg = "You are now friend with {$name}.";
			}
			
			if (isset ( $_POST ['special'] )) {
				if (intval ( ($_POST ['follow']) == 1 ) and intval ( $_POST ['special'] ) == 0) {
					//$msg = "You have removed {$name} from your circle.";
					$msg = "You are now friend with {$name}.";
				}
			}
			
			CI::model ( 'core' )->cleanCacheGroup ( 'users/relations' );
			exit ( $msg );
		}
	}

}



