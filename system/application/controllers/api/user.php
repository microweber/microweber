<?php

class User extends Controller {

	function __construct() {

		parent::Controller ();

		require_once (APPPATH . 'controllers/default_constructor.php');
		//p($user_session);
		require_once (APPPATH . 'controllers/api/default_constructor.php');

		if ($this->users_model->is_logged_in () == false) {
			exit ( 'login required' );
		}

	}

	function index() {
		var_dump ( $_COOKIE );
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
		$data = $this->core_model->fetchDbData ( $table, $aFilter = false, $aOptions );
		$data = json_encode ( $data );
		print $data;
		exit ();
		//p ( $data );
		if (! empty ( $data )) {

		}
		//print mt_rand(1, 65654);
		exit ();
	}

	function statusUpdate() {
		if ($_POST) {
			$currentUser = $this->session->userdata ( 'user' );
			$status = array ('user_id' => $this->core_model->userId (), 'status' => $_POST ['status'] );
			$updated = $this->core_model->saveData ( TABLE_PREFIX . 'users_statuses', $status );
			echo $updated;
			$this->users_model->cleanOldStatuses ( $currentUser );

		}
	}

	function message_send() {
		if ($_POST) {

			$currentUser = $this->session->userdata ( 'user' );

			$messageKey = $_POST ['mk'];
			unset ( $_POST ['mk'] );
			//$messageKey =  ( $messageKey );
			$messageKey = $this->core_model->securityDecryptString ( $messageKey );
			//var_dump( $this->core_model->userId (), $messageKey);


			if ($this->core_model->userId () != $messageKey) {
				exit ( 'Error in $messageKey' );
			}

			$data = $_POST;
			$data = stripFromArray ( $data );
			$data = htmlspecialchars_deep ( $data );

			/*
			 * Format data array
			 */

			// from user
			$data ['from_user'] = intval ( $this->core_model->userId () );

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

				$parentMessage = $this->core_model->fetchDbData ( TABLE_PREFIX . 'messages', array (array ('id', $data ['parent_id'] ) ) );

				$parentMessage = $parentMessage [0];

				if (! in_array ( $data ['to_user'], array ($parentMessage ['from_user'], $parentMessage ['to_user'] ) )) {
					throw new Exception ( 'Cheating detected.' );
				}

			}
			$data ['is_read'] = 'n';
			$sent = $this->users_model->messageSave ( $data );

			//echo $sent;
			echo 'Message sent';
			$cache_group = 'users/messages';
			$this->core_model->cleanCacheGroup ( $cache_group );

		}

	}

	/**
	 * Mark given message as read
	 */
	function notification_read() {

		if ($_POST) {
			global $cms_db_tables;
			$table = $cms_db_tables ['table_users_notifications'];

			$messageId = $_POST ['id'];
			$messageId - intval ( $messageId );
			$user_id = $this->core_model->userId ();

			$message = $this->core_model->fetchDbData ( $table, array (array ('id', $messageId ) ) );

			$message = $message [0];
			//	p ( $currentUser );
			//	p ( $message );
			if (($user_id != $message ['to_user'])) {

				throw new Exception ( 'You have no rights to read this message.' );

			}

			$q = " update $table set is_read = 'y' where id=$messageId ";
			$q = $this->core_model->dbQ ( $q );

			/*$q = " update $table set is_read = 'y' where
			from_user={$message['from_user']}
			and to_user={$message['to_user']}
			and to_table_id={$message['to_table_id']}
			and to_table='{$message['to_table']}'
			and subtype='{$message['subtype']}'
			and subtype_value='{$message['subtype_value']}'
			and is_read='n'
			";*/

			$q = " update $table set is_read = 'y' where
			from_user={$message['from_user']}
			and to_user={$message['to_user']}
			and to_table_id={$message['to_table_id']}
			and to_table='{$message['to_table']}'

			and is_read='n'
			";
			$q = $this->core_model->dbQ ( $q );

			$cache_group = 'users/notifications';
			$this->core_model->cleanCacheGroup ( $cache_group );

			//$read = $this->core_model->saveData ( $table, array ('id' => $messageId, 'is_read' => 'y' ) );


			echo $messageId;

		}

	}

	/**
	 * Mark given message as read
	 */
	function notification_delete() {

		if ($_POST) {
			global $cms_db_tables;
			$table = $cms_db_tables ['table_users_notifications'];

			$messageId = $_POST ['id'];

			$user_id = $this->core_model->userId ();

			$message = $this->core_model->fetchDbData ( $table, array (array ('id', $messageId ) ) );

			$message = $message [0];
			//	p ( $currentUser );
			//	p ( $message );
			if (($user_id != $message ['to_user'])) {

				throw new Exception ( 'You have no rights to delete this.' );

			}

			$read = $this->core_model->deleteDataById ( $table, intval ( $message ['id'] ) );
			$q = "DELETE FROM $table where to_user={$user_id} and to_table='{$message['to_table']}'
and to_table_id='{$message['to_table_id']}'
			";
			$this->core_model->dbQ ( $q );

			$cache_group = 'users/notifications';
			$this->core_model->cleanCacheGroup ( $cache_group );
			echo intval ( $message ['id'] );

		}

	}

	/**
	 * Mark given message as read
	 */
	function message_read() {

		if ($_POST) {
			global $cms_db_tables;
			$table = $cms_db_tables ['table_messages'];

			$messageId = $_POST ['id'];

			$user_id = $this->core_model->userId ();

			$message = $this->core_model->fetchDbData ( $table, array (array ('id', $messageId ) ) );

			$message = $message [0];
			//	p ( $currentUser );
			//p ( $message );
			if (($user_id != $message ['to_user'])) {
				if (($user_id != $message ['from_user'])) {

					throw new Exception ( 'You have no rights to read this message.' );
				}

			}

			$read = $this->core_model->saveData ( TABLE_PREFIX . 'messages', array ('id' => $message ['id'], 'is_read' => 'y' ) );

			echo $read;

			$cache_group = 'users/messages';
			$this->core_model->cleanCacheGroup ( $cache_group );
		}

	}

	/**
	 * Mark given message as not read
	 */
	function message_unread() {
		if ($_POST) {

			$messageId = $_POST ['id'];

			$user_id = $this->core_model->userId ();

			$message = $this->core_model->fetchDbData ( TABLE_PREFIX . 'messages', array (array ('id', $messageId ) ) );

			$message = $message [0];

			if ($user_id != $message ['to_user']) {
				throw new Exception ( 'You have no rights to unread this message.' );
			}

			$read = $this->core_model->saveData ( TABLE_PREFIX . 'messages', array ('id' => $message ['id'], 'is_read' => 'n' ) );

			echo $read;

			$cache_group = 'users/messages';
			$this->core_model->cleanCacheGroup ( $cache_group );
		}
	}

	/**
	 * Delete message
	 */
	function message_delete() {

		if ($_POST) {

			$messageId = $_POST ['id'];

			$currentUser = $this->session->userdata ( 'user' );

			$message = $this->core_model->fetchDbData ( TABLE_PREFIX . 'messages', array (array ('id', $messageId ) ) );

			$message = $message [0];
			//p($message);
			if ($message ['from_user'] == $this->core_model->userId ()) {
				$deletedFrom = 'sender';
			} elseif ($message ['to_user'] == $this->core_model->userId ()) {
				$deletedFrom = 'receiver';
			} else {
				throw new Exception ( 'You have no permission to delete this message.' );
			}

			$deleted = $this->core_model->saveData ( TABLE_PREFIX . 'messages', array ('id' => $message ['id'], 'deleted_from_' . $deletedFrom => 'y' ) );

			echo $deleted;

			$cache_group = 'users/messages';
			$this->core_model->cleanCacheGroup ( $cache_group );
		}

	}

	function followingSystem() {

		if ($_POST) {
			$you = $this->core_model->userId ();
			if (intval ( $you ) == 0) {
				exit ( 'You must login.' );
			}

			$followerId = intval ( $_POST ['follower_id'] );
			$follow = ( bool ) $_POST ['follow']; // if 0 unlollow, if 1 follow
			$special = ( bool ) $_POST ['special']; // if 1 will add special flag


			if ($followerId == 0) {
				exit ( 'Error: no follower defined? Are you sure you clicked on actual person?' );
			} else {
				$follower = $this->users_model->getUserById ( $followerId );
				//p($follower);
				if (empty ( $follower )) {
					exit ( 'Error: invalid user id ' . $followerId );
				}
			}

			if ($followerId == $this->core_model->userId ()) {
				exit ( 'Error: you cant follow yourself :)' );
			}

			$currentUser = $this->session->userdata ( 'user' );

			$followed = $this->users_model->saveFollower ( array ('user' => $this->core_model->userId (), 'follower' => $followerId, 'follow' => $follow, 'special' => $special ) );

			//echo $followed;


			$follower ['first_name'] ? $name = $follower ['first_name'] : $name = $follower ['username'];

			if ((intval ( $_POST ['follow'] ) == 0)) {
				$msg = "You no longer follow {$name}.";
			}

			if ((intval ( $_POST ['follow'] ) == 1)) {
				$msg = "You are now following {$name}.";
			}

			if ((intval ( $_POST ['follow'] ) == 1) and intval ( $_POST ['special'] ) == 1) {
				$msg = "You have added {$name} to your circle.";
			}

			if (isset ( $_POST ['special'] )) {
				if (intval ( ($_POST ['follow']) == 1 ) and intval ( $_POST ['special'] ) == 0) {
					$msg = "You have removed {$name} from your circle.";
				}
			}

			$this->core_model->cleanCacheGroup ( 'users/relations' );
			exit ( $msg );
		}
	}

}



