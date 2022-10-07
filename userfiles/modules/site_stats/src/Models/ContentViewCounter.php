<?php


namespace MicroweberPackages\SiteStats\Models;


use MicroweberPackages\Content\Models\Content;

class ContentViewCounter
{
    public $cacheSeconds = 600;

    public function getCountViewsForContent($contentId)
    {
        $use_cache = get_option('stats_views_counter_live_stats', 'site_stats') != 1;

        if ($use_cache) {
            $cacheTags = ['stats_visits_log'];
            $cacheKey = 'stats_view_count_' . $contentId;

            $cacheFind = \Cache::tags($cacheTags)->get($cacheKey);

            if ($cacheFind !== null) {
                return $cacheFind;
            }

        }
        $related_data = new StatsUrl();
        $related_data = $related_data->where('stats_urls.content_id', $contentId);;
        $related_data = $related_data->join('stats_visits_log', 'stats_visits_log.url_id', '=', 'stats_urls.id');

        $data = $related_data->sum('stats_visits_log.view_count');

        if ($use_cache) {
            \Cache::tags($cacheTags)->put($cacheKey, $data, $this->cacheSeconds);
        }
        return $data;
    }

    public function getMostViewedForContentForPeriod($contentId, $period = 'daily') {

        $range = $this->getDateRangeByPeriod($period);

        $contentQuery = Content::query();
        $contentQuery->select('content.id', 'stats_urls.id as stats_url_id', \DB::raw('SUM(view_count) AS stats_view_count'));
        $contentQuery->where('content.parent', $contentId);
        $contentQuery->join('stats_urls', 'stats_urls.content_id', '=', 'content.id');
        $contentQuery->join('stats_visits_log', 'stats_visits_log.url_id', '=', 'stats_urls.id');
        $contentQuery->whereBetween('stats_visits_log.updated_at', [$range['start_date'], $range['end_date']]);
        $contentQuery->groupBy('content.id');
        $contentQuery->orderBy('stats_view_count', 'DESC');

        return $contentQuery->get();

    }

    public function getDateRangeByPeriod($period = 'daily') {

        $startDate = date('Y-m-d H:i:s');
        $endDate = date('Y-m-d H:i:s');

        switch ($period) {
            case 'daily':
                $startDate = date('Y-m-d H:i:s', strtotime('-1 days'));
                break;

            case 'weekly':
                $startDate = date('Y-m-d H:i:s', strtotime('-1 weeks'));
                break;

            case 'monthly':
                $startDate = date('Y-m-d H:i:s', strtotime('-12 months'));
                break;

            case 'yearly':
                $startDate = date('Y-m-d H:i:s', strtotime('-1 year'));
                break;
            default:
                $startDate = date('Y-m-d H:i:s', strtotime($period));
                break;
        }

        return ['start_date'=>$startDate, 'end_date'=>$endDate];
    }
}
