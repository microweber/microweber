<?php

namespace MicroweberPackages\Modules\SiteStats;


use MicroweberPackages\Modules\SiteStats\Models\Browsers;
use MicroweberPackages\Modules\SiteStats\Models\Geoip;
use MicroweberPackages\Modules\SiteStats\Models\Log;
use MicroweberPackages\Modules\SiteStats\Models\Referrers;
use MicroweberPackages\Modules\SiteStats\Models\ReferrersDomains;
use MicroweberPackages\Modules\SiteStats\Models\ReferrersPaths;
use MicroweberPackages\Modules\SiteStats\Models\Sessions;
use MicroweberPackages\Modules\SiteStats\Models\StatsUrl;
use Jenssegers\Agent\Agent;
use GeoIp2\Database\Reader;


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
            return $this->process_buffer();
        }
        return true;
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

                $item = array_map('strip_tags', $item);
                $item = array_map('e', $item);
                 if (isset($item['language']) and $item['language']) {
                    $language = $item['language'];
                }
                if (isset($item['browser_agent']) and $item['browser_agent']) {
                    $hash = md5($item['browser_agent']);

                    $browser_data = array(
                        'browser_agent_hash' => $hash,
                        'browser_agent' => $item['browser_agent']
                    );


                    $related_data = new Browsers();
                    $related_data = $related_data->firstOrCreate([
                        'browser_agent_hash' => $hash
                    ], array_merge($browser_data, $this->_parse_agent($item['browser_agent'])));
                    if ($related_data->id) {
                        $browser_id = $related_data->id;
                    }
                }


                $session_original_ref_id = false;
                $ref = false;
                if (isset($item['referrer']) and $item['referrer']) {
                    $hash = md5($item['referrer']);
                    $ref = $item['referrer'];
                    $related_data = new Referrers();
                    $is_internal = false;
                    if (strstr($item['referrer'], site_url())) {
                        $is_internal = true;
                    }
                    $related_data = $related_data->firstOrCreate([
                        'referrer_hash' => $hash
                    ], [
                        'referrer_hash' => $hash,
                        'referrer_domain_id' => $this->_referrer_domain_id($ref),
                        'referrer_path_id' => $this->_referrer_path_id($ref),
                        'is_internal' => $is_internal,
                        'referrer' => $ref
                    ]);
                    if ($related_data->id) {
                        $item['referrer_id'] = $related_data->id;
                        // if (!$is_internal) {
                        $session_original_ref_id = $item['referrer_id'];
                        // }
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
                        'referrer_id' => $session_original_ref_id,
                        'language' => $language,
                        'session_id' => $hash,
                        'geoip_id' => $this->_geo_ip_id($item['user_ip']),
                        'referrer_domain_id' => $this->_referrer_domain_id($ref),
                        'referrer_path_id' => $this->_referrer_path_id($ref),
                        'user_id' => $item['user_id'],
                        'user_ip' => $item['user_ip']
                    ]);
                    if ($related_data->id) {
                        $item['session_id_key'] = $related_data->id;

                    }
                }

                if (isset($item['visit_url']) and $item['visit_url']) {
                    $hash = md5($item['visit_url']);
                    $related_data = new StatsUrl();

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
                        $existing_log = $check_existing;

                       // $existing_log->where('id', intval($existing));
                      //  $existing_log->where('id', intval($existing))->increment('view_count', 1, $track);
                        $view_count_log =  $check_existing;
                        $view_count = 0;

                        if($view_count_log){
                            $view_count  =intval( $existing_log->view_count);
                        }

                        $view_count = intval($view_count) + 1;

                        $view_count_log->updated_at = $item['updated_at'];
                        $view_count_log->view_count = $view_count;
                        $view_count_log->save();



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
        return true;
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
        $ref = e($ref);

        if ($last_page) {
            $last_page = e($last_page);

            $last_page = rtrim($last_page, '?');
            $last_page = rtrim($last_page, '#');
        }

        if (strstr($ref, admin_url())) {
            return;
        }

        $data['visit_url'] = $last_page;
        $data['referrer'] = $ref;
        $data['session_id'] = mw()->user_manager->session_id();
        $data['user_id'] = mw()->user_manager->id();
        $data['content_id'] = content_id();
        $data['category_id'] = category_id();

        return $data;
    }


    private function _parse_agent($browser_agent_string)
    {

        $return = array();
        $agent = new Agent();
        $agent->setUserAgent($browser_agent_string);


        $platform = $agent->platform();
        $version = $agent->version($platform);


        $return['platform'] = $platform;
        $return['platform_version'] = $version;


        $browser = $agent->browser();
        $version = $agent->version($browser);

        $return['browser'] = $browser;
        $return['browser_version'] = $version;


        $return['is_desktop'] = $agent->isDesktop();
        $return['is_phone'] = $agent->isPhone();
        $return['is_mobile'] = $agent->isMobile();
        $return['is_tablet'] = $agent->isTablet();


        $return['browser_version'] = $version;
        $return['browser_version'] = $version;

        $return['device'] = $agent->device();


        $langs = $agent->languages();
        if ($langs and !empty($langs)) {
            $return['language'] = array_pop($langs);
        }


        $is_robot = $agent->isRobot();
        if ($is_robot) {
            $return['is_robot'] = $is_robot;
            $return['robot_name'] = $agent->robot();
        }
        return $return;

    }

    private function _geo_ip_id($ip)
    {
        $ip = $this->__parse_geo_ip_country($ip);

        if ($ip and isset($ip['country_code'])) {
            $data = new Geoip();
            $data = $data->firstOrCreate([
                'country_code' => $ip['country_code']
            ], $ip);

            if ($data->id) {
                return $data->id;
            }


        }
    }


    private function _referrer_domain_id($referrer_url = false)
    {

        if (!$referrer_url) {
            return;
        }

        $parse = parse_url($referrer_url);

        if (isset($parse['host']) and $parse['host']) {

            $domain = $parse['host']; //referrer_domain
            if ($domain) {
                $data = new ReferrersDomains();
                $data = $data->firstOrCreate([
                    'referrer_domain' => $domain
                ], array('referrer_domain' => $domain));

                if ($data->id) {
                    return $data->id;
                }


            }
        }
    }


    private function _referrer_path_id($referrer_url = false)
    {

        if (!$referrer_url) {
            return;
        }

        $parse = parse_url($referrer_url);

        if (isset($parse['host']) and $parse['host']) {
            if (isset($parse['path']) and $parse['path']) {

                $domain = $parse['host'];
                $path = $parse['path'];
                $domain_id = $this->_referrer_domain_id($referrer_url);
                if ($domain_id) {
                    $data = new ReferrersPaths();
                    $data = $data->firstOrCreate([
                        'referrer_path' => $path,
                        'referrer_domain_id' => $domain_id
                    ], array('referrer_domain_id' => $domain_id, 'referrer_path' => $path));

                    if ($data->id) {
                        return $data->id;
                    }


                }
            }
        }
    }


    private function __parse_geo_ip_country($ip)
    {

        $return = array();
        $return['country_name'] = 'unknown';
        $return['country_code'] = 'unknown';

        if (filter_var($ip, FILTER_VALIDATE_IP) === false) {
            return $return;
        }

        $mmdb = normalize_path(dirname(MW_PATH) . 'Utils/ThirdPartyLibs/geoip_lite/GeoLite2-Country.mmdb', false);

        if (is_file($mmdb)) {

            try {
                $reader = new Reader($mmdb);
                $record = $reader->country($ip);
                 if ($record) {
                    $return['country_code'] = $record->country->isoCode;
                    $return['country_name'] = $record->country->name;
                }
                unset($reader);
            } catch (\Exception $e) {

            }


        }

        return $return;
    }


}
