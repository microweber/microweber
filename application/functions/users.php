<?php

if (!defined("MW_DB_TABLE_USERS")) {
    define('MW_DB_TABLE_USERS', MW_TABLE_PREFIX . 'users');
}
if (!defined("MW_DB_TABLE_LOG")) {
    define('MW_DB_TABLE_LOG', MW_TABLE_PREFIX . 'log');
}


/**
 * Allows you to login a user into the system
 *
 * It also sets user session when the user is logged. <br />
 * On 5 unsuccessful logins, blocks the ip for few minutes <br />
 *
 *
 * @param array|string $params You can pass parameter as string or as array.
 * @param mixed|string $params['email'] optional If you set  it will use this email for login
 * @param mixed|string $params['password'] optional Use password for login, it gets trough hash_user_pass() function
 * @param mixed|string $params['password_hashed'] optional Use hashed password for login, it does NOT go trough hash_user_pass() function
 *
 *
 * @example
 * <code>
 * //login with username
 * user_login('username=test&password=pass')
 * </code>
 * @example
 * <code>
 * //login with email
 * user_login('email=my@email.com&password=pass')
 * </code>
 * @example
 * <code>
 * //login hashed password
 * user_login('email=my@email.com&password_hashed=c4ca4238a0b923820dcc509a6f75849b')
 * </code>
 *
 * @return array|bool
 * @hooks
 *
 * You can also hook to this function with custom functions <br />
 * There are few events that get executed on login <br />
 *
 * <code>
 * Here is example:
 * action_hook('before_user_login', 'custom_login_function'); //executed before making login query
 * action_hook('mw_user_login', 'custom_after_login_function'); //executed after successful login
 * </code>
 * @package Users
 * @category Users
 * @uses hash_user_pass()
 * @uses parse_str()
 * @uses get_users()
 * @uses session_set()
 * @uses get_log()
 * @uses save_log()
 * @uses user_login_set_failed_attempt()
 * @uses update_user_last_login_time()
 * @uses exec_action()
 * @function user_login()
 * @see _table() For the database table fields
 */
function user_login($params)
{
    $params2 = array();


    exec_action('before_user_login', $params);

    if (is_string($params)) {
        $params = parse_str($params, $params2);
        $params = $params2;
    }


    //$is_logged =  session_get('user_session');
   // if(isarr($is_logged) and isset($is_logged['']))


    if (isset($params) and !empty($params)) {

        $user = isset($params['username']) ? $params['username'] : false;
        $pass = isset($params['password']) ? $params['password'] : false;
        $email = isset($params['email']) ? $params['email'] : false;
	    $pass2 = isset($params['password_hashed']) ? $params['password_hashed'] : false;

        $pass = hash_user_pass($pass);
		if($pass2 != false and $pass2 != NULL and trim($pass2) != ''){
			$pass =  $pass2;
		}


        if (trim($user) == '' and trim($email) == '' and trim($pass) == '') {
            return array('error' => 'Please enter username and password!');

        }
        $url = curent_url(1);

        $check = get_log("is_system=y&count=1&created_on=[mt]1 min ago&updated_on=[lt]1 min&rel=login_failed&user_ip=" . USER_IP);

        if ($check == 5) {

            $url_href = "<a href='$url' target='_blank'>$url</a>";
            save_log("title=User IP " . USER_IP . " is blocked for 1 minute for 5 failed logins.&content=Last login url was " . $url_href . "&is_system=n&rel=login_failed&user_ip=" . USER_IP);
        }
        if ($check > 5) {
            $check = $check - 1;
            return array('error' => 'There are ' . $check . ' failed login attempts from your IP in the last minute. Try again in 1 minute!');
        }
        $check2 = get_log("is_system=y&count=1&created_on=[mt]10 min ago&updated_on=[lt]10 min&&rel=login_failed&user_ip=" . USER_IP);
        if ($check2 > 25) {

            return array('error' => 'There are ' . $check2 . ' failed login attempts from your IP in the last 10 minutes. You are blocked for 10 minutes!');
        }

        $api_key = isset($params['api_key']) ? $params['api_key'] : false;


        if ($user != false) {
            $data1 = array();
            $data1['username'] = $user;
            $data1['password'] = $pass;
            $data1['search_in_fields'] = 'username,email,password';
            $data1['is_active'] = 'y';


        }

        $data = array();

        if (trim($user != '') and trim($pass != '') and isset($data1) and isarr($data1)) {
            $data = get_users($data1);
        }
        if (isset($data[0])) {
            $data = $data[0];
        } else {
            if (!isset($email) or ($email) == '') {

                if (isset($user) and $user != false) {
                    $email = $user;
                }
            }


            if (trim($email) != '') {
                $data = array();

                $email = str_replace(' ', '+', $email);

                $data['email'] = $email;
                $data['password'] = $pass;
                $data['is_active'] = 'y';




                $data['search_in_fields'] = 'password,email';

                $data = get_users($data);

                if (isset($data[0])) {

                    $data = $data[0];
                } else {

                    user_login_set_failed_attempt();
                    return array('error' => 'Please enter right username and password!');

                }
            } else {
                //	return array('error' => 'Please enter username or email!');

            }

            // return false;
        }

        if (!isarr($data)) {
            if (trim($user) != '') {
                $data = array();
                $data['email'] = $user;
                $data['password'] = $pass;
                $data['is_active'] = 'y';
                //  $data['debug'] = 'y';

                $data = get_users($data);

                if (isset($data[0])) {
                    $data = $data[0];
                }
            }
        }
        if (!isarr($data)) {
            user_login_set_failed_attempt();

            $user_session = array();
            $user_session['is_logged'] = 'no';
            session_set('user_session', $user_session);

            return array('error' => 'Please enter the right username and password!');
            return false;
        } else {
            $user_session = array();
            $user_session['is_logged'] = 'yes';
            $user_session['user_id'] = $data['id'];

            if (!defined('USER_ID')) {
                define("USER_ID", $data['id']);
                exec_action('mw_user_login');

            }
            exec_action('user_login', $data);
            session_set('user_session', $user_session);
            $user_session = session_get('user_session');
            update_user_last_login_time();
            if (isset($data["is_admin"]) and $data["is_admin"] == 'y') {
                if (isset($params['where_to']) and $params['where_to'] == 'live_edit') {
                    exec_action('mw_user_login_admin');
                    $p = get_page();
                    if (!empty($p)) {
                        $link = page_link($p['id']);
                        $link = $link . '/editmode:y';
                        safe_redirect($link);
                    }
                }
            }

            $aj = isAjax();

            if ($aj == false and $api_key == false) {
                if (isset($_SERVER["HTTP_REFERER"])) {
                    //	d($user_session);
                    //exit();
                    safe_redirect($_SERVER["HTTP_REFERER"]);
                    exit();
                } else {
					 $user_session['success'] = "You are logged in!";
					 return $user_session;
				}
            } else if ($aj == true) {
                $user_session['success'] = "You are logged in!";
            }

            return $user_session;
        }
    }

    return false;
}


