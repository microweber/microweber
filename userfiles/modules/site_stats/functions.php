<?php

use MicroweberPackages\Modules\SiteStats\UtmVisitorData;

require_once(__DIR__ . DS . 'vendor' . DS . 'autoload.php');


api_expose('stattstrack', function () {


});

if (!defined("MODULE_DB_USERS_ONLINE")) {
    define('MODULE_DB_USERS_ONLINE', 'stats_users_online');
}


event_bind('mw.admin.dashboard.content', function ($params = false) {
    print '<div type="site_stats/admin" class="mw-lazy-load-module" id="site_stats_admin"></div>';


});

/*
 * event_bind('mw.admin.settings.seo', function ($params = false) {
    if(user_can_access('site_stats.settings')) {
        print '<module type="site_stats/tracking_settings" id="site_stats_tracking_settings" />';
    }
});*/


event_bind('mw_admin_quick_stats_by_session', function ($params = false) {
    return mw_print_quick_stats_by_session($params);
});
function mw_print_quick_stats_by_session($sid = false)
{

    print '<module type="site_stats/admin" view="quick_stats_by_session" class="mw-site-stats-quick-view-table" data-subtype="quick" data-user-sid="' . $sid . '" />';
}


event_bind('mw.pageview', function ($params = false) {
    template_foot(function () {
        if (get_option('stats_disabled', 'site_stats') == 1) {
            return;
        }

        $enableEvents = get_option('google-tag-manager-enable-events', 'website') == 1;
        $hasGoogleAnalytics = get_option('google-analytics-id', 'website') ;

//        $src_code = '$(document).ready(function () {
//            setTimeout(function () {
//                 var mwtrackpageview = document.createElement(\'script\'); mwtrackpageview.type = \'text/javascript\'; mwtrackpageview.async = true; mwtrackpageview.defer = true;
//                  mwtrackpageview.src = "' . api_url('pingstats') . '";
//                  var s = document.getElementsByTagName(\'head\')[0]; s.parentNode.insertBefore(mwtrackpageview, s);
//            }, 3337);
//        });';
//
//        $src = '<script defer>' . $src_code . '</script>';
//
//
//        return $src;


        if(is_admin()){
            return;
        }

        $src_code = '';
        $ping_js = userfiles_path() . 'modules/site_stats/ping.js';
        if (is_file($ping_js)) {
            $src_code = file_get_contents($ping_js);
        }

        $src = '<script async>' . $src_code . '</script>';


        $compile_assets = \Config::get('microweber.compile_assets');   //$compile_assets =  \Config::get('microweber.compile_assets');
        if ($compile_assets and defined('MW_VERSION')) {
            $userfiles_dir = userfiles_path();
            $userfiles_cache_dir = normalize_path($userfiles_dir . 'cache' . DS . 'apijs' . DS);
            $hash = crc32(site_url() . $src_code);
            $userfiles_cache_filename = $userfiles_cache_dir . 'ping.' . $hash . '.' . MW_VERSION . '.js';
            if (!is_file($userfiles_cache_filename)) {
                if (!is_dir(userfiles_url() . 'cache/apijs/')) {
                    @mkdir_recursive(userfiles_url() . 'cache/apijs/');
                }
                @file_put_contents($userfiles_cache_filename, $src_code);
            }

            if (is_file($userfiles_cache_filename)) {
                $traker_url = userfiles_url() . 'cache/apijs/' . 'ping.' . $hash . '.' . MW_VERSION . '.js';
                $src = '<script async defer type="text/javascript" src="' . $traker_url . '"></script>';
            }

        }

//        return   '<script defer async src="data:text/javascript,
//
//$(document).ready(function () {
//            setTimeout(function () {
//                var track = {referrer: document.referrer}
//                $.ajax({
//                    url: mw.settings.api_url+\'pingstats\',
//                    data: track,
//                    type: \'POST\',
//                    dataType: \'json\'
//                });
//            }, 1337);
//        });
//
//">';

        //$traker_url = modules_url() . 'site_stats/ping.js';
        // return '<script async type="text/javascript" src="' . $traker_url . '"></script>';

        if($hasGoogleAnalytics and $enableEvents){
            $traker_url = modules_url() . 'site_stats/gtmEventListener.js';
            $src .= '<script async type="text/javascript" src="' . $traker_url . '"></script>';
        }

        return $src;

    });
});


