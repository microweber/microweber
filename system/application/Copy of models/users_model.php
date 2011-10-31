<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Users_model extends Model {
	
	private $_notificationTypes;
	public $user_id;
	
	var $fb_uid = "";
	
	function __construct() {
		parent::Model ();
	
	}
	
	protected function _initActivitiesTypes() {
		//	$this->_notificationTypes = array ('comment_on_post' => 'commented on your post: <a href="{content_url}">{content_title}</a>', 'vote_on_post' => 'voted on your post: <a href="{content_url}">{content_title}</a>', 'comment_on_status' => 'commented on your <a href="{status_url}">status</a>', 'vote_on_status' => 'liked your <a href="{status_url}">status</a>', 'add_to_followers' => 'followed you', 'add_to_circle' => 'added you to you <a href="{circle_url}">his/her circle of influence</a>' );
	}
	function validate_user_facebook($uid = 0) {
		//confirm that facebook session data is still valid and matches
		$this->load->library ( 'fb_connect' );
		
		//see if the facebook session is valid and the user id in the sesison is equal to the user_id you want to validate
		$session_uid = 'fb:' . $this->fb_connect->fbSession ['uid'];
		if (! $this->fb_connect->fbSession || $session_uid != $uid) {
			return false;
		}
		
		//Receive Data
		$this->user_id = $uid;
		
		//See if User exists
		$this->db->where ( 'user_id', $this->user_id );
		$q = $this->db->get ( 'users' );
		
		if ($q->num_rows == 1) {
			//yes, a user exists,
			return true;
		}
		
		//no user exists
		return false;
	}
	
	function create_user($db_values = '') {
		$this->user_id = $db_values ["user_id"];
		$this->full_name = $db_values ["full_name"];
		$this->pwd = md5 ( $db_values ["pwd"] );
		if (strlen ( $db_values ['fb_uid'] ) > 0) {
			$this->fb_uid = $db_values ['fb_uid'];
		} else {
			$this->fb_uid = "";
		}
		
		$new_user_data = array ('user_id' => $this->user_id, 'full_name' => $this->full_name, 'pwd' => $this->pwd, 'fb_uid' => $this->fb_uid );
		p ( $new_user_data );
		print __LINE__ . __FILE__;
		exit ();
		//  $insert = $this->db->insert('users', $new_user_data);
		

		return $insert;
	}
	
	function get_user_by_fb_uid($fb_uid = 0) {
		//returns the facebook user as an array.
		global $cms_db_tables;
		$table = $cms_db_tables ['table_users'];
		
		$sql = " SELECT * FROM $table WHERE 0 = 0 AND fb_uid = ?";
		$usr_qry = CI::db()->query ( $sql, array ('fb:' . $fb_uid ) );
		
		if ($usr_qry->num_rows == 1) {
			//yes, a user exists
			return $usr_qry->result ();
		} else {
			// no user exists
			return false;
		}
	}
	
	function checkUser($field_criteria, $username) {
		global $cms_db_tables;
		$table = $cms_db_tables ['table_users'];
		$q = " select count(*) as qty from $table where $field_criteria like '%$username%' ";
		$q = CI::db()->query ( $q );
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
		

		$q = CI::db()->query ( $q );
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
		$data = CI::model('core')->fetchDbData ( $table, $params, $options );
		
		//	p($data);
		

		return $data;
	
	}
	
	function checkUserPassById($id, $pass) {
		global $cms_db_tables;
		$table = $cms_db_tables ['table_users'];
		$q = " select count(*) as qty from $table where password like '%$pass%' and id='$id' ";
		$q = CI::db()->query ( $q );
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
		if (intval ( $data ['id'] ) == 0) {
			$accounts_expiration = CI::model('core')->optionsGetByKey ( 'accounts_expiration' );
			if ($accounts_expiration != false) {
				$now = strtotime ( "now" );
				if (strtotime ( $accounts_expiration, $now ) != false) {
					$expires_on = strtotime ( $accounts_expiration, $now );
					$expires_on = date ( "Y-m-d H:i:s", $expires_on );
					
					$data ['expires_on'] = $expires_on;
				}
			}
			
		// 
		}
		if ($data ['username']) {
			$data ['username'] = str_ireplace ( ' ', '', $data ['username'] );
		}
		
		$save = CI::model('core')->saveData ( $table, $data );
		
		if (intval ( $data ['id'] ) != 0) {
			CI::model('core')->cleanCacheGroup ( 'users/' . $data ['id'] );
		}
		
		if (intval ( $data ['parent_id'] ) != 0) {
			CI::model('core')->cleanCacheGroup ( 'users/' . $data ['parent_id'] );
		}
		
		CI::model('core')->cleanCacheGroup ( 'users/global' );
		
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
		//$q = CI::model('core')->dbQuery ( $q, md5 ( $q ), 'comments' );
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
		//$data ['debug'] = 1;
		
		if (intval ( $data ['id'] ) != 0) {
			$cache_group = 'users/' . $data ['id'];
		} else {
			
			$cache_group = 'users/global';
		}
		
		if ($data ['only_those_fields']) {
			$only_those_fields = $data ['only_those_fields'];
		}
		
		$get = CI::model('core')->getDbData ( $table, $criteria = $data, $limit, $offset = false, $orderby = array ('updated_on', 'DESC' ), $cache_group, $debug = false, $ids, $count_only = $count_only, $only_those_fields, $exclude_ids = false, $force_cache_id = false, $get_only_whats_requested_without_additional_stuff = true );
		
		return $get;
	}
	
	function getUserThumbnail($user_id, $size = 128) {
		$image = CI::model('core')->mediaGetThumbnailForItem ( 'table_users', $to_table_id = $user_id, $size = $size, $order_direction = "DESC" );
		return $image;
	}
	
	function getUserPictureInfo($user_id) {
		
		//mediaGet($to_table, $to_table_id, $media_type = false, $order = "ASC", $queue_id = false, $no_cache = false, $id = false)
		

		$image = CI::model('core')->mediaGet ( 'table_users', $to_table_id = $user_id, $media_type = 'picture', $order_direction = "DESC" );
		
		return $image ["pictures"] [0];
	}
	
	/**
	 * Get user id by given username
	 * @param $username
	 * @return string | false
	 * @example
	 *
	 * CI::model('users')->getIdByUsername('admin');
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
	 * CI::model('users')->getPrintableName(10, 'full');
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
		$del = CI::model('core')->deleteData ( $table, $data );
		
		$table = $cms_db_tables ['table_followers'];
		//$options ['only_fields'] = array ('id', 'user_id', 'follower_id', 'is_special' );
		//deleteData($table, $data, $delete_cache_group = false)
		$data = array ();
		$data ['user_id'] = $id;
		CI::model('core')->deleteData ( $table, $data, $delete_cache_group = false );
		
		$data = array ();
		$data ['follower_id'] = $id;
		CI::model('core')->deleteData ( $table, $data, $delete_cache_group = false );
		
		$table = $cms_db_tables ['table_votes'];
		$data = array ();
		$data ['created_by'] = $id;
		CI::model('core')->deleteData ( $table, $data, $delete_cache_group = false );
		
		$log_table = $cms_db_tables ['table_users_log'];
		$data = array ();
		$data ['created_by'] = $id;
		CI::model('core')->deleteData ( $log_table, $data, $delete_cache_group = false );
		
		CI::model('core')->cleanCacheGroup ( 'votes/global' );
		CI::model('core')->cleanCacheGroup ( 'users/global' );
		CI::model('core')->cleanCacheGroup ( 'users/relations' );
		CI::model('core')->cleanCacheGroup ( 'users/' . $id );
		
		return true;
	}
	
	/**
	 * Get user id
	 * @return id
	 *
	 */
	function userId() {
		return CI::model('core')->userId ();
	
	}
	
	function sendMail($opt = array()) {
		if (empty ( $opt ))
			return false;
		
		$to = $opt ['email'];
		$admin_options = CI::model('core')->optionsGetByKey ( 'admin_email', true );
		
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
	 * CI::model('users')->saveFollower(array(
	 * 'user' => <<currentUser>>['id'],
	 * 'follower' => 100000,
	 * 'follow' => false,
	 * ));
	 *
	 * Add special follower:
	 * CI::model('users')->saveFollower(array(
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
		$isApproved = isset ( $aData ['is_approved'] ) ? $aData ['is_approved'] : false;
		$cancel = isset ( $aData ['cancel'] ) ? $aData ['cancel'] : false;
		
		$return = null;
		if ($followerId == $userId) {
			$return = 'You can not follow yourself.';
		}
		if ($follow) {
			$table = TABLE_PREFIX . 'followers';
			
			//@XXX $existsCheck = CI::model('core')->fetchDbData ( TABLE_PREFIX . 'followers', array (array ('user_id', $userId ), array ('follower_id', $followerId ) ), array ('only_fields' => array ('id' ) ) );
			

			$params = array (array ('user_id', $userId ), array ('follower_id', $followerId ) );
			$opts = array ('only_fields' => array ('id' ) );
			
			$existsCheck = CI::model('core')->fetchDbData ( $table, $params, $opts );
			//p($existsCheck, 1);
			

			$saveData = array ('user_id' => $userId, 'follower_id' => $followerId, 'is_special' => ($isSpecial ? 'y' : 'n'), 'is_approved' => ($isApproved ? 'y' : 'n') );
			
			if (! empty ( $existsCheck [0] )) {
				if (count ( $existsCheck ) > 1) {
					$i = 1;
					foreach ( $existsCheck as $item ) {
						if (! empty ( $item )) {
							$id_del = intval ( $item [$i] ['id'] );
							$del_q = "DELETE FROM $table where id=$id_del";
							CI::model('core')->dbQ ( $del_q );
							
							$i ++;
						}
					}
				}
				
				$saveData ['id'] = $existsCheck [0] ['id'];
			}
			
			$return = CI::model('core')->saveData ( TABLE_PREFIX . 'followers', $saveData );
			
			$params = array (array ('user_id', $followerId ), array ('follower_id', $userId ) );
			$opts = array ('only_fields' => array ('id' ) );
			$check_for_mutual = CI::model('core')->fetchDbData ( $table, $params, $opts );
			if (! empty ( $check_for_mutual [0] )) {
				$approve = "UPDATE  $table set is_approved='y' where user_id={$followerId} and follower_id={$userId}";
				CI::model('core')->dbQ ( $approve );
				
				$approve = "UPDATE  $table set is_approved='y' where user_id={$userId} and follower_id={$followerId}";
				CI::model('core')->dbQ ( $approve );
			}
		
		} else {
			
			$return = CI::model('core')->deleteData ( TABLE_PREFIX . 'followers', array ('user_id' => $userId, 'follower_id' => $followerId ) );
			if ($cancel == true) {
				$return = CI::model('core')->deleteData ( TABLE_PREFIX . 'followers', array ('user_id' => $followerId, 'follower_id' => $userId ) );
			
			}
		
		}
		
		CI::model('core')->cleanCacheGroup ( 'users/relations' );
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
		exit ( 'dep' );
		if ($aUserId == false) {
			//	$user_session = CI::library('session')->userdata ( 'user_session' );
			//p($user_session);
			$aUserId = CI::model('core')->userId ();
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
		$followers = CI::model('core')->fetchDbData ( $table, array (array ('follower_id', $aUserId ) ), $db_options );
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
			
			$aUserId = CI::model('core')->userId ();
		}
		
		if (is_array ( $aUserId )) {
			$params = $aUserId;
		} else {
			$params = array (array ('user_id', $aUserId ) );
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
		$followers = CI::model('core')->fetchDbData ( $table, $params, $db_options1 );
		//p($followers);
		$return = array ();
		
		if ($aOnlyIds) {
			
			foreach ( $followers as $follower ) {
				if (is_string ( $aOnlyIds )) {
					$return [] = $follower [$aOnlyIds];
				} else {
					
					$return [] = $follower ['follower_id'];
				}
			}
		} else {
			$return = $followers;
		}
		return $return;
	}
	
	function realtionsCheckIfUserHasFriendRequestToUser($curent_user = false, $relation_with_user, $is_special = false) {
		if ($curent_user == false) {
			
			$curent_user = CI::model('core')->userId ();
		
		}
		$params = array ();
		$params [] = array ('user_id', intval ( $curent_user ) );
		$params [] = array ('follower_id', intval ( $relation_with_user ) );
		
		if (intval ( $curent_user ) == intval ( $relation_with_user )) {
			return false;
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
		//	var_dump($check);
		if (intval ( $check ) != 0) {
			
			//check if the request is still uncofirmed
			$params = array ();
			
			$params [] = array ('follower_id', intval ( $curent_user ) );
			$params [] = array ('user_id', intval ( $relation_with_user ) );
			$check = $this->realtionsGetByParams ( $params, $options );
			//var_dump($check);
			if (intval ( $check ) == 0) {
				return true;
			} else {
				return false;
			}
		
		} else {
			
			/*$params = array ();
			$params [] = array ('follower_id', intval ( $curent_user ) );
			$params [] = array ('user_id', intval ( $relation_with_user ) );
			$check = $this->realtionsGetByParams ( $params, $options );
			if (intval ( $check ) != 0) {
				return true;
			}*/
			return false;
		}
	}
	
	function realtionsCheckIfUserIsConfirmedFriendWithUser($curent_user = false, $relation_with_user, $is_special = false) {
		if ($curent_user == false) {
			
			$curent_user = CI::model('core')->userId ();
		
		}
		
		$params = array ();
		$params [] = array ('user_id', intval ( $curent_user ) );
		$params [] = array ('follower_id', intval ( $relation_with_user ) );
		
		if (intval ( $curent_user ) == intval ( $relation_with_user )) {
			return true;
		}
		
		if ($is_special == true) {
			//$params ['is_special'] = 'y';
		//$params [] = array ('is_special', 'y' );
		}
		
		$options = array ();
		$options ['get_count'] = true;
		$options ['cache'] = true;
		$options ['cache_group'] = 'users/relations';
		//p($params);
		$check = $this->realtionsGetByParams ( $params, $options );
		if (intval ( $check ) != 0) {
			
			$params = array ();
			$params [] = array ('follower_id', intval ( $curent_user ) );
			$params [] = array ('user_id', intval ( $relation_with_user ) );
			
			$check = $this->realtionsGetByParams ( $params, $options );
			if (intval ( $check ) != 0) {
				return true;
			}
		
		}
		return false;
	}
	
	/**
	 * Get the ids of all Followers for given user id and return them as array of ids
	 * @param int $id user id
	 * @param array $params more db params
	 * @return array
	 */
	function realtionsCheckIfUserIsFollowedByUser($curent_user = false, $relation_with_user, $is_special = false) {
		if ($curent_user == false) {
			
			$curent_user = CI::model('core')->userId ();
		
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
			
			/*$params = array ();
			$params [] = array ('follower_id', intval ( $curent_user ) );
			$params [] = array ('user_id', intval ( $relation_with_user ) );
			$check = $this->realtionsGetByParams ( $params, $options );
			if (intval ( $check ) != 0) {
				return true;
			}*/
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
			
			$aUserId = CI::model('core')->userId ();
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
	 * Get the ids of all Followed users (users that the $aUserId is folowing aka is freind with  ) ids for given user id and return them as array of ids
	 * @param int $id user id
	 * @param string $special returns the special (circle of influence)
	 * @param array $query_options db options
	 * @return array
	 */
	function realtionsGetFollowedIdsForUser($aUserId = false, $special = false, $query_options = false) {
		
		if ($aUserId == false) {
			
			$aUserId = CI::model('core')->userId ();
		
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
			
			/*	$latestStatusUpdates = CI::model('core')->fetchDbData ( TABLE_PREFIX . 'users_statuses',
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
			
			$data = CI::model('core')->fetchDbData ( $table, $params, $options );
			return $data;
		}
		
		if ($updates_type == 'posts') {
			//	$latestPosts = CI::model('core')->fetchDbData ( TABLE_PREFIX . 'content', array (array ('created_by', '(' . implode ( ',', $friendsIds ) . ')', 'IN' ) ), array ('order' => array ('created_on', 'DESC' ), 'limit' => 10 ) );
			$table = TABLE_PREFIX . 'content';
			$params [] = array ('created_by', '(' . implode ( ',', $friendsIds ) . ')', 'IN' );
			$params [] = array ('is_active', 'y' );
			
			$options = array ();
			$options ['order'] = array ('created_on', 'DESC' );
			$options ['limit'] = 10;
			$options ['get_count'] = false;
			$options ['cache'] = true;
			$options ['cache_group'] = 'content';
			
			$data = CI::model('core')->fetchDbData ( $table, $params, $options );
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
			
			if ($pass_to_fetch ['search_keyword'] != false) {
				// p ( $pass_to_fetch );
				$kw_data = array ();
				$kw_data ['search_keyword'] = $pass_to_fetch ['search_keyword'];
				$kw_data ['only_those_fields'] = array ('id' );
				
				$kw_ids = $this->getUsers ( $kw_data, $limit = false, $count_only = false );
				//p ( $kw_ids );
				$idd_array = array ();
				
				if (! empty ( $kw_ids )) {
					foreach ( $kw_ids as $idd ) {
						// 'include_ids' => array(1, 2, 3)
						$idd_array [] = $idd ['id'];
					}
					
					if ($pass_to_fetch ['include_ids'] != false) {
						$pass_to_fetch ['include_ids'] = array_merge ( $pass_to_fetch ['include_ids'], $idd_array );
					} else {
						$pass_to_fetch ['include_ids'] = $idd_array;
					}
					$pass_to_fetch ['include_ids_field'] = 'follower_id';
				}
				unset ( $pass_to_fetch ['search_keyword'] );
			}
		
		}
		//p ( $params );
		//p ( $pass_to_fetch );
		

		$data = CI::model('core')->fetchDbData ( $table, $params, $pass_to_fetch );
		//	p($data);
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
		$q = CI::model('core')->fetchDbData ( $table, $q, $defalut_options );
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
		$q = CI::model('core')->fetchDbData ( $table, $q, $defalut_options );
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
	
	function logOut() {
		
		CI::library('session')->unset_userdata ( 'user_session' );
		CI::library('session')->unset_userdata ( 'user' );
		CI::library('session')->unset_userdata ( 'the_user' );
		return true;
	}
	
	function logIn() {
		
		if ($_POST) {
			
			$username_or_email = $this->input->post ( 'username' );
			
			$password = $this->input->post ( 'password' );
			
			$check = array ();
			$check ['username'] = $username_or_email;
			$check ['password'] = $password;
			$check = $this->getUsers ( $check );
			
			if (empty ( $check [0] )) {
				
				$check = array ();
				$check ['email'] = $username_or_email;
				$check ['password'] = $password;
				$check = $this->getUsers ( $check );
			
			}
			
			if (empty ( $check [0] )) {
				$return_result ['fail'] = 'Login failed.';
			} else {
				if (($check [0] ['is_active'] == 'n')) {
					$return_result ['fail'] = 'Login failed. Your acount is not activated!';
				} else {
					
					$user_session ['is_logged'] = 'yes';
					
					$user_session ['user_id'] = $check [0] ['id'];
					
					CI::library('session')->set_userdata ( 'user_session', $user_session );
					CI::library('session')->set_userdata ( 'user', $check [0] );
					$back_to = CI::model('core')->getParamFromURL ( 'back_to' );
					if (trim ( $_POST ['back_to'] ) != '') {
						
						$back_to = $_POST ['back_to'];
					}
					
					if ($back_to != '') {
						$back_to = base64_decode ( $back_to );
						if (trim ( $back_to ) != '') {
							$return_result ['redirect'] = $back_to;
							//header ( 'Location: ' . $back_to );
						//exit ();
						} else {
						
						}
					} else {
						$return_result ['ok'] = true;
					}
				}
			}
		
		} else {
			$return_result ['fail'] = 'Login failed.';
		}
		
		return $return_result;
	
	}
	
	function is_logged_in() {
		
		return CI::model('core')->userId ();
	
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
		
		CI::db()->query ( 'REPLACE
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
		
		CI::db()->query ( 'REPLACE
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
