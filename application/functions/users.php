<?php

function user_login($params) {
    $params2 = array();

    if (is_string($params)) {
        $params = parse_str($params, $params2);
        $params = $params2;
    }

    if (isset($params) and !empty($params)) {

        $user = isset($params ['username']) ? $params ['username'] : false;
        $pass = isset($params ['password']) ? $params ['password'] : false;
        $email = isset($params ['email']) ? $params ['email'] : false;

        $data = array();
        $data ['username'] = $user;
        $data ['password'] = $pass;
        $data ['is_active'] = 'y';

        $data = get_users($data);
        if (isset($data [0])) {
            $data = $data [0];
            if (empty($data)) {
                if (trim($email) != '') {
                    $data = array();
                    $data ['email'] = $email;
                    $data ['password'] = $pass;
                    $data ['is_active'] = 'y';
                    $data = get_users($data);
                    $data = $data [0];
                }
            }
        } else {
            return false;
        }

        if (empty($data)) {

            return false;
        } else {

            $user_session ['is_logged'] = 'yes';
            $user_session ['user_id'] = $data ['id'];
            session_set('user_session', $user_session);
            $user_session = session_get('user_session');
            if (isset($data ["is_admin"]) and $data ["is_admin"] == 'y') {
                if (isset($params ['where_to']) and $params ['where_to'] == 'live_edit') {

                    $p = get_page();
                    if (!empty($p)) {
                        $link = page_link($p ['id']);
                        $link = $link . '/editmode:y';
                        safe_redirect($link);
                    }
                }
            }

            return $user_session;
        }
    }

    return false;
}

function user_id() {
    // static $uid;
    if (defined('USER_ID')) {
        // print USER_ID;
        return USER_ID;
    } else {

        $user_session = session_get('user_session');

        $res = false;
        if (isset($user_session ['user_id'])) {
            $res = $user_session ['user_id'];
        }

        // $res = $sess->get ( 'user_id' );
        define("USER_ID", $res);
        return $res;
    }
}

function is_admin() {
    static $is = 0;

    if ($is != 0 or defined('USER_IS_ADMIN')) {
        // var_dump( $is);
        return $is;
    } else {
        $usr = user_id();
        if ($usr == false) {
            return false;
        }
        $usr = get_user($usr);

        if ($usr ['is_admin'] == 'y') {
            define("USER_IS_ADMIN", true);
        } else {
            define("USER_IS_ADMIN", false);
        }
        $is = USER_IS_ADMIN;
        // var_dump( $is);
        // var_dump( $is);
        // var_dump( USER_IS_ADMIN.USER_IS_ADMIN.USER_IS_ADMIN);
        return USER_IS_ADMIN;
    }
}

/**
 * user_name
 *
 * gets the user's FULL name
 *
 * @access public
 * @category users
 * @author Microweber
 * @link http://microweber.com
 * @param $user_id -
 *        	the is of the user. If false it will use the curent user (you)
 * @param string $mode
 *        	= 'full' //prints full name (first +last)
 *
 *        	$mode = 'first' //prints first name
 *        	$mode = 'last' //prints last name
 *        	$mode = 'username' //prints username
 *
 */
function user_name($user_id = false, $mode = 'full') {
    if ($mode != 'username') {
        if ($user_id == user_id()) {
            // return 'You';
        }
    }
    if ($user_id == false) {
        $user_id = user_id();
    }

    $name = nice_user_name($user_id, $mode);
    return $name;
}

function online_users_count() {
    // $CI = get_instance ();
    get_instance()->load->model('Users_model', 'users_model');
    get_instance()->load->library('OnlineUsers');
    $u = get_instance()->onlineusers->total_users();

    // $u = CI::library( 'OnlineUsers' )->total_users();
    return $u;
}

/**
 * get_users
 *
 * get_users
 *
 * @access public
 * @category users
 * @author Microweber
 * @link http://microweber.com
 * @param $params =
 *        	array();
 * @return array array of users;
 */
