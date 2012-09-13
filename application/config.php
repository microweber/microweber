<?php

defined('T') or die();




// Global site configuration
$config = array(
    // In development, debug mode unlocks extra error info
    'debug_mode' => TRUE,
    'admin_url' => 'admin',
    'uri_protocol' => 'AUTO',
    'installed' => '1',
    // Database Settings 
    'db' => array(
        //'dsn' => 'mysql:host=localhost;port=3306;dbname=mw1',
        'dsn' => 'sqlite:application/db/default.db',
        'user' => '',     
        'pass' => '',   
        'args' => array(   
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
            //
            // If using
            // MySQL, force UTF-8 
            // Cookie options
            'cookie' => array( 
                'key' => md5(__FILE__),
                'expires' => time() + 60 * 5, //
                'path' => '/',
                'domain' => '',
                'secure' => '1',
                'httponly' => ''
            )
        )
    )
);















































$cms_db_tables = array();
$cms_db_tables['table_cache'] = TABLE_PREFIX . 'cache';
$cms_db_tables['table_content'] = TABLE_PREFIX . 'content';
$cms_db_tables['table_taxonomy'] = TABLE_PREFIX . 'taxonomy';
$cms_db_tables['table_taxonomy_items'] = TABLE_PREFIX . 'taxonomy_items';
$cms_db_tables['table_menus'] = TABLE_PREFIX . 'menus';
$cms_db_tables['table_options'] = TABLE_PREFIX . 'options';
$cms_db_tables['table_media'] = TABLE_PREFIX . 'media';
$cms_db_tables['table_geodata'] = TABLE_PREFIX . 'geodata';
$cms_db_tables['table_comments'] = TABLE_PREFIX . 'comments';
$cms_db_tables['table_votes'] = TABLE_PREFIX . 'votes';
$cms_db_tables['table_users'] = TABLE_PREFIX . 'users';
$cms_db_tables['table_messages'] = TABLE_PREFIX . 'messages';
$cms_db_tables['table_users_log'] = TABLE_PREFIX . 'users_log';
$cms_db_tables['table_users_statuses'] = TABLE_PREFIX . 'users_statuses';
$cms_db_tables['table_stats_sites'] = TABLE_PREFIX . 'statssite';
$cms_db_tables['table_users_notifications'] = TABLE_PREFIX . 'users_notifications';
$cms_db_tables['table_users_statuses'] = TABLE_PREFIX . 'users_statuses';
$cms_db_tables['table_followers'] = TABLE_PREFIX . 'followers';
$cms_db_tables['table_sessions'] = TABLE_PREFIX . 'sessions';
$cms_db_tables['table_custom_fields'] = TABLE_PREFIX . 'content_custom_fields';
$cms_db_tables['table_custom_fields_config'] = TABLE_PREFIX . 'content_custom_fields_config';
$cms_db_tables['table_cart'] = TABLE_PREFIX . 'cart';
$cms_db_tables['table_cart_orders'] = TABLE_PREFIX . 'cart_orders';

$cms_db_tables['table_modules'] = TABLE_PREFIX . 'modules';


$cms_db_tables['table_cart_promo_codes'] = TABLE_PREFIX . 'cart_promo_codes';
$cms_db_tables['table_countries'] = TABLE_PREFIX . 'countries';
$cms_db_tables['table_cart_orders_shipping_cost'] = TABLE_PREFIX . 'cart_orders_shipping_cost';
$cms_db_tables['table_cart_currency'] = TABLE_PREFIX . 'cart_currency';
$cms_db_tables['table_reports'] = TABLE_PREFIX . 'reports';

$cms_db_tables['table_forms'] = TABLE_PREFIX . "forms";
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
$cms_db_tables_search_fields = array('title', "content", "content_url");
return $config;