<?

if (!defined("MODULE_DB_TABLE_USERS_ONLINE")) {
    define('MODULE_DB_TABLE_USERS_ONLINE', TABLE_PREFIX . 'module_stats_users_online');
}

function mw_install_stats_module($config = false) {

    if (is_admin() == false) {
        return false;
    }
    cache_clean_group('stats');

    $this_dir = dirname(__FILE__);

    $sql = $this_dir . DS . 'install.sql';
    $cfg = $this_dir . DS . 'config.php';

    $is_installed = db_table_exist(MODULE_DB_TABLE_USERS_ONLINE);
    //d($is_installed);
    if ($is_installed == false) {
        $install = import_sql_from_file($sql);
        cache_clean_group('db');

        return true;
    } elseif (is_array($is_installed) and !empty($is_installed)) {
        return true;
    } else {

        return false;
    }
    //d($install);
}

function mw_uninstall_stats_module() {
    if (is_admin() == false) {
        return false;
    }

    $table = MODULE_DB_TABLE_USERS_ONLINE;
    $q = "DROP TABLE IF EXISTS {$table}; ";
    d($q);

    db_q($q);
    cache_clean_group('stats');
    cache_clean_group('db');
}

document_ready('stats_append_image');

function stats_append_image($layout) {

    //if (!defined('IN_ADMIN')) {
    //<table(?=[^>]*class="details")[^>]*>
    //$selector = '<body>';
    $selector = '/<\/body>/si';
    $rand = date("Y-m-d");
    $layout = modify_html($layout, $selector, '<img src="' . site_url('api/stats_image?rand=' . $rand) . '" height="1" class="semi_hidden statts_img" />', 'prepend');
    //}
    //   $layout = modify_html($layout, $selector = '.editor_wrapper', 'append', 'ivan');
    //$layout = modify_html2($layout, $selector = '<div class="editor_wrapper">', '');
    return $layout;
}

api_expose('stats_image');

function stats_image() {
    stats_insert();
    //exit();
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

function stats_insert() {
    $function_cache_id = false;
    $uip = $_SERVER['REMOTE_ADDR'];
    $function_cache_id = $function_cache_id . $uip . USER_IP;

    $function_cache_id = __FUNCTION__ . crc32($function_cache_id);

    $cache_content = cache_get_content($function_cache_id, $cache_group = 'module_stats_users_online');
    if (($cache_content) == '--false--') {
        //return false;
    }
    $cookie_name = md5($function_cache_id);


    $vc1 = 1;
    if (isset($_COOKIE[$cookie_name])) {
        $vc1 = intval($_COOKIE[$cookie_name]) + 1;
        setcookie($cookie_name, $vc1);
        //  return true;
    } elseif (!isset($_COOKIE[$cookie_name])) {
        setcookie($cookie_name, $vc1);
        // return true;
    }

    // $cache_content = false;
    if (($cache_content) != false) {

        if (isset($cache_content["visit_time"])) {
            $then = strtotime($cache_content["visit_time"]);
            $few_mins_ago = strtotime("-1 minute");


            $then_visit_date = date("Y-m-d", strtotime($cache_content["visit_date"]));
            $few_mins_ago_visit_date = date("Y-m-d");

            if ($then_visit_date == $few_mins_ago_visit_date) {
                if ($then > $few_mins_ago) {
                    return true;


                    //d($cache_content);
                } else {
                    //  exit('asdasd');
                }
            }
        }
        //return $cache_content;
    }







    $data = array();
    $data['visit_date'] = date("Y-m-d");
    $data['visit_time'] = date("H:i:s");
    //   $data['debug'] = date("H:i:s");

    $table = MODULE_DB_TABLE_USERS_ONLINE;
    $check = get("table={$table}&user_ip={$uip}&no_cache=1&one=1&limit=1&visit_date=" . $data['visit_date']);
    if ($check != false and is_array($check) and !empty($check) and isset($check['id'])) {
        $data['id'] = $check['id'];
        $vc = 0;
        if (isset($check['view_count'])) {
            //	d($check);
            $vc = ($check['view_count']);
        }

        $vc1 = 0;
        if (isset($_COOKIE[$cookie_name])) {
            $vc1 = intval($_COOKIE[$cookie_name]);
            // setcookie($cookie_name, '', 1);
        }
        $vc = $vc + 1 + $vc1;
        $data['view_count'] = $vc;
    }
    //d($data);
    if (isset($_SERVER['HTTP_REFERER'])) {
        $lp = $_SERVER['HTTP_REFERER'];
    } else {
        $lp = $_SERVER['PHP_SELF'];
    }
    $data['last_page'] = $lp;
    //$data['debug'] = $lp;

    if (!defined("FORCE_SAVE")) {
        define('FORCE_SAVE', $table);
    }



    $save = save_data($table, $data);



    cache_store_data($data, $function_cache_id, $cache_group = 'module_stats_users_online');
    return true;
    //d($save);
}

function get_visits($range = 'daily') {
    $table = MODULE_DB_TABLE_USERS_ONLINE;
    $q = false;
    $results = false;
    switch ($range) {
        case 'daily' :
            $ago = date("Y-m-d", strtotime("-1 month"));
            $q = "SELECT COUNT(*) AS unique_visits, SUM(view_count) as total_visits, visit_date FROM $table where visit_date > '$ago' group by visit_date  ";
            $results = db_query($q);

            break;

        case 'last5' :
            $q = "SELECT * FROM $table order by visit_date DESC, visit_time DESC limit 5  ";
            //d($q);
            $results = db_query($q);

            break;

        case 'users_online' :

            //$data['visit_time'] = date("H:i:s");
            $ago = date("H:i:s", strtotime("-30 minutes"));
            $ago2 = date("Y-m-d", strtotime("now"));
            $q = "SELECT COUNT(*) AS users_online FROM $table where visit_date='$ago2' and visit_time>'$ago'    ";

            $results = db_query($q);
            $results = intval($results[0]['users_online']);

            //	$q = 'SELECT COUNT(*) AS count FROM ' . $table . ' WHERE visit_date > '';
            //
			break;

        default :
            break;
    }

    if ($q == false or $results == false) {
        return false;
    }

    return $results;
}
