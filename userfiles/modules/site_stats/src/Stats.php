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


        switch ($period) {
            case 'daily':
                $date_column_substr_to_char = 13;
                break;

            case 'weekly':
                 $date_column_substr_to_char = 10;

                break;


            case 'monthly':
                $date_column_substr_to_char = 7;
                break;
            default:

                break;
        }




        switch ($return) {
            case 'visitors_count':

                $log = new Log();
                $log = $log->period($period);
                $log = $log->select('session_id_key');
                $log = $log->groupBy('session_id_key');
                $return = $log->get();
                if ($return) {
                    return $return->count();
                }
                return 0;

                break;

            case 'views_count':
                $log = new Log();
                $log = $log->period($period);
                $return = $log->sum('view_count');

                return $return;

                break;


            case 'views_count_grouped_by_period':
                $log = new Log();
                $log = $log->period($period);



                $date_period_q = 'substr(updated_at, 1, '.$date_column_substr_to_char.') as date_period';
                $log->select(DB::raw($date_period_q.', sum(view_count) as view_count_period'));


                $log = $log->groupBy('date_period');


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
