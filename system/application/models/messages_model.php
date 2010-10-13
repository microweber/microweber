<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Messages_model extends Model {
	
	function __construct() {
		parent::Model ();
	
	}
	
	/**
	 * Get unread messages for given user ID
	 * @param int $user_id user id - if false the curent user is used
	 * @return array msg
	 */
	function messagesGetUnreadCountForUser($user_id = false) {
		if ($user_id == false) {
			
			$user_id = $this->core_model->userId ();
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
	 AND from_user <> $user_id
	AND is_read = 'n'
	AND deleted_from_receiver = 'n'
	 ";
		
		//print $q;
		$q = $this->core_model->dbQuery ( $q );
		$q = $q [0] ['qty'];
		return intval ( $q );
		
		$options = array ();
		$options ['get_count'] = true;
		$options ['debug'] = true;
		$options ['cache'] = false;
		//$options ['cache_group'] = false;
		//	$options [] = array ('cache_group' => 'users/messages' . $user_id );
		

		$data = $this->messagesGetByParams ( $params, $options );
		
		return intval ( $data );
	}
	
	/**
	 * Generic message saving funtion
	 * @param array
	 * @return int id saved
	 */
	function messageSave($data) {
		$id = $this->core_model->saveData ( TABLE_PREFIX . 'messages', $data );
		$this->core_model->cleanCacheGroup ( 'users/messages' );
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
		$q = "SELECT * from $table where parent_id={$msg_id}  ";
		$cache_group = 'users/messages';
		$cache_group_id = __FUNCTION__ . md5 ( $q );
		$resutlt = $this->core_model->dbQuery ( $q, $cache_group_id, $cache_group );
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
		$q = "SELECT * from $table where id={$msg_id} ";
		
		$resutlt = $this->core_model->dbQuery ( $q );
		//var_dump($resutlt);
		if (empty ( $resutlt )) {
			return false;
		} else {
			foreach ( $resutlt as $item ) {
				$messages [] = $item;
				$more = $this->messagesGetByParent ( $item ['id'] );
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
		
		$data = $this->messagesGetByParams ( $params );
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
			$currentUserId = intval ( $this->core_model->userId () );
			if ($this->core_model->userId () == 0) {
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
		$options ['cache_group'] = 'users/messages';
		if (! empty ( $db_options )) {
			foreach ( $db_options as $k => $v ) {
				$options ["{$k}"] = $v;
			}
		}
		
		$data = $this->core_model->fetchDbData ( $table, $params, $options );
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
		$options ['cache_group'] = 'users/messages/';
		
		if (! empty ( $db_options )) {
			foreach ( $db_options as $k => $v ) {
				$options ["{$k}"] = $v;
			}
		}
		
		$data = $this->core_model->fetchDbData ( $table, $params, $options );
		return $data;
	}

}
