<?php
$config = array();
$config['name'] = "Site stats";
$config['author'] = "Microweber";
$config['ui_admin'] = false;

$config['ui'] = false;
$config['position'] = 30;
$config['version'] = 0.5;
$config['type'] = "stats";

$config['tables'] = array();
//$fields_to_add = array();
//$fields_to_add['created_by'] = 'integer';
//$fields_to_add['view_count'] = ['type' => 'integer', 'default' => 1];
//$fields_to_add['referrer'] = 'string';
//$fields_to_add['last_page'] = 'string';
//$fields_to_add['visit_date'] = 'date';
//$fields_to_add['visit_time'] = 'time';
//$fields_to_add['updated_at'] = 'dateTime';
//$fields_to_add['session_id'] = 'string';
//$fields_to_add['user_ip'] = 'string';
//$fields_to_add['user_id'] = 'string';
//$config['tables']['stats_users_online'] = $fields_to_add;


$fields_to_add = array();
$fields_to_add['content_id'] = 'integer';
$fields_to_add['category_id'] = 'integer';
$fields_to_add['url_id'] = 'integer';


//$fields_to_add['visit_url'] = 'string';
//$fields_to_add['referrer'] = 'string';
$fields_to_add['referrer_id'] = 'integer';

$fields_to_add['updated_at'] = 'dateTime';
//$fields_to_add['created_at'] = 'dateTime';

$fields_to_add['session_id_key'] = 'integer';
//$fields_to_add['user_ip'] = 'string';
//$fields_to_add['user_id'] = 'integer';

//$fields_to_add['browser_agent'] = 'string';
//$fields_to_add['language'] = 'string';

//$fields_to_add['browser_agent_id'] = 'integer';




$config['tables']['stats_visits_log'] = $fields_to_add;



$fields_to_add = array();
$fields_to_add['browser_agent'] = 'string';
$fields_to_add['browser_agent_hash'] = 'string';
$fields_to_add['updated_at'] = 'dateTime';
//$fields_to_add['created_at'] = 'dateTime';
$config['tables']['stats_browser_agents'] = $fields_to_add;


$fields_to_add = array();
$fields_to_add['referrer'] = 'string';
$fields_to_add['referrer_hash'] = 'string';
$fields_to_add['is_internal'] = 'integer';
$fields_to_add['updated_at'] = 'dateTime';
//$fields_to_add['created_at'] = 'dateTime';
$config['tables']['stats_referrers'] = $fields_to_add;



$fields_to_add = array();
$fields_to_add['url'] = 'string';
$fields_to_add['url_hash'] = 'string';
$fields_to_add['updated_at'] = 'dateTime';
//$fields_to_add['created_at'] = 'dateTime';
$config['tables']['stats_urls'] = $fields_to_add;

$fields_to_add = array();
$fields_to_add['session_id'] = 'string';
$fields_to_add['session_id'] = 'string';
$fields_to_add['user_ip'] = 'integer';
$fields_to_add['user_id'] = 'integer';
$fields_to_add['browser_id'] = 'integer';
$fields_to_add['language'] = 'string';

$fields_to_add['updated_at'] = 'dateTime';
//$fields_to_add['created_at'] = 'dateTime';
$config['tables']['stats_sessions'] = $fields_to_add;


//
//$fields_to_add = array();
//$fields_to_add['view_count'] = ['type' => 'integer', 'default' => 1];
//$fields_to_add['page_id'] = 'integer';
////$fields_to_add['main_page_id'] = 'integer';
////$fields_to_add['parent_page_id'] = 'integer';
//$fields_to_add['category_id'] = 'integer';
//$fields_to_add['updated_at'] = 'dateTime';
//$fields_to_add['session_id'] = 'string';
//$fields_to_add['user_ip'] = 'string';
//$fields_to_add['user_id'] = 'string';
//$fields_to_add['referrer'] = 'string';
//$fields_to_add['last_page'] = 'string';
//$fields_to_add['visit_date'] = 'date';
//$fields_to_add['visit_time'] = 'time';
//
//$config['tables']['stats_pageviews'] = $fields_to_add;