api_expose('user_social_login');
function user_social_login($params)
{
    set_exception_handler('social_login_exception_handler');
    $params2 = array();

    if (is_string($params)) {
        $params = parse_str($params, $params2);
        $params = $params2;
    }

    $return_after_login = false;
    if (isset($_SERVER["HTTP_REFERER"]) and stristr($_SERVER["HTTP_REFERER"], site_url())) {
        $return_after_login = $_SERVER["HTTP_REFERER"];
        session_set('user_after_login', $return_after_login);

    }

    $provider = false;
    if (isset($_REQUEST['provider'])) {
        $provider = $_REQUEST['provider'];
        $provider = trim(strip_tags($provider));
    }

    if ($provider != false and isset($params) and !empty($params)) {

        $api = new \mw\auth\Social();

        try {

            $authenticate = $api->authenticate($provider);
            if (isarr($authenticate) and isset($authenticate['identifier'])) {

                $data = array();
                $data['oauth_provider'] = $provider;
                $data['oauth_uid'] = $authenticate['identifier'];

                $data_ex = get_users($data);
                if (empty($data_ex)) {
                    $data_to_save = $data;
                    $data_to_save['first_name'] = $authenticate['firstName'];
                    $data_to_save['last_name'] = $authenticate['lastName'];
                    $data_to_save['thumbnail'] = $authenticate['photoURL'];
                    $data_to_save['profile_url'] = $authenticate['profileURL'];
                    $data_to_save['website_url'] = $authenticate['webSiteURL'];

                    $data_to_save['email'] = $authenticate['emailVerified'];
                    $data_to_save['user_information'] = $authenticate['description'];
                    $data_to_save['is_active'] = 'y';
                    $data_to_save['is_admin'] = 'n';

                    $table = MW_DB_TABLE_USERS;
                    mw_var('FORCE_SAVE', $table);

                    $save = save_data($table, $data_to_save);
                    cache_clean_group('users/global');
                    if ($save > 0) {
                        $data = array();
                        $data['id'] = $save;

                        $notif = array();
                        $notif['module'] = "users";
                        $notif['rel'] = 'users';
                        $notif['rel_id'] = $save;
                        $provider1 = ucwords($provider);
                        $notif['title'] = "New user registration with {$provider1}";
                        $notif['content'] = "You have new user registered with $provider1. The new user id is: $save";
                        \mw\Notifications::save($notif);

                        save_log($notif);

                    }
                    //d($save);
                }

                $data_ex = get_users($data);

                if (isset($data_ex[0])) {
                    $data = $data_ex[0];
                    $user_session['is_logged'] = 'yes';
                    $user_session['user_id'] = $data['id'];
                    exec_action('after_user_register', $data);
                    if (!defined('USER_ID')) {
                        define("USER_ID", $data['id']);
                    }
                    exec_action('user_login', $data);
                    session_set('user_session', $user_session);
                    $user_session = session_get('user_session');
                    $return_after_login = session_get('user_after_login');
                    update_user_last_login_time();

                    if ($return_after_login != false) {
                        safe_redirect($return_after_login);
                        exit();
                    }

                    //d($user_session);
                }

            }

            //d($authenticate);

        } catch (Exception $e) {
            die("<b>got an error!</b> " . $e->getMessage());
        }

    }
}



api_expose('logout');

function logout()
{

    if (!defined('USER_ID')) {
        define("USER_ID", false);
    }

    // static $uid;
    $aj = isAjax();
    session_end();

    if (isset($_COOKIE['editmode'])) {
        setcookie('editmode');
    }

    if ($aj == false) {
        if (isset($_SERVER["HTTP_REFERER"])) {
            safe_redirect($_SERVER["HTTP_REFERER"]);
        }
    }
}

//api_expose('register_user');
api_expose('register_user');

function register_user($params)
{
    exec_action('before_user_register', $params);





    $user = isset($params['username']) ? $params['username'] : false;
    $pass = isset($params['password']) ? $params['password'] : false;
    $email = isset($params['email']) ? $params['email'] : false;
    $pass2 = $pass;
    $pass = hash_user_pass($pass);

    if (!isset($params['captcha'])) {
        return array('error' => 'Please enter the captcha answer!');
    } else {
        $cap = session_get('captcha');
        if ($cap == false) {
            return array('error' => 'You must load a captcha first!');
        }
        if ($params['captcha'] != $cap) {
            return array('error' => 'Invalid captcha answer!');
        }
    }
    if (!isset($params['password'])) {
        return array('error' => 'Please set password!');
    } else {
        if ($params['password'] == '') {
            return array('error' => 'Please set password!');
        }
    }

    if ($email != false) {

        $data = array();
        $data['email'] = $email;
        $data['password'] = $pass;
        $data['oauth_uid'] = '[null]';
        $data['oauth_provider'] = '[null]';
        $data['one'] = true;
        // $data ['is_active'] = 'y';
        $user_data = get_users($data);




        if (empty($user_data)) {

            $data = array();
            $data['username'] = $email;
            $data['password'] = $pass;
            $data['oauth_uid'] = '[null]';
            $data['oauth_provider'] = '[null]';
            $data['one'] = true;
            // $data ['is_active'] = 'y';
            $user_data = get_users($data);
        }

        if (empty($user_data)) {
            $data = array();


            $data['username'] = $email;
            $data['password'] = $pass;
            $data['is_active'] = 'n';

            $table = MW_TABLE_PREFIX . 'users';

            $q = " INSERT INTO  $table SET email='$email',  password='$pass',   is_active='y' ";
            $next = db_last_id($table);
            $next = intval($next) + 1;
            $q = "INSERT INTO $table (id,email, password, is_active)
			VALUES ($next, '$email', '$pass', 'y')";


            db_q($q);
            cache_clean_group('users' . DIRECTORY_SEPARATOR . 'global');
            //$data = save_user($data);
            session_del('captcha');

            $notif = array();
            $notif['module'] = "users";
            $notif['rel'] = 'users';
            $notif['rel_id'] = $next;
            $notif['title'] = "New user registration";
            $notif['description'] = "You have new user registration";
            $notif['content'] = "You have new user registered with the username [" . $data['username'] . '] and id [' . $next . ']';
            \mw\Notifications::save($notif);

            save_log($notif);


            $params = $data;
            $params['id'] = $next;
            if (isset($pass2)) {
                $params['password2'] = $pass2;
            }
            exec_action('after_user_register', $params);
            //user_login('email='.$email.'&password='.$pass);


            return array('success' => 'You have registered successfully');

            //return array($next);
        } else {

            if(isset($pass) and $pass != '' and isset($user_data['password']) && $user_data['password'] == $pass){
                if(isset($user_data['email']) && $user_data['email'] != ''){
                  $is_logged =  user_login('email='.$user_data['email'].'&password_hashed='.$pass);
                } else  if(isset($user_data['username']) && $user_data['username'] != ''){
                    $is_logged =  user_login('username='.$user_data['username'].'&password_hashed='.$pass);
                }
                if(isset($is_logged) and isarr($is_logged) and isset($is_logged['success']) and isset($is_logged['is_logged'])){
                    return ($is_logged);
                   // $user_session['success']
                }

            }



            return array('error' => 'This user already exists!');
        }
    }
}

