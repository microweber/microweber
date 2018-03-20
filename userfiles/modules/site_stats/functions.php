<?php

require_once(__DIR__ . DS . 'vendor' . DS . 'autoload.php');


if (!defined("MODULE_DB_USERS_ONLINE")) {
    define('MODULE_DB_USERS_ONLINE', 'stats_users_online');
}
if (!defined('MW_USER_IP')) {
    if (isset($_SERVER["REMOTE_ADDR"])) {
        define("MW_USER_IP", $_SERVER["REMOTE_ADDR"]);
    } else {
        define("MW_USER_IP", '127.0.0.1');

    }
}


event_bind('mw.admin.dashboard.content', function ($params = false) {
    return mw_print_stats_on_dashboard($params);
});


event_bind('mw_admin_quick_stats_by_session', function ($params = false) {
    return mw_print_quick_stats_by_session($params);
});
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
    print '  <module type="site_stats/admin" />';
    //print '<microweber module="site_stats" view="admin" />';
}

//
//event_bind('mw.pageviewffffff', function ($params = false) {
//
//
//        if (defined('MW_FRONTEND') and !isset($_REQUEST['isolate_content_field'])
//            and !is_ajax()
//            and !is_cli()
//
//        ) {
//
//
//
//            $tracker = new Microweber\SiteStats\Tracker(); ;
//            $tracker->track();
//           // $tracker->track_visit();
//          //  $tracker->track_pageview();
//
//        }
//
//});

template_foot(function ($page) {

    if(get_option('stats_disabled', 'site_stats') == 1){
        return;
    }



    //$track_src = modules_url().'site_stats/track.js';
    //$link = '<script type="text/javascript" src="'.$track_src.'"></script>';

    $track_url = api_url('pingstats');

    $link = '<script defer type="text/javascript">
    $( document ).ready(function() {
           setTimeout(function(){
            var track = { _token :"' . csrf_token() . '", referrer : document.referrer }
            $.ajax({
                url: "' . $track_url . '",
                data: track,
                type: "POST",
                dataType: "json"
            });
            }, 1337);
     });
    </script>';
    return $link;

});

api_expose('pingstats', function ($params = false) {

    if(get_option('stats_disabled', 'site_stats') == 1){
        return;
    }

    if (!is_ajax()) {
        return;
    }
    if (!isset($_POST['_token'])) {
        return;
    }
    if (isset($_POST['_token'])) {
        $validate = mw()->user_manager->csrf_validate($_POST);
        if (!$validate) {
            return;
        }
    }



    $tracker = new Microweber\SiteStats\Tracker();

    if(get_option('stats_is_buffered', 'site_stats') == 1){
         return $tracker->track_buffered();
    } else {
        return $tracker->track();
    }





});


function mw_stats_track_pageview()
{
    if (!get_option('track_pageviews', 'stats')) {
        return;
    }


    if (defined('CONTENT_ID') and CONTENT_ID != 0) {
        $visit_date = date("Y-m-d H:i:s");
        $existing = DB::table('stats_pageviews')->where('page_id', CONTENT_ID)->take(1)->pluck('id');
        if ($existing) {
            $track = array('updated_at' => $visit_date);
            if (defined('MAIN_PAGE_ID')) {
                $track['main_page_id'] = MAIN_PAGE_ID;
            }

            if (defined('PARENT_PAGE_ID')) {
                $track['parent_page_id'] = PARENT_PAGE_ID;
            }
            DB::table('stats_pageviews')->where('id', intval($existing))->increment('view_count', 1, $track);
        } else {
            DB::table('stats_pageviews')->insert(
                ['page_id' => CONTENT_ID, 'updated_at' => $visit_date, 'view_count' => 1]
            );
        }

    }
}