function get_users($params = array()) {
    $params2 = array();

    if (is_string($params)) {
        $params = parse_str($params, $params2);
        $params = $params2;
    }

    $table = c('db_tables');
    $table = $table ['table_users'];

    $data = string_clean($params);
    $orig_data = $data;

    if (isset($data ['ids']) and is_array($data ['ids'])) {
        if (!empty($data ['ids'])) {
            $ids = $data ['ids'];
        }
    }

    $data ['search_by_keyword_in_fields'] = array(
        'first_name',
        'last_name',
        'username',
        'email'
    );
    // $data ['debug'] = 1;
    $cache_group = 'users/global';
    if (isset($data ['id']) and intval($data ['id']) != 0) {
        $cache_group = 'users/' . $data ['id'];
    } else {

        $cache_group = 'users/global';
    }

    if (isset($limit) and $limit != false) {
        $data ['limit'] = $limit;
    }

    if (isset($count_only) and $count_only != false) {
        $data ['get_count'] = $count_only;
    }

    if (isset($data ['only_those_fields']) and $data ['only_those_fields']) {
        $only_those_fields = $data ['only_those_fields'];
    }

    if (isset($data ['count']) and $data ['count']) {
        $count_only = $data ['count'];
    }

    // $data ['no_cache'] = 1;

    if (isset($data ['username']) and $data ['username'] == null) {
        unset($data ['username']);
    }
    // p ( $data );
    // .//p($data);
    // $get = $this->core_model->fetchDbData ( $table, $data );

    $function_cache_id = false;

    $args = func_get_args();
    $i = 0;
    foreach ($args as $k => $v) {

        $function_cache_id = $function_cache_id . serialize($k) . serialize($v);

        $i++;
    }

    $function_cache_id = __FUNCTION__ . crc32($function_cache_id);

    $cache_content = cache_get_content($function_cache_id, $cache_group);

    if (($cache_content) != false) {

        return $cache_content;
    } else {

        $get = db_get($table, $criteria = $data, $cache_group);
        // var_dump($get, $function_cache_id, $cache_group);
        cache_store_data($get, $function_cache_id, $cache_group);

        return $get;
    }
}

/**
 * get_user
 *
 * get_user get the user info from the DB
 *
 * @access public
 * @category users
 * @author Microweber
 * @link http://microweber.com
 * @param $id =
 *        	the id of the user;
 * @return array
 */
function get_user($id = false) {
    if ($id == false) {
        $id = user_id();
    }

    $res = get_user_by_id($id);

    if (empty($res)) {

        $res = get_user_by_username($id);
    }

    return $res;
}

/**
 * Generic function to get the user by id.
 * Uses the getUsers function to get the data
 *
 * @param
 *        	int id
 * @return array
 *
 */
function get_user_by_id($id) {
    $id = intval($id);
    if ($id == 0) {
        return false;
    }

    $data = array();
    $data ['id'] = $id;
    $data ['limit'] = 1;
    $data = get_users($data);
    $data = $data [0];
    return $data;
}

function get_user_by_username($username) {
    $data = array();
    $data ['username'] = $username;
    $data ['limit'] = 1;
    $data = get_users($data);
    $data = $data [0];
    return $data;
}

/**
 * Function to get user printable name by given ID
 *
 * @param
 *        	$id
 * @param
 *        	$mode
 * @return string
 * @example Delete relation:
 *          $this->users_model->getPrintableName(10, 'full');
 *
 */
function nice_user_name($id, $mode = 'full') {
    $user = get_user_by_id($id);
    $user_data = $user;
    if (empty($user)) {
        return false;
    }

    switch ($mode) {
        case 'first' :
        case 'fist' :
            // because of a common typo :)
            $user_data ['first_name'] ? $name = $user_data ['first_name'] : $name = $user_data ['username'];
            $name = ucwords($name);
            return $name;
            break;

        case 'last' :
            $user_data ['last_name'] ? $name = $user_data ['last_name'] : $name = $user_data ['last_name'];
            $name = ucwords($name);
            return $name;
            break;

        case 'username' :
            $name = $user_data ['username'];
            return $name;
            break;

        case 'full' :
        default :
            $name = $user_data ['first_name'] . ' ' . $user_data ['last_name'];

            if (trim($name) == '') {
                $name = $user_data ['username'];
            }

            $name = ucwords($name);
            return $name;

            break;
    }
    exit();
}