api_expose('save_user');

/**
 * Allows you to save users in the database
 *
 * By default it have security rules.
 *
 * If you are admin you can save any user in the system;
 *
 * However if you are regular user you must post param id with the current user id;
 *
 * @param $params
 * @param  $params['id'] = $user_id; // REQUIRED , you must set the user id.
 * For security reasons, to make new user please use register_user() function that requires captcha
 * or write your own save_user wrapper function that sets  mw_var('force_save_user',true);
 * and pass its params to save_user();
 *
 *
 * @param  $params['is_active'] = 'y'; //default is 'n'
 * @usage
 *
 * $upd = array();
 * $upd['id'] = 1;
 * $upd['email'] = $params['new_email'];
 * $upd['password'] = $params['passwordhash'];
 * mw_var('force_save_user', false|true); // if true you want to make new user or foce save ... skips id check and is admin check
 * mw_var('save_user_no_pass_hash', false|true); //if true skips pass hash function and saves password it as is in the request, please hash the password before that or ensure its hashed
 * $s = save_user($upd);
 *
 *
 *
 *
 *
 * @return bool|int
 */
function save_user($params)
{

    $force = mw_var('force_save_user');
    $no_hash = mw_var('save_user_no_pass_hash');
    if (isset($params['id'])) {
        //error('COMLETE ME!!!! ');

        $adm = is_admin();
        if ($adm == false) {
            if ($force == false) {


                $is_logged = user_id();
                if ($is_logged == false or $is_logged == 0) {
                    return array('error' => 'You must be logged to save user');
                } elseif (intval($is_logged) == intval($params['id']) and intval($params['id']) != 0) {

                } else {
                    return array('error' => 'You must be logged to as admin save this user');

                }

                // error('Error: not logged in as admin.' . __FILE__ . __LINE__);

            } else {
                mw_var('force_save_user', false);
            }
        }
    } else {
        if (MW_IS_INSTALLED == true) {


            if ($force == false) {
                error('COMLETE ME!!!! ');
            } else {
                mw_var('force_save_user', false);
            }
        }
    }

    $data_to_save = $params;

    if (isset($data_to_save['password'])) {
        if ($no_hash == false) {
            $data_to_save['password'] = hash_user_pass($data_to_save['password']);
        } else {
            mw_var('save_user_no_pass_hash', false);
        }
    }
    $table = MW_DB_TABLE_USERS;
    $save = save_data($table, $data_to_save);
    $id = $save;
    cache_clean_group('users' . DIRECTORY_SEPARATOR . 'global');
    cache_clean_group('users' . DIRECTORY_SEPARATOR . '0');
    cache_clean_group('users' . DIRECTORY_SEPARATOR . $id);
    return $id;
}

action_hook('mw_db_init_users', 'mw_db_init_users_table');
//action_hook('mw_db_init', 'mw_db_init_users_table');
/**
 * Creates the users and log tables in the database.
 *
 * It is executed on install and on update
 *
 * @function mw_db_init_users_table()
 * @category Users
 * @package Users
 * @subpackage  Advanced
 * @uses set_db_table()
 */
