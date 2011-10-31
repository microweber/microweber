<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Notifications_model extends Model {
	
	function __construct() {
		parent::Model ();
		//$this->_initActivitiesTypes ();
		

		$this->notificationsParseFromLog ();
	
	}
	
	/**
	 * Deletes notification by id
	 * @param int id
	 * @return true
	 */
	function notificationDeleteById($id) {
		global $cms_db_tables;
		$table = $cms_db_tables ['table_users_notifications'];
		$data = array ();
		$data ['id'] = $id;
		$del = CI::model('core')->deleteData ( $table, $data, 'users/notifications' );
		CI::model('core')->cleanCacheGroup ( 'users/notifications/' );
		return true;
	}
	
	/**
	 * Generic notification saving funtion
	 * @param array
	 * @return int id saved
	 */
	function notificationSave($data, $no_cache_clean = false) {
		global $cms_db_tables;
		$table = $cms_db_tables ['table_users_notifications'];
		$id = CI::model('core')->saveData ( $table, $data );
		if ($no_cache_clean == false) {
			CI::model('core')->cleanCacheGroup ( 'users/notifications/' );
		}
		return $id;
	}
	
	/**
	 * Get notifications by default  params
	 * @param array $params
	 * @param array|false $options
	 * @return array
	 */
	function notificationsGetByDefaultParams($params, $db_options = false) {
		if (empty ( $params )) {
			//return '$params are empty.. pls define some $params';+
			

			$params = array ();
			$params [] = array ('to_user', CI::model('core')->userId () );
			$params [] = array ("log_id", "0", '>', 'and', true );
			$params [] = array ("is_read", "n" );
		}
		
		global $cms_db_tables;
		$table = $cms_db_tables ['table_users_notifications'];
		
		$options = array ();
		$options ['get_params_from_url'] = true;
		$options ['debug'] = false;
		$options ['items_per_page'] = 20;
		//$options ['group_by'] = 'to_table, to_table_id,from_user, type';
		

		$options ['group_by'] = 'to_table, to_table_id,from_user';
		$options ['order'] = array ('created_on', 'DESC' );
		
		$options ['cache'] = true;
		$options ['cache_group'] = 'users/notifications/';
		
		if (! empty ( $db_options )) {
			foreach ( $db_options as $k => $v ) {
				$options ["{$k}"] = $v;
			}
		}
		$options ['only_fields'] = array ('id' );
		
		$data = CI::model('core')->fetchDbData ( $table, $params, $options );
		if (! empty ( $data )) {
			$data2 = array ();
			foreach ( $data as $item ) {
			
			}
			
			return $data2;
		} else {
			return false;
		}
	
	}
	
	/**
	 * Get notifications by given params
	 * @param array $params
	 * @param array|false $options
	 * @return array
	 */
	function notificationsGetByParams($params, $db_options = false) {
		if (empty ( $params )) {
			return false;
		}
		
		global $cms_db_tables;
		$table = $cms_db_tables ['table_users_notifications'];
		
		$options = array ();
		$options ['get_count'] = false;
		$options ['debug'] = false;
		$options ['cache'] = true;
		$options ['cache_group'] = 'users/notifications';
		
		if (! empty ( $db_options )) {
			foreach ( $db_options as $k => $v ) {
				$options ["{$k}"] = $v;
			}
		}
		
		$data = CI::model('core')->fetchDbData ( $table, $params, $options );
		return $data;
	}
	
	/**
	 * Get new notifications count for give user ID
	 * @param int $user_id user id
	 * @return int msg count
	 */
	function notificationsGetUnreadCountForUser($user_id = false) {
		if ($user_id == false) {
			
			$user_id = CI::model('core')->userId ();
		}
		$params = array ();
		$params [] = array ('to_user', $user_id );
		$params [] = array ('is_read', 'n' );
		$params [] = array ("log_id", "0", '>', 'and', true );
		
		//$options = array ('get_count' => true, 'debug' => false, 'cache' => true, 'cache_group' => 'users/notifications' );
		$options ['get_count'] = false;
		$options ['debug'] = false;
		$options ['group_by'] = 'to_table, to_table_id';
		$options ['cache'] = true;
		$options ['cache_group'] = 'users/notifications';
		$notificationsGetUnreadCountForUser = $this->notificationsGetByDefaultParams ( $params, $options );
		//p ( $notificationsGetUnreadCountForUser );
		return intval ( count ( $notificationsGetUnreadCountForUser ) );
	}
	
	/**
	 * Clean old notifications
	 * @param int $aHistoryLenght Number of readed messages kepd for history
	 * @todo: put this method on notifications page
	 */
	public function cleanOldNotifications($aHistoryLenght) {
		$timeFile = CACHEDIR . 'interval_' . __FUNCTION__;
		if (! file_exists ( $timeFile )) {
			touch ( $timeFile );
		}
		
		if (filemtime ( $timeFile ) < strtotime ( "-10 minutes" )) {
			
		//TODO: each user has to have up to $aHistoryLenght readed notifications
		

		}
	
	}
	
	/**
	 * Parse notifications from the log
	 */
	public function notificationsParseFromLog() {
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
		
		if (filemtime ( $timeFile ) < strtotime ( "-30 seconds" )) {
			@touch ( $timeFile );
			$query_options = array ();
			$query_options ['debug'] = false;
			$query_options ['get_params_from_url'] = false;
			$query_options ['items_per_page'] = 10;
			//$query_options ['debug'] = true;
			//	$query_options ['group_by'] = 'to_table, to_table_id';
			

			//p($dashboard_following);
			

			$log_params = array ();
			$log_params ["for_user_ids"] = array ($user_id );
			
			$log_params [] = array ("to_table", " ('table_comments', 'table_followers', 'table_votes') ", 'in', 'and', true );
			
			$log_params [] = array ("created_by", $user_id );
			$log_params [] = array ("notifications_parsed", 'n' );
			
			$log = $this->logGetByParams ( $log_params, $query_options );
			
			if (! empty ( $log )) {
				$ids = CI::model('core')->dbExtractIdsFromArray ( $log );
				$table_log = TABLE_PREFIX . 'users_log';
				$ids_implode = implode ( ',', $ids );
				$q = " UPDATE  $table_log set notifications_parsed='y' where id in($ids_implode) ";
				//p ( $q );
				$q = CI::model('core')->dbQ ( $q );
				
				foreach ( $log as $entry ) {
					switch ($entry ['to_table']) {
						case 'table_followers' :
							$data = array ();
							$data [] = array ('id', intval ( $entry ['to_table_id'] ) );
							$data = CI::model('core')->fetchDbData ( $entry ['to_table'], $data );
							if (empty ( $data [0] )) {
								//$this->notificationDeleteById ( $entry ['id'] );
								$this->logDeleteById ( $entry ['id'] );
							} else {
								$data = $data [0];
								$to_save = array ();
								$to_save ['to_table'] = $entry ['to_table'];
								$to_save ['to_table_id'] = $entry ['to_table_id'];
								$to_save ['log_id'] = $entry ['id'];
								
								$to_save ['from_user'] = $data ['user_id'];
								$to_save ['to_user'] = $data ['follower_id'];
								if ($data ['is_special'] == 'y') {
									$to_save ['type'] = 'special';
								}
								if (intval ( $to_save ['to_user'] ) != 0 and ($to_save ['from_user'] != $to_save ['to_user'])) {
									$to_save = $this->notificationSave ( $to_save );
								}
								//$to_save = $this->notificationSave ( $to_save );
							//p ( $to_save );
							//p ( $data );
							}
							
							break;
						
						case 'table_comments' :
						case 'table_votes' :
							$data = array ();
							$data_params [] = array ('id', intval ( $entry ['to_table_id'] ) );
							$data = CI::model('core')->fetchDbData ( $entry ['to_table'], $data_params );
							
							if (empty ( $data [0] )) {
								//$this->notificationDeleteById ( $entry ['id'] );
								$this->logDeleteById ( $entry ['id'] );
							} else {
								
								$totable_id = false;
								$data = $data [0];
								$content_data = false;
								
								if ($data ['to_table'] == $entry ['to_table']) {
									$this->logDeleteById ( $entry ['id'] );
								} else {
									if ($data ['to_table'] == 'table_content') {
										$content_data = CI::model('content')->contentGetByIdAndCache ( $data ['to_table_id'] );
										$totable_id = $content_data ['id'];
									}
									if ($data ['to_table'] != 'table_content') {
										$totable_id = $data ['id'];
									}
									
									$to_save = array ();
									$to_save ['to_table'] = $entry ['to_table'];
									$to_save ['to_table_id'] = $totable_id;
									$to_save ['from_user'] = intval ( $data ['created_by'] );
									$to_save ['to_user'] = intval ( $content_data ['created_by'] );
									$to_save ['message'] = $data ['comment_body'];
									$to_save ['log_id'] = $entry ['id'];
									//if ($data ['is_special'] == 'y') {
									$to_save ['type'] = $content_data ['content_type'];
									$to_save ['subtype'] = $content_data ['content_subtype'];
									$to_save ['subtype_value'] = $content_data ['content_subtype_value'];
									//	p($to_save);
									if (intval ( $to_save ['to_user'] ) != 0 and ($to_save ['from_user'] != $to_save ['to_user'])) {
										$to_save = $this->notificationSave ( $to_save );
									}
								}
							}
							
							break;
						
						default :
							error_log ( 'Not implemented yet' . __FILE__ . __LINE__ );
							print 'Not implemented yet';
							//var_dump ( __FILE__ );
							//var_dump ( __LINE__ );
							//p ( $entry );
							break;
					
					}
				
				}
			
			}
			
		//p ( $log );
		

		}
	
	}
	
	public function buildNotificationMessage($aNotificationType, $aBuildParams) {
		return false;
	
	}
	
	public function sendNotification($aNotification) {
		//@todo clean users cache
		if (CI::model('core')->optionsGetByKey ( 'enable_notifications' )) {
			
			if (! isset ( $aNotification ['message_params'] )) {
				$aNotification ['message_params'] = array ();
			}
			
			$aNotification ['message'] = $this->buildNotificationMessage ( $aNotification ['type'], $aNotification ['message_params'] );
			
			unset ( $aNotification ['message_params'] );
			
			CI::model('core')->saveData ( TABLE_PREFIX . 'users_notifications', $aNotification );
		
		} else {
			// notifications disabled
		}
		CI::model('core')->cleanCacheGroup ( 'log/global' );
	
	}
	
	/**
	 * Get messages by given params
	 * @param array $params - standard db params
	 * Extra params:
	 * $params['for_user_ids'] = array(1,2); - you can define user ids array to get log for
	 *
	 *
	 * @param array|false standart $db_options
	 * @return array
	 */
	function logGetByParams($params, $db_options = false) {
		
		if (empty ( $params )) {
			//return false;
		}
		global $cms_db_tables;
		$table = $cms_db_tables ['table_users_log'];
		//$params [] = array ('to_user', $user_id );
		//$params [] = array ('is_read', 'n' );
		//$params [] = array ('deleted_from_receiver', 'n' );
		$query_options = array ();
		//$query_options ['only_fields'] = array ('follower_id', 'user_id' );
		

		if (! empty ( $params ['for_user_ids'] )) {
			$for_users = $params ['for_user_ids'];
			unset ( $params ['for_user_ids'] );
			$query_options ['include_ids_field'] = 'user_id';
			$query_options ['include_ids'] = $for_users;
		}
		
		//$query_options ['debug'] = false;
		$query_options ['get_params_from_url'] = false;
		$query_options ['cache'] = true;
		$query_options ['page'] = $params ['page'];
		$query_options ['cache_group'] = 'log/global';
		$dir = CI::model('core')->_getCacheDir ( 'log/global/' );
		$c_file = $dir . 'skip_cache.php';
		if (is_file ( $c_file )) {
			
			$filemtime = @filemtime ( $c_file ); // returns FALSE if file does not exist
			$cache_life = '10'; //caching time, in seconds
			if (! $filemtime or (time () - $filemtime <= $cache_life)) {
				//$query_options ['cache'] = false;
				//$query_options ['cache_group'] = false;
				@touch ( $c_file );
			
			} else {
			
			}
			
		//$query_options ['cache'] = false;
		//$query_options ['cache_group'] = false;
		

		}
		
		$query_options ['order'] = array ('created_on', 'DESC' );
		//	$query_options ['only_fields'] = array ('id' ); // array of fields
		// $query_options ['cache_group'] = 'users/relations';
		if (! empty ( $db_options )) {
			foreach ( $db_options as $k => $v ) {
				$query_options ["{$k}"] = $v;
			}
		}
		
		$data = CI::model('core')->fetchDbData ( $table, $params, $query_options );
		return $data;
		if (! empty ( $data )) {
			$arr = array ();
			foreach ( $data as $dat ) {
				$arr [] = $dat ['id'];
			}
		}
		
		return $arr;
	}
	
	function logGetById($id) {
		
		$id = intval ( $id );
		if ($id == 0) {
			
			return false;
		}
		global $cms_db_tables;
		$table = $cms_db_tables ['table_users_log'];
		
		$q = "SELECT * from $table where id='{$id}'  limit 0,1 ";
		// var_dump($q);
		$q = CI::model('core')->dbQuery ( $q, __FUNCTION__ . md5 ( $q ), 'log/' . $id );
		//var_dump ( $q );
		$content = $q [0];
		
		return $content;
	
	}
	
	/**
	 * Deletes log entry by id
	 * @param int id
	 * @return true
	 */
	function logDeleteById($id) {
		$id = intval ( $id );
		if ($id == 0) {
			
			return false;
		}
		global $cms_db_tables;
		$table = $cms_db_tables ['table_users_log'];
		$data = array ();
		$data ['id'] = $id;
		$del = CI::model('core')->deleteData ( $table, $data );
		CI::model('core')->cleanCacheGroup ( 'log/' . $id );
		return true;
	}

}
