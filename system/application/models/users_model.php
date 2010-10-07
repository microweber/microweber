<?php if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Users_model extends Model {

	private $_notificationTypes;
	public $user_id;

	function __construct() {
		parent::Model ();
		//$this->_initActivitiesTypes ();

		$this->notificationsParseFromLog ();

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
		/*	$q = " select count(*) as qty from $table where is_active='y'  ";
		$q = $this->db->query ( $q );
		$q = $q->row_array ();
		$q = intval ( $q ['qty'] );
		if ($q == 0) {
			return 0;
		} else {
			return $q;
		}

		*/
		$options = array ();
		$options ['get_count'] = true;
		$options ['debug'] = false;
		$options ['cache'] = true;
		$options ['cache_group'] = 'users/data/';

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

		/*$del_media_params = array ();
			$del_media_params ['to_table'] = 'table_users';
			$del_media_params ['to_table_id'] = $data ['id'];
			$del_media_params ['media_type'] = 'picture';
			$this->core_model->mediaDeleteAllByParams ( $del_media_params );

			$this->core_model->mediaUpload ( 'table_users', $data ['id'] );
			$this->core_model->cleanCacheGroup ( 'media' );*/

		}
		$save = $this->core_model->saveData ( $table, $data );

		$this->core_model->cleanCacheGroup ( 'users/data' );

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

		$cache_group = 'users/data';
		$get = $this->core_model->getDbData ( $table, $criteria = $data, $limit, $offset = false, $orderby = array ('updated_on', 'DESC' ), $cache_group, $debug = false, $ids, $count_only = $count_only, $only_those_fields = false, $exclude_ids = false, $force_cache_id = false, $get_only_whats_requested_without_additional_stuff = true );
		return $get;
	}

	function getUsersForGMaps($data = false, $limit = false, $count_only = false) {

		global $cms_db_tables;
		$table = $cms_db_tables ['table_users'];
		//$q = $this->core_model->dbQuery ( $q, md5 ( $q ), 'comments' );
		$data = codeClean ( $data );
		//var_dump($data);
		$cache_group = 'users/data';
		$get = $this->core_model->getDbData ( $table = $table, $criteria = $data, $limit = $limit, $offset = false, $orderby = array ('zip,updated_on', 'DESC' ), $cache_group = $cache_group, $debug = false, $ids = false, $count_only = $count_only, $only_those_fields = false );
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
	 * 	$this->users_model->getIdByUsername('admin');
	 *
	 */
	function getIdByUsername($username = false) {
		if ($username == false) {
			return false;
		}
		$users_list = array ();

		$users_list ['username'] = $username;

		$users_list = $this->getUsers ( $users_list, array (0, 1 ) );
		//p($users_list);
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
	 * 	Delete relation:
	 * 	$this->users_model->getPrintableName(10, 'full');
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
		$del = $this->core_model->deleteData ( $table, $data, 'users' );
		$this->core_model->cleanCacheGroup ( 'users' );
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
	 * 	Delete relation:
	 * 	$this->users_model->saveFollower(array(
	 *		'user' => <<currentUser>>['id'],
	 *		'follower' => 100000,
	 *		'follow' => false,
	 *	));
	 *
	 * 	Add special follower:
	 * 	$this->users_model->saveFollower(array(
	 *		'user' => <<currentUser>>['id'],
	 *		'follower' => 100000,
	 *		'special' => true,
	 *	));
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
			$aUserId = $this->users_model->userId ();
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

			$aUserId = $this->users_model->userId ();
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

	public function buildNotificationMessage($aNotificationType, $aBuildParams) {
		return false;

	}

	public function sendNotification($aNotification) {
		//@todo clean users cache
		if ($this->core_model->optionsGetByKey ( 'enable_notifications' )) {

			if (! isset ( $aNotification ['message_params'] )) {
				$aNotification ['message_params'] = array ();
			}

			$aNotification ['message'] = $this->buildNotificationMessage ( $aNotification ['type'], $aNotification ['message_params'] );

			unset ( $aNotification ['message_params'] );

			$this->core_model->saveData ( TABLE_PREFIX . 'users_notifications', $aNotification );

		} else {
			// notifications disabled
		}
		$this->core_model->cleanCacheGroup ( 'users/notifications' );

	}

	public function cleanOldStatuses($aUser, $aHistoryLength = 100) {
		$oldStatuses = $this->core_model->fetchDbData ( TABLE_PREFIX . 'users_statuses', array (array ('user_id', $aUser ['id'] ) ), array ('only_fields' => array ('id' ), 'oder' => array ('created_on' ), 'limit' => 100000, //no limit
'offset' => $aHistoryLength ) );

		if ($oldStatuses) {

			$oldStatusesIds = array ();
			foreach ( $oldStatuses as $status ) {
				$oldStatusesIds [] = $status ['id'];
			}

			$this->core_model->db->query ( "DELETE FROM " . TABLE_PREFIX . 'users_statuses' . " WHERE id IN (" . implode ( ',', $oldStatusesIds ) . ")" );
			$this->core_model->cleanCacheGroup ( 'users/statuses' );
		}

	}

	/**
	 * Get unread messages for given user ID
	 * @param int $user_id user id - if false the curent user is used
	 * @return array msg
	 */
	function messagesGetUnreadCountForUser($user_id = false) {
		if ($user_id == false) {

			$user_id = $this->users_model->userId ();
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
		$table = TABLE_PREFIX . 'users_log';
		//$params [] = array ('to_user', $user_id );
		//$params [] = array ('is_read', 'n' );
		//$params [] = array ('deleted_from_receiver', 'n' );
		$query_options = array ();
		//$query_options ['only_fields'] = array ('follower_id', 'user_id' );


		if (! empty ( $params ['for_user_ids'] )) {
			$for_users = $params ['for_user_ids'];
			unset ( $params ['for_user_ids'] );

		}

		$query_options ['include_ids_field'] = 'user_id';
		$query_options ['include_ids'] = $for_users;

		//$query_options ['debug'] = false;
		$query_options ['get_params_from_url'] = false;
		$query_options ['cache'] = false;
		$query_options ['order'] = array ('created_on', 'DESC' );

		// $query_options ['cache_group'] = 'users/relations';
		if (! empty ( $db_options )) {
			foreach ( $db_options as $k => $v ) {
				$query_options ["{$k}"] = $v;
			}
		}

		$data = $this->core_model->fetchDbData ( $table, $params, $query_options );
		return $data;
	}

	/**
	 * Deletes log entry by id
	 * @param int id
	 * @return true
	 */
	function logDeleteById($id) {
		global $cms_db_tables;
		$table = $cms_db_tables ['table_users_log'];
		$data = array ();
		$data ['id'] = $id;
		$del = $this->core_model->deleteData ( $table, $data );
		$this->core_model->cleanCacheGroup ( 'users/log' );
		return true;
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
		$params [] = array ('from_user', $user_id, '<>' , 'and'  );

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
			$currentUserId = intval ( $this->users_model->userId () );
			if ($this->users_model->userId () == 0) {
				exit ( "Error in " . __FILE__ . " on line " . __LINE__ );
			}
			$params = array ();
			$params [] = array ('to_user', $currentUserId );
			$params [] = array ('is_read', 'y' );
			$params [] = array ('deleted_from_receiver', 'n' );
			//$params [] = array ('from_user', $currentUserId);
			$params [] = array ('from_user', $currentUserId, '<>' , 'and'  );
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

	/**
	 * Parse notifications from the log
	 */
	public function notificationsParseFromLog() {
		global $cms_db_tables;
		$table_notifications = $cms_db_tables ['table_users_notifications'];

		$user_id = $this->userId ();
		if (intval ( $user_id ) == 0) {
			return false;
		}

		$timeFile = CACHEDIR . 'interval_' . __FUNCTION__;
		if (! file_exists ( $timeFile )) {
			@touch ( $timeFile );
		}

		if (filemtime ( $timeFile ) < strtotime ( "-5 minutes" )) {
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
				$ids = $this->core_model->dbExtractIdsFromArray ( $log );
				$table_log = TABLE_PREFIX . 'users_log';
				$ids_implode = implode ( ',', $ids );
				$q = " UPDATE  $table_log set notifications_parsed='y' where id in($ids_implode) ";
				//p ( $q );
				$q = $this->core_model->dbQ ( $q );

				foreach ( $log as $entry ) {
					switch ($entry ['to_table']) {
						case 'table_followers' :
							$data = array ();
							$data [] = array ('id', intval ( $entry ['to_table_id'] ) );
							$data = $this->core_model->fetchDbData ( $entry ['to_table'], $data );
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
							$data = $this->core_model->fetchDbData ( $entry ['to_table'], $data_params );

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
										$content_data = $this->content_model->contentGetByIdAndCache ( $data ['to_table_id'] );
										$totable_id = $content_data ['id'];
									}
									if ($data ['to_table'] != 'table_content') {
										$totable_id = $data ['id'];
									}

									$to_save = array ();
									$to_save ['to_table'] = $entry ['to_table'];
									$to_save ['to_table_id'] = $totable_id;
									$to_save ['from_user'] = intval ( $data ['created_by'] );
									$to_save ['to_user'] = intval ( $content_data ['created_by']);
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
		$del = $this->core_model->deleteData ( $table, $data, 'users/notifications' );
		$this->core_model->cleanCacheGroup ( 'users/notifications' );
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
		$id = $this->core_model->saveData ( $table, $data );
		if ($no_cache_clean == false) {
			$this->core_model->cleanCacheGroup ( 'users/notifications' );
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
			$params [] = array ('to_user', $this->users_model->userId () );
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
		$options ['cache_group'] = 'users/notifications';
		if (! empty ( $db_options )) {
			foreach ( $db_options as $k => $v ) {
				$options ["{$k}"] = $v;
			}
		}

		$data = $this->core_model->fetchDbData ( $table, $params, $options );
		return $data;
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

		$data = $this->core_model->fetchDbData ( $table, $params, $options );
		return $data;
	}

	/**
	 * Get new notifications count for give user ID
	 * @param int $user_id user id
	 * @return int msg count
	 */
	function notificationsGetUnreadCountForUser($user_id = false) {
		if ($user_id == false) {

			$user_id = $this->users_model->userId ();
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
	 * Get the ids of all Followers for given user id and return them as array of ids
	 * @param int $id user id
	 * @param array $params more db params
	 * @return array
	 */
	function realtionsCheckIfUserIsFollowedByUser($curent_user = false, $relation_with_user, $is_special = false) {
		if ($curent_user == false) {

			$curent_user = $this->users_model->userId ();
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

			$aUserId = $this->users_model->userId ();
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

			$aUserId = $this->users_model->userId ();
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

		$data = $this->core_model->fetchDbData ( $table, $params, $defalut_options );
		return $data;
	}

	/**
	 * Get last status row for given User Id
	 * @param int
	 * @return array
	 */
	function statusesLastByUserId($id = false) {

		if (intval ( $id ) == 0) {

			$id = $this->users_model->userId ();

		}

		$params = array ();
		$params [] = array ('user_id', $id );

		$options = array ();
		$options ['limit'] = 1;

		$options ['order'] = array ('id', 'desc' );
		$options ['cache'] = false;
		$options ['use_cache'] = false;

		$status_res = $this->statusesByParams ( $params, $options );
		//p($status_res);
		/*if(empty($status_res)){
	$params [] = array ('user_id', false );
	$params [] = array ('created_by', $id );
	$status_res = $this->statusesByParams ( $params, $options );
}*/
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
		require_once (ROOTPATH . '/forum/bb-includes/backpress/class.passwordhash.php');

		// By default, use the portable hash from phpass
		$hasher = new PasswordHash ( 8, TRUE );

		return $hasher->HashPassword ( $password );
	}
}
