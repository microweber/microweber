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
                return $log->count('session_id');
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
                if($return == 'visits_count_grouped_by_period'){

                    $log = $log->select(DB::raw($date_period_q . ', count(session_id_key) as date_value'));
                    $log = $log->groupBy('date_key');


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