/**
 * get_new_users
 *
 * get_new_users
 *
 * @access public
 * @category users
 * @author Microweber
 * @link http://microweber.com
 * @param $period =
 *        	7 days;
 * @return array $ids - array of user ids;
 */
function get_new_users($period = '7 days', $limit = 20) {

    // $CI = get_instance ();
    get_instance()->load->model('Users_model', 'users_model');
    $data = array();
    $data ['created_on'] = '[mt]-' . $period;
    $data ['fields'] = array(
        'id'
    );
    $limit = array(
        '0',
        $limit
    );
    // $data['debug']= true;
    // $data['no_cache']= true;
    $data = get_instance()->users_model->getUsers($data, $limit, $count_only = false);
    $res = array();
    if (!empty($data)) {
        foreach ($data as $item) {
            $res [] = $item ['id'];
        }
    }
    return $res;
}

function user_id_from_url() {
    if (url_param('username')) {
        $usr = url_param('username');
        // $CI = get_instance ();
        get_instance()->load->model('Users_model', 'users_model');
        $res = get_instance()->users_model->getIdByUsername($username = $usr);
        return $res;
    }

    if (url_param('user_id')) {
        $usr = url_param('user_id');
        return $usr;
    }
    return user_id();
}

/**
 * user_thumbnail
 *
 * get the user_thumbnail of the user
 *
 * @access public
 * @category general
 * @author Microweber
 * @link http://microweber.com
 * @param $params =
 *        	array();
 *        	$params['id'] = 15; //the user id
 *        	$params['size'] = 200; //the thumbnail size
 * @return string - The thumbnail link.
 * @example Use
 *          user_thumbnail
 *
 *          get the user_thumbnail of the user
 *
 * @access public
 * @category general
 * @author Microweber
 * @link http://microweber.com
 * @param $params =
 *        	array();
 *        	$params['id'] = 15; //the user id
 *        	$params['size'] = 200; //the thumbnail size
 * @return string - The thumbnail link.
 * @example Use print post_thumbnail($post['id']);
 */
function user_picture($params) {
    return user_thumbnail($params);
}

function user_thumbnail($params) {
    $params2 = array();

    if (is_string($params)) {
        $params = parse_str($params, $params2);
        $params = $params2;
    }

    //
    // $CI = get_instance ();

    if (!$params ['size']) {
        $params ['size'] = 200;
    }

    // $pic = get_picture($params ['id'], $for = 'user');
    // $media = get_instance()->core_model->mediaGetThumbnailForMediaId (
    // $pic['id'],
    // $params ['size'], $size_height );
    // p($media);

    $thumb = get_instance()->core_model->mediaGetThumbnailForItem($to_table = 'table_users', $to_table_id = $params ['id'], $params ['size'], 'DESC');

    return $thumb;
}

function users_count() {
    $options = array();
    $options ['get_count'] = true;
    // $options ['debug'] = true;
    $options ['count'] = true;
    // $options ['no_cache'] = true;
    $options ['cache_group'] = 'users/global/';

    $data = get_users($options);

    return $data;
}

function user_link($user_id) {
    // a.k.a profile_link($user_id);
    return profile_link($user_id);
}

function profile_link($user_id) {
    // $CI = get_instance ();
    if ($user_id == false) {
        $user_id = user_id();
    }

    return site_url('userbase/action:profile/username:' . user_name($user_id, 'username'));
}

function cf_get_user($user_id, $field_name) {
    $fields = get_custom_fields_for_user($user_id);
    if (empty($fields)) {
        return false;
    }

    foreach ($fields as $field) {
        if (trim(strtolower($field_name)) == trim(strtolower($field ['custom_field_name']))) {

            if ($field ['custom_field_value']) {
                return $field ['custom_field_value'];
            } else {

                if ($field ['custom_field_values']) {
                    return $field ['custom_field_values'];
                }
            }

            // p ( $field );
        }
    }
}

