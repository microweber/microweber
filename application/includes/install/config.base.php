<?php

defined('T') or die();





// Global site configuration
$config = array(
    // In development, debug mode unlocks extra error info
    'debug_mode' => TRUE,
    'admin_url' => 'admin',
    'uri_protocol' => 'AUTO',
    'default_timezone' => 'UTC',
    'installed' => '{IS_INSTALLED}',
    // Database Settings
    'db' => array(
        //'dsn' => 'mysql:host=localhost;port=3306;dbname=mw_install',
        // 'dsn' => 'sqlite:db/default.db',
        'host' => '{DB_HOST}',
        'dbname' => '{dbname}',
        'user' => '{DB_USER}',
        'pass' => '{DB_PASS}'
    )
);

 

































$cms_db_tables = array();
$cms_db_tables['table_cache'] = MW_TABLE_PREFIX . 'cache';
$cms_db_tables['table_content'] = MW_TABLE_PREFIX . 'content';
$cms_db_tables['table_taxonomy'] = $cms_db_tables['table_categories'] = MW_TABLE_PREFIX . 'taxonomy';
$cms_db_tables['table_taxonomy_items'] = MW_TABLE_PREFIX . 'taxonomy_items';




$cms_db_tables['table_menus'] = MW_TABLE_PREFIX . 'menus';
$cms_db_tables['table_options'] = MW_TABLE_PREFIX . 'options';
$cms_db_tables['table_media'] = MW_TABLE_PREFIX . 'media';
$cms_db_tables['table_geodata'] = MW_TABLE_PREFIX . 'geodata';
$cms_db_tables['table_comments'] = MW_TABLE_PREFIX . 'comments';
$cms_db_tables['table_votes'] = MW_TABLE_PREFIX . 'votes';
$cms_db_tables['table_users'] = MW_TABLE_PREFIX . 'users';
$cms_db_tables['table_messages'] = MW_TABLE_PREFIX . 'messages';
$cms_db_tables['table_users_log'] = MW_TABLE_PREFIX . 'users_log';
$cms_db_tables['table_users_statuses'] = MW_TABLE_PREFIX . 'users_statuses';
$cms_db_tables['table_stats_sites'] = MW_TABLE_PREFIX . 'statssite';
$cms_db_tables['table_users_notifications'] = MW_TABLE_PREFIX . 'users_notifications';
$cms_db_tables['table_users_statuses'] = MW_TABLE_PREFIX . 'users_statuses';
$cms_db_tables['table_followers'] = MW_TABLE_PREFIX . 'followers';
$cms_db_tables['table_sessions'] = MW_TABLE_PREFIX . 'sessions';
$cms_db_tables['table_custom_fields'] = MW_TABLE_PREFIX . 'content_custom_fields';
$cms_db_tables['table_custom_fields_config'] = MW_TABLE_PREFIX . 'content_custom_fields_config';
$cms_db_tables['table_cart'] = MW_TABLE_PREFIX . 'cart';
$cms_db_tables['table_cart_orders'] = MW_TABLE_PREFIX . 'cart_orders';

$cms_db_tables['table_modules'] = MW_TABLE_PREFIX . 'modules';
$cms_db_tables['table_elements'] = MW_TABLE_PREFIX . 'elements';



$cms_db_tables['table_cart_promo_codes'] = MW_TABLE_PREFIX . 'cart_promo_codes';
$cms_db_tables['table_countries'] = MW_TABLE_PREFIX . 'countries';
$cms_db_tables['table_cart_orders_shipping_cost'] = MW_TABLE_PREFIX . 'cart_orders_shipping_cost';
$cms_db_tables['table_cart_currency'] = MW_TABLE_PREFIX . 'cart_currency';
$cms_db_tables['table_reports'] = MW_TABLE_PREFIX . 'reports';

$cms_db_tables['table_forms'] = MW_TABLE_PREFIX . "forms";
//stats
$cms_db_tables['table_stats_site'] = 'piwik_site';
$cms_db_tables['table_stats_access'] = 'piwik_access';
$cms_db_tables['table_log_action'] = 'piwik_log_action';
$cms_db_tables['table_log_link_visit_action'] = 'piwik_log_link_visit_action';
//use this array to exlude certain table interactions from the users log table
$users_log_exclude = array();
//not used if $users_log_include is not empty
$users_log_exclude[] = 'table_users_notifications';
$users_log_exclude[] = 'table_media';
$users_log_exclude[] = 'table_cart_orders_shipping_cost';
$users_log_exclude[] = 'table_cart_orders';
$users_log_exclude[] = 'table_taxonomy';
$users_log_exclude[] = 'table_sessions';
$users_log_exclude[] = 'table_messages';
$users_log_exclude[] = 'table_users_log';
// :) no loops pls
$users_log_exclude[] = 'table_countries';
$users_log_exclude[] = 'table_stats';
$users_log_exclude[] = 'table_options';
$users_log_exclude[] = 'table_reports';
//use this array to force certain table interactions from the users log table
$users_log_include = array();
$users_log_include[] = 'table_comments';
$users_log_include[] = 'table_users';
$users_log_include[] = 'table_users_statuses';
$users_log_include[] = 'table_content';
$users_log_include[] = 'table_votes';
$users_log_include[] = 'table_followers';














$config['db_tables'] = $cms_db_tables;
$config['db_log_tables'] = $users_log_include;
//$_GLOBALS ['cms_db'] = $cms_db;.
$cms_db_tables_search_fields = array('title', "content", "url");
return $config;