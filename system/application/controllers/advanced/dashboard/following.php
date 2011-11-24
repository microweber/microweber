<?php

$some_items_per_page = 20;
//p($is_special);
$query_options = array ();
$query_options ['get_params_from_url'] = true;
$query_options ['debug'] = false;
$query_options ['items_per_page'] = $some_items_per_page;
$dashboard_following = CI::model('users')->realtionsGetFollowedIdsForUser ( $aUserId = false,  $is_special, $query_options );
$query_options ['get_count'] = true;

$query_options ['items_per_page'] = false;
$dashboard_following_count = CI::model('users')->realtionsGetFollowedIdsForUser ( $aUserId = false,  $is_special, $query_options );

$results_count = intval ( $dashboard_following_count );
$following_pages_count = ceil ( $results_count / $some_items_per_page );

$url = site_url ( 'dashboard/action:' . $user_action . '/' );
$paging = CI::model('content')->pagingPrepareUrls ( $url, $following_pages_count );
$this->template ['posts_pages_links'] = $paging;


$this->template ['dashboard_following'] = $dashboard_following;
$content ['content_filename'] = 'dashboard/following.php';