function get_custom_fields_for_user($user_id, $field_name = false) {
    // p($content_id);
    $more = false;
    $more = get_instance()->core_model->getCustomFields('table_users', $user_id, true, $field_name);
    return $more;
}

function friends_count($user_id = false) {

    // $CI = get_instance ();
    if ($user_id == false) {
        $user_id = user_id();
    }

    $query_options = array();

    $query_options ['get_count'] = true;
    $query_options ['debug'] = false;
    $query_options ['group_by'] = false;
    get_instance()->load->model('Users_model', 'users_model');
    $users = get_instance()->users_model->realtionsGetFollowedIdsForUser($aUserId = $user_id, $special = false, $query_options);
    return intval($users);
}

function fb_login() {
    // $CI = get_instance ();
    $data = array();

    get_instance()->load->library('fb_connect');
    $data = array(
        'facebook' => get_instance()->fb_connect->fb,
        'fbSession' => get_instance()->fb_connect->fbSession,
        'user' => get_instance()->fb_connect->user,
        'uid' => get_instance()->fb_connect->user_id,
        'fbLogoutURL' => get_instance()->fb_connect->fbLogoutURL,
        'fbLoginURL' => get_instance()->fb_connect->fbLoginURL,
        'base_url' => site_url('fb_login'),
        'appkey' => get_instance()->fb_connect->appkey
    );

    get_instance()->template ['data'] = $data;
    get_instance()->load->vars(get_instance()->template);
    $content_filename = get_instance()->load->file(DEFAULT_TEMPLATE_DIR . 'blocks/users/fb_login.php', true);
    print ($content_filename);
}

function friend_requests() {
    // $CI = get_instance ();
    $db_params = array();
    $db_params [] = (array(
        'follower_id',
        user_id()
            ));
    $db_params [] = (array(
        'is_approved',
        'n'
            ));
    get_instance()->load->model('Users_model', 'users_model');
    $req = get_instance()->users_model->getFollowers($db_params, $aOnlyIds = 'user_id', $db_options = false);
    if (empty($req)) {

    }

    // p($req);
    return $req;
}

function get_unread_messages() {
    $msgs = CI::model('messages')->messagesGetUnreadCountForUser();
    return $msgs;
}

function get_message_by_id($id) {
    if (intval($id) == 0) {
        return false;
    }
    // $CI = get_instance ();
    $id = CI::model('messages')->messagesGetById($id);
    return $id;
}

/**
 * get_messages
 *
 * get the messages by parameters
 *
 * @access public
 * @category general
 * @author Microweber
 * @link http://microweber.com
 * @param $params =
 *        	array();
 *        	$params['user_id'] = false; //the user id
 *        	$params['show'] = false; // params: read, unread, 'all'
 * @return array - The messages.
 *
 */
