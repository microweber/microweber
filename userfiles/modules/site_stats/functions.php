<?php

if (!defined("MODULE_DB_USERS_ONLINE")) {
    define('MODULE_DB_USERS_ONLINE', get_table_prefix() . 'stats_users_online');
}

event_bind('mw.admin.dashboard.content', 'mw_print_stats_on_dashboard');


event_bind('mw_admin_quick_stats_by_session', 'mw_print_quick_stats_by_session');
function mw_print_quick_stats_by_session($sid = false)
{

    print '<microweber module="site_stats" view="admin" data-subtype="quick" data-user-sid="' . $sid . '" />';
}

function mw_print_stats_on_dashboard()
{
    $active = url_param('view');
    $cls = '';
    if ($active == 'shop') {
        //   $cls = ' class="active" ';
    }
	print '  <module type="site_stats/admin" subtype="graph" />
  <module type="site_stats/admin" />';
    //print '<microweber module="site_stats" view="admin" />';
}

function mw_install_stats_module($config = false)
{
    return true;
    if (is_admin() == false) {
        return false;
    }
    mw('cache')->delete('stats');

    $this_dir = dirname(__FILE__);

    $sql = $this_dir . DS . 'install.sql';
    $cfg = $this_dir . DS . 'config.php';

    $is_installed = db_table_exist(MODULE_DB_USERS_ONLINE);
    //d($is_installed);
    if ($is_installed == false) {
        $install = import_sql_from_file($sql);
        //   mw('cache')->delete('db');

        return true;
    } elseif (is_array($is_installed) and !empty($is_installed)) {

    } else {

        return false;
    }
    //d($install);
}

function mw_uninstall_stats_module()
{
    if (is_admin() == false) {
        return false;
    }

    $table = MODULE_DB_USERS_ONLINE;
    $q = "DROP TABLE IF EXISTS {$table}; ";
    //d($q);

    mw()->database_manager->q($q);
    mw('cache')->delete('stats');
    //  mw('cache')->delete('db');
}

//document_ready('stats_append_image');

event_bind('frontend', 'stats_append_image');

function stats_append_image($layout = false)
{
    if (defined('MW_API_CALL')) {
        return true;
    }

    if (defined('MW_FRONTEND') and !isset($_REQUEST['isolate_content_field'])) {
        stats_insert();
    }

}

api_expose('stats_image');

function stats_image()
{
    stats_insert();

    $f = dirname(__FILE__);
    $f = $f . DS . '1px.png';
    $name = $f;
    $fp = fopen($name, 'rb');

    // send the right headers
    header("Content-Type: image/png");
    header("Content-Length: " . filesize($name));

    // dump the picture and stop the script
    fpassthru($fp);
    exit;
}

