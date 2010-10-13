<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Users_model extends Model {
	
	private $_notificationTypes;
	public $user_id;
	
	function __construct() {
		parent::Model ();
	
	}
	
	protected function _initActivitiesTypes() {
		//	$this->_notificationTypes = array ('comment_on_post' => 'commented on your post: <a href="{content_url}">{content_title}</a>', 'vote_on_post' => 'voted on your post: <a href="{content_url}">{content_title}</a>', 'comment_on_status' => 'commented on your <a href="{status_url}">status</a>', 'vote_on_status' => 'liked your <a href="{status_url}">status</a>', 'add_to_followers' => 'followed you', 'add_to_circle' => 'added you to you <a href="{circle_url}">his/her circle of influence</a>' );
	}
	
	function checkUser($field_criteria, $username) {
		global $cms_db_tables;
		$table = $cms_db_tables ['table_users'];
		$q = " select count(*) as qty from $table where $field_criteria like '%$username%' ";
		$q = $this->db->query ( $q );
		$q = $q->row_array ();
		$q = intval ( $q ['qty'] );
		if ($q == 0) {
			return false;
		} else {
			return true;
		}
	
	}
	
	function getUsersOnlineUsersCount() {
		global $cms_db_tables;
		$table = $cms_db_tables ['table_sessions'];
		
		$strtotime = strtotime ( '+0 minutes' );
		$strtotime2 = mktime ( strtotime ( '-15 minutes' ) );
		
		$q = " select count(*) as qty from $table where ('last_activity')>('$strtotime2')  ";
		
		//print $q;
		

		$q = $this->db->query ( $q );
		$q = $q->row_array ();
		$q = intval ( $q ['qty'] );
		if ($q == 0) {
			return 0;
		} else {
			return $q;
		}
	
	}
	
	function getUsersCount() {
		
		global $cms_db_tables;
		$table = $cms_db_tables ['table_users'];
		
		$options = array ();
		$options ['get_count'] = true;
		$options ['debug'] = false;
		$options ['cache'] = true;
		$options ['cache_group'] = 'users/global/';
		
		$params = " select count(*) as qty from $table where is_active='y'  ";
		$data = $this->core_model->fetchDbData ( $table, $params, $options );
		
		//	p($data);
		

		return $data;
	
	}
	
	function checkUserPassById($id, $pass) {
		global $cms_db_tables;
		$table = $cms_db_tables ['table_users'];
		$q = " select count(*) as qty from $table where password like '%$pass%' and id='$id' ";
		$q = $this->db->query ( $q );
		$q = $q->row_array ();
		$q = intval ( $q ['qty'] );
		if ($q == 0) {
			return false;
		} else {
			return true;
		}
	
	}
	
	function saveUser($data) {
		global $cms_db_tables;
		$table = $cms_db_tables ['table_users'];
		if ($_FILES) {
		
		}
		$save = $this->core_model->saveData ( $table, $data );
		
		if (intval ( $data ['id'] ) != 0) {
			$this->core_model->cleanCacheGroup ( 'users/' . $data ['id'] );
		}
		
		$this->core_model->cleanCacheGroup ( 'users/global' );
		
		return $save;
	}
	
	/**
	 * Generic function to get the user by id. Uses the getUsers function to get the data
	 * @param int id
	 * @return array
	 *
	 */
	function getUserById($id) {
		$id = intval ( $id );
		if ($id == 0) {
			return false;
		}
		
		$data = array ();
		$data ['id'] = $id;
		$data = $this->getUsers ( $data );
		$data = $data [0];
		return $data;
	}
	
	/**
	 * Generic function to get the users by db params defined in $data
	 * @param $data
	 * @param $limit
	 * @param $count_only
	 * @return array
	 *
	 */
	function getUsers($data = false, $limit = false, $count_only = false) {
		global $cms_db_tables;
		$table = $cms_db_tables ['table_users'];
		//$q = $this->core_model->dbQuery ( $q, md5 ( $q ), 'comments' );
		$data = codeClean ( $data );
		//var_dump($data);
		

		if ($limit == false) {
			$limit = array (0, 30 );
		}
		//getDbData($table = false, $criteria = false, $limit = false, $offset = false, $orderby = false, $cache_group = false, $debug = false, $ids = false, $count_only = false, $only_those_fields = false, $exclude_ids = false, $force_cache_id = false, $get_only_whats_requested_without_additional_stuff = false)
		if (is_array ( $data ['ids'] )) {
			if (! empty ( $data ['ids'] )) {
				$ids = $data ['ids'];
			}
		}
		
		//p($data);
		

		$data ['search_by_keyword_in_fields'] = array ('first_name', 'last_name', 'username', 'email' );
		
		if (intval ( $data ['id'] ) != 0) {
			$cache_group = 'users/' . $data ['id'];
		} else {
			
			$cache_group = 'users/global';
		}
		
		$get = $this->core_model->getDbData ( $table, $criteria = $data, $limit, $offset = false, $orderby = array ('updated_on', 'DESC' ), $cache_group, $debug = false, $ids, $count_only = $count_only, $only_those_fields = false, $exclude_ids = false, $force_cache_id = false, $get_only_whats_requested_without_additional_stuff = true );
		
		return $get;
	}
	
	function getUserThumbnail($user_id, $size = 128) {
		$image = $this->core_model->mediaGetThumbnailForItem ( 'table_users', $to_table_id = $user_id, $size = $size, $order_direction = "DESC" );
		return $image;
	}
	
	function getUserPictureInfo($user_id) {
		
		//mediaGet($to_table, $to_table_id, $media_type = false, $order = "ASC", $queue_id = false, $no_cache = false, $id = false)
		

		$image = $this->core_model->mediaGet ( 'table_users', $to_table_id = $user_id, $media_type = 'picture', $order_direction = "DESC" );
		
		return $image ["pictures"] [0];
	}
	
	/**
	 * Get user id by given username
	 * @param $username
	 * @return string | false
	 * @example
	 *
	 * $this->users_model->getIdByUsername('admin');
	 *
	 */
	function getIdByUsername($username = false) {
		
		if ($username == false) {
			return false;
		}
		
		
		
		
		
		
		
		
		
		
		
		$users_list = array ();
		$users_list ['username'] = $username;
		$users_list = $this->getUsers ( $users_list, array (0, 1 ) );
		$currentUser = $users_list [0];
		return intval ( $currentUser ['id'] );
	
	}
	
	function getUsernameById($id = false) {
		$temp = $this->getPrintableName ( $id, 'username' );
		return $temp;
	}
	
	/**
	 * Function to get user printable name by given ID
	 * @param $id
	 * @param $mode
	 * @return string
	 * @example
	 * Delete relation:
	 * $this->users_model->getPrintableName(10, 'full');
	 *
	 */
	function getPrintableName($id, $mode = 'full') {
		$user = $this->getUserById ( $id );
		$user_data = $user;
		if (empty ( $user )) {
			return false;
		}
		
		
		
		switch ($mode) {
			case 'first' :
			case 'fist' : //because of a common typo :)
				$user_data ['first_name'] ? $name = $user_data ['first_name'] : $name = $user_data ['username'];
				$name = ucwords ( $name );
				return $name;
				break;
			
			case 'last' :
				$user_data ['last_name'] ? $name = $user_data ['last_name'] : $name = $user_data ['last_name'];
				$name = ucwords ( $name );
				return $name;
				break;
			
			case 'username' :
				$name = $user_data ['username'];
				return $name;
				break;
			
			case 'full' :
			default :
				$name = $user_data ['first_name'] . ' ' . $user_data ['last_name'];
				
				if (trim ( $name ) == '') {
					$name = $user_data ['username'];
				}
				
				$name = ucwords ( $name );
				return $name;
				
				break;
		
		}
		exit ();
	}
	
	function userDeleteById($id) {
		global $cms_db_tables;
		$table = $cms_db_tables ['table_users'];
		$data = array ();
		$data ['id'] = $id;
		$del = $this->core_model->deleteData ( $table, $data );
		$this->core_model->cleanCacheGroup ( 'users/global' );
		$this->core_model->cleanCacheGroup ( 'users/'.$id );
 
		
		return true;
	}
	
	/**
	 * Get user id
	 * @return id
	 *
	 */
	function userId() {
		return $this->core_model->userId ();
	
	}
	
	function sendMail($opt = array()) {
		if (empty ( $opt ))
			return false;
		
		$to = $opt ['email'];
		$admin_options = $this->core_model->optionsGetByKey ( 'admin_email', true );
		
		$from = (empty ( $admin_options )) ? 'noreply@ooyes.net' : $admin_options ['option_value'];
		
		$object = $opt ['object'];
		if (! $object)
			$object = 'Forgotten password';
		$total = 0;
		$message = <<<STR
		Hello, <b>{$opt ['name']}</b>!

		Here You are login details from site <b>{$opt ['site']}</b>:<br />
		Username: <b>{$opt ['username']}</b><br />
		Password: <b>{$opt ['password']}</b><br />
		<p>
		Have a nice day!
STR;
		
		@mail ( $to, $object, $message, "From: $from\nReply-To: $from\nContent-Type: text/html;charset=\"windows-1251\"\nContent-Transfer-Encoding: 8bit" );
	}
	
	/*~~~~~~~~~~ Followers methods ~~~~~~~~~~~~*/
	
	/**
	 * Save follower relations
	 * @param array $aData Data to be saved.
	 * @return bool
	 * @example
	 * Delete relation:
	 * $this->users_model->saveFollower(array(
	 * 'user' => <<currentUser>>['id'],
	 * 'follower' => 100000,
	 * 'follow' => false,
	 * ));
	 *
	 * Add special follower:
	 * $this->users_model->saveFollower(array(
	 * 'user' => <<currentUser>>['id'],
	 * 'follower' => 100000,
	 * 'special' => true,
	 * ));
	 *
	 */
	public function saveFollower($aData) {
		$allowedOptions = array ('follower' => false, 'user' => false, 'follow' => false, 'special' => false );
		$aData = array_intersect_key ( $aData, $allowedOptions );
		
		$followerId = $aData ['follower'];
		$userId = $aData ['user'];
		$follow = isset ( $aData ['follow'] ) ? $aData ['follow'] : true;
		$isSpecial = isset ( $aData ['special'] ) ? $aData ['special'] : false;
		
		$return = null;
		if ($followerId == $userId) {
			$return = 'You can not follow yourself.';
		}
		if ($follow) {
			$table = TABLE_PREFIX . 'followers';
			
			//@XXX $existsCheck = $this->core_model->fetchDbData ( TABLE_PREFIX . 'followers', array (array ('user_id', $userId ), array ('follower_id', $followerId ) ), array ('only_fields' => array ('id' ) ) );
			

			$params = array (array ('user_id', $userId ), array ('follower_id', $followerId ) );
			$opts = array ('only_fields' => array ('id' ) );
			
			$existsCheck = $this->core_model->fetchDbData ( $table, $params, $opts );
			//p($existsCheck, 1);
			

			$saveData = array ('user_id' => $userId, 'follower_id' => $followerId, 'is_special' => ($isSpecial ? 'y' : 'n') );
			if (! empty ( $existsCheck [0] )) {
				if (count ( $existsCheck ) > 1) {
					$i = 1;
					foreach ( $existsCheck as $item ) {
						if (! empty ( $item )) {
							$id_del = intval ( $item [$i] ['id'] );
							$del_q = "DELETE FROM $table where id=$id_del";
							$this->core_model->dbQ ( $del_q );
							
							$i ++;
						}
					}
				}
				
				$saveData ['id'] = $existsCheck [0] ['id'];
			}
			
			$return = $this->core_model->saveData ( TABLE_PREFIX . 'followers', $saveData );
		
		} else {
			
			$return = $this->core_model->deleteData ( TABLE_PREFIX . 'followers', array ('user_id' => $userId, 'follower_id' => $followerId ) );
		}
		
		$this->core_model->cleanCacheGroup ( 'users/relations' );
		return $return;
	}
	
	/**
	 * Generic funtion to get the people a user follows
	 * @param int | false - $user_id user id to get Following uses for - if false the curent user id (if logged) is used
	 * @param bool $aOnlyIds - if true will return only an array with follower_id's
	 * @param bool $db_options - you can pass standart $db_options array
	 * @return array msg
	 */
	public function getFollowing($aUserId = false, $aOnlyIds = false, $db_options = false) {
		exit ( 'Use the $this->users_model->realtionsGetFollowedIdsForUser function instead' );
		if ($aUserId == false) {
			//	$user_session = $this->session->userdata ( 'user_session' );
			//p($user_session);
			$aUserId = $this->core_model->userId ();
		}
		
		$table = TABLE_PREFIX . 'followers';
		
		$db_options = array ();
		$db_options ['only_fields'] = array ('follower_id', 'user_id' );
		$db_options ['cache'] = true;
		$db_options ['debug'] = true;
		$db_options ['get_params_from_url'] = true;
		$db_options ['cache_group'] = 'users/relations';
		if (! empty ( $db_options )) {
			foreach ( $db_options as $k => $v ) {
				$db_options [$k] = $v;
			}
		}
		$followers = $this->core_model->fetchDbData ( $table, array (array ('follower_id', $aUserId ) ), $db_options );
		//p($followers);
		$return = array ();
		
		if ($aOnlyIds) {
			foreach ( $followers as $follower ) {
				$return [] = $follower ['user_id'];
			}
		} else {
			$return = $followers;
		}
		return $return;
	}
	
	/**
	 * Generic funtion to get followers
	 * @param int | false - $user_id user id to get followers for - if false the curent user id (if logged) is used
	 * @param bool $aOnlyIds - if true will return only an array with follower_id's
	 * @param bool $db_options - you can pass standart $db_options array
	 * @return array msg
	 */
	public function getFollowers($aUserId = false, $aOnlyIds = false, $db_options = false) {
		
		if ($aUserId == false) {
			
			$aUserId = $this->core_model->userId ();
		}
		
		$table = TABLE_PREFIX . 'followers';
		
		$db_options1 = array ();
		$db_options1 ['only_fields'] = array ('follower_id', 'user_id' );
		
		$db_options1 ['debug'] = false;
		$db_options1 ['get_params_from_url'] = true;
		$db_options1 ['cache'] = true;
		$db_options1 ['cache_group'] = 'users/relations';
		if (! empty ( $db_options )) {
			foreach ( $db_options as $k => $v ) {
				$db_options1 [$k] = $v;
			}
		}
		$followers = $this->core_model->fetchDbData ( $table, array (array ('user_id', $aUserId ) ), $db_options1 );
		//p($followers);
		$return = array ();
		
		if ($aOnlyIds) {
			foreach ( $followers as $follower ) {
				$return [] = $follower ['follower_id'];
			}
		} else {
			$return = $followers;
		}
		return $return;
	}
	
	/**
	 * Get the ids of all Followers for given user id and return them as array of ids
	 * @param int $id user id
	 * @param array $params more db params
	 * @return array
	 */
	function realtionsCheckIfUserIsFollowedByUser($curent_user = false, $relation_with_user, $is_special = false) {
		if ($curent_user == false) {
			
			$curent_user = $this->core_model->userId ();
			;
		}
		$params = array ();
		$params [] = array ('user_id', intval ( $curent_user ) );
		$params [] = array ('follower_id', intval ( $relation_with_user ) );
		
		if (intval ( $curent_user ) == intval ( $relation_with_user )) {
			return true;
		}
		
		if ($is_special == true) {
			//$params ['is_special'] = 'y';
			$params [] = array ('is_special', 'y' );
		}
		
		$options = array ();
		$options ['get_count'] = true;
		$options ['cache'] = true;
		$options ['cache_group'] = 'users/relations';
		//p($params);
		$check = $this->realtionsGetByParams ( $params, $options );
		if (intval ( $check ) != 0) {
			return true;
		} else {
			return false;
		}
		//p($check,1);
	}
	
	/**
	 * Get the ids of all Followers for given user id and return them as array of ids
	 * @param int $id user id
	 * @param array $params more db params
	 * @return array
	 */
	function realtionsGetFollowersIdsForUser($aUserId = false, $special = false, $query_options = false) {
		if ($aUserId == false) {
			
			$aUserId = $this->core_model->userId ();
		}
		
		$params = array ();
		$params [] = array ('follower_id', $aUserId );
		
		if ($special == 'y') {
			//$params ['is_special'] = 'y';
			$params [] = array ('is_special', 'y' );
		}
		if ($special == 'n') {
			$params [] = array ('is_special', 'n' );
			//$params ['is_special'] = 'n';
		}
		$options = array ();
		$options ['only_fields'] = array ('id', 'user_id', 'follower_id', 'is_special' );
		$options ['return_only_ids'] = false;
		$options ['cache'] = true;
		$options ['cache_group'] = 'users/relations';
		if (! empty ( $query_options )) {
			foreach ( $query_options as $k => $v ) {
				$options ["{$k}"] = $v;
			}
		}
		//	p($options);
		$return = $this->realtionsGetByParams ( $params, $options );
		$ids = array ();
		if (is_string ( $return )) {
			return $return;
		}
		
		if (empty ( $return )) {
			return false;
		} else {
			foreach ( $return as $relation ) {
				//if (intval ( $aUserId ) == intval ( $relation ['user_id'] )) {
				$ids [] = $relation ['user_id'];
				//}
			}
		}
		return $ids;
	}
	
	/**
	 * Get the ids of all Followed users (users that the $aUserId is folowing  ) ids for given user id and return them as array of ids
	 * @param int $id user id
	 * @param string $special returns the special (circle of influence)
	 * @param array $query_options db options
	 * @return array
	 */
	function realtionsGetFollowedIdsForUser($aUserId = false, $special = false, $query_options = false) {
		
		if ($aUserId == false) {
			
			$aUserId = $this->core_model->userId ();
			;
		}
		
		$params = array ();
		//$params [] = array ('follower_id', $aUserId );
		$params [] = array ('user_id', $aUserId );
		
		if (strval ( $special ) == 'y') {
			//$params ['is_special'] = 'y';
			$params [] = array ('is_special', 'y' );
		}
		if (strval ( $special ) == 'n') {
			$params [] = array ('is_special', 'n' );
			//$params ['is_special'] = 'n';
		}
		$options = array ();
		$options ['only_fields'] = array ('id', 'user_id', 'follower_id', 'is_special' );
		$options ['return_only_ids'] = false;
		$options ['group_by'] = 'follower_id';
		$options ['cache'] = true;
		//$options ['debug'] = true;
		$options ['cache_group'] = 'users/relations';
		if (! empty ( $query_options )) {
			foreach ( $query_options as $k => $v ) {
				$options ["{$k}"] = $v;
			}
		}
		//p($options);
		$return = $this->realtionsGetByParams ( $params, $options );
		$ids = array ();
		
		if (is_string ( $return )) {
			return $return;
		}
		
		if (empty ( $return )) {
			return false;
		} else {
			foreach ( $return as $relation ) {
				//if (intval ( $aUserId ) == intval ( $relation ['user_id'] )) {
				$ids [] = $relation ['follower_id'];
				//	}
			}
		}
		
		return $ids;
	}
	
	/**
	 * Relations get updates from friends
	 * @param int $id user id
	 * @return array
	 */
	function realtionsGetUpdatesFromFriends($id, $updates_type = 'statuses') {
		$id = intval ( $id );
		
		$friendsIds = $this->realtionsGetFollowersIdsForUser ( $id );
		
		if (empty ( $friendsIds )) {
			return false;
		}
		if ($updates_type == 'statuses') {
			
			/*	$latestStatusUpdates = $this->core_model->fetchDbData ( TABLE_PREFIX . 'users_statuses',
			array (array ('user_id', '(' . implode ( ',', $friendsIds ) . ')', 'IN' ) ), array ('order' => array ('created_on', 'DESC' ), 'limit' => 10 ) );
			*/
			
			$table = TABLE_PREFIX . 'users_statuses';
			$params [] = array ('user_id', '(' . implode ( ',', $friendsIds ) . ')', 'IN' );
			
			$options = array ();
			$options ['order'] = array ('created_on', 'DESC' );
			$options ['limit'] = 10;
			$options ['get_count'] = false;
			$options ['cache'] = true;
			$options ['cache_group'] = 'users/statuses/';
			
			$data = $this->core_model->fetchDbData ( $table, $params, $options );
			return $data;
		}
		
		if ($updates_type == 'posts') {
			//	$latestPosts = $this->core_model->fetchDbData ( TABLE_PREFIX . 'content', array (array ('created_by', '(' . implode ( ',', $friendsIds ) . ')', 'IN' ) ), array ('order' => array ('created_on', 'DESC' ), 'limit' => 10 ) );
			$table = TABLE_PREFIX . 'content';
			$params [] = array ('created_by', '(' . implode ( ',', $friendsIds ) . ')', 'IN' );
			$params [] = array ('is_active', 'y' );
			
			$options = array ();
			$options ['order'] = array ('created_on', 'DESC' );
			$options ['limit'] = 10;
			$options ['get_count'] = false;
			$options ['cache'] = true;
			$options ['cache_group'] = 'content';
			
			$data = $this->core_model->fetchDbData ( $table, $params, $options );
			return $data;
		
		}
	}
	
	/**
	 * Get all relations for given user ID and return ids as array
	 * @param int $id user id
	 * @return array with ids
	 */
	function realtionsGetAllForUserId($id) {
		$id = intval ( $id );
		$params = array ();
		$params [] = array ('user_id', $id );
		$params [] = array ('follower_id', $id, '=', 'OR' );
		$data = $this->realtionsGetByParams ( $params );
		return $data;
	}
	
	/**
	 * Get  relations for given params and return ids as array
	 * @param array $params
	 * @param array $options
	 * @return array array of ids
	 */
	function realtionsGetByParams($params, $options = false) {
		if (empty ( $params )) {
			return false;
		}
		
		//array (array ('user_id', $currentUser ['id'] ), array ('follower_id', $currentUser ['id'], '=', 'OR' ) ) );
		global $cms_db_tables;
		$table = $cms_db_tables ['table_followers'];
		//$params [] = array ('to_user', $user_id );
		//$params [] = array ('is_read', 'n' );
		//$params [] = array ('deleted_from_receiver', 'n' );
		$temp = array ();
		$defalut_options = array ();
		$defalut_options ['get_count'] = false;
		//$defalut_options ['debug'] = false;
		$defalut_options ['cache'] = true;
		$defalut_options ['cache_group'] = 'users/relations';
		
		$pass_to_fetch = array ();
		$pass_to_fetch = $defalut_options;
		if (empty ( $options )) {
		
		} else {
			foreach ( $options as $k => $item ) {
				$pass_to_fetch [$k] = $item;
			}
		
		}
		//p ( $params );
		//p ( $pass_to_fetch );
		

		$data = $this->core_model->fetchDbData ( $table, $params, $pass_to_fetch );
		//p($data);
		return $data;
	}
	
	/**
	 * Get the top Commenters
	 *
	 * @return array of ids
	 */
	function rankingsTopCommenters($limit = 100) {
		$limit = intval ( $limit );
		global $cms_db_tables;
		$table = $cms_db_tables ['table_comments'];
		$q = "select created_by from $table where created_by is not null group by created_by limit 0, $limit";
		$defalut_options = array ();
		$defalut_options ['get_count'] = false;
		$defalut_options ['debug'] = false;
		$defalut_options ['cache'] = true;
		$defalut_options ['query'] = true;
		
		$defalut_options ['cache_group'] = 'users/rankings/';
		$q = $this->core_model->fetchDbData ( $table, $q, $defalut_options );
		if (empty ( $q )) {
			return false;
		} else {
			$return = array ();
			foreach ( $q as $item ) {
				$return [] = $item ['created_by'];
			}
		}
		return $return;
	
	}
	
	/**
	 * Get the top contibutors
	 *
	 * @return array of ids
	 */
	function rankingsTopContibutors($limit = 100) {
		$limit = intval ( $limit );
		global $cms_db_tables;
		$table = $cms_db_tables ['table_content'];
		$q = "select created_by from $table where created_by is not null group by created_by limit 0, $limit";
		$defalut_options = array ();
		$defalut_options ['get_count'] = false;
		$defalut_options ['debug'] = false;
		$defalut_options ['cache'] = true;
		$defalut_options ['query'] = true;
		$defalut_options ['cache_group'] = 'users/rankings/';
		$q = $this->core_model->fetchDbData ( $table, $q, $defalut_options );
		if (empty ( $q )) {
			return false;
		} else {
			$return = array ();
			foreach ( $q as $item ) {
				$return [] = $item ['created_by'];
			}
		}
		return $return;
	
	}
	
	function is_logged_in() {
		$user_session = $this->session->userdata ( 'user_session' );
		if (strval ( $user_session ['is_logged'] ) != 'yes') {
			return false;
		} else {
			return true;
		}
	}
	
	function forum_sync($user) {
		//return true;
		/*echo '<h1> USER </h1><pre>';
        print_r($user);
        echo '</pre>';*/
		
		/*
            id              => $user['id']
            user_login      => $user['username']
            user_pass       => $user['password']
            user_nicename   => $user['username']
            user_email      => $user['email']
            user_url        => $user['website']
            user_registered => $user['created_on']
            user_status     => $user['is_active']
            display_name    => $user['username']
        */
		
		$this->db->query ( 'REPLACE
                             INTO bb_users
                              SET id              = ?,
                                  user_login      = ?,
                                  user_pass       = ?,
                                  user_nicename   = ?,
                                  user_email      = ?,
                                  user_url        = ?,
                                  user_registered = ?,
                                  user_status     = ?,
                                  display_name    = ?', 

		array ($user ['id'], $user ['username'], $this->hash_password ( $user ['password'] ), $user ['username'], $user ['email'], $user ['website'], $user ['created_on'], ($user ['is_active'] == 'y' ? 0 : 1), $user ['username'] ) );
		
		$admin_cap = 'a:1:{s:9:"keymaster";b:1;}';
		$member_cap = 'a:1:{s:6:"member";b:1;}';
		
		$this->db->query ( 'REPLACE
                             INTO bb_usermeta
                              SET user_id    = ?,
                                  meta_key   = ?,
                                  meta_value = ?', array ($user ['id'], 'bb_capabilities', ($user ['is_admin'] == 'y' ? $admin_cap : $member_cap) ) );
	}
	
	function hash_password($password) {
		if (is_file ( ROOTPATH . '/forum/bb-includes/backpress/class.passwordhash.php' )) {
			require_once (ROOTPATH . '/forum/bb-includes/backpress/class.passwordhash.php');
			
			// By default, use the portable hash from phpass
			$hasher = new PasswordHash ( 8, TRUE );
			
			return $hasher->HashPassword ( $password );
		} else {
			return $password;
		}
	
	}
}
