<?php
$config = array();
$config['name'] = "Site stats";
$config['author'] = "Microweber";
$config['ui_admin'] = false;

$config['ui'] = false;
$config['position'] = 9999;
$config['version'] = "0.7";
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


$fields_to_add['view_count'] = ['type' => 'integer', 'default' => 1];

$config['tables']['stats_visits_log'] = $fields_to_add;


$fields_to_add = array();
$fields_to_add['browser_agent'] = 'text';
$fields_to_add['browser_agent_hash'] = 'string';

$fields_to_add['platform'] = 'string';
$fields_to_add['platform_version'] = 'string';
$fields_to_add['browser'] = 'string';
$fields_to_add['browser_version'] = 'string';
$fields_to_add['device'] = 'string';

$fields_to_add['is_desktop'] = 'integer';
$fields_to_add['is_mobile'] = 'integer';
$fields_to_add['is_phone'] = 'integer';
$fields_to_add['is_tablet'] = 'integer';

$fields_to_add['robot_name'] = 'text';
$fields_to_add['is_robot'] = 'string';
$fields_to_add['language'] = 'string';


$fields_to_add['updated_at'] = 'dateTime';
//$fields_to_add['created_at'] = 'dateTime';
$config['tables']['stats_browser_agents'] = $fields_to_add;


$fields_to_add = array();
$fields_to_add['referrer'] = 'text';
$fields_to_add['referrer_hash'] = 'string';
$fields_to_add['referrer_domain_id'] = 'integer';
$fields_to_add['referrer_path_id'] = 'integer';
$fields_to_add['is_internal'] = 'integer';
$fields_to_add['updated_at'] = 'dateTime';
//$fields_to_add['created_at'] = 'dateTime';
$config['tables']['stats_referrers'] = $fields_to_add;

$fields_to_add = array();
$fields_to_add['referrer_domain'] = 'text';
$fields_to_add['updated_at'] = 'dateTime';
$config['tables']['stats_referrers_domains'] = $fields_to_add;

$fields_to_add = array();
$fields_to_add['referrer_domain_id'] = 'integer';
$fields_to_add['referrer_path'] = 'string';
$fields_to_add['updated_at'] = 'dateTime';
$config['tables']['stats_referrers_paths'] = $fields_to_add;


$fields_to_add = array();
$fields_to_add['url'] = 'string';
$fields_to_add['content_id'] = 'integer';
$fields_to_add['category_id'] = 'integer';
$fields_to_add['url_hash'] = 'string';
$fields_to_add['updated_at'] = 'dateTime';
//$fields_to_add['created_at'] = 'dateTime';
$config['tables']['stats_urls'] = $fields_to_add;

$fields_to_add = array();
$fields_to_add['session_id'] = 'string';
$fields_to_add['session_hostname'] = 'string';
$fields_to_add['user_ip'] = 'string';
$fields_to_add['user_id'] = 'integer';
$fields_to_add['browser_id'] = 'integer';
$fields_to_add['referrer_id'] = 'integer';
$fields_to_add['referrer_domain_id'] = 'integer';
$fields_to_add['referrer_path_id'] = 'integer';
$fields_to_add['geoip_id'] = 'integer';
$fields_to_add['language'] = 'string';

$fields_to_add['updated_at'] = 'dateTime';
//$fields_to_add['created_at'] = 'dateTime';
$config['tables']['stats_sessions'] = $fields_to_add;


$fields_to_add = array();
$fields_to_add['country_code'] = 'string';
$fields_to_add['country_name'] = 'string';
$fields_to_add['region'] = 'string';
$fields_to_add['city'] = 'string';
$fields_to_add['latitude'] = 'string';
$fields_to_add['longitude'] = 'string';
$fields_to_add['updated_at'] = 'dateTime';
//$fields_to_add['created_at'] = 'dateTime';
$config['tables']['stats_geoip'] = $fields_to_add;

$google_analytics_events = [
    'event_category' => 'string',
    'event_action' => 'string',
    'event_label' => 'string',
    'event_value' => 'integer',
    'utm_source' => 'string',
    'utm_medium' => 'string',
    'utm_campaign' => 'string',
    'utm_term' => 'string',
    'utm_content' => 'string',
    'event_timestamp' => 'dateTime',
];
$config['tables']['stats_events'] = $google_analytics_events;



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



$config['settings'] = [];
$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\Modules\SiteStats'
    ],
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\SiteStats\Providers\SiteStatsServiceProvider::class,
    \MicroweberPackages\Modules\SiteStats\Providers\SiteStatsEventsServiceProvider::class
];