function mw_db_init_users_table()
{
    $function_cache_id = false;

    $args = func_get_args();

    foreach ($args as $k => $v) {

        $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
    }

    $function_cache_id = __FUNCTION__ . crc32($function_cache_id);

    $cache_content = cache_get_content($function_cache_id, 'db');

    if (($cache_content) != false) {

        return $cache_content;
    }

    $table_name = MW_DB_TABLE_USERS;

    $fields_to_add = array();

    $fields_to_add[] = array('updated_on', 'datetime default NULL');
    $fields_to_add[] = array('created_on', 'datetime default NULL');
    $fields_to_add[] = array('expires_on', 'datetime default NULL');
    $fields_to_add[] = array('last_login', 'datetime default NULL');
    $fields_to_add[] = array('last_login_ip', 'TEXT default NULL');

    $fields_to_add[] = array('created_by', 'int(11) default NULL');

    $fields_to_add[] = array('edited_by', 'int(11) default NULL');

    $fields_to_add[] = array('username', 'TEXT default NULL');

    $fields_to_add[] = array('password', 'TEXT default NULL');
    $fields_to_add[] = array('email', 'TEXT default NULL');

    $fields_to_add[] = array('is_active', "char(1) default 'n'");
    $fields_to_add[] = array('is_admin', "char(1) default 'n'");
    $fields_to_add[] = array('is_verified', "char(1) default 'n'");
    $fields_to_add[] = array('is_public', "char(1) default 'y'");

    $fields_to_add[] = array('basic_mode', "char(1) default 'n'");

    $fields_to_add[] = array('first_name', 'TEXT default NULL');
    $fields_to_add[] = array('last_name', 'TEXT default NULL');
    $fields_to_add[] = array('thumbnail', 'TEXT default NULL');

    $fields_to_add[] = array('parent_id', 'int(11) default NULL');

    $fields_to_add[] = array('api_key', 'TEXT default NULL');

    $fields_to_add[] = array('user_information', 'TEXT default NULL');
    $fields_to_add[] = array('subscr_id', 'TEXT default NULL');
    $fields_to_add[] = array('role', 'TEXT default NULL');
    $fields_to_add[] = array('medium', 'TEXT default NULL');

    $fields_to_add[] = array('oauth_uid', 'TEXT default NULL');
    $fields_to_add[] = array('oauth_provider', 'TEXT default NULL');
    $fields_to_add[] = array('oauth_token', 'TEXT default NULL');
    $fields_to_add[] = array('oauth_token_secret', 'TEXT default NULL');

    $fields_to_add[] = array('profile_url', 'TEXT default NULL');
    $fields_to_add[] = array('website_url', 'TEXT default NULL');
    $fields_to_add[] = array('password_reset_hash', 'TEXT default NULL');

    set_db_table($table_name, $fields_to_add);

    db_add_table_index('username', $table_name, array('username(255)'));
    db_add_table_index('email', $table_name, array('email(255)'));



    $table_name = MW_DB_TABLE_LOG;

    $fields_to_add = array();

    $fields_to_add[] = array('updated_on', 'datetime default NULL');
    $fields_to_add[] = array('created_on', 'datetime default NULL');
    $fields_to_add[] = array('created_by', 'int(11) default NULL');
    $fields_to_add[] = array('edited_by', 'int(11) default NULL');
    $fields_to_add[] = array('rel', 'TEXT default NULL');

    $fields_to_add[] = array('rel_id', 'TEXT default NULL');
    $fields_to_add[] = array('position', 'int(11) default NULL');

    $fields_to_add[] = array('field', 'longtext default NULL');
    $fields_to_add[] = array('value', 'TEXT default NULL');
    $fields_to_add[] = array('module', 'longtext default NULL');

    $fields_to_add[] = array('data_type', 'TEXT default NULL');
    $fields_to_add[] = array('title', 'longtext default NULL');
    $fields_to_add[] = array('description', 'TEXT default NULL');
    $fields_to_add[] = array('content', 'TEXT default NULL');
    $fields_to_add[] = array('user_ip', 'TEXT default NULL');
    $fields_to_add[] = array('session_id', 'longtext default NULL');
    $fields_to_add[] = array('is_system', "char(1) default 'n'");

    set_db_table($table_name, $fields_to_add);

    cache_save(true, $function_cache_id, $cache_group = 'db');
    return true;

}

api_expose('system_log_reset');

function system_log_reset($data = false)
{
    $adm = is_admin();
    if ($adm == false) {
        return array('error' => 'Error: not logged in as admin.' . __FILE__ . __LINE__);
    }

    $table = MW_DB_TABLE_LOG;

    $q = "DELETE FROM $table ";

    $cg = guess_cache_group($table);

    cache_clean_group($cg);
    $q = db_q($q);
    return array('success' => 'System log is cleaned up.');

    //return $data;
}

api_expose('delete_log_entry');

function delete_log_entry($data)
{
    $adm = is_admin();
    if ($adm == false) {
        return array('error' => 'Error: not logged in as admin.' . __FILE__ . __LINE__);
    }

    if (isset($data['id'])) {
        $c_id = intval($data['id']);
        db_delete_by_id('log', $c_id);
        $table = MW_DB_TABLE_LOG;
        $old = date("Y-m-d H:i:s", strtotime('-1 month'));
        $q = "DELETE FROM $table WHERE created_on < '{$old}'";

        $q = db_q($q);

        return $c_id;

    }
    return $data;
}

function delete_log($params)
{
    $params = parse_params($params);
    $table = MW_DB_TABLE_LOG;
    $params['table'] = $table;

    if (is_admin() == false) {
        $params['user_ip'] = USER_IP;
    }

    $q = get($params);
    if (isarr($q)) {
        foreach ($q as $val) {
            $c_id = intval($val['id']);
            db_delete_by_id('log', $c_id);
        }

    }
    cache_clean_group('log' . DIRECTORY_SEPARATOR . 'global');
    return true;
}

function save_log($params)
{
    $params = parse_params($params);

    $params['user_ip'] = USER_IP;
    $data_to_save = $params;
    $table = MW_DB_TABLE_LOG;
    mw_var('FORCE_SAVE', $table);
    $save = save_data($table, $params);
    $id = $save;
    cache_clean_group('log' . DIRECTORY_SEPARATOR . 'global');
    return $id;
}

function get_log_entry($id)
{

    $params = array();
    $params['id'] = intval($id);
    $params['one'] = true;

    $get = get_log($params);
    return $get;

}


function get_log($params)
{
    $params = parse_params($params);
    $table = MW_DB_TABLE_LOG;
    $params['table'] = $table;

    if (is_admin() == false) {
        $params['user_ip'] = USER_IP;
    }

    $q = get($params);

    return $q;
}

api_expose('delete_user');

function delete_user($data)
{
    $adm = is_admin();
    if ($adm == false) {
        error('Error: not logged in as admin.' . __FILE__ . __LINE__);
    }

    if (isset($data['id'])) {
        $c_id = intval($data['id']);
        db_delete_by_id('users', $c_id);
        return $c_id;

    }
    return $data;
}


function hash_user_pass($pass)
{
    //$hash = password_hash($pass, PASSWORD_BCRYPT);
    //
    $hash = md5($pass);
    if ($hash == false) {
        $hash = db_escape_string($hash);
        return $pass;
    }
    return $hash;

}


api_expose('captcha');

function captcha_vector($palette, $startx, $starty, $angle, $length, $colour)
{
    $angle = deg2rad($angle);
    $endx = $startx + cos($angle) * $length;
    $endy = $starty - sin($angle) * $length;
    return (imageline($palette, $startx, $starty, $endx, $endy, $colour));
}

