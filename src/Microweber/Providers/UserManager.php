<?php

/*
 * This file is part of Microweber
 *
 * (c) Microweber LTD
 *
 * For full license information see
 * http://microweber.com/license/
 *
 */

namespace Microweber\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\User as DefaultUserProvider;
use Illuminate\Support\Facades\Config;
use Laravel\Socialite\SocialiteManager;
use Illuminate\Support\Facades\Session;
use Auth;
use User;

if (!defined('MW_USER_IP')){
    if (isset($_SERVER["REMOTE_ADDR"])){
        define("MW_USER_IP", $_SERVER["REMOTE_ADDR"]);
    } else {
        define("MW_USER_IP", '127.0.0.1');

    }
}


class UserManager {
    public $tables = array();


    function __construct($app = null) {
        $this->set_table_names();


        if (is_object($app)){
            $this->app = $app;
        } else {
            $this->app = mw();
        }


        $this->socialite = new SocialiteManager($this->app);

    }


    public function set_table_names($tables = false) {

        if (!is_array($tables)){
            $tables = array();
        }
        if (!isset($tables['users'])){
            $tables['users'] = 'users';
        }
        if (!isset($tables['log'])){
            $tables['log'] = 'log';
        }
        $this->tables['users'] = $tables['users'];
        $this->tables['log'] = $tables['log'];


    }

    public function is_admin() {

        if (!mw_is_installed()){
            return false;
        }

        if (Auth::check()){

            return Auth::user()->is_admin;
        }
    }

