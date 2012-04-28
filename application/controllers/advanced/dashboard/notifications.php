<?php

$some_items_per_page = 300;
/*$params = array ();
$params [] = array ('to_user', $currentUser ['id'] );



$query_options = array ();
$query_options ['get_params_from_url'] = true;
$query_options ['debug'] = true;
$query_options ['items_per_page'] = $some_items_per_page;
$query_options ['group_by'] = 'to_table, to_table_id,from_user, type';

*/




$notifications = CI::model('notifications')->notificationsGetByDefaultParams ( $params = false , $query_options = false);
$query_options ['get_count'] = true;

$query_options ['items_per_page'] = false;
$notifications_count = CI::model('notifications')->notificationsGetByDefaultParams ( $params  = false , $query_options );
$results_count = intval ( $notifications_count );
$following_pages_count = ceil ( $results_count / $some_items_per_page );

$url = site_url ( 'dashboard/action:' . $user_action . '/' );
$paging = $this->content_model->pagingPrepareUrls ( $url, $following_pages_count );
$this->template ['posts_pages_links'] = $paging;





//p($notifications);


//$notifications = $this->core_model->fetchDbData ( TABLE_PREFIX . 'users_notifications', array ( ), array ('order' => array ('created_on', 'DESC' ) ) );


$this->template ['notifications'] = $notifications;

$content ['content_filename'] = 'dashboard/notifications/notifications.php';

?>