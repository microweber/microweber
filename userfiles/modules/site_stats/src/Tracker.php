<?php

namespace Microweber\SiteStats;

use Microweber\App\Providers\Illuminate\Support\Facades\DB;


class Tracker
{


    function track()
    {

        if (!isset($_SERVER['HTTP_USER_AGENT'])) {
            return;
        }
        $buffer = cache_get('stats_buffer_visits', 'site_stats');
        $buffer_skip = cache_get('stats_buffer_timeout', 'site_stats');
        if (!$buffer_skip) {
            cache_save('skip', 'stats_buffer_timeout', 'site_stats', 1);
        }
        if (!is_array($buffer)) {
            $buffer = array();
        }


        $data = $this->_collect_user_data();
        $buffer_key = 'stat' . crc32($data['referrer'] . $data['session_id']);


        if (!isset($buffer[$buffer_key])) {
            $data['visit_date'] = date("Y-m-d");
            $data['visit_time'] = date("H:i:s");
            $buffer[$buffer_key] = $data;
            cache_save($buffer, 'stats_buffer_visits', 'site_stats');
        }


        return;


        // REMOVE ME !!!!!!
        // REMOVE ME !!!!!!
        // REMOVE ME !!!!!!
        // REMOVE ME !!!!!!
        // REMOVE ME !!!!!!
        // REMOVE ME !!!!!!
        // REMOVE ME !!!!!!
        // REMOVE ME !!!!!!
        // REMOVE ME !!!!!!
        // REMOVE ME !!!!!!
        // REMOVE ME !!!!!!
        // REMOVE ME !!!!!!
        // REMOVE ME !!!!!!
        // REMOVE ME !!!!!!
        // REMOVE ME !!!!!!
        // REMOVE ME !!!!!!
        $this->process_buffer();

        // REMOVE ME !!!!!!
        // REMOVE ME !!!!!!
        // REMOVE ME !!!!!!
        // REMOVE ME !!!!!!
        // REMOVE ME !!!!!!
        // REMOVE ME !!!!!!
        // REMOVE ME !!!!!!
        // REMOVE ME !!!!!!
        // REMOVE ME !!!!!!
        // REMOVE ME !!!!!!
        // REMOVE ME !!!!!!
        // REMOVE ME !!!!!!
        // REMOVE ME !!!!!!
        // REMOVE ME !!!!!!
        // REMOVE ME !!!!!!
        // REMOVE ME !!!!!!

        if (!$buffer_skip) {
            $this->process_buffer();
        }
    }

    function process_buffer()
    {
        $buffer = cache_get('stats_buffer_visits', 'site_stats');
        if ($buffer) {
            $data_to_save_bulk = array();
            foreach ($buffer as $key => $item) {

                $this->_track_visit($item);
                $this->_track_pageview($item);
                unset($buffer[$key]);
            }

        }

        cache_save($buffer, 'stats_buffer_visits', 'site_stats');

    }


    private function _collect_user_data()
    {

        $data = array();
        $data['user_ip'] = user_ip();
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        }


        $last_page = url_current(true);

        $ref = false;
        if ($last_page == false) {
            $last_page = $_SERVER['PHP_SELF'];


            if (isset($_SERVER['HTTP_REFERER'])) {
                $ref = $_SERVER['HTTP_REFERER'];
            }
        }

        $data['last_page'] = $last_page;
        $data['referrer'] = $ref;
        $data['session_id'] = mw()->user_manager->session_id();
        $data['user_id'] = mw()->user_manager->id();
        $data['content_id'] = content_id();
        $data['category_id'] = category_id();

        return $data;
    }


    private function _track_visit($visit_data)
    {


        $uip = $visit_data['user_ip'];
        $lp = $visit_data['referrer'];
        $ref = $visit_data['referrer'];
        $visit_date = $visit_data['visit_date'];
        $visit_time = $visit_data['visit_time'];


        $data = array();
        $data['visit_date'] = $visit_date;
        $data['visit_time'] = $visit_time;
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
            $vc = $vc + $vc1;
            $data['view_count'] = $vc;
        }
        $data['last_page'] = $lp;
        $data['referrer'] = $ref;
        $save = mw()->database_manager->save($table, $data);
        return $save;
    }


    private function _track_pageview($visit_data)
    {


        if (!get_option('track_pageviews', 'stats')) {
            //  return;
        }


        $content_id = $visit_data['content_id'];
        if ($content_id != 0) {
            $visit_date = $visit_data['visit_date'];
            $visit_time = $visit_data['visit_time'];


            $updated_at = date("Y-m-d H:i:s");

            $existing = DB::table('stats_pageviews')
                ->where('page_id', $content_id)
                ->where('visit_date', $visit_date)
                ->limit(1)->first();

            if ($existing) {

                dd($existing);
                $track = array(
                    'updated_at' => $visit_date

                );
                if (isset($visit_data['category_id']) and $visit_data['category_id']) {
                    $track['category_id'] = $visit_data['category_id'];
                }
                DB::table('stats_pageviews')->where('id', intval($existing))->increment('view_count', 1, $track);
            } else {
                DB::table('stats_pageviews')->insert(
                    [
                        'page_id' => $content_id,
                        'updated_at' => $updated_at,
                        'visit_date' => $visit_date,
                        'view_count' => 1
                    ]
                );
            }
        }
    }