    public function id() {

        if (Auth::check()){
            return Auth::user()->id;
        }

        return false;


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
     * @package  Users
     * @category Users
     * @uses     $this->hash_pass()
     * @uses     parse_str()
     * @uses     $this->get_all()
     * @uses     $this->session_set()
     * @uses     $this->app->log_manager->get()
     * @uses     $this->app->log_manager->save()
     * @uses     $this->login_set_failed_attempt()
     * @uses     $this->update_last_login_time()
     * @uses     $this->app->event_manager->trigger()
     * @function $this->login()
     * @see      _table() For the database table fields
     */


    public function login($params) {
        if (is_string($params)){
            $params = parse_params($params);
        }
        $check = $this->app->log_manager->get("no_cache=1&count=1&updated_at=[mt]1 min ago&is_system=y&rel_type=login_failed&user_ip=" . MW_USER_IP);
        $url = $this->app->url->current(1);
        if ($check==5){
            $url_href = "<a href='$url' target='_blank'>$url</a>";
            $this->app->log_manager->save("title=User IP " . MW_USER_IP . " is blocked for 1 minute for 5 failed logins.&content=Last login url was " . $url_href . "&is_system=n&rel_type=login_failed&user_ip=" . MW_USER_IP);
        }
        if ($check > 5){
            $check = $check - 1;

            return array('error' => 'There are ' . $check . ' failed login attempts from your IP in the last minute. Try again in 1 minute!');
        }
        $check2 = $this->app->log_manager->get("no_cache=1&is_system=y&count=1&created_at=[mt]10 min ago&updated_at=[lt]10 min&rel_type=login_failed&user_ip=" . MW_USER_IP);
        if ($check2 > 25){
            return array('error' => 'There are ' . $check2 . ' failed login attempts from your IP in the last 10 minutes. You are blocked for 10 minutes!');
        }

        $override = $this->app->event_manager->trigger('mw.user.before_login', $params);
        $redirect_after = isset($params['redirect']) ? $params['redirect'] : false;
        $overiden = false;
        $return_resp = false;
        if (is_array($override)){

            foreach ($override as $resp) {
                if (isset($resp['error']) or isset($resp['success'])){
                    $return_resp = $resp;
                    $overiden = true;
                }
            }
        }
        if ($overiden==true and $redirect_after!=false){
            return $this->app->url_manager->redirect($redirect_after);;
        } elseif ($overiden==true) {
            return $return_resp;
        }
        $old_sid = Session::getId();
        if (isset($params['username'])){
            $ok = Auth::attempt([
                'username' => $params['username'],
                'password' => $params['password']
            ]);
            if (!$ok){
                if ($params['username']!=false and filter_var($params['username'], FILTER_VALIDATE_EMAIL)){
                    $ok = Auth::attempt([
                        'email'    => $params['username'],
                        'password' => $params['password']
                    ]);
                }
            }
        } elseif (isset($params['email'])) {
            $ok = Auth::attempt([
                'email'    => $params['email'],
                'password' => $params['password']
            ]);
        }

        if (!isset($ok)){
            return;
        }
        if ($ok){
            $user = Auth::login(Auth::user());
            $user_data = $this->get_by_id(Auth::user()->id);
            $user_data['old_sid'] = $old_sid;
            $this->app->event_manager->trigger('mw.user.login', $user_data);
            if ($ok && $redirect_after){
                return $this->app->url_manager->redirect($redirect_after);;
            } else if ($ok){
                return ['success' => "You are logged in!"];
            }

        } else {
            $this->login_set_failed_attempt();
        }

        return array('error' => 'Please enter right username and password!');
    }


    public function logout($params = false) {
        Session::flush();
        $aj = $this->app->url_manager->is_ajax();
        $redirect_after = isset($_GET['redirect']) ? $_GET['redirect'] : false;
        if (isset($_COOKIE['editmode'])){
            setcookie('editmode');
        }

        $this->app->event_manager->trigger('mw.user.logout', $params);
        if ($redirect_after==false and $aj==false){
            if (isset($_SERVER["HTTP_REFERER"])){
                return $this->app->url_manager->redirect($_SERVER["HTTP_REFERER"]);
            }
        }

        if ($redirect_after==true){
            $redir = site_url($redirect_after);

            return $this->app->url_manager->redirect($redir);

        }

        return true;
    }

    public function is_logged() {

        if (Auth::check()){
            return true;
        } else {
            return false;
        }

    }

    public function login_as($params) {

        $is_a = $this->is_admin();
        if ($is_a==true){
            return true;
        }


    }


    public function has_access($function_name) {
        // will be updated with roles and perms
        $is_a = $this->is_admin();
        if ($is_a==true){
            return true;
        } else {
            return false;
        }
    }

    public function admin_access() {
        if ($this->is_admin()==false){
            exit('You must be logged as admin');
        }
    }


    public function attributes($user_id = false) {

        if (!$user_id){
            $user_id = $this->id();
        }
        if (!$user_id){
            return;
        }

        $data = array();
        $data['rel_type'] = 'users';
        $data['rel_id'] = intval($user_id);
        $res = array();
        $get = $this->app->content_manager->get_attributes($data);
        if (!empty($get)){
            foreach ($get as $item) {
                if (isset($item['attribute_name']) and isset($item['attribute_value'])){
                    $res[ $item['attribute_name'] ] = $item['attribute_value'];
                }
            }
        }
        if (!empty($res)){
            return $res;
        }

        return $get;
    }

    public function data_fields($user_id = false) {

        if (!$user_id){
            $user_id = $this->id();
        }
        if (!$user_id){
            return;
        }

        $data = array();
        $data['rel_type'] = 'users';
        $data['rel_id'] = intval($user_id);
        $res = array();
        $get = $this->app->content_manager->get_data($data);
        if (!empty($get)){
            foreach ($get as $item) {
                if (isset($item['field_name']) and isset($item['field_value'])){
                    $res[ $item['field_name'] ] = $item['field_value'];
                }
            }
        }
        if (!empty($res)){
            return $res;
        }

        return $get;
    }


    public function picture($user_id = false) {
        $name = $this->get_by_id($user_id);
        if (isset($name['thumbnail']) and $name['thumbnail']!=''){
            return $name['thumbnail'];
        }
    }

    /**
     * @function user_name
     * gets the user's FULL name
     *
     * @param        $user_id  the id of the user. If false it will use the curent user (you)
     * @param string $mode     full|first|last|username
     *                         'full' //prints full name (first +last)
     *                         'first' //prints first name
     *                         'last' //prints last name
     *                         'username' //prints username
     *
     * @return string
     */
    public function name($user_id = false, $mode = 'full') {
        if ($mode!='username'){
            if ($user_id==user_id()){
                // return 'You';
            }
        }
        if ($user_id==false){
            $user_id = user_id();
        }

        $name = $this->nice_name($user_id, $mode);

        return $name;
    }

    /**
     * Function to get user printable name by given ID
     *
     * @param        $id
     * @param string $mode
     *
     * @return string
     * @example
     * <code>
     * //get user name for user with id 10
     * $this->nice_name(10, 'full');
     * </code>
     * @uses $this->get_by_id()
     */
    public function nice_name($id, $mode = 'full') {
        $user = $this->get_by_id($id);
        $user_data = $user;
        if (empty($user)){
            return false;
        }

        switch ($mode) {
            case 'first' :
            case 'fist' :
                // because of a common typo :)
                $user_data['first_name'] ? $name = $user_data['first_name'] : $name = $user_data['username'];
                $name = ucwords($name);


                if (trim($name)=='' and $user_data['email']!=''){
                    $n = explode('@', $user_data['email']);
                    $name = $n[0];
                }
                // return $name;
                break;

            case 'last' :
                $user_data['last_name'] ? $name = $user_data['last_name'] : $name = $user_data['last_name'];
                $name = ucwords($name);
                break;

            case 'username' :
                $name = $user_data['username'];
                break;

            case 'full' :
            default :

                $name = '';
                if (isset($user_data['first_name'])){
                    if ($user_data['first_name']){
                        $name = $user_data['first_name'];
                    }
                }

                if (isset($user_data['last_name'])){
                    if ($user_data['last_name']){
                        $name .= ' ' . $user_data['last_name'];

                    }
                }
                $name = ucwords($name);

                if (trim($name)=='' and $user_data['email']!=''){
                    $name = $user_data['email'];
                    $name_from_email = explode('@', $user_data['email']);
                    $name = $name_from_email[0];
                }

                if (trim($name)=='' and $user_data['username']!=''){
                    $name = $user_data['username'];
                    $name = ucwords($name);
                }

                break;
        }


        if (!isset($name) or $name==false or $name==null or trim($name)==''){
            if (isset($user_data['username']) and $user_data['username']!=false and trim($user_data['username'])!=''){
                $name = $user_data['username'];
            } else if (isset($user_data['email']) and $user_data['email']!=false and trim($user_data['email'])!=''){
                $name_from_email = explode('@', $user_data['email']);
                $name = $name_from_email[0];
            }
        }

        return $name;

    }

    public function api_login($api_key = false) {

        if ($api_key==false and isset($_REQUEST['api_key']) and user_id()==0){
            $api_key = $_REQUEST['api_key'];
        }

        if ($api_key==false){
            return false;
        } else {
            if (trim($api_key)==''){
                return false;
            } else {
                if (user_id() > 0){
                    return true;
                } else {
                    $data = array();
                    $data['api_key'] = $api_key;
                    $data['is_active'] = 1;
                    $data['limit'] = 1;

                    $data = $this->get_all($data);

                    if ($data!=false){
                        if (isset($data[0])){
                            $data = $data[0];

                            if (isset($data['api_key']) and $data['api_key']==$api_key){
                                return $this->make_logged($data['id']);
                            }

                        }

                    }


                }

            }
        }

    }

    public function register($params) {

        if (defined("MW_API_CALL")){
            //	if (isset($params['token'])){
            if ($this->is_admin()==false){
                $validate_token = $this->csrf_validate($params);
                if ($validate_token==false){
                    return array('error' => 'Invalid token!');
                }
            }
            //}
        }
        $user = isset($params['username']) ? $params['username'] : false;
        $pass = isset($params['password']) ? $params['password'] : false;
        $email = isset($params['email']) ? $params['email'] : false;
        $first_name = isset($params['first_name']) ? $params['first_name'] : false;
        $last_name = isset($params['last_name']) ? $params['last_name'] : false;
        $confirm_password = isset($params['confirm_password']) ? $params['confirm_password'] : false;
        $pass2 = $pass;


        $no_captcha = get_option('captcha_disabled', 'users')=='y';

        if (!$no_captcha){
            if (!isset($params['captcha'])){
                return array('error' => 'Please enter the captcha answer!');
            } else {
                $validate_captcha = $this->app->captcha->validate($params['captcha']);
                if (!$validate_captcha){
                    return array('error' => 'Invalid captcha answer!', 'captcha_error' => true);
                }
            }
        }
        $override = $this->app->event_manager->trigger('before_user_register', $params);

        if (is_array($override)){
            foreach ($override as $resp) {
                if (isset($resp['error']) or isset($resp['success'])){
                    return $resp;
                }
            }
        }


        if (defined("MW_API_CALL")){
            if (isset($params['is_admin']) and $this->is_admin()==false){
                unset($params['is_admin']);
            }
        }


        if (isset($params['password']) and ($params['password'])==''){
            return array('error' => 'Please set password!');
        }

        if (isset($params['password']) and ($params['password'])!=''){

            if ($confirm_password!=false){
                if ($params['password']!=$confirm_password){
                    return array('error' => 'Password confirm does not mach password!');
                }
            }

            if ($email!=false){

                $data = array();
                $data['email'] = $email;
                $data['one'] = true;
                $data['no_cache'] = true;
                $user_data = $this->get_all($data);

                if (empty($user_data)){
                    $data = array();
                    $data['username'] = $email;
                    $data['one'] = true;
                    $data['no_cache'] = true;
                    $user_data = $this->get_all($data);
                }


                if (empty($user_data)){
                    $data = array();
                    $data['username'] = $email;
                    $data['password'] = $pass;
                    $data['is_active'] = 1;
                    $table = $this->tables['users'];

                    $reg = array();
                    $reg['username'] = $user;
                    $reg['email'] = $email;
                    $reg['password'] = $pass2;
                    $reg['is_active'] = 1;
                    if ($first_name!=false){
                        $reg['first_name'] = $first_name;
                    }
                    if ($first_name!=false){
                        $reg['first_name'] = $first_name;
                    }
                    if ($last_name!=false){
                        $reg['last_name'] = $last_name;
                    }

                    $this->force_save = true;
                    $next = $this->save($reg);
                    $this->force_save = false;
                    $this->app->cache_manager->delete('users/global');
                    $this->session_del('captcha');


                    $this->after_register($next);

                    $params = $data;
                    $params['id'] = $next;
                    if (isset($pass2)){
                        $params['password2'] = $pass2;
                    }
                    $this->make_logged($params['id']);


                    return array('success' => 'You have registered successfully');

                } else {

                    $try_login = $this->login($params);
                    if (isset($try_login['success'])){
                        return ($try_login);
                    }

                    return array('error' => 'This user already exists!');
                }
            }
        }


    }


    public function after_register($user_id, $suppress_output = true) {
        if ($suppress_output==true){
            ob_start();
        }
        $data = $this->get_by_id($user_id);
        if (!$data){
            return;
        }

        $notif = array();
        $notif['module'] = "users";
        $notif['rel_type'] = 'users';
        $notif['rel_id'] = $user_id;
        $notif['title'] = "New user registration";
        $notif['description'] = "You have new user registration";
        $notif['content'] = "You have new user registered with the username [" . $data['username'] . '] and id [' . $user_id . ']';
        $this->app->notifications_manager->save($notif);

        $this->app->log_manager->save($notif);
        $this->register_email_send($user_id);

        $this->app->event_manager->trigger('mw.user.after_register', $data);
        if ($suppress_output==true){
            ob_end_clean();
        }

    }


    public function register_email_send($user_id) {

        $data = $this->get_by_id($user_id);
        if (!$data){
            return;
        }
        if (is_array($data)){
            $register_email_enabled = $this->app->option_manager->get('register_email_enabled', 'users');
            if ($register_email_enabled==true){
                $register_email_subject = $this->app->option_manager->get('register_email_subject', 'users');
                $register_email_content = $this->app->option_manager->get('register_email_content', 'users');
                if ($register_email_subject==false or trim($register_email_subject)==''){
                    $register_email_subject = "Thank you for your registration!";
                }
                $to = $data['email'];
                if ($register_email_content!=false and trim($register_email_subject)!=''){
                    if (!empty($data)){
                        foreach ($data as $key => $value) {
                            if (!is_array($value) and is_string($key)){
                                $register_email_content = str_ireplace('{' . $key . '}', $value, $register_email_content);
                            }
                        }
                    }
                    if (isset($to) and (filter_var($to, FILTER_VALIDATE_EMAIL))){
                        $sender = new \Microweber\Utils\MailSender();
                        $sender->send($to, $register_email_subject, $register_email_content);
                        return true;
                    }
                }
            }
        }
    }

    function csrf_validate(&$data) {
        $session_token = Session::token();
        if (is_array($data) and $this->session_id()){
            foreach ($data as $k => $v) {
                if ($k=='token' or $k=='_token'){
                    if ($session_token===$v){
                        unset($data[ $k ]);

                        return true;
                    }
                }
            }
        }
    }

    public function hash_pass($pass) {
        $hash = \Hash::make($pass);

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
     * @param  $params
     * @param  $params ['id'] = $user_id; // REQUIRED , you must set the user id.
     *                 For security reasons, to make new user please use user_register() function that requires captcha
     *                 or write your own save_user wrapper function that sets  mw_var('force_save_user',true);
     *                 and pass its params to save_user();
     *
     *
     * @param  $params ['is_active'] = 1; //default is 'n'
     *
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
    public $force_save = false;

    public function save($params) {
        $force = false;
        if (defined('MW_FORCE_USER_SAVE')){
            $force = MW_FORCE_USER_SAVE;
        } elseif ($this->force_save) {
            $force = $this->force_save;
        } elseif (mw_var('force_save_user')) {
            $force = mw_var('force_save_user');
        }
        if (!$force){
            if (defined("MW_API_CALL") and mw_is_installed()==true){
                if (isset($params['is_admin']) and $this->is_admin()==false and !is_null(User::first())){
                    unset($params['is_admin']);
                }
            }
        }
        if ($force==false){
            if (isset($params['id']) and $params['id']!=0){
                $adm = $this->is_admin();
                if ($adm==false){
                    $is_logged = user_id();
                    if ($is_logged==false or $is_logged==0){
                        return array('error' => 'You must be logged to save user');
                    } elseif (intval($is_logged)==intval($params['id']) and intval($params['id'])!=0) {
                        // the user is editing their own profile
                    } else {
                        return array('error' => 'You must be logged to as admin save this user');
                    }
                }
            } else {
                if (defined('MW_API_CALL') and mw_is_installed()==true){
                    $adm = $this->is_admin();
                    if ($adm==false){
                        $params['id'] = $this->id();
                        $is_logged = user_id();
                        if (intval($params['id'])!=0 and $is_logged!=$params['id']){
                            return array('error' => 'You must be logged save your settings');
                        }
                    } else {
                        if (!isset($params['id'])){
                            $params['id'] = $this->id();
                        }
                    }
                }
            }
        }

        $data_to_save = $params;


        if (isset($data_to_save['id']) and $data_to_save['id']!=0 and isset($data_to_save['email']) and $data_to_save['email']!=false){
            $old_user_data = $this->get_by_id($data_to_save['id']);
            if (isset($old_user_data['email']) and $old_user_data['email']!=false){
                if ($data_to_save['email']!=$old_user_data['email']){
                    if (isset($old_user_data['password_reset_hash']) and $old_user_data['password_reset_hash']!=false){
                        $hash_cache_id = md5(serialize($old_user_data)) . uniqid() . rand();
                        $data_to_save['password_reset_hash'] = $hash_cache_id;
                    }
                }
            }
        }
        if (isset($data_to_save['email']) and isset($data_to_save['id'])){
            $email = trim($data_to_save['email']);
            if (filter_var($email, FILTER_VALIDATE_EMAIL)){
                $check_existing = array();
                $check_existing['email'] = $email;
                $check_existing['single'] = 1;
                $check_existing = $this->get_all($check_existing);


                if (isset($check_existing['id']) and $check_existing['id']!=$data_to_save['id']){
                    return array('error' => 'User with this email already exists! Try different email address!');
                }
            }
        }
        if (isset($params['id']) and intval($params['id'])!=0){
            $user = User::find($params['id']);
        } else {
            $user = new User;
        }
        $id_to_return = false;

        $data_to_save = $this->app->format->clean_xss($data_to_save);
        if ($user->validateAndFill($data_to_save)){
            $save = $user->save();

            if (isset($params['attributes']) or isset($params['data_fields'])){
                $params['extended_save'] = true;
            }

            if (isset($params['extended_save'])){
                if (isset($data_to_save['password'])){
                    unset($data_to_save['password']);
                }
                if (isset($data_to_save['id'])){
                    $data_to_save['table'] = 'users';

                    $this->app->database->extended_save($data_to_save);
                }
            }

            if (isset($params['id']) and intval($params['id'])!=0){
                $id_to_return = intval($params['id']);
            } else {
                $id_to_return = DB::getPdo()->lastInsertId();
            }
            $params['id'] = $id_to_return;
            $this->app->event_manager->trigger('mw.user.save', $params);
        } else {
            return array('error' => 'Error saving the user!');
        }
        $this->app->cache_manager->delete('users' . DIRECTORY_SEPARATOR . 'global');
        $this->app->cache_manager->delete('users' . DIRECTORY_SEPARATOR . '0');
        $this->app->cache_manager->delete('users' . DIRECTORY_SEPARATOR . $id_to_return);

        return $id_to_return;
    }


    public function login_set_failed_attempt() {
        $this->app->log_manager->save("title=Failed login&is_system=y&rel_type=login_failed&user_ip=" . MW_USER_IP);
    }

    public function get($params = false) {


        $id = $params;
        if ($id==false){
            $id = $this->id();
        }
        if ($id==0){
            return false;
        }
        $res = $this->get_by_id($id);
        if (empty($res)){
            $res = $this->get_by_username($id);
        }

        return $res;
    }

    public function get_by_username($username) {
        $data = array();
        $data['username'] = $username;
        $data['limit'] = 1;
        $data = $this->get_all($data);
        if (isset($data[0])){
            $data = $data[0];
        }

        return $data;
    }

    function delete($data) {
        if (!is_array($data)){
            $new_data = array();
            $new_data['id'] = intval($data);
            $data = $new_data;
        }
        if (isset($data['id'])){
            $c_id = intval($data['id']);
            $this->app->database_manager->delete_by_id('users', $c_id);

            return $c_id;

        }

        return $data;
    }

    public function reset_password_from_link($params) {
        if (!isset($params['captcha'])){
            return array('error' => 'Please enter the captcha answer!');
        } else {
            $validate_captcha = $this->app->captcha->validate($params['captcha']);
            if (!$validate_captcha){
                return array('error' => 'Invalid captcha answer!', 'captcha_error' => true);
            }
        }

        if (!isset($params['id']) or trim($params['id'])==''){
            return array('error' => 'You must send id parameter');
        }

        if (!isset($params['password_reset_hash']) or trim($params['password_reset_hash'])==''){
            return array('error' => 'You must send password_reset_hash parameter');
        }

        if (!isset($params['pass1']) or trim($params['pass1'])==''){
            return array('error' => 'Enter new password!');
        }

        if (!isset($params['pass2']) or trim($params['pass2'])==''){
            return array('error' => 'Enter repeat new password!');
        }

        if ($params['pass1']!=$params['pass2']){
            return array('error' => 'Your passwords does not match!');
        }

        $data1 = array();
        $data1['id'] = intval($params['id']);
        $data1['password_reset_hash'] = $this->app->database_manager->escape_string($params['password_reset_hash']);
        $table = $this->tables['users'];

        $check = $this->get_all("single=true&password_reset_hash=[not_null]&password_reset_hash=" . $data1['password_reset_hash'] . '&id=' . $data1['id']);
        if (!is_array($check)){
            return array('error' => 'Invalid data or expired link!');
        } else {
            $data1['password_reset_hash'] = '';
        }
        $this->force_save = true;
        $save = $this->app->database_manager->save($table, $data1);
        $save_user = array();
        $save_user['id'] = intval($params['id']);
        $save_user['password'] = $params['pass1'];
        if (isset($check['email'])){
            $save_user['email'] = $check['email'];

        }
        $this->app->event_manager->trigger('mw.user.change_password', $save_user);

        $this->save($save_user);


        $notif = array();
        $notif['module'] = "users";
        $notif['rel_type'] = 'users';
        $notif['rel_id'] = $data1['id'];
        $notif['title'] = "The user have successfully changed password. (User id: {$data1['id']})";

        $this->app->log_manager->save($notif);
        $this->session_end();

        return array('success' => 'Your password have been changed!');

    }

    public function session_end() {
        \Session::flush();
        \Session::regenerate();
    }

    public function send_forgot_password($params) {

        if (!isset($params['captcha'])){
            return array('error' => 'Please enter the captcha answer!');
        } else {
            $validate_captcha = $this->app->captcha->validate($params['captcha']);
            if ($validate_captcha==false){
                return array('error' => 'Invalid captcha answer!', 'captcha_error' => true);
            }
        }
        if (isset($params['email'])){
            //return array('error' => 'Enter username or email!');
        } else if (!isset($params['username']) or trim($params['username'])==''){
            return array('error' => 'Enter username or email!');
        }


        $data_res = false;
        $data = false;
        if (isset($params) and !empty($params)){
            $user = isset($params['username']) ? $params['username'] : false;
            $email = isset($params['email']) ? $params['email'] : false;
            $data = array();
            if (trim($user!='')){
                $data1 = array();
                $data1['username'] = $user;
                $data = array();
                if (trim($user!='')){
                    $data = $this->get_all($data1);
                    if ($data==false){
                        $data1 = array();
                        $data1['email'] = $user;
                        $data = $this->get_all($data1);
                    }
                }
            } elseif (trim($email!='')) {
                $data1 = array();
                $data1['email'] = $email;
                $data = array();
                if (trim($email!='')){
                    $data = $this->get_all($data1);
                }
            }

            if (isset($data[0])){
                $data_res = $data[0];
            }
            if (!is_array($data_res)){
                return array('error' => 'Enter right username or email!');
            } else {
                $to = $data_res['email'];
                if (isset($to) and (filter_var($to, FILTER_VALIDATE_EMAIL))){

                    $subject = "Password reset!";
                    $content = "Hello, {$data_res['username']} <br> ";
                    $content .= "You have requested a password reset link from IP address: " . MW_USER_IP . "<br><br> ";
                    $security = array();
                    $security['ip'] = MW_USER_IP;
                    //  $security['hash'] = $this->app->format->array_to_base64($data_res);
                    $function_cache_id = md5(rand()) . uniqid() . rand() . str_random(40);
                    if (isset($data_res['id'])){
                        $data_to_save = array();
                        $data_to_save['id'] = $data_res['id'];
                        $data_to_save['password_reset_hash'] = $function_cache_id;
                        $table = $this->tables['users'];
                        mw_var('FORCE_SAVE', $table);
                        $save = $this->app->database_manager->save($table, $data_to_save);
                    }

                    $base_link = $this->app->url_manager->current(1);

                    $cur_template = template_dir();
                    $cur_template_file = normalize_path($cur_template . 'login.php', false);
                    $cur_template_file2 = normalize_path($cur_template . 'forgot_password.php', false);
                    if (is_file($cur_template_file)){
                        $base_link = site_url('login');
                    } elseif (is_file($cur_template_file2)) {
                        $base_link = site_url('forgot_password');
                    }


                    $pass_reset_link = $base_link . '?reset_password_link=' . $function_cache_id;
                    $notif = array();
                    $notif['module'] = "users";
                    $notif['rel_type'] = 'users';
                    $notif['rel_id'] = $data_to_save['id'];
                    $notif['title'] = "Password reset link sent";
                    $content_notif = "User with id: {$data_to_save['id']} and email: {$to}  has requested a password reset link";
                    $notif['description'] = $content_notif;
                    $this->app->log_manager->save($notif);
                    $content .= "Click here to reset your password  <a href='{$pass_reset_link}'>" . $pass_reset_link . "</a><br><br> ";
                    $sender = new \Microweber\Utils\MailSender();
                    $sender->send($to, $subject, $content);

                    return array('success' => 'Your password reset link has been sent to ' . $to);
                } else {
                    return array('error' => 'Error: the user doesn\'t have a valid email address!');
                }

            }


        }

    }

    public function social_login($params) {
        if (is_string($params)){
            $params = parse_params($params);
        }

        $return_after_login = false;
        if (isset($params['redirect'])){
            $return_after_login = $params['redirect'];
            $this->session_set('user_after_login', $return_after_login);
        } else if (isset($_SERVER["HTTP_REFERER"]) and stristr($_SERVER["HTTP_REFERER"], $this->app->url_manager->site())){
            $return_after_login = $_SERVER["HTTP_REFERER"];
            $this->session_set('user_after_login', $return_after_login);
        }

        $provider = false;
        if (isset($_REQUEST['provider'])){
            $provider = $_REQUEST['provider'];
            $provider = trim(strip_tags($provider));
        }

        if ($provider!=false and isset($params) and !empty($params)){
            $this->socialite_config($provider);
            switch ($provider) {
                case "github":
                    return $login = $this->socialite->with($provider)->scopes(['user:email'])->redirect();
            }

            return $login = $this->socialite->with($provider)->redirect();
        }
    }


    public function make_logged($user_id) {


        if (is_array($user_id)){
            if (isset($user_id['id'])){
                $user_id = $user_id['id'];
            }
        }
        if (intval($user_id) > 0){
            $data = $this->get_by_id($user_id);
            if ($data==false){
                return false;
            } else {
                if (is_array($data)){
                    $user_session = array();
                    $user_session['is_logged'] = 'yes';
                    $user_session['user_id'] = $data['id'];

                    if (!defined('USER_ID')){
                        define("USER_ID", $data['id']);
                    }

                    $old_sid = Session::getId();
                    $data['old_sid'] = $old_sid;
                    $user_session['old_session_id'] = $old_sid;
                    $current_user = Auth::user();
                    if ((isset($current_user->id) and $current_user->id==$user_id)){
                        Auth::login(Auth::user());
                    } else {
                        Auth::loginUsingId($data['id']);
                    }
//
//                    Session::setId($old_sid);
//                    Session::save();

                    $this->app->event_manager->trigger('mw.user.login', $data);
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
     *
     * @return array
     *
     */
    public function get_by_id($id) {
        $id = intval($id);
        if ($id==0){
            return false;
        }

        $data = array();
        $data['id'] = $id;
        $data['limit'] = 1;
        $data['single'] = 1;


        $data = $this->get_all($data);

        return $data;
    }

    public function update_last_login_time() {

        $uid = user_id();
        if (intval($uid) > 0){

            $data_to_save = array();
            $data_to_save['id'] = $uid;
            $data_to_save['last_login'] = date("Y-m-d H:i:s");
            $data_to_save['last_login_ip'] = MW_USER_IP;

            $table = $this->tables['users'];
            $save = $this->app->database_manager->save($table, $data_to_save);

            $this->app->log_manager->delete("is_system=y&rel_type=login_failed&user_ip=" . MW_USER_IP);

        }

    }

    public function social_login_process($params = false) {
        $user_after_login = $this->session_get('user_after_login');

        if (!isset($_REQUEST['provider']) and isset($_REQUEST['hauth_done'])){
            $_REQUEST['provider'] = $_REQUEST['hauth_done'];
        }
        if (!isset($_REQUEST['provider'])){
            return $this->app->url_manager->redirect(site_url());

        }

        $auth_provider = $_REQUEST['provider'];
        $this->socialite_config($auth_provider);

        $user = $this->socialite->driver($auth_provider)->user();

        $email = $user->getEmail();

        $username = $user->getNickname();
        $oauth_id = $user->getId();
        $avatar = $user->getAvatar();
        $name = $user->getName();

        $existing = array();


        if ($email!=false){
            $existing['email'] = $email;
        } else {
            $existing['oauth_uid'] = $oauth_id;
            $existing['oauth_provider'] = $auth_provider;
        }
        $save = $existing;
        $save['thumbnail'] = $avatar;
        $save['username'] = $username;
        $save['is_active'] = 1;
        $save['is_admin'] = is_null(User::first());
        $save['first_name'] = '';
        $save['last_name'] = '';
        if ($name!=false){
            $names = explode(' ', $name);
            if (isset($names[0])){
                $save['first_name'] = array_shift($names);
                if (!empty($names)){
                    $last = implode(' ', $names);
                    $save['last_name'] = $last;
                }
            }
        }
        $existing['single'] = true;
        $existing['limit'] = 1;
        $existing = $this->get_all($existing);
        if (!defined('MW_FORCE_USER_SAVE')){
            define('MW_FORCE_USER_SAVE', true);
        }
        if (isset($existing['id'])){
            if ($save['is_active']!=1){
                return;
            }
            $this->make_logged($existing['id']);
        } else {
            $new_user = $this->save($save);
            $this->after_register($new_user);

            $this->make_logged($new_user);
        }

        if ($user_after_login!=false){
            return $this->app->url_manager->redirect($user_after_login);

        } else {
            return $this->app->url_manager->redirect(site_url());
        }

    }

    public function count() {
        $options = array();
        $options['count'] = true;
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
    public function get_all($params) {
        $params = parse_params($params);

        $table = $this->tables['users'];

        $data = $this->app->format->clean_html($params);
        $orig_data = $data;

        if (isset($data['ids']) and is_array($data['ids'])){
            if (!empty($data['ids'])){
                $ids = $data['ids'];
            }
        }
        if (!isset($params['search_in_fields'])){
            $data['search_in_fields'] = array('id', 'first_name', 'last_name', 'username', 'email');
        }
        $cache_group = 'users/global';
        if (isset($limit) and $limit!=false){
            $data['limit'] = $limit;
        }
        if (isset($count_only) and $count_only!=false){
            $data['count'] = $count_only;
        }
        if (isset($data['username']) and $data['username']==false){
            unset($data['username']);
        }

        $data['table'] = $table;
        $get = $this->app->database_manager->get($data);

        return $get;
    }

    function register_url() {


        $template_dir = $this->app->template->dir();
        $file = $template_dir . 'register.php';
        $default_url = false;
        if (is_file($file)){
            $default_url = 'register';
        } else {
            $default_url = 'users/register';
        }

        $checkout_url = $this->app->option_manager->get('register_url', 'users');
        if ($checkout_url!=false and trim($checkout_url)!=''){
            $default_url = $checkout_url;
        }

        $checkout_url_sess = $this->session_get('register_url');

        if ($checkout_url_sess==false){
            return $this->app->url_manager->site($default_url);
        } else {
            return $this->app->url_manager->site($checkout_url_sess);
        }

    }


    function logout_url() {
        return api_url('logout');
    }

    function login_url() {

        $template_dir = $this->app->template->dir();
        $file = $template_dir . 'login.php';
        $default_url = false;
        if (is_file($file)){
            $default_url = 'login';
        } else {
            $default_url = 'users/login';
        }

        $checkout_url = $this->app->option_manager->get('login_url', 'users');
        if ($checkout_url!=false and trim($checkout_url)!=''){
            $default_url = $checkout_url;
        }

        $checkout_url_sess = $this->session_get('login_url');

        if ($checkout_url_sess==false){
            return $this->app->url_manager->site($default_url);
        } else {
            return $this->app->url_manager->site($checkout_url_sess);
        }

    }

    function forgot_password_url() {


        $template_dir = $this->app->template->dir();
        $file = $template_dir . 'forgot_password.php';
        $default_url = false;
        if (is_file($file)){
            $default_url = 'forgot_password';
        } else {
            $default_url = 'users/forgot_password';
        }
        $checkout_url = $this->app->option_manager->get('forgot_password_url', 'users');
        if ($checkout_url!=false and trim($checkout_url)!=''){
            $default_url = $checkout_url;
        }
        $checkout_url_sess = $this->session_get('forgot_password_url');
        if ($checkout_url_sess==false){
            return $this->app->url_manager->site($default_url);
        } else {
            return $this->app->url_manager->site($checkout_url_sess);
        }
    }

    public function session_set($name, $val) {
        $this->app->event_manager->trigger('mw.user.session_set', $name, $val);

        return Session::put($name, $val);
    }

    function csrf_form($unique_form_name = false) {
        if ($unique_form_name==false){
            $unique_form_name = uniqid();
        }

        $token = $this->csrf_token($unique_form_name);

        $input = '<input type="hidden" value="' . $token . '" name="_token">';

        return $input;
    }

    public function session_all() {
        $value = Session::all();

        return $value;
    }

    public function session_id() {
        return Session::getId();
    }

    public function session_get($name) {
        $value = Session::get($name);

        return $value;
    }

    public function session_del($name) {
        Session::forget($name);
    }

    function csrf_token($unique_form_name = false) {
        return csrf_token();
    }


    public function socialite_config($provider = false) {
        $callback_url = api_url('social_login_process?provider=' . $provider);

        if (get_option('enable_user_fb_registration', 'users')=='y'){
            Config::set('services.facebook.client_id', get_option('fb_app_id', 'users'));
            Config::set('services.facebook.client_secret', get_option('fb_app_secret', 'users'));
            Config::set('services.facebook.redirect', $callback_url);
        }

        if (get_option('enable_user_twitter_registration', 'users')=='y'){
            Config::set('services.twitter.client_id', get_option('twitter_app_id', 'users'));
            Config::set('services.twitter.client_secret', get_option('twitter_app_secret', 'users'));
            Config::set('services.twitter.redirect', $callback_url);
        }

        if (get_option('enable_user_google_registration', 'users')=='y'){
            Config::set('services.google.client_id', get_option('google_app_id', 'users'));
            Config::set('services.google.client_secret', get_option('google_app_secret', 'users'));
            Config::set('services.google.redirect', $callback_url);
        }

        if (get_option('enable_user_github_registration', 'users')=='y'){
            Config::set('services.github.client_id', get_option('github_app_id', 'users'));
            Config::set('services.github.client_secret', get_option('github_app_secret', 'users'));
            Config::set('services.github.redirect', $callback_url);
        }

        if (get_option('enable_user_microweber_registration', 'users')=='y'){
            $svc = Config::get('services.microweber');
            if (!isset($svc['client_id'])){
                Config::set('services.microweber.client_id', get_option('microweber_app_id', 'users'));
            }
            if (!isset($svc['client_secret'])){
                Config::set('services.microweber.client_secret', get_option('microweber_app_secret', 'users'));
            }
            if (!isset($svc['redirect'])){
                Config::set('services.microweber.redirect', $callback_url);
            }
            $this->socialite->extend('microweber', function ($app) {
                $config = $app['config']['services.microweber'];

                return $this->socialite->buildProvider('\Microweber\Providers\Socialite\MicroweberProvider', $config);
            });
        }

    }


}
