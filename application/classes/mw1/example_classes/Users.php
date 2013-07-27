<?php
namespace mw;
if (!defined("MW_DB_TABLE_USERS")) {
    define('MW_DB_TABLE_USERS', MW_TABLE_PREFIX . 'users');
}
if (!defined("MW_DB_TABLE_LOG")) {
    define('MW_DB_TABLE_LOG', MW_TABLE_PREFIX . 'log');
}
action_hook('mw_db_init_default', '\mw\Users::db_init');
action_hook('mw_db_init', '\mw\Users::db_init');
class Users
{

    static function register($params)
    {


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

        $override = exec_action('before_user_register', $params);

        if (is_arr($override)) {
            foreach ($override as $resp) {
                if (isset($resp['error']) or isset($resp['success'])) {
                    return $resp;
                }
            }
        }
//    if (!isset($params['password'])) {
//        return array('error' => 'Please set password!');
//    } else {
//        if ($params['password'] == '') {
//            return array('error' => 'Please set password!');
//        }
//    }


        if (isset($params['password']) and  ($params['password']) != '') {
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
                    $next = \mw\Db::last_id($table);
                    $next = intval($next) + 1;
                    $q = "INSERT INTO $table (id,email, password, is_active)
			VALUES ($next, '$email', '$pass', 'y')";


                    \mw\Db::q($q);
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

                    if (isset($pass) and $pass != '' and isset($user_data['password']) && $user_data['password'] == $pass) {
                        if (isset($user_data['email']) && $user_data['email'] != '') {
                            $is_logged = user_login('email=' . $user_data['email'] . '&password_hashed=' . $pass);
                        } else if (isset($user_data['username']) && $user_data['username'] != '') {
                            $is_logged = user_login('username=' . $user_data['username'] . '&password_hashed=' . $pass);
                        }
                        if (isset($is_logged) and isarr($is_logged) and isset($is_logged['success']) and isset($is_logged['is_logged'])) {
                            return ($is_logged);
                            // $user_session['success']
                        }

                    }


                    return array('error' => 'This user already exists!');
                }
            }
        }


    }



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
     * For security reasons, to make new user please use user_register() function that requires captcha
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
   static function save($params)
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
        $save = \mw\Db::save($table, $data_to_save);
        $id = $save;
        cache_clean_group('users' . DIRECTORY_SEPARATOR . 'global');
        cache_clean_group('users' . DIRECTORY_SEPARATOR . '0');
        cache_clean_group('users' . DIRECTORY_SEPARATOR . $id);
        return $id;
    }
    function delete($data)
    {
        $adm = is_admin();
        if ($adm == false) {
            error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }

        if (isset($data['id'])) {
            $c_id = intval($data['id']);
            \mw\Db::delete_by_id('users', $c_id);
            return $c_id;

        }
        return $data;
    }


    static function update_last_login_time()
    {

        $uid = user_id();
        if (intval($uid) > 0) {

            $data_to_save = array();
            $data_to_save['id'] = $uid;
            $data_to_save['last_login'] = date("Y-m-d H:i:s");
            $data_to_save['last_login_ip'] = USER_IP;

            $table = MW_DB_TABLE_USERS;
            mw_var("FORCE_SAVE", MW_DB_TABLE_USERS);
            $save = \mw\Db::save($table, $data_to_save);

            delete_log("is_system=y&rel=login_failed&user_ip=" . USER_IP);

        }

    }


   static function reset_password_from_link($params)
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

        $save = \mw\Db::save($table, $data1);

        $notif = array();
        $notif['module'] = "users";
        $notif['rel'] = 'users';
        $notif['rel_id'] = $data1['id'];
        $notif['title'] = "The user have successfully changed password. (User id: {$data1['id']})";

        save_log($notif);

        return array('success' => 'Your password have been changed!');

    }

    static function send_forgot_password($params)
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

                            $save = \mw\Db::save($table, $data_to_save);
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


    static function  social_login($params)
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

                        $save = \mw\Db::save($table, $data_to_save);
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
                        user_set_logged($data['id']);

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
    static function social_login_process()
    {
        set_exception_handler('social_login_exception_handler');

        $api = new \mw\auth\Social();
        $api->process();

        // d($err);
        //$err= $api->is_error();

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
    static function get($params)
    {
        return \mw\User::get_all($params);
    }


    static function count()
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

    static function db_init()
    {
        $function_cache_id = false;

        $args = func_get_args();

        foreach ($args as $k => $v) {

            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
        }

        $function_cache_id = 'users'.__FUNCTION__ . crc32($function_cache_id);

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

        \mw\DbUtils::build_table($table_name, $fields_to_add);

        \mw\DbUtils::add_table_index('username', $table_name, array('username(255)'));
        \mw\DbUtils::add_table_index('email', $table_name, array('email(255)'));


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

        \mw\DbUtils::build_table($table_name, $fields_to_add);

        cache_save(true, $function_cache_id, $cache_group = 'db');
        return true;

    }

}
if(!function_exists('social_login_exception_handler')){
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
}