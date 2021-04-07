<?php


$views = 0;
$content_id = 0;

if (isset($params['content-id'])) {
    $content_id = intval($params['content-id']);
    $views = stats_get_views_count_for_content($content_id);
}


print $views;