function stats_insert()
{

    if (!isset($_SERVER['HTTP_USER_AGENT']) or stristr($_SERVER['HTTP_USER_AGENT'], 'bot')) {

        return;
    }
    $function_cache_id = false;
    $uip = $_SERVER['REMOTE_ADDR'];
    $function_cache_id = $function_cache_id . $uip . MW_USER_IP;

    $function_cache_id = __FUNCTION__ . crc32($function_cache_id);
    $few_mins_ago_visit_date = date("Y-m-d H:i:s");

    $cookie_name = 'mw-stats' . crc32($function_cache_id);
    $cookie_name_time = 'mw-time' . crc32($function_cache_id);

    $vc1 = 1;
    if (isset($_SESSION[$cookie_name])) {
        $vc1 = intval($_SESSION[$cookie_name]) + 1;
        $_SESSION[$cookie_name] = $vc1;

    } elseif (!isset($_SESSION[$cookie_name])) {
        $_SESSION[$cookie_name] = $vc1;
    }


    if (!isset($_COOKIE[$cookie_name_time])) {
        if (!headers_sent()) {
            setcookie($cookie_name_time, $few_mins_ago_visit_date, time() + 90);
        }
        $data = array();
        $data['visit_date'] = date("Y-m-d");
        $data['visit_time'] = date("H:i:s");

        $table = MODULE_DB_USERS_ONLINE;
        $check = get("table={$table}&user_ip={$uip}&one=1&limit=1&visit_date=" . $data['visit_date']);
        if ($check != false and is_array($check) and !empty($check) and isset($check['id'])) {
            $data['id'] = $check['id'];
            $vc = 0;
            if (isset($check['view_count'])) {
                $vc = ($check['view_count']);
            }

            $vc1 = 0;
            if (isset($_SESSION[$cookie_name])) {
                $vc1 = intval($_SESSION[$cookie_name]);
            }
            $vc = $vc + $vc1;
            $data['view_count'] = $vc;
        }
        $lp = url_current(true);

        if ($lp == false) {
            if (isset($_SERVER['HTTP_REFERER'])) {
                $lp = $_SERVER['HTTP_REFERER'];
            } else {
                $lp = $_SERVER['PHP_SELF'];
            }
        }

        $data['last_page'] = $lp;
        $data['skip_cache'] = 1;

        mw_var('FORCE_SAVE', $table);
        mw_var('apc_no_clear', 1);
        $save = mw()->database_manager->save($table, $data);
        $_SESSION[$cookie_name] = 0;

        mw_var('apc_no_clear', 0);
        //	setcookie($cookie_name, 1);

    }
    return true;

}


function stats_insert_cookie_based()
{

    $function_cache_id = false;
    $uip = $_SERVER['REMOTE_ADDR'];
    $function_cache_id = $function_cache_id . $uip . MW_USER_IP;

    $function_cache_id = __FUNCTION__ . crc32($function_cache_id);

    //$cache_content = mw('cache')->get($function_cache_id, $cache_group = 'module_stats_users_online');
    //if (($cache_content) == '--false--') {
    //return false;
    //	}
    $cookie_name = 'mw-stats' . crc32($function_cache_id);
    $cookie_name_time = 'mw-time' . crc32($function_cache_id);

    $vc1 = 1;


    $few_mins_ago_visit_date = date("Y-m-d H:i:s");
    if (isset($_COOKIE[$cookie_name])) {
        $vc1 = intval($_COOKIE[$cookie_name]) + 1;
        //	$_SESSION[$cookie_name] = $vc1;
        setcookie($cookie_name, $vc1, time() + 99);
        //  return true;
    } elseif (!isset($_COOKIE[$cookie_name])) {
        setcookie($cookie_name, $vc1, time() + 99);
        //$_SESSION[$cookie_name] = $vc1;
        // return true;
    }

    if (!isset($_COOKIE[$cookie_name_time])) {


        setcookie($cookie_name_time, $few_mins_ago_visit_date, time() + 90);

        $data = array();
        $data['visit_date'] = date("Y-m-d", strtotime("now"));
        $data['visit_time'] = date("H:i:s", strtotime("now"));

        $table = MODULE_DB_USERS_ONLINE;
        $check = get("no_cache=1&table={$table}&user_ip={$uip}&one=1&limit=1&visit_date=" . $data['visit_date']);
        if ($check != false and is_array($check) and !empty($check) and isset($check['id'])) {
            $data['id'] = $check['id'];
            $vc = 0;
            if (isset($check['view_count'])) {
                $vc = ($check['view_count']);
            }

            $vc1 = 0;
            if (isset($_COOKIE[$cookie_name])) {
                $vc1 = intval($_COOKIE[$cookie_name]);
            }
            $vc = $vc + $vc1;
            $data['view_count'] = $vc;
        }
        if (isset($_SERVER['HTTP_REFERER'])) {
            $lp = $_SERVER['HTTP_REFERER'];
        } else {
            $lp = $_SERVER['PHP_SELF'];
        }
        $data['last_page'] = $lp;
        $data['skip_cache'] = 1;
        if (isset($_SESSION) and !empty($_SESSION)) {
            $data['session_id'] = session_id();
        }
        mw_var('FORCE_SAVE', $table);
        mw_var('apc_no_clear', 1);
        $save = mw()->database_manager->save($table, $data);
        //	$_SESSION[$cookie_name] = 0;
        setcookie($cookie_name, 0, time() + 99);

        mw_var('apc_no_clear', 0);
        //	setcookie($cookie_name, 1);

    }
    return true;

}

