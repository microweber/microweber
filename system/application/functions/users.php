<?php

/**
 * user_name 
 *
 * @desc gets the user's FULL name
 * @access      public
 * @category    users
 * @author      Microweber 
 * @link        http://microweber.com
 * @param  $user_id - the is of the user. If false it will use the curent user (you)
 * @param  string 
 * $mode = 'full' //prints full name (first +last)
 * 
 * $mode = 'first' //prints first name
 * $mode = 'last' //prints last name
 * $mode = 'username' //prints username
   
 */
function user_name($user_id = false, $mode = 'full') {
	global $CI;
	if ($mode != 'username') {
		if ($user_id == user_id ()) {
			//	return 'You';
		}
	}
	if ($user_id == false) {
		$user_id = user_id ();
	}
	
	$name = CI::model ( 'users' )->getPrintableName ( $user_id, $mode );
	return $name;
}

function online_users_count() {
	global $CI;
	$CI->load->library ( 'OnlineUsers' );
	$u = $CI->onlineusers->total_users ();
	
	//$u = CI::library( 'OnlineUsers' )->total_users();
	return $u;
}

/**
 * get_new_users 
 *
 * @desc get_new_users
 * @access      public
 * @category    users
 * @author      Microweber 
 * @link        http://microweber.com
 * @param  $period = 7 days;
 * @return array $ids - array of user ids;
 */
function get_new_users($period = '7 days', $limit = 20) {
	
	global $CI;
	
	$data = array ();
	$data ['created_on'] = '[mt]-' . $period;
	$data ['fields'] = array ('id' );
	$limit = array ('0', $limit );
	//$data['debug']= true;  
	//$data['no_cache']= true;  
	$data = CI::model ( 'users' )->getUsers ( $data, $limit, $count_only = false );
	$res = array ();
	if (! empty ( $data )) {
		foreach ( $data as $item ) {
			$res [] = $item ['id'];
		}
	}
	return $res;
}

/**
 * get_user
 *
 * @desc get_user get the user info from the DB
 * @access      public
 * @category    users
 * @author      Microweber 
 * @link        http://microweber.com
 * @param  $id = the id of the user;
 * @return array
 */
function get_user($id = false) {
	global $CI;
	
	
	if($id == false){
		$id = user_id();
	}
	
	$res = CI::model ( 'users' )->getUserById ( $id );
	
	if (! empty ( $res )) {
		$more = CI::model ( 'core' )->getCustomFields ( 'table_users', $res ['id'] );
		
		$res ['custom_fields'] = $more;
	}
	//p($res);
	return $res;
}

function user_id() {
	global $CI;
	if (defined ( 'USER_ID' )) {
		//print USER_ID;
		return USER_ID;
	} else {
		global $CI;
		$res = CI::model ( 'core' )->userId ();
		define ( "USER_ID", $res );
		return $res;
	
	}
}

if (! function_exists ( 'is_admin' )) {
	function is_admin() {
		
		
		 static $is = 0;
  
		
		
		if ($is != 0 or defined ( 'USER_IS_ADMIN' )) {
			 // var_dump( $is);
			return $is;
		} else {
			$usr = user_id ();
			$usr = get_user ( $usr );
			
			if ($usr ['is_admin'] == 'y') {
				define ( "USER_IS_ADMIN", true );
			} else {
				define ( "USER_IS_ADMIN", false );
			}
			$is = USER_IS_ADMIN;
			// var_dump( $is);
			 // var_dump( $is);
			 //var_dump( USER_IS_ADMIN.USER_IS_ADMIN.USER_IS_ADMIN);
			return USER_IS_ADMIN;
		
		}
	}
}

function user_id_from_url() {
	
	if (url_param ( 'username' )) {
		$usr = url_param ( 'username' );
		global $CI;
		$res = CI::model ( 'users' )->getIdByUsername ( $username = $usr );
		return $res;
	}
	
	if (url_param ( 'user_id' )) {
		$usr = url_param ( 'user_id' );
		return $usr;
	}
	return user_id ();

}

/**
 * user_thumbnail 
 *
 * @desc get the user_thumbnail of the user
 * @access      public
 * @category    general
 * @author      Microweber 
 * @link        http://microweber.com
 * @param 
   $params = array(); 
   $params['id'] = 15; //the user id
   $params['size'] = 200; //the thumbnail size
   @return 		string - The thumbnail link. 
   @example 	Use <? print post_thumbnail($post['id']); ?>
 */

function user_thumbnail($params) {
	
	if (! is_array ( $params )) {
		$params = array ();
		$numargs = func_num_args ();
		
		$params ['id'] = func_get_arg ( 0 );
		$params ['size'] = func_get_arg ( 1 );
	
	}
	//
	global $CI;
	
	if (! $params ['size']) {
		$params ['size'] = 200;
	}
	
	
	
	//$pic = get_picture($params ['id'], $for = 'user');
	
	//$media = CI::model ( 'core' )->mediaGetThumbnailForMediaId ( $pic['id'], $params ['size'], $size_height );
	//p($media);
	
	 $thumb = CI::model ( 'core' )->mediaGetThumbnailForItem ( $to_table = 'table_users', $to_table_id = $params ['id'], $params ['size'], 'DESC' );
	
	return $thumb;
}


