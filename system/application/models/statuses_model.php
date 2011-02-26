<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Statuses_model extends Model {
	
	function __construct() {
			parent::Model ();
	
	}
	
	/**
	 * Get last status row for given User Id
	 * @param int
	 * @return array
	 */
	function statusesLastByUserId($id = false) {
		
		if (intval ( $id ) == 0) {
			
			$id = CI::model('core')->userId ();
		
		}
		
		$params = array ();
		$params [] = array ('user_id', $id );
		
		$options = array ();
		$options ['limit'] = 1;
		
		$options ['order'] = array ('id', 'desc' );
		$options ['cache'] = false;
		$options ['use_cache'] = false;
		
		$status_res = $this->statusesByParams ( $params, $options );
		$status = $status_res [0];
		return $status;
	
	}
	
	/**
	 * Get last status row for given User Id
	 * @param int
	 * @return array
	 */
	function statusGetById($id = false) {
		
		$params = array ();
		$params [] = array ('id', $id );
		
		$options = array ();
		$options ['limit'] = 1;
		$options ['order'] = array ('created_on', 'DESC' );
		
		$status = $this->statusesByParams ( $params, $options );
		
		$status = $status [0];
		return $status;
	
	}
	
public function cleanOldStatuses($aUser, $aHistoryLength = 100) {
		$oldStatuses = CI::model('core')->fetchDbData ( TABLE_PREFIX . 'users_statuses', array (array ('user_id', $aUser ['id'] ) ), array ('only_fields' => array ('id' ), 'oder' => array ('created_on' ), 'limit' => 100000, //no limit
'offset' => $aHistoryLength ) );
		
		if ($oldStatuses) {
			
			$oldStatusesIds = array ();
			foreach ( $oldStatuses as $status ) {
				$oldStatusesIds [] = $status ['id'];
			}
			
			CI::model('core')->db->query ( "DELETE FROM " . TABLE_PREFIX . 'users_statuses' . " WHERE id IN (" . implode ( ',', $oldStatusesIds ) . ")" );
			CI::model('core')->cleanCacheGroup ( 'users/statuses' );
		}
	
	}
	
	/**
	 * Get  statuses for given params and return as array
	 * @param array $params
	 * @param array $options
	 * @return array array of ids
	 */
	function statusesByParams($params, $options = false) {
		if (empty ( $params )) {
			return false;
		}
		global $cms_db_tables;
		$table = $cms_db_tables ['table_users_statuses'];
		//array (array ('user_id', $currentUser ['id'] ), array ('follower_id', $currentUser ['id'], '=', 'OR' ) ) );
		

		//$params [] = array ('to_user', $user_id );
		//$params [] = array ('is_read', 'n' );
		//$params [] = array ('deleted_from_receiver', 'n' );
		

		$defalut_options = array ();
		$defalut_options ['get_count'] = false;
		$defalut_options ['debug'] = false;
		$defalut_options ['cache'] = true;
		$defalut_options ['cache_group'] = 'users/statuses/';
		
		foreach ( $options as $k => $item ) {
			$defalut_options [$k] = $item;
		}
		
		$data = CI::model('core')->fetchDbData ( $table, $params, $defalut_options );
		return $data;
	}

}
