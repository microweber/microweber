<?php

namespace Microweber\Utils;

if (!defined("MODULE_DB_IP2COUNTRY")) {
    define('MODULE_DB_IP2COUNTRY', MW_TABLE_PREFIX . 'ip2country');
}

 
class Ip2Country
{

    public $app;
    function __construct($app=null)
    {
      

        if (!is_object($this->app)) {

            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = \Microweber\Application::getInstance();
            }

        }
$this->db_init();
    }
    public function get($ip = false, $key = 'country_name')
    {
        if ($ip == false) {
            $ip = MW_USER_IP;
        }


        $table = MODULE_DB_IP2COUNTRY;
        $params = array();
        $params['table'] = $table;
        $params['ip'] = $ip;
        $params['limit'] = 1;
        $get = $this->app->db->get($params);
        if ($get == false) {
            $remote_host = 'http://api.microweber.net';
            $service = "/service/ip2country/?ip=" . $ip;
            $remote_host_s = $remote_host . $service;
            //d($remote_host_s);
            $get_remote = false;
            $get_remote = @mw('url')->download($remote_host_s);

            if ($get_remote != false) {
                $get_remote = json_decode($get_remote, 1);
                if ($get_remote != false) {

                    $params = $get = $get_remote;
                    $params['ip'] = $ip;

                    if (isset($params['country_name']) and $params['country_name'] == '') {
                        $params['country_name'] = "Unknown";

                    } else if (!isset($params['country_name'])) {
                        $params['country_name'] = "Unknown";
                    }
                    //d($params);
                    $s = $this->app->db->save($table, $params);
                    $get = $params;
                }
            }


        } else {
            $get = $get[0];
        }
        if (isset($get[$key])) {
            return $get[$key];
        }

    }


    /**
     * Creates the content tables in the database.
     *
     * It is executed on install and on update
     *
     * @function mw_db_init_content_table
     * @category Content
     * @package Content
     * @subpackage  Advanced
     * @uses \mw('db')->build_table()
     */
    public function db_init()
    {
        $function_cache_id = false;

        $args = func_get_args();

        foreach ($args as $k => $v) {

            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
        }

        $function_cache_id = 'ip2country_' . __FUNCTION__ . crc32($function_cache_id);

        $cache_content = $this->app->cache->get($function_cache_id, 'db');

        if (($cache_content) != false) {

            return $cache_content;
        }

        $table_name = MODULE_DB_IP2COUNTRY;

        $fields_to_add = array();
        $fields_to_add[] = array('ip', 'TEXT default NULL');
        $fields_to_add[] = array('ip_long', 'TEXT default NULL');
        $fields_to_add[] = array('country_code', 'TEXT default NULL');
        $fields_to_add[] = array('country_name', 'TEXT default NULL');
        $fields_to_add[] = array('region', 'TEXT default NULL');
        $fields_to_add[] = array('city', 'TEXT default NULL');
        $fields_to_add[] = array('latitude', 'TEXT default NULL');
        $fields_to_add[] = array('longitude', 'TEXT default NULL');
        \mw('db')->build_table($table_name, $fields_to_add);
        $this->app->cache->save(true, $function_cache_id, $cache_group = 'db');



    }
}
