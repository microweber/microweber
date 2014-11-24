<?php
namespace Microweber;

if (!defined("MW_DB_TABLE_LOG")) {
    define('MW_DB_TABLE_LOG', MW_TABLE_PREFIX . 'log');
}


class Log
{

    public $app;

    function __construct($app=null)
    {

        if (!is_object($this->app)) {

            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = Application::getInstance();
            }

        }


    }

    public function get_entry_by_id($id)
    {

        $params = array();
        $params['id'] = intval($id);
        $params['one'] = true;

        $get = $this->get($params);
        return $get;

    }

    public function get($params)
    {
        $params = parse_params($params);
        $table = MW_DB_TABLE_LOG;
        $params['table'] = $table;

        if (is_admin() == false) {
            $params['user_ip'] = MW_USER_IP;
        }

        $q = $this->app->database->get($params);

        return $q;
    }

    public function reset()
    {
        $adm = $this->app->user->is_admin();
        if ($adm == false) {
            return array('error' => 'Error: not logged in as admin.' . __FILE__ . __LINE__);
        }

        $table = MW_DB_TABLE_LOG;

        $q = "DELETE FROM $table ";

        $cg = guess_cache_group($table);

        $this->app->cache->delete($cg);
        $q = $this->app->database->q($q);
        return array('success' => 'System log is cleaned up.');

        //return $data;
    }

    public function delete($params)
    {
        $params = parse_params($params);
        $table = MW_DB_TABLE_LOG;
        $params['table'] = $table;

        if (is_admin() == false) {
            $params['user_ip'] = MW_USER_IP;
        }

        $q = $this->app->database->get($params);
        if (is_array($q)) {
            foreach ($q as $val) {
                $c_id = intval($val['id']);
                $this->app->database->delete_by_id('log', $c_id);
            }

        }
        $this->app->cache->delete('log' . DIRECTORY_SEPARATOR . 'global');
        return true;
    }

    public function save($params)
    {
        $params = parse_params($params);

        $params['user_ip'] = MW_USER_IP;
        $data_to_save = $params;
        $table = MW_DB_TABLE_LOG;
        mw_var('FORCE_SAVE', $table);
        $save = $this->app->database->save($table, $params);
        $id = $save;
        $this->app->cache->delete('log' . DIRECTORY_SEPARATOR . 'global');
        return $id;
    }

    public function delete_entry($data)
    {
        $adm = $this->app->user->is_admin();
        if ($adm == false) {
            return array('error' => 'Error: not logged in as admin.' . __FILE__ . __LINE__);
        }

        if (isset($data['id'])) {
            $c_id = intval($data['id']);
            $this->app->database->delete_by_id('log', $c_id);
            $table = MW_DB_TABLE_LOG;
            $old = date("Y-m-d H:i:s", strtotime('-1 month'));
            $q = "DELETE FROM $table WHERE created_on < '{$old}'";

            $q = $this->app->database->q($q);

            return $c_id;

        }
        return $data;
    }
}