function captcha()
{
    $roit1 = rand(1, 6);
    $font = INCLUDES_DIR . DS . 'admin' . DS . 'catcha_fonts' . DS . 'font' . $roit1 . '.ttf';
    $font = normalize_path($font, 0);

    header("Content-type: image/png");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    if (function_exists('imagettftext')) {
        $text1 = mt_rand(100, 4500);
    } else {
        $text1 = mt_rand(100, 999);

    }
    $text2 = mt_rand(2, 9);
    $roit = mt_rand(1, 5);
    $text = "$text1";
    $answ = $text1;
    $x = 100;
    $y = 20;
    $image = @imagecreate($x, 20) or die("Unable to render a CAPTCHA picture!");

    $tcol1z = rand(1, 150);
    $ttcol1z1 = rand(0, 150);
    $tcol1z11 = rand(0, 150);

    $bgcolor = imagecolorallocate($image, 255, 255, 255);
    // $black = imagecolorallocate($image, $tcol1z, $ttcol1z1, $tcol1z11);
    $black = imagecolorallocate($image, 0, 0, 0);
    session_set('captcha', $answ);
   // session_write_close();
    $col1z = rand(200, 242);
    $col1z1 = rand(150, 242);
    $col1z11 = rand(150, 242);
    $color1 = imagecolorallocate($image, $col1z, $col1z1, $tcol1z11);
    $color2 = imagecolorallocate($image, $tcol1z - 1, $ttcol1z1 - 1, $tcol1z11 - 2);
    // imagefill($image, 0, 0, $color1);
    for ($i = 0; $i < $x; $i++) {
        for ($j = 0; $j < $y; $j++) {
            if (mt_rand(0, 20) < 10) {

                //  $coords = array(mt_rand(0, 10), mt_rand(0, 10), mt_rand(0, 10), mt_rand(0, 10), 5, 6);

                $y21 = mt_rand(5, 20);
                captcha_vector($image, $x - mt_rand(0, 10), mt_rand(0, 10), mt_rand(0, 180), 200, $bgcolor);
                //   imagesetpixel($image, $i, $j, $color2);
            }
        }
    }
    $x1 = mt_rand(15, 30);
    $y1 = mt_rand(15, 20);
    $tsize = rand(13, 16);


    if (function_exists('imagettftext')) {
        imagettftext($image, $tsize, $roit, $x1, $y1, $black, $font, $text);
    } else if (function_exists('imagestring')) {
        $font = INCLUDES_DIR . DS . 'admin' . DS . 'catcha_fonts' . DS . 'font' . $roit1 . '.gdf';
        $font = normalize_path($font, 0);
        $font = imageloadfont($font);
        imagestring($image, $font, 0, 0, $text, $black);


    } else {

    }

    if (function_exists('imagefilter')) {
        $filter_img = rand(1, 6);

        switch ($filter_img) {
            case 1:
                $gaussian = array(array(1.0, 2.0, 1.0), array(2.0, 4.0, 2.0), array(1.0, 2.0, 1.0));
                imageconvolution($image, $gaussian, 16, 0);
                break;


            // break;
            case 3:
                imagefilter($image, IMG_FILTER_PIXELATE, 1);
                break;

            default:

                break;

        }


    }


    $y21 = mt_rand(5, 20);
    captcha_vector($image, $x, $y21 / 2, 180, 200, $bgcolor);

    $y21 = mt_rand(5, 20);
    captcha_vector($image, $x, $y21 / 2, $col1z11, 200, $bgcolor);

    $y21 = mt_rand(5, 20);
    captcha_vector($image, $x / 3, $y21 / 3, $col1z11, 200, $bgcolor);


    //   imagestring($image, 5, 2, 2, $text, $black);

    $emboss = array(array(2, 0, 0), array(0, -1, 0), array(0, 0, -1));
    $embize = mt_rand(1, 4);
    // imageconvolution($image, $emboss, $embize, 255);
    //   imagefilter($image, IMG_FILTER_SMOOTH, 50);
    imagepng($image);
    imagecolordeallocate($image, $bgcolor);
    imagecolordeallocate($image, $black);

    imagedestroy($image);
}

function api_login($api_key = false)
{

    if ($api_key == false and isset($_REQUEST['api_key']) and user_id() == 0) {
        $api_key = $_REQUEST['api_key'];
    }

    if ($api_key == false) {
        return false;
    } else {
        if (trim($api_key) == '') {
            return false;
        } else {
            $api_key = db_escape_string($api_key);
            if (user_id() > 0) {
                return true;
            } else {
                $data = array();
                $data['api_key'] = $api_key;
                $data['is_active'] = 'y';
                $data['limit'] = 1;

                $data = get_users($data);

                if ($data != false) {
                    if (isset($data[0])) {
                        $data = $data[0];

                        if (isset($data['api_key']) and $data['api_key'] == $api_key) {
                            return user_login($data);

                        }

                    }

                }
            }

        }
    }

}

function update_user_last_login_time()
{

    $uid = user_id();
    if (intval($uid) > 0) {

        $data_to_save = array();
        $data_to_save['id'] = $uid;
        $data_to_save['last_login'] = date("Y-m-d H:i:s");
        $data_to_save['last_login_ip'] = USER_IP;

        $table = MW_DB_TABLE_USERS;
		mw_var("FORCE_SAVE", MW_DB_TABLE_USERS);
        $save = save_data($table, $data_to_save);

        delete_log("is_system=y&rel=login_failed&user_ip=" . USER_IP);

    }

}

api_expose('social_login_process');
function social_login_process()
{
    set_exception_handler('social_login_exception_handler');

    $api = new \mw\auth\Social();
    $api->process();

    // d($err);
    //$err= $api->is_error();

}

function social_login_exception_handler($exception)
{

    if (isAjax()) {
        return array('error' => $exception->getMessage());
    }

    $after_log = session_get('user_after_login');
    if ($after_log != false) {
        safe_redirect($after_log);
    } else {
        safe_redirect(site_url());
    }

}





