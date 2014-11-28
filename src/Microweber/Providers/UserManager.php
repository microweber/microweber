<?php



/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber LTD
 *
 * For full license information see
 * http://MicroweberCMS.com/license/
 *
 */

namespace Microweber\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\User as DefaultUserProvider;
use Microweber\Utils\Database as DbManager;


use Auth;

use Illuminate\Support\Facades\Session;

if (!defined('MW_USER_IP')) {
    if (isset($_SERVER["REMOTE_ADDR"])) {
        define("MW_USER_IP", $_SERVER["REMOTE_ADDR"]);
    } else {
        define("MW_USER_IP", '127.0.0.1');

    }
}


class UserManager
{
    public $tables = array();


    function __construct($app = null)
    {
        $this->set_table_names();


        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = mw();
        }

    }

    public function install()
    {
        $this->db_init();
    }


    public function set_table_names($tables = false)
    {

        if (!is_array($tables)) {
            $tables = array();
        }
        if (!isset($tables['users'])) {
            $tables['users'] = 'users';
        }
        if (!isset($tables['log'])) {
            $tables['log'] = 'log';
        }
        $this->tables['users'] = $tables['users'];
        $this->tables['log'] = $tables['log'];


    }

    public function db_init()
    {


//        $table_name = $this->tables['users'];
//
//        $fields_to_add = array();
//
//        $fields_to_add['updated_on'] = 'dateTime';
//        $fields_to_add['created_on'] = 'dateTime';
//        $fields_to_add['expires_on'] = 'dateTime';
//        $fields_to_add['last_login'] = 'dateTime';
//        $fields_to_add['last_login_ip'] = 'longText';
//
//        $fields_to_add['created_by'] = 'integer';
//
//        $fields_to_add['edited_by'] = 'integer';
//
//        $fields_to_add['username'] = 'longText';
//
//        $fields_to_add['password'] = 'longText';
//        $fields_to_add['email'] = 'longText';
//
//        $fields_to_add['is_active'] = "string";
//        $fields_to_add['is_admin'] = "string";
//        $fields_to_add['is_verified'] = "string";
//        $fields_to_add['is_public'] = "string";
//
//        $fields_to_add['basic_mode'] = "string";
//
//        $fields_to_add['first_name'] = 'longText';
//        $fields_to_add['last_name'] = 'longText';
//        $fields_to_add['thumbnail'] = 'longText';
//
//        $fields_to_add['parent_id'] = 'integer';
//
//        $fields_to_add['api_key'] = 'longText';
//
//        $fields_to_add['user_information'] = 'longText';
//        $fields_to_add['subscr_id'] = 'longText';
//        $fields_to_add['role'] = 'longText';
//        $fields_to_add['medium'] = 'longText';
//
//        $fields_to_add['oauth_uid'] = 'longText';
//        $fields_to_add['oauth_provider'] = 'longText';
//        $fields_to_add['oauth_token'] = 'longText';
//        $fields_to_add['oauth_token_secret'] = 'longText';
//
//        $fields_to_add['profile_url'] = 'longText';
//        $fields_to_add['website_url'] = 'longText';
//        $fields_to_add['password_reset_hash'] = 'longText';
//
//        mw()->database_manager->build_table($table_name, $fields_to_add);
//
//        mw()->database_manager->add_table_index('username', $table_name, array('username(255)'));
//        mw()->database_manager->add_table_index('email', $table_name, array('email(255)'));
//
//
//        $table_name = $this->tables['log'];
//
//        $fields_to_add = array();
//
//        $fields_to_add['updated_on'] = 'dateTime';
//        $fields_to_add['created_on'] = 'dateTime';
//        $fields_to_add['created_by'] = 'integer';
//        $fields_to_add['edited_by'] = 'integer';
//        $fields_to_add['rel'] = 'longText';
//
//        $fields_to_add['rel_id'] = 'longText';
//        $fields_to_add['position'] = 'integer';
//
//        $fields_to_add['field'] = 'longText';
//        $fields_to_add['value'] = 'longText';
//        $fields_to_add['module'] = 'longText';
//
//        $fields_to_add['data_type'] = 'longText';
//        $fields_to_add['title'] = 'longText';
//        $fields_to_add['description'] = 'longText';
//        $fields_to_add['content'] = 'longText';
//        $fields_to_add['user_ip'] = 'longText';
//        $fields_to_add['session_id'] = 'longText';
//        $fields_to_add['is_system'] = "string";
//
//        mw()->database_manager->build_table($table_name, $fields_to_add);

        return true;

    }

    public function logout($params = false)
    {

        if (!defined('USER_ID')) {
            define("USER_ID", false);
        }
        $this->app->event_manager->trigger('user_logout');

        // static $uid;
        $aj = $this->app->url->is_ajax();
        $this->session_end();
        $redirect_after = isset($_GET['redirect']) ? $_GET['redirect'] : false;

        if (isset($_COOKIE['editmode'])) {
            setcookie('editmode');
        }

        if ($redirect_after == false and $aj == false) {
            if (isset($_SERVER["HTTP_REFERER"])) {
                $this->app->url->redirect($_SERVER["HTTP_REFERER"]);
            }
        }


        if ($redirect_after == true) {
            $redir = site_url($redirect_after);
            $this->app->url->redirect($redir);
            exit();
        }


    }

    public function is_logged()
    {

        if ($this->id() > 0) {
            return true;
        } else {
            return false;
        }


    }

    public function has_access($function_name)
    {

        // will be updated with roles and perms
        $is_a = $this->is_admin();

        if ($is_a == true) {
            return true;
        } else {
            return false;
        }
    }

    public function admin_access()
    {

        if ($this->is_admin() == false) {
            exit('You must be logged as admin');
        }
    }

    public function picture($user_id = false)
    {


        $name = $this->get_by_id($user_id);
        if (isset($name['thumbnail']) and $name['thumbnail'] != '') {
            return $name['thumbnail'];
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
    public function name($user_id = false, $mode = 'full')
    {
        if ($mode != 'username') {
            if ($user_id == user_id()) {
                // return 'You';
            }
        }
        if ($user_id == false) {
            $user_id = user_id();
        }

        $name = $this->nice_name($user_id, $mode);
        return $name;
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
     * $this->nice_name(10, 'full');
     * </code>
     * @uses $this->get_by_id()
     */
    public function nice_name($id, $mode = 'full')
    {
        $user = $this->get_by_id($id);
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
                if (trim($name) == '' and $user_data['email'] != '') {
                    $n = explode('@', $user_data['email']);
                    $name = $n[0];
                }
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
                $name = ucwords($name);
                if (trim($name) == '' and $user_data['email'] != '') {
                    $name = $user_data['email'];
                    $name_from_email = explode('@', $user_data['email']);
                    $name = $name_from_email[0];
                }
                if (trim($name) == '' and $user_data['username'] != '') {
                    $name = $user_data['username'];
                    $name = ucwords($name);
                }

                break;
        }


        if (!isset($name) or $name == false or $name == NULL or trim($name) == '') {
            if (isset($user_data['username']) and $user_data['username'] != false and trim($user_data['username']) != '') {
                $name = $user_data['username'];
            } else if (isset($user_data['email']) and $user_data['email'] != false and trim($user_data['email']) != '') {
                $name_from_email = explode('@', $user_data['email']);
                $name = $name_from_email[0];
            }
        }

        return $name;

    }

    public function api_login($api_key = false)
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
                $api_key = $this->app->database_manager->escape_string($api_key);
                if (user_id() > 0) {
                    return true;
                } else {
                    $data = array();
                    $data['api_key'] = $api_key;
                    $data['is_active'] = 'y';
                    $data['limit'] = 1;

                    $data = $this->get_all($data);

                    if ($data != false) {
                        if (isset($data[0])) {
                            $data = $data[0];

                            if (isset($data['api_key']) and $data['api_key'] == $api_key) {
                                return $this->login($data);

                            }

                        }

                    }
                }

            }
        }

    }

    public function register($params)
    {

        if (defined("MW_API_FUNCTION_CALL") and (MW_API_FUNCTION_CALL == 'user_register' or MW_API_FUNCTION_CALL == 'user/register')) {
            if ($this->is_admin() == false) {
                $validate_token = $this->app->user_manager->csrf_validate($params);
                if ($validate_token == false) {
                    return array('error' => 'Invalid token!');
                }
            }
        }
        $user = isset($params['username']) ? $params['username'] : false;
        $pass = isset($params['password']) ? $params['password'] : false;
        $email = isset($params['email']) ? $params['email'] : false;
        $first_name = isset($params['first_name']) ? $params['first_name'] : false;
        $last_name = isset($params['last_name']) ? $params['last_name'] : false;
        $pass2 = $pass;
        $pass = $this->hash_pass($pass);

        if (!isset($params['captcha'])) {
            return array('error' => 'Please enter the captcha answer!');
        } else {
            $cap = $this->session_get('captcha');
            if ($cap == false) {
                return array('error' => 'You must load a captcha first!');
            }
            if ($params['captcha'] != $cap) {
                return array('error' => 'Invalid captcha answer!');
            }
        }

        $override = $this->app->event_manager->trigger('before_user_register', $params);

        if (is_array($override)) {
            foreach ($override as $resp) {
                if (isset($resp['error']) or isset($resp['success'])) {
                    return $resp;
                }
            }
        }


        if (defined("MW_API_CALL")) {
            if (isset($params['is_admin']) and $this->is_admin() == false) {
                unset($params['is_admin']);
            }
        }


//    if (!isset($params['password'])) {
//        return array('error' => 'Please set password!');
//    } else {
//        if ($params['password'] == '') {
//            return array('error' => 'Please set password!');
//        }
//    }


        if (isset($params['password']) and ($params['password']) != '') {
            if ($email != false) {

                $data = array();
                $data['email'] = $email;
                // $data['password'] = $pass;
                //  $data['oauth_uid'] = '[null]';
                // $data['oauth_provider'] = '[null]';
                $data['one'] = true;
                $data['no_cache'] = true;
                // $data ['is_active'] = 'y';
                $user_data = $this->get_all($data);


                if (empty($user_data)) {

                    $data = array();
                    $data['username'] = $email;
                    //  $data['password'] = $pass;
                    //  $data['oauth_uid'] = '[null]';
                    //  $data['oauth_provider'] = '[null]';
                    $data['one'] = true;
                    $data['no_cache'] = true;
                    // $data ['is_active'] = 'y';
                    $user_data = $this->get_all($data);
                }


                if (empty($user_data)) {
                    $data = array();


                    $data['username'] = $email;
                    $data['password'] = $pass;
                    $data['is_active'] = 'n';

                    //   $table = get_table_prefix() . 'users';
                    $table = $this->tables['users'];


                    $reg = array();
                    $reg['username'] = $user;
                    $reg['email'] = $email;
                    $reg['password'] = $pass2;
                    $reg['is_active'] = 'y';
                    $reg['first_name'] = $first_name;
                    $reg['last_name'] = $first_name;

                    $this->force_save = true;

                    $next = $this->save($reg);
                    $this->force_save = false;


                    $this->app->cache_manager->delete('users/global');
                    $this->session_del('captcha');

                    $notif = array();
                    $notif['module'] = "users";
                    $notif['rel'] = 'users';
                    $notif['rel_id'] = $next;
                    $notif['title'] = "New user registration";
                    $notif['description'] = "You have new user registration";
                    $notif['content'] = "You have new user registered with the username [" . $data['username'] . '] and id [' . $next . ']';
                    $this->app->notifications->save($notif);

                    $this->app->log_manager->save($notif);


                    $params = $data;
                    $params['id'] = $next;
                    if (isset($pass2)) {
                        $params['password2'] = $pass2;
                    }
                    $this->app->event_manager->trigger('after_user_register', $params);


                    return array('success' => 'You have registered successfully');

                } else {

                    if (isset($pass) and $pass != '' and isset($user_data['password']) && $user_data['password'] == $pass) {
                        if (isset($user_data['email']) && $user_data['email'] != '') {
                            $is_logged = $this->login('email=' . $user_data['email'] . '&password_hashed=' . $pass);
                        } else if (isset($user_data['username']) && $user_data['username'] != '') {
                            $is_logged = $this->login('username=' . $user_data['username'] . '&password_hashed=' . $pass);
                        }
                        if (isset($is_logged) and is_array($is_logged) and isset($is_logged['success']) and isset($is_logged['is_logged'])) {
                            return ($is_logged);
                            // $user_session['success']
                        }

                    }


                    return array('error' => 'This user already exists!');
                }
            }
        }


    }

    public function is_admin()
    {

        if (!mw_is_installed()) {
            return false;
        }

        if (Auth::check()) {
            return Auth::user()->ifAdmin;
        }
    }

    public function id()
    {


        if (defined('USER_ID')) {
            return USER_ID;
        } else {

            $user_session = $this->session_get('user_session');
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

    function csrf_validate(&$data)
    {


        if (is_array($data) and isset($_SESSION)) {
            foreach ($data as $k => $v) {


                foreach ($_SESSION as $sk => $sv) {
                    if (is_string($sk) and strstr($sk, 'csrf_token_')) {
                        $sk = substr($sk, 11, 1000);

                        if ($k == $sv and $sk == $v) {
                            unset($data[$k]);
                            return true;
                        } elseif ($k == $sk and $sv == $v) {
                            unset($data[$k]);
                            return true;
                        }
                    }
                    if ($k == 'token') {


                        if ($sv === $v) {
                            unset($data[$k]);
                            return true;
                        }
                    }
                }
            }
        }
    }

    public function hash_pass($pass)
    {

        // Currently only md5 is supported for portability
        // Will improve this soon!

        //$hash = password_hash($pass, PASSWORD_BCRYPT);
        //
        $hash = md5($pass);
        if ($hash == false) {
            $hash = $this->app->database_manager->escape_string($hash);
            return $pass;
        }
        return $hash;

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
     * @param  $params ['id'] = $user_id; // REQUIRED , you must set the user id.
     * For security reasons, to make new user please use user_register() function that requires captcha
     * or write your own save_user wrapper function that sets  mw_var('force_save_user',true);
     * and pass its params to save_user();
     *
     *
     * @param  $params ['is_active'] = 'y'; //default is 'n'
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
    public function save($params)
    {

        $force = mw_var('force_save_user');
        $no_hash = mw_var('save_user_no_pass_hash');
        if ($force == false) {
            if ($this->force_save) {
                $force = $this->force_save;
            }
        }
        if (isset($params['id'])) {
            //error('COMLETE ME!!!! ');

            $adm = $this->is_admin();
            if ($adm == false) {
                if ($force == false) {


                    $is_logged = user_id();
                    if ($is_logged == false or $is_logged == 0) {
                        return array('error' => 'You must be logged to save user');
                    } elseif (intval($is_logged) == intval($params['id']) and intval($params['id']) != 0) {

                    } else {
                        return array('error' => 'You must be logged to as admin save this user');

                    }

                    // $this->app->error('Error: not logged in as admin.' . __FILE__ . __LINE__);

                } else {
                    mw_var('force_save_user', false);
                }
            }
        } else {
            if (defined('MW_API_CALL') and defined('mw_is_installed()') and mw_is_installed() == true) {


                if ($force == false) {
                    $params['id'] = $this->id();
                    if (intval($params['id']) == 0) {
                        return array('error' => 'You must be logged save your settings');
                    }
                    //  $this->app->error('COMLETE ME!!!! ');
                } else {
                    mw_var('force_save_user', false);
                }
            }
        }

        $data_to_save = $params;

        if (isset($data_to_save['password'])) {
            if ($no_hash == false) {
                $data_to_save['password'] = $this->hash_pass($data_to_save['password']);
            } else {
                mw_var('save_user_no_pass_hash', false);
            }
        }


        if (isset($data_to_save['id']) and isset($data_to_save['email']) and $data_to_save['email'] != false) {
            $old_user_data = $this->get_by_id($data_to_save['id']);
            if (isset($old_user_data['email']) and $old_user_data['email'] != false) {
                if ($data_to_save['email'] != $old_user_data['email']) {
                    if (isset($old_user_data['password_reset_hash']) and $old_user_data['password_reset_hash'] != false) {
                        $hash_cache_id = md5(serialize($old_user_data)) . uniqid() . rand();
                        $data_to_save['password_reset_hash'] = $hash_cache_id;
                    }
                }
            }
        }


        $table = $this->tables['users'];
        $save = $this->app->database_manager->save($table, $data_to_save);
        $id = $save;
        $this->app->cache_manager->delete('users' . DIRECTORY_SEPARATOR . 'global');
        $this->app->cache_manager->delete('users' . DIRECTORY_SEPARATOR . '0');
        $this->app->cache_manager->delete('users' . DIRECTORY_SEPARATOR . $id);
        return $id;
    }

    /**
     * Allows you to login a user into the system
     *
     * It also sets user session when the user is logged. <br />
     * On 5 unsuccessful logins, blocks the ip for few minutes <br />
     *
     *
     * @param array|string $params You can pass parameter as string or as array.
     * @param mixed|string $params ['email'] optional If you set  it will use this email for login
     * @param mixed|string $params ['password'] optional Use password for login, it gets trough $this->hash_pass() function
     * @param mixed|string $params ['password_hashed'] optional Use hashed password for login, it does NOT go trough $this->hash_pass() function
     *
     *
     * @example
     * <code>
     * //login with username
     * $this->login('username=test&password=pass')
     * </code>
     * @example
     * <code>
     * //login with email
     * $this->login('email=my@email.com&password=pass')
     * </code>
     * @example
     * <code>
     * //login hashed password
     * $this->login('email=my@email.com&password_hashed=c4ca4238a0b923820dcc509a6f75849b')
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
     * event_bind('before_user_login', 'custom_login_function'); //executed before making login query
     * event_bind('on_user_login', 'custom_after_login_function'); //executed after successful login
     * </code>
     * @package Users
     * @category Users
     * @uses $this->hash_pass()
     * @uses parse_str()
     * @uses $this->get_all()
     * @uses $this->session_set()
     * @uses $this->app->log_manager->get()
     * @uses $this->app->log_manager->save()
     * @uses $this->login_set_failed_attempt()
     * @uses $this->update_last_login_time()
     * @uses $this->app->event_manager->trigger()
     * @function $this->login()
     * @see _table() For the database table fields
     */
    public function login($params)
    {
        $params2 = array();
        $user_session = array();
        $ok = Auth::attempt(['username' => $params['username'], 'password' => $params['password']]);

        if ($ok) {
            $user = Auth::id();
            if ($user) {

                $this->make_logged($user);
            }

        }


        if ($ok && isset($params['redirect_to'])) {


            return Redirect::to($params['redirect_to']);

        } else if ($ok) {
            return $user_session['success'] = _e("You are logged in!", true);

        } else {
            $this->login_set_failed_attempt();
            return array('error' => 'Please enter right username and password!');
        }


        //DIE
        // @todo remove below


        $override = $this->app->event_manager->trigger('before_user_login', $params);
        $redirect_after = isset($params['redirect']) ? $params['redirect'] : false;
        $overiden = false;
        if (is_array($override)) {
            foreach ($override as $resp) {
                if (isset($resp['error']) or isset($resp['success'])) {
                    $overiden = true;
                }
            }
        }

        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
        }
        if ($overiden == true and $redirect_after != false) {
            $this->app->url->redirect($redirect_after);
            exit();
        } elseif ($overiden == true) {
            return $resp;
        }

        //$is_logged =  $this->session_get('user_session');
        // if(is_array($is_logged) and isset($is_logged['']))


        if (isset($params) and !empty($params)) {

            $user = isset($params['username']) ? $params['username'] : false;
            $pass = isset($params['password']) ? $params['password'] : false;
            $email = isset($params['email']) ? $params['email'] : false;
            $pass2 = isset($params['password_hashed']) ? $params['password_hashed'] : false;
            $redirect_after = isset($params['redirect']) ? $params['redirect'] : false;

            $pass = $this->hash_pass($pass);
            if ($pass2 != false and $pass2 != NULL and trim($pass2) != '') {
                $pass = $pass2;
            }


            if (trim($user) == '' and trim($email) == '' and trim($pass) == '') {
                return array('error' => 'Please enter username and password!');

            }
            $url = $this->app->url->current(1);

            $check = $this->app->log_manager->get("is_system=y&count=1&created_on=[mt]1 min ago&updated_on=[lt]1 min&rel=login_failed&user_ip=" . MW_USER_IP);

            if ($check == 5) {

                $url_href = "<a href='$url' target='_blank'>$url</a>";
                $this->app->log_manager->save("title=User IP " . MW_USER_IP . " is blocked for 1 minute for 5 failed logins.&content=Last login url was " . $url_href . "&is_system=n&rel=login_failed&user_ip=" . MW_USER_IP);
            }
            if ($check > 5) {
                $check = $check - 1;
                return array('error' => 'There are ' . $check . ' failed login attempts from your IP in the last minute. Try again in 1 minute!');
            }
            $check2 = $this->app->log_manager->get("is_system=y&count=1&created_on=[mt]10 min ago&updated_on=[lt]10 min&&rel=login_failed&user_ip=" . MW_USER_IP);
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

            if (trim($user != '') and trim($pass != '') and isset($data1) and is_array($data1)) {
                $data = $this->get_all($data1);
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

                    $data = $this->get_all($data);
                    // print_r(mw()->orm->getLastQuery());
                    if (isset($data[0])) {

                        $data = $data[0];
                    } else {

                        $this->login_set_failed_attempt();
                        return array('error' => 'Please enter right username and password!');

                    }
                } else {
                    //	return array('error' => 'Please enter username or email!');

                }

                // return false;
            }

            if (!is_array($data)) {
                if (trim($user) != '') {
                    $data = array();
                    $data['email'] = $user;
                    $data['password'] = $pass;
                    $data['is_active'] = 'y';


                    $data = $this->get_all($data);

                    if (isset($data[0])) {
                        $data = $data[0];
                    }
                }
            }
            if (!is_array($data)) {
                $this->login_set_failed_attempt();

                $user_session = array();
                $user_session['is_logged'] = 'no';
                $this->session_set('user_session', $user_session);

                $aj = $this->app->url->is_ajax();

                if ($aj == false and $api_key == false) {
                    if ($redirect_after != false) {
                        $this->app->url->redirect($redirect_after);
                        exit();
                    }
                }

                return array('error' => 'Please enter the right username and password!');

            } else {

                if (!isset($data['id'])) {
                    return array('error' => 'Please enter the right username and password!');

                }

                $user_session = array();
                $user_session['is_logged'] = 'yes';
                $user_session['user_id'] = $data['id'];

                if (!defined('USER_ID')) {
                    define("USER_ID", $data['id']);


                }
                $this->make_logged($data['id']);
                if (isset($data["is_admin"]) and $data["is_admin"] == 'y') {
                    if (isset($params['where_to']) and $params['where_to'] == 'live_edit') {
                        $this->app->event_manager->trigger('user_login_admin');
                        $p = mw()->content_manager->get_page();
                        if (!empty($p)) {
                            $link = $this->app->content_manager->link($p['id']);
                            $link = $link . '/editmode:y';
                            $this->app->url->redirect($link);
                            exit();
                        }
                    }
                }

                $aj = $this->app->url->is_ajax();

                if ($aj == false and $api_key == false) {
                    if (isset($_SERVER["HTTP_REFERER"]) and $redirect_after == false) {
                        //	d($user_session);
                        //exit();
                        if ($redirect_after != false) {
                            $this->app->url->redirect($redirect_after);
                            exit();
                        } else {
                            $this->app->url->redirect($_SERVER["HTTP_REFERER"]);
                            exit();
                        }


                        exit();
                    } elseif ($redirect_after != false) {
                        $this->app->url->redirect($redirect_after);
                        exit();
                    } else {
                        $user_session['success'] = _e("You are logged in!", true);
                        if ($redirect_after != false) {
                            $user_session['redirect'] = $redirect_after;
                        }


                        return $user_session;
                    }
                } else if ($aj == true) {
                    $user_session['success'] = _e("You are logged in!", true);
                }

                return $user_session;
            }


            if ($redirect_after != false) {
                $this->app->url->redirect($redirect_after);
                exit();
            }
        }


        return false;
    }

    public function login_set_failed_attempt()
    {

        $this->app->log_manager->save("title=Failed login&is_system=y&rel=login_failed&user_ip=" . MW_USER_IP);

    }

    public function get($params = false)
    {
        $id = $params;
        if ($id == false) {
            $id = user_id();
        }


        if ($id == 0) {
            return false;
        }

        $res = $this->get_by_id($id);

        if (empty($res)) {

            $res = $this->get_by_username($id);
        }

        return $res;
    }

    public function get_by_username($username)
    {
        $data = array();
        $data['username'] = $username;
        $data['limit'] = 1;
        $data = $this->get_all($data);
        if (isset($data[0])) {
            $data = $data[0];
        }
        return $data;
    }

    function delete($data)
    {
        $adm = $this->is_admin();
        if (defined('MW_API_CALL') and $adm == false) {
            return ('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }

        if (!is_array($data)) {
            $new_data = array();
            $new_data['id'] = intval($data);
            $data = $new_data;
        }

        if (isset($data['id'])) {
            $c_id = intval($data['id']);
            $this->app->database_manager->delete_by_id('users', $c_id);
            return $c_id;

        }
        return $data;
    }

    public function reset_password_from_link($params)
    {
        if (!isset($params['captcha'])) {
            return array('error' => 'Please enter the captcha answer!');
        } else {
            $cap = $this->session_get('captcha');
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
        $data1['password_reset_hash'] = $this->app->database_manager->escape_string($params['password_reset_hash']);
        $table = $this->tables['users'];

        $check = $this->get_all("single=true&password_reset_hash=[not_null]&password_reset_hash=" . $data1['password_reset_hash'] . '&id=' . $data1['id']);
        if (!is_array($check)) {
            return array('error' => 'Invalid data or expired link!');
        } else {
            $data1['password'] = $params['pass1'];
            $data1['password_reset_hash'] = '';


            $data1['password'] = $this->hash_pass($data1['password']);


        }


        mw_var('FORCE_SAVE', $table);

        $save = $this->app->database_manager->save($table, $data1);

        $notif = array();
        $notif['module'] = "users";
        $notif['rel'] = 'users';
        $notif['rel_id'] = $data1['id'];
        $notif['title'] = "The user have successfully changed password. (User id: {$data1['id']})";

        $this->app->log_manager->save($notif);
        $this->session_end();

        return array('success' => 'Your password have been changed!');

    }

    public function session_end()
    {


        $_SESSION = array();

        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        //session_write_close();
        unset($_SESSION);

    }

    public function send_forgot_password($params)
    {

        if (!isset($params['captcha'])) {
            return array('error' => 'Please enter the captcha answer!');
        } else {
            $cap = $this->session_get('captcha');
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
                    $data = $this->get_all($data1);
                }

                if (isset($data[0])) {
                    $data_res = $data[0];

                } else {
                    $data1 = array();
                    $data1['email'] = $user;
                    //$data1['oauth_uid'] = '[null]';
                    //$data1['oauth_provider'] = '[null]';
                    $data = $this->get_all($data1);
                    if (isset($data[0])) {
                        $data_res = $data[0];

                    }

                }
                if (!is_array($data_res)) {
                    return array('error' => 'Enter right username or email!');

                } else {
                    $to = $data_res['email'];
                    if (isset($to) and (filter_var($to, FILTER_VALIDATE_EMAIL))) {

                        $subject = "Password reset!";
                        $content = "Hello, {$data_res['username']} <br> ";
                        $content .= "You have requested a password reset link from IP address: " . MW_USER_IP . "<br><br> ";

                        //$content .= "on " . $this->app->url->current(1) . "<br><br> ";

                        $security = array();
                        $security['ip'] = MW_USER_IP;
                        $security['hash'] = $this->app->format->array_to_base64($data_res);
                        $function_cache_id = md5(serialize($security)) . uniqid() . rand();
                        //$this->app->cache_manager->save($security, $function_cache_id, $cache_group = 'password_reset');
                        if (isset($data_res['id'])) {
                            $data_to_save = array();
                            $data_to_save['id'] = $data_res['id'];
                            $data_to_save['password_reset_hash'] = $function_cache_id;

                            $table = $this->tables['users'];
                            mw_var('FORCE_SAVE', $table);

                            $save = $this->app->database_manager->save($table, $data_to_save);
                        }
                        $pass_reset_link = $this->app->url->current(1) . '?reset_password_link=' . $function_cache_id;

                        $notif = array();
                        $notif['module'] = "users";
                        $notif['rel'] = 'users';
                        $notif['rel_id'] = $data_to_save['id'];
                        $notif['title'] = "Password reset link sent";
                        $content_notif = "User with id: {$data_to_save['id']} and email: {$to}  has requested a password reset link";
                        $notif['description'] = $content_notif;

                        $this->app->log_manager->save($notif);
                        $content .= "Click here to reset your password  <a href='{$pass_reset_link}'>" . $pass_reset_link . "</a><br><br> ";

                        //d($data_res);
                        \Microweber\email\Sender::send($to, $subject, $content, true, $no_cache = true);

                        return array('success' => 'Your password reset link has been sent to ' . $to);
                    } else {
                        return array('error' => 'Error: the user doesn\'t have a valid email address!');
                    }

                }

            }

        }

    }

    public function  social_login($params)
    {


        set_exception_handler($this->social_login_exception_handler());
        $params2 = array();

        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
        }

        $return_after_login = false;
        if (isset($_SERVER["HTTP_REFERER"]) and stristr($_SERVER["HTTP_REFERER"], $this->app->url->site())) {
            $return_after_login = $_SERVER["HTTP_REFERER"];
            $this->session_set('user_after_login', $return_after_login);

        }

        $provider = false;
        if (isset($_REQUEST['provider'])) {
            $provider = $_REQUEST['provider'];
            $provider = trim(strip_tags($provider));
        }

        if ($provider != false and isset($params) and !empty($params)) {

            $api = new \Microweber\Auth\Social();

            try {

                $authenticate = $api->authenticate($provider);


                if (is_array($authenticate) and isset($authenticate['identifier'])) {

                    $data = array();
                    $data['oauth_provider'] = $provider;
                    $data['oauth_uid'] = $authenticate['identifier'];

                    $data_ex = $this->get_all($data);
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

                        $table = $this->tables['users'];
                        mw_var('FORCE_SAVE', $table);

                        $save = $this->app->database_manager->save($table, $data_to_save);
                        $this->app->cache_manager->delete('users/global');
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
                            $this->app->notifications->save($notif);

                            $this->app->log_manager->save($notif);

                        }

                    }

                    $data_ex = $this->get_all($data);

                    if (isset($data_ex[0])) {
                        $data = $data_ex[0];
                        $user_session['is_logged'] = 'yes';
                        $user_session['user_id'] = $data['id'];
                        $this->app->event_manager->trigger('after_user_register', $data);
                        if (!defined('USER_ID')) {
                            define("USER_ID", $data['id']);
                        }
                        $this->make_logged($data['id']);

                        if ($return_after_login != false) {
                            $this->app->url->redirect($return_after_login);
                            exit();
                        } else {
                            if ($return_after_login != false) {
                                $this->app->url->redirect($return_after_login);
                                exit();
                            } else {

                                $go_sess = $this->session_get('user_after_login');
                                if ($go_sess != false) {
                                    $this->app->url->redirect($go_sess);
                                } else {
                                    $this->app->url->redirect(site_url());
                                }


                                exit();
                            }
                        }

                    }

                }


            } catch (Exception $e) {


                die("<b>got an error!</b> " . $e->getMessage());
            }

        }
    }

    function social_login_exception_handler($exception = false)
    {


        if ($this->app->url->is_ajax()) {
            if ($exception == false) {
                return;
            }


            return array('error' => $exception->getMessage());
        }
        $after_log = $this->session_get('user_after_login');
        if ($after_log != false) {
            $this->app->url->redirect($after_log);
        } else {
            $this->app->url->redirect(site_url());
        }
    }

    public function make_logged($user_id)
    {


        if (is_array($user_id)) {
            if (isset($user_id['id'])) {
                $user_id = $user_id['id'];
            }
        }

        if (intval($user_id) > 0) {
            $data = $this->get_by_id($user_id);
            if ($data == false) {
                return false;
            } else {
                if (is_array($data)) {
                    $user_session = array();
                    $user_session['is_logged'] = 'yes';
                    $user_session['user_id'] = $data['id'];

                    if (!defined('USER_ID')) {
                        define("USER_ID", $data['id']);


                    }

                    $this->app->event_manager->trigger('user_login', $data);
                    $this->session_set('user_session', $user_session);
                    $user_session = $this->session_get('user_session');

                    $this->update_last_login_time();
                    $user_session['success'] = _e("You are logged in!", true);
                    return $user_session;
                }
            }
        }

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
    public function get_by_id($id)
    {
        $id = intval($id);
        if ($id == 0) {
            return false;
        }

        $data = array();
        $data['id'] = $id;
        $data['limit'] = 1;
        $data['single'] = 1;


        $data = $this->get_all($data);

        return $data;
    }

    public function update_last_login_time()
    {

        $uid = user_id();
        if (intval($uid) > 0) {

            $data_to_save = array();
            $data_to_save['id'] = $uid;
            $data_to_save['last_login'] = date("Y-m-d H:i:s");
            $data_to_save['last_login_ip'] = MW_USER_IP;

            $table = $this->tables['users'];
            mw_var("FORCE_SAVE", $this->tables['users']);
            $save = $this->app->database_manager->save($table, $data_to_save);

            $this->app->log_manager->delete("is_system=y&rel=login_failed&user_ip=" . MW_USER_IP);

        }

    }

    public function social_login_process($params = false)
    {


        if (isset($_SERVER["HTTP_REFERER"]) and stristr($_SERVER["HTTP_REFERER"], $this->app->url->site())) {
            $return_after_login = $_SERVER["HTTP_REFERER"];
            $this->session_set('user_after_login', $return_after_login);

        }


        set_exception_handler($this->social_login_exception_handler);


        try {
            $api = new \Microweber\Auth\Social();


            $api->process();


        } catch (Exception $e) {
            echo "Ooophs, we got an error: " . $e->getMessage();
        }


    }

    public function count()
    {
        $options = array();
        $options['get_count'] = true;
        // $options ['debug'] = true;
        $options['count'] = true;
        // $options ['no_cache'] = true;
        $options['cache_group'] = 'users/global/';

        $data = $this->get_all($options);

        return $data;
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
     * @usage $this->get_all('email=my_email');
     *
     *
     * @return array of users;
     */
    public function get_all($params)
    {
        $params = parse_params($params);

        $table = $this->tables['users'];

        $data = $this->app->format->clean_html($params);
        $orig_data = $data;

        if (isset($data['ids']) and is_array($data['ids'])) {
            if (!empty($data['ids'])) {
                $ids = $data['ids'];
            }
        }
        if (!isset($params['search_in_fields'])) {
            $data['search_in_fields'] = array('id', 'first_name', 'last_name', 'username', 'email');
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

        //$data ['no_cache'] = 1;

        if (isset($data['username']) and $data['username'] == null) {
            unset($data['username']);
        }
        if (isset($data['username']) and $data['username'] == '') {
            //return false;
        }

        // $function_cache_id = __FUNCTION__ . crc32($function_cache_id);
        $data['table'] = $table;
        //  $data ['cache_group'] = $cache_group;


        $get = $this->app->database_manager->get($data);

        //$get = $this->app->database_manager->get_long($table, $criteria = $data, $cache_group);
        // $get = $this->app->database_manager->get_long($table, $criteria = $data, $cache_group);
        // var_dump($get, $function_cache_id, $cache_group);
        //  $this->app->cache_manager->save($get, $function_cache_id, $cache_group);

        return $get;
    }

    function register_url()
    {


        $template_dir = $this->app->template->dir();
        $file = $template_dir . 'register.php';
        $default_url = false;
        if (is_file($file)) {
            $default_url = 'register';
        } else {
            $default_url = 'users/register';
        }

        $checkout_url = $this->app->option_manager->get('register_url', 'users');
        if ($checkout_url != false and trim($checkout_url) != '') {
            $default_url = $checkout_url;
        }

        $checkout_url_sess = $this->session_get('register_url');

        if ($checkout_url_sess == false) {
            return $this->app->url->site($default_url);
        } else {
            return $this->app->url->site($checkout_url_sess);
        }

    }


    function logout_url()
    {


        return api_url('logout');

    }

    function login_url()
    {


        $template_dir = $this->app->template->dir();
        $file = $template_dir . 'login.php';
        $default_url = false;
        if (is_file($file)) {
            $default_url = 'login';
        } else {
            $default_url = 'users/login';
        }

        $checkout_url = $this->app->option_manager->get('login_url', 'users');
        if ($checkout_url != false and trim($checkout_url) != '') {
            $default_url = $checkout_url;
        }

        $checkout_url_sess = $this->session_get('login_url');

        if ($checkout_url_sess == false) {
            return $this->app->url->site($default_url);
        } else {
            return $this->app->url->site($checkout_url_sess);
        }

    }

    function forgot_password_url()
    {


        $template_dir = $this->app->template->dir();
        $file = $template_dir . 'forgot_password.php';
        $default_url = false;
        if (is_file($file)) {
            $default_url = 'forgot_password';
        } else {
            $default_url = 'users/forgot_password';
        }

        $checkout_url = $this->app->option_manager->get('forgot_password_url', 'users');
        if ($checkout_url != false and trim($checkout_url) != '') {
            $default_url = $checkout_url;
        }

        $checkout_url_sess = $this->session_get('forgot_password_url');

        if ($checkout_url_sess == false) {
            return $this->app->url->site($default_url);
        } else {
            return $this->app->url->site($checkout_url_sess);
        }

    }

    public function session_set($name, $val)
    {


        return Session::put($name, $val);


    }

    function csrf_form($unique_form_name = false)
    {
        if ($unique_form_name == false) {
            $unique_form_name = uniqid();
        }

        $token = $this->csrf_token($unique_form_name);

        $input = '<input type="hidden" name="' . $token . '" value="' . md5($unique_form_name) . '">';
        // $input = '<input type="text" name="' . $token . '" value="' . md5($unique_form_name) . '">';
        //  $input = '<input type="hidden" name="' . $token . '" value="' . md5($unique_form_name) . '">';

        return $input;
    }

    public function session_get($name)
    {


        $value = Session::get($name);
        return $value;

    }

    function csrf_token($unique_form_name = false)
    {


        if (function_exists("hash_algos") and in_array("sha512", hash_algos())) {
            $token = hash("sha512", mt_rand(0, mt_getrandmax()));
        } else {
            $token = ' ';
            for ($i = 0; $i < 128; ++$i) {
                $r = mt_rand(0, 35);
                if ($r < 26) {
                    $c = chr(ord('a') + $r);
                } else {
                    $c = chr(ord('0') + $r - 26);
                }
                $token .= $c;
            }
        }

        $this->session_set('csrf_token_' . md5($unique_form_name), $token);

        return $token;
    }

    public function session_del($name)
    {
        if (isset($_SESSION[$name])) {
            unset($_SESSION[$name]);
        }
    }


}