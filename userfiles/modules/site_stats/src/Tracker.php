<?php

namespace Microweber\SiteStats;


use Microweber\SiteStats\Models\Browsers;
use Microweber\SiteStats\Models\Log;
use Microweber\SiteStats\Models\Referrers;
use Microweber\SiteStats\Models\Sessions;
use Microweber\SiteStats\Models\Urls;


class Tracker
{

    function track()
    {
        $track = array();
        $data = $this->_collect_user_data();
        $data['updated_at'] = date("Y-m-d H:i:s");

        $track[] = $data;


        return $this->process_buffer($track);


    }

    function track_buffered()
    {


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
            $data['updated_at'] = date("Y-m-d H:i:s");
            $buffer[$buffer_key] = $data;
            cache_save($buffer, 'stats_buffer_visits', 'site_stats');
        }


        if (!$buffer_skip) {
            $this->process_buffer();
        }
    }

    function process_buffer($track_data = false)
    {

        if ($track_data != false) {
            $buffer = $track_data;
        } else {
            $buffer = cache_get('stats_buffer_visits', 'site_stats');

        }
        if (is_array($buffer) and !empty($buffer)) {

            $log = new Log();

            foreach ($buffer as $key => $item) {
                $browser_id = false;
                $language = false;

                if (isset($item['language']) and $item['language']) {
                    $language = $item['language'];
                }
                if (isset($item['browser_agent']) and $item['browser_agent']) {
                    $hash = md5($item['browser_agent']);
                    $related_data = new Browsers();
                    $related_data = $related_data->firstOrCreate([
                        'browser_agent_hash' => $hash
                    ], [
                        'browser_agent_hash' => $hash,
                        'browser_agent' => $item['browser_agent']
                    ]);
                    if ($related_data->id) {
                        $browser_id = $related_data->id;
                    }
                }


                $session_original_ref = false;
                if (isset($item['referrer']) and $item['referrer']) {
                    $hash = md5($item['referrer']);
                    $related_data = new Referrers();
                    $is_internal = false;
                    if (strstr($item['referrer'], site_url())) {
                        $is_internal = true;
                    }
                    $related_data = $related_data->firstOrCreate([
                        'referrer_hash' => $hash
                    ], [
                        'referrer_hash' => $hash,
                        'is_internal' => $is_internal,
                        'referrer' => $item['referrer']
                    ]);
                    if ($related_data->id) {
                        $item['referrer_id'] = $related_data->id;
                        if (!$is_internal) {
                            $session_original_ref = $item['referrer_id'];
                        }
                    }
                }

                if (isset($item['session_id']) and $item['session_id']
                    and isset($item['user_ip'])
                    and isset($item['user_id'])
                ) {
                    $hash = $item['session_id'];
                    $related_data = new Sessions();
                    $related_data = $related_data->firstOrCreate([
                        'session_id' => $hash
                    ], [
                        'browser_id' => $browser_id,
                        'referrer_id' => $session_original_ref,
                        'language' => $language,
                        'session_id' => $hash,
                        'user_id' => $item['user_id'],
                        'user_ip' => $item['user_ip']
                    ]);
                    if ($related_data->id) {
                        $item['session_id_key'] = $related_data->id;
                    }
                }


                if (isset($item['visit_url']) and $item['visit_url']) {
                    $hash = md5($item['visit_url']);
                    $related_data = new Urls();

                    $related_data = $related_data->firstOrCreate([
                        'url_hash' => $hash
                    ], [
                        'url_hash' => $hash,
                        'content_id' => $item['content_id'],
                        'category_id' => $item['category_id'],
                        'url' => $item['visit_url']
                    ]);
                    if ($related_data->id) {
                        $item['url_id'] = $related_data->id;
                    }
                }
                $existing = false;

                if (isset($item['url_id']) and isset($item['session_id_key'])) {


                    $existing_log = new Log();

                    $check_existing = $existing_log->where('url_id', $item['url_id'])
                        ->where('session_id_key', $item['session_id_key'])
                        ->limit(1)->first();

                    if ($check_existing and $check_existing->id) {
                        $existing = $check_existing->id;

                    }
                    if ($check_existing and isset($item['updated_at'])) {
                        $existing_log = new Log();

                        $track = array(
                            'updated_at' => $item['updated_at']
                        );
                        $existing_log->where('id', intval($existing))->increment('view_count', 1, $track);
                    } else {
                        $log->create($item);

                    }
                }
                unset($buffer[$key]);
            }

        }
        if ($track_data == false) {
            cache_save($buffer, 'stats_buffer_visits', 'site_stats');
        }

    }


    private function _collect_user_data()
    {

        $data = array();
        $data['user_ip'] = user_ip();
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $data['browser_agent'] = $_SERVER['HTTP_USER_AGENT'];
        }
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            $data['language'] = $lang;
        }


        $last_page = url_current(true);

        $ref = false;
        if ($last_page == false) {
            $last_page = $_SERVER['PHP_SELF'];


        }

        if (isset($_SERVER['HTTP_REFERER'])) {
            $ref = $_SERVER['HTTP_REFERER'];
        }

        if (is_ajax()) {
            if (isset($_POST['referrer'])) {
                $ref = $_POST['referrer'];
            }
        }


        if ($last_page) {
            $last_page = rtrim($last_page, '?');
            $last_page = rtrim($last_page, '#');
        }
        $data['visit_url'] = $last_page;
        $data['referrer'] = $ref;
        $data['session_id'] = mw()->user_manager->session_id();
        $data['user_id'] = mw()->user_manager->id();
        $data['content_id'] = content_id();
        $data['category_id'] = category_id();
 
        return $data;
    }


}