api_expose('user_reset_password_from_link');
function user_reset_password_from_link($params)
{
    if (!isset($params['captcha'])) {
        return array('error' => 'Please enter the captcha answer!');
    } else {
        $cap = session_get('captcha');
        if ($cap == false) {
            return array('error' => 'You must load a captcha first!');
        }
        if ($params['captcha'] != $cap) {
            return array('error' => 'Invalid captcha answer!');
        }
    }

    if (!isset($params['id']) or trim($params['id']) == '') {
        return array('error' => 'You must send id parameter');
    }

    if (!isset($params['password_reset_hash']) or trim($params['password_reset_hash']) == '') {
        return array('error' => 'You must send password_reset_hash parameter');
    }

    if (!isset($params['pass1']) or trim($params['pass1']) == '') {
        return array('error' => 'Enter new password!');
    }

    if (!isset($params['pass2']) or trim($params['pass2']) == '') {
        return array('error' => 'Enter repeat new password!');
    }

    if ($params['pass1'] != $params['pass2']) {
        return array('error' => 'Your passwords does not match!');
    }

    $data1 = array();
    $data1['id'] = intval($params['id']);
    $data1['password_reset_hash'] = db_escape_string($params['password_reset_hash']);
    $table = MW_DB_TABLE_USERS;

    $check = get_users("single=true&password_reset_hash=[not_null]&password_reset_hash=" . $data1['password_reset_hash'] . '&id=' . $data1['id']);
    if (!isarr($check)) {
        return array('error' => 'Invalid data or expired link!');
    } else {
        $data1['password'] = $params['pass1'];
        $data1['password_reset_hash'] = '';


        $data1['password'] = hash_user_pass($data1['password']);


    }


    mw_var('FORCE_SAVE', $table);

    $save = save_data($table, $data1);

    $notif = array();
    $notif['module'] = "users";
    $notif['rel'] = 'users';
    $notif['rel_id'] = $data1['id'];
    $notif['title'] = "The user have successfully changed password. (User id: {$data1['id']})";

    save_log($notif);

    return array('success' => 'Your password have been changed!');

}

api_expose('user_send_forgot_password');
function user_send_forgot_password($params)
{

    if (!isset($params['captcha'])) {
        return array('error' => 'Please enter the captcha answer!');
    } else {
        $cap = session_get('captcha');
        if ($cap == false) {
            return array('error' => 'You must load a captcha first!');
        }
        if ($params['captcha'] != $cap) {
            return array('error' => 'Invalid captcha answer!');
        }
    }
    if (!isset($params['username']) or trim($params['username']) == '') {
        return array('error' => 'Enter username or email!');
    }

    if (isset($params) and !empty($params)) {

        $user = isset($params['username']) ? $params['username'] : false;

        if (trim($user != '')) {
            $data1 = array();
            $data1['username'] = $user;
            //$data1['oauth_uid'] = '[null]';
            //$data1['oauth_provider'] = '[null]';
            $data = array();
            $data_res = false;
            if (trim($user != '')) {
                $data = get_users($data1);
            }

            if (isset($data[0])) {
                $data_res = $data[0];

            } else {
                $data1 = array();
                $data1['email'] = $user;
                //$data1['oauth_uid'] = '[null]';
                //$data1['oauth_provider'] = '[null]';
                $data = get_users($data1);
                if (isset($data[0])) {
                    $data_res = $data[0];

                }

            }
            if (!isarr($data_res)) {
                return array('error' => 'Enter right username or email!');

            } else {
                $to = $data_res['email'];
                if (isset($to) and (filter_var($to, FILTER_VALIDATE_EMAIL))) {

                    $subject = "Password reset!";
                    $content = "Hello, {$data_res['username']} <br> ";
                    $content .= "You have requested a password reset link from IP address: " . USER_IP . "<br><br> ";

                    //$content .= "on " . curent_url(1) . "<br><br> ";

                    $security = array();
                    $security['ip'] = USER_IP;
                    $security['hash'] = encode_var($data_res);
                    $function_cache_id = md5(serialize($security)) . uniqid() . rand();
                    //cache_save($security, $function_cache_id, $cache_group = 'password_reset');
                    if (isset($data_res['id'])) {
                        $data_to_save = array();
                        $data_to_save['id'] = $data_res['id'];
                        $data_to_save['password_reset_hash'] = $function_cache_id;

                        $table = MW_DB_TABLE_USERS;
                        mw_var('FORCE_SAVE', $table);

                        $save = save_data($table, $data_to_save);
                    }
                    $pass_reset_link = curent_url(1) . '?reset_password_link=' . $function_cache_id;

                    $notif = array();
                    $notif['module'] = "users";
                    $notif['rel'] = 'users';
                    $notif['rel_id'] = $data_to_save['id'];
                    $notif['title'] = "Password reset link sent";
                    $content_notif = "User with id: {$data_to_save['id']} and email: {$to}  has requested a password reset link";
                    $notif['description'] = $content_notif;

                    save_log($notif);
                    $content .= "Click here to reset your password  <a href='{$pass_reset_link}'>" . $pass_reset_link . "</a><br><br> ";

                    //d($data_res);
                    \mw\email\Sender::send($to, $subject, $content, true, $no_cache = true);

                    return array('success' => 'Your password reset link has been sent to ' . $to);
                } else {
                    return array('error' => 'Error: the user doesn\'t have a valid email address!');
                }

            }

        }

    }

}

function user_login_set_failed_attempt()
{

    save_log("title=Failed login&is_system=y&rel=login_failed&user_ip=" . USER_IP);

}

api_expose('is_logged');

function is_logged()
{

    //ignore_user_abort();

    mw_cron();
   // if(isAjax()){
    //
   // }

    if(user_id() >0){
         return true;
    } else {
        return false;
    }




}
function user_id()
{

    // static $uid;
    if (defined('USER_ID')) {
        // print USER_ID;
        return USER_ID;
    } else {

        $user_session = session_get('user_session');
        if ($user_session == FALSE) {
            return false;
        }
        $res = false;
        if (isset($user_session['user_id'])) {
            $res = $user_session['user_id'];
        }

        if ($res != false) {
            // $res = $sess->get ( 'user_id' );
            define("USER_ID", $res);
        }
        return $res;
    }
}

function has_access($function_name)
{

    $is_a = is_admin();

    if ($is_a == true) {
        return true;
    } else {
        return false;
    }
}

function admin_access()
{
    if (is_admin() == false) {
        exit('You must be logged as admin');
    }

}

function only_admin_access()
{
    if (is_admin() == false) {
        exit('You must be logged as admin');
    }

}

function is_admin()
{

    static $is = 0;
    if (MW_IS_INSTALLED == false) {
        return true;
    }
    if ($is != 0 or defined('USER_IS_ADMIN')) {
        // var_dump( $is);
        return $is;
    } else {
        $usr = user_id();
        if ($usr == false) {
            return false;
        }
        $usr = get_user($usr);

        if (isset($usr['is_admin']) and $usr['is_admin'] == 'y') {
            define("USER_IS_ADMIN", true);
            define("IS_ADMIN", true);
        } else {
            define("USER_IS_ADMIN", false);
            define("IS_ADMIN", false);
        }
        $is = USER_IS_ADMIN;
        // var_dump( $is);
        // var_dump( $is);
        // var_dump( USER_IS_ADMIN.USER_IS_ADMIN.USER_IS_ADMIN);
        return USER_IS_ADMIN;
    }
}

