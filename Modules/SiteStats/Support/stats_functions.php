<?php


if(!function_exists('stats_get_views_count_for_content')) {
    function stats_get_views_count_for_content($content_id = 0)
    {
        return (new \Modules\SiteStats\Models\ContentViewCounter())->getCountViewsForContent($content_id);
    }
}

