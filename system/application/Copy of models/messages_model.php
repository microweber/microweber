<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Messages_model extends Model {
	
	function __construct() {
		parent::Model ();
	
	}
	
	public function cleanup() {
		global $cms_db_tables;
		$table_notifications = $cms_db_tables ['table_users_notifications'];
		
		$user_id = CI::model('core')->userId ();
		if (intval ( $user_id ) == 0) {
			return false;
		}
		
		$timeFile = CACHEDIR . 'interval_' . __FUNCTION__;
		if (! file_exists ( $timeFile )) {
			@touch ( $timeFile );
		}
		
		if (filemtime ( $timeFile ) < strtotime ( "-2 hours" )) {
			@touch ( $timeFile );
			$table = TABLE_PREFIX . 'messages';
			
			$q = "delete
	
FROM
	$table
WHERE
	deleted_from_sender = 'y'
	AND deleted_from_receiver = 'y'
	 ";
			$q = CI::model('core')->dbQ ( $q );
		
		}
	
	}
	
	/**
	 * Get unread messages for given user ID
	 * @param int $user_id user id - if false the curent user is used
	 * @return array msg
	 */
	function messagesGetUnreadCountForUser($user_id = false) {
		if ($user_id == false) {
			
			$user_id = CI::model('core')->userId ();
		}
		$table = TABLE_PREFIX . 'messages';
		$params = array ();
		$params [] = array ('to_user', $user_id );
		$params [] = array ('is_read', 'n' );
		$params [] = array ('deleted_from_receiver', 'n' );
		$params [] = array ('parent_id', 'NULL' );
		
		$q = "SELECT
	count(*) as qty
FROM
	$table
WHERE
	 to_user = $user_id
	 AND created_by <> $user_id
	 AND from_user <> $user_id
	AND is_read = 'n'
	AND deleted_from_receiver = 'n' AND deleted_from_sender = 'n'
	 ";
		
		//print $q;
		$q = CI::model('core')->dbQuery ( $q );
		$q = $q [0] ['qty'];
		return intval ( $q );
		
		$options = array ();
		$options ['get_count'] = true;
		$options ['debug'] = true;
		$options ['cache'] = false;
		//$options ['cache_group'] = false;
		//	$options [] = array ('cache_group' => 'users/messages/global' . $user_id );
		

		$data = $this->messagesGetByParams ( $params, $options );
		
		return intval ( $data );
	}
	
	/**
	 * Generic message saving funtion
	 * @param array
	 * @return int id saved
	 */
	function messageSave($data) {
		
		
		
		$id = CI::model('core')->saveData ( TABLE_PREFIX . 'messages', $data );
		CI::model('core')->cleanCacheGroup ( 'users/messages/global' );
		return $id;
	}
	/**
	 * Get messages by parent - recursive! :)
	 * @param int $msg_id msg id
	 * @return array msgs
	 */
	function messagesGetByParent($msg_id) {
		$msg_id = intval ( $msg_id );
		
		if ($msg_id == 0) {
			return false;
		}
		//$messages = array ();
		$table = TABLE_PREFIX . 'messages';
		
		$q = "SELECT * from $table where parent_id={$msg_id} and deleted_from_receiver='n' and deleted_from_sender='n' ";
		$cache_group = 'users/messages/' . $msg_id;
		$cache_group_id = __FUNCTION__ . md5 ( $q );
		$resutlt = CI::model('core')->dbQuery ( $q, $cache_group_id, $cache_group );
		$return = array ();
		if (empty ( $resutlt )) {
			return false;
		} else {
			foreach ( $resutlt as $item ) {
				$return [] = $item;
				$more = $this->messagesGetByParent ( $item ['id'] );
				if (! empty ( $more )) {
					foreach ( $more as $item1 ) {
						if ($item ['id'] != $item1 ['id']) {
							$return [] = $item1;
						}
					}
				}
			}
		}
		//$return = array_unique ( $return );
		return $return;
	}
	
	function messagesGetById($msg_id) {
		$msg_id = intval ( $msg_id );
		
		if ($msg_id == 0) {
			return false;
		}
		//$messages = array ();  
		$table = TABLE_PREFIX . 'messages';
		$q = "SELECT * from $table where id={$msg_id}  ";
		$cache_group = 'users/messages/' . $msg_id;
		$cache_group_id = __FUNCTION__ . md5 ( $q );
		$resutlt = CI::model('core')->dbQuery ( $q, $cache_group_id, $cache_group );
		
		if (empty ( $resutlt )) {
			return false;
		} else {
			return $resutlt [0];
		}
		//$return = array_unique ( $return );
		return $return;
	}
	
	/**
	 * Get the whole message thread
	 * @param int $msg_id msg id
	 * @return array msgs
	 */
	function messagesThread($msg_id) {
		$msg_id = intval ( $msg_id );
		
		if ($msg_id == 0) {
			return false;
		}
		//$messages = array ();
		

		$table = TABLE_PREFIX . 'messages';
		$q = "SELECT * from $table where id={$msg_id} and deleted_from_receiver='n' and deleted_from_sender='n' ";
		
		$resutlt = CI::model('core')->dbQuery ( $q, md5 ( $q ), 'users/messages/' . $msg_id );
		//var_dump($resutlt);
		if (empty ( $resutlt )) {
			return false;
		} else {
			foreach ( $resutlt as $item ) {
				$messages [] = $item;
				if (intval ( $item ['parent_id'] ) != 0) {
					$more = $this->messagesGetByParent ( $item ['parent_id'] );
				} else {
					$more = $this->messagesGetByParent ( $item ['id'] );
				}
				
				if (! empty ( $more )) {
					foreach ( $more as $item1 ) {
						$messages [] = $item1;
					}
				}
			}
		}
		//p ( $messages );
		if (empty ( $messages )) {
			return false;
		} else {
			foreach ( $messages as $item ) {
			
			}
		}
		
		return $messages;
	}
	
	/**
	 * Get unread messages for given user ID
	 * @param int $user_id user id
	 * @return array msg
	 */
	function messagesGetUnreadForUser($user_id) {
		
		$params = array ();
		$params [] = array ('to_user', $user_id );
		$params [] = array ('is_read', 'n' );
		$params [] = array ('deleted_from_receiver', 'n' );
		$params [] = array ('from_user', $user_id, '<>', 'and' );
		$params [] = array ('created_by', $user_id, '<>', 'and' );
		$db_options = array();
		$db_options['debug'] = 1;
		
		
		$data = $this->messagesGetByParams ( $params ,$db_options);
		return $data;
	}
	
	/**
	 * Get messages by default  params
	 * @param array $params
	 * @param array|false $options
	 * @return array
	 */
	function messagesGetByDefaultParams($params = false, $db_options = false) {
		if (empty ( $params )) {
			$currentUserId = intval ( CI::model('core')->userId () );
			if (CI::model('core')->userId () == 0) {
				exit ( "Error in " . __FILE__ . " on line " . __LINE__ );
			}
			$params = array ();
			$params [] = array ('to_user', $currentUserId );
			$params [] = array ('is_read', 'y' );
			$params [] = array ('deleted_from_receiver', 'n' );
			//$params [] = array ('from_user', $currentUserId);
			$params [] = array ('from_user', $currentUserId, '<>', 'and' );
		}
		
		global $cms_db_tables;
		$table = TABLE_PREFIX . 'messages';
		
		$options = array ();
		$options ['get_params_from_url'] = true;
		$options ['debug'] = false;
		$options ['items_per_page'] = 20;
		//$options ['group_by'] = 'to_table, to_table_id,from_user, type';
		

		//$options ['group_by'] = 'to_table, to_table_id,from_user';
		$options ['order'] = array ('created_on', 'DESC' );
		
		$options ['cache'] = true;
		$options ['cache_group'] = 'users/messages/global';
		if (! empty ( $db_options )) {
			foreach ( $db_options as $k => $v ) {
				$options ["{$k}"] = $v;
			}
		}
		
		$data = CI::model('core')->fetchDbData ( $table, $params, $options );
		return $data;
	}
	
	/**
	 * Get messages by given params
	 * @param array $params
	 * @param array|false $options
	 * @return array
	 */
	function messagesGetByParams($params, $db_options = false) {
		if (empty ( $params )) {
			return false;
		}
		$table = TABLE_PREFIX . 'messages';
		//$params [] = array ('to_user', $user_id );
		//$params [] = array ('is_read', 'n' );
		//$params [] = array ('deleted_from_receiver', 'n' );
		

		$options = array ();
		$options ['get_count'] = false;
		$options ['debug'] = false;
		$options ['cache'] = true;
		$options ['cache_group'] = 'users/messages/global/';
		
		if (! empty ( $db_options )) {
			foreach ( $db_options as $k => $v ) {
				
				$options ["{$k}"] = $v;
			}
		}
		
		$data = CI::model('core')->fetchDbData ( $table, $params, $options );
		return $data;
	}

}
