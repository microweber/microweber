<?php

namespace Microweber\SiteStats\Models;


class Log extends Base
{
    protected $table = 'stats_visits_log';
    protected $fillable = [

        'session_id_key',
        'url_id',
       // 'visit_url',
     //   'referrer',
        'referrer_id',
      //  'user_ip',
      //  'user_id',
        'content_id',
        'category_id',
       // 'browser_agent_id',
       // 'language',
        'updated_at',
    ];

   /* public function session()
    {
        return $this->belongsTo($this->getConfig()->get('session_model'));
    }

    public function path()
    {
        return $this->belongsTo($this->getConfig()->get('path_model'));
    }

    public function error()
    {
        return $this->belongsTo($this->getConfig()->get('error_model'));
    }

    public function logQuery()
    {
        return $this->belongsTo($this->getConfig()->get('query_model'), 'query_id');
    }

    public function routePath()
    {
        return $this->belongsTo($this->getConfig()->get('route_path_model'), 'route_path_id');
    }

    public function pageViews($minutes, $results)
    {
        $query = $this->select(
            $this->getConnection()->raw('DATE(created_at) as date, count(*) as total')
        )->groupBy(
            $this->getConnection()->raw('DATE(created_at)')
        )
            ->period($minutes)
            ->orderBy('date');
        if ($results) {
            return $query->get();
        }
        return $query;
    }

    public function pageViewsByCountry($minutes, $results)
    {
        $query =
            $this
                ->select(
                    'tracker_geoip.country_name as label', $this->getConnection()->raw('count(tracker_log.id) as value')
                )
                ->join('tracker_sessions', 'tracker_log.session_id', '=', 'tracker_sessions.id')
                ->join('tracker_geoip', 'tracker_sessions.geoip_id', '=', 'tracker_geoip.id')
                ->groupBy('tracker_geoip.country_name')
                ->period($minutes, 'tracker_log')
                ->whereNotNull('tracker_sessions.geoip_id')
                ->orderBy('value', 'desc');
        if ($results) {
            return $query->get();
        }
        return $query;
    }

    public function errors($minutes, $results)
    {
        $query = $this
            ->with('error')
            ->with('session')
            ->with('path')
            ->period($minutes, 'tracker_log')
            ->whereNotNull('error_id')
            ->orderBy('created_at', 'desc');
        if ($results) {
            return $query->get();
        }
        return $query;
    }

    public function allByRouteName($name, $minutes = null)
    {
        $result = $this
            ->join('tracker_route_paths', 'tracker_route_paths.id', '=', 'tracker_log.route_path_id')
            ->leftJoin(
                'tracker_route_path_parameters',
                'tracker_route_path_parameters.route_path_id',
                '=',
                'tracker_route_paths.id'
            )
            ->join('tracker_routes', 'tracker_routes.id', '=', 'tracker_route_paths.route_id')
            ->where('tracker_routes.name', $name);
        if ($minutes) {
            $result->period($minutes, 'tracker_log');
        }
        return $result;
    }*/
}