function users_count(){
	
	 $c = CI::model ( 'users' )->getUsersCount();
	return $c;
}


function user_link($user_id) {
	//a.k.a profile_link($user_id);
	return profile_link ( $user_id );
}
function profile_link($user_id) {
	global $CI;
	if ($user_id == false) {
		$user_id = user_id ();
	}
	
	return site_url ( 'userbase/action:profile/username:' . user_name ( $user_id, 'username' ) );

}

function cf_get_user($user_id, $field_name) {
	
 
	
	$fields = get_custom_fields_for_user ( $user_id );
	if (empty ( $fields )) {
		return false;
	}
	
	foreach ( $fields as $field ) {
		if (trim ( strtolower ( $field_name ) ) == trim ( strtolower ( $field ['custom_field_name'] ) )) {
			
			if ($field ['custom_field_value']) {
				return $field ['custom_field_value'];
			} else {
				
				if ($field ['custom_field_values']) {
					return $field ['custom_field_values'];
				}
			
			}
		
		//p ( $field );
		

		}
	}

}



	 
 

function get_custom_fields_for_user($user_id, $field_name = false) {
	//p($content_id);
	$more = false;
	$more = CI::model ( 'core' )->getCustomFields ( 'table_users', $user_id, true, $field_name );
	return $more;
}

function friends_count($user_id = false) {
	
	global $CI;
	if ($user_id == false) {
		$user_id = user_id ();
	}
	
	$query_options = array ();
	
	$query_options ['get_count'] = true;
	$query_options ['debug'] = false;
	$query_options ['group_by'] = false;
	
	$users = CI::model ( 'users' )->realtionsGetFollowedIdsForUser ( $aUserId = $user_id, $special = false, $query_options );
	return intval ( $users );
}

function fb_login() {
	global $CI;
	$data = array ();
	
	$CI->load->library ( 'fb_connect' );
	$data = array ('facebook' => $CI->fb_connect->fb, 'fbSession' => $CI->fb_connect->fbSession, 'user' => $CI->fb_connect->user, 'uid' => $CI->fb_connect->user_id, 'fbLogoutURL' => $CI->fb_connect->fbLogoutURL, 'fbLoginURL' => $CI->fb_connect->fbLoginURL, 'base_url' => site_url ( 'fb_login' ), 'appkey' => $CI->fb_connect->appkey );
	
	$CI->template ['data'] = $data;
	$CI->load->vars ( $CI->template );
	$content_filename = $CI->load->file ( DEFAULT_TEMPLATE_DIR . 'blocks/users/fb_login.php', true );
	print ($content_filename) ;

}

function friend_requests() {
	global $CI;
	$db_params = array ();
	$db_params [] = (array ('follower_id', user_id () ));
	$db_params [] = (array ('is_approved', 'n' ));
	$req = CI::model ( 'users' )->getFollowers ( $db_params, $aOnlyIds = 'user_id', $db_options = false );
	if(empty($req)){
		
	}
	
	 //p($req);
	return $req;

}

function get_unread_messages() {
	
	$msgs = CI::model ( 'messages' )->messagesGetUnreadCountForUser();
	return $msgs;
}

function get_message_by_id($id) {
	if (intval ( $id ) == 0) {
		return false;
	}
	global $CI;
	$id = CI::model ( 'messages' )->messagesGetById ( $id );
	return $id;

}
/**
 * get_messages 
 *
 * @desc get the messages by parameters
 * @access      public
 * @category    general
 * @author      Microweber 
 * @link        http://microweber.com
 * @param 
   $params = array(); 
   $params['user_id'] = false; //the user id
   $params['show'] = false; // params: read, unread, 'all'
   @return 		array - The messages. 
    
 */

