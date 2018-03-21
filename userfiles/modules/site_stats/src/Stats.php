<?php

namespace Microweber\SiteStats;


use Microweber\SiteStats\Models\Browsers;
use Microweber\SiteStats\Models\Comments;
use Microweber\SiteStats\Models\Log;
use Microweber\SiteStats\Models\Orders;
use Microweber\SiteStats\Models\Referrers;
use Microweber\SiteStats\Models\Sessions;
use Microweber\SiteStats\Models\Urls;
use Carbon\Carbon;
use Microweber\App\Providers\Illuminate\Support\Facades\DB;

class Stats
{

    function get_stats_items($params)
    {
        if (!is_array($params)) {
            $params = parse_params($params);
        }

        $period = 'daily';
        if (isset($params['period'])) {
            $period = $params['period'];
        }


        $return = 'visitors_list';
        if (isset($params['return'])) {
            $return = $params['return'];
        }
        switch ($return) {
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

                $data = $log->get();

            //  dd($data);

                if (!$data) {
                    return;
                }
                $return = array();
                // return $data;
                foreach ($data as $item) {
                    if(isset($item['updated_at'])){
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

                            $related_data[$related_item->id] = array(
                                'updated_at' => $updated_at,
                                'url_id' => $url_id,
                                'title' => $title,
                                'url' => $url,
                                'content_id' => $content_id,
                                'category_id' => $category_id,
                            );
                        }


                        $item_array['views_data'] = $related_data;

                    }

//                    if ($item->geoip) {
//                        $item_array['geoip_data'] = collection_to_array($item->geoip);;
//
//
//                    }
//                    if ($item->browser) {
//                        $browser = $item->browser->browser_agent;
//                        $item_array['browser_agent'] = $browser;
//                    }


                    $item_array['views_count'] = $item->views()->count();;
                    $return[] = $item_array;

                }


                $return = collection_to_array($return);

                return $return;

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


                if ($period == 'weekly') {
                    $date_period_q = "DATE(updated_at,'weekday 1','+7 days') as date_key";
                }

                if ($period == 'monthly') {
                    $date_period_q = "DATE(updated_at,'start of month','+1 month','-1 day') as date_key";

                }
                if ($period == 'daily') {
                    $date_period_q = "DATE(updated_at) as date_key";

                }
                if ($return == 'visits_count_grouped_by_period') {

//                    $log = $log->select(DB::raw($date_period_q . ', count(session_id_key) as date_value'));
//                    $log = $log->groupBy('date_key');


                    $log = new Sessions();
                    $log = $log->select(DB::raw($date_period_q . ', count(session_id) as date_value'));
                    $log = $log->groupBy('date_key');
                } else {
                    $log = $log->select(DB::raw($date_period_q . ', sum(view_count) as date_value'));
                    $log = $log->groupBy('date_key');
                }


                $log = $log->limit(30);
                $return = $log->get();

                $return = collection_to_array($return);

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
                $log = new Comments();
                $log = $log->period($period);
                $return = $log->count();
                return $return;
                break;


            default:
                $start_date = date('Y-m-d H:i:s', strtotime($period));
                $end_date = date('Y-m-d H:i:s');

                break;
        }


    }


}