function get_visits_for_sid($sid)
{
    $table = MODULE_DB_USERS_ONLINE;
    $q = false;
    $results = false;
    $data = array();
    $data['table'] = $table;
    $data['session_id'] = $sid;
    $data['limit'] = 10;
    $data['order_by'] = "visit_date desc,visit_time desc";
    return get($data);


}

function get_visits($range = 'daily')
{
    $table = MODULE_DB_USERS_ONLINE;
    $q = false;
    $results = false;
    switch ($range) {
        case 'daily' :
            $ago = date("Y-m-d", strtotime("-1 month"));
            $q = "SELECT COUNT(*) AS unique_visits, SUM(view_count) AS total_visits, visit_date FROM $table WHERE visit_date > '$ago' GROUP BY visit_date  ";
            $results = mw()->database_manager->query($q);

            break;

        case 'weekly' :
            $ago = date("Y-m-d", strtotime("-1 year"));


            $q = "SELECT COUNT(*) AS unique_visits, SUM(view_count) AS total_visits,visit_date, DATE_FORMAT(visit_date, '%x %V') AS weeks  FROM $table WHERE visit_date > '$ago' GROUP BY weeks  ";

            $results = mw()->database_manager->query($q);

            break;

        case 'monthly' :
            $ago = date("Y-m-d", strtotime("-1 year"));
            //

            $q = "SELECT COUNT(*) AS unique_visits, SUM(view_count) AS total_visits,visit_date, DATE_FORMAT(visit_date, '%x %m') AS months  FROM $table WHERE visit_date > '$ago' GROUP BY months  ";

            $results = mw()->database_manager->query($q);

            break;

        case 'last5' :
            $q = "SELECT * FROM $table ORDER BY visit_date DESC, visit_time DESC LIMIT 5  ";


            $results = mw()->database_manager->query($q);

            break;


        case 'requests_num' :
            $ago = date("H:i:s", strtotime("-1 minute"));
            $ago2 = date("Y-m-d", strtotime("now"));
            $total = 0;
            $q = "SELECT SUM(view_count) AS total_visits FROM $table  WHERE visit_date='$ago2' AND visit_time>'$ago'   ";
            $results = mw()->database_manager->query($q);
            if (isset($results[0]) and isset($results[0]['total_visits'])) {
                $mw_req_sec = mw()->user_manager->session_get('stats_requests_num');
                $total = $results[0]['total_visits'];
                mw()->user_manager->session_set('stats_requests_num', $total);
                $results = intval($total) - intval($mw_req_sec);
            } else {
                $results = false;
            }

            break;


        case 'users_online' :
            $ago = date("H:i:s", strtotime("-15 minutes"));
            $ago2 = date("Y-m-d", strtotime("now"));
            $q = "SELECT COUNT(*) AS users_online FROM $table WHERE visit_date='$ago2' AND visit_time>'$ago'    ";

            $results = mw()->database_manager->query($q);
            if (is_array($results)) {
                $results = intval($results[0]['users_online']);
            }
            break;

        default :
            break;
    }

    if ($q == false or $results == false) {
        return false;
    }
    $url = site_url();
    $res = array();
    if (is_array($results)) {
        foreach ($results as $item) {
            if (isset($item['last_page'])) {
                $item['last_page'] = str_replace($url, '', $item['last_page']);
            }
            $res[] = $item;
        }
        return $res;
    }
    return $results;
}