function get_messages($params) {
    if (!is_array($params)) {
        $params = array();
        $numargs = func_num_args();
        if ($numargs > 1) {
            foreach (func_get_args() as $name => $value)

            // $arg_list = func_get_args ();
                $params ['user_id'] = func_get_arg(0);
            $params ['show'] = func_get_arg(1);
        }
    }
    // var_Dump($params);
    // $CI = get_instance ();

    if ($params ['user_id'] == false) {
        $params ['user_id'] = user_id();
    }
    $currentUserId = $params ['user_id'];
    if ($show == false and $conversation == false) {
        $show = 'read';
    }

    if ($show_inbox == 1 and $conversation == false) {
        $show = 'unread';
    }

    if ($show == 'unread') {
        $messages = $unreadedMessages = CI::model('messages')->messagesGetUnreadForUser($params ['user_id']);
    }

    $some_items_per_page = 50;
    $opts = array();
    $opts ['get_count'] = false;
    $opts ['items_per_page'] = $some_items_per_page;
    $opts ['only_fields'] = array(
        'id',
        'parent_id',
        'max(id) as id_1'
    ); // array
    // of
    // fields
    $opts ['group_by'] = 'parent_id , id'; // if set the results will be grouped
    // by the filed name
    $opts ['order'] = array(
        'created_on',
        'desc'
    );
    // $opts ['debug'] = 1;

    $msg_params = array();
    $msg_params [] = array(
        'to_user',
        $currentUserId,
        '=',
        'OR'
    );
    $msg_params [] = array(
        'from_user',
        $currentUserId
    );
    $msg_params [] = array(
        'parent_id',
        0
    );
    // $msg_params [] = array ('from_user', $currentUserId, '=', 'OR' );
    // $params [] = array ('is_read', 'y' );
    $msg_params [] = array(
        'deleted_from_receiver',
        'n'
    );
    $msg_params [] = array(
        'deleted_from_sender',
        'n'
    );
    // $params [] = array ('from_user', $currentUserId);
    // $msg_params [] = array ('from_user', $currentUserId, '<>', 'and' );

    foreacH ($params as $pk => $pv) {
        $msg_params [] = array(
            $pk,
            $pv
        );
    }

    // $execQuery
    $table = TABLE_PREFIX . 'messages';
    $opts ['query'] = "select id,parent_id from $table where";
    $opts ['query'] .= "(to_user={$currentUserId} or from_user={$currentUserId}) ";
    $opts ['query'] .= "and parent_id=0 and deleted_from_receiver='n' and deleted_from_sender='n' order by created_on desc";
    $msg_params ['query'] = $opts ['query'];

    $messages = $conversations = CI::model('messages')->messagesGetByParams($msg_params, $opts);
    $res = array();
    if (!empty($messages)) {
        foreach ($messages as $message) {
            // if(intval($message ['parent_id']) == 0){
            $res [] = $message ['id'];

            // }
        }
    }
    return $res;

    $opts = array();
    $opts ['get_count'] = true;
    $opts ['items_per_page'] = 100;
    $conversations_count = CI::model('messages')->messagesGetByParams($msg_params, $opts);
    $results_count = intval($conversations_count);
    $pages_count = ceil($results_count / $some_items_per_page);
    // p ( $conversations );
    $url = site_url('dashboard/action:messages/show:read');
    $paging = get_instance()->content_model->pagingPrepareUrls($url, $pages_count);

    // }

    if ($show == 'sent') {

        $params = array();

        $params [] = array(
            'from_user',
            get_instance()->core_model->userId()
        );

        $some_items_per_page = 1;
        $opts = array();
        $opts ['get_count'] = false;
        $opts ['items_per_page'] = $some_items_per_page;
        $conversations = CI::model('messages')->messagesGetByDefaultParams($params, $opts);
        $opts = array();
        $opts ['get_count'] = true;
        $opts ['items_per_page'] = 1;
        $conversations_count = CI::model('messages')->messagesGetByDefaultParams($params, $opts);
        $results_count = intval($conversations_count);
        $pages_count = ceil($results_count / $some_items_per_page);

        $url = site_url('dashboard/action:messages/show:read');
        $paging = get_instance()->content_model->pagingPrepareUrls($url, $pages_count);
    }

    if ($conversation != false) {

        $conversation = intval($conversation);
        if (intval($conversation) > 0) {
            $q = "UPDATE " . TABLE_PREFIX . 'messages' . " SET is_read='y' where  (id = {$conversation} OR parent_id = {$conversation})
and to_user=$userid
		 ";
            $q = get_instance()->core_model->dbQ($q);
        }

        $params = array();
        $params [] = array(
            'id',
            $conversation
        );
        $parentMessage = CI::library('messages')->messagesGetByParams($params, $options = false);
        $parentMessage = $parentMessage [0];

        if ($parentMessage ['from_user'] == get_instance()->core_model->userId()) {
            $receiver = $parentMessage ['to_user'];
        } elseif ($parentMessage ['to_user'] == get_instance()->core_model->userId()) {
            $receiver = $parentMessage ['from_user'];
        } else {
            // throw new Exception ( 'You have no permission to view this
            // conversation.' );
            exit('You have no permission to view this conversation.');
        }

        $q = "(id = {$conversation}
	OR parent_id = {$conversation})
	AND ((from_user = {$currentUser['id']}
	AND deleted_from_sender = 'n') OR (to_user = {$currentUser['id']}
	AND deleted_from_receiver = 'n'))";

        $messages = CI::library('messages')->messagesThread($conversation);
    }
    return $messages;
}

