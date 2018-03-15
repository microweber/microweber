<?php

namespace Microweber\SiteStats;


use Microweber\SiteStats\Models\Browsers;
use Microweber\SiteStats\Models\Comments;
use Microweber\SiteStats\Models\Log;
use Microweber\SiteStats\Models\Orders;
use Microweber\SiteStats\Models\Referrers;
use Microweber\SiteStats\Models\Sessions;
use Microweber\SiteStats\Models\Urls;


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


        if ($return == 'visitors_count') {
            $log = new Log();
            $log = $log->period($period);
            $log = $log->select('session_id_key');
            $log = $log->groupBy('session_id_key');
            $return = $log->get();
            if ($return) {
                return $return->count();
            }

            return $return;
        }


        if ($return == 'views_count') {
            $log = new Log();
            $log = $log->period($period);
            $return = $log->sum('view_count');

            return $return;
        }


        if ($return == 'orders_count') {
            $log = new Orders();
            $log = $log->period($period);
            $log = $log->where('order_completed', 1);
            $return = $log->count();
            return $return;
        }

        if ($return == 'comments_count') {
            $log = new Comments();
            $log = $log->period($period);
            $return = $log->count();
            return $return;
        }


    }

}