/**
 * @function user_name
 * gets the user's FULL name
 *
 * @param $user_id  the id of the user. If false it will use the curent user (you)
 * @param string $mode full|first|last|username
 *  'full' //prints full name (first +last)
 *  'first' //prints first name
 *  'last' //prints last name
 *  'username' //prints username
 * @return string
 */
function user_name($user_id = false, $mode = 'full')
{
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

/**
 * @function get_users
 *
 * @param $params array|string;
 * @params $params['username'] string username for user
 * @params $params['email'] string email for user
 * @params $params['password'] string password for user
 *
 *
 * @usage get_users('email=my_email');
 *
 *
 * @return array of users;
 */
function get_users($params)
{
    $params = parse_params($params);

    $table = MW_TABLE_PREFIX . 'users';

    $data = string_clean($params);
    $orig_data = $data;

    if (isset($data['ids']) and is_array($data['ids'])) {
        if (!empty($data['ids'])) {
            $ids = $data['ids'];
        }
    }
    if (!isset($params['search_in_fields'])) {
        $data['search_in_fields'] = array('first_name', 'last_name', 'username', 'email');
        // $data ['debug'] = 1;
    }

    $cache_group = 'users/global';
    if (isset($data['id']) and intval($data['id']) != 0) {
        $cache_group = 'users/' . $data['id'];
    } else {

    }
    $cache_group = 'users/global';
    if (isset($limit) and $limit != false) {
        $data['limit'] = $limit;
    }

    if (isset($count_only) and $count_only != false) {
        $data['get_count'] = $count_only;
    }

    if (isset($data['only_those_fields']) and $data['only_those_fields']) {
        $only_those_fields = $data['only_those_fields'];
    }

    if (isset($data['count']) and $data['count']) {
        $count_only = $data['count'];
    }

    // $data ['no_cache'] = 1;

    if (isset($data['username']) and $data['username'] == null) {
        unset($data['username']);
    }
    if (isset($data['username']) and $data['username'] == '') {
        //return false;
    }

    // $function_cache_id = __FUNCTION__ . crc32($function_cache_id);
    $data['table'] = $table;
    //  $data ['cache_group'] = $cache_group;

    $get = get($data);

    //$get = db_get($table, $criteria = $data, $cache_group);
    // $get = db_get($table, $criteria = $data, $cache_group);
    // var_dump($get, $function_cache_id, $cache_group);
    //  cache_save($get, $function_cache_id, $cache_group);

    return $get;
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
 *            the id of the user;
 * @return array
 */
function get_user($id = false)
{
    if ($id == false) {
        $id = user_id();
    }

    if ($id == 0) {
        return false;
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
 *            int id
 * @return array
 *
 */
function get_user_by_id($id)
{
    $id = intval($id);
    if ($id == 0) {
        return false;
    }

    $data = array();
    $data['id'] = $id;
    $data['limit'] = 1;
    $data = get_users($data);
    if (isset($data[0])) {
        $data = $data[0];
    }
    return $data;
}

function get_user_by_username($username)
{
    $data = array();
    $data['username'] = $username;
    $data['limit'] = 1;
    $data = get_users($data);
    if (isset($data[0])) {
        $data = $data[0];
    }
    return $data;
}

/**
 * Function to get user printable name by given ID
 *
 * @param  $id
 * @param string $mode
 * @return string
 * @example
 * <code>
 * //get user name for user with id 10
 * nice_user_name(10, 'full');
 * </code>
 * @uses get_user_by_id()
 */
function nice_user_name($id, $mode = 'full')
{
    $user = get_user_by_id($id);
    $user_data = $user;
    if (empty($user)) {
        return false;
    }

    switch ($mode) {
        case 'first' :
        case 'fist' :
            // because of a common typo :)
            $user_data['first_name'] ? $name = $user_data['first_name'] : $name = $user_data['username'];
            $name = ucwords($name);
            // return $name;
            break;

        case 'last' :
            $user_data['last_name'] ? $name = $user_data['last_name'] : $name = $user_data['last_name'];
            $name = ucwords($name);
            // return $name;
            break;

        case 'username' :
            $name = $user_data['username'];
            // return $name;
            break;

        case 'full' :
        default :
            $name = $user_data['first_name'] . ' ' . $user_data['last_name'];

            if (trim($name) == '') {
                $name = $user_data['username'];
            }

            $name = ucwords($name);
            // return $name;
            break;
    }


    if (!isset($name) or $name == false or $name == NULL or trim($name) == '') {
        if (isset($user_data['username']) and $user_data['username'] != false and trim($user_data['username']) != '') {
            $name = $user_data['username'];
        } else if (isset($user_data['email']) and $user_data['email'] != false and trim($user_data['email']) != '') {
            $name = $user_data['email'];
        }
    }

    return $name;

}

function users_count()
{
    $options = array();
    $options['get_count'] = true;
    // $options ['debug'] = true;
    $options['count'] = true;
    // $options ['no_cache'] = true;
    $options['cache_group'] = 'users/global/';

    $data = get_users($options);

    return $data;
}

function cf_get_user($user_id, $field_name)
{
    $fields = get_custom_fields_for_user($user_id);
    if (empty($fields)) {
        return false;
    }

    foreach ($fields as $field) {
        if (trim(strtolower($field_name)) == trim(strtolower($field['custom_field_name']))) {

            if ($field['custom_field_value']) {
                return $field['custom_field_value'];
            } else {

                if ($field['custom_field_values']) {
                    return $field['custom_field_values'];
                }
            }

            // p ( $field );
        }
    }
}

/**
 * A Compatibility library with PHP 5.5's simplified password hashing API.
 *
 * @author Anthony Ferrara <ircmaxell@php.net>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2012 The Authors
 */

if (!defined('PASSWORD_BCRYPT')) {

    define('PASSWORD_BCRYPT', 1);
    define('PASSWORD_DEFAULT', PASSWORD_BCRYPT);

    /**
     * Hash the password using the specified algorithm
     *
     * @param string $password The password to hash
     * @param int $algo     The algorithm to use (Defined by PASSWORD_* constants)
     * @param array $options  The options for the algorithm to use
     *
     * @return string|false The hashed password, or false on error.
     */
    function password_hash($password, $algo, array $options = array())
    {
        if (!function_exists('crypt')) {
            trigger_error("Crypt must be loaded for password_hash to function", E_USER_WARNING);
            return null;
        }
        if (!is_string($password)) {
            trigger_error("password_hash(): Password must be a string", E_USER_WARNING);
            return null;
        }
        if (!is_int($algo)) {
            trigger_error("password_hash() expects parameter 2 to be long, " . gettype($algo) . " given", E_USER_WARNING);
            return null;
        }
        switch ($algo) {
            case PASSWORD_BCRYPT :
                // Note that this is a C constant, but not exposed to PHP, so we don't define it here.
                $cost = 10;
                if (isset($options['cost'])) {
                    $cost = $options['cost'];
                    if ($cost < 4 || $cost > 31) {
                        trigger_error(sprintf("password_hash(): Invalid bcrypt cost parameter specified: %d", $cost), E_USER_WARNING);
                        return null;
                    }
                }
                $required_salt_len = 22;
                $hash_format = sprintf("$2y$%02d$", $cost);
                break;
            default :
                trigger_error(sprintf("password_hash(): Unknown password hashing algorithm: %s", $algo), E_USER_WARNING);
                return null;
        }
        if (isset($options['salt'])) {
            switch (gettype($options['salt'])) {
                case 'NULL' :
                case 'boolean' :
                case 'integer' :
                case 'double' :
                case 'string' :
                    $salt = (string)$options['salt'];
                    break;
                case 'object' :
                    if (method_exists($options['salt'], '__tostring')) {
                        $salt = (string)$options['salt'];
                        break;
                    }
                case 'array' :
                case 'resource' :
                default :
                    trigger_error('password_hash(): Non-string salt parameter supplied', E_USER_WARNING);
                    return null;
            }
            if (strlen($salt) < $required_salt_len) {
                trigger_error(sprintf("password_hash(): Provided salt is too short: %d expecting %d", strlen($salt), $required_salt_len), E_USER_WARNING);
                return null;
            } elseif (0 == preg_match('#^[a-zA-Z0-9./]+$#D', $salt)) {
                $salt = str_replace('+', '.', base64_encode($salt));
            }
        } else {
            $buffer = '';
            $raw_length = (int)($required_salt_len * 3 / 4 + 1);
            $buffer_valid = false;
            if (function_exists('mcrypt_create_iv') && !defined('PHALANGER')) {
                $buffer = mcrypt_create_iv($raw_length, MCRYPT_DEV_URANDOM);
                if ($buffer) {
                    $buffer_valid = true;
                }
            }
            if (!$buffer_valid && function_exists('openssl_random_pseudo_bytes')) {
                $buffer = openssl_random_pseudo_bytes($raw_length);
                if ($buffer) {
                    $buffer_valid = true;
                }
            }
            if (!$buffer_valid && is_readable('/dev/urandom')) {
                $f = fopen('/dev/urandom', 'r');
                $read = strlen($buffer);
                while ($read < $raw_length) {
                    $buffer .= fread($f, $raw_length - $read);
                    $read = strlen($buffer);
                }
                fclose($f);
                if ($read >= $raw_length) {
                    $buffer_valid = true;
                }
            }
            if (!$buffer_valid || strlen($buffer) < $raw_length) {
                $bl = strlen($buffer);
                for ($i = 0; $i < $raw_length; $i++) {
                    if ($i < $bl) {
                        $buffer[$i] = $buffer[$i] ^ chr(mt_rand(0, 255));
                    } else {
                        $buffer .= chr(mt_rand(0, 255));
                    }
                }
            }
            $salt = str_replace('+', '.', base64_encode($buffer));

        }
        $salt = substr($salt, 0, $required_salt_len);

        $hash = $hash_format . $salt;

        $ret = crypt($password, $hash);

        if (!is_string($ret) || strlen($ret) <= 3) {
            return false;
        }

        return $ret;
    }

    /**
     * Get information about the password hash. Returns an array of the information
     * that was used to generate the password hash.
     *
     * array(
     *    'algo' => 1,
     *    'algoName' => 'bcrypt',
     *    'options' => array(
     *        'cost' => 10,
     *    ),
     * )
     *
     * @param string $hash The password hash to extract info from
     *
     * @return array The array of information about the hash.
     */
    function password_get_info($hash)
    {
        $return = array('algo' => 0, 'algoName' => 'unknown', 'options' => array(),);
        if (substr($hash, 0, 4) == '$2y$' && strlen($hash) == 60) {
            $return['algo'] = PASSWORD_BCRYPT;
            $return['algoName'] = 'bcrypt';
            list($cost) = sscanf($hash, "$2y$%d$");
            $return['options']['cost'] = $cost;
        }
        return $return;
    }

    /**
     * Determine if the password hash needs to be rehashed according to the options provided
     *
     * If the answer is true, after validating the password using password_verify, rehash it.
     *
     * @param string $hash    The hash to test
     * @param int $algo    The algorithm used for new password hashes
     * @param array $options The options array passed to password_hash
     *
     * @return boolean True if the password needs to be rehashed.
     */
    function password_needs_rehash($hash, $algo, array $options = array())
    {
        $info = password_get_info($hash);
        if ($info['algo'] != $algo) {
            return true;
        }
        switch ($algo) {
            case PASSWORD_BCRYPT :
                $cost = isset($options['cost']) ? $options['cost'] : 10;
                if ($cost != $info['options']['cost']) {
                    return true;
                }
                break;
        }
        return false;
    }

    /**
     * Verify a password against a hash using a timing attack resistant approach
     *
     * @param string $password The password to verify
     * @param string $hash     The hash to verify against
     *
     * @return boolean If the password matches the hash
     */
    function password_verify($password, $hash)
    {
        if (!function_exists('crypt')) {
            trigger_error("Crypt must be loaded for password_verify to function", E_USER_WARNING);
            return false;
        }
        $ret = crypt($password, $hash);
        if (!is_string($ret) || strlen($ret) != strlen($hash) || strlen($ret) <= 13) {
            return false;
        }

        $status = 0;
        for ($i = 0; $i < strlen($ret); $i++) {
            $status |= (ord($ret[$i]) ^ ord($hash[$i]));
        }

        return $status === 0;
    }

}