function mw_stats_track_visit()
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
    if (mw()->user_manager->session_get($cookie_name)) {
        $vc1 = intval(mw()->user_manager->session_get($cookie_name)) + 1;
        mw()->user_manager->session_set($cookie_name, $vc1);

    } elseif (!mw()->user_manager->session_get($cookie_name)) {
        mw()->user_manager->session_set($cookie_name, $vc1);
    }


    if (!isset($_COOKIE[$cookie_name_time])) {
        if (!headers_sent()) {
            setcookie($cookie_name_time, $few_mins_ago_visit_date, time() + 30);
        }


        $data = array();
        $data['visit_date'] = date("Y-m-d");
        $data['visit_time'] = date("H:i:s");
        $data['user_ip'] = $uip;

        $table = MODULE_DB_USERS_ONLINE;
        $check = db_get("table={$table}&user_ip={$uip}&one=1&limit=1&visit_date=" . $data['visit_date']);

        if ($check != false and is_array($check) and !empty($check) and isset($check['id'])) {

            $data['id'] = $check['id'];
            $vc = 0;
            if (isset($check['view_count'])) {
                $vc = ($check['view_count']);
            }

            $vc1 = 0;
            if (mw()->user_manager->session_get($cookie_name)) {
                $vc1 = intval(mw()->user_manager->session_get($cookie_name));
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
        //$data['skip_cache'] = 1;


        $save = mw()->database_manager->save($table, $data);
        mw()->user_manager->session_set($cookie_name, 0);


    }

    return true;

}


function stats_insert_cookie_based()
{

    $function_cache_id = false;
    $uip = $_SERVER['REMOTE_ADDR'];
    $function_cache_id = $function_cache_id . $uip . MW_USER_IP;


    $cookie_name = 'mw-stats' . crc32($function_cache_id);
    $cookie_name_time = 'mw-time' . crc32($function_cache_id);

    $vc1 = 1;


    $few_mins_ago_visit_date = date("Y-m-d H:i:s");
    if (isset($_COOKIE[$cookie_name])) {
        $vc1 = intval($_COOKIE[$cookie_name]) + 1;
        //	mw()->user_manager->session_get($cookie_name) = $vc1;
        setcookie($cookie_name, $vc1, time() + 99);
        //  return true;
    } elseif (!isset($_COOKIE[$cookie_name])) {
        setcookie($cookie_name, $vc1, time() + 99);
        //mw()->user_manager->session_get($cookie_name) = $vc1;
        // return true;
    }

    if (!isset($_COOKIE[$cookie_name_time])) {
        setcookie($cookie_name_time, $few_mins_ago_visit_date, time() + 90);
        $data = array();
        $data['visit_date'] = date("Y-m-d", strtotime("now"));
        $data['visit_time'] = date("H:i:s", strtotime("now"));
        $table = MODULE_DB_USERS_ONLINE;
        $check = db_get("no_cache=1&table={$table}&user_ip={$uip}&one=1&limit=1&visit_date=" . $data['visit_date']);
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
        if (mw()->user_manager->session_id() and !(mw()->user_manager->session_all() == false)) {
            $data['session_id'] = mw()->user_manager->session_id();
        }
        mw_var('FORCE_SAVE', $table);
        mw_var('apc_no_clear', 1);
        $save = mw()->database_manager->save($table, $data);
        setcookie($cookie_name, 0, time() + 99);


    }

    return true;

}

function get_visits_for_sid($sid)
{
    return;

    $table = MODULE_DB_USERS_ONLINE;
    $q = false;
    $results = false;
    $data = array();
    $data['table'] = $table;
    $data['session_id'] = $sid;
    $data['limit'] = 10;
    $data['order_by'] = "visit_date desc,visit_time desc";

    return db_get($data);


}

function stats_group_by($rows, $format)
{
    $results = array();
    foreach ($rows as $row) {
        if (isset($row->visit_date)) {
            $group = Carbon::parse($row->visit_date)->format($format);
            $results[$group] = $row;
        }
    }

    return $results;
}

function get_visits($range = 'daily')
{
    $table = MODULE_DB_USERS_ONLINE;
    $table_real = mw()->database_manager->real_table_name($table);
    $q = false;
    $results = false;
    switch ($range) {
        case 'daily' :
            $ago = date("Y-m-d", strtotime("-1 month"));
            $results = DB::table($table)
                ->select('visit_date', DB::raw('count(id) as unique_visits, sum(view_count) as total_visits'))
                ->where('visit_date', '>', $ago)
                ->groupBy('id')
                ->get();

            if ($results) {
                $results = $results->toArray();
            }

            break;

        case 'weekly' :

            $ago = date("Y-m-d", strtotime("-1 week"));
            $rows = DB::table($table)
                ->select('visit_date', DB::raw('count(id) as unique_visits, sum(view_count) as total_visits'))
                ->where('visit_date', '>', $ago)
                ->groupBy('id')
                ->get();
            if ($results) {
                $results = $results->toArray();
            }
            $results = stats_group_by($rows, 'W');


            break;

        case 'monthly' :
            $ago = date("Y-m-d", strtotime("-1 year"));
            $rows = DB::table($table)
                ->select('visit_date', DB::raw('count(id) as unique_visits, sum(view_count) as total_visits'))
                ->where('visit_date', '>', $ago)
                ->groupBy('id')
                ->get();
            if ($results) {
                $results = $results->toArray();
            }

            $results = stats_group_by($rows, 'm');
            break;

        case 'last5' :
            $results = DB::table($table)
                ->orderBy('visit_date', 'desc')
                ->orderBy('visit_time', 'desc')
                ->take(5)
                ->get();

            if ($results) {
                $results = $results->toArray();
            }


            break;

        case 'requests_num' :
            $ago = date("H:i:s", strtotime("-1 minute"));
            $ago2 = date("Y-m-d", strtotime("now"));
            $total = 0;
            $q = "SELECT SUM(view_count) AS total_visits FROM $table_real  WHERE visit_date='$ago2' AND visit_time>'$ago'   ";
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
            $q = "SELECT COUNT(*) AS users_online FROM $table_real WHERE visit_date='$ago2' AND visit_time>'$ago'    ";

            $results = mw()->database_manager->query($q);
            if (is_array($results)) {
                $results = intval($results[0]['users_online']);
            }
            break;

        default :
            break;
    }

    if ($results == false) {
        return false;
    }
    $url = site_url();
    $res = array();
    if (is_array($results)) {
        foreach ($results as &$item) {

            if (is_object($item)) {
                $item = (array)$item;
            }
            if (isset($item['last_page'])) {
                $item['last_page'] = str_replace($url, '', $item['last_page']);
            }
            $res[] = $item;
        }

        return $res;
    }

    return $results;
}
