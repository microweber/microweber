<?php

$some_items_per_page = 10;
$query_options = array ();
$query_options ['debug'] = false;
$query_options ['get_params_from_url'] = true;
$query_options ['items_per_page'] = $some_items_per_page;
$query_options ['debug'] = false;
$query_options ['group_by'] = 'to_table, to_table_id';

$user_action = $this->core_model->getParamFromURL ( 'action' );

$log_params = array ();
if ($user_action != 'live') {
	$followed = $this->users_model->realtionsGetFollowedIdsForUser ( $aUserId = false, false, false );
	if (empty ( $followed )) {
		$log_params [] = array ("id", '0' );
	} else {
		$log_params ["for_user_ids"] = $followed;
	}

}
$log_params [] = array ("is_read", 'n' );

$log = $this->notifications_model->logGetByParams ( $log_params, $query_options );
$query_options ['group_by'] = false;
//$query_options ['debug'] = true;
$query_options ['get_count'] = true;
$query_options ['items_per_page'] = false;
$log_count = $this->notifications_model->logGetByParams ( $log_params, $query_options );
//p($log_count);
$results_count = intval ( $log_count );
$log_count = ceil ( $results_count / $some_items_per_page );

$url = site_url ( 'dashboard' );
$paging = $this->content_model->pagingPrepareUrls ( $url, $log_count );

$this->template ['posts_pages_links'] = $paging;
$this->template ['dashboard_log'] = $log;

$content ['content_filename'] = 'dashboard/index.php';
