//
//event_bind('mw.csrf.ajax_request', function ($params = false) {
//    $to_track = false;
//    if (get_option('stats_disabled', 'site_stats') == 1) {
//        return;
//    }
//
//
//    if (isset($_SERVER['HTTP_REFERER'])) {
//        $ref_page = $_SERVER['HTTP_REFERER'];
//        if (stristr(site_url(), $ref_page)) {
//            if (is_ajax()) {
//                $to_track = true;
//            }
//        }
//    }
//
//    if (!$to_track) {
//        return;
//    }
//
//    $tracker = new MicroweberPackages\Modules\SiteStats\Tracker();
//
//    if (get_option('stats_is_buffered', 'site_stats') == 1) {
//        return $tracker->track_buffered();
//    } else {
//        return $tracker->track();
//    }
//
//
//});

api_expose('pingstats', function ($params = false) {

    $to_track = false;
//    if (get_option('stats_disabled', 'site_stats') == 1) {
//        return;
//    }

    if (isset($_SERVER['HTTP_REFERER'])) {
        $ref_page = $_SERVER['HTTP_REFERER'];
        //if (stristr(site_url(), $ref_page)) {
        if (starts_with($ref_page, site_url())) {
             if (is_ajax()) {
            $to_track = true;
             }
        }
    }
    if (!$to_track) {
        return;
    }
    $tracker = new MicroweberPackages\Modules\SiteStats\Tracker();

    if (get_option('stats_is_buffered', 'site_stats') == 1) {
        $tracker->track_buffered();
    } else {
        $tracker->track();
    }

    // Decode referrer and save UTM in cookie
    $referer = request()->headers->get('referer');
    $refererParse = parse_url($referer);
    if (!empty($refererParse)) {
        $refererQuery = [];
        if (isset($refererParse['query']) && !empty($refererParse['query'])) {
            parse_str($refererParse['query'], $refererQuery);
        }

        if (!empty($refererQuery)) {
            foreach ($refererQuery as $refererQueryKey => $refererQueryValue) {
                if (in_array($refererQueryKey, ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'])) {
                    set_cookie($refererQueryKey, $refererQueryValue);
                }
            }
        }
    }

    if (!isset($_COOKIE['_mw_stats_visitor_data'])) {
        UtmVisitorData::setVisitorData([
            'utm_visitor_id' => md5(rand(100000000, 999999999).time()),
        ]);
    }

    event(new \MicroweberPackages\Modules\SiteStats\Events\PingStatsEvent([
        'referer'=>$referer,
    ]));

    $pingStatsViewResponse = "var mwpingstats={}; \n";

    $overwriteResponse = mw()->event_manager->trigger('mw.pingstats.response');
    if (!empty($overwriteResponse)) {
        foreach ($overwriteResponse as $response) {
            $pingStatsViewResponse .= $response . "\n";
        }
    }

    $response = response($pingStatsViewResponse);

    $response->header('Pragma', 'no-cache');
    $response->header('Content-Type', 'text/javascript');
    $response->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    $response->header('Cache-Control', 'no-cache, must-revalidate, no-store, max-age=0, private');

    return $response;


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
    $function_cache_id = $function_cache_id . $uip . user_ip();

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
    $function_cache_id = $function_cache_id . $uip . user_ip();


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


function stats_get_views_count_for_content($content_id = 0)
{
    return (new \MicroweberPackages\Modules\SiteStats\Models\ContentViewCounter)->getCountViewsForContent($content_id);
}
