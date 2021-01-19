<?php


namespace MicroweberPackages\SiteStats\Models;


class ContentViewCounter
{
    public $cacheSeconds = 600;
    public function getCountViewsForContent($content_id)
    {
        $cacheTags = ['stats_visits_log'];
        $cacheKey = 'stats_view_count_'.$content_id;


        $cacheFind = \Cache::tags($cacheTags)->get($cacheKey);

        if($cacheFind !== null){
            return $cacheFind;
        }

        $related_data = new Urls();
        $related_data = $related_data->where('stats_urls.content_id', $content_id);;
        $related_data = $related_data->join('stats_visits_log', 'stats_visits_log.url_id', '=', 'stats_urls.id');

        $data = $related_data->sum('stats_visits_log.view_count');

        \Cache::tags($cacheTags)->put($cacheKey, $data, $this->cacheSeconds);

        return $data;
    }
}