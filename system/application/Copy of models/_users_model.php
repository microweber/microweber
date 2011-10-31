<?php class Users_model extends Model {

	function __construct() {
		parent::Model ();

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
		if($_FILES){
			//CI::model('core')->cacheDelete ( 'cache_group', 'media' );
			CI::model('core')->mediaUpload('table_users', $data ['id']);
		}
		$save = CI::model('core')->saveData ( $table, $data );
		if(CI::model('core')->dbCheckExistTable('affiliate_users') !== FALSE){
			$data2 = array();
			//$data2_buf = array('parent_affil','password','email','ip','reg_ip','addr1','addr2');
			$data2['password'] = $data['password'];
			$data2['email'] = $data['email'];
			$data2['name'] = $data['username'];
			$data2['ip'] = $_SERVER['REMOTE_ADDR'];
			$data2['reg_ip'] = $_SERVER['REMOTE_ADDR'];
			$data2['addr1'] = $data['addr1'];
			$data2['addr2'] = $data['addr2'];
			$data2['city'] = $data['city'];
			$data2['state'] = $data['state'];
			$data2['zip'] = $data['zip'];
			$data2['country'] = $data['country'];
			$data2['phone'] = $data['phone'];
			$data2['fax'] = $data['fax'];
			$data2['website_url'] = $data['website'];
			$data2['first_name'] = $data['first_name'];
			$data2['last_name'] = $data['last_name'];
			$data2['approved'] = 1;
			$data2['parent_affil'] = $_COOKIE['referrer_id'];
			$data2['payout'] = 1;
			$data2['usertype'] = 4;
			$save = CI::model('core')->saveData ( 'affiliate_users', $data2 );
		}
		CI::model('core')->cacheDelete ( 'cache_group', 'media' );
		CI::model('core')->cacheDelete ( 'cache_group', 'users' );
		//if($_FILES){

		//}


		return $save;
	}

	function getUserById($id) {
		$data = array();
		$data['id'] = $id;
		$data = $this->getUsers($data);
		$data = $data[0];
		return $data;
	}


	function getUsers($data = false,$limit = false, $count_only = false ) {
		global $cms_db_tables;
		$table = $cms_db_tables ['table_users'];
		//$q = CI::model('core')->dbQuery ( $q, md5 ( $q ), 'comments' );
		$data = codeClean($data);
		//var_dump($data);
		$get = CI::model('core')->getDbData ( $table, $criteria = $data, $limit, $offset = false, $orderby = array('updated_on', 'DESC'), $cache_group = false, $debug = false, $ids = false, $count_only = $count_only, $only_those_fields = false );
		return $get;
	}
	
	function getUsersForGMaps($data = false,$limit = false,$count_only = false ) {
		global $cms_db_tables;
		$table = $cms_db_tables ['table_users'];
		//$q = CI::model('core')->dbQuery ( $q, md5 ( $q ), 'comments' );
		$data = codeClean($data);
		//var_dump($data);
		$get = CI::model('core')->getDbData ( $table = $table, $criteria = $data, $limit = $limit, $offset = false, $orderby = array('zip,updated_on', 'DESC'), $cache_group = false, $debug = false, $ids = false, $count_only = $count_only, $only_those_fields = false );
		return $get;
	}

	function getUserThumbnail($user_id, $size = 128) {

		$image = CI::model('core')->mediaGetThumbnailForItem ( 'table_users', $to_table_id = $user_id, $size = $size, $order_direction = "DESC" );
		return $image;

	}

	function userDeleteById($id) {
		global $cms_db_tables;
		$table = $cms_db_tables ['table_users'];
		$data = array ( );
		$data ['id'] = $id;
		$del = CI::model('core')->deleteData ( $table, $data, 'users' );
		CI::model('core')->cacheDelete ( 'cache_group', 'users' );
		return true;
	}
	
	function sendMail($opt = array()) {
		if (empty ( $opt ))
			return false;

		$to = $opt ['email'];
		$admin_options = CI::model('core')->optionsGetByKey ( 'admin_email', true );
		
		$from = (empty ( $admin_options )) ? 'noreply@ooyes.net' : $admin_options ['option_value'];
		
		$object = $opt ['object']; 
		if(!$object) $object = 'Water For Life Mail';
		$total = 0;
		$message =<<<STR
		Hello, <b>{$opt ['name']}</b>!

		Here You are login details from site <b>{$opt ['site']}</b>:<br />
		Username: <b>{$opt ['username']}</b><br />
		Password: <b>{$opt ['password']}</b><br />
		<p>
		Have a nice day!
STR;
		

		@mail ( $to, $object, $message, "From: $from\nReply-To: $from\nContent-Type: text/html;charset=\"windows-1251\"\nContent-Transfer-Encoding: 8bit" );
	}

}
