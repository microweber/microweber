<?php
namespace Mw;

action_hook('mw_db_init', mw('Mw\User')->db_init());

class User
{


    function __construct()
    {
        if (!defined("MW_DB_TABLE_USERS")) {
            define('MW_DB_TABLE_USERS', MW_TABLE_PREFIX . 'users');
        }
        if (!defined("MW_DB_TABLE_LOG")) {
            define('MW_DB_TABLE_LOG', MW_TABLE_PREFIX . 'log');
        }
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
     * action_hook('on_user_login', 'custom_after_login_function'); //executed after successful login
     * </code>
     * @package Users
     * @category Users
     * @uses hash_user_pass()
     * @uses parse_str()
     * @uses $this->get_all()
     * @uses $this->session_set()
     * @uses get_log()
     * @uses save_log()
     * @uses user_login_set_failed_attempt()
     * @uses user_update_last_login_time()
     * @uses exec_action()
     * @function user_login()
     * @see _table() For the database table fields
     */
    public function login($params)
    {
        $params2 = array();


        $override = exec_action('before_user_login', $params);
        if (is_arr($override)) {
            foreach ($override as $resp) {
                if (isset($resp['error']) or isset($resp['success'])) {
                    return $resp;
                }
            }
        }
        
        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
        }


        //$is_logged =  $this->session_get('user_session');
        // if(isarr($is_logged) and isset($is_logged['']))


        if (isset($params) and !empty($params)) {

            $user = isset($params['username']) ? $params['username'] : false;
            $pass = isset($params['password']) ? $params['password'] : false;
            $email = isset($params['email']) ? $params['email'] : false;
            $pass2 = isset($params['password_hashed']) ? $params['password_hashed'] : false;

            $pass = hash_user_pass($pass);
            if ($pass2 != false and $pass2 != NULL and trim($pass2) != '') {
                $pass = $pass2;
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


                    $data = $this->get_all($data);

                    if (isset($data[0])) {
                        $data = $data[0];
                    }
                }
            }
            if (!isarr($data)) {
                user_login_set_failed_attempt();

                $user_session = array();
                $user_session['is_logged'] = 'no';
                $this->session_set('user_session', $user_session);

                return array('error' => 'Please enter the right username and password!');

            } else {
                $user_session = array();
                $user_session['is_logged'] = 'yes';
                $user_session['user_id'] = $data['id'];

                if (!defined('USER_ID')) {
                    define("USER_ID", $data['id']);


                }
                user_set_logged($data['id']);
                if (isset($data["is_admin"]) and $data["is_admin"] == 'y') {
                    if (isset($params['where_to']) and $params['where_to'] == 'live_edit') {
                        exec_action('user_login_admin');
                        $p = mw('content')->get_page();
                        if (!empty($p)) {
                            $link = page_link($p['id']);
                            $link = $link . '/editmode:y';
                            mw('url')->redirect($link);
                        }
                    }
                }

                $aj = isAjax();

                if ($aj == false and $api_key == false) {
                    if (isset($_SERVER["HTTP_REFERER"])) {
                        //	d($user_session);
                        //exit();
                        mw('url')->redirect($_SERVER["HTTP_REFERER"]);
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


    public function logout()
    {

        if (!defined('USER_ID')) {
            define("USER_ID", false);
        }

        // static $uid;
        $aj = isAjax();
        mw('user')->session_end();

        if (isset($_COOKIE['editmode'])) {
            setcookie('editmode');
        }

        if ($aj == false) {
            if (isset($_SERVER["HTTP_REFERER"])) {
                mw('url')->redirect($_SERVER["HTTP_REFERER"]);
            }
        }
    }

    public function is_logged()
    {

        if (user_id() > 0) {
            return true;
        } else {
            return false;
        }


    }

    public function has_access($function_name)
    {

        // will be updated with roles and perms
        $is_a = is_admin();

        if ($is_a == true) {
            return true;
        } else {
            return false;
        }
    }

    public function admin_access()
    {

        if (is_admin() == false) {
            exit('You must be logged as admin');
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
        $data = $this->get_all($data);
        if (isset($data[0])) {
            $data = $data[0];
        }
        return $data;
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

        $table = MW_TABLE_PREFIX . 'users';

        $data = mw('format')->clean_html($params);
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

        $get = \mw('db')->get($data);

        //$get = \mw('db')->get_long($table, $criteria = $data, $cache_group);
        // $get = \mw('db')->get_long($table, $criteria = $data, $cache_group);
        // var_dump($get, $function_cache_id, $cache_group);
        //  mw('cache')->save($get, $function_cache_id, $cache_group);

        return $get;
    }

    public function is_admin()
    {

        static $is = 0;
        if (MW_IS_INSTALLED == false) {
            return true;
        }
        if ($is != 0 or defined('USER_IS_ADMIN')) {
             return $is;
        } else {
            $usr = $this->id();
            if ($usr == false) {
                return false;
            }
            $usr = $this->get($usr);

            if (isset($usr['is_admin']) and $usr['is_admin'] == 'y') {
                define("USER_IS_ADMIN", true);
                define("IS_ADMIN", true);
            } else {
                define("USER_IS_ADMIN", false);
                define("IS_ADMIN", false);
            }
            $is = USER_IS_ADMIN;

            return USER_IS_ADMIN;
        }
    }

    public function id()
    {

        // static $uid;
        if (defined('USER_ID')) {
            // print USER_ID;
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

    public function make_logged($user_id)
    {

        if (is_array($user_id)) {
            if (isset($user_id['id'])) {
                $user_id = $user_id['id'];
            }
        }

        if (intval($user_id) > 0) {
            $data = get_user($user_id);
            if ($data == false) {
                return false;
            } else {
                if (isarr($data)) {
                    $user_session = array();
                    $user_session['is_logged'] = 'yes';
                    $user_session['user_id'] = $data['id'];

                    if (!defined('USER_ID')) {
                        define("USER_ID", $data['id']);


                    }
                    exec_action('user_login', $data);
                    $this->session_set('user_session', $user_session);
                    $user_session = $this->session_get('user_session');
                    user_update_last_login_time();
                    $user_session['success'] = "You are logged in!";
                    return $user_session;
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
            $hash = mw('db')->escape_string($hash);
            return $pass;
        }
        return $hash;

    }

    public function login_set_failed_attempt()
    {

        save_log("title=Failed login&is_system=y&rel=login_failed&user_ip=" . USER_IP);

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
                $api_key = mw('db')->escape_string($api_key);
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
                                return user_login($data);

                            }

                        }

                    }
                }

            }
        }

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

    public function db_init()
    {
        $function_cache_id = false;

        $args = func_get_args();

        foreach ($args as $k => $v) {

            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
        }

        $function_cache_id = 'users' . __FUNCTION__ . crc32($function_cache_id);

        $cache_content = mw('cache')->get($function_cache_id, 'db');

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

        \mw('Mw\DbUtils')->build_table($table_name, $fields_to_add);

        \mw('Mw\DbUtils')->add_table_index('username', $table_name, array('username(255)'));
        \mw('Mw\DbUtils')->add_table_index('email', $table_name, array('email(255)'));


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

        \mw('Mw\DbUtils')->build_table($table_name, $fields_to_add);

        mw('cache')->save(true, $function_cache_id, $cache_group = 'db');
        return true;

    }






    public function session_set($name, $val)
    {


        if (!defined('MW_NO_SESSION') and !headers_sent()) {
            if (!isset($_SESSION)) {
                session_set_cookie_params(86400);
                ini_set('session.gc_maxlifetime', 86400);
                session_start();
                $_SESSION['ip'] = USER_IP;
            }
            if ($val == false) {
                mw('user')->session_del($name);
            } else {
                $is_the_same = $this->session_get($name);
                if ($is_the_same != $val) {
                    $_SESSION[$name] = $val;
                    //session_write_close();
                    //$_SESSION['ip']=USER_IP;
                }
            }
        }
    }

    public function session_get($name)
    {
        if (!defined('MW_NO_SESSION')) {
            if (!headers_sent()) {
                if (!isset($_SESSION)) {
                    //return false;
                    session_start();
                    //d($_SESSION);
                    $_SESSION['ip'] = USER_IP;
                }
            }
            // probable timout here?!
        }
        //
        if (isset($_SESSION) and isset($_SESSION[$name])) {


            if (!isset($_SESSION['ip'])) {
                $_SESSION['ip'] = USER_IP;
            } else if ($_SESSION['ip'] != USER_IP) {

                mw('user')->session_end();
                return false;
            }


            return $_SESSION[$name];
        } else {
            return false;
        }
    }

    public function session_del($name)
    {
        if (isset($_SESSION[$name])) {
            unset($_SESSION[$name]);
        }
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
}