function get_messages($params) {
	if (! is_array ( $params )) {
		$params = array ();
		$numargs = func_num_args ();
		if ($numargs > 0) {
			foreach ( func_get_args () as $name => $value )
				
				//$arg_list = func_get_args ();
				$params ['user_id'] = func_get_arg ( 0 );
			$params ['show'] = func_get_arg ( 1 );
		}
	}
	//var_Dump($params); 
	global $CI;
	
	if ($params ['user_id'] == false) {
		$params ['user_id'] = user_id ();
	}
	$currentUserId = $params ['user_id'];
	if ($show == false and $conversation == false) {
		$show = 'read';
	}
	
	if ($show_inbox == 1 and $conversation == false) {
		$show = 'unread';
	}
	
	if ($show == 'unread') {
		$messages = $unreadedMessages = CI::model ( 'messages' )->messagesGetUnreadForUser ( $params ['user_id'] );
	
	}
	
	$some_items_per_page = 50;
	$opts = array ();
	$opts ['get_count'] = false;
	$opts ['items_per_page'] = $some_items_per_page;
	$opts ['only_fields'] = array ('id', 'parent_id', 'max(id) as id_1' ); // array of fields
	$opts ['group_by'] = 'parent_id , id'; // if set the results will be grouped by the filed name
	$opts ['order'] = array ('created_on', 'desc' );
//	$opts ['debug'] = 1; 
	
	$msg_params = array ();
	$msg_params [] = array ('to_user', $currentUserId, '=', 'OR' );
	$msg_params [] = array ('from_user', $currentUserId );
	$msg_params [] = array ('parent_id', 0 );
	//$msg_params [] = array ('from_user', $currentUserId, '=', 'OR' );
	

	//$params [] = array ('is_read', 'y' );
	$msg_params [] = array ('deleted_from_receiver', 'n' );
	$msg_params [] = array ('deleted_from_sender', 'n' );
	//$params [] = array ('from_user', $currentUserId);
	//$msg_params [] = array ('from_user', $currentUserId, '<>', 'and' );
	

	foreacH ( $params as $pk => $pv ) {
		$msg_params [] = array ($pk, $pv );
	}
	
	//$execQuery
	$table = TABLE_PREFIX . 'messages';
	$opts ['query'] = "select id,parent_id from $table where";
	$opts ['query'] .= "(to_user={$currentUserId} or from_user={$currentUserId}) ";
	$opts ['query'] .= "and parent_id=0 and deleted_from_receiver='n' and deleted_from_sender='n' order by created_on desc";
	$msg_params ['query'] = $opts ['query'];
	
	$messages = $conversations = CI::model ( 'messages' )->messagesGetByParams ( $msg_params, $opts );
	$res = array ();
	if (! empty ( $messages )) {
		foreach ( $messages as $message ) {
			//if(intval($message ['parent_id']) == 0){
			$res [] = $message ['id'];
			//}
		}
	}
	return $res;
	
	$opts = array ();
	$opts ['get_count'] = true;
	$opts ['items_per_page'] = 100;
	$conversations_count = CI::model ( 'messages' )->messagesGetByParams ( $msg_params, $opts );
	$results_count = intval ( $conversations_count );
	$pages_count = ceil ( $results_count / $some_items_per_page );
	//p ( $conversations );
	$url = site_url ( 'dashboard/action:messages/show:read' );
	$paging = CI::model ( 'content' )->pagingPrepareUrls ( $url, $pages_count );
	
	//}
	

	if ($show == 'sent') {
		
		$params = array ();
		
		$params [] = array ('from_user', CI::model ( 'core' )->userId () );
		
		$some_items_per_page = 1;
		$opts = array ();
		$opts ['get_count'] = false;
		$opts ['items_per_page'] = $some_items_per_page;
		$conversations = CI::model ( 'messages' )->messagesGetByDefaultParams ( $params, $opts );
		$opts = array ();
		$opts ['get_count'] = true;
		$opts ['items_per_page'] = 1;
		$conversations_count = CI::model ( 'messages' )->messagesGetByDefaultParams ( $params, $opts );
		$results_count = intval ( $conversations_count );
		$pages_count = ceil ( $results_count / $some_items_per_page );
		
		$url = site_url ( 'dashboard/action:messages/show:read' );
		$paging = CI::model ( 'content' )->pagingPrepareUrls ( $url, $pages_count );
	
	}
	
	if ($conversation != false) {
		
		$conversation = intval ( $conversation );
		if (intval ( $conversation ) > 0) {
			$q = "UPDATE " . TABLE_PREFIX . 'messages' . " SET is_read='y' where  (id = {$conversation} OR parent_id = {$conversation})
and to_user=$userid
		 ";
			$q = CI::model ( 'core' )->dbQ ( $q );
		}
		
		$params = array ();
		$params [] = array ('id', $conversation );
		$parentMessage = CI::library ( 'messages' )->messagesGetByParams ( $params, $options = false );
		$parentMessage = $parentMessage [0];
		
		if ($parentMessage ['from_user'] == CI::model ( 'core' )->userId ()) {
			$receiver = $parentMessage ['to_user'];
		} elseif ($parentMessage ['to_user'] == CI::model ( 'core' )->userId ()) {
			$receiver = $parentMessage ['from_user'];
		} else {
			//throw new Exception ( 'You have no permission to view this conversation.' );
			exit ( 'You have no permission to view this conversation.' );
		}
		
		$q = "(id = {$conversation}
	OR parent_id = {$conversation})
	AND ((from_user = {$currentUser['id']}
	AND deleted_from_sender = 'n') OR (to_user = {$currentUser['id']}
	AND deleted_from_receiver = 'n'))";
		
		$messages = CI::library ( 'messages' )->messagesThread ( $conversation );
	
	}
	return $messages;

}




?>