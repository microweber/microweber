<?php

$some_items_per_page = 20;
//p($is_special);
$query_options = array ();
$query_options ['get_params_from_url'] = true;
$query_options ['debug'] = false;
$query_options ['items_per_page'] = $some_items_per_page;
$dashboard_following = $this->users_model->realtionsGetFollowersIdsForUser ( $aUserId = false,  $is_special, $query_options );
$query_options ['get_count'] = true;

$query_options ['items_per_page'] = false;
$dashboard_following_count = $this->users_model->realtionsGetFollowersIdsForUser ( $aUserId = false,  $is_special, $query_options );

$results_count = intval ( $dashboard_following_count );
$following_pages_count = ceil ( $results_count / $some_items_per_page );

$url = site_url ( 'dashboard/action:' . $user_action . '/' );
$paging = $this->content_model->pagingPrepareUrls ( $url, $following_pages_count );
$this->template ['posts_pages_links'] = $paging;


$this->template ['dashboard_following'] = $dashboard_following;
$content ['content_filename'] = 'dashboard/following.php';




/*

$dashboard_followers = $this->users_model->getFollowers(false, $only_ids = true);
$this->template ['dashboard_followers'] = $dashboard_followers;

//p($followers);

$content ['content_filename'] = 'dashboard/followers.php';*/