//
//    private function ____OLD_track_visit()
//    {
//
//
//        if (!isset($_SERVER['HTTP_USER_AGENT']) or stristr($_SERVER['HTTP_USER_AGENT'], 'bot')) {
//            return;
//        }
//
//        $function_cache_id = false;
//        $uip = $_SERVER['REMOTE_ADDR'];
//        $function_cache_id = $function_cache_id . $uip . MW_USER_IP;
//
//        $function_cache_id = __FUNCTION__ . crc32($function_cache_id);
//        $few_mins_ago_visit_date = date("Y-m-d H:i:s");
//
//        $cookie_name = 'mw-stats' . crc32($function_cache_id);
//        $cookie_name_time = 'mw-time' . crc32($function_cache_id);
//
//        $vc1 = 1;
//        if (mw()->user_manager->session_get($cookie_name)) {
//            $vc1 = intval(mw()->user_manager->session_get($cookie_name)) + 1;
//            mw()->user_manager->session_set($cookie_name, $vc1);
//
//        } elseif (!mw()->user_manager->session_get($cookie_name)) {
//            mw()->user_manager->session_set($cookie_name, $vc1);
//        }
//
//
//        if (!isset($_COOKIE[$cookie_name_time])) {
//            if (!headers_sent()) {
//                setcookie($cookie_name_time, $few_mins_ago_visit_date, time() + 30);
//            }
//
//
//            $data = array();
//            $data['visit_date'] = date("Y-m-d");
//            $data['visit_time'] = date("H:i:s");
//            $data['user_ip'] = $uip;
//
//            $table = MODULE_DB_USERS_ONLINE;
//            $check = db_get("table={$table}&user_ip={$uip}&one=1&limit=1&visit_date=" . $data['visit_date']);
//
//            if ($check != false and is_array($check) and !empty($check) and isset($check['id'])) {
//
//                $data['id'] = $check['id'];
//                $vc = 0;
//                if (isset($check['view_count'])) {
//                    $vc = ($check['view_count']);
//                }
//
//                $vc1 = 0;
//                if (mw()->user_manager->session_get($cookie_name)) {
//                    $vc1 = intval(mw()->user_manager->session_get($cookie_name));
//                }
//                $vc = $vc + $vc1;
//                $data['view_count'] = $vc;
//            }
//            $lp = url_current(true);
//
//            if ($lp == false) {
//                if (isset($_SERVER['HTTP_REFERER'])) {
//                    $lp = $_SERVER['HTTP_REFERER'];
//                } else {
//                    $lp = $_SERVER['PHP_SELF'];
//                }
//            }
//
//            $data['last_page'] = $lp;
//
//
//            $save = mw()->database_manager->save($table, $data);
//            mw()->user_manager->session_set($cookie_name, 0);
//
//
//        }
//
//        return true;
//    }
//
//
//    private function ____OLD_track_pageview()
//    {
//        if (!get_option('track_pageviews', 'stats')) {
//            return;
//        }
//
//
//        if (defined('CONTENT_ID') and CONTENT_ID != 0) {
//            $visit_date = date("Y-m-d H:i:s");
//            $existing = DB::table('stats_pageviews')->where('page_id', CONTENT_ID)->take(1)->pluck('id');
//            if ($existing) {
//                $track = array('updated_at' => $visit_date);
//                if (defined('MAIN_PAGE_ID')) {
//                    $track['main_page_id'] = MAIN_PAGE_ID;
//                }
//
//                if (defined('PARENT_PAGE_ID')) {
//                    $track['parent_page_id'] = PARENT_PAGE_ID;
//                }
//                DB::table('stats_pageviews')->where('id', intval($existing))->increment('view_count', 1, $track);
//            } else {
//                DB::table('stats_pageviews')->insert(
//                    ['page_id' => CONTENT_ID, 'updated_at' => $visit_date, 'view_count' => 1]
//                );
//            }
//
//        }
//    }


}
