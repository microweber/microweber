<?php

namespace MicroweberPackages\SiteStats;

use MicroweberPackages\SiteStats\Models\Browsers;
use MicroweberPackages\SiteStats\Models\Comments;
use MicroweberPackages\SiteStats\Models\Log;
use MicroweberPackages\SiteStats\Models\Orders;
use MicroweberPackages\SiteStats\Models\Referrers;
use MicroweberPackages\SiteStats\Models\ReferrersDomains;
use MicroweberPackages\SiteStats\Models\ReferrersPaths;
use MicroweberPackages\SiteStats\Models\Sessions;
use MicroweberPackages\SiteStats\Models\StatsUrl;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Stats
{

    public $cache_exp = 5;
    public $cache = true;

    function get_stats_items($params)
    {


        if (!is_array($params)) {
            $params = parse_params($params);
        }

        $period = 'daily';
        if (isset($params['period'])) {
            $period = $params['period'];
        }
        $engine = mw()->database_manager->get_sql_engine();

        $site_url = site_url();

        $return = 'visitors_list';
        if (isset($params['return'])) {
            $return = $params['return'];
        }
        $orig_return = $return;

        $cache_name = 'period_' . $params['period'] . '_' . $return;
        if ($this->cache) {
            $cache_get = cache_get($cache_name, 'site_stats');
            if ($cache_get) {
                return $cache_get;
            }
        }

        switch ($return) {

            case 'content_list':

                $return = array();
                $log = new Log();
                $log = $log->period($period, 'stats_visits_log');

                if (isset($params['only_for_session_id'])) {
                    $sid = $params['only_for_session_id'];
                    $log = $log->join('stats_sessions', function ($join) use ($sid) {
                        $join->on('stats_sessions.id', '=', 'stats_visits_log.session_id_key');


                    });

                    $log = $log->where('stats_sessions.session_id', $sid);

                }

//
//                INNER JOIN
//            (
//                SELECT customer_id,
//          SUM(payment.amount) AS SumPayment
//   GROUP BY customer_id
//   FROM payment
// ) AS payment

//                ->join(DB::raw('(SELECT user_id, COUNT(user_id) TotalCatch,
//               DATEDIFF(NOW(), MIN(created_at)) Days,
//               COUNT(user_id)/DATEDIFF(NOW(), MIN(created_at))
//               CatchesPerDay FROM `catch-text` GROUP BY user_id)
//               TotalCatches'),

                $log = $log->join('stats_urls', function ($join) use ($log) {

                    $join->on('stats_visits_log.url_id', '=', 'stats_urls.id');

                    // $log = $join->select('stats_visits_log.url_id as url_id')->where('url_id', '=', 'stats_urls.id');


                });

                $log = $log->select('stats_visits_log.*',
                    //      'stats_referrers.referrer',
                    'stats_urls.url as url',
                    'stats_urls.content_id as content_id',
                    'stats_urls.category_id as category_id',
                    DB::raw('sum(view_count) as view_count_sum'),
                    DB::raw('count(session_id_key) as sessions_count')


                );

                //  $log = $log->orderBy('stats_visits_log.updated_at', 'desc');
                //  $log = $log->orderBy('view_count_sum', 'desc');
                $log = $log->orderBy('sessions_count', 'desc');

                $log = $log->orderBy('stats_visits_log.updated_at', 'desc');


                if ($engine == 'pgsql') {

                    $log = $log->groupBy('url_id', 'stats_visits_log.id', 'url', 'content_id', 'category_id');
                } else {
                    $log = $log->groupBy('url_id');

                }
                $data = $log->get();

                if (!$data) {
                    return;
                }

                $data = collection_to_array($data);

                $return = array();
                // return $data;
                $most_sessions_count = 0;
                foreach ($data as $item) {
                    if (isset($item['sessions_count']) and $item['sessions_count'] > $most_sessions_count) {
                        $most_sessions_count = $item['sessions_count'];
                    }
                }
                foreach ($data as $item) {
                    if (isset($item['content_id'])) {
                        $item['content_title'] = content_title($item['content_id']);
                    }
                    $item['url_slug'] = '/';
                    if (isset($item['url'])) {
                        $item['url_slug'] = str_replace($site_url, '', $item['url']);
                    }
                    if (!$item['url_slug']) {
                        $item['url_slug'] = '/';
                    }
                    if (isset($item['sessions_count'])) {
                        $item['sessions_percent'] = mw()->format->percent($item['sessions_count'], $most_sessions_count);
                    }


                    $return[] = $item;

                }

                if ($this->cache) {
                    cache_save($return, $cache_name, 'site_stats', $this->cache_exp);
                }

                return $return;

                break;

            case 'referrers_list':

                $log = new Sessions();
                $log = $log->period($period, 'stats_sessions');

                $log = $log->join('stats_referrers', function ($join) {
                    $join->on('stats_sessions.referrer_id', '=', 'stats_referrers.id');
                });


                $log = $log->select(
                    'stats_sessions.id',
                    'stats_sessions.referrer_domain_id',
                    'stats_referrers.is_internal',
//                    'stats_sessions.geoip_id as geoip_id',
//                    'stats_geoip.country_code as country_code',
//                    'stats_geoip.country_name as country_name',
                    DB::raw('count(session_id) as sessions_count')
                );


                // $log = $log->groupBy('referrer_id');
                if ($engine == 'pgsql') {
                    $log = $log->groupBy('stats_sessions.referrer_domain_id',
                        'stats_sessions.id',
                        'stats_referrers.is_internal');

                } else {

                    $log = $log->groupBy('stats_sessions.referrer_domain_id');
                }

                $log = $log->limit(500);
                $log = $log->orderBy('sessions_count', 'desc');

                $data = $log->get();

                if (!$data) {
                    return;
                }


                $return = array();


                $most_sessions_count = 0;
                foreach ($data as $item) {
                    $item_array = collection_to_array($item);

                    if (isset($item_array['sessions_count']) and $item_array['sessions_count'] > $most_sessions_count) {
                        $most_sessions_count = $item_array['sessions_count'];
                    }

                    if (isset($item_array['referrer_domain_id']) and $item_array['referrer_domain_id']) {
                        $log = new Sessions();
                        $log = $log->period($period, 'stats_sessions');

                        $log = $log->select(
                            'stats_sessions.referrer_domain_id',
                            'stats_sessions.referrer_path_id',
                            'stats_sessions.referrer_id',


                            DB::raw('count(referrer_path_id) as path_sessions_count')
                        );


                        $log = $log->where('referrer_domain_id', $item_array['referrer_domain_id']);
                        $log = $log->groupBy('stats_sessions.referrer_path_id');

                        if ($engine == 'pgsql') {
                            $log = $log->groupBy('stats_sessions.referrer_path_id', 'stats_sessions.referrer_domain_id', 'stats_sessions.referrer_id');

                        }


                        $log = $log->orderBy('path_sessions_count', 'desc');


                        $data2 = $log->get();
                        if ($data2) {
                            $item_array2 = collection_to_array($data2);
                            $item_array['referrer_paths'] = $item_array2;
                            foreach ($item_array2 as $related_data) {
                                if (isset($related_data['referrer_domain_id']) and $related_data['referrer_domain_id']) {
                                    $related_item = new ReferrersDomains();
                                    $related_item = $related_item->where('id', $related_data['referrer_domain_id'])->first();
                                    if ($related_item and $related_item->referrer_domain) {
                                        $item_array['referrer_domain'] = $related_item->referrer_domain;
                                    }
                                }
                            }


                            foreach ($item_array['referrer_paths'] as $rel_key => $related_data) {
                                if (isset($related_data['referrer_id']) and $related_data['referrer_id']) {
                                    $related_item = new Referrers();
                                    $related_item = $related_item->where('id', $related_data['referrer_id'])->first();
                                    if ($related_item and $related_item->referrer) {
                                        $item_array['referrer_paths'][$rel_key]['referrer_url'] = $related_item->referrer;
                                    }
                                    if (isset($item_array['sessions_count']) and $item_array['sessions_count']) {
                                        if (isset($related_data['path_sessions_count']) and $related_data['path_sessions_count']) {
                                            $item_array['referrer_paths'][$rel_key]['path_sessions_percent'] = mw()->format->percent($related_data['path_sessions_count'], $item_array['sessions_count']);

                                        }
                                    }
                                    if (isset($related_data['referrer_path_id']) and $related_data['referrer_path_id']) {
                                        $related_item = new ReferrersPaths();
                                        $related_item = $related_item->where('id', $related_data['referrer_path_id'])->first();
                                        if ($related_item and $related_item->referrer_path) {
                                            $item_array['referrer_paths'][$rel_key]['referrer_path'] = $related_item->referrer_path;
                                        } else {
                                            $item_array['referrer_paths'][$rel_key]['referrer_path'] = '';

                                        }
                                    }
                                }
                            }
                        }
                    }

                    if ($item_array and !empty($item_array)) {
                        ksort($item_array);
                        $return[] = $item_array;
                    }

                }
                if ($return) {
                    $data = $return;
                    $return = array();
                    foreach ($data as $item) {
                        $item_array = $item;
                        if (isset($item['sessions_count'])) {
                            $item_array['sessions_percent'] = mw()->format->percent($item['sessions_count'], $most_sessions_count);
                        }
                        $return[] = $item_array;

                    }
                }

                if ($this->cache) {
                    cache_save($return, $cache_name, 'site_stats', $this->cache_exp);
                }

                return $return;

                break;
            case 'locations_list':
            case 'languages_list':

                $log = new Sessions();
                $log = $log->period($period, 'stats_sessions');

                $log = $log->join('stats_geoip', function ($join) {
                    $join->on('stats_sessions.geoip_id', '=', 'stats_geoip.id');
                });


                if ($orig_return == 'locations_list') {
                    $log = $log->select('stats_sessions.id',
                        'stats_sessions.geoip_id as geoip_id',
                        'stats_geoip.country_code as country_code',
                        'stats_geoip.country_name as country_name',
                        DB::raw('count(geoip_id) as sessions_count')
                    );

                    $log = $log->groupBy('geoip_id');
                    if ($engine == 'pgsql') {
                        $log = $log->groupBy('geoip_id', 'country_code', 'country_name', 'stats_sessions.id');
                    }
                }

                if ($orig_return == 'languages_list') {
                    $log = $log->select('stats_sessions.id',
                        'stats_sessions.language as language',

                        DB::raw('count(language) as sessions_count')
                    );

                    $log = $log->groupBy('language');
                    if ($engine == 'pgsql') {
                        $log = $log->groupBy('language', 'stats_sessions.id');
                    }

                }


                $log = $log->limit(500);
                $log = $log->orderBy('sessions_count', 'desc');

                $data = $log->get();


                $most_sessions_count = 0;
                foreach ($data as $item) {
                    if (isset($item['sessions_count']) and $item['sessions_count'] > $most_sessions_count) {
                        $most_sessions_count = $item['sessions_count'];
                    }
                }

                $return = array();
                foreach ($data as $item) {
                    $item_array = collection_to_array($item);

                    if (isset($item['sessions_count'])) {
                        $item_array['sessions_percent'] = mw()->format->percent($item['sessions_count'], $most_sessions_count);
                    }
                    $return[] = $item_array;

                }

                if ($this->cache) {
                    cache_save($return, $cache_name, 'site_stats', $this->cache_exp);
                }

                return $return;
                break;

            case 'visitors_list':

                $log = new Sessions();
                //   $log = $log->select('stats_sessions.*');
                $log = $log->period($period, 'stats_sessions');
                //    $log = $log->select('session_id');


                $log = $log->join('stats_browser_agents', function ($join) {
                    $join->on('stats_sessions.browser_id', '=', 'stats_browser_agents.id');
                });

//                $log = $log->join('stats_referrers', function ($join) {
//                    $join->on('stats_sessions.referrer_id', '=', 'stats_referrers.id');
//                });


                $log = $log->join('stats_geoip', function ($join) {
                    $join->on('stats_sessions.geoip_id', '=', 'stats_geoip.id');
                });


                $log = $log->select('stats_sessions.*',
                    //      'stats_referrers.referrer',
                    'stats_browser_agents.browser as browser_name',
                    'stats_browser_agents.platform as browser_os',

                    'stats_geoip.country_code as country_code',
                    'stats_geoip.country_name as country_name'


                );


                $log = $log->limit(500);
                $log = $log->orderBy('stats_sessions.updated_at', 'desc');
                $log = $log->groupBy('session_id');

                if ($engine == 'pgsql') {
                    $log = $log->groupBy('stats_sessions.id', 'country_code', 'country_name', 'browser_name', 'browser_os');
                }


                $data = $log->get();


                if (!$data) {
                    return;
                }
                $return = array();
                // return $data;
                foreach ($data as $item) {
                    if (isset($item['updated_at'])) {
                        $item['updated_at'] = trim($item['updated_at']);
                    }


                    $item_array = collection_to_array($item);
                    //dd($item_array);

                    if ($item->views) {
                        // $item_array['views_data'] =$item->views->toArray();

                        $related_data = array();
                        foreach ($item->views as $related_item) {
                            $updated_at = $related_item->updated_at;
                            $url_id = $related_item->url_id;
                            $url = false;
                            $content_id = false;
                            $category_id = false;
                            $browser = false;
                            $title = false;
                            if ($related_item->url) {

                                $url = $related_item->url->url;
                                $content_id = $related_item->url->content_id;

                                $category_id = $related_item->url->category_id;
                                $title = content_title($content_id);

                            }

                            $related_data[] = array(
                                'view_id' => $related_item->id,
                                'updated_at' => $updated_at,
                                'url_id' => $url_id,
                                'title' => $title,
                                'url' => $url,
                                'content_id' => $content_id,
                                'category_id' => $category_id,
                            );
                        }
                        $item_array['title'] = false;
                        $item_array['url'] = false;

                        if (!empty($related_data)) {
                            $last = end($related_data);
                            $item_array['title'] = $last['title'];
                            $item_array['url'] = $last['url'];
                            $item_array['updated_at'] = $last['updated_at'];
                        }

                        $item_array['views_data'] = $related_data;

                    }

                    $item_array['views_count'] = $item->views()->count();
                    $return[] = $item_array;

                }


                $return = collection_to_array($return);
                if ($return) {
                    $sort = array();
                    foreach ($return as $key => $part) {
                        if (isset($part['updated_at'])) {
                            $sort[$key] = strtotime($part['updated_at']);
                        }
                    }
                    array_multisort($sort, SORT_DESC, $return);


                    if ($this->cache) {
                        cache_save($return, $cache_name, 'site_stats', $this->cache_exp);
                    }

                    return $return;
                }
                break;

        }

    }

    function get_stats_count($params)
    {
        if (!is_array($params)) {
            $params = parse_params($params);
        }

        $period = 'daily';
        if (isset($params['period'])) {
            $period = $params['period'];
        }


        $return = 'visitors_count';
        if (isset($params['return'])) {
            $return = $params['return'];
        }


        switch ($return) {
            case 'visitors_count':

                $log = new Sessions();
                $log = $log->period($period);
                $log = $log->select('session_id');
                $log = $log->groupBy('session_id');
                $return = $log->get();
                if ($return) {
                    return $return->count();
                }
                //    return $log->count('session_id');
//                $log = new Log();
//                $log = $log->period($period);
//                $log = $log->select('session_id_key');
//                $log = $log->groupBy('session_id_key');
//                $return = $log->get();
//                if ($return) {
//                    return $return->count();
//                }
                return 0;

                break;

            case 'views_count':
                $log = new Log();
                $log = $log->period($period);
                $return = $log->sum('view_count');

                return $return;

                break;


            case 'views_count_grouped_by_period':
            case 'visits_count_grouped_by_period':
                $log = new Log();
                //  $log = $log->period($period);
                $engine = mw()->database_manager->get_sql_engine();


                $group_by_key = 'date_key';
                if ($period == 'weekly') {
                    if ($engine == 'mysql') {
                        // DATE_SUB(DATE(updated_at), INTERVAL 7 DAY) as date_key
                        $date_period_q = "DATE(updated_at) as date_key, WEEK(DATE(updated_at)) as date_week, YEAR(DATE(updated_at)) as date_year";
                        $group_by_key = array('date_week', 'date_year');

                        // $group_by_key = 'date_week';
                        //  $date_period_q = "DATE_FORMAT(WEEK(DATE(updated_at)) as date_key";
                        //  $date_period_q = "SUBDATE(updated_at, INTERVAL 1 WEEK) as date_key";
                        //  $date_period_q = "DATE(updated_at - INTERVAL '7 DAY') as date_key";
                        //   $date_period_q = "DATE_SUB(DATE(updated_at),'INTERVAL 1 WEEK') as date_key";

                    } else if ($engine == 'pgsql') {
                        $date_period_q = "DATE(updated_at - INTERVAL '7 DAY') as date_key";
                    } else {
                        $date_period_q = "DATE(updated_at,'weekday 1','+7 days') as date_key";
                    }
                }

                if ($period == 'monthly') {


                    if ($engine == 'mysql') {
                        //  $date_period_q = "DATE_SUB(updated_at, INTERVAL 1 MONTH) as date_key";
                        // $date_period_q = "DATE_SUB(DATE(updated_at), INTERVAL 1 MONTH) as date_key";

                        $date_period_q = "DATE(updated_at) as date_key, MONTH(DATE(updated_at)) as date_month, YEAR(DATE(updated_at)) as date_year";
                        $group_by_key = array('date_month', 'date_year');


                    } else if ($engine == 'pgsql') {
                        $date_period_q = "DATE(updated_at - INTERVAL '1 MONTH') as date_key";
                    } else {
                        $date_period_q = "DATE(updated_at,'start of month','+1 month','-1 day') as date_key";
                    }
                }
                if ($period == 'daily') {
                    if ($engine == 'mysql') {
                        $date_period_q = "DATE(updated_at) as date_key";
                        //  $date_period_q = "DATE_SUB(updated_at, INTERVAL 1 DAY) as date_key";
                    } else if ($engine == 'pgsql') {
                        $date_period_q = "DATE(updated_at - INTERVAL '1 DAY') as date_key";
                    } else {
                        $date_period_q = "DATE(updated_at) as date_key";
                    }
                }

                if ($period == 'yearly') {
                    if ($engine == 'mysql') {
                        $date_period_q = "DATE_ADD(updated_at, INTERVAL 1 YEAR) as date_key";

                        $date_period_q = "DATE(updated_at) as date_key, YEAR(DATE(updated_at)) as date_year";
                        $group_by_key = array('date_year');


                    } else if ($engine == 'pgsql') {
                        $date_period_q = "DATE(updated_at - INTERVAL '1 YEAR') as date_key";
                    } else {
                        $date_period_q = "DATE(updated_at,'start of year','+1 year','-1 day') as date_key";
                    }
                }


                if ($return == 'visits_count_grouped_by_period') {

//                    $log = $log->select(DB::raw($date_period_q . ', count(session_id_key) as date_value'));
//                    $log = $log->groupBy('date_key');


                    $log = new Sessions();
                    $log = $log->select(DB::raw($date_period_q . ', count(session_id) as date_value'));

                    //   $log->where('updated_at', '>=', date('Y-m-d H:i:s', strtotime('-1 year')));


                    $log = $log->groupBy($group_by_key);
                } else {
                    $log = $log->select(DB::raw($date_period_q . ', sum(view_count) as date_value'));
                    // $log->where('updated_at', '>=', date('Y-m-d H:i:s', strtotime('-1 year')));

                    $log = $log->groupBy($group_by_key);
                }


                if ($period == 'daily') {
                    $log = $log->limit(30);
                    $log->where('updated_at', '>=', date('Y-m-d H:i:s', strtotime('-1 month')));

                }
                if ($period == 'weekly') {
                    $log = $log->limit(12);
                    $log->where('updated_at', '>=', date('Y-m-d H:i:s', strtotime('-3 months')));

                }
                if ($period == 'monthly') {
                    $log = $log->limit(12);
                    $log->where('updated_at', '>=', date('Y-m-d H:i:s', strtotime('-1 year')));

                }
                if ($period == 'yearly') {
                    $log = $log->limit(5);
                    $log->where('updated_at', '>=', date('Y-m-d H:i:s', strtotime('-5 year')));

                }

                $return = $log->get();

                $return = collection_to_array($return);

//                if ($return) {
//                    $return = array_reverse($return);
//                    $return = array_slice($return, 0, 14);
//                    $return = array_reverse($return);
//
//                }

                return $return;

                break;


            case 'users_online':

                $log = new Log();
                $log = $log->period('-15 minutes');
                $log = $log->select('session_id_key');
                $log = $log->groupBy('session_id_key');
                $return = $log->get();
                if ($return) {
                    return $return->count();
                }
                return 0;
                break;


            case 'orders_count':
                $log = new Orders();
                $log = $log->period($period);
                $log = $log->where('order_completed', 1);
                $return = $log->count();
                return $return;

                break;
            case 'comments_count':

                if (is_module_installed('comments')) {
                    $log = new Comments();
                    $log = $log->period($period);
                    $return = $log->count();
                    return $return;
                }
                break;


            default:
                $start_date = date('Y-m-d H:i:s', strtotime($period));
                $end_date = date('Y-m-d H:i:s');

                break;
        }


    }


}
