<?php
namespace mw;
if (!defined("MW_DB_TABLE_USERS")) {
    define('MW_DB_TABLE_USERS', MW_TABLE_PREFIX . 'users');
}
if (!defined("MW_DB_TABLE_LOG")) {
    define('MW_DB_TABLE_LOG', MW_TABLE_PREFIX . 'log');
}

class User
{

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
     * @uses get_users()
     * @uses session_set()
     * @uses get_log()
     * @uses save_log()
     * @uses user_login_set_failed_attempt()
     * @uses user_update_last_login_time()
     * @uses exec_action()
     * @function user_login()
     * @see _table() For the database table fields
     */
    static function login($params)
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


        //$is_logged =  session_get('user_session');
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


   static function logout()
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

    static function is_logged()
    {

        if (user_id() > 0) {
            return true;
        } else {
            return false;
        }


    }
    static function has_access($function_name)
    {

        // will be updated with roles and perms
        $is_a = is_admin();

        if ($is_a == true) {
            return true;
        } else {
            return false;
        }
    }

    static function admin_access()
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
    static function name($user_id = false, $mode = 'full')
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
     * Generic function to get the user by id.
     * Uses the getUsers function to get the data
     *
     * @param
     *            int id
     * @return array
     *
     */
    static function get_by_id($id)
    {
        $id = intval($id);
        if ($id == 0) {
            return false;
        }

        $data = array();
        $data['id'] = $id;
        $data['limit'] = 1;
        $data = self::get_all($data);
        if (isset($data[0])) {
            $data = $data[0];
        }
        return $data;
    }
    static function get_by_username($username)
    {
        $data = array();
        $data['username'] = $username;
        $data['limit'] = 1;
        $data = self::get_all($data);
        if (isset($data[0])) {
            $data = $data[0];
        }
        return $data;
    }
    static function get($id = false)
    {
        if ($id == false) {
            $id = user_id();
        }

        if ($id == 0) {
            return false;
        }

        $res = self::get_by_id($id);

        if (empty($res)) {

            $res = get_user_by_username($id);
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
     * @usage get_users('email=my_email');
     *
     *
     * @return array of users;
     */
    static function get_all($params)
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

        $get = \mw\Db::get($data);

        //$get = \mw\Db::get_long($table, $criteria = $data, $cache_group);
        // $get = \mw\Db::get_long($table, $criteria = $data, $cache_group);
        // var_dump($get, $function_cache_id, $cache_group);
        //  cache_save($get, $function_cache_id, $cache_group);

        return $get;
    }
    static function is_admin()
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

    static function id()
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
    static function make_logged($user_id)
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
                    session_set('user_session', $user_session);
                    $user_session = session_get('user_session');
                    user_update_last_login_time();
                    $user_session['success'] = "You are logged in!";
                    return $user_session;
                }
            }
        }

    }
    static function hash_pass($pass)
    {

        // Currently only md5 is supported for portability
        // Will improve this soon!

        //$hash = password_hash($pass, PASSWORD_BCRYPT);
        //
        $hash = md5($pass);
        if ($hash == false) {
            $hash = db_escape_string($hash);
            return $pass;
        }
        return $hash;

    }
    static function login_set_failed_attempt()
    {

        save_log("title=Failed login&is_system=y&rel=login_failed&user_ip=" . USER_IP);

    }

    static function api_login($api_key = false)
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
    static function nice_name($id, $mode = 'full